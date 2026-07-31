<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class bestsellers extends object_class{
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
        $_w['Best Seller Management'] = '' ;
        //bestsellersEdit.php
        $_w['Manage Bestsellers'] = '' ;

        //bestsellers.php
        $_w['Active Bestsellers'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Sort Bestsellers'] = '' ;
        $_w['Add New Bestseller'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;
        $_w['SNO'] = '' ;
        $_w['PRODUCT'] = '' ;
        $_w['DATE'] = '' ;
        $_w['ACTION'] = '' ;

        $_w['Image File Error'] = '' ;
        $_w['Image Not Found'] = '' ;
        $_w['Bestsellers'] = '' ;
        $_w['Added'] = '' ;
        $_w['Bestseller Add Successfully'] = '' ;
        $_w['Bestseller Add Failed'] = '' ;
        $_w['Bestseller Update Failed'] = '' ;
        $_w['Bestseller Update Successfully'] = '' ;
        $_w['Update'] = '' ;
        $_w['Bestseller Title'] = '' ;
        $_w['Bestseller Link'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Image Recommended Size : {{size}}'] = '' ;
        $_w['Publish'] = '' ;
        $_w['Layer'] = '' ;

        $_w['SAVE'] = '' ;
        $_w['Old Bestseller Image'] = '' ;
        $_w['Choose Product'] = '' ;
        $_w['Type Product Name For Suggestion List'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Bestsellers');

    }


    public function bestsellersSort(){

          // SELECT * FROM `best_seller_products` b
          // LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
          // LEFT OUTER JOIN `product_image` pi ON pi.product_id = p.prodet_id
          // WHERE b.publish='1' ORDER BY pi.sort, b.sort ASC

        echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
        $sql  = " SELECT * FROM `best_seller_products` b
                  LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
                  LEFT OUTER JOIN `product_image` pi ON pi.product_id = p.prodet_id
                  WHERE b.publish='1' AND ( pi.sort = 0 OR pi.sort IS NULL )
                  GROUP BY b.product_id
                  ORDER BY pi.sort, b.sort ASC ";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $id = $val['id'];
            @$layer0    =   unserialize($val['layer0']);
            @$image    =   WEB_URL.'/images/'.$val['image'];
            // @$title = unserialize($val['banner_heading']);
            $title = $this->functions->unserializeTranslate($val['prodet_name']);
            // @$title = $title[$defaultLang];
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


    public function bestsellersView(){
        $sql  = " SELECT * FROM `best_seller_products` b
                  LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
                  WHERE b.publish='1' ORDER BY b.ID DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->bestsellersPrint($data);
    }

    public function bestsellersDraft(){
        $sql  = " SELECT * FROM `best_seller_products` b
                  LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
                  WHERE b.publish='0' ORDER BY b.ID DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->bestsellersPrint($data);
    }

    public function bestsellersPrint($data){
        global $_e;
        // $class = 'tableIBMS';
        // $heading = false;
        // if($this->functions->developer_setting('banner_heading')=='1'){
            $class=" dTable tableIBMS";
            $heading = true;
        // }
        echo '<div class="table-responsive">
                <table class="table table-hover '.$class.'">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>';
        if($heading){
            echo        '<th>'. _u($_e['PRODUCT']) .'</th>';
        }
        echo            '<th>'. _u($_e['DATE']) .'</th>
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
                $product_id    =   $val['prodet_id'];
                @$product_name = unserialize($val['prodet_name']);
                @$product_name = $product_name[$defaultLang];
                $product_link  = "<a href='?edit_pro={$product_id}'  data-id='{$product_id}' data-method='post'  data-window='new' data-action='-product?page=edit' class='btn btn-info'>$product_name</a>";

                echo "<td>{$product_link}</td>";
            }

            @$layer0    =   unserialize($val['layer0']);
            @$layer0    =   $this->functions->addWebUrlInLink($layer0[$defaultLang]);
            
            @$date_updated =   ($val['date_updated']);

            echo "
                    <td>{$date_updated}</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='{$id}' href='-bestseller?page=edit&bestsellerId={$id}' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='{$id}' onclick='deleteBestseller(this);' class='btn'>
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

    public function newBestsellersAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newBestseller')){return false;}

            $product_id   = isset($_POST['product_id'])   ? $_POST['product_id']    : 0;
            // $product_name = isset($_POST['product_name']) ? $_POST['product_name']  : 0;
            $publish      = isset($_POST['publish'])      ? $_POST['publish']  : 0;

            try{
                $this->db->beginTransaction();

                if($product_id == 0) {
                    throw new Exception("Error Processing Request", 1);
                }

                $sql      =   "INSERT INTO `best_seller_products`(
                                    `product_id`, `publish`)
                                    VALUES ( ?,? )";

                $array   = array($product_id,$publish);
                $this->dbF->setRow($sql,$array,false);
                $lastId = $this->dbF->rowLastId;

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Bestsellers']),$lastId,($_e['Bestseller Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Add Failed']),'btn-danger');
                }
            }catch (Exception $e){
                // var_dump($e[2]);
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Add Failed']),'btn-danger');
            }
        } // If end
    }




    public function bestsellersEditSubmit(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('editBestsellers')){return false;}


            $product_id   = isset($_POST['product_id'])   ? $_POST['product_id']    : 0;
            $product_name = isset($_POST['product_name']) ? $_POST['product_name']  : 0;
            $publish      = isset($_POST['publish'])      ? $_POST['publish']       : 0;


            try{
                $this->db->beginTransaction();
                $lastId   =   $_POST['editId'];

                $sql    =  "UPDATE `best_seller_products` SET
                                    `product_id`    =   ?,
                                    `publish`       =   ?
                                    WHERE id        =   ?
                                ";

                $array   = array($product_id,$publish,$lastId);
                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Update']),_uc($_e['Bestsellers']),$lastId,($_e['Bestseller Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Update Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Bestsellers']),($_e['Bestseller Update Failed']),'btn-danger');
            }

        }
    }

    public function bestsellersNew(){
        global $_e;
        $this->bestsellersEdit(true);
    }

    public function bestsellersEdit($new=false){
        global $_e;
        if($new){
            $token = $this->functions->setFormToken('newBestseller',false);
            $product_id = '';
        }else {
            $id    = $_GET['bestsellerId'];
            $sql   = " SELECT * FROM `best_seller_products` b
                       LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
                       WHERE b.id = ?
                       ORDER BY b.ID DESC ";
            $data  = $this->dbF->getRow($sql,array($id));

            $product_id = $data['prodet_id'];
            
            $token = $this->functions->setFormToken('editBestsellers', false);
            $token .= '<input type="hidden" name="editId" value="'.$id.'"/>';
        }

        // $size = $this->functions->developer_setting('banner_size');
        //No need to remove any thing,, go in developer setting table and set 0

        echo '<form method="post" action="-bestseller?page=bestsellers" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            '
            <div class="form-horizontal">';

        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }

        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';
        echo '<input id="product_id" type="hidden" name="product_id" value="'.$product_id.'" />';

        echo '<div class="panel-group" id="accordion">';

        @$heading   = unserialize($data['heading']);
        @$shrtDesc  = unserialize($data['shrtDesc']);
        @$layer0    = unserialize($data['layer0']);
        @$layer1    = unserialize($data['layer1']);
        @$layer2    = unserialize($data['layer2']);
        @$layer3    = unserialize($data['layer3']);

        $defaultLang  = $this->functions->AdminDefaultLanguage();
        $product_name = isset($data) ? unserialize($data['prodet_name'])[$defaultLang] : '';
        // $product_name = $product_name[$defaultLang];

        // for ($i = 0; $i < sizeof($lang); $i++) {
            // if ($i == 0) {
                $collapseIn = ' in ';
            // } else {
                // $collapseIn = '';
            // }


            //Title
            // if($this->functions->developer_setting('heading')=='1'){
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Choose Product']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <input id="product_name" type="text" name="heading" value="'.@$product_name.'" class="form-control typeahead" placeholder="'. _uc($_e['Type Product Name For Suggestion List']) .'" autocomplete="off" required="" >
                        </div>
                    </div>';
            // }else{ echo '<input type="hidden" name="heading" value="" class="form-control">';}

            // //Short Desc
            // if($this->functions->developer_setting('shrtDesc')=='1'){
            //     $classEditor = '';
            //     if($this->functions->developer_setting('shrtDescEditor')=='1'){
            //         $classEditor = 'ckeditor';
            //     }
            //     echo '<div class="form-group">
            //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Desc']) .'</label>
            //             <div class="col-sm-10  col-md-9">
            //                 <textarea name="shrtDesc" id="shrtDesc" maxlength="500" class="'.$classEditor.' form-control" placeholder="'. _uc($_e['Short Desc']) .'">'.@$shrtDesc.'</textarea>
            //             </div>
            //         </div>';
            // }else{ echo '<input type="hidden" name="shrtDesc" value="" class="form-control">';}


            // //bestseller_layer0
            // if($this->functions->developer_setting('bestseller_layer0')=='1'){
            //     $image0 = empty($layer0) ? "" : $this->functions->addWebUrlInLink(@$layer0);
            //     echo '<div class="form-group">
            //         <label class="col-sm-2 col-md-3  control-label"></label>
            //         <div class="col-sm-10  col-md-9 ">
            //             <img src="'.$image0.'" class="layer0 kcFinderImage"/>
            //         </div>
            //     </div>';

            //     echo '<div class="form-group">
            //             <label class="col-sm-2 col-md-3  control-label">'. _replace('{{size}}',$size,$_e['Image Recommended Size : {{size}}']) .'</label>
            //             <div class="col-sm-10  col-md-9">
            //                 <div class="input-group">
            //                     <input type="url"  name="layer0" value="'.$image0.'" class="layer0 form-control" placeholder="">
            //                     <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('layer0')".'"><i class="glyphicon glyphicon-picture"></i></div>
            //                 </div>
            //             </div>
            //         </div>';
            // }else{ echo '<input type="hidden" name="layer0" value="" class="form-control">';}

            // //layer1
            // $lay1_Status = intval($this->functions->developer_setting('bestseller_layer1'));
            // if($lay1_Status>0){
            //     if($lay1_Status==2) {
            //         $image1 = empty($layer1) ? "" : $this->functions->addWebUrlInLink(@$layer1);
            //         echo '<div class="form-group">
            //                 <label class="col-sm-2 col-md-3  control-label"></label>
            //                 <div class="col-sm-10  col-md-9 ">
            //                     <img src="' .$image1 . '" class="layer1 kcFinderImage"/>
            //                 </div>
            //         </div>';
            //         $lay1_Status = '<div class="input-group">
            //                     <input type="text"  name="layer1" value="'.$image1.'" class="layer1 form-control" placeholder="">
            //                     <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('layer1')".'"><i class="glyphicon glyphicon-picture"></i></div>
            //                 </div>';
            //     }else{
            //         $image1 = @$layer1;
            //         $lay1_Status = '
            //             <input type="text"  name="layer1" value="'.@$layer1.'" class="layer1 form-control" placeholder="">';
            //     }

            //     echo '<div class="form-group">
            //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Layer']) .' 1</label>
            //             <div class="col-sm-10  col-md-9">
            //                 '.$lay1_Status.'
            //             </div>
            //         </div>';
            // }else{ echo '<input type="hidden" name="layer1" value="" class="form-control">';}

            // //banner_layer2
            // $lay2_Status = intval($this->functions->developer_setting('banner_layer2'));
            // if($lay2_Status>0){
            //     if($lay2_Status==2) {
            //         $image2 = empty($layer2]) ? "" : $this->functions->addWebUrlInLink(@$layer2]);
            //         echo '<div class="form-group">
            //                 <label class="col-sm-2 col-md-3  control-label"></label>
            //                 <div class="col-sm-10  col-md-9 ">
            //                     <img src="' .$image2. '" class="layer2 kcFinderImage"/>
            //                 </div>
            //         </div>';
            //         $lay2_Status = '<div class="input-group">
            //                     <input type="text"  name="layer2" value="'.$image2.'" class="layer2 form-control" placeholder="">
            //                     <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('layer2')".'"><i class="glyphicon glyphicon-picture"></i></div>
            //                 </div>';
            //     }else{
            //         $image2 = @$layer2];
            //         $lay2_Status = '
            //             <input type="text"  name="layer2" value="'.@$layer2].'" class="layer2 form-control" placeholder="">';
            //     }

            //     echo '<div class="form-group">
            //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Layer']) .' 2</label>
            //             <div class="col-sm-10  col-md-9">
            //                 '.$lay2_Status.'
            //             </div>
            //         </div>';
            // }else{ echo '<input type="hidden" name="layer2" value="" class="form-control">';}

            // //banner_layer3
            // $lay3_Status = intval($this->functions->developer_setting('banner_layer3'));
            // if($lay3_Status>0){
            //     if($lay3_Status==2) {
            //         $image3 = empty($layer3]) ? "" : $this->functions->addWebUrlInLink(@$layer3]);
            //         echo '<div class="form-group">
            //                 <label class="col-sm-2 col-md-3  control-label"></label>
            //                 <div class="col-sm-10  col-md-9 ">
            //                     <img src="' .$image3. '" class="layer3 kcFinderImage"/>
            //                 </div>
            //         </div>';
            //         $lay3_Status = '<div class="input-group">
            //                     <input type="text"  name="layer3" value="'.$image3.'" class="layer3 form-control" placeholder="">
            //                     <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('layer3')".'"><i class="glyphicon glyphicon-picture"></i></div>
            //                 </div>';
            //     }else{
            //         $image3 = @$layer3];
            //         $lay3_Status = '
            //             <input type="text"  name="layer3" value="'.$image3.'" class="layer3 form-control" placeholder="">';
            //     }

            //     echo '<div class="form-group">
            //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Layer']) .' 3</label>
            //             <div class="col-sm-10  col-md-9">
            //                 '.$lay3_Status.'
            //             </div>
            //         </div>';
            // }else{ echo '<input type="hidden" name="layer3" value="" class="form-control">';}

        // }

        echo '</div> <!-- .accordian end -->';


        // //Link
        // if($this->functions->developer_setting('bestseller_link')=='1'){
        //     echo '<div class="form-group">
        //             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Bestseller Link']) .'</label>
        //             <div class="col-sm-10  col-md-9">
        //                 <input type="url" name="link" value="'.$this->functions->addWebUrlInLink(@$data['link']).'" class="form-control" placeholder="'. _uc($_e['Bestseller Link']) .'">
        //             </div>
        //         </div>';
        // }else{ echo '<input type="hidden" name="link" value="" class="form-control">';}

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
    }
}
?>