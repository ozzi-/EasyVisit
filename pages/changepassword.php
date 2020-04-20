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

	if(isset($_POST['password'])&&isset($_POST['passwordr']) && ((admin_check() && isset($_POST['uid'])) || isset($_POST['passwordc']))){
		if(hash_equals($_POST['password'],$_POST['passwordr'])){
			if(admin_check()){
				$uid=$_POST['uid'];
			}else{
				$uid=$_SESSION['id'];
				if(!checkCurrentPassword($_POST['passwordc'])){
					header('Location: index.php?msg=password_current_wrong');
					die();
				}
			}
			$result = changePassword($_POST['password'],$uid);
			if(is_string($result)){
					if( strpos( $result, "exists" ) !== false ){
						header('Location: index.php?msg=username_exists');
						die();
					}
					if( strpos( $result, "usernameempty" ) !== false ){
						header('Location: index.php?msg=username_empty');
						die();
					}
					if( strpos( $result, "min_length" ) !== false ){
						header('Location: index.php?msg=password_min_length');
						die();
					}
					if( strpos( $result, "min_numbers" ) !== false ){
						header('Location: index.php?msg=password_min_numbers');
						die();
					}
					if( strpos( $result, "min_capital" ) !== false ){
						header('Location: index.php?msg=password_min_capitals');
						die();
					}
					if( strpos( $result, "min_special" ) !== false ){
						header('Location: index.php?msg=password_min_special_chars');
						die();
					}
					header('Location: index.php?msg=unknown_problem');
					die();
			}
			if($_SESSION['id']==$uid){
				logout();
				header('Location: index.php?p=loginform&msg=password_changed&type=success');
			}else{
				header('Location: index.php?p=management&msg=password_changed_for_other&type=success');
			}
			die();
		}else{
			header('Location: index.php?msg=password_mismatch');
			die();
		}
	}else{
		header('Location: index.php?msg=missing_params');
		die();
	}
?>

