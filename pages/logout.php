<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	

	defined('APP_RAN') or die();
	require_once './api.php';
	checkCSRF();

	logout();
	header('Location: index.php?p=loginform&msg=loggedout&type=success');
	die();
?>