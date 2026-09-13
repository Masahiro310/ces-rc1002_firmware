<?php

require('files.php');

$xmlData = simplexml_load_file('../../config/UnitSetting.xml');

$jsonArray = array();

$jsonArray['user'] = (string)($xmlData->WebUser);
$jsonArray['password'] = (string)($xmlData->WebPassword);
$jsonArray['unit_password'] = (string)($xmlData->UnitPassword);

header("Content-Type: application/json; charset=utf-8");

$json = json_encode($jsonArray);
print($json);
