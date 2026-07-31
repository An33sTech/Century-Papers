<?php
ob_start();

require_once("classes/news.class.php");
global $dbF;

$news  =   new news();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$news->newsEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage News']); ?></h2>

            <?php $news->newsEdit(); ?>


<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>