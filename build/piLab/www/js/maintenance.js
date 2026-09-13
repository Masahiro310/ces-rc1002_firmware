var loading_img = '<br><br><img src="img/loading.gif" width="40" height="40" alt="now executing">';

$(function () {
	$.ajaxSetup({ cache: false, asysnc: true, timeout: 90000 });
	//	alterWidth4RadiusButton();

	// ファイル転送エラーダイアログ
	$("#file_transfer_err").dialog({
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
	$("#file_select_err").dialog({
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

	// 取扱説明書ダウンロード実行確認ダイアログ
	$("#confirm_dl_um").dialog({
		modal: true,
		autoOpen: false,
		width: 410,
		resizable: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
		buttons: {
			OK: function () {
				// 設定ファイルの転送
				dlUserManual();
				$(this).dialog("close");
			},
			Cancel: function () {
				$(this).dialog("close");
			},
		},
	});

	// 設定ファイル作成アプリケーションダウンロード実行確認ダイアログ
	$("#confirm_dl_sa").dialog({
		modal: true,
		autoOpen: false,
		width: 450,
		resizable: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
		buttons: {
			OK: function () {
				// 設定ファイルの転送
				dlSettingApp();
				$(this).dialog("close");
			},
			Cancel: function () {
				$(this).dialog("close");
			},
		},
	});

	// サポートデータ取得実行確認ダイアログ
	$("#confirm_dl_logs").dialog({
		modal: true,
		autoOpen: false,
		width: 360,
		resizable: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
		buttons: {
			OK: function () {
				// 設定ファイルの転送
				dlSystemLogFiles();
				$(this).dialog("close");
			},
			Cancel: function () {
				$(this).dialog("close");
			},
		},
	});
	// バージョンアップ実行確認ダイアログ
	$("#confirm_update").dialog({
		modal: true,
		autoOpen: false,
		width: 350,
		resizable: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
		buttons: {
			OK: function () {
				// 設定ファイルの転送
				transferUpdateFile();
				$(this).dialog("close");
			},
			Cancel: function () {
				$(this).dialog("close");
			},
		},
	});

	// バージョンアップ実行完了ダイアログ
	$("#during_update").dialog({
		modal: true,
		autoOpen: false,
		width: 350,
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

	// 実行中ダイアログ
	$("#executing_process").dialog({
		modal: true,
		autoOpen: false,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close").hide();
		},
	});

	// バージョンアップ実行ボタンクリック時の処理
	$("#btn_update_execute").click(function () {
		var file = $("#file-input1").prop("files")[0];
		if (file != null) {
			// Basic認証がOKの場合ダイアログ表示
			if (doBasicAuthorize()) {
				$("#confirm_update").dialog("open");
			}
		} else {
			$("#file_select_err").dialog("open");
		}
	});

	// 取扱説明書ダウンロード実行ボタンクリック時の処理
	$("#btn_dl_um_execute").click(function () {
		// Basic認証がOKの場合ダイアログ表示
		if (doBasicAuthorize()) {
			$("#confirm_dl_um").dialog("open");
		}
	});

	// 設定ファイル作成アプリケーションダウンロード実行ボタンクリック時の処理
	$("#btn_dl_sa_execute").click(function () {
		// Basic認証がOKの場合ダイアログ表示
		if (doBasicAuthorize()) {
			$("#confirm_dl_sa").dialog("open");
		}
	});

	// サポートデータダウンロード実行ボタンクリック時の処理
	$("#btn_dl_logs_execute").click(function () {
		// Basic認証がOKの場合ダイアログ表示
		if (doBasicAuthorize()) {
			$("#confirm_dl_logs").dialog("open");
		}
	});

	// 設定初期化実行ボタンクリック時の処理
	$('#btn_initialize_log').click(function(){
		// Basic認証がOKの場合ダイアログ表示
// common.js が　読み込まれていないのか関数が動かない
//		if(doBasicAuthorize()){
//			if(!isWebFuncLimited()){
				$('#initialize_log').dialog('open');
//			}
//		}
	});
	// 設定初期化実行確認ダイアログ
	$('#initialize_log').dialog({
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
				initializeLogFiles();
				$(this).dialog('close');
			},
			'Cancel': function(){
				$(this).dialog('close');
			}
		}	
	});

	$('#comp_initialize_log').dialog({
		modal: true,
		autoOpen: false,
		width: 340,
		resizable: false,
		open: function(event, ui){
			$('.ui-dialog-titlebar-close').hide();
		},
		buttons: {
			'OK': function(){
				$(this).dialog('close');
			},
		}	
	});

	$(function () {
		var ua = window.navigator.userAgent.toLowerCase();
		if (ua.indexOf("safari") != -1) {
			$("#file-input1").css("width", "0px");
			$("#file-input1").css("height", "0px");
			$("#file-input1").css("display", "0px");
		}
	});

	$("#file-selector1").click(function () {
		$("#file-input1").click();
	});

	$("#file-input1").change(function () {
		$("#selected-file1").html($(this).val());
	});

	$("#file-deletor1").click(function () {
		$("#file-input1").val("");
		$("#selected-file1").html("select file...");
	});
	getProductInfo();
});

function initializeLogFiles(){
	var cgiUrl = createCgiUrl(location, "initializeLogFiles.php");

	$.ajax({
		type: 'POST',
		url: cgiUrl,
		data: {},
		dataType: 'json',
		success: function(res){
			if(res['status'] != 0){
				// ファイル転送エラー
				alert(res['message']);
			}
			else{
				$('#comp_initialize_log').dialog('open');				
			}
		}
	});
}

function getProductInfo(){
    var res;
    var cgiUrl = createCgiUrl(location, "getProductInfo.php");
    
    $.ajax({
        async: false,
        type: 'POST',
        url: cgiUrl,
        data: {},
        dataType: 'json',
        complete: function(jqXHR, textStatus){
            if(textStatus == const_timeout){
                $('#timeout_error').dialog('open');
            }
        },
        success: function(res){
            if(res['status'] == 0){
				$("#soft_ver").text(res['version']);
            }
        }
    });
}

function transferUpdateFile() {
	var cgiUrl = createCgiUrl(location, "updateSystemFile.php");
	var file = $("#file-input1").prop("files")[0];
	var res;

	var fd = new FormData();
	fd.append("file", file);
	var postData = {
		type: "POST",
		dataType: "text",
		data: fd,
		processData: false,
		contentType: false,
	};

	$("#executing_process").dialog("open");
	$("#executing_process_message").html("ファイル転送中...." + loading_img);

	var jqxhr = $.ajax(cgiUrl, postData).done(function (res) {
		var tmp = JSON.parse(res);
		if (tmp["status"] != 0) {
			// ファイル転送エラー
			$("#file_transfer_err").dialog("open");
			$("#file_transfer_err_msg").html(tmp["message"]);
		} else {
			// システム再起動
			execute_restart("during_update", "AILEUN_APP_REBOOT");
		}
	});

	jqxhr.always(function () {
		$("#executing_process").dialog("close");
		$("#executing_process_message").html("");
		if (jqxhr.statusText == const_timeout) {
			$("#timeout_error").dialog("open");
		}
	});
}

function dlSystemLogFiles() {
	var cgiUrl = createCgiUrl(location, "getSystemLogFiles.php");

	$("#executing_process").dialog("open");
	$("#executing_process_message").html("ファイル転送中...." + loading_img);

	$.ajax({
		type: "POST",
		url: cgiUrl,
		data: {},
		dataType: "json",
		timeout: 300000,
		complete: function (jqXHR, textStatus) {
			$("#executing_process").dialog("close");
			$("#executing_process_message").html("");
			if (textStatus == const_timeout) {
				$("#timeout_error").dialog("open");
			}
		},
		success: function (res) {
			if (res["status"] != 0) {
				alert(res["message"]);
			} else {
				location.href = res["data"]["RedirectUrl"];
			}
		},
	});
}
