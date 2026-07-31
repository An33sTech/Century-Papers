<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'adminSetting';

switch ($page):
    case ('IBMSSetting'):
        $subMenu = 'IBMSSetting';
        $content = include 'IBMSSetting.php';
        break;
    case ('history'):
        $subMenu = 'history';
        $content = include 'history.php';
        break;
    case ('account'):
        $subMenu = 'account';
        $content = include 'account.php';
        break;
    case ('hardWords'):
        $subMenu = 'hardWords';
        $content = include 'hardWords.php';
        break;
    default:
        $content = 'Page Not Found';
        break;
endswitch;

if (!$isAjax) {
    include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Setting']) . "</h3>$content</div>";

if (!$isAjax) {
    include_once('../footer.php');
}

?>