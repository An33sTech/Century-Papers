<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class pages extends object_class{
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
        $_w=array();//homePage.php
        $_w['Manage Home Page Content'] = '' ;
        $_w['Home Page Boxes'] = '' ;
        $_w['Manage'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;

        //homePageEdit.php
        $_w['Update Home Page Box'] = '' ;

        //index.php
        $_w['Pages Management'] = '' ;
        //page.php
        $_w['Manage Pages'] = '' ;
        $_w['Pages'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Add New Page'] = '' ;
        $_w['UnPublish Pages'] = '' ;
        //pageEdit.php
        $_w['Update'] = '' ;

        //This class
        $_w['SNO'] = '' ;
        $_w['SLUG'] = '' ;
        $_w['TITLE'] = '' ;
        $_w['UPDATE'] = '' ;
        $_w['ACTION'] = '' ;

        $_w['Image File Error'] = '' ;
        $_w['Page'] = '' ;
        $_w['Page Save Successfully'] = '' ;
        $_w['Page Add Successfully'] = '' ;
        $_w['Page Save Failed,Please Enter Correct Values, And unique slug'] = '' ;
        $_w['Added'] = '' ;
        $_w['Update'] = '' ;
        $_w['Detail'] = '' ;
        $_w['Page Setting'] = '' ;
        $_w['Page Detail'] = '' ;

        $_w['Title'] = '' ;
        $_w['Page Title'] = '' ;
        $_w['Sub Title'] = '' ;
        $_w['Short Description'] = '' ;
        $_w['Enter Short Description'] = '' ;
        $_w['Enter Full Detail'] = '' ;
        $_w['use : {{contactForm}} FOR CONTACT FORM'] = '' ;
        $_w['PageLink'] = '' ;
        $_w['Custom Page Slug'] = '' ;
        $_w['You Can write Your Custom Page Link,Leave Blank For Default'] = '' ;
        $_w['Redirect Link'] = '' ;
        $_w['Allow Comment'] = '' ;
        $_w['Publish'] = '' ;
        $_w['Old Banner Image'] = '' ;
        $_w['Page Banner Image'] = '' ;
        $_w['SAVE'] = '' ;
        $_w['BOX NAME'] = '' ;

        $_w['Page Not Found For Update'] = '' ;
        $_w['Image'] = '' ;
        $_w['Old Image'] = '' ;
        $_w['Link'] = '' ;
        $_w['Link Text'] = '' ;
        $_w['Update Detail'] = '' ;
        $_w['Update Box'] = '' ;
        $_w['Home Page Box Save Successfully'] = '' ;
        $_w['Home Page Box Save Failed'] = '' ;
        $_w['Home Page Box'] = '' ;
        $_w['Login Required'] = '' ;
        $_w['Yes'] = '' ;
        $_w['No'] = '' ;
        $_w['use : {{employee}} FOR EMPLOYEE PAGE'] = '' ;
        $_w['use : {{files-Manager}} FOR FILES MANAGER PAGE'] = '' ;
        $_w['use : {{testimonial}} FOR TESTIMONIAL PAGE'] = '' ;

        $_w['use : {{albumSingle(AlbumName)}} FOR SINGLE ALBUM (Enter your album name inside ())'] = '' ;
        $_w['use : {{albumAll}} FOR ALL ALBUMS'] = '' ;
        $_w["use : {{albumPictures(AlbumName)}} FOR ALBUM's ALL IMAGES (Enter your album name inside ())"] = '' ;
        $_w['Use Widget In Your Page'] = '' ;
        $_w['Close'] = '' ;
        $_w['Use Widgets'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Page Management');

    }


    public function pagesView(){
        $sql  = "SELECT page_pk,slug,heading,dateTime FROM pages WHERE publish = '1' ORDER BY page_pk DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->printViewTable($data);
    }

    public function pagesDraft(){
        $sql  = "SELECT page_pk,slug,heading,dateTime FROM pages WHERE publish = '0'  ORDER BY page_pk DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->printViewTable($data);
    }

    private function printViewTable($data){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>';
        $slug = false;
        if($this->functions->developer_setting('page_slug')=='1'){
            echo '<th>'. _u($_e['SLUG']) .'</th>';
            $slug = true;
        }
        echo '          <th>'. _u($_e['TITLE']) .'</th>
                        <th>'. _u($_e['UPDATE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $i = 0;
        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $i++;
            $id = $val['page_pk'];
            $heading = unserialize($val['heading']);
            $heading = $heading[$defaultLang];
            echo "<tr>
                    <td>$i</td>";
            if($slug){
                echo "  <td>$val[slug]</td>";
            }

            $seoLink = '';
            if($this->functions->developer_setting('seo') == '1'){
                $this->functions->getAdminFile("seo/classes/seo.class.php");
                $seoC = new seo();
                $seoLink = $seoC->seoQuickLink($id,urlencode("/".$this->db->dataPage."$val[slug]"));
            }

            echo "  <td>$heading</td>
                    <td>$val[dateTime]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            $seoLink
                            <a data-id='$id' href='-".$this->functions->getLinkFolder()."?page=edit&pageId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deletePage(this);' class='btn'>
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


    public function newPageAdd(){
        global $_e;
        if(isset($_POST['heading']) && isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newPage')){return false;}

            $heading        = empty($_POST['heading'])     ? ""    : serialize($_POST['heading']);
            $sub_heading    = empty($_POST['sub_heading']) ? ""    : serialize($_POST['sub_heading']);
            $short_desc     = empty($_POST['short_desc'])  ? ""    : serialize($_POST['short_desc']);
            $dsc            = empty($_POST['dsc'])         ? ""    : base64_encode(serialize($_POST['dsc']));
            $slug           = empty($_POST['slug'])        ? ""    : sanitize_slug($_POST['slug']);
            $redirect       = empty($_POST['redirect'])    ? ""    : $_POST['redirect'];
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $comment        = empty($_POST['comment'])     ? "0"   : $_POST['comment'];
            $file           = empty($_FILES['page_banner']['name'])? false    : true;
            $returnImage    = "";

            $redirect       = str_replace(WEB_URL,'',$redirect);

            try{
                $this->db->beginTransaction();
                if($file){
                    $returnImage =  $this->functions->uploadSingleImage($_FILES['page_banner'],'pages',$slug);
                    if($returnImage==false){
                        throw new Exception($_e['Image File Error']);
                    }
                }
                $sql      =   "INSERT INTO `pages`
                                ( `heading`, `sub_heading`,
                                  `short_desc`, `dsc`, `redirect`,
                                  `publish`, `comment`,`page_banner`
                                 ) VALUES (?,?,  ?,?,?,   ?,?,?)";

                $array   = array($heading,$sub_heading,
                    $short_desc,$dsc,$redirect,
                    $publish,$comment,$returnImage);

                $this->dbF->setRow($sql,$array,false);
                $lastId  =   $this->dbF->rowLastId;
                $this->functions->setting_fieldsSet($lastId,'pages',false);

                $sql_slug = "SELECT * FROM pages where slug = '$slug' ";
                $data_slug = $this->dbF->getRow($sql_slug);

                if($this->dbF->rowCount!=0){
                    $slug = $slug."-".rand(1, 15);
                }


                if($slug == ""){
                    $slug = $this->db->dataPage.$lastId;
                }

                $sql ="UPDATE `pages` SET slug = ? WHERE page_pk = ?";
                $arry = array($slug,$lastId);
                $this->dbF->setRow($sql,$arry,false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Successfully'])),'btn-success');
                    $this->functions->setlog(_js(_uc($_e['Added'])),_js(_uc($_e['Page'])),$lastId,_js(_uc($_e['Page Save Successfully'])));
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');
                }
            }catch (Exception $e){
                if($returnImage!==false){
                    $this->functions->deleteOldSingleImage($returnImage);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');
            }
        } //If end
    }




    public function PageEditSubmit(){
        global $_e;
        if(isset($_POST['heading']) && isset($_POST['submit'])){
            if(!$this->functions->getFormToken('editPage')){return false;}

            $heading        = empty($_POST['heading'])     ? ""    : serialize($_POST['heading']);
            $sub_heading    = empty($_POST['sub_heading']) ? ""    : serialize($_POST['sub_heading']);
            $short_desc     = empty($_POST['short_desc'])  ? ""    : serialize($_POST['short_desc']);
            $dsc            = empty($_POST['dsc'])         ? ""    : base64_encode(serialize($_POST['dsc']));
            $slug           = empty($_POST['slug'])        ? ""    : sanitize_slug($_POST['slug']);
            $redirect       = empty($_POST['redirect'])    ? ""    : $_POST['redirect'];
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $comment        = empty($_POST['comment'])     ? "0"   : $_POST['comment'];
            $file           = empty($_FILES['page_banner']['name'])? false    : true;

            $oldImg         = empty($_POST['oldImg'])     ? ""   : $_POST['oldImg'];
            $returnImage    = $oldImg;

            $redirect       = str_replace(WEB_URL,'',$redirect);

            try{
                $this->db->beginTransaction();

                $lastId   =   $_POST['editId'];
                if($file){
                    $this->functions->deleteOldSingleImage($oldImg);
                    $returnImage = $this->functions->uploadSingleImage($_FILES['page_banner'],'pages',$slug);
                }
                
                $check = $this->functions->check_slug_duplicate($slug);

                if(!$check){
                    $slug = $slug."-".rand(1, 15);
                }

                $sql      =   "UPDATE `pages` SET
                                `slug`      = ?,
                                `heading`   =?,
                                `sub_heading`=?,
                                `short_desc`=?,
                                `dsc`       =?,
                                `redirect`  =?,
                                `publish`   =?,
                                `comment`   =?,
                                `page_banner`=?
                                 WHERE page_pk = ?";

                $array   = array($slug,$heading,$sub_heading,
                    $short_desc,$dsc,$redirect,
                    $publish,$comment,$returnImage,$lastId);
                $this->dbF->setRow($sql,$array,false);
                $this->functions->setting_fieldsSet($lastId,'pages',false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Successfully'])),'btn-success');
                    $this->functions->setlog(_js(_uc($_e['Update'])),_js(_uc($_e['Page'])),$lastId,_js(_uc($_e['Page Save Successfully'])));
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');
                }

            }catch (Exception $e) {
                if ($file && $returnImage !== false) {
                    $this->functions->deleteOldSingleImage($returnImage);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_js(_uc($_e['Page'])), _js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])), 'btn-danger');
            }
        }
    }



    public function pageNew(){
        $this->pageEdit(true);
        return '';
    }

    public function pageEdit($new = false){
        global $_e;
        $isEdit = false;
        if($new ===true){
            $token       = $this->functions->setFormToken('newPage',false);
        }else {
            //For Edit Page.
            $isEdit = true;
            $token = $this->functions->setFormToken('editPage', false);
            $id = $_GET['pageId'];
            $sql = "SELECT * FROM pages where page_pk = '$id' ";
            $data = $this->dbF->getRow($sql);

            if($this->dbF->rowCount==0){
                echo  _uc($_e["Page Not Found For Update"]);
                return false;
            }

            $settingInfo = $this->functions->setting_fieldsGet($id,'pages');
        }
        //No need to remove any thing,, go in developer setting table and set 0
        echo '<form method="post" action="-pages?page=page" class="form-horizontal" role="form" enctype="multipart/form-data">
                <input type="hidden" name="editId" value="'.@$id.'"/>'.
                $token.
                '<div class="form-horizontal">
            <!-- Nav tabs -->
                <ul class="nav nav-tabs tabs_arrow" role="tablist">
                    <li class="active"><a href="#homeDetail" role="tab" data-toggle="tab">'. _uc($_e['Detail']) .'</a></li>
                    <li><a href="#setting" role="tab" data-toggle="tab">'. _uc($_e['Page Setting']) .'</a></li>
                </ul>

                <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active container-fluid" id="homeDetail">
                            <h2  class="tab_heading">'. _uc($_e['Page Detail']) .'</h2>
            ';

        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }
        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

        echo '<div class="panel-group" id="accordion">';
        //For Edit
        if($isEdit) {
            $heading = unserialize($data['heading']);
            $sub_heading = unserialize($data['sub_heading']);
            $short_desc = unserialize($data['short_desc']);
            $dsc = unserialize(base64_decode($data['dsc']));
        }
        //For Edit End

        for ($i = 0; $i < sizeof($lang); $i++) {
            if($i==0){
                $collapseIn = ' in ';
            }else{
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
            echo '              <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Title']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.@$heading[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Page Title']) .'">
                                    </div>
                                </div>';

            //Sub Heading
            if($this->functions->developer_setting('page_subHeading')=='1'){
                echo '          <div class="form-group">
                                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Sub Title']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text"  value="'.@$sub_heading[$lang[$i]].'" name="sub_heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Sub Title']) .'">
                                    </div>
                               </div>';
            }else{ echo '<input type="hidden" name="sub_heading" value="" class="form-control">';}


            //Short Desc
            if($this->functions->developer_setting('page_shortDesc')=='1'){
                echo '          <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Description']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <textarea name="short_desc['.$lang[$i].']" class="ckeditor form-control" placeholder="'. _uc($_e['Enter Short Description']) .'" maxlength="500">'.@$short_desc[$lang[$i]].'</textarea>
                                    </div>
                               </div>';
            }else{ echo '<input type="hidden" name="short_desc['.$lang[$i].']" value="" class="form-control">';}

            //Desc

            echo '            <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Detail']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <textarea name="dsc['.$lang[$i].']" id="dsc_'.$lang[$i].'" placeholder="'. _uc($_e['Enter Full Detail']) .'" class="ckeditor">'.@$dsc[$lang[$i]].'</textarea>

                                        <a href="#"  class="btn btn-sm btn-info" data-toggle="modal" data-target="#useWidgets">'.$_e['Use Widgets'].'</a>

                                    </div>
                               </div>';

            echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>
                ';
        }
        echo '</div>';





        echo '</div> <!-- home Tab End -->
                     <div class="tab-pane fade in container-fluid" id="setting">
                            <h2  class="tab_heading">'. _uc($_e['Page Setting']) .'</h2>
';

        if($isEdit) {
            ## Correction point 1 //pageLink 
            $link = WEB_URL . '/' . $this->db->dataPage . $data['slug'];
            echo '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['PageLink']) .'</label>
                            <div class="col-sm-10  col-md-9">
                                <input type="text" value="' . $link . '" class="form-control" placeholder="'. _uc($_e['PageLink']) .'" readonly>
                            </div>
                        </div>';
        }
        //Slug
        if($this->functions->developer_setting('page_slug')=='1'){
            echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Custom Page Slug']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input  pattern="[A-Za-z0-9-_]{1,150}" type="text" name="slug" class="form-control" value="'.@$data['slug'].'" placeholder="'. _uc($_e['You Can write Your Custom Page Link,Leave Blank For Default']) .'">
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="slug" value="'.@$data['slug'].'" class="form-control">';}

        //Redirect
        //Link
        // $link = isset($data['redirect']) ? $data['redirect'] :'';
        // if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){

        // }else if($link!=''){
        //     $link = WEB_URL.$link;
        // }
        // echo '<div class="form-group">
        //             <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Redirect Link']) .'</label>
        //             <div class="col-sm-10  col-md-9">
        //                 <div class="input-group">
        //                     <input type="text" name="redirect" value="'.$link.'" class="pastLinkHere form-control" placeholder="http://www.google.com">
        //                     <div class="input-group-addon linkList pointer"><i class="glyphicon glyphicon-search"></i></div>
        //                 </div>
        //             </div>
        //       </div>';

        // echo '<div class="form-group">
        //             <label class="col-sm-2 col-md-3   control-label" >'.$_e['Login Required'].'</label>

        //             <div class="col-sm-10 col-md-9">
        //                 <label class="radio-inline">
        //                     <input type="radio" class="loginReq" name="setting_f[loginReq]" value="1">'.$_e['Yes'].'
        //                 </label>
        //                 <label class="radio-inline">
        //                     <input type="radio" class="loginReq" name="setting_f[loginReq]" value="0">'.$_e['No'].'
        //                 </label>
        //             </div>
        //         </div>
        //         <script>
        //         $(document).ready(function(){
        //             $(".loginReq[value=\''.@strtolower($this->functions->setting_fieldsArray($settingInfo,'loginReq')).'\']").attr("checked", true);
        //         });

        //         </script>';

        /*echo '
                <div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label"></label>
                    <div class="col-sm-10  col-md-9 ">
                        <img src="" class="pageIcon kcFinderImage"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 col-md-3   control-label" >'.$_e['Login Required'].'</label>

                    <div class="col-sm-10 col-md-9">
                        <div class="input-group">
                            <input type="text" class="pageIcon form-control" name="setting_f[icon]" value="'.@($this->functions->setting_fieldsArray($settingInfo,'icon')).'">
                            <div class="input-group-addon  pointer"  onclick="'."openKCFinderImageWithImg('pageIcon')".'"><i class="glyphicon glyphicon-picture"></i></div>
                        </div>
                    </div>
                </div>';*/



        //Comment
        // if($this->functions->developer_setting('page_comment')=='1'){
        //     $checked = "";
        //     if(@$data['comment']=='1'){$checked='checked';}
        //     echo '<div class="form-group">
        //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Allow Comment']) .'</label>
        //             <div class="col-sm-10  col-md-9">
        //                 <div class="make-switch" data-off="warning" data-on="success">
        //                     <input type="checkbox" name="comment" value="1" '.$checked.'>
        //                 </div>
        //             </div>
        //       </div>';
        // }else{ echo '<input type="hidden" name="comment" value="0" class="form-control">';}

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


        //Banner
if ($this->functions->developer_setting('pageBanner') == '0') {
    $img = "";
    if (@$data['page_banner'] != '' && $isEdit) {
        $img = $data['page_banner'];
        echo "<input type='hidden' name='oldImg' value='$img' />";

        // get extension
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        echo '<div class="form-group">
                <label class="col-sm-2 col-md-3 control-label">'. _uc($_e['Old Banner Image']) .'</label>
                <div class="col-sm-10 col-md-9">';

        // if mp4 show video, otherwise image
        if ($ext == 'mp4') {
            echo '<video controls style="max-height:250px; max-width:100%;">
                    <source src="../images/'.$img.'" type="video/mp4">
                    Your browser does not support the video tag.
                  </video>';
        } else {
            echo '<img src="../images/'.$img.'" style="max-height:250px; max-width:100%;" >';
        }

        echo '    </div>
              </div>';
    }

    echo '<div class="form-group">
            <label class="col-sm-2 col-md-3 control-label">'. _uc($_e['Page Banner Image']) .'</label>
            <div class="col-sm-10 col-md-9">
                <input type="file" name="page_banner" class="btn-file btn btn-primary">
            </div>
          </div>';
} else {
    echo '<input type="hidden" name="page_banner" value="" class="form-control">';
}

        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div><!-- setting tabs end -->
        </div> <!-- tab-content end -->
    </div> <!-- container end -->
</form>";

        $this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
        $menuC  =   new webMenu();
        $menuC->menuWidgetLinksWOLang();


        $employeePage = $this->functions->developer_setting('employeePage');
        $filesManagerPage = $this->functions->developer_setting('filesManagerPage');
        $testimonialPage = $this->functions->developer_setting('testimonialPage');
        $galleryPage = $this->functions->developer_setting('hasGalleryPage');

        $employeeFormat = '';
        if($employeePage == '1'){
            $employeeFormat = "<tr><td>"._n($_e['use : {{employee}} FOR EMPLOYEE PAGE']) ."</td></tr>";
        }

        $filesManagerFormat = '';
        if($filesManagerPage == '1'){
            $filesManagerFormat = "<tr><td>"._n($_e['use : {{files-Manager}} FOR FILES MANAGER PAGE']) ."</td></tr>";
        }
        $testimonialFormat = '';
        if($testimonialPage == '1'){
            $testimonialFormat = "<tr><td>". _n($_e['use : {{testimonial}} FOR TESTIMONIAL PAGE']) ."</td></tr>";
        }
        $galleryPageFormat = '';
        if($galleryPage == '1'){
            $galleryPageFormat .= "<tr><td>"._n($_e['use : {{albumAll}} FOR ALL ALBUMS']) ."</td></tr>";
            $galleryPageFormat = "<tr><td>"._n($_e['use : {{albumSingle(AlbumName)}} FOR SINGLE ALBUM (Enter your album name inside ())']) ."</td></tr>";
            $galleryPageFormat .= "<tr><td>"._n($_e["use : {{albumPictures(AlbumName)}} FOR ALBUM's ALL IMAGES (Enter your album name inside ())"]) ."</td></tr>";


        }
        $useWidget = '<table class="table table-striped table-hover">
                    <tr><td>'. _n($_e['use : {{contactForm}} FOR CONTACT FORM']) .'</td></tr>
                     '. $employeeFormat .'
                     '. $filesManagerFormat .'
                     '. $testimonialFormat .'
                    '. $galleryPageFormat .'
                     </table>
                     ';

        echo $this->functions->blankModal($_e["Use Widget In Your Page"],"useWidgets",$useWidget,$_e['Close']);

    } //function end


    public function homePageBoxView(){
        global $_e;
        echo '<div class="table-responsive">
                <script>$(document).ready(function(){dTableT()});</script>
                <table class="table table-hover dTableT tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['BOX NAME']) .'</th>
                        <th>'. _u($_e['TITLE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';

        $sql  = "SELECT box,id,heading FROM box WHERE publish='1' ORDER BY id ASC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            @$heading = unserialize($val['heading']);
            if($heading===false){
                $heading = $val['heading'];
            }else{
                @$heading = $heading[$defaultLang];
            }

            echo "<tr>
                    <td>$i</td>";
            echo "  <td>$val[box]</td>";
            echo "  <td>$heading</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-".$this->functions->getLinkFolder()."?page=homePageEdit&pageId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }



    public function homePageBoxEdit(){
        global $_e;
        $token  =   $this->functions->setFormToken('homePageBoxEdit',false);
        $id     =   $_GET['pageId'];
        $sql    =   "SELECT * FROM box where id = '$id' AND publish = '1'";
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount==0){
            echo "Box Page Not Found For Update";
            return false;
        }

        $boxName = $data['box'];
        $sql     =   "SELECT * FROM box_setting where box = '$boxName' ";
        $box_setting  =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount==0){
            echo "Box Setting Not Found";
            return false;
        }


        //No need to remove any thing,, go in developer setting table and set 0
        echo '<form method="post" action="-'.$this->functions->getLinkFolder().'?page=homePage" class="form-horizontal" role="form" enctype="multipart/form-data">
                <input type="hidden" name="editId" value="'.$id.'"/>
                '.$token.
                '<div class="form-horizontal col-sm-12">
                            <h2  class="tab_heading ">'. _uc($_e['Update Detail']) .'</h2>
            ';

        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }
        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

        echo '<div class="panel-group" id="accordion">';

        @$heading = unserialize($data['heading']);
        @$sub_heading   =  unserialize($data['sub_heading']);
        @$short_desc    =  unserialize($data['short_desc']);
        @$linkText      =  unserialize($data['linktext']);

        for ($i = 0; $i < sizeof($lang); $i++) {
            if($i==0){
                $collapseIn = ' in ';
            }else{
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
            echo '              <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['TITLE']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.$heading[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['TITLE']) .'">
                                    </div>
                                </div>';

            //Sub Heading
            if($box_setting['sub_heading']=='1'){
                echo '          <div class="form-group">
                                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Sub Title']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text"  value="'.$sub_heading[$lang[$i]].'" name="sub_heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Sub Title']) .'">
                                    </div>
                               </div>';
            }else{ echo '<input type="hidden" name="sub_heading" value="" class="form-control">';}


            //Short Desc
            if($box_setting['short_desc']=='1'){
                $editor = '';
                if($box_setting['editor']=='1'){
                    $editor = 'ckeditor';
                }

                echo '          <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Description']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <textarea name="short_desc['.$lang[$i].']" class="form-control '.$editor.'" placeholder="'. _uc($_e['Enter Short Description']) .'" maxlength="500">'.$short_desc[$lang[$i]].'</textarea>
                                    </div>
                               </div>';
            }else{ echo '<input type="hidden" name="short_desc" value="" class="form-control">';}

            //linkText
            if($box_setting['linktext']=='1'){
                echo '            <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link Text']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text"  value="'.$linkText[$lang[$i]].'" name="linkText['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Link Text']) .'">
                                    </div>
                               </div>';
            }else{ echo '<input type="hidden" name="linkText" value="" class="form-control">';}






                   //Redirect
if($box_setting['redirect']=='1'){
//Link
// $link = unserialize($data['redirect']);
// if(preg_match('@http://@i',$link[$lang[$i]]) || preg_match('@https://@i',$link[$lang[$i]])){

// }else if($link!=''){
// $link = unserialize($data['redirect']);
// $link = WEB_URL.$link;


// }
//         // echo '<div class="form-group">
        //             <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
        //             <div class="col-sm-10  col-md-9">
        //                 <input type="url" name="redirect" value="'.$link.'" class="form-control" placeholder="http://www.google.com">
        //             </div>
        //        </div>';


$str = @$data['redirect'];
// var_dump($str);
$datas = @unserialize($str);
if ($datas !== false) {
@$link = unserialize($data['redirect']); 

@$link   = $this->functions->addWebUrlInLink($link[$lang[$i]]);
// $link = $link;

} else {
// @$link = unserialize($data['link']);
if(preg_match('@http://@i',$str) || preg_match('@https://@i',$str)){
@$link = $str;

    }else{
@$link = WEB_URL.$str;
}
}



                 echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="input-group">
                            <input type="url" value="'.$link.'" name="redirect['.$lang[$i].']" class="'.$lang[$i].'pastLinkHere form-control" placeholder="'. _uc($_e['Link']) .'">
                        <div class="input-group-addon '.$lang[$i].'linkList '.$lang[$i].'pointer" data-lang="'.$lang[$i].'"><i class="glyphicon glyphicon-search"></i></div>
                        </div>
                    </div>
                </div>';




        }else{ echo '<input type="hidden" name="redirect['.$lang[$i].']" value="" class="form-control">';}






            echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div><!--.Panel end-->
                ';
        }
        echo '</div><!--.accordion end--> <hr><hr>';

 
        //Banner
        if($box_setting['image']=='1'){
            $img = "";
            if($data['image']!=''){
                $img=$data['image'];
                echo "<input type='hidden' name='oldImg' value='$img' />";
                echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Old Image']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <img src="../images/'.$img.'" style="max-height:250px;" >
                    </div>
               </div>';
            }

            echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Image']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="file" name="image" class="btn-file btn btn-primary">
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="image" value="" class="form-control">';}

        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div><!-- form-horizontal end -->
</form>";
$this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
$menuC  =   new webMenu();
$menuC->menuWidgetLinks();
    } //function end


    public function homePageBoxEditSubmit(){
        global $_e;

        if(isset($_POST['heading']) && isset($_POST['submit'])){
            if(!$this->functions->getFormToken('homePageBoxEdit')){return false;}

            $heading        = empty($_POST['heading'])     ? ""    : serialize($_POST['heading']);
            $sub_heading    = empty($_POST['sub_heading']) ? ""    : serialize($_POST['sub_heading']);
            $short_desc     = empty($_POST['short_desc'])  ? ""    : serialize($_POST['short_desc']);
            $linkText       = empty($_POST['linkText'])    ? ""    : serialize($_POST['linkText']);
            // $redirect       = empty($_POST['redirect'])    ? ""    : serialize($_POST['redirect']);

              // $redirect        = empty($_POST['redirect'])   ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['redirect'])); 

  $redirect        = empty($_POST['redirect'])   ? ""    : serialize($_POST['redirect']); 

            $file           = empty($_FILES['image']['name'])? false    : true;

            // $redirect       = str_replace(WEB_URL,'',$redirect);


            $oldImg         = empty($_POST['oldImg'])     ? ""   : $_POST['oldImg'];
            $returnImage    = $oldImg;

            try{
                $this->db->beginTransaction();

                $lastId   =   $_POST['editId'];
                if($file){
                    $this->functions->deleteOldSingleImage($oldImg);
                    $returnImage = $this->functions->uploadSingleImage($_FILES['image'],'box');
                }
                $sql = "UPDATE `box` SET `heading` = ?, `sub_heading` = ?, `short_desc` = ?, `linktext` = ?, `redirect` = ?, `image` = ? WHERE id = ?";

                $array = array($heading, $sub_heading, $short_desc, $linkText, $redirect, $returnImage, $lastId);
                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box Save Successfully'])),'btn-success');
                    $this->functions->setlog(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box'])),$lastId,_js(_uc($_e['Home Page Box Save Successfully'])));
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box Save Failed'])),'btn-danger');
                }

            }catch (Exception $e){
                if($file && $returnImage!==false){
                    $this->functions->deleteOldSingleImage($returnImage);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box Save Failed'])),'btn-danger');
        }
    }
    }


}
?>