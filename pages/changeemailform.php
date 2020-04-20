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
<form action="index.php?p=changeemail" method="POST">
	<?php addCSRFField(); ?>
	<div class="form-group">
		<input class="form-control" type="hidden" name="uid" id="uid" value="<?= isset($_GET['id'])?intval($_GET['id']):"" ?>">
		<?= translation_get('email'); ?>: <input class="form-control" type="email" name="email" id="email">
		</div>
	<button class="btn" style="background:<?= BRANDING_COLOR ?>" onclick="validatePolicy()"><?= translation_get('email_change'); ?></button>
</form>
