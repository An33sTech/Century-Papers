<?php
require_once (__DIR__."/../../global.php"); //connection setting db
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class milestones extends object_class{
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
        $_w['Milestones Management'] = '' ;
        //brandsEdit.php
        $_w['Manage Milestones'] = '' ;

        //brands.php
        $_w['Active Milestones'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Sort Milestones'] = '' ;
        $_w['Add New Milestones'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;
        $_w['SNO'] = '' ;
        $_w['TITLE'] = '' ;
        $_w['IMAGE'] = '' ;
        $_w['ACTION'] = '' ;

        $_w['Image File Error'] = '' ;
        $_w['Image Not Found'] = '' ;
        $_w['Brands'] = '' ;
        $_w['Added'] = '' ;
        $_w['Brand Add Successfully'] = '' ;
        $_w['Brand Add Failed'] = '' ;
        $_w['Brand Update Failed'] = '' ;
        $_w['Brand Update Successfully'] = '' ;
        $_w['Update'] = '' ;
        $_w['Brand Title'] = '' ;
        $_w['Brand Link'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Image Recommended Size : {{size}}'] = '' ;
        $_w['Publish'] = '' ;

        $_w['SAVE'] = '' ;
        $_w['Old Brand Image'] = '' ;
        $_w['Milestone Heading'] = '' ;
        $_w['Milestone Description'] = '' ;
        $_w['Milestone Year'] = '' ;
        $_w['DESCRIPTION'] = '' ;
        $_w['Milestone Add Successfully'] = '' ;
        $_w['Milestone Update Successfully'] = '' ;
        $_w['Milestone'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Brands');

    }


    public function brandsSort(){
        echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
        $sql ="SELECT brand_heading,image,id FROM `brands` WHERE publish = '1' ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $id = $val['id'];
            $image = $val['image'];
            @$title = unserialize($val['brand_heading']);
            @$title = $title[$defaultLang];
            echo '  <div class="singleAlbum " id="album_'.$id.'">
                         <div class="col-sm-12 albumSortTop"> ::: </div>
                         <div class="albumImage"><img src="../images/'.$image.'"  class="img-responsive"/></div>
                        <div class="clearfix"></div>
                        <div class="albumMange col-sm-12">
                            <div class="col-sm-12 btn-default" style="">'.$title.'</div>
                        </div>
                    </div>';
        }

        echo '</div>';
        echo '</div>';
    }


    public function brandsView(){
        $sql  = "SELECT * FROM milestone WHERE publish='1' ORDER BY ID DESC";
        $data =  $this->dbF->getRows($sql);
        $this->brandsPrint($data);
    }

    public function brandsDraft(){
        $sql  = "SELECT * FROM milestone WHERE publish='0' ORDER BY ID DESC";
        $data =  $this->dbF->getRows($sql);
        $this->brandsPrint($data);
    }

    public function brandsPrint($data){
        global $_e;
        $class = 'tableIBMS';
        $heading = false;
        if($this->functions->developer_setting('brand_heading')=='1'){
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
        echo            '<th>'. _u($_e['DESCRIPTION']) .'</th>
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
                @$brand_heading = unserialize($val['heading']);
                @$brand_desc = unserialize($val['description']);
                @$brand_heading = $brand_heading[$defaultLang];
                @$brand_desc = $brand_desc[$defaultLang];
                echo "  <td>$brand_heading</td>";
            }
            echo "  <td>$brand_desc</td>";
            echo "<td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-milestones?page=edit&milestonesId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteBrand(this);' class='btn'>
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

    public function newBrandsAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newBrand')){return false;}

            $heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
            $desc           = empty($_POST['description'])   ? ""    : serialize($_POST['description']);
            $year           = empty($_POST['year'])   ? ""    : serialize($_POST['year']);
            $category           = empty($_POST['category'])   ? ""    : serialize($_POST['category']);
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $file           = empty($_FILES['image']['name'])   ? false    : true;
            $returnImage = "";
            try{
                $this->db->beginTransaction();

                $sql      =   "INSERT INTO `milestone`(`heading`,`description`,`year`,`category`,`publish`)
                                    VALUES (?,?,?,?,?)";
                $array   = array($heading,$desc,$year,$category,$publish);
                $this->dbF->setRow($sql,$array,false);
                $lastId = $this->dbF->rowLastId;
                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Milestone']),($_e['Milestone Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Milestone']),$lastId,($_e['Milestone Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Milestone']),($_e['Milestone Add Failed']),'btn-danger');
                }
            }catch (Exception $e){
                if($returnImage!==false && $file){
                    $this->functions->deleteOldSingleImage($returnImage);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Brands']),($_e['Brand Add Failed']),'btn-danger');
            }
        } // If end
    }




    public function brandsEditSubmit(){
        global $_e;
        if(isset($_POST['submit']) && isset($_POST['editId'])){
            if(!$this->functions->getFormToken('editBrands')){return false;}

            $heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
            $desc           = empty($_POST['description'])   ? ""    : serialize($_POST['description']);
            $year           = empty($_POST['year'])   ? ""    : serialize($_POST['year']);
            $category           = empty($_POST['category'])   ? ""    : serialize($_POST['category']);
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $file           = empty($_FILES['image']['name'])   ? false    : true;
            $returnImage = "";

            $oldImg      = empty($_POST['oldImg'])     ? ""   : $_POST['oldImg'];
            $returnImage = $oldImg;
            try{
                $this->db->beginTransaction();
                $lastId   =   $_POST['editId'];
                $sql    =  "UPDATE `milestone` SET
                                    `heading`=?,
                                    `description`=?,
                                    `year`=?,
                                    `category`=?,
                                    `publish`=?
                                       WHERE id = '$lastId'";

                $array   = array($heading,$desc,$year,$category,$publish);
                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Milestone']),($_e['Milestone Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Update']),_uc($_e['Milestone']),$lastId,($_e['Milestone Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Milestone']),($_e['Milestone Update Failed']),'btn-danger');
                }
            }catch (Exception $e){
                if($returnImage!==false && $file){
                    $this->functions->deleteOldSingleImage($returnImage);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Brands']),($_e['Brand Update Failed']),'btn-danger');
            }

        }
    }

    public function brandsNew(){
        global $_e;
        $this->brandsEdit(true);
    }

    public function brandsEdit($new=false){
        global $_e;
        if($new){
            $token       = $this->functions->setFormToken('newBrand',false);
        }else {
            $id = $_GET['milestonesId'];
            $sql = "SELECT * FROM milestone where id = '$id' ";
            $data = $this->dbF->getRow($sql);

            $token = $this->functions->setFormToken('editBrands', false);
            $token .= '<input type="hidden" name="editId" value="'.$id.'"/>';
        }
        //No need to remove any thing,, go in developer setting table and set 0

        echo '<form method="post" action="-milestones?page=milestones" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '
            <div class="form-horizontal">';

        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }

        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

        echo '<div class="panel-group" id="accordion">';


        @$heading = unserialize($data['heading']);
        @$description =  unserialize($data['description']);
        @$year =  unserialize($data['year']);
        @$selectedCategory = unserialize($data['category']);
// var_dump($selectedCategory);

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
            if($this->functions->developer_setting('brand_heading')=='1'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Milestone Heading']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <input type="text" name="heading['.$lang[$i].']" value="'.@$heading[$lang[$i]].'" class="form-control" placeholder="'. _uc($_e['Milestone Heading']) .'">
                        </div>
                    </div>';
            }else{ echo '<input type="hidden" name="heading['.$lang[$i].']" value="" class="form-control">';}




                   //Link
// if($this->functions->developer_setting('brand_link')=='1'){
// echo '<div class="form-group">
// <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Brand Link']) .'</label>
// <div class="col-sm-10  col-md-9">
// <div class="input-group">

// <input type="url" value="'.@$brand_link[$lang[$i]].'" name="brand_link['.$lang[$i].']" class="'.$lang[$i].'pastLinkHere form-control" placeholder="'. _uc($_e['Brand Link']) .'">
// <div class="input-group-addon '.$lang[$i].'linkList '.$lang[$i].'pointer" data-lang="'.$lang[$i].'"><i class="glyphicon glyphicon-search"></i></div>
// </div>
// </div>
// </div>';
// }else{ echo '<input type="hidden" name="brand_link['.$lang[$i].']" value="" class="form-control">';}


echo '<div class="form-group">
    <label class="col-sm-2 col-md-3 control-label">
        Category
    </label>

    <div class="col-sm-10 col-md-9">
        <select name="category" class="form-control" required>
            <option value="">Select Category</option>

            <option value="awards" ' .
                ($selectedCategory === 'awards' ? 'selected' : '') .
            '>
                Awards
            </option>

            <option value="milestones" ' .
                ($selectedCategory === 'milestones' ? 'selected' : '') .
            '>
                Milestones
            </option>
        </select>
    </div>
</div>';




            //Short Desc
            if($this->functions->developer_setting('brand_shrtDesc')=='0'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Milestone Description']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="description['.$lang[$i].']" id="brand_shrtDesc" maxlength="500" class="form-control ckeditor" placeholder="'. _uc($_e['Milestone Description']) .'">'.@$description[$lang[$i]].'</textarea>
                        </div>
                    </div>';
            }else{ echo '<input type="hidden" name="description['.$lang[$i].']" value="" class="form-control">';}
            
            
            if($this->functions->developer_setting('brand_heading')=='1'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Milestone Year']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <input type="text" name="year['.$lang[$i].']" value="'.@$year[$lang[$i]].'" class="form-control" placeholder="'. _uc($_e['Milestone Year']) .'">
                        </div>
                    </div>';
            }else{ echo '<input type="hidden" name="year['.$lang[$i].']" value="" class="form-control">';}
            
            


            echo '           </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                    </div><!-- panel end-->';
        }

        echo '</div> <!-- .accordian end -->';

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
$this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
$menuC  =   new webMenu();
$menuC->menuWidgetLinks();
    }
}
?>