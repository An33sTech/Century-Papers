<?php
_traitFileInclude("webCart.php");

class webProduct_functions extends object_class
{
    use webcart;

    public $product;
    public $productF;
    public $webClass;
    private $totalProductFrNav;
    private $productLimit;
    public $comparePage = false;

    function __construct()
    {
        parent::__construct('3');
        $this->functions->includeAdminFile("product_management/functions/product_function.php");
        $this->functions->includeAdminFile("product/classes/product.class.php");
        $this->functions->_modelFile("classes/rating.php");


        $this->product = new product();
        $this->productF = new product_function();
        @$this->rating = new rating();
        if (isset($GLOBALS['webClass'])) {
            $this->webClass = $GLOBALS['webClass'];
        }


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        $_w = array();
        $_w['Products'] = '';
        $_w['Close'] = '';
        $_w['No Product Found'] = '';
        $_w['Price'] = '';
        $_w['In Stock'] = '';
        $_w['Out Stock'] = '';
        $_w['Color'] = '';
        $_w['DISCOUNT'] = '';
        $_w['Size'] = '';
        $_w['No Product Found'] = '';
        $_w['Specials'] = '';
        $_w['Collection by Designer'] = '';
        $_w['Compare Product'] = '';
        $_w['Add to WishList'] = '';
        $_w['BUY'] = '';
        $_w['Details'] = '';
        $_w['Show Details'] = '';
        $_w['Product Quick View'] = '';
        $_w['Email'] = '';
        $_w['Three For Two Category'] = '';
        $_w['SALE'] = '';
        $_w['Subscribe'] = '';
        $_w['Close'] = '';
        $_w['Free Gift'] = '';

        $_w['From'] = '';
        $_w['Lagg till i varukorgen'] = '';
        $_w['Custom'] = '';
        $_w['Subscribe Successfully'] = '';
        $_w['Subscribe Fail'] = '';
        $_w['Refer To Friend'] = '';
        $_w['Refer to a Friend Description'] = '';
        $_w['Email Send Successfully'] = '';
        $_w['Email Send Fail'] = '';
        $_w['Send Coupon Code'] = '';
        $_w['CouponOffer'] = '';
        $_w['Enter your email and get Latest Coupons Code'] = '';
        $_w['Add To Cart'] = '';
        $_w['Add To Wishlist'] = '';
        $_w['Check Out Offer But Now & Get Special Discount'] = '';
        $_w['I Accept'] = '';
        $_w['Order Code'] = '';
        $_w['User Info'] = '';
        $_w['User Name'] = '';
        $_w['Sale Trigger Form'] = '';
        $_w['Stock Trigger Form'] = '';
        $_w['Email'] = '';
        $_w['Contact'] = '';
        $_w['Menu'] = '';
        $_w['Product Name'] = '';
        $_w['Number Which Claims In'] = '';
        $_w['Get New Item'] = '';
        $_w['Get Money Back'] = '';
        $_w['Want to switch to another product or get your money back?'] = '';
        $_w['Buy Back'] = '';
        $_w['Name of your bank'] = '';
        $_w['sortCode'] = '';
        $_w['Account Number'] = '';
        $_w['When Replacing'] = '';
        $_w['I want to change to'] = '';
        $_w['Message'] = '';
        $_w['Submit'] = '';
        $_w['Submit DateTime'] = '';
        $_w['Edit custom size form'] = '';
        $_w['User not fill final form'] = '';
        $_w['Submit now, But i will fill this form later'] = '';
        $_w['Defect Image'] = '';
        $_w['Return Product Save Successfully'] = '';
        $_w['Product add to cart successfully'] = '';
        $_w['Print PDF'] = '';
        $_w['Send To Factory'] = '';
        $_w['Your Gift card Id "{{giftId}}"  will be charged {{cartPrice}} from {{giftPrice}}'] = '';
        $_w['Gift Card Id is Not Valid.'] = '';
        $_w['You have low price in you Gift card :{{giftPrice}}'] = '';
        $_w['Your Gift card in ( {{giftCurrency}} ) currency, and not valid for ( {{cartCurrency}} ) currency'] = '';


        $_w['Save'] = '';
        $_w['You save'] = '';


        $_w['Default'] = '';
        $_w['Recommends'] = '';
        $_w['By Low Price'] = '';
        $_w['By High Price'] = '';
        $_w['By Low Rate'] = '';
        $_w['By High Rate'] = '';
        $_w['By Low View'] = '';
        $_w['By Top View'] = '';
        $_w['By Low Sale'] = '';
        $_w['By Top Sale'] = '';
        $_w['Show'] = '';
        $_w['EDIT'] = '';
        $_w['SUBTOTAL'] = '';
        $_w['ESTIMATED DELIVERY & HANDLING'] = '';
        $_w['TOTAL'] = '';
        $_w['REMOVE'] = '';
        $_w['YOUR CART'] = '';
        $_w['ITEM(s)'] = '';
        $_w['COUNT(*)'] = '';
        $_w['Payment Type'] = '';
        $_w['NEXT STEP'] = '';
        $_w['DELIVERY'] = '';
        $_w['SUMMARY'] = '';
        $_w['Klarna Facture - Order now pay later'] = '';
        $_w['ESTIMATED DELIVERY & HANDLING'] = '';
        $_w['Click to view your previous orders OR'] = '';
        $_w['Continue Shopping'] = '';
        $_w['Click to view your invoice OR'] = '';
        $_w['ORDER PREVIEW'] = '';
        $_w['Klarna = Faktura, Delbetalning, Kort & Internetbank'] = '';
        $_w['CHECK OUT'] = '';
        $_w['Payment Option'] = '';
        $_w['Billing Country'] = '';
        $_w['You Get +{{free_qty}} free'] = '';
        $_w['Buy {{buy_qty}} Get 1 free'] = '';
        $_w['Subscription for stock availability'] = '';
        $_w['Fill The Data'] = '';
        $_w['By Latest Added'] = '';
        $_w['By Best Seller'] = '';
        $_w['Search By Date Range'] = '';
        $_w['You are already subscribed on this sale offer'] = '';
        $_w['You are already subscribed for this product'] = '';
        $_w['View'] = '';
        $_w['Select Color'] = '';
        $_w['Select Scale'] = '';
        $_w['Sale'] = '';
        $_w['ADD TO BAG'] = '';
        $_w['Package Deal'] = '';
        $_w['Bundle Category'] = '';
        $_w['Bundle feature applies:'] = '';
        $_w['pcs for'] = '';
        $_w['BESTSELLER CATEGORY'] = '';
        $_w['Buy to'] = '';
        $_w['Do not forget to buy to'] = '';
        $_w['No thanks !'] = '';
        $_w['Read More'] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';
        $_w[''] = '';

        $_e = $this->dbF->hardWordsMulti($_w, currentWebLanguage(), 'Website Products');
    }

    public function setStore()
    {
        //Not Proper,,, Which is default and which store user select Not define
        $sql = "SELECT * FROM `store_name` ORDER BY store_pk ASC ";
        $data = $this->dbF->getRow($sql);
        $_SESSION['webUser']['store_Id'] = $data['store_pk'];
    }

    public function getStoreId()
    {
        //Not Proper,,, Which is default and which store user select Not define
        if (isset($_SESSION['webUser']['store_Id'])) {

        } else {
            $sql = "SELECT * FROM `store_name` ORDER BY store_pk ASC ";
            $data = $this->dbF->getRow($sql);
            $_SESSION['webUser']['store_Id'] = $data['store_pk'];
        }
        return $_SESSION['webUser']['store_Id'];
    }

    public function setMultiCurrency()
    {
        if (isset($_GET['currency']) && intval($_GET['currency']) > 0 && cur_define == false) {
            unset($_SESSION['klarna_checkout']);
            $currData = $this->productF->currencyInfo($_GET['currency'], true);
            $this->setDefaultCurrencySession($currData);
        } elseif (isset($_GET['currency']) && intval($_GET['currency']) > 0) {
            $currData = $this->productF->currencyInfo($_GET['currency'], true);
            $this->setDefaultCurrencySession($currData);
        }
    }

    public function multiCurrency($openRight = false)
    {
        $currentCurrency = $this->currentCurrencySymbol();
        $temp = "";
        if ($openRight) {
            $openRight = " dropdown-menu-right";
        } else {
            $openRight = "";
        }
        $temp .= '<div class="dropdown">
<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
' . $currentCurrency . '
<span class="caret"></span>
</button>
<ul class="dropdown-menu ' . $openRight . '" role="menu" aria-labelledby="dropdownMenu1">';

        $sql = "SELECT * FROM `currency` ORDER BY cur_id";
        $data = $this->dbF->getRows($sql);
        $link = $this->functions->defaultHttp . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parm = false;
        if (!empty($_GET)) {
            $parm = true;
            if (isset($_GET['currency'])) {
                $old = $_GET['currency'];
                foreach ($data as $val) {
                    $link = str_replace("&currency=$val[cur_id]", "", $link);
                    $link = str_replace("?currency=$val[cur_id]", "?", $link);
                }
            }
        }

        foreach ($data as $val) {
            $link2 = $link;
            if ($parm) {
                $link2 .= "&currency=$val[cur_id]";
            } else {
                $link2 .= "?currency=$val[cur_id]";
            }
            $active = '';
            if ($currentCurrency == $val['cur_symbol']) {
                $active = 'active';
            }

            $temp .= "<li class='$active' role='presentation'><a role='menuitem' tabindex='-1' href='$link2'>$val[cur_symbol] $val[cur_name]</a></li>";
        }

        $temp .= '</ul>
</div>';
        return $temp;
    }

    public function multiCurrencyCustom()
    {
        $currentCurrency = $this->currentCurrencySymbol();
        $currentCurrencyH = $currentCurrency . " - " . $this->currentCurrencyCountry(true);
        $temp = "";

        $temp .= '<h6>' . $currentCurrencyH . '</h6>
<!--<div class="more_currency"> -->';

        return $temp;

        $sql = "SELECT * FROM `currency` ORDER BY cur_id";
        $data = $this->dbF->getRows($sql);
        $link = $this->functions->defaultHttp . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parm = false;
        if (!empty($_GET)) {
            $parm = true;
            if (isset($_GET['currency'])) {
                $old = $_GET['currency'];
                foreach ($data as $val) {
                    $link = str_replace("&currency=$val[cur_id]", "", $link);
                    $link = str_replace("?currency=$val[cur_id]", "?", $link);
                }
            }
        }

        foreach ($data as $val) {
            $link2 = $link;
            if ($parm) {
                $link2 .= "&currency=$val[cur_id]";
            } else {
                $link2 .= "?currency=$val[cur_id]";
            }
            $active = '';
            if ($currentCurrency == $val['cur_symbol']) {
                $active = 'active';
            }
            $countryKey = $val['cur_country'];
            $countryName = $this->functions->countryFullName($countryKey);

            $temp .= " <a href='$link2' class='$active'>$val[cur_symbol] - $countryName</a>";
        }

        $temp .= '
</div>';
        return $temp;
    }

    public function currentCurrencyId()
    {
        if (!isset($_SESSION['webUser']['currencyId'])) {
            $this->setDefaultCurrencySession();
        }
        $currId = $_SESSION['webUser']['currencyId'];
        return $currId;
    }

    public function currentCurrencyCountry($fullname = false)
    {

        if (!isset($_SESSION['webUser']['currencyC'])) {
            $this->setDefaultCurrencySession();
        }
        $currC = $_SESSION['webUser']['currencyC'];
        if ($fullname == true) {
            $currC = $this->functions->countryFullName($currC);
        }
        return $currC;
    }

    public function currentCurrencySymbol()
    {
        if (!isset($_SESSION['webUser']['currencySymbol'])) {
            $this->setDefaultCurrencySession();
        }
        $currSymbol = $_SESSION['webUser']['currencySymbol'];
        return $currSymbol;
    }

    private function setDefaultCurrencySession($data = false)
    {
        if ($data == false) {
            $currCountry = $this->functions->ibms_setting('Default Web_Price_Country');
            $data = $this->productF->currencyInfo($currCountry);
        }
        $_SESSION['webUser']['currencyId'] = $data['cur_id'];
        $_SESSION['webUser']['currencySymbol'] = $data['cur_symbol'];
        $_SESSION['webUser']['currencyC'] = $data['cur_country'];
    }

    public function currentCountry()
    {

        // country same as currency
        $country = $this->currentCurrencyCountry();

        return $country;
    }

    public function bestSellerProductByDays($days = 30, $end_limit = 5, $limitT0 = false, $QuickViewId = false, $viewType = true)
    {


        $sql = "SELECT order_pIds FROM `order_invoice_product` WHERE order_invoice_id in (SELECT order_invoice_pk FROM `order_invoice` WHERE invoice_date BETWEEN (CURRENT_DATE() - INTERVAL $days DAY) AND CURRENT_DATE() ORDER BY `dateTime` DESC) ORDER BY `order_invoice_product`.`invoice_product_pk` DESC";
        $productIds = $this->dbF->getRows($sql);

        $temp = array();
        foreach ($productIds as $key => $val) {
            $id = explode('-', $val['order_pIds'])[0];
            if (isset($temp[$id])) {
                $con = ($temp[$id]);
                $temp[$id] = ($con + 1);
            } else {
                $temp[$id] = 1;
            }

        }

        // sorting data
        arsort($temp);
        $ran = "";
        if (count($temp) > $end_limit) {
            $ran = array_rand($temp, $end_limit);
        } else {
            $ran = $temp;
        }


        $tempIds = implode(",", $ran);

        $sql2 = "SELECT * FROM `proudct_detail` WHERE `prodet_id` in ($tempIds) ";
        $productId = $this->dbF->getRows($sql2);

        $array['viewType'] = $viewType;
        return $this->productPrint($productId, $QuickViewId, '', $array);

    }
    public function bestSellerProducts__($limitT0 = false, $QuickViewId = false, $viewType = true)
    {
        $limitFrom = 0;
        if ($limitT0 == false) {
            $limitT0 = abs(intval($this->functions->ibms_setting('bestSellerProductLimit')));
            if ($limitT0 <= 0) {
                $limitT0 = 20;
            }
        }

        $sql = " SELECT * FROM `best_seller_products` b
LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
WHERE b.publish='1' ORDER BY b.SORT ASC ";
        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);
        $array['viewType'] = $viewType;
        return $this->productPrint($productIds, $QuickViewId, '', $array);
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

    public function productDateIsReadyForLaunch($pId)
    {
        $today = date('m/d/Y');
        $sql = "SELECT * FROM `product_setting`
WHERE
`p_id` = '$pId' AND `setting_name` = 'launchDate'
AND `setting_val` <= '$today'";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0)
            return true;//yes ready for launch
        return false; // not ready for launch
    }

    public function get_product_view($cat_id = false)
    {
        $this->functions->modelClasFile("category.php");
        $category_c = new p_category();
        return $category_c->get_view_option($cat_id);
    }

    public $productSerial = 0;

    public function pBox($pId, $wishList = false, $view = true, $name = "")
    {
        global $_e;
        $className = empty($name) ? "" : "." . $name;

        //Allow with no qty on web will add in setting


        $sql = "SELECT set_id FROM `product_setting` where setting_name='freeGift' and setting_val='1' and p_id ='$pId'";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0) {


            if (!$this->productF->hasStock($pId)) {
                if (isset($_SESSION["expressMode"]) && $_SESSION["expressMode"] == "on") {
                    return false;
                }
                if ($this->functions->ibms_setting('no_inventory_product_show_onWeb') == 'yes') {
                    return false;
                }
            }



        } else {


            if (!$this->productF->hasStock($pId)) {
                if (isset($_SESSION["expressMode"]) && $_SESSION["expressMode"] == "on") {
                    return false;
                }
                if ($this->functions->ibms_setting('no_inventory_product_show_onWeb') == 'no') {
                    return false;
                }
            }



        }

        $data = $this->productData($pId);

        if (!$this->dbF->rowCount) {
            return false;
        }

        $productSlug = $data['slug'];

        if (!$this->productDateIsReadyForLaunch($pId)) {
            return false;
        }

        $webLang = currentWebLanguage();
        $loaderGif = WEB_URL . '/images/loader.gif';
        $serialNo = $this->productSerial = $this->productSerial + 1;

        $productScales = $this->productScales($pId);
        // if(strpos($productScales, '<li>') == false){
        //     $productScales = '<ul><li>No stock available</li></ul>';
        // }
        $productImage = $this->productSpecialImage($pId, 'main');

        if ($productImage == "") {
            $productImage = "default.jpg";
        }
        $productBigImage = $productImage;
        $productImage = $this->functions->resizeImage($productImage, '300', '500', false);

        $productHoverImage = $this->productSpecialImage($pId, 'hover');
        if ($productHoverImage == "") {
            $productHoverImage = "default.jpg";
        }
        $productHoverImage = $this->functions->resizeImage($productHoverImage, '300', '500', false);

        $showAllImages = false; // make it false if you dont need all images, so this query will not execute => save time
        if ($showAllImages) {
            $images = $this->productAllImage($pId, 3);
            $smallImages = "";
            foreach ($images as $val) {
                $smlImage = $this->functions->resizeImage($val['image'], '350', '350', false);
                $smallImages .= "<img src='$smlImage' loading='lazy'/>";
            }
        }



        $_uid = $_SESSION['webUser']['tempId'];

        $rate = $this->rating->ratingInfo($pId, false, $_uid);

        $rate_star = "";

        if ($rate != "") {



            $rate_avg = $rate['avg'];

            $rate_full = $rate['noOfVotes'];

            for ($i = 1; $i <= 5; $i++) {

                $class = "";

                if ($rate_full >= $i || $rate_avg >= ($i - 0.2)) {

                    $rate_star .= '<img src="https://sharkspeed.com/webImages/35.png" loading="lazy" alt="star"> ';

                } else if ($rate_avg > $i - 0.8) {


                    $rate_star .= ' <img src="https://sharkspeed.com/webImages/34.png" loading="lazy" alt="star-half">';

                } else {
                    $rate_star .= '';

                }

            }

        }



        $pName = ucwords(strtolower(translateFromSerialize($data['prodet_name'])));
        $shortDesc = translateFromSerialize($data['prodet_shortDesc']);

        $currencyId = $this->currentCurrencyId();
        $ngCurrencySymbol = $currencySymbol = $this->currentCurrencySymbol();

        $storeId = $this->getStoreId(); ///////////EDited by MUD 9/15/2017

        $ngpPriceData = $pPriceData = $this->productF->productPrice($pId, $currencyId);
        //$pPriceData Return , currency id,international shipping, price, id,
        $ngpPriceActual = $pPriceActual = $pPriceData['propri_price'];
        $pPrice = $pPriceActual;

        $ngDiscount = $discount = $this->productF->productDiscount($pId, $currencyId);
        @$discountFormat = $discount['discountFormat'];
        @$discountP = $discount['discount'];

        $discountValue = $discountP;
        if ($discountFormat == "percent") {
            $discountValue .= " %";
        } else {
            $discountValue = $discountValue . " $currencySymbol";
        }

        /////////////********EDited by MUD 9/15/2017*****/////////////////

        $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
        $inventoryLimit = ($inventoryLimit == '1' ? true : false);

        $hasScaleVal = $this->functions->developer_setting('product_Scale');
        $hasColorVal = $this->functions->developer_setting('product_color');

        $hasWebOrder_with_Scale = $this->functions->developer_setting('webOrder_with_Scale');
        $hasWebOrder_with_color = $this->functions->developer_setting('webOrder_with_color');

        $hasScale = ($hasScaleVal == '1' ? true : false);
        $hasColor = ($hasColorVal == '1' ? true : false);

        if ($inventoryLimit) {
            $getInfo = $this->inventoryReport($pId);
        } else {
            $getInfo = $this->productSclaeColorReport($pId);
        }
        $getInfoReport = $getInfo['report'];

        if ($getInfo['scale'] == false && @$hasWebOrder_with_Scale == '0') {

            //if scale not found then make scale data empty,
//if product scale allow from setting and dont have inventory, it will make scale val to 0
// we will assume scale not allow from setting for javascript
            $scaleDiv = "";
            $hasScaleVal = 0;
            $hasScale = false;
        }

        if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {
            //if color not found then make color data empty,
//if product color allow from setting and dont have inventory, it will make color val to 0
// we will assume color not all ow from setting for javascript

            $colorDiv = "";
            $hasColorVal = 0;
            $hasColor = false;
        }

        $randVal = rand(99, 999999);

        $colorDiv = "";
        if ($hasColor) {
            $colorDiv = $this->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
            $colorDiv = "<div class='container_detail_RightR_color_heading'>
<dl id='sample_" . $randVal . "_" . $pId . "' class='dropdown'> <dt><a> <span>" . $_e['Select Color'] . "</span></a></dt><dd>
<ul> " . $colorDiv . "    </ul>
</dd></dl>" . "</div>";

        }


        if ($hasScale) {

            $scaleDiv = $this->getScalesDiv($pId, $hasColor, $storeId, $currencyId, $currencySymbol);

        }




        //Custom Size
        if ($this->functions->developer_setting('product_customSize') == '1') {
            $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
            $customData = $this->dbF->getRows($sql);

            if (!empty($customData)) {
                @$customSize = $customData[0]['type_id'];
                if ($customSize != "0" && !empty($customSize)) {
                    $isCustomSize = true;

                    $customSizeForm = $this->customSizeForm($pId, $customSize);
                    if ($customSizeForm == false) {
                        $isCustomSize = true;
                        $customSize = 0;
                    } else {
                        $customSize_price = $this->customSizeArrayFilter($customData, $currencyId);
                        $customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
                        $onclick = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '" id="custom_size_button" ';
                        $scaleDiv = $scaleDiv . $this->getScaleDivFormat($pId, $_e['Custom'], -1, $customSize_price, -1, '', $onclick);
                    }
                }//if customSize==0 end
            } //if customData end
        } // if developer setting end
//Custom Size End



        if (!empty($scaleDiv)) {
            $scaleDiv = "<div class='container_detail_RightR_color_heading'>
<dl id='sample_select_" . $randVal . "_" . $pId . "' class='dropdown_select'> <dt><a> <span>" . $_e['Select Scale'] . "</span></a></dt><dd>
<ul> " . $scaleDiv . "    </ul>
</dd></dl>" . "</div>";
        }

        /////////////********EDited by MUD 9/15/2017*****/////////////////


        $discountPrice = $this->productF->discountPriceCalculation($pPriceActual, $discount);
        $newPrice = $pPriceActual - $discountPrice;

        $isSale = false;
        if (isset($discount['isSale']) && $discount['isSale'] == '1') {
            $isSale = true;
        }

        $isDiscount = false;
        if ($newPrice != $pPrice) {
            $isDiscount = true;
        }

        $pPrice = empty($pPrice) ? 0 : $pPrice;
        $newPrice = empty($newPrice) ? 0 : $newPrice;
        $pPrice .= '<span class="currencysymbol">' . $currencySymbol . '</span>';
        $newPrice .= '<span class="currencysymbol">' . $currencySymbol . '</span>';

        $hasDiscount = false;

        if ($newPrice != $pPrice && $pPriceActual != '') {
            $hasDiscount = true;
            $oldPriceNormal = $pPrice;
            $newPriceNormal = $newPrice;
            $oldPriceDiv = '<h6>' . $pPriceActual . '<span class="currencysymbol">' . $currencySymbol . '</span></h6>';
            $newPriceDiv = '<h4>' . $newPrice . '</h4>';
        } else {
            $oldPriceNormal = "";
            $newPriceNormal = $pPrice;
            $oldPriceDiv = "";
            $newPriceDiv = '<h4>' . $newPrice . '</h4>';
        }

        /*Update Your Product Box Here... BY using Upper Variables*/
        $wishListRemove = "";
        if ($wishList) {
            $wishListRemove = "<div class='wishListRemove cursor' onclick='WishListRemove(this,$pId)'>X</div>";
        }

        $chk_link = "/" . $this->db->productDetail . $productSlug;


        $sql = "SELECT `slug`,`id` FROM `seo` WHERE `pageLink` = ?";
        $data = $this->dbF->getRow($sql, array($chk_link));
        if ($this->dbF->rowCount > 0) {
            $webLang = currentWebLanguage();
            $seoid = $data['id'];
            $sql_seo_slug = "SELECT slug FROM seo_slug WHERE seo_id = '$seoid' and lang = '$webLang' and slug != ''";
            $check_seo_slug = $this->dbF->getRow($sql_seo_slug);
            if ($this->dbF->rowCount > 0) {
                $link = WEB_URL . "/" . $check_seo_slug['slug'];
            } else {
                $link = WEB_URL . "/" . $data['slug'];
            }
        } else {
            $link = WEB_URL . "/$productSlug";
        }



        $Qucicklink = WEB_URL . "/quickView.php?pId=$pId";

        $saleDiv = "";
        if ($isSale) {
            //Sale
            $saleDiv = "
<div class='discount_tag'>
<h4>$discountValue</h4>
<h5>" . $_e['SALE'] . "</h5>
</div>";
        } else if ($isDiscount) {
            //Discount
            $saleDiv = "
<div class='discount_tag'>
<h4>$discountValue</h4>
<h5>" . $_e['DISCOUNT'] . "</h5>
</div>";
        }

        $saleDivDis = "";
        $saleDivDisVal = "";
        $saleDivDisPerc = "";
        if ($isSale) {
            //Sale
            if ($discountFormat == "percent") {
                $saleDivDisPerc = "<!-- <div class='col3_box_tag1'> -->-$discountValue <!-- </div> -->";
            } else {
                $price = explode(" ", $pPrice);
                if (is_numeric($price[0])) {
                    $discount_per = ($discountP / $price[0]) * 100;
                    $discount_per = ceil($discount_per);
                    $saleDivDisPerc = "<!-- <div class='col3_box_tag1'> -->-$discount_per %<!-- </div> -->";
                    $saleDivDisVal = "<div class='col3_box_tag3'>$discountValue " . $_e['SALE'] . "</div>";
                }
            }

        } else if ($isDiscount) {
            //Discount
            if ($discountFormat == "percent") {
                $saleDivDisPerc = "<!-- <div class='col3_box_tag1'> -->-$discountValue<!-- </div> -->";
            } else {
                $price = explode(" ", $pPrice);
                @$discount_per = ($discountP / $price[0]) * 100;
                $discount_per = ceil($discount_per);
                $saleDivDisPerc = "<!-- <div class='col3_box_tag1'> -->-$discount_per%<!-- </div> -->";
                $saleDivDisVal = "<div class='col3_box_tag3'>$discountValue " . $_e['DISCOUNT'] . "</div>";
            }
        }
        /**
         * For Product Quick View, Also need to call function productQuickViewModel(); in footer
         * <a class="" data-toggle="modal" data-target="#paroductQuickView" onclick="quickView(this,$pId);">
         *
         *
         * </a>
         */

        //Which view Print
//Default View
//Use pBox Class in main product div
        $webUrl = WEB_URL;
        $wishlistT = _u($_e['Add to WishList']);
        $buyT = _u($_e['BUY']);
        $detailsT = _u($_e['Details']);
        $showDetailsT = _u($_e['Show Details']);
        $viewT = _u($_e['Lagg till i varukorgen']);
        $bestCat = _u($_e['BESTSELLER CATEGORY']);
        $recommends = _u($_e['Recommends']);


        ######### 3 For 2 Category ########
        $three_for_2_category = $this->productF->check_product_in_3_for_2($pId);
        if ($three_for_2_category) {
            $three_for_2_category = "<div class='three_for_2_icon'><img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' loading='lazy'/></div>";
        } else {
            $three_for_2_category = "";
        }

        ######### Staple Product Category ########
        $staple_product_category1 = $this->productF->check_product_in_staple_cat($pId);
        if ($staple_product_category1) {
            $staple_product_category = "<div class='three_for_2_icon bundle_cat_icon'><img src='" . WEB_URL . "/webImages/bundle_icon.png' alt='bundle' width='60px' height='60px' loading='lazy'/></div>";
        } else {
            $staple_product_category = "";
        }

        $addToCartButton = "


<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>
<input type='hidden' id='hidden_stock_$pId' value='@'/>


<div class='cart1_btn AddToCart_$pId'><a href='javascript:void(0)' onclick='addToCart(this,$pId);'>{$_e['ADD TO BAG']}</a></div> ";
        // arish box
        $temp = '';

        $sqlProductTag = "SELECT `setting_val` FROM `product_setting` WHERE p_id = ? AND setting_name = 'productTags'";
        $sqlProductTagData = $this->dbF->getRow($sqlProductTag, array($pId));

        $productTags = "";
        $productTags .= '<div class="product_tags">';
        if ($sqlProductTagData !== false) {

            $productTagsArray = unserialize($sqlProductTagData['setting_val']);

            for ($i = 0; $i < 4; $i++) {
                if (isset($productTagsArray[$i])) {
                    if ($productTagsArray[$i] == 'New Arrival') {
                        $classTag = 'tag1';
                    } else if ($productTagsArray[$i] == 'Best Seller') {
                        $classTag = 'tag2';
                    } else if ($productTagsArray[$i] == 'Campaign') {
                        $classTag = 'tag3';
                    } else {
                        $classTag = 'tag4';
                    }
                    $t = $this->dbF->hardWords($productTagsArray[$i], false);
                    $productTags .= '<span class="' . $classTag . '">' . $t . '</span>';
                }
            }
        }

        $sqlCustomProductTag = "SELECT `setting_val` FROM `product_setting` WHERE p_id = ? AND setting_name = 'productCustomTags'";
        $sqlCustomProductTagData = $this->dbF->getRow($sqlCustomProductTag, array($pId));

        if ($sqlCustomProductTagData !== false) {
            $productCustomTagsArray = unserialize($sqlCustomProductTagData["setting_val"]);

            if (!empty($productCustomTagsArray[0])) {
                $productCustomTagsArray = explode(',', $productCustomTagsArray[0]);
                if (is_array($productCustomTagsArray)) {
                    foreach ($productCustomTagsArray as $key => $customTags) {
                        $classTag = 'tag4';
                        $t = $this->dbF->hardWords($customTags, false);
                        $productTags .= '<span class="' . $classTag . '">' . $t . '</span>';
                    }
                }
            }
        }
        $productTags .= '</div>';

        if ($view === "List") {
            $viewT = $this->dbF->hardWords('view details', false);
            $shortDesc = strip_tags($shortDesc);
            $temp = <<<HTML

<div class="p_box_white wow fadeInDown pBox list_view" data-id="$pId" data-wow-delay="0.2s">
$wishListRemove

<input type="hidden" class="hidden" value="$discountFormat" id="discountFormat_$pId">
<input type="hidden" class="hidden" value="$discountP" id="discount_$pId">
<input type="hidden" class="hidden" value="$newPrice" id="discountPrice_$pId">

<div class="p_box p_box_list">
$three_for_2_category
<div class="p_box_right hidden-xs p_bright">
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed">
<img src="$productImage" alt="$pName" class="first_img" loading="lazy">

</a>

</div>
<div class="p_box_left p_bleft">

<div class="container-fluid padding-0 text-center visible-xs">
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed">
<img src="$productImage" alt="$pName" class="" loading="lazy">
</a>
</div>

<div class="p_head_text">
<div>
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed" class="p_text_head">$pName</a>
</div>

<div class="p_text_desc">

$shortDesc
</div>

<div class="white_box">
<div class="view_btn">


<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed">$viewT</a>
</div>
</div>

<div class="list_prize ">$newPriceNormal <span> $oldPriceNormal</span></div>


</div>
<!--p_text_desc end-->



</div>
<!--p_box_left end-->
$saleDiv

</div>

</div>
HTML;
        } else if ($view == "Grid") {




            $temp = "
<div class='col3_box 1' data-id='$pId' data-sr='enter bottom and scale up 80% over 1.5s'>
$wishListRemove

<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>

$three_for_2_category
$staple_product_category

<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed'>


<div class='col3_box_img'>
$productTags
<img src='$productImage' alt='$pName' loading='lazy'>

$saleDivDisVal

</div>
<!-- col3_box_img close -->
</a>
<div class='col3_box_detail'>
<div class='col3_box_detail_size'>

$productScales


</div>
<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed'>
<div class='col3_box_detail_txt'> $pName  </div>
<div class='col3_box_detail_price'>



$newPriceDiv";
            if (empty(trim($saleDivDisPerc))) {

            } else {
                $temp .= "<h4 class='discount-price-' style=''>$saleDivDisPerc</h4>";
            }

            $temp .= "$oldPriceDiv 
</div>
<!-- col3_box_detail_price close -->

<!-- col3_box_detail_txt close -->
</a>

<!-- col3_box_detail_size close -->
<div class='col3_star'>

$rate_star




</div>
<!-- col3_star close -->
</div>
<!-- col3_box_detail close -->



</div>
<!-- col3_box close -->
";

        } else if ($view == "SixGrid") {
            $temp = "
<div class='grid_box_white pBox pSixGrid' data-id='$pId'>
$wishListRemove

<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>

<div class='grid_box_outer'>
<div class='grid_box_inner'>
$three_for_2_category
$staple_product_category

<table class='gridbox_right'>
<tr valign='middle'>
<td>
<a href='$link'data-page='$link' data-type='product' title=' $pName - Sharkspeed'>
<img src='$productImage' alt='$pName' class='first_img' loading='lazy'>

</a></td>

</tr>

</table>



$saleDivDis

<div class='mobile_div'>
<div class='product_name'>
<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed' class='pgrid_text_head'>$pName</a> <img src='" . WEB_URL . "/webImages/left-arrow.png' alt='left-arrow' style='margin-top:-1px;' loading='lazy'>
</div>



<div class='pgrid_prize_mob'>
<h4>$newPriceDiv</h4>
<h6>
$oldPriceDiv
</h6>

</div>

</div><!--mobile div end-->
</div><!--grid_box_inner end-->


<div class='pgrid_text'>
<div>
<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed' class='pgrid_text_head'>$pName</a>
</div>

</div> <!--pgrid_text end-->
<div class='pgrid_prize'>
<h4>$newPriceDiv</h4>
<h6>
$oldPriceDiv
</h6>

</div>

<div class='both_icons'>
<div class='basket cursor' onclick='quickView(this,$pId);'></div>
<a class='glyphicon glyphicon-briefcase' href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed'></a>
</div>
</div><!--grid_box _outer outer-->




</div>";

        } else if ($view == "IndexView") {
            $temp = "
<div class='grid_box_white pBox' data-id='$pId'  data-sr='enter bottom and scale up 80% over 1.5s' >
$wishListRemove

<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>

<div class='grid_box_outer'>
<div class='grid_box_inner'>
$three_for_2_category
$staple_product_category


<table class='gridbox_right'>
<tr valign='middle'>
<td>
<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed'>
<img src='$productImage' alt='$pName' class='first_img' loading='lazy'>

</a></td>

</tr>

</table>
$saleDivDis


<div class='mobile_div'>
<div class='product_name'>
<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed' class='pgrid_text_head'>$pName</a> <img src='" . WEB_URL . "/webImages/left-arrow.png' alt='left-arrow' style='margin-top:-1px;' loading='lazy'>
</div>



<div class='pgrid_prize_mob'>
<h4>$newPriceDiv</h4>
<h6>
$oldPriceDiv
</h6>

</div>

</div><!--movie div end-->


</div><!--grid_box_inner end-->


<div class='pgrid_text'>
<div>
<a href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed' class='pgrid_text_head'>$pName</a>
</div>

</div> <!--pgrid_text end-->
<div class='pgrid_prize'>
<h4>$newPriceDiv</h4>
<h6>
$oldPriceDiv
</h6>

</div>

<div class='both_icons'>
<div class='basket cursor' onclick='quickView(this,$pId);'></div>
<a class='glyphicon glyphicon-briefcase' href='$link' data-page='$link' data-type='product' title=' $pName - Sharkspeed'></a>
</div>
</div><!--grid_box _outer outer-->




</div>";

        } else if ($view == '1') {
            $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
            $saleDiv = '';
            if ($isSale) {
                $saleDiv = '<div class="sale_tag">' . $_e['SALE'] . '</div>';
            }
            $productImage = $this->functions->resizeImage($productBigImage, '175', '330', false);
            $productImageb = $this->functions->resizeImage($productBigImage, '150', '300', false);

            $temp = '
<div class="col3_box ">
' . $wishListRemove . '
<a href="' . $link . '" data-page="' . $link . '" data-type="product" title="' . $pName . ' - Sharkspeed">
<div class="col3_box_img"> 
' . $productTags . '
<a href="' . $link . '" data-page="' . $link . '" data-type="product" title="' . $pName . ' - Sharkspeed" class="">

<img src="' . $productImage . '" class="" alt="' . $pName . '" loading="lazy"> 
' . $saleDivDisVal . '
</a>

<!-- col3_box_tag3 close -->
</div>
<!-- col3_box_img close -->
</a>
<div class="col3_box_detail">
<div class="col3_box_detail_size">
                                   ' . $productScales . '
                                </div>

<!-- col3_box_tag1 close -->

<!-- col3_box_tag close -->
<a href="' . $link . '" data-page="' . $link . '" data-type="product" title="' . $pName . ' - Sharkspeed">
<div class="col3_box_detail_txt"> ' . $pName . ' </div>
<!-- col3_box_detail_txt close -->
<div class="col3_box_detail_price">
' . $newPriceDiv . '<h4 class="discount-price-">' . $saleDivDisPerc . '</h4>
' . $oldPriceDiv . '
</div>
<!-- col3_box_detail_price close -->

</a>





<!-- jawwad 1 -->

<div class="' . $name . ' select ' . $scl_emp . '">
    <div id="size_color" class="container_detail_RightR_color choice_color"> 
        ' . $colorDiv . '
    </div>
    <div class="detail_top_r_size" id="size_radio">
        ' . $scaleDiv . '
    </div>
    <div class="padding-0" style=" margin-top: 10px;">
        <small id="stock_' . $pId . '"> </small>
        <input type="hidden" id="hidden_stock_' . $pId . '">
    </div>
</div>
' . $addToCartButton . '
<script>
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    
    $(this).parent().next().find("ul").fadeToggle(600);
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    console.log(this)
    
    });
    
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul li a").unbind("click").on("click",function() {
    var text = $(this).html();  
    
    // show the selected size
    $(this).closest("dd").prev("dt").find("a > span").html(text);
    
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    });
    
    
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    $(this).parent().next().find("ul").fadeToggle(600);
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    console.log("clicked by sample_select_ dt, #sample_select_' . $randVal . '_' . $pId . ' ")
    });
    
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dd a").unbind("click").on("click",function() {                
    // save the selected size
    var size_selected = $(this).find("div.color_name.size").html();
    console.log("clicked by sample_select_ dd")
    // hide the ul
    $(this).closest("ul").hide();
    
    // show the selected size
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' > dt > a > span").html(size_selected);
    });
</script> 
</div>
<!-- col3_box_detail close -->
</div>
<!-- col3_box close -->
';


        } else if ($view == '2') {

            $productImage = $this->functions->resizeImage($productBigImage, '480', '520', false);
            $temp = <<<HTML
<div class="buy_box">
<div class="buy_img"><a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed"><img src="$productImage" alt="$pName" loading="lazy"></a></div>
<div class="buy_text">
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed"><h5>$pName</h5></a>
<div class="r_prize">$newPriceNormal <span> $oldPriceNormal</span></div>
</div>
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed" class="glyphicon glyphicon-briefcase my_cart_img transition_3"></a>

</div><!--buy_box end-->
HTML;
        } else if ($view == 'recommendedProduct') {

            $productImage = $this->functions->resizeImage($productBigImage, '510', '400', false);
            $temp = <<<HTML
<div class="col3_box_main special_box">
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed">
<div class="col3_box_main_img"> <img src="$productImage" alt="$pName" loading="lazy">
<div class="col3_box_main_txt">$recommends</div>
<!-- col3_box_main_txt close -->
<div class="col3_box_main_txt_main">
<h3>$oldPriceNormal</h3>
<h4>$newPriceNormal</h4>
<div class="col3_box_main_txt_main_txt"> $pName </div>
<!-- col3_box_main_txt_main_txt close -->
</div>
<!-- col3_box_main_txt_main close -->
</div>
<!-- col3_box_main_img close -->
</a>
</div>
<!-- col3_box_main close -->
HTML;
        } else if ($view == 'bestProduct') {

            $productImage = $this->functions->resizeImage($productBigImage, '510', '400', false);
            $temp = <<<HTML
<div class="col3_box_main special_box">
<a href="$link" data-page="$link" data-type="product" title=" $pName - Sharkspeed">
<div class="col3_box_main_img"> <img src="$productImage" alt="$pName" loading="lazy">
<div class="col3_box_main_txt">$bestCat</div>
<!-- col3_box_main_txt close -->
<div class="col3_box_main_txt_main">
<h3>$oldPriceNormal</h3>
<h4>$newPriceNormal</h4>
<div class="col3_box_main_txt_main_txt"> $pName </div>
<!-- col3_box_main_txt_main_txt close -->
</div>
<!-- col3_box_main_txt_main close -->
</div>
<!-- col3_box_main_img close -->
</a>
</div>
<!-- col3_box_main close -->
HTML;
        } else if ($view == 'somethingExtra') {
            $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
            $productImage = $this->functions->resizeImage($productBigImage, '510', '400', false);


            $addToCartButton2 = "


<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>
<input type='hidden' id='hidden_stock_$pId' value='@'/>


<div class='cart1_btn AddToCart_$pId'><a href='javascript:void(0)' onclick='addToCart(this,$pId,false, true);'>{$_e['ADD TO BAG']}</a></div> ";


            $temp = '<div class="select ' . $scl_emp . '">
            <div id="size_color" class="container_detail_RightR_color choice_color"> 
                ' . $colorDiv . '
            </div>
            <div class="detail_top_r_size" id="size_radio">
                ' . $scaleDiv . '
            </div>
            <div class="padding-0" style=" margin-top: 10px;">
                <small id="stock_' . $pId . '"> </small>
                <input type="hidden" id="hidden_stock_' . $pId . '">
            </div>
        </div>
        <!-- jawwad -->
' . $addToCartButton2 . '
<script>
    $("#sample_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    
    $(this).parent().next().find("ul").fadeToggle(600);
    $("#sample_select_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    
    });
    
    $("#sample_' . $randVal . '_' . $pId . ' dd ul li a").unbind("click").on("click",function() {
    var text = $(this).html();  
    
    // show the selected size
    $(this).closest("dd").prev("dt").find("a > span").html(text);
    
    $("#sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    });
    
    
    $("#sample_select_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    $(this).parent().next().find("ul").fadeToggle(600);
    $("#sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    });
    
    $("#sample_select_' . $randVal . '_' . $pId . ' dd a").unbind("click").on("click",function() {                
    // save the selected size
    var size_selected = $(this).find("div.color_name.size").html();
    
    // hide the ul
    $(this).closest("ul").hide();
    
    // show the selected size
    $("#sample_select_' . $randVal . '_' . $pId . ' > dt > a > span").html(size_selected);
    });
</script> ';


            $tempPrice = '<div class="col3_box_detail_price">
' . $newPriceDiv . '<h4 class="discount-price-">' . $saleDivDisPerc . '</h4>
' . $oldPriceDiv . '
</div>';



            $temp = <<<HTML


<div class='item'>
    <div class='extra_products'>
        <div class='innerFlex_prod'>
            <div class='img_holder'>
                <img src='{$productImage}' alt='{$pName}' loading='lazy'>
            </div>
            <div class='txt_holder'>
                <div class='prod_title'>
                    <h1>{$pName}</h1>
                    $tempPrice
                   
                </div>
                
                $temp
                
            </div>
        </div>
    </div>
</div>


HTML;
        } else if ($view == 'gearGuidNew') {

            $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
            $productImage = $this->functions->resizeImage($productBigImage, '510', '400', false);


            $addToCartButton2 = "
<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>
<input type='hidden' id='hidden_stock_$pId' value='@'/>

<button class='primary_pbtn AddToCart_$pId' onclick='addToCart(this,$pId);'>{$_e['ADD TO BAG']}</button>
";
            // <div class='cart1_btn AddToCart_$pId'>
// <div class='cart1_btn AddToCart_$pId'><a href='javascript:void(0)' onclick='addToCart(this,$pId,false, true);'>{$_e['ADD TO BAG']}</a></div> ";
// </div>


            $discountPrice = $this->productF->discountPriceCalculation($pPriceActual, $discount);
            $newPrice = $pPriceActual - $discountPrice;



            if ($newPrice != $pPriceActual) {
                $discountPercentage = ($discountPrice / $pPriceActual) * 100;
                $discountPercentage = ceil($discountPercentage);

                $newPriceDiv = '<div class="col3_box_detail_price2">
                    <h4>Pris <span class="mainPrice">' . $newPrice . ' </span><small class="currency">' . $ngCurrencySymbol . ' </small><span class="cutprice">' . $pPriceActual . ' ' . $ngCurrencySymbol . '</span></h4>
                </div>
                <div class="col3_box_save">
                    <h4>' . $_e["You save"] . ' ' . $discountPrice . ' ' . $ngCurrencySymbol . ' (' . $discountPercentage . ' %)</h4>
                </div>';
            } else {
                $newPriceDiv = '<div class="col3_box_detail_price2">
                    <h4>Pris <span class="mainPrice">' . $newPrice . ' </span><small class="currency">' . $ngCurrencySymbol . ' </small></h4>
                </div>';

            }


            $className = empty($name) ? ".gearGuidNew" : "." . $name;


            $temp = <<<HTML


        <div class='p_singlediv gearGuidNew'>
            <div class="imgHolder">
                {$productTags}
                <img src='{$productImage}' alt='{$pName}' loading='lazy'/>
            </div>
            <div class="txtHolder">
                <div class="product_title">
                    <a href="{$link}" data-page="{$link}" data-type="product" title=" {$pName} - Sharkspeed">
                        {$pName}
                    </a>
                </div>
                {$newPriceDiv}
                <div class="select_box4" id="size_radio">
                    {$scaleDiv}
                </div>
                <div class="p_btn">
                    {$addToCartButton2}
                </div>
                
                
            </div>
        </div>


HTML;


            $temp .= '<script>
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    
    $(this).parent().next().find("ul").fadeToggle(600);
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    console.log(this)
    
    });
    
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul li a").unbind("click").on("click",function() {
    var text = $(this).html();  
    
    // show the selected size
    $(this).closest("dd").prev("dt").find("a > span").html(text);
    
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    });
    
    
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    $(this).parent().next().find("ul").fadeToggle(600);
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    console.log("clicked by sample_select_ dt, #sample_select_' . $randVal . '_' . $pId . ' ")
    });
    
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dd a").unbind("click").on("click",function() {                
    // save the selected size
    var size_selected = $(this).find("div.color_name.size").html();
    console.log("clicked by sample_select_ dd")
    // hide the ul
    $(this).closest("ul").hide();
    
    // show the selected size
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' > dt > a > span").html(size_selected);
    });
</script>';
        } else if ($view == 'doNotForgetPopup') {



            $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
            $productImage = $this->productSpecialImage($pId, 'main');
            $productImage = $this->functions->resizeImage($productImage, '250', '', false);


            $addToBagToBagPopUP = $this->dbF->hardWords('ADD TO BAG POPUP', false);

            $addToCartButton2 = "
<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>
<input type='hidden' id='hidden_stock_$pId' value='@'/>

<button class='popup_btn1 popbtn AddToCart_$pId' onclick='addToCart(this,$pId,true, false);'>{$addToBagToBagPopUP}</button>
";
            // <div class='cart1_btn AddToCart_$pId'>
// <div class='cart1_btn AddToCart_$pId'><a href='javascript:void(0)' onclick='addToCart(this,$pId,false, true);'>{$_e['ADD TO BAG']}</a></div> ";
// </div>


            $discountPrice = $this->productF->discountPriceCalculation($pPriceActual, $discount);
            $newPrice = $pPriceActual - $discountPrice;



            if ($newPrice != $pPriceActual) {
                $discountPercentage = ($discountPrice / $pPriceActual) * 100;
                $discountPercentage = ceil($discountPercentage);

                $newPriceDiv = '<div class="col3_box_detail_price2">
                    <h4>Pris <span class="mainPrice">' . $newPrice . ' </span><small class="currency">' . $ngCurrencySymbol . ' </small><span class="cutprice">' . $pPriceActual . ' ' . $ngCurrencySymbol . '</span></h4>
                </div>
                <div class="col3_box_save">
                    <h4>' . $_e["You save"] . ' ' . $discountPrice . ' ' . $ngCurrencySymbol . ' (' . $discountPercentage . ' %)</h4>
                </div>';
                $newPriceDiv = '<h1>' . $newPrice . ' ' . $ngCurrencySymbol . ' <span class="cutprice">' . $pPriceActual . ' ' . $ngCurrencySymbol . '</span></h1>';

            } else {
                $newPriceDiv = '<div class="col3_box_detail_price2">
                    <h4>Pris <span class="mainPrice">' . $newPrice . ' </span><small class="currency">' . $ngCurrencySymbol . ' </small></h4>
                </div>';
                $newPriceDiv = '<h1>' . $newPrice . ' ' . $ngCurrencySymbol . '</h1>';


            }


            $className = empty($name) ? ".popUpForget" : "." . $name;
            $noThanksTxt = $this->dbF->hardWords('No Thanks', false);
            $headerTxt = $this->dbF->hardWords("Pop up donot forget to buy", false);

            $temp = <<<HTML

        <div class="popup_header">
            <h1>{$headerTxt}</h1>
        </div>
        
        <div class="innercart_flex popUpForget">
            <div class="imgHolder">
                <img src='{$productImage}' alt='{$pName}' loading='lazy'> 
            </div>
            <div class="txtHolder">
                <h3>{$pName}</h3>
                <div class="pricemain">
                    {$newPriceDiv}
                </div>
                <div class="select_box4" id="size_radio">
                    {$scaleDiv}
                </div>
            </div>
        </div>
        <div class="btnFlex">
           {$addToCartButton2}
            <button class="popup_btn2 popbtn">{$noThanksTxt}</button>
        </div>
HTML;


            $temp .= '<script>
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    
    $(this).parent().next().find("ul").fadeToggle(600);
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    console.log(this)
    
    });
    
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul li a").unbind("click").on("click",function() {
    var text = $(this).html();  
    
    // show the selected size
    $(this).closest("dd").prev("dt").find("a > span").html(text);
    
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    });
    
    
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dt a").unbind("click").on("click",function() {
    $(this).parent().next().find("ul").fadeToggle(600);
    $("' . $className . ' #sample_' . $randVal . '_' . $pId . ' dd ul").fadeOut(600);
    console.log("clicked by sample_select_ dt, #sample_select_' . $randVal . '_' . $pId . ' ")
    });
    
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' dd a").unbind("click").on("click",function() {                
    // save the selected size
    var size_selected = $(this).find("div.color_name.size").html();
    console.log("clicked by sample_select_ dd")
    // hide the ul
    $(this).closest("ul").hide();
    
    // show the selected size
    $("' . $className . ' #sample_select_' . $randVal . '_' . $pId . ' > dt > a > span").html(size_selected);
    });
</script>';
        }

        /*$pColorData = $this->productF->colorSQL($pId);
        foreach($pColorData as $val){
        $temp .= '<div class="colors_in_divs grow" style="background-color:#'.$val['proclr_name'].';background-color:'.$val['proclr_name'].';"></div>';
        }*/

        return $temp;
    }

    public function salePBox($pId, $wishList = false, $view = true, $salePrice = false, $extraDiscount = false)
    {
        global $_e;

        //Allow with no qty on web will add in setting
        $sql = "SELECT set_id FROM `product_setting` where setting_name='freeGift' and setting_val='1' and p_id ='$pId'";
        $this->dbF->getRow($sql);


        if ($this->dbF->rowCount > 0) {
            $inven = 1;

            if (!$this->productF->hasStock($pId)) {
                if (isset($_SESSION["expressMode"]) && $_SESSION["expressMode"] == "on") {
                    return false;
                }
                if ($this->functions->ibms_setting('no_inventory_product_show_onWeb') == 'yes') {
                    return false;
                }
            }



        } else {
            $inven = 0;


            if (!$this->productF->hasStock($pId)) {
                if (isset($_SESSION["expressMode"]) && $_SESSION["expressMode"] == "on") {
                    return false;
                }
                if ($this->functions->ibms_setting('no_inventory_product_show_onWeb') == 'no') {
                    return false;
                }
            }



        }

        $data = $this->productData($pId);

        if (!$this->dbF->rowCount) {
            return false;
        }

        $productSlug = $data['slug'];

        if (!$this->productDateIsReadyForLaunch($pId)) {
            return false;
        }

        $webLang = currentWebLanguage();
        $loaderGif = WEB_URL . '/images/loader.gif';
        $serialNo = $this->productSerial = $this->productSerial + 1;

        $productScales = $this->productScales($pId);

        $productImage = $this->productSpecialImage($pId, 'main');

        if ($productImage == "") {
            $productImage = "default.jpg";
        }
        $productBigImage = $productImage;
        $productImage = $this->functions->resizeImage($productImage, '300', '500', false);

        $productHoverImage = $this->productSpecialImage($pId, 'hover');
        if ($productHoverImage == "") {
            $productHoverImage = "default.jpg";
        }
        $productHoverImage = $this->functions->resizeImage($productHoverImage, '300', '500', false);

        $showAllImages = false; // make it false if you dont need all images, so this query will not execute => save time
        if ($showAllImages) {
            $images = $this->productAllImage($pId, 3);
            $smallImages = "";
            foreach ($images as $val) {
                $smlImage = $this->functions->resizeImage($val['image'], '350', '350', false);
                $smallImages .= "<img src='$smlImage' loading='lazy'/>";
            }
        }

        $pName = translateFromSerialize($data['prodet_name']);
        $shortDesc = translateFromSerialize($data['prodet_shortDesc']);

        $currencyId = $this->currentCurrencyId();
        $currencySymbol = $this->currentCurrencySymbol();

        $storeId = $this->getStoreId(); ///////////EDited by MUD 9/15/2017

        $pPriceData = $this->productF->productPrice($pId, $currencyId);
        //$pPriceData Return , currency id,international shipping, price, id,
        $pPriceActual = $pPriceData['propri_price'];
        $pPrice = $pPriceActual;


        if ($salePrice == false) {
            $pPrice = 0;
            # code...
        }



        $discount = $this->productF->productDiscount($pId, $currencyId);
        @$discountFormat = $discount['discountFormat'];
        @$discountP = $discount['discount'];



        if ($salePrice == false) {
            @$discountP = 0;
            # code...
        }



        $discountValue = $discountP;
        if ($discountFormat == "percent") {
            $discountValue .= " %";
        } else {
            $discountValue = $discountValue . " $currencySymbol";
        }




        if ($salePrice == false) {
            @$discountValue = "";
            # code...
        }




        /////////////********EDited by MUD 9/15/2017*****/////////////////

        $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
        $inventoryLimit = ($inventoryLimit == '1' ? true : false);

        $hasScaleVal = $this->functions->developer_setting('product_Scale');
        $hasColorVal = $this->functions->developer_setting('product_color');

        $hasWebOrder_with_Scale = $this->functions->developer_setting('webOrder_with_Scale');
        $hasWebOrder_with_color = $this->functions->developer_setting('webOrder_with_color');

        $hasScale = ($hasScaleVal == '1' ? true : false);
        $hasColor = ($hasColorVal == '1' ? true : false);

        if ($inventoryLimit || $inven == 1) {
            $getInfo = $this->inventoryReport($pId);
        } else {
            $getInfo = $this->productSclaeColorReport($pId);
        }
        $getInfoReport = $getInfo['report'];

        if ($getInfo['scale'] == false && @$hasWebOrder_with_Scale == '0') {
            //if scale not found then make scale data empty,
//if product scale allow from setting and dont have inventory, it will make scale val to 0
// we will assume scale not allow from setting for javascript
            $scaleDiv = "";
            $hasScaleVal = 0;
            $hasScale = false;
        }

        if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {
            //if color not found then make color data empty,
//if product color allow from setting and dont have inventory, it will make color val to 0
// we will assume color not all ow from setting for javascript

            $colorDiv = "";
            $hasColorVal = 0;
            $hasColor = false;
        }


        $colorDiv = "";
        if ($hasColor) {
            $colorDiv = $this->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
            $colorDiv = "<div class='container_detail_RightR_color_heading'>
<dl id='sample_$pId' class='dropdown'> <dt><a> <span>" . $_e['Select Color'] . "</span></a></dt><dd>
<ul> " . $colorDiv . "    </ul>
</dd></dl>" . "</div>";

        }


        if ($hasScale) {

            $scaleDiv = $this->getScalesDiv($pId, $hasColor, $storeId, $currencyId, $currencySymbol);

        }




        //Custom Size
        if ($this->functions->developer_setting('product_customSize') == '1') {
            $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
            $customData = $this->dbF->getRows($sql);

            if (!empty($customData)) {
                @$customSize = $customData[0]['type_id'];
                if ($customSize != "0" && !empty($customSize)) {
                    $isCustomSize = true;

                    $customSizeForm = $this->customSizeForm($pId, $customSize);
                    if ($customSizeForm == false) {
                        $isCustomSize = true;
                        $customSize = 0;
                    } else {
                        $customSize_price = $this->customSizeArrayFilter($customData, $currencyId);
                        $customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
                        $onclick = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '" id="custom_size_button" ';
                        $scaleDiv = $scaleDiv . $this->getScaleDivFormat($pId, $_e['Custom'], -1, $customSize_price, -1, '', $onclick);
                    }
                }//if customSize==0 end
            } //if customData end
        } // if developer setting end
//Custom Size End




        if (!empty($scaleDiv)) {
            $scaleDiv = "<div class='container_detail_RightR_color_heading'>
<dl id='sample_select_$pId' class='dropdown_select'> <dt><a> <span>" . $_e['Select Scale'] . "</span></a></dt><dd>
<ul style='height: 100px;'> " . $scaleDiv . "    </ul>
</dd></dl>" . "</div>";
        }



        /////////////********EDited by MUD 9/15/2017*****/////////////////


        $discountPrice = $this->productF->discountPriceCalculation($pPriceActual, $discount);

        $newPrice = $pPriceActual - $discountPrice;

        $isSale = false;
        if (isset($discount['isSale']) && $discount['isSale'] == '1') {
            $isSale = true;
        }

        $isDiscount = false;
        if ($newPrice != $pPrice) {
            $isDiscount = true;
        }

        $pPrice = empty($pPrice) ? 0 : $pPrice;
        $newPrice = empty($newPrice) ? 0 : $newPrice;
        $newPrice = $salePrice;
        $pPrice .= ' ' . $currencySymbol;
        $newPrice .= ' ' . $currencySymbol;

        $hasDiscount = false;

        if ($newPrice != $pPrice && $pPriceActual != '') {
            $hasDiscount = true;
            $oldPriceNormal = $pPriceActual;
            $newPriceNormal = $salePrice;
            $oldPriceDiv = '<h6>' . $pPriceActual . '</h6>';
            $newPriceDiv = '<h4>' . $newPrice . '</h4>';
        } else {
            $oldPriceNormal = "";
            $newPriceNormal = $salePrice;
            $oldPriceDiv = "";
            $newPriceDiv = '<h4>' . $pPrice . '</h4>';
        }

        /*Update Your Product Box Here... BY using Upper Variables*/
        $wishListRemove = "";
        if ($wishList) {
            $wishListRemove = "<div class='wishListRemove cursor' onclick='WishListRemove(this,$pId)'>X</div>";
        }



        $chk_link = "/" . $this->db->productDetail . $productSlug;

        $sql = "SELECT `slug` FROM `seo` WHERE `pageLink` = ?";

        $data = $this->dbF->getRow($sql, array($chk_link));
        if ($this->dbF->rowCount > 0) {
            $link = WEB_URL . "/" . $data['slug'];
        } else {
            $link = WEB_URL . "/$productSlug";
        }

        //$link = WEB_URL . "/" . $this->db->productDetail . "$productSlug"; // product slug
        $Qucicklink = WEB_URL . "/quickView.php?pId=$pId";



        $pricen = explode(" ", $pPrice);



        $discountValue = $pricen[0] - $salePrice;

        $saleDiv = "";
        if ($isSale) {
            //Sale
            $saleDiv = "
<div class='discount_tag'>
<h4>$discountValue</h4>
<h5>" . $_e['SALE'] . "</h5>
</div>";
        } else if ($isDiscount) {
            //Discount
            $saleDiv = "
<div class='discount_tag'>
<h4>$discountValue</h4>
<h5>" . $_e['DISCOUNT'] . "</h5>
</div>";
        }

        $saleDivDis = "";
        if ($isSale) {
            //Sale
            $saleDivDis = "
<div class='grid_discount_tag'>
<h4>$discountValue</h4>
<h5>" . $_e['SALE'] . "</h5>
</div>";
        } else if ($isDiscount) {
            //Discount
            $saleDivDis = "
<div class='grid_discount_tag'>
<h4>$discountValue</h4>
<h5>" . $_e['DISCOUNT'] . "</h5>
</div>";
        }
        /**
         * For Product Quick View, Also need to call function productQuickViewModel(); in footer
         * <a class="" data-toggle="modal" data-target="#paroductQuickView" onclick="quickView(this,$pId);">
         *
         *
         * </a>
         */

        //Which view Print
//Default View
//Use pBox Class in main product div
        $webUrl = WEB_URL;
        $wishlistT = _u($_e['Add to WishList']);
        $buyT = _u($_e['BUY']);
        $detailsT = _u($_e['Details']);
        $showDetailsT = _u($_e['Show Details']);
        $viewT = _u($_e['Lagg till i varukorgen']);



        ######### 3 For 2 Category ########
        $three_for_2_category = $this->productF->check_product_in_3_for_2($pId);
        if ($three_for_2_category) {
            $three_for_2_category = "<div class='three_for_2_icon'><img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' loading='lazy'/></div>";
        } else {
            $three_for_2_category = "";
        }

        ######### Staple Product Category ########
        $staple_product_category1 = $this->productF->check_product_in_staple_cat($pId);
        if ($staple_product_category1) {
            $staple_product_category = "<div class='three_for_2_icon bundle_cat_icon'><img src='" . WEB_URL . "/webImages/bundle_icon.png' alt='bundle' loading='lazy'/></div>";
        } else {
            $staple_product_category = "";
        }

        $addToCartButton = "


<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>
<input type='hidden' id='hidden_stock_$pId' value=''/>
<input type='hidden' class='hidden' value='$salePrice' id='salePrice_$pId'>


<div class='cart1_btn AddToCart_$pId'><a href='javascript:void(0)' onclick='addToCart(this,$pId);'>{$_e['ADD TO BAG']}</a></div> ";

        $temp = '';

        if ($view == '1') {
            $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
            $saleDiv = '';
            if ($isSale) {
                $saleDiv = '<div class="sale_tag">' . $_e['SALE'] . '</div>';
            }
            $productImage = $this->functions->resizeImage($productBigImage, '175', '330', false);



            // $saleDivDis = "";
// $saleDivDisVal = "";
            $saleDivDisPerc = "";
            if ($isSale) {
                //Sale
                if ($discountFormat == "percent") {
                    $saleDivDisPerc = "<div class='col3_box_tag1'>-$discountValue</div>";
                } else {
                    $price = explode(" ", $pPrice);
                    $discount_per = ($discountP / $price[0]) * 100;
                    $discount_per = ceil($discount_per);
                    $saleDivDisPerc = "<div class='col3_box_tag1'>-$discount_per%</div>";
                }

            } else if ($isDiscount) {
                //Discount
                if ($discountFormat == "percent") {
                    $saleDivDisPerc = "<div class='col3_box_tag1'>-$discountValue</div>";
                } else {
                    $price = explode(" ", $pPrice);
                    if ($extraDiscount) {
                        $discount_per = ($price[0] - $salePrice) / $price[0] * 100;
                        $discount_per = ceil($discount_per);
                    } else {
                        $discount_per = ($discountP / $price[0]) * 100;
                        $discount_per = ceil($discount_per);
                    }

                    $saleDivDisPerc = "<div class='col3_box_tag1'>-$discount_per %</div>";
                }
            }



            if ($salePrice == false) {
                @$saleDivDisVal = "<div class='col3_box_tag3'>" . $_e['Free Gift'] . "</div>";
                @$saleDivDisPerc = "";
                @$newPriceDiv = '<h4>0 ' . $currencySymbol . '<h4>';
                @$oldPriceDiv = "";

                # code...
            }





            $temp = <<<HTML
<div class="col3_box">
$wishListRemove
<a href="$link" data-page="$link" title=" $pName - Sharkspeed">
<div class="col3_box_img"> 
$productTags
<img src="$productImage" alt="$pName" loading="lazy">

<!-- col3_box_tag3 close -->
</div>
<!-- col3_box_img close -->
</a>
<div class="col3_box_detail">
<div class="col3_box_tag">
$saleDivDisPerc
<!-- col3_box_tag1 close -->
<!-- <div class="col3_box_tag2">UTFORSALJNING!</div> -->
</div>
<!-- col3_box_tag close -->
<a href="$link" data-page="$link" title=" $pName - Sharkspeed">
<div class="col3_box_detail_price">
$newPriceDiv
$oldPriceDiv
</div>
<!-- col3_box_detail_price close -->
<div class="col3_box_detail_txt"> $pName </div>
<!-- col3_box_detail_txt close -->
</a>
<div class="select $scl_emp">
<div id="size_color" class="container_detail_RightR_color choice_color"> 
$colorDiv
</div>
<div class="detail_top_r_size" id="size_radio">
$scaleDiv
</div>
<div class="padding-0" style=" margin-top: 10px;">
<small id="stock_$pId"> </small>
<input type="hidden" id="hidden_stock_$pId">
</div>
</div>
$addToCartButton
<script>
$("#sample_$pId dt a").click(function() {

$(this).parent().next().find("ul").fadeToggle(600);
$("#sample_select_$pId dd ul").fadeOut(600);

});

$("#sample_$pId dd ul li a").click(function() {
var text = $(this).html();  

// show the selected size
$(this).closest("dd").prev("dt").find("a > span").html(text);

// $("#sample_'.$pId.' dt a span").html(text);
$("#sample_$pId dd ul").fadeOut(600);
});


$("#sample_select_$pId dt a").click(function() {
$(this).parent().next().find("ul").fadeToggle(600);
$("#sample_$pId dd ul").fadeOut(600);
});

$("#sample_select_$pId dd a").click(function() {                
// save the selected size
var size_selected = $(this).find("div.color_name.size").html();

// hide the ul
$(this).closest("ul").hide();

// show the selected size
$("#sample_select_$pId > dt > a > span").html(size_selected);
});
</script>
</div>
<!-- col3_box_detail close -->
</div>
<!-- col3_box close -->




HTML;
            //             $temp = <<<HTML
//             <div class="sale_box">
//                 <input type="hidden" class="hidden" value="$salePrice" id="salePrice_$pId">

            //             $wishListRemove

            //                 <div class="r_img">
//                     <a href="$link">
//                         <img src="$productImage" alt="$pName" class="shrink">
//                     </a>
//                 </div><!--r_img end-->
//                 <div class="r_text_head">$pName</div>

            //                 <div class="r_prize">$newPriceNormal <span> $oldPriceNormal</span></div>
//                 <div class="select $scl_emp">
//                     <div id="size_color" class="container_detail_RightR_color choice_color">
//                         $colorDiv
//                     </div>
//                     <div class="detail_top_r_size" id="size_radio">
//                         $scaleDiv
//                     </div>
//                     <div class="padding-0" style=" margin-top: 10px;">
//                         <small id="stock_$pId"> </small>
//                         <input type="hidden" id="hidden_stock_$pId">
//                     </div>
//                 </div>
//                 $addToCartButton
//                 <script>
//                     $("#sample_$pId dt a").click(function() {

            //                     $(this).parent().next().find("ul").fadeToggle(600);
//                     $("#sample_select_$pId dd ul").fadeOut(600);

            //                 });

            //                 $("#sample_$pId dd ul li a").click(function() {
//                     var text = $(this).html();  

            //                     // show the selected size
//                     $(this).closest("dd").prev("dt").find("a > span").html(text);

            //                     // $("#sample_'.$pId.' dt a span").html(text);
//                     $("#sample_$pId dd ul").fadeOut(600);
//                 });


            //                     $("#sample_select_$pId dt a").click(function() {
//                     $(this).parent().next().find("ul").fadeToggle(600);
//                     $("#sample_$pId dd ul").fadeOut(600);
//                 });

            //                 $("#sample_select_$pId dd a").click(function() {                
//                     // save the selected size
//                     var size_selected = $(this).find("div.color_name.size").html();

            //                     // hide the ul
//                     $(this).closest("ul").hide();

            //                     // show the selected size
//                     $("#sample_select_$pId > dt > a > span").html(size_selected);
//                 });
//               </script>
//             </div><!--r_box end-->
// HTML;

        }

        /*$pColorData = $this->productF->colorSQL($pId);
        foreach($pColorData as $val){
        $temp .= '<div class="colors_in_divs grow" style="background-color:#'.$val['proclr_name'].';background-color:'.$val['proclr_name'].';"></div>';
        }*/

        return $temp;
    }

    public function productQuickViewModel()
    {
        //use this functions in end of footer file.
//<a class="" data-toggle="modal" data-target="#productQuickView" onclick="quickView(this,$pId);">
        global $_e;
        $temp = '
<!-- Modal -->
<div class="modal fade" id="productQuickView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">' . _uc($_e['Product Quick View']) . '</h4>
</div>
<div class="modal-body ">
<div id="frameProductQuickView"></div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger" data-dismiss="modal">' . _uc($_e['Close']) . '</button>
</div>
</div>
</div>
</div>';
        return $temp;

    }

    public function getSubCatIds($parent)
    {
        return $this->productF->getSubCatIds($parent);
    }

    public function productByfilterAll($text, $QuickViewId = false, $hasId = true)
    {
        if ($text != "") {
            $text = " (`prodet_name` LIKE '%$text%' OR  `prodet_shortDesc` LIKE '%$text%') ";
        } else {
            $text = "1=1";
        }
        $limitFrom = 0;
        $limitT0 = $this->productLimitShowOnWeb();
        $currencyId = $this->currentCurrencyId();
        //product sort by filteration, e.g by price, by sale etc
        $sortByArray = $this->sortByProductQuery();
        $filterBySize = $this->filterBySizeQueryNew();

        $getCatSearch = $this->getCatSearch();
        $filerByPriceRange = $this->filerByPriceRange($currencyId);
        $newField = $sortByArray[0];
        $sortGroupBy = $sortByArray[1];
        $best_seller_sql = $sortByArray[2];
        //Find Product That in this category




        //search All related Match products
        $sql = "SELECT `prodet_id` $newField
        FROM `proudct_detail`
        WHERE $text
        $filerByPriceRange
        $getCatSearch
        $filterBySize
        GROUP BY prodet_id $sortGroupBy";

        $tempTId = $this->functions->setTempTableVal('Query', $sql);
        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

        $sql .= " LIMIT $limitFrom,$limitT0";

        # best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
        $best_seller_products = array();
        if ($best_seller_sql == 'www') {
            $productIds = $this->newBestSellerSQL("");

        } else {
            $productIds = $this->dbF->getRows($sql);
            if (!$this->dbF->rowCount) {
                return "";
            }
        }

        $mergerd_products = $productIds;
        return $this->productPrint($mergerd_products, $QuickViewId, $products);

    }

    public function productByfilter_All_search_cat($text, $QuickViewId = false, $hasId = true)
    {
        if ($text != "") {
            $text = strtolower($text);

            $temp = str_replace('Å', '%', $text);
            $temp = str_replace('Ä', '%', $temp);
            $temp = str_replace('å', '%', $temp);
            $temp = str_replace('ä', '%', $temp);

            $text = " ( lower(`prodet_name`)   LIKE '%$temp%' OR
            lower(`prodet_shortDesc`) LIKE '%$temp%' ) ";

        } else {
            $text = "1=1";
        }
        $limitFrom = 0;
        $limitT0 = $this->productLimitShowOnWeb();
        $limitT0 = 100;
        $currencyId = $this->currentCurrencyId();
        //search All related Match products
        $sql = "SELECT `prodet_id` FROM `proudct_detail` WHERE $text  AND `product_discount_$currencyId` > 0 AND `listing` = 1 GROUP BY prodet_id ORDER BY sort ASC";

        $tempTId = $this->functions->setTempTableVal('Query', $sql);
        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

        $sql .= " LIMIT $limitFrom,$limitT0";

        # best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
        $productIds = $this->dbF->getRows($sql);

        if (!$this->dbF->rowCount) {
            return "";
        }


        $mergerd_products = $productIds;


        return $this->productPrint($mergerd_products, $QuickViewId, $products);

    }

    public function productByCategory($category, $QuickViewId = false, $hasId = true)
    {

        $sql = "SELECT * FROM `tree_data` WHERE id = ? ";
        if ($hasId == false) {
            $sql = "SELECT * FROM `tree_data` WHERE nm = ?";
        }
        $catData = $this->dbF->getRow($sql, array($category));

        $currentPage = isset($_GET['page']) ? floatval($_GET['page']) : 0;

        if (!$this->dbF->rowCount) {
            return false;
        }

        $catId = $catData['id'];
        $catId = $this->getSubCatIds($catId); //array

        $LIKE = "";
        foreach ($catId as $val) {
            $cId = $val;
            $LIKE .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";
        }
        $LIKE = trim($LIKE, "OR");


        $limitFrom = 0;
        $limitT0 = $this->productLimitShowOnWeb();


        //product sort by filteration, e.g by price, by sale etc
        $sortByArray = $this->sortByProductQuery();
        $newField = $sortByArray[0];
        $sortGroupBy = $sortByArray[1];
        $best_seller_sql = $sortByArray[2];

        // $sortGroupBy = '';

        //Find Product That in this category
        $sql = "SELECT `procat_prodet_id`,`prodet_id`
$newField
FROM `product_category`
JOIN
`proudct_detail` as detail
on `product_category`.`procat_prodet_id` = `prodet_id`

WHERE $LIKE
GROUP BY `detail`.`prodet_id` $sortGroupBy";


        # best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
        $best_seller_products = array();
        if ($best_seller_sql != '') {
            $best_seller_products = $this->newBestSellerSQL($LIKE);
        }


        $tempTId = $this->functions->setTempTableVal('Query', $sql);
        //For navigation
/*$limitFrom      = $currentPage*$limitT0-$limitT0;
$limitT0        = $currentPage*$limitT0;
$this->dbF->getRows($sql);
$this->totalProductFrNav    = $this->dbF->rowCount;
$this->productLimit         = $limitT0;*/
        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);

        $mergerd_products = $best_seller_products + $productIds;
        return $this->productPrint($mergerd_products, $QuickViewId, $products);
    }

    public function productByCategoryNew($category, $QuickViewId = false, $hasId = true)
    {
        $sql = "SELECT * FROM `categories` WHERE id = ? ";
        if ($hasId == false) {
            $sql = "SELECT * FROM `categories` WHERE name = ?";
        }
        $catData = $this->dbF->getRow($sql, [$category]);

        $currentPage = isset($_GET['page']) ? floatval($_GET['page']) : 0;

        if (!$this->dbF->rowCount) {
            return false;
        }

        $catId = $catData['id'];
        $catId = $this->getSubCatIds($catId); //array

        $LIKE = "";
        foreach ($catId as $val) {
            $cId = $val;
            $LIKE .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";
        }
        $LIKE = trim($LIKE, "OR");


        $limitFrom = 0;
        $limitT0 = $this->productLimitShowOnWeb();
        $currencyId = $this->currentCurrencyId();

        //product sort by filteration, e.g by price, by sale etc
        $sortByArray = $this->sortByProductQuery();
        $filterBySize = $this->filterBySizeQuery();
        $filerByPriceRange = $this->filerByPriceRange($currencyId);
        $listing = "AND `listing` = 1";
        $newField = $sortByArray[0];
        $sortGroupBy = $sortByArray[1];
        $best_seller_sql = $sortByArray[2];

        //Find Product That in this category
        $sql = "SELECT `procat_prodet_id`,`prodet_id` $newField FROM `product_category` JOIN `proudct_detail` as detail on 
        `product_category`.`procat_prodet_id` = `detail`.`prodet_id` WHERE ($LIKE) $filerByPriceRange $listing $filterBySize GROUP BY 
        `detail`.`prodet_id` $sortGroupBy";

        # best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
        $best_seller_products = array();
        if ($best_seller_sql != '') {
            $best_seller_products = $this->newBestSellerSQL($LIKE);
        }


        $tempTId = $this->functions->setTempTableVal('Query', $sql);
        //For navigation
        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

        $sql .= " LIMIT $limitFrom,$limitT0";

        $productIds = $this->dbF->getRows($sql);
        $mergerd_products = $productIds;

        return $this->productPrint($mergerd_products, $QuickViewId, $products);

    }

    public function get_change_grid_view_link($type)
    {
        $link = $this->functions->currentUrl(false, true); //viewType
        $link = preg_replace("/([&|?])viewType=Grid|([&|?])viewType=list/i", "", $link);
        $link .= "viewType=$type";
        return $link;
    }

    private function productPrint($productIds, $QuickViewId = false, $products = "", $array = array(), $name = "")
    {
        global $_e;
        $currencyId = $this->currentCurrencyId();
        $viewType = isset($array['viewType']) ? $array['viewType'] : true;

        if ($viewType === true) {
            $cat_id = empty($_GET["catId"]) ? false : $_GET["catId"];
            $viewType = $this->get_product_view($cat_id);
        }

        if ($QuickViewId === false || $QuickViewId == '') {
            if (isset($_GET['pId'])) {
                $QuickViewId = $_GET['pId'];
            } else {
                $QuickViewId = '0';
            }
        }

        $QuickFind = false; // use in when user share product, and detail show under product

        $i = 0;
        $oneCond = 0;
        $dealIndex = 1;
        $checkDeal = false;
        $bestProductPos = 3;
        $checkRecommend = false;

        if (is_array($productIds) || is_object($productIds)) {
            foreach ($productIds as $p) {
                $i++;
                if ($QuickViewId == $p['prodet_id']) {
                    $QuickFind = true;
                }

                if (isset($cat_id) && !empty($cat_id) && $i == 1) {
                    $pro_deals = $this->getCatDeals($cat_id, 0);
                    if (!empty($pro_deals)) {
                        $checkDeal = true;
                        $pro_base_price = 0;
                        $img = $this->functions->resizeImage($pro_deals['image'], '510', '400', false);
                        $id = $pro_deals['id'];
                        $slug = $pro_deals['slug'];
                        $name = translateFromSerialize($pro_deals['name']);
                        $imageR = $pro_deals['image'];
                        $view_type = $pro_deals['view_type'];
                        $dealClass = '';
                        if ($view_type == '0') {
                            $dealClass = 'col3_box_main_add_';
                        } else if ($view_type == '1') {
                            $dealClass = '';
                        }

                        $price = unserialize($pro_deals['price']);

                        $package = unserialize($pro_deals['package']);

                        foreach ($package as $key => $value) {
                            $base_price = $this->productF->productPrice($value);
                            $pro_base_price += $base_price['propri_price'];
                        }
                        if (isset($price[$currencyId])) {
                            $dealDiscount = $pro_base_price - $price[$currencyId];
                        } else {
                            $dealDiscount = '';
                        }



                        $chk_link = "/" . $this->db->dealProduct . $slug;


                        $sql = "SELECT `slug`,`id` FROM `seo` WHERE `pageLink` = ?";
                        $data = $this->dbF->getRow($sql, array($chk_link));
                        if ($this->dbF->rowCount > 0) {
                            $webLang = currentWebLanguage();
                            $seoid = $data['id'];
                            $sql_seo_slug = "SELECT slug FROM seo_slug WHERE seo_id = '$seoid' and lang = '$webLang' and slug != ''";
                            $check_seo_slug = $this->dbF->getRow($sql_seo_slug);
                            if ($this->dbF->rowCount > 0) {
                                $link = WEB_URL . "/" . $check_seo_slug['slug'];
                            } else {
                                $link = WEB_URL . "/" . $data['slug'];
                            }
                        } else {
                            $link = WEB_URL . "/$slug";
                        }

                        $web_url = WEB_URL;

                        $price = isset($price[$currencyId]) ? $price[$currencyId] : "0";
                        $currencySymbol = $this->currentCurrencySymbol();

                        $dealDiscount = $dealDiscount . ' ' . $currencySymbol;
                        $totalpPrice = $pro_base_price . "<span class='currencysymbol'>" . $currencySymbol . "</span>";

                        if ($price == '0' || empty($price)) {
                            //if price 0 it will not show OR it wil not show in that country

                            $priceDiv = "";
                            continue;
                        } else {
                            $priceDiv = "$price<span class='currencysymbol'>$currencySymbol</span>";
                        }
                        $image = $this->functions->resizeImage($imageR, '270', '270', false);

                        # get deal setting
                        $deal_setting_data = $this->get_deal_setting($id);
                        $shortDesc = $this->functions->findArrayFromSettingTable($deal_setting_data, 'sDesc');
                        $shortDesc = translateFromSerialize($shortDesc);

                        $products .= "<div class='col3_box_main  " . $dealClass . "'>
                        <input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>
                        <a href='{$link}' data-page='{$link}' title=' {$name} - Sharkspeed'>
                        <div class='col3_box_main_img'> 
                        <img src='{$img}' alt='{$name}' loading='lazy'> 
                        <div class='col3_box_tag3'>
                        {$dealDiscount} " . $_e['DISCOUNT'] . "
                        </div>
                        </div>
                        <!-- col3_box_main_img close -->
                        <div class='col3_box_main_txt'>" . $_e['Package Deal'] . "</div>
                        <!-- col3_box_main_txt close -->
                        <div class='col3_box_detail'>
                        <div class='col3_box_detail_price'>
                        <h4>{$priceDiv}</h4>
                        <h6>{$totalpPrice}</h6> </div>
                        <!-- col3_box_detail_price close -->
                        <div class='col3_box_detail_txt'> {$name} </div>
                        <!-- col3_box_detail_txt close -->
                        </div>
                        <!-- col3_box_detail close -->
                        </a>
                        </div>
                        <!-- col3_box_main close -->";
                    }
                }

                if ($checkDeal) {
                    $bestProductPos = 1;
                }

                $products .= $this->pBox($p['prodet_id'], false, $viewType, $name);



                $checkrecommend = $this->getCatRecomended(@$cat_id, 0);
                if (!empty($checkrecommend)) {
                    $checkRecommend = true;
                }


                $recommendedProductPos = 0;
                if ($checkRecommend) {
                    $recommendedProductPos = 1;
                }





                if (isset($cat_id) && !empty($cat_id) && $i == $recommendedProductPos) {

                    $sql = "SELECT * FROM `sp_recommends` where cat_id ='$cat_id' and publish ='1'";

                    $data = $this->dbF->getRows($sql);
                    foreach ($data as $key => $value) {
                        $products .= $this->pBox($value['product_id'], false, 'recommendedProduct', $name);
                    }
                }



                if (isset($cat_id) && !empty($cat_id) && $i == $bestProductPos) {
                    $best = $this->ProductsCombineWith($cat_id, 1);
                    if ($best) {
                        # code...
                        $products .= $this->pBox($best[0]['prodet_id'], false, 'bestProduct', $name);
                    }
                }








                if (isset($cat_id) && !empty($cat_id) && $i % 6 == 0) {
                    $pro_deals = $this->getCatDeals($cat_id, $dealIndex);
                    if (!empty($pro_deals)) {
                        $pro_base_price = 0;
                        $img = $this->functions->resizeImage($pro_deals['image'], '510', '400', false);
                        $id = $pro_deals['id'];
                        $slug = $pro_deals['slug'];
                        $name = translateFromSerialize($pro_deals['name']);
                        $imageR = $pro_deals['image'];
                        $price = unserialize($pro_deals['price']);
                        $package = unserialize($pro_deals['package']);

                        $view_type = $pro_deals['view_type'];

                        if ($view_type == '0') {
                            $dealClass = 'col3_box_main_add_';
                        } else if ($view_type == '1') {
                            $dealClass = '';
                        }

                        foreach ($package as $key => $value) {
                            $base_price = $this->productF->productPrice($value);
                            $pro_base_price += $base_price['propri_price'];
                        }
                        if (isset($price[$currencyId])) {
                            $dealDiscount = $pro_base_price - $price[$currencyId];
                        } else {
                            $dealDiscount = '';
                        }

                        $dealDiscount = $dealDiscount . ' ' . $currencySymbol;
                        $totalpPrice = $pro_base_price . "<span class='currencysymbol'>" . $currencySymbol . "</span>";

                        $chk_link = "/" . $this->db->dealProduct . $slug;



                        $sql = "SELECT `slug`,`id` FROM `seo` WHERE `pageLink` = ?";
                        $data = $this->dbF->getRow($sql, array($chk_link));
                        if ($this->dbF->rowCount > 0) {
                            $webLang = currentWebLanguage();
                            $seoid = $data['id'];
                            $sql_seo_slug = "SELECT slug FROM seo_slug WHERE seo_id = '$seoid' and lang = '$webLang' and slug != ''";
                            $check_seo_slug = $this->dbF->getRow($sql_seo_slug);
                            if ($this->dbF->rowCount > 0) {
                                $link = WEB_URL . "/" . $check_seo_slug['slug'];
                            } else {
                                $link = WEB_URL . "/" . $data['slug'];
                            }
                        } else {
                            $link = WEB_URL . "/$slug";
                        }


                        $web_url = WEB_URL;

                        $currencyId = $this->currentCurrencyId();
                        $price = isset($price[$currencyId]) ? $price[$currencyId] : "0";
                        $currencySymbol = $this->currentCurrencySymbol();

                        if ($price == '0' || empty($price)) {
                            //if price 0 it will not show OR it wil not show in that country

                            $priceDiv = "";
                            continue;
                        } else {
                            $priceDiv = "$price <span class='currencysymbol'>$currencySymbol</span>";
                        }
                        $image = $this->functions->resizeImage($imageR, '500', '200', false);

                        # get deal setting
                        $deal_setting_data = $this->get_deal_setting($id);
                        $shortDesc = $this->functions->findArrayFromSettingTable($deal_setting_data, 'sDesc');
                        $shortDesc = translateFromSerialize($shortDesc);


                        $products .= "<div class='col3_box_main " . $dealClass . "'>
                        <input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>
                        <a href='{$link}' data-page='{$link}' title=' {$name} - Sharkspeed'>
                        <div class='col3_box_main_img'> 
                        <img src='{$img}' alt='{$name}' loading='lazy'> 
                        <div class='col3_box_tag3'>
                        {$dealDiscount} " . $_e['DISCOUNT'] . "
                        </div>
                        </div>
                        <!-- col3_box_main_img close -->
                        <div class='col3_box_main_txt'>" . $_e['Package Deal'] . "</div>
                        <!-- col3_box_main_txt close -->
                        <div class='col3_box_detail'>
                        <div class='col3_box_detail_price'>
                        <h4>{$priceDiv}</h4>
                        <h6>{$totalpPrice}</h6> </div>
                        <!-- col3_box_detail_price close -->
                        <div class='col3_box_detail_txt'> {$name} </div>
                        <!-- col3_box_detail_txt close -->
                        </div>
                        <!-- col3_box_detail close -->
                        </a>
                        </div>
                        <!-- col3_box_main close -->";

                        $dealIndex++;
                    }
                }

            }
        }


        if (isset($cat_id) && !empty($cat_id)) {
            $totalDeals = $this->countTotalDeals(@$cat_id);

            if ($totalDeals > $dealIndex) {
                $nextIndex = $dealIndex + 1;
                for ($d = $dealIndex; $d <= $totalDeals; $d++) {
                    $pro_deals = $this->getCatDeals(@$cat_id, $d);
                    if (!empty($pro_deals)) {
                        $pro_base_price = 0;
                        $img = WEB_URL . '/images/' . $pro_deals['image'];
                        $id = $pro_deals['id'];
                        $slug = $pro_deals['slug'];
                        $name = translateFromSerialize($pro_deals['name']);
                        $imageR = $pro_deals['image'];
                        $price = unserialize($pro_deals['price']);
                        $package = unserialize($pro_deals['package']);

                        $view_type = $pro_deals['view_type'];
                        $dealClass = '';
                        if ($view_type == '0') {
                            $dealClass = 'col3_box_main_add_';
                        } else if ($view_type == '1') {
                            $dealClass = '';
                        }

                        foreach ($package as $key => $value) {
                            $base_price = $this->productF->productPrice($value);
                            $pro_base_price += $base_price['propri_price'];
                        }
                        if (isset($price[$currencyId])) {
                            $dealDiscount = $pro_base_price - $price[$currencyId];
                        } else {
                            $dealDiscount = '';
                        }

                        $currencySymbol = $this->currentCurrencySymbol();

                        $dealDiscount = $dealDiscount . ' ' . $currencySymbol;
                        $totalpPrice = $pro_base_price . ' ' . $currencySymbol;

                        $chk_link = "/" . $this->db->dealProduct . $slug;

                        $sql = "SELECT `slug` FROM `seo` WHERE `pageLink` = ?";


                        $data = $this->dbF->getRow($sql, array($chk_link));
                        if ($this->dbF->rowCount > 0) {
                            $link = WEB_URL . "/" . $data['slug'];
                        } else {
                            $link = WEB_URL . "/$slug";
                        }
                        $web_url = WEB_URL;

                        $currencyId = $this->currentCurrencyId();
                        $price = isset($price[$currencyId]) ? $price[$currencyId] : "0";


                        if ($price == '0' || empty($price)) {
                            //if price 0 it will not show OR it wil not show in that country

                            $priceDiv = "";
                            continue;
                        } else {
                            $priceDiv = "<span class='NewDiscountPrice h2'>$price $currencySymbol</span>";
                        }
                        $image = $this->functions->resizeImage($imageR, '500', '200', false);

                        # get deal setting
                        $deal_setting_data = $this->get_deal_setting($id);
                        $shortDesc = $this->functions->findArrayFromSettingTable($deal_setting_data, 'sDesc');
                        $shortDesc = translateFromSerialize($shortDesc);


                        $products .= "<div class='col3_box_main " . $dealClass . "' data-id='$id'>
                        <input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>
                        <a href='{$link}' data-page='{$link}' title=' {$name} - Sharkspeed'>
                        <div class='col3_box_main_img'> 
                        <img src='{$img}' alt='{$name}' loading='lazy'> 
                        <div class='col3_box_tag3'>
                        {$dealDiscount} " . $_e['DISCOUNT'] . "
                        </div>
                        </div>
                        <!-- col3_box_main_img close -->
                        <div class='col3_box_main_txt'>" . $_e['Package Deal'] . "</div>
                        <!-- col3_box_main_txt close -->
                        <div class='col3_box_detail'>
                        <div class='col3_box_detail_price'>
                        <h4>{$priceDiv}</h4>
                        <h6>{$totalpPrice}</h6> </div>
                        <!-- col3_box_detail_price close -->
                        <div class='col3_box_detail_txt'> {$name} </div>
                        <!-- col3_box_detail_txt close -->
                        </div>
                        <!-- col3_box_detail close -->
                        </a>
                        </div>
                        <!-- col3_box_main close -->";
                    }
                }
            }
        }

        /*When product show in quick view, and client share that url, so on request same product open again, need to do some work*/


        if ($QuickFind === false && $QuickViewId != '0') {

            $products .= $this->pBox($QuickViewId, false, $viewType, $name);
        }
        return $products;
    }

    private function saleProductPrint($productIds, $inv, $QuickViewId = false, $products = "", $array = array())
    {
        global $_e;
        $sql = "SELECT `first_email`, `second_email` FROM `extra_sales_products` WHERE `invoice_no` = ?";
        $dataExtra = $this->dbF->getRow($sql, [$inv]);
        
        $firstEmail = $dataExtra["first_email"];
        $secondEmail = $dataExtra["second_email"];
        $extraDiscount = false;
        
        if ($firstEmail == 1 && $secondEmail == 1) {
            $extraDiscount = true;
        }

        $currencyId = $this->currentCurrencyId();
        $viewType = isset($array['viewType']) ? $array['viewType'] : true;
        if ($viewType === true) {
            $cat_id = empty($_GET["catId"]) ? false : $_GET["catId"];
            $viewType = $this->get_product_view($cat_id);
        }

        if ($QuickViewId === false || $QuickViewId == '') {
            if (isset($_GET['pId'])) {
                $QuickViewId = $_GET['pId'];
            } else {
                $QuickViewId = '0';
            }
        }
        $QuickFind = false; // use in when user share product, and detail show under product
        $product_count = count($productIds);
        $i = 0;
        $oneCond = 0;
        $dealIndex = 1;

        foreach ($productIds as $p) {
            $i++;
            if ($QuickViewId == $p['prodet_id']) {
                $QuickFind = true;
            }

            $viewType = '1';

            $products .= $this->salePBox($p['prodet_id'], false, $viewType, $p['salePrice'], $extraDiscount);
        }

        /*When product show in quick view, and client share that url, so on request same product open again, need to do some work*/


        if ($QuickFind === false && $QuickViewId != '0') {
            $products .= $this->salePBox($QuickViewId, false, $viewType, $p['salePrice'], $extraDiscount);
        }
        return $products;
    }

    private function freeGiftProductPrint($productIds, $QuickViewId = false, $products = "", $array = array())
    {
        global $_e;
        $currencyId = $this->currentCurrencyId();
        $viewType = isset($array['viewType']) ? $array['viewType'] : true;
        if ($viewType === true) {
            $cat_id = empty($_GET["catId"]) ? false : $_GET["catId"];
            $viewType = $this->get_product_view($cat_id);
        }

        if ($QuickViewId === false || $QuickViewId == '') {
            if (isset($_GET['pId'])) {
                $QuickViewId = $_GET['pId'];
            } else {
                $QuickViewId = '0';
            }
        }
        $QuickFind = false; // use in when user share product, and detail show under product
        $product_count = count($productIds);
        $i = 0;
        $oneCond = 0;
        $dealIndex = 1;
        foreach ($productIds as $p) {
            $i++;
            if ($QuickViewId == $p['prodet_id']) {
                $QuickFind = true;
            }

            $viewType = '1';

            $products .= $this->salePBox($p['prodet_id'], false, $viewType);
        }

        /*When product show in quick view, and client share that url, so on request same product open again, need to do some work*/


        if ($QuickFind === false && $QuickViewId != '0') {
            $products .= $this->salePBox($QuickViewId, false, $viewType);
        }
        return $products;
    }

    public function sortByProductNew()
    {
        global $_e;



        $default = (@$_SESSION['sortBy'] == "default") ? 'checked' : '';


        $lowPrice = (@$_SESSION['sortBy'] == "lowPrice") ? 'checked' : '';



        $highPrice = (@$_SESSION['sortBy'] == "highPrice") ? 'checked' : '';


        $lowRate = (@$_SESSION['sortBy'] == "lowRate") ? 'checked' : '';


        $highRate = (@$_SESSION['sortBy'] == "highRate") ? 'checked' : '';


        $topView = (@$_SESSION['sortBy'] == "topView") ? 'checked' : '';


        $topSale = (@$_SESSION['sortBy'] == "topSale") ? 'checked' : '';



        $latest = (@$_SESSION['sortBy'] == "latest") ? 'checked' : '';


        $bestseller = (@$_SESSION['sortBy'] == "bestseller") ? 'checked' : '';





        $temp1 = "<label>
<input class='indeterminate-checkbox add' value='default' " . $default . "  type='radio' name='l2'> <span>" . $_e["Default"] . "</span> </label>



<label>
<input class='indeterminate-checkbox add' value='latest' " . $latest . " type='radio' name='l2'> <span>" . $_e["By Latest Added"] . "</span> </label>




<label>
<input class='indeterminate-checkbox add' value='highPrice' " . $highPrice . " type='radio' name='l2'> <span>" . $_e["By High Price"] . "</span> </label>




<label>
<input class='indeterminate-checkbox add' value='lowPrice' " . $lowPrice . " type='radio' name='l2'> <span>" . $_e["By Low Price"] . "</span> </label>











<label>
<input class='indeterminate-checkbox add' value='bestseller' " . $bestseller . "  type='radio' name='l2'> <span>" . $_e["By Best Seller"] . "</span> </label>



";



        return $temp1;
    }

    public function sortByProduct()
    {
        global $_e;

        $array = array(
            "default" => _uc($_e["Default"]),
            "lowPrice" => _uc($_e["By Low Price"]),
            "highPrice" => _uc($_e["By High Price"]),
            "topSale" => _uc($_e["By Top Sale"]),
            "latest" => _uc($_e["By Latest Added"]),
            "bestseller" => _uc($_e["By Best Seller"]),
        );


        if (!empty($_GET['sortBy'])) {
            $sortBy = $_GET['sortBy'];
        } else {
            $sortBy = getUserSession("sortBy");
        }
        if (empty($sortBy)) {
            $sortBy = 'latest';
        }
        $form[] = array(
            "array" => $array,
            "class" => "form-control productSortBy selectpicker",
            "type" => "select",
            "data" => "",
            "select" => $sortBy,


        );

        $temp = $this->functions->print_form($form, false, false);
        $link = $this->functions->activeLink(false);
        $link = $this->functions->getLinkExpectOneParameter("sortBy", $link);

        if (stripos($link, "?") || !empty($_GET)) {
            //if product page and first parameter not sortBy
            if ($this->webClass->isProductPage() && !stripos($link, "?sortBy="))
                $link .= "?sortBy=";
            else
                $link .= "&sortBy=";
        } else {
            $link .= "?sortBy=";
        }

        $temp .= "<script>
function productSortBy(ths){
sortBy = $(ths).val();
link   = '$link';
location.replace(''+link+sortBy+'');
}
</script>";

        return $temp;
    }

    public function sortByProductMobile()
    {
        global $_e;

        $array = array(
            "default" => _uc($_e["Default"]),
            "lowPrice" => _uc($_e["By Low Price"]),
            "highPrice" => _uc($_e["By High Price"]),
            "topSale" => _uc($_e["By Top Sale"]),
            "latest" => _uc($_e["By Latest Added"]),
            "bestseller" => _uc($_e["By Best Seller"]),
        );


        if (!empty($_GET['sortBy'])) {
            $sortBy = $_GET['sortBy'];
        } else {
            $sortBy = getUserSession("sortBy");
        }
        if (empty($sortBy)) {
            $sortBy = 'latest';
        }
        $form[] = array(
            "array" => $array,
            "class" => "form-control productSortBy selectpicker",
            "type" => "select",
            "data" => "",
            "select" => $sortBy,


        );

        $temp = $this->functions->print_form($form, false, false);
        $link = $this->functions->activeLink(false);
        $link = $this->functions->getLinkExpectOneParameter("sortBy", $link);

        if (stripos($link, "?") || !empty($_GET)) {
            //if product page and first parameter not sortBy
            if ($this->webClass->isProductPage() && !stripos($link, "?sortBy="))
                $link .= "?sortBy=";
            else
                $link .= "&sortBy=";
        } else {
            $link .= "?sortBy=";
        }

        $temp .= "<script>
function productSortBy(ths){
sortBy = $(ths).val();
link   = '$link';
location.replace(''+link+sortBy+'');
}
</script>";

        return $temp;
    }

    private function sortByProductQuery()
    {

        @$sortBy = $_SESSION['sortBy'];

        //First time


        if (!empty($sortBy)) {
            setUserSession("sortBy", $sortBy);
        } else {
            $sortBy = "default";
            setUserSession("sortBy", $sortBy);
        }


        $sql = $sql2 = $sql3 = '';

        $currencyId = $this->currentCurrencyId();

        switch ($sortBy) {
            case "lowprice":

                $sql2 = " ORDER BY product_discount_$currencyId ASC";

                break;
            case "highprice":
                $sql2 = " ORDER BY product_discount_$currencyId DESC";
                break;
            case "lowRate":

                $sql = " , (SELECT AVG(rate)+SUM(rate)/(
SELECT COUNT(id) FROM rating as r2
) FROM rating as ra
WHERE ra.p_id = prodet_id) as rate";
                $sql2 = " ORDER BY rate ASC";
                break;
            case "highRate":
                $sql = " , (SELECT AVG(rate)+SUM(rate)/(
SELECT COUNT(id) FROM rating as r2
) FROM rating as ra
WHERE ra.p_id = prodet_id) as rate";
                $sql2 = " ORDER BY rate DESC";
                break;
            case "topView":
                $sql2 = " ORDER BY view DESC";
                break;
            case "lowView":
                $sql2 = " ORDER BY view ASC";
                break;
            case "topSale":
                $sql2 = " ORDER BY sale DESC";
                break;
            case "lowSale":
                $sql2 = " ORDER BY sale ASC";
                break;
            case "latest":
                $sql2 = " ORDER BY `prodet_timeStamp` DESC ";
                break;
            case "bestseller":

                $sql3 = "www";

                break;
            case "default":
                $sql2 = " ORDER BY sort ASC";
                break;
        }

        return array($sql, $sql2, $sql3);
    }

    private function getCatSearch()
    {
        @$catId = $_SESSION['catId']['abcd'];


        if (!empty($catId)) {


            $catId = filter_var(
                $catId,
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );

            $catId = str_replace("-", "", $catId);
            $catId = str_replace("/", "", $catId);

            $sql = " AND 
`prodet_id` IN 
(SELECT `procat_prodet_id` FROM product_category WHERE `procat_cat_id` LIKE '%$catId%' )";






        } else {
            $sql = "";
        }

        return $sql;
    }

    private function filterBySizeQuery()
    {

        @$filterBy = $_SESSION['SizeFilter'];
        $size = explode(',', $filterBy ?? '');
        $size_c = '';

        for ($i = 0; $i < count($size); $i++) {
            $size_c .= "ps.prosiz_name = '$size[$i]' OR ";

        }

        $size_arr = rtrim($size_c, 'OR ');

        if (!empty($filterBy)) {

            $sql = " AND 
`prodet_id` IN 
(SELECT prosiz_prodet_id 
FROM product_size as ps
WHERE 
ps.prosiz_prodet_id = prodet_id AND 
($size_arr) )";

        } else {
            $sql = "";
        }

        return $sql;
    }

    private function filterBySizeQueryNew()
    {
        @$filterBy = $_SESSION['SizeFilter'];
        $sql = "SELECT `sizes` FROM `filters` WHERE `name` = '$filterBy'";
        $data = $this->dbF->getRow($sql);
        $size = json_decode($data["sizes"], true);
        $size_c = '';

        for ($i = 0; $i < count($size); $i++) {
            $size_c .= "ps.prosiz_name = '$size[$i]' OR ";

        }

        $size_arr = rtrim($size_c, 'OR ');

        if (!empty($filterBy)) {

            $sql = " AND 
`prodet_id` IN 
(SELECT prosiz_prodet_id 
FROM product_size as ps
WHERE 
ps.prosiz_prodet_id = prodet_id AND 
($size_arr) )";

        } else {
            $sql = "";
        }

        return $sql;
    }

    private function filerByPriceRange($curId)
    {

        @$filterByPrice = $_SESSION['priceRange'];

        $minVal = @$_SESSION['priceRange']['minPrice'];
        $maxVal = @$_SESSION['priceRange']['maxPrice'];


        if (!empty($filterByPrice)) {

            $sql = " AND `product_discount_$curId` BETWEEN $minVal AND $maxVal ";

        } else {
            $sql = " AND `product_discount_$curId` > 0";
        }

        return $sql;
    }

    public function productLimitByUserForm()
    {
        global $_e;
        $maxlimit = 150;

        if (!empty($_POST['showPLimit'])) {
            $limitT0 = $_POST['showPLimit'];
        } else {
            $limitT0 = getUserSession("showPLimit");
        }
        if (empty($limitT0)) {
            $limitT0 = intval($this->functions->ibms_setting('productLimit'));
        }

        $limitT0 = abs($limitT0);
        if ($limitT0 > $maxlimit) {
            $limitT0 = $maxlimit;
        }

        setUserSession("showPLimit", $limitT0);
        $form[] = array(
            "class" => "form-control showPLimit padding-F5 selectpicker",
            "type" => "number",
            "min" => "1",
            "required" => "true",
            "max" => "$maxlimit",
            "name" => "showPLimit",
            'value' => $limitT0,
            'format' => "<div class='padding-0 container-fluid'>

<div class='padding-0 input-group'>
{{form}}
<label class='col-xs-2 padding-F5 control-label' style='width: auto;padding-top: 10px;'>" . $_e["Show"] . "</label>
<div class='input-group-addon padding-0'><input class='btn btn-default btn-sm my_btn' type='button' value='" . $_e["Submit"] . "' id='limit_sub'></div>
</div>
</div>
"
        );

        $link = $this->functions->activeLink(false);
        $form["form"] = array(
            "class" => "form-horizontal",
            "type" => "form",
            'method' => "post",
            'id' => "from_limit"
        );

        $temp = $this->functions->print_form($form, false, false);
        return $temp;
    }

    public function productLimitByUserFormMob()
    {
        global $_e;
        $maxlimit = 150;

        if (!empty($_POST['showPLimit'])) {
            $limitT0 = $_POST['showPLimit'];
        } else {
            $limitT0 = getUserSession("showPLimit");
        }
        if (empty($limitT0)) {
            $limitT0 = intval($this->functions->ibms_setting('productLimit'));
        }

        $limitT0 = abs($limitT0);
        if ($limitT0 > $maxlimit) {
            $limitT0 = $maxlimit;
        }

        setUserSession("showPLimit", $limitT0);
        $form[] = array(
            "class" => "form-control showPLimitMob padding-F5 selectpicker",
            "type" => "number",
            "min" => "1",
            "required" => "true",
            "max" => "$maxlimit",
            "name" => "showPLimit",
            'value' => $limitT0,
            'format' => "<div class='padding-0 container-fluid'>
<div class='padding-0 input-group'>
{{form}}
<div class='input-group-addon padding-0'><input class='btn btn-default btn-sm my_btn' type='button' value='" . $_e["Submit"] . "' id='limit_subMob'></div>
</div>
</div>
"
        );

        $link = $this->functions->activeLink(false);
        $form["form"] = array(
            "class" => "form-horizontal",
            "type" => "form",
            'method' => "post",
            'id' => "from_limitMob"
        );

        $temp = $this->functions->print_form($form, false, false);
        return $temp;
    }

    public function productLimitShowOnWeb()
    {
        if (!empty($_POST['showPLimit'])) {
            $limitT0 = $_POST['showPLimit'];
        } else {
            $limitT0 = getUserSession("showPLimit");
        }
        if (empty($limitT0)) {
            $limitT0 = intval($this->functions->ibms_setting('productLimit'));
        }
        $limitT0 = abs($limitT0);
        setUserSession("showPLimit", $limitT0);
        return $limitT0;
    }

    public function AllProducts($QuickViewId = false)
    {
        $currentPage = isset($_GET['page']) ? floatval($_GET['page']) : 0;
        $limitT0 = $this->productLimitShowOnWeb();
        $limitFrom = 0;

        //product sort by filteration, e.g by price, by sale etc
        $sortByArray = $this->sortByProductQuery();
        $newField = $sortByArray[0];
        $sortGroupBy = $sortByArray[1];
        $best_seller_sql = $sortByArray[2];

        $sql = "SELECT `detail`.*, `setting`.`setting_val`
        $newField
        FROM
        `proudct_detail` as detail join `product_setting` as setting
        on `detail`.`prodet_id` = `setting`.`p_id`
        WHERE
        `setting`.`setting_name`='publicAccess'
        AND `setting`.`setting_val`='1'
        AND `detail`.`product_update`='1' $sortGroupBy";


        # best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
        $best_seller_products = array();
        if ($best_seller_sql != '') {
            $best_seller_products = $this->newBestSellerSQL();
        }

        $tempTId = $this->functions->setTempTableVal('Query', $sql);

        $sql .= " LIMIT $limitFrom,$limitT0 ";

        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
        $productIds = $this->dbF->getRows($sql);

        $mergerd_products = $best_seller_products + $productIds;
        return $this->productPrint($mergerd_products, $QuickViewId, $products);

    }

    public function AllProductDeals()
    {
        $limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));
        $limitFrom = 0;
        $sql = "SELECT id,name,price,image,slug,package,view_type FROM product_deal WHERE publish = '1' ORDER BY sort ASC ";
        $tempTId = $this->functions->setTempTableVal('Query', $sql);

        $sql .= " LIMIT $limitFrom,$limitT0";
        $deals = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
        $productDeals = $this->dbF->getRows($sql);
        foreach ($productDeals as $val) {
            $deals .= $this->pBoxDeal($val);
        }
        if (empty($productDeals))
            $deals = false;
        return $deals;
    }

    public function productDealsByCategory($category, $QuickViewId = false, $hasId = true)
    {
        $sql = "SELECT * FROM `tree_data` WHERE id = ? ";
        if ($hasId == false) {
            $sql = "SELECT * FROM `tree_data` WHERE nm = ?";
        }
        $catData = $this->dbF->getRow($sql, array($category));
        if (!$this->dbF->rowCount) {
            return false;
        }

        $catId = $catData['id'];
        $catId = $this->getSubCatIds($catId); //array

        $LIKE = "";
        foreach ($catId as $val) {
            $cId = $val;
            $LIKE .= " `category` LIKE '%$cId%' OR";
        }
        $LIKE = trim($LIKE, "OR");

        $limitFrom = 0;
        $limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));

        //Find Product That in this category
        $sql = "SELECT id,name,price,image,slug,view_type FROM product_deal WHERE publish = '1' AND $LIKE  ORDER BY sort ASC ";
        $tempTId = $this->functions->setTempTableVal('Query', $sql);
        $deals = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productDeals = $this->dbF->getRows($sql);
        foreach ($productDeals as $val) {
            $deals .= $this->pBoxDeal($val);
        }
        if (empty($productDeals))
            $deals = false;
        return $deals;
    }

    public function AllDealsPackage($id)
    {
        $sql = "SELECT * FROM product_deal WHERE publish = '1' AND id = '$id' ";
        $data = $this->dbF->getRow($sql);

        $package = unserialize($data['package']);
        $prodcts = '';
        if (empty($package)) {
            $package = array();
        }
        foreach ($package as $p) {
            $pId = $p;
            $prodcts .= $this->dealProductChoice($pId, $data);
        }

        return $prodcts;
    }

    public function checkOutProductPrice($pId, $currencyId)
    {
        $sql = "SELECT proadc_price FROM product_addcost WHERE
proadc_prodet_id = '$pId'
AND proadc_name = 'checkout_price'
AND proadc_cur_id = '$currencyId'
AND proadc_price > 0";
        $data = $this->dbF->getRow($sql);
        return $data['proadc_price'];
    }

    public function checkOutProductIncludePrice($pId, $currencyId)
    {
        $sql = "SELECT * FROM product_addcost 
WHERE proadc_prodet_id = '$pId' 
AND proadc_name = 'when_cart_price'
AND proadc_cur_id = '$currencyId'
AND proadc_price > 0
";
        $data = $this->dbF->getRow($sql);
        return $data['proadc_price'];
    }

    /**
     * Work as deal offer products and check out offers products.
     *
     * @param $pId
     * @param $dealData
     * @param bool|false $checkOutOffer
     * @return string
     */
    private function dealProductChoice($pId, $dealData, $checkOutOffer = false)
    {
        //if $checkOutOffer is true, $dealData will not be use
//functions use to print product data, in deal detail view, or checkout offer at cart page.
        global $_e;
        $echo = "";
        $data = $this->productData($pId);
        if (empty($data)) {
            return false;
        }

        $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
        $inventoryLimit = ($inventoryLimit == '1' ? true : false);

        $productImage = $this->productSpecialImage($pId, 'main');

        if ($productImage == "") {
            $productImage = "default.jpg";
        }
        $productThumb = $this->webClass->resizeImage($productImage, 250, 300, false);

        $pName = translateFromSerialize($data['prodet_name']);
        $currencyId = $this->currentCurrencyId();
        $currencySymbol = $this->currentCurrencySymbol();
        $storeId = $this->getStoreId();

        $oldPriceDiv = $newPriceDiv = $oldPriceNormal = $newPriceNormal = '';
        if ($checkOutOffer == false) {
            $price = unserialize($dealData['price']);
            $priceDefault = $price[$currencyId];
            $addToCartButton = '';
            $js_data_attrs = '';

        } else {

            $addToCartButton = "<div class='cart_btn text-center margin-5D  cursor AddToCart_$pId' onclick='addToCart(this,$pId,true);'>
{$_e['Add To Cart']}
</div>";
            $pPriceData = $this->productF->productPrice($pId, $currencyId);
            //$pPriceData Return , currency id,international shipping, price, id,
            $pPrice = $priceDefault = $pPriceData['propri_price'];

            $newPrice = $checkOutPrice = $checkOutOffer['proadc_price'];


            $discountP = $priceDefault - $checkOutPrice;
            $discountFormat = 'price';
            if ($priceDefault != $checkOutPrice) {
                $oldPriceNormal = $priceDefault . " " . $currencySymbol;
                $newPriceNormal = $checkOutPrice . " " . $currencySymbol;
                $oldPriceDiv = ' <span class="oldPrice tabprice"><span class="productOldPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . ' </span>';
                $newPriceDiv = '<span class="NewDiscountPrice productPrice_' . $pId . '">' . $checkOutPrice . '</span> ' . $currencySymbol . '';
            } else {
                $oldPriceNormal = "";
                $newPriceNormal = $priceDefault . " " . $currencySymbol;
                $oldPriceDiv = "";
                $newPriceDiv = '<span class="NewDiscountPrice price ">' . $priceDefault . '</span>';
            }



            $js_data_attrs = " data-discountP             = '$discountP'
data-discountFormat        = '$discountFormat'
data-discountDefaultPrice  = '$newPrice'
data-defaultPrice          = '$priceDefault' ";



        }

        $hasScaleVal = $this->functions->developer_setting('product_Scale');
        $hasColorVal = $this->functions->developer_setting('product_color');

        $hasWebOrder_with_Scale = $this->functions->developer_setting('webOrder_with_Scale');
        $hasWebOrder_with_color = $this->functions->developer_setting('webOrder_with_color');

        $hasScale = ($hasScaleVal == '1' ? true : false);
        $hasColor = ($hasColorVal == '1' ? true : false);

        /*
        *
        Info Of is size & color insert in product either it is out of stock
        Or either it is out of store,
        */

        if ($inventoryLimit) {
            $getInfo = $this->inventoryReport($pId);
        } else {
            $getInfo = $this->productSclaeColorReport($pId);
        }
        $getInfoReport = $getInfo['report'];

        if ($getInfo['scale'] == false && @$hasWebOrder_with_Scale == '0') {
            //if scale not found then make scale data empty,
//if product scale allow from setting and dont have inventory, it will make scale val to 0
// we will assume scale not allow from setting for javascript
            $scaleDiv = "";
            $hasScaleVal = 0;
            $hasScale = false;
        }
        if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {
            //if color not found then make color data empty,
//if product color allow from setting and dont have inventory, it will make color val to 0
// we will assume color not allow from setting for javascript
            $colorDiv = "";
            $hasColorVal = 0;
            $hasColor = false;
        }

        //Make Color Divs,, print where you want
        $colorDiv = "";
        if ($hasColor) {
            $colorDiv = $this->getColorsDiv($pId, $storeId, '0', $currencyId, $currencySymbol, '0', $hasScale);

            $colorDiv = <<<COLORDIV

<div id='size_color' class='container_detail_RightR_color choice_color'>
<dl id='sample' class='dropdown_select detail_top_r_size'>
<dt><a><span>{$_e['Select Color']}</span></a></dt>
<dd>
<ul> {$colorDiv} </ul>
</dd>
</dl> 
</div>

COLORDIV;

        }

        //Make Scale Divs,, print where you want
        $isCustomSize = false;
        $customSizeForm = '';

        $scaleDiv = "";
        if ($hasScale) {
            $scaleDiv = $this->getScalesDiv($pId, $hasColor, $storeId, $currencyId, $currencySymbol);
        }
        //Custom Size
        $isCustomSize = false;
        if ($this->functions->developer_setting('product_customSize') == '1' && true == false) { //stop cutome size in checkout offer
            $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
            $customData = $this->dbF->getRows($sql);

            if (!empty($customData)) {
                @$customSize = $customData[0]['type_id'];
                if ($customSize != "0" && !empty($customSize)) {
                    $isCustomSize = true;

                    $customSizeForm = $this->customSizeForm($pId, $customSize);
                    if ($customSizeForm == false) {
                        $isCustomSize = true;
                        $customSize = 0;
                    } else {
                        $customSize_price = $this->customSizeArrayFilter($customData, $currencyId);
                        $customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
                        $onclick = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '"';
                        $scaleDiv = $scaleDiv . $this->getScaleDivFormat($pId, 'Custom', -1, $customSize_price, -1, '', $onclick);
                    }

                }//if customSize==0 end
            } //if customData end

        } // if developer setting end
//Custom Size End

        if (!empty($scaleDiv)) {
            if ($isCustomSize) {
                $echo .= $this->functions->blankModal("Custom Size", "customF_$pId", $customSizeForm, "Close");
            }

            $scaleDiv = <<<SIZEDIV

<div id='size_color' class='container_detail_RightR_color choice_color'>
<dl id='sample_{$pId}' class='dropdown detail_top_r_size'>
<dt><a><span> {$_e['Select Scale']} </span></a></dt>
<dd>
<ul> {$scaleDiv}    </ul>
</dd>
</dl> 
</div>            
SIZEDIV;

        }


        $isSingleProduct = false;
        if (!$hasScale && !$hasColor) {
            $isSingleProduct = true;
        }

        $link = WEB_URL . "/detail?pId=$pId";

        $inventoryLimit = ($inventoryLimit == true ? "1" : "0");
        //print jsInfo after body start, or in product div
        $jsInfo = "
<!-- javascript Info use in js-->
<input type='hidden' class='dealsProducts' value='$pId'/>
<input type='hidden' id='currency_$pId' value= '$currencySymbol'
{$js_data_attrs} />
<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='checkout_$pId' value='$pId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>

<input type='hidden' id='order_with_Color_$pId' value='$hasWebOrder_with_color'/>
<input type='hidden' id='order_with_Scale_$pId' value='$hasWebOrder_with_Scale'/>
<input type='hidden' id='deatilStockCheck_$pId' value='$inventoryLimit' >
$getInfoReport
<!-- javascript Info use in js End-->";


        $stockStatus = $this->productF->hasStock($pId);
        if ($stockStatus) {
            $stockStatus = _uc($_e['In Stock']);
        } else {
            $stockStatus = _uc($_e['Out Stock']);
        }


        if ($checkOutOffer) {
            $productThumb = $this->webClass->resizeImage($productImage, 150, 210, false);
            //checkout offer view





            /*Page cart*/
            $echo .= "<div class='container-fluid padding-0 checkOutOffer' id='p$pId'>
$jsInfo
<div class='col-sm-4 padding-0 text-center checkout_img'>
<a href='$link' target='_blank'><img src='$productThumb' alt='$pName' class='glow grow thumbnail img-responsive display-inlineBlock' loading='lazy'/></a>
</div>
<div class='col-sm-8 padding-F5 text-center-xs'>
<h4 class='tmH4 h4 pName_$pId'><a href='$link' target='_blank'>$pName</a></h4>
$oldPriceDiv
$newPriceDiv


{$colorDiv}


{$scaleDiv}

" .



                $addToCartButton . "<div class='padding-0' style='height: 22px;  margin-top: 5px;'>
<small id='stock_$pId'></small>
</div>
</div>";

            if ($isSingleProduct) {
                $echo .= "<script>
$(document).ready(function () {
productStockCheck($pId, 0, 0);
});
</script>";
            }

            echo "
</div>";
        } else {
            //deal offer detail view
//working on product deal package view, color or size
            $echo .= "<div class='container-fluid padding-0 pDealBox' id='p$pId'>
$jsInfo
<div class='col-sm-4 padding-0 text-center'>
<a href='$link' target='_blank'><img src='$productThumb' alt='$pName' class='glow grow thumbnail img-responsive display-inlineBlock' loading='lazy'/></a>
</div>
<div class='col-sm-8 padding-0 text-center-xs'>
<h4 class='tmH4 h4 pName_$pId'><a href='$link' target='_blank'>$pName</a></h4>
$newPriceDiv
$oldPriceDiv
<div id='size_color' class='container_detail_RightR_color choice_color'>
$colorDiv
</div>
<div class='detail_top_r_size' id='size_radio'>
$scaleDiv
</div>
<div class='padding-0' style='height: 34px;  margin-top: 10px;'>
<small id='stock_$pId'></small>
</div>
</div>";

            if ($isSingleProduct) {
                $echo .= "<script>
$(document).ready(function () {
productStockCheck($pId, 0, 0);
});
</script>";
            }

            echo "
</div>";
        }



        return $echo;
    }

    /**
     * Work as deal offer products and check out offers products.
     *
     * @param $pId
     * @param $dealData
     * @param bool|false $checkOutOffer
     * @return string
     */
    private function doNotForgetProductChoice($pId, $dealData, $checkOutOffer = false)
    {
        //if $checkOutOffer is true, $dealData will not be use
//functions use to print product data, in deal detail view, or checkout offer at cart page.
        global $_e;
        $echo = "";
        $data = $this->productData($pId);
        if (empty($data)) {
            return false;
        }

        $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
        $inventoryLimit = ($inventoryLimit == '1' ? true : false);

        $productImage = $this->productSpecialImage($pId, 'main');

        if ($productImage == "") {
            $productImage = "default.jpg";
        }
        $productThumb = $this->webClass->resizeImage($productImage, 250, 300, false);


        $pName = translateFromSerialize($data['prodet_name']);
        $currencyId = $this->currentCurrencyId();
        $currencySymbol = $this->currentCurrencySymbol();
        $storeId = $this->getStoreId();

        $oldPriceDiv = $newPriceDiv = $oldPriceNormal = $newPriceNormal = '';
        if ($checkOutOffer == false) {
            $price = unserialize($dealData['price']);
            $priceDefault = $price[$currencyId];
            $addToCartButton = '';
            $js_data_attrs = '';

        } else {

            $addToCartButton = "<div class='button_side2 AddToCart_$pId' onclick='donot_addToCart(this,$pId);'>  {$_e['Buy to']}  </div>";

            $pPriceData = $this->productF->productPrice($pId, $currencyId);
            //$pPriceData Return , currency id,international shipping, price, id,
            $pPrice = $priceDefault = $pPriceData['propri_price'];

            $discount = $this->productF->productDiscount($pId, $currencyId);
            $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
            $newPriceDis = $pPrice - $discountPrice;

            $newPrice = $checkOutPrice = $newPriceDis;


            $discountP = $priceDefault - $checkOutPrice;
            $discountFormat = 'price';
            if ($priceDefault != $checkOutPrice) {
                $oldPriceNormal = $priceDefault . " " . $currencySymbol;
                $newPriceNormal = $checkOutPrice . " " . $currencySymbol;
                $oldPriceDiv = ' <span class="productOldPrice_' . $pId . '">' . $pPrice . ' ' . $currencySymbol . ' </span>';
                $newPriceDiv = $checkOutPrice . ' ' . $currencySymbol;

            } else {
                $oldPriceNormal = "";
                $newPriceNormal = $priceDefault . " " . $currencySymbol;
                $oldPriceDiv = "";
                $newPriceDiv = $priceDefault . " " . $currencySymbol;
            }



            $js_data_attrs = " data-discountP             = '$discountP'
data-discountFormat        = '$discountFormat'
data-discountDefaultPrice  = '$newPrice'
data-defaultPrice          = '$priceDefault' ";



        }

        $hasScaleVal = $this->functions->developer_setting('product_Scale');
        $hasColorVal = $this->functions->developer_setting('product_color');

        $hasWebOrder_with_Scale = $this->functions->developer_setting('webOrder_with_Scale');
        $hasWebOrder_with_color = $this->functions->developer_setting('webOrder_with_color');

        $hasScale = ($hasScaleVal == '1' ? true : false);
        $hasColor = ($hasColorVal == '1' ? true : false);

        /*
        *
        Info Of is size & color insert in product either it is out of stock
        Or either it is out of store,
        */

        if ($inventoryLimit) {
            $getInfo = $this->inventoryReport($pId);
        } else {
            $getInfo = $this->productSclaeColorReport($pId);
        }
        $getInfoReport = $getInfo['report'];

        if ($getInfo['scale'] == false && $hasWebOrder_with_Scale == '0') {
            //if scale not found then make scale data empty,
//if product scale allow from setting and dont have inventory, it will make scale val to 0
// we will assume scale not allow from setting for javascript
            $scaleDiv = "";
            $hasScaleVal = 0;
            $hasScale = false;
        }
        if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {
            //if color not found then make color data empty,
//if product color allow from setting and dont have inventory, it will make color val to 0
// we will assume color not allow from setting for javascript
            $colorDiv = "";
            $hasColorVal = 0;
            $hasColor = false;
        }

        //Make Color Divs,, print where you want
        $colorDiv = "";
        if ($hasColor) {
            $colorDiv = $this->getColorsDiv($pId, $storeId, '0', $currencyId, $currencySymbol, '0', $hasScale);


            $colorDiv = <<<COLORDIV

<div id='size_color' class='container_detail_RightR_color choice_color'>
<dl id='sample' class='dropdown_select detail_top_r_size dropdown_select'>
<dt><a><span>{$_e['Select Color']}</span></a></dt>
<dd>
<ul> {$colorDiv} </ul>
</dd>
</dl> 
</div>

COLORDIV;

        }

        //Make Scale Divs,, print where you want
        $isCustomSize = false;
        $customSizeForm = '';

        $scaleDiv = "";
        if ($hasScale) {
            $scaleDiv = $this->getScalesDiv($pId, $hasColor, $storeId, $currencyId, $currencySymbol);
        }
        //Custom Size
        $isCustomSize = false;
        if ($this->functions->developer_setting('product_customSize') == '1' && true == false) { //stop cutome size in checkout offer
            $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
            $customData = $this->dbF->getRows($sql);

            if (!empty($customData)) {
                @$customSize = $customData[0]['type_id'];
                if ($customSize != "0" && !empty($customSize)) {
                    $isCustomSize = true;

                    $customSizeForm = $this->customSizeForm($pId, $customSize);
                    if ($customSizeForm == false) {
                        $isCustomSize = true;
                        $customSize = 0;
                    } else {
                        $customSize_price = $this->customSizeArrayFilter($customData, $currencyId);
                        $customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
                        $onclick = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '"';
                        $scaleDiv = $scaleDiv . $this->getScaleDivFormat($pId, 'Custom', -1, $customSize_price, -1, '', $onclick);
                    }

                }//if customSize==0 end
            } //if customData end

        } // if developer setting end
//Custom Size End

        if (!empty($scaleDiv)) {
            if ($isCustomSize) {
                $echo .= $this->functions->blankModal("Custom Size", "customF_$pId", $customSizeForm, "Close");
            }

            $scaleDiv = <<<SIZEDIV

<div id='size_color' class='container_detail_RightR_color choice_color'>
<dl id='sample' class='dropdown detail_top_r_size dropdown_select'>
<dt><a><span> {$_e['Select Scale']} </span></a></dt>
<dd>
<ul> {$scaleDiv}    </ul>
</dd>
</dl> 
</div>            
SIZEDIV;

        }


        $isSingleProduct = false;
        if (!$hasScale && !$hasColor) {
            $isSingleProduct = true;
        }

        $link = WEB_URL . "/detail?pId=$pId";

        $inventoryLimit = ($inventoryLimit == true ? "1" : "0");
        //print jsInfo after body start, or in product div
        $jsInfo = "
<!-- javascript Info use in js-->
<input type='hidden' class='dealsProducts' value='$pId'/>
<input type='hidden' id='currency_$pId' value= '$currencySymbol'
{$js_data_attrs} />
<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='donotoffer_$pId' value='$pId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>

<input type='hidden' id='order_with_Color_$pId' value='$hasWebOrder_with_color'/>
<input type='hidden' id='order_with_Scale_$pId' value='$hasWebOrder_with_Scale'/>
<input type='hidden' id='deatilStockCheck_$pId' value='$inventoryLimit' >
$getInfoReport
<!-- javascript Info use in js End-->";


        $stockStatus = $this->productF->hasStock($pId);
        if ($stockStatus) {
            $stockStatus = _uc($_e['In Stock']);
        } else {
            $stockStatus = _uc($_e['Out Stock']);
        }


        if ($checkOutOffer) {
            $productThumb = $this->webClass->resizeImage($productImage, 150, 210, false);
            //checkout offer view

            if ($hasColor) {
                $color = "<div class='select_box4' id='color_radio'>
{$colorDiv}
</div>";
            } else {
                $color = "";
            }



            $echo .= "<div class='pop_slide_main' id='$pId'>
$jsInfo

<div class='pop_side_top'> <i class='far fa-bell'></i> {$_e['Do not forget to buy to']}
<!-- <div class='number_side'><span>1</span>/2</div> -->
</div>
<!-- pop_side_top close -->

<div class='pop_content'>
<div class='pop_img'>
<div class='pop_img1'>
<img src='$productThumb' alt='$pName' loading='lazy'>
</div>
<!-- pop_img1 close -->
</div>
<!-- pop_img close -->
<div class='pop_content_main'>
<div class='pop_content_main_btn'> <a href='#'>{$_e['Do not forget to buy to']}</a> </div>
<!-- pop_content_main_btn close -->
<div class='selection_side'>$pName</div>
<!-- selection_side close -->
{$color}
<div class='select_box4' id='size_radio'>
{$scaleDiv}
</div>
<!-- select_box4 close -->




<div class='pop_price'> 

" . $newPriceDiv . $oldPriceDiv . "


 </div>
<!-- pop_price close -->
<div class='button_side'>
<div class='button_side1' id='addid'><a href='#'>{$_e['No thanks !']}</a></div> 

" . $addToCartButton . "
<!-- button_side1 close -->
<div class='padding-0'>
<small id='stock_$pId'></small>
</div>
</div>
<!-- button_side close -->
</div>
<!-- pop_content_main close -->
</div>
<!-- pop_content close -->
</div>
<!-- pop_slide_main close -->";

        } else {
            //deal offer detail view
//working on product deal package view, color or size
            $echo .= "<div class='container-fluid padding-0 pDealBox' id='$pId'>
$jsInfo
<div class='col-sm-4 padding-0 text-center'>
<a href='$link' target='_blank'><img src='$productThumb' alt='$pName' class='glow grow thumbnail img-responsive display-inlineBlock' loading='lazy'/></a>
</div>
<div class='col-sm-8 padding-0 text-center-xs'>
<h4 class='tmH4 h4 pName_$pId'><a href='$link' target='_blank'>$pName</a></h4>
$newPriceDiv
$oldPriceDiv
<div id='size_color' class='container_detail_RightR_color choice_color'>
$colorDiv
</div>
<div class='detail_top_r_size' id='size_radio'>
$scaleDiv
</div>
<div class='padding-0'>
<small id='stock_$pId'></small>
</div>
</div>
</div>";

            if ($isSingleProduct) {
                $echo .= "
<script>
$(document).ready(function () {
productStockCheck($pId, 0, 0);     
      
});

</script>



";
            }


        }



        return $echo;
    }

    public function pBoxDeal($data)
    {
        global $_e;
        $id = $data['id'];
        $slug = $data['slug'];
        $name = translateFromSerialize($data['name']);
        $imageR = $data['image'];
        $pro_base_price = 0;
        $price = unserialize($data['price']);
        $package = unserialize($data['package']);
        $products = '';
        $view_type = $data['view_type'];

        foreach ($package as $key => $value) {
            $base_price = $this->productF->productPrice($value);

            $pro_base_price += $base_price['propri_price'];
        }

        $chk_link = "/" . $this->db->dealProduct . $slug;

        $sql = "SELECT `slug` FROM `seo` WHERE `pageLink` = ?";


        $data = $this->dbF->getRow($sql, array($chk_link));
        if ($this->dbF->rowCount > 0) {
            $link = WEB_URL . "/" . $data['slug'];
        } else {
            $link = WEB_URL . "/$slug";
        }
        $web_url = WEB_URL;

        $currencyId = $this->currentCurrencyId();

        $price = isset($price[$currencyId]) ? $price[$currencyId] : "0";
        $currencySymbol = $this->currentCurrencySymbol();
        $dealDiscount = $pro_base_price - $price;
        $dealDiscount = $dealDiscount . ' ' . $currencySymbol;
        $totalpPrice = $pro_base_price . ' ' . $currencySymbol;



        if ($price == '0' || empty($price)) {
            //if price 0 it will not show OR it wil not show in that country
            return "";
        } else {
            $priceDiv = "$price $currencySymbol";
        }

        $viewT = _u($_e['View']);
        $saleT = _u($_e['Sale']);
        $image = $this->functions->resizeImage($imageR, '510', '400', false);

        # get deal setting
        $deal_setting_data = $this->get_deal_setting($id);
        $shortDesc = $this->functions->findArrayFromSettingTable($deal_setting_data, 'sDesc');
        $shortDesc = translateFromSerialize($shortDesc);
        $dealClass = '';
        if ($view_type == '0') {
            $dealClass = 'col3_box_main_add_';
        } else if ($view_type == '1') {
            $dealClass = '';
        }


        $products .= "<div class='col3_box_main " . $dealClass . "' data-id='{$id}'>
<input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>
<a href='{$link}' data-page='{$link}' title='{$name} - Sharkspeed'>
<div class='col3_box_main_img'> 
<img src='{$image}' alt='{$name}' loading='lazy'> 
<div class='col3_box_tag3'>
{$dealDiscount} " . $_e['DISCOUNT'] . "
</div>
</div>
<!-- col3_box_main_img close -->
<div class='col3_box_main_txt'>" . $_e['Package Deal'] . "</div>
<!-- col3_box_main_txt close -->
<div class='col3_box_detail'>
<div class='col3_box_detail_price'>
<h4>{$priceDiv}</h4>
<h6>{$totalpPrice}</h6> </div>
<!-- col3_box_detail_price close -->
<div class='col3_box_detail_txt'> {$name} </div>
<!-- col3_box_detail_txt close -->
</div>
<!-- col3_box_detail close -->
</a>
</div>
<!-- col3_box_main close -->";



        return $products;




        $format = <<<HTML

<div class="product1"  data-id='$id' data-wow-delay="0.2s">

<input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>

<div class="product_main">


<div class="product2_inner visible_responsive">

<img src='{$image}' alt='{$name}' loading='lazy'>

<div class="sales_btn">
<img src="{$web_url}/images/sales.png" alt="sales" loading="lazy">
<div class="tag">{$saleT}</div><!--tag close-->
</div>

</div><!--product2_inner-->


<div class="product1_inner">

<h3>{$name}</h3>

<div class="text_product">
{$shortDesc}
</div><!--text_product close-->

<div class="price_side">
<span class="dealPrice">{$priceDiv}</span>
</div><!--price_side-->

<div class="btn_side_product hvr-sweep-to-bottom">
<a href="{$link}" data-page="{$link}" title=" {$name} - Sharkspeed">
<span><img src="{$web_url}/images/cart2.png" alt="cart" loading="lazy"></span>
{$viewT}
</a>
</div><!--btn_side_product-->

</div><!--product1_inner-->

<div class="product2_inner">

<img src="{$image}" alt="{$name}" class="" loading="lazy">

<div class="sales_btn">
<img src="{$web_url}/images/sales.png" alt="sales" loading="lazy">
<div class="tag">
{$saleT}
</div><!--tag close-->
</div>

</div><!--product2_inner-->

</div>

</div>


HTML;

    }

    public function get_deal_setting($deal_id)
    {

        $sql = " SELECT * FROM product_deal_setting where deal_id = ? ";
        $dataSetting = $this->dbF->getRows($sql, array($deal_id));
        if ($this->dbF->rowCount > 0) {
            $result = $dataSetting;
        } else {
            $result = array();
        }

        return $result;

    }

    public function featuredProducts($limitT0 = false, $QuickViewId = false)
    {
        $limitFrom = 0;
        if ($limitT0 == false) {
            $limitT0 = abs(intval($this->functions->ibms_setting('featuredProductLimit')));
            if ($limitT0 <= 0) {
                $limitT0 = 20;
            }
        }

        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
FROM
`proudct_detail` join `product_setting`
on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
WHERE
`product_setting`.`setting_name`='publicAccess'
AND `product_setting`.`setting_val`='1'
AND `proudct_detail`.feature = '1'
AND `proudct_detail`.`product_update`='1' ORDER BY sort ASC";

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);


        return $this->productPrint($productIds, $QuickViewId);
    }

    public function featuredProducts2($limitT0 = false, $QuickViewId = false)
    {
        //Use for 2nd type of view product..
        $limitFrom = 0;
        if ($limitT0 == false) {
            $limitT0 = abs(intval($this->functions->ibms_setting('featureProduct2Limit')));
            if ($limitT0 <= 0) {
                $limitT0 = 15;
            }
        }

        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
FROM
`proudct_detail` join `product_setting`
on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
WHERE
`product_setting`.`setting_name`='publicAccess'
AND `product_setting`.`setting_val`='1'
AND `proudct_detail`.feature = '2'
AND `proudct_detail`.`product_update`='1' ORDER BY sort ASC";

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);

        if ($QuickViewId === false || $QuickViewId == '') {
            if (isset($_GET['pId'])) {
                $QuickViewId = $_GET['pId'];
            } else {
                $QuickViewId = '0';
            }
        }
        $QuickFind = false; // use in when user share product, and detail show under product
        $products = "";
        foreach ($productIds as $p) {
            if ($QuickViewId == $p['prodet_id']) {
                $QuickFind = true;
            }
            $products .= $this->pBox($p['prodet_id'], false, 2);
        }

        if ($QuickFind === false && $QuickViewId != '0') {
            $products .= $this->pBox($QuickViewId, false, 2);
        }

        return $products;
    }

    public function latestProducts($limitT0 = false, $QuickViewId = false)
    {
        $limitFrom = 0;
        if ($limitT0 == false) {
            $limitT0 = abs(intval($this->functions->ibms_setting('latestProductLimit')));
            if ($limitT0 <= 0) {
                $limitT0 = 20;
            }
        }

        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
FROM
`proudct_detail` join `product_setting`
on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
WHERE
`product_setting`.`setting_name`='publicAccess'
AND `product_setting`.`setting_val`='1'
AND `proudct_detail`.`product_update`='1' ORDER BY prodet_id DESC";

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);


        return $this->productPrint($productIds, $QuickViewId);
    }

    public function topSaleProducts($limitT0 = false, $QuickViewId = false, $viewType = true)
    {
        $limitFrom = 0;
        if ($limitT0 == false) {
            $limitT0 = abs(intval($this->functions->ibms_setting('topSaleProductLimit')));
            if ($limitT0 <= 0) {
                $limitT0 = 20;
            }
        }

        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
FROM
`proudct_detail` join `product_setting`
on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
WHERE
`product_setting`.`setting_name`='publicAccess'
AND `product_setting`.`setting_val`='1'
AND `proudct_detail`.`product_update`='1' ORDER BY sale DESC";
        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);

        $array['viewType'] = $viewType;
        return $this->productPrint($productIds, $QuickViewId, '', $array);
    }

    public function dontForgetToBuyProduct($pId, $relatedIds = false, $viewType = true, $limitT0 = 5, $name = "donotForget")
    {
        $limitFrom = 0;
        $limitT02 = $limitT0 + 10;

        if (empty($relatedIds)) {


            $sql = "SELECT * FROM (
SELECT `proudct_detail`.*, `product_setting`.`setting_val`
FROM
`proudct_detail` join `product_setting`
on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
WHERE
`product_setting`.`setting_name`='publicAccess'
AND `product_setting`.`setting_val`='1'
AND `proudct_detail`.`product_update`='1'

ORDER BY sale DESC  LIMIT $limitFrom,3

) AS temptable
ORDER BY RAND()
LIMIT $limitT0";
            $productIds = $this->dbF->getRows($sql);

        } else {
            if (count($relatedIds) < 3) {

                @$relatedIdsT = array_rand($relatedIds, count($relatedIds));
            } else {

                @$relatedIdsT = array_rand($relatedIds, 3);
            }

            if (is_array($relatedIdsT)) {
                foreach ($relatedIdsT as $id) {
                    $productIds[]['prodet_id'] = $relatedIds[$id];
                }
            } else {
                $productIds[]['prodet_id'] = $relatedIds[$relatedIdsT];
            }
        }

        if (empty($productIds)) {
            $productIds[]['prodet_id'] = false;
        }
        $array['viewType'] = $viewType;
        return $this->productPrint($productIds, '0', '', $array, $name);
    }

    public function relatedProduct($pId, $limit, $viewType = true)
    {
        //Get Pid Category
//return all related categorys product...
        $sql = "SELECT * FROM product_category WHERE procat_prodet_id = '$pId'";
        $categoryData = $this->dbF->getRow($sql);
        if (!$this->dbF->rowCount) {
            return false;
        }
        $cats = explode(",", $categoryData['procat_cat_id']);

        $LIKE = "";
        foreach ($cats as $catIds) {
            //find related Category products
            $LIKE .= " `product_category`.`procat_cat_id` LIKE '%$catIds%' OR";
        }
        $LIKE = trim($LIKE, "OR");

        $limitFrom = 0;
        $limitT0 = $limit;

        //Find Product That in this category
        $sql = "SELECT `prodet_id` FROM `product_category`
JOIN
`proudct_detail`
on `product_category`.`procat_prodet_id` = `proudct_detail`.`prodet_id`
WHERE $LIKE
GROUP BY `proudct_detail`.`prodet_id` ORDER BY RAND()";
        $products = '';

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);

        //remove current id from list
        $productIds = array_delete($productIds, $pId);

        if (!$this->dbF->rowCount || empty($productIds)) {
            return false;
        }

        $array['viewType'] = $viewType;
        return $this->productPrint($productIds, '0', $products, $array);
    }

    public function youMayAlsoLikeProduct($pId, $limit, $viewType = true)
    {
        //Get Pid Category
//return all related categorys product...
        $sql = "SELECT * FROM product_category WHERE procat_prodet_id != '$pId' ORDER BY RAND()";
        $categoryData = $this->dbF->getRow($sql);
        if (!$this->dbF->rowCount) {
            return false;
        }
        $cats = explode(",", $categoryData['procat_cat_id']);

        $LIKE = "";
        foreach ($cats as $catIds) {
            //find related Category products
            $LIKE .= " `product_category`.`procat_cat_id` LIKE '%$catIds%' OR";
        }
        $LIKE = trim($LIKE, "OR");

        $limitFrom = 0;
        $limitT0 = $limit;

        //Find Product That in this category
        $sql = "SELECT `prodet_id` FROM `product_category`
JOIN
`proudct_detail`
on `product_category`.`procat_prodet_id` = `proudct_detail`.`prodet_id`
WHERE $LIKE
GROUP BY `proudct_detail`.`prodet_id` ORDER BY RAND()";
        $products = '';

        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);

        //remove current id from list
        $productIds = array_delete($productIds, $pId);

        if (!$this->dbF->rowCount || empty($productIds)) {
            return false;
        }

        $array['viewType'] = $viewType;
        return $this->productPrint($productIds, '0', $products, $array);
    }

    public function matchingProduct($pId, $limit, $viewType = true)
    {
        //Get Pid Category
//return all related categorys product...
        $data = array();
        $sql_ = "SELECT * FROM `product_setting` WHERE setting_name = 'getlook' AND p_id = ?";
        $data = $this->dbF->getRow($sql_, array($pId));
        if ($this->dbF->rowCount > 0) {
            $getlook_array = unserialize($data['setting_val']);
            $ids = '';
            foreach ($getlook_array as $row_id) {
                $ids .= $row_id . ', ';
            }
            $ids = rtrim($ids, ', ');

            $sql = "SELECT * FROM `proudct_detail` WHERE prodet_id IN ($ids) AND prodet_id NOT IN (SELECT p_id FROM product_setting WHERE setting_name = 'publicAccess' AND setting_val = 0) ORDER BY RAND() LIMIT $limit";
            $rows = $this->dbF->getRows($sql);

            $array['viewType'] = $viewType;
            return $this->productPrint($rows, '0', '', $array);
        } else {
            return false;
        }


    }

    public function matchingProductIds($pId, $limit)
    {
        $data = array();
        $sql_ = "SELECT * FROM `product_setting` WHERE setting_name = 'getlook' AND p_id = ?";
        $data = $this->dbF->getRow($sql_, array($pId));
        if ($this->dbF->rowCount > 0) {
            $getlook_array = unserialize($data['setting_val']);
            $ids = '';
            foreach ($getlook_array as $row_id) {
                $ids .= $row_id . ', ';
            }
            $ids = rtrim($ids, ', ');

            $sql = "SELECT * FROM `proudct_detail` WHERE prodet_id IN ($ids) AND prodet_id NOT IN (SELECT p_id FROM product_setting WHERE setting_name = 'publicAccess' AND setting_val = 0) ORDER BY RAND() LIMIT $limit";
            $rows = $this->dbF->getRows($sql);
            $prodet_ids = array_column($rows, 'prodet_id');
            return $prodet_ids;
        } else {
            return false;
        }


    }

    public function productDetailImages($pId)
    {
        $data = array();
        $sql_ = "SELECT * FROM `product_detail_image` WHERE product_id = ? ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql_, array($pId));
        if ($this->dbF->rowCount > 0) {
            $temp = "";
            foreach ($data as $key => $val) {
                $subHeading = translateFromSerialize(@$val['alt']);
                $desc = translateFromSerialize(@$val['desc']);
                $productImage = @$val['image'];
                $image = $this->functions->resizeImage($productImage, '280', '', false);

                $temp .= '<div class="single_detail item">
                            <div class="imgHolder">
                                <img src="' . $image . '" loading="lazy">
                            </div>
                            <div class="txtHolder">
                                <h5>' . $subHeading . '</h5>
                                <p>' . $desc . '</p>
                            </div>
                </div>';
            }
            return $temp;
        } else {
            return false;
        }

    }

    public function Top_Selling_Products($days = 30, $limitTo = 0, $viewType = "", $QuickViewId = false)
    {

        if ($limitTo <= 0) {
            $limitTo = 20;
        }
        $sql = "SELECT SUBSTRING_INDEX(order_pIds, '-', 1) AS 'prodet_id' FROM order_invoice_product WHERE order_invoice_id IN (SELECT order_invoice_pk FROM order_invoice WHERE invoice_date BETWEEN CURDATE()-INTERVAL $days Day AND CURDATE() ORDER BY order_invoice.invoice_date DESC) AND order_pIds != '-' AND order_pIds NOT LIKE '0-%' GROUP BY prodet_id HAVING prodet_id != 5854 ORDER BY COUNT('prodet_id') DESC LIMIT $limitTo";
        $productIds = $this->dbF->getRows($sql);
        $array['viewType'] = $viewType;
        $temp = "";


        foreach ($productIds as $key => $val) {
            $pId = $val['prodet_id'];
            $data = $this->productData($pId);


            if (isset($data) && !empty($data)) {
                $productSlug = $data['slug'];
                $productImage = $this->productSpecialImage($pId, 'main');
                $productImage = $this->functions->resizeImage($productImage, '600', '', false);
                $pName = translateFromSerialize($data['prodet_name']);
                $link = WEB_URL . "/" . $productSlug;
                $headerName = $this->dbF->hardWords('Best Selling Right Now', false);
                $currencyId = $this->currentCurrencyId();
                $pPriceData = $this->productF->productPrice($pId, $currencyId);
                $discount = $this->productF->productDiscount($pId, $currencyId);
                @$discountFormat = $discount['discountFormat'];
                @$discountP = $discount['discount'];
                @$discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
                $actualPrice = @$pPriceData['propri_price'];
                $currencySymbol = $this->currentCurrencySymbol();
                $priceDiv = "";
                if ($discountPrice != "" || $discountPrice > 0) {
                    $newPrice = $actualPrice - $discountPrice;
                    $priceDiv = '
                    <h3>' . $actualPrice . '<span class="currencysymbol">' . $currencySymbol . '</span></h3>
                    <h4>' . $newPrice . '<span class="currencysymbol">' . $currencySymbol . '</span></h4>
                ';
                } else {
                    $priceDiv = '
                    <h4>' . $actualPrice . '<span class="currencysymbol">' . $currencySymbol . '</span></h4>
                ';
                }


                $temp .= '
                <div class="col3_box_main special_box">
                    <a href="' . $link . '" data-page="' . $link . '" title="' . $pName . ' - Sharkspeed">
                    <div class="col3_box_main_img"> 
                        <img src="' . $productImage . '" alt="'.$pName.'" loading="lazy">
                        <div class="col3_box_main_txt">' . $headerName . '</div>
                        <div class="col3_box_main_txt_main">
                            ' . $priceDiv . ' 
                            <div class="col3_box_main_txt_main_txt">' . $pName . '</div>
                        </div>
                    </div>
                    </a>
                </div>';
            }
        }
        return $temp;


    }

    public function bestSellerProducts($limitT0 = false, $QuickViewId = false, $viewType = true)
    {
        $limitFrom = 0;
        if ($limitT0 == false) {
            $limitT0 = abs(intval($this->functions->ibms_setting('bestSellerProductLimit')));
            if ($limitT0 <= 0) {
                $limitT0 = 20;
            }
        }

        $sql = " SELECT * FROM `best_seller_products` b
    LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
    WHERE b.publish='1' ORDER BY b.SORT ASC ";
        $sql .= " LIMIT $limitFrom,$limitT0";
        $productIds = $this->dbF->getRows($sql);

        $array['viewType'] = $viewType;

        return $this->productPrint($productIds, $QuickViewId, '', $array);
    }

    public function invRelatedProducts($productIds = false, $QuickViewId = false, $viewType = true)
    {
        $temp = '';

        # code...

        $sql = " SELECT * FROM `proudct_detail` b WHERE b.prodet_id =?";

        $productIds = $this->dbF->getRows($sql, array($productIds));




        $array['viewType'] = $viewType;

        $temp = $this->productPrint($productIds, $QuickViewId, '', $array);


        return $temp;




    }

    public function productSearch($key)
    {
        $limitFrom = 0;
        $limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));
        $key = addslashes($key);
        //search All related Match products
        $sql = "SELECT prodet_id,prodet_name,product_update
FROM `proudct_detail`
WHERE prodet_name LIKE '%$key%'
GROUP BY prodet_id
UNION
SELECT prodet_id,prodet_name,product_update
FROM `proudct_detail`
WHERE prodet_name LIKE '%$key%'";

        $keyArray = explode(" ", $key);
        if (sizeof($keyArray) > 1) {
            foreach ($keyArray as $key2) {
                if (intval($key2) > 0) {
                    continue;
                }
                $sql .= " OR prodet_name LIKE '%$key2%'";
            }
        }
        $sql .= " GROUP BY prodet_id";

        $tempTId = $this->functions->setTempTableVal('Query', $sql);

        $sql .= " LIMIT $limitFrom,$limitT0";

        $productIds = $this->dbF->getRows($sql);
        $productIds = $this->dbF->getRows($sql);
        if (!$this->dbF->rowCount) {
            return "";
        }
        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
        foreach ($productIds as $p) {
            $products .= $this->pBox($p['prodet_id']);
        }
        return $products;
    }

    public function makeSearchLabel($parameter, $val)
    {
        return "<div class='btn searchLabel btn-xs btn-default'>$val
<div class='btn btn-xs'>
<i onclick='removeFromSearch(\"$parameter\",\"$val\")' class='glyphicon glyphicon-remove'></i></div>
</div>";
    }

    public function productAdvanceSearchLabels()
    {
        //search word
        $searchWord = '';
        if (isset($_GET['s'])) {
            $key = $_GET['s'];
            $searchWord .= $this->makeSearchLabel("s", $key);
        }

        // search price
        $price = "";
        if (isset($_GET['pMin']) || isset($_GET['pMax'])) {
            $pMin = floatval($_GET['pMin']);
            $pMax = floatval($_GET['pMax']);
            $searchWord .= $this->makeSearchLabel("price", "$pMin - $pMax");
        }

        //search by color
        $color = "";
        if (isset($_GET['color'])) {
            $colorKey = $_GET['color'];
            $colorKey = trim($colorKey, ",");
            $colorKey = explode(",", $colorKey);
            foreach ($colorKey as $val) {
                $searchWord .= $this->makeSearchLabel("color", $val);
            }
        }

        //search by size
        $size = "";
        if (isset($_GET['size'])) {
            $sizeKey = $_GET['size'];
            $sizeKey = trim($sizeKey, ",");
            $sizeKey = explode(",", $sizeKey);
            foreach ($sizeKey as $val) {
                $searchWord .= $this->makeSearchLabel("size", $val);
            }
        }

        return $searchWord;
    }

    public function productAdvanceSearch()
    {
        $limitFrom = 0;
        $limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));

        //search word
        $searchWord = "";
        if (isset($_GET['s'])) {
            $key = $_GET['s'];
            $key = addslashes($key);
            $key = strtolower($key);

            $temp = str_replace('Å', '%', $key);
            $temp = str_replace('Ä', '%', $temp);
            $temp = str_replace('å', '%', $temp);
            $temp = str_replace('ä', '%', $temp);

            $searchWord = " (`prodet_name` LIKE '%$temp%' OR `prodet_shortDesc` LIKE '%$temp%') ";
        } else {
            $searchWord = "1=1";
        }


        $limitFrom = 0;
        $limitT0 = $this->productLimitShowOnWeb();
        $limitT0 = 100;
        $currencyId = $this->currentCurrencyId();

        //product sort by filteration, e.g by price, by sale etc
        $sortByArray = $this->sortByProductQuery();
        $filterBySize = $this->filterBySizeQuery();
        $filerByPriceRange = $this->filerByPriceRange($currencyId);
        $newField = $sortByArray[0];
        $sortGroupBy = $sortByArray[1];
        $best_seller_sql = $sortByArray[2];

        //Find Product That in this category




        $sql = "SELECT `prodet_id` $newField
FROM `proudct_detail`
WHERE $searchWord

$filerByPriceRange

$filterBySize
GROUP BY prodet_id $sortGroupBy";





        $tempTId = $this->functions->setTempTableVal('Query', $sql);

        $sql .= " LIMIT $limitFrom,$limitT0";

        $sql;

        # best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
        $best_seller_products = array();
        if ($best_seller_sql == 'www') {
            $productIds = $this->newBestSellerSQL("");

        } else {
            $productIds = $this->dbF->getRows($sql);


            if (!$this->dbF->rowCount) {
                return "";
            }


        }


        $products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
        foreach ($productIds as $p) {
            $products .= $this->pBox($p['prodet_id']);
        }
        return $products;
    }

    public function noOfItemInMyCart()
    {
        $userId = $this->webUserId();
        $tempUser = $this->webTempUserId();

        if ($userId == '0') {
            $sql = "SELECT sum(qty) as qty FROM `cart` WHERE `tempUser` = '$tempUser'";
            $data = $this->dbF->getRow($sql);
        } else {
            $sql = "SELECT sum(qty) as qty FROM `cart` WHERE `userId` = '$userId'";
            $data = $this->dbF->getRow($sql);
        }

        if ($this->dbF->rowCount > 0) {
            if ($data['qty'] != "") {
                return $data['qty'];
            }
        }
        return '0';
    }

    public function wishListInfo()
    {
        $userId = webUserId();
        $tempUser = webTempUserId();

        if ($userId == '0') {
            $sql = "SELECT COUNT(pId) as pId FROM `cartwishlist` WHERE `tempUser` = '$tempUser'";
            $dataC = $this->dbF->getRow($sql);
        } else {
            $sql = "SELECT COUNT(pId) as pId FROM `cartwishlist` WHERE `userId` = '$userId'";
            $dataC = $this->dbF->getRow($sql);
        }

        $array = array('qty' => 0);

        if ($this->dbF->rowCount > 0) {
            $array['qty'] = $dataC['pId'];
            return $array;
        }
        return $array;
    }

    /**
     * @param $pId
     */
    public function productData($pId)
    {
        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
FROM
`proudct_detail` join `product_setting`
on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
WHERE
`product_setting`.`setting_name`='publicAccess'
AND `product_setting`.`setting_val`='1'
AND `proudct_detail`.`product_update`='1'
AND `proudct_detail`.`prodet_id` = '$pId'";
        $data = $this->dbF->getRow($sql);
        return $data;
    }

    /**
     * @param $pId
     * @return customPrice
     */
    public function customSizePrice($pId)
    {
        $currencyId = $this->currentCurrencyId();
        $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId' AND currencyId = '$currencyId'";
        $customData = $this->dbF->getRow($sql);
        $price = $customData['price'];
        $price = empty($price) ? 0 : floatval($price);
        return $price;
    }

    public function three_for_two_data($grandTotal)
    {

        $three_for_2_ibm_cat = intval($this->functions->ibms_setting("checkout_two_for_3_category"));
        $three_for_2_cat_div = "3 For 2 Category";
        $three_for_2_qty = 0;
        $three_for_2_pro_price = array();
        if ($three_for_2_ibm_cat > 0) {
            $three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
        } else {
            $three_for_2_ibm_cat = array();
        }


        ##################### check if product category in 3 For 2 Category START ##############
        $three_for_2_category = "";
        if (sizeof(array_intersect($three_for_2_ibm_cat, $pro_cat)) > 0 && $newPrice > 0) {
            $three_for_2_pro_price[$cartId]["id"] = $pId;
            $three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
            $three_for_2_pro_price[$cartId]["qty"] = $qty;
            $three_for_2_qty += $qty;
            $three_for_2_category = " <img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' height='40' loading='lazy'/>";
        }


        $three_for_2_qty = floor($three_for_2_qty / 3);
        $three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price, $three_for_2_qty);
        $grandTotal = $grandTotal - $three_for_2_minus_price;
        $three_for_2_cat_div = '';

        $result_array = array();
        if ($three_for_2_minus_price > 0) {

            $three_for_2_cat_div = "<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['Three For Two Category'] . " </div>
<div class='sub_2'>({$three_for_2_minus_price}) {$currencySymbol}</div>
</div>
<!--sub_box end-->";

            $result_array['three_for_two_discount'] = $three_for_2_minus_price;
            $result_array['three_for_2_div'] = $three_for_2_cat_div;
            $result_array['grandTotal'] = $grandTotal;
            $result_array['three_for_two_applied'] = true;

        } else {
            $result_array['three_for_two_applied'] = false;
        }



        return $three_for_2_minus_price;

    }
    public function donotForgetToBuyCartProductIds()
    {

        $userId = webUserId();
        $tempUser = webTempUserId();
        $productsDiv = '';

        if ($userId == '0') {
            $sql = "SELECT pId FROM `cart` WHERE `tempUser` = '$tempUser'";
            $dataC = $this->dbF->getRows($sql);
        } else {
            $sql = "SELECT pId FROM `cart` WHERE `userId` = '$userId'";
            $dataC = $this->dbF->getRows($sql);
        }
        if (empty($dataC)) {
            // get from ibms setting
            $sqlIBMS = "SELECT `setting_val` FROM `ibms_setting` WHERE `setting_name` = 'doNotForgetProduct'";
            $IbmsData = $this->dbF->getRow($sqlIBMS);
            $IbmsData__ = $IbmsData['setting_val'];
            if (empty($IbmsData__)) {
                return false;
            } else {
                $IbmsData__Arr = explode(',', $IbmsData__);
                return $IbmsData__Arr;
            }
        } else {
            $pIdT = "";
            foreach ($dataC as $val) {
                $pIdT .= $val['pId'] . ",";
            }
            $pIdT = trim($pIdT, ",");
            $sqlDFP = "SELECT setting_val FROM `product_setting` WHERE setting_name LIKE 'dontForget' AND p_id in ($pIdT)";
            $DFPData = $this->dbF->getRows($sqlDFP);
            if (empty($DFPData)) {

                $sqlIBMS = "SELECT `setting_val` FROM `ibms_setting` WHERE `setting_name` = 'doNotForgetProduct'";
                $IbmsData = $this->dbF->getRow($sqlIBMS);
                $IbmsData__ = $IbmsData['setting_val'];
                if (empty($IbmsData__)) {
                    return false;
                } else {
                    $IbmsData__Arr = explode(',', $IbmsData__);
                    return $IbmsData__Arr;
                }
            } else {
                $dFPId = array();
                foreach ($DFPData as $val2) {
                    $temp = unserialize($val2['setting_val']);
                    if (!empty($temp)) {
                        foreach ($temp as $val3) {
                            $dFPId[] = $val3;
                        }
                    }


                }
            }
            // jawwad ids
            array_unique($dFPId);
            return $dFPId;
        }
    }

    public function cartInfo($productShortView = false, $view = 'first')
    {
        global $_e;

        $userId = webUserId();
        $tempUser = webTempUserId();
        $productsDiv = '';

        if ($userId == '0') {
            $sql = "SELECT * FROM `cart` WHERE `tempUser` = '$tempUser'";
            $dataC = $this->dbF->getRows($sql);
        } else {
            $sql = "SELECT * FROM `cart` WHERE `userId` = '$userId'";
            $dataC = $this->dbF->getRows($sql);
        }

        $array = array('qty' => 0, 'price' => 0, 'items' => 0, 'symbol' => '', 'store' => 0, 'products' => "");

        if ($this->dbF->rowCount > 0) {
            @$totalQty = 0;
            $totalPrice = 0;
            $totalItems = 0;
            $currencyCountry = $this->currentCurrencyCountry();
            $countryPk = $this->currentCountry();
            $currencyId = $this->currentCurrencyId();



            # three for two category start
            $three_for_2_ibm_cat = intval($this->functions->ibms_setting("checkout_two_for_3_category"));
            $three_for_2_cat_div = "3 For 2 Category";
            $three_for_2_qty = 0;
            $three_for_2_pro_price = array();
            if ($three_for_2_ibm_cat > 0) {
                $three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
            } else {
                $three_for_2_ibm_cat = array();
            }
            # three for two category end

            # staple product category start
            $staple_ibm_cat = intval($this->functions->ibms_setting("stapleProduct"));
            $staple_cat_div = "";
            $staple_qty = 0;
            $staple_pro_price = array();
            if ($staple_ibm_cat > 0) {
                $staple_ibm_cat = $this->getSubCatIds($staple_ibm_cat);
            } else {
                $staple_ibm_cat = array();
            }
            # staple product  category end




            $currencySymbol = $this->currentCurrencySymbol();
            $web_url = WEB_URL;
            $qtyT = $this->dbF->hardWords('Quantity', false);

            $pIdsForCheckOutOffer = '';

            foreach ($dataC as $val) {
                $pId = $val['pId'];
                $cartId = $val['id'];
                $salePrice = $val['salePrice'];
                $qty = floatval($val['qty']);
                $dealId = $val['deal']; // if not it is 0
                @$checkout = $val['checkout']; // if not it is 0
                @$info = unserialize($val['info']);

                $pIdsForCheckOutOffer[$pId] = '1';

                @$totalQty += $qty;

                $totalItems++;

                $price = $this->productF->productTotalPrice($val['pId'], $val['scaleId'], $val['colorId'], $val['customId'], $currencyCountry, false);
                $price = floatval($price);

                if (!empty($salePrice) && $salePrice != '') {
                    $price = $salePrice;
                }


                if ($productShortView) {


                    if ($dealId == '0') {
                        $productImage = $this->productSpecialImage($pId, 'main');

                        $data = $this->productData($pId);
                        $pName = translateFromSerialize($data['prodet_name']);
                        $link = WEB_URL . "/detail.php?pId=$pId";
                        $scaleId = $val['scaleId'];
                        $colorId = $val['colorId'];
                        $customId = $val['customId'];

                        $pPriceActual = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);
                        $pPrice = $pPriceActual;
                        $coupon = '';
                        if ($checkout == '1') {
                            $checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
                            $discountPrice = $pPrice - $checkoutPrice;
                            @$discountFormat = "price";
                            @$discountP = $discountPrice;
                        } else {

                            $coupon = $this->getCoupon();
                            if (!empty($coupon)) {
                                $couponApply = true;
                            }

                            $discount = $this->productF->productDiscount($pId, $currencyId, $coupon);

                            @$discountFormat = $discount['discountFormat'];
                            @$discountP = $discount['discount'];
                            $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
                            @$couponHas = $this->productF->productCouponStatus($coupon);
                            if (!$couponHas) {
                                $_SESSION['webUser']['coupon'] = '';
                            }
                        }

                        $newPrice = $pPriceActual - $discountPrice;
                        if (!empty($salePrice) && $salePrice != '') {
                            $newPrice = $salePrice;
                        }

                        $newPrice_simple = $newPrice;
                        $pPrice_simple = $pPrice;
                        $pPrice .= ' ' . $currencySymbol;
                        $newPrice .= ' ' . $currencySymbol;

                        # COLOR AND SIZE WORKING
                        $hasScaleVal = $this->functions->developer_setting('product_Scale');
                        $hasColorVal = $this->functions->developer_setting('product_color');

                        $hasScale = ($hasScaleVal == '1' ? true : false);
                        $hasColor = ($hasColorVal == '1' ? true : false);

                        $pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);

                        $pNames = explode(' - ', $pName);
                        $pName1 = $pNames[0];
                        $pScaleName = isset($pNames[1]) ? $pNames[1] : '';
                        $pColorName = isset($pNames[2]) ? $pNames[2] : '';
                        $slugname = $this->get_product_slugname($pId);
                        $slug_link = WEB_URL . '/' . $this->db->productDetail . $slugname;


                        $pColorName = ($pColorName != '') ? str_replace('padding: 5px 12px;', 'padding: 2px 9px;', $pColorName) : '';

                        $pName1 = "<a href='{$slug_link}'>{$pName1}</a>";

                        $sizeT = $this->dbF->hardWords('Size', false);
                        $colorT = $this->dbF->hardWords('Color', false);

                        $sizeInfo = '';
                        $colorInfo = '';

                        if ($hasScale && $pScaleName) {

                            $sizeInfo = "<div class='info_1'>{$sizeT}:<span>{$pScaleName}</span></div>";

                            if ($customId != '0' && !empty($customId) && $scaleId == '0') {
                                $sizeInfo = "<div class='info_1'>{$sizeT}:</div> <a href='#{$customId}' data-toggle='modal' data-target='#customSizeInfo_{$customId}'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                                $customFieldsData = $this->customSubmitValues($customId);
                                $customFields = $customFieldsData['form'];
                                $sizeModal .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                            }

                            if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                                $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                                $customFields = $this->dealSubmitPackage($info);
                                $sizeModal .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
                            }
                        }

                        if ($hasColor && $pColorName) {
                            $colorInfo = "<div class='info_1 colorspan'>{$colorT}: {$pColorName}</div>";
                        }





                    } else {
                        $dealData = $this->getDealData($dealId);
                        $productImage = $dealData['image'];
                        $pName = $this->getDealNameWeb($dealData);
                        $link = WEB_URL . "/productDeals?deal=$dealId  ";

                        $newPrice = $pPrice = $this->getDealPrice($dealData);
                        $discountPrice = $discount = 0;
                        $discountFormat = $discountP = $pPrice = '';
                        $newPrice_simple = $newPrice;
                        $newPrice .= ' ' . $currencySymbol;

                    }


                    $newPrice = filter_var(
                        $newPrice,
                        FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION
                    );



                    $totalPrice += $newPrice * $qty;
                    if ($productImage == "") {
                        $productImage = "default.jpg";
                    }
                    $productImageReal = $productImage;
                    $productImage = $this->functions->resizeImage($productImage, '50', '', false);


                    if ($view == 'first') {
                        $productsDiv .= "<div class='cart_small glow tr_$cartId'>
<div class='cart_pro_img_div cart_img'>
<a href='$link' data-page='$link' title=' $pName - Sharkspeed' class=''><img src='$productImage' alt='$pName' loading='lazy'></a>
</div>
<div class='top_cart_detials_div'>
<h2 class='r_text_head margin-0'>$pName</h2>
<span class='qtyS '><span class='pQtyS'>$qty</span> X $newPrice</span>
<div>
<span class='newPriceS '>$newPrice</span>
<span class='oldPriceS  xs'><del>$pPrice</del></span>
</div>
</div>
<div class='removeCartproduct' onclick='cartProductRemove(this,$cartId)'>X</div>
</div>";

                    } elseif ($view == 'checkout_popup_side_cart_view') {





                        $hasScaleVal = $this->functions->developer_setting('product_Scale');
                        $hasColorVal = $this->functions->developer_setting('product_color');



                        $hasScale = ($hasScaleVal == '1' ? true : false);
                        $hasColor = ($hasColorVal == '1' ? true : false);



                        $userId = webUserId();
                        $tempUser = webTempUserId();


                        if ($userId == '0') {
                            $sql = "SELECT * FROM `cart` WHERE `tempUser` = '$tempUser'";
                            $dataC = $this->dbF->getRow($sql);
                        } else {
                            $sql = "SELECT * FROM `cart` WHERE `userId` = '$userId'";
                            $dataC = $this->dbF->getRow($sql);
                        }

                        $dealIdT = $dataC['deal'];
                        $infoItemT = $dataC['info'];
                        $scaleId = $dataC['scaleId'];
                        $colorId = $dataC['colorId'];
                        $customId = $dataC['customId'];



                        $isDiscount = false;
                        if ($newPrice != $pPrice) {
                            $isDiscount = true;
                        }

                        $pPrice = empty($pPrice) ? 0 : $pPrice;
                        $newPrice = empty($newPrice) ? 0 : $newPrice;

                        $newPrice .= ' ' . $currencySymbol;

                        $hasDiscount = false;


                        $currencyId = $this->currentCurrencyId();
                        $pPriceData = $this->productF->productPrice($pId, $currencyId);
                        //$pPriceData Return , currency id,international shipping, price, id,
                        $pPriceActual = $pPriceData['propri_price'];



                        if ($newPrice != $pPrice && $pPriceActual != '') {
                            $hasDiscount = true;
                            $oldPriceNormal = $pPrice;

                        } else {
                            $oldPriceNormal = "";

                        }




                        $slugname = $this->get_product_slugname($pId);
                        $slug_link = WEB_URL . '/' . $this->db->productDetail . $slugname;

                        $productImage = $this->functions->resizeImage($productImageReal, '', '200', false);

                        $totalPriceSimple = $newPrice_simple * $qty;

                        ############ Product Categories ##########
                        $pro_cat = $this->productF->product_category($pId);

                        ##################### check if product category in 3 For 2 Category START ##############
                        $three_for_2_category = "";
                        if (sizeof(array_intersect($three_for_2_ibm_cat, $pro_cat)) > 0 && $newPrice_simple > 0) {
                            $three_for_2_pro_price[$cartId]["id"] = $pId;
                            $three_for_2_pro_price[$cartId]["price"] = intval($newPrice_simple);
                            $three_for_2_pro_price[$cartId]["qty"] = $qty;
                            $three_for_2_qty += $qty;
                            $three_for_2_category = " <img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' height='40' loading='lazy'/>";
                        }

                        ##################### check if product category in staple Category START ##############
                        $staple_category = "";
                        if (sizeof(array_intersect($staple_ibm_cat, $pro_cat)) > 0 && $newPrice_simple > 0) {

                            $staple_pro_price[$cartId]["id"] = $pId;
                            $staple_pro_price[$cartId]["price"] = intval($newPrice_simple);
                            $staple_pro_price[$cartId]["qty"] = $qty;
                            $staple_qty += $qty;
                            $staple_category = " <img src='" . WEB_URL . "/webImages/bundle_icon.png' alt='bundle' height='40' loading='lazy'/>";
                        }


                        $scaleDiv1 = "";

                        if (!empty($salePrice) && $salePrice != '') {
                            $staple_category = "";
                        }

                        $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
                        $storeId = $this->getStoreId();
                        $randVal = rand(99, 999999);


                        $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
                        $inventoryLimit = ($inventoryLimit == '1' ? true : false);




                        $hasScaleVal = $this->functions->developer_setting('product_Scale');

                        $hasScale = ($hasScaleVal == '1' ? true : false);


                        if ($inventoryLimit) {
                            $getInfo = $this->inventoryReport($pId);
                        } else {
                            $getInfo = $this->productSclaeColorReport($pId);
                        }
                        $getInfoReport = $getInfo['report'];



                        if ($getInfo['scale'] == false && @$hasWebOrder_with_Scale == '0') {
                            //if scale not found then make scale data empty,
                            //if product scale allow from setting and dont have inventory, it will make scale val to 0
                            // we will assume scale not allow from setting for javascript
                            $scaleDiv = "";
                            $hasScaleVal = 0;
                            $hasScale = false;
                        }



                        if ($hasScale) {

                            $scaleDiv = $this->getScalesDivNew($pId, $cartId, $hasColor, $storeId, $currencyId, $currencySymbol);

                        }


                        $size = $this->dbF->hardWords('Size', false);



                        $scaleMobileDic1 = "";
                        if (!empty($scaleDiv)) {



                            // getScaleIdfromCart

                            $scaleId = $this->getScaleIdfromCart($cartId);

                            $sName = $this->getScaleNameWeb($scaleId);


                            $scaleMobileDic1 = "<div class='storlek_mobile_side'>
        <h3>" . $size . " : " . $sName . "</h3>
        </div>";

                            $scaleDiv1 = "<div class='storlek_main_side'>
<h3>" . $size . "</h3>
<div class='select'>
<div id='size_color' class='container_detail_RightR_color choice_color'> 

<div class='container_detail_RightR_color_heading'>
<dl id='sample_select_" . $cartId . "_" . $pId . "' class='dropdown_select'> <dt><a> <span>" . $sName . "</span></a></dt><dd>
<ul> " . $scaleDiv . "    </ul>
</dd></dl></div></div>
</div>
</div>



<script>
$('#sample_{$cartId}_{$pId} dt a').click(function() {

$(this).parent().next().find('ul').fadeToggle(600);
$('#sample_select_{$cartId}_{$pId} dd ul').fadeOut(600);

});

$('#sample_{$cartId}_{$pId} dd ul li a').click(function() {
var text = $(this).html();  

// show the selected size
$(this).closest('dd').prev('dt').find('a > span').html(text);

$('#sample_{$cartId}_{$pId} dd ul').fadeOut(600);
});


$('#sample_select_{$cartId}_{$pId} dt a').click(function() {
$(this).parent().next().find('ul').fadeToggle(600);
$('#sample_{$cartId}_{$pId} dd ul').fadeOut(600);
});

$('#sample_select_{$cartId}_{$pId} dd a').click(function() {                
// save the selected size
var size_selected = $(this).find('div.color_name.size').html();

// hide the ul
$(this).closest('ul').hide();

// show the selected size
$('#sample_select_{$cartId}_{$pId} > dt > a > span').html(size_selected);
});
</script>

";
                        }

                        $scaleMobileDic1 .= "<div class='quantityParent'>
                                <h3>" . $qtyT . " :</h3>
                                <div class='quantity'>
                                    <select class='number' name='quantity' style='width: 100%;height: 100%;text-align: center;background: #f5f4f4;'>
                                        <option value='1' " . ($qty == 1 ? 'selected' : '') . ">1</option>
                                        <option value='2' " . ($qty == 2 ? 'selected' : '') . ">2</option>
                                        <option value='3' " . ($qty == 3 ? 'selected' : '') . ">3</option>
                                        <option value='4' " . ($qty == 4 ? 'selected' : '') . ">4</option>
                                        <option value='5' " . ($qty == 5 ? 'selected' : '') . ">5</option>
                                        <option value='6' " . ($qty == 6 ? 'selected' : '') . ">6</option>
                                        <option value='7' " . ($qty == 7 ? 'selected' : '') . ">7</option>
                                        <option value='8' " . ($qty == 8 ? 'selected' : '') . ">8</option>
                                        <option value='9' " . ($qty == 9 ? 'selected' : '') . ">9</option>
                                    </select>
                                </div>
                            </div>";

                        $qtyT = $this->dbF->hardWords('Quantity', false);



                        if ($customId != '0') {
                            $totalAllowQty = "9999999"; //If product has custom size, then its Stock qty...
                        } else {
                            $totalAllowQty = $this->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
                        }

                        $stockCheck = $this->functions->developer_setting('product_check_stock');
                        if ($stockCheck == '0') {
                            $totalAllowQty = 9999999;
                        }


                        if (!empty($oldPriceNormal)) {
                            $oldPriceNormals = filter_var(
                                $oldPriceNormal,
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                            );
                        } else {


                            $oldPriceNormals = '0';


                        }

                        $newPrice_simples = filter_var(
                            $newPrice_simple,
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );



                        $save = $oldPriceNormals - $newPrice_simples;

                        if ($this->functions->developer_setting('addQty_custome') == '1') {
                            $addQty_custome = "<div class='addByQtyDiv reload_btn' onmouseout='addByQty(this,$cartId)'>
<input type='number' min='1' max='$totalAllowQty'  onkeypress='addByQty(this,$cartId)' data-prev='$qty' value='$qty' pattern='[0-9]{1,5}' class='addByQty_$cartId' />
</div>
";

                        }

                        $newDesignPrice = "<div class='cart_right_price'>";
                        if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {
                            $newDesignPrice .= "
 <div class='cart_right_price_red'>
<span>
0 {$currencySymbol}
</span>
</div>
";
                        } else {
                            if ($isDiscount) {
                                $newDesignPrice .= "
 
<div class='cart_right_price_red 1'>
<span>
{$newPrice_simple} {$currencySymbol}
</span>
</div>
<div class='cart_right_org_price'>
    {$oldPriceNormal}
</div>";
                            } else {
                                $newDesignPrice .= "
{$oldPriceNormal}
";
                            }
                        }

                        $newDesignPrice .= "</div>";
                        $dealProduct = "";
                        if ($dealId > 0 && $info !== NULL) {
                            $dealProduct .= "<div class='dealProduct'>";

                            foreach ($info as $val) {
                                // Jawwad $info
                                @$pId = $val['pId'];
                                @$scaleId = $val['scaleId'];
                                @$scaleId = !empty($scaleId) ? $this->getScaleNameWeb($scaleId) : "";
                                $name = $this->productF->getProductName($pId);
                                $img = $this->productF->productSpecialImage($pId, 'main');
                                $img = $this->functions->resizeImage($img, 'auto', 160, false);

                                $dealProduct .= "<div class='item'>
                            <div class='image'><img src='{$img}' loading='lazy' alt='{$name}'/></div>
                            <div class='nameFlex'>
                                <div class='name'><h3>{$name}</h3></div>
                                <div class='size'>{$scaleId}</div>
                            </div>
                        </div>";


                            }
                            $dealProduct .= "</div>";
                        }
                        $dealIdT_text = ($dealId > 0) ? "deal" : "";
                        $dealIdT_div = ($dealId > 0) ? "<img src='{$web_url}/webImages/icons/arrow_open.png' class='open_deal' alt='arrow' loading='lazy'>" : "";


                        $productsDiv .= "


<div class='cart_1_inner    1  {$dealIdT_text}     tr_{$cartId}'>
    <div class='cart_1_main'>
        <div class='cart_1_inner_1'>
            <a href='{$slug_link}' data-page='{$slug_link}' title=' {$pName} - Sharkspeed'>
                <img src='{$productImage}' alt='{$pName}' loading='lazy'>
            </a>
        </div>

        <div class='cart_1_inner_2'>
            <div class='cart_left_portion'>
                <h3>{$pName}</h3>
                {$newDesignPrice}
                <h3 class='category'>{$three_for_2_category} {$staple_category}</h3>
                {$scaleMobileDic1}
            </div>
            <div class='cart_right_portion'>
                <div class='update_box' >
                    <img src='{$web_url}/webImages/trash.png' onclick='cartProductRemove( this, {$cartId}, false )' alt='trash' loading='lazy'>
                    {$dealIdT_div}
                </div>
                <div class='update_box'>
                    <img src='{$web_url}/webImages/update_cart_image.png' onclick='updateCartQty(this, {$pId}, {$cartId}, {$scaleId})' alt='update' loading='lazy'>
                </div>
            </div>
        </div>
    </div>
    {$dealProduct}
    </div>
 </div>
";


                    } elseif ($view == 'checkout_popup_side_cart_view_new') {
                        // jawwad=>





                        $hasScaleVal = $this->functions->developer_setting('product_Scale');
                        $hasColorVal = $this->functions->developer_setting('product_color');



                        $hasScale = ($hasScaleVal == '1' ? true : false);
                        $hasColor = ($hasColorVal == '1' ? true : false);



                        $userId = webUserId();
                        $tempUser = webTempUserId();


                        if ($userId == '0') {
                            $sql = "SELECT * FROM `cart` WHERE `tempUser` = '$tempUser'";
                            $dataC = $this->dbF->getRow($sql);
                        } else {
                            $sql = "SELECT * FROM `cart` WHERE `userId` = '$userId'";
                            $dataC = $this->dbF->getRow($sql);
                        }


                        $scaleId = $dataC['scaleId'];
                        $colorId = $dataC['colorId'];
                        $customId = $dataC['customId'];



                        $isDiscount = false;
                        if ($newPrice != $pPrice) {
                            $isDiscount = true;
                        }

                        $pPrice = empty($pPrice) ? 0 : $pPrice;
                        $newPrice = empty($newPrice) ? 0 : $newPrice;

                        $newPrice .= ' ' . $currencySymbol;

                        $hasDiscount = false;


                        $currencyId = $this->currentCurrencyId();
                        $pPriceData = $this->productF->productPrice($pId, $currencyId);
                        //$pPriceData Return , currency id,international shipping, price, id,
                        $pPriceActual = $pPriceData['propri_price'];



                        if ($newPrice != $pPrice && $pPriceActual != '') {
                            $hasDiscount = true;
                            $oldPriceNormal = $pPrice;

                        } else {
                            $oldPriceNormal = "";

                        }




                        $slugname = $this->get_product_slugname($pId);
                        $slug_link = WEB_URL . '/' . $this->db->productDetail . $slugname;

                        $productImage = $this->functions->resizeImage($productImageReal, '', '200', false);

                        $totalPriceSimple = $newPrice_simple * $qty;

                        ############ Product Categories ##########
                        $pro_cat = $this->productF->product_category($pId);

                        ##################### check if product category in 3 For 2 Category START ##############
                        $three_for_2_category = "";
                        if (sizeof(array_intersect($three_for_2_ibm_cat, $pro_cat)) > 0 && $newPrice_simple > 0) {
                            $three_for_2_pro_price[$cartId]["id"] = $pId;
                            $three_for_2_pro_price[$cartId]["price"] = intval($newPrice_simple);
                            $three_for_2_pro_price[$cartId]["qty"] = $qty;
                            $three_for_2_qty += $qty;
                            $three_for_2_category = " <img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' height='40' loading='lazy'/>";
                        }

                        ##################### check if product category in staple Category START ##############
                        $staple_category = "";
                        if (sizeof(array_intersect($staple_ibm_cat, $pro_cat)) > 0 && $newPrice_simple > 0) {

                            $staple_pro_price[$cartId]["id"] = $pId;
                            $staple_pro_price[$cartId]["price"] = intval($newPrice_simple);
                            $staple_pro_price[$cartId]["qty"] = $qty;
                            $staple_qty += $qty;
                            $staple_category = " <img src='" . WEB_URL . "/webImages/bundle_icon.png' alt='bundle' height='40' loading='lazy'/>";
                        }


                        $scaleDiv1 = "";

                        if (!empty($salePrice) && $salePrice != '') {
                            $staple_category = "";
                        }

                        $scl_emp = ($hasColor == false && $hasScale == false) ? 'scale_blank' : '';
                        $storeId = $this->getStoreId();
                        $randVal = rand(99, 999999);


                        $inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
                        $inventoryLimit = ($inventoryLimit == '1' ? true : false);




                        $hasScaleVal = $this->functions->developer_setting('product_Scale');

                        $hasScale = ($hasScaleVal == '1' ? true : false);


                        if ($inventoryLimit) {
                            $getInfo = $this->inventoryReport($pId);
                        } else {
                            $getInfo = $this->productSclaeColorReport($pId);
                        }
                        $getInfoReport = $getInfo['report'];



                        if ($getInfo['scale'] == false && @$hasWebOrder_with_Scale == '0') {
                            //if scale not found then make scale data empty,
                            //if product scale allow from setting and dont have inventory, it will make scale val to 0
                            // we will assume scale not allow from setting for javascript
                            $scaleDiv = "";
                            $hasScaleVal = 0;
                            $hasScale = false;
                        }



                        if ($hasScale) {

                            $scaleDiv = $this->getScalesDivNew($pId, $cartId, $hasColor, $storeId, $currencyId, $currencySymbol);
                        }


                        $size = $this->dbF->hardWords('Size', false);


                        $qtyT = $this->dbF->hardWords('Quantity', false);
                        $scaleMobileDic1 = "";
                        $scaleMobileDic2 = "";
                        $quantityMobileDic2 = "<div class='storlek_mobile_side storlek_mobile_side_qty'>
         
    </div>";

                        if (!empty($scaleDiv)) {



                            // getScaleIdfromCart

                            $scaleId = $this->getScaleIdfromCart($cartId);

                            $sName = $this->getScaleNameWeb($scaleId);

                            $scaleMobileDic1 = "<div class='storlek_mobile_side'>
        <h3>" . $size . " : " . $sName . "</h3> 
    </div>";

                            $sizeT_T = $this->dbF->hardWords('Selected Size', false);
                            $scaleMobileDic2 = "<div class='storlek_mobile_side'>
        <h3>" . $sizeT_T . " : " . $sName . "</h3> 
        <h3>" . $qtyT . " : " . $qty . "</h3>
    </div>";

                            $scaleDiv1 = "<div class='storlek_main_side'>
<h3>" . $size . "</h3>
<div class='select'>
<div id='size_color' class='container_detail_RightR_color choice_color'> 

<div class='container_detail_RightR_color_heading'>
<dl id='sample_select_" . $cartId . "_" . $pId . "' class='dropdown_select'> <dt><a> <span>" . $sName . "</span></a></dt><dd>
<ul> " . $scaleDiv . "    </ul>
</dd></dl></div></div>
</div>
</div>



<script>
$('#sample_{$cartId}_{$pId} dt a').click(function() {

$(this).parent().next().find('ul').fadeToggle(600);
$('#sample_select_{$cartId}_{$pId} dd ul').fadeOut(600);

});

$('#sample_{$cartId}_{$pId} dd ul li a').click(function() {
var text = $(this).html();  

// show the selected size
$(this).closest('dd').prev('dt').find('a > span').html(text);

$('#sample_{$cartId}_{$pId} dd ul').fadeOut(600);
});


$('#sample_select_{$cartId}_{$pId} dt a').click(function() {
$(this).parent().next().find('ul').fadeToggle(600);
$('#sample_{$cartId}_{$pId} dd ul').fadeOut(600);
});

$('#sample_select_{$cartId}_{$pId} dd a').click(function() {                
// save the selected size
var size_selected = $(this).find('div.color_name.size').html();

// hide the ul
$(this).closest('ul').hide();

// show the selected size
$('#sample_select_{$cartId}_{$pId} > dt > a > span').html(size_selected);
});
</script>

";
                        }




                        if ($customId != '0') {
                            $totalAllowQty = "9999999"; //If product has custom size, then its Stock qty...
                        } else {
                            $totalAllowQty = $this->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
                        }

                        $stockCheck = $this->functions->developer_setting('product_check_stock');
                        if ($stockCheck == '0') {
                            $totalAllowQty = 9999999;
                        }

                        if (!empty($oldPriceNormal)) {
                            $oldPriceNormals = filter_var(
                                $oldPriceNormal,
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                            );
                        } else {


                            $oldPriceNormals = '0';


                        }

                        $newPrice_simples = filter_var(
                            $newPrice_simple,
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        );



                        $save = $oldPriceNormals - $newPrice_simples;

                        if ($this->functions->developer_setting('addQty_custome') == '1') {
                            $addQty_custome = "<div class='addByQtyDiv reload_btn' onmouseout='addByQty(this,$cartId)'>
<input type='number' min='1' max='$totalAllowQty'  onkeypress='addByQty(this,$cartId)' data-prev='$qty' value='$qty' pattern='[0-9]{1,5}' class='addByQty_$cartId' />
</div>
";

                        }

                        $newDesignPrice = "<div class='cart_right_price'>";
                        if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {


                            $newDesignPrice .= "
 <div class='cart_right_price_red'>
<span>
0 {$currencySymbol}

</span>
</div>
";



                        } else {

                            if ($isDiscount) {
                                $newDesignPrice .= "
 
<div class='cart_right_price_red 1'>
<span>
{$newPrice_simple} {$currencySymbol}

</span>
</div>
<div class='cart_right_org_price'>
    {$oldPriceNormal}
</div>";
                            } else {
                                $newDesignPrice .= "
{$oldPriceNormal}
";
                            }


                        }




                        $dealProduct = "";
                        if ($dealId > 0 && $info !== NULL) {
                            $dealProduct .= "<div class='dealProduct'>";
                            // $infoT = unserialize($info);
                            foreach ($info as $val) {
                                // Jawwad $info
                                @$pId = $val['pId'];
                                @$scaleId = $val['scaleId'];
                                @$scaleId = !empty($scaleId) ? $this->getScaleNameWeb($scaleId) : "";
                                $name = $this->productF->getProductName($pId);
                                $img = $this->productF->productSpecialImage($pId, 'main');
                                $img = $this->functions->resizeImage($img, 'auto', 160, false);

                                $dealProduct .= "<div class='item'>
                            <div class='image'><img src='{$img}'  alt='{$name}' loading='lazy'/></div>
                            <div class='nameFlex'>
                                <div class='name'><h3>{$name}</h3></div>
                                <div class='size'>{$scaleId}</div>
                            </div>
                        </div>";


                            }
                            $dealProduct .= "</div>";
                        }



                        $newDesignPrice .= "</div>";


                        $dealIdT_text = ($dealId > 0) ? "deal" : "";
                        $dealIdT_div = ($dealId > 0) ? "<img src='{$web_url}/webImages/icons/arrow_open.png' class='open_deal_1' alt='arrow' loading='lazy'>" : "";

                        $productsDiv .= "


<div class='cart_1_inner  2 {$dealIdT_text} single_order tr_{$cartId}'>
 <div class='cart_1_main'>
<div class='cart_1_inner_1'>



<a href='{$slug_link}' data-page='{$slug_link}' title='{$pName} - Sharkspeed'>

<img src='{$productImage}' alt='{$pName}' loading='lazy'>

</a>
</div>

<div class='cart_1_inner_2'>
<div class='cart_left_portion'>
    
        <h3>{$pName}</h3>
        {$newDesignPrice}
        <h3 class='category'>{$three_for_2_category} {$staple_category} </h3>
        {$scaleMobileDic2}
        
        {$quantityMobileDic2}
</div>
<div class='cart_right_portion'>

 <div class='cancel_order' onclick='cartProductRemove( this, {$cartId}, false, true )'> X </div>
 <div class='delete_box'>
 {$dealIdT_div}
 </div>
 

";


                        $productsDiv .= "
            </div>
        </div>
    </div>
    {$dealProduct}
  </div>
</div>";


                    }

                } else {
                    $totalPrice += $price * $qty;
                }
            }






            ############ 3 For 2 Category START #########
            $three_for_2_qty = floor($three_for_2_qty / 3);
            $three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price, $three_for_2_qty);
            $totalPrice = $totalPrice - $three_for_2_minus_price;
            $three_for_2_cat_div = '';
            if ($three_for_2_minus_price > 0) {
                $three_for_2_cat_div = "<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['Three For Two Category'] . " </div>
<div class='sub_2'>({$three_for_2_minus_price}) {$currencySymbol}</div>
</div>
<!--sub_box end-->
<div class='tc_line'></div>
<br><br>";
            }
            ############ 3 For 2 Category END #########

            ############ 3 For 2 Category START #########
// $three_for_2_qty = floor($three_for_2_qty/3);
            $staple_minus_price = $this->staple_category_rec($staple_pro_price, $staple_qty);
            $totalPrice = $totalPrice - $staple_minus_price;

            $staple_pro_cat_div = '';
            if ($staple_minus_price > 0 && ($salePrice == '' || empty($salePrice))) {
                $staple_pro_cat_div = $this->cartBundleDetail($staple_qty);
                $staple_pro_cat_div = $staple_pro_cat_div['text'];

            }
            ############ 3 For 2 Category END #########




            $array['qty'] = @$totalQty;
            $array['price'] = $totalPrice;
            $array['items'] = $totalItems;
            $array['symbol'] = $this->currentCurrencySymbol();
            $array['store'] = $this->getStoreId();
            $array['products'] = $productsDiv;
            $array['three_for_2_cat_div'] = $three_for_2_cat_div;
            $array['three_for_2_minus_price'] = $three_for_2_minus_price;
            $array['staple_pro_cat_div'] = $staple_pro_cat_div;
            $array['staple_pro_minus_price'] = $staple_minus_price;
            $array['pIdsForCheckOutOffer'] = $pIdsForCheckOutOffer;

            return $array;

        }

        $array['symbol'] = $this->currentCurrencySymbol();
        return $array;
    }

    private function staple_category_rec($staple_pro_array, $staple_qty)
    {
        $small_price = 0;
        $staple_cat_pro = 0;
        $staple_cat_array_qty = 0;
        $staple_cat_array_key = 0;
        $bundle_qty = 0;
        $bundle_price = 0;
        $pro_orginal_price = 0;
        $total_dis = 0;
        $rem_pro_total = 0;

        $staple_cat_setting = unserialize($this->functions->ibms_setting("stapleProductSetting"));
        $currencyId = $this->currentCurrencyId();

        foreach ($staple_pro_array as $key1 => $val1) {
            $pro_orginal_price += $val1["price"] * $val1["qty"];
        }

        $bundle_array = array();
        for ($i = 0; $i < sizeof($staple_cat_setting['quantity']); $i++) {
            $bundle_array[$staple_cat_setting['quantity'][$i]] = $staple_cat_setting['price'][$currencyId][$i];
        }
        krsort($bundle_array);


        $funct_return = $this->checkBundleQuantity($bundle_array, $staple_qty);
        if ($funct_return > 0) {
            $funct_return = rtrim($funct_return, ',');
            $funct_return = explode(',', $funct_return);
            foreach ($funct_return as $key => $value) {
                $bundle_qty += $value;
                @$bundle_price += $bundle_array[$value];
            }
        }

        $ret_pro = $staple_qty - $bundle_qty;

        if ($ret_pro > 0) {
            $rem_pro_total = $this->getRemainingLowPrice($staple_pro_array, $ret_pro);

        }

        $total_discount_staple = $bundle_price + $rem_pro_total;
        $total_dis = $pro_orginal_price - $total_discount_staple;

        return @$total_dis;
    }

    private function checkBundleQuantity($qty_array, $pro_qty)
    {

        $bundle = '';
        $result = '';
        foreach ($qty_array as $key => $value) {
            if ($pro_qty >= $key) {
                $bundle_count = floor($pro_qty / $key);
                $remain_product = $pro_qty - $key;
                $bundle = $key . ',';
                if ($remain_product != 0) {
                    $bundle .= $this->checkBundleQuantity($qty_array, $remain_product);

                }

                break;
            }
        }
        return $bundle;
    }

    private function getRemainingLowPrice($tot_pro_array, $noOfValues)
    {
        $rem_price = 0;
        $small_price = 0;
        if ($noOfValues != 0) {
            foreach ($tot_pro_array as $key => $val) {
                if ($val["qty"] >= 1) {
                    if ($val["price"] < $small_price || $small_price === 0) {
                        $small_price = $val["price"];
                    }
                }
            }
            $noOfValues--;
        }

        if ($noOfValues > 0) {
            $small_price += $this->getRemainingLowPrice($tot_pro_array, $noOfValues);
        }

        return $small_price;

    }

    /**
     * @param $pid
     * @return string | array if 2nd parameter is passed
     */
    public function getProductNameWeb($pid, $slug = false)
    {
        if ($pid == '0') {
            return "";
        }
        $defaultLang = currentWebLanguage();
        $sql = " SELECT `slug`, `prodet_name` FROM `proudct_detail` WHERE `prodet_id` = '$pid' AND product_update = '1' ";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0) {
            $name = translateFromSerialize($data['prodet_name']);
            if ($slug) {
                $name = array('prodet_name' => $name, 'slug' => $data['slug']);
            }
            return $name;
        }
        return false;
    }

    public function getProductShortDescWeb($pid, $slug = false)
    {
        if ($pid == '0') {
            return "";
        }
        $defaultLang = currentWebLanguage();
        $sql = " SELECT `slug`, (SELECT `setting_val` FROM `product_setting` WHERE `p_id` = '$pid' AND `setting_name` = 'ldesc') AS 'prodet_shortDesc' FROM `proudct_detail` WHERE `prodet_id` = '$pid' AND product_update = '1' ";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0) {
            $prodet_shortDesc = translateFromSerialize($data['prodet_shortDesc']);
            if ($slug) {
                $prodet_shortDesc = array('prodet_shortDesc' => $prodet_shortDesc, 'slug' => $data['slug']);
            }
            return $prodet_shortDesc;
        }
        return false;
    }

    /**
     * @param $id
     * @return string
     */
    public function getScaleNameWeb($id)
    {
        if ($id == '0') {
            return "";
        }
        $sql = "SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id` = '$id'";
        $data = $this->dbF->getRow($sql);
        $name = $data['prosiz_name'];
        return $name;
    }

    /**
     * @param $id
     * @return string
     */
    public function getScaleIdfromCart($id)
    {
        if ($id == '0') {
            return "";
        }
        $sql = "SELECT `scaleId` FROM `cart` WHERE `id` = '$id'";
        $data = $this->dbF->getRow($sql);
        $name = $data['scaleId'];
        return $name;
    }

    /**
     * @param $id
     * @return string
     */
    public function getColorNameWeb($id)
    {
        if ($id == '0') {
            return "";
        }
        $sql = "SELECT `proclr_name` FROM `product_color` WHERE `propri_id` = '$id'";
        $data = $this->dbF->getRow($sql);
        $name = $data['proclr_name'];
        return $name;
    }

    public function getDealData($id)
    {
        $sql = "SELECT * FROM product_deal WHERE id = '$id'";
        $data = $this->dbF->getRow($sql);
        return $data;
    }

    public function getDealNameWeb($data)
    {
        $name = translateFromSerialize($data['name']);
        return $name;
    }

    public function getDealPrice($data)
    {
        $price = unserialize($data['price']);
        $currencyId = $this->currentCurrencyId();
        return @$price[$currencyId];
    }

    public function getDealShippingStatus($info)
    {
        $currencyId = $this->currentCurrencyId();
        foreach ($info as $val) {
            $pId = $val['pId'];
            $scaleId = $val['scaleId'];
            $colorId = $val['colorId'];
            $storeId = $val['storeId'];

            $shippingData = $this->productF->productShipping($pId, $currencyId);
            $shipping = $shippingData['propri_intShipping'];
            if ($shipping == '0') {
                return "0";
            }
        }
        return "1";
    }

    public function getDealWeight($info)
    {
        $weight = 0;
        foreach ($info as $val) {
            $pId = $val['pId'];
            $scaleId = $val['scaleId'];
            $colorId = $val['colorId'];
            $storeId = $val['storeId'];

            $weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);
            $weight = $weight + $weightSingle;
        }
        return $weight;
    }

    public function getDealpIds($info, $pIdsForCheckOutOffer)
    {
        $package = unserialize($info['package']);
        foreach ($package as $val) {
            $pId = $val;
            $pIdsForCheckOutOffer[$pId] = '1';
        }
        return $pIdsForCheckOutOffer;
    }

    public function getDealLowestProductQty($json)
    {
        $totalQtyV = 0;

        foreach ($json as $val) {
            $pId = $val['pId'];
            $scaleId = $val['scaleId'];
            $colorId = $val['colorId'];
            $storeId = $val['storeId'];
            @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
            $hash = md5($hashVal);

            //Check stock in store
            $sqlCheck = "SELECT `qty_item`,`product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = '$hash'";
            @$totalQty = $this->dbF->getRow($sqlCheck);
            if (intval(@$totalQty['qty_item']) < $totalQtyV || $totalQtyV == 0)
                $totalQtyV = intval(@$totalQty['qty_item']);

            if ($totalQtyV <= 0) {
                return false;
            }
        }
        return $totalQtyV;
    }

    public function getProductFullNameWeb($pid, $scaleId, $colorId)
    {
        $pName = $this->getProductNameWeb($pid);
        if ($pName == false) {
            return false;
        }
        if ($scaleId != '0') {
            $sName = $this->getScaleNameWeb($scaleId);
        } else {
            $sName = "";
        }
        if ($colorId != '0') {
            $cName = $this->getColorNameWeb($colorId);
            $cName = $cName;
        } else {
            $cName = "";
        }
        $temp = "$pName - $sName - $cName";
        $temp = trim($temp, '- ');
        return $temp;
    }

    public function viewCartTable()
    {
        $userId = $this->webUserId();
        $tempUser = $this->webTempUserId();

        if ($userId == '0') {
            $sql = "SELECT * FROM `cart` WHERE `tempUser` = '$tempUser'";
            $data = $this->dbF->getRowS($sql);
        } else {
            $sql = "SELECT * FROM `cart` WHERE `userId` = '$userId'";
            $data = $this->dbF->getRows($sql);
        }

        $temp = '';
        if ($this->dbF->rowCount > 0) {
            $i = 0;
            $SNOT = $this->dbF->hardWords('SNO', false);
            $PRODUCTT = $this->dbF->hardWords('PRODUCT', false);
            $PRICET = $this->dbF->hardWords('PRICE', false);
            $DISCOUNTT = $this->dbF->hardWords('DISCOUNT', false);
            $QUANTITYT = $this->dbF->hardWords('QUANTITY', false);
            $WEIGHTT = $this->dbF->hardWords('WEIGHT', false);
            $TOTALT = $this->dbF->hardWords('TOTAL', false);
            $temp = "<table id='cartViewTable' class='table tableIBMS table-hover table-striped'>
<thead>
<tr>
<th>$SNOT</th>
<th>$PRODUCTT</th>
<th>$PRICET</th>
<th>$DISCOUNTT</th>
<th>$QUANTITYT</th>
<th>$WEIGHTT</th>
<th>$TOTALT</th>
</tr>
</thead>
<tbody>
";

            $grandTotal = 0;
            $totalWeight = 0;
            foreach ($data as $val) {
                $i++;
                $cartId = $val['id'];
                $pId = $val['pId'];
                $scaleId = $val['scaleId'];
                $colorId = $val['colorId'];
                $customId = $val['customId'];
                $storeId = $val['storeId'];
                $qty = $val['qty'];

                $country = $this->currentCountry();
                $currencyId = $this->currentCurrencyId();

                $pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
                $pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $country, false);


                $discount = $this->productF->productDiscount($pId, $currencyId);
                $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
                $newPrice = $pPrice - $discountPrice;

                $totalPrice = $newPrice * $qty;
                $grandTotal += $totalPrice;
                $currencySymbol = $this->currentCurrencySymbol();

                $shippingData = $this->productF->productShipping($pId, $currencyId);
                $shipping = $shippingData['propri_intShipping'];
                $pImage = $this->productSpecialImage($pId, 'main');
                $weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);
                $weight = $weightSingle * $qty;
                $totalWeight += $weight;

                $temp .= "<tr id='tr_$cartId' class='$cartId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
<td>$i
<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
</td>
<td>$pName</td>
<td><span class='pPrice'>$pPrice</span></td>
<td><span class='pDiscount'>$discountPrice</span></td>
<td>
<div class='col-sm-12 padding-0'>
<div class='col-sm-5 btn btn-sm '>
<span class='pQty'>$qty</span>
</div>
<div class='col-sm-6 padding-0'>
<div class='btn-success btn-sm btn col-sm-4' onclick='addPlusToCart(this,$cartId)'>
<i class='glyphicon glyphicon-thumbs-up'></i>
</div>
<div class='btn-warning btn-sm btn  col-sm-4' onclick='minusFromCart(this,$cartId)'>
<i class='glyphicon glyphicon-thumbs-down'></i>
</div>

<div class='btn-danger btn-sm btn col-sm-4' onclick='cartProductRemove(this,$cartId)'>
<i class='glyphicon glyphicon-trash'></i>
</div>
</div>
</div>
</td>
<td><span class='pWeight'>$weight </span> KG</td>
<td><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</td>
</tr>";
            }
            $GRANDT = $this->dbF->hardWords('GRAND TOTAL', false);
            $WEIGHTT = $this->dbF->hardWords('TOTAL WEIGHT', false);
            $temp .= "
<tr><th colspan='4'>$GRANDT</th>
<th colspan='3'>
<input type='hidden' class='totalWeightInput' value='$totalWeight' data_cart='$cartId'/>
<input type='hidden' id='priceCode' value='$currencySymbol' data_cart='$cartId'/>

<span class='pGrandTotal'>$grandTotal </span> $currencySymbol</th>
<tr><th colspan='4'>$WEIGHTT</th>
<th colspan='3'>
<span class='pTotalWeight'>$totalWeight</span> KG</th>
</tr>
</tbody></table>";
        } else {
            $temp = false;
        }

        return $temp;
    }

    public function customSubmitValues($customId, $submit = false)
    {
        global $_e;

        $sql = "SELECT *,
(SELECT setting_value FROM p_custom_setting as b WHERE b.fieldName=s.setting_name AND b.setting_name='name' AND b.c_id=a.custom_id ) as tName
FROM `p_custom_submit` as a JOIN `p_custom_submit_setting` as s ON a.id = s.orderId  WHERE a.id = '$customId'";
        $data = $this->dbF->getRows($sql);
        if (empty($data)) {
            return false;
        }

        foreach ($data as $val) {
            $name = $val['setting_name'];
            $tName = translateFromSerialize($val['tName']);
            if (empty($tName)) {
                $tName = $name;
            }
            $value = $val['setting_value'];
            $form_fields[] = array(
                'label' => $tName,
                'format' => "$value"
            );
        }


        if ($data[0]['submitLater'] == '1' && $this->functions->isWebLink()) {
            $customEditLink = WEB_URL . "/viewOrder?editCustom=" . $this->functions->encode($customId);
            if ($submit) {
                $temp = "<div class='text-center form-group  margin-0'><a href='$customEditLink' data-page='$customEditLink' title='" . $_e["Edit custom size form"] . " - Sharkspeed' class='btn themeButton'>" . $_e["Edit custom size form"] . "</a></div>";
            } else {
                $temp = "<div class='text-center form-group  margin-0'><div class='btn themeButton'>" . $_e["Submit now, But i will fill this form later"] . "</div></div>";
            }

            $form_fields[] = array(
                'thisFormat' => "$temp"
            );
        } else if ($data[0]['submitLater'] == '1' && $this->functions->isAdminLink()) {
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'>" . $_e["User not fill final form"] . "</div>"
            );
        } else if ($data[0]['submitLater'] == '0' && $submit) {
            $form_fields[] = array(
                'label' => $_e["Submit DateTime"],
                'type' => "none",
                'format' => "<div class='text-center form-group  margin-0'>" . date('H:i:s d-m-Y', strtoTime($data[0]['dateTime'])) . "</div>"
            );

            $pdfLink = WEB_URL . "/src/pdf/measurementPDF.php?id=$customId&orderId=" . $this->functions->encode($customId);
            $form_fields[] = array(
                'label' => $_e["Print PDF"],
                'type' => "none",
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$pdfLink' target='_blank' class='btn themeButton'>{$_e["Print PDF"]}</a></div>"
            );

        }

        $form_fields['main'] = array(
            'type' => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>
<style>#customSizeInfo_$customId .modal-body{padding: 0 15px;}</style>
"
        );

        $format = '<div class="form-group border padding-5 margin-0">
<label class="col-sm-2 col-md-3 text-right">{{label}}</label>
<div class="col-sm-10  col-md-9 text-center">
{{form}}
</div>
</div>';

        $array = array("form" => $this->functions->print_form($form_fields, $format, false), "formFill" => $data[0]['submitLater']);
        return $array;
    }

    public function dealSubmitPackage($orderId, $cart = true)
    {
        if ($cart) {
            $orderId = $this->getDealProductOrders($orderId);
        }
        foreach ($orderId as $val) {
            $name = $val['name'];
            $form_fields[] = array(
                'format' => "<div>$name</div>"
            );
        }

        $form_fields['main'] = array(
            'type' => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>"
        );

        $format = '<div class="form-group border padding-5 margin-0">
<div class="col-sm-12 text-center">
{{form}}
</div>
</div>';

        return $this->functions->print_form($form_fields, $format, false);

    }

    /**
     * Find related GIFT from cart related product, then add in cart.
     * @param $price
     * @param array $PIds
     */
    private function free_gift_add($price, array $PIds = array())
    {
        if ($this->functions->developer_setting("add_free_gift_in_cart") == '1') {
            $currencyId = $this->currentCurrencyId();

            $_POST['storeID'] = $this->getStoreId();
            $_POST['scaleId'] = 0;
            $_POST['colorId'] = 0;
            $_POST['customQty'] = 0;
            $_POST['free_gift'] = "1";

            $whereIds = '';
            foreach ($PIds as $key => $val) {
                $whereIds .= "'$key',";
            }
            $whereIds = trim($whereIds, ",");
            $today = date('m/d/Y');
            $sql = "SELECT * FROM product_setting WHERE
p_id IN (
SELECT proadc_prodet_id FROM product_addcost WHERE
proadc_name = 'giftAdd_when_cart_price'
AND proadc_cur_id = '$currencyId'
AND proadc_price <= '$price'
AND proadc_price > '0'

AND product_addcost.proadc_prodet_id IN (
SELECT p_id FROM product_setting
WHERE setting_name = 'launchDate'
AND setting_val < '$today'
)
)
AND p_id IN ($whereIds)
AND setting_name = 'free_gift'
GROUP BY p_id ORDER BY rand() LIMIT 0,5";

            $data = $this->dbF->getRows($sql);

            if (!empty($data)) {
                // Add to cart only one product.. so why loop?
// whe product add to cart fail, then try next, if success then break loop
                foreach ($data as $key => $val) {
                    @$pId = unserialize($val["setting_val"]);
                    @$pId = $pId[0];
                    if (empty($pId)) {
                        continue;
                    }

                    $_POST['pId'] = $pId;

                    //Add Free Gift
                    $this->functions->modelFunFile("products_ajax_functions.php");
                    $pro_ajax = new product_ajax();
                    $return = $pro_ajax->AddToCart();
                    if ($return === "1" || $return === true) {
                        header("Location: " . $this->functions->currentUrl(false));

                        break;
                    }
                }// foreach loop end
            }// if end..

            //If function reach here, its mean no free gift product add, now check free gift default product
            $default_product_id = $this->functions->ibms_setting("default_free_gift");
            if ($default_product_id > 0 && empty($return)) {
                $check_out_gift_price_limit = unserialize($this->functions->ibms_setting("check_out_gift_price_limit"));
                $check_out_price = floatval($check_out_gift_price_limit[$currencyId]);

                if ($price >= $check_out_price) {
                    $_POST['pId'] = $default_product_id;

                    //Add Free Gift
                    $this->functions->modelFunFile("products_ajax_functions.php");
                    $pro_ajax = new product_ajax();
                    $return = $pro_ajax->AddToCart();
                    if ($return === "1" || $return === true) {
                        header("Location: " . $this->functions->currentUrl(false));

                    }
                }//if end.. check_out_price
            }//if end.. default free gift product
        }//check developer setting if end
    }

    /**
     * Call from cart view product.
     * @param $price Cart price limit.
     * @param array $notPIds list of product ids, that dont want to show in cart offers
     * @return bool|string
     */
    public function checkOutOffer($price, $notPIds = array())
    {
        $isCheckOutOfferFromDev = $this->functions->developer_setting('check_out_offer');
        $isCheckOutOfferFromAdmin = $this->functions->ibms_setting('check_out_offer');

        if ($isCheckOutOfferFromDev == '0' || $isCheckOutOfferFromAdmin == '0') {
            return false;
        }

        $country = $this->currentCountry();
        $currencyId = $this->currentCurrencyId();
        $currencySymbol = $this->currentCurrencySymbol();

        $offerIds = array();
        $whereNotIds = '';
        foreach ($notPIds as $key => $val) {
            $whereNotIds .= "'$key',";
        }
        $whereNotIds = trim($whereNotIds, ",");
        $today = date('m/d/Y');

        //for get pIds, Checkout offers products
        $sql = "SELECT product_addcost.* FROM product_addcost WHERE
product_addcost.proadc_prodet_id NOT IN ($whereNotIds)
AND product_addcost.proadc_name = 'checkout_price'
AND product_addcost.proadc_cur_id = '$currencyId'
AND product_addcost.proadc_price > 0

AND product_addcost.proadc_prodet_id IN (
SELECT proadc_prodet_id FROM product_addcost WHERE
proadc_prodet_id NOT IN ($whereNotIds)
AND proadc_name = 'when_cart_price'
AND proadc_cur_id = '$currencyId'
AND proadc_price < '$price'
)

AND product_addcost.proadc_prodet_id IN (SELECT p_id FROM product_setting
WHERE setting_name = 'launchDate'
AND setting_val < '$today')

GROUP BY proadc_prodet_id ORDER BY rand() LIMIT 0,3";
        $data = $this->dbF->getRows($sql);
        $temp = '';
        foreach ($data as $val) {
            $pId = $val['proadc_prodet_id'];
            $return = $this->productDateIsReadyForLaunch($pId); //check product is ready for launch
            if (!$return)
                continue;
            $temp .= $this->dealProductChoice($pId, false, $val);
        }

        return $temp;
    }

    public function doNotForgetOffer($pIds = false)
    {
        $isDoNotForgetOfferFromDev = $this->functions->developer_setting('doNotForget_offer');
        $isDoNotForgetOfferFromAdmin = $this->functions->ibms_setting('doNotForget_offer');

        $LimitProductCount = $this->functions->ibms_setting('doNotForget_offer_count');

        if (!empty($LimitProductCount) && $LimitProductCount != '') {
            $limit = "LIMIT " . $LimitProductCount;
        } else {
            $limit = '';
        }

        if ($isDoNotForgetOfferFromDev == '0' || $isDoNotForgetOfferFromAdmin == '0') {
            return false;
        }



        $sqls = "SELECT set_id FROM `product_setting` where setting_name='freeGift' and setting_val='1' and p_id ='$pIds'";
        $this->dbF->getRow($sqls);
        if ($this->dbF->rowCount > 0) {


            return $temp = "";




        }




        $country = $this->currentCountry();
        $currencyId = $this->currentCurrencyId();
        $currencySymbol = $this->currentCurrencySymbol();

        $sql = "SELECT * FROM `product_setting` WHERE `setting_name` = 'dontForget' AND `p_id` = '$pIds' $limit";
        $res = $this->dbF->getRow($sql);

        $donot_forget_products = is_array($res) ? unserialize($res['setting_val']) : "";



        $ccc = 0;


        if (!empty($donot_forget_products)) {
            foreach ($donot_forget_products as $val) {
                $ccc++;


            }
        }

        $owl = "a";
        if ($ccc == "1") {
            $owl = "b";
        } else {
            $owl = "owl-carousel owl-theme";
        }
        $temp = '';
        if (!empty($donot_forget_products)) {
            $temp .= '<div class="pop_slide ' . $owl . '">';
            foreach ($donot_forget_products as $val) {
                $pId = $val;
                $return = $this->productDateIsReadyForLaunch($pId); //check product is ready for launch
                if (!$return)
                    continue;
                $temp .= $this->doNotForgetProductChoice($pId, false, $val);
            }

            $temp .= "</div>



  <script>
    $(document).ready(function () {
  $('.button_side1').click(function() {
              $('.pop_side').fadeOut();
        });
});

      
$(window).load(function () {
    
    resp_img = $('.pop_slide_main').length;
if(resp_img == 1 || resp_img == 0){
res_i = false;
}else{
res_i = true;
}



        $('.pop_slide').owlCarousel({
      loop: res_i,
      navigation: true,
      autoplay: res_i,
      autoplayTimeout: 3000,
      autoplayHoverPause: true,
      items: 1,
      responsiveClass: true,
      responsive: {
          0: {
              items: 1,
              nav: true,
          },
          300: {
              items: 1,
              nav: true,
          },
          1600: {
              items: 1,
              nav: true,
          }
          }
      
      
          })
    
    });
      
      

      
            
resp_imgs = $('.pop_slide_main').length;
if(resp_imgs == 1 || resp_imgs == 0){


           
      
}else{

$('.pop_btn2').click(function() {
var owl = $('.owl-carousel');
owl.owlCarousel();

owl.trigger('next.owl.carousel');

// var owl = $('.pop_slide').data('owlCarousel');
// owl.next(); // Go to next slide
});
$('.pop_btn1').click(function() {


var owl = $('.owl-carousel');
owl.owlCarousel();

owl.trigger('prev.owl.carousel', [300]);
// var owl = $('.pop_slide').data('owlCarousel');
// owl.prev(); // Go to previous slide
});
}      
              
   </script>";


        }







        return $temp;
    }

    public function doNotForgetOfferSingleProductPopUp($pId = false)
    {
        $product_id = "";
        if ($pId != false && $pId !== NULL) {
            $sql1 = "SELECT `setting_val` FROM `product_setting` WHERE `setting_name` = ? AND `p_id` = ?";
            $doNotForgetProductPopup = $this->dbF->getRow($sql1, array('dontForgetPopUp', $pId));
            if ($doNotForgetProductPopup != false) {
                $doNotForgetProductPopupArray = @unserialize($doNotForgetProductPopup['setting_val']);
                $lenArr = count($doNotForgetProductPopupArray);
                $randomArrIndex = rand(0, $lenArr - 1);
                $product_id = $doNotForgetProductPopupArray[$randomArrIndex];
            } else {
                $doNotForgetProductPopup = $this->functions->ibms_setting('doNotForgetProductPopup');
                $doNotForgetProductPopupIbmsArray = @explode(",", $doNotForgetProductPopup);
                $lenArr = count($doNotForgetProductPopupIbmsArray);
                $randomArrIndex = rand(0, $lenArr - 1);
                $product_id = $doNotForgetProductPopupIbmsArray[$randomArrIndex];
            }
        } else {
            $doNotForgetProductPopup = $this->functions->ibms_setting('doNotForgetProductPopup');
            $doNotForgetProductPopupIbmsArray = @explode(",", $doNotForgetProductPopup);
            $lenArr = count($doNotForgetProductPopupIbmsArray);
            $randomArrIndex = rand(0, $lenArr - 1);
            $product_id = $doNotForgetProductPopupIbmsArray[$randomArrIndex];
        }
        $product = $this->pBox($product_id, false, "doNotForgetPopup");


        return $product;
    }

    public function viewCartTable2()
    {
        //view from cart.php from cart table
        global $_e;
        $userId = $this->webUserId();
        $tempUser = $this->webTempUserId();

        ############ Buy 2 get 1 free START #########
        $has_buy_get_free = false;
        $buy_get_free_query = "";
        if ($this->functions->developer_setting("buy_2_get_1_free") == "1") {
            $has_buy_get_free = true;
            $buy_get_free_query = ", (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free') as buy_2_get_1_free
, (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free_qty') as buy_get_free_apply_limit_qty";
        }
        ############# Buy 2 get 1 free END ##########

        if ($userId == '0') {
            $sql = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `tempUser` = '$tempUser'";
            $data = $this->dbF->getRowS($sql);
        } else {
            $sql = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `userId` = '$userId'";
            $data = $this->dbF->getRows($sql);
        }

        $temp = '';
        if ($this->dbF->rowCount > 0) {
            $i = 0;

            //Main cart products wrapper div start
            $temp = "<div class='container-fluid table-responsive padding-0' id='cartViewTable'>";

            $grandTotal = 0;
            $totalWeight = 0;
            $shippingPrice = 0;
            $pIdsForCheckOutOffer = '';

            $hasScaleVal = $this->functions->developer_setting('product_Scale');
            $hasColorVal = $this->functions->developer_setting('product_color');

            $hasScale = ($hasScaleVal == '1' ? true : false);
            $hasColor = ($hasColorVal == '1' ? true : false);
            $couponApply = false;
            $country = $this->currentCountry();
            $currencyCountry = $this->currentCurrencyCountry();
            $currencyId = $this->currentCurrencyId();
            $currencySymbol = $this->currentCurrencySymbol();
            $has_free_gift = false; // it is use to check if any product has free? then dont add other free gift product
            foreach ($data as $val) {
                $i++;
                $discountPrice = 0;
                $discountFormat = $discountP = '';

                $cartId = $val['id'];       // Cart table primary ID
                $pId = $val['pId'];      // Product ID
                $scaleId = $val['scaleId'];  // Product size ID, size=>(small,medium,large)
                $colorId = $val['colorId'];  // Product color ID,
                $storeId = $val['storeId'];  // Product store ID, sale from this store
                $qty = $val['qty'];      // Product Quantity
                $customId = $val['customId']; // Product custom size Id, if has
                $dealId = $val['deal'];     // Product Deal Id, => Get 3 product in 1000 price    // if not it is 0
                @$checkout = $val['checkout']; // Checkout Product Price, it is on discount from cart page. // if not it is 0
                @$info = unserialize($val['info']); //product info, use to save any random data on required.

                @$shippingId = $val['shippingPrice'];   // Product shipping Price

                $shippingPriceData = $this->shippingPriceByClass($shippingId, $shippingPrice);
                $shippingPriceT = $shippingPriceData["price"];
                $shippingPrice = $shippingPriceData["classPrice"];

                if ($customId != '0') {
                    $totalAllowQty = "9999999"; //If product has custom size, then its Stock qty...
                } else {
                    $totalAllowQty = $this->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
                }

                $pScaleName = $pColorName = '';
                $coupon = '';

                ############ Buy 2 Product get 1 free START ############
                $buy_2_get_1_free_div = "";
                $buy_2_get_1_free_input = "";

                ###################### FREE GIFT #################
                $free_gift_product_div = "";

                if ($dealId == '0') {
                    //Normal Product / checkout product / custom size product

                    $pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
                    $pNames = explode(" - ", $pName);
                    @$pName1 = $pNames[0];
                    @$pScaleName = $pNames[1];
                    @$pColorName = $pNames[2];
                    $pName1 = "<a href='" . WEB_URL . "/detail?pId=$pId' data-page='detail?pId=$pId' title='$pName1'>$pName1</a>";
                    $pIdsForCheckOutOffer[$pId] = '1';

                    $pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);

                    ####################################################################################
################ Buy 2 Product get 1 free START ################
                    if ($val["buy_2_get_1_free"] == '1' && $has_buy_get_free) {
                        $buy_get_free_apply_limit_qty = empty($val["buy_get_free_apply_limit_qty"]) ? "2" : $val["buy_get_free_apply_limit_qty"]; //limit of product to buy
                        $class = "displaynone";
                        if ($qty >= $buy_get_free_apply_limit_qty) {
                            $class = "";
                        }
                        $free_qty = floor($qty / $buy_get_free_apply_limit_qty);
                        $buy_2_get_1_free_input = "<input type='hidden' class='hidden buy_2_get_1_free' value='$buy_get_free_apply_limit_qty' data-id='$cartId' data-limit='$buy_get_free_apply_limit_qty' id='buy_2_get_1_free_$cartId'>";
                        $buy_2_get_1_free_div = "<div class='$class buy_get_free_css buy_2_get_1_free_div'>" . _replace("{{buy_qty}}", $buy_get_free_apply_limit_qty, $_e["Buy {{buy_qty}} Get 1 free"]) . "</div>";
                        $buy_2_get_1_free_div .= "<div class='clearfix'></div><div class='$class buy_free_qty buy_get_free_css buy_2_get_1_free_div'>" . _replace("{{free_qty}}", "<span class='you_get_free_qty'>$free_qty</span>", $_e["You Get +{{free_qty}} free"]) . "</div>";
                    }
                    ################ Buy 2 Product get 1 free END ##################
####################################################################################


                    ######### //checking if this is checkout offer then checkout discount offer apply
######### //if deal is 0 then checout offer need to check,
                    if ($checkout == '1') {
                        $checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
                        //add color or scale price
                        $p_color_price = $this->productF->colorPrice($colorId, $currencyId, $pId);
                        @$p_color_price = $p_color_price['proclr_price'];
                        $p_size_price = $this->productF->scalePrice($scaleId, $currencyId, $pId);
                        @$p_size_price = $p_size_price['prosiz_price'];
                        $checkoutPrice = $checkoutPrice + floatval($p_color_price) + floatval($p_size_price);
                        $discountPrice = $pPrice - $checkoutPrice;
                        @$discountFormat = "price";
                        @$discountP = $discountPrice;
                    }
                    ############################################################
################# FREE GIFT Check Is product FREE then make it price 0 free ############
                    else if ($checkout == '2') {
                        $has_free_gift = true;
                        $discountPrice = $pPrice;
                        @$discountFormat = "price";
                        @$discountP = $discountPrice;

                        $free_gift_product_div = $this->productF->free_gift_text();
                    } else {
                        $coupon = $this->getCoupon();
                        @$couponHas = $this->productF->productCouponStatus($coupon);
                        if (!$couponHas) {
                            $_SESSION['webUser']['coupon'] = '';
                        }

                        $discount = $this->productF->productDiscount($pId, $currencyId, $coupon);

                        @$discountFormat = $discount['discountFormat'];
                        @$discountP = $discount['discount'];
                        $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
                    } // /$checkout else end

                    $shippingData = $this->productF->productShipping($pId, $currencyId);
                    $shipping = $shippingData['propri_intShipping'];
                    $pImage = $this->productSpecialImage($pId, 'main');
                    $weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);

                } else {
                    // Deal product
                    $dealData = $this->getDealData($dealId);
                    $pName1 = $this->getDealNameWeb($dealData);
                    $pName1 = "<a href='" . WEB_URL . "/productDeals?deal=$dealId' data-page='productDeals?deal=$dealId' title='$pName1'>$pName1</a>";

                    $pPrice = $this->getDealPrice($dealData);
                    $pIdsForCheckOutOffer = $this->getDealpIds($dealData, $pIdsForCheckOutOffer);

                    $totalAllowQty = $this->getDealLowestProductQty($info);

                    $shipping = $this->getDealShippingStatus($info);
                    $pImage = $dealData['image'];
                    $weightSingle = $this->getDealWeight($info);
                }

                $discountPrice = round($discountPrice, 2);
                $newPrice = round($pPrice - $discountPrice, 2);
                $productQtyDiscount = $discountPrice * $qty;
                $totalPrice = $newPrice * $qty;
                $grandTotal += $totalPrice;

                $weight = $weightSingle * $qty;
                $totalWeight += $weight;

                $sum = $qty * $pPrice;
                $sumT = $this->dbF->hardWords('sum', false);
                $weightT = $this->dbF->hardWords('Weight', false);
                $discountT = $this->dbF->hardWords('Discount', false);
                $qtyT = $this->dbF->hardWords('Quantity', false);
                $totalT = $this->dbF->hardWords('Total', false);
                $Farg = $this->dbF->hardWords('Farg', false);
                $TotalITaxT = $this->dbF->hardWords('Total Including Tax', false);
                $sizeT = $this->dbF->hardWords('Size', false);
                $dealT = $this->dbF->hardWords('Deal', false);
                $sizeInfo = '';
                $colorInfo = '';
                if ($hasScale) {
                    $sizeInfo = "<div>$sizeT# : $pScaleName</div>";

                    ############## Show custom size info on popup ###############
                    if ($customId != '0' && !empty($customId) && $scaleId == '0') {
                        $sizeInfo = "<div>$sizeT# : <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFieldsData = $this->customSubmitValues($customId);
                        $customFields = $customFieldsData['form'];
                        $sizeInfo .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                    }
                    ############## Show deal's product info on popup ###############
                    if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                        $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFields = $this->dealSubmitPackage($info);
                        $sizeInfo .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
                    }
                }

                if ($hasColor) {
                    $colorInfo = "<div>$Farg : $pColorName</div>";
                }

                //if deal then color or scale make blank
                if ($dealId != '0') {

                    $colorInfo = '';
                }

                $stockCheck = $this->functions->developer_setting('product_check_stock');
                if ($stockCheck == '0') {
                    $totalAllowQty = 9999999;
                }

                if ($this->functions->developer_setting('addQty_custome') == '1') {
                    $addQty_custome = "<div class='addByQtyDiv'>
<input type='number' min='1' max='$totalAllowQty' data-prev='$qty' value='$qty' pattern='[0-9]{1,5}' class='addByQty_$cartId' />
</div>
<div class='addByQtyBtn' onclick='addByQty(this,$cartId)'><i class='glyphicon glyphicon-refresh'></i></div>";
                } else {
                    $addQty_custome = "<div class='cartPlus'  onclick='addPlusToCart(this,$cartId)'>+</div>
<div class='cartMinus' onclick='minusFromCart(this,$cartId)'>-</div>";
                }

                ######################### FREE GIFT #################
//If free gift then add more qty option hide.
                if ($checkout == "2") {
                    $addQty_custome = "";
                }
                ######################### FREE GIFT #################


                // Product HTML to display in cart.php
                $temp .= "

<div class='container-fluid padding-0 padding-bottom border cart_product_wrapper cart_product_$cartId $cartId' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
<div class='col-md-1 col-sm-1 padding-0 serialTd'>
<div class='cartSerial'>$i.</div>
<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
<input type='hidden' class='hidden' value='$totalAllowQty' id='productTotalQty_$cartId'>
<input type='hidden' class='hidden shippingClass' value='$shippingPriceT' id='shippingClass_$cartId'>
<input type='hidden' class='hidden' value='$stockCheck' id='stockCheck_$cartId'>
$buy_2_get_1_free_input
</div>
<div class='col-md-3 col-sm-3 padding-0 col-xs-12 text-center-xs imgTd'><img src='images/$pImage' alt='$pName1' loading='lazy'/></div>
<div class='col-md-2 col-sm-2 col-xs-12 text-center-xs padding-0 nameTd'>
<div class=''>
<div>$pName1</div>
$sizeInfo
$colorInfo
$buy_2_get_1_free_div
$free_gift_product_div
</div>
</div>
<div class='col-md-6 col-sm-6 padding-0 col-xs-12 priceTd'>
<div class='removeCartproduct' onclick='cartProductRemove(this,$cartId)'>X</div>
<div class='sumMainDiv'>
<div class='qtyDiv'>
<div>$qtyT</div>
$addQty_custome
<div class='productTotalQ'>
<div class='productQty'><span class='pQty'>$qty</span></div>
<div>X</div>
<div class='productPrice'><span class='pPrice'>$pPrice</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$sumT</div>
<div class='priceTemp'><span class='sumProduct'>$sum</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$weightT</div>
<div class='priceTemp'><span class='pWeight'>$weight </span> KG</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$discountT</div>
<div class='priceTemp'><span class='pDiscount'>$productQtyDiscount</span> $currencySymbol</div>
</div>
</div>

<div class='productTotal'>
<div class='productSum'>
<div class=''>$TotalITaxT</div>
<div class='priceTemp'><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</div>
</div>
</div>
</div>
</div>
</div>
";
            }

            //free

            $temp .= "
</div>
<br>";


            ############ FREE GIFT ADD START #################
// When new free gift add in cart, reload whole page. to show this free product in cart list.
            if ($this->functions->developer_setting("add_free_gift_in_cart") == "1" && !$has_free_gift) {
                $this->free_gift_add($grandTotal, $pIdsForCheckOutOffer);
            }
            ############ FREE GIFT ADD END  #################


            ############ CHECK OUT OFFER #################
//get checkout offers, and show on submit page...
            $checkoutOnClick = $checkoutModelClick = '';
            $checkoutOffers = '';
            if (!isset($_GET['checkout'])) {
                $checkoutOffers = $this->checkOutOffer($grandTotal, $pIdsForCheckOutOffer);
                if (!empty($checkoutOffers)) {
                    //Remove comment then check out offer show in popup screen
                    $checkoutOnClick = " onsubmit='return checkOutOffer();'";
                    $checkoutModelClick = " data-toggle='modal' data-target='#checkoutOffer' ";
                }
            }
            ############ CHECK OUT OFFER #################


            $country_list = $this->functions->countrylist();
            $storeCountry = $this->currentCountry();
            $countryName = $country_list[$storeCountry];
            $token = $this->functions->setFormToken('WebOrderReadyForCheckOut', false);
            $countryT = $this->dbF->hardWords('Country', false);
            $ShippingT = $this->dbF->hardWords('Shipping Price', false);
            $FreeT = $this->dbF->hardWords('FREE', false);
            $TotalWeightT = $this->dbF->hardWords('Total Weight', false);
            $KlarnaFCheckOut = $this->dbF->hardWords('Klarna Fast CheckOut', false);
            $GrandTotalT = $this->dbF->hardWords('Grand Total', false);
            $LoginContinue = $this->dbF->hardWords('Login To Continue', false);
            $Continue = $this->dbF->hardWords('Continue', false);

            $price_cal = $this->functions->product_tax_cal($grandTotal, 25);

            $tax = $price_cal['tax_price'];

            $shippingInfo = $this->shippingInfo();
            $shippingType = $shippingInfo["shippingType"];
            $shippingPriceLimit = $shippingInfo["priceLimit"];

            if ($grandTotal > $shippingPriceLimit) {
                $shippingPrice = 0;
            }

            if ($this->functions->developer_setting('shipping_class') == '1' && $shippingType == 'class') {
                $grandTotal = ($grandTotal + $shippingPrice);
            } else {
                $shippingPrice = 0;
            }


            $giftCardSystemData = $this->giftCardSystem($grandTotal);
            $giftCard_payPrice = 0;
            if (isset($giftCardSystemData['payPrice'])) {
                $giftCard_payPrice = $giftCardSystemData['payPrice'];
            }

            $jsInfo = "
<input type='hidden' class='shippingPrice' value='$shippingPrice'/>
<input type='hidden' class='shippingLimit' value='$shippingPriceLimit'>
<input type='hidden' class='shippingType'  value='$shippingType'>
";

            $temp .= "
<form $checkoutOnClick  action='orderInvoice.php' method='post'>
$token
$jsInfo
<div class='shippingWight col-sm-12'>
<div class='col-sm-6 text-center'>
<div class='col-sm-12 lead'>
$ShippingT : <span class='shippingPriceText'>$shippingPrice</span> $currencySymbol
</div>
<div class='col-sm-12 form-horizontal'>
<div class='form-group'>
<label for='receipt_vendor' class='col-sm-2 control-label'>" . $countryT . "</label>
<div class='col-sm-8'>
<fieldset class='sender_countryFieldset'>
<input type='hidden'  name='shippingWidget' value='$storeCountry'/>
<input type='text' readonly id='shippingWidget' required
value='$countryName' class='form-control'/>
</fieldset>
</div>
</div>
<span class='pShippingPriceTemp'> </span>
</div>
<div class='col-sm-12 displaynone'>
<button type='button' class='btn btn-primary btn-sm' onclick='shippingPriceWidget();'>Update Shipping Price</button>
<input type='hidden' id='storeCountryShippingWidget' name='storeCountry' value='$storeCountry'>
<input type='hidden' id='priceCodeShippingWidget' value='$currencySymbol'/>
</div>
<div class='clearfix'></div>
<br>
</div>";


            $temp .= "<div class='col-sm-6 col-xs-12 padding-0'>";
            $temp .= $this->couponSystem();
            $temp .= $giftCardSystemData['form'];
            $temp .= "</div>";

            //detuct giftPrice from grand Total/
            $giftCardDiv = '';
            if ($giftCard_payPrice > 0) {
                $grandTotal = $grandTotal - $giftCard_payPrice;
                $giftCardDiv = "<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Gift Card Price', false) . "</div>
<div class='priceTemp'>
( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
</div>
</div>
";
            }

            $temp .= "
</div><!--shippingWight End-->


<br>
<div class='GrandTotalDiv'>
<div class='col-xs-12 padding-0 g_div'>

<div class='productSum'>
<div class=''>$TotalWeightT</div>
<div class='priceTemp'><span class='pTotalWeight'>$totalWeight</span> KG</div>
</div>

<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Total Tax 25%', false) . "</div>
<div class='priceTemp'>
<span class='pGrandtax'>$tax </span> $currencySymbol
</div>
</div>

$giftCardDiv

<div class='productSum'>
<div class=''>$GrandTotalT</div>
<div class='priceTemp'>
<input type='hidden' class='totalWeightInput' value='$totalWeight' data-cart='$cartId'/>
<input type='hidden' id='priceCode' value='$currencySymbol' data-cart='$cartId'/>
<span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol
</div>
</div>
</div><!--GrandTotalDiv > col-sm-12 End-->
<div class='continueBtn'>";

            $login = $this->webClass->userLoginCheck();
            $loginForOrder = $this->functions->developer_setting('loginForOrder');

            $temp .= '<div><!-- Buttons Div-->';
            if ($this->functions->developer_setting('klarna') == '1') {
                $temp .= '<button type="submit" value="Klarna" class="btn themeButton cartSubmit1">' . $KlarnaFCheckOut . '</button>';
            }

            if (!$login && $loginForOrder == '1') {
                $temp .= ' <a href="' . WEB_URL . '/login" class="btn themeButton">' . $LoginContinue . '</a>';
            } else {
                $temp .= '<button type="submit"  value="continue"  class="btn themeButton cartSubmit1">' . $Continue . '</button>';
            }
            $temp .= '</div><!-- Buttons Div end-->';

            $temp .= "</div><!--continueBtn end-->
</div><!--GrandTotalDiv end-->
</form>";
        } else {
            $temp = false;
        }

        return array("cart" => $temp, "offer" => @$checkoutOffers);
    }

    public function removeCartCheckoutOfferProduct($cartId, $pId)
    {
        $sql = "DELETE FROM `cart` WHERE `id`= '$cartId' AND `pId` = '$pId' AND `checkout` = 1 ";
        $this->dbF->setRow($sql);
    }

    public function cartBundleDetail($staple_qty)
    {
        global $_e;
        $staple_cat_setting = unserialize($this->functions->ibms_setting("stapleProductSetting"));
        $currencyId = $this->currentCurrencyId();
        $currencySymbol = $this->currentCurrencySymbol();

        $bundle_array = array();
        for ($i = 0; $i < sizeof($staple_cat_setting['quantity']); $i++) {
            $bundle_array[$staple_cat_setting['quantity'][$i]] = $staple_cat_setting['price'][$currencyId][$i];
        }
        krsort($bundle_array);


        $bundelT = $_e['Bundle feature applies:'];

        $text = $bundelT . '<br>';

        $funct_return1 = $this->checkBundleQuantity($bundle_array, $staple_qty);
        $funct_return = rtrim($funct_return1, ',');
        $funct_return = explode(',', $funct_return);


        foreach ($funct_return as $key => $value) {
            if (!empty($value) && $value != '') {
                $text .= $value . ' ' . $_e['pcs for'] . ' ' . $bundle_array[$value] . ' ' . $currencySymbol . '<br>';
            }
        }
        return array('text' => $text, 'bundles' => $funct_return1);

    }

    public function viewCartTable3($grandprevTotal = 0)
    {

        static $counter = 0;
        // $checkoutOffersTotalPrice    this is total checkout offers price
// $checkoutIncludeHighestPrice this is the highest including price of a checkout offer, this is in the Charges On Offers on admin panel's price tab with name of when_cart_price.
        $checkoutOffersTotalPrice = $checkoutIncludeHighestPrice = 0;
        $two_cart = '';
        //view from cart.php from cart table

        global $_e, $productClass, $webClass;
        $userId = $this->webUserId();
        $tempUser = $this->webTempUserId();

        ############ Buy 2 get 1 free START #########
        $has_buy_get_free = false;
        $buy_get_free_query = "";
        if ($this->functions->developer_setting("buy_2_get_1_free") == "1" && true === false) {
            $has_buy_get_free = true;
            $buy_get_free_query = ", (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free') as buy_2_get_1_free
, (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free_qty') as buy_get_free_apply_limit_qty";
        }
        ############# Buy 2 get 1 free END ##########


        ############ 3 For 2 Category START #########
        $three_for_2_ibm_cat = intval($this->functions->ibms_setting("checkout_two_for_3_category"));
        $three_for_2_cat_div = "3 For 2 Category";
        $three_for_2_qty = 0;
        $three_for_2_pro_price = array();
        if ($three_for_2_ibm_cat > 0) {
            $three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
        } else {
            $three_for_2_ibm_cat = array();
        }
        ############ 3 For 2 Category END  ##########

        # staple product category start
        $staple_ibm_cat = intval($this->functions->ibms_setting("stapleProduct"));
        $staple_cat_div = "";
        $staple_qty = 0;
        $staple_pro_price = array();
        if ($staple_ibm_cat > 0) {
            $staple_ibm_cat = $this->getSubCatIds($staple_ibm_cat);
        } else {
            $staple_ibm_cat = array();
        }
        # staple product  category end


        if ($userId == '0') {
            $sql = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `tempUser` = '$tempUser'";
            $data = $this->dbF->getRowS($sql);
        } else {
            $sql = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `userId` = '$userId'";
            $data = $this->dbF->getRows($sql);
        }


        $temp = '';
        if ($this->dbF->rowCount > 0) {
            $i = 0;

            // //Main cart products wrapper div start

            $currencySymbol = $this->currentCurrencySymbol();



            $grandTotal = 0;
            $totalWeight = 0;
            $shippingPrice = 0;
            $pIdsForCheckOutOffer = '';

            $hasScaleVal = $this->functions->developer_setting('product_Scale');
            $hasColorVal = $this->functions->developer_setting('product_color');

            $hasScale = ($hasScaleVal == '1' ? true : false);
            $hasColor = ($hasColorVal == '1' ? true : false);
            $couponApply = false;
            $country = $this->currentCountry();
            $currencyCountry = $this->currentCurrencyCountry();
            $currencyId = $this->currentCurrencyId();
            $has_free_gift = false; // it is use to check if any product has free? then dont add other free gift product

            $subtotal = 0;
            $sizeModal = '';

            foreach ($data as $val) {
                $i++;
                $discountPrice = 0;
                $discountFormat = $discountP = '';

                $cartId = $val['id'];      // Cart table primary ID
                $pId = $val['pId'];     // Product ID
                $scaleId = $val['scaleId']; // Product size ID, size=>(small,medium,large)
                $colorId = $val['colorId']; // Product color ID,
                $storeId = $val['storeId']; // Product store ID, sale from this store
                $qty = $val['qty'];     // Product Quantity
                $customId = $val['customId'];// Product custom size Id, if has
                $dealId = $val['deal'];    // Product Deal Id, => Get 3 product in 1000 price    // if not it is 0
                @$checkout = $val['checkout'];// Checkout Product Price, it is on discount from cart page. // if not it is 0
                @$info = unserialize($val['info']); //product info, use to save any random data on required.


                ############ Product Categories ##########
                $pro_cat = $this->productF->product_category($pId);


                @$shippingId = $val['shippingPrice'];   // Product shipping Price

                $shippingPriceData = $this->shippingPriceByClass($shippingId, $shippingPrice);
                $shippingPriceT = $shippingPriceData["price"];
                $shippingPrice = $shippingPriceData["classPrice"];


                if ($customId != '0') {
                    $totalAllowQty = "9999999"; //If product has custom size, then its Stock qty...
                } else {
                    $totalAllowQty = $this->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
                }

                $pScaleName = $pColorName = '';
                $coupon = '';

                ############ Buy 2 get 1 free START #########
                $buy_2_get_1_free_div = "";
                $buy_2_get_1_free_input = "";

                ################# FREE GIFT ############
                $free_gift_product_div = "";

                if ($dealId == '0') {
                    //Normal Product / checkout product / custom size product

                    $pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
                    $pNames = explode(" - ", $pName);
                    @$pName1 = $pNames[0];
                    @$pScaleName = $pNames[1];
                    @$pColorName = $pNames[2];
                    $pColorName = str_replace('padding: 5px 12px;', 'padding: 2px 9px;', $pColorName);
                    $pName1 = "<a href='" . WEB_URL . "/detail?pId=$pId' data-page='detail?pId=$pId' title='$pName1'>$pName1</a>";
                    $pIdsForCheckOutOffer[$pId] = '1';

                    $pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);

                    ####################################################################################
################ Buy 2 Product get 1 free START #####################

                    ############ Buy 2 get 1 free END #########
####################################################################################


                    ############ //checking if this is checkout offer then checkout discount offer apply
############ //if deal is 0 then checout offer need to check,
                    if ($checkout == '1') {
                        $checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
                        $checkoutIncludePrice = $this->checkOutProductIncludePrice($pId, $currencyId);
                        $checkoutIncludeHighestPrice = ($checkoutIncludePrice > $checkoutIncludeHighestPrice) ? $checkoutIncludePrice : $checkoutIncludeHighestPrice;

                        ############################ CONTINUING HERE ############################
                        if ($grandprevTotal > 0 && $grandprevTotal < $checkoutIncludePrice) {
                            $this->removeCartCheckoutOfferProduct($cartId, $pId);
                            continue;
                        }

                        //add color or scale price
                        $p_color_price = $this->productF->colorPrice($colorId, $currencyId, $pId);
                        @$p_color_price = $p_color_price['proclr_price'];
                        $p_size_price = $this->productF->scalePrice($scaleId, $currencyId, $pId);
                        @$p_size_price = $p_size_price['prosiz_price'];
                        $checkoutPrice = $checkoutPrice + floatval($p_color_price) + floatval($p_size_price);
                        $discountPrice = $pPrice - $checkoutPrice;
                        @$discountFormat = "price";
                        @$discountP = $discountPrice;
                        @$discountTooltip = $this->productF->tooltip_div('checkout_offer');
                    }
                    ############################################################
################# FREE GIFT Check Is product FREE then make it price 0 free ############
                    else if ($checkout == '2') {
                        $has_free_gift = true;
                        $discountPrice = $pPrice;
                        @$discountFormat = "price";
                        @$discountP = $discountPrice;

                        $free_gift_product_div = $this->productF->free_gift_text();
                    } else {
                        $coupon = $this->getCoupon();
                        @$couponHas = $this->productF->productCouponStatus($coupon);
                        if (!$couponHas) {
                            $_SESSION['webUser']['coupon'] = '';
                        }

                        $discount = $this->productF->productDiscount($pId, $currencyId, $coupon);
                        @$discountFormat = $discount['discountFormat'];
                        @$discountP = $discount['discount'];
                        @$discountTooltip = $discount['tooltip'];
                        $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
                    } // /$checkout else end

                    $shippingData = $this->productF->productShipping($pId, $currencyId);
                    $shipping = $shippingData['propri_intShipping'];
                    $pImage = $this->productSpecialImage($pId, 'main');
                    $weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);

                } else {
                    $dealData = $this->getDealData($dealId);
                    $pName1 = $this->getDealNameWeb($dealData);
                    $pName1 = "<a href='" . WEB_URL . "/productDeals?deal=$dealId' data-page='productDeals?deal=$dealId' title='$pName1'>$pName1</a>";

                    $pPrice = $this->getDealPrice($dealData);
                    $pIdsForCheckOutOffer = $this->getDealpIds($dealData, $pIdsForCheckOutOffer);

                    $totalAllowQty = $this->getDealLowestProductQty($info);

                    $shipping = $this->getDealShippingStatus($info);
                    $pImage = $dealData['image'];
                    $weightSingle = $this->getDealWeight($info);
                }

                $discountPrice = round($discountPrice, 2);
                $newPrice = round($pPrice - $discountPrice, 2);
                $productQtyDiscount = $discountPrice * $qty;
                $totalPrice = $newPrice * $qty;
                $grandTotal += $totalPrice;


                // #### if checkout offer than include its price into $checkoutOffersTotalPrice
                $checkoutOffersTotalPrice = ($checkout == 1) ? $checkoutOffersTotalPrice + $totalPrice : $checkoutOffersTotalPrice;


                $weight = $weightSingle * $qty;
                $totalWeight += $weight;

                $sum = $qty * $pPrice;

                $subtotal += $sum;

                $sumT = $this->dbF->hardWords('sum', false);
                $weightT = $this->dbF->hardWords('Weight', false);
                $discountT = $this->dbF->hardWords('Discount', false);
                $qtyT = $this->dbF->hardWords('Quantity', false);
                $totalT = $this->dbF->hardWords('Total', false);
                $Farg = $this->dbF->hardWords('Farg', false);
                $TotalITaxT = $this->dbF->hardWords('Total Including Tax', false);
                $sizeT = $this->dbF->hardWords('Size', false);
                $dealT = $this->dbF->hardWords('Deal', false);
                $sizeInfo = '';
                $colorInfo = '';
                if ($hasScale) {
                    $sizeInfo = "<label>$sizeT:</label> $pScaleName";
                    if ($customId != '0' && !empty($customId) && $scaleId == '0') {
                        $sizeInfo = "<label>$sizeT:</label> <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                        $customFieldsData = $this->customSubmitValues($customId);
                        $customFields = $customFieldsData['form'];
                        $sizeModal .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                    }
                    if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                        $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFields = $this->dealSubmitPackage($info);
                        $sizeModal .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
                    }
                }

                if ($hasColor && $pColorName) {
                    $colorInfo = "<label>$Farg:</label> $pColorName";
                }

                //if deal then color or scale make blank
                if ($dealId != '0') {
                    $colorInfo = '';
                }

                $stockCheck = $this->functions->developer_setting('product_check_stock');
                if ($stockCheck == '0') {
                    $totalAllowQty = 9999999;
                }

                if ($this->functions->developer_setting('addQty_custome') == '1') {
                    $addQty_custome = "<div class='addByQtyDiv reload_btn'>
<input type='number' min='1' max='$totalAllowQty' data-prev='$qty' value='$qty' pattern='[0-9]{1,5}' class='addByQty_$cartId' />
</div>
<div class='addByQtyBtn' onclick='addByQty(this,$cartId)'><i class='glyphicon glyphicon-refresh'></i></div>";
                } else {
                    $addQty_custome = "<div class='cartPlus'  onclick='addPlusToCart(this,$cartId)'>+</div>
<div class='cartMinus' onclick='minusFromCart(this,$cartId)'>-</div>";
                }

                ######################### FREE GIFT #################
//If free gift then add more qty option hide.
                if ($checkout == "2") {
                    $addQty_custome = "";
                }
                ######################### FREE GIFT #################


                ##################### check if product category in 3 For 2 Category START ##############
                $three_for_2_category = "";
                if (sizeof(array_intersect($three_for_2_ibm_cat, $pro_cat)) > 0 && $newPrice > 0) {
                    $three_for_2_pro_price[$cartId]["id"] = $pId;
                    $three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
                    $three_for_2_pro_price[$cartId]["qty"] = $qty;
                    $three_for_2_qty += $qty;
                    $three_for_2_category = " <img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' height='40' loading='lazy'/>";
                }
                #################### check if product category in 3 For 2 Category END ###############

                ##################### check if product category in staple Category START ##############
                $staple_category = "";
                if (sizeof(array_intersect($staple_ibm_cat, $pro_cat)) > 0 && $newPrice_simple > 0) {
                    $staple_pro_price[$cartId]["id"] = $pId;
                    $staple_pro_price[$cartId]["price"] = intval($newPrice_simple);
                    $staple_pro_price[$cartId]["qty"] = $qty;
                    $staple_qty += $qty;
                    $staple_category = " <img src='" . WEB_URL . "/webImages/airoh.png' alt='airoh' height='40' loading='lazy'/>";
                }



                ### if grandprevTotal is given (gt 0), means we are in recursive function call, check $checkoutIncludePrice with previous $grandprevTotal price and remove checkout offer if price is greater than grandprevTotal, using continue to just go up and not include this offer.


                // Product HTML to display in cart.php
                $temp .= " 
<div class=' {$counter} detail_cart wow fadeInLeft cart_product_wrapper cart_product_{$cartId} {$cartId}' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle' >

<div class='img_detail  inline_block'><a href='" . WEB_URL . "/detail?pId={$pId}' data-page='detail?pId={$pId}' title='{$pNames[0]}'><img src='images/$pImage' alt='{$pNames[0]}' loading='lazy'></a></div>
<div class='info_cart inline_block'>

<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
<input type='hidden' class='hidden' value='$totalAllowQty' id='productTotalQty_$cartId'>
<input type='hidden' class='hidden shippingClass' value='$shippingPriceT' id='shippingClass_$cartId'>
<input type='hidden' class='hidden' value='$stockCheck' id='stockCheck_$cartId'>
$buy_2_get_1_free_input

<div class='info_head'> {$pName1} {$three_for_2_category} </div>
<div class='info_text'> {$sizeInfo} </div>
<div class='info_text'> {$colorInfo} </div>
<div class='info_text'><label> {$qtyT}: </label> <span class='pQty'>{$qty}</span> @ <span class='pPrice'>{$newPrice}</span> {$currencySymbol}  </div>
$buy_2_get_1_free_div
$free_gift_product_div
<div class='clearfix'></div>

<input type='submit' value='" . $_e['REMOVE'] . "' class='info_btn' onclick='cartProductRemove(this,$cartId)'>
{$addQty_custome}
<!-- <input type='submit' value='" . $_e['EDIT'] . "' class='info_btn'> -->
</div><!--info_cart end-->

<div class='rate_detail inline_block wow fadeInLeft'><span class='pTotalPrice'>{$totalPrice}</span> {$currencySymbol}
" . $discountTooltip . "
</div>
</div><!--detail_cart end-->";

            }


            $temp .= "

</div><!--one_cart end-->";



            ############ FREE GIFT ADD START #################
// When new free gift add in cart, reload whole page. to show this free product in cart list.

            if ($this->functions->developer_setting("add_free_gift_in_cart") == "1" && !$has_free_gift) {
                $this->free_gift_add($grandTotal, $pIdsForCheckOutOffer);
            }
            ############ FREE GIFT ADD END  #################


            ############ CHECK OUT OFFER #################
//get checkout offers, and show on submit page...
            $checkoutOnClick = $checkoutModelClick = '';
            $checkoutOffers = '';
            if (!isset($_GET['checkout'])) {
                $checkoutOffers = $this->checkOutOffer($grandTotal, $pIdsForCheckOutOffer);
                if (!empty($checkoutOffers)) {
                    $checkoutOnClick = " onsubmit='return checkOutOffer();'";
                    $checkoutModelClick = " data-toggle='modal' data-target='#checkoutOffer' ";
                }
            }
            ############ CHECK OUT OFFER #################

            $country_list = $this->functions->countrylist();
            $storeCountry = $this->currentCountry();
            $countryName = $country_list[$storeCountry];
            $token = $this->functions->setFormToken('WebOrderReadyForCheckOut', false);
            $countryT = $this->dbF->hardWords('Country', false);
            $ShippingT = $this->dbF->hardWords('Shipping Price', false);
            $FreeT = $this->dbF->hardWords('FREE', false);
            $TotalWeightT = $this->dbF->hardWords('Total Weight', false);
            $KlarnaFCheckOut = $this->dbF->hardWords('Klarna Fast CheckOut', false);
            $GrandTotal = $this->dbF->hardWords('Grand Total', false);
            $LoginContinue = $this->dbF->hardWords('Login To Continue', false);
            $Continue = $this->dbF->hardWords('Continue', false);
            $ContinueGuest = $this->dbF->hardWords('Checkout as Guest', false);

            $price_cal = $this->functions->product_tax_cal($grandTotal, 25);
            $tax = $price_cal['tax_price'];


            $shippingInfo = $this->shippingInfo();
            $shippingType = $shippingInfo["shippingType"];
            $shippingPriceLimit = $shippingInfo["priceLimit"];

            if ($grandTotal > $shippingPriceLimit) {
                $shippingPrice = 0;
            }

            if ($this->functions->developer_setting('shipping_class') == '1' && $shippingType == 'class') {
                $grandTotal = ($grandTotal + $shippingPrice);
            } else {
                $shippingPrice = 0;
            }


            $giftCardSystemData = $this->giftCardSystem($grandTotal);
            $giftCard_payPrice = 0;
            if (isset($giftCardSystemData['payPrice'])) {
                $giftCard_payPrice = $giftCardSystemData['payPrice'];
            }

            $jsInfo = "
<input type='hidden' class='shippingPrice' value='$shippingPrice'/>
<input type='hidden' class='shippingLimit' value='$shippingPriceLimit'>
<input type='hidden' class='shippingType'  value='$shippingType'>
";

            //<div class='col-sm-12 lead'>$ShippingT : $FreeT</div>


            // Coupon Code
            $couponDiv = $giftCardDiv = '';
            $couponDiv .= $this->couponSystem();
            $giftCardDiv .= $giftCardSystemData['form'];


            //detuct giftPrice from grand Total/
            $giftCard = '';
            if ($giftCard_payPrice > 0) {
                $grandTotal = $grandTotal - $giftCard_payPrice;
                $giftCard = "<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Gift Card Price', false) . "</div>
<div class='priceTemp'>
( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
</div>
</div>
";
            }

            $checkoutButton = $loginButton = '';
            $login = $this->webClass->userLoginCheck();
            $loginForOrder = $this->functions->developer_setting('loginForOrder');

            if (!$login && $loginForOrder == '1') {
                $checkoutButton .= ' <a href="' . WEB_URL . '/login" class="checkout btn themeButton">' . $LoginContinue . '</a>';
            } else {
                // if not logged in then show login button, this project has loginfororder = 0
                if (!$login) {
                    $loginButton = '<div class="sub_box"> <a href="' . WEB_URL . '/login" class="checkout btn ">' . $LoginContinue . '</a> </div><!--sub_box end-->';
                    $checkoutButton .= '<button type="submit"  value="continue"  class="checkout btn cartSubmit1">' . $ContinueGuest . '</button>';
                } else {
                    # Logged in, show proceed
                    $checkoutButton .= '<button type="submit"  value="continue"  class="checkout btn cartSubmit1">' . $Continue . '</button>';
                }
            }

            ############ 3 For 2 Category START #########
            $three_for_2_qty = floor($three_for_2_qty / 3);
            $three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price, $three_for_2_qty);
            $grandTotal = $grandTotal - $three_for_2_minus_price;
            $three_for_2_cat_div = '';
            if ($three_for_2_minus_price > 0) {
                $three_for_2_cat_div = "<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['Three For Two Category'] . " </div>
<div class='sub_2'>({$three_for_2_minus_price}) {$currencySymbol}</div>
</div>
<!--sub_box end-->";
            }
            ############ 3 For 2 Category END #########

            ############ 3 For 2 Category START #########
            $staple_pro_minus_price = $this->staple_category_rec($staple_pro_price, $staple_qty);


            $grandTotal = $grandTotal - $staple_minus_price;
            $staple_pro_cat_div = '';
            if ($staple_pro_minus_price > 0) {
                $staple_pro_cat_div = $this->cartBundleDetail($staple_qty);
                $staple_pro_cat_div = $staple_pro_cat_div['text'];

            }
            ############ 3 For 2 Category END #########

            $two_cart .= "
<div class='two_cart inline_block wow fadeInUp' >

<form $checkoutOnClick  action='orderInvoice.php' method='post'>
$token
$jsInfo

<input type='hidden'  name='shippingWidget' value='$storeCountry'/>
<input type='hidden' readonly id='shippingWidget' required value='$countryName' class='form-control'/>
<div class='summary'>" . $_e['SUMMARY'] . "</div>

{$couponDiv}

{$giftCardDiv}

<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['SUBTOTAL'] . " </div>
<div class='sub_2'>{$subtotal} {$currencySymbol}</div>
</div><!--sub_box end-->

{$three_for_2_cat_div}
{$staple_pro_cat_div}

<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['ESTIMATED DELIVERY & HANDLING'] . "</div>
<div class='sub_2'><span class='pShippingPriceTemp' data-real='$shippingPrice'>$shippingPrice</span> {$currencySymbol}</div>
</div><!--sub_box end-->

<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1 sub_font'>" . $_e['TOTAL'] . "</div>
<div class='sub_2 sub_font'><span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol</div>
</div><!--sub_box end-->

<div class='tc_line'></div>
{$loginButton}
<div class='sub_box'>
{$checkoutButton}
</div><!--sub_box end-->

<div class='tc_line'></div>";
            $shipping_div = '';
            if ($shippingPrice == 0) {
                # free shipping last div in cart page at bottom right
                $box = $webClass->getBox('box13');
                $shipping_div = $box['text'];

                $shipping_div = "
<div class='sub_box'>
" . $box['text'] . "
</div><!--sub_box end-->";
            }

            $two_cart .= " $shipping_div ";

            $two_cart .= "
</form></div><!--two_cart end-->";


            //Main cart products wrapper div start
            $cart_info = $productClass->cartInfo();
            $shippingInfo = $this->shippingInfo();
            $shippingType = $shippingInfo["shippingType"];
            $shippingPriceLimit = $shippingInfo["priceLimit"];

            $box = $webClass->getBox('box9');

            $temp = "   <div class='head_cart wow fadeInDown' >" . $_e['YOUR CART'] . "</div>
<div class='items_cart wow fadeInDown' >" . $cart_info['items'] . ' ' . $_e['ITEM(s)'] . "</div>
<div class='one_cart inline_block wow fadeInLeft' id='cartViewTable' >

<div class='oc_head'>
<div class='oc_heading'>" . $box['heading'] . "</div>
<div class='oc_text'>
" . $box['text'] . "
</div>
</div><!--oc_head end-->
" . $temp;
            ### if there are products in cart only then include the right side prices and continue buttons.
            if ($cart_info['items'] > 0) {
                $temp = $temp . $two_cart;
            } else {
                # if no products in cart then set cart to false
                $temp = false;
            }


        } else {
            $temp = false;
        }


        ################################  RECURSIVE FUNC CALL  ##########################################
### if price to include checkout offer is 500 and grand total is less than that, also checking static counter, we are running this function once again with grandTotal - $checkoutOffersTotalPrice in it, to check each checkout offers including price with that of grandTotal - $checkoutOffersTotalPrice to avoid checkout offer misuse, $checkoutOffersTotalPrice is the total price of all the checkout offers.
        if (isset($grandTotal) && $checkoutIncludeHighestPrice > ($grandTotal - $checkoutOffersTotalPrice) && $counter == 0) {

            $counter++;
            $new_array = $this->viewCartTable3($grandTotal - $checkoutOffersTotalPrice);
            $temp = $new_array['cart'];
            $checkoutOffers = $new_array['offer'];
            $sizeModal = $new_array['sizeModal'];
        }


        return array("cart" => $temp, "offer" => @$checkoutOffers, "sizeModal" => @$sizeModal);
    }

    private function three_for_2_category_rec($three_for_2_pro_array, $three_for_2_qty)
    {
        $small_price = 0;
        $three_for_2_pro = 0;
        $three_for_2_array_qty = 0;
        $three_for_2_array_key = 0;
        $total_min = 0;
        foreach ($three_for_2_pro_array as $key => $val) {
            if ($val["price"] < $small_price || $small_price === 0) {
                $small_price = $val["price"];
                $three_for_2_pro = $val['id'];
                $three_for_2_array_qty = intval($val['qty']);
                $three_for_2_array_key = $key;
            }
        }


        $temp = $three_for_2_qty;
        $three_for_2_qty = $three_for_2_qty - $three_for_2_array_qty;
        if ($three_for_2_qty > 0) {
            $small_price = ($small_price * $three_for_2_array_qty);
            $total_min += $small_price;
        } else {
            $small_price = ($small_price * $temp);
            $total_min += $small_price;
        }

        if ($three_for_2_qty > 0) {
            unset($three_for_2_pro_array[$three_for_2_array_key]);
            $total_min += $this->three_for_2_category_rec($three_for_2_pro_array, $three_for_2_qty);
        }

        return $total_min;
    }

    private function getCoupon()
    {
        $coupon = '';

        if (isset($_GET['coupon']) && $_GET['coupon'] != '') {
            $coupon = $_GET['coupon'];
            $_SESSION['webUser']['coupon'] = $coupon;
        } else if (isset($_SESSION['webUser']['coupon'])) {
            $coupon = $_SESSION['webUser']['coupon'];
        } else {
            $_SESSION['webUser']['coupon'] = $coupon;
        }

        if (isset($_GET['coupon']) && $_GET['coupon'] == 'remove') {
            $_SESSION['webUser']['coupon'] = '';
            $coupon = '';
        }
        return $coupon;
    }

    private function couponSystem()
    {
        $removeCoupon = $temp = '';
        $coupon = $this->getCoupon();
        $couponApply = false;
        if (!empty($coupon)) {
            $couponApply = true;
        }
        @$couponHas = $this->productF->productCouponStatus($coupon);

        $couponStatus = $this->dbF->hardWords('Discount Code', false);
        if ($couponApply) {
            if ($couponHas) {
                $couponApplyT = $this->dbF->hardWords('Discount code apply', false);
                $couponStatus = "<div class='gift_succ'>$couponApplyT</div>";

                $couponApplyT = $this->dbF->hardWords('Remove Coupon', false);
                $removeCoupon = '<div class="clearfix margin-5"></div><a href="cart?coupon=remove" data-page="cart?coupon=remove" title="Cart - Sharkspeed" class="btn-danger btn-sm">' . $couponApplyT . '</a>';
            } else {
                $coupon = '';
                $couponApplyT = $this->dbF->hardWords('Code Not Found or expired', false);
                $couponStatus = "<div class='gift_dangr'>$couponApplyT</div>";
            }
        }

        if ($this->functions->developer_setting('couponSystem') == '1') {
            $couponT = $this->dbF->hardWords('Coupon', false);
            $checkT = $this->dbF->hardWords('Check', false);
            $checkT = $this->dbF->hardWords('Apply', false);

            ######### For old Cart two, bootstrap #######
            $work_with_old_cart2 = "
<!-- Coupon Code -->
<div class='tc_line for_cart3 displaynone'></div>
<div class='col-xs-12 text-center couponCode promo-code'>
<div class='col-sm-12 margin-5'>
$couponStatus
</div>
<div class='col-sm-12 form-horizontal'>
<div class='form-group'>
<label class='col-sm-3 control-label'>$couponT</label>
<div class='col-sm-8 input-group' >
<input type='text' id='couponCode' value='$coupon' class='form-control' />
<div class='input-group-addon' onclick='applyCoupon();' style='cursor: pointer; '>$checkT</div>
</div>
$removeCoupon
</div>
</div>
</div>
<div class='clearfix'></div>
<!-- Coupon Code End -->";

            $temp = "
<!-- Coupon Code -->
<div class='tc_line'></div>
<div class='promo-code'> 
<div class='pc_text'>{$couponStatus}</div>
<input type='text' id='couponCode' value='' class='pc_field'>
<input type='button' onclick='applyCoupon();' value='" . $checkT . "' class='apply'>
$removeCoupon
</div><!--promo-code end-->
<!-- Coupon Code End -->";


        }
        return $temp;
    }

    private function getGiftCardId()
    {
        $giftCard = '';
        if (isset($_GET['giftCard']) && $_GET['giftCard'] != '') {
            $giftCard = $_GET['giftCard'];
            $_SESSION['webUser']['giftCard'] = $giftCard;
        } else if (isset($_SESSION['webUser']['giftCard'])) {
            $giftCard = $_SESSION['webUser']['giftCard'];
        } else {
            $_SESSION['webUser']['giftCard'] = $giftCard;
        }

        if (isset($_GET['giftCard']) && $_GET['giftCard'] == 'remove') {
            $_SESSION['webUser']['giftCard'] = '';
            $giftCard = '';
        }

        return $giftCard;
    }

    private function giftCardSystem($cartPrice)
    {
        $removeGiftCard = $temp = '';
        $giftId = $this->getGiftCardId();

        $giftCardStatus = $this->dbF->hardWords('Gift Card Id', false);
        $giftCardData = $this->giftCardCheck($cartPrice);
        $giftCardStatus2 = $giftCardData['msg'];
        if (!empty($giftCardStatus2)) {
            $giftCardStatus = $giftCardStatus2;
            $giftCardStatus .= "<input type='hidden' class='giftCard_giftPrice_input' value='$giftCardData[giftPrice]' />";
            $giftCardStatus .= "<input type='hidden' class='giftCard_payPrice_input' value='$giftCardData[payPrice]' />";


            $tempT = $this->dbF->hardWords('Remove GiftCard', false);
            $removeGiftCard = '<div class="clearfix margin-5"></div><a href="cart?giftCard=remove" data-page="cart?giftCard=remove" title="Cart - Sharkspeed" class="btn-danger btn-sm">' . $tempT . '</a>';

        }

        if ($this->functions->developer_setting('giftCard') == '1') {
            $giftCardT = $this->dbF->hardWords('Gift Card', false);
            $checkT = $this->dbF->hardWords('Check', false);
            $checkT = $this->dbF->hardWords('Apply', false);

            ######### That was use for old cart two, bootstrap  ######
            $temp['form'] = "
<!-- Gift Card -->
<div class='tc_line for_cart3 displaynone'></div>
<div class='col-sm-12 text-center giftCard_formDiv'>
<div class='col-xs-12'>
<div class='col-sm-12 margin-0'>
$giftCardStatus
</div>
<div class='col-sm-12 form-horizontal padding-0'>
<div class='form-group'>
<label class='col-sm-3 control-label'>$giftCardT</label>
<div class='col-sm-8 input-group' >
<input type='text' id='applyGiftCard' value='$giftId' class='form-control' />
<div class='input-group-addon' onclick='applyGiftCard();' style='cursor: pointer; '>$checkT</div>
</div>
$removeGiftCard
</div>
</div>
</div>
</div>
<div class='clearfix'></div>
<!-- Gift Card End -->";



            $temp['form'] = "
<!-- Gift Card -->
<div class='tc_line'></div>
<div class='promo-code'>
<div class='pc_text'>{$giftCardStatus}</div>
<input type='text' id='applyGiftCard' value='$giftId' class='pc_field'>
<input type='button' onclick='applyGiftCardd();' value='" . $checkT . "' class='apply'>
$removeGiftCard
</div><!--promo-code end-->
<!-- Gift Card End -->

";
        }
        return array_merge($temp, $giftCardData);
    }

    public function giftCardCheck($cartPrice)
    {
        global $_e;
        //first check is giftid ok, then check is gift card price greater then cart price,,

        $giftId = $this->getGiftCardId();

        $array['giftPrice'] = 0;
        $array['giftCardId'] = $giftId;
        $array['payPrice'] = 0;
        $array['msg'] = '';
        $array['info'] = '';
        //$array['error']    = true; //error !isset if, if condition not execute
        if (!empty($giftId)) {
            $giftId = removeSpace($giftId);
            $giftId = str_replace(" ", "", $giftId);
            $giftId = str_replace("-", "", $giftId);

            $error = false;
            $errormsg = '';

            $sql = "SELECT * FROM gift_card WHERE publish = '1' AND sale='1' AND giftId = ?";
            $data = $this->dbF->getRow($sql, array($giftId));
            if (empty($data) && !$error || $data['usePrice'] > $data['price']) {
                $errormsg = $_e["Gift Card Id is Not Valid."];
                $error = true;
            }
            $array['info'] = $data['info'];

            $giftCardPrice = floatval($data['price']);
            $giftUsePrice = floatval($data['usePrice']);
            $giftCurrency = $data['currency'];
            $giftPrice = $giftCardPrice - $giftUsePrice;

            //now check if giftcard price is greater then cart price

            //check gift card Currency..
            $cartCurrency = $this->currentCurrencySymbol();
            if ($cartCurrency != $giftCurrency && !$error) {
                $errormsg = _replace("{{giftCurrency}}", $giftCurrency, $_e["Your Gift card in ( {{giftCurrency}} ) currency, and not valid for ( {{cartCurrency}} ) currency"]);
                $errormsg = _replace("{{cartCurrency}}", $cartCurrency, $errormsg);
                $error = true;
            }

            //check giftcard and cart price
            if ($cartPrice > $giftPrice && !$error) {
                $cartPrice = $giftPrice;
            }


            if ($error) {
                $array['msg'] = "<div class='gift_dangr'>$errormsg</div>";
            } else {
                $array['giftPrice'] = $giftPrice;
                $array['payPrice'] = $cartPrice;

                $errormsg = _replace("{{giftId}}", $giftId, $_e['Your Gift card Id "{{giftId}}"  will be charged {{cartPrice}} from {{giftPrice}}']);
                $errormsg = _replace("{{cartPrice}}", "<span class='giftcardPrice_span_payPrice'>$cartPrice</span> $giftCurrency", $errormsg);
                $errormsg = _replace("{{giftPrice}}", "<span class='giftcardPrice_span_giftPrice'>$giftPrice</span> $giftCurrency", $errormsg);

                if ($giftPrice <= 0) {
                    $error = true;
                    $array['msg'] = "<div class='gift_dangr'>$errormsg</div>";
                } else {
                    $array['msg'] = "<div class='gift_succ'>$errormsg</div>";
                }

                //now every thing is ok,, send to cardcontinue page for submit form..
            }

            $array['error'] = $error;
        }

        return $array;
    }

    public function openPaypal($PaymentType, $invoiceId)
    {
        if ($PaymentType === '1' && $PaymentType !== false) {
            //Means User select PayPal Payment type;
            header("Location:" . WEB_URL . "/src/paypal/paypal.php?inv=$invoiceId");
            exit;
        } else {
            return false;
        }
    }

    public function openPaypalExtra($PaymentType, $invoiceId, $extraId)
    {
        if ($PaymentType === '1' && $PaymentType !== false) {
            //Means User select PayPal Payment type;
            header("Location:" . WEB_URL . "/src/paypal/e_paypal.php?inv=$invoiceId&id=$extraId");
            exit;
        } else {
            return false;
        }
    }

    public function openPayson($PaymentType, $invoiceId)
    {
        if ($PaymentType === '5' && $PaymentType !== false) {
            //Means User select PayPal Payment type;
            header("Location:" . WEB_URL . "/src/payson/main.php?inv=$invoiceId");
            exit;
        } else {
            return false;
        }
    }

    public function openPaysonExtra($PaymentType, $invoiceId, $extraId)
    {
        if ($PaymentType === '5' && $PaymentType !== false) {
            //Means User select PayPal Payment type;
            header("Location:" . WEB_URL . "/src/payson/extra_main.php?inv=$invoiceId&id=$extraId");
            exit;
        } else {
            return false;
        }
    }

    public function openSwissBilling($PaymentType, $invoiceId)
    {
        if ($PaymentType === '8' && $PaymentType !== false) {
            //Means User select swissBilling Payment type;
            echo '<script>location.href = "' . WEB_URL . '/src/swissBilling/swissBilling.php?inv=' . $invoiceId . '&dispatch=process";</script>';
            exit;
        } else {
            return false;
        }
    }    
    

    public function openSwissBillingExtra($PaymentType, $invoiceId, $extraId)
    {
        if ($PaymentType === '8' && $PaymentType !== false) {
            //Means User select swissBilling Payment type;
            header("Location:" . WEB_URL . "/src/swissBilling/extra_swissBilling.php?inv=$invoiceId&dispatch=process&id=$extraId");
            exit;
        } else {
            return false;
        }
    }

    public function checkGiftCardid($giftId, $invoiceId, $userId, $updatePrice = true)
    {
        $giftId = removeSpace($giftId);
        $giftId = str_replace(" ", "", $giftId);
        $giftId = str_replace("-", "", $giftId);

        $sql = "SELECT * FROM gift_card WHERE publish = '1' AND sale='1' AND giftId = ?";
        $data = $this->dbF->getRow($sql, array($giftId));
        if (empty($data)) {
            $errormsg = "Gift Card Id \"$giftId\" is Not Valid. Note: It is Case Sensitive.";
            return false;
        }

        $giftCardPrice = floatval($data['price']);
        $giftUsePrice = floatval($data['usePrice']);
        $giftCurrency = $data['currency'];
        $giftPrice = $giftCardPrice - $giftUsePrice;

        //now check if giftcard price is greater then cart price
        $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$invoiceId' AND orderUser = '$userId'";
        $orderInvoice = $this->dbF->getRow($sql);
        if (empty($orderInvoice)) {
            $errormsg = "Cart Error.";
            return false;
        }

        $cartPrice = floatval($orderInvoice['total_price']);
        $cartCurrency = $orderInvoice['price_code'];

        //check gift card Currency..
        if ($cartCurrency != $giftCurrency) {
            $errormsg = "Your Gift card in ( $giftCurrency ) currency, and not valid for ( $cartCurrency ) currency";
            return false;
        }

        //check giftcard and cart price
        if ($cartPrice > $giftPrice) {
            $errormsg = "You have low price in you Gift card :$giftPrice $cartCurrency";
            return false;
        }

        //now every thing is ok,,
        if ($updatePrice) {
            $this->updateGiftCard($giftId, $cartPrice, $invoiceId, $data['info']);
        }

        return true;
    }

    public function updateGiftCard($giftId, $price, $orderId, $oldInfo)
    {
        $orderId = "GiftCard \"$giftId\" Use In :\nOrder Id : $orderId; Price:$price \n ____ \n";
        if (empty($oldInfo)) {
            $info = " info = '$orderId'";
        } else {
            $info = " info = CONCAT(info,'$orderId')";
        }
        $sql = "UPDATE gift_card SET
usePrice = usePrice + ?,
$info
WHERE giftId = ?";

        $giftId = preg_replace("/-| /", "", $giftId);

        $this->dbF->setRow($sql, array($price, $giftId));
        return $orderId;
    }

    public $cartInvoice = false;
    
    public function viewCheckOutProduct($id)
    {
        global $_e;

        $orderUser = $this->webUserId();
        if ($orderUser == '0') {
            $orderUser = $this->webTempUserId();
        }
        $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id' AND orderUser = '$orderUser'";
        $orderInvoice = $this->dbF->getRow($sql);
        if (!$this->dbF->rowCount) {
            return false;
        }

        $sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
        $orderProducts = $this->dbF->getRows($sql);
        if ($orderInvoice['orderStatus'] == 'process') {
            $submitSuccessT = $this->dbF->hardWords('Your Order Submit SuccessFully', false);
            $msg = "<br><div class='well alert alert-success h4 text-center'>$submitSuccessT </div>";

            if ($this->functions->developer_setting('invoice_print_after_Checkout') == '1') {
                $print_link = WEB_URL . "/invoicePrint?mailId=$id&orderId=" . $this->functions->encode($id);
                header("Location: $print_link");
                exit;
            }

            $msg .= "
<script>
$(document).ready(function(){
setTimeout(function(){location.replace('viewOrder?success')},2000);
});
</script>
";
            return $msg;
        }

        if ($orderInvoice['orderStatus'] == 'pendingPaypal' || $orderInvoice['paymentType'] == '1') {
            //redirect to paypal page for payment
            $this->openPaypal($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
        } else if ($orderInvoice['orderStatus'] == 'pendingPayson' || $orderInvoice['paymentType'] == '5') {
            //redirect to payson page for payment
            $this->openPayson($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
        } else if ($orderInvoice['orderStatus'] == 'pendingSwissBilling' || $orderInvoice['paymentType'] == '8') {
            //redirect to payson page for payment
            $this->openSwissBilling($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
        }

        $temp = '';
        if ($this->dbF->rowCount > 0) {
            $i = 0;

            $temp = "<div class='container-fluid table-responsive' id='cartViewTable'>";

            $grandTotal = 0;
            $totalWeight = 0;

            $hasScaleVal = $this->functions->developer_setting('product_Scale');
            $hasColorVal = $this->functions->developer_setting('product_color');

            $hasScale = ($hasScaleVal == '1' ? true : false);
            $hasColor = ($hasColorVal == '1' ? true : false);

            $currencyCountry = $this->currentCurrencyCountry();
            $country = $this->currentCountry();
            $currencyId = $this->currentCurrencyId();

            foreach ($orderProducts as $val) {
                $this->cartInvoice = true;
                $i++;
                $cartId = $val['invoice_product_pk'];
                //Product hash
                $pIds = $val['order_pIds'];
                $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                $pId = $pArray[0]; // 491
                $scaleId = $pArray[1]; // 426
                $colorId = $pArray[2]; // 435
                $storeId = $pArray[3]; // 5
                @$customId = $pArray[4]; // 5
                $dealId = $val['deal']; // if not it is 0
                @$info = unserialize($val['info']);
                if (empty($customId)) {
                    $customId = 0;
                }
                $qty = $val['order_pQty'];

                if ($dealId == '0') {
                    $pImage = $this->productSpecialImage($pId, 'main');
                } else {
                    $dealData = $this->getDealData($dealId);
                    $pImage = $dealData['image'];
                }
                $pName = $val['order_pName'];
                $pNames = explode(" - ", $pName);
                @$pName1 = $pNames[0];
                @$pScaleName = $pNames[1];
                @$pColorName = $pNames[2];

                $pPrice = $val['order_salePrice'];
                $discount = $this->productF->productDiscount($pId, $currencyId);
                @$discountFormat = $discount['discountFormat'];
                @$discountP = $discount['discount'];

                $discountPrice = $val['order_discount'];
                $newPrice = $pPrice - $discountPrice;
                $productQtyDiscount = $discountPrice * $qty;
                $totalPrice = $newPrice * $qty;
                $grandTotal += $totalPrice;
                $currencySymbol = $this->currentCurrencySymbol();

                $shippingData = $this->productF->productShipping($pId, $currencyId);
                $shipping = $shippingData['propri_intShipping'];

                $weightSingle = $val['order_pWeight'];
                $weight = $weightSingle;
                $totalWeight += $weight;

                $sum = $qty * $pPrice;

                $sumT = $this->dbF->hardWords('sum', false);
                $weightT = $this->dbF->hardWords('Weight', false);
                $discountT = $this->dbF->hardWords('Discount', false);
                $qtyT = $this->dbF->hardWords('Quantity', false);
                $totalT = $this->dbF->hardWords('Total', false);
                $Farg = $this->dbF->hardWords('Farg', false);
                $sizeT = $this->dbF->hardWords('Size', false);
                $dealT = $this->dbF->hardWords('Deal', false);

                $sizeInfo = '';
                $colorInfo = '';
                if ($hasScale) {
                    $sizeInfo = "<div>$sizeT# : $pScaleName</div>";
                    if ($customId != '0' && !empty($customId)) {
                        $sizeInfo = "<div>$sizeT# : <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFieldsData = $this->customSubmitValues($customId);
                        $customFields = $customFieldsData['form'];
                        $sizeInfo .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                    }
                    if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                        $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFields = $this->dealSubmitPackage($info, false);
                        $sizeInfo .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
                    }
                }
                if ($hasColor) {
                    $colorInfo = "<div>$Farg : $pColorName</div>";
                }

                ############ /* Buy 2 get 1 free start */
                $buy_2_get_1_free_div = $this->productF->buy_get_free_invoice_div($id, $cartId);
                ############ /* Buy 2 get 1 free end */


                ############ FREE GIFT TEXT #############
                $free_gift_product_div = "";
                if ($totalPrice == "0" && $sum == $productQtyDiscount) {
                    $free_gift_product_div = $this->productF->free_gift_text();
                }
                ############ FREE GIFT TEXT #############


                //if deal then color or scale make blank
                if ($dealId != '0') {
                    $colorInfo = '';
                }

                $temp .= "
<div class='container-fluid padding-0 padding-bottom border $cartId' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
<div class='col-md-1 col-sm-1 padding-0 serialTd'>
<div class='cartSerial'>$i.</div>
<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
</div>
<div class='col-md-3 col-sm-3 padding-0 col-xs-12 text-center-xs imgTd'><img src='images/$pImage' alt='$pName1' loading='lazy'/></div>
<div class='col-md-2 col-sm-2 col-xs-12 text-center-xs padding-0 nameTd'>
<div class=''>
<div>$pName1</div>
<div>$sizeInfo</div>
<div>$colorInfo</div>
$buy_2_get_1_free_div
$free_gift_product_div
</div>
</div>
<div class='col-md-6 col-sm-6 padding-0 col-xs-12 priceTd'>
<div class='sumMainDiv'>
<div class='qtyDiv'>
<div>$qtyT</div>
<div class='productTotalQ'>
<div class='productQty'><span class='pQty'>$qty</span></div>
<div>X</div>
<div class='productPrice'><span class='pPrice'>$pPrice</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$sumT</div>
<div class='priceTemp'><span class='sumProduct'>$sum</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$weightT</div>
<div class='priceTemp'><span class='pWeight'>$weight </span> KG</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$discountT</div>
<div class='priceTemp'><span class='pDiscount'>$productQtyDiscount</span> $currencySymbol</div>
</div>
</div>

<div class='productTotal'>
<div class='productSum'>
<div class=''>$totalT</div>
<div class='priceTemp'><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</div>
</div>
</div>
</div>
</div>
</div>
";
            }

            $country_list = $this->functions->countrylist();
            $storeCountry = $orderInvoice['shippingCountry'];
            $countryName = $country_list[$storeCountry];
            $shipPrice = $orderInvoice['ship_price'];
            $orderTotal = $orderInvoice['total_price'];
            $countryT = $this->dbF->hardWords('Country', false);

            $shipPriceT = $this->dbF->hardWords('Shipping Price', false);
            $totalWeightT = $this->dbF->hardWords('Total Weight', false);
            $grandTotalT = $this->dbF->hardWords('Grand Total', false);

            $grandTotal = ($grandTotal + $shipPrice);


            $price_cal = $this->functions->product_tax_cal($grandTotal, 25);
            $tax = $price_cal['tax_price'];

            $giftCardDiv = '';
            $recordData = $this->productF->get_order_invoice_record($id, 'giftCard');
            if ($recordData != false) {
                $giftCardId = $recordData['setting_val'];
                $giftCardNewPriceData = $this->giftCardNewPrice($giftCardId, $grandTotal);
                $grandTotal = $giftCardNewPriceData['total'];
                $giftCard_payPrice = $giftCardNewPriceData['payPrice'];
                $giftCardDiv = "<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Gift Card Price', false) . "</div>
<div class='priceTemp'>
( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
</div>
</div>";
            }

            $temp .= "
</div>
<br>
<div class='shippingWight col-sm-12'>
<div class='col-sm-6 text-center'>
<div class='col-sm-12 lead'>$shipPriceT : <span class='pShippingPriceTemp' data-real='$shipPrice'> $shipPrice </span> $currencySymbol</div>
<div class='col-sm-12 form-horizontal'>
<div class='form-group'>
<label for='receipt_vendor' class='col-sm-2 control-label'>" . $countryT . "</label>
<div class='col-sm-8'>
<fieldset class='sender_countryFieldset'>
<input type='text' readonly value='$countryName' class='form-control'/>
</fieldset>
</div>
</div>
</div>
<div class='col-sm-12'>
<input type='hidden' id='storeCountryShippingWidget' name='storeCountry' value='$storeCountry'>
<input type='hidden' id='priceCodeShippingWidget' value='$currencySymbol'/>
</div>
<div class='clearfix'></div>
<br>
</div>
</div>


<br>
<div class='GrandTotalDiv'>
<div class='inner_grand_div'>

<div class='productSum'>
<div class=''>$totalWeightT</div>
<div class='priceTemp'><span class='pTotalWeight'>$totalWeight</span> KG</div>
</div>

<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Total Tax 25%', false) . "</div>
<div class='priceTemp'>
<span class='pGrandtax'>$tax </span> $currencySymbol
</div>
</div>

$giftCardDiv

<div class='productSum'>
<div class=''>$grandTotalT</div>
<div class='priceTemp'>
<input type='hidden' class='totalWeightInput' value='$totalWeight' data_cart='$cartId'/>
<input type='hidden' id='priceCode' value='$currencySymbol' data_cart='$cartId'/>
<span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol
</div>
</div>
</div>
<div class='continueBtn'>";
            $login = $this->webClass->userLoginCheck();
            if (!$login) {

            } else {

            }
            $temp .= "</div>
</div>";
        } else {
            $temp = false;
        }
        return $temp;
    }

    public $preview = false;

    public function somethingExtraProductByIBMS()
    {
        $extraProIds = $this->functions->ibms_setting("somethingExtra");
        $pIds = explode(",", $extraProIds);
        $product = "";
        foreach ($pIds as $key => $pid) {
            $product .= $this->pBox($pid, false, "somethingExtra");
        }
        return $product;
    }
    public function somethingExtraProduct($id)
    {

        global $_e, $productClass, $webClass, $functions;
        $sql = "SELECT order_pIds FROM `order_invoice_product` WHERE order_invoice_id =  ?";
        $orderInvoiceData = $this->dbF->getRows($sql, array($id));

        $product = "<div class='owl_carousel_checkout owl-carousel owl-theme owl-loaded owl-drag'>";



        // If product found against invoice id
        if (count($orderInvoiceData) > 0) {
            $pIds = "";
            foreach ($orderInvoiceData as $key => $val) {
                $pIds .= explode("-", $val['order_pIds'])[0] . ",";
            }
            $pIds = trim($pIds, ",");

            $sql1 = "SELECT `setting_val` FROM `product_setting` WHERE `setting_name` = ? AND `p_id` in ($pIds)";
            $somethingExtra = $this->dbF->getRows($sql1, array('somethingExtra'));
            $tempArr = array();

            // if any of the invoice product has something extra product
            if (count($somethingExtra) > 0) {
                foreach ($somethingExtra as $key1 => $val1) {
                    $val1 = unserialize($val1['setting_val']);

                    foreach ($val1 as $key3 => $val3) {
                        array_push($tempArr, $val3);
                    }

                }
            } else {
                $extraProIds = $this->functions->ibms_setting("somethingExtra");
                $extraProIds = $extraProIds["setting_val"];
                $tempArr = explode(",", $extraProIds);
            }

            $tempArr = array_unique($tempArr);
            foreach ($tempArr as $key => $pid) {
                $product .= $this->pBox($pid, false, "somethingExtra");
            }



        } else {
            $products .= $this->somethingExtraProductByIBMS();
        }

        $product .= '</div>';

        $Array = array(
            'somethingExtra' => $product
        );

        return $Array;

    }

    public function viewCheckOutProduct3($id)
    {
        global $subtotal, $currencySymbol, $shipPrice, $grandTotal;

        global $_e, $productClass, $webClass, $functions;

        $orderUser = $this->webUserId();
        if ($orderUser == '0') {
            $orderUser = $this->webTempUserId();
        }
        $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id' AND orderUser = '$orderUser'";
        $orderInvoice = $this->dbF->getRow($sql);

        if (!$this->dbF->rowCount) {
            return false;
        }

        $preview = $this->preview;
        if ($orderInvoice['orderStatus'] == 'process') {
            $this->preview = $preview = true;
        }

        $sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
        $orderProducts = $this->dbF->getRows($sql);


        # preview for showing in order preview
        if ($preview == false) {

            if ($orderInvoice['orderStatus'] == 'process') {
                $submitSuccessT = $this->dbF->hardWords('Your Order Submitted Successfully', false);
                $msg = "<br><div class='well alert alert-success h4 text-center'>$submitSuccessT </div>";

                if ($this->functions->developer_setting('invoice_print_after_Checkout') == '1') {
                    $print_link = WEB_URL . "/invoicePrint?mailId=$id&orderId=" . $this->functions->encode($id);
                    header("Location: $print_link");
                    exit;
                }
                $msg .= "   <script>
            $(document).ready(function(){
            // setTimeout(function(){location.replace('viewOrder')},1500);
            // setTimeout(function(){location.href = location.href+'&preview=1'},1500);
            });
            </script>";

                return $msg;
            }

            if ($orderInvoice['orderStatus'] == 'pendingPaypal' || $orderInvoice['paymentType'] == '1') {
                //redirect to paypal page for payment
                $this->openPaypal($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
            } else if ($orderInvoice['orderStatus'] == 'pendingPayson' || $orderInvoice['paymentType'] == '5') {
                //redirect to payson page for payment
                $this->openPayson($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
            } else if ($orderInvoice['orderStatus'] == 'pendingSwissBilling' || $orderInvoice['paymentType'] == '8') {
                //redirect to payson page for payment
                $this->openSwissBilling($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
            }

        }

        $temp = '';
        if ($this->dbF->rowCount > 0) {
            $i = 0;

            $currencyCountry = $this->currentCurrencyCountry();
            $country = $this->currentCountry();
            $currencyId = $this->currentCurrencyId();


            $temp = "";

            $abc = " <div class='container-fluid table-responsive'> ";

            $grandTotal = 0;
            $totalWeight = 0;
            $totalPriceProducts = 0;

            $hasScaleVal = $this->functions->developer_setting('product_Scale');
            $hasColorVal = $this->functions->developer_setting('product_color');

            $hasScale = ($hasScaleVal == '1' ? true : false);
            $hasColor = ($hasColorVal == '1' ? true : false);
            $sizeModal = '';


            $count = 0;
            $cart_side_order_products = $top_divs = $last_divs = $order_products = '';

            $web_url = WEB_URL;

            # three for two category start
            $three_for_2_ibm_cat = intval($this->functions->ibms_setting("checkout_two_for_3_category"));
            $three_for_2_cat_div = "3 For 2 Category";
            $three_for_2_qty = 0;
            $three_for_2_pro_price = array();
            if ($three_for_2_ibm_cat > 0) {
                $three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
            } else {
                $three_for_2_ibm_cat = array();
            }
            # three for two category end

            # staple product category start
            $staple_ibm_cat = intval($this->functions->ibms_setting("stapleProduct"));
            $staple_cat_div = "";
            $staple_qty = 0;
            $staple_pro_price = array();
            if ($staple_ibm_cat > 0) {
                $staple_ibm_cat = $this->getSubCatIds($staple_ibm_cat);
            } else {
                $staple_ibm_cat = array();
            }
            # staple product  category end

            $new_cart_side_order_product = "";
            foreach ($orderProducts as $val) {
                $this->cartInvoice = true;
                $i++;
                $cartId = $val['invoice_product_pk'];
                //Product hash
                $pIds = $val['order_pIds'];
                $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                $pId = $pArray[0]; // 491
                $scaleId = $pArray[1]; // 426
                $colorId = $pArray[2]; // 435
                $storeId = $pArray[3]; // 5
                @$customId = $pArray[4]; // 5
                $dealId = $val['deal']; // if not it is 0
                @$info = unserialize($val['info']);
                if (empty($customId)) {
                    $customId = 0;
                }
                $qty = $val['order_pQty'];
                $link = WEB_URL . '/detail.php?pId=' . $pId;

                if ($dealId == '0') {
                    $pImage = $this->productSpecialImage($pId, 'main');
                } else {
                    $dealData = $this->getDealData($dealId);
                    $pImage = $dealData['image'];
                }
                $pName = $val['order_pName'];
                $pNames = explode(" - ", $pName);
                @$pName1 = $pNames[0];
                @$pScaleName = $pNames[1];
                @$pColorName = $pNames[2];
                $pPrice = $val['order_salePrice'];
                $discount = $this->productF->productDiscount($pId, $currencyId);
                @$discountFormat = $discount['discountFormat'];
                @$discountP = $discount['discount'];

                $discountPrice = $val['order_discount'];
                $newPrice = $pPrice - $discountPrice;
                $productQtyDiscount = $discountPrice * $qty;
                $totalPrice = $newPrice * $qty;
                $totalPriceProducts += $newPrice * $qty;
                $grandTotal += $totalPrice;
                $currencySymbol = $this->currentCurrencySymbol();

                $shippingData = $this->productF->productShipping($pId, $currencyId);
                $shipping = $shippingData['propri_intShipping'];

                $weightSingle = $val['order_pWeight'];
                $weight = $weightSingle;
                $totalWeight += $weight;

                $sum = $qty * $pPrice;

                $subtotal += $sum;


                $sumT = $this->dbF->hardWords('sum', false);
                $weightT = $this->dbF->hardWords('Weight', false);
                $discountT = $this->dbF->hardWords('Discount', false);
                $qtyT = $this->dbF->hardWords('Quantity', false);
                $totalT = $this->dbF->hardWords('Total', false);
                $Farg = $this->dbF->hardWords('Farg', false);
                $sizeT = $this->dbF->hardWords('Size', false);
                $sizeTT = $this->dbF->hardWords('SIZE', false);
                $dealT = $this->dbF->hardWords('Deal', false);
                $oldT = $this->dbF->hardWords('Old', false);

                $sizeInfo = '';
                $colorInfo = '';



                $oldPInfo = "<span>(" . $oldT . " " . $pPrice . " " . $currencySymbol . ")</span>";


                if ($hasScale) {
                    $sizeInfo = "$pScaleName";
                    if ($customId != '0' && !empty($customId)) {
                        $sizeInfo = "<a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                        $customFieldsData = $this->customSubmitValues($customId);
                        $customFields = $customFieldsData['form'];
                        $sizeModal .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                    }
                    if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                        $sizeInfo = "<a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                        $customFields = $this->dealSubmitPackage($info, false);
                        $sizeModal .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);

                        $oldPInfo = '';

                    }
                }
                if ($hasColor) {
                    // decrease the color circle
                    $pColorName = str_replace('padding: 5px 12px;', 'padding: 2px 9px;', $pColorName ?? '');
                    $colorInfo = "$pColorName";
                }


                ############ /* Buy 2 get 1 free start */
                $buy_2_get_1_free_div = $this->productF->buy_get_free_invoice_div($id, $cartId);
                ############ /* Buy 2 get 1 free end */


                ############ FREE GIFT TEXT #############
                $free_gift_product_div = "";
                if ($totalPrice == "0" && $sum == $productQtyDiscount) {
                    $free_gift_product_div = $this->productF->free_gift_text();
                }
                ############ FREE GIFT TEXT #############

                //if deal then color or scale make blank
                if ($dealId != '0') {
                    $colorInfo = '';
                }


                $order_products .= "
            <div class='tc_line2'></div>
            <div class='detail_cart2'>
            <div class='img_detail2  inline_block'><a href='{$link}' data-page='{$link}' title=' {$pName1} - Sharkspeed'><img src='images/{$pImage}' alt='{$pName1}' loading='lazy'></a></div>
            <div class='info_cart2 inline_block'>
            <div class='info_head2'><a href='{$link}' data-page='{$link}' title=' {$pName1} - Sharkspeed'>{$pName1}</a></div>
            <div class='info_text2'><label>" . $sizeT . ": </label>{$sizeInfo} </div>
            <div class='info_text2'><label>" . $Farg . ": </label>{$colorInfo} </div>
            <div class='info_text2'><label>" . $qtyT . ": </label>{$qty} @ {$pPrice} {$currencySymbol}</div>
            <div class='info_text2'>$buy_2_get_1_free_div</div>
            <div class='info_text2'>$free_gift_product_div</div>
            <div class='rate_detail2'>{$newPrice} {$currencySymbol}</div>
            </div><!--info_cart2 end-->
            </div><!--detail_cart2 end-->
            ";

                $slugname = $this->get_product_slugname($pId);
                $slug_link = WEB_URL . '/' . $this->db->productDetail . $slugname;

                $productImageReal = $pImage;
                $productImage = $this->functions->resizeImage($productImageReal, '', '200', false);

                $totalPriceSimple = $newPrice * $qty;

                ############ Product Categories ##########
                $pro_cat = $this->productF->product_category($pId);

                ##################### check if product category in 3 For 2 Category START ##############
                $three_for_2_category = "";
                if (sizeof(array_intersect($three_for_2_ibm_cat, $pro_cat)) > 0 && $newPrice > 0) {
                    $three_for_2_pro_price[$cartId]["id"] = $pId;
                    $three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
                    $three_for_2_pro_price[$cartId]["qty"] = $qty;
                    $three_for_2_qty += $qty;
                    $three_for_2_category = " <img src='" . WEB_URL . "/images/3for2.jpg' alt='3for2' height='40' loading='lazy'/>";
                }

                ##################### check if product category in staple Category START ##############
                $staple_category = "";
                if (sizeof(array_intersect($staple_ibm_cat, $pro_cat)) > 0 && $newPrice > 0) {
                    $staple_pro_price[$cartId]["id"] = $pId;
                    $staple_pro_price[$cartId]["price"] = intval($newPrice);
                    $staple_pro_price[$cartId]["qty"] = $qty;
                    $staple_qty += $qty;
                    $staple_category = " <img src='" . WEB_URL . "/webImages/bundle_icon.png' alt='bundle' height='40' loading='lazy'/>";
                }





                $cart_side_order_products .= "
            
            <div class='check_out_box1'>
            <div class='check_out_box1_img'> <img src='{$productImage}' alt='{$pName1}' loading='lazy'> </div>
            <div class='check_out_box2'>
            <h3> </h3>
            <h4>{$pName1} {$three_for_2_category} {$staple_category}</h4>
            <h3>{$sizeT} </h3>
            <div class='check_out_box2_txt'>{$colorInfo}
            &nbsp;
            {$sizeInfo} <span>
            <img src='webImages/20.jpg' alt='' loading='lazy'>
            </span> </div>
            <div class='check_out_box2_txt2'> {$qtyT} <span>{$qty}</span> </div>
            <div class='check_out_box2_price'> {$newPrice} {$currencySymbol}  {$oldPInfo}</div>
            </div>
            </div>
            
            ";

                //   Jawwad order checkout popup 
                $sizeT_T = $this->dbF->hardWords('Selected Size', false);
                $new_cart_side_order_products = "";
                $new_cart_side_order_products .= "
            <div class='single_order'>
                            <div class='innerFlex_2'>
                                <div class='img_holder'>
                                    <img src='{$productImage}' alt='{$pName1}' loading='lazy'>
                                </div>
                                <div class='inner_txt2'>
                                    <div class='innertxt_header'>
                                        <div class='txt_title'>
                                            <h3>Voodo Clothing</h3>
                                            <h3>{$newPrice} {$currencySymbol}  {$oldPInfo}</h3>
                                            <p>Mid Waist</p>
                                        </div>
                                        <div class='cancel_order' onclick='cartProductRemove( this, {$cartId}, false, true )'>
                                            X
                                        </div>
                                    </div>
                                    
                                    <div class='txt_footer'>
                                        <div class='incart_size'>
                                            <label>{$sizeT_T} : <span>{$sizeInfo}</span></label>
                                            
                                        </div>
                                        <div class='incart_size incart_quantity'> 
                                         <label>{$qtyT} : <span>{$qty}</span></label>
                                        </div>
                                   
                                    </div>
                                </div>
                            </div>
            ";







                $count++;

            }


            $country_list = $this->functions->countrylist();
            $storeCountry = $orderInvoice['shippingCountry'];
            $countryName = $country_list[$storeCountry];
            $shipPrice = $orderInvoice['ship_price'];
            $orderTotal = $orderInvoice['total_price'];
            $countryT = $this->dbF->hardWords('Country', false);

            $shipPriceT = $this->dbF->hardWords('Shipping Price', false);
            $totalWeightT = $this->dbF->hardWords('Total Weight', false);
            $grandTotalT = $this->dbF->hardWords('Grand Total', false);

            $grandTotal = ($grandTotal + $shipPrice);


            $price_cal = $this->functions->product_tax_cal($grandTotal, 25);
            $tax = $price_cal['tax_price'];

            $giftCardDiv = '';
            $recordData = $this->productF->get_order_invoice_record($id, 'giftCard');
            if ($recordData != false) {
                $giftCardId = $recordData['setting_val'];
                $giftCardNewPriceData = $this->giftCardNewPrice($giftCardId, $grandTotal);
                $grandTotal = $giftCardNewPriceData['total'];
                $giftCard_payPrice = $giftCardNewPriceData['payPrice'];
                $giftCardDiv = "<div class='productSum'>
        <div class=''>" . $this->dbF->hardWords('Gift Card Price', false) . "</div>
        <div class='priceTemp'>
        ( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
        </div>
        </div>
        ";
            }


            $staple_cat_setting = unserialize($this->functions->ibms_setting("stapleProductSetting"));
            $currencyId = $this->currentCurrencyId();

            $bundle_array = array();
            for ($i = 0; $i < sizeof($staple_cat_setting['quantity']); $i++) {
                $bundle_array[$staple_cat_setting['quantity'][$i]] = $staple_cat_setting['price'][$currencyId][$i];
            }
            krsort($bundle_array);

            $bundle_type = '';
            $grandTotal = $orderInvoice['total_price'];
            global $three_for_2_minus_price;
            $three_for_2_minus_price = $orderInvoice['three_for_two_cat'];

            global $staple_pro_minus_price;
            $staple_pro_minus_price = $orderInvoice['staple_pro_cat'];

            $bundle_type = $orderInvoice['bundle_type'];



            $login = $this->webClass->userLoginCheck();
            if (!$login) {

            } else {

            }

            $abc = '
        </div>
        </div>';


            $temp = $order_products;
            $cart_side_order_products_html = $cart_side_order_products;


            // $cart_side_order_products_html = $new_cart_side_order_products;

            // jawwad=>new_cart_side_order_products
            $new_cart_side_order_products = $productClass->cartInfo(true, "checkout_popup_side_cart_view_new");
            $new_cart_side_order_products = $new_cart_side_order_products['products'];


            $cart_side_order_products_html = $new_cart_side_order_products;

        } else {
            $temp = false;
        }
        $symbol__T = $this->currentCurrencySymbol();
        return array(
            'temp' => $temp,
            'sizeModal' => $sizeModal,
            'symbol' => $symbol__T,
            'new_cart_side_order_products_html' => $new_cart_side_order_products,
            'cart_side_order_products_html' => $cart_side_order_products,
            'subtotal' => $subtotal,
            'shipPrice' => $shipPrice,
            'grandTotal' => $grandTotal,
            'three_for_2_minus_price' => $three_for_2_minus_price,
            'staple_pro_minus_price' => $staple_pro_minus_price,
            'currencySymbol' => $currencySymbol,
            'totalPriceProducts' => $totalPriceProducts,
            'bundle_type' => $bundle_type
        );
    }

    public function webUserId()
    {
        return webUserId();
    }

    public function webUserOldTempId()
    {
        return webUserOldTempId();
    }

    public function webTempUserId()
    {
        return webTempUserId();
    }

    public function cartSubmit()
    {


        //submit from carContinue... Cash on delivery or like that work
//submit from orderInvoice.php
        $btn1 = 'ORDER';
        //set Submit buttons value here
        if (isset($_POST) && !empty($_POST) && !empty($_POST['receiver_country'])) {
            if (!$this->functions->getFormToken('WebOrderReady')) {
                return false;
            }

            try {
                $this->db->beginTransaction();
                if ($_POST['submit'] == $btn1) {
                    $process = 0;
                } else {
                    throw new Exception("Cart Submit Error");
                }

                $invoiceStatus = "2"; //pending
                $invoiceId = $_POST['invoiceId'];

                @$paymentType = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : '0'; //int
                @$payment_info = $_POST['paymentInfo']; //text
                @$total_price = $_POST['totalPrice']; //Using In Security, If price from web form or php calculated not match, mean Hacking Attempt
                $userId = webUserId();
                if ($userId == '0') {
                    $userId = webTempUserId();
                }

                //major data submit here, will later here, update this table
                $rsv = '';
                if ($paymentType == '1') {
                    //PayPal
                    $status = 'pendingPayPal';
                    $invoiceStatus = "1"; //Denied
                } else if ($paymentType == '0') {
                    //cash on delivery
                    $status = 'process';

                    //giftcard /....
                    $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$invoiceId'";
                    $orderInvoice = $this->dbF->getRow($sql);

                    //gift Card work...
                    $recordData = $this->productF->get_order_invoice_record($invoiceId, 'giftCard');
                    $giftCard_payPrice = 0;
                    if ($recordData != false) {
                        $giftCardId = $recordData['setting_val'];
                        $giftCardNewPriceData = $this->giftCardNewPrice($giftCardId, $orderInvoice['total_price']);
                        $giftCard_payPrice = $giftCardNewPriceData['payPrice'];
                        if ($giftCard_payPrice > 0) {
                            //now every thing is ok in giftcard,,
                            $payment_info = $this->updateGiftCard($giftCardId, $giftCard_payPrice, $invoiceId, $giftCardNewPriceData['data']['info']) . $payment_info;
                            $rsv = ",`rsvNo` = 'GiftCard : $giftCardId; Price:$giftCard_payPrice'";
                        }
                    }
                    //giftcard End....

                } else if ($paymentType == '5') {
                    //payson
                    $status = 'pendingPayson';
                    $invoiceStatus = "1"; //Denied
                } else if ($paymentType == '8') {
                    //payson
                    $status = 'pendingSwissBilling';
                    $invoiceStatus = "1"; //Denied
                }

                ################## Add additional price of selected payment method ##############
                $payment_type_price = $this->payment_additional_price($paymentType, true);
                $payment_type_price_sql = '';
                if ($payment_type_price > 0) {
                    $payment_type_price_sql = " , `total_price` = total_price+$payment_type_price
, `ship_price` = ship_price+$payment_type_price ";
                }
                ################## Add additional price of selected payment method  END ##############

                $sql = "UPDATE  `order_invoice` SET
`orderStatus` = ?,
`invoice_status` = ?,
`paymentType` = ?,
`payment_info` = ?
$rsv
$payment_type_price_sql
WHERE order_invoice_pk = ? && orderUser = ?";
                $this->dbF->setRow($sql, array($status, $invoiceStatus, $paymentType, $payment_info, $invoiceId, $userId), false);
                // invoice first data Enter

                //Deduct Stock qty
//Paypal qty deduct after paypal confirmation
                if ($paymentType == '0') {
                    $this->functions->require_once_custom('orderInvoice');
                    $orderInvoiceClass = new invoice();
                    $returnStatus = $orderInvoiceClass->stockDeductFromOrder($invoiceId, false);
                    if ($returnStatus === false) {
                        throw new Exception($this->dbF->hardWords("Stock Deduction Error", false));
                        return false;
                    }
                }
                $senderDOB = isset($_POST['sender_bithday']) ? $_POST['sender_bithday'] : NULL;
                // User Info Add
//first add order invoice,, addNewOrder();
                $sql = "INSERT INTO `order_invoice_info` (`order_invoice_id`, `sender_Id`, `sender_name`, `sender_phone`, 
                `sender_email`, `sender_address`, `sender_city`, `sender_country`, `sender_post`, `receiver_name`, `receiver_phone`, 
                `receiver_email`, `receiver_address`, `receiver_city`, `receiver_country`, `receiver_post`, `sender_dob`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $array = array(
                    $invoiceId,
                    $userId,
                    $_POST['sender_firstName'] . ' ' . $_POST['sender_lastName'],
                    $_POST['sender_phone'],
                    $_POST['sender_email'],
                    $_POST['sender_address'],
                    $_POST['sender_city'],
                    $_POST['sender_country'],
                    $_POST['sender_post'],
                    $_POST['receiver_name'] . ' ' . $_POST['receiver_lastName'],
                    $_POST['receiver_phone'],
                    $_POST['receiver_email'],
                    $_POST['receiver_address'],
                    $_POST['receiver_city'],
                    $_POST['receiver_country'],
                    $_POST['receiver_post'],
                    $senderDOB
                );

                $this->dbF->setRow($sql, $array, false);


                $this->emptyCart();
                $this->db->commit(); // important work is end...



                $sql = "UPDATE `orderDataTbl` SET `sender_email` = ?,`sender_phone` = ?  , `acc_email` = ? , `sender_address` = ?  , `paymentType` = ? , $payment_type_price_sql1 WHERE order_invoice_pk = ? && orderUser = ?";


                $this->dbF->setRow($sql, array($_POST['sender_email'], $_POST['sender_phone'], $_POST['sender_email'], $_POST['sender_address'], $paymentType, $invoiceId, $userId));




                /////////////////////Sending Email
                {
                    //invoice id,
                    $_GET['mailId'] = $invoiceId;
                    $orderIdInvoiceF = $this->functions->ibms_setting('invoice_key_start_with') . $invoiceId;
                    $orderIdInvoice = $this->dbF->hardWords('ORDERING', false) . " ($orderIdInvoiceF)"; // return Order invoice-12
                    $fromName = $this->functions->webName; // website name
                    $mailArray['fromName'] = $fromName;
                    $mailArray['invoiceNumber'] = $orderIdInvoiceF;

                    if ($paymentType == '0') {
                        //send email when payment method is cas on delivery
                        $msg2 = include(__DIR__ . '/../../orderMail.php');
                        //send email to client
                        $this->functions->send_mail($_POST['sender_email'], $orderIdInvoice, $msg2, '', $_POST['sender_firstName'] . ' ' . $_POST['sender_lastName'], $mailArray);

                        // Special offer send on invoice generate

                        if ($_POST['sender_email'] != $_POST['receiver_email']) {
                            //send email to client if both email not equal
                            $this->functions->send_mail($_POST['receiver_email'], $orderIdInvoice, $msg2, '', $_POST['sender_firstName'] . ' ' . $_POST['sender_lastName'], $mailArray);
                        }

                        //send email to admin
                        $adminMail = $this->functions->ibms_setting('Email');
                        $this->functions->send_mail($adminMail, $orderIdInvoice, $msg2, '', '', $mailArray);

                    } //////////////////////Sending Email PDF End


                    //check is custom product has
                    $sql = "SELECT * FROM `order_invoice_product` WHERE order_pIds NOT LIKE '%-%-%-%-0' AND `order_invoice_id` = '$invoiceId'";
                    $customData = $this->dbF->getRow($sql);
                    if ($this->dbF->rowCount > 0) {
                        //Email custom measurement link...
                        $invoiceLink = WEB_URL . "/viewOrder?view=$invoiceId&orderId=" . $this->functions->encode($invoiceId);
                        $mailArray['link'] = $invoiceLink;
                        $this->functions->send_mail($_POST['sender_email'], $orderIdInvoice, '', 'Measure_email_on_invoice_create', $_POST['sender_firstName'] . ' ' . $_POST['sender_lastName'], $mailArray);
                    }
                }//////////////////////Sending Email End
                $msg = $this->dbF->hardWords('Thank you your Order is successfully submitted', false);


                $this->restCartSession();
                if ($paymentType == '1') {
                    $this->openPaypal($paymentType, $invoiceId);
                } elseif ($paymentType == '5') {
                    $this->openPayson($paymentType, $invoiceId);
                } elseif ($paymentType == '8') {
                    $this->openSwissBilling($paymentType, $invoiceId);
                }
                
                return $msg;
            } catch (Exception $e) {
                $this->dbF->error_submit($e);
                $msgT = $e->getMessage();
                $this->db->rollBack();
                $msg = $this->dbF->hardWords('Something went wrong Please try again', false);
                return $msg . " <br> " . $msgT;
            }

        } else if (isset($_POST) && !empty($_POST) && isset($_POST['submit']) && @$_POST['submit'] == $btn1) {
            $msg = $this->dbF->hardWords('Something Went Wrong, Order Submit Fail', false);
            return $msg;
        }
    } //Function End

    public $orderLastInvoiceId = 0;

    public function cartSubmitForCheckOut($directSubmit = false)
    {
        // call from cart.php, first submit here...
        // klarna or after login continue,, this will call function

        $btn1 = 'ORDER';
        global $_e;
        //set Submit buttons value here
        if (isset($_POST) && !empty($_POST) || $directSubmit) {

            if ($directSubmit) {
                $_POST['storeCountry'] = $this->currentCountry();
                $_POST['shippingWidget'] = $this->currentCountry();
            }

            if (!$this->functions->getFormToken('WebOrderReadyForCheckOut') && $directSubmit == false) {
                return false;
            }

            try {
                $this->db->beginTransaction();

                $invoiceStatus = "1"; //denied
                $invoiceId = '';     //
                $process = 0;
                $orderStatus = 'inComplete';
                @$country = $_POST['storeCountry'];
                $price_code = $this->currentCurrencySymbol();

                $orderUserId = $this->webUserId();
                if ($orderUserId == '0') {
                    $orderUserId = $this->webTempUserId();
                }
                $shippingCountry = $_POST['shippingWidget'];

                $countryData = $this->productF->productCountryId($country);
                $countryId = $countryData['cur_id'];



                ############## ORDER INVOICE REMOVING OLD ORDER, BACK BUTTON CART WORKING BELOW ################


                ################################################################################################



                $lang = currentWebLanguage();

                ############################### //major data submit here, will later here, update this table #################################
                $now = date('Y-m-d H:i:s');
                $sql = "INSERT INTO `order_invoice`
(
`price_code`,
`invoice_date`,
`invoice_status`,
`orderStatus`,
`orderUser`,
`shippingCountry`,
`language`
)
VALUES (
?,?,?,?,?,?,?
)";
                $array = array($price_code, $now, $invoiceStatus, $orderStatus, $orderUserId, $shippingCountry, $lang);
                $this->dbF->setRow($sql, $array, false);
                $invoiceId = $this->dbF->rowLastId;
                $this->orderLastInvoiceId = $invoiceId;


                $sqlC = "INSERT INTO `orderDataTbl`(`pname`,`sender_email`,`sender_phone`,`invoice_id`,`invoice_date`,`dateTime`,`flagged`,`shippingCountry`,`orderStatus`,`invoice_status`,`acc_name`,`acc_email`,`sender_address`,`order_invoice_pk`,`orderUser`,`paymentType`,`total_price`,`price_code`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $res = $this->dbF->setRow($sqlC, array("", "", "", $invoiceId, $now, $now, "0", $shippingCountry, $orderStatus, $invoiceStatus, "", "", "", $invoiceId, $orderUserId, "", "", $price_code));


                ##################### //invoice first data Enter ###################


                ##################### //Invoice Product add ################
                $userId = $this->webUserId();
                $tempId = $this->webTempUserId();


                ############## /* Buy 2 get 1 free start */
                $has_buy_get_free = false;
                $buy_get_free_query = "";
                if ($this->functions->developer_setting("buy_2_get_1_free") == "1" && true == false) {
                    $has_buy_get_free = true;
                    $buy_get_free_query = ", (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free') as buy_2_get_1_free
, (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free_qty') as buy_2_get_1_free_qty";
                }
                ############# /* Buy 2 get 1 free end */


                ############ 3 For 2 Category START #########
                $three_for_2_ibm_cat = intval($this->functions->ibms_setting("checkout_two_for_3_category"));
                $three_for_2_cat_div = "3 For 2 Category";
                $three_for_2_qty = 0;
                $three_for_2_pro_price = array();
                if ($three_for_2_ibm_cat > 0) {
                    $three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
                } else {
                    $three_for_2_ibm_cat = array();
                }
                ############ 3 For 2 Category END  ##########

                # staple product category start
                $staple_ibm_cat = intval($this->functions->ibms_setting("stapleProduct"));
                $staple_cat_div = "";
                $staple_qty = 0;
                $staple_pro_price = array();
                if ($staple_ibm_cat > 0) {
                    $staple_ibm_cat = $this->getSubCatIds($staple_ibm_cat);
                } else {
                    $staple_ibm_cat = array();
                }
                # staple product  category end


                $sql = "SELECT *, (SELECT setting_val FROM product_setting as s WHERE c.pId = s.p_id AND s.setting_name = 'shippingClass') AS shippingPrice $buy_get_free_query FROM `cart` as c WHERE `userId` = '$userId' AND tempUser = '$tempId'";
                $data = $this->dbF->getRows($sql);
                $temp = '';
                if (!$this->dbF->rowCount) {
                    throw new Exception("Some Thing Is Wrong, No items in cart: error:42251");
                    //42251 = hack1
//Hack
                }
                $grandTotal = 0;
                $totalWeight = 0;
                $shippingClassPrice = 0;

                $currencyCountry = $this->currentCurrencyCountry();
                $country = $this->currentCountry();
                $currencyId = $this->currentCurrencyId();

                foreach ($data as $key => $val) {
                    $cartId = $val['id'];
                    $pId = $val['pId'];
                    $scaleId = $val['scaleId'];
                    @$scaleName = $val['scaleName'];
                    $sqlScaleNew = "SELECT * FROM `product_size` WHERE `prosiz_id` = ?";
                    $scaleData = $this->dbF->getRow($sqlScaleNew, [$scaleId]);
                    
                    if($this->dbF->rowCount == 0){
                        $sqlScaleNew2 = "SELECT `prosiz_id` FROM `product_size` WHERE `prosiz_prodet_id` = ? AND `prosiz_name` = ? AND `prosiz_cur_id` = ?";
                        $scaleData2 = $this->dbF->getRow($sqlScaleNew2, [$pId, $scaleName, 20]);
                        if($this->dbF->rowCount > 0){
                            $scaleId = $scaleData2['prosiz_id'];
                        }
                    }
                    
                    $colorId = $val['colorId'];
                    $storeId = $val['storeId'];
                    $salePrice = $val['salePrice'];
                    $customId = $val['customId'];
                    $dealId = $val['deal'];
                    @$checkout = $val['checkout'];
                    @$info = unserialize($val['info']);
                    $infoM = $info;
                    $pIds = [$pId, $scaleId, $colorId, $storeId, $customId];
                    $pIds = implode("-", $pIds);


                    ############ Product Categories ##########
                    $pro_cat = $this->productF->product_category($pId);

                    $qty = $val['qty'];
                    $coupon = "";
                    @$shippingId = $val['shippingPrice'];
                    $shippingPriceData = $this->shippingPriceByClass($shippingId, $shippingClassPrice);
                    $shippingPriceT = $shippingPriceData["price"];
                    $shippingClassPrice = $shippingPriceData["classPrice"];

                    //if not deal, it is product
                    if ($dealId == '0') {
                        $pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);


                        if ($customId != '0' && $scaleId == '0') {
                            $pName = explode(" - ", $pName);
                            $pName[1] = $_e['Custom'];
                            $pName = implode(" - ", $pName);
                        }
                        $pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);


                        //checking if this is checkout offer then checkout discount offer apply
//if deal is 0 then checout offer need to check,
//if checkOut is 1 then coupon not apply and different price will apply
                        if ($checkout == '1') {
                            $checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
                            //add color or scale price
                            $p_color_price = $this->productF->colorPrice($colorId, $currencyId, $pId);
                            @$p_color_price = $p_color_price['proclr_price'];
                            $p_size_price = $this->productF->scalePrice($scaleId, $currencyId, $pId);
                            @$p_size_price = $p_size_price['prosiz_price'];
                            $checkoutPrice = $checkoutPrice + floatval($p_color_price) + floatval($p_size_price);
                            $discountPrice = $pPrice - $checkoutPrice;
                            @$discountFormat = "price";
                            @$discountP = $discountPrice;
                        }
                        ############################################################
################# FREE GIFT Check Is product FREE then make it price 0 free ############
                        else if ($checkout == '2') {
                            $discountPrice = $pPrice;
                            @$discountFormat = "price";
                            @$discountP = $discountPrice;
                        } else {
                            $coupon = $this->getCoupon();
                            if (!empty($coupon)) {
                                $this->productF->set_order_invoice_record($invoiceId, 'coupon', $coupon, $pId);
                            }

                            $discount = $this->productF->productDiscount($pId, $currencyId, $coupon);
                            $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
                        }///$checkout else end

                        $shippingData = $this->productF->productShipping($pId, $currencyId);
                        $shipping = $shippingData['propri_intShipping'];
                        $pImage = $this->productSpecialImage($pId, 'main');
                        $weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);
                    } else {
                        //if order is deal
                        $dealData = $this->getDealData($dealId);
                        $pName = $this->getDealNameWeb($dealData);

                        $pPrice = $this->getDealPrice($dealData);
                        $discountPrice = 0;
                        $discountFormat = $discountP = '';

                        $shipping = $this->getDealShippingStatus($infoM);
                        $pImage = $dealData['image'];
                        $weightSingle = $this->getDealWeight($infoM);

                        $info = $this->getDealProductOrders($infoM);
                    }


                    $discountPrice = round($discountPrice, 2);

                    $newPrice = round($pPrice - $discountPrice, 2);

                    if (!empty($salePrice) && $salePrice != '') {
                        $newPrice = $salePrice;
                        $discountPrice = $pPrice - $newPrice;
                        $discountPrice = round($discountPrice, 2);
                    }

                    $totalPrice = $newPrice * $qty;
                    $grandTotal += $totalPrice;

                    $productQtyDiscount = $discountPrice * $qty;

                    $weight = $weightSingle * $qty;
                    $totalWeight += $weight;

                    $storeName = $this->productF->getStoreName($storeId);

                    @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
                    $hash = md5($hashVal);

                    //insert product info into order products
                    $sql = "INSERT INTO `order_invoice_product` (`order_invoice_id`, `order_pIds`, `order_pName`, `order_pStore`, `order_pPrice`, `order_salePrice`, `order_discount`, `order_pQty`, `order_pWeight`, `order_process`, `deal`, `checkout`, `info`, `order_hash`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";



                    if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {
                        $pPrice = 0;
                        $discountPrice = 0;
                        $process = 1;
                    }
                    
                    $dataTime__ = date('d-m-Y H:i:s');
                    if ($process > 0) {
                        $msg = "<table>
            <tr><td>invoiceId</td><td>$invoiceId</td></tr>
            <tr><td>productId</td><td>$pIds</td></tr>
            <tr><td>pName</td><td>$pName</td></tr>
            <tr><td>hash</td><td>$hash</td></tr>
            <tr><td>Date</td><td>$dataTime__</td></tr>
    </table>";

                        // $this->functions->send_mail('jawwad.rafique007@gmail.com', 'Order Process', $msg);
                        $process = 0;
                    }



                    @$info = serialize($info);
                    $array = [$invoiceId, $pIds, $pName, $storeName, $pPrice, $pPrice, $discountPrice, $qty, $weight, $process, $dealId, $checkout, $info, $hash];
                    $this->dbF->setRow($sql, $array, false);
                    $invoice_product_id = $this->dbF->rowLastId;
                    
                    $log_desc = "Order created with Product(s):<br>$pName";
                    $this->orderlog("Product Added In Order", 'Order Product', $shippingCountry . $invoiceId, $log_desc);
                    ############ Buy 2 Product get 1 free START  ##############

                    ############ Buy 2 get 1 free END #########
####################################################################################


                    ##################### check if product category in 3 For 2 Category START ##############
                    if (sizeof(array_intersect($three_for_2_ibm_cat, $pro_cat)) > 0 && $newPrice > 0) {
                        $three_for_2_pro_price[$cartId]["id"] = $pId;
                        $three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
                        $three_for_2_pro_price[$cartId]["qty"] = $qty;
                        $three_for_2_qty += $qty;
                    }
                    #################### check if product category in 3 For 2 Category END ###############

                    ##################### check if product category in staple Category START ##############
                    if (sizeof(array_intersect($staple_ibm_cat, $pro_cat)) > 0 && $newPrice > 0) {
                        $staple_pro_price[$cartId]["id"] = $pId;
                        $staple_pro_price[$cartId]["price"] = intval($newPrice);
                        $staple_pro_price[$cartId]["qty"] = $qty;
                        $staple_qty += $qty;
                    }


                } // Foreach loop End

                ############ 3 For 2 Category START #########
// $three_for_2_qty = floor($three_for_2_qty/3);
                $staple_minus_price = $this->staple_category_rec($staple_pro_price, $staple_qty);
                $grandTotal = $grandTotal - $staple_minus_price;
                ############ 3 For 2 Category END #########

                $staple_pro_cat_div = $this->cartBundleDetail($staple_qty);

                ###################### //Update invoice after

                //Calculating Shiping price
                $shippingInfo = $this->shippingInfo();
                $shippingType = $shippingInfo["shippingType"];
                $shippingPriceLimit = $shippingInfo["priceLimit"];

                if ($grandTotal > $shippingPriceLimit) {
                    //Free shipping
                    $finalShippingPrice = 0;

                } else {
                    if ($shippingType == 'class') {
                        $finalShippingPrice = $shippingClassPrice;
                    } else {
                        $shippingData = $this->productF->shippingPrice($country, $_POST['shippingWidget']);
                        if ($shippingData == false) {
                            throw new Exception("Shipping Error Found");
                        }

                        $shippingWeight = $shippingData['shp_weight'];
                        $shippingPrice = $shippingData['shp_price'];
                        $this->functions->includeOnceCustom(ADMIN_FOLDER . '/shipping/classes/shipping.php');
                        $shippingC = new shipping();
                        $shippingWeight = $shippingC->shpWeightArrayFind($shippingWeight);

                        //calculating
                        $unitWeight = ceil($totalWeight / $shippingWeight);
                        $unitWeight = round($unitWeight, 2);
                        $finalShippingPrice = $shippingPrice * $unitWeight;
                    }
                    $grandTotal += $finalShippingPrice;

                }

                $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with'); // Invoice Number start with


                $giftCardData = $this->giftCardCheck($grandTotal);
                $giftCardId = $giftCardData['giftCardId'];

                if (!empty($giftCardId)) {
                    $giftCard_payPrice = $giftCardData['payPrice'];
                    if (!$giftCardData['error'] && $giftCard_payPrice > 0) {
                        $this->productF->set_order_invoice_record($invoiceId, 'giftCard', $giftCardId);
                    }
                }

                ############ 3 For 2 Category START #########
                $three_for_2_qty = floor($three_for_2_qty / 3);
                $three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price, $three_for_2_qty);
                $grandTotal = $grandTotal - $three_for_2_minus_price;
                ############ 3 For 2 Category END #########

                $host = $_SESSION['webUser']['currencyC'];

                $sql = "UPDATE `order_invoice` SET
`invoice_id`    =   '" . $host . '' . $invoiceKey . '' . $invoiceId . "',
`total_price`   =   '$grandTotal',
`ship_price`    =   '$finalShippingPrice',
`three_for_two_cat`    =   '$three_for_2_minus_price',
`staple_pro_cat`    =   '$staple_minus_price',
`bundle_type` = ?,
`total_weight`  =   '$totalWeight'
WHERE `order_invoice_pk`  = ?";


                if (@$_SESSION['freeGift_ai_id'] && @$_SESSION['freeGift_ai_id'] != '') {


                    $grandTotal = 0;
                    $finalShippingPrice = 0;


                    $sqlIcs = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = ?";
                    $dataz = $this->dbF->getRow($sqlIcs, array($_SESSION['freeGift_ai_id']));

                    $orderUser = $dataz['orderUser'];
                    $orderTempUser = $dataz['orderTempUser'];
                    $paymentType = $dataz['paymentType'];
                    $payment_info = $dataz['payment_info'];
                    $invoice_status = $dataz['invoice_status'];
                    $orderStatus = $dataz['orderStatus'];


                    $sql = "UPDATE `order_invoice` SET
`invoice_id`    =   '" . $host . '' . $invoiceKey . '' . $invoiceId . "',
`total_price`   =   '$grandTotal',
`ship_price`    =   '$finalShippingPrice',
`three_for_two_cat`    =   '$three_for_2_minus_price',
`staple_pro_cat`    =   '$staple_minus_price',


`orderUser`    =   '$orderUser',

`orderTempUser`    =   '$orderTempUser',
`paymentType`    =   '$paymentType',
`payment_info`    =   '$payment_info',

`invoice_status`    =   '$invoice_status',
`orderStatus`    =   '$orderStatus',


`bundle_type` = ?,
`total_weight`  =   '$totalWeight'
WHERE `order_invoice_pk`  = ?";

                } else {
                    $sql = "UPDATE `order_invoice` SET
`invoice_id`    =   '" . $host . '' . $invoiceKey . '' . $invoiceId . "',
`total_price`   =   '$grandTotal',
`ship_price`    =   '$finalShippingPrice',
`three_for_two_cat`    =   '$three_for_2_minus_price',
`staple_pro_cat`    =   '$staple_minus_price',
`bundle_type` = ?,
`total_weight`  =   '$totalWeight'
WHERE `order_invoice_pk`  = ?";


                }






                if (@$_SESSION['saleOffer_ai_id'] && @$_SESSION['saleOffer_ai_id'] != '') {


                    $finalShippingPrice = 0;





                    $sql = "UPDATE `order_invoice` SET
`invoice_id`    =   '" . $host . '' . $invoiceKey . '' . $invoiceId . "',
`total_price`   =   '$grandTotal',
`ship_price`    =   '$finalShippingPrice',
`three_for_two_cat`    =   '$three_for_2_minus_price',
`staple_pro_cat`    =   '$staple_minus_price',
`bundle_type` = ?,
`total_weight`  =   '$totalWeight'
WHERE `order_invoice_pk`  = ?";

                } else {
                    $sql = "UPDATE `order_invoice` SET
`invoice_id`    =   '" . $host . '' . $invoiceKey . '' . $invoiceId . "',
`total_price`   =   '$grandTotal',
`ship_price`    =   '$finalShippingPrice',
`three_for_two_cat`    =   '$three_for_2_minus_price',
`staple_pro_cat`    =   '$staple_minus_price',
`bundle_type` = ?,
`total_weight`  =   '$totalWeight'
WHERE `order_invoice_pk`  = ?";


                }





                $this->dbF->setRow($sql, array($staple_pro_cat_div['bundles'], $invoiceId));

                $this->db->commit();
                $this->updateCartStatus();


                $msg = $this->dbF->hardWords('Your Cart Is ready to submit, Please Select Payment Type To place your Order.', false);
                return $msg;
            } catch (Exception $e) {
                $this->dbF->error_submit($e);
                $this->db->rollBack();
                $msg = $this->dbF->hardWords('Something went wrong Please try again', false);
                return $msg;
            }
        }

    } //Function End

    public function restCartSession()
    {
        unset($_SESSION['webUser']['giftCard']);
        unset($_SESSION['webUser']['coupon']);
    }

    public function giftCardNewPrice($giftCardId, $grandTotal)
    {
        $_SESSION['webUser']['giftCard'] = $giftCardId;
        $giftCardData = $this->giftCardCheck($grandTotal);
        if (!empty($giftCardId)) {
            $giftCard_payPrice = $giftCardData['payPrice'];
            if (isset($giftCardData['error']) && $giftCardData['error'] == false && $giftCard_payPrice > 0) {

                $grandTotal = $grandTotal - $giftCard_payPrice;
            }

        }
        return array('total' => $grandTotal, 'payPrice' => $giftCard_payPrice, 'data' => $giftCardData);
    }

    public function shippingInfo()
    {
        $shippingType = $this->functions->ibms_setting("shippingType");
        $currencyId = $this->currentCurrencyId();
        $shippingPriceLimit = unserialize($this->functions->ibms_setting('check_out_shiping_price_limit'));
        @$shippingPriceLimit = $shippingPriceLimit[$currencyId];

        $array = array(
            "shippingType" => $shippingType,
            "priceLimit" => $shippingPriceLimit,
        );

        return $array;
    }

    public function shippingPriceByClass($shippingId, $oldClassPrice = 0)
    {
        $shippingPrice = 0;
        if ($this->functions->developer_setting('shipping_class') == '1') {
            if (empty($shippingId)) {
                //Shipping by weight
            } else {
                $currencyId = $this->currentCurrencyId();
                $sql = "SELECT * FROM `shipping_class` WHERE `id` = '$shippingId'";
                $shippingData = $this->dbF->getRow($sql);
                $shippingDataPrice = unserialize($shippingData['price']);

                $shippingPrice = $shippingDataPrice[$currencyId];
                if ($oldClassPrice < $shippingPrice) {
                    $oldClassPrice = $shippingDataPrice[$currencyId];
                }
            }
        }
        $array = array(
            "price" => $shippingPrice,
            "classPrice" => $oldClassPrice, //high price of all classes...
        );
        return $array;
    }

    public function getDealProductOrders($info)
    {
        //working here,, get all product info and save in order product table info,, and show array in order view
        $pName = array();
        foreach ($info as $val) {
            $pId = $val['pId'];
            $scaleId = $val['scaleId'];
            $colorId = $val['colorId'];
            $pName[$pId]['pIds'] = "$pId-$scaleId-$colorId";
            $pName[$pId]['name'] = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
        }
        return $pName;
    }

    public function emptyCart()
    {

        $userId = $this->webUserId();
        $sql = "DELETE FROM `cart` WHERE `userId` = '$userId'";
        $sqlcart_order_remaining = "DELETE FROM `cart_order_remaining` WHERE `userId` = '$userId'";

        if ($userId == 0) {
            # means not logged in user
            $userId = $this->webTempUserId();
            $sql = "DELETE FROM `cart` WHERE `tempUser` = '$userId'";
            $sqlcart_order_remaining = "DELETE FROM `cart_order_remaining` WHERE `userId` = '$userId'";

        }

        $this->dbF->setRow($sqlcart_order_remaining);
        $this->dbF->setRow($sql);
        $result = ($this->dbF->rowCount > 0) ? TRUE : FALSE;
        return $result;
    }

    public function updateCartStatus()
    {
        //Update status so we can come back from the order page and resume this cart session
        $userId = $this->webUserId();
        $tempUserId = $this->webTempUserId();
        $sql = " UPDATE `cart` SET `order_status` = ? WHERE `userId` = ? AND `tempUser` = ? ";
        $this->dbF->setRow($sql, array('1', $userId, $tempUserId));
    }

    public function order_remove_unprocessed()
    {

        # delete old unprocessed order, person has submitted cart button again, delete old order and create new order.
        $sql = "
DELETE FROM `order_invoice` WHERE `order_invoice_pk` IN (
SELECT * FROM (
SELECT oi.order_invoice_pk FROM `order_invoice` oi
INNER JOIN `cart` c ON c.tempUser = oi.orderUser
WHERE oi.orderStatus = 'inComplete' AND c.order_status = 1
) as t
)
";
        $this->dbF->setRow($sql);
    }

    /**
     * @param string $ulClass
     * @param string $liClass
     * @param string $aClass
     * @param string $divClass
     * @return string
     * First ul Li list
     */
    public function getCategorySingle($ulClass = '', $liClass = '', $aClass = '', $divClass = '')
    {
        $sql = "SELECT * FROM tree_struct JOIN tree_data
ON  tree_struct.id = tree_data.id
WHERE lvl  = '1' ORDER BY pos ASC";
        $data = $this->dbF->getRows($sql);
        $webLang = currentWebLanguage();

        $array = array();
        $data2 = $data;
        foreach ($data2 as $val) {
            $name = $val['nm'];
            $array[$name] = '';
        }
        global $_e;
        $_e = $this->dbF->hardWordsMulti($array, $webLang, 'webCategory');

        $link = WEB_URL . "/" . $this->db->pCategory;

        $temp = "<ul class='$ulClass'>";
        foreach ($data2 as $val) {
            $name = $_e[$val['nm']];

            $linkT = $link . "$val[id]-$val[nm]"; // pCategory slug

            //print Structure
            $temp .= "<li class='$liClass'>
<a href='$linkT' class='$aClass'>
<div  class='$divClass'>$name</div>
</a>
</li>";

        }
        $temp .= "</ul>";
        return $temp;
    }

    public function getCategoryQueryData($level, $parentId = false)
    {
        $parent = '';
        if ($parentId != false) {
            $parent = " AND pid = '$parentId'";
        }
        if ($level === false) {
            $level = "";
        } else {
            $level = " AND lvl = '$level'";
        }
        $sql = "SELECT * FROM tree_struct JOIN tree_data
ON  tree_struct.id = tree_data.id
WHERE tree_data.id != '1' $level $parent ORDER BY pos ASC";
        $data = $this->dbF->getRows($sql);
        return $data;
    }

    private function categoryArraySearch($data, $pid)
    {
        $temp = array();
        foreach ($data as $val) {
            if ($val['pid'] == $pid) {
                $val['has-sub'] = '0'; // 2nd and 3rd array initital value
                $temp[$val['nm']] = $val;
            }
        }
        return $temp;
    }

    public function getCategoryArray($dept = 3)
    {
        $data = $this->getCategoryQueryData(false);
        $cat = array();
        foreach ($data as $val) {
            if ($val['lvl'] == '1') {
                $val['has-sub'] = '0'; // initial 1st array value
                $cat[$val['nm']] = $val;
            }
        }

        if ($dept > 1) {
            foreach ($cat as $key => $val) {
                $c2 = $this->categoryArraySearch($data, $val['id']);
                if (!empty($c2)) {
                    $cat[$key]['has-sub'] = '1'; // 1st array
                    $cat[$key][$val['nm']] = $c2;
                    if ($dept > 2) {
                        foreach ($c2 as $key3 => $val3) {
                            $c3 = $this->categoryArraySearch($data, $val3['id']);
                            if (!empty($c3)) {
                                $cat[$key][$val['nm']][$val3['nm']][$val3['nm']] = $c3;
                                $cat[$key][$val['nm']][$key3]['has-sub'] = '1'; // 2nd array
                            }
                        }
                    }//if >2 end

                } // if c2 not empty
                else {
                    $cat[$key]['has-sub'] = '0';
                }
            }//foreach
        }//if >1


        $this->makeCategoryArrayMultiLanguageE($data);

        return $cat;
    }

    private function makeCategoryArrayMultiLanguageE($data, $lang = false)
    {
        if ($lang == false) {
            $lang = currentWebLanguage();
        }

        $_w = array();
        foreach ($data as $val) {
            $_w[$val['nm']] = '';
        }

        $_e = $this->dbF->hardWordsMulti($_w, $lang, 'product category');

    }

    /**
     * get url from .htaccess and make it proper parameter.
     */
    public function setProductSlug()
    {
        if (isset($_GET['catSlug'])) {
            $slug = ($_GET['catSlug']);
            if (stristr($slug, "-")) {
                $slug = explode("-", $slug, 2);
                $_GET['catId'] = $slug[0];
                @$_GET['cat'] = $slug[1];
            } else if (intval($slug) > 0) {
                $_GET['catId'] = $slug;
            } else {
                $_GET['cat'] = $slug;
            }
        }
    }

    public function getCategoryList($ulClass = '', $liClass = '', $aClass = '', $spanClass = '')
    {
        $data = $this->getCategoryArray();
        $webLang = currentWebLanguage();
        $page = basename($_SERVER['PHP_SELF']);
        if ($page == 'search' || $page == 'search.php') {
            $page = 'search';
        } else {
            $page = 'products';
            $baseLink = WEB_URL . "/" . $this->db->pCategory;
        }


        if (isset($_GET['catId'])) {
            @$cat = $_GET['catId'];
        } else {
            @$cat = $_GET['cat'];
        }


        global $_e;
        $temp1 = '';
        $is3Active = false;
        $is2ndActive = false;
        $display = '';

        $temp = "<!--1st array-->
<ul class='$ulClass'>";
        foreach ($data as $key => $val) {
            $active = '';
            $aActive = '';
            $temp2 = '';
            if (isset($val['has-sub']) && $val['has-sub'] == '1') {
                //2nd array
                $temp2V = '';
                foreach ($data[$key][$key] as $key2 => $val2) {
                    $temp3 = '';
                    if (isset($val2['has-sub']) && $val2['has-sub'] == '1') {
                        //3rd array

                        $temp3V = '';
                        foreach ($data[$key][$key][$key2][$key2] as $key3 => $val3) {
                            if ($cat == $val3['id'] || $cat == $val3['nm']) {
                                $active = 'active';
                                $aActive = 'aActive';
                                $is3Active = true;
                            } else {
                                $active = '';
                                $aActive = '';
                            }
                            $name3 = $_e[$val3['nm']];
                            if ($page == 'products') {
                                $link3 = $baseLink . "$val3[id]-$val3[nm]"; // pCategory slug
                            } else {
                                $link3 = WEB_URL . "/$page?cat=" . $val3['id'];
                            }

                            //print Structure
                            $temp3V .= "<li class='" . $liClass . "_3 $active'>
<a href='$link3' class='" . $aClass . "_3 $aActive'>
<span  class='" . $spanClass . "_3'>$name3</span>
</a>";

                            $temp3V .= "</li>";
                        }
                        $display = '';
                        if ($is3Active) {
                            $display = "style='display:block'";
                        }
                        $temp3 .= "<!--2nd array--><ul class='" . $ulClass . "_3' $display>
$temp3V
</ul><!--3rd array End-->";
                        //3rd array end
                    }

                    //2nd array
                    $name2 = $_e[$val2['nm']];
                    if ($page == 'products') {
                        $link2 = $baseLink . "$val2[id]-$val2[nm]"; // pCategory slug
                    } else {
                        $link2 = WEB_URL . "/$page?cat=" . $val2['id'];
                    }
                    $active = '';
                    $aActive = '';
                    if ($cat == $val2['id'] || $cat == $val2['nm'] || $is3Active) {
                        $active = 'active';
                        $aActive = 'aActive';
                        $is2ndActive = true;
                    }
                    $lisHasSub = '';
                    if ($val2['has-sub'] == '1') {
                        $lisHasSub = 'has-sub';
                    }

                    //print Structure
                    $temp2V .= "<li class='" . $liClass . "_2 $lisHasSub $active'>
<a href='$link2' class='" . $aClass . "_2 $aActive'>
<span  class='" . $spanClass . "_2'>$name2</span>
</a>
$temp3";
                    $is3Active = false;

                    $temp2V .= "</li>";
                }

                $display = '';
                if ($is2ndActive) {
                    $display = "style='display:block'";
                }
                $temp2 .= "<!--2nd array--><ul class='" . $ulClass . "_2' $display>
$temp2V
</ul><!--2nd array End-->";
                //2nd array end
            }

            //1st array
            $active = '';
            $aActive = '';
            if ($cat == $val['id'] || $cat == $val['nm'] || $is2ndActive) {
                $active = 'active';
                $aActive = 'aActive';
                $is2ndActive = true;
            }


            $name1 = $_e[$val['nm']];
            if ($page == 'products') {
                $link1 = $baseLink . "$val[id]-$val[nm]"; // pCategory slug
            } else {
                $link1 = WEB_URL . "/$page?cat=" . $val['id'];
            }

            $lisHasSub = '';
            if ($val['has-sub'] == '1') {
                $lisHasSub = 'has-sub';
            }


            $temp1 .= "<li class='$liClass $lisHasSub $active' $display>
<a href='$link1' data-page='$link1' title='$name1 - Sharkspeed' class='$aClass $aActive'>
<span  class='$spanClass'>$name1</span>
</a>
$temp2";
            $is2ndActive = false;
            $temp1 .= "</li>";
        }
        $temp .= "$temp1 </ul><!--1st array End-->
<input type='hidden' class='activeCategory' value='$cat'/>
";
        return $temp;
    }

    public function getCategoryList1()
    {
        $temp = "<div id='categoryMenu1'>";
        $temp .= $this->getCategoryList();
        $temp .= "</div>";
        $temp .= '<link rel="stylesheet" type="text/css" href="' . WEB_URL . '/css/category/categoryStyle1.css"/>';
        return $temp;
    }

    public function getCategoryList2()
    {
        $temp = "<div id='categoryMenu2'>";
        $temp .= $this->getCategoryList();
        $temp .= "</div>";
        $temp .= '<link rel="stylesheet" type="text/css" href="' . WEB_URL . '/css/category/categoryStyle2.css"/>';
        $temp .= '<script type="text/javascript" src="' . WEB_URL . '/css/category/category2.js"></script>';
        return $temp;
    }

    public function getDistinctColor()
    {
        if ($this->functions->developer_setting('product_color') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT DISTINCT(proclr_name) as name , propri_id FROM `product_color` GROUP BY proclr_name ORDER BY propri_id ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "
<div class='padding-0 pColorSearch'>
<div class='heading'>
<span>" . $_e['Color'] . "</span>
</div>
<div class='container-fluid colorCheckBoxes text-center'>

";
        $color = array();
        @$selected = $_GET['color'];
        if ($selected == "" || $selected == false) {
            $selected = array();
        } else {
            $selected = explode(",", $selected);
        }
        foreach ($data as $val) {
            $name = $val['name'];
            $name = str_replace(" ", "", $name);
            $name = str_replace("#", "", $name);
            $name = HexToColorName($name);
            if (in_array($name, $color)) {
                continue;
            }
            $checked = "";
            if (in_array($name, $selected)) {
                $checked = "checked = 'checked'";
            }
            $color[] = $name;
            $id = "$val[propri_id]";
            $temp .= "<label class='grow'>
<input type='checkbox' name='colorCheckbox' value='$name' $checked>
<span class='checkColorDiv ' style='background:$name;'></span>
</label>
";
        }
        $temp .= "</div>
</div><!--.pColorSearch end-->";
        return $temp;

    }

    public function getDistinctSize()
    {
        if ($this->functions->developer_setting('product_Scale') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) as name , prosiz_id FROM `product_size` GROUP BY prosiz_name ORDER BY prosiz_id ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "<div class='padding-0 pSizeSearch'>
<div class='heading'>
<span>" . $_e['Size'] . "</span>
</div>
<div class='container-fluid sizeCheckBoxes  text-center'> ";
        $size = array();
        @$selected = $_GET['size'];
        if ($selected == "" || $selected == false) {
            $selected = array();
        } else {
            $selected = explode(",", $selected);
        }
        foreach ($data as $val) {
            $name = $val['name'];
            if (in_array($name, $size)) {
                continue;
            }
            $checked = "";
            if (in_array($name, $selected)) {
                $checked = "checked = 'checked'";
            }
            $size[] = $name;
            $id = "$val[prosiz_id]";
            $temp .= "
<label class='grow'>
<input type='checkbox' name='colorSizeBox' value='$name' $checked>
<span class='checkSizeDiv' style=''> $name </span>
</label>
";
        }
        $temp .= "</div>
</div><!--.pSizeSearch end-->";
        return $temp;

    }

    public function getCategoryName($catId)
    {
        $sql = "SELECT * FROM tree_data WHERE id = '$catId'";
        $data = $this->dbF->getRow($sql);
        $webLang = currentWebLanguage();
        if ($this->dbF->rowCount > 0) {
            $name = $data['nm'];
            $array = array($name => '');
            global $_e;
            $_e = $this->dbF->hardWordsMulti($array, $webLang, 'webCategory');
            $name = $_e[$name];
            return $name;
        } else {
            return false;
        }
    }

    public function getCategoryNameNew($catId)
    {
        $sql = "SELECT * FROM `categories` WHERE id = '$catId'";
        $data = $this->dbF->getRow($sql);
        $webLang = currentWebLanguage();
        if ($this->dbF->rowCount > 0) {
            $name = $data['name'];
            $array = array($name => '');
            global $_e;
            $_e = $this->dbF->hardWordsMulti($array, $webLang, 'webCategory');
            $name = $_e[$name];
            return $name;
        } else {
            return false;
        }
    }

    public function getCategoryId($catName)
    {
        $sql = "SELECT * FROM tree_data WHERE nm = '$catName'";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0) {
            $id = $data['id'];
            return $id;
        } else {
            return false;
        }
    }

    public function getPrinceRange()
    {
        $sql = "SELECT MIN(propri_price) as min,MAX(propri_price) as max FROM `product_price`";
        $data = $this->dbF->getRow($sql);
        $array = array("min" => 0, "max" => 0);
        if ($this->dbF->rowCount > 0) {
            $array['min'] = $data['min'];
            $array['max'] = $data['max'];
        }
        $array['cMin'] = $array['min'];
        if (isset($_GET['pMin'])) {
            $array['cMin'] = $_GET['pMin'];
        }
        $array['cMax'] = $array['max'];
        if (isset($_GET['pMax'])) {
            $array['cMax'] = $_GET['pMax'];
        }
        return $array;
    }

    public function navigation()
    {
        $totalP = floatval($this->totalProductFrNav);
        $limit = floatval($this->productLimit);
        $noOfPage = ceil($totalP / $limit);


        $current = isset($_GET['page']) ? intval($_GET['page']) : 0;
        $parameters = '';

        $hasPara = false;
        foreach ($_GET as $key => $val) {
            if ($key == 'page') {
                continue;
            }
            if ($hasPara) {
                $parameters .= "?$key=$val";
            } else {
                $parameters .= "&$key=$val";
            }
            $hasPara = true;
        }


        $start = 0;
        $end = $noOfPage;
        $i = 1;
        $pagi = '';


        //Previous Link
        if ($i == 1 && $current > $i) {
            $page = "page=" . ($current - 1);
            $link = WEB_URL . "/products?$parameters$page";
            $pagi .= '<li>
<a href="' . $link . '" aria-label="Previous">
<span aria-hidden="true">&laquo;</span>
</a>
</li>';
        }

        //If page 6 then show ... before pages
        if ($current > 5 && $noOfPage > $current + 3) {
            $start = $current - 3;
            $pagi .= '<li class=""><a href="">...</a></li>';
        }

        //If page 6 then End on where show ... before pages
        if ($current > 5 && $noOfPage > $current + 3) {
            $end = $current + 3;
        }


        $more = false;
        for ($i = $start; $i <= $end; $i++) {
            $active = '';
            if ($i == $current) {
                $active = 'active';
            }

            if ($hasPara) {
                $parameters .= "&";
            }
            $page = "page=" . $i;
            $link = WEB_URL . "/products?$parameters$page";

            $pagi .= '<li class="' . $active . '"><a href="' . $link . '" data-page="' . $link . '" title="' . $i . ' - Sharkspeed">' . $i . '</a></li>';
        }

        for ($i = $noOfPage - 3; $i <= $noOfPage; $i++) {
            $active = '';
            if ($i == $current) {
                $active = 'active';
            }

            if ($hasPara) {
                $parameters .= "&";
            }
            $page = "page=" . $i;
            $link = WEB_URL . "/products?$parameters$page";

            $pagi .= '<li class="' . $active . '"><a href="' . $link . '" data-page="' . $link . '" title="' . $i . ' - Sharkspeed">' . $i . '</a></li>';
        }


        if ($i == $noOfPage && $i > $current) {
            $parameters .= "page=" . ($current + 1);
            $link = WEB_URL . "/products?$parameters";
            $pagi .= '<li>
<a href="' . $link . '" data-page="' . $link . '" title="' . $i . ' - Sharkspeed" aria-label="Next">
<span aria-hidden="true">&raquo;</span>
</a>
</li>';
        }


        ?>
        <div class="clearfix"></div>


        <nav>
            <ul class="pagination">
                <?php echo $pagi; ?>
            </ul>
        </nav>

    <?php }

    public function productSclaeColorReport($pId)
    {
        //get info is product has sclae or colors
        $sql = "SELECT (SELECT count(propri_id) FROM product_color  WHERE `proclr_prodet_id` = '$pId' GROUP BY proclr_prodet_id) as color,
(SELECT count(prosiz_id) FROM product_size  WHERE `prosiz_prodet_id` = '$pId' GROUP BY prosiz_prodet_id) as size ";
        $data = $this->dbF->getRow($sql);

        $scale = false;
        $color = false;

        if (!empty($data['size'])) {
            $scale = true;
        }
        if (!empty($data['color'])) {
            $color = true;
        }


        //make report
        $report = "";
        if ($scale) {
            $report .= "<input type='hidden' id='hasScaleInventory_$pId' value='1' />";
        } else {
            $report .= "<input type='hidden' id='hasScaleInventory_$pId' value='0' />";
        }

        if ($color) {
            $report .= "<input type='hidden' id='hasColorInventory_$pId' value='1' />";
        } else {
            $report .= "<input type='hidden' id='hasColorInventory_$pId' value='0' />";
        }

        $array = array();
        $array['report'] = $report;
        $array['scale'] = $scale;
        $array['color'] = $color;
        return $array;
    }

    public function inventoryReport($pId)
    {
        /*
        Info Of is size & color insert in product either it is out of stock
        Or either it is out of store,
        */

        $sql = "SELECT `qty_product_scale`,`qty_product_color` FROM product_inventory  WHERE `qty_product_id` = '$pId'";


        $data = $this->dbF->getRows($sql);
        $scale = false;
        $color = false;
        foreach ($data as $val) {
            if ($val['qty_product_scale'] > 0) {
                $scale = true;
            }
            if ($val['qty_product_color'] > 0) {
                $color = true;
            }
        }

        //make report
        $report = "";
        if ($scale) {
            $report .= "<input type='hidden' id='hasScaleInventory_$pId' value='1' />";
        } else {
            $report .= "<input type='hidden' id='hasScaleInventory_$pId' value='0' />";
        }

        if ($color) {
            $report .= "<input type='hidden' id='hasColorInventory_$pId' value='1' />";
        } else {
            $report .= "<input type='hidden' id='hasColorInventory_$pId' value='0' />";
        }

        $array = array();
        $array['report'] = $report;
        $array['scale'] = $scale;
        $array['color'] = $color;
        return $array;
    }

    public function getColorsDiv($pId, $storeId = false, $pPrice = false, $currencyId = false, $currencySymbol = false, $discountP = false, $hasScale = false)
    {
        //Collect Data
        if ($currencyId == false || $currencyId == "") {
            $currencyId = $this->currentCurrencyId();
        }

        if ($storeId == false || $storeId == "") {
            $storeId = $this->getStoreId();
        }
        if ($currencySymbol == false || $currencySymbol == "") {
            $currencySymbol = $this->currentCurrencySymbol();
        }

        if ($pPrice == false || $pPrice == "") {
            $pPriceData = $this->productF->productPrice($pId, $currencyId);
            //$pPriceData Return , currency id,international shipping, price, id,
            $priceDefault = $pPriceData['propri_price'];
            $pPrice = $priceDefault;
        } else {
            $priceDefault = $pPrice;
        }

        if ($discountP == false || $discountP == "") {
            $discount = $this->productF->productDiscount($pId, $currencyId);
            @$discountFormat = $discount['discountFormat'];
            @$discountP = $discount['discount'];
            $discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
            $newPrice = $pPrice - $discountPrice;
        }

        //Make Color Divs,,
        $select_color = '';
        $colorDiv = '';
        $colorImg = '';

        $firstColorId = 0;
        $firstColor = true;
        //just color sql, to show product colors,,,
        $pColorData = $this->productF->colorSQL($pId);


        foreach ($pColorData as $val) {
            $colorName = $val['proclr_name'];
            $colorName = str_replace('#', "", $colorName);
            $colorName = str_replace(' ', "", $colorName);
            $colorId = $val['propri_id'];

            $scalesInColor = '';
            $colorInventory = '';
            if ($hasScale) {
                //size in color... get Sizes That are in this Color


                $sql = "SELECT `qty_product_scale` FROM `product_inventory` WHERE qty_store_id = '$storeId'
AND qty_product_id = '$pId'
AND qty_product_color = '$colorId'
AND qty_item > 0";
                $colorSizeData = $this->dbF->getRows($sql);

                foreach ($colorSizeData as $val2) {
                    $scaleIdInColor = $val2['qty_product_scale'];
                    $scalesInColor .= ".sizeId_$scaleIdInColor,";
                }
                $scalesInColor = trim($scalesInColor, ",");
                //size in color end

            } else {
                //if not scale then give inventory detail direct in color, else it will load ajax inventory
//size in color... get Sizes That are in this Color
                $sql = "SELECT `qty_item` FROM `product_inventory` WHERE qty_store_id = '$storeId'
AND qty_product_id = '$pId'
AND qty_product_color = '$colorId'
AND qty_item > 0";
                $colorSizeData = $this->dbF->getRow($sql);
                $colorInventory = $colorSizeData['qty_item'];
                $colorInventory = ' ' . $colorInventory . '';
                $colorInventory = trim($colorInventory);
            }

            if ($scalesInColor == "" && $colorInventory == "" && $this->functions->developer_setting('product_check_stock') == '1') {
                // if color inventory and scale invoentory not found

            }

            $colorData = $this->productF->colorPrice($colorId, $currencyId, $pId);
            $colorPrice = $colorData['proclr_price'];

            //First Color Id get, For Auto Select or Call Ajax on load
            if ($firstColor) {
                $firstColor = false;
                $firstColorId = $colorId;
                $checked = "";
            } else {
                $checked = "";
            }
            $onclick = '';
            if ($this->comparePage == false) {
                $onclick = 'onclick="productColorPriceUpdate(' . $pId . ',' . $colorId . ');';
            }

            $no_inv_class = '';
            if (empty($colorInventory)) {
                $no_inv_class = 'no_stock no_color_stock';
                $colorInventory = 0;
            }



            $colorName_uppercase = strtoupper($colorName);
            $colorDiv .= '

<li><input type="radio" name="colorSelect_' . $pId . '" id="' . $colorName . '_' . $pId . '"
data-colorInv="' . $colorInventory . '" data-price="' . $colorPrice . '" ' . $checked . ' data-id="' . $colorId . '" value="' . $colorName . '"
class="productRadioHidden colorSelect_' . $pId . ' colorId_' . $colorId . '" />
<a><div class="bgColorActive' . $no_inv_class . ' colorId_' . $colorId . ' colorDiv_' . $pId . '"
data-colorInv="' . $colorInventory . '" data-price="' . $colorPrice . '" data-scales="' . $scalesInColor . '" data-id="' . $colorId . '" data-name="' . $colorName . '"
style="display:inline-block" >
<label for="' . $colorName . '_' . $pId . '">
<div class="colors_in_divs"
data-price="' . $colorPrice . '" data-scales="' . $scalesInColor . '" data-id="' . $colorId . '" data-name="' . $colorName . '"
style=""
' . $onclick . '" style="display:block">
</div>
<div class = "color_name"> ' . $colorName_uppercase . ' </div>
</label>
</div> 
</a>
</li>

';

        }

        if ($this->comparePage == false && $pColorData != array()) {
            $colorDiv .= "<script>
$(document).ready(function(){
productColorPriceUpdate('$pId','$firstColorId');
//productPriceUpdate('$pId'); use for scale price update
});
</script>";
        }

        return $colorDiv;
    }

    public function get_product_stock($pId)
    {
        $store_id = $this->getStoreId();
        $sql = "SELECT * FROM `product_inventory` WHERE
qty_store_id = '$storeId'
AND qty_product_id = '$pId'
AND qty_item > 0";
        $qtyData = $this->dbF->getRows($sql);
    }

    public function getScalesDivNew($pId, $cartId, $hasColor, $storeId = false, $currencyId = false, $currencySymbol = false)
    {
        $size = '';
        $firstScale = true;
        if ($hasColor == false) {
            //Collect Data
            if ($currencyId == false || $currencyId == "") {
                $currencyId = $this->currentCurrencyId();
            }

            if ($storeId == false || $storeId == "") {
                $storeId = $this->getStoreId();
            }
            if ($currencySymbol == false || $currencySymbol == "") {
                $currencySymbol = $this->currentCurrencySymbol();
            }

            //Get Color Inventory
//size in color... get Sizes That are in this Color
            $sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
AND qty_product_id = '$pId'
AND qty_product_color = '0'
AND qty_item > 0";


            if ($this->comparePage == false) {
                $size .= "<script>
$(document).ready(function(){
try {
productPriceUpdateNew('$pId','$cartId');
} catch(e){

}
});
</script>";
            }

        }

        $sizeData = $this->productF->scaleWithInventory($pId, $currencyId, $storeId, $hasColor);



        foreach ($sizeData as $val) {
            $class = '';
            if (empty($val['hasInventory']) && $this->functions->developer_setting('product_check_stock') == '1') {
                //check if inventory not found, then continue, if inventory unlimit then condition false
                if ($this->comparePage == false) {
                    //this work only when product has only size, no color
// continue; //dont show size if its not has any stock
                }

                if ($hasColor == false) {
                    $class = 'no_stock';
                }


            }

            $sizeName = $val['prosiz_name'];
            $sizePrice = $val['prosiz_price'];
            $sizeId = $val['prosiz_id'];
            @$sizeInventoryId = $val['inventoryScaleId'];
            if (empty($sizeInventoryId)) {
                @$sizeInventoryId = $sizeId; //i hope both are same.. 24 / 6 /2015
            }
            if ($firstScale) {
                $checked = "";
                $firstScale = false;
            } else {
                $checked = "";
            }

            $onclick = '';
            if ($this->comparePage == false) {
                $onclick = 'onclick="productPriceUpdateNew(' . $pId . ',' . $cartId . ');';
            }



            $size .= $this->getScaleDivFormat($pId, $sizeName, $sizeId, $sizePrice, $sizeInventoryId, $checked, $onclick, $class);


        }
        return $size;
    }

    public function getScalesDiv($pId, $hasColor, $storeId = false, $currencyId = false, $currencySymbol = false, $tabId = "")
    {
        $size = '';
        $firstScale = true;
        if ($hasColor == false) {
            //Collect Data
            if ($currencyId == false || $currencyId == "") {
                $currencyId = $this->currentCurrencyId();
            }

            if ($storeId == false || $storeId == "") {
                $storeId = $this->getStoreId();
            }
            if ($currencySymbol == false || $currencySymbol == "") {
                $currencySymbol = $this->currentCurrencySymbol();
            }

            //Get Color Inventory
//size in color... get Sizes That are in this Color
            $sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
AND qty_product_id = '$pId'
AND qty_product_color = '0'
AND qty_item > 0";

            if ($this->comparePage == false) {
                $size .= "<script>
$(document).ready(function(){
try {
productPriceUpdate('$pId');
} catch(e){

}
});
</script>";
            }

        }

        $sizeData = $this->productF->scaleWithInventory($pId, $currencyId, $storeId, $hasColor);
        foreach ($sizeData as $val) {
            $class = '';
            if (empty($val['hasInventory']) && $this->functions->developer_setting('product_check_stock') == '1') {
                //check if inventory not found, then continue, if inventory unlimit then condition false
                if ($this->comparePage == false) {
                    //this work only when product has only size, no color
// continue; //dont show size if its not has any stock
                }

                if ($hasColor == false) {
                    $class = 'no_stock';
                }


            }

            $sizeName = $val['prosiz_name'];
            $sizePrice = $val['prosiz_price'];
            @$sizeId = $val['prosiz_oid'];

            @$sizeInventoryId = $val['inventoryScaleId'];
            if (empty($sizeInventoryId)) {
                @$sizeInventoryId = $sizeId; //i hope both are same.. 24 / 6 /2015
                continue;
            }
            if ($firstScale) {
                $checked = "";
                $firstScale = false;
            } else {
                $checked = "";
            }

            $onclick = '';
            if ($this->comparePage == false) {
                $onclick = 'onclick="productPriceUpdate(' . $pId . ');';
            }



            $size .= $this->getScaleDivFormat($pId, $sizeName, $sizeId, $sizePrice, $sizeInventoryId, $checked, $onclick, $class, $tabId);


        }
        return $size;
    }

    /*Page: Product Detail
    Select Size Option Format Can be changed from Here
    */
    public function getScaleDivFormat($pId, $sizeName, $sizeId, $sizePrice, $sizeInventoryId, $checked, $onclick, $class = '', $tabId = '')
    {
        $sizeSelect_ = $tabId == "" ? 'sizeSelect_' . $pId : 'sizeSelect_' . $tabId . '_' . $pId;
        $size = '<li><input type="radio" name="sizeSelect_' . $pId . '" id="' . $sizeName . '_' . $pId . '"
data-price="' . $sizePrice . '"  ' . $checked . '  data-id="' . $sizeInventoryId . '" value="' . $sizeName . '"
class="productRadioHidden ' . $sizeSelect_ . ' sizeId2_' . $sizeId . '"/>

<a>    <div class="bgSizeActive size_in_divs sizeId_' . $sizeInventoryId . ' sizeDiv_' . $pId . '  ' . $class . '  " ' . $onclick . '" data-id="' . $pId . '">
    
<label for="' . $sizeName . '_' . $pId . '" style="display: block; margin: 0px !important; border-radius: 0px !important; text-align: left; width: 100%; padding:5px ">   <div class = "color_name size">      ' . $sizeName . '  </div>
</label>
</div></a>
</li> ';
        return $size;
    }

    public function productViewCount($pId)
    {
        if (!isset($_SESSION['webUser']['productView'][$pId])) {
            $sql = "UPDATE proudct_detail SET `view` = `view` + ? WHERE prodet_id = ?";
            $this->dbF->setRow($sql, array(1, $pId));
            $_SESSION['webUser']['productView'][$pId] = 'view';
        }
    }

    public function productDealViewCount($pId)
    {
        if (!isset($_SESSION['webUser']['dealView'][$pId])) {
            $sql = "UPDATE product_deal SET `view` = `view` + ? WHERE id = ?";
            $this->dbF->setRow($sql, array(1, $pId));
            $_SESSION['webUser']['dealView'][$pId] = 'view';
        }
    }

    public function productMetaSeo($array)
    {
        global $seo;
        if (isset($array['title'])) {
            if ($seo['title'] == '' || $seo['reWriteTitle'] == '0') {
                $seo['title'] = strip_tags($array['title']);
            }
        }
        if (isset($array['description'])) {
            if ($seo['description'] == '' || $seo['default'] == '1') {
                $seo['description'] = strip_tags($array['description']);
            }
        }
        if (isset($array['image'])) {
            $seo['image'] = strip_tags($array['image']);
        }
        if (isset($array['price'])) {
            $seo['price'] = strip_tags($array['price']);
        }
        if (isset($array['currency'])) {
            $seo['currency'] = strip_tags($array['currency']);
        }
        if (isset($array['shipping'])) {
            $seo['shipping'] = strip_tags($array['shipping']);
        }

    }

    public function referToFriend($pId)
    {
        /*
         * where this open on click
         * use: data-toggle="modal" data-target="#referToFriend"
         * */
        global $_e;
        $token = $this->functions->setFormToken('referToFriend', false);
        $body = '<div class="container-fluid">
<form  action="" method="post" class="form-horizontal"> ' . $token . '
<input type="hidden" name="pId" value="' . $pId . '" />
<div class="form-group">
<div class="container-fluid">
<div class="sales_text">' . $_e['Refer to a Friend Description'] . '</div>
<label class="col-sm-2 control-label" for="referToFriendInput">' . $_e['Email'] . '</label>
<div class="col-sm-10">
<input type="email" required name="email" class="form-control" id="referToFriendInput" placeholder="">
</div>
</div>
</div>
<button type="submit" name="referToFriend" class="btn btn-default">' . $_e['Refer To Friend'] . '</button>
</form>
</div>';
        $temp = $this->functions->blankModal($_e["Refer To Friend"], 'referToFriend', $body, $_e['Close']);
        return $temp;
    }

    public function productSubscribeOnSale($pId)
    {
        /*
         * where this open on click
         * use: data-toggle="modal" data-target="#ProductSubscribe"
         * */
        global $_e;
        $token = $this->functions->setFormToken('ProductSubscribe', false);
        $body = '<div class="container-fluid">

<form  action="" method="post" class="form-horizontal"> ' . $token . '
<input type="hidden" name="insert[p_id]" value="' . $pId . '" />
<input type="hidden" name="insert[type]" value="sale" />
<div class="form-group">
<div class="container-fluid">
<div class="sales_text">' . $_e['Sale Trigger Form'] . '</div>
<label for="productSubscribeOnSale" class="col-sm-2 control-label">' . $_e['Email'] . '</label>
<div class="col-sm-10">
<input type="email" required name="insert[email]" class="form-control" id="productSubscribeOnSale" placeholder="">
</div>
</div>
</div>
<button type="submit" name="ProductSubscribe" class="btn themeButton">' . $_e['Subscribe'] . '</button>
</form>
</div>';
        $temp = $this->functions->blankModal($_e["Subscribe"], 'ProductSubscribe', $body, $_e['Close']);
        return $temp;
    }

    public function productSubscribeOnSaleSubmit()
    {
        global $_e;
        if (isset($_POST['insert']) && isset($_POST['ProductSubscribe'])) {
            if (!$this->functions->getFormToken('ProductSubscribe')) {
                return false;
            }

            $sql = "SELECT * FROM product_subscribe WHERE `p_id` = ? AND `type`=?  AND `email`=? ";
            $this->dbF->getRow($sql, $_POST['insert'], true, true);
            $row_exists = $return = false;
            if (!$this->dbF->rowCount) {
                $return = $this->functions->formInsert('product_subscribe', $_POST['insert']);
            } else {
                $row_exists = TRUE;
            }

            if ($return) {
                return $this->functions->jAlertifyAlert($_e["Subscribe Successfully"], false);
            } else {
                $hardword = ($row_exists == TRUE) ? $_e['You are already subscribed on this sale offer'] : $_e['Subscribe Fail'];
                return $this->functions->jAlertifyAlert($hardword, false);
            }
        }
        return "";
    }

    public function checkout_popup_stock()
    {
        /*
         * where this open on click
         * use: data-toggle="modal" data-target="#CheckoutSubscription"
         * */
        global $_e;
        $token = $this->functions->setFormToken('CheckoutSubscription', false);
        $body = '<div class="container-fluid">

<form  action="" method="post" id="denied_cart" class="form-horizontal"> ' . $token . '

<input type="hidden" name="den_invId" class="out_of_cart invId" value="0" />




<div class="form-group">
<div class="container-fluid">
<label for="den_name" class="col-sm-2 control-label">' . $_e['Name'] . '</label>
<div class="col-sm-10">
<input type="text" required name="den_name" class="form-control" id="den_name" placeholder="">
</div>
</div>
</div>


<div class="form-group">
<div class="container-fluid">
<label for="invEmail" class="col-sm-2 control-label">' . $_e['Email'] . '</label>
<div class="col-sm-10">
<input type="email" required name="den_email" class="form-control" id="invEmail" placeholder="">
</div>
</div>
</div>


<div class="form-group">
<div class="container-fluid">
<label for="den_phone" class="col-sm-2 control-label">' . $this->dbF->hardWords('Phone', false) . '</label>
<div class="col-sm-10">
<input type="text" required name="den_phone" class="form-control" id="den_phone" placeholder="">
</div>
</div>
</div>



<button type="submit" name="CheckoutSubscription" id="btn_cart" class="btn themeButton">' . $_e['Subscribe'] . '</button>
</form>
</div>';
        $temp = $this->functions->blankModal($_e['Fill The Data'], 'CheckoutSubscription', $body);
        return $temp;
    }

    public function subscribed_on_stock_availability($pId)
    {
        /*
         * where this open on click
         * use: data-toggle="modal" data-target="#StockSubscription"
         * */
        global $_e;
        $token = $this->functions->setFormToken('StockSubscription', false);
        $body = '<div class="container-fluid">

<form  action="" method="post" class="form-horizontal"> ' . $token . '
<input type="hidden" name="insert[p_id]" value="' . $pId . '" />
<input type="hidden" name="insert[color_id]" class="out_of_stock color_id" value="0" />
<input type="hidden" name="insert[scale_id]" class="out_of_stock scale_id" value="0" />
<input type="hidden" name="insert[store_id]" class="out_of_stock store_id" value="0" />
<input type="hidden" name="insert[type]" value="stock">
<div class="form-group">
<div class="container-fluid">
<div class="sales_text">' . $_e['Stock Trigger Form'] . '</div>
<label for="productSubscribeOnStock" class="col-sm-2 control-label">' . $_e['Email'] . '</label>
<div class="col-sm-10">
<input type="email" required name="insert[email]" class="form-control" id="productSubscribeOnStock" placeholder="">
</div>
</div>
</div>
<button type="submit" name="StockSubscription" class="btn themeButton">' . $_e['Subscribe'] . '</button>
</form>
</div>';
        $temp = $this->functions->blankModal($_e['Subscription for stock availability'], 'StockSubscription', $body, $_e['Close']);
        return $temp;
    }

    public function subscribed_on_stock_availability_submit()
    {
        global $_e;
        if (isset($_POST['insert']) && isset($_POST['StockSubscription'])) {
            if (!$this->functions->getFormToken('StockSubscription')) {
                return false;
            }

            $sql = "SELECT * FROM product_subscribe WHERE `p_id` = ? AND `color_id`=? AND `scale_id`=? AND `store_id`=?  AND `type`=?  AND `email`=? ";
            $this->dbF->getRow($sql, $_POST['insert'], true, true);
            $row_exists = $return = false;
            if (!$this->dbF->rowCount) {
                $return = $this->functions->formInsert('product_subscribe', $_POST['insert']);
            } else {
                $row_exists = TRUE;
            }

            if ($return) {
                return $this->functions->jAlertifyAlert($_e['Subscribe Successfully'], false);
            } else {
                $hardword = ($row_exists == TRUE) ? $_e['You are already subscribed for this product'] : $_e['Subscribe Fail'];
                return $this->functions->jAlertifyAlert($hardword, false);
            }
        }
        return "";
    }

    public function referToFriendSubmit()
    {
        global $_e;
        if (isset($_POST['referToFriend']) && isset($_POST['email'])) {
            if (!$this->functions->getFormToken('referToFriend')) {
                return false;
            }

            @$pId = $_POST['pId'];
            $link = WEB_URL . "/detail.php?pId=$pId";
            if (isset($_GET['pSlug'])) {
                $pId = $_GET['pSlug'];
                $link = WEB_URL . "/" . $this->db->productDetail . $pId;
            }

            @$email = $_POST['email'];
            $mailArray = array('link' => $link);
            $return = $this->functions->send_mail($email, '', '', 'ReferToFriend', '', $mailArray);

            if ($return) {
                return $this->functions->jAlertifyAlert($_e["Email Send Successfully"], false);
            } else {
                return $this->functions->jAlertifyAlert($_e["Email Send Fail"], false);
            }
        }
        return "";
    }

    public function measurementArray($data, $filedName, $settingName, $returnId = false)
    {
        foreach ($data as $key => $val) {
            if ($val['fieldName'] == $filedName && $val['setting_name'] == $settingName) {
                if ($returnId) {
                    return $val['id'];
                }
                return $val['setting_value'];
            }
        }

        return "";
    }

    public function customSizeForm($pId, $customSizeType)
    {
        global $_e;
        $sql = "SELECT * FROM p_custom WHERE id = '$customSizeType'";
        $data = $this->dbF->getRow($sql);

        if (!$this->dbF->rowCount) {
            return false;
        }

        $fields = $data['custom_fields'];
        $fields = str_replace(",,", "", $fields);
        $fields = trim($fields, ",");
        $fields = explode(",", $fields);

        $sql = "SELECT * FROM p_custom_setting WHERE c_id = '$customSizeType'";
        $dataFields = $this->dbF->getRows($sql);

        $token = $this->functions->setFormToken('measurementFields', false);
        $token .= '<input type="hidden" name="editId" value="' . $pId . '"/>';

        $form = '';
        $form_fields = array();

        $form_fields[] = array(
            'name' => "customPId",
            'type' => 'hidden',
            'value' => "$pId",
        );

        $form_fields[] = array(
            'name' => "custom_id",
            'type' => 'hidden',
            'value' => "$data[id]",
        );

        //Price field, For update if needed
        $form_fields[] = array(
            'name' => "customStore_$pId",
            'type' => 'hidden',
            'value' => $this->getStoreId(),
            'class' => "customStore_$pId",
        );

        //Color field, For update if needed
        $form_fields[] = array(
            'name' => "customColor_$pId",
            'type' => 'hidden',
            'value' => '0',
            'class' => "customColor_$pId",
        );


        foreach ($fields as $key => $val) {
            $required = translateFromSerialize($this->measurementArray($dataFields, $val, 'required'));
            $valTemp = translateFromSerialize($this->measurementArray($dataFields, $val, 'name'));
            $form_fields[] = array(
                'label' => $valTemp,
                'name' => "custom[$val]",
                'type' => 'hidden',
                'class' => 'form-control',
                'value' => "",
                'required' => "$required"
            );
        }

        //edit link
        if ($data['allowSubmitLater'] == '1') {
            $form_fields[] = array(
                'name' => "customSubmit_later_$pId",
                'type' => 'check',
                'id' => 'custom_check',
                'class' => 'btn btn-danger',
                'value' => "1",
                'option' => array(_u($_e["I Accept"])),
                'format' => "<label class='checkbox-inline'>{{form}} {{option}}</label>",
            );
        }

        //Submit Button
        $form_fields[] = array(
            'name' => "submit",
            'id' => 'custom_submit',
            'type' => 'submit',
            'class' => 'btn themeButtonn ',
            'value' => _u($_e['Add To Cart']),
        );

        //Make <form, call first or any where then make array index key is 'form',
//now mange more clear, just make format here... no thisFormat work here.
        $form_fields['form'] = array(
            'name' => "form",
            'type' => 'form',
            'class' => "formClass",
            'id' => "customForm_$pId",
            'data' => 'onsubmit="return customFormSubmit(this,' . $pId . ')"',
            'action' => '',
            'disabled' => '',
            'method' => 'post',
            'format' => '<div class="form-horizontal">{{form}}</div>'
        );
        $format = '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">{{label}}</label>
<div class="col-sm-12  col-md-12 col-xs-12 sub_mob_btn">
{{form}}
</div>
</div>';
        $form .= $this->functions->print_form($form_fields, $format, false);

        return $form;


    }

    public function customSizeArrayFilter($data, $country_id)
    {
        foreach ($data as $key => $val) {
            if ($val['currencyId'] == $country_id) {
                return $val['price'];
            }
        }
        return "";
    }

    public function couponOfferOnVisit()
    {
        if (getUserSession('couponOfferMail') == false) {

            if ($this->functions->developer_setting('couponOfferMail') == '1') {
                $couponOfferStatus = $this->functions->ibms_setting('couponOfferEmail');

                if ($couponOfferStatus == '1') {
                    setUserSession('couponOfferMail');
                    /*
                     * where this open on click
                     * use: data-toggle="modal" data-target="#ProductSubscribe"
                     * */
                    global $_e;
                    $token = $this->functions->setFormToken('ProductCouponOffer', false);
                    $body = '<div class="container-fluid pop_up">
<h3 class="my_alert">' . $_e['Enter your email and get Latest Coupons Code'] . '</h3>
<form  action="" method="post" class="form-horizontal"> ' . $token . '
<div class="form-group">
<div class="pop_subscribe">
<label for="productSubscribeOnSale" class="sub_label">' . $_e['Email'] . '</label>
<div class="pop_email">
<input type="email" required name="email" class="form-control" placeholder="">
</div>
</div>
</div>
<button type="submit" name="CouponOfferSubmit" class="btn themeButton pop_btn">' . $_e['Send Coupon Code'] . '</button>
</form>
</div>';
                    $temp = $this->functions->blankModal($_e["CouponOffer"], 'ProductCouponOffer', $body, $_e['Close']);
                    $temp .= "<script>
$(document).ready(function(){
setTimeout(function(){
$('#ProductCouponOffer').modal('show');
},5000);
});
</script>";
                    return $temp;
                }
            }

        }
        $this->couponOfferOnVisitSubmit();
    }

    private function couponOfferOnVisitSubmit()
    {
        if (isset($_POST['CouponOfferSubmit'])) {
            if (!$this->functions->getFormToken('ProductCouponOffer')) {
                return false;
            }

            $email = $_POST['email'];
            $this->functions->send_mail($email, '', '', 'couponOfferEmail', '');
        }
    }

    public function productReturnOrDefectForm($type = 'return')
    {
        global $_e;
        //$data varibale was using in admin, on edit -> logs/
        $userId = $this->webClass->webUserId();
        $form_fields = array();
        $token = $this->functions->setFormToken($type . 'Form', false);

        $data = array();

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => $token,
        );

        $form_fields[] = array(
            'type' => 'hidden',
            'name' => 'insert[userId]',
            'value' => $userId,
        );

        $form_fields[] = array(
            'type' => 'hidden',
            'name' => 'insert[type]',
            'value' => $type,
        );

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => '<div class="form-group">
<label class="col-sm-5 control-label">' . $_e['User Info'] . '</label>
</div>',
        );

        $form_fields[] = array(
            'label' => $_e['User Name'],
            'name' => 'insert[name]',
            'required' => "true",
            "value" => getUserSession("name"),
            'type' => 'text',
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'label' => $_e['Email'],
            'name' => 'insert[email]',
            'required' => "true",
            'type' => 'email',
            "value" => getUserSession("email"),
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'label' => $_e['Contact'],
            'name' => 'insert[phone]',
            'required' => "true",
            'type' => 'text',
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => "<hr>",
        );

        if ($userId > '0') {
            $sql = "SELECT * FROM `order_invoice` WHERE orderUser = '$userId' AND orderStatus = 'process' ORDER BY order_invoice_pk DESC";
            $invoice = $this->dbF->getRows($sql);
            $invoiceKey = $this->functions->ibms_setting('invoice_key_start_with');
            $array = array("" => "----");
            foreach ($invoice as $val) {
                $array[$val['order_invoice_pk']] = "$invoiceKey $val[order_invoice_pk] (Date : $val[dateTime])";
            }
            $form_fields[] = array(
                'label' => $this->dbF->hardWords('ORDER CODE', false),
                'name' => 'insert[orderId]',
                'required' => "true",
                'array' => $array,
                'type' => 'select',
                'class' => 'form-control',
            );
        } else {
            $form_fields[] = array(
                'label' => $_e['Order Code'],
                'name' => 'insert[orderId]',
                'required' => "true",
                "value" => @$data['orderId'],
                'type' => 'text',
                'class' => 'form-control',
            );
        }

        $form_fields[] = array(
            'label' => $_e['Product Name'],
            'name' => 'insert[products]',
            'required' => "true",
            "value" => @$data['products'],
            'type' => 'textarea',
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'label' => $_e['Number Which Claims In'],
            'name' => 'insert[claimNo]',
            "value" => @$data['claimNo'],
            'type' => 'text',
            'class' => 'form-control',
        );

        $getNewT = $_e['Get New Item'];
        $moneybackT = $_e['Get Money Back'];
        $form_fields[] = array(
            'label' => $_e['Want to switch to another product or get your money back?'],
            'name' => 'insert[switchProduct]',
            'required' => "true",
            "value" => "1,2",
            "option" => "$getNewT,$moneybackT",
            "select" => @$data['switchProduct'],
            'type' => 'radio',
            'format' => '<label class="radio-inline">{{form}} {{option}}</label>',
        );

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => '<div class="form-group">
<label class="col-sm-5 control-label">' . $_e['Buy Back'] . '</label>
</div>',
        );


        $form_fields[] = array(
            'label' => $_e['Name of your bank'],
            'name' => 'insert[bankName]',
            "value" => @$data['bankName'],
            'type' => 'text',
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'label' => $_e['sortCode'],
            'name' => 'insert[sortCode]',
            'type' => 'text',
            "value" => @$data['sortCode'],
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'label' => $_e['Account Number'],
            'name' => 'insert[accountNo]',
            'type' => 'text',
            "value" => @$data['accountNo'],
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => '<div class="form-group">
<label class="col-sm-5 control-label">' . $_e['When Replacing'] . '</label>
</div>',
        );

        $form_fields[] = array(
            'label' => $_e['I want to change to'],
            'name' => 'insert[changeTo]',
            'type' => 'text',
            "value" => @$data['changeTo'],
            'class' => 'form-control',
        );

        $form_fields[] = array(
            'label' => $_e['Message'],
            'name' => 'insert[message]',
            'type' => 'textarea',
            "value" => @$data['message'],
            'class' => 'form-control',
        );

        if ($type == 'defect') {
            $form_fields[] = array(
                'label' => $_e['Defect Image'],
                'type' => 'none',
                'format' => '<input type="file" name="image[]" >
<input type="file" name="image[]" >
<input type="file" name="image[]" >',
            );
        }

        $form_fields[] = array(
            "name" => 'submit',
            'class' => 'btn btn-success defaultSpecialButton',
            'type' => 'submit',
            'id' => 'signup_btn',
            "value" => $_e['Submit'],
        );

        $form_fields['form'] = array(
            'type' => 'form',
            'class' => "form-horizontal",
            'method' => 'post',
            'format' => '{{form}}'
        );

        $format = '<div class="form-group">
<label class="col-sm-5 control-label">{{label}}</label>
<div class="col-sm-7">
{{form}}
</div>
</div>';

        $this->functions->print_form($form_fields, $format);
    }

    public function afterAddToCart_show_goToCart_option()
    {
        //call in footer
        global $_e;

        if ($this->functions->ibms_setting('afterAddToCart_show_goToCart_option') == '1' && !$this->functions->isCartPage()) {

            $checkoutO = '<div class="container-fluid">
<input type="hidden" class="goToCartOption"  value="1" />
<div class="col-sm-6 col-xs-12"><a id="continue_shopping" style="" href="#" data-dismiss="modal"><div class="col-xs-12 btn btn-lg btn-default">' . $_e["CONTINUE SHOPPING"] . '</div></a></div>
<div class="visible-xs clearfix margin-5"></div>
<div class="col-sm-6  col-xs-12"><a id="go_to_checkout" class="" href="' . WEB_URL . '/cart" data-page="cart" title="Cart - Sharkspeed"><div class="col-xs-12 btn btn-lg btn-success">' . $_e["GO TO CHECKOUT"] . '</div></a></div>
</div><!--check_btn_area end-->
';
            $title = $_e["Product add to cart successfully"];
            return $this->functions->blankModal("$title", "goToCartOptionId", $checkoutO);
        }
        return "";
    }

    public function shippingClassInfo($shippingClassId)
    {
        $sql = "SELECT id,price as priceSer,name FROM shipping_class WHERE id = '$shippingClassId' AND publish = '1'";
        $data = $this->dbF->getRow($sql);
        if (!empty($data)) {
            $price = unserialize($data['priceSer']);
            $currency = $this->currentCurrencyId();
            $data['price'] = $price[$currency];
            return $data;
        }
        return false;
    }

    public function orderDataByCustomSizeId($id)
    {
        $sql = "SELECT * FROM `order_invoice_product` WHERE order_pIds LIKE '%-%-%-%-$id'";
        $orderProducts = $this->dbF->getRow($sql);
        $orderId = $orderProducts['order_invoice_id'];

        $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$orderId' AND orderStatus = 'process'";
        $orderInvoice = $this->dbF->getRow($sql);

        $sql = "SELECT * FROM `order_invoice_info` WHERE order_invoice_id = '$orderId'";
        $orderInfo = $this->dbF->getRow($sql);

        $data = array_merge($orderProducts, $orderInvoice, $orderInfo);
        return $data;
    }

    public function orderDataByCustomSizeIdNew($id)
    {
        $sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id =  '$id'";
        $orderProducts = $this->dbF->getRows($sql);
        $orderId = $orderProducts['order_invoice_id'];

        $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id' ";
        $orderInvoice = $this->dbF->getRow($sql);

        $sql = "SELECT * FROM `order_invoice_info` WHERE order_invoice_id = '$id'";
        $orderInfo = $this->dbF->getRow($sql);

        $data = array("products" => $orderProducts, "invoices" => $orderInvoice, "orderInfo" => $orderInfo);
        return $data;
    }

    public function getNextOrPrevProduct($pid, $next = true)
    {
        if (empty($pid)) {
            return "";
        }

        if ($next) {
            $where = "detail.prodet_id > '$pid'";
        } else {
            $where = "detail.prodet_id < '$pid'";
        }

        $sql = "SELECT detail.prodet_id,detail.slug, detail.prodet_name, setting.p_id, setting.setting_val
FROM `proudct_detail` detail INNER JOIN `product_setting` setting
ON detail.prodet_id = setting.p_id
WHERE
$where AND setting.setting_name = 'publicAccess' AND setting.setting_val = 1
AND detail.product_update = '1'
ORDER BY `prodet_id` DESC LIMIT 1";

        $data = $this->dbF->getRow($sql);
        $name = array();
        if ($this->dbF->rowCount > 0) {

        } else {
            if ($next) {
                //First Product.
                $where = "ORDER BY `prodet_id` ASC";
            } else {
                //last Product.
                $where = "ORDER BY `prodet_id` DESC";
            }
            $sql = "SELECT detail.prodet_id,detail.slug, detail.prodet_name, setting.p_id, setting.setting_val
FROM `proudct_detail` detail INNER JOIN `product_setting` setting ON detail.prodet_id = setting.p_id
WHERE setting.setting_name = 'publicAccess' AND setting.setting_val = 1 AND detail.product_update = '1'
$where LIMIT 1";
            $data = $this->dbF->getRow($sql);
        }

        $name['name'] = translateFromSerialize($data['prodet_name']);
        $name['id'] = $data['prodet_id'];
        $name['slug'] = $data['slug'];
        $name['link'] = WEB_URL . "/" . $this->db->productDetail . $name['slug'];
        if ($this->productDateIsReadyForLaunch($name['id'])) {
            return $name;
        }
        return false;
    }

    /**
     * return addional total price of select payment method
     * @param $payment_type
     */
    public function payment_additional_price($payment_type, $return_price = false)
    {
        //Add additional price to selected payment method
        @$payment_additional_price = unserialize($this->functions->ibms_setting("payment_method_price"));
        $currency_id = $this->currentCurrencyId();
        $currency_symbol = $this->currentCurrencySymbol();

        @$price = $payment_additional_price[$payment_type][$currency_id];
        $price = empty($price) ? 0 : $price;

        //only return price for calculation
        if ($return_price)
            return floatval($price);

        if (!empty($price) && $price > 0) {
            return " (+$price $currency_symbol)
<input type='hidden' value='$price' data-symbol='$currency_symbol' data-payment='$payment_type' class='payment_method_price payment_method_price_$payment_type'/>
";
        }
        return "";
    }

    private function bestSellerSQL()
    {
        return " INNER JOIN best_seller_products bsp ON bsp.product_id = prodet_id ";
    }

    private function newBestSellerSQL($cat_like_sql = '')
    {

        $cat_like_sql = ($cat_like_sql != '') ? '( ' . $cat_like_sql . ') AND ' : '';

        $sql = " SELECT pd.`prodet_id` FROM proudct_detail as pd
INNER JOIN best_seller_products bsp ON bsp.product_id = pd.prodet_id
INNER JOIN product_setting ps       ON ps.p_id = pd.prodet_id
INNER JOIN product_category ON product_category.procat_prodet_id = pd.prodet_id
WHERE {$cat_like_sql}
`ps`.`setting_name`       = 'publicAccess'
AND   `ps`.`setting_val`        = '1'
AND   `pd`.`product_update`     = '1'
ORDER BY bsp.sort ASC ";
        $best_seller_products = $this->dbF->getRows($sql);

        return $best_seller_products;

    }

    public function get_product_slugname($pid)
    {
        $sql = " SELECT `slug` FROM `proudct_detail` WHERE `prodet_id` = ? AND product_update = '1' ";
        $row = $this->dbF->getRow($sql, array($pid));
        if ($this->dbF->rowCount > 0) {
            $result = $row['slug'];
        } else {
            $result = false;
        }

        return $result;

    }

    public function insert_user_info($data_array)
    {

        $invoiceId = $_SESSION['webUser']['lastInvoiceId'];
        $userId = $this->webUserId();

        $payer_email = $data_array[0];
        $payer_name = $data_array[1] . ' ' . $data_array[2];
        $recipientName = $data_array[3];
        $address = $data_array[4] . ', ' . $data_array[5]; // address line 1, state
        $countryCode = $data_array[6];
        $city = $data_array[7];
        $postalCode = $data_array[8];

        $sql = "INSERT INTO `order_invoice_info`
(
`order_invoice_id`,
`sender_Id`,
`sender_name`,

`sender_email`,
`receiver_name`,
`receiver_address`,

`receiver_city`,
`receiver_country`,
`receiver_post`
)
VALUES (
?,?,?,
?,?,?,
?,?,?
)";

        $array = array(
            $invoiceId,
            $userId,
            $payer_name,

            $payer_email,
            $recipientName,
            $address,

            $city,
            $countryCode,
            $postalCode

        );

        $this->dbF->setRow($sql, $array, false);
        if ($this->dbF->rowCount > 0) {
            $result = TRUE;
        } else {
            $result = FALSE;
        }

        return $result;

    }

    public function updateProductDiscountTable()
    {

        $sql = "SELECT `prodet_id`,`slug` FROM `proudct_detail`";
        $pro_Ids = $this->dbF->getRows($sql);

        if ($this->dbF->rowCount > 0) {

            foreach ($pro_Ids as $key => $value) {
                $pId = $value['prodet_id'];

                $sql1 = "SELECT * FROM `currency`";
                $cur_ids = $this->dbF->getRows($sql1);

                foreach ($cur_ids as $key => $val) {

                    $currencyId = $val['cur_id'];


                    $pPriceData = $this->productF->productPrice($pId, $currencyId);
                    //$pPriceData Return , currency id,international shipping, price, id,
                    $pPriceActual = $pPriceData['propri_price'];
                    $pPrice = $pPriceActual;

                    $discount = $this->productF->productDiscount($pId, $currencyId);
                    @$discountFormat = $discount['discountFormat'];
                    @$discountP = $discount['discount'];

                    $discountPrice = $this->productF->discountPriceCalculation($pPriceActual, $discount);
                    $newPrice = $pPriceActual - $discountPrice;

                    $pPrice = empty($pPrice) ? 0 : $pPrice;
                    $newPrice = empty($newPrice) ? 0 : $newPrice;

                    $array = array($newPrice, $pId);

                    $sql1 = "UPDATE `proudct_detail` SET `product_discount_$currencyId` = ? WHERE `prodet_id` = ?";
                    $pro_Ids1 = $this->dbF->setRow($sql1, $array);

                }
            }
        }
    }

    public function getNoProductsCategory($id)
    {

        $sql = "SELECT * FROM `categories` WHERE id = ? ";

        $catData = $this->dbF->getRow($sql, array($id));

        if (!$this->dbF->rowCount) {
            return false;
        }



        $catId = $catData['id'];
        $catId = $this->getSubCatIds($catId); //array


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
WHERE ($LIKE)
GROUP BY `detail`.`prodet_id`";

        $productIds = $this->dbF->getRows($sql);

        $ispublish = $this->countPublishProducts($productIds);

        return $ispublish;


    }

    public function countPublishProducts($pIds)
    {
        $count = 0;
        foreach ($pIds as $key => $value) {
            $sql = "SELECT * FROM `product_setting` WHERE `p_id` =? AND `setting_name` =? AND `setting_val` = ?";
            $res = $this->dbF->getRow($sql, array($value['prodet_id'], 'publicAccess', '1'));
            if ($this->dbF->rowCount) {
                $count++;
            }
        }
        return $count;

    }

    public function getDistinctSizeForFilterNew()
    {
        if ($this->functions->developer_setting('product_Scale') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) as name , prosiz_id FROM `product_size` GROUP BY prosiz_name ORDER BY prosiz_id ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "";

        if (isset($_SESSION['SizeFilter']) && !empty($_SESSION['SizeFilter'])) {
            $size = $_SESSION['SizeFilter'];
            $size1 = explode(',', $size);
        } else {
            $size = "no";

        }

        foreach ($data as $val) {
            $name = $val['name'];
            $id = "$val[prosiz_id]";

            $oj = ($name == $size) ? 'checked' : '';
            $temp .= "




<label>
<input type='radio' value='$name' id='' name='cb1' class='indeterminate-checkbox checkboxDesk1 squaredThree' $oj/>

 <span>
  $name
 </span>

</label>

";
        }

        return $temp;

    }

    public function getSizeFilters()
    {
        if ($this->functions->developer_setting('product_Scale') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT * FROM `filters` ORDER BY `id` ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "";

        if (isset($_SESSION['SizeFilter']) && !empty($_SESSION['SizeFilter'])) {
            $size = $_SESSION['SizeFilter'];
            $size1 = explode(',', $size);
        } else {
            $size = "no";

        }

        foreach ($data as $val) {
            $name = $val['name'];
            $id = "$val[id]";

            $oj = ($name == $size) ? 'checked' : '';
            $temp .= "




<label>
<input type='radio' value='$name' id='' name='cb1' class='indeterminate-checkbox checkboxDesk1 squaredThree' $oj/>

 <span>
  $name
 </span>

</label>

";
        }

        return $temp;

    }

    public function productHasScales($pId)
    {

        $storeId = $this->getStoreId();
        $currencyId = $this->currentCurrencyId();

        $sizeData = $this->productF->scaleWithInventory($pId, $currencyId, $storeId, @$hasColor);
        if (count($sizeData) > 0) {
            return 1;
        } else {
            return 0;
        }

    }

    public function productScales($pId, $storeId = false, $currencyId = false)
    {

        if ($currencyId == false || $currencyId == "") {
            $currencyId = $this->currentCurrencyId();
        }

        if ($storeId == false || $storeId == "") {
            $storeId = $this->getStoreId();
        }

        $sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId' AND qty_product_id = '$pId'";

        $sizeData = $this->productF->scaleWithInventory($pId, $currencyId, $storeId, @$hasColor);

        $scaleForpBox = '<ul>';
        foreach ($sizeData as $val) {
            if (isset($_SESSION["expressMode"]) && $val["hasInventory"] == 0) {
                continue;
            }
            $sizeName = $val['prosiz_name'];
            $sizePrice = $val['prosiz_price'];
            $sizeId = $val['prosiz_id'];
            @$sizeInventoryId = $val['inventoryScaleId'];

            $scaleForpBox .= "<li><a>$sizeName</a></li>";

        }

        $scaleForpBox .= '<ul>';

        return $scaleForpBox;

    }

    public function getDistinctSizeForFilter()
    {
        if ($this->functions->developer_setting('product_Scale') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) as name , prosiz_id FROM `product_size` GROUP BY prosiz_name ORDER BY prosiz_id ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "";

        if (isset($_SESSION['SizeFilter']) && !empty($_SESSION['SizeFilter'])) {
            $size = $_SESSION['SizeFilter'];
            @$size1 = explode(',', $size);
        }

        foreach ($data as $val) {
            $name = $val['name'];
            $id = "$val[prosiz_id]";
            $temp .= "

<div class='squaredThree'>
<label for='squaredThree'></label>
<input type='checkbox' value='$name' id='squaredThree' name='cb1' class='checkboxDesk' checked=''/> $name
</div>
";
        }

        return $temp;

    }

    public function getDistinctSizeForFilterMobileNewAll()
    {
        if ($this->functions->developer_setting('product_Scale') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) as name , prosiz_id FROM `product_size` GROUP BY prosiz_name ORDER BY prosiz_id ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "";



        foreach ($data as $val) {
            $name = $val['name'];
            $id = "$val[prosiz_id]";


            $temp .= "<li>


<a data-id='$name'>$name</a>


</li>
";
        }


        return $temp;

    }

    public function getDistinctSizeForFilterMobile()
    {
        if ($this->functions->developer_setting('product_Scale') == '0') {
            return "";
        }
        global $_e;
        $sql = "SELECT DISTINCT(prosiz_name) as name , prosiz_id FROM `product_size` GROUP BY prosiz_name ORDER BY prosiz_id ASC";
        $data = $this->dbF->getRows($sql);
        $temp = "";

        if (isset($_SESSION['SizeFilter']) && !empty($_SESSION['SizeFilter'])) {
            $size = $_SESSION['SizeFilter'];
            @$size1 = explode(',', $size);
        } else {

            $size1 = "";
        }


        return $temp;

    }

    public function getInvoiceId($order_invoice_pk)
    {

        $sql = "SELECT * FROM `order_invoice` WHERE `order_invoice_pk` = ? ";
        $res = $this->dbF->getRow($sql, array($order_invoice_pk));

        return $res;
    }

    public function getExtraInvoiceId($id)
    {

        $sql = "SELECT * FROM `order_extra_amount` WHERE `id` = ? ";
        $res = $this->dbF->getRow($sql, array($id));

        return $res;
    }

    public function orderlog($title = "untitled", $ref_name = false, $ref_id = "", $desc = "", $transaction = true)
    {
        if (!empty($ref_name)) {
            $user_email = @$_SESSION['_email'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = "";
            foreach ($this->getBrowser() as $key => $val) {
                $browser .= "$key : $val <br />";
            }

            $sql = "INSERT INTO `order_activity_log` (`log_title`, `ref_name`, `ref_id`, `ref_user`, `log_desc`, `log_ip`, `log_browser`)
VALUES (?,?,?,?,?,?,?) ";

            $arr = array($title, $ref_name, $ref_id, $user_email, $desc, $ip, $browser);
            $this->dbF->setRow($sql, $arr, $transaction);
            return true;
        }
        return false;
    }

    private function getBrowser()
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

    public function ProductsUserBought($pId, $limit = false, $viewType = false)
    {
        $sql = "SELECT `pId` AS `prodet_id` FROM `recommended` WHERE `orderUser` IN (SELECT Distinct `orderUser` FROM `recommended` WHERE `pId` IN(?)) GROUP BY `pId` ORDER BY Count(`pId`) DESC LIMIT 0,15";
        $res = $this->dbF->getRows($sql, array($pId));

        if ($this->dbF->rowCount < 15) {
            $rowReturn = $this->dbF->rowCount;
            $limit = 15 - $rowReturn;
            $sql1 = "SELECT `pId` AS `prodet_id` FROM `recommended` ORDER BY RAND() LIMIT $limit";
            $res1 = $this->dbF->getRows($sql1);
            $res = array_merge($res, $res1);
        }

        $products = '';
        $array['viewType'] = $viewType;

        return $this->productPrint($res, '0', $products, $array);
    }

    public function ProductsCombineWith($catId, $limit = false)
    {
        if ($limit) {
            $limitBy = $limit;
        } else {
            $limitBy = 8;
            $useragent = $_SERVER['HTTP_USER_AGENT'];

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $limitBy = 5;
            }
        }
        $sql = "SELECT SUBSTRING_INDEX(ip.`order_pIds`, '-', 1) AS 'prodet_id' FROM `order_invoice_product` ip, `order_invoice` oi 
        WHERE SUBSTRING_INDEX(`order_pIds`, '-', 1) IN (SELECT `procat_prodet_id` FROM `product_category` WHERE `procat_cat_id` LIKE '%$catId%') AND oi.`order_invoice_pk` = ip.`order_invoice_id` AND DATE(oi.`invoice_date`) BETWEEN CURDATE()-INTERVAL 30 Day AND CURDATE() GROUP BY `prodet_id` ORDER BY Count(`prodet_id`) DESC LIMIT 0,$limitBy";
        $res = $this->dbF->getRows($sql);
        return $res;
    }

    public function productsCombineWithPrint($catId, $limit = false, $viewType = false)
    {

        $res = $this->ProductsCombineWith($catId, $limit = false);

        $products = '';
        $array['viewType'] = $viewType;
        return $this->productPrint($res, '0', $products, $array);
    }

    public function updateRecommendView()
    {
        $sql = "CREATE OR REPLACE VIEW recommended AS 
SELECT oi.`orderUser`,SUBSTRING_INDEX(ip.`order_pIds`, '-', 1) AS 'pId' 
FROM `order_invoice` oi 
JOIN `order_invoice_product` ip 
WHERE oi.`order_invoice_pk` = ip.`order_invoice_id`";
        $this->dbF->updateViewRecommmend($sql);
    }

    public function getCatDeals($catId, $index)
    {
        $sql = "SELECT id,name,price,image,slug,package,view_type FROM product_deal WHERE publish = '1' AND `category` LIKE '%$catId%'";
        $res = $this->dbF->getRows($sql);
        return @$res[$index];
    }
    
    public function getCatRecomended($catId, $index)
    {
        $sql = "SELECT product_id FROM sp_recommends WHERE `publish` = '1' AND `cat_id` = '$catId'";
        $res = $this->dbF->getRow($sql);
        return @$res['product_id'];
    }
    
    public function countTotalDeals($catId)
    {
        $sql = "SELECT count('*') AS totalDeals FROM product_deal WHERE publish = '1' AND `category` LIKE '%$catId%'";
        $res = $this->dbF->getRow($sql);
        return $res['totalDeals'];
    }

    public function getSeoLink($slug = false)
    {
        $sql2 = "SELECT `pageLink`,`id` FROM `seo` WHERE `slug` = ?";
        $res2 = $this->dbF->getRow($sql2, array($slug));
        if ($this->dbF->rowCount > 0) {
            $webLang = currentWebLanguage();
            $seoid = $res2['id'];
            $sql_seo_slug = "SELECT slug FROM seo_slug WHERE seo_id = '$seoid' and lang = '$webLang' and slug != ''";
            $check_seo_slug = $this->dbF->getRow($sql_seo_slug);
            if ($this->dbF->rowCount > 0) {
                return $check_seo_slug['slug'];
            } else {
                return $res2['pageLink'];
            }
        } else {
            return $res2['pageLink'];
        }
    }

    public function getQtyss($slug = false)
    {
        $sql2 = "SELECT `txt_inv_pro_qty` FROM `free_gift_inv` WHERE `id` = ? and status = ?";
        $res2 = $this->dbF->getRow($sql2, array($slug, 0));

        $result = ($this->dbF->rowCount > 0) ? $res2['txt_inv_pro_qty'] : FALSE;

        return $result;
    }

    public function returnPro($invoiceId)
    {

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

        $pLink = WEB_URL . "/detail.php?pId=$invoiceId";



        $id = $invoiceId;
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

        $newPrice = $priceP - $discountPrice;
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
<div class='pop_img1'><img alt='' loading='lazy' src='$img' /></div>
<!-- pop_img1 close --></div>
<!-- pop_img close -->

<div class='pop_content_main'>
<div class='selection_side'>$name</div>
<!-- selection_side close -->
<div class='button_side'><!-- <div class='button_side1'><a href='" . $pLink . "' data-page='" . $pLink . "' title=''>No thanks !</a></div> --><!-- button_side1 close -->
<div class='button_side2'><a href='" . $pLink . "' data-page='" . $pLink . "' title='" . $buyToT . "'>" . $buyToT . "</a></div>
<!-- button_side1 close --></div>
<!-- button_side close --></div>
<!-- pop_content_main close --></div>";


        return $temp;
    }

    public function extraSalesProductsNew($invoiceId, $orderedProducts = false)
    {

        $sql = "SELECT * FROM `extra_sales_products` WHERE `invoice_no` = ?";
        $res = $this->dbF->getRow($sql, array($invoiceId));

        $offerProducts = $res['products'];
        $offerProducts = explode(',', $offerProducts);

        $firstEmail = $res["first_email"];
        $secondEmail = $res["second_email"];
        
        $extra15Percent = false;

        // if ($firstEmail == 1 && $secondEmail == NULL) {
        //     $extra15Percent = true;
        // }


        $pLink = WEB_URL . '/saleOfferNew.php?invoice=' . base64_encode($invoiceId);

        foreach ($offerProducts as $pId) {
            $name = $this->productF->getProductName($pId);
            $img = $this->productF->productSpecialImage($pId, 'main');
            $img = $this->functions->resizeImage($img, 'auto', 160, false);
            $currencyId = $this->currentCurrencyId();
            $price = $this->productF->productPrice($pId, $currencyId);
            $symbol = $this->productF->currencySymbol($currencyId);
            $priceP = $price['propri_price'];
            $discount = $this->productF->productDiscount($pId, $currencyId);
            $discountPrice = $this->productF->discountPriceCalculation($priceP, $discount);

            if ($extra15Percent) {
                $originalPrice = $priceP - $discountPrice;
                $discountPercentage = 15;
                $discountAmount = ($originalPrice * $discountPercentage) / 100;
                $newPrice = ceil($originalPrice - $discountAmount);
            } else {
                $newPrice = $priceP - $discountPrice;
            }

            $priceP .= ' ' . $symbol;
            $newPrice .= ' ' . $symbol;

            if ($newPrice != $priceP) {
                $hasDiscount = true;
                $oldPriceDiv = '<span class="oldPrice" style="text-decoration: line-through; font-size: 20px;">' . $priceP . '</span>';
                $newPriceDiv = $newPrice;
            } else {
                $oldPriceDiv = "";
                $newPriceDiv = $priceP;
            }

            $buyToT = $this->dbF->hardWords('Buy To', false);

            $temp .= "<div style='position: relative; width: 45%; padding-top: 20px; text-align: center; display: inline-block; vertical-align: top; margin-right: 2%;'>
                <div style='position: relative; display: block; width: 35%; margin: 0 auto;'>
                    <div style='position: relative; width: 100%;'>
                        <img alt='' src='$img' style='width: 100%; max-width: 160px;' />
                    </div>
                </div>
                
                <div style='display: inline-block; vertical-align: middle; width: 55%; text-align: center; margin: 15px 0;'>
                    <div style='color: #42474b; font-size: 16px;'>$name</div>
                    
                    <div style='color: #eb333d; font-size: 25px; text-align: center; font-weight: bold;'>
                        $newPriceDiv $oldPriceDiv
                    </div>
                    
                    <div style='display: inline-block; vertical-align: middle; width: 100%; margin-top: 5px;'>
                        <div style='display: inline-block; vertical-align: top; width: 60%; background: #7ebc41; padding: 10px 0; border-radius: 2px; text-align: center;'>
                            <a href='" . $pLink . "' style='color: #ffffff; font-size: 16px; text-decoration: none;'>" . $buyToT . "</a>
                        </div>
                    </div>
                </div>
            </div>";


        }
        
        if ($firstEmail == NULL && $secondEmail == NULL){
            $sqlUpdate = "UPDATE `extra_sales_products` SET `first_email` = ? WHERE `invoice_no` = ?";
            $this->dbF->setRow($sqlUpdate, [1, $invoiceId]);
        }
        
        // elseif($firstEmail == 1 && $secondEmail == NULL){
        //     $sqlUpdate = "UPDATE `extra_sales_products` SET `second_email` = ? WHERE `invoice_no` = ?";
        //     $this->dbF->setRow($sqlUpdate, [1, $invoiceId]);
        // }
        
        return $temp;
    }

    public function extraSalesProducts($invoiceId, $orderedProducts = false)
    {

        $sql = "SELECT * FROM `extra_sales_products` WHERE `invoice_no` = ?";
        $res = $this->dbF->getRow($sql, array($invoiceId));

        $offerProducts = json_decode($res['products']);

        $firstEmail = $res["first_email"];
        $secondEmail = $res["second_email"];
        
        $extra15Percent = false;

        // if ($firstEmail == 1 && $secondEmail == NULL) {
        //     $extra15Percent = true;
        // }

        

        $pLink = WEB_URL . '/saleOffer.php?invoice=' . base64_encode($invoiceId);

        foreach ($offerProducts as $pId => $SpecPrice) {
            $name = $this->productF->getProductName($pId);
            $img = $this->productF->productSpecialImage($pId, 'main');
            $img = $this->functions->resizeImage($img, 'auto', 160, false);
            $currencyId = $this->currentCurrencyId();
            $price = $this->productF->productPrice($pId, $currencyId);
            $symbol = $this->productF->currencySymbol($currencyId);
            $priceP = $price['propri_price'];
            $discountPrice = $SpecPrice;


            if ($extra15Percent) {
                $originalPrice = $discountPrice;
                $discountPercentage = 15;
                $discountAmount = ($originalPrice * $discountPercentage) / 100;
                $newPrice = ceil($originalPrice - $discountAmount);
            } else {
                $newPrice = $discountPrice;
            }

            $priceP .= ' ' . $symbol;
            $newPrice .= ' ' . $symbol;

            if ($newPrice != $priceP) {
                $hasDiscount = true;
                $oldPriceDiv = '<span class="oldPrice" style="text-decoration: line-through; font-size: 20px;">' . $priceP . '</span>';
                $newPriceDiv = $newPrice;
            } else {
                $oldPriceDiv = "";
                $newPriceDiv = $priceP;
            }


            $buyToT = $this->dbF->hardWords('Buy To', false);

            $temp .= "<div style='position: relative; width: 45%; padding-top: 20px; text-align: center; display: inline-block; vertical-align: top; margin-right: 2%;'>
                <div style='position: relative; display: block; width: 35%; margin: 0 auto;'>
                    <div style='position: relative; width: 100%;'>
                        <img alt='' src='$img' style='width: 100%; max-width: 160px;' />
                    </div>
                </div>
                
                <div style='display: inline-block; vertical-align: middle; width: 55%; text-align: center; margin: 15px 0;'>
                    <div style='color: #42474b; font-size: 16px;'>$name</div>
                    
                    <div style='color: #eb333d; font-size: 25px; text-align: center; font-weight: bold;'>
                        $newPriceDiv $oldPriceDiv
                    </div>
                    
                    <div style='display: inline-block; vertical-align: middle; width: 100%; margin-top: 5px;'>
                        <div style='display: inline-block; vertical-align: top; width: 60%; background: #7ebc41; padding: 10px 0; border-radius: 2px; text-align: center;'>
                            <a href='" . $pLink . "' style='color: #ffffff; font-size: 16px; text-decoration: none;'>" . $buyToT . "</a>
                        </div>
                    </div>
                </div>
            </div>";

        }
            
        if ($firstEmail == NULL && $secondEmail == NULL){
            $sqlUpdate = "UPDATE `extra_sales_products` SET `first_email` = ? WHERE `invoice_no` = ?";
            $this->dbF->setRow($sqlUpdate, [1, $invoiceId]);
        }
        
        // elseif($firstEmail == 1 && $secondEmail == NULL){
        //     $sqlUpdate = "UPDATE `extra_sales_products` SET `second_email` = ? WHERE `invoice_no` = ?";
        //     $this->dbF->setRow($sqlUpdate, [1, $invoiceId]);
        // }
        
        return $temp;
    }

    public function AllGiftProducts($inv)
    {

        $cur_Date = date('Y-m-d');
        $sql = "SELECT pro_ids FROM `free_gift_inv` WHERE `id` = ?";
        $res = $this->dbF->getRow($sql, array($inv));

        $productIds = array();
        $saleProducts = $res['pro_ids'];


        $cats = explode(",", $saleProducts);

        foreach ($cats as $catIds) {




            $productIds[] = array(
                "prodet_id" => $catIds
            );
        }

        $products = '<input type="hidden" style="display: none" id="q_tempTable" value=""/>';

        return $this->freeGiftProductPrint($productIds, false, $products);
    }
    
    public function AllSaleProductsNew($inv)
    {
        $cur_Date = date('Y-m-d');
        $sql = "SELECT * FROM `extra_sales_products` WHERE `invoice_no` = ? AND `validity_date` >= $cur_Date";
        $res = $this->dbF->getRow($sql, array($inv));

        $firstEmail = $res["first_email"];
        $secondEmail = $res["second_email"];
        
        $extra15Percent = false;

        if ($firstEmail == 1 && $secondEmail == 1) {
            $extra15Percent = true;
        }

        $productIds = array();
        $saleProducts = $res['products'];
        $saleProducts = explode(',', $saleProducts);

        foreach ($saleProducts as $pId) {
            $currencyId = $this->currentCurrencyId();
            $price = $this->productF->productPrice($pId, $currencyId);
            $priceP = $price['propri_price'];
            $discount = $this->productF->productDiscount($pId, $currencyId);
            $discountPrice = $this->productF->discountPriceCalculation($priceP, $discount);

            if ($extra15Percent) {
                $originalPrice = $priceP - $discountPrice;
                $discountPercentage = $this->functions->ibms_setting('vipDiscountPercentage');;
                $discountAmount = ($originalPrice * $discountPercentage) / 100;
                $value = ceil($originalPrice - $discountAmount);
            } else {
                $value = $priceP - $discountPrice;
            }

            $productIds[] = array(
                "procat_prodet_id" => $pId,
                "prodet_id" => $pId,
                "salePrice" => $value
            );
        }

        $products = '<input type="hidden" style="display: none" id="q_tempTable" value=""/>';

        return $this->saleProductPrint($productIds, $inv, false, $products);
    }
    
    public function AllSaleProducts($inv)
    {
        $currentDate = date('Y-m-d');
        $validity = $this->functions->ibms_setting('saleOfferValidity');
        $productIds = [];
        
        $sqlInv = "SELECT `invoice_date` FROM `order_invoice` WHERE `order_invoice_pk` = ?";
        $dataInv = $this->dbF->getRow($sqlInv, [$inv]);
        
        if ($this->dbF->rowCount > 0) {
            $orderDate = date('Y-m-d', strtotime($dataInv["invoice_date"]));
        
            $orderTimestamp = strtotime($orderDate);
            $currentTimestamp = strtotime($currentDate);
            
            $dateDifference = ($currentTimestamp - $orderTimestamp) / (60 * 60 * 24);
    
            if ($dateDifference <= $validity) {
                $sql = "SELECT * FROM `extra_sales_products` WHERE `invoice_no` = ?";
                $res = $this->dbF->getRow($sql, [$inv]);
                $saleProducts = json_decode($res['products'], true);
            } else {
                $saleProducts = [];
            }
        } else {
            $saleProducts = [];
        }
        
        $superDiscountPer = $this->functions->ibms_setting('superDiscountPercentage');
        
        foreach ($saleProducts as $key => $value) {
            if ($superDiscountPer > 0) {
                $originalPrice = $value;
                $discountPercentage = $this->functions->ibms_setting('superDiscountPercentage');
                $discountAmount = ($originalPrice * $discountPercentage) / 100;
                $value = ceil($originalPrice - $discountAmount);
            } else {
                $value = $value;
            }
            $productIds[] = [
                "procat_prodet_id" => $key,
                "prodet_id" => $key,
                "salePrice" => $value
            ];
        }

        $products = '<input type="hidden" style="display: none" id="q_tempTable" value=""/>';

        return $this->saleProductPrint($productIds, $inv, false, $products);
    }
}
?>