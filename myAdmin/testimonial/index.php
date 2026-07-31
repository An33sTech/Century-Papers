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
$menu = 'pages';
// ul menu active

switch ($page):
  case ('testimonial'):
    $subMenu = 'testimonial';
    $content = include 'testimonial.php';
    break;
  case ('edit'):
    $subMenu = 'testimonial';
    $content = include 'testimonialEdit.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Industries Management']) . "</h3>$content</div>";

if (!$isAjax) {
  include_once('../footer.php');
}

?>