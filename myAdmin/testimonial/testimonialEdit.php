<?php
ob_start();

require_once("classes/testimonial.class.php");
global $dbF;

$testimonial  =   new testimonial();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$testimonial->testimonialEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Industries']); ?></h2>
<?php $testimonial->testimonialEdit(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>