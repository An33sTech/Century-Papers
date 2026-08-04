<?php
if (session_status() === PHP_SESSION_NONE || session_id() === '') {

    session_set_cookie_params([
        'lifetime' => 3600 * 24 * 7,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    ini_set('session.cookie_secure', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    session_start();
}
/*****************************************/
global $db, $dbF;
include_once "_models/setting/global.setting.php"; // connection setting db
require_once "_models/functions/functions.as.php"; // execute query functions

require_once __DIR__ . "/" . ADMIN_FOLDER . "/functions/check_license.php"; // License

global $db, $dbF;

include_once __DIR__ . "/_models/traits/session_security.php"; // check session set
//Move // Encrypting_ trait functions.Encode Fun call in login Fun
//Login Functions move...
require_once __DIR__ . "/_models/traits/ajax_functions.php"; // .
require_once __DIR__ . '/_models/traits/admin_permission.php';
require_once __DIR__ . "/_models/traits/common_functions.php";
require_once __DIR__ . "/_models/traits/common_functions2.php";

require_once __DIR__ . "/_models/functions/menu.php"; //
require_once __DIR__ . "/_models/functions/web_functions.php"; //
require_once __DIR__ . "/_models/functions/sm_functions.php"; //

require_once __DIR__ . "/_models/traits/form_view.php";
/**************a***************************/

include_once __DIR__ . "/_models/functions/main_functions.php"; //clear only country list function

global $functions;
$functions = new functions();
$menuClass = new menu();

$webClass = new web_functions();
//Get seo Array
$seo = $webClass->webSeoNew();


$adminPanelLanguage = currentWebLanguage(); // when axes admin file for translation use this variable before webProduct and after Web_functions
//If product has in project
require_once __DIR__ . '/_models/functions/webProduct_functions.php';
$productClass = new webProduct_functions();

//only for shark project
$cur_define = false;
if (isset($_GET['lang'])) {
    switch ($_GET['lang']) {
            //now currency change option hide from admin
        case "Swedish":
            $cur_define = true;
            $_GET['currency'] = '20';
            break;
        case "Norwegian":
            $cur_define = true;
            $_GET['currency'] = '23';
            break;
        case "Danish":
            $cur_define = true;
            $_GET['currency'] = '24';
            break;
        case "Finnish":
            $cur_define = true;
            $_GET['currency'] = '25';
            break;
        case "French":
            $cur_define = true;
            $_GET['currency'] = '26';
            break;
        case "German":
            $cur_define = true;
            $_GET['currency'] = '26';
            break;
        case "English":
            $cur_define = true;
            $_GET['currency'] = '26';
            break;
    }
}
define("cur_define", $cur_define);
//only for shark project End

$productClass->setMultiCurrency();
//multi language function call in web class constructor

$_SESSION['logo'] = uniqid(4);
