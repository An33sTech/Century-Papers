<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class proStatics extends object_class{
public $productF;
public $imageName;
public function __construct(){
parent::__construct('3');

/**
* MultiLanguage keys Use where echo;
* define this class words and where this class will call
* and define words of file where this class will called
**/
global $_e;
global $adminPanelLanguage;
$_w=array();

//index.php
$_w['Product Qty Statistics'] = '' ;

$_w['ids'] = '' ;
$_w['Invoice Status'] = '' ;


//page.php
$_w['Product Stats'] = '' ;
$_w['All Products'] = '' ;
$_w['Stats'] = '' ;
$_w['Add New Page'] = '' ;
$_w['UnPublish Pages'] = '' ;
//pageEdit.php
$_w['Update'] = '' ;
$_w['Status'] = '' ;
$_w['Generate Statistics'] = '' ;

//This class
$_w['Invoice #'] = '' ;
$_w['Order Date'] = '' ;
$_w['Product Name'] = '' ;
$_w['Qty'] = '' ;
$_w['Price'] = '' ;
$_w['Discount'] = '' ;
$_w['Total Revenue'] = '' ;

$_w['Search By Date Range'] = '' ;
$_w['Date From'] = '' ;
$_w['Date To'] = '' ;
$_w['Close'] = '' ;
$_w['SNO'] = '' ;
$_w['Delete Fail Please Try Again.'] = '' ;
$_w['Sweden'] = '' ;
$_w['Norwegian'] = '' ;
$_w['Finland'] = '' ;
$_w['Denmark'] = '' ;
$_w['Manufacturer Name'] = '' ;
$_w[''] = '' ;
$_w[''] = '' ;
$_w[''] = '' ;
$_w[''] = '' ;
$_w[''] = '' ;
$_w[''] = '' ;
$_w[''] = '' ;
$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product QTY Statistics');

}


public function statsView(){
$sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process'";
// $data =  $this->dbF->getRows($sql);
// $this->printViewTable($data);
$this->product_list_View($sql);
}

public function statsViewSEK(){
$sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'SE'";
// $data =  $this->dbF->getRows($sql);
// $this->printViewTable($data);
$this->product_list_View($sql, 'SE');
}

public function statsViewNOK(){
$sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'NO'";
// $data =  $this->dbF->getRows($sql);
// $this->printViewTable($data);
$this->product_list_View($sql, 'NO');
}

public function statsViewFI(){
$sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'FI'";
// $data =  $this->dbF->getRows($sql);
// $this->printViewTable($data);
$this->product_list_View($sql, 'FI');
}

public function statsViewDK(){
$sql  = "SELECT `order_invoice_pk`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'DK'";
// $data =  $this->dbF->getRows($sql);
// $this->printViewTable($data);
$this->product_list_View($sql, 'DK');
}









public function alldata($page){

         // return $this->dbF->prnt("ssssss");
         // die;
        global $_e, $functions;
        $start  = ( isset($_POST['start']) )  ? $_POST['start']             : 0;
        $length = ( isset($_POST['length']) ) ? $_POST['length']            : 10;
        // $invoiceStatus = ( isset($_POST['invoiceStatus']) ) ? $_POST['invoiceStatus']            : NULL;

          $invoiceStatus = (isset($_POST['invoiceStatus']) && $_POST['invoiceStatus'] != '' )  ? $_POST['invoiceStatus'] : NULL;


        // $draw   = ( isset($_POST['draw']) )   ? (int) $_POST['draw']        : null;
        $search = ( isset($_POST['search']) ) ? ($_POST['search']['value']) : null;
        $orderBy = ( isset($_POST['order'][0]['column']) ) ? ($_POST['order'][0]['column']) : null;
        $orderDir = ( isset($_POST['order'][0]['dir']) ) ? ($_POST['order'][0]['dir']) : null;

        // $columnss = array( 
        //     // datatable column index  => database column name
        //         1 => 'invNo', 
        //         2 => 'order_pName',
        //         3 => 'invoice_date',
        //         4 => 'order_pQty',
        //         5 => 'order_salePrice',
        //         6 => 'revenue',
        //     );

        #### Search Query #####
        // @$page  = $_GET['page'];
        $setting_val = " '1' ";

        
        if($page == 'SE'){
            $country_sql = "AND oi.`shippingCountry` = 'SE' ";
        }

        else if($page == 'NO'){
            $country_sql = "AND oi.`shippingCountry` = 'NO' ";
        }

        else if($page == 'FI'){
            $country_sql = "AND oi.`shippingCountry` = 'FI' ";
        }

        else if($page == 'DK'){
            $country_sql = "AND oi.`shippingCountry` = 'DK' ";
        }
        else{
            $country_sql = '';
        }

        if($search) { $search_sql = "AND ( ip.`order_pName` LIKE '%{$search}%' ) ";
        } else { $search_sql = ''; }

        if($orderBy && $columnss[$orderBy] != 'revenue' && $columnss[$orderBy] != 'invNo') {
            $order_select = ",CAST(".$columnss[$orderBy]." as DECIMAL(9,2)) '".$columnss[$orderBy]."' ";
            $order_sql = " ORDER BY `". $columnss[$orderBy]."` ".$orderDir."";
        }
        else{
            $order_select = '';
            $order_sql = " GROUP BY `order_pIds`";
        }

        ##### ADDITIONAL CUSTOM FILTER FILEDS #####
        $dateCodeFrom = (isset($_POST['min']) && $_POST['min'] != '' )  ? $_POST['min'] . ' 00:00:00'  : date('Y-m-d') . ' 00:00:00';
        $dateCodeTo   = (isset($_POST['max']) && $_POST['max'] != '' )    ? $_POST['max'] . ' 23:59:59'    : date('Y-m-d') . ' 23:59:59';

        ## make between sql for date
        // $between_sql = ( isset($dateCodeFrom) && isset($dateCodeTo) ) ? " AND oi.`invoice_date` BETWEEN '${dateCodeFrom}' AND '${dateCodeTo}' " : '' ;

        ############# GET TOTAL ROWS #############
        // $total_count_sql = "SELECT oi.`order_invoice_pk`,oi.`shippingCountry`$order_select,oi.`invoice_id`,oi.`invoice_date`,oi.`price_code`,oi.`orderStatus`,ip.* 
        //                     FROM `order_invoice` oi 
        //                     JOIN `order_invoice_product` ip
        //                     WHERE oi.`order_invoice_pk` = ip.`order_invoice_id` AND `invoice_status` <> '{$invoiceStatus}' {$between_sql} {$country_sql} {$search_sql} {$order_sql}";

        // $all_data = $this->dbF->getRows($total_count_sql);
        // $recordsTotal = $this->dbF->rowCount;

        $between_sql = ( isset($dateCodeFrom) && isset($dateCodeTo) ) ? " AND oi.`invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' " : '' ;


        $invoiceStatus_sql =  (isset($invoiceStatus) && $invoiceStatus != '9999' )  ? " AND `invoice_status` = '{$invoiceStatus}' " : '' ;



     $invoiceStatus_sql1 =  (isset($invoiceStatus) && $invoiceStatus == '9999' )  ? " AND `invoice_status` not in ('0','1','3','6') " : '' ;





        ###### Get Data ######
$qry = "SELECT ip.`order_pName` , count(ip.`order_pIds`) as qty , GROUP_CONCAT(DISTINCT oi.`invoice_id` SEPARATOR ', ') AS invoice_id
FROM `order_invoice` oi 
JOIN `order_invoice_product` ip
WHERE oi.`order_invoice_pk` = ip.`order_invoice_id` {$invoiceStatus_sql} {$invoiceStatus_sql1} {$between_sql} {$country_sql} {$search_sql} {$order_sql} ";

        $data = $this->dbF->getRows($qry);


        // $columns = array();
        // if($draw == 1){ $draw - 1; }

        // $columns["draw"] =$draw+1;
        // $columns["recordsTotal"] = $recordsTotal; //total record,
        // $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear

        
$temp='';
        $i = $start;
        foreach($data as $key => $val){
            $i++;
            // $defaultLang= $this->functions->AdminDefaultLanguage();
            // $invoice_No = $val['invoice_id'];
            // $orderStatus = $val['orderStatus'];
            // $invoice_date = $val['invoice_date'];
            // $prodIdd_st = $val['order_pIds'];
            // $pro_exp = explode('-',$prodIdd_st, 2);
            // $prod_idd = $pro_exp[0];
            $prod_name =  $val['order_pName'];
            $pro_qty = $val['qty'];    $ids = $val['invoice_id'];
            // $prod_price = $val['order_salePrice'];
            // $total_reve = ($val['order_salePrice']-$val['order_discount']);

            // $pro_setting = $this->getProductSetting($prod_idd);
            // $model = $pro_setting['setting_val'];

            # this functions uses $_SERVER['REQUEST_URI'], as now we are using ajax request so the link in $_SERVER['REQUEST_URI'] is of the ajax request not the current url in browser, so we are hardcoding this for the time being, new way / function will have to be created.
            // $link  = $this->functions->getLinkFolder();

            //6 columns
            // $columns["data"][$key] = array(
            //     $i, ##### disabling this for the time being needs work "{$first_column}",
            //     "{$qry}",
            //     "{$orderStatus}",
            //     "{$prod_name}",
            //     "{$model}",
            //     "{$invoice_date}",
            //     "{$pro_qty}",
            //     "{$prod_price}",
            //      "{$invoiceStatus}"
            // );


         $temp .=   '<tr><td>'.$i.'</td><td>'.$prod_name.'</td><td>'.$pro_qty.'</td><td>'.$ids.'</td></tr>';



        }

return $temp;

        // if($recordsTotal =='0'){
        //     $columns["data"] = array();
        // }
        // Jason Encode
        // print_r($data);
        
        // echo ($qry);
        // die;
        // print_r($pro_setting);
        // echo $total_count_sql;
        // echo 'mudassir'.$qry;
        // echo $recordsTotal;
        // echo $qry;
         // echo json_encode($columns);
    }

/**
* @param $qry
*/
private function product_list_View($qry=false,$page = ''){
global $_e;
$data=$this->dbF->getRows($qry);
// var_dump("expression");
# href is used by ajax request
$href = "product_qty_stats/pro_statics_ajax.php?page=all_products";

if($page == 'SE'){
$href = "product_qty_stats/pro_statics_ajax.php?page=SE";



// var_dump("expressionse");





$defaultLang= $this->functions->AdminDefaultLanguage();
if(!empty($data) && $data != false){



$uniq=uniqid('id');
echo  '
<div class="table-responsive">
<table class="table table-hover dTableFull tableIBMS dataTable" data-href="'.$href.'" data-uniq="'.$uniq.'" data-sorting="true">
<thead>
<tr>
<th><div class="checkbox delCheckboxOncolvis">
<label>
<input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="'.$uniq.'" ng-model="'.$uniq.'">'. _u($_e['SNO']) .'
</label>
</div>
</th>

<th>'. _u($_e['Product Name']) .'</th>

<th>'. _u($_e['Qty']) .'</th>
<th>'. _u($_e['ids']) .'</th>
</tr>
</thead>

<tbody>';
echo $this->alldata('SE');
echo <<<HTML
</tbody>
</table>
</div>
HTML;

}

}
else if($page == 'NO'){
$href = "product_qty_stats/pro_statics_ajax.php?page=NO";


// var_dump("expressionsenoono");

$defaultLang= $this->functions->AdminDefaultLanguage();
if(!empty($data) && $data != false){



$uniq=uniqid('id');
echo  '
<div class="table-responsive">
<table class="table table-hover dTableFull tableIBMS dataTable" data-href="'.$href.'" data-uniq="'.$uniq.'" data-sorting="true">
<thead>
<tr>
<th><div class="checkbox delCheckboxOncolvis">
<label>
<input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="'.$uniq.'" ng-model="'.$uniq.'">'. _u($_e['SNO']) .'
</label>
</div>
</th>

<th>'. _u($_e['Product Name']) .'</th>

<th>'. _u($_e['Qty']) .'</th>
<th>'. _u($_e['ids']) .'</th>
</tr>
</thead>

<tbody>';
echo $this->alldata('NO');
echo <<<HTML
</tbody>
</table>
</div>
HTML;

}




}
else if($page == 'FI'){
$href = "product_qty_stats/pro_statics_ajax.php?page=FI";




// var_dump("expressionsenoonofllll");


$defaultLang= $this->functions->AdminDefaultLanguage();
if(!empty($data) && $data != false){



$uniq=uniqid('id');





echo  '
<div class="table-responsive">
<table class="table table-hover dTableFull tableIBMS dataTable" data-href="'.$href.'" data-uniq="'.$uniq.'" data-sorting="true">
<thead>
<tr>
<th><div class="checkbox delCheckboxOncolvis">
<label>
<input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="'.$uniq.'" ng-model="'.$uniq.'">'. _u($_e['SNO']) .'
</label>
</div>
</th>

<th>'. _u($_e['Product Name']) .'</th>

<th>'. _u($_e['Qty']) .'</th>
<th>'. _u($_e['ids']) .'</th>
</tr>
</thead>

<tbody>




';
 // $ajax->fetch_products();  

echo $this->alldata('FI');

echo <<<HTML
</tbody>
</table>
</div>
HTML;

}








}
else if($page == 'DK'){
$href = "product_qty_stats/pro_statics_ajax.php?page=DK";

// var_dump("expressionsenoonoflllldkkkkkkkkkkkkk");

$defaultLang= $this->functions->AdminDefaultLanguage();
if(!empty($data) && $data != false){



$uniq=uniqid('id');
echo  '
<div class="table-responsive">
<table class="table table-hover dTableFull tableIBMS dataTable" data-href="'.$href.'" data-uniq="'.$uniq.'" data-sorting="true">
<thead>
<tr>
<th><div class="checkbox delCheckboxOncolvis">
<label>
<input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="'.$uniq.'" ng-model="'.$uniq.'">'. _u($_e['SNO']) .'
</label>
</div>
</th>


<th>'. _u($_e['Product Name']) .'</th>

<th>'. _u($_e['Qty']) .'</th>
<th>'. _u($_e['ids']) .'</th>
</tr>
</thead>

<tbody>';

echo $this->alldata('DK');
echo <<<HTML
</tbody>
</table>
</div>
HTML;

}



}else {
// $href = "product_qty_stats/pro_statics_ajax.php?page=DK";

// var_dump("expressionsenoonoflllldkkkkkkkkkkkkk");

$defaultLang= $this->functions->AdminDefaultLanguage();
if(!empty($data) && $data != false){



$uniq=uniqid('id');
echo  '
<div class="table-responsive">
<table class="table table-hover dTableFull tableIBMS dataTable" data-href="'.$href.'" data-uniq="'.$uniq.'" data-sorting="true">
<thead>
<tr>
<th><div class="checkbox delCheckboxOncolvis">
<label>
<input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="'.$uniq.'" ng-model="'.$uniq.'">'. _u($_e['SNO']) .'
</label>
</div>
</th>


<th>'. _u($_e['Product Name']) .'</th>

<th>'. _u($_e['Qty']) .'</th>
<th>'. _u($_e['ids']) .'</th>
</tr>
</thead>

<tbody>';

echo $this->alldata('');
echo <<<HTML
</tbody>
</table>
</div>
HTML;

}



}



}
    public function getProductSetting($pId){
        $qry    =   "SELECT `setting_val` FROM  `product_setting` WHERE `p_id` = '$pId' AND `setting_name` = 'Model'";
        $eData  =   $this->dbF->getRow($qry);
        if($this->dbF->rowCount>0)
            return $eData;
        return false;
    }

private function printViewTable($data){
global $_e;
echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>';
$slug = false;
if($this->functions->developer_setting('page_slug')=='1'){
echo '<th>'. _u($_e['SLUG']) .'</th>';
$slug = true;
}
echo '          <th>'. _u($_e['TITLE']) .'</th>
<th>'. _u($_e['UPDATE']) .'</th>
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';
$i = 0;
$defaultLang = $this->functions->AdminDefaultLanguage();
foreach($data as $val){
$i++;
$id = $val['page_pk'];
$heading = unserialize($val['heading']);
$heading = $heading[$defaultLang];
echo "<tr>
<td>$i</td>";
if($slug){
echo "  <td>$val[slug]</td>";
}

$seoLink = '';
if($this->functions->developer_setting('seo') == '1'){
$this->functions->getAdminFile("seo/classes/seo.class.php");
$seoC = new seo();
$seoLink = $seoC->seoQuickLink($id,urlencode("/".$this->db->dataPage."$val[slug]"));
}

echo "  <td>$heading</td>
<td>$val[dateTime]</td>
<td>
<div class='btn-group btn-group-sm'>
$seoLink
<a data-id='$id' href='-".$this->functions->getLinkFolder()."?page=edit&pageId=$id' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deletePage(this);' class='btn'>
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
}
?>