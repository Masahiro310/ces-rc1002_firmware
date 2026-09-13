function isEmptyString(text) {
	if (text == "") {
		return true;
	}
	return false;
}

function isNumber(text) {
	var reg = new RegExp(/^[0-9]+$/);
	var res = reg.test(text);
	return res;
}

function validateBroadcastAddress(arg_1, arg_2, arg_3, arg_4,maskbit)
{
	retval = false;
	if (isNumber(arg_1) && isNumber(arg_2) && isNumber(arg_3) && isNumber(arg_4)&& validateSubnetMask(maskbit)) {
		ip = (arg_1*256*256*256) + (arg_2*256*256) + (arg_3*256) + $arg_4;
		subnetMask = (0xFFFFFFFF << (32-maskbit)) & 0x00FFFFFFFF;
		subnetMask = ~subnetMask & 0x00FFFFFFFF;
		tmpIp = ip | subnetMask;

		if( ip == tmpIp ){
			retval = true;
		}
	}
	return retval;
}
function validateNetworkAddress(arg_1, arg_2, arg_3, arg_4,maskbit)
{
	retval = false;
	if (isNumber(arg_1) && isNumber(arg_2) && isNumber(arg_3) && isNumber(arg_4)&& validateSubnetMask(maskbit)) {
		ip = (arg_1*256*256*256) + (arg_2*256*256) + (arg_3*256) + $arg_4;
		subnetMask = (0xFFFFFFFF << (32-maskbit)) & 0x00FFFFFFFF;
		subnetMask = ~subnetMask & 0x00FFFFFFFF;
		tmpIp = ip & subnetMask;

		if( 0 == tmpIp ){
			retval = true;
		}
	}
	return retval;
}

function validateIpv4(arg_1, arg_2, arg_3, arg_4) {
	retval = true;
	if (isNumber(arg_1) && isNumber(arg_2) && isNumber(arg_3) && isNumber(arg_4)) {
		if (arg_1 < 0 || arg_1 >= 224) {
			retval = false;
		}
		if (arg_2 < 0 || arg_2 > 255) {
			retval = false;
		}
		if (arg_3 < 0 || arg_3 > 255) {
			retval = false;
		}
		if (arg_4 < 0 || arg_4 > 255) {
			retval = false;
		}
	} else {
		retval = false;
	}
	return retval;
}
function validateIpv4orEmpty(arg_1, arg_2, arg_3, arg_4) {
	retval = true;

	if (isNumber(arg_1) && isNumber(arg_2) && isNumber(arg_3) && isNumber(arg_4)) {
		if (arg_1 < 0 || arg_1 >= 224) {
			retval = false;
		}
		if (arg_2 < 0 || arg_2 > 255) {
			retval = false;
		}
		if (arg_3 < 0 || arg_3 > 255) {
			retval = false;
		}
		if (arg_4 < 0 || arg_4 > 255) {
			retval = false;
		}
	} else {
		if (!(isEmptyString(arg_1) && isEmptyString(arg_2) && isEmptyString(arg_3) && isEmptyString(arg_4))) {
			retval = false;
		}
	}
	return retval;
}
function validateSubnetMask(maskBit) {
	retval = true;
	if (isNumber(maskBit)) {
		if (maskBit < 1 || maskBit > 30) {
			retval = false;
		}
	} else {
		retval = false;
	}
	return retval;
}

function validatePortNumber(port) {
	retval = true;
	if (isNumber(port)) {
		if (port <= 0 || port > 65535) {
			retval = false;
		}
	} else {
		retval = false;
	}
	return retval;
}

function validateUserId(param) {
	var reg = new RegExp(/[^\"\'` ]/);
	var regPrintable = new RegExp(/^[\x21-\x7E]{3,32}$/); // printable
	var res = reg.test(param) && regPrintable.test(param);
	return res;
}

function validatePassword(param) {
	var reg = new RegExp(/[^\"\'` ]/);
	var regPrintable = new RegExp(/^[\x21-\x7E]{5,14}$/); // printable
	var res = reg.test(param) && regPrintable.test(param);
	return res;
}

function validateUnitPassword(param) {
	var reg = new RegExp(/^[0-9]{5}$/);
	var res = reg.test(param);
	return res;
}

function validateUri(param) {
	var reg = new RegExp(/^[0-9a-zA-Z.\-_]{0,253}$/);
	var res = reg.test(param);
	return res;
}

function validateDate(param) {
	var reg = new RegExp(/^\d{4}\/\d{1,2}\/\d{1,2}$/);
	var res = reg.test(param);
	return res;
}

function validateHour(param) {
	retval = true;
	if (isNumber(param)) {
		if (param < 0 || param > 23) {
			retval = false;
		}
	} else {
		retval = false;
	}
	return retval;
}

function validateMinute(param) {
	retval = true;
	if (isNumber(param)) {
		if (param < 0 || param > 59) {
			retval = false;
		}
	} else {
		retval = false;
	}
	return retval;
}
function validateSecond(param) {
	retval = true;
	if (isNumber(param)) {
		if (param < 0 || param > 59) {
			retval = false;
		}
	} else {
		retval = false;
	}
	return retval;
}
