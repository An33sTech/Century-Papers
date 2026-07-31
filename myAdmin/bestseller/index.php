<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'product';

switch ($page):
  case ('bestsellers'):
    $subMenu = 'bestsellers';
    $content = include 'bestsellers.php';
    break;
  case ('edit'):
    $subMenu = 'bestsellers';
    $content = include 'bestsellerEdit.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Best Seller Management']) . "</h3> $content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}

?>