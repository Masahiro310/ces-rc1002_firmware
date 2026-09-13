<?php
// ces_rc1002.zip作成
exec("../../cmd/cmpressConfigFiles.sh ../data");

// API戻り値作成(JSでRedirectUrlに飛ばしてDownload)
$message="test";
$status="0";
$RedirectUrl="/data/ces_rc1002.zip";

// ファイルを判定してStatus等々メッセージ決める
$array = array(
	"message" => $message ,
	"status" => $status ,
	"data" => array(
		"RedirectUrl" => $RedirectUrl ,
	),
);


$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;
