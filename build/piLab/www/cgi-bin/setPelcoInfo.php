<?php
require('validate.php');
require('files.php');

$paramKeys = array('serialSpeed', 'camera_id', 'pt_hiSpeed', 'pt_loSpeed');

if (!validateParams($_POST, $paramKeys)) {
	http_response_code(500);
	return;
}
function UpdatePelcoConfig(string $key, string $value)
{
	$xmlData = simplexml_load_file('../../config/PelcoSetting.xml');

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$rootElement = $dom->createElement("PelcoSetting");
	$dom->appendChild($rootElement);
	if ($key == "SerialSpeed") {
		$rootElement->appendChild($dom->createElement("SerialSpeed", $value));
	} else {
		$rootElement->appendChild($dom->createElement("SerialSpeed", (string)($xmlData->SerialSpeed)));
	}
	if ($key == "CameraId") {
		$rootElement->appendChild($dom->createElement("CameraId", $value));
	} else {
		$rootElement->appendChild($dom->createElement("CameraId", (string)($xmlData->CameraId)));
	}
	if ($key == "PtLoSpeed") {
		$rootElement->appendChild($dom->createElement("PtLoSpeed", $value));
	} else {
		$rootElement->appendChild($dom->createElement("PtLoSpeed", (string)($xmlData->PtLoSpeed)));
	}
	if ($key == "PtHiSpeed") {
		$rootElement->appendChild($dom->createElement("PtHiSpeed", $value));
	} else {
		$rootElement->appendChild($dom->createElement("PtHiSpeed", (string)($xmlData->PtHiSpeed)));
	}
	// $unit_setting = $dom->saveXML();
	// saveTextFile('../config/PelcoSetting.xml', $unit_setting);
	$dom->save('../../config/PelcoSetting.xml');
}


function isValidBaudRate($value): bool {
    // 文字列の "9600" などが渡されても判定できるように (int) で数値化
    return in_array((int)$value, [9600, 19200, 38400], true);
}

function isValidPelcoId($value): bool {
    // 数値かつ 1 〜 255 の範囲内か判定
    return is_numeric($value) && $value >= 1 && $value <= 254;
}

function isValidSpeed($value): bool {
    // 数値かつ 1 〜 63 の範囲内か判定
    return is_numeric($value) && $value >= 1 && $value <= 63;
}

if (
	isValidBaudRate($_POST['serialSpeed'])
	&& isValidPelcoId($_POST['camera_id'])
	&& isValidSpeed($_POST['pt_hiSpeed'])
	&& isValidSpeed($_POST['pt_loSpeed'])
) {

	UpdatePelcoConfig("SerialSpeed", $_POST['serialSpeed']);
	UpdatePelcoConfig("CameraId", $_POST['camera_id']);
	UpdatePelcoConfig("PtLoSpeed", $_POST['pt_loSpeed']);
	UpdatePelcoConfig("PtHiSpeed", $_POST['pt_hiSpeed']);

} else {
	http_response_code(500);
	print(var_dump($_POST));
	return;
}
exec("../../cmd/apps_restart.sh > /dev/null &");
