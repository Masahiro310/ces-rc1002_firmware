<?php

function validateParams($paramList, $keyList)
{
	$retval = true;
	foreach ($keyList as $key) {
		if (!isset($paramList[$key])) {
			$retval = false;
		}
	}
	return $retval;
}

function isEmptyString($text)
{
	if ($text == '') {
		return true;
	}
	return false;
}

function validateBool($param)
{
	$retval = true;
	if ($param === "false" || $param === "true") {
	} else {
		$retval = false;
	}
	return $retval;
}

function validateBroadcastAddress($arg_1, $arg_2, $arg_3, $arg_4,$selfip_1, $selfip_2, $selfip_3, $selfip_4,$maskbit)
{
	$retval = false;
	if (ctype_digit((string)$arg_1) && ctype_digit((string)$arg_2) && ctype_digit((string)$arg_3) && ctype_digit((string)$arg_4)&& validateSubnetMask($maskbit)) {
		$ip = ($arg_1*256*256*256) + ($arg_2*256*256) + ($arg_3*256) + $arg_4;
		$selfip = ($selfip_1*256*256*256) + ($selfip_2*256*256) + ($selfip_3*256) + $selfip_4;
		$subnetMask = (0xFFFFFFFF << (32-$maskbit)) & 0x00FFFFFFFF;
		$subnetMask = ~$subnetMask & 0x00FFFFFFFF;
		$tmpIp = $selfip | $subnetMask;
		if( dechex($ip) == dechex($tmpIp) ){
			$retval = true;
		}
	}
	return $retval;
}
function getNetworkAddress($selfip_1, $selfip_2, $selfip_3, $selfip_4,$maskbit)
{
	$netAddr = "";
	if (ctype_digit((string)$selfip_1) && ctype_digit((string)$selfip_2) && ctype_digit((string)$selfip_3) && ctype_digit((string)$selfip_4)&& validateSubnetMask($maskbit)) {
		$selfip = ($selfip_1*256*256*256) + ($selfip_2*256*256) + ($selfip_3*256) + $selfip_4;
		$subnetMask = (0xFFFFFFFF << (32-$maskbit)) & 0x00FFFFFFFF;
		$netAddr = $selfip & $subnetMask;
	}
	return $netAddr;
}
function validateNetworkAddress($arg_1, $arg_2, $arg_3, $arg_4,$selfip_1, $selfip_2, $selfip_3, $selfip_4,$maskbit)
{
	$retval = false;
	if (ctype_digit((string)$arg_1) && ctype_digit((string)$arg_2) && ctype_digit((string)$arg_3) && ctype_digit((string)$arg_4)&& validateSubnetMask($maskbit)) {
		$ip = ($arg_1*256*256*256) + ($arg_2*256*256) + ($arg_3*256) + $arg_4;
		$selfip = ($selfip_1*256*256*256) + ($selfip_2*256*256) + ($selfip_3*256) + $selfip_4;
		$subnetMask = (0xFFFFFFFF << (32-$maskbit)) & 0x00FFFFFFFF;
		$tmpIp = $selfip & $subnetMask;
		if( dechex($ip) == dechex($tmpIp) ){
			$retval = true;
		}
	}
	return $retval;
}
function validateIpv4($arg_1, $arg_2, $arg_3, $arg_4)
{
	$retval = true;
	if (ctype_digit((string)$arg_1) && ctype_digit((string)$arg_2) && ctype_digit((string)$arg_3) && ctype_digit((string)$arg_4)) {
		if ($arg_1 < 0 || $arg_1 >= 224) {
			$retval = false;
		}
		if ($arg_1 == 0) {
			$retval = false;
		}
		if ($arg_1 == 127) {
			$retval = false;
		}
		if ($arg_2 < 0 || $arg_2 > 255) {
			$retval = false;
		}
		if ($arg_3 < 0 || $arg_3 > 255) {
			$retval = false;
		}
		if ($arg_4 < 0 || $arg_4 > 255) {
			$retval = false;
		}
		if($arg_1==223 && $arg_2 == 255 && $arg_3 == 255 && $arg_4 == 255){
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateIpv4orEmpty($arg_1, $arg_2, $arg_3, $arg_4)
{
	$retval = true;

	if (ctype_digit((string)$arg_1) && ctype_digit((string)$arg_2) && ctype_digit((string)$arg_3) && ctype_digit((string)$arg_4)) {
		if ($arg_1 < 0 || $arg_1 >= 224) {
			$retval = false;
		}
		if ($arg_1 == 0) {
			$retval = false;
		}
		if ($arg_1 == 127) {
			$retval = false;
		}
		if ($arg_2 < 0 || $arg_2 > 255) {
			$retval = false;
		}
		if ($arg_3 < 0 || $arg_3 > 255) {
			$retval = false;
		}
		if ($arg_4 < 0 || $arg_4 > 255) {
			$retval = false;
		}
		if($arg_1==223 && $arg_2 == 255 && $arg_3 == 255 && $arg_4 == 255){
			$retval = false;
		}
	} else {
		if (!(isEmptyString($arg_1) && isEmptyString($arg_2) && isEmptyString($arg_3) && isEmptyString($arg_4))) {
			$retval = false;
		}
	}
	return $retval;
}
function validateSubnetMask($maskBit)
{
	$retval = true;
	if (ctype_digit((string)$maskBit)) {
		if ($maskBit < 1 || $maskBit > 30) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validatePortNumber($port)
{
	$retval = true;
	if (ctype_digit((string)$port)) {
		if ($port <= 0 || $port > 65535) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateUserId($param)
{
	$ngPrintable = array('"', "'", '\\', ' ', '`', ':', ';', '|', '&');
	$retval = true;
	if (ctype_print($param)) {
		foreach ($ngPrintable as $delimiter) {
			$paramList = explode($delimiter, $param);
			$elementNum = count($paramList);
			if ($elementNum > 1) {
				$retval = false;
			}
		}
		$len = strlen($param);
		if ($len < 3 || $len > 32) {
			$retval = false;
		}
		// 先頭の時のみ禁止
		if($param[0] == '#'
			|| $param[0] == '*'){
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validatePassword($param)
{
	$ngPrintable = array('"', "'", '\\', ' ', '`', ':', ';', '|', '&');
	$retval = true;
	if (ctype_print($param)) {
		foreach ($ngPrintable as $delimiter) {
			$paramList = explode($delimiter, $param);
			$elementNum = count($paramList);
			if ($elementNum > 1) {
				$retval = false;
			}
		}
		$len = strlen($param);
		if ($len < 5 || $len > 32) {
			$retval = false;
		}
		// 先頭の時のみ禁止
		if($param[0] == '#'
			|| $param[0] == '*'){
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateUnitPassword($param)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		$len = strlen($param);
		if ($len != 5) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateCameraUserId($param)
{
	$ngPrintable = array('"', "'", '\\', ' ', '`', ':', ';', '|', '&');
	$retval = true;
	$len = strlen($param);
	if($len > 0){
		if (ctype_print($param)) {
			foreach ($ngPrintable as $delimiter) {
				$paramList = explode($delimiter, $param);
				$elementNum = count($paramList);
				if ($elementNum > 1) {
					$retval = false;
				}
			}
			if ($len > 32) {
				$retval = false;
			}
		} else {
			$retval = false;
		}	
	}
	return $retval;
}

function validateCameraPassword($param)
{
	$ngPrintable = array('"', "'", '\\', ' ', '`', ':', ';', '|', '&');
	$retval = true;
	$len = strlen($param);
	if($len > 0){
		if (ctype_print($param)) {
			foreach ($ngPrintable as $delimiter) {
				$paramList = explode($delimiter, $param);
				$elementNum = count($paramList);
				if ($elementNum > 1) {
					$retval = false;
				}
			}
			if ($len > 32) {
				$retval = false;
			}
		} else {
			$retval = false;
		}
	}
	return $retval;
}

function validateAileunUserId($param)
{
	$okPrintable = array('.', "-", '_','@');
	$checkTxt = str_replace($okPrintable, "1", $param);
	$retval = true;
	if (!ctype_alnum($checkTxt)) {
		$retval = false;
	}
	$len = strlen($param);
	if ($len < 3) {
		$retval = false;
	}
	if ($len > 32) {
		$retval = false;
	}
	return $retval;
}

function validateAileunPassword($param)
{
	$okPrintable = array('.', "-", '_','@');
	$checkTxt = str_replace($okPrintable, "1", $param);
	$retval = true;
	if (!ctype_alnum($checkTxt)) {
		$retval = false;
	}
	$len = strlen($param);
	if ($len > 32) {
		$retval = false;
	}
	if ($len < 5) {
		$retval = false;
	}
	return $retval;
}


/// 以下は、エラーチェックを実装する
function validateUri($param)
{
	$okPrintable = array('.', "-", '_');
	$checkTxt = str_replace($okPrintable, "1", $param);
	$retval = true;
	if (!ctype_alnum($checkTxt)) {
		$retval = false;
	}
	$len = strlen($param);
	if ($len > 253) {
		$retval = false;
	}
	/// ok文字も、パターンによって禁止
	$ngPrintable = array('..', '-.', '.-', '_.', '._');
	foreach ($ngPrintable as $delimiter) {
		$paramList = explode($delimiter, $param);
		$elementNum = count($paramList);
		if ($elementNum > 1) {
			$retval = false;
		}
	}

	// 先頭、最後の文字は英数字
	$firstChar=substr($param, 0, 1);
	$lastChar=substr($param, strlen($param)-1, 1);
	if (!ctype_alnum($firstChar)) {
		$retval = false;
	}
	if (!ctype_alnum($lastChar)) {
		$retval = false;
	}

	/// .が無いとエラー
	$paramList = explode('.', $param);
	$elementNum = count($paramList);
	if ($elementNum <= 1) {
		$retval = false;
	}
	else{
		// .で区切られたブロックは、63文字まで
		foreach($paramList as $block){
			if(strlen($block)>63){
				$retval = false;
			}
		}
	}

	return $retval;
}

function validateDate($param)
{
	$retval = true;
	$check = date_create_from_format("Y/m/d", $param);
	if ($check === false) {
		$retval = false;
	}
	return $retval;
}

function validateNumber($param, $minNum, $maxNum)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		if ($param < $minNum || $param > $maxNum) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}


function validateHour($param)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		if ($param < 0 || $param > 23) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateMinute($param)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		if ($param < 0 || $param > 59) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}
function validateSecond($param)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		if ($param < 0 || $param > 59) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateAileunUnit($param)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		if ($param <= 0 || $param > 10) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateAileunCamCh($param)
{
	$retval = true;
	if (ctype_digit((string)$param)) {
		if ($param <= 0 || $param > 36) {
			$retval = false;
		}
	} else {
		$retval = false;
	}
	return $retval;
}

function validateMaker(string $param, $makers)
{
	$retval = false;
	foreach ($makers as $key => $value) {
		if ($param == $key) {
			$retval = true;
			break;
		}
	}
	return $retval;
}

function validateCustomCommandType(string $param)
{
	$cmdTypes = array('unused', 'select_menu', 'custom_command');

	$retval = false;
	foreach ($cmdTypes as $value) {
		if ($param == $value) {
			$retval = true;
			break;
		}
	}
	return $retval;
}

function validateCustomCommandAction(string $param)
{
	$retval = false;
	if(ctype_alnum($param)){
		$retval = true;
	}
	return $retval;
}

function validateCustomCommandData(string $param)
{
	$ngPrintable = array(' ',);
	$retval = true;
	$len = strlen($param);
	if($len > 0 && $len <= 253){
		$firstChar=substr($param, 0, 1);
		if (0 == strcmp($firstChar, "/") && ctype_print($param)) {
			foreach ($ngPrintable as $delimiter) {
				$paramList = explode($delimiter, $param);
				$elementNum = count($paramList);
				if ($elementNum > 1) {
					$retval = false;
				}
			}
		} else {
			$retval = false;
		}
	}
	else{
		$retval = false;
	}
	return $retval;
}

function isIpV4Format(string $value)
{
	$ret = false;
	$ipaddr = explode(".",$value);
	if(count($ipaddr)==4){
		if(ctype_digit($ipaddr[0])&&ctype_digit($ipaddr[1])&&ctype_digit($ipaddr[2])&&ctype_digit($ipaddr[3])){
			$ret = true;
		}
	}
	return $ret;
}