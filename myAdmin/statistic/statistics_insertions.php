<?php
ob_start();

require_once("classes/statistic.class.php");
global $dbF;

$object  =   new statistics();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;

?>

<h3 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Statistics Report']); ?></h3>
    

<?php
    $object->generate_form();
?>


<?php $object->statistics_creation_daily(); ?>
<?php $object->create_list_report(); ?>







<style>

    .tableIBMS th:last-child{
        min-width: initial;
    }

    .ui-autocomplete-loading {
        background: white url("../images/ui-anim_basic_16x16.gif") right center no-repeat;
    }

    .input_date {
        padding-left: 45px;
    }

    .input-group-addon-date {
        position: absolute;
        left: 15px;
        top: 0;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: normal;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

</style>

<script>
    $(function(){
        tableHoverClasses();
        // dateJqueryUi();
        // dateRangePicker();
        minMaxDate();
    });
</script>
<?php return ob_get_clean(); ?>