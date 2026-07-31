<?php
ob_start();

//var_dump($_POST);
global $dbF,$functions,$_e;
$functions->includeOnceCustom(ADMIN_FOLDER."/productDeal/classes/deal.class.php");

$deal  =   new deal();
$deal->dealEditSubmit();


    ?>
<style>
    .form-group:has(.none) {
        display: none;
    }
</style>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Deal']); ?></h2>
<?php
if(isset($_GET['view']) && $_GET['view'] == 'fields') {
    $deal->dealFields();
}else {
    $deal->dealEdit();
}
?>

<script>
    $(function(){
        dateJqueryUi();
    });
</script>


<?php return ob_get_clean(); ?>