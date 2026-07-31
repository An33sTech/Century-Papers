<?php



if(isset($_GET['page'])){

    require_once(__DIR__ . "/classes/proStatics_ajax.class.php");

    $page=$_GET['page'];



    $ajax=new proStatics();

    switch($page){
    case 'all_products':
    case 'SE':
    case 'NO':
    case 'FI':
    case 'DK':
    case 'CH':
     $ajax->fetch_products();
    break;

    }

}



?>