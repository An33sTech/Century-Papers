<?php
ob_start();
require_once("classes/giftCard.class.php");
global $dbF;
$giftCardC  =   new giftCard();
$giftCardC->giftCardEditSubmit();
$giftCardC->newGiftCardAdd();
?>
    <h2 class="sub_heading"><?php echo _uc($_e['Manage Gift Card']); ?></h2>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active Gift Card']); ?></a></li>
        <li><a href="#sold" role="tab" data-toggle="tab"><?php echo _uc($_e['Sold Gift Card']); ?></a></li>
        <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Gift Card']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active Gift Card']); ?></h2>
            <?php $giftCardC->giftCardView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="sold">
            <h2  class="tab_heading"><?php echo _uc($_e['Sold Gift Card']); ?></h2>
            <?php $giftCardC->giftCardSaleView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php $giftCardC->giftCardDraft();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Add New Gift Card']); ?></h2>
            <?php $giftCardC->newGiftCard();  ?>
        </div>
    </div>

    <script>
        $(function(){
            tableHoverClasses();
            dateJqueryUi();
        });

        function deleteGiftCard(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'giftcard/giftCard_ajax.php?page=deleteGiftCard',
                    data: { id:id }
                }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo _js(_uc($_e['Delete Fail Please Try Again.'])); ?>');
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

        function saleGiftCard(ths){
            btn=$(ths);
            if(secure_delete('<?php echo _uc($_e['Are You Sure You Want TO Update?']); ?>')){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                val =btn.attr('data-val');
                $.ajax({
                    type: 'POST',
                    url: 'giftcard/giftCard_ajax.php?page=saleGiftCard',
                    data: { id:id,val:val }
                }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo _uc($_e['Update Fail Please Try Again.']); ?>');
                    }
                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
            }
        }

    </script>
<?php return ob_get_clean(); ?>