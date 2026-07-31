<?php
############ Export table into CSV ############
 echo $importEmailyes = ob_get_clean();
require_once("../global.php");
require_once("classes/invoice.php");
require_once(__DIR__ . "/classes/order.php");
        $order_c = new order();
 global $productF;

 $invoice = new invoice();
        $functions->includeAdminFile("product_management/functions/product_function.php");

        $productF = new product_function();


// var_dump($_POST['multi_select']);



// $dbF->prnt($_POST['multi_select']);



if (isset($_POST['csvExportnewsizecolor'])) {

$_d = ",";

####### CSV file Headings, for excel edit mode.

$file_heading = "SNO{$_d}INVOICE{$_d}DATE{$_d}CUSTOMER NAME{$_d}CUSTOMER EMAIL{$_d}CUSTOMER PHONE{$_d}SOLD PRICE{$_d}CURRENCY{$_d}PAYMENT METHOD{$_d}ORDER PROCESS- INVOICE STATUS{$_d}INVOICE STATUS{$_d}RESERVATION NO{$_d}PAYMENT INFO";

$file_heading .= "\n";

$output = $file_heading;

$min = $_POST['min'];
$max = $_POST['max'];


###### GET DATA FROM DB

$sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        WHERE `invoice_date` BETWEEN '$min' AND '$max' ORDER BY order_invoice_pk DESC";

$data = $dbF->getRows($sql);

$i = 0;

// $dbF->prnt($data);
foreach ($data as $key => $val) {
    $i++;
    $divInvoice = '';
    $invoiceStatus = $productF->invoiceStatusFind($val['invoice_status']);

    $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
    $invoiceId = $val['order_invoice_pk'];

    $country = $val['shippingCountry'];
    $country = $functions->countryFullName($country);

    $orderInfo = $order_c->orderInvoiceInfo($invoiceId);
    $orderUser_id = $val['orderUser'];
    $resv_no = $val['rsvNo'];
    $customer_Name = $orderInfo['sender_name'];
    $customer_email = $orderInfo['sender_email'];
    $customer_phone = $orderInfo['sender_phone'];
    $payment_info = $val['payment_info'];
    $payment_info = preg_split('/\r\n|\r|\n/', $payment_info);
    $payment = '';
    foreach ($payment_info as $row) {
        $payment .= $row.',';
    }

    // echo $payment_info.'<br>';
    // echo '<pre>'; print_r($payment_info); echo '</pre>';

    //Check order process or not,, if single product process it show 1
    $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
    $dbF->getRow($sql);
    $orderProcess = "NO";
    if ($dbF->rowCount > 0) {
        //make sure all order process or custome process
        $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
        $dbF->getRow($sql);
        if ($dbF->rowCount > 0) {
            //Ja = yes
            $orderProcess = "Yes";
        } else {
            $orderProcess = "Yes";
        }
    }

    $days = $functions->ibms_setting('order_invoice_deleteOn_request_after_days');
    $link = $functions->getLinkFolder();
    $date = date('Y-m-d', strtotime($val['dateTime']));
    $minusDays = date('Y-m-d', strtotime("-$days days"));

    $paymentMethod = $val['paymentType'];
    $paymentMethod = $productF->paymentArrayFind($paymentMethod);
    $cur_symbol = md5($val['price_code']);

    $order_id = $val['order_invoice_pk'];
    // $form_invoice = array();
    // $form_invoice[] = array(
    //     "type" => "select",
    //     "array" => $productF->invoiceStatusArray(),
    //     "select" => $val['invoice_status'],
    //     "id" => $st.'-'.$val['invoice_id'],
    //     "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
    //     "class" => "form-control invoice_quick_select",
    //     "format" => "<div class='invoice_quick_select_div'>{{form}}</div>"
    // );
    // $invoice_status = $functions->print_form($form_invoice, "", false);

    $statusFine = $productF->invoiceStatusFind($val['invoice_status']);

    //10 columns
    $count_me = $val['total_price'];
    $price_code = $val['price_code'];

    $invoiceDate = date('d/M/y', strtotime($invoiceDate));

    // $columns["data"][$key] = array(
    //     $i,
    //     "$val[invoice_id]",
    //     $country,
    //     $invoiceDate,
    //     $customer_Name,
    //     $count_me,
    //     $paymentMethod,
    //     $orderProcess,
    //     $divInvoice . $invoice_status,
    //     $action
    // );
    $invoice_id = $val['invoice_id'];

    $output .= "{$i}{$_d}{$invoice_id}{$_d}{$invoiceDate}{$_d}{$customer_Name}{$_d}{$customer_email}{$_d}{$customer_phone}{$_d}{$count_me}{$_d}{$price_code}{$_d}{$paymentMethod}{$_d}{$orderProcess}{$_d}{$statusFine}{$_d}{$resv_no}{$_d}{$payment}";
        $output .= "\n";

}

####### Download csv File...
$filename = "Sharkspeed_orders.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;
exit;
}//if end on submit
?>