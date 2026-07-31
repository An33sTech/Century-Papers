<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/fileManager_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new filesManager_ajax();
    switch($page){
        case 'deleteFileManager':
            $ajax->deleteFilesManager();
        break;
        case 'fileManagerSort':
            $ajax->filesManagerSort();
            break;
    }
}

?>