<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	
	defined('APP_RAN') or die();
?>
<style>
        body,html {
        	position: fixed;
	}
	.footer {
		position: fixed;
		right: 0px;
		bottom: 0px;
		left: 0px;
		min-height: 25px;
		width: 100%;
		background-color: #efefef;
		text-align: center;
	}

	img{
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	}

	.center {
		padding-left:13%;
		padding-right:13%;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	body,html {
		position: static;
	}

	#overlay {
		display:none;
		position:fixed;
		padding:0;
		margin:0;
		top:0;
		left:0;
		width: 100%;
		height: calc(100% - 25px);
		background-color: gray;
		opacity: .8;
	}
</style>

<a id="formlink" href="tablet.php?p=tabletform&action=user">
	<span class="center">
	<div style="text-align: center;">
		<h3>Check-In</h3>
	</div>
	<img class="img-fluid" src="img/logo.png">
</span>
</a>

<span class="footer">
	<a href="#" onclick="settings()">
			<?= translation_get("settings"); ?>
	</a>
</span>

<div id="overlay"></div>

<script>
	function settings(){
		var settingsprompt = prompt("<?= translation_get("device_pass_settings"); ?>");
		if(settingsprompt != null){
			if(settingsprompt===localStorage.secret){
				window.location="tablet.php?p=tabletsettings";
			}
		}
	} 

	(function poll() {
		if(localStorage.secret===undefined){
			window.location="tablet.php?p=tabletregister";
		}
		var request = new XMLHttpRequest();
		request.open("GET","calls/awaitinginput.php");
		request.addEventListener('load', function(event) {
			if (request.status == 200) {
				try {
					var jsonResponse = JSON.parse(request.responseText);
					var awaitingInput = jsonResponse.awaiting_input === "true";
					var deviceName = jsonResponse.device_name;
					if(deviceName!==localStorage.device_name){
						document.getElementById('formlink').removeAttribute('href');
						document.getElementById('overlay').style.display = 'block';
					}else{
						document.getElementById('formlink').setAttribute("href","tablet.php?p=tabletform&action=user");
						document.getElementById('overlay').style.display = 'none';
					}
					if(awaitingInput && deviceName == localStorage.device_name){
						window.location.href = "tablet.php?p=tabletform";
					}
				} catch(err) {
					console.warn(err.message);
				}
			} else {
				console.warn(request.statusText, request.responseText);
			}
			setTimeout(poll, 1200);
		});
		request.send();
	}());
</script>
