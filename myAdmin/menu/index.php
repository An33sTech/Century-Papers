<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu   =   'webMenuM';

switch ($page):
  case ('menu'):
    $subMenu = 'menu';
    $content = include 'menu.php';
    break;
  case ('edit'):
    $subMenu = 'menu';
    $content = include 'menuEdit.php';
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

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Manage Website Menu']) . "</h3>$content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}

?>