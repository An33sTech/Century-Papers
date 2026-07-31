<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'statisticM';

switch ($page):
  case ('statistics'):
    $subMenu = 'produt_statistics';
    $content = include 'pro_statics.php';
    break;  
    
case ('statistics_inv'):
    $subMenu = 'statistics_inv';
    $content = include 'inv_statics.php';
    break;
case ('csv'):
    $subMenu = 'statistics_inv';
    $content = include 'csv.php';
    break;
  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Product Statistics']) . "</h3>$content</div>";

if (!$isAjax) {
  include_once('../footer.php');
}
?>