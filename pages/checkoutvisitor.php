<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	require_once './api.php';
	login_check_r();
	checkCSRF();

	if(isset($_POST['visitor_id'])){
		$visitorID=intval($_POST['visitor_id']);
      		if(vistorCheckedOut($visitorID)){
      			header('Location: index.php?msg=already_checkedout');
			die();
      		}
		$today=visitorCheckinToday($visitorID);

		$time=date('H:i', time());
		if(ALLOW_TO_SET_CHECKOUT_TIME && isset($_POST['checkouttime'])){
			if(preg_match("/^(2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $_POST['checkouttime'])){
				$time=$_POST['checkouttime'];
			}
		}
		if(!$today){
			$time=translation_get('late_checkout');
		}

		$listID=checkoutVisitor($visitorID, $time);
		if($today){
			header('Location: index.php?p=checkoutvisitorform&highlightvisitor='.$visitorID);
		}else{
			header('Location: index.php?p=visitorlist&id='.$listID."&highlightvisitor=".$visitorID);
		}
		die();
	}else{
		header('Location: index.php?msg=missing_params');
		die();
	}
?>
