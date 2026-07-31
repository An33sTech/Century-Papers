<?php
ob_start();
require_once("classes/invoice.php");
global $_e;
$invoice = new invoice();
$invoice->update();

@$pId = $_POST['pId'];
if (empty($pId)) {
    @$pId = $_GET['orderId']; // in case of future need just add this in url  &orderId={id}
}
$orderId = $pId;
$data = $invoice->invoiceDetail($orderId);
$country_list = $functions->countrylist();
if (isset($_GET['apiData'])) {
    echo "<pre>";
    print_r(unserialize(base64_decode($data['apiReturn'])));
    echo "</pre>";
}

@$extraId = $_GET['extraOrderId'];

$sql = "SELECT `apiReturn` FROM `order_extra_amount` WHERE `id` = ? ";
$res = $dbF->getRow($sql, array($extraId));
if (isset($_GET['extraApiData'])) {
    echo "<pre>";
    print_r(unserialize(base64_decode($res['apiReturn'])));
    echo "</pre>";
}


if (!empty($data['apiReturn'])) {
    $viewApiReturnData = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&apiData'>" . $_e['View Api Return Info'] . "</a>";
} else {
    $viewApiReturnData = '';
}
?>

    <h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Invoice Detail View']); ?></h4>
    <!-- sender detail -->
    <div class="table-responsive newProduct col-sm-6">
        <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
               cellspacing="0">
            <thead>
            <th colspan="7">
                <div class="text-center"><?php echo _u($_e['ORDER SENDER DETAIL']); ?></div>
            </th>
            </thead>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
                <td><?php echo $data['sender_name']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
                <td><?php echo $data['sender_address']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Post Code']); ?></td>
                <td><?php echo $data['sender_post']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['City']); ?></td>
                <td><?php echo $data['sender_city']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
                <td><?php
                    $countryName = $country_list[strtoupper($data['sender_country'])];
                    echo $countryName; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
                <td><?php echo $data['sender_email']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
                <td><?php echo $data['sender_phone']; ?></td>
            </tr>
        </table>
    </div>
    <!-- sender detail end -->

    <!-- receiver detail -->
    <div class="table-responsive newProduct col-sm-6">
        <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
               cellspacing="0">
            <thead>
            <th colspan="7">
                <div class="text-center"><?php echo _u($_e['ORDER RECEIVER DETAIL']); ?></div>
            </th>
            </thead>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
                <td><?php echo $data['receiver_name']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
                <td><?php echo $data['receiver_address']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Post Code']); ?></td>
                <td><?php echo $data['receiver_post']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['City']); ?></td>
                <td><?php echo $data['receiver_city']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
                <td><?php $countryName = $country_list[strtoupper($data['receiver_country'])];
                    echo $countryName; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
                <td><?php echo $data['receiver_email']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
                <td><?php echo $data['receiver_phone']; ?></td>
            </tr>
        </table>
    </div>
    <!-- receiver detail end -->


    <div class="clearfix"></div>
    <div class="padding-20"></div>

    <form method="post" id="extraAmountForm">
        <input type="hidden" name="invoiceId" value="<?php echo $data['invoice_id']; ?>"/>
        <input type="hidden" name="senderEmail" value="<?php echo $data['sender_email']; ?>"/>
        <input type="hidden" name="curSybmol" value="<?php echo $data['price_code']; ?>"/>
        <input type="hidden" name="paymentType" value="<?php echo $data['paymentType']; ?>"/>
        <div class="table-responsive newProduct">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="12">
                    <div class="text-center"><?php echo _u($_e['EXTRA PAYMENT FORM']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo $_e['Amount']; ?></th>
                    <th><?php echo $_e['Description']; ?></th>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="extra_amnt"></td>
                    <td><textarea class="form-control" name="description" placeholder="Description"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" class="btn btn-primary" id="submitExtraAmount" name="submitExtraAmount" value="Submit"></td>
                </tr>
            </table>
        </div>
        <!-- product detail end -->
    </form>

    <script type="text/javascript">
        $('#submitExtraAmount').click(function(){
            extraForm = $('#extraAmountForm').serialize();

            $.ajax({
                url: 'adminorder/order_ajax.php?page=submitExtraAmountForm',
                type: 'post',
                data: extraForm
            }).done(function(res){
                console.log(res);
                if(res == '0'){
                    jAlertifyAlert('Something Went Wrong!');
                }else{
                    jAlertifyAlert('<?php $dbF->hardWords("Email Sent To Customer For Extra Payment",true); ?>');
                }
                // else if(res == '0'){
                //     jAlertifyAlert('Something Went Wrong!');
                // }else{
                //     jAlertifyAlert('Something Went Wrong!');
                // }
            });
        });
    </script>

    <div class="clearfix"></div>
    <div class="padding-20"></div>

    <div class="table-responsive newProduct">
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
               cellspacing="0">
            <thead>
            <th colspan="12">
                <div class="text-center"><?php echo _u($_e['EXTRA PAYMENTS']); ?></div>
            </th>
            </thead>
            <tr class="gray-tr">
                <th><?php echo $_e['SNO']; ?></th>
                <th><?php echo $_e['INVOICE DATE']; ?></th>
                <th><?php echo $_e['EXTRA AMOUNT']; ?></th>
                <th><?php echo $_e['DESC']; ?></th>
                <th><?php echo $_e['INVOICE STATUS']; ?></th>
                <th><?php echo $_e['PAYMENT TYPE']; ?></th>
                <th><?php echo $_e['PAYMENT STATUS']; ?></th>
                <th><?php echo $_e['RESERVATION NO']; ?></th>
                <th><?php echo $_e['PAYMENT LINK']; ?></th>
                <th><?php echo $_e['PAYMENT INFO']; ?></th>
                <th><?php echo $_e['UPDATE DATE']; ?></th>
            </tr>
            <?php 
            	$extraPayLink = 'https://sharkspeed.'._l($data['shippingCountry']);

                $sql = "SELECT * FROM `order_extra_amount` WHERE `invoice_no` = ?";
                $res = $dbF->getRows($sql, array($data['invoice_id']));
                $j = 1;
                if(!empty($res)){
                    foreach ($res as $key => $value) {

                        $val = $invoice->productF->paymentArrayFind($value['paymentType']);
                        if ($value['paymentType'] == '2') {
                            $processT = "<div class='btn-success btn btn-sm'> $val </div> ";
                        } else if ($value['paymentType'] == '0') {
                            $processT = "<div class='btn-default btn btn-sm'> $val </div> ";
                        } else {
                            $processT = "<div class='btn-default btn btn-sm'> $val </div> ";
                        }

                        if($value['paymentType'] == 2){
                            $link = $extraPayLink.'/extra_pay?inv='.$data["invoice_id"].'&id='.$value['id'];
                        }else if($value['paymentType'] == 5){
                            $link = $extraPayLink.'/extra_payment?inv='.$invoiceId.'&id='.$value['id'];
                        }

                        if (!empty($value['apiReturn'])) {
                            $viewExtraApiReturnData = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&extraOrderId=$value[id]&extraApiData'>" . $_e['View Api Return Info'] . "</a>";
                        } else {
                            $viewExtraApiReturnData = '';
                        }

                        $paymentStatus = $value['orderStatus'];
                        if ($paymentStatus == 'process') {
                            $paymentStatus = _uc($_e['OK']);
                        } else {
                            $paymentStatus = _uc($_e['InComplete']);
                        }



                        echo "
                            <tr class=''>
                                <td>$j</td>
                                <td>$value[invoice_date]</td>
                                <td>$value[extra_amount] $value[price_code]</td>
                                <td>$value[description]</td>
                                <td>$value[orderStatus]</td>
                                <td>$processT $viewExtraApiReturnData</td>
                                <td>$paymentStatus</td>
                                <td>$value[rsvNo]</td>
                                <td><input type='text' id='extraAmountLink' value='".$link."'><button class='btn btn-primary btn-xs' onclick='copyFunction()'>Copy</button></td>
                                <td>$value[payment_info]</td>
                                <td>$value[date_timestamp]</td>
                            </tr>";

                        $j++;    
                    }
                }else{
                    echo "<tr><td colspan='10'>No Record Found</td></tr>";
                }

            ?>
        </table>
    </div>
    <!-- product detail end -->

    <div class="clearfix"></div>
    <div class="padding-20"></div>

    <!-- product detail -->
    <form method="post">
        <div class="table-responsive newProduct">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="12">
                    <div class="text-center"><?php echo _u($_e['ORDER PRODUCTS']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo _u($_e['SNO']); ?></th>
                    <th><?php echo _u($_e['PRODUCT NAME']); ?></th>
                    <th><?php echo _u($_e['STORE NAME']); ?></th>
                    <th><?php echo _u($_e['LOCATION']); ?></th>
                    <th><?php echo _u($_e['ORIGINAL PRICE']); ?></th>
                    <th><?php echo _u($_e['SALE IN PRICE']); ?></th>
                    <th><?php echo _u($_e['DISCOUNT']); ?></th>
                    <th><?php echo _u($_e['SALE QTY']); ?></th>
                    <th><?php echo _u($_e['OFFER']); ?></th>
                    <th><?php echo _u($_e['PROCESS']); ?></th>
                    <th><?php echo _u($_e['RETURNS INFO']); ?></th>
                    <!--<th>RETURN</th>-->
                    <th><?php echo _u($_e['TOTAL']); ?></th>
                </tr>
                <?php
                $totalDiscount = 0;
                $totalProductPrice = 0;
                $pdata = $invoice->invoiceProduct($orderId);
                $totalNet = 0;
                $process = "0";
                $i = 0;
                $done = true;

                foreach ($pdata as $p) {
                    $i++;
                    $invoice_product_id = $p['invoice_product_pk'];
                    $pIds = explode("-", $p['order_pIds']);
                    @$pId = $pIds[0];
                    @$scaleId = $pIds[1];
                    @$colorId = $pIds[2];
                    @$storeId = $pIds[3];
                    @$customId = $pIds[4];

                    @$dealId = $p['deal']; // if not it is 0
                    @$checkout = $p['checkout']; // if not it is 0
                    @$info = unserialize($p['info']);

                    $pQty = $p['order_pQty'];
                    $total = $p['order_salePrice'] * $pQty;

                    $discount = $p['order_discount'];
                    $totalDiscount += $discount * $pQty;

                    $saleIn = ( ($total / $pQty) - ($discount) );
                    $saleIn = round($saleIn, 2);
                    $total = $saleIn * $pQty;
                    $totalNet += $total;

                    $singleDiscount = $discount;

                    $process = $p['order_process'];
                    $processTemp = "";

                    if ($process === '0') {
                        $processT = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
                        $processTemp = "<input type='checkbox' name='pro[]' class='btn-sm btn' value='$p[invoice_product_pk]'/> &nbsp; ";
                        $done = false;
                    } else {
                        $processT = "<div class='btn btn-success btn-sm'>" . _uc($_e['Yes']) . "</div>";
                    }


                    # New Returns Management Work
                    switch ($process) {
                        case '2':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Refunded']) . "</div>";
                             break;
                        case '3':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Defected']) . "</div>";
                             break;
                        case '4':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Changed Product']) . "</div>";
                             break;
                        case '5':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Changed Size']) . "</div>";
                             break;
                         
                         default:
                            $returns_info = '';
                             break;
                    } 


                    if ( $checkout === '1' )  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Checkout']) . "</div>";
                    }elseif ($checkout === '2')  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Free Gift']) . "</div>";
                    }elseif ($checkout === '3')  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Extra Sale']) . "</div>";
                    }else {
                        $checkoutD = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
                    }

                    $retrunInput = "";
                    @$returnP = @$p['order_return'];
                    $retrunStatus = "";
                    if ($returnP === '0') {
                        if ($process === '0') {
                            $retrunInput = '';
                            $retrunStatus = "";
                        } else {
                            $retrunInput = "<input type='checkbox' name='retrun[]' class='btn-sm btn' value='$p[invoice_product_pk]'/> &nbsp; ";
                            $retrunStatus = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
                        }
                    } else {
                        $retrunStatus = "<div class='btn btn-success btn-sm'>" . _uc($_e['Yes']) . "</div>";
                    }


                    $pName = $p['order_pName'];
                    //custom Info
                    $sizeInfo = '';
                    $class = '';
                    if ($customId != '0' && !empty($customId) && $scaleId == '0') {
                        $sizeInfo = "<a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                        $pName = explode(" - ", $pName);
                        $pName[1] = $sizeInfo;
                        $pName = implode(" - ", $pName);

                        $customFieldsData = $invoice->customSubmitValues($customId);
                        $customFields = $customFieldsData["form"];
                        $customFormFill = $customFieldsData["formFill"];
                        $sizeInfo = $functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                        $processTemp = '';
                        if ($customFormFill == '1') { //edit able,, not fill
                            $class = 'danger';
                        }
                    }

                    if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                        $dealT = $_e['Deal'];
                        $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFields = $invoice->dealSubmitPackage($info, false);
                        $sizeInfo .= $functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
                    }

                    ############## Buy 2 Get 1 Free ######
                    $buy_get_free = $invoice->productF->buy_get_free_invoice_div($orderId, $invoice_product_id, "2");
                    if (!empty($buy_get_free)) {
                        $pQty = $pQty . $buy_get_free;
                    }
                    ############## Buy 2 Get 1 Free END ######

                    ############ FREE GIFT TEXT #############
                    $free_gift_product_div = "";
                    if ($saleIn == "0" && $p["order_pPrice"] == $singleDiscount) {
                        $free_gift_product_div = $invoice->productF->free_gift_text();
                    }
                    ############ FREE GIFT TEXT #############

                    ########### Store Location ##########
                    $location = $invoice->productF->get_stock_location($pId, $storeId, $scaleId, $colorId);

                    //<td>$pName $sizeInfo $free_gift_product_div</td>
                    echo "
                    <tr class='$class'>
                        <td>$i</td>
                        <td>$pName $sizeInfo</td>
                        <td>$p[order_pStore]</td>
                        <td>$location</td>
                        <td>$p[order_pPrice]</td>
                        <td>$saleIn</td>
                        <td>$singleDiscount</td>
                        <td>$pQty</td>
                        <td>$checkoutD</td>
                        <td>$processTemp $processT</td>
                        <td>$returns_info</td>
                        <!-- <td>$retrunInput $retrunStatus</td> -->
                        <td>$total $data[price_code]</td>
                    </tr>";
                }

                echo "
                <tr>
                    <td colspan='11'><b>" . _uc($_e['Total Net Amount']) . "</b></td>
                    <td>$totalNet  $data[price_code]</td>
                </tr>";

                ?>

            </table>
        </div>
        <!-- product detail end -->

        <div class="clearfix"></div>
        <div class="padding-20"></div>

        <!-- invoice detail -->

        <input type="hidden" name="pId" value="<?php echo $orderId; ?>"/>
        <?php $functions->setFormToken('Invoice'); ?>
        <div class="table-responsive newProduct col-sm-6">
        <input type="hidden" name="invoi_idd" value="<?php echo $data['invoice_id']; ?>">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="6">
                    <div class="text-center"><?php echo _uc($_e['Invoice Detail']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo _uc($_e['Property']); ?></th>
                    <th><?php echo _uc($_e['Value']); ?></th>
                </tr>
                <tr>
                    <td><?php echo _uc($_e['Invoice ID']); ?></td>
                    <td><?php echo $data['invoice_id']; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Discount Code']); ?></td>
                    <td><?php $temp = $invoice->productF->get_order_invoice_record($orderId, "coupon", false);
                        echo @$temp['setting_val'];
                        ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Total Weight']); ?></td>
                    <td><?php echo $data['total_weight'] . " KG"; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['DISCOUNT']); ?></td>
                    <td><?php echo $totalDiscount . " " . $data['price_code']; ?></td>
                </tr>


                <?php
                $three_for_two_cat = $data['three_for_two_cat'];
                // if($three_for_two_cat>0){ ?>
                <tr class="lasts_tr">
                    <td><?php $dbF->hardWords('Three For Two Categry Price'); ?> </td>
                    <td> <?php echo $three_for_two_cat . " " . $data['price_code']; ?></td>
                </tr>
                <?php //} ?>
                
                <?php
                $staple_pro_cat = $data['staple_pro_cat'];
                   if($staple_pro_cat>0){ ?>
                <tr class="lasts_tr">
                    <td><?php $dbF->hardWords('Bundle Category Discount'); ?> </td>
                    <td> <?php echo $staple_pro_cat . " " . $data['price_code']; ?></td>
                </tr>
                <?php } ?>

                <tr>
                    <td><?php echo _uc($_e['Shipping Price']); ?></td>
                    <td><?php echo $data['ship_price'] . " " . $data['price_code']; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Total Product Price']); ?></td>
                    <td><?php echo $totalNet + $totalDiscount . " " . $data['price_code']; ?></td>
                </tr>

               <!-- <?php /*if( intval($data['three_for_two_cat']) > 0 ){ */?>
                    <tr>
                        <td><?php /*echo _uc($_e['3 For 2 Category']); */?></td>
                        <td><?php /*echo @$data['three_for_two_cat']." ". $data['price_code'];  */?></td>
                    </tr>
                --><?php /*} */?>

                <tr>
                    <td><?php echo _uc($_e['Total']); ?></td>
                    <td title="<?php echo $data['ship_price'] . '+' . ($totalNet + $totalDiscount) . '-' . $totalDiscount . ' - ' . $three_for_two_cat . ' = ' . $data['total_price']; ?>"><?php echo $data['total_price'] . " " . $data['price_code']; ?>
                        &nbsp;<i class="glyphicon glyphicon-info-sign   "></i></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Creation Time']); ?></td>
                    <td><?php echo $data['invoice_date']; ?></td>
                </tr>


                <tr>
                    <td><?php echo _uc($_e['Last Updated Time']); ?></td>
                    <td><?php echo $data['dateTime']; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Invoice Status']); ?></td>
                    <td><?php
                        $invoiceStatus = $data['invoice_status'];
                        $invs = true;
                        if ($invoiceStatus == 0) {
                            $invStatus = "btn-danger";
                        } elseif ($invoiceStatus == 1) {
                            $invStatus = "btn-warning";
                        } else if ($invoiceStatus == 2) {
                            $invStatus = "btn-primary";
                        } else if ($invoiceStatus == 3) {
                            $invStatus = "btn-success";
                            $invs = false;
                        } else {
                            $invStatus = "btn-info";
                        }

                        $click = '$("#invStatus").show(500);';
                        $btn = '$("#upbtn").show(500);';

                        //Done Was working if all product process then always show done order
                        //if($done){
                        $oldStatus = $invoice->productF->invoiceStatusFind($invoiceStatus);
                        echo "<input type='hidden' name='old_status_id' value='$invoiceStatus'>";
                        echo "<input type='hidden' name='old_status_name' value='$oldStatus'>";
                        if ($done === 'asad') {
                            $invStatus = "btn-success";
                            echo "<div class='$invStatus' onclick='$click'>Done Order Complete</div>";
                        } else {
                            echo "<div class='$invStatus btn' onclick='$click'>" . $invoice->productF->invoiceStatusFind($invoiceStatus) . "</div>";
                        }


                        //if(!$done || $invoiceStatus!=3){
                        //var_dump($done);
                        // if(!$done){ ?>
                        <select name="invoiceStatus" id="invStatus" style="display: none;" class="form-control">
                            <?php echo $invoice->productF->invoiceStatus(); ?>
                        </select>
                        <script>
                            $(document).ready(function () {
                                $("#invStatus").val("<?php echo $invoiceStatus;?>").change();
                            });
                        </script>
                        <?php //} ?></td>
                </tr>


                <tr>
                    <td><?php echo _uc($_e['Shipping Track Number']); ?></td>
                    <td>
                        <input type="hidden" name="old_trackNo" value="<?php echo $data['trackNo']; ?>">
                    <input type="text" class="form-control" value="<?php echo $data['trackNo']; ?>" name="trackNo"/>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Send Email To Customer']); ?></td>
                    <td>
                        <input type="hidden" value="<?php echo $data['sender_email'] ?>" name="toEmail"/>
                        <label><input type="radio" value="1" name="sendEmail" checked/><?php echo _u($_e['Yes']); ?>
                        </label>
                        <label><input type="radio" value="0" name="sendEmail"/><?php echo _u($_e['NO']); ?></label>
                    </td>
                </tr>

                <!--<tr>
                <td>Order process</td>
                <td><?php
                /*                    $click = '$("#payment").show(500);';
                                    if($process==0){
                                        $processT = "<div class='btn-danger' onclick='$click'> Pending, Order Now </div> ";
                                    }else{
                                        $processT = "<div class='btn-success'> SuccessFully </div> ";
                                    }
                                    echo $processT;

                                    if($process==0){    */ ?>
                        <select name="payment" id="payment"  style="display: none;">
                            <?php /*echo $invoice->productF->paymentSelect(); */ ?>
                        </select>
                        <script>
                            $(document).ready(function(){
                                $("#payment").val("<?php /*echo $process;*/ ?>").change();
                            });
                        </script>
                    <?php
                /*                    }
                                    */ ?>
                </td>
            </tr>-->

            </table>
        </div>


        <div class="table-responsive newProduct col-sm-6">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="6">
                    <div class="text-center"><?php echo _uc($_e['Payment Information']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo _uc($_e['Property']); ?></th>
                    <th><?php echo _uc($_e['Value']); ?></th>
                </tr>
                <tr>
                    <td><?php echo _uc($_e['Payment Type']); ?></td>
                    <td><?php $val = $invoice->productF->paymentArrayFind($data['paymentType']);

                        $click = '$("#payment").show(500);';

                        if ($data['paymentType'] == '2') {
                            $processT = "<div class='btn-success btn btn-sm' onclick='$click'> $val </div> ";
                        } else if ($data['paymentType'] == '0') {
                            $processT = "<div class='btn-danger btn btn-sm' onclick='$click'> $val </div> ";
                        } else {
                            $processT = "<div class='btn-default btn btn-sm' onclick='$click'> $val </div> ";
                        }
                        echo $processT;
                        if (!$done) {
                            ?>
                            <select name="payment" id="payment" style="display: none;" class="form-control">
                                <?php echo $invoice->productF->paymentSelect(); ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                    $("#payment").val("<?php echo $data['paymentType'];?>").change();
                                });
                            </script>
                            <?php
                        } else {
                            echo "<input type='hidden' value='$data[paymentType]' name='payment'/>";
                        }
                        echo $viewApiReturnData;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Payment Status']); ?></td>
                    <td>
                        <?php $paymentStatus = $data['orderStatus'];
                        if ($paymentStatus == 'process') {
                            $paymentStatus = _uc($_e['OK']);
                        } else {
                            $paymentStatus = _uc($_e['InComplete']);
                        }

                        echo $paymentStatus;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Reservation Number']); ?></td>
                    <td>
                        <?php echo $data['rsvNo'];
                        ?>
                    </td>
                </tr>

                <?php 
                    $staple_pro_cat_div = '';
                    $bundle_type = $data['bundle_type'];
                    if(!empty($data['bundle_type'])){
                        $currencyId = $_SESSION['webUser']['currencyId'];
                        $currencySymbol = $data['price_code'];
                        // $currencyId = $this->productClass->currentCurrencyId();
                        // $currencySymbol = $this->productClass->currentCurrencySymbol();
                        $staple_cat_setting = unserialize( $functions->ibms_setting("stapleProductSetting") );
                        $bundle_array = array();
                        for ($i=0; $i < sizeof($staple_cat_setting['quantity']); $i++) { 
                            $bundle_array[$staple_cat_setting['quantity'][$i]] = $staple_cat_setting['price'][$currencyId][$i];
                        }
                        krsort($bundle_array);
                        $bundelT = $_e['Bundle feature applies:'];

                        $text = $bundelT."\n";
                            $bundle_type = rtrim($bundle_type,',');
                            $bundle_type = explode(',', $bundle_type);
                            foreach ($bundle_type as $key => $value) {
                                $text .= $value.' '.$_e['pcs for'].' '.$bundle_array[$value].' '.$currencySymbol."\n";
                            }

                        $staple_pro_cat_div = $text;
                    }    
                 ?>

                <tr>
                    <td><?php echo _uc($_e['Payment Info']); ?></td>
                    <td>
                        <div class="col-sm-10 col-md-9">
                            <textarea name="paymentInfo" class="form-control"
                                      placeholder="<?php echo _uc($_e['Enter Vendor Payment Information']); ?>"
                                      style="width: 320px;height: 268px;"><?php echo $data['payment_info']; 
                                      echo "\n". $staple_pro_cat_div;
                                      ?></textarea>
                        </div>
                    </td>
                </tr>

            </table>
        </div>



        <!-- invoice detail End -->
        <a class="btn btn-primary" id="made_to_measure"><?php $dbF->hardWords("Send Made to Measure Email",true); ?></a>

        <div class="clearfix"></div>

        <br>

        

        <div class="clearfix"></div>
        <br>

        <a href="<?php echo WEB_URL; ?>/invoicePrint.php?mailId=<?php echo $orderId; ?>" target="_blank"
           class="btn btn-info btn-lg"><?php echo _uc($_e['Print Out']); ?></a>
        <input type="submit" id="upbtn" onclick="return formSubmit();" name="submit" value="UPDATE"
               class="submit btn btn-primary btn-lg">

        <div class="padding-20"></div>

    </form>

    <div class="table-responsive newProduct col-sm-10">
        <form action="" method="post" id="internal_comment_form">
            <input type="hidden" name="orderIdint" value="<?php echo $orderId; ?>">
            <div class="col_portion">
                <h3><?php echo _uc($_e['INTERNAL COMMENT']); ?></h3>
            <div class="main_portion">
                <div class="hd_no1"><?php echo _uc($_e['Write a Comment for the Order']); ?></div>
                <div class="hd_no1"><?php echo _uc($_e['Status']); ?></div>
                <!--hd_no1 close-->
            <div class="col_select_portion">
                <input type="hidden" name="invoiceStatus" value="<?php echo $invoiceStatus; ?>">
                <p style="font-weight: bold;"><?php echo $oldStatus; ?></p>
            </div>
            <!--col_select_portion close-->
            <div class="hd_no1"><?php echo _uc($_e['Comment']); ?></div>
            <div class="txt_area">
                <textarea name="int_comTxt"></textarea>
            </div>
            <!--txt_area-->
            <div class="col_portion_middle">
                <!-- <ul>
                    <li><label><input type="checkbox" name="cb2" class="chb" /> Medella Kund via e-post</label></li>
                    <li><label><input type="checkbox" name="cb2" class="chb" /> Synlig i frontend</label></li>
                </ul> -->
            <a class="btn btn-info btn-lg" id="create_comment" style="cursor: pointer"><?php echo _uc($_e['Create Comment']); ?></a>
            <script>
                $(".chb").change(function()
                                      {
                                          $(".chb").prop('checked',false);
                                          $(this).prop('checked',true);
                                      });
            </script>
            </div>
            <!--col_portion_middle-->
            <div class="col_portion_bottom">
                <ul id="prev_internal"></ul>
            </div>
            <!--col_portion_bottom-->
            </div>
            <!--main_portion close-->
            </div>
            <!--col_portion close-->
        </form>
    </div>

    <div class="table-responsive newProduct col-sm-10">
        <form action="" method="post" id="email_template_form">
            <input type="hidden" name="senderr_email" value="<?php echo $data['sender_email']; ?>">
            <input type="hidden" name="invoic_id" value="<?php echo $data['invoice_id']; ?>">
            <div class="col_portion">
                <h3><?php echo _uc($_e['Email To Customer']); ?></h3>
            <div class="main_portion">
                <!-- <div class="hd_no1"><?php //echo _uc($_e['Write a Comment for the Order']); ?></div> -->
                <!-- <div class="hd_no1"><?php //echo _uc($_e['Email Templates']); ?></div> -->
                <!--hd_no1 close-->
                <div class="">
                    <select  style="" id="email_temp" name="email_temp" class="form-control">
                        <option disabled selected>Select Email Template</option>
                        <?php echo $invoice->productF->getOrderEmailTemplate(); ?>
                    </select>
                </div>
                <div class="col_select_portion">
                    <input type="text" class="form-control" id="titleHtml" name="titleHtml" placeholder="<?php echo _uc($_e['LETTER TITLE FOR ADMIN']); ?>"/>
                </div>

                <div class="col_select_portion">
                    <input type="text" class="form-control" id="nameHtml" name="nameHtml" placeholder="<?php echo _uc($_e['FROM NAME']); ?>"/>
                </div>

                <div class="col_select_portion">
                    <input type="text" class="form-control" id="mailHtml" name="mailHtml" placeholder="<?php echo _uc($_e['FROM MAIL']); ?>"/>
                </div>

                <div class="col_select_portion" id="subject">
                    <input type="text" class="form-control" id="subjectHtml" name="subjectHtml" placeholder="<?php echo _uc($_e['SUBJECT']); ?>"/>
                </div>

                <div class="col_select_portion" id="giftCard" style="display: none;">
                    <select style="" id="giftCardField" name="giftCard" class="form-control">
                        <option disabled selected>Select Gift Card</option>
                        <?php echo $invoice->productF->getAvailableGiftCards($data['price_code']); ?>
                    </select>
                </div>
                <!--col_select_portion close-->
                <div class="hd_no1"><?php echo _uc($_e['EMAIL MESSAGE']); ?></div>
                <div class="txt_area">
                    <textarea name="email_msg" id="msgHtml" class="ckeditor form-control"></textarea>
                </div>
                <!--txt_area-->
                <div class="col_portion_middle">
                <a class="btn btn-info btn-lg" id="send_email" style="cursor: pointer"><?php echo _uc($_e['Send Email']); ?></a>
                <script>
                    $(".chb").change(function(){
                      $(".chb").prop('checked',false);
                      $(this).prop('checked',true);
                    });
                </script>
                </div>
                <!--col_portion_middle-->
                <div class="col_portion_bottom">
                    <ul id="prev_internal"></ul>
                </div>
                <!--col_portion_bottom-->
            </div>
            <!--main_portion close-->
            </div>
            <!--col_portion close-->
        </form>
    </div>

    <div class="table-responsive newProduct col-sm-10">
            <!--<?php //echo _uc($_e['INTERNAL COMMENT']); ?> :
            <textarea class="form-control" style="height: 100px"
                      name="comment"><?php //echo $data['comment'] ?></textarea>-->
        <div class="col_portion">
            <h3><?php echo _uc($_e['Log List']); ?></h3>
        <div class="main_portion">
            <!--hd_no1 close-->
        <div class="col_portion_bottom">
            <ul id="log_ul">
            </ul>
        </div>
        <!--col_portion_bottom-->
        </div>
        <!--main_portion close-->
        </div>
        <!--col_portion close-->
        </div>

    <script type="text/javascript">
    getComments();
    getLogs();

        $('#made_to_measure').click(function(event) {
            email = '<?php echo $data['sender_email']; ?>';
            invoice_id = '<?php echo $data['invoice_id']; ?>';
            $.ajax({
                type: 'POST',
                url: 'adminorder/order_ajax.php?page=sendMadeMeasure',
                data: { email:email, inv_id:invoice_id }
            }).done(function(data)
                {
                    jAlertifyAlert('<?php $dbF->hardWords("Mail Send Successfully, Kindly check inbox/spam folder",true); ?>');
                    getLogs();
                });
        });

        $('#create_comment').on('click', function(){
            var form = $('#internal_comment_form').serialize();
            $.ajax({
                type: 'POST',
                url: 'adminorder/order_ajax.php?page=create_comment',
                data: form
            }).done(function(res)
                {
                    console.log(res);
                    getComments();
                });
        });

        $('#send_email').on('click', function(){
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
            var form = $('#email_template_form').serialize();
            // console.log(form);
            $.ajax({
                type: 'POST',
                url: 'adminorder/order_ajax.php?page=sendTemplateEmail',
                data: form
            }).done(function(res)
                {
                    console.log(res);
                    jAlertifyAlert('<?php $dbF->hardWords("Mail Send Successfully, Kindly check inbox/spam folder",true); ?>');
                    getLogs();
                });
        });

        function getComments(){
            var invoice_id = '<?php echo $orderId; ?>';
            $.ajax({
                type: 'POST',
                url: 'adminorder/order_ajax.php?page=getComments',
                data: { invoice_id:invoice_id }
            }).done(function(res)
                {
                    $('#prev_internal').html(res);
                    // console.log(res);
                });
        }

        function getLogs(){
            var invoice_id = '<?php echo $data['invoice_id']; ?>';
            $.ajax({
                type: 'POST',
                url: 'adminorder/order_ajax.php?page=getLogs',
                data: { invoice_id:invoice_id }
            }).done(function(res)
                {
                    $('#log_ul').html(res);
                    // console.log(res);
                });
        }

        $('#email_temp').on('change', function(){
            var tem_id = $(this).val();
            var host = '<?php echo $_SERVER['HTTP_HOST']; ?>';

            if(tem_id == 257){
                $('#giftCard').show();
            }

            // console.log(host);
            $.ajax({
                type: 'POST',
                url: 'adminorder/order_ajax.php?page=getTemplateDetail',
                data: { temp_id:tem_id }
            }).done(function(res)
                {
                    var title       = $($.parseHTML(res)).find("#title").text();
                    var from_name   = $($.parseHTML(res)).find("#from_name").text();
                    var from_mail   = $($.parseHTML(res)).find("#from_mail").text();
                    var subject     = $($.parseHTML(res)).find("#subject").text();
                    var message     = $($.parseHTML(res)).find("#message").html();

                    $('#titleHtml').val(title);
                    $('#nameHtml').val(from_name);
                    $('#mailHtml').val(from_mail+'@'+host);
                    $('#subjectHtml').val(subject);
                    CKEDITOR.instances.msgHtml.setData(message);
                    getLogs();
                });
        });

        function copyFunction(){
            var copyText = document.getElementById("extraAmountLink");

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);

        }

    </script>
    
<?php return ob_get_clean(); ?>