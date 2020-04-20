<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

	defined('APP_RAN') or die();
	require_once './api.php';
	login_check_r();

	$highlightvisitor = isset($_GET['highlightvisitor']) ? intval($_GET['highlightvisitor']) : 0;

	$date = date('Y-m-d');
	$visitorList = getSpecificVisitorList($date);
	?><h2><?= $visitorList['visitorlist_date'] ?></h2>
	<style>
		table {
			table-layout: fixed;
			width: 100%;
		}

		table td {
			word-wrap: break-word;
			overflow-wrap: break-word;
		}
	</style>
	<div class="table-responsive">
		<br><br>
		<table class="table">
		<tr>
			<th><?= translation_get("visitor_fullname") ?></th>
			<th><?= translation_get("visitor_company") ?></th>
			<th><?= translation_get("visitor_contactperson") ?></th>
			<th><?= translation_get("visitor_start") ?></th>
			<th><?= translation_get("visitor_end") ?></th>
		</tr><?php
		if(empty($visitorList["visitors"])){
	        	echo("<tr><td><br><b>".translation_get("visitor_no_visitors")."</b></td><td></td><td></td><td></td><td></td></tr>");
		}

		$visitors = $visitorList['visitors'];
//		function cmp($a, $b){
//			return strcmp($a["visitor_end"], $b["visitor_end"]);
//		}
//		usort($visitors, "cmp");


		foreach ($visitors as $index => &$visitor) {
			if($highlightvisitor==$visitor['visitor_id']){
				?><tr style="background:<?= MARK_ITEM_COLOR ?>;"><?php
                        }elseif ($index % 2 == 0){
                                ?><tr style="background: <?= ALTERNATE_ROW_COLOR ?>";><?php
			}else{
				?><tr><?php
			} ?>
			<td>
				<?= $visitor['visitor_name'] ?> <b><?= $visitor['visitor_surname']?></b>
			</td>
			<td>
				<?= $visitor['visitor_company'] ?>
			</td>
			<td>
				<?= $visitor['visitor_contactperson'] ?>
			</td>
			<td>
				<?= $visitor['visitor_start'] ?>
			</td>
			<td>
				<?php if($visitor['visitor_end']==null){ ?>
				<form action="index.php?p=checkoutvisitor" method="POST" >
					<?php addCSRFField();
						if(ALLOW_TO_SET_CHECKOUT_TIME){ ?>
							<div class="input-group clockpicker" data-autoclose="true">
								<input type="text" class="form-control" name="checkouttime" value="<?=  date("H:i") ?>">
								<div class="input-group-addon" style="cursor:pointer;">
									<span class="oi oi-clock clockpicker"></span>
								</div>
							</div>
							<?php
						} ?>
					<input type="hidden" name="visitor_id" value="<?= $visitor['visitor_id'] ?>">
					<button class="btn" style="background: <?= BRANDING_COLOR ?>; width: 100%; margin-top:10px;" type="submit"><?= translation_get('visitor_checkout') ?></button>
				</form>
				<?php } else {
					echo($visitor['visitor_end']);
				}?>
			</td>
		</tr>
		<?php }
	?></table></div><?php
?>
<script type="text/javascript">

	window.jQuery(document).ready(function($){
		var cps = $('.clockpicker');
		cps.each(function( index ) {
			$(this).clockpicker({
				donetext: 'Done',
				afterDone: function() {
					cps[index].firstElementChild.modifiedcp=true;
				}
			});
		});
	});

	function pad(i) {
		return (i < 10 ? '0' : '') + i;
	}

     function getHHMM(){
                var time = new Date();
                return (pad(time.getHours()) + ":" + pad(time.getMinutes()));
        }

	window.setInterval(function(){
		var timeElems = document.getElementsByName("checkouttime");
		var curHHMM =  getHHMM();
		for(i = 0;i < timeElems.length; i++){
			if(document.activeElement !== timeElems[i] && timeElems[i].modifiedcp !== true){
				timeElems[i].value = curHHMM;
			}
		}
	}, 10000);
</script>

