<?php
require_once(__DIR__ . "/../../global.php"); //connection setting db
class faq extends object_class
{
    public $productF;
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
        //Index
        //filesManagerEdit.php

        //filesManager.php
        $_w['Active Documents'] = '';
        $_w['Draft Documents'] = '';
        $_w['Draft'] = '';
        $_w['Add New Document'] = '';
        $_w['Delete Fail Please Try Again.'] = '';
        $_w['There is an error, Please Refresh Page and Try Again'] = '';
        $_w['SNO'] = '';
        $_w['Documents Title'] = '';
        $_w['IMAGE'] = '';
        $_w['ACTION'] = '';
        $_w['Image Documents Error'] = '';
        $_w['Image Not Found'] = '';
        $_w['FAQS'] = '';
        $_w['Added'] = '';
        $_w['FAQ Add Successfully'] = '';
        $_w['FAQ Add Failed'] = '';
        $_w['FAQ Update Failed'] = '';
        $_w['FAQ Update Successfully'] = '';
        $_w['Update'] = '';
        $_w['Title'] = '';
        $_w['File'] = '';
        $_w['Short Desc'] = '';
        $_w['Image Recommended Size : {{size}}'] = '';
        $_w['Publish'] = '';
        $_w['Layer'] = '';
        $_w['User'] = '';
        $_w['Select'] = '';
        $_w['SAVE'] = '';
        $_w['Old Documents Image'] = '';
        $_w['USER'] = '';
        $_w['mail'] = '';
        $_w['Select Service'] = '';
        $_w['Products'] = '';
        $_w['User'] = '';
        $_w['Products'] = '';
        $_w['Due Date'] = '';
        $_w['Mandatory'] = '';
        $_w['Recommended'] = '';
        $_w['Assign To'] = '';
        $_w['One User'] = '';
        $_w['All User'] = '';
        $_w['Place'] = '';
        $_w['Type'] = '';
        $_w['Description'] = '';
        $_w['Approved'] = '';
        $_w['Yes'] = '';
        $_w['No'] = '';
        $_w['Approved Documents'] = '';
        $_w['Recurring Duration'] = '';
        $_w['Recurrence'] = '';
        $_w['Training'] = '';
        $_w['FAQS'] = '';
        $_w['Sub Category'] = '';
        $_w['Manage FAQ'] = '';
        $_w['Active FAQ'] = '';
        $_w['Add New FAQ'] = '';
        $_w['Draft FAQ'] = '';
        $_w['Add New FAQ'] = '';
        $_w['Enter Full Detail'] = '';
        $_w['Detail'] = '';
        $_w['FAQ Management'] = '';
        $_w['Select Category'] = '';
        $_w['Select Product'] = '';

        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Documents');
    }

    public function faqView()
    {
        $sql = "SELECT * FROM faqs WHERE publish = '1' ORDER BY id DESC";
        $data = $this->dbF->getRows($sql);
        $this->faqPrint($data);
    }

    public function faqDraft()
    {
        $sql = "SELECT * FROM faqs WHERE publish = '0' ORDER BY id DESC";
        $data = $this->dbF->getRows($sql);
        $this->faqPrint($data);
    }

    public function faqPrint($data)
    {
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                        <th>' . _u($_e['Title']) . '</th>
                        <th>' . _u($_e['Detail']) . '</th>
                        <th>' . _u($_e['Place']) . '</th>
                        <th>' . _u($_e['ACTION']) . '</th>
                    </thead>
                <tbody>';

        $i = 0;
        foreach ($data as $val) {
            $i++;
            $id = $val['id'];

            echo "<tr>
                    <td>$id</td>
                    <td>$val[title]</td>
                    <td>$val[dsc]</td>
                    <td>$val[category]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-faq?page=edit&faqId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deletefaq (this);' class='btn'>
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

    public function faqAdd()
    {
        global $_e;
        if (isset($_POST['submit'])) {
            if (!$this->functions->getFormToken('newfaq')) {
                return false;
            }

            $title = empty($_POST['title']) ? "" : $_POST['title'];
            $publish = empty($_POST['publish']) ? "0" : $_POST['publish'];
            $category = empty($_POST['category']) ? "" : $_POST['category'];
            $dsc = empty($_POST['dsc']) ? "" : $_POST['dsc'];
            $categoryId = empty($_POST['categoryId']) ? "" : $_POST['categoryId'];
            $pId = empty($_POST['pId']) ? "" : $_POST['pId'];
            htmlspecialchars($title);
            htmlspecialchars($publish);
            htmlspecialchars($category);
            try {
                $this->db->beginTransaction();

                $sql = "INSERT INTO `faqs` (`title`, `dsc`, `category`, `category_id`, `p_id`, `publish`) VALUES (?, ?, ?, ?, ?, ?)";
                $array = array($title, $dsc, $category, $categoryId, $pId, $publish);
                $this->dbF->setRow($sql, $array, false);

                $lastId = $this->dbF->rowLastId;

                $this->db->commit();
                if ($this->dbF->rowCount > 0) {
                    $this->functions->notificationError(_uc($_e['FAQS']), ($_e['FAQ Add Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Added']), _uc($_e['FAQS']), $lastId, ($_e['FAQ Add Successfully']));
                } else {
                    $this->functions->notificationError(_uc($_e['FAQS']), ($_e['FAQ Add Failed']), 'btn-danger');
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['FAQS']), ($_e['FAQ Add Failed']), 'btn-danger');
            }
        } // If end
    }




    public function faqEditSubmit()
    {
        global $_e;
        if (isset($_POST['submit'])) {
            if (!$this->functions->getFormToken('editfaq')) {
                return false;
            }

            $title = empty($_POST['title']) ? "" : $_POST['title'];
            $dsc = empty($_POST['dsc']) ? "" : $_POST['dsc'];
            $publish = empty($_POST['publish']) ? "0" : $_POST['publish'];
            $category = empty($_POST['category']) ? "" : $_POST['category'];
            $categoryId = empty($_POST['categoryId']) ? "" : $_POST['categoryId'];
            $pId = empty($_POST['pId']) ? "" : $_POST['pId'];
            htmlspecialchars($title);
            htmlspecialchars($publish);
            htmlspecialchars($category);

            try {
                $this->db->beginTransaction();
                $lastId = intval($_POST['editId']);

                $sql = "UPDATE `faqs` SET `title` = ?, `dsc` = ?, `publish` = ?, `category` = ?, category_id = ?, p_id = ? WHERE id = '$lastId'";
                $array = array($title, $dsc, $publish, $category, $categoryId, $pId);
                $this->dbF->setRow($sql, $array, false);

                $this->db->commit();
                if ($this->dbF->rowCount > 0) {
                    $this->functions->notificationError(_uc($_e['FAQS']), ($_e['FAQ Update Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Update']), _uc($_e['Training']), $lastId, ($_e['FAQ Update Successfully']));
                } else {
                    $this->functions->notificationError(_uc($_e['FAQS']), ($_e['FAQ Update Failed']), 'btn-danger');
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['FAQS']), ($_e['FAQ Update Failed']), 'btn-danger');
            }
        }
    }

    public function faqNew()
    {
        global $_e;
        $this->faqEdit(true);
    }

    public function eventUsers()
    {
        $sql = "SELECT * FROM accounts_user WHERE  acc_type = '1' ORDER BY `acc_name`";
        $data = $this->dbF->getRows($sql);
        $opt = '';
        foreach ($data as $val) {
            $mail = $val['acc_email'];
            $heading = $val['acc_name'];
            $opt .= '<option value="' . $val['acc_id'] . '">' . htmlentities($heading) . ' -- ' . $mail . '</option>';
        }
        return $opt;
    }

    public function faqEdit($new = false)
    {
        global $_e;
        if ($new) {
            $token = $this->functions->setFormToken('newfaq', false);
        } else {
            $id = $_GET['faqId'];
            $sql = "SELECT * FROM `faqs` where id = '$id' ";
            $data = $this->dbF->getRow($sql);

            $token = $this->functions->setFormToken('editfaq', false);
            $token .= '<input type="hidden" name="editId" value="' . $id . '"/>';
        }

        $size = $this->functions->developer_setting('file_size');
        //No need to remove any thing,, go in developer setting table and set 0

        echo '<form method="post" action="-faq?page=faq" class="form-horizontal" role="form" enctype="multipart/form-data">' .
            $token .
            '
            <div class="form-horizontal">';

        @$title = $data['title'];
        @$publish = $data['publish'];
        @$category = $data['category'];
        @$dsc = $data['dsc'];

        $faqCategories = $this->functions->ibms_setting('faqCategory');
        
        $cat_for_fields_array = explode(',', $faqCategories["setting_val"]);

        //Title
        echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Title']) . '</label>
                        <div class="col-sm-10  col-md-9">
                            <input type="text" name="title" value="' . @$title . '" class="form-control" placeholder="' . _uc($_e['Title']) . '" required>
                        </div>
                    </div>';

        echo '<div class="form-group categorySelect" style="display: none;">
                <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Select Service']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <select name="categoryId" class="form-control">';
                        $sql = "SELECT * FROM `service` WHERE `publish` = ?";
                        $categories = $this->dbF->getRows($sql, [1]);

                        foreach ($categories as $category) {
                            $categoryId = $category["id"];
                            $categoryName = getTextFromSerializeArray($category["heading"]);

                            echo '<option value="' . $categoryId . '">' . $categoryName . '</option>';
                        }
                    echo '</select></div>
                </div>';

        echo '<div class="form-group productSelect" style="display: none;">
                <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Select Product']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <select name="pId" class="form-control">';
                        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val` FROM `proudct_detail` JOIN `product_setting` ON 
                        `proudct_detail`.`prodet_id` = `product_setting`.`p_id` WHERE `product_setting`.`setting_name` = 'publicAccess' 
                        AND `product_setting`.`setting_val`='1' AND `proudct_detail`.`product_update`='1' ORDER BY `proudct_detail`.`prodet_id` DESC";
                        $products = $this->dbF->getRows($sql);

                        foreach ($products as $product) {
                            $productId = $product["prodet_id"];
                            $proName = getTextFromSerializeArray($product["prodet_name"]);

                            echo '<option value="' . $productId . '">' . $proName . '</option>';
                        }
                    echo '</select></div>
                </div>';

        echo '<div class="form-group">
                           <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Detail']) . '</label>
                              <div class="col-sm-10  col-md-9">
                                 <textarea name="dsc" id="dsc" placeholder="' . _uc($_e['Enter Full Detail']) . '" class="ckeditor">' . $dsc . '</textarea>
                                 </div>
                             </div>';




        //Publish
        $checked = "";
        if (@$publish == '1') {
            $checked = 'checked';
        }
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Publish']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">
                            <input type="checkbox" name="publish" value="1" ' . $checked . '>
                        </div>
                    </div>
               </div>';

        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _uc($_e['SAVE']) . '</button>';

        echo "</div>
             </form>";
    }
}
