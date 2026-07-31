<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/emailin_waiting_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new emailin_waiting_ajax();
    switch($page){
        case 'deleteemailin_waiting':
            $ajax->deleteemailin_waiting();
        break;
        case 'emailin_waitingSort':
            $ajax->productin_waitingsSort();
            break;
    }
}

?>