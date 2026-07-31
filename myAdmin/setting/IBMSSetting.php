<?php
ob_start();
//Encrypt after ob_start();
?>
<?php
//require_once("classes/setting.class.php");
global $dbF;
global $functions;
global $_e;
$functions->require_once_custom('setting.class.php');
$setting    =  new setting();

//$dbF->prnt($_POST);
//exit;
$setting->IBMSSubmit();
$settingData = $setting->getIBMSSettingData();
?>
    <h4 class="sub_heading"><?php echo _uc($_e['IBMS Setting']); ?></h4>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">

        <!-- ################ General Setting ############## -->
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['General']); ?></a></li>

        <!-- ################ Social Links ############## -->
        <?php if($functions->developer_setting('hasSocialLinks')=='1') { ?>
            <li><a href="#social" role="tab" data-toggle="tab"><?php echo _uc($_e['Social']); ?></a></li>
        <?php } ?>

        <!-- ################ Reviews / Comments Setting ############## -->
        <?php
        $ourReview          = $functions->developer_setting('reviews');
        $facebookComment    = $functions->developer_setting('isFacebookComments');
        if($ourReview=='1' || $facebookComment=='1') { ?>
            <!--<li><a href="#reviews" role="tab" data-toggle="tab"><?php //echo _uc($_e['Reviews']); ?></a></li>-->
        <?php } ?>

        <!-- ################ Products Setting ############## -->
        <?php if( $functions->developer_setting('product') == '1' && $functions->developer_setting('cartSystem') == '1' ) { ?>
            <!--<li><a href="#product" role="tab" data-toggle="tab"><?php //echo _uc($_e['Product']); ?></a></li>-->
        <?php } ?>
        
        <!-- ################ Something extra product ############## -->
        <?php if($functions->developer_setting('something_extra')=='1') { ?>
            <!--<li><a href="#something_extra" role="tab" data-toggle="tab"><?php //echo _uc($_e['Something Extra']); ?></a></li>-->
        <?php } ?>
        
        <!-- ################ do not forget product ############## -->
        <?php if($functions->developer_setting('donotForgetProducts')=='1') { ?>
            <!--<li><a href="#donotForgetProducts" role="tab" data-toggle="tab"><?php //echo _uc($_e['Do not forget']); ?></a></li>-->
        <?php } ?>

        <!-- ################ Free shipping price limit OR check out products ############## -->
        <?php if($functions->developer_setting('check_out_offer')=='1' || $functions->developer_setting('free_shipping_price')=='1') { ?>
            <!--<li><a href="#offers" role="tab" data-toggle="tab"><?php //echo _uc($_e['Check Out Offer']); ?></a></li>-->
        <?php } ?>
        
        
        <!-- ################ Bundle Products Category ############## -->
        <?php //if( $functions->developer_setting('product') == '1' && $functions->developer_setting('staple_product') == '1' ) { ?>
            <!--<li><a href="#staple" role="tab" data-toggle="tab"><?php //echo _uc($_e['Staple Products']); ?></a></li>-->
        <?php //}
        
        ?>

        <!-- ################ Show graphs on dashboard ############## -->
        <?php if($functions->developer_setting('dashboard_graph_setting')=='1') { ?>
            <!--<li><a href="#dashboard_graph_setting" role="tab" data-toggle="tab"><?php //echo _uc($_e['Dashboard']); ?></a></li>-->
        <?php } ?>

        <!-- ################ Payments method additional price ############## -->
        <?php if( $functions->developer_setting('product') == '1' && $functions->developer_setting('cartSystem') == '1' ) { ?>
            <!--<li><a href="#payment" role="tab" data-toggle="tab"><?php //echo _uc($_e['Payment']); ?></a></li>-->
        <?php } ?>
        

          <!--<li><a href="#auto" role="tab" data-toggle="tab"><?php //echo _n($_e['Send Mail']); ?></a></li>-->



        <?php if( $functions->developer_setting('smtp_change') == '1' ) { ?>
            <li><a href="#smtp" role="tab" data-toggle="tab"><?php echo _n($_e['SMTP Server']); ?></a></li>
        <?php } ?>

        <?php if( $functions->developer_setting('salesFeature') == '1' ) { ?>
            <!--<li><a href="#salesFeature" role="tab" data-toggle="tab"><?php //echo _n($_e['Sales Offer Features']); ?></a></li>-->
        <?php } ?>

        <?php //if( $functions->developer_setting('salesFeature') == '1' ) { ?>
            <!--<li><a href="#priceCalculate" role="tab" data-toggle="tab"><?php //echo _n($_e['Price Calculation']); ?></a></li>-->
        <?php //} ?>

    </ul>


    <div class="container-fluid">
        <form action="" method="post" class="form-horizontal">
            <?php $functions->setFormToken('IBMSSetting'); ?>

            <!-- Tab panes -->
            <div class="tab-content">

                <!-- ################ General Setting ############## -->
                <div class="tab-pane fade in active container-fluid" id="home">
                    <h2  class="tab_heading"><?php echo _uc($_e['General']); ?></h2>
                    <?php $setting->generalSetting($settingData); ?>
                </div>
                <!-- ################ General Setting  End ############## -->

                <?php
                    ################ Social Links ##############
                //Check If socialLinks is allow in project
                if($functions->developer_setting('hasSocialLinks')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="social">
                        <h2  class="tab_heading"><?php echo _uc($_e['Social']); ?></h2>
                        <?php $setting->socialSetting($settingData); ?>
                    </div>
                <?php }
                    ################ Social Links End ##############
                ?>


                <?php
                    ################ Reviews / Comments Setting ##############
                //Check If Review is allow in project
                if($ourReview == '1' || $facebookComment == '1') { ?>
                    <div class="tab-pane fade in container-fluid" id="reviews">
                        <?php if($ourReview=='1') { ?>
                            <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Reviews']); ?></h2>
                            <?php $setting->reviewSetting($settingData);
                        }

                        ?>
                        <?php if($facebookComment=='1') { ?>
                            <br>
                            <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Facebook Comment']); ?></h2>
                            <?php $setting->facebookReviewSetting($settingData);
                        }
                        ?>

                    </div>
                <?php }
                    ################ Reviews / Comments Setting End ##############
                ?>


                <?php
                    ############### Products Setting ##############
                //Check If product is allow in project
                if($functions->developer_setting('product')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="product">
                        <h2  class="tab_heading"><?php echo _uc($_e['Product']); ?></h2>
                        <?php $setting->productSetting($settingData);

                        if($functions->developer_setting('askQuestion') == '1'){
                            echo "<h2 class='tab_heading borderIfNotabs'>"._uc($_e['Products Ask Question'])."</h2>";
                            $setting->askQuestionSetting($settingData);
                        }

                        if($functions->developer_setting('grid_view') == '1'){
                            echo "<h2 class='tab_heading borderIfNotabs'>"._uc($_e['Grid View By Category'])."</h2>";
                            $setting->grid_view_setting($settingData);
                        }

                        ?>
                    </div>
                <?php }
                ############### Products Setting END ##############
                ?>
                
                <?php
                ################ something extra product ##############
                if($functions->developer_setting('something_extra')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="something_extra">
                    <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Add something extra product']); ?></h2>
                    <?php  
                        // var_dump( $settingData);
                        $setting->something_extra_products($settingData); ?>
                    </div>
                <?php }
                    ################ some thing extra product END ##############
                ?>
                
                
                <?php
                ################ do not forget product ##############
                if($functions->developer_setting('donotForgetProducts')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="donotForgetProducts">
                    <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Do not forget products']); ?></h2>
                    <?php  
                        $setting->do_not_forget_products($settingData); 
                    ?>
                    <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Do not forget products popup']); ?></h2>
                    <?php  
                        $setting->do_not_forget_products_popup($settingData); 
                    ?>    
                    </div>
                    
                    
                <?php }
                    ################ do not forget product END ##############
                ?>


                <?php
                ################ Free shipping price limit OR check out products ##############
                //Checkout offer setting,
                if($functions->developer_setting('check_out_offer')=='1' || $functions->developer_setting('free_shipping_price')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="offers">

                        <?php //free_shipping_price setting,
                        if($functions->developer_setting('free_shipping_price')=='1') { ?>
                            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Free Shipping Offer']); ?></h2>
                            <?php $setting->free_shipping_offer($settingData); ?>
                        <?php } ?>
                            <hr>
                        <?php //Checkout offer setting,
                        if($functions->developer_setting('check_out_offer')=='1') { ?>
                            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Check Out Offer']); ?></h2>
                            <?php $setting->checkOutOffer($settingData); ?>
                        <?php } ?>

                        <?php ################# FREE GIFT DEFAULT PRODUCT ################# ?>
                        <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Free Gift Product']); ?></h2>
                        <?php $setting->free_default_product($settingData); ?>


                        <?php ################# 3 for 2 Category ################# ?>
                        <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['3 for 2 Category Offer']); ?></h2>
                        <?php 
                            // $setting->two_for_3_category($settingData); 
                            $setting->threeForTwo_cat($settingData); 

                        ?>
                    </div>
                <?php }
                    ################ Free shipping price limit OR check out products END ##############
                ?>


                <?php
                    ################ Show graphs on dashboard ##############
                //dashboard graph setting, which graph show in dashboard... other all graph will show on their proper page
                if($functions->developer_setting('dashboard_graph_setting')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="dashboard_graph_setting">
                        <h2  class="tab_heading"><?php
                            echo _uc($_e['Dashboard']);
                            echo "<br><small>"._uc($_e['Which Graph report you want to show on dashboard'])."</small>";
                            ?>
                        </h2>
                        <?php $setting->dashboard_graph_setting($settingData); ?>
                    </div>
                <?php }
                    ################ Show graphs on dashboard END##############
                ?>
                
                
                <?php
                ############### Staple Products ##############
                //Check if staple products allow in project
                if($functions->developer_setting('staple_product')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="staple">

                        <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Staple Product Category']); ?></h2>
                        <?php $setting->stapleProduct_cat($settingData); ?>

                        <h2  class="tab_heading"><?php echo _uc($_e['Staple Product Settings']); ?></h2>
                        <?php $setting->stapleProductSetting($settingData);

                        ?>
                    </div>
                <?php }
                ############### Products Setting END ##############
                ?>



                <?php
                ############### Staple Products ##############
                //Check if staple products allow in project
                if($functions->developer_setting('salesFeature') == '1') { ?>
                    <div class="tab-pane fade in container-fluid" id="salesFeature">
                        <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Super Offer Default Settings']); ?></h2>
                        <?php $setting->salesFeatureSetting($settingData); ?>
                    </div>
                <?php }
                ############### Products Setting END ##############
                ?>


                <?php
                ############### Price Calculation Country Wise ##############
                //Check if staple products allow in project
                //if($functions->developer_setting('salesFeature') == '1') { ?>
                
                
                
           
            
            
            
            
            
            
                    <div class="tab-pane fade in container-fluid" id="priceCalculate">

                        <h2 class="tab_heading borderIfNotabs"><?php //echo _uc($_e['Staple Product Category']); ?></h2>
                        <?php //$setting->stapleProduct_cat($settingData); ?>


     
                      
                <br>
                <br>
                
                <table class="table table-striped table-hover">
                <tbody>
                <tr><td colspan="4"><p style="color: red">** You can Sort CH Products By Clicking Here ...</p></td></tr>
                <tr>
                <th>  <input type="button" class="btn btn-primary" id="" value="Update Sorting" onclick="updateSort()"></th>
                </tr>
                
                

            </tbody></table>
            
            
            
            
            
                        <h2  class="tab_heading"><?php echo _uc($_e['Price Calculation']); ?></h2>
                        <?php $setting->countryPriceCalculation($settingData);

                        ?>
                    </div>
                <?php //}
                ############### Products Setting END ##############
                ?>



                <?php
                ################ Payments method additional price ##############
                //dashboard graph setting, which graph show in dashboard... other all graph will show on their proper page
                if($functions->developer_setting('dashboard_graph_setting')=='1') { ?>
                    <div class="tab-pane fade in container-fluid" id="payment">
                        <h2 class="tab_heading"><?php
                            echo _uc($_e['Payment method additional price']);
                            ?>
                        </h2>
                        <?php $setting->payment_setting($settingData); ?>
                    </div>
                <?php }
                ################ Payments method additional price END ##############
                ?>
                




                 <div class="tab-pane fade in container-fluid" id="auto">

                    <h2 class="tab_heading">

                        <?php
                        echo _n($_e['Run Daily Cron File']);
                        ?>
                    </h2>




            <table class="table table-striped table-hover">
            <tbody>
          
            <tr>
            <th>  <input type="button" class="btn btn-primary" value="<?php
                        echo _n($_e['Run']);
                        ?>" onclick="runCronAll()"></th>
            </tr>



            </tbody>
        </table>


  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>  <br>
                <br>

                  
                </div>



                <div class="tab-pane fade in container-fluid" id="smtp">

                    <h2 class="tab_heading"><?php
                        echo _n($_e['SMTP Server Default']);
                        ?>
                    </h2>
                    <?php $setting->smtp_setting_default($settingData); ?>

                    <?php if($functions->developer_setting('newsletter_smtp')=='1'){ ?>
                    <h2 class="tab_heading"><?php
                        echo _n($_e['SMTP Server For Newsletter']);
                        ?>
                    </h2>
                    <?php $setting->smtp_setting_newsletter($settingData); ?>
                    <?php } ?>
                </div>

            </div>

            <button type="submit" class="btn btn-primary btn-lg"><?php echo _u($_e['SAVE']); ?></button>
        </form>
    </div>
    
<script type="text/javascript">
    function addStapleSlot(){
        var mainDiv = 'staple_quantity';

        $.ajax({
            url: 'setting/setting_ajax.php?page=addStapleQuantity',
            type: 'POST'
        }).done(function(res){
            $('.staple_quantity').append(res);
            console.log(res);
        });
    }

    var next_count = 0;
    function addSalesFeatureSlot(count){

        next_count += count+1;
        // console.log(next_count);

        $.ajax({
            url: 'setting/setting_ajax.php?page=addSalesFeature',
            type: 'POST',
            data: { prod_count: next_count }
        }).done(function(res){
            $('.salesFeature_main').append(res);
            $('.salesFeatureSetting').multiselect(
              {
                includeSelectAllOption: true,
                enableHTML      : true,
                filterPlaceholder: 'Search for something...',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true
              }
            );
            // console.log(res);
        });
    }



        function runCronAll(){
        // var divide = parseFloat($('#divide_'+curId).val());
        // var multiply = parseFloat($('#multiply_'+curId).val());

        if(secure_delete('Are you sure you want to run ?')){

            window.location = 'https://sharkspeed.se/cron/cronTasks.php?time=ibms';
       
        }
    }
    
            function updateSort(){
        // var divide = parseFloat($('#divide_'+curId).val());
        // var multiply = parseFloat($('#multiply_'+curId).val());

        if(secure_delete('Are you sure you want to update ?')){
            $.ajax({
                type: 'POST',
                url: 'setting/setting_ajax.php?page=massImgSort',
                data: { imgId: "0"}
            }).done(function(res){
                
                console.log(res);
                if(res == '1'){
                    jAlertifyAlert('Product Sort Updated Successfully.');
                }else if(res == '0'){
                    jAlertifyAlert('Something Went Wrong, Please Try Again!');
                }
            });
        }
    }
    
    
    
    function updatePrice(curId){
        var divide = parseFloat($('#divide_'+curId).val());
        var multiply = parseFloat($('#multiply_'+curId).val());

        if(secure_delete('Are you sure you want to update ?')){
            $.ajax({
                type: 'POST',
                url: 'setting/setting_ajax.php?page=massPriceUpdateCH',
                data: { curId: curId, divide: divide, multiply: multiply }
            }).done(function(res){
                if(res == '1'){
                    jAlertifyAlert('Product Prices Updated Successfully.');
                }else if(res == '0'){
                    jAlertifyAlert('Something Went Wrong, Please Try Again!');
                }
            });
        }
    }
</script>
<?php return ob_get_clean(); ?>