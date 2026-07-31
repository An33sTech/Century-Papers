<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = "logs";

switch ($page):
  case ("defectReg"):
    $subMenu = 'defectReg';
    $content = include "defectReg.php";
    break;
  case ("defectArchive"):
    $subMenu = 'defectArchive';
    $content = include "defectArchive.php";
    break;

  case ("returnReg"):
    $subMenu = 'returnReg';
    $content = include "returnReg.php";
    break;
  case ("productReturn"):
    $subMenu = 'productReturn';
    $content = include "productReturn.php";
    break;
  case ("productDefect"):
    $subMenu = 'productDefect';
    $content = include "productDefect.php";
    break;
  case ("all_returns"):
    $subMenu = 'all_returns';
    $content = include "all_returns.php";
    break;
  case ("all_returns_1"):
    $subMenu = 'all_returns';
    $content = include "all_returns_1.php";
    break;

  default:
    $content = "Page Not Found.";
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Logs Management']) . "</h3>$content </div>";

if (!$isAjax) {
  include_once('../footer.php');
}
?>

<script>
$('#orderProcess').on('click', function() {

    var form_action = $(this).attr("data-action");

    var formBody = '<form action="' + form_action + '" method="POST" target="_blank">';

    var href = $(this).attr("href");
    href = href.replace("?", "");
    var str_array = href.split('&');
    for (var i = 0; i < str_array.length; i++) {
        str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
        var x2str = str_array[i];
        var str_array_2 = x2str.split('=');
        for (var i2 = 0; i2 < str_array_2.length; i2 = i2 + 2) {
            formBody += '<input type="hidden" name="' + str_array_2[i2] + '" value="' + str_array_2[(i2 + 1)] +
                '" />';
        }
    }
    formBody += '</form>';
    var $form = $(formBody).appendTo('body');
})
</script>