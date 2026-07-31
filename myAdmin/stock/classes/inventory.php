<?php

class inventory extends object_class
{
    public $productF;

    public function __construct()
    {
        parent::__construct('3');
        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__ . "/../../product_management/functions/product_function.php");
            $this->productF = new product_function();
        }

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w = array();
        //inventory.php
        $_w['Please Enter Correct Value.'] = '';
        $_w['No product Found to be Delete.'] = '';
        $_w['Please Select Color From this Row'] = '';
        $_w['Please Select Scale From this Row'] = '';
        $_w['Please Select Store From this Row'] = '';
        $_w['Remove Quantity'] = '';
        $_w['This is not recommended, Please go to purchase Receipt and generate new Receipt'] = '';
        $_w['This is not recommended, Please go to Invoice Receipt and generate new Receipt'] = '';
        $_w['Add Quantity'] = '';
        $_w['Stock Update if needed'] = '';
        $_w['View Product Stock Inventory'] = '';
        $_w['Remove Qty'] = '';
        $_w['Add Qty'] = '';
        $_w['PRODUCT THUMBNAIL'] = '';


        $_w['Product Inventory'] = '';
        $_w['Store Location'] = '';
        $_w['Quick Add Qty'] = '';
        //This class
        $_w['Stock Update'] = '';
        $_w['If Stock has Some Error,Its was Successfully remove'] = '';
        $_w['ADD QUANTITY'] = '';
        $_w['REMOVE QUANTITY'] = '';
        $_w['LAST UPDATE'] = '';
        $_w['SELLING PRICE'] = '';
        $_w['QUANTITY'] = '';
        $_w['STORE NAME'] = '';
        $_w['PRODUCT'] = '';
        $_w['SNO'] = '';
        $_w['Enter QTY Number'] = '';
        $_w['{{qty}} Product Found With 0 Products in inventory.'] = '';
        $_w['Add'] = '';
        $_w['Deduct'] = '';
        $_w['Select Store'] = '';
        $_w['Select Scale'] = '';
        $_w['Select Color'] = '';
        $_w['CURRENT QTY'] = '';
        $_w['SCALE'] = '';
        $_w['COLOR'] = '';
        $_w['Update'] = '';

        $_w['Success'] = '';
        $_w['Failed'] = '';
        $_w['STOCK LOCATION'] = '';


        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin StoreInventory');
    }

    private function Inventory_0_delete_afterDays()
    {
        // when product inventory 0 it delete from inventory, but if its delete no alert will work, so
        // option in admin setting product inventory 0 product delete after selected days.,, and till
        // thats days show alert in stock inventory
        $days = $this->functions->ibms_setting('Inventory_0_delete_afterDays');
        if ($days == '0') {
            $sql = "DELETE FROM `product_inventory` WHERE `qty_item` ='0'";
        } else {
            $date = date('Y-m-d', strtotime("-$days days"));
            $sql = "DELETE FROM `product_inventory` WHERE `qty_item` ='0' AND `updateTime` < '$date'";
        }
        $this->dbF->setRow($sql);
    }

    public function cleanInventory()
    {
        global $_e;
        $inventory = $this->productF->stockProductInventory();
        $i = 0;
        $count0Qty = 0;
        foreach ($inventory as $val) {
            $i++;
            $pid = $val['qty_product_id'];
            $scaleId = $val['qty_product_scale'];
            $colorId = $val['qty_product_color'];
            $storeId = $val['qty_store_id'];

            //check Store
            $sql = "SELECT * FROM store_name WHERE store_pk = '$storeId'";
            $this->dbF->getRow($sql);
            if (!$this->dbF->rowCount) {
                $this->inventoryDeleteRow($pid, $scaleId, $colorId, $storeId);
                continue;
            }

            //check product it will auto delete when product delete

            //Scale check
            $sql = "SELECT * FROM product_size WHERE prosiz_id = '$scaleId'";
            $this->dbF->getRow($sql);
            if (!$this->dbF->rowCount && $scaleId != '0') {
                $this->inventoryDeleteRow($pid, $scaleId, $colorId, $storeId);
                continue;
            }

            //check color
            $sql = "SELECT * FROM product_color WHERE propri_id = '$colorId'";
            $this->dbF->getRow($sql);
            if (!$this->dbF->rowCount && $colorId != '0') {
                $this->inventoryDeleteRow($pid, $scaleId, $colorId, $storeId);
                continue;
            }
        }

        if ($this->functions->developer_setting('product_Scale') == '0') {
            //if no scale then remove all scale qty
            $sql = "DELETE FROM product_inventory WHERE qty_product_scale != '0'";
            $this->dbF->setRow($sql);
        }
        if ($this->functions->developer_setting('product_color') == '0') {
            //if no color then remove all color qty
            $sql = "DELETE FROM product_inventory WHERE qty_product_color != '0'";
            $this->dbF->setRow($sql);
        }


        $this->functions->notificationError(_js(_uc($_e['Stock Update'])), _js(_uc($_e['If Stock has Some Error,Its was Successfully remove'])), 'btn-info');

        //delete Old Cart and custom From table...

        $date = date('Y-m-d', strtotime("-30 days"));
        $sql = "DELETE FROM p_custom_submit WHERE dateTime <= '$date' AND id in (SELECT customId FROM cart WHERE dateTime <= '$date')";
        $this->dbF->setRow($sql);

        $sql = "DELETE FROM cart WHERE dateTime <= '$date'";
        $this->dbF->setRow($sql);

    }

    public function inventoryDeleteRow($pId, $scaleId, $colorId, $storeId)
    {
        $sql = "DELETE FROM product_inventory WHERE
                    qty_store_id = '$storeId'
                    AND qty_product_id = '$pId'
                    AND qty_product_scale = '$scaleId'
                    AND qty_product_color = '$colorId'";
        $this->dbF->setRow($sql);
    }

    public function showProductInventory()
    {
        global $_e;
        $this->Inventory_0_delete_afterDays();

        echo '
            <div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                                <th>'. _u($_e['PRODUCT THUMBNAIL']) .'</th>
                        <th>' . _u($_e['PRODUCT']) . '</th>
                        <th>' . _u($_e['STORE NAME']) . '</th>
                        <th>' . _u($_e['QUANTITY']) . '</th>
                        <th>' . _u($_e['SELLING PRICE']) . '</th>
                        <th>' . _u($_e['LAST UPDATE']) . '</th>
                        <th class="qty_location_th">' . _u($_e['STOCK LOCATION']) . '</th>
                        <th class="qtyAddTDLast">' . _u($_e['ADD QUANTITY']) . '</th>
                        <th class="qtyAddTDLast">' . _u($_e['REMOVE QUANTITY']) . '</th>
                    </thead>
                <tbody>';

        $inventory = $this->productF->stockProductInventory();
        $i = 0;
        $count0Qty = 0;
        foreach ($inventory as $val) {
            $i++;
            $id = $val['qty_pk'];
            $pid = $val['qty_product_id'];
            $scaleId = $val['qty_product_scale'];
            $colorId = $val['qty_product_color'];
            $storeId = $val['qty_store_id'];
            $location = $val['location'];
            $pName = $this->productF->getProductFullName($pid, $scaleId, $colorId);
            if ($pName == false) {
                continue;
            }

            $storeName = $this->productF->getStoreName($storeId);
            $qty = $val['qty_item'];
            if ($qty == '0') {
                $qty0 = 'style="background-color:#E64444;color:#fff;"';
                $count0Qty++;
            } else {
                $qty0 = '';
            }


             $productImage = $this->productF->productSpecialImage($pid, 'main');
            // $productThumb = $this->resizeImage($productImage, 300, 300, false);
            $productImage = WEB_URL . '/images/' . $productImage;
            $data_img = '<img src="'.$productImage.'" width="70px"/>';





            $price = $this->productF->productTotalPrice($pid, $scaleId, $colorId, '0', '0', 'after', ' ');
            echo "<tr>
                    <td>$i</td>
                    <td>$data_img</td>
                    <td>$pName</td>
                    <td>$storeName</td>
                    <td $qty0 class='currentQTY'>$qty</td>
                    <td>$price</td>
                    <td>$val[updateTime]</td>
                    <td class='add_location'>
                        <div class='form-group'>
                            <div class='input-group'>
                                <input class='productQTYInput product_location_input' value='$location' type='text' placeholder='" . _uc($_e['STOCK LOCATION']) . "' style='width: 64%;'>
                                <button  style='width: 35%;padding:2px;' type='button' class='QTYSubmitBtn btn-primary' onclick='productStockLocationSubmit(this);' data-id='$id' value='" . _uc($_e['Update']) . "'>
                                    <span>" . _uc($_e['Update']) . "</span>
                                    <i class='fa fa-refresh waiting2 fa-spin' style='display: none;'></i>
                                </button>
                            </div>
                        </div>
                    </td>
                    <td class='addQty'>
                        <div class='form-group'>
                            <div class='input-group'>
                            <input class='productQTYInput' type='number' min='0' placeholder='" . _uc($_e['Enter QTY Number']) . "'>
                            <button type='button' class='QTYSubmitBtn btn-primary' onclick='productStockAddQTYSubmit(this);' data-id='$id' value='" . _uc($_e['Add']) . "'>
                                <span>" . _uc($_e['Add']) . "</span>
                                <i class='fa fa-refresh waiting2 fa-spin' style='display: none;'></i>
                            </button>
                            </div>
                        </div>
                    </td>
                    <td class='removeQty'>
                    <div class='form-group'>
                            <div class='input-group'>
                            <input class='productQTYInput' type='number' min='0' placeholder='" . _uc($_e['Enter QTY Number']) . "'>
                            <button type='button' class='QTYSubmitBtn btn-danger' onclick='productStockSubQTYSubmit(this);' data-id='$id' value='" . _uc($_e['Deduct']) . "'>
                            <span>" . _uc($_e['Deduct']) . "</span>
                            <i class='fa fa-refresh waiting2 fa-spin' style='display: none;'></i>
                            </button>
                            </div>
                        </div>
                    </td>
                </tr>";

        }

        echo '
                </tbody>
                </table>
            </div> <!-- .table-responsive End -->';

        if ($count0Qty != 0) {
            $temp = _replace('{{qty}}', $count0Qty, $_e['{{qty}} Product Found With 0 Products in inventory.']);
            echo "<i ng-init=\"invenFun('$temp')\"></i>";
        }


    }

    private function productQtyPrint($view)
    {
        global $_e;
        $allowScale = false;
        $allowColor = false;
        echo '
            <div class="table-responsive">
                <table class="table table-hover dTableT tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                                    <th>'. _u($_e['PRODUCT THUMBNAIL']) .'</th>
                        <th>' . _u($_e['PRODUCT']) . '</th>
                        <th>' . _u($_e['CURRENT QTY']) . '</th>
                        <th>' . _u($_e['STORE NAME']) . '</th>';
        if ($this->functions->developer_setting('product_Scale') == '1') {
            echo '          <th>' . _u($_e['SCALE']) . '</th>';
            $allowScale = true;
        }
        if ($this->functions->developer_setting('product_color') == '1') {
            echo '          <th>' . _u($_e['COLOR']) . '</th>';
            $allowColor = true;
        }
        if ($view == 'add') {
            echo '          <th class="qtyAddTDLast">' . _u($_e['ADD QUANTITY']) . '</th>';
        } else if ($view == 'remove') {
            echo '            <th class="qtyRemoveTDLast">' . _u($_e['REMOVE QUANTITY']) . '</th>';
        }

        echo '      </thead>
                <tbody>';


        $storeData = $this->productF->storeSQL("`store_name`,`store_location`,`store_pk`");
        $storeSel = '';
        foreach ($storeData as $val) {
            $storeSel .= "<option value='$val[store_pk]'> $val[store_name] - $val[store_location] </option>";
        }
        if ($storeSel != '') {
            $storeSelect = "<select name='store' class='store selectCSS' onchange='selectChange(this);'>
                <option value='0'>" . _uc($_e['Select Store']) . "</option>
                    $storeSel
                </select>";
        } else {
            $storeSelect = '----';
        }

        $inventory = $this->productF->productSQL("*");

        $i = 0;
        foreach ($inventory as $val) {
            $i++;
            $pid = $val['prodet_id'];
            $storeId = '';
            $pName = $this->productF->getProductName($pid);
            if ($pName == false) {
                continue;
            }




             $productImage = $this->productF->productSpecialImage($pid, 'main');
            // $productThumb = $this->resizeImage($productImage, 300, 300, false);
            $productImage = WEB_URL . '/images/' . $productImage;
            $data_img = '<img src="'.$productImage.'" width="70px"/>';




            $qty = $this->productF->productQTY($pid);

            $scaleData = $this->productF->scaleSQL($pid, "`prosiz_name`,`prosiz_id`");
            $scaleSel = '';
            foreach ($scaleData as $val) {
                $scaleSel .= "<option value='$val[prosiz_id]'>$val[prosiz_name]</option>";
            }
            if ($scaleSel != '') {
                $scaleSelect = "<select name='scale' class='scale selectCSS' onchange='selectChange(this);'>
                <option value='0'>" . _uc($_e['Select Scale']) . "</option>
                    $scaleSel
                </select>";
            } else {
                $scaleSelect = '----';
            }

            $colorData = $this->productF->colorSQL($pid, "`proclr_name`,`propri_id`");
            $colorSel = '';
            foreach ($colorData as $val) {
                $colorSel .= "<option value='$val[propri_id]' style='background: #$val[proclr_name];color:#eee;'>$val[proclr_name]</option>";
            }
            if ($colorSel != '') {
                $colorSelect = "
                <select name='scale' class='color selectCSS' onchange='selectChange(this);'>
                    <option value='0'>" . _uc($_e['Select Color']) . "</option>
                  $colorSel
                </select>";
            } else {
                $colorSelect = '----';
            }


            if ($view == 'add') {
                echo "<tr id='Add$pid' data-id='$pid'>";
            } else {
                echo "<tr id='Remove$pid' data-id='$pid'>";
            }

            echo "<td>$i</td>

                  <td>$data_img</td>
                <td>$pName</td>
                <td><span class='currentQTY'>$qty</span>
                    <i class='fa fa-refresh waiting fa-spin' style='display: none;position: absolute;right: 10px;top: 35%;'></i>
                </td>
                <td>$storeSelect</td>";

            if ($allowScale) {
                echo "<td>$scaleSelect</td>";
            }
            if ($allowColor) {
                echo "<td>$colorSelect</td>";
            }
            echo "  <td><div class='form-group'>
                        <div class='input-group'>
                        <input class='productQTYInput' type='number' placeholder='" . _uc($_e['Enter QTY Number']) . "'>";
            if ($view == 'add') {
                echo "<button type='button' class='QTYSubmitBtn btn-primary' onclick='productAddQTYSubmit(this);' data-id='$pid' value='" . _uc($_e['Add']) . "'>
                        <span>" . _uc($_e['Add']) . "</span>";
            } elseif ($view == 'remove') {
                echo "<button type='button' class='QTYSubmitBtn btn-danger' onclick='productRemoveQTYSubmit(this);' data-id='$pid' value='" . _uc($_e['Deduct']) . "'>
                        <span>" . _uc($_e['Deduct']) . "</span>";
            }
            echo "     <i class='fa fa-refresh waiting2 fa-spin' style='display: none;'></i>
                        </button>

                        </div>
                    </div>

               </td>
            </tr>";

        }
        echo '
                </tbody>
                </table>
            </div> <!-- .table-responsive End -->';

    }

    public function addProductQty()
    {
        $this->productQtyPrint('add');
    }

    public function removeProductQty()
    {
        $this->productQtyPrint('remove');
        return false;
    }

    public function quickAddQty()
    {
        global $_e;
        $form_fields = array();

        $token = $this->functions->setFormToken("quickQty", false);

        $form_fields[] = array(
            "thisFormat" => "$token",
            "type" => "none",
        );

        $form_fields[] = array(
            "label" => "Add Qty in all size",
            "value" => "5000",
            "name" => "qty",
            "class" => "form-control",
            "type" => "number",
        );

        $form_fields[] = array(
            "label" => "submit",
            "name" => 'btn',
            'class' => 'btn btn-default',
            'type' => 'submit'
        );

        $form_fields['form'] = array(
            'type' => 'form',
            'method' => 'post',
            'format' => '<div class="form-horizontal">{{form}}</div>'
        );

        $format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';
        $this->functions->print_form($form_fields, $format);
    }

    public function quickAddQtySubmit()
    {
        global $_e;
        if (isset($_POST) && !empty($_POST['qty'])) {

            if (!$this->functions->getFormToken("quickQty")) {
                return false;
            }

            $qty = $_POST['qty'];
            try {
                $this->db->beginTransaction();

                //delete All previous
                $sql = "DELETE FROM product_inventory";
                $this->dbF->setRow($sql);

                $storeData = $this->productF->storeSQL("`store_pk`");
                $storeIds = '';
                foreach ($storeData as $val) {
                    $storeIds .= "$val[store_pk],";
                }
                $storeIds = trim($storeIds, ",");
                $storeIds = explode(",", $storeIds);
                if (empty($storeIds)) $storeIds = array('0');

                $inventory = $this->productF->productSQL("*");

                $i = 0;
                foreach ($inventory as $val) {
                    $i++;
                    $pid = $val['prodet_id'];
                    $pName = $this->productF->getProductName($pid);
                    if (empty($pName)) {
                        continue;
                    }

                    $scaleData = $this->productF->scaleSQL($pid, "`prosiz_id`");
                    $scaleSel = '';
                    foreach ($scaleData as $val) {
                        $scaleSel .= "$val[prosiz_id],";
                    }
                    $scaleSel = trim($scaleSel, ",");
                    $scaleSel = explode(",", $scaleSel);
                    if (empty($scaleSel)) $scaleSel = array('0');

                    $colorData = $this->productF->colorSQL($pid, "`propri_id`");
                    $colorSel = '';
                    foreach ($colorData as $val) {
                        $colorSel .= "$val[propri_id],";
                    }
                    $colorSel = trim($colorSel, ",");
                    $colorSel = explode(",", $colorSel);
                    if (empty($colorSel)) $colorSel = array('0');

                    //now print multi loops
                    //store loop -> size loop -> color loop

                    $sql = "INSERT INTO product_inventory (qty_store_id,qty_product_id,qty_product_scale,qty_product_color,qty_item,product_store_hash) VALUE";
                    $arry = array();
                    foreach ($storeIds as $storeID) {
                        foreach ($scaleSel as $scaleId) {
                            foreach ($colorSel as $colorId) {

                                $scaleId = empty($scaleId) ? 0 : $scaleId;
                                $colorId = empty($colorId) ? 0 : $colorId;
                                $storeID = empty($storeID) ? 0 : $storeID;

                                @$hashVal = $pid . ":" . $scaleId . ":" . $colorId . ":" . $storeID;
                                $hash = md5($hashVal);

                                $sql .= "(?,?,?,?,?,?),";

                                $arry[] = $storeID;
                                $arry[] = $pid;
                                $arry[] = $scaleId;
                                $arry[] = $colorId;
                                $arry[] = $qty;
                                $arry[] = $hash;

                            }//Store Loop
                        }//Store Loop

                    }//Store Loop

                    $sql = trim($sql, ",");
                    $this->dbF->setRow($sql, $arry);

                }

                $this->db->commit();
                $this->functions->notificationError(_js(_uc($_e["Success"])), _js($_e["Quick Add Qty"]), "btn-success");
                $this->functions->setlog(_uc($_e['Quick Add Qty']), 'Stock', '', _uc($_e['Quick Add Qty']) . $qty);

                $this->cleanInventory();

            } catch (Exception $e) {
                $this->db->rollBack();
                $this->functions->notificationError(_js(_uc($_e["Failed"])), _js($_e["Quick Add Qty"]), "btn-danger");
            }


        }
    }

}


?>