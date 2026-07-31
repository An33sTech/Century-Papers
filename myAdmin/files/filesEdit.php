<?php
ob_start();

require_once "classes/files.class.php";
global $dbF;

$files = new files();

$files->filesEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc('Manage Reports'); ?></h2>

<?php $files->filesEdit(); ?>


<script>
	$(function() {
		dateJqueryUi();
	});
</script>
<?php return ob_get_clean(); ?>