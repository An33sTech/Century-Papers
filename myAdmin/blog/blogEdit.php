<?php
ob_start();

require_once("classes/blog.class.php");
global $dbF;

$blog =   new blog();

$blog->blogEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Blog']); ?></h2>

<?php $blog->blogEdit(); ?>


<script>
    $(function(){
        dateJqueryUi();
    });
    $('#category').click(function(){
        val = $(this).val();
        if(val=='other'){
            $('#categoryOther').slideDown(500).attr('required','true');
        }else{
            $('#categoryOther').slideUp(500).removeAttr('required');
        }
    });

</script>
<?php return ob_get_clean(); ?>