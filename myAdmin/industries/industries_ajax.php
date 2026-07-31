<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/industries_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new services_ajax();
    switch($page){
        case 'deleteServices':
            $ajax->deleteServices();
        break;

    }
}

?>