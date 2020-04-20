<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();
	admin_check_r();
	checkCSRF();

	if(isset($_POST['fdeid'])){
		$id	= intval($_POST['fdeid']);
		$result=de_promoteFrontdeskemployee($id,true);
		header('Location: index.php?p=management&tab=tab_user_management&highlightemployee='.$id);
		die();
	}else{
		header('Location: index.php?p=management&msg=missing_params&tab=tab_user_management');
		die();
	}
?>

