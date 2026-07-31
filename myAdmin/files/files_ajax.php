<?php

if (isset($_GET['page'])) {
	require_once __DIR__ . "/classes/files_ajax.class.php";
	$page = $_GET['page'];

	$ajax = new files_ajax();
	switch ($page) {
		case 'deleteFiles':
			$ajax->deleteFiles();
			break;
		case 'filesSort':
			$ajax->filesSort();
			break;
	}
}