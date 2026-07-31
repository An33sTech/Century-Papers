<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/gallery_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new gallery_ajax();
    switch($page){
        case 'albumEditImageDel':
            $ajax->albumEditImageDel();
        break;

        case 'albumAltUpdate':
            $ajax->albumAltUpdate();
            break;

        case 'sortAlbumImage':
        $ajax->sortAlbumImage();
        break;

        case 'activeAlbums':
            $ajax->activeAlbums();
            break;

        case 'deleteAlbum':
            $ajax->deleteAlbum();
            break;
    }
}

?>