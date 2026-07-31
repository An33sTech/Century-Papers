<?php
ob_start();

require_once("classes/blog.class.php");
global $dbF;

$blog  =   new blog();

$blog->blogEditSubmit();
$blog->newBlogAdd();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Blog']); ?></h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active Blog']); ?></a></li>
        <li><a href="#pending" role="tab" data-toggle="tab"><?php echo _uc($_e['Pending Blog']); ?></a></li>
        <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft Blog']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Blog']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active Blog']); ?></h2>
            <?php $blog->blogView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="pending">
            <h2  class="tab_heading"><?php echo _uc($_e['Pending Blog']); ?></h2>
            <?php $blog->blogPending();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft Blog']); ?></h2>
            <?php $blog->blogDraft();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Blog']); ?></h2>
            <?php $blog->blogNew();  ?>
        </div>
    </div>

<script>
      $(function(){
        tableHoverClasses();
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

    function deleteBlog(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'blog/blog_ajax.php?page=deleteBlog',
                data: { id:id }
            }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
                    }
                    else{
                        btn.append(data);
                    }
                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
        }
    }


</script>
<?php return ob_get_clean(); ?>