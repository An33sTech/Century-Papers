<?php
ob_start();

require_once("classes/services.class.php");
global $dbF;

$services  =   new services();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$services->newServicesAdd();
?>
<h2 class="sub_heading"><?php echo ($_e['Add New Services']); ?></h2>
        <?php $services->servicesNew();  ?>

    <script>
        $(function(){
            dateJqueryUi();
        });
    </script>
<?php return ob_get_clean(); ?>