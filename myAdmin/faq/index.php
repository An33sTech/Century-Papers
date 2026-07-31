<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once('../global.php');

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="faqM"; // ul menu active

switch($page):
    case ("faq"):
        $subMenu='faq';
        $content = include "faq.php";
        break;
    case ("edit"):
        $subMenu='faq';
        $content = include "faqEdit.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


if (!$isAjax) {
    include_once('../header.php');
}
echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['FAQ Management']) . "</h3>$content</div>";

if (!$isAjax) {
    include_once('../footer.php');
}

?>