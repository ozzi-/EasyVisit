<?php
	header("Content-type: text/css", true);
	header("X-Content-Type-Options: nosniff", true);
	include("../includes/config.php");
?>
.html{
}

.btn{
	color:white;
	cursor: pointer;
}

::selection {
  background: <?= BRANDING_COLOR ?>;
}
::-moz-selection {
  background: <?= BRANDING_COLOR ?>;/
}

a{
	color:<?= BRANDING_COLOR ?>;
}

a:hover{
	color:<?= BRANDING_WARNING_COLOR ?>;
}


.navbar-toggler{
	color: <?= BRANDING_COLOR ?>;
}
.loader {
    border: 16px solid <?= BRANDING_COLOR ?>;
    border-top: 16px solid <?= BRANDING_WARNING_COLOR ?>;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
	
}
.centered {
  position: fixed; 
  top: 50%;
  left: 50%;
}

.content {
	margin-right: 5.5vw;
	margin-left: 5.5vw;
}

.header{
	padding: 0px 0 0px 0px;
	box-shadow: 0px 0px 2px 2px rgba(0,0,0,0.2);
	z-index: 9999;
	width: 100%;
	position: fixed;
	background: #fff;
}


@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.home-link{
 text-decoration-color: white !important;
 text-decoration: none !important;
}
