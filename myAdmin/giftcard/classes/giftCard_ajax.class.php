<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class giftCard_ajax extends object_class{
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
        $_w['Gift Card Delete Successfully'] = '' ;
        $_w['DELETE'] = '' ;
        $_w['Update Gift Card'] = '' ;
        $_w['Gift Card'] = '' ;
        $_w['Gift Card Update Successfully'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin SEO');

    }

    public function deleteGiftCard(){
    global $_e;
    try{
       $this->db->beginTransaction();

       $id=$_POST['id'];
       $sql2="DELETE FROM gift_card WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog(_uc($_e['DELETE']),_uc($_e['Gift Card']),$id,_uc($_e['Gift Card Delete Successfully']));
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}

    public function saleGiftCard(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];
            $verify = $_POST['val'];

            $sql2="UPDATE gift_card SET sale = ? WHERE id=?";
            $this->dbF->setRow($sql2,array($verify,$id),false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Update Gift Card']),_uc($_e['Gift Card']),$id,_uc($_e['Gift Card Update Successfully'])." sale:$verify");
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

}
?>