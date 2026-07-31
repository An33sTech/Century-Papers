<?php

############ Export table into CSV ############



require_once("../global.php");



$_d = ","; //$delimiter



####### CSV file Headings, for excel edit mode.

$file_heading = "product_id{$_d}product_name{$_d}disID{$_d}curID{$_d}Discount(currency){$_d}Discount Format";

// $file_heading .= "qty_pk{$_d}qty_store_id{$_d}qty_product_id{$_d}qty_product_scale{$_d}qty_product_color{$_d}product_store_hash";

$file_heading .= "\n";



$output = $file_heading;





$sql    = "SELECT `prodet_id`,`prodet_name` 

                FROM `proudct_detail` WHERE `product_update` = 1"; // ORDER BY qty_pk DESC

    $data   = $dbF->getRows($sql);

    foreach ($data as $val) {

        $pId         = $val['prodet_id'];

        $prodet_name = $functions->unserializeTranslate($val['prodet_name']);

        $prodet_name   = specialChar_to_english_letters($prodet_name);







        $sql1 = "SELECT * FROM `product_discount` WHERE `discount_PId` = ?";

        $res1  = $dbF->getRow($sql1, array($pId));

        $discount_id = $res1['product_discount_pk'];

        // $dbF->prnt($res1);



        



        if($dbF->rowCount > 0){

            $sql0 = "SELECT * FROM `currency`";

        $curData = $dbF->getRows($sql0);



foreach ($curData as $key => $value) {

    $cur_id     = $value['cur_id'];

    $cur_symbol = $value['cur_symbol'];



    

            $sql2 = "SELECT * FROM `product_discount_prices` 

                        WHERE product_dis_curr_Id = ? 

                        AND `product_dis_id` = ?";

            $res2 = $dbF->getRow($sql2, array($cur_id, $discount_id));

            $discount = $res2['product_dis_price'].' '.$cur_symbol;



            $sql3 = "SELECT `product_dis_value` FROM `product_discount_setting` WHERE `product_dis_id` = ? AND `product_dis_name` = 'discountFormat'" ;

            $res3 = $dbF->getRow($sql3, array($discount_id));

            $discount_format = $res3['product_dis_value'];

            $output .= "{$pId}{$_d}{$prodet_name}{$_d}{$discount_id}{$_d}{$cur_id}{$_d}{$discount}{$_d}{$discount_format}";

        $output .= "\n";

            } 

        }

        else{

            foreach ($curData as $key => $value) {

    $cur_id     = $value['cur_id'];

    $cur_symbol = $value['cur_symbol'];

            $discount = 0;

            $discount_format = '';

            $output .= "{$pId}{$_d}{$prodet_name}{$_d}{$discount_id}{$_d}{$cur_id}{$_d}{$discount}{$_d}{$discount_format}";

        $output .= "\n";

    }

        }

        

        

    }





// }



####### Download csv File...

$filename = "IBMS_product_discount.csv";

header('Content-type: application/csv;charset=UTF-8');

header('Content-Disposition: attachment; filename=' . $filename);



echo $output;

exit;



?>