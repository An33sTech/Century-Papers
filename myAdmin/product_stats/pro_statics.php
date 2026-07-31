<?php

ob_start();



require_once("classes/proStatics.class.php");

global $dbF;



$proStatics  =   new proStatics();




?>

<h2 class="sub_heading"><?php echo _uc($_e['Product Stats']); ?></h2>



    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist">

        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['All Products']); ?></a></li>
        <li><a href="#sweden" role="tab" data-toggle="tab"><?php echo _uc($_e['Sweden']); ?></a></li>
        <li><a href="#norwegian" role="tab" data-toggle="tab"><?php echo _uc($_e['Norwegian']); ?></a></li>
        <li><a href="#finland" role="tab" data-toggle="tab"><?php echo _uc($_e['Finland']); ?></a></li>
        <li><a href="#denmark" role="tab" data-toggle="tab"><?php echo _uc($_e['Denmark']); ?></a></li>
        <li><a href="#switzerland" role="tab" data-toggle="tab"><?php echo _uc($_e['Switzerland']); ?></a></li>
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

            <?php $proStatics->statsView();  ?>

        </div>

        <div class="tab-pane fade container-fluid" id="sweden">
            <h2  class="tab_heading"><?php echo _uc($_e['Stats']); ?></h2>
            <?php $proStatics->statsViewSEK();  ?>
        </div>
        <div class="tab-pane fade container-fluid" id="norwegian">
            <h2  class="tab_heading"><?php echo _uc($_e['Stats']); ?></h2>
            <?php $proStatics->statsViewNOK();  ?>
        </div>
        <div class="tab-pane fade container-fluid" id="finland">
            <h2  class="tab_heading"><?php echo _uc($_e['Stats']); ?></h2>
            <?php $proStatics->statsViewFI();  ?>
        </div>
        <div class="tab-pane fade container-fluid" id="denmark">
            <h2  class="tab_heading"><?php echo _uc($_e['Stats']); ?></h2>
            <?php $proStatics->statsViewDK();  ?>
        </div>
        <div class="tab-pane fade container-fluid" id="switzerland">
            <h2  class="tab_heading"><?php echo _uc($_e['Stats']); ?></h2>
            <?php $proStatics->statsViewCHF();  ?>
        </div>
    </div>



<?php return ob_get_clean(); ?>