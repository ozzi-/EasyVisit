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

  if(isset($_GET["code"])){
      if($_GET["code"]==200){

      }else{?>
      <script>
        alert("Image Upload failed "+<?= $_GET["code"] ?>);
        window.location = "index.php?p=configuration";
      </script>
  <?php } }
?>
<h2>
  <?= translation_get('config_title_app'); ?>
</h2>
<a href="index.php?p=update">
    <span class="oi oi-cloud-download"></span>
    <b>Update</b>
</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="index.php?p=backup">
    <span class="oi oi-file"></span>
    <b>Backups</b>
</a><br><br>
<?php
createHTMLINP("UPDATE_URL", "config_update_url");
createHTMLINP("UPDATE_KEY", "config_update_key");
echo("<br>");
createHTMLCB("ALLOW_TO_SET_CHECKOUT_TIME","config_app_set_checkout");
echo("<br>");
createHTMLCB("ENABLE_VISITOR_ID_SHOWN","config_app_enable_visitor_id_shown");
echo("<br>");
createHTMLCB("ENABLE_VISITOR_IDENTIFIER","config_app_enable_visitor_identifier");echo("<br>");
createHTMLCB("ENABLE_VISITOR_BADGE","config_app_enable_visitor_badge");echo("<br>");
createHTMLINP("BADGE_W", "config_app_badge_w");
createHTMLINP("BADGE_H", "config_app_badge_h");
echo("<br>");
createHTMLDD("MOTD_MODE", array("off","file","url"),"motd_mode", true, false);
?><span id="motd_val" style="display:none"><?php
createHTMLINP("MOTD_VAL", "motd_val");
echo("</span><br>");
createHTMLCB("NOTIFY_CHECKIN_EMAIL","config_app_notify_email");
createHTMLINP("NOTIFY_CHECKIN_EMAIL_ENDPOINT", "config_app_notify_email_url");echo("<br>");
createHTMLCB("NOTIFY_CHECKIN_MOBILE","config_app_notify_mobile");
createHTMLINP("NOTIFY_CHECKIN_MOBILE_ENDPOINT", "config_app_notify_mobile_url");echo("<br>");
createHTMLCB("NOTIFY_CHECKIN_ENDPOINT_INSECURE","config_app_notify_insecure");
echo("<br>");
createHTMLINP("NOTIFY_CHECKIN_ENDPOINT_SECRET", "config_app_notify_secret");echo("<br>");
echo("<br>");

?>
<br>
<h2>
  <?= translation_get('config_title_security'); ?>
</h2>
<?php
  createHTMLCB("HTTP_AUTO_REDIRECT","config_http_auto_redir");
  createHTMLCB("COOKIE_SECURE_FLAG","config_cookie_secure");
?>
<br>
<h2>
  <?= translation_get('config_title_login'); ?>
</h2>
<?php
  createHTMLINP("COOKIE_LIFETIME_HOURS", "config_login_cookie_lifetime_hours", true);
  echo("<br>");
echo("<ul><li>".translation_get("login_mode_local")."</li><li>".translation_get("login_mode_ldap")."</li><li>".translation_get("login_mode_proxy")."</li><li>".translation_get("login_mode_auto")."</li></ul>");
  createHTMLDD("LOGIN_MODE", array("local","ldap","proxy","auto")
    ,"login_mode", true, false);
  echo("<br>");
  ?><span id="ldap" style="display:none"><?php
    createHTMLINP("LOGIN_LDAP_HOSTNAME", "config_login_ldap_hostname", false);
    createHTMLINP("LOGIN_LDAP_PORT", "config_login_ldap_port", true);echo("<br>");
    createHTMLCB ("LOGIN_LDAP_SECURE", "config_login_ldap_secure"); echo("<br>");
    createHTMLINP("LOGIN_LDAP_BIND_DN", "config_login_ldap_bind_dn", false);
    createHTMLINP("LOGIN_LDAP_TIMEOUT", "config_login_ldap_timeout", true);echo("<br>");
    createHTMLCB ("LOGIN_LDAP_DEBUG", "config_login_ldap_debug");echo("<br>");
 ?></span><span id="proxy" style="display:none"><?php
    createHTMLINP("LOGIN_PROXY_HEADER", "config_login_proxy_header");echo("<br>");
    createHTMLCB ("LOGIN_AUTO_REGISTER", "config_login_auto_register");echo("<br>");
 ?></span><span id="auto" style="display:none"><?php
    createHTMLINP("LOGIN_AUTO_NAME", "config_login_auto");echo("<br>");
    createHTMLCB ("LOGIN_AUTO_REGISTER", "config_login_auto_register");echo("<br>");
 echo("</span>");
?>
<script>
  showLoginSettings("<?= LOGIN_MODE ?>");
  function showLoginSettings(mode){
    document.getElementById("proxy").style.display = "none";
    document.getElementById("auto").style.display = "none";
    document.getElementById("ldap").style.display = "none";

    if(mode==="proxy" || mode==="auto" || mode==="ldap"){
      document.getElementById(mode).style.display = "block";
    }
  }
  showMOTDSettings("<?= MOTD_MODE ?>");
  function showMOTDSettings(mode){
    //console.log(mode);
    document.getElementById("motd_val").style.display = "none";
    if(mode==="url" || mode==="file"){
      document.getElementById("motd_val").style.display = "block";
    }
  }

</script>
<br><br>

<h2>
  <?= translation_get('password_policy'); ?>
</h2>
<?php

  createHTMLINP("PW_POLICY_MIN_LENGTH", "password_policy_min_length", true);
  createHTMLINP("PW_POLICY_MIN_CAPITAL", "password_policy_min_capital", true);
  createHTMLINP("PW_POLICY_MIN_NUMBERS", "password_policy_min_number", true);
  createHTMLINP("PW_POLICY_MIN_SPECIAL", "password_policy_min_special", true)
?>
<br>

<h2>
  <?= translation_get('config_title_error_handling'); ?>
</h2>

<?php
  createHTMLCB("DB_SHOW_ERRORS","config_show_db_errors");

  createHTMLCB("PHP_SHOW_ERRORS","config_show_php_errors");
?>
<br><br>
<h2>
  <?= translation_get('config_title_logging'); ?>
</h2>
<?php
  createHTMLDD("LOG_ERROR_LEVEL", array("LOG_PERROR","LOG_ERR","LOG_ERROR") ,"config_log_level", false, true);
?>
<br><br>

<h2>
  <?= translation_get('config_title_branding'); ?>
</h2>
<?php
  createHTMLCP("BRANDING_COLOR", "config_branding_color");
  createHTMLCP("BRANDING_WARNING_COLOR", "config_warning_color");
  createHTMLCP("MARK_ITEM_COLOR", "config_mark_item_color");
  createHTMLCP("ALTERNATE_ROW_COLOR", "config_alt_row_color");
  createHTMLIMG("logo.png");
  createHTMLIMG("logo_navbar.png");
  createHTMLIMG("apple-touch-icon.png");
  createHTMLIMG("android-chrome-192x192.png");
  createHTMLIMG("android-chrome-512x512.png");
  createHTMLIMG("favicon.ico");
  createHTMLIMG("favicon-16x16.png");
  createHTMLIMG("favicon-32x32.png");
?>
<br><br>
<h2>
  <?= translation_get('config_title_language_region'); ?>
</h2>
<?php

  $translations = scandir("includes/translations");
  array_splice($translations, 0, 2); // . and ..

  createHTMLDD("LANGUAGE", $translations ,"config_language", true, false);


  createHTMLDD("TIMEZONE", timezone_identifiers_list(), "config_timezone");


?>
<div style="visibility: hidden" id="spinner" class="loader centered"></div>

<?php

    function createHTMLIMG($name){
        $path = "img/".$name;
        $size=getimagesize($path);?>
        <br><b><?= $name ?></b> - <?= $size[0]."px w ".$size[1]."px h" ?><br><img style="max-width:100px; padding-top:5px; max-height:100px" src="img/<?= $name ?>"><br>
        <form action="calls/configurationajx.php" method="post" enctype="multipart/form-data">
            Select image to upload:
            <?php addCSRFField(); ?>
            <input type="hidden" name="img" id="img" value="<?= $name ?>">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" onclick="toggleSpinner()" value="<?= translation_get('config_upload'); ?>" name="submit">
        </form>
    <?php }

  function createHTMLCP($id, $translation){ ?>
    <div class="form-check">
      <input type="color" id="<?= $id ?>" value="<?= constant($id) ?>">
      <label class="form-check-label" for="<?= $id ?>">
        <?= translation_get($translation); ?>
      </label>
    </div>
  <?php
    createJSListener($id,"value",true);
  }

  function createHTMLINP($id, $translation, $number=false){ ?>
    <div class="form-group">
        <label class="form-check-label" for="<?= $id ?>">
            <?= translation_get($translation); ?>
        </label>
        <input class="form-control" type="<?= $number?"number":"input" ?>" value="<?= constant($id)?>" id="<?= $id ?>">
        <button class="btn" style="background: <?= BRANDING_COLOR ?>" id="<?= $id ?>-btn" type="submit"><?= translation_get('submit') ?></button>
    </div>
  <?php
    createJSListener($id,"btn",!$number);
  }

  function createHTMLCB($id, $translation){ ?>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="<?= $id ?>" <?= constant($id) ?"checked":""?>>
      <label class="form-check-label" for="<?= $id ?>">
        <?= translation_get($translation); ?>
      </label>
    </div>
  <?php
    createJSListener($id,"checked",false);
  }

  function createHTMLDD($id, $values, $translation, $quotes=true, $checkdefined=false){ ?>
    <div class="form-group">
        <label for="<?= $id ?>">
            <?= translation_get($translation); ?>
        </label>
        <select class="form-control" id="<?= $id ?>">
        <?php
        if($checkdefined){
                foreach ($values as &$value) {
                if(!defined($value)){
                    $values=array_diff( $values, array($value) ) ;
                }
            }
        }
        foreach ($values as &$value) {   ?>
            <option <?= constant($id)==$value?"selected":""?>>
                <?= $value ?>
            </option>
        <?php } ?>
        </select>
    </div>
  <?php
    createJSListener($id,"value",$quotes);
  }

  ?><script>
          function toggleSpinner(){
            var spinner = document.getElementById("spinner");
            spinner.style.visibility = spinner.style.visibility == "hidden" ? "visible" : "hidden";
          }

        function setAllDisabled(mode) {
            $('input').attr('disabled',mode);
            $('select').attr('disabled',mode);
        }

  </script>

  <?php

  function createJSListener($id, $access, $paranthesis){?>
        <script>
            $listener = "change";
            var select = document.getElementById("<?= $id ?>");
            if("<?= $access ?>"==="btn"){
                select = document.getElementById("<?= $id ?>-btn");
                $listener = "click";
                <?php if($access === "btn"){
                        $access="value";
                } ?>
            }
            select.addEventListener($listener, function() {
                var select = document.getElementById("<?= $id ?>");
                toggleSpinner();
                setAllDisabled(true);
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(e) {
                    if (xhttp.readyState === 4) {
                        setTimeout(function() {
                            var select = document.getElementById("<?= $id ?>");
                            select.disabled=false;
                            setAllDisabled(false);
                            toggleSpinner();
			    if(select.id==="LOGIN_MODE"){
                                 showLoginSettings(select.value);
                            }
			    if(select.id==="MOTD_MODE"){
                                 showMOTDSettings(select.value);
                            }
                            if(xhttp.status!==200){
                                if(xhttp.status==500){
                                    alert("Error changing setting - could not write into includes/config.php");
                                }else{
                                    alert("Error changing setting - could not find or update setting");
                                }
                            }
                        }, 2000);
                    }
                }
              xhttp.open("POST", "calls/configurationajx.php", true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              xhttp.send("id=<?= $id ?>&val="+select.<?= $access?>+"&parathesis=<?= $paranthesis?"true":"false" ?>&CSRF="+encodeURIComponent("<?= getCSRF() ?>"));
            });
        </script>
  <?php }
?>
<br><br>