<?php

require('files.php');

$xmlData = simplexml_load_file('../../config/PelcoSetting.xml');

$jsonArray = array();

$jsonArray['serialSpeed'] = (string)($xmlData->SerialSpeed);
$jsonArray['camera_id'] = (string)($xmlData->CameraId);
$jsonArray['pt_hiSpeed'] = (string)($xmlData->PtHiSpeed);
$jsonArray['zoom_hiSpeed'] = (string)($xmlData->ZoomHiSpeed);

header("Content-Type: application/json; charset=utf-8");

$json = json_encode($jsonArray);
print($json);
