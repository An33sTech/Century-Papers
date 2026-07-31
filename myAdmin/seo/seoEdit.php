<?php
ob_start();

require_once "classes/seo.class.php";
global $dbF;

$seo = new seo();

$seo->seoEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage SEO']); ?></h2>

<?php $seo->seoEdit(); ?>
<?php

global $functions;
$functions->includeAdminFile("menu/classes/menu.class.php");
$menuC = new WebMenu();

$menuC->menuWidgetLinksWOLang();
?>

<script>
    $(function() {
        dateJqueryUi();
    });
</script>
<?php return ob_get_clean(); ?>