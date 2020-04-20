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
<form action="index.php?p=changemobile" method="POST">
	<?php addCSRFField(); ?>
	<div class="form-group">
		<input class="form-control" type="hidden" name="uid" id="uid" value="<?= isset($_GET['id'])?intval($_GET['id']):"" ?>">
		<?= translation_get('mobile'); ?> (z.B. +41 79 123 45 67): <input class="form-control" type="tel" name="mobile" id="mobile">
		</div>
	<button class="btn" style="background:<?= BRANDING_COLOR ?>"><?= translation_get('mobile_change'); ?></button>
</form>
