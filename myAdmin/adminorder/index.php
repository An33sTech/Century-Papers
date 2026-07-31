<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'adminorderManagement';

switch ($page):
    case ('newOrder'):
        $subMenu = 'newOrder';
        $content = include 'newOrder.php';
        break;
    case ('edit'):
        $subMenu = 'newOrder';
        $content = include 'invoice.php';
        break;
    case ('csv'):
        $subMenu = 'Import/Export';
        $content = include 'csv.php';
        break;

    default:
        $content = 'Page Not Found.';
        break;
endswitch;

if (!$isAjax) {
    include_once('../header.php');
}

echo  "<div id='content shopSelling'><h3 class='main_heading'>" . _uc($_e['Order / Invoice Management']) . "</h3> $content  </div>";

if (!$isAjax) {
    include_once('../footer.php');
}
?>