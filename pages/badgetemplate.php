<?php 
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
?>
<div style="padding: 15px;">
	<img src="img/logo_navbar.png">&nbsp;
	<b><?= translation_get('visitor'); ?></b> <?= $visitor['visitor_name'] ?> <?= $visitor['visitor_surname'] ?> (<?= $visitor['visitor_company'] ?>)
	<br><br>
	<span id="qrcode" style="float: right;"></span>
</div>
