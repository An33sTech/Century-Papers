<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'product';

switch ($page):
  case ('csv'):
    $subMenu = 'impExp';
    $content = include 'csv.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc(@$_e['Product Manangement']) . "</h3>$content</div>";

if (!$isAjax) {
  include_once('../footer.php');
}

?>