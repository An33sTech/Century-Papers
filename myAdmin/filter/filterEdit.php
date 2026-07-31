<?php
ob_start();
require_once("classes/filter.class.php");
$filter = new filter();

$filter->filterEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Filters']); ?></h2>
<a href="-filter?page=filter" class="btn btn-primary">Go Back</a>
<?php $filter->filterEdit(); ?>

<script>
    $(function() {
        dateJqueryUi();
    });
</script>
<?php return ob_get_clean(); ?>