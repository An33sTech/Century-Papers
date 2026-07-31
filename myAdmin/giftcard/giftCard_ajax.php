<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/giftCard_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new giftCard_ajax();
    switch($page){
        case 'deleteGiftCard':
            $ajax->deleteGiftCard();
        break;
        case 'saleGiftCard':
            $ajax->saleGiftCard();
            break;

    }
}

?>