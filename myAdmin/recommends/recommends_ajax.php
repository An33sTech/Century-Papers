<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/recommends_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new recommends_ajax();
    switch($page){
        case 'deleteRecommends':
            $ajax->deleteRecommends();
        break;
        case 'recommendssSort':
            $ajax->recommendssSort();
            break;
    }
}

?>