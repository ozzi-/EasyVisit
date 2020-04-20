<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
        require_once './api.php';
        login_check_r(true);

        $history = array_reverse(getVisitorCountHistory(12));
     	$nov = getVisitorCountThisMonth();
?>
<br>
<style>
  .center {
    padding-left:23%;
    padding-right:23%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>
<!-- <img class="img-fluid center" src="img/logo.png"> -->
<div class="container">
	<div class="row">
		<div class="col-lg" align="center" style="margin-bottom: 15px">
			<span style="padding:5px 15px 5px 15px; background-color:<?= BRANDING_COLOR ?>; -moz-border-radius: 4px 4px 4px 4px; border-radius: 4px 4px 4px 4px;">
				<span class="oi oi-calendar"></span>
				<b><?= $nov ?></b>
			</span><br>
			<?= translation_get("dashboard_visitors_this_month") ?>
		</div>
		<div class="col-lg" align="center" style="margin-bottom: 15px">
			<span style="padding:5px 15px 5px 15px; background-color:<?= BRANDING_WARNING_COLOR ?>; -moz-border-radius: 4px 4px 4px 4px; border-radius: 4px 4px 4px 4px;">
				<span class="oi oi-person"></span> <b><?= getVisitorCountToday()?></b>
			</span><br>
			<?= translation_get("dashboard_visitors_today") ?>
		</div>
		<div class="col-lg" align="center" style="margin-bottom: 15px">
			<span style="padding:5px 15px 5px 15px; background-color:<?= BRANDING_COLOR ?>; -moz-border-radius: 4px 4px 4px 4px; border-radius: 4px 4px 4px 4px;">
				<span class="oi oi-action-redo"></span> <b><?= getVisitorCountTodayCheckedIn()?></b>
			</span><br>
			<?= translation_get("dashboard_visitors_currently_checkedin") ?>
		</div>
		<div class="col-lg" align="center" style="margin-bottom: 15px">
			<span style="padding:5px 15px 5px 15px; background-color:<?= BRANDING_WARNING_COLOR ?>; -moz-border-radius: 4px 4px 4px 4px; border-radius: 4px 4px 4px 4px;">
				<span class="oi oi-action-undo"></span> <b><?= getVisitorCountTodayCheckedOut() ?></b>
			</span><br>
			<?= translation_get("dashboard_visitors_currently_checkedout") ?>
		</div>
	</div>
</div>
<br><hr><br>
<div class="row">
	<div class="col"><?php
		$date = date('Y-m-d');
		$visitorList = getSpecificVisitorList($date);
		$currentVisitors = false;
		foreach($visitorList['visitors'] as $visitor){
			if(strlen($visitor['frontdeskemployee_checkout'])<1){
				$currentVisitors= true;
			}
		}
		if($currentVisitors){?>
			<h5><?= translation_get("dashboard_currently_loggedin") ?></h5>
		<?php }else{ ?>
			<h5><?= translation_get("dashboard_currently_nobody_loggedin") ?></h5>
		<?php }
		foreach($visitorList['visitors'] as $visitor){
			if(strlen($visitor['frontdeskemployee_checkout'])<1){?>
			<a style="text-decoration: none;" href="index.php?p=checkoutvisitorform&highlightvisitor=<?= $visitor["visitor_id"] ?>">
				<span style="white-space: nowrap; color:black; padding:5px 0px 5px 0px; background-color:<?= BRANDING_COLOR ?>; margin-right: 20px; margin-bottom:10px;   display: inline-block;  -moz-border-radius: 4px 4px 4px 4px; border-radius: 4px 4px 4px 4px;">
				<?= "&nbsp;".$visitor["visitor_name"]." ".$visitor["visitor_surname"]."&nbsp;" ?>
				</span>
			</a>
			<?php }
		}
		?>
	</div>
	<div class="col">
		<div class="chart-container" style="position: relative; height:30vh;">
	        	<canvas id="visitorstats"></canvas>
		</div>
	</div>
  </div>
<br><br>
<script>
var ctx = document.getElementById("visitorstats").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php  foreach($history as &$month){
                echo("'".$month[0]."',");
            }
            ?>],
        datasets: [{
            borderColor: '<?= BRANDING_WARNING_COLOR ?>',
            backgroundColor: '<?= BRANDING_COLOR ?>',
            label: '# <?= translation_get("visitor") ?>',
            data: [<?php  foreach($history as &$month){
                echo($month[1].",");
            }
            ?>],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                  	stepSize: 5,
                }
            }]
        }
    }
});
</script>
