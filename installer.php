<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

  error_reporting(E_ALL);
  ini_set('display_errors', 1);
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>EasyVisit</title>
    <link href="css/custom.php" rel="stylesheet">
    <link href="css/open-iconic-bootstrap.css" rel="stylesheet">
  </head>
  <body>
    <style>
      html{
        padding:40px;
      }
    </style>
	<h2>EasyVisit Installation</h2><br>
<?php
  if(!isset($_GET["step"])){
    $step=1;
  }else{
    $step=intval($_GET["step"]);
  }

  if($step===1){
    echo("<b>Please read the <a href=\"EULA_READ_ME.txt\">End User License Agreement (EULA)</a> before continuing.</b><br><br>");
    echo("<h3>Step 1 - Checking PHP extensions & required directories</h3>");
    $extensionsOk=true;
    $packageCommand = "sudo apt-get install ";
    if (!extension_loaded('mbstring')){
      echo("Missing mbstring PHP extension.<br><br>");
      $packageCommand .= "php-mbstring ";
      $extensionsOk=false;
    }
    if (!extension_loaded('dom')){
      echo("Missing dom PHP extension.<br><br>");
      $packageCommand .= "php-xml ";
      $extensionsOk=false;
    }
    if (!extension_loaded('gd')){
      echo("Missing gd PHP extension.<br><br>");
      $packageCommand .= "php-gd ";
      $extensionsOk=false;
    }
    if (!extension_loaded('ldap')){
      echo("Missing ldap PHP extension.<br><br>");
      $packageCommand .= "php-ldap ";
      $extensionsOk=false;
    }
    if (!extension_loaded('mysqli')){
      echo("Missing mysqli PHP extension.<br><br>");
      $packageCommand .= "php-mysqli";
      $extensionsOk=false;
    }
    if (!extension_loaded('curl')){
      echo("Missing curl PHP extension.<br><br>");
      $packageCommand .= "php-curl";
      $extensionsOk=false;
    }
    if (!extension_loaded('zip')){
      echo("Missing zip PHP extension.<br><br>");
      $packageCommand .= "php-zip";
      $extensionsOk=false;
    }

   if(!is_writable("index.php") || !is_writeable("includes/config.php")) {
     echo("Cannot write to current directory as user!<br>Make sure that the directory is read and writeable.<br><br>");
     die();
   }

   if(!is_writable(session_save_path())){
     echo("Session directory ".session_save_path()." is not writable! Please fix the user permissions");
     die();
   }

    if($extensionsOk){
      echo("&#10003; All PHP extensions are installed<br><br><form method=\"POST\" action=\"?step=2\"><input type=\"Submit\" value=\"Continue\" /></form>");
    }else{
      echo("<b>Can not continue with installation.</b><br>");
      echo("Please install the required PHP extensions and then run this installer again.<br>");
      echo("Installation using apt: <i>".$packageCommand."</i></html>");
      die();
    }
  }

  if($step===2){
    echo("<h3>Step 2 - MySQL DB Setup and Backups</h3><br>");
    echo("Enter your MySQL user with sufficient privileges in order to create and initialize the database and the connection details.<br>");
    ?>
      <form method="POST" action="?step=3">
      <br>
      <input type="text" name="username" id="username" placeholder="Username" required><br>
      <br><input type="password" name="password" id="password" placeholder="Password" required><br>
      <br><input type="text" name="serverip" id="serverip" placeholder="Server IP" required>
      <br><i>If you are using a custom port, append :port to the input field.</i><br><br>
      Enter password for MySQL user 'easyvisit'<br>
      <input type="password" name="passwordusr" placeholder="Password" id="passwordusr" required><br><br>
      <input type="password" name="passwordusrc" placeholder="Repeat Password" id="passwordusrc" required><br>
      <br>
      Enter the path to where you wish to save EasyVisit backups.<br>
      This directory shall be read and writeable to the user your webserver is running as and <b>not</b> inside of the www folder.<br>
      <input type="text" name="backuppath" placeholder="/easyvisitbackup" id="backuppath" required><br>
      <br>
      <input type="Submit" value="Continue" />
     </form>
    <?php
  }

  if($step===3){
    echo("<h3>Step 3 - Creating DB & Backup Path</h3>");
    if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["serverip"]) && isset($_POST["passwordusr"]) && isset($_POST["passwordusrc"]) && isset($_POST["backuppath"])){
     $backuppath  = rtrim($_POST["backuppath"], '/') . '/';
      if(!is_writable($backuppath)) {
        echo("Cannot write into specified backup path.");
        closeHTML();
        die();
      }
      $handle =  mysqli_connect($_POST["serverip"], $_POST["username"], $_POST["password"]);
      if (mysqli_connect_errno()){
        echo("Error connecting to MySQL server: ".mysqli_connect_error()."<br>");
        echo("<br><input type=\"button\" value=\"Back\" onclick=\"window.history.back()\" /> ");
	closeHTML();
        die();
      }
      echo("&#10003; Connected to DB<br><br>Starting import<br><br>");
      if($_POST['passwordusr']===$_POST['passwordusrc']){
        if(strlen($_POST['passwordusr'])<8){
          echo("<b>Suggestion</b> Please use passwords with the <b>minimum</b> length of 8 characters.<br><br>");
        }
    $res=mysqli_query($handle,"CREATE USER IF NOT EXISTS 'easyvisit'@'localhost' IDENTIFIED BY '".mysqli_real_escape_string($handle,$_POST["passwordusr"])."';");
        if(!$res){ die("<br><b>Error creating MySQL user:</b> ".mysqli_error($handle)."</html>"); }
        $res=mysqli_query($handle,"GRANT ALL PRIVILEGES ON easyvisit.* TO 'easyvisit'@'localhost';");
    echo(mysqli_error($handle));

        if(!$res){ die("<br><b>Error granting pricileges</b></html>"); }
        $res=mysqli_query($handle,"FLUSH PRIVILEGES;");
      }else{
        echo("Passwords do not match.<br><input type=\"button\" value=\"Back\" onclick=\"window.history.back()\" /> ");
        die("<br><b>Aborting.</b></html>");
      }

      if(file_exists("db.sql")){
        $templine = '';
        $lines = file("db.sql");
        foreach ($lines as $line){
          if (substr($line, 0, 2) == '--' || $line == '')
            continue;
          $templine .= $line;
          if (substr(trim($line), -1, 1) == ';'){
            $res=mysqli_query($handle,$templine);
            if(!$res){
				print("Error performing query '". $templine ."':<br><b>". mysqli_error($handle) . "</b><br /><br />");
				die("<br><b>Aborting.</b></html>");
            }
            $templine = '';
          }
        }
        echo("&#10003; Successfully imported DB and created user<br><br>");
        $str=file_get_contents("includes/config.php");
        $strarr = explode("\n", $str);
        foreach ($strarr as &$val) {
          $val=preg_replace("/^.*define.*[(][\"]DB_HOST.*/m",'  define ("DB_HOST","'.$_POST["serverip"].'");', $val);
          $val=preg_replace("/^.*define.*[(][\"]DB_PASS.*/m",'  define ("DB_PASS","'.$_POST["passwordusr"].'");', $val);
	  $val=preg_replace("/^.*define.*[(][\"]BACKUP_PATH.*/m",'  define ("BACKUP_PATH","'.$backuppath.'");', $val);
        }
        $str = implode("\n",$strarr);
        file_put_contents("includes/config.php", $str);
        echo("&#10003; Successfully set MySQL DB in includes/config.php<br><br><form method=\"POST\" action=\"?step=4\"><input type=\"Submit\" value=\"Continue\" /></form>");
      }else{
        die("<br><b>Cannot find file 'db.sql'. Aborting</b></html>");
      }
    }else{
      echo("Empty form transmitted<br><input type=\"button\" value=\"Back\" onclick=\"window.history.back()\" /> ");
    }
  }

  if($step===4){
    ?>Congratulations, the basic installation has completed successfully.<br>You can now login with the created admin user with the password <b>changeme</b>.
    <span style=\"color: red\"><br>You should change the password as soon as possible.</span><br><br>
    Further configuration can be performed, when loggedin as admin, under <a href="index.php?p=configuration">Configuration</a>.<br>
    This includes settings such as language, password policy, setting up LDAP logins and branding.<br>
    <br><br><b>For security reasons, all installation files will be deleted automatically.</b><br><?php
          $ok = true;
          if(!@unlink("installer.php")){
                  $ok = false;
                  echo("<span style=\"color: red\">Error: Could not remove installer.php (permission denied). Please do so manually</span><br>");
          }
          if(!@unlink("db.sql")){
                  $ok = false;
                  echo("<span style=\"color: red\">Error: Could not remove sql.db (permission denied). Please do so manually</span>");
          }
          if($ok){?>
                <br><br><a href="index.php"><b>Continue to the EasyVisit App</b></a><?php
          }
  }
  closeHTML();

  function closeHTML(){?>
    </body>
    </html>
  <?php }
?>
