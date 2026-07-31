<?php
ob_start();

require_once("classes/giftCard.class.php");
global $dbF;

$seo  =   new giftCard();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$seo->giftCardEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Gift Card']); ?></h2>

<?php $seo->giftCardEdit(); ?>


<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>