<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db

class proStatics extends object_class{
    public function __construct(){
        parent::__construct('3');
    }

    public  function fetch_products(){
        global $_e, $functions;
        $start  = ( isset($_POST['start']) )  ? $_POST['start']             : 0;
        $length = ( isset($_POST['length']) ) ? $_POST['length']            : 10;
        $draw   = ( isset($_POST['draw']) )   ? (int) $_POST['draw']        : null;
        $search = ( isset($_POST['search']) ) ? ($_POST['search']['value']) : null;
        $orderBy = ( isset($_POST['order'][0]['column']) ) ? ($_POST['order'][0]['column']) : null;
        $orderDir = ( isset($_POST['order'][0]['dir']) ) ? ($_POST['order'][0]['dir']) : null;

        $columnss = array( 
            // datatable column index  => database column name
                1 => 'invNo', 
                2 => 'order_pName',
                3 => 'invoice_date',
                4 => 'order_pQty',
                5 => 'order_salePrice',
                6 => 'revenue',
            );

        #### Search Query #####
        @$page  = $_GET['page'];
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
        
        else if($page == 'CH'){
            $country_sql = "AND oi.`shippingCountry` = 'CH' ";
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
            $order_sql = " ORDER BY oi.`order_invoice_pk` DESC";
        }




        ##### ADDITIONAL CUSTOM FILTER FILEDS #####
        $dateCodeFrom = (isset($_POST['dateCodeFrom']) && $_POST['dateCodeFrom'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeFrom'])->format('Y-m-d') . ' 00:00:00 ' : NULL;
        $dateCodeTo = (isset($_POST['dateCodeTo']) && $_POST['dateCodeTo'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeTo'])->format('Y-m-d') . ' 23:59:59 ' : NULL;

        ## make between sql for date
        $between_sql = ( isset($dateCodeFrom) && isset($dateCodeTo) ) ? " AND oi.`invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' " : '' ;

        ############# GET TOTAL ROWS #############
        // $total_count_sql = "SELECT oi.`order_invoice_pk`,oi.`shippingCountry`$order_select,oi.`invoice_id`,oi.`invoice_date`,oi.`price_code`,ip.* 
        //                     FROM `order_invoice` oi 
        //                     JOIN `order_invoice_product` ip
        //                     WHERE oi.`order_invoice_pk` = ip.`order_invoice_id` AND `orderStatus` <> 'inComplete' {$between_sql} {$country_sql} {$search_sql} {$order_sql}";        
        
        $total_count_sql = "SELECT COUNT(*) as total_count FROM `order_invoice` oi JOIN `order_invoice_product` ip WHERE 
        oi.`order_invoice_pk` = ip.`order_invoice_id` AND `orderStatus` <> 'inComplete' {$between_sql} {$country_sql} {$search_sql} {$order_sql}";

        $all_data = $this->dbF->getRow($total_count_sql);
        $recordsTotal = $all_data['total_count'];

        $between_sql = ( isset($dateCodeFrom) && isset($dateCodeTo) ) ? " AND oi.`invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' " : '' ;

        ###### Get Data ######
        $qry = "SELECT oi.`order_invoice_pk`,oi.`invoice_id`,oi.`shippingCountry`$order_select,oi.`invoice_date`,oi.`price_code`,ip.* 
                            FROM `order_invoice` oi 
                            JOIN `order_invoice_product` ip
                            WHERE oi.`order_invoice_pk` = ip.`order_invoice_id` AND `orderStatus` <> 'inComplete' {$between_sql} {$country_sql} {$search_sql} {$order_sql} LIMIT {$start},{$length}";

        $data = $this->dbF->getRows($qry);


        $columns = array();
        if($draw == 1){ $draw - 1; }

        $columns["draw"] =$draw+1;
        $columns["recordsTotal"] = $recordsTotal; //total record,
        $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear

        
        $i = $start;
        foreach($data as $key => $val){

            $i++;
            $defaultLang= $this->functions->AdminDefaultLanguage();
            $invoice_No = $val['invoice_id'];
            $invoice_date = $val['invoice_date'];
            $prodIdd_st = $val['order_pIds'];
            $pro_exp = explode('-',$prodIdd_st, 2);
            $prod_idd = $pro_exp[0];
            $prod_name =  $val['order_pName'];
            $pro_qty = $val['order_pQty'];
            $prod_price = $val['order_salePrice'];
            $total_reve = ($val['order_salePrice']-$val['order_discount']);

            $pro_setting = $this->getProductSetting($prod_idd);
            $model = $pro_setting['setting_val'];

            # this functions uses $_SERVER['REQUEST_URI'], as now we are using ajax request so the link in $_SERVER['REQUEST_URI'] is of the ajax request not the current url in browser, so we are hardcoding this for the time being, new way / function will have to be created.
            // $link  = $this->functions->getLinkFolder();

            //6 columns
            $columns["data"][$key] = array(
                $i, ##### disabling this for the time being needs work "{$first_column}",
                "{$invoice_No}",
                "{$prod_name}",
                "{$model}",
                "{$invoice_date}",
                "{$pro_qty}",
                "{$prod_price}",
                "{$total_reve}"
            );
        }
        if($recordsTotal =='0'){
            $columns["data"] = array();
        }
        
        echo json_encode($columns);
    }

    public function getProductSetting($pId){
        $qry    =   "SELECT `setting_val` FROM  `product_setting` WHERE `p_id` = '$pId' AND `setting_name` = 'Model'";
        $eData  =   $this->dbF->getRow($qry);
        if($this->dbF->rowCount>0)
            return $eData;
        return false;
    }


}
?>