<?php
ob_start();

require_once("classes/recommends.class.php");
global $dbF;

$recommendss  =   new recommendss();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$recommendss->recommendssEditSubmit();
$recommendss->newRecommendssAdd();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Recommends']); ?></h2>

    <!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
<li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active Recommends']); ?></a></li>
<li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
<!--<li><a href="#sort" role="tab" data-toggle="tab"><?php #echo _uc($_e['Sort Recommends']); ?></a></li>-->
<li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Recommends']); ?></a></li>
</ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active Recommends']); ?></h2>
            <?php $recommendss->recommendssView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php $recommendss->recommendssDraft();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="sort">
            <h2  class="tab_heading"><?php echo _uc($_e['Sort Recommends']); ?></h2>
            <?php $recommendss->recommendssSort();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Recommends']); ?></h2>
            <?php $recommendss->recommendssNew();  ?>
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

    function deleteRecommends(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'recommends/recommends_ajax.php?page=deleteRecommends',
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
                    url: 'recommends/recommends_ajax.php?page=recommendssSort',
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
            url: "../ajax_call.php?page=searched_products&recommends",
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