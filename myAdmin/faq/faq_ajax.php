<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/faq_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new faq_ajax();
    switch($page){
        case 'deletefaq':
            $ajax->deletefaq();
        break;
    }
}

?>