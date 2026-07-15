/**
 * IoT Baby Cradle — ESP8266 NodeMCU
 * Cry detection (sound sensor) + DHT11 monitoring + Laravel API
 *
 * Fixes applied vs. original:
 *  - Green LED state tracked in a variable instead of digitalRead()
 *    on an output pin (unreliable on some ESP8266 cores).
 *  - Cry alert and DHT warning unified into a single indicator
 *    state machine so they no longer override each other's LED/buzzer.
 *    Cry alert takes priority over a DHT warning.
 *  - DHT payload built once per loop and reused for both the
 *    heartbeat post and the incident post (was built twice).
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// ─── WiFi credentials ───────────────────────────────
const char* WIFI_SSID     = "ITProfessional";
const char* WIFI_PASSWORD = "ITPro@250";

// ─── Server settings ────────────────────────────────
const char* SERVER_HOST   = "http://192.168.191.236:8000";
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
const unsigned long HEARTBEAT_INTERVAL = 30000;
const unsigned long CRY_COOLDOWN       = 10000;
const unsigned long DHT_COOLDOWN       = 15000;
const unsigned long ALERT_DURATION     = 3000;  // cry alert (red)
const unsigned long WARNING_DURATION   = 500;   // DHT warning (yellow)

// ─── Globals ────────────────────────────────────────
DHT dht(DHT_PIN, DHT_TYPE);

unsigned long lastCryAlert  = 0;
unsigned long lastDhtAlert  = 0;
unsigned long lastHeartbeat = 0;

bool greenLedState = false;

// Unified indicator state machine (replaces separate alert/warning flags)
enum IndicatorType { IND_NONE, IND_CRY, IND_DHT };
IndicatorType indicatorType   = IND_NONE;
unsigned long indicatorEndsAt = 0;

// ─── Helpers ────────────────────────────────────────
void stopOutputs() {
  digitalWrite(BUZZER_PIN, LOW);
  digitalWrite(LED_RED_PIN, LOW);
  digitalWrite(LED_YELLOW_PIN, LOW);
  indicatorType = IND_NONE;
}

void blinkGreen() {
  greenLedState = true;
  digitalWrite(LED_GREEN_PIN, HIGH);
  delay(150);
  greenLedState = false;
  digitalWrite(LED_GREEN_PIN, LOW);
}

// type = IND_CRY (red, higher priority) or IND_DHT (yellow).
// A cry alert in progress cannot be interrupted by a DHT warning;
// a DHT warning can be interrupted by a cry alert.
void startIndicator(IndicatorType type, unsigned long durationMs) {
  if (indicatorType == IND_CRY && type == IND_DHT) return; // cry wins

  indicatorType   = type;
  indicatorEndsAt = millis() + durationMs;

  digitalWrite(LED_RED_PIN,    type == IND_CRY ? HIGH : LOW);
  digitalWrite(LED_YELLOW_PIN, type == IND_DHT ? HIGH : LOW);
  digitalWrite(BUZZER_PIN, HIGH);
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
  if (WiFi.status() != WL_CONNECTED) return false;

  WiFiClient client;
  HTTPClient http;
  String url = String(SERVER_HOST) + String(API_ENDPOINT);
  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");

  StaticJsonDocument<512> doc;
  doc["device_token"] = DEVICE_TOKEN;
  doc["event_type"]   = eventType;
  doc["payload"]      = payload;

  String body;
  serializeJson(doc, body);

  int httpCode = http.POST(body);
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
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  unsigned long start = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - start < 20000) {
    greenLedState = !greenLedState;
    digitalWrite(LED_GREEN_PIN, greenLedState);
    delay(500);
  }
  greenLedState = false;
  digitalWrite(LED_GREEN_PIN, LOW);
}

// ─── Setup ──────────────────────────────────────────
void setup() {
  Serial.begin(115200);
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
  if (soundLevel >= CRY_THRESHOLD && (now - lastCryAlert >= CRY_COOLDOWN)) {
    lastCryAlert = now;
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

    bool needHeartbeat = (now - lastHeartbeat >= HEARTBEAT_INTERVAL);
    bool needIncident   = dhtIncident && (now - lastDhtAlert >= DHT_COOLDOWN);

    // Build the payload once and reuse it for whichever post(s) fire this pass
    if (needHeartbeat || needIncident) {
      String payload = buildDhtPayload(temperature, humidity, tempAlert, humidAlert);

      if (needHeartbeat) {
        lastHeartbeat = now;
        if (postActivity("dht", payload)) blinkGreen();
      }
      if (needIncident) {
        lastDhtAlert = now;
        if (postActivity("dht", payload)) blinkGreen();
        startIndicator(IND_DHT, WARNING_DURATION);
      }
    }
  }

  if (WiFi.status() != WL_CONNECTED) connectWiFi();
  delay(500);
}
