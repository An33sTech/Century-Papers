<?php
ob_start();
global $db, $dbF, $functions;
$product = new product();
    /**
     * MultiLanguage keys Use where echo;
     * define this class words and where this class will call
     * and define words of file where this class will called
     **/

    global $_e;
    global $adminPanelLanguage;

    $_w['Product List!'] = '' ;
    $_w['All Products'] = '' ;
    $_w['Drafts'] = '' ;
    $_w['Pending'] = '' ;
    $_w['All Products'] = '' ;
    $_w['Draft'] = '' ;
    $_w['Pending'] = '' ;
    $_w['Product Description'] = '' ;
    $_w['Description'] = '' ;
    $_w['Short Description'] = '' ;
    $_w['Size Chart'] = '' ;
    $_w['Delete All Selected Product'] = '' ;
    $_w['Delete Fail Please Try Again.'] = '' ;
    $_w['Select Visible Product'] = '' ;
    $_w['Filter'] = '' ;
    $_w['Filter Selected'] = '' ;
    $_w['Add to Category'] = '' ;
    $_w['Products Added To Category'] = '' ;
    $_w['Remove From Category'] = '' ;
    $_w['Products Removed From Category'] = '' ;
    $_w[''] = '' ;
    $_w[''] = '' ;
    $_w[''] = '' ;
    $_w[''] = '' ;
    
    $_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin ProductView');

?>
<div class="modal fade" id="productEditModal" tabindex="1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" >Update Product Name<Edit></Edit></h4>
                        </div>
                         <?php

                            $lang = $functions->IbmsLanguages();

                                $lang_nonArray = implode(',', $lang);



                                echo <<<HTML

                                      <input type="hidden" name="lang" value="$lang_nonArray" />
<div class="modal-body" style="display: block;">
                                      <div class="panel-group" id="accordion">

HTML;




                                for ($i = 0; $i < sizeof($lang); $i++) {

                                    if ($i == 0) {

                                        $collapseIn = ' in ';

                                    } else {

                                        $collapseIn = '';

                                    }

                                    $lang[$i];

                                    

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

                                        'placeholder' => _uc(@$_e['Product Name']),

                                        

                                        'type' => 'text',

                                        'class' => 'form-control uniqueClass pName'.$lang[$i],

                                    );
                                    
                                    $form_fields[] = array(

                                        'label' => _uc($_e['Short Description']),

                                        'name' => "$product->prefix_productBasicInformation[desc][$lang[$i]]",

                                        'placeholder' => _uc($_e['Short Description']),

                                        

                                        'type' => 'textarea',

                                        'class' => 'form-control uniqueClass  ckeditor  pDesc'.$lang[$i],

                                    );

                                    $form_fields[] = array(

                                        'label' => _uc($_e['Description']),

                                        'name' => "$product->prefix_productBasicInformation[detail_desc][$lang[$i]]",

                                        'placeholder' => _uc($_e['Description']),

                                        'type' => 'textarea',

                                        'class' => 'form-control uniqueClass  ckeditor  detail_desc'.$lang[$i],

                                    );
                                    
                                    $form_fields[] = array(

                                        'label' => _uc($_e['Size Chart']),

                                        'name' => "$product->prefix_productBasicInformation[pSize][$lang[$i]]",

                                        'placeholder' => _uc($_e['Size Chart']),

                                        'type' => 'textarea',

                                        'class' => 'form-control uniqueClass  ckeditor  pSize'.$lang[$i],

                                    );

                                    $format='';
                                    $functions->print_form($form_fields, $format);

                                        echo '

                                </div> <!-- panel-body-->

                        </div> <!-- #$lang[$i] -->



                    </div> ';
                }
                    echo "</div>";
                    ?>
                </div>
                    <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary save_button" data-loading-text="Saving..." onclick="productUpdate(this);" style="display: inline-block;">
                                    <i class="fa fa-check-square-o success" style="display: none"></i>
                                    <i class="fa fa-exclamation-triangle fail" style="display: none"></i>
                                    <span class="text">Update</span>
                                </button>
                            </div>
                    
                </div>
            </div>
        </div>
<script>
    function productEditModal(ths){
        var pId = $(ths).attr('data-id');
         $('#productEditModal').modal('show');
         
         
            
        $.ajax({
         type: 'POST',
          url: 'product_management/product_ajax.php?page=productNameEdit&pId='+pId
        }).done(function(data)
        {   

        
            arrayValue = jQuery.parseJSON( data );
        
            $(".pNameSwedish").val(arrayValue['name'].Swedish);
            $(".pNameNorwegian").val(arrayValue['name'].Norwegian);
            $(".pNameDanish").val(arrayValue['name'].Danish);
            $(".pNameFinnish").val(arrayValue['name'].Finnish);
            $(".pNameEnglish").val(arrayValue['name'].English);
            $(".pNameGerman").val(arrayValue['name'].German);
            $(".pNameFrench").val(arrayValue['name'].French);
            
            CKEDITOR.instances['pinfo[desc][Swedish]'].setData(arrayValue['short_desc'].Swedish);
            CKEDITOR.instances['pinfo[desc][Norwegian]'].setData(arrayValue['short_desc'].Norwegian);
            CKEDITOR.instances['pinfo[desc][Danish]'].setData(arrayValue['short_desc'].Danish);
            CKEDITOR.instances['pinfo[desc][Finnish]'].setData(arrayValue['short_desc'].Finnish);
            CKEDITOR.instances['pinfo[desc][English]'].setData(arrayValue['short_desc'].English);
            CKEDITOR.instances['pinfo[desc][German]'].setData(arrayValue['short_desc'].German);
            CKEDITOR.instances['pinfo[desc][French]'].setData(arrayValue['short_desc'].French);
            
            
            CKEDITOR.instances['pinfo[detail_desc][Swedish]'].setData(arrayValue['detail_desc'].Swedish);
            CKEDITOR.instances['pinfo[detail_desc][Norwegian]'].setData(arrayValue['detail_desc'].Norwegian);
            CKEDITOR.instances['pinfo[detail_desc][Danish]'].setData(arrayValue['detail_desc'].Danish);
            CKEDITOR.instances['pinfo[detail_desc][Finnish]'].setData(arrayValue['detail_desc'].Finnish);
            CKEDITOR.instances['pinfo[detail_desc][English]'].setData(arrayValue['detail_desc'].English);
            CKEDITOR.instances['pinfo[detail_desc][German]'].setData(arrayValue['detail_desc'].German);
            CKEDITOR.instances['pinfo[detail_desc][French]'].setData(arrayValue['detail_desc'].French);
            
            CKEDITOR.instances['pinfo[pSize][Swedish]'].setData(arrayValue['size_chart'].Swedish);
            CKEDITOR.instances['pinfo[pSize][Norwegian]'].setData(arrayValue['size_chart'].Norwegian);
            CKEDITOR.instances['pinfo[pSize][Danish]'].setData(arrayValue['size_chart'].Danish);
            CKEDITOR.instances['pinfo[pSize][Finnish]'].setData(arrayValue['size_chart'].Finnish);
            CKEDITOR.instances['pinfo[pSize][English]'].setData(arrayValue['size_chart'].English);
            CKEDITOR.instances['pinfo[pSize][German]'].setData(arrayValue['size_chart'].German);
            CKEDITOR.instances['pinfo[pSize][French]'].setData(arrayValue['size_chart'].French);

            $(".save_button").attr("data-pId",pId);
        });


    }

    function productUpdate(ths){
        var pId = $(ths).attr('data-pId');
$('#productEditModal').modal('hide');
         jsonObj = [];
      
         item = {
            'pName' : {},
            'pShortDesc' : {},
            'pDetailDesc' : {},
            'pSizeChart' : {}
        }
        item['pName']["Swedish"] = $(".pNameSwedish").val();
        item['pName']["Norwegian"] = $(".pNameNorwegian").val();
        item['pName'] ["Danish"] = $(".pNameDanish").val();
        item['pName']["Finnish"] = $(".pNameFinnish").val();
        item['pName']["English"] = $(".pNameEnglish").val();
        item['pName']["German"] = $(".pNameGerman").val();
        item['pName']["French"] = $(".pNameFrench").val();
        
        item['pShortDesc']["Swedish"] = CKEDITOR.instances['pinfo[desc][Swedish]'].getData();
        item['pShortDesc']["Norwegian"] = CKEDITOR.instances['pinfo[desc][Norwegian]'].getData();
        item['pShortDesc'] ["Danish"] = CKEDITOR.instances['pinfo[desc][Danish]'].getData();
        item['pShortDesc']["Finnish"] = CKEDITOR.instances['pinfo[desc][Finnish]'].getData();
        item['pShortDesc']["English"] = CKEDITOR.instances['pinfo[desc][English]'].getData();
        item['pShortDesc']["German"] = CKEDITOR.instances['pinfo[desc][German]'].getData();
        item['pShortDesc']["French"] = CKEDITOR.instances['pinfo[desc][French]'].getData();
        
        item['pDetailDesc']["Swedish"] = CKEDITOR.instances['pinfo[detail_desc][Swedish]'].getData();
        item['pDetailDesc']["Norwegian"] = CKEDITOR.instances['pinfo[detail_desc][Norwegian]'].getData();
        item['pDetailDesc'] ["Danish"] = CKEDITOR.instances['pinfo[detail_desc][Danish]'].getData();
        item['pDetailDesc']["Finnish"] = CKEDITOR.instances['pinfo[detail_desc][Finnish]'].getData();
        item['pDetailDesc']["English"] = CKEDITOR.instances['pinfo[detail_desc][English]'].getData();
        item['pDetailDesc']["German"] = CKEDITOR.instances['pinfo[detail_desc][German]'].getData();
        item['pDetailDesc']["French"] = CKEDITOR.instances['pinfo[detail_desc][French]'].getData();
        
        item['pSizeChart']["Swedish"] = CKEDITOR.instances['pinfo[pSize][Swedish]'].getData();
        item['pSizeChart']["Norwegian"] = CKEDITOR.instances['pinfo[pSize][Norwegian]'].getData();
        item['pSizeChart'] ["Danish"] = CKEDITOR.instances['pinfo[pSize][Danish]'].getData();
        item['pSizeChart']["Finnish"] = CKEDITOR.instances['pinfo[pSize][Finnish]'].getData();
        item['pSizeChart']["English"] = CKEDITOR.instances['pinfo[pSize][English]'].getData();
        item['pSizeChart']["German"] = CKEDITOR.instances['pinfo[pSize][German]'].getData();
        item['pSizeChart']["French"] = CKEDITOR.instances['pinfo[pSize][French]'].getData();
        

        jsonString = JSON.stringify(item);

        
        $.ajax({
         type: 'POST',
          url: 'product_management/product_ajax.php?page=productUpdate&pId='+pId ,
          data:{arrayData:item}
        }).done(function(data)
        {   

            var pName =$(".pNameSwedish").val();
            $(".productAjax_edit").each(function(){
                var dataId = $(this).attr("data-id");
                if (dataId == pId) {
                    $(this).text(pName);
                }

            });

        });


    }
    $(document).ready(function(){

        tableHoverClasses();



        setTimeout(function(){

            $("ul.ColVis_collection .delCheckboxOncolvis").remove();

            $("ul.ColVis_collection li:first-child span").text("SNO");

        },2000);

    });


    let toggleState = false;

        function selectVisible(ths) {
            toggleState = !toggleState;
            $(".prod_check").each(function() {
                $(this).prop('checked', toggleState);
            });
        }


    function selectProductDel(ths){

       var remove;

       var checkedValues='';

        var i=true;

        btn=$(ths);

        if(secure_delete()){

            btn.addClass('disabled');

            btn.children('.trash').hide();

            btn.children('.waiting').show();



            $('.dTableWidth tbody input:checkbox:checked, .dTable_ajax tbody input:checkbox:checked').each(function(){

                if(i){

                  remove = ".p_"+ $(this).val();

                  checkedValues= $(this).val();

                  i=false;

                }else{

                    remove += " , .p_"+ $(this).val();

                    checkedValues += ", "+$(this).val();

                }

            });



            if(checkedValues==''){

                alert("No Product Found To Delete.");

                btn.removeClass('disabled');

                btn.children('.trash').show();

                btn.children('.waiting').hide();

                return false;

            }



            $.ajax({

                type: 'POST',

                url: "product_management/product_ajax.php?page=selectedProductDel",

                data: { id:checkedValues }

            }).done(function(data)

                {

                    if(data=='1'){

                        setTimeout(function(){

                            $(remove).hide(700, function(){

                                $(remove).remove();

                                btn.removeClass('disabled');

                                btn.children('.trash').show();

                                btn.children('.waiting').hide();

                            });

                        },300);

                    }

                    else if(data=='0'){

                        btn.removeClass('disabled');

                        btn.children('.trash').show();

                        btn.children('.waiting').hide();

                        jAlertifyAlert('<?php echo _js($_e['Delete Fail Please Try Again.']); ?>');

                    }

                });

        }

    }







</script>

 

<div ng-app="angular" ng-controller='angularController'>



    <h4 class="sub_heading"><?php echo _uc($_e['Product List!']); ?></h4>





    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist" >

        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['All Products']); ?></a></li>

        <li><a href="#product_draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Drafts']); ?></a></li>

        <li><a href="#prodcut_pending" role="tab" data-toggle="tab"><?php echo _uc($_e['Pending']); ?></a></li>
        
        <li><a href="#prodcut_missing" role="tab" data-toggle="tab"><?php echo _uc($_e['Missing Products']); ?></a></li>

    </ul>



    <!-- Tab panes -->

    <div class="tab-content">

        <div class="tab-pane fade in active container-fluid" id="home">

            <h2 class="tab_heading"><?php echo _uc($_e['All Products']); ?></h2>

            <button class="btn btn-primary" id="updateDiscount">Update Discounts</button>

            <button id="hide-show" class="btn btn-primary" style="position: relative;

    display: inherit;">Categories</button>

            <div id="cate" class="collapse" style="

                position: relative;

                width: 300px;

                height: 500px;

                display: inline-block;

                vertical-align: top;

            ">

               <div class="tab-pane container-fluid" id="new_tab_categroy">

                    <h2 class="tab_heading"><?php echo _uc($_e['Product Category']); ?></h2>

                    <ul id="nestedlist">

                        <?php



                        ##### Main MENU

                        $css = false;

                        $view_css= '';

                        $mainMenu = $product->menuTypeSingle('main');

                        foreach ($mainMenu as $val) {

                        $insideActive = false;

                        $innerUl = '';

                        $menuId = $val['id'];

                        $text = _n($val['name']);

                        $link = $val['link'];

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



                        if ($active == '1') {

                        $active = 'active';

                        $insideActive = $css = true;

                        }



                        $has_inner_level_three_class = '';



                        $mainMenu3 = $product->menuTypeSingle('main', $menuId2);

                        # count the inner level 3 lis

                        $innerUl3count = ( $mainMenu3 == false ? 0 : count($mainMenu3) ) ;

                        $innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';



                        if ( $innerUl3count > 0) {



                        foreach ($mainMenu3 as $val3) {

                        $view_css3 = '';

                        $text3       = _n($val3['name']);

                        $menuId3     = $val3['id'];

                        $link3       = $val3['link'];

                        $menuIcon3   = $val3['icon'];

                        $active3     = $val3['active'];

                        if ($active3 == '1') {

                        $active3 = 'active';

                        $insideActiveThree = true;

                        }





                        $has_inner_level_three_class = 'has-sub';



                        $innerUl3 .= '



                        <li><input type="checkbox" name="cats[]" value='.$menuId3.'>

                        '. $text3 . '



                        </li>





                        ';



                        }



                        }



                        $innerUl3 .= ( $innerUl3count > 0 ) ? '</ul><!--3rd array End-->' : '';



                        if ($innerUl3) {



                        $image_div = '';



                        } else {

                        $image_div = '';

                        }



                        $innerUl .= '



                        <li><input type="checkbox" name="cats[]" value='.$menuId2.'>



                        ' . $text . '



                        <span>



                        '.$image_div.'



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

                        <li><input type="checkbox" name="cats[]" value='.$menuId.'>



                        ' . $text . '







                        ' . $innerUl . '



                        </li>

                        ';

                        }



                        echo '';



                        $cat = $selectedNode=$product->productSelectedNode();

                        $trim_Cat = rtrim($cat,',');



                        ?>
                    </ul>
                    <div> 
                        <a class="btn btn-primary" id="fiter_products"><?php echo _uc($_e['Filter']); ?></a>
                        <a class="btn btn-primary" id="fiter_selected_products"><?php echo _uc($_e['Filter Selected']); ?></a>
                        <a class="btn btn-primary" id="add_to_category"><?php echo _uc($_e['Add to Category']); ?></a>
                        <a class="btn btn-primary" id="remove_from_category"><?php echo _uc($_e['Remove From Category']); ?></a>               
                    </div>
                </div> 
            </div>
                <?php $product->productView(); ?>
                <button class="btn btn-danger btn-large" onclick="selectProductDel(this);" style="display: none;">

                    <i class='glyphicon glyphicon-trash trash'></i>
        
                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
        
                    <?php echo _uc($_e['Delete All Selected Product']); ?>
        
                </button>
                
                <button class="btn btn-primary btn-large" onclick="selectVisible(this);">

                    <i class='glyphicon glyphicon-check'></i>
        

                    <?php echo _uc($_e['Select Visible Product']); ?>
        
                </button>
        </div>

        <div class="tab-pane fade container-fluid" id="product_draft">

            <h2 class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>

            <?php $product->productDraft(); ?>
            <button class="btn btn-danger btn-large" onclick="selectProductDel(this);">

                <i class='glyphicon glyphicon-trash trash'></i>
    
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
    
                <?php echo _uc($_e['Delete All Selected Product']); ?>
    
            </button>
        </div>

        <div class="tab-pane fade container-fluid" id="prodcut_pending">

            <h2 class="tab_heading"><?php echo _uc($_e['Pending']); ?></h2>

            <?php $product->productPending(); ?>
            <button class="btn btn-danger btn-large" onclick="selectProductDel(this);">
    
                <i class='glyphicon glyphicon-trash trash'></i>
    
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
    
                <?php echo _uc($_e['Delete All Selected Product']); ?>
    
            </button>
        </div>

        <div class="tab-pane fade container-fluid" id="prodcut_missing">

            <h2 class="tab_heading"><?php echo _uc($_e['Missing']); ?></h2>
            <p>All Products Missing in Sharkspeed Switzerland</p>

            <?php $missing_products = $product->countMissingProducts(); ?>

            <button type="button" class="btn btn-primary" onClick="copyMissingProducts(this)" data-toggle="tooltip" data-html="true" title="<b><em><?php echo $missing_products['count']; ?></em> Products Missing!</b>">
              Copy Missing Products
            </button>

        </div>



        <?php $product->productF->AjaxDelScript('singleProductDel','Product'); ?>



    </div>



</div>



<script>

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });

    function copyMissingProducts(ths){
        var ids = '<?php echo $missing_products['ids'] ?>';
        
        $.ajax({
            url: 'product_management/product_ajax.php?page=copyMissingProducts',
            type: 'POST',
            data: { ids : ids}
        }).done(function(res){
            if(res == '1'){
                jAlertifyAlert('<?php echo $_e['Missing Products Copied Successfully.']; ?>');
            }
        });
    }

    function featureItem(ths){

        btn=$(ths);

        val=btn.attr('data-val');

        if(val=='1'){

            start = '<?php echo $_e["Active"]; ?>';

        }else{

            start = '<?php echo $_e["DeActive"]; ?>';

        }

        if(secure_delete("<?php echo _replace("{{state}}",'"+start+"',$_e["Are you sure you want to {{state}} Feature Product?"]); ?>")){

            btn.addClass('disabled');

            btn.children('.trash').hide();

            btn.children('.waiting').show();



            id=btn.attr('data-id');



            $.ajax({

                type: 'POST',

                url: 'product_management/product_ajax.php?page=featureItem',

                data: { id:id,val:val }

            }).done(function(data)

            {

                ift =true;

                if(data=='1'){

                    if(val=='0'){


                        btn.attr('data-val','1');

                        btn.children('.trash').removeClass('glyphicon-star');

                        btn.children('.trash').addClass('glyphicon-star-empty');

                    }else{

                        btn.attr('data-val','0');

                        btn.children('.trash').addClass('glyphicon-star');

                        btn.children('.trash').removeClass('glyphicon-star-empty');

                    }



                }

                else if(data=='0'){

                    jAlertifyAlert('<?php echo $_e['Update Fail Please Try Again.']; ?>');

                }



                btn.removeClass('disabled');

                btn.children('.trash').show();

                btn.children('.waiting').hide();



            });

        }

    }



    function trandingItem(ths){

        btn=$(ths);

        val=btn.attr('data-val');

        if(val=='2'){

            start = '<?php echo $_e["Active"]; ?>';

        }else{

            start = '<?php echo $_e["DeActive"]; ?>';

        }

        if(secure_delete("<?php echo _replace("{{state}}",'"+start+"',$_e["Are you sure you want to {{state}} Feature Item 2?"]); ?>")){

            btn.addClass('disabled');

            btn.children('.trash').hide();

            btn.children('.waiting').show();



            id=btn.attr('data-id');



            $.ajax({

                type: 'POST',

                url: 'product_management/product_ajax.php?page=featureItem',

                data: { id:id,val:val }

            }).done(function(data)

            {

                ift =true;

                if(data=='1'){

                    if(val=='3'){


                        btn.attr('data-val','2');

                        btn.children('.trash').removeClass('glyphicon-heart');

                        btn.children('.trash').addClass('glyphicon-heart-empty');

                    }else{

                        btn.attr('data-val','3');

                        btn.children('.trash').addClass('glyphicon-heart');

                        btn.children('.trash').removeClass('glyphicon-heart-empty');

                    }



                }

                else if(data=='0'){

                    jAlertifyAlert('<?php echo $_e['Update Fail Please Try Again.']; ?>');

                }



                btn.removeClass('disabled');

                btn.children('.trash').show();

                btn.children('.waiting').hide();



            });

        }

    }





</script>



<script>



$(document).ready(function(){

    $('#hide-show').click(function(event) {

        $('#cate').toggle();

        $('#script-width').toggleClass("dTableWidth");

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

            if($('input[type=checkbox]', this).is(':checked')) 

                sibs=true;

    })

 

});

var joinTest = '';

var joinTest1 = '';

var favorite = new Array();

$('#fiter_products').click(function(event) {

    favorite = [];

    joinTest1 = '';

    $.each($("input[name='cats[]']:checked"), function(){            

                favorite.push($(this).val());

            });

    joinTest = favorite.join(",");

    console.log('joinTest : '+joinTest);

    fetch_ajax_result_again();

});



function fetch_ajax_result_again() {



    my_dtable = $.fn.dataTable.tables( { visible: true, api: true } );

    $(my_dtable).DataTable().ajax.reload();



}



$('#updateDiscount').click(function(event) {

    $.ajax({

        url: 'product/updateDiscount.php?update=true',

        type: 'post',

    })

    .done(function(res) {

        console.log("success : "+res);

    });

    

});



$('#fiter_selected_products').click(function(){

    joinTest = '';

    $.each($("input[name='cats[]']:checked"), function(){            

                favorite.push($(this).val());

            });

    joinTest1 = favorite.join(",");

    fetch_ajax_result_again();

});



var addCatArray = new Array();

var addProArray = new Array();

$('#add_to_category').click(function(){

    $.each($("input[name='prod_check']:checked"), function(){            

                addProArray.push($(this).val());

            });

    $.each($("input[name='cats[]']:checked"), function(){            

                addCatArray.push($(this).val());

            });

    if(addCatArray.length > 0 && addProArray.length > 0){

        $.ajax({

            type: 'POST',

            url: "product_management/product_ajax.php?page=addProToCat",

            data: { proArray:addProArray, catArray:addCatArray}

        }).done(function(res){

            var msgAlert = '<?php echo $_e["Products Added To Category"]; ?>';

            alert(msgAlert);

        });

    }else{

        var add_error = '<?php echo _uc($_e['Please select categories and products both, It is mandatory']); ?>';

        alert(add_error);

    }

});





var removeCatArray = new Array();

var removeProArray = new Array();

$('#remove_from_category').click(function(){

    $.each($("input[name='prod_check']:checked"), function(){            

                removeProArray.push($(this).val());

            });

    $.each($("input[name='cats[]']:checked"), function(){            

                removeCatArray.push($(this).val());

            });

    if(removeCatArray.length > 0 && removeProArray.length > 0){

        $.ajax({

            type: 'POST',

            url: "product_management/product_ajax.php?page=removeProFromCat",

            data: { proArray:removeProArray, catArray:removeCatArray}

        }).done(function(res){

            var msgAlert = '<?php echo $_e["Products Removed From Category"]; ?>';

            alert(msgAlert);

        });

    }else{

        var remove_error = '<?php echo _uc($_e['Please select categories and products both, It is mandatory']); ?>';

        alert(remove_error);

    }

});


</script>



<style>

.dataTables_processing {

    position: fixed;

    top: 50%;

    left: 50%;

    border: none; 

    background: none;

}



#nestedlist, #nestedlist ul {

  list-style-type: none;

  margin-left:0;

  padding-left:30px;

  text-indent: -4px;

}



/* UL Layer 1 Rules */

#nestedlist {

  font-weight:bold;

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

.dTableWidth {

    display: inline-block;  

    width: 70%;

}

</style>



<?php return ob_get_clean(); ?>