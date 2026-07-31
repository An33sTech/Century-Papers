<?php
ob_start();
$product = new product();

function underMenu3Option($id, $defaultLang)
{

    global $dbF;
    global $functions;
    $sql = "SELECT * FROM categories WHERE  under = '$id' ORDER BY sort";
    $data = $dbF->getRows($sql);
    $temp = '';
    if ($dbF->rowCount) {
        foreach ($data as $val) {
            $heading = translateFromSerialize($val['name']);

            $id = @$_GET['recommendsId'];

            $sql = "SELECT cat_id FROM sp_recommends WHERE  id = '$id' ORDER BY sort";
            $datas = $dbF->getRow($sql);

            $temp .= '<option value="' . $val['id'] . '"';



            if (@$val['id'] == @$datas['cat_id']) {
                $temp .= "selected";
            }

            $temp .= '> -- -- ' . $heading . '</option>';
        }
        return $temp;
    } else {
        return false;
    }
}

function underMenu2Option($id, $defaultLang)
{

    global $dbF;
    global $functions;
    $sql = "SELECT * FROM categories WHERE  under = '$id' ORDER BY sort";
    $data = $dbF->getRows($sql);
    $temp = '';
    if ($dbF->rowCount) {
        foreach ($data as $val) {
            $id = @$_GET['recommendsId'];

            $sql = "SELECT cat_id FROM sp_recommends WHERE  id = '$id' ORDER BY sort";
            $datas = $dbF->getRow($sql);

            $heading = translateFromSerialize($val['name']);
            $temp .= '<option value="' . $val['id'] . '"';

            if (@$val['id'] == @$datas['cat_id']) {
                $temp .= "selected";
            }

            $temp .= '> -- ' . $heading . '</option>';
            $menu3 = underMenu3Option($val['id'], $defaultLang);



            if ($menu3 != false) {
                $temp .= $menu3;
            } else {
                continue;
            }
        }
        return $temp;
    } else {
        return false;
    }
}

function underMenuOption()
{
    global $dbF;
    global $functions;

    $sql = "SELECT * FROM categories WHERE  under = '0' ORDER BY sort";
    $data = $dbF->getRows($sql);
    $opt = '';
    $defaultLang = $functions->AdminDefaultLanguage();

    foreach ($data as $val) {
        $menu2 = underMenu2Option($val['id'], $defaultLang);
        $heading = translateFromSerialize($val['name']);
        $opt .= '<option value="' . $val['id'] . '" disabled>' . htmlentities($heading) . '</option>';
        if ($menu2 != false) {
            $opt .= $menu2;
        } else {
            continue;
        }
    }
    return $opt;
}
?>
<ul class="nav nav-tabs tabs_arrow" role="tablist">

    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Mass Update']); ?></a></li>

</ul>

<div class="tab-content">

    <div class="tab-pane fade in active container-fluid" id="home">




        <h2 class="tab_heading"><?php echo _uc($_e['Mass Update']); ?></h2>


        <div class="form-horizontal">
            <form action="" method="post" id="massUpdateForm">
                <input type="hidden" name="mode" id="mode" />
                <?php
                $option = underMenuOption();
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">' . _uc('Select Categories') . '</label>
                        <div class="col-sm-10  col-md-9">
                        <select name="underMenu" id="underMenu" class="underMenu form-control">
                        <option value="0">' . _uc('Categories Menu') . '</option>
                        ' . $option . '
                        </select>
                        </div>
                        </div>'; 
                ?>

                <div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label"></label>
                    <div class="col-sm-10  col-md-9">
                        <input type="button" class="btn btn-primary btn-md" id="submit_form" value="Submit" />
                    </div>
                </div>
            </form>
        </div>
        <div id="product_table">

        </div>
    </div>
</div>
<script>
    function updateMassSupplier(formElement) {
        var formData = new FormData(formElement);
    
        $.ajax({
            url: "product/products_listing_ajax.php?page=updateMassSupplier",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if(response == 1){
                    jAlertifyAlert("Data updated successfully");
                }else{
                    jAlertifyAlert("An error occurred while submitting the form.");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    
    $('#submit_form').on('click', function() {
        form = $('#massUpdateForm').serialize();
        var data,
            tableName = '.dTable_dynamic',
            columns,
            str;

        $('#preloader').css('display', 'block');

        $.ajax({
            url: 'product/products_listing_ajax.php?page=getMassDataNew',
            type: 'post',
            data: form
        }).done(function(responseText) {
            $('#preloader').css('display', 'none');
            $('#product_table').html(responseText);
            if ($('.example-getting-started_without_img3').length) {
                $('.example-getting-started_without_img3').multiselect({
                    includeSelectAllOption: true,
                    enableHTML: true,
                    filterPlaceholder: 'Search for something...',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true
                });
            }
            var responseHTML = $(responseText);
            var dropboxDetails = responseHTML.find('.dropboxDetail');
            var dropboxDetailCount = responseHTML.find('.dropboxDetail').length;
            
            for(i = 0; i < dropboxDetailCount; i++){
                newIdDetail = $(".dropboxDetailNew"+i).closest('.col-md-12').find('#AjaxFileNewIdDetail').val();
                console.log(newIdDetail)
                commonFileDropSettings($(".dropboxDetailNew"+i), createDetailImage, showMessage2, newIdDetail, "productDetail");
            }
            
            $(".pDetailImageAltUpdate").click(function () {
                btn = $(this);
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();
                id = btn.attr('data-id');
                btn.children('span').text('Wait...');
                
                let swedishHeading = btn.closest('.progressHolder').find('.swedish-heading').val();
                let norwegianHeading = btn.closest('.progressHolder').find('.norwegian-heading').val();
                let danishHeading = btn.closest('.progressHolder').find('.danish-heading').val();
                let finnishHeading = btn.closest('.progressHolder').find('.finnish-heading').val();
                let englishHeading = btn.closest('.progressHolder').find('.english-heading').val();
                let germanHeading = btn.closest('.progressHolder').find('.german-heading').val();
                let frenchHeading = btn.closest('.progressHolder').find('.french-heading').val();
    
                let swedishDesc = btn.closest('.progressHolder').find('.swedish-desc').val();
                let norwegianDesc = btn.closest('.progressHolder').find('.norwegian-desc').val();
                let danishDesc = btn.closest('.progressHolder').find('.danish-desc').val();
                let finnishDesc = btn.closest('.progressHolder').find('.finnish-desc').val();
                let englishDesc = btn.closest('.progressHolder').find('.english-desc').val();
                let germanDesc = btn.closest('.progressHolder').find('.german-desc').val();
                let frenchDesc = btn.closest('.progressHolder').find('.french-desc').val();
    
    
                let headings = {
                    'Swedish': swedishHeading,
                    'Norwegian': norwegianHeading,
                    'Danish': danishHeading,
                    'Finnish': finnishHeading,
                    'English': englishHeading,
                    'German': germanHeading,
                    'French': frenchHeading,
                }
    
                let description = {
                    'Swedish': swedishDesc,
                    'Norwegian': norwegianDesc,
                    'Danish': danishDesc,
                    'Finnish': finnishDesc,
                    'English': englishDesc,
                    'German': germanDesc,
                    'French': frenchDesc,
                }
    
    
                $.ajax({
                    type: 'POST',
                    url: 'product_management/product_ajax.php?page=pDetailImageAltUpdate',
                    data: { imageId: id, altT: headings, desc: description }
                }).done(function (data) {
                    ift = true;
                    if (data == '1') {
                        btn.children('span').text('<?php echo _js($_e['Done']); ?>');
                    }
                    else {
                        btn.children('span').text('<?php echo _js($_e['Fail']); ?>');
                    }
                    btn.removeClass('disabled');
                    btn.children('.trash').show();
                    btn.children('.waiting').hide();
                });
            });
        })
    });
    
    

</script>
<?php
return ob_get_clean();
?>