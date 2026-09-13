<?php
# FileName : interProcessCom.pl
# Summary  : プロセス間通信API
// -------------------------------
// ひとまず　シャットダウンのシェルを起動するだけにする。
// -------------------------------

// SendDataからID貰ってコマンドを設定して対応するコマンドを実行
// Aile経由じゃなく直接なので暫定
// JSON

$message = "test";
$status = "0";
// URLを送るJSON形式
$array = array(
	"message" => $message ,
	"status" => $status ,
);
$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;

// 再起動
system('../../cmd/reboot.sh > /dev/null &');
