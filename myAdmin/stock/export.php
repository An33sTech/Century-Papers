<?php
############ Export table into CSV ############
 echo $importEmailyes = ob_get_clean();
require_once("../global.php");
 global $productF;
        $functions->includeAdminFile("product_management/functions/product_function.php");

        $productF = new product_function();


// var_dump($_POST['multi_select']);



// $dbF->prnt($_POST['multi_select']);



 //$delimiter
if (isset($_POST['csvExportnew'])) {

$_d = ",";



####### CSV file Headings, for excel edit mode.
$file_heading = "id{$_d}titel{$_d}l채nk{$_d}bildl채nk{$_d}pris{$_d}beskrivning";


// $file_heading .= "{$_d}beskrivning";
$file_heading .= "\n";

$output = $file_heading;

####### get data from DB
// $sql    = "SELECT *,
//             (SELECT prodet_name FROM proudct_detail  WHERE prodet_id = qty_product_id   ) as product,
//               (SELECT prosiz_name FROM product_size    WHERE prosiz_id = qty_product_scale) as size,
//               (SELECT proclr_name FROM product_color   WHERE propri_id = qty_product_color) as color
//            FROM product_inventory"; // ORDER BY qty_pk DESC


$sql    = "SELECT prodet_id,slug,prodet_name,prodet_shortDesc,(SELECT image FROM product_image WHERE product_id = prodet_id order by sort ASC limit 1)  as image, (SELECT propri_price FROM product_price WHERE propri_prodet_id = prodet_id limit 1)  as Actual_price FROM proudct_detail"; // ORDER BY qty_pk DESC



$data   = $dbF->getRows($sql);

foreach ($data as $val) {

    // $qty_pk         = $val['qty_pk'];
    // $qty_store_id   = $val['qty_store_id'];
    // $qty_product_id = $val['qty_product_id'];
    // $qty_product_scale = $val['qty_product_scale'];
    // $qty_product_color = $val['qty_product_color'];
    // $qty_item       = $val['qty_item'];
    // $location       = $val['location'];
    // $product_store_hash = $val['product_store_hash'];
    // $product        = $val['product'];

    $id        = $val['prodet_id'];
    $Actual_price        = $val['Actual_price'];
    $pPrice        = $val['Actual_price'];
    $slug        = WEB_URL."/".$val['slug'];
    $image        = WEB_URL."/images/".$val['image'];
    $product_name   = $functions->unserializeTranslate($val['prodet_name']);
    $product_name   = specialChar_to_english_letters($product_name);

 $product_name = str_replace(array('.',"\n", "\t", "\r",'<br />',','), ' ', $product_name);
  // $product_name   =   str_replace(",", " ", $product_name);
    // $size           = $val['size'];
    // $color          = $val['color'];
    $prodet_shortDesc   = $functions->unserializeTranslate($val['prodet_shortDesc']);
    $prodet_shortDesc   = strip_tags($prodet_shortDesc);
    $prodet_shortDesc   = specialChar_to_english_letters($prodet_shortDesc);
    $prodet_shortDesc = str_replace(array('.',"\n", "\t", "\r",'<br />',','), ' ', $prodet_shortDesc);

    $curr_id  = $_POST['cur_id'];

    $discount = $productF->productDiscount($id, $curr_id);
    @$discountP = $discount['discount'];




  $discountPrice = $productF->discountPriceCalculation($Actual_price, $discount);
        $newPrice = $Actual_price - $discountPrice;

        $isSale = false;
        if (isset($discount['isSale']) && $discount['isSale'] == '1') {
            $isSale = true;
        }

        $isDiscount = false;
        if ($newPrice != $pPrice) {
            $isDiscount = true;
        }

        $pPrice = empty($pPrice) ? 0 : $pPrice;
        $newPrice = empty($newPrice) ? 0 : $newPrice;
        $hasDiscount = false;

        if ($newPrice != $pPrice && $Actual_price != '') {
            $hasDiscount = true;
            $oldPriceNormal = $pPrice;
            $newPriceNormal = $newPrice;                      
        } else {
            $oldPriceNormal = "";
            $newPriceNormal = $pPrice;
            $oldPriceDiv = "";
            
        }
    ####### CSV single row...
   $output .= "{$id}{$_d}{$product_name}{$_d}{$slug}{$_d}{$image}{$_d}{$newPriceNormal}{$_d}{$prodet_shortDesc}";

    // $output .= "{$_d}{$prodet_shortDesc}";


    // $output .= "{$qty_pk}{$_d}{$qty_store_id}{$_d}{$qty_product_id}{$_d}{$qty_product_scale}{$_d}{$qty_product_color}{$_d}{$product_store_hash}";
    $output .= "\n";
}



####### Download csv File...
$filename = "feedfile.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;
exit;
}//if end on submit


if (isset($_POST['csvExportnewsizecolor'])) {

$_d = ",";

$multiarray = $_POST['multi_select'];


 // $dbF->prnt($multiarray);


####### CSV file Headings, for excel edit mode.

$file_heading = "id{$_d}titel{$_d}l채nk{$_d}bildl채nk{$_d}beskrivning";



if(in_array("Size", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Size";

}


if(in_array("Color", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Color";

}

$file_heading .= "{$_d}pris";


if (in_array("Model.No", $multiarray))
{

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Model.No";
}

if(in_array("Label", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Label";

}
if(in_array("Categories", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}categories_id";

}




$file_heading .= "{$_d}qty_item{$_d}location{$_d}qty_store_id";






// else
// {

// }



// $file_heading .= "prodet_shortDesc";
$file_heading .= "\n";

$output = $file_heading;

####### get data from DB
$sql    = "SELECT *,

(SELECT prodet_id FROM proudct_detail  WHERE prodet_id = qty_product_id   ) as prodet_id,
(SELECT slug FROM proudct_detail  WHERE prodet_id = qty_product_id   ) as slug,
(SELECT prodet_name FROM proudct_detail  WHERE prodet_id = qty_product_id   ) as prodet_name,
(SELECT prodet_shortDesc FROM proudct_detail  WHERE prodet_id = qty_product_id   ) as prodet_shortDesc,


(SELECT image FROM product_image WHERE product_id = qty_product_id order by sort ASC limit 1)  as image, 
(SELECT setting_val FROM product_setting WHERE p_id = qty_product_id and setting_name = 'Model')  as Model, 
(SELECT setting_val FROM product_setting WHERE p_id = qty_product_id and setting_name = 'label')  as Label,

(SELECT procat_cat_id FROM product_category WHERE procat_prodet_id = qty_product_id )  as categories_id,

(SELECT propri_price FROM product_price WHERE propri_prodet_id = qty_product_id limit 1)  as Actual_price,


(SELECT prosiz_name FROM product_size    WHERE prosiz_id = qty_product_scale) as size,
(SELECT proclr_name FROM product_color   WHERE propri_id = qty_product_color) as color


           FROM product_inventory"; // ORDER BY qty_pk DESC


// $sql    = "
//  SELECT prodet_id,slug,prodet_name,prodet_shortDesc,
// (SELECT image FROM product_image WHERE product_id = prodet_id order by sort ASC limit 1)  as image, 
// (SELECT setting_val FROM product_setting WHERE p_id = prodet_id and setting_name = 'Model')  as Model, 
// (SELECT setting_val FROM product_setting WHERE p_id = prodet_id and setting_name = 'label')  as Label, 
// (SELECT propri_price FROM product_price WHERE propri_prodet_id = prodet_id limit 1)  as Actual_price FROM proudct_detail"; // ORDER BY qty_pk DESC



$data   = $dbF->getRows($sql);

foreach ($data as $val) {

    $qty_pk         = $val['qty_pk'];
    $qty_store_id   = $val['qty_store_id'];
    $qty_product_id = $val['qty_product_id'];
    $qty_product_scale = $val['qty_product_scale'];
    $qty_product_color = $val['qty_product_color'];
    $qty_item       = $val['qty_item'];
    $location       = $val['location'];
    $product_store_hash = $val['product_store_hash'];
    $categories_id = $val['categories_id'];
    // $product        = $val['product'];


  $categories_id = str_replace(',','-', $categories_id);



    $id        = $val['prodet_id'];
    $Actual_price        = $val['Actual_price'];
    $pPrice        = $val['Actual_price'];
    $pModel        = $val['Model'];
    $pLabel    = $val['Label'];
    $slug        = WEB_URL."/".$val['slug'];
    $image        = WEB_URL."/images/".$val['image'];
    $product_name   = $functions->unserializeTranslate($val['prodet_name']);
    $product_name   = specialChar_to_english_letters($product_name);
      // $product_name   =   str_replace(",", " ", $product_name);

       $product_name = str_replace(array('.',"\n", "\t", "\r",'<br />',','), ' ', $product_name);

       
    $pSize           = $val['size'];
    $pColor          = $val['color'];
    $prodet_shortDesc   = $functions->unserializeTranslate($val['prodet_shortDesc']);
    $prodet_shortDesc   = strip_tags($prodet_shortDesc);
    $prodet_shortDesc   = specialChar_to_english_letters($prodet_shortDesc);
    $prodet_shortDesc = str_replace(array('.',"\n", "\t", "\r",'<br />',','), ' ', $prodet_shortDesc);
    $curr_id  = $_POST['cur_ids'];

        $discount = $productF->productDiscount($id, $curr_id);
        @$discountP = $discount['discount'];




  $discountPrice = $productF->discountPriceCalculation($Actual_price, $discount);
        $newPrice = $Actual_price - $discountPrice;

        $isSale = false;
        if (isset($discount['isSale']) && $discount['isSale'] == '1') {
            $isSale = true;
        }

        $isDiscount = false;
        if ($newPrice != $pPrice) {
            $isDiscount = true;
        }

        $pPrice = empty($pPrice) ? 0 : $pPrice;
        $newPrice = empty($newPrice) ? 0 : $newPrice;

        $hasDiscount = false;

        if ($newPrice != $pPrice && $Actual_price != '') {
            $hasDiscount = true;
            $oldPriceNormal = $pPrice;
            $newPriceNormal = $newPrice;
         
        } else {
            $oldPriceNormal = "";
            $newPriceNormal = $pPrice;                       
        }







  
  ####### CSV single row...
     $output .= "{$id}{$_d}{$product_name}{$_d}{$slug}{$_d}{$image}{$_d}{$prodet_shortDesc}";


    if(in_array("Size", $multiarray)){

####### CSV single row...
     $output .= "{$_d}{$pSize}";

  }




    if(in_array("Color", $multiarray)){

####### CSV single row...
     $output .= "{$_d}{$pColor}";

  }


   $output .= "{$_d}{$newPriceNormal}";



if (in_array("Model.No", $multiarray))
  {

  ####### CSV single row...
     $output .= "{$_d}{$pModel}";
  }

  if(in_array("Label", $multiarray)){

####### CSV single row...
     $output .= "{$_d}{$pLabel}";

  }



if(in_array("Categories", $multiarray)){

####### CSV file Headings, for excel edit mode.
$output .= "{$_d}{$categories_id}";

}




$output .= "{$_d}{$qty_item}{$_d}{$location}{$_d}{$qty_store_id}";
$output .= "\n";
}



####### Download csv File...
$filename = "feedfile2.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;
exit;
}//if end on submit


if (isset($_POST['csvExportnewsizecolorAll'])) {

$_d = ",";

$multiarray = $_POST['multi_select'];


 // $dbF->prnt($multiarray);


####### CSV file Headings, for excel edit mode.

$file_heading = "id{$_d}product{$_d}link{$_d}imgLink{$_d}beskrivning";



if(in_array("Size", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Size";

}


if(in_array("Color", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Color";

}

$file_heading .= "{$_d}pris";


if (in_array("Model.No", $multiarray))
{

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Model.No";
}

if(in_array("Label", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}Label";

}
if(in_array("Categories", $multiarray)){

####### CSV file Headings, for excel edit mode.
$file_heading .= "{$_d}categories_id";

}




$file_heading .= "{$_d}qty_item{$_d}location{$_d}qty_store_id";






// else
// {

// }



// $file_heading .= "prodet_shortDesc";
$file_heading .= "\n";

$output = $file_heading;

####### get data from DB
$sql    = "SELECT *,(SELECT image FROM product_image WHERE product_id = prodet_id order by sort ASC limit 1)  as image, 
(SELECT setting_val FROM product_setting WHERE p_id = prodet_id and setting_name = 'Model' limit 1)  as Model, 
(SELECT setting_val FROM product_setting WHERE p_id = prodet_id and setting_name = 'label' limit 1)  as Label,
(SELECT procat_cat_id FROM product_category WHERE procat_prodet_id = prodet_id limit 1)  as categories_id,
(SELECT propri_price FROM product_price WHERE propri_prodet_id = prodet_id limit 1)  as Actual_price FROM proudct_detail"; // ORDER BY qty_pk DESC


// $sql    = "
//  SELECT prodet_id,slug,prodet_name,prodet_shortDesc,
// (SELECT image FROM product_image WHERE product_id = prodet_id order by sort ASC limit 1)  as image, 
// (SELECT setting_val FROM product_setting WHERE p_id = prodet_id and setting_name = 'Model')  as Model, 
// (SELECT setting_val FROM product_setting WHERE p_id = prodet_id and setting_name = 'label')  as Label, 
// (SELECT propri_price FROM product_price WHERE propri_prodet_id = prodet_id limit 1)  as Actual_price FROM proudct_detail"; // ORDER BY qty_pk DESC



$data   = $dbF->getRows($sql);
 // $dbF->prnt($sql);
foreach ($data as $val) {

    // $qty_pk         = $val['qty_pk'];

    // $qty_product_id = $val['qty_product_id'];
    // $qty_product_scale = $val['qty_product_scale'];
    // $qty_product_color = $val['qty_product_color'];

    // $product_store_hash = $val['product_store_hash'];
    $categories_id = $val['categories_id'];
    // $product        = $val['product'];


  $categories_id = str_replace(',','-', $categories_id);



    $id        = $val['prodet_id'];



    $Actual_price        = $val['Actual_price'];
    $pPrice        = $val['Actual_price'];
    $pModel        = $val['Model'];
    $pLabel    = $val['Label'];
    $slug        = WEB_URL."/".$val['slug'];
    $image        = WEB_URL."/images/".$val['image'];
    $product_name   = $functions->unserializeTranslate($val['prodet_name']);
    $product_name   = specialChar_to_english_letters($product_name);
      $product_name   =   str_replace(",", " ", $product_name);
    // $pSize           = $val['size'];
    // $pColor          = $val['color'];

        $pSize           = 0;
    $pColor          = 0;


    $prodet_shortDesc   = $functions->unserializeTranslate($val['prodet_shortDesc']);
    $prodet_shortDesc   = strip_tags($prodet_shortDesc);
    $prodet_shortDesc   = specialChar_to_english_letters($prodet_shortDesc);
    $prodet_shortDesc = str_replace(array('.',"\n", "\t", "\r",'<br />',','), ' ', $prodet_shortDesc);
    $curr_id  = $_POST['cur_ids'];

        $discount = $productF->productDiscount($id, $curr_id);
        @$discountP = $discount['discount'];




  $discountPrice = $productF->discountPriceCalculation($Actual_price, $discount);
        $newPrice = $Actual_price - $discountPrice;

        $isSale = false;
        if (isset($discount['isSale']) && $discount['isSale'] == '1') {
            $isSale = true;
        }

        $isDiscount = false;
        if ($newPrice != $pPrice) {
            $isDiscount = true;
        }

        $pPrice = empty($pPrice) ? 0 : $pPrice;
        $newPrice = empty($newPrice) ? 0 : $newPrice;

        $hasDiscount = false;

        if ($newPrice != $pPrice && $Actual_price != '') {
            $hasDiscount = true;
            $oldPriceNormal = $pPrice;
            $newPriceNormal = $newPrice;
         
        } else {
            $oldPriceNormal = "";
            $newPriceNormal = $pPrice;                       
        }




// $q = "SELECT * FROM product_inventory WHERE qty_product_id = '$' and qty_product_scale = '$' and qty_product_color = '$' and qty_store_id = '$' ";

// $dataX   = $dbF->getRow($q);





// $qty_item       = $dataX['qty_item'];
// $location       = $dataX['location'];
// $qty_store_id   = $dataX['qty_store_id'];

$qty_item       = 0;
$location       = 0;
$qty_store_id   = 0;

  $q = "SELECT * FROM product_size WHERE prosiz_prodet_id = '$id' and prosiz_cur_id = '$curr_id' ";

$dataX   = $dbF->getRows($q);


foreach ($dataX as $keyX => $valueX) {
  
$pSize = $valueX['prosiz_name'];
$prosiz_id = $valueX['prosiz_id'];




  $qQ = "SELECT * FROM product_inventory WHERE qty_product_id = '$id' and qty_product_scale = '$prosiz_id' and qty_product_color = 0";

$dataXQ   = $dbF->getRow($qQ);


// $qty_item = $dataXQ['qty_item'];
// $location = $dataXQ['location'];
// $qty_store_id = $dataXQ['qty_store_id'];

$qty_item        = empty($dataXQ['qty_item'])      ? "0"    : ($dataXQ['qty_item']);

$location        = empty($dataXQ['location'])      ? "0"    : ($dataXQ['location']);
$qty_store_id        = empty($dataXQ['qty_store_id'])      ? "0"    : ($dataXQ['qty_store_id']);



  ####### CSV single row...
     $output .= "{$id}{$_d}{$product_name}{$_d}{$slug}{$_d}{$image}{$_d}{$prodet_shortDesc}";
    if(in_array("Size", $multiarray)){
     $output .= "{$_d}{$pSize}";
     }
    if(in_array("Color", $multiarray)){
     $output .= "{$_d}{$pColor}";
     }
    $output .= "{$_d}{$newPriceNormal}";
if (in_array("Model.No", $multiarray))
  {
     $output .= "{$_d}{$pModel}";
  }
  if(in_array("Label", $multiarray)){
    $output .= "{$_d}{$pLabel}";
  }
if(in_array("Categories", $multiarray)){
$output .= "{$_d}{$categories_id}";
}
$output .= "{$_d}{$qty_item}{$_d}{$location}{$_d}{$qty_store_id}";
$output .= "\n";
  ####### CSV single row...


}












  $q = "SELECT * FROM product_color WHERE proclr_prodet_id = '$id' and proclr_cur_id = '$curr_id' ";

$dataX   = $dbF->getRows($q);


foreach ($dataX as $keyX => $valueX) {
  
$pColor = $valueX['color_name'];
$propri_id = $valueX['propri_id'];




  $qQ = "SELECT * FROM product_inventory WHERE qty_product_id = '$id' and qty_product_scale = 0 and qty_product_color = '$propri_id'";

$dataXQ   = $dbF->getRow($qQ);


// $qty_item = $dataXQ['qty_item'];
// $location = $dataXQ['location'];
// $qty_store_id = $dataXQ['qty_store_id'];


$qty_item        = empty($dataXQ['qty_item'])      ? "0"    : ($dataXQ['qty_item']);

$location        = empty($dataXQ['location'])      ? "0"    : ($dataXQ['location']);
$qty_store_id        = empty($dataXQ['qty_store_id'])      ? "0"    : ($dataXQ['qty_store_id']);


  ####### CSV single row...
     $output .= "{$id}{$_d}{$product_name}{$_d}{$slug}{$_d}{$image}{$_d}{$prodet_shortDesc}";
    if(in_array("Size", $multiarray)){
     $output .= "{$_d}{$pSize}";
     }
    if(in_array("Color", $multiarray)){
     $output .= "{$_d}{$pColor}";
     }
    $output .= "{$_d}{$newPriceNormal}";
if (in_array("Model.No", $multiarray))
  {
     $output .= "{$_d}{$pModel}";
  }
  if(in_array("Label", $multiarray)){
    $output .= "{$_d}{$pLabel}";
  }
if(in_array("Categories", $multiarray)){
$output .= "{$_d}{$categories_id}";
}
$output .= "{$_d}{$qty_item}{$_d}{$location}{$_d}{$qty_store_id}";
$output .= "\n";
  ####### CSV single row...


}




}

 // $dbF->prnt($output);

####### Download csv File...
$filename = "allStockProducts.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;
exit;
}//if end on submit
?>