<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	require_once './api.php';
	defined('APP_RAN') or die();
	login_check_r();
 	?>
       <form action="index.php" method="GET">
        <input type="hidden" name="p" value="searchvisitor">
        <input type="text" class="form-control" placeholder="<?= translation_get("visitor_name")  ?>" value="<?= isset($_GET['name'])? $_GET['name']:"" ?>" name="name"><br>
        <input type="text" class="form-control" placeholder="<?= translation_get("visitor_surname")  ?>" value="<?= isset($_GET['surname'])? $_GET['surname']:"" ?>" name="surname"><br>
        <input type="text" class="form-control" placeholder="<?= translation_get("visitor_company")  ?>" value="<?= isset($_GET['company'])? $_GET['company']:"" ?>" name="company"> <br>
        <button class="btn" style="background: <?= BRANDING_COLOR ?>" type="submit" ><?= translation_get("search")  ?></button>
	<br><br><br>
       </form>
	<?php
	if(isset($_GET['name'])&&isset($_GET['surname'])&&isset($_GET['company'])){
		$res = searchVisitor($_GET['name'],$_GET['surname'],$_GET['company']);
		?>
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col"><?= translation_get("date") ?></th><th scope="col"><?= translation_get("visitor_name") ?></th><th scope="col"><?= translation_get("visitor_surname") ?></th><th scope="col"><?= translation_get("visitor_company") ?></th>
		    <tr>
		  </thead>
		  <tbody><?php
		    foreach ($res as &$result) {
		      $id 		= $result ["visitor_id"];
		      $name 		= $result ["visitor_name"];
		      $surname 		= $result ["visitor_surname"];
		      $company 		= $result ["visitor_company"];
		      $date		= $result["visitorlist_date"];

		      $name 		= highlightResult($name,$_GET['name']);
		      $surname 		= highlightResult($surname,$_GET['surname']);
		      $company 		= highlightResult($company,$_GET['company']);

		      echo("<tr style='cursor:pointer;' class='clickable-row' data-href='index.php?p=visitorlist&id=".$result ["visitor_visitorlist_idfk"]."&highlightvisitor=".$id."'><td>".$date."</td><td>".$name."</td><td>".$surname."</td><td>".$company."</td></tr>");
		    }
		echo("</table>");
		if(sizeof($res)==0){
			echo("<h5>".translation_get("search_no_result")."</h5>");
		}
		echo("<br><button class=\"btn\" style=\"background: ".BRANDING_COLOR."\" onclick=\"window.history.back();\">".translation_get("back")."</button>");
		?><script>
		jQuery(document).ready(function($) {
		  $(".clickable-row").click(function() {
		    window.location = $(this).data("href");
		  });
		  $(".clickable-row").hover(function() {
		    var $this = $(this);
		    $this.data('bgcolor', $this.css('background-color')).css('background-color', '<?= MARK_ITEM_COLOR ?>');
		  });
                  $(".clickable-row").mouseleave(function() {
                    var $this = $(this);
                    $this.data('bgcolor', $this.css('background-color')).css('background-color', '#fff');
                  });
		});</script>
		<?php
	}else{
		header('Location: index.php?p=visitorlist&msg=missing_params');
		die();
	}

	function highlightResult($res,$var){
		$resl = strtolower($res);
		$varl = strtolower($var);
		$pos = strrpos($resl, ($varl));
		if($pos!==false){
			return substr($res,0,$pos)."<b>".substr($res, $pos, strlen($var))."</b>".substr($res,$pos+strlen($var));
		}
		return $res;
	}
?>
