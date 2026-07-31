<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/deal_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new deal_ajax();
    switch($page){
        case 'deleteDeal':
            $ajax->deleteDeal();
        break;
        case 'dealSort':
            $ajax->dealSort();
            break;
    }
}

?>