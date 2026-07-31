<?php
require_once (__DIR__."/../../global.php"); //connection setting db



class surveyForm extends object_class{
    public $productF;
    public $imageName;
    public function __construct(){
        parent::__construct('3');

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            if ($this->functions->developer_setting('product') == '1') {
                $this->functions->require_once_custom('product_functions');
                $this->productF = new product_function();
            }
        }
        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        $_w['Manage Emails'] = '';
        $_w['Import Emails'] = '';
        $_w['Excel File'] = '';
        $_w['Submit'] = '';
        $_w['Example'] = '';
        $_w['Delete Group'] = '';
        $_w['Verify Emails'] = '';
        $_w['UnVerify Emails'] = '';
        $_w['Delete Fail Please Try Again.'] = '';
        $_w['Are You Sure You Want To Update?'] = '';
        $_w['Update Fail Please Try Again.'] = '';
        $_w['SNO'] = '';
        $_w['EMAIL'] = '';
        $_w['UPDATE'] = '';
        $_w['GROUP'] = '';
        $_w['GROUP CHANGE'] = '';
        $_w['ACTION'] = '';
        $_w['News Letter'] = '';
        $_w['Added'] = '';
        $_w['News Letter Add Successfully'] = '';
        $_w['News Letter Save Failed'] = '';
        $_w['News Letter Save Successfully'] = '';
        $_w['News Letter Save Failed,Please Enter Correct Values,: Error: {{msg}}'] = '';
        $_w['Email Queue'] = '';
        $_w['Queue Already Created'] = '';
        $_w['Queue Created Successfully'] = '';
        $_w['Queue Create Fail'] = '';
        $_w['News Letter UPDATE Successfully'] = '';
        $_w['News Letter UPDATE Failed'] = '';
        $_w['News Letter UPDATE Failed,Please Enter Correct Values,: Error: {{msg}}'] = '';

        $_w['All Form Data']  = '';
        $_w['LETTER TITLE']  = '';
        $_w['EMAIL SUBJECT']  = '';
        $_w['EMAIL PENDING']  = '';
        $_w['TOTAL EMAIL']  = '';
        $_w['Start/Pause Sending Email Queue']  = '';
        $_w['Delete Email Queue']  = '';
        $_w['SELECT GROUP']  = '';
        $_w['Edit']  = '';
        $_w['Delete Email Letter']  = '';

        $_w['FOR ADMIN']  = '';
        $_w['FROM NAME']  = '';
        $_w['FROM MAIL']  = '';
        $_w['REPLAY TO']  = '';
        $_w['SUBJECT']  = '';

        $_w['USE these Keys to replace user INFO in SUBJECT OR IN Letter']  = '';
        $_w['Enter Full Detail']  = '';
        $_w['Email News Letter']  = '';
        $_w['All Emails Send Successfully']  = '';
        $_w['Update'] = '';
        $_w['DeActive Email']= '';
        $_w['Delete Email']= '';
        $_w['Active Email']= '';
        $_w['Delete']= '';

        $_w['Email Content']= '';
        $_w['GO BACK']= '';
        $_w['Delete Fail Please Try Again.']= '';
        $_w['Update Fail Please Try Again.']= '';
        $_w["Are you sure you want to {{state}} email queue?"]= '';
        $_w['Please select group before send email letter'] = '';
        $_w['Are you sure you want to send email to {{grp}} Group?'] = '';
        $_w['Email Management'] = '';
        $_w['News Letters'] =   '';
        $_w['Email Stats'] =   '';
        $_w['New News Letter'] =   '';
        $_w['Bounce Email'] =   '';
        $_w['DateTime'] =   '';
        $_w['Delete Bounce Emails'] =   '';
        $_w['Delete'] =   '';
        $_w['Bounce Email Delete Successfully'] =   '';
        $_w['Bounce Email Delete Successfully'] =   '';
        $_w['Bounce Email Delete Failed'] =   '';
        $_w['CONTACT NO'] =   '';
        $_w['Message For Notification'] =   '';
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Email');
    }

    public function emailGrpOption($option='',$otherOption=true){
        $sql    =   "SELECT DISTINCT(`grp`) FROM email_subscribe ORDER BY `grp` ASC";
        $data   =   $this->dbF->getRows($sql);

        $temp   =   "";
        foreach($data as $val){
            $select    = "";
            if($option==$val['grp']){
                $select =   "selected";
            }
            $temp   .=  "<option value='$val[grp]' $select>$val[grp]</option>";
        }
        if($otherOption){
            $temp   .=  "<option value='other'>other</option>";
        }else{
            $temp   .=  "<option value='all'>Send To All</option>";
        }
        return $temp;
    }

    public function emailView(){
        global $_e;
        $href = "email/email_ajax.php?page=data_ajax_active_email";
        echo '<div class="table-responsive">
                <table class="table table-hover dTable_ajax_email tableIBMS" data-href="'.$href.'">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['EMAIL']) .'</th>
                        <th>'. _u($_e['CONTACT NO']) .'</th>
                        <th>'. _u($_e['UPDATE']) .'</th>
                        <th>'. _u($_e['GROUP']) .'</th>
                        <th>'. _u($_e['GROUP CHANGE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        /*$sql  = "SELECT * FROM email_subscribe WHERE verify = '1' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];

            $grpOption  =   $this->emailGrpOption($val['grp']);
            $group      = "<div class='btn-group grpDiv btn-group-sm  col-sm-12' data-id='$id'>
                                <select class='form-control emailGrp col-sm-10' onchange='emailGroup(this);' style='width: 80%'>
                                    $grpOption
                                </select>
                                <div class='col-sm-2' style='padding: 8px 0'>
                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                </div>
                                <div class='col-sm-12 padding-0 emailOtherGrp displaynone' style='padding: 8px 0'>
                                    <div class='col-sm-8 padding-0'>
                                        <input type='text' class='form-control emailOtherInput' style='width: 100%'/>
                                    </div>
                                    <div class='col-sm-4 padding-0'>
                                        <button class='btn btn-sm btn-primary emailOtherButton' onclick='emailOtherGroup(this)' type='button'>". _uc($_e['Update']) ."</button>
                                    </div>
                                </div>
                            </div>";

            echo "<tr>
                    <td>$i</td>
                    <td>$val[email]</td>
                    <td>$val[dateTime]</td>
                    <td>$val[grp]</td>
                    <td style='width: 300px'>$group</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' data-val='0' onclick='activeEmail(this);' class='btn'   title='". $_e['DeActive Email'] ."'>
                                <i class='glyphicon glyphicon-thumbs-down trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                            <a data-id='$id' onclick='deleteEmail(this);' class='btn'   title='". $_e['Delete Email'] ."'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }*/


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }



    

public function allFormdataContent(){
        global $_e;
 
        $sql  = "SELECT * FROM formAllData GROUP BY type DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
           
            $id = $val['id'];
            $type = $val['type'];


echo "<div class='tab-pane fade in container-fluid' id='newPage".$id."'>
<h2  class='tab_heading'>".$type." </h2>";

echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>Sno</th>

<th>Inserted date time</th>
<th>File</th>
<th>Field 1</th>
<th>Field 2</th>
<th>Field 3</th>
<th>Field 4</th>
<th>Field 5</th>
<th>Field 6</th>
<th>Field 7</th>
<th>Field 8</th>
<th>Field 9</th>
<th>Comment</th>
<th>Action</th>


</thead>


<tbody>';
  $i = 0;
        $sql  = "SELECT * FROM formAllData where type = '$type' ORDER BY id DESC ";
        $variable =  $this->dbF->getRows($sql);
foreach ($variable as $key => $value) {
     $id = $value['id'];
 $prev_comment = $value['comment'];

 $i++;
echo "<tr>
<td>$i</td>
<td>$value[currentdatetimePrint]</td>
<td>$value[file]</td>
<td>$value[field1]</td>
 <td>$value[field2]</td>
<td>$value[field3]</td>
<td>$value[field4]</td>
<td>$value[field5]</td>
<td>$value[field6]</td>
<td>$value[field7]</td>
<td>$value[field8]</td>
<td>$value[field9]</td>
<form method='POST'>
<td><textarea name='comment' id ='comment_text".$id."' class='form-control' maxlength='500' placeholder=''>".$prev_comment."</textarea></td>



<td><div class='btn-group btn-group-sm'>
                             
                            <a id='".$id."' class='btn btn-lg btn-primary'  name='submit_comment' onclick='commentadd(this);' value='SAVE'><i class='glyphicon glyphicon-saved'></i></a>
                            <a id='".$id."' onclick='deleteComment(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div></td></form>


</tr>";
 

    # code...
}
?>

 <script>
    function commentadd(ths) {


                var allAttr = $(ths);
                var dataId = allAttr.attr('id');
                var comm = $('#comment_text'+dataId).val();
                console.log(dataId);
                console.log(comm);

                $.ajax({
                     type:"POST",
                     url:"email/classes/ajax.php",
                     data: {
                            id: dataId,comment: comm
                            },
                     success: function(response){
                             $('#comment_text').val(comm);
                             alert('Comment Saved'); 
                     },
                     error: function(jqxhr, status, exception) {
             alert('Exception:', exception);
         }
                });

            }

            function deleteComment(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('id');
            $.ajax({
                type: 'POST',
                url: 'email/classes/ajax.php',
                data: { id:id,comm:'delete' }
            }).done(function(data)
                {
                    ift =true;
                    
                        
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    
                    

                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
        }
    }

         //    function deleteComment(ths) {


         //        var allAttr = $(ths);
         //        var dataId = allAttr.attr('id');
         //        // var comm = $('#comment_text'+dataId).val();
         //        console.log(dataId);
         //        // console.log(dataId);

         //        $.ajax({
         //             type:"POST",
         //             url:"email/classes/ajax.php",
         //             data: {
         //                    id: dataId,comm: 'delete'
         //                    },
         //             success: function(response){

         //                     // $('#comment_text').val(comm);
         //                     alert('deleted');  
         //                      location.reload();
         //             },
         //             error: function(jqxhr, status, exception) {
         //     alert('Exception:', exception);
         // }
         //        });

         //    }


    </script>

<?php

        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';




            echo "

        </div>




            ";

             
        }


       
    }
    
public function allFormdataContent2(){
        global $_e;
 
        $sql  = "SELECT * FROM surveyFormData GROUP BY type DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        // var_dump($data);

        
foreach($data as $val){
    // var_dump($val);
        //   exit();
$id = $val['id'];
$type = $val['type'];


echo "<div class='tab-pane fade in container-fluid' id='newPage".$id."'>
<h2  class='tab_heading'>".$type." </h2>";

echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>Sno</th>

<th>Inserted date time</th>
<th>Field 1</th>
<th>Field 2</th>
<th>Field 3</th>
<th>Field 4</th>
<th>Field 5</th>
<th>Field 6</th>
<th>Field 7</th>
<th>Field 8</th>
<th>Field 9</th>
<th>Comment</th>
<th>Action</th>


</thead>


<tbody>';
  $i = 0;
        $sql  = "SELECT * FROM surveyFormData where type = '$type' ORDER BY id DESC ";
        $variable =  $this->dbF->getRows($sql);
foreach ($variable as $key => $value) {
     $id = $value['id'];
 $prev_comment = $value['comment'];

 $i++;
echo "<tr>
<td>$i</td>
<td>$value[currentdatetimePrint]</td>
<td>$value[field1]</td>
<td>$value[field2]</td>
<td>$value[field3]</td>
<td>$value[field4]</td>
<td>$value[field5]</td>
<td>$value[field6]</td>
<td>$value[field7]</td>
<td>$value[field8]</td>
<td>$value[field9]</td>
<form method='POST'>
<td><textarea name='comment' id ='comment_text".$id."' class='form-control' maxlength='500' placeholder=''>".$prev_comment."</textarea></td>



<td><div class='btn-group btn-group-sm'>
                             
                            <a id='".$id."' class='btn btn-lg btn-primary'  name='submit_comment' onclick='commentAdd(this);' value='SAVE'><i class='glyphicon glyphicon-saved'></i></a>
                            <a id='".$id."' onclick='deleteComment(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div></td></form>


</tr>";
 

    # code...
}
?>

 <script>
    function commentAdd(ths) {


                var allAttr = $(ths);
                var dataId = allAttr.attr('id');
                var comm = $('#comment_text'+dataId).val();
                

                $.ajax({
                     type:"POST",
                     url:"surveyForm/classes/ajax.php",
                     data: {
                            id: dataId,comment: comm
                            },
                     success: function(response){
                             $('#comment_text').val(comm);
                             alert('Comment Saved'); 
                     },
                     error: function(jqxhr, status, exception) {
             alert('Exception:', exception);
         }
                });

            }

            function deleteComment(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('id');
            $.ajax({
                type: 'POST',
                url: 'surveyForm/classes/ajax.php',
                data: { id:id,comm:'delete' }
            }).done(function(data)
                {
                    ift =true;
                    
                        
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    
                    

                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
        }
    }

         //    function deleteComment(ths) {


         //        var allAttr = $(ths);
         //        var dataId = allAttr.attr('id');
         //        // var comm = $('#comment_text'+dataId).val();
         //        console.log(dataId);
         //        // console.log(dataId);

         //        $.ajax({
         //             type:"POST",
         //             url:"email/classes/ajax.php",
         //             data: {
         //                    id: dataId,comm: 'delete'
         //                    },
         //             success: function(response){

         //                     // $('#comment_text').val(comm);
         //                     alert('deleted');  
         //                      location.reload();
         //             },
         //             error: function(jqxhr, status, exception) {
         //     alert('Exception:', exception);
         // }
         //        });

         //    }


    </script>

<?php

        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';




            echo "

        </div>




            ";

             
        }


       
    }


public function allFormdata(){
        global $_e;
        // $href = "email/email_ajax.php?page=data_ajax_active_email";
        // echo '<div class="table-responsive">
        //         <table class="table table-hover dTable_ajax_email tableIBMS" data-href="'.$href.'">
        //             <thead>
        //                 <th>'. _u($_e['SNO']) .'</th>
        //                 <th>'. _u($_e['EMAIL']) .'</th>
        //                 <th>'. _u($_e['CONTACT NO']) .'</th>
        //                 <th>'. _u($_e['UPDATE']) .'</th>
        //                 <th>'. _u($_e['GROUP']) .'</th>
        //                 <th>'. _u($_e['GROUP CHANGE']) .'</th>
        //                 <th>'. _u($_e['ACTION']) .'</th>
        //             </thead>
        //         <tbody>';
        $sql  = "SELECT * FROM formAllData GROUP BY type DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            $type = str_replace("_"," ",$val['type']);


            echo "<li id='NewMenu'><a href='#newPage".$id."' role='tab' data-toggle='tab'>".$type."</a></li>";

            // $grpOption  =   $this->emailGrpOption($val['grp']);
            // $group      = "<div class='btn-group grpDiv btn-group-sm  col-sm-12' data-id='$id'>
            //                     <select class='form-control emailGrp col-sm-10' onchange='emailGroup(this);' style='width: 80%'>
            //                         $grpOption
            //                     </select>
            //                     <div class='col-sm-2' style='padding: 8px 0'>
            //                         <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            //                     </div>
            //                     <div class='col-sm-12 padding-0 emailOtherGrp displaynone' style='padding: 8px 0'>
            //                         <div class='col-sm-8 padding-0'>
            //                             <input type='text' class='form-control emailOtherInput' style='width: 100%'/>
            //                         </div>
            //                         <div class='col-sm-4 padding-0'>
            //                             <button class='btn btn-sm btn-primary emailOtherButton' onclick='emailOtherGroup(this)' type='button'>". _uc($_e['Update']) ."</button>
            //                         </div>
            //                     </div>
            //                 </div>";

            // echo "<tr>
            //         <td>$i</td>
            //         <td>$val[email]</td>
            //         <td>$val[dateTime]</td>
            //         <td>$val[grp]</td>
            //         <td style='width: 300px'>$group</td>
            //         <td>
            //             <div class='btn-group btn-group-sm'>
            //                 <a data-id='$id' data-val='0' onclick='activeEmail(this);' class='btn'   title='". $_e['DeActive Email'] ."'>
            //                     <i class='glyphicon glyphicon-thumbs-down trash'></i>
            //                     <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            //                 </a>
            //                 <a data-id='$id' onclick='deleteEmail(this);' class='btn'   title='". $_e['Delete Email'] ."'>
            //                     <i class='glyphicon glyphicon-trash trash'></i>
            //                     <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            //                 </a>
            //             </div>
            //         </td>
            //       </tr>";
        }


        // echo '</tbody>
        //      </table>
        //     </div> <!-- .table-responsive End -->';
    }

public function allFormdata2(){
        global $_e;
        
        $sql  = "SELECT * FROM surveyFormData GROUP BY type ASC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            $type = str_replace("_"," ",$val['type']);


            echo "<li id='NewMenu'><a href='#newPage".$id."' role='tab' data-toggle='tab'>".$type."</a></li>";
        }

    }

    public function emailGroup(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTableT tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['GROUP']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $sql  = "SELECT DISTINCT(`grp`) FROM email_subscribe ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            $grp= $val['grp'];

            echo "<tr>
                    <td>$i</td>

                    <td>$grp</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$grp' onclick='deleteGroup(this);' class='btn'   title='". $_e['Delete Group'] ."'>
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


    public function emailPending(){
        global $_e;
        $href = "email/email_ajax.php?page=data_ajax_unactive_email";
        echo '<div class="table-responsive">
                <table class="table table-hover dTable_ajax_email tableIBMS" data-href="'.$href.'">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['EMAIL']) .'</th>
                        <th>'. _u($_e['CONTACT NO']) .'</th>
                        <th>'. _u($_e['UPDATE']) .'</th>
                        <th>'. _u($_e['GROUP']) .'</th>
                        <th>'. _u($_e['GROUP CHANGE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        /*$sql  = "SELECT * FROM email_subscribe WHERE verify = '0' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            echo "<tr>
                    <td>$i</td>
                    <td>$val[email]</td>
                    <td>$val[dateTime]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' data-val='1' onclick='activeEmail(this);' class='btn'  title='". $_e['Active Email'] ."'>
                                <i class='glyphicon glyphicon-thumbs-up trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                            <a data-id='$id' onclick='deleteEmail(this);' title='". $_e['Delete'] ."' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }*/


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }


    public function newLetterAdd(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('newNewsLetter')){return false;}

            $subject        = empty($_POST['subject'])      ? ""    : ($_POST['subject']);
            $fromName       = empty($_POST['fromName'])     ? ""  : ($_POST['fromName']);
            $dsc            = empty($_POST['dsc'])          ? ""    : (($_POST['dsc']));
            $message_notification            = empty($_POST['message_notification'])          ? ""    : (($_POST['message_notification']));
            $event          = empty($_POST['event'])        ? ""    : $_POST['event'];
            $replayTO       = empty($_POST['replayTO'])     ? ""    : $_POST['replayTO'];
            $fromMail       = empty($_POST['fromMail'])     ? ""    : $_POST['fromMail'];

            $fromMail       =   explode("@",$fromMail);
            $fromMail       =   $fromMail[0];
      
htmlspecialchars($subject);
htmlspecialchars($fromName);
htmlspecialchars($dsc);
htmlspecialchars($message_notification);
htmlspecialchars($event);
htmlspecialchars($replayTO);
htmlspecialchars($fromMail);


            try{
                $this->db->beginTransaction();

                $sql      =   "INSERT INTO `email_letters`(
                                `event`, `from_name`, `from_mail`,
                                `reply_to`, `return_path`,`subject`,
                                 `message`,`mesaage_notification`)
                                 VALUES (?,?,?,
                                        ?,?,?,
                                          ?,
                                          ?)";

                $array   = array($event,$fromName,$fromMail,
                                $replayTO,$replayTO,$subject,
                                $dsc,$message_notification);

                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();

                if($this->dbF->rowCount>0){
                    $this->functions->notificationError($_e['News Letter'],$_e['News Letter Save Successfully'],'btn-success');
                    $this->functions->setlog($_e['Added'],$_e['News Letter'],$this->dbF->rowLastId,$_e['News Letter Add Successfully']);
                }else{
                    $this->functions->notificationError($_e['News Letter'],$_e['News Letter Save Failed'],'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $msg = $this->dbF->rowException;
                $this->functions->notificationError($_e['News Letter'],_replace('{{msg}}',$msg,$_e['News Letter Save Failed,Please Enter Correct Values,: Error: {{msg}}']),'btn-danger');
            }
        } // If end
    }


    public function letterSend(){
        global $_e;
        if(isset($_GET['sendletter']) && isset($_GET['grp']) && $_GET['grp']!='undefined' && isset($_SERVER['HTTP_REFERER'])){
            $letterId   =   $_GET['sendletter'];
            $grp    =   $_GET['grp'];

            $sql    =   "SELECT * FROM email_letter_queue WHERE letter_id = '$letterId' AND grp = '$grp'";
            $data   =   $this->dbF->getRow($sql);

            if(!$this->dbF->rowCount){
                    $this->emailQueue($letterId,$grp);
                    $this->cronJob();
            }else{
               $this->functions->notificationError($_e['Email Queue'],$_e['Queue Already Created'],'btn-warning');
               echo "<script>
                        location.replace('-email?page=newsLetter');
                    </script>";
            }
        }
    }

    public function emailQueue($letterId,$grp){
        global $_e;
        if($grp !='' && $grp != 'all'){
            $grpM = " AND grp = '$grp'";
        }else{
            $grpM = "";
        }
        $sql    =   "INSERT INTO email_letter_queue(`letter_id`,`grp`,`email_name`,`email_to` )
                        SELECT '$letterId','$grp',`name`,`email`  FROM email_subscribe WHERE verify = '1' $grpM";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            $this->functions->notificationError($_e['Email Queue'],$_e['Queue Created Successfully'],'btn-success');
        }else{
            $this->functions->notificationError($_e['Email Queue'],$_e['Queue Create Fail'],'btn-danger');
        }

    }

    public function letterEditSubmit(){
        global $_e;
        if(isset($_POST['submit'])){
            if(!$this->functions->getFormToken('editNewsLetter')){return false;}

            $subject        = empty($_POST['subject'])      ? ""    : ($_POST['subject']);
            $fromName       = empty($_POST['fromName'])     ? ""  : ($_POST['fromName']);
            $dsc            = empty($_POST['dsc'])          ? ""    : (($_POST['dsc']));
            $message_notification            = empty($_POST['message_notification'])          ? ""    : (($_POST['message_notification']));
            $event          = empty($_POST['event'])        ? ""    : $_POST['event'];
            $replayTO       = empty($_POST['replayTO'])     ? ""    : $_POST['replayTO'];
            $fromMail       = empty($_POST['fromMail'])     ? ""    : $_POST['fromMail'];

            $lastId         = $_POST['editId'];

            $fromMail       =   explode("@",$fromMail);
            $fromMail       =   $fromMail[0];

htmlspecialchars($subject);
htmlspecialchars($fromName);
htmlspecialchars($dsc);
htmlspecialchars($message_notification);
htmlspecialchars($event);
htmlspecialchars($replayTO);
htmlspecialchars($fromMail);
htmlspecialchars($lastId);
htmlspecialchars($fromMail);

            try{
                $this->db->beginTransaction();

                $sql  =   "UPDATE `email_letters` SET
                                `event` = ? ,
                                `from_name` = ?,
                                `from_mail` = ?,
                                `reply_to`  = ?,
                                `return_path` = ?,
                                `subject` = ?,
                                `message` =  ?,
                                `mesaage_notification` =  ?
                                 WHERE id = '$lastId'";

                $array   = array($event,$fromName,$fromMail,
                                    $replayTO,$replayTO,$subject,
                                        $dsc,$message_notification);

                $this->dbF->setRow($sql,$array,false);

                $this->db->commit();

                if($this->dbF->rowCount>0){
                    $this->functions->notificationError($_e['News Letter'],$_e['News Letter UPDATE Successfully'],'btn-success');
                    $this->functions->setlog($_e['Added'],$_e['News Letter'],$this->dbF->rowLastId,$_e['News Letter UPDATE Successfully']);
                }else{
                    $this->functions->notificationError($_e['News Letter'],$_e['News Letter UPDATE Failed'],'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $msg = $this->dbF->rowException;
                $this->functions->notificationError($_e['News Letter'],str_replace('{{msg}}',$msg,$_e['News Letter UPDATE Failed,Please Enter Correct Values,: Error: {{msg}}']),'btn-danger');

            }
        } // If end
    }

    public function newsLetterProducts(){
        if(isset($_POST['producNewsLetter']) && $_POST['producNewsLetter'] !=''){
            $temp = '<style>
                    .allProductInfo {
                        float: left;
                        width: 225px;
                        padding: 5px;
                        background: #ddd;
                        margin: 8px;
                        height: 200px;
                        position: relative;
                    }
                    .allProductInfo .pImg {
                        height: 160px;
                        text-align: center;
                        width: 100%;
                    }
                    .allProductInfo .pName {
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        background: #ccc;
                        width: 100%;
                        text-align: center;
                    }
                    .allProductInfo .pImg img {
                        max-height: 100%;
                        max-width: 100%;
                    }
                    .allProductInfo .oldPrice {
                        font-size: 11px;
                        text-decoration: line-through;
                        color: red;
                    }
                </style>

                <br><br>
                ';
            $pLink = WEB_URL.'/products?product=';
            foreach($_POST['producNewsLetter'] as $pId){
                $id = $pId;
                $name=$this->productF->getProductName($pId);
                $img = $this->productF->productSpecialImage($id,'main');
                $img    = $this->functions->resizeImage($img,'auto',160,false);
                $price = $this->productF->productPrice($id);
                $currencyId =   $price['propri_cur_id'];
                $symbol     =   $this->productF->currencySymbol($currencyId);
                $priceP =   $price['propri_price'];

                $discount       =   $this->productF->productDiscount($id,$currencyId);
                @$discountFormat=   $discount['discountFormat'];
                @$discountP     =   $discount['discount'];

                $discountPrice  =   $this->productF->discountPriceCalculation($priceP,$discount);
                $newPrice       =   $priceP - $discountPrice;

                $priceP         .= ' '.$symbol;
                $newPrice       .= ' '.$symbol;

                if($newPrice    !=  $priceP){
                    $hasDiscount = true;
                    $oldPriceDiv = '<span class="oldPrice">'.$priceP.'</span>';
                    $newPriceDiv = '<span class="NewDiscountPrice">'.$newPrice.'</span>';
                }else{
                    $oldPriceDiv= "";
                    $newPriceDiv = '<span class="NewDiscountPrice">'.$priceP.'</span>';
                }

                $temp .= "
                    <div class='allProductInfo'>
                        <div class='pImg'>
                            <a href='".$pLink.$id."'><img src='$img'/></a>
                        </div>
                        <div class='pName'>
                            <a href='".$pLink.$id."'>$name
                                <br>
                                   $oldPriceDiv $newPriceDiv
                            </a>
                        </div>
                      </div>
                ";
            }
            return $temp;
        }
    }

    public function emailStats(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['LETTER TITLE']) .'</th>
                        <th>'. _u($_e['EMAIL SUBJECT']) .'</th>
                        <th>'. _u($_e['GROUP']) .'</th>
                        <th>'. _u($_e['EMAIL PENDING']) .'</th>
                        <th>'. _u($_e['TOTAL EMAIL']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $sql  = "SELECT * FROM email_letter_queue GROUP BY letter_id,grp ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];

            $sql  = "SELECT * FROM email_letters WHERE id = '$val[letter_id]' ";
            $letterData =  $this->dbF->getRow($sql);

            //Pending COunt
            $sql    =   "SELECT count(id) as cnt FROM email_letter_queue WHERE letter_id = '$val[letter_id]' AND grp = '$val[grp]'";
            $countData  =   $this->dbF->getRow($sql);
            $count  =   $countData['cnt'];


            //Subscribe Email Count
            $grp    =   $val['grp'];
            if($grp=='all' || $grp==''){
                $sql    =   "SELECT count(id) as cnt FROM email_subscribe WHERE verify= '1'";
            }else{
                $sql    =   "SELECT count(id) as cnt FROM email_subscribe WHERE  verify= '1' AND grp = '$grp'";
            }
            $countData2  =   $this->dbF->getRow($sql);
            $countTotal  =   $countData2['cnt'];

            $status =   $val['status'];
            if($status=='1'){
                $class  =   "glyphicon glyphicon-pause";
                $status =   '0';
            }else{
                $class  =   "glyphicon glyphicon-play";
                $status =   '1';
            }

            echo "<tr>
                    <td>$i</td>
                    <td>$letterData[event]</td>
                    <td>$letterData[subject]</td>
                    <td>$val[grp]</td>

                    <td>$count</td>
                    <td>$countTotal</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id ='$id' data-val='$status' onclick='startQueue(this);' class='btn'   title='". $_e['Start/Pause Sending Email Queue'] ."'>
                                <i class='$class trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                            <a data-id='$id' onclick='deleteQueue(this);' class='btn'   title='". $_e['Delete Email Queue'] ."'>
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

    public function emailBounceDelete(){
        global $_e;
        $sql = "DELETE FROM email_bounce";
        $this->dbF->setRow($sql);

        if($this->dbF->rowCount>0){
            $this->functions->notificationError($_e['Bounce Email'],$_e['Bounce Email Delete Successfully'],'btn-success');
            $this->functions->setlog($_e['Delete'],$_e['Bounce Email'],$this->dbF->rowLastId,$_e['Bounce Email Delete Successfully']);
        }else{
            $this->functions->notificationError($_e['Bounce Email'],$_e['Bounce Email Delete Failed'],'btn-danger');
        }
    }

    public function emailBounce(){
        global $_e;
        $this->functions->dTableT();
        echo '<div class="table-responsive">
                <table class="table table-hover dTableT tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['EMAIL']) .'</th>
                        <th>'. _u($_e['DateTime']) .'</th>
                    </thead>
                <tbody>';
        $sql  = "SELECT * FROM email_bounce  ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;
        foreach($data as $val){
            $i++;
            /*$sql  = "SELECT * FROM email_letters WHERE id = '$val[letter_id]' ";
            $letterData =  $this->dbF->getRow($sql);*/

            echo "<tr>
                    <td>$i</td>
                    <td>$val[email]</td>
                    <td>$val[dateTime]</td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }
    public function letterView(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['LETTER TITLE']) .'</th>
                        <th>'. _u($_e['EMAIL SUBJECT']) .'</th>
                        <th>'. _u($_e['SELECT GROUP']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $sql  = "SELECT * FROM email_letters WHERE email_type='letter' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;


        $grpOption  =   $this->emailGrpOption('',false);
        $grpOption  ="<select class='form-control emailGrp col-sm-10' style='width: 80%'>
                                    $grpOption
                                </select>";
        foreach($data as $val){
            $i++;
            $id = $val['id'];
            echo "<tr>
                    <td>$i</td>
                    <td>$val[event]</td>
                    <td>$val[subject]</td>
                    <td>$grpOption</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a href='#' class='btn' title='Send Email To this Group'  onclick='return sendLetter(this,$id);'>
                                <i class='glyphicon glyphicon-envelope'></i>
                            </a>
                            <a href='-email?page=newsLetter&editId=$id' class='btn' title='". $_e['Edit'] ."'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteLetter(this);' class='btn'   title='". $_e['Delete Email Letter'] ."'>
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


    public function emailContentView(){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTableT tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['LETTER TITLE']) .'</th>
                        <th>'. _u($_e['EMAIL SUBJECT']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $sql  = "SELECT * FROM email_letters WHERE email_type != 'letter' AND visible = '1'  ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;

        foreach($data as $val){
            $i++;
            $id = $val['id'];
            echo "<tr>
                    <td>$i</td>
                    <td>$val[event]</td>
                    <td>$val[subject]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a href='-email?page=emailContent&editId=$id' class='btn' title='". $_e['Edit'] ."'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div><script>$(document).ready(function(){dTableT();});</script> <!-- .table-responsive End -->';
    }

    public function letterNew(){
        global $_e;
        if(isset($_GET['editId']) && $_GET['editId']!=''){
            $editId = $_GET['editId'];
            $sql  = "SELECT * FROM email_letters WHERE  id ='$editId'";
            $data =  $this->dbF->getRow($sql);
            $token       = $this->functions->setFormToken('editNewsLetter',false);
            $product    =   $data['message'];
        //   echo "<br>";
       $message_notification    =   $data['mesaage_notification'];
            $lastId =   "<input type='hidden' name='editId' value='$data[id]'/>";
        }else{ 
            $token       = $this->functions->setFormToken('newNewsLetter',false);
            //No need to remove any thing,, go in developer setting table and set 0
            $product    = $this->newsLetterProducts();
            $lastId =   '';
        }


        echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">'.
            $token.
            $lastId.
            '<div class="form-horizontal">';

            //LETTER TITLE
            echo '<div class="form-group">
                                            <label class="col-sm-2 col-md-3  control-label">'. _u($_e['LETTER TITLE']) .'<small> '. _u($_e['FOR ADMIN']) .'</small></label>
                                            <div class="col-sm-10  col-md-9">
                                                <input type="text" value="'.@$data['event'].'" name="event"  maxlength="150"  class="form-control" placeholder="'. _u($_e['LETTER TITLE']) .'">
                                            </div>
                                        </div>';



            //From Name
            echo '<div class="form-group">
                                            <label class="col-sm-2 col-md-3  control-label">'. _u($_e['FROM NAME']) .'</label>
                                            <div class="col-sm-10  col-md-9">
                                                <input type="text" value="'.@$data['from_name'].'"  name="fromName"  maxlength="150"  class="form-control" placeholder="'. _u($_e['FROM NAME']) .'">
                                            </div>
                                        </div>';

            //FROM MAIL
            echo '<div class="form-group">
                                            <label class="col-sm-2 col-md-3  control-label">'. _u($_e['FROM MAIL']) .'</label>
                                            <div class="col-sm-10  col-md-9">
                                                <input type="email"  value="'.@$data['from_mail'].'@'.$this->functions->defaultEmail.'"  name="fromMail"  maxlength="150"  class="form-control" placeholder="'. _u($_e['FROM MAIL']) .'">
                                            </div>
                                        </div>';


        if(empty($data['email_type']) || @$data['email_type'] == "letter"){
            //REPLAY TO
            echo '<div class="form-group">
                                            <label class="col-sm-2 col-md-3  control-label">' . _u($_e['REPLAY TO']) . '</label>
                                            <div class="col-sm-10  col-md-9">
                                                <input type="email"  value="' . @$data['reply_to'] . '"  name="replayTO"  maxlength="150"  class="form-control" placeholder="' . _u($_e['REPLAY TO']) . '">
                                            </div>
                                        </div>';
        }
            //SUBJECT
            echo '<div class="form-group">
                                            <label class="col-sm-2 col-md-3  control-label">'. _u($_e['SUBJECT']) .'</label>
                                            <div class="col-sm-10  col-md-9">
                                                <input type="text" value="'.@$data['subject'].'"  name="subject"  maxlength="150"  class="form-control" placeholder="'. _u($_e['SUBJECT']) .'">
                                            </div>
                                        </div>';

            //Desc
            echo '<div class="form-group">
                                        <label class="col-sm-2 col-md-3  control-label">'. _u($_e['Email News Letter']) .'</label>
                                        <div class="col-sm-10  col-md-9">
                                            <textarea name="dsc" id="dsc" placeholder="'. _u($_e['Enter Full Detail']) .'" class="ckeditor">'.$product.'</textarea>

                                            <br>
                                            '. $_e['USE these Keys to replace user INFO in SUBJECT OR IN Letter'] .' <br>
                                            email : {{email}} , name : {{name}} , group : {{group}}
                                            <br>
                                            '.$this->db->webName.' : {{webName}} , WebSite Link : {{webLink}}
                                        </div>
                                   </div>';
        //Desc
            echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">'. _u($_e['Message For Notification']) .'</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="message_notification" id="message_notification" placeholder="'. _u($_e['Enter Full Detail']) .'" class="ckeditor">'.$message_notification.'</textarea>

                            <br>
                            '. $_e['USE these Keys to replace user INFO in SUBJECT OR IN Letter'] .' <br>
                            email : {{email}} , name : {{name}} , group : {{group}}
                            <br>
                            '.$this->db->webName.' : {{webName}} , WebSite Link : {{webLink}}
                        </div>
                   </div>';

        echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';

        echo "
    </div> <!-- container end -->
</form>";
    }


    public function letterComplete(){
        global $_e;
        if(isset($_GET['completeEmails'])){

            ob_start();
            include_once('../requiredCron.txt');
            $emailComplete =  ob_get_clean();
            if($emailComplete =='okay'){
                $requiredCronFile   =   "../requiredCron.txt";
                $file   =  "false";
                file_put_contents($requiredCronFile, $file); //blank file..

                $this->cronJob();

                $this->functions->notificationError($_e['EMAIL'],$_e['All Emails Send Successfully'],'btn-success');
            }
        }
    }

    public function cronJob(){
        $this->functions->cronJob();
    }

    public function cronJobRunning(){
            //view running job
            //-email?page=newsLetter&runningJob
            $output = shell_exec('crontab -l');
            $cron_file = "crontab.txt";
            $this->dbF->prnt(nl2br($output));
    }
}
?>