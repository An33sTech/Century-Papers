<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class testimonial extends object_class{
    public $productF;
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
        $_w['Industries Management'] = '' ;
        //testimonialEdit.php
        $_w['Manage Industries'] = '' ;

        //testimonial.php
        $_w['Active Industries'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Sort Industries'] = '' ;
        $_w['Add New Industries'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;
        $_w['SNO'] = '' ;
        $_w['TITLE'] = '' ;
        $_w['IMAGE'] = '' ;
        $_w['Sort'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Add New'] = '' ;

        $_w['Image File Error'] = '' ;
        $_w['Image Not Found'] = '' ;
        $_w['Testimonial'] = '' ;
        $_w['Added'] = '' ;
        $_w['Testimonial Add Successfully'] = '' ;
        $_w['Testimonial Add Failed'] = '' ;
        $_w['Testimonial Update Failed'] = '' ;
        $_w['Testimonial Update Successfully'] = '' ;
        $_w['Update'] = '' ;
        $_w['Testimonial Title'] = '' ;
        $_w['Testimonial Link'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Image Recommended Size : {{size}}'] = '' ;
        $_w['Publish'] = '' ;
        $_w['Layer'] = '' ;

        $_w['SAVE'] = '' ;
        $_w['Designation'] = '' ;
        $_w['Email'] = '' ;
        $_w['Date'] = '' ;
        $_w['Old File Image'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Testimonial');

    }


    public function testimonialSort(){
        echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
        $sql ="SELECT testimonial_heading,testimonial_image,id FROM `testimonial` WHERE publish = '1' ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $id = $val['id'];
            @$testimonial_image    =   unserialize($val['testimonial_image']);
            @$image    =  $this->functions->addWebUrlInLink($testimonial_image[$defaultLang]);
            @$title = unserialize($val['testimonial_heading']);
            @$title = $title[$defaultLang];
            echo '  <div class="singleAlbum " id="album_'.$id.'">
                         <div class="col-sm-12 albumSortTop"> ::: </div>
                         <div class="albumImage"><img src="'.$image.'"  class="img-responsive"/></div>
                        <div class="clearfix"></div>
                        <div class="albumMange col-sm-12">
                            <div class="col-sm-12 btn-default" style="">'.$title.'</div>
                        </div>
                    </div>';
        }

        echo '</div>';
        echo '</div>';
    }


    public function testimonialView(){
        $sql  = "SELECT id, testimonial_heading,testimonial_image FROM testimonial WHERE publish='1' ORDER BY ID DESC";
        $data =  $this->dbF->getRows($sql);
        $this->testimonialPrint($data);
    }

    public function testimonialDraft(){
        $sql  = "SELECT id, testimonial_heading,testimonial_image FROM testimonial WHERE publish='0' ORDER BY ID DESC";
        $data =  $this->dbF->getRows($sql);
        $this->testimonialPrint($data);
    }

    public function testimonialPrint($data){
        global $_e;
        $class = 'tableIBMS';
        $heading = false;
        if($this->functions->developer_setting('testimonial_heading')=='1'){
            $class=" dTable tableIBMS";
            $heading = true;
        }
        echo '<div class="table-responsive">
                <table class="table table-hover '.$class.'">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>';
        if($heading){
            echo        '<th>'. _u($_e['TITLE']) .'</th>';
        }
        echo            '<th>'. _u($_e['IMAGE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';

        $i = 0;
        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            echo "<tr>
                    <td>$i</td>";
            if($heading){
                @$testimonial_heading = unserialize($val['testimonial_heading']);
                @$testimonial_heading = $testimonial_heading[$defaultLang];
                echo "<td>$testimonial_heading</td>";
            }

            @$testimonial_image    =   $val['testimonial_image'];

            echo "
                    <td><img src='$testimonial_image' style='max-height:200px;max-with:500px;'/></td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-testimonial?page=edit&fileId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteTestimonial(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }

    public function newTestimonialAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newTestimonial')){return false;}

            $heading        = empty($_POST['testimonial_heading'])   ? ""    : serialize($_POST['testimonial_heading']);
            $link           = empty($_POST['testimonial_link'])      ? ""    : serialize($_POST['testimonial_link']);
            $short_desc     = empty($_POST['testimonial_shrtDesc'])  ? ""    : serialize($_POST['testimonial_shrtDesc']);
            $publish        = empty($_POST['publish'])          ? "0"   : $_POST['publish'];
            $testimonial_image         =    empty($_POST['testimonial_image'])          ? ""    : ($_POST['testimonial_image']);
            $testimonial_position      = empty($_POST['testimonial_position'])          ? ""    : ($_POST['testimonial_position']);
            $testimonial_email         =    empty($_POST['testimonial_email'])          ? ""    : ($_POST['testimonial_email']);
            $testimonial_date         =     empty($_POST['testimonial_date'])          ? ""    : ($_POST['testimonial_date']);

            // $testimonial_image          =   serialize($this->functions->removeWebUrlFromLink($testimonial_image));
            $testimonial_position       =   serialize($this->functions->removeWebUrlFromLink($testimonial_position));
            $testimonial_email          =   serialize($this->functions->removeWebUrlFromLink($testimonial_email));
            $testimonial_date           =   serialize($this->functions->removeWebUrlFromLink($testimonial_date));

            try{
                $this->db->beginTransaction();

                $sql      =   "INSERT INTO `testimonial`(
                                    `testimonial_link`, `testimonial_heading`, `testimonial_shrtDesc`,`testimonial_image`,`testimonial_position`,`testimonial_email`,`testimonial_date`,`publish`)
                                    VALUES (?,?,?,?,?,?,?,?)";

                $array   = array($link,$heading,$short_desc,$testimonial_image,$testimonial_position,$testimonial_email,$testimonial_date,$publish);
                $this->dbF->setRow($sql,$array,false);

                $lastId = $this->dbF->rowLastId;
                $this->functions->setting_fieldsSet($lastId,'testimonial',false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Testimonial']),($_e['Testimonial Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Testimonial']),$lastId,($_e['Testimonial Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Testimonial']),($_e['Testimonial Add Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Testimonial']),($_e['Testimonial Add Failed']),'btn-danger');
            }
        } // If end
    }




    public function testimonialEditSubmit(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('editTestimonial')){return false;}

            $heading        = empty($_POST['testimonial_heading'])   ? ""    : serialize($_POST['testimonial_heading']);
            $link           = empty($_POST['testimonial_link'])      ? ""    : serialize($_POST['testimonial_link']);
            $short_desc     = empty($_POST['testimonial_shrtDesc'])  ? ""    : serialize($_POST['testimonial_shrtDesc']);
            $publish        = empty($_POST['publish'])          ? "0"   : $_POST['publish'];
            $testimonial_image         = empty($_POST['testimonial_image'])          ? ""    : ($_POST['testimonial_image']);
            $testimonial_position     = empty($_POST['testimonial_position'])          ? ""    : ($_POST['testimonial_position']);
            $testimonial_email         = empty($_POST['testimonial_email'])          ? ""    : ($_POST['testimonial_email']);
            $testimonial_date         = empty($_POST['testimonial_date'])          ? ""    : ($_POST['testimonial_date']);

            // $testimonial_image          =   serialize($this->functions->removeWebUrlFromLink($testimonial_image));
            $testimonial_position       =   serialize($this->functions->removeWebUrlFromLink($testimonial_position));
            $testimonial_email          =   serialize($this->functions->removeWebUrlFromLink($testimonial_email));
            $testimonial_date           =   serialize($this->functions->removeWebUrlFromLink($testimonial_date));

            try{
                $this->db->beginTransaction();
                $lastId   =   $_POST['editId'];

                $sql    =  "UPDATE `testimonial` SET
                                    `testimonial_link`=?,
                                    `testimonial_heading`=?,
                                    `testimonial_shrtDesc`=?,
                                    `testimonial_image`=?,
                                    `testimonial_position`=?,
                                    `testimonial_email`=?,
                                    `testimonial_date`=?,
                                    `publish`=?
                                       WHERE id = ?";

                $array   = array($link, $heading, $short_desc,
                                    $testimonial_image,$testimonial_position,$testimonial_email,$testimonial_date,
                                $publish,$lastId);
                $this->dbF->setRow($sql,$array,false);
                $this->functions->setting_fieldsSet($lastId,'testimonial',false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Testimonial']),($_e['Testimonial Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Update']),_uc($_e['Testimonial']),$lastId,($_e['Testimonial Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Testimonial']),($_e['Testimonial Update Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Testimonial']),($_e['Testimonial Update Failed']),'btn-danger');
            }

        }
    }

    public function testimonialNew(){
        global $_e;
        $this->testimonialEdit(true);
    }

    public function testimonialEdit($new=false){
        global $_e;
        if($new){
            $token       = $this->functions->setFormToken('newTestimonial',false);
        }else {
            $id = $_GET['fileId'];
            $sql = "SELECT * FROM testimonial where id = '$id' ";
            $data = $this->dbF->getRow($sql);

            $token = $this->functions->setFormToken('editTestimonial', false);
            $token .= '<input type="hidden" name="editId" value="'.$id.'"/>';
        }

        $size = $this->functions->developer_setting('testimonial_size');
        //No need to remove any thing,, go in developer setting table and set 0

        echo '<form method="post" action="-testimonial?page=testimonial" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '
            <div class="form-horizontal">';

        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }

        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

        echo '<div class="panel-group" id="accordion">';


        @$testimonial_heading = unserialize($data['testimonial_heading']);
        @$testimonial_shrtDesc =  unserialize($data['testimonial_shrtDesc']);
        @$testimonial_image = $data['testimonial_image'];
        @$testimonial_position = unserialize($data['testimonial_position']);
        @$testimonial_email = unserialize($data['testimonial_email']);
        @$testimonial_date = unserialize($data['testimonial_date']);
        @$testimonial_link = getTextFromSerializeArray($data['testimonial_link']);

        $developer_testimonial_heading = $this->functions->developer_setting('testimonial_heading');
        $developer_testimonial_shrtDesc     = $this->functions->developer_setting('testimonial_shrtDesc');
        $developer_testimonial_shrtDescEditor = $this->functions->developer_setting('testimonial_shrtDescEditor');
        $developer_testimonial_image     = $this->functions->developer_setting('testimonial_image');
        $developer_testimonial_position      = $this->functions->developer_setting('testimonial_position');
        $developer_testimonial_email      = $this->functions->developer_setting('testimonial_email');
        $developer_testimonial_date      = $this->functions->developer_setting('testimonial_date');

        for ($i = 0; $i < sizeof($lang); $i++) {
            if ($i == 0) {
                $collapseIn = ' in ';
            } else {
                $collapseIn = '';
            }

            echo '<div class="panel panel-default">
                          <div class="panel-heading">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
                                    <h4 class="panel-title">
                                        '.$lang[$i].'
                                    </h4>
                                 </a>
                          </div>
                          <div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
                             <div class="panel-body">';

            //Title
            if($developer_testimonial_heading=='1'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['TITLE']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <input type="text" name="testimonial_heading['.$lang[$i].']" value="'.@$testimonial_heading[$lang[$i]].'" class="form-control" placeholder="'. _uc($_e['Testimonial Title']) .'">
                        </div>
                    </div>';
            }else{ echo '<input type="hidden" name="testimonial_heading['.$lang[$i].']" value="" class="form-control">';}


            //testimonial_testimonial_image
            if($developer_testimonial_image=='1'){
                $image0 = $testimonial_image ;
                echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label"></label>
                    <div class="col-sm-10  col-md-9 ">
                        <img src="'.$image0.'" class="testimonial_image_'.$i.' kcFinderImage"/>
                    </div>
                </div>';

                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _replace('{{size}}',$size,$_e['Image Recommended Size : {{size}}']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <div class="input-group">
                                <input type="url"  name="testimonial_image" value="'.$image0.'" class="testimonial_image_'.$i.' form-control" placeholder="">
                                <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('testimonial_image_$i')".'"><i class="glyphicon glyphicon-picture"></i></div>
                            </div>
                        </div>
                    </div>';
            }else{ echo '<input type="hidden" name="testimonial_image" value="" class="form-control">';}
            
            echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Testimonial Link']) .'</label>
                        <div class="col-sm-10  col-md-9">
                        <div class="input-group">
                        <input type="url" value="'.$testimonial_link.'" name="testimonial_link['.$lang[$i].']" class="'.$lang[$i].'pastLinkHere form-control" placeholder="'. _uc($_e['Testimonial Link']) .'">
                        <div class="input-group-addon '.$lang[$i].'linkList '.$lang[$i].'pointer" data-lang="'.$lang[$i].'"><i class="glyphicon glyphicon-search"></i></div>
                        </div>
                        </div>
                        </div>';


            echo '           </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                    </div><!-- panel end-->';
        }

        echo '</div> <!-- .accordian end -->';


        //Link
        // if($this->functions->developer_setting('testimonial_link')=='0'){
        //     echo '<div class="form-group">
        //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Testimonial Link']) .'</label>
        //             <div class="col-sm-10  col-md-9">
        //                 <input type="url" name="testimonial_link" value="'.@$data['testimonial_link'].'" class="form-control" placeholder="'. _uc($_e['Testimonial Link']) .'">
        //             </div>
        //         </div>';
        // }else{ echo '<input type="hidden" name="testimonial_link" value="" class="form-control">';}

        //Publish
        $checked = "";
        if(@$data['publish']=='1'){$checked='checked';}
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Publish']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                            <input type="checkbox" name="publish" value="1" '.$checked.'>
                        </div>
                    </div>
               </div>';

        //echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _uc($_e['SAVE']) .'</button>';

        echo "</div>
             </form>";
             $this->functions->includeOnceCustom(ADMIN_FOLDER . "/menu/classes/menu.class.php");
        $menuC  =   new webMenu();
        $menuC->menuWidgetLinks();
    }
}
?>