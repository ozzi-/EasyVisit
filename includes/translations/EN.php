<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
	$translation['app_name'] = "EasyVisit";

	$translation['config_title_app'] = "Application";
	$translation['config_app_set_checkout'] = 'Enable choosable Check-Out time';
	$translation['config_app_enable_visitor_identifier'] = 'Activate visitor identifier';
	$translation['config_app_enable_visitor_id_shown'] = 'Activate the field "Identity document checked"';
	$translation['config_app_enable_visitor_badge'] = 'Activate Visitor Badge Creation';
	$translation['config_app_badge_w'] = 'Visitor Badge Width in pixel';
	$translation['config_app_badge_h'] = 'Visitor Badge Height in ixel';
	$translation['config_app_notify_email'] =  'Notify contact person on Check-In via E-Mail (only if contact person has E-Mail address set)';
	$translation['config_app_notify_mobile'] = 'Notify contact person on Check-In via SMS (only if contact person has SMS address set)';
	$translation['config_app_notify_email_url'] = 'HTTP Post URL to be called on Check-Is (Email)';
	$translation['config_app_notify_mobile_url'] = 'HTTP Post URL to be called on Check-Is (SMS)';
	$translation['config_app_notify_secret'] = 'Shared Secret for authentication on the Check-In notifications';
	$translation['config_app_notify_insecure'] = 'Don\'t check HTTPS certificate for notifications HTTP Post (should only be active in testing environments)';
	$translation['config_title_error_handling'] = "Error handling";
	$translation['config_show_db_errors'] = "Display detailed SQL errors (should only be active in testing environments)";
	$translation['config_show_php_errors'] = "Display detailed PHP errors (should only be active in testing environments)";
		
	$translation['config_upload'] = "Upload";
	$translation['config_timezone'] = "Timezone";
	$translation['config_update_url'] = "Update Server URL (coming soon)";
	$translation['config_update_key'] = "License Key for Updates";
	$translation['config_language'] = "Language File (includes/translations/)";
		
	$translation['config_title_login'] = "Login";
	$translation['login_mode'] = "Choose User Login Mode";
	$translation['login_mode_local'] = "Local - Username & Password stored in the database";
	$translation['login_mode_ldap'] = "LDAP - Username & Password via your LDAP Server";
	$translation['login_mode_proxy'] = "Header - Setting a HTTP Header for use with a reverse proxy";
	$translation['login_mode_auto'] = "Autologin - Automatic login with generic user";

	$translation['motd_mode'] = "Information text to be shown during Check-In<ul><li>off - No info text</li><li>file - Info Text is loaded from a file</li><li>url - Info Text is loaded from an URL (message of the day)</li></ul>";
	$translation['motd_val'] = "Path to the info text resource";

	$translation['config_login_proxy_header'] = "HTTP Header Name";

	$translation['config_login_auto'] = "Username that will be used for autologin";
	$translation['config_login_auto_register'] = "Create a user automatically if username unknown (make sure only authorized users have access via reverse proxy)";

	$translation['config_login_ldap_hostname'] = "LDAP IP / hostname";
	$translation['config_login_ldap_port'] = "LDAP Port";
	$translation['config_login_ldap_secure'] = "Use Secure LDAP";
	$translation['config_login_ldap_bind_dn'] = "Bind DN (needs to contain %username%)";
	$translation['config_login_ldap_timeout'] = "LDAP Timeout (in seconds)";
	$translation['config_login_ldap_debug'] = "LDAP Debug Messages (only activate in test environments)";
	$translation['config_login_cookie_lifetime_hours'] = "Session Lifetime (in hours)";

	$translation['clear_signature'] = "Clear Signature";

	$translation['config_title_branding'] = "Design";
	$translation['config_branding_color'] = "Main";
	$translation['config_warning_color'] = "Warnings";
	$translation['config_mark_item_color'] = "Selections";
	$translation['config_alt_row_color'] = "Uneven Rows";

	$translation['config_title_language_region'] = "Language & Region";
		
	$translation['config_title_security'] = "Security";
	$translation['config_http_auto_redir'] = "Redirect HTTP requests automatically to HTTPS - recommended if HTTPS is used";
	$translation['config_cookie_secure'] = "Set the 'secure' Flag for the cookie - recommended if HTTPS is used";
		
	$translation['config_title_logging'] = "Syslog";
	$translation['config_log_level'] = "Log Level for the syslog service - LOG_PERROR for Windows Systems, LOG_ERR for GNU Linux , LOG_ERROR for macOS";
		
	$translation['error'] = 'Error';
	$translation['missing_params']	= 'Invalid Request - Missing Parameter';
	$translation['invalid_device_credentials'] = 'Invalid Device Credentials';
	$translation['device_not_active'] = 'Device not active';
	$translation['device_pass_settings'] = 'Please enter device password';
	$translation['device_no_devices'] = 'No devices have been registered';
	
	$translation['user_inactive'] = 'User inactive';
	$translation['username_unknown'] = 'User unknown';
	$translation['username_exists'] = 'Username already taken';
	$translation['password_min_length'] = 'Password too short';
	$translation['password_min_numbers'] = 'Password does not contain enough numbers';
	$translation['password_min_capitals'] = 'Password does not contain enough capital letters';
	$translation['password_min_special_chars'] = 'Password does not contain enough special characters';
	$translation['admin_required'] = 'Request blocked due to missing priviliges';
		
	$translation['unknown_problem'] = 'A unknown problem has occured';
	$translation['password_mismatch'] = 'Passwords do not match';

	$translation['password_policy'] = 'Password Policy';
	$translation['password_policy_min_length'] = 'Minimum length';
	$translation['password_policy_min_capital'] = 'Minimal count of capital letters';
	$translation['password_policy_min_number'] = 'Minimal count of numbers';
	$translation['password_policy_min_special'] = 'Minimal count of special characters';

	$translation['username_empty'] = 'Username cannot be empty';

	$translation['csrf_mismatch'] = 'CSRF Token invalid - please try again';
		
	$translation['welcome'] = 'Welcome to';
		
	$translation['loggedout']		= 'Logout successful';
		
	$translation['fde'] 			= 'Receptionist';
	$translation['fde_add'] 		= 'Add receptionist';
	$translation['fde_add_submit'] 	= 'Add';
	$translation['contactperson_add']			= 'Add contact person';
	$translation['contactperson_name']			= 'Name';
	$translation['contactperson_add_submit']	= 'Add';
	$translation['contactperson_remove']		= 'Remove';
	$translation['contactperson_not_in_list'] 	= 'Choose contact person';
		
	$translation['dashboard_visitors_this_month']				= 'Visitors this month';
	$translation['dashboard_visitors_today']					= 'Visitors today';
	$translation['dashboard_visitors_currently_checkedin']		= 'Today\'s checked-in visitors';
	$translation['dashboard_visitors_currently_checkedout']		= 'Today\'s checked-out visitors';
	$translation['dashboard_currently_loggedin']			= 'Currently checked-in visitors';
	$translation['dashboard_currently_nobody_loggedin']		= 'Currently no visitors are checked-in';
		
	$translation['menu_visitor_list'] 	= 'Visitors';
	$translation['menu_configuration']	= "Settings";
	$translation['menu_checkin'] 		= 'Check-In';
	$translation['menu_checkout'] 		= 'Check-Out';
	$translation['late_checkout'] 		= 'Belated Check-out';
	$translation['menu_management'] 	= 'Management';
	$translation['menu_logout'] 		= 'Logout';
	$translation['menu_login'] 			= 'Login';
	$translation['check_secret'] 		= 'Check Credentials';
	$translation['unregister'] 			= 'Delete Credentials';


	$translation['identifier_name']			= 'ID';
	$translation['identifier_description']	= 'Description';
	$translation['identifier_title']		= 'Identifier';
	$translation['identifier_add'] 			= 'Add identifier';
	$translation['identifier_history']		= 'Visitor history';

	$translation['visitor_name'] 			= 'Name';
	$translation['visitor_surname'] 		= 'Surname';
	$translation['visitor_fullname'] 		= 'Name';
	$translation['visitor_company'] 		= 'Company';
	$translation['visitor_contactperson'] 	= 'Contact Person';
	$translation['visitor_identifier'] 		= 'Identifier';
	$translation['visitor_id_checked'] 		= 'ID checked';
	$translation['visitor_ok'] 				= 'Check-in visitor';
	$translation['visitor_start'] 			= 'Check-In';
	$translation['visitor_end']				= 'Check-Out';
	$translation['visitor_checkout']		= 'Check-Out';
	$translation['visitor_no_visitors']		= 'No checked-in Visitors';
	$translation['visitor_no_visitors_on']	= 'No visitors on';
	$translation['visitor_no_visitors_yet']	= 'No visitors have been here yet';
	$translation['visitor_signature']		= 'Signature';
		
	$translation['visitorlist_date']		= 'Enter date';
	$translation['visitorlist_show']		= 'Show visitor history';
	$translation['visitorlist_search']		= 'Search visitor history';
	$translation['search_no_result']		= 'No search results found. ';
	$translation['search']					= 'Search';
	$translation['today'] 					= 'Today';
	$translation['list_today']				= 'Today\'s visitor list';
		
	$translation['create_badge']			= "Badge";
	$translation['visitor']					= "Visitor";
	$translation['print_badge']				= "Print";

	$translation['checkin_delete']				= "Delete check-in";
	$translation['checkin_delete_sure']			= "Are you sure you wish to delete this check-in?";
		
	$translation['reoccuring_visitor']		= "Are you here often and possess a visitor code?";
	$translation['reoccuring_visitor_btn']	= "Fast Check-In";
	$translation['reoccuring_visitor_placeholder']	= "Visitor code";
	$translation['reoccuring_add']			= "Add fast check-in visitor";
		
	$translation['reoccuring_code'] = "Fast Check-In Code";
	$translation['reoccuring_name'] = "Name";
	$translation['reoccuring_surname'] = "Surname";
	$translation['reoccuring_company'] = "Company";
	$translation['reoccuring_contactperson'] = "Contact person";
	$translation['reoccuring_add_submit'] 	= "Add";
	$translation['reoccuring_remove']		= 'Remove';
		
	$translation['contactperson_title']		= 'Contact person';
	$translation['user_management']			= 'User Management';
	$translation['reoccuring_title']		= 'Fast Check-In';
	$translation['reoccuring_code']			= 'Code';
	$translation['reoccuring_name']			= 'Name';
		
	$translation['device']					= 'Devices';
	$translation['device_register']			= 'Register device';
	$translation['device_this_register']	= 'Register device';
	$translation['device_delete']			= 'Delete device';
	$translation['device_activate']			= 'Activate device';
	$translation['device_active']			= 'Device active';
	$translation['device_deactivate']		= 'Deactivate device';
	$translation['device_name']				= 'Device name';
	$translation['device_secret']			= 'Device password';
	$translation['device_state']			= 'Status';
	$translation['device_no_"devices']		= 'No devices have been registered yet';
	$translation['device_invalid_credentials']	= 'Invalid credentials';
	$translation['device_valid_credentials']	= 'Valid credentials';
	$translation['device_registered']		= 'Device has been registered successfully';
	$translation['device_exists']			= 'Device name already taken';
		
		
	$translation['visitor_waiting_for_input']	= 'Tabked input started - waiting for check-in.';
		
	$translation['settings']		= 'Settings';
	$translation['active'] 			= 'Active';
	$translation['activate'] 		= 'Activate';
	$translation['deactivate'] 		= 'Deactivate';
	$translation['inactive'] 		= 'Inactive';
	$translation['remove'] 			= 'Remove';
	$translation['cancel'] 			= 'Cancel';
	$translation['back'] 			= 'Back';
	$translation['invalid_view'] 	= 'Page not found';
			
	$translation['clear_signature']	= 'Clear signature';
		
	$translation['username'] 		= 'Username';
	$translation['password'] 		= 'Password';
	$translation['email']	 		= 'E-Mail';
	$translation['mobile']	 		= 'Mobile number';
	$translation['login'] 			= 'Login';
	$translation['submit'] 			= 'Submit';
	$translation['date']			= 'Date';
	$translation['export']			= 'Export';
	$translation['created_at'] 		= 'Created ';
	$translation['state']	 		= 'Status';
	$translation['admin'] 			= 'Admin';
	$translation['admin_demote']	= 'Remove admin privileges';
	$translation['admin_promote']	= 'Grand admin privileges';
		
	$translation['visitor_count']	= 'Visitor Count';
		
		
	$translation['password_change_for_user']		= 'Change password for';
	$translation['mobile_change_for_user']		= 'Change mobile number for';
	$translation['email_change_for_user']		= 'Change E-Mail address for';
	$translation['password_repeat']		= 'Repeat password';
	$translation['password_current_wrong']		= 'Current password wrong';
	$translation['password_change']		= 'Change password';
	$translation['mobile_change']		= 'Change mobile number';
	$translation['email_change']		= 'Change E-Mail address';
	$translation['password_current']	= 'Current Password';
	$translation['default_pw']              = 'You are using the default password. Please change <a href="index.php?p=management&tab=tab_change_password">your password</a> immediately.';
	$translation['mobile_changed']		= 'Mobile number changed successfully.';
	$translation['password_changed']		= 'Password successfully changed. For security reasons, you have to login again.';
	$translation['password_changed_for_other']	= 'Password successfully changed.';
	$translation['email_changed']		= 'E-Mail address successfully changed.';
	$translation['password_change_ldap']	= 'Only local users can change their password';
	$translation['failed_login'] 		= 'Wrong username or password';
	$translation['continue'] 			= 'Continue';
	$translation['already_checkedin']   = 'Visitor has been checked-in in the meantime';
	$translation['already_checkedout']  = 'Visitor has been checked-out in the meantime';
	$translation['next']	 			= '>';
	$translation['back']	 			= '<';
	$translation['no_employee_yet']			= 'No users have been created yet';
	$translation['length_menu'] = "Show _MENU_ Entries";
	$translation['no_entries'] = "No entries found";
	$translation['info_page'] = "Page _PAGE_ /  _PAGES_";
?>
