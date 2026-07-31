<?php
ob_start();

require_once("classes/industries.class.php");
global $dbF;

$service =   new industries();

$service->serviceEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Industries']); ?></h2>

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