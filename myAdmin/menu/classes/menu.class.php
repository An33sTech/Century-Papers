    <?php
require_once (__DIR__."/../../global.php"); //connection setting db
class WebMenu extends object_class{
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
        //FooterMenu.php
        $_w['Manage WebSite Footer Menu'] = '' ;
        $_w['Footer Menu'] = '' ;
        $_w['Add New Footer Menu'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;

        //footerMenuEdit.php
        $_w['Update Footer Menu'] = '' ;
        //Index Page
        $_w['Manage Website Menu'] = '' ;

        //menu.php
        $_w['Add New Menu'] = '' ;
        $_w['Website Menu'] = '' ;
        //menuEdit.php
        $_w['Update Menu'] = '' ;

        //This Class
        $_w['Menu Update Fail'] = '' ;
        $_w['Multi Language SEO Links'] = '' ;
        $_w['Menu'] = '' ;
        $_w['Menu Update Successfully'] = '' ;

        $_w['Added'] = '' ;
        $_w['Menu Add Successfully'] = '' ;
        $_w['New Menu Add Fail'] = '' ;
        $_w['SAVE'] = '' ;
        $_w['Menu Name'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Menu Under'] = '' ;
        $_w['Icon Image Link'] = '' ;
        $_w['Icon'] = '' ;
        $_w['Old Icon'] = '' ;
        $_w['Root Menu'] = '' ;
        $_w['Link'] = '' ;

        $_w['Page Links'] = '' ;
        $_w['USE'] = '' ;
        $_w['Home'] = '' ;
        $_w['All Products'] = '' ;
        $_w['All Deals Products'] = '' ;
        $_w['Product Category Links'] = '' ;
        $_w['Product Deals Category Links'] = '' ;
        $_w['Product Links'] = '' ;
        $_w['Deal Product Links'] = '' ;
        $_w['Blog Links'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin WebSite Menu');

    }



    public function newMenuAdd(){
        global $_e;

        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newMenu')){return false;}

            $heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
$short_desc     = empty($_POST['short_desc'])   ? "" : serialize($_POST['short_desc']);
            $short_desc     = data_compress($short_desc);

            // $link           = $this->functions->removeWebUrlFromLink($_POST['link']);
            // $icon           = $this->functions->removeWebUrlFromLink($_POST['icon']);

            $link        = empty($_POST['link'])   ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['link']));    

            $icon        = empty($_POST['icon'])   ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['icon']));


            // $link           = empty($_POST['link'])      ? ""    : serialize($link);
            // $icon           = empty($_POST['icon'])      ? ""    : serialize($icon);
            $underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
            $menuType       = empty($_POST['menuType'])  ? "main"    : $_POST['menuType'];
            // $link           = $this->functions->removeWebUrlFromLink($link);
            // $icon           = $this->functions->removeWebUrlFromLink($icon);
            try{
                $this->db->beginTransaction();

                $sql      =   "INSERT INTO `webmenu`(`name`,`short_desc`, `link`,`icon`, `under`,`type`) VALUES (?,?,?,?,?,?)";
                $array    =     array($heading,$short_desc,$link,$icon,$underMenu,$menuType);
                $this->dbF->setRow($sql,$array,false);
                $lastId = $this->dbF->rowLastId;

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),($_e['Menu']),$lastId,($_e['Menu Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-danger');
            }
        } // If end
    }

    public function menuEditSubmit(){
        global $_e;
        if(isset($_POST['submit']) && isset($_POST['editId'])){
            if(!$this->functions->getFormToken('editMenu')){return false;}

            $heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
            $short_desc     = empty($_POST['short_desc'])   ? "" : serialize($_POST['short_desc']);
            $short_desc     = data_compress($short_desc);

            $link        = empty($_POST['link'])   ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['link']));   
            // $link           = empty($_POST['link'])      ? ""    : serialize($_POST['link']);
            $icon           = empty($_POST['icon'])      ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['icon']));   


                // $link           = $this->functions->removeWebUrlFromLink($_POST['link']);
            // $icon           = $this->function_exists(function_name)->removeWebUrlFromLink($_POST['icon']);


            // $link           = empty($_POST['link'])      ? ""    : serialize($link);
            // $icon           = empty($_POST['icon'])      ? ""    : serialize($icon);



            $underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
            $menuType       = empty($_POST['menuType'])  ? ""    : $_POST['menuType'];
            $lastId         = $_POST['editId'];
            // $link   = $this->functions->removeWebUrlFromLink($link);
            // $icon   = $this->functions->removeWebUrlFromLink($icon);

            try{
                $this->db->beginTransaction();
                if($underMenu==$lastId){
                    throw new Exception('Fail');
                }
                $sql      =   "UPDATE `webmenu` SET
                               `name` = ?,
                               `short_desc`  = ?,
                               `link` = ?,
                               `icon` = ?,
                                `under` = ?,
                                `type` =?
                              WHERE id = ?";
                $array    =     array($heading,$short_desc,$link,$icon,$underMenu,$menuType,$lastId);
                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Update Menu']),_uc($_e['Menu']),$lastId,($_e['Menu Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $msg = $e->getMessage();
                $this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-danger');
            }
        } // If end
    }


    public function menuNew(){
        $this->menuEdit(true);
        return false;
    }



    public function menuEdit($new=false){
        global $_e;
        $isEdit = false;
        if($new ===true){
            $token      =   $this->functions->setFormToken('newMenu',false);
        }else {
            $id         =   $_GET['menuId'];
            $token      =   $this->functions->setFormToken('editMenu',false);
            $token      = '<input type="hidden" name="editId" value="'.$id.'"/> '.$token;

            $sql        =   "SELECT * FROM webmenu where id = '$id' ";
            $data       =   $this->dbF->getRow($sql);
            if($this->dbF->rowCount==0){
                echo "Menu Not Found For Update";
                return false;
            }
            $isEdit = true;
        }

        $action = "";
        if(isset($_GET['type'])){
            $action = "-".$this->functions->getLinkFolder(false);
        }else if(isset($data['type'])){
            $action = "-menu?page=menu&type=$data[type]";
        }

        echo '<form method="post" action="'.$action.'" class="form-horizontal" role="form" enctype="multipart/form-data">'. $token.
                '<div class="form-horizontal">';

        $lang   =   $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }
        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

     
        //Under Menu
        $option = $this->underMenuOption();
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <select name="underMenu" id="underMenu" class="underMenu form-control">
                        <option value="0">'. _uc($_e['Root Menu']) .'</option>
                           '.$option.'
                        </select>
                    </div>
                </div>';

        if($isEdit) {
            @$oldunder = $data['under'];
            echo '<script>
            $(document).ready(function(){
                $("#underMenu").val("' . $oldunder . '").change();
            });
            </script>';
        }





        //Menu Type

            $option = $this->menuTypesOption();
            echo '<div class="form-group displaynone">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <select name="" class="menuType form-control">
                               '.$option.'
                            </select>
                        </div>
                    </div>';
            if($isEdit){
                @$oldunder = $data['type'];
                echo '<script>
                $(document).ready(function(){
                    $(".menuType").val("' . $oldunder . '").change();
                });
                </script>';
            }else if(isset($_GET['type'])){
                @$oldunder = $_GET['type'];
                echo '<script>
                        $(document).ready(function(){
                            $(".menuType").val("' . $oldunder . '").change();
                        });
                </script>';
            }

        if(isset($_GET['type'])) {
            echo '<input type="hidden" name="menuType" value="' . $_GET['type'] . '" />';
        }elseif(isset($data['type'])) {
            echo '<input type="hidden" name="menuType" value="' . $data['type'] . '" />';
        }

        echo '<div class="panel-group" id="accordion">';
        for ($i = 0; $i < sizeof($lang); $i++) {
            if($i==0){
                $collapseIn = ' in ';
            }else{
                $collapseIn = '';
            }
            @$heading = unserialize($data['name']);
            @$shortDesc = unserialize(data_unCompress($data['short_desc']));
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
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Name']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="'.@htmlspecialchars(@$heading[$lang[$i]]).'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Menu Name']) .'">
                    </div>
                </div>';


            //Short Desc
            echo '<div class="form-group displaynone">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Desc']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="'.@htmlspecialchars(@$shortDesc[$lang[$i]]).'" name="short_desc['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Short Desc']) .'">
                    </div>
                </div>';





   //Link
        // @$link = unserialize($data['link']);

$str = @$data['link'];
$datas = @unserialize($str);
if ($datas !== false) {
  @$link = unserialize($data['link']); 

    @$link   = $this->functions->addWebUrlInLink($link[$lang[$i]]);

} else {
  @$link = ($data['link']);
    @$link   = $this->functions->addWebUrlInLink($link);

}

     
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="input-group">
                            <input type="url" value="'.$link.'" name="link['.$lang[$i].']" class="'.$lang[$i].'pastLinkHere form-control" placeholder="'. _uc($_e['Link']) .'">
                        <div class="input-group-addon '.$lang[$i].'linkList '.$lang[$i].'pointer" data-lang="'.$lang[$i].'"><i class="glyphicon glyphicon-search"></i></div>
                        </div>
                    </div>
                </div>';

        //icon Link
        // @$icon = unserialize($data['icon']);
        // $icon   = $this->functions->addWebUrlInLink($icon[$lang[$i]]);




$str1 = @$data['icon'];
$datas1 = @unserialize($str1);
if ($datas1 !== false) {
  @$icon = unserialize($data['icon']); 

    @$icon   = $this->functions->addWebUrlInLink($icon[$lang[$i]]);

} else {
  @$icon = ($data['icon']);
    @$icon   = $this->functions->addWebUrlInLink($icon);

}



        /*if($this->functions->developer_setting('main_menu_icon')=="1") { */
        if("1"=="1") {
            echo "<div class='icon-div'>";
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label"></label>
                    <div class="col-sm-10  col-md-9 ">
                        <img src="'.$icon.'" class="'.$lang[$i].'menuIcon '.$lang[$i].'kcFinderImage"/>
                    </div>
                </div>';

            $iconText = $this->functions->developer_setting('main_menu_icon_size');
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Icon']) ." ".$iconText. '</label>
                    <div class="col-sm-10  col-md-9 ">
                        <div class="input-group">
                            <input type="url" name="icon['.$lang[$i].']" value="'.$icon.'"  class="'.$lang[$i].'menuIcon form-control" placeholder="' . _uc($_e['Icon Image Link']) . '">
                            <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('".$lang[$i]."menuIcon')".'"><i class="glyphicon glyphicon-picture"></i></div>
                        </div>
                    </div>
                </div>';
            echo "</div>";
        }






            echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>
                ';
        }
        echo '</div> <!-- .accordian end -->';


        //echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div>
             </form>";
    }

    public function menuTypes()
    {
        $types  = $this->functions->developer_setting('main_menu_type');
        $types   = explode("/",$types);
        echo '<div class="panel-group accordion">';
        foreach ($types as $val) {
            $val = explode(",",$val);
            $heading = $val[0];
            $icon = $val[1];
            $headingUC = _uc($heading);
            echo '<div class="panel panel-default relative" ">
                              <div class="panel-heading">
                                   <a href="-menu?page=menu&type='.$heading.'">
                                        <h4 class="panel-title">
                                            ' . $headingUC . '</h4>
                                   </a>';
            echo "               ";
            echo '
                              </div>
                           </div> <!-- collapse end-->';
        }
        echo ' </div>';
    }

    public function menuView($type='main'){
        $sql = "SELECT * FROM webmenu WHERE  under = '0' AND type='$type' ORDER BY sort";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        echo '<div class="panel-group accordion">';
        foreach($data as $val){
            $heading = unserialize($val['name']);
            $heading = $heading[$defaultLang];
            $heading = htmlspecialchars($heading);
            $id =$val['id'];
                echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
                              <div class="panel-heading">
                                 <div  class="menuSortDiv">  :: </div>
                                   <a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
                                        <h4 class="panel-title">
                                            '.$heading.'</h4>
                                   </a>';
               echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
                                                <a onclick='addNewMenu($id);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-plus'></i>
                                               </a>
                                               <a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-edit'></i>
                                                </a>
                                                <a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-trash trash'></i>
                                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                                </a>
                                            </div> ";
               echo '
                              </div>
                              <div id="'.$id.'" class="panel-collapse collapse">
                                 <div class="panel-body">';
                    echo $this->menuViewUnder2($id,$defaultLang,$type);

                echo '           </div> <!-- panel-body end -->
                              </div> <!-- collapse end-->
                         </div>
                    ';
        }
        echo '</div> <!-- .accordian end -->';
    }


    public function menuViewUnder2($id,$defaultLang,$type='main'){
        $sql = "SELECT * FROM webmenu WHERE  under = '$id' AND type='$type' ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){return false;}

        echo '<div class="panel-group accordion2">';
        foreach($data as $val){
            $heading = unserialize($val['name']);
            $heading = $heading[$defaultLang];
            $id =$val['id'];
            echo '<div class="panel panel-default relative" id="menu_'.$id.'">
                              <div class="panel-heading" >
                                    <div  class="menuSortDiv">  :: </div>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
                                        <h4 class="panel-title">
                                            '.$heading.'</h4>
                                    </a>';
            echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
                                                <a onclick='addNewMenu($id);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-plus'></i>
                                               </a>
                                               <a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-edit'></i>
                                                </a>
                                                <a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-trash trash'></i>
                                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                                </a>
                                            </div> ";
            echo '
                              </div>
                              <div id="'.$id.'" class="panel-collapse collapse">
                                 <div class="panel-body">';
                echo $this->menuViewUnder3($id,$defaultLang,$type);

            echo '           </div> <!-- panel-body end -->
                              </div> <!-- collapse end-->
                         </div>
                    ';
        }
        echo '</div> <!-- .accordian end -->';
    }

    public function menuViewUnder3($id,$defaultLang,$type='main'){
        $sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){return false;}

        echo '<div class="panel-group accordion3">';
        foreach($data as $val){
            $heading = unserialize($val['name']);
            $heading = $heading[$defaultLang];
            $id =$val['id'];
            echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
                              <div class="panel-heading">
                                    <div  class="menuSortDiv">  :: </div>
                                     <a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
                                        <h4 class="panel-title">
                                            '.$heading.'</h4>
                                     </a>';
            echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
                                                <a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-edit'></i>
                                                </a>
                                                <a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-trash trash'></i>
                                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                                </a>
                                            </div> ";
            echo '
                              </div>
                         </div>
                    ';
        }
        echo '</div> <!-- .accordian end -->';
    }


    public function underMenuOption(){
        $type = '';
        if(isset($_GET['type'])) {
            $type = "AND type = '$_GET[type]' ";
        }

        $sql  = "SELECT * FROM webmenu WHERE  under = '0' $type ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        $opt  = '';
        $defaultLang = $this->functions->AdminDefaultLanguage();

        foreach ($data as $val){
           $menu2       = $this->underMenu2Option($val['id'],$defaultLang);
            $heading    = unserialize($val['name']);
            @$heading    = $heading[$defaultLang];
            $opt        .= '<option value="'.$val['id'].'">'.htmlentities($heading).'</option>';
            if($menu2   !=  false){
                $opt    .= $menu2;
            } else{
                continue;
            }
        }
        return $opt;
    }

    public function menuTypesOption(){
        $types  = $this->functions->developer_setting('main_menu_type');
        $types   = explode("/",$types);
        $opt = '';

        foreach($types as $val){
            $val = explode(",",$val);
            $value = $val[0];
            $icon = $val[1];
            $type = _uc($value);

            $opt .='<option value="'.$value.'" data-icon="'.$icon.'">'.htmlentities($type).'</option>';
        }

        return $opt;


    }

    public function underMenu2Option($id,$defaultLang){
        $sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        $temp = '';
        if($this->dbF->rowCount){
            foreach ($data as $val){
                @$heading = unserialize($val['name']);
                @$heading = $heading[$defaultLang];
                $temp .='<option value="'.$val['id'].'"> -- '.$heading.'</option>';
                $menu3 = $this->underMenu3Option($val['id'],$defaultLang);
                if($menu3!=false){
                    $temp .= $menu3;
                }
                else{
                    continue;
                }
            }
            return $temp;
        }else{
            return false;
        }
    }


    public function underMenu3Option($id,$defaultLang){
        $sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        $temp = '';
        if($this->dbF->rowCount){
            foreach ($data as $val){
                $heading = unserialize($val['name']);
                $heading = $heading[$defaultLang];
                $temp .='<option value="'.$val['id'].'" disabled> -- -- '.$heading.'</option>';
            }
            return $temp;
        }else{
            return false;
        }
    }



/*
 *
 *
 * Footer Menu
 *
 *
 */

    public function footerNewMenuAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('footerNewMenu')){return false;}

            $heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
               $link        = empty($_POST['link'])   ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['link'])); 
            $underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
            $link           =   str_replace(WEB_URL,'',$link);
            try{
                $this->db->beginTransaction();

                $sql      =   "INSERT INTO `webfootermenu`(`name`, `link`, `under`) VALUES (?,?,?)";
                $array    =     array($heading,$link,$underMenu);
                $this->dbF->setRow($sql,$array,false);
                $lastId = $this->dbF->rowLastId;

                $this->db->commit();
                if($this->dbF->rowCount>0) {
                    $this->functions->notificationError(_uc($_e['Menu']), ($_e['Menu Add Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Added']), _uc($_e['Footer Menu']), $lastId, ($_e['Menu Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-success');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-success');
            }
        } // If end
    }

    public function footerMenuEditSubmit(){
        global $_e;
        if(isset($_POST['submit']) && isset($_POST['editId'])){
            if(!$this->functions->getFormToken('footerEditMenu')){return false;}

            $heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);



    $link        = empty($_POST['link'])   ? ""    : serialize($this->functions->removeWebUrlFromLink($_POST['link'])); 




            $underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
            $lastId         = $_POST['editId'];
            // $link   = str_replace(WEB_URL,'',$link);
            try{
                $this->db->beginTransaction();
                if($underMenu==$lastId){
                    throw new Exception('Fail');
                }
                $sql      =   "UPDATE `webfootermenu` SET
                               `name` = ?,
                               `link` = ?,
                                `under` = ?
                              WHERE id = ?";
                $array    =     array($heading,$link,$underMenu,$lastId);
                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();
                if($this->dbF->rowCount>0) {
                    $this->functions->notificationError(_uc($_e['Menu']), ($_e['Menu Update Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Update Menu']), _uc($_e['Footer Menu']), $lastId, ($_e['Menu Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-success');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-success');
            }
        } // If end
    }


    public function footerMenuView(){
        $sql = "SELECT * FROM webfootermenu WHERE  under = '0' ORDER BY sort";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        echo '<div class="panel-group accordion">';
        foreach($data as $val){
            $heading = unserialize($val['name']);
            $heading = $heading[$defaultLang];
            $id =$val['id'];
            echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
                              <div class="panel-heading">
                                 <div  class="menuSortDiv">  :: </div>
                                   <a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
                                        <h4 class="panel-title">
                                            '.$heading.'</h4>
                                   </a>';
            echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
                                               <a onclick='addNewMenu($id);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-plus'></i>
                                               </a>

                                                <a data-id='$id' href='-menu?page=footerMenuEdit&menuId=$id' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-edit'></i>
                                                </a>
                                                <a data-id='$id' onclick='deleteFooterMenu(this);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-trash trash'></i>
                                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                                </a>
                                            </div> ";
            echo '
                              </div>
                              <div id="'.$id.'" class="panel-collapse collapse">
                                 <div class="panel-body">';
            echo $this->footerMenuViewUnder2($id,$defaultLang);

            echo '           </div> <!-- panel-body end -->
                              </div> <!-- collapse end-->
                         </div>
                    ';
        }
        echo '</div> <!-- .accordian end -->';
    }

    public function footerMenuViewUnder2($id,$defaultLang){
        $sql = "SELECT * FROM webfootermenu WHERE  under = '$id' ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){return false;}

        echo '<div class="panel-group accordion2">';
        foreach($data as $val){
            $heading = unserialize($val['name']);
            $heading = $heading[$defaultLang];
            $id =$val['id'];
            echo '<div class="panel panel-default relative" id="menu_'.$id.'">
                              <div class="panel-heading" >
                                    <div  class="menuSortDiv">  :: </div>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
                                        <h4 class="panel-title">
                                            '.$heading.'</h4>
                                    </a>';
            echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
                                                <a data-id='$id' href='-menu?page=footerMenuEdit&menuId=$id' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-edit'></i>
                                                </a>
                                                <a data-id='$id' onclick='deleteFooterMenu(this);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-trash trash'></i>
                                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                                </a>
                                            </div> ";
            echo '
                              </div>
                              <div id="'.$id.'" class="panel-collapse collapse">
                              ';

            echo '
                              </div> <!-- collapse end-->
                         </div>
                    ';
        }
        echo '</div> <!-- .accordian end -->';
    }



    public function footerMenuNew(){
        global $_e;
        $token       = $this->functions->setFormToken('footerNewMenu',false);
        //No need to remove any thing,, go in developer setting table and set 0
        echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '<div class="form-horizontal">';


        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }
        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';



        //Under Menu
        $option = $this->underFooterMenuOption();
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <select name="underMenu" class="underMenu form-control">
                        <option value="0">'. _uc($_e['Root Menu']) .'</option>
                           '.$option.'
                        </select>
                    </div>
                </div>';

        echo '<div class="panel-group" id="accordion">';
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
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Name']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Menu Name']) .'">
                    </div>
                </div>';



        //Link
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="input-group">
                            <input type="url" name="link['.$lang[$i].']" class="form-control '.$lang[$i].'pastLinkHere" placeholder="'. _uc($_e['Link']) .'">
                            <div class="input-group-addon '.$lang[$i].'linkList '.$lang[$i].'pointer" data-lang="'.$lang[$i].'"><i class="glyphicon glyphicon-search"></i></div>
                        </div>
                    </div>
                </div>';






            echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>
                ';
        }
        echo '</div> <!-- .accordian end -->';

        //echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div>
             </form>";
    }

    public function underFooterMenuOption(){
        global $_e;
        $sql = "SELECT * FROM webfootermenu WHERE  under = '0' ORDER BY sort";
        $data = $this->dbF->getRows($sql);
        $opt = '';
        $defaultLang = $this->functions->AdminDefaultLanguage();

        foreach ($data as $val){
            $heading = unserialize($val['name']);
            $heading = $heading[$defaultLang];
            $opt .='<option value="'.$val['id'].'">'.$heading.'</option>';
        }
        return $opt;
    }

    public function FooterMenuEdit(){
        global $_e;
        $token       = $this->functions->setFormToken('footerEditMenu',false);
        $id     =   $_GET['menuId'];
        $sql    =   "SELECT * FROM webfootermenu where id = '$id' ";
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount==0){
            echo "Footer Menu Not Found For Update";
            return false;
        }
        echo '<form method="post" action="-menu?page=footerMenu" class="form-horizontal" role="form" enctype="multipart/form-data">
                <input type="hidden" name="editId" value="'.$id.'"/>'.
            $token.
            '<div class="form-horizontal">';


        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }
        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';


  
        //Under Menu
        $option = $this->underFooterMenuOption();
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <select name="underMenu" id="underMenu" class="form-control">
                        <option value="0">'. _uc($_e['Root Menu']) .'</option>
                           '.$option.'
                        </select>
                    </div>
                </div>';
        $oldunder = $data['under'];
        echo '<script>
        $(document).ready(function(){
            $("#underMenu").val("'.$oldunder.'").change();
        });
        </script>';

        echo '<div class="panel-group" id="accordion">';
        for ($i = 0; $i < sizeof($lang); $i++) {
            if($i==0){
                $collapseIn = ' in ';
            }else{
                $collapseIn = '';
            }
            $heading = unserialize($data['name']);


                    //Link
// $link = unserialize($data['link']);
// if(preg_match('@http://@i',$link[$lang[$i]]) || preg_match('@https://@i',$link[$lang[$i]])){
// }else{
// $link = unserialize($data['link']);

// // $link = WEB_URL.$link;
// }
   

$str = @$data['link'];
// var_dump($str);
$datas = @unserialize($str);
if ($datas !== false) {
@$link = unserialize($data['link']); 

@$link   = $this->functions->addWebUrlInLink($link[$lang[$i]]);
// $link = $link;

} else {
// @$link = unserialize($data['link']);
@$link = WEB_URL.$str;

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
            echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Name']) .'</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="'.$heading[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Menu Name']) .'">
                    </div>
                </div>';




echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url" value="'.$link.'" name="link['.$lang[$i].']" class="'.$lang[$i].'pastLinkHere form-control" placeholder="'. _uc($_e['Link']) .'">
<div class="input-group-addon '.$lang[$i].'linkList pointer" data-lang="'.$lang[$i].'"><i class="glyphicon glyphicon-search"></i></div>
</div>
</div>
</div>';


            echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>
                ';
        }
        echo '</div> <!-- .accordian end -->';

        //echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo "</div>
             </form>";
    }






    public function pageLinksWOLang($copyDiv=true){
        global $_e;

        // $sql = "SELECT * FROM `seo`";
        // $data   =   $this->dbF->getRows($sql);

        $sql    =   "SELECT page_pk,slug,heading FROM `pages` WHERE publish = '1'";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Page Links']) ."</h3>";

        $link   =   WEB_URL."/";
        $slug = _uc($_e['Home']);
        $temp2 = "";
        if($copyDiv){




            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>";




        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        // $link='';
        // $slug='';
        foreach($data as $val){

            $pglnk = "/".$this->db->dataPage."$val[slug]";

            $sql1 = "SELECT * FROM `seo` WHERE `pageLink` = ?";
            $data1   =   $this->dbF->getRow($sql1,array($pglnk));

            if($this->dbF->rowCount > 0 && !empty($data1['slug'])){
                $link = WEB_URL."/".$data1['slug'];
                $slug = $data1['slug'];
            }
            else{
                $link = WEB_URL."/".$this->db->dataPage.$val['slug'];
                $slug = $val['slug'];
            }

            $temp2 = "";
           if($copyDiv){
  


            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>";


   



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control qwer'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }


public function seo_slug_multi($copyDiv=true){
global $_e;

 $sql = "SELECT * FROM `seo_slug` where slug != ''";

$data   =   $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){
return false;
}
$temp   =   "<div class='col-sm-12 form-horizontal'>
<h3>". _uc($_e['Multi Language SEO Links']) ."</h3>";


foreach($data as $val){

$link = WEB_URL."/".$val['slug'];

$temp2 = "";

$temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$val['lang']."copyLink(this);'>".$val['lang']."</div>";


$sql1 = "SELECT * FROM `seo` WHERE `id` = ?";
$data1   =   $this->dbF->getRow($sql1,array($val['seo_id']));

$slug = unserialize($data1['title']);

   $heading = $slug[$val['lang']];

$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$heading
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control new seo table'>
$temp2
</div>
</div>
";
}
$temp .= "</div>";
return $temp;
}



    public function pageLinks($copyDiv=true){
        global $_e;

        // $sql = "SELECT * FROM `seo`";
        // $data   =   $this->dbF->getRows($sql);

        $sql    =   "SELECT page_pk,slug,heading FROM `pages` WHERE publish = '1'";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Page Links']) ."</h3>

<span class='allLang'>
</span>

                        ";

        $link   =   WEB_URL."/";
        $slug = _uc($_e['Home']);
        $temp2 = "";
        if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        // $link='';
        // $slug='';
        foreach($data as $val){

            $pglnk = "/".$this->db->dataPage."$val[slug]";

            $sql1 = "SELECT * FROM `seo` WHERE `pageLink` = ?";
            $data1   =   $this->dbF->getRow($sql1,array($pglnk));

            if($this->dbF->rowCount > 0 && !empty($data1['slug'])){
                $link = WEB_URL."/".$data1['slug'];
                $slug = $data1['slug'];
            }
            else{
                $link = WEB_URL."/".$this->db->dataPage.$val['slug'];
                $slug = $val['slug'];
            }

            $temp2 = "";
           if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

$temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control fhgf 6'>
                                  <div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>
                                </div>
                            </div>
                          ";






$sql1 = "SELECT * FROM `seo_slug` WHERE `seo_id` = ? and `slug` !=''";
@$val   =   $this->dbF->getRows($sql1,array($data1['id']));

            if ($this->dbF->rowCount>0) {
foreach ($val as $key => $value) {


$slug = unserialize($data1['title']);

$links = WEB_URL."/".$value['slug'];

$heading = @$slug[$value['lang']];

if (empty($heading)) {
$heading = $slug['Swedish'];
   

}
$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$heading
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$links' class='form-control new seo table for pages'>
<div class='input-group-addon pointer' data-val='$links' onclick='".$value['lang']."copyLink(this);'>".$value['lang']."</div>
</div>
</div>
";
    # code...
}
}


        }
        $temp .= "</div>";
        return $temp;
    }

        public function pageLinksSEO($copyDiv=true){
        global $_e;
        $sql    =   "SELECT page_pk,slug FROM `pages` WHERE publish = '1'";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Page Links']) ."</h3>";

        $link   =   WEB_URL."/";
        $slug = _uc($_e['Home']);
        $temp2 = "";
      if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }
        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";

        foreach($data as $val){
            $link   =   WEB_URL."/".$this->db->dataPage."$val[slug]";
            $slug   =   $val['slug'];
            $temp2 = "";
           if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }

    public function categoryLinks($copyDiv=true,$productDeal=false){
        global $_e;
        $sql    =   "SELECT * FROM `tree_data` WHERE id != '1'";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>";

        if($productDeal){
            $temp .= "<h3>" . _uc($_e['Product Deals Category Links']) . "</h3>";
        }else {
            $temp .= "<h3>" . _uc($_e['Product Category Links']) . "</h3>";
        }

        if($productDeal) {
            $link = WEB_URL . "/productDeals";
            $slug = _uc($_e['All Deals Products']);
        }else {
            $link = WEB_URL . "/products";
            $slug = _uc($_e['All Products']);
        }

        $temp2 = "";
         if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>";

        if($productDeal) {
            $link = WEB_URL . "/".$this->db->dealCategory;
        }else {
            $link = WEB_URL . "/".$this->db->pCategory;
        }

        foreach($data as $val){
            //$linkThis =  $link."?cat=$val[nm]&catId=$val[id]";
            $linkThis =  $link."$val[id]-$val[nm]"; // pCategory slug
            $slug  =  $val['nm'];

            $temp2 = "";
          if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$linkThis' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }





    public function dealCategoryLinksWOLang($copyDiv=true){
    global $_e;

        // $sql = "SELECT * FROM `seo`";
        // $data   =   $this->dbF->getRows($sql);

        $sql    =   "SELECT * FROM `categories`";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Product Category Links']) ."</h3>";

        $link   =   WEB_URL."/products";
        $slug = _uc($_e['Home']);
        $temp2 = "";
          if($copyDiv){
     



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>";




        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        // $link='';
        // $slug='';
        foreach($data as $val){

            $name = translateFromSerialize($val['name']);
            $name = str_replace(' ', '-', $name);
            $sl = $val['id'].'deals';

            //$pglnk = "%/".$this->db->pCategory.$val['id']."%";

            $sql1 = "SELECT * FROM `seo` WHERE `slug` = ?";
            $data1   =   $this->dbF->getRow($sql1, array($sl));

            if($this->dbF->rowCount > 0){
                $link = WEB_URL."/".$data1['slug'];
                $slug = $name;
            }
            else{
                $link = WEB_URL."/".$this->db->pCategory.$val['id'];
                $slug = $name;
            }            

            // $link   =   WEB_URL."/"."$val[slug]";
            // $slug   =   $val['slug'];
            $temp2 = "";
           if($copyDiv){
 



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>";


        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control fhgf 7'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }


    public function dealCategoryLinks($copyDiv=true){
    global $_e;

        // $sql = "SELECT * FROM `seo`";
        // $data   =   $this->dbF->getRows($sql);

        $sql    =   "SELECT * FROM `categories`";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Product Category Links']) ."</h3>";

        $link   =   WEB_URL."/products";
        $slug = _uc($_e['Home']);
        $temp2 = "";
          if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        // $link='';
        // $slug='';
        foreach($data as $val){

            $name = translateFromSerialize($val['name']);
            $name = str_replace(' ', '-', $name);
            $sl = $val['id'].'deals';

            //$pglnk = "%/".$this->db->pCategory.$val['id']."%";

            $sql1 = "SELECT * FROM `seo` WHERE `slug` = ?";
            $data1   =   $this->dbF->getRow($sql1, array($sl));

            if($this->dbF->rowCount > 0){
                $link = WEB_URL."/".$data1['slug'];
                $slug = $name;
            }
            else{
                $link = WEB_URL."/".$this->db->pCategory.$val['id'];
                $slug = $name;
            }            

            // $link   =   WEB_URL."/"."$val[slug]";
            // $slug   =   $val['slug'];
            $temp2 = "";
           if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$slug
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control fhgf8'>
<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>
</div>
</div>
";



$sql1 = "SELECT * FROM `seo_slug` WHERE `seo_id` = ? and `slug` !=''";
@$val   =   $this->dbF->getRows($sql1,array($data1['id']));

if ($this->dbF->rowCount>0) {
foreach ($val as $key => $value) {


@$slug = unserialize($data1['title']);

$links = WEB_URL."/".$value['slug'];

$heading = @$slug[$value['lang']];

if (empty($heading)) {
$heading = $name;
    # code...
}


$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$heading
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$links' class='form-control new seo table for categories'>
<div class='input-group-addon pointer' data-val='$links' onclick='".$value['lang']."copyLink(this);'>".$value['lang']."</div>
</div>
</div>
";
# code...
}
}











        }
        $temp .= "</div>";
        return $temp;
    }





        public function productCategoryLinksWOLang($copyDiv=true){
        global $_e;

        // $sql = "SELECT * FROM `seo`";
        // $data   =   $this->dbF->getRows($sql);

        $sql    =   "SELECT * FROM `categories`";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Product Category Links']) ."</h3>";

        $link   =   WEB_URL."/products";
        $slug = _uc($_e['Home']);
        $temp2 = "";
          if($copyDiv){



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>";





        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        // $link='';
        // $slug='';
        foreach($data as $val){

            $name = translateFromSerialize($val['name']);
            $name = str_replace(' ', '-', $name);

            $pglnk = $this->db->pCategory.$val['id'];

            $sql1 = "SELECT * FROM `seo` WHERE `ref_id` LIKE '%$pglnk%'";
            $data1   =   $this->dbF->getRow($sql1);

            if($this->dbF->rowCount > 0){
                $link = WEB_URL."/".$data1['slug'];
                $slug = $name;
            }
            else{
                $link = WEB_URL."/".$this->db->pCategory.$val['id'];
                $slug = $name;
            }            

            // $link   =   WEB_URL."/"."$val[slug]";
            // $slug   =   $val['slug'];
            $temp2 = "";
            if($copyDiv){
        



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>";





        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control fhgf9'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }  

        public function productCategoryLinks($copyDiv=true){
        global $_e;

        // $sql = "SELECT * FROM `seo`";
        // $data   =   $this->dbF->getRows($sql);

        $sql    =   "SELECT * FROM `categories`";
        $data   =   $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Product Category Links']) ."</h3>";

        $link   =   WEB_URL."/products";
        $slug = _uc($_e['Home']);
        $temp2 = "";
          if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

        $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control'>
                                  $temp2
                                </div>
                            </div>
                          ";
        // $link='';
        // $slug='';
        foreach($data as $val){

            $name = translateFromSerialize($val['name']);
            $name = str_replace(' ', '-', $name);

            $pglnk = $this->db->pCategory.$val['id'];

            $sql1 = "SELECT * FROM `seo` WHERE `ref_id` LIKE '%$pglnk%'";
            $data1   =   $this->dbF->getRow($sql1);

            if($this->dbF->rowCount > 0){
                $link = WEB_URL."/".$data1['slug'];
                $slug = $name;
            }
            else{
                $link = WEB_URL."/".$this->db->pCategory.$val['id'];
                $slug = $name;
            }            

            // $link   =   WEB_URL."/"."$val[slug]";
            // $slug   =   $val['slug'];
            $temp2 = "";
            if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control fhgf 1'>
                                 <div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>USE</div>
                                </div>
                            </div>
                          ";



$sql1 = "SELECT * FROM `seo_slug` WHERE `seo_id` = ? and `slug` !=''";
$val   =   $this->dbF->getRows($sql1,array($data1['id']));

if ($this->dbF->rowCount>0) {
foreach ($val as $key => $value) {


@$slug = unserialize($data1['title']);

$links = WEB_URL."/".$value['slug'];

$heading = @$slug[$value['lang']];

if (empty($heading)) {
@$heading = @$slug['Swedish'];
   

}

$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$heading
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$links' class='form-control new seo table for categories'>
<div class='input-group-addon pointer' data-val='$links' onclick='".$value['lang']."copyLink(this);'>".$value['lang']."</div>
</div>
</div>
";
# code...
}
}



        }
        $temp .= "</div>";
        return $temp;
    }  

        public function ProductLinks($copyDiv=true){
        global $_e;

        $sql = "SELECT * FROM `proudct_detail` WHERE `product_update` = 1";
        $data   =   $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Product Links']) ."</h3>";

        $link = '';                

        $temp2 = "";
          if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

        foreach($data as $val){
            $name = translateFromSerialize($val['prodet_name']);
            $link   =   WEB_URL."/".$this->db->productDetail."$val[slug]";
            $slug   =   $name;
            $temp2 = "";
            if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control fhgf 2'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }


        public function DealProductLinks($copyDiv=true){
        global $_e;

        $sql = "SELECT * FROM `product_deal` WHERE `publish` = 1";
        $data   =   $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Deal Product Links']) ."</h3>";

        $link = '';                

        $temp2 = "";
        if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }
        
        foreach($data as $val){
            $name = translateFromSerialize($val['name']);
            $link   =   WEB_URL."/".$this->db->dealProduct."$val[slug]";
            $slug   =   $name;
            $temp2 = "";
            if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control fhgf4'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    } 

        public function BlogLinks($copyDiv=true){
        global $_e;

        $sql = "SELECT * FROM `blog` WHERE `publish` = 1";
        $data   =   $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){
            return false;
        }
        $temp   =   "<div class='col-sm-12 form-horizontal'>
                        <h3>". _uc($_e['Blog Links']) ."</h3>";

        $link = '';                

        $temp2 = "";
         if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }
        
        foreach($data as $val){
            $name = translateFromSerialize($val['heading']);
            $link   =   WEB_URL."/".$this->db->blogPage."$val[slug]";
            $slug   =   $name;
            $temp2 = "";
            if($copyDiv){
        $lang   =   $this->functions->IbmsLanguages();
             for ($i = 0; $i < sizeof($lang); $i++) {



            $temp2 .= "<div class='input-group-addon pointer' data-val='$link' onclick='".$lang[$i]."copyLink(this);'>".$lang[$i]."</div>";


        }



        }

            $temp   .=    "<div class='form-group'>
                                <label class='col-sm-2 control-label'>
                                    $slug
                                </label>
                                <div class='input-group col-sm-10'>
                                  <input type='text' value='$link' class='form-control fhgf 5'>
                                  $temp2
                                </div>
                            </div>
                          ";
        }
        $temp .= "</div>";
        return $temp;
    }




    public function menuWidgetLinksWOLang($copyDiv=true){
        global $_e;
        //Required class .linkList where click to open Diaglog,
        //if want to past, use .pastLinkHere class on target input area
        // if copyDiv is true, its mean USE will show, to copy link
        $body = $this->pageLinksWOLang($copyDiv);
        if($this->functions->developer_setting('product') == '1') {
            $body .= $this->productCategoryLinksWOLang($copyDiv);
            if($this->functions->developer_setting('dealProduct') == '1') {
                $body .= $this->dealCategoryLinksWOLang($copyDiv, true);
            }
        }
    $this->functions->dialogCommon('linkList','linkList',true,$body);
        echo "
            <style>
                #linkList{
                        max-height: 400px !important;
                }
            </style>
            <script>
                $(document).ready(function(){
                    $('.linkList').click(function(){
                        $('#linkList').dialog('open');
                    });

                });
                function copyLink(ths){
                    link    = $(ths).attr('data-val');
                         console.log(link);console.log('000111');
                    $('.pastLinkHere').val(link);
               $('#linkList').dialog('close');
                  
                }
                </script>";






    }

    public function menuWidgetLinks($copyDiv=true){
        global $_e;
        //Required class .linkList where click to open Diaglog,
        //if want to past, use .pastLinkHere class on target input area
        // if copyDiv is true, its mean USE will show, to copy link
        $body = $this->pageLinks($copyDiv);
        if($this->functions->developer_setting('product') == '1') {
            $body .= $this->productCategoryLinks($copyDiv);
            if($this->functions->developer_setting('dealProduct') == '1') {
                $body .= $this->dealCategoryLinks($copyDiv, true);
            }
        }


        // $body .= $this->seo_slug_multi($copyDiv);






        $lang   =   $this->functions->IbmsLanguages();

for ($i = 0; $i < sizeof($lang); $i++) {



        $this->functions->dialogCommon(''.$lang[$i].'linkList',''.$lang[$i].'linkList',true,$body);
        echo "
            <style>
                #".$lang[$i]."linkList{
                        max-height: 400px !important;
                }
            </style>
            <script>
                $(document).ready(function(){
                    $('.".$lang[$i]."linkList').click(function(){

                        console.log('dsdsdsdsdsd');
                     var lang = $(this).attr('data-lang');
console.log(lang);
                    $('.allLang').text(lang);



                        $('#".$lang[$i]."linkList').dialog('open');
                    });

                });
                function ".$lang[$i]."copyLink(ths){
                    link    = $(ths).attr('data-val');
                    $('.".$lang[$i]."pastLinkHere').val(link);
                    $('#EnglishlinkList').dialog('close');
                    $('#SwedishlinkList').dialog('close');
                    $('#NorwegianlinkList').dialog('close');
                    $('#DanishlinkList').dialog('close');
                }
                </script>";


                
            }


  echo "            <style>
                #linkList{
                        max-height: 400px !important;
}
</style>
<script>
$(document).ready(function(){
$('.linkList').click(function(){
$('#linkList').dialog('open');
});

});
function copyLink(ths){
link    = $(ths).attr('data-val');


var lang = $('.allLang').text();

console.log(lang+'llllll');
 var varLang = lang+'pastLinkHere';
 var varLanglinkList = lang+'linkList';
 $('.'+varLang).val(link);


console.log(link);console.log('000');

console.log(lang);


                    $('#'+varLanglinkList).dialog('close');
                    $('#EnglishlinkList').dialog('close');
                    $('#SwedishlinkList').dialog('close');
                    $('#NorwegianlinkList').dialog('close');
                    $('#DanishlinkList').dialog('close');
}
</script>";



}

        public function menuWidgetLinksSEO($copyDiv=true){
        global $_e;
        //Required class .linkList where click to open Diaglog,
        //if want to past, use .pastLinkHere class on target input area
        // if copyDiv is true, its mean USE will show, to copy link
        $body = $this->pageLinksSEO($copyDiv);
        if($this->functions->developer_setting('product') == '1') {
            $body .= $this->CategoryLinks($copyDiv);
            if($this->functions->developer_setting('dealProduct') == '1') {
                $body .= $this->CategoryLinks($copyDiv, true);
            }
            $body .= $this->ProductLinks($copyDiv);
            $body .= $this->DealProductLinks($copyDiv);
            $body .= $this->BlogLinks($copyDiv);
        }



    // $this->functions->dialogCommon('linkList','linkList',true,$body);
    //     echo "
    //         <style>
    //             #linkList{
    //                     max-height: 400px !important;
    //             }
    //         </style>
    //         <script>
    //             $(document).ready(function(){
    //                 $('.linkList').click(function(){
    //                     $('#linkList').dialog('open');
    //                 });

    //             });
    //             function copyLink(ths){
    //                 link    = $(ths).attr('data-val');
    //                 $('.pastLinkHere').val(link);
    //            $('#linkList').dialog('close');
                  
    //             }
    //             </script>";
         $lang   =   $this->functions->IbmsLanguages();

for ($i = 0; $i < sizeof($lang); $i++) {



        $this->functions->dialogCommon(''.$lang[$i].'linkList',''.$lang[$i].'linkList',true,$body);
        echo "
            <style>
                #".$lang[$i]."linkList{
                        max-height: 400px !important;
                }
            </style>
            <script>
                $(document).ready(function(){
                    $('.".$lang[$i]."linkList').click(function(){
                        $('#".$lang[$i]."linkList').dialog('open');
                    });

                });
                function ".$lang[$i]."copyLink(ths){
                    link    = $(ths).attr('data-val');
                    $('.".$lang[$i]."pastLinkHere').val(link);
                    $('#EnglishlinkList').dialog('close');
                    $('#SwedishlinkList').dialog('close');
                    $('#NorwegianlinkList').dialog('close');
                    $('#DanishlinkList').dialog('close');
                    
                }
                </script>";

            }

    }

    
          






}
?>

