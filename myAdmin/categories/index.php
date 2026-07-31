<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu   =   'product';

switch ($page):
  case ('managecat'):
    $subMenu = 'managecat';
    $content = include 'category.php';
    break;
  case ('edit'):
    $subMenu = 'managecat';
    $content = include 'categoryEdit.php';
    break;
  case ('footerMenu'):
    $subMenu = 'footerMenu';
    $content = include 'footerMenu.php';
    break;
  case ('footerMenuEdit'):
    $subMenu = 'footerMenu';
    $content = include 'footerMenuEdit.php';
    break;
  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Manage Categories']) . "</h3> $content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}


?>