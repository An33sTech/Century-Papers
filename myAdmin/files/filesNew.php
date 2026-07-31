<?php
ob_start();

require_once "classes/files.class.php";
global $dbF;

$files = new files();
$files->newFilesAdd();
?>
<h2 class="sub_heading"><?php echo ('Add New Reports'); ?></h2>
<?php $files->filesNew(); ?>

<script>
	$(function() {
		dateJqueryUi();
	});
</script>
<?php return ob_get_clean(); ?>