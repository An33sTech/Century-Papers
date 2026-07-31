<?php
ob_start();

require_once("classes/faq.class.php");
global $dbF;

$documents = new faq();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$documents->faqEditSubmit();
$documents->faqAdd();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage FAQ']); ?></h2>

<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active FAQ']); ?></a></li>
    <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft FAQ']); ?></a></li>
    <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New FAQ']); ?></a></li>
</ul>


<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane fade in active container-fluid" id="home">
        <h2 class="tab_heading"><?php echo _uc($_e['Active FAQ']); ?></h2>
        <?php $documents->faqView(); ?>
    </div>

    <div class="tab-pane fade in container-fluid" id="draft">
        <h2 class="tab_heading"><?php echo _uc($_e['Draft FAQ']); ?></h2>
        <?php $documents->faqDraft(); ?>
    </div>

    <div class="tab-pane fade in container-fluid" id="newPage">
        <h2 class="tab_heading"><?php echo _uc($_e['Add New FAQ']); ?></h2>
        <?php $documents->faqNew(); ?>
    </div>
</div>

<script>
    $(function() {
        tableHoverClasses();
        dateJqueryUi();
    });

    $('select[name="category"]').change(function(){
        var value = $(this).val();
        if (value == "Service") {
            $(".categorySelect").fadeIn();
            $(".productSelect").fadeOut();
        } else if (value == "Product") {
            $(".productSelect").fadeIn();
            $(".categorySelect").fadeOut();
        } else {
            $(".categorySelect").fadeOut();
            $(".productSelect").fadeOut();
        }
    });

    function deletefaq(ths) {
        btn = $(ths);
        if (secure_delete()) {
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id = btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'faq/faq_ajax.php?page=deletefaq',
                data: {
                    id: id
                }
            }).done(function(data) {
                ift = true;
                if (data == '1') {
                    ift = false;
                    btn.closest('tr').hide(1000, function() {
                        $(this).remove()
                    });
                } else if (data == '0') {
                    jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
                } else {
                    btn.append(data);
                }
                if (ift) {
                    btn.removeClass('disabled');
                    btn.children('.trash').show();
                    btn.children('.waiting').hide();
                }

            });
        }
    }

    $('#make-switch0').on('change', function() {
        var chk = $('.checkboxHidden').val();
        if (chk == '1') {
            $('#users').show();
            $('#users select').attr("name", "assignto");
        } else {
            $('#users select').removeAttr("name");
            $('#users').hide();
        }
    });
</script>
<?php return ob_get_clean(); ?>