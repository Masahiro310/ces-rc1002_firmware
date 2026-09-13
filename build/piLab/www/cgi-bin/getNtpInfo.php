<?php
require('files.php');

$ntpState = loadTextFile('../../config/ntpStatus.txt');
$xmlData = simplexml_load_file('../../config/NtpSetting.xml');

$jsonArray['use_ntp'] = (string)($xmlData->UseNTP);
$jsonArray['ntp_server_addr'] = (string)($xmlData->NTPServer);
if(strcmp($xmlData->UseNTP, "true") == 0){
    $jsonArray['ntp_connect_status'] = $ntpState;
}
else{
    $jsonArray['ntp_connect_status'] = "";
}

header("Content-Type: application/json; charset=utf-8");

$json = json_encode($jsonArray);
print($json);
