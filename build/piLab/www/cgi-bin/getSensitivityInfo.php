<?php

require('files.php');

$xmlData = simplexml_load_file('../../config/Sensitivity.xml');

$jsonArray = array();

$jsonArray['pan_sensitivity'] = (string)($xmlData->Pan);
$jsonArray['tilt_sensitivity'] = (string)($xmlData->Tilt);
$jsonArray['zoom_sensitivity'] = (string)($xmlData->Zoom);
$jsonArray['stop_area'] = (string)($xmlData->StopArea);

header("Content-Type: application/json; charset=utf-8");

$json = json_encode($jsonArray);
print($json);
