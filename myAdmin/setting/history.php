<?php
ob_start();

global $dbF;

$functions->require_once_custom('setting.class.php');
$setting = new setting();

echo '<h4 class="sub_heading borderIfNotabs">' . _uc($_e['IBMS History']) . '</h4>';

$functions->dataTableDateRange();

$setting->historyPrint();

$deleteDay = $functions->ibms_setting('historyDeleteAfterDays');
$days = date('Y-m-d', strtotime("-" . "$deleteDay days"));
$sql = "DELETE FROM activity_log WHERE log_time < '$days' ";
$dbF->setRow($sql);
?>


<script>
    $(document).ready(function(){
        tableHoverClasses();
        dateJqueryUi();
    });
</script>


<?php return ob_get_clean(); ?>