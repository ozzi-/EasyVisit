<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

  require_once './api.php';
  defined('APP_RAN') or die();
  $contactpersons = getContactPersons();
?>
<style>
	.form-group{
		margin-bottom: .4rem !important;
	}
	.box-shadow--3dp {
		box-shadow: 0 3px 4px 0 rgba(0, 0, 0, .14), 0 3px 3px -2px rgba(0, 0, 0, .2), 0 1px 8px 0 rgba(0, 0, 0, .12)
	}
</style>
<div class="header">
	<div class="content" style="padding-bottom:5px; padding-top:5px;">
		<table>
			<tr>
			<td> <img src="img/logo_navbar.png" width="35" height="35" alt=""> </td>
			<td> <span style="font-size:180%">&nbsp;&nbsp;&nbsp;<?= translation_get('welcome'); ?> <?= COMPANY_NAME ?></span> </td>
			</tr>
		</table>
	</div>
</div>
<div style="padding-top:55px;" class="content">
	<div class="">
		<div class="row">
			<div class="col-6" id="formd">
				<form id="form" action="tablet.php?p=selfcheckinvisitor" onsubmit="return checkSignature();" method="POST">
					<?php addCSRFField() ?>
					<input type="hidden" id="device_name" name="device_name">
					<input type="hidden" id="secret" name="secret">
					<div class="form-group" id="step1_1">
						<label for="name"><?= translation_get('visitor_name'); ?></label>
						<input class="form-control" maxlength="120" type="text" id="name" name="name" required autocomplete="off">
					</div>
					<div class="form-group" id="step1_2">
						<label for="name"><?= translation_get('visitor_surname'); ?></label>
						<input class="form-control" maxlength="120" type="text" id="surname" name="surname" required autocomplete="off">
					</div>
					<div class="form-group" id="step1_3" style="display:none">
						<label for="company"><?= translation_get('visitor_company'); ?></label>
						<input class="form-control" maxlength="120" type="text" id="company" name="company" required autocomplete="off" value="-">
					</div>
					<div class="form-group" id="step1_4" style="display:none">
						<label for="contactperson"><?= translation_get('visitor_contactperson'); ?></label><br>
						<select onchange="contactpersonselected()" class="form-control"
							name="contactpersondd" id="contactpersondd" <?php if(sizeof($contactpersons)==0){ echo "style=\"display: none\""; } ?>>
							<option selected value="-"></option>
						</select>
						<input class="form-control" autocomplete="off" maxlength="120" type="text" id="contactperson" name="contactperson" required autocomplete="off">
					</div>
					<br>
					<div id="step2_4" style="display: none;">
						<b><?= translation_get("visitor_signature"); ?></b>
					<br></div>
					<span id="reoccuring_confirmation"></span>

					<div id="step2_1" style="  border-radius: 7px; border:1px solid black; margin-top:+10px; display: none; width: <?= SIGNATURE_WIDTH ?>px; height: <?= SIGNATURE_HEIGHT ?>px;">
						<canvas id="signature-pad" class="signature-pad" width="<?= SIGNATURE_WIDTH ?>px" height="<?= SIGNATURE_HEIGHT ?>px"></canvas>
					</div>
					<input type="hidden" id="signature" name="signature">
					<br>
					<button id="step2_3" class="btn" style="margin-right: 25px; display: none; background: <?= BRANDING_WARNING_COLOR ?>;" onclick="return false;" ><?= translation_get('clear_signature'); ?> <span class="oi oi-delete"></span>
					</button>
					<button id="step2_2" class="btn" style="display: none; background: <?= BRANDING_COLOR ?>" type="submit"><?= translation_get('submit'); ?></button>
				</form>
			</div>
	                <div class="col" id="formnext" style="padding-left:5vw;">
				<div class="d-flex flex-row align-items-end my-flex-container" style="height:300px;">
					<div class="p-2 my-flex-item">
						<br><b>Check-In</b><br>
			        	        <button id="step1_5" class="btn box-shadow--3dp" style="background: <?= BRANDING_COLOR ?>" onclick="next();"><?= translation_get("continue"); ?></button>
					</div>
				</div>
			</div>
			<div class="col" id="reoccuring_step" style="padding-left:5vw;">
				<br>
				<?= translation_get('reoccuring_visitor'); ?><br>
				<!-- <button id="reoccuring_btn" class="btn" onclick="reoccuring();" style="background: <?= BRANDING_WARNING_COLOR ?>;" onclick="return false;" ><?= translation_get('reoccuring_visitor_btn'); ?></button> -->
				<input class="form-control" maxlength="120" type="text" oninput="reoccuringCodeOnInputHandler()" placeholder="<?= translation_get('reoccuring_visitor_placeholder'); ?>" id="reoccuring_code" name="reoccuring_code">
			</div>
		</div>
	</div>
	<br>
	<br>

<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/signature_pad.min.js"></script>
<script>

	document.getElementById("device_name").value    = "none";
	document.getElementById("secret").value         = "none";
	<?php if(sizeof($contactpersons)!=0){ ?>
		updateinput();
		document.getElementById("contactperson").style.display = 'none';
	<?php } ?>
	function updateinput(){
		document.getElementById("contactperson").value = document.getElementById("contactpersondd").value;
	}
	function contactpersonselected(){
		if(document.getElementById("contactpersondd").value==="custom"){
			document.getElementById("contactperson").style.display = 'block';
			document.getElementById("contactpersondd").style.display = 'none';
			document.getElementById("contactperson").value="";
		}else{
			updateinput();
		}
	}
	function next($showname=false){
		if(	document.getElementById("name").value.length>0 &&
			document.getElementById("surname").value.length>0 &&
			document.getElementById("company").value.length>0 &&
			document.getElementById("contactperson").value.length>0 ){
				document.getElementById("formd").style.width=null;
				document.getElementById("reoccuring_step").style.display="none";
				document.getElementById("step1_2").style.display="none";
				document.getElementById("step1_1").style.display="none";
				document.getElementById("step1_3").style.display="none";
				document.getElementById("step1_4").style.display="none";
				document.getElementById("step1_5").style.display="none";
				document.getElementById("step2_1").style.display="block";
				document.getElementById("step2_2").style.display="inline";
				document.getElementById("step2_3").style.display="inline";
				document.getElementById("step2_4").style.display="block";
				document.getElementById("formnext").style.display="none";
		}
      
      	if(document.getElementById("name").value.length<1){
			document.getElementById("name").focus();
        	return;
		}
      	if(document.getElementById("surname").value.length<1){
			document.getElementById("surname").focus();
          	return;
		}
      	if(document.getElementById("company").value.length<1){
			document.getElementById("company").focus();
        	return;
		}
		if(document.getElementById("contactpersondd").value.length<1){
			document.getElementById("contactpersondd").focus();
        	return;
		}
		if(document.getElementById("contactperson").value.length<1){
			document.getElementById("contactperson").focus();
			return;
		}
	}
  
	var canvas = document.getElementById("signature-pad");
	setScaling(canvas);
	function setScaling(canvas) {
		canvas.style.width = canvas.style.width || canvas.width + 'px';
		canvas.style.height = canvas.style.height || canvas.height + 'px';
		var scaleFactor = window.devicePixelRatio;
		// bigger than 2.5 is crazy and a slowdown in general
		scaleFactor = scaleFactor>2.5?2.5:scaleFactor;
		var width = parseFloat(canvas.style.width);
		var height = parseFloat(canvas.style.height);
		var oldScale = canvas.width / width;
		var backupScale = scaleFactor / oldScale;
		var backup = canvas.cloneNode(false);
		backup.getContext('2d').drawImage(canvas, 0, 0);
		var ctx = canvas.getContext('2d');
		canvas.width = Math.ceil(width * scaleFactor);
		canvas.height = Math.ceil(height * scaleFactor);
		ctx.setTransform(backupScale, 0, 0, backupScale, 0, 0);
		ctx.drawImage(backup, 0, 0);
		ctx.setTransform(scaleFactor, 0, 0, scaleFactor, 0, 0);
	}
	 function checkSignature(){
		var data = signaturePad.toDataURL('image/png');
		document.getElementById('signature').value = data;
		var signatureOK = !signaturePad._isEmpty;
		if(signatureOK){ // prevent multi submit
			document.getElementById("step2_2").disabled = true;
		}
		return signatureOK;
	}
	var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)',
	  minWidth: 1.5,
	});
	var cancelButton = document.getElementById('step2_3');
	cancelButton.addEventListener('click', function (event) {
	  signaturePad.clear();
	  return false;
	});
</script>
<script>
	// hack to fix stuck ios keyboard in mobile application mode and guided access
	window.setInterval(function(){
		var name = document.getElementById("name").value;
		if(name.length==0){
			document.getElementById("name").disabled=true;
			location.reload();
		}
	}, 60000);

	var firstState=null;
	var lastState=null;

</script>
