<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';

	header('Content-Type: application/json');
	$checkin	= getCheckinVisitorCount();
	$checkout 	= getCheckoutVisitorCount();
	echo(json_encode(array(
		'checkout' => $checkout,
		'checkin' => $checkin))
	);
?>
