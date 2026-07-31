<?php
include(__DIR__ . "/../../global.php");

// Encrypt From here
global $webClass, $dbF;
$WEB_URL = WEB_URL;

function productAjaxCallEndOfThisPage()
{

  if (isset($_GET['page'])) {

    $page = $_GET['page'];



    $ajax = new product_ajax();

    switch ($page) {

      case 'logoTag':

        $ajax->logoTag();

        break;

      case 'addByQty':

        $ajax->AddPlusToCart();

        break;

      case 'AddToCart':

        $ajax->AddToCart();

        break;

      case 'AddToCartDeal':

        $ajax->AddToCartDeal();

        break;

      case 'AddToCartCustom':

        $ajax->AddToCartCustom();

        break;

      case 'AddToWishList':

        $ajax->AddToWishList();

        break;

      case 'RemoveToWishList':

        $ajax->RemoveToWishList();

        break;

      case 'AddPlusToCart':

        $ajax->AddPlusToCart();

        break;


      case 'minusFromCart':

        $ajax->minusFromCart();

        break;

      case 'cartProductRemove':

        $ajax->cartProductRemove();

        break;

      case 'setCookie':

        $ajax->setCookie();

        break;

      case 'dontForgetToBuy':
        $ajax->dontForgetToBuy();
        break;

      case 'more_product':

        $ajax->more_product();

        break;

      case 'getSearchJson':

        $ajax->getSearchJson();

        break;


      case 'getSearchJsonDiv':

        $ajax->getSearchJsonDiv();

        break;

      case 'getSearchJsonDiv123':

        $ajax->getSearchJsonDiv123();

        break;


      case 'getSearchJsontxt_search_cat':

        $ajax->getSearchJsontxt_search_cat();

        break;



      case 'sizeCartUpdate':

        $ajax->sizeCartUpdate();

        break;

      case 'cartSmallProduct':

        $ajax->cartSmallProduct();

        break;

      case 'addRating':

        $ajax->addRating();

        break;

      case 'orderProducts_1':

        $ajax->get_order_products_1();

        break;

      case 'orderProducts':

        $ajax->get_order_products();

        break;


      case 'allPublishProducts_with_size_color':

        $ajax->get_allPublishProducts_with_size_color();

        break;

      case 'allPublishProducts_with_size_color1':

        $ajax->get_allPublishProducts_with_size_color1();

        break;



      case 'searched_products':

        $ajax->get_searched_products();

        break;

      case 'sizes_colors':

        $ajax->get_product_sizes_colors();

        break;

      case 'get_currency':

        $ajax->get_order_currency();

        break;


      case 'cart_side_view':

        $ajax->cart_checkout_side_view();

        break;

      case 'set_unset_coupon':

        $ajax->set_unset_coupon();

        break;

      case 'set_unset_giftcard':

        $ajax->set_unset_giftcard();

        break;

      case 'cart_side_submit':

        $ajax->cart_side_submit();

        break;

      case 'cart_side_load_order_file':

        $ajax->cart_side_load_order_file();

        break;

      case 'longTimeSubscribe':
        $ajax->longTimeSubscribe();
        break;



      case 'cart_denied':
        $ajax->cart_denied();
        break;


      case 'something_extra_products':
        $ajax->something_extra_products();
        break;

      case 'loadCombineWithProducts':
        $ajax->loadCombineWithProducts();
        break;

      case 'klarnaAuthResponce':
        $ajax->klarnaAuthResponce();
        break;

      case 'setExpressMode':
        $ajax->setExpressMode();
        break;

      case 'updateCart':
        $ajax->updateCart();
        break;
    }
  }
}



class product_ajax extends object_class
{

  public $productClass;

  public $webClass;



  public function __construct()
  {

    parent::__construct('3');



    $this->functions->require_once_custom('webProduct_functions');

    $this->productClass = new webProduct_functions();

    $this->webClass = $GLOBALS['webClass'];



    /**

     * MultiLanguage keys Use where echo;

     * define this class words and where this class will call

     * and define words of file where this class will called

     **/

    global $_e;

    $_w = array();

    $_w['Quantity exceed stock quantity Limit QTY : {{qty}}'] = '';

    $_w['Product out of stock'] = '';

    $_w['Product Add In Cart Fail Please Try Again'] = '';

    $_w['Product Inventory Error,Please Try Again'] = '';

    $_w['Add To Cart Fail Please Try Again'] = '';

    $_w['Three For Two Category'] = '';
    $_w['Related Products'] = '';
    $_w['Quantity Updated Successfully'] = '';

    $_w['Bundle Discount'] = '';

    $_e = $this->dbF->hardWordsMulti($_w, currentWebLanguage(), 'Web Product Ajax');
  }



  public function logoTag()
  {

    //this function will not work if md5 footer work ok...

    //this work when logo hide with css or JS

    if (!isset($_SESSION['logoMail'])) {

      //one time email send per session

      $email = base64_decode($this->functions->developer_setting("emailImedia"));

      $to = $email;

      $subject = "iMedia tag remove On" . $this->functions->webName;

      $message = "This Is alert Msg Send From Ajax, iMedia Tag Not Found On " . $this->functions->webName . "

<br>URL : " . WEB_URL . "

<br>URI : " . $this->functions->currentUrl(false) . "

<br>DateTime : " . date('Y-m-d h:i:a');



      $this->functions->send_mail($to, $subject, $message);

      $_SESSION['logoMail'] = '1';
    }
  }



  public function more_product()
  {

    $limitFrom = intval($_POST['limitFrom']);

    $limitTo = intval($_POST['limitTo']); //Was not working

    $limit = $this->productClass->productLimitShowOnWeb();

    $viewType = isset($_POST['view']) ? (string) ($_POST['view']) : '';





    if (!isset($_POST['id'])) {

      echo "0";

      exit;
    }

    $id = $_POST['id'];



    $data = $this->productClass->functions->getTempTableVal($id);

    $sql = $data['value'];

    $sql .= " LIMIT $limitFrom,$limit";



    $productIds = $this->dbF->getRows($sql);

    $products = "";

    if ($this->dbF->rowCount > 0 && $productIds != null) {

      $this->productClass->productSerial = $limitFrom;

      foreach ($productIds as $p) {

        if (isset($_GET['productDeals'])) {

          $products .= $this->productClass->pBoxDeal($p);
        } else {

          $products .= $this->productClass->pBox($p['prodet_id'], false, $viewType);
        }
      }

      echo $products;
    } else {

      echo "0";
    }
  }




  public function sizeCartUpdate()
  {
    global $_e;

    //send with ajax from add to cart function in js.

    if (
      isset($_POST['pId']) && isset($_POST['storeID']) &&

      isset($_POST['scaleId']) && isset($_POST['cartId'])

    ) {

      @$cartId = $_POST['cartId'];

      @$pId = $_POST['pId'];

      @$storeId = $_POST['storeID'];

      @$scaleId = $_POST['scaleId'];

      @$colorId = $_POST['colorId'];


      $userId = webUserId();
      $tempHash = $userId;
      $TempUserId = webTempUserId();
      $tempOld = webUserOldTempId();



      if ($tempOld != "") {

        //user is login ... so i get his old temp id,, for hask key match...

        $tempHash = $tempOld;
      }



      if (intval($userId) < 1) {

        $tempHash = $TempUserId;
      }

      @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId . ":" . $tempHash;

      $hashCart = md5($hashVal);


      $sql = "UPDATE `cart` SET
`scaleId`   =   ?,
`hash`    =   ?
WHERE `id`  = ?";




      $array = array($scaleId, $hashCart, $cartId);


      $this->dbF->setRow($sql, $array);

?>

      <?php

    } else {
    }
  }

  public function loadCombineWithProducts()
  {
    global $_e;
    $combineWith = json_decode($_POST["combineId"]);
    $html = '';
    if (count($combineWith) > 0) {
      foreach ($combineWith as $key => $value) {
        if ($key < 3) {
          $categ_namee = translateFromSerialize($this->productClass->getCategoryNameNew($value));
          $catLinkk = $this->productClass->getSeoLink($value);
          $html .= '<div class="col3_main combine_with_' . $key . '">
                                <h2>' . _uc($_e['Related Products']) . ' <u><a href="' . $catLinkk . '">' . $categ_namee . '</a></u></h2>
                                <div class="col3_all owl-carousel owl-theme">' .
            $this->productClass->productsCombineWithPrint($value, false, '1') .
            '</div>
                              </div>';
        }
      }
    }
    echo $html;
  }

  public function AddToWishList()
  {

    if (isset($_POST['pId'])) {

      $pId = $_POST['pId'];

      $userId = $this->productClass->webUserId();

      $TempUserId = $this->productClass->webTempUserId();



      $sql = "SELECT * FROM cartwishlist WHERE pId =? AND userId = ? AND tempUser = ?";

      $check = $this->dbF->getRow($sql, array($pId, $userId, $TempUserId));

      if ($this->dbF->rowCount > 0) {

        echo "0";

        return false;
      }



      $sql = "INSERT INTO cartwishlist

(pId,userId,tempUser)

values (?,?,?)";

      $this->dbF->setRow($sql, array($pId, $userId, $TempUserId));

      if ($this->dbF->rowCount > 0) {

        echo "1";
      } else {

        echo "0";
      }
    }
  }



  public function RemoveToWishList()
  {

    if (isset($_POST['pId'])) {

      $pId = $_POST['pId'];

      $userId = $this->productClass->webUserId();

      $TempUserId = $this->productClass->webTempUserId();



      $sql = "SELECT * FROM cartwishlist WHERE pId =? AND userId = ? AND tempUser = ?";

      $check = $this->dbF->getRow($sql, array($pId, $userId, $TempUserId));

      if (!$this->dbF->rowCount > 0) {

        echo "0";

        return false;
      }



      $sql = "DELETE FROM cartwishlist WHERE pId = '$pId' AND userId = '$userId' AND tempUser = '$TempUserId'";

      $this->dbF->setRow($sql);

      if ($this->dbF->rowCount > 0) {

        echo "1";
      } else {

        echo "0";
      }
    }
  }



  public function AddToCart()
  {



    global $_e;

    //send with ajax from add to cart function in js.


    if (isset($_GET['donotForget'])) {
    }

    if (
      isset($_POST['pId']) && isset($_POST['storeID']) &&

      isset($_POST['scaleId']) && isset($_POST['colorId'])

    ) {

      $pId = $_POST['pId'];

      $storeId = $_POST['storeID'];

      $scaleId = $_POST['scaleId'];

      $colorId = $_POST['colorId'];

      $salePrice = $_POST['salePrice'];

      $customQty = $_POST['customQty'] ?? '';

      $scaleName = $_POST['scaleName'] ?? '';

      $result = array();



      $hasCustomQty = false;

      if (!empty($customQty)) {

        $hasCustomQty = true;
      }

      $customQty = intval($customQty);



      $userId = webUserId();

      $tempHash = $userId;

      $TempUserId = webTempUserId();



      $tempOld = webUserOldTempId();



      if ($tempOld != "") {

        //user is login ... so i get his old temp id,, for hask key match...

        $tempHash = $tempOld;
      }



      if (intval($userId) < 1) {
        $tempHash = $TempUserId;
      }




      //Hash for cart

      @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId . ":" . $tempHash;

      $hashCart = md5($hashVal);



      //hash for inventory table

      @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;

      $hash = md5($hashVal);



      $stock = false;
      $sqls = "SELECT set_id FROM `product_setting` where setting_name='freeGift' and setting_val='1' and p_id ='$pId'";
      $this->dbF->getRow($sqls);
      if ($this->dbF->rowCount > 0) {
        $stock = true;
      }






      //check if stock work, then qty check

      if ($this->functions->developer_setting('product_check_stock') == '1' || $stock == true) {

        //Check stock in store

        $sqlCheck = "SELECT `qty_item`,`product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = '$hash'";

        $totalQty = $this->dbF->getRow($sqlCheck);

        $totalQty = $totalQty['qty_item'];



        if ($totalQty <= 0) {

          if (!isset($_POST['free_gift']))

            $result['status'] = false;
        }



        if ($hasCustomQty) {

          if ($customQty > $totalQty) {

            if (!isset($_POST['free_gift']))

              $result['status'] = false;
          }
        }
      }



       $sqlDCheck = "SELECT `prodet_name` FROM `proudct_detail` WHERE `prodet_id` = '$pId'";

        $productNamedata = $this->dbF->getRow($sqlDCheck);

        $productName = $productNamedata['prodet_name'];

     echo "
        <script>
        fbq('track', 'AddToCart', {
            content_name: '{$productName}',
            content_size: '{$scaleName}',
            content_ids: ['{$pId}'],
            content_type: 'product',
            value: {$salePrice},
            currency: 'PKR'
        });
        </script>";




      $customId = empty($_POST['customId']) ? "0" : $_POST['customId'];



      if ($this->dbF->rowCount > 0 || $this->functions->developer_setting('product_check_stock') == '0') {

        //check cart already or not

        $sqlCheck = "SELECT * FROM `cart` WHERE `hash` = '$hashCart'";

        $cartData = $this->dbF->getRow($sqlCheck);



        $checkout = '';



        ############### CHECKOUT OFFER ###############

        if (isset($_GET['checkout'])) {

          //Checkout offer on special discount

          $checkout = " , `checkout` = '1'";
        }

        ############### FREE GIFT ###################
        elseif (isset($_POST['free_gift'])) {

          //Free gift auto add in cart when read at special price

          $checkout = " , `checkout` = '2'";
        }

        ############### EXTRA SALES FEATURE #############
        elseif (!empty($salePrice) && $salePrice != '') {

          //Free gift auto add in cart when read at special price

          $checkout = " , `checkout` = '3'";
        }

        ############### CHECKOUT OFFER ###############

        if (isset($_GET['donotForget'])) {

          //Checkout offer on special discount

          $checkout = " , `checkout` = '4'";
        }





        if ($this->dbF->rowCount > 0) {

          $qty = intval($cartData['qty']);

          if ($hasCustomQty) {

            $qty = $customQty;
          } else {

            $qty++;
          }



          //check if stock work, then qty check

          if ($this->functions->developer_setting('product_check_stock') == '1' || $stock == true) {

            if ($qty > $totalQty) {

              if (!isset($_POST['free_gift']))

                $result['status'] = false;
            }
          }



          $cartId = $cartData['id'];

          $sqlQtyJoin = "`qty` = qty+1, ";


          $sql = "UPDATE `cart` SET

$sqlQtyJoin

`userId` = ?,
`scaleName` = ?

WHERE `id`= ?";

          $this->dbF->setRow($sql, array($userId, $scaleName, $cartId));
        } else {



          $sqlQtyJoin = "qty = '1',";

          if ($hasCustomQty) {

            $sqlQtyJoin = "qty = '$customQty',";
          }


          $salePriceColumn = '';

          if ($salePrice != '') {

            $salePriceColumn = ", `salePrice` = '$salePrice'";
          }


          $sql = "INSERT INTO cart SET pId = ?, scaleId = ?, scaleName = ?, colorId = ?, storeId = ?, customId = ?, $sqlQtyJoin userId  = ?, tempUser = ?, hash = ? $salePriceColumn $checkout";

          $array = array($pId, $scaleId, $scaleName, $colorId, $storeId, $customId, $userId, $TempUserId, $hashCart);



          if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {
            $inv = $this->functions->decode($_SESSION['freeGift_ai_id']);
            $sqlq = "SELECT `txt_inv_pro_qty`,`pro_ids` FROM `free_gift_inv` WHERE `id` = ?";
            $dataq = $this->dbF->getRow($sqlq, array($inv));
            if ($this->dbF->rowCount > 0) {
              $qqty = $dataq['txt_inv_pro_qty'];
              $pro_ids = $dataq['pro_ids'];
              $sqlqi = "SELECT `pId`,`qty` FROM `cart` WHERE `tempUser` = ?";
              $dataqi = $this->dbF->getRows($sqlqi, array($TempUserId));
              $items = array();
              $ttl = 1;

              foreach ($dataqi as $valq) {


                $ttl += $valq['qty'];


                $items[] = $valq['pId'];
              }
              $arrCart = array_unique($items);

              $string = count($arrCart);
              $string = ($ttl);




              $applied_for_fields_array = explode(',', $pro_ids);

              $itemsArr = array();
              foreach ($applied_for_fields_array as $field) {
                $itemsArr[] = $field;
              }



              $diff_result = array_diff($arrCart, $itemsArr);

              $diff_result = array_values($diff_result);

              if ($qqty < $string && $qqty != $string) {
              } else {

                $this->dbF->setRow($sql, $array);
              }

              for ($i = 0; $i < count($diff_result); $i++) {

                $sqll = "DELETE FROM cart WHERE pId = '" . $diff_result[$i] . "' and  tempUser = '" . $TempUserId . "'";

                $this->dbF->setRow($sqll);


                # code...
              }
            }
          } else {





            $this->dbF->setRow($sql, $array);



            // 	# code...


          }
        }



        if (!$this->dbF->rowCount) {

          //this print when add to cart fail or update fail..

          if ($hasCustomQty) {

            if (isset($qty) && $qty == $customQty) {

              $result['status'] = true;
            }
          }



          if (!isset($_POST['free_gift'])) {


            $result['status'] = false;
          }
        } else {
          //success full add or update

          if (!isset($_POST['free_gift'])) {
            $doNotForget_offer = $this->productClass->doNotForgetOffer($pId);

            $result['donotForget'] = $doNotForget_offer;

            $result['status'] = '1';
          }

          $result['status'] = true;
        }
      } else {

        //this print when inventory record not found, and $totalQty condition if true, that may be not possibles

        if (!isset($_POST['free_gift'])) {



          $result['status'] = false;
        }
      }
    } else {

      //function call with illegal parameters... 99% not possible, if user not try to hack.

      if (!isset($_POST['free_gift'])) {

        $result['status'] = false;
      }
    }

    // popupForget

    $result['forgetPopup'] = $this->productClass->doNotForgetOfferSingleProductPopUp($pId);




    echo json_encode($result);
  }



  public function AddToCartDeal()
  {



    $dealId = $_POST['dealId'];

    $json = $_POST['deal'];

    $json = trim($json, ",");

    $jsonT = "[$json]";

    $json = json_decode($jsonT, true);

    $storeId = $json[0]['storeId'];



    @$customQty = $_POST['customQty'];



    $hasCustomQty = false;

    if (!empty($customQty)) {

      $hasCustomQty = true;
    }

    $customQty = intval($customQty);



    $userId = webUserId();

    $tempHash = $userId;

    $TempUserId = webTempUserId();



    $tempOld = webUserOldTempId();



    if ($tempOld != "") {

      //user is login ... so i get his old temp id,, for hask key match...

      $tempHash = $tempOld;
    }



    if (intval($userId) < 1) {

      $tempHash = $TempUserId;
    }




    //Hash for cart

    @$hashVal = "0:0:0:" . $storeId . ":" . $tempHash . ":" . $jsonT . ":" . $dealId;

    $hashCart = md5($hashVal);



    //hash for inventory table

    //check all products Inventory

    $totalQtyV = 0;

    if ($this->functions->developer_setting('product_check_stock') == '1') {

      foreach ($json as $val) {

        $pId = $val['pId'];

        $scaleId = $val['scaleId'];

        $colorId = $val['colorId'];

        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;

        $hash = md5($hashVal);



        //Check stock in store

        $sqlCheck = "SELECT `qty_item`,`product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = '$hash'";

        $totalQty = $this->dbF->getRow($sqlCheck);

        if (intval($totalQty['qty_item']) < $totalQtyV || $totalQtyV == 0)

          $totalQtyV = intval($totalQty['qty_item']);



        if ($totalQtyV <= 0) {

          echo 'Quantity out of stock';

          return false;
        }
      }
    }



    $pId = $scaleId = $colorId = 0;

    //check cart already or not

    $sqlCheck = "SELECT * FROM `cart` WHERE `hash` = '$hashCart'";

    $cartData = $this->dbF->getRow($sqlCheck);

    if ($this->dbF->rowCount > 0) {

      $qty = $cartData['qty'];



      if ($this->functions->developer_setting('product_check_stock') == '1') {

        if ($qty >= $totalQtyV) {

          echo 'Any one package product has exceed stock quantity';

          return false;
        }
      }



      $cartId = $cartData['id'];



      $cartId = $cartData['id'];

      $sqlQtyJoin = "`qty` = qty+1, ";

      if ($hasCustomQty) {

        $sqlQtyJoin = "`qty` = '$qty', ";
      }

      $sql = "UPDATE `cart` SET

$sqlQtyJoin

`userId` = ?

WHERE `id`= ?";

      $this->dbF->setRow($sql, array($userId, $cartId));
    } else {

      $sqlQtyJoin = "`qty`= '1',";

      if ($hasCustomQty) {

        $sqlQtyJoin = "`qty`= '$customQty',";
      }



      $sql = "INSERT INTO `cart` SET

`pId`     =?,

`scaleId` =?,

`colorId` =?,

`storeId` =?,

$sqlQtyJoin

`userId`  =?,

`tempUser`=?,

`hash`    =?,

`deal`    =?,

`info`    =?

";



      $json = serialize($json);


      $array = array(
        $pId,
        $scaleId,
        $colorId,
        $storeId,

        $userId,
        $TempUserId,
        $hashCart,
        $dealId,
        $json
      );

      $this->dbF->setRow($sql, $array);
    }

    if (!$this->dbF->rowCount) {

      echo "Product Add In Cart Fail Please Try Again";
    } else {

      echo "1";
    }
  }



  public function AddToCartCustom()
  {

    if (isset($_POST['customPId'])) {

      $pId = $_POST['customPId'];

      $custom_id = $_POST['custom_id'];

      $storeId = $_POST['customStore_' . $pId];

      $colorId = $_POST['customColor_' . $pId];

      @$submit_later = $_POST['customSubmit_later_' . $pId];

      $submit_later = empty($submit_later) ? '0' : '1';



      $userId = webUserId();

      $tempHash = $userId;

      $TempUserId = webTempUserId();



      $tempOld = webUserOldTempId();



      if ($tempOld != "") {

        //user is login ... so i get his old temp id,, for hash key match...

        $tempHash = $tempOld;
      }



      if (intval($userId) < 1) {


        $tempHash = $TempUserId;
      }



      @$customFields = $_POST['custom'];

      $customFieldsS = serialize($customFields);

      $hashOfCustomFields = md5(str_replace(" ", "", $customFieldsS) . ":");

      $scaleId = $hashOfCustomFields;



      $customPrice = $this->productClass->customSizePrice($pId);



      //Hash for cart

      @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId . ":" . $tempHash;

      $hashCart = md5($hashVal);



      //check cart already or not

      $sqlCheck = "SELECT * FROM `cart` WHERE `hash` = '$hashCart'";

      $cartData = $this->dbF->getRow($sqlCheck);

      if ($this->dbF->rowCount > 0) {

        $qty = $cartData['qty'];

        $cartId = $cartData['id'];

        $sql = "UPDATE `cart` SET

`qty`     = qty + ?,

`userId` = ?

WHERE `id`= ?";

        $this->dbF->setRow($sql, array(1, $userId, $cartId));



        //update submitLater info on cart submit.....

        $sql = "UPDATE p_custom_submit SET submitLater = ? WHERE id IN (SELECT customId FROM cart WHERE `id`= ?)";

        $cartData = $this->dbF->setRow($sql, array($submit_later, $cartId));
      } else {

        //Submit Custom Fields

        $pInfo = "$pId-custom-$colorId-$storeId";

        $sql = "INSERT INTO p_custom_submit(pInfo,custom_id,actualPrice,submitLater) VALUES (?,?,?,?)";

        $this->dbF->setRow($sql, array($pInfo, $custom_id, $customPrice, $submit_later));

        $customId = $this->dbF->rowLastId;



        $sqlArray = array();

        $isFields = false;

        $sql = "INSERT INTO p_custom_submit_setting(orderId,setting_name,setting_value) VALUES ";



        foreach ($_POST['custom'] as $key2 => $val2) {

          $isFields = true;

          $sql .= "(?,?,?),";

          $sqlArray[] = $customId;

          $sqlArray[] = $key2;

          $sqlArray[] = $val2;
        }



        $sql = trim($sql, ",");

        if ($isFields) {

          $this->dbF->setRow($sql, $sqlArray);
        }



        $sql = "INSERT INTO `cart` SET

`pId`     =?,

`scaleId` ='0',

`colorId` =?,

`storeId` =?,

`customId` = ?,

`qty`     = '1',

`userId`  =?,

`tempUser`=?,

`hash`    =?";



        $array = array(
          $pId,
          $colorId,
          $storeId,
          $customId,

          $userId,
          $TempUserId,
          $hashCart
        );

        $this->dbF->setRow($sql, $array);

        if ($this->dbF->rowCount > 0) {

          echo "1";
        } else {

          echo "0";
        }
      }
    }
  }



  public function AddPlusToCart()
  {

    if (isset($_POST['cartId'])) {

      $cartId = $_POST['cartId'];



      if (isset($_POST['addQty'])) {

        @$customQty = intval($_POST['addQty']);
      } else {

        $customQty = false;
      }



      $hasCustomQty = false;

      if (!empty($customQty)) {

        $hasCustomQty = true;
      }



      //Get Detail For get Product total Qty in stock

      $sql = "SELECT * FROM `cart`

WHERE `id`= '$cartId'";

      $data = $this->dbF->getRow($sql);

      if ($this->dbF->rowCount > 0) {

        $pId = $data['pId'];

        $storeId = $data['storeId'];

        $scaleId = $data['scaleId'];

        $colorId = $data['colorId'];

        $qty = $data['qty'];

        $dealId = $data['deal'];

        $customId = $data['customId'];
        $tempUser = $data['tempUser'];

        @$info = unserialize($data['info']);



        if ($hasCustomQty) {

          $qty = $customQty;
        } else {

          $qty++;
        }





        if ($dealId != '0') {

          $totalQty = $this->productClass->getDealLowestProductQty($info);
        } else {

          $totalQty = $this->productClass->productF->productQTY($pId, $storeId, $scaleId, $colorId);
        }



        //check if stock work, then qty check

        if ($customId == '0' && $this->functions->developer_setting('product_check_stock') == '1') {

          if ($qty > $totalQty) {

            echo 'Quantity out of stock: Limit QTY :' . $totalQty;

            return false;
          }
        }
      }





      $sqlQtyJoin = "`qty`     = qty+1";

      if ($hasCustomQty) {

        $sqlQtyJoin = "`qty`= '$customQty'";
      }



      $sql = "UPDATE `cart` SET

$sqlQtyJoin

WHERE `id`= ?";





      if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {

        $inv = $this->functions->decode($_SESSION['freeGift_ai_id']);
        $sqlq = "SELECT `txt_inv_pro_qty`,`pro_ids` FROM `free_gift_inv` WHERE `id` = ?";
        $dataq = $this->dbF->getRow($sqlq, array($inv));
        if ($this->dbF->rowCount > 0) {
          $qqty = $dataq['txt_inv_pro_qty'];
          $pro_ids = $dataq['pro_ids'];
          $sqlqi = "SELECT `pId`,`qty` FROM `cart` WHERE `tempUser` = ?";
          $dataqi = $this->dbF->getRows($sqlqi, array($tempUser));
          $items = array();
          $ttl = 1;
          foreach ($dataqi as $valq) {
            $ttl += $valq['qty'];
            $items[] = $valq['pId'];
          }
          $arrCart = array_unique($items);
          $string = ($ttl);
          if ($qqty < $string && $qqty != $string) {
          } else {
            $this->dbF->setRow($sql, array($cartId));
          }
        }
      } else {

        $this->dbF->setRow($sql, array($cartId));
      }
    }
  }



  public function addByQty()
  {

    if (isset($_POST['cartId'])) {

      $cartId = $_POST['cartId'];

      $newQty = intval($_POST['addQty']);



      //Get Detail For get Product total Qty in stock

      $sql = "SELECT * FROM `cart`

WHERE `id`= '$cartId'";

      $data = $this->dbF->getRow($sql);

      if ($this->dbF->rowCount > 0) {

        $pId = $data['pId'];

        $storeId = $data['storeId'];

        $scaleId = $data['scaleId'];

        $colorId = $data['colorId'];

        $qty = $data['qty'];

        $dealId = $data['deal'];
        $tempUser = $data['tempUser'];
        @$info = unserialize($data['info']);



        if ($dealId != '0') {

          $totalQty = $this->productClass->getDealLowestProductQty($info);
        } else {

          $totalQty = $this->productClass->productF->productQTY($pId, $storeId, $scaleId, $colorId);
        }

        if ($newQty > $totalQty) {

          echo 'Quantity out of stock';

          return false;
        }
      }



      $sql = "UPDATE `cart` SET

`qty`     = $newQty

WHERE `id`= ?";

      if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {
        $inv = $this->functions->decode($_SESSION['freeGift_ai_id']);
        $sqlq = "SELECT `txt_inv_pro_qty`,`pro_ids` FROM `free_gift_inv` WHERE `id` = ?";
        $dataq = $this->dbF->getRow($sqlq, array($inv));
        if ($this->dbF->rowCount > 0) {
          $qqty = $dataq['txt_inv_pro_qty'];
          $pro_ids = $dataq['pro_ids'];
          $sqlqi = "SELECT `pId`,`qty` FROM `cart` WHERE `tempUser` = ?";
          $dataqi = $this->dbF->getRows($sqlqi, array($tempUser));
          $items = array();
          $ttl = 1;
          foreach ($dataqi as $valq) {
            $ttl += $valq['qty'];
            $items[] = $valq['pId'];
          }
          $arrCart = array_unique($items);
          $string = count($arrCart);
          $string = ($ttl);
          if ($qqty < $string && $qqty != $string) {
          } else {
            $this->dbF->setRow($sql, array($cartId));
          }
        }
      } else {
        $this->dbF->setRow($sql, array($cartId));
      }
    }
  }



  public function minusFromCart()
  {

    if (isset($_POST['cartId'])) {

      $cartId = $_POST['cartId'];



      $sql = "UPDATE `cart` SET

`qty`     = qty - ?

WHERE `id`= ? AND qty >= ?";

      $this->dbF->setRow($sql, array(1, $cartId, 0));
    }
  }
  public function getSearchJsonDiv123()
  {
    global $_e;
    $key = $_POST['val'];
    $key = addslashes($key);
    $products = $this->productClass->productByfilterAll($key, false, false);
    if ($products == "" || $products == false) {
      //print error emssage
      $t = $_e["No Product Found"];
      $products = "<div class='alert alert-danger'>$t</div>";
    } else {
      $products = "<div class='col3_main iHaveProducts'>$products</div>
    
        <script>
        $('.hideall').hide();
        </script>
        ";

      // using product ajax load on scroll
    }

    echo $products;
  }




  public function getSearchJsontxt_search_cat()
  {

    global $_e;
    $key = $_POST['val'];

    $key = addslashes($key);
    $products = $this->productClass->productByfilter_All_search_cat($key, false, false);

    if ($products == "" || $products == false) {
      $t = $_e["No Product Found"];
      $products = "<div class='alert alert-danger'>$t</div>";
    } else {
      $products = "<div class='col3_main iHaveProducts'>$products</div>

        <script>
        $('.hideall').hide();
        </script>
        ";
    }

    echo $products;
  }

  public function setCookie()
  {


    $cookie_name = "mainCookie";
    $cookie_value = "1";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 3560), "/"); // 86400 = 1 day


    if (isset($_COOKIE["mainCookie"])) {
      echo 1;
    } else {
      echo 0;
    }
  }

  public function cartProductRemove()
  {

    if (isset($_POST['cartId'])) {

      $cartId = $_POST['cartId'];



      $sql = "DELETE FROM `cart` WHERE `id`= '$cartId'";

      $this->dbF->setRow($sql);

      echo "1";
    } else {

      echo "0";
    }
  }


  public function getSearchJsonDiv()
  {

    global $_e;
    $key = $_POST['val'];



    $key = addslashes($key);

    $limit = 15;


    //search All related Match products

    $sql = "SELECT prodet_id,prodet_name,product_update,slug FROM `proudct_detail` WHERE prodet_name LIKE '%$key%'  GROUP BY prodet_id

UNION

SELECT prodet_id,prodet_name,product_update,slug FROM `proudct_detail` WHERE prodet_name LIKE '%$key%' GROUP BY prodet_id LIMIT 0,$limit";


    $data = $this->dbF->getRows($sql);


    if ($this->dbF->rowCount > 0) {
      $temp = "<div class='col3_main'>";


      foreach ($data as $val) {

        $id = $val['prodet_id'];
        $pId = $val['prodet_id'];

        $pName = translateFromSerialize($val['prodet_name']);

        $slug = translateFromSerialize($val['slug']);



        $key2 = stripslashes($key);

        $pName2 = str_ireplace($key2, '<span class="searchHighlight">' . $key2 . '</span>', $pName);

        $saleDivDisVal = "";



        $productImage = $this->productClass->productSpecialImage($pId, 'main');

        if ($productImage == "") {
          $productImage = "default.jpg";
        }
        $productBigImage = $productImage;
        $productImage = $this->functions->resizeImage($productImage, '300', '500', false);



        $price = $this->productClass->productF->productPrice($id);

        $currencyId = $price['propri_cur_id'];

        $symbol = $this->productClass->productF->currencySymbol($currencyId);

        $priceP = $price['propri_price'];



        $discount = $this->productClass->productF->productDiscount($id, $currencyId);

        @$discountFormat = $discount['discountFormat'];

        @$discountP = $discount['discount'];


        $currencyId = $this->productClass->currentCurrencyId();
        $currencySymbol = $this->productClass->currentCurrencySymbol();

        $discountValue = $discountP;
        if ($discountFormat == "percent") {
          $discountValue .= " %";
        } else {
          $discountValue = $discountValue . " $currencySymbol";
        }


        $discountPrice = $this->productClass->productF->discountPriceCalculation($priceP, $discount);

        $newPrice = $priceP - $discountPrice;



        $priceP .= ' ' . $symbol;

        $newPrice .= ' ' . $symbol;



        if ($newPrice != $priceP) {

          $hasDiscount = true;
          $newPriceNormal = $newPrice;
          $oldPriceDiv = '<h6>' . $priceP . ' </h6>';
          $newPriceDiv = '<h4>' . $newPrice . '</h4>';
        } else {
          $oldPriceNormal = "";
          $oldPriceDiv = "";
          $newPriceDiv = '<h4>' . $newPrice . '</h4>';
        }






        $pName = addslashes($pName);

        $pName2 = addslashes($pName2);

        $link = $slug;





        $chk_link = "/" . $this->db->productDetail . $slug;

        $sql = "SELECT `slug` FROM `seo` WHERE `pageLink` = ?";

        $data = $this->dbF->getRow($sql, array($chk_link));
        if ($this->dbF->rowCount > 0) {
          $product_link = WEB_URL . "/" . $data['slug'];
        } else {
          $product_link = WEB_URL . "/$productSlug";
        }


        $staple_product_category1 = $this->productClass->productF->check_product_in_staple_cat($pId);
        if ($staple_product_category1) {
          $staple_product_category = "<div class='three_for_2_icon bundle_cat_icon'><img loading='lazy' src='" . WEB_URL . "/webImages/bundle_icon.png' alt='bundle' width='60px' height='60px'/></div>";
        } else {
          $staple_product_category = "";
        }

        $three_for_2_category = $this->productClass->productF->check_product_in_3_for_2($pId);
        if ($three_for_2_category) {
          $three_for_2_category = "<div class='three_for_2_icon'><img loading='lazy' alt='3for2' src='" . WEB_URL . "/images/3for2.jpg' /></div>";
        } else {
          $three_for_2_category = "";
        }



        $pPriceData = $this->productClass->productF->productPrice($pId, $currencyId);
        //$pPriceData Return , currency id,international shipping, price, id,
        $pPriceActual = $pPriceData['propri_price'];
        $pPrice = $pPriceActual;



        $isSale = false;
        if (isset($discount['isSale']) && $discount['isSale'] == '1') {
          $isSale = true;
        }
        $isDiscount = false;
        if ($newPrice != $pPrice) {
          $isDiscount = true;
        }


        $saleDivDisVal = "";
        $saleDivDisPerc = "";
        if ($isSale) {
          //Sale
          if ($discountFormat == "percent") {
            $saleDivDisPerc = "<div class='col3_box_tag1'>-$discountValue</div>";
          } else {
            $discount_per = ($discountP / $pPrice) * 100;
            $discount_per = ceil($discount_per);
            $saleDivDisPerc = "<div class='col3_box_tag1'>-$discount_per%</div>";
            $saleDivDisVal = "<div class='col3_box_tag3'>$discountValue " . $_e['SALE'] . "</div>";
          }
        } else if ($isDiscount) {
          //Discount
          if ($discountFormat == "percent") {
            $saleDivDisPerc = "<div class='col3_box_tag1'>-$discountValue</div>";
          } else {
            $discount_per = ($discountP / $pPrice) * 100;
            $discount_per = ceil($discount_per);
            $saleDivDisPerc = "<div class='col3_box_tag1'>-$discount_per %</div>";
            $saleDivDisVal = "<div class='col3_box_tag3'>$discountValue " . $_e['DISCOUNT'] . "</div>";
          }
        }
        $productScales = $this->productClass->productScales($pId);




        $this->functions->_modelFile("classes/rating.php");

        $this->rating = new rating();

        $_uid = $_SESSION['webUser']['tempId'];

        $rate = $this->rating->ratingInfo($pId, false, $_uid);

        $rate_star = "";

        if ($rate != "") {



          $rate_avg = $rate['avg'];

          $rate_full = $rate['noOfVotes'];

          for ($i = 1; $i <= 5; $i++) {

            $class = "";

            if ($rate_full >= $i || $rate_avg >= ($i - 0.2)) {


              $rate_star .= '<img src="https://sharkspeed.com/new/webImages/35.png" alt="star" loading="lazy"> ';
            } else if ($rate_avg > $i - 0.8) {


              $rate_star .= ' <img src="https://sharkspeed.com/new/webImages/34.png" alt="star-half" loading="lazy">';
            } else {

              $rate_star .= '';
            }
          }
        }








        $temp .= "<div class='col3_box' data-id='$pId' data-sr='enter bottom and scale up 80% over 1.5s'>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>

$three_for_2_category
$staple_product_category

<a href='$product_link'>
<div class='col3_box_img'> <img src='$productImage' alt='$pName' loading='lazy'>$saleDivDisVal


<div class='col3_box_tag'>
$saleDivDisPerc
</div>

</div>

<!-- col3_box_img close -->
</a>
<div class='col3_box_detail'>

<a href='$product_link'>
<div class='col3_box_detail_price'>
$newPriceDiv
$oldPriceDiv 
</div>
<!-- col3_box_detail_price close -->
<div class='col3_box_detail_txt'> $pName </div>
<!-- col3_box_detail_txt close -->
</a>
<div class='col3_box_detail_size'>
$productScales
</div>


<div class='col3_star'>
$rate_star
</div>
<!-- col3_star close -->

<!-- col3_box_detail_size close -->
</div>
<!-- col3_box_detail close -->
</div>
<!-- col3_box close -->
";
      }

      $temp .= "</div>";
    } else {
      $temp = "";
    }



    echo $temp;
  }

  public function getSearchJson()
  {

    $key = $_GET['val'];



    $key = addslashes($key);

    $limit = 3;

    if (isset($_GET['limit'])) {

      $limit = $_GET['limit'];
    }

    //search All related Match products

    $sql = "SELECT prodet_id,prodet_name,product_update,slug FROM `proudct_detail` WHERE prodet_name LIKE '%$key%'  GROUP BY prodet_id

UNION

SELECT prodet_id,prodet_name,product_update,slug FROM `proudct_detail` WHERE prodet_name LIKE '%$key%' GROUP BY prodet_id LIMIT 0,$limit";

    $data = $this->dbF->getRows($sql);



    if ($this->dbF->rowCount > 0) {

      $temp = "[";

      foreach ($data as $val) {

        $id = $val['prodet_id'];

        $pName = translateFromSerialize($val['prodet_name']);

        $slug = translateFromSerialize($val['slug']);



        $key2 = stripslashes($key);

        $pName2 = str_ireplace($key2, '<span class="searchHighlight">' . $key2 . '</span>', $pName);



        $img = $this->productClass->productF->productSpecialImage($id, 'main');

        $img = $this->productClass->functions->resizeImage($img, 'auto', 70, false);



        $price = $this->productClass->productF->productPrice($id);

        $currencyId = $price['propri_cur_id'];

        $symbol = $this->productClass->productF->currencySymbol($currencyId);

        $priceP = $price['propri_price'];



        $discount = $this->productClass->productF->productDiscount($id, $currencyId);

        @$discountFormat = $discount['discountFormat'];

        @$discountP = $discount['discount'];



        $discountPrice = $this->productClass->productF->discountPriceCalculation($priceP, $discount);

        $newPrice = $priceP - $discountPrice;



        $priceP .= ' ' . $symbol;

        $newPrice .= ' ' . $symbol;



        if ($newPrice != $priceP) {

          $hasDiscount = true;

          $oldPriceDiv = '<span class="oldPrice">' . $priceP . '</span>';

          $newPriceDiv = '<span class="NewDiscountPrice">' . $newPrice . '</span>';
        } else {

          $oldPriceDiv = "";

          $newPriceDiv = '<span class="NewDiscountPrice">' . $priceP . '</span>';
        }



        $pName = addslashes($pName);

        $pName2 = addslashes($pName2);

        $img = addslashes($img);

        $link = $slug;

        $product_link = WEB_URL . '/' . $this->db->productDetail . $slug;

        $temp .= '{

"label"     : \'' . $pName . '\',

"id"        : "' . $id . '",

"name"      : \'' . $pName2 . '\',

"image"     : "' . $img . '",

"priceCode" : "' . $symbol . '",

"newPrice"  : \'' . $newPriceDiv . '\',

"oldPrice"  : \'' . $oldPriceDiv . '\',

"link"  : \'' . $product_link . '\'

},';
      }

      $temp = trim($temp, ',');

      $temp .= "]";
    } else {

      $temp = "[]";
    }



    echo $temp;
  }



  public function cartSmallProduct()
  {

    $cartInfo = $this->productClass->cartInfo(true);

    $price = $cartInfo['price'] . " " . $cartInfo['symbol'];

    $products = $cartInfo['products'];

    $qty = $cartInfo['qty'];



    if (!isset($_GET['product'])) {

      $products = '';
    }

    echo "$products

<script>

$(document).ready(function(){

$('.cartPriceAjax').text('$price');

$('.cartItemNo').text('$qty');

});

</script>";
  }



  public function cart_checkout_side_data()
  {



    $cartInfo = $this->productClass->cartInfo(true, $view = 'checkout_popup_side_cart_view');


    // # three for two


    $price = $cartInfo['price'] . " " . $cartInfo['symbol'];

    $products = $cartInfo['products'];

    $qty = $cartInfo['qty'];

    $price_simple = $cartInfo['price'];

    $symbol = $cartInfo['symbol'];

    $three_for_2_cat_div = isset($cartInfo['three_for_2_cat_div']) ? $cartInfo['three_for_2_cat_div'] : '';

    $three_for_2_minus_price = isset($cartInfo['three_for_2_minus_price']) ? $cartInfo['three_for_2_minus_price'] : 0;

    $staple_pro_cat_div = isset($cartInfo['staple_pro_cat_div']) ? $cartInfo['staple_pro_cat_div'] : '';

    $staple_pro_minus_price = isset($cartInfo['staple_pro_minus_price']) ? $cartInfo['staple_pro_minus_price'] : 0;

    $pIdsForCheckOutOffer = isset($cartInfo['pIdsForCheckOutOffer']) ? $cartInfo['pIdsForCheckOutOffer'] : '';



    $result_array = array();

    $result_array['qty'] = $qty;

    $result_array['price'] = $price;

    $result_array['products'] = $products;

    $result_array['price_simple'] = $price_simple;

    $result_array['symbol'] = $symbol;

    $result_array['three_for_2_cat_div'] = $three_for_2_cat_div;

    $result_array['three_for_2_minus_price'] = $three_for_2_minus_price;

    $result_array['staple_pro_cat_div'] = $staple_pro_cat_div;

    $result_array['staple_pro_minus_price'] = $staple_pro_minus_price;

    $result_array['pIdsForCheckOutOffer'] = $pIdsForCheckOutOffer;

    return $result_array;
  }



  public function cart_checkout_side_view()
  {



    $second_array = $this->get_coupon_status_and_text();

    $result_array = $this->cart_checkout_side_data();

    $gift_array = $this->productClass->giftCardCheck($result_array['price_simple']);

    # if giftcard does not have an error then add the remove gift card div

    if (isset($gift_array['error']) && $gift_array['error'] == false) {

      $gift_array['removeGiftCard'] = '<div class="clearfix margin-5"></div><span href="cart?giftCard=remove" id="giftcard_remove" class="btn-danger btn-sm">' . $this->dbF->hardWords('Remove GiftCard', false) . '</span>';
    }


    if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {

      # deduct free gift

      $result_array['price_simple'] = "0";
    } else {

      # deduct giftcard price from total

      $result_array['price_simple'] = $result_array['price_simple'] - $gift_array['payPrice'];
    }

    $pIdsss = $this->productClass->donotForgetToBuyCartProductIds();
    $doNotForgetProducts = $this->productClass->dontForgetToBuyProduct("", $pIdsss, '1');
    $result_array['doNotForgetProducts'] = $doNotForgetProducts;

    echo json_encode($result_array + $second_array + $gift_array);
  }


  public function dontForgetToBuy()
  {
    $pIdsss = $this->productClass->donotForgetToBuyCartProductIds();
    $doNotForgetProducts = $this->productClass->dontForgetToBuyProduct("", $pIdsss, '1');
    $result_array = array('doNotForgetProducts' => ($doNotForgetProducts));
    echo $doNotForgetProducts;
  }



  public function cart_side_submit()
  {

    $result_array = array();



    if (array_key_exists('order_submit', $_GET) && $_GET['order_submit'] == '1') {

      # submitted first time, show checkout offer.


      $result_array = $this->cart_checkout_side_data();

      $price_simple = $result_array['price_simple'];

      $pIdsForCheckOutOffer = $result_array['pIdsForCheckOutOffer'];

      $checkout_offer = $this->productClass->checkOutOffer($price_simple, $pIdsForCheckOutOffer);


      $result_array['checkout_offer'] = $checkout_offer;



      if ($checkout_offer == '') {

        $msg = $this->productClass->cartSubmitForCheckOut($directSubmit = TRUE);

        $invoiceId = $this->productClass->orderLastInvoiceId;



        $result_array['msg'] = $msg;

        $result_array['invoiceId'] = $invoiceId;
      }
    } else {

      # submitted second time, submit and create order.



      $msg = $this->productClass->cartSubmitForCheckOut($directSubmit = TRUE);

      $invoiceId = $this->productClass->orderLastInvoiceId;
      $youWillLoveThisProducts = $this->functions->ibms_setting('youWillLoveThisProduct');


      $result_array['you_will_love_products'] = $youWillLoveThisProducts;
      $result_array['msg'] = $msg;
      
      $result_array['invoiceId'] = $invoiceId;
    }





    echo json_encode($result_array);
  }

  public function something_extra_products()
  {

    global $_e;
    if (array_key_exists('invoiceId', $_GET)) {
      $_SESSION['webUser']['lastInvoiceId'] = $invoiceId = $_GET['invoiceId'];
      $cartReturned = $this->productClass->somethingExtraProduct($invoiceId);
      $cart_side_order_products = $cartReturned["somethingExtra"];

      $result_array = array();
      $result_array['somethingExtraProduct'] = $cart_side_order_products;
      echo json_encode($result_array);
    } else {

      echo 'No invoice!';
    }
  }

  public function cart_side_load_order_file()
  {
    global $_e;
    if (array_key_exists('invoiceId', $_GET)) {
      $_SESSION['webUser']['lastInvoiceId'] = $invoiceId = $_GET['invoiceId'];
      $cartReturned = $this->productClass->viewCheckOutProduct3($invoiceId);

      $cartReturn = $cartReturned['temp'];

      $symbol = $cartReturned['symbol'];

      $cartCustomSizeModals = $cartReturned['sizeModal'];

      $cart_side_order_products = $cartReturned['cart_side_order_products_html'];

      $new_cart_side_order_products = $cartReturned['new_cart_side_order_products_html'];

      $subtotal = $cartReturned['subtotal'];

      $shipPrice = $cartReturned['shipPrice'];

      $grandTotal = $cartReturned['grandTotal'];

      $three_for_2_minus_price = $cartReturned['three_for_2_minus_price'];

      $staple_pro_minus_price = $cartReturned['staple_pro_minus_price'];

      $currencySymbol = $cartReturned['currencySymbol'];

      $totalPriceProducts = $cartReturned['totalPriceProducts'];

      $bundle_type = $cartReturned['bundle_type'];



      $_GET['inv'] = $invoiceId;

      $_GET['ajax'] = 1;

      $product_ajax_function = TRUE;

      $order_popup_file = include_once(__DIR__ . '/../../order_popup.php');





      $gift_array = $this->productClass->giftCardCheck($grandTotal);

      $grandTotal = $grandTotal - $gift_array['payPrice'];







      ############ 3 For 2 Category START #########

      $three_for_2_cat_div = '';

      if ($three_for_2_minus_price > 0) {

        $three_for_2_cat_div = "

        <div class='sub_3'  style='margin-right: 10px;'>" . $_e['Three For Two Category'] . "
        
        <div class='sub_4'>$three_for_2_minus_price $currencySymbol</div>
        
        </div>";
      }

      ############ 3 For 2 Category END #########



      ############ 3 For 2 Category START #########

      $staple_pro_cat_div = '';

      $staple_discount_div = '';

      if ($staple_pro_minus_price > 0) {

        $currencyId = $this->productClass->currentCurrencyId();

        $currencySymbol = $this->productClass->currentCurrencySymbol();

        $staple_cat_setting = unserialize($this->functions->ibms_setting("stapleProductSetting"));

        $bundle_array = array();

        for ($i = 0; $i < sizeof($staple_cat_setting['quantity']); $i++) {

          $bundle_array[$staple_cat_setting['quantity'][$i]] = $staple_cat_setting['price'][$currencyId][$i];
        }

        krsort($bundle_array);

        $bundelT = $_e['Bundle feature applies:'];



        $text = $bundelT . '<br>';

        if (!empty($bundle_type)) {

          $bundle_type = rtrim($bundle_type, ',');

          $bundle_type = explode(',', $bundle_type);

          foreach ($bundle_type as $key => $value) {

            $text .= $value . ' ' . $_e['pcs for'] . ' ' . $bundle_array[$value] . ' ' . $currencySymbol . '<br>';
          }
        }



        $staple_pro_cat_div = $text;



        $staple_discount_div = "

        <div class='sub_3'  style='margin-right: 10px;'>" . $_e['Bundle Discount'] . "
        
        <div class='sub_4'>$staple_pro_minus_price $currencySymbol</div>
        
        </div>";
      }

      ############ 3 For 2 Category END #########



      $order_price_html = <<<HTML



<!--
{$staple_pro_cat_div}<hr>


<div class="sum_side" style="border-bottom: none;">
<div class="sum_side_left"> {$_e['SUBTOTAL']}: </div> -->
<!-- sum_side_left close -->
<!--  <div class="sum_side_right"> {$totalPriceProducts} {$currencySymbol} </div> -->
<!-- sum_side_right close -->
<!-- </div>  -->








{$three_for_2_cat_div}

{$staple_discount_div}



<!--
<div class="sum_side" style="border-bottom: none;">
<div class="sum_side_left"> {$_e['ESTIMATED DELIVERY & HANDLING']}: </div> -->
<!-- sum_side_left close -->
<!-- <div class="sum_side_right">    <span class='pShippingPriceTemp' data-real='{$shipPrice}'>

{$shipPrice}

</span> 

{$currencySymbol}
</div> -->
<!-- sum_side_right close -->
</div>



<div class="sum_side bold_side">
<div class="sum_side_left"> {$_e['TOTAL']} </div>
<!-- sum_side_left close -->
<div class="sum_side_right"> <span class='pGrandTotal' data-total='{$grandTotal}'>{$grandTotal} </span> 

{$currencySymbol}  </div>
<!-- sum_side_right close -->
</div>
<!-- sum_side close -->













HTML;





      $result_array = array();

      $result_array['cartReturn'] = $cartReturn;

      $result_array['cartCustomSizeModals'] = $cartCustomSizeModals;

      $result_array['cart_side_order_products_html'] = $cart_side_order_products;

      $result_array['new_cart_side_order_products_html'] = $new_cart_side_order_products;


      $result_array['order_popup_html'] = $order_popup_file;

      $result_array['subtotal'] = $subtotal;

      $result_array['symbol'] = $symbol;

      $result_array['shipPrice'] = $shipPrice;

      $result_array['grandTotal'] = $grandTotal;

      $result_array['three_for_2_minus_price'] = $three_for_2_minus_price;

      $result_array['staple_pro_minus_price'] = $staple_pro_minus_price;

      $result_array['order_price_html'] = $order_price_html;

      $result_array['totalPriceProducts'] = $totalPriceProducts;

      $second_array = $this->get_coupon_status_and_text();

      echo json_encode($result_array + $second_array + $gift_array);
    } else {

      echo 'No invoice!';
    }
  }



  public function addRating()
  {

    $this->functions->_modelFile("classes/rating.php");

    $rate = new rating();

    $rate->addRatings_();
  }
  public function get_allPublishProducts_with_size_color()
  {

    $relatedData = $this->productClass->productF->productActiveSql('prodet_id,prodet_name');




    $select_box = '<select id="order_product123" name="order_product123" class="form-control test" required="" >';

    foreach ($relatedData as $val) {

      $Ids = ($val['prodet_id']);

      $prosiz_id_prosiz_name = $this->productClass->productF->scaleSQL($Ids);


      $propri_id_color_name = $this->productClass->productF->colorSQL($Ids);


      $img = $this->productClass->productSpecialImage($Ids, 'main');

      $productImage = $this->functions->resizeImage($img, '300', '500', false);

      $product_name = $this->functions->unserializeTranslate($val['prodet_name']);

      foreach ($prosiz_id_prosiz_name as $val_prosiz_id) {

        $size_id = $val_prosiz_id['prosiz_name'];


        $select_box .= "<option value='{$val['prodet_id']}::{$val_prosiz_id['prosiz_id']}' data-img='{$productImage}'>{$product_name} - {$size_id} </option>";
      }
    }

    $select_box .= '</select>';

    echo $select_box;
  }

  public function get_allPublishProducts_with_size_color1()
  {

    $relatedData = $this->productClass->productF->productActiveSql('prodet_id,prodet_name');


    $select_box = '<select id="order_product123456" name="order_product123456" class="form-control test132456" >

    <option value=""> Select </option>';

    foreach ($relatedData as $val) {

      $Ids = ($val['prodet_id']);



      $prosiz_id_prosiz_name = $this->productClass->productF->scaleSQL($Ids);


      $propri_id_color_name = $this->productClass->productF->colorSQL($Ids);


      $img = $this->productClass->productSpecialImage($Ids, 'main');



      $productImage = WEB_URL . "/images/" . $img;
      $product_name = $this->functions->unserializeTranslate($val['prodet_name']);


      foreach ($prosiz_id_prosiz_name as $val_prosiz_id) {

        $size_id = $val_prosiz_id['prosiz_name'];


        $select_box .= "<option value='{$val['prodet_id']}::{$val_prosiz_id['prosiz_id']}' data-img='{$productImage}'>{$product_name} - {$size_id} </option>";
      }
    }

    $select_box .= '</select>';



    echo $select_box;
  }
  public function get_order_products_1()
  {

    $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : NULL;

    if (!$order_id) {

      return false;
    }



    $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with');

    $order_id = str_replace($invoiceKey, '', $order_id);


    $sql = " SELECT oi.shippingCountry, oip.* FROM `order_invoice_product` oip LEFT OUTER JOIN `order_invoice` 
    oi ON oi.order_invoice_pk = oip.order_invoice_id WHERE oip.order_invoice_id = ? ORDER BY oip.invoice_product_pk DESC ";



    $invoice = $this->dbF->getRows($sql, array($order_id));

    $select_box = '<select multiple="" id="order_product" name="insert[order_product][]" class="form-control test" required="" >';

    foreach ($invoice as $val) {

      $Ids = explode('-', $val['order_pIds']);

      $pId = $Ids[0];


      $img = $this->productClass->productSpecialImage($pId, 'main');

      $productImage = WEB_URL . "/images/" . $img;


      $price = ($val['order_pPrice'] - $val['order_discount']) . ' ' . $val['shippingCountry'];

      $select_box .= "<option value='{$val['invoice_product_pk']}' data-img='{$productImage}'>{$val['order_pName']} ({$price})

        </option>";
    }

    $select_box .= '</select>';



    echo $select_box;
  }



  public function get_order_products()
  {

    $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : NULL;

    if (!$order_id) {

      return false;
    }



    $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with');

    $order_id = str_replace($invoiceKey, '', $order_id);



    $sql = " SELECT oi.shippingCountry, oip.* FROM `order_invoice_product` oip LEFT OUTER JOIN `order_invoice` 
    oi ON oi.order_invoice_pk = oip.order_invoice_id WHERE oip.order_invoice_id = ? ORDER BY oip.invoice_product_pk DESC ";

    $invoice = $this->dbF->getRows($sql, array($order_id));

    $select_box = '<select multiple="" id="order_product" name="insert[order_product][]" class="form-control test" required="" >';

    foreach ($invoice as $val) {

      $Ids = explode('-', $val['order_pIds']);

      $pId = $Ids[0];

      $img = $this->productClass->productSpecialImage($pId, 'main');

      $productImage = WEB_URL . "/images/" . $img;


      $price = ($val['order_pPrice'] - $val['order_discount']) . ' ' . $val['shippingCountry'];

      $select_box .= "<option value='{$val['invoice_product_pk']}' data-img='{$productImage}'>{$val['order_pName']} ({$price})

        </option>";
    }

    $select_box .= '</select>';



    echo $select_box;
  }


  public function get_order_products_old()
  {

    $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : NULL;

    if (!$order_id) {

      return false;
    }



    $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with');

    $order_id = str_replace($invoiceKey, '', $order_id);


    $sql = " SELECT oi.shippingCountry, oip.* FROM `order_invoice_product` oip LEFT OUTER JOIN `order_invoice` 
    oi ON oi.order_invoice_pk = oip.order_invoice_id WHERE oip.order_process = ? 
    AND oip.order_invoice_id = ? ORDER BY oip.invoice_product_pk DESC ";

    $invoice = $this->dbF->getRows($sql, array('1', $order_id));

    $select_box = '<select multiple="" id="order_product" name="insert[order_product][]" class="form-control test" required="" >';

    foreach ($invoice as $val) {

      $Ids = explode('-', $val['order_pIds']);

      $pId = $Ids[0];


      $img = $this->productClass->productSpecialImage($pId, 'main');

      $productImage = WEB_URL . "/images/" . $img;


      $price = ($val['order_pPrice'] - $val['order_discount']) . ' ' . $val['shippingCountry'];

      $select_box .= "<option value='{$val['invoice_product_pk']}' data-img='{$productImage}'>{$val['order_pName']} ({$price})

        </option>";
    }

    $select_box .= '</select>';



    echo $select_box;
  }



  public function get_searched_products()
  {



    $not_in_sql = '';

    # check if this request is coming from best seller products in admin

    if (array_key_exists('bestseller', $_GET)) {

      $not_in_sql = "  AND `proudct_detail`.`prodet_id` NOT IN ( SELECT `product_id` FROM `best_seller_products` ) ";
    }



    $search = isset($_POST['search']) ? $_POST['search'] : NULL;

    if (!$search) {

      return false;
    }



    $search_value = '%' . $search . '%';



    $sql = " SELECT `proudct_detail`.* FROM `proudct_detail` LEFT OUTER JOIN `product_setting` 
    ON `product_setting`.p_id = `proudct_detail`.prodet_id WHERE `proudct_detail`.`product_update` = 1 
    AND `proudct_detail`.`prodet_name` LIKE ? AND ( `product_setting`.`setting_name`='publicAccess' AND 
    `product_setting`.`setting_val`='1' ) {$not_in_sql} ORDER BY `proudct_detail`.prodet_id DESC";

    $products = $this->dbF->getRows($sql, array($search_value));

    $list_items = '';

    $list_array = array();

    $i = 0;

    foreach ($products as $product) {

      $product_id = $this->functions->unserializeTranslate($product['prodet_id']);

      $product_name = $this->functions->unserializeTranslate($product['prodet_name']);


      $list_array[$i]['id'] = $product_id;

      $list_array[$i]['title'] = $product_name;



      $i++;
    }


    echo json_encode($list_array);
  }



  public function get_product_sizes_colors()
  {

    global $_e;



    $product_id = isset($_POST['product']) ? $_POST['product'] : NULL;

    if (!$product_id) {

      return false;
    }

    $currency_id = isset($_POST['currency']) ? $_POST['currency'] : NULL;



    $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory

    $inventoryLimit = ($inventoryLimit == '1' ? true : false);



    $hasScaleVal = $this->functions->developer_setting('product_Scale');

    $hasColorVal = $this->functions->developer_setting('product_color');



    $hasWebOrder_with_Scale = $this->functions->developer_setting('webOrder_with_Scale');

    $hasWebOrder_with_color = $this->functions->developer_setting('webOrder_with_color');



    $hasScale = ($hasScaleVal == '1' ? true : false);

    $hasColor = ($hasColorVal == '1' ? true : false);



    if ($inventoryLimit) {

      $getInfo = $this->productClass->inventoryReport($product_id);
    } else {

      $getInfo = $this->productClass->productSclaeColorReport($product_id);
    }



    if ($getInfo['scale'] == false && $hasWebOrder_with_Scale == '0') {

      //if scale not found then make scale data empty,

      //if product scale allow from setting and dont have inventory, it will make scale val to 0

      // we will assume scale not allow from setting for javascript

      $scaleDiv = "";

      $hasScaleVal = 0;

      $hasScale = false;
    }



    if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {

      //if color not found then make color data empty,

      //if product color allow from setting and dont have inventory, it will make color val to 0

      // we will assume color not allow from setting for javascript

      $colorDiv = "";

      $hasColorVal = 0;

      $hasColor = false;
    }



    if ($hasColor) {

      $colorDiv = $this->productClass->getColorsDiv($product_id, false, false, $currency_id, false, false, $hasScale);

      if ($colorDiv != '') {

        echo $colorDiv = '<label class="col-sm-5 control-label">' . _uc($_e['Color']) . '</label>

            <div id="size_color" class="col-sm-7" >' . $colorDiv . '</div>';
      }
    }



    if ($hasScale) {
      $scaleDiv = $this->productClass->getScalesDiv($product_id, $hasColor, false, $currency_id, false);


      if ($scaleDiv != '') {

        echo $scaleDiv = '<label class="col-sm-5 control-label">' . _uc($_e['Size']) . '</label>

            <div id="size_radio" class="col-sm-7" >' . $scaleDiv . '</div>';
      }
    }



    echo '<div style="clear:both"></div>';
  }



  public function get_order_currency()
  {

    $order_id = isset($_POST['order']) ? $_POST['order'] : NULL;

    if (!$order_id) {

      return false;
    }



    $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with');

    $order_id = str_replace($invoiceKey, '', $order_id);



    $sql = " SELECT * FROM `order_invoice` LEFT OUTER JOIN `currency` ON `order_invoice`.price_code = 
    `currency`.`cur_symbol` AND `order_invoice`.`shippingCountry` = `currency`.`cur_country` WHERE `orderStatus` = 
    'process' AND `order_invoice_pk` = ? ";

    $order = $this->dbF->getRow($sql, array($order_id));



    echo $order['cur_id'];
  }



  public function get_coupon_status_and_text($coupon_name = '')
  {



    if ($coupon_name == '') {

      $coupon_name = isset($_SESSION['webUser']['coupon']) ? $_SESSION['webUser']['coupon'] : '';
    }



    $result_array = array();

    $couponHas = $this->productClass->productF->productCouponStatus($coupon_name);

    if ($couponHas == FALSE) {

      $_SESSION['webUser']['coupon'] = '';

      $result_array['coupon_applied'] = false;



      if ($coupon_name == '') {

        # no coupon, hide the coupon status text.

        $result_array['coupon_text'] = '';
      } else {

        # coupon name exists, but not applied show warning.

        $result_array['coupon_text'] = '<div class="gift_dangr">' . $this->dbF->hardWords('Code Not Found or expired', false) . '</div>';
      }
    } else {

      $_SESSION['webUser']['coupon'] = $coupon_name;

      $result_array['coupon_applied'] = true;

      $result_array['coupon_text'] = '<div class="gift_succ">' . $this->dbF->hardWords('Discount code apply', false) . '</div>';

      $result_array['remove_coupon_text'] = '<div class="clearfix margin-5"></div><span  id="coupon_remove" class="btn-danger btn-sm">' . $this->dbF->hardWords('Remove Coupon', false) . '</span>';
    }



    return $result_array;
  }



  public function set_unset_coupon()
  {

    $_SESSION['webUser']['coupon'] = $coupon = isset($_POST['coupon']) ? $_POST['coupon'] : ($_SESSION['webUser']['coupon'] == '' ? NULL : $_SESSION['webUser']['coupon']);





    $remove_coupon = isset($_POST['remove_coupon']) ? TRUE : FALSE;



    # unset the coupon

    if ($remove_coupon) {

      $_SESSION['webUser']['coupon'] = $coupon = '';
    }

    # get the coupon status and its text status for div

    $second_array = $this->get_coupon_status_and_text($coupon);



    $result_array = $this->cart_checkout_side_data();



    $giftcard_arr = $this->productClass->giftCardCheck($result_array['price_simple']);













    if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {

      # deduct free gift

      $result_array['price_simple'] = "0";
    } else {

      # deduct giftcard price from total

      $result_array['price_simple'] = $result_array['price_simple'] - $giftcard_arr['payPrice'];
    }



    echo json_encode($result_array + $second_array);
  }



  public function set_unset_giftcard()
  {



    $giftcard = isset($_POST['giftcard']) ? $_POST['giftcard'] : ($_SESSION['webUser']['giftCard'] == '' ? NULL : $_SESSION['webUser']['giftCard']);





    $remove_giftcard = isset($_POST['remove_giftcard']) ? TRUE : FALSE;



    if ($giftcard) {

      $_GET['giftCard'] = $giftcard;
    }



    # unset the giftCard

    if ($remove_giftcard) {

      $_SESSION['webUser']['giftCard'] = $_GET['giftCard'] = '';
    }



    // # get the coupon status and its text status for div




    $result_array = $this->cart_checkout_side_data();



    $second_array = $this->productClass->giftCardCheck($result_array['price_simple']);


    # if giftcard does not have an error then add the remove gift card div

    if (isset($second_array['error']) && $second_array['error'] == false) {

      $second_array['removeGiftCard'] = '<div class="clearfix margin-5"></div><span href="cart?giftCard=remove" id="giftcard_remove" class="btn-danger btn-sm">' . $this->dbF->hardWords('Remove GiftCard', false) . '</span>';
    }







    if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {

      # deduct free gift

      $result_array['price_simple'] = "0";
    } else {



      # deduct giftcard price from total

      $result_array['price_simple'] = $result_array['price_simple'] - $second_array['payPrice'];
    }

    echo json_encode($result_array + $second_array);
  }


  public function klarnaAuthResponce($returnStatus = false)
  {
    $error = '';
    if (isset($_POST['authorization_token'])) {
      $authorization_token = $_POST['authorization_token'];
      $lastId = $_POST['lastId'];
      $sql = "SELECT * FROM klarnaAPI WHERE id =?";
      $check = $this->dbF->getRow($sql, array($lastId));



      if ($this->functions->ibms_setting('klarnaPaymentTesting') == '1') {


        $url = 'https://api.playground.klarna.com/payments/v1/sessions/' . $check['session_id'];
      } else {


        $url = 'https://api.klarna.com/payments/v1/sessions/' . $check['session_id'];
      }



      $password = $_POST['password'];
      $username = $_POST['username'];
      $aAuthorization = base64_encode($username . ":" . $password);
      $curlx = curl_init();
      curl_setopt_array(
        $curlx,
        array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "{}",
          CURLOPT_HTTPHEADER => array(
            // 'Authorization: Basic UEszNzY3N182MTVhYmMwNDZlYjc6bmZRSXpJN0hIa3VXTXJyUA==',
            'Authorization: Basic ' . $aAuthorization . '',
            'Content-Type: application/json'
          ),
        )
      );
      $resultx = curl_exec($curlx);
      $allRes = serialize($resultx);


      $error .= $allRes;
      curl_close($curlx);

      $decoded = json_decode($resultx, true);
      $r = [];
      $s = [];
      foreach ($decoded as $id => $row) {

        unset($decoded['locale']);
        unset($decoded['customer']);
        unset($decoded['design']);
        unset($decoded['expires_at']);
        unset($decoded['status']);
        unset($decoded['payment_method_categories']);
        unset($decoded['intent']);
        unset($decoded['authorization_token']);
        unset($decoded['client_token']);
        if ($id == "merchant_urls") {
          $r[$id] = array_merge($row, json_decode(unserialize($check['sessionRequestShipper']), true)[$id]);
        }
      }
      $two = array_merge($r, $s);
      $b = json_decode(unserialize($check['sessionRequestShipper']), true);
      $result = array_merge($b, $two);

      $dataShipping = json_encode($result);

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $dataShipping);




      if ($this->functions->ibms_setting('klarnaPaymentTesting') == '1') {

        $url = 'https://api.playground.klarna.com/payments/v1/authorizations/' . $authorization_token . '/order';
      } else {
        $url = 'https://api.klarna.com/payments/v1/authorizations/' . $authorization_token . '/order';
      }





      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt(
        $curl,
        CURLOPT_HTTPHEADER,
        array(
          // 'Authorization: Basic UEszNzY3N182MTVhYmMwNDZlYjc6bmZRSXpJN0hIa3VXTXJyUA==',
          'Authorization: Basic ' . $aAuthorization . '',
          'Content-Type: application/json'
        )
      );
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      $result = curl_exec($curl);
      curl_close($curl);
      $bb = $result;

      $linkRedirect = json_decode($result, true);


      $error .= serialize($bb);

      $sql = "UPDATE `klarnaAPI` SET `sessionRequestShipper` = ?,`authorization_token` = ?,`sessionResponceShipper` = ?,`sessionReadResponceAll` = ? WHERE `id`= ?";
      $this->dbF->setRow($sql, array(serialize($dataShipping), $authorization_token, serialize($bb), $allRes, $lastId));



      foreach ($linkRedirect as $ids => $rows) {

        if ($linkRedirect['redirect_url']) {



          $sql = "UPDATE `klarnaAPI` SET `redirect_url` = ? WHERE `id`= ?";
          $this->dbF->setRow($sql, array($linkRedirect['redirect_url'], $lastId));

          echo $linkRedirect['redirect_url'];
          exit();
        }
      }
    }

    echo $error;
  }


  public function cart_denied($returnStatus = false)
  {
    if (isset($_POST['den_email'])) {
      global $_e;
      if (!$this->functions->getFormToken('CheckoutSubscription')) {
        return false;
      }

      $den_name = $_POST['den_name'];
      $den_phone = $_POST['den_phone'];
      $l_email = $_POST['den_email'];
      $den_invId = $_POST['den_invId'];



      $sql = "SELECT orderUser FROM order_invoice WHERE `order_invoice_pk` = ?";

      $check = $this->dbF->getRow($sql, array($den_invId));


      $sql = "INSERT INTO `cart_order_remaining`(`email`, `invId`, `userId`, `name`, `phone`) VALUES (?,?,?,?,?)";

      $array = array($l_email, $den_invId, $check['orderUser'], $den_name, $den_phone);
      $this->dbF->setRow($sql, $array);
      if (!$this->dbF->hasException) {
        $sql = "DELETE FROM cart_order_remaining WHERE userId = '" . $check['orderUser'] . "' AND invId <> '" . $den_invId . "'";

        $this->dbF->setRow($sql);


        $sqls = "SELECT invoice_status FROM order_invoice WHERE `order_invoice_pk` = ?";

        $checkData = $this->dbF->getRow($sqls, array($den_invId));

        if ($checkData['invoice_status'] != "1") {

          $sql = "DELETE FROM cart_order_remaining WHERE invId = '$den_invId'";

          $this->dbF->setRow($sql);


          # code...
        }

        echo '1';
      } else {
        echo '0';
      }
    }
  }

  public function setExpressMode()
  {
    $value = $_POST["value"];
    if ($value === "checked") {
      $_SESSION["expressMode"] = "on";
      echo "checked";
    } else {
      unset($_SESSION["expressMode"]);
      echo "unchecked";
    }
  }

  public function updateCart()
  {
    global $_e;
    if (isset($_POST["newqty"]) && isset($_POST["cartId"]) && isset($_POST["scaleId"])) {
      $newQty = $_POST["newqty"];
      $cartId = $_POST["cartId"];
      $scaleId = $_POST["scaleId"];
      $pId = $_POST["pId"];
      
      $sql = "UPDATE cart SET qty = ? WHERE scaleId = ? AND pId = ? AND id = ?";
      $this->dbF->setRow($sql, [$newQty, $scaleId, $pId, $cartId]);
      echo $_e['Quantity Updated Successfully'];
    }
  }

  public function longTimeSubscribe($returnStatus = false)
  {
    if (isset($_POST['l_email'])) {
      global $_e;
      if (!$this->functions->getFormToken('LongWaitForm')) {
        return false;
      }


      $l_name = $_POST['l_name'];
      $l_email = $_POST['l_email'];
      $l_phone = $_POST['l_phone'];

      $sql = "INSERT INTO `long_time_subscribe`(`email`, `name`, `phone_no`) VALUES (?,?,?)";

      $array = array($l_email, $l_name, $l_phone);
      $this->dbF->setRow($sql, $array);
      if (!$this->dbF->hasException) {
        if ($returnStatus) {
          return true;
        } else {
          echo '1';
        }
      } else {
        if ($returnStatus) {
          return false;
        } else {
          echo '0';
        }
      }
    }
  }
}

productAjaxCallEndOfThisPage();
      ?>