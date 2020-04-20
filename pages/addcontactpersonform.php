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
<h4><?= translation_get('contactperson_add'); ?></h4>
<form action="index.php?p=addcontactperson" method="POST">
	<?php addCSRFField() ?>
	<div class="form-group">
	  <input class="form-control" maxlength="250" type="text" name="name" placeholder="<?= translation_get('contactperson_name'); ?>" required>
	</div>
	<button class="btn" style="background:<?= BRANDING_COLOR ?>"><?= translation_get('contactperson_add_submit'); ?></button>
</form>