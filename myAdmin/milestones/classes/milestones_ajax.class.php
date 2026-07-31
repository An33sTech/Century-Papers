<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class milestones_ajax extends object_class{
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
        $_w['Brand'] = '' ;
        $_w['Milestones Delete Successfully'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Brands');
    }

public function deleteBrand(){
    global $_e;
    try{
        $this->db->beginTransaction();

        $id=$_POST['id'];

        $sql3="SELECT image FROM milestones WHERE id='$id'";
        $data=$this->dbF->getRows($sql3,false);
        foreach($data as $key=>$val){
            @unlink(__DIR__."/../../../images/$val[image]");
        }

        $sql2="DELETE FROM milestones WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog(($_e['Delete']),($_e['Milestones']),$id,($_e['Milestones Delete Successfully']));
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


    public function brandSort(){
        $list=$_POST['album'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `milestones` SET sort = ? WHERE `id` = ?";
            $data=$this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }


}
?>