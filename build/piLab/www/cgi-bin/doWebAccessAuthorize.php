<?php
#=======================================================================
# FileName : doWebAccessAuthorize.php
# Summary  : APIBasic認証実行API
# 成功のメッセージ返すだけにした。// {"message":"","status":0}
#=======================================================================
// Aileへのコマンド無し
$array = array(
	"message" => "test" ,
	"status" => "0" ,
);
$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;

