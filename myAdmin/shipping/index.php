<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'shippingManagement';

switch ($page):

    case ('shipping'):
        $subMenu    =   'shipping by weight';
        $content    =   include 'shipping.php';
        break;

    case ('edit'):
        $subMenu    =   'shipping by weight';
        $content    =   include 'shippingEdit.php';
        break;

    case ('shippingByClass'):
        $subMenu    =   'shipping by class';
        $content    =   include 'shippingByClass.php';
        break;    
        
    case ('orderTracking'):
        $subMenu    =   'order tracking';
        $content    =   include 'orderTracking.php';
        break;

    default:
        $content = 'Page Not Found.';
        break;
endswitch;

if (!$isAjax) {
    include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Shipping Management']) . "</h3>$content</div>";

if (!$isAjax) {
    include_once('../footer.php');
}

?>