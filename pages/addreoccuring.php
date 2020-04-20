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

	if(isset($_POST['code'])&&isset($_POST['name'])&&isset($_POST['surname'])&&isset($_POST['company'])&&isset($_POST['contactperson'])){
		$code			= $_POST['code'];
		$name			= $_POST['name'];
		$surname		= $_POST['surname'];
		$company		= $_POST['company'];
		$contactperson		= $_POST['contactperson'];
		$result 	= addReoccuring($code,$name,$surname,$company,$contactperson);
		$id=intval($result);
		header('Location: index.php?p=management&highlightreoccuring='.$id.'&tab=tab_reoccuring_title');
		die();
	}else{
		header('Location: index.php?p=management&msg=missing_params&tab=tab_reoccuring_title');
		die();
	}
?>
