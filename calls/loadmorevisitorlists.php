<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';
	login_check_r();

	if(isset($_GET['offset'],$_GET['limit'])){
		$offset = intval($_GET['offset']);
		$limit = intval($_GET['limit']);

		$hardupperlimit = 50;
		$limit = $limit > $hardupperlimit ? $hardupperlimit : $limit;

		$visitorlists = getvisitorLists($limit,$offset);
		header('Content-Type: application/json');
		echo(
			json_encode($visitorlists)
		);
	}
?>
