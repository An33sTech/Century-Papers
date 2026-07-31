<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/statistic_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new statistic_ajax();
    switch($page){
        case 'size_color_range':
            $ajax->st_product_size_color_range();
            break;
    }
}

?>