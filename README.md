# esp8266InfraredRemoteRepeater

Repeat an infrared (IR) remote signal over WiFi on your LAN or over the Internet.  The signal gets sent over HTTP as a form POST request.  An HTTP server receives the signal and transmits it to your device.  

For example, press a button on your remote in one room and having it change the channel in another room.  Access your TV set-box over the Internet so you can remotely change the channel using your phone or laptop.

You can make a web page that can be used to submit the HTTP form POST without the remote.  Send the signals from any web page.

Modules used:
* Sourcekit 161002922 Infrared LED 1W 350mA Transmitter Module
  * Sold on Ebay and Aliexpress: "1w infrared module"
* YwRobot 545754 IR Receiver Module
* 2x ESP8266 Microcontrollers
