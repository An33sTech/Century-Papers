<?php
ob_start();

require_once("classes/logs.class.php");
global $dbF;

$logs=new logs();
$logs->productReturnEditSubmit();

if(isset($_GET['editId']) && $_GET['editId'] != ''){
    echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['Product Defect']) .'</h4>';
    echo '<a href="-'.$functions->getLinkFolder().'?page=productDefect" class="btn btn-primary">'. _u($_e['GO BACK']) .'</a><br><br>';
    $logs->productReturnView('defect');
}else{ ?>

<h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Product Defect']); ?></h4>
<div class="container-fluid" >
    <?php $logs->productReturnOrDefectTable('defect'); ?>
</div>
<?php } ?>

<script>
    $(document).ready(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function returnFormDel(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'logs/logs_ajax.php?page=returnFormDel&id='+id,
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
                    jAlertifyAlert(data);
                }

                if(ift){
                    btn.removeClass('disabled');
                    btn.children('.trash').show();
                    btn.children('.waiting').hide();
                }
            });
        }
    };
</script>
<?php return ob_get_clean(); ?>