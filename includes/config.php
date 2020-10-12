<?php

	define ("ALLOW_TO_SET_CHECKOUT_TIME",true);
	define ("ENABLE_VISITOR_ID_SHOWN",true);
	define ("ENABLE_VISITOR_BADGE",true);
	define ("ENABLE_VISITOR_IDENTIFIER",true);
	define ("BADGE_W",600);
	define ("BADGE_H",400);
	define ("NOTIFY_CHECKIN_EMAIL",false);
	define ("NOTIFY_CHECKIN_MOBILE",false);
	define ("NOTIFY_CHECKIN_EMAIL_ENDPOINT","https://domain.changeme.ch/email.php");
	define ("NOTIFY_CHECKIN_MOBILE_ENDPOINT","https://domain.changeme.ch/sms.php");
	define ("NOTIFY_CHECKIN_ENDPOINT_SECRET","change_me");
	define ("NOTIFY_CHECKIN_ENDPOINT_INSECURE",false);

	define ("COMPANY_NAME","Company Name");

	define ("APP_NAME","EasyVisit");
	define ("BACKUP_PATH","/changeme/demo/");

	define ("UPDATE_URL","http://8.8.8.8/changme");
	define ("UPDATE_KEY","222");
	define ("UPDATE_TLS_VERIFY","0");

	define ("MOTD_MODE","off");
	define ("MOTD_VAL","/var/www/html/easyvisit/motd");

	// ==================
	// ==== SECURITY ====
	// ==================
	define ("DB_SHOW_ERRORS",true);
	define ("PHP_SHOW_ERRORS",true);
	define ("HTTP_AUTO_REDIRECT",true);
	define ("COOKIE_SECURE_FLAG",true);
	define ("LOG_ERROR_LEVEL",LOG_ERR);
	
	define("SIGNATURE_WIDTH",620);
	define("SIGNATURE_HEIGHT",370);
	
	// ================
	// ==== REGION ====
	// ================
	define ("TIMEZONE","Europe/Zurich");
	date_default_timezone_set(TIMEZONE);
	define ("LANGUAGE","DE.php");
	
	// ================
	// ==== VISUAL ====
	// ================
	define ("BRANDING_COLOR","#539ba2");
	define ("BRANDING_WARNING_COLOR","#E54D1D");
	define ("MARK_ITEM_COLOR","#87d1db");
	
	define("VISITORLISTS_LIMIT",20);
	
	// ===============
	// ==== LOGIN ====
	// ===============
	
	define ("LOGIN_MODE","local");
	define ("LOGIN_PROXY_HEADER","X-USERNAME");
	define ("LOGIN_AUTO_NAME","autologin");
	define ("LOGIN_AUTO_REGISTER",false);
	
	define ("LOGIN_LDAP_HOSTNAME","127.0.0.1");
	define ("LOGIN_LDAP_PORT",389);
	define ("LOGIN_LDAP_SECURE",true);
	define ("LOGIN_LDAP_BIND_DN","uid=%username%,cn=users,dc=server,dc=ch");
	define ("LOGIN_LDAP_TIMEOUT",2);
	define ("LOGIN_LDAP_DEBUG",false);

	define("COOKIE_LIFETIME_HOURS",8);
		
	// ============
	// ==== DB ====
	// ============

	define ("DB_HOST","127.0.0.1");
	define ("DB_USER","easyvisit");
	define ("DB_PASS","changeme");
	define ("DB_NAME","easyvisit");

	// ===================
	// ==== PW POLICY ====
	// ===================
	define ("PW_POLICY_MIN_LENGTH",7);
	define ("PW_POLICY_MIN_NUMBERS",2);
	define ("PW_POLICY_MIN_CAPITAL",1);
	define ("PW_POLICY_MIN_SPECIAL",1);

	
?>