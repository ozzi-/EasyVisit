<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

  defined('APP_RAN') or die();
  require_once './api.php';
  login_check_r();
  admin_check_r();
  checkCSRF();

  if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['passwordr'])){

  $username	= $_POST['username'];
  $password	= $_POST['password'];
  $passwordr = $_POST['passwordr'];

  if(hash_equals($password,$passwordr)){
    $result=addLocalFrontDeskEmployee($username,$password);
    if(is_string($result)){
      if( strpos( $result, "exists" ) !== false ){
        header('Location: index.php?p=management&msg=username_exists&tab=tab_user_management');
        die();
      }
      if( strpos( $result, "usernameempty" ) !== false ){
        header('Location: index.php?p=management&msg=username_empty&tab=tab_user_management');
        die();
      }
      if( strpos( $result, "min_length" ) !== false ){
        header('Location: index.php?p=management&msg=password_min_length&tab=tab_user_management');
        die();
      }
      if( strpos( $result, "min_numbers" ) !== false ){
        header('Location: index.php?p=management&msg=password_min_numbers&tab=tab_user_management');
        die();
      }
      if( strpos( $result, "min_capital" ) !== false ){
        header('Location: index.php?p=management&msg=password_min_capitals&tab=tab_user_management');
        die();
      }
      if( strpos( $result, "min_special" ) !== false ){
        header('Location: index.php?p=management&msg=password_min_special_chars&tab=tab_user_management');
        die();
      }
      header('Location: index.php?msg=unknown_problem&tab=tab_user_management');
      die();
    }
    $id=intval($result);
    header('Location: index.php?p=management&highlightemployee='.$id.'&tab=tab_user_management&highlightemployee='.$id);
    die();
  }else{
    header('Location: index.php?p=management&msg=password_mismatch&tab=tab_user_management');
    die();
  }
}else{
  header('Location: index.php?p=management&msg=missing_params&tab=tab_user_management');
  die();
}
?>
