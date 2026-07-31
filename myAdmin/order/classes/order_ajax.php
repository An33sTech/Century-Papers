<?php
require_once (__DIR__ . "/../../global_ajax.php"); //connection setting db

class order_ajax extends object_class
{
    public $productF; // admin/product_management/functions/
    public $product; // admin/product/classes/
    public $order_c;
    public $invoice_c;

    public function __construct()
    {
        parent::__construct('3');
        //product_management functions
        if (isset($GLOBALS['productF']))
            $this->productF = $GLOBALS['productF'];
        else {
            require_once (__DIR__ . "/../../product_management/functions/product_function.php");
            $this->productF = new product_function();
        }

        //product add/edit class
        if (isset($GLOBALS['product']))
            $this->product = $GLOBALS['product'];
        else {
            require_once (__DIR__ . "/../../product/classes/product.class.php");
            $this->product = new product();
        }

        require_once (__DIR__ . "/order.php");
        $this->order_c = new order();        
        
        require_once (__DIR__ . "/invoice.php");
        $this->invoice_c = new invoice();

    }



    private function getBrowserCommon()
    {

        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

    public function getOrderProductJson()
    {
        $country = $_POST['country'];
        $countryData = $this->productF->productCountryId($country);
        $countryId = $countryData['cur_id'];
        $priceCode = $countryData['cur_symbol'];
        $sql = "SELECT `proudct_detail`.`prodet_id`, `proudct_detail`.`prodet_name`,`product_price`.`propri_intShipping`
            FROM `proudct_detail` join `product_price` on
                `proudct_detail`.`prodet_id`=`product_price`.`propri_prodet_id`
                where `product_price`.`propri_cur_id`='$countryId'
                ORDER BY `proudct_detail`.`prodet_id` ASC";
        $product = $this->dbF->getRows($sql);

        $defaultLang = $this->functions->AdminDefaultLanguage();
        $JSON = '[';


        if ($this->dbF->rowCount > 0) {
            $JSON2 = '';
            foreach ($product as $val) {
                $id = $val['prodet_id'];
                $name = unserialize($val['prodet_name']);

                //verify story country Product
                $sql = "SELECT * from product_inventory
                        WHERE qty_product_id = '$id' AND qty_store_id in
                        (SELECT store_pk FROM `store_name` WHERE store_country = '$country')";

                $result = $this->dbF->getRows($sql);
                if ($this->dbF->rowCount > 0) {
                } else {
                    continue;
                }


                //scale JSON
                $scle = $this->productF->scaleSQL($id, '`prosiz_id`,`prosiz_name`');
                if ($this->dbF->rowCount > 0) {
                    $SCALE = '[';
                    $temp = '';
                    foreach ($scle as $sval) {
                        $sWeight = $this->productF->getProductWeight($id, $sval['prosiz_id']);
                        $temp .= '{"id": "' . $sval['prosiz_id'] . '","label" : "' . $sval['prosiz_name'] . '", "sWeight": "' . $sWeight . '" },';
                    }
                    $temp = trim($temp, ',');
                    $SCALE .= $temp;
                    $SCALE .= ']';
                } else {
                    $SCALE = 'null';
                }

                //color json
                $colr = $this->productF->colorSQL($id, '`propri_id`,`proclr_name`');
                if ($this->dbF->rowCount > 0) {
                    $COLOR = '[';
                    $temp = '';
                    foreach ($colr as $cval) {
                        $temp .= '{"id": "' . $cval['propri_id'] . '","label" : "' . $cval['proclr_name'] . '"},';
                    }
                    $temp = trim($temp, ',');
                    $COLOR .= $temp;
                    $COLOR .= ']';
                } else {
                    $COLOR = 'null';
                }

                //JSON create
                $pSetting = $this->productF->getProductSetting($id); // Full Setting Report of data
                $weight = $this->productF->productSettingArray('defaultWeight', $pSetting, $id);
                $weight = floatval($weight);
                $JSON2 .= '{
                        "id" : "' . $id . '",
                        "label" : "' . $name[$defaultLang] . '",
                        "scale" : ' . $SCALE . ',
                        "color" : ' . $COLOR . ',
                        "priceCode" : "' . $priceCode . '",
                        "weight" : "' . $weight . '",
                        "interShipping" : "' . $val['propri_intShipping'] . '"
                        },';
            }
            $JSON2 = trim($JSON2, ',');
            $JSON .= $JSON2;

        }
        $JSON .= ']';
        $JSON = trim($JSON);
        echo $JSON;
        /**
         * Out Put :
         * {
         * id:1,
         * label:asad,
         * scale: {id:2,label:raza},
         * color: {id:2,label:sheerazi},
         * weight:64
         * }
         */
    }

    public function getOrderProductStoreJson()
    {
        $country = $_POST['country'];
        $countryData = $this->productF->productCountryId($country);
        $countryId = $countryData['cur_id'];
        $pId = $_POST['pId'];
        $scleId = $_POST['scaleId'];
        $colorId = $_POST['colorId'];
        @$customId = $_POST['customId'];
        $sql = "SELECT * FROM `product_inventory` WHERE `qty_product_id` = '$pId' AND `qty_product_scale_name` = '$scleId' AND `qty_product_color` = '$colorId'";
        $product = $this->dbF->getRows($sql);
        $JSON = '[';

        if ($this->dbF->rowCount > 0) {
            $JSON2 = '';
            foreach ($product as $val) {
                $storeName = $this->productF->getStoreName($val['qty_store_id']);
                $price = $this->productF->productTotalPrice($pId, $scleId, $colorId, $customId, $country);
                $discountArray = $this->productF->productDiscount($pId, $countryId);
                if (!empty($discountArray)) {
                    $discount = $discountArray['discount'];
                    $discountFormat = $discountArray['discountFormat'];
                    if ($discountFormat == 'price') {
                        // $discount   =   $price-$discount;
                    } else if ($discountFormat == 'percent') {
                        $discount = ($price * $discount) / 100;
                    }
                } else {
                    $discount = 0;
                }
                $JSON2 .= '{
                        "label" : "' . $storeName . '",
                        "id"    : "' . $val['qty_pk'] . '",
                        "storeId": "' . $val['qty_store_id'] . '",
                        "qty"   : ' . $val['qty_item'] . ',
                        "price" : "' . $price . '",
                        "discount" : "' . $discount . '"
                        },';
            }
            $JSON .= trim($JSON2, ',');
        }

        $JSON .= ']';
        $JSON = trim($JSON);
        echo $JSON;
    }

    public function finalPriceShipping()
    {
        require_once (__DIR__ . '/../../shipping/classes/shipping.php');
        $shippingC = new shipping();


        $storeCountry = $_POST['storeCountry'];
        $deliverCountry = $_POST['deliverCountry'];

        $hash = "$storeCountry:$deliverCountry";

        $sql = "SELECT * FROM `shipping` WHERE hash = '$hash'";
        $data = $this->dbF->getRow($sql);
        $array = array();
        if ($this->dbF->rowCount > 0) {
            $array['find'] = "1"; // Found
            $array['shp_int'] = $data['shp_int'];
            $weight = $shippingC->shpWeightArrayFind($data['shp_weight']);
            $array['shp_weight'] = floatval($weight);
            $array['shp_price'] = $data['shp_price'];
        } else {
            $array['find'] = "0"; // Not Found
            $array['message'] = "N0 Shipping Date Found";
        }
        echo json_encode($array);

    }

    public function delOrder()
    {
        try {
            $this->db->beginTransaction();

            $id = $_POST['itemId'];

            $sql = "SELECT * FROM `order_invoice_product` WHERE  order_invoice_id='$id'";
            $oldData = $this->dbF->getRows($sql);
            foreach ($oldData as $val) {
                $pIds = $val['order_pIds'];
                $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                $pId = $pArray[0]; // 491
                $scaleId = $pArray[1]; // 426
                $colorId = $pArray[2]; // 435
                $storeId = $pArray[3]; // 5
                @$customId = $pArray[4]; // 5

                //delete custom if has
                if ($customId != '0' && !empty($customId)) {
                    $sql = "DELETE FROM p_custom_submit WHERE id = '$customId'";
                    $this->dbF->setRow($sql);
                }
            }

            $sql2 = "DELETE FROM order_invoice WHERE order_invoice_pk='$id'";
            $this->dbF->setRow($sql2, false);
            if ($this->dbF->rowCount)
                echo '1';
            else
                echo '0';

            $this->db->commit();
            $this->functions->setlog('DELETE', 'Order Invoice', $id, 'Order Invoice Delete Successfully');
        } catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }





    public function order_fetch2($page)
    {
        global $_e;
        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $length = (isset($_POST['length'])) ? $_POST['length'] : 10;
        $draw = (isset($_POST['draw'])) ? (int) $_POST['draw'] : null;
        $search = (isset($_POST['search']) && $_POST['search'] != '') ? ($_POST['search']['value']) : null;
        $order = (isset($_POST['order'])) ? $_POST['order'][0] : 0;

        $order_by_sql = ' ORDER BY `flagged` DESC, order_invoice_pk DESC ';
        if ($order) {
            # order by sql generation
            $order_by = ($order['column']);
            $order_by_direction = strtoupper($order['dir']);

            switch ($order_by) {
                case '0':
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '1':
                    # INVOICE...
                    $order_by_sql = ' ORDER BY invoice_id ' . $order_by_direction;
                    break;
                case '2':
                    # Country...
                    $order_by_sql = ' ORDER BY shippingCountry ' . $order_by_direction;
                    break;
                case '3':
                    # INVOICE DATE...
                    $order_by_sql = ' ORDER BY invoice_date ' . $order_by_direction;
                    break;
                case '4':
                    # CUSTOMER NAME...
                    $order_by_sql = ' ORDER BY ac.acc_name ' . $order_by_direction;
                    break;
                case '5':
                    # SOLD PRICE...
                    $order_by_sql = ' ORDER BY total_price ' . $order_by_direction;
                    break;
                case '6':
                    # PAYMENT METHOD...
                    $order_by_sql = ' ORDER BY paymentType ' . $order_by_direction;
                    break;
                case '7':
                    # ORDER PROCESS... CANNOT DO THIS CURRENTLY, BECAUSE THIS COMES FROM ORDER_INVOICE_PRODUCT AND CAN BE MULTIPLE
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '8':
                    # Invoice Status...
                    $order_by_sql = ' ORDER BY invoice_status ' . $order_by_direction;
                    break;

                default:
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
            }

        }



        ##### ADDITIONAL CUSTOM FILTER FILEDS #####
        $dateCodeFrom = (isset($_POST['dateCodeFrom']) && $_POST['dateCodeFrom'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeFrom'])->format('Y-m-d') . ' 00:00:00 ' : NULL;
        $dateCodeTo = (isset($_POST['dateCodeTo']) && $_POST['dateCodeTo'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeTo'])->format('Y-m-d') . ' 23:59:59 ' : NULL;

        ## make between sql for date
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' AND " : '';

        ## if date range filter is applied then apply its date order by sql
        ## if order is null, and date range is not empty then use between sql order by , else use order by of the datatable column selected
        $order_by_sql = (!$order && $between_sql != '') ? ' ORDER BY `flagged` DESC, `dateTime` ASC ' : $order_by_sql;


        #### Search SQL #####
        $country = $this->functions->countryKeyByName($search);
        $country = !empty($country) ? " `shippingCountry` = '{$country}' OR " : "";
        $statusSearch = '';
        $statusSQL = '';
        if ($search) {

            $input = preg_quote("$search", '~');
            $invst = $this->getInvoiceStatusHardwords('Received');
            $arr = array(
                11 => $this->getInvoiceStatusHardwords('Received'),
                2 => $this->getInvoiceStatusHardwords('pending'),
                5 => $this->getInvoiceStatusHardwords('Ready For Packaging'),
                0 => $this->getInvoiceStatusHardwords('Cancel'),
                9 => $this->getInvoiceStatusHardwords('Partial Delivery Done'),
                6 => $this->getInvoiceStatusHardwords('Full Refunded'),
                10 => $this->getInvoiceStatusHardwords('Awaiting Measures From Customer'),
                7 => $this->getInvoiceStatusHardwords('Order send for factory'),
                3 => $this->getInvoiceStatusHardwords('Complete'),
                1 => $this->getInvoiceStatusHardwords('Denied'),
                4 => $this->getInvoiceStatusHardwords('Order will be sent from factory by DHL EXPRESS'),
                8 => $this->getInvoiceStatusHardwords('PRIORITY 1 URGENT DELIVERY'),
                12 => $this->getInvoiceStatusHardwords('ORDERED TO MAIN STOCK'),
                13 => $this->getInvoiceStatusHardwords('MADE TO MEASURE ORDER'),
                99 => $this->getInvoiceStatusHardwords('Manually Created'),

            );
            $result = preg_grep('~' . $input . '~', $arr);

            if (!empty($result)) {
                $keys = array_keys($result);
                foreach ($keys as $key => $value) {
                    $statusSearch .= '\'' . $value . '\',';

                }

                $statusSearch = rtrim($statusSearch, ',');
                $statusSQL = "`invoice_status` IN({$statusSearch}) OR";
            }

            $search_sql = " ( `invoice_id` LIKE '%{$search}%'               OR
                                        $country
                                        `orderUser` = '{$search}'         OR
                                        `invoice_date` LIKE '%{$search}%' OR
                                        `orderStatus`  LIKE '%{$search}%' OR 
                                        `total_price`  LIKE '%{$search}%' OR
                                        `sender_email`  LIKE '%{$search}%' OR
                                        $statusSQL
                                         ac.acc_name   LIKE '%{$search}%' ) AND";
        } else {
            $search_sql = '';
        }

        //############# GET TOTAL ROWS #############
        $search_w = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';


        ## DATE RANGE SQL
        ## make between sql for date
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' AND " : '';

        switch ($page) {
            case 'data_ajax_complete':
                $order_name = "complete";

                $sql = " SELECT order_info.sender_email,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id
                JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                WHERE {$search_sql} {$between_sql} invoice_status = '3' {$order_by_sql} ";

                $sql_count = "SELECT COUNT(order_info.sender_email) FROM `order_invoice`
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id
                JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                WHERE {$search_sql} {$between_sql} invoice_status = '3' {$order_by_sql}";



                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->count_total_number($sql_count);

                $sql .= " LIMIT $start,$length ";
                break;
            case 'data_ajax_invoices':
                $order_name = "invoices";

                # now added user name searching in all

                $sql = " SELECT order_info.sender_email,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        WHERE {$search_sql} {$between_sql} orderStatus != 'inComplete' AND invoice_status != '3' AND invoice_status != '99' AND invoice_status != '0' {$order_by_sql} ";




                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);
                $sql .= " LIMIT $start,$length ";
                break;
            case 'data_ajax_cancel':
                $order_name = "cancel";

                # doing this, because we are changing the search sql, for cancelled orders.
                $search_sql = trim($search_sql, 'AND');
                $search_sql = (isset($search_sql) && $search_sql != '') ? "  AND {$search_sql} " : '';

                $between_sql = rtrim($between_sql, ' AND ');

                if ($between_sql != '') {
                    $between_sql = ' AND ' . $between_sql;
                }
                $sql = "SELECT order_info.sender_email,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        WHERE `invoice_status` = '0' {$search_sql} {$between_sql} {$order_by_sql} ";

                $sql_count = "SELECT COUNT(order_info.sender_email) FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        WHERE `invoice_status` = '0' {$search_sql} {$between_sql} {$order_by_sql} ";


                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->count_total_number($sql_count);
                $sql .= " LIMIT $start,$length ";
                break;
            case 'data_ajax_incomplete':

                $search_sql1 = " ( `invoice_id` LIKE '%{$search}%'               OR
                                        $country
                                        `orderUser` = '{$search}'         OR
                                        `invoice_date` LIKE '%{$search}%' OR
                                        `orderStatus`  LIKE '%{$search}%' OR 
                                        `total_price`  LIKE '%{$search}%' OR
                                        $statusSQL
                                         ac.acc_name   LIKE '%{$search}%' ) AND";

                $order_name = "incomplete";
                $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id  
                        WHERE {$search_sql1} {$between_sql} orderStatus = 'inComplete' {$order_by_sql} ";


                $sql_count = "SELECT COUNT(ac.acc_email) FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id ";

                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->count_total_number($sql_count);



                $sql .= " LIMIT $start,$length ";
                break;


            case 'data_ajax_inc':

                $order_name = "all";

                # adding between sql with $search_w
                $search_w = ($search_w == '' && $between_sql != '') ? ' WHERE ' . rtrim($between_sql, 'AND ') : $search_w . ' AND ' . rtrim($between_sql, 'AND ');
                # if no between sql then remove the AND which gets appended everytime before between_sql
                $search_w = ($between_sql == '') ? str_replace(' AND ', '', $search_w) : $search_w;

                $sql = "SELECT order_info.sender_email,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        $search_w  {$order_by_sql} ";


                $sql_count = "SELECT COUNT(order_info.sender_email) FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        $search_w {$order_by_sql} ";



                ############# GET TOTAL ROWS #############

                $recordsTotal = $this->count_total_number($sql_count);
                $sql .= " LIMIT $start,$length ";
                break;



            default: //all

                $order_name = "all";

                # adding between sql with $search_w
                $search_w = ($search_w == '' && $between_sql != '') ? ' WHERE ' . rtrim($between_sql, 'AND ') : $search_w . ' AND ' . rtrim($between_sql, 'AND ');
                # if no between sql then remove the AND which gets appended everytime before between_sql
                $search_w = ($between_sql == '') ? str_replace(' AND ', '', $search_w) : $search_w;

                $sql = "SELECT order_info.sender_email,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        $search_w AND invoice_status != '3' AND invoice_status != '0' AND invoice_status != '1' {$order_by_sql} ";


                $sql_count = "SELECT COUNT(order_info.sender_email) FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        $search_w AND invoice_status != '3' AND invoice_status != '0' AND invoice_status != '1' {$order_by_sql} ";



                ############# GET TOTAL ROWS #############

                $recordsTotal = $this->count_total_number($sql_count);


                $sql .= " LIMIT $start,$length ";
                break;
        }

        // ###### Get Data ####
        $data = $this->dbF->getRows($sql);

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
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == 0)
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 1)
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 2)
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 3)
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";


            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];

            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->order_c->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_email = $orderInfo['sender_email'];
            $customer_Name = $orderInfo['sender_name'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }

            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint?mailId=$invoiceId' target='_blank' class='btn'>
                                    <i class='fa fa-file-pdf-o'></i>
                               </a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);
            $cur_symbol = md5($val['price_code']);

            $action = "<div class='btn-group btn-group-sm'>
                       $inoivcePdf
                        <a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a>";
            if ($date < $minusDays) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
                         <i class='glyphicon glyphicon-trash trash'></i>
                         <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                     </a>";
            } else {
                $action .= "<a class='btn'>
                         <i class='glyphicon glyphicon-trash '></i>
                         <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                     </a>";
            }

            if ($val['flagged'] == 1) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return removeFlagOrderToTop(this);'>
                            <i class='glyphicon glyphicon-pushpin'></i>
                        </a>";
            } else {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return flagOrderToTop(this);'>
                            <i class='glyphicon glyphicon-pushpin'></i>
                        </a>";
            }



            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();
            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "id" => $st . '-' . $val['invoice_id'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div qqqqqqq'>{{form}}</div>"
            );
            $invoice_status = $this->functions->print_form($form_invoice, "", false);

            $statusFine = $this->productF->invoiceStatusFind($val['invoice_status']);

            //10 columns
            $count_me = "<span  class='countMe_{$order_name}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]";
            $columns["data"][$key] = array(
                $i,
                "$val[invoice_id]",
                $country,
                $invoiceDate,
                $customer_Name,
                $customer_email,
                $count_me,
                $paymentMethod,
                "$val[inTransaction]",
                $orderProcess,
                $divInvoice . $invoice_status,
                "$val[flagged]",
                $action
            );

        }
        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }

        //Jason Encode
        echo json_encode($columns);
    }
    public function order_fetch_forAllAndCompleted($page)
    {
        global $_e;
        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $length = (isset($_POST['length'])) ? $_POST['length'] : 10;
        $draw = (isset($_POST['draw'])) ? (int) $_POST['draw'] : null;
        $search = (isset($_POST['search']['value']) && $_POST['search']['value'] != '') ? ($_POST['search']['value']) : null;

        $var = explode("-", $search ?? '');

        if ($var[0] == "p" || $var[0] == "P") {
            $search = $var[1];
        }

        $order = (isset($_POST['order'])) ? $_POST['order'][0] : 0;

        $order_by_sql = ' ORDER BY `flagged` DESC, order_invoice_pk DESC ';
        if ($order) {
            # order by sql generation
            $order_by = ($order['column']);
            $order_by_direction = strtoupper($order['dir']);

            switch ($order_by) {
                case '0':
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '1':
                    # INVOICE...
                    $order_by_sql = ' ORDER BY invoice_id ' . $order_by_direction;
                    break;
                case '2':
                    # Country...
                    $order_by_sql = ' ORDER BY shippingCountry ' . $order_by_direction;
                    break;
                case '3':
                    # INVOICE DATE...
                    $order_by_sql = ' ORDER BY invoice_date ' . $order_by_direction;
                    break;
                case '4':
                    # CUSTOMER NAME...
                    $order_by_sql = ' ORDER BY ac.acc_name ' . $order_by_direction;
                    break;
                case '5':
                    # SOLD PRICE...
                    $order_by_sql = ' ORDER BY total_price ' . $order_by_direction;
                    break;
                case '6':
                    # PAYMENT METHOD...
                    $order_by_sql = ' ORDER BY paymentType ' . $order_by_direction;
                    break;
                case '7':
                    # ORDER PROCESS... CANNOT DO THIS CURRENTLY, BECAUSE THIS COMES FROM ORDER_INVOICE_PRODUCT AND CAN BE MULTIPLE
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '8':
                    # Invoice Status...
                    $order_by_sql = ' ORDER BY invoice_status ' . $order_by_direction;
                    break;

                default:
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
            }

        }



        ##### ADDITIONAL CUSTOM FILTER FILEDS #####
        $dateCodeFrom = (isset($_POST['dateCodeFrom']) && $_POST['dateCodeFrom'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeFrom'])->format('Y-m-d') . ' 00:00:00 ' : NULL;

        $dateCodeTo = (isset($_POST['dateCodeTo']) && $_POST['dateCodeTo'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeTo'])->format('Y-m-d') . ' 23:59:59 ' : NULL;
        
        $selectedCountry = (isset($_POST["selectedCountry"]) && $_POST["selectedCountry"] != '') ? $_POST["selectedCountry"] : '';
        
        $selectedInvoice = (isset($_POST["selectedInvoice"]) && $_POST["selectedInvoice"] != '') ? $_POST["selectedInvoice"] : '';
        ## make between sql for date
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' AND " : '';

        ## if date range filter is applied then apply its date order by sql
        ## if order is null, and date range is not empty then use between sql order by , else use order by of the datatable column selected
        $order_by_sql = (!$order && $between_sql != '') ? ' ORDER BY `flagged` DESC, `dateTime` ASC ' : $order_by_sql;


        #### Search SQL #####
        $country = $this->functions->countryKeyByName($search);
        $country = !empty($country) ? " `shippingCountry` = '{$country}' OR " : "";
        $statusSearch = '';
        $statusSQL = '';
        if ($var['0'] == "p" || $var['0'] == "P") {
            $search_sql = "";
            $pSearch = "HAVING `pname`  LIKE '%{$search}%'";
        } elseif ($var[0] != "p" || $var[0] != "P") {
            $input = preg_quote("$search", '~');
            $invst = $this->getInvoiceStatusHardwords('Received');
            $arr = array(
                11 => $this->getInvoiceStatusHardwords('Received'),
                2 => $this->getInvoiceStatusHardwords('pending'),
                5 => $this->getInvoiceStatusHardwords('Ready For Packaging'),
                0 => $this->getInvoiceStatusHardwords('Cancel'),
                9 => $this->getInvoiceStatusHardwords('Partial Delivery Done'),
                6 => $this->getInvoiceStatusHardwords('Full Refunded'),
                10 => $this->getInvoiceStatusHardwords('Awaiting Measures From Customer'),
                7 => $this->getInvoiceStatusHardwords('Order send for factory'),
                3 => $this->getInvoiceStatusHardwords('Complete'),
                1 => $this->getInvoiceStatusHardwords('Denied'),
                4 => $this->getInvoiceStatusHardwords('Order will be sent from factory by DHL EXPRESS'),
                8 => $this->getInvoiceStatusHardwords('PRIORITY 1 URGENT DELIVERY'),
                12 => $this->getInvoiceStatusHardwords('ORDERED TO MAIN STOCK'),
                13 => $this->getInvoiceStatusHardwords('MADE TO MEASURE ORDER'),
                99 => $this->getInvoiceStatusHardwords('Manually Created'),

            );
            $result = preg_grep('~' . $input . '~', $arr);

            if (!empty($result)) {
                $keys = array_keys($result);
                foreach ($keys as $key => $value) {
                    $statusSearch .= '\'' . $value . '\',';

                }

                $statusSearch = rtrim($statusSearch, ',');
                $statusSQL = "`invoice_status` IN({$statusSearch}) OR";
            }

            $search_sql = " (  `invoice_id` LIKE '%{$search}%'               OR
                                        $country
                                        `orderUser` = '{$search}'         OR
                                        `manuall_id` LIKE  '%{$search}%'         OR
                                        `invoice_date` LIKE '%{$search}%' OR
                                        `orderStatus`  LIKE '%{$search}%' OR 
                                        `total_price`  LIKE '%{$search}%' OR
                                        `sender_email`  LIKE '%{$search}%' OR
                                        `sender_address`  LIKE '%{$search}%' OR
                                        `sender_phone`  LIKE '%{$search}%' OR
                                        $statusSQL
                                         ac.acc_name   LIKE '%{$search}%') AND";
            $pSearch = '';

        } else {
            $search_sql = '';
            $pSearch = '';
        }
        
        if ($selectedCountry != '') {
            $search_sql .= " `shippingCountry` = '{$selectedCountry}' AND";
        }
        if ($selectedInvoice != '') {
            $search_sql .= " `invoice_status` = '{$selectedInvoice}' AND";
        }

        //############# GET TOTAL ROWS #############
        $search_sql_all = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';


        ## DATE RANGE SQL
        ## make between sql for date
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' AND " : '';
        switch ($page) {

            case 'data_ajax_forAllAndCompleted_complete':
                $order_name = "complete";

                $sql = "SELECT GROUP_CONCAT(order_product_info.order_pName) as pname, order_info.sender_email,order_info.sender_address,order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id
                JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                 JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                WHERE {$search_sql} {$between_sql} invoice_status = '3'  GROUP by order_info.order_invoice_id $pSearch {$order_by_sql}";
                $sql .= " LIMIT $start,$length ";

                break;

            case 'data_ajax_forAllAndCompleted_manullayCreated':
                $order_name = "manullayCreated";

                $sql = "SELECT GROUP_CONCAT(order_product_info.order_pName) as pname, order_info.sender_email,order_info.sender_address,order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id
                JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                 JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                WHERE {$search_sql} {$between_sql} invoice_status = '99'  GROUP by order_info.order_invoice_id $pSearch {$order_by_sql}";
                $sql .= " LIMIT $start,$length ";
                break;



            default: //all

                $order_name = "all";

                // # adding between sql with $search_sql_all
                $search_sql_all = ($search_sql_all == '' && $between_sql != '') ? ' WHERE ' . rtrim($between_sql, 'AND ') : $search_sql_all . ' AND ' . rtrim($between_sql, 'AND ');
                // # if no between sql then remove the AND which gets appended everytime before between_sql
                $search_sql_all = ($between_sql == '') ? str_replace(' AND ', '', $search_sql_all) : $search_sql_all;



                $sql = "SELECT GROUP_CONCAT( order_product_info.order_pName) as pname, order_info.sender_email, order_info.sender_address, 
                   order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.*, order_invoice.manuall_id FROM `order_invoice` 
                   LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser` LEFT OUTER JOIN `accounts_user` ac 
                   ON ac.acc_id = tau.acc_id JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk` 
                   JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk` 
                   {$search_sql_all} GROUP BY order_info.order_invoice_id $pSearch {$order_by_sql} ";
                $sql .= " LIMIT $start,$length ";
                break;

        }
        $data = $this->dbF->getRows($sql);
        $columns = array();
        if ($draw == 1) {
            $draw - 1;
        }

        $columns["draw"] = $draw + 1;
        $columns["recordsTotal"] = 1200; //total record,
        $columns["recordsFiltered"] = 1200; //filter record, same as total record, then next button will appear

        $i = $start;
        foreach ($data as $key => $val) {
            $i++;
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == 0)
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 1)
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 2)
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 3)
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            $errorBtn = "";
            if (strpos($val['payment_info'], " Error :") !== false) {
                $temp = explode(" Error :", $val['payment_info']);

                $payment_info .= $temp[0];

                $errorBtn = "<div class='btn btn-danger  btn-sm' title = '" . $val['payment_info'] . "'> Error </div><br />";
            }

            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];
            $input = '<input type="checkbox" id="' . $invoiceId . '" value="' . $invoiceId . '" name="order_check" class="order_check">';
            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->order_c->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_email = $orderInfo['sender_email'];
            $customer_Name = $orderInfo['sender_name'];
            $sender_address = $orderInfo['sender_address'];
            $sender_phone = $orderInfo['sender_phone'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }


            //Check order process or not,, if single product process it show 1
            $sqlbtn = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";

            $rem = $this->dbF->getRow($sqlbtn);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {


                $pIds = explode("-", $rem['order_pIds']);
                $order_pName = $rem['order_pName'];

                $eX = explode(" - ", $order_pName, 2);
                $s = @$eX[1];

                $pQty = $rem['order_pQty'];



                //make sure all order process or custome process
                $sqlbtn = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sqlbtn);
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            } else {

                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";

                $rem = $this->dbF->getRow($sql);


                $pIds = explode("-", $rem['order_pIds']);



                $order_pName = $rem['order_pName'];

                $eX = explode(" - ", $order_pName, 2);
                $s = @$eX[1];

                $pQty = $rem['order_pQty'];

            }

            $empt = "";







            $pIds1 = filter_var(
                $pIds[0],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            @$scaleIds = filter_var(
                $pIds[1],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            @$colorIds = filter_var(
                $pIds[2],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            @$storeIds = filter_var(
                $pIds[3],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            @$customIds = filter_var(
                $pIds[4],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
            $pIds1 = $pIds1; // 491







            @$hashVals = $pIds1 . ":" . $scaleIds . ":" . $colorIds . ":" . $storeIds;
            $hashs = md5($hashVals);


            $sqlcc = "SELECT * FROM `product_inventory` WHERE `product_store_hash` = '$hashs' AND `qty_item` != '0' and `qty_item` >= '$pQty'";
            $this->dbF->getRow($sqlcc);
            if ($this->dbF->rowCount == 0) {
            } else {

                $empt = "<br><span data-tooltip='Yes, item exists in inventory.' data-tooltip-position='bottom' title='Yes, item exists in inventory.' style='font-size:13px; color: blue;'>&#10004;  
    
     </span>";

            }





            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint?mailId=$invoiceId' target='_blank' class='btn'>
                                    <i class='fa fa-file-pdf-o'></i>
                               </a>";
            }

            $paymentMethod = $val['paymentType'];
            @$paymentMethod = $this->productF->paymentArrayFind($paymentMethod);
            $cur_symbol = md5($val['price_code']);

            $action = "<div class='btn-group btn-group-sm'>
                       $inoivcePdf
                        <a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a>";
            if ($date < $minusDays) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
                         <i class='glyphicon glyphicon-trash trash'></i>
                         <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                     </a>";
            } else {
                $action .= "<a class='btn'>
                         <i class='glyphicon glyphicon-trash '></i>
                         <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                     </a>";
            }

            if ($val['flagged'] == 1) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return removeFlagOrderToTop(this);'>
                            <i class='glyphicon glyphicon-pushpin'></i>
                        </a>";
            } else {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return flagOrderToTop(this);'>
                            <i class='glyphicon glyphicon-pushpin'></i>
                        </a>";
            }



            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();



            $sqlQ = "SELECT * FROM  order_invoice_product WHERE order_invoice_id='$invoiceId'";
            $pdataQ = $this->dbF->getRows($sqlQ);
            foreach ($pdataQ as $p) {
                @$info = unserialize($p['info']);
                if (empty($info)) {
                    $pName = $p['order_pName'];
                    $order_pIds = $p['order_pIds'];
                    $pArray = explode("-", $order_pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                    $pId = $pArray[0]; // 491
                    @$scaleId = $scaleId1 = $pArray[1]; // 426
                    $order_pQty = $p['order_pQty'];
                    $order_pName = $p['order_pName'];
                    $eX = explode(" - ", $order_pName, 2);
                    $s = @$eX[1];
                    $sp = "SELECT prosiz_name  FROM `product_size` WHERE `prosiz_id` = '$scaleId' ";
                    $spData = $this->dbF->getRow($sp);
                    if ($spData === false || empty($spData)) {
                        $pids = explode(' - ', $p['order_pName']);
                        $spData = [];
                        $spData['prosiz_name'] = end($pids);
                    }
                    // if(empty($spData)){
                    //         $pids = explode(' - ',$p['order_pName']);
                    //         $spData['prosiz_name'] = $pids[count($pids) -1 ];
                    // }

                    $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' and `prosiz_cur_id` = 20 and `prosiz_name`='$spData[prosiz_name]' ";
                    $spData = $this->dbF->getRow($sp);
                    if ($this->dbF->rowCount > 0) {
                        $scaleId = $spData['prosiz_id'];

                    } else {
                        $scaleId = $scaleId;
                    }



                    @$hashVal = $pId . ":" . $scaleId . ":0:6";
                    $hash = md5($hashVal);
                    $sqlqty_item = "SELECT qty_item FROM `product_inventory` WHERE `product_store_hash` = '$hash'";
                    $qty_itemData = $this->dbF->getRow($sqlqty_item);

                    if (is_array($qty_itemData) && isset($qty_itemData['qty_item'])) {
                        $ded = $qty_itemData['qty_item'];
                    } else {
                        $ded = "";
                    }

                    $order_process__ = $p['order_process'];
                    $style = "";
                    // 0 => cancel 1 denied 6 refund
                    if (($st == '0' || $st == '1' || $st == '6' || $st == '3') || $order_process__ != "0") {
                        $style = "style=display:none";
                    }

                    if ($order_process__ == "1") {
                        $pName = "<div class='deduct_products'>
            <div class='pro_name'>" . $pName . "</div>
            <div class='pro_qty'>Order Qty: " . $order_pQty . "</div>
            <div class='pro_name'>Stock Qty: (" . $ded . ") </div>
      </div>
      ";
                    } else {
                        $pName = "<div class='deduct_products'>
            <div class='pro_name'>" . $pName . "</div>
            <div class='pro_qty'>Order Qty: " . $order_pQty . "</div>
            <div class='pro_name'>Stock Qty: (" . $ded . ") </div>
            <div class='pro_ded_text' " . $style . ">add/deduct inventory?</div>
      </div>
      <div >
      <label>
      {{form}} <span class='yes_btn'>Yes<span>
      <label>
      </div>
      ";

                    }



                    if (($st != '0' && $st != '1' && $st != '6' && $st != '3')) {
                        $form_invoice[] = array(
                            "class" => "form-control",
                            "type" => "checkbox",
                            "required" => "true",
                            "name" => "dStock[]",
                            'value' => $hash,
                            'format' => "<div class='invoice_quick_select_div deleteStatus 1'>$pName</div>"
                        );
                    }
                }
            }





            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "id" => $st . '-' . $val['invoice_id'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div wwwww'>{{form}}</div>"
            );



            $invForLable = @explode("_", $val['manuall_id'])[1];
            $invForLable1 = (isset($invForLable) && !empty($invForLable)) ? " ($invForLable) " : "";


            $invoice_status = $this->functions->print_form($form_invoice, "", false);

            $statusFine = $this->productF->invoiceStatusFind($val['invoice_status']);

            //10 columns
            $count_me = "<span  class='countMe_{$order_name}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]";
            $columns["data"][$key] = array(
                $input,
                $i,
                "$val[invoice_id] $invForLable1",
                "$val[pname] $empt",
                $sender_address,
                $country,
                $sender_phone,
                $invoiceDate,
                $customer_Name,
                $customer_email,
                $count_me,
                $paymentMethod,
                "$val[inTransaction]",
                "$val[trackNo]",
                $errorBtn . $orderProcess,
                $divInvoice . $invoice_status,
                "$val[flagged]",
                $action
            );

        }
        if (empty($data)) {
            $columns["data"] = array();
        }

        echo json_encode($columns);

    }
    public function order_fetch($page)
    {
        global $_e;
        global $sharkOnlineConnection;
        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $length = (isset($_POST['length'])) ? $_POST['length'] : 10;
        $draw = (isset($_POST['draw'])) ? (int) $_POST['draw'] : null;
        $search = (isset($_POST['search']['value']) && $_POST['search']['value'] != '') ? ($_POST['search']['value']) : null;
        $var = explode("-", $search ?? '');

        if ($var[0] == "p" || $var[0] == "P") {
            $search = $var[1];
        }
        $order = (isset($_POST['order'])) ? $_POST['order'][0] : 0;
        $order_by_sql = ' ORDER BY `flagged` DESC, order_invoice_pk DESC ';

        if ($order) {
            # order by sql generation
            $order_by = ($order['column']);
            $order_by_direction = strtoupper($order['dir']);

            switch ($order_by) {
                case '0':
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '1':
                    # INVOICE...
                    $order_by_sql = ' ORDER BY invoice_id ' . $order_by_direction;
                    break;
                case '2':
                    # Country...
                    $order_by_sql = ' ORDER BY shippingCountry ' . $order_by_direction;
                    break;
                case '3':
                    # INVOICE DATE...
                    $order_by_sql = ' ORDER BY invoice_date ' . $order_by_direction;
                    break;
                case '4':
                    # CUSTOMER NAME...
                    $order_by_sql = ' ORDER BY ac.acc_name ' . $order_by_direction;
                    break;
                case '5':
                    # SOLD PRICE...
                    $order_by_sql = ' ORDER BY total_price ' . $order_by_direction;
                    break;
                case '6':
                    # PAYMENT METHOD...
                    $order_by_sql = ' ORDER BY paymentType ' . $order_by_direction;
                    break;
                case '7':
                    # ORDER PROCESS... CANNOT DO THIS CURRENTLY, BECAUSE THIS COMES FROM ORDER_INVOICE_PRODUCT AND CAN BE MULTIPLE
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '8':
                    # Invoice Status...
                    $order_by_sql = ' ORDER BY invoice_status ' . $order_by_direction;
                    break;

                default:
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
            }
        }



        ##### ADDITIONAL CUSTOM FILTER FILEDS #####
        $dateCodeFrom = (isset($_POST['dateCodeFrom']) && $_POST['dateCodeFrom'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeFrom'])->format('Y-m-d') . ' 00:00:00 ' : NULL;

        $dateCodeTo = (isset($_POST['dateCodeTo']) && $_POST['dateCodeTo'] != '') ? DateTime::createFromFormat('Y-m-d', $_POST['dateCodeTo'])->format('Y-m-d') . ' 23:59:59 ' : NULL;
        
        $selectedCountry = (isset($_POST["selectedCountry"]) && $_POST["selectedCountry"] != '') ? $_POST["selectedCountry"] : '';
        
        $selectedInvoice = (isset($_POST["selectedInvoice"]) && $_POST["selectedInvoice"] != '') ? $_POST["selectedInvoice"] : '';
        
        ## make between sql for date
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' AND " : '';
        ## if date range filter is applied then apply its date order by sql
        ## if order is null, and date range is not empty then use between sql order by , else use order by of the datatable column selected
        $order_by_sql = (!$order && $between_sql != '') ? ' ORDER BY `flagged` DESC, `dateTime` ASC ' : $order_by_sql;


        #### Search SQL #####
        $country = $this->functions->countryKeyByName($search);
        $country = !empty($country) ? " `shippingCountry` = '{$country}' OR " : "";
        $statusSearch = '';
        $statusSQL = '';

        if ($var['0'] == "p" || $var['0'] == "P") {
            $search_sql = "";
            $pSearch = "HAVING `pname`  LIKE '%{$search}%'";
        } elseif ($var[0] != "p" || $var[0] != "P") {
            $input = preg_quote("$search", '~');
            $invst = $this->getInvoiceStatusHardwords('Received');
            $arr = array(
                11 => $this->getInvoiceStatusHardwords('Received'),
                2 => $this->getInvoiceStatusHardwords('pending'),
                5 => $this->getInvoiceStatusHardwords('Ready For Packaging'),
                0 => $this->getInvoiceStatusHardwords('Cancel'),
                9 => $this->getInvoiceStatusHardwords('Partial Delivery Done'),
                6 => $this->getInvoiceStatusHardwords('Full Refunded'),
                10 => $this->getInvoiceStatusHardwords('Awaiting Measures From Customer'),
                7 => $this->getInvoiceStatusHardwords('Order send for factory'),
                3 => $this->getInvoiceStatusHardwords('Complete'),
                1 => $this->getInvoiceStatusHardwords('Denied'),
                4 => $this->getInvoiceStatusHardwords('Order will be sent from factory by DHL EXPRESS'),
                8 => $this->getInvoiceStatusHardwords('PRIORITY 1 URGENT DELIVERY'),
                12 => $this->getInvoiceStatusHardwords('ORDERED TO MAIN STOCK'),
                13 => $this->getInvoiceStatusHardwords('MADE TO MEASURE ORDER'),
                99 => $this->getInvoiceStatusHardwords('Manually Created'),
            );
            $result = preg_grep('~' . $input . '~', $arr);

            if (!empty($result)) {
                $keys = array_keys($result);
                foreach ($keys as $key => $value) {
                    $statusSearch .= '\'' . $value . '\',';

                }

                $statusSearch = rtrim($statusSearch, ',');
                $statusSQL = "`invoice_status` IN({$statusSearch}) OR";
            }

            $search_sql = " (`invoice_id` LIKE '%{$search}%' OR $country 
            `orderUser` = '{$search}' OR `invoice_date` LIKE '%{$search}%' OR 
            `orderStatus`  LIKE '%{$search}%' OR `total_price`  LIKE '%{$search}%' OR 
            `sender_email`  LIKE '%{$search}%' OR `sender_address`  LIKE '%{$search}%' OR 
            `sender_phone`  LIKE '%{$search}%' OR $statusSQL ac.acc_name   LIKE '%{$search}%') AND";
            $pSearch = '';
        } else {
            $search_sql = '';
            $pSearch = '';
        }
        if ($selectedCountry != '') {
            $search_sql .= " `shippingCountry` = '{$selectedCountry}' AND";
        }
        if ($selectedInvoice != '') {
            $search_sql .= " `invoice_status` = '{$selectedInvoice}' AND";
        }
        //############# GET TOTAL ROWS #############
        $search_sql_all = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';


        ## DATE RANGE SQL
        ## make between sql for date
        $between_sql = (isset($dateCodeFrom) && isset($dateCodeTo)) ? " `invoice_date` BETWEEN '{$dateCodeFrom}' AND '{$dateCodeTo}' AND " : '';

        switch ($page) {
            case 'data_ajax_complete':
                $order_name = "complete";
                $sql = "SELECT GROUP_CONCAT(order_product_info.order_pName) as pname, order_info.sender_email,order_info.sender_address,order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id
                JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                 JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                WHERE {$search_sql} {$between_sql} invoice_status = '3' GROUP by order_info.order_invoice_id $pSearch {$order_by_sql}";

                $sql .= " LIMIT $start,$length ";

                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);

                break;
            case 'data_ajax_invoices':
                $order_name = "invoices";

                # now added user name searching in all
                // # specific search sql, adding user name searching by joining user account table

                $sql = "SELECT GROUP_CONCAT(order_product_info.order_pName SEPARATOR '###') as pname,order_info.sender_email,order_info.sender_address,order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk` 
                        JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk` 
                        WHERE {$search_sql} {$between_sql} orderStatus != 'inComplete' AND invoice_status != '3' AND invoice_status != '99' AND invoice_status != '0' AND invoice_status != '1' GROUP by order_info.order_invoice_id $pSearch {$order_by_sql} ";


                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);
                $sql .= " LIMIT $start,$length ";

                break;
            case 'data_ajax_cancel':
                $order_name = "cancel";

                # doing this, because we are changing the search sql, for cancelled orders.
                $search_sql = trim($search_sql, 'AND');
                $search_sql = (isset($search_sql) && $search_sql != '') ? "  AND {$search_sql} " : '';

                $between_sql = rtrim($between_sql, ' AND ');

                if ($between_sql != '') {
                    $between_sql = ' AND ' . $between_sql;
                }
                $sql = "SELECT GROUP_CONCAT( order_product_info.order_pName) as pname,order_info.sender_email,order_info.sender_address,order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk` 
                        JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk` 
                        WHERE `invoice_status` = '0' {$search_sql} {$between_sql} GROUP by order_info.order_invoice_id $pSearch {$order_by_sql} ";
                
                $count_sql = "SELECT COUNT(DISTINCT order_invoice.order_invoice_pk) as total_count
                  FROM `order_invoice`
                  LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                  LEFT OUTER JOIN `accounts_user` ac ON ac.acc_id = tau.acc_id
                  JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                  JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                  WHERE `invoice_status` = '0' {$search_sql} {$between_sql} {$pSearch}";
                $countData = $this->dbF->getRow($count_sql);

                ############# GET TOTAL ROWS #############
                $recordsTotal = $countData["total_count"];
                $sql .= " LIMIT $start,$length ";

                break;
            case 'data_ajax_incomplete':
                $search_sql1 = " (`invoice_id` LIKE '%{$search}%' OR $country 
                `orderUser` = '{$search}' OR `invoice_date` LIKE '%{$search}%' OR 
                `orderStatus`  LIKE '%{$search}%' OR `total_price`  LIKE '%{$search}%' OR 
                $statusSQL ac.acc_name   LIKE '%{$search}%' ) AND";
                if ($selectedCountry != '') {
                    $search_sql1 .= " `shippingCountry` = '{$selectedCountry}' AND";
                }
                if ($selectedInvoice != '') {
                    $search_sql1 .= " `invoice_status` = '{$selectedInvoice}' AND";
                }
                $order_name = "incomplete";
                $sql = "SELECT GROUP_CONCAT( order_product_info.order_pName) as pname,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id  
                         JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        WHERE {$search_sql1} {$between_sql} orderStatus = 'inComplete' GROUP BY order_product_info.order_invoice_id $pSearch {$order_by_sql} ";

                $count_sql = "SELECT COUNT(DISTINCT order_invoice.order_invoice_pk) as total_count FROM `order_invoice` 
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser` LEFT OUTER JOIN 
                `accounts_user` ac ON ac.acc_id = tau.acc_id JOIN `order_invoice_product` order_product_info ON 
                order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk` WHERE {$search_sql1} {$between_sql} orderStatus = 'inComplete' {$pSearch}";
                $countData = $this->dbF->getRow($count_sql);

                ############# GET TOTAL ROWS #############
                $recordsTotal = $countData["total_count"];

                $sql .= " LIMIT $start,$length ";
                break;
            default: //all
                $order_name = "all";
                // # adding between sql with $search_sql_all
                $search_sql_all = ($search_sql_all == '' && $between_sql != '') ? ' WHERE ' . rtrim($between_sql, 'AND ') : $search_sql_all . ' AND ' . rtrim($between_sql, 'AND ');
                // # if no between sql then remove the AND which gets appended everytime before between_sql
                $search_sql_all = ($between_sql == '') ? str_replace(' AND ', '', $search_sql_all) : $search_sql_all;

                $sql = "SELECT GROUP_CONCAT( order_product_info.order_pName) as pname, order_info.sender_email,order_info.sender_address,order_info.sender_phone,ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        JOIN `order_invoice_info` order_info ON order_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                          JOIN `order_invoice_product` order_product_info ON order_product_info.order_invoice_id = `order_invoice`.`order_invoice_pk`
                        {$search_sql_all} GROUP BY order_info.order_invoice_id $pSearch {$order_by_sql} ";

                $sql .= " LIMIT $start,$length ";

                ############# GET TOTAL ROWS #############

                $recordsTotal = $this->get_total_rows($sql);
                break;
        }
        
        $data = $this->dbF->getRows($sql);
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
            $divInvoice = '';
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == 0)
                $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 1)
                $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 2)
                $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 3)
                $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else
                $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            $errorBtn = "";

            if (isset($val['payment_info']) && is_string($val['payment_info']) && strpos($val['payment_info'], " Error :") !== false) {
                $temp = explode(" Error :", $val['payment_info']);

                $payment_info .= $temp[0];
                $errorBtn = "<div class='btn btn-danger btn-sm' title='" . $val['payment_info'] . "'> Error </div>";
            }

            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];
            $input = '<label class="uniqueLabel" for="' . $invoiceId . '"><input type="checkbox" id="' . $invoiceId . '" value="' . $invoiceId . '" name="order_check" class="order_check"></label>';

            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->order_c->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_email = isset($orderInfo['sender_email']) ? $orderInfo['sender_email'] : '';
            $customer_Name = isset($orderInfo['sender_name']) ? preg_replace("/[^a-zA-Z0-9\s]/", "", $orderInfo['sender_name']) : '';
            $sender_address = isset($orderInfo['sender_address']) ? preg_replace("/[^a-zA-Z0-9\s]/", "", $orderInfo['sender_address']) : '';
            $sender_country = isset($orderInfo['sender_country']) ? $orderInfo['sender_country'] : '';
            $sender_phone = isset($orderInfo['sender_phone']) ? $orderInfo['sender_phone'] : '';
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }


            //Check order process or not,, if single product process it show 1
            $sqlbtn = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";

            $rem = $this->dbF->getRow($sqlbtn);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                $pIds = explode("-", $rem['order_pIds']);
                $order_pName = $rem['order_pName'];
                $eX = explode(" - ", $order_pName, 2);
                $s = @$eX[1];
                $pQty = $rem['order_pQty'];

                //make sure all order process or custome process
                $sqlbtn = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sqlbtn);
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            } else {

                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";

                $rem = $this->dbF->getRow($sql);


                $pIds = explode("-", $rem['order_pIds']);


                $order_pName = $rem['order_pName'];

                $eX = explode(" - ", $order_pName, 2);
                $s = @$eX[1];

                $pQty = $rem['order_pQty'];

            }

            $empt = "";


            $pIds1 = filter_var(
                $pIds[0],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            $scaleIds = filter_var(
                $pIds[1],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            $colorIds = filter_var(
                $pIds[2],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            $storeIds = filter_var(
                $pIds[3],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            @$customIds = filter_var(
                $pIds[4],
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
            $pIds1 = $pIds1;

            @$hashVals = $pIds1 . ":" . $scaleIds . ":" . $colorIds . ":" . $storeIds;
            $hashs = md5($hashVals);


            $sqlcc = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = '$s' AND `qty_product_id` = '$pIds1' AND `qty_item` != '0' AND `qty_item` >= '$pQty'";
            $this->dbF->getRow($sqlcc);
            if ($this->dbF->rowCount == 0) {
            } else {
                $empt = "<br><span data-tooltip='Yes, item exists in inventory.' data-tooltip-position='bottom' title='Yes, item exists in inventory.' style='font-size:13px; color: blue;'>&#10004;</span>";
            }

            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint?mailId=$invoiceId' target='_blank' class='btn'>
                                    <i class='fa fa-file-pdf-o'></i>
                               </a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);
            $cur_symbol = md5($val['price_code']);

            $action = "<div class='btn-group btn-group-sm'>
                       $inoivcePdf
                        <a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a>";
            if ($date < $minusDays) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
                         <i class='glyphicon glyphicon-trash trash'></i>
                         <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                     </a>";
            } else {
                $action .= "<a class='btn'>
                         <i class='glyphicon glyphicon-trash '></i>
                         <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                     </a>";
            }

            if ($val['flagged'] == 1) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return removeFlagOrderToTop(this);'>
                            <i class='glyphicon glyphicon-pushpin'></i>
                        </a>";
            } else {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return flagOrderToTop(this);'>
                            <i class='glyphicon glyphicon-pushpin'></i>
                        </a>";
            }
            $action .= "<a class='btn' data-id='$invoiceId' onclick='return printShippingLabel(\"$sender_country\", \"$invoiceId\");'>
                            <i class='glyphicon glyphicon-print'></i>
                        </a>";

            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();

            $sqlQ = "SELECT * FROM  order_invoice_product WHERE order_invoice_id='$invoiceId'";
            $pdataQ = $this->dbF->getRows($sqlQ);
            foreach ($pdataQ as $p) {
                @$info = unserialize($p['info']);
                if (empty($info)) {
                    $pName = $p['order_pName'];
                    $order_pIds = $p['order_pIds'];
                    $pArray = explode("-", $order_pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                    $pId = $pArray[0]; // 491
                    $scaleId = $scaleId1 = $pArray[1]; // 426
                    $order_pQty = $p['order_pQty'];
                    $order_pName = $p['order_pName'];
                    $eX = explode(" - ", $order_pName, 2);
                    $s = @$eX[1];

                    $sp = "SELECT prosiz_name  FROM `product_size` WHERE `prosiz_id` = '$scaleId' ";
                    $spData = $this->dbF->getRow($sp);

                    if ($spData === false || empty($spData)) {
                        $pids = explode(' - ', $p['order_pName']);
                        $spData = [];
                        $spData['prosiz_name'] = end($pids);
                    }

                    $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' AND `prosiz_cur_id` = 20 AND `prosiz_name`='$spData[prosiz_name]' ";
                    $spData = $this->dbF->getRow($sp);
                    if ($this->dbF->rowCount > 0) {
                        $scaleId = $spData['prosiz_id'];

                    } else {
                        $scaleId = $scaleId;
                    }



                    @$hashVal = $pId . ":" . $scaleId . ":0:6";
                    $hash = md5($hashVal);

                    $sqlqty_item = "SELECT `qty_item` FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ?";
                    $qty_itemData = $this->dbF->getRow($sqlqty_item, [$spData['prosiz_name'], $pId]);

                    if (is_array($qty_itemData) && isset($qty_itemData['qty_item'])) {
                        $ded = $qty_itemData['qty_item'];
                    } else {
                        $ded = "";
                    }

                    $order_process__ = $p['order_process'];
                    $checked = '';
                    if ($qty_itemData !== false && $ded >= $order_pQty && $order_process__ == "0") {
                        $checked = 'checked';
                    }


                    
                    $style = "";
                    // 0 => cancel 1 denied 6 refund
                    if (($st == '0' || $st == '1' || $st == '6' || $st == '3') || $order_process__ != "0") {
                        $style = "style=display:none";
                    }


                    $order_process__ = $p['order_process'];
                    $style = "";
                    // 0 => cancel 1 denied 6 refund
                    if (($st == '0' || $st == '1' || $st == '6' || $st == '3') || $order_process__ != "0") {
                        $style = "style=display:none";
                    }

                    if ($order_process__ == "1") {
                        $pName = "<div class='deduct_products'>
                                <div class='pro_name'>" . $pName . "</div>
                                <div class='pro_qty'>Order Qty: " . $order_pQty . "</div>
                                <div class='pro_name'>Stock Qty: (" . $ded . ") </div>
                          </div>
                          ";
                    } else {
                        $pName = "<div class='deduct_products'>
                                <div class='pro_name'>" . $pName . "</div>
                                <div class='pro_qty'>Order Qty: " . $order_pQty . "</div>
                                <div class='pro_name'>Stock Qty: (" . $ded . ") </div>
                                <div class='pro_ded_text' " . $style . ">add/deduct inventory?</div>
                          </div>
                          <div >
                          <label>
                          {{form}} <span class='yes_btn'>Yes<span>
                          <label>
                          </div>
                          ";

                    }





                    if (($st != '0' && $st != '1' && $st != '6' && $st != '3')) {

                        $form_invoice[] = array(
                            "class" => "form-control",
                            "type" => "checkbox",
                            "required" => "true",
                            "name" => "dStock[]",
                            'value' => $hash,
                            'format' => "<div class='invoice_quick_select_div deleteStatus 3'>$pName</div>"
                        );
                    }


                }
            }




            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "id" => $st . '-' . $val['invoice_id'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div wwwww'>{{form}}</div>"
            );

            $products = explode('###', $val['pname']);
            $productButtons = '';
            
            $sqlNew = "SELECT * FROM order_invoice_product WHERE order_invoice_id = ?";
            $datInvoiceProducts = $this->dbF->getRows($sqlNew, [$val['order_invoice_pk']]);
            
            $invoiceProductIds = [];
            foreach($datInvoiceProducts as $datInvoiceProduct){
                $invoiceProductIds[] = $datInvoiceProduct["invoice_product_pk"];
            }

            foreach ($products as $index => $product) {
                $selectBox = "";
                $invoice_product_id = $invoiceProductIds[$index] ?? null;
                $sqlcC = "SELECT * FROM `accounts` WHERE `acc_role` = '7' and acc_type = '1'";
                $supplierData = $sharkOnlineConnection->getRows($sqlcC);
                if ($sharkOnlineConnection->rowCount > 0) {
                    foreach ($supplierData as $valZ) {
                        $acc_name = $valZ['acc_name'];
                        $acc_email = $valZ['acc_email'];
                        $acc_id = $valZ['acc_id'];
                        $selectBox .= "<option value='$acc_id' data-id='$invoice_product_id - $val[invoice_id]'>$acc_name - $acc_email</option>";
                    }
                }
                
                $sqlcCNew = "SELECT opID,ass_id FROM `history` WHERE `opID` = '$invoice_product_id'";
                $supplierData2 = $sharkOnlineConnection->getRow($sqlcCNew);
                if($rem["shipped"] == 1){
                    $shipped = "<span style='display: block; font-weight: bold; color: rgba(2, 194, 122, 1); background: #d1f5e5; border-radius: 0.375rem; padding: 4px;'>Shipped</span>";
                }else{
                    $shipped = "";
                }
                if ($sharkOnlineConnection->rowCount > 0) {
                    $sqlcc2 = "SELECT acc_name,acc_email FROM accounts WHERE `acc_id` = '$supplierData2[ass_id]'";
                    $datavs = $sharkOnlineConnection->getRow($sqlcc2);
                    $snname = $datavs['acc_name'] . ' - ' . $datavs['acc_email'];

                    $productButtons .= "$product $shipped <div class='shippersInfo'><span>Assigned To $snname</span></div>";
                }else{
                    $productButtons .= "$product $shipped <div class='shippersInfo'><button type='button' class='btn btn-info btn-sm showShippers'>Assign To Shipper</button>";
                    $productButtons .= "<select class='shipperSelect form-control' style='display: none; width: 100%;'>
                                    <option value=''>select supplier</option>
                                    $selectBox
                                    </select></div>";
                }
                
            }

            $invoice_status = $this->functions->print_form($form_invoice, "", false);

            $statusFine = $this->productF->invoiceStatusFind($val['invoice_status']);

            //10 columns
            $count_me = "<span  class='countMe_{$order_name}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]";
            $columns["data"][$key] = array(
                $input,
                $i,
                "$val[invoice_id]",
                "$productButtons $empt",
                $sender_address,
                $country,
                $sender_phone,
                $invoiceDate,
                $customer_Name,
                $customer_email,
                $count_me,
                $paymentMethod,
                "$val[inTransaction]",
                $errorBtn . $orderProcess,
                $divInvoice . $invoice_status,
                "$val[flagged]",
                $action
            );

        }
        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }

        //Jason Encode
        echo json_encode($columns);

    }

    protected function count_total_number($sql_count)
    {
        $count = $this->dbF->getRow($sql_count);
        return $count;
    }
    protected function get_total_rows($sql, $search_sql = '')
    {
        $search_w = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';
        $sql = $sql . ' ' . $search_w;
        $data = $this->dbF->getRows($sql);
        return $recordsTotal = $this->dbF->rowCount;
    }

    public function bestSellerNewsletter()
    {

        $sql = "SELECT pid FROM `order_product_info` where order_date >= DATE(NOW()) - INTERVAL 30 DAY GROUP BY pid ORDER BY `order_product_info`.`order_date` DESC";

        $res = $this->dbF->getRows($sql);


        $temp = '<style type="text/css">

/*pop side*/

/*pop side*/

.pop_side {
.pop_content {
position: relative;
width: 45%;
padding-top: 20px;
text-align: center;
display: inline-block;
vertical-align: top;
margin-right: 2%;
}
}

.pop_side_top {
position: relative;
width: 100%;
border-bottom: 1px solid #e1e2e4;
padding-bottom: 15px;
}

.pop_side_top i {
font-size: 25px;
color: #42474b;
display: inline-block;
vertical-align: middle;
margin-right: 10px;
}

.number_side {
position: absolute;
right: 0px;
top: 0px;
font-size: 25px;
color: #42474b;
}

.number_side span {
font-size: 34px;
color: #42474b;
font-family: "ubuntubold";
display: inline-block;
vertical-align: top;
}

.pop_content {
position: relative;
width: 45%;
display: inline-block;
vetical-align: top;
margin-right: 2%;
text-align: center;
}

.pop_content:nth-child(even) {
margin-right: 0%;
}

.pop_img {
position: relative;
display: block;
width: 35%;
margin: 0 auto;
}

.pop_img img {
width: 100%;
}

.pop_slide {
position: relative;
width: 100%;
}

.pop_img1 {
position: relative;
width: 100%;
}

.pop_content_main {
display: inline-block;
vertical-align: middle;
width: 55%;
text-align: center;
margin: 15px 0px;
}

.pop_content_main_btn {
position: relative;
width: 215px;
text-align: center;
background: #42474b;
box-shadow: 5px 5px 0px #a2a6af;
border-radius: 5px 5px;
margin: 0 auto;
transition: .7s;
margin-bottom: 20px;
}

.pop_content_main_btn a {
display: block;
color: #ffffff;
font-size: 16px;
padding: 10px 0px;
}

.pop_content_main_btn:hover {
background: #7ebc41;
}

.selection_side {
position: relative;
width: 100%;
color: #42474b;
font-size: 16px;
}

.pop_content_main .select_box4 {
width: 215px;
}

.pop_content_main .select_box4 .dropdown_select dt a {
height: 42px !important;
line-height: 42px !important;
border: 1px solid #6b6f72 !important;
box-shadow: 3px 3px 0px #363b3f;
border-radius: 2px 2px;
background: #ffffff url(../webImages/arrow.png) no-repeat scroll right center;
}

.pop_price {
position: relative;
width: 100%;
color: #eb333d;
font-size: 30px;
text-align: center;
font-weight: bold;
}

.pop_price span {
color: #a0a9b0;
margin: 0px 5px;
display: block;
font-style: normal;
font-size: 16px;
text-decoration: line-through;
font-weight: normal;
font-family: sans-serif;
}

.button_side {
position: relative;
display: inline-block;
vertical-align: middle;
width: 100%;
margin-top: 5px;
}

.button_side1 {
position: relative;
display: inline-block;
vertical-align: top;
width: 45%;
background: #42474b;
padding: 10px 0px;
border-radius: 2px 2px;
text-align: center;
margin-right: 2%;
}

.button_side1 a {
display: block;
color: #ffffff;
font-size: 16px;
}

.button_side2 {
position: relative;
display: inline-block;
vertical-align: top;
width: 60%;
background: #7ebc41;
padding: 10px 0px;
border-radius: 2px 2px;
text-align: center;
}

.button_side2 a {
display: block;
color: #ffffff;
font-size: 16px;
}

.pop_btn {
position: absolute;
left: 0px;
top: 50%;
width: 100%;
z-index: 1;
}

.pop_btn1 {
position: absolute;
left: 1%;
width: 16px;
height: 28px;
background: url(../webImages/news_left1.png);
cursor: pointer;
}

.pop_btn2 {
position: absolute;
right: 1%;
width: 16px;
height: 28px;
background: url(../webImages/news_right1.png);
cursor: pointer;
}

.pop_close {
position: absolute;
right: 10px;
top: 10px;
cursor: pointer;
font-size: 25px;
}


/*pop side*/
</style>

<br><br>
';


        foreach ($res as $pId) {


            $id = $pId['pid'];


            $pLink = WEB_URL . '/detail.php?pId=' . $id;

            $name = $this->productF->getProductName($id);
            $img = $this->productF->productSpecialImage($id, 'main');
            $img = $this->functions->resizeImage($img, 'auto', 160, false);
            $price = $this->productF->productPrice($id);
            $currencyId = $price['propri_cur_id'];
            $symbol = $this->productF->currencySymbol($currencyId);
            $priceP = $price['propri_price'];

            $discount = $this->productF->productDiscount($id, $currencyId);
            @$discountFormat = $discount['discountFormat'];
            @$discountP = $discount['discount'];

            $discountPrice = $this->productF->discountPriceCalculation($priceP, $discount);

            $newPrice = $discountPrice;

            $priceP .= ' ' . $symbol;
            $newPrice .= ' ' . $symbol;

            if ($newPrice != $priceP) {
                $hasDiscount = true;
                $oldPriceDiv = '<span class="oldPrice">' . $priceP . '</span>';
                $newPriceDiv = $newPrice;
            } else {
                $oldPriceDiv = "";
                $newPriceDiv = $priceP;
            }

            $buyToT = $this->dbF->hardWords('Buy To', false);

            $temp .= "<div class='pop_content'>
<div class='pop_img'>
<div class='pop_img1'><img alt='' src='$img' loading='lazy'/></div>
<!-- pop_img1 close --></div>
<!-- pop_img close -->

<div class='pop_content_main'>
<div class='selection_side'>$name</div>
<!-- selection_side close -->

<div class='pop_price'>
$newPriceDiv $oldPriceDiv
</div>
<!-- pop_price close -->

<div class='button_side'><!-- <div class='button_side1'><a href='" . $pLink . "'>No thanks !</a></div> --><!-- button_side1 close -->
<div class='button_side2'><a href='" . $pLink . "'>" . $buyToT . "</a></div>
<!-- button_side1 close --></div>
<!-- button_side close --></div>
<!-- pop_content_main close --></div>";

        }


        return $temp;
    }

    public function quick_invoice_update()
    {
        global $_e;
        if (!empty($_POST["orderid"]) && isset($_POST["invoice"])) {
            $order_id = $_POST["orderid"];
            $pre_st = explode('-', $_POST['prev_status']);
            $actual_invId = $pre_st[1];
            $previous_order = $pre_st[0];
            $previous_status = $this->productF->invoiceStatusFind($previous_order);

            $id = $order_id;
            $invoice_id = $_POST["invoice"];
            $new_st = $this->productF->invoiceStatusFind($invoice_id);
            $inv = $invoice_id;

            if ($previous_order != $invoice_id) {
                $log_des = "Invoice status changed from $previous_status to $new_st";
                $this->functions->orderlog(_js(_uc($_e['Invoice Status Updated'])), _js(_uc($_e['Invoice'])), $actual_invId, $log_des);
            }

            $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id'";
            $dataTrans = $this->dbF->getRow($sql);

            $paymentType = $dataTrans['paymentType'];
            $paymentInfo = $dataTrans['payment_info'];
            $trackNumber = $dataTrans['trackNo'];

            if (($inv == '0' || $inv == '3' || $inv == '6')) {
                $sql = "SELECT inTransaction,rsvNo,rsvNo_done,invoice_id FROM `order_invoice` WHERE order_invoice_pk = '$id' AND inTransaction!=''";
                $dataTrans = $this->dbF->getRow($sql);

                if ($this->dbF->rowCount > 0 && ($paymentType == '2')) {
                    $rsvNo = $dataTrans['rsvNo'];
                    $rsvNo_done = $dataTrans['rsvNo_done'];
                    $inTransaction = trim($dataTrans['inTransaction']);
                    $invv_id = $dataTrans['invoice_id'];

                    $sql1 = "SELECT * FROM `order_extra_amount` WHERE invoice_no = '$invv_id' AND inTransaction!=''";
                    $extraTrans = $this->dbF->getRows($sql1);

                    if ($this->dbF->rowCount > 0 && ($paymentType == '2')) {
                        foreach ($extraTrans as $key => $value) {
                            $extraPaymentType = $value['paymentType'];
                            $extraPaymentInfo = $value['payment_info'];
                            $extraId = $value['id'];
                            $extrarsvNo = $value['rsvNo'];
                            $extrarsvNo_done = $value['rsvNo_done'];
                            $extrainTransaction = trim($value['inTransaction']);
                            /* ------- ---------- KLARNA ------------- ------------ */
                            $klarnaReturnExtra = $this->handelKlarna($extraId, $extrainTransaction, $inv, $extraPaymentType, $extrarsvNo, $extrarsvNo_done, true);

                            $returnKlarnaExtra = $klarnaReturnExtra;
                            /* ------- ----------KLARNA End------------- ------------ */
                            $paymentInfoExtra = $extraPaymentInfo . "\n $returnKlarnaExtra";

                            $sql0 = "UPDATE `order_extra_amount` SET invoice_status = ?,payment_info = ?  WHERE invoice_no = ?";
                            $this->dbF->setRow($sql0, array($invoice_id, $paymentInfoExtra, $invv_id));

                        }
                    }

                    /* ------- ---------- KLARNA ------------- ------------ */
                    $klarnaReturn = $this->handelKlarna($id, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done);
                    $returnKlarna = $klarnaReturn;
                    /* ------- ----------KLARNA End------------- ------------ */
                    $paymentInfo = $paymentInfo . "\n $returnKlarna";
                }
            }
            
            if (($inv == '3') && ($paymentType == '9')) {
                ///complete
                $sqlX = "SELECT * FROM `klarnaAPI` WHERE `invId` = '$id' AND `authorization_token` != ''";
                $orderX = $this->dbF->getRow($sqlX);
                $orderJ = unserialize($orderX['sessionRequestShipper']);
                $decoded = json_decode($orderJ, true);
                $sessionResponceShipper = unserialize($orderX['sessionResponceShipper']);
                $decodedq = json_decode($sessionResponceShipper, true);
                $order_idD = $decodedq['order_id'];
                if ($this->functions->ibms_setting('klarnaPaymentTesting') == '1') {
                    $url = 'https://api.playground.klarna.com/ordermanagement/v1/orders/' . $order_idD . '/captures';
                    $username = $this->functions->ibms_setting('KP_Test_user');
                    $password = $this->functions->ibms_setting('KP_Test_pswrd');
                } else {
                    $url = 'https://api.klarna.com/ordermanagement/v1/orders/' . $order_idD . '/captures';
                    $username = $this->functions->ibms_setting('KP_live_user');
                    $password = $this->functions->ibms_setting('KP_Live_pswrd');
                }
                $aAuthorization = base64_encode($username . ":" . $password);
                $order_amount = $decoded['order_amount'];
                $r = [];
                foreach ($decoded as $idC => $row) {
                    unset($decoded['purchase_country']);
                    unset($decoded['purchase_currency']);
                    unset($decoded['order_amount']);
                    unset($decoded['order_tax_amount']);
                    unset($decoded['auto_capture']);
                    unset($decoded['merchant_urls']);
                    unset($decoded['merchant_reference1']);
                }
                $ajs = json_encode($decoded, true);
                $ajs = str_replace('{"order_lines":[', '"order_lines":[', $ajs);
                $ajs = str_replace('}]}', '}]', $ajs);
                $jJson = '{
                "captured_amount": ' . $order_amount . ',
                "description": ' . $id . ' ,"reference": ' . $id . ', ' . $ajs . '}';
                $curlx = curl_init();
                curl_setopt_array(
                    $curlx,
                    array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $jJson,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Basic ' . $aAuthorization . '',
                            'Content-Type: application/json'
                        ),
                    )
                );
                $resultx = curl_exec($curlx);
                curl_close($curlx);
                $dec = json_decode($resultx, true);

                $sql = "UPDATE `klarnaAPI` SET `capturesRequest` = ?, `capturesResponce` = ? WHERE `id` = ?";
                $this->dbF->setRow($sql, [serialize($jJson), serialize($resultx), $orderX['id']]);

                $paymentInfo = $paymentInfo . "\n" . serialize($resultx);

                echo "<pre>";
                print_r($dec);
                echo "</pre>";
            }
            
            $sql = "UPDATE order_invoice SET invoice_status = ?, payment_info = ?  WHERE order_invoice_pk = ?";
            $this->dbF->setRow($sql, array($invoice_id, $paymentInfo, $order_id));

            if ($this->dbF->rowCount > 0) {
                 
                $sql = "SELECT * FROM `order_invoice_info` WHERE order_invoice_id = '$id'";
                $data_info = $this->dbF->getRow($sql);

                $link = WEB_URL . "/viewOrder?view=$id&orderId=" . $this->functions->encode($id);
                $invStatus = $this->productF->invoiceStatusFind($inv);

                $to = $data_info['sender_email'];
                $invoice = $this->functions->ibms_setting('invoice_key_start_with');



                $returnProArr = $this->bestSellerNewsletter();

                $completeMailArray['best_selling_products_last_30_days'] = $returnProArr;
                $completeMailArray['link'] = $link;
                $completeMailArray['invoiceStatus'] = $invStatus;
                $completeMailArray['invoiceNumber'] = $invoice . "" . $id;
                $completeMailArray["trackingNo"] = $trackNumber;

                if($inv === "3" && $pre_st[0] !== $inv){
                    $lang = $this->productF->getOrderLanguage($id);
                    $this->functions->send_mail($to, '', '', 'orderUpdate', '', $completeMailArray, false, $lang);
                }
                

                if ($inv === "3" || $inv === "9") {
                    $this->functions->require_once_custom('orderInvoice');
                    $orderInvoiceClass = new invoice();
                    $orderInvoiceClass->stockDeductFromOrder($order_id, false, false, true);

                }

                if($inv === "0" && $pre_st[0] !== $inv){
                    $cancelMailArray['invoiceStatus'] = $invStatus;
                    $cancelMailArray['invoiceNumber'] = $invoice . "" . $id;
                    
                    $lang = $this->productF->getOrderLanguage($id);
                    $this->functions->send_mail($to, '', '', 'orderCancel', '', $cancelMailArray, false, $lang);
                }

                if ($inv == "0" || $inv == "6" || $inv == "1") {




                    $sql11 = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
                    $sQ = $this->dbF->getRows($sql11);

                    if ($this->dbF->rowCount > 0) {
                        foreach ($sQ as $key => $pP) {

                            $order_pName = $pP['order_pName'];

                            $eX = explode(" - ", $order_pName, 2);

                            $s = $eX[1];




                            $pIds = $pP['order_pIds'];
                            $pids = explode("-", $pIds);
                            $pId = $pids[0];
                            $scaleId = $pids[1];
                            $colorId = $pids[2];
                            $storeId = $pids[3];

                            $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' and `prosiz_cur_id` = 20 and `prosiz_name`='$s' ";
                            $spData = $this->dbF->getRow($sp);
                            if ($this->dbF->rowCount > 0) {
                                $scaleId = $spData['prosiz_id'];
                            }




                            @$customId = $pids[4];
                            //Stock add on Admin side
                        }


                    }



                }

                echo json_encode(array("id" => "1", "val" => $returnKlarna));
            } else {
                echo json_encode(array("id" => "0", "val" => $returnKlarna));
            }
        }
    }


    function AddQtyBackInStock($pId = "", $scaleId = "", $colorId = "", $storeId = "", $qty = "", $oid = "")
    {
        global $_e;
        $pqty = intval($qty);

        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
        $hash = md5($hashVal);

        if (isset($_POST['dStock'])) {
            $dStock = empty($_POST['dStock']) ? array() : $_POST['dStock'];
            for ($i = 0; $i < count($dStock); $i++) {

                if ($dStock[$i] == $hash) {
                    $hash = $dStock[$i];

                    $sqlCheck = "SELECT * FROM `product_inventory` WHERE `product_store_hash` = ?";
                    $inventoryData = $this->dbF->getRow($sqlCheck, [$hash]);
                    
                    if ($this->dbF->rowCount > 0) {
                        $invQty = $inventoryData['qty_item'];
                        $qty_product_id = $inventoryData['qty_product_id'];
                        $qty_product_scale = $inventoryData['qty_product_scale'];
                        
                        $pName = $this->productF->getProductName($qty_product_id);
                        $sName = $this->productF->getScaleName($qty_product_scale);

                        $date = date('Y-m-d H:i:s');
                        $pqty = $qty;

                        $sql = "UPDATE `product_inventory` SET `qty_item` = `qty_item` + ? WHERE `product_store_hash` = ?";
                        $stmt = $this->dbF->setRow($sql, [$qty, $hash]);

                        $pMmsg = $pqty . " qty Added successfully " . $pName . " " . $sName;
                        echo "<div class='alert alert-info'>$pMmsg</div>";
                        $publish_date = date('Y-m-d h:i:a');

                        $sqlg = "INSERT INTO `invLogs` (`type`, `hash`, `sid`, `pid`, `minusQty`, `addQty`, `publish_date`, `heading`, `oid`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['OrderUpdate From Sharkspeed MyAdmin', $hash, $qty_product_scale, $qty_product_id, '', $qty, $publish_date, 'sharkspeed.com', $oid]);

                        $user_email = @$_SESSION['_email'];
                        $ref_id = $_SESSION['_uid'];
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $browser = "";
                        foreach ($this->getBrowserCommon() as $key => $val) {
                            $browser .= "$key : $val <br />";
                        }

                        $pName = $this->productF->getProductName($cid) . '-' . $this->productF->getScaleName($sid);
                        $desc = $qty . " Items has been deleted to the productName( $pName) and scale id $sid  has been deleted";
                        
                        $sqlg = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['OrderUpdate', 'Sharkspeed MyAdmin', $hash, $ref_id, $user_email, $qty, '', $qty_product_id, $sid, $oid, $desc, $ip, $browser]);

                    } else {
                        $pName = $this->productF->getProductName($pId);
                        $sName = $this->productF->getScaleName($scaleId);

                        $sql = "INSERT INTO `product_inventory` (`qty_store_id`, `qty_product_id`, `qty_product_scale`, `qty_product_color`, `qty_item`, `product_store_hash`) 
                        VALUES (?, ?, ?, ?, ?, ?) ";
                        $this->dbF->setRow($sql, [$storeId, $pId, $scaleId, $colorId, $pqty, $hash]);
                        
                        $pMmsg = $pqty . " qty Added successfully " . $pName . " " . $sName;
                        echo "<div class='alert alert-info'>$pMmsg</div>";

                        $publish_date = date('Y-m-d h:i:a');
                        
                        $sqlg = "INSERT INTO `invLogs` (`type`, `hash`, `sid`, `pid`, `minusQty`, `addQty`, `publish_date`, `heading`, `oid`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['cancel order', $hash, $scaleId, $pId, '', $pqty, $publish_date, 'sharkspeed.com', $oid]);

                        $user_email = @$_SESSION['_email'];
                        $ref_id = $_SESSION['_uid'];
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $browser = "";
                        
                        foreach ($this->getBrowserCommon() as $key => $val) {
                            $browser .= "$key : $val <br />";
                        }
                        
                        $pName = $this->productF->getProductName($cid) . '-' . $this->productF->getScaleName($sid);
                        $desc = $qty . " Items has been deleted to the productName( $pName) and scale id $sid  has been deleted";
                        
                        $sqlg = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `hash`, `ref_id`, `ref_user`, `add_qty`, `minus_qty`, `pid`, `sid`, `order_id`, `log_desc`, `log_ip`, `log_browser`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->dbF->setRow($sqlg, ['OrderUpdate', 'Sharkspeed MyAdmin', $hash, $ref_id, $user_email, $pqty, '', $pId, $scaleId, $oid, $desc, $ip, $browser]);
                    }


                }
            }
        }

        return true;
    }



    public function handelKlarna($orderId, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done, $extra = false)
    {
        //All work will Handel Accordingly
        $this->functions->require_once_custom('Class.myKlarna.php');
        $klarnaClass = new myKlarna();
        return $klarnaClass->klarnaInvoices($orderId, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done, $extra);
    }

    public function sendMadeMeasure()
    {
        $to = $_POST['email'];
        $inv_id = $_POST['inv_id'];



        $this->functions->send_mail($to, '', '', 'madeToMeasurePdf', '', '');

        $sql = "SELECT * FROM email_letters WHERE email_type = 'madeToMeasurePdf' ";
        $letterData = $this->dbF->getRow($sql);

        $log_desc = "<p>Email Sent To: $to  </p>" . $letterData['message'];

        $this->functions->orderlog('Made To Measure Email Sent', 'Invoice', $inv_id, $log_des);
    }

    public function getTemplateDetail()
    {
        $temp_id = $_POST['temp_id'];

        $sql = "SELECT * FROM `email_letters` WHERE `id` = ?";
        $res = $this->dbF->getRow($sql, array($temp_id));

        echo '<div class="email_temp">';
        echo '<p id="title">' . $res['event'] . '</p>';
        echo '<p id="from_name">' . $res['from_name'] . '</p>';
        echo '<p id="from_mail">' . $res['from_mail'] . '</p>';
        echo '<p id="subject">' . $res['subject'] . '</p>';
        echo '<div id="message">' . $res['message'] . '</div>';
        echo '</div>';

    }






    public function saveRecordSPonline()
    {
        global $sharkOnlineConnection;
        $ass_id = $_POST['uId'];
        $a = explode(" - ", $_POST['oId']);
        $oId = $a[1];
        $opID = $a[0];
        $assign_date = date('Y-m-d');
        $sharkOnlineConnection->getRow("SELECT opID FROM `history` WHERE `opID` = ?", array($opID));
        if ($sharkOnlineConnection->rowCount == 0) {
            $sqlc = "SELECT orderUser,invoice_date,shippingCountry FROM `order_invoice` WHERE `invoice_id` = ?";
            $res = $this->dbF->getRow($sqlc, array($oId));
            $orderUserId = $res['orderUser'];
            $order_date = $res['invoice_date'];
            $shippingCountry = $res['shippingCountry'];
            $sqlcc = "SELECT  order_pName,order_pIds, order_pQty FROM `order_invoice_product` WHERE `invoice_product_pk` = ?";
            $res1 = $this->dbF->getRow($sqlcc, array($opID));
            $pname = $res1['order_pName'];
            $pqty = $res1['order_pQty'];
            $order_pIds = $res1['order_pIds'];
            $pArray = explode("-", $order_pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
            $pId = $pArray[0]; // 491
            $scaleId = $pArray[1]; // 426
            $colorId = $pArray[2]; // 435
            $sqlcc = "SELECT  sender_email,sender_name FROM `order_invoice_info` WHERE `order_invoice_id` = ?";
            $oIdz = filter_var(
                $oId,
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
            $res11 = $this->dbF->getRow($sqlcc, array($oIdz));
            $orderUserName = $res11['sender_name'];
            $orderUserEmail = $res11['sender_email'];
            $img = $this->productF->productSpecialImage($pId, 'main');
            if (empty($img)) {
                $img = "default.jpg";
            }
            // create history end
// create order sharkonline start
            $assign_supplier_date = date('Y-m-d');
            $order_date = date('Y-m-d', strtotime($order_date));
            $order_info = $oId;
            $acc_id = $ass_id;
            $publish = "1";
            $order_status = "pending";
            $extra_message = $pname;
            $delivery_time = "10";
            $date_data = date('Y-m-d', strtotime($order_date));
            $effectiveDate = date('Y-m-d', strtotime("+10 days", strtotime($date_data)));
            $delivery_date = $effectiveDate;

            $hisId = $sharkOnlineConnection->getRow("SELECT id,acc_id FROM `supplier` WHERE `order_info` = ? and `acc_id` = ?", array($order_info, $acc_id));
            if ($sharkOnlineConnection->rowCount == 0) {
                try {
                    $username = "sharkonl_commusr";
                    $password = 'hEbr6%99%6TtJM..bT';
                    $dbname = 'sharkonl_communication';
                    $servername = 'localhost';
                    $img1 = WEB_URL . "/images/" . $img;
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sqlCC = "INSERT INTO `supplier`(`order_date`, `order_date_supplier` , `order_info`,`order_product_qty`,`acc_id`,`publish`,`order_status`,`extra_message`,`delivery_time`,`delivery_date`,`highlighted_admin`,`imgLink`) VALUES ('$order_date','$assign_supplier_date','$order_info','$pqty','$acc_id','$publish','$order_status','$extra_message','$delivery_time','$delivery_date','$highlighted_admin','$img1') ";
                    $stmt = $conn->exec($sqlCC);
                    $sFK = $conn->lastInsertId();
                    echo "Successfully order create for supplier & log created in SharkOnline.";







                    // create history start
                    $sql = "INSERT INTO `history`(`ass_id`, `orderUserId`, `opID`, `pQty`, `assign_date`, `order_date`, `oId`, `pname`, `orderUserName`, `orderUserEmail`, `shippingCountry`, `sFK`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
                    $sharkOnlineConnection->setRow($sql, array($ass_id, $orderUserId, $opID, $pqty, $assign_date, $order_date, $oId, $pname, $orderUserName, $orderUserEmail, $shippingCountry, $sFK));






                    $accNme = $sharkOnlineConnection->getRow("SELECT acc_name FROM `accounts` WHERE `acc_id` = ?", array($acc_id));

                    $log_desc = "<p>was sent to: " . $accNme['acc_name'] . " </p>";

                    $this->functions->orderlog($extra_message, 'Successfully order create for supplier & log created in SharkOnline.', $order_info, $log_desc);

                    $id = $sFK;



                } catch (PDOException $e) {
                    echo $e;
                }
                $conn = null;
            } else {
                try {
                    $username = "sharkonl_commusr";
                    $password = 'hEbr6%99%6TtJM..bT';
                    $dbname = 'sharkonl_communication';
                    $servername = 'localhost';
                    $img1 = WEB_URL . "/images/" . $img;
                    $connN = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $connN->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $id = $hisId['id'];
                    $acc_idD = $hisId['acc_id'];

                    $sqlUp = "UPDATE `supplier` SET `extra_message` = CONCAT(extra_message, ',', '$extra_message'), `imgLink` = CONCAT(imgLink, ',', '$img1') WHERE `id`= '$id'";
                    $stmt = $connN->exec($sqlUp);
                    echo "Successfully update within previous order & log created in SharkOnline.";



                    // create history start
                    $sql = "INSERT INTO `history`(`ass_id`, `orderUserId`, `opID`, `assign_date`, `order_date`, `oId`, `pname`, `orderUserName`, `orderUserEmail`, `shippingCountry`, `sFK`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                    $sharkOnlineConnection->setRow($sql, array($ass_id, $orderUserId, $opID, $assign_date, $order_date, $oId, $pname, $orderUserName, $orderUserEmail, $shippingCountry, $id));



                    $accNme = $sharkOnlineConnection->getRow("SELECT acc_name FROM `accounts` WHERE `acc_id` = ?", array($acc_idD));

                    $log_desc = "<p>was sent to: " . $accNme['acc_name'] . " </p>";

                    $this->functions->orderlog($extra_message, 'Successfully update within previous order & log created in SharkOnline.', $order_info, $log_desc);





                } catch (PDOException $e) {

                }
                $connN = null;
            }
            $img = str_replace("/product/", "/supplier/", $img);
            try {
                $username = "sharkonl_commusr";
                $password = 'hEbr6%99%6TtJM..bT';
                $dbname = 'sharkonl_communication';
                $servername = 'localhost';
                $conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sqlCC = "INSERT INTO `supplier_p_image`(`supplier_p_id`,`image`) VALUES ('$id','$img') ";
                $stmt = $conn1->exec($sqlCC);
            } catch (PDOException $e) {
                // echo $e;
            }
            $conn1 = null;





        } else {

            echo "already assigned to supplier";

        }




    }

    public function sendTemplateEmail()
    {
        $titleHtml = $_POST['titleHtml'];
        $nameHtml = $_POST['nameHtml'];
        $mailHtml = $_POST['mailHtml'];
        $subjectHtml = $_POST['subjectHtml'];
        $email_msg = $_POST['email_msg'];
        $to = $_POST['senderr_email'];
        $email_temp = $_POST['email_temp'];
        $giftCard = (isset($_POST['giftCard']) && !empty($_POST['giftCard'])) ? $_POST['giftCard'] : '';

        $inv_id = $_POST['invoic_id'];

        $array = array();
        $msgType = '';
        $array['fromBeforeAt'] = $mailHtml;
        $array['fromName'] = $nameHtml;
        if (!empty($giftCard) && $email_temp == 257) {
            $array['giftCard'] = $giftCard;
            $msgType = 'GiftCardFromOrder';
        }

        $mail = $this->functions->send_mail($to, $subjectHtml, $email_msg, $msgType, '', $array);

        $log_desc = "<p>Status : $subjectHtml</p><p>Email Sent To: $mailHtml </p>" . $email_msg;

        $this->functions->orderlog("Email sent to customer with status", 'Invoice', $inv_id, $log_desc);
    }

    public function create_comment()
    {
        $inv_id = $_POST['orderIdint'];
        $invoiceStatus = $_POST['invoiceStatus'];
        $int_comTxt = $_POST['int_comTxt'];
        $user_email = $_SESSION['_email'];

        $sql = "INSERT INTO `internal_comment_orderInvoice`(`invoice_id`, `status`, `comment`, `user_email`) VALUES (?,?,?,?)";
        $res = $this->dbF->setRow($sql, array($inv_id, $invoiceStatus, $int_comTxt, $user_email));

        print_r(array($inv_id, $invoiceStatus, $int_comTxt, $user_email));

    }




    public function freeGiftProducts($invoiceId, $orderedProducts = false)
    {

        $sql = "SELECT pro_ids,id FROM `free_gift_inv` WHERE `txt_inv_id` = ? and status =? ORDER BY `free_gift_inv`.`id` DESC";
        $res = $this->dbF->getRow($sql, array($invoiceId, 0));

        $offerProducts = ($res['pro_ids']);

        $temp = '<style type="text/css">/*pop side*/

/*pop side*/

.pop_side {
.pop_content {
position: relative;
width: 45%;
padding-top: 20px;
text-align: center;
display: inline-block;
vertical-align: top;
margin-right: 2%;
}
}

.pop_side_top {
position: relative;
width: 100%;
border-bottom: 1px solid #e1e2e4;
padding-bottom: 15px;
}

.pop_side_top i {
font-size: 25px;
color: #42474b;
display: inline-block;
vertical-align: middle;
margin-right: 10px;
}

.number_side {
position: absolute;
right: 0px;
top: 0px;
font-size: 25px;
color: #42474b;
}

.number_side span {
font-size: 34px;
color: #42474b;
font-family: "ubuntubold";
display: inline-block;
vertical-align: top;
}

.pop_content {
position: relative;
width: 45%;
display: inline-block;
vetical-align: top;
margin-right: 2%;
text-align: center;
}

.pop_content:nth-child(even) {
margin-right: 0%;
}

.pop_img {
position: relative;
display: block;
width: 35%;
margin: 0 auto;
}

.pop_img img {
width: 100%;
}

.pop_slide {
position: relative;
width: 100%;
}

.pop_img1 {
position: relative;
width: 100%;
}

.pop_content_main {
display: inline-block;
vertical-align: middle;
width: 55%;
text-align: center;
margin: 15px 0px;
}

.pop_content_main_btn {
position: relative;
width: 215px;
text-align: center;
background: #42474b;
box-shadow: 5px 5px 0px #a2a6af;
border-radius: 5px 5px;
margin: 0 auto;
transition: .7s;
margin-bottom: 20px;
}

.pop_content_main_btn a {
display: block;
color: #ffffff;
font-size: 16px;
padding: 10px 0px;
}

.pop_content_main_btn:hover {
background: #7ebc41;
}

.selection_side {
position: relative;
width: 100%;
color: #42474b;
font-size: 16px;
}

.pop_content_main .select_box4 {
width: 215px;
}

.pop_content_main .select_box4 .dropdown_select dt a {
height: 42px !important;
line-height: 42px !important;
border: 1px solid #6b6f72 !important;
box-shadow: 3px 3px 0px #363b3f;
border-radius: 2px 2px;
background: #ffffff url(../webImages/arrow.png) no-repeat scroll right center;
}

.pop_price {
position: relative;
width: 100%;
color: #eb333d;
font-size: 30px;
text-align: center;
font-weight: bold;
}

.pop_price span {
color: #a0a9b0;
margin: 0px 5px;
display: block;
font-style: normal;
font-size: 16px;
text-decoration: line-through;
font-weight: normal;
font-family: sans-serif;
}

.button_side {
position: relative;
display: inline-block;
vertical-align: middle;
width: 100%;
margin-top: 5px;
}

.button_side1 {
position: relative;
display: inline-block;
vertical-align: top;
width: 45%;
background: #42474b;
padding: 10px 0px;
border-radius: 2px 2px;
text-align: center;
margin-right: 2%;
}

.button_side1 a {
display: block;
color: #ffffff;
font-size: 16px;
}

.button_side2 {
position: relative;
display: inline-block;
vertical-align: top;
width: 60%;
background: #7ebc41;
padding: 10px 0px;
border-radius: 2px 2px;
text-align: center;
}

.button_side2 a {
display: block;
color: #ffffff;
font-size: 16px;
}

.pop_btn {
position: absolute;
left: 0px;
top: 50%;
width: 100%;
z-index: 1;
}

.pop_btn1 {
position: absolute;
left: 1%;
width: 16px;
height: 28px;
background: url(../webImages/news_left1.png);
cursor: pointer;
}

.pop_btn2 {
position: absolute;
right: 1%;
width: 16px;
height: 28px;
background: url(../webImages/news_right1.png);
cursor: pointer;
}

.pop_close {
position: absolute;
right: 10px;
top: 10px;
cursor: pointer;
font-size: 25px;
}


/*pop side*/
</style>

<br><br>
';


        $pLink = WEB_URL . '/giftOffer.php?invoice=' . $this->functions->encode($res['id']);


        $applied_for_fields_array = explode(',', $offerProducts);


        foreach ($applied_for_fields_array as $field) {






            $id = $field;
            $name = $this->productF->getProductName($id);
            $img = $this->productF->productSpecialImage($id, 'main');
            $img = $this->functions->resizeImage($img, 'auto', 160, false);
            $price = $this->productF->productPrice($id);
            $currencyId = $price['propri_cur_id'];
            $symbol = $this->productF->currencySymbol($currencyId);
            $priceP = $price['propri_price'];

            $discount = $this->productF->productDiscount($id, $currencyId);
            @$discountFormat = $discount['discountFormat'];
            @$discountP = $discount['discount'];

            $discountPrice = $this->productF->discountPriceCalculation($priceP, $discount);

            $newPrice = $discountPrice;

            $priceP .= ' ' . $symbol;
            $newPrice .= ' ' . $symbol;

            if ($newPrice != $priceP) {
                $hasDiscount = true;
                $oldPriceDiv = '<span class="oldPrice">' . $priceP . '</span>';
                $newPriceDiv = $newPrice;
            } else {
                $oldPriceDiv = "";
                $newPriceDiv = $priceP;
            }

            $buyToT = $this->dbF->hardWords('Buy To', false);
            $buyToFree = $this->dbF->hardWords('Free Gift', false);

            $temp .= "<div class='pop_content'>
<div class='pop_img'>
<div class='pop_img1'><img alt='' src='$img' loading='lazy'/></div>
<!-- pop_img1 close --></div>
<!-- pop_img close -->

<div class='pop_content_main'>
<div class='selection_side'>$name</div>
<!-- selection_side close -->


<div class='button_side'>


 <div class='button_side1'><a href='" . $pLink . "'>" . $buyToFree . "</a></div>



<!-- button_side1 close -->
<div class='button_side2'><a href='" . $pLink . "'>" . $buyToT . "</a></div>
<!-- button_side1 close --></div>
<!-- button_side close --></div>
<!-- pop_content_main close --></div>";


        }


        return $temp;
    }

    public function create_pro_ajax()
    {
        $inv_id = $_POST['txt_inv_id'];
        $txt_inv_pro_qty = $_POST['txt_inv_pro_qty'];

        $data = $_POST['signUp']['ids'];

        $val = "";

        for ($i = 0; $i < count($data); $i++) {
            $val = implode(',', $data);
        }

        $validity = $this->functions->ibms_setting('saleOfferValidity');
        $validity_date = date('Y-m-d', strtotime("+" . $validity . " days"));


        $sql = "INSERT INTO `free_gift_inv`(`txt_inv_id`, `txt_inv_pro_qty`, `pro_ids`,`validity_date`) VALUES (?,?,?,?)";
        $res = $this->dbF->setRow($sql, array($inv_id, $txt_inv_pro_qty, $val, $validity_date));



        $freeGiftEmailContent = $this->freeGiftProducts($inv_id);

        $sqli = "SELECT sender_email,sender_name FROM order_invoice_info WHERE `order_invoice_id` = ?";
        $datai = $this->dbF->getRow($sqli, array($inv_id));


        $freeGiftextraMailArray['freeGiftProductsDiv'] = $freeGiftEmailContent;
        $freeGiftextraMailArray['cusName'] = $datai['sender_name'];

        $sqlj = "SELECT invoice_date FROM order_invoice WHERE `order_invoice_pk` = ?";
        $invoice_date = $this->dbF->getRow($sqlj, array($inv_id));


        $freeGiftextraMailArray['orDate'] = $invoice_date['invoice_date'];

        $freeGiftextraMailArray['invoiceNumber'] = $inv_id;
        $this->functions->send_mail($datai['sender_email'], '', '', 'freeGiftProductsDiv', '', $freeGiftextraMailArray);



    }



    public function getComments()
    {
        $inv_id = $_POST['invoice_id'];

        $sql = "SELECT * FROM `internal_comment_orderInvoice` WHERE `invoice_id` = ? ORDER BY `date_timestamp` DESC";
        $res = $this->dbF->getRows($sql, array($inv_id));

        foreach ($res as $key => $value) {
            $status = $value['status'];
            $comment = $value['comment'];
            $user = $value['user_email'];
            $date_timestamp = explode(' ', $value['date_timestamp']);

            $date = $date_timestamp[0];
            $time = $date_timestamp[1];

            $inv_name = $this->productF->invoiceStatusFind($status);

            echo '
                <li>
                    <div class="date_portion">' . $date . '</div><div class="time_portion">' . $time . '</div><div class="tag_portion">' . $inv_name . '</div>
                    <div class="col_portion_mid_area">By: ' . $user . '</div>
                    <div class="col_portion_mid_area1">' . $comment . '</div>
                </li>

            ';
        }
    }

    public function getLogs()
    {
        $inv_id = $_POST['invoice_id'];
        $sql = "SELECT * FROM `order_activity_log` WHERE `ref_id` LIKE '%$inv_id%' ORDER BY `log_time` DESC";
        $res = $this->dbF->getRows($sql);

        if ($this->dbF->rowCount > 0) {

            foreach ($res as $key => $value) {
                $log_title = $value['log_title'];
                $ref_user = $value['ref_user'];
                $log_desc = $value['log_desc'];
                $log_time = $value['log_time'];
                $log_ip = $value['log_ip'];
                $date_timestamp = explode(' ', $log_time);

                $date = $date_timestamp[0];
                $time = $date_timestamp[1];

                echo '
                <li>
                    <div class="date_portion">' . $date . '</div><div class="time_portion">' . $time . '</div><div class="tag_portion">' . $log_title . '</div>
                    <div class="col_portion_mid_area1">' . $log_desc . '</div>
                </li>

            ';
            }
        }
    }

    public function getLogs1()
    {
        $inv_id = $_POST['invoice_id'];

        $inv_id = filter_var($inv_id, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);



        $sql = "SELECT * FROM `free_gift_inv` WHERE `txt_inv_id` = ? ORDER BY `dateTime` DESC";
        $res = $this->dbF->getRows($sql, array($inv_id));

        if ($this->dbF->rowCount > 0) {

            foreach ($res as $key => $value) {
                $dateTime = $value['dateTime'];
                $status = $value['status'];
                $txt_inv_pro_qty = $value['txt_inv_pro_qty'];
                $pro_ids = $value['pro_ids'];
                $new_inv = $value['new_inv'];

                $getProductName = "";

                $lang_nonArray = explode(',', $pro_ids);
                for ($i = 0; $i < count($lang_nonArray); $i++) {



                    $getProductName .= $this->productF->getProductName($lang_nonArray[$i]);
                    $getProductName .= ",";

                }



                echo '
<li>
<div class="time_portion">Mail Sending Time: ' . $dateTime . '</div>';
                if ($status == 0) {
                    echo '

<div class="time_portion">Status: Not Used</div>


';
                } else {


                    echo '

<div class="time_portion">Status: Used</div>


';

                }

                echo '


<div class="time_portion">Product Quantity Allow: ' . $txt_inv_pro_qty . '</div>
<div class="time_portion">Free Gift Invoice ID: ' . $new_inv . '

<div class="btn-group btn-group-sm">
                        <a href="../invoicePrint?mailId=' . $new_inv . '" target="_blank" class="btn">
                                    <i class="fa fa-file-pdf-o"></i>
                               </a>
                        </div>

</div>

</li>

';

            }
        } else {




            $sql = "SELECT * FROM `free_gift_inv` WHERE `new_inv` = ? ORDER BY `dateTime` DESC";
            $res = $this->dbF->getRow($sql, array($inv_id));

            if ($this->dbF->rowCount > 0) {

                echo '
<li>
<div class="time_portion">Mail Sending Time: ' . $res['dateTime'] . '</div>';


                echo '


<div class="time_portion">Product Quantity Allow: ' . $res['txt_inv_pro_qty'] . '</div>
<div class="time_portion">Reference Invoice ID: ' . $res['txt_inv_id'] . '
<div class="btn-group btn-group-sm">
<a href="../invoicePrint?mailId=' . $res['txt_inv_id'] . '" target="_blank" class="btn">
<i class="fa fa-file-pdf-o"></i>
</a>
</div>

</div>

</li>

';




            }

        }
    }

    public function getInvoiceStatusHardwords($value)
    {
        return $this->dbF->hardwords($value, false);
    }

    public function submitExtraAmountForm()
    {

        $invoiceId = $_POST['invoiceId'];
        $extra_amnt = $_POST['extra_amnt'];
        $to = $_POST['senderEmail'];
        $curSybmol = $_POST['curSybmol'];
        $paymentType = $_POST['paymentType'];
        $description = $_POST['description'];

        $now = date('Y-m-d H:i:s');

        $sql = "INSERT INTO `order_extra_amount`(`invoice_no`, `invoice_date`, `paymentType`, `extra_amount`, `price_code`, `invoice_status`, `orderStatus`, `shippingCountry`, `description`) VALUES (?,?,?,?,?,?,?,?,?)";
        $res = $this->dbF->setRow($sql, array($invoiceId, $now, $paymentType, $extra_amnt, $curSybmol, '1', 'inComplete', 'SE', $description));

        if ($this->dbF->rowCount > 0) {

            if ($paymentType == 2) {
                $link = WEB_URL . '/extra_pay?inv=' . $invoiceId . '&id=' . $this->dbF->rowLastId;
            } else if ($paymentType == 5) {
                $link = WEB_URL . '/extra_payment?inv=' . $invoiceId . '&id=' . $this->dbF->rowLastId;
            }

            $mailArray['invoiceNumber'] = $invoiceId;
            $mailArray['ExtraPayment'] = $extra_amnt . ' ' . $curSybmol;
            $mailArray['ExtraPayLink'] = $link;
            $mailArray['ExtraPayDesc'] = $description;
            $lang = $this->productF->getOrderLanguage($invoiceId);
            $mail = $this->functions->send_mail($to, '', '', 'orderExtraPayment', '', $mailArray, true, $lang);

            if ($mail) {
                // echo '1';
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }

    public function flagOrder()
    {
        $invId = $_POST['itemId'];

        $sql = "UPDATE `order_invoice` SET `flagged` = ? WHERE `order_invoice_pk` = ?";
        $res = $this->dbF->setRow($sql, array(1, $invId));

        if ($this->dbF->rowCount > 0) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function removeFlagOrder()
    {
        $invId = $_POST['itemId'];

        $sql = "UPDATE `order_invoice` SET `flagged` = ? WHERE `order_invoice_pk` = ?";
        $res = $this->dbF->setRow($sql, array(0, $invId));

        if ($this->dbF->rowCount > 0) {
            echo '1';
        } else {
            echo '0';
        }
    }
    
    public function readyOrdersCount(){
        $data = $this->order_c->invoiceListReady('invoicesReady');
        echo $data;
    }    
    
    public function semiReadyOrdersCount(){
        $data = $this->order_c->invoiceListSReady('invoicesSReady');
        echo $data;
    }
    
    public function invoiceDetailAjax(){
        global $_e;
        $country = $_POST["country"];
        $orderId = $_POST["orderId"];
        $data = $this->invoice_c->invoiceDetail($orderId);
        $modal = "";
        if($country == "se" || $country == "SE"){
            $invForLable = @explode("_", $data['manuall_id'])[1];
            $invForLable = (isset($invForLable) && !empty($invForLable)) ? $invForLable : $data['invoice_id'];
        
            $modal .= '<div class="modal fade" id="checkoutOfferModalForShippingLabel" tabindex="-1" role="dialog"
              aria-labelledby="ForShippingLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
            
                <div class="modal-content">
            
                  <div class="modal-header">
                    <h4 class="modal-title" id="ForShippingLabel">Shipping Label</h4>
                  </div>
            
                  <div class="modal-body ">
                    <div>
                      <p id="shippingLabelLink"></p>
                    </div>
                    <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                      cellspacing="0">
                      <thead>
                        <th colspan="6">
                          <div class="text-center">' . _uc($_e["Detail"]) . '</div>
                        </th>
                      </thead>
            
                      <tr>
                        <td>' . _uc($_e["Invoice ID"]) . '</td>
                        <td>
                          <input class="shipLabel_inv_id form-control" value="' .  $invForLable . '" readonly>
                          <input type="hidden" class="shipLabel_countryName form-control"
                            value="' . $data["shippingCountry"] . '">
                        </td>
                      </tr>
            
                      <tr>
                        <td>' . _uc($_e["PACKAGE CODE"]) . '</td>
                        <td>
                          <select name="" class="shipLabel_basic_code form-control">
                            <option value="19">MYPACK COLLECT – 19</option>
                            <option value="24">MYPACK RETUR – 24</option>
                            <option value="86">VARUBREV - 86</option>
                          </select>
                        </td>
                      </tr>
            
            
                      <tr>
                        <td>' . _uc($_e["CUSTOMER NAME"]) . '</td>
                        <td><input class="shipLabel_cus_name form-control" value="' . $data["receiver_name"] . '"></td>
                      </tr>
            
                      <tr>
                        <td>' . _uc($_e["CUSTOMER EMAIL"]) . '</td>
                        <td><input class="shipLabel_cus_email form-control" value="' . $data["receiver_email"] . '"></td>
                      </tr>
            
                      <tr>
                        <td>' . _uc($_e["COSTUMER ADRESS"]) . '</td>
                        <td>
                          <textarea class="shipLabel_cus_add form-control">' . $data["receiver_address"] . '</textarea>
                        </td>
                      </tr>
            
                      <tr>
                        <td>' . _uc($_e["Contact No"]) . '</td>
                        <td><input class="shipLabel_contact_no_se form-control" value="' . $data["receiver_phone"] . '"></td>
                      </tr>
            
                      <tr>
                        <td>' . _uc($_e["Postal Code"]) . '</td>
                        <td><input class="shipLabel_post_code form-control" value="' . $data["receiver_post"] . '"></td>
                      </tr>
            
                      <tr>
                        <td>' . _uc($_e["City"]) . '</td>
                        <td><input class="shipLabel_city form-control" value="' . $data["receiver_city"] . '">
                        </td>
                      </tr>
            
            
            
                      <tr>
                        <td>' . _uc($_e["Total Gross Weight"]) . '</td>
                        <td><input class="shipLabel_Weight form-control" value="0.2"></td>
                      </tr>
            
            
                    </table>
                  </div>
            
            
                  <div class="modal-footer">
                    <button id="shipping_label_dismiss_btnNew" type="button" data-id="se"
                      class="btn btn-dark shipping_label_dismiss_btnNew">Submit</button>
                    <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
            
                </div>
              </div>
            </div>';
        }elseif($country == "ch" || $country == "CH"){
          $invForLable = @explode("_", $data['manuall_id'])[1];
          $invForLable = (isset($invForLable) && !empty($invForLable)) ? $invForLable : $data['invoice_id'];
            $modal .= '<div class="modal fade" id="checkoutOfferModalForShippingLabelCh" tabindex="-1" role="dialog"
                  aria-labelledby="ForShippingLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                
                    <div class="modal-content">
                
                      <div class="modal-header">
                        <h4 class="modal-title" id="ForShippingLabel">Shipping Label</h4>
                      </div>
                      <div class="modal-body ">
                        <div>
                          <p id="shippingLabelLink"></p>
                        </div>
                
                        <input type="hidden" class="shipLabel_country_code_ch" value="">
                        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                          cellspacing="0">
                          <thead>
                            <th colspan="6">
                              <div class="text-center">' . _uc($_e['Invoice Detail']) . '</div>
                            </th>
                          </thead>
                
                          <tr>
                            <td colspan="3">' . _uc($_e['Invoice ID']) . '</td>
                            <td colspan="3"><input class="shipLabel_inv_id_ch form-control" value="' . $invForLable . '" readonly>
                            </td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Order ID') . '</td>
                            <td colspan="3"><input class="shipLabel_order_id_ch form-control"
                                value="' . $data['order_invoice_pk'] . '" readonly></td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Order Date') . '</td>
                            <td colspan="3"><input class="form-control" value="' . $data['invoice_date'] . '" readonly></td>
                            <input type="hidden" class="shipLabel_order_date"
                              value="' . date('Ymd', strtotime($data['invoice_date'])) . '">
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Reason For Export') . '</td>
                            <td colspan="3"><input class="shipLabel_res_for_exp form-control" value="SALE"></td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Currency Code') . '</td>
                            <td colspan="3"><input class="shipLabel_curr_code form-control" value="' . $data['price_code'] . '">
                            </td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Frieght Charges') . '</td>
                            <td colspan="3"><input class="shipLabel_frieght_charges form-control" value=""></td>
                          </tr>
                
                          <thead>
                            <th colspan="6">
                              <div class="text-center">' . _uc('Customer Information') . '</div>
                            </th>
                          </thead>
                
                          <tr>
                            <td colspan="3">' . _uc('CUSTOMER NAME') . '</td>
                            <td colspan="3"><input class="shipLabel_cus_name form-control"
                                value="' . $data['receiver_name'] . '"></td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('CUSTOMER EMAIL') . '</td>
                            <td colspan="3"><input class="shipLabel_cus_email form-control"
                                value="' . $data['receiver_email'] . '"></td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('COSTUMER ADDRESS') . '</td>
                            <td colspan="3">
                              <textarea class="shipLabel_cus_add form-control">' . $data['receiver_address'] . '</textarea>
                            </td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Contact No') . '</td>
                            <td colspan="3"><input class="shipLabel_contact_noch form-control"
                                value="' . $data['receiver_phone'] . '"></td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Postal Code') . '</td>
                            <td colspan="3"><input class="shipLabel_post_code form-control"
                                value="' . $data['receiver_post'] . '"></td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('City') . '</td>
                            <td colspan="3"><input class="shipLabel_city form-control" value="' . $data['receiver_city'] . '">
                            </td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Province Code') . '</td>
                            <td colspan="3"><input class="shipLabel_province_code form-control" value=""></td>
                          </tr>
                
                          <thead>
                            <th colspan="6">
                              <div class="text-center">' . _uc('Package Information') . '</div>
                            </th>
                          </thead>
                
                          <tr>
                            <td colspan="3">' . _uc('Package Description') . '</td>
                            <td colspan="3">
                              <textarea class="shipLabel_pkg_desc form-control"></textarea>
                            </td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Packaging Code') . '</td>
                            <td colspan="3">
                              <select class="form-control shipLabel_pkg_code">
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                              </select>
                            </td>
                          </tr>
                
                          <tr>
                            <td colspan="3">' . _uc('Package Measurement Code') . '</td>
                            <td colspan="3">
                              <select class="form-control shipLabel_pkg_mea_code">
                                <option value="KGS">Kilo Gram (KGS)</option>
                                <option value="PND">Pounds (PND)</option>
                              </select>
                            </td>
                          </tr>
                
                
                
                          <tr>
                            <td colspan="3">' . _uc('Package Weight') . '</td>
                            <td colspan="3">
                              <input class="shipLabel_pkg_weight form-control" value="1">
                            </td>
                          </tr>
                
                          <thead>
                            <th colspan="6">
                              <div class="text-center">' . _uc('Products') . '</div>
                            </th>
                          </thead>
                
                          <tr>
                            <td>' . _uc('Product Name') . '</td>
                            <td>' . _uc('Qty') . '</td>
                            <td>' . _uc('Price') . '</td>
                            <td>' . _uc('Unit') . '</td>
                            <td>' . _uc('Commodity Code') . '</td>
                            <td>' . _uc('Part Number') . '</td>
                          </tr>';
                          
                          $pdata = $this->invoice_c->invoiceProduct($orderId);
                          foreach ($pdata as $p) {
                            $pQty = $p['order_pQty'];
                            $total = $p['order_salePrice'] * $pQty;
                
                            $discount = $p['order_discount'];
                            $totalDiscount += $discount * $pQty;
                
                            $saleIn = (($total / $pQty) - ($discount));
                            $saleIn = round($saleIn, 2);
                          
                            $modal .= '<tr class="order_products_row">
                              <td><input class="proName form-control" value="' . $p['order_pName'] . '" readonly /></td>
                              <td><input class="proQty form-control" value="' . $p['order_pQty'] . '" /></td>
                              <td><input class="proPrice form-control" value="' . $saleIn / 2 . '" /></td>
                              <td>
                                <select class="form-control proUnit">
                                  <option value="PC-PIECES">PIECES</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-control proCommodityCode" onchange="proCommodityCode(this)">
                                  <option value="4203.1001">4203.1001</option>
                                  <option value="9506.9908">9506.9908</option>
                                  <option value="6506.1000">6506.1000</option>
                                  <option value="3926.2000">3926.2000</option>
                                  <option value="4203.2100">4203.2100</option>
                                </select>
                
                              </td>
                              <td><input class="proPartNo form-control" value="PROTECTION GARMENT" /></td>
                
                            </tr>';
                          }
                
                
                
                        $modal .= '</table>
                      </div>
                
                
                      <div class="modal-footer">
                        <button id="shipping_label_dismiss_btnNewCh" type="button" data-id="no"
                          class="btn btn-dark shipping_label_dismiss_btnNewCh">Submit</button>
                        <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                
                    </div>
                  </div>
                </div>';
        }else{
            $invForLable = @explode("_", $data['manuall_id'])[1];
            $invForLable = (isset($invForLable) && !empty($invForLable)) ? $invForLable : $data['invoice_id'];
            $modal .= '<div class="modal fade" id="checkoutOfferModalForShippingLabelNo" tabindex="-1" role="dialog"
              aria-labelledby="ForShippingLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
            
                <div class="modal-content">
            
                  <div class="modal-header">
                    <h4 class="modal-title" id="ForShippingLabel">Shipping Label</h4>
                  </div>

                  <div class="modal-body ">
                    <div>
                      <p id="shippingLabelLink"></p>
                    </div>
            
                    <input type="hidden" class="shipLabel_country_code" value="">
                    <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                      cellspacing="0">
                      <thead>
                        <th colspan="6">
                          <div class="text-center">' . _uc($_e['Invoice Detail']) . '</div>
                        </th>
                      </thead>
                      <tr>
                        <td colspan="3">' . _uc($_e['Invoice ID']) . '</td>
                        <td colspan="3">
                          <input class="shipLabel_inv_id form-control" value="' . $invForLable . '" readonly>
                          <input type="hidden" class="shipLabel_countryName form-control"
                            value="' . $data['shippingCountry'] . '">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Order ID']) . '</td>
                        <td colspan="3"><input class="shipLabel_order_id form-control"
                            value="' . $data['order_invoice_pk'] . '" readonly></td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Order Date']) . '</td>
                        <td colspan="3"><input class="form-control" value="' . $data['invoice_date'] . '" readonly></td>
                        <input type="hidden" class="shipLabel_order_date"
                          value="' . date('Ymd', strtotime($data['invoice_date'])) . '">
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Reason For Export']) . '</td>
                        <td colspan="3"><input class="shipLabel_res_for_exp form-control" value="SALE"></td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Currency Code']) . '</td>
                        <td colspan="3"><input class="shipLabel_curr_code form-control"
                            value="' . (($data['price_code'] == 'EURO' || $data['price_code'] == 'euro') ? 'EUR' : $data['price_code']) . '">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Frieght Charges']) . '</td>
                        <td colspan="3"><input class="shipLabel_frieght_charges form-control" value=""></td>
                      </tr>
                      <thead>
                        <th colspan="6">
                          <div class="text-center">' . _uc($_e['Customer Information']) . '</div>
                        </th>
                      </thead>
                      <tr>
                        <td colspan="3">' . _uc($_e['CUSTOMER NAME']) . '</td>
                        <td colspan="3"><input class="shipLabel_cus_name form-control"
                            value="' . $data['receiver_name'] . '"></td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['CUSTOMER EMAIL']) . '</td>
                        <td colspan="3"><input class="shipLabel_cus_email form-control"
                            value="' . $data['receiver_email'] . '"></td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['COSTUMER ADDRESS']) . '</td>
                        <td colspan="3">
                          <textarea class="shipLabel_cus_add form-control">' . $data['receiver_address'] . '</textarea>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Contact No']) . '</td>
                        <td colspan="3"><input class="shipLabel_contact_no form-control"
                            value="' . $data['receiver_phone'] . '"></td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Postal Code']) . '</td>
                        <td colspan="3"><input class="shipLabel_post_code form-control"
                            value="' . $data['receiver_post'] . '"></td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['City']) . '</td>
                        <td colspan="3"><input class="shipLabel_city form-control" value="' . $data['receiver_city'] . '">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Province Code']) . '</td>
                        <td colspan="3"><input class="shipLabel_province_code form-control" value=""></td>
                      </tr>
                      <thead>
                        <th colspan="6">
                          <div class="text-center">' . _uc($_e['Package Information']) . '</div>
                        </th>
                      </thead>
                      <tr>
                        <td colspan="3">' . _uc($_e['Package Description']) . '</td>
                        <td colspan="3">
                          <textarea class="shipLabel_pkg_desc form-control"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Packaging Code']) . '</td>
                        <td colspan="3">
                          <select class="form-control shipLabel_pkg_code">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Package Measurement Code']) . '</td>
                        <td colspan="3">
                          <select class="form-control shipLabel_pkg_mea_code">
                            <option value="KGS">Kilo Gram (KGS)</option>
                            <option value="PND">Pounds (PND)</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">' . _uc($_e['Package Weight']) . '</td>
                        <td colspan="3">
                          <input class="shipLabel_pkg_weight form-control" value="1">
                        </td>
                      </tr>
                      <thead>
                        <th colspan="6">
                          <div class="text-center">' . _uc($_e['Products']) . '</div>
                        </th>
                      </thead>
                      <tr>
                        <td>' . _uc($_e['Product Name']) . '</td>
                        <td>' . _uc($_e['Qty']) . '</td>
                        <td>' . _uc($_e['Price']) . '</td>
                        <td>' . _uc($_e['Unit']) . '</td>
                        <td>' . _uc($_e['Commodity Code']) . '</td>
                        <td>' . _uc($_e['Part Number']) . '</td>
                      </tr>';
                      
                      $pdata = $this->invoice_c->invoiceProduct($orderId);
                      foreach ($pdata as $p) {
                        $pQty = $p['order_pQty'];
                        $total = $p['order_salePrice'] * $pQty;
            
                        $discount = $p['order_discount'];
                        $totalDiscount += $discount * $pQty;
            
                        $saleIn = (($total / $pQty) - ($discount));
                        $saleIn = round($saleIn, 2);
            
                        $modal .= '<tr class="order_products_row">
                          <td><input class="proName form-control" value="' . $p['order_pName'] . '" readonly />
                          </td>
                          <td><input class="proQty form-control" value="' . $p['order_pQty'] . '" /></td>
                          <td><input class="proPrice form-control" value="' . $saleIn / 2 . '" /></td>
                          <td>
                            <select class="form-control proUnit">
                              <option value="PC-PIECES">PIECES</option>
                            </select>
                          </td>
                          <td>
                            <select class="form-control proCommodityCode" onchange="proCommodityCode(this)">
                              <option value="4203.1001">4203.1001</option>
                              <option value="9506.9908">9506.9908</option>
                              <option value="6506.1000">6506.1000</option>
                              <option value="3926.2000">3926.2000</option>
                              <option value="4203.2100">4203.2100</option>
                            </select>
                            </select>
                          </td>
                          <td><input class="proPartNo form-control" value="PROTECTION GARMENT" /></td>
                        </tr>';
                      }
            
                    $modal .= '</table>
                  </div>
            
                  <div class="modal-footer">
                    <button id="shipping_label_dismiss_btnNewNo" type="button" data-id="no"
                      class="btn btn-dark shipping_label_dismiss_btnNewNo">Submit</button>
                    <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                  
                </div>
              </div>
            </div>';
        }
        echo $modal;
    }
}

?>