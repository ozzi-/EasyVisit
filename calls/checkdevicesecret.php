<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';

	if(isset($_POST['device_name'])&&isset($_POST['secret'])){
		$result=checkSecret($_POST['device_name'],$_POST['secret'],false);
		echo(json_encode(array('secret' => ($result?"OK":"NOK"))));
	}else{
		echo("Missing params");
	}
?>
