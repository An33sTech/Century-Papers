<?php
ob_start();

require_once("classes/gallery.class.php");
global $dbF;

$gallery  =   new gallery();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$gallery->albumAddSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Album']); ?></h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Albums']); ?></a></li>
        <li class=""><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Albums']); ?></h2>
            <?php $gallery->activeAlbums();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php $gallery->draftAlbums();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Album']); ?></h2>
            <?php $gallery->albumNewForm();  ?>
        </div>
    </div>


<script>
    $(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function deleteAlbum(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'gallery/gallery_ajax.php?page=deleteAlbum',
                data: { id:id }
            }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        $('#album_'+id).hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo _fu($_e['Delete Fail Please Try Again.']); ?>');
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

    $(document).ready(function() {
            $( ".activeAlbums" ).sortable({
                handle: '.albumSortTop',
                containment: "parent",
                update : function () {
                    serial = $(this).sortable('serialize');
                    $.ajax({
                        url: 'gallery/gallery_ajax.php?page=activeAlbums',
                        type: "post",
                        data: serial,
                        error: function(){
                            jAlertifyAlert("<?php echo _fu($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                        }
                    });
                }
            });
       });

</script>

<?php return ob_get_clean(); ?>