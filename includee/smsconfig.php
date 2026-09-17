<?php
return;

//------ config sms account
$SmsUser     = 'sahe_javad';
$SmsUser     = 'sahebi';
$SmsPass     = '@Javad2839';
$SmsNumber   = '3000191010';
$SmsWSDL     = 'http://www.tsms.ir/soapWSDL/?wsdl';
$SmsUrlSend  = "http://tsms.ir/url/tsmshttp.php?from=$SmsNumber&username=$SmsUser&password=$SmsPass&to=";  //&to=$to
$SmsUrlDeliveri  = "http://tsms.ir/url/tsmshttp.php?from=$SmsNumber&username=$SmsUser&password=$SmsPass&deliver20=";//&deliver20=$smsid';
$SmsUrlGetXML    = "http://www.tsms.ir/url/recived_sms_xml.php?username=$SmsUser&password=$SmsPass&from=$SmsNumber";
$SmsUrlGetCredit = "http://tsms.ir/url/tsmshttp.php?from=$SmsNumber&username=$SmsUser&password=$SmsPass&credit=what";
//$SmsUrlGet = "../GetSms.php?from=$FROM&to=$TO&text=$TEXT&udh=$UDH";
?>