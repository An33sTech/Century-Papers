<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/measurement_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new measurement_ajax();
    switch($page){
        case 'deleteMeasurement':
            $ajax->deleteMeasurement();
        break;
        case 'measurementSort':
            $ajax->measurementSort();
            break;
    }
}

?>