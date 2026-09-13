<?php
header( "Content-Type: application/json; charset=utf-8" );
$nowDate = getdate();
$array["year"] = $nowDate["year"];
$array["month"] = $nowDate["mon"];
$array["day"] = $nowDate["mday"];
$array["hour"] = $nowDate["hours"];
$array["minute"] = $nowDate["minutes"];
$array["second"] = $nowDate["seconds"];
$json = json_encode( $array ) ;
print($json);
?>



