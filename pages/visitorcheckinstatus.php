<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
	require_once './api.php';
	login_check_r();
	
	header('Content-Type: application/json');
	$checkinvisitor=getCheckinVisitor();
	echo(json_encode(array('visitor_checkedin' => !empty($checkinvisitor))));
?>
	

