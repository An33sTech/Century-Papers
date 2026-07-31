<?php

class invoice extends object_class
{
    public $productF;
    public function __construct()
    {
        parent::__construct('3');
        if (isset($GLOBALS['productF']))
            $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__ . "/../../product_management/functions/product_function.php");
            $this->productF = new product_function();

            /**
             * MultiLanguage keys Use where echo;
             * define this class words and where this class will call
             * and define words of file where this class will called
             **/
            global $_e;
            global $adminPanelLanguage;
            $_w = array();
            //Invoice.php
            $_w['View Api Return Info'] = '';
            $_w['Invoice Detail View'] = '';
            $_w['ORDER SENDER DETAIL'] = '';
            $_w['Name'] = '';
            $_w['LOCATION'] = '';
            $_w['SUPPLIER'] = '';
            $_w['E-mail'] = '';
            $_w['Phone'] = '';
            $_w['Address'] = '';
            $_w['Post Code'] = '';
            $_w['City'] = '';
            $_w['Country'] = '';
            $_w['ORDER RECEIVER DETAIL'] = '';
            $_w['STOCK MANAGEMENT'] = '';
            $_w['STOCK AND SHIP MANAGEMENT'] = '';
            $_w['TOTAL'] = '';
            $_w['PROCESS'] = '';
            $_w['SALE QTY'] = '';
            $_w['DISCOUNT'] = '';
            $_w['SALE IN PRICE'] = '';
            $_w['ORIGINAL PRICE'] = '';
            $_w['STORE NAME'] = '';
            $_w['PRODUCT NAME'] = '';
            $_w['ORDER PRODUCTS'] = '';
            $_w['SNO'] = '';
            $_w['Products'] = '';
            $_w['Stock Deduct Intranet'] = '';
            $_w['NO'] = '';
            $_w['Yes'] = '';
            $_w['Quantity'] = '';
            $_w['Free Gift Add Products'] = '';

            $_w['Shipping Label'] = '';
            $_w['COSTUMER ADRESS'] = '';
            $_w['PACKAGE CODE'] = '';
            $_w['Total Gross Weight'] = '';
            $_w['CUSTOMER NAME'] = '';
            $_w['CUSTOMER EMAIL'] = '';
            $_w['COSTUMER ADDRESS'] = '';
            $_w['Contact No'] = '';
            $_w['Postal Code'] = '';
            $_w['City'] = '';
            $_w['Detail'] = '';
            $_w['Shipping Label'] = '';


            $_w['Total Net Amount'] = '';
            $_w['Print Out'] = '';
            $_w['INTERNAL COMMENT'] = '';
            $_w['Enter Vendor Payment Information'] = '';
            $_w['Payment Info'] = '';
            $_w['Reservation Number'] = '';
            $_w['InComplete'] = '';
            $_w['OK'] = '';
            $_w['Payment Status'] = '';
            $_w['Payment Type'] = '';
            $_w['Value'] = '';
            $_w['Property'] = '';
            $_w['Payment Information'] = '';
            $_w['Send Email To Customer'] = '';
            $_w['Shipping Track Number'] = '';
            $_w['Invoice Status'] = '';
            $_w['Date Time'] = '';
            $_w['Total'] = '';
            $_w['Total Product Price'] = '';
            $_w['Shipping Price'] = '';
            $_w['Total Weight'] = '';
            $_w['Invoice ID'] = '';
            $_w['Relative Product(s)'] = '';
            $_w['Invoice Detail'] = '';
            $_w['Custom'] = '';
            $_w['Close'] = '';
            //This class
            $_w['Stock'] = '';
            $_w['Submit DateTime'] = '';
            $_w['Stock QTY is less then your Order, Please check'] = '';
            $_w['Stock Error stock not found for process OR stock QTY error, Please check'] = '';
            $_w['Product Update Successfully'] = '';
            $_w['Product Update Failed'] = '';
            $_w['Product Update'] = '';
            $_w['Deal'] = '';
            $_w['Edit custom size form'] = '';
            $_w['User not fill final form'] = '';
            $_w['Print PDF'] = '';
            $_w['Discount Code'] = '';
            $_w['Creation Time'] = '';
            $_w['3 For 2 Category'] = '';
            $_w['Free Gift'] = '';
            $_w['Checkout'] = '';
            $_w['OFFER'] = '';
            $_w['Last Updated Time'] = '';
            $_w['RETURNS INFO'] = '';
            $_w['ASSIGN TO SHIPPER'] = '';
            $_w['STATUS SUPPLIER'] = '';
            $_w['Refunded'] = '';
            $_w['Defected'] = '';
            $_w['Changed Product'] = '';
            $_w['Changed Size'] = '';
            $_w['Status Unknown'] = '';
            $_w['Write a Comment for the Order'] = '';
            $_w['Status'] = '';
            $_w['Send Free Gift Email To client'] = '';
            $_w['Free Gift Log List'] = '';

            $_w['Comment'] = '';
            $_w['Create Comment'] = '';
            $_w['Email To Customer'] = '';
            $_w['Email Templates'] = '';
            $_w['LETTER TITLE FOR ADMIN'] = '';
            $_w['FROM NAME'] = '';
            $_w['FROM MAIL'] = '';
            $_w['SUBJECT'] = '';
            $_w['EMAIL MESSAGE'] = '';
            $_w['Send Email'] = '';
            $_w['Invoice'] = '';
            $_w['Invoice Status Updated'] = '';
            $_w['Log List'] = '';
            $_w['Invoice Status Updated'] = '';
            $_w['Invoice'] = '';
            $_w['Bundle feature applies:'] = '';
            $_w['pcs for'] = '';
            $_w['Extra Sale'] = '';
            $_w['Amount'] = '';
            $_w['Description'] = '';
            $_w['Email Sent To Customer For Extra Payment'] = '';
            $_w['EXTRA PAYMENTS'] = '';
            $_w['EXTRA AMOUNT'] = '';
            $_w['DESC'] = '';
            $_w['RESERVATION NO'] = '';
            $_w['PAYMENT INFO'] = '';
            $_w['UPDATE DATE'] = '';
            $_w['INVOICE DATE'] = '';
            $_w['INVOICE STATUS'] = '';
            $_w['PAYMENT TYPE'] = '';
            $_w['PAYMENT STATUS'] = '';
            $_w['EXTRA PAYMENT FORM'] = '';
            $_w['PAYMENT LINK'] = '';
            $_w['Currency Code'] = '';
            $_w['Reason For Export'] = '';
            $_w['Order Date'] = '';
            $_w['Order ID'] = '';
            $_w['Invoice Detail'] = '';
            $_w['Frieght Charges'] = '';
            $_w['Customer Information'] = '';
            $_w['Province Code'] = '';
            $_w['Package Information'] = '';
            $_w['Package Description'] = '';
            $_w['Package Measurement Code'] = '';
            $_w['Packaging Code'] = '';
            $_w['Package Weight'] = '';
            $_w['Products'] = '';
            $_w['Product Name'] = '';
            $_w['Qty'] = '';
            $_w['Price'] = '';
            $_w['Unit'] = '';
            $_w['Commodity Code'] = '';
            $_w['Part Number'] = '';
            $_w['Stock'] = '';
            $_w['Stock Update'] = '';
            $_w[''] = '';
            $_w[''] = '';
            $_w[''] = '';
            $_w[''] = '';
            $_w[''] = '';

            $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Invoice');

        }
    }


    public function customSubmitValues($orderId)
    {
        global $_e;
        $sql = "SELECT *,
(SELECT setting_value FROM p_custom_setting as b WHERE b.fieldName=s.setting_name AND b.setting_name='name' AND b.c_id=a.custom_id ) as tName
FROM `p_custom_submit` as a JOIN `p_custom_submit_setting` as s ON a.id = s.orderId  WHERE a.id = '$orderId'";
        $data = $this->dbF->getRows($sql);
        if (empty($data)) {
            return false;
        }

        foreach ($data as $val) {
            $name = $val['setting_name'];
            $tName = $this->functions->unserializeTranslate($val['tName']);
            if (empty($tName)) {
                $tName = $name;
            }
            $value = $val['setting_value'];
            $form_fields[] = array(
                'label' => $tName,
                'format' => "$value"
            );
        }

        if ($data[0]['submitLater'] == '1' && $this->functions->isWebLink()) {
            $customEditLink = WEB_URL . "/viewOrder?editCustom=" . $this->functions->encode($orderId);
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$customEditLink' class='btn themeButton'>" . $_e["Edit custom size form"] . "</a></div>"
            );
        } else if ($data[0]['submitLater'] == '1' && $this->functions->isAdminLink()) {
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'>" . $_e["User not fill final form"] . "</div>"
            );
        } else if ($data[0]['submitLater'] == '0') {
            $form_fields[] = array(
                'label' => $_e["Submit DateTime"],
                'type' => "none",
                'format' => "<div class='text-center form-group  margin-0'>" . date('H:i:s d-m-Y', strtoTime($data[0]['dateTime'])) . "</div>"
            );

            $pdfLink = WEB_URL . "/src/pdf/measurementPDF.php?id=$orderId&orderId=" . $this->functions->encode($orderId);
            $form_fields[] = array(
                'label' => $_e["Print PDF"],
                'type' => "none",
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$pdfLink' target='_blank' class='btn btn-default'>{$_e["Print PDF"]}</a></div>"
            );

        }

        $form_fields['main'] = array(
            'type' => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>
<style>#customSizeInfo_$orderId .modal-body{padding: 0 15px;}</style>
"
        );

        $format = '<div class="form-group border padding-5 margin-0">
<label class="col-sm-2 col-md-3 text-right">{{label}}</label>
<div class="col-sm-10  col-md-9">
{{form}}
</div>
</div>';

        $array = array("form" => $this->functions->print_form($form_fields, $format, false), "formFill" => $data[0]['submitLater']);
        return $array;

    }

    public function dealSubmitPackage($orderId, $cart = true)
    {
        if ($cart) {
            $orderId = $this->getDealProductOrders($orderId);
        }
        foreach ($orderId as $val) {
            $name = $val['name'];
            $form_fields[] = array(
                'format' => "<div>$name</div>"
            );
        }

        $form_fields['main'] = array(
            'type' => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>"
        );

        $format = '<div class="form-group border padding-5 margin-0">
<div class="col-sm-12 text-center">
{{form}}
</div>
</div>';

        return $this->functions->print_form($form_fields, $format, false);

    }

    public function invoiceDetail($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM order_invoice left join order_invoice_info
on order_invoice.order_invoice_pk= order_invoice_info.order_invoice_id
WHERE order_invoice.order_invoice_pk='$id'";

        $data = $this->dbF->getRow($sql);
        return $data;
    }
    public function orderData($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM order_invoice WHERE order_invoice.order_invoice_pk='$id'";

        $data = $this->dbF->getRow($sql);
        return $data;
    }
    public function invoiceProduct($id)
    {
        $sql = "SELECT * FROM order_invoice_product WHERE order_invoice_id = ?";
        $data = $this->dbF->getRows($sql, [$id]);

        return $data;
    }

    public function invoiceRelativeProduct($pName, $pIds)
    {
        $relativeProductsDiv = "";

        @$pId = $pIds[0];
        @$scaleId = $pIds[1];
        @$colorId = $pIds[2];
        @$storeId = $pIds[3];
        @$customId = $pIds[4];

        $eX = explode(" - ", $pName, 2);
        $sizeName = @$eX[1];
        $proName = @$eX[0];

        $sql = "SELECT `setting_val` FROM `product_setting` WHERE `p_id` = ? AND `setting_name` = ?";
        $data = $this->dbF->getRow($sql, [$pId, 'relative']);

        if ($this->dbF->rowCount > 0) {
            $relativeProducts = unserialize($data['setting_val']);

            foreach ($relativeProducts as $key => $val) {

                if ($sizeName == NULL) {
                    $sp = "SELECT * FROM `product_inventory` WHERE `qty_product_id` = '$val' AND `qty_product_scale` = '$scaleId'";
                } else {
                    $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$val' AND `prosiz_cur_id` = 20 AND `prosiz_name` = '$sizeName'";
                }

                $spData = $this->dbF->getRow($sp);

                if ($this->dbF->rowCount > 0) {
                    $scaleIdNew = ($sizeName == NULL) ? $scaleId : $spData['prosiz_id'];
                    $related_pname = $pName = $this->productF->getProductName($val) . ' - ' . $this->productF->getScaleName($scaleIdNew);


                    @$hashVal = $val . ":" . $scaleIdNew . ":" . $colorId . ":" . $storeId;
                    $hash = md5($hashVal);

                    $sqlcc = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ? AND `qty_item` != '0'";
                    $qty_itemData = $this->dbF->getRow($sqlcc, [$spData['prosiz_name'], $val]);

                    if ($this->dbF->rowCount > 0) {
                        $qty = $qty_itemData['qty_item'];
                    } else {
                        $qty = 0;
                    }

                    if ($qty > 0) {
                        $relativeProductsDiv .= "<p class='relativeProducts'><span class='pname'>$related_pname</span><br><span class='stockQty'>Stock Quantity = ($qty)</span</p>";
                    }
                }
            }
        }


        return $relativeProductsDiv;
    }

    public function handelKlarna($orderId, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done, $extra = false)
    {
        //All work will Handel Accordingly
        $this->functions->require_once_custom('Class.myKlarna.php');
        $klarnaClass = new myKlarna();

        return $klarnaClass->klarnaInvoices($orderId, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done, $extra);

    }




    public function bestSellerNewsletter()
    {

        $sql = "SELECT pid FROM `order_product_info` where order_date >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY pid ORDER BY `order_product_info`.`order_date` DESC";

        $res = $this->dbF->getRows($sql);


        $temp = '<style type="text/css">

/*pop side*/

/*pop side*/

.pop_side {
.pop_content {
position: relative;
width: 45%;
padding-top: 20px;
text-align: center;
display: inline-block;
vertical-align: top;
margin-right: 2%;
}
}

.pop_side_top {
position: relative;
width: 100%;
border-bottom: 1px solid #e1e2e4;
padding-bottom: 15px;
}

.pop_side_top i {
font-size: 25px;
color: #42474b;
display: inline-block;
vertical-align: middle;
margin-right: 10px;
}

.number_side {
position: absolute;
right: 0px;
top: 0px;
font-size: 25px;
color: #42474b;
}

.number_side span {
font-size: 34px;
color: #42474b;
font-family: "ubuntubold";
display: inline-block;
vertical-align: top;
}

.pop_content {
position: relative;
width: 45%;
display: inline-block;
vetical-align: top;
margin-right: 2%;
text-align: center;
}

.pop_content:nth-child(even) {
margin-right: 0%;
}

.pop_img {
position: relative;
display: block;
width: 35%;
margin: 0 auto;
}

.pop_img img {
width: 100%;
}

.pop_slide {
position: relative;
width: 100%;
}

.pop_img1 {
position: relative;
width: 100%;
}

.pop_content_main {
display: inline-block;
vertical-align: middle;
width: 55%;
text-align: center;
margin: 15px 0px;
}

.pop_content_main_btn {
position: relative;
width: 215px;
text-align: center;
background: #42474b;
box-shadow: 5px 5px 0px #a2a6af;
border-radius: 5px 5px;
margin: 0 auto;
transition: .7s;
margin-bottom: 20px;
}

.pop_content_main_btn a {
display: block;
color: #ffffff;
font-size: 16px;
padding: 10px 0px;
}

.pop_content_main_btn:hover {
background: #7ebc41;
}

.selection_side {
position: relative;
width: 100%;
color: #42474b;
font-size: 16px;
}

.pop_content_main .select_box4 {
width: 215px;
}

.pop_content_main .select_box4 .dropdown_select dt a {
height: 42px !important;
line-height: 42px !important;
border: 1px solid #6b6f72 !important;
box-shadow: 3px 3px 0px #363b3f;
border-radius: 2px 2px;
background: #ffffff url(../webImages/arrow.png) no-repeat scroll right center;
}

.pop_price {
position: relative;
width: 100%;
color: #eb333d;
font-size: 30px;
text-align: center;
font-weight: bold;
}

.pop_price span {
color: #a0a9b0;
margin: 0px 5px;
display: block;
font-style: normal;
font-size: 16px;
text-decoration: line-through;
font-weight: normal;
font-family: sans-serif;
}

.button_side {
position: relative;
display: inline-block;
vertical-align: middle;
width: 100%;
margin-top: 5px;
}

.button_side1 {
position: relative;
display: inline-block;
vertical-align: top;
width: 45%;
background: #42474b;
padding: 10px 0px;
border-radius: 2px 2px;
text-align: center;
margin-right: 2%;
}

.button_side1 a {
display: block;
color: #ffffff;
font-size: 16px;
}

.button_side2 {
position: relative;
display: inline-block;
vertical-align: top;
width: 60%;
background: #7ebc41;
padding: 10px 0px;
border-radius: 2px 2px;
text-align: center;
}

.button_side2 a {
display: block;
color: #ffffff;
font-size: 16px;
}

.pop_btn {
position: absolute;
left: 0px;
top: 50%;
width: 100%;
z-index: 1;
}

.pop_btn1 {
position: absolute;
left: 1%;
width: 16px;
height: 28px;
background: url(../webImages/news_left1.png);
cursor: pointer;
}

.pop_btn2 {
position: absolute;
right: 1%;
width: 16px;
height: 28px;
background: url(../webImages/news_right1.png);
cursor: pointer;
}

.pop_close {
position: absolute;
right: 10px;
top: 10px;
cursor: pointer;
font-size: 25px;
}


/*pop side*/
</style>

<br><br>
';


        foreach ($res as $pId) {


            $id = $pId['pid'];


            $pLink = WEB_URL . '/detail.php?pId=' . $id;

            $name = $this->productF->getProductName($id);
            $img = $this->productF->productSpecialImage($id, 'main');
            $img = $this->functions->resizeImage($img, 'auto', 160, false);
            $price = $this->productF->productPrice($id);
            $currencyId = $price['propri_cur_id'];
            $symbol = $this->productF->currencySymbol($currencyId);
            $priceP = $price['propri_price'];

            $discount = $this->productF->productDiscount($id, $currencyId);
            @$discountFormat = $discount['discountFormat'];
            @$discountP = $discount['discount'];

            $discountPrice = $this->productF->discountPriceCalculation($priceP, $discount);

            $newPrice = $discountPrice;

            $priceP .= ' ' . $symbol;
            $newPrice .= ' ' . $symbol;

            if ($newPrice != $priceP) {
                $hasDiscount = true;
                $oldPriceDiv = '<span class="oldPrice">' . $priceP . '</span>';
                $newPriceDiv = $newPrice;
            } else {
                $oldPriceDiv = "";
                $newPriceDiv = $priceP;
            }

            $buyToT = $this->dbF->hardWords('Buy To', false);

            $temp .= "<div class='pop_content'>
<div class='pop_img'>
<div class='pop_img1'><img alt='' src='$img' loading='lazy'/></div>
<!-- pop_img1 close --></div>
<!-- pop_img close -->

<div class='pop_content_main'>
<div class='selection_side'>$name</div>
<!-- selection_side close -->

<div class='pop_price'>
$newPriceDiv $oldPriceDiv
</div>
<!-- pop_price close -->

<div class='button_side'><!-- <div class='button_side1'><a href='" . $pLink . "'>No thanks !</a></div> --><!-- button_side1 close -->
<div class='button_side2'><a href='" . $pLink . "'>" . $buyToT . "</a></div>
<!-- button_side1 close --></div>
<!-- button_side close --></div>
<!-- pop_content_main close --></div>";


        }


        return $temp;
    }


    public function update()
    {
        global $_e;
        if (!$this->functions->getFormToken('Invoice')) {
            return false;
        }

        try {
            $this->db->beginTransaction();
            $id = $_POST['pId'];
            if (!empty($_POST)) {
                if (isset($_POST['invoiceStatus'])) {
                    $old_status = $_POST['old_status_id'];
                    $old_status_name = $_POST['old_status_name'];
                    $invoi_idd = $_POST['invoi_idd'];
                    $inv = $_POST['invoiceStatus'];
                    $old_trackNo = $_POST['old_trackNo'];

                    $invStatus = $this->productF->invoiceStatusFind($inv);

                    if ($old_status != $inv) {
                        $log_des = "Invoice status changed to $invStatus from $old_status_name";
                        $this->functions->orderlog(_js(_uc($_e['Invoice Status Updated'])), _js(_uc($_e['Invoice'])), $invoi_idd, $log_des);
                    }

                    if ($old_trackNo != $_POST['trackNo']) {
                        if (empty($old_trackNo) && $old_trackNo == '') {
                            $old_trackNo = 'NONE';
                        }
                        $new_trackNo = $_POST['trackNo'];
                        $log_des1 = "Order Shipping Track Number Changed From $old_trackNo To $new_trackNo";
                        $this->functions->orderlog('Order Shipping Track Number Changed', 'Invoice', $invoi_idd, $log_des1);
                    }

                    if ($old_status == $inv) {
                        $invStatus = $this->productF->invoiceStatusFind($inv);
                        $log_des = "Invoice has been updated without any change and status is $invStatus";
                        $this->functions->orderlog(_js(_uc($_e['Invoice Status Updated'])), _js(_uc($_e['Invoice'])), $invoi_idd, $log_des);
                    }

                    @$paymentInfo = $_POST['paymentInfo'];
                    
                    if (isset($_POST['payment'])) {
                        $paymentTypeSql = "paymentType = '" . $_POST['payment'] . "', ";
                        $paymentType = $_POST['payment'];
                    } else {
                        $paymentType = '';
                        $paymentTypeSql = '';
                    }

                    if (($inv == '0' || $inv == '3' || $inv == '6')) {
                        $sql = "SELECT `inTransaction`, `rsvNo`, `rsvNo_done`, `invoice_id` FROM `order_invoice` WHERE `order_invoice_pk` = '$id' AND `inTransaction` !=''";
                        $dataTrans = $this->dbF->getRow($sql);

                        if ($this->dbF->rowCount > 0 && ($paymentType == '2')) {
                            $rsvNo = $dataTrans['rsvNo'];
                            $rsvNo_done = $dataTrans['rsvNo_done'];
                            $inTransaction = trim($dataTrans['inTransaction']);
                            $invv_id = $dataTrans['invoice_id'];

                            $sql1 = "SELECT * FROM `order_extra_amount` WHERE `invoice_no` = '$invv_id' AND `inTransaction` !=''";
                            $extraTrans = $this->dbF->getRows($sql1);

                            if ($this->dbF->rowCount > 0 && ($paymentType == '2')) {
                                foreach ($extraTrans as $key => $value) {
                                    $extraPaymentType = $value['paymentType'];
                                    $extraPaymentInfo = $value['payment_info'];
                                    $extraId = $value['id'];
                                    $extrarsvNo = $value['rsvNo'];
                                    $extrarsvNo_done = $value['rsvNo_done'];
                                    $extrainTransaction = trim($value['inTransaction']);
                                    /* ------- ---------- KLARNA ------------- ------------ */
                                    $klarnaReturnExtra = $this->handelKlarna($extraId, $extrainTransaction, $inv, $extraPaymentType, $extrarsvNo, $extrarsvNo_done, true);

                                    $returnKlarnaExtra = $klarnaReturnExtra;
                                    /* ------- ----------KLARNA End------------- ------------ */
                                    $paymentInfoExtra = $extraPaymentInfo . "\n $returnKlarnaExtra";
                                    // Edit by jawwad
                                    if (strpos($returnKlarnaExtra, " Error :") !== false) {
                                        $this->functions->jAlertError($returnKlarnaExtra);
                                    }
                                    // Edit by jawwad


                                    $sql0 = "UPDATE `order_extra_amount` SET `invoice_status` = ?, `payment_info` = ?  WHERE `invoice_no` = ?";
                                    $this->dbF->setRow($sql0, array($invoice_id, $paymentInfoExtra, $invv_id));

                                }
                            }

                            /* ------- ---------- KLARNA ------------- ------------ */
                            $klarnaReturn = $this->handelKlarna($id, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done);
                            $returnKlarna = $klarnaReturn;
                            /* ------- ----------KLARNA End------------- ------------ */
                            $paymentInfo = $paymentInfo . "\n $returnKlarna";
                        }
                    }

                    if (($inv == '3') && ($paymentType == '9')) {
                        ///complete
                        $sqlX = "SELECT * FROM `klarnaAPI` WHERE `invId` = '$id' AND `authorization_token` != ''";
                        $orderX = $this->dbF->getRow($sqlX);
                        $orderJ = unserialize($orderX['sessionRequestShipper']);
                        $decoded = json_decode($orderJ, true);
                        $sessionResponceShipper = unserialize($orderX['sessionResponceShipper']);
                        $decodedq = json_decode($sessionResponceShipper, true);
                        $order_id = $decodedq['order_id'];
                        if ($this->functions->ibms_setting('klarnaPaymentTesting') == '1') {
                            $url = 'https://api.playground.klarna.com/ordermanagement/v1/orders/' . $order_id . '/captures';
                            $username = $this->functions->ibms_setting('KP_Test_user');
                            $password = $this->functions->ibms_setting('KP_Test_pswrd');
                        } else {
                            $url = 'https://api.klarna.com/ordermanagement/v1/orders/' . $order_id . '/captures';
                            $username = $this->functions->ibms_setting('KP_live_user');
                            $password = $this->functions->ibms_setting('KP_Live_pswrd');
                        }
                        $aAuthorization = base64_encode($username . ":" . $password);
                        $order_amount = $decoded['order_amount'];
                        $r = [];
                        foreach ($decoded as $idC => $row) {
                            unset($decoded['purchase_country']);
                            unset($decoded['purchase_currency']);
                            unset($decoded['order_amount']);
                            unset($decoded['order_tax_amount']);
                            unset($decoded['auto_capture']);
                            unset($decoded['merchant_urls']);
                            unset($decoded['merchant_reference1']);
                        }
                        $ajs = json_encode($decoded, true);
                        $ajs = str_replace('{"order_lines":[', '"order_lines":[', $ajs);
                        $ajs = str_replace('}]}', '}]', $ajs);
                        $jJson = '{
                            "captured_amount": ' . $order_amount . ',
                            "description": ' . $id . ' ,"reference": ' . $id . ', ' . $ajs . '}';
                        $curlx = curl_init();
                        curl_setopt_array($curlx, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $jJson,
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Basic ' . $aAuthorization . '',
                                'Content-Type: application/json'
                            ),
                        ));
                        $resultx = curl_exec($curlx);
                        curl_close($curlx);
                        $dec = json_decode($resultx, true);


                        $sql = "UPDATE `klarnaAPI` SET `capturesRequest` = ?, `capturesResponce` = ? WHERE `id`= ?";
                        $this->dbF->setRow($sql, [serialize($jJson), serialize($resultx), $orderX['id']]);

                        $paymentInfo = $paymentInfo . "\n" . serialize($resultx);

                        echo "<pre>";
                        print_r($dec);
                        echo "</pre>";
                    }
                    
                    if(($inv == '0') && ($paymentType == '10')){
                        $sql = "SELECT * FROM `order_invoice` WHERE `order_invoice_pk` = '$id' AND `inTransaction` != ''";
                        $dataTrans = $this->dbF->getRow($sql);
                        
                        if($this->dbF->rowCount > 0){
                            $inTransaction = trim($dataTrans['inTransaction']);
                            $invv_id = $dataTrans['invoice_id'];
                            $invId = $dataTrans['order_invoice_pk'];
                            
                            // Total amount of order (Remember Amount must be in smallest money unit for the Currency (cents for CHF))
                            $amount = $dataTrans['total_price'] * 100;
                            
                            // UUID => (Universally Unique IDentifier) generation, This method is required for cembraPay 
                            $requestMsgId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                            
                            // ISO8601 Date with time in format 'yyyy-MM-ddTHH:mm:ssZ'.
                            $date = new DateTime();
                            $date->setTimezone(new DateTimeZone('UTC'));
                            $formattedDate = $date->format('Y-m-d\TH:i:s\Z');
                            
                            $usernameCembra = $this->functions->ibms_setting('cembrapay_client');
                            $password = $this->functions->ibms_setting('cembrapay_secret');
                            
                            $authString = base64_encode("$usernameCembra:$password");
                            
                            $apiUrl = "https://ext-test.api.cembrapay.ch/v1.0";
                            $cancelUrl = "$apiUrl/Transactions/cancel";
                            
                            $cancelData = [
                                "transactionId" => $inTransaction,
                                "merchantOrderRef" => $invId,
                                "amount" => $amount,
                                "currency" => $dataTrans["price_code"],
                                "requestMsgId" => $requestMsgId,
                                "requestMsgDateTime" => $formattedDate,
                                "isFullCancelation" => true
                            ];
                            
                            $ch = curl_init($cancelUrl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                "Content-Type: application/json",
                                "Authorization: Basic $authString"
                            ]);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cancelData));
                            
                            $cancelResponse = curl_exec($ch);
                            $cancelResponseData = json_decode($cancelResponse, true);

                            if (isset($cancelResponseData['processingStatus']) && $cancelResponseData['processingStatus'] === "SUCCESS") {
                                $paymentInfo = $paymentInfo . "\n" . serialize($cancelResponse);
                            }

                        }
                    }
                    
                    $sql = "UPDATE `order_invoice` SET `invoice_status` = ?, `payment_info` = ?, $paymentTypeSql `trackNo` = ? WHERE `order_invoice_pk` = ?";

                    $this->dbF->setRow($sql, [$inv, $paymentInfo, $_POST['trackNo'], $id], false);

                    if ($_POST['sendEmail'] == '1') {
                        
                        $invStatus = $this->productF->invoiceStatusFind($inv);
                        $invoice = $this->functions->ibms_setting('invoice_key_start_with');
                        
                        $to = $_POST['toEmail'];
                        $new_trackNo = $_POST['trackNo'];
                        $lang = $this->productF->getOrderLanguage($id);
                        
                        // Order Complete Mail Start
                        if ($inv === "3" && $old_status !== $inv) {
                            $link = WEB_URL . "/viewOrder?view=$id&orderId=" . $this->functions->encode($id);
                            $returnProArr = $this->bestSellerNewsletter();
                            
                            $completeMailArray['best_selling_products_last_30_days'] = $returnProArr;
                            $completeMailArray['link'] = $link;
                            $completeMailArray['invoiceStatus'] = $invStatus;
                            $completeMailArray['trackingNo'] = $new_trackNo;
                            $completeMailArray['invoiceNumber'] = $invoice . "" . $id;

                            $this->functions->send_mail($to, '', '', 'orderUpdate', '', $completeMailArray, true, $lang);
                        }
                        // Order Complete Mail End
                        
                        // Order Cancel Mail Start
                        if ($inv === "0" && $old_status !== $inv) {
                            $cancelMailArray['invoiceStatus'] = $invStatus;
                            $cancelMailArray['invoiceNumber'] = $invoice . "" . $id;
                            
                            $this->functions->send_mail($to, '', '', 'orderCancel', '', $cancelMailArray, true, $lang);
                        }
                        // Order Cancel Mail End
                        
                        // Partial Delivery Mail Start
                        if ($inv === "9" && $old_status !== $inv) {
                            $this->sendPartialDeliveryMail($id, $invStatus, $invoice, $lang, $to, $new_trackNo);
                        }
                        // Partial Delivery Mail End
                    }

                    if ($_POST['invoiceStatus'] == "3" || $_POST['invoiceStatus'] == "9") {
                        $returnStatus = $this->stockDeductFromOrder($id, false, false, false, true);
                    }

                    if ($_POST['invoiceStatus'] == "0" || $_POST['invoiceStatus'] == "6" || $_POST['invoiceStatus'] == "1") {
                        @$pr = $_POST['payment'];

                        $sql11 = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
                        $sQ = $this->dbF->getRows($sql11);

                        if ($this->dbF->rowCount > 0) {
                            foreach ($sQ as $key => $pP) {
                                $order_pName = $pP['order_pName'];
                                $eX = explode(" - ", $order_pName, 2);
                                $s = $eX[1];
                                $pIds = $pP['order_pIds'];
                                $pArray = explode("-", $pIds);
                                $pId = $pArray[0];
                                $scaleId = $pArray[1];
                                $colorId = $pArray[2];
                                $storeId = $pArray[3];
                                $sp = "SELECT prosiz_id FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' and `prosiz_cur_id` = 20 and `prosiz_name`='$s' ";
                                $spData = $this->dbF->getRow($sp);
                                if ($this->dbF->rowCount > 0) {
                                    $scaleId = $spData['prosiz_id'];
                                }
                            }
                        }
                    }
                }

                if ($this->dbF->rowCount > 0) {
                    echo $this->functions->notificationError(_js(_uc($_e["Product Update"])), _js($_e["Product Update Successfully"]), "btn-success");
                } else {
                    echo $this->functions->notificationError(_js(_uc($_e["Product Update"])), _js($_e["Product Update Failed"]), "btn-danger");
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }
    
    public function sendPartialDeliveryMail($orderId, $invStatus, $invoice, $lang, $to, $new_trackNo){
        $sqlOrderProduct = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$orderId'";
        $orderProducts = $this->dbF->getRows($sqlOrderProduct);
        
        $productsArray = [];
        
        if (isset($_POST['dShip']) && !empty($_POST['dShip'])) {
            foreach($orderProducts as $orderProduct){
                $productInvoiceID = $orderProduct["invoice_product_pk"];
                $pids = $orderProduct['order_pIds'];
                $pids = explode("-", $pids);
                $pId = $pids[0];
                $scaleId = $pids[1];
                $pName = $this->productF->getProductName($pId) . ' - ' . $this->productF->getScaleName($scaleId);
                
                if(in_array($productInvoiceID, $_POST["dShip"])){
                    $productsArray[] = $pName;
                }
            }
        
            $partialMailArray['trackingNo'] = $new_trackNo;
            $partialMailArray['invoiceStatus'] = $invStatus;
            $partialMailArray['invoiceNumber'] = $invoice . "" . $orderId;
            $partialMailArray['orderProducts'] = implode('<br>', $productsArray);
    
            $this->functions->send_mail($to, '', '', 'partialOrderMail', '', $partialMailArray, true, $lang);
        }
    }
    
    function AddQtyBackInStockRelative($pId = "", $scaleId = "", $colorId = "", $storeId = "", $qty = "", $add = true, $data = "", $insertIntoRecord = false, $oid = '')
    {
        global $_e;

        $pqty = intval($qty);
        $date = date('Y-m-d H:i:s');
        $publish_date = date('Y-m-d h:i:a');
        $returnRes = [];

        // Fetch session, IP, and browser details once
        $user_email = @$_SESSION['_email'];
        $ref_id = $_SESSION['_uid'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $browser = "";
        foreach ($this->functions->getBrowserCommon() as $key => $val) {
            $browser .= "$key : $val <br />";
        }
        $hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
        $hash = md5($hashVal);

        if (isset($_POST['relativeSize']) && !empty($_POST['relativeSize'])) {
            $relativeSizes = explode(",", $_POST['relativeSize']);
            foreach ($relativeSizes as $sizeName) {
                $inventoryData = $this->dbF->getRow("SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ?", [$sizeName, $pId]);

                if ($this->dbF->rowCount > 0) {
                    $oldQty = $invQty = $inventoryData['qty_item'];
                    $__qty = intval($oldQty) - intval($pqty);

                    $sql = "UPDATE `product_inventory` SET `qty_item` = ? WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ?";
                    $this->dbF->setRow($sql, [$__qty, $sizeName, $pId]);

                    $sql_log = "INSERT INTO `invLogs` (`type`, `hash`, `sid`, `pid`, `minusQty`, `addQty`, `publish_date`, `heading`, `oid`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql_log, ['OrderUpdate From Sharkspeed MyAdmin', $hash, $inventoryData['qty_product_scale'], $pId, $qty, '', $publish_date, 'sharkspeed.com', $oid]);

                    $pName = $this->productF->getProductName($pId) . '-' . $this->productF->getScaleName($scaleId);
                    $desc = $add ? "$qty Items have been added to the productName ($pName) with scale id $scaleId" : "$qty Items have been removed from the productName ($pName) with scale id $scaleId";

                    $sql_activity = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql_activity, ['OrderUpdate', 'Sharkspeed MyAdmin', $hash, $ref_id, $user_email, '', $qty, $pId, $inventoryData['qty_product_scale'], $oid, $desc, $ip, $browser]);

                    $order_inv_id = $_POST['invoi_idd'] ?? '';
                    $order_inv_pro_id = "";
                    $sql_order_inv_pro = "SELECT invoice_product_pk FROM `order_invoice_product` WHERE order_invoice_id = ? AND order_pIds LIKE ?";
                    $sql_order_inv_pro_data = $this->dbF->getRow($sql_order_inv_pro, [$order_inv_id, "{$inventoryData['qty_product_id']}-%"]);

                    if ($sql_order_inv_pro_data) {
                        $order_inv_pro_id = $sql_order_inv_pro_data['invoice_product_pk'];
                    }

                    $sql_in_s_d_h = "INSERT INTO `stock_deduct_record`(`order_inv_id`, `order_inv_pro_id`, `order_pro_id`, `order_product_name`, `order_pro_hash`, `stock_qty`, `message`, `dateTime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql_in_s_d_h, [$order_inv_id, $order_inv_pro_id, "{$inventoryData['qty_product_id']}-{$inventoryData['qty_product_scale']}", $this->productF->getProductName($pId), $hash, "$oldQty : $__qty", "Previous quantity ($oldQty) new quantity ($__qty)", date('Y-m-d h:i:s')]);
                } else {
                    $oldQty = 0;
                    $__qty = $pqty = $oldQty - $pqty;
                    if ($pqty < 0) {
                        $pqty = 0;
                    }
                    $addQty = "";
                    $minusQty = $pqty;

                    $sql = "INSERT INTO `product_inventory` (`qty_store_id`, `qty_product_id`, `qty_product_scale`, `qty_product_color`, `qty_item`, `product_store_hash`) VALUES (?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql, [$storeId, $pId, $scaleId, $colorId, $pqty, $hash]);

                    $sql_log = "INSERT INTO `invLogs` (`type`, `hash`, `sid`, `pid`, `minusQty`, `addQty`, `publish_date`, `heading`, `oid`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql_log, ['OrderedWithoutStock', $hash, $scaleId, $pId, $minusQty, $addQty, $publish_date, 'sharkspeed.com', $oid]);

                    $pName = $this->productF->getProductName($pId) . '-' . $this->productF->getScaleName($scaleId);
                    $desc = $add ? "$qty Items have been added to the productName ($pName) with scale id $scaleId" : "$qty Items have been removed from the productName ($pName) with scale id $scaleId";

                    $sql_activity = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql_activity, ['OrderUpdate', 'Sharkspeed MyAdmin', $hash, $ref_id, $user_email, $addQty, $minusQty, $pId, $scaleId, $oid, $desc, $ip, $browser]);


                    //insert into stock_deduct_history
                    $order_inv_id = $_POST['invoi_idd'] ?? '';
                    $order_inv_pro_id = "";
                    $sql_order_inv_pro = "SELECT invoice_product_pk FROM `order_invoice_product` WHERE order_invoice_id = ? AND order_pIds LIKE ?";
                    $sql_order_inv_pro_data = $this->dbF->getRow($sql_order_inv_pro, [$order_inv_id, "{$pId}-%"]);

                    if ($sql_order_inv_pro_data) {
                        $order_inv_pro_id = $sql_order_inv_pro_data['invoice_product_pk'];
                    }

                    $sql_in_s_d_h = "INSERT INTO `stock_deduct_record`(`order_inv_id`, `order_inv_pro_id`, `order_pro_id`, `order_product_name`, `order_pro_hash`, `stock_qty`, `message`, `dateTime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->dbF->setRow($sql_in_s_d_h, [$order_inv_id, $order_inv_pro_id, "{$pId}-{$scaleId}", $this->productF->getProductName($pId), $hash, "0 : $__qty", "Previous quantity ($oldQty) new quantity ($__qty)", date('Y-m-d h:i:s')]);
                }
                $this->dbF->setRow("UPDATE order_invoice_product SET order_process = ? WHERE order_hash = ?", [1, $data['order_hash']]);
                $returnRes[] = $desc;
            }
        }
        return true;
    }

    function AddQtyBackInStock($pId = "", $scaleId = "", $colorId = "", $storeId = "", $qty = "", $add = true, $data = "", $insertIntoRecord = false, $oid = '')
    {

        global $_e;

        $sid = $scaleId;
        $pqty = intval($qty);
        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
        $hash = md5($hashVal);

        $returnRes = [];

        if (isset($_POST['dStock'])) {
            $dStock = empty($_POST['dStock']) ? array() : $_POST['dStock'];
            for ($i = 0; $i < count($dStock); $i++) {
                if ($dStock[$i] == $hash) {
                    $hash = $dStock[$i];

                    $sqlCheck = "SELECT * FROM `product_inventory` WHERE `product_store_hash` = '$hash'";
                    $inventoryData = $this->dbF->getRow($sqlCheck);

                    if ($this->dbF->rowCount > 0) {
                        $oldQty = $invQty = $inventoryData['qty_item'];
                        $qty_product_id = $inventoryData['qty_product_id'];
                        $qty_product_scale = $inventoryData['qty_product_scale'];

                        $pName = $this->productF->getProductName($qty_product_id);
                        $sName = $this->productF->getScaleName($qty_product_scale);

                        $addQty = "";
                        $minusQty = "";

                        $__qty = intval($oldQty) - intval($pqty);

                        $date = date('Y-m-d H:i:s');
                        $pqty = $qty;

                        $sql = "UPDATE `product_inventory` SET `qty_item` = ? WHERE `product_store_hash` = ?";
                        $this->dbF->setRow($sql, array($__qty, $hash));

                        $publish_date = date('Y-m-d h:i:a');

                        $sqlg = "INSERT INTO `invLogs` (`type`, `hash`, `sid`, `pid`, `minusQty`, `addQty`, `publish_date`, `heading`, `oid`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['OrderUpdate From Sharkspeed MyAdmin', $hash, $qty_product_scale, $qty_product_id, $qty, '', $publish_date, 'sharkspeed.com', $oid]);

                        $user_email = @$_SESSION['_email'];
                        $ref_id = $_SESSION['_uid'];
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $browser = "";

                        foreach ($this->functions->getBrowserCommon() as $key => $val) {
                            $browser .= "$key : $val <br />";
                        }

                        $pName = $this->productF->getProductName($qty_product_id) . '-' . $this->productF->getScaleName($sid);
                        $desc = "";

                        if ($add) {
                            $desc = $qty . " Items has been added to the productName( $pName) having scale id $sid ";
                        } else {
                            $desc = $qty . " Items has been deleted to the productName( $pName) and scale id $sid  has been deleted";
                        }

                        $returnRes[] = $desc;

                        $sqlg_ = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg_, ['OrderUpdate', 'Sharkspeed MyAdmin', $hash, $ref_id, $user_email, '', $qty, $qty_product_id, $qty_product_scale, $oid, $desc, $ip, $browser]);

                        $ref_id__ = @$_POST['invoi_idd'];
                        $this->functions->orderlog(_js(_uc($_e['Stock Update'])), _js(_uc($_e['Stock'])), $ref_id__, $desc . ' having hash ' . $hash);

                        //insert into stock_deduct_history
                        $order_inv_id = @$_POST['invoi_idd'];
                        $order_inv_pro_id = "";
                        $sql_order_inv_pro = "SELECT invoice_product_pk FROM `order_invoice_product` WHERE order_invoice_id = '$order_inv_id' AND order_pIds LIKE '$qty_product_id-%'";
                        $sql_order_inv_pro_data = $this->dbF->getRow($sql_order_inv_pro);


                        if ($sql_order_inv_pro_data) {
                            $order_inv_pro_id = @$sql_order_inv_pro_data['invoice_product_pk'];
                        }

                        $order_pro_id = "$qty_product_id-$sid";
                        $order_product_name = $this->productF->getProductName($qty_product_id) . '-' . $this->productF->getScaleName($sid);
                        $order_pro_hash = $hash;
                        $stock_qty = "$oldQty : $__qty";
                        $message = "Previous quantity ($oldQty) new quantity ($__qty)";
                        $dateTime = date('Y-m-d h:i:s');

                        $sql_in_s_d_h = "INSERT INTO `stock_deduct_record`(`order_inv_id`, `order_inv_pro_id`, `order_pro_id`, `order_product_name`, `order_pro_hash`, `stock_qty`, `message`, `dateTime`) VALUES (?,?,?,?,?,?,?,?)";
                        $this->dbF->setRow($sql_in_s_d_h, array($order_inv_id, $order_inv_pro_id, $order_pro_id, $order_product_name, $order_pro_hash, $stock_qty, $message, $dateTime));
                        
                        $updateSQL = "UPDATE `order_invoice_product` SET `deducted` = ? WHERE `order_invoice_id` = ? AND `order_hash` = ?";
                        $this->dbF->setRow($updateSQL, [1, $oid, $data['order_hash']]);
                    } else {
                        $pName = $this->productF->getProductName($pId);
                        $sName = $this->productF->getScaleName($scaleId);
                        $newQty = $pqty;
                        $oldQty = 0;
                        $__qty = $pqty = $oldQty - $pqty;

                        if ($pqty < 0) {
                            $pqty = 0;
                        }

                        $addQty = "";
                        $minusQty = $pqty;


                        $sql = "INSERT INTO `product_inventory` (`qty_store_id`, `qty_product_id`, `qty_product_scale`, `qty_product_color`, `qty_item`, `product_store_hash`) VALUES (?, ?, ?, ?, ?, ?) ";
                        $this->dbF->setRow($sql, [$storeId, $pId, $scaleId, $colorId, $pqty, $hash]);

                        $publish_date = date('Y-m-d h:i:a');

                        $sqlg = "INSERT INTO `invLogs` (`type`, `hash`, `sid`, `pid`, `minusQty`, `addQty`, `publish_date`, `heading`, `oid`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['OrderedWithoutStock', $hash, $scaleId, $pId, $minusQty, $addQty, $publish_date, 'sharkspeed.com', $oid]);

                        $user_email = @$_SESSION['_email'];
                        $ref_id = $_SESSION['_uid'];
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $browser = "";

                        foreach ($this->functions->getBrowserCommon() as $key => $val) {
                            $browser .= "$key : $val <br />";
                        }

                        $pName = $this->productF->getProductName($pId) . '-' . $this->productF->getScaleName($sid);
                        $desc = "";

                        if ($add) {
                            $desc = $qty . " Items has been added to the productName( $pName) having scale id $sid ";
                        } else {
                            $desc = $qty . " Items has been deleted to the productName( $pName) and scale id $sid  has been deleted";
                        }

                        $returnRes[] = $desc;

                        $sqlg = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['OrderedWithoutStock', 'Sharkspeed MyAdmin', $hash, $ref_id, $user_email, $addQty, $minusQty, $pId, $scaleId, $oid, $desc, $ip, $browser]);

                        $ref_id__ = @$_POST['invoi_idd'];
                        $this->functions->orderlog(_js(_uc($_e['Stock Update'])), _js(_uc($_e['Stock'])), $ref_id__, $desc . ' having hash ' . $hash);

                        //insert into stock_deduct_history
                        $order_inv_id = @$_POST['invoi_idd'];
                        $order_inv_pro_id = "";
                        $sql_order_inv_pro = "SELECT invoice_product_pk FROM `order_invoice_product` WHERE order_invoice_id = '$order_inv_id' AND order_pIds LIKE '$pId-%'";
                        $sql_order_inv_pro_data = $this->dbF->getRow($sql_order_inv_pro);

                        if ($sql_order_inv_pro_data) {
                            $order_inv_pro_id = @$sql_order_inv_pro_data['invoice_product_pk'];
                        }

                        $order_pro_id = "$pId-$sid";
                        $order_product_name = $this->productF->getProductName($pId) . '-' . $this->productF->getScaleName($sid);
                        $order_pro_hash = $hash;
                        $stock_qty = "$oldQty : $__qty";
                        $message = "Previous quantity ($oldQty) new quantity ($__qty)";
                        $dateTime = date('Y-m-d h:i:s');

                        $sql_in_s_d_h = "INSERT INTO `stock_deduct_record`(`order_inv_id`, `order_inv_pro_id`, `order_pro_id`, `order_product_name`, `order_pro_hash`, `stock_qty`, `message`, `dateTime`) VALUES (?,?,?,?,?,?,?,?)";
                        $this->dbF->setRow($sql_in_s_d_h, array($order_inv_id, $order_inv_pro_id, $order_pro_id, $order_product_name, $order_pro_hash, $stock_qty, $message, $dateTime));
                        
                        $updateSQL = "UPDATE `order_invoice_product` SET `deducted` = ? WHERE `order_invoice_id` = ? AND `order_hash` = ?";
                        $this->dbF->setRow($updateSQL, [1, $oid, $data['order_hash']]);
                    }

                    $sql = "UPDATE order_invoice_product SET order_process = ? WHERE order_hash = ?";
                    $this->dbF->setRow($sql, array(1, $data['order_hash']));

                }
            }
        }
        return true;
    }

    public function stockDeductFromOrderAdmin($orderId, $transection = true)
    {
        global $_e;

        $sql = "SELECT * FROM order_invoice_product WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRows($sql, false);

        foreach ($data as $d) {
            $invProductId = $d['invoice_product_pk'];

            if (in_array($d['invoice_product_pk'], $_POST['pro'])) {
            } else {
                continue;
            }

            $pids = $d['order_pIds'];
            $pids = explode("-", $pids);

            $pId = $pids[0];
            $scaleId = $pids[1];
            $colorId = $pids[2];
            $storeId = $pids[3];

            $saleQTY = $d['order_pQty'];

            @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
            $hash = md5($hashVal);

            $invQty = $this->productF->stockProductQty($hash);
            if ($saleQTY <= $invQty) {


                if ($this->productF->stockProductQtyMinus($hash, $saleQTY)) {
                    $sql = "UPDATE order_invoice_product SET order_process = ? WHERE invoice_product_pk = ?";
                    $this->dbF->setRow($sql, array(1, $invProductId));
                } else {
                    echo $this->functions->notificationError(_js(_uc($_e["Stock"])), _js($_e["Stock Error stock not found for process OR stock QTY error, Please check"]), "btn-danger");
                    return false;
                }
            } else {
                echo $this->functions->notificationError(_js(_uc($_e["Stock"])), _js($_e["Stock QTY is less then your Order, Please check"]), "btn-danger");
                return false;
            }
        } //foreach
    }

    public function stockDeductFromOrder($orderId, $transection = true, $add = true, $api = false, $insertIntoRecord = false)
    {
        global $_e;
        
        $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$orderId'";
        $data = $this->dbF->getRows($sql, false);
        
        if(isset($_POST["relativeSize"]) && !empty($_POST["relativeSize"])){
            $relativeSizes = explode(",", $_POST["relativeSize"]);
        }
        

        $return = false;
        foreach ($data as $d) {
            $invProductId = $d['invoice_product_pk'];
            $pids = $d['order_pIds'];
            $pids = explode("-", $pids);
            $pId = $pids[0];
            $scaleId = $pids[1];
            $colorId = $pids[2];
            $storeId = $pids[3];
            $customId = isset($pids[4]) ? $pids[4] : 0;
            @$dealId = $d['deal']; // if not it is 0
            @$info = unserialize($d['info']);
            $orderHash = $d['order_hash'];

            if ($customId != '0') {

                return true;
            }


            $saleQTY = $d['order_pQty'];
            if ($dealId == '0') {


                $sp = "SELECT prosiz_name  FROM `product_size` WHERE `prosiz_id` = '$scaleId' ";
                $spData = $this->dbF->getRow($sp);

                if (empty($spData)) {
                    $pids = explode(' - ', $d['order_pName']);
                    $spData['prosiz_name'] = $pids[count($pids) - 1];
                }

                $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' and `prosiz_cur_id` = 20 and `prosiz_name`='$spData[prosiz_name]' ";
                $spData = $this->dbF->getRow($sp);

                if ($this->dbF->rowCount > 0) {
                    $scaleId = $spData['prosiz_id'];
                } else {

                    $scaleId = filter_var(
                        $scaleId,
                        FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION
                    );
                }


                $return = $this->AddQtyBackInStock($pId, $scaleId, $colorId, $storeId, $saleQTY, $add, $d, $insertIntoRecord, $orderId);
                
                if (isset($_POST['dShip']) && !empty($_POST['dShip'])) {
                    $dShip = $_POST['dShip'];
                    for ($i = 0; $i < count($dShip); $i++) {
                        if($dShip[$i] == $invProductId){
                            $sqlUpdate = "UPDATE `order_invoice_product` SET `shipped` = ? WHERE `order_invoice_id` = ? AND `order_hash` = ? AND invoice_product_pk = ?";
                            $this->dbF->setRow($sqlUpdate, [1, $orderId, $orderHash, $invProductId]);
                        }
                    }
                }
                
                // Stock Deduct For Relative
                $relativeSQL = "SELECT `setting_val` FROM `product_setting` WHERE `p_id` = ? AND `setting_name` = ?";
                $dataRelative = $this->dbF->getRow($relativeSQL, [$pId, 'relative']);
                
                if ($this->dbF->rowCount > 0) {
                    $relativeProducts = unserialize($dataRelative['setting_val']);

                    foreach ($relativeProducts as $key => $value) {
                        $sizeNameRelative = explode(' - ', $d['order_pName']);
                        $sizeNameOfRelative = $sizeNameRelative[1];

                        $spRelative = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = ? AND `prosiz_cur_id` = ? AND `prosiz_name` = ?";
                        $spDataRelative = $this->dbF->getRow($spRelative, [$value, 20, $sizeNameOfRelative]);

                        $scaleIdRelative = $spDataRelative["prosiz_id"];

                        $inventoryRelativeSQL = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ?";
                        $inventoryRelativeData = $this->dbF->getRow($inventoryRelativeSQL, [$sizeNameOfRelative, $value]);
                        if(isset($_POST["relativeSize"]) && !empty($_POST["relativeSize"])){
                            if (in_array($inventoryRelativeData["qty_product_scale_name"], $relativeSizes)) {
                                $return = $this->AddQtyBackInStockRelative($value, $scaleIdRelative, $colorId, $storeId, $saleQTY, $add, $d, $insertIntoRecord, $orderId);
                            }
                        }
                    }
                }


            } else {
                foreach ($info as $val) {
                    $pids = $val['pIds'];
                    $pids = explode("-", $pids);
                    $pId = $pids[0];
                    $scaleId = $pids[1];
                    $colorId = $pids[2];


                    $sp = "SELECT prosiz_name  FROM `product_size` WHERE `prosiz_id` = '$scaleId' ";
                    $spData = $this->dbF->getRow($sp);

                    if (empty($spData)) {
                        $pids = explode(' - ', $d['order_pName']);
                        $spData['prosiz_name'] = $pids[count($pids) - 1];
                    }

                    $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' and `prosiz_cur_id` = 20 and `prosiz_name`='$spData[prosiz_name]' ";
                    $spData = $this->dbF->getRow($sp);

                    if ($this->dbF->rowCount > 0) {
                        $scaleId = $spData['prosiz_id'];
                    } else {

                        $scaleId = filter_var(
                            $scaleId,
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                    }
                    $return = $this->AddQtyBackInStock($pId, $scaleId, $colorId, $storeId, $saleQTY, $add, $d, $insertIntoRecord, $orderId);
                    if ($return == false) {
                        break;
                    }
                }
            }

        } //foreach


        if ($return == false) {
            return false;
        }
        return true;
    }

    private function stockDeductFromOrderLoop($pId, $scaleId, $colorId, $storeId, $data)
    {
        global $_e;
        global $conIntra;
        $invProductId = $data['invoice_product_pk'];
        $saleQTY = $data['order_pQty'];
        $order_pName = $data['order_pName'];
        $eX = explode(" - ", $order_pName, 2);
        $s = $eX[1];
        @$dealId = $data['deal']; // if not it is 0


        $order_invoice_id = $data['order_invoice_id'];



        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
        $hash = md5($hashVal);

        $product_check_stock = $this->functions->developer_setting('product_check_stock');

        $invQty = $this->productF->stockProductQty($hash);
        if (1 == 1) {
            if ($dealId != '0') {
                $this->productF->productDealCountPlus($dealId, $saleQTY);
            }
            $this->productF->productSaleCountPlus($pId, $saleQTY);

        } else {
            echo $this->functions->notificationError(_js(_uc($_e["Stock"])), _js($_e["Stock QTY is less then your Order, Please check"]), "btn-danger");
            return false;
        }



        return true;
    }

}


?>