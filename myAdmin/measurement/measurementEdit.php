<?php
ob_start();

//var_dump($_POST);
global $dbF,$functions,$_e;
$functions->includeOnceCustom(ADMIN_FOLDER."/measurement/classes/measurement.class.php");

$measurement  =   new measurement();

if(isset($_GET['view']) && $_GET['view'] == 'fields') {
    $measurement->measurementFieldsSubmit();
}else{
    $measurement->measurementEditSubmit();
}

    ?>

<h2 class="sub_heading"><?php echo _uc($_e['Manage Measurement']); ?></h2>
<?php
if(isset($_GET['view']) && $_GET['view'] == 'fields') {
    $measurement->measurementFields();
}else {
    $measurement->measurementEdit();
}
?>

<script>
    $(function(){
        dateJqueryUi();
    });
</script>


<?php return ob_get_clean(); ?>