<?php
// require('files.php');

function UpdateUnitConfig(string $key, string $value)
{
	$xmlData = simplexml_load_file('../../config/UnitSetting.xml');

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$rootElement = $dom->createElement("UnitSetting");
	$dom->appendChild($rootElement);
	if ($key == "WebUser") {
		$rootElement->appendChild($dom->createElement("WebUser", $value));
	} else {
		$rootElement->appendChild($dom->createElement("WebUser", (string)($xmlData->WebUser)));
	}
	if ($key == "WebPassword") {
		$rootElement->appendChild($dom->createElement("WebPassword", $value));
	} else {
		$rootElement->appendChild($dom->createElement("WebPassword", (string)($xmlData->WebPassword)));
	}
	if ($key == "UnitPassword") {
		$rootElement->appendChild($dom->createElement("UnitPassword", $value));
	} else {
		$rootElement->appendChild($dom->createElement("UnitPassword", (string)($xmlData->UnitPassword)));
	}
	// $unit_setting = $dom->saveXML();
	// saveTextFile('../config/UnitSetting.xml', $unit_setting);
	$dom->save('../../config/UnitSetting.xml');
}

function UpdateNtpConfig(string $key, string $value)
{
	$xmlData = simplexml_load_file('../../config/NtpSetting.xml');

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$rootElement = $dom->createElement("UnitSetting");
	$dom->appendChild($rootElement);
	if ($key == "UseNTP") {
		$rootElement->appendChild($dom->createElement("UseNTP", $value));
	} else {
		$rootElement->appendChild($dom->createElement("UseNTP", (string)($xmlData->UseNTP)));
	}
	if ($key == "NTPServer") {
		$rootElement->appendChild($dom->createElement("NTPServer", $value));
	} else {
		$rootElement->appendChild($dom->createElement("NTPServer", (string)($xmlData->NTPServer)));
	}
	// $unit_setting = $dom->saveXML();
	// saveTextFile('../config/NtpSetting.xml', $unit_setting);
	$dom->save('../../config/NtpSetting.xml');
}

function loadNetworkConfig()
{
	$netConfig = loadTextFile('../../config/interfaces');

	$jsonArray = array();

	$jsonArray['dns1'] = "";
	$jsonArray['dns2'] = "";
	$jsonArray['dns3'] = "";
	$jsonArray['dns4'] = "";

	$netConfig = str_replace(array("\r\n", "\r", "\n"), "\n", $netConfig);
	$netConfigLine = explode("\n", $netConfig);
	foreach ($netConfigLine as $lineStr) {
		if (!(strpos($lineStr, 'address ') === false)) {
			$ipaddr = explode(" ", $lineStr);
			$subnet = explode("/", $ipaddr[1]);
			$ipaddrs = explode(".", $subnet[0]);
			$jsonArray['ipaddr1'] = $ipaddrs[0];
			$jsonArray['ipaddr2'] = $ipaddrs[1];
			$jsonArray['ipaddr3'] = $ipaddrs[2];
			$jsonArray['ipaddr4'] = $ipaddrs[3];
			$jsonArray['subnet'] = $subnet[1];
		} else if (!(strpos($lineStr, 'gateway ') === false)) {
			$gateway = explode(" ", $lineStr);
			$gateways = explode(".", $gateway[1]);
			$jsonArray['gateway1'] = $gateways[0];
			$jsonArray['gateway2'] = $gateways[1];
			$jsonArray['gateway3'] = $gateways[2];
			$jsonArray['gateway4'] = $gateways[3];
		} else if (!(strpos($lineStr, 'dns-nameservers ') === false)) {
			$dns = explode(" ", $lineStr);
			$dnss = explode(".", $dns[1]);
			$jsonArray['dns1'] = $dnss[0];
			$jsonArray['dns2'] = $dnss[1];
			$jsonArray['dns3'] = $dnss[2];
			$jsonArray['dns4'] = $dnss[3];
		}
	}

	if($jsonArray['dns1']==127)
	{
		$jsonArray['dns1'] = "";
		$jsonArray['dns2'] = "";
		$jsonArray['dns3'] = "";
		$jsonArray['dns4'] = "";
	}
	return $jsonArray;
}

//// 制御する機器のアドレスの妥当性を検証する
//禁止事項
// ・オクテット単位で255を超える値   validateIpv4
// ・ネットワークアドレス           validateNetworkAddress
// ・ブロードキャストアドレス        validateBroadcastAddress
// ・クラスD/クラスE               validateIpv4
// ・ループバックアドレス           validateIpv4
// ・第1オクテットが「0」           validateIpv4
// ・223.255.255.255             validateIpv4
// ・自身のIPアドレスと同じ値

function validateUnitIpv4Addr(string $ipv4addr)
{
	$ret = false;
	$ipaddr = explode(".",$ipv4addr);
	if(validateIpv4($ipaddr[0], $ipaddr[1], $ipaddr[2], $ipaddr[3])){
		$jsonArray = loadNetworkConfig();
		if (
			!validateBroadcastAddress(
				$ipaddr[0],
				$ipaddr[1],
				$ipaddr[2],
				$ipaddr[3],
				$jsonArray['ipaddr1'],
				$jsonArray['ipaddr2'],
				$jsonArray['ipaddr3'],
				$jsonArray['ipaddr4'],
				$jsonArray['subnet']
			)
			&& !validateNetworkAddress(
				$ipaddr[0],
				$ipaddr[1],
				$ipaddr[2],
				$ipaddr[3],
				$jsonArray['ipaddr1'],
				$jsonArray['ipaddr2'],
				$jsonArray['ipaddr3'],
				$jsonArray['ipaddr4'],
				$jsonArray['subnet']
			)
			&& !($jsonArray['ipaddr1'] == $ipaddr[0] && $jsonArray['ipaddr2'] == $ipaddr[1] && $jsonArray['ipaddr3'] == $ipaddr[2] && $jsonArray['ipaddr4'] == $ipaddr[3])
		) {
			$ret = true;
		}
	}	
	return $ret;
}

function UpdateSensitivityConfig(string $key, string $value)
{
	$xmlData = simplexml_load_file('../../config/Sensitivity.xml');

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$rootElement = $dom->createElement("Sensitivity");
	$dom->appendChild($rootElement);
	if ($key == "Pan") {
		$rootElement->appendChild($dom->createElement("Pan", $value));
	} else {
		$rootElement->appendChild($dom->createElement("Pan", (string)($xmlData->Pan)));
	}
	if ($key == "Tilt") {
		$rootElement->appendChild($dom->createElement("Tilt", $value));
	} else {
		$rootElement->appendChild($dom->createElement("Tilt", (string)($xmlData->Tilt)));
	}
	if ($key == "Zoom") {
		$rootElement->appendChild($dom->createElement("Zoom", $value));
	} else {
		$rootElement->appendChild($dom->createElement("Zoom", (string)($xmlData->Zoom)));
	}
	if ($key == "StopArea") {
		$rootElement->appendChild($dom->createElement("StopArea", $value));
	} else {
		$rootElement->appendChild($dom->createElement("StopArea", (string)($xmlData->StopArea)));
	}
	// $unit_setting = $dom->saveXML();
	// saveTextFile('../config/UnitSetting.xml', $unit_setting);
	$dom->save('../../config/Sensitivity.xml');
}

