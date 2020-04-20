<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
	$translation['app_name'] = "EasyVisit";

	$translation['config_title_app'] = "Applikation";
	$translation['config_app_set_checkout'] = 'Frei wählbare Check-Out Zeit aktivieren';
	$translation['config_app_enable_visitor_identifier'] = 'Besucher Identifier Zuweisung aktivieren';
	$translation['config_app_enable_visitor_id_shown'] = 'Feld "Identitätsdokument überprüft" aktivieren';
	$translation['config_app_enable_visitor_badge'] = 'Besucher Badge Erstellung aktivieren';
	$translation['config_app_badge_w'] = 'Besucher Badge Breite in Pixel';
	$translation['config_app_badge_h'] = 'Besucher Badge Höhe in Pixel';
	$translation['config_app_notify_email'] =  'Kontaktperson bei Check-In per E-Mail benachrichtigen (falls hinterlegt)';
	$translation['config_app_notify_mobile'] = 'Kontaktperson bei Check-In per SMS benachrichtigen (falls hinterlegt)';
	$translation['config_app_notify_email_url'] = 'URL auf welche ein HTTP Post aufgerufen werden soll bei Check-In (Email)';
	$translation['config_app_notify_mobile_url'] = 'URL auf welche ein HTTP Post aufgerufen werden soll bei Check-In (Mobile)';
	$translation['config_app_notify_secret'] = 'Shared Secret zur Authentisierung der Check-In Benachrichtigung';
	$translation['config_app_notify_insecure'] = 'HTTPS Zertifikat nicht prüfen bei HTTP Post (sollte nur in Testumgebungen aktiv sein)';
	$translation['config_title_error_handling'] = "Fehlerbehandlung";
	$translation['config_show_db_errors'] = "Anzeige detailierter SQL Fehlermeldungen (sollte nur in Testumgebungen aktiv sein)";
	$translation['config_show_php_errors'] = "Anzeige detailierter PHP Fehlermeldungen (sollte nur in Testumgebungen aktiv sein)";
		
	$translation['config_upload'] = "Upload";
	$translation['config_timezone'] = "Zeitzone";
	$translation['config_update_url'] = "URL des Update Server";
	$translation['config_update_key'] = "Lizenz Schlüssel für Updates";
	$translation['config_language'] = "Sprach Datei (zu finden unter includes/translations/)";
		
	$translation['config_title_login'] = "Login";
	$translation['login_mode'] = "Wählen Sie den Modus, wie sich Benutzer anmelden";
	$translation['login_mode_local'] = "Lokal - Mittels Usernamen & Passwort an der Datenbank";
	$translation['login_mode_ldap'] = "LDAP - Mittels Usernamen & Passwort an einem LDAP Server";
	$translation['login_mode_proxy'] = "Header - Mittels HTTP Header durch einen Reverse Proxy";
	$translation['login_mode_auto'] = "Autologin - Automatische Anmeldung mit unpersönlichem Benutzer";

	$translation['motd_mode'] = "Info Text beim Check-In<ul><li>off - Kein Info Text</li><li>file - Info Text aus einem File einbinden</li><li>url - Info Text von einer Website laden</li></ul>";
	$translation['motd_val'] = "Pfad zur Info Text Ressource";

	$translation['config_login_proxy_header'] = "HTTP Header Namen";

	$translation['config_login_auto'] = "Username welcher für den Autologin verwendet wird";
	$translation['config_login_auto_register'] = "Automatisch einen User erstellen, falls Username unbekannt";

	$translation['config_login_ldap_hostname'] = "LDAP IP / Hostname";
	$translation['config_login_ldap_port'] = "LDAP Port";
	$translation['config_login_ldap_secure'] = "Secure LDAP verwenden";
	$translation['config_login_ldap_bind_dn'] = "Bind DN (muss %username% enthalten)";
	$translation['config_login_ldap_timeout'] = "LDAP Timeout (in Sekunden)";
	$translation['config_login_ldap_debug'] = "LDAP Debug Meldungen (sollte nur in Testumgebungen aktiv sein)";
	$translation['config_login_cookie_lifetime_hours'] = "Session Lebensdauer (in Stunden)";

	$translation['clear_signature'] = "Unterschrift löschen";

	$translation['config_title_branding'] = "Design";
	$translation['config_branding_color'] = "Haupt";
	$translation['config_warning_color'] = "Warnung";
	$translation['config_mark_item_color'] = "Selektion";
	$translation['config_alt_row_color'] = "Ungrade Zeilen";

	$translation['config_title_language_region'] = "Sprache & Region";
		
	$translation['config_title_security'] = "Sicherheit";
	$translation['config_http_auto_redir'] = "HTTP Anfragen automatisch auf HTTPS umleiten - empfohlen falls HTTPS verwendet wird";
	$translation['config_cookie_secure'] = "Cookie 'secure' Flag setzen - empfohlen falls HTTPS verwendet wird";
		
	$translation['config_title_logging'] = "Syslog";
	$translation['config_log_level'] = "Log Level für den Syslog Dienst - LOG_PERROR für Windows Systeme, LOG_ERR für GNU Linux , LOG_ERROR für macOS";
		
	$translation['error'] = 'Fehler';
	$translation['missing_params']	= 'Ungültige Anfrage - Fehlender Parameter';
	$translation['invalid_device_credentials'] = 'Ungültige Geräte Anmeldedaten';
	$translation['device_not_active'] = 'Gerät nicht aktiv';
	$translation['device_pass_settings'] = 'Bitte Gerätepasswort eingeben';
	$translation['device_no_devices'] = 'Es wurden noch keine Geräte erfasst';
	
	$translation['user_inactive'] = 'Benutzer inaktiv';
	$translation['username_unknown'] = 'Unbekannter Benutzer';
	$translation['username_exists'] = 'Benutzername existiert bereits';
	$translation['password_min_length'] = 'Passwort zu kurz';
	$translation['password_min_numbers'] = 'Passwort enthält nicht genügend Zahlen';
	$translation['password_min_capitals'] = 'Passwort enthält nicht genügend Grossbuchstaben.';
	$translation['password_min_special_chars'] = 'Passwort enthält nicht genügend Sonderzeichen';
	$translation['admin_required'] = 'Vorgang wegen ungenügenden Berechtigungen abgebrochen';
		
	$translation['unknown_problem'] = 'Ein unbekanntes Problem ist aufgetreten';
	$translation['password_mismatch'] = 'Passwörter stimmen nicht überein';

	$translation['password_policy'] = 'Kennwort Richtline';
	$translation['password_policy_min_length'] = 'Mindestlänge';
	$translation['password_policy_min_capital'] = 'Mindestanzahl von Grossbuchstaben';
	$translation['password_policy_min_number'] = 'Mindestanzahl von Zahlen';
	$translation['password_policy_min_special'] = 'Mindestanzahl von Sonderzeichen';

	$translation['username_empty'] = 'Benutzername darf nicht leer sein';

	$translation['csrf_mismatch'] = 'CSRF Token ungültig - bitte probieren Sie die fehlgeschlagene Aktion erneut';
		
	$translation['welcome'] = 'Willkommen bei';
		
	$translation['loggedout']		= 'Erfolgreich abgemeldet';
		
	$translation['fde'] 			= 'Empfangsperson';
	$translation['fde_add'] 		= 'Empfangsperson hinzufügen';
	$translation['fde_add_submit'] 	= 'Hinzufügen';
	$translation['contactperson_add']			= 'Kontaktperson hinzufügen';
	$translation['contactperson_name']			= 'Name';
	$translation['contactperson_add_submit']	= 'Hinzufügen';
	$translation['contactperson_remove']		= 'Entfernen';
	$translation['contactperson_not_in_list'] 	= 'Kontaktperson selbst wählen';
		
	$translation['dashboard_visitors_this_month']				= 'Besucher in diesem Monat';
	$translation['dashboard_visitors_today']					= 'Besucher heute';
	$translation['dashboard_visitors_currently_checkedin']		= 'Eingecheckte Besucher heute';
	$translation['dashboard_visitors_currently_checkedout']		= 'Ausgecheckte Besucher heute';
	$translation['dashboard_currently_loggedin']			= 'Zur Zeit eingecheckte Besucher';
	$translation['dashboard_currently_nobody_loggedin']		= 'Zur Zeit keine eingecheckte Besucher';
		
	$translation['menu_visitor_list'] 	= 'Besucher';
	$translation['menu_configuration']	= "Einstellungen";
	$translation['menu_checkin'] 		= 'Check-in';
	$translation['menu_checkout'] 		= 'Check-out';
	$translation['late_checkout'] 		= 'Nachträglicher Check-out';
	$translation['menu_management'] 	= 'Management';
	$translation['menu_logout'] 		= 'Abmelden';
	$translation['menu_login'] 			= 'Anmelden';
	$translation['check_secret'] 		= 'Anmeldedaten überprüfen';
	$translation['unregister'] 			= 'Anmeldedaten löschen';


	$translation['identifier_name']			= 'ID';
	$translation['identifier_description']		= 'Beschreibung';
	$translation['identifier_title']		= 'Identifier';
	$translation['identifier_add'] 			= 'Identifier hinzufügen';
	$translation['identifier_history']		= 'Besucher Verlauf';

	$translation['visitor_name'] 			= 'Vorname';
	$translation['visitor_surname'] 		= 'Nachname';
	$translation['visitor_fullname'] 		= 'Name';
	$translation['visitor_company'] 		= 'Firma';
	$translation['visitor_contactperson'] 	= 'Kontaktperson';
	$translation['visitor_identifier'] 	= 'Identifier';
	$translation['visitor_id_checked'] 		= 'ID überprüft';
	$translation['visitor_ok'] 				= 'Besucher einchecken';
	$translation['visitor_start'] 			= 'Check-In';
	$translation['visitor_end']				= 'Check-Out';
	$translation['visitor_checkout']		= 'Check-Out';
	$translation['visitor_no_visitors']		= 'Keine eingecheckten Besucher';
	$translation['visitor_no_visitors_on']	= 'Keine Besucher erfasst am';
	$translation['visitor_no_visitors_yet']	= 'Noch keine Besucher erfasst';
	$translation['visitor_signature']		= 'Unterschrift';
		
	$translation['visitorlist_date']		= 'Datum eingeben';
	$translation['visitorlist_show']		= 'Besucherliste anzeigen';
	$translation['visitorlist_search']		= 'In Besucherlisten suchen';
	$translation['search_no_result']= 'Es wurden keine mit deiner Suchanfrage übereinstimmenden Besucher gefunden. ';
	$translation['search']					= 'Suchen';
	$translation['today'] 					= 'Heute';
	$translation['list_today']				= 'Heutige Besucherliste';
		
	$translation['create_badge']			= "Badge";
	$translation['visitor']					= "Besucher";
	$translation['print_badge']				= "Ausdrucken";

	$translation['checkin_delete']				= "Check-In löschen";
	$translation['checkin_delete_sure']			= "Sind Sie sicher, dass Sie diesen Check-In löschen möchten?";
		
	$translation['reoccuring_visitor']		= "Sind Sie öfters hier und haben einen Besuchercode?";
	$translation['reoccuring_visitor_btn']	= "Fast Check-In";
	$translation['reoccuring_visitor_placeholder']	= "Besuchercode";
	$translation['reoccuring_add']			= "Fast Check-In Besucher hinzufügen";
		
		
	$translation['reoccuring_code'] = "Fast Check-In Code";
	$translation['reoccuring_name'] = "Vorname";
	$translation['reoccuring_surname'] = "Nachname";
	$translation['reoccuring_company'] = "Firma";
	$translation['reoccuring_contactperson'] = "Kontaktperson";
	$translation['reoccuring_add_submit'] 	= "Hinzufügen";
	$translation['reoccuring_remove']		= 'Entfernen';
		
	$translation['contactperson_title']		= 'Kontaktpersonen';
	$translation['user_management']			= 'Benutzerverwaltung';
	$translation['reoccuring_title']		= 'Fast Check-In';
	$translation['reoccuring_code']			= 'Code';
	$translation['reoccuring_name']			= 'Name';
		
	$translation['device']			= 'Geräte';
	$translation['device_register']			= 'Neues Gerät registrieren';
	$translation['device_this_register']	= 'Gerät registrieren';
	$translation['device_delete']			= 'Gerät löschen';
	$translation['device_activate']			= 'Gerät aktivieren';
	$translation['device_active']			= 'Gerät aktiv';
	$translation['device_deactivate']		= 'Gerät deakivieren';
	$translation['device_name']				= 'Geräte Namen';
	$translation['device_secret']			= 'Geräte Passwort';
	$translation['device_state']			= 'Status';
	$translation['device_no_"devices']		= 'Noch keine Geräte erfasst';
	$translation['device_invalid_credentials']	= 'Ungültige Anmeldedaten';
	$translation['device_valid_credentials']	= 'Gültige Anmeldedaten';
	$translation['device_registered']		= 'Gerät erfolgreich registriert';
	$translation['device_exists']			= 'Geräte Namen bereits vergeben.';
		
		
	$translation['visitor_waiting_for_input']	= 'Tablet Eingabe gestartet - warten auf Check-In.';
		
	$translation['settings']		= 'Einstellungen';
	$translation['active'] 			= 'Aktiv';
	$translation['activate'] 		= 'Aktiveren';
	$translation['deactivate'] 		= 'Deaktivieren';
	$translation['inactive'] 		= 'Inaktiv';
	$translation['remove'] 			= 'Entfernen';
	$translation['cancel'] 			= 'Abbrechen';
	$translation['back'] 			= 'Zurück';
	$translation['invalid_view'] 	= 'Seite nicht gefunden';
			
	$translation['clear_signature']	= 'Unterschrift löschen';
		
	$translation['username'] 		= 'Benutzername';
	$translation['password'] 		= 'Passwort';
	$translation['email']	 		= 'E-Mail';
	$translation['mobile']	 		= 'Mobile Nummer';
	$translation['login'] 			= 'Anmelden';
	$translation['submit'] 			= 'Absenden';
	$translation['date']			= 'Datum';
	$translation['export']			= 'Exportieren';
	$translation['created_at'] 		= 'Erstellt am';
	$translation['state']	 		= 'Status';
	$translation['admin'] 			= 'Admin';
	$translation['admin_demote']	= 'Adminrechte entziehen';
	$translation['admin_promote']	= 'Adminrechte geben';
		
	$translation['visitor_count']	= 'Anzahl Besucher';
		
		
	$translation['password_change_for_user']		= 'Passwort ändern für';
	$translation['mobile_change_for_user']		= 'Mobile Nummer ändern für';
	$translation['email_change_for_user']		= 'E-Mail ändern für';
	$translation['password_repeat']		= 'Passwort wiederholen';
	$translation['password_current_wrong']		= 'Aktuelles Passwort falsch';
	$translation['password_change']		= 'Passwort ändern';
	$translation['mobile_change']		= 'Mobile Nummer ändern';
	$translation['email_change']		= 'E-Mail Adresse ändern';
	$translation['password_current']	= 'Aktuelles Passwort';
	$translation['default_pw']              = 'Sie verwenden dass Standard Passwort. Bitte ändern Sie umgehend <a href="index.php?p=management&tab=tab_change_password">Ihr Passwort</a>.';
	$translation['mobile_changed']		= 'Mobile Nummer wurde erfolgreich geändert.';
	$translation['default_pw']		= 'Sie verwenden dass Standard Passwort. Bitte ändern Sie umgehend <a href="index.php?p=frontdeskemployees&tab=tab_change_password">Ihr Passwort</a>.';
	$translation['password_changed']		= 'Passwort wurde erfolgreich geändert. Aus Sicherheitsgründen müssen Sie sich erneut anmelden.';
	$translation['password_changed_for_other']	= 'Passwort wurde erfolgreich geändert.';
	$translation['email_changed']		= 'E-Mail Adresse wurde erfolgreich geändert.';
	$translation['password_change_ldap']	= 'Nur lokale Benutzer können Ihr Passwort ändern';
	$translation['failed_login'] 		= 'Falscher Benutzername oder falsches Passwort';
	$translation['continue'] 			= 'Weiter';
	$translation['already_checkedin']   = 'Besucher wurde zwischenzeitlich bereits eingecheckt';
	$translation['already_checkedout']  = 'Besucher wurde zwischenzeitlich bereits ausgecheckt';
	$translation['next']	 			= '>';
	$translation['back']	 			= '<';
	$translation['no_employee_yet']			= 'Noch keine Benutzer erfasst';
	$translation['length_menu'] = "Zeige _MENU_ Einträge";
	$translation['no_entries'] = "Keine Einträge vorhanden";
	$translation['info_page'] = "Seite _PAGE_ /  _PAGES_";
?>
