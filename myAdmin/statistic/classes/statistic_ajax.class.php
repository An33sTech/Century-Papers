<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class statistic_ajax extends object_class{
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
        //ajax class
        $_w['Delete'] = '' ;
        $_w['Size'] = '' ;
        $_w['Color'] = '' ;
        


        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Statistic Ajax 2016');
    }

public function st_product_size_color_range(){
    global $_e;
    try{

        $html = '';

        if ( !isset($_POST['id']) || !isset($_POST['date_from']) || !isset($_POST['date_to']) ) {
            $result = false;
        } else {

            // var_dump($_POST);

            $date_from = $_POST['date_from'] . ' 00:00:00';
            $date_to   = $_POST['date_to'] . ' 23:23:59';

            $size_array  = array();
            $color_array = array();

            $sql  = " SELECT opi.*, oip.order_pQty FROM `order_product_info` opi
                      LEFT OUTER JOIN `order_invoice_product` oip ON oip.invoice_product_pk = opi.invoice_product_pk
                      WHERE opi.`pid` = ? AND opi.`order_date` BETWEEN ? AND ? ";
            $rows = $this->dbF->getRows($sql,array($_POST['id'],$date_from,$date_to));

            # loop over the products
            foreach ($rows as $row) {
    
                # get the size
                $size_row = $this->dbF->getRow(" SELECT * FROM `product_size` WHERE prosiz_id = ? ", array($row['size']));
                if ( $this->dbF->rowCount > 0 ) {
                    # initialize size
                    if( !isset($size_array['size'][$size_row['prosiz_name']]) ) {
                        $size_array['size'][$size_row['prosiz_name']] = '0';
                    }
                    # save and increment size count
                    $size_array['size'][$size_row['prosiz_name']] += $row['order_pQty'];
                    
                }
                
                # get the color
                $color_row = $this->dbF->getRow(" SELECT * FROM `product_color` WHERE propri_id = ? ", array($row['color']));
                if ( $this->dbF->rowCount > 0 ) {
                    # initialize color
                    if( !isset($color_array['color'][$color_row['proclr_name']]) ) {
                        $color_array['color'][$color_row['proclr_name']] = '0';
                    }
                    # save and increment color count
                    $color_array['color'][$color_row['proclr_name']] += $row['order_pQty'];
                }
                // var_dump($size_row['prosiz_name']);

            }

            if (isset($size_array['size'])) {
                # run print loop
                $html .= $_e['Size'] . ': ';
                foreach ($size_array['size'] as $size_name => $val) {
                    $html .= $size_name . ' (' . $val . '), ';
                }
                $html = rtrim($html,', ');
            }

            if (isset($color_array['color'])) {
                
                # run print loop
                $html .= '<br>' . $_e['Color'] . ': ';
                foreach ($color_array['color'] as $color_name => $val) {
                    $html .= '<div title="' . $color_name . '" class="color_div" style="background-color: #' . $color_name . '"></div> (' . $val . '), ';
                }
                $html = rtrim($html,', ');

            }

            // var_dump($size_array,$color_array);

            echo $html;


            
        }

    } catch (PDOException $e) {

    }
}





}
?>