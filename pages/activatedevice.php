<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();

	if(isset($_POST['device_id'])){
		$id=intval($_POST['device_id']);
		activateDevice($id);
		header('Location: index.php?p=management&tab=tab_devices');
		die();
	}else{
		header('Location: index.php?p=management&tab=tab_devices&msg=missing_params');
		die();
	}
?>
