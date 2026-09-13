<?php
#=======================================================================
# FileName : orgin:getAileLogFiles.pl
# Summary  : Aileunログファイル取得API
# ----------------------------------------------------------------------
# DATE       NAME               REASON
# ---------- ------------------ ----------------------------------------
#{
#	"message": "testMsg",
#	"status": "0", // 0：正常　0以外：messageが表示されるっぽぃ
#	"data": {
#		"RedirectUrl": "filePath"
#	}
#}
#=======================================================================

$zipname;
exec("../../cmd/cmpressBackuplog.sh ../data", $zipname);

$message="ok";
$status="0";
$RedirectUrl="/data/";
// zipファイルが存在するかでステータスを異常とかにするか・・・
// cmpressBackuplog.shのsudo権限周りの修正が必要

// URLを送るJSON形式
$array = array(
	"message" => $message ,
	"status" => $status ,
	"data" => array(
		"RedirectUrl" => $RedirectUrl.$zipname[0] ,
	),
);
$json = json_encode( $array ) ;
header( "Content-Type: application/json; charset=utf-8" );
echo $json;

