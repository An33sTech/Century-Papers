<?php
include_once "global.php";
global $webClass, $productClass, $_e;
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['pId'])) {
    $pId = sanitize_slug($_GET['pId']);
    if (stristr($pId, "-")) {
        $pId = explode("-", $pId, 2);
        $_GET['pId'] = $pId[0];
        $_GET['pName'] = $pId[1];
    }
}

if (isset($_GET['pSlug'])) {
    $pSlug = sanitize_slug($_GET['pSlug']);
    $sql = "SELECT prodet_id FROM proudct_detail WHERE slug = '$pSlug'";
    $productSlug = $dbF->getRow($sql);
    $pId = $productSlug['prodet_id'];
    $_GET['pId'] = $pId;
}

$pId = isset($_GET['pId']) ? sanitize_slug($_GET['pId']) : 0;
$fullUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//$productClass->setRecentlyVisitedProduct($pId, $fullUrl);
$webLang = currentWebLanguage();
$defaultLang = defaultWebLanguage();
$data = $productClass->productData($pId);
$productImage = $productClass->productSpecialImage($pId, 'main');
if ($productImage == "") {
    $productImage = "default.jpg";
}
$productThumb = $webClass->resizeImage($productImage, 619, 680, false);
$productImage = $webClass->resizeImage($productImage, 619, 680, false);
$pName = translateFromSerialize($data['prodet_name']);
$pShrtDesc = translateFromSerialize($data['prodet_shortDesc']);

$functions->require_once_custom('webBlog_functions');
$blogC = new webBlog_functions();
$reviewMsg = "";
$reviews = "";
$pSetting = $productClass->productF->getProductSetting($pId);
$myReview = $productClass->productF->productSettingArray('review', $pSetting, $pId);
$reviewOff = $productClass->productF->productSettingArray('reviewOffMsg', $pSetting, $pId);
$questionOff = $productClass->productF->productSettingArray('questionOffMsg', $pSetting, $pId);
$questionAllow = $productClass->productF->productSettingArray('askQuestion', $pSetting, $pId);
$supplier = $productClass->productF->productSettingArray('supplier', $pSetting, $pId);
$pDesc = translateFromSerialize($productClass->productF->productSettingArray('ldesc', $pSetting, $pId));
$additionalInfo = translateFromSerialize($productClass->productF->productSettingArray('Ainfo', $pSetting, $pId));
$shipping = translateFromSerialize($productClass->productF->productSettingArray('shipping', $pSetting, $pId));
$return = translateFromSerialize($productClass->productF->productSettingArray('return', $pSetting, $pId));
$freeGift = $productClass->productF->productSettingArray('freeGift', $pSetting, $pId);
$size_chart = translateFromSerialize($productClass->productF->productSettingArray('size_chart', $pSetting, $pId));
$delivery = translateFromSerialize($productClass->productF->productSettingArray('del', $pSetting, $pId));
// $shipping2 = translateFromSerialize($productClass->productF->productSettingArray('return', $pSetting, $pId));

if ($myReview == '1' || empty($myReview)) {
    $reviewMsg = $blogC->reviewSubmit();
    $reviews = $blogC->reviews($pId, 'product', 3, false, $myReview, $reviewOff);
} else if ($reviewOff != '') {
    $reviews = "<hr><div class='reviewoffMsg alert alert-warning  margin-0'>$reviewOff</div>";
}

$sqlProductTag = "SELECT `setting_val` FROM `product_setting` WHERE p_id = ? AND setting_name = 'productTags'";
$sqlProductTagData = $dbF->getRow($sqlProductTag, [$pId]);
$productTags = "";
if ($sqlProductTagData !== false) {
$productTagsArray = unserialize($sqlProductTagData['setting_val']);
for ($i = 0; $i < 4; $i++) {
if (isset($productTagsArray[$i])) {
$t = $dbF->hardWords($productTagsArray[$i], false);
$productTags .= '<div class="badges">' . $t . '</div>';
}
}
}

$currencyId = $productClass->currentCurrencyId();
$currencySymbol = $productClass->currentCurrencySymbol();
$pPriceData = $productClass->productF->productPrice($pId, $currencyId);
$pPrice = $pPriceData['propri_price'];
$discount = $productClass->productF->productDiscount($pId, $currencyId);
$discountFormat = $discount['discountFormat'] ?? '';
$discountP = $discount['discount'] ?? '';
$discountPrice = $productClass->productF->discountPriceCalculation($pPrice, $discount);
$newPrice = $pPrice - $discountPrice;
//$categories = $productClass->getProductCategories($pId);
$hasColorVal = $functions->developer_setting('product_color');
$hasWebOrder_with_Scale = $functions->developer_setting('webOrder_with_Scale');
$hasWebOrder_with_color = $functions->developer_setting('webOrder_with_color');
$inventoryLimit = $functions->developer_setting('product_check_stock');
$hasScaleVal = $functions->developer_setting('product_Scale');
$sku = $productClass->productF->productSettingArray('sku', $pSetting, $pId);
$hasColor = ($hasColorVal == '1' ? true : false);

$inventoryLimit = ($inventoryLimit == '1' ? true : false);
$freeGift_setting_val = ($freeGift == '1' ? true : false);
$hasScale = ($hasScaleVal == '1' ? true : false);
$storeId = $productClass->getStoreId();
if ($inventoryLimit || $freeGift_setting_val) {
    $getInfo = $productClass->inventoryReport($pId);
} else {
    $getInfo = $productClass->productSclaeColorReport($pId);
}

if ($getInfo['scale'] == false && $hasWebOrder_with_Scale == '0') {
    $scaleDiv = "";
    $hasScaleVal = 0;
    $hasScale = false;
}
$getInfoReport = $getInfo['report'];
$scaleDiv = "";

if ($hasScale) {
    $scaleDiv = $productClass->getScalesDiv($pId, $hasColor, $storeId, $currencyId, $currencySymbol);
}
if ($hasScale) {
    $colorDiv = $productClass->getColorsDiv($pId, $hasColor, $storeId, $currencyId, $currencySymbol);
}
$userId = $productClass->webUserId();
$TempUserId = $productClass->webTempUserId();
$sqlFavorite = "SELECT DISTINCT (pId) FROM `cartwishlist` WHERE `userId` = ? AND `tempUser` = ? AND pId = ?";
$dbF->getRows($sqlFavorite, [$userId, $TempUserId, $pId]);

$favoriteCount = $dbF->rowCount;
$arryySeo = [];

$arraySeo['title'] = $pName;

$arraySeo['image'] = $productImage;

$arraySeo['price'] = $pPrice;

$arraySeo['currency'] = $currencySymbol;

$productClass->productMetaSeo($arraySeo);

if (!$isAjax) {
    include_once "header.php";
}

$jsInfo = " <!-- javascript Info use in js-->

<input type='hidden' id='currency_$pId' value='$currencySymbol' data-discountP='$discountP' data-discountFormat='$discountFormat' data-discountDefaultPrice='$newPrice' data-defaultPrice='$pPrice'/>

<input type='hidden' id='store_$pId' value='$storeId'/>

<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>

<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>

<input type='hidden' id='hasFreePro_$pId' value='$freeGift'/>

<input type='hidden' id='order_with_Color_$pId' value='$hasWebOrder_with_color'/>

<input type='hidden' id='order_with_Scale_$pId' value='$hasWebOrder_with_Scale'/>

<input type='hidden' id='deatilStockCheck_$pId' value='$inventoryLimit' >

$getInfoReport

<!-- javascript Info use in js End-->";
echo $jsInfo;
$allImages = $productClass->productAllImage($pId);
if (count($allImages) == 1) {
$img = $allImages[0]['image'];
$imgSize1 = $functions->resizeImage($img, 825, 530, false);
$alt = $allImages[0]['alt'] !== NULL ? $allImages[0]['alt'] : $pName;
}
?>
<!-- Page Title -->
<div class="industify_fn_pagetitle innovative_banner">
    <div class="banner_bg_shapes">
        <div class="bg_shape grid_pattern"></div>
        <div class="bg_shape glow_1"></div>
        <div class="bg_shape glow_2"></div>
    </div>
    <div class="banner_decorations">
        <!-- Floating Leaf 1 -->
        <div class="decor_item leaf_1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 22C2 22 6 18 12 17C18 16 22 12 22 2C22 2 12 2 7 8C2 14 2 22 2 22Z" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 22C10 20 16 16 22 2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Leaf 2 -->
        <div class="decor_item leaf_2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 22C2 22 6 18 12 17C18 16 22 12 22 2C22 2 12 2 7 8C2 14 2 22 2 22Z" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 22C10 20 16 16 22 2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Recycling Symbol -->
        <div class="decor_item recycling_icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 12V20C4 20.5523 4.44772 21 5 21H13" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20 12V4C20 3.44772 19.5523 3 19 3H11" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 8L20 4L16 0" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8 16L4 20L8 24" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17 13.5L12 22L7 13.5H17Z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Paper Sheet 1 -->
        <div class="decor_item paper_1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                    d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 2V8H20" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 13H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 17H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10 9H8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Paper Sheet 2 -->
        <div class="decor_item paper_2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                    d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 2V8H20" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 13H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 17H8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div class="container">
        <div class="title_holder">
            <h3><?php echo $pName ?></h3>
            <div class="industify_fn_breadcrumbs">
                <ul>
                    <li><a href="<?php echo WEB_URL ?>" title="Home">Home</a></li>
                    <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                    <li><span class="bread-current"><?php echo $pName ?></span></li>
                </ul>
            </div>

        </div>
    </div>
</div>
<div class="industify_fn_sidebarpage">
    <div class="container">
        <div class="s_inner">
            <div class="industify_fn_leftsidebar">
                <div class="industify_fn_service_single">
                    <div class="img_holder">
                        <img src="<?php echo $imgSize1 ?>" alt="Century Packaging Boards" />
                    </div>
                    <?php echo $pDesc ?>
                </div>
            </div>
            <div class="industify_fn_rightsidebar">
                <div class="service_list_as_function">
                    <div class="title">
                        <h3>Our Products</h3>
                    </div>
                    <div class="list_holder">
                        <ul>
                        <?php
                        $sqlProducts = "SELECT `prodet_id`, `prodet_name`, `slug` FROM `proudct_detail` WHERE `product_update` = '1'";
                        $productsData = $dbF->getRows($sqlProducts);
                        foreach($productsData as $product){
                            $title = getTextFromSerializeArray($product['prodet_name']);
                            $slug = $product['slug'];
                            echo'<li class="active"><a href="'.$slug.'">'.$title.'</a></li>';
                        }
                        ?>
                        </ul>
                    </div>
                </div>
                <div data-html="includes/sidebar.html"></div>
            </div>
        </div>
    </div>
</div>
<!-- /Sidebar Page -->
<?php
include_once "footer.php";
?>