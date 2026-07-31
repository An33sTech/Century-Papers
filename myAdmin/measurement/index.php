<?php

/**
 * For add new page, just copy past all files,
 * and replace words with new page name.
 * if new fields required use setting_fields table for additional fields,
 */
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'product';

switch ($page):
  case ('measurement'):
    $subMenu = 'Measurement';
    $content = include 'measurement.php';
    break;
  case ('edit'):
    $subMenu = 'Measurement';
    $content = include 'measurementEdit.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Measurement Management']) . "</h3>$content </div>";
if (!$isAjax) {
  include_once('../footer.php');
}

?>