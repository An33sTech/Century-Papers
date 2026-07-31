<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class statistics extends object_class{
public $productF;
public $webClass;
protected $date;
protected $price_range_types;
public $date_from;
public $date_to;
public $country;
public $filter_mode;  // used to track, when searching filter is applied
public $filter_hash;  // used to save hash
public $filter_cat;   // 
public $filter_color; // 
public $filter_size;  // 



public function __construct(){

$this->price_range_types = array( '1_to_99' => 99, '100_to_199' => 199, '200_to_299' => 299, '300_to_399' => 399, '400_to_499' => 499, '500_to_599' => 599, 'above_600' => 600  );


parent::__construct('3');

if (isset($GLOBALS['webClass'])) {
$this->webClass = $GLOBALS['webClass'];
} else {
$this->functions->includeOnceCustom("_models/functions/webProduct_functions.php");
$this->webClass = new webProduct_functions();
}

if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
else {
$this->functions->require_once_custom('product_functions');
$this->productF = new product_function();
}


/**
* MultiLanguage keys Use where echo;
* define this class words and where this class will call
* and define words of file where this class will called
**/
global $_e;
global $adminPanelLanguage;
$_w=array();
//Index
$_w['Generate Statistic'] = '' ;
$_w['Statics Reports'] = '' ;
$_w['Statistics Report'] = '' ;


//This Class
$_w['SNO'] = '' ;
$_w['QTY'] = '' ;
$_w['DATE'] = '' ;
$_w['PENDING'] = '' ;
$_w['COMPLETE'] = '' ;
$_w['IN PROCESS'] = '' ;
$_w['TOTAL'] = '' ;
$_w['Daily'] = '' ;
$_w['Monthly'] = '' ;
$_w['Yearly'] = '' ;
$_w['Report By'] = '' ;
$_w['Submit'] = '' ;

// statistics.php
$_w['Date From'] = '' ;
$_w['Date To'] = '' ;
$_w['Choose category'] = '' ;
$_w['All categories'] = '' ;
$_w['Generate Statistics'] = '' ;
$_w['Choose size'] = '' ;
$_w['All sizes'] = '' ;
$_w['Choose color'] = '' ;
$_w['All colors'] = '' ;


$_w['Article Number']                           = '';
$_w['Product Name']                             = '';
$_w['Product Category']                         = '';
$_w['Price Original']                           = '';
$_w['Stock Current']                            = '';
$_w['Received from supplier pcs']               = '';
$_w['Buy in price total']                       = '';
$_w['Sold quantity total since created']        = '';
$_w['Sold quantity (last 7 days)']              = '';
$_w['Sold quantity during chosen period']       = '';
$_w['First selling date']                       = '';
$_w['Product created']                          = '';
$_w['Availability in stock']                    = '';
$_w['Average selling price (SE,NO,DA,FI)']      = '';
$_w['Returns registered']                       = '';
$_w['Payment methods']                          = '';
$_w['Total amount sold country']                = '';
$_w['Total quantity sold by country']           = '';
$_w['Buying price total']                       = '';
$_w['Discount quantity']                        = '';
$_w['Minimum sale price']                       = '';
$_w['Maximum sale price']                       = '';


$_w['Not available since']                      = '';
$_w['Available since']                          = '';
$_w['weeks']                                    = '';
$_w['Color Size Breakdown Below In Date Range'] = '';
$_w['Close']                                    = '';




$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Statistic 2016');

}


public function report_generation_form()
{

global $_e;
$form_fields = array();
$form_fields[] = array(
'name'  => 'page',
'value' => "statics",
'type'  => 'hidden',
);
$form_fields[] = array(
'label' => _uc($_e['Date From']),
'name'  => 'from',
'value' => @$_GET['from'],
'type'  => 'text',
'required'  => 'true',
'class' => 'form-control from',
);

$form_fields[] = array(
'label' => _uc($_e['Date To']),
'name'  => 'to',
'value' => @$_GET['to'],
'type'  => 'text',
'required'  => 'true',
'class' => 'form-control to',
);

$form_fields[] = array(
'label' => _uc($_e['Report By']),
'name'  => 'by',
'select' => @$_GET['by'],
'type'  => 'radio',
'value'  => array("day","month","year"),
'option'  => array($_e["Daily"],$_e["Monthly"],$_e["Yearly"]),
'required'  => 'true',
'class' => '',
'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>',
);


$form_fields[] = array(
'type'      => 'submit',
'class'     => 'btn btn-primary',
'value'     => $_e['Submit'],
);

$form_fields['form']  = array(
'name'      => "form",
'type'      => 'form',
'class'     => "form-horizontal",
'action'   => '',
'method'   => 'get',
'format'   => '<div class="form-horizontal">

<small style="color: red;font-size: large;">Leave Blank For Current Day Sales Statistics....<br><br><br>
                            </small>{{form}}</div>'


                            
);

$format = '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">{{label}}</label>
<div class="col-sm-10  col-md-9">
{{form}}
</div>
</div>';

$this->functions->print_form($form_fields,$format);

}


public function generate_statistic_report(){
global $_e;
if(isset($_POST['submit'])){


$dateCodeFrom = (isset($_POST['min']) && $_POST['min'] != '' )  ? $_POST['min'] . ' 00:00:00 '  : NULL;
$dateCodeTo   = (isset($_POST['max']) && $_POST['max'] != '' )  ? $_POST['max'] . ' 23:59:59 '  : NULL;
$category     = isset($_POST['category'])                       ? $_POST['category']            : '';
$color        = isset($_POST['color'])                          ? $_POST['color']               : '';
$size         = isset($_POST['size'])                           ? $_POST['size']                : '';


@$between_sql = (isset($dateCodeFrom) && isset($dateCodeTo) ) ? " AND `dateTime` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' " : '' ;



// if(!$this->functions->getFormToken('newBestseller')){return false;}

// $product_id   = isset($_POST['product_id'])   ? $_POST['product_id']    : 0;
// // $product_name = isset($_POST['product_name']) ? $_POST['product_name']  : 0;
// $publish      = isset($_POST['publish'])      ? $_POST['publish']  : 0;

// try{
//     $this->db->beginTransaction();

//     if($product_id == 0) {
//         throw new Exception("Error Processing Request", 1);
//     }

//     $sql      =   "INSERT INTO `best_seller_products`(
//                         `product_id`, `publish`)
//                         VALUES ( ?,? )";

//     $array   = array($product_id,$publish);
//     $this->dbF->setRow($sql,$array,false);
//     $lastId = $this->dbF->rowLastId;

//     $this->db->commit();
//     if($this->dbF->rowCount>0){
//         $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Add Successfully']),'btn-success');
//         $this->functions->setlog(_uc($_e['Added']),_uc($_e['Bestsellers']),$lastId,($_e['Bestseller Add Successfully']));
//     }else{
//         $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Add Failed']),'btn-danger');
//     }
// }catch (Exception $e){
//     // var_dump($e[2]);
//     $this->db->rollBack();
//     $this->dbF->error_submit($e);
//     $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Add Failed']),'btn-danger');
// }


} // If end

}

public function generate_form($hide_filters = false){
global $_e;

?>
<form class="form-horizontal" enctype="multipart/form-data" method="post">

<?php    

$this->functions->dataTableDateRange(true,$div_view = 2); 

?>

<?php if ($hide_filters == false): ?>


<div class="form-group">                
<label class="col-sm-2 control-label"><?php echo _uc($_e['Choose category']); ?></label>
<div class="col-sm-7">
<select class="form-control" name="category" >
<?php
$categories = $this->webClass->getCategoryArray();
echo '<option value="" selected="" >' . $_e['All categories'] . '</option>';

$cat_id = false;
if( isset($_POST['category']) && $_POST['category'] != '' ) {
$cat_id = (int) $_POST['category'];
}

foreach ($categories as $category) {
// print_r($category);
echo '<option ' . ( $cat_id == TRUE && $cat_id == $category['id'] ? ' selected="" ' :'' ) . ' value="' . $category['id'] . '">' . $category['nm'] . '</option>';
// ( $category['has-sub'] == '1' ) ? print_r($category[$category['nm']]) : '';
if ( $category['has-sub'] == '1' ) {
// echo "\n\r";
foreach ($category[$category['nm']] as $key => $value) {
echo '<option ' . ( $cat_id == TRUE && $cat_id == $value['id'] ? ' selected="" ' :'' ) . ' value="' . $value['id'] . '">&nbsp;&nbsp;&nbsp;>>>' . $value['nm'] . '</option>';
}
}
}
// var_dump($category);
?>
</select>
</div>
</div>




<div class="form-group">                
<label class="col-sm-2 control-label"><?php echo _uc($_e['Choose color']); ?></label>
<div class="col-sm-7">
<select class="form-control" name="color">
<?php
$colors = $this->productF->get_all_colors();
echo '<option value="" selected="" >' . $_e['All colors'] . '</option>';

$color_id = false;
if( isset($_POST['color']) && $_POST['color'] != '' ) {
$color_id = (int) $_POST['color'];
}

foreach ($colors as $color) {
echo '<option ' . ( $color_id == TRUE && $color_id == $color['color_id'] ? ' selected="" ' :'' ) . ' value="' . $color['color_id'] . '" >' . $color['color_name'] . '</option>';
}
?>
</select>
</div>
</div>



<div class="form-group">                
<label class="col-sm-2 control-label"><?php echo _uc($_e['Choose size']); ?></label>
<div class="col-sm-7">
<select class="form-control" name="size">
<?php
$sizes = $this->productF->get_all_sizes();

$size_id = false;
if( isset($_POST['size']) && $_POST['size'] != '' ) {
$size_id = (int) $_POST['size'];
}

echo '<option value="" selected="" >' . $_e['All sizes'] . '</option>';
foreach ($sizes as $size) {
echo '<option ' . ( $size_id == TRUE && $size_id == $size['scale_id'] ? ' selected="" ' :'' ) . ' value="' . $size['scale_id'] . '">' . $size['scale_name'] . '</option>';
}
?>
</select>
</div>
</div>


<?php endif; ?>




<div class="col-sm-2"></div>

<div class="col-sm-7" style=" margin-left: 0; padding-left: 5px; ">
<button type="submit" name="submit" value="GENERATE" class="btn btn-sm btn-primary st_generate_btn">  <?php echo $_e['Generate Statistics']; ?>  </button>
</div>








</form>

<?php 

} # end of function generate_form()

public function statistics_creation_daily()
{
$payment_method_id = 0;
$top_daily_payment_method_id = 0;
$total_orders_daily_id = 0;
$top_user_daily = 0;
$price_range_daily = 0;
$highest_product_sold_daily = 0;
$returns_registered_daily = 0;
$coupon_used_daily = 0;
$top_coupon_used_daily = 0;
$receipt_buying_daily = 0;
// @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
// $hash = md5($hashVal);


# lets make a date
// $date = '2016-11-22';
// $this->date = $date;

// if( $this->functions->getFormToken('statistics_creation_daily') ){
//     var_dump(1);
// } else {
//     var_dump(0);
// }

if( !isset($_POST['submit']) ){
return false;
}


// $this->date = '2015-09-30';
/*       $sql = "SELECT * FROM check_statistics";
$result =  $this->dbF->getRows($sql,false);
print_r($result);*/
$daterange = $this->date_variables_and_range_creation();


foreach($daterange as $date){

// echo $date->format("Y-m-d") . "<br>";
echo '<script defer="defer"> console.log("' . $date->format("Y-m-d") . '") </script>';

$this->date = $date->format("Y-m-d");
$sql = "SELECT * FROM check_statistics where updated_date = '$this->date_to'";
$result =  $this->dbF->getRow($sql,false);

//print_r($result);
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# make daily payment methods and their quantities for today
$payment_method_id = $this->make_daily_payment_method();
//echo "test";
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){  
# make the top payment method quantity for today
$top_daily_payment_method_id = $this->make_top_payment_method();
}

if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# make total order numbers by country for today
$total_orders_daily_id = $this->make_total_orders();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# make top user by most orders countrywise for today
$top_user_daily = $this->make_top_user();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# make sold products in price ranges for today
$price_range_daily = $this->make_price_ranges_for_sold_products();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# highest and lowest product total numbers sold on the day per country
$highest_product_sold_daily = $this->make_highest_lowest_product();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# total returns registered for that day
$returns_registered_daily = $this->make_returns_registered();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# total coupons used for that day
$coupon_used_daily =  $this->make_coupons_used();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
// # top coupon used and count for that day
$top_coupon_used_daily = $this->make_top_coupon();
}
if( $result["updated_date"] < $this->date &&  $this->date < date("Y-m-d") ){
# total buying price for the day
$receipt_buying_daily = $this->make_buy_price_total();
}





# split order_invoice_product info for product id,size,color etc and add in new table
$this->split_order_product_info();

# update with last date will use this to determine which date was done last
$last_date = $this->functions->ibms_setting_update('statistics_last_date', $this->date);



} # foreach end

//echo $payment_method_id;
if($payment_method_id > 0)
{
$sql = "SELECT * FROM statistics where id = '$payment_method_id' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['payment_method_id']);
}
if($top_daily_payment_method_id > 0)
{
$sql = "SELECT * FROM statistics where id = '$top_daily_payment_method_id' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['top_daily_payment_method_id']);
}
if($total_orders_daily_id > 0)
{
$sql = "SELECT * FROM statistics where id = '$total_orders_daily_id' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['total_orders_daily_id']);
}
if($top_user_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$top_user_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['top_user_daily']);
}
if($price_range_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$price_range_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['price_range_daily']);
}
if($highest_product_sold_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$highest_product_sold_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['highest_product_sold_daily']);
}
if($returns_registered_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$returns_registered_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['returns_registered_daily']);
}
if($coupon_used_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$coupon_used_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['coupon_used_daily']);
}
if($top_coupon_used_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$top_coupon_used_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['top_coupon_used_daily']);
}
if($receipt_buying_daily > 0)
{
$sql = "SELECT * FROM statistics where id = '$receipt_buying_daily' ";
$res = $this->dbF->getRow($sql,false);
//print_r($res);
$type =  $res['type'];
$shipping_country = $res["shipping_country"];
$updated_date = $res["date"];

$sql_insert = "INSERT INTO `check_statistics` (updated_date,type,shipping_country) VALUES(?,?,?)";
$set = $this->dbF->setRow($sql_insert,array($updated_date,$type,$shipping_country));
unset($_SESSION['receipt_buying_daily']);
}

}

public function table_view_print()
{
if( !isset($_POST['submit']) ){
return false;
}

global $_e;

// $this->date = '2015-09-29';
$this->date_from = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : date('Y-m-d');
$this->date_to   = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : '';

# if no date to is supplied, then use this month's last date, (this month can be the selected month, or the default current month)
if ( $this->date_to == '' ) {
$end  = new DateTime( $this->date_from );
$this->date_to = $end->format("Y-m-t");
}

// $date_obj = DateTime::createFromFormat('Y-m-d', $this->date_from);
// var_dump($month_start_date = $date_obj->format('Y-m-01'));
// var_dump($month_end_date = $date_obj->format('Y-m-t'));

$class = " tableIBMS";

$heading_1 = 'SE';
$heading_2 = 'DK';
$heading_3 = 'NO';
$heading_4 = 'FI';

echo '<div class="table-responsive">
<table class="table table-hover '.$class.'">
<thead>
<th></th>
<th>'. $heading_1 .'</th>';
echo           '<th>'. $heading_2 .'</th>
<th>'. $heading_3 .'</th>
<th>'. $heading_4 .'</th>
</thead>
<tbody>';



# making hardwords here
$payment_method_hw            = $this->dbF->hardwords('Payment methods',false);
$top_daily_payment_method_hw  = $this->dbF->hardwords('Top payment method',false);
$total_orders_daily_hw        = $this->dbF->hardwords('Total Orders',false);
$top_user_daily_hw            = $this->dbF->hardwords('Top User',false);
$price_range_daily_hw         = $this->dbF->hardwords('Product sold in price range',false);
$returns_registered_daily_hw  = $this->dbF->hardwords('Defect registration',false);
$coupon_used_daily_hw         = $this->dbF->hardwords('Coupons Used',false);
$top_coupon_used_daily_hw     = $this->dbF->hardwords('Top Coupon Used',false);
$receipt_buying_daily_hw      = $this->dbF->hardwords('Buying price',false);
$highest_product_sold_daily_hw= $this->dbF->hardwords('Most sold product',false);
$lowest_product_sold_daily_hw = $this->dbF->hardwords('Lowest sold product',false);

# setting types here to print in the table, you can change the order from here.
$types = array( 'payment_method'           => $payment_method_hw, 
'top_daily_payment_method' => $top_daily_payment_method_hw, 
'total_orders_daily'       => $total_orders_daily_hw,
'top_user_daily'           => $top_user_daily_hw, 
'price_range_daily'        => $price_range_daily_hw, 
'returns_registered_daily' => $returns_registered_daily_hw, 
'coupon_used_daily'        => $coupon_used_daily_hw, 
'top_coupon_used_daily'    => $top_coupon_used_daily_hw, 
'highest_product_sold_daily'=> $highest_product_sold_daily_hw, 
'lowest_product_sold_daily'=> $lowest_product_sold_daily_hw,
'receipt_buying_daily'     => $receipt_buying_daily_hw 
);

foreach ($types as $type => $type_text) {
$hardword = $this->dbF->hardwords($type_text,false);

$data     = $this->get_statistic_data($type);

# we are echoing tr rows for the type price_range_daily directly from its function so no need continue below
if( $type == 'price_range_daily' ) {
continue;
}




$col_1    = isset($data[$type][$heading_1]) ? $data[$type][$heading_1] : '0';
$col_2    = isset($data[$type][$heading_2]) ? $data[$type][$heading_2] : '0';
$col_3    = isset($data[$type][$heading_3]) ? $data[$type][$heading_3] : '0';
$col_4    = isset($data[$type][$heading_4]) ? $data[$type][$heading_4] : '0';


// var_dump($data);

echo " <tr>
<td>{$hardword}</td>
<td>{$col_1}</td>
<td>{$col_2}</td>
<td>{$col_3}</td>
<td>{$col_4}</td>
</tr>
";

// if ( $type == 'payment_method' ) {
//     break;
// }


}


echo '</tbody>
</table>
</div> <!-- .table-responsive End -->';
}

/**
* function makes daily payment method rows by country and payment method
*
* @return 
* @author 
**/
private function make_daily_payment_method($type = 'payment_method')
{
try {

$this->check_for_previous_insertion($type);

$result     = false;

# get the shipping countries based on the date
$sql = " SELECT DISTINCT `shippingCountry` FROM `order_invoice` WHERE dateTime BETWEEN '{$this->date} 00:00:00' AND '{$this->date} 23:23:59' ";
$shipping_countries = $this->dbF->getRows($sql,false,false);

# get the distinct payment method ids based on the date
$sql = " SELECT DISTINCT `paymentType` FROM `order_invoice` WHERE dateTime BETWEEN '{$this->date} 00:00:00' AND '{$this->date} 23:23:59' ";
$payment_methods = $this->dbF->getRows($sql,false,false);

# user in row insertion
$insert = array();
foreach ($shipping_countries as $shipping_country_row) {
$shipping_country   = $shipping_country_row['shippingCountry'];
// echo "&nbsp;&nbsp;---&nbsp;&nbsp;";
# get each payment method for the shipping country
foreach ($payment_methods as $payment_method_row) {
$payment_method = $payment_method_row['paymentType'];
// echo "&nbsp;&nbsp;---&nbsp;&nbsp;";

$sql = " SELECT COUNT(`paymentType`) as total FROM `order_invoice` WHERE dateTime BETWEEN '{$this->date} 00:00:00' AND '{$this->date} 23:23:59' AND `shippingCountry` = '{$shipping_country}' AND `paymentType` = '{$payment_method}' ";
$total_payment_method_row = $this->dbF->getRow($sql,false,false);
// echo "<br>";
if( $total_payment_method_row['total'] ) {

$insert['shipping_country']     = $shipping_country;
$insert['payment_type']         = $payment_method;
$insert['total_quantity']       = $total_payment_method_row['total'];
$insert['type']                 = 'payment_method';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['payment_method_id'] = $this->dbF->rowLastId;
}


}
// echo "<br>";


}

$result     =$_SESSION['payment_method_id'] ;


}

catch (Exception $e) {
$result     = $_SESSION['payment_method_id'];
}


return $result;

}

private function make_top_payment_method($type = 'top_daily_payment_method')
{
try {

$this->check_for_previous_insertion($type);


// SELECT payment_type as pt, shipping_country, ( SELECT SUM(total_quantity) FROM `statistics` WHERE payment_type = pt   ) as total FROM `statistics` WHERE date = ? 

$sql = "
SELECT t.*, pt.name  FROM ( SELECT * FROM `statistics` 
WHERE type = 'payment_method'
AND date = ?
ORDER BY total_quantity DESC
LIMIT 18446744073709551615
) as t
LEFT OUTER JOIN payment_types pt ON pt.id = t.payment_type
GROUP BY t.shipping_country
";

$top_payment_method_rows = $this->dbF->getRows($sql,array($this->date),false);

if( $top_payment_method_rows != false ) {

foreach ($top_payment_method_rows as $row) {

$insert['shipping_country']     = $row['shipping_country'];
$insert['payment_type']         = $row['payment_type'];
$insert['total_quantity']       = $row['total_quantity'];
$insert['name']                 = $row['name']; // payment method name
$insert['type']                 = 'top_daily_payment_method';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION["top_daily_payment_method_id"] = $this->dbF->rowLastId;

}

}

$result     = $_SESSION["top_daily_payment_method_id"];


}

catch (Exception $e) {
$result     =  $_SESSION["top_daily_payment_method_id"];
}


return $result;

}

private function make_total_orders($type = 'total_orders_daily')
{
try {

$this->check_for_previous_insertion($type);

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';

$sql = "    SELECT shippingCountry, COUNT(order_invoice_pk) as total FROM `order_invoice` 
WHERE dateTime BETWEEN ? AND ?
GROUP BY shippingCountry  ";

$total_order_rows = $this->dbF->getRows($sql,array($date_start,$date_end),false);

foreach ($total_order_rows as $row) {

$insert['shipping_country']     = $row['shippingCountry'];
$insert['total_quantity']       = $row['total'];
$insert['type']                 = 'total_orders_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['total_orders_daily_id'] =  $this->dbF->rowLastId;
}

$result     =  $_SESSION['total_orders_daily_id'];


}

catch (Exception $e) {
$result     =  $_SESSION['total_orders_daily_id'];
}


return $result;

}

private function make_top_user($type = 'top_user_daily')
{
try {

$this->check_for_previous_insertion($type);


$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';
$array      = array();

# get the shipping countries based on the date
$sql = " SELECT DISTINCT `shippingCountry` FROM `order_invoice` WHERE dateTime BETWEEN '{$date_start}' AND '{$date_end}' ";
$shipping_countries = $this->dbF->getRows($sql,$array,false);

foreach ($shipping_countries as $country_row) {
$shipping_country   = $country_row['shippingCountry'];

$sql = "    SELECT SUM(`oi`.total_price) as total_amount, COUNT(oi.`orderUser`) as total_quantity, oi.*, au.acc_id, au.acc_name FROM `order_invoice` oi
INNER JOIN `accounts_user` au ON CAST(au.acc_id as CHAR(12)) = `oi`.`orderUser`
WHERE `oi`.`dateTime` BETWEEN ? AND ? AND invoice_status = ? AND `oi`.`shippingCountry` = ?
GROUP BY oi.`orderUser`
ORDER BY total_quantity DESC
";

$row = $this->dbF->getRow($sql,array($date_start,$date_end,'3',$shipping_country),false);

// var_dump($row);
# row not found
if ( $row == false ) {
continue;
}

$insert['shipping_country']     = $shipping_country;
$insert['total_quantity']       = ( $row['total_quantity'] == '' ? 0 : $row['total_quantity'] );
$insert['total_amount']         = ( $row['total_amount']   == '' ? 0 : $row['total_amount'] );
$insert['user_id']              = ( $row['acc_id']         == '' ? 0 : $row['acc_id'] );
$insert['type']                 = 'top_user_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['top_user_daily'] = $this->dbF->rowLastId;

}


$result     = $_SESSION['top_user_daily'];


}

catch (Exception $e) {
$result     = $_SESSION['top_user_daily'];
}


return $result;

}

private function make_price_ranges_for_sold_products($type = 'price_range_daily')
{
try {

$this->check_for_previous_insertion($type);

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';



$range_types = $this->price_range_types;

foreach ($range_types as $range_key => $range) {

# seperating values from range_key, like 1_to_99 becomes 1 in first variable and 99 in second, we are using these values in the between sql for price range.
$range_first_val = preg_replace('/(\d+)_to_(\d+)/', '$1', $range_key);
$range_second_val= preg_replace('/(\d+)_to_(\d+)/', '$2', $range_key);

if ( $range_key == 'above_600' ) {
# for this range key we have to change the value for range_first_val
$range_first_val  = $range;
# range_second_val value comes from a nested sql
$range_second_val = '';
}

# making sign, for last range we are chaning the sign
$sign            = ( $range_key == 'above_600' ) ? '>' : '>';
# making conditional sql for last range, and ? placeholder for all others
$last_range_sql  = ( $range_key == 'above_600' ) ? ' ( SELECT MAX(total_price) FROM order_invoice ) ' : "{$range_second_val}";



// var_dump($range_first_val, $range_second_val);

$sql = "        
SELECT SUM(oip.order_pQty) as sold_quantity, COUNT(oi.order_invoice_pk) as orders_quantity, oi.shippingCountry, oip.* FROM `order_invoice` oi
LEFT OUTER JOIN `order_invoice_product` oip ON oi.order_invoice_pk = oip.order_invoice_id
WHERE oi.dateTime BETWEEN ? AND ? AND ( oip.order_pPrice - oip.order_discount ) {$sign} ?
AND ( oip.order_pPrice - oip.order_discount ) <= {$last_range_sql}
GROUP BY oi.shippingCountry ";

$rows = $this->dbF->getRows($sql,array($date_start,$date_end,$range_first_val),false);
// var_dump($rows);
foreach ($rows as $range_row) {

$insert['shipping_country']     = $range_row['shippingCountry'];
$insert['total_quantity']       = $range_row['sold_quantity'];
$insert['type']                 = 'price_range_daily';
$insert['price_range_type']     = $range_key;
$insert['date']                 = $this->date;
// var_dump($insert);
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['price_range_daily'] =  $this->dbF->rowLastId;

}



}

$result     = $_SESSION['price_range_daily'];


}

catch (Exception $e) {
$result     = $_SESSION['price_range_daily'];
}


return $result;

}


/**
* take `order_invoice_product` table rows 
* select column `order_pIds`
* column has information for ( pId,ScaleId,ColorId,StoreId,customId ) stored in a single column, seperated by a -
* this function selects that column and splits and saves each value in a seperate column in the new table or der_product_info
* selection is in a date range for a single day.
*
* @return bool
* @author tAken
**/
private function split_order_product_info()
{
try {

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';

# duplication check is made in sql
// $this->check_for_previous_insertion_date_for_table($this->date);


$result = false;

# SELECT order_invoice AND order_invoice_product joined rows , WHICH DO NOT EXIST IN order_product_info
$sql = "    SELECT * FROM `order_invoice` oi
LEFT OUTER JOIN `order_invoice_product` oip ON `oip`.`order_invoice_id` = `oi`.`order_invoice_pk`
WHERE `oi`.`dateTime` BETWEEN ? AND ?
AND `oip`.`invoice_product_pk` NOT IN  ( SELECT `opi`.`invoice_product_pk` FROM `order_product_info` opi WHERE `opi`.`invoice_product_pk` = `oip`.`invoice_product_pk` )   
";
$rows = $this->dbF->getRows($sql,array($date_start,$date_end),false);
if ($rows == false) {
// echo '<h1> No rows found for date: ' . $this->date . ' or rows already exist. function:' . __METHOD__ . '</h1>';
// echo '<script defer="defer"> console.log("No rows found for date: ' . $this->date . ' or rows already exist. function:' . __METHOD__ . '") </script>';
}

foreach ($rows as $row) {
$order_ids = explode('-', $row['order_pIds']); // 491-426-435-5-0 => p_ pid - scaleId - colorId - storeId;
$pid       = $order_ids[0]; // 491
$size      = $order_ids[1]; // 426
$color     = $order_ids[2]; // 435
$store     = $order_ids[3]; // 5


// $custom    = $order_ids[4]; // 0



$custom = (isset($order_ids[4]) && $order_ids[4] != '' )  ? $order_ids[4]  : '0';

// 0



$hash      = md5($pid.':'.$size.':'.$color.':'.$store);

$insert['order_invoice_id']     = $row['order_invoice_id'];
$insert['invoice_product_pk']   = $row['invoice_product_pk'];
$insert['pid']                  = $pid;
$insert['size']                 = $size;
$insert['color']                = $color;
$insert['store']                = $store;
$insert['custom']               = $custom;
$insert['hash']                 = $hash;
$insert['order_date']           = $row['dateTime'];

$row_id = $this->functions->formInsert('order_product_info',$insert);
if ( $row_id > 0 ) {
$result = true;
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
} else {
# error, break out of loop
$result = false;
// echo '<h1>Insertion failed!</h1>';
echo '<script defer="defer"> console.log("Insertion failed!") </script>';
throw new Exception("Error Processing Request", 1);
break;
}



}


}

catch (Exception $e) {
$result = false;
}


return $result;




}

public function make_highest_lowest_product()
{
$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';
// $date_end   = $this->date . ' 23:23:59';
// var_dump($date_end);

try {

### LIMIT 18446744073709551615 // IS USED TO FORCE FILESORT IN MARIADB, SEARCH FOR MORE INFO.
### ORDER BY IN A FROM SUBQUERY IS IGNORED BY MARIA DB, SO WE USE LIMIT, AND SPECIFY THE HIGHEST POSSIBLE LIMIT IN THE SUBQUERY


$result     = false;

$type = 'highest_product_sold_daily';
$this->check_for_previous_insertion($type);

$highest_sql = " SELECT * FROM (        
SELECT opi.pid as pid, COUNT(opi.pid) as total , oi.shippingCountry , oi.order_invoice_pk, opi.order_date FROM `order_invoice` oi
LEFT OUTER JOIN `order_invoice_product` oip ON `oip`.`order_invoice_id` = `oi`.`order_invoice_pk`
LEFT OUTER JOIN `order_product_info` opi ON `oip`.`invoice_product_pk` = `opi`.`invoice_product_pk`
WHERE dateTime BETWEEN ? AND ? AND opi.pid IS NOT NULL
GROUP BY opi.pid, oi.shippingCountry
ORDER BY total DESC, oi.dateTime DESC
LIMIT 18446744073709551615
) as t

GROUP BY t.shippingCountry";
$highest_rows = $this->dbF->getRows($highest_sql,array($date_start,$date_end),false);

if ( count($highest_rows) > 0 ) {

foreach ($highest_rows as $row) {

if ( $row['total'] == '0' ) {
continue;
}

$insert['shipping_country']     = $row['shippingCountry'];
$insert['total_quantity']       = $row['total'];
$insert['id_to_save']           = $row['pid'];
$insert['type']                 = 'highest_product_sold_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['highest_product_sold_daily'] = $this->dbF->rowLastId;
}

$result = $_SESSION['highest_product_sold_daily'];


}


$type = 'lowest_product_sold_daily';
$this->check_for_previous_insertion($type);

$lowest_sql = " SELECT * FROM (        
SELECT opi.pid, COUNT(opi.pid) as total , oi.shippingCountry , oi.order_invoice_pk, opi.order_date FROM `order_invoice` oi
LEFT OUTER JOIN `order_invoice_product` oip ON `oip`.`order_invoice_id` = `oi`.`order_invoice_pk`
LEFT OUTER JOIN `order_product_info` opi ON `oip`.`invoice_product_pk` = `opi`.`invoice_product_pk`
WHERE dateTime BETWEEN ? AND ? AND opi.pid IS NOT NULL
GROUP BY opi.pid, oi.shippingCountry
ORDER BY total ASC, oi.dateTime DESC
LIMIT 18446744073709551615
) as t 
GROUP BY t.shippingCountry";

$lowest_rows = $this->dbF->getRows($lowest_sql,array($date_start,$date_end),false);
if ( count($lowest_rows) > 0 ) {

foreach ($lowest_rows as $row) {

if ( $row['total'] == '0' ) {
continue;
}

$insert['shipping_country']     = $row['shippingCountry'];
$insert['total_quantity']       = $row['total'];
$insert['id_to_save']           = $row['pid'];
$insert['type']                 = 'lowest_product_sold_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['highest_product_sold_daily'] = $this->dbF->rowLastId;
}

$result =  $_SESSION['highest_product_sold_daily'];


}
// var_dump($highest_rows, $lowest_rows);

// $languages = unserialize($this->functions->ibms_setting('Languages'));
// $lang_count = count($languages);

// $top_array = array_slice($rows, 0);
// $low_array = array_slice($rows, (0 + $lang_count));

// var_dump(count($rows), $top_array, $low_array);


}

catch (Exception $e) {
$result     =  $_SESSION['highest_product_sold_daily'];
}

return $result;

}



private function make_buy_price_total($type = 'receipt_buying_daily') 
{
try {

$this->check_for_previous_insertion($type);

// $date   = $this->date;
// $date   = '2016-11-01';

$result = false;
$_SESSION['receipt_buying_daily'] = 0;

$sql = "SELECT SUM(t.receipt_price) as total_amount, SUM(t.receipt_qty) as total_quantity, t.receipt_date FROM (
SELECT * FROM `purchase_receipt` pr
LEFT OUTER JOIN `purchase_receipt_pro` prp ON prp.receipt_id = pr.receipt_pk
WHERE pr.receipt_date = ?
) as t";

$rows = $this->dbF->getRows($sql,array($this->date),false);


if ( $rows != false ) {

foreach ($rows as $row) {

if ( $row['total_amount'] == '0' || $row['total_amount'] == null ) {
continue;
}

$insert['total_amount']         = $row['total_amount'];
$insert['total_quantity']       = $row['total_quantity']; // IN SEK
$insert['type']                 = 'receipt_buying_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';

}
if($this->dbF->rowLastId > 0)
$_SESSION['receipt_buying_daily'] = $this->dbF->rowLastId;
else
$_SESSION['receipt_buying_daily'] = false; 
}
if($_SESSION['receipt_buying_daily'] > 0)
$result = $_SESSION['receipt_buying_daily'];


}

}

catch (Exception $e) {
$result     = $_SESSION['receipt_buying_daily'];
}


return $result;




}

# to remember
# refund = 2,defect=3,change_product=4,change_size_color=5
private function make_returns_registered($type = 'returns_registered_daily')
{
try {

// old sql
// SELECT COUNT(id) as total FROM `all_in_one_returns`
// WHERE datetime_added BETWEEN ? AND ? AND ( type = '2' OR type = '3' )

$this->check_for_previous_insertion($type);

$result     = false;

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';

$sql = " 
SELECT COUNT(id) as total, oi.shippingCountry FROM `all_in_one_returns` aio
LEFT OUTER JOIN `order_invoice` oi ON oi.`order_invoice_pk` = aio.`order_invoice_id`
WHERE aio.datetime_added BETWEEN ? AND ? AND ( aio.type = '2' OR aio.type = '3' ) AND oi.shippingCountry IS NOT NULL
GROUP BY oi.shippingCountry ";

$rows = $this->dbF->getRows($sql,array($date_start,$date_end),false);

if ( $rows != false ) {

foreach ($rows as $row) {

if ( $row['total'] == '0' ) {
continue;
}

$insert['shipping_country']     = $row['shippingCountry'];
$insert['total_quantity']       = $row['total'];
$insert['type']                 = 'returns_registered_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['returns_registered_daily'] =  $this->dbF->rowLastId;
}

$result = $_SESSION['returns_registered_daily'];


}


}

catch (Exception $e) {
$result     = $_SESSION['returns_registered_daily'];
}

return $result;


}


private function make_coupons_used($type = 'coupon_used_daily')
{
try {

$this->check_for_previous_insertion($type);

$result     = false;

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';


$countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");

foreach ($countries_currency as $country) {
$country = $country['cur_country'];

$sql = " SELECT COUNT(t.order_id) as total, t.shippingCountry FROM (
SELECT * FROM `order_invoice_record` oir
LEFT OUTER JOIN `order_invoice` oi ON `oi`.`order_invoice_pk` = `oir`.`order_id`
WHERE `oi`.`shippingCountry` = ? AND  oir.setting_name = 'coupon' AND oir.rec_dateTime BETWEEN ? AND ?
GROUP BY oir.order_id
) as t ";

$row = $this->dbF->getRow($sql,array($country,$date_start,$date_end),false);

# if there is a row and $row['total'] is not zero
if ( count($row) > 0 && $row['total'] != '0' && $row != false  ) {


if ( $row['total'] == '0' ) {
continue;
}

$insert['shipping_country']     = $row['shippingCountry'];
$insert['total_quantity']       = $row['total'];
$insert['type']                 = 'coupon_used_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
}
$_SESSION['coupon_used_daily'] = $this->dbF->rowLastId;
$result = $_SESSION['coupon_used_daily'];

}

}


}

catch (Exception $e) {
$result     = $_SESSION['coupon_used_daily'];
}

return $result;


}


private function make_top_coupon($type = 'top_coupon_used_daily')
{
try {

$this->check_for_previous_insertion($type);

$result     = false;

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';

// $date_start = '2015-12-14 00:00:00';
// $date_end   = '2016-02-30 23:23:59';


$countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");

foreach ($countries_currency as $country) {
$country = $country['cur_country'];

$sql = " SELECT COUNT(t.order_id) as total, t.setting_val as coupon_name, t.shippingCountry FROM (
SELECT * FROM `order_invoice_record` oir
LEFT OUTER JOIN `order_invoice` oi ON `oi`.`order_invoice_pk` = `oir`.`order_id`
WHERE `oi`.`shippingCountry` = ? AND oir.setting_name = 'coupon' AND rec_dateTime BETWEEN ? AND ?
GROUP BY oir.order_id
) as t 
GROUP BY t.setting_val, t.shippingCountry
ORDER BY total DESC ";

$row = $this->dbF->getRow($sql,array($country,$date_start,$date_end),false);
# if there is a row and $row['total'] is not zero
if($row == null){

}
else if( count($row) > 0 && $row['total'] != '0' && $row != false  ) {

$insert['shipping_country']     = $row['shippingCountry'];
$insert['total_quantity']       = $row['total'];
$insert['name']                 = $row['coupon_name'];
$insert['type']                 = 'top_coupon_used_daily';
$insert['date']                 = $this->date;
if ( $this->insert_statistic_row($insert) ) {
### insertion successfull
// echo '<h1> Insertion successfull, row id is: ' . $this->dbF->rowLastId . '</h1>';
echo '<script defer="defer"> console.log("Insertion successfull, row id is: ' . $this->dbF->rowLastId . '") </script>';
}
$_SESSION['top_coupon_used_daily'] = $this->dbF->rowLastId;

$result = $_SESSION['top_coupon_used_daily'];

}



}




}

catch (Exception $e) {
$result     = $_SESSION['top_coupon_used_daily'];
}

return $result;


}

private function check_for_previous_insertion($type)
{

$sql = " SELECT COUNT(id) as count FROM `statistics` WHERE `date` = ? AND type = ? ";
$row = $this->dbF->getRow($sql,array($this->date,$type),false);


# if row/s are found for this date and this type then halt this process
if ($row['count'] > 0) {
// echo '<h1> Rows already exist on date: ' . $this->date . ' for type: ' . $type . '</h1>';
echo '<script defer="defer"> console.log("Rows already exist on date: ' . $this->date . ' for type: ' . $type . '") </script>';
throw new Exception("Error Processing Request", 1);
}

}

private function check_for_previous_insertion_date_for_table($date)
{

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';

$sql = " SELECT COUNT(id) as count FROM `order_product_info` WHERE `order_date` BETWEEN ? AND ? ";
$row = $this->dbF->getRow($sql,array($date_start,$date_end),false);


# if row/s are found for this date and this type then halt this process
if ($row['count'] > 0) {
// echo '<h1> Rows already exist on date: ' . $this->date . ' for the table! </h1>';
echo '<script defer="defer"> console.log("Rows already exist on date: ' . $this->date . ' for the table!") </script>';
throw new Exception("Error Processing Request", 1);
}

}



private function insert_statistic_row($single_row_array)
{
$result = false;
if ( $single_row_array == array() ) {

} else {

// var_dump($single_row_array);
// $sql    = " INSERT INTO `statistics` ( `col_1`, `col_2`, `col_3`, `type`, `date` ) VALUES ( ?, ?, ?, ?, ? ) ";
// $row_id = $this->dbF->setRow($sql,$single_row_array);
$row_id = $this->functions->formInsert('statistics',$single_row_array);
if ( $row_id > 0 ) {
$result = true;
}

}

return $result;

}

private function get_statistic_data($type)
{
switch ($type) {
case 'payment_method':
$result = $this->statistic_for_payment_method('payment_method');
break;

case 'top_daily_payment_method':
$result = $this->statistic_for_top_daily_payment_method('top_daily_payment_method');
break;

case 'total_orders_daily':
$result = $this->statistic_for_total_orders_daily('total_orders_daily');
break;

case 'top_user_daily':
$result = $this->statistic_for_top_user_daily('top_user_daily');
break;

case 'price_range_daily':
$result = $this->statistic_for_price_range_daily('price_range_daily');
break;

case 'returns_registered_daily':
$result = $this->statistic_for_returns_registered_daily('returns_registered_daily');
break;

case 'coupon_used_daily':
$result = $this->statistic_for_coupon_used_daily('coupon_used_daily');
break;

case 'top_coupon_used_daily':
$result = $this->statistic_for_top_coupon_used_daily('top_coupon_used_daily');
break;

case 'receipt_buying_daily':
$result = $this->statistic_for_receipt_buying_daily('receipt_buying_daily');
break;

case 'highest_product_sold_daily':
$result = $this->statistic_for_highest_lowest_product_sold_daily('highest_product_sold_daily');
break;

case 'lowest_product_sold_daily':
$result = $this->statistic_for_highest_lowest_product_sold_daily('lowest_product_sold_daily');
break;

default:
$result = FALSE;
break;
}


return $result;



}

public function statistic_for_payment_method($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

 $sql = " SELECT SUM(total_quantity) as total, s.*, pt.* FROM `statistics` s
LEFT OUTER JOIN `payment_types` pt ON pt.id = s.payment_type
WHERE s.type = ? AND s.date BETWEEN ? AND ?
GROUP BY s.shipping_country, s.payment_type ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {
# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['name'] . ' (' . $row['total'] . ') <br>';

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_top_daily_payment_method($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

// $sql = " SELECT SUM(total_quantity) as total, s.*, pt.* FROM `statistics` s
//          LEFT OUTER JOIN `payment_types` pt ON pt.id = s.payment_type
//          WHERE s.type = ? AND s.date BETWEEN ? AND ? 
//          GROUP BY shipping_country";


// SELECT SUM(total_quantity) as total, s.*, pt.* FROM `statistics` s
// LEFT OUTER JOIN `payment_types` pt ON pt.id = s.payment_type
// WHERE s.type = ? AND s.date BETWEEN ? AND ? 
// GROUP BY shipping_country, payment_type

$sql = "
SELECT * FROM ( 
SELECT * FROM `statistics` 
WHERE date BETWEEN ? AND ? AND type = ?
ORDER BY total_quantity DESC
LIMIT 18446744073709551615
) as t
GROUP BY t.shipping_country
";

$rows = $this->dbF->getRows($sql,array($this->date_from,$this->date_to,$type),false);

foreach ($rows as $row) {
# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['name'] . ' (' . $row['total_quantity'] . ') <br>';

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_total_orders_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$sql = " 
SELECT SUM(s.total_quantity) as total, s.* FROM `statistics` s
WHERE s.type = ? AND s.date BETWEEN ? AND ?
GROUP BY s.shipping_country ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['total'];

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_top_user_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$sql = " 
SELECT * FROM (
SELECT au.acc_name, au.acc_id, au.acc_email, s.* FROM `statistics` s
LEFT OUTER JOIN `accounts_user` au ON au.acc_id = s.user_id
WHERE s.type = ? AND s.date BETWEEN ? AND ?
ORDER BY s.total_amount DESC
LIMIT 18446744073709551615
) as t
GROUP BY t.shipping_country ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['acc_name'] . ' (' . $row['total_amount'] . ')';

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_price_range_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$column_first_hw = $this->dbF->hardwords('Product sold in price range', false);

$range_types = $this->price_range_types;

// $price_range_daily_hw = $this->dbF->hardwords('Product sold in price range',false);


foreach ($range_types as $range_key => $range) {

// var_dump($range);


$sql = " 
SELECT SUM(s.total_quantity) as total, s.* FROM `statistics` s
WHERE s.type = ? AND s.price_range_type = ? AND s.date BETWEEN ? AND ?
GROUP BY s.shipping_country
";

$rows = $this->dbF->getRows($sql,array($type,$range_key,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = null;
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['total'];

}


$heading_1 = 'SE';
$heading_2 = 'DK';
$heading_3 = 'NO';
$heading_4 = 'FI';

$col_1    = isset($result_array[$type][$heading_1]) ? $result_array[$type][$heading_1] : '0';
$col_2    = isset($result_array[$type][$heading_2]) ? $result_array[$type][$heading_2] : '0';
$col_3    = isset($result_array[$type][$heading_3]) ? $result_array[$type][$heading_3] : '0';
$col_4    = isset($result_array[$type][$heading_4]) ? $result_array[$type][$heading_4] : '0';

// var_dump($col_1);


echo " <tr>
<td>{$column_first_hw}({$range_key})</td>
<td>{$col_1}</td>
<td>{$col_2}</td>
<td>{$col_3}</td>
<td>{$col_4}</td>
</tr>
";

# clear the array, because otherwise values get appended on next cycle
$result_array[$type] = null;

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_returns_registered_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$sql = " SELECT SUM(s.total_quantity) as total, s.* FROM `statistics` s
WHERE s.type = ? AND s.date BETWEEN ? AND ?
GROUP BY s.shipping_country ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['total'];

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_coupon_used_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$sql = " SELECT SUM(s.total_quantity) as total, s.* FROM `statistics` s
WHERE s.type = ? AND s.date BETWEEN ? AND ?
GROUP BY s.shipping_country ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['total'];

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_top_coupon_used_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$sql = " SELECT SUM(s.total_quantity) as total, s.* FROM `statistics` s
WHERE s.type = ? AND s.date BETWEEN ? AND ?
GROUP BY s.shipping_country ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
# save values in country array
$result_array[$type][$row['shipping_country']] .= $row['name'] . '(' .  $row['total'] . ')';

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

/**
* By price is same for all currencies, so we select only SUM(total_amount) and display it in all columns.
*
* @return void
* @author 
**/
public function statistic_for_receipt_buying_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$sql = " SELECT SUM(s.total_amount) as total, s.* FROM `statistics` s
WHERE s.type = ? AND s.date BETWEEN ? AND ? ";

$row = $this->dbF->getRow($sql,array($type,$this->date_from,$this->date_to),false);

$countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");

foreach ($countries_currency as $country) {
$country = $country['cur_country'];

# initialize the country key
if ( !isset($result_array[$type][$country]) ) {
// var_dump($row);
$result_array[$type][$country] = '';
}
$result_array[$type][$country] .= $row['total'];

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}

public function statistic_for_highest_lowest_product_sold_daily($type)
{
$result  = false;

// $date    = $this->date;
// $date    = '2015-09-29';

$result_array = array();            

try {

$order_by_sql = ( $type == 'highest_product_sold_daily' ) ? ' DESC ' : ' ASC ';

$sql = "
SELECT * FROM (
SELECT * FROM `statistics` s
LEFT OUTER JOIN proudct_detail ON proudct_detail.prodet_id = s.id_to_save
WHERE s.type = ? AND s.date BETWEEN ? AND ?
ORDER BY s.total_quantity {$order_by_sql}
LIMIT 18446744073709551615
) as t
GROUP BY t.shipping_country ";

$rows = $this->dbF->getRows($sql,array($type,$this->date_from,$this->date_to),false);

foreach ($rows as $row) {

# initialize the country key
if ( !isset($result_array[$type][$row['shipping_country']]) ) {
// var_dump($row);
$result_array[$type][$row['shipping_country']] = '';
}
$product_name = $this->functions->unserializeTranslate($row['prodet_name']);
# save values in country array
$result_array[$type][$row['shipping_country']] .= $product_name . '(' .  $row['total_quantity'] . ')';

}

$result = $result_array;

} catch (Exception $e) {
$result = false;
}


return $result;

}


# misc
public function date_variables_and_range_creation()
{

$this->date_from = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : date('Y-m-d');
$this->date_to   = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : date('Y-m-d');

$begin           = new DateTime( $this->date_from ); // '2015-10-01'
$end             = new DateTime( $this->date_to ); //'2015-10-15'
$end             = $end->modify( '+1 day' ); # include the end date using the DateTime method 'modify', for date 1 - 31 or 1 - 15, last date is not included if we don't specify this +1 day in modify method

$interval        = new DateInterval('P1D');
$daterange       = new DatePeriod($begin, $interval ,$end);

return $daterange;

}
#misc end

### list report section start


public function create_list_report()
{
if( !isset($_POST['submit']) ){
return false;
}


# start date range loop
$daterange = $this->date_variables_and_range_creation();
$i = 1; // restricting to 7 runs only, if no max date then current date is used, so currently restricting to 7 runs.
foreach($daterange as $date){

// if( $i == 7 ) {
//     break;
// }

// echo $date->format("Y-m-d") . "<br>";
echo '<script defer="defer"> console.log("' . $date->format("Y-m-d") . '") </script>';
$this->date = $date->format("Y-m-d");

# insertion for order product info table
$this->split_order_product_info();

$searched_rows = $this->searching_controller();


# iterate over rows
foreach ($searched_rows as $row) {
// @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
// $hash = md5($hashVal);
# country loop

$this->insert_or_update_product_row($row);

$this->update_product_row_status($row);

}


// (DISCONTINUED) # filtered searching
// $this->search_based_cat_color_size_row_creation();
$i++;
}


}

public function generate_list_report()
{
if( !isset($_POST['submit']) ){
return false;
}

global $_e;

$this->date_from = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : date('Y-m-d');
$this->date_to   = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : '';

# if no date to is supplied, then use this month's last date, (this month can be the selected month, or the default current month)
if ( $this->date_to == '' ) {
$end  = new DateTime( $this->date_from );
$this->date_to = $end->format("Y-m-t");
}

// var_dump($this->date_from, $this->date_to);

$searched_rows = $this->list_search_rows();
//$this->dbF->prnt($searched_rows);
// var_dump($searched_rows);

echo $this->list_rows_print($searched_rows);


# print voucher link in bootstrap modal
echo $this->functions->blankModal($_e['Color Size Breakdown Below In Date Range'], 'product_size_color_details', '', $_e['Close'], '', false, false);


}

/**
* function to search list view rows.
*
* @return array
* @author tAken
**/
private function list_search_rows()
{

$type = 'product_row_daily';
$values_array = array($this->date_from,$this->date_to,$type);

$cat = $color = $size = $cat_sql = $color_sql = $size_sql = $cat_join_sql = '';

# if filter is applied then
if ( (isset($_POST['category']) && $_POST['category'] != '') 
|| ( isset($_POST['color']) && $_POST['color'] != '' ) 
|| ( isset($_POST['size'])  && $_POST['size']  != '' ) 
) {

// $type = 'product_row_filtered_daily';


if ( (isset($_POST['category']) && $_POST['category'] != '') ) {
$cat_sql    = ' AND prc.procat_cat_id LIKE ? ';
$to_search  = '%' . $_POST['category'] . '%';
array_push($values_array, $to_search);
$cat        = $this->filter_cat      = $_POST['category'];

$cat_join_sql = ' LEFT OUTER JOIN `product_category` prc ON prc.procat_prodet_id = st.id_to_save ';
}

if ( ( isset($_POST['color']) && $_POST['color'] != '' ) ) {
// $color_sql  = ' AND st.color LIKE ? '; // OLD
$color_sql  = ' AND st.color IN ( SELECT propri_id FROM `product_color` WHERE proclr_name IN 
( SELECT color_name FROM `colors` WHERE color_id = ? ) 
AND sizeGroup IN ( SELECT color_name_id FROM `colors` WHERE color_id = ? )   ) ';
array_push($values_array, $_POST['color'], $_POST['color']);
$color      = $this->filter_color  = $_POST['color'];
}

if ( ( isset($_POST['size'])  && $_POST['size']  != '' ) ) {
// $size_sql   = ' AND st.size LIKE ? '; // OLD
$size_sql   = ' AND st.size IN ( SELECT prosiz_id FROM `product_size` WHERE prosiz_name IN 
( SELECT scale_name FROM `scales` WHERE scale_id = ?  ) 
AND sizeGroup IN ( SELECT scale_name_id FROM `scales` WHERE scale_id = ? ) ) ';

array_push($values_array, $_POST['size'], $_POST['size']);
$size       = $this->filter_size    = $_POST['size'];

}




} else {
# no filter applied
// $type = 'product_row_daily';
}

$result = array();

// $sql  = " SELECT * FROM `statistics`
//           WHERE `date` BETWEEN ? AND ? AND `type` = ? 
//           GROUP BY `id_to_save` ";

$sql  = " SELECT pc.proclr_name as color_name, ps.prosiz_name as size_name , st.* FROM `statistics` st
LEFT OUTER JOIN `product_size`  ps ON ps.prosiz_id = st.size
LEFT OUTER JOIN `product_color` pc ON pc.propri_id = st.color
{$cat_join_sql}
WHERE `date` BETWEEN ? AND ? AND `type` = ? {$cat_sql} {$color_sql} {$size_sql}
GROUP BY st.size, st.color
";

//  print_r($values_array);
// echo "<br>".$sql;

// die;
// // $rows = $this->dbF->getRows($sql,array($this->date_from,$this->date_to,$type));
$rows = $this->dbF->getRows($sql,$values_array);

if ( $this->dbF->rowCount > 0 ) {
$result = $rows;
}

return $result;
}

/**
* function to print list view rows.
*
* @return array
* @author tAken
**/
private function list_rows_print($searched_rows)
{
global $_e;

$html  = '';

$class = " dTableFull tableIBMS"; // table-wrapper

$html  .= '<div class=" table-wrapper">
<table class="table table-hover '.$class.'">
<thead>
<th class="first_th"><br>'. $_e['Product Name'] .'</th>
<th>'. $_e['Article Number'] .'</th>
<th>'. $_e['Product Category'] .'</th>
<th>'. $_e['Price Original'] .'</th>
<th>'. $_e['Stock Current'] .'</th>
<th>'. $_e['Received from supplier pcs'] .'</th>
<th>'. $_e['Buy in price total'] .'</th>
<th>'. $_e['Sold quantity total since created'] .'</th>
<th>'. $_e['Sold quantity (last 7 days)'] .'</th>
<th>'. $_e['Sold quantity during chosen period'] .'</th>
<th>'. $_e['First selling date'] .'</th>
<th>'. $_e['Product created'] .'</th>
<th>'. $_e['Availability in stock'] .'</th>
<th>'. $_e['Average selling price (SE,NO,DA,FI)'] .'</th>
<th>'. $_e['Returns registered'] .'</th>
<th>'. $_e['Payment methods'] .'</th>
<th>'. $_e['Total amount sold country'] .'</th>
<th>'. $_e['Total quantity sold by country'] .'</th>
<th>'. $_e['Buying price total'] .'</th>
<th>'. $_e['Discount quantity'] .'</th>
<th>'. $_e['Minimum sale price'] .'</th>
<th>'. $_e['Maximum sale price'] .'</th>
</thead>
<tbody>';


foreach ($searched_rows as $row) {

$pid                 = $row['id_to_save'];
$product             = $this->productF->get_product($pid);
$product_name        = $this->functions->unserializeTranslate($product['prodet_name']);

$color_name          = ( $row['color_name'] == '' ) ? '' : '<div title="' . $row['color_name'] . '" class="color_div" style="background-color: #' . $row['color_name'] . '"></div>';
$size_name           = ( $row['size_name'] == '' )  ? '' : ' (' . $row['size_name'] . ')';

$product_name        = $product_name . $color_name . $size_name;


$pSetting            = $this->productF->getProductSetting($pid);
$model               = $this->productF->productSettingArray('Model', ( ( $pSetting == false ) ? array() : $pSetting ), $pid);

$cat_names           = '';
$category            = $this->productF->productCategory($pid,false);
$category_array      = explode(',', $category);
foreach ($category_array as $cat_id) {
$cat_names .= '(' . $this->webClass->getCategoryName($cat_id) . ')<br>';
}
$cat_names = rtrim($cat_names, '<br>');


$currCountry         = $this->functions->ibms_setting('Default Web_Price_Country');
$currData            = $this->productF->currencyInfo($currCountry);
$currency_name       = $currData['cur_name'];
$product_price_row   = $this->productF->productPrice($pid);
$product_price       = $product_price_row['propri_price'] . ' ' . $currency_name;

// $stock_qty           = $this->productF->productQTY($pid);
$stock_qty           = $this->productF->get_product_inventory_by_hash($row['hash']);

$receipt_sum_data    = $this->purchase_receipt_sum_data($pid,$row['hash']);

$total_receipt_qty   = ( $receipt_sum_data['total_qty'] == NULL ) ? 'N/A' : $receipt_sum_data['total_qty'];

$buy_in_price_total  = ( $receipt_sum_data['total_buying_price'] == NULL ) ? 'N/A' : $receipt_sum_data['total_buying_price'];

$sold_quantity_total = $this->st_sold_quantity_total($pid,$row['hash']);

$sold_last_seven_day = $this->st_sold_quantity_range($pid,$row['hash']);

$st_sold_discount    = $this->st_sold_quantity_discount_total_range($pid,$row['hash']);

$sold_quantity_range = $st_sold_discount['total'];

$first_sold_date     = $this->st_first_sale_date($pid,$row['hash']);

$product_created     = $product['prodet_addOn'];

$available_in_stock  = $this->stock_last_update($stock_qty);

$avg_min_max_data    = $this->st_product_avg_min_max($pid,$row['hash']);

$avg_sale_price      = $avg_min_max_data['avg'];

$returns_qty         = $this->get_all_returns($pid,$row['hash']);
$returns_qty         = ( $returns_qty == '' ) ? 0 : $returns_qty;

$payment_methods     = $this->st_product_payment_methods($pid,$row['hash']);

$payment_methods_data= $this->st_product_payment_method_data($pid,$row['hash']);
$total_amount_country= $payment_methods_data['total_amount_country'];
$total_qty_country   = $payment_methods_data['total_quantity_country'];

$buy_price_total     = ( $receipt_sum_data['total_buying_price'] == NULL ) ? 'N/A' : $receipt_sum_data['total_buying_price'];

$discount_qty        = $st_sold_discount['discount_total_qty'];

$min_sale_price      = $avg_min_max_data['min'];

$max_sale_price      = $avg_min_max_data['max'];

$html  .= " <tr>
<th data-id='" . $pid . "' class='product_name' title='{$product_name}' >{$product_name}</th>
<td>{$model}</td>
<td>{$cat_names}</td>
<td>{$product_price}</td>
<td>{$stock_qty}</td>
<td>{$total_receipt_qty}</td>
<td>{$buy_in_price_total}</td>
<td>{$sold_quantity_total}</td>
<td>{$sold_last_seven_day}</td>
<td>{$sold_quantity_range}</td>
<td>{$first_sold_date}</td>
<td>{$product_created}</td>
<td>{$available_in_stock}</td>
<td>{$avg_sale_price}</td>
<td>{$returns_qty}</td>
<td>{$payment_methods}</td>
<td>{$total_amount_country}</td>
<td>{$total_qty_country}</td>
<td>{$buy_price_total}</td>
<td>{$discount_qty}</td>
<td>{$min_sale_price}</td>
<td>{$max_sale_price}</td>
</tr>
";            
}



$html  .= '</tbody>
</table>
</div> <!-- .table-responsive End -->';

return $html;
}

private function purchase_receipt_sum_data($pid,$hash){
## // WHERE receipt_product_id = ?   $pid
$sql = " SELECT SUM(prp.receipt_qty) as total_qty, AVG(prp.receipt_price) as average_buying_price, SUM(prp.receipt_price) as total_buying_price FROM `purchase_receipt_pro` prp
LEFT OUTER JOIN `purchase_receipt` pr ON pr.receipt_pk = prp.receipt_id
WHERE prp.receipt_hash = ? AND pr.receipt_date BETWEEN ? AND ? ";
$row = $this->dbF->getRow($sql,array($hash,$this->date_from,$this->date_to));

return $row;
}

private function st_sold_quantity_total($pid,$hash)
{
// id_to_save = ? AND 
// $my_hash = ($hash);
/*print_r($hash);
echo "<br>".$p;
die;*/
$sql = " SELECT SUM(`total_quantity`) as total FROM `statistics` 
WHERE type = ? AND hash = ? ";
$row = $this->dbF->getRow($sql,array('product_row_daily',$hash));

return $row['total'];        
}

private function st_sold_quantity_range($pid,$hash)
{

$one_day_minus = new DateTime( $this->date_from );
$one_day_minus = $one_day_minus->modify( '-1 day' ); 
$one_day_minus_date = $one_day_minus->format("Y-m-d");

$from_date = new DateTime( $this->date_from );
$from_date = $from_date->modify( '-7 day' ); # include the end date using the DateTime method 'modify', for date 1 - 31 or 1 - 15, last date is not included if we don't specify this +1 day in modify method
$last_seven_days_date_start = $from_date->format("Y-m-d");

# check the dates, I had to play with the modify method to get the 7 days, once -6 worked and once -7 worked
// var_dump($last_seven_days_date_start,$one_day_minus_date);

## // id_to_save = ? AND 
$sql = " SELECT SUM(`total_quantity`) as total FROM `statistics` 
WHERE type = ? AND `date` BETWEEN ? AND ? AND hash = ?  ";
$row = $this->dbF->getRow($sql,array('product_row_daily',$last_seven_days_date_start,$one_day_minus_date,$hash));

if( $row['total'] == 0 ) {
$row['total'] = 0;
}

return $row['total'];        
}


private function st_sold_quantity_discount_total_range($pid,$hash)
{
## // id_to_save = ? AND 
$sql = " SELECT SUM(`total_quantity`) as total, SUM(`count`) as discount_total_qty FROM `statistics` 
WHERE type = ? AND `date` BETWEEN ? AND ? AND hash = ? ";
$row = $this->dbF->getRow($sql,array('product_row_daily',$this->date_from,$this->date_to,$hash));

return $row;
}


private function get_all_returns($pid,$hash)
{
// ### old_pId
$sql = " SELECT SUM(sale_qty) as total FROM `all_in_one_returns` WHERE hash = ? AND ( type = ? OR type = ? ) AND `date` BETWEEN ? AND ?  ";
$row = $this->dbF->getRow($sql,array($hash,'2','3',$this->date_from,$this->date_to));

return $row['total'];
}


private function st_first_sale_date($pid,$hash)
{
//## id_to_save = ?
$sql = " SELECT MIN(date) as first_selling_date FROM `statistics` WHERE hash = ? AND type = ? ";
$row = $this->dbF->getRow($sql,array($hash,'product_row_daily'));

return $row['first_selling_date'];
}


private function st_product_avg_min_max($pid,$hash)
{

$text_array = array();    
$text_avg   = $text_min = $text_max = '';    
$countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");

# make avg by country
foreach ($countries_currency as $country) {
$country = $country['cur_country'];

// ## id_to_save
$sql  = " SELECT FLOOR(SUM(avg)) as avg, min, max FROM `statistics` WHERE hash = ? AND type = ? AND `date` BETWEEN ? AND ? AND shipping_country = ? ";
$row  = $this->dbF->getRow($sql,array($hash,'product_row_daily',$this->date_from,$this->date_to,$country));

if ($row['avg'] == '') {
continue;
}

$text_avg .= $row['avg'] . '&nbsp;' . $country . ',<br>';
$text_min .= $row['min'] . '&nbsp;' . $country . ',<br>';
$text_max .= $row['max'] . '&nbsp;' . $country . ',<br>';

}


$text_array['avg'] = rtrim($text_avg, ',<br>');
$text_array['min'] = rtrim($text_min, ',<br>');
$text_array['max'] = rtrim($text_max, ',<br>');


return $text_array;

}


private function st_product_payment_methods($pid,$hash)
{

// ### id_to_save

$text = '';    
$countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");

$sql  = "   SELECT pt.name, SUM(st.total_quantity) as total FROM `statistics` st 
LEFT OUTER JOIN payment_types pt ON pt.id = st.payment_type
WHERE st.type = ? AND st.hash = ? AND `date` BETWEEN ? AND ?
GROUP BY st.payment_type
";
$rows = $this->dbF->getRows($sql,array('product_row_payment_daily',$hash,$this->date_from,$this->date_to));

foreach ($rows as $row) {

$text .= _uc($row['name']) . '&nbsp;(' . $row['total'] . ') ';

}

return $text;
}


private function st_product_payment_method_data($pid,$hash)
{

// ### id_to_save

$text_array = array();    
$text = '';
$countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");

$sql  = "   SELECT pt.name, SUM(st.total_quantity) as total_quantity, SUM(st.total_amount) as total_amount, st.shipping_country FROM `statistics` st 
LEFT OUTER JOIN payment_types pt ON pt.id = st.payment_type
WHERE st.type = ? AND st.hash = ? AND `date` BETWEEN ? AND ?
GROUP BY shipping_country ";
$rows = $this->dbF->getRows($sql,array('product_row_daily',$hash,$this->date_from,$this->date_to));

# make total amount by country
foreach ($rows as $row) {

$text .= $row['total_amount'] . '&nbsp;' . $row['shipping_country'] . ',<br>';

}

$text_array['total_amount_country'] = rtrim($text, ',<br>');




$text = '';
# make total quantity by country
foreach ($rows as $row) {

$text .= $row['total_quantity'] . '&nbsp;' . $row['shipping_country'] . ',<br>';

}

$text_array['total_quantity_country'] = rtrim($text, ',<br>');

return $text_array;

}

private function purchase_buying_price($pid)
{

$sql = " SELECT * FROM `purchase_receipt_pro` WHERE receipt_product_id = ? ";
$row = $this->dbF->getRow($sql,array($pid));

return $row;
}

private function stock_last_update($stock_qty)
{
global $_e;

$stock_last_max_update_time = strftime('%Y-%m-%d', strtotime($this->productF->product_inventory_date_time));
$begin = new DateTime( $stock_last_max_update_time );
$end   = new DateTime( $this->date_to );

$difference = $begin->diff($end);
$total_days = $difference->days;
### adding 1 because date from 1st to 20th counts as 19 in difference, so we add 1.
$total_days += 1;        

$weeks = (int) ($total_days / 7);

if( $stock_qty == '0' ) {
// $_e['Not available since'] $_e['weeks'] $_e['Available since']
$result = $this->dbF->hardwords('Not available since',false) . ' ' . $weeks . ' ' . $this->dbF->hardwords('weeks',false);
} elseif ( $stock_qty == false ) {
# not found row in table
$result = $this->dbF->hardwords('Stock data unavailable',false);

} else {
$result = $this->dbF->hardwords('Available since',false) . ' ' . $weeks . ' ' . $this->dbF->hardwords('weeks',false);
}

return $result;
}





private function search_row_in_order_product_info()
{
try {

$result = false;


$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';


$sql  = " SELECT oi.shippingCountry,oi.paymentType, opi.*, oip.* FROM `order_product_info` opi
LEFT OUTER JOIN `order_invoice` oi ON oi.order_invoice_pk = opi.order_invoice_id 
LEFT OUTER JOIN `order_invoice_product` oip ON oip.invoice_product_pk = opi.invoice_product_pk

WHERE opi.`order_date` BETWEEN ? AND ? AND `calculated` = 0 ";
$rows = $this->dbF->getRows($sql,array($date_start,$date_end));

$result = $rows;



} catch (Exception $e) {
$result = false;

}


return $result;

}

private function insert_or_update_product_row($row_array)
{
try {

// $date_start = $this->date . ' 00:00:00';
// $date_end   = $this->date . ' 23:23:59';

$result = true;

$filter_comment = '';
$max_price_sql = $min_price_sql = $discount_count_sql = '';

$hash = md5($row_array['pid'].':'.$row_array['size'].':'.$row_array['color'].':'.$row_array['store']);

# DISCONTINUED START

// # get the countries
// $countries_currency  = $this->dbF->getRows(" SELECT `cur_country` FROM `currency` ");


// foreach ($countries_currency as $country) {

// $this->country = $country['cur_country'];
// // var_dump($this->country, $row_array['shippingCountry']);
// # if current country is not equal to order country than continue to next country
// if ( $this->country != $row_array['shippingCountry'] ) {
//     continue;
// }
# DISCONTINUED END

# table column type
$type = 'product_row_daily';
# if filter is applied like cat/color/size then search for filtered hash
if ( $this->filter_mode == TRUE ) {
# changing hash value
$hash = $this->filter_hash;
$type = 'product_row_filtered_daily';
$filter_comment = 'Cat: ' . $this->filter_cat . "\n\r" .  'Color: ' . $this->filter_color . "\n\r" .  'Size: ' . $this->filter_size . "\n\r";
}



# checking if row exists, then updating the stats for this row
$sql       = " SELECT * FROM `statistics` WHERE `date` = ? AND `hash` = ? AND `shipping_country` = ? ";
$stat_row  = $this->dbF->getRow($sql,array($this->date,$hash,$row_array['shippingCountry']));

# hash exists on this date, run update
if ($this->dbF->rowCount > 0) {

# update order product quantity
$qty     = $row_array['order_pQty'];
# update discount
$discount= $row_array['order_discount'];
# update price total
$amount  = ( $row_array['order_pPrice'] - $row_array['order_discount'] ) * $row_array['order_pQty'];


# save min 
$min           = $stat_row['min'];
# save max
$max           = $stat_row['max'];
# current product price
$current_price = ( $row_array['order_pPrice'] - $row_array['order_discount'] );
# check if current price is lt min, then update it
if( $current_price < $min ) {
$min_price_sql = " , `min`   = '{$current_price}' ";
}
# check if current price is gt max, then update it
if( $current_price > $min ) {
$max_price_sql = " , `max`   = '{$current_price}' ";
}
# if discount is applied, then add the purchase qty to the current discount quantity
if ( $row_array['order_discount'] > 0 ) {
$discount_count_sql = " `count` = count + {$qty} , ";
}

# calculate average
$average = ( $stat_row['total_amount'] + $amount ) / ( $stat_row['count'] + 1 );


$sql    = " UPDATE `statistics` SET 
`total_quantity` = total_quantity + {$qty}, 
`total_discount` = total_discount + {$discount}, 
`total_amount`   = total_amount + {$amount},
{$discount_count_sql} 
`avg`            = '{$average}'
{$min_price_sql} {$max_price_sql}
WHERE `id` = ?
AND `type` = ? ";
$row_id = $this->dbF->setRow($sql,array($stat_row['id'], 'product_row_daily'));



# DISCONTINUED
// // # add average, sale quantity
// $this->add_update_product_qty_avg($row_array,$stat_row,$amount);


// $row_id = $this->functions->formUpdate('statistics',$row_array,$lastId,$UpdateFieldName='id');

} else {
# UPDATE 2016-12-07, COLOR AND SIZE ALSO NOW CHECKED

# hash does not exist, run insert row
// $insert['shipping_country']     = $row['shipping_country'];
// $insert['total_quantity']       = $top_payment_method_row['total'];

$amount       = ( $row_array['order_pPrice'] - $row_array['order_discount'] ) * $row_array['order_pQty'];
$discount_qty = ( $row_array['order_discount'] > 0 ) ? $row_array['order_pQty'] : 0;

$insert['shipping_country'] = $row_array['shippingCountry'];
$insert['total_amount']     = $amount;
$insert['total_discount']   = $row_array['order_discount'];
$insert['total_quantity']   = $row_array['order_pQty'];
$insert['id_to_save']       = $row_array['pid'];
$insert['avg']              = $amount / $row_array['order_pQty'];
$insert['min']              = $amount;
$insert['max']              = $amount;
$insert['count']            = $discount_qty;
$insert['hash']             = $hash;
$insert['date']             = $this->date;
$insert['color']            = $row_array['color'];
$insert['size']             = $row_array['size'];
$insert['comment']          = $row_array['invoice_product_pk'] . "\r\n" . $filter_comment;


$insert['type']             = $type;

$row_id = $this->functions->formInsert('statistics',$insert);

# DISCONTINUED
// // # add average, sale quantity
// $this->add_update_product_qty_avg($row_array,$stat_row,$insert['total_amount']);

}

# add/update the payment method type
$this->payment_type_add_or_update($row_array);

// }  // foreach ($countries_currency as $country) end

// return $rows;



} catch (Exception $e) {
$result = false;

}


return $result;

}

/**
* function to update status of order_product_info tbl, if we have calculated a row in statistics we update its status, so that we don't calculate over it again.
*
* @return bool
* @author tAken
**/
private function update_product_row_status($row_array)
{
try {

$result = false;

$insert['calculated'] = 1;
$row_id = $this->functions->formUpdate('order_product_info',$insert,$row_array['id'],$UpdateFieldName='id');

if ( $row_id ) {
$result = true;
} else {
throw new Exception("Error Processing Request", 1);
}


} catch (Exception $e) {
$result = false;

}


return $result;

}

/**
* (DISCONTINUED) function to update average product price.
*
* @return bool
* @author tAken
**/
private function update_product_avg($id)
{
try {

$result = false;

$sql    = " UPDATE `statistics` SET 
`avg` = AVG(`total_amount`)
WHERE `id` = ?
AND `type` = ?";
$row_id = $this->dbF->setRow($sql,array($id, 'product_row_daily'));


if ( $row_id ) {
$result = true;
} else {
throw new Exception("Error Processing Request", 1);
}


} catch (Exception $e) {
$result = false;

}


return $result;

}

/** DISCONTINUED, WORKING APPLIED INSIDE insert_or_update_product_row()
* function to create daily product sold quantity and average.
*
* @return bool
* @author tAken
**/
private function add_update_product_qty_avg($row_array,$stat_row,$total_amount)
{
try {

$result = false;


$insert_mode = true;
# search for pid in the given date, if match found then update else run insert.
$sql  = " SELECT * FROM `statistics` WHERE `id_to_save` = ? AND `date` = ? AND `shipping_country` = ? AND `type` = ? ";
$row = $this->dbF->getRow($sql,array($row_array['pid'],$this->date,$row_array['shippingCountry'],'product_qty_and_avg_daily'));
if( $this->dbF->rowCount > 0 ) {
$insert_mode = false;
}


# row not found, run insertion
if ($insert_mode) {

# calculate average
$average = ( $total_amount ) / ( $row_array['order_pQty'] );

$sql    = " INSERT INTO `statistics` 
( `avg`, `id_to_save`, `total_amount`, `total_quantity`, `shipping_country`, `type`, `date` ) 
VALUES ( ?, ?, ?, ?, ?, ?, ? )
";
$row_id = $this->dbF->setRow($sql,array($average,$row_array['pid'],$total_amount,$row_array['order_pQty'],$row_array['shippingCountry'],'product_qty_and_avg_daily',$this->date));



} else {
# row found, update mode

$sql    = " UPDATE `statistics` SET 
`avg`            = ( avg + '{$total_amount}' ) / ( total_quantity + '{$row_array['order_pQty']}' ) ,
`total_quantity` = total_quantity + '{$row_array['order_pQty']}',
`total_amount`   = total_amount + '{$total_amount}'

WHERE `type` = ? AND `id_to_save` = ? AND `date` = ? AND `shipping_country` = ?";
$row_id = $this->dbF->setRow($sql,array('product_qty_and_avg_daily',$row_array['pid'],$this->date,$row_array['shippingCountry']));


}

if ( $row_id ) {
$result = true;
} else {
throw new Exception("Error Processing Request", 1);
}

} catch (Exception $e) {
$result = false;

}


return $result;

}

/**
* function search for product rows with filter applied
*
* @return bool
* @author tAken
**/
private function search_based_on_cat_color_size()
{

$date_start = $this->date . ' 00:00:00';
$date_end   = $this->date . ' 23:23:59';

$values_array = array($date_start,$date_end);


if ( !isset($_POST['category']) && !isset($_POST['color']) && !isset($_POST['size']) ) {
return false;
}

$cat = $color = $size = $cat_sql = $color_sql = $size_sql = '';

if ( (isset($_POST['category']) && $_POST['category'] != '') ) {
$cat_sql    = ' AND pc.procat_cat_id LIKE ? ';
$to_search  = '%' . $_POST['category'] . '%';
array_push($values_array, $to_search);
$cat        = $this->filter_cat      = $_POST['category'];
}

if ( ( isset($_POST['color']) && $_POST['color'] != '' ) ) {
// $color_sql  = ' AND opi.color LIKE ? '; // OLD
$color_sql  = ' AND opi.color IN ( SELECT propri_id FROM `product_color` WHERE proclr_name IN 
( SELECT color_name FROM `colors` WHERE color_id = ? ) 
AND sizeGroup IN ( SELECT color_name_id FROM `colors` WHERE color_id = ? )   ) ';
array_push($values_array, $_POST['color'], $_POST['color']);
$color      = $this->filter_color  = $_POST['color'];
}

if ( ( isset($_POST['size'])  && $_POST['size']  != '' ) ) {
// $size_sql   = ' AND opi.size LIKE ? '; // OLD
$size_sql   = ' AND opi.size IN ( SELECT prosiz_id FROM `product_size` WHERE prosiz_name IN 
( SELECT scale_name FROM `scales` WHERE scale_id = ?  ) 
AND sizeGroup IN ( SELECT scale_name_id FROM `scales` WHERE scale_id = ? ) ) ';
array_push($values_array, $_POST['size'], $_POST['size']);
$size       = $this->filter_size    = $_POST['size'];
}

try {

$result      = false;
// # first search in statistics for the searched cat/color/size

// # make hash for searching
$this->filter_hash = md5($cat . ':' . $color . ':' . $size);
// $rows = $this->dbF->getRows($sql,$values_array);
// if( $this->dbF->rowCount > 0 ) {
//     $result = $rows;
// }




# search for category/color/size product rows in order_invoice_product.
$sql  = " 
SELECT pc.procat_cat_id,oi.shippingCountry, opi.*, oip.* FROM `order_product_info` opi
LEFT OUTER JOIN `order_invoice_product` oip ON oip.invoice_product_pk = opi.invoice_product_pk
LEFT OUTER JOIN `order_invoice` oi ON oi.order_invoice_pk = opi.order_invoice_id
LEFT OUTER JOIN `product_category` pc ON pc.procat_prodet_id = opi.pid
WHERE opi.`order_date` BETWEEN ? AND ? {$cat_sql} {$color_sql} {$size_sql}
";
$rows = $this->dbF->getRows($sql,$values_array);
if( $this->dbF->rowCount > 0 ) {
$result = $rows;
}


} catch (Exception $e) {
$result = false;

}


return $result;

}

/**
* function to decide which searching function to use for getting product rows, for filter searching we use a different function.
*
* @return bool
* @author tAken
**/
private function searching_controller()
{

# if filter is applied then
if ( (isset($_POST['category']) && $_POST['category'] != '') 
|| ( isset($_POST['color']) && $_POST['color'] != '' ) 
|| ( isset($_POST['size'])  && $_POST['size']  != '' ) 
) {
# DISCONTINUED 13-DEC-2016, NOT MAKING NEW ROWS NOW FOR FILTER
// $this->filter_mode = TRUE; // will be used in rows creating to set appropriate type and diffent type of hash
// $rows = $this->search_based_on_cat_color_size();
$rows = $this->search_row_in_order_product_info();

} else {
# no filter applied
$rows = $this->search_row_in_order_product_info();

}

if ($rows == false) {
$rows = array();
}

return $rows;
}

public function payment_type_add_or_update($row_array,$type = 'product_row_payment_daily')
{
try {

# to remember, working is below
# we save order id and pid in new table, we then check if this order id and pid have been calculated or not
# we do this, because we want to calculate a payment method, only once, and per product
# so we are using this process


# to check if this order has been processed or not, because we can process a payment type only once per product
# UPDATE 13-DEC-2016 > Grabbing from hash now, instead of PID
/*$sql          = " SELECT * FROM `statistics_order_invoice_status` WHERE `order_invoice_pk` = ? AND `hash` = ? AND `calculated` = ? ";
$order_status = $this->dbF->getRow($sql,array($row_array['order_invoice_id'],$row_array['hash'],'1'));
// var_dump('Before', $row_array['order_invoice_id']);
if ($this->dbF->rowCount > 0) {
# if row is found then, this order's payment method has been calculated, no need to update or add its entry again
// var_dump('EXCEPTION FOR ORDER: ' . $row_array['order_invoice_id'] . '||| PID: ' . $row_array['pid']);
throw new Exception("Error Processing Request", 1);
} else {

$insert = array();
# add entry in statistics_order_invoice_status table
$insert['order_invoice_pk'] = $row_array['order_invoice_id'];
$insert['pid']              = $row_array['pid'];
$insert['size']             = $row_array['size'];
$insert['color']            = $row_array['color'];
$insert['hash']             = $row_array['hash'];
$insert['calculated']       = '1';
$ins_id = $this->functions->formInsert('statistics_order_invoice_status',$insert);

// var_dump('INSERTION FOR ORDER: ' . $row_array['order_invoice_id'] . '||| PID: ' . $row_array['pid']);
}*/
// var_dump('After', $row_array['order_invoice_id']);

# search for existing row, for this pid and payment type
# UPDATE 13-DEC-2016 > Grabbing from hash now, instead of PID
$sql       = " SELECT * FROM `statistics` WHERE `date` = ? AND `type` = ? AND `hash` = ? AND `payment_type` = ? AND `shipping_country` = ? ";
$stat_row  = $this->dbF->getRow($sql,array($this->date,$type,$row_array['hash'],$row_array['paymentType'],$row_array['shippingCountry']));

$result    = true;

# row exists, run update
if ($this->dbF->rowCount > 0) {

$qty     = $row_array['order_pQty'];

$sql    = " UPDATE `statistics` SET 
`total_quantity` = total_quantity + {$qty}
WHERE `id`       = ?
AND   `type`     = ? ";
$row_id = $this->dbF->setRow($sql,array($stat_row['id'],$type));


} else {
# run insert

$insert = array();
$insert['name']             = $this->productF->paymentArrayFind($row_array['paymentType']);
$insert['payment_type']     = $row_array['paymentType'];
$insert['id_to_save']       = $row_array['pid'];
$insert['hash']             = $row_array['hash'];
$insert['shipping_country'] = $row_array['shippingCountry'];
$insert['total_quantity']   = '1';
$insert['date']             = $this->date;
$insert['type']             = $type;

$row_id = $this->functions->formInsert('statistics',$insert);


}

} catch (Exception $e) {
$result = false;
}

return $result;

}









}
?>