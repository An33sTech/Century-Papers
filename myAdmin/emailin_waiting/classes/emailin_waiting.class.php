<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class emailin_waiting extends object_class{
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
        $_w['Emails in Waiting'] = '' ;
        //emailin_waitingEdit.php
        $_w['Email in Waiting Information'] = '' ;
        $_w['Manage Waiting Email Information'] = '' ;
        //emailin_waiting.php
        $_w['Active Waiting Email Information'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Sort Waiting Email Information'] = '' ;
        $_w['Add New Waiting Email Information'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['There is an error, Please Refresh Page and Try Again'] = '' ;
        $_w['SNO'] = '' ;
        $_w['Email'] = '' ;
        $_w['Type'] = '' ;
        $_w['Product Name'] = '' ;
        $_w['Color'] = '' ;
        $_w['Size'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Email Delete Successfully'] = '' ;
        $_w['Image File Error'] = '' ;
        $_w['Image Not Found'] = '' ;
        $_w['Waiting Email Information'] = '' ;
        $_w['Added'] = '' ;
        $_w['Waiting Email Add Successfully'] = '' ;
        $_w['Waiting Email Add Failed'] = '' ;
        $_w['Waiting Email Update Failed'] = '' ;
        $_w['Waiting Email Update Successfully'] = '' ;
        $_w['Update'] = '' ;
        $_w['Waiting Email Title'] = '' ;
        $_w['Waiting Email Link'] = '' ;
        $_w['Short Desc'] = '' ;
        $_w['Image Recommended Size : {{size}}'] = '' ;
        $_w['Publish']  = '' ;
        $_w['Layer']    = '' ;
        $_w['SAVE']     = '' ;
        $_w['Old Waiting Email Image'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Waiting Email');

    }


    public function emailin_waitingSort(){
        echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
        $sql ="SELECT * FROM `product_subscribe`
         ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $id = $val['id'];
            @$product_name    =   unserialize($val['p_id']);
            @$product_color    =   unserialize($val['color_id']);
            @$product_size    =   unserialize($val['store_id']);
            @$email    =   unserialize($val['email']);
            @$type    =   unserialize($val['type']);    
            @$dateTime    =   unserialize($val['dateTime']);
        }

        echo '</div>';
        echo '</div>';
    }


    public function emailin_waitingView(){
       // $sql  = "SELECT * FROM `product_subscribe` ORDER BY ID DESC";
       $sql= "SELECT * FROM `product_subscribe` 
       LEFT OUTER JOIN `proudct_detail` ON `proudct_detail`.`prodet_id` = `product_subscribe`.`p_id`
       LEFT OUTER JOIN `product_size` ON `product_size`.`prosiz_id` = `product_subscribe`.`scale_id`
       LEFT OUTER JOIN `product_color` ON `product_color`.`propri_id` = `product_subscribe`.`color_id`";
        $data =  $this->dbF->getRows($sql);
        $this->emailin_waitingPrint($data);
    }

    public function emailin_waitingPrint($data){
        global $_e;
        $class = 'dTableFull tableIBMS';
        // $email = false;
        // if($this->functions->developer_setting('email')=='1'){
        //     $class="  tableIBMS";
        //     $email = true;
        // }
        echo '<div class="table-responsive">
                <table class="table table-hover '.$class.'">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>';
        // if($email){
                    echo     '<th>'. _u($_e['Product Name']) .'</th>';
                      echo   '<th>'. _u($_e['Color']) .'</th>';
                     echo    '<th>'. _u($_e['Size']) .'</th>';
             echo      '<th>'. _u($_e['Email']) .'</th>';
       // }
        echo            '<th>'. _u($_e['Type']) .'</th>
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

            @$product_id      =   $val['prodet_id'];

            @$product_name    =   $this->functions->unserializeTranslate($val['prodet_name']);
            @$product_name    =   $this->functions->addWebUrlInLink($product_name);

            @$product_color   = ( $val['proclr_name'] == '' ) ? '' : '<div title="' . $val['proclr_name'] . '" class="color_div" style="background-color: #' . $val['proclr_name'] . '"></div>';

            @$product_size    =   $val['prosiz_name'];
            @$product_size    =   $this->functions->addWebUrlInLink($product_size);

            @$email    =   $val['email'];
            @$email    =   $this->functions->addWebUrlInLink($email);

            @$type     =   $val['type'];
            @$type     =   $this->functions->addWebUrlInLink($type);

            @$type     =   $val['type'];
            @$type     =   $this->functions->addWebUrlInLink($type);

            @$dateTime =   $val['dateTime'];
            @$dateTime =   $this->functions->addWebUrlInLink($dateTime);

            $product_link = "<a href='?edit_pro={$product_id}'  data-id='{$product_id}' data-method='post'  data-window='new' data-action='-product?page=edit' class='btn btn-info'>$product_name</a>";



            echo "

                    <td> {$product_link} </td>
                    
                    <td> $product_color </td>
                    
                    <td>$product_size</td>

                    <td>$email</td>

                    <td>$type</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a style='display:none;' data-id='$id' href='-emailin_waiting?page=edit&emailin_waitingId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteemailin_waiting(this);' class='btn'>
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

    



}
?>