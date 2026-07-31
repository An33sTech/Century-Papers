<?php
ob_start();

require_once("classes/brands.class.php");
global $dbF;

$brands  =   new brands();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$brands->brandsEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Brands']); ?></h2>
<?php $brands->brandsEdit(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>