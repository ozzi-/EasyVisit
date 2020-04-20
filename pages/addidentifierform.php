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
<h4><?= translation_get('identifier_add'); ?></h4>
<form action="index.php?p=addidentifier" method="POST">
	<?php addCSRFField() ?>
	<div class="form-group">
	  <input class="form-control" maxlength="250" type="text" name="name" placeholder="<?= translation_get('identifier_name'); ?>" required>
	</div>
	<div class="form-group">
	  <input class="form-control" maxlength="250" type="text" name="description" placeholder="<?= translation_get('identifier_description'); ?>" required>
	</div>
	<button class="btn" style="background:<?= BRANDING_COLOR ?>"><?= translation_get('reoccuring_add_submit'); ?></button>
</form>
