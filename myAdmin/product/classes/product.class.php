<?php
require_once (__DIR__ . "/../../product_management/classes/currency.class.php");
require_once (__DIR__ . "/../../product_management/classes/scale.class.php");
require_once (__DIR__ . "/../../product_management/classes/color.class.php");

class product extends object_class
{
    use global_setting;
    public $productF;
    public $c_scale;
    public $c_color;
    public $c_currency;
    public $pid;
    public $pidch;
    public $editPid;
    public $prefix_language = "lang";
    public $prefix_productBasicInformation = "pinfo";
    public $prefix_productCategory = "cats";
    public $prefix_scaleCheckBox = "scale_checkList";
    public $prefix_scaleName = "name_scale";
    public $prefix_scaleCost = "scaleAddCost";
    public $prefix_colorCheckBox = "color_checkList";
    public $prefix_colorName = "name_color";
    public $prefix_colorCost = "colorAddCost";
    public $prefix_setting = "setting";
    public $prefix_currencyArray = "curlist";
    public $prefix_addCostCheckBox = "addCost_checkList";
    public $prefix_addCostName = "addCost_name";
    public $prefix_addCostCost = "addCost_cost";
    public $prefix_editPro = "edit_pro";

    function __construct()
    {
        parent::__construct();
        if (isset($GLOBALS['productF']))
            $this->productF = $GLOBALS['productF'];
        else {
            require_once (__DIR__ . "/../../product_management/functions/product_function.php");
            $this->productF = new product_function();
        }

        $this->c_color = new colors();
        $this->c_scale = new scales();
        $this->c_currency = new currency_management();
        $this->additionalCost_name();

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/

        global $_e, $adminPanelLanguage;

        $_w['Sort Products'] = '';

        $_w['Select Product Category'] = '';

        $_w['There is an error, Please Refresh Page and Try Again'] = '';

        $_w['Product Update'] = '';

        $_w['Product Add'] = '';

        $_w['Your Product Update Successfully'] = '';

        $_w['Your New Product Add Successfully'] = '';

        $_w['Your Product Update Fail'] = '';

        $_w['Free Gift Product'] = '';

        $_w['When this product exist in cart then which free gift add in cart, please select'] = '';

        $_w['Mass Update'] = '';

        //This class

        $_w['Country (Currency)'] = '';

        $_w['Price'] = '';

        $_w['International Shipping'] = '';

        $_w['Price/Percent'] = '';

        $_w['Discount'] = '';

        $_w['Size Name'] = '';

        $_w['Weight In KG'] = '';

        $_w['Product Management'] = '';

        $_w['Add More Weight'] = '';

        $_w['Select Scale Group'] = '';

        $_w['Select Color Group'] = '';

        $_w['SNO'] = '';

        $_w['PRODUCT NAME'] = '';

        $_w['SHORT DESC'] = '';

        $_w['CREATE DATE'] = '';

        $_w['ACTION'] = '';

        $_w['Enter Alt'] = '';

        $_w['Update'] = '';

        $_w['Remove'] = '';

        $_w['Additional Saved Charges'] = '';

        $_w['Name'] = '';

        $_w['Active/DeActive Feature item'] = '';

        $_w['Active/DeActive Feature item2'] = '';

        $_w['Are you sure you want to {{state}} Feature Product?'] = '';

        $_w['Update Fail Please Try Again.'] = '';

        $_w['Active'] = '';

        $_w['DeActive'] = '';

        $_w['Are you sure you want to {{state}} Feature Item 2?'] = '';

        $_w['Charges On Offers'] = '';

        $_w['VIEWS'] = '';

        $_w['SALES'] = '';

        $_w['By Weight'] = '';

        $_w['Shipping Class'] = '';

        $_w['SALES'] = '';

        $_w['SELECT'] = '';

        $_w['PRODUCT THUMBNAIL'] = '';

        $_w['Please select categories and products both, It is mandatory'] = '';

        $_w['Product Category'] = '';

        $_w['Do not forget'] = '';

        $_w['Do Not Forget To Buy Products'] = '';

        $_w['Do Not Forget To Buy Products Popup'] = '';

        $_w['Missing Products'] = '';

        $_w['Missing'] = '';

        $_w['Missing Products Copied Successfully.'] = '';

        $_w['Enter Sub Heading'] = '';

        $_w['Enter Description'] = '';

        $_w[''] = '';

        $_w[''] = '';

        $_w[''] = '';

        $_w[''] = '';

        $_w[''] = '';

        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Product Class');
    }


    public function firstInsert()
    {
        $date = date('Y-m-d');
        try {
            $this->db->beginTransaction();
            $sqlD = "SELECT * FROM `proudct_detail` WHERE `product_update` = '0' AND `prodet_timeStamp` < '$date'";

            $dataDel = $this->dbF->getRows($sqlD, false);

            foreach ($dataDel as $key => $val) {
                $id = $val['prodet_id'];
                $sql3 = "SELECT * FROM `product_image` WHERE `product_id`= '$id'";
                $data = $this->dbF->getRows($sql3, false);
                
                foreach ($data as $key => $val) {
                    $this->functions->deleteOldSingleImage($val['image']);
                }

                $sql3 = "DELETE FROM `product_image` WHERE `product_id` = '$id'";
                $this->dbF->setRow($sql3, false);

                $sql3 = "DELETE FROM `proudct_detail` WHERE `prodet_id` = '$id'";
                $this->dbF->setRow($sql3);
            }
            
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }

        $sql = "INSERT INTO `proudct_detail` (`product_update`) VALUES ('0')";
        $lastId = $this->dbF->setRow($sql);
        $this->pid = $lastId;
    }



    public function editProductInformation()
    {
        if (isset($_POST[$this->prefix_editPro])) {
            $this->editPid = $_POST[$this->prefix_editPro];
            $this->pid = $_POST[$this->prefix_editPro];
        }
    }


    /**
     * @param $id
     * Add Product into database!
     * Get/Set Function for individual section.
     */

    private function editProductDelOld($id)
    {
        $sql = "DELETE  FROM  `product_color` WHERE  `proclr_prodet_id` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM  `product_addcost` WHERE  `proadc_prodet_id` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM  `product_size` WHERE  `prosiz_prodet_id` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM  `product_setting` WHERE  `p_id` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM  `product_price` WHERE  `propri_prodet_id` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM  `product_category` WHERE  `procat_prodet_id` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM `product_size_weight` WHERE `pwPId` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";

        $sql = "DELETE FROM product_size_custom WHERE `pId` = '$id'";
        $this->dbF->setRow($sql);

        $sql = "";
    }

    public function addProductInformation()
    {
        global $_e;

        if (!$this->functions->getFormToken('edit_pro')) {
            return false;
        }

        if (isset($_POST) && !empty($_POST) && isset($_POST['ProductNewId']) && !empty($_POST['ProductNewId'])) {
            $this->db->beginTransaction();
            $isEdit = false;
            
            if (isset($_POST['editProduct']) && $_POST['editProduct'] != "" && !isset($_POST['copy'])) {
                $this->editProductDelOld($_POST['editProduct']);
                $isEdit = true;
            }

            if (isset($_POST['copy']) && $_POST['copy'] == true) {
                $isEdit = true;
            }

            $this->pid = $_POST['ProductNewId'];
            $lang = explode(",", $_POST['lang']);
            $pinfo = $this->getset_proInformation($lang);

            if ($pinfo === true && $this->pid > 0) {
                $desc = "Setting Product Additional Information Prodcut ID : " . $this->pid;
                $status = $this->getset_categoryList1();
                $s = "<pre> cat:" . (($status) ? "ok" : "not") . "</pre>";
                $desc .= $s;
                $status = $this->getset_currency();
                $s = "<pre> currency:" . (($status) ? "ok" : "not") . "</pre>";
                $desc .= $s;
                $status = $this->getset_additionalCost();
                $s = "<pre> additional charges:" . (($status) ? "ok: $status item inserted!" : "not") . "</pre>";
                $desc .= $s;

                if ($isEdit)
                    $status = $this->getsetEdit_scales();
                else
                    $status = $this->getset_scales();
                $scale_status = $status;
                $s = "<pre> scale charges:" . (($status) ? "ok: $status item inserted!" : "not") . "</pre>";
                if ($status != false) {
                    //work For submit Size Weight
                    $this->set_scales_weight();
                }
                $desc .= $s;
                $custom = $this->customScaleSubmit();
                $s = "<pre> scale charges:" . (($status) ? "ok: $status item inserted!" : "not") . "</pre>";

                if ($status != false) {
                    //work For submit Size Weight
                    $this->set_scales_weight();
                }
                $desc .= $s;
                if ($isEdit) {
                    $status = $this->getsetEdit_colors();
                } else {
                    $status = $this->getset_colors();
                }
                $color_status = $status;
                $s = "<pre> color charges:" . (($status) ? "ok: $status item inserted!" : "not") . "</pre>";
                $desc .= $s;
                $status = $this->getset_setting();
                $s = "<pre> Product Setting:" . (($status) ? "ok: $status item inserted!" : "not") . "</pre>";
                $desc .= $s;
                $this->functions->setlog("Product Added", "Sharkspeed MyAmin Product", $this->pid, $desc);

                if ($this->editPid != "") {
                    $this->functions->notificationError(_js($_e["Product Update"]), _js($_e["Your Product Update Successfully"]), "btn-success");
                } else {
                    $this->functions->notificationError(_js($_e["Product Add"]), _js($_e["Your New Product Add Successfully"]), "btn-success");
                }
                $this->db->commit();
            } else {
                $this->functions->notificationError(_js($_e["Product Update"]), _js($_e["Your Product Update Fail"] . "<br>" . $this->dbF->rowException), "btn-danger");
                $this->db->rollBack();
            }
        }
    }



    private function customScaleSubmit()
    {
        if (isset($_POST['custom'])) {
            $pId = $this->pid;
            $new_pid = $pId;
            $val = $_POST['custom'];
            $typeId = empty($val['type_id']) ? "0" : $val['type_id'];
            $currency = empty($val['currencyId']) ? array() : $val['currencyId'];
            
            $sql = "INSERT INTO product_size_custom(type_id,pId,currencyId,price) VALUES";
            $sql_new = "INSERT INTO product_size_custom(type_id,pId,currencyId,price) VALUES";
            $array = array();
            $new_array = array();

            foreach ($currency as $key2 => $val2) {
                $currencyId = $key2;
                $price = $val2;
                $sql .= "(?,?,?,?),";
                $array[] = $typeId;
                $array[] = $pId;
                $array[] = $currencyId;
                $array[] = $price;
            }

            $sql = trim($sql, ",");


            if (!empty($array)) {

                $this->dbF->setRow($sql, $array);

            }

        }

    }



    private function getset_additionalCost()
    {
        if (isset($_POST[$this->prefix_addCostCheckBox]) && !empty($_POST[$this->prefix_addCostCheckBox])) {
            $pid = $this->pid;
            $newId = $pid;
            $val_array = array();
            $newArray = array();

            $sql = "INSERT INTO `product_addcost`

                    ( `proadc_name`,`proadc_prodet_id`,

                      `proadc_cur_id`,`proadc_price`

                    ) VALUES ";
                    

            foreach ($_POST[$this->prefix_addCostCheckBox] as $key => $val) {

                $postvar_name = $val . "-" . $this->prefix_addCostName;

                $postvar_cost = $val . "-" . $this->prefix_addCostCost;

                $charges_name = $_POST[$postvar_name];

                foreach ($_POST[$postvar_cost] as $key2 => $val2) {
                    $sql .= " (?,?,?,?) ,";
                    $val_array[] = $charges_name;
                    $val_array[] = $pid;
                    $val_array[] = intval($key2);
                    $val_array[] = doubleval($val2);
                }

            }

            $sql = trim($sql, ",");
            $qry = $this->dbF->setRow($sql, $val_array);

            if ($qry > 0)
                return $qry;
            else
                return false;

        }

    }



    private function getset_currency()
    {
        if (isset($_POST[$this->prefix_currencyArray]) && !empty($_POST[$this->prefix_currencyArray])) {
            $chf_form = $this->functions->ibms_setting('chfPriceFormula');
            $chf_form1 = explode(',', $chf_form);
            $chf_divide = $chf_form1[0];
            $chf_multiply = $chf_form1[1];
            

            if (is_array($_POST[$this->prefix_currencyArray])) {
                $sql = "INSERT INTO `product_price` (`propri_cur_id`,`propri_prodet_id`,`propri_price`,`propri_intShipping`) 
                VALUES ";
                $pid = $this->pid;
                $val_array = array();
                $foreach_loop = false;
                
                foreach ($_POST[$this->prefix_currencyArray] as $key => $val) {
                    if ($val > 0) {
                        $sql .= " (?,?,?,?) ,";
                        $val_array[] = $key;
                        $val_array[] = $pid;
                        $val_array[] = doubleval($val);
                        $intShipping_var = "intShipping_" . $key;
                        @$intShipping_val = intval($_POST[$intShipping_var]);
                        $val_array[] = $intShipping_val;

                        $foreach_loop = true;
                    }
                }
                
                $sql = trim($sql, ",");

                if ($foreach_loop) {
                    $pid_new = $pid;
                    $sek_price = intval($_POST[$this->prefix_currencyArray][20]);
                    $priceCalc = $this->functions->ibms_setting('priceCalc');
                    $priceCalc = unserialize($priceCalc);
                    $country_fr = $priceCalc['country'][0];
                    $fr_divide = $priceCalc['divide'][0];
                    $fr_multiply = $priceCalc['multiply'][0];
                    $fr_price = doubleval(($sek_price / $fr_divide) * $fr_multiply);
                    $fr_price = ceil($fr_price);
                    $country_de = $priceCalc['country'][1];
                    $de_divide = $priceCalc['divide'][1];
                    $de_multiply = $priceCalc['multiply'][1];
                    $de_price = doubleval(($sek_price / $de_divide) * $de_multiply);
                    $de_price = ceil($de_price);
                    $country_nl = $priceCalc['country'][2];
                    $nl_divide = $priceCalc['divide'][2];
                    $nl_multiply = $priceCalc['multiply'][2];
                    $nl_price = doubleval(($sek_price / $nl_divide) * $nl_multiply);
                    $nl_price = ceil($nl_price);
                    $country_us = $priceCalc['country'][3];
                    $us_divide = $priceCalc['divide'][3];
                    $us_multiply = $priceCalc['multiply'][3];
                    $us_price = doubleval(($sek_price / $us_divide) * $us_multiply);
                    $us_price = ceil($us_price);
                    $country_be = $priceCalc['country'][4];
                    $be_divide = $priceCalc['divide'][4];
                    $be_multiply = $priceCalc['multiply'][4];
                    $be_price = doubleval(($sek_price / $be_divide) * $be_multiply);
                    $be_price = ceil($be_price);
                    $country_uk = $priceCalc['country'][5];
                    $uk_divide = $priceCalc['divide'][5];
                    $uk_multiply = $priceCalc['multiply'][5];
                    $uk_price = doubleval(($sek_price / $uk_divide) * $uk_multiply);
                    $uk_price = ceil($uk_price);
                    $country_es = $priceCalc['country'][6];
                    $es_divide = $priceCalc['divide'][6];
                    $es_multiply = $priceCalc['multiply'][6];
                    $es_price = doubleval(($sek_price / $es_divide) * $es_multiply);
                    $es_price = ceil($es_price);
                    $country_at = $priceCalc['country'][7];
                    $at_divide = $priceCalc['divide'][7];
                    $at_multiply = $priceCalc['multiply'][7];
                    $at_price = doubleval(($sek_price / $at_divide) * $at_multiply);
                    $at_price = ceil($at_price);
                    $country_it = $priceCalc['country'][8];
                    $it_divide = $priceCalc['divide'][8];
                    $it_multiply = $priceCalc['multiply'][8];
                    $it_price = doubleval(($sek_price / $it_divide) * $it_multiply);
                    $it_price = ceil($it_price);
                    $country_chf = $priceCalc['country'][9];
                    $chf_divide = $priceCalc['divide'][9];
                    $chf_multiply = $priceCalc['multiply'][9];
                    $chf_price = doubleval(($sek_price / $chf_divide) * $chf_multiply);
                    $chf_price = ceil($chf_price);
                    $country_other = $priceCalc['country'][10];
                    $other_divide = $priceCalc['divide'][10];
                    $other_multiply = $priceCalc['multiply'][10];
                    $other_price = doubleval(($sek_price / $other_divide) * $other_multiply);
                    $other_price = ceil($other_price);
                    $intShipping_varSEK = "intShipping_20";
                    @$intShipping_valSEK = intval($_POST[$intShipping_varSEK]);
                    $sql_new = "INSERT INTO `product_price` (`propri_cur_id`,`propri_prodet_id`,`propri_price`,
                    `propri_intShipping`) VALUES 
                                ($country_fr, '$pid_new', $fr_price, $intShipping_valSEK),
                                ($country_de, '$pid_new', $de_price, $intShipping_valSEK),
                                ($country_nl, '$pid_new', $nl_price, $intShipping_valSEK),
                                ($country_us, '$pid_new', $us_price, $intShipping_valSEK),
                                ($country_be, '$pid_new', $be_price, $intShipping_valSEK),
                                ($country_uk, '$pid_new', $uk_price, $intShipping_valSEK),
                                ($country_es, '$pid_new', $es_price, $intShipping_valSEK),
                                ($country_at, '$pid_new', $at_price, $intShipping_valSEK),
                                ($country_it, '$pid_new', $it_price, $intShipping_valSEK),
                                ($country_chf, '$pid_new', $chf_price, $intShipping_valSEK),
                                ($country_other, '$pid_new', $other_price, $intShipping_valSEK)";
                    $qry = $this->dbF->setRow($sql, $val_array);
                    
                    
                    if ($qry > 0)
                        return $qry;
                    else
                        return false;

                } else
                    return false;
            }

        }

    }



    private function getset_colors()
    {
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

        if (isset($_POST[$this->prefix_colorCheckBox]) && !empty($_POST[$this->prefix_colorCheckBox])) {
            $pid = $this->pid;
            $val_array = array();
            $val_array1 = array();
            
            $sql = "INSERT INTO `product_color` (`proclr_name`,`color_name`,`proclr_prodet_id`,`proclr_cur_id`,`proclr_price`,`sizeGroup`) VALUES ";
            
            foreach ($_POST[$this->prefix_colorCheckBox] as $key => $val) {
                $postvar_name = $val . "-" . $this->prefix_colorName;
                $postvar_cost = $val . "-" . $this->prefix_colorCost;
                $color_name = $_POST[$postvar_name];
                
                foreach ($_POST[$postvar_cost] as $key2 => $val2) {
                    $sql .= " (?,?,?,?,?,?) ,";
                    $val_array[] = $color_name;
                    $val_array[] = HexToColorName($color_name);
                    $val_array[] = $pid;
                    $val_array[] = intval($key2);
                    $val_array[] = doubleval($val2);
                    $val_array[] = $_POST['sizeGroup_color'];
                }
            }

            $sql = trim($sql, ",");
            $qry = $this->dbF->setRow($sql, $val_array);

            if ($qry > 0) {
                return $qry;
            } else
                return false;

        }

    }





    private function getsetEdit_colors()
    {
        if (isset($_POST[$this->prefix_colorCheckBox]) && !empty($_POST[$this->prefix_colorCheckBox])) {
            
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
            
            $pid = $this->pid;
            $new_pid = $pid;
            $val_array = array();
            $val_array2 = array();
            $new_array = array();
            $new_array2 = array();
            $record = array();
            $curInsert = array();
            
            $sql = "INSERT INTO `product_color` (`propri_id`,`proclr_name`,`color_name`,`proclr_prodet_id`, 
            `proclr_cur_id`,`proclr_price`,`sizeGroup`) VALUES ";
            
            $sql_new = "INSERT INTO `product_color` (`propri_id`,`proclr_name`,`color_name`,`proclr_prodet_id`, 
            `proclr_cur_id`,`proclr_price`,`sizeGroup`) VALUES (?,?,?,?,?,?,?)";

            $tempData = false;

            foreach ($_POST[$this->prefix_colorCheckBox] as $key => $val) {
                $exc = explode('_', $val);
                if (isset($exc[3])) {
                    $oldId = $exc[3];
                    //Old id. override id in db,, try to save old color primary id
                } else {
                    continue;
                }
                
                $postvar_name = $val . "-" . $this->prefix_colorName;
                $postvar_cost = $val . "-" . $this->prefix_colorCost;
                $color_name = $_POST[$postvar_name];
                
                foreach ($_POST[$postvar_cost] as $key2 => $val2) {
                    if (in_array($oldId, $record) && $oldId != '0') {
                        continue;
                    } else {
                        $record[] = $exc[3];
                        $curInsert[] = $key2;
                    }

                    $sql .= " ('$oldId',?,?,?,?,?,?) ,";
                    $val_array[] = $color_name;
                    $val_array[] = HexToColorName($color_name);
                    $val_array[] = $pid;
                    $val_array[] = intval($key2);
                    $val_array[] = doubleval($val2);
                    $val_array[] = $_POST['sizeGroup_color'];
                    
                    $tempData = true;
                }
            }

            if ($tempData) {
                $sql = trim($sql, ",");
                $sql_new = trim($sql_new, ",");
                $qry = $this->dbF->setRow($sql, $val_array);
                
            } else {
                $qry = 0;
            }
            
            $tempData = false;
            
            $sql = "INSERT INTO `product_color` (`proclr_name`,`color_name`,`proclr_prodet_id`, `proclr_cur_id`,
            `proclr_price`,`sizeGroup`) VALUES ";
            
            foreach ($_POST[$this->prefix_colorCheckBox] as $key => $val) {
                $exc = explode('_', $val);
                if (isset($exc[3])) {
                    $oldId = $exc[3];
                }

                $postvar_name = $val . "-" . $this->prefix_colorName;
                $postvar_cost = $val . "-" . $this->prefix_colorCost;
                $color_name = $_POST[$postvar_name];
                
                foreach ($_POST[$postvar_cost] as $key2 => $val2) {
                    if (in_array($key2, $curInsert)) {
                        if (isset($exc[3])) {
                            continue;
                        }
                    } else {
                    }

                    $sql .= " (?,?,?,?,?,?) ,";
                    $val_array2[] = $color_name;
                    $val_array2[] = HexToColorName($color_name);
                    $val_array2[] = $pid;
                    $val_array2[] = intval($key2);
                    $val_array2[] = doubleval($val2);
                    $val_array2[] = $_POST['sizeGroup_color'];
                    
                    $tempData = true;
                }
            }

            if ($tempData) {
                $sql = trim($sql, ",");
                $qry2 = $this->dbF->setRow($sql, $val_array2);
                
                $sql_get = "SELECT * FROM `product_color` WHERE `proclr_prodet_id` = ? AND `proclr_cur_id` = 20";
                $res = $this->dbF->getRows($sql_get, array($pid));
                
            } else {
                $qry2 = 0;
            }
            
            $qry = $qry + $qry2;
            
            if ($qry > 0)
                return $qry;
            else
                return false;
        }
    }

    private function getset_setting()
    {
        $pid = $this->pid;
        $new_pid = $pid;

        if (isset($_POST[$this->prefix_setting]) && !empty($_POST[$this->prefix_setting])) {
            $setting = $_POST[$this->prefix_setting];

            $sql = "INSERT INTO `product_setting`(`p_id`,`setting_name`,`setting_val`) VALUES ";
            
            foreach ($setting as $key => $val) {
                $sql .= "('$pid',?,?) ,";
                if (is_array($val) && $key != "productConnectKeywords") {
                    $val = serialize($val);
                }elseif($key == "productConnectKeywords"){
                    $val = implode(',', $val);
                }
                $arry[] = $key;
                $arry[] = $val;
            }
            
            $sql = trim($sql, ",");
            $this->dbF->setRow($sql, $arry);
            
            
            if ($this->dbF->rowCount > 0)
                return true;
            else
                return false;

        } else
            return false;


    }



    /**

     * @param $lang

     * @return bool

     */

    /**
 * Normalize category IDs into a clean integer array.
 *
 * @param mixed $categoryIds
 * @return array
 */
private function normalizeCategoryIds($categoryIds)
{
    if (empty($categoryIds)) {
        return [];
    }

    /*
     * Convert comma-separated values into an array.
     */
    if (is_string($categoryIds)) {
        $categoryIds = explode(',', $categoryIds);
    }

    /*
     * Convert a single integer value into an array.
     */
    if (!is_array($categoryIds)) {
        $categoryIds = [$categoryIds];
    }

    $cleanCategoryIds = [];

    foreach ($categoryIds as $categoryId) {

        /*
         * Handle nested category arrays.
         */
        if (is_array($categoryId)) {
            foreach ($categoryId as $nestedId) {
                $nestedId = (int)$nestedId;

                if ($nestedId > 0) {
                    $cleanCategoryIds[] = $nestedId;
                }
            }

            continue;
        }

        $categoryId = (int)$categoryId;

        if ($categoryId > 0) {
            $cleanCategoryIds[] = $categoryId;
        }
    }

    return array_values(array_unique($cleanCategoryIds));
}


/**
 * Save product basic information.
 *
 * @param mixed $lang
 * @return bool
 */
private function getset_proInformation($lang)
{
    if (
        !isset($_POST[$this->prefix_productBasicInformation]) ||
        !is_array($_POST[$this->prefix_productBasicInformation]) ||
        empty($_POST[$this->prefix_productBasicInformation])
    ) {
        return false;
    }

    $pinfo = $_POST[$this->prefix_productBasicInformation];
    $id = (int)$this->pid;
    $addedOn = date('Y-m-d H:i:s');

    /*
     * Prevent undefined array key warnings.
     */
    $productName = [];

    if (isset($pinfo['name']) && is_array($pinfo['name'])) {
        $productName = $pinfo['name'];
    }

    /*
     * Support both sdesc and shortDesc field names.
     */
    $productShortDescription = [];

    if (isset($pinfo['sdesc']) && is_array($pinfo['sdesc'])) {
        $productShortDescription = $pinfo['sdesc'];
    } elseif (
        isset($pinfo['shortDesc']) &&
        is_array($pinfo['shortDesc'])
    ) {
        $productShortDescription = $pinfo['shortDesc'];
    }

    $listing = isset($pinfo['listing'])
        ? $pinfo['listing']
        : 0;

    $buyingPrice = isset($pinfo['buyingPrice'])
        ? (float)$pinfo['buyingPrice']
        : 0;

    $submittedSlug = isset($pinfo['slug'])
        ? trim((string)$pinfo['slug'])
        : '';

    /*
     * Create slug from product name when the slug field is empty.
     */
    if ($submittedSlug === '' && !empty($productName)) {
        $firstProductName = reset($productName);
        $submittedSlug = (string)$firstProductName;
    }

    $slug = sanitize_slug($submittedSlug);

    if ($slug === '') {
        $slug = 'product-' . $id;
    }

    $refId = $this->db->productDetail . $id;

    /*
     * Check whether another record already uses this slug.
     */
    $slugAvailable = $this->functions->check_slug_duplicate(
        $slug,
        $refId
    );

    if (!$slugAvailable) {
        $slug .= '-' . $id;
    }

    $sql = "
        UPDATE `proudct_detail`
        SET
            `prodet_name`      = ?,
            `slug`             = ?,
            `prodet_shortDesc` = ?,
            `prodet_addOn`     = ?,
            `product_update`   = ?,
            `listing`          = ?,
            `buying_price`     = ?
        WHERE `prodet_id` = ?
    ";

    $array = [
        serialize($productName),
        $slug,
        serialize($productShortDescription),
        $addedOn,
        1,
        $listing,
        $buyingPrice,
        $id
    ];

    $this->dbF->setRow($sql, $array);

    if ($this->dbF->hasException) {
        return false;
    }

    /*
     * Pass clean product data to the SEO method.
     */
    $seoArray = [
        'id'          => $id,
        'slug'        => $slug,
        'name'        => $productName,
        'sdesc'       => $productShortDescription,
        'listing'     => $listing,
        'buyingPrice' => $buyingPrice
    ];

    $this->product_seo($seoArray);

    return !$this->dbF->hasException;
}


/**
 * Insert or update product SEO data.
 *
 * @param array $productData
 * @return bool
 */
private function product_seo($productData)
{
    $result = false;

    $pinfo = [];

    if (
        isset($_POST[$this->prefix_productBasicInformation]) &&
        is_array($_POST[$this->prefix_productBasicInformation])
    ) {
        $pinfo = $_POST[$this->prefix_productBasicInformation];
    }

    /*
     * Get product names safely.
     */
    $productNames = [];

    if (
        isset($productData['name']) &&
        is_array($productData['name'])
    ) {
        $productNames = $productData['name'];
    } elseif (
        isset($pinfo['name']) &&
        is_array($pinfo['name'])
    ) {
        $productNames = $pinfo['name'];
    }

    /*
     * Get product short descriptions safely.
     */
    $productDescriptions = [];

    if (
        isset($productData['sdesc']) &&
        is_array($productData['sdesc'])
    ) {
        $productDescriptions = $productData['sdesc'];
    } elseif (
        isset($pinfo['sdesc']) &&
        is_array($pinfo['sdesc'])
    ) {
        $productDescriptions = $pinfo['sdesc'];
    } elseif (
        isset($pinfo['shortDesc']) &&
        is_array($pinfo['shortDesc'])
    ) {
        $productDescriptions = $pinfo['shortDesc'];
    }

    /*
     * Support your configured category field and a direct "cats" field.
     */
    $submittedCategories = [];

    if (isset($_POST[$this->prefix_productCategory])) {
        $submittedCategories =
            $_POST[$this->prefix_productCategory];
    } elseif (isset($_POST['cats'])) {
        $submittedCategories = $_POST['cats'];
    }

    $categoryIds = $this->normalizeCategoryIds(
        $submittedCategories
    );

    $id = isset($productData['id'])
        ? (int)$productData['id']
        : (int)$this->pid;

    $slug = isset($productData['slug'])
        ? sanitize_slug((string)$productData['slug'])
        : '';

    if ($slug === '') {
        $slug = 'product-' . $id;
    }

    $refId = $this->db->productDetail . $id;
    $pageLink = '/' . sanitize_slug(
        $this->db->productDetail . $slug
    );

    /*
     * Add SEO data.
     */
    if (
        !isset($_POST['edit_proSubmit']) ||
        empty($_POST['edit_proSubmit'])
    ) {
        $public = 1;
        $categoryNames = [];

        /*
         * Load category names only when categories were submitted.
         */
        if (!empty($categoryIds)) {
            $placeholders = implode(
                ',',
                array_fill(0, count($categoryIds), '?')
            );

            $sqlCategory = "
                SELECT `name`
                FROM `categories`
                WHERE `id` IN ($placeholders)
            ";

            $categoryResults = $this->dbF->getRows(
                $sqlCategory,
                $categoryIds
            );

            if (is_array($categoryResults)) {
                foreach ($categoryResults as $categoryResult) {
                    $translatedCategory = translateFromSerialize(
                        $categoryResult['name'] ?? ''
                    );

                    $translatedCategory = trim(
                        (string)$translatedCategory
                    );

                    if ($translatedCategory !== '') {
                        $categoryNames[] = $translatedCategory;
                    }
                }
            }
        }

        $categoryNamesString = implode(', ', $categoryNames);

        /*
         * Load configured languages safely.
         */
        $languageSetting = $this->functions->ibms_setting(
            'Languages'
        );

        $languages = @unserialize($languageSetting);

        if (!is_array($languages) || empty($languages)) {
            $languages = array_keys($productNames);
        }

        if (empty($languages)) {
            $languages = ['English'];
        }

        /*
         * Generate SEO keywords for each language.
         */
        $keywords = [];

        foreach ($languages as $language) {
            $productName = isset($productNames[$language])
                ? trim((string)$productNames[$language])
                : '';

            $productDescription =
                isset($productDescriptions[$language])
                    ? trim((string)$productDescriptions[$language])
                    : '';

            $keywordParts = [];

            if ($productName !== '') {
                $keywordParts[] = $productName;
            }

            if ($categoryNamesString !== '') {
                $keywordParts[] = $categoryNamesString;
            }

            if ($productDescription !== '') {
                $keywordParts[] = $productDescription;
            }

            $keywords[$language] = implode(
                ', ',
                $keywordParts
            );
        }

        $serializedName = serialize($productNames);
        $serializedDescription = serialize(
            $productDescriptions
        );
        $serializedKeywords = serialize($keywords);

        $sqlSeo = "
            INSERT INTO `seo`
            (
                `pageLink`,
                `ref_id`,
                `slug`,
                `title`,
                `seo_title`,
                `keywords`,
                `dsc`,
                `sIndex`,
                `sFollow`,
                `rewriteTitle`,
                `revisit-after`,
                `type`,
                `publish`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $seoId = $this->dbF->setRow(
            $sqlSeo,
            [
                $pageLink,
                $refId,
                $slug,
                $serializedName,
                $serializedName,
                $serializedKeywords,
                $serializedDescription,
                1,
                1,
                1,
                '1 week',
                'product',
                $public
            ]
        );

        /*
         * Generate category-based URLs only when categories exist.
         */
        if (
            !empty($categoryIds) &&
            !empty($seoId)
        ) {
            $this->generate_category_slugs(
                $slug,
                $categoryIds,
                $languages,
                $seoId
            );
        }

        if (!$this->dbF->hasException) {
            $result = true;
        }
    } else {
        /*
         * Update existing SEO URL and slug.
         */
        $sqlSeo = "
            UPDATE `seo`
            SET
                `pageLink` = ?,
                `slug` = ?
            WHERE `ref_id` LIKE ?
        ";

        $this->dbF->setRow(
            $sqlSeo,
            [
                $pageLink,
                $slug,
                '%' . $refId . '%'
            ]
        );

        if (!$this->dbF->hasException) {
            $result = true;
        }
    }

    return $result;
}


/**
 * Generate category-based product URLs.
 *
 * @param string $productSlug
 * @param mixed  $categoryIds
 * @param array  $languages
 * @param int    $seoId
 * @return bool
 */
private function generate_category_slugs(
    $productSlug,
    $categoryIds,
    $languages,
    $seoId
) {
    $categoryIds = $this->normalizeCategoryIds($categoryIds);

    /*
     * Stop here when no categories were submitted.
     */
    if (empty($categoryIds)) {
        return true;
    }

    if (!is_array($languages) || empty($languages)) {
        $languages = ['English'];
    }

    $placeholders = implode(
        ',',
        array_fill(0, count($categoryIds), '?')
    );

    $sql = "
        SELECT
            `id`,
            `name`,
            `under`
        FROM `categories`
        WHERE `id` IN ($placeholders)
    ";

    $categoryResults = $this->dbF->getRows(
        $sql,
        $categoryIds
    );

    if (
        empty($categoryResults) ||
        !is_array($categoryResults)
    ) {
        return true;
    }

    $categories = [];

    foreach ($categoryResults as $categoryResult) {
        $categoryId = isset($categoryResult['id'])
            ? (int)$categoryResult['id']
            : 0;

        if ($categoryId <= 0 || $categoryId === 1) {
            continue;
        }

        $categories[$categoryId] = [
            'name'  => $categoryResult['name'] ?? '',
            'under' => isset($categoryResult['under'])
                ? (int)$categoryResult['under']
                : 0
        ];
    }

    if (empty($categories)) {
        return true;
    }

    $productInfo = [];

    if (
        isset($_POST[$this->prefix_productBasicInformation]) &&
        is_array($_POST[$this->prefix_productBasicInformation])
    ) {
        $productInfo =
            $_POST[$this->prefix_productBasicInformation];
    }

    $productNames = [];

    if (
        isset($productInfo['name']) &&
        is_array($productInfo['name'])
    ) {
        $productNames = $productInfo['name'];
    }

    $productDescriptions = [];

    if (
        isset($productInfo['sdesc']) &&
        is_array($productInfo['sdesc'])
    ) {
        $productDescriptions = $productInfo['sdesc'];
    } elseif (
        isset($productInfo['shortDesc']) &&
        is_array($productInfo['shortDesc'])
    ) {
        $productDescriptions =
            $productInfo['shortDesc'];
    }

    $productSlug = sanitize_slug((string)$productSlug);
    $seoId = (int)$seoId;
    $productId = (int)$this->pid;

    $groupId = 1;

    foreach ($languages as $language) {
        foreach ($categories as $categoryId => $category) {
            $path = [];

            $translatedName = translateFromSerialize(
                $category['name'],
                true,
                false,
                $language
            );

            $translatedName = sanitize_slug(
                (string)$translatedName
            );

            if ($translatedName !== '') {
                $path[] = $translatedName;
            }

            $path[] = $productSlug;

            $generatedSlug = implode('/', $path);

            $schemaName = isset($productNames[$language])
                ? (string)$productNames[$language]
                : '';

            $schemaDescription =
                isset($productDescriptions[$language])
                    ? (string)$productDescriptions[$language]
                    : '';

            $schema = [
                '@context'    => 'https://schema.org',
                '@type'       => 'Product',
                'name'        => $schemaName,
                'description' => $schemaDescription,
                'productID'   => $productId
            ];

            $structuredSchema = json_encode(
                $schema,
                JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE
            );

            if ($structuredSchema === false) {
                $structuredSchema = '{}';
            }

            $sqlInsert = "
                INSERT INTO `seo_slug`
                (
                    `seo_id`,
                    `slug`,
                    `lang`,
                    `group_id`,
                    `schema_markup`
                )
                VALUES (?, ?, ?, ?, ?)
            ";

            $this->dbF->setRow(
                $sqlInsert,
                [
                    $seoId,
                    $generatedSlug,
                    $language,
                    $groupId,
                    $structuredSchema
                ]
            );

            $groupId++;
        }
    }

    return !$this->dbF->hasException;
}



    private function getset_categoryList1()
    {
        if (isset($_POST[$this->prefix_productCategory]) && !empty($_POST[$this->prefix_productCategory])) {
            $cat_format = "";

            foreach ($_POST[$this->prefix_productCategory] as $key => $value) {
                $cat_format .= $value . ',';
            }

            $cat = $_POST[$this->prefix_productCategory];
            $sql = "INSERT INTO `product_category` (`procat_cat_id`,`procat_prodet_id`) VALUES (?,?)";
            $pid = $this->pid;
            $arry = array($cat_format, $pid);
            $this->dbF->setRow($sql, $arry);
                

            if ($this->dbF->rowCount > 0)
                return $this->dbF->rowCount;
            else
                return false;
        } else {
            return false;
        }
    }



    private function getsetEdit_scales()
    {
        if (isset($_POST[$this->prefix_scaleCheckBox]) && !empty($_POST[$this->prefix_scaleCheckBox])) {

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



            $pid = $this->pid;

            $new_pid = $pid;

            $val_array = array();

            $val_array2 = array();

            $new_array = array();

            $new_array2 = array();



            $sql = "INSERT INTO `product_size` (`prosiz_id`, `prosiz_name`, `prosiz_prodet_id`, `prosiz_cur_id`, `prosiz_price`, `sizeGroup`, `sort`) VALUES ";

            $tempData = false;
            $record = array();
            $curInsert = array();

            foreach ($_POST[$this->prefix_scaleCheckBox] as $key => $val) {

                //sc_99_227_246
                $postvar_name = $val . "-" . $this->prefix_scaleName;
                $postvar_cost = $val . "-" . $this->prefix_scaleCost;
                $exc = explode('_', $val);
                $sort = $key;
                if (isset($exc[3])) {
                    $oldId = $exc[3];
                } else {
                    continue;
                }

                $scale_name = $_POST[$postvar_name];

                foreach ($_POST[$postvar_cost] as $key2 => $val2) {
                    if (in_array($oldId, $record) && $oldId != '0') {
                        continue;
                    } else {
                        $record[] = $exc[3];
                        $curInsert[] = $key2;
                    }
                    
                    $sql .= " ('$oldId',?,?,?,?,?,?) ,";
                    $val_array[] = $scale_name;
                    $val_array[] = $pid;
                    $val_array[] = intval($key2);
                    $val_array[] = doubleval($val2);
                    $val_array[] = $_POST['sizeGroup_scale'];
                    $val_array[] = $sort;

                    $tempData = true;
                }
            }
            
            if ($tempData) {
                $sql = trim($sql, ",");
                $qry1 = $this->dbF->setRow($sql, $val_array);
            } else {
                $qry1 = 0;
            }

            $tempData = false;

            $sql = "INSERT INTO `product_size` (`prosiz_name`, `prosiz_prodet_id`, `prosiz_cur_id`, `prosiz_price`, `sizeGroup`, `sort`) VALUES ";

            foreach ($_POST[$this->prefix_scaleCheckBox] as $key => $val) {
                $postvar_name = $val . "-" . $this->prefix_scaleName;
                $postvar_cost = $val . "-" . $this->prefix_scaleCost;
                $exc = explode('_', $val);
                $sort = $key;
                if (isset($exc[3])) {
                    $oldId = $exc[3];
                }

                $scale_name = $_POST[$postvar_name];

                foreach ($_POST[$postvar_cost] as $key2 => $val2) {
                    if (in_array($key2, $curInsert)) {
                        if (isset($exc[3])) {
                            continue;
                        }
                    } else {
                    }

                    $sql .= " (?,?,?,?,?,?) ,";
                    $val_array2[] = $scale_name;
                    $val_array2[] = $pid;
                    $val_array2[] = intval($key2);
                    $val_array2[] = doubleval($val2);
                    $val_array2[] = $_POST['sizeGroup_scale'];
                    $val_array2[] = $sort;
                    
                    $tempData = true;
                }
            }

            if ($tempData) {
                $sql = trim($sql, ",");
                $qry2 = $this->dbF->setRow($sql, $val_array2);
            } else {
                $qry2 = 0;
            }

            $qry = $qry1 + $qry2;

            if ($qry > 0)
                return $qry;
            else
                return false;

        }

    }



    private function set_scales_weight()
    {
        if (isset($_POST['sizeWeightName']) && !empty($_POST['sizeWeightName'])) {
            $pId = $this->pid;
            foreach ($_POST['sizeWeightName'] as $key => $sizeName) {
                $sizeWeight = $_POST['sizeWeight'][$key];
                $sizeWeight = floatval($sizeWeight);
                
                if ($sizeName != '' && $sizeWeight != '') {
                    $hash = "$pId:$sizeName:$sizeWeight";
                    $hash = md5($hash);

                    $sql = "INSERT INTO `product_size_weight` (`pwPId`, `pw_size`, `pw_weight`, `pw_unique`) 
                    SELECT * FROM ( SELECT ?,?,?,?) AS tmp WHERE NOT EXISTS 
                    ( SELECT pw_unique FROM product_size_weight WHERE pw_unique= ?) LIMIT 1";
                    $arry = array($pId, $sizeName, $sizeWeight, $hash, $hash);
                    $this->dbF->setRow($sql, $arry);
                
                }
            }
        }
    }



    private function getset_scales()
    {
        
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

        if (isset($_POST[$this->prefix_scaleCheckBox]) && !empty($_POST[$this->prefix_scaleCheckBox])) {
            $pid = $this->pid;
            $val_array = array();
            $val_array1 = array();
            
            $sql = "INSERT INTO `product_size` (`prosiz_name`, `prosiz_prodet_id`, `prosiz_cur_id`, `prosiz_price`, `sizeGroup`, `sort`) VALUES ";

            foreach ($_POST[$this->prefix_scaleCheckBox] as $key => $val) {
                $postvar_name = $val . "-" . $this->prefix_scaleName;
                $postvar_cost = $val . "-" . $this->prefix_scaleCost;
                $scale_name = $_POST[$postvar_name];
                $sort = $key;
                foreach ($_POST[$postvar_cost] as $key2 => $val2) {

                    $sql .= " (?,?,?,?,?,?) ,";
                    $val_array[] = $scale_name;
                    $val_array[] = $pid;
                    $val_array[] = intval($key2);
                    $val_array[] = doubleval($val2);
                    $val_array[] = intval($_POST['sizeGroup_scale']);
                    $val_array[] = $sort;
                }

            }

            $sql = trim($sql, ",");
            $qry = $this->dbF->setRow($sql, $val_array);

            if ($qry > 0) {
                return $qry;
            } else
                return false;

        }

    }



    /*****************************************************/





    public function createPricingViewSystem()
    {

        global $_e;

        $countryCodeList = $this->functions->countrylist();



        echo "<div class='table-responsive '><table class='table table-striped table-hover'>

                <thead>

                    <tr>

                        <th>" . _uc($_e['Country (Currency)']) . "</th>

                        <th>" . _uc($_e['Price']) . "</th>

                        <th style='white-space: nowrap'>" . _uc($_e['International Shipping']) . "</th>

                    </tr>

                </thead>

                ";



        foreach ($this->c_currency->getList() as $data) {

            $country_name = $countryCodeList[$data['cur_country']];

            $currency = $data["cur_name"];

            $symbol = $data['cur_symbol'];

            $currency_id = $data['cur_id'];



            $ePrice = "";

            $eShip = "";



            if ($this->prefix_editPro != "") {

                // If product is edit mode

                $eId = $this->editPid;

                $qry = "SELECT * FROM  `product_price` WHERE `propri_prodet_id` = '$eId' AND `propri_cur_id` = '$currency_id'";

                $eData = $this->dbF->getRow($qry);

                if ($this->dbF->rowCount > 0) {

                    $ePrice = $eData['propri_price'];

                    if ($eData['propri_intShipping'] == '1') {

                        $eShip = 'checked';

                    }

                }

            }



            echo "

            <tr>

                <td style='white-space: nowrap;'>

                    $country_name ($currency):

                </td>



                <td>

                    <div class='input-group input-group-sm'>

                        <span class='input-group-addon'>$symbol</span>

                        <input type='text' pattern='\d+(\.\d+)?' value='$ePrice' name='$this->prefix_currencyArray[$currency_id]' class='form-control' placeholder='00.00'>

                    </div>

                </td>



                <td style='text-align: center;'>

                    <div class='make-switch' data-on='success' data-off='danger' >

                        <input type='checkbox' value='1' $eShip  name='intShipping_$currency_id' >

                    </div>

                </td>

            </tr>

            ";

        }

        echo "</table></div> ";

    }



    public function discountPriceArrayFind($data, $findKey, $returnKey, $page)
    {

        $find = 'product_dis_curr_Id';

        if ($page == 'saleForm') {

            $find = 'pSale_price_curr_Id';

        }



        foreach ($data as $val) {

            if ($val[$find] == $findKey) {

                return $val[$returnKey];

            }

        }

        return '';

    }



    public function discountPricingViewSystem($page = 'add', $edit = false, $priceData = false)
    {



        global $_e;

        $countryCodeList = $this->functions->countrylist();

        echo "<div class='table-responsive '><table class='table table-striped table-hover'>

                <thead>

                    <tr>

                        <th>" . _uc($_e['Country (Currency)']) . "</th>

                        <th>" . _uc($_e['Price/Percent']) . "</th>

                        <th style='white-space: nowrap'>" . _uc($_e['Discount']) . "</th>

                    </tr>

                </thead>

                ";



        foreach ($this->c_currency->getList() as $data) {

            $country_name = $countryCodeList[$data['cur_country']];

            $currency = $data["cur_name"];

            $symbol = $data['cur_symbol'];

            $currency_id = $data['cur_id'];



            $ePrice = "";

            $eShip = "";



            if ($edit) {

                // If product is edit mode

                $eData = $priceData;

                if ($page == 'saleForm') {

                    $ePrice = $this->discountPriceArrayFind($eData, $currency_id, 'pSale_price_price', $page);

                    if ($this->discountPriceArrayFind($eData, $currency_id, 'pSale_price_intShipping', $page) == '1') {

                        $eShip = 'checked';

                    }



                } else {

                    $ePrice = $this->discountPriceArrayFind($eData, $currency_id, 'product_dis_price', $page);

                    if ($this->discountPriceArrayFind($eData, $currency_id, 'product_dis_intShipping', $page) == '1') {

                        $eShip = 'checked';

                    }

                }

            }





            echo "

            <tr>

                <td style='white-space: nowrap;'>

                    $country_name ($currency):

                </td>



                <td>

                    <div class='input-group input-group-sm'>

                        <span class='input-group-addon'>$symbol</span>

                        <input type='text' pattern='\d+(\.\d{2})?' value='$ePrice' name='$this->prefix_currencyArray[$currency_id]' class='form-control' placeholder='00.00'>

                    </div>

                </td>



                <td style='text-align: center;'>

                    <div class='make-switch' data-on='success' data-off='danger' >

                        <input type='checkbox' value='1' $eShip  name='intShipping_$currency_id' >

                    </div>

                </td>

            </tr>

            ";

        }

        echo "</table></div> ";

    }

    /************************************/

    public function createListOfcategory()
    {

        $this->catTree_starter();

    }



    private function catTree_starter($under_id = 0)
    {



    }





    /************************************/

    public function customSizeArrayFilter($data, $country_id)
    {

        foreach ($data as $key => $val) {

            if ($val['currencyId'] == $country_id) {

                return $val['price'];

            }

        }

        return "";

    }







    public function createListOfCustomSize()
    {

        //old data.

        $isEdit = false;

        if ($this->editPid != "") {

            $isEdit = true;

            $pId = $this->editPid;

        }



        $dataOld = array();

        if ($isEdit) {

            $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";

            $dataOld = $this->dbF->getRows($sql);

        }



        $format = '<div class="form-group">

                        <label class="col-sm-2 control-label">{{label}}</label>

                        <div class="col-sm-10">

                            {{form}}

                        </div>

                    </div>';



        global $_e;

        $divs = '';

        $form_fields = array();

        if ($this->functions->developer_setting('product_customSize') == '1') {

            $countryCodeList = $this->functions->countrylist(); // country list

            $currency_data = $this->c_currency->getList(); // get currency list



            $customTypeArray = array();

            //get list of options in custom size table

            $sql = "SELECT * FROM `p_custom` WHERE publish = '1'";

            $data = $this->dbF->getRows($sql);



            $customTypeArray = array();

            if ($this->dbF->rowCount > 0) {

                $customTypeArray['value'] = "0";

                $customTypeArray['option'] = "----------";

                $customTypeArray = $this->functions->getSelectValueAndOptions($data, 'id', 'custom_type', false, $customTypeArray);

                @$formVal = $dataOld[0]['type_id'];

                if (empty($formVal))
                    $formVal = 0;

                $form_fields[] = array(

                    'label' => _uc($_e['Custom Size Type']),

                    'name' => 'custom[type_id]',

                    'select' => "$formVal",

                    'value' => $customTypeArray['value'],

                    'option' => $customTypeArray['option'],

                    'type' => 'select',

                    'class' => 'form-control',

                );

                $this->functions->print_form($form_fields, $format);

                $form_fields = array();

                $tds = "<td></td>";

                $tds2 = "<td></td>";

                foreach ($currency_data as $data) {

                    $country_id = $data['cur_id'];

                    $symbol = $data['cur_symbol'];

                    $country_name = $countryCodeList[$data['cur_country']];

                    $currency = $data["cur_name"];

                    $tds .= "<td>$country_name ($currency)</td>";

                    $curOldPrice = $this->customSizeArrayFilter($dataOld, $country_id);

                    $tds2 .= '<td>

                                    <div class="input-group input-group-sm">

                                      <span class="input-group-addon">' . $symbol . '</span>

                                      <input type="text" class="form-control" value="' . $curOldPrice . '" name="custom[currencyId][' . $country_id . ']" >

                                    </div>

                                </td> ';

                }



                $form_fields[] = array(

                    'format' => "<tr>$tds</tr>"

                );

                $form_fields[] = array(

                    'format' => "<tr>$tds2</tr>"

                );

                $form_fields['main'] = array(

                    'type' => 'main',

                    'format' => "<table class='table table-striped table-hover'>{{form}}</table>"

                );

                $format = "{{form}}";



                $this->functions->print_form($form_fields, $format);


            }


        }

    }



    /**

     *

    */

    public function createListOfScales()
    {
        $data = $this->c_scale->getDataSQL();
        $this->scaleListFormate($data);
    }



    /**

     * @param $pId

     * @return bool|MultiArray

     */

    public function scalesWeightEcho($pId)
    {

        $sql = "SELECT * FROM product_size_weight WHERE pwPId = '$pId' ORDER BY id";

        $data = $this->dbF->getRows($sql);

        if ($this->dbF->rowCount > 0)

            return $data;

        return false;

    }

    /**

     *

     */

    public function createListOfScalesWeight()
    {

        global $_e;

        $temp1 = _uc($_e['Size Name']);

        $temp2 = ($_e['Weight In KG']);

        echo '<table class="col-sm-24 col-md-10 table" id="sizeWeightDiv">';

        //Edit Section

        $isEdit = false;

        if ($this->editPid != "") {

            $pId = $this->editPid;

            $isEdit = true;

            $eData = $this->scalesWeightEcho($pId);

            if ($eData == false) {
                $eData = array();
            }





            foreach ($eData as $data) {

                echo <<<HTML

        <tr>

            <td>

                <div class="input-group input-group-sm">

                     <span class="input-group-addon">$temp1</span>

                     <input type="text" class="form-control" value="$data[pw_size]" name="sizeWeightName[$data[id]]" placeholder="$temp1">

                </div>

           </td>

           <td>

                <div class="input-group input-group-sm">

                     <span class="input-group-addon">$temp2</span>

                     <input type="text" class="form-control" value="$data[pw_weight]" name="sizeWeight[$data[id]]" placeholder="$temp2,e.g: 200,2,0.2">

                </div>

           </td>

        </tr>

HTML;

            } //foreach end

        } /// edit if end

        //Edit Section End





        $temp3 = _uc($_e['Add More Weight']);

        echo <<<HTML

        <tr>

        <td>

            <div class="input-group input-group-sm">

                 <span class="input-group-addon">$temp1</span>

                 <input type="text" class="form-control" value="" name="sizeWeightName[0]" placeholder="$temp1">

            </div>

       </td>

       <td>

            <div class="input-group input-group-sm">

                 <span class="input-group-addon">$temp2</span>

                 <input type="text" class="form-control" value="" name="sizeWeight[0]" placeholder="$temp2,e.g: 200,2,0.2">

            </div>

       </td>

        </tr>

        </table>



        <div class="clearfix"></div>



<button type="button" class="btn btn-info " onclick="addWeightSlot()">$temp3</button>



HTML;



    }



    /**

     * @param string $var

     */

    public function currencyListJson($var = "cdata")
    {



        $countryCodeList = $this->functions->countrylist();

        $currency_data = $this->c_currency->getList();



        $cur_Data = array();

        foreach ($currency_data as $data) {

            $country_id = $data['cur_id'];

            $country_name = $countryCodeList[$data['cur_country']];

            $currency = $data["cur_name"];

            $symbol = $data['cur_symbol'];



            $cur_Data[] = array(

                "id" => $country_id,

                "country" => $country_name,

                "name" => $currency,

                "symbol" => $symbol

            );

        }

        $cur_json = json_encode($cur_Data);

        echo <<<HTML

<script type="text/javascript">

var $var = $cur_json;

</script>

HTML;



    }





    /**

     * @param $pidKey

     * @param $group

     * @param $array

     * @param $returnKey

     * @return string

     *

     */

    private function searchInArrayGroup($pidKey, $group, $array, $returnKey)
    {

        foreach ($array as $key2 => $val) {

            if ($val['sizeGroup'] == $group && $val[$pidKey] == $this->editPid) {

                return $val[$returnKey];

            }

        }

        return "";

    }



    /**

     * @param $pidKey

     * @param $group

     * @param $search

     * @param $key

     * @param $array

     * @param $returnKey

     * @return string

     */

    private function searchInArray($pidKey, $group, $search, $key, $array, $returnKey)
    {

        foreach ($array as $key2 => $val) {

            if ($val[$key] == $search && $val['sizeGroup'] == $group && $val[$pidKey] == $this->editPid) {

                return $val[$returnKey];

            }

        }

        return "";

    }



    /**

     * @param $pidKey

     * @param $search

     * @param $key

     * @param $array

     * @param $returnKey

     * @return string

     */

    private function searchInArrayLeftItem($pidKey, $search, $key, $array, $returnKey)
    {

        foreach ($array as $key2 => $val) {

            if ($val[$key] == $search && $val[$pidKey] == $this->editPid) {

                return $val[$returnKey];

            }

        }

        return "";

    }



    /**

     * @param $pidKey

     * @param $group

     * @param $match1

     * @param $match2

     * @param $key

     * @param $key2

     * @param $array

     * @param $returnKey

     * @return string

     */

    private function searchInArray2($pidKey, $group, $match1, $match2, $key, $key2, $array, $returnKey)
    {

        foreach ($array as $keya => $val) {

            if ($val[$key] == $match1 && $val[$key2] == $match2 && $val['sizeGroup'] == $group && $val[$pidKey] == $this->editPid) {

                return $val[$returnKey];

            }

        }

        return "";

    }



    /**

     * @param $pidKey

     * @param $match1

     * @param $match2

     * @param $key

     * @param $key2

     * @param $array

     * @param $returnKey

     * @return string

     */

    private function searchInArray2LeftItems($pidKey, $match1, $match2, $key, $key2, $array, $returnKey)
    {

        foreach ($array as $keya => $val) {

            if ($val[$key] == $match1 && $val[$key2] == $match2 && $val[$pidKey] == $this->editPid) {

                return $val[$returnKey];

            }

        }

        return "";

    }



    private function scaleListFormateEdit()
    {



        if ($this->prefix_editPro != "") {

            // If product is edit mode

            $eId = $this->editPid;

            $qry = "SELECT * FROM product_size WHERE prosiz_prodet_id = '$eId' ORDER BY sort ASC";

            $eData = $this->dbF->getRows($qry);

            return $eData;

        } else {

            return false;

        }

    }





    private function scaleListFormateOld($data)
    {



        global $_e;

        if (is_array($data)) {

            $isEdit = false;

            //Edit Section

            $eData = $this->scaleListFormateEdit();

            if ($eData)
                $isEdit = true;

            //Edit Section End





            $countryCodeList = $this->functions->countrylist(); // country list

            $currency_data = $this->c_currency->getList(); // get currency list



            $options = "<option>" . _uc($_e['Select Scale Group']) . "</option>";

            $divs = "";

            foreach ($data as $val) {



                $name = $val["name"];

                $scale = $val["scale"];



                //Edit Section

                $sel = "";

                if ($isEdit) {

                    if ($this->searchInArrayGroup('prosiz_prodet_id', $name['scaleName_id'], $eData, 'sizeGroup')) {

                        $sel = "selected=selected";

                    }

                }//Edit Section End



                $options .= "<option $sel value='$name[scaleName_id]'>$name[scaleName_name]</option>";



                $divs .= "<div id='divScaleVal$name[scaleName_id]' class='classScaleVal table-responsive'>

                    <table class='table table-striped table-hover'>

                    <tr>

                        <td></td> ";



                $cur_id_array = array();

                $cur_name_array = array();

                foreach ($currency_data as $data) {

                    //print top scale county name.. first TR

                    $country_id = $data['cur_id'];

                    $country_name = $countryCodeList[$data['cur_country']];

                    $currency = $data["cur_name"];

                    $symbol = $data['cur_symbol'];



                    $cur_id_array[] = $country_id;

                    $cur_name_array[] = "$country_name ($currency)";



                    $divs .= "<td>$country_name ($currency)</td>";

                }



                $divs .= "</tr>";



                foreach ($scale as $sc) {

                    // print scale name with price

                    $rowId = "sc_" . $name['scaleName_id'] . "_" . $sc['scale_id']; // sc_parentID_selfId



                    // scale first td checkbox



                    //Edit Section

                    $checked = "";

                    if ($isEdit) {

                        if ($this->searchInArray('prosiz_prodet_id', $name['scaleName_id'], $sc['scale_name'], 'prosiz_name', $eData, 'prosiz_timeStamp')) {

                            $checked = "checked";

                            echo "<style>

                                    #divScaleVal" . $name['scaleName_id'] . "{

                                    display:block;}

                                   </style>";

                        }

                    }//Edit Section End



                    $divs .= <<<HTML

                    <tr>

                        <td>

                        <label>

                            <input type="checkbox" $checked value="$rowId" data-name="$this->prefix_scaleCheckBox[]">

                            <input type="hidden" value="$sc[scale_name]" data-name="$rowId-$this->prefix_scaleName">

                                $sc[scale_name]

                        </label>

                        </td>

HTML;





                    $cur_Data = array();

                    foreach ($currency_data as $data) {

                        // print input field

                        $country_id = $data['cur_id'];

                        $country_name = $countryCodeList[$data['cur_country']];

                        $currency = $data["cur_name"];

                        $symbol = $data['cur_symbol'];



                        $cur_Data[] = array(

                            "id" => $country_id,

                            "country" => $country_name,

                            "name" => $currency,

                            "symbol" => $symbol

                        );



                        //Edit Section

                        $ePrice = "";

                        if ($isEdit) {

                            $ePrice = $this->searchInArray2('prosiz_prodet_id', $name['scaleName_id'], $sc['scale_name'], $country_id, 'prosiz_name', 'prosiz_cur_id', $eData, 'prosiz_price');

                        }//Edit Section End



                        $divs .= <<<HTML

                        <td>

                            <div class="input-group input-group-sm">

                              <span class="input-group-addon">$symbol</span>

                              <input type="text" class="form-control" value="$ePrice" data-name="$rowId-$this->prefix_scaleCost[$country_id]" >

                            </div>

                        </td>

HTML;

                    }



                    $divs .= "</tr>";

                }

                $divs .= "</table>

                </div>";



            }



            echo <<<HTML

          <style>

              .classScaleVal{

                display: none;

              }

          </style>

<script type="text/javascript">







        function seleScale(id){

            var iid = id;

            var divId = "#divScaleVal"+iid;

            $(".classScaleVal input[data-name]").attr("name",null);

            $(".classScaleVal").hide(0,function (){

                $(divId).show(0,function (){

                    $(" input[data-name]",this).each(function  (){

                        var name = $(this).attr("data-name");

                        $(this).attr("name",name);

                    });

                });

            });

        };

</script>



HTML;

            echo "<select id='seleScale' class='selectpicker form-control' name='sizeGroup'>$options</select><br /><br />

                   <script> $(function(){

                                $( '#seleScale' ).selectmenu({

                                    change: function( event, data ) {

                                        seleScale(data.item.value);

                                    }

                                });

                   })</script>";

            echo $divs;



        }

    }



    /**

     * @param $data

     */

    private function scaleListFormate($data)
    {

        global $_e;



        if (is_array($data)) {

            $isEdit = false;

            //Edit Section

            $eData = $this->scaleListFormateEdit();

            if ($eData != false)
                $isEdit = true;





            $uniquename = array();

            $namePrint = array();



            //get all unique scale name in array,

            foreach ($eData as $key => $val) {

                if (!in_array($val['prosiz_name'], $uniquename)) {

                    $uniquename[] = $val['prosiz_name'];

                }

            }




            //Edit Section End



            $countryCodeList = $this->functions->countrylist(); // country list

            $currency_data = $this->c_currency->getList(); // get currency list



            $options = "<option>" . _uc($_e['Select Scale Group']) . "</option>";

            $divs = "";

            foreach ($data as $val) {



                $name = $val["name"];

                $scale = $val["scale"];

                //Edit Section

                $sel = "";

                if ($isEdit) {

                    // which dropdown scale was selected , define here. but currenlty from css it is display none. css is in this function

                    if ($this->searchInArrayGroup('prosiz_prodet_id', $name['scaleName_id'], $eData, 'sizeGroup')) {

                        $sel = "selected=selected";

                        $divs .= "<script>$(document).ready(function(){

                                    seleScale($name[scaleName_id])

                                    });

                                 </script>";

                    }

                }//Edit Section End



                $options .= "<option $sel value='$name[scaleName_id]'>$name[scaleName_name]</option>";

                $divs .= "<div id='divScaleVal$name[scaleName_id]' class='classScaleVal table-responsive'>

                    <table class='table table-striped table-hover'>

                    <thead>

                    <tr>

                        <th></th> ";



                $cur_id_array = array();

                $cur_name_array = array();

                foreach ($currency_data as $data) {

                    //print top scale county name.. first TR

                    $country_id = $data['cur_id'];

                    $country_name = $countryCodeList[$data['cur_country']];

                    $currency = $data["cur_name"];

                    $symbol = $data['cur_symbol'];



                    $cur_id_array[] = $country_id;

                    $cur_name_array[] = "$country_name ($currency)";



                    $divs .= "<th>$country_name ($currency)</th>";

                }



                $divs .= "</tr></thead><tbody>";

                $scaleId = '';

                foreach ($scale as $sc) {

                    //Edit Section

                    $checked = "";

                    $saveScaleId = 0;



                    if ($isEdit) {

                        // get previous scale id, so it will be use in updating



                        $saveScaleId = $this->searchInArray('prosiz_prodet_id', $name['scaleName_id'], $sc['scale_name'], 'prosiz_name', $eData, 'prosiz_id');

                        if ($saveScaleId == "") {
                            $saveScaleId = 0;
                        }



                        //if this array was selected so it is selected

                        if ($this->searchInArray('prosiz_prodet_id', $name['scaleName_id'], $sc['scale_name'], 'prosiz_name', $eData, 'prosiz_timeStamp')) {

                            $checked = "checked";

                            echo "<style>

                                    #divScaleVal" . $name['scaleName_id'] . "{

                                    display:block;}

                                   </style>";

                        } else {

                            continue; //if scale was not inserted

                            // if you remove continue, all scale group item show, either you were not select

                        }

                    }//Edit Section End



                    //If define scale find so show define group,

                    if (in_array($sc['scale_name'], $uniquename)) {

                        $namePrint[] = $sc['scale_name'];

                        $scaleId = $name['scaleName_id'];

                    }


                    // scale first td checkbox



                    @$pro_iddd = $eData[0]['prosiz_prodet_id'];



                    // print scale name with price

                    $rowId = "sc_" . $name['scaleName_id'] . "_" . $sc['scale_id'] . "_" . $saveScaleId; // sc_parentID_groupId_scaleSavedId

                    $divs .= <<<HTML

                    <tr class="ui-state-default" id="size_$sc[scale_name]:$pro_iddd">

                        <td>

                        <label>

                            <input type="checkbox" $checked value="$rowId" data-name="$this->prefix_scaleCheckBox[]">

                            <input type="hidden" value="$sc[scale_name]" data-name="$rowId-$this->prefix_scaleName">

                                $sc[scale_name]

                        </label>

                        </td>

HTML;





                    $cur_Data = array();



                    foreach ($currency_data as $data2) {

                        // print input field



                        $country_id = $data2['cur_id'];

                        $country_name = $countryCodeList[$data2['cur_country']];

                        $currency = $data2["cur_name"];

                        $symbol = $data2['cur_symbol'];



                        $cur_Data[] = array(

                            "id" => $country_id,

                            "country" => $country_name,

                            "name" => $currency,

                            "symbol" => $symbol

                        );



                        //Edit Section

                        $ePrice = "";

                        if ($isEdit) {

                            $ePrice = $this->searchInArray2('prosiz_prodet_id', $name['scaleName_id'], $sc['scale_name'], $country_id, 'prosiz_name', 'prosiz_cur_id', $eData, 'prosiz_price');

                        }//Edit Section End



                        $divs .= <<<HTML

                        <td>

                            <div class="input-group input-group-sm">

                              <span class="input-group-addon">$symbol</span>

                              <input type="text" class="form-control" value="$ePrice" data-name="$rowId-$this->prefix_scaleCost[$country_id]" >

                            </div>

                        </td>

HTML;

                    }



                    $divs .= "</tr>";

                }



                if ($scaleId != "" && $isEdit) {

                    for ($i = 0; $i < sizeof($uniquename); $i++) {



                        if (!in_array($uniquename[$i], $namePrint)) {

                            $saveScaleId = 0;

                            if ($isEdit) {



                                $saveScaleId = $this->searchInArray('prosiz_prodet_id', $name['scaleName_id'], $uniquename[$i], 'prosiz_name', $eData, 'prosiz_id');

                                if ($saveScaleId == "")
                                    $saveScaleId = 0;

                            }//Edit Section End



                            $rowId = "sc_" . $scaleId . "_Sav" . $i . "_" . $saveScaleId; // sc_parentID_selfId

                            $divs .= <<<HTML

                        <tr>

                            <td>

                            <label>

                                <input type="checkbox" checked value="$rowId" data-name="$this->prefix_scaleCheckBox[]">

                                <input type="hidden" value="$uniquename[$i]" data-name="$rowId-$this->prefix_scaleName">

                                    $uniquename[$i]

                            </label>

                            </td>

HTML;



                            foreach ($currency_data as $data) {

                                // print input field

                                $country_id = $data['cur_id'];

                                $symbol = $data['cur_symbol'];



                                //Edit Section

                                $ePrice = "";

                                if ($isEdit) {

                                    $ePrice = $this->searchInArray2('prosiz_prodet_id', $scaleId, $uniquename[$i], $country_id, 'prosiz_name', 'prosiz_cur_id', $eData, 'prosiz_price');

                                }//Edit Section End



                                $divs .= <<<HTML

                            <td>

                                <div class="input-group input-group-sm">

                                  <span class="input-group-addon">$symbol</span>

                                  <input type="text" class="form-control" value="$ePrice" data-name="$rowId-$this->prefix_scaleCost[$country_id]" >

                                </div>

                            </td>

HTML;

                            }



                            $divs .= "</tr>";

                        }

                    }

                }



                $divs .= "</table></tbody>

                 </div>";



            }











            //if no scale print, so left all scale print then upper for loop useless :(

            if (sizeof($uniquename) > 0 && !isset($namePrint[0]) && $isEdit) {



                $divs = "";

                $divs .= "<div id='divScaleVal' class='table-responsive'>

                    <table class='table table-striped table-hover'>

                    <tr>

                        <td></td> ";



                foreach ($currency_data as $data) {

                    //print top scale county name.. first TR

                    $country_name = $countryCodeList[$data['cur_country']];

                    $currency = $data["cur_name"];

                    $divs .= "<td>$country_name ($currency)</td>";

                }



                $divs .= "</tr>";

                for ($i = 0; $i < sizeof($uniquename); $i++) {



                    if (!in_array($uniquename[$i], $namePrint)) {

                        $saveScaleId = 0;

                        if ($isEdit) {

                            $saveScaleId = $this->searchInArrayLeftItem('prosiz_prodet_id', $uniquename[$i], 'prosiz_name', $eData, 'prosiz_id');

                            if ($saveScaleId == "")
                                $saveScaleId = 0;

                        }//Edit Section End

                        $scaleId = 0;

                        $rowId = "sc_" . $scaleId . "_Sav" . $i . "_" . $saveScaleId; // sc_parentID_selfId

                        @$pro_iddd = $eData[0]['prosiz_prodet_id'];
                        @$pProsiz_name = $uniquename[$i];

                        // <tr class="ui-state-default" id="size_$pProsiz_name:$pro_iddd">
                        $divs .= <<<HTML

                        <tr class="ui-state-default" id="size_$saveScaleId:$pro_iddd">
                            <td>

                            <label>

                                <input type="checkbox" checked value="$rowId" name="$this->prefix_scaleCheckBox[]">

                                <input type="hidden" value="$uniquename[$i]" name="$rowId-$this->prefix_scaleName">

                                    $uniquename[$i]

                            </label>

                            </td>

HTML;



                        foreach ($currency_data as $data) {

                            // print input field

                            $country_id = $data['cur_id'];

                            $symbol = $data['cur_symbol'];



                            //Edit Section

                            $ePrice = "";

                            if ($isEdit) {

                                $ePrice = $this->searchInArray2LeftItems('prosiz_prodet_id', $uniquename[$i], $country_id, 'prosiz_name', 'prosiz_cur_id', $eData, 'prosiz_price');

                            }//Edit Section End



                            $divs .= <<<HTML

                            <td>

                                <div class="input-group input-group-sm">

                                  <span class="input-group-addon">$symbol</span>

                                  <input type="text" class="form-control" value="$ePrice" name="$rowId-$this->prefix_scaleCost[$country_id]" >

                                </div>

                            </td>

HTML;

                        }



                        $divs .= "</tr>";



                    }

                }

                $divs .= "</table>

                </div>";

            }









            echo <<<HTML

          <style>

              .classScaleVal{

                display: none;

              }

          </style>

<script type="text/javascript">

        function seleScale(id){

            var iid = id;

            var divId = "#divScaleVal"+iid;

            $(".classScaleVal input[data-name]").attr("name",null);

            $(".classScaleVal").hide(0,function (){

                $(divId).show(0,function (){

                    $(" input[data-name]",this).each(function  (){

                        var name = $(this).attr("data-name");

                        $(this).attr("name",name);

                    });

                });

            });

        };

</script>



HTML;

            if ($isEdit)

                $view = "style='display:none'";
            else

                $view = "id='seleScale'";

            echo "<select  $view class='selectpicker form-control' name='sizeGroup_scale'>$options</select><br /><br />

                   <script> $(function(){

                                $( '#seleScale' ).selectmenu({

                                    change: function( event, data ) {

                                        seleScale(data.item.value);

                                    }

                                });

                   })</script>";

            echo $divs;



        }

    }



    /************************************/





    /************************************/





    private function colorListFormateEdit()
    {



        if ($this->prefix_editPro != "") {

            // If product is edit mode

            $eId = $this->editPid;

            $qry = "SELECT * FROM  `product_color` WHERE `proclr_prodet_id` = '$eId'";

            $eData = $this->dbF->getRows($qry);

            return $eData;

        } else {

            return false;

        }

    }





    public function createListOfColor()
    {

        $data = $this->c_color->getDataSQL();

        $this->colorListFormate($data);

    }



    /**

     * @param $data

     */

    private function colorListFormate($data)
    {

        global $_e;

        if (is_array($data)) {



            $isEdit = false;

            //Edit Section

            $eData = $this->colorListFormateEdit();

            if ($eData)
                $isEdit = true;

            $uniquename = array();

            $namePrint = array();

            foreach ($eData as $key => $val) {

                if (!in_array($val['proclr_name'], $uniquename)) {

                    $uniquename[] = $val['proclr_name'];

                }

            }

            //Edit Section End









            $countryCodeList = $this->functions->countrylist(); // get country

            $currency_data = $this->c_currency->getList(); //get currency list



            $options = "<option>" . _uc($_e['Select Color Group']) . "</option>";

            $divs = "";

            foreach ($data as $val) {

                $name = $val["name"];

                $color = $val["color"];



                //Edit Section

                $sel = "";

                if ($isEdit) {

                    if ($this->searchInArrayGroup('proclr_prodet_id', $name['colorName_id'], $eData, 'sizeGroup')) {

                        $sel = "selected=selected";

                        $divs .= "<script>$(document).ready(function(){

                                    selecolor($name[colorName_id])

                                    });

                                 </script>";

                    }

                }//Edit Section End



                $options .= "<option $sel value='$name[colorName_id]'>$name[colorName_name]</option>";



                $divs .= "<div id='divcolorVal$name[colorName_id]' class='classcolorVal table-responsive'>

                    <table class='table table-striped table-hover'>

                    <tr>

                        <td></td>

                ";



                foreach ($currency_data as $data) {

                    // print first tr country currency names

                    $country_name = $countryCodeList[$data['cur_country']];

                    $currency = $data["cur_name"];

                    $symbol = $data['cur_symbol'];



                    $divs .= "<td>$country_name ($currency)</td>";

                }



                $divs .= "</tr>";

                // tr country currency names End



                $colorId = '';



                foreach ($color as $sc) {



                    //Edit Section

                    //Edit section after in_array if, show error, because if color name and define color group are same then

                    //it will go in if condition,, previously it was $isedit are place after in_array if

                    $checked = "";

                    $saveScaleId = 0;

                    if ($isEdit) {

                        $saveScaleId = $this->searchInArray('proclr_prodet_id', $name['colorName_id'], $sc['color_name'], 'proclr_name', $eData, 'propri_id');

                        if ($saveScaleId == "") {
                            $saveScaleId = 0;
                        }

                        if ($this->searchInArray('proclr_prodet_id', $name['colorName_id'], $sc['color_name'], 'proclr_name', $eData, 'proclr_timeStamp')) {

                            $checked = "checked";

                            echo "<style>

                                    #divcolorVal" . $name['colorName_id'] . "{

                                    display:block;}

                                   </style>";

                            //disable color select option,, may be work will go wrong in edit page

                        } else {

                            continue; // if color was not selected

                        }

                    }//Edit Section End



                    if (in_array($sc['color_name'], $uniquename)) {

                        $namePrint[] = $sc['color_name'];

                        $colorId = $name['colorName_id'];

                    }



                    $rowId = "clr_" . $name['colorName_id'] . '_' . $sc['color_id'] . '_' . $saveScaleId; // clr_parentID_selfId

                    // print first td checkbox

                    $divs .= <<<HTML

                    <tr>

                        <td>

                            <label class="color_label">

                                <input type="checkbox" $checked value="$rowId" data-name="$this->prefix_colorCheckBox[]">

                                <input type="hidden" data-name="$rowId-$this->prefix_colorName" value="$sc[color_name]">

                           $sc[color_name] </label>

                        </td>

HTML;



                    foreach ($currency_data as $data) {

                        //print inout field with currency name

                        $country_id = $data['cur_id'];

                        $country_name = $countryCodeList[$data['cur_country']];

                        $currency = $data["cur_name"];

                        $symbol = $data['cur_symbol'];



                        //Edit Section

                        $ePrice = "";



                        if ($isEdit) {

                            $ePrice = $this->searchInArray2('proclr_prodet_id', $name['colorName_id'], $sc['color_name'], $country_id, 'proclr_name', 'proclr_cur_id', $eData, 'proclr_price');

                        }//Edit Section End



                        $divs .= <<<HTML

                            <td>

                                <div class="input-group input-group-sm">

                                  <span class="input-group-addon">$symbol</span>

                                  <input type="text" value="$ePrice" class="form-control" data-name="$rowId-$this->prefix_colorCost[$country_id]" >

                                </div>



                            </td>

HTML;

                    }

                    $divs .= "</tr>";

                }





                if ($colorId != "") {

                    for ($i = 0; $i < sizeof($uniquename); $i++) {

                        if (!in_array($uniquename[$i], $namePrint)) {

                            $saveScaleId = 0;

                            if ($isEdit) {

                                $saveScaleId = $this->searchInArray('proclr_prodet_id', $name['colorName_id'], $uniquename[$i], 'proclr_name', $eData, 'propri_id');

                                if ($saveScaleId == "")
                                    $saveScaleId = 0;

                            }//Edit Section End

                            $rowId = "clr_" . $colorId . "_Sav" . $i . '_' . $saveScaleId; // sc_parentID_selfId

                            $divs .= <<<HTML

                        <tr>

                            <td>

                            <label>

                                <input type="checkbox" checked value="$rowId" data-name="$this->prefix_colorCheckBox[]">

                                <input type="hidden" data-name="$rowId-$this->prefix_colorName" value="$uniquename[$i]">

                            $uniquename[$i]

                            </label>

                            </td>

HTML;



                            foreach ($currency_data as $data) {

                                // print input field

                                $country_id = $data['cur_id'];

                                $symbol = $data['cur_symbol'];



                                //Edit Section

                                $ePrice = "";

                                if ($isEdit) {

                                    $ePrice = $this->searchInArray2('proclr_prodet_id', $colorId, $uniquename[$i], $country_id, 'proclr_name', 'proclr_cur_id', $eData, 'proclr_price');

                                }//Edit Section End



                                $divs .= <<<HTML

                            <td>

                                <div class="input-group input-group-sm">

                                  <span class="input-group-addon">$symbol</span>

                                  <input type="text" class="form-control" value="$ePrice" data-name="$rowId-$this->prefix_colorCost[$country_id]" >

                                </div>

                            </td>

HTML;

                            }



                            $divs .= "</tr>";

                        }

                    }

                }





                $divs .= "</table>

                </div>";

            }









            if (sizeof($uniquename) > 0 && !isset($namePrint[0]) && $isEdit) {

                $divs = "";

                $divs .= "<div id='divScaleVal' class='table-responsive'>

                    <table class='table table-striped table-hover'>

                    <tr>

                        <td></td> ";



                foreach ($currency_data as $data) {

                    //print top scale county name.. first TR

                    $country_name = $countryCodeList[$data['cur_country']];

                    $currency = $data["cur_name"];

                    $divs .= "<td>$country_name ($currency)</td>";

                }



                $divs .= "</tr>";

                for ($i = 0; $i < sizeof($uniquename); $i++) {

                    if (!in_array($uniquename[$i], $namePrint)) {

                        $saveScaleId = 0;

                        if ($isEdit) {

                            $saveScaleId = $this->searchInArrayLeftItem('proclr_prodet_id', $uniquename[$i], 'proclr_name', $eData, 'propri_id');

                            if ($saveScaleId == "")
                                $saveScaleId = 0;

                        }//Edit Section End

                        $colorId = '0';

                        $rowId = "clr_" . $colorId . "_Sav" . $i . '_' . $saveScaleId; // sc_parentID_selfId

                        $divs .= <<<HTML

                        <tr>

                            <td>

                            <label>

                                <input type="checkbox" checked value="$rowId" name="$this->prefix_colorCheckBox[]">

                                <input type="hidden" name="$rowId-$this->prefix_colorName" value="$uniquename[$i]">

$uniquename[$i]

                            </label>

                            </td>

HTML;



                        foreach ($currency_data as $data) {

                            // print input field

                            $country_id = $data['cur_id'];

                            $symbol = $data['cur_symbol'];



                            //Edit Section

                            $ePrice = "";

                            if ($isEdit) {

                                $ePrice = $this->searchInArray2LeftItems('proclr_prodet_id', $uniquename[$i], $country_id, 'proclr_name', 'proclr_cur_id', $eData, 'proclr_price');

                            }//Edit Section End



                            $divs .= <<<HTML

                            <td>

                                <div class="input-group input-group-sm">

                                  <span class="input-group-addon">$symbol</span>

                                  <input type="text" class="form-control" value="$ePrice" name="$rowId-$this->prefix_colorCost[$country_id]" >

                                </div>

                            </td>

HTML;

                        }



                        $divs .= "</tr>";

                    }

                }

                $divs .= "</table>

                </div>";

            }





            echo <<<HTML

          <style>

              .classcolorVal{

                display: none;

              }

              .colorBox{

                display: inline-block;

                width: 12px;

                height: 12px;

                margin: 0 6px;

              }

          </style>



<script type="text/javascript">

    function selecolor(id){

            var iid = id;

            var divId = "#divcolorVal"+iid;

            $(".classcolorVal input[type='text']").attr("name",null);

            $(".classcolorVal").hide(0,function (){

                $(divId).show(0,function (){

                    $(" input[data-name]",this).each(function  (){

                        var name = $(this).attr("data-name");

                        $(this).attr("name",name);

                    });

                });

            });



        };

</script>



HTML;

            if ($isEdit)

                $view = "style='display:none'";
            else

                $view = "id='selecolor'";

            echo "<select $view class='selectpicker' name='sizeGroup_color'>$options</select> <br /><br />

                    <script> $(function(){

                                $( '#selecolor' ).selectmenu({

                                    change: function( event, data ) {

                                        selecolor(data.item.value);

                                    }

                                });

                   })</script>";

            echo $divs;



        }

    }



    /************************************/





    public function productView()
    {
        $qry = "SELECT `proudct_detail`.*, `product_setting`.`setting_val` FROM 
        `proudct_detail` join `product_setting` ON `proudct_detail`.`prodet_id` 
        = `product_setting`.`p_id` WHERE `product_setting`.`setting_name` = 
        'publicAccess' AND `product_setting`.`setting_val`='1' AND 
        `proudct_detail`.`product_update`='1' ORDER BY 
        `proudct_detail`.`prodet_id` DESC";

        echo $this->product_list_View($qry);
    }



    public function productDraft()
    {

        $qry = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`

                FROM

                   `proudct_detail` join `product_setting`

                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`

                    WHERE `product_setting`.`setting_name`='publicAccess' AND `product_setting`.`setting_val`='0' AND `proudct_detail`.`product_update`='1'

                    ORDER BY `proudct_detail`.`prodet_id` DESC";

        echo $this->product_list_View($qry, 'draft');



    }



    public function productPending()
    {

        $date = date('m/d/Y');

        $qry = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`

                FROM

                   `proudct_detail` join `product_setting`

                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`

                    WHERE

                    `product_setting`.`setting_name`='launchDate'

                    AND `product_setting`.`setting_val`>'$date'

                    AND `proudct_detail`.`product_update`='1'

                    ORDER BY `proudct_detail`.`prodet_id` DESC";

        echo $this->product_list_View($qry, 'pending');

    }



    /**

     * @param $qry

     */

    private function product_list_View($qry, $page = '')
    {

        global $_e;

        $data = $this->dbF->getRows($qry);



        # href is used by ajax request

        $href = "product/products_listing_ajax.php?page=active_products";

        $style = 'dTableWidth';



        if ($page == 'draft') {

            $href = "product/products_listing_ajax.php?page=draft_products";



            $qry = "SELECT `proudct_detail`.* FROM `proudct_detail` WHERE `proudct_detail`.`prodet_id` NOT IN (SELECT distinct(p_id) FROM product_setting) AND `proudct_detail`.`product_update`='1'

                    ORDER BY `proudct_detail`.`prodet_id` DESC";

            $data2 = $this->dbF->getRows($qry);

            if ($this->dbF->rowCount > 0) {

                $data = array_merge($data, $data2);

            }

            $style = '';

        } elseif ($page == 'pending') {

            $href = "product/products_listing_ajax.php?page=pending_products";

            $style = '';

        }





        $defaultLang = $this->functions->AdminDefaultLanguage();

        if (!empty($data) && $data != false) {







            $uniq = uniqid('id');

            echo '

            <div class="table-responsive ' . $style . '" id="script-width">

            <table class="uniqueClass table table-hover tableIBMS dTable_ajax1 " data-href="' . $href . '" data-uniq="' . $uniq . '" data-sorting="true">

                <thead>

                    <tr>

                        <th>' . _u($_e['SELECT']) . '</th>

                        <th><div class="checkbox delCheckboxOncolvis">

                                    <label>

                                      <input style="display:none !important" type="checkbox" class="checkBoxSelectAll" id="' . $uniq . '" ng-model="' . $uniq . '">' . _u($_e['SNO']) . '

                                    </label>

                                  </div>

                            </th>

                        <th>' . _u($_e['PRODUCT THUMBNAIL']) . '</th>

                        <th>' . _u($_e['PRODUCT NAME']) . '</th>

                        <th>' . _u($_e['SHORT DESC']) . '</th>

                        <th>' . _u($_e['CREATE DATE']) . '</th>

                        <th>' . _u($_e['VIEWS']) . '</th>

                        <th>' . _u($_e['SALES']) . '</th>

                        <th>' . _u($_e['ACTION']) . '</th>

                    </tr>

                </thead>

                <tbody>';


            echo <<<HTML

                </tbody>

            </table>

            </div>

HTML;



        }

    }





    /**

     * @return bool|MultiArray

     */

    public function productSettingEdit()
    {
        if ($this->prefix_editPro != "") {
            // If product is edit mode
            $eId = $this->editPid;
            $qry = "SELECT * FROM  `product_setting` WHERE `p_id` = '$eId'";
            $eData = $this->dbF->getRows($qry);
            return $eData;
        } else {
            return false;
        }
    }



    /**

     * @param $settingName

     * @param $array

     * @return string

     */

    public function productSettingArray($settingName, $array)
    {
        // enter setting name and qry execute array.. -> productSettingEdit() array
        if (!is_array($array)) {
            return "";
        }

        foreach ($array as $keya => $val) {
            if ($val['setting_name'] == $settingName && $val['p_id'] == $this->editPid) {
                return $val['setting_val'];
            }
        }

        return "";
    }



    public function productEditImagesMass($idz)
    {

        global $_e;

        // if($this->prefix_editPro!=""){

        // If product is edit mode

        $eId = $this->editPid;

        $qry = "SELECT * FROM  `product_image` WHERE `product_id` = '$idz' ORDER BY sort ASC";

        $eData = $this->dbF->getRows($qry);

        if ($this->dbF->rowCount > 0) {






            $temp1T = _uc($_e['Enter Alt']);

            $temp2T = _uc($_e['Update']);

            $temp3T = _uc($_e['Remove']);

            foreach ($eData as $key => $val) {

                $img = $val['image'];

                $imgId = $val['img_id'];

                $alt = $val['alt'];

                echo <<<HTML

                    <div class="preview albumPreview" id="image_$imgId">

                            <span class="imageHolder">

                                 <img src="../images/$img" loading="lazy"/>

                            </span>



                            <div class="progressHolder album" style="">

                                <input type="text" id="alt-$imgId" value="$alt" placeholder="$temp1T" class="form-control" style="margin:3px 0">

                                <a class="pImageAltUpdate$idz  btn btn-default btn-sm" data-id="$imgId" ><span>$temp2T</span>

                                    <i class='glyphicon glyphicon-save trash'></i>

                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                                </a>


                            </div>

                        </div>

HTML;

            }

        }

        // }

    }


    public function productEditImages()
    {

        global $_e;

        if ($this->prefix_editPro != "") {

            // If product is edit mode

            $eId = $this->editPid;

            $qry = "SELECT * FROM  `product_image` WHERE `product_id` = '$eId' ORDER BY sort ASC";

            $eData = $this->dbF->getRows($qry);

            if ($this->dbF->rowCount > 0) {




                $temp1T = _uc($_e['Enter Alt']);

                $temp2T = _uc($_e['Update']);

                $temp3T = _uc($_e['Remove']);

                foreach ($eData as $key => $val) {

                    $img = $val['image'];

                    $imgId = $val['img_id'];

                    $alt = $val['alt'];

                    echo <<<HTML

                    <div class="preview albumPreview" id="image_$imgId">

                            <span class="imageHolder">

                                 <img src="../images/$img" loading="lazy"/>

                            </span>



                            <div class="progressHolder album">

                                <input type="text" id="alt-$imgId" value="$alt" placeholder="$temp1T" class="form-control" style="margin:3px 0">

                                <a class="pImageAltUpdate  btn btn-default btn-sm" data-id="$imgId" ><span>$temp2T</span>

                                    <i class='glyphicon glyphicon-save trash'></i>

                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                                </a>

                                    <a class="productEditImageDel btn btn-danger btn-sm" data-id="$imgId">$temp3T</a>

                            </div>

                        </div>

HTML;

                }

            }

        }

    }


    public function productEditDetailImages()
    {

        global $_e;

        if ($this->prefix_editPro != "") {

            // If product is edit mode

            $eId = $this->editPid;

            $qry = "SELECT * FROM  `product_detail_image` WHERE `product_id` = '$eId' ORDER BY sort ASC";

            $eData = $this->dbF->getRows($qry);

            if ($this->dbF->rowCount > 0) {




                $temp1T = _uc($_e['Enter Sub Heading']);


                $temp2T = _uc($_e['Update']);

                $temp3T = _uc($_e['Remove']);

                $temp4T = _uc($_e['Enter Description']);


                foreach ($eData as $key => $val) {

                    $img = $val['image'];

                    $imgId = $val['img_id'];

                    $alt = unserialize($val['alt']);

                    $desc = unserialize($val['desc']);


                    $swedishHeading = isset($alt['Swedish']) ? $alt['Swedish'] : "";
                    $norwegianHeading = isset($alt['Norwegian']) ? $alt['Norwegian'] : "";
                    $danishHeading = isset($alt['Danish']) ? $alt['Danish'] : "";
                    $finnishHeading = isset($alt['Finnish']) ? $alt['Finnish'] : "";
                    $englishHeading = isset($alt['English']) ? $alt['English'] : "";
                    $germanHeading = isset($alt['German']) ? $alt['German'] : "";
                    $frenchHeading = isset($alt['French']) ? $alt['French'] : "";


                    $swedishDesc = isset($desc['Swedish']) ? $desc['Swedish'] : "";
                    $norwegianDesc = isset($desc['Norwegian']) ? $desc['Norwegian'] : "";
                    $danishDesc = isset($desc['Danish']) ? $desc['Danish'] : "";
                    $finnishDesc = isset($desc['Finnish']) ? $desc['Finnish'] : "";
                    $englishDesc = isset($desc['English']) ? $desc['English'] : "";
                    $germanDesc = isset($desc['German']) ? $desc['German'] : "";
                    $frenchDesc = isset($desc['French']) ? $desc['French'] : "";

                    echo <<<HTML

                    <div class="preview albumPreview" id="image_$imgId" style="">

                            <span class="imageHolder">

                                 <img src="../images/$img" loading="lazy"/>

                            </span>



                            <div class="progressHolder album" style="">
                            
                                <div class="section">
                                    <label class="swedish-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">Swedish</label>
                                    <input type="text" id="detail-swedish-alt-$imgId" placeholder="$temp1T" value="$swedishHeading" class="form-control swedish-heading" style="margin:3px 0">
                                    <textarea id="detail-swedish-desc-$imgId" class="form-control swedish-desc" style="margin:3px 0" placeholder="$temp4T">$swedishDesc</textarea>
                                </div>
                                
                                <div class="section">
                                    <label class="norwegian-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">Norwegian</label>
                                    <input type="text" id="detail-norwegian-alt-$imgId" placeholder="$temp1T" value="$norwegianHeading" class="form-control norwegian-heading" style="margin:3px 0">
                                    <textarea id="detail-norwegian-desc-$imgId" class="form-control norwegian-desc" style="margin:3px 0" placeholder="$temp4T">$norwegianDesc</textarea>
                                </div>
                                
                                <div class="section">
                                    <label class="danish-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">Danish</label>
                                    <input type="text" id="detail-danish-alt-$imgId" placeholder="$temp1T" value="$danishHeading" class="form-control danish-heading" style="margin:3px 0">
                                    <textarea id="detail-danish-desc-$imgId" class="form-control danish-desc" style="margin:3px 0" placeholder="$temp4T">$danishDesc</textarea>
                                </div>
                                
                                <div class="section">
                                    <label class="finnish-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">Finnish</label>
                                    <input type="text" id="detail-finnish-alt-$imgId" placeholder="$temp1T" value="$finnishHeading" class="form-control finnish-heading" style="margin:3px 0">
                                    <textarea id="detail-finnish-desc-$imgId" class="form-control finnish-desc" style="margin:3px 0" placeholder="$temp4T">$finnishDesc</textarea>
                                </div>
                                
                                <div class="section">
                                    <label class="english-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">English</label>
                                    <input type="text" id="detail-english-alt-$imgId" placeholder="$temp1T" value="$englishHeading" class="form-control english-heading" style="margin:3px 0">
                                    <textarea id="detail-english-desc-$imgId" class="form-control english-desc" style="margin:3px 0" placeholder="$temp4T">$englishDesc</textarea>
                                </div>                                
                                <div class="section">
                                    <label class="german-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">German</label>
                                    <input type="text" id="detail-german-alt-$imgId" placeholder="$temp1T" value="$germanHeading" class="form-control german-heading" style="margin:3px 0">
                                    <textarea id="detail-german-desc-$imgId" class="form-control german-desc" style="margin:3px 0" placeholder="$temp4T">$germanDesc</textarea>
                                </div>                                
                                <div class="section">
                                    <label class="french-label" style="width: 100% !important;text-align: left;margin: 0;margin-top: 5px;">French</label>
                                    <input type="text" id="detail-french-alt-$imgId" placeholder="$temp1T" value="$frenchHeading" class="form-control french-heading" style="margin:3px 0">
                                    <textarea id="detail-french-desc-$imgId" class="form-control french-desc" style="margin:3px 0" placeholder="$temp4T">$frenchDesc</textarea>
                                </div>
                               
                                <a class="pDetailImageAltUpdate  btn btn-default btn-sm" data-id="$imgId" ><span>$temp2T</span>

                                    <i class='glyphicon glyphicon-save trash'></i>

                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                                </a>

                                    <a class="productEditDetailImageDel btn btn-danger btn-sm" data-id="$imgId">$temp3T</a>

                            </div>

                        </div>

HTML;

                }

            }

        }

    }


    public function productSelectedNode()
    {

        $eId = $this->editPid;

        $qry = "SELECT * FROM  `product_category` WHERE `procat_prodet_id` = '$eId'";

        $eData = $this->dbF->getRow($qry);

        if ($this->dbF->rowCount > 0)

            return $eData['procat_cat_id'];
        else

            return "";

    }





    /**

     * @param $name

     * @param $currencyId

     * @param $array

     * @return string

     */

    private function priceAdditionChargesEditArray($name, $currencyId, $array)
    {

        foreach ($array as $keya => $val) {

            if ($val['proadc_name'] == $name && $val['proadc_cur_id'] == $currencyId && $val['proadc_prodet_id'] == $this->editPid) {

                return $val['proadc_price'];

            }

        }

        return "";

    }





    private $additionalCost_name;

    private function additionalCost_name()
    {

        $additionalCost_name = array('when_cart_price', 'checkout_price'); // extra addional price defined...

        if ($this->functions->developer_setting("add_free_gift_in_cart") == "1") {

            $additionalCost_name[] = "giftAdd_when_cart_price";

        }

        $this->additionalCost_name = $additionalCost_name;

    }





    public function priceAdditionChargesEdit()
    {

        global $_e;



        $notIn = "'" . implode("','", $this->additionalCost_name) . "'";

        $eId = $this->editPid;

        $sql = "SELECT * FROM  `product_addcost` WHERE `proadc_prodet_id` = '$eId'  AND proadc_name NOT IN ($notIn)  ";

        $eData = $this->dbF->getRows($sql);



        if ($this->dbF->rowCount > 0) {

            $name = array();

            foreach ($eData as $key => $val) {

                if (!in_array($val['proadc_name'], $name)) {

                    $name[] = $val['proadc_name'];

                }

            }



            $countryCodeList = $this->functions->countrylist(); // country list

            $currency_data = $this->c_currency->getList(); // get currency list



            echo '<br>

                    <div class="panel panel-primary">

                        <div class="panel-heading"><h3 class="panel-title">' . _uc($_e['Additional Saved Charges']) . '</h3></div>

                        <div class="panel-body table-responsive">

                            <table id="table_priceEdit"

                                   class="table table-condensed table-responsive">

                                <thead>

                                <tr>

                                    <th>' . _uc($_e['Name']) . '</th>';



            foreach ($currency_data as $data) {

                //print top scale county name.. first TR

                $country_id = $data['cur_id'];

                $country_name = $countryCodeList[$data['cur_country']];

                $currency = $data["cur_name"];

                $symbol = $data['cur_symbol'];



                $cur_id_array[] = $country_id;

                $cur_name_array[] = "$country_name ($currency)";



                echo "<th>$country_name ($currency)</th>";

            }



            echo '

                        </tr>

                        </thead>

                        <tbody id="tbody_priceEdit">';



            for ($i = 0; $i < sizeof($name); $i++) {

                echo '<tr>

                            <td><div class="input-group input-group-sm">

                                <span class="input-group-addon">

                                    <input type="checkbox" checked value="rowid_PriceSaved_' . $i . '" name="addCost_checkList[]">

                                </span>

                                <input type="text" value="' . $name[$i] . '" name="rowid_PriceSaved_' . $i . '-addCost_name" class="form-control ">

                            </div>

                            </td>';



                foreach ($currency_data as $data) {

                    //print top scale county name.. first TR

                    $country_id = $data['cur_id'];

                    $symbol = $data['cur_symbol'];



                    $price = $this->priceAdditionChargesEditArray($name[$i], $country_id, $eData);

                    echo '<td>

                                      <div class="input-group input-group-sm">

                                        <span class="input-group-addon">' . $symbol . '</span>

                                        <input type="text" class="form-control" value="' . $price . '"  name="rowid_PriceSaved_' . $i . '-addCost_cost[' . $country_id . ']">

                                      </div>

                                      </td>';

                }



                echo '</tr>';

            }



            echo '</tbody>

                    </table>

                    <div class="panel_note_footer"></div>

                </div>

            </div>';

        }



    }





    public function priceCheckOutChargesEdit()
    {

        global $_e;

        $name = $this->additionalCost_name;
        $additionalCost_in = "'" . implode("','", $this->additionalCost_name) . "'";
        $eId = $this->editPid;

        $sql = "SELECT * FROM  `product_addcost` WHERE `proadc_prodet_id` = '$eId' AND proadc_name IN ($additionalCost_in) ";
        $eData = $this->dbF->getRows($sql);

        if (!empty($name)) {
            foreach ($eData as $key => $val) {
                if (!in_array($val['proadc_name'], $name)) {
                    $name[] = $val['proadc_name'];
                }
            }
            $countryCodeList = $this->functions->countrylist(); // country list
            $currency_data = $this->c_currency->getList(); // get currency list
            echo '<br>
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h3 class="panel-title">' . _uc($_e['Charges On Offers']) . '</h3></div>
                        <div class="panel-body table-responsive">
                        <small>If you don\'t want to place offer on selected Region left Blank price or 0.</small>
                            <table id="table_priceEdit"
                                   class="table table-condensed table-responsive">
                                <thead>
                                <tr><th>' . _uc($_e['Name']) . '</th>';

            foreach ($currency_data as $data) {
                //print top scale county name.. first TR
                $country_id = $data['cur_id'];
                $country_name = $countryCodeList[$data['cur_country']];
                $currency = $data["cur_name"];
                $symbol = $data['cur_symbol'];
                $cur_id_array[] = $country_id;
                $cur_name_array[] = "$country_name ($currency)";
                echo "<th>$country_name ($currency)</th>";
            }
            echo '</tr></thead>
            <tbody id="tbody_priceEdit">';

            for ($i = 0; $i < sizeof($name); $i++) {
                echo '<tr><td><div class="input-group input-group-sm">
                <span class="input-group-addon">
                <input type="checkbox" checked value="rowid_PriceSaved_' . $i . '" name="addCost_checkList[]" class="disable">
                </span>
                <input type="text" value="' . $name[$i] . '" name="rowid_PriceSaved_' . $i . '-addCost_name" class="form-control ">
                </div></td>';

                foreach ($currency_data as $data) {
                    //print top scale county name.. first TR
                    $country_id = $data['cur_id'];
                    $symbol = $data['cur_symbol'];
                    $price = $this->priceAdditionChargesEditArray($name[$i], $country_id, $eData);
                    $price = empty($price) ? 0 : $price;
                    echo '<td><div class="input-group input-group-sm">
                    <span class="input-group-addon">' . $symbol . '</span>
                    <input type="text" class="form-control" value="' . $price . '"  name="rowid_PriceSaved_' . $i . '-addCost_cost[' . $country_id . ']">
                    </div></td>';
                }
                echo '</tr>';
            }

            echo '</tbody></table><div class="panel_note_footer"></div>
            </div></div>';
        }
    }

    /****************************************************/



    public function productLastImage($id)
    {

        $sql = "SELECT * FROM `product_image` WHERE product_id = '$id' ORDER BY sort ASC ";

        $data = $this->dbF->getRow($sql);

        return $data['image'];

    }



    //////////////// Main menu function ///////////////////////////////////////////

    public function menuTypeSingle($type = 'main', $under = '0', $url_function = false)
    {

        global $_e;

        $sql = "SELECT * FROM categories WHERE under = '$under' AND type='$type' ORDER BY sort ASC";

        $data = $this->dbF->getRows($sql);



        if (!$this->dbF->rowCount) {
            return false;
        }

        foreach ($data as $val) {

            $id = $val['id'];

            $heading = htmlspecialchars(translateFromSerialize($val['name']));



            $link = $val['link'];

            if ($url_function) {

                $link = $this->functions->addCatRegexWebUrlInLink($link);

            } else {

                $link = $this->functions->addWebUrlInLink($link);

            }



            $icon = $val['icon'];

            $icon = $this->functions->addWebUrlInLink($icon);



            $array["$id"]['name'] = $heading;

            $array["$id"]['link'] = $link;

            $array["$id"]['icon'] = $icon;

            $array["$id"]['id'] = $id;

            $array["$id"]['active'] = '';

            $activeLink = pageLink(false);

            if (($activeLink) == ($link)) {

                $array["$id"]['active'] = '1';

            }

        }
        return $array;

    }


    public function countMissingProducts()
    {
        $sql = "SELECT `prodet_id` FROM `proudct_detail` WHERE `slug` IS NOT NULL";
        $res = $this->dbF->getRows($sql);

        $missing_array = array();
        $return = array();

        $i = 0;
        foreach ($res as $key => $value) {
            $new_id = $value['prodet_id'];

            $sql_count = "SELECT `prodet_id` FROM `proudct_detail` WHERE `prodet_id` = '$new_id'";
            $this->dbF->getRow($sql_count);

            if ($this->dbF->rowCount == 0) {
                $missing_array[] = $value['prodet_id'];
                $i++;
            }
        }




        $return['ids'] = json_encode($missing_array);
        $return['count'] = $i;

        return $return;


    }

}
?>