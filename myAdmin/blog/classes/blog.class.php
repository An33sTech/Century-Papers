<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class blog extends object_class{
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
        //index page
        $_w['Blog Management'] = '' ;
        //blog.php
        $_w['Manage Blog'] = '' ;
        $_w['Active Blog'] = '' ;
        $_w['Pending Blog'] = '' ;
        $_w['Draft Blog'] = '' ;
        $_w['Add New Blog'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;

        //blogEdit.php

        //This Class
        $_w['SNO'] = '' ;
        $_w['USER'] = '' ;
        $_w['TITLE'] = '' ;
        $_w['BLOG DATE'] = '' ;
        $_w['PUBLISH DATE'] = '' ;
        $_w['UPDATE'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Blog'] = '' ;
        $_w['Blog Save Successfully'] = '' ;
        $_w['Added'] = '' ;
        $_w['Blog Save Failed'] = '' ;
        $_w['Blog Update Successfully'] = '' ;
        $_w['Image File Error'] = '' ;
        $_w['Blog Title'] = '' ;

        $_w['Category'] = '' ;
        $_w['Other'] = '' ;
        $_w['Enter Category Name'] = '' ;
        $_w['Short Description'] = '' ;
        $_w['Enter Short Description'] = '' ;
        $_w['Detail'] = '' ;
        $_w['Enter Full Detail'] = '' ;
        $_w['Allow Comment'] = '' ;
        $_w['Publish'] = '' ;
        $_w['Draft'] = '' ;

        $_w['Leave Blank to publish now'] = '' ;
        $_w['Blog Image'] = '' ;
        $_w['Old Blog Image'] = '' ;
        $_w['SAVE'] = '' ;
        $_w['Slug'] = '' ;
        $_w['SLUG'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Blog');
    }

    public function get_admin_user($user_id)
    {
        if (!$user_id) {
            return false;
        }

        # get admin user

        return $this->dbF->getRow(' SELECT * FROM `accounts` WHERE `acc_id` = ? ', array($user_id) );

    }


    public function blogView(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['USER']) .'</th>
                        <th>'. _u($_e['TITLE']) .'</th>
                        <th>'. _u($_e['SLUG']) .'</th>';
        $blog_date = false;
        if($this->functions->developer_setting('blog_date')=='1'){
                        echo '<th>'. _u($_e['BLOG DATE']) .'</th>';
                        $blog_date = true;
        }
        $publish_date = false;
        if($this->functions->developer_setting('blog_publish_date')=='1'){
                    echo '<th>'. _u($_e['PUBLISH DATE']) .'</th>';
                    $publish_date = true;
        }

        echo  '         <th>'. _u($_e['UPDATE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $today = date('Y-m-d');
        $sql  = "SELECT `id`,`user`,`slug`, `heading`,`date`,`category`,publish_date,dateTime FROM blog WHERE publish = '1' AND publish_date <= '$today' ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        
        $admin_lang = $this->functions->AdminDefaultLanguage();

        foreach($data as $val){
            $i++;
            $id = $val['id'];
            $admin_user = $this->get_admin_user($val['user']);
            $admin_username = $admin_user['acc_name'];

            $heading        = @unserialize($val['heading']);
            $heading        = $heading[$admin_lang];

            $slug           = $this->db->blogPage . $val['slug'];


            echo "<tr>
                    <td>$i</td>
                    <td>{$admin_username}</td>
                    <td>{$heading}</td>
                    <td>{$slug}</td>";

            if($blog_date){
                  echo "<td>$val[date]</td>";
            }
            if($publish_date){
                  echo "<td>$val[publish_date]</td>";
            }

             echo " <td>$val[dateTime]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-blog?page=edit&blogId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteBlog(this);' class='btn'>
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

    public function blogPending(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['USER']) .'</th>
                        <th>'. _u($_e['TITLE']) .'</th>
                        <th>'. _u($_e['SLUG']) .'</th>';
        $blog_date = false;
        if($this->functions->developer_setting('blog_date')=='1'){
            echo '<th>'. _u($_e['BLOG DATE']) .'</th>';
            $blog_date = true;
        }
        $publish_date = false;
        if($this->functions->developer_setting('blog_publish_date')=='1'){
            echo '<th>'. _u($_e['PUBLISH DATE']) .'</th>';
            $publish_date = true;
        }

        echo  '         <th>'. _u($_e['UPDATE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $today = date('Y-m-d');
        $sql  = "SELECT `id`,`user`,`slug`, `heading`,`date`,`category`,publish_date,dateTime FROM blog WHERE publish = '1' AND publish_date > '$today' ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        
        $admin_lang = $this->functions->AdminDefaultLanguage();

        foreach($data as $val){
            $i++;
            $id = $val['id'];
            $admin_user = $this->get_admin_user($val['user']);
            $admin_username = $admin_user['acc_name'];

            $heading        = @unserialize($val['heading']);
            $heading        = $heading[$admin_lang];

            $slug           = $this->db->blogPage . $val['slug'];

            echo "<tr>
                    <td>$i</td>
                    <td>{$admin_username}</td>
                    <td>{$heading}</td>
                    <td>{$slug}</td>";

            if($blog_date){
                echo "<td>$val[date]</td>";
            }
            if($publish_date){
                echo "<td>$val[publish_date]</td>";
            }

            echo " <td>$val[dateTime]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-blog?page=edit&blogId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteBlog(this);' class='btn'>
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


    public function blogDraft(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['USER']) .'</th>
                        <th>'. _u($_e['TITLE']) .'</th>
                        <th>'. _u($_e['SLUG']) .'</th>';
        $blog_date = false;
        if($this->functions->developer_setting('blog_date')=='1'){
            echo '<th>'. _u($_e['BLOG DATE']) .'</th>';
            $blog_date = true;
        }
        $publish_date = false;
        if($this->functions->developer_setting('blog_publish_date')=='1'){
            echo '<th>'. _u($_e['PUBLISH DATE']) .'</th>';
            $publish_date = true;
        }

        echo  '         <th>'. _u($_e['UPDATE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $today = date('Y-m-d');
        $sql  = "SELECT `id`,`user`,`slug`, `heading`,`date`,`category`,publish_date,dateTime FROM blog WHERE publish = '0'";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        
        $admin_lang = $this->functions->AdminDefaultLanguage();

        foreach($data as $val){
            $i++;
            $id = $val['id'];
            $admin_user = $this->get_admin_user($val['user']);
            $admin_username = $admin_user['acc_name'];

            $heading        = @unserialize($val['heading']);
            $heading        = $heading[$admin_lang];

            $slug           = $this->db->blogPage . $val['slug'];

            echo "<tr>
                    <td>$i</td>
                    <td>{$admin_username}</td>
                    <td>{$heading}</td>
                    <td>{$slug}</td>";

            if($blog_date){
                echo "<td>$val[date]</td>";
            }
            if($publish_date){
                echo "<td>$val[publish_date]</td>";
            }

            echo " <td>$val[dateTime]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-blog?page=edit&blogId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteBlog(this);' class='btn'>
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


    public function newBlogAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newBlog')){return false;}

            $heading        = empty($_POST['heading'])     ? ''    : serialize($_POST['heading']);
            $short_desc     = empty($_POST['shortDesc'])   ? ''    : serialize($_POST['shortDesc']);
            $dsc            = empty($_POST['dsc'])         ? ''    : serialize($_POST['dsc']);



            $date           = empty($_POST['date'])   ? ""    : $_POST['date'];
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $publishDate    = empty($_POST['publish_date'])  ? ""    : date('Y-m-d',strtotime($_POST['publish_date']));
            $comment        = empty($_POST['comment'])     ? "0"   : $_POST['comment'];
            $file           = empty($_FILES['image']['name'])? false    : true;
            $category       = empty($_POST['category'])     ? ""   : $_POST['category'];
            if($category=='other'){
                $category  =  empty($_POST['categoryOther'])     ? ""   : $_POST['categoryOther'];
            }

            $user = $_SESSION['_uid'];
            $slug        = empty($_POST['slug'])       ? ""   : $_POST['slug'];

            try{
                $this->db->beginTransaction();

                $sql      =   "INSERT INTO `blog` SET
                                 `date`=?,
                                 `heading`=?,
                                 `user`=?,
                                 `category`=?,
                                 `shortDesc`=?,
                                 `dsc`=?,
                                 `image`=?,
                                 `comment`=?,
                                 `publish`=?,
                                 `publish_date`=?";
                $imgName ="";
                if($file){
                    $imgName =  $this->functions->uploadSingleImage($_FILES['image'],'blog/');
                    if($imgName==false){
                        throw new Exception($imgName);
                    }
                }

                $array   = array($date,$heading,$user,$category,
                                $short_desc,$dsc,$imgName,$comment,$publish,$publishDate);

                $this->dbF->setRow($sql,$array,false);

                $lastId  =   $this->dbF->rowLastId;

                $sql_slug = "SELECT * FROM blog where slug = '$slug' ";
                $data_slug = $this->dbF->getRow($sql_slug);

                if($this->dbF->rowCount!=0){
                    $slug = $slug."-".rand(1, 15);
                }
                
                if($slug == ""){
                    $slug = $this->db->dataPage.$lastId;
                }

                $sql ="UPDATE `blog` SET slug = ? WHERE id = ?";
                $arry = array($slug,$lastId);
                $this->dbF->setRow($sql,$arry,false);

                // $check = $this->functions->check_slug_duplicate($slug);

                // if(!$check){
                //     $slug = $slug.uniqid();
                // }

                $pageLink = '/'.$this->db->blogPage.$slug;

                $ref_id = $this->db->blogPage.$lastId;
                
                $sql1 = "INSERT INTO `seo`
                                ( `pageLink`, `ref_id`, `slug`, `title` ) 
                                VALUES (?,?,?,?)";

                $array1   = array($pageLink,$ref_id,$slug,$heading);

                $this->dbF->setRow($sql1,$array1,false);



                $this->db->commit();

                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Blog']),($_e['Blog Save Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Blog']),$this->dbF->rowLastId,($_e['Blog Save Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Blog']),($_e['Blog Save Failed']),'btn-danger');
                }
            }catch (Exception $e){
                if($imgName!==false && $file){
                    $this->functions->deleteOldSingleImage($imgName);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Blog']),($_e['Blog Save Failed']),'btn-danger');
            }
        } // If end
    }




    public function blogEditSubmit(){
        global $_e;
        if(isset($_POST['submit'])  && isset($_POST['editId'])){
           if(!$this->functions->getFormToken('editBlog')){return false;}

            $heading        = empty($_POST['heading'])      ? ""    : serialize($_POST['heading']);
            $short_desc     = empty($_POST['shortDesc'])    ? ""    : serialize($_POST['shortDesc']);
            $dsc            = empty($_POST['dsc'])          ? ""    : serialize($_POST['dsc']);
            

            $date           = empty($_POST['date'])   ? ""    : $_POST['date'];
            $publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
            $publishDate    = empty($_POST['publish_date'])  ? ""    : date('Y-m-d',strtotime($_POST['publish_date']));
            $comment        = empty($_POST['comment'])     ? "0"   : $_POST['comment'];
            $file           = empty($_FILES['image']['name'])? false    : true;
            $category       = empty($_POST['category'])     ? ""   : $_POST['category'];
            if($category=='other'){
                $category  =  empty($_POST['categoryOther'])     ? ""   : $_POST['categoryOther'];
            }

            $user = $_SESSION['_uid'];
            $slug        = empty($_POST['slug'])       ? ""   : $_POST['slug'];

            $oldImg      = empty($_POST['oldImg'])     ? ""   : $_POST['oldImg'];
            $imgName     = $oldImg;
            try{
                $this->db->beginTransaction();
                $lastId   =   $_POST['editId'];

                if($file){
                    $this->functions->deleteOldSingleImage($oldImg);
                    $imgName =  $this->functions->uploadSingleImage($_FILES['image'],'blog');
                    if($imgName==false){
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                
                $check = $this->functions->check_slug_duplicate($slug);

                if(!$check){
                    $slug = $slug."-".rand(1, 15);
                }

                $sql      =   "UPDATE `blog` SET
                                 `date`=?,
                                 `heading`=?,
                                 `user`=?,
                                 `category`=?,
                                 `shortDesc`=?,
                                 `dsc`=?,
                                 `image`=?,
                                 `comment`=?,
                                 `publish`=?,
                                 `publish_date` = ?,
                                 `slug` = ?

                                WHERE id = ?";

                $array   = array($date,$heading,$user,$category,
                    $short_desc,$dsc,$imgName,$comment,$publish,$publishDate,$slug,$lastId);

                $this->dbF->setRow($sql,$array,false);

                $check = $this->functions->check_slug_duplicate($slug);

                if(!$check){
                    $slug = $slug.uniqid();
                }

                $pageLink = '/'.$this->db->blogPage.$slug;
                $ref_id = $this->db->blogPage.$lastId;

                $sql1 = "UPDATE `seo` SET `pageLink` = ?, `slug` = ?, `title` = ?
                            WHERE ref_id = ?";

                $array1   = array($pageLink,$slug,$heading,$ref_id);

                $this->dbF->setRow($sql1,$array1,false);

                $this->db->commit();

                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Blog']),($_e['Blog Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Blog']),$this->dbF->rowLastId,($_e['Blog Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Blog']),($_e['Blog Save Failed']),'btn-danger');
                }

            }catch (Exception $e){
                if($imgName!==false && $file){
                    $this->functions->deleteOldSingleImage($imgName);
                }
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Blog']),($_e['Blog Update Failed']),'btn-danger');
            }

        }
    }

    public function blogCategoryOption(){
        $sql = "SELECT DISTINCT(category) AS category FROM blog ORDER BY category ASC";
        $data = $this->dbF->getRows($sql);
        $opt = '';
        foreach($data as $val){
            $opt .= "<option value='$val[category]'>$val[category]</option>";
        }
        return $opt;
    }

    public function blogNew(){
        global $_e;
        $token       = $this->functions->setFormToken('newBlog',false);
        //No need to remove any thing,, go in developer setting table and set 0


    echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">'.
           $token.
           '<div class="form-horizontal">

            <div class="panel-group" id="accordion">

            ';


        $lang = $this->functions->IbmsLanguages();
        if ($lang != false) {
            $lang_size = sizeof($lang);
            for ($i = 0; $i < $lang_size; $i++) {
                if ($i == 0) {
                    $collapseIn = ' in ';
                } else {
                    $collapseIn = '';
                }

                echo '<div class="panel panel-default">
                        <div class="panel-heading">
                             <a data-toggle="collapse" data-parent="#accordion" href="#' . $lang[$i] . '">
                                <h4 class="panel-title">
                                    ' . $lang[$i] . '
                                </h4>
                             </a>
                        </div>
                        <div id="' . $lang[$i] . '" class="panel-collapse collapse ' . $collapseIn . '">
                            <div class="panel-body">';


                //Title
                echo '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Blog Title']) .'</label>
                            <div class="col-sm-10  col-md-9">
                                <input type="text" name="heading[' . $lang[$i] .']" class="form-control" placeholder="'. _uc($_e['Blog Title']) .'">
                            </div>
                      </div>';

                //Short Desc
                if($this->functions->developer_setting('blog_shrtDesc')=='1'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Description']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="shortDesc[' . $lang[$i] .']" class="form-control" placeholder="'. _uc($_e['Enter Short Description']) .'"></textarea>
                        </div>
                   </div>';
                }else{ echo '<input type="hidden" name="shortDesc" value="" class="form-control">';}

                //Desc
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Detail']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="dsc[' . $lang[$i] .']" id="dsc_' . $lang[$i] .'" placeholder="'. _uc($_e['Enter Full Detail']) .'"></textarea>
                            <script>
                               $(function() {
                                 CKEDITOR.replace("dsc_' . $lang[$i] .'");
                               });
                            </script>
                        </div>
                   </div>';



                echo '

                </div> <!-- panel-body-->
                        </div> <!-- #$lang[$i] -->

                    </div> <!-- .panel-default -->';


            }

            echo '</div> <!-- .panel-group -->';

        }


        //Date
        if($this->functions->developer_setting('blog_date')=='1'){
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['BLOG DATE']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="date" class="date form-control" placeholder="'. _uc($_e['BLOG DATE']) .'">
                    </div>
                </div>';
        }else{ echo '<input type="hidden" name="date" value="" class="form-control">';}

        //Category
        $optionCategory = $this->blogCategoryOption();
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Category']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <select name="category" id="category" class="form-control" required>
                            '.$optionCategory.'
                            <option value="other">'. _uc($_e['Other']) .'</option>
                        </select>
                        <br>
                        <input type="text" name="categoryOther" id="categoryOther" class="form-control" style="display:none" placeholder="'. _uc($_e['Enter Category Name']) .'"/>

                    </div>
               </div>';





        //Comment
        if($this->functions->developer_setting('blog_comment')=='1'){
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Allow Comment']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="warning" data-on="success">
                            <input type="checkbox" name="comment" value="1">
                        </div>
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="comment" value="0" class="form-control">';}


        //Publish
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Publish']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                            <input type="checkbox" name="publish" value="1">
                        </div>
                    </div>
               </div>';


        //Publish Date
        if($this->functions->developer_setting('blog_publish_date')=='1'){
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['PUBLISH DATE']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="publish_date" class="date form-control" placeholder="'. _uc($_e['Leave Blank to publish now']) .'">
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="publish_date" value="" class="form-control">';}


        // Slug
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Slug']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="" name="slug" class="form-control" placeholder="'. _uc($_e['Slug']) .'">
                    </div>
                </div>';


        //Banner
        if($this->functions->developer_setting('blog_image')=='1'){
            echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Blog Image']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="file" name="image" class="btn-file btn btn-primary">
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="news_image" value="" class="form-control">';}


        //echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div>
             </form>";
    }

    public function blogEdit(){
        global $_e;
        $token       = $this->functions->setFormToken('editBlog',false);
        $id     =   $_GET['blogId'];
        $sql    =   "SELECT * FROM blog where id = '$id' ";
        $data   =   $this->dbF->getRow($sql);
        //No need to remove any thing,, go in developer setting table and set 0
        echo '<form method="post" action="-blog?page=blog" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '<input type="hidden" name="editId" value="'.$id.'"/>
            <div class="form-horizontal">

            <div class="panel-group" id="accordion">

            ';



        $lang = $this->functions->IbmsLanguages();
        if ($lang != false) {
            $lang_size = sizeof($lang);
            for ($i = 0; $i < $lang_size; $i++) {
                if ($i == 0) {
                    $collapseIn = ' in ';
                } else {
                    $collapseIn = '';
                }

                $heading   = unserialize($data['heading']);
                $short_dsc = unserialize($data['shortDesc']);
                $dsc       = unserialize($data['dsc']);


                echo '<div class="panel panel-default">
                        <div class="panel-heading">
                             <a data-toggle="collapse" data-parent="#accordion" href="#' . $lang[$i] . '">
                                <h4 class="panel-title">
                                    ' . $lang[$i] . '
                                </h4>
                             </a>
                        </div>
                        <div id="' . $lang[$i] . '" class="panel-collapse collapse ' . $collapseIn . '">
                            <div class="panel-body">';


                //Title
                echo '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Blog Title']) .'</label>
                            <div class="col-sm-10  col-md-9">
                                <input type="text" value="'.$heading[$lang[$i]].'" name="heading[' . $lang[$i] .']" class="form-control" placeholder="'. _uc($_e['Blog Title']) .'">
                            </div>
                        </div>';

                //Short Desc
                if($this->functions->developer_setting('blog_shrtDesc')=='1'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Description']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="shortDesc[' . $lang[$i] .']" class="form-control" placeholder="'. _uc($_e['Enter Short Description']) .'">'.$short_dsc[$lang[$i]].'</textarea>
                        </div>
                   </div>';
                }else{ echo '<input type="hidden" name="shortDesc[' . $lang[$i] .']" value="" class="form-control">';}

                //Desc
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Detail']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="dsc[' . $lang[$i] .']" id="dsc_' . $lang[$i] . '" placeholder="'. _uc($_e['Enter Full Detail']) .'">'.($dsc[$lang[$i]]).'</textarea>
                            <script>
                               $(function() {
                                 CKEDITOR.replace("dsc_' . $lang[$i] . '");
                               });
                            </script>
                        </div>
                   </div>';


                echo '

                </div> <!-- panel-body-->
                        </div> <!-- #$lang[$i] -->

                    </div> <!-- .panel-default -->';


            } // for loop end

            echo '</div> <!-- .panel-group -->';

        }



        //Date
        if($this->functions->developer_setting('blog_date')=='1'){
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['BLOG DATE']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="date" value="'.$data['date'].'"  class="date form-control" placeholder="'. _uc($_e['BLOG DATE']) .'">
                    </div>
                </div>';
        }else{ echo '<input type="hidden" name="news_date" value="" class="form-control">';}


        //Category
        $optionCategory = $this->blogCategoryOption();
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Category']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <select name="category" id="category" class="form-control" required>
                            '.$optionCategory.'
                            <option value="other">'. _uc($_e['Other']) .'</option>
                        </select>
                        <br>
                        <input type="text" name="categoryOther" id="categoryOther" class="form-control" style="display:none" placeholder="'. _uc($_e['Enter Category Name']) .'"/>

                    </div>
               </div>';



        //Comment
        if($this->functions->developer_setting('blog_comment')=='1'){
            $checked = "";
            if($data['comment']=='1'){$checked='checked';}
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Allow Comment']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="warning" data-on="success">
                            <input type="checkbox" name="comment" value="1" '.$checked.'>
                        </div>
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="comment" value="0" class="form-control">';}

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


        //Publish Date
        $publish_date = empty($data['publish_date'])?"":date('m/d/Y',strtotime($data['publish_date']));
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['PUBLISH DATE']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="'.$publish_date.'" name="publish_date" class="date form-control" placeholder="'. _uc($_e['Leave Blank to publish now']) .'">
                    </div>
               </div>';


        // Slug
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Slug']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="'.$data['slug'].'" name="slug" class="form-control" placeholder="'. _uc($_e['Slug']) .'">
                    </div>
                </div>';


        //Banner
        if($this->functions->developer_setting('blog_image')=='1'){
            $img = "";
            if($data['image']!=''){
                $img=$data['image'];
                echo "<input type='hidden' name='oldImg' value='$img' />";
                echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Old Blog Image']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <img src="../images/'.$img.'" style="max-height:250px;" >
                    </div>
               </div>';


            }

            echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Blog Image']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="file" name="image" class="btn-file btn btn-primary">
                    </div>
               </div>';
        }else{ echo '<input type="hidden" name="image" value="" class="form-control">';}

        //echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div>
             </form>";


    }
}
?>