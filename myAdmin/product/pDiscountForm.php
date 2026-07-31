<?php



ob_start();



$product = new product();



$discount = new discount();



addDiscount();



if(!isset($_GET['pId'])){



    echo "Please go in Product View And select Product For Discount";



    return ob_get_clean();



}











$pId    =   $_GET['pId'];



$editDiscount = true;



$discountSettingData    = $discount->discountSettingData($pId);



$discountPriceData      = array();



if($discountSettingData==false){



    $editDiscount = false;



}else{



    $discountPriceData  = $discount->discountPriceData($pId);



}



global $dbF;



//var_dump($discountSettingData);











function addDiscount(){
    global $dbF, $db, $functions, $product, $_e;

    if(!empty($_POST)){
        if(isset($_POST['discount']) && !empty($_POST['curlist']) && isset($_POST['pId'])){
            if(!$functions->getFormToken('discountForm')){
                return false;
            }


            $priceCalc = $functions->ibms_setting('priceCalc');
            $priceCalc = unserialize($priceCalc);

            $country_fr     = $priceCalc['country'][0];
            $fr_divide      = $priceCalc['divide'][0];
            $fr_multiply    = $priceCalc['multiply'][0];

            $country_de     = $priceCalc['country'][1];
            $de_divide      = $priceCalc['divide'][1];
            $de_multiply    = $priceCalc['multiply'][1];

            $country_nl     = $priceCalc['country'][2];
            $nl_divide      = $priceCalc['divide'][2];
            $nl_multiply    = $priceCalc['multiply'][2];

            $country_us     = $priceCalc['country'][3];
            $us_divide      = $priceCalc['divide'][3];
            $us_multiply    = $priceCalc['multiply'][3];

            $country_be     = $priceCalc['country'][4];
            $be_divide      = $priceCalc['divide'][4];
            $be_multiply    = $priceCalc['multiply'][4];

            $country_uk     = $priceCalc['country'][5];
            $uk_divide      = $priceCalc['divide'][5];
            $uk_multiply    = $priceCalc['multiply'][5];

            $country_es     = $priceCalc['country'][6];
            $es_divide      = $priceCalc['divide'][6];
            $es_multiply    = $priceCalc['multiply'][6];

            $country_at     = $priceCalc['country'][7];
            $at_divide      = $priceCalc['divide'][7];
            $at_multiply    = $priceCalc['multiply'][7];

            $country_it     = $priceCalc['country'][8];
            $it_divide      = $priceCalc['divide'][8];
            $it_multiply    = $priceCalc['multiply'][8];

            $country_chf    = $priceCalc['country'][9];
            $chf_divide     = $priceCalc['divide'][9];
            $chf_multiply   = $priceCalc['multiply'][9];

            $country_other    = $priceCalc['country'][10];
            $other_divide     = $priceCalc['divide'][10];
            $other_multiply   = $priceCalc['multiply'][10];


            try{
                $db->beginTransaction();

                if($_POST['edit']!=''){
                    $sql = "DELETE FROM `product_discount` WHERE product_discount_pk = '$_POST[edit]'";
                    $dbF->setRow($sql);
                }

                $pId = $_POST['pId'];
                
                $sql = "INSERT INTO `product_discount`(`discount_PId`, `product_dis_status`) VALUES (?,?)";
                
                $status = isset($_POST['discount']['status']) ? '1':'0';
                $array = array($pId,$status);
                $dbF->setRow($sql,$array,false);
                
                $lastId = $dbF->rowLastId;
                $new_pid = $pId;
                
                //Discount Setting
                $sql ="";
                
                $sql = "INSERT INTO `product_discount_setting`(`product_dis_id`, `product_dis_name`, `product_dis_value`) VALUES ";

                $array = "";
                $array = array();
                $array1 = array();
                
                foreach($_POST['discount'] as $key=>$post){
                    if($key=='status'){
                        continue;
                    }
                    
                    $sql .= "(?,?,?),";
                    
                    $array[] = $lastId;
                    $array[] = $key; //Key name use In queryies to filter
                    $array[] = $post;
                    $array1[] = $new_lastID;
                    $array1[] = $key; //Key name use In queryies to filter
                    $array1[] = $post;
                }
                
                $sql = trim($sql,',');
                $dbF->setRow($sql,$array,false);

                
                //Discount Setting End
                //Discount Prices
                
                $sql = "";
                $sql = "INSERT INTO `product_discount_prices`(`product_dis_id`, `product_dis_curr_Id`, `product_dis_price`, 
                `product_dis_intShipping`) VALUES ";
                
                $array = "";
                $array = array();
                $array_new = array();
                
                foreach($_POST['curlist'] as $key=>$post){
                    $intShipping = 'intShipping_'.$key;
                    $intShipping = isset($_POST[$intShipping]) ? '1' : '0';

                    //do not use any other values, because it is use in other $sql queries
                    $sql .= "(?,?,?,?),";
                    
                    $array[] = $lastId;
                    $array[] = $key;
                    $array[] = empty($post)?'0':$post;
                    $array[] = $intShipping;
                    
                    if($key == '20'){
                        $array_new[] = $new_lastID;
                        $array_new[] = $key;
                        $chf_price = empty($post)?'0':$post;
                        
                        if($_POST['discount']['discountFormat'] == 'price' && !empty($post)){
                            $sek_price      = intval($post);
                        
                            $fr_price       = doubleval(($sek_price/$fr_divide)*$fr_multiply);
                            $fr_price       = ceil($fr_price);

                            $de_price       = doubleval(($sek_price/$de_divide)*$de_multiply);
                            $de_price       = ceil($de_price);

                            $nl_price       = doubleval(($sek_price/$nl_divide)*$nl_multiply);
                            $nl_price       = ceil($nl_price);

                            $us_price       = doubleval(($sek_price/$us_divide)*$us_multiply);
                            $us_price       = ceil($us_price);

                            $be_price       = doubleval(($sek_price/$be_divide)*$be_multiply);
                            $be_price       = ceil($be_price);

                            $uk_price       = doubleval(($sek_price/$uk_divide)*$uk_multiply);
                            $uk_price       = ceil($uk_price);

                            $es_price       = doubleval(($sek_price/$es_divide)*$es_multiply);
                            $es_price       = ceil($es_price);

                            $at_price       = doubleval(($sek_price/$at_divide)*$at_multiply);
                            $at_price       = ceil($at_price);

                            $it_price       = doubleval(($sek_price/$it_divide)*$it_multiply);
                            $it_price       = ceil($it_price);

                            $chf_price      = doubleval(($sek_price/$chf_divide)*$chf_multiply);
                            $chf_price      = ceil($chf_price);

                            $other_price      = doubleval(($sek_price/$other_divide)*$other_multiply);
                            $other_price      = ceil($other_price);

                        }

                        $array_new[] = $chf_price;
                        $array_new[] = $intShipping;

                        ######  FR Currency Discount  ######
                        
                        $array_new[] = $new_lastID;
                        $array_new[] = $country_fr;
                        $array_new[] = $fr_price;
                        $array_new[] = $intShipping;

                        #######  DE Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_de;
                        $array_new[]    =   $de_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  NL Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_nl;
                        $array_new[]    =   $nl_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  US Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_us;
                        $array_new[]    =   $us_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  BE Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_be;
                        $array_new[]    =   $be_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  UK Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_uk;
                        $array_new[]    =   $uk_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  ES Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_es;
                        $array_new[]    =   $es_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  AT Currency Discount  ######
                        
                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_at;
                        $array_new[]    =   $at_price;
                        $array_new[]    =   $intShipping;
                        
                        #######  IT Currency Discount  ######

                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_it;
                        $array_new[]    =   $it_price;
                        $array_new[]    =   $intShipping;

                        #######  Other Currency Discount  ######

                        $array_new[]    =   $new_lastID;
                        $array_new[]    =   $country_other;
                        $array_new[]    =   $other_price;
                        $array_new[]    =   $intShipping;
                        
                    }

                }
                
                $sql    =   trim($sql,',');
                $dbF->setRow($sql,$array,false);


                //Discount Prices End
                $db->commit();
                
                if($dbF->rowCount>0) {
                    $temp = $_e["New Product Discount Added with Product Id : {{pId}} And Discount Id : {{id}}"];
                    $temp = _replace('{{pId}}',$pId,$temp);
                    $temp = _replace('{{id}}',$lastId,$temp);
                    $functions->setlog(_uc($_e['Discount']), _uc($_e['Product']), $lastId, $temp);
                    $functions->notificationError(_js($_e['Discount']), _js($_e['Product Discount Save Successfully']), 'btn-success');
                }else{
                    $functions->notificationError(_js($_e['Discount']), _js($_e['Product Discount Save Failed']),'btn-danger');
                }
            }catch (Exception $e){
                $dbF->error_submit($e);
                $db->rollBack();
                $functions->notificationError(_js($_e['Discount']), _js($_e['Product Discount Save Failed']),'btn-danger');
            }



        }//if end



    }// if end isset post



} // function end







?>







    <div class="container-fluid">



        <h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Discount Product Setting']); ?> : <?php echo $product->productF->getProductName($pId); ?></h4>







        <div class="discountForm">



            <form action="" method="post" class="form-horizontal">



                <input type="hidden" name="pId" value="<?php echo $_GET['pId']; ?>"/>



                <input type="hidden" name="edit" value="<?php if($editDiscount)echo $discountSettingData[0]['product_discount_pk'];else echo ""; ?>"/>



                <?php $functions->setFormToken('discountForm'); ?>







                <div class="form-group">



                    <label class="col-sm-3 col-md-2 control-label"



                         for="discountOnOff"><?php echo _uc($_e['Discount']); ?></label>



                    <div class="col-sm-9 col-md-10">



                        <div class="make-switch" data-off="warning" data-on="success">



                            <?php



                                //if edit



                            $status     =    '';



                                if($editDiscount){



                                    if($discountSettingData[0]['product_dis_status']=='1'){



                                        $status = 'checked';



                                    }



                                }



                            ?>



                            <input type="checkbox" name="discount[status]"  id="discountOnOff" value="1"  <?php echo $status; ?>>



                        </div>



                    </div>



                </div>















                <div class="form-group">



                    <label class="col-sm-3 col-md-2 control-label" for="discountFrom"><?php echo _uc($_e['Discount From']); ?></label>



                    <div class="col-sm-9 col-md-10">



                        <?php



                        //if edit



                        $dateFrom     =    '';



                        if($editDiscount){



                                $dateFrom = $discount->discountArrayFound($discountSettingData,'dateFrom');



                        }



                        ?>



                            <input type="text" value="<?php echo $dateFrom; ?>" name="discount[dateFrom]" id="discountFrom" class="form-control from" placeholder="<?php echo _uc($_e['Discount Start Date : Discount will available from start date,Leave blank To Start Now']); ?>"/>



                    </div>



                </div>







                <div class="form-group">



                    <label class="col-sm-3 col-md-2 control-label" for="discountTo"><?php echo _uc($_e['Discount To']); ?></label>



                    <div class="col-sm-9 col-md-10">



                        <?php



                        //if edit



                        $dateTo     =    '';



                        if($editDiscount){



                            $dateTo = $discount->discountArrayFound($discountSettingData,'dateTo');



                        }



                        ?>



                            <input type="text" value="<?php echo $dateTo; ?>" name="discount[dateTo]" id="discountTo" class="form-control to" placeholder="<?php echo _uc($_e['Discount End Date: Leave blank for Always']); ?>"/>



                    </div>



                </div>











                <div class="form-group">



                    <label class="col-sm-3 col-md-2 control-label" for="discountFormat"><?php echo _uc($_e['Discount Deduct In']); ?></label>



                    <div class="col-sm-9 col-md-10">



                        <?php



                        //if edit



                        $discountFormat     =    'price';



                        if($editDiscount){



                            $discountFormat = $discount->discountArrayFound($discountSettingData,'discountFormat');



                        }



                        ?>



                        <select type="text" name="discount[discountFormat]" id="discountFormat" class="form-control to">



                            <option value="price"><?php echo _uc($_e['In Price']); ?></option>



                            <option value="percent"><?php echo _uc($_e['In Percent %']); ?> </option>



                        </select>



                        <script>



                            $(document).ready(function(){



                                $('#discountFormat').val('<?php echo $discountFormat; ?>').change();



                            });



                        </script>



                    </div>



                </div>











                <div class="form-group"><br>



                    <?php $product->discountPricingViewSystem('discountForm',$editDiscount,$discountPriceData); ?></div>



<br>



                <button type="submit" class="btn btn-primary btn-lg" onsubmit="return submitDiscount();"><?php echo _u($_e['SUBMIT']); ?></button>







            </form>



        </div>







    </div>







<script>



    $(document).ready(function(){



        dateRangePicker();



    });



</script>







<?php return ob_get_clean(); ?>