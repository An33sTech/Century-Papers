<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/logs.class.php");
    $page=$_GET['page'];

    $ajax=new logs();
    switch($page){
        case 'defectProductDel':
            $ajax->defectProductDel();
        break;
        case 'defectEditImageDel':
            $ajax->defectEditImageDel();
            break;
        case 'defectDel':
            $ajax->defectDel();
        break;
        case 'returnDel':
            $ajax->returnDel();
        break;

        case 'returnReport':
            $ajax->returnReport();
            break;
        case 'returnFormDel':
            $ajax->returnFormDel();
            break;
        case 'get_inprocess_prod':
            $ajax->get_inprocess_prod();
            break;
            
        case 'pendingOrder':
        $ajax->pendingOrder();
        break;
        case 'get_process_orders':
            $ajax->get_process_orders();
            break;
            case 'pImg':
            $ajax->pImg();
        break;




    }
}

?>