<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();
?>
<h4><?= translation_get('reoccuring_add'); ?></h4>
<form action="index.php?p=addreoccuring" method="POST">
	<?php addCSRFField() ?>
	<div class="form-group">
	  <input class="form-control" maxlength="20" type="text" name="code" placeholder="<?= translation_get('reoccuring_code'); ?>" required>
	</div>
	<div class="form-group">
	  <input class="form-control" maxlength="250" type="text" name="name" placeholder="<?= translation_get('reoccuring_name'); ?>" required>
	</div>
	<div class="form-group">
	  <input class="form-control" maxlength="250" type="text" name="surname" placeholder="<?= translation_get('reoccuring_surname'); ?>" required>
	</div>
	<div class="form-group">
	  <input class="form-control" maxlength="250" type="text" name="company" placeholder="<?= translation_get('reoccuring_company'); ?>" required>
	</div>
	<div class="form-group">
          <input class="form-control" maxlength="250" type="text" name="contactperson" placeholder="<?= translation_get('reoccuring_contactperson'); ?>" required>
	</div>
	<button class="btn" style="background:<?= BRANDING_COLOR ?>"><?= translation_get('reoccuring_add_submit'); ?></button>
</form>