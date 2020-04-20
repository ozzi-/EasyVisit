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

  if(isset($_GET['operation'])){
    $op = $_GET['operation'];

    if($op==="backup"){
      checkCSRF();
      $res = createBackup();
      echo("<b>Backup ".($res!==false?"succeeded</b> - ".$res:"failed</b>")."<br>");
      if($res!==false){
        toSysLog("Backup succeeded: ".$res);
      }else{
        toSysLogP("Backup failed" ,LOG_ERR);
      }?><br>
      <form action="index.php?p=backup" method="POST">
        <button class="btn" style="background:<?= BRANDING_COLOR ?>">Continue</button>
      </form><?php
    }

    if($op==="dump"){
      checkCSRF();
      $res = createDump();
      if(!$res){
        echo("<b>Failed to create Database Backup!</b>");
      }else{
        echo("<b>Database Backup completed successfully: </b><br>".$res."<br>");
      }?>
      <br>
      <form action="index.php?p=backup" method="POST">
        <button class="btn" style="background:<?= BRANDING_COLOR ?>">Continue</button>
      </form><?php
    }

    if($op==="restore" && isset($_GET["backup"]) && isset($_GET["type"])){
      checkCSRF();
      if($_GET["type"]==="full"){
        $res = restoreBackup($_GET["backup"]);
      }elseif($_GET["type"]==="db"){
        $res = restoreDump($_GET["backup"]);
      }
      echo("<br>Restore ".($res?"succeeded":"failed"));
      toSysLog("Restore ".($res?"succeeded":"failed"));
    }

  }else{ ?>

    <div id="spinnerbkp" class="loader centered" style="display: none;"></div>
    <h2>Backups</h2>
    <br>
    <div class="container float-left">
      <div class="row">
        <div class="col"><?php
          csrfAction("index.php?p=backup&operation=backup","Create Full Backup","showSpinner()");
        ?><br><p>These backups allow complete snapshots of EasyVisit.</p>
        </div>
        <div class="col"><?php
          csrfAction("index.php?p=backup&operation=dump","Create Database Backup","showSpinner()");
        ?><br><p>These backups allow database snapshots.</p>
        </div>
      </div>
    </div>
    <div class="container float-left">
      <hr>
      <div class="row">
        <div class="col">
          <?php outputBackups(".zip"); ?>
        </div>
        <div class="col">
          <?php outputBackups(".sql"); ?>
        </div>
      </div>
    </div>
<?php
  }
  function outputBackups($endsWith){
    $backups = scandir(BACKUP_PATH);
    foreach($backups as $backup){
      if($backup!=="." && $backup !==".."){
	  if(endsWith($backup,$endsWith,false)){
            echo($backup);
            csrfAction("index.php?p=backup&operation=restore&type=db&backup=".$backup,"Restore","return confirmFirst()");
            echo("<br>");
          }
      }
    }
    if(sizeof($backups)<3){ // . & ..
      echo("No backups found");
    }
  }
?>
<br><br>
<script>
  function showSpinner(){
    document.getElementById("spinnerbkp").style.display="block";
  }
  function confirmFirst(){
    var conf = confirm("Are you sure you want to restore this backup?");
    if(conf){
      showSpinner();
    }
    return conf;
  }
</script>
