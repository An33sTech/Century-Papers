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
        $_w['Product Statistics'] = '' ;
        //page.php
        $_w['Product Stats'] = '' ;
        $_w['All Products'] = '' ;
        $_w['Stats'] = '' ;
        $_w['Add New Page'] = '' ;
        $_w['UnPublish Pages'] = '' ;
        //pageEdit.php
        $_w['Update'] = '' ;

        //This class
        $_w['Invoice #'] = '' ;
        $_w['Order Date'] = '' ;
        $_w['Product Name'] = '' ;
        $_w['Qty'] = '' ;
        $_w['Price'] = '' ;
        $_w['Discount'] = '' ;
        $_w['Total Revenue'] = '' ;
        $_w['Product ID'] = '' ;
        $_w['Product Size'] = '' ;
        $_w['Buy In Price'] = '' ;
        $_w['Selling Price'] = '' ;

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
        $_w['Switzerland'] = '' ;
        $_w['Manufacturer Name'] = '' ;
        $_w['Product Inventory Stats'] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Statistics');

    }


    public function statsView(){
        $sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' ";
        // $data =  $this->dbF->getRows($sql);
        // $this->printViewTable($data);
        $this->product_list_View($sql);
    }

    public function statsViewSEK(){
        $sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'SE' ";
        // $data =  $this->dbF->getRows($sql);
        // $this->printViewTable($data);
        $this->product_list_View($sql, 'SE');
    }

    public function statsViewNOK(){
        $sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'NO' ";
        // $data =  $this->dbF->getRows($sql);
        // $this->printViewTable($data);
        $this->product_list_View($sql, 'NO');
    }

    public function statsViewFI(){
        $sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'FI' ";
        // $data =  $this->dbF->getRows($sql);
        // $this->printViewTable($data);
        $this->product_list_View($sql, 'FI');
    }

    public function statsViewDK(){
        $sql  = "SELECT `order_invoice_pk`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'DK' ";
        // $data =  $this->dbF->getRows($sql);
        // $this->printViewTable($data);
        $this->product_list_View($sql, 'DK');
    }
    
    public function statsViewCHF(){
        $sql  = "SELECT `order_invoice_pk`,`shippingCountry`,`invoice_id`,`invoice_date`,`price_code` FROM `order_invoice` WHERE `orderStatus` = 'process' AND `shippingCountry` = 'CH' ";
        // $data =  $this->dbF->getRows($sql);
        // $this->printViewTable($data);
        $this->product_list_View($sql, 'CH');
    }
    
    public function statsInventoryView(){
        $sql = "SELECT pd.prodet_id, pd.prodet_name, pd.buying_price, ps.prosiz_name, pi.qty_item, pp.propri_price FROM proudct_detail pd LEFT JOIN product_size ps ON ps.prosiz_prodet_id = pd.prodet_id AND ps.prosiz_cur_id = 20 LEFT JOIN product_inventory pi ON pi.qty_product_id = pd.prodet_id AND pi.qty_item > 0 LEFT JOIN product_price pp ON pp.propri_prodet_id = pd.prodet_id AND pp.propri_cur_id = 20 WHERE ps.prosiz_cur_id = 20 AND pp.propri_cur_id = 20 AND pi.qty_product_scale_name = ps.prosiz_name AND pi.qty_item > 0";
        $data = $this->dbF->getRows($sql);
        $this->printStatsDiv($data);
        $this->printViewTableNew($data);
    }

    /**
     * @param $qry
     */
    private function product_list_View($qry=false,$page = ''){
        global $_e;
        $data=$this->dbF->getRows($qry);

        # href is used by ajax request
        $href = "product_stats/pro_statics_ajax.php?page=all_products";

        if($page == 'SE'){
            $href = "product_stats/pro_statics_ajax.php?page=SE";
        }
        else if($page == 'NO'){
            $href = "product_stats/pro_statics_ajax.php?page=NO";
        }
        else if($page == 'FI'){
            $href = "product_stats/pro_statics_ajax.php?page=FI";
        }
        else if($page == 'DK'){
            $href = "product_stats/pro_statics_ajax.php?page=DK";
        }        
        else if($page == 'CH'){
            $href = "product_stats/pro_statics_ajax.php?page=CH";
        }


        $defaultLang= $this->functions->AdminDefaultLanguage();
        if(!empty($data) && $data != false){
            
            

            $uniq=uniqid('id');
            echo  '
            <div class="table-responsive">
            <table class="table table-hover tableIBMS dTable_ajax1" data-href="'.$href.'" data-uniq="'.$uniq.'" data-sorting="true">
                <thead>
                    <tr>
                        <th><div class="checkbox delCheckboxOncolvis">
                                    <label>
                                      <input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="'.$uniq.'" ng-model="'.$uniq.'">'. _u($_e['SNO']) .'
                                    </label>
                                  </div>
                            </th>
                        <th>'. _u($_e['Invoice #']) .'</th>
                        <th>'. _u($_e['Product Name']) .'</th>
                        <th>'. _u($_e['Manufacturer Name']) .'</th>
                        <th>'. _u($_e['Order Date']) .'</th>
                        <th>'. _u($_e['Qty']) .'</th>
                        <th>'. _u($_e['Price']) .'</th>
                        <th>'. _u($_e['Total Revenue']) .'</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>';

            echo <<<HTML
                </tbody>
            </table>
            </div>
HTML;

        }
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
    
    private function printStatsDiv($data){
        $totalQty = 0;
        $totalBuyInPrice = 0;
        foreach($data as $val){
            $totalQty += intval($val['qty_item']);
            $totalBuyInPrice += intval($val['buying_price']);
        }
        echo '<h4>Available Stock Quantity Total: ' . $totalQty . '</h4>';
        echo '<h4>Buy In Price Total: ' . $totalBuyInPrice . '</h4>';
    }
    
    private function printViewTableNew($data){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['Product Name']) .'</th>
                        <th>'. _u($_e['Product Size']) .'</th>
                        <th>'. _u($_e['Qty']) .'</th>
                        <th>'. _u($_e['Buy In Price']) .'</th>
                        <th>'. _u($_e['Selling Price']) .'</th>
                        <th>'. _u($_e['Total Revenue']) .'</th>
                    </thead>
                <tbody>';
        $i = 0;

        foreach($data as $val){
            $i++;
            $revenue = intval($val['propri_price'] - $val['buying_price']);
            $pName = unserialize($val['prodet_name']);
            
            echo "<tr>
                    <td>$i</td>
                    <td>$pName[Swedish]</td>
                    <td>$val[prosiz_name]</td>
                    <td>$val[qty_item]</td>
                    <td>$val[buying_price]</td>
                    <td>$val[propri_price]</td>
                    <td>$revenue</td>
                  </tr>";
        }
        echo '</tbody>
            </table>
            </div> <!-- .table-responsive End -->';
    }
}
?>