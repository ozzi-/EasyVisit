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

    	if(isset($_POST['name'])&&isset($_POST['secret'])){
    		$name	= $_POST['name'];
    		$secret	= $_POST['secret'];

    		$result=registerDevice($name,$secret);
    		if($result==="nameexists"){
    			header('Location: index.php?msg=device_exists');
    			die();
    		}
    		$id=intval($result);
    		header('Location: index.php?p=management&tab=tab_devices&highlightdevice='.$id);
    		die();
    	}else{
    		header('Location: index.php?p=management&tab=tab_devices&msg=missing_params');
    		die();
    	}
    ?>

