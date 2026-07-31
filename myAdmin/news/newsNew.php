<?php
ob_start();

require_once("classes/news.class.php");
global $dbF;

$news  =   new news();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$news->newNewsAdd();
?>
<h2 class="sub_heading"><?php echo ($_e['Add New News/Event']); ?></h2>
        <?php $news->newsNew();  ?>

    <script>
        $(function(){
            dateJqueryUi();
        });
    </script>
<?php return ob_get_clean(); ?>