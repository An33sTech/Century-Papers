<?php
ob_start();

global $db, $dbF, $functions;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$product = new product();
$product->editProductInformation();

$isEdit = false;

if ($product->editPid != "") {
    $isEdit = true;
}


if (!isset($_POST[$product->prefix_editPro]) || isset($_POST['edit_proSubmit']) || isset($_POST['copy'])) {

    $product->addProductInformation(); //add or edit

    if (!isset($_POST['edit_proSubmit'])) {
        $product->firstInsert();
    }
}

if ($isEdit) {
    //product setting Data
    $eData = $product->productSettingEdit();
}


/*

 * Create Json Country List

 * with price code data format

 * for custom slots

 */

$product->currencyListJson("cdata");


//Words

/**

 * MultiLanguage keys Use where echo;

 * define this class words and where this class will call

 * and define words of file where this class will called

 **/

global $_e;

global $adminPanelLanguage;

$_w = array();

$_w['Size Name'] = '';

$_w['On'] = '';

$_w['Off'] = '';

$_w['Weight In KG'] = '';

$_w['Edit Product'] = '';

$_w['Add New Product'] = '';

$_w['Basic Information'] = '';

$_w['Category'] = '';

$_w['Price'] = '';

$_w['Sizes'] = '';

$_w['Colors'] = '';

$_w['Images'] = '';

$_w['Detail Images'] = '';

$_w['Setting'] = '';

$_w['Keywords Connection'] = '';

$_w['Select Product Keyword'] = '';

$_w['Select Connection Keywords'] = '';

$_w['Product Basic Information'] = '';

$_w['Product Name'] = '';

$_w['Name'] = '';

$_w['Free Gift Product'] = '';

$_w['Listing'] = '';

$_w['Yes'] = '';

$_w['No'] = '';

$_w['Detail Description'] = '';

$_w['SIZES & MEASUREMENTS'] = '';

$_w['DELIVERY & RETURN'] = '';

$_w['Product Price'] = '';

$_w['Add Slot'] = '';

$_w['Product Category'] = '';

$_w['Product Sizes'] = '';

$_w['Product Sizes Weight'] = '';

$_w['Enter Exact name that use in product Size name, IF Size Name not match Product Weight will not work in shipping and It will use product default weight'] = '';

$_w['Product Color'] = '';

$_w['Product Images'] = '';

$_w['Buy In Price'] = '';

$_w["Use These name in Alt, main: main image, And Other all image enter there alt"] = '';

$_w['Drop images here to upload.'] = '';

$_w['they will only be visible to you'] = '';

$_w['Public Access'] = '';

$_w['Product Launch Date'] = '';

$_w['Launch Date : leave blank if you want to Publish Now'] = '';

$_w['Default Weight In KG'] = '';

$_w['Supplier'] = '';

$_w['Enter Supplier Info'] = '';


$_w['Seperate with comma'] = '';

$_w['Minimum Quantity Allow'] = '';

$_w['Use Config Setting'] = '';

$_w['Maximum Quantity Allow'] = '';

$_w['Product Discount'] = '';

$_w['Manage Discount'] = '';

$_w['SUBMIT'] = '';

$_w['Image Preview'] = '';

$_w['Close'] = '';

$_w['Image Not Delete Please Try Again'] = '';

$_w['There is an error, Please Refresh Page and Try Again'] = '';

$_w['Done'] = '';

$_w['Fail'] = '';

$_w['Model'] = '';

$_w['Model No'] = '';

$_w['Label'] = '';

$_w['Publish'] = '';

$_w['Draft'] = '';

$_w['Short Description'] = '';

$_w['Policy Icons'] = '';

$_w['Specification'] = '';

$_w['Size Chart'] = '';

$_w['Buy 2 get 1 Free'] = '';

$_w['Buy QTY Limit'] = '';

$_w['Review'] = '';

$_w['Facebook Comment'] = '';

$_w['Review Off Msg'] = '';

$_w['Ask Questions'] = '';

$_w['Question Off Msg'] = '';

$_w['Fb Comment Off Msg'] = '';

$_w['Video Link'] = '';

$_w['Product Main Image Size : 230x380, Detail Image Size:475x700'] = '';

$_w['Custom Size Type'] = '';

$_w['Custom Size Price +'] = '';

$_w['Feature Points'] = '';

$_w['Related Products'] = '';

$_w['Relative Products'] = '';

$_w['Slug'] = '';

$_w['Quick Product Quantity Add Successfully'] = '';

$_w['Stock'] = '';

$_w['Selected Products'] = '';

$_w['Get A Look'] = '';

$_w['Get Look Products'] = '';

$_w['Get This Feature Look Products'] = '';

$_w['New Category'] = '';

$_w['Combine With Category'] = '';

$_w['Product Images Detail'] = '';

$_w['Product Tag'] = '';

$_w['Best Seller'] = '';

$_w['Campaign'] = '';

$_w['Surprise'] = '';

$_w['New Arrival'] = '';

$_w['Products Tags'] = '';

$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Product Add');


if (isset($_GET["quickadd"]) && !$isEdit) {
    $functions->notificationError(_js($_e["Stock"]), _js($_e["Quick Product Quantity Add Successfully"]), "btn-success");
}

$format = '<div class="form-group">
                    <label class="col-sm-2 control-label">{{label}}</label>
                    <div class="col-sm-10">
                        {{form}}
                    </div>
               </div>';
?>

<script type="text/javascript">

    var curTable = (function (json_data) {

        $self = this;

        /***************/

        this.checkbox_name = "_undefined_";

        this.input_name = "_undefined_";

        this.input_name__extra_class = "";

        this.input_addprice_name = "_undefined_";

        this.slotType = "";

        this.panel_note_footer = "";

        this.panel_heading = "Customize Slots";

        this.always_callback = function () {

            fireSortable();

        };

        this.addSlot_callback = function () {

        };

        /***************/

        this.uniqid = functions.uniqid();

        this.json = (typeof (json_data) == "undefined") ? new Object() : json_data;

        this.objNumber = 0;

        this.initializeCheck = false;

        this.divid = "body";

        this.tbody = $("<tbody></tbody>").attr("id", "tbody_" + this.uniqid);

        this.construct = function () {

            this.objNumber = Number(this.json.length) || 0;

        };

        this.construct();



        this.initializeTable = function (id) {

            if (this.initializeCheck == false) {

                this.initializeCheck = true;

                id = (document.getElementById(id)) ? "#" + id : this.divid;

                $table = $("<table></table>").attr({

                    id: "table_" + this.uniqid,

                    class: "table table-condensed table-responsive"

                });

                $thead = $("<thead></thead>");

                $tr = $("<tr></tr>");

                $tr.append("<th>Name</th>");

                var data = this.json;

                for (x in data) {

                    $tr.append("<th>" + data[x].country + " (" + data[x].name + ") " + "</th>");

                }

                $thead.append($tr);

                $table.append($thead);

                $tbody = this.tbody;

                $table.append($tbody);

                var panel_heading = this.panel_heading;

                var slef_panel_note_footer = this.panel_note_footer;

                $(function () {

                    $panel = $("<div class='panel panel-primary'></div>");

                    $panel.append("<div class='panel-heading'>" + "<h3 class='panel-title'>" + panel_heading + "</h3>" +

                        "</div>");

                    $panel_body = $("<div class='panel-body table-responsive'></div>");

                    $panel_body.append($table);

                    $panel_body.append("<div class='panel_note_footer'>" + slef_panel_note_footer + "</div>");

                    $panel.append($panel_body);

                    $(id).append("<br><hr>");

                    $(id).append($panel);

                });

            }

        };



        this.xhtmText = function (rowID, trCount) {

            return "<div class='input-group input-group-sm slotDiv_" + trCount + "'>" + "<span class='input-group-addon'>" +

                "<input class='slotCheckBox_" + trCount + "'  type='checkbox' value='" + rowID + "' name='" + this.checkbox_name + "[]'>" + "</span>" +

                "<input type='text' value='' name='" + rowID + "-" + this.input_name + "' class='form-control slotInput_" + trCount + " " + this.input_name__extra_class + "'>" + "</div>";

        };





        var trCount = 0;

        this.addSlot = function () {

            if (this.initializeCheck == false) {

                this.initializeTable(this.divid);

            }

            trCount++;

            var rowID = functions.uniqid("rowid");

            $tr = $("<tr></tr>");

            $td = $("<td></td>");

            $xht = this.xhtmText(rowID, trCount);

            $td.append($xht);

            $tr.append($td);

            var data = this.json;

            for (x in data) {

                $td = $("<td></td>");

                $xht = "<div class='input-group input-group-sm'>" +

                    "<span class='input-group-addon'>" + data[x].symbol +

                    "</span>" +

                    "<input type='text' class='form-control' name='" + rowID + "-" + this.input_addprice_name + "[" + data[x].id + "]' >" +

                    "</div>";

                $td.append($xht);

                $tr.append($td);

            }

            this.tbody.append($tr);

            this.addSlot_callback();

            this.always_callback();

        }

    });

    var mscale = new curTable(cdata);

    mscale.divid = "tab_sizes_div";

    mscale.checkbox_name = "<?php echo $product->prefix_scaleCheckBox; ?>";

    mscale.input_name = "<?php echo $product->prefix_scaleName; ?>";

    mscale.input_addprice_name = "<?php echo $product->prefix_scaleCost; ?>";

    var addCharges = new curTable(cdata);

    addCharges.panel_heading = "Additional Charges";

    addCharges.divid = "addCharges_div";

    addCharges.checkbox_name = "<?php echo $product->prefix_addCostCheckBox; ?>";

    addCharges.input_name = "<?php echo $product->prefix_addCostName; ?>";

    addCharges.input_addprice_name = "<?php echo $product->prefix_addCostCost; ?>";


    var mcolors = new curTable(cdata);

    mcolors.divid = "tab_color_div";

    mcolors.checkbox_name = "<?php echo $product->prefix_colorCheckBox; ?>";

    mcolors.input_name = "<?php echo $product->prefix_colorName; ?>";

    mcolors.input_addprice_name = "<?php echo $product->prefix_colorCost; ?>";

    /**/

    mcolors.input_name__extra_class = "color_picker";

    mcolors.slotType = "color_picker_input";

    mcolors.addSlot_callback = function () {

        color_picker();

    };


    $(function () {
        fireSortable();
    });

    var fixHelperModified = function (e, tr) {

        var $originals = tr.children();

        var $helper = tr.clone();

        $helper.children().each(function (index) {

            $(this).width($originals.eq(index).width())

        });

        return $helper;

    }, updateIndex = function (e, ui) {

        $('td.index', ui.item.parent()).each(function (i) {

            $(this).html(i + 1);

        });

    };

    function fireSortable() {

        $(".tableSort tbody").sortable({

            helper: fixHelperModified, axis: 'y', placeholder: "ui-state-highlight",

            stop: updateIndex

        }).disableSelection();

    }



    var uniqueId = 1;

    function addWeightSlot() {

        uniqueId = uniqueId + 1;

        var weightVar = '<tr>' +

            '<td>' +

            '<div class="input-group input-group-sm">' +

            '<span class="input-group-addon"><?php echo _js($_e['Size Name']); ?></span>' +

            '<input type="text" class="form-control" value="" name="sizeWeightName[' + uniqueId + ']" placeholder="<?php echo _js($_e['Size Name']); ?>">' +

            '</div>' +

            '</td>' +

            '<td>' +

            '<div class="input-group input-group-sm">' +

            '<span class="input-group-addon"><?php echo _js($_e['Weight In KG']); ?></span>' +

            '<input type="text" class="form-control" value="" name="sizeWeight[' + uniqueId + ']" placeholder="<?php echo _js($_e['Weight In KG']); ?>,e.g: 200,2,0.2">' +

            '</div>' +

            '</td>' +

            '</tr>';



        $('#sizeWeightDiv').append(weightVar);

    }
</script>

<style>
    .ui-state-highlight {
        background: #FAEBCC;
        height: 1.5em;
        line-height: 1.2em;
    }
</style>

<?php
if ($isEdit) {
    echo '<h4 class="sub_heading">' . _uc($_e['Edit Product']) . '</h4>';
} else {
    echo '<h4 class="sub_heading">' . _uc($_e['Add New Product']) . '</h4>';
}
?>

<form method="post" class="form-horizontal ">
    <?php
    $functions->setFormToken('edit_pro');

    if ($isEdit) { ?>

        <input type="hidden" name="edit_pro" value="<?php echo $product->pid; ?>" />

        <?php if (isset($_POST['copy'])) { ?>
            <input type="hidden" name="edit_proSubmit" value="" />
        <?php } else { ?>
            <input type="hidden" name="edit_proSubmit" value="<?php echo $product->pid; ?>" />
        <?php }
    } ?>





    <div class="container-fluid">

        <div class="row">

            <div class="tabbable">

                <ul class="nav nav-tabs tabs_arrow">

                    <li class="active"><a href="#tab_bi"
                            data-toggle="tab"><?php echo _uc($_e['Basic Information']); ?></a></li>


                    <!--<li><a href="#new_tab_categroy" data-toggle="tab"><?php //echo _uc($_e['Category']); ?></a></li>-->

                    <!--<li><a href="#tab_price" data-toggle="tab"><?php //echo _uc($_e['Price']); ?></a></li>-->



                    <?php if ($functions->developer_setting('product_Scale') == '1') { ?>

                        <!--<li><a href="#tab_sizes" data-toggle="tab"><?php //echo _uc($_e['Sizes']); ?></a></li>-->

                    <?php } ?>

                    <?php if ($functions->developer_setting('product_color') == '1') { ?>

                        <!--<li><a href="#tab_colors" data-toggle="tab"><?php //echo _uc($_e['Colors']); ?></a></li>-->

                    <?php } ?>

                    <?php if ($functions->developer_setting("product_related_item")) { ?>

                        <!--<li><a href="#tab_realtedProduct" data-toggle="tab"><?php //echo _uc($_e['Related Products']); ?></a>-->
                        <!--</li>-->

                    <?php } ?>

                    <?php if ($functions->developer_setting("product_relative_item")) { ?>

                        <!--<li><a href="#tab_relativeProduct"-->
                        <!--        data-toggle="tab"><?php //echo _uc($_e['Relative Products']); ?></a></li>-->

                    <?php } ?>

                    <!--<li><a href="#tab_getlookProduct"-->
                    <!--        data-toggle="tab"><?php //echo _uc($_e['Get This Feature Look Products']); ?></a></li>-->

                    <!--<li><a href="#dontForgetToBuy" data-toggle="tab"><?php //echo _uc($_e['Do not forget']); ?></a></li>-->

                    <!--<li><a href="#combine_with" data-toggle="tab"><?php //echo _uc($_e['Combine With Category']); ?></a>-->
                    </li>

                    <li><a href="#tab_images" data-toggle="tab"><?php echo _uc($_e['Images']); ?></a></li>

                    <!--<li><a href="#tab_detail_images" data-toggle="tab"><?php //echo _uc($_e['Detail Images']); ?></a></li>-->
                    
                    <!--<li><a href="#tab_keyword" data-toggle="tab"><?php //echo _uc($_e['Keywords Connection']); ?></a></li>-->
                    
                    <li><a href="#tab_setting" data-toggle="tab"><?php echo _uc($_e['Setting']); ?></a></li>

                </ul>

                <div class="tab-content" style="padding-top: 20px">

                    <div class="tab-pane fade in active container-fluid" id="tab_bi">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Basic Information']); ?></h2>

                        <?php





                        /**

                         * @param $dbF

                         * @return mixed

                         */





                        $lang = $functions->IbmsLanguages();

                        if ($lang != false) {

                            $lang_nonArray = implode(',', $lang);



                            echo <<<HTML

                                      <input type="hidden" name="lang" value="$lang_nonArray" />

                                      <div class="panel-group" id="accordion">

HTML;



                            if ($product->editPid != "") {

                                $editId = $product->editPid;

                                if (isset($_POST['copy'])) {
                                    echo "<input type='hidden' name='editProduct' value='' />";
                                } else {
                                    echo "<input type='hidden' name='editProduct' value='$editId' />";
                                }

                        


                                $qry = "SELECT * FROM `proudct_detail` WHERE `prodet_id` = '$product->editPid'";

                                $editData = $dbF->getRow($qry);



                                $editPName = unserialize($editData['prodet_name']);

                                $editPShortDesc = unserialize($editData['prodet_shortDesc']);

                                $editPTag = unserialize($product->productSettingArray('tags', @$eData));

                                $editPLongDesc = unserialize($product->productSettingArray('ldesc', @$eData));

                                $editSizeChart = unserialize($product->productSettingArray('size_chart', @$eData));

                                $editFeatureIcon = unserialize($product->productSettingArray('featureIcon', @$eData));

                                $editFeaturePoints = unserialize($product->productSettingArray('featurePoints', @$eData));

                                $editSpecification = unserialize($product->productSettingArray('specification', @$eData));



                                $isEdit = true;

                            }



                            for ($i = 0; $i < sizeof($lang); $i++) {

                                if ($i == 0) {

                                    $collapseIn = ' in ';

                                } else {

                                    $collapseIn = '';

                                }

                                $lang[$i];

                                if ($isEdit) {

                                    @$eName = $editPName[$lang[$i]];

                                    @$eTag = $editPTag[$lang[$i]];

                                    @$eShortDesc = $editPShortDesc[$lang[$i]];

                                    @$eLongDesc = $editPLongDesc[$lang[$i]];

                                    @$size_chart = $editSizeChart[$lang[$i]];

                                    @$eEditFeature = $editFeatureIcon[$lang[$i]];

                                    @$eEditFeatureP = $editFeaturePoints[$lang[$i]];

                                    @$specification = $editSpecification[$lang[$i]];

                                } else {

                                    $eName = $eTag = $eShortDesc = $eLongDesc = $eEditFeature = $eEditFeatureP = $specification = $size_chart = "";

                                }











                                echo '<div class="panel panel-default">

                        <div class="panel-heading">

                             <a data-toggle="collapse" data-parent="#accordion" href="#' . $lang[$i] . '">

                                <h4 class="panel-title">

                                    ' . $lang[$i] . '

                                </h4>

                             </a>

                        </div>

                        <div id="' . $lang[$i] . '" class="panel-collapse collapse ' . $collapseIn . '">

                            <div class="panel-body">';

                                $form_fields = array();

                                $form_fields[] = array(

                                    'label' => _uc($_e['Name']),

                                    'name' => "$product->prefix_productBasicInformation[name][$lang[$i]]",

                                    'placeholder' => _uc($_e['Product Name']),

                                    'value' => "$eName",

                                    'type' => 'text',

                                    'class' => 'form-control',

                                );



                                // $form_fields[] = array(

                                //     'label' => _uc($_e['Short Description']),

                                //     'name' => "$product->prefix_productBasicInformation[sdesc][$lang[$i]]",

                                //     'value' => "$eShortDesc",

                                //     'type' => 'textarea',

                                //     'class' => 'form-control ckeditor',

                                // );



                                $form_fields[] = array(

                                    'label' => _uc($_e['Detail Description']),

                                    'name' => "$product->prefix_setting[ldesc][$lang[$i]]",

                                    'value' => "$eLongDesc",

                                    'type' => 'textarea',

                                    'class' => 'form-control ckeditor'

                                );





                                // $form_fields[] = array(

                                //     'label' => _uc($_e['Size Chart']),

                                //     'name' => "$product->prefix_setting[size_chart][$lang[$i]]",

                                //     'value' => "$size_chart",

                                //     'type' => 'textarea',

                                //     'class' => 'form-control ckeditor'

                                // );




                                $functions->print_form($form_fields, $format);

                                echo '



          </div> <!-- panel-body-->

                        </div> <!-- #$lang[$i] -->



                    </div> <!-- .panel-default -->';
                        


                            }

                            echo '</div> <!-- .panel-group -->';

                        }

                        ?>



                    </div>





                    <div class="tab-pane container-fluid fade" id="tab_price">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Price']); ?></h2>
                        
                        <?php
                            // <!--if($isEdit){-->
                            // <!--    $buyingPrice = $editData["buying_price"];-->
                            // <!--}-->
                        ?>
                        <!--<div class="form-group">-->
                        <!--    <label class="col-sm-2 control-label"><?php //echo $_e['Buy In Price']; ?></label>-->
                        <!--    <div class="col-sm-10">-->
                        <!--        <input type="text" pattern="\d+(\.\d+)?" value="<?php //echo $buyingPrice ?>" name="<?php //echo $product->prefix_productBasicInformation . '[buyingPrice]' ?>" class="form-control" placeholder="00.00">-->
                        <!--    </div>-->
                        <!--</div>-->
                        
                        


                        <div> <?php $product->createPricingViewSystem(); ?>

                            <div id="addCharges_div" class="">

                                <?php

                                $product->priceAdditionChargesEdit();

                                if ($functions->developer_setting('check_out_offer') == '1' && $functions->ibms_setting('check_out_offer') == '1') {

                                    $product->priceCheckOutChargesEdit();

                                }

                                ?>

                            </div>

                            <button type="button" class="btn btn-info pull-right" onclick="addCharges.addSlot()">

                                <?php echo _uc($_e['Add Slot']); ?>

                            </button>

                        </div>

                    </div>





           



                    <div class="tab-pane container-fluid fade" id="new_tab_categroy">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Category']); ?></h2>

                        <ul id="nestedlist">

                            <?php



                            ##### Main MENU
                            
                            $css = false;

                            $view_css = '';

                            $mainMenu = $product->menuTypeSingle('main');

                            foreach ($mainMenu as $val) {

                                $insideActive = false;

                                $innerUl = '';

                                $menuId = $val['id'];

                                $text = _n($val['name']);

                                $link = $val['link'];



                                // $underid = $val['under'];
                            
                                $has_inner_level_two_class = '';

                                $inner_level_two = null;

                                $mainMenu2 = $product->menuTypeSingle('main', $menuId);

                                if (!empty($mainMenu2)) {

                                    $has_inner_level_two_class = 'has-sub';

                                    $inner_level_two = true;



                                    $innerUl .= '



<ul>

';

                                    foreach ($mainMenu2 as $val2) {

                                        $innerUl3 = '';

                                        $text = _n($val2['name']);

                                        $menuId2 = $val2['id'];

                                        $link = $val2['link'];

                                        $menuIcon = '';

                                        $active = $val2['active'];





                                        // $underid = $val2['under'];
                            


                                        if ($active == '1') {

                                            $active = 'active';

                                            $insideActive = $css = true;

                                        }



                                        $has_inner_level_three_class = '';



                                        $mainMenu3 = $product->menuTypeSingle('main', $menuId2);

                                        # count the inner level 3 lis
                            



                                        $innerUl3count = ($mainMenu3 == false ? 0 : count($mainMenu3));

                                        $innerUl3 .= ($innerUl3count > 0) ? '<ul>' : '';



                                        if ($innerUl3count > 0) {



                                            foreach ($mainMenu3 as $val3) {
                                                $innerUl4 = '';
                                                $view_css3 = '';

                                                $text3 = _n($val3['name']);

                                                $menuId3 = $val3['id'];

                                                $link3 = $val3['link'];

                                                $menuIcon3 = $val3['icon'];

                                                $active3 = $val3['active'];

                                                if ($active3 == '1') {

                                                    $active3 = 'active';

                                                    $insideActiveThree = true;

                                                }





                                                $has_inner_level_three_class = 'has-sub';









                                                $mainMenu4 = $product->menuTypeSingle('main', $menuId3);
                                                # count the inner level 3 lis
                            
                                                $innerUl4count = ($mainMenu4 == false ? 0 : count($mainMenu4));



                                                $innerUl4 .= ($innerUl4count > 0) ? '<ul>' : '';



                                                if ($innerUl4count > 0) {



                                                    foreach ($mainMenu4 as $val4) {

                                                        $view_css4 = '';

                                                        $text4 = _n($val4['name']);

                                                        $menuId4 = $val4['id'];

                                                        $link4 = $val4['link'];

                                                        $menuIcon4 = $val4['icon'];

                                                        $active4 = $val4['active'];

                                                        if ($active4 == '1') {

                                                            $active4 = 'active';

                                                            $insideActiveThree = true;

                                                        }





                                                        $has_inner_level_three_class = 'has-sub';



                                                        $innerUl4 .= '



<li><input type="checkbox" name="cats[]" value=' . $menuId4 . '>

' . $text4 . '



</li>





';



                                                    }



                                                }



                                                $innerUl4 .= ($innerUl4count > 0) ? '</ul><!--3rd array End-->' : '';








                                                $innerUl3 .= '



<li><input type="checkbox" name="cats[]" value=' . $menuId3 . '>

' . $text3 . '

' . $innerUl4 . '

</li>





';



                                            }



                                        }



                                        $innerUl3 .= ($innerUl3count > 0) ? '</ul><!--3rd array End-->' : '';
                            
                                        if ($innerUl3) {




                                            $image_div = '';



                                        } else {

                                            $image_div = '';

                                        }



                                        $innerUl .= '



<li><input type="checkbox" name="cats[]" value=' . $menuId2 . '>



' . $text . '



<span>



' . $image_div . '



</span>' . $innerUl3 . '



</li>

';

                                    }



                                    $innerUl .= "</ul><!--2nd array End-->";

                                }



                                $text = _n($val['name']);



                                $link = $val['link'];

                                $menuIcon = $val['icon'];

                                if (!empty($menuIcon)) {

                                    $image_div = '<img src="' . $menuIcon . '" alt="" loading="lazy">';

                                } else {

                                    $image_div = '';

                                }

                                $active = $val['active'];



                                if ($active == '1' || $insideActive) {



                                    if (!empty($mainMenu2)) {

                                        $css = true;

                                    }

                                    $active = 'active';

                                }

                                echo '

<li><input type="checkbox" name="cats[]" value=' . $menuId . '>



' . $text . '







' . $innerUl . '



</li>

';

                            }



                            echo '';



                            $cat = $selectedNode = $product->productSelectedNode();

                            $trim_Cat = rtrim($cat, ',');



                            ?>



                            <script>

                                $(document).ready(function () {

                                    var abc = '<?php echo $trim_Cat; ?>';

                                    console.log(abc);

                                });

                            </script>



                        </ul>





                        <div>


                        </div>

                    </div>









                    <div class="tab-pane container-fluid fade" id="tab_sizes">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Sizes']); ?> </h2>



                        <div class=""><?php $product->createListOfScales(); ?></div>



                        <div id="tab_sizes_div" class=""></div>

                        <button type="button" class="btn btn-info pull-right"
                            onclick="mscale.addSlot()"><?php echo _uc($_e['Add Slot']); ?></button>

                        <div class="clearfix"></div>

                        <br>



                        <div class="margin-10 bg-info">&nbsp;</div>

                        <br>



                        <div class=""><?php $product->createListOfCustomSize(); ?></div>



                        <div class="clearfix"></div>

                        <br>



                        <div class="margin-10 bg-info">&nbsp;</div>

                        <br>



                        <h2 class="tab_heading"><?php echo _uc($_e['Product Sizes Weight']); ?></h2>

                        <small><?php echo _n($_e['Enter Exact name that use in product Size name, IF Size Name not match Product Weight will not work in shipping and It will use product default weight']); ?>
                        </small>

                        <div class=""><?php $product->createListOfScalesWeight(); ?></div>

                    </div>





                    <div class="tab-pane container-fluid fade" id="tab_colors">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Color']); ?></h2>



                        <div class="">

                            <?php $product->createListOfColor(); ?>

                        </div>

                        <div id="tab_color_div" class=""></div>

                        <button type="button" class="btn btn-info pull-right" onclick="mcolors.addSlot()">

                            <?php echo _uc($_e['Add Slot']); ?>

                        </button>

                    </div>





                    <?php

                    if ($functions->developer_setting("product_related_item") == "1" || $functions->developer_setting("add_free_gift_in_cart") == "1") {

                        $relatedData = $product->productF->productActiveSql('prodet_id,prodet_name', "prodet_id != '" . $product->pid . "'");


                        foreach ($relatedData as $val) {

                            $name = $functions->unserializeTranslate($val['prodet_name']);
                            $pro_img = $product->productF->getProductSingleImage($val['prodet_id']);
                            if ($pro_img !== false) {
                                $img_pa = WEB_URL . '/images/' . $pro_img['image'];
                                $img = '<img src="' . $img_pa . '" loading="lazy">';
                                $product_array[$val['prodet_id']]['image'] = $img_pa;
                                $product_array[$val['prodet_id']]['imgSrc'] = $pro_img['image'];
                            }
                            
                            $product_array[$val['prodet_id']]['name'] = $name;

                        }

                        ?>



                        <!--Related Product-->

                        <div class="tab-pane container-fluid fade" id="tab_realtedProduct">


                            <!--Related Products-->



                            <h2 class="tab_heading"><?php echo _uc($_e['Related Products']); ?></h2>

                            <?php

                            $relatedmodel = unserialize($product->productSettingArray('related', @$eData));
                            
                            if (!is_array($relatedmodel)) {
                                $relatedmodel = [];
                            }
                            
                            if (!empty($relatedmodel)) {
                                $countRelated = 1;
                                foreach ($relatedmodel as $key => $value) {
                                    if (array_key_exists($value, $product_array)) {
                                        echo $countRelated . '. ' . $product_array[$value]['name'] . '<br>';
                                    }
                                    $countRelated++;
                                }
                            }

                            echo '<select name="setting[related][]" class="form-control test" id="example-getting-started1" style="height:300px" multiple="">';
                                foreach ($product_array as $key => $value) {
                                    @$selectrelate = (in_array($key, $relatedmodel)) ? 'selected' : '';
                                    $smlImg1 = $functions->resizeImage($value['imgSrc'], '60', '70', false);
                                    echo '<option data-img="' . $smlImg1 . '" value="' . $key . '" ' . @$selectrelate . '>' . $value['name'] . '</option>';
                                }
                            echo '</select>';

                            $form_fields = array();

                            $model = unserialize($product->productSettingArray('related', @$eData));

                            $form_fields[] = array(

                                'id' => 'example-getting-started1',

                                'label' => _uc($_e['Products']),

                                'name' => $product->prefix_setting . '[related][]',

                                'array' => $product_array,

                                'type' => 'select',

                                'multiple' => 'multiple',

                                'select' => $model,

                                'multi' => 'true',

                                'class' => 'form-control',

                                'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>',

                                "data" => "style='height:300px'"



                            );
                        
                            ?>



                            <h2 class="tab_heading"><?php echo _uc($_e['Free Gift Product']); ?></h2>

                            <small
                                class=""><strong><?php echo _uc($_e['When this product exist in cart then which free gift add in cart, please select']); ?></strong></small><br><br>

                            <?php

                            $freeGiftmodel = unserialize($product->productSettingArray('free_gift', @$eData));
                            
                            if(!is_array($freeGiftmodel)){
                                $freeGiftmodel = [];
                            }
                            
                            if (!empty($freeGiftmodel)) {
                                $countfreeGift = 1;
                                foreach ($freeGiftmodel as $key => $value) {
                                    if (array_key_exists($value, $product_array)) {
                                        echo $countfreeGift . '. ' . $product_array[$value]['name'] . '<br>';
                                    }
                                    $countfreeGift++;
                                }
                            }

                            echo '<select name="setting[free_gift][]" class="form-control test" id="example-getting-started2" style="height:300px" multiple="">';
                            foreach ($product_array as $key => $value) {
                                @$selectfree = (in_array($key, $freeGiftmodel)) ? 'selected' : '';
                                $smlImg2 = $functions->resizeImage($value['imgSrc'], '60', '70', false);
                                echo '<option data-img="' . $smlImg2 . '" value="' . $key . '" ' . @$selectfree . '>' . $value['name'] . '</option>';
                            }
                            echo '</select>';

                            $form_fields = array();

                            @$model = unserialize($product->productSettingArray('free_gift', $eData));

                            $form_fields[] = array(

                                'id' => 'example-getting-started2',

                                'label' => _uc($_e['Products']),

                                'name' => $product->prefix_setting . '[free_gift][]',

                                'array' => $product_array,

                                'type' => 'select',

                                'multiple' => 'multiple',

                                'select' => $model,

                                'multi' => 'false',

                                'class' => 'form-control',
                        
                            );

                        
                            ?>

                        </div>

                    <?php } ?>



                    <!--Product Images-->


                    <?php

                    if ($functions->developer_setting("product_relative_item") == "1") {

                        $relatedData = $product->productF->productActiveSql('prodet_id,prodet_name', "prodet_id != '" . $product->pid . "'");


                        foreach ($relatedData as $val) {

                            $name = $functions->unserializeTranslate($val['prodet_name']);
                            $pro_img = $product->productF->getProductSingleImage($val['prodet_id']);
                            if ($pro_img !== false) {
                                $img_pa = WEB_URL . '/images/' . $pro_img['image'];
                                $img = '<img src="' . $img_pa . '" loading="lazy">';
                                $product_array[$val['prodet_id']]['image'] = $img_pa;
                                $product_array[$val['prodet_id']]['imgSrc'] = $pro_img['image'];
                            }
                            $product_array[$val['prodet_id']]['name'] = $name;
                        }

                        ?>



                        <!--Related Product-->

                        <div class="tab-pane container-fluid fade" id="tab_relativeProduct">

                            <h2 class="tab_heading"><?php echo _uc($_e['Relative Products']); ?></h2>

                            <?php

                            $relativedmodel = unserialize($product->productSettingArray('relative', @$eData));
                            
                            if(!is_array($relativedmodel)){
                                $relativedmodel = [];
                            }
                            
                            if (!empty($relativedmodel)){
                                $countRelated = 1;
                                foreach ($relativedmodel as $key => $value) {
                                    if (array_key_exists($value, $product_array)) {
                                        echo $countRelated . '. ' . $product_array[$value]['name'] . '<br>';
                                    }
                                    $countRelated++;
                                }
                            }

                            echo '<select name="setting[relative][]" class="form-control test" id="example-getting-started1" style="height:300px" multiple="">';

                            foreach ($product_array as $key => $value) {
                                @$selectrelate = (in_array($key, $relativedmodel)) ? 'selected' : '';
                                $smlImg1 = $functions->resizeImage($value['imgSrc'], '60', '70', false);
                                echo '<option data-img="' . $smlImg1 . '" value="' . $key . '" ' . @$selectrelate . '>' . $value['name'] . '</option>';
                            }

                            echo '</select>';

                            $form_fields = array();

                            @$model = unserialize($product->productSettingArray('relative', $eData));

                            $form_fields[] = array(

                                'id' => 'example-getting-started1',

                                'label' => _uc($_e['Products']),

                                'name' => $product->prefix_setting . '[relative][]',

                                'array' => $product_array,

                                'type' => 'select',

                                'multiple' => 'multiple',

                                'select' => $model,

                                'multi' => 'true',

                                'class' => 'form-control',

                                'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>',

                                "data" => "style='height:300px'"



                            );


                            ?>




                        </div>

                    <?php } ?>





                    <!--GET LOOK PRODUCTS-->


                    <div class="tab-pane container-fluid fade" id="tab_getlookProduct">

                        <h2 class="tab_heading"><?php echo _uc($_e['Get This Feature Look Products']); ?></h2>

                        <?php

                        
                        $getLookmodel = unserialize($product->productSettingArray('getlook', @$eData));
                        
                        if(!is_array($getLookmodel)){
                            $getLookmodel = [];
                        }
                        if (!empty($getLookmodel)) {
                            $countGetLook = 1;
                            foreach ($getLookmodel as $key => $value) {
                                if (array_key_exists($value, $product_array)) {
                                    echo $countGetLook . '. ' . $product_array[$value]['name'] . '<br>';
                                }
                                $countGetLook++;
                            }
                        }

                        echo '<select name="setting[getlook][]" class="form-control test" id="example-getting-started" style="height:300px" multiple="">';
                        foreach ($product_array as $key => $value) {
                            @$select = (in_array($key, $getLookmodel)) ? 'selected' : '';
                            $smlImg = $functions->resizeImage($value['imgSrc'], '60', '70', false);
                            echo '<option data-img="' . $smlImg . '" value="' . $key . '" ' . @$select . '>' . $value['name'] . '</option>';
                        }
                        echo '</select>';

                        $form_fields = array();

                        @$model = unserialize($product->productSettingArray('getlook', $eData));

                        $form_fields[] = array(

                            'id' => 'example-getting-started',

                            'label' => _uc($_e['Products']),

                            'name' => $product->prefix_setting . '[getlook][]',

                            'array' => $product_array,

                            'type' => 'select',

                            'multiple' => 'multiple',

                            'select' => $model,

                            'multi' => 'true',

                            'class' => 'form-control col-md-12',

                            'format' => '<label class="checkbox-inline">{{form}} {{option}}

                                    </label>',



                        );

                        
                        ?>



                    </div>


                    <div class="tab-pane container-fluid fade" id="dontForgetToBuy">

                        <h2 class="tab_heading"><?php echo _uc($_e['Do Not Forget To Buy Products']); ?></h2>

                        <?php

                        $getLookmodel = unserialize($product->productSettingArray('dontForget', @$eData));
                        if(!is_array($getLookmodel)){
                            $getLookmodel = [];
                        }
                        if(!empty($getLookmodel)){
                            $countGetLook = 1;
                            foreach ($getLookmodel as $key => $value) {
                                if (array_key_exists($value, $product_array)) {
                                    echo $countGetLook . '. ' . $product_array[$value]['name'] . '<br>';
                                }
                                $countGetLook++;
                            }
                        }
                        echo '<select name="setting[dontForget][]" class="form-control test" id="example-getting-started" style="height:300px" multiple="">';
                        foreach ($product_array as $key => $value) {
                            @$select = (in_array($key, $getLookmodel)) ? 'selected' : '';
                            $smlImg = $functions->resizeImage($value['imgSrc'], '60', '70', false);
                            echo '<option data-img="' . $smlImg . '" value="' . $key . '" ' . @$select . '>' . $value['name'] . '</option>';
                        }
                        echo '</select>';

                        $form_fields = array();

                        @$model = unserialize($product->productSettingArray('dontForget', $eData));

                        $form_fields[] = array(

                            'id' => 'example-getting-started',

                            'label' => _uc($_e['Products']),

                            'name' => $product->prefix_setting . '[dontForget][]',

                            'array' => $product_array,

                            'type' => 'select',

                            'multiple' => 'multiple',

                            'select' => $model,

                            'multi' => 'true',

                            'class' => 'form-control col-md-12',

                            'format' => '<label class="checkbox-inline">{{form}} {{option}}

                                    </label>',



                        );

                        ?>


                        <!--donot popup form-->
                        <h2 class="tab_heading"><?php echo _uc($_e['Do Not Forget To Buy Products Popup']); ?></h2>

                        <?php

                        $getLookmodel = unserialize($product->productSettingArray('dontForgetPopUp', @$eData));
                        
                        if(!is_array($getLookmodel)){
                            $getLookmodel = [];
                        }
                        
                        if(!empty($getLookmodel)){
                            $countGetLook = 1;
                            foreach ($getLookmodel as $key => $value) {
                                if (array_key_exists($value, $product_array)) {
                                    echo $countGetLook . '. ' . $product_array[$value]['name'] . '<br>';
                                }
                                $countGetLook++;
                            }
                        }
                        
                        echo '<select name="setting[dontForgetPopUp][]" class="form-control test" id="example-getting-started" style="height:300px" multiple="">';
                        foreach ($product_array as $key => $value) {
                            @$select = (in_array($key, $getLookmodel)) ? 'selected' : '';
                            $smlImg = $functions->resizeImage($value['imgSrc'], '60', '70', false);
                            echo '<option data-img="' . $smlImg . '" value="' . $key . '" ' . @$select . '>' . $value['name'] . '</option>';
                        }


                        echo '</select>';

                        $form_fields = array();

                        @$model = unserialize($product->productSettingArray('dontForgetPopUp', $eData));

                        $form_fields[] = array(

                            'id' => 'example-getting-started',

                            'label' => _uc($_e['Products']),

                            'name' => $product->prefix_setting . '[dontForgetPopUp][]',

                            'array' => $product_array,

                            'type' => 'select',

                            'multiple' => 'multiple',

                            'select' => $model,

                            'multi' => 'true',

                            'class' => 'form-control col-md-12',

                            'format' => '<label class="checkbox-inline">{{form}} {{option}}

                                    </label>',



                        );

                        ?>



                    </div>





                    <!--GET LOOK PRODUCT END-->


                    <div class="tab-pane container-fluid fade" id="combine_with">
                        <h2 class="tab_heading"><?php echo _uc('Combine With Categories'); ?></h2>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php $dbF->hardWords('Category 1'); ?></label>
                            <div class="col-sm-10">
                                <?php
                                @$combine = unserialize($product->productSettingArray('combineWith', $eData));
                                ?>
                                <select name="setting[combineWith][]" class="form-control">
                                    <option selected disabled><?php $dbF->hardWords('Select Category'); ?></option>
                                    <?php
                                    ##### Main MENU
                                    $mainMenu = $product->menuTypeSingle('main');
                                    foreach ($mainMenu as $val) {
                                        $innerUl = '';
                                        $menuId = $val['id'];
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        $mainMenu2 = $product->menuTypeSingle('main', $menuId);

                                        if (!empty($mainMenu2)) {

                                            foreach ($mainMenu2 as $val2) {
                                                $innerUl3 = '';
                                                $text = _n($val2['name']);
                                                $menuId2 = $val2['id'];
                                                $link = $val2['link'];
                                                $active = $val2['active'];

                                                $mainMenu3 = $product->menuTypeSingle('main', $menuId2);
                                                # count the inner level 3 lis
                                                $innerUl3count = ($mainMenu3 == false ? 0 : count($mainMenu3));
                                                //$innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';
                                    
                                                if ($innerUl3count > 0) {
                                                    foreach ($mainMenu3 as $val3) {
                                                        $text3 = _n($val3['name']);
                                                        $menuId3 = $val3['id'];
                                                        $link3 = $val3['link'];
                                                        $menuIcon3 = $val3['icon'];
                                                        $active3 = $val3['active'];

                                                        $selected3 = (@$combine[0] == $menuId3) ? 'selected' : '';

                                                        $innerUl3 .= '<option value=' . $menuId3 . ' ' . $selected3 . '>- -' . $text3 . '</option>';

                                                    }
                                                }

                                                $selected2 = (@$combine[0] == $menuId2) ? 'selected' : '';

                                                $innerUl .= '<option value=' . $menuId2 . ' ' . $selected2 . '>' . $text . '</option>' . $innerUl3;

                                            }
                                        }
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        echo $innerUl;

                                    }
                                    echo '';
                                    $cat = $selectedNode = $product->productSelectedNode();
                                    $trim_Cat = rtrim($cat, ',');
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php $dbF->hardWords('Category 2'); ?></label>
                            <div class="col-sm-10">
                                <select name="setting[combineWith][]" class="form-control">
                                    <option selected disabled><?php $dbF->hardWords('Select Category'); ?></option>
                                    <?php
                                    ##### Main MENU
                                    $mainMenu = $product->menuTypeSingle('main');
                                    foreach ($mainMenu as $val) {
                                        $innerUl = '';
                                        $menuId = $val['id'];
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        $mainMenu2 = $product->menuTypeSingle('main', $menuId);

                                        if (!empty($mainMenu2)) {

                                            foreach ($mainMenu2 as $val2) {
                                                $innerUl3 = '';
                                                $text = _n($val2['name']);
                                                $menuId2 = $val2['id'];
                                                $link = $val2['link'];
                                                $active = $val2['active'];

                                                $mainMenu3 = $product->menuTypeSingle('main', $menuId2);
                                                # count the inner level 3 lis
                                                $innerUl3count = ($mainMenu3 == false ? 0 : count($mainMenu3));
                                                //$innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';
                                    
                                                if ($innerUl3count > 0) {
                                                    foreach ($mainMenu3 as $val3) {
                                                        $text3 = _n($val3['name']);
                                                        $menuId3 = $val3['id'];
                                                        $link3 = $val3['link'];
                                                        $menuIcon3 = $val3['icon'];
                                                        $active3 = $val3['active'];

                                                        $selected3 = (@$combine[1] == $menuId3) ? 'selected' : '';
                                                        $innerUl3 .= '<option value=' . $menuId3 . ' ' . $selected3 . '>- -' . $text3 . '</option>';

                                                    }
                                                }

                                                $selected2 = (@$combine[1] == $menuId2) ? 'selected' : '';
                                                $innerUl .= '<option value=' . $menuId2 . ' ' . $selected2 . '>' . $text . '</option>' . $innerUl3;

                                            }
                                        }
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        echo $innerUl;

                                    }
                                    echo '';
                                    $cat = $selectedNode = $product->productSelectedNode();
                                    $trim_Cat = rtrim($cat, ',');
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php $dbF->hardWords('Category 3'); ?></label>
                            <div class="col-sm-10">
                                <select name="setting[combineWith][]" class="form-control">
                                    <option selected disabled><?php $dbF->hardWords('Select Category'); ?></option>
                                    <?php
                                    ##### Main MENU
                                    $mainMenu = $product->menuTypeSingle('main');
                                    foreach ($mainMenu as $val) {
                                        $innerUl = '';
                                        $menuId = $val['id'];
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        $mainMenu2 = $product->menuTypeSingle('main', $menuId);

                                        if (!empty($mainMenu2)) {

                                            foreach ($mainMenu2 as $val2) {
                                                $innerUl3 = '';
                                                $text = _n($val2['name']);
                                                $menuId2 = $val2['id'];
                                                $link = $val2['link'];
                                                $active = $val2['active'];

                                                $mainMenu3 = $product->menuTypeSingle('main', $menuId2);
                                                # count the inner level 3 lis
                                                $innerUl3count = ($mainMenu3 == false ? 0 : count($mainMenu3));
                                                //$innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';
                                    
                                                if ($innerUl3count > 0) {
                                                    foreach ($mainMenu3 as $val3) {
                                                        $text3 = _n($val3['name']);
                                                        $menuId3 = $val3['id'];
                                                        $link3 = $val3['link'];
                                                        $menuIcon3 = $val3['icon'];
                                                        $active3 = $val3['active'];
                                                        $selected3 = (@$combine[2] == $menuId3) ? 'selected' : '';
                                                        $innerUl3 .= '<option value=' . $menuId3 . ' ' . $selected3 . '>- -' . $text3 . '</option>';

                                                    }
                                                }
                                                $selected2 = (@$combine[2] == $menuId2) ? 'selected' : '';
                                                $innerUl .= '<option value=' . $menuId2 . ' ' . $selected2 . '>' . $text . '</option>' . $innerUl3;

                                            }
                                        }
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        echo $innerUl;

                                    }
                                    echo '';
                                    $cat = $selectedNode = $product->productSelectedNode();
                                    $trim_Cat = rtrim($cat, ',');
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php $dbF->hardWords('Category 4'); ?></label>
                            <div class="col-sm-10">
                                <select name="setting[combineWith][]" class="form-control">
                                    <option selected disabled><?php $dbF->hardWords('Select Category'); ?></option>
                                    <?php
                                    ##### Main MENU
                                    $mainMenu = $product->menuTypeSingle('main');
                                    foreach ($mainMenu as $val) {
                                        $innerUl = '';
                                        $menuId = $val['id'];
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        $mainMenu2 = $product->menuTypeSingle('main', $menuId);

                                        if (!empty($mainMenu2)) {

                                            foreach ($mainMenu2 as $val2) {
                                                $innerUl3 = '';
                                                $text = _n($val2['name']);
                                                $menuId2 = $val2['id'];
                                                $link = $val2['link'];
                                                $active = $val2['active'];

                                                $mainMenu3 = $product->menuTypeSingle('main', $menuId2);
                                                # count the inner level 3 lis
                                                $innerUl3count = ($mainMenu3 == false ? 0 : count($mainMenu3));
                                                //$innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';
                                    
                                                if ($innerUl3count > 0) {
                                                    foreach ($mainMenu3 as $val3) {
                                                        $text3 = _n($val3['name']);
                                                        $menuId3 = $val3['id'];
                                                        $link3 = $val3['link'];
                                                        $menuIcon3 = $val3['icon'];
                                                        $active3 = $val3['active'];
                                                        $selected3 = (@$combine[3] == $menuId3) ? 'selected' : '';
                                                        $innerUl3 .= '<option value=' . $menuId3 . ' ' . $selected3 . '>- -' . $text3 . '</option>';

                                                    }
                                                }
                                                $selected2 = (@$combine[3] == $menuId2) ? 'selected' : '';
                                                $innerUl .= '<option value=' . $menuId2 . ' ' . $selected2 . '>' . $text . '</option>' . $innerUl3;

                                            }
                                        }
                                        $text = _n($val['name']);
                                        $link = $val['link'];
                                        echo $innerUl;

                                    }
                                    echo '';
                                    $cat = $selectedNode = $product->productSelectedNode();
                                    $trim_Cat = rtrim($cat, ',');
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="tab-pane container-fluid fade" id="tab_images">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Images']); ?></h2>

                        <small><?php echo _n($_e["Use These name in Alt, main: main image, And Other all image enter there alt"]); ?>

                            <br> <?php echo _n($_e["Product Main Image Size : 230x380, Detail Image Size:475x700"]); ?>

                        </small>



                        <input type="hidden" id="AjaxFileNewId" name="ProductNewId"
                            value="<?php echo $product->pid; ?>">

                        <input type="hidden" id="AjaxFileNewPage" value="product">



                        <div id="dropbox">



                            <?php

                            // if product edit
                            
                            if ($isEdit && !isset($_POST['copy'])) {

                                $product->productEditImages();

                            }

                            ?>

                            <style>
                                #dropbox .preview {

                                    height: 255px !important;

                                    padding: 4px;

                                    background: #eee;

                                }

                                .dropboxDetail .progressHolder.album {
                                    height: auto !important;
                                    position: relative !important;
                                }

                                /*albumPreview*/
                                #dropbox .progressHolder.album {

                                    height: 80px !important;
                                    padding: 5px !importan;

                                }
                            </style>

                        </div>

                        <span class="message">

                            <?php echo _uc($_e['Drop images here to upload.']); ?>

                            <br />

                            <i>

                                (<?php echo _fc($_e['they will only be visible to you']); ?>)

                            </i>

                        </span>

                    </div>


                    <div class="tab-pane container-fluid fade" id="tab_detail_images">

                        <h2 class="tab_heading"><?php echo _uc($_e['Product Images Detail']); ?></h2>

                        <small><?php echo _n($_e["Use These name in Alt, main: main image, And Other all image enter there alt"]); ?>

                            <br> <?php echo _n($_e["Product Main Image Size : 230x380, Detail Image Size:475x700"]); ?>

                        </small>



                        <input type="hidden" id="AjaxFileNewIdDetail" name="ProductNewId4"
                            value="<?php echo $product->pid; ?>">

                        <input type="hidden" id="AjaxFileNewPageDetail" value="productDetail">



                        <div id="dropboxDetail" class="dropboxDetail">



                            <?php

                            // if product edit
                            
                            if ($isEdit && !isset($_POST['copy'])) {

                                $product->productEditDetailImages();

                            }

                            ?>

                            <style>
                                #dropbox .preview {

                                    height: 255px !important;

                                    padding: 4px;

                                    background: #eee;

                                }




                                #dropbox .progressHolder.album {

                                    height: 80px !important;

                                    padding: 5px;

                                }
                            </style>

                        </div>

                        <span class="messageDetail">

                            <?php echo _uc($_e['Drop images here to upload.']); ?>

                            <br />

                            <i>

                                (<?php echo _fc($_e['they will only be visible to you']); ?>)

                            </i>

                        </span>

                    </div>

                    <!--Keyword Connection-->
                    
                    <div class="tab-pane container-fluid fade" id="tab_keyword">
                        <div class="col-md-6">
                            <h2 class="tab_heading"><?php echo _uc($_e['Select Product Keyword']); ?></h2>
                            
                            <select name="setting[productKeyword]" class="form-control">
                                <option value="">Select</option>
                            <?php
                                $keywords = $functions->ibms_setting('productKeywords');
                                $keywords = unserialize($keywords);
                                
                                if($isEdit){
                                    $productKeyword = $product->productSettingArray('productKeyword', $eData);
                                }
                                
                                foreach($keywords as $key => $keyword){
                                    $select = '';
                                    if($isEdit){
                                        $select = ($keyword === $productKeyword) ? 'selected' : '';
                                    }
                                    echo '<option value="' . $keyword .'" ' . $select . '>' . $keyword . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <h2 class="tab_heading"><?php echo _uc($_e['Select Connection Keywords']); ?></h2>
                            
                            <select name="setting[productConnectKeywords][]" class="form-control test2" id="example-getting-started_without_img2" style="height:300px" multiple="">
                                <?php
                                    if($isEdit){
                                        $connectKeywords = $product->productSettingArray('productConnectKeywords', $eData);
                                        $connectKeywords = explode(',', $connectKeywords);
                                    }
                    
                                    foreach($keywords as $key => $keyword){
                                        $select = '';
                                        if($isEdit){
                                            $select = (in_array($keyword, $connectKeywords)) ? 'selected' : '';
                                        }
                                        echo '<option value="' . $keyword .'" ' . $select . '>' . $keyword . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--Keyword Connection-->
                    
                    
                    <!--Product Settings-->

                    <div class="tab-pane container-fluid fade" id="tab_setting">

                        <?php
                        // if ($functions->developer_setting('product_tag') == '1') {
                        //     $tagId = array(
                        //         array(
                        //             'name' => $_e['Surprise']
                        //         ),
                        //         array(
                        //             'name' => $_e['Campaign']
                        //         ),
                        //         array(
                        //             'name' => $_e['Best Seller']
                        //         ),
                        //         array(
                        //             'name' => $_e['New Arrival']
                        //         ),
                        //     );
                            ?>


                            <!--<h2 class="tab_heading"><?php //echo _uc($_e['Products Tags']); ?></h2>-->
                            <?php


                        //     $getLookmodel = unserialize($product->productSettingArray('productTags', @$eData));
                            
                        //     if(!is_array($getLookmodel)){
                        //         $getLookmodel = [];
                        //     }
                            
                        //     echo '<select name="setting[productTags][]" class="form-control test2" id="example-getting-started_without_img" style="height:300px" multiple="">';

                        //     foreach ($tagId as $key => $value) {
                        //         $key__ = $value['name'];
                        //         @$select = (in_array($key__, $getLookmodel)) ? 'selected' : '';
                        //         echo '<option  value="' . $key__ . '" ' . @$select . '>' . $key__ . '</option>';
                        //     }

                        //     echo '</select>';
                        //     $customTags = unserialize($product->productSettingArray('productCustomTags', @$eData));
                        //     $customTagsString = is_array($customTags) ? implode(',', $customTags) : '';
                        //     echo '<input type="text" id="p_tags" value="'.$customTagsString.'" class="form-control" name="setting[productCustomTags][]" placeholder="Type in custom tags for product and hit enter">';
                        // } 
                        ?>



                        <h2 class="tab_heading">Product Setting</h2>

                        <?php

                        $form_fields = array();



                        // Form Publish or Draft
                        
                        $val = '0';

                        if ($isEdit) {

                            if ($product->productSettingArray('publicAccess', $eData)) {

                                $val = '1';

                            }

                        }

                        $valFormTemp = $val;

                        $form_fields[] = array(

                            "label" => $_e['Public Access'],

                            'type' => 'checkbox',

                            'value' => "$valFormTemp",

                            'select' => "1",

                            'format' => '<div class="make-switch" data-off="warning" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">

                            {{form}}

                            <input type="hidden" name="' . $product->prefix_setting . '[publicAccess]" class="checkboxHidden" value="' . $val . '" />

                        </div>'

                        );





                        // Form Publish or Draft
                        
                        $val = '0';

                        if ($isEdit) {

                            if ($product->productSettingArray('freeGift', $eData)) {

                                $val = '1';

                            }

                        }

                        $valFormTemp = $val;

                        // $form_fields[] = array(

                        //     "label" => $_e['Free Gift Product'],

                        //     'type' => 'checkbox',

                        //     'value' => "$valFormTemp",

                        //     'select' => "1",

                        //     'format' => '<div class="make-switch" data-off="warning" data-on="success" data-on-label="' . _uc($_e['Yes']) . '" data-off-label="' . _uc($_e['No']) . '">

                        //     {{form}}

                        //     <input type="hidden" name="' . $product->prefix_setting . '[freeGift]" class="checkboxHidden" value="' . $val . '" />

                        // </div>'

                        // );                       
                        
                        $val = '1';

                        if ($isEdit) {

                            if ($product->productSettingArray('listing', $eData)) {

                                $val = $product->productSettingArray('listing', $eData);

                            }

                        }
                        
                        $valFormTemp = $val;
                         
                        $form_fields[] = array(

                            "label" => $_e['Listing'],

                            'type' => 'checkbox',

                            'value' => "$valFormTemp",

                            'select' => "1",

                            'format' => '<div class="make-switch" data-off="warning" data-on="success" data-on-label="' . _uc($_e['On']) . '" data-off-label="' . _uc($_e['Off']) . '">

                            {{form}}

                            <input type="hidden" name="' . $product->prefix_productBasicInformation . '[listing]" class="checkboxHidden" value="' . $val . '" />

                        </div>'

                        );





                        //Product Slug
                        
                        $slug = $product->pid;
                        if ($isEdit) {

                            $slug = $editData['slug'];

                        }

                        $form_fields[] = array(

                            'label' => _uc($_e['Slug']),

                            'name' => $product->prefix_productBasicInformation . '[slug]',

                            'placeholder' => _n($_e['Slug']),

                            'value' => "$slug",

                            'type' => 'text',

                            //'pattern' => '[A-Za-z0-9-_+]{1,150}',
                            'pattern' => '[A-Za-z0-9_+\-\x2B]{1,150}',

                            'class' => 'form-control',

                        );



                        //Product Model No
                        
                        $model = "";

                        if ($isEdit) {

                            $model = $product->productSettingArray('Model', $eData);

                        }

                        // $form_fields[] = array(

                        //     'label' => _uc($_e['Model']),

                        //     'name' => $product->prefix_setting . '[Model]',

                        //     'placeholder' => _n($_e['Model No']),

                        //     'value' => "$model",

                        //     'type' => 'text',

                        //     'class' => 'form-control',

                        // );









                        //Product Label
                        
                        $label = "";

                        if ($isEdit) {

                            $label = $product->productSettingArray('label', $eData);

                        }

                        // $form_fields[] = array(

                        //     'label' => _uc($_e['Label']),

                        //     'name' => $product->prefix_setting . '[label]',

                        //     'placeholder' => _n($_e['Label']),

                        //     'value' => "$label",

                        //     'type' => 'text',

                        //     'class' => 'form-control',

                        // );



                        //Product Video Link
                        
                        $label = "";

                        if ($isEdit) {

                            $label = $product->productSettingArray('video', $eData);

                        }

                        // $form_fields[] = array(

                        //     'label' => _uc($_e['Video Link']),

                        //     'name' => $product->prefix_setting . '[video]',

                        //     'placeholder' => _n($_e['Video Link']),

                        //     'value' => "$label",

                        //     'type' => 'url',

                        //     'class' => 'form-control',

                        // );





                        //Product Launch Date
                        
                        $label = "";

                        if ($isEdit) {

                            $label = $product->productSettingArray('launchDate', $eData);

                        }

                        // $form_fields[] = array(

                        //     'label' => _uc($_e['Product Launch Date']),

                        //     'name' => $product->prefix_setting . '[launchDate]',

                        //     'placeholder' => _n($_e['Launch Date : leave blank if you want to Publish Now']),

                        //     'value' => "$label",

                        //     'type' => 'text',

                        //     'class' => 'form-control datepicker',

                        // );





                        //Product Default Weight
                        
                        $label = "";

                        if ($isEdit) {

                            $label = $product->productSettingArray('defaultWeight', $eData);

                        }

                        // $form_fields[] = array(

                        //     'label' => _uc($_e['Default Weight In KG']),

                        //     'name' => $product->prefix_setting . '[defaultWeight]',

                        //     'placeholder' => _uc($_e['Default Weight In KG']),

                        //     'value' => "$label",

                        //     'type' => 'text',

                        //     'class' => 'form-control convertNumber',

                        // );                        
                        
                        $label = "";

                        if ($isEdit) {

                            $label = $product->productSettingArray('supplier', $eData);

                        }
                        
                        // $form_fields[] = array(

                        //     'label' => _uc($_e['Supplier']),

                        //     'name' => $product->prefix_setting . '[supplier]',

                        //     'placeholder' => _uc($_e['Enter Supplier Info']),

                        //     'value' => "$label",

                        //     'type' => 'text',

                        //     'class' => 'form-control',

                        // );
                        
                        
                        // if ($isEdit) {

                        //     $linkDiscount = '<a href="-product?page=pDiscountForm&pId=' . $product->editPid . '" class="btn btn-success" target="_blank">' . _uc($_e['Manage Discount']) . '</a>';

                        //     $form_fields[] = array(

                        //         'label' => _uc($_e['Product Discount']),

                        //         'format' => $linkDiscount

                        //     );

                        // }





                        if ($functions->developer_setting('shipping_class') == '1') {

                            $sql = "SELECT * FROM shipping_class WHERE publish  = '1' ORDER BY name";

                            $data = $dbF->getRows($sql);

                            $classesName = array("" => "---");

                            foreach ($data as $val) {

                                $classesName[$val['id']] = $val['name'];

                            }



                            $val = '0';

                            if ($isEdit) {

                                $val = $product->productSettingArray('shippingClass', $eData);

                            }

                            $valFormTemp = $val;

                            // $form_fields[] = array(

                            //     "label" => $_e["Shipping Class"],

                            //     'type' => 'select',

                            //     'required' => 'true',

                            //     'name' => $product->prefix_setting . '[shippingClass]',

                            //     'array' => $classesName,

                            //     'select' => "$valFormTemp",

                            //     'class' => 'form-control',

                            // );

                        }



                        // Form 2 for 3 Product allow on this product or not
                        
                        if ($functions->developer_setting("buy_2_get_1_free") == "1" && true === false) {

                            $val = '0';

                            if ($isEdit) {

                                if ($product->productSettingArray('buy_2_get_1_free', $eData)) {

                                    $val = '1';

                                }

                            }

                            $valFormTemp = $val;

                            $form_fields[] = array(

                                "label" => $_e['Buy 2 get 1 Free'],

                                'type' => 'checkbox',

                                'value' => "$valFormTemp",

                                'select' => "1",

                                'format' => '<div class="make-switch" data-off="warning" data-on="success">

                            {{form}}

                            <input type="hidden" name="' . $product->prefix_setting . '[buy_2_get_1_free]" class="checkboxHidden" value="' . $val . '" />

                         </div>'

                            );



                            $valTemp = "2";

                            if ($isEdit) {

                                $valTemp = $product->productSettingArray('buy_2_get_1_free_qty', $eData);

                            }

                            $form_fields[] = array(

                                'label' => _uc($_e['Buy QTY Limit']),

                                'name' => $product->prefix_setting . '[buy_2_get_1_free_qty]',

                                'value' => "$valTemp",

                                'type' => 'number',

                                "min" => "1",

                                'class' => 'form-control',

                            );



                        }



                        if ($functions->developer_setting('reviews') == '1' && $functions->ibms_setting('showReview') == '1') {

                            //want to send some thing between form
                        
                            $form_fields[] = array(

                                'thisFormat' => "<hr><hr><hr>"

                            );



                            // Form review allow on this product or not
                        
                            $val = '0';

                            if ($isEdit) {

                                if ($product->productSettingArray('review', $eData)) {

                                    $val = '1';

                                }

                            }

                            $valFormTemp = $val;

                //             $form_fields[] = array(

                //                 "label" => $_e['Review'],

                //                 'type' => 'checkbox',

                //                 'value' => "$valFormTemp",

                //                 'select' => "1",

                //                 'format' => '<div class="make-switch" data-off="warning" data-on="success"">

                //     {{form}}

                //   <input type="hidden" name="' . $product->prefix_setting . '[review]" class="checkboxHidden" value="' . $val . '" />

                //  </div>'

                //             );



                            //Review off msg
                        
                            $valTemp = "";

                            if ($isEdit) {

                                $valTemp = $product->productSettingArray('reviewOffMsg', $eData);

                            }

                            // $form_fields[] = array(

                            //     'label' => _uc($_e['Review Off Msg']),

                            //     'name' => $product->prefix_setting . '[reviewOffMsg]',

                            //     'placeholder' => _uc($_e['Review Off Msg']),

                            //     'value' => "$valTemp",

                            //     'type' => 'text',

                            //     'class' => 'form-control',

                            // );



                        }// review Setting
                        




                        if ($functions->developer_setting('askQuestion') == '1' && $functions->ibms_setting('showQuestion') == '1') {

                            // Form Question allow on this product or not
                        
                            $val = '0';

                            if ($isEdit) {

                                if ($product->productSettingArray('askQuestion', $eData)) {

                                    $val = '1';

                                }

                            }

                            $valFormTemp = $val;

                        //     $form_fields[] = array(

                        //         "label" => $_e['Ask Questions'],

                        //         'type' => 'checkbox',

                        //         'value' => "$valFormTemp",

                        //         'select' => "1",

                        //         'format' => '<div class="make-switch" data-off="warning" data-on="success"">

                        //     {{form}}

                        //     <input type="hidden" name="' . $product->prefix_setting . '[askQuestion]" class="checkboxHidden" value="' . $val . '" />

                        //  </div>'

                        //     );



                            //Review off msg
                        
                            $valTemp = "";

                            if ($isEdit) {

                                $valTemp = $product->productSettingArray('questionOffMsg', $eData);

                            }

                            // $form_fields[] = array(

                            //     'label' => _uc($_e['Question Off Msg']),

                            //     'name' => $product->prefix_setting . '[questionOffMsg]',

                            //     'placeholder' => _uc($_e['Question Off Msg']),

                            //     'value' => "$valTemp",

                            //     'type' => 'text',

                            //     'class' => 'form-control',

                            // );



                        }// review Setting
                        




                        if ($functions->developer_setting('isFacebookComments') == '1' && $functions->ibms_setting('showFacebookComment') == '1') {

                            // Form Facebook Comment allow on this product or not
                        
                            $val = '0';

                            if ($isEdit) {

                                if ($product->productSettingArray('facebookComment', $eData)) {

                                    $val = '1';

                                }

                            }

                            $valFormTemp = $val;

                            $form_fields[] = array(

                                "label" => $_e['Facebook Comment'],

                                'type' => 'checkbox',

                                'value' => "$valFormTemp",

                                'select' => "$valFormTemp",

                                'format' => '<div class="make-switch" data-off="warning" data-on="success"">

                            {{form}}

                         <input type="hidden" name="' . $product->prefix_setting . '[facebookComment]" class="checkboxHidden" value="' . $val . '" />

                        </div>'

                            );



                            //Review off msg
                        
                            $valTemp = "";

                            if ($isEdit) {

                                $valTemp = $product->productSettingArray('fbCommentOffMsg', $eData);

                            }

                            $form_fields[] = array(

                                'label' => _uc($_e['Fb Comment Off Msg']),

                                'name' => $product->prefix_setting . '[fbCommentOffMsg]',

                                'placeholder' => _uc($_e['Fb Comment Off Msg']),

                                'value' => "$valTemp",

                                'type' => 'text',

                                'class' => 'form-control',

                            );



                        } // Facebook Comment Setting End
                        



                        $functions->print_form($form_fields, $format);

                        ?>



                        <!--<div class="form-group">

        <label class="col-sm-2 control-label">Size Select Type </label>

        <div class="col-sm-10 ">

            <div class="input-group">

                <?php

                // if product edit
                
                $selectType = "";

                $disabled = 'disabled';

                $checked = 'checked';

                if ($isEdit) {

                    $selectType = $product->productSettingArray('selectType', $eData);

                    if ($selectType != '') {

                        $checked = '';

                        $disabled = '';

                    }

                }

                ?>



                <span class="input-group-addon">

                                        <label><input type="checkbox" <?php echo $checked; ?> data-id="selectType" class="QtyAllow"><?php echo _uc($_e['Use Config Setting']); ?></label>

                                    </span>

                <select <?php echo $disabled; ?> data-old="<?php echo $selectType; ?>" id="selectType" class="form-control" data-name="<?php echo $product->prefix_setting; ?>[selectType]" name="<?php echo $product->prefix_setting; ?>[selectType]">

                    <option value="">Select Type</option>

                    <option value="check">Check Box</option>

                    <option value="select">Select Drop Box</option>

                    <option value="radio">Radio Button</option>

                </select>

                

            </div>

        </div>

    </div> <!-- setting single .Form group  End-->





                    </div>

                    <!-- Product Setting tab End-->

                </div>

                <!-- Tabs Div End-->

            </div>

            <!-- Parent Tabs End-->

        </div>

        <!-- Page row Div end-->

    </div>

    <hr />



    <button type="submit" class="btn btn-primary btn-lg"
        onsubmit="return submitProduct();"><?php echo _u($_e['SUBMIT']); ?></button>

</form>



<!-- Modal use in modal div-->

<div class="modal fade bs-example-modal-lg" id="productImgDialog" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>

                <h4 class="modal-title" id="myModalLabel"><?php echo _uc($_e['Image Preview']); ?></h4>

            </div>

            <div class="modal-body" style="text-align: center">

                <img src="" align="center" />

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?php echo _uc($_e['Close']); ?></button>

            </div>

        </div>

    </div>

</div>



<?php

if ($isEdit) {

    $sqll = "SELECT * FROM `product_category` WHERE `procat_prodet_id` = $editId";

    $ress = $dbF->getRow($sqll);

    $categ = $ress['procat_cat_id'];

    $cat_Array = explode(',', $categ);

    $cnt = sizeof($cat_Array);



    for ($i = 0; $i < $cnt; $i++) {

        $cat_idd = $cat_Array[$i];





        ?>



        <script>

            var p_idd = '<?php echo $cat_idd; ?>';

            $("input[name='cats[]']").each(function () {
                var ths = $(this);
                if (ths.val() == '<?php echo @$cat_idd; ?>') {
                    ths.attr({ 'checked': 'true' });
                }
            });

        </script>



        <?php

    }

}

?>

<script>

    $(document).ready(function () {

        ////////////////////////////////////

        $(".datepicker").datepicker({ minDate: 0 });



        ///////////////////////////////////////

        $(".imageHolder").click(function () {

            img = $(this).find("img").attr('src');

            $('#productImgDialog').modal('show');

            $("#productImgDialog .modal-body").find("img").attr("src", img).hide().show(600);

        });



        ////////////////////////////////////////////

        $(".productEditImageDel").click(function () {

            if (secure_delete()) {

                id = $(this).attr("data-id");

                parnt = $(this).closest(".preview");

                $.ajax({

                    type: "POST",

                    url: 'product_management/product_ajax.php?page=productEditImageDel',

                    data: { imageId: id }

                }).done(function (data) {

                    if (data == '1') {

                        parnt.hide(500);

                    } else if (data == '0') {

                        jAlertifyAlert("<?php echo _js($_e['Image Not Delete Please Try Again']); ?>");

                        return false;

                    }

                });

            }

        });

        $(".productEditDetailImageDel").click(function () {

            if (secure_delete()) {

                id = $(this).attr("data-id");

                parnt = $(this).closest(".preview");

                $.ajax({

                    type: "POST",

                    url: 'product_management/product_ajax.php?page=productEditDetailImageDel',

                    data: { imageId: id }

                }).done(function (data) {

                    if (data == '1') {

                        parnt.hide(500);

                    } else if (data == '0') {

                        jAlertifyAlert("<?php echo _js($_e['Image Not Delete Please Try Again']); ?>");

                        return false;

                    }

                });

            }

        });
        /////////////////////////////////////////////////



        $('.QtyAllow').change(function () {

            data = $(this).attr('data-id');

            input = $('#' + data);

            name = input.attr('data-name');

            old = input.attr('data-old');

            if ($(this).prop("checked")) {

                input.removeAttr('name');

                input.attr('readonly', 'readonly');

                input.attr('disabled', 'disabled');

                input.val("").change();



            } else {

                input.attr('name', name);

                input.removeAttr('readonly');

                input.removeAttr('disabled');

                input.val(old).change();



            }

        });

        /////////////////////////////////////////////////////////



        $(".pImageAltUpdate").click(function () {

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

                data: { imageId: id, altT: alt }

            }).done(function (data) {

                ift = true;

                if (data == '1') {

                    btn.children('span').text('<?php echo _js($_e['Done']); ?>');

                }

                else {

                    btn.children('span').text('<?php echo _js($_e['Fail']); ?>');

                }

                btn.removeClass('disabled');

                btn.children('.trash').show();

                btn.children('.waiting').hide();



            });

        });

        $(".pDetailImageAltUpdate").click(function () {

            btn = $(this);

            btn.addClass('disabled');

            btn.children('.trash').hide();

            btn.children('.waiting').show();



            id = btn.attr('data-id');

            btn.children('span').text('Wait...');


            let swedishHeading = btn.closest('.progressHolder').find('.swedish-heading').val();
            let norwegianHeading = btn.closest('.progressHolder').find('.norwegian-heading').val();
            let danishHeading = btn.closest('.progressHolder').find('.danish-heading').val();
            let finnishHeading = btn.closest('.progressHolder').find('.finnish-heading').val();
            let englishHeading = btn.closest('.progressHolder').find('.english-heading').val();
            let germanHeading = btn.closest('.progressHolder').find('.german-heading').val();
            let frenchHeading = btn.closest('.progressHolder').find('.french-heading').val();

            let swedishDesc = btn.closest('.progressHolder').find('.swedish-desc').val();
            let norwegianDesc = btn.closest('.progressHolder').find('.norwegian-desc').val();
            let danishDesc = btn.closest('.progressHolder').find('.danish-desc').val();
            let finnishDesc = btn.closest('.progressHolder').find('.finnish-desc').val();
            let englishDesc = btn.closest('.progressHolder').find('.english-desc').val();
            let germanDesc = btn.closest('.progressHolder').find('.german-desc').val();
            let frenchDesc = btn.closest('.progressHolder').find('.french-desc').val();


            let headings = {
                'Swedish': swedishHeading,
                'Norwegian': norwegianHeading,
                'Danish': danishHeading,
                'Finnish': finnishHeading,
                'English': englishHeading,
                'German': germanHeading,
                'French': frenchHeading,
            }

            let description = {
                'Swedish': swedishDesc,
                'Norwegian': norwegianDesc,
                'Danish': danishDesc,
                'Finnish': finnishDesc,
                'English': englishDesc,
                'German': germanDesc,
                'French': frenchDesc,
            }


            $.ajax({

                type: 'POST',

                url: 'product_management/product_ajax.php?page=pDetailImageAltUpdate',

                data: { imageId: id, altT: headings, desc: description }

            }).done(function (data) {

                ift = true;

                if (data == '1') {

                    btn.children('span').text('<?php echo _js($_e['Done']); ?>');

                }

                else {

                    btn.children('span').text('<?php echo _js($_e['Fail']); ?>');

                }

                btn.removeClass('disabled');

                btn.children('.trash').show();

                btn.children('.waiting').hide();



            });

        });

        ////////////////////////////////////////////

        function submitProduct() {

            convertNumber();

            return true;

        }



        //////////////////////////

        $("#dropbox").sortable({

            handle: '.imageHolder',

            containment: "parent",

            update: function () {

                serial = $(this).sortable('serialize');

                $.ajax({

                    url: 'product_management/product_ajax.php?page=sortProductImage',

                    type: "post",

                    data: serial,

                    error: function () {

                        jAlertifyAlert("<?php echo _js($_e['There is an error, Please Refresh Page and Try Again']); ?>");

                    }

                });

            }

        });

        $("#dropboxDetail").sortable({

            handle: '.imageHolder',

            containment: "parent",

            update: function () {

                serial = $(this).sortable('serialize');

                $.ajax({

                    url: 'product_management/product_ajax.php?page=sortProductDetailImage',

                    type: "post",

                    data: serial,

                    error: function () {

                        jAlertifyAlert("<?php echo _js($_e['There is an error, Please Refresh Page and Try Again']); ?>");

                    }

                });

            }

        });

        ////////////////////////////////

        $("table tbody").sortable({
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    // $(this).find('td').last().html(index + 1)
                });
                serial = $(this).sortable('serialize');
                console.log(serial);
                $.ajax({
                    url: 'product_management/product_ajax.php?page=sortProductSize',
                    type: "post",
                    data: serial,
                    error: function () {
                        jAlertifyAlert("<?php echo _js($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                    }
                }).done(function (res) {
                    console.log(res);
                });
            }
        });

    });

</script>



<script>
    $("input[name='pinfo[name][English]']").keyup(function(){
         var value = $(this).val();
         value = value.replace(/\s+/g, '-');
         value = value.replace(/\//g, '');
         value = value.toLowerCase();
         var pId = '<?php echo $product->pid; ?>';
         var refId = '<?php echo $db->productDetail; ?>' + pId;
         $.ajax({
            type: "POST",
            url: 'product_management/product_ajax.php?page=check_slug_duplicate',
            data: {
                slug: value,
                refId: refId
            },
            success: function(res){
                if(res === "not matched"){
                    $("input[name='pinfo[slug]']").val(value);
                    if ($("button[type='submit']").next("span").length > 0) {
                        $("button[type='submit']").prop('disabled', false);
                        $("button[type='submit']").next("span").remove();
                    }
                }else{
                    $("input[name='pinfo[slug]']").val(value);
                    $("button[type='submit']").prop('disabled', true);
                    if ($("button[type='submit']").next("span").length === 0) {
                        $("<span>").text("Slug already exists, Please change the slug").insertAfter("button[type='submit']").css({
                            color: 'red',
                            'margin-left': '10px',
                        });
                    }
                }
            },
            error: function(xhr, status, errorThrown){
                console.log(xhr.responseText);
            }
         });
         
    });
    
    $("input[name='pinfo[slug]']").keyup(function(){
         var value = $(this).val();
         var pId = '<?php echo $product->pid; ?>';
         var refId = '<?php echo $db->productDetail; ?>' + pId;
         $.ajax({
            type: "POST",
            url: 'product_management/product_ajax.php?page=check_slug_duplicate',
            data: {
                slug: value,
                refId: refId
            },
            success: function(res){
                if(res === "not matched"){
                    if ($("button[type='submit']").next("span").length > 0) {
                        $("button[type='submit']").prop('disabled', false);
                        $("button[type='submit']").next("span").remove();
                    }
                }else{
                    $("button[type='submit']").prop('disabled', true);
                    if ($("button[type='submit']").next("span").length === 0) {
                        $("<span>").text("Slug already exists, Please change the slug").insertAfter("button[type='submit']").css({
                            color: 'red',
                            'margin-left': '10px',
                        });
                    }
                }
            },
            error: function(xhr, status, errorThrown){
                console.log(xhr.responseText);
            }
         });
         
    });
    $('input[type=checkbox]').click(function () {

        console.log('checkbox clicked');

        $(this).parent()

            .find('li input[type=checkbox]')

            .prop('checked', $(this)

                .is(':checked'));

        var sibs = false;



        $(this).closest('ul')

            .children('li').each(function () {

                if ($('input[type=checkbox]', this).is(':checked'))

                    sibs = true;

            })

        $(this).parents('ul').prev().prop('checked', sibs);



        $("input[type='checkbox'] ~ ul input[type='checkbox']").change(function () {

            $(this).closest("li:has(li)").children("input[type='checkbox']").prop('checked', $(this).closest('ul').find("input[type='checkbox']").is(':checked'));

        });

    });



</script>



<style>
    #nestedlist,
    #nestedlist ul {

        list-style-type: none;

        margin-left: 0;

        padding-left: 30px;

        text-indent: -4px;

    }



    /* UL Layer 1 Rules */

    #nestedlist {
        font-weight: bold;

    }



    /* UL Layer 2 Rules */

    #nestedlist ul {

        font-weight: normal;

        margin-top: 3px;

    }



    /* UL Layer 3 Rules */

    #nestedlist ul ul {

        font-size: 16px;

    }



    /* UL 4 Rules */

    #nestedlist ul ul ul {

        font-size: 14px;

    }
</style>

<?php return ob_get_clean(); ?>