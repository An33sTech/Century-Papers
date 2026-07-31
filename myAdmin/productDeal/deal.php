<?php
ob_start();

require_once("classes/deal.class.php");
global $dbF;

$deal  =   new deal();

$deal->dealEditSubmit();
$deal->newDealAdd();
?>
    <style>
        .form-group:has(.multiproduct) {
            display: none;
        }
    </style>
    <h2 class="sub_heading"><?php echo _uc($_e['Manage Deal']); ?></h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active Deal']); ?></a></li>
        <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
        <li><a href="#sort" role="tab" data-toggle="tab"><?php echo _uc($_e['Sort']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active Deal']); ?></h2>
            <?php $deal->dealView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php $deal->dealDraft();  ?>
        </div>
        <div class="tab-pane fade in container-fluid" id="sort">
            <h2  class="tab_heading"><?php echo _uc($_e['Sort']); ?></h2>
            <?php $deal->dealSort();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Deal']); ?></h2>
            <?php $deal->dealNew();  ?>
        </div>
    <!--</div>-->
    
    <script type="text/javascript">
        $(function(){
            tableHoverClasses();
            dateJqueryUi();
        });

        function deleteDeal(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'productDeal/deal_ajax.php?page=deleteDeal',
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
                        url: 'productDeal/deal_ajax.php?page=dealSort',
                        type: "post",
                        data: serial,
                        error: function(){
                            jAlertifyAlert("<?php echo ($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                        }
                    });
                }
            });
        });
        
        $('.show_product').on('switch-change', function (e, data) {
     
        if(data.value){
            $($('.multiproduct').closest(".form-group")).css("display", "block")
        }else{
           
             $($('.multiproduct').closest(".form-group")).css("display", "none")
        }
    });


    </script>

    <script>
    $('input[type=checkbox]').click(function () {
        console.log('checkbox clicked');
        $(this).parent()
            .find('li input[type=checkbox]')
            .prop('checked', $(this)
            .is(':checked'));
        var sibs = false;

        $(this).closest('ul')
            .children('li').each(function () {
                if($('input[type=checkbox]', this).is(':checked')) 
                    sibs=true;
        })
        $(this).parents('ul').prev().prop('checked', sibs);

        $("input[type='checkbox'] ~ ul input[type='checkbox']").change(function() {
            $(this).closest("li:has(li)").children("input[type='checkbox']").prop('checked', $(this).closest('ul').find("input[type='checkbox']").is(':checked'));
        });
    });  
</script>



<?php return ob_get_clean(); ?>