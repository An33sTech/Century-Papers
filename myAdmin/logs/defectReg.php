<?php
ob_start();

require_once("classes/logs.class.php");
global $dbF;

$logs=new logs();
//$dbF->prnt($_POST);
//exit;
defectAdd();
function defectAdd(){
    global $db;
    global $dbF;
    global $functions;
    global $_e;

    if(isset($_POST['insertId']) && $_POST['insertId']!=""){
        try {
            if(!$functions->getFormToken('defectForm')){ return false;}

            $db->beginTransaction();

            $RowId=$_POST['insertId'];

            $sql="UPDATE `defected` SET
                `customerName`=?,
                `store_id` = ?,
                `vendorName`=?,
                `orderNo`=?,
                `iComment`=?,
                `vComment`=?,
                `isVendor`=?,
                `vCreateDate`=?,
                `returnDate`=?,
                `updateD`= ? WHERE id = ?";

                @$array=array($_POST['customerName'],
                $_POST['receipt_store_id'],
                $_POST['vendorName'],
                $_POST['invoice'],
                $_POST['whatwedo'],
                $_POST['vendorComment'],
                $_POST['vendorCreate'],
                $_POST['vendorDate'],
                $_POST['date'], 1, $RowId);

            $run=$dbF->setRow($sql,$array,false);

            $lastId=$RowId;

            $form_prod='';
            $i=0;

            $cart = empty($_POST['cart_list'])? array():$_POST['cart_list'];
            foreach($cart as $itemId){
                $id=$itemId;
                $pid=$itemId;

                $temp="pName_".$id;
                $p_name=$_POST[$temp];

                $temp="pqty_".$id;
                $qty=$_POST[$temp];

                $temp="pdesc_".$id;
                $pDesc=$_POST[$temp];


                    $temp="img_".$id;
                $img_=$_POST[$temp];





                $qry_order="INSERT INTO `defectedorder`
                (
                `defectedId`,
                `pName`,
                `pQty`,
                `img_`,
                `pDesc`
                )
                VALUES
                (
                '$lastId',
                ?,
                ?,
                ?,
                ?
                )";

                $arry=array($p_name,$qty,$img_,$pDesc);
                $dbF->setRow($qry_order,$arry,false);
                $i++;
            }

            if($_POST['new']=="YES"){
                $up="Add";
            }else{
                $up="Update";
            }

            if($dbF->rowCount>0){
                $functions->notificationError(_fu($_e['Defect']),_fu($_e['Defected registration save successfully']),'btn-success');
                $functions->setlog(_fu($_e['Added']),_fu($_e['Defect']),$id,_fu($_e['Defected registration save successfully']),false);
            }else{
                $functions->notificationError(_fu($_e['Defect Error']),_fu($_e['Defected registration save Fail']),'btn-danger');
            }

            $db->commit();
        } catch(PDOExecption $e) {
            $db->rollBack();
            $dbF->error_submit($e);
        }
    }

}
echo '<h4 class="sub_heading">'. _uc($_e['Defect Registration']) .'</h4>';

$isEdit = false;
$defectData = array();
$defectProduct = array();
$new = 'YES';


/*$isEdit = true;
$editId= '367';
$rowLastId = $editId;
$defectData = $logs->defectData($editId);
$defectProduct = $logs->defectProducts($editId);
$new = 'NO';*/

if(isset($_POST['insertId']) || isset($_POST['editId'])){
    $isEdit =  true;
    $editId = isset($_POST['insertId']) ? $_POST['insertId'] : $_POST['editId'];
    $rowLastId = $editId;
    $new = 'NO';
    $defectData = $logs->defectData($editId);
    $defectProduct = $logs->defectProducts($editId);
} else if($isEdit==false){
    //Add new
    $date=date('Y-m-d');
    $sqlD="SELECT * FROM defected  WHERE `updateD` ='0' ";
    // AND dateTime<'$date'
    $dataDel=$dbF->getRows($sqlD);

    foreach($dataDel as $key=>$val){
        $id=$val['id'];
        $sql3="SELECT * FROM defect_image WHERE defect_id='$id'";
        $data=$dbF->getRows($sql3);
        foreach($data as $key=>$val){
            unlink(__DIR__."/../../images/$val[image]");
        }
        $sql2="DELETE FROM defected WHERE id='$id'";
        $dbF->setRow($sql2);
    }

    $sql="INSERT INTO `defected`(`updateD`) VALUES ('0')";
    $dbF->setRow($sql);
    $rowLastId = $dbF->rowLastId;
}
?>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Register']); ?></a></li>
        <li><a href="#tab_images" role="tab" data-toggle="tab"><?php echo _uc($_e['Defect Images']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['New Defect Registration']); ?></h2>

        <form action="" method="post" class="form-horizontal">

            <?php $functions->setFormToken('defectForm'); ?>

            <input type="hidden" id="insertId" name="insertId" value="<?php echo $rowLastId; ?>" />
            <input type="hidden" name="new" value="<?php $new ?>" />
            <div class="col-sm-12">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Date']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <?php
                                $today = date('Y-m-d');
                                if($isEdit){
                                    $today = $defectData['returnDate'];
                                }
                            ?>
                            <input type="text" id="date" name="date" value="<?php echo $today; ?>" class="form-control date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customerName" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Customer Name']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <?php
                                $name = "";
                                if($isEdit){
                                    $name = $defectData['customerName'];
                                }
                            ?>
                            <input type="text" id="customerName" value="<?php echo $name; ?>" name="customerName" class="form-control" placeholder="<?php echo _uc($_e['Customer Name']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="invoice" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Invoice Number']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <?php
                                $invoice = "";
                                if($isEdit){
                                    $invoice = $defectData['customerName'];
                                }
                            ?>
                            <input type="text" id="invoice" value="<?php echo $invoice; ?>" name="invoice" class="form-control" placeholder="<?php echo _uc($_e['Invoice Number']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="whatwedo" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['What We Do? (comment)']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <?php
                                $invoice = "";
                                if($isEdit){
                                    $invoice = $defectData['orderNo'];
                                }
                            ?>
                            <textarea id="whatwedo" name="whatwedo" class="form-control" placeholder="<?php echo _uc($_e['Enter Comment']); ?>"><?php echo $invoice; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="receipt_store_id" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Select Store']); ?></label>
                        <div class="col-sm-10 col-md-9">
                            <input type="hidden" name="receipt_store_id" class="form-control receipt_store_id" data-val="" required>
                            <fieldset id ="store">
                                <select required name="receipt_store_id"  id="receipt_store_id" class="form-control product_color">
                                    <option value=""><?php echo _uc($_e['Select Store']); ?></option>
                                    <?php
                                    echo $logs->storeNamesOption();
                                    ?>
                                </select>
                                <?php
                                $select = "";
                                if($isEdit){
                                    $select = $defectData['store_id'];
                                }
                                ?>
                                <script>
                                    $(document).ready(function(){
                                        $('#receipt_store_id').val('<?php echo $select; ?>').change();
                                    });
                                </script>
                            </fieldset>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendorName" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Vendor Name']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <?php
                            $vName = "";
                            if($isEdit){
                                $vName = $defectData['vendorName'];
                            }
                            ?>
                            <!--Asad Start From Here temporary-->
                            <input type="text" value="<?php echo $vName; ?>" id="vendorName" name="vendorName" class="form-control" placeholder="<?php echo _uc($_e['Vendor Name']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Create By Vendor?']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <div class="make-switch" data-off="warning" data-on="success" data-on-label="<?php echo _uc($_e['Yes']); ?>" data-off-label="<?php echo _uc($_e['No']); ?>">
                                <?php
                                $isVendor = "";
                                $checked  = "";
                                if($isEdit){
                                    $isVendor = $defectData['isVendor'];
                                    if($isVendor=='Yes'){
                                        $checked = 'checked';
                                    }
                                }
                                ?>
                                <input type="checkbox" <?php  echo $checked; ?> id="vendorCreate" name="vendorCreate" onchange="if($(this).is(':checked')){$('.vendorDate,.vendorComment').stop().slideToggle(500);}" value="Yes">
                            </div>
                        </div>
                    </div>

                    <?php
                    $display = "displaynone";
                    if($checked=="checked"){
                        $display = "";
                    }
                    ?>
                    <div class="form-group vendorDate <?php echo $display; ?>">
                        <label for="vendorDate" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Date']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <input type="text" id="vendorDate" required="" name="vendorDate" value="<?php echo $today; ?>" class="form-control date" placeholder="<?php echo _uc($_e['Vendor Date']); ?>">
                        </div>
                    </div>

                    <div class="form-group vendorComment <?php echo $display; ?>">
                        <label for="vendorComment" class="col-sm-2 col-md-3 control-label"><?php echo _uc($_e['Vendor Comment']); ?></label>
                        <div class="col-sm-10  col-md-9">
                            <?php
                            $vComm = "";
                            if($isEdit){
                                $vComm = $defectData['vComment'];
                            }
                            ?>
                            <textarea id="vendorComment" name="vendorComment" class="form-control"  placeholder="<?php echo _uc($_e['Enter Comment']); ?>"><?php echo $vComm; ?></textarea>
                        </div>
                    </div>

                </div>

            <div class="clearfix"></div>

                <hr><br>

                <div class="table-responsive bootTable" >
                    <table id="selected" class="table sTable table-hover" style="min-width: 570px;" width="100%" border="0" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th><?php echo _u($_e['PRODUCT']); ?></th>
                            <th><?php echo _u($_e['Thumbnail']); ?></th>
                            <th><?php echo _u($_e['PRODUCT SCALE']); ?></th>
                            <th><?php echo _u($_e['PRODUCT COLOR']); ?></th>
                            <th><?php echo _u($_e['QTY']); ?></th>
                            <th><?php echo _u($_e['DESCRIPTION']); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <td>
                                <input type="text" class="form-control" id="receipt_product_id" placeholder="<?php echo _uc($_e['Enter Product Name']); ?>">
                                <input type="hidden" class="form-control receipt_product_id" data-val="">
                            </td>



                             <td>
                               <img src="https://sharkspeed.se/myAdmin/images/logo_ibms.png" id="image_from_url" width="100" height="100">
                            </td>




                            <td>
                                <input type="text" class="form-control" id="receipt_product_scale" placeholder="<?php echo _uc($_e['Enter Product Scale']); ?>" readonly value="No Scale Avaiable">
                                <input type="hidden" class="form-control receipt_product_scale" data-val="">
                            </td>
                            <td>
                                <input type="text" class="form-control" required id="receipt_product_color" placeholder="<?php echo _uc($_e['Enter Product Color']); ?>" readonly value="No Color Avaiable">
                                <input type="hidden" class="form-control receipt_product_color" data-val="">
                            </td>
                            <td>
                                <input type="number" class="form-control" min="1" id="receipt_qty" placeholder="<?php echo _uc($_e['Enter Product Quantity']); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="receipt_desc" placeholder="<?php echo _uc($_e['Enter Defect Description']); ?>">
                            </td>
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <div class="col-sm-10">
                        <button type="button" onclick="receiptFormValid();" id="AddProduct" class="btn btn-default"><?php echo _uc($_e['Add Product']); ?></button>
                    </div>
                </div>

                <div style="margin:50px 0 0 0;">
                    <input type="button" class="btn btn-danger" onclick="removechecked()" value="<?php echo _uc($_e['Remove Checked Items']); ?>" >
                    <input type="button" class="btn btn-danger" onclick="uncheckall()" value="<?php echo _uc($_e['Check/Uncheck All']); ?>">
                    <br><br>

                    <div class="table-responsive" >
                        <table id="selected" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th><?php echo _u($_e['SNO']); ?></th>
                                <th><?php echo _u($_e['PRODUCT']); ?></th>
                                <th><?php echo _u($_e['Thumbnail']); ?></th>
                                <th><?php echo _u($_e['QTY']); ?></th>
                                <th><?php echo _u($_e['DESCRIPTION']); ?></th>
                            </tr>
                            </thead>
                            <tbody id="vendorProdcutList">

                            </tbody>

                        </table>
                    </div>
                <?php if($isEdit){ ?>

                    <div class="table-responsive" >
                        <h2>Save Products</h2>
                        <table id="selected" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th><?php echo _uc($_e['PRODUCT']); ?></th>
                                <th><?php echo _uc($_e['Thumbnail']); ?></th>
                                <th><?php echo _uc($_e['QTY']); ?></th>
                                <th><?php echo _uc($_e['DESCRIPTION']); ?></th>
                                <th><?php echo _uc($_e['ACTION']); ?></th>
                            </tr>
                            </thead>
                            <tbody id="vendorProdcutList2">
                                <?php

                                $sql2="SELECT * FROM defectedorder WHERE `defectedId`='$rowLastId' ORDER BY id ASC";
                                $data2=$dbF->getRows($sql2);
                                echo "<ol style='margin:30px;'>";
                                foreach($data2 as $key2=>$val2){
                                    echo "<tr>";
echo "<td>$val2[pName] </td><td><img src='$val2[img_]' width='100' height='100'></img> </td><td>$val2[pQty]</td><td>$val2[pDesc]</td><td><a class='defectProduct' id='$val2[id]'>". _uc($_e['Delete']) ."</a></td>";
                                    echo "</tr>";
                                }
                                echo "</ol>";
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                    <br>
                    <input type="submit" onclick="return formSubmit();" name="submit" value="SAVE " class="submit btn btn-primary btn-lg">

                </div> <!-- add product script div end -->

            </div>
        </form>
    </div>


        <div class="tab-pane container-fluid fade" id="tab_images">
            <h2 class="tab_heading"><?php echo _uc($_e['Defect Images']); ?></h2>
            <input type="hidden" id="AjaxFileNewId" name="ProductNewId" value="<?php echo $rowLastId; ?>">
            <input type="hidden" id="AjaxFileNewPage" value="defect">

            <div id="dropbox">
                <span class="message"><?php echo _uc($_e['Drop images here to upload.']); ?><br />
                                    <i>(<?php echo _fu($_e['they will only be visible to you']); ?>)</i></span>
                <?php
                // if product edit
                if($isEdit){
                    $logs->defectEditImages($editId);
                }
                ?>
            </div>
        </div>



    <script>
        $(document).ready(function(){
            dateJqueryUi();
        });

            $(function() {









                productId="#receipt_product_id";
                productHiddenClass = ".receipt_product_id";

                var availableTags = <?php $logs->productF->productJSON(); ?>;
                $(productId).autocomplete({
                    source: availableTags,
                    minLength: 0,
                    select: function( event, ui ) {
    $(productHiddenClass).val(ui.item.id);








$.ajax({
type: 'POST',
url: 'logs/logs_ajax.php?page=pImg',
data:'idz_pod='+ui.item.id,
success:function(html){
console.log(html);
// $('#place_of_delivery').html(html);

 $("#image_from_url").attr("src", html);



}
});









    $(productHiddenClass).attr("data-val",ui.item.label);
                        scale(ui.item.scale);
                        color(ui.item.color);
                    }
                }).on('focus : click', function(event) {
                        $(this).autocomplete("search", "");
                });
            });

    </script>


        <script src="logs/js/logs.php"></script>

        <div id="defectDel" style="display:none;">Use for jquery load</div>

        <!-- Modal use in modal div-->
        <div class="modal fade bs-example-modal-lg" id="productImgDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo _uc($_e['Image Preview']); ?></h4>
                    </div>
                    <div class="modal-body" style="text-align: center">
                        <img src="" align="center"  />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _uc($_e['Close']); ?></button>
                    </div>
                </div>
            </div>
        </div>

<?php return ob_get_clean(); ?>