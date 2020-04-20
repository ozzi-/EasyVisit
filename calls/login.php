<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';
	checkCSRF();

	if(isset($_POST['username'])&&isset($_POST['password'])){
		$username=$_POST['username'];
		$password=$_POST['password'];

		if(login($username,$password)){
			if($password==="changeme"){
				header('Location: ../index.php?msg=default_pw');
				die();
			}else{
				header('Location: ../index.php');
				die();
			}
		}else{
			header('Location: ../index.php?p=loginform&msg=failed_login');
			die();
		}
	}else{
		header('Location: ../index.php?p=loginform&msg=missing_params');
		die();
	}
?>



