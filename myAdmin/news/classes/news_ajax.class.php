<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class news_ajax extends object_class{
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
        //Ajax class
        $_w['Delete'] = '' ;
        $_w['News'] = '' ;
        $_w['News Delete Successfully'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin News Management');
    }

public function deleteNews(){
    global $_e;
    try{
        $this->db->beginTransaction();

        $id=$_POST['id'];

        $sql3="SELECT image FROM news WHERE id='$id'";
        $data=$this->dbF->getRows($sql3,false);
        foreach($data as $key=>$val){
            $this->functions->deleteOldSingleImage($val['image']);

        }

        $sql2="DELETE FROM news WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog(($_e['Delete']),($_e['News']),$this->dbF->rowLastId,($_e['News Delete Successfully']));
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


}
?>