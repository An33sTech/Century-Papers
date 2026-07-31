<?php require_once("../global.php");
require(__DIR__ . "/classes/product.class.php");
require_once(__DIR__."/../product_management/functions/product_function.php");

$productF = new product_function();

	if(isset($_GET['update'])){
	    $sql = "SELECT `prodet_id`,`slug` FROM `proudct_detail`";
        $pro_Ids = $dbF->getRows($sql);

        if($dbF->rowCount > 0){

            foreach ($pro_Ids as $key => $value) {
                $pId = $value['prodet_id'];

                $sql1 = "SELECT * FROM `currency`";
                $cur_ids = $dbF->getRows($sql1);

                foreach ($cur_ids as $key => $val) {

                    $currencyId = $val['cur_id'];

                    $pPriceData = $productF->productPrice($pId, $currencyId);

                    //$pPriceData Return , currency id,international shipping, price, id,
                    $pPriceActual = $pPriceData['propri_price'];
                    $pPrice = $pPriceActual;

                    $discount = $productF->productDiscount($pId, $currencyId);
                    @$discountFormat = $discount['discountFormat'];
                    @$discountP = $discount['discount'];

                    $discountPrice = $productF->discountPriceCalculation($pPriceActual, $discount);
                    $newPrice = $pPriceActual - $discountPrice;

                    $pPrice = empty($pPrice) ? 0 : $pPrice;
                    $newPrice = empty($newPrice) ? 0 : $newPrice;

                    $array = array($newPrice,$pId);

                    $sql1 = "UPDATE `proudct_detail` SET `product_discount_$currencyId` = ? WHERE `prodet_id` = ?";
                    $pro_Ids1 = $dbF->setRow($sql1,$array);
                }

            }
        } 

	}

?>