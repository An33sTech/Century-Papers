<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class measurement extends object_class{
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
        $_w['Measurement Management'] = '' ;
        //measurementEdit.php
        $_w['Manage Measurement'] = '' ;

        //measurement.php
        $_w['Active Measurement'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Sort Measurement'] = '' ;
        $_w['Add New Measurement'] = '' ;
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
        $_w['Measurement'] = '' ;
        $_w['Added'] = '' ;
        $_w['Measurement Add Successfully'] = '' ;
        $_w['Measurement Add Failed'] = '' ;
        $_w['Measurement Update Failed'] = '' ;
        $_w['Measurement Update Successfully'] = '' ;
        $_w['Update'] = '' ;
        $_w['Measurement Title'] = '' ;
        $_w['Measurement Link'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Image Recommended Size : {{size}}'] = '' ;
        $_w['Publish'] = '' ;
        $_w['Layer'] = '' ;

        $_w['SAVE'] = '' ;
        $_w['Allow Submit Later'] = '' ;
        $_w['Designation'] = '' ;
        $_w['Email'] = '' ;
        $_w['Date'] = '' ;
        $_w['Old File Image'] = '' ;
        $_w['Fileds'] = '' ;
        $_w['Edit Form Fields'] = '' ;
        $_w['GroupName'] = '' ;
        $_w['Fields Name: separate with comma'] = '' ;
        $_w['This is only For admin to manage or sort fields'] = '' ;
        $_w['Field Name'] = '' ;
        $_w['Field Desc'] = '' ;
        $_w['Required'] = '' ;
        $_w['Fileds'] = '' ;
        $_w['Yes'] = '' ;
        $_w['No'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Measurement');

    }

    public function measurementSort(){
        echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
        $sql ="SELECT measurement_heading,measurement_image,id FROM `measurement` WHERE publish = '1' ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $id = $val['id'];
            @$measurement_image    =   unserialize($val['measurement_image']);
            @$image    =  $this->functions->addWebUrlInLink($measurement_image[$defaultLang]);
            @$title = unserialize($val['measurement_heading']);
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

    public function measurementView(){
        $sql  = "SELECT * FROM p_custom WHERE publish='1' ORDER BY id DESC";
        $data =  $this->dbF->getRows($sql);
        $this->measurementPrint($data);
    }

    public function measurementDraft(){
        $sql  = "SELECT * FROM p_custom WHERE publish='0' ORDER BY id DESC";
        $data =  $this->dbF->getRows($sql);
        $this->measurementPrint($data);
    }

    public function measurementPrint($data){
        global $_e;

        $class="dTable tableIBMS";
            echo '<div class="table-responsive">
                <table class="table table-hover '.$class.'">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>';
            echo        '<th>'. _u($_e['GroupName']) .'</th>';
        echo            '<th>'. _u($_e['Fileds']) .'</th>
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
                $custom_type = ($val['custom_type']);
                echo "<td>$custom_type</td>";

            $custom_fields    = ($val['custom_fields']);
            $custom_fields    = str_replace(",,","",$custom_fields);
            $custom_fields      = trim($custom_fields,",");
            $custom_fields    = str_replace(",","<br>",$custom_fields);

            echo "
                    <td>$custom_fields</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-measurement?page=edit&pId=$id&view=edit' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' href='-measurement?page=edit&pId=$id&view=fields' class='btn' title='".$_e['Edit Form Fields']."'>
                                <i class='glyphicon glyphicon-list-alt'></i>
                            </a>
                            <a data-id='$id' onclick='deleteMeasurement(this);' class='btn'>
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

    public function newMeasurementAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newMeasurement')){return false;}

            try{
                $this->db->beginTransaction();

                    $_POST['insert']['custom_fields'] = str_replace(", ","",$_POST['insert']['custom_fields']);
                    $_POST['insert']['custom_fields'] = str_replace(" ","_",$_POST['insert']['custom_fields']);
                    $lastId = $this->functions->formInsert('p_custom',$_POST['insert']);
                if($lastId == false){
                    throw new Exception("Add Fail");
                }
                $this->db->commit();

                if($lastId != false){
                    $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Add Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Measurement']),$lastId,($_e['Measurement Add Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Add Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Add Failed']),'btn-danger');
            }
        } // If end
    }



    public function measurementFieldsSubmit(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('measurementFields')){return false;}

            try{
                $this->db->beginTransaction();

                $lastId = !empty($_POST['editId']) ? $_POST['editId'] : "";

                $sql    = "SELECT * FROM p_custom WHERE id = '$lastId'";
                $data   = $this->dbF->getRow($sql);
                $fields = $data['custom_fields'];
                $fields = str_replace(",,","",$fields);
                $fields = trim($fields,",");
                $fields = explode(",",$fields);

                $sql    =   "DELETE FROM p_custom_setting WHERE c_id = '$lastId' ";
                $this->dbF->setRow($sql);
                $sqlFields = "";

                $sql    =   "INSERT INTO p_custom_setting(c_id,fieldName,setting_name,setting_value) VALUES ";

                $sqlArray = array();
                $isFields = false;
                foreach($fields as $key=>$val){
                    if(isset($_POST[$val])){
                        $fieldPost  = $_POST[$val];

                        foreach($fieldPost as $key2=>$val2){
                            $isFields = true;
                            $sql .= "(?,?,?,?),";
                            $sqlArray[] = $lastId;
                            $sqlArray[] = $val;
                            $sqlArray[] = $key2;

                            if($key2=='image'){
                                $sqlArray[] = $this->functions->removeWebUrlFromLink($val2);
                            }else {
                                $sqlArray[] = serialize($val2);
                            }
                        }

                    }
                }
                $sql = trim($sql,",");
                if($isFields) {
                    $this->dbF->setRow($sql, $sqlArray);
                    if($this->dbF->rowCount>0){
                        $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Update Successfully']),'btn-success');
                        $this->functions->setlog(_uc($_e['Update']),_uc($_e['Measurement']),$lastId,($_e['Measurement Update Successfully']));
                    }else{
                        $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Update Failed']),'btn-danger');
                    }
                }else{
                    $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Update Failed']),'btn-danger');
                }

                $this->db->commit();
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
            }

        }
    }

    public function measurementEditSubmit(){

        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('editMeasurement')){return false;}

            try{
                $this->db->beginTransaction();

                $_POST['insert']['custom_fields'] = str_replace(", ","",$_POST['insert']['custom_fields']);
                $_POST['insert']['custom_fields'] = str_replace(" ","_",$_POST['insert']['custom_fields']);
                $lastId = !empty($_POST['editId']) ? $_POST['editId'] : "";
                $returnForm = $this->functions->formUpdate('p_custom',$_POST['insert'],$lastId);
                if($returnForm == false){
                    throw new Exception("Add Fail");
                }

                $this->db->commit();
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Update Successfully']),'btn-success');
                    $this->functions->setlog(_uc($_e['Update']),_uc($_e['Measurement']),$lastId,($_e['Measurement Update Successfully']));
                }else{
                    $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Update Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Measurement']),($_e['Measurement Update Failed']),'btn-danger');
            }

        }
    }

    public function measurementNew(){
        global $_e;
        $this->measurementEdit(true);
    }

    public function measurementEdit($new=false){
        global $_e;
        if($new){
            $token       = $this->functions->setFormToken('newMeasurement',false);
        }else {
            $id = $_GET['pId'];
            $sql = "SELECT * FROM p_custom where id = '$id' ";
            $data = $this->dbF->getRow($sql);

            $token = $this->functions->setFormToken('editMeasurement', false);
            $token .= '<input type="hidden" name="editId" value="'.$id.'"/>';
        }

        @$measurement_type      = ($data['custom_type']);
        @$measurement_fields    =  ($data['custom_fields']);
        @$publish               = ($data['publish']);

        echo '<form method="post" action="-'.$this->functions->getLinkFolder().'?page=measurement" class="form-horizontal" role="form" enctype="multipart/form-data">'.
                $token;

        $form_fields    = array();
        $val = $measurement_type;
        $form_fields[]  = array(
            "label" => $_e['GroupName'],
            "name"  => "insert[custom_type]",
            'class' => 'form-control',
            'type'  => 'text',
            'value' => "$val"
        );

        $val = $measurement_fields;
        $form_fields[]  = array(
            "label" => $_e['Fields Name: separate with comma']."<br><small>".$_e['This is only For admin to manage or sort fields']."</small>",
            "name"  => "insert[custom_fields]",
            'class' => 'form-control',
            'type'  => 'text',
            'value' => "$val"
        );

        $val    =   $publish;
        $form_fields[]  = array(
            "label" => $_e['Publish'],
            'type'  => 'checkbox',
            'value' => "$val",
            'select' => "$val",
            'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                            {{form}}
                            <input type="hidden" name="insert[publish]" class="checkboxHidden" value="'.$val.'" />
                         </div>'
        );

        $form_fields[]  = array(
            "label" => "submit",
            "name"  => 'submit',
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'thisFormat' => '<div class="form-group container-fluid">
                                {{form}}
                              </div>'
        );

        $format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';
        //$format = false;
        $this->functions->print_form($form_fields,$format);
        echo "</form>";
    }

    public function measurementArray($data,$filedName,$settingName){
        foreach($data as $key=>$val){
            if($val['fieldName'] == $filedName && $val['setting_name'] == $settingName)
                return $val['setting_value'];
        }

        return "";
    }

    public function measurementFields(){
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

        $token       = $this->functions->setFormToken('measurementFields',false);
        $token .= '<input type="hidden" name="editId" value="'.$pId.'"/>';

        $format     = '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                            <div class="col-sm-10  col-md-9">
                                {{form}}
                            </div>
                        </div>';

        echo "<form class='form-horizontal' action='-measurement?page=measurement' method='post'> $token.";

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

                                $valTemp = $this->functions->unserializeTranslate($this->measurementArray($dataFields,$val,'name'),$lang[$i]);
                                $form_fields[] = array(
                                    'label'     => $_e["Field Name"],
                                    'name'      => "$val"."[name]["."$lang[$i]]",
                                    'type'      => 'text',
                                    'class'     => 'form-control',
                                    'value'     => "$valTemp"
                                );


                                $valTemp = $this->functions->unserializeTranslate($this->measurementArray($dataFields,$val,'desc'),$lang[$i]);
                                $form_fields[] = array(
                                    'label'     => $_e["Field Desc"],
                                    'name'      => "$val"."[desc]["."$lang[$i]]",
                                    'type'      => 'textarea',
                                    'class'     => 'form-control ckeditor',
                                    'value'     => "$valTemp"
                                );

                                $valFormTemp   =   $this->functions->unserializeTranslate($this->measurementArray($dataFields,$val,'required'),$lang[$i]);
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

        echo "<br><hr>";

        $form_fields = array();

        foreach($fields as $key=>$val) {
            $valTemp        = $this->functions->addWebUrlInLink($this->measurementArray($dataFields,$val,'image'));
            $form_fields[]  = array(
                'label'     => $val." Image",
                'name'      => "$val"."[image]",
                'type'      => 'text',
                'class'     => 'form-control '.$val.'_image',
                'value'     => "$valTemp",
                'format' => '<div>
                                    <img src="'.$valTemp.'" class="'.$val.'_image kcFinderImage"/>
                                    <div class="input-group">
                                        {{form}}
                                        <div class="input-group-addon pointer"
                                            onclick="openKCFinderImageWithImg(\''.$val.'_image\')"><i class="glyphicon glyphicon-picture"></i></div>
                                    </div>
                             </div>'
            );

        }


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


}
?>