<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();

	if(isset($_GET['visitorid'])){
		$visitor = getSpecificVisitor($_GET['visitorid']);
?>
	<h2><?= translation_get('create_badge'); ?></h2>
	<div id="badge" style="border: 1px solid black; width: <?= BADGE_W ?>px; height: <?= BADGE_H ?>px;">
		<?php include("badgetemplate.php"); ?>
	</div>
	<br>
	<button class="btn" style="background: <?= BRANDING_COLOR ?>" onclick="printBadge();"><?= translation_get('print_badge'); ?></button>
	<script type="text/javascript">
	new QRCode(document.getElementById("qrcode"), "<?= $visitor['visitor_name'] ?>|<?= $visitor['visitor_surname'] ?>|<?= $visitor['visitor_company']?>");

	function printBadge(){
		var mywindow = window.open('', 'PRINT', 'height=<?= BADGE_H ?>,width=<?= BADGE_W ?>');
		mywindow.document.write('<html><head><title>' + document.title  + '</title>');
		mywindow.document.write('</head><body><style>body {  font-family: Helvetica, Arial, Sans-Serif; }</style>');
		mywindow.document.write(document.getElementById("badge").innerHTML);
		mywindow.document.write('</body></html>');
		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/
		mywindow.print();
		mywindow.close();
		return true;
	}
	</script>
<?php
	}
?>