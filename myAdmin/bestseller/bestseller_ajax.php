<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/bestseller_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new bestseller_ajax();
    switch($page){
        case 'deleteBestseller':
            $ajax->deleteBestseller();
        break;
        case 'bestsellersSort':
            $ajax->bestsellersSort();
            break;
    }
}

?>