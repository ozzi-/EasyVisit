<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once '../includes/dompdf/lib/html5lib/Parser.php';
	require_once '../includes/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
	require_once '../includes/dompdf/lib/php-svg-lib/src/autoload.php';
	require_once '../includes/dompdf/src/Autoloader.php';
	require_once '../api.php';

	restore_error_handler();

	Dompdf\Autoloader::register();
	use Dompdf\Dompdf;

	$TOKEN = "downloadToken";
	// Sets a cookie so that when the download begins the browser can
	// unblock the submit button (thus helping to prevent multiple clicks).
	// The false parameter allows the cookie to be exposed to JavaScript.
	if(isset($_GET['token'])){
		$tokenValue = preg_replace('/[^0-9]/', '', $_GET['token']);
		setCookieToken( $TOKEN, $tokenValue, false );

		$dompdf = new Dompdf();
		$html="";
		if(isset($_GET["date"])){
			$date = $_GET["date"];
		}else{
			$date = date('Y-m-d');
		}
		$html="<html><head>
		<meta http-equiv=\"Content-Type\" content=\"charset=utf-8\" /> <style>html{font-size:70%} .footer{    position: fixed; bottom: 0; right: 0;} .page-number:after { content: counter(page); }";
		$html.=str_replace("::before","",file_get_contents("../css/bootstrap.min.css"));
		$html.=" table { table-layout: fixed; width: 100%; } table td { word-wrap: break-word; overflow-wrap: break-word;   } html {font-family: DejaVu Sans, sans-serif;}";
		$html.="</style></head><body>";

		$visitorList = getSpecificVisitorList($date);
		if($visitorList===null){
			$html.=COMPANY_NAME." ".$date;
		}else{
			$html.='<span style="font-size:160%">'.COMPANY_NAME." - ".$visitorList["visitorlist_date"].'</span>';
			$html.='<br>'.translation_get('fde').': '.$visitorList["frontdeskemployee_username"]."<br>";
			if(!empty($visitorList["visitors"])){
				$html.='<div class="table-responsive">';
				$html.='<table class="table" id="table">';
				$html.='<tr><th>'.translation_get('visitor_name').'</th><th>'.translation_get('visitor_surname').'</th><th>'.translation_get('visitor_company').'</th><th>'.translation_get('visitor_contactperson').'</th>';
				if(ENABLE_VISITOR_ID_SHOWN){
					$html.='<th>'.translation_get('visitor_id_checked').'</th>';
				}
				if(ENABLE_VISITOR_IDENTIFIER){
					$html.='<th>'.translation_get('visitor_identifier').'</th>';
				}
				$html.='<th>'.translation_get('visitor_start').'</th><th>'.translation_get('visitor_end').'</th><th>'.translation_get('visitor_signature').'</th></tr>';
				foreach ($visitorList['visitors'] as &$visitor) {
					$html.='<tr>';
					$html.='<td>';
					$html.=$visitor['visitor_name'];
					$html.='</td><td>';
					$html.=$visitor['visitor_surname'];
					$html.='</td><td>';
					$html.=$visitor['visitor_company'];
					$html.='</td><td>';
					$html.=$visitor['visitor_contactperson'];
					$html.='</td>';
	                                if(ENABLE_VISITOR_ID_SHOWN){
						$html.="<td>".($visitor['visitor_idshown']?"<div style=\"font-family: DejaVu Sans, sans-serif;\">&#10004;</div>":"X")."</td>";
					}
					if(ENABLE_VISITOR_IDENTIFIER){
						$html.="<td>".$visitor['identifier_name']."</td>";
					}
					$html.='<td style="width:8%">';
					$html.=$visitor['visitor_start']."-".$visitor['frontdeskemployee_checkin'];
					$html.='</td><td style="width:8%">';
					$html.=$visitor['visitor_end']."-".$visitor['frontdeskemployee_checkout'];
					$html.='</td><td>';
					$html.='<img style="max-height:80px" src="'.$visitor['visitor_signature'].'" />';
					$html.='</td></tr>';
				}
				$html.='</table></div>';
			}
		}
		$html.='<div class="footer">
					<span class="page-number">Page </span>
				</div>';
		$html.="</body></html>";
		if(isset($_GET['debug_html'])){
			die($html);
		}

		$dompdf->loadHtml($html);

		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream('visitorlist_'.$date.'.pdf');
	}else{
		die("missing token");
	}

	function setCookieToken( $cookieName, $cookieValue, $httpOnly = true, $secure = false ) {
		setcookie(
			$cookieName,
			$cookieValue,
			2147483647,            // expires January 1, 2038
			"/",                   // your path
			$_SERVER["HTTP_HOST"], // your domain
			$secure,               // Use true over HTTPS
			$httpOnly              // Set true for $AUTH_COOKIE_NAME
		);
	}
?>
