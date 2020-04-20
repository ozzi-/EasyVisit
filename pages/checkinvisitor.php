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

	if( ((ENABLE_VISITOR_IDENTIFIER && isset($_POST['identifier'])) || !ENABLE_VISITOR_IDENTIFIER) && isset($_POST["id"]) && isset($_POST['name'])&&isset($_POST['surname'])&&isset($_POST['company'])&&isset($_POST['contactperson'])&&isset($_POST['signature'])&&isset($_POST['start'])&&isset($_POST['date'])){
		$name			=$_POST['name'];
		$surname		=$_POST['surname'];
		$company		=$_POST['company'];
		$idshown		=isset($_POST['idshown']);
		$contactperson    	=$_POST['contactperson'];
		$signature		=$_POST['signature'];
		$start			=$_POST['start'];
		$date			=$_POST['date'];
                $id			=intval($_POST["id"]);
		if(ENABLE_VISITOR_IDENTIFIER){
                	$identifier = $_POST['identifier'];
                        if($identifier=="none"){
				$identifier=null;
			}
		}else{
			$identifier = null;
		}
		if(!deleteCheckinVisitor($id)){
			header('Location: index.php?msg=already_checkedin');
			die();
		}
		$visitoraddedid	= checkinVisitor($name,$surname,$company,$idshown,$identifier,$contactperson,$signature,$start,$date);
		setInputStatus(false);

		header('Location: index.php?p=visitorlist&date='.$date.'&highlightvisitor='.$visitoraddedid);
		die();
	}else{
  		header('Location: index.php?p=visitorlist&msg=missing_params');
		die();
	}
?>