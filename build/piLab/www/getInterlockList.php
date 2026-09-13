<?php
$xml = "../config/Matrix.xml"; //ファイルを指定
$xmlData = simplexml_load_file($xml); //xmlを読み込む

$jsonCamera = array();
$jsonList = array();

$base_camera = 1;
$endIndex = 100;

$chCount = exec("../cmd/getCameraCount.sh");
if($chCount == 99){
	$aileun_count = 10;
	$endIndex = 99;
}
else if($chCount == 36){
	$aileun_count = 9;
	$endIndex = 36;
}
else if($chCount == 16){
	$aileun_count = 4;
	$endIndex = 16;
}
else{
	$aileun_count = 1;
	$endIndex = 4;
}


// 空要素を生成(xmlのデータなしのケースでもデータを返信する)
$emptyCamera = array('id' => '1', 'AileunID' => '', 'Ch' => '');
for ($index = $base_camera; $index <= $endIndex; $index++) {
	$emptyCamera['id'] = $index;
	$jsonList[$index] = $emptyCamera;
}

foreach ($xmlData as $camera) {
	/// ID
	foreach ($camera->attributes() as $key => $value) {
		$jsonCamera[$key] = (string)$value;
	}
	foreach ($camera->children() as $key => $value) {
		$jsonCamera[$key] = (string)$value;
	}
	if ($base_camera <= $jsonCamera['id'] && $jsonCamera['id'] <= $endIndex && $jsonCamera['AileunID'] <= $aileun_count) {
		$jsonList[$jsonCamera['id']] = $jsonCamera;
	}
}

$aileuns = array('' => '--');
for ($index = 1; $index <= $aileun_count; $index++) {
	$aileuns[(string)$index] = sprintf('エルーア%02d', $index);
}
$channels = array('1' => '01CH', '2' => '02CH', '3' => '03CH', '4' => '04CH', '5' => '05CH', '6' => '06CH', '7' => '07CH', '8' => '08CH', '9' => '09CH', '10' => '10CH', '11' => '11CH', '12' => '12CH', '13' => '13CH', '14' => '14CH', '15' => '15CH', '16' => '16CH', '17' => '17CH', '18' => '18CH', '19' => '19CH', '20' => '20CH', '21' => '21CH', '22' => '22CH', '23' => '23CH', '24' => '24CH', '25' => '25CH', '26' => '26CH', '27' => '27CH', '28' => '28CH', '29' => '29CH', '30' => '30CH', '31' => '31CH', '32' => '32CH', '33' => '33CH', '34' => '34CH', '35' => '35CH', '36' => '36CH');

print('<p class="table_title">カメラ割付</p>' . PHP_EOL);
print('<input id="camera_num" type="hidden" value="' . $endIndex . '"/>' . PHP_EOL);

print('<table border="2" width="750px">' . PHP_EOL);
print('<tr>' . PHP_EOL);
print('<th width="200px" height="50px">カメラCH</th>' . PHP_EOL);
print('<th>連動エルーア選択</th>' . PHP_EOL);
print('<th>切替カメラCH選択</th>' . PHP_EOL);
print('</tr>' . PHP_EOL);

foreach ($jsonList as $camera) {

	print('<tr>' . PHP_EOL);
	print('<th>' . str_pad($camera['id'], 2, 0, STR_PAD_LEFT) . 'CH</th>' . PHP_EOL);

	print('<td>' . PHP_EOL);
	print('<select id="camera' . $camera['id'] . '_unit" value="">' . PHP_EOL);
	if (strlen($camera['AileunID']) <= 0) {
		$camera['AileunID'] = 0;
	}
	foreach ($aileuns as $key => $value) {
		if ($key == $camera['AileunID']) {
			print('<option value="' . $key . '" selected>' . $value . '</option>' . PHP_EOL);
		} else {
			print('<option value="' . $key . '">' . $value . '</option>' . PHP_EOL);
		}
	}
	print('</select></td>' . PHP_EOL);

	print('<td>' . PHP_EOL);
	print('<select id="camera' . $camera['id'] . '_ch" value="">' . PHP_EOL);
	if (strlen($camera['Ch']) <= 0) {
		$camera['Ch'] = 0;
	}
	foreach ($channels as $key => $value) {
		if ($key == $camera['Ch']) {
			print('<option value="' . $key . '" selected>' . $value . '</option>' . PHP_EOL);
		} else {
			print('<option value="' . $key . '">' . $value . '</option>' . PHP_EOL);
		}
	}
	print('</select></td></tr>' . PHP_EOL);
}
print('</table>' . PHP_EOL);
print('<br><br><br>'  . PHP_EOL);

print('<footer><div class="button_place"><button type="button" class="btn btn-primary btn-lg" style="margin-right: 5px;" id="interlock_setting" >設定</button><button type="button" class="btn btn-primary btn-lg" style="margin-left: 5px;" id="clear_setting">クリア</button></div></footer>');
