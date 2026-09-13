// let unit_time = new Date();
// let month = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
var getDateTimeUrl = createCgiUrl(location, "getTime.php");
var getNtpInfoUrl = createCgiUrl(location, "getNtpInfo.php");
var setNtpInfoUrl = createCgiUrl(location, "setNtpInfo.php");
let unitTimeTimer;
var setCcommandListUrl = createCgiUrl(location, "getTime.php");

function shouErrorMessage(message) {
	$("#dialog_message").text(message);
	$("#dialog").dialog({
		modal: true, //モーダル表示
		title: "入力エラー", //タイトル
		buttons: {
			//ボタン
			確認: function () {
				$(this).dialog("close");
			},
		},
	});
}
function getUnitTime() {
	clearInterval(unitTimeTimer);
	$.ajax({
		async: false,
		url: getDateTimeUrl,
		type: "POST",
		dataType: "json",
		success: function (network) {
			unit_time = new Date(
				Number(network.year),
				Number(network.month) - 1,
				Number(network.day),
				Number(network.hour),
				Number(network.minute),
				Number(network.second)
			);
			setUnitDateTime(unit_time);
		},
	});
}
function getNtpInfo() {
	$.ajax({
		async: true,
		url: getNtpInfoUrl,
		type: "POST",
		dataType: "json",
		success: function (network) {
            $("#ntp_server_addr").val(network.ntp_server_addr);
            setTimeSyncRadio(network.use_ntp);
			$("#ntp_connect_status").text(network.ntp_connect_status);
		},
	});
}
function setNtpInfo() {
	$.blockUI();
	if ($('input:radio[name="time_sync"]:checked').val() == "use_ntp") {
		$used = "true";
	} else {
		$used = "false";
	}
	var send_data =
		"use_ntp=" +
		$used +
		"&" +
		"ntp_server_addr=" +
		encodeURIComponent($("#ntp_server_addr").val());
	var dateStr;
	if($('#pc_sync').prop('checked')){
		var now = new Date();
		dateStr =
		"&" +
		"date=" +
		now.getFullYear() + "/" + month[Number(now.getMonth())] + "/" + now.getDate() +
		"&" +
		"hour_time=" +
		now.getHours() +
		"&" +
		"minute_time=" +
		now.getMinutes() +
		"&" +
		"second_time=" +
		now.getSeconds();
	}
	else{
		dateStr = 
		"&" +
		"date=" +
		$("#date").val() +
		"&" +
		"hour_time=" +
		$("#hour_time").val() +
		"&" +
		"minute_time=" +
		$("#minute_time").val() +
		"&" +
		"second_time=" +
		$("#second_time").val();
	}
	send_data += dateStr;
	$.ajax({
		async: true,
		url: setNtpInfoUrl,
		data: send_data,
		type: "POST",
		dataType: "json",
		success: function (res) {
			$.unblockUI();
			if (res["status"] != 0) {
				alert(res["message"]);
			} else {
				getUnitTime();
				getNtpInfo();
				setNowTime();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
            alert("接続エラー");
			getUnitTime();
			getNtpInfo();
			$.unblockUI();
		},
	});
}
function setNowTime() {
	// var now = new Date();
	var now = unit_time;
	$("#date").val(
		now.getFullYear() +
			"/" +
			month[Number(now.getMonth())] +
			"/" +
			("0" + now.getDate()).slice(-2)
	);
	$("#hour_time").val(("0" + now.getHours()).slice(-2));
	$("#minute_time").val(("0" + now.getMinutes()).slice(-2));
	$("#second_time").val(("0" + now.getSeconds()).slice(-2));
}
function clearNtpInfo() {
    $("#ntp_server_addr").val("");
    setTimeSyncRadio("false");  // manual
    $("#ntp_connect_status").text("");
}
function setTimeSyncRadio(isntp){
    if (isntp == "true") {
        $('input:radio[name="time_sync"]').val(["use_ntp"]);
    } else {
        $('input:radio[name="time_sync"]').val(["manual"]);
    }
    setActiveDateItem($("input:radio[name=time_sync]:checked").val());
}

function setActiveDateItem(cmd_type) {
	if (cmd_type == "use_ntp") {
		$("#ntp_server_addr").prop("disabled", false);
		$("#pc_sync").prop("disabled", true);
		$("#date").prop("disabled", true);
		$("#date").css("cursor", "not-allowed");
		$("#date").css("background-color","#eee");
		$("#hour_time").prop("disabled", true);
		$("#minute_time").prop("disabled", true);
		$("#second_time").prop("disabled", true);
	} else if (cmd_type == "manual") {
		$("#ntp_server_addr").prop("disabled", true);
		$("#pc_sync").prop("disabled", false);
		if($('#pc_sync').prop('checked')){
			$("#date").prop("disabled", true);
			$("#date").css("cursor", "not-allowed");
			$("#date").css("background-color","#eee");
			$("#hour_time").prop("disabled", true);
			$("#minute_time").prop("disabled", true);
			$("#second_time").prop("disabled", true);
			}
		else{
			$("#date").prop("disabled", false);
			$("#date").css("cursor", "default");
			$("#date").css("background-color","#FFFFFF");
			$("#hour_time").prop("disabled", false);
			$("#minute_time").prop("disabled", false);
			$("#second_time").prop("disabled", false);	
		}
	}
}

function initDateSetting() {
	$("input:radio[name=time_sync]").change(function () {
		setActiveDateItem($(this).val());
	});
    setActiveDateItem($("input:radio[name=time_sync]:checked").val());
}
