<?php
ob_start();
require_once("classes/order.php");
$order = new order();

$functions->sessionMsg();



if ($functions->developer_setting('product_Scale') == '0') {
    echo "<style>.allowProductScale{display:none;}</style>";
}

if ($functions->developer_setting('product_color') == '0') {
    echo "<style>.allowProductColor{display:none;}</style>";
}

$functions->includeAdminFile("product_management/classes/currency.class.php");
$c_currency = new currency_management();
$currency_data = $c_currency->getList(); // get currency list

foreach ($currency_data as $val) {
    $cur_id = $val['cur_id'];
    $cur_symbol = md5($val['cur_symbol']);
    echo '<input type="hidden" class="currIds" value="' . $cur_symbol . '" />';
}

?>


    <h4 class="sub_heading"><?php echo _uc('Relative Ready Orders'); ?></h4>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['InProcess Invoices']); ?></a></li>
       

    </ul>

    <!-- Tab panes -->

    <div class="tab-content">
 


        <div class="tab-pane fade in active container-fluid" id="home">
            <div class="heading_invoice">
                <h2 class="tab_heading">Relative Ready Orders</h2>

                
            </div>
            <?php $order->invoiceListRelativeReady('invoicesRelativeReady'); ?>
        </div>

   


     





    

    </div> <!-- tab-content div end-->

<?php //$functions->dialogCommon('dialog', 'Order View'); ?>

<style>

.dataTables_processing {
    position: fixed;
    top: 50%;
    left: 50%;
    border: none; 
    background: none;
}

.heading_invoice {
    position: relative;
}

.small_btn {
    position: absolute;
    right: 0;
    top: -30px;
}

@media (max-width: 992px) {

.small_btn{
    position: static;
    display: block;
    clear: both;
}

}

</style>

    <script src="order/js/order.php"></script>
  <!--   <script>
        $(function () {
            dateJqueryUi();
            // minMaxDate();
            // dTableRangeSearch();
            minMaxDateFilter();

            $(document).on('keydown', '.dataTables_filter input', function (event) {
                orderPrice();
            });

            $("#DataTables_Table_0_length_select,#DataTables_Table_1_length_select,#DataTables_Table_2_length_select,#min,#max").change(function () {
                orderPrice();
            });

            setTimeout(function () {
                orderPrice();
            }, 100);

        });

        function orderPrice() {
            setTimeout(function () {
                countOrderPrice('invoices');
                countOrderPrice('cancel');
                countOrderPrice('complete');
                countOrderPrice('all');
                countOrderPrice('incomplete');
            }, 500);
        }

    </script> -->
    <!-- using stock js same here.. -->

<script>

    // function fetch_ajax_result_again (dateCodeFrom, dateCodeTo) {

    //     my_dtable = $.fn.dataTable.tables( { visible: true, api: true } );
    //     $(my_dtable).DataTable().ajax.reload();


    // }


    // $('.dTableFull').on( 'draw.dt', function () {
    //     console.log( 'Table redrawn' );
    // });

</script>
<?php return ob_get_clean(); ?>