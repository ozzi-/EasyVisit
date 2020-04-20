<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

// THIS IS A EXAMPLE IMPLEMENTATION, PLEASE REFER TO YOUR SMS GATEWAY API
  require_once './api.php';
  if(isset($_POST["surname"])&&isset($_POST["name"])&&isset($_POST["company"])&&isset($_POST["secret"])&&isset($_POST["mobile"])){
    if(!hash_equals($_POST["secret"],NOTIFY_CHECKIN_ENDPOINT_SECRET)){
      http_response_code(401);
      die();
    }
    $url = "https://YOUR_SMS_PROVIDER_HERE";
    $username = "your_user_name";
    $pw = "your_password";

    $mobile = str_replace(' ','',$_POST["mobile"]);

    $msg = "Check-In von: ".$_POST["name"]." ".$_POST["surname"]." der Firma ".$_POST["company"];
    $msg = htmlspecialchars($msg, ENT_XML1);

    $request = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
		<SMSBoxXMLRequest>
  		  <username>".$username."</username>
		  <password>".$pw."</password>
		  <command>WEBSEND</command>
		  <parameters>
		    <receiver>".$mobile."</receiver>
		    <service>SMS</service>
		    <text>".$msg."</text>
		    <guessOperator/>
    		  </parameters>
	     </SMSBoxXMLRequest>";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if(!$result){
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      http_response_code($httpcode);
      die();
    }
    curl_close($ch);
    if (strpos($result, "receiver status=\"OK\"") !== false) {
      die("sent");
    }else{
      http_response_code(409);
      echo($result);
    }
  }else{
    http_response_code(400);
    die();
  }
?>
