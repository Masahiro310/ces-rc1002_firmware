<?php
require('files.php');
require('unitSetting.php');

$jsonArray = loadNetworkConfig();

$portConfig = loadTextFile('../../config/http_port.txt');

$portConfig = str_replace(array("\r\n", "\r", "\n"), "\n", $portConfig);
$portConfigLine = explode("\n", $portConfig);
foreach ($portConfigLine as $lineStr) {
	if (!(strpos($lineStr, 'http_port') === false)) {
		$http_port = explode("=", $lineStr);
		$jsonArray['http_port'] = $http_port[1];
	}
}


header("Content-Type: application/json; charset=utf-8");
$json = json_encode($jsonArray);
print($json);
