<?php
require_once(__DIR__ . "/../../global_ajax.php"); //connection setting db
class filter_ajax extends object_class
{
    public function __construct()
    {
        parent::__construct('3');

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w = array();
        //Ajax class
        $_w['Delete'] = '';
        $_w['Filter'] = '';
        $_w['Filter Delete Successfully'] = '';

        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Filter Management');
    }

    public function deleteFilter()
    {
        global $_e;
        try {
            $this->db->beginTransaction();

            $id = $_POST['id'];

            $sql = "DELETE FROM filters WHERE id = '$id'";
            $this->dbF->setRow($sql, false);
            if ($this->dbF->rowCount)
                echo '1';
            else
                echo '0';

            $this->db->commit();
            $this->functions->setlog(($_e['Delete']), ($_e['Filter']), $this->dbF->rowLastId, ($_e['Filter Delete Successfully']));
        } catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }
}
