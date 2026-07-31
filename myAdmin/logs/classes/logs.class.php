<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class logs extends object_class{
    

public $product; // admin/product/classes/
public $productF;
public $productClass;

public function __construct(){
parent::__construct('3');
if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
else {
require_once(__DIR__."/../../product_management/functions/product_function.php");
$this->productF=new product_function();
}

if (isset($GLOBALS['productClass'])) $this->productF = $GLOBALS['productClass'];
else {
require_once(__DIR__.'/../../../_models/functions/webProduct_functions.php');
$this->productClass=new webProduct_functions();
}



//product add/edit class
if (isset($GLOBALS['product'])) $this->product = $GLOBALS['product'];
else {
    require_once(__DIR__ . "/../../product/classes/product.class.php");
    $this->product = new product();
}





/**
* MultiLanguage keys Use where echo;
* define this class words and where this class will call
* and define words of file where this class will called
**/
global $_e;
global $adminPanelLanguage;
$_w=array();

//Defect Archive
$_w['Defect Archive'] = '' ;

//Defect register Page
$_w['Defect'] = '' ;
$_w['Defected registration save successfully'] = '' ;
$_w['Added'] = '' ;
$_w['Defect Error'] = '' ;
$_w['Defected registration save Fail'] = '' ;
$_w['Defect Registration'] = '' ;
$_w['Register'] = '' ;
$_w['Defect Images'] = '' ;

$_w['Select Store'] = '' ;
$_w['Enter Comment'] = '' ;
$_w['What We Do? (comment)'] = '' ;
$_w['Invoice Number'] = '' ;
$_w['Customer Name'] = '' ;
$_w['Date'] = '' ;
$_w['New Defect Registration'] = '' ;
$_w['Vendor Name'] = '' ;
$_w['Create By Vendor?'] = '' ;
$_w['Yes'] = '' ;
$_w['No'] = '' ;

$_w['With Invoice'] = '' ;
$_w['With Out Invoice'] = '' ;



$_w['Vendor Date'] = '' ;
$_w['Vendor Comment'] = '' ;

$_w['PRODUCT'] = '' ;
$_w['PRODUCT SCALE'] = '' ;
$_w['PRODUCT COLOR'] = '' ;
$_w['QTY'] = '' ;

$_w['Enter Product Name'] = '' ;
$_w['Enter Product Scale'] = '' ;
$_w['Enter Product Color'] = '' ;
$_w['Enter Product Quantity'] = '' ;
$_w['Enter Defect Description'] = '' ;
$_w['Add Product'] = '' ;
$_w['Remove Checked Items'] = '' ;
$_w['Check/Uncheck All'] = '' ;
$_w['SNO'] = '' ;
$_w['Thumbnail'] = '' ;

$_w['DESCRIPTION'] = '' ;
$_w['ACTION'] = '' ;
$_w['Delete'] = '' ;

$_w['Drop images here to upload.'] = '' ;
$_w['they will only be visible to you'] = '' ;
$_w['Image Preview'] = '' ;
$_w['Close'] = '' ;
//Defect register Page End

//Index page
$_w['Logs Management'] = '' ;

//Product Defect Form
$_w['Product Defect'] = '' ;
$_w['Delete Fail Please Try Again.'] = '' ;
$_w['GO BACK'] = '' ;

//Product return form
$_w['Product Return'] = '' ;

//Product return Archive
$_w['Return Products'] = '' ;
$_w['Return Archive'] = '' ;
$_w['Return Registration'] = '' ;

//This Class
$_w['Remove'] = '' ;
$_w['Defected Product Delete Successfully'] = '' ;
$_w['Search By Date Range'] = '' ;
$_w['Date From'] = '' ;
$_w['Date To'] = '' ;

$_w['INVOICE'] = '' ;
$_w['CUSTOMER'] = '' ;
$_w['INVOICE DATE'] = '' ;
$_w['VENDOR'] = '' ;
$_w['CREATE BY VENDOR'] = '' ;
$_w['REGISTRATION DATE'] = '' ;
$_w['PRODUCTS'] = '' ;

$_w['CLAIM NO'] = '' ;
$_w['SWITCH PRODUCT'] = '' ;
$_w['BANK NAME'] = '' ;
$_w['SORT CODE'] = '' ;
$_w['ACCOUNT NUMBER'] = '' ;
$_w['WANT TO CHANGE TO'] = '' ;
$_w['MESSAGE'] = '' ;

$_w['Order Code'] = '' ;
$_w['Product Name'] = '' ;
$_w['Number Which Claims In'] = '' ;
$_w['Want to switch to another product or get your money back?'] = '' ;

$_w['Defect Image'] = '' ;
$_w['I want to change to'] = '' ;
$_w['When replacing'] = '' ;
$_w['sortCode'] = '' ;
$_w['Name of your bank'] = '' ;
$_w['Buy Back'] = '' ;
$_w['get money back'] = '' ;
$_w['get new item'] = '' ;
$_w['Some Thing Is Wrong Please Try Again.'] = '' ;
$_w['REASON'] = '' ;
$_w['NAME'] = '' ;
$_w['STORE NAME'] = '' ;
$_w['RECEIPT DATE'] = '' ;

$_w['REGISTER DATE'] = '' ;
$_w['Add In Stock?'] = '' ;
$_w['Enter Customer Name'] = '' ;
$_w['Enter Return Reason'] = '' ;
$_w['SAVE'] = '' ;
$_w['Return Product'] = '' ;
$_w['Return/Defect Product'] = '' ;
$_w['Return Product Add Successfully'] = '' ;
$_w['Return/Defect Registration Update Fail'] = '' ;
$_w['Return Registration Update Fail'] = '' ;
$_w['Return Registration Add successfully'] = '' ;
$_w['Return Product QTY {{qty}}'] = '' ;
$_w['Qty Back'] = '' ;
$_w['Defect Product Del'] = '' ;
$_w['Defect Product'] = '' ;
$_w['From Defect Order, 1 listed product delete'] = '' ;
$_w['USER NAME'] = '' ;
$_w['EMAIL'] = '' ;

$_w['Order Code'] = '' ;
$_w['User Info'] = '' ;
$_w['User Name'] = '' ;
$_w['Email'] = '' ;
$_w['Contact'] = '' ;
$_w['Product Name'] = '' ;
$_w['Number Which Claims In'] = '' ;
$_w['Get New Item'] = '' ;
$_w['Get Money Back'] = '' ;
$_w['Want to switch to another product or get your money back?'] = '' ;
$_w['Buy Back'] = '' ;
$_w['Name of your bank'] = '' ;
$_w['sortCode'] = '' ;
$_w['Account Number'] = '' ;
$_w['When Replacing'] = '' ;
$_w['I want to change to'] = '' ;
$_w['Message'] = '' ;
$_w['Submit'] = '' ;
$_w['STATUS'] = '' ;
$_w['Send Email'] = '' ;
$_w['Return/Defect Product Save Successfully'] = '' ;

// all in one product returns
$_w['All In One Product Returns'] = '' ;
$_w['Add Entry'] = '';
$_w['View Entries'] = '';
$_w['TYPE'] = '';
$_w['AMOUNT'] = '';
$_w['Refunded'] = '';
$_w['Defected'] = '';
$_w['Changed Product'] = '';
$_w['Changed Color Size'] = '';
$_w['Type your order to proceed'] = '';
$_w['Orders'] = '';
$_w['Choose products'] = '';
$_w['Choose return type'] = '';
$_w['Refund'] = '';
$_w['Defect'] = '';
$_w['Change product'] = '';
$_w['Change size/color'] = '';
$_w['Type product name to select product'] = '';
$_w['Continue'] = '';
$_w['Send'] = '';
$_w['OLD PRODUCT'] = '';
$_w['SNO'] = '';
$_w['INVOICE'] = '';
$_w['ADDRESS'] = '';
$_w['PRODUCT'] = '';
$_w['INVOICE DATE'] = '';
$_w['INVOICE'] = '';
$_w['COUNTRY'] = '';
$_w['PHONE'] = '';
$_w['SOLD PRICE'] = '';
$_w['INVOICE STATUS'] = '';
$_w['ACTION'] = '';




$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Logs');
}


public function defectEditImages($id){
global $_e;
// If product is edit mode
$eId=$id;
$qry="SELECT * FROM  `defect_image` WHERE `defect_id` = '$eId'";
$eData=$this->dbF->getRows($qry);
if($this->dbF->rowCount>0){

/*                echo "<style>
#dropbox .message{
display: none !important;
}
</style>";*/

foreach($eData as $key=>$val){
$img=$val['image'];
$imgId=$val['img_id'];
$e1 = _uc($_e['Remove']);
echo <<<HTML
<div class="preview">
<span class="imageHolder">
<img src="../images/$img" />
</span>

<div class="progressHolder col-sm-12">
<a class="productEditImageDel btn btn-danger" data-id="$imgId">$e1</a>
</div>
</div>
HTML;
}
}
}


public function get_total_rows($sql, $search_sql = '')
    {
         $search_w = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';
        $sql  = $sql . ' ' . $search_w;
        $data = $this->dbF->getRows($sql);
        return $recordsTotal = $this->dbF->rowCount;
    }


public function orderInvoiceInfo($orderId){
$sql    =   "SELECT * FROM order_invoice_info WHERE order_invoice_id = '$orderId'";
$data   =   $this->dbF->getRow($sql);
return $data;
}




public function get_inprocess_prod(){

        global $_e;
        
        $PIDs = array();
        
        if(!empty($_POST['invoice_id'])){
            
            foreach($_POST['invoice_id'] as $invoice_id){
            
            $sql  = " SELECT * FROM `order_invoice_product` LEFT OUTER JOIN order_invoice ON `order_invoice_product`.`order_invoice_id` = `order_invoice`.`order_invoice_pk` WHERE `invoice_product_pk` = ? ";
            $data = $this->dbF->getRow($sql,array($invoice_id));
    
            $pids          = $data['order_pIds'];
            $pids          = explode("-",$pids);
            $PIDs[]           = $pids[0];
            
            }
        
            
        }else{
            
            $id = $_POST['id'];
            $exp = explode("::",$id);
            $PIDs[] = $exp[0];
        }
        
        $PIDs;
        
        foreach($PIDs as $PID){

        $sql = 'SELECT order_invoice_id FROM order_invoice_product WHERE `order_pIds` LIKE "'.$PID.'%" order by invoice_product_pk desc';
    $row = $this->dbF->getRows($sql);
    
    $table = '';
    
    if(!empty($row)){
    
    // $recordsTotal = $this->logs->get_total_rows($row);



        // $uniq=uniqid('id');
                
                
        
        // $draw =0;
        
        // $columns = array();
        
        $i = 0;
        
    foreach($row as $value){
    
        $sql1 = 'SELECT * From order_invoice_product as oip LEFT JOIN order_invoice as oi on oip.order_invoice_id = oi.order_invoice_pk where order_invoice_id = '.$value["order_invoice_id"].' and oi.orderStatus != "inComplete" AND oi.invoice_status != "3" AND oi.invoice_status != "0" order by oip.order_invoice_id desc';
        $row1 = $this->dbF->getRows($sql1);

        // $this->dbF->prnt($row1);
        
        if(!empty($row1)){

            
            $i++;
            
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($row1[0]['invoice_status']);
            $st = $row1[0]['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == 0) $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 1) $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 2) $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 3) $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            


            $invoiceDate = date('Y-m-d H:i:s', strtotime($row1[0]['invoice_date']));
            $invoiceId = $row1[0]['order_invoice_pk'];

            $country = $row1[0]['shippingCountry'];
            // $country = $this->functions->countryFullName($country);

            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $row1['orderUser'];
            $customer_email = $orderInfo['sender_email'];
            $customer_Name = $orderInfo['sender_name'];
            $sender_address = $orderInfo['sender_address'];
            $sender_phone = $orderInfo['sender_phone'];

        
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['No']) . "</div>";
            
            
            if ($row1[0]['order_process'] == 1) {


                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                
                
            }


            // $action = "
            // <a href='?pId=$invoiceId' id='orderProcess' data-method='post' data-action='".WEB_URL."/myAdmin/-order?page=edit' class='btn'>
            //                 <i class='glyphicon glyphicon-edit'></i>
            //             </a>
            // ";
                        
$action = "";

     
            
            $table .= '<tr>
            <td>'.$i.'</td>
            <td>'.$row1[0]["invoice_id"].'</td>
            <td>';
            foreach($row1 as $pName){
                $table .= $pName["order_pName"].' <br> ';
            }
            $table .='</td>
            
            <td>'.$country.'</td>
            <td>'.$sender_phone.'</td>
            <td>'.$invoiceDate.'</td>
            
            <td>'.$divInvoice.'</td>
            
            </tr>';
            
            // $customer_Name,
            // $paymentMethod,"$val[inTransaction]",
                // $errorBtn .$orderProcess,
                // $customer_email,
                // "$val[flagged]",

        }
        
        
        
    }
            
    }else{
        $table = "<div style='font-size: 18px;text-align: center;margin: 100px;'>No Record Found</div>";
    }
    
        }
    
    // $arr["table"] = $table;
    // $arr["noRecord"] = $noRecord;
    
    // echo json_encode($arr);
    
    echo $table;
    
    
}




public function pendingOrder(){
    
    $pId = $_GET['pId'];
    
    $sql = 'SELECT order_invoice_id FROM order_invoice_product WHERE `order_pIds` LIKE "'.$pId.'%" order by invoice_product_pk desc';
    $row = $this->dbF->getRows($sql);
    
    $recordsTotal = $this->get_total_rows($row);
        
        // $draw =0;
        
        $columns = array();
        
    foreach($row as $value){
    
        $sql1 = 'SELECT * From order_invoice_product as oip LEFT JOIN order_invoice as oi on oip.order_invoice_id = oi.order_invoice_pk where order_invoice_id = '.$value["order_invoice_id"].'';
        $row1 = $this->dbF->getRows($sql1); 

        $i = $start;
        foreach ($row1 as $key => $val) {
            
            if ($draw == 1) {
                $draw - 1;
            }
    
            $columns["draw"] = $draw + 1;
            $columns["recordsTotal"] = $recordsTotal; //total record,
            $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear
        
            
            
            $i++;
            
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == 0) $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 1) $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 2) $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 3) $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            
            // $errorBtn = "";
            //  if( strpos($val['payment_info'], " Error :") !== false){
            // $temp = explode(" Error :" , $val['payment_info'] );
            
            // $payment_info .= $temp[0];
            // // ".$val['payment_info']."
            // $errorBtn = "<div class='btn btn-danger  btn-sm' title = '".$val['payment_info']."'> Error </div>";
            //  }
            
            

            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];

            $country = $val['shippingCountry'];
            // $country = $this->functions->countryFullName($country);

            $orderInfo = $this->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_email = $orderInfo['sender_email'];
            $customer_Name = $orderInfo['sender_name'];
            $sender_address = $orderInfo['sender_address'];
            $sender_phone = $orderInfo['sender_phone'];
            // if (is_numeric($orderUser_id)) {
            //     $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
            //     $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            // }

        
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            
            
            if ($val['order_process'] == 1) {


                 $pIds = explode("-", $val['order_pIds']);
                $order_pName   = $val['order_pName'];
                
                $eX = explode(" - ", $order_pName,2);
                $s= @$eX[1];
                
                 $pQty = $val['order_pQty'];



                //make sure all order process or custome process
                
                // $sqlbtn = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                // $this->dbF->getRow($sqlbtn);
                
                // if ($this->dbF->rowCount > 0) {
                
                    //Ja = yes
                //     $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                // } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                // }
                
            }else{



             $pIds = explode("-", $val['order_pIds']);
                            // @$pId = $pIds[0];
                            // @$scaleId = $pIds[1];
                            // @$colorId = $pIds[2];
                            // @$storeId = $pIds[3];
                            // @$customId = $pIds[4];
        
        
            $order_pName   = $val['order_pName'];
            
            $eX = explode(" - ", $order_pName,2);
            $s= @$eX[1];
            
                 $pQty = $val['order_pQty'];
            
        }


            $action = "<div class='btn-group btn-group-sm'>
                       
                        <a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a>";
            // if ($date < $minusDays) {
            //     $action .= "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
            //              <i class='glyphicon glyphicon-trash trash'></i>
            //              <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            //          </a>";
            // } else {
            //     $action .= "<a class='btn'>
            //              <i class='glyphicon glyphicon-trash '></i>
            //              <i class='glyphicon glyphicon-ban-circle combineicon'></i>
            //          </a>";
            // }

            // if($val['flagged'] == 1){
            //     $action .= "<a class='btn' data-id='$invoiceId' onclick='return removeFlagOrderToTop(this);'>
            //                 <i class='glyphicon glyphicon-pushpin'></i>
            //             </a>";
            // }else{
            //     $action .= "<a class='btn' data-id='$invoiceId' onclick='return flagOrderToTop(this);'>
            //                 <i class='glyphicon glyphicon-pushpin'></i>
            //             </a>";
            // }




// $form_invoice[] = array(
// "type" => "select",
// "array" => $this->productF->invoiceStatusArray(),
// "select" => $val['invoice_status'],
// "id" => $st.'-'.$val['invoice_id'],
// "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
// "class" => "form-control invoice_quick_select",
// "format" => "<div class='invoice_quick_select_div wwwww'>{{form}}</div>"
// );




            // $invoice_status = $this->functions->print_form($form_invoice, "", false);

            // $statusFine = $this->productF->invoiceStatusFind($val['invoice_status']);
          
           //10 columns
            $count_me = "<span  class='countMe_'>$val[total_price]</span> $val[price_code]";
            
            $columns["data"][$key] = array(
                $i,
                "$val[invoice_id]",
                "$val[pname]",
                $sender_address,
                $country,
                $sender_phone,
                $invoiceDate,
                $count_me,
                $divInvoice,
                $action
            );
            
            // $customer_Name,
            // $paymentMethod,"$val[inTransaction]",
                // $errorBtn .$orderProcess,
                // $customer_email,
                // "$val[flagged]",

        }
        
        
        
    }
    
        // if ($recordsTotal == '0') {
        //     $columns["data"] = array();
        // }
       
          
        echo json_encode($columns);
}



public function defectDel(){
global $_e;
try{
$this->db->beginTransaction();

$id=$_POST['id'];

$sql3="SELECT * FROM defect_image WHERE defect_id='$id'";
$data=$this->dbF->getRows($sql3,false);
foreach($data as $key=>$val){
$this->functions->deleteOldSingleImage($val['image']);
}

$sql2   =   "DELETE FROM defected WHERE id='$id'";
$this->dbF->setRow($sql2,false);
if($this->dbF->rowCount) echo '1';
else echo '0';

$this->db->commit();
$this->functions->setlog(_uc($_e['Delete']),_uc($_e['Defect']),$id,($_e['Defected Product Delete Successfully']));
}catch (PDOException $e) {
echo '0';
$this->db->rollBack();
$this->dbF->error_submit($e);
}
}


public function defectEditImageDel(){
$id=$_POST['imageId'];

$sql3="SELECT * FROM `defect_image` WHERE `img_id`='$id'";
$data=$this->dbF->getRow($sql3);
$this->functions->deleteOldSingleImage($data['image']);
$sql3="DELETE FROM `defect_image` WHERE `img_id`='$id'";
$this->dbF->setRow($sql3);
if($this->dbF->rowCount>0){
echo "1";
}else{
echo "0";
}
}

public function defectView(){
global $_e;
$this->functions->dataTableDateRange();
echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['INVOICE']) .'</th>
<th>'. _u($_e['CUSTOMER']) .'</th>
<th>'. _u($_e['INVOICE DATE']) .'</th>
<th>'. _u($_e['VENDOR']) .'</th>
<th>'. _u($_e['CREATE BY VENDOR']) .'</th>
<th>'. _u($_e['REGISTRATION DATE']) .'</th>
<th>'. _u($_e['PRODUCTS']) .'</th>
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';

$sql    =   "SELECT * FROM defected WHERE `updateD`='1' ORDER BY id DESC";
$data   =   $this->dbF->getRows($sql);
$i=0;
foreach($data as $key=>$val){
$iscreat="";
if($val['isVendor']=="Yes"){
$iscreat="$val[isVendor]";
if($val['vCreateDate']!=""){
$iscreat.=" -- ".$val['vCreateDate'];
}
}else{$iscreat="";}
$i++;
echo "
<tr>
<td>$i </td>
<td>$val[orderNo]</td>
<td>$val[customerName]</td>
<td>".$val['returnDate']."</td>
<td>$val[vendorName]</td>
<td>$iscreat</td>
<td>".$val['dateTime']."</td>
<td>";
$sql2="SELECT * FROM defectedorder WHERE `defectedId`='$val[id]' ORDER BY id ASC";
$data2=$this->dbF->getRows($sql2);
foreach($data2 as $key2=>$val2){
echo "<a class='defectProduct removeMe' id='$val2[id]'>($val2[pName]) - $val2[pQty]<br/></a>";
}
echo "</td>
<td>
<div class='btn-group btn-group-sm'>
<a href='?editId=$val[id]' data-method='post' data-action='-logs?page=defectReg' class='btn receiptEdit'>
<i class='glyphicon glyphicon-edit receiptEdit'></i
</a>

<a data-id='$val[id]' href='#' onclick='defectDelete(this);' class='btn'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div>
</td>
</tr>";
}

echo '
</tbody>
</table>
</div> <!-- .table-responsive End -->';


}

public function productReturnOrDefectTable($type='return'){
global $_e;
echo '
<div class="table-responsive">
<style>
.colorBlack:hover{
color:#000;
}
</style>
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['INVOICE']) .'</th>
<th>'. _u($_e['CUSTOMER']) .'</th>
<th>'. _u($_e['USER NAME']) .'</th>
<th>'. _u($_e['EMAIL']) .'</th>
<th>'. _u($_e['PRODUCTS']) .'</th>
<th>'. _u($_e['MESSAGE']) .'</th>
<th>'. _u($_e['STATUS']) .'</th>
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';

$sql    =   "SELECT orderId,id,userId,name,email,products,message,readStatus,status FROM product_return_form WHERE type='$type' ORDER BY id DESC";
$data   =   $this->dbF->getRows($sql);
// $data   =   $this->dbF->getRows($sql);
$i=0;
$invoiceKey =   $this->functions->ibms_setting('invoice_key_start_with');
foreach($data as $key=>$val){
$i++;
$id         =   $val['id'];

$st = $val['status'];
$invoiceStatus = $this->productF->invoiceStatusFind($st);
$onclick = " onclick= 'show_quick_invoice(this);' ";
if($st==0) $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
else if($st==1) $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
else if($st==2) $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
else if($st==3 || $st==11) $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
else $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";


$class = "";
$orderId    =   $val['orderId'];
$invoiceId  =   $invoiceKey . ' ' . $orderId;
$invoiceId  =   "<a href='?pId=$orderId' data-method='post'  data-window='new' data-action='-order?page=edit' class='btn btn-info'>$invoiceId</a>";
if($val['readStatus']=='0'){
//$class= 'btn-primary colorBlack';
}

$sql            =   "SELECT * FROM accounts_user WHERE acc_id = '$val[userId]'";
$customerData   =   $this->dbF->getRow($sql,false);
@$customerName   =   @$customerData['acc_name'];
if(!empty($customerName)) {
$customerName = "<a href='-webUsers?page=edit&userId=$val[userId]' class='btn btn-info' target='_blank'>$customerName</a>";
}


echo "<tr class='$class'>";
echo "<td>$i</td>";
echo "<td>$invoiceId</td>";
echo "<td>$customerName</td>";
echo "<td>$val[name]</td>";
echo "<td>$val[email]</td>";
echo "<td>$val[products]</td>";
echo "<td>$val[message]</td>";
echo "<td>$divInvoice</td>";
echo "<td><div class='btn-group btn-group-sm'>
<a href='-".$this->functions->getLinkFolder(false)."&editId=$id'  class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>

<a data-id='$id' onclick='returnFormDel(this)'  class='btn'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div>
</td>";
echo "</tr>";
}

echo '</tbody>
</table>
</div> <!-- .table-responsive End -->';

$sql    =   "UPDATE product_return_form SET readStatus = ? WHERE type = ?";
$data   =   $this->dbF->setRow($sql, array(1, $type));
}

public function productReturnView($type='return'){
global $_e;
$form_fields    =     array();
$token = $this->functions->setFormToken('returnFormEdit',false);

if(isset($_GET['editId'])){
$id     = $_GET['editId'];
$sql    = "SELECT * FROM product_return_form WHERE type = '$type' AND id = '$id'";
$data   = $this->dbF->getRow($sql);

$form_fields[]  = array(
'type'      => 'hidden',
'name'      => 'editId',
'value'     => $id,
);
}

$form_fields[] = array(
'type'      => 'none',
'thisFormat' => $token,
);

$form_fields[] = array(
'label' => $_e['Order Code'],
'name' => 'insert[orderId]',
'required' => "true",
"value"     => @$data['orderId'],
'type' => 'text',
'class' => 'form-control',
);

$form_fields[]  = array(
'type'      => 'none',
'thisFormat' => '<div class="form-group">
<label class="col-sm-5 control-label">'.$_e['User Info'].'</label>
</div>',
);


$form_fields[] = array(
'label' => $_e['User Name'],
'name'  => 'insert[name]',
'required' => "true",
"value"     => @$data['name'],
'type'  => 'text',
'class' => 'form-control',
);

$form_fields[] = array(
'label' => $_e['Email'],
'name'  => 'insert[email]',
'required' => "true",
'type'  => 'email',
"value"     => @$data['email'],
'class' => 'form-control',
);

$form_fields[] = array(
'label' => $_e['Contact'],
'name'  => 'insert[phone]',
'required' => "true",
"value"     => @$data['phone'],
'type'  => 'text',
'class' => 'form-control',
);

$form_fields[] = array(
'type'      => 'none',
'thisFormat' => "<hr>",
);

$form_fields[] = array(
'label' => $_e['Product Name'],
'name'  => 'insert[products]',
'required' => "true",
"value"     => @$data['products'],
'type'  => 'textarea',
'class' => 'form-control',
);

$form_fields[] = array(
'label' => $_e['Number Which Claims In'],
'name'  => 'insert[claimNo]',
"value"     => @$data['claimNo'],
'type'  => 'text',
'class' => 'form-control',
);

$getNewT    = $_e['Get New Item'];
$moneybackT = $_e['Get Money Back'];
$form_fields[] = array(
'label'     => $_e['Want to switch to another product or get your money back?'],
'name'      => 'insert[switchProduct]',
'required'  => "true",
"value"     =>  "1,2",
"option"    =>  "$getNewT,$moneybackT",
"select"     => @$data['switchProduct'],
'type'      => 'radio',
'format'    => '<label class="radio-inline">{{form}} {{option}}</label>',
);

$form_fields[]  = array(
'type'      => 'none',
'thisFormat' => '<div class="form-group">
<label class="col-sm-5 control-label">'.$_e['Buy Back'].'</label>
</div>',
);


$form_fields[] = array(
'label' => $_e['Name of your bank'],
'name'  => 'insert[bankName]',
"value" => @$data['bankName'],
'type'  => 'text',
'class' => 'form-control',
);

$form_fields[] = array(
'label' => $_e['sortCode'],
'name'  => 'insert[sortCode]',
'type'  => 'text',
"value" => @$data['sortCode'],
'class' => 'form-control',
);

$form_fields[] = array(
'label' => $_e['Account Number'],
'name'  => 'insert[accountNo]',
'type'  => 'text',
"value"     => @$data['accountNo'],
'class' => 'form-control',
);

$form_fields[] = array(
'type'      => 'none',
'thisFormat' => '<div class="form-group">
<label class="col-sm-5 control-label">'.$_e['When Replacing'].'</label>
</div>',
);

$form_fields[] = array(
'label' => $_e['I want to change to'],
'name'  => 'insert[changeTo]',
'type'  => 'text',
"value" => @$data['changeTo'],
'class' => 'form-control',
);

$form_fields[] = array(
'label' => $_e['Message'],
'name'  => 'insert[message]',
'type'  => 'textarea',
"value"     => @$data['message'],
'class' => 'form-control',
);

$form_fields[] = array(
'type'  => 'none',
'thisFormat'  => '<hr>',

);

$form_fields[] = array(
'label' => _uc($_e['STATUS']),
'name'  => 'insert[status]',
'type'  => 'select',
"array" => $this->productF->invoiceStatusArray(),
"select"     => @$data['status'],
'class' => 'form-control',
);

$form_fields[] = array(
'label'     => $_e['Send Email'],
'name'      => 'mail',
'required'  => "true",
"value"     =>  "yes,no",
"option"    =>  array($_e["Yes"],$_e["No"]),
"select"     => "yes",
'type'      => 'radio',
'format'    => '<label class="radio-inline">{{form}} {{option}}</label>',
);

if($type=='defect') {
$images = $data['image'];
$images = unserialize($images);
$images = empty($images) ? array() : $images;
$temp = '';
foreach ($images as $img) {
$img = WEB_URL . '/images/' . $img;
$temp .= "<a href='$img' class='text-center' style='display: inline-block;' target='_blank'><img src='$img' class='img-responsive'/></a><br>";
}
$form_fields[] = array(
'label' => $_e['Defect Image'],
'type' => 'none',
'format' => '<div class="text-center">' . $temp . "</div>",
);
}

$form_fields[]  = array(
"name"  => 'submit',
'class' => 'btn btn-success defaultSpecialButton',
'type'  => 'submit',
'id'    => 'signup_btn',
"value" => $_e['Submit'],
);

$form_fields['form']  = array(
'type'      => 'form',
'class'     => "form-horizontal",
'method'   => 'post',
'format'   => '{{form}}'
);

$format = '<div class="form-group">
<label class="col-sm-5 control-label">{{label}}</label>
<div class="col-sm-7">
{{form}}
</div>
</div>';

$this->functions->print_form($form_fields,$format);
}

public function productReturnEditSubmit()
{
global $_e;
if (isset($_POST) && !empty($_POST)) {
if ($this->functions->getFormToken('returnFormEdit')) {
$lastId = $_POST['editId'];
$return = $this->functions->formUpdate("product_return_form", $_POST['insert'],$lastId);
if($return){
$this->functions->notificationError(_uc($_e['Return/Defect Product']),_uc($_e['Return/Defect Product Save Successfully']),'btn-success');
$this->functions->setlog(_uc($_e['Added']),_uc($_e['Return/Defect Product']),$lastId,_uc($_e['Return/Defect Product Save Successfully']),false);

//Send Email
$sendEmail = $_POST["mail"];
if($sendEmail == "yes"){
$status = $_POST["insert"]['status'];
$status = $this->productF->invoiceStatusFind($status);
$email = $_POST["insert"]["email"];
$name = $_POST["insert"]["name"];
$orderId = $_POST["insert"]["orderId"];
$invoice_id = $this->functions->ibms_setting("invoice_key_start_with").$orderId;

$mailArray['invoiceStatus'] =   $status;
$mailArray['invoiceNumber'] =   $invoice_id;
$this->functions->send_mail($email,'','','return_Product_Update',$name,$mailArray);
}



}else{
$this->functions->notificationError(_uc($_e['Return/Defect Product']),_uc($_e['Return/Defect Registration Update Fail']),'btn-danger');
}
}
}
}

public function receiptStoreSQL($column){
$sql="SELECT ".$column." FROM `store_name` ORDER BY `store_pk` ASC";
return $this->dbF->getRows($sql);
}

public function storeNamesOption(){
$data = $this->receiptStoreSQL("`store_pk`,`store_name`,`store_location`");
$op='';
if($this->dbF->rowCount > 0){
foreach($data as $val){
$op .="<option value='$val[store_pk]'>$val[store_name] - $val[store_location]</option>";
}
return $op;
}
return "";
}

public function returnDel(){
$id=$_POST['id'];
$sql2="DELETE FROM return_product WHERE id='$id'";
$this->dbF->setRow($sql2);
if($this->dbF->rowCount) echo '1';
else echo '0';
}

public function returnReport(){
global $_e;
if(isset($_POST['id'])){
$id = $_POST['id'];
}else{
echo ($_e['Some Thing Is Wrong Please Try Again.']);
exit;
}

$sql    =   "SELECT * FROM  `return_product_list` Where `rId` ='$id' ";
$data   =   $this->dbF->getRows($sql);

echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['PRODUCT']) .'</th>
<th>'. _u($_e['QTY']) .'</th>
<th>'. _u($_e['REASON']) .'</th>
</thead>
<tbody>';
$i=0;
foreach($data as $val){
$i++;
$pName= $val['pName'];
echo "<tr>
<td>$i</td>
<td>$pName</td>
<td>$val[qty]</td>
<td>$val[reason]</td>
</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";

}

public function returnView(){
global $_e;
$this->functions->dataTableDateRange();
echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['NAME']) .'</th>
<th>'. _u($_e['STORE NAME']) .'</th>
<th>'. _u($_e['RECEIPT DATE']) .'</th>
<th>'. _u($_e['INVOICE']) .'</th>
<th>'. _u($_e['REGISTER DATE']) .'</th>
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';

$sql="SELECT * FROM `return_product` ORDER BY id DESC";
$return_receipt = $this->dbF->getRows($sql);
$i=0;
foreach($return_receipt as $val){
$i++;
$id=$val['id'];
$storeName      =   $this->productF->getStoreName($val['return_store']);
$receiptDate    =   $val['date'];
echo "<tr class='tr_$id'>
<td>$i</td>
<td>$val[name]</td>
<td>$storeName</td>
<td>$receiptDate</td>
<td>$val[invoice]</td>
<td>$val[dateTime]</td>
<td> <div class='btn-group btn-group-sm'>
<a data-id='$id' onclick='ViewReturnModal(this)' class='btn receiptEdit'>
<i class='glyphicon glyphicon-list-alt receiptEdit'></i>
</a>
<a data-id='$id' onclick='returnDel(this);' class='btn'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div>
</td>
</tr>";
}

echo '</tbody>
</table>
</div> <!-- .table-responsive End -->';
}

public function returnRegister(){
global $_e;
$today = date('Y-m-d');
$token = $this->functions->setFormToken('returnForm',false);
echo '
<form action="" method="post" class="form-horizontal">
'.$token.'
<div class="col-sm-12">

<div class="col-md-6">
<div class="form-group">
<label for="date" class="col-sm-2 col-md-3 control-label">'. _uc($_e['Date']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" id="date" name="date" value="'.$today.'" class="form-control date">
</div>
</div>

<div class="form-group">
<label for="customerName" class="col-sm-2 col-md-3 control-label">'. _uc($_e['NAME']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" id="customerName" value="" name="customerName" class="form-control" placeholder="'. _uc($_e['Enter Customer Name']) .'">
</div>
</div>

<div class="form-group">
<label for="invoice" class="col-sm-2 col-md-3 control-label">'. _uc($_e['Invoice Number']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" id="invoice" value="" name="invoice" class="form-control" placeholder="'. _uc($_e['Invoice Number']) .'">
</div>
</div>


</div>

<div class="col-md-6">

<div class="form-group">
<label  class="col-sm-2 col-md-3 control-label">'. _uc($_e['Add In Stock?']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="make-switch" data-off="warning" data-on="success" data-on-label="'. _uc($_e['Yes']) .'" data-off-label="'. _uc($_e['No']) .'">
<input type="checkbox" id="addInStock" name="addInStock" value="Yes">
</div>
</div>
</div>

<div class="form-group">
<label for="receipt_store_id" class="col-sm-2 col-md-3 control-label">'. _uc($_e['Select Store']) .'</label>
<div class="col-sm-10 col-md-9">
<input type="hidden" name="receipt_store_id" class="form-control receipt_store_id" data-val="" required>
<fieldset id ="store">
<select required name="receipt_store_id"  id="receipt_store_id" class="form-control product_color">
<option value="">'. _uc($_e['Select Store']) .'</option>';

echo $this->storeNamesOption();
echo '
</select>
</fieldset>
</div>
</div>
</div>

<div class="clearfix"></div>

<hr><br>

<div class="table-responsive bootTable" >
<table id="selected" class="table sTable table-hover" style="min-width: 570px;" width="100%" border="0" cellpadding="0" cellspacing="0">
<thead>
<tr>
<th>'. _u($_e['PRODUCT']) .'</th>
<th>'. _u($_e['PRODUCT SCALE']) .'</th>
<th>'. _u($_e['PRODUCT COLOR']) .'</th>
<th>'. _u($_e['QTY']) .'</th>
<th>'. _u($_e['REASON']) .'</th>
</tr>
</thead>
<tbody>
<td>
<input type="text" class="form-control" id="receipt_product_id" placeholder="'. _uc($_e['Enter Product Name']) .'">
<input type="hidden" class="form-control receipt_product_id" data-val="">
</td>
<td>
<input type="text" class="form-control" id="receipt_product_scale" placeholder="'. _uc($_e['Enter Product Scale']) .'" readonly value="No Scale Avaiable">
<input type="hidden" class="form-control receipt_product_scale" data-val="">
</td>
<td>
<input type="text" class="form-control" required id="receipt_product_color" placeholder="'. _uc($_e['Enter Product Color']) .'" readonly value="No Color Avaiable">
<input type="hidden" class="form-control receipt_product_color" data-val="">
</td>
<td>
<input type="number" class="form-control" min="1" id="receipt_qty" placeholder="'. _uc($_e['Enter Product Quantity']) .'">
</td>
<td>
<input type="text" class="form-control" id="receipt_desc" placeholder="'. _uc($_e['Enter Return Reason']) .'">
</td>
</tbody>
</table>
</div>

<div class="form-group">
<div class="col-sm-10">
<button type="button" onclick="receiptFormValid();" id="AddProduct" class="btn btn-default">'. _uc($_e['Add Product']) .'</button>
</div>
</div>

<div style="margin:50px 0 0 0;">
<input type="button" class="btn btn-danger" onclick="removechecked()" value="'. _uc($_e['Remove Checked Items']) .'" >
<input type="button" class="btn btn-danger" onclick="uncheckall()" value="'. _uc($_e['Check/Uncheck All']) .'">
<br><br>

<div class="table-responsive" >
<table id="selected" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
<thead>
<tr>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['PRODUCT']) .'</th>
<th>'. _u($_e['QTY']) .'</th>
<th>'. _u($_e['DESCRIPTION']) .'</th>
</tr>
</thead>
<tbody id="vendorProdcutList">

</tbody>

</table>
</div>

<br>
<button type="submit" onclick="return formSubmit();" name="submit" value="SAVE" class="submit btn btn-primary btn-lg">'. _u($_e['SAVE']) .' </button>

</div> <!-- add product script div end -->

</div>
</form>';

}

function returnAdd(){
global $_e;

if(!empty($_POST['cart_list'])){
try {
if(!$this->functions->getFormToken('returnForm')){ return false;}

$this->db->beginTransaction();
$sql="INSERT INTO
`return_product`(
`name`,
`invoice`,
`return_store`,
`date`,
`addInStock`
) VALUES
(?,?,?,?,?)";

$stock = empty($_POST['addInStock'])?"0":"1";

@$array=array($_POST['customerName'],
$_POST['invoice'],
$_POST['receipt_store_id'],
$_POST['date'],
$stock
);

$run=$this->dbF->setRow($sql,$array,false);
$lastId=$this->dbF->rowLastId;

$form_prod='';
$i=0;

$cart = empty($_POST['cart_list'])? array():$_POST['cart_list'];
$returnQty = 0;
foreach($cart as $itemId){
$id=$itemId;
$pid=$itemId;

$temp="pName_".$id;
$p_name=$_POST[$temp];

$temp="pqty_".$id;
$qty=$_POST[$temp];
$returnQty += $qty;

$temp="pdesc_".$id;
$pDesc=$_POST[$temp];

$pArray     =   explode("_",$pid); //p_491-246-435-5    => p_ pid - scaleId - colorId - storeId;
$pIds       =   $pArray[1];
if($stock=='1'){
$pArray     =   explode("-",$pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
$productId  =   $pArray[0]; // 491
$scaleId    =   $pArray[1]; // 426
$colorId    =   $pArray[2]; // 435
$storeId    =   $pArray[3]; // 5

$this->AddQtyBackInStock($productId,$scaleId,$colorId,$storeId,$qty);
}

$qry_order="INSERT INTO `return_product_list`
(
`rId`,
`pId`,
`pName`,
`qty`,
`reason`
)
VALUES
('$lastId',?,?,?,?)";

$arry=array($pIds,$p_name,$qty,$pDesc);
$this->dbF->setRow($qry_order,$arry,false);
$i++;
}

$sql ="UPDATE `return_product` SET `qtyTotal` = ? WHERE id = ?";
$this->dbF->setRow($sql,array($returnQty, $lastId), false);

if($this->dbF->rowCount>0){
$this->functions->notificationError(_uc($_e['Return Product']),_uc($_e['Return Registration Add successfully']),'btn-success');
$this->functions->setlog(_uc($_e['Added']),_uc($_e['Return Product']),$lastId,_uc($_e['Return Product Add Successfully']),false);
}else{
$this->functions->notificationError(_uc($_e['Return Product']),_uc($_e['Return Registration Update Fail']),'btn-danger');
}

$this->db->commit();

} catch(PDOExecption $e) {
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_uc($_e['Return Product']),_uc($_e['Return Registration Update Fail']),'btn-danger');
}
}

}

    public function AddQtyBackInStock($pId="",$scaleId="",$colorId="",$storeId="",$qty="")
    {
        global $_e;
        $pqty = intval($qty);
    
        @$hashVal = $pId.":".$scaleId.":".$colorId.":".$storeId;
        $hash = md5($hashVal);
    
        $sqlCheck = "SELECT `product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = ?";
        $this->dbF->getRow($sqlCheck, [$hash]);
        
        if($this->dbF->rowCount>0){
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE `product_inventory` SET `qty_item` = `qty_item` + ?, `updateTime` = ? WHERE `product_store_hash` = ?";
            $this->dbF->setRow($sql, [$pqty, $date, $hash],false);
        }else{
            $sql = "INSERT INTO `product_inventory` (`qty_store_id`, `qty_product_id`, `qty_product_scale`, `qty_product_color`, `qty_item`, `product_store_hash`) VALUES (?, ?, ?, ?, ?, ?) ";
            $arry = [$storeId, $pId, $scaleId, $colorId, $pqty, $hash];
            $this->dbF->setRow($sql, $arry, false);
        }
        
        if($this->dbF->rowCount>0){
            $desc='Return Product QTY {{qty}}';
            $desc=  _replace('{{qty}}',$pqty,_uc($_e[$desc]));
            $user_email = @$_SESSION['_email'];
            $ref_id = $_SESSION['_uid'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = "";
                        
            foreach ($this->functions->getBrowserCommon() as $key => $val) {
                $browser .= "$key : $val <br />";
            }
            $sqlg_ = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->dbF->setRow($sqlg_, ['Qty Back', 'Return Product', $hash, $ref_id, $user_email, $pqty, '', $pId, $scaleId, '', $desc, $ip, $browser]);
        }
        
        return true;
    }



public  function AddQtyBackInStock_intra($pId="",$scaleId="",$colorId="",$storeId="",$qty=""){
    
}

public  function RemoveQtyFromInStock_intra($pId="",$scaleId="",$colorId="",$storeId="",$qty=""){
    
}



    public  function RemoveQtyFromInStock($pId="", $scaleId="", $colorId="", $storeId="", $qty="")
    {
        global $_e;
        $pqty = intval($qty);
    
        @$hashVal=$pId.":".$scaleId.":".$colorId.":".$storeId;
        $hash = md5($hashVal);
    
        $sqlCheck = "SELECT `product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = ?";
        $this->dbF->getRow($sqlCheck, [$hash]);
        if($this->dbF->rowCount>0){
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE `product_inventory` SET `qty_item` = qty_item - ?, `updateTime` = ? WHERE `product_store_hash` = ?";
            $this->dbF->setRow($sql, [$pqty, $date, $hash], false);
        }else{
            $sql = "INSERT INTO `product_inventory` (`qty_store_id`, `qty_product_id`, `qty_product_scale`, `qty_product_color`, `qty_item`, `product_store_hash`) VALUES (?, ?, ?, ?, ?, ?) ";
            $arry = [$storeId, $pId, $scaleId, $colorId, $pqty, $hash];
            $this->dbF->setRow($sql,$arry,false);
        }
        if($this->dbF->rowCount>0){
            $desc = 'Return Product QTY {{qty}}';
            $desc =  _replace('{{qty}}',$pqty,_uc($_e[$desc]));
            $user_email = @$_SESSION['_email'];
            $ref_id = $_SESSION['_uid'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = "";
                        
            foreach ($this->functions->getBrowserCommon() as $key => $val) {
                $browser .= "$key : $val <br />";
            }
            $sqlg_ = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->dbF->setRow($sqlg_, ['Qty Back', 'Return Product', $hash, $ref_id, $user_email, '', $pqty, $pId, $scaleId, '', $desc, $ip, $browser]);
        }
        return true;
    }






public function defectData($id){
$sqlD="SELECT * FROM defected  WHERE id = '$id' ";
$data=$this->dbF->getRow($sqlD);
return $data;
}

public function defectProducts($id){
//Defected products, it is inside defected order
$sqlD="SELECT * FROM defectedorder  WHERE defectedId = '$id' ";
$data=$this->dbF->getRows($sqlD);
return $data;
}




public function pImg(){

$id     =   $_POST['idz_pod'];

echo  WEB_URL."/images/".$img = $this->productF->productSpecialImage($id,'main');



}



public function defectProductDel(){
global $_e;
$id     =   $_POST['id'];
$sql    =   "DELETE  FROM defectedorder WHERE id='$id'";
$this->dbF->setRow($sql);
if($this->dbF->rowCount>0){
echo "1";
$this->functions->setlog(($_e['Defect Product Del']),($_e['Defect Product']),$id,($_e['From Defect Order, 1 listed product delete']));
}else{
echo "0";
}
}

public function returnFormDel(){
$id=$_POST['id'];

$sql3   =   "SELECT image FROM product_return_form WHERE id='$id'";
$data   =   $this->dbF->getRow($sql3,false);
$images =   $data['image'];
$images =   unserialize($images);
$images =   empty($images) ? array() : $images;
foreach ($images as $img) {
$this->functions->deleteOldSingleImage($img);
}

$sql2="DELETE FROM product_return_form WHERE id='$id'";
$this->dbF->setRow($sql2);
if($this->dbF->rowCount) echo '1';
else echo '0';
}

public function sign( $number ) { 
return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 ); 
} 

public function all_in_one_product_returns_view(){
global $_e;
echo '
<div class="table-responsive">
<style>
.colorBlack:hover{
color:#000;
}
</style>
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['INVOICE']) .'</th>
<th>'. _u($_e['TYPE']) .'</th>
<th>'. _u($_e['OLD PRODUCT']) .'</th>
<th>'. _u($_e['PRODUCT']) .'</th>
<th>'. _u($_e['AMOUNT']) .'</th>
</thead>
<tbody>';


$sql    =   " SELECT `all_in_one_returns`.*,`proudct_detail`.`prodet_name`,pdo.prodet_name as old_prodet_name FROM `all_in_one_returns` 
LEFT OUTER JOIN `proudct_detail` ON `proudct_detail`.`prodet_id` = `all_in_one_returns`.`new_pId`
LEFT OUTER JOIN `proudct_detail` pdo ON pdo.`prodet_id` = `all_in_one_returns`.`old_pId`
ORDER BY `id` DESC ";
$data   =   $this->dbF->getRows($sql);
$i=0;
$invoiceKey =   $this->functions->ibms_setting('invoice_key_start_with');
foreach($data as $key=>$val){
$i++;
$id         =   $val['id'];

# 1 means value is positive and not 0
if ( $this->sign($val['new_amount']) == 1 ) {
$price_div =  '<div class="btn btn-success  btn-sm" style="min-width:80px;">' . $val['new_amount'] . ' ' . $val['price_code'] . '</div>';

} elseif (  $this->sign($val['new_amount']) == -1  ) {
# -1 means value is negative and not 0
$price_div =  '<div class="btn btn-danger  btn-sm" style="min-width:80px;">' . $val['new_amount'] . ' ' . $val['price_code'] . '</div>';                
} else {
# value is zero
$price_div =  '<div class="btn btn-default btn-sm" style="min-width:60px;">' . $val['new_amount'] . ' ' . $val['price_code'] . '</div>';                

}

$refunds_array   = array( 2 => _uc($_e['Refunded']),  3 => _uc($_e['Defected']),  4 => _uc($_e['Changed Product']),  5 => _uc($_e['Changed Color Size']) );
$return_type_text = '<div class="btn btn-default btn-sm" >' . $refunds_array[$val['type']] . '</div>'; 

$class     = '';
$orderId   = $val['order_invoice_id'];
$invoiceId = $invoiceKey . ' ' . $orderId;

$link = '?pId=' . $orderId;



$invoiceId = "<a href='$link' data-method='post'  data-window='new' data-action='-order?page=edit' class='btn btn-info'>$invoiceId</a>";

$product_name = $this->functions->unserializeTranslate($val['prodet_name']);

$old_product_name = $this->functions->unserializeTranslate($val['old_prodet_name']);

$old_size  = ( $val['old_size']  == '' ) ? '' : ' (' . $val['old_size'] . ') ' ;
$old_color = ( $val['old_color'] == '' ) ? '' : '<div title="' . $val['old_color'] . '" class="color_div" style="background-color: #' . $val['old_color'] . '"></div>';

$old_product_name = $old_product_name . $old_size . $old_color;

echo "<tr class='$class'>";
echo "<td>$i</td>";
echo "<td>$invoiceId</td>";
echo "<td>{$return_type_text}</td>";
echo "<td>{$old_product_name}</td>";
echo "<td>{$product_name}</td>";
echo "<td>{$price_div}</td>";
echo "</tr>";


}

echo '</tbody>
</table>
</div> <!-- .table-responsive End -->';

}    

public function get_process_orders()
{
$search_term = $_GET['term'];

$sql        = " SELECT * FROM `order_invoice`
LEFT OUTER JOIN `currency` ON `order_invoice`.price_code = `currency`.`cur_symbol` AND `order_invoice`.`shippingCountry` = `currency`.`cur_country` 
WHERE `orderStatus` = ? AND `invoice_id` LIKE ? 
ORDER BY `order_invoice_pk` DESC ";

$search_val  = '%' . $search_term . '%';
$invoices    = $this->dbF->getRows($sql,array('process',$search_val));
$invoiceKey  = $this->functions->ibms_setting('invoice_key_start_with');


$invoices_array = array();
$total_rows = $this->dbF->rowCount;
for ($i=0; $i < $total_rows; $i++) { 
$invoices_array[$i]['id']    = $invoices[$i]['order_invoice_pk'];
$invoices_array[$i]['cur']   = $invoices[$i]['cur_id'];
$invoices_array[$i]['label'] = $invoiceKey . $invoices[$i]['order_invoice_pk'];
$invoices_array[$i]['value'] = $invoiceKey . $invoices[$i]['order_invoice_pk'];
}



if(array_key_exists('callback', $_GET)){

header('Content-Type: text/javascript; charset=utf8');
header('Access-Control-Allow-Origin: http://www.example.com/');
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$callback = $_GET['callback'];
echo $callback.'('.json_encode($invoices_array).');';

}else{
// normal JSON string
header('Content-Type: application/json; charset=utf8');

echo json_encode($invoices_array);
}



// echo json_encode($invoices_array);

}


public function get_order_invoice_product_info($order_product_id)
{

$sql        = " SELECT * FROM `order_invoice_product`
WHERE `order_invoice_id` = ? ";

return $this->dbF->getRow($sql,array($order_product_id));

}

public function get_color_size_total_price($new_scaleId, $new_colorId, $new_currencyId, $new_product_id, $sale_qty)
{
// # get the size price
// $scale_data  = $this->productF->scalePrice( $new_scaleId, $new_currencyId, $new_product_id );
// $scale_price = $scale_data['prosiz_price'];

// # get the color price
// $color_data  = $this->productF->colorPrice( $new_colorId, $new_currencyId, $new_product_id );
// $color_price = $color_data['proclr_price'];


// $scale_price_total   = $scale_price * $sale_qty;
// $color_price_total   = $color_price * $sale_qty;
// var_dump($scale_price_total,$color_price_total);
// $total = $scale_price + $color_price;

// return $total;

}

public function all_in_one_return_email($post_array, $data_array)
{
global $_e;

// return false;

switch ($_POST['return_type']) {

case 'refund':
$subject = $_e['Refunded'];
break;

case 'defect':
$subject = $_e['Defected'];
break;

case 'change_product':
$subject = $_e['Changed Product'];
break;

case 'change_size':
$subject = $_e['Changed Color Size'];
break;

default:

break;
}

$sql            =   " SELECT * FROM `accounts_user` WHERE CAST(acc_id AS CHAR(12)) = ? ";
$customerData   =   $this->dbF->getRow($sql,array($data_array['orderUser']));
$customerName   =   $customerData['acc_name'];
$customerEmail  =   $customerData['acc_email'];

$status     = $subject;
$email      = $customerEmail;
$name       = $customerName;
$orderId    = $data_array['order_invoice_pk'];
$invoice_id = $this->functions->ibms_setting("invoice_key_start_with").$orderId;



    $pids          = $data_array['order_pIds'];
    $pids          = explode("-",$pids);
    $pId           = $pids[0];


$returnProArr = $this->productClass->returnPro($pId);

$mailArray['returnPro'] =   $returnProArr;
$mailArray['invoiceStatus'] =   $status;
$mailArray['invoiceNumber'] =   $invoice_id;
$this->functions->send_mail($email,'','','return_Product_Update',$name,$mailArray);


}


}


?>