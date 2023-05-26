# solis-wifi-poller
This small project allows for the polling of basic solar inverter statistics.

It is part of a local client to remote server pairing, but can be used to just poll the inverter for the data locally.

## How To Use
To use, set the inverter admin password to your password used by the Solis WiFi stick to connect to the local WIFi access point, it uses to connect to the Solis cloud.

## Testing Against Your WiFi Stick
It is easy to interrogate the WiFi stick for basic status and metrics.

To do a test (assuming your WiFi stick is 192.168.1.149) use this URL: http://admin:<password>@192.168.1.149/inverter.cgi
  
In this example, set <password> to your password used bu the WiFi stick to connect to your local network.

A response in a web browser should look something like: 0000000000000000;360026;F4;28.1;0;191;3390;NO; 

## Debugging
There is a debug flag to allow for debugging of what the script. Set to true to show what it is doing.
