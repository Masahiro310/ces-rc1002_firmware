<?php
require('validate.php');
require('files.php');
require('unitSetting.php');
$paramKeys = array('use_ntp');
$ntpKeys = array('ntp_server_addr');
$manualKeys = array('date', 'hour_time', 'minute_time', 'second_time');

$array = array(
	"message" => "" ,
	"status" => 0 ,
);


if (!validateParams($_POST, $paramKeys)) {
	$array["message"] = "パラメータが不足しています" ;
	$array["status"] = 1 ;
	goto response;
}
if (validateBool($_POST['use_ntp'])) {
	if ($_POST['use_ntp'] === 'true') {
		if (validateParams($_POST, $ntpKeys)
			&& ((isIpV4Format($_POST['ntp_server_addr']) && validateUnitIpv4Addr($_POST['ntp_server_addr']))
				|| !isIpV4Format($_POST['ntp_server_addr']) && validateUri($_POST['ntp_server_addr']))
		) {
			UpdateNtpConfig("UseNTP", $_POST['use_ntp']);
			UpdateNtpConfig("NTPServer", $_POST['ntp_server_addr']);
			/// NTPへ接続する
			exec("../../cmd/execNtpdate.sh > /dev/null");
		} else {
			$array["message"] = "NTPサーバーアドレスが不正です";
			$array["status"] = 1;
			goto response;
		}
	} else {
		if (
			validateParams($_POST, $manualKeys) &&
			validateDate($_POST['date']) && validateHour($_POST['hour_time']) && validateMinute($_POST['minute_time']) && validateSecond($_POST['second_time'])
		) {
			UpdateNtpConfig("UseNTP", $_POST['use_ntp']);
			UpdateNtpConfig("NTPServer", "");

			/// 時刻更新する
			$dayArray = explode("/", $_POST['date']);

			$daytime = mktime( $_POST['hour_time'], $_POST['minute_time'], $_POST['second_time'], $dayArray[1], $dayArray[2], $dayArray[0] );
			$cmd = "sudo date -s \"" . $_POST['date'] . " " . $_POST['hour_time'] .":".  $_POST['minute_time'] .":". $_POST['second_time'] . "\"";
			exec( $cmd );
			exec( "../../cmd/writeHwclock.sh > /dev/null" );
			
		} else {
			$array["message"] = "日時が不正です" ;
			$array["status"] = 1 ;
			goto response;
		}
	}
}
response:
$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;
