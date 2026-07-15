/**
 * IoT Baby Cradle — ESP8266 NodeMCU
 * Cry detection (sound sensor) + DHT11 monitoring + Laravel API
 *
 * v4 — SAFE PINS + DHT READ-INTERVAL FIX
 *  - Moved all digital I/O off boot-strapping pins (D0, D3, D4, D8)
 *  - DHT11 now reads on its own 2.5s interval, decoupled from the
 *    500ms main loop delay (fixes NaN read failures)
 *  - Still incident-only posting: nothing sent to the server unless
 *    a cry is detected or a DHT threshold is exceeded
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// ─── WiFi credentials ───────────────────────────────
const char* WIFI_SSID     = "itel V52A";
const char* WIFI_PASSWORD = "hx3xhh2x8v523gt";

// ─── Server settings ────────────────────────────────
const char* SERVER_HOST   = "http://192.168.201.236:8000";
const char* API_ENDPOINT  = "/api/device-activities";
const char* DEVICE_TOKEN  = "dd332414-fed9-46c7-9cf4-f5a5c9c1ef17";

// ─── Pin definitions (all boot-safe — no D0/D3/D4/D8) ──
#define SOUND_ANALOG_PIN  A0
#define DHT_PIN           D1   // GPIO5
#define DHT_TYPE          DHT11
#define BUZZER_PIN        D2   // GPIO4
#define LED_RED_PIN       D5   // GPIO14
#define LED_YELLOW_PIN    D6   // GPIO12
#define LED_GREEN_PIN     D7   // GPIO13

// ─── Thresholds ─────────────────────────────────────
const int   CRY_THRESHOLD = 600;
const float TEMP_HIGH     = 35.0;
const float TEMP_LOW      = 16.0;
const float HUMID_HIGH    = 80.0;
const float HUMID_LOW     = 30.0;

// ─── Timing constants (ms) ──────────────────────────
const unsigned long CRY_COOLDOWN       = 10000;
const unsigned long DHT_COOLDOWN       = 15000;
const unsigned long DHT_READ_INTERVAL  = 2500;  // DHT11 needs >=1-2s between reads
const unsigned long ALERT_DURATION     = 3000;  // cry alert (red)
const unsigned long WARNING_DURATION   = 500;   // DHT warning (yellow)
const unsigned long SOUND_LOG_INTERVAL = 2000;

// ─── Globals ────────────────────────────────────────
DHT dht(DHT_PIN, DHT_TYPE);

unsigned long lastCryAlert = 0;
unsigned long lastDhtAlert = 0;
unsigned long lastSoundLog = 0;
unsigned long lastDhtRead  = 0;

// Cache the most recent DHT reading so it's available between read intervals
float lastTemp = NAN;
float lastHum  = NAN;

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

// Reads DHT only if DHT_READ_INTERVAL has elapsed; updates lastTemp/lastHum.
// Returns true if a fresh, valid reading was taken this call.
bool maybeReadDHT(unsigned long now, float& temp, float& hum) {
  if (now - lastDhtRead < DHT_READ_INTERVAL) return false;
  lastDhtRead = now;

  float t = dht.readTemperature();
  float h = dht.readHumidity();

  if (isnan(t) || isnan(h)) {
    Serial.println(F("[DHT] Read failed (NaN) — check wiring/pin/power."));
    return false;
  }

  lastTemp = t;
  lastHum  = h;
  temp = t;
  hum  = h;
  return true;
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
    Serial.printf("[HTTP] Failed: %d (%s)\n", httpCode, http.errorToString(httpCode).c_str());
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
  Serial.println(F(">>> RUNNING VERSION 4 -- SAFE PINS + DHT FIX <<<"));

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

  // ── Sound sensor — cry detection ──
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

  // ── DHT11 sensor — temperature & humidity (rate-limited internally) ──
  float temperature, humidity;
  if (maybeReadDHT(now, temperature, humidity)) {
    bool tempAlert   = (temperature > TEMP_HIGH || temperature < TEMP_LOW);
    bool humidAlert  = (humidity > HUMID_HIGH || humidity < HUMID_LOW);
    bool dhtIncident = tempAlert || humidAlert;

    Serial.printf("[DHT] Temp: %.1fC  Hum: %.1f%%\n", temperature, humidity);

    if (dhtIncident && (now - lastDhtAlert >= DHT_COOLDOWN)) {
      lastDhtAlert = now;
      Serial.println(F("[INCIDENT] DHT threshold exceeded!"));
      String payload = buildDhtPayload(temperature, humidity, tempAlert, humidAlert);
      if (postActivity("dht", payload)) blinkGreen();
      startIndicator(IND_DHT, WARNING_DURATION);
    }
  }

  if (WiFi.status() != WL_CONNECTED) connectWiFi();
  delay(500);
}
