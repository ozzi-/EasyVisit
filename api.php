<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

  require_once (dirname(__FILE__)."/includes/session.php");
  sec_session_start();

  // Escaping Input Data
  foreach ($_POST as $name => $val){
    if($name!=="CSRF" && $name!=="val"){
      $_POST[$name] = htmlspecialchars($val, ENT_QUOTES);
    }
  }
  foreach ($_GET as $name => $val){
    if($name!=="CSRF" && $name!=="val"){
      $_GET[$name] = htmlspecialchars($val, ENT_QUOTES);
    }
  }

  require_once (dirname(__FILE__)."/includes/config.php");

  if(PHP_SHOW_ERRORS){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  }else{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
  }
  //set_error_handler("customErrorHandler");
  //set_exception_handler("customExceptionHandler");

  require_once (dirname(__FILE__)."/includes/db.php");
  require_once (dirname(__FILE__)."/includes/syslog.php");
  require_once (dirname(__FILE__)."/includes/translations.php");

  checkForUserStatus();

  function restoreBackup($backupFile){
    $backupFilePath = BACKUP_PATH.$backupFile;
    if(file_exists($backupFilePath) && strpos($backupFile,"..") === false && strpos($backupFile,"%") === false){
      $zip = new ZipArchive;
      $res = $zip->open($backupFilePath);
      if ($res === TRUE) {
        echo("Valid ZIP archive<br>");
        $res = $zip->extractTo(getcwd());
        echo("Extracted ZIP archive ".($res?"successfully":"unsuccessfully")."<br>");
        $dump = file_get_contents("dump.sql");
        if($dump!==FALSE){
          $mqRes = multiquery($dump);
          if($mqRes===TRUE){
            echo("Successfully restored db.<br>");
            return true;
          }else{
            echo("Failed restoring DB:".$mqRes);
          }
        }else{
          $zip->close();
          echo("Could not load dump.sql. Aborting");
        }
      }else{
        echo("Invalid ZIP archive. Aborting");
      }
    }
    toSysLogP("Failed to restore backup due to invalid backup file provided" ,LOG_ERR);
    return false;
  }

  function restoreDump($backupFile){
    $backupFilePath = BACKUP_PATH.$backupFile;
    if(file_exists($backupFilePath) && strpos($backupFile,"..") === false && strpos($backupFile,"%") === false){
      $dump = file_get_contents($backupFilePath);
      if($dump!==FALSE){
        $mqRes = multiquery($dump);
        if($mqRes===TRUE){
          echo("Successfully restored db.<br>");
          return true;
        }else{
          echo("Failed restoring DB:".$mqRes);
        }
      }else{
        echo("Could not load DB dump. Aborting");
      }
    }
    toSysLogP("Failed to restore backup due to invalid backup file provided" ,LOG_ERR);
    return false;
  }

  function csrfAction($action,$buttonName,$jscb=false){
    if($jscb!=false){
      $jscb = 'onsubmit="'.$jscb.'"';
    }?>
    <form <?= $jscb ?> action="<?= $action ?>" method="POST">
      <?= addCSRFField() ?>
      <button class="btn" style="background:<?= BRANDING_COLOR ?>; cursor: pointer;"><?= $buttonName ?></button>
    </form>
  <?php }

  function checkForUserStatus(){
    if(login_check()){
      $query = "SELECT frontdeskemployee_active, frontdeskemployee_admin FROM frontdeskemployee WHERE frontdeskemployee_id = ?;";
      $result=s_query($query,array('i'),array($_SESSION['id']));
      if(empty($result) || $result[0]["frontdeskemployee_active"]==0){
        logout();
      }
      $_SESSION['admin'] = $result[0]["frontdeskemployee_admin"];
    }
  }

  function searchVisitor($name,$surname,$company){
    $name = $name==""?"*":$name;
    $surname = $surname==""?"*":$surname;
    $company = $company==""?"*":$company;
    $search = "SELECT * FROM visitor  LEFT JOIN visitorlist ON visitor.visitor_visitorlist_idfk = visitorlist.visitorlist_id WHERE LOWER(visitor_name) LIKE ? OR LOWER(visitor_surname) LIKE ? OR LOWER(visitor_company) LIKE ? ORDER BY visitorlist.visitorlist_date DESC;";
    $result=s_query($search,array('s','s','s'),array("%".$name."%","%".$surname."%","%".$company."%"));
    return $result;
  }

  function getvisitorLists($limit,$offset){
    $limit = intval($limit);
    $offset = intval($offset);
    $visitorlist =	"SELECT visitorlist_id, visitorlist_date, visitorlist_frontdeskemployee_idfk, COUNT(v.visitor_id) AS visitorcount FROM visitorlist AS vl LEFT JOIN visitor AS v ON vl.visitorlist_id = v.visitor_visitorlist_idfk GROUP BY vl.visitorlist_id ORDER BY visitorlist_date DESC LIMIT ".$limit." OFFSET ".$offset.";";
    $result=s_query($visitorlist,array(),array());
    return $result;
  }

  function getIdentifiersList(){
    $identifierslist = "SELECT * FROM identifier WHERE identifier_deleted != 1;";
    $result=s_query($identifierslist,array(),array());
    return $result;
  }

  function getInputStatus(){
    $visitorlist =	"SELECT * FROM flag WHERE flag_name='awaiting_input' LIMIT 1;";
    $result=s_query($visitorlist,array(),array());
    if(count($result)>0){
      return $result[0];
    }
    return false;
  }

  function getCurrentVersion(){
    $version=json_decode(file_get_contents('version'),true);
    if($version===NULL){
      echo("Failed loading version file");
      die();
    }
    return $version;
  }

  function setInputStatus($status){
    $status=$status?1:0;
    $visitorlist = "UPDATE flag SET flag_value = ? WHERE flag_name='awaiting_input' LIMIT 1;";
    $result=s_query($visitorlist,array('i'),array($status));
    return $result;
  }

  function checkoutVisitor($visitorID,$time){
    $getvisitor = "SELECT * FROM visitor WHERE visitor_id=?;";
    $result=s_query($getvisitor,array('i'),array($visitorID));

    $checkoutvisitor = "UPDATE visitor SET visitor_end=? , visitor_end_frontdeskemployee_idfk=? WHERE visitor_id=?;";
    s_i_query($checkoutvisitor,array('s','i','i'),array($time,$_SESSION['id'],$visitorID));
    toSysLog("Check-out of visitor with ID ".$visitorID);
    return $result[0]['visitor_visitorlist_idfk'];
  }

  function checkReoccuringCode($code){
    $result=s_query("SELECT * FROM reoccuringvisitor WHERE reoccuringvisitor_code = ? LIMIT 1",array('s'),array($code));
    if(sizeof($result)!==0){
      return $result[0];
    }
    return false;
  }

  function getContactPersons(){
    $qry = "SELECT * FROM contactperson ORDER BY contactperson_name;";
    $result=s_query($qry,array(),array());
    return $result;
  }

  function getReoccuring(){
    $qry = "SELECT * FROM reoccuringvisitor ORDER BY reoccuringvisitor_id;";
    $result=s_query($qry,array(),array());
    return $result;
  }

  function addReoccuring($code,$name,$surname,$company,$contactperson){
    $addreoccuring = "INSERT INTO reoccuringvisitor(
                      reoccuringvisitor_code,
                      reoccuringvisitor_name,
                      reoccuringvisitor_surname,
                      reoccuringvisitor_company,
                      reoccuringvisitor_contactperson
                      )VALUES(?,?,?,?,? );";

    $result=s_i_query($addreoccuring,
                      array('s','s','s','s','s'),
		      array($code,$name,$surname,$company,$contactperson)
    );
    toSysLog("Creating reoccuring visitor ".$name." ".$result);
    return intval($result);
  }


  function addIdentifier($name,$description){
    $addidentifier = "INSERT INTO identifier(
                      identifier_name,
                      identifier_description,
                      identifier_deleted
                      )VALUES(?,?,?);";
    $result=s_i_query($addidentifier,
                       array('s','s','i'),
                       array($name,$description,0)
    );
    toSysLog("Creating identifier ".$name." ".$result);
    return intval($result);
  }

  function addContactPerson($name){
    $addcontactperson="INSERT INTO contactperson(
                       contactperson_name
                       )VALUES(?);";

    $result=s_i_query($addcontactperson,
                      array('s'),array($name)
    );
    toSysLog("Creating contact person ".$name." ".$result);
    return intval($result);
  }

  function deleteReoccuring($id){
    $deletecheckinvisitor="DELETE FROM reoccuringvisitor WHERE reoccuringvisitor_id = ?;";
    toSysLog("Deleting reoccuring ".$id);
    return s_query($deletecheckinvisitor,array('i'),array($id));
  }

  function setIdentifierDeleted($id){
    $sid="UPDATE identifier SET identifier_deleted = 1 WHERE identifier_id = ?;";
    toSysLog("Setting identifier as deleted ".$id);
    return s_query($sid,array('i'),array($id));
  }

  function deleteContactPerson($id){
    $deletecheckinvisitor="DELETE FROM contactperson WHERE contactperson_id = ?;";
    toSysLog("Deleting contactperson ".$id);
    return s_query($deletecheckinvisitor,array('i'),array($id));
  }

  function getVisitorCountTodayCheckedIn(){
    return getVisitorCountInternal(date('Y-m-d')," AND visitor_end_frontdeskemployee_idfk IS null");
  }

  function getVisitorCountTodayCheckedOut(){
    return getVisitorCountInternal(date('Y-m-d')," AND visitor_end_frontdeskemployee_idfk IS NOT null");
  }

  function getVisitorCountToday(){
    return getVisitorCountInternal(date('Y-m-d'));
  }

  function getVisitorCountThisMonth(){
    return getVisitorCountInternal(date('Y-m')."-%%");
  }

  function getVisitorCountHistory($count){
    $res = array();
    $currentYM = date('Y-m');
    array_push($res, array($currentYM,getVisitorCountThisMonth()));

    for($i = 1; $i <= $count; $i++){
      $dateAgo = date("Y-m", strtotime( date( "Y-m-01") . "-".$i." months"));
      array_push($res, array($dateAgo,getVisitorCountXMonthsAgo($i)));
    }
    return $res;
  }

  function getVisitorCountXMonthsAgo($x){
    $x = intval($x);
    $date = date("Y-m", strtotime( date( "Y-m-01") . "-".$x." months"));
    $date = $date."-__";
    return getVisitorCountInternal($date);
  }

  // TODO use prepared statement, even though those aren't user inputs
  function getVisitorCountInternal($datemode,$additional=""){
    $qry = "SELECT visitorlist_id FROM visitorlist WHERE visitorlist_date LIKE '".$datemode."'";
    $result=s_query($qry,array(),array());
    $strng = "";
    foreach ($result as &$val) {
      $strng = $strng.$val["visitorlist_id"]." = visitor_visitorlist_idfk OR ";
    }
    $strng=rtrim($strng," OR ");
    if($strng===""){
      return 0;
    }
    $qry = "SELECT count(*) FROM visitor WHERE ".$strng.$additional;
    $result=s_query($qry,array(),array());
    return intval($result[0]["count(*)"]);
  }

  function vistorCheckedOut($visitorID){
    $visitorcheckedout="SELECT visitor_end FROM visitor WHERE visitor_id=?;";
    $res = s_query($visitorcheckedout,array('i'),array($visitorID));
    return $res[0]["visitor_end"]!=null;
  }

  function deleteCheckinVisitor($id){
    $deletecheckinvisitor="DELETE FROM checkinvisitor WHERE checkinvisitor_id=?;";
    $res = del_query($deletecheckinvisitor,array('i'),array($id));
    if($res!=1){
      toSysLog("Cannot delete self check-in of visitor with id ".$id." as already deleted");
      return false;
    }
    toSysLog("Deleting self check-in of visitor with id ".$id." res =".$res);
    return true;
  }

	function getCheckinVisitorCount(){
		$checkinvisitorcount="SELECT COUNT(*) FROM checkinvisitor;";
		$res = s_query($checkinvisitorcount,array(),array());
		return $res[0]["COUNT(*)"];
	}

	function getCheckoutVisitorCount(){
		$getcurrentlistid = "SELECT visitorlist_id FROM visitorlist WHERE visitorlist_date = ?;";
		$res = s_query($getcurrentlistid,array('s'),array(date('Y-m-d')));
		if(empty($res)){
			return 0;
		}
		$visitorlistID = $res[0]["visitorlist_id"];
		$checkinvisitor	="SELECT COUNT(*) FROM visitor WHERE visitor_end IS NULL AND visitor_visitorlist_idfk = ?;";
		$res = s_query($checkinvisitor,array('i'),array($visitorlistID));
		return $res[0]["COUNT(*)"];
	}
	function getSpecificHeaderName($name){
		$name = str_replace("-", "_", $name);
        	return "HTTP_".strtoupper($name);
        }

	function doProxyAndAutoLogin($page){
		if(LOGIN_MODE==="auto"){
			if(!isset($_SESSION['username'])){
				session_regenerate_id(true);
				$_SESSION['username'] = "autologin";
				$_SESSION['id']  = 9999;
				$_SESSION['admin'] = 0;
			}
			if(!isset($_SESSION['CSRF'])){
				$_SESSION['CSRF'] = base64_encode( openssl_random_pseudo_bytes(32));
			}
		}
		if(LOGIN_MODE==="proxy" && $page!=="loginform"){
                	$headerName = getSpecificHeaderName(LOGIN_PROXY_HEADER);
			if(isset($_SERVER[$headerName])){
				$user = $_SERVER[$headerName];
				$fde = getFrontdeskemployeeByName($user);
				if(empty($fde)){
					if(LOGIN_AUTO_REGISTER){
						toSysLog("PROXY auto register for user ".$user);
						autoRegisterFrontdeskemployee($user);
						$fde = getFrontdeskemployeeByName($user);
					}else{
						logout();
						header('Location: index.php?p=loginform&msg=username_unknown');
						die();
					}
				}
				if(!$fde[0]['frontdeskemployee_active']){
					toSysLogP("Login attempt for inactive user ".$username,LOG_WARNING);
					header('Location: index.php?p=loginform&msg=user_inactive');
					die();
				}
				if(!isset($_SESSION['username'])){
					session_regenerate_id(true);
					$_SESSION['username']	=$fde[0]["frontdeskemployee_username"];
					$_SESSION['id']			=$fde[0]["frontdeskemployee_id"];
					$_SESSION['admin']		=$fde[0]["frontdeskemployee_admin"];
				}
				if(!isset($_SESSION['CSRF'])){
					$_SESSION['CSRF'] = base64_encode( openssl_random_pseudo_bytes(32));
				}
			}
		}
	}
	
	function visitorCheckinToday($visitorID){
		$getVisitorlistID="SELECT visitor_visitorlist_idfk FROM visitor WHERE visitor_id=?";
		$visitorlistID 		= s_query($getVisitorlistID,array('i'),array($visitorID));
		if(empty($visitorlistID)){
			return false;
		}
		$visitorlistID=$visitorlistID[0]["visitor_visitorlist_idfk"];
		$getVisitorlistDate="SELECT visitorlist_date FROM visitorlist WHERE visitorlist_id=?";
		$visitorlistDate 	= s_query($getVisitorlistDate,array('i'),array($visitorlistID));
		if(empty($visitorlistDate)){
			return false;
		}
		$visitorlistDate=$visitorlistDate[0]["visitorlist_date"];
		return date('Y-m-d')===$visitorlistDate;
	}
	
	function getCheckinVisitor(){
		$checkinvisitor	="SELECT * FROM checkinvisitor ORDER BY checkinvisitor_id ASC LIMIT 1;";
		return s_query($checkinvisitor,array(),array());
	}
	
	function selfCheckinVisitor($name,$surname,$company,$contactperson,$signature){
		$checkinvisitor	="INSERT INTO checkinvisitor(
							checkinvisitor_name,
							checkinvisitor_surname,
							checkinvisitor_company,
							checkinvisitor_contactperson,
							checkinvisitor_signature,
							checkinvisitor_start,
							checkinvisitor_date
						)VALUES(
							?,?,?,?,?,?,?
						);";
		$start = date('H:i', time());
		$date = date('Y-m-d');
		$result=s_i_query($checkinvisitor,
			array('s','s','s','s','s','s','s'),
			array($name, $surname, $company, $contactperson, $signature, $start, $date)
		);
		setInputStatus(false);

		informContactPerson($name,$surname,$company,$contactperson);

		toSysLog("Self checkin of visitor ".$name." ".$surname);
		return intval($result);
	}

	function checkinVisitor($name,$surname,$company,$idshown,$identifier,$contactperson,$signature,$start,$date){
		$checkinvisitor	="INSERT INTO visitor(
						visitor_visitorlist_idfk,
						visitor_name,
						visitor_surname,
						visitor_company,
						visitor_idshown,
						visitor_identifier_idfk,
						visitor_contactperson,
						visitor_start,
						visitor_start_frontdeskemployee_idfk,
						visitor_signature
						)VALUES(
						?,?,?,?,?,?,?,?,?,?
						);";

		$visitorlistspecific = "SELECT visitorlist_id FROM visitorlist WHERE visitorlist_date = ?;";
		$visitorlist=s_query($visitorlistspecific,array('s'),array($date));
		if(empty($visitorlist)){
			$visitorlistcreate = "INSERT INTO visitorlist(visitorlist_date, visitorlist_frontdeskemployee_idfk)VALUES(?,?)";
			$visitorlistid=s_i_query($visitorlistcreate,
				array('s','i'),
				array($date,$_SESSION['id'])
			);
		}else{
			$visitorlistid=($visitorlist[0]["visitorlist_id"]);
		}

		$result=s_i_query($checkinvisitor,
			array('i','s','s','s','i','i','s','s','i','s'),
			array($visitorlistid, $name, $surname, $company, $idshown, $identifier, $contactperson, $start, $_SESSION['id'], $signature)
		);

		toSysLog("Check-in of visitor with id=".intval($result));

		return intval($result);
	}

	function informContactPerson($name, $surname, $company, $contactperson){
		if(!NOTIFY_CHECKIN_EMAIL && !NOTIFY_CHECKIN_MOBILE){
			return;
		}
		$getContactPersonQuery = "SELECT * FROM contactperson WHERE contactperson_name =?";
		$result=s_query($getContactPersonQuery,array('s'),array($contactperson));
		if(!empty($result)){
			$mobile = $result[0]['contactperson_mobile'];
			$email = $result[0]['contactperson_email'];
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded\r\n'));
			$post = array(
				'surname'	=>$surname,
				'name'		=>$name,
				'company'	=>$company,
				'secret'	=>NOTIFY_CHECKIN_ENDPOINT_SECRET,
				'mobile'	=>$mobile,
				'email'		=>$email
			);
			curl_setopt($curl ,CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, NOTIFY_CHECKIN_ENDPOINT_INSECURE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, NOTIFY_CHECKIN_ENDPOINT_INSECURE);

			if(NOTIFY_CHECKIN_EMAIL && ! empty($email)){
				toSysLog("Sending post to notification email endpoint: ".NOTIFY_CHECKIN_EMAIL_ENDPOINT);
				curl_setopt($curl, CURLOPT_URL, NOTIFY_CHECKIN_EMAIL_ENDPOINT);
				$result = curl_exec ($curl);
				if($result===false){
			                toSysLogP("Error occured informing email endpoint of checkin: ".(isset($http_response_header)?var_export($http_response_header,true):"no header")." -- ".var_export(error_get_last(),true),LOG_WARNING);
				}
			}
			if(NOTIFY_CHECKIN_MOBILE && ! empty($mobile)){
				toSysLog("Sending post to notification mobile endpoint: ".NOTIFY_CHECKIN_MOBILE_ENDPOINT);
				curl_setopt($curl, CURLOPT_URL, NOTIFY_CHECKIN_MOBILE_ENDPOINT);
				$result = curl_exec ($curl);
				//if(!$result){
 				//	echo 'Curl-Fehler: ' . curl_error($curl);
				//}
				if($result===false){
			                toSysLogP("Error occured informing mobile endpoint of checkin: ".(isset($http_response_header)?var_export($http_response_header,true):"no header")." -- ".var_export(error_get_last(),true),LOG_WARNING);
				}
			}
			curl_close($curl);
		}
	}

	function currentlyLoggedOnUserIsLDAP(){
		$query = "SELECT frontdeskemployee_ldap FROM frontdeskemployee WHERE frontdeskemployee_id = ?;";
		$result=s_query($query,array('i'),array($_SESSION['id']));
		return $result[0]["frontdeskemployee_ldap"];
	}

	function getSpecificvisitorListById($id){
		$query		=	"SELECT visitorlist_id,visitorlist_date,frontdeskemployee_id,frontdeskemployee_username FROM visitorlist
						JOIN frontdeskemployee ON frontdeskemployee.frontdeskemployee_id = visitorlist.visitorlist_frontdeskemployee_idfk
						WHERE visitorlist.visitorlist_id = ? ;";
		return getSpecificvisitorListInt($id,$query);
	}
	
	function getSpecificVisitor($visitorID){
		$checkinvisitor	="SELECT * FROM visitor WHERE visitor_id = ?;";
		$res = s_query($checkinvisitor,array('i'),array($visitorID));
		return $res[0];
	}
	
	function getSpecificvisitorList($date){
		$query		=	"SELECT visitorlist_id,visitorlist_date,frontdeskemployee_id,frontdeskemployee_username FROM visitorlist
						JOIN frontdeskemployee ON frontdeskemployee.frontdeskemployee_id = visitorlist.visitorlist_frontdeskemployee_idfk
						WHERE visitorlist.visitorlist_date = ? ;";
		return getSpecificvisitorListInt($date,$query);
	}
	
	function getSpecificvisitorListInt($var,$visitorlistspecific){
		$getvisitors			= 	"SELECT visitor_id, visitor_visitorlist_idfk, visitor_name, visitor_surname, visitor_idshown, visitor_identifier_idfk,
									visitor_company, visitor_contactperson, visitor_start, visitor_end, visitor_signature,
									idS.identifier_name as identifier_name,
									fdS.frontdeskemployee_username as frontdeskemployee_checkin,  fdE.frontdeskemployee_username as frontdeskemployee_checkout
									FROM visitor
									LEFT JOIN identifier AS idS ON idS.identifier_id = visitor_identifier_idfk
									LEFT JOIN frontdeskemployee AS fdS ON fdS.frontdeskemployee_id = visitor_start_frontdeskemployee_idfk
									LEFT JOIN frontdeskemployee AS fdE ON fdE.frontdeskemployee_id = visitor_end_frontdeskemployee_idfk
									WHERE visitor_visitorlist_idfk = ?;";
		$result=s_query($visitorlistspecific,array('s'),array($var));
		if (!empty($result)){
			$resultvisitor=s_query($getvisitors,array('s'),array($result[0]["visitorlist_id"]));
			$all_left=true;
			if(!empty($resultvisitor)){
				$result[0]["visitors"]=$resultvisitor;
				foreach ($resultvisitor as &$value) {
					if($value["visitor_end"]==NULL){
						$all_left=false;
						break;
					}
				}
			}else{
				$result[0]["visitor"]=[];
			}
			$result[0]["allvisitorLeft"]=$all_left;
			return $result[0];
		}else{
			return null;
		}
		exit();
	}

  function getActiveDevice(){
    $result=s_query("SELECT * FROM device WHERE device_active=true LIMIT 1;" ,array(),array());
    if(count($result)>0){
      return $result[0]['device_name'];
    }
    return false;
  }

  function deleteDevice($deviceID){
    $deactivateAll = "DELETE FROM device WHERE device_id = ?;";
    toSysLog("Deleting device with id=".$deviceID);
    s_i_query($deactivateAll,array('i'),array(intval($deviceID)));
  }

  function activateDevice($deviceID){
    $deactivateAll = "UPDATE device SET device_active=false;";
    s_i_query($deactivateAll,array(),array());

    $activateSpecific = "UPDATE device SET device_active=true WHERE device_id = ? ;";
    $result=s_i_query($activateSpecific ,array('i'),array($deviceID));
    toSysLog("Activating device with id=".$deviceID);
    return $result;
  }

  function getEmployeesForPWChange(){
    return s_query("SELECT frontdeskemployee_id,frontdeskemployee_username FROM frontdeskemployee",array(),array());
  }

  function changePassword($password,$uid){
    $pwchk = checkPWPolicy($password);
    if($pwchk!==""){
      toSysLog("Password change failed due to password policy violation - ".$pwchk);
      return $pwchk;
    }
    $password     = hash("sha512", $password);
    $random_salt  = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    $password     = hash('sha512', $password . $random_salt);

    $qry = "UPDATE frontdeskemployee SET frontdeskemployee_password = ?, frontdeskemployee_salt = ? WHERE frontdeskemployee_id=?;";
    $result=s_query($qry,array('s','s','i'),array($password,$random_salt,$uid));
    toSysLog("Changed Password successfully");
  }

	function changeEmail($email,$uid){
		$qry = "UPDATE contactperson SET contactperson_email = ? WHERE contactperson_id=?;";
		$result=s_query($qry,array('s','i'),array($email,$uid));
		toSysLog($uid." changed Email to ".$email);
	}

	function changeMobile($mobile,$uid){
		$qry = "UPDATE contactperson SET contactperson_mobile = ? WHERE contactperson_id=?;";
		$result=s_query($qry,array('s','i'),array($mobile,$uid));
		toSysLog($uid." changed Mobile to ".$mobile);
	}

	function autoRegisterFrontdeskemployee($username){
		$password		= hash("sha512", uniqid(mt_rand(1, mt_getrandmax()), true)."ABCDEFGHI");
		$random_salt 		= hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		$password 		= hash('sha512', $password . $random_salt);
		$frontdeskemployee	=	"INSERT INTO frontdeskemployee(
									frontdeskemployee_username,
									frontdeskemployee_password,
									frontdeskemployee_salt,
									frontdeskemployee_creation,
									frontdeskemployee_ldap,
									frontdeskemployee_admin
								)VALUES(
									?,?,?,?,?,?
								);";

		toSysLog("Frontdesk employee '".$username."' successfully created");
		return s_i_query($frontdeskemployee, array('s','s','s','s','i','i'), array($username,$password,$random_salt,date('Y-m-d'),0,0));
	}

	function addLocalFrontDeskEmployee($username, $password, $authcheck=true, $pwpolicycheck=true){
		if(strlen($username)<1){
			toSysLog("Adding frontdesk employee failed because username empty");
			return "usernameempty";
		}

		$username=htmlentities($username);

		$result=s_query("SELECT * FROM frontdeskemployee WHERE frontdeskemployee_username = ? LIMIT  1",array('s'),array($username));

		if(sizeof($result)!==0){
			toSysLog("Adding frontdesk employee failed because username already taken");
			return "exists";
		}

		$pwchk = checkPWPolicy($password);
		if($pwchk!=="" && $pwpolicycheck){
			toSysLog("Password change failed due to password policy violation - ".$pwchk);
			return $pwchk;
		}

		$password		= hash("sha512", $password);
		$random_salt 	= hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		$password 		= hash('sha512', $password . $random_salt);

		$frontdeskemployee	=	"INSERT INTO frontdeskemployee(
									frontdeskemployee_username,
									frontdeskemployee_password,
									frontdeskemployee_salt,
									frontdeskemployee_creation,
									frontdeskemployee_ldap,
									frontdeskemployee_admin
								)VALUES(
									?,?,?,?,?,?
								);";

		toSysLog("Frontdesk employee '".$username."' successfully created");
		return s_i_query($frontdeskemployee, array('s','s','s','s','i','i'), array($username,$password,$random_salt,date('Y-m-d'),0,0));
	}

	function getRegisteredDevices(){
		$result=s_query("SELECT * FROM device;" ,array(),array());
		return $result;
	}

	function getIdentifierHistory($id){
		$result=s_query("SELECT visitor_start, visitor_end, visitor_name, visitor_surname, visitor_company, viL.visitorlist_date FROM visitor  LEFT JOIN visitorlist AS viL ON viL.visitorlist_id = visitor_visitorlist_idfk WHERE visitor_identifier_idfk=? ORDER BY visitorlist_date DESC" ,array('i'),array($id));
		return $result;
	}

	function getIdentifierById($id){
		$result=s_query("SELECT * FROM identifier WHERE identifier_id = ?", array('i'),array($id));
		return $result;
	}

	function checkSecret($device_name, $secret,$mustBeActive){
		$result=s_query("SELECT * FROM device WHERE device_name = ? LIMIT  1",array('s'),array($device_name));
		if(sizeof($result)!==0){
			$secretH = hash('sha512', $secret);
			$secretH = hash('sha512', $secretH . $result[0]['device_salt']);
			if (hash_equals($secretH, $result[0]['device_secret'])){
				if($mustBeActive===true){
					return $result[0]['device_active'];
				}
				return true;
			}
		}
		toSysLogP("Checking device secret failed",LOG_WARNING);
		return false;
	}

	function checkCurrentPassword($password){
		$result=s_query("SELECT * FROM frontdeskemployee WHERE frontdeskemployee_id = ? LIMIT  1;",array('i'),array($_SESSION['id']));

		if(sizeof($result)!==0){
			$password = hash('sha512', $password);
			$password = hash('sha512', $password . $result[0]['frontdeskemployee_salt']);
			$ret = hash_equals($password,$result[0]['frontdeskemployee_password']);
			if($ret){
				toSysLogP("Checking current password failed",LOG_WARNING);
			}
			return $ret;
		}else{
			toSysLogP("Checking current password for unknown frontdesk employee",LOG_ERR);
			die("invalid user id");
		}
	}

	function registerDevice($name, $secret){
		s_query("UPDATE device SET device_active = 0;",array(),array());
		$result=s_query("SELECT * FROM device WHERE device_name = ? LIMIT  1",array('s'),array($name));
		if(sizeof($result)!==0){
			toSysLog("Failed registering device because name is already taken");
			return "nameexists";
		}

		$secret	= hash("sha512", $secret);
		$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		$secret = hash('sha512', $secret . $random_salt);

		$frontdeskemployee	=	"INSERT INTO device(
									device_name,
									device_secret,
									device_salt,
									device_registered,
									device_frontdeskemployee_idfk,
									device_active
								)VALUES(
									?,?,?,?,?,?
								);";

		toSysLog("Successfully registered device ".$name);
		
		return s_i_query($frontdeskemployee, array('s','s','s','s','i','i'), array($name,$secret,$random_salt,date('Y-m-d'),$_SESSION['id'],1));
	}
	
	function de_activateFrontdeskemployee($id,$mode){
		if(is_bool($mode)){
			$modeStr = $mode?"activated":"deactivated";
			toSysLog($mode." frontdesk employee with id = ".$id);
			$qry = "UPDATE frontdeskemployee SET frontdeskemployee_active = ? WHERE frontdeskemployee_id=?;";
			$result=s_query($qry,array('i','i'),array($mode,$id));
			return $result;
		}
	}

  function de_promoteFrontdeskemployee($id,$mode){
    if(is_bool($mode)){
      $modeStr = $mode?"promoted":"demoted";
      toSysLog($mode." frontdesk employee with id = ".$id);
      $qry = "UPDATE frontdeskemployee SET frontdeskemployee_admin = ? WHERE frontdeskemployee_id=?;";
      $result=s_query($qry,array('i','i'),array($mode,$id));
      return $result;
    }
  }

	function login($username, $provided_password) {
		if($username==="admin" || LOGIN_MODE==="local"){
			$result=s_query("SELECT * FROM frontdeskemployee WHERE frontdeskemployee_username = ? LIMIT  1",array('s'),array($username));

			if(sizeof($result)!==0){
				$provided_password = hash('sha512', $provided_password);
				$provided_password = hash('sha512', $provided_password . $result[0]['frontdeskemployee_salt']);
				if(!$result[0]['frontdeskemployee_active']){
					toSysLogP("Login attempt for inactive user ".$username,LOG_WARNING);
					return false;
				}
				if (hash_equals($provided_password, $result[0]['frontdeskemployee_password'])){
					session_regenerate_id(true);    // regenerated the session, delete the old one.
					$_SESSION['username']		=$result[0]["frontdeskemployee_username"];
					$_SESSION['id']			=$result[0]["frontdeskemployee_id"];
					$_SESSION['admin']		=$result[0]["frontdeskemployee_admin"];
					$_SESSION['CSRF'] =  base64_encode( openssl_random_pseudo_bytes(32));
					toSysLog("Successful login for username ".$username);
					return true;
				}
				toSysLogP("Login attempt with wrong password for username ".$username,LOG_WARNING);
				return false;
			}else{
				toSysLogP("Login attempt with unknown username ".$username,LOG_WARNING);
			}
		}else{
			$loginRes = authLdap(LOGIN_LDAP_HOSTNAME,LOGIN_LDAP_PORT,LOGIN_LDAP_SECURE,LOGIN_LDAP_BIND_DN,LOGIN_LDAP_TIMEOUT,$username,$provided_password,LOGIN_LDAP_DEBUG);
			if($loginRes){
				$result=s_query("SELECT * FROM frontdeskemployee WHERE frontdeskemployee_username = ? LIMIT  1",array('s'),array($username));
				if(sizeof($result)<1){
					$username=htmlentities($username);
					$id = addLocalFrontDeskEmployee($username,$provided_password,false,false);
					toSysLog("Creating local user account for first time login of ldap user ".$username);
				}
				$result=s_query("SELECT * FROM frontdeskemployee WHERE frontdeskemployee_username = ? LIMIT  1",array('s'),array($username));

				if(!$result[0]['frontdeskemployee_active']){
					toSysLogP("Login attempt for inactive user ".$username,LOG_WARNING);
					return false;
				}
				$id=intval($result);
				session_regenerate_id(true);
				$_SESSION['username']=$username;
				$_SESSION['id']=$result[0]["frontdeskemployee_id"];
				$_SESSION['admin']=$result[0]["frontdeskemployee_admin"];
				$_SESSION['CSRF'] =  base64_encode( openssl_random_pseudo_bytes(32));
				toSysLog("Successful LDAP login for username ".$username);
				return true;
			}
			toSysLogP("LDAP login attempt with wrong password OR unknown username = ".$username,LOG_WARNING);
			return false;
		}
	}

  function logout(){
    toSysLog("logged out");
    session_unset();
    session_destroy();
  }

	function authLdap($server, $port, $secure, $path ,$timeoutInSec, $username, $password,$debug){
		set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
			// error was suppressed with the @-operator
			if (0 === error_reporting()) {
				return false;
			}
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
		$username = ldap_escape($username, null, LDAP_ESCAPE_DN);
		$password = ldap_escape($password, null, LDAP_ESCAPE_DN);
		$path = str_replace("%username%", $username, $path,$done);
		if($done<1){
			toSysLogP("LDAP dn does not contain %username% placeholder --> includes/config.php",LOG_ERR);
			die("Could not replace %username% in path.");
		}
		try{
			if($con = ldap_connect($server, $port)){
				if(!ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3)) {
					toSysLogP("Error with LDAP auth: could not set protocol level to v3" ,LOG_ERR);
					return NULL;
				}
				ldap_set_option($con, LDAP_OPT_NETWORK_TIMEOUT, $timeoutInSec);
				if($secure){
					ldap_start_tls($con);
				}
				$bind_return = ldap_bind($con,$path,$password);
				if($bind_return){
					toSysLog("LDAP bind successful");
					return true;
				}
				// Depending on your ldap you might want to check if the user account is disabled or not
				//$filter="(|(sn=*))"; 
				//$justthese = array( "useraccountcontrol"); 
				//$sr=ldap_search($con, $path, $filter, $justthese); 
				//$info = ldap_get_entries($con, $sr); 
				//$acctDisabled = (bool)($info->userAccountControl & 0x2);  
			}
		}catch (Exception $e){
			$ldap_error_code=ldap_errno($con);
			$ldap_error_name=ldap_error($con);
			toSysLogP("Error with LDAP auth:".$e->getMessage() ,LOG_ERR);
			if($debug){
				echo($ldap_error_name." (".$ldap_error_code.") -> ".$e->getMessage());
				die();
			}
			return false;
		}
		return false;
	}

  function getFrontdeskemployeeByName($name){
    $frontdeskemployees= "SELECT * FROM frontdeskemployee WHERE frontdeskemployee_username = ?;";
    return s_query($frontdeskemployees,array("s"),array($name));
  }

  function getFrontdeskemployees(){
    $frontdeskemployees= "SELECT * FROM frontdeskemployee WHERE frontdeskemployee_username <> 'autologin';";
    return s_query($frontdeskemployees,array(),array());
  }

  function checkCSRF(){
    if (!isset($_SESSION['CSRF']) || !isset($_POST['CSRF']) || !hash_equals($_POST['CSRF'],$_SESSION['CSRF'])){
      toSysLog("Invalid or missing CSRF token submitted by user");
      header('Location: index.php?msg=csrf_mismatch');
      die("");
    }
  }

  function checkPWPolicy($pw){
    $errors = "";
    if(strlen($pw)<PW_POLICY_MIN_LENGTH){
      $errors.="min_length;";
    }
    if(countDigits($pw)<PW_POLICY_MIN_NUMBERS){
      $errors.="min_numbers;";
    }
    if(countCapitalLetters($pw)<PW_POLICY_MIN_CAPITAL){
      $errors.="min_capital;";
    }
    if(countSpecialChars($pw)<PW_POLICY_MIN_SPECIAL){
      $errors.="min_special;";
    }
    return $errors;
  }

  function admin_check_r(){
    if($_SESSION['admin']!==1){
      $bt =  debug_backtrace();
      toSysLogP("User tried to perform an admin action but has no admin privileges. ".$bt[0]['file'] . ' line  '. $bt[0]['line'],LOG_WARNING);
      header('Location: index.php?msg=admin_required');
      die();
    }
  }

  function login_check_r($isStart=false){
    if(!login_check()){
      if(!$isStart){
        $bt = debug_backtrace();
        toSysLog("User tried to perform a an action but is not logged in. ".$bt[0]['file'] . ' line  '. $bt[0]['line']);
      }
      header('Location: index.php?p=loginform');
      die();
    }
  }

  function addCSRFField(){
    echo('<input type="hidden" name="CSRF" id="CSRF" value="'.$_SESSION['CSRF'].'">');
  }

  function getCSRF(){
    return $_SESSION["CSRF"];
  }

  function countSpecialChars($str){
    $strR = preg_replace('/[0-9]+/', '', $str);
    $strR = preg_replace('/[a-zA-Z]+/', '', $strR);
    return strlen($strR);
  }

  function countDigits( $str ){
    return preg_match_all( "/[0-9]/", $str );
  }

  function countCapitalLetters($str){
    $lowerCase = mb_strtolower($str);
    return strlen($lowerCase) - similar_text($str, $lowerCase);
  }

  function injectVarsIntoMSG($msg,array $vars){
    $i = 0;
    foreach($vars as &$var){
      $i++; $count = 0;
      $msg = str_replace('{{'.$i.'}}',$var,$msg);
    }
    return $msg;
  }
?>
