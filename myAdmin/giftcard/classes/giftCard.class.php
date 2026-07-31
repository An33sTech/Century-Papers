<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class giftCard extends object_class{
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
        //index
        $_w['Gift Card Management'] = '' ;
        //giftcard.php
        $_w['Manage Gift Card'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Update Fail Please Try Again.'] = '' ;
        $_w['Are You Sure You Want TO Update?'] = '' ;
        $_w['Sold Gift Card'] = '' ;
        $_w['Add New Gift Card'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['Active Gift Card'] = '' ;

        //This Class
        $_w['SNO'] = '' ;
        $_w['PAGE'] = '' ;
        $_w['TITLE'] = '' ;
        $_w['UPDATE'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Gift Card'] = '' ;
        $_w['Added'] = '' ;
        $_w['Gift Card Save Successfully'] = '' ;
        $_w['Gift Card Save Failed,Please Enter Correct Values'] = '' ;
        $_w['Gift Name'] = '' ;
        $_w['How many same gift cards add?All will generate unique id'] = '' ;

        $_w['Yes'] = '' ;
        $_w['Info'] = '' ;
        $_w['Currency'] = '' ;
        $_w['Price'] = '' ;
        $_w['NO'] = '' ;
        $_w['Publish'] = '' ;
        $_w['SAVE'] = '' ;
        $_w['Add Gift Card'] = '' ;
        $_w['CARD ID'] = '' ;
        $_w['Sale Gift Card'] = '' ;
        $_w['Use Price'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Gift Card');

    }

    public function giftCardQuickLink($id,$link){
        global $_e;
        $temp = "<a data-id='$id' title='{$_e['Add Gift Card']}' href='-giftcard?page=edit&quickLink=$link#newPage' target='_blank' class='btn'>
                     <i class='glyphicon glyphicon glyphicon-globe'></i>
                 </a>";
        return $temp;
    }

    private function printGiftCardViewTable($data,$view='view'){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTableFull tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['CARD ID']) .'</th>
                        <th>'. _u($_e['Gift Name']) .'</th>
                        <th>'. _u($_e['Price']) .'</th>
                        <th>'. _u($_e['Use Price']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $i = 0;
        foreach($data as $val){
            $i++;
            $id     = $val['id'];
            $title  = $this->functions->unserializeTranslate($val['name']);
            $giftId = $val['giftId'];

            //break in 3 parts, for space
            $giftId1 = substr($giftId,0,4);
            $giftId2 = substr($giftId,4,4);
            $giftId3 = substr($giftId,8,4);

            $saleClass = 'glyphicon-thumbs-up';
            $sale    = '1';
            if($view == 'sale'){
                $saleClass = 'glyphicon-thumbs-down';
                $sale = '0';
            }
            $saleDiv = '';
            if($view !='draft'){
                $saleDiv = "<a data-id='$id' data-val='$sale' onclick='saleGiftCard(this);' class='btn' title='".$_e['Sale Gift Card']."'>
                                <i class='glyphicon $saleClass trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }
            echo "<tr>
                    <td>$i</td>
                    <td>$giftId1$giftId2$giftId3</td>
                    <td>$title</td>
                    <td>$val[price] $val[currency]</td>
                     <td>$val[usePrice] $val[currency]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>

                            $saleDiv

                            <a data-id='$id' href='-giftcard?page=edit&giftCardId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteGiftCard(this);' class='btn'>
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

    public function giftCardView(){
        $sql  = "SELECT id, giftId,`name`,price,currency,usePrice FROM gift_card WHERE publish = '1' AND sale ='0'";
        $data =  $this->dbF->getRows($sql);
        $this->printGiftCardViewTable($data,'view');
    }


    public function giftCardSaleView(){
        $sql  = "SELECT id, giftId,`name`,price,currency,usePrice FROM gift_card WHERE publish = '1' AND sale ='1'";
        $data =  $this->dbF->getRows($sql);
        $this->printGiftCardViewTable($data,'sale');
    }

    public function giftCardDraft(){
        $sql  = "SELECT id, giftId,`name`,price,currency,usePrice FROM gift_card WHERE publish = '0'";
        $data =  $this->dbF->getRows($sql);
        $this->printGiftCardViewTable($data,'draft');
    }

    public function newGiftCardAdd(){

        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newGiftCard')){return false;}
                global $_e;

                @$loop   = intval($_POST['loop']);
                if(empty($loop)){
                    $loop = 1;
                }
                if($loop>='500'){
                    $loop = 500;
                }

                for($i=1;$i<=$loop;$i++) {
                    //generate Gift id
                    $giftId = $this->functions->genRandomString('12');
                    $_POST['insert']['giftId'] = $giftId;
                    $lastId = $this->functions->formInsert('gift_card', $_POST['insert']);
                }

                if($lastId>0) {
                    $this->functions->notificationError(_js(_uc($_e['Gift Card'])), _js(_uc($_e['Gift Card Save Successfully'])), 'btn-success');
                    $this->functions->setlog(_uc($_e['Added']), _uc($_e['Gift Card']), $lastId, _uc($_e['Gift Card Save Successfully']));
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Gift Card'])),_js(_uc($_e['Gift Card Save Failed,Please Enter Correct Values'])),'btn-danger');
                }
        } // If end
    }

    public function giftCardEditSubmit(){

        if(isset($_POST['submit'])  && isset($_POST['editId'])){
             if(!$this->functions->getFormToken('editGiftCard')){return false;}

            global $_e;
            $lastId         =   $_POST['editId'];
            $return         =   $this->functions->formUpdate('gift_card',$_POST['insert'],$lastId);

                if($return) {
                    $this->functions->notificationError(_js(_uc($_e['Gift Card'])), _js(_uc($_e['Gift Card Save Successfully'])), 'btn-success');
                    $this->functions->setlog(_uc($_e['UPDATE']), _uc($_e['Gift Card']), $lastId, _uc($_e['Gift Card Save Successfully']));
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Gift Card'])),_js(_uc($_e['Gift Card Save Failed,Please Enter Correct Values'])),'btn-danger');
                }
        }
    }

    public function newGiftCard(){
        $this->giftCardEdit(true);
        return false;
    }

    public function giftCardEdit($new = false){
        global $_e;
        $isEdit     =   false;
        $quickAdd   =   false;

        if($new){
            $token       = $this->functions->setFormToken('newGiftCard',false);
        }else {
            $isEdit = true;
            $token = $this->functions->setFormToken('editGiftCard', false);
            $id = $_GET['giftCardId'];
            $sql = "SELECT * FROM gift_card where id = '$id' ";
            $data = $this->dbF->getRow($sql);
        }

        $form_fields = array();

        $form_fields[] = array(
            'type'      => 'none',
            'format' => $token,
        );

        $form_fields[] = array(
            'label' => "hidden",
            'name'  => 'editId',
            'placeholder' => "",
            'value' => @$id,
            'type'  => 'hidden',
            'class' => 'form-control',
        );

        //making Loop for multilanguage
        $form_fields[]  = array(
            'type'          => 'none',
            'thisFormat'    => '<div class="panel-group" id="accordion">',
        );
        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }

        @$title         =    unserialize($data['name']);
        for ($i = 0; $i < sizeof($lang); $i++) {
            if ($i == 0) {
                $collapseIn = ' in ';
            } else {
                $collapseIn = '';
            }

            $form_fields[] = array(
                'type'  => 'none',
                'thisFormat' => '<div class="panel panel-default">
                          <div class="panel-heading">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
                                    <h4 class="panel-title">
                                        '.$lang[$i].'
                                    </h4>
                                 </a>
                          </div>
                          <div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
                             <div class="panel-body">',
            );

            //Title
            @$tempValue = $title[$lang[$i]];
            $form_fields[] = array(
                'label' => _uc($_e['Gift Name']),
                'name'  => 'insert[name]['.$lang[$i].']',
                'placeholder' => _uc($_e['Gift Name']),
                'value' => $tempValue,
                'type'  => 'text',
                'class' => 'form-control',
            );

            $form_fields[]  = array(
                'type'          => 'none',
                'thisFormat'    => '</div><!-- panel-body end -->
                                        </div><!-- collapse end-->
                                    </div>',
            );

        }


        $form_fields[]  = array(
            'type'          => 'none',
            'thisFormat'    => '</div>',
        );

        //price
        $form_fields[] = array(
            'label' => _uc($_e['Price']),
            'name'  => 'insert[price]',
            'placeholder' => _uc($_e['Price']),
            'value' => @$data['price'],
            'type'  => 'text',
            'required'  => 'true',
            'class' => 'form-control',
        );


        if($isEdit) {
            //price
            @$temp =  $data['usePrice'];

            $form_fields[] = array(
                'label' => _uc($_e['Use Price']),
                'name'  => 'insert[usePrice]',
                'value' => $temp,
                'type'  => 'text',
                'required'  => 'true',
                'class' => 'form-control',
            );

            @$temp =  $data['info'];

            $form_fields[] = array(
                'label' => _uc($_e['Info']),
                'value' => $temp,
                'type'  => 'textarea',
                'name' => 'insert[info]',
                'class' => 'form-control',
            );
        }

        //currency
        $sql        = "SELECT * FROM `currency` ORDER BY cur_id";
        $curr_data  = $this->dbF->getRows($sql);
        $array      = array();
        foreach($curr_data as $curr_val){
            $symbol = $curr_val['cur_symbol'];
            $curr_name = $curr_val['cur_name'];
            $array[$symbol] = "$curr_name - $symbol";
        }

        $form_fields[] = array(
            'label' => _uc($_e['Currency']),
            'name'  => 'insert[currency]',
            'placeholder' => _uc($_e['Currency']),
            'select' => @$data['currency'],
            'type'  => 'select',
            'array' => $array,
            'class' => 'form-control',
        );

        if(!$isEdit) {
            //loop
            $form_fields[] = array(
                'label' => _uc($_e['How many same gift cards add?All will generate unique id']),
                'name' => 'loop',
                'value' => '1',
                'type' => 'number',
                'min' => '1',
                'max' => '500',
                'required' => 'true',
                'class' => 'form-control',
            );
        }

        //Follow
        if($isEdit) {
            $valFormTemp = empty($data['publish']) ? "0" : '1';
        }else{
            $valFormTemp = '1';
        }
        $form_fields[]  = array(
            "label" => $_e['Publish'],
            'type'  => 'checkbox',
            'value' => "$valFormTemp",
            'select' => "$valFormTemp",
            'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['NO']) .'">
                            {{form}}
                            <input type="hidden" name="insert[publish]" class="checkboxHidden" value="'.$valFormTemp.'" />
                         </div>'
        );

        $form_fields[]  = array(
            "name"  => 'submit',
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'value' => _u($_e['SAVE']),
            'thisFormat' => '{{form}}'
        );

        $form_fields['form']  = array(
            'type'      => 'form',
            'class'     => "form-horizontal",
            'action'   => '-giftcard?page=giftCard',
            'method'   => 'post',
            'format'   => '{{form}}'
        );

        $format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);
    }
}
?>