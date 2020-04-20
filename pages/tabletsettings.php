<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once './api.php';
	defined('APP_RAN') or die();
?>
<table id="wrapper">
	<tr><td>
		<button class="btn" style="width: 300px; background: <?= BRANDING_COLOR ?>" onclick="checkSecret()"><?= translation_get('check_secret'); ?></button><br><br>
		<button class="btn" style="width: 300px; background: <?= BRANDING_COLOR ?>" onclick="unregister()"><?= translation_get('unregister'); ?></button><br><br>
		<button class="btn" style="width: 300px; background: <?= BRANDING_COLOR ?>" onclick="window.location='tablet.php'"><?= translation_get('back'); ?></button>
	</td></tr>
</table>


<style>
	html, body, #wrapper {
	   height:100%;
	   width: 100%;
	   margin: 0;
	   padding: 0;
	   border: 0;
	}

	#wrapper td {
	   vertical-align: middle;
	   text-align: center;
	}

</style>
<script>
	function unregister(){
		localStorage.clear();
		window.location="tablet.php?p=tabletregister";
	}

	function checkSecret(){
		var request = new XMLHttpRequest();
		request.open("POST","calls/checkdevicesecret.php");
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var params = "device_name="+localStorage.device_name+"&secret="+localStorage.secret;
		request.addEventListener('load', function(event) {
			if (request.status == 200) {
				var obj = JSON.parse(request.response);
				if(obj.secret==="OK"){
					alert("<?= translation_get('device_valid_credentials'); ?>");
				}else{
					alert("<?= translation_get('device_invalid_credentials'); ?>");
				}
			}
		});
		request.send(params);
	}
</script>
