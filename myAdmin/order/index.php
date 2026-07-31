<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'orderManagement';

switch ($page):
  case ('newOrder'):
    $subMenu = 'newOrder';
    $content = include 'newOrder.php';
    break;
  case ('otherOrder'):
    $subMenu = 'otherOrder';
    $content = include 'otherOrder.php';
    break;
  case ('edit'):
    $subMenu = 'newOrder';
    $content = include 'invoice.php';
    break;

  case ('edit2'):
    $subMenu = 'newOrder';
    $content = include 'invoice2.php';
    break;
  case ('csv'):
    $subMenu = 'Import/Export';
    $content = include 'csv.php';
    break;

  case ('readyOrders'):
    $subMenu = 'readyOrders';
    $content = include 'readyOrders.php';
    break;
  case ('semireadyOrders'):
    $subMenu = 'semireadyOrders';
    $content = include 'semireadyOrders.php';
    break;
  case ('relativereadyOrders'):
    $subMenu = 'relativereadyOrders';
    $content = include 'relativereadyOrders.php';
    break;
  case ('visiting'):
    $subMenu = 'Denied Order';
    $content = include 'visiting.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Order / Invoice Management']) . "</h3>$content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}


?>