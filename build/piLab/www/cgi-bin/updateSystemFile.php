<?php
$array = array(
    "status" => "0",
    "message" => ""
);
$cesrc1002_install_file = "/opt/installer/INSTALLCESRC1002";
$uploadfile = "/opt/installer/cesrc1002/cesrc1002.tar.gz";
$status = "0";
if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadfile)) {
        system("../../cmd/validateSystemFile.sh", $status);
        if ($status == "0") {
           file_put_contents($cesrc1002_install_file, "" );
        }
        else{
            unlink($cesrc1002_install_file);
            $array["status"] = "-3";
            $array["message"] = "バージョンアップファイルではありません";
       }
   } else {
        $array["status"] = "-1";
        $array["message"] = "アップロードに失敗しました";
    }
} else {
    $array["status"] = "-2";
    $array["message"] = "ファイルが選択されていません";
}

$json = json_encode($array);
header( "Content-Type: application/json; charset=utf-8" );
echo $json;

