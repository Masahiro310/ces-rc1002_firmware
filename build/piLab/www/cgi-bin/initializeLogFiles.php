<?php
// 工場初期化実行
exec("../../cmd/initializeLog.sh > /dev/null");

// API戻り値作成(JSでRedirectUrlに飛ばしてDownload)
$message="test";
$status="0";


// ファイルを判定してStatus等々メッセージ決める
$array = array(
	"message" => $message ,
	"status" => $status ,
);

exec("../../cmd/apps_restart.sh > /dev/null &");

$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;
