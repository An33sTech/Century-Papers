<?php
ob_start();

require_once("classes/menu.class.php");
global $dbF;

$menuC  =   new webMenu();

$menuC->footerMenuEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Update Footer Menu']); ?></h2>

 <?php $menuC->FooterMenuEdit(); ?>
<?php $menuC->menuWidgetLinks(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>