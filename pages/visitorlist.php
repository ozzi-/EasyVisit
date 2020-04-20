<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
	require_once './api.php';
	login_check_r();

	include('includes/downloadvisitorlist.php');

	if(isset($_GET["id"])){
		$visitorList = getSpecificVisitorListById($_GET["id"]);
		$date = $visitorList['visitorlist_date'];
	}else{
		if(isset($_GET["date"])){
			$date = $_GET["date"];
		}else{
			$date = date('Y-m-d');
		}
		$visitorList = getSpecificVisitorList($date);
	}
	$today = ($date === date('Y-m-d'));

	injectJS();

	$highlightvisitor = isset($_GET['highlightvisitor'])?intval($_GET['highlightvisitor']):0;
	?>
	<div id="signaturemodal" style="padding-top:200px;" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"></h4>
		  </div>
		  <div class="modal-body">
			<div id="signatureplaceholder"></div>
		  </div>
		</div>
	  </div>
	</div>
	<div class="row">
		<div class="col">
			<h2><?= $date ?></h2>
		</div>
		<div class="col" style="text-align: right;">
			<?= translation_get("fde") ?>: <?= $visitorList["frontdeskemployee_username"] ?>
		</div>
	</div>
	</span>
	<br>
	<style>
		table td {
			word-wrap: break-word;
			overflow-wrap: break-word;
		}
	</style>
	<br>
	<?php if(!empty($visitorList["visitors"])){?>
		<div class="table-responsive">
			<table class="table" id="table">
			<thead><tr>
				<th><?= translation_get('visitor_fullname') ?>			</th>
				<th><?= translation_get('visitor_company') ?>			</th>
				<th><?= translation_get('visitor_contactperson') ?>		</th>
				<?php if (ENABLE_VISITOR_ID_SHOWN) { ?>
				<th><?= translation_get('visitor_id_checked') ?>		</th>
				<?php } ?>
				<?php if (ENABLE_VISITOR_IDENTIFIER) { ?>
				<th style="white-space: nowrap"><?= translation_get('visitor_identifier') ?>		</th>
				<?php } ?>
				<th style="white-space: nowrap"><?= translation_get('visitor_start') ?>				</th>
				<th><?= translation_get('visitor_end') ?>				</th>
				<th><?= translation_get('visitor_signature') ?>			</th>
			</tr></thead><tbody><?php
			foreach ($visitorList['visitors'] as $index => &$visitor) {
			if($highlightvisitor==$visitor['visitor_id']){
				?><tr style="background:<?= MARK_ITEM_COLOR ?>;"><?php
			}elseif ($index % 2 == 0){
				?><tr style="background: <?= ALTERNATE_ROW_COLOR ?>";><?php
			}else{
				?><tr><?php
			} ?>
				<td style="word-break: break-word; width:17%">
					<?= $visitor['visitor_name'] ?> <b><?= $visitor['visitor_surname'] ?></b>
				</td>
				<td style="word-break: break-word; width:20%">
					<?= $visitor['visitor_company'] ?>
				</td>
				<td>
					<?= $visitor['visitor_contactperson'] ?>
				</td>
				<?php if (ENABLE_VISITOR_ID_SHOWN) { ?>
				<td>
					<?php if($visitor['visitor_idshown']){
							 echo("&#10004;");
						}else{
							echo("&#10006;");
						}
						?>
				</td>
				<?php } ?>
				<?php if(ENABLE_VISITOR_IDENTIFIER){ ?>
					<td><?= $visitor['identifier_name'] ?></td>
				<?php } ?>
				<td>
					<?= $visitor['visitor_start'] ?> -
					<?= $visitor['frontdeskemployee_checkin'] ?>
					<br>
					<?php if(ENABLE_VISITOR_BADGE && $today){ ?><a href="index.php?p=visitorbadge&visitorid=<?= $visitor['visitor_id'] ?>"><?= translation_get('create_badge') ?></a><?php } ?>
				</td>
				<td>
					<?php if(!$today && $visitor['visitor_end']===NULL){?>
						<form action="index.php?p=checkoutvisitor" method="POST" >
							<?php addCSRFField() ?>
							<input type="hidden" name="visitor_id" value="<?= $visitor['visitor_id'] ?>">
							<?php if($highlightvisitor==$visitor['visitor_id']){?>
								<button class="btn" style="border:1px; border-style: solid; background: <?= BRANDING_COLOR ?>" ><?=translation_get("late_checkout") ?></button>
							<?php }else{ ?>
							<button class="btn" style="background: <?= BRANDING_COLOR ?>" ><?= translation_get("late_checkout") ?></button>
							<?php } ?>
						</form>
					<?php }else{?>
						<?= $visitor['visitor_end'] ?> -
						<?= $visitor['frontdeskemployee_checkout'] ?>
					<?php } ?>
				</td>
				<td>
					<img name="signature" style="cursor: pointer; max-height:80px" src="<?= $visitor['visitor_signature'] ?>" onclick="zoomSignature(this)"/>
				</td>
				</tr></tbody>
				<?php } ?> </table>
                </div>

                <a href="#" id="showModalLink" style="display:none;" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#signaturemodal">Open Modal</a>
                <script>
                var delay = function (elem, callback) {
                        var timeout = null;
                        elem.onmouseover = function() {
                                timeout = setTimeout(function() {callback(elem)}, 1000);
                        };
                        elem.onmouseout = function() {
                                clearTimeout(timeout);
                        }
                };

                document.getElementsByName('signature').forEach(function (s, i, o) {
                        i++;
                        delay(s, zoomSignature);
                });

                function zoomSignature(elem){
                        document.getElementById("showModalLink").click();
                        var img = document.createElement("IMG");
                        img.src = elem.src;
                        img.classList.add('img-fluid');
                        document.getElementById('signatureplaceholder').innerHTML = '';
                        document.getElementById('signatureplaceholder').appendChild(img);
                }
                </script><?php
		getDownloadButton("calls/visitorlistexport.php?date=".$date);
	}else{ echo("<b>".translation_get('visitor_no_visitors')."</b>");}
?>
