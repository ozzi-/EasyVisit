<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
function init($name){ ?>
	<style>
	tfoot{
	    visibility: hidden;
	}
	.page-item.active .page-link{
	        background-color: <?= BRANDING_COLOR ?>;
	        border-color: #ddd;
	}
	.page-link{
	        color: <?= BRANDING_COLOR ?>;
	}
	.page-link:focus, .page-link:hover{
        	color: <?= BRANDING_COLOR ?>;
	}
	</style>
	<script>
	$(document).ready(function() {
	        $('#<?= $name ?>').dataTable( {
		  //"pageLength": 100,
		  "paging": false,
	          "language": {
	            //"paginate": {
	            //  "previous": "<?= translation_get("back") ?>",
	            //  "next": "<?= translation_get("next") ?>"
	            //},
	            "lengthMenu": "<?= translation_get("length_menu") ?>",
	            "zeroRecords": "<?= translation_get("no_entries") ?>",
	            "info": "<?= translation_get("info_page") ?>",
	            "infoEmpty": "<?= translation_get("info_page") ?>",
	            "infoFiltered": "",
	            "search": "<?= translation_get("search") ?>"
	          },
	          "fixedHeader": {
                    header: false,
	            footer: false
	          }
                });
	} );
</script>
<?php
}
?>
