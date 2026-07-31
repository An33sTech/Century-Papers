<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once "../global.php";

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = "filesM";

switch ($page):
	case "files":
		$subMenu = 'files';
		$content = include "files.php";
		break;
	case "edit":
		$subMenu = 'files';
		$content = include "filesEdit.php";
		break;
	case "addFiles":
		$subMenu = 'addFiles';
		$content = include "filesNew.php";
		break;
	default:
		$content = "Page Not Found.";
		break;
endswitch;


if (!$isAjax) {
	include_once '../header.php';
}
echo "<div id='content'><h3 class='main_heading'>" . _uc($_e['Reports Management']) . "</h3> $content  </div>";

if (!$isAjax) {
	include_once '../footer.php';
}
