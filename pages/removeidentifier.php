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

	if(isset($_POST['identifierid'])){
		$id	= intval($_POST['identifierid']);
		$result = setIdentifierDeleted($id);
		$id = intval($result);
		header('Location: index.php?p=management&tab=tab_identifier_title&highlightidentifier='.$id);
		die();
	}else{
		header('Location: index.php?p=management&msg=missing_params&tab=tab_identifier_title');
		die();
	}
?>
