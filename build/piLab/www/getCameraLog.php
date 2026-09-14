<?php
$xml = "../config/CameraLog.xml"; //ファイルを指定
$xmlData = simplexml_load_file($xml); //xmlを読み込む
?>
<p class="table_title">カメラ接続ログ</p>
<table border="2" width="750px">
	<tr>
		<th width="200">日時</th>
		<th width="150">カメラID</th>
		<th>ログ内容</th>
	</tr>

	<?php
	foreach ($xmlData as $log) {
		print("<tr><td>$log->Date</td><td>" . str_pad($log->Unit, 2, 0, STR_PAD_LEFT) ."</td><td>$log->Message</td></tr>\n");
	}
	?>
</table>
