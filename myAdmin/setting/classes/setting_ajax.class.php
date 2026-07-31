<?php
require_once(__DIR__ . "/../../global_ajax.php"); //connection setting db

class setting_ajax extends object_class
{
    public $productF;
    public function __construct()
    {
        parent::__construct('3');
        if (isset($GLOBALS['productF']))
            $this->productF = $GLOBALS['productF'];
        else {
            if ($this->functions->developer_setting('product') == '1') {
                $this->functions->require_once_custom('product_functions');
                $this->productF = new product_function();
            }
        }
    }

    public function deleteHardWord()
    {
        try {
            $this->db->beginTransaction();

            $id = $_POST['id'];

            $sql2 = "DELETE FROM hardwords WHERE id='$id'";
            $this->dbF->setRow($sql2, false);
            if ($this->dbF->rowCount)
                echo '1';
            else
                echo '0';

            $this->db->commit();
            $this->functions->setlog('DELETE', 'Special Words', $id, 'Special Words Delete Successfully');
        } catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function hardWords()
    {
        global $_e;
        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $length = (isset($_POST['length'])) ? $_POST['length'] : 25;
        $draw = (isset($_POST['draw'])) ? (int) $_POST['draw'] : null;
        $search = (isset($_POST['search']['value']) && $_POST['search']['value'] != '') ? ($_POST['search']['value']) : "";

        $orderBy = "ORDER by id DESC limit 25";
        $sql = "SELECT * FROM hardwords ";
        if (!empty($search) && strlen($search) >= 3) {
            $sql .= " WHERE `en` LIKE '%{$search}%' OR `lang` LIKE '%{$search}%' ";
        } else {
            $sql .= $orderBy;
        }
        $data = $this->dbF->getRows($sql);

        $columns = array();
        if ($draw == 1) {
            $draw - 1;
        }
        $recordsTotal = $this->dbF->rowCount;
        $columns["draw"] = $draw + 1;
        $columns["recordsTotal"] = $recordsTotal; //total record,
        $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear

        $i = $start;
        foreach ($data as $key => $val) {

            $i++;
            $id = $val['id'];
            $delete = "<a data-id='$id' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                            </a>";

            if ($val['allowDelete'] == '1' || 1 === 1) {
                $delete = "<a data-id='$id' onclick='deleteHardWords(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }

            @$langWord = unserialize($val['lang']);
            $serial = true;
            if ($langWord === false) {
                $serial = false;
            }
            $value = $val['lang'];
            if ($serial) {
                @$value = $langWord[$defaultlang];
                if (!isset($value) || $value == '') {
                    $value = $val['en'];
                }
            }
            $action = "<div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-setting?page=hardWords&editId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            $delete
                        </div>";
            $columns["data"][$key] = array(
                $i,
                mb_convert_encoding($val['en'], 'UTF-8', 'ISO-8859-1'),
                mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1'),
                "$val[place]",
                $action
            );
        }
        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }

        echo json_encode($columns);
    }

    public function history()
    {
        global $_e;
        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $length = (isset($_POST['length'])) ? $_POST['length'] : 25;
        $draw = (isset($_POST['draw'])) ? (int) $_POST['draw'] : null;
        $search = (isset($_POST['search']['value']) && $_POST['search']['value'] != '') ? ($_POST['search']['value']) : "";
    
        $dateCodeFrom = (isset($_POST['dateCodeFrom']) && $_POST['dateCodeFrom'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeFrom'])->format('Y-m-d') . ' 00:00:00' : NULL;
        $dateCodeTo = (isset($_POST['dateCodeTo']) && $_POST['dateCodeTo'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeTo'])->format('Y-m-d') . ' 23:59:59' : NULL;
    
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `log_time` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' " : '';
    
        $orderBy = " ORDER BY log_id DESC limit $start, $length";
        $sql = "SELECT * FROM activity_log ";
    
        if (!empty($search) && strlen($search) >= 3) {
            $sql .= " WHERE (log_title LIKE '%{$search}%' OR log_desc LIKE '%{$search}%' OR ref_name LIKE '%{$search}%') ";
            if ($between_sql) {
                $sql .= " AND $between_sql";
            }
        } elseif ($between_sql) {
            $sql .= " WHERE $between_sql";
        }
    
        $sql .= $orderBy;
    
        $data = $this->dbF->getRows($sql);
    
        $columns = [];
        if ($draw == 1) {
            $draw - 1;
        }
        $recordsTotal = $this->dbF->rowCount;
        $columns["draw"] = $draw + 1;
        $columns["recordsTotal"] = $recordsTotal;
        $columns["recordsFiltered"] = $recordsTotal;
    
        $i = $start;
        foreach ($data as $key => $val) {
            $i++;
            $columns["data"][$key] = [
                $i,
                $val["log_title"],
                // $val["log_ip"],
                // $val["log_browser"],
                $val["ref_user"],
                $val["add_qty"],
                $val["minus_qty"],
                $val["pid"],
                $val["sid"],
                $val["order_id"],
                $val["ref_id"],
                $val["log_desc"],
                $val["ref_name"],
                $val["log_time"]
            ];
        }
    
        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }
        echo json_encode($columns);
    }

    public function hardWords2()
    {

        $search = (isset($_POST['search']['value']) && $_POST['search']['value'] != '') ? ($_POST['search']['value']) : "";
        $orderBy = "ORDER by id DESC limit 100";
        echo strlen($search);
        return 0;
        $sql = "SELECT * FROM hardwords ";
        if (!empty($search) && strlen($search) >= 3) {
            $sql .= " WHERE `en` LIKE '%{$search}%' OR `lang` LIKE '%{$search}%' ";
        }
        echo $sql .= $orderBy;


        $data = $this->dbF->getRows($sql);

        $columns = array();
        $i = 1;
        $defaultlang = $this->functions->AdminDefaultLanguage();
        $columns["draw"] = $draw + 1;
        $columns["recordsTotal"] = 10; //total record,
        $columns["recordsFiltered"] = 1200;
        foreach ($data as $key => $val) {
            $id = $val['id'];
            $delete = "<a data-id='$id' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                            </a>";

            if ($val['allowDelete'] == '1' || 1 === 1) {
                $delete = "<a data-id='$id' onclick='deleteHardWords(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }

            @$langWord = unserialize($val['lang']);
            $serial = true;
            if ($langWord === false) {
                $serial = false;
            }
            $value = $val['lang'];
            if ($serial) {
                @$value = $langWord[$defaultlang];
                if (!isset($value) || $value == '') {
                    $value = $val['en'];
                }
            }
            $action = "<div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-setting?page=hardWords&editId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            $delete
                        </div>";

            $columns["data"][$key] = array(
                $i,
                "$val[en]",
                $value,
                "$val[place]",
                " ",
                " ",
                $action
            );
            $i++;
        }

        echo json_encode($columns);
    }
    public function massImgSort()
    {

        try {
            global $_e;

            $sql = "SELECT * from product_image";
            $res = $this->dbF->getRows($sql, false);


            echo '1';
        } catch (Exception $e) {
            echo '0';
        }
    }



    public function addStapleQuantity()
    {
        //Price
        ############# MULTI CURRENCY ################
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency = new currency_management();
        $countryCodeList = $this->functions->countrylist(); // country list
        $currency_data = $c_currency->getList(); // get currency list
        ############# MULTI CURRENCY ################

        $tds = "";
        $tds .= "<tr><td colspan='2' class='borderIfNotabs'><input type='text' class='form-control' name='setting[stapleProductSetting][quantity][]' value='' placeholder='Quantity'></td></tr>";
        foreach ($currency_data as $val) {
            $country_id = $val['cur_id'];
            $symbol = $val['cur_symbol'];
            $country_name = $countryCodeList[$val['cur_country']];
            $currency = $val["cur_name"];
            $tds .= "";
            $tds .= "<tr><td>&nbsp;</td>";
            $tds .= '<td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon">' . $symbol . '</span>
                          <input type="text" class="form-control" value="" pattern="\d+(\.\d+)?"  name="setting[stapleProductSetting][price][' . $country_id . '][]" >
                        </div>
                      </td>
                       </tr>';
        }

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => " <br>
                        <table class='table table-striped table-hover'>$tds</table><hr>"
        );

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $a = $this->functions->print_form($form_fields, $format);
        return $a;
    }

    public function addSalesFeature()
    {
        //Price
        ############# MULTI CURRENCY ################
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency = new currency_management();
        $countryCodeList = $this->functions->countrylist(); // country list
        $currency_data = $c_currency->getList(); // get currency list
        ############# MULTI CURRENCY ################


        $settingData = $this->getIBMSSettingData();

        $productData = $this->productF->productActiveSql('prodet_id,prodet_name');

        $prod_count = $_POST['prod_count'];

        foreach ($productData as $val) {

            $name = $this->functions->unserializeTranslate($val['prodet_name']);
            $pro_img = $this->productF->getProductSingleImage($val['prodet_id']);
            $img_pa = WEB_URL . '/images/' . $pro_img['image'];
            $img = '<img src="' . $img_pa . '">';

            $product_array[$val['prodet_id']]['name'] = $name;
            $product_array[$val['prodet_id']]['image'] = $img_pa;
            $product_array[$val['prodet_id']]['imgSrc'] = $pro_img['image'];
        }

        $tds = "";
        $tds .= "<tr>
                    <td class='borderIfNotabs'>";

        $tds .= "<select class='form-control' name='setting[salesFeature][country][]'>";

        foreach ($currency_data as $val) {
            $country_id = $val['cur_id'];
            $symbol = $val['cur_symbol'];
            $country_name = $countryCodeList[$val['cur_country']];
            $currency = $val["cur_name"];
            @$oldQty = $valForm[$country_id]['quantity'];
            @$oldPrice = $valForm[$country_id]['price'];
            $tds .= "<option value=" . $country_id . ">" . $country_name . " (" . $symbol . ")" . "</option>";
        }

        $tds .= "</select>";

        $tds .= "</td>
                    <td class='borderIfNotabs'>
                        <input type='text' class='form-control' name='setting[salesFeature][cartAmount][]' value='' placeholder='Quantity'>
                    </td>
                </tr>";

        $tds .= "<tr>
                    <td>Select Products</td>
                    <td class='borderIfNortabs'>
                        <select name='setting[salesFeature][products][" . $prod_count . "][]' class='form-control test salesFeatureSetting' id='salesFeatureSetting' style='height:300px' multiple=''>";

        foreach ($product_array as $key => $value) {
            $tds .= '<option value="' . $key . '">' . $value['name'] . '</option>';
        }

        $tds .= "</select></td><td colspan='2'></td><tr>";


        $tds .= "<td>Price</td>";
        $tds .= '<td>
                    <input type="text" class="form-control" value="" pattern="\d+(\.\d+)?"  name="setting[salesFeature][price][]" >
                  </td>
                   ';

        $tds .= "</tr>";

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => " <br>
                        <table class='table table-striped table-hover'>$tds</table><hr>"
        );

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $a = $this->functions->print_form($form_fields, $format);
        return $a;
    }

    public function getIBMSSettingData()
    {
        $sql = "SELECT * FROM ibms_setting ORDER BY id ASC";
        $data = $this->dbF->getRows($sql);
        return $data;
    }

    public function getIBMSSettingArrayValue($Key, $data)
    {
        foreach ($data as $keya => $val) {
            if ($val['setting_name'] == $Key) {
                return $val['setting_val'];
            }
        }
        return "";
    }

    public function massPriceUpdateCH()
    {
        global $_e;

        try {

            $curId = $_POST['curId'];
            $divide = $_POST['divide'];
            $multiply = $_POST['multiply'];

            if ($curId == 20) {
                $index = 9;
                $code = '';
            } elseif ($curId == 30) {
                $index = 0;
                $code = 'fr';
            } elseif ($curId == 31) {
                $index = 1;
                $code = 'de';
            } elseif ($curId == 32) {
                $index = 2;
                $code = 'nl';
            } elseif ($curId == 33) {
                $index = 3;
                $code = 'us';
            } elseif ($curId == 34) {
                $index = 4;
                $code = 'be';
            } elseif ($curId == 35) {
                $index = 5;
                $code = 'uk';
            } elseif ($curId == 36) {
                $index = 6;
                $code = 'es';
            } elseif ($curId == 37) {
                $index = 7;
                $code = 'at';
            } elseif ($curId == 38) {
                $index = 8;
                $code = 'it';
            } elseif ($curId == 39) {
                $index = 10;
                $code = 'ot';
            }

            $priceCalc = $this->functions->ibms_setting('priceCalc');
            $priceCalc = unserialize($priceCalc);

            $country_id = $priceCalc['country'][$index];
            $cur_divide = $priceCalc['divide'][$index];
            $cur_multiply = $priceCalc['multiply'][$index];

            $sql = "SELECT DISTINCT(`prodet_id`) FROM `proudct_detail` WHERE `slug` IS NOT NULL";
            $comProducts = $this->dbF->getRows($sql);

            $find_in = '';
            $updArray = array();
            $sizeArray = array();
            $colorArray = array();


            echo '1';
        } catch (Exception $e) {
            echo '0';
        }
    }




    public function massPriceUpdateCHwithoutSS()
    {

        global $_e;

        try {

            $curId = $_POST['curId'];
            $divide = $_POST['divide'];
            $multiply = $_POST['multiply'];

            if ($curId == 20) {
                $index = 9;
                $code = '';
            } elseif ($curId == 30) {
                $index = 0;
                $code = 'fr';
            } elseif ($curId == 31) {
                $index = 1;
                $code = 'de';
            } elseif ($curId == 32) {
                $index = 2;
                $code = 'nl';
            } elseif ($curId == 33) {
                $index = 3;
                $code = 'us';
            } elseif ($curId == 34) {
                $index = 4;
                $code = 'be';
            } elseif ($curId == 35) {
                $index = 5;
                $code = 'uk';
            } elseif ($curId == 36) {
                $index = 6;
                $code = 'es';
            } elseif ($curId == 37) {
                $index = 7;
                $code = 'at';
            } elseif ($curId == 38) {
                $index = 8;
                $code = 'it';
            } elseif ($curId == 39) {
                $index = 10;
                $code = 'ot';
            }

            $priceCalc = $this->functions->ibms_setting('priceCalc');
            $priceCalc = unserialize($priceCalc);

            $country_id = $priceCalc['country'][$index];
            $cur_divide = $priceCalc['divide'][$index];
            $cur_multiply = $priceCalc['multiply'][$index];

            $sql = "SELECT DISTINCT(`prodet_id`) FROM `proudct_detail` WHERE `slug` IS NOT NULL";
            $comProducts = $this->dbF->getRows($sql);

            $find_in = '';
            $updArray = array();
            $sizeArray = array();
            $colorArray = array();




            echo '1';
        } catch (Exception $e) {
            echo '0';
        }
    }
}
