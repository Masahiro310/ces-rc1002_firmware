function initializeConfigFiles(){
	var cgiUrl = createCgiUrl(location, "initializeConfigFiles.php");
	var send_data = "";

	if ($('input:radio[name="without_netconf"]:checked').val() == "without_net") {
		send_data = "config=without_net";
	} else {
		send_data = "config=init_all";
	}

	$.ajax({
		type: 'POST',
		url: cgiUrl,
		data: send_data,
		dataType: 'json',
		success: function(res){
			if(res['status'] != 0){
				// ファイル転送エラー
				$('#file_transfer_err2').dialog('open');
				$("#file_transfer_err2_msg").html(res['message']);
			} else{
				// システム再起動
				// execute_restart('during_initialize_setting', 'AILEUN_CONF_UP_REBOOT');
				$('#during_initialize_setting').dialog('open');

			}
		}
	});
}

function dlConfigFiles(){
	var cgiUrl = createCgiUrl(location, "getConfigFiles.php");

	$('#executing_process').dialog('open');
	$('#executing_process_message').html('ファイル転送中....' + loading_img);

	$.ajax({
		type: 'POST',
		url: cgiUrl,
		data: {},
		dataType: 'json',
		complete: function(jqXHR, textStatus){
			if(textStatus == const_timeout){
				$('#timeout_error').dialog('open');
			}
			$('#executing_process').dialog('close');
			$('#executing_process_message').html("");

		},

		success: function(res){
			if(res['status'] != 0){
				// ファイル転送エラー
				$('#file_transfer_err2').dialog('open');
				$("#file_transfer_err2_msg").html(res['message']);
			}else{
				location.href = res['data']['RedirectUrl'];
			}
		}
	});
}

// 設定ファイル転送
function transferSettingsFile() {
	var cgiUrl = createCgiUrl(location, "loadSystemSettings.php");
	var file = $('#file-input2').prop('files')[0];
	var res;

	var fd = new FormData();
	fd.append("file", file);
	var postData = {
	        type: 'POST',
	        dataType: 'text',
	        data: fd,
	        processData: false,
	        contentType: false
	    };
    
	$('#executing_process').dialog('open');
	$('#executing_process_message').html('ファイル転送中....' + loading_img);

	var jqxhr = $.ajax(cgiUrl, postData).done(function(res){
		var tmp = JSON.parse(res);
		if(tmp['status'] != 0){
			// ファイル転送エラー
			$('#file_transfer_err2').dialog('open');
			$("#file_transfer_err2_msg").html(tmp['message']);
		}else{
			// システム再起動
			// execute_restart('during_setting_load2', 'AILEUN_CONF_UP_REBOOT');
			$('#during_setting_load2').dialog('open');
		}
	});

	jqxhr.always(function(){
		$('#executing_process').dialog('close');
		$('#executing_process_message').html("");
		if(jqxhr.statusText == const_timeout){
			$('#timeout_error').dialog('open');
		}
	});
}

$(function () {
	$.ajaxSetup({ cache: false, asysnc: true, timeout: 90000 });
	// 実行中ダイアログ
	$('#executing_process').dialog({
		modal: true,
		autoOpen: false,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		}
	});
	
	// ファイル転送エラーダイアログ
	$("#file_transfer_err2").dialog({
		modal: true,
		autoOpen: false,
		width: 480,
		resizable: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
		buttons: {
			OK: function () {
				$(this).dialog("close");
			},
		},
	});

	// ファイル選択エラーダイアログ
	$("#file_select_err2").dialog({
		modal: true,
		autoOpen: false,
		resizable: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
		buttons: {
			OK: function () {
				$(this).dialog("close");
			},
		},
	});

	// 設定ダウンロード実行ボタンクリック時の処理
	$('#btn_save_execute').click(function(){
		// Basic認証がOKの場合ダイアログ表示
// common.js が　読み込まれていないのか関数が動かない
//		if(doBasicAuthorize()){
//			if(!isWebFuncLimited()){
				$('#confirm_dl_config').dialog('open');
//			}
//		}
	});

	// 設定アップロード実行ボタンクリック時の処理
	$('#btn_load_execute').click(function(){
		var file = $('#file-input2').prop('files')[0];
		if(file != null){
			// Basic認証がOKの場合ダイアログ表示
//			if(doBasicAuthorize()){
//				if(!isWebFuncLimited()){
					$('#confirm_setting_load').dialog('open');
//				}
//			}
		}else{
			$('#file_select_err2').dialog('open');
		}
	});

	// 設定初期化実行ボタンクリック時の処理
	$('#btn_initialize').click(function(){
		// Basic認証がOKの場合ダイアログ表示
// common.js が　読み込まれていないのか関数が動かない
//		if(doBasicAuthorize()){
//			if(!isWebFuncLimited()){
				$('#initialize_setting').dialog('open');
//			}
//		}
	});
	// 設定初期化実行確認ダイアログ
	$('#initialize_setting').dialog({
		modal: true,
		autoOpen: false,
		width: 340,
		resizable: false,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		},
		buttons: {
			'OK': function(){
				// 設定ファイルの取得
				initializeConfigFiles();
				$(this).dialog('close');
			},
			'Cancel': function(){
				$(this).dialog('close');
			}
		}	
	});
	$('#during_initialize_setting').dialog({
		modal: true,
		autoOpen: false,
		width: 350,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		},
		buttons: {
			'OK': function(){
				$(this).dialog('close');
			}
		}
	});
	// 設定ダウンロード実行確認ダイアログ
	$('#confirm_dl_config').dialog({
		modal: true,
		autoOpen: false,
		width: 340,
		resizable: false,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		},
		buttons: {
			'OK': function(){
				// 設定ファイルの取得
				dlConfigFiles();
				$(this).dialog('close');
			},
			'Cancel': function(){
				$(this).dialog('close');
			}
		}	
	});

	// 設定アップロード実行確認ダイアログ
	$('#confirm_setting_load').dialog({
		modal: true,
		autoOpen: false,
		width: 400,
		resizable: false,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		},
		buttons: {
			'OK': function(){
				// 設定ファイルの転送
				transferSettingsFile();
				$(this).dialog('close');
			},
			'Cancel': function(){
				$(this).dialog('close');
			}
        }
	});

	// 設定アップロード実行完了ダイアログ
	$('#during_setting_load2').dialog({
		modal: true,
		autoOpen: false,
		width: 350,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		},
		buttons: {
			'OK': function(){
				$(this).dialog('close');
			}
		}
	});

	$("#file-selector2").click(function () {
		$("#file-input2").click();
	});

	$("#file-input2").change(function () {
		$("#selected-file2").html($(this).val());
	});

	$("#file-deletor2").click(function () {
		$("#file-input2").val("");
		$("#selected-file2").html("select file...");
	});
});
