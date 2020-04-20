<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();

	include("includes/datatables.php");
	init("employees");
	init("contactperson");
	init("reoccuring");
	$employees = getFrontdeskemployees();
	$reoccurings = getReoccuring();
	$identifiers = getIdentifiersList();
?>
	<br>

	<button class="btn" id="button_tab_user_management" onclick="switchTab('tab_user_management',true)" style="background: <?= MARK_ITEM_COLOR ?>"><?= translation_get('user_management'); ?></button>
	<button class="btn" id="button_tab_devices" onclick="switchTab('tab_devices',true)" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('device'); ?></button>
	<button class="btn" id="button_tab_change_password" onclick="switchTab('tab_change_password',true)" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('password_change'); ?></button>
	<button class="btn" id="button_tab_contactperson_title" onclick="switchTab('tab_contactperson_title',true)" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('contactperson_title'); ?></button>
	<button class="btn" id="button_tab_reoccuring_title" onclick="switchTab('tab_reoccuring_title',true)" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('reoccuring_title'); ?></button>
	<button class="btn" id="button_tab_identifier_title" onclick="switchTab('tab_identifier_title',true)" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('identifier_title'); ?></button>
	<br><br>
	<script>
	  function switchTab(tab,clicked){
	    if(clicked){
  	      var msgs = document.getElementsByClassName("alert");
  	      for (index = 0; index < msgs.length; ++index) {
  	        msgs[index].remove();
	      }
	    }
            document.getElementById("tab_change_password").style.display="none";
            document.getElementById("tab_user_management").style.display="none";
            document.getElementById("tab_contactperson_title").style.display="none";
            document.getElementById("tab_reoccuring_title").style.display="none";
            document.getElementById("tab_identifier_title").style.display="none";
            document.getElementById("tab_devices").style.display="none";
            document.getElementById("button_tab_change_password").style.backgroundColor="<?= BRANDING_COLOR ?>";
            document.getElementById("button_tab_user_management").style.backgroundColor="<?= BRANDING_COLOR ?>";
            document.getElementById("button_tab_contactperson_title").style.backgroundColor="<?= BRANDING_COLOR ?>";
            document.getElementById("button_tab_reoccuring_title").style.backgroundColor="<?= BRANDING_COLOR ?>";
            document.getElementById("button_tab_identifier_title").style.backgroundColor="<?= BRANDING_COLOR ?>";
            document.getElementById("button_tab_devices").style.backgroundColor="<?= BRANDING_COLOR ?>";

	    window.history.pushState('', '', 'index.php?p=management&tab='+tab);
            document.getElementById(tab).style.display="block";
            document.getElementById("button_"+tab).style.backgroundColor="<?= MARK_ITEM_COLOR ?>";

          }
	<?php
	if(isset($_GET["tab"])){
  	  $tab = preg_replace("/[^a-z_]+/i", "", $_GET["tab"]);?>
	  document.addEventListener('DOMContentLoaded', function() {
	    switchTab('<?= $tab ?>',false);
	  }, false);
	<?php }?>
	</script>

	<span id="tab_change_password" style="display: none">
	<?php if(!currentlyLoggedOnUserIsLDAP()){ ?>
		<b><?= translation_get('password_policy'); ?></b><br>
		<ul>
			<li><?= translation_get('password_policy_min_length');  ?>: <?= PW_POLICY_MIN_LENGTH ?></li>
			<li><?= translation_get('password_policy_min_number'); ?>: <?= PW_POLICY_MIN_NUMBERS ?></li>
			<li><?= translation_get('password_policy_min_capital'); ?>: <?= PW_POLICY_MIN_CAPITAL ?></li>
			<li><?= translation_get('password_policy_min_special'); ?>: <?= PW_POLICY_MIN_SPECIAL ?></li>
		</ul>
		<br>
		<form action="index.php?p=changepassword&tab=tab_change_password" method="POST">
			<?php addCSRFField() ?>
			<?php if(!admin_check()){ ?>
			<div class="form-group">
				<?= translation_get('password_current'); ?>: <input class="form-control" maxlength="250" type="password" name="passwordc" id="passwordc">
			</div>
			<?php }else{
					$users = getEmployeesForPWChange();
					echo('<div class="form-group">'.translation_get('password_change_for_user').'<select id="uid" name="uid" class="form-control">');
					foreach ($users as &$user) {
						$selected = $user['frontdeskemployee_id']===$_SESSION[id]?' selected="selected" ':'';
						echo('<option '.$selected.' value="'.$user['frontdeskemployee_id'].'">'.$user['frontdeskemployee_username'].'</option>');
					}
					echo("</select></div>");
			?>
			<?php } ?>
			<div class="form-group">
				<?= translation_get('password'); ?>: <input class="form-control" type="password" name="password" id="password">
			</div>
			<div class="form-group">
				<?= translation_get('password_repeat'); ?>: <input class="form-control" type="password" name="passwordr" id="passwordr"><br>
			</div>
			<button class="btn" style="background:<?= BRANDING_COLOR ?>" onclick="validatePolicy()"><?= translation_get('password_change'); ?></button>
		</form>

		<script>
			var password = document.getElementById("password")
			, confirm_password = document.getElementById("passwordr");

			function validatePassword(){
			  if(password.value != confirm_password.value) {
				confirm_password.setCustomValidity("<?= translation_get('password_mismatch'); ?>");
			  } else {
				confirm_password.setCustomValidity('');
			  }
			}

			function validatePolicy(){
				var pw = confirm_password.value;
				if(pw.length<<?= PW_POLICY_MIN_LENGTH ?>){
					confirm_password.setCustomValidity("<?= $translation['password_min_length'] ?>");
					return false;
				}
				var numbers = pw.replace(/[^0-9]/g,"").length;
				if(numbers<<?= PW_POLICY_MIN_NUMBERS ?>){
					confirm_password.setCustomValidity("<?= $translation['password_min_numbers'] ?>");
					return false;
				}
				var capitals = pw.replace(/[^A-Z]/g,"").length;
				if(capitals<<?= PW_POLICY_MIN_CAPITAL ?>){
					confirm_password.setCustomValidity("<?= $translation['password_min_capitals'] ?>");
					return false;
				}
				var lowers = pw.replace(/[^a-z]/g,"").length;

				var specials = pw.length-numbers-capitals-lowers;
				if(specials<<?= PW_POLICY_MIN_SPECIAL ?>){
					confirm_password.setCustomValidity("<?= $translation['password_min_special_chars'] ?>");
					return false;
				}
				return true;
			}

			password.onchange = validatePassword;
			confirm_password.onkeyup = validatePassword;
		</script>
		<?php } else {
			echo("<h4>".translation_get('password_change_ldap')."</h4>");
		} ?>
	</span>
	<span id="tab_devices" style="display: none;">
	  <?php
  	  // DEVICES
	  $registeredDevices = getRegisteredDevices();
	  $highlightdevice = isset($_GET['highlightdevice'])?intval($_GET['highlightdevice']):0; ?>
	  <br>
	  <?php if(!empty($registeredDevices)){?>
		<div class="table-responsive">
			<table class="table">
			<tr>
				<th><?= translation_get('device_name'); ?></th>
				<th><?= translation_get('device_state'); ?></th>
				<?php  if(admin_check()){ ?>
				<th></th>
				<?php } ?>
			</tr><?php
			foreach ($registeredDevices as &$device) { ?>
					<?php if($highlightdevice==$device['device_id']){
						?><tr style="background:<?= MARK_ITEM_COLOR ?>;"><?php
					}else{
						?><tr><?php
					} ?>
					<td>
						<?= $device['device_name'] ?>
					</td>
					<td>
						<?php
							$active = $device['device_active']==1?true:false;
							if($active){
								?><button class="btn" style="background: <?= BRANDING_COLOR ?>" disabled><?= translation_get('device_active') ?></button>
							<?php }else{ ?>
								<form action="index.php?p=activatedevice" method="POST">
									<?php addCSRFField() ?>
									<input type="hidden" name="device_id" value="<?= $device['device_id'] ?>">
									<button class="btn" style="background: <?= BRANDING_COLOR ?>">
										<span class="oi oi-circle-check"></span> <?= translation_get('device_activate') ?>
									</button>
								</form>
							<?php }
						?>
					</td>
					<?php  if(admin_check()){ ?>
						<td>
							<form action="index.php?p=deletedevice" method="POST">
								<?php addCSRFField() ?>
								<input type="hidden" name="device_id" value="<?= $device['device_id'] ?>">
								<button class="btn" style="background: <?= BRANDING_WARNING_COLOR ?>">
									<?= translation_get('device_delete') ?>
								</button>
							</form>
						</td>
					<?php } ?>
				</tr><?php
			}?>
			</table>
		</div> <?php
	  }else{
		echo("<br>".translation_get('device_no_devices'));
	  } ?>
	  <form action="index.php?p=registerdeviceform" method="POST">
                <button class="btn" style="background: <?= BRANDING_COLOR ?>"><span class="oi oi-plus"></span> <?= translation_get('device_register'); ?></button>
          </form>
	</span>
	<span id="tab_user_management" style="display: block;">
	<?php
	// USER MANAGEMENT
	if(!empty($employees)){
	        $highlightemployee = isset($_GET['highlightemployee'])?intval($_GET['highlightemployee']):0; ?>
		<div class="table-responsive">
			<table id="employees" class="table">
			<thead>
				<tr>
					<th><?= translation_get('username'); ?></th>
					<th><?= translation_get('created_at'); ?></th>
					<?php if($_SESSION['admin']){ ?>
					<th><?= translation_get('state'); ?></th>
					<th><?= translation_get('admin'); ?></th>
					<?php } ?>
				</tr>
			</thead><tbody><?php
			foreach ($employees as &$employee) {
			  if($highlightemployee==$employee['frontdeskemployee_id']){
                                ?><tr style="background:<?= MARK_ITEM_COLOR ?>;"><?php
				}else{?>
				<tr>
				<?php } ?>
					<td>
						<?= $employee['frontdeskemployee_username'] ?>
					</td>
					<td>
						<?= $employee['frontdeskemployee_creation'] ?>
					</td>
					<?php if($_SESSION['admin']){
							if($employee['frontdeskemployee_active']){?>
							<td>
								<form action="index.php?p=deactivatefrontdeskemployee&tab=tab_user_management" method="POST">
									<input type="hidden" name="fdeid" value="<?= $employee['frontdeskemployee_id'] ?>">
									<?php addCSRFField() ?>
									<button class="btn" <?= $employee['frontdeskemployee_id']==$_SESSION['id']?"disabled":""; ?> style="width: 145px; background: <?= BRANDING_WARNING_COLOR ?>"><?= translation_get('deactivate'); ?></button>
								</form>
							</td><?php } else { ?>
							<td><form action="index.php?p=activatefrontdeskemployee&tab=tab_user_management" method="POST">
									<?php addCSRFField() ?>
									<input type="hidden"  name="fdeid" value="<?= $employee['frontdeskemployee_id'] ?>">
									<button class="btn" <?= $employee['frontdeskemployee_id']==$_SESSION['id']?"disabled":""; ?>style="width: 145px; background: <?= BRANDING_COLOR ?>"><?= translation_get('activate'); ?></button>
								</form>
							</td>
						<?php } ?>
						<?php if($employee['frontdeskemployee_admin']){?>
							<td><form action="index.php?p=demotefrontdeskemployee&tab=tab_user_management" method="POST">
								<?php addCSRFField() ?>
								<input type="hidden" name="fdeid" value="<?= $employee['frontdeskemployee_id'] ?>">
								<button class="btn" <?= $employee['frontdeskemployee_id']==$_SESSION['id']?"disabled":""; ?> style="width: 220px; background: <?= BRANDING_WARNING_COLOR ?>"><?= translation_get('admin_demote'); ?></button>
							</form></td>
						<?php }else{ ?>
							<td><form action="index.php?p=promotefrontdeskemployee&tab=tab_user_management" method="POST">
								<?php addCSRFField() ?>
								<input type="hidden" name="fdeid" value="<?= $employee['frontdeskemployee_id'] ?>">
								<button class="btn" <?= $employee['frontdeskemployee_id']==$_SESSION['id']?"disabled":""; ?> style="width: 220px; background: <?= BRANDING_COLOR ?>"><?= translation_get('admin_promote'); ?></button>
							</form></td>
						<?php }
					} ?>
				</tr><?php
			}?>
			</tbody>
			</table>
		</div>
	<?php }else{ echo("<br>".translation_get("no_employee_yet")."<br><br>");}
	if($_SESSION['admin']){ ?>
		<form action="index.php?p=addfrontdeskemployeeform&tab=tab_user_management" method="POST">
			<?php addCSRFField() ?>
			<button class="btn" style="background: <?= BRANDING_COLOR ?>"><span class="oi oi-plus"></span> <?= translation_get('fde_add'); ?></button>
		</form><br>
	<?php }
	?></span><?php
	// CONTACT PERSON
	$contactpersons = getContactpersons();
	$highlightcontactperson = isset($_GET['highlightcontactperson'])?intval($_GET['highlightcontactperson']):0; ?>
        <span id="tab_contactperson_title" style="display: none;">
	<div class="table-responsive">
		<table id="contactperson" class="table">
			<thead>
			<tr>
				<th><?= translation_get('contactperson_name'); ?></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($contactpersons as $contactperson) {
				if($highlightcontactperson==$contactperson['contactperson_id']){
                                ?><tr style="background:<?= MARK_ITEM_COLOR ?>;"><?php
				}else{?>
				<tr><?php } ?>
					<td>
						<?= $contactperson["contactperson_name"] ?>
					</td>
					<td>
						<form action="index.php?p=removecontactperson&tab=tab_contactperson_title" method="POST">
							<?php addCSRFField() ?>
							<input type="hidden"  name="cpid" value="<?= $contactperson["contactperson_id"] ?>">
							<button class="btn" style="background: <?= BRANDING_WARNING_COLOR ?>"><?= translation_get('contactperson_remove'); ?></button>
						</form>
					</td>
					<td>
						<form action="index.php?p=changeemailform&id=<?= $contactperson['contactperson_id'] ?>&tab=tab_contactperson_title" method="POST">
					                <button class="btn" style="background: <?= BRANDING_COLOR ?>"> <?= translation_get('email_change'); ?></button>
					        </form>
					</td>
					<td>
						<form action="index.php?p=changemobileform&id=<?= $contactperson['contactperson_id'] ?>&tab=tab_contactperson_title" method="POST">
					                <button class="btn" style="background: <?= BRANDING_COLOR ?>"> <?= translation_get('mobile_change'); ?></button>
					        </form>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<form action="index.php?p=addcontactpersonform&tab=tab_contactperson_title" method="POST">
		<?php addCSRFField() ?>
		<button class="btn" style="background: <?= BRANDING_COLOR ?>"><span class="oi oi-plus"></span> <?= translation_get('contactperson_add'); ?></button>
	</form><br>
	</span>
        <span id="tab_reoccuring_title" style="display: none;">
	<?php // FAST CHECK-IN CODES
	$highlightreoccuring = isset($_GET['highlightreoccuring'])?intval($_GET['highlightreoccuring']):0;
	?>
        <script>
          function showCode(id){
            var code = document.getElementById("eye-"+id);
            var eye = document.getElementById("eye-icon-"+id);
            if(code.style.visibility === "visible"){
              code.style.visibility = "hidden";
              eye.className = "oi oi-eye";
            }else{
              code.style.visibility = "visible";
              eye.className = "oi oi-x";
            }
          }
        </script>
	<div class="table-responsive">
		<table id="reoccuring" class="table">
			<thead>
				<tr>
					<th><?= translation_get('reoccuring_code'); ?></th>
					<th><?= translation_get('reoccuring_name'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($reoccurings as $reoccuring) {
				if($highlightreoccuring==$reoccuring['reoccuringvisitor_id']){
                                ?><tr style="background:<?= MARK_ITEM_COLOR ?>;"><?php
				}else{ ?>
				<tr><?php } ?>
					<td>
						<span onclick="showCode(<?= $reoccuring["reoccuringvisitor_id"] ?>)" style="text-decoration: none; cursor: pointer;">
							<span id="eye-icon-<?= $reoccuring["reoccuringvisitor_id"] ?>" class="oi oi-eye"></span>
						</span>
						<span id="eye-<?= $reoccuring["reoccuringvisitor_id"] ?>" style="visibility:hidden">
							<?= $reoccuring["reoccuringvisitor_code"] ?>
						</span>
					</td>
					<td>
						<?= $reoccuring["reoccuringvisitor_name"] ?> <?= $reoccuring["reoccuringvisitor_surname"] ?> (<?= $reoccuring["reoccuringvisitor_company"] ?>)
					</td>
					<td>
						<form action="index.php?p=removereoccuring&tab=tab_reoccuring_title" method="POST">
							<?php addCSRFField() ?>
							<input type="hidden"  name="cpid" value="<?= $reoccuring["reoccuringvisitor_id"] ?>">
							<button class="btn" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('reoccuring_remove'); ?></button>
						</form>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<form action="index.php?p=addreoccuringform&tab=tab_reoccuring_title" method="POST">
		<?php addCSRFField() ?>
		<button class="btn" style="background: <?= BRANDING_COLOR ?>"><span class="oi oi-plus"></span> <?= translation_get('reoccuring_add'); ?></button>
	</form><br>
	</span>
        <span id="tab_identifier_title" style="display: none;">
	<?php // IDENTIFIER BADGES
		$highlightidentifier = isset($_GET['highlightidentifier'])?intval($_GET['highlightidentifier']):0;
	?>
	<div class="table-responsive">
		<table id="reoccuring" class="table">
			<thead>
				<tr>
					<th><?= translation_get('identifier_name'); ?></th>
					<th><?= translation_get('identifier_description'); ?></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($identifiers as $identifier) {
				if($highlightidentifier==$identifier['identifier_id']){ ?>
                                <tr style="background:<?= MARK_ITEM_COLOR ?>;">
				<?php }else{ ?>
				<tr>
				<?php } ?>
					<td>
						<?= $identifier["identifier_name"] ?>
					</td>
					<td>
						<?= $identifier["identifier_description"] ?>
					</td>
					<td>

						<form action="index.php?p=identifierhistory&identifier=<?= $identifier["identifier_id"] ?>&tab=tab_identifier_title" method="POST">
							<?php addCSRFField() ?>
							<input type="hidden"  name="identifierid" value="<?= $identifier["identifier_id"] ?>">
							<button class="btn" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('identifier_history'); ?></button>
						</form>
					</td>
					<td>
						<form action="index.php?p=removeidentifier&tab=tab_identifier_title" method="POST">
							<?php addCSRFField() ?>
							<input type="hidden"  name="identifierid" value="<?= $identifier["identifier_id"] ?>">
							<button class="btn" style="background: <?= BRANDING_COLOR ?>"><?= translation_get('reoccuring_remove'); ?></button>
						</form>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<form action="index.php?p=addidentifierform&tab=tab_identifier_title" method="POST">
		<?php addCSRFField() ?>
		<button class="btn" style="background: <?= BRANDING_COLOR ?>"><span class="oi oi-plus"></span> <?= translation_get('identifier_add'); ?></button>
	</form>
	</span>
	<br>

