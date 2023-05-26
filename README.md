# solis-wifi-poller
This small project allows for the polling of basic solar inverter statistics.

It is part of a local client to remote server pairing, but can be used to just poll the inverter for the data locally.

## How To Use
To use, set the inverter admin password to your password used by the Solis WiFi stick to connect to the local WIFi access point, it uses to connect to the Solis cloud.

## Testing Against Your WiFi Stick
It is easy to interrogate the WiFi stick for basic status and metrics.

To do a test (assuming your WiFi stick is 192.168.1.149) use this URL: http://admin:password@192.168.1.149/inverter.cgi
  
In this example, set <password> to your password used bu the WiFi stick to connect to your local network.

A response in a web browser should look something like: 0000000000000000;360026;F4;28.1;0;191;3390;NO; 

The data from the WiFi Stick are semi-colon delimited fields. Note this is hardcoded on the stick and may change of the stick firmware is updated.
  
    // Data returned successfully looks like: "0000000000000000;360026;F4;23.2;170;67;3371;NO;"
    //                                         f1               f2     f3 f4   f5  f6 f7   f8
    // f1 = Inverter serial number
    // f2 = Firmware version
    // f3 = Solis Inverter System Type
    // f4 = Inverter Internal Temperature (C)
    // f5 = Instant power reading from panels
    // f6 = Yield for the current day -> note this is missing the decimal point, with the right-most number being part of 0.1 kWh
    // f7 = Yield for the system since commissioned in kWh
    // f8 = Alerts enabled (YES/NO)
  
## Debugging
There is a debug flag to allow for debugging of what the script. Set to true to show what it is doing.
