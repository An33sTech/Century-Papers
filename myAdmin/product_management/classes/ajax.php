<?php
require_once(__DIR__ . "/../../global_ajax.php"); //connection setting db

class ajax extends object_class
{
    private $colorC;

    private $scaleC;

    private $var_del;

    private $var_edit;

    private $var_edit_fromName;



    public $product;



    public function __construct()
    {

        parent::__construct('3');



        $page = $_GET['page'];


        if (
            $page == 'colorAjax_edit' || $page == 'AjaxUpdate_color' ||

            $page == 'colorAjax_del' || $page == 'AjaxAfterUpdateScript_color'
        ) {

            $this->color();
        } else if (
            $page == 'scaleAjax_edit' || $page == 'AjaxUpdate_scale' ||

            $page == 'scaleAjax_del' || $page == 'AjaxAfterUpdateScript_scale'
        ) {

            $this->scale();
        }


        if (isset($GLOBALS['productF']))
            $this->product = $GLOBALS['productF'];
        else {

            require_once(__DIR__ . "/../../product/classes/product.class.php");

            $this->product = new product();
        }





        /**

         * MultiLanguage keys Use where echo;

         * define this class words and where this class will call

         * and define words of file where this class will called

         **/

        global $_e;

        global $adminPanelLanguage;

        $_w = array();

        //This Class

        $_w['Add Slot'] = '';

        $_w['Delete'] = '';

        $_w['Color Name'] = '';

        $_w['Scale Name'] = '';

        $_w["Store Is Not Empty.\n Please Delete Store`s Product First."] = '';

        $_w['Store In Use'] = '';

        $_w['Store Description'] = '';

        $_w['Select Country'] = '';

        $_w['Store Country'] = '';

        $_w['Store City'] = '';

        $_w['Store Name'] = '';

        $_w['Store Officer Name'] = '';



        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Product ajax');
    }



    private function color()
    {



        require_once(__DIR__ . "/color.class.php");

        $this->colorC = new colors();



        $this->var_del = $this->colorC->var_del;

        $this->var_edit = $this->colorC->var_edit;

        $this->var_edit_fromName = $this->colorC->var_edit_fromName;
    }



    private function scale()
    {

        require_once(__DIR__ . "/scale.class.php");

        $this->scaleC = new scales();



        $this->var_del = $this->scaleC->var_del;

        $this->var_edit = $this->scaleC->var_edit;

        $this->var_edit_fromName = $this->scaleC->var_edit_fromName;
    }
    public function productNameEdit()
    {
        $pId = intval($_GET['pId']);
        $qry = "SELECT * FROM `proudct_detail` WHERE `prodet_id` = '$pId'";

        $temp = array();
        $editData = $this->dbF->getRow($qry);
        $temp['name'] = unserialize($editData['prodet_name']);
        $temp['short_desc'] = unserialize($editData['prodet_shortDesc']);


        $sql = "SELECT * FROM `product_setting` WHERE `p_id` = '$pId' AND `setting_name` = 'ldesc'";
        $detail_desc = $this->dbF->getRow($sql);

        $temp['detail_desc'] = unserialize($detail_desc['setting_val']);

        $sql1 = "SELECT * FROM `product_setting` WHERE `p_id` = '$pId' AND `setting_name`  = 'size_chart'";
        $size_chart = $this->dbF->getRow($sql1);

        $temp['size_chart'] = unserialize($size_chart['setting_val']);


        echo json_encode($temp);
    }

    public function productUpdate()
    {
        $pId = intval($_GET['pId']);
        //   echo $_POST['arrayData'];
        //   $qry = "SELECT * FROM `proudct_detail` WHERE `prodet_id` = '$pId'";

        //     $editData = $this->dbF->getRow($qry);
        // $this->dbF->prnt($_POST['arrayData']);
        // exit;
        $editPName = serialize($_POST['arrayData']['pName']);
        $editPshortDesc = serialize($_POST['arrayData']['pShortDesc']);
        $editPdetailDesc = serialize($_POST['arrayData']['pDetailDesc']);
        $editPchartSize = serialize($_POST['arrayData']['pSizeChart']);

        $sql = "UPDATE `proudct_detail` SET `prodet_name` =  ?, `prodet_shortDesc` =  ? WHERE `prodet_id` = ? ";
        $arry = array($editPName, $editPshortDesc, $pId);
        $this->dbF->setRow($sql, $arry);

        $sql1 = "UPDATE `product_setting` SET `setting_val` = ? WHERE `p_id` = ? AND `setting_name` = ?";
        $arry1 = array($editPdetailDesc, $pId, 'ldesc');
        $this->dbF->setRow($sql1, $arry1);

        $sql2 = "UPDATE `product_setting` SET `setting_val` = ? WHERE `p_id` = ? AND `setting_name` = ?";
        $arry2 = array($editPchartSize, $pId, 'size_chart');
        $this->dbF->setRow($sql2, $arry2);


        //  if($this->dbF->rowCount > 0){
        //      echo "1";
        //  }

    }


    public function processEdit($page)
    {

        $id = intval($_GET['id']);

        switch ($page) {

            case 'color':

                $this->createEditFormColor($id); //Color

                break;

            case 'scale':

                $this->createEditFormScale($id); //scale

                break;
        }
    }



    private function createEditFormColor($id)
    {

        global $_e;

        $data = $this->colorC->getDataSQL($id);



        $name = $data[0]['name'];

        $colors = $data[0]['color'];





        $i = 0;

        $trs = "";



        foreach ($colors as $color) {

            $i++;

            $trs .= "

                <tr>

                    <td> $i) </td>

                    <td>

                     <div class='col-xs-8'>

                        <input type='text' style='border-color: #$color[color_name]; border-width: 3px; '

                         class='inp color_picker form-control'

                         name='$this->var_edit_fromName[color][$color[color_id]]'

                         value='$color[color_name]' >

                     </div>

                     <div class='checkbox col-xs-4'>

                        <label><input type='checkbox' name='$this->var_edit_fromName[colorDel][]' value='$color[color_id]'  >" . _uc($_e['Delete']) . "</label>

                     </div>

                    </td>

                </tr>

            ";
        }



        echo '

        <input type="hidden" name="' . $this->var_edit_fromName . '[id]" id="color_edit_id" value="' . $name['colorName_id'] . '">



        ' . _uc($_e['Color Name']) . ' : <input type="text" autocomplete="off" id="color_name" class="inp " name="' . $this->var_edit_fromName . '[name]" value="' . $name['colorName_name'] . '">

        <br><br>



       <table id="slot_table2" class="table slot_table">

                        <tbody>

                        ' . $trs . '

                        </tbody>

                    </table>

                    <button type="button" class="btn btn-primary" onclick="addSlot2(); return false;">

                        <i class="icon_bs-plus"></i> ' . _uc($_e['Add Slot']) . '

                    </button>

                    <script>

                    color_picker();

                    </script>';
    }





    private function createEditFormScale($id)
    {

        global $_e;

        $data = $this->scaleC->getDataSQL($id);



        $name = $data[0]['name'];

        $scales = $data[0]['scale'];





        $i = 0;

        $trs = "";

        foreach ($scales as $scale) {

            $i++;

            $trs .= "

                <tr>

                    <td> $i) </td>

                    <td>

                    <div class='col-xs-8'>

                        <input type='text' class='inp form-control' name='$this->var_edit_fromName[scale][$scale[scale_id]]' value='$scale[scale_name]' >

                     </div>



                     <div class='checkbox col-xs-4'>

                        <label><input type='checkbox' name='$this->var_edit_fromName[scaleDel][]' value='$scale[scale_id]'>" . _uc($_e['Delete']) . "</label>

                     </div>

                     </td>

                </tr>

            ";
        }



        echo '

        <input type="hidden" name="' . $this->var_edit_fromName . '[id]" id="scale_edit_id" value="' . $name['scaleName_id'] . '">



        ' . _uc($_e['Scale Name']) . ' : <input type="text" autocomplete="off" id="scale_name" class="inp" name="' . $this->var_edit_fromName . '[name]" value="' . $name['scaleName_name'] . '">



        <br /><br />



        <table id="slot_table2" class="table slot_table">

            <tbody>

            ' . $trs . '

            </tbody>

        </table>



        <button type="button" class="btn btn-primary" onclick="addSlot2(); return false;">

            <i class="icon_bs-plus"></i> ' . _uc($_e['Add Slot']) . '

        </button>';
    }





    public function AjaxAfterUpdateScript_color()
    {

        $id = $_GET['id'];

        $sql_2 = "SELECT * FROM `colors` WHERE `color_name_id` = '$id' ";

        $data = $this->dbF->getRows($sql_2);



        if ($this->dbF->rowCount > 0) {

            foreach ($data as $key => $val) {

                echo "<div class='colorBox' style='background-color:#" . $val['color_name'] . "' ></div>";
            }
        }
    }





    public function AjaxUpdate_color()
    {

        if (isset($_POST[$this->var_edit_fromName])) {

            $form = $_POST[$this->var_edit_fromName];

            $this->updateColorSQL($form);
        }
    }



    private function updateColorSQL($form)
    {

        $id = intval($form['id']);

        @$colors = $form['color'];

        @$name = $form['name'];

        if ($name == "") {

            echo '0';

            exit;
        }



        $sql = "UPDATE `color_name` SET `colorName_name` =  ? WHERE `colorName_id` = ? ";

        $arry = array($name, $id);

        $this->dbF->setRow($sql, $arry);



        if (is_array($colors) && $id > 0) {



            $sql = "INSERT INTO `colors` (`color_id`,`color_name`,`color_name_id`) VALUES (?,?,?)

                        ON DUPLICATE KEY

                        UPDATE `color_name`= ? ";



            foreach ($colors as $key => $color) {

                $key = intval($key);

                $color_name = $color;

                if (!isset($form['colorDel']) || !in_array(intval($key), $form['colorDel'])) {

                    $arry = array($key, $color_name, $id, $color_name);

                    $this->dbF->setRow($sql, $arry);
                }
            }



            if (isset($form['colorDel']) && is_array($form['colorDel'])) {

                $ids = "";

                foreach ($form['colorDel'] as $del_id) {

                    $ids .= intval($del_id) . ",";
                }

                $ids = trim($ids, ",");

                $sql = "DELETE FROM `colors` WHERE `color_id` IN ($ids) ";

                $qry = $this->dbF->setRow($sql);
            }
        }



        echo "1";
    }





    public function AjaxDelScript_color()
    {

        @$id = intval($_POST['itemId']);

        $sql = "DELETE FROM `color_name` WHERE `colorName_id` = '$id' ";

        $this->dbF->setRow($sql);

        if ($this->dbF->rowCount > 0) {

            echo '1';
        } else {

            echo '0';
        }
    }



    public function AjaxDelScript_scale()
    {
        @$id = intval($_POST['itemId']);
        $sql = "DELETE FROM `scale_name` WHERE `scaleName_id` = '$id' ";
        $this->dbF->setRow($sql);

        if ($this->dbF->rowCount > 0) {
            echo '1';
        } else {
            echo '0';
        }
    }





    public function AjaxUpdate_scale()
    {

        if (isset($_POST[$this->var_edit_fromName])) {

            $form = $_POST[$this->var_edit_fromName];

            $this->updateScaleSQL($form);
        }
    }



    private function updateScaleSQL($form)
    {

        try {

            $this->db->beginTransaction();



            $id = intval($form['id']);

            @$scales = $form['scale'];

            @$name = $form['name'];



            $sql = "UPDATE `scale_name` SET `scaleName_name` =  ? WHERE `scaleName_id` = ? ";

            $arry = array($name, $id);

            $this->dbF->setRow($sql, $arry, false);



            if (is_array($scales) && $id > 0) {



                $sql = "INSERT INTO `scales` (`scale_id`,`scale_name`,`scale_name_id`) VALUES (?,?,?)

                        ON DUPLICATE KEY

                        UPDATE `scale_name`= ? ";



                foreach ($scales as $sid => $scale) {

                    $sid = intval($sid);

                    $scale_name = $scale;

                    $sm_id = $id;

                    if (!isset($form['scaleDel']) || !in_array(intval($sid), $form['scaleDel'])) {

                        $arry = array($sid, $scale_name, $sm_id, $scale_name);

                        $this->dbF->setRow($sql, $arry, false);
                    }
                }



                if (isset($form['scaleDel']) && is_array($form['scaleDel'])) {

                    $ids = "";

                    foreach ($form['scaleDel'] as $del_id) {

                        $ids .= intval($del_id) . ",";
                    }

                    $ids = trim($ids, ",");

                    $sql = "DELETE FROM `scales` WHERE `scale_id` IN ($ids) ";

                    $qry = $this->dbF->setRow($sql, false);
                }
            }



            $this->db->commit();

            echo '1';
        } catch (Exception $e) {

            echo '0';

            $this->dbF->error_submit($e);

            $this->db->rollBack();
        }
    }



    public function AjaxAfterUpdateScript_scale()
    {

        $id = $_GET['id'];

        $sql_2 = "SELECT * FROM `scales` WHERE `scale_name_id` = '$id' ";

        $data = $this->dbF->getRows($sql_2);



        $temp = '';

        if ($this->dbF->rowCount > 0) {

            foreach ($data as $key => $val) {

                $temp .= $val['scale_name'] . ', ';
            }

            $temp = trim($temp);

            echo trim($temp, ',');
        }
    }









    public function AjaxUpdate_currency()
    {

        $form_array_prefix = 'edit_currency_form';

        if (isset($_POST[$form_array_prefix])) {

            $form = $_POST[$form_array_prefix];

            if (

                isset($form['country']) && !empty($form['country'])

                && isset($form['cid']) && !empty($form['cid'])

                && isset($form['currency']) && !empty($form['currency'])

                && isset($form['symbol']) && !empty($form['symbol'])

            ) {

                $sql = "UPDATE `currency` SET

                            `cur_country` = ?,

                            `cur_name` = ?,

                            `cur_symbol` = ?

                            WHERE `cur_id` = ?";

                $arry = array($form['country'], $form['currency'], $form['symbol'], $form['cid']);

                $this->dbF->setRow($sql, $arry);

                echo '1';
            }
        }
    }



    public function AjaxAfterUpdateScript_currency()
    {

        $id = $_GET['id'];

        $data = $this->dbF->getRow("SELECT * FROM `currency` WHERE `cur_id`='$id'");

        if ($this->dbF->rowCount > 0) {

            $con = $this->functions->countrylist()[$data['cur_country']];

            echo '<td>' . $con . '</td>

                <td>' . $data['cur_name'] . '</td>

                <td>' . $data['cur_symbol'] . '</td>

                <td>



                <div class="btn-group btn-group-sm">

                  <a data-toggle="modal" href="#currencyEditModal" onclick="formEditInit(\'' . $data['cur_id'] . '\',\'' . $data['cur_country'] . '\',\'' . $data['cur_name'] . '\',\'' . $data['cur_symbol'] . '\')"  class="btn"><i class="glyphicon glyphicon-edit"></i></a>

                  <a data-id="' . $data['cur_id'] . '" onclick="AjaxDelScript(this);" class="btn secure_delete">

                    <i class="glyphicon glyphicon-trash trash"></i>

                    <i class="fa fa-refresh waiting fa-spin" style="display: none"></i>

                  </a>

                </div>

                </td>';
        }
    }





    public function AjaxDelScript_currency()
    {

        $id = intval($_GET['id']);

        $sql = "DELETE FROM `currency` WHERE `cur_id`= '$id'";


        $this->dbF->setRow($sql);

        if ($this->dbF->rowCount > 0) {

            echo '1';
        } else {

            echo '0';
        }
    }



    public function AjaxDelScript_product()
    {

        try {

            $this->db->beginTransaction();

            @$id = intval($_POST['itemId']);





            $sql3 = "SELECT * FROM `product_image` WHERE `product_id`='$id'";

            $data = $this->dbF->getRows($sql3, false);

            foreach ($data as $key => $val) {

                $this->functions->deleteOldSingleImage($val['image']);
            }

            $sql3 = "DELETE FROM `product_image` WHERE `product_id`='$id'";

            $this->dbF->setRow($sql3, false);



            $sql3 = "DELETE FROM `proudct_detail` WHERE `prodet_id`='$id'";

            $this->dbF->setRow($sql3);



            if ($this->dbF->rowCount > 0) {

                echo '1';
            } else {

                echo '0';
            }

            $this->db->commit();
        } catch (Exception $e) {

            echo '0';

            $this->db->rollBack();

            $this->dbF->error_submit($e);
        }
    }





    public function AjaxDelScript_productSelected()
    {

        try {

            $ids = $_POST['id'];

            $this->db->beginTransaction();

            $ids = explode(",", $ids);

            for ($i = 0; $i < sizeof($ids); $i++) {

                $id = $ids[$i];



                $sql3 = "SELECT * FROM `product_image` WHERE `product_id`='$id'";

                $data = $this->dbF->getRows($sql3, false);

                foreach ($data as $key => $val) {

                    $this->functions->deleteOldSingleImage($val['image']);
                }

                $sql3 = "DELETE FROM `product_image` WHERE `product_id`='$id'";

                $this->dbF->setRow($sql3, false);



                $sql3 = "DELETE FROM `proudct_detail` WHERE `prodet_id`='$id'";

                $this->dbF->setRow($sql3);
            }





            $this->db->commit();

            echo "1";
        } catch (Exception $e) {

            echo "0";

            $this->db->rollBack();

            $this->dbF->error_submit($e);
        }
    }



    public function AjaxDelScript_productImageDel()
    {

        $id = $_POST['imageId'];



        $sql3 = "SELECT * FROM `product_image` WHERE `img_id`='$id'";

        $data = $this->dbF->getRow($sql3);



        unlink(__DIR__ . "/../../../images/$data[image]");


        $sql3 = "DELETE FROM `product_image` WHERE `img_id`='$id'";

        $this->dbF->setRow($sql3);



        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }

    public function productEditDetailImageDel()
    {

        $id = $_POST['imageId'];



        $sql3 = "SELECT * FROM `product_detail_image` WHERE `img_id`='$id'";

        $data = $this->dbF->getRow($sql3);



        unlink(__DIR__ . "/../../../images/$data[image]");


        $sql3 = "DELETE FROM `product_detail_image` WHERE `img_id`='$id'";

        $this->dbF->setRow($sql3);
        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }

    }



    function AjaxDelScript_storeDel()
    {

        global $_e;

        $id = $_POST['itemId'];



        $sql = "SELECT * FROM  `product_inventory`  WHERE `qty_store_id`='$id' AND `qty_item`>'0'";

        $this->dbF->getRows($sql);

        if ($this->dbF->rowCount > 0) {

            echo "<script>jAlert('" . _js($_e["Store Is Not Empty.\n Please Delete Store`s Product First."]) . "','" . _js($_e['Store In Use']) . "');</script>";
        } else {

            $sql3 = "DELETE FROM `store_name` WHERE `store_pk`='$id'";

            $this->dbF->setRow($sql3);



            if ($this->dbF->rowCount > 0) {

                echo "1";
            } else {

                echo "0";
            }
        }
    }



    function AjaxDelScript_receiptDel()
    {

        $id = $_POST['itemId'];



        $sql3 = "DELETE FROM `purchase_receipt` WHERE `receipt_pk`='$id'";

        $this->dbF->setRow($sql3);



        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }



    public function AjaxEditStore()
    {

        global $_e;

        $id = $_GET['id'];

        $sql = "SELECT * FROM `store_name` WHERE `store_pk` = '$id' ";

        $data = $this->dbF->getRow($sql);

        $country_list = $this->functions->countrySelectOption();



        echo '<div class="form-horizontal">

                <div class="form-group">

                    <label for="input1" class="col-sm-4 control-label">' . _uc($_e['Store Officer Name']) . '</label>

                    <div class="col-sm-8">

                        <input type="hidden" value="' . $data['store_pk'] . '" id="store_edit_id" name="storeId" />

                        <input type="text" value="' . $data['store_owner'] . '" name="storeOfficer" class="form-control" required id="input2" placeholder="' . _uc($_e['Store Officer Name']) . '">

                    </div>

                </div>



                <div class="form-group">

                    <label for="input3" class="col-sm-4 control-label">' . _uc($_e['Store Name']) . '</label>

                    <div class="col-sm-8">

                        <input type="text" value="' . $data['store_name'] . '"  name="storeName" class="form-control" required id="input3" placeholder="' . _uc($_e['Store Name']) . '">

                    </div>

                </div>



                <div class="form-group">

                    <label for="input2" class="col-sm-4 control-label">' . _uc($_e['Store City']) . '</label>

                    <div class="col-sm-8">

                        <input type="text" value="' . $data['store_location'] . '" name="storeLocation" class="form-control" required id="input2" placeholder="' . _uc($_e['Store City']) . '">

                    </div>

                </div>



                <div class="form-group">

                    <label for="input2" class="col-sm-4 control-label">' . _uc($_e['Store Country']) . '</label>

                    <div class="col-sm-8">

                    <select name="storCountry" id="storCountry" class="form-control" required="required">

                        <option value="">' . _uc($_e['Select Country']) . '</option>

                            ' . $country_list . '

                        </select>

                        <script>

                        $(document).ready(function(){

                            $("#storCountry").val("' . $data['store_country'] . '").change();

                        });

                        </script>

                    </div>

                </div>



                <div class="form-group">

                    <label for="input4" class="col-sm-4 control-label">' . _uc($_e['Store Description']) . '</label>

                    <div class="col-sm-8">

                        <textarea  name="storeDesc" class="form-control" rows="3" id="input4" placeholder="' . _uc($_e['Store Description']) . '">' . $data['store_desc'] . '</textarea>

                    </div>

                </div>



                </div>';
    }



    public function AjaxEditRequestStore()
    {

        if (
            isset($_POST['storeOfficer']) && isset($_POST['storeLocation']) && isset($_POST['storeName']) &&

            !empty($_POST['storeOfficer']) && !empty($_POST['storeLocation']) && !empty($_POST['storeName']) && !empty($_POST['storCountry'])

        ) {

            $id = $_POST['storeId'];



            $sql = "UPDATE `store_name` SET

                    `store_owner`=?,

                    `store_location`=?,

                    `store_country`=?,

                    `store_name`=?,

                    `store_desc`=?

                    WHERE `store_pk` = ?";

            $arry = array($_POST['storeOfficer'], $_POST['storeLocation'], $_POST['storCountry'], $_POST['storeName'], $_POST['storeDesc'], $id);



            $this->dbF->setRow($sql, $arry);

            if ($this->dbF->rowCount > 0)
                echo '1';
            else
                echo '0';
        } else {

            echo '0';
        }
    }





    public function AjaxAfterUpdateScript_store()
    {

        $id = $_GET['id'];

        $sql = "SELECT * FROM `store_name` WHERE `store_pk` = '$id' ";

        $val = $this->dbF->getRow($sql);

        echo "

                    <td>*</td>

                    <td>$val[store_owner]</td>

                    <td>$val[store_name]</td>

                    <td>$val[store_location] - $val[store_country]</td>

                    <td>$val[store_desc]</td>

                    <td><div class='btn-group btn-group-sm'>

                        <a data-id='$id'  data-target='#storeEditModal' onclick='AjaxEditScript(this);' class='btn _storeEdit'><i class='glyphicon glyphicon-edit '></i></a>



                         <a data-id='$id' onclick='AjaxDelScript(this);' class='btn'>

                                 <i class='glyphicon glyphicon-trash trash'></i>

                                 <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                         </a>

                         </div>

                    </td>

                ";
    }



    public function AjaxDelScript_discountDel()
    {

        $id = $_POST['id'];

        $sql = "DELETE FROM `product_discount` WHERE product_discount_pk = '$id'";

        $this->dbF->setRow($sql);

        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }

    public function AjaxDelScript_holeSaleDel()
    {

        $id = $_POST['id'];

        $sql = "DELETE FROM `product_sale` WHERE pSale_pk = '$id'";

        $this->dbF->setRow($sql);

        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }

    public function AjaxDelScript_couponDel()
    {

        $id = $_POST['id'];

        $sql = "DELETE FROM `product_coupon` WHERE pCoupon_pk = '$id'";

        $this->dbF->setRow($sql);

        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }





    public function sortProductImage()
    {

        $list = $_POST['image'];

        for ($i = 0; $i < count($list); $i++) {

            $sql3 = "UPDATE `product_image` SET sort = ? WHERE `img_id` = ?";

            $data = $this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }


    public function sortProductDetailImage()
    {

        $list = $_POST['image'];

        for ($i = 0; $i < count($list); $i++) {

            $sql3 = "UPDATE `product_detail_image` SET sort = ? WHERE `img_id` = ?";

            $data = $this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }




    public function pImageAltUpdate()
    {

        $id = $_POST['imageId'];

        $alt = $_POST['altT'];

        $sql3 = "UPDATE `product_image` SET alt = ? WHERE `img_id` = ?";

        $array = array($alt, $id);

        $data = $this->dbF->setRow($sql3, $array);

        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }

    public function pDetailImageAltUpdate()
    {

        $id = $_POST['imageId'];

        $alt = serialize($_POST['altT']);

        $desc = serialize($_POST['desc']);

        $sql3 = "UPDATE `product_detail_image` SET `alt`= ?,  `desc`=?  WHERE `img_id` = ?";

        $array = array($alt, $desc, $id);

        $data = $this->dbF->setRow($sql3, $array);

        if ($this->dbF->rowCount > 0) {

            echo "1";
        } else {

            echo "0";
        }
    }


    public function sortProducts()
    {

        $list = $_POST['sort'];

        for ($i = 0; $i < count($list); $i++) {

            $sql3 = "UPDATE `proudct_detail` SET sort = ? WHERE `prodet_id` = ?";

            $data = $this->dbF->setRow($sql3, array($i, $list[$i]));
        }
    }





    public function featureItem()
    {

        global $_e;

        $id = $_POST['id'];

        $val = $_POST['val'];



        $sql2 = "UPDATE proudct_detail set feature = ? WHERE prodet_id = ?";

        $this->dbF->setRow($sql2, array($val, $id), false);

        if ($this->dbF->rowCount)
            echo '1';
        else
            echo '0';
    }



    public function sortProductSize()
    {
        $list = $_POST['size'];
        
        $i = 1;
        foreach ($list as $key => $val) {
            $list_ex = explode(':', $val);

            $size_id = $list_ex[0];
            $pro_id = $list_ex[1];

            $sql2 = "SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id`= $size_id AND `prosiz_prodet_id` = $pro_id";
            $data2 = $this->dbF->getRow($sql2);
            
            $size_name = @$data2['prosiz_name'];

            $sql3 = "UPDATE `product_size` SET sort=? WHERE `prosiz_name`= ? AND `prosiz_prodet_id` = ?";

            $data = $this->dbF->setRow($sql3, array($i, $size_name, $pro_id));
            $i++;
        }
    }

    public function sortProductSizeOld()
    {



        $list = $_POST;

        $count_list = array_keys($list);

        $j = 1;
        foreach ($count_list as $key => $val) {

            for ($i = 0; $i < count($list[$val]); $i++) {

                $list_ex = explode(':', $list[$val][$i]);


                $suf_ex = explode('_', $val);


                $size_name = isset($suf_ex[1]) ? $suf_ex[1] . '-' . $list_ex[0] : $list_ex[0];

                $pro_id = $list_ex[1];


                $sql3 = "UPDATE `product_size` SET sort=? WHERE `prosiz_name`= ? AND `prosiz_prodet_id` = ? ";
                $j++;

                $data = $this->dbF->setRow($sql3, array($j, $size_name, $pro_id));
            }
        }
    }



    public function fetch_products()
    {
        global $_e, $functions;

        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $length = (isset($_POST['length'])) ? $_POST['length'] : 10;
        $draw = (isset($_POST['draw'])) ? (int) $_POST['draw'] : null;
        $search = (isset($_POST['search'])) ? ($_POST['search']['value']) : null;
        $orderBy = (isset($_POST['order'][0]['column'])) ? ($_POST['order'][0]['column']) : null;
        $orderDir = (isset($_POST['order'][0]['dir'])) ? ($_POST['order'][0]['dir']) : null;

        $columnss = array(
            // datatable column index  => database column name 
            1 => 'prodet_name',
            2 => 'prodet_shortDesc',
            4 => 'prodet_timeStamp',
            5 => 'view',
            6 => 'sale'
        );

        $selectedCheck = @$_POST['selectedCheck'];

        #### Search Query #####
        @$page = $_GET['page'];
        $setting_val = " '1' ";

        if ($page == 'draft_products') {
            $setting_val = " '0' ";
        }

        $categories = @$_POST['catego'];

        if ($search) {
            $search_sql = "( `proudct_detail`.`prodet_shortDesc` LIKE '%{$search}%' 
        OR `proudct_detail`.`prodet_name` LIKE '%{$search}%' ) AND";
        } else {
            $search_sql = '';
        }

        if ($orderBy) {
            $order_sql = " ORDER BY `" . $columnss[$orderBy] . "` " . $orderDir . "";
        } else {
            $order_sql = " ORDER BY `proudct_detail`.`prodet_id` DESC";
        }

        $cat_pro = array();
        $cat_filter = '';

        if (isset($categories) && !empty($categories)) {
            $cat_exp = explode(',', $categories);

            foreach ($cat_exp as $key => $value) {
                $cat_pro[] = $this->productByCategoryNew($value);
            }

            $count_cat_pro = count($cat_pro);
            $c = 0;

            if (!empty($cat_pro[0])) {
                $pIdd = '';

                foreach ($cat_pro as $key => $value) {
                    for ($i = 0; $i < sizeof($value); $i++) {
                        $product_id = $value[$i]['prodet_id'];
                        $pIdd .= '\'' . $product_id . '\',';
                    }
                }
                $pIdd = rtrim($pIdd, ',');
                $cat_filter = " `proudct_detail`.`prodet_id` IN({$pIdd}) AND ";
                $c++;
            } else {
                $cat_filter = "";
                $columns["data"] = array();
                echo json_encode($columns);
                exit;
            }
        }

        $cat_proSelect = array();
        $product_idSelect = array();

        if (isset($selectedCheck) && !empty($selectedCheck)) {
            $cat_expSelect = explode(',', $selectedCheck);

            foreach ($cat_expSelect as $key => $value) {
                $cat_proSelect[] = $this->productByCategoryNew($value);
            }

            foreach ($cat_proSelect as $key => $value) {
                for ($i = 0; $i < sizeof($value); $i++) {
                    $product_idSelect[] = $value[$i]['prodet_id'];
                }
            }
        }

        ############# GET TOTAL ROWS #############
        $total_count_sql = " SELECT `proudct_detail`.*, `product_setting`.`setting_val` 
                FROM `proudct_detail` 
                join `product_setting`  on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                WHERE {$search_sql} {$cat_filter} `product_setting`.`setting_name`='publicAccess' 
                AND `product_setting`.`setting_val`={$setting_val} 
                AND `proudct_detail`.`product_update`='1'";


        # overriding sql for pending products, for total count and normal count

        if ($page == 'pending_products') {
            $date = date('m/d/Y');
            $total_count_sql = $qry = "  SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                    FROM `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE {$search_sql} `product_setting`.`setting_name`='launchDate'
                    AND `product_setting`.`setting_val` > '$date'
                    AND `proudct_detail`.`product_update` = '1'
                    ORDER BY `proudct_detail`.`prodet_id` DESC ";
        }

        $all_data = $this->dbF->getRows($total_count_sql);
        $recordsTotal = $this->dbF->rowCount;

        ###### Get Data ######
        $qry = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                FROM `proudct_detail` 
                join `product_setting` on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                WHERE {$search_sql} {$cat_filter}
                `product_setting`.`setting_name`='publicAccess' 
                AND `product_setting`.`setting_val`={$setting_val} 
                AND `proudct_detail`.`product_update`='1'
                {$order_sql} LIMIT {$start},{$length} ";

        # overriding sql for pending products, for total count and normal count
        if ($page == 'pending_products') {
            $qry = $total_count_sql;
        }
        $data = $this->dbF->getRows($qry);

        $columns = array();
        if ($draw == 1) {
            $draw - 1;
        }
        $columns["draw"] = $draw + 1;
        $columns["recordsTotal"] = $recordsTotal; //total record,
        $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear

        $i = $start;

        foreach ($data as $key => $val) {
            $i++;
            $defaultLang = $this->functions->AdminDefaultLanguage();
            $name = unserialize($val['prodet_name']);
            $sDesc = unserialize($val['prodet_shortDesc']);
            $views = $val['view'];
            $sales = $val['sale'];
            $id = $val['prodet_id'];

            # this functions uses $_SERVER['REQUEST_URI'], as now we are using ajax request so the link in $_SERVER['REQUEST_URI'] is of the ajax request not the current url in browser, so we are hardcoding this for the time being, new way / function will have to be created.

            // $link  = $this->functions->getLinkFolder();

            $link = 'product';
            $pro_img = $this->product->productF->getProductSingleImage($id);

            if (is_array($pro_img)) {
                $img_pa = WEB_URL . '/images/' . $pro_img['image'];
                $smlImage = $this->functions->resizeImage($pro_img['image'], '80', '90', false);
            }


            // $grpOption  =   $this->email->emailGrpOption($val['grp']);

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

            $group = "";


            //For featured Item
            $featureProduct = "";

            if ($this->functions->developer_setting('featureProduct') == '1') {
                $featureProduct = true;
                $status = $val['feature'];

                if ($status == '1') {
                    $class = "glyphicon glyphicon-star";
                    $status = '0';
                } else {
                    $class = "glyphicon glyphicon-star-empty";
                    $status = '1';
                }

                $featureProduct = "<a data-id ='$id' data-val='$status' onclick='featureItem(this);' class='btn'   title='" . $_e['Active/DeActive Feature item'] . "'>
                        <i class='$class trash'></i>
                        <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                    </a>";
            }

            //For Trending Fashion

            $feature2 = "";

            if ($this->functions->developer_setting('featureProduct2') == '1') {
                $feature2 = true;
                $statusT = $val['feature'];

                if ($statusT == '2') {
                    $classT = "glyphicon glyphicon-heart";
                    $statusT = '3';
                } else {
                    $classT = "glyphicon glyphicon-heart-empty";
                    $statusT = '2';
                }

                $feature2 = "<a data-id ='$id' data-val='$statusT' onclick='trandingItem(this);' class='btn'   title='" . $_e['Active/DeActive Feature item2'] . "'>
                                <i class='$classT trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }

            $seoLink = '';

            if ($this->functions->developer_setting('seo') == '1') {
                $this->functions->getAdminFile("seo/classes/seo.class.php");
                $seoC = new seo();
                $seoLink = $seoC->seoQuickLink($id, urlencode("/" . $this->db->productDetail . "$val[slug]"));
            }

            // $action = "<div class='btn-group btn-group-sm'>

            //                 <a data-id='$id' data-val='0' onclick='activeEmail(this);' class='btn'   title='". $_e['DeActive Email'] ."'>

            //                     <i class='glyphicon glyphicon-thumbs-down trash'></i>

            //                     <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

            //                 </a>

            //                 <a data-id='$id' onclick='deleteEmail(this);' class='btn'   title='". $_e['Delete Email'] ."'>

            //                     <i class='glyphicon glyphicon-trash trash'></i>

            //                     <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

            //                 </a>

            //             </div>";

            // if($page == 'data_ajax_unactive_email') {

            //     $action = "<div class='btn-group btn-group-sm'>

            //                 <a data-id='$id' data-val='1' onclick='activeEmail(this);' class='btn'  title='" . $_e['Active Email'] . "'>

            //                     <i class='glyphicon glyphicon-thumbs-up trash'></i>

            //                     <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

            //                 </a>

            //                 <a data-id='$id' onclick='deleteEmail(this);' title='" . $_e['Delete'] . "' class='btn'>

            //                     <i class='glyphicon glyphicon-trash trash'></i>

            //                     <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

            //                 </a>

            //             </div>";

            // }
            $uniq = (isset($_GET['uniq'])) ? $_GET['uniq'] : 'no-uniq';

            $first_column = "<div class='checkbox'>
                                <label>
                                    <input type='checkbox' ng-checked='$uniq' name='productListCheck[]' value='$id'> $i
                                </label>
                            </div>";

            $myprefix = $this->product->prefix_editPro;

            $action = "<div class='btn-group btn-group-sm'>
                            $featureProduct
                            $feature2
                            $seoLink
                            <a data-id='$id' href='?{$myprefix}=$id'
                                data-method='post' data-action='-$link?page=edit'
                                class='btn'><i class='glyphicon glyphicon-edit'></i></a>
                            <a data-id='$id' onclick='AjaxDelScript(this);' class='btn '>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                            <a data-id='$id' href='?{$myprefix}=$id&copy=true'
                                data-method='post' data-action='-$link?page=edit'
                                class='btn'><i class='fa fa-clipboard'></i></a>
                        </div>";

            $checked = (in_array($id, $product_idSelect) || in_array($id, $cat_pro)) ? 'checked' : '';
            $checkbox = "<label class='uniqueLabel' for='$id'><input type='checkbox' id='$id' value='$id' name='prod_check' class='prod_check' $checked></label>";

            //6 columns
            $smlImage = isset($smlImage) ? $smlImage : '';

            $columns["data"][$key] = array(
                $checkbox,
                $i, ##### disabling this for the time being needs work "{$first_column}",
                "<img src='$smlImage'>",
                "<a data-id='" . $id . "'   data-target='#productEditModal' onclick='productEditModal(this);' class='btn productAjax_edit'>{$name[$defaultLang]}</a>",
                "{$sDesc[$defaultLang]}",
                "{$val['prodet_timeStamp']}",
                $views,
                $sales,
                $action
            );
        }

        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }

        //Json Encode
        echo json_encode($columns);
    }



    public function productByCategoryNew($category, $QuickViewId = false, $hasId = true)
    {



        $sql = "SELECT * FROM `categories` WHERE id = ? ";

        if ($hasId == false) {

            $sql = "SELECT * FROM `categories` WHERE name = ?";
        }

        $catData = $this->dbF->getRow($sql, array($category));



        if (!$this->dbF->rowCount) {

            return false;
        }



        $catId = $catData['id'];

        $catId = $this->getSubCatIdsNew($catId); //array





        $LIKE = "";

        foreach ($catId as $val) {

            $cId = $val;

            $LIKE .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";
        }

        $LIKE = trim($LIKE, "OR");



        //Find Product That in this category

        $sql = "SELECT `procat_prodet_id`,`prodet_id`

                                FROM `product_category`

                                JOIN

                                `proudct_detail` as detail

                                  on `product_category`.`procat_prodet_id` = `detail`.`prodet_id`

                                    WHERE $LIKE

                                      GROUP BY `detail`.`prodet_id`";



        $productIds = $this->dbF->getRows($sql);

        return $productIds;

        // return $this->productPrint($mergerd_products, $QuickViewId, $products);

        //return $catId;

    }



    public function getSubCatIdsNew($parent)
    {

        //4 dept first query is 2 dept.

        $sql = "SELECT * FROM `categories` WHERE id = '$parent'";

        $data = $this->dbF->getRows($sql);

        $cat = array();

        if ($this->dbF->rowCount > 0) {

            //1 2 dept

            foreach ($data as $val) {

                $id = $val['id'];

                $cat[] = $id;

                $sql = "SELECT * FROM `categories` WHERE id = '$id'";

                $data2 = $this->dbF->getRows($sql);

                if ($this->dbF->rowCount > 0) {

                    //3 dept

                    foreach ($data2 as $val2) {

                        $id = $val2['id'];

                        $cat[] = $id;

                        $sql = "SELECT * FROM `categories` WHERE id = '$id'";

                        $data3 = $this->dbF->getRows($sql);

                        foreach ($data3 as $val3) {

                            //4 dept

                            $id = $val3['id'];

                            $cat[] = $id;
                        } //4 dept end

                    }
                } //3 dept end

            }
        } //1 2 dept end



        $cat = array_unique($cat);

        return $cat;
    }



    public function addProToCat()
    {

        $catArray = $_POST['catArray'];

        $proArray = $_POST['proArray'];

        $newCat = array();



        foreach ($proArray as $key => $value) {

            $cat_exp[] = '';

            $sql = "SELECT * FROM `product_category` WHERE `procat_prodet_id` = ?";

            $res = $this->dbF->getRow($sql, array($value));

            $cat_exp = explode(',', $res['procat_cat_id']);



            foreach ($catArray as $key1 => $value1) {

                if (!in_array($value1, $cat_exp)) {

                    array_push($cat_exp, $value1);
                }
            }

            $proNewCat = join(',', $cat_exp);



            $sql1 = "UPDATE `product_category` SET `procat_cat_id` = ? WHERE `procat_prodet_id` = ?";

            $res1 = $this->dbF->setRow($sql1, array($proNewCat, $value));



            if ($this->dbF->rowCount)
                echo "1";
            else {
                echo "0";
            }
        }
    }



    public function removeProFromCat()
    {

        $catArray = $_POST['catArray'];

        $proArray = $_POST['proArray'];

        $newCat = array();



        foreach ($proArray as $key => $value) {

            $cat_exp[] = '';

            $sql = "SELECT * FROM `product_category` WHERE `procat_prodet_id` = ?";

            $res = $this->dbF->getRow($sql, array($value));

            $cat_exp = explode(',', $res['procat_cat_id']);



            foreach ($catArray as $key1 => $value1) {



                if (($key = array_search($value1, $cat_exp)) !== false) {

                    unset($cat_exp[$key]);
                }



                // if(in_array($value1,$cat_exp)){

                //    array_pop($cat_exp,$value1); 

                // }

            }

            $proNewCat = join(',', $cat_exp);



            $sql1 = "UPDATE `product_category` SET `procat_cat_id` = ? WHERE `procat_prodet_id` = ?";

            $res1 = $this->dbF->setRow($sql1, array($proNewCat, $value));



            if ($this->dbF->rowCount)
                echo "1";
            else {
                echo "0";
            }
        }
    }

    public function copyMissingProducts()
    {

        $ids = json_decode($_POST['ids']);
        $copyData = array();

        $priceCalc = $this->functions->ibms_setting('priceCalc');
        $priceCalc = unserialize($priceCalc);

        $country_fr = $priceCalc['country'][0];
        $fr_divide = $priceCalc['divide'][0];
        $fr_multiply = $priceCalc['multiply'][0];

        $country_de = $priceCalc['country'][1];
        $de_divide = $priceCalc['divide'][1];
        $de_multiply = $priceCalc['multiply'][1];

        $country_nl = $priceCalc['country'][2];
        $nl_divide = $priceCalc['divide'][2];
        $nl_multiply = $priceCalc['multiply'][2];

        $country_us = $priceCalc['country'][3];
        $us_divide = $priceCalc['divide'][3];
        $us_multiply = $priceCalc['multiply'][3];

        $country_be = $priceCalc['country'][4];
        $be_divide = $priceCalc['divide'][4];
        $be_multiply = $priceCalc['multiply'][4];

        $country_uk = $priceCalc['country'][5];
        $uk_divide = $priceCalc['divide'][5];
        $uk_multiply = $priceCalc['multiply'][5];

        $country_es = $priceCalc['country'][6];
        $es_divide = $priceCalc['divide'][6];
        $es_multiply = $priceCalc['multiply'][6];

        $country_at = $priceCalc['country'][7];
        $at_divide = $priceCalc['divide'][7];
        $at_multiply = $priceCalc['multiply'][7];

        $country_it = $priceCalc['country'][8];
        $it_divide = $priceCalc['divide'][8];
        $it_multiply = $priceCalc['multiply'][8];

        $country_chf = $priceCalc['country'][9];
        $chf_divide = $priceCalc['divide'][9];
        $chf_multiply = $priceCalc['multiply'][9];

        $country_other = $priceCalc['country'][10];
        $other_divide = $priceCalc['divide'][10];
        $other_multiply = $priceCalc['multiply'][10];


        #############  GET PRODUCT ORIGINAL DATA  #############

        foreach ($ids as $key => $value) {

            $new_id = $value;
            /* -------  ##############  PRODUCT_DETAIL TABLE  #############  -------- */



            $sql_proInfo = "SELECT `prodet_id`, `slug`, `prodet_name`, `prodet_shortDesc`, `product_update`, `sort`, `feature` FROM `proudct_detail` WHERE `prodet_id` = $value";
            $res_proInfo = $this->dbF->getRow($sql_proInfo);

            $copyData[$new_id]['proInfo'] = $res_proInfo;

            $check_ref = '%product-' . $res_proInfo['prodet_id'] . '%';

            $sql_proSeo = "SELECT * FROM `seo` WHERE `ref_id` LIKE '$check_ref'";
            $res_proSeo = $this->dbF->getRow($sql_proSeo);

            $copyData[$new_id]['proInfo']['seo'] = $res_proSeo;



            /* ----------  ##############  PRODUCT_PRICE TABLE  ############# ------------  */


            $sql_proPrice = "SELECT `propri_cur_id`, `propri_price`, `propri_intShipping` FROM `product_price` WHERE `propri_prodet_id` = $value AND `propri_cur_id` = 20";
            $res_proPrice = $this->dbF->getRow($sql_proPrice);

            $copyData[$new_id]['proPrice'] = $res_proPrice;


            /* ---------  ##############  PRODUCT_ADDCOST TABLE  ############# -----------  */


            $sql_proaddcost = "SELECT `proadc_name`, `proadc_price` FROM `product_addcost` WHERE `proadc_prodet_id` = $value AND `proadc_cur_id` = 20";
            $res_proaddcost = $this->dbF->getRow($sql_proaddcost);

            if (!empty($res_proaddcost)) {
                $copyData[$new_id]['proadc'] = $res_proaddcost;
            }


            /* ---------  ##############  PRODUCT_IMAGE TABLE  ############# -----------  */


            $sql_proimg = "SELECT `image`, `alt`, `sort` FROM `product_image` WHERE `product_id` = $value";
            $res_proimg = $this->dbF->getRows($sql_proimg);

            if (!empty($res_proimg)) {
                $copyData[$new_id]['proimg'] = $res_proimg;
            }


            /* ---------  ##############  PRODUCT_SETTING TABLE  ############# -----------  */


            $sql_proSetting = "SELECT * FROM `product_setting` WHERE `p_id` = $value";
            $res_proSetting = $this->dbF->getRows($sql_proSetting);

            if (!empty($res_proSetting)) {
                $copyData[$new_id]['proSetting'] = $res_proSetting;
            }


            /* ---------  ##############  PRODUCT_DISCOUNT/PRICES/SETTING TABLE  ############# -----------  */


            $sql_proDiscount = "SELECT `product_dis_id`, `product_dis_price`, `product_dis_status`, `product_dis_intShipping` FROM `product_discount` d JOIN `product_discount_prices` dp ON d.`product_discount_pk` = dp.`product_dis_id` WHERE d.`discount_PId` = $value AND `product_dis_curr_Id` = 20";
            $res_proDiscount = $this->dbF->getRow($sql_proDiscount);

            if (!empty($res_proDiscount)) {
                $copyData[$new_id]['proDiscount'] = $res_proDiscount;

                $sql_proDiscSetting = "SELECT `product_dis_name`, `product_dis_value` FROM `product_discount_setting` WHERE `product_dis_id` = ?";
                $res_proDiscSetting = $this->dbF->getRows($sql_proDiscSetting, array($res_proDiscount['product_dis_id']));

                $copyData[$new_id]['proDiscount']['setting'] = $res_proDiscSetting;
            }


            /* ---------  ##############  PRODUCT_COLOR TABLE  ############# -----------  */


            $sql_proColorId = "SELECT `propri_id`,`proclr_name` FROM `product_color` WHERE `proclr_prodet_id` = $value AND `proclr_cur_id` = 24";
            $res_proColorId = $this->dbF->getRows($sql_proColorId);

            if (!empty($res_proColorId)) {
                $countColor = 0;
                foreach ($res_proColorId as $keyid => $valueid) {

                    $sql_proColor = "SELECT `proclr_name`, `color_name`, `proclr_price`, `sizeGroup` FROM `product_color` WHERE `proclr_prodet_id` = $value AND `proclr_cur_id` = 20 AND `proclr_name` = ?";
                    $res_proColor = $this->dbF->getRow($sql_proColor, array($valueid['proclr_name']));


                    $copyData[$new_id]['proColor'][] = $res_proColor;
                    $copyData[$new_id]['proColor'][$countColor]['id'] = $valueid['propri_id'];
                    $countColor++;
                }
            }


            /* ---------  ##############  PRODUCT_SCALE TABLE  ############# -----------  */


            $sql_proScaleId = "SELECT `prosiz_id`,`prosiz_name` FROM `product_size` WHERE `prosiz_prodet_id` = $value AND `prosiz_cur_id` = 24";
            $res_proScaleId = $this->dbF->getRows($sql_proScaleId);

            if (!empty($res_proScaleId)) {
                $countScale = 0;
                foreach ($res_proScaleId as $keyid => $valueid) {

                    $sql_proScale = "SELECT `prosiz_name`, `prosiz_price`, `sizeGroup` FROM `product_size` WHERE `prosiz_prodet_id` = $value AND `prosiz_cur_id` = 20 AND `prosiz_name` = ?";
                    $res_proScale = $this->dbF->getRow($sql_proScale, array($valueid['prosiz_name']));


                    $copyData[$new_id]['proScale'][] = $res_proScale;
                    $copyData[$new_id]['proScale'][$countScale]['id'] = $valueid['prosiz_id'];
                    $countScale++;
                }
            }


            /* ---------  ##############  PRODUCT_SIZECUSTOM TABLE  ############# -----------  */


            $sql_proSizCustom = "SELECT `type_id`, `price` FROM `product_size_custom` WHERE `pId` = $value AND `currencyId` = 20";
            $res_proSizCustom = $this->dbF->getRow($sql_proSizCustom);

            if (!empty($res_proSizCustom)) {
                $copyData[$new_id]['proSizeCustom'] = $res_proSizCustom;
            }



            /* ---------  ##############  PRODUCT_CATEGORY TABLE  ############# -----------  */


            $sql_proCategory = "SELECT `procat_cat_id` FROM `product_category` WHERE `procat_prodet_id` = $value";
            $res_proCategory = $this->dbF->getRow($sql_proCategory);

            $copyData[$new_id]['proCategory'] = trim($res_proCategory['procat_cat_id'], ',');
        }

    }

    public function check_slug_duplicate()
    {
        $slug = $_POST["slug"];
        $ref_id = $_POST["refId"];
        $ref_id_check = ($ref_id == false) ? "" : " AND `ref_id` NOT LIKE '%$ref_id%' ";
        $sql = "SELECT * FROM `seo` WHERE `slug` = ? $ref_id_check";
        $this->dbF->getRows($sql, array($slug));

        if ($this->dbF->rowCount > 0) {
            echo "matched";
        } else {
            echo "not matched";
        }
    }
    
    public function getMassData()
    {

        $fields_select = $_POST['field_select'];
        $language = $_POST['lang_select'];

        $check_array = array(
            "proudct_detail" => array(
                'prodet_name',
                'prodet_shortDesc',
                'slug'
            ),

            "product_setting" => array(
                'ldesc',
                'publicAccess',
                'Model',
                'label',
                'launchDate',
                'shippingClass',
                'size_chart',
                'tags',
                'specification',
                'featureIcon',
                'featurePoints'
            )
        );

        $update_array = array();
        $columns = array();
        // $columns[] = array('SNO');
        $columns[] = array('Product Image');
        $columns[] = array('Product Name');
        $table = '';
        foreach ($check_array as $key => $value) {
            foreach ($fields_select as $row) {
                if (in_array($row, $value)) {
                    $update_array[$key][] = $row;
                    $columns[] = array($row);
                }
            }
        }

        $columns[] = array('Action');

        $detail_col = join(',', $update_array['proudct_detail']);
        $det_col = (empty($detail_col)) ? '*' : $detail_col;

        $sql_pro = "SELECT `prodet_id`,`prodet_name`,$det_col FROM `proudct_detail` WHERE `product_update` = 1";
        $res_pro = $this->dbF->getRows($sql_pro);

        $data_array = array();
        $pcount = 0;
        foreach ($res_pro as $key => $value) {
            $pId = $value['prodet_id'];
            // $data_array[$pcount][] = $pcount;
            $pName = unserialize($value['prodet_name']);
            $pNameSe = $pName['Swedish'];
            $productImage = $this->productSpecialImage($pId, 'main');
            $productThumb = $this->resizeImage($productImage, 300, 300, false);
            // $productImage = WEB_URL . '/images/' . $productImage;
            $data_array[$pcount][] = '<img src="' . $productThumb . '" width="70px"/>';
            $data_array[$pcount][] = '<p>' . $pNameSe . '</p>';
            foreach ($value as $keyy => $valuee) {
                for ($i = 0; $i < sizeof($update_array['proudct_detail']); $i++) {
                    if ($keyy == $update_array['proudct_detail'][$i]) {
                        @$a = unserialize($valuee);
                        if ($a === false) {
                            $un_val = empty($valuee) ? '-' : $valuee;
                            $data_array[$pcount][] = '<input type="text" id="area' . $keyy . $pId . '" class="form-control" name="' . $keyy . '" value="' . $un_val . '" />';
                        } else {
                            $empty = '';
                            if (empty(@$a[$language])) {
                                $empty = 'empty_div';
                            }

                            if ($keyy == 'prodet_name') {
                                $data_array[$pcount][] = '<input type="text" id="area' . $keyy . $pId . '" class="form-control" name="' . $keyy . '" value="' . @$a[$language] . '" />';
                            } else {
                                $remove = "'remove'";
                                $data_array[$pcount][] = '<div id="area' . $keyy . $pId . '" data-id="' . $pId . '" onclick="toggleArea1(this);" name="' . $keyy . '" class="dt_div ' . $empty . '">' . @$a[$language] . '</div><div class="btn btn-primary btn-xs remove" onclick="toggleArea1(this, ' . $remove . ')" style="display:none;">X</div>';
                            }
                        }
                    }
                }
            }

            // $this->dbF->prnt($update_array);
            $pSetting = $this->product->productF->getProductSetting($pId);
            if (!empty($pSetting)) {
                if (isset($update_array['product_setting']) && !empty($update_array['product_setting'])) {
                    foreach ($update_array['product_setting'] as $set_key => $set_value) {
                        $set_val = $this->product->productF->productSettingArray($set_value, $pSetting, $pId);

                        @$unse_var = unserialize($set_val);

                        if ($unse_var === false) {
                            $sett_val = empty($set_val) ? ' ' : $set_val;
                            $data_array[$pcount][] = '<input type="text" id="area' . $set_value . $pId . '" data-id="abc" class="form-control" name="' . $keyy . '" value="' . $sett_val . '" />';
                        } else {

                            $content = @$unse_var[$language];

                            $emptys = '';
                            if (trim($content) == "") {
                                $emptys = 'empty_div';
                            }

                            /////// Remove Style attribute from data, just for some time for data correction  ///////

                            if ($set_value == 'ldesc') {

                                $dom = new DOMDocument;
                                $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED);
                                $nodes = $dom->getElementsByTagName('*');

                                if (!empty($nodes)) {
                                    foreach ($nodes as $node) {
                                        if ($node->hasAttribute('style')) {
                                            $node->removeAttribute('style');
                                        }
                                    }

                                    $content = $dom->saveHTML($dom->documentElement);
                                }
                            }

                            /////// Remove Style attribute from data, just for some time for data correction END ///////



                            $remove = "'remove'";
                            $data_array[$pcount][] = '<div id="area' . $set_value . $pId . '" data-id="' . $pId . '" onclick="toggleArea1(this);" name="' . $set_value . '" data-id="' . $pId . '" class="dt_div ' . $emptys . '">' . @trim($content) . '</div><div class="btn btn-primary btn-xs remove" onclick="toggleArea1(this, ' . $remove . ')" style="display:none;">X</div>';
                        }
                    }
                }
            } else {
                if (isset($update_array['product_setting'])) {
                    foreach (@$update_array['product_setting'] as $set_key => $set_value) {
                        $data_array[$pcount][] = '<input type="text" id="area' . $set_value . $pId . '" class="form-control" name="' . $keyy . '" value="" />';
                    }
                }
            }

            if (!intval($pId)) {
                $pId = "'" . $pId . "'";
            }

            $data_array[$pcount][] = '<a class="btn btn-primary" data-id="' . $pId . '" id="update_' . $pId . '" onclick="updateProduct(' . $pId . ')">Update</a>';
            $pcount++;
        }

        $final_array = array(
            "data" => $data_array,
            "columns" => $columns
        );

        $final_array = json_encode($final_array);
        echo $final_array;
    }






    public function getMassData1()
    {

        $underMenu = $_POST['underMenu'];
        // $language = $_POST['lang_select'];


        // $qry="SELECT DISTINCT product_id FROM product_image WHERE sort IS NULL order by product_id desc limit 25";




        $LIKE = "";

        // foreach ($catId as $val) {

        //     $cId = $val;

        $LIKE = " `product_category`.`procat_cat_id` LIKE '%$underMenu%' OR";

        // }

        $LIKE = trim($LIKE, "OR");




        // $qry = "SELECT `procat_prodet_id`,`prodet_id` as ppiidd

        //                 FROM `product_category`

        //                 JOIN

        //                 `proudct_detail` as detail

        //                   on `product_category`.`procat_prodet_id` = `detail`.`prodet_id`

        //                     WHERE $LIKE

        //                       GROUP BY `product_category`.`procat_cat_id`";

        $qry = "SELECT `procat_prodet_id` as ppiidd

                                FROM `product_category`
                              

                                    WHERE `product_category`.`procat_cat_id` LIKE '%$underMenu%' 
                                      ";










        $eData = $this->dbF->getRows($qry);

        if ($this->dbF->rowCount > 0) {

            foreach ($eData as $key => $val) {


?>







                <!-- <div class="tab-pane container-fluid fade" id="tab_images"> -->

                <h2 class="tab_heading"><?php


                                        $qrys = "SELECT prodet_name
FROM proudct_detail
WHERE prodet_id = $val[ppiidd]";

                                        $eDatas = $this->dbF->getRow($qrys);


                                        $defaultLang = $this->functions->AdminDefaultLanguage();


                                        $heading = unserialize($eDatas['prodet_name']);
                                        $heading = $heading[$defaultLang];

                                        echo $heading;









                                        ?></h2>
                <input type="hidden" id="AjaxFileNewId" name="ProductNewId" value="<?php echo $val['ppiidd']; ?>">

                <input type="hidden" id="AjaxFileNewPage" value="product">

                <div id="dropbox" class="dropbox<?php echo $val['ppiidd']; ?>">
                    <?php

                    // if product edit

                    // if ($isEdit && !isset($_POST['copy'])) {

                    $this->product->productEditImagesMass($val['ppiidd']);

                    // }

                    ?>

                    <style>
                        .dropbox .preview {

                            height: 255px !important;

                            padding: 4px;

                            background: #eee;

                        }


                        .dropbox<?php echo $val['ppiidd']; ?> {

                            background: url(../img/background_tile_3.jpg);
                            border-radius: 10px;
                            position: relative;
                            min-height: 290px;
                            overflow: hidden;
                            padding-bottom: 40px;
                        }

                        #dropbox<?php echo $val['ppiidd']; ?> {

                            background: url(../img/background_tile_3.jpg);
                            border-radius: 10px;
                            position: relative;
                            min-height: 290px;
                            overflow: hidden;
                            padding-bottom: 40px;
                        }

                        .dropbox .progressHolder.album {

                            height: 80px !important;

                            padding: 5px;

                        }
                    </style>





                </div>


                <script>
                    $(document).ready(function() {

                        $(".dropbox<?php echo $val['ppiidd']; ?>").sortable({

                            handle: '.imageHolder',

                            containment: "parent",

                            update: function() {

                                serial = $(this).sortable('serialize');

                                $.ajax({

                                    url: 'product_management/product_ajax.php?page=sortProductImage',

                                    type: "post",

                                    data: serial,

                                    error: function() {

                                        jAlertifyAlert("There is an error, Please Refresh Page and Try Again");

                                    }

                                });

                            }

                        });


                        /////////////////////////////////////////////////////////



                        $(".pImageAltUpdate<?php echo $val['ppiidd']; ?>").click(function() {

                            btn = $(this);

                            btn.addClass('disabled');

                            btn.children('.trash').hide();

                            btn.children('.waiting').show();



                            id = btn.attr('data-id');

                            alt = $('#alt-' + id).val();

                            btn.children('span').text('Wait...');

                            $.ajax({

                                type: 'POST',

                                url: 'product_management/product_ajax.php?page=pImageAltUpdate',

                                data: {
                                    imageId: id,
                                    altT: alt
                                }

                            }).done(function(data) {

                                ift = true;

                                if (data == '1') {

                                    btn.children('span').text('Done');

                                } else {

                                    btn.children('span').text('Fail');

                                }

                                btn.removeClass('disabled');

                                btn.children('.trash').show();

                                btn.children('.waiting').hide();



                            });

                        });

                        ////////////////////////////////////////////














                    });
                </script>

            <?php
            }
        }
    }

    public function getMassDataNew()
    {

        $underMenu = $_POST['underMenu'];

        $LIKE = "";

        $LIKE = " `product_category`.`procat_cat_id` LIKE '%$underMenu%' OR";


        $LIKE = trim($LIKE, "OR");

        $qry = "SELECT `procat_prodet_id` as ppiidd FROM `product_category` WHERE `product_category`.`procat_cat_id` LIKE '%$underMenu%' 
                                      ";

        $eData = $this->dbF->getRows($qry);

        if ($this->dbF->rowCount > 0) {
            echo '<h2 class="tab_heading">Update Supplier And Keywords</h2>';
            $i = 0;
            foreach ($eData as $key => $val) {
                
                $qrys = "SELECT prodet_id, prodet_name, buying_price FROM proudct_detail WHERE prodet_id = $val[ppiidd]";
                $eDatas = $this->dbF->getRow($qrys);
                $defaultLang = $this->functions->AdminDefaultLanguage();
                $heading = unserialize($eDatas['prodet_name']);
                $heading = $heading[$defaultLang];
                $productID = $eDatas['prodet_id'];
                $buyingPriceValue = $eDatas['buying_price'];
                $settingData = $this->product->productF->getProductSetting($val['ppiidd']);
                $keywords = $this->product->productF->productSettingArray('productConnectKeywords', $settingData, $val['ppiidd']);
                $keywordsValue = explode(',', $keywords);
                
                $keywordValue = $this->product->productF->productSettingArray('productKeyword', $settingData, $val['ppiidd']);
                
                $supplierValue = $this->product->productF->productSettingArray('supplier', $settingData, $val['ppiidd']);
                
                $keywords = $this->functions->ibms_setting('productKeywords');
                $keywords = unserialize($keywords);
            ?>
                <div class="form-horizontal">
                    <form method="post" onsubmit="updateMassSupplier(this); return false;">
                        <input type="hidden" name="productID" value="<?php echo $productID; ?>">
                        <div class="col-md-12" style="background: #fff; border-radius: 1rem; margin-bottom: 12px;">
                            <h2 class="tab_heading"><?php echo $heading; ?></h2>
                            <div class="col-md-6">  
                                <div class="form-group" style="margin-left: 0px !important;">
                                    <label class="control-label">Select Product Keyword</label>
                                    <select name="productKeyword" class="form-control">
                                        <option value="">Select Keyword</option>
                                        <?php
                                            foreach($keywords as $key => $keyword){
                                                $select = '';
                                                $select = ($keyword === $keywordValue) ? 'selected' : '';
                                                echo '<option value="' . $keyword .'" ' . $select . '>' . $keyword . '</option>';
                                            }
                                        ?>
                                    </select>   
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-left: 0px !important;">
                                    <label class="control-label" style="display: block; text-align: start;">Select Connection Keywords</label>
                                    <select name="productConnectKeywords[]" class="form-control test2 example-getting-started_without_img3" style="height:300px" multiple="">
                                        <?php
                                            foreach($keywords as $key => $keyword){
                                                $select = '';
                                                $select = (in_array($keyword, $keywordsValue)) ? 'selected' : '';
                                                echo '<option value="' . $keyword .'" ' . $select . '>' . $keyword . '</option>';
                                            }
                                        ?>
                                    </select>  
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group" style="margin-left: 0px !important;">
                                    <label class="control-label">Supplier</label>
                                    <input type="text" value="<?php echo $supplierValue; ?>" class="form-control" name="supplierMassUpdate" placeholder="Enter Supplier">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-left: 0px !important;">
                                    <label class="control-label">Buy In Price</label>
                                    <input type="number" value="<?php echo $buyingPriceValue; ?>" class="form-control" name="buyingPrice">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="margin-left: 0px !important;">
                                    <label class="control-label" style="display: block; text-align: start; visibility: hidden;">Submit</label>
                                    <input type="submit" name="productMassUpdate" value="Update" class="btn btn-primary btn-md" style="margin-left: 20px;">
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-bottom: 10px;"> 
                                <h2 class="tab_heading">Detail Images</h2>
                                <input type="hidden" id="AjaxFileNewIdDetail" name="ProductNewId4" value="<?php echo $productID; ?>">
                                <input type="hidden" id="AjaxFileNewPageDetail" value="productDetail">
                                <div id="dropboxDetail" class="dropboxDetail dropboxDetailNew<?php echo $i; ?>">
                                    <?php $this->product->editPid = $productID; $this->product->productEditDetailImages(); ?>
                                    <style>
                                        .dropboxDetail .progressHolder.album {
                                            height: auto !important;
                                            position: relative !important;
                                        }
                                    </style>
    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
<?php
$i++;
            }
        }
    }

    public function productSpecialImage($id, $alt)
    {
        $sql = "SELECT image FROM `product_image` WHERE product_id = '$id' AND alt = '$alt' ORDER BY sort ASC ";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0) {
            $imag = $data['image'];
        } else {
            //get first Image
            $imag = $this->productFirstImage($id);
        }
        return $imag;
    }


    public function resizeImage($image, $width = 'auto', $height = 'auto', $echo = true, $pngBgColor = false, $imageWithWebUrl = true, $cache = true)
    {
        //It has one setting also in src/image.php development folder name
        return $this->functions->resizeImage($image, $width, $height, $echo, $pngBgColor, $imageWithWebUrl, $cache);
    }



    public function productFirstImage($pId)
    {
        $data = $this->productAllImage($pId, '1', true);
        if (!empty($data)) {
            $imag = $data['image'];
            return $imag;
        }
        return "";
    }



    public function productAllImage($id, $limitP = false, $OnlyFirstImage = false)
    {
        $limit = '';
        if ($limitP != false) {
            $limit = " LIMIT 0,$limitP";
        }
        if ($OnlyFirstImage == true) {
            $limit = " LIMIT 0,1";
        }
        $sql = "SELECT * FROM `product_image` WHERE product_id = '$id' ORDER BY sort ASC $limit";

        if ($OnlyFirstImage == true || $limitP == '1') {
            $data = $this->dbF->getRow($sql);
        } else {
            $data = $this->dbF->getRows($sql);
        }
        return $data;
    }
    
    public function updateMassSupplier(){
        if (!empty($_POST['productID']) && !empty($_POST['supplierMassUpdate'])) {

            $productID = $_POST['productID'];
            $supplierUpdate = $_POST['supplierMassUpdate'];
            $buyingPrice = $_POST['buyingPrice'];
            $productKeyword = $_POST['productKeyword'];
            $productConnectKeywords = isset($_POST['productConnectKeywords']) ? $_POST['productConnectKeywords'] : [];
            $productConnectKeywords = is_array($productConnectKeywords) ? implode(',', $productConnectKeywords) : '';
            
            try{
                $this->db->beginTransaction();
                $checkSupplierSQL = "SELECT setting_val FROM product_setting WHERE setting_name = ? AND p_id = ?";
                $this->dbF->getRow($checkSupplierSQL, ['supplier', $productID]);
                
                if($this->dbF->rowCount > 0){
                    $updateSupplierSQL = "UPDATE product_setting SET setting_val = ? WHERE p_id = ? AND setting_name = ?";
                    $this->dbF->setRow($updateSupplierSQL, [$supplierUpdate, $productID, 'supplier']);
                }else{
                    $insertSupplierSQL = "INSERT INTO product_setting (p_id, setting_name, setting_val) VALUES (?, ?, ?)";
                    $this->dbF->setRow($insertSupplierSQL, [$productID, 'supplier', $supplierUpdate]);
                }
                
                $checkKeywordSQL = "SELECT setting_val FROM product_setting WHERE setting_name = ? AND p_id = ?";
                $this->dbF->getRow($checkKeywordSQL, ['productKeyword', $productID]);
                
                if($this->dbF->rowCount > 0){
                    $updateKeywordSQL = "UPDATE product_setting SET setting_val = ? WHERE p_id = ? AND setting_name = ?";
                    $this->dbF->setRow($updateKeywordSQL, [$productKeyword, $productID, 'productKeyword']);
                }else{
                    $insertKeywordSQL = "INSERT INTO product_setting (p_id, setting_name, setting_val) VALUES (?, ?, ?)";
                    $this->dbF->setRow($insertKeywordSQL, [$productID, 'productKeyword', $productKeyword]);
                }            
                
                $checkKeywordsSQL = "SELECT setting_val FROM product_setting WHERE setting_name = ? AND p_id = ?";
                $this->dbF->getRow($checkKeywordsSQL, ['productConnectKeywords', $productID]);
                
                if($this->dbF->rowCount > 0){
                    $updateKeywordsSQL = "UPDATE product_setting SET setting_val = ? WHERE p_id = ? AND setting_name = ?";
                    $this->dbF->setRow($updateKeywordsSQL, [$productConnectKeywords, $productID, 'productConnectKeywords']);
                }else{
                    $insertKeywordsSQL = "INSERT INTO product_setting (p_id, setting_name, setting_val) VALUES (?, ?, ?)";
                    $this->dbF->setRow($insertKeywordsSQL, [$productID, 'productConnectKeywords', $productConnectKeywords]);
                }
                
                $updateBuyingPriceSQL = "UPDATE proudct_detail SET buying_price = ? WHERE prodet_id = ?";
                $this->dbF->setRow($updateBuyingPriceSQL, [$buyingPrice, $productID]);
                
                $this->db->commit();
                echo 1;
            }catch(Exception $e){
                echo '0';
                $this->db->rollBack();
                $this->dbF->error_submit($e);
            }
            
        }
    }
    
    public function printSelectedInvoice(){
        $invoiceIds = $_POST["id"];
        $invoiceIds = str_replace(' ', '', $invoiceIds);
        $url = WEB_URL ."/invoicePrint?mailId=$invoiceIds";
        echo $url; 
    }
}

?>