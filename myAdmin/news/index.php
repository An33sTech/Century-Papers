<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'newsM';

switch ($page):
  case ('news'):
    $subMenu = 'news';
    $content = include 'news.php';
    break;
  case ('edit'):
    $subMenu = 'news';
    $content = include 'newsEdit.php';
    break;
  case ('addNews'):
    $subMenu = 'addNews';
    $content = include 'newsNew.php';
    break;
  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['News Management']) . "</h3>$content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}

?>