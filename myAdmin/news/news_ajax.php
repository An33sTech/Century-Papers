<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/news_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new news_ajax();
    switch($page){
        case 'deleteNews':
            $ajax->deleteNews();
        break;

    }
}

?>