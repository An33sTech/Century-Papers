<?php
ob_start();

require_once("classes/bestseller.class.php");
global $dbF;

$bestsellers  =   new bestsellers();

$bestsellers->bestsellersEditSubmit();
$bestsellers->newBestsellersAdd();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Bestsellers']); ?></h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active Bestsellers']); ?></a></li>
        <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
        <li><a href="#sort" role="tab" data-toggle="tab"><?php echo _uc($_e['Sort Bestsellers']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Bestseller']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active Bestsellers']); ?></h2>
            <?php $bestsellers->bestsellersView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php $bestsellers->bestsellersDraft();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="sort">
            <h2  class="tab_heading"><?php echo _uc($_e['Sort Bestsellers']); ?></h2>
            <?php $bestsellers->bestsellersSort();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Bestseller']); ?></h2>
            <?php $bestsellers->bestsellersNew();  ?>
        </div>
    </div>

<style>
    .ui-autocomplete-loading {
        background: white url("../images/ui-anim_basic_16x16.gif") right center no-repeat;
    }
</style>

<script>
    $(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function deleteBestseller(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'bestseller/bestseller_ajax.php?page=deleteBestseller',
                data: { id:id }
            }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
                    }
                    else{
                        btn.append(data);
                    }
                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
        }
    }

    $(document).ready(function() {

        $( ".sortDiv .activeSort" ).sortable({
            handle: '.albumSortTop',
            containment: "parent",
            update : function () {
                serial = $(this).sortable('serialize');
                $.ajax({
                    url: 'bestseller/bestseller_ajax.php?page=bestsellersSort',
                    type: "post",
                    data: serial,
                    error: function(){
                        jAlertifyAlert("<?php echo ($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                    }
                });
            }
        });
    });

    function showLoadingMask (target, boolean) {
        // body...
        if (boolean === true) {
            $(target).addClass('ui-autocomplete-loading');
        } else{
            $(target).removeClass('ui-autocomplete-loading');
        };

    }

    $("input.typeahead").typeahead({
        onSelect: function(item) {
            $('#product_id').val(item.value);
            console.log(item.value);
        },
        ajax: {
            url: "../ajax_call.php?page=searched_products&bestseller",
            dataType: 'JSON',
            timeout: 500,
            triggerLength: 1,
            method: "post",
            displayField: "title",
            loadingClass: "loading-circle",
            preDispatch: function (query) {
                showLoadingMask('#product_name',true);
                return {
                    search: query
                }
            },
            preProcess: function (data) {
                showLoadingMask('#product_name',false);
                return data;
                // console.log(data);
                // if (data.success === false) {
                    // Hide the list, there was some error
                    // return false;
                // }
                // We good!
                // return data.mylist;
            }
        }
    });

</script>
<?php return ob_get_clean(); ?>