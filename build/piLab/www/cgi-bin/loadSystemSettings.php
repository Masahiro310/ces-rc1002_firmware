<?php
/*
$_FILES["file"]["tmp_name"]
*/

// JSON
$message = "";
$status = "0";

// 一時ファイルの場所
$tmp_file=$_FILES["file"]["tmp_name"];


// 現状ces_rc1002固定
$filename = "ces_rc1002.zip";

// ファイルの保存場所
$file = "/tmp/" . $filename;

// ファイル移動
if (!rename($tmp_file, $file)) {
    $message = "file move error";
    $status = 1;
}

// Filecheck
if ($status == "0") {
    system("../../cmd/validateConfigFile.sh {$filename}", $status);
}

if ($status == 0) {
    system("../../cmd/uncmpressConfigFile.sh" , $status);
} else {
    $message = "file check error";
    $status = "1";
}

// 展開後、webユーザーパスワードを設定
if ($status == 0) {
    system("../../cmd/updateWebUser.sh" , $status);
}

// web portを設定
if ($status == 0) {
    system("../../cmd/updateWebPort.sh" , $status);
}

// URLを送るJSON形式
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