<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class emailin_waiting_ajax extends object_class{
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
        //ajax class
        $_w['Delete'] = '' ;
        $_w['Waiting Emails'] = '' ;
        $_w['Email Delete Successfully'] = '' ;
        $_w['Active Email Product Information'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Email Waiting');
    }

public function deleteemailin_waiting(){
    global $_e;
    try{
        $this->db->beginTransaction();
        $id=$_POST['id'];

       $sql2="DELETE FROM product_subscribe WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog(($_e['Delete']),($_e['Active Email Product Information']),$id,($_e['Email Delete Successfully']));
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


    public function emailin_waitingSort(){
        $list=$_POST['album'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `product_subscribe` SET sort = ? WHERE `id` = ?";
            $data=$this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }


}
?>