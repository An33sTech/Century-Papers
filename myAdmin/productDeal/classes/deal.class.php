<?php
require_once (__DIR__."/../../global.php"); //connection setting db


require_once(__DIR__ . "/../../product_management/classes/currency.class.php");
class deal extends object_class{
    public $productF;
    public $c_currency;
    public function __construct(){
        parent::__construct('3');

        $this->c_currency = new currency_management();

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //Index
        $_w['Deal Management'] = '' ;
        //dealEdit.php
        $_w['Manage Deal'] = '' ;
        $_w['Multiple Products'] = '';
        $_w['Yes'] = '';
        $_w['No'] = '';

        //deal.php
        $_w['Active Deal'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Category'] = '' ;
        $_w['Basic Information'] = '' ;
        $_w['Sort Deal'] = '' ;
        $_w['Add New Deal'] = '' ;
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
        $_w['Deal'] = '' ;
        $_w['Added'] = '' ;
        $_w['Deal Add Successfully'] = '' ;
        $_w['Deal Add Failed'] = '' ;
        $_w['Deal Update Failed'] = '' ;
        $_w['Deal Update Successfully'] = '' ;
        $_w['Update'] = '' ;
        $_w['Deal Title'] = '' ;
        $_w['Deal Link'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Image Recommended Size : {{size}}'] = '' ;
        $_w['Publish'] = '' ;
        $_w['Layer'] = '' ;

        $_w['SAVE'] = '' ;
        $_w['Designation'] = '' ;
        $_w['Email'] = '' ;
        $_w['Date'] = '' ;
        $_w['Old File Image'] = '' ;
        $_w['Fileds'] = '' ;
        $_w['Edit Form Fields'] = '' ;
        $_w['Deal Name'] = '' ;
        $_w['Fields Name: separate with comma'] = '' ;
        $_w['This is only For admin to manage or sort fields'] = '' ;
        $_w['Field Name'] = '' ;
        $_w['Field Desc'] = '' ;
        $_w['Required'] = '' ;
        $_w['Fileds'] = '' ;
        $_w['Price'] = '' ;
        $_w['Image'] = '' ;
        $_w['NAME'] = '' ;
        $_w['Slug'] = '' ;
        $_w['Deal Products'] = '' ;
        $_w['Short Description'] = '' ;
        $_w['Deal Box Size'] = '' ;
        $_w['Small'] = '' ;
        $_w['Big'] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        $_w[''] = '' ;
        
        $_w['Option 1 Heading'] = '' ; 
        $_w['option 1 Products'] = '' ; 
        $_w['Option 2 Heading'] = '' ;
        $_w['option 2 Products'] = '' ;
        $_w['Option 3 Heading'] = '' ;
        $_w['option 3 Products'] = '' ;
        $_w['Option 4 Heading'] = '' ;
        $_w['option 4 Products'] = '' ;
        $_w['Option 5 Heading'] = '' ;
        $_w['option 5 Products'] = '' ;
        $_w['Option 6 Heading'] = '' ;
        $_w['option 6 Products'] = '' ;
        $_w['Option 7 Heading'] = '' ;
        $_w['option 7 Products'] = '' ;
        $_w['Option 8 Heading'] = '' ;
        $_w['option 8 Products'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Deal');

    }


    public function dealSort(){
        echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
        $sql ="SELECT `name`,image,id FROM `product_deal` WHERE publish = '1' ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);

        foreach($data as $val){
            $id = $val['id'];
            @$deal_image    =   ($val['image']);
            $image    =  WEB_URL."/images/".$deal_image;
            $title     = $this->functions->unserializeTranslate($val['name']);
            echo '<div class="singleAlbum " id="album_'.$id.'">
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


    public function dealView(){
        $this->functions->dTableT();
        $sql  = "SELECT * FROM product_deal WHERE publish='1' ORDER BY id DESC";
        $data =  $this->dbF->getRows($sql);
        $this->dealPrint($data);
    }

    public function dealDraft(){
        $sql  = "SELECT * FROM product_deal WHERE publish='0' ORDER BY id DESC";
        $data =  $this->dbF->getRows($sql);
        $this->dealPrint($data);
    }

    public function dealPrint($data){
        global $_e;
        $class="dTableT tableIBMS";
            echo '<div class="table-responsive">
                <table class="table table-hover '.$class.'">
                    <thead>
                        <tr>
                            <th>'. _u($_e['SNO']) .'</th>
                            <th>'. _u($_e['NAME']) .'</th>
                            <th>'. _u($_e['ACTION']) .'</th>
                        </tr>
                    </thead>
                <tbody>';

        $i = 0;
        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            echo "<tr>
                    <td>$i</td>";
                $custom_type = $this->functions->unserializeTranslate($val['name']);
                echo "<td>$custom_type</td>";


            $seoLink = '';
            if($this->functions->developer_setting('seo') == '1'){
                $this->functions->getAdminFile("seo/classes/seo.class.php");
                $seoC = new seo();
                $seoLink = $seoC->seoQuickLink($id,urlencode("/".$this->db->dealProduct."$val[slug]"));
            }


            echo "
                    <td>
                        <div class='btn-group btn-group-sm'>

                        $seoLink

                            <a data-id='$id' href='-".$this->functions->getLinkFolder()."?page=edit&pId=$id&view=edit' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteDeal(this);' class='btn'>
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

    public function newDealAdd(){
        global $_e;
        if(!isset($_POST['editId'])){
            if(!$this->functions->getFormToken('newDeal')){return false;}
            try{
                $this->db->beginTransaction();
                $image = $this->functions->uploadSingleImage($_FILES['image'],'deal');

                if(!empty($image)){
                    $_POST['insert']['image'] = $image;
                }
                //category
                @$categ = !isset($_POST['categoryy']) ? array() : $_POST['categoryy'];
                $category_ch = '';
                
                foreach ($categ as $key => $value) {
                    @$_POST['insert']['category'] .= $value.',';
                    @$category_ch .= $value.',';
                    @$category_ch .= 'ss'.$value.',';
                    # code...
                }


                if(isset($_POST['insert']['category'])){
                    $_POST['insert']['category'] = rtrim($_POST['insert']['category'],",");
                }
                
                $category_ch = rtrim($category_ch,",");



                //slug
                $slug = $_POST['slug'];

                $sql_slug = "SELECT * FROM `product_deal` where `slug` = ? ";
                $data_slug = $this->dbF->getRow($sql_slug, array($slug));

                if($this->dbF->rowCount!=0){
                    $slug = $slug."-".rand(1, 15);
                }

                if(!empty($slug)){
                    $_POST['insert']['slug'] = $slug;
                }
                $lastId = $this->functions->formInsert('product_deal',$_POST['insert']);

                if(empty($lastId)){
                    $this->functions->notificationError(_uc($_e['Deal']),'$check: '.$check.' slug after check: '.$slug.' slug in post: '.$_POST['insert']['slug'],'btn-success');
                    throw new Exception("Dublicate slug");
                }



                //if slug not set, then update to its default id
                if(empty($slug)){
                    $sql = "UPDATE product_deal SET slug = ? WHERE id = ?";
                    $this->dbF->setRow($sql, array($lastId, $lastId));
                       $slug = $lastId;
                }


                $ref_id = $this->db->dealProduct.$lastId;
                $pageLink = "/".$this->db->dealProduct.$slug;

                $sql = " INSERT INTO `seo` (
                        `ref_id`,
                        `slug`,
                        `pageLink`,
                        `revisit-after`,
                        `type`,
                        `publish`
                        )

            VALUES ('$ref_id', '$slug', '$pageLink', '1 week','dealProduct' , 1 ) ";
            $this->dbF->setRow($sql);


                $sql = "INSERT " . "INTO `product_deal_price`
                        (`deal_id`,`currencyId`,`price`) VALUES ";
                $val_array = array();
                foreach ($_POST['insert']['price'] as $key => $val) {
                        $sql .= " (?,?,?) ,";
                        $val_array[] = $lastId;
                        $val_array[] = $key;
                        $val_array[] = doubleval($val);
                }
                $sql = trim($sql, ",");
                $this->dbF->setRow($sql, $val_array);

                //Insert Setting
                $sql = "INSERT " . "INTO `product_deal_setting`
                        (`deal_id`,`setting_name`,`setting_val`) VALUES ";
                $val_array = array();
                $foreach_loop = false;
                foreach ($_POST['setting'] as $key => $val) {
                    if(is_array($val)){
                        $val = serialize($val);
                    }
                        $sql .= " (?,?,?) ,";
                        $val_array[] = $lastId;
                        $val_array[] = $key;
                        $val_array[] = $val;
                        $foreach_loop = true;
                }
                $sql = trim($sql, ",");

                if ($foreach_loop) {
                    $this->dbF->setRow($sql, $val_array);
                }

                if($lastId != false){
                    $this->db->commit();
                    $this->functions->notificationError(_uc($_e['Deal']),($_e['Deal Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Deal']),$lastId,($_e['Deal Add Successfully']));
                }else{
                    $this->db->rollBack();
                    $this->functions->notificationError(_uc($_e['Deal']),($_e['Deal Add Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->functions->notificationError(_uc($_e['Deal']),_js($_e['Deal Add Failed']."<br>".$this->dbF->rowException),'btn-danger');
            }
        } // If end
    }

    public function dealEditSubmit(){
     global $_e;
     
        // if(isset($_POST['submit']) && isset($_POST['editId'])){
        if(isset($_POST['editId'])){
            if(!$this->functions->getFormToken('editDeal')){
                return false;
            }
            
            try{
                $this->db->beginTransaction();
                $lastId = !empty($_POST['editId']) ? $_POST['editId'] : "";

                $image = $this->functions->uploadSingleImage(@$_FILES['image'],'deal');
                // $this->dbF->prnt($_POST);
                
                if(!empty($image)){
                    $_POST['insert']['image'] = $image;
                    if(isset($_POST['image_old']) && !empty($_POST['image_old'])) {
                        $this->functions->deleteOldSingleImage($_POST['image_old']);
                    }
                }


                //category
                $categ = $_POST['categoryy'];
                $category_ch = '';
                foreach ($categ as $key => $value) {
                    @$_POST['insert']['category'] .= $value.',';
                    @$category_ch .= $value.',';
                }

                $_POST['insert']['category'] = rtrim($_POST['insert']['category'],",");
                $category_ch = rtrim($category_ch,",");


  // $sql = "SELECT * FROM product_deal where id = '$id' ";
  //           $data = $this->dbF->getRow($sql);



                //slug
                $slug = $_POST['slug'];
                $ref_id = $this->db->dealProduct.$lastId;
                $check = $this->functions->check_slug_duplicate($slug, $ref_id);

                if($check==0){
            // echo "sssssssss";
            // die;        
                    $slug = $slug."-".rand(1, 15);
                }

                if(!empty($slug)){
                    $_POST['insert']['slug'] = $slug;
                }
                $returnForm = $this->functions->formUpdate('product_deal',$_POST['insert'],$lastId);
                
                
                if($this->dbF->rowException){
                    throw new Exception("Dublicate slug");
                }



                
                
                $sql = "DELETE FROM product_deal_price WHERE deal_id = '$lastId'";
                $this->dbF->setRow($sql);

                //if slug not set, then update to its default id
                if(empty($slug)){
                    $sql = "UPDATE product_deal SET slug = ? WHERE id = ?";
                    $this->dbF->setRow($sql, array($lastId, $lastId));
                       $slug = $lastId;
                }
                
                
                $ref_id = $this->db->dealProduct.$lastId;
                 $check = $this->functions->check_slug_duplicate($slug,$ref_id);
               if($check == 0){
                    $slug = $slug.uniqid();
                }

                $ref_id = $this->db->dealProduct.$lastId;
                $pageLink = "/".$this->db->dealProduct.$slug;

                 $sql1 = " UPDATE `seo` SET `pageLink`= ?, `slug` = ? WHERE `ref_id` = ?";
                $arrayy = array($pageLink,$slug,$ref_id);
                // echo '<br><br>';
                // $this->dbF->prnt($arrayy);
                // echo '<br><br>';
                 $slugReturn = $this->dbF->setRow($sql1,$arrayy);
              

                $sql = "INSERT " . "INTO `product_deal_price`
                        (`deal_id`,`currencyId`,`price`) VALUES ";
                $val_array      = array();
                foreach($_POST['insert']['price'] as $key => $val) {
                        $sql .= " (?,?,?) ,";
                        $val_array[] = $lastId;
                        $val_array[] = $key;
                        $val_array[] = doubleval($val);
                }
                $sql = trim($sql, ",");
                $this->dbF->setRow($sql, $val_array);

                //Insert Setting
                $sql = "DELETE FROM product_deal_setting WHERE deal_id = '$lastId'";
                $this->dbF->setRow($sql);
 
                $sql = "INSERT  INTO `product_deal_setting`
                        (`deal_id`,`setting_name`,`setting_val`) VALUES ";
                $val_array = array();
                $foreach_loop = false;
                // $this->dbF->prnt($_POST['setting']);
                
                foreach ($_POST['setting'] as $key => $val) {
                    if(is_array($val)){
                        $val = serialize($val);
                    }
                    $sql .= " (?,?,?) ,";
                    $val_array[] = $lastId;
                    $val_array[] = $key;
                    $val_array[] = $val; 
                    $foreach_loop = true;
                }
                 $sql = trim($sql, ",");
                // $this->dbF->prnt($val_array); 
                
                if ($foreach_loop) {
                    $settingForm =  $this->dbF->setRow($sql, $val_array);
                     
                }
                
            //   $this->dbF->rowCount
                if($this->dbF->rowCount>0){
                    $this->db->commit();
                    $this->functions->notificationError(_uc($_e['Deal']),($_e['Deal Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Update']),_uc($_e['Deal']),$lastId,($_e['Deal Update Successfully']));
                }else{
                    $this->db->rollBack();
                    $this->functions->notificationError(_uc($_e['Deal']),($_e['Deal Update Failed']),'btn-danger');
                }
            }catch (Exception $e){
                
                $this->db->rollBack();
                $this->functions->notificationError(_uc($_e['Deal']),_js($_e['Deal Update Failed']."<br>".$this->dbF->rowException),'btn-danger');
            }

        }
    }
    
    
    
    public function findArrayFromDealProductSetting($data,$settingName){
        foreach($data as $val){
            if($val['setting_name'] == $settingName){
                return $val['setting_val'];
            }
        }
        return "";
    }

    public function dealNew(){
        global $_e;
        $this->dealEdit(true);
    }

    public function dealEdit($new=false){
        global $_e;
        if($new){
            $token = $this->functions->setFormToken('newDeal',false);
        }else{
            $id = $_GET['pId'];
            $sql = "SELECT * FROM product_deal where id = '$id' ";
            $data = $this->dbF->getRow($sql);

            $sql = "SELECT * FROM product_deal_setting where deal_id = '$id' ";
            $dataSetting = $this->dbF->getRows($sql);

            $token = $this->functions->setFormToken('editDeal', false);
            $token .= '<input type="hidden" name="editId" value="'.$id.'"/>';
        }

        $form_fields    = array();
        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => $token
        );

        $lang   =   $this->functions->IbmsLanguages();

        for ($i = 0; $i < sizeof($lang); $i++) {
            if($i == 0) {
                $collapseIn = ' in ';
            }else {
                $collapseIn = '';
            }

            $form_fields[] = array(
                'type' => 'none',
                'group' => 'name',
                'thisFormat' => '<div class="panel panel-default">
                                  <div class="panel-heading">
                                         <a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
                                            <h4 class="panel-title">
                                                '.$lang[$i].'
                                            </h4>
                                         </a>
                                  </div>
                              <div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
                                <div class="panel-body">'
            );

            @$val       = unserialize($data['name']);
            $form_fields[] = array(
                "label" => $_e['Deal Name'],
                'group' => 'name',
                "name"  => "insert[name][".$lang[$i]."]",
                'class' => 'form-control',
                'type'  => 'text',
                'value' => @$val[$lang[$i]]
            );

            @$val       = unserialize($this->findArrayFromDealProductSetting($dataSetting,'sDesc'));
            $form_fields[] = array(
                "label" => $_e['Short Description'],
                'group' => 'name',
                "name"  => "setting[sDesc][".$lang[$i]."]",
                'class' => 'form-control ckeditor',
                'type'  => 'textarea',
                'value' => @$val[$lang[$i]]
            );

            $form_fields[] = array(
                'type' => 'none',
                'group' => 'name',
                'thisFormat' => '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>'
            );
        }

        $form_fields[] = array(
            'type' => 'group',
            'name' => 'name',
            'group' => 'basic',
            'thisFormat' => '<div class="panel-group" id="accordion">{{form}}</div><!--#accordion-->'
        );


        //Price
        $countryCodeList    = $this->functions->countrylist(); // country list
        $currency_data      = $this->c_currency->getList(); // get currency list
        $tds    = "<td></td>";
        $tds2   = "<td></td>";
        @$val2       =  unserialize($data['price']);
        foreach ($currency_data as $val) {
            $country_id     = $val['cur_id'];
            $symbol         = $val['cur_symbol'];
            $country_name   = $countryCodeList[$val['cur_country']];
            $currency       = $val["cur_name"];
            @$oldPrice      = $val2[$country_id];
            $tds .= "<td>$country_name ($currency)</td>";
            $tds2 .= '<td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon">'.$symbol.'</span>
                          <input type="text" class="form-control" value="'.$oldPrice.'" name="insert[price]['.$country_id.']" >
                        </div>
                      </td> ';
        }

        $form_fields[] = array(
            'type' => 'none',
            'group' => 'price',
            'thisFormat' => "<table class='table table-striped table-hover'><tr>$tds</tr><tr>$tds2</tr></table> <hr>"
        );


        @$val       =   $data['image'];
        if(!empty($val)){
            $val = WEB_URL."/images/".$val;
        }
        $form_fields[]  = array(
            "label" => $_e['Image'],
            "name"  => "image",
            'class' => 'form-control',
            'type'  => 'image',
            'image' => "$val",
            'format' => "{{image}} {{form}}",
            'group' => 'basic',
        );


        $sql            = "SELECT `prodet_id`,`prodet_name` FROM proudct_detail WHERE `product_update` = '1'";
        $dataProducts   = $this->dbF->getRows($sql);
        $options = array();
        $values = array();
        foreach($dataProducts as $val){
            $values[] = $val['prodet_id'];
            $options[] = $this->functions->unserializeTranslate($val['prodet_name']);
        }
       @$val   =  ($data['multi_product']);
       @$multi_product =  ($data['multi_product']);
       $display = $multi_product == 0 ? "none" : "";
        $form_fields[]  = array(
            "label" => $_e['Multiple Products'],
            'type'  => 'checkbox',
            'value' => "$val",
            'select' => "1",
            'format' => '<div class="make-switch show_product" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Yes']) .'" data-off-label="'. _uc($_e['No']) .'">
                            {{form}}
                            <input type="hidden" id="multiple_product" name="insert[multi_product]" class="checkboxHidden" value="'.$val.'" />
                         </div>',
            'group' => 'basic',
        );
        
        
        // single product
        // @$val       = $data['option1Head'];
        //     $form_fields[] = array(
        //         "label" => $_e['Option 1 Heading'],
        //         "name"  => "insert[option1Head]",
        //         'class' => 'form-control',
        //         'type'  => 'text',
        //         'value' => $val
        //     );
            
        @$val       =  $data['package_heading'];
        $form_fields[] = array(
            "label" => $_e['Option 1 Heading'],
            "name"  => "insert[package_heading]",
            'class' => 'form-control',
            'type'  => 'text',
            'value' => $val,
            'group' => 'basic',
        );
        
        @$val       =   unserialize($data['package']);
        $form_fields[]  = array(
            "label" => $_e['option 1 Products'],
            "name"  => 'insert[package][]',
            'class' => 'form-control newDealProduct option1',
            'type'  => 'select',
            'select' => $val,
            'option' => $options,
            'value' => $values,
            'multi'  => 'true',
            'data' => 'style="height: 330px;"',
            'group' => 'basic',
        );   
        
        @$val       =  $data['option2Head'];
        $form_fields[] = array(
            "label" => $_e['Option 2 Heading'],
            "name"  => "insert[option2Head]",
            'class' => 'form-control multiproduct '.$display,
            'type'  => 'text',
            'value' => $val,
            'group' => 'basic',
        );
        @$val       =  unserialize($data['option2Pro']);
        $form_fields[]  = array(
            "label" => $_e['option 2 Products'],
            "name"  => 'insert[option2Pro][]',
            'class' => 'form-control newDealProduct multiproduct option2 '.$display,
            'type'  => 'select',
            'select' => $val,
            'option' => $options,
            'value' => $values,
            'multi'  => 'true',
            'data' => 'style="height: 330px;"',
            'group' => 'basic',
        ); 
        
        @$val       =  $data['option3Head'];
        $form_fields[] = array(
            "label" => $_e['Option 3 Heading'],
            "name"  => "insert[option3Head]",
            'class' => 'form-control multiproduct '.$display,
            'type'  => 'text',
            'value' => $val,
            'group' => 'basic',
        );
        @$val       =  unserialize($data['option3Pro']);
        $form_fields[]  = array(
            "label" => $_e['option 3 Products'],
            "name"  => 'insert[option3Pro][]',
            'class' => 'form-control newDealProduct multiproduct '.$display,
            'type'  => 'select', 
            'select' => $val,
            'option' => $options,
            'value' => $values,
            'multi'  => 'true',
            'data' => 'style="height: 330px;"',
            'group' => 'basic',
        ); 
        
        @$val       =  $data['option4Head'];
        $form_fields[] = array(
            "label" => $_e['Option 4 Heading'],
            "name"  => "insert[option4Head]",
            'class' => 'form-control multiproduct '.$display,
            'type'  => 'text',
            'value' => $val,
            'group' => 'basic',
        );
        @$val       =  unserialize($data['option4Pro']);
        $form_fields[]  = array(
            "label" => $_e['option 4 Products'],
            "name"  => 'insert[option4Pro][]',
            'class' => 'form-control newDealProduct multiproduct '.$display,
            'type'  => 'select', 
            'select' => $val,
            'option' => $options,
            'value' => $values,
            'multi'  => 'true',
            'data' => 'style="height: 330px;"',
            'group' => 'basic',
        ); 
        
        @$val       =  $data['option5Head'];
        $form_fields[] = array(
            "label" => $_e['Option 5 Heading'],
            "name"  => "insert[option5Head]",
            'class' => 'form-control multiproduct '.$display,
            'type'  => 'text',
            'value' => $val,
            'group' => 'basic',
        );
        @$val       =  unserialize($data['option5Pro']);
        $form_fields[]  = array(
            "label" => $_e['option 5 Products'],
            "name"  => 'insert[option5Pro][]',
            'class' => 'form-control newDealProduct multiproduct '.$display,
            'type'  => 'select', 
            'select' => $val,
            'option' => $options,
            'value' => $values,
            'multi'  => 'true',
            'data' => 'style="height: 330px;"',
            'group' => 'basic',
        ); 
        
          
        
        
        
        
        
        // end Single product


        @$val       = ($data['slug']);
        $form_fields[] = array(
            "label" => $_e['Slug'],
            "name"  => "slug",
            'class' => 'form-control',
            'type'  => 'text',
            'pattern' => '[A-Za-z0-9-_+]{1,150}',
            'value' => $val,
            'group' => 'basic',
        );


        @$val       =   ($data['view_type']);
        $form_fields[]  = array(
            "label" => $_e['Deal Box Size'],
            'type'  => 'checkbox',
            'value' => "$val",
            'select' => "1",
            'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Big']) .'" data-off-label="'. _uc($_e['Small']) .'">
                            {{form}}
                            <input type="hidden" name="insert[view_type]" class="checkboxHidden" value="'.$val.'" />
                         </div>',
            'group' => 'basic',
        );


        @$val       =   $data['publish'];
        $form_fields[]  = array(
            "label" => $_e['Publish'],
            'type'  => 'checkbox',
            'value' => "$val",
            'select' => "1",
            'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                            {{form}}
                            <input type="hidden" name="insert[publish]" class="checkboxHidden" value="'.$val.'" />
                         </div>',
            'group' => 'basic',
        );


        $form_fields[] = array(
            'type'  => 'group',
            'name'  => 'basic',
            'group' => 'tabsContent',
            'thisFormat' => '<div class="tab-pane fade in active container-fluid" id="basicInfo">
                                <h2  class="tab_heading">'._uc($_e['Basic Information']).'</h2>
                                {{form}}
                             </div>',
        );

        $form_fields[] = array(
            'type'  => 'group',
            'name'  => 'price',
            'group' => 'tabsContent',
            'thisFormat' => '<div class="tab-pane fade in container-fluid" id="price">
                                <h2  class="tab_heading">'._uc($_e['Price']).'</h2>
                                {{form}}
                             </div>',
        );

        $selectedNode="";
        @$selectedNode       =   ($data['category']);
        $count_cat = explode(',', $selectedNode ?? '');
        $cnt = count($count_cat);
        $linkCategory = WEB_URL.'/'.ADMIN_FOLDER.'/product_management/?operation=get_node';
        $category = <<<HTML
            <script type="text/javascript">
                $(function () {
                    $("#tree").jstree({
                        'core': {
                            'data': {
                                'url': '$linkCategory',
                                'data': function (node) {
                                    return { 'id': node.id };
                                }
                            }
                        },
                        "plugins": [ "wholerow", "checkbox","ui" ]
                    })
                        .on('loaded.jstree', function () {
                            $("#tree").jstree('open_all');
                        }).on('open_all.jstree', function () {

                            $('#tree').jstree(true).select_node([$selectedNode]);
                        })
                        .on('changed.jstree', function (e, data) {
                            if (data && data.selected && data.selected.length) {
                                $('.category_make_root').val(data.selected);
                            } else {
                                $('.category_make_root').val('0');
                            }
                        });
                });
            </script>
            <div id="tree"></div>
            <div>
                <!-- <input type="hidden" class="category_make_root" name="insert[category]"> -->
            </div>
            <br><br>
HTML;



    $category1 = '<ul id="nestedlist">';

        ##### Main MENU
        $css = false;
        $view_css= '';
        $mainMenu = $this->menuTypeSingle('main');
        foreach ($mainMenu as $val) {
        $insideActive = false;
        $innerUl = '';
        $menuId = $val['id'];
        $text = _n($val['name']);
        $link = $val['link'];

        // $underid = $val['under'];
        $has_inner_level_two_class = '';
        $inner_level_two = null;
        $mainMenu2 = $this->menuTypeSingle('main', $menuId);
        if (!empty($mainMenu2)) {
        $has_inner_level_two_class = 'has-sub';
        $inner_level_two = true;

        $innerUl .= '

        <ul>
        ';
        foreach ($mainMenu2 as $val2) {
        $innerUl3 = '';
        $text = _n($val2['name']);
        $menuId2 = $val2['id'];
        $link = $val2['link'];
        $menuIcon = '';
        $active = $val2['active'];


        // $underid = $val2['under'];

        if ($active == '1') {
        $active = 'active';
        $insideActive = $css = true;
        }

        $has_inner_level_three_class = '';

        $mainMenu3 = $this->menuTypeSingle('main', $menuId2);
        # count the inner level 3 lis
        $innerUl3count = ( $mainMenu3 == false ? 0 : count($mainMenu3) ) ;
        $innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';

        if ( $innerUl3count > 0) {

        foreach ($mainMenu3 as $val3) {
        $view_css3 = '';
        $text3       = _n($val3['name']);
        $menuId3     = $val3['id'];
        $link3       = $val3['link'];
        $menuIcon3   = $val3['icon'];
        $active3     = $val3['active'];
        if ($active3 == '1') {
        $active3 = 'active';
        $insideActiveThree = true;
        }


        $has_inner_level_three_class = 'has-sub';

        $innerUl3 .= '

        <li><input type="checkbox" name="categoryy[]" value='.$menuId3.'>
        '. $text3 . '

        </li>


        ';

        }

        }

        $innerUl3 .= ( $innerUl3count > 0 ) ? '</ul><!--3rd array End-->' : '';

        // $innerUl3 .= "</ul><!--3rd array End-->";
        if ($innerUl3) {

        // var_dump($menuId);

        $image_div = '';

        } else {
        $image_div = '';
        }

        $innerUl .= '

        <li><input type="checkbox" name="categoryy[]" value='.$menuId2.'>

        ' . $text . '

        <span>

        '.$image_div.'

        </span>' . $innerUl3 . '

        </li>
        ';
        }

        $innerUl .= "</ul><!--2nd array End-->";
        }

        $text = _n($val['name']);

        $link = $val['link'];
        $menuIcon = $val['icon'];
        if (!empty($menuIcon)) {
        $image_div = '<img src="' . $menuIcon . '" alt="">';
        } else {
        $image_div = '';
        }
        $active = $val['active'];

        if ($active == '1' || $insideActive) {

        if (!empty($mainMenu2)) {
        $css = true;
        }
        $active = 'active';
        }

        $category1 .= '
        <li><input type="checkbox" name="categoryy[]" value='.$menuId.'>

        ' . $text . '



        ' . $innerUl . '

        </li>
        ';
        }

    $category1 .= '</ul>';





        $form_fields[] = array(
            'type'  => 'none',
            'group' => 'tabsContent',
            'thisFormat' => '<div class="tab-pane fade in container-fluid" id="category">
                                <h2  class="tab_heading">'._uc($_e['Category']).'</h2>
                                '.$category1.'
                             </div>',
        );

        $form_fields[] = array(
            'type' => 'group',
            'name' => 'tabsContent',
            'thisFormat' => '<ul class="nav nav-tabs tabs_arrow" role="tablist">
                                <li class="active"><a href="#basicInfo" role="tab" data-toggle="tab">'._uc($_e['Basic Information']).'</a></li>
                                <li><a href="#price" role="tab" data-toggle="tab">'._uc($_e['Price']).'</a></li>
                                <li><a href="#category" role="tab" data-toggle="tab">'._uc($_e['Category']).'</a></li>
                             </ul>

                             <!-- Tab panes -->
                             <div class="tab-content">
                             {{form}}
                             </div>
                            ',
        );


        $form_fields[]  = array(
            "label" => "submit",
            "name"  => 'formSubmit',
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'thisFormat' => '<div class="form-group container-fluid">
                                {{form}}
                              </div>'
        );

        $form_fields['form']  = array(
            'name'      => "form",
            'type'      => 'form',
            'class'     => "",
            'onsubmit'  => "jwaf",
            'id'        => "formId",
            'action'   => '-'.$this->functions->getLinkFolder().'?page=deal',
            'method'   => 'post',
            'format'   => '<div class="form-horizontal">{{form}}</div>'
        );

        $format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';
        //$format = false;
        $this->functions->print_form($form_fields,$format);


        for ($i=0; $i < $cnt; $i++) { 
            $cat_idd = $count_cat[$i];
            ?>

            <script>
                var p_idd = '<?php echo $cat_idd; ?>';
                console.log(p_idd);
                $(':checkbox[value="' + p_idd + '"]').prop('checked', true);
            </script>

        <?php
        }
    }

    public function dealArray($data,$filedName,$settingName){
        foreach($data as $key=>$val){
            if($val['fieldName'] == $filedName && $val['setting_name'] == $settingName)
                return $val['setting_value'];
        }

        return "";
    }

    public function dealFields(){
        global $_e;
        $pId        =   $_GET['pId'];

        $sql    = "SELECT * FROM p_custom WHERE id = '$pId'";
        $data   = $this->dbF->getRow($sql);
        $fields = $data['custom_fields'];
        $fields = str_replace(",,","",$fields);
        $fields = trim($fields,",");
        $fields = explode(",",$fields);

        $sql          = "SELECT * FROM p_custom_setting WHERE c_id = '$pId'";
        $dataFields   = $this->dbF->getRows($sql);

        $token       = $this->functions->setFormToken('dealFields',false);
        $token .= '<input type="hidden" name="editId" value="'.$pId.'"/>';

        $format     = '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                            <div class="col-sm-10  col-md-9">
                                {{form}}
                            </div>
                        </div>';

        echo "<form class='form-horizontal' action='-deal?page=deal' method='post'> $token.";

        echo '<div class="form-horizontal">';
            $lang = $this->functions->IbmsLanguages();
            if($lang != false){
                $lang_nonArray = implode(',', $lang);
            }
            echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

        echo '<div class="accordion1">';

        foreach($fields as $key=>$val) {
            echo '<h3>'.$val.'</h3>';
            echo '<div class="accordion">';

            for ($i = 0; $i < sizeof($lang); $i++) {
                echo '
                        <h3>'.$lang[$i].'</h3>
                          <div class="insideAccordion">';
                                $form_fields = array();

                                $valTemp = $this->functions->unserializeTranslate($this->dealArray($dataFields,$val,'name'),$lang[$i]);
                                $form_fields[] = array(
                                    'label'     => $_e["Field Name"],
                                    'name'      => "$val"."[name]["."$lang[$i]]",
                                    'type'      => 'text',
                                    'class'     => 'form-control',
                                    'value'     => "$valTemp"
                                );


                                $valTemp = $this->functions->unserializeTranslate($this->dealArray($dataFields,$val,'desc'),$lang[$i]);
                                $form_fields[] = array(
                                    'label'     => $_e["Field Desc"],
                                    'name'      => "$val"."[desc]["."$lang[$i]]",
                                    'type'      => 'textarea',
                                    'class'     => 'form-control ckeditor',
                                    'value'     => "$valTemp"
                                );

                                $valFormTemp   =   $this->functions->unserializeTranslate($this->dealArray($dataFields,$val,'required'),$lang[$i]);
                                if(empty($valFormTemp)){
                                    $valFormTemp = '0';
                                }
                                $name = $val.'[required]['.$lang[$i].']';
                                $form_fields[]  = array(
                                    "label" => $_e['Required'],
                                    'type'  => 'checkbox',
                                    'value' => "$valFormTemp",
                                    'select' => "$valFormTemp",
                                    'format' => '<div class="make-switch" data-off="danger" data-on="success">
                                                    {{form}}
                                                  <input type="hidden" name="'.$name.'" class="checkboxHidden" value="'.$valFormTemp.'" />
                                                 </div>'
                                );

                                $this->functions->print_form($form_fields,$format);

                echo "    </div><!--.insideAccordion End-->
                        ";
            }

            echo " </div><!--.accordion-->";
        }

        echo " </div><!--.accordion1-->";
        echo '<script>
                      $(function() {
                        $( ".accordion,.accordion1 " ).accordion({
                              heightStyle: "content",
                               collapsible: true
                            });
                      });
                            </script>';

        $form_fields = array();
        $form_fields[] = array(
            'label'     => "",
            'name'      => "submit",
            'type'      => 'submit',
            'class'     => 'btn btn-primary',
            'format'   => '<br><br>{{form}}'
        );


        $this->functions->print_form($form_fields,$format);




        echo "</div></form>";


    }

        //////////////// Main menu function ///////////////////////////////////////////

    public function menuTypeSingle($type='main',$under='0',$url_function=false){
        global $_e;
        $sql = "SELECT * FROM categories WHERE under = '$under' AND type='$type' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            if ($url_function) {
                $link = $this->functions->addCatRegexWebUrlInLink($link);
            } else {
                $link = $this->functions->addWebUrlInLink($link);
            }

            $icon = $val['icon'];
            $icon = $this->functions->addWebUrlInLink($icon);

            $array["$id"]['name']   = $heading;
            $array["$id"]['link']   = $link;
            $array["$id"]['icon']   = $icon;
            $array["$id"]['id']     = $id;
            $array["$id"]['active']= '';
            $activeLink = pageLink(false);
            if( ($activeLink) == ($link)){
                $array["$id"]['active']= '1';
            }
        }

        return $array;
    }


}


?>



<style>

#nestedlist, #nestedlist ul {
  list-style-type: none;
  margin-left:0;
  padding-left:30px;
  text-indent: -4px;
}

/* UL Layer 1 Rules */
#nestedlist {
  /*font-size: 20px;*/
  font-weight:bold;
}

/* UL Layer 2 Rules */
#nestedlist ul {
  /*font-size: 18px;*/
  font-weight: normal;
  margin-top: 3px;
}

/* UL Layer 3 Rules */
#nestedlist ul ul {
  font-size: 16px;
}

/* UL 4 Rules */
#nestedlist ul ul ul {
  font-size: 14px;
}


/*ul li a {
  text-decoration: none;
  border: 1px solid #000;
  border-width: 0 0 1px 1px;
  border-radius: 0 0 0 10px;
}*/  
    </style>