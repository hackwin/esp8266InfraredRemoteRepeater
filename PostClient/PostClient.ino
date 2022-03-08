// ESP8266 IR remote repeater over wireless network.  Remotely control any IR device such as a TV set-top box
// 2x ESP8266 NodeMCU, 1x IR LED module, 1x IR Receiver, 6x female/female jumper wires
// Arduino C++, written in Arduino IDE, GNU GPL 3 License

// By Jesse Campbell
// March 2021
// Updated March 2022
// http://www.jbcse.com

/* 
_Instructions to setup Arduino IDE for ESP8266_
Edit > Preferences > Additional Boards Manager URLs: ... ",https://arduino.esp8266.com/stable/package_esp8266com_index.json"
Tools > Boards > ESP8266 Boards (2.6.3) > Generic ESP8266 Module
*/

#include <Arduino.h>
#include <IRrecv.h>
#include <IRremoteESP8266.h>
#include <IRac.h>
#include <IRtext.h>
#include <IRutils.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

ESP8266WiFiMulti WiFiMulti;

const char* wifiSSID = "???"; // change this to your WiFi AP name!
const char* password = "???"; // change this to your WiFi AP pass!
const char* serverIP = "?.?.?.?"; // the other ESP8266 with the IR LED module; see PostServer.ino
const String url = "http://"+String(serverIP)+"/ir-remote-signal/";

const uint16_t kRecvPin = 14; // Port D5 on ESP8266
const uint16_t kCaptureBufferSize = 1024;

#if DECODE_AC
const uint8_t kTimeout = 50;
#else   // DECODE_AC
const uint8_t kTimeout = 15;
#endif  // DECODE_AC

IRrecv irrecv(kRecvPin, kCaptureBufferSize, kTimeout, true);
decode_results results;
HTTPClient http;
WiFiClient client;

void setup(){
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);
  
  if(String(wifiSSID) == "???" || String(password) == "???"){
    while(true){
      Serial.println("You must change the wifi access point/password from ??? to your settings!");
      digitalWrite(LED_BUILTIN, 1);
      delay(500);
      digitalWrite(LED_BUILTIN, 0);
      delay(500);
    }
  }
  
  irrecv.enableIRIn();  // Start the receiver
  
  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP(wifiSSID, password);

  Serial.println();
  Serial.print("Connecting to access point");
  while(WiFiMulti.run() != WL_CONNECTED){
    delay(500);
    Serial.print('.');
  }
  Serial.println();
  Serial.println("Connected to access point: " + String(wifiSSID));
  Serial.println("Connected! DHCP IP address: "+WiFi.localIP().toString());
  Serial.println("MAC address: " + String(WiFi.macAddress()));
  Serial.println("Awaiting IR Signal!");
}

void loop(){
  if (WiFiMulti.run() == WL_CONNECTED) { // wait for WiFi connection
    if (irrecv.decode(&results)) { // check if the IR code has been received
      const decode_results * r1 = &results; // decode IR code
      String postContent = "p="; // begin building HTTP Post body
      postContent += String(r1->rawlen-1);
      for(int i = 1; i < r1->rawlen; i++){
        postContent += String(",") + String(r1->rawbuf[i]*2);
      }
      postContent += ";";      
      yield();

      //Serial.println(postContent);
    
      http.begin(client, url);
      http.addHeader("Content-Type", "text/plain");
      Serial.println("Received IR signal! Sending data to HTTP server!");
      int httpCode = http.POST(postContent);
      String payload = http.getString();
      Serial.println(httpCode);
      Serial.print(payload);
      http.end();
      yield();
    }
  }
}
