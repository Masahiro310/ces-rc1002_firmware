<?php

require('files.php');

$versionData = file('../../version.txt');

$jsonArray = array();

$jsonArray['status'] = 0;
$jsonArray['version'] = trim($versionData[0]);
$jsonArray['date'] = trim($versionData[1]);
$jsonArray['product'] = "CES-RC-1002";

header("Content-Type: application/json; charset=utf-8");

$json = json_encode($jsonArray);
print($json);
