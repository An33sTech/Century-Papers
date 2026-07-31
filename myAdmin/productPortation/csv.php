<?php



if (isset($_GET['exportPrice'])) {

    include_once(__DIR__."/exportPrice_csv.php");

}

if (isset($_GET['exportDiscount'])) {

    include_once(__DIR__."/exportDiscount_csv.php");

}



ob_start();

global $_e;

global $adminPanelLanguage;

$_w = array();

$_w['Export Product Price'] = '';

$_w['Import Product Price'] = '';

$_w['Export Product Discount'] = '';

$_w['Import Product Discount'] = '';

$_w['Export Price'] = '';

$_w['Export Discount']       = '';

$_w['Submit']       = '';

$_w['Note: After Export all stock inventory, Only update data from 2 columns (Discount(currency) and DiscountFormat)']       = '';

$_w['Product Discount Exported File']       = '';

$_w['Product Price Exported File'] = '';

$_w['Note: After Export all product prices, Only update data from 5 columns (Manufacturer Name,Sweden(SEK),Norwegian(NOK),Denmark(DK),Finland(FI))'] = '';

$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Product Portation');

?>

    <h4 class="sub_heading"><?php echo _uc($_e['Import/Export']); ?></h4>

    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist">

        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Export Product Price']); ?></a></li>

        <li><a href="#importPrice" role="tab" data-toggle="tab"><?php echo _uc($_e['Import Product Price']); ?></a></li>

        <li><a href="#exportDiscount" role="tab" data-toggle="tab"><?php echo _uc($_e['Export Product Discount']); ?></a></li>

        <li><a href="#importDiscount" role="tab" data-toggle="tab"><?php echo _uc($_e['Import Product Discount']); ?></a></li>

    </ul>



    <!-- Tab panes -->

    <div class="tab-content">

        <div class="tab-pane fade in active container-fluid" id="home">

            <h2 class="tab_heading"><?php echo _uc($_e['Export Product Price']); ?></h2>

            <div class=""><?php echo _uc($_e['Note: After Export all product prices, Only update data from 5 columns (Manufacturer Name,Sweden(SEK),Norwegian(NOK),Denmark(DK),Finland(FI))']); ?></div>

            <a href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&exportPrice" class="btn btn-primary btn-lg"><?php echo _uc($_e['Export Price']); ?></a>

        </div>



        <div class="tab-pane fade container-fluid" id="importPrice">

            <h2 class="tab_heading"><?php echo _uc($_e['Import Product Price']); ?></h2>

            <?php

                ob_start();

                    include_once( 'importPrice_csv.php' );

                echo $importEmail = ob_get_clean();

            ?>

        </div>



        <div class="tab-pane fade container-fluid" id="exportDiscount">

            <h2 class="tab_heading"><?php echo _uc($_e['Export Product Discount']); ?></h2>

            <div class=""><?php echo _uc($_e['Note: After Export all stock inventory, Only update data from 2 columns (Discount(currency) and DiscountFormat)']); ?></div>

            <a href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&exportDiscount" class="btn btn-primary btn-lg"><?php echo _uc($_e['Export Discount']); ?></a>

        </div>



        <div class="tab-pane fade container-fluid" id="importDiscount">

            <h2 class="tab_heading"><?php echo _uc($_e['Import Product Discount']); ?></h2>

            <?php

                ob_start();

                    include_once( 'importDiscount_csv.php' );

                echo $importEmail = ob_get_clean();

            ?>

        </div>

    </div>



<?php return ob_get_clean(); ?>