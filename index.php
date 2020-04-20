<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

  if (file_exists("installer.php")){
    header("Location: installer.php");
  }

  require_once './api.php';
  require_once './includes/functions.php';

  sendHeaders(usingHTTPS());
  define('APP_RAN','');

  $page=isset($_GET['p'])?$_GET['p']:"start";
  doProxyAndAutoLogin($page);
  $page_url = (usingHTTPS() ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $page_url = substr($page_url, 0, strrpos( $page_url, '/'));
?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title><?= APP_NAME ?></title>
    <link href="css/custom.php" rel="stylesheet">
    <link href="css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="css/datatables.css" rel="stylesheet">
    <link href="css/open-iconic-bootstrap.css" rel="stylesheet">
    <script src="js/ev.php"></script>
    <script src="js/jquery.slim.min.js"></script>
    <script src="js/qrcode.min.js"></script>
    <script src="js/chart.bundle.min.js"></script>
    <script src="js/datatables.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-clockpicker.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $page_url ?>/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest.php">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body>
    <div class="header">
      <div class="content">
        <nav class="navbar navbar-expand-lg bg-faded">
          <a class="navbar-brand" href="index.php">
            <span><img src="img/logo_navbar.png" width="35" height="35" alt=""></span>
            <span style="position: relative; top:2px;"><?= APP_NAME ?></span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
<?php
if(login_check()){
  addMenuItem("visitorlists",translation_get("menu_visitor_list"),"oi-list",$page);
  addMenuItem("checkinvisitorform",translation_get("menu_checkin"),"oi-action-redo",$page);
  addMenuItem("checkoutvisitorform",translation_get("menu_checkout"),"oi-action-undo",$page);
  addMenuItem("management",translation_get("menu_management"),"oi-people",$page);
  if(admin_check()){
    addMenuItem("configuration",translation_get("menu_configuration"),"oi-cog",$page);
  }
  if(LOGIN_MODE!=="auto" && LOGIN_MODE!=="proxy"){
?>
              <li class="nav-item ">
                <form action="index.php?p=logout" method="POST">
                  <?php addCSRFField(); ?>
                  <a class="nav-link <?= $page===" menu_logout "?"active ":" "; ?>" href="javascript:;" onclick="parentNode.submit();">
                    <span class="oi oi-account-logout"></span>
                    <?= translation_get("menu_logout") ?>
                  </a>
                </form>
              </li>
<?php
  }
}else{
  addMenuItem("loginform",translation_get("menu_login"),"oi-account-login",$page);
} ?>
            </ul>
          </div>
        </nav>
      </div>
    </div>
    <br><br><br><br>
    <div class="content">
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
} ?>
    </div>
    <script>
      pollVisitorStatus();
    </script>
    <div style="box-shadow: 0px 0px 2px 2px rgba(0,0,0,0.2); background-color: white; position: fixed; z-index: 9999; width: 100%; bottom: 0;">
      <div class="content" style="text-align: right">
        <i><?= APP_NAME ." v.".getCurrentVersion()["version"] ?></i>
      </div>
    </div>
  </body>
</html>
