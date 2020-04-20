<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
  require_once './api.php';
  require_once './includes/functions.php';

  define('APP_RAN','');

  $page = isset($_GET['p'])?$_GET['p']:"tabletindex";
  $page_url = (usingHTTPS() ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $page_url = substr($page_url, 0, strrpos( $page_url, '/'));

  sendHeaders(usingHTTPS());
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="apple-mobile-web-app-title" content="<?= translation_get("app_name") ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?= translation_get("app_name") ?></title>
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <script src="js/ev.php"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.php" rel="stylesheet">
    <link href="css/open-iconic-bootstrap.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $page_url ?>/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest.php">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body>
    <script>
      // if standalone, rewrite href's so safari does not open when navigating
      (function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(chref=d.href).replace(e.href,"").indexOf("#")&&(!/^[a-z\+\.\-]+:/i.test(chref)||chref.indexOf(e.protocol+"//"+e.host)===0)&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone");
        // if standalone, disable double tap and pinch zooming
        if (window.navigator.standalone === true ){
           document.addEventListener('gesturestart', function (e) { e.preventDefault(); });
        }
    </script>
<?php
if(isset($_GET['msg'])){
  $msg = $_GET['msg'];
  if(isset($_GET['type'])){
    if($_GET['type']==="success"){ ?>
      <div class="alert alert-success">
        <strong><?= translation_get($msg) ?></strong>
      </div>
<?php
    }
  }else{ ?>
      <div class="alert alert-warning">
        <strong><?= translation_get("error") ?></strong>
        <?= translation_get($msg) ?>
      </div>
<?php
  }
}
$page = preg_replace("/[^0-9a-zA-Z]/", "", $page);
$page .= ".php";
$include=@include("pages/".$page);
if(!$include) {?>
      <div class="alert alert-warning">
        <strong><?= translation_get("error") ?></strong>
        <?= translation_get("invalid_view") ?>
      </div>
<?php
}
if($page==="tabletform.php" && (MOTD_MODE==="file" || MOTD_MODE==="url")){
  if(MOTD_MODE==="file" && strlen(MOTD_VAL)>1){
    $motd_include = @include(MOTD_VAL);
    if(!$motd_include){
      toSysLogP("Error loading MOTD from file (invalid path / privilege issue?)" , LOG_ERR);
    }
  }
  if(MOTD_MODE==="url" && strlen(MOTD_VAL)>1){ ?>
      <span id="motd_url"></span>
      <script>
        var request = new XMLHttpRequest();
          request.open("GET","<?= MOTD_VAL ?>");
          request.addEventListener('load', function(event) {
          if (request.status == 200) {
            document.getElementById('motd_url').innerHTML = request.responseText;
          } else {
            console.warn("Could not load MOTD \r\n\r\n"+request.statusText, request.responseText);
          }
        });
        request.send();
      </script>
<?php
  }
} ?>
  </body>
</html>
