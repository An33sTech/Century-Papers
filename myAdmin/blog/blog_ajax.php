<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/blog_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new blog_ajax();
    switch($page){
        case 'deleteBlog':
            $ajax->deleteBlog();
        break;

    }
}

?>