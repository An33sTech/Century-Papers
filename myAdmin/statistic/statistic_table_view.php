<?php
ob_start();

require_once("classes/statistic.class.php");
global $dbF;

$object  =   new statistics();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
// $object->generate_statistic_report();

    $date_range_selected = '';
if( isset($_POST['submit']) ){

    $date_range_from     = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : date('Y-m-d');
    $date_range_to       = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : '';

    # if no date to is supplied, then use this month's last date, (this month can be the selected month, or the default current month)
    if ( $date_range_to == '' ) {
        $end  = new DateTime( $date_range_from );
        $date_range_to = $end->format("Y-m-t");
    }

    $date_range_selected = ' (' . $date_range_from . ' - ' . $date_range_to . ')';
}

?>

<h3 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Statistics Report']); echo $date_range_selected; ?></h3>
    
<small style="color: red;font-size: large;">Leave Blank For Current Day Sales Statistics....<br><br><br>
                            </small>

<?php
    $object->generate_form($hide_filters = true);
?>


<?php $object->statistics_creation_daily(); ?>

<div class="col-sm-12">
    <br>
    <?php $object->table_view_print(); ?>
</div>





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