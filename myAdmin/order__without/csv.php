<?php

if (isset($_GET['newexport'])) {
    include_once(__DIR__."/export.php");
}
ob_start();
global $_e;
global $adminPanelLanguage;
$_w = array();
$_w['Sales Report'] = '';
$_w['Export'] = '';
$_w['New Export Stock'] = '';
$_w['Submit']       = '';
$_w['Export Product Data with size color']       = '';
$_w['Export Product Data']       = '';
$_w['Import Stock'] = '';
$_w['Stock Inventory Exported File'] = '';
$_w['Select Currency']= ''; 
$_w['Model.No']= ''; 
$_w['Country Code']= ''; 
$_w['Label']= ''; 
$_w['Size']= ''; 
$_w['Color']= ''; 
$_w['Categories']= ''; 
$_w['Date From']= '';
$_w['Date To']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';



$_w['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.'] = '';
$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Stock Import');
?>
    <h4 class="sub_heading"><?php echo _uc($_e['Import/Export']); ?></h4>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Sales Report']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2 class="tab_heading"><?php echo _uc($_e['Sales Report']); ?></h2>
           

            <div class="">
                <?php # echo _uc($_e['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.']); ?>   
            </div>

            <a style="display: none;" href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="btn btn-primary btn-lg"><?php echo _uc($_e['New Export Stock']); ?></a> 

            <div class="container-fluid">
                <form action="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="form-horizontal" method="post"
                enctype="multipart/form-data">
                    <!-- <div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">Select Currency</label>
                        <div class="col-sm-10 col-md-9" style="padding-left: 0;padding-right: 0;">
                            <select name="cur_ids" required="" class="form-control">
                                <option value="" disabled="" selected=""> <?php //echo ($_e['Select Currency']); ?>   </option>                           
                                <?php 
                                    // $sqli        = "SELECT * FROM `currency` ORDER BY `cur_country` DESC";
                                    // $datas = $dbF->getRows($sqli);
                                    // $option     = '';
                                    // foreach($datas as $vali){
                                ?>
                                    <option value='<?php //echo $vali["cur_id"]; ?>'><?php //echo $vali["cur_country"]; ?></option>

                                <?php //}  ?>
                            </select>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">Date From</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i> 
                            </div>
                            <input type="text" name="min" id="min1"  class="form-control date" placeholder="<?php echo _uc($_e['Date From']) ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">Date To</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i> </div>
                            <input type="text" name="max" id="max1"  class="form-control date" placeholder="<?php echo _uc($_e['Date To']) ?>" autocomplete="off">
                        </div>
                    </div>
                    <input type="submit" name="csvExportnewsizecolor" value="<?php echo $_e['Export']; ?>" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>

<script>
    $(function(){
        dateJqueryUi();
    });
</script>

<?php return ob_get_clean(); ?>