<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();

	if(isset($_GET['cancel'])){
		if($_GET["cancel"]==="input"){
			setInputStatus(false);
			header('Location: index.php');
			die();
		}
		if($_GET["cancel"]==="checkin" && isset($_POST["id"])){
			setInputStatus(false);
			deleteCheckinVisitor(intval($_POST["id"]));
			header('Location: index.php');
			die();
		}
	}

	$checkinvisitor=getCheckinVisitor();
	$disabled = false;
	if(empty($checkinvisitor)){
		setInputStatus(true);
		doPoll();
		$disabled=true;
	}
	$checkinvisitor=$checkinvisitor[0];
	?>
				<style>
					.custom-control-input:checked~.custom-control-indicator{
							background-color: <?= BRANDING_COLOR ?>;
					}
				</style>
				<form id="form" action="index.php?p=checkinvisitor" method="POST">
				    <div class="row">
				      <div class="col-sm">
                                        <?php addCSRFField();
					if($disabled){
					  echo("<span style=\"color:".BRANDING_COLOR."\"><b>".translation_get('visitor_waiting_for_input')."</b></span><br><br>");
					}else{ ?>
					<b><?= translation_get('menu_checkin') ?> - <?= $checkinvisitor["checkinvisitor_date"] ?> - <?= $checkinvisitor["checkinvisitor_start"] ?></b><br><br>
					<?php } ?>
					<div class="form-group">
					  <b><?= translation_get('visitor_name') ?></b> <input <?= $disabled?"disabled":""; ?> type="text" maxlength="250" class="form-control" id="name" name="name" value="<?= $checkinvisitor["checkinvisitor_name"] ?>" required>
					</div>
					<div class="form-group">
					  <b><?= translation_get('visitor_surname') ?></b> <input <?= $disabled?"disabled":""; ?> type="text" maxlength="250" class="form-control" id="surname" name="surname" value="<?= $checkinvisitor["checkinvisitor_surname"] ?>" required>
					</div>
					<div class="form-group">
					  <b><?= translation_get('visitor_company') ?></b> <input <?= $disabled?"disabled":""; ?> type="text" maxlength="250" class="form-control" id="company" name="company" value="<?= $checkinvisitor["checkinvisitor_company"] ?>" required>
					</div>
					<div class="form-group">
					  <b><?= translation_get('visitor_contactperson') ?></b> <input <?= $disabled?"disabled":""; ?> type="text" maxlength="250" class="form-control" id="contactperson" name="contactperson" value="<?= $checkinvisitor["checkinvisitor_contactperson"] ?>" required>
					</div>
					<?php
					  if(ENABLE_VISITOR_IDENTIFIER){
					    $identifiers = getIdentifiersList();
					?>
					  <div class="form-group">
					    <b><?= translation_get('visitor_identifier') ?></b><br>
					    <select class="form-control" id="identifier" name="identifier">
					      <option value="none">-</option>
					      <?php foreach($identifiers as $identifier){
						echo("<option value=\"".$identifier["identifier_id"]."\">".$identifier["identifier_name"]."</option>");
					      }?>
					    </select>
					  </div>
					<?php
					  }
					?>
				      </div>
				      <div class="col-sm">
					<div class="form-group">
				   	  <div style="border:1px solid black; width: <?= SIGNATURE_WIDTH ?>px; height: <?= SIGNATURE_HEIGHT ?>px;">
					    <img src="<?= $checkinvisitor['checkinvisitor_signature'] ?>" />
					  </div>
					</div>
					<input type="hidden" id="id" name="id" value="<?= $checkinvisitor["checkinvisitor_id"] ?>">
					<input type="hidden" id="start" name="start" value="<?= $checkinvisitor["checkinvisitor_start"] ?>">
					<input type="hidden" id="date" name="date" value="<?= $checkinvisitor["checkinvisitor_date"] ?>">
					<input type="hidden" id="signature" name="signature" value="<?= $checkinvisitor["checkinvisitor_signature"] ?>">
					<?php if(ENABLE_VISITOR_ID_SHOWN){ ?>
					<div class="form-check">
					  <label class="custom-control custom-checkbox">
					    <input <?= $disabled?"disabled":""; ?> type="checkbox" class="custom-control-input" id="idshown" name="idshown" value="false">
					      <span class="custom-control-indicator"></span>
					      <span class="custom-control-description"><b><?= translation_get('visitor_id_checked') ?></b></span>
					    </label>
					</div>
					<?php } ?>
				    </div>
				  </div>
				</form>
				<br>
                                <table style="float: left">
				  <tr><td>
                                    <form onsubmit="return confirm('<?= translation_get('checkin_delete_sure') ?>')" action="index.php?p=checkinvisitorform&cancel=checkin" method="POST">
                                       <?php addCSRFField() ?>
					<input  type="hidden" id="id" name="id" value="<?= $checkinvisitor["checkinvisitor_id"] ?>">
                                       <button <?= $disabled?"disabled":""; ?>  class="btn" style="background: <?= BRANDING_WARNING_COLOR ?>"><?= translation_get('checkin_delete') ?></button>
                                     </form>
				  </td><td style="padding-left:20px">
      		                    <button <?= $disabled?"disabled":""; ?> onclick="this.disabled=true;document.getElementById('form').submit();" class="btn" style="background:<?= BRANDING_COLOR ?>"><?= translation_get('visitor_ok') ?></button>
				  </td></tr></table><?php
	//if($disabled){ doCancel(); }
	function doCancel(){?>
		<form action="index.php?p=checkinvisitorform&cancel=input" method="POST">
			<?php addCSRFField() ?>
			<button class="btn" style="background: <?= BRANDING_WARNING_COLOR ?>"><?= translation_get('cancel') ?></button>
		</form>
	<?php }

	function doPoll(){ ?>
		<script>
			(function poll() {
				var request = new XMLHttpRequest();
				request.open("GET","calls/visitorcheckinstatus.php");
				request.addEventListener('load', function(event) {
					if (request.status == 200) {
						try {
							var jsonResponse = JSON.parse(request.responseText);
							var visitorcheckinstatus = jsonResponse.visitor_checkedin === true;
							if(visitorcheckinstatus){
								location.reload();
							}
						} catch(err) {
							console.warn(err.message);
						}
					} else {
						console.warn(request.statusText, request.responseText);
					}
					setTimeout(poll, 1500);
				});
				request.send();
			}());
		</script> <?php
	}
?>
<br>
<br>
</div>
