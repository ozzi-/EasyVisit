<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
  defined('APP_RAN') or die();
  require_once './api.php';
  login_check_r();

  include('includes/downloadvisitorlist.php');

  $visitorLists = getVisitorLists(VISITORLISTS_LIMIT,0);
  injectJS();
?>
<script>
function checkDateInput(x){
  if(x.value.length==10){
    document.getElementById("listShowBtn").disabled=false;
  }else{
    document.getElementById("listShowBtn").disabled=true;
  }
}
</script>
<div class="">
  <div class="row">
    <div class="col-6">
      <div id="results" class="table-responsive">
          <table class="table" id="table">
            <tr>
              <th style="width:250px"><?= translation_get("date") ?></th>
   	      <th><?= translation_get("visitor_count") ?></th>
	    </tr>
  	  <?php foreach ($visitorLists as &$visitorList) {?>
	    <tr>
	    <td style="width: 250px">
	      <a name="visitorlist" href="index.php?p=visitorlist&date=<?= $visitorList['visitorlist_date']?>">
	        <?=$visitorList['visitorlist_date'] ?>
	      </a>
	    </td>
	    <td>
	      <?=$visitorList["visitorcount"] ?>
	    </td>
	  </tr>
 	  <?php } ?>
        </table>
      </div>
    </div>
    <div class="col-6">
        <form action="index.php" method="GET" onsubmit="return checkDateInput();">
          <input type="hidden" name="p" value="visitorlist">
                <div class="form-inline">
                        <input type="date" class="form-control" onchange="checkDateInput(this)" id="date" value="<?= date('Y-m-d') ?>" name="date" style="border-radius: .0rem; border-top-left-radius: .25rem !important; border-bottom-left-radius: .25rem !important;">
                        <button class="btn" id="listShowBtn" style="background: <?= BRANDING_COLOR ?>; border-radius: .0rem; border-top-right-radius: .25rem !important; border-bottom-right-radius: .25rem !important;"><?= translation_get("visitorlist_show") ?></button>
                </div>
        </form>
        <br>
        <form action="index.php" method="GET">
        <div class="form-inline">
                <input type="hidden" name="p" value="searchvisitor">
                <input style="border-radius: .0rem; width:20%; border-top-left-radius: .25rem !important; border-bottom-left-radius: .25rem !important;" type="text" class="form-control" placeholder="<?= translation_get("visitor_name")?>" name="name">
                <input style="border-radius: .0rem; width:20%;" type="text" class="form-control" placeholder="<?= translation_get("visitor_surname")  ?>" name="surname">
                <input style="border-radius: .0rem; width:20%; " type="text" class="form-control" placeholder="<?= translation_get("visitor_company")  ?>" name="company">
                <button class="btn" style="background: <?= BRANDING_COLOR ?>; border-radius: .0rem; border-top-right-radius: .25rem !important; border-bottom-right-radius: .25rem !important;" type="submit"><?= translation_get("search")  ?></button>
        </div>
        <!-- <i><?= translation_get("visitorlist_search") ?></i> -->
       </form>
    </div>
  </div>
</div>
<div id="loading" class="loader centered" style="display: none;"></div>

<script>
var t = 0;
jQuery(document).ready(function() {
    if (!(jQuery("body").height() > jQuery(window).height())) {
	do{
		t=t+1;
			if(t>250){ return; }
        }
	while(!loadMore() );
    }
});

window.onscroll = function() {
	scrollCheck();
};


function scrollCheck() {
	var scrollTop = $(document).scrollTop();
	var windowHeight = $(window).height();
	var bodyHeight = $(document).height() - windowHeight;
	var scrollPercentage = (scrollTop / bodyHeight);
	if(scrollPercentage > 0.85) {
		loadMore();
	}
};

var offset=0;
var loading = false;
var lastOffset = 0;
var first = true;

function loadMore(){
        if(loading){
		return false;
        }
	loading=true;
	offset=document.getElementsByName('visitorlist').length;
	if(lastOffset == offset){
		return;
	}
	var request = new XMLHttpRequest();

	if(!first){
		document.getElementById("loading").style.display = "block";
	}

	first = false;

	request.open("GET","calls/loadmorevisitorlists.php?limit=<?= VISITORLISTS_LIMIT ?>&offset="+offset);
	request.addEventListener('load', function(event) {
		if (request.status == 200) {
			console.log(request.readyState);
			try {
				var jsonResponse = JSON.parse(request.responseText);
				for (i = 0; i < jsonResponse.length; i++) {
					var table = document.getElementById("table");
					var row = table.insertRow();
					var cell1 = row.insertCell(0);
					var cell2 = row.insertCell(1);
					cell1.innerHTML = '<a name="visitorlist" href="index.php?p=visitorlist&date='+jsonResponse[i].visitorlist_date+'">'+jsonResponse[i].visitorlist_date+'</a>';
					cell2.innerHTML = jsonResponse[i].visitorcount;
				}
				loading=false;
				document.getElementById("loading").style.display = "none";
				lastOffset = offset;
			} catch(err) {
				if(typeof err.message !== "undefined"){
					console.warn(err.message);
				}
				loading=false;
			}
		} else {
			console.warn(request.statusText, request.responseText);
			loading=false;
		}
	});
	request.send();
}
</script>
