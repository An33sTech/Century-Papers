<?php
ob_start();
require_once("classes/statics.class.php");
global $dbF;
$staticsC  =   new statics();

?>
    <h3 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Statics Reports']); ?></h3>

    <?php
        $staticsC->form();

        $staticsC->report();
    ?>



    <script>
        $(function(){
            tableHoverClasses();
            dateJqueryUi();
            dateRangePicker();
        });

    </script>
<?php return ob_get_clean(); ?>