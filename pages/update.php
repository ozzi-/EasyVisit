<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
  defined('APP_RAN') or die();
  require_once './api.php';
  login_check_r();
  admin_check_r();

  $triggersFile="triggers.php";
  $updateFile="update.zip";
?>
  <script>
    function showSpinner(){
      document.getElementById("spinnerbkp").style.display="block";
    }
  </script>
  <div id="spinnerbkp" class="loader centered" style="display: none;"></div>
<?php
  if(isset($_GET['step'])){
    $step=$_GET['step'];
    if($step==="download"){
      checkCSRF();
      $nv=newVersionAvailable();
      $downloadOK = downloadAndCheckUpdate($updateFile,$nv['buildid']);
      if($downloadOK){
        echo("<span class=\"oi oi-check\"></span> Downloaded newest version<br>");
        toSysLog("Downloaded newest update");
        $ret =createBackup("_pre_update_");
        echo(($ret?"<span class=\"oi oi-check\"></span>":"<span class=\"oi oi-x\"></span>")."Created Backup ".($ret?"successfully":"- failed!")."<br><br>");
        csrfAction("index.php?p=update&step=install","Install Update","showSpinner()");
      }else{
        echo("<span class=\"oi oi-x\"></span> Downloaded newest version.<b> Signature INVALID</b>. Aborting");
        toSysLogP("Downloaded newest backup, however signature was invalid!" ,LOG_ERR);
      }
    }
    if($step==="install"){
      checkCSRF();
      if(!file_exists($updateFile)){
        echo("Missing Update File");
        csrfAction("index.php?p=update&step=download","Download","showSpinner()");
      }else{
        $zip = new ZipArchive;
        $res = $zip->open($updateFile);
        if ($res===TRUE) {
          $path = pathinfo(realpath($updateFile), PATHINFO_DIRNAME);
          $zip->extractTo($path);
          $zip->close();
          echo("Starting Update<br>");
	      if(file_exists($triggersFile)){
	        echo("<span class=\"oi oi-bolt\"></span>Running triggers<br><i>");
            include($triggersFile);
            echo("</i><br>");
          }
	  echo("<span class=\"oi oi-check\"></span> Installation complete.<br>");
	  echo("<br>");
          toSysLog("Installed update");
          ?>
		  <form action="index.php?p=update" method="POST">
			<button class="btn" style="background:<?= BRANDING_COLOR ?>">Continue</button>
		  </form>
          <?php
        }else{
          toSysLogP("Update failed (1) - ".$res ,LOG_ERR);
          echo("Update failed (1) - ".$res."<br>");
        }
        $cu1=unlink($updateFile);
        $cu3=true;
        if(file_exists($triggersFile)){
                $cu3=unlink($triggersFile);
        }
        if(!$cu1||!$cu3){
          echo("Failed to remove update files (".$cu1."-".$cu3.")<br>");
          toSysLogP("Failed to remove update files (".$cu1."-".$cu3.")", LOG_ERR);
        }
	$updatedVersionFile = updateVersionFile();
        if(!$updatedVersionFile){
          echo("Failed to update version file<br>");
          toSysLogP("Failed to update version file", LOG_ERR);
        }
      }
    }
  }else{
    $version = getCurrentVersion();
    echo("<b>Currently installed version: ".$version["version"]." (".$version["buildid"].")</b><br>");
    $test = doGet(UPDATE_URL."?operation=test");
    if($test[0]!=202){
      if($test[0]==403){
        echo("Invalid Update Key.<br>");
      }else{
        echo("Connection to update server failed. Please check the configured URL<br>");
      }?>
      <form action="index.php?p=configuration" method="POST">
        <button class="btn" style="background:<?= BRANDING_COLOR ?>">Back</button>
      </form><?php
    }else{
      $nv=newVersionAvailable();
      if($nv!==false){
 	echo("<b>Newer version found :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nv['version']."(".$nv['buildid'].")</b><br>");
        echo("<br>".$nv['version']." Release Notes:<br><i>".$nv['releasenotes']."</i><br><br>");
        csrfAction("index.php?p=update&step=download","<span class=\"oi oi-cloud-download\"></span> Download Update","showSpinner()");
      }else{
  	echo("<span class=\"oi oi-check\"></span> You are using the newest version.<br><br>Release notes:<br>");
        echo("<i>".$version['releasenotes']."</i>");
      }?><br><br>
	<form action="index.php?p=configuration" method="POST">
	  <button class="btn" style="background:<?= BRANDING_COLOR ?>">Back</button>
	</form>
	<?php
    }
  }

  function updateVersionFile(){
    $version = getCurrentVersion();
    $newerURL = UPDATE_URL."?operation=update&buildid=".$version["buildid"];
    $ret = doGet($newerURL);
    if(!$ret){
      echo("Failed updating version file");
      toSysLogP("Failed updating version file" ,LOG_ERR);
    }else{
      toSysLog("Updated version file");
      $ver = json_decode($ret[1],true);
      $test = doGet(UPDATE_URL."?operation=updatecomplete&buildid=".$ver["buildid"]);
      return file_put_contents("version",$ret[1]);
    }
  }

  function newVersionAvailable(){
	  $version = getCurrentVersion();
  	  $newerURL = UPDATE_URL."?operation=update&buildid=".$version["buildid"];
	  $ret = doGet($newerURL);
	  if($ret[0]===200){
            return json_decode($ret[1],true);
	  }
	  return false;
  }

  function downloadAndCheckUpdate($updateFile,$currentBuildID){
	  $downloadURL = UPDATE_URL."?operation=download&buildid=".$currentBuildID;

	  $retCode  = downloadFile($updateFile,$downloadURL);
	  if($retCode==200){
		return true;
	  }else{
		die("Received status code $retCode from update server. Expected 200. Aborting");
	  }
  }

  function doGet($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, UPDATE_TLS_VERIFY);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,            1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Key: '.UPDATE_KEY,
	));
	$content  = @curl_exec($ch);
	if(curl_errno($ch)){
		die("Network error. ".curl_error($ch));
	}
	$code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);
	curl_close($ch);
	return array($code,$content);
  }

  function downloadFile($name,$url){
    $fp = fopen($name, 'w+');

    if($fp === false){
        throw new Exception('Could not open: ' . $saveTo);
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-Key: '.UPDATE_KEY,
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, UPDATE_TLS_VERIFY);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

    @curl_exec($ch);
    if(curl_errno($ch)){
      die("Downloading update failed. ".curl_error($ch));
    }
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $statusCode;
  }
?>
