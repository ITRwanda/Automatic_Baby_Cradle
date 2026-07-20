/**
 * IoT Baby Cradle — ESP8266 NodeMCU
 * Cry detection (sound sensor) + DHT11 monitoring + Laravel API
 *
 * v5 — AUTO-CALIBRATED SOUND THRESHOLD
 *  - Fixes constant "446" false readings by measuring the real
 *    silence baseline at boot instead of using a guessed fixed number
 *  - Cry threshold = baseline + margin (adaptive to YOUR sensor/room)
 *  - Clear serial logging when it goes back to "listening" after a cry
 *  - Everything else identical to v4 (safe pins, DHT interval fix)
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// ─── WiFi credentials ───────────────────────────────
const char* WIFI_SSID     = "VAVA";
const char* WIFI_PASSWORD = "bora@250";

// ─── Server settings ────────────────────────────────
const char* SERVER_HOST   = "http://192.168.137.1:8000";
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

// ─── Calibration settings ───────────────────────────
const int   CAL_SAMPLES   = 200;   // number of readings taken at boot
const int   CAL_DELAY_MS  = 10;    // spacing between samples (~2s total)
const int   CRY_MARGIN    = 2;   // how far above baseline counts as a cry
                                    // increase if still too sensitive,
                                    // decrease if it misses real cries

int   soundBaseline  = 0;          // measured "silence" level
int   CRY_THRESHOLD  = 0;          // computed = baseline + CRY_MARGIN

// ─── DHT thresholds ─────────────────────────────────
const float TEMP_HIGH     = 35.0;
const float TEMP_LOW      = 16.0;
const float HUMID_HIGH    = 80.0;
const float HUMID_LOW     = 30.0;

// ─── Timing constants (ms) ──────────────────────────
const unsigned long CRY_COOLDOWN       = 10000;
const unsigned long DHT_COOLDOWN       = 15000;
const unsigned long DHT_READ_INTERVAL  = 2500;
const unsigned long ALERT_DURATION     = 3000;
const unsigned long WARNING_DURATION   = 500;
const unsigned long SOUND_LOG_INTERVAL = 2000;

// ─── Globals ────────────────────────────────────────
DHT dht(DHT_PIN, DHT_TYPE);

unsigned long lastCryAlert = 0;
unsigned long lastDhtAlert = 0;
unsigned long lastSoundLog = 0;
unsigned long lastDhtRead  = 0;

float lastTemp = NAN;
float lastHum  = NAN;

bool greenLedState = false;
bool wasListening  = true;   // tracks state transitions for logging

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
  if (indicatorType == IND_CRY && type == IND_DHT) return;

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

// Measures the real "silence" noise floor of the sound sensor and
// sets CRY_THRESHOLD relative to it. Keep the room quiet during boot.
void calibrateSound() {
  Serial.println(F("[Calibrate] Measuring silence baseline... keep quiet."));

  long sum = 0;
  int  minVal = 4095, maxVal = 0;

  for (int i = 0; i < CAL_SAMPLES; i++) {
    int v = analogRead(SOUND_ANALOG_PIN);
    sum += v;
    if (v < minVal) minVal = v;
    if (v > maxVal) maxVal = v;
    delay(CAL_DELAY_MS);
  }

  soundBaseline = sum / CAL_SAMPLES;
  CRY_THRESHOLD = soundBaseline + CRY_MARGIN;

  Serial.printf("[Calibrate] Baseline avg=%d  min=%d  max=%d\n",
                soundBaseline, minVal, maxVal);
  Serial.printf("[Calibrate] Cry threshold set to %d (baseline + %d)\n",
                CRY_THRESHOLD, CRY_MARGIN);
}

// ─── Setup ──────────────────────────────────────────
void setup() {
  Serial.begin(9600);
  delay(200);
  Serial.println();
  Serial.println(F(">>> RUNNING VERSION 5 -- AUTO-CALIBRATED SOUND <<<"));

  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_RED_PIN, OUTPUT);
  pinMode(LED_YELLOW_PIN, OUTPUT);
  pinMode(LED_GREEN_PIN, OUTPUT);

  stopOutputs();
  calibrateSound();     // <-- establishes real baseline before sensing starts
  dht.begin();
  connectWiFi();
  Serial.println(F("[IoTBabyCradle] Ready. Listening..."));
}

// ─── Main Loop ──────────────────────────────────────
void loop() {
  unsigned long now = millis();
  updateIndicator(now);

  // ── Sound sensor — cry detection ──
  int soundLevel = analogRead(SOUND_ANALOG_PIN);

  if (now - lastSoundLog >= SOUND_LOG_INTERVAL) {
    lastSoundLog = now;
    Serial.printf("[Sound] Level: %d  (threshold: %d)\n", soundLevel, CRY_THRESHOLD);
  }

  bool inCooldown = (now - lastCryAlert < CRY_COOLDOWN);

  if (soundLevel >= CRY_THRESHOLD && !inCooldown) {
    lastCryAlert = now;
    wasListening = false;
    Serial.println(F("[INCIDENT] Cry detected!"));

    StaticJsonDocument<64> doc;
    doc["sound_level"] = soundLevel;
    String payload;
    serializeJson(doc, payload);
    if (postActivity("cry_detected", payload)) blinkGreen();
    startIndicator(IND_CRY, ALERT_DURATION);
  }

  // Announce once when cooldown ends and it's back to normal sensing
  if (!wasListening && !inCooldown) {
    wasListening = true;
    Serial.println(F("[Sound] Reset — back to listening."));
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
