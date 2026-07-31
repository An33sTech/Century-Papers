<?php
############ Export table into CSV ############

require_once("../global.php");

$_d = ","; //$delimiter

####### CSV file Headings, for excel edit mode.
$file_heading = "Product ID{$_d}Product Name{$_d}Product Size{$_d}Quantity{$_d}Buy In Price{$_d}Selling Price{$_d}Total Revenue";
$file_heading .= "\n";

$output = $file_heading;

$sql = "SELECT pd.prodet_id, pd.prodet_name, pd.buying_price, ps.prosiz_name, pi.qty_item, pp.propri_price FROM proudct_detail pd LEFT JOIN product_size ps ON ps.prosiz_prodet_id = pd.prodet_id AND ps.prosiz_cur_id = 20 LEFT JOIN product_inventory pi ON pi.qty_product_id = pd.prodet_id AND pi.qty_item > 0 LEFT JOIN product_price pp ON pp.propri_prodet_id = pd.prodet_id AND pp.propri_cur_id = 20 WHERE ps.prosiz_cur_id = 20 AND pp.propri_cur_id = 20 AND pi.qty_product_scale_name = ps.prosiz_name AND pi.qty_item > 0";
$data = $dbF->getRows($sql);
$total_qty = 0;
$total_buy_in_price = 0;
foreach ($data as $val) {

    $pId         = $val['prodet_id'];
    $prodet_name = $functions->unserializeTranslate($val['prodet_name']);
    $prodet_name   = specialChar_to_english_letters($prodet_name);
    $size = $val["prosiz_name"];
    $qty = $val["qty_item"];
    $buyInPrice = $val["buying_price"];
    $price = $val["propri_price"];
    $revenue = intval($val['propri_price'] - $val['buying_price']);
    
    $total_qty += $qty;
    $total_buy_in_price += $buyInPrice;
    
    ####### CSV single row...
    $output .= "{$pId}{$_d}{$prodet_name}{$_d}{$size}{$_d}{$qty}{$_d}{$buyInPrice}{$_d}{$price}{$_d}{$revenue}";
    $output .= "\n";
}

$output .= "Total{$_d}{$_d}{$_d}{$total_qty}{$_d}{$total_buy_in_price}{$_d}{$_d}{$_d}\n";

####### Download csv File...
$filename = "IBMS_product_inventory_stats.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;
exit;

?>