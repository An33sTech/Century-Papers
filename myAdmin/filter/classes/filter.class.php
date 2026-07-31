<?php
require_once(__DIR__ . "/../../global.php"); //connection setting db

class filter extends object_class
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
        //Index
        $_w['Filter Management'] = '';
        //news.php
        $_w['Active Filters'] = '';
        $_w['Filters'] = '';
        $_w['Add New Filter'] = '';
        $_w['Delete Fail Please Try Again.'] = '';

        //This Class
        $_w['SNO'] = '';
        $_w['TITLE'] = '';
        $_w['UPDATE'] = '';
        $_w['ACTION'] = '';
        $_w['Filter'] = '';
        $_w['Filter Save Successfully'] = '';
        $_w['Added'] = '';
        $_w['Filter Save Failed'] = '';

        $_w['SAVE'] = '';
        $_w['Publish'] = '';
        $_w['Draft'] = '';

        $_w['Date'] = '';
        $_w['Filter Name'] = '';


        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Filter Management');
    }

    public function filterView()
    {
        $sql = "SELECT * FROM filters WHERE publish = 1";
        $data = $this->dbF->getRows($sql);
        $this->print_filter_table($data);
    }

    public function filterDraft()
    {
        $sql = "SELECT * FROM filters WHERE publish = 0";
        $data = $this->dbF->getRows($sql);
        $this->print_filter_table($data);
    }

    private function print_filter_table($data)
    {
        $data = empty($data) ? array() : $data;
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                        <th>' . _u($_e['TITLE']) . '</th>
                        <th>' . _u($_e['UPDATE']) . '</th>
                        <th>' . _u($_e['ACTION']) . '</th>
                    </thead>
                <tbody>';

        $i = 0;
        $defaultLang = $this->functions->AdminDefaultLanguage();
        foreach ($data as $val) {
            $i++;
            $id = $val['id'];
            $heading = $val['name'];
            echo "<tr>
                    <td>$i</td>
                    <td>$heading</td>
                    <td>$val[dateTime]</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-filter?page=edit&pageId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteFilter(this);' class='btn'>
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

    public function filterAdd()
    {

        global $_e;
        if (isset($_POST['submit'])) {
            if (!$this->functions->getFormToken('newFilter')) {
                return false;
            }

            try {
                $this->db->beginTransaction();
                if (!empty($_POST["size"]) && !empty($_POST["heading"])) {
                    $name = $_POST["heading"];
                    $publish = empty($_POST['publish']) ? "0" : $_POST['publish'];
                    $sizeNames = $_POST["size"];
                    $sizes_json = json_encode($sizeNames);
                    $sql = "INSERT INTO `filters` (`name`, `sizes`) VALUES (?, ?)";
                    $array = [$name, $sizes_json];
                    $data = $this->dbF->setRow($sql, $array);
                }
                $this->db->commit();
                if ($this->dbF->rowCount > 0) {
                    $this->functions->notificationError(_uc($_e['Filter']), ($_e['Filter Save Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Added']), _uc($_e['Filter']), $this->dbF->rowLastId, ($_e['Filter Save Successfully']));
                } else {
                    $this->functions->notificationError(_uc($_e['Filter']), ($_e['Filter Save Failed.']), 'btn-danger');
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Filter']), ($_e['Filter Save Failed.']), 'btn-danger');
            }
        } // If end
    }

    public function filterNew()
    {
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) FROM product_size ORDER BY prosiz_name ASC";
        $sizes = $this->dbF->getRows($sql);

        $token = $this->functions->setFormToken('newFilter', false);
        echo '<form method="post" class="form-horizontal" role="form">' .
            $token .
            '<div class="form-horizontal">';
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['TITLE']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="heading"  maxlength="150" required class="form-control" placeholder="' . _uc($_e['Filter Name']) . '">
                    </div>
                </div>';
        echo '<h2 class="tab_heading">Sizes</h2>';
        echo '<div class="form-group"><div class="col-md-12">';
        $counter = 0;
        echo '<div class="row">';

        foreach ($sizes as $val) {
            $size = $val["prosiz_name"];

            if ($counter % 8 == 0 && $counter > 0) {
                echo '</div><div class="row">';
            }

            echo '<div class="col-md-3">';
            echo '<input type="checkbox" class="form-check" name="size[]" value="' . $size . '"> ' . $size;
            echo '</div>';

            $counter++;
        }

        echo '</div>';
        echo '</div></div>';
        echo '<div class="form-group">
                            <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Publish']) . '</label>
                            <div class="col-sm-10  col-md-9">
                                <div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">
                                    <input type="checkbox" name="publish" value="1">
                                </div>
                            </div>
                       </div>';
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _u($_e['SAVE']) . '</button>';
        echo '</div>
        </form>';
    }

    public function filterEdit()
    {
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) FROM product_size ORDER BY prosiz_name ASC";
        $sizes = $this->dbF->getRows($sql);
        $token = $this->functions->setFormToken('editFilter', false);
        $id = $_GET['pageId'];
        $sql = "SELECT * FROM filters WHERE id = '$id'";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount == 0) {
            echo "News Not Found For Update";
            return false;
        }

        echo '<form method="post" action="-filter?page=filter" class="form-horizontal" role="form">' .
            $token .
            '<input type="hidden" name="editId" value="' . $id . '"/>
            <div class="form-horizontal">';
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['TITLE']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" name="heading"  maxlength="150" required value="' . $data["name"] . '" class="form-control" placeholder="' . _uc($_e['Filter Name']) . '">
                    </div>
                </div>';
        echo '<h2 class="tab_heading">Sizes</h2>';
        echo '<div class="form-group"><div class="col-md-12">';
        $counter = 0;
        echo '<div class="row">';
        $selectedSizes = json_decode($data['sizes'], true);
        foreach ($sizes as $val) {
            $size = $val["prosiz_name"];
            $checked = '';
            if (is_array($selectedSizes) && in_array($size, $selectedSizes)) {
                $checked = 'checked';
            }
            if ($counter % 8 == 0 && $counter > 0) {
                echo '</div><div class="row">';
            }

            echo '<div class="col-md-3">';
            echo '<input type="checkbox" class="form-check" name="size[]" value="' . $size . '" ' . $checked . '> ' . $size;
            echo '</div>';

            $counter++;
        }

        echo '</div>';
        echo '</div></div>';
        $checked = "";
        if ($data['publish'] == '1') {
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
        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _u($_e['SAVE']) . '</button>';
        echo '</div>
        </form>';
    }

    public function filterEditSubmit()
    {
        global $_e;
        if (isset($_POST['submit']) && isset($_POST['editId'])) {
            if (!$this->functions->getFormToken('editFilter')) {
                return false;
            }

            $name = $_POST["heading"];
            $publish = empty($_POST['publish']) ? "0" : $_POST['publish'];
            $sizeNames = $_POST["size"];
            $sizes_json = json_encode($sizeNames);
            try {
                $this->db->beginTransaction();
                $lastId = $_POST['editId'];

                $sql = "UPDATE `filters` SET `name` = ?, `sizes` = ?, `publish` = ? WHERE id = ?";

                $array = array($name, $sizes_json, $publish, $lastId);

                $this->dbF->setRow($sql, $array, false);

                $this->db->commit();

                if ($this->dbF->rowCount > 0) {
                    $this->functions->notificationError(_uc($_e['Filter']), _uc($_e['Filter Save Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['UPDATE']), _uc($_e['Filter']), $this->dbF->rowLastId, _uc($_e['Filter Save Successfully']));
                } else {
                    $this->functions->notificationError(_uc($_e['Filter']), _uc($_e['Filter Save Failed']), 'btn-danger');
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Filter']), _uc($_e['Filter Save Failed']), 'btn-danger');
            }
        }
    }
}
