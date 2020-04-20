<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */	

	require_once './api.php';
	checkCSRF();

	if(	isset($_POST['name'])		&&isset($_POST['surname'])		&&isset($_POST['contactperson'])
		&&isset($_POST['signature'])&&isset($_POST['device_name'])	&&isset($_POST['secret']) &&isset($_POST['company'])){

		$chkRes= checkSecret($_POST['device_name'], $_POST['secret'],true);
		if($chkRes===1){
			$name		= $_POST['name'];
			$surname	= $_POST['surname'];
			$company	= $_POST['company'];
			$contactperson	= $_POST['contactperson'];
			$signatureB64	= $_POST['signature'];

			// basic sanity check against potential DDOS
			if(strlen($signatureB64)>280000){
				die("<h1>Signature too big</h1>");
			}

			$signatureB64Raw = str_replace('data:image/png;base64,', '', $signatureB64,$count);
			if($count!==1){
				die("Invalid signature image transmitted (1)");
			}
			$signatureB64Raw = str_replace(' ', '+', $signatureB64Raw);

			$signatureDecoded 		= base64_decode($signatureB64Raw);
			if(!$signatureDecoded){
				die("Invalid signature image transmitted (2)");
			}

			$signatureImage 		= @imagecreatefromstring($signatureDecoded);
			if(!$signatureImage){
				die("Invalid Image");
			}

			$signatureImageScaled 	= imagecreatetruecolor(SIGNATURE_WIDTH, SIGNATURE_HEIGHT);
			imagecolortransparent($signatureImageScaled, imagecolorallocatealpha($signatureImageScaled, 0, 0, 0, 127));
			imagealphablending($signatureImageScaled, false);
			imagesavealpha($signatureImageScaled, true);

			$resampleRes = imagecopyresampled($signatureImageScaled, $signatureImage, 0, 0, 0, 0, SIGNATURE_WIDTH, SIGNATURE_HEIGHT, imagesx ($signatureImage), imagesy ($signatureImage));

			if(!$resampleRes){
				die("Error resampling signature image");
			}
			ob_start();
			$convertRes = imagepng($signatureImageScaled);
			if(!$convertRes){
				die("Error converting resampled signature image");
			}
			$signatureScaled = ob_get_contents();
			ob_end_clean();
			$signatureScaledEncoded = "data:image/png;base64,".base64_encode($signatureScaled);
			$visitoraddedid	=selfCheckinVisitor($name,$surname,$company,$contactperson,$signatureScaledEncoded);
			header('Location: tablet.php');
			die();
		}elseif($chkRes==0){
			header('Location: tablet.php?msg=device_not_active');
			die();
		}else{
			header('Location: tablet.php?p=tabletregister&msg=invalid_device_credentials');
			die();
		}
	}else{
		header('Location: tablet.php?msg=missing_params');
		die();
	}
?>