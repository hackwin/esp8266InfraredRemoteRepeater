// ESP8266 IR remote repeater over wireless network.  Remotely control any IR device such as a TV set-top box
// 2x ESP8266 NodeMCU, 1x IR LED module, 1x IR Receiver, 6x female/female jumper wires
// Arduino C++, written in Arduino IDE, GNU GPL 3 License

// By Jesse Campbell
// March 2021
// Updated November 2021
// http://www.jbcse.com

/* 
_Instructions to setup Arduino IDE for ESP8266_
Edit > Preferences > Additional Boards Manager URLs: ... ",https://arduino.esp8266.com/stable/package_esp8266com_index.json"
Tools > Boards > ESP8266 Boards (2.6.3) > Generic ESP8266 Module
*/

#include <Arduino.h>
#include <ESP8266WiFi.h> 
#include <WiFiClient.h>
#include <IRremoteESP8266.h>
#include <IRsend.h>
#include <ESP8266WebServer.h>

const char* wifiSSID = ???"; // change this to your WiFi AP name!
const char* password = "???"; // change this to your WiFi AP pass!
IPAddress staticIP(10, 0, 0, 88); // change this to an unused IP address on your network
IPAddress gateway (staticIP[0], staticIP[1], staticIP[2], 1); // you may need to change this
IPAddress subnet  (255, 255, 255, 0);
IPAddress dns     (1, 1, 1, 1);

IRsend irsend(4); // Connect your IR LED module to ESP8266 Pin D2
ESP8266WebServer server(80); // TCP Port 80 (Standard HTTP)

const int RELAY_RESET_PIN = 16; //ESP8266 Pin D0, breaks power to the box using relay.  Relay is NC and disconnected when HIGH

// declare function signatures to bypass forward reference checks by compiler
void handleRoot(void), handlePlainFormPost(void), handleNotFound(void), handleReset(void);

// web form to send IR remote signal data
const char postForms[] PROGMEM = R"=====(
<html>
  <head>
    <title>ESP8266 Web Server Post Form</title>
    <style>
      body { 
        background-color: #eeeeee; 
        font-family: Arial, Helvetica, Sans-Serif; 
      }
      li { padding: 2px; }
      pre{
        background: white;
        color: black;
        font-weight: bold;
        padding: 5px;
        border: solid 1px lightgray;
      }
    </style>
  </head>
  <body>
    <h1>Form to transmit infrared remote signals</h1>
    <h3>Data Format</h3>
    <ul>        
      <li>Start with the total count of readings and a comma</li>
      <li>Enter readings with a comma</li>
      <li>End with a semi-colon instead of a comma</li>
      <li>Several button presses can be appended together</li>
    </ul>
    <h3>Tips</h3>
    <ul>
      <li>IR light can be seen on cameras as a purplish white flicker</li>
    </ul>
    <hr>
    <h2>Enter IR signal values below</h2>
    
    <form method="post" enctype="text/plain" action="/ir-remote-signal/">
      <h3>Simple Format</h3>
      <pre>(see example below): sumCount,firstValue,secondValue,...,lastValue;</pre>
      <textarea name="p" style="width: 100%; min-height: 150px;">39,8962,4486,478,4494,478,4492,478,2254,476,4496,476,2254,476,2254,478,2252,478,2254,476,2254,476,2252,478,2252,478,2254,476,4494,478,2252,478,4494,478,2254,476,30806,8960,2248,474;</textarea>
      <input style="font-size: 16pt; padding: 5px; margin-top: 10px;" type="submit" value="Send Signal">
      <br><br>
    </form>

    <form method="post" enctype="text/plain" action="/ir-remote-signal/">
      <h3>Advanced Format</h3>
      <pre>(see example below): sumCount1,firstValue1,secondValue1,...,lastValue1;sumCount2,firstValue2,secondValue2,...,lastValue2;</pre>
      <textarea name="p" style="width: 100%; min-height: 150px;">39,8958,4486,478,2254,476,2252,478,2254,476,2278,452,2252,476,2254,476,2280,452,2254,476,2254,476,2254,476,2252,478,2254,476,2278,452,2278,450,2256,476,2256,474,42002,8958,2270,452;39,8964,4486,478,2252,478,2256,476,4496,476,2254,476,2256,474,2252,478,2254,478,2256,474,2280,452,2256,474,2256,476,2280,452,2254,476,2254,476,4522,450,4498,474,35098,8960,2272,450;39,8958,4488,476,2254,476,2254,476,2256,476,4494,478,2280,452,2256,476,2278,452,2282,450,2254,476,2282,450,2254,478,2280,450,2252,478,2254,476,2254,476,4494,478,37398,8960,2248,476;39,8962,4484,480,4496,476,2252,478,2252,476,2254,478,2254,476,2254,476,2280,450,2256,476,2256,476,2254,476,2256,474,2252,478,4492,478,4494,478,4494,478,4494,478,30800,8958,2270,452;</textarea>
      <br><br>
      <input style="font-size: 16pt; padding: 5px; margin-top: 10px;" type="submit" value="Send Signal">
      <br><br>
    </form>
        
    <hr>
    <a href="https://github.com/hackwin/esp8266InfraredRemoteRepeater">Github Project Page</a>
  </body>
</html>
)=====";

void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(RELAY_RESET_PIN, OUTPUT);
  
  if(String(wifiSSID) == "???" || String(password) == "???"){
    while(true){
      Serial.println("You must change the wifi access point/password from ??? to your settings!");
      digitalWrite(LED_BUILTIN, 1);
      delay(500);
      digitalWrite(LED_BUILTIN, 0);
      delay(500);
    }
  }

  irsend.begin();
  WiFi.config(staticIP, subnet, gateway, dns);  
  WiFi.begin(wifiSSID, password);

  Serial.println();
  Serial.print("Connecting to access point");
  while (WiFi.status() != WL_CONNECTED) { // Wait for wifi connection
    delay(500);
    Serial.print('.');
  }
  Serial.println();
  Serial.print("Connected to access point: ");
  Serial.println(wifiSSID);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("MAC address: ");
  Serial.println(WiFi.macAddress());

  server.on("/", handleRoot);
  server.on("/reset/", handleReset);
  server.on("/ir-remote-signal/", handlePlainFormPost);
  server.onNotFound(handleNotFound);  
  server.begin();
  Serial.println("HTTP server started: http://" + WiFi.localIP().toString());
  Serial.println("Awaiting HTTP request!");
}

void loop() {
  server.handleClient();
}

void handleRoot() {
  server.send(200, "text/html", postForms);
}

void handlePlainFormPost() {
  if (server.method() != HTTP_POST) {
    server.send(405, "text/plain", "Method Not Allowed.  Use HTTP POST.");
  } else {
    server.send(200, "text/plain", "POST body received was:\n" + server.arg("plain"));
    Serial.println("HTTP request received! Re-transmitting IR signal!");

    boolean first;
    uint16_t irSignal[1024];
    int arrayIndex = 0, arraySize = 0;
    String element, sig = server.arg("plain");
  
   for(int i=0; i<sig.length(); i++){
      char inByte = sig.charAt(i);
      if(inByte == 'p' || inByte == '=' || inByte == ' '){
        continue;
      }
      else if(inByte == ';'){  // request ends with semicolon
        irSignal[arrayIndex] = element.toInt();        
        irsend.sendRaw(irSignal, arraySize, 38);  // Send a raw data capture at 38kHz
        element = "";
        first = true;
        arrayIndex = 0;
      }
      else if(inByte != ','){
        //Serial.print("Appending digit: ");
        //Serial.print(inByte);
        //Serial.print(", to element: ");
        //Serial.println(element);
        element += inByte;
      }
      else if (first == true && inByte == ','){ // request begins with length
        //Serial.print("Found end of array size: ");
        arraySize = element.toInt();
        //Serial.println(arraySize);
        first = false;
        element = "";
      }
      else if(first == false && inByte == ','){
        //Serial.print("Found end of array element[");
        //Serial.print(arrayIndex);
        //Serial.print("]: ");
        unsigned int e = element.toInt();
        //Serial.println(e);
        element = "";
        irSignal[arrayIndex] = e;
        arrayIndex++;
      }
    }
  }
}

void handleNotFound() {
  server.send(404, "text/plain", "File Not Found");
}

void handleReset(){
  digitalWrite(RELAY_RESET_PIN, HIGH);
  delay(3000);
  digitalWrite(RELAY_RESET_PIN, LOW);
  server.send(200, "text/plain", "Power to box has been reset");
}
