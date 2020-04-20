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

<h3><?= translation_get('device_register'); ?></h3>
<form action="index.php?p=registerdevice" method="POST">
	<?php addCSRFField() ?>
	<div class="form-group">
		<input class="form-control" maxlength="250" type="text" name="name" placeholder="<?= translation_get('device_name');?>" required><br>
		<input class="form-control" maxlength="250" type="password"  name="secret" placeholder="<?= translation_get('device_secret'); ?>" required><br>
		<button class="btn" style="background: <?= BRANDING_COLOR ?>" onclick="saveSecret();" type="submit"><?= translation_get('submit'); ?></button>
	</div>
</form>