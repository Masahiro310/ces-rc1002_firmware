function setCameraSetting() {
	$.blockUI();
	var send_data = "";
	var setCameraListUrl = createCgiUrl(location, "setCameraList.php");

	start = Number($("#cam_base").val());
	end = Number(start) + Number($("#cam_num").val());
	for (; start < end; start++) {
		if (send_data != "") {
			send_data += "&";
		}
		send_data +=
			"cam" +
			start +
			'_maker=' +
			$("#cam" + start + "_maker").val() +
			'&' +
			"cam" +
			start +
			'_ipaddr=' +
			encodeURIComponent($("#cam" + start + "_ipaddr").val()) + 
			'&' +
			"cam" +
			start +
			'_http_port=' +
			$("#cam" + start + "_http_port").val() +
			'&' +
			"cam" +
			start +
			'_user=' +
			encodeURIComponent($("#cam" + start + "_user").val()) + 
			'&' +
			"cam" +
			start +
			'_password=' +
			encodeURIComponent($("#cam" + start + "_password").val()) + 
			'&' +
			"cam" +
			start +
			'_pan=' +
			$("#cam" + start + "_pan").val() +
			'&' +
			"cam" +
			start +
			'_tilt=' +
			$("#cam" + start + "_tilt").val();
	}

	$.ajax({
		async: true,
		url: setCameraListUrl,
		data: send_data,
		type: "POST",
		dataType: "json",
		success: function (res) {
			$.unblockUI();
			if (res["status"] != 0) {
				alert(res["message"]);
			}
			else{
				loadCameraList($("#cam_base").val(), true);
				$('body,html').animate({ scrollTop: 0 }, 500);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert("接続エラー");
//			loadCameraList($("#cam_base").val(), true);
			$.unblockUI();
		},
	});
}
function clearCameraSetting() {
	start = Number($("#cam_base").val());
	end = Number(start) + Number($("#cam_num").val());
	for (; start < end; start++) {
		$key = "#cam" + start + "_ipaddr";
		$("#cam" + start + "_maker").val("Panasonic1");
		$("#cam" + start + "_ipaddr").val("");
		$("#cam" + start + "_http_port").val("80");
		$("#cam" + start + "_user").val("");
		$("#cam" + start + "_password").val("");
		$("#cam" + start + "_pan").val("false");
		$("#cam" + start + "_tilt").val("false");
	}
}
