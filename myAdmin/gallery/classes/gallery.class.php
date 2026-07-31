<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class gallery extends object_class{
    public $productF;
    public $imageName;
    public function __construct(){
        parent::__construct('3');


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //Index
        $_w['Gallery Management'] = '' ;

        //gallery Page
        $_w['Manage Album'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Add New'] = '' ;
        $_w['Albums'] = '' ;
        $_w['Add New Album'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;
        //gallery Page End

        //GalleryEdit page
        //$_w['Manage Album'] = '' ;
        $_w['Back To Albums'] = '' ;
        $_w['Image Preview'] = '' ;
        $_w['Close'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;
        $_w['Image Not Delete Please Try Again'] = '' ;
        //GalleryEdit page End

        //This Class Words
        $_w['Album'] = '' ;
        $_w['Album Added'] = '' ;
        $_w['Album Add Successfully'] = '' ;
        $_w['Album Add Failed'] = '' ;
        $_w['Album Add Failed,Please Enter Correct Values,: Error: {{msg}}'] = '' ;

        $_w['Album Update Failed,Please Enter Correct Values,: Error: {{msg}}'] = '' ;
        $_w['Album Update Failed'] = '' ;
        $_w['Album Update'] = '' ;
        $_w['Album Update Successfully'] = '' ;

        $_w['Name'] = '' ;
        $_w['ADD'] = '' ;
        $_w['Enter Album Title'] = '' ;
        $_w['Publish'] = '' ;

        $_w['Mange'] = '' ;
        $_w['Delete'] = '' ;
        $_w['Album Images'] = '' ;
        $_w['Drop images here to upload.'] = '' ;
        $_w['they will only be visible to you'] = '' ;
        $_w['Remove Image'] = '' ;
        $_w['Update Alt'] = '' ;
        $_w['Enter Alt'] = '' ;
        //This Class Words End

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Gallery');

    }

    public function albumAddSubmit(){
        global $_e;

        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newAlbum')){return false;}

            $album          = empty($_POST['album'])   ? ""    : $_POST['album'];
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];

            try{
              $this->db->beginTransaction();
                    $sql    =  "INSERT INTO `gallery` SET
                                        `album`=?,
                                        `publish`=?";

                    $array   = array($album,$publish);
                    $this->dbF->setRow($sql,$array,false);
                    $lastId = $this->dbF->rowLastId;
              $this->db->commit();

                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Album Added']),_uc($_e['Album Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Album Added']),_uc($_e['Album']),$lastId,($_e['Album Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Album Added']),($_e['Album Add Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $msg = $this->dbF->rowException;
                $this->functions->notificationError(_uc($_e['Album']),_replace('{{msg}}',$msg,($_e['Album Add Failed,Please Enter Correct Values,: Error: {{msg}}'])),'btn-danger');
            }

        }
    }

    public function submitEditAlbum(){
        global $_e;
        if(isset($_POST['submit']) && isset($_POST['editId'])){
            if(!$this->functions->getFormToken('editAlbum')){return false;}

            $album          =   empty($_POST['album'])   ? ""    : $_POST['album'];
            $publish        =   empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $lastId         =   $_POST['editId'];
            try{
                $this->db->beginTransaction();
                $sql    =  "UPDATE `gallery` SET
                                        `album`=?,
                                        `publish`=? WHERE gallery_pk=?";

                $array   = array($album,$publish,$lastId);
                $this->dbF->setRow($sql,$array,false);
                $this->db->commit();

                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Album Update']),_uc($_e['Album Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Album Update']),_uc($_e['Album']),$lastId,_uc($_e['Album Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Album Update']),_uc($_e['Album Update Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $msg = $this->dbF->rowException;
                $this->functions->notificationError(_uc($_e['Album']),_replace('{{msg}}',$msg,($_e['Album Update Failed,Please Enter Correct Values,: Error: {{msg}}'])),'btn-danger');
            }

        }
    }



    public function activeAlbums(){
        global $_e;

        echo '<div class="table-responsive sortAlbum">
                <div class="container-fluid activeAlbums">';
        $sql ="SELECT * FROM `gallery` WHERE publish = '1' ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);
        foreach($data as $val){
            $id = $val['gallery_pk'];
            $image = $this->albumFirstImg($id);
            echo '  <div class="singleAlbum " id="album_'.$id.'">
                         <div class="col-sm-12 albumSortTop"> ::: </div>
                         <div class="albumImage"><img src="../images/'.$image.'" /></div>

                        <div class="clearfix"></div>

                        <div class="col-sm-12 albumMange">
                            <div class="col-sm-6 "><a href="-gallery?page=albumManage&albumId='.$id.'" class="btn-default btn">'. _uc($_e['Mange']) .'</a></div>
                            <div class="col-sm-6 "><a data-id="'.$id.'" href="#" onclick="deleteAlbum(this)" class="btn btn-danger ">'. _uc($_e['Delete']) .'</a></div>
                            <div class="col-sm-12 btn-default">'.$val['album'].'</div>
                        </div>
                    </div>';
        }

         echo '</div>';
        echo '</div>';
    }

    public function draftAlbums(){
        global $_e;

        echo '<div class="table-responsive">
                <div class="container-fluid">';
        $sql ="SELECT * FROM `gallery` WHERE publish = '0' ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);
        foreach($data as $val){
            $id = $val['gallery_pk'];
            $image = $this->albumFirstImg($id);
            echo '  <div class="singleAlbum" id="'.$id.'">
                         <div class="col-sm-12 albumSortTop"> ::: </div>
                         <div class="albumImage"><img src="../images/'.$image.'" /></div>

                        <div class="clearfix"></div>

                       <div class="col-sm-12 albumMange">
                            <div class="col-sm-6 "><a href="-gallery?page=albumManage&albumId='.$id.'" class="btn-default btn">'. _uc($_e['Mange']) .'</a></div>
                            <div class="col-sm-6 "><a data-id="'.$id.'" href="#" onclick="deleteAlbum(this)" class="btn btn-danger ">'. _uc($_e['Delete']) .'</a></div>
                            <div class="col-sm-12 btn-default">'.$val['album'].'</div>
                        </div>
                    </div>';
        }

        echo '</div>';
        echo '</div>';
    }


    public function albumFirstImg($id){
        $sql = "SELECT * FROM  `gallery_images` WHERE `gallery_id` = '$id' ORDER BY sort ASC";
        $data = $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0){
            return $data['image'];
        }else{
            return "notFound.png";
        }
    }

    public function albumNewForm(){
        global $_e;
        $token       = $this->functions->setFormToken('newAlbum',false);
        //No need to remove any thing,, go in developer setting table and set 0
        echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '<div class="form-horizontal">';

        //Title
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Name']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="album" class="form-control" placeholder="'. _uc($_e['Enter Album Title']) .'">
                    </div>
                </div>';

        //Publish
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Publish']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                            <input type="checkbox" name="publish" value="1" >
                        </div>
                    </div>
               </div>';

        echo '<button type="submit" name="submit" value="ADD" class="btn btn-lg btn-primary">'. _u($_e['ADD']) .'</button>';
        echo "</div></form>";
    }

    public function albumEdit(){
        global $_e;

        $token  =   $this->functions->setFormToken('editAlbum',false);
        $id     =   $_GET['albumId'];
        $sql    =   "SELECT * FROM gallery where gallery_pk = '$id' ";
        $data   =   $this->dbF->getRow($sql);

        echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '<input type="hidden" name="editId" value="'.$data['gallery_pk'].'"/>
            <div class="form-horizontal">';

        //Title
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Name']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="album" value="'.$data['album'].'" class="form-control" placeholder="'. _uc($_e['Enter Album Title']) .'">
                    </div>
                </div>';

        //Publish
        $checked = "";
        if($data['publish']=='1'){$checked='checked';}
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Publish']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                            <input type="checkbox" name="publish" value="1" '.$checked.'>
                        </div>
                    </div>
               </div>';
        echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary" />';

        echo '<h2 class="tab_heading">'. _uc($_e['Album Images']) .'</h2>
            <input type="hidden" id="AjaxFileNewId" name="ProductNewId" value="'.$id.'">
            <input type="hidden" id="AjaxFileNewPage" value="album">


            <div id="dropbox">';
                $this->albumEditImages($id);
        echo '</div><span class="message">'. _fu($_e['Drop images here to upload.']) .'<br />
                                    <i>('. _fu($_e['they will only be visible to you']) .')</i></span><br>';



        echo "</div>
            </form>";
    }


    public function albumEditImages($id){
        global $_e;
        $qry="SELECT * FROM  `gallery_images` WHERE `gallery_id` = '$id' ORDER BY sort ASC";
            $eData=$this->dbF->getRows($qry);
            if($this->dbF->rowCount>0){
                foreach($eData as $key=>$val){
                    $img    =$val['image'];
                    $imgId  =$val['img_pk'];
                    $alt    = $val['alt'];
                    $place1 =   $_e['Enter Alt'];
                    $place2 =   $_e['Update Alt'];
                    $place3 =   $_e['Remove Image'];
                    echo <<<HTML
                        <div class="preview albumPreview" id="image_$imgId">
                            <span class="imageHolder">
                                 <img src="../images/$img" />
                            </span>

                            <div class="progressHolder album">
                                <input type="text" id="alt-$imgId" value="$alt" placeholder="$place1" class="form-control" style="margin:3px 0">
                                <a class="albumAltUpdate  btn btn-default btn-sm" data-id="$imgId" ><span>$place2</span>
                                    <i class='glyphicon glyphicon-save trash'></i>
                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                </a>
                                <a class="productEditImageDel btn btn-danger btn-sm" data-id="$imgId">$place3</a>
                            </div>
                        </div>
HTML;
                }
            }
        }
}
?>