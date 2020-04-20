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

	if(isset($_POST['name'])&&isset($_POST['description'])){
		$name = $_POST['name'];
		$description = $_POST['description'];
		$result = addIdentifier($name,$description);
		$id = intval($result);
		header('Location: index.php?p=management&highlightidentifier='.$id.'&tab=tab_identifier_title');
		die();
	}else{
		header('Location: index.php?p=management&msg=missing_params&tab=tab_identifier_title');
		die();
	}
?>
