<?php
ob_start();

require_once("classes/emailin_working.class.php");
global $dbF;

$emailin_working  =   new emailin_working();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$emailin_working->emailin_workingEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Stock Information']); ?></h2>
<?php $emailin_working->emailin_workingEdit(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>