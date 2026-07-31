<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'emailin_waitingM';

switch ($page):
  case ('emailin_waiting'):
    $subMenu = 'emailin_waiting';
    $content = include 'emailin_waiting.php';
    break;
  case ('edit'):
    $subMenu = 'emailin_waiting';
    $content = include 'emailin_waitingEdit.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Emails in Waiting']) . "</h3>$content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}

?>