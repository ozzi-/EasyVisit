<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	include_once('config.php');

	function sec_session_start(){
		header('X-XSS-Protection: 1; mode=block');
		header('Strict-Transport-Security: max-age=2592000');
		header('X-Frame-Options: DENY');
		header('X-Content-Type-Options: nosniff');
		ini_set('session.cookie_httponly', 1 );
		
		if(COOKIE_SECURE_FLAG && ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443 )){
			ini_set('session.cookie_secure', 1 );
		}
		ini_set('session.use_only_cookies', 1);
		ini_set('session.gc_maxlifetime', 60*60*COOKIE_LIFETIME_HOURS); 
		
		// Forces sessions to only use cookies.
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
			die("Could not initiate a safe session (ini_set)");
			exit();
		}

		session_start();
		
		if(!isset($_SESSION['CSRF'])){
			$_SESSION['CSRF']= base64_encode( openssl_random_pseudo_bytes(32));
			$_SESSION['CSRF'] = str_replace("+", "p", $_SESSION['CSRF']);
			$_SESSION['CSRF'] = str_replace("/", "s", $_SESSION['CSRF']);
			$_SESSION['CSRF'] = str_replace("=", "e", $_SESSION['CSRF']);
		}
	}
		
	function login_check() {
		$chk = isset($_SESSION['username']);
		return ($chk);
	}
	
	function admin_check() {
		$chk = isset($_SESSION['admin'])&&$_SESSION['admin'];
		return ($chk);
	}
	

?>