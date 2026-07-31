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
        $_w['User'] = '';
        $_w['Select User'] = '';
        $_w['Shop Customer'] = '';
        $_w['Invoice Status'] = '';
        $_w['Payment Type'] = '';
        $_w['Payment Info'] = '';
        $_w['Enter Vendor Payment Information'] = '';
        $_w['PRODUCT SCALE'] = '';
        $_w['PRODUCT COLOR'] = '';
        $_w['STORE'] = '';
        $_w['Order / Invoice Management'] = '';
        $_w['QUANTITY'] = '';
        $_w['PRICE'] = '';
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
        $_w['Thumbnail'] = '';


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
        $_w['Invoice Preview'] = '';
        $_w['ORDER'] = '';
        $_w['Order  Price'] = '';
        $_w['Shipping Price'] = '';
        $_w['Total'] = '';
        $_w['ORDER AND PROCESS'] = '';
        $_w['Selected Products'] = '';

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
        $_w['Order Successfully Submit'] = '';
        $_w['SNO'] = '';
        $_w['INVOICE'] = '';
        $_w['CUSTOMER NAME'] = '';
        $_w['INVOICE DATE'] = '';
        $_w['SOLD PRICE'] = '';
        $_w['PAYMENT METHOD'] = '';
        $_w['ORDER PROCESS'] = '';
        $_w['ACTION'] = '';
        $_w['Yes'] = '';
        $_w['PURCHASE PRICE'] = '';
        $_w['VIEW ORDER'] = '';
        $_w['Delete All Old Incomplete Orders'] = '';
        $_w['Search By Date Range'] = '';
        $_w['Date To'] = '';
        $_w['Date From'] = '';
        $_w['Selected SubTotal'] = '';

        $_w['ORDER PROCESS - Invoice Status'] = '';
        $_w['Update Invoice Status'] = '';
        $_w['Invoice Status Updated'] = '';
        $_w['Invoice'] = '';
        $_w['CUSTOMER EMAIL'] = '';
        $_w['Flagged'] = '';
        $_w['PRICE CODE'] = '';
        $_w['Product Name'] = '';
        $_w['Enter Scale'] = '';
        $_w['Enter Color'] = '';
        $_w['Enter Store'] = '';
        $_w['Order Invoice NO'] = '';
        $_w['Enter Manual Order INvoice No'] = '';
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
    public function newOrderForm2()
    {
        global $_e;
        $this->functions->require_once_custom('store');
        $storeC = new store();


        $paymentSelectOption = $this->productF->paymentSelect();
        $invoiceStatus = $this->productF->invoiceStatus();
        $country_list = $this->functions->countrySelectOption2();
        $storeList = $storeC->storeNamesCountryValueOption();
        $token = $this->functions->setFormToken('orderAdd', false);


        //user list
        $this->functions->require_once_custom('webUsers.class');
        $userC = new webUsers();
        $usersOption = $userC->userSelectOptionList();

        echo '

    <form method="post" class="form-horizontal" role="form">
        ' . $token . '
        <div class="form-horizontal">









               <div class="table-responsive inline-block newProduct " >
            <div id="productNotFoundInCountry" class=""></div>
              <table id="newProduct" class="table sTable table-hover " width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>' . _u($_e['PRODUCT']) . '</th>
                        <th>' . _u($_e['Thumbnail']) . '</th>
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



                        <td>
                           <img src="https://sharkspeed.se/myAdmin/images/logo_ibms.png" id="image_from_url" width="100" height="100">
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
                            <input name = "priceCode"  id="priceCode" class="form-control" value="">
                    </td>
                    <td>
                            <input type="number" class="form-control"  min="1"  id="invoice_qty" placeholder="' . _uc($_e['Product QTY']) . '">
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


             <div class="table-responsive" >
              <table id="addSelectedProduct" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>' . _u($_e['NO']) . '</th>
                        <th>' . _u($_e['PRODUCT']) . '</th>
                      
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
                <div class="h4"> ' . _u($_e['TOTAL PRICE']) . ': <span class="totalPrice bold">0</span></div>
                <input type="hidden" name="totalPrice" class="totalPriceInput" />
                <input type="hidden" name="totalWeight" class="totalWeightInput" />
            </div>








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
                    
                            ' . $storeList . '
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="input2" class="col-sm-2 col-md-3  control-label">' . _uc($_e['User']) . '</label>
                    <div class="col-sm-10  col-md-9">
                    <select  id="userId" name="userId" onchange="allUser(this);" class="form-control" required="required">
                        <option value="">' . _uc($_e['Select User']) . '</option>
                        <option Selected value="0">' . _uc($_e['Shop Customer']) . '</option>
                           ' . $usersOption . '

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="receipt_store_id" class="col-sm-2 col-md-3 control-label">' . _uc($_e['Invoice Status']) . '</label>

                    <div class="col-sm-10 col-md-9">
                    <fieldset id ="store" class="statusSelectFieldset">
                        <select required name="invoiceStatus"  id="statusSelect"  class="form-control statusSelect">
                          <option Selected value="99">Manually Created</option>
                          <option  value="3">Complete</option>
                            ' . $invoiceStatus . '
                        </select>
                    </fieldset>
                    </div>
                </div>

                  <div class="form-group">
                        <label for="receipt_store_id" class="col-sm-2 col-md-3 control-label">' . _uc($_e['Payment Type']) . '</label>
                        <div class="col-sm-10 col-md-9">
                        <input type="hidden" name="paymentType" class="form-control paymentType" data-val="0">
                        <fieldset id ="store" class="paymentTypeFieldset">
                            <select required  id="paymentTypeSelect" onchange="' . "$('.paymentType').val($(this).val());" . '" class="form-control paymentTypeFieldset">
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

  <div class="col-sm-6" id="receiverDiv">
              
                    <div class="col-sm-12">

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Name']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_name" id="receiver_name" placeholder="' . _uc($_e['Name']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Phone']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_phone" id="receiver_phone" placeholder="' . _uc($_e['Phone']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Email']) . '</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="receiver_email" id="receiver_email" placeholder="' . _uc($_e['Email']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['City']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_city" id="receiver_city" placeholder="' . _uc($_e['City']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Country']) . '</label>
                            <div class="col-sm-9">


<fieldset class="receiver_countryFieldset">
<select required  id="receiver_country" name="receiver_country" class="form-control">
' . $country_list . '
</select>
</fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Post Code']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_post" id="receiver_post" value="" placeholder="' . _uc($_e['Post Code']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Address']) . '</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="receiver_address" id="receiver_address" placeholder="' . _uc($_e['Address']) . '"></textarea>
                            </div>
                        </div>


                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 receiver info end -->


  



            <div class="container-fluid" style="display:none">
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
                                <input type="text" class="form-control" name="receiver_name1" id="receiver_name1" placeholder="' . _uc($_e['Receiver Name']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Phone']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_phone1" id="receiver_phone1" placeholder="' . _uc($_e['Receiver Phone']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Email']) . '</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="receiver_email1" id="receiver_email1" placeholder="' . _uc($_e['Receiver Email']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver City']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_city1" id="receiver_city1" placeholder="' . _uc($_e['Receiver City']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Country']) . '</label>
                            <div class="col-sm-9">
                                <fieldset class="receiver_countryFieldset">
                                    <select id="receiver_country1" name="receiver_country1" class="form-control">
                                        ' . $country_list . '
                                    </select>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Post Code']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_post1" id="receiver_post1" value="" placeholder="' . _uc($_e['Receiver Post Code']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Address']) . '</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="receiver_address1" id="receiver_address1" placeholder="' . _uc($_e['Receiver Address']) . '"></textarea>
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
                                <input type="text" class="form-control" name="sender_name" id="sender_name" placeholder="' . _uc($_e['Sender Name']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Phone']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sender_phone" id="sender_phone" placeholder="' . _uc($_e['Sender Phone']) . '"/>
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
                                <input type="text" class="form-control" name="sender_city" id="sender_city" placeholder="' . _uc($_e['Sender City']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Country']) . '</label>
                            <div class="col-sm-9">
                                <fieldset class="sender_countryFieldset">
                                    <select required  id="sender_country" name="sender_country" class="form-control">
                                        ' . $country_list . '
                                    </select>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Post Code']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sender_post" id="sender_post" value="" placeholder="' . _uc($_e['Sender Post Code']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Address']) . '</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="sender_address" id="sender_address" placeholder="' . _uc($_e['Sender Address']) . '"></textarea>
                            </div>
                        </div>

                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 sender info end -->

            </div> <!-- Send Receiver Info-->

            <div class="clearfix"></div>

            <div class="container-fluid ReviewButtons">
                <button type="button" onclick="return viewOrderReport();" value="Last Order View" class="submit btn btn-info">' . _uc($_e['Invoice Preview']) . '</button>
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
                </div>
                <br>';
        // <button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER AND PROCESS" class="submit btn btn-primary">'. _uc($_e['ORDER AND PROCESS']) .'</button>

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

    public function newOrderForm()
    {
        global $_e;
        $this->functions->require_once_custom('store');
        $storeC = new store();


        $paymentSelectOption = $this->productF->paymentSelect();
        $invoiceStatus = $this->productF->invoiceStatus();
        $country_list = $this->functions->countrySelectOption2();
        $storeList = $storeC->storeNamesCountryValueOption();
        $token = $this->functions->setFormToken('orderAdd', false);


        //user list
        $this->functions->require_once_custom('webUsers.class');
        $userC = new webUsers();
        $usersOption = $userC->userSelectOptionList();

        echo '

    <form method="post" class="form-horizontal" role="form">
        ' . $token . '
        <div class="form-horizontal">









               <div class="table-responsive inline-block newProduct " >
            <div id="productNotFoundInCountry" class=""></div>
              <table id="newProduct" class="table sTable table-hover " width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>' . _u($_e['PRODUCT']) . '</th>
                        <th class="allowProductScale">' . _u($_e['PRODUCT SCALE']) . '</th>

                        <th width="110">' . _u($_e['QUANTITY']) . '</th>
                      <th>' . _u($_e['PRICE']) . '</th>
                        <th>' . _u($_e['DISCOUNT']) . '</th>
                        <th>' . _u($_e['TOTAL PRICE']) . '</th>
                    </tr>
                </thead>
                <tbody>
                    <td>
                            <input type="text" class="form-control" id="invoice_product_id__" placeholder="' . _uc($_e['Product Name']) . '">
                            <input type="hidden" class="form-control invoice_product_id" data-val="">
                            <input type="hidden" class="form-control invoice_product_shipping" data-val="">
                            <input type="hidden" class="" id="invoice_product_weight">
                    </td>


                    <td class="allowProductScale">
                            <input type="text" class="form-control" id="invoice_product_scale" placeholder="' . _uc($_e['Enter Scale']) . '"  value="">
                            <input type="hidden" class="form-control invoice_product_scale" data-val="">
                    </td>
                            <input type="hidden" class="form-control" id="invoice_product_color" placeholder="' . _uc($_e['Enter Color']) . '"  value="0">
                            <input type="hidden" class="form-control invoice_product_color" data-val="">
                            <input type="hidden" class="form-control" id="invoice_product_store" placeholder="' . _uc($_e['Enter Store']) . '"  value="6">
                            <input type="hidden" class="form-control invoice_product_store" data-val="6" value="6">
                            <input type="hidden" name="priceCodeNew" class="form-control invoice_product_store__" id="priceCodeNew" >
                    
                    <td>
                            <input type="number" class="form-control"  min="1" value="1" onchange="calculateTotalPrice(this)"  id="invoice_qtyNew" placeholder="' . _uc($_e['Product QTY']) . '">
                    </td>
                        <input type="hidden"  name = "priceCode"  id="priceCode" value="">
                          
                    <td>
                            <input type="number" class="form-control" data-val="" min="0"  onchange="calculateTotalPrice(this)"  id="invoice_priceNew" placeholder="' . _uc($_e['Single Price']) . '">
                    </td>
                    <td>
                            <input type="number" class="form-control" data-val="" min="0"   onchange="calculateTotalPrice(this)" id="invoice_discountNew" placeholder="' . _uc($_e['Product Discount']) . '">
                    </td>
                    <td>
                            <input type="number"  class="form-control" min="0"  id="invoice_total_priceNew" placeholder="' . _uc($_e['TOTAL PRICE']) . '">
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


             <div class="table-responsive" >
              <table id="addSelectedProduct" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>' . _u($_e['NO']) . '</th>
                        <th>' . _u($_e['PRODUCT']) . '</th>
                      
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
                <div class="h4"> ' . _u($_e['TOTAL PRICE']) . ': <span class="totalPrice bold">0</span></div>
                <input type="hidden" name="totalPrice" class="totalPriceInput" />
                <input type="hidden" name="totalWeight" class="totalWeightInput" />
            </div>








            <div class="col-md-6">
                <!--
                <div class="form-group">
                    <label for="receipt_date" class="col-sm-2 col-md-3 control-label">Date</label>
                    <div class="col-sm-10 col-md-9">
                        <input type="text" name="receipt_date" class="form-control date" required id="receipt_date" placeholder="Purchasing Date">
                    </div>
                </div> -->




                <div class="form-group">
                    <label for="input2" class="col-sm-2 col-md-3  control-label">' . _uc($_e['Order Invoice NO']) . '</label>
                    <div class="col-sm-10  col-md-9">
                    <input type="text" name="munual_order_inv" class="form-control" Placeholder="' . _uc($_e['Enter Manual Order INvoice No']) . '" data-val="">
                    </div>
                </div>
                
               
                <input type="hidden" name="invoiceStatus" value="99" class="form-control statusSelect" id="statusSelect"/>
                <input type="hidden" name="paymentType" class="form-control paymentType" value="99">
                


                <div class="form-group">
                    <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">' . _uc($_e['Payment Info']) . '</label>
                    <div class="col-sm-10 col-md-9">
                        <textarea name="paymentInfo" id="paymentInfo" class="form-control" placeholder="' . _uc($_e['Enter Vendor Payment Information']) . '"></textarea>
                    </div>
                </div>




              

            </div><!-- First col-md-6 end -->

  <div class="col-sm-6" id="receiverDiv">
              
                    <div class="col-sm-12">

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Name']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_name" id="receiver_name" placeholder="' . _uc($_e['Name']) . '"/>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Address']) . '</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="receiver_address" id="receiver_address" placeholder="' . _uc($_e['Address']) . '"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Post Code']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_post" id="receiver_post" value="" placeholder="' . _uc($_e['Post Code']) . '"/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['City']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_city" id="receiver_city" placeholder="' . _uc($_e['City']) . '"/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Country']) . '</label>
                            <div class="col-sm-9">

                                
                                <fieldset class="receiver_countryFieldset">
                                <select required  id="receiver_country" name="receiver_country" class="form-control">
                                ' . $country_list . '
                                </select>
                                </fieldset>
                            </div>
                        </div>




                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Phone']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_phone" id="receiver_phone" placeholder="' . _uc($_e['Phone']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Email']) . '</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="receiver_email" id="receiver_email" placeholder="' . _uc($_e['Email']) . '"/>
                            </div>
                        </div>

                        

                         
                        
                       

                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 receiver info end -->


  



            <div class="container-fluid" style="display:none">
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
                                <input type="text" class="form-control" name="receiver_name1" id="receiver_name1" placeholder="' . _uc($_e['Receiver Name']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Phone']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_phone1" id="receiver_phone1" placeholder="' . _uc($_e['Receiver Phone']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Email']) . '</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="receiver_email1" id="receiver_email1" placeholder="' . _uc($_e['Receiver Email']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver City']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_city1" id="receiver_city1" placeholder="' . _uc($_e['Receiver City']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Country']) . '</label>
                            <div class="col-sm-9">
                                <fieldset class="receiver_countryFieldset">
                                    <select id="receiver_country1" name="receiver_country1" class="form-control">
                                        ' . $country_list . '
                                    </select>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Post Code']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receiver_post1" id="receiver_post1" value="" placeholder="' . _uc($_e['Receiver Post Code']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Receiver Address']) . '</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="receiver_address1" id="receiver_address1" placeholder="' . _uc($_e['Receiver Address']) . '"></textarea>
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
                                <input type="text" class="form-control" name="sender_name" id="sender_name" placeholder="' . _uc($_e['Sender Name']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Phone']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sender_phone" id="sender_phone" placeholder="' . _uc($_e['Sender Phone']) . '"/>
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
                                <input type="text" class="form-control" name="sender_city" id="sender_city" placeholder="' . _uc($_e['Sender City']) . '"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Country']) . '</label>
                            <div class="col-sm-9">
                                <fieldset class="sender_countryFieldset">
                                    <select required  id="sender_country" name="sender_country" class="form-control">
                                        ' . $country_list . '
                                    </select>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Post Code']) . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sender_post" id="sender_post" value="" placeholder="' . _uc($_e['Sender Post Code']) . '"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">' . _uc($_e['Sender Address']) . '</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="sender_address" id="sender_address" placeholder="' . _uc($_e['Sender Address']) . '"></textarea>
                            </div>
                        </div>

                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 sender info end -->

            </div> <!-- Send Receiver Info-->

            <div class="clearfix"></div>

            <div class="container-fluid ReviewButtons">
                <button type="button" onclick="return viewOrderReport();" value="Last Order View" class="submit btn btn-info">' . _uc($_e['Invoice Preview']) . '</button>
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
                </div>
                <br>';
        // <button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER AND PROCESS" class="submit btn btn-primary">'. _uc($_e['ORDER AND PROCESS']) .'</button>

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

        if (isset($_POST) && !empty($_POST)) {


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
                @$price_code = $_POST['priceCodeNew'];
                @$country = $_POST['storeCountry'];
                @$userId = $_POST['userId'];
                @$munual_order_inv = "M_";
                @$munual_order_inv .= (isset($_POST['munual_order_inv']) && !empty($_POST['munual_order_inv'])) ? $_POST['munual_order_inv'] : "";

                @$totalWeightReceiveFromForm = $_POST['totalWeight']; //Using In Security, If Weight from web form or php calculated not match, mean Hacking Attempt
                $total_priceNew = 0; //Calculateing in foreach loop, test with $total_price after loop, If not match its hacking attempt
                $total_weightNew = 0;//Calculateing in foreach loop, test with $totalWeightReceiveFromForm after loop, If not match its hacking attempt


                $countryData = $this->productF->productCountryId($country);
                $countryId = $countryData['cur_id'];

                //major data submit here, will later here, update this table
                $now = date('Y-m-d H:i:s');
                $sql = "INSERT INTO `order_invoice` (`paymentType`, `invoice_date`, `orderUser`, `payment_info`, `price_code`, `invoice_status`, 
                `manuall_id`) VALUES (?,?,?,?,?,?,?)";
                $array = array($paymentType, $now, $userId, $payment_info, $price_code, $invoiceStatus, $munual_order_inv);
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

                    $pName = @$_POST["pName_$id"];

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
                    $pIds = "-";

                    $sql = "INSERT INTO `order_invoice_product` (`order_invoice_id`, `order_pIds`, `order_pName`, `order_pStore`, `order_pPrice`, 
                    `order_salePrice`, `order_discount`, `order_pQty`, `order_pWeight`, `order_process`, `order_hash`) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)";

                    $array = array($invoiceId, $pIds, $pName, $storeName, $pPrice, $salePrice, $discount, $saleQTY, $weight, $process, $hash);
                    $this->dbF->setRow($sql, $array, false);

                    $invoiceIddddddd = $this->dbF->rowLastId;

                    // Remove QTY FROM inventory
                    if ($process == 1) {
                        $invQty = $this->productF->stockProductQty($hash);
                        
                        // if ($invQty >= $saleQTY) {
                        //     if ($this->productF->stockProductQtyMinus($hash, $saleQTY)) {
                        //     } else {
                        //         throw new Exception($_e['Order QTY is Greater Than stock Quantity']);
                        //     }

                        // } else {
                        //     throw new Exception($_e['Order QTY is Greater Than stock Quantity']);
                        // }
                    } // If Process Order End
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
                $shippingWeight = isset($shippingData['shp_weight']) ? $shippingData['shp_weight'] : 0;
                $shippingPrice = isset($shippingData['shp_price']) ? $shippingData['shp_price'] : 0;
                //calculating
                $finalShippingPrice = $shippingPrice;

                $total_priceNew += $finalShippingPrice;

                $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with'); // Invoice Number start with


                if (intval($paymentType) === intval('2')) {
                    $processStatus = 'inComplete';
                } else {
                    $processStatus = 'process';
                }
                $sql = "UPDATE `order_invoice` SET
                            `invoice_id`    =   '" . $invoiceKey . '' . $invoiceId . "',
                            `total_price`   =   '$total_priceNew',
                            `ship_price`     =   '$finalShippingPrice',
                            `total_weight`  =   '$total_weightNew',
                            `orderStatus`       =   '$processStatus',
                            `shippingCountry`   =   ?
                               WHERE `order_invoice_pk`  = '$invoiceId'";
                $this->dbF->setRow($sql, array($_POST['receiver_country']), false);

                $this->db->commit();

                if ($this->dbF->rowCount > 0) {
                    
                    $invoicePdf = '<a href="'.WEB_URL.'/invoicePrint.php?mailId=' . $invoiceId . '" target="_blank" class="btn">INVOICE No: ' . $invoiceId . ' <i class="fa fa-file-pdf-o"></i></a><br><form method="post" target="_blank" action="https://sharkspeed.se/myAdmin/-order?page=edit" ><input type="hidden" name="pId" value="' . $invoiceId . '"><input type="submit" class="gotoOrder" value="Go to Order" style="background: none;border: none;margin-left: 10px; color: blue;" ></form>';
                    
                    $msg = $this->functions->notificationError(_js(_uc($_e['Order Successfully Submit'])), $invoicePdf, 'btn-success');

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

    } // Function End



    public function invoiceOrdersSql()
    {
        $sql = "SELECT * FROM `order_invoice` WHERE orderStatus != 'inComplete' AND invoice_status != '3'  AND invoice_status != '0' ORDER BY order_invoice_pk DESC";
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
    public function invoiceList($order = '', $user_id = false)
    {
        global $_e;
        $href = "adminorder/order_ajax.php?page=data_ajax_" . $order;
        $class = "dTable";
        $data_attr = '';
        switch ($order) {
            case 'complete':
                // $invoice = $this->completeOrdersSql();
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'all':
                $invoice = array();
                $class = "dTable_ajaxOrder";
                break;
            case 'cancel':
                // $invoice = $this->cancelOrdersSql();
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'incomplete':
                // $invoice = $this->inCompleteOrdersSql();
                $invoice = array();
                $class = "dTable_ajax";
                break;
            case 'user':
                $invoice = $this->all($user_id);
                break;
            case 'invoices':
                // $invoice = $this->invoiceOrdersSql();
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
            <div class="table-responsive">
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
                "format" => "<div class='invoice_quick_select_div'>{{form}}</div>"
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
            <div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th class="hidden-xs">' . _u($_e['SNO']) . '</th>
                        <th>' . _u($_e['INVOICE']) . '</th>
                        <th class="hidden-xs">' . _u($_e['CUSTOMER NAME']) . '</th>
                        <th class="hidden-xs">' . _u($_e['INVOICE DATE']) . '</th>
                        <th>' . _u($_e['PURCHASE PRICE']) . '</th>
                        <th class="hidden-xs hidden-sm">' . _u($_e['PAYMENT METHOD']) . '</th>
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

}

?>