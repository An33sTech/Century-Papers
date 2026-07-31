<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'milestones';

switch ($page):
  case ('milestones'):
    $subMenu = 'milestones';
    $content = include 'milestone.php';
    break;
  case ('edit'):
    $subMenu = 'milestones';
    $content = include 'milestonesEdit.php';
    break;

  default:
    $content = 'Page Not Found.';
    break;
endswitch;

if (!$isAjax) {
  include('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Milestones Management']) . "</h3>$content </div>";

if (!$isAjax) {
  include('../footer.php');
}

?>