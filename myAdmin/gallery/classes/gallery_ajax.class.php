<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class gallery_ajax extends object_class{
    public function __construct(){
        parent::__construct('3');
    }

public function albumEditImageDel(){
    $id=$_POST['imageId'];

    $sql3="SELECT * FROM `gallery_images` WHERE `img_pk`='$id'";
    $data=$this->dbF->getRow($sql3);

    $this->functions->deleteOldSingleImage($data['image']);

    $sql3="DELETE FROM `gallery_images` WHERE `img_pk`='$id'";
    $this->dbF->setRow($sql3);

    if($this->dbF->rowCount>0){
        echo "1";
    }else{
        echo "0";
    }
}

    public function deleteAlbum(){
        $id=$_POST['id'];
        $sql3="SELECT * FROM `gallery_images` WHERE `gallery_id`='$id'";
        $da=$this->dbF->getRows($sql3);
        foreach($da as $data){
            $this->functions->deleteOldSingleImage($data['image']);
        }

        $sql3="DELETE FROM `gallery` WHERE `gallery_pk`='$id'";
        $this->dbF->setRow($sql3);

        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }

    public function albumAltUpdate(){
        $id=$_POST['imageId'];
        $alt=$_POST['altT'];
        $sql3="UPDATE `gallery_images` SET alt=? WHERE `img_pk`=?";
        $array = array($alt,$id);
        $data=$this->dbF->setRow($sql3,$array);
        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }

    public function sortAlbumImage(){
        $list=$_POST['image'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `gallery_images` SET sort = ? WHERE `img_pk` = ?";
            $data=$this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }

    public function activeAlbums(){
        $list=$_POST['album'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `gallery` SET sort = ? WHERE `gallery_pk` = ?";
            $data=$this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }
}
?>