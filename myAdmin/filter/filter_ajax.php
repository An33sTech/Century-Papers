<?php

if (isset($_GET['page'])) {
    require_once(__DIR__ . "/classes/filter_ajax.class.php");
    $page = $_GET['page'];

    $ajax = new filter_ajax();
    switch ($page) {
        case 'deleteFilter':
            $ajax->deleteFilter();
            break;
    }
}
