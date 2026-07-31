<?php
ob_start();
require_once("classes/filter.class.php");
$filter = new filter();
$filter->filterAdd();
$filter->filterEditSubmit();
?>
<h4 class="sub_heading"><?php echo _uc($_e['Filters']); ?></h4>
<ul class="nav nav-tabs tabs_arrow" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active Filters']); ?></a></li>
    <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
    <li><a href="#new" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Filter']); ?></a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in active container-fluid" id="home">
        <h2 class="tab_heading"><?php echo _uc($_e['Active Filters']); ?></h2>
        <?php $filter->filterView(); ?>
    </div>
    <div class="tab-pane fade container-fluid" id="draft">
        <h2 class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
        <?php $filter->filterDraft(); ?>
    </div>
    <div class="tab-pane fade container-fluid" id="new">
        <h2 class="tab_heading"><?php echo _uc($_e['Add New Filter']); ?></h2>
        <?php $filter->filterNew(); ?>
    </div>
</div>
<script>
    function deleteFilter(ths) {
        btn = $(ths);
        if (secure_delete()) {
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id = btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'filter/filter_ajax.php?page=deleteFilter',
                data: {
                    id: id
                }
            }).done(function(data) {
                ift = true;
                if (data == '1') {
                    ift = false;
                    btn.closest('tr').hide(1000, function() {
                        $(this).remove()
                    });
                } else if (data == '0') {
                    jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
                } else {
                    btn.append(data);
                }
                if (ift) {
                    btn.removeClass('disabled');
                    btn.children('.trash').show();
                    btn.children('.waiting').hide();
                }

            });
        }
    }
</script>
<?php return ob_get_clean(); ?>