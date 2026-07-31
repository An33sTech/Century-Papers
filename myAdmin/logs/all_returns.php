<?php
ob_start();

require_once("classes/logs.class.php");
global $dbF, $db, $functions;

$logs = new logs();

$storeId = $logs->productClass->getStoreId();
$new_order_product_price = 0;
function do_return_calculation($post_array, $data_array)
{
    global $logs, $new_order_product_price, $dbF, $functions;

    $pids = $data_array['order_pIds'];
    $pids = explode("-", $pids);
    $pId = $pids[0];
    $scaleId = $pids[1];
    $colorId = $pids[2];
    $storeId = $pids[3];
    $customId = $pids[4];
    $hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
    $hash = md5($hashVal);
    $sale_qty = $data_array['order_pQty'];
    $shippingCountry = $data_array['shippingCountry'];

    $new_product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $new_scaleId = isset($_POST['size_id']) ? (int) $_POST['size_id'] : 0;
    $new_colorId = isset($_POST['color_id']) ? (int) $_POST['color_id'] : 0;
    $new_storeId = isset($_POST['store_id']) ? (int) $_POST['store_id'] : 0;
    $new_currencyId = isset($_POST['cur_id']) ? (int) $_POST['cur_id'] : 0;

    $product_price = $data_array['order_pPrice'] - $data_array['order_discount'];
    $product_price_total = $product_price * $sale_qty;
    $order_total = $data_array['total_price'] - $data_array['ship_price'];

    $result = false;

    $return_type = $_POST['return_type'];

    switch ($return_type) {
        case 'refund':
            # product returned to stock and refunded
            $result = $logs->AddQtyBackInStock($pId, $scaleId, $colorId, $storeId, $sale_qty);

            $new_order_product_price = 0 - $product_price_total;

            $logs->all_in_one_return_email($_POST, $data_array);

            break;

        case 'defect':
            # defected product registered
            $result = true;

            $logs->all_in_one_return_email($_POST, $data_array);

            break;

        case 'change_product':
            # product changed
            $available_stock_quantity = $logs->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
            if ($available_stock_quantity < $sale_qty) {
                $hardword_text = $dbF->hardWords('Available Size / Color Stock Is {{avail_qty}}, Required Quantity Is {{req_qty}}', false);
                $hardword_text = _replace('{{avail_qty}}', $available_stock_quantity, _uc($hardword_text));
                $hardword_text = _replace('{{req_qty}}', $sale_qty, _uc($hardword_text));

                $functions->notificationError(_fu($dbF->hardWords('Product Change Failed!', false)), _fu($hardword_text), 'btn-danger');
                # break out, so result will be false
                break;
            }

            # get the new products price, with size and color
            $new_product_total_price = $logs->productF->productTotalPrice($new_product_id, $new_scaleId, $customId, $new_colorId, $shippingCountry, 'after');


            if (!empty($new_product_total_price)) {
                $new_product_total_price = filter_var(
                    $new_product_total_price,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            } else {


                $new_product_total_price = '0';
            }
            if (!empty($sale_qty)) {
                $sale_qty = filter_var(
                    $sale_qty,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            } else {


                $sale_qty = '0';
            }



            if (!empty($product_price_total)) {
                $product_price_total = filter_var(
                    $product_price_total,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            } else {


                $product_price_total = '0';
            }




            # multiply with order quantity
            $new_product_total_price = $new_product_total_price * $sale_qty;

            $new_order_product_price = $product_price_total - $new_product_total_price;

            $result = $logs->AddQtyBackInStock($pId, $scaleId, $colorId, $storeId, $sale_qty);


            $result = $logs->RemoveQtyFromInStock($new_product_id, $new_scaleId, $new_colorId, $new_storeId, $sale_qty);

            $logs->all_in_one_return_email($_POST, $data_array);

            break;

        case 'change_size':
            # change size / color

            $available_stock_quantity = $logs->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
            if ($available_stock_quantity < $sale_qty) {
                $hardword_text = $dbF->hardWords('Available Size / Color Stock Is {{avail_qty}}, Required Quantity Is {{req_qty}}', false);
                $hardword_text = _replace('{{avail_qty}}', $available_stock_quantity, _uc($hardword_text));
                $hardword_text = _replace('{{req_qty}}', $sale_qty, _uc($hardword_text));
                $functions->notificationError(_fu($dbF->hardWords('Size / Color Change Failed!', false)), _fu($hardword_text, false), 'btn-danger');

                # break out, so result will be false
                break;
            }

            # get the new products price, with size and color
            $new_product_total_price = $logs->productF->productTotalPrice($new_product_id, $new_scaleId, $customId, $new_colorId, $shippingCountry, 'after');


            if (!empty($new_product_total_price)) {
                $new_product_total_price = filter_var(
                    $new_product_total_price,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            } else {
                $new_product_total_price = '0';
            }
            if (!empty($sale_qty)) {
                $sale_qty = filter_var(
                    $sale_qty,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            } else {


                $sale_qty = '0';
            }

            if (!empty($product_price_total)) {
                $product_price_total = filter_var(
                    $product_price_total,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            } else {
                $product_price_total = '0';
            }

            # multiply with order quantity
            $new_product_total_price = $new_product_total_price * $sale_qty;

            $new_order_product_price = $product_price_total - $new_product_total_price;

            $result = $logs->RemoveQtyFromInStock($pId, $scaleId, $colorId, $storeId, $sale_qty);

            $logs->all_in_one_return_email($_POST, $data_array);
            break;

        default:
            # code...
            break;
    }



    return $result;
}


$print = 'false';


if (isset($_POST['submit']) && $functions->getFormToken('all_in_one_return_form')) {
    foreach ($_POST['insert']['order_product'] as $order_invoice_product_id) {
        ## get the ordered product invoice
        $sql = " SELECT * FROM `order_invoice_product` LEFT OUTER JOIN order_invoice ON `order_invoice_product`.`order_invoice_id` = `order_invoice`.`order_invoice_pk` WHERE `invoice_product_pk` = ? ";
        $data = $dbF->getRow($sql, array($order_invoice_product_id));

        $pids = $data['order_pIds'];
        $pids = explode("-", $pids);
        $pId = $pids[0];
        $scaleId = $pids[1];
        $colorId = $pids[2];
        $storeId = $pids[3];
        $customId = $pids[4];
        $hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
        $hash = md5($hashVal);
        $sale_qty = $data['order_pQty'];

        $new_product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $new_scaleId = isset($_POST['size_id']) ? (int) $_POST['size_id'] : 0;
        $new_colorId = isset($_POST['color_id']) ? (int) $_POST['color_id'] : 0;
        $new_storeId = isset($_POST['store_id']) ? (int) $_POST['store_id'] : 0;
        $new_currencyId = isset($_POST['cur_id']) ? (int) $_POST['cur_id'] : 0;

        $product_price = $data['order_pPrice'] - $data['order_discount'];
        $product_price_total = $product_price * $sale_qty;
        $calc_return = do_return_calculation($_POST, $data);



        if ($_POST['return_type'] == 'refund') {
            $PID = $pids[0];
            $print = 'true';
        }

        $refunds_array = array('refund' => 2, 'defect' => 3, 'change_product' => 4, 'change_size' => 5);

        if ($calc_return) {

            $refunds_array_text = array(
                'refund' => $dbF->hardWords('Refund Submitted Successfully', false),
                'defect' => $dbF->hardWords('Defected Product Submitted Successfully', false),
                'change_product' => $dbF->hardWords('Changed Product Submitted Successfully', false),
                'change_size' => $dbF->hardWords('Changed Size / Color / Submitted Successfully', false)
            );
            $hard_word = $refunds_array_text[$_POST['return_type']];

            # removing invoice start text from order number.
            $invoiceKey = $functions->ibms_setting('invoice_key_start_with');
            $order_id = str_replace($invoiceKey, '', $_POST['order_id']);

            $to_insert['order_invoice_id'] = $order_id;
            $to_insert['invoice_product_id'] = $order_invoice_product_id;
            $to_insert['type'] = $refunds_array[$_POST['return_type']];
            $to_insert['price_code'] = $data['price_code'];
            $to_insert['new_amount'] = $new_order_product_price; // $new_order_product_price is a global
            $to_insert['new_pId'] = $new_product_id;
            $to_insert['new_color'] = $new_colorId;
            $to_insert['new_size'] = $new_scaleId;
            $to_insert['old_pId'] = $pId;
            $to_insert['old_size'] = $logs->productF->getScaleName($scaleId);
            $to_insert['old_color'] = $logs->productF->getColorName($colorId);
            $to_insert['hash'] = $data['order_hash'];
            $to_insert['old_order_product_price'] = $data['order_pPrice'];
            $to_insert['old_order_product_discount'] = $data['order_discount'];
            $to_insert['sale_qty'] = $sale_qty;
            $to_insert['date'] = date('Y-m-d');



            $insert_result = $functions->formInsert('all_in_one_returns', $to_insert);
            if ($insert_result > 0) {
                $heading_hardword = $dbF->hardWords('Ordered Product Invoice # {{id}} Submission Success', false);
                $heading_hardword = _replace('{{id}}', $order_invoice_product_id, _uc($heading_hardword));
                $functions->notificationError(_fu($heading_hardword), _fu($hard_word), 'btn-success');
            }

            $sql = " UPDATE order_invoice_product SET order_process = ? WHERE invoice_product_pk = ? ";
            $dbF->setRow($sql, array($refunds_array[$_POST['return_type']], $order_invoice_product_id));
        }
    }
}



if (isset($_POST['submit']) && $functions->getFormToken('all_in_one_return_form123')) {
    $new_product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

    $new_scaleId = isset($_POST['size_id']) ? (int) $_POST['size_id'] : 0;

    $new_colorId = isset($_POST['color_id']) ? (int) $_POST['color_id'] : 0;

    $new_storeId = isset($_POST['store_id']) ? (int) $_POST['store_id'] : 0;

    $new_currencyId = isset($_POST['cur_id']) ? (int) $_POST['cur_id'] : 0;



    $order_invoice_product_id = $_POST['order_product123'];

    $pids = explode("::", $order_invoice_product_id);

    $pId = $order_invoice_product_id;
    $scaleId = $new_scaleId;
    $colorId = $new_colorId;
    $storeId = $new_storeId;
    $customId = 0;
    $hashVal = $pids[0] . ":" . $pids[1] . ":" . $colorId . ":" . $storeId;
    $hash = md5($hashVal);
    $sale_qty = 1;

    $new_product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;

    $result = false;


    $result = $logs->AddQtyBackInStock($pids[0], $pids[1], $colorId, $storeId, $sale_qty);




    if ($_POST['return_type'] == 'refund') {
        $PID = $pids[0];
        $print123 = 'true';
    }

    $refunds_array = array('refund' => 2, 'defect' => 3, 'change_product' => 4, 'change_size' => 5);

    if ($result) {

        $refunds_array_text = array(
            'refund' => $dbF->hardWords('Refund Submitted Successfully', false),
            'defect' => $dbF->hardWords('Defected Product Submitted Successfully', false),
            'change_product' => $dbF->hardWords('Changed Product Submitted Successfully', false),
            'change_size' => $dbF->hardWords('Changed Size / Color / Submitted Successfully', false)
        );

        $to_insert['invoice_product_id'] = $pids[0];
        $to_insert['type'] = $refunds_array[$_POST['return_type']];
        $to_insert['new_pId'] = $new_product_id;
        $to_insert['new_color'] = $new_colorId;
        $to_insert['new_size'] = $new_scaleId;
        $to_insert['old_pId'] = $pids[0];
        $to_insert['old_size'] = $logs->productF->getScaleName($pids[1]);
        $to_insert['old_color'] = $logs->productF->getColorName($colorId);
        $to_insert['sale_qty'] = "1";
        $to_insert['date'] = date('Y-m-d');

        if (empty($_POST['order_product123456'])) {
            # code...

            $insert_result = $functions->formInsert('all_in_one_returns', $to_insert);
            if ($insert_result > 0) {
                $heading_hardword = $dbF->hardWords('Save', false);
                $pMmsg = $dbF->hardWords('Replacement / Return Submit Successfully', false);

                $functions->notificationError(_fu($heading_hardword), _fu($pMmsg), 'btn-success');
            }
        }
    }

    $order_invoice_product_id123 = $_POST['order_product123456'];

    $new_product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

    $new_scaleId = isset($_POST['size_id']) ? (int) $_POST['size_id'] : 0;

    $new_colorId = isset($_POST['color_id']) ? (int) $_POST['color_id'] : 0;

    $new_storeId = isset($_POST['store_id']) ? (int) $_POST['store_id'] : 0;

    $new_currencyId = isset($_POST['cur_id']) ? (int) $_POST['cur_id'] : 0;

    $pids1 = explode("::", $order_invoice_product_id123);

    $pId = $order_invoice_product_id123;
    $scaleId = $new_scaleId;
    $colorId = $new_colorId;
    $storeId = $new_storeId;
    $customId = 0;
    $hashVal = $pids[0] . ":" . $pids[1] . ":" . $colorId . ":" . $storeId;
    $hash = md5($hashVal);
    $sale_qty = 1;

    $new_product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;

    $result = false;


    $result = $logs->RemoveQtyFromInStock($pids[0], $pids[1], $colorId, $storeId, $sale_qty);

    $refunds_array = array('refund' => 2, 'defect' => 3, 'change_product' => 4, 'change_size' => 5);

    if ($result) {

        $refunds_array_text = array(
            'refund' => $dbF->hardWords('Refund Submitted Successfully', false),
            'defect' => $dbF->hardWords('Defected Product Submitted Successfully', false),
            'change_product' => $dbF->hardWords('Changed Product Submitted Successfully', false),
            'change_size' => $dbF->hardWords('Changed Size / Color / Submitted Successfully', false)
        );

        $to_insert['invoice_product_id'] = $pids[0];
        $to_insert['type'] = $refunds_array[$_POST['return_type']];
        $to_insert['new_pId'] = $pids1[0];
        $to_insert['new_color'] = 0;

        $new_size = isset($pids1[1]) ? (int) $pids1[1] : 0;




        $to_insert['new_size'] = $new_size;
        $to_insert['old_pId'] = $pids[0];
        $to_insert['old_size'] = $logs->productF->getScaleName($pids[1]);
        $to_insert['old_color'] = $logs->productF->getColorName($colorId);
        $to_insert['sale_qty'] = "1";
        $to_insert['date'] = date('Y-m-d');

        if (!empty($_POST['order_product123456']) && !empty($_POST['order_product123'])) {


            $insert_result = $functions->formInsert('all_in_one_returns', $to_insert);
            if ($insert_result > 0) {
                $heading_hardword = $dbF->hardWords('Save', false);
                $pMmsg = $dbF->hardWords('Replacement / Return Submit Successfully', false);

                $functions->notificationError(_fu($heading_hardword), _fu($pMmsg), 'btn-success');
            }
        }
    }
}


?>



<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Add Entry']); ?></a></li>
    <li><a href="#view_entries" role="tab" data-toggle="tab"><?php echo _uc($_e['View Entries']); ?></a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">

    <div class="tab-pane fade in active container-fluid" id="home">
        <h2 class="tab_heading"><?php echo _uc($_e['All In One Product Returns']); ?></h2>




        <ul class="nav nav-tabs tabs_arrow" role="tablist">
            <li class="active"><a href="#wi" role="tab" data-toggle="tab"><?php echo _uc($_e['With Invoice']); ?></a>
            </li>
            <li><a href="#woi" role="tab" data-toggle="tab"><?php echo _uc($_e['With Out Invoice']); ?></a></li>
        </ul>





        <div class="tab-content">

            <div class="tab-pane fade in active container-fluid" id="wi">

                <div class="col-sm-12">
                    <br>

                    <form id="returns_reg_form" class="form-horizontal" action="" method="post"
                        enctype="multipart/form-data">
                        <?php
                        $functions->setFormToken('all_in_one_return_form');
                        ?>
                        <input id="product_id" type="hidden" name="product_id" value="">
                        <input id="store_id" type="hidden" name="store_id" value="<?php echo $storeId; ?>">
                        <input id="cur_id" type="hidden" name="cur_id" value="">
                        <input id="color_id" type="hidden" name="color_id" value="">
                        <input id="size_id" type="hidden" name="size_id" value="">

                        <div class="form-group">
                            <label
                                class="col-sm-5 control-label"><?php echo _uc($_e['Type your order to proceed']); ?></label>
                            <div class="col-sm-7">
                                <input id="order_id" type="text" class="form-control" name="order_id" value=""
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group" id="products_area" style="display:none">
                            <label class="col-sm-5 control-label"><?php echo _uc($_e['Choose products']); ?></label>
                            <div class="col-sm-7" id="products_area_insert">

                            </div>
                        </div>

                        <div class="form-group" id="choose_return_type" style="display:none">
                            <label class="col-sm-5 control-label"><?php echo _uc($_e['Choose return type']); ?></label>
                            <div class="col-sm-7" id="">
                                <select id="return_type" name="return_type" class="form-control" required
                                    autocomplete="off">
                                    <option value="">------------</option>
                                    <option value="refund"><?php echo _uc($_e['Refund']); ?></option>
                                    <option value="defect"><?php echo _uc($_e['Defect']); ?></option>
                                    <option value="change_product"><?php echo _uc($_e['Change product']); ?></option>
                                    <option value="change_size"><?php echo _uc($_e['Change size/color']); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="product_select_area" style="display:none">
                            <label
                                class="col-sm-5 control-label"><?php echo _uc($_e['Type product name to select product']); ?></label>
                            <div class="col-sm-7">
                                <input id="product_name" type="text" class="form-control typeahead" name="product_name"
                                    value="" autocomplete="off">
                            </div>
                        </div>

                        <div id="size_color_area">


                        </div>

                        <div class="form-group">
                            <label class="col-sm-5 control-label"></label>
                            <div class="col-sm-7">
                                <input type="button" name="button" value="<?php echo _uc($_e['Continue']); ?>"
                                    id="order_continue" class="btn btn-success">
                                <input type="submit" name="submit" value="<?php echo _uc($_e['Send']); ?>"
                                    id="order_submit" class="btn btn-success">
                            </div>
                        </div>

                        <?php


                        if ($print == 'true') {

                            global $_e;


                            $sql = 'SELECT order_invoice_id FROM order_invoice_product WHERE `order_pIds` LIKE "' . $PID . '%" order by invoice_product_pk desc';
                            $row = $dbF->getRows($sql);

                            if ($dbF->rowCount > 0) {

                                $recordsTotal = $logs->get_total_rows($row);

                                echo '<div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    
                       <th>' . _u($_e['SNO']) . '</th>
                       <th>' . _u($_e['INVOICE']) . '</th>
                       <th>' . _u($_e['PRODUCT']) . '</th> 
                       
                       <th>' . _u($_e['COUNTRY']) . '</th> 
                       <th>' . _u($_e['PHONE']) . '</th>
                       <th>' . _u($_e['INVOICE DATE']) . '</th>
                       
                       <th>' . _u($_e['INVOICE STATUS']) . '</th>
                       <th>' . _u($_e['ACTION']) . '</th>
                       
                       
                    </thead>
                <tbody>';


                                $i = 0;

                                foreach ($row as $value) {

                                    $sql1 = 'SELECT * From order_invoice_product as oip LEFT JOIN order_invoice as oi on oip.order_invoice_id = oi.order_invoice_pk where order_invoice_id = ' . $value["order_invoice_id"] . ' AND oi.orderStatus != "inComplete" AND oi.invoice_status != "3" AND oi.invoice_status != "0" order by oip.order_invoice_id desc';
                                    $row1 = $dbF->getRows($sql1);

                                    if (!empty($row1)) {

                                        $i++;

                                        $divInvoice = '';
                                        $invoiceStatus = $logs->productF->invoiceStatusFind($row1[0]['invoice_status']);
                                        $st = $row1[0]['invoice_status'];
                                        $onclick = " onclick= 'show_quick_invoice(this);' ";
                                        if ($st == 0)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else if ($st == 1)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else if ($st == 2)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else if ($st == 3)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";



                                        $invoiceDate = date('Y-m-d H:i:s', strtotime($row1[0]['invoice_date']));
                                        $invoiceId = $row1[0]['order_invoice_pk'];

                                        $country = $row1[0]['shippingCountry'];

                                        $orderInfo = $logs->orderInvoiceInfo($invoiceId);
                                        $orderUser_id = $row1['orderUser'];
                                        $customer_email = $orderInfo['sender_email'];
                                        $customer_Name = $orderInfo['sender_name'];
                                        $sender_address = $orderInfo['sender_address'];
                                        $sender_phone = $orderInfo['sender_phone'];


                                        $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";


                                        if ($row1[0]['order_process'] == 1) {



                                            $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                                        }



                                        $action = "
            <a href='?pId=$invoiceId' id='orderProcess' data-method='post' data-action='" . WEB_URL . "/myAdmin/-order?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a> 
            ";



                                        echo '<tr>
            <td>' . $i . '</td>
            <td>' . $row1[0]["invoice_id"] . '</td>
            <td>';
                                        foreach ($row1 as $pName) {
                                            echo $pName["order_pName"] . ' <br> ';
                                        }
                                        echo '</td>
            <td>' . $country . '</td>
            <td>' . $sender_phone . '</td>
            <td>' . $invoiceDate . '</td>
            
            <td>' . $divInvoice . '</td>
            <td>' . $action . '</td>
            </tr>';
                                    }
                                }

                                echo '</tbody>
             </table>
            </div> <!--table-responsive End -->';
                            } else {
                                echo "<div style='font-size: 18px;text-align: center;margin: 100px;'>No Record Found</div>";
                            }
                        }




                        ?>

                    </form>


                    <div id="inprocess_prod_div" style="margin-top: 50px;display:none;">
                        <?php
                        global $_e;

                        echo '<div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    
                       <th>' . _u($_e['SNO']) . '</th>
                       <th>' . _u($_e['INVOICE']) . '</th>
                       <th>' . _u($_e['PRODUCT']) . '</th> 
                       
                       <th>' . _u($_e['COUNTRY']) . '</th> 
                       <th>' . _u($_e['PHONE']) . '</th>
                       <th>' . _u($_e['INVOICE DATE']) . '</th>
                       
                       <th>' . _u($_e['INVOICE STATUS']) . '</th>
                      
                       
                       
                    </thead>
                <tbody>
                
                </tbody>
             
             </table>
            </div> <!--table-responsive End -->';

                        ?>

                    </div>

                    <div class="inprocess" style="margin-top: 100px;">
                        <input type="submit" name="submit" value="Show Order of Same Product" id="inprocess"
                            class="btn btn-success">
                    </div>

                </div>

            </div>



            <div class="tab-pane container-fluid" id="woi">

                <div class="col-sm-12">
                    <br>

                    <form id="returns_reg_form123" class="form-horizontal"
                        action="<?= WEB_URL ?>/myAdmin/-logs?page=all_returns#woi" method="post"
                        enctype="multipart/form-data">
                        <?php
                        $functions->setFormToken('all_in_one_return_form123');
                        ?>
                        <input id="product_id123" type="hidden" name="product_id" value="">
                        <input id="store_id" type="hidden" name="store_id" value="<?php echo $storeId; ?>">
                        <input id="cur_id123" type="hidden" name="cur_id" value="">
                        <input id="color_id" type="hidden" name="color_id" value="">
                        <input id="size_id" type="hidden" name="size_id" value="">

                        <div class="form-group" style="display:none">
                            <label
                                class="col-sm-5 control-label"><?php echo _uc($_e['Type your order to proceed']); ?></label>
                            <div class="col-sm-7">
                                <input id="order_id" type="text" class="form-control" name="order_id" value=""
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-5 control-label"><?php echo _uc($_e['Choose products']); ?></label>
                            <div class="col-sm-7" id="products_area_insert123">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label"><?php echo _uc($_e['Choose return type']); ?></label>
                            <div class="col-sm-7" id="">
                                <select id="return_type" name="return_type" class="form-control" required
                                    autocomplete="off">
                                    <option value="">------------</option>
                                    <option value="refund"><?php echo _uc($_e['Refund']); ?></option>
                                    <option value="defect"><?php echo _uc($_e['Defect']); ?></option>
                                    <option value="change_product"><?php echo _uc($_e['Change product']); ?></option>
                                    <option value="change_size"><?php echo _uc($_e['Change size/color']); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="product_select_area123456" style="display:none">
                            <label
                                class="col-sm-5 control-label"><?php echo _uc($_e['Type product name to select product']); ?></label>



                            <div class="col-sm-7" id="products_area_insert123456">

                            </div>


                        </div>

                        <div class="form-group">
                            <label class="col-sm-5 control-label"></label>
                            <div class="col-sm-7">

                                <input type="submit" name="submit" value="<?php echo _uc($_e['Save']); ?>"
                                    id="order_submit" class="btn btn-success">
                            </div>
                        </div>

                        <?php


                        if (isset($print123) && $print123 == 'true') {

                            global $_e;


                            $sql = 'SELECT order_invoice_id FROM order_invoice_product WHERE `order_pIds` LIKE "' . $PID . '%" order by invoice_product_pk desc';
                            $row = $dbF->getRows($sql);

                            if ($dbF->rowCount > 0) {

                                $recordsTotal = $logs->get_total_rows($row);

                                echo '<div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    
                       <th>' . _u($_e['SNO']) . '</th>
                       <th>' . _u($_e['INVOICE']) . '</th>
                       <th>' . _u($_e['PRODUCT']) . '</th> 
                       
                       <th>' . _u($_e['COUNTRY']) . '</th> 
                       <th>' . _u($_e['PHONE']) . '</th>
                       <th>' . _u($_e['INVOICE DATE']) . '</th>
                       
                       <th>' . _u($_e['INVOICE STATUS']) . '</th>
                       <th>' . _u($_e['ACTION']) . '</th>
                       
                       
                    </thead>
                <tbody>';

                                $i = 0;

                                foreach ($row as $value) {

                                    $sql1 = 'SELECT * From order_invoice_product as oip LEFT JOIN order_invoice as oi on oip.order_invoice_id = oi.order_invoice_pk where order_invoice_id = ' . $value["order_invoice_id"] . ' AND oi.orderStatus != "inComplete" AND oi.invoice_status != "3" AND oi.invoice_status != "0" order by oip.order_invoice_id desc';
                                    $row1 = $dbF->getRows($sql1);

                                    if (!empty($row1)) {

                                        $i++;

                                        $divInvoice = '';
                                        $invoiceStatus = $logs->productF->invoiceStatusFind($row1[0]['invoice_status']);
                                        $st = $row1[0]['invoice_status'];
                                        $onclick = " onclick= 'show_quick_invoice(this);' ";
                                        if ($st == 0)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else if ($st == 1)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else if ($st == 2)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else if ($st == 3)
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
                                        else
                                            $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";


                                        $invoiceDate = date('Y-m-d H:i:s', strtotime($row1[0]['invoice_date']));
                                        $invoiceId = $row1[0]['order_invoice_pk'];

                                        $country = $row1[0]['shippingCountry'];

                                        $orderInfo = $logs->orderInvoiceInfo($invoiceId);
                                        $orderUser_id = $row1['orderUser'];
                                        $customer_email = $orderInfo['sender_email'];
                                        $customer_Name = $orderInfo['sender_name'];
                                        $sender_address = $orderInfo['sender_address'];
                                        $sender_phone = $orderInfo['sender_phone'];

                                        $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";


                                        if ($row1[0]['order_process'] == 1) {
                                            $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                                        }


                                        $action = "
            <a href='?pId=$invoiceId' id='orderProcess' data-method='post' data-action='" . WEB_URL . "/myAdmin/-order?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a>
            ";



                                        echo '<tr>
            <td>' . $i . '</td>
            <td>' . $row1[0]["invoice_id"] . '</td>
            <td>';
                                        foreach ($row1 as $pName) {
                                            echo $pName["order_pName"] . ' <br> ';
                                        }
                                        echo '</td>
            <td>' . $country . '</td>
            <td>' . $sender_phone . '</td>
            <td>' . $invoiceDate . '</td>
            
            <td>' . $divInvoice . '</td>
            <td>' . $action . '</td>
            </tr>';
                                    }
                                }

                                echo '</tbody>
             </table>
            </div> <!--table-responsive End -->';
                            } else {
                                echo "<div style='font-size: 18px;text-align: center;margin: 100px;'>No Record Found</div>";
                            }
                        }




                        ?>

                    </form>



                    <div id="inprocess_prod_div123" style="margin-top: 50px;display:none;">
                        <?php
                        global $_e;

                        echo '<div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    
                       <th>' . _u($_e['SNO']) . '</th>
                       <th>' . _u($_e['INVOICE']) . '</th>
                       <th>' . _u($_e['PRODUCT']) . '</th> 
                       
                       <th>' . _u($_e['COUNTRY']) . '</th> 
                       <th>' . _u($_e['PHONE']) . '</th>
                       <th>' . _u($_e['INVOICE DATE']) . '</th>
                       
                       <th>' . _u($_e['INVOICE STATUS']) . '</th>
                       
                       
                       
                    </thead>
                <tbody>
                
                </tbody>
             
             </table>
            </div> <!--table-responsive End -->';

                        ?>

                    </div>

                    <div class="inprocess" style="margin-top: 100px;">
                        <input type="submit" name="submit" value="Show Order of Same Product" id="inprocess123"
                            class="btn btn-success">
                    </div>


                </div>

            </div>


        </div>

    </div>

    <div class="tab-pane container-fluid fade" id="view_entries">
        <h2 class="tab_heading"><?php echo _uc($_e['All In One Product Returns']); ?></h2>


        <div class="container-fluid">
            <?php $logs->all_in_one_product_returns_view(); ?>
        </div>


    </div>

</div>

<style>
    .color_div {
        width: 20px;
        height: 20px;
        display: inline-block;
        vertical-align: bottom;
    }

    .ui-autocomplete-loading {
        background: white url("../images/ui-anim_basic_16x16.gif") right center no-repeat;
    }

    .grow:hover,
    .grow:focus,
    .grow:active {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    .grow {
        display: inline-block;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -webkit-transition-property: transform;
        transition-property: transform;
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        box-shadow: 0 0 1px rgba(0, 0, 0, 0);
    }

    #size_color label {
        width: 49px;
        height: 41px !important;
        border: 1px solid #cccccc !important;
    }

    .colors_in_divs {
        width: 100%;
        height: 100%;
        border: 0px !important;
    }

    .size_in_divs {
        display: inline-block;
    }

    #size_radio label:hover,
    #size_radio label:active {
        border: 1px solid #7cbe35 !important;
        color: #7cbe35 !important;
    }

    #size_radio label {
        display: inline-block;
        background: #eee;
        border: 1px solid #aaa;
        height: 26px;
        margin-right: 10px;
        text-align: center;
        min-width: 30px;
        font-size: 13px;
        line-height: 24px;
        color: #000;
        border-radius: 0px;
        cursor: pointer;
        transition: all ease-in-out 200ms;
        -webkit-transition: all ease-in-out 200ms;
        -moz-transition: all ease-in-out 100ms;
        -o-transition: all ease-in-out 200ms;
        -ms-transition: all ease-in-out 200ms;
        -webkit-box-shadow: 0px 1px 1px 0px #9C9C9C;
        -moz-box-shadow: 0px 1px 1px 0px #9c9c9c;
        box-shadow: 0px 1px 1px 0px #9C9C9C;
    }

    #size_radio label {
        height: 49px !important;
        min-width: 49px !important;
        font-size: 15px !important;
        line-height: 49px !important;
        padding: 0px 0px !important;
        background: none !important;
        border: 1px solid transparent !important;
        box-shadow: none !important;
        border-radius: 50px !important;
    }

    .productRadioHidden:checked+.bgSizeActive label {
        color: #7cbe35 !important;
        background: transparent !important;
        padding: 0 13px;
        border: 1px solid #7cbe35 !important;
    }

    .productRadioHidden:checked+.bgColorActive label {
        -webkit-transform: scale(1.2);
        transform: scale(1.2);
    }

    #size_color_area {
        margin-bottom: 10px;
    }

    #size_color_area label.control-label {
        padding-top: 15px;
    }



    #size_color_area123 {
        margin-bottom: 10px;
    }

    #size_color_area123 label.control-label {
        padding-top: 15px;
    }



    #size_radio,
    #size_color {
        margin-bottom: 10px;
    }
</style>

<script>
    $('#inprocess').click(function(e) {

        e.preventDefault();


        let selctProductId = $('#order_product').val();

        console.log(selctProductId);

        $.ajax({
                url: 'logs/logs_ajax.php?page=get_inprocess_prod',
                type: 'POST',
                dataType: 'html',
                data: {
                    invoice_id: selctProductId
                },
            })
            .done(function(res) {
                console.log(res);
                $('#inprocess_prod_div tbody').html(res)
                $('#inprocess_prod_div').css("display", "block")
            })

    })

    $('#inprocess123').click(function(e) {

        e.preventDefault();


        let selctProductId = $('#order_product123').val();

        console.log(selctProductId);

        $.ajax({
                url: 'logs/logs_ajax.php?page=get_inprocess_prod',
                type: 'POST',
                dataType: 'html',
                data: {
                    id: selctProductId
                },
            })
            .done(function(res) {
                console.log(res);
                $('#inprocess_prod_div123 tbody').html(res)
                $('#inprocess_prod_div123').css("display", "block")
            })

    })



    function get_currency(order_id) {

        $.ajax({
                url: '../ajax_call.php?page=get_currency',
                type: 'POST',
                dataType: 'text',
                data: {
                    order: order_id
                },
            })
            .done(function(data) {
                console.log("success");
                $('#cur_id').val(data);


                $('#cur_id123').val(data);


            });

    }

    $(function() {


        $("#order_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "logs/logs_ajax.php?page=get_process_orders&callback=?",
                    dataType: "jsonp",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                        <?php // don't change response function name, custom function does not work for now. 
                        ?>
                    }

                });
            },
            minLength: 3
        });
    });
</script>

<script>
    $("#order_id").on("autocompletechange", function(event, ui) {
        show_hide_calculation();
        get_currency($(this).val());
    });

    function show_hide_calculation() {
        /* Act on the event */
        console.log('Changed!');
        if ($('#order_continue').prop('disabled') == true && $('#order_id') != '') {
            $('#order_continue').prop('disabled', '');
        };
    }


    $('#returns_reg_form').on('click', '#order_continue', function(event) {
        event.preventDefault();
        /* Act on the event */

        var order_id_value = $('#order_id').val();

        if (order_id_value != '') {


            console.log('Clicked!');


            $.ajax({
                    url: '../ajax_call.php?page=orderProducts',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        order_id: order_id_value
                    },
                })
                .done(function(data) {
                    console.log(data);
                    $('#order_id').hide();
                    $('#products_area').show();
                    $('#products_area_insert').html(data);
                    $('#order_continue').prop('disabled', 'true');
                    $('#choose_return_type').show();








                    $('.test').multiselect({
                        includeSelectAllOption: true,
                        enableHTML: true,
                        filterPlaceholder: 'Search for something...',
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        maxHeight: 450,
                        optionLabel: function(element) {

                            return '<img src="' + $(element).attr('data-img') + '"  width="100"  height="100"> ' + $(element).text();
                        },
                    });





                });

        };



        $(document).ready(function() {

            $('#example-getting-started, #example-getting-started1, #example-getting-started2').multiselect({
                includeSelectAllOption: true,
                enableHTML: true,
                filterPlaceholder: 'Search for something...',
                enableFiltering: true,
                maxHeight: 450,
                optionLabel: function(element) {
                    return '<img src="' + $(element).attr('data-img') + '"> ' + $(element).text();
                },
            });

            $('#newDealProduct').multiselect({
                includeSelectAllOption: true,
                enableHTML: true,
                filterPlaceholder: 'Search for something...',
                maxHeight: 450,
                enableFiltering: true
            });

            $('.salesFeatureSetting').multiselect({
                includeSelectAllOption: true,
                enableHTML: true,
                maxHeight: 450,
                filterPlaceholder: 'Search for something...',
                enableFiltering: true
            });

        });





    });
</script>
<script>
    $(document).ready(function() {
        tableHoverClasses();
        dateJqueryUi();





        $.ajax({
                url: '../ajax_call.php?page=allPublishProducts_with_size_color',
                type: 'POST',
                dataType: 'html',
                data: {
                    order_ids: "not_in_use"
                },
            })
            .done(function(data) {
                $('#products_area_insert123').html(data);
                $('.test').multiselect({
                    includeSelectAllOption: true,
                    enableHTML: true,
                    filterPlaceholder: 'Search for something...',
                    enableFiltering: true,
                    maxHeight: 450,
                    enableCaseInsensitiveFiltering: true,
                    optionLabel: function(element) {

                        return '<img src="' + $(element).attr('data-img') + '"  width="100"  height="100"> ' + $(element).text();
                    },
                });

            });






        $.ajax({
                url: '../ajax_call.php?page=allPublishProducts_with_size_color1',
                type: 'POST',
                dataType: 'html',
                data: {
                    order_ids: "not_in_use"
                },
            })
            .done(function(data) {
                $('#products_area_insert123456').html(data);
                $('.test132456').multiselect({
                    includeSelectAllOption: true,
                    enableHTML: true,
                    filterPlaceholder: 'Search for something...',
                    enableFiltering: true,
                    maxHeight: 450,
                    enableCaseInsensitiveFiltering: true,
                    optionLabel: function(element) {
                        return '<img src="' + $(element).attr('data-img') + '"  width="100"  height="100"> ' + $(element).text();
                    },
                });

            });


    });

    function returnFormDel(ths) {
        btn = $(ths);
        if (secure_delete()) {
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id = btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'logs/logs_ajax.php?page=returnFormDel&id=' + id,
                data: {
                    id: id
                }
            }).done(function(data) {
                ift = true;
                if (data == '1') {
                    ift = false;
                    btn.closest('tr').hide(1000, function() {
                        $(this).remove()
                    });
                } else if (data == '0') {
                    jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
                } else {
                    jAlertifyAlert(data);
                }

                if (ift) {
                    btn.removeClass('disabled');
                    btn.children('.trash').show();
                    btn.children('.waiting').hide();
                }
            });
        }
    };

    function showLoadingMask(target, boolean) {
        // body...
        if (boolean === true) {
            $(target).addClass('ui-autocomplete-loading');
        } else {
            $(target).removeClass('ui-autocomplete-loading');
        };

    }

    $("input.typeahead").typeahead({
        onSelect: function(item) {
            $('#product_id').val(item.value);
            console.log(item.value);
            get_product_scale_color();
        },
        ajax: {
            url: "../ajax_call.php?page=searched_products",
            dataType: 'JSON',
            timeout: 500,
            triggerLength: 1,
            method: "post",
            displayField: "title",
            loadingClass: "loading-circle",
            preDispatch: function(query) {
                showLoadingMask('#product_name', true);
                return {
                    search: query
                }
            },
            preProcess: function(data) {
                showLoadingMask('#product_name', false);
                return data;
            }
        }
    });


    $("input.typeahead123").typeahead({
        onSelect: function(item) {
            $('#product_id123').val(item.value);
            console.log(item.value);
            get_product_scale_color123();
        },
        ajax: {
            url: "../ajax_call.php?page=searched_products",
            dataType: 'JSON',
            timeout: 500,
            triggerLength: 1,
            method: "post",
            displayField: "title",
            loadingClass: "loading-circle",
            preDispatch: function(query) {
                showLoadingMask('#product_name123', true);
                return {
                    search: query
                }
            },
            preProcess: function(data) {
                showLoadingMask('#product_name123', false);
                return data;
            }
        }
    });

    function get_product_scale_color() {

        var product_id = $('#product_id').val();
        var cur_id = $('#cur_id').val();


        $.ajax({
                url: '../ajax_call.php?page=sizes_colors',
                type: 'POST',
                dataType: 'html',
                data: {
                    product: product_id,
                    currency: cur_id
                },
            })
            .done(function(data) {
                $('#size_color_area').html(data);
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

    }

    function get_product_scale_color123() {

        var product_id = $('#product_id123').val();
        var cur_id = $('#cur_id123').val();


        $.ajax({
                url: '../ajax_call.php?page=sizes_colors_radio',
                type: 'POST',
                dataType: 'html',
                data: {
                    product: product_id,
                    currency: cur_id
                },
            })
            .done(function(data) {
                $('#size_color_area123').html(data);
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

    }

    function productColorPriceUpdate() {
        // body...
    }

    function return_type_calculation() {
        <?php // for onload hiding submit button 
        ?>
        if ($('#return_type').val() == '') {
            $('#order_submit').prop('disabled', true).hide();
        };

        $('#returns_reg_form').on('change', '#return_type', function(event) {
            /* Act on the event */
            console.log(this.value);

            if (this.value == 'change_product' || this.value == 'change_size') {
                $('#order_product').prop('multiple', false);
                $('#product_select_area').show();
                $('#order_submit').prop('disabled', false).show();
                $('#product_name').prop('required', true);

            } else {
                $('#order_product').prop('multiple', true);
                $('#product_select_area').hide();
                $('#order_submit').prop('disabled', false).show();
                $('#product_name').prop('required', false);
            };

        });




        $('#returns_reg_form123').on('change', '#return_type', function(event) {
            /* Act on the event */
            console.log(this.value);

            if (this.value == 'change_product' || this.value == 'change_size') {
                $('#product_select_area123456').show();

            } else {
                $('#product_select_area123456').hide();
            };

        });



    }
    return_type_calculation();

    function productPriceUpdate(product_id) {

        setTimeout(function() {
            var pId = product_id;
            var storeId = $('#store_id').val();
            var size = $('.sizeSelect_' + product_id + ":checked");
            var color = $('.colorSelect_' + product_id + ':checked');
            var scaleId = size.data('id');
            console.log(scaleId);
            var colorId = ('undefined' === typeof(color.data('id'))) ? 0 : color.data('id');

            <?php // save in hidden input for use in form submission 
            ?>
            $('#color_id').val(colorId);
            $('#size_id').val(scaleId);

            $.ajax({
                type: "POST",
                url: "<?php echo WEB_ADMIN_URL; ?>/stock/stock_ajax.php?page=countCurrentQTY",
                data: {
                    pId: pId,
                    storeID: storeId,
                    scaleId: scaleId,
                    colorId: colorId,
                    loadFromWeb: '1'
                }
            }).done(function(data) {
                if (data == 0) {
                    $('#order_submit').prop('disabled', 'true');
                    $('#order_submit').hide();
                } else {
                    $('#order_submit').prop('disabled', '');
                    $('#order_submit').show();
                }
            });

        }, 400);
    }
</script>


<?php return ob_get_clean(); ?>