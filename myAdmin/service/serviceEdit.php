<?php
ob_start();

require_once("classes/service.class.php");
global $dbF;

$service =   new service();

$service->serviceEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Service']); ?></h2>

<?php $service->serviceEdit(); ?>


<script>
    $(function(){
        dateJqueryUi();
    });
    $('#category').click(function(){
        val = $(this).val();
        if(val=='other'){
            $('#categoryOther').slideDown(500).attr('required','true');
        }else{
            $('#categoryOther').slideUp(500).removeAttr('required');
        }
    });

</script>
<?php return ob_get_clean(); ?>