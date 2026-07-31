<?php
ob_start();

require_once("classes/logs.class.php");
global $dbF;

$logs=new logs();
//$dbF->prnt($_POST);
//exit;
$logs->returnAdd();

echo '<h4 class="sub_heading">'. _uc($_e['Return Products']) .'</h4>';

?>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Return Archive']); ?></a></li>
        <li><a href="#tab_register" role="tab" data-toggle="tab"><?php echo _uc($_e['Return Registration']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Return Archive']); ?></h2>
            <?php $logs->returnView(); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="tab_register">
            <h2  class="tab_heading"><?php echo _uc($_e['Return Registration']); ?></h2>
            <?php $logs->returnRegister(); ?>
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
                        $(productHiddenClass).attr("data-val",ui.item.label);
                        scale(ui.item.scale);
                        color(ui.item.color);
                    }
                }).on('focus : click', function(event) {
                        $(this).autocomplete("search", "");
                });














            });

    </script>
    <script>
        $(document).ready(function(){
            tableHoverClasses();
            dateJqueryUi();
            minMaxDate();
            dTableRangeSearch();






            
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="returnModal" tabindex="1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="'returnModalLabel"><?php echo _uc($_e['Return Archive']); ?></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _uc($_e['Close']); ?></button>
                </div>
            </div>
        </div>
    </div>

    <script src="logs/js/logs.php"></script>
<?php return ob_get_clean(); ?>