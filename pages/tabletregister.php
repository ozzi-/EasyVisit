<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
        require_once './api.php';
        defined('APP_RAN') or die();
?>
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

        #overlay {
                display:none;
                position:fixed;
                padding:0;
                margin:0;

                top:0;
                left:0;

                width: 100%;
                height: 95%;
                background-color: gray;
                opacity: .8;
        }
</style>
<br>
<table id="wrapper">
        <tr><td>
                <span id="secret">
                        <h3><?= translation_get('device_registered'); ?>. <a href="tablet.php"><?= translation_get('continue'); ?></a></h3>
                </span>

                <span id="update_browser"><b>Your browser does not support local storage.</b></span>

                <span id="no_secret">
                        <h2><?= translation_get('device_this_register'); ?></h2>
                        <?= translation_get('device_name'); ?> <input maxlength="250" class="form-control" type="text" style="margin: auto; width:300px" id="form_device_name"><br>
                        <?= translation_get('device_secret'); ?> <input maxlength="250" class="form-control" type="password" style="margin: auto; width:300px" id="form_secret"><br>
                        <button class="btn" style="background: <?= BRANDING_COLOR ?>" onclick="register();" type="submit"><?= translation_get('submit'); ?></button>
                </span>
        </td></tr>
</table>
<script>
        updateUI();

        function updateUI(){
                document.getElementById("no_secret").style.display = "none";
                document.getElementById("secret").style.display = "none";
                document.getElementById("update_browser").style.display = "none";
                if (typeof(Storage) !== "undefined") {
                        console.log(localStorage.secret);
                        if(localStorage.secret!==undefined){
                                document.getElementById("secret").style.display = "block";
                        }else{
                                document.getElementById("no_secret").style.display = "block";
                        }
                } else {
                        document.getElementById("update_browser").style.display = "block";
                }
        }

        function checkSecret(device_name,secret){
                var request = new XMLHttpRequest();
                request.open("POST","calls/checkdevicesecret.php");
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                var params = "device_name="+device_name+"&secret="+secret;
                request.addEventListener('load', function(event) {
                        if (request.status == 200) {
                                var obj = JSON.parse(request.response);
                                if(obj.secret==="OK"){
                                        localStorage.secret=            document.getElementById("form_secret").value;
                                        localStorage.device_name=       document.getElementById("form_device_name").value;
                                        updateUI();
                                }else{
                                        alert("<?= translation_get('device_invalid_credentials'); ?>");
                                }
                        }else{
                                alert("Server error.");
                        }
                });
                request.send(params);
        }

        function register(){
                var device_name = document.getElementById("form_device_name").value;
                var secret = document.getElementById("form_secret").value;

                if(secret.length>0 && device_name.length>0){
                        checkSecret(device_name,secret);
                }
        }
</script>
