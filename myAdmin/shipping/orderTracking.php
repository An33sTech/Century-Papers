<?php
ob_start();

$functions->require_once_custom("shipping");
global $dbF;

$shipping   =   new shipping();
?>

    <h2  class="tab_heading"><?php echo _uc($_e['Order Tracking Number']); ?></h2>
    <?php
        $shipping->orderTrackingView();
    ?>




<script>
    $(document).ready(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>