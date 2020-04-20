<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	$mode = LOGIN_MODE === "ldap" ? "LDAP Login" : "Login";
?>
<div class="row justify-content-md-center" style="padding-top: 10vh">
  <div class="col col-lg-4">
    <center><h4><?= $mode ?></h4></center><br>
    <form action="calls/login.php" method="POST">
      <?php addCSRFField() ?>
      <div class="form-group">
        <input class="form-control" maxlength="250" type="text" name="username" placeholder="<?= translation_get('username'); ?>">
      </div>
      <div class="form-group">
        <input class="form-control" maxlength="250" type="password" name="password" placeholder="<?= translation_get('password'); ?>"><br>
      </div>
      <input class="btn form-control" style="background: <?= BRANDING_COLOR ?>" type="submit" value="<?= translation_get('login'); ?>">
    </form>
  </div>
</div>
