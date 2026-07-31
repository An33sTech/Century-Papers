<?php

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'statics';

switch ($page):
  case ('statics'):
    $subMenu = 'statics';
    $content = include 'statics.php';
    break;
  case ('edit'):
    $subMenu = 'giftCard';
    $content = include 'giftCardEdit.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Statics Reports']) . "</h3>$content</div>";

if (!$isAjax) {
  include_once('../footer.php');
}

// if (!$isAjax) {
//   include('../header.php');
// }
// //echo '<h3 class="main_heading">'. _uc($_e['Statics Reports']) .'</h3>';
// echo  "<div id='content'>' . $content . ' </div>";

// if (!$isAjax) {
//   include('../footer.php');
// }
?>