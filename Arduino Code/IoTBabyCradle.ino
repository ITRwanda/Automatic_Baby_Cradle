/**
 * ============================================================
 *  IoT Baby Cradle — Arduino / ESP8266 (NodeMCU / Wemos D1)
 * ============================================================
 *
 * Hardware:
 *   - Sound sensor  (analog + digital) → A0  / D5
 *   - DHT22 (or DHT11)                 → D4
 *   - Buzzer (active)                  → D6
 *   - Red LED   — critical alert       → D7
 *   - Yellow LED — warning             → D8
 *   - Green LED  — status / heartbeat  → D3
 *
 * Behaviour:
 *   1. Continuously reads sound level and DHT temperature/humidity.
 *   2. If sound exceeds CRY_THRESHOLD  → "cry_detected" event sent
 *      to server; buzzer + red LED fire for ALERT_DURATION ms.
 *   3. If temperature > TEMP_HIGH or humidity > HUMID_HIGH
 *      → "dht" event sent (payload carries readings);
 *      yellow LED + short buzzer beep warns the caregiver.
 *   4. Every HEARTBEAT_INTERVAL ms a "dht" event is always sent
 *      so the server has a regular sensor log even with no incident.
 *   5. Green LED blinks once after every successful HTTP post.
 *
 * Wiring notes:
 *   - DHT data pin needs a 10 kΩ pull-up to 3.3 V.
 *   - LEDs need 220–330 Ω series resistors.
 *   - Active buzzer: HIGH = on.
 *   - Sound sensor: analog output on A0 (0–1023 on NodeMCU).
 *
 * Server API (Laravel):
 *   POST  http://<SERVER_HOST>/api/device-activities
 *   Body  { device_token, event_type, payload }
 *   No authentication header required — device_token IS the credential.
 *
 * ============================================================
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// ─── WiFi credentials ───────────────────────────────────────
const char* WIFI_SSID     = "YOUR_WIFI_SSID";
const char* WIFI_PASSWORD = "YOUR_WIFI_PASSWORD";

// ─── Server settings ────────────────────────────────────────
// Replace with your Laravel server IP or domain.
// When running via XAMPP on localhost use the machine's LAN IP,
// e.g. "192.168.1.100". Port 80 assumed; add :8080 if needed.
const char* SERVER_HOST   = "http://192.168.1.100";
const char* API_ENDPOINT  = "/api/device-activities";

// Device token assigned by the admin in the Laravel dashboard.
// Copy the UUID exactly as shown in the Devices table.
const char* DEVICE_TOKEN  = "YOUR_DEVICE_TOKEN_UUID";

// ─── Pin definitions ────────────────────────────────────────
#define SOUND_ANALOG_PIN  A0   // Analog sound level (0-1023)
#define SOUND_DIGITAL_PIN D5   // Digital threshold output from sensor
#define DHT_PIN           D4   // DHT data pin
#define DHT_TYPE          DHT22
#define BUZZER_PIN        D6
#define LED_RED_PIN       D7   // Critical alert (cry / high temp)
#define LED_YELLOW_PIN    D8   // Warning
#define LED_GREEN_PIN     D3   // Status / heartbeat OK

// ─── Thresholds ─────────────────────────────────────────────
// Adjust CRY_THRESHOLD for your sound sensor and environment.
// NodeMCU A0 range: 0–1023 (maps the sensor's 0–3.3 V range).
const int   CRY_THRESHOLD        = 600;   // analog value (0-1023)
const float TEMP_HIGH            = 35.0;  // °C — alert above this
const float TEMP_LOW             = 16.0;  // °C — alert below this
const float HUMID_HIGH           = 80.0;  // % RH — alert above this
const float HUMID_LOW            = 30.0;  // % RH — alert below this

// ─── Timing constants (ms) ──────────────────────────────────
const unsigned long HEARTBEAT_INTERVAL  = 30000;  // 30 s — periodic DHT post
const unsigned long CRY_COOLDOWN        = 10000;  // 10 s — min gap between cry alerts
const unsigned long DHT_COOLDOWN        = 15000;  // 15 s — min gap between DHT alerts
const unsigned long ALERT_DURATION      = 3000;   // 3 s  — buzzer/LED on time
const unsigned long BEEP_DURATION       = 500;    // 0.5 s — short warning beep

// ─── Globals ────────────────────────────────────────────────
DHT dht(DHT_PIN, DHT_TYPE);

unsigned long lastCryAlert      = 0;
unsigned long lastDhtAlert      = 0;
unsigned long lastHeartbeat     = 0;
unsigned long alertEndTime      = 0;
bool          alertActive       = false;
bool          warningActive     = false;
unsigned long warningEndTime    = 0;

// ─── Forward declarations ────────────────────────────────────
bool  postActivity(const char* eventType, const String& payload);
void  triggerAlert();
void  triggerWarning();
void  stopOutputs();
void  blinkGreen();
void  connectWiFi();
bool  readDHT(float& temp, float& hum);

// ============================================================
//  SETUP
// ============================================================
void setup() {
    Serial.begin(115200);
    Serial.println(F("\n[IoTBabyCradle] Booting..."));

    // Pin modes
    pinMode(SOUND_DIGITAL_PIN, INPUT);
    pinMode(BUZZER_PIN,        OUTPUT);
    pinMode(LED_RED_PIN,       OUTPUT);
    pinMode(LED_YELLOW_PIN,    OUTPUT);
    pinMode(LED_GREEN_PIN,     OUTPUT);

    // All outputs off at start
    stopOutputs();

    // Startup visual feedback — blink all LEDs once
    digitalWrite(LED_RED_PIN,    HIGH);
    digitalWrite(LED_YELLOW_PIN, HIGH);
    digitalWrite(LED_GREEN_PIN,  HIGH);
    delay(500);
    stopOutputs();

    dht.begin();

    connectWiFi();

    Serial.println(F("[IoTBabyCradle] Ready."));
}

// ============================================================
//  MAIN LOOP
// ============================================================
void loop() {
    unsigned long now = millis();

    // ── Turn off timed alert / warning ──────────────────────
    if (alertActive && now >= alertEndTime) {
        stopOutputs();
        alertActive = false;
        Serial.println(F("[Alert] Cleared."));
    }
    if (warningActive && now >= warningEndTime) {
        stopOutputs();
        warningActive = false;
        Serial.println(F("[Warning] Cleared."));
    }

    // ── Sound sensor ────────────────────────────────────────
    int soundLevel = analogRead(SOUND_ANALOG_PIN);
    Serial.print(F("[Sound] Level: "));
    Serial.println(soundLevel);

    bool cryDetected = (soundLevel >= CRY_THRESHOLD) ||
                       (digitalRead(SOUND_DIGITAL_PIN) == HIGH);

    if (cryDetected && (now - lastCryAlert >= CRY_COOLDOWN)) {
        lastCryAlert = now;
        Serial.println(F("[INCIDENT] Cry detected!"));

        // Build payload
        String payload = "{\"sound_level\":" + String(soundLevel) + "}";

        // POST to server (notifies caregiver + family parent via email)
        bool ok = postActivity("cry_detected", payload);

        if (ok) {
            blinkGreen();
        }

        // Local alert regardless of HTTP success — always alert locally
        triggerAlert();
    }

    // ── DHT sensor ──────────────────────────────────────────
    float temperature = NAN;
    float humidity    = NAN;
    bool  dhtOk       = readDHT(temperature, humidity);

    if (dhtOk) {
        bool tempAlert  = (temperature > TEMP_HIGH || temperature < TEMP_LOW);
        bool humidAlert = (humidity    > HUMID_HIGH || humidity    < HUMID_LOW);
        bool dhtIncident = tempAlert || humidAlert;

        // ── Periodic heartbeat post (always) ────────────────
        if (now - lastHeartbeat >= HEARTBEAT_INTERVAL) {
            lastHeartbeat = now;

            String payload = buildDhtPayload(temperature, humidity,
                                             tempAlert, humidAlert);
            bool ok = postActivity("dht", payload);
            if (ok) blinkGreen();

            Serial.print(F("[Heartbeat] T="));
            Serial.print(temperature);
            Serial.print(F(" H="));
            Serial.println(humidity);
        }

        // ── Incident-specific alert post ────────────────────
        if (dhtIncident && (now - lastDhtAlert >= DHT_COOLDOWN)) {
            lastDhtAlert = now;
            Serial.println(F("[INCIDENT] DHT threshold exceeded!"));

            String payload = buildDhtPayload(temperature, humidity,
                                             tempAlert, humidAlert);
            bool ok = postActivity("dht", payload);
            if (ok) blinkGreen();

            // Visual + buzzer warning
            triggerWarning();
        }
    } else {
        Serial.println(F("[DHT] Read failed — check wiring."));
    }

    // WiFi watchdog — reconnect if dropped
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println(F("[WiFi] Disconnected — reconnecting..."));
        connectWiFi();
    }

    delay(500); // sample every 500 ms
}

// ============================================================
//  HELPERS
// ============================================================

/**
 * Build the JSON payload string for a DHT event.
 */
String buildDhtPayload(float temp, float hum, bool tempAlert, bool humidAlert) {
    // Escape booleans as lowercase strings for readability in the dashboard
    String ta = tempAlert  ? "true" : "false";
    String ha = humidAlert ? "true" : "false";
    return "{\"temperature\":" + String(temp, 1) +
           ",\"humidity\":"    + String(hum,  1) +
           ",\"temp_alert\":"  + ta +
           ",\"humid_alert\":" + ha + "}";
}

/**
 * POST a device activity to the Laravel API.
 * Returns true on HTTP 2xx.
 */
bool postActivity(const char* eventType, const String& payload) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println(F("[HTTP] Not connected — skipping post."));
        return false;
    }

    WiFiClient client;
    HTTPClient http;

    String url = String(SERVER_HOST) + String(API_ENDPOINT);
    http.begin(client, url);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept",       "application/json");

    // Build JSON body
    // Use ArduinoJson to safely escape token and payload strings
    StaticJsonDocument<512> doc;
    doc["device_token"] = DEVICE_TOKEN;
    doc["event_type"]   = eventType;
    doc["payload"]      = payload;   // already a JSON string; stored as text

    String body;
    serializeJson(doc, body);

    Serial.print(F("[HTTP] POST → "));
    Serial.println(url);
    Serial.print(F("[HTTP] Body: "));
    Serial.println(body);

    int httpCode = http.POST(body);
    http.end();

    if (httpCode >= 200 && httpCode < 300) {
        Serial.print(F("[HTTP] Success: "));
        Serial.println(httpCode);
        return true;
    } else {
        Serial.print(F("[HTTP] Failed: "));
        Serial.println(httpCode);
        return false;
    }
}

/**
 * Critical alert: buzzer + red LED on for ALERT_DURATION ms.
 * Non-blocking — sets a flag and a future end time.
 */
void triggerAlert() {
    if (!alertActive) {
        alertActive  = true;
        alertEndTime = millis() + ALERT_DURATION;
    }
    digitalWrite(LED_RED_PIN, HIGH);
    digitalWrite(LED_YELLOW_PIN, LOW);
    digitalWrite(BUZZER_PIN, HIGH);
    Serial.println(F("[Output] Alert ON (red LED + buzzer)"));
}

/**
 * Warning: yellow LED + short beep for BEEP_DURATION ms.
 */
void triggerWarning() {
    if (!warningActive) {
        warningActive  = true;
        warningEndTime = millis() + BEEP_DURATION;
    }
    if (!alertActive) { // don't override a critical alert
        digitalWrite(LED_YELLOW_PIN, HIGH);
        digitalWrite(LED_RED_PIN,    LOW);
        digitalWrite(BUZZER_PIN,     HIGH);
    }
    Serial.println(F("[Output] Warning ON (yellow LED + beep)"));
}

/**
 * Turn off all outputs.
 */
void stopOutputs() {
    digitalWrite(BUZZER_PIN,     LOW);
    digitalWrite(LED_RED_PIN,    LOW);
    digitalWrite(LED_YELLOW_PIN, LOW);
}

/**
 * Single short green LED blink — indicates successful API post.
 */
void blinkGreen() {
    digitalWrite(LED_GREEN_PIN, HIGH);
    delay(150);
    digitalWrite(LED_GREEN_PIN, LOW);
}

/**
 * Read DHT temperature and humidity.
 * Returns false if either reading is NaN (sensor not ready / wiring issue).
 */
bool readDHT(float& temp, float& hum) {
    temp = dht.readTemperature();  // Celsius
    hum  = dht.readHumidity();
    return (!isnan(temp) && !isnan(hum));
}

/**
 * Connect to WiFi and block until connected (with timeout).
 */
void connectWiFi() {
    Serial.print(F("[WiFi] Connecting to "));
    Serial.println(WIFI_SSID);

    WiFi.mode(WIFI_STA);
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

    unsigned long start = millis();
    while (WiFi.status() != WL_CONNECTED) {
        if (millis() - start > 20000) {
            Serial.println(F("[WiFi] Timeout — will retry in next loop."));
            return;
        }
        digitalWrite(LED_GREEN_PIN, !digitalRead(LED_GREEN_PIN)); // blink while connecting
        delay(500);
        Serial.print(F("."));
    }

    digitalWrite(LED_GREEN_PIN, LOW);
    Serial.println();
    Serial.print(F("[WiFi] Connected — IP: "));
    Serial.println(WiFi.localIP());
}
