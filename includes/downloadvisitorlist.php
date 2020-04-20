<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	function injectJS(){?>
		<script>
		var downloadTimer;
		var attempts = 20;

		function blockResubmit(tokenValue,spinner,button) {
			console.log(button);
			document.getElementById(spinner).style.visibility = "visible";
			document.getElementById(button).disabled = true;
			downloadTimer = window.setInterval( function() {
				var token = getCookie( "downloadToken" );
				console.log(tokenValue);
				console.log(token);
				if( (token == tokenValue) || (attempts == 0) ) {
					if(attempts == 0){
						alert("Timeout generating PDF export");
					}
					unblockSubmit(spinner,button);
				}
				attempts--;
			}, 500 );
		}
		function unblockSubmit(spinner, button) {
		  window.clearInterval( downloadTimer );
		  expireCookie( "downloadToken" );
		  attempts = 20;
		  document.getElementById(spinner).style.visibility = "hidden";
		  document.getElementById(button).disabled = false;
		}
		function getCookie( name ) {
		  var parts = document.cookie.split(name + "=");
		  if (parts.length == 2) return parts.pop().split(";").shift();
		}
		function expireCookie( name ) {
		  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		}
		</script>
	<?php }

	function getDownloadButton($downloadLink){
		$rand = rand(); ?>
		<div id="spinner<?= $rand ?>" class="loader centered"></div>
		<button class="btn" style="background: <?= BRANDING_COLOR ?>" id="dwnldbtn<?= $rand ?>" onclick="dwnld<?= $rand ?>()">Export</button>
		<script language="javascript" type="text/javascript">
			document.getElementById("spinner<?= $rand ?>").style.visibility = "hidden";
			function dwnld<?= $rand ?>() {
				var token = new Date().getTime();
				blockResubmit(token,"spinner<?= $rand ?>","dwnldbtn<?= $rand ?>");
				window.location.href = "<?= $downloadLink ?>&token="+token;
			}
		</script>
<?php } ?>