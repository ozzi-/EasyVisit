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
?>
<h4><?= translation_get('fde_add'); ?></h4>
<b><?= translation_get('password_policy'); ?></b><br>
<ul>
	<li><?= translation_get('password_policy_min_length');  ?>: <?= PW_POLICY_MIN_LENGTH ?></li>
	<li><?= translation_get('password_policy_min_number'); ?>: <?= PW_POLICY_MIN_NUMBERS ?></li>
	<li><?= translation_get('password_policy_min_capital'); ?>: <?= PW_POLICY_MIN_CAPITAL ?></li>
	<li><?= translation_get('password_policy_min_special'); ?>: <?= PW_POLICY_MIN_SPECIAL ?></li>
</ul>
<form action="index.php?p=addfrontdeskemployee" method="POST">
	<?php addCSRFField() ?>
	<div class="form-group">
		<?= translation_get('username'); ?>: <input class="form-control" maxlength="250" type="text" name="username" required>
	</div>
	<div class="form-group">
		<?= translation_get('password'); ?>: <input class="form-control" maxlength="250" type="password" name="password" id="password" onchange="validatePassword()" required>
	</div>
	<div class="form-group">
		<?= translation_get('password_repeat'); ?>: <input class="form-control" maxlength="250" type="password" name="passwordr" id="passwordr" onkeyup="validatePassword()" required>
	</div>
	<button class="btn" style="background:<?= BRANDING_COLOR ?>" onclick="validatePolicy()"><?= translation_get('fde_add_submit'); ?></button>
</form>
