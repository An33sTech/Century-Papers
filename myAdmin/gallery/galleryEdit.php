<?php
ob_start();

require_once("classes/gallery.class.php");
global $dbF;

$gallery  =   new gallery();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$gallery->submitEditAlbum();
?>

<a href="-<?php echo $functions->getLinkFolder();?>?page=gallery" class="btn btn-primary"><?php echo _uc($_e['Back To Albums']); ?></a>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Album']); ?></h2>

<?php $gallery->albumEdit(); ?>


    <!-- Modal use in modal div-->
    <div class="modal fade bs-example-modal-lg" id="productImgDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo _uc($_e['Image Preview']); ?></h4>
                </div>
                <div class="modal-body" style="text-align: center">
                    <img src="" align="center"  />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _uc($_e['Close']); ?></button>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {

        $( "#dropbox" ).sortable({
            handle: '.imageHolder',
            containment: "parent",
            update : function () {
                serial = $(this).sortable('serialize');
                $.ajax({
                    url: 'gallery/gallery_ajax.php?page=sortAlbumImage',
                    type: "post",
                    data: serial,
                    error: function(){
                        jAlertifyAlert("<?php echo _uc($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                    }
                });
            }
        });
    });

    ///////////////////////////////////////
    $(".imageHolder").click(function(){
        img=$(this).find("img").attr('src');
        $('#productImgDialog').modal('show');
        $("#productImgDialog .modal-body").find("img").attr("src",img).hide().show(600);
    });

    ////////////////////////////////////////////
    $(".productEditImageDel").click(function(){
        if(secure_delete()){
            id=$(this).attr("data-id");
            parnt=$(this).closest(".preview");
            $.ajax({
                type: "POST",
                url: 'gallery/gallery_ajax.php?page=albumEditImageDel',
                data: { imageId:id }
            }).done(function(data) {
                    if(data=='1'){
                        parnt.hide(500);
                    }else if(data=='0'){
                        jAlertifyAlert("<?php echo _uc($_e['Image Not Delete Please Try Again']); ?>");
                        return false;
                    }
                });
        }
    });
    /////////////////////////////////////////////////


    ////////////////////////////////////////////
    $(".albumAltUpdate").click(function(){
        btn=$(this);
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            alt= $('#alt-'+id).val();
            btn.children('span').text('Wait...');
            $.ajax({
                type: 'POST',
                url: 'gallery/gallery_ajax.php?page=albumAltUpdate',
                data: { imageId:id,altT:alt }
            }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        btn.children('span').text('Done');
                    }
                    else{
                        btn.children('span').text('Fail');
                    }
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();

                });
    });
    /////////////////////////////////////////////////


</script>
<style>
    #dropbox .preview{
        height: 255px !important;
        padding: 4px;
        background: #eee;
    }
    #dropbox .progressHolder.album {
        height: 80px !important;
        padding: 5px;
    }

</style>
<?php return ob_get_clean(); ?>