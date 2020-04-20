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

	if(isset($_POST['name'])){
		$name	= $_POST['name'];
		$result = addContactPerson($name);
		$id=intval($result);
		header('Location: index.php?p=management&highlightcontactperson='.$id.'&tab=tab_contactperson_title');
		die();
	}else{
		header('Location: index.php?p=management&msg=missing_params&tab=tab_contactperson_title');
		die();
	}
?>
