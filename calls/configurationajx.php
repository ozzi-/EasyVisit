<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../api.php';

	$imgMode = isset($_POST['img']);

	function handleReturn($code,$imgMode){
		if($imgMode){
			header("Location: index.php?p=configuration&code=".$code);
			die();
		}else{
			http_response_code($code);
			die();
		}
	}

	if(!login_check()||! admin_check()){
		toSysLogP("Insufficient priviliges when trying to change setting",LOG_WARNING);
		handleReturn(401,$imgMode);
	}

	if(!isset($_POST['CSRF'])||!hash_equals($_POST['CSRF'],$_SESSION['CSRF'])){
		toSysLogP("Invalid / missing CSRF when trying to change setting",LOG_WARNING);
		handleReturn(403,$imgMode);
	}

	if($imgMode){
		$img = $_POST['img'];
		if($img != "logo.png" && $img != "logo_navbar.png" && $img != "apple-touch-icon.png"){
			handleReturn(416,$imgMode);
		}
		$target_file = "img/".$img;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if(isset($_POST["submit"])) {
			if(getimagesize($_FILES["fileToUpload"]["tmp_name"])===false){
				handleReturn(415,$imgMode);
			}
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			handleReturn(415,$imgMode);
		}
		$res = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
		if(!$res){
			echo($_FILES["fileToUpload"]["error"]);
			handleReturn(500,$imgMode);
		}
	}else{
		if(!isset($_POST['id']) || !isset($_POST['val']) || !isset($_POST['parathesis'])){
			handleReturn(422,$imgMode);
		}
		changeConfiguration($_POST['id'],$_POST['val'],$_POST['parathesis']);
	}

	handleReturn(200,$imgMode);

	function changeConfiguration($id,$value,$parathesis){
		$pathOfConfigFile = "../includes/config.php";
		$value = str_replace('"','\"',$value);
		if($parathesis=="true"){
			$value='"'.$value.'"';
		}
		restore_error_handler ();
		toSysLog("Changing setting ".$id." to ".$value);
		ini_set('opcache.enable', 0);
		$cfg=file_get_contents($pathOfConfigFile);
		$cfgarr = explode("\n", $cfg);
		$count = 0;
		foreach ($cfgarr as &$cfgl) {
		  $before=$cfgl;
		  $pattern = "/^\s*define\s*\(\s*\"".$id."\".*/";
		  $replacement= '        define ("'.$id.'",'.$value.');';
		  $cfgl=preg_replace($pattern, $replacement, $cfgl,1,$count);
		  if($count==1){
			$changed=true;
		  }
		}
		if(!$changed){
			handleReturn(404,false);
		}
		$cfg = implode("\n",$cfgarr);
		$ret=file_put_contents($pathOfConfigFile, $cfg, LOCK_EX);
		echo($ret." ret    -  changed ".$changed);
		touch($pathOfConfigFile, time());
		clearstatcache();
		if(!$ret){
			handleReturn(500,false);
		}
	}

?>

