<?php
require('validate.php');
require('files.php');
require('unitSetting.php');
$array = array(
	"message" => "" ,
	"status" => 0 ,
);

$paramKeys = array('ipaddr1', 'ipaddr2', 'ipaddr3', 'ipaddr4', 'gateway1', 'gateway2', 'gateway3', 'gateway4', 'dns1', 'dns2', 'dns3', 'dns4', 'subnet', 'http_port','user', 'password', 'conf_pass', 'unit_password', 'pan_sensitivity', 'tilt_sensitivity', 'zoom_sensitivity', 'stop_area');

if (!validateParams($_POST, $paramKeys)) {
	$array["message"] = "パラメータが不足しています" ;
	$array["status"] = 1 ;
	goto response;
}
if (
	validateIpv4($_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'])
	&& validateSubnetMask($_POST['subnet'])
	&& !validateBroadcastAddress($_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],
									$_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet'])
	&& !validateNetworkAddress($_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],
									$_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet'])

){
	$myIpAddr = (int)$_POST['ipaddr1'] . '.' . (int)$_POST['ipaddr2'] . '.' . (int)$_POST['ipaddr3'] . '.' . (int)$_POST['ipaddr4'];
	$subnet =  $_POST['subnet'];
} else{
	$array["message"] = "IPアドレスが不正です" ;
	$array["status"] = 1 ;
	goto response;

}
if (
	validateIpv4($_POST['gateway1'], $_POST['gateway2'], $_POST['gateway3'], $_POST['gateway4'])
	&& !validateBroadcastAddress($_POST['gateway1'], $_POST['gateway2'], $_POST['gateway3'], $_POST['gateway4'],
								$_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet'])
	&& !validateNetworkAddress($_POST['gateway1'], $_POST['gateway2'], $_POST['gateway3'], $_POST['gateway4'],
								$_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet'])
	&& (getNetworkAddress($_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet']) == getNetworkAddress($_POST['gateway1'], $_POST['gateway2'], $_POST['gateway3'], $_POST['gateway4'],$_POST['subnet']) )
	&& !(($_POST['gateway1']==$_POST['ipaddr1'])&&($_POST['gateway2']==$_POST['ipaddr2'])&&($_POST['gateway3']==$_POST['ipaddr3'])&&($_POST['gateway4']==$_POST['ipaddr4']))
) {
	$myGateway = (int)$_POST['gateway1'] . '.' . (int)$_POST['gateway2'] . '.' . (int)$_POST['gateway3'] . '.' . (int)$_POST['gateway4'];
} else {
	$array["message"] = "ゲートウェイアドレスが不正です" ;
	$array["status"] = 1 ;
	goto response;
}
if ( validatePortNumber($_POST['http_port']) )
{
	$http_port =  (string)intval($_POST['http_port']);
} else {
	$array["message"] = "HTTPポート番号が不正です" ;
	$array["status"] = 1 ;
	goto response;
}



if (validateIpv4orEmpty($_POST['dns1'], $_POST['dns2'], $_POST['dns3'], $_POST['dns4'])
	&& !validateBroadcastAddress($_POST['dns1'], $_POST['dns2'], $_POST['dns3'], $_POST['dns4'],
	$_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet'])
	&& !validateNetworkAddress($_POST['dns1'], $_POST['dns2'], $_POST['dns3'], $_POST['dns4'],
	$_POST['ipaddr1'], $_POST['ipaddr2'], $_POST['ipaddr3'], $_POST['ipaddr4'],$_POST['subnet'])
) {
	if (isEmptyString($_POST['dns1'])) {
		$myDns = '127.0.0.1';				// 未設定時はLoopbackしない
	} else {
		$myDns = (int)$_POST['dns1'] . '.' . (int)$_POST['dns2'] . '.' . (int)$_POST['dns3'] . '.' . (int)$_POST['dns4'];
	}
}
else {
	$array["message"] = "DNSサーバーアドレスが不正です" ;
	$array["status"] = 1 ;
	goto response;
}

if (
	$_POST['password'] === $_POST['conf_pass']
	&& validateUserId($_POST['user'])
	&& validatePassword($_POST['password'])
	&& validateUnitPassword($_POST['unit_password'])
) {

	UpdateUnitConfig("WebUser", $_POST['user']);
	UpdateUnitConfig("WebPassword", $_POST['password']);
	UpdateUnitConfig("UnitPassword", $_POST['unit_password']);
	exec("../../cmd/updateWebUser.sh > /dev/null &");

} else {
	if($_POST['user'][0] =='#'
		|| $_POST['user'][0] =='*'
		|| $_POST['password'][0] =='#'
		|| $_POST['password'][0] =='*'
		 ){
		$array["message"] = "ユーザー名・パスワードの1文字目に*、および#を使用することはできません" ;
	}
	else{
		$array["message"] = "管理者設定が不正です" ;
	}
	$array["status"] = 1 ;
	goto response;
}

if ( validateNumber($_POST['pan_sensitivity'],1, 100)
	&& validateNumber($_POST['tilt_sensitivity'],1, 100)
	&& validateNumber($_POST['zoom_sensitivity'],1, 100)
	&& validateNumber($_POST['stop_area'],10, 50)
) {

	UpdateSensitivityConfig("Pan", $_POST['pan_sensitivity']);
	UpdateSensitivityConfig("Tilt", $_POST['tilt_sensitivity']);
	UpdateSensitivityConfig("Zoom", $_POST['zoom_sensitivity']);
	UpdateSensitivityConfig("StopArea", $_POST['stop_area']);

} else {
	$array["message"] = "ジョイスティック操作感度設定が不正です" ;
	$array["status"] = 1 ;
	goto response;
}

$oldParam=loadNetworkConfig();
if ($oldParam['dns1']==127) {
	$myDnsTmp = '127.0.0.1';				// 未設定時はLoopbackしない
} else {
	$myDnsTmp = (int)$oldParam['dns1'] . '.' . (int)$oldParam['dns2'] . '.' . (int)$oldParam['dns3'] . '.' . (int)$oldParam['dns4'];
}
$isUpdate = false;

$interfaceParam = array('ipaddr1', 'ipaddr2', 'ipaddr3', 'ipaddr4', 'gateway1', 'gateway2', 'gateway3', 'gateway4', 'dns1', 'dns2', 'dns3', 'dns4', 'subnet');
foreach ($interfaceParam as $key) {
	if ($oldParam[$key]!=$_POST[$key]) {
		$isUpdate = true;
	}
}
if($isUpdate){
	// add interfasefile as cesrc1002
	$interfacefile  = "auto lo\n";
	$interfacefile .= "iface lo inet loopback\n";
	$interfacefile .= "auto eth0\n";
	$interfacefile .= "iface eth0 inet static\n";
	$interfacefile .= "address {$myIpAddr}/{$subnet}\n";
	$interfacefile .= "gateway {$myGateway}\n";
	$interfacefile .= "dns-nameservers {$myDns}\n";
	saveTextFile('../../config/interfaces', $interfacefile);
}
else{
	$array["message"] = "IP変更がありません" ;
	$array["status"] = 2;
}
$portInfo = "http_port={$http_port}\r\n";
saveTextFile('../../config/http_port.txt', $portInfo);
$portInfo = "Listen {$http_port}\n";
saveTextFile('../../config/ports.conf', $portInfo);
$sitesConf = "<VirtualHost *:{$http_port}>\n";
$sitesConf .= "Include ./sites-available/_cesrc1002-sites\n</VirtualHost>\n";
saveTextFile('../../config/cesrc1002-sites', $sitesConf);


response:
$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;

if($array["status"] == 2 ){
	exec("../../cmd/apache_restart.sh > /dev/null &");
	exec("../../cmd/apps_restart.sh > /dev/null &");

}
elseif($array["status"] == 0){
	system('../../cmd/reboot.sh > /dev/null &');
}
//exec("../../cmd/network_restart.sh > /dev/null &");
//exec("../../cmd/apache_restart.sh > /dev/null &");
//exec("../../cmd/apps_restart.sh > /dev/null &");
