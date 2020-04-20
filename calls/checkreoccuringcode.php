<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';

	if(isset($_GET['code'])){
		$result=checkReoccuringCode($_GET['code']);
		if($result){
			header('Content-Type: application/json');
			echo(json_encode($result));
			http_response_code(200);
			die();
		}else{
			http_response_code(404);
			die();
		}
	}else{
		http_response_code(500);
		die();
	}
?>
