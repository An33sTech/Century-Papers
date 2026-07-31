<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/milestones_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new milestones_ajax();
    switch($page){
        case 'milestonesBrand':
            $ajax->milestonesBrand();
        break;
        case 'milestonesSort':
            $ajax->milestonesSort();
            break;
    }
}

?>