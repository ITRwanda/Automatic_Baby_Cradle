/**
 * IoT Baby Cradle — ESP8266 NodeMCU
 * Cry detection (sound sensor) + DHT11 monitoring + Laravel API
 *
 * v3 — INCIDENT ONLY
 *  - Removed heartbeat posts (no routine payloads).
 *  - Payloads sent only when cry detected or DHT thresholds exceeded.
 *  - Cooldowns prevent repeated spamming.
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// ─── WiFi credentials ───────────────────────────────
const char* WIFI_SSID     = "ITProfessional";
const char* WIFI_PASSWORD = "ITPro@250";

// ─── Server settings ────────────────────────────────
const char* SERVER_HOST   = "http://192.168.1.87:8000";
const char* API_ENDPOINT  = "/api/device-activities";
const char* DEVICE_TOKEN  = "dd332414-fed9-46c7-9cf4-f5a5c9c1ef17";

// ─── Pin definitions ────────────────────────────────
#define SOUND_ANALOG_PIN  A0
#define DHT_PIN           D4
#define DHT_TYPE          DHT11
#define BUZZER_PIN        D6
#define LED_RED_PIN       D7
#define LED_YELLOW_PIN    D8
#define LED_GREEN_PIN     D3

// ─── Thresholds ─────────────────────────────────────
const int   CRY_THRESHOLD = 600;
const float TEMP_HIGH     = 35.0;
const float TEMP_LOW      = 16.0;
const float HUMID_HIGH    = 80.0;
const float HUMID_LOW     = 30.0;

// ─── Timing constants (ms) ──────────────────────────
const unsigned long CRY_COOLDOWN     = 10000;
const unsigned long DHT_COOLDOWN     = 15000;
const unsigned long ALERT_DURATION   = 3000;  // cry alert (red)
const unsigned long WARNING_DURATION = 500;   // DHT warning (yellow)
const unsigned long SOUND_LOG_INTERVAL = 2000;

// ─── Globals ────────────────────────────────────────
DHT dht(DHT_PIN, DHT_TYPE);

unsigned long lastCryAlert   = 0;
unsigned long lastDhtAlert   = 0;
unsigned long lastSoundLog   = 0;

bool greenLedState = false;

enum IndicatorType { IND_NONE, IND_CRY, IND_DHT };
IndicatorType indicatorType   = IND_NONE;
unsigned long indicatorEndsAt = 0;

// ─── Helpers ────────────────────────────────────────
void stopOutputs() {
  digitalWrite(BUZZER_PIN, LOW);
  digitalWrite(LED_RED_PIN, LOW);
  digitalWrite(LED_YELLOW_PIN, LOW);
  indicatorType = IND_NONE;
  Serial.println(F("[Alert] Cleared."));
}

void blinkGreen() {
  greenLedState = true;
  digitalWrite(LED_GREEN_PIN, HIGH);
  delay(150);
  greenLedState = false;
  digitalWrite(LED_GREEN_PIN, LOW);
}

void startIndicator(IndicatorType type, unsigned long durationMs) {
  if (indicatorType == IND_CRY && type == IND_DHT) return; // cry wins

  indicatorType   = type;
  indicatorEndsAt = millis() + durationMs;

  digitalWrite(LED_RED_PIN,    type == IND_CRY ? HIGH : LOW);
  digitalWrite(LED_YELLOW_PIN, type == IND_DHT ? HIGH : LOW);
  digitalWrite(BUZZER_PIN, HIGH);

  if (type == IND_CRY) {
    Serial.println(F("[Output] Alert ON (red LED + buzzer)"));
  } else {
    Serial.println(F("[Output] Warning ON (yellow LED + buzzer)"));
  }
}

void updateIndicator(unsigned long now) {
  if (indicatorType != IND_NONE && now >= indicatorEndsAt) {
    stopOutputs();
  }
}

bool readDHT(float& temp, float& hum) {
  temp = dht.readTemperature();
  hum  = dht.readHumidity();
  return (!isnan(temp) && !isnan(hum));
}

bool postActivity(const char* eventType, const String& payload) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println(F("[HTTP] Skipped — WiFi not connected"));
    return false;
  }

  WiFiClient client;
  HTTPClient http;
  String url = String(SERVER_HOST) + String(API_ENDPOINT);

  if (!http.begin(client, url)) {
    Serial.println(F("[HTTP] begin() failed"));
    return false;
  }

  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");

  StaticJsonDocument<512> doc;
  doc["device_token"] = DEVICE_TOKEN;
  doc["event_type"]   = eventType;
  doc["payload"]      = payload;

  String body;
  serializeJson(doc, body);

  int httpCode = http.POST(body);

  if (httpCode > 0) {
    Serial.printf("[HTTP] Status: %d\n", httpCode);
    Serial.println(http.getString());
  } else {
    Serial.printf("[HTTP] Failed: %d\n", httpCode);
  }

  http.end();
  return (httpCode >= 200 && httpCode < 300);
}

String buildDhtPayload(float temp, float hum, bool tempAlert, bool humidAlert) {
  StaticJsonDocument<128> doc;
  doc["temperature"] = temp;
  doc["humidity"]    = hum;
  doc["temp_alert"]  = tempAlert;
  doc["humid_alert"] = humidAlert;
  String payload;
  serializeJson(doc, payload);
  return payload;
}

void connectWiFi() {
  Serial.print(F("[WiFi] Connecting to "));
  Serial.println(WIFI_SSID);

  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  unsigned long start = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - start < 20000) {
    greenLedState = !greenLedState;
    digitalWrite(LED_GREEN_PIN, greenLedState);
    delay(500);
  }
  digitalWrite(LED_GREEN_PIN, LOW);

  if (WiFi.status() == WL_CONNECTED) {
    Serial.print(F("[WiFi] Connected. IP: "));
    Serial.println(WiFi.localIP());
  } else {
    Serial.println(F("[WiFi] Connection failed / timed out."));
  }
}

// ─── Setup ──────────────────────────────────────────
void setup() {
  Serial.begin(9600);
  delay(200);
  Serial.println();
  Serial.println(F(">>> RUNNING VERSION 3 -- INCIDENT ONLY <<<"));

  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_RED_PIN, OUTPUT);
  pinMode(LED_YELLOW_PIN, OUTPUT);
  pinMode(LED_GREEN_PIN, OUTPUT);

  stopOutputs();
  dht.begin();
  connectWiFi();
  Serial.println(F("[IoTBabyCradle] Ready."));
}

// ─── Main Loop ──────────────────────────────────────
void loop() {
  unsigned long now = millis();
  updateIndicator(now);

  // Sound sensor — cry detection
  int soundLevel = analogRead(SOUND_ANALOG_PIN);

  if (now - lastSoundLog >= SOUND_LOG_INTERVAL) {
    lastSoundLog = now;
    Serial.printf("[Sound] Level: %d\n", soundLevel);
  }

  if (soundLevel >= CRY_THRESHOLD && (now - lastCryAlert >= CRY_COOLDOWN)) {
    lastCryAlert = now;
    Serial.println(F("[INCIDENT] Cry detected!"));

    StaticJsonDocument<64> doc;
    doc["sound_level"] = soundLevel;
    String payload;
    serializeJson(doc, payload);
    if (postActivity("cry_detected", payload)) blinkGreen();
    startIndicator(IND_CRY, ALERT_DURATION);
  }

  // DHT11 sensor — temperature & humidity
  float temperature, humidity;
  if (readDHT(temperature, humidity)) {
    bool tempAlert   = (temperature > TEMP_HIGH || temperature < TEMP_LOW);
    bool humidAlert  = (humidity > HUMID_HIGH || humidity < HUMID_LOW);
    bool dhtIncident = tempAlert || humidAlert;

    if (dhtIncident && (now - lastDhtAlert >= DHT_COOLDOWN)) {
      lastDhtAlert = now;
      Serial.println(F("[INCIDENT] DHT threshold exceeded!"));
      String payload = buildDhtPayload(temperature, humidity, tempAlert, humidAlert);
      if (postActivity("dht", payload)) blinkGreen();
      startIndicator(IND_DHT, WARNING_DURATION);
    }
  } else {
    Serial.println(F("[DHT] Read failed (NaN) — check wiring/pin."));
  }

  if (WiFi.status() != WL_CONNECTED) connectWiFi();
  delay(500);
}
