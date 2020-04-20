<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	function toSysLog($msg){
		toSysLogP($msg,LOG_INFO);
	}
	
	function toSysLogP($msg,$priority){
		openlog("easyvisit", LOG_PID | LOG_PERROR, LOG_USER);
		$access = date("Y/m/d H:i:s");
		$user = isset($_SESSION['username'])?$_SESSION['username']:"anonymous";
		syslog($priority,$access."-".$user."@".$_SERVER['REMOTE_ADDR']."-".$msg);
		closelog();
	}
?>