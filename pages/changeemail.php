<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();
	checkCSRF();

	if(isset($_POST['email']) && isset($_POST['uid'])){
		$uid = $_POST['uid'];
		$email = $_POST['email'];
		$result = changeEmail($email,$uid);
		header('Location: index.php?p=management&tab=tab_contactperson_title&msg=email_changed&type=success&highlightcontactperson='.$uid);
		die();
	}else{
		header('Location: index.php?p=management&tab=tab_contactperson_title&msg=missing_params');
		die();
	}
?>
