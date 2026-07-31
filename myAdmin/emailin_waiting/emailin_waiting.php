<?php
ob_start();

require_once("classes/emailin_waiting.class.php");
global $dbF;

$emailin_waiting  =   new emailin_waiting();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Waiting Email Information']); ?></h2>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
           
            <?php $emailin_waiting->emailin_waitingView();  ?>
        </div>
    </div>


<style>
    .color_div {
        width: 20px;
        height: 20px;
        display: inline-block;
        vertical-align: bottom;
    }    
</style>

<script>
    $(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function deleteemailin_waiting(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'emailin_waiting/emailin_waiting_ajax.php?page=deleteemailin_waiting',
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

</script>

<?php return ob_get_clean(); ?>