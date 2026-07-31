<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/brand_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new brand_ajax();
    switch($page){
        case 'deleteBrand':
            $ajax->deleteBrand();
        break;
        case 'brandsSort':
            $ajax->brandSort();
            break;
    }
}

?>