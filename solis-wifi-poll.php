<?php
    // ------------------------------------------------------------------------
    // Function: Script to poll a Solis WiFi Stick
    // Version : 0.1
    // Author  : Darren Hatcher
    // Date    : 2023-05-26
    // ------------------------------------------------------------------------

    $sDebugFlag = false;
    $sSendKey = "<key-here>";       // needs to be the same as online key
    $sSystemName = "<system-name>"; // system name

// ------------------------------------------------------------------------
    // This is usually the IP address of the WiFi stick
    $sgDataSourceLocation = "192.168.1.149/inverter.cgi";
    // This is usually the destination for the process data
    $sgDataDestination = "api.stratus.org.uk/solar/solis/store.php";
    $sgHttpPrefix = "http://";
    // This is usually unchanged on the wifi stick
    $sgUserName = "admin";
    // if empty, then creds are not used (for testing). In live, the password is the same as the WiFi access point password
    $sgUserPass = "";

    // set the default timezone to use
    date_default_timezone_set('Europe/London');
    $sDateNow = date(DATE_ATOM);

    // Any inputs to this script?
    //  process here ...

    // creds for stick ...
    if ($sgUserPass != ""){
        $sLogOnCreds = "$sgUserName:$sgUserPass@";
    } else {
        $sLogOnCreds = "";
    }
    if ($sDebugFlag) {echo "\nsLogOnCreds='$sLogOnCreds'";}

    $sFinalURLToUse = $sgHttpPrefix.$sLogOnCreds.$sgDataSourceLocation;
    if ($sDebugFlag) {echo "\nsFinalURLToUse=$sFinalURLToUse";}

    // Open the file using the HTTP headers set above
    $sData= file_get_contents($sFinalURLToUse);

    if ($sData != false) { // then something came back ok
        ProcessResults($sData);
    } else {
        // request failed
        echo "\n$sDateNow: Poll request to inverter failed. No data processed.";
        exit(1);
    }

// ------------------------------------------------------------------------
function ProcessResults($sData)
{
    // The data from the WiFi Stick are semi-colon delimited fields -> note this is hardcoded on the stick and may change of the stick f/w is updated
    // Data returned successfully looks like: "0000000000000000;360026;F4;23.2;170;67;3371;NO;"
    //                                         f1               f2     f3 f4   f5  f6 f7   f8
    // f1 = ?
    // f2 = ?
    // f3 = Solis Inverter System Type
    // f4 = Inverter Internal Temperature (C)
    // f5 = Instant power reading from panels
    // f6 = Yield for the current day -> note this is missing the decimal point, with the right-most number being part of 0.1 kWh
    // f7 = Yield for the system since commissioned in kWh
    // f8 = ?

    // now unpick the results
}
    global $sDebugFlag,$sgDataDestination,$sDateNow;

    if ($sDebugFlag) {echo "\nPassed sData=$sData";}

    $aComponents = explode(";",$sData);

    if ($sDebugFlag) {$sArray = print_r($aComponents,true); echo "\nExploded data=$sArray";}

    // now assign to something more obvious
    $sInverterTemp = rtrim($aComponents[3]);                   // in Degrees C
    $sInverterDCInput = rtrim($aComponents[4]);                // in Watts
    $sInverterTodayYield = round(rtrim($aComponents[5])/10,2); // divide by ten because figure does not have a decimal point in it (67 means 6.7)
    $sInverterSinceCommissionedYield = rtrim($aComponents[6]);

    $sFinalURLToUse = "http://".$sgDataDestination;
    if ($sDebugFlag) {echo "\nInitial sFinalURLToUse=$sFinalURLToUse";}

    $sFinalURLToUse .= "?f1=".$aComponents[0];
    $sFinalURLToUse .= "&f2=".$aComponents[1];
    $sFinalURLToUse .= "&inverter_type=".$aComponents[2];
    $sFinalURLToUse .= "&inverter_temp=$sInverterTemp";
    $sFinalURLToUse .= "&inverter_dc_input_pwr=$sInverterDCInput";
    $sFinalURLToUse .= "&inverter_daily_yield=$sInverterTodayYield";
    $sFinalURLToUse .= "&inverter_commission_yield=$sInverterSinceCommissionedYield";
    $sFinalURLToUse .= "&f8=".$aComponents[7];
    $sFinalURLToUse .= "&key=$sSendKey"; // add a basic auth key
    $sFinalURLToUse .= "&system_name=$sSystemName"; //system name to identify the wifi stick

    // remove any null characters from the wifi stick
    $sFinalURLToUse = str_replace("\0","",$sFinalURLToUse);

    if ($sDebugFlag) {echo "\nUpdated sFinalURLToUse=$sFinalURLToUse\n";}

    // Open the file using the HTTP headers set above
    $sData = file_get_contents($sFinalURLToUse);

    if ($sData != false) { // then something came back ok
        echo "\n$sDateNow: Send request success.";
    } else {
        // request failed
        echo "\n$sDateNow: Send request failed.";
    }

    // we are done
    exit(0);
// ------------------------------------------------------------------------
?>
