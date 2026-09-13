var const_success = "success";
var const_timeout = "timeout";
var loading_img = '<br><br><img src="img/loading.gif" width="40" height="40" alt="now executing">';
var require_mark_html = '<span class="require font-small">(*)</span>';
var repTime = 1000;

/*
$(function(){
    $('#func_limited_err').dialog({
        modal: true,
        autoOpen: false,
        minWidth: 480,
        resizable: false,
        open: function(event, ui){
            $(".ui-dialog-titlebar-close").hide();
        },
        buttons: {
            'OK': function() {
                 $('#func_limited_err').dialog('close');
             }
        }
    });

    $('#timeout_error').dialog({
        modal: true,
        autoOpen: false,
        minWidth: 290,
        resizable: false,
        open: function(event, ui){
            $(".ui-dialog-titlebar-close").hide();
        },
        buttons: {
            'OK': function() {
                 $('#timeout_error').dialog('close');
             }
        }
    });
});
*/

function createUrl(lcobj, htmlFile){
    var protocol     = lcobj.protocol;
    var host         = lcobj.host;
    var pathName     = lcobj.pathname;
    var tmp          = pathName.split("/");
    var baseHtmlFile = tmp[tmp.length-1];
    var base         = pathName.replace(baseHtmlFile, '');
    var html_url     = protocol + '//' + host + base + htmlFile;

    return html_url;
}

function createDataDlUrl(lcobj, dataFile){
    var protocol     = lcobj.protocol;
    var host         = lcobj.host;
    var pathName     = lcobj.pathname;
    var tmp          = pathName.split("/");
    var baseHtmlFile = tmp[tmp.length-1];
    var base         = pathName.replace(baseHtmlFile, '');
    var data_url     = protocol + '//' + host + base + dataFile;

    return data_url;
}

function createCgiUrl(lcobj, cgiFile){
    var protocol = lcobj.protocol;
    var host     = lcobj.host;
    var pathName = lcobj.pathname;
    var tmp      = pathName.split("/");
    var htmlFile = tmp[tmp.length-1];
    var base     = pathName.replace(htmlFile, '');
    base         = base.replace("sp/", '');
    var cgi_url  = protocol + '//' + host + '/cgi-bin' + base + cgiFile;

    return cgi_url;
}

// システム再起動
function execute_restart(id, reqType) {
	var cgiUrl = "";
	var res;
	cgiUrl = createCgiUrl(location, "interProcessCom.php");
	var res;
	var obj = {
		btnId: "btnExecReboot",
		reqType: reqType,
		targetView: -999
	};
	var sendData = JSON.stringify(obj);

	$.ajax({
		type: 'POST',
		url: cgiUrl,
		data: {
			data: sendData
		},
		dataType: 'json',
		complete: function(jqXHR, textStatus){
		if(textStatus == const_timeout){
			$('#timeout_error').dialog('open');
		}
	},
	success: function(res) {
		$('#'+id).dialog('open');
	}
	});
}

function getDeviceType(){
    var ua = navigator.userAgent;
    var deviceType = "pc";

    if(ua.indexOf('iPhone') > 0 && ua.indexOf('iPad') == -1 || ua.indexOf('iPod') > 0){
        deviceType = "sp";
    }else if(ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0){
        deviceType = "sp";
    }else if(ua.indexOf('iPad') > 0 || ua.indexOf('Android') > 0){
        deviceType = "tab";
    }else{
        deviceType = "pc";
    }
    
    return deviceType;
}

function alterWidth4RadiusButton() {
    var str = $('.radiusButton').text;
    var width = 1.4 * (str.length + 2);
    $('.radiusButton').css('width', width + "em");
}

function trim(str){
    var tmpStr = str;

    tmpStr = tmpStr.replace(/^\s+/, "");
    tmpStr = tmpStr.replace(/\s+$/, "");

    return tmpStr;
}

function autoMenuClose(){
    $('.btn-navbar').click();
}

function setSoftwareVersion(version){
    if($('#soft_ver')){
        $('#soft_ver').text(version);
    }
}

function doBasicAuthorize(){
     var res;
     var cgiUrl = createCgiUrl(location, "doWebAccessAuthorize.php");
     var authorized = false;

     $.ajax({
         async: false,
         url: cgiUrl,
         type: 'POST',
         data: {},
         dataType: 'json',
         success: function(res){
             authorized = true;
         },
         error: function(res){
             authorized = false;
         }
     });

     return authorized;
//return true;
}

function isWebFuncLimited(){
    var res;
    var cgiUrl = createCgiUrl(location, "getWebFuncLimitedStatus.pl");
    var funcLimited = false;

funcLimited = true;
/*
    $.ajax({
        async: false,
        url: cgiUrl,
        type: 'POST',
        data: {},
        dataType: 'json',
        success: function(res){
            if(res['status'] != 0){
                funcLimited = true;
                $('#func_limited_err_msg').html(res['message']);
                $('#func_limited_err').dialog('open');
            }
        }
    });
*/
    return funcLimited;
}

function getKeyStrings(length){
    return createKeyStrings(length);
}

function createKeyStrings(length){
    return Math.random().toString(36).slice(-length);
}

