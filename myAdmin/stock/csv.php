<?php

if (isset($_GET['export'])) {
    include_once(__DIR__."/export_csv.php");
}


if (isset($_GET['newexport'])) {
    include_once(__DIR__."/export.php");
}
ob_start();
global $_e;
global $adminPanelLanguage;
$_w = array();
$_w['Export Stock'] = '';
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



$_w['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.'] = '';
$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Stock Import');
?>
    <h4 class="sub_heading"><?php echo _uc($_e['Import/Export']); ?></h4>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Export Stock']); ?></a></li>


        <li><a href="#exp" role="tab" data-toggle="tab"><?php echo _uc($_e['Export Product Data']); ?></a></li>


 <li><a href="#expdata" role="tab" data-toggle="tab"><?php echo _uc($_e['Export Product Data with size color']); ?></a></li>



<li><a href="#import" role="tab" data-toggle="tab"><?php echo _uc($_e['Import Stock']); ?></a></li>


    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2 class="tab_heading"><?php echo _uc($_e['Export Stock']); ?></h2>
            <div class=""><?php echo _uc($_e['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.']); ?></div>
            <a href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&export" class="btn btn-primary btn-lg"><?php echo _uc($_e['Export Stock']); ?></a>

                </div>


                  <div class="tab-pane fade container-fluid" id="import">
            <h2 class="tab_heading"><?php echo _uc($_e['Import Stock']); ?></h2>
            <?php
                ob_start();
                    include_once( 'import_csv.php' );
                echo $importEmail = ob_get_clean();
            ?>
        </div>

        <div class="tab-pane fade container-fluid" id="exp">
            <h2 class="tab_heading"><?php echo _uc($_e['Export Product Data']); ?></h2>
           

        <div class=""><?php #echo _uc($_e['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.']); ?></div>

        <a style="display: none;" href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="btn btn-primary btn-lg"><?php echo _uc($_e['New Export Stock']); ?></a> 

<div class="container-fluid">
<form action="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="form-horizontal" method="post"
enctype="multipart/form-data">

<div class="form-group">
<label><?php ##echo $_e['Export Product Data']; ?> </label>
<!-- <input type="file" name="csv" required/> -->

<select name="cur_id" required="" class="form-control">

<option value="" disabled="" selected="">  <?php echo ($_e['Select Currency']); ?>  </option>  
    
<?php 

 // $data       = $this->productCurrencySqli();
       

 // function productCurrencySqli(){
        $sqli        = "SELECT * FROM `currency` ORDER BY `cur_country` DESC";
        $datas = $dbF->getRows($sqli);

// $dbF->prnt($sql);

    // }


        $option     = '';
        foreach($datas as $vali){


         ?>

<option value='<?php echo $vali["cur_id"]; ?>'><?php echo $vali["cur_country"]; ?> - <?php echo ($_e['Country Code']); ?></option>

<?php }  ?>
</select>
</div>

<input type="submit" name="csvExportnew" value="<?php echo $_e['Export']; ?>" class="btn btn-primary">
</form>
</div>


        </div>




         <div class="tab-pane fade container-fluid" id="expdata">
            <h2 class="tab_heading"><?php echo _uc($_e['Export Product Data with size color']); ?></h2>
           


        <a style="display: none;" href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="btn btn-primary btn-lg"><?php echo _uc($_e['New Export Stock']); ?></a> 



<div class="container-fluid">
<form action="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="form-horizontal" method="post"
enctype="multipart/form-data">

<div class="form-group">
<label><?php ##echo $_e['Export Product Data']; ?> </label>
<!-- <input type="file" name="csv" required/> -->

<select name="cur_ids" required="" class="form-control">

<option value="" disabled="" selected=""> <?php echo ($_e['Select Currency']); ?>   </option>  
    
<?php 

 // $data       = $this->productCurrencySqli();
       

 // function productCurrencySqli(){
        $sqli        = "SELECT * FROM `currency` ORDER BY `cur_country` DESC";
        $datas = $dbF->getRows($sqli);

// $dbF->prnt($sql);

    // }


        $option     = '';
        foreach($datas as $vali){


         ?>

<option value='<?php echo $vali["cur_id"]; ?>'><?php echo $vali["cur_country"]; ?> - <?php echo ($_e['Country Code']); ?></option>

<?php }  ?>
</select>
</div>

<div class="form-group">
<select class="selectpicker form-control" required="" name="multi_select[]" multiple>
  <option value="Model.No"><?php echo ($_e['Model.No']); ?></option>
 <option value="Label"><?php echo ($_e['Label']); ?></option>
  <option value="Size"><?php echo ($_e['Size']); ?></option>
  <option value="Color"><?php echo ($_e['Color']); ?></option>
 <option value="Categories"><?php echo ($_e['Categories']); ?></option>
</select>
</div>




<input type="submit" name="csvExportnewsizecolor" value="<?php echo $_e['Export']; ?> With Inventory" class="btn btn-primary">
<input type="submit" name="csvExportnewsizecolorAll" value="<?php echo $_e['Export']; ?> All" class="btn btn-primary">
</form>
</div></div></div>
<?php return ob_get_clean(); ?>