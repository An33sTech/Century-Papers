<?php

class invoice extends object_class{
    public $productF;
    public function __construct(){
        parent::__construct('3');
        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../../product_management/functions/product_function.php");
            $this->productF=new product_function();

            /**
             * MultiLanguage keys Use where echo;
             * define this class words and where this class will call
             * and define words of file where this class will called
             **/
            global $_e;
            global $adminPanelLanguage;
            $_w=array();
            //Invoice.php
            $_w['View Api Return Info'] = '' ;
            $_w['Invoice Detail View'] = '' ;
            $_w['ORDER SENDER DETAIL'] = '' ;
            $_w['Name'] = '' ;
            $_w['LOCATION'] = '' ;
            $_w['E-mail'] = '' ;
            $_w['Phone'] = '' ;
            $_w['Address'] = '' ;
            $_w['Post Code'] = '' ;
            $_w['City'] = '' ;
            $_w['Country'] = '' ;
            $_w['ORDER RECEIVER DETAIL'] = '' ;
            $_w['TOTAL'] = '' ;
            $_w['PROCESS'] = '' ;
            $_w['SALE QTY'] = '' ;
            $_w['DISCOUNT'] = '' ;
            $_w['SALE IN PRICE'] = '' ;
            $_w['ORIGINAL PRICE'] = '' ;
            $_w['STORE NAME'] = '' ;
            $_w['PRODUCT NAME'] = '' ;
            $_w['ORDER PRODUCTS'] = '' ;
            $_w['SNO'] = '' ;
            $_w['NO'] = '' ;
            $_w['Yes'] = '' ;
            $_w['Total Net Amount'] = '' ;
            $_w['Print Out'] = '' ;
            $_w['INTERNAL COMMENT'] = '' ;
            $_w['Enter Vendor Payment Information'] = '' ;
            $_w['Payment Info'] = '' ;
            $_w['Reservation Number'] = '' ;
            $_w['InComplete'] = '' ;
            $_w['OK'] = '' ;
            $_w['Payment Status'] = '' ;
            $_w['Payment Type'] = '' ;
            $_w['Value'] = '' ;
            $_w['Property'] = '' ;
            $_w['Payment Information'] = '' ;
            $_w['Send Email To Customer'] = '' ;
            $_w['Shipping Track Number'] = '' ;
            $_w['Invoice Status'] = '' ;
            $_w['Date Time'] = '' ;
            $_w['Total'] = '' ;
            $_w['Total Product Price'] = '' ;
            $_w['Shipping Price'] = '' ;
            $_w['Total Weight'] = '' ;
            $_w['Invoice ID'] = '' ;
            $_w['Invoice Detail'] = '' ;
            $_w['Custom'] = '' ;
            $_w['Close'] = '' ;
            //This class
            $_w['Stock'] = '' ;
            $_w['Submit DateTime'] = '' ;
            $_w['Stock QTY is less then your Order, Please check'] = '' ;
            $_w['Stock Error stock not found for process OR stock QTY error, Please check'] = '' ;
            $_w['Product Update Successfully'] = '' ;
            $_w['Product Update Failed'] = '' ;
            $_w['Product Update'] = '' ;
            $_w['Deal'] = '' ;
            $_w['Edit custom size form'] = '' ;
            $_w['User not fill final form'] = '' ;
            $_w['Print PDF'] = '' ;
            $_w['Discount Code'] = '' ;
            $_w['Creation Time'] = '' ;
            $_w['3 For 2 Category'] = '' ;
            $_w['Free Gift'] = '' ;
            $_w['Checkout'] = '' ;
            $_w['OFFER'] = '' ;
            $_w['Last Updated Time'] = '' ;
            $_w['RETURNS INFO'] = '' ;
            $_w['Refunded'] = '' ;
            $_w['Defected'] = '' ;
            $_w['Changed Product'] = '' ;
            $_w['Changed Size'] = '' ;
            $_w['Status Unknown'] = '' ;
            $_w['Write a Comment for the Order'] = '' ;
            $_w['Status'] = '' ;
            $_w['Comment'] = '' ;
            $_w['Create Comment'] = '' ;
            $_w['Email To Customer'] = '' ;
            $_w['Email Templates'] = '' ;
            $_w['LETTER TITLE FOR ADMIN'] = '' ;
            $_w['FROM NAME'] = '' ;
            $_w['FROM MAIL'] = '' ;
            $_w['SUBJECT'] = '' ;
            $_w['EMAIL MESSAGE'] = '' ;
            $_w['Send Email'] = '' ;
            $_w['Invoice'] = '' ;
            $_w['Invoice Status Updated'] = '' ;
            $_w['Log List'] = '' ;
            $_w['Invoice Status Updated'] = '' ;
            $_w['Invoice'] = '' ;
            $_w['Bundle feature applies:'] = '' ;
            $_w['pcs for'] = '' ;
            $_w['Extra Sale'] = '' ;
            $_w['Amount'] = '' ;
            $_w['Description'] = '' ;
            $_w['Email Sent To Customer For Extra Payment'] = '' ;
            $_w['EXTRA PAYMENTS'] = '' ;
            $_w['EXTRA AMOUNT'] = '' ;
            $_w['DESC'] = '' ;
            $_w['RESERVATION NO'] = '' ;
            $_w['PAYMENT INFO'] = '' ;
            $_w['UPDATE DATE'] = '' ;
            $_w['INVOICE DATE'] = '' ;
            $_w['INVOICE STATUS'] = '' ;
            $_w['PAYMENT TYPE'] = '' ;
            $_w['PAYMENT STATUS'] = '' ;
            $_w['EXTRA PAYMENT FORM'] = '' ;
            $_w['PAYMENT LINK'] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;

            $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Invoice');

        }
    }


    public function customSubmitValues($orderId){
        global $_e;
        $sql  = "SELECT *,
                    (SELECT setting_value FROM p_custom_setting as b WHERE b.fieldName=s.setting_name AND b.setting_name='name' AND b.c_id=a.custom_id ) as tName
                     FROM `p_custom_submit` as a JOIN `p_custom_submit_setting` as s ON a.id = s.orderId  WHERE a.id = '$orderId'";
        $data = $this->dbF->getRows($sql);
        if(empty($data)){
            return false;
        }

        foreach($data as $val){
            $name   = $val['setting_name'];
            $tName  = $this->functions->unserializeTranslate($val['tName']);
            if(empty($tName)){
                $tName = $name;
            }
            $value = $val['setting_value'];
            $form_fields[] = array(
                'label' => $tName,
                'format' => "$value"
            );
        }

        if($data[0]['submitLater']=='1' && $this->functions->isWebLink()){
            $customEditLink = WEB_URL."/viewOrder?editCustom=".$this->functions->encode($orderId);
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$customEditLink' class='btn themeButton'>".$_e["Edit custom size form"]."</a></div>"
            );
        }else if($data[0]['submitLater']=='1' && $this->functions->isAdminLink()){
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'>".$_e["User not fill final form"]."</div>"
            );
        }else if($data[0]['submitLater']=='0'){
            $form_fields[] = array(
                'label'  => $_e["Submit DateTime"],
                'type'   => "none",
                'format' => "<div class='text-center form-group  margin-0'>".date('H:i:s d-m-Y',strtoTime($data[0]['dateTime']))."</div>"
            );

            $pdfLink =  WEB_URL."/src/pdf/measurementPDF.php?id=$orderId&orderId=".$this->functions->encode($orderId);
            $form_fields[] = array(
                'label'  => $_e["Print PDF"],
                'type'   => "none",
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$pdfLink' target='_blank' class='btn btn-default'>{$_e["Print PDF"]}</a></div>"
            );

        }

        $form_fields['main'] = array(
            'type'   => "form",
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

        $array = array("form"=>$this->functions->print_form($form_fields,$format,false),"formFill"=>$data[0]['submitLater']);
        return $array;

    }

    public function dealSubmitPackage($orderId,$cart=true){
        if($cart) {
            $orderId = $this->getDealProductOrders($orderId);
        }
        foreach($orderId as $val){
            $name = $val['name'];
            $form_fields[] = array(
                //'label' => $name,
                'format' => "<div>$name</div>"
            );
        }

        $form_fields['main'] = array(
            'type'   => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>"
        );

        $format = '<div class="form-group border padding-5 margin-0">
                        <div class="col-sm-12 text-center">
                            {{form}}
                        </div>
                    </div>';

        return $this->functions->print_form($form_fields,$format,false);

    }

    public function invoiceDetail($id){
        $id=    intval($id);
        $sql = "SELECT * FROM order_invoice left join order_invoice_info
                    on order_invoice.order_invoice_pk= order_invoice_info.order_invoice_id
                    WHERE order_invoice.order_invoice_pk='$id'";

        $data =$this->dbF->getRow($sql);
        return $data;
    }
    public function orderData($id){
        $id=    intval($id);
        $sql = "SELECT * FROM order_invoice WHERE order_invoice.order_invoice_pk='$id'";

        $data =$this->dbF->getRow($sql);
        return $data;
    }
    public function invoiceProduct($id){
        $sql = "SELECT * FROM  order_invoice_product
                    WHERE order_invoice_id='$id'";

        $data =$this->dbF->getRows($sql);
        return $data;
    }


    public function handelKlarna($orderId,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done,$extra = false){
        //All work will Handel Accordingly
        $this->functions->require_once_custom('Class.myKlarna.php');
        $klarnaClass    = new myKlarna();

        return $klarnaClass->klarnaInvoices($orderId,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done,$extra);

    }



    public function update(){
        global $_e;
        if(!$this->functions->getFormToken('Invoice')){
            return false;
        }
        // $this->dbF->prnt($_POST);
        try{
        $this->db->beginTransaction();
        $id = $_POST['pId'];
        if(isset($_POST['submit'])){
            if(isset($_POST['invoiceStatus'])){
                $old_status = $_POST['old_status_id'];
                $old_status_name = $_POST['old_status_name'];
                $invoi_idd = $_POST['invoi_idd'];
                $inv = $_POST['invoiceStatus'];

                $old_trackNo = $_POST['old_trackNo'];

                $invStatus  =   $this->productF->invoiceStatusFind($inv);

                

                if($old_status != $inv){
                    $log_des = "Invoice status changed from $invStatus to $old_status_name";
                    $this->functions->orderlog(_js(_uc($_e['Invoice Status Updated'])),_js(_uc($_e['Invoice'])),$invoi_idd,$log_des);
                }

                if($old_trackNo != $_POST['trackNo']){
                    if(empty($old_trackNo) && $old_trackNo == ''){
                        $old_trackNo = 'NONE';
                    }
                    $new_trackNo = $_POST['trackNo'];
                    $log_des1 = "Order Shipping Track Number Changed From $old_trackNo To $new_trackNo";
                    $this->functions->orderlog('Order Shipping Track Number Changed','Invoice',$invoi_idd,$log_des1);
                }

                @$paymentInfo = $_POST['paymentInfo'];
                if(isset($_POST['payment'])){
                    $paymentTypeSql = "paymentType = '".$_POST['payment']."', ";
                    $paymentType    =   $_POST['payment'];
                }else{
                    $paymentType = '';
                    $paymentTypeSql = '';
                }

                /*  if(!isset($_POST['pro']) && isset($_POST['invoiceStatus'])){
                    echo $this->functions->notificationError("Error","Before Process Please select Orders product for continue process","btn-danger");
                    throw new Exception("");
                }*/
                if (($inv == '0' || $inv == '3' || $inv == '6')) {
                    $sql = "SELECT inTransaction,rsvNo,rsvNo_done,invoice_id FROM `order_invoice` WHERE order_invoice_pk = '$id' AND inTransaction!=''";
                    $dataTrans = $this->dbF->getRow($sql);

                    if ( $this->dbF->rowCount > 0 && ($paymentType == '2') ) {
                        $rsvNo = $dataTrans['rsvNo'];
                        $rsvNo_done = $dataTrans['rsvNo_done'];
                        $inTransaction = trim($dataTrans['inTransaction']);
                        $invv_id = $dataTrans['invoice_id'];
                        
                        $sql1 = "SELECT * FROM `order_extra_amount` WHERE invoice_no = '$invv_id' AND inTransaction!=''";
                        $extraTrans = $this->dbF->getRows($sql1);
                        
                        if( $this->dbF->rowCount > 0 && ($paymentType == '2') ){
                            foreach ($extraTrans as $key => $value) {
                                $extraPaymentType = $value['paymentType'];
                                $extraPaymentInfo = $value['payment_info'];
                                $extraId          = $value['id'];
                                $extrarsvNo       = $value['rsvNo'];
                                $extrarsvNo_done  = $value['rsvNo_done'];
                                $extrainTransaction = trim($value['inTransaction']);
                                /* ------- ---------- KLARNA ------------- ------------ */
                                $klarnaReturnExtra = $this->handelKlarna($extraId, $extrainTransaction, $inv, $extraPaymentType, $extrarsvNo, $extrarsvNo_done, true);

                                $returnKlarnaExtra = $klarnaReturnExtra;
                                /* ------- ----------KLARNA End------------- ------------ */
                                $paymentInfoExtra = $extraPaymentInfo . "\n $returnKlarnaExtra";

                                $sql0 = "UPDATE `order_extra_amount` SET invoice_status = '$invoice_id',payment_info = '$paymentInfoExtra'  WHERE invoice_no = '$invv_id' ";
                                $this->dbF->setRow($sql0);
                                
                            }
                        }
                        
                        /* ------- ---------- KLARNA ------------- ------------ */
                        $klarnaReturn = $this->handelKlarna($id, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done);
                        $returnKlarna = $klarnaReturn;
                        /* ------- ----------KLARNA End------------- ------------ */
                        $paymentInfo = $paymentInfo . "\n $returnKlarna";
                    }
                }

                $sql ="UPDATE order_invoice SET invoice_status='$inv',
                 payment_info = ?,
                 $paymentTypeSql
                 trackNo    = ?
                  WHERE order_invoice_pk = '$id'";

                $this->dbF->setRow($sql,array($paymentInfo,$_POST['trackNo']),false);

                if($_POST['sendEmail']=='1'){
                    $link       = WEB_URL."/viewOrder?view=$id&orderId=".$this->functions->encode($id);
                    $invStatus  =   $this->productF->invoiceStatusFind($inv);

                    $to         =  $_POST['toEmail'];
                    $invoice    =   $this->functions->ibms_setting('invoice_key_start_with');
                    $mailArray['link']        =   $link;
                    $mailArray['invoiceStatus'] =   $invStatus;
                    $mailArray['invoiceNumber'] =   $invoice."".$id;
                    $mailArray["other"]['shippingNumber'] =  $_POST['trackNo'];
                    $this->functions->send_mail($to,'','','orderUpdate','',$mailArray);
                }
            }

            if(isset($_POST['pro'])){
                @$pr = $_POST['payment'];

                //Stock Not deduct on Admin side
                $status = $this->stockDeductFromOrderAdmin($id,false);
                if($status===false){
                    throw new Exception("");
                }
            }
            if($this->dbF->rowCount>0){
                echo  $this->functions->notificationError(_js(_uc($_e["Product Update"])),_js($_e["Product Update Successfully"]),"btn-success");
            }else{
                echo  $this->functions->notificationError(_js(_uc($_e["Product Update"])),_js($_e["Product Update Failed"]),"btn-danger");
            }
       }

            $this->db->commit();
        }catch(Exception $e){
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }


    public function stockDeductFromOrderAdmin($orderId,$transection=true){
        global $_e;

        $sql ="SELECT * FROM order_invoice_product WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRows($sql,false);

        foreach($data as $d){
            $invProductId = $d['invoice_product_pk'];

            if(in_array($d['invoice_product_pk'],$_POST['pro'])){
            }else{
                continue;
            }

            $pids = $d['order_pIds'];
            $pids = explode("-",$pids);

            $pId = $pids[0];
            $scaleId = $pids[1];
            $colorId = $pids[2];
            $storeId = $pids[3];

            $saleQTY = $d['order_pQty'];

            @$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;
            $hash       =   md5($hashVal);

            $invQty =   $this->productF->stockProductQty($hash);
            if($saleQTY  <= $invQty){
                if( $this->productF->stockProductQtyMinus($hash,$saleQTY) ){
                    $sql ="UPDATE order_invoice_product SET order_process = '1' WHERE invoice_product_pk = '$invProductId'";
                    $this->dbF->setRow($sql);
                    //$this->functions->setlog('Product Sale','Inventory',$invProductId,'Stock Deduct,StockId '.$invProductId.' :  QTY:'.$saleQTY,$transection);
                }else{
                    echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock Error stock not found for process OR stock QTY error, Please check"]),"btn-danger");
                    return false;
                }
            }else{
                echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock QTY is less then your Order, Please check"]),"btn-danger");
                return false;
            }
        } //foreach
    }

    public function stockDeductFromOrder($orderId,$transection=true){
        global $_e;

        //if unlimit stock, then just make all to process, else one by one do all
        if($this->functions->developer_setting('product_check_stock') == '0'){
            $sql = "UPDATE order_invoice_product SET order_process = '1' WHERE order_invoice_id = '$orderId'";
            $this->dbF->setRow($sql);
            //return true;
        }


        $sql ="SELECT * FROM order_invoice_product WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRows($sql,false);
       // $this->db->beginTransaction();
        $return = false;
        foreach($data as $d){
            $invProductId   = $d['invoice_product_pk'];
            $pids           = $d['order_pIds'];
            $pids           = explode("-",$pids);
            $pId        =   $pids[0];
            $scaleId    =   $pids[1];
            $colorId    =   $pids[2];
            $storeId    =   $pids[3];
            $customId   =   $pids[4];
            @$dealId    =   $d['deal']; // if not it is 0
            @$info      =   unserialize($d['info']);

            if($customId != '0' && $scaleId == '0'){
                return true;
            }

            $saleQTY   = $d['order_pQty'];
            if($dealId == '0'){
                $return = $this->stockDeductFromOrderLoop($pId,$scaleId,$colorId,$storeId,$d);
            }else{
                foreach($info as $val){
                    $pids       = $val['pIds'];
                    $pids       = explode("-",$pids);
                    $pId        =   $pids[0];
                    $scaleId    =   $pids[1];
                    $colorId    =   $pids[2];
                    $return =  $this->stockDeductFromOrderLoop($pId,$scaleId,$colorId,$storeId,$d);
                    if($return==false){
                        break;
                    }
                }
            }

        } //foreach
        if($return==false){
            return false;
        }
        return true;
    }

    private function stockDeductFromOrderLoop($pId,$scaleId,$colorId,$storeId,$data){
        global $_e;
          global $conIntra;
        $invProductId   = $data['invoice_product_pk'];
        $saleQTY        = $data['order_pQty'];
        @$dealId    =   $data['deal']; // if not it is 0
        @$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;
        $hash       =   md5($hashVal);
        
        
$order_invoice_id        = $data['order_invoice_id'];
        
          $add_and_minus_product_to_intranet = $this->functions->ibms_setting('add_and_minus_product_to_intranet');



        if(!empty($add_and_minus_product_to_intranet) && $add_and_minus_product_to_intranet == 1){
            
            
// ---------------------stock deduct from se to Intranet--------------------------
    // if(
$thisIs =    $this->productF->stockProductQtyMinusIntraNEW($hash,$saleQTY,$order_invoice_id);



$sql = "UPDATE order_invoice_product SET removeIntra = '$thisIs' WHERE invoice_product_pk = '$invProductId'";
$this->dbF->setRow($sql);


     // ){
                // $sql = "UPDATE order_invoice_product SET order_process = '1' WHERE invoice_product_pk = '$invProductId'";
                // $this->dbF->setRow($sql);
                //$this->functions->setlog('Product Sale','Inventory',$invProductId,'Stock Deduct,StockId '.$invProductId.' :  QTY:'.$saleQTY,$transection);
            // }else{
                // echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock Error stock not found for process OR stock QTY error, Please check"]),"btn-danger");
                // return false;
                
                
                
// $sql = "INSERT INTO `product_inventory` (`qty_store_id`,
//                                                 `qty_product_id`,
//                                                 `qty_product_scale`,
//                                                 `product_store_hash`,
//                                                 `qty_product_color`,
//                                                 `qty_item`) VALUES (?,?,?,?,?,?)";
// $array = array($storeId,$pId,$scaleId,$hash,"0","-1");
// $conIntra->setRow($sql,$array,false);
// $lastId = $conIntra->rowLastId;

//             }
            


        // --------------------------------------------------
}






        $product_check_stock = $this->functions->developer_setting('product_check_stock');

        $invQty =   $this->productF->stockProductQty($hash);
        if($saleQTY  <= $invQty || $product_check_stock == '0'){
            if($dealId != '0') {
                $this->productF->productDealCountPlus($dealId,$saleQTY);
            }
            $this->productF->productSaleCountPlus($pId,$saleQTY);
            if($this->productF->stockProductQtyMinus($hash,$saleQTY) ){
                $sql = "UPDATE order_invoice_product SET order_process = '1' WHERE invoice_product_pk = '$invProductId'";
                $this->dbF->setRow($sql);
                //$this->functions->setlog('Product Sale','Inventory',$invProductId,'Stock Deduct,StockId '.$invProductId.' :  QTY:'.$saleQTY,$transection);
            }else{
                echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock Error stock not found for process OR stock QTY error, Please check"]),"btn-danger");
                return false;
            }
            
            
            
            
            
            
      
        }else{
            echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock QTY is less then your Order, Please check"]),"btn-danger");
            return false;
        }
        
   
        
        return true;
    }

}


?>