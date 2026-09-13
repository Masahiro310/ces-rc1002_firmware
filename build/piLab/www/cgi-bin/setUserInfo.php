<?php
require('validate.php');
require('files.php');
require('unitSetting.php');
$paramKeys = array('user', 'password', 'conf_pass', 'unit_password');

if (!validateParams($_POST, $paramKeys)) {
	http_response_code(500);
	return;
}
if (
	$_POST['password'] === $_POST['conf_pass']
	&& validateUserId($_POST['user'])
	&& validatePassword($_POST['password'])
	&& validateUnitPassword($_POST['unit_password'])
) {

	UpdateUnitConfig("WebUser", $_POST['user']);
	UpdateUnitConfig("WebPassword", $_POST['password']);
	UpdateUnitConfig("UnitPassword", $_POST['unit_password']);

	exec( "../../cmd/updateWebUser.sh" );

} else {
	http_response_code(500);
	print(var_dump($_POST));
	return;
}
exec("../../cmd/apps_restart.sh > /dev/null &");
