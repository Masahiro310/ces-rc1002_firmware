<?php
require('validate.php');
$paramKeys = array('config');

// API戻り値作成(JSでRedirectUrlに飛ばしてDownload)
$message="";
$status="0";



if (!validateParams($_POST, $paramKeys)) {
	$message = "パラメータが不足しています" ;
	$status = 1 ;
	goto response;
}

// 工場初期化実行
exec("../../cmd/initializeConfig.sh " . $_POST[$paramKeys[0]]);

response:
// ファイルを判定してStatus等々メッセージ決める
$array = array(
	"message" => $message ,
	"status" => $status ,
);

$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;

// 再起動
if ($status == 0) {
    system('../../cmd/reboot.sh > /dev/null &');
}