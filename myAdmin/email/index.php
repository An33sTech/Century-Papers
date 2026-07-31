<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = 'emailM';

switch ($page):
    case ('email'):
        $subMenu = 'email';
        $content = include 'email.php';
        break;

    case ('newsLetter'):
        $subMenu = 'newsLetter';
        $content = include 'newsLetter.php';
        break;

    case ('emailContent'):
        $subMenu = 'emailContent';
        $content = include 'emailContent.php';
        break;

    default:
        $content = 'Page Not Found.';
        break;
endswitch;

if (!$isAjax) {
    include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Email Management']) . "</h3>$content </div>";

if (!$isAjax) {
    include_once('../footer.php');
}
?>