<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();

	if(!isset($_GET["identifier"])){
		header('Location: index.php?msg=missing_params');
		die();
	}

	$id = $_GET["identifier"];
	$identifier = getIdentifierById($id);
	if(empty($identifier)){
	        header('Location: index.php?msg=missing_params');
        	die();
	}else{
		$identifierHistory = getIdentifierHistory($id);
		$identifierName = $identifier[0]['identifier_name'];
		$identifierDescription = $identifier[0]['identifier_description'];
		echo("<b>".$identifierName."</b> ".$identifierDescription."<br>");
		?><br>
		<div class="table-responsive">
		<table class="table">
			<tr>
				<th><?= translation_get('date'); ?></th>
				<th><?= translation_get('visitor_name'); ?></th>
				<th><?= translation_get('visitor_surname'); ?></th>
				<th><?= translation_get('visitor_company'); ?></th>
			</tr>
			<?php foreach($identifierHistory as $history){ ?>
			<tr>
				<td>
					<?= $history['visitorlist_date'] ?> (<?= $history['visitor_start']?>-<?= $history['visitor_end'] ?>)
				</td>
				<td>
					<?= $history['visitor_name'] ?>
				</td>
				<td>
					<?= $history['visitor_surname'] ?>
				</td>
				<td>
					<?= $history['visitor_company'] ?>
				</td>
			</tr><?php } ?>
		</table>
		</div>
	<?php }
?>