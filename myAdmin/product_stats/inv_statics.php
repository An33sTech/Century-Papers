<?php

ob_start();



require_once("classes/proStatics.class.php");

global $dbF;



$proStatics  =   new proStatics();




?>

<h2 class="sub_heading"><?php echo _uc($_e['Product Inventory Stats']); ?></h2>



    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist">

        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['All Products']); ?></a></li>
    </ul>





    <!-- Tab panes -->

    <div class="tab-content">

 <form class="form-horizontal" enctype="multipart/form-data" method="post" style="display: none;">

<?php    

$functions->dataTableDateRange(true,$div_view = 2); 

?>



<button type="submit" name="submit" value="GENERATE" class="btn btn-sm btn-primary st_generate_btn">  <?php echo $_e['Stats']; ?>  </button>









</form>

        <div class="tab-pane fade in active container-fluid" id="home">

            <h2  class="tab_heading"><?php echo _uc($_e['Stats']); ?></h2>
            <a href="-product_stats?page=csv&amp;export" class="btn btn-primary btn-lg">Export Data</a>
            <?php $proStatics->statsInventoryView();  ?>

        </div>
    </div>



<?php return ob_get_clean(); ?>