<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';

	header('Content-Type: application/json');
	$status = getInputStatus();
	if(!$status){
		echo(json_encode(array('awaiting_input' => 0, 'device_name' => "not_set")));
	}else{
		$awaitingInput = $status["flag_value"] == 1? "true":"false";
		$deviceName = getActiveDevice();
		$deviceName=$deviceName === false? "not_set":$deviceName;
		echo(json_encode(array('awaiting_input' => $awaitingInput, 'device_name' => $deviceName)));
	}
?>
