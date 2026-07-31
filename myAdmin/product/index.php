<?php

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

require(__DIR__ . '/classes/product.class.php');

require(__DIR__ . '/ajax.controller.php');

global $menu;

global $subMenu;

$menu = 'product';

switch ($page):

  case ('add'):

    $subMenu = 'New Product';

    $content = include 'add.page.php';

    break;

  case ('list'):

    $subMenu = 'Product View';

    $content = include 'productlist.page.php';

    break;

  case ('edit'):

    $subMenu = 'Product View';

    $content = include 'add.page.php';

    break;

  case ('allProductsInfo'):

    $menu = 'emailM';

    $subMenu = 'allProductsInfo';

    $content = include 'allProductsInfo.php';

    break;

  case ('sort'):

    $subMenu = 'Product Sort';

    $content = include 'sortProduct.php';

    break;

  case ('update'):

    $subMenu = 'Sort Products Image';

    $content = include 'massUpdate.php';

    break;
    
  case ('update2'):

    $subMenu = 'Sort Products Image';

    $content = include 'massUpdate2.php';

    break;

  case ('pDiscount'):

    $subMenu = 'Product Discount';

    require(__DIR__ . '/classes/discount.class.php');

    $content = include 'pDiscount.php';

    break;

  case ('pDiscountForm'):

    $subMenu = 'Product Discount';

    require(__DIR__ . '/classes/discount.class.php');

    $content = include 'pDiscountForm.php';

    break;

  case ('pSale'):

    $subMenu = 'Product Sale';

    require(__DIR__ . '/classes/sale.class.php');

    $content = include 'pHoleSale.php';

    break;

  case ('pHoleSaleForm'):

    $subMenu = 'Product Sale';

    require(__DIR__ . '/classes/sale.class.php');

    $content = include 'pHoleSaleForm.php';

    break;

  case ('pCoupon'):

    $subMenu = 'Product Coupon';

    require(__DIR__ . '/classes/coupon.class.php');

    $content = include 'pCoupon.php';

    break;

  case ('pCouponForm'):

    $subMenu = 'Product Coupon';

    require(__DIR__ . '/classes/coupon.class.php');

    $content = include 'pCouponForm.php';

    break;

  case ('updateDiscount'):

    $subMenu = 'Product View';

    $content = include 'updateDiscount.php';

    break;  
    
    case ('massUpdate'):

    $subMenu = 'Product Mass Update';

    $content = include 'productMassUpdate.php';

    break;

  default:

    $subMenu = '';

    $content = include 'default.page.php';

    break;

endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Product Management']) . "</h3>$content</div>";

if (!$isAjax) {
  include_once('../footer.php');
}


?>