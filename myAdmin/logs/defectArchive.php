<?php
ob_start();

require_once("classes/logs.class.php");
global $dbF;

$logs=new logs();
?>

<h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Defect Archive']); ?></h4>
<div class="container-fluid" >
    <?php $logs->defectView(); ?>
</div>
    <script src="logs/js/logs.php"></script>
<script>
    $(document).ready(function(){
        tableHoverClasses();
        dateJqueryUi();
        minMaxDate();
        dTableRangeSearch();
    });
</script>
<?php return ob_get_clean(); ?>