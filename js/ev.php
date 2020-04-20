<?php
  header("Content-Type: application/javascript; charset=utf-8");
  header("X-Content-Type-Options: nosniff", true);
  include("../includes/config.php");
  include("../includes/translations.php");
?>
function pollVisitorStatus() {
    var request = new XMLHttpRequest();
    request.open("GET", "calls/visitorstatus.php");
    request.addEventListener('load', function(event) {
        if (request.status == 200) {
            try {
                var jsonResponse = JSON.parse(request.responseText);
                if (jsonResponse.checkin == 0) {
                    document.getElementById("checkincount").style.visibility = "hidden";
                } else {
                    document.getElementById("checkincount").style.visibility = "visible";
                }
                if (jsonResponse.checkout == 0) {
                    document.getElementById("checkoutcount").style.visibility = "hidden";
                } else {
                    document.getElementById("checkoutcount").style.visibility = "visible";
                }
                document.getElementById("checkincount").innerHTML = jsonResponse.checkin;
                document.getElementById("checkoutcount").innerHTML = jsonResponse.checkout;
            } catch (err) {
                console.warn("Error setting visitor status in ui: "+err.message);
            }
        } else {
            console.warn("Error polling visitor status - "+request.statusText+" - "+request.responseText);
        }
        setTimeout(pollVisitorStatus, 2200);
    });
    if (document.getElementById("checkincount") != undefined) {
        request.send();
    }
}

function reoccuringCodeOnInputHandler() {
    var code = document.getElementById("reoccuring_code").value;
    var xhr = new XMLHttpRequest();
    var params = "code=" + code;
    xhr.open("GET", "calls/checkreoccuringcode.php?" + params, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function(e) {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var responseJSON = JSON.parse(xhr.response);
                document.getElementById("name").value = responseJSON.reoccuringvisitor_name;
                document.getElementById("surname").value = responseJSON.reoccuringvisitor_surname;
                document.getElementById("company").value = responseJSON.reoccuringvisitor_company;
                document.getElementById("contactperson").value = responseJSON.reoccuringvisitor_contactperson;
		var confirmationText = responseJSON.reoccuringvisitor_name + " - " + responseJSON.reoccuringvisitor_surname + "(" + responseJSON.reoccuringvisitor_company + ")";
                document.getElementById("reoccuring_confirmation").innerHTML = confirmationText;
                next();
            }
        }
    };
    xhr.send(null);
}

function validatePassword(){
  var password = document.getElementById("password")
  var confirm_password = document.getElementById("passwordr");
  if(password.value != confirm_password.value) {
        confirm_password.setCustomValidity("<?= translation_get('password_mismatch'); ?>");
  } else {
        confirm_password.setCustomValidity('');
  }
}

function validatePolicy(){
  var confirm_password = document.getElementById("passwordr");
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

