<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/testimonial_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new testimonial_ajax();
    switch($page){
        case 'deleteTestimonial':
            $ajax->deleteTestimonial();
        break;
        case 'testimonialSort':
            $ajax->testimonialSort();
            break;
    }
}

?>