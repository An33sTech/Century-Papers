<?php
class order extends object_class
{
    public $productF;

    public function __construct()
    {
        parent::__construct('3');

        if (isset($GLOBALS['productF']))
            $this->productF = $GLOBALS['productF'];
        else {
            require_once (__DIR__ . "/../../product_management/functions/product_function.php");
            $this->productF = new product_function();
        }

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w = array();
        //newOrder.php
        $_w['Add New Order'] = '';
        $_w['InComplete Orders'] = '';
        $_w['All Orders'] = '';
        $_w['Complete Orders'] = '';
        $_w['Cancel Orders'] = '';
        $_w['InProcess Invoices'] = '';
        $_w['Order Create/View'] = '';

        //New order form Function
        $_w['Store Country'] = '';
        $_w['Select Country'] = '';
        $_w['SELECT'] = '';
        $_w['Print Out All Selected Order'] = '';
        $_w['Select Visible Order'] = '';
        $_w['Send Selected Order To SharkOnline'] = '';
        $_w['Print Fail Please Try Again.'] = '';
        $_w['User'] = '';
        $_w['Select User'] = '';
        $_w['No User'] = '';
        $_w['Invoice Status'] = '';
        $_w['Payment Type'] = '';
        $_w['Payment Info'] = '';
        $_w['Enter Vendor Payment Information'] = '';
        $_w['PRODUCT SCALE'] = '';
        $_w['PRODUCT COLOR'] = '';
        $_w['STORE'] = '';
        $_w['QUANTITY'] = '';
        $_w['PRICE'] = '';
        $_w['Address'] = '';
        $_w['Phone'] = '';
        $_w['Select Product Name'] = '';
        $_w['Select Scale'] = '';
        $_w['Select Color'] = '';
        $_w['Select Store'] = '';
        $_w['Product QTY'] = '';
        $_w['Single Price'] = '';
        $_w['Product Discount'] = '';
        $_w['Add Product'] = '';
        $_w['Remove Checked Items'] = '';
        $_w['Check/Uncheck All'] = '';
        $_w['NO'] = '';
        $_w['PRODUCT'] = '';
        $_w['WEIGHT'] = '';
        $_w['QTY'] = '';
        $_w['(QTY*PRICE) - DISCOUNT = TOTAL PRICE'] = '';
        $_w['DISCOUNT'] = '';
        $_w['TOTAL WEIGHT'] = '';
        $_w['TOTAL PRICE'] = '';
        $_w['Sender And Receiver Information'] = '';
        $_w['I am sender And Receiver'] = '';
        $_w['I am Sender And Friend Is receiver'] = '';
        $_w['Sender Information'] = '';
        $_w['Sender Name'] = '';
        $_w['Sender Phone'] = '';
        $_w['Sender Email'] = '';
        $_w['Sender City'] = '';
        $_w['Sender Country'] = '';
        $_w['Country'] = '';
        $_w['Sender Post Code'] = '';
        $_w['Sender Address'] = '';
        $_w['Receiver Information'] = '';
        $_w['Receiver Name'] = '';
        $_w['Receiver Phone'] = '';
        $_w['Receiver Email'] = '';
        $_w['Receiver City'] = '';
        $_w['Receiver Country'] = '';
        $_w['Receiver Post Code'] = '';
        $_w['Receiver Address'] = '';
        $_w['Last Order View'] = '';
        $_w['ORDER'] = '';
        $_w['Order  Price'] = '';
        $_w['Shipping Price'] = '';
        $_w['Order / Invoice Management'] = '';
        $_w['Total'] = '';
        $_w['ORDER AND PROCESS'] = '';
        $_w['Selected Products'] = '';
        $_w['Manually Created Orders'] = '';
        //Add new order function
        $_w['Order QTY is Greater Than stock Quantity'] = '';
        $_w['Shipping Error'] = '';
        $_w['Some thing went wrong Please try again'] = '';
        $_w['Product Submit Fail'] = '';
        $_w['Product Submit'] = '';
        $_w['Product Submit Failed'] = '';
        $_w['New Order Added Successfully'] = '';
        $_w['New Order'] = '';
        $_w['Product Successfully Submit'] = '';
        $_w['Thank you your product is successfully submit'] = '';

        //Order view function
        $_w['SNO'] = '';
        $_w['INVOICE'] = '';
        $_w['CUSTOMER NAME'] = '';
        $_w['INVOICE DATE'] = '';
        $_w['SOLD PRICE'] = '';
        $_w['PAYMENT METHOD'] = '';
        $_w['Transaction Id'] = '';
        $_w['Tracking Number'] = '';
        $_w['ORDER PROCESS'] = '';
        $_w['ACTION'] = '';
        $_w['Yes'] = '';
        $_w['PURCHASE PRICE'] = '';
        $_w['VIEW ORDER'] = '';
        $_w['Delete All Old Incomplete Orders'] = '';
        $_w['Search By Date Range'] = '';
        $_w['Date To'] = '';
        $_w['Date From'] = '';
        $_w['Custom Filters'] = '';
        $_w['Selected SubTotal'] = '';

        $_w['ORDER PROCESS - Invoice Status'] = '';
        $_w['Update Invoice Status'] = '';
        $_w['Invoice Status Updated'] = '';
        $_w['Invoice'] = '';
        $_w['CUSTOMER EMAIL'] = '';
        $_w['Flagged'] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Order');

    }


    public function deleteOrders($type)
    {
        $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
        $minusDays = date('Y-m-d', strtotime("-$days days"));

        try {
            $this->db->beginTransaction();

            $sql = "SELECT order_pIds FROM `order_invoice_product` WHERE  dateTime <= '$minusDays' AND orderStatus = 'inComplete'";
            $oldData = $this->dbF->getRows($sql);
            foreach ($oldData as $val) {
                $orderId = $val['order_invoice_id'];
                $pIds = $val['order_pIds'];
                $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                $pId = $pArray[0]; // 491
                $scaleId = $pArray[1]; // 426
                $colorId = $pArray[2]; // 435
                $storeId = $pArray[3]; // 5
                @$customId = $pArray[4]; // 5

                //delete custom if has
                if ($customId != '0' && !empty($customId)) {
                    $sql = "DELETE FROM p_custom_submit WHERE id = '$customId'";
                    $this->dbF->setRow($sql);
                }

                $sql = "DELETE FROM order_invoice WHERE order_invoice_pk = '$orderId' '";
                $this->dbF->setRow($sql);
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
        }
        $this->deleteCartOld();
    }

    public function deleteCartOld()
    {
        //delete Old Cart and custom From table...
        $date = date('Y-m-d', strtotime("-30 days"));
        $sql = "DELETE FROM p_custom_submit WHERE dateTime <= '$date' AND id in (SELECT customId FROM cart WHERE dateTime <= '$date')";
        $this->dbF->setRow($sql);

        $sql = "DELETE FROM cart WHERE dateTime <= '$date'";
        $this->dbF->setRow($sql);
    }



    /**
     *  Simple Form For New Order
     */
    public function newOrderForm()
    {
        global $_e;
        $this->functions->require_once_custom('store');
        $storeC = new store();


        $paymentSelectOption = $this->productF->paymentSelect();
        $invoiceStatus = $this->productF->invoiceStatus();
        $country_list = $this->functions->countrySelectOption();
        $storeList = $storeC->storeNamesCountryValueOption();
        $token = $this->functions->setFormToken('orderAdd', false);


        //user list
        $this->functions->require_once_custom('webUsers.class');
        $userC = new webUsers();
        $usersOption = '';

        echo '

<form method="post" class="form-horizontal" role="form">
' . $token . '
<input type="hidden" id="priceCode" name="priceCode"/>
<div class="form-horizontal">
<div class="col-md-6">
<!--
<div class="form-group">
<label for="receipt_date" class="col-sm-2 col-md-3 control-label">Date</label>
<div class="col-sm-10 col-md-9">
<input type="text" name="receipt_date" class="form-control date" required id="receipt_date" placeholder="Purchasing Date">
</div>
</div> -->




<div class="form-group">
<label for="input2" class="col-sm-2 col-md-3  control-label">' . _uc($_e['Store Country']) . '</label>
<div class="col-sm-10  col-md-9">
<input type="hidden" name="storeCountry" class="form-control storeCountry" data-val="">
<select  id="storeCountry" onchange="orderProductJson(this);"  name="storeCountry" class="form-control" required="required">
<option value="">' . _uc($_e['Select Country']) . '</option>
' . $storeList . '
</select>
</div>
</div>


<div class="form-group">
<label for="input2" class="col-sm-2 col-md-3  control-label">' . _uc($_e['User']) . '</label>
<div class="col-sm-10  col-md-9">
<select  id="userId" name="userId" class="form-control" required="required">
<option value="">' . _uc($_e['Select User']) . '</option>
<option value="0">' . _uc($_e['No User']) . '</option>
' . $usersOption . '

</select>
</div>
</div>

<div class="form-group">
<label for="receipt_store_id" class="col-sm-2 col-md-3 control-label">' . _uc($_e['Invoice Status']) . '</label>

<div class="col-sm-10 col-md-9">
<fieldset id ="store" class="statusSelectFieldset">
<select required name="invoiceStatus"  id="statusSelect"  class="form-control statusSelect">
<option value="">------</option>
' . $invoiceStatus . '
</select>
</fieldset>
</div>
</div>

<div class="form-group">
<label for="receipt_store_id" class="col-sm-2 col-md-3 control-label">' . _uc($_e['Payment Type']) . '</label>
<div class="col-sm-10 col-md-9">
<input type="hidden" name="paymentType" class="form-control paymentType" data-val="">
<fieldset id ="store" class="paymentTypeFieldset">
<select required  id="paymentTypeSelect" onchange="' . "$('.paymentType').val($(this).val());" . '" class="form-control paymentTypeFieldset">
<option value="">------</option>
' . $paymentSelectOption . '
</select>
</fieldset>
</div>
</div>


<div class="form-group">
<label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">' . _uc($_e['Payment Info']) . '</label>
<div class="col-sm-10 col-md-9">
<textarea name="paymentInfo" id="paymentInfo" class="form-control" placeholder="' . _uc($_e['Enter Vendor Payment Information']) . '"></textarea>
</div>
</div>




<div class="form-group" style="position: relative;margin-bottom: 30px;">
<div id="loadingProgress" style="position: absolute;width: 100%;"></div>
</div>


</div><!-- First col-md-6 end -->


<div class="table-responsive inline-block newProduct " >
<div id="productNotFoundInCountry" class="orderAddProductNotAllow"></div>
<table id="newProduct" class="table sTable table-hover " width="100%" border="0" cellpadding="0" cellspacing="0">
<thead>
<tr>
<th>' . _u($_e['PRODUCT']) . '</th>
<th class="allowProductScale">' . _u($_e['PRODUCT SCALE']) . '</th>
<th class="allowProductColor">' . _u($_e['PRODUCT COLOR']) . '</th>
<th>' . _u($_e['STORE']) . '</th>
<th width="110">' . _u($_e['QUANTITY']) . '</th>
<th>' . _u($_e['PRICE']) . '</th>
<th>' . _u($_e['DISCOUNT']) . '</th>
<th>' . _u($_e['TOTAL PRICE']) . '</th>
</tr>
</thead>
<tbody>
<td>
<input type="text" class="form-control" id="invoice_product_id" placeholder="' . _uc($_e['Select Product Name']) . '">
<input type="hidden" class="form-control invoice_product_id" data-val="">
<input type="hidden" class="form-control invoice_product_shipping" data-val="">
<input type="hidden" class="" id="invoice_product_weight">
</td>
<td class="allowProductScale">
<input type="text" class="form-control" id="invoice_product_scale" placeholder="' . _uc($_e['Select Scale']) . '" readonly value="No Scale Avaiable">
<input type="hidden" class="form-control invoice_product_scale" data-val="">
</td>
<td class="allowProductColor">
<input type="text" class="form-control" id="invoice_product_color" placeholder="' . _uc($_e['Select Color']) . '" readonly value="No Color Avaiable">
<input type="hidden" class="form-control invoice_product_color" data-val="">
</td>
<td>
<input type="text" class="form-control" id="invoice_product_store" placeholder="' . _uc($_e['Select Store']) . '" readonly value="No Store Avaiable">
<input type="hidden" class="form-control invoice_product_store" data-val="">
</td>
<td>
<input type="number" class="form-control" data-val="" data-max="0" min="1"  id="invoice_qty" placeholder="' . _uc($_e['Product QTY']) . '">
</td>
<td>
<input type="number" class="form-control" data-val="" min="0"   id="invoice_price" placeholder="' . _uc($_e['Single Price']) . '">
</td>
<td>
<input type="number" class="form-control" data-val="" min="0"  id="invoice_discount" placeholder="' . _uc($_e['Product Discount']) . '">
</td>
<td>
<input type="number" readonly class="form-control" min="0" id="invoice_total_price" placeholder="' . _uc($_e['TOTAL PRICE']) . '">
</td>
</tbody>
</table>

<div class="form-group">
<div class="col-sm-10">
<button type="button" onclick="invoiceFormValid();" id="AddProduct" class="btn btn-default">' . _uc($_e['Add Product']) . '</button>
</div>
</div>


</div> <!-- first table end-->

<div style="margin:70px 0 0 0;">
<input type="button" class="btn btn-danger" onclick="removechecked()" value="' . _uc($_e['Remove Checked Items']) . '" >
<input type="button" class="btn btn-danger" onclick="uncheckall()" value="' . _uc($_e['Check/Uncheck All']) . '">
<br><br>


<div class="table-responsive search_manually" >
<table id="addSelectedProduct" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
<thead>
<tr>
<th>' . _u($_e['NO']) . '</th>
<th>' . _u($_e['PRODUCT']) . '</th>
<th>' . _u($_e['WEIGHT']) . '</th>
<th>' . _u($_e['QTY']) . '</th>
<th width="300">' . _u($_e['(QTY*PRICE) - DISCOUNT = TOTAL PRICE']) . '</th>
</tr>
</thead>
<tbody id="vendorProdcutList">

</tbody>

</table>
</div><!-- table-responsive added -->

<div class="container-fluid text-right">
<div class="h4"> ' . _u($_e['QUANTITY']) . ': <span class="totalQuantity bold">0</span></div>
<div class="h4"> ' . _u($_e['DISCOUNT']) . ': <span class="totalDiscount bold">0</span></div>
<div class="h4"> ' . _u($_e['TOTAL WEIGHT']) . ': <span class="totalWeight bold">0</span></div>
<div class="h4"> ' . _u($_e['TOTAL PRICE']) . ': <span class="totalPrice bold">0</span></div>
<input type="hidden" name="totalPrice" class="totalPriceInput" />
<input type="hidden" name="totalWeight" class="totalWeightInput" />
</div>



<div class="container-fluid">
<h3 class="navbar-inverse bg-black text-center" >' . _uc($_e['Sender And Receiver Information']) . '</h3>
<div class="form-group col-md-12">
<label class="radio-inline">
<input type="radio" checked name="senderOrReceiver" class="senderOrReceiver iAmReceiver" data-id="iAmReceiver" value="iAmReceiver">' . _uc($_e['I am sender And Receiver']) . '
</label>

<label class="radio-inline">
<input type="radio"  name="senderOrReceiver" class="senderOrReceiver" data-id="iAmSender" value="receiverFriend">' . _uc($_e['I am Sender And Friend Is receiver']) . '
</label>

</div>
<div class="col-sm-6" id="receiverDiv">
<div class="navbar-inverse bg-black" style="color:#fff">' . _uc($_e['Receiver Information']) . '</div>
<div class="col-sm-12">

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Name']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="receiver_name" id="receiver_name" required="" placeholder="' . _uc($_e['Receiver Name']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Phone']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="receiver_phone" id="receiver_phone" required placeholder="' . _uc($_e['Receiver Phone']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Email']) . '</label>
<div class="col-sm-9">
<input type="email" class="form-control" name="receiver_email" id="receiver_email" placeholder="' . _uc($_e['Receiver Email']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver City']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="receiver_city" id="receiver_city" required placeholder="' . _uc($_e['Receiver City']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Country']) . '</label>
<div class="col-sm-9">
<fieldset class="receiver_countryFieldset">
<select required  id="receiver_country" name="receiver_country" class="form-control">
<option value="">------</option>
' . $country_list . '
</select>
</fieldset>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Post Code']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="receiver_post" id="receiver_post" required="" value="" placeholder="' . _uc($_e['Receiver Post Code']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Address']) . '</label>
<div class="col-sm-9">
<textarea class="form-control" name="receiver_address" id="receiver_address" required placeholder="' . _uc($_e['Receiver Address']) . '"></textarea>
</div>
</div>


</div><!-- col-md-12 sender info end -->
</div>
<!-- col-md-6 receiver info end -->

<div class="col-sm-6">
<div class="navbar-inverse bg-black" style="color:#fff">' . _uc($_e['Sender Information']) . '</div>
<div class="col-sm-12">
<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Name']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="sender_name" id="sender_name" required="" placeholder="' . _uc($_e['Sender Name']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Phone']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="sender_phone" id="sender_phone" required placeholder="' . _uc($_e['Sender Phone']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Email']) . '</label>
<div class="col-sm-9">
<input type="email" class="form-control" name="sender_email" id="sender_email" placeholder="' . _uc($_e['Sender Email']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender City']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="sender_city" id="sender_city" required placeholder="' . _uc($_e['Sender City']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Country']) . '</label>
<div class="col-sm-9">
<fieldset class="sender_countryFieldset">
<select required  id="sender_country" name="sender_country" class="form-control">
<option value="">------</option>
' . $country_list . '
</select>
</fieldset>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Post Code']) . '</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="sender_post" id="sender_post" required="" value="" placeholder="' . _uc($_e['Sender Post Code']) . '"/>
</div>
</div>

<div class="form-group">
<label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Address']) . '</label>
<div class="col-sm-9">
<textarea class="form-control" name="sender_address" id="sender_address" required placeholder="' . _uc($_e['Sender Address']) . '"></textarea>
</div>
</div>

</div><!-- col-md-12 sender info end -->
</div>
<!-- col-md-6 sender info end -->

</div> <!-- Send Receiver Info-->

<div class="clearfix"></div>

<div class="container-fluid ReviewButtons">
<button type="button" onclick="return viewOrderReport();" value="Last Order View" class="submit btn btn-info">' . _uc($_e['Last Order View']) . '</button>
</div>

<div class="clearfix"></div>
<br>
<div class="container-fluid ReviewButtons">
<button type="button" onclick="return finalPrice();" name="submit" value="ORDER" class="submit btn btn-primary btn-lg">' . _u($_e['ORDER']) . '</button>
</div>
<div class="clearfix"></div>
<br/>';

        $viewBody = '
<div class="FinalPriceReport">
<div class="h4"> ' . _uc($_e['Order  Price']) . ' : <span class="totalPriceModel bold"></span></div>
<div class="h4"> ' . _uc($_e['Shipping Price']) . ' : <span class="totalPriceShipping bold"></span></div>
<div class="h4"> ' . _uc($_e['Total']) . ' : <span class="totalFinal bold"></span></div>
</div>
<br>
<div id="submitButtons">
<button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER" class="submit btn btn-primary">' . _uc($_e['ORDER']) . '</button>
<button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER AND PROCESS" class="submit btn btn-primary">' . _uc($_e['ORDER AND PROCESS']) . '</button>
</div>
<br>';

        $this->functions->customDialogView('Check Out', $viewBody, 'Close');

        echo '
<!-- if you change value of button then must change from addNewOrder();
</div> <!-- added product script div end -->

</div> <!-- form-horizontal end -->
</form>
</div>
<div class="container-fluid lastReview displaynone">
<div class="reportReview">
<div class="form-horizontal">
<div class="col-md-6">

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Store Country']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportStoreCountry">View</div>
</div>
</div>

<div class="form-group">
<label  class="col-sm-4 col-md-5">' . _uc($_e['Payment Type']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportPaymentType">View</div>
</div>
</div>
</div><!-- col-md-6 end -->
<div class="col-md-6">
<div class="form-group">
<label  class="col-sm-4 col-md-5">' . _uc($_e['Invoice Status']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportInvoiceStatus">View</div>
</div>
</div>

<div class="form-group">
<label  class="col-sm-4 col-md-5">' . _uc($_e['Payment Info']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportPaymentInfo">View</div>
</div>
</div>

</div><!-- col-md-6 end -->
</div><!-- Form horizontal 1 end-->
<hr>
<h4>' . _uc($_e['Selected Products']) . '</h4>
<div class="col-sm-12" id="reportSelectedProduct"></div>
<hr>
<h4>' . _uc($_e['Sender And Receiver Information']) . '</h4>

<div class="form-horizontal">
<div class="col-md-6">

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Sender Name']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportSenderName">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Sender Phone']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportSenderPhone">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Sender Email']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportSenderEmail">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Sender City']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportSenderCity">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Sender Country']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportSenderCountry">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Sender Address']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportSenderAddress">View</div>
</div>
</div>

</div><!-- col-sm-6 end 1-->

<div class="col-md-6">

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Receiver Name']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportReceiverName">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Receiver Phone']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportReceiverPhone">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Receiver Email']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportReceiverEmail">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Receiver City']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportReceiverCity">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Receiver Country']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportReceiverCountry">View</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 col-md-5">' . _uc($_e['Receiver Address']) . '</label>
<div class="col-sm-8 col-md-7">
<div id="reportReceiverAddress">View</div>
</div>
</div>

</div> <!--col sm 6 end 2-->

</div><!-- Form horizontal 2 end-->
</div>
</div><!-- Last review Info-->';


    }



    public function addNewOrder()
    {
        global $_e;
        if (!$this->functions->getFormToken('orderAdd')) {
            return false;
        }
        $btn1 = 'ORDER';
        $btn2 = 'ORDER AND PROCESS';
        //set Submit buttons value here
        if (isset($_POST) && !empty($_POST) && !empty($_POST['cart_list']) && !empty($_POST['receiver_name']) && !empty($_POST['receiver_country'])) {


            try {
                $this->db->beginTransaction();
                if ($_POST['submit'] == $btn1) {
                    $process = 0;
                } else if ($_POST['submit'] == $btn2) {
                    $process = 1; //submit product quantity from inventory
                } else {
                    throw new Exception("");
                }

                $invoiceId = '';
                @$paymentType = $_POST['paymentType']; //int
                @$payment_info = $_POST['paymentInfo']; //text
                @$invoiceStatus = $_POST['invoiceStatus']; // varchar
                @$total_price = $_POST['totalPrice']; //Using In Security, If price from web form or php calculated not match, mean Hacking Attempt
                @$price_code = $_POST['priceCode'];
                @$country = $_POST['storeCountry'];
                @$userId = $_POST['userId'];
                @$totalWeightReceiveFromForm = $_POST['totalWeight']; //Using In Security, If Weight from web form or php calculated not match, mean Hacking Attempt
                $total_priceNew = 0; //Calculateing in foreach loop, test with $total_price after loop, If not match its hacking attempt
                $total_weightNew = 0;//Calculateing in foreach loop, test with $totalWeightReceiveFromForm after loop, If not match its hacking attempt


                $countryData = $this->productF->productCountryId($country);
                $countryId = $countryData['cur_id'];

                //major data submit here, will later here, update this table
                $now = date('Y-m-d H:i:s');
                $sql = "INSERT INTO `order_invoice`
        (
        `paymentType`,
        `invoice_date`,
        `orderUser`,
        `payment_info`,
        `price_code`,
        `invoice_status`
        )
        VALUES (
        ?,?,?,?,?,?
        )";
                $array = array($paymentType, $now, $userId, $payment_info, $price_code, $invoiceStatus);
                $this->dbF->setRow($sql, $array, false);
                $invoiceId = $this->dbF->rowLastId;
                // invoice first data Enter

                //Invoice Product add
                foreach ($_POST['cart_list'] as $key => $id) {
                    $pArray = explode("_", $id); //p_491-246-435-5    => p_ pid - scaleId - colorId - storeId;
                    $pIds = $pArray[1];
                    $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                    $pId = $pArray[0]; // 491
                    $scaleId = $pArray[1]; // 426
                    $colorId = $pArray[2]; // 435
                    $storeId = $pArray[3]; // 5
                    @$customId = $pArray[4]; // 5

                    $pName = $this->productF->getProductFullName($pId, $scaleId, $colorId);
                    $storeName = $this->productF->getStoreName($storeId);
                    $pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $country);

                    //price calculation
                    $salePrice = $_POST['pTotalprice_' . $id];
                    $discount = floatval($_POST['pDiscount_' . $id]);
                    $total_priceNew += floatval($salePrice);
                    $saleQTY = $_POST['pQty_' . $id];
                    $salePrice = ($salePrice + $discount) / $saleQTY; // get single product QTY price

                    //Weight Calculation
                    $weight = $this->productF->getProductWeight($pId, $scaleId, $colorId);
                    $total_weightNew += $weight * $saleQTY;

                    @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
                    $hash = md5($hashVal);

                    $sql = "INSERT INTO `order_invoice_product`
            (
            `order_invoice_id`,
            `order_pIds`,
            `order_pName`,
            `order_pStore`,
            `order_pPrice`,
            `order_salePrice`,
            `order_discount`,
            `order_pQty`,
            `order_pWeight`,
            `order_process`,
            `order_hash`
            ) VALUES (
            ?,?,?,?,?,?,?,?,?,?,?
            )";


                    $array = array($invoiceId, $pIds, $pName, $storeName, $pPrice, $salePrice, $discount, $saleQTY, $weight, $process, $hash);
                    $this->dbF->setRow($sql, $array, false);

                    // Remove QTY FROM inventory
                    if ($process == 1) {
                        $invQty = $this->productF->stockProductQty($hash);
                    } // If Process Order End
                    $add_and_minus_product_to_intranet = $this->functions->ibms_setting('add_and_minus_product_to_intranet');
                } // Foreach loop End

                //check php calculate price and javascript price
                if (floatval($total_price) != floatval($total_priceNew)) {
                    throw new Exception("Hacking Attempt Found Code : 151");
                }

                //check php calculate weight and javascript weight
                if (floatval($totalWeightReceiveFromForm) != floatval($total_weightNew)) {
                    throw new Exception("Hacking Attempt Found Code : 152");
                }

                // User Info Add
                //first add order invoice,, addNewOrder(); // not klarna
                if (intval($paymentType) != intval('2')) {
                    $sql = "INSERT INTO `order_invoice_info`
            (
            `order_invoice_id`,
            
            `sender_name`,
            `sender_phone`,
            `sender_email`,
            `sender_address`,
            `sender_city`,
            `sender_country`,
            `sender_post`,
            
            `receiver_name`,
            `receiver_phone`,
            `receiver_email`,
            `receiver_address`,
            `receiver_city`,
            `receiver_country`,
            `receiver_post`
            )
            VALUES (
            ?,
            ?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?
            )";
                    $array = array(
                        $invoiceId,
                        $_POST['sender_name'],
                        $_POST['sender_phone'],
                        $_POST['sender_email'],
                        $_POST['sender_address'],
                        $_POST['sender_city'],
                        $_POST['sender_country'],
                        $_POST['sender_post'],
                        $_POST['receiver_name'],
                        $_POST['receiver_phone'],
                        $_POST['receiver_email'],
                        $_POST['receiver_address'],
                        $_POST['receiver_city'],
                        $_POST['receiver_country'],
                        $_POST['receiver_post'],
                    );
                    $this->dbF->setRow($sql, $array, false);
                }
                //Update invoice after
                //Calculating Shiping price
                $shippingData = $this->productF->shippingPrice($country, $_POST['receiver_country']);
                if ($shippingData == false) {
                    //throw new Exception("Hacking Attempt Found OR Shipping Error");
                    throw new Exception($_e["Shipping Error"]);
                }
                $shippingWeight = $shippingData['shp_weight'];
                $shippingPrice = $shippingData['shp_price'];
                $unitWeight = ceil($total_weightNew / $shippingWeight);
                $unitWeight = round($unitWeight, 2);
                $finalShippingPrice = $shippingPrice * $unitWeight;
                $total_priceNew += $finalShippingPrice;


                $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with'); // Invoice Number start with


                if (intval($paymentType) === intval('2')) {
                    $processStatus = 'inComplete';
                } else {
                    $processStatus = 'process';
                }

                $sql = "UPDATE `order_invoice` SET
        `invoice_id`    =   ?,
        `total_price`   =   ?,
        `ship_price`     =   ?,
        `total_weight`  =   ?,
        `orderStatus`       =   ?,
        `shippingCountry`   =   ?
        WHERE `order_invoice_pk`  = ?";

                $this->dbF->setRow($sql, array($invoiceKey . '' . $invoiceId, $total_priceNew, $finalShippingPrice, $total_weightNew, $processStatus, $_POST['receiver_country'], $invoiceId), false);
                $this->db->commit();
                if ($this->dbF->rowCount > 0) {
                    $msg = $this->functions->notificationError(_js(_uc($_e['Product Successfully Submit'])), _js($_e['Thank you your product is successfully submit']), 'btn-success');
                    $_SESSION['msg'] = base64_encode($msg);
                    $this->functions->setlog(_uc($_e['New Order']), _uc($_e['ORDER']), $invoiceKey . '' . $invoiceId, $_e['New Order Added Successfully']);
                } else {
                    $msg = $this->functions->notificationError(_js(_uc($_e['Product Submit'])), _js(_uc($_e['Product Submit Failed'])), 'btn-danger');
                    $_SESSION['msg'] = base64_encode($msg);
                }
                $this->productF->paymentProcess($paymentType);
            } catch (Exception $e) {
                $this->dbF->error_submit($e);
                $this->db->rollBack();
                $msg = '';
                $msg = $e->getMessage();
                if ($msg != '') {
                    $msg = $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])), $msg, 'btn-danger');
                }
                $msg = $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])), _js($_e['Some thing went wrong Please try again']), 'btn-danger');
                $_SESSION['msg'] = base64_encode($msg);
            }

        } else if (isset($_POST) && !empty($_POST) && ($_POST['submit'] == $btn1 || $_POST['submit'] == $btn2)) {
            $msg = $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])), _js($_e['Some thing went wrong Please try again']), 'btn-danger');
            $_SESSION['msg'] = base64_encode($msg);
        }

    }



    public function invoiceOrdersSql()
    {
        $sql = "SELECT * FROM `order_invoice` WHERE orderStatus != 'inComplete' AND invoice_status != '3' AND invoice_status != '1' AND invoice_status != '0' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function all($user_id = false)
    {
        $user = "";
        $array = array();
        if (!empty($user_id)) {
            $user = " WHERE `orderUser` = ? ";
            $array[] = $user_id;
        }
        $sql = "SELECT * FROM `order_invoice` $user ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql, $array);
        return $invoice;
    }

    public function completeOrdersSql()
    {
        $sql = "SELECT * FROM `order_invoice` WHERE invoice_status = '3' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function cancelOrdersSql()
    {
        $sql = "SELECT * FROM `order_invoice` WHERE invoice_status = '0' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function inCompleteOrdersSql()
    {
        $sql = "SELECT * FROM `order_invoice` WHERE orderStatus = 'inComplete' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }

    public function invoiceListSReady($order = '', $user_id = false)
    {
        global $_e;
        $class = "dTable";
        $data_attr = '';
        switch ($order) {
            case 'complete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;
            case 'cancel':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'incomplete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'user':
                $invoice = $this->all($user_id);
                break;
            case 'invoices':
                $invoice = array();
                break;
            case 'invoicesReady':
                $invoice = $this->invoiceOrdersSql();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            case 'invoicesSReady':
                $invoice = $this->invoiceOrdersSql();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            default:
                $invoice = array();
                $class = '';
                break;
        }

        echo '<div class="table-responsive search_manually">
            <table class="table table-hover dTable tableIBMS">
            <thead>
            <th>' . _u($_e['SNO']) . '</th>
            <th>' . _u($_e['INVOICE']) . '</th>
            <th>' . _u($_e['Country']) . '</th>
            <th>' . _u($_e['INVOICE DATE']) . '</th>
            <th>' . _u($_e['CUSTOMER EMAIL']) . '</th>
            <th>' . _u($_e['SOLD PRICE']) . '</th>
            <th>' . _u($_e['PAYMENT METHOD']) . '</th>
            <th>' . _u($_e['Transaction Id']) . '</th>
            <th>' . _u($_e['ORDER PROCESS - Invoice Status']) . '</th>
            <th>' . _u($_e['Update Invoice Status']) . '</th>
            <th width="120">' . _u($_e['ACTION']) . '</th>
    </thead>
    <tbody>';
        $cCount = 0;
        $i = 0;
        $j = 0;
        $ccc = 1;

        foreach ($invoice as $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == '0')
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '1')
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '2')
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '3')
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];
            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);
            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_Name = $orderInfo['sender_name'];

            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }

            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint.php?mailId=$invoiceId' target='_blank' class='btn'>
            <i class='fa fa-file'></i>
            </a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);

            if ($val['paymentType'] == "2") {
                $paymentMethod = "<div class='btn btn-success  btn-sm'>$paymentMethod</div>";
            } else if ($val['paymentType'] == "0") {
                $paymentMethod = "<div class='btn btn-danger  btn-sm'>$paymentMethod</div>";
            } else {
                $paymentMethod = "<div class='btn btn-default  btn-sm'>$paymentMethod</div>";
            }

            $cur_symbol = md5($val['price_code']);
            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();

            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div sadasas'>{{form}}</div>"
            );

            $invoice_status = $this->functions->print_form($form_invoice, "", false);
            $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                <i class='glyphicon glyphicon-edit'></i>
                </a>";

            if (!empty($user_id)) {
                $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>
            <i class='glyphicon glyphicon-edit'></i>
            </a>";
            }

            $dealProCount = 0;
            $dealProCountInventry = 0;
            $sum = 0;
            $sqlCount = "SELECT deal,order_invoice_id,COUNT(invoice_product_pk) as ttl FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId'";
            $datacount = $this->dbF->getRow($sqlCount);
            $ttl = $datacount['ttl'];
            $order_invoice_idbb = $datacount['order_invoice_id'];

            if ($ttl >= 2 && $datacount['deal'] == 0) {
                $sqlccv = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$order_invoice_idbb'";
                $variable = $this->dbF->getRows($sqlccv);

                $totalProductInvoice = sizeof($variable);
                $totalProductFoundInventory = 0;
                $productsMetaData = array();

                if ($this->dbF->rowCount > 0) {
                    $divi = 0;
                    $ggg = "";
                    $s = "";
                    $nt = 0;
                    foreach ($variable as $key => $html) {
                        $pIds = $html['order_pIds'];
                        $order_pQty = $html['order_pQty'];
                        $order_pName = $html['order_pName'];
                        $eX = explode(" - ", $order_pName, 2);
                        $s = @$eX[1];
                        $info = unserialize($html['info']);
                        $pArray = explode("-", $pIds);
                        $pId = filter_var(
                            $pArray[0],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $scaleId = filter_var(
                            $pArray[1],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $colorId = filter_var(
                            @$pArray[2],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $storeId = filter_var(
                            @$pArray[3],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );

                        $sp = "SELECT prosiz_name FROM `product_size` WHERE `prosiz_id` = '$scaleId' ";
                        $spData = $this->dbF->getRow($sp);

                        if ($spData === false || empty($spData)) {
                            $pids = explode(' - ', $order_pName);
                            $spData = [];
                            $spData['prosiz_name'] = end($pids);
                        }

                        $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' AND `prosiz_cur_id` = 20 AND `prosiz_name` = '$spData[prosiz_name]'";
                        $spData = $this->dbF->getRow($sp);

                        if ($this->dbF->rowCount > 0) {
                            $scaleId = $spData['prosiz_id'];
                        } else {
                            $scaleId = filter_var(
                                $pArray[1],
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                            );
                        }

                        @$customId = filter_var(
                            $pArray[4],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $pId = $pId; // 491

                        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
                        $hash = md5($hashVal);
                        $sqlcc = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = '$spData[prosiz_name]' AND `qty_product_id` = '$pId'";
                        $invvD = $this->dbF->getRow($sqlcc);

                        if (is_array($invvD)) {
                            $productsMetaData[$invoiceId][] = array('pid' => $pId, 'pName' => $order_pName, 'pids' => $html['order_pIds'], 'order_qty' => $order_pQty, "invent_qty" => $invvD['qty_item']);
                        }
                        if (is_array($invvD)) {
                            if ($invvD['qty_item'] >= $order_pQty) {
                                $s = "t";
                                $nt++;
                                $totalProductFoundInventory++;
                            } else {
                                $s = "f";
                            }
                        }
                        if (is_array($invvD)) {
                            $ggg .= "<pre>p  " . $pId . "q  " . $order_pQty . "i  " . $invvD['qty_item'] . "s " . $s . "</pre>";
                        }

                    }
                }

                $invProPercentage = ceil(($totalProductFoundInventory / $totalProductInvoice) * 100);
                if ($invProPercentage < 50 || $invProPercentage == 100) {
                } else {
                    $divi = $ttl / 2;
                    $divi = intval($divi);
                    echo "<tr><td>s.no$ccc <br><span data-tooltip='Yes, ";
                    echo $nt;
                    echo "items exist in inventory.' data-tooltip-position='bottom' title='Yes, $nt items exist in inventory.' style='font-size:13px; color: blue;'>&#10004; 
                        </span></td>
                        <td><a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>$val[invoice_id]</a></td>
                        <td>$country</td>
                        <td>$invoiceDate</td>
                        <td>$customer_Name</td>
                        <td><span  class='countMe_{$order}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]</td>
                        <td>$paymentMethod</td>
                                        <td>$val[inTransaction]</td>
                        <td>$orderProcess</td>
                        <td>$divInvoice $invoice_status</td>
                        <td>
                        <div class='btn-group btn-group-sm'>
                        $inoivcePdf
                        $edit_link ";
                    if ($date < $minusDays) {
                        echo "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
                            <i class='glyphicon glyphicon-trash trash'></i>
                            <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
                    } else {
                        echo "<a class='btn'>
                        <i class='glyphicon glyphicon-trash '></i>
                        <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                        </a>";
                    }
                    echo "</div>
                        </td>
                        </tr>";
                    $ccc++;
                }
            }
        }

        echo '</tbody>
            </table>
            </div> <!-- .table-responsive End -->';
    }

    public function invoiceListReady($order = '', $user_id = false)
    {

        global $_e;
        $class = "dTable";
        $data_attr = '';
        switch ($order) {
            case 'complete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;
            case 'cancel':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'incomplete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'user':
                $invoice = $this->all($user_id);
                break;
            case 'invoices':
                $invoice = array();
                break;
            case 'invoicesReady':
                $invoice = $this->invoiceOrdersSql();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            default:
                $invoice = array();
                $class = '';
                break;
        }

        echo '<div class="table-responsive search_manually">
            <table class="table table-hover dTable tableIBMS">
            <thead>
            <th>' . _u($_e['SNO']) . '</th>
            <th>' . _u($_e['INVOICE']) . '</th>
            <th>' . _u($_e['Country']) . '</th>
            <th>' . _u($_e['INVOICE DATE']) . '</th>


            <th>' . _u($_e['CUSTOMER EMAIL']) . '</th>
            <th>' . _u($_e['SOLD PRICE']) . '</th>
            <th>' . _u($_e['PAYMENT METHOD']) . '</th>

            <th>' . _u($_e['Transaction Id']) . '</th>
            <th>' . _u($_e['ORDER PROCESS - Invoice Status']) . '</th>
            <th>' . _u($_e['Update Invoice Status']) . '</th>


            <th width="120">' . _u($_e['ACTION']) . '</th>
            </thead>
            <tbody>';
        $cCount = 0;
        $i = 0;

        foreach ($invoice as $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == '0')
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '1')
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '2')
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '3')
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";


            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];
            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);
            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_Name = $orderInfo['sender_name'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }
            if($invoiceStatus === "Manually Created"){
                continue;
            }
            //Check order process or not,, if single product process it show 1

            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            // 6
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";

            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                // 6
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }


            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));
            $inoivcePdf = '';

            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint.php?mailId=$invoiceId' target='_blank' class='btn'>
                <i class='fa fa-file'></i>
                </a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);

            if ($val['paymentType'] == "2") {
                $paymentMethod = "<div class='btn btn-success  btn-sm'>$paymentMethod</div>";
            } else if ($val['paymentType'] == "0") {
                $paymentMethod = "<div class='btn btn-danger  btn-sm'>$paymentMethod</div>";
            } else {
                $paymentMethod = "<div class='btn btn-default  btn-sm'>$paymentMethod</div>";
            }

            $cur_symbol = md5($val['price_code']);
            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();

            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div aaaaaaaaaa'>{{form}}</div>"
            );

            $invoice_status = $this->functions->print_form($form_invoice, "", false);
            $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
        <i class='glyphicon glyphicon-edit'></i>
        </a>";

            if (!empty($user_id)) {
                $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>
            <i class='glyphicon glyphicon-edit'></i>
            </a>";
            }

            //Check order process or not,, if single product process it show 1
            $dealProCount = 0;
            $dealProCountInventry = 0;
            $sum = 0;

            $sqlCount = "SELECT COUNT(invoice_product_pk) as ttl FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId'";
            $datacount = $this->dbF->getRow($sqlCount);

            // Roadies - XS
            // jacka - L
            // jacka - XXS
            // jacka - 3XL
            // shirt  - XXL
            // shirt  - L
            $sqlccv = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' and `deal` = '0'";
            $variable = $this->dbF->getRows($sqlccv);

            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";

            if ($this->dbF->rowCount > 0) {
                foreach ($variable as $key => $html) {
                    $pIds = $html['order_pIds'];
                    $order_pQty = $html['order_pQty'];
                    $order_pName = $html['order_pName'];
                    $info = unserialize($html['info']);
                    $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                    $pId = $pArray[0]; // 491
                    @$scaleId = $pArray[1]; // 426

                    $sp = "SELECT prosiz_name FROM `product_size` WHERE `prosiz_id` = '$scaleId'";
                    $spData = $this->dbF->getRow($sp);

                    if ($spData === false || empty($spData)) {
                        $pids = explode(' - ', $html['order_pName']);
                        $spData = [];
                        $spData['prosiz_name'] = end($pids);
                    } else {
                        $pids = explode(' - ', $html['order_pName']);
                        $tempScale = $pids[count($pids) - 1];
                        if ($spData['prosiz_name'] == $tempScale) {
                        } else {
                            $spData['prosiz_name'] = $tempScale;

                        }
                    }
                    $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' AND `prosiz_cur_id` = 20 AND `prosiz_name` = '$spData[prosiz_name]'";
                    $spData = $this->dbF->getRow($sp);

                    if ($this->dbF->rowCount > 0) {
                        $scaleId = $spData['prosiz_id'];
                    } else {
                        @$scaleId = filter_var(
                            $pArray[1],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                    }

                    @$colorId = $pArray[2]; // 435
                    @$storeId = $pArray[3]; // 5
                    @$customId = $pArray[4]; // 5
                    @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
                    $hash = md5($hashVal);
                    $sqlcc = "SELECT * FROM `product_inventory` WHERE (`qty_product_scale_name` = '$spData[prosiz_name]' OR `qty_product_scale` = '$scaleId') AND `qty_item` != '0' AND `qty_product_id` = '$pId' AND `qty_item` >= '$order_pQty'";
                    $this->dbF->getRow($sqlcc);

                    if ($this->dbF->rowCount == 0) {
                        continue;
                    } else {
                        $sum++;
                    }
                }

                if ($datacount['ttl'] == $sum) {
                    $cCount++;
                    $i++;
                } else {
                    continue;
                }
            } else {
                $sqldeal = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' and `deal` != '0'";
                $variableDeal = $this->dbF->getRow($sqldeal);

                @$info = unserialize($variableDeal['info']);

                if (!empty($info)) {
                    foreach ($info as $keys => $value) {
                        $pIds = explode("-", $value['pIds']);
                        @$hashVal = $pIds[0] . ":" . $pIds[1] . ":0:6";
                        $hash = md5($hashVal);

                        $sqlcc = "SELECT * FROM `product_inventory` WHERE `product_store_hash` = '$hash' AND `qty_item` != '0' and `qty_item` >= '$pQty'";
                        $this->dbF->getRow($sqlcc);
                        
                        if ($this->dbF->rowCount == 0) {
                        } else {
                            $dealProCountInventry++;
                        }
                        $dealProCount++;
                    }

                    if ($dealProCount != $dealProCountInventry) {
                        continue;
                    } else {
                        $cCount++;
                        $i++;
                        $sum++;
                    }

                }
            }
            echo "<tr>
            <td>$cCount</td>
            <td><a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>$val[invoice_id]</a></td>
            <td>$country</td>
            <td>$invoiceDate</td>
            <td>$customer_Name</td>
            <td><span  class='countMe_{$order}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]</td>
            <td>$paymentMethod</td>
                            <td>$val[inTransaction]</td>
            <td>$orderProcess</td>
            <td>$divInvoice $invoice_status</td>
            <td>
            <div class='btn-group btn-group-sm'>
            $inoivcePdf
            $edit_link ";
            if ($date < $minusDays) {
                echo "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
            <i class='glyphicon glyphicon-trash trash'></i>
            <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            </a>";
            } else {
                echo "<a class='btn'>
            <i class='glyphicon glyphicon-trash '></i>
            <i class='glyphicon glyphicon-ban-circle combineicon'></i>
            </a>";
            }

            echo "</div>
                </td>
                </tr>";
        }

        echo '</tbody>
            </table>
            </div> <!-- .table-responsive End -->';

    }

    public function invoiceListRelativeReady($order = '', $user_id = false)
    {
        global $_e;
        $class = "dTable";
        $data_attr = '';
        
        switch ($order) {
            case 'complete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;
            case 'cancel':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'incomplete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'user':
                $invoice = $this->all($user_id);
                break;
            case 'invoices':
                $invoice = array();
                break;
            case 'invoicesReady':
                $invoice = $this->invoiceOrdersSql();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            case 'invoicesSReady':
                $invoice = $this->invoiceOrdersSql();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            case 'invoicesRelativeReady':
                $invoice = $this->invoiceOrdersSql();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            default:
                $invoice = array();
                $class = '';
                break;
        }

        echo '<div class="table-responsive search_manually">
            <table class="table table-hover dTable tableIBMS">
            <thead>
                <tr>
                    <th>' . _u($_e['SNO']) . '</th>
                    <th>' . _u($_e['INVOICE']) . '</th>
                    <th>' . _u($_e['Country']) . '</th>
                    <th>' . _u($_e['INVOICE DATE']) . '</th>
                    <th>' . _u($_e['CUSTOMER EMAIL']) . '</th>
                    <th>' . _u($_e['SOLD PRICE']) . '</th>
                    <th>' . _u($_e['PAYMENT METHOD']) . '</th>
                    <th>' . _u($_e['Transaction Id']) . '</th>
                    <th>' . _u($_e['ORDER PROCESS - Invoice Status']) . '</th>
                    <th>' . _u($_e['Update Invoice Status']) . '</th>
                    <th width="120">' . _u($_e['ACTION']) . '</th>
                </tr>
            </thead>
            <tbody>';
        $cCount = 0;
        $i = 0;
        $j = 0;
        $ccc = 1;
        foreach ($invoice as $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";

            if ($st == '0')
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '1')
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '2')
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '3')
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];

            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_Name = $orderInfo['sender_name'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }


            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint.php?mailId=$invoiceId' target='_blank' class='btn'>
                <i class='fa fa-file'></i>
                </a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);

            if ($val['paymentType'] == "2") {
                $paymentMethod = "<div class='btn btn-success  btn-sm'>$paymentMethod</div>";
            } else if ($val['paymentType'] == "0") {
                $paymentMethod = "<div class='btn btn-danger  btn-sm'>$paymentMethod</div>";
            } else {
                $paymentMethod = "<div class='btn btn-default  btn-sm'>$paymentMethod</div>";
            }

            $cur_symbol = md5($val['price_code']);
            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();

            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div sadasas'>{{form}}</div>"
            );

            $invoice_status = $this->functions->print_form($form_invoice, "", false);
            $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                <i class='glyphicon glyphicon-edit'></i>
                </a>";
            if (!empty($user_id)) {
                $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>
                <i class='glyphicon glyphicon-edit'></i>
                </a>";
            }

            $dealProCount = 0;
            $dealProCountInventry = 0;
            $sum = 0;
            $sqlCount = "SELECT deal,order_invoice_id,COUNT(invoice_product_pk) as ttl FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId'";
            $datacount = $this->dbF->getRow($sqlCount);
            $ttl = $datacount['ttl'];
            $order_invoice_idbb = $datacount['order_invoice_id'];

            if ($ttl >= 1 && $datacount['deal'] == 0) {
                $sqlccv = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$order_invoice_idbb'";
                $variable = $this->dbF->getRows($sqlccv);
                
                $totalProductInvoice = sizeof($variable);
                $totalProductFoundInventory = 0;
                $productsMetaData = array();
                if ($this->dbF->rowCount > 0) {
                    $divi = 0;
                    $ggg = "";
                    $s = "";
                    $nt = 0;
                    $invoiceRelativeProductArray = [];
                    foreach ($variable as $key => $html) {
                        $pIds = $html['order_pIds'];
                        $order_pQty = $html['order_pQty'];
                        $order_pName = $html['order_pName'];
                        $eX = explode(" - ", $order_pName, 2);
                        $s = @$eX[1];
                        $info = unserialize($html['info']);
                        $pArray = explode("-", $pIds);
                        $pId = filter_var(
                            $pArray[0],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $scaleId = filter_var(
                            $pArray[1],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $colorId = filter_var(
                            $pArray[2],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );
                        $storeId = filter_var(
                            $pArray[3],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );

                        $sp = "SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id` = '$scaleId'";
                        $spData = $this->dbF->getRow($sp);

                        if ($spData === false || empty($spData)) {
                            $pids = explode(' - ', $order_pName);
                            $spData = [];
                            $spData['prosiz_name'] = end($pids);
                        }

                        $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' AND `prosiz_cur_id` = 20 AND `prosiz_name` = '$spData[prosiz_name]'";
                        $spData = $this->dbF->getRow($sp);

                        if ($this->dbF->rowCount > 0) {
                            $scaleId = $spData['prosiz_id'];
                        } else {
                            $scaleId = filter_var(
                                $pArray[1],
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                            );
                        }

                        @$customId = filter_var(
                            $pArray[4],
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );

                        $pId = $pId;

                        $sqlcc = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ?";
                        $invvD = $this->dbF->getRow($sqlcc, [$spData['prosiz_name'], $pId]);

                        $invoiceRelativeProduct = $this->invoiceRelativeProduct($order_pName, $html['order_pIds']);
                        $invoiceRelativeProductArray[] = $invoiceRelativeProduct;

                        if (is_array($invvD)) {
                            if ($invvD['qty_item'] >= $order_pQty){
                                foreach ($invoiceRelativeProductArray as $key => $value) {
                                    if ($value === true) {
                                        unset($invoiceRelativeProductArray[$key]);
                                        break;
                                    }
                                }
                            }
                        }
                        
                        if (is_array($invvD)) {
                            $productsMetaData[$invoiceId][] = array('pid' => $pId, 'pName' => $order_pName, 'pids' => $html['order_pIds'], 'order_qty' => $order_pQty, "invent_qty" => $invvD['qty_item']);
                        }
                        
                        if (is_array($invvD)) {
                            if ($invvD['qty_item'] >= $order_pQty) {
                                $s = "t";
                                $nt++;
                                $totalProductFoundInventory++;
                            } else {
                                $s = "f";
                            }
                        }
                        if (is_array($invvD)) {
                            $ggg .= "<pre>p  " . $pId . "q  " . $order_pQty . "i  " . $invvD['qty_item'] . "s " . $s . "</pre>";
                        }
                    }
                }
                $invProPercentage = ceil(($totalProductFoundInventory / $totalProductInvoice) * 100);

                if ($invProPercentage == 100 || in_array(false, $invoiceRelativeProductArray)) {
                } else {
                    echo "<tr>
                    <td>s.no$ccc <br><span data-tooltip='Yes, ";
                    echo $nt;
                    echo "items exist in inventory.' data-tooltip-position='bottom' title='Yes, $nt items exist in inventory.' style='font-size:13px; color: blue;'>&#10004;
                    </span></td>
                    <td><a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>$val[invoice_id]</a></td>
                    <td>$country</td>
                    <td>$invoiceDate</td>
                    <td>$customer_Name</td>
                    <td><span  class='countMe_{$order}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]</td>
                    <td>$paymentMethod</td>
                                    <td>$val[inTransaction]</td>
                    <td>$orderProcess</td>
                    <td>$divInvoice $invoice_status</td>
                    <td>
                    <div class='btn-group btn-group-sm'>
                    $inoivcePdf
                    $edit_link ";
                    if ($date < $minusDays) {
                        echo "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
                        <i class='glyphicon glyphicon-trash trash'></i>
                        <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                        </a>";
                    } else {
                        echo "<a class='btn'>
                        <i class='glyphicon glyphicon-trash '></i>
                        <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                        </a>";
                    }

                    echo "</div>
                        </td>
                        </tr>";
                    $ccc++;
                }
            }
        }
        echo '</tbody>
            </table>
            </div> <!-- .table-responsive End -->';

    }

    public function invoiceRelativeProduct($pName, $pIds)
    {
        $pIds = explode('-', $pIds);

        $relativeProductsDiv = false;

        @$pId = $pIds[0];
        @$scaleId = $pIds[1];
        @$colorId = $pIds[2];
        @$storeId = $pIds[3];
        @$customId = $pIds[4];

        $eX = explode(" - ", $pName, 2);
        $sizeName = @$eX[1];
        $proName = @$eX[0];

        $sql = "SELECT `setting_val` FROM `product_setting` WHERE `p_id` = ? AND setting_name = ?";
        $data = $this->dbF->getRow($sql, [$pId, 'relative']);
        if ($this->dbF->rowCount > 0) {
            $relativeProducts = unserialize($data['setting_val']);
            
            foreach ($relativeProducts as $key => $val) {
                    
                if ($sizeName == NULL) {
                    $sp = "SELECT * FROM `product_inventory` WHERE `qty_product_id` = '$val' AND `qty_product_scale` = '$scaleId'";
                } else {
                    $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$val' AND `prosiz_cur_id` = 20 and `prosiz_name`= '$sizeName'";
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
                        $relativeProductsDiv = true;
                    } else {
                        $qty = 0;
                    }
                }
            }
        }
        return $relativeProductsDiv;
    }

    public function invoiceList2($order = '', $user_id = false)
    {
        global $_e;
        $href = "order/order_ajax.php?page=data_ajax_" . $order;
        $class = "dTable";
        $data_attr = '';
        switch ($order) {
            case 'complete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;

            case 'inc':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;
            case 'cancel':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'incomplete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'user':
                $invoice = $this->all($user_id);
                break;
            case 'invoices':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            default:
                $invoice = array();
                $class = '';
                break;
        }

        echo '
<div class="table-responsive search_manually">
<table class="table table-hover ' . $class . ' tableIBMS" data-href="' . $href . '" ' . $data_attr . '>
<thead>
<th>' . _u($_e['SNO']) . '</th>
<th>' . _u($_e['INVOICE']) . '</th>
<th>' . _u($_e['Country']) . '</th>
<th>' . _u($_e['INVOICE DATE']) . '</th>
<th>' . _u($_e['CUSTOMER NAME']) . '</th>
<th>' . _u($_e['CUSTOMER EMAIL']) . '</th>
<th>' . _u($_e['SOLD PRICE']) . '</th>
<th>' . _u($_e['PAYMENT METHOD']) . '</th>

<th>' . _u($_e['Transaction Id']) . '</th>
<th>' . _u($_e['ORDER PROCESS - Invoice Status']) . '</th>
<th>' . _u($_e['Update Invoice Status']) . '</th>
<th>' . _u($_e['Flagged']) . '</th>
<th width="120">' . _u($_e['ACTION']) . '</th>
</thead>
<tbody>';

        $i = 0;
        foreach ($invoice as $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == '0')
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '1')
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '2')
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '3')
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";


            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];

            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_Name = $orderInfo['sender_name'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }


            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint.php?mailId=$invoiceId' target='_blank' class='btn'>
<i class='fa fa-file-pdf-o'></i>
</a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);


            if ($val['paymentType'] == "2") {
                $paymentMethod = "<div class='btn btn-success  btn-sm'>$paymentMethod</div>";
            } else if ($val['paymentType'] == "0") {
                $paymentMethod = "<div class='btn btn-danger  btn-sm'>$paymentMethod</div>";
            } else {
                $paymentMethod = "<div class='btn btn-default  btn-sm'>$paymentMethod</div>";
            }

            $cur_symbol = md5($val['price_code']);


            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();
            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div deeeeeeee'>{{form}}</div>"
            );
            $invoice_status = $this->functions->print_form($form_invoice, "", false);

            $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>";
            if (!empty($user_id)) {
                $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>";
            }

            echo "<tr>
<td>$i</td>
<td><a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>$val[invoice_id]</a></td>
<td>$country</td>

<td>$invoiceDate</td>
<td>$customer_Name</td>
<td><span  class='countMe_{$order}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]</td>
 <td>$paymentMethod</td>
                <td>$val[inTransaction]</td>
<td>$orderProcess</td>
<td>$divInvoice $invoice_status</td>
<td>
<div class='btn-group btn-group-sm'>
$inoivcePdf
$edit_link ";
            if ($date < $minusDays) {
                echo "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>";
            } else {
                echo "<a class='btn'>
<i class='glyphicon glyphicon-trash '></i>
<i class='glyphicon glyphicon-ban-circle combineicon'></i>
</a>";
            }

            echo "</div>
</td>
</tr>";

        }

        echo '
</tbody>
</table>
</div> <!-- .table-responsive End -->';

    }
    public function invoiceList($order = '', $user_id = false)
    {
        global $_e;
        $href = "order/order_ajax.php?page=data_ajax_" . $order;
        $class = "dTable";
        $data_attr = '';
        switch ($order) {
            case 'complete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;
            case 'cancel':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'incomplete':
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'user':
                $invoice = $this->all($user_id);
                break;
            case 'invoices':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                $data_attr = ' data-sorting="true" ';
                break;
            default:
                $invoice = array();
                $class = '';
                break;
        }

        echo '
<div class="table-responsive search_manually">
<table class="uniqueClass table table-hover ' . $class . ' tableIBMS" data-href="' . $href . '" ' . $data_attr . '>
<thead>
<th>' ._u($_e['SELECT']) . '</th>
<th>' . _u($_e['SNO']) . '</th>
<th>' . _u($_e['INVOICE']) . '</th>
<th>' . _u($_e['Products']) . '</th>
<th>' . _u($_e['Address']) . '</th>
<th>' . _u($_e['Country']) . '</th>
<th>' . _u($_e['Phone']) . '</th>
<th>' . _u($_e['INVOICE DATE']) . '</th>
<th>' . _u($_e['CUSTOMER NAME']) . '</th>
<th>' . _u($_e['CUSTOMER EMAIL']) . '</th>
<th>' . _u($_e['SOLD PRICE']) . '</th>
<th>' . _u($_e['PAYMENT METHOD']) . '</th>
<th>' . _u($_e['Transaction Id']) . '</th>
<th>' . _u($_e['ORDER PROCESS - Invoice Status']) . '</th>
<th>' . _u($_e['Update Invoice Status']) . '</th>
<th>' . _u($_e['Flagged']) . '</th>
<th width="120">' . _u($_e['ACTION']) . '</th>
</thead>
<tbody>';

        $i = 0;
        foreach ($invoice as $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == '0')
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '1')
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '2')
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '3')
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            
            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];

            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_Name = $orderInfo['sender_name'];
            $sender_address = $orderInfo['sender_address'];
            $sender_phone = $orderInfo['sender_phone'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }


            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint.php?mailId=$invoiceId' target='_blank' class='btn'>
<i class='fa fa-file-pdf-o'></i>
</a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);


            if ($val['paymentType'] == "2") {
                $paymentMethod = "<div class='btn btn-success  btn-sm'>$paymentMethod</div>";
            } else if ($val['paymentType'] == "0") {
                $paymentMethod = "<div class='btn btn-danger  btn-sm'>$paymentMethod</div>";
            } else {
                $paymentMethod = "<div class='btn btn-default  btn-sm'>$paymentMethod</div>";
            }

            $cur_symbol = md5($val['price_code']);


            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();
            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div wwwwwwwwww'>{{form}}</div>"
            );
            $invoice_status = $this->functions->print_form($form_invoice, "", false);

            $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>";
            if (!empty($user_id)) {
                $edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>";
            }







            $newPrice = filter_var(
                $val['invoice_id'],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            $sqle = "SELECT order_pName FROM `order_invoice_product` WHERE `order_invoice_id` = '$newPrice' ";
            $variable = $this->dbF->getRows($sqle);
            $temData = "Nill";
            if ($this->dbF->rowCount > 0) {
                $temData = "";

                foreach ($variable as $key => $value) {

                    $pid_name = ($value['order_pName']);




                    $temData .= $pid_name . "<hr>";

                }

            }





            echo "<tr>
<td>$i</td>
<td><a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>$val[invoice_id]</a></td>
<td>$temData</td>

<td>$sender_address</td>
<td>$country</td>
<td>$sender_phone</td>

<td>$invoiceDate</td>
<td>$customer_Name</td>
<td><span  class='countMe_{$order}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]</td>
 <td>$paymentMethod</td>
                <td>$val[inTransaction]</td>
<td>$orderProcess</td>
<td>$divInvoice $invoice_status</td>
<td>
<div class='btn-group btn-group-sm'>
$inoivcePdf
$edit_link ";
            if ($date < $minusDays) {
                echo "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>";
            } else {
                echo "<a class='btn'>
<i class='glyphicon glyphicon-trash '></i>
<i class='glyphicon glyphicon-ban-circle combineicon'></i>
</a>";
            }

            echo "</div>
</td>
</tr>";

        }

        echo '
</tbody>
</table>
</div> <!-- .table-responsive End -->';

    }

    public function invoiceListFORallandComplete($order = '', $user_id = false)
    {
        global $_e;
        $href = "order/order_ajax.php?page=data_ajax_forAllAndCompleted_" . $order;
        $class = "dTable";
        $data_attr = '';
        switch ($order) {
            case 'complete':
                $invoice = array();
                $class = "dTable_ajax";
                break;

            case 'manullayCreated':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;

            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;



            default:
                $invoice = array();
                $class = '';
                break;
        }

        echo '
<div class="table-responsive search_manually">
<table class="table table-hover ' . $class . ' tableIBMS" data-href="' . $href . '" ' . $data_attr . '>
<thead>
<th>' . _u($_e['SELECT']) . '</th>
<th>' . _u($_e['SNO']) . '</th>
<th>' . _u($_e['INVOICE']) . '</th>
<th>' . _u($_e['Products']) . '</th>
<th>' . _u($_e['Address']) . '</th>
<th>' . _u($_e['Country']) . '</th>
<th>' . _u($_e['Phone']) . '</th>
<th>' . _u($_e['INVOICE DATE']) . '</th>
<th>' . _u($_e['CUSTOMER NAME']) . '</th>
<th>' . _u($_e['CUSTOMER EMAIL']) . '</th>
<th>' . _u($_e['SOLD PRICE']) . '</th>
<th>' . _u($_e['PAYMENT METHOD']) . '</th>

<th>' . _u($_e['Transaction Id']) . '</th>
<th>' . _u($_e['Tracking Number']) . '</th>
<th>' . _u($_e['ORDER PROCESS - Invoice Status']) . '</th>
<th>' . _u($_e['Update Invoice Status']) . '</th>
<th>' . _u($_e['Flagged']) . '</th>
<th width="120">' . _u($_e['ACTION']) . '</th>
</thead>
<tbody>';




        echo '
</tbody>
</table>
</div> <!-- .table-responsive End -->';

    }


    public function orderInvoiceInfo($orderId)
    {
        $sql = "SELECT * FROM order_invoice_info WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRow($sql);
        return $data;
    }

    public function invoiceListUser($userId, $echo = true)
    {
        global $_e;
        $temp = '';

        $temp .= '
<div class="table-responsive search_manually">
<table class="table table-hover dTable tableIBMS">
<thead>
<th class="hidden-xs">' . _u($_e['SNO']) . '</th>
<th>' . _u($_e['INVOICE']) . '</th>
<th class="hidden-xs">' . _u($_e['CUSTOMER NAME']) . '</th>
<th class="hidden-xs">' . _u($_e['INVOICE DATE']) . '</th>
<th>' . _u($_e['PURCHASE PRICE']) . '</th>
<th class="hidden-xs hidden-sm">' . _u($_e['PAYMENT METHOD']) . '</th><th>' . _u($_e['Transaction Id']) . '</th>
<th class="hidden-xs">' . _u($_e['ORDER PROCESS']) . '</th>
<th>' . _u($_e['Invoice Status']) . '</th>
<th>' . _u($_e['VIEW ORDER']) . '</th>
</thead>
<tbody>';

        $sql = "SELECT * FROM `order_invoice` WHERE orderUser = '$userId' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        if (!$this->dbF->rowCount) {
            $noFound = "<div class='alert alert-danger text-center'>" . $this->dbF->hardWords('No Invoice Found', false) . "</div>";
            if ($echo) {
                echo $noFound;
            } else {
                return $noFound;
            }
            return "";
        }
        $i = 0;
        foreach ($invoice as $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];

            if ($st == '0')
                $divInvoice = "<div class='btn btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '1')
                $divInvoice = "<div class='btn btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '2')
                $divInvoice = "<div class='btn btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == '3')
                $divInvoice = "<div class='btn btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div class='btn btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['dateTime']));
            $invoiceId = $val['order_invoice_pk'];

            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $customeName = $orderInfo['sender_name'];

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);

            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }
            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $class = "
<a href='invoicePrint.php?mailId=$invoiceId&orderId=" . $this->functions->encode($invoiceId) . "'  target='_blank' class='btn btn-success'>
<i class='fa fa-file-pdf-o'></i>
</a>
<a href='?view=$invoiceId&orderId=" . $this->functions->encode($invoiceId) . "' class='btn  btn-success'>
<i class='glyphicon glyphicon-list-alt'></i>
</a>";
            if (
                $val['orderStatus'] == 'inComplete'
                || $val['orderStatus'] == 'pendingPaypal'
                || $val['orderStatus'] == 'pendingPayson'
            ) {
                $class = "
<a href='orderInvoice.php?inv=$invoiceId' target='_blank' class='btn btn-danger'>
<i class='glyphicon glyphicon-share-alt '></i>
</a>
<a href='?view=$invoiceId&orderId=" . $this->functions->encode($invoiceId) . "' class='btn  btn-success'>
<i class='glyphicon glyphicon-list-alt'></i>
</a>

";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFindWeb($paymentMethod);
            $temp .= "<tr>
<td class='hidden-xs'>$i</td>
<td>$val[invoice_id]</td>
<td class='hidden-xs'>$customeName</td>
<td class='hidden-xs'>$invoiceDate</td>
<td>$val[total_price] $val[price_code]</td>
<td class='hidden-xs hidden-sm'>$paymentMethod</td>
<td class='hidden-xs'>$orderProcess</td>
<td>$divInvoice</td>
<td>
<div class='btn-group btn-group-sm'>
$class";
            $temp .= "</div>
</td>
</tr>";
        }

        $temp .= '
</tbody>
</table>
</div> <!-- .table-responsive End -->';

        if ($echo) {
            echo $temp;
        } else {
            return $temp;
        }

    }

    public function invoiceSQL($column = '*')
    {
        $sql = "SELECT " . $column . " FROM `order_invoice`";
        return $this->dbF->getRows($sql);
    }






    private function stockAddFromOrderLoop($pId, $scaleId, $colorId, $storeId, $data)
    {
        global $_e;

        $invProductId = $data['invoice_product_pk'];
        $order_invoice_id = $data['order_invoice_id'];
        $saleQTY = $data['order_pQty'];
        @$dealId = $data['deal']; // if not it is 0

        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
        $hash = md5($hashVal);


        $invQty = $this->productF->stockProductQty($hash);


        // ---------------------stock deduct from se to Intranet--------------------------

        $thisIs = $this->productF->stockProductQtyPlusIntraNEW($hash, $saleQTY, $order_invoice_id);





        $sql = "UPDATE order_invoice_product SET `removeIntra` = removeIntra - ? WHERE invoice_product_pk = ?";
        $this->dbF->setRow($sql, array($thisIs, $invProductId));


        return true;
    }



    public function stockAddAfterOrderStatusChange($orderId, $transection = true)
    {
        global $_e;



        $sql = "SELECT * FROM order_invoice_product WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRows($sql, false);
        $return = false;
        foreach ($data as $d) {
            $invProductId = $d['invoice_product_pk'];
            $pids = $d['order_pIds'];
            $pids = explode("-", $pids);
            $pId = $pids[0];
            $scaleId = $pids[1];
            $colorId = $pids[2];
            $storeId = $pids[3];
            @$customId = $pids[4];
            @$dealId = $d['deal']; // if not it is 0
            @$info = unserialize($d['info']);

            if ($customId != '0' && $scaleId == '0') {
                return true;
            }

            $saleQTY = $d['order_pQty'];
            if ($dealId == '0') {
                $return = $this->stockAddFromOrderLoop($pId, $scaleId, $colorId, $storeId, $d);
            } else {
                foreach ($info as $val) {
                    $pids = $val['pIds'];
                    $pids = explode("-", $pids);
                    $pId = $pids[0];
                    $scaleId = $pids[1];
                    $colorId = $pids[2];
                    $return = $this->stockAddFromOrderLoop($pId, $scaleId, $colorId, $storeId, $d);
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


}

?>