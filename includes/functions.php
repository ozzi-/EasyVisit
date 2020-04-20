<?php
function addMenuItem($lnk,$name,$icon,$page){
  $highlight = ($lnk === $page);
  $highlight = !$highlight && $page==="visitorlist" && $lnk==="visitorlists"?true:$highlight;
  $highlight = $page==="loginform"?false:$highlight;  ?>
    <li class="nav-item ">
      <a class="nav-link <?= $page===$lnk?" active ":" "; ?>" href="index.php?p=<?= $lnk ?>" <?php if($highlight){ ?> style="color: <?= BRANDING_WARNING_COLOR ?>" <?php }  ?>>
      <span class="oi <?= $icon ?>"></span> <?= $name?>
  <?php
  if($lnk==="checkinvisitorform"){ ?>
      <span id="checkincount" style="visibility:hidden; background: <?= BRANDING_COLOR ?>" class="badge badge-primary">0</span></a>
  <?php
  }else if($lnk==="checkoutvisitorform"){ ?>
      <span id="checkoutcount" style="visibility:hidden; background: <?= BRANDING_COLOR ?>" class="badge badge-primary">0</span></a>
  <?php
  }else{
    echo("&nbsp;</a>");
  }?>
     </li>
  <?php
}

function sendHeaders($https){
  header("X-Content-Type-Options: nosniff");
  header("X-Frame-Options: DENY");
  header("X-XSS-Protection: 1; mode=block");
  if($https){
    header("Strict-Transport-Security: max-age=2592000");
  }
  header("Content-Security-Policy: default-src 'none'; script-src 'self' 'unsafe-inline'; object-src 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; media-src 'self'; frame-src 'none'; font-src 'self'; connect-src 'self'");
}

function createDump($cfn=""){
  include_once("includes/Mysqldump.php");
  set_time_limit(300);
  $rootPath = realpath('');
  $dumpSettings = array(
    'add-drop-table' => true
  );
  $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS,$dumpSettings);
  $dumpName= BACKUP_PATH.'dbdump'.$cfn.'_'.date('m-d-Y_H-i-s').'.sql';
  $dump->start($dumpName);
  $res = (file_exists($dumpName) && filesize($dumpName)>3);
  if(!$res){
    return false;
  }
  return $dumpName;
}

 function createBackup($cfn=""){
  include_once("includes/Mysqldump.php");
  set_time_limit(300);
  $rootPath = realpath('');
  $dumpSettings = array(
    'add-drop-table' => true
  );
  $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS,$dumpSettings);
  $dump->start('dump.sql');

  $zip = new ZipArchive();
  $zipName= BACKUP_PATH.'backup'.$cfn.'_'.date('m-d-Y_H-i-s').'.zip';
  $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
  $addedFiles = 0;
  $it = new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS);
  $it = new RecursiveCallbackFilterIterator($it, 'filter');
  $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);

  foreach ($it as $name => $file){
    // Skip directories (they would be added automatically)
    if (!$file->isDir()){
      if(!endsWith($file,".swp")){
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        $zip->addFile($filePath, $relativePath);
        $addedFiles++;
      }
    }
  }
  $ret = $zip->close()?$zipName:false;
  unlink('dump.sql');
  return $ret;
}

class CRFI extends RecursiveFilterIterator{
  public function accept(){
    $excludes = array("backups");
    return !($this->isDir() && in_array($this->getFilename(), $excludes));
  }
}

function usingHTTPS(){
  return ((! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
         (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') );
}

function customErrorHandler($errno, $errstr) {
  if(PHP_SHOW_ERRORS){
    echo "<b>Error Handler:</b> [$errno] $errstr<br>";
  }else{
    echo("<b>Unexpected Error occured</b>");
  }
  toSysLogP("Error Handler: [$errno] $errstr",LOG_ERROR_LEVEL);
  die();
}


function customExceptionHandler($exception){
  if(PHP_SHOW_ERRORS){
    echo "<b>Error Handler:</b>".$exception->getMessage()."<br>";
  }else{
    echo("<b>Unexpected Error occured</b>");
  }
  toSysLogP("Exception Handler: ".$exception->getMessage(),LOG_ERROR_LEVEL);
  die();
}


function removeInstaller(){
  if(!unlink("installer.php")){
    toSysLogP("Error removing installer.php");
  }
}

function removeSQL(){
  if(!unlink("db.sql")){
    toSysLogP("Error removing db.sql");
  }
}

function endsWith($haystack, $needle){
  $length = mb_strlen($needle, 'UTF-8');
  return $length === 0 || (mb_substr($haystack, -$length, $length, 'UTF-8') === $needle);
}

function filter($file, $key, $iterator){
  $exclude = array('backups');
  return ! in_array($file->getFilename(), $exclude);
}
