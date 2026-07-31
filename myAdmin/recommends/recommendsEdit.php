<?php
ob_start();

require_once("classes/recommends.class.php");
global $dbF;

$recommendss  =   new recommendss();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$recommendss->recommendssEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Recommends']); ?></h2>
<?php $recommendss->recommendssEdit(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

    function showLoadingMask (target, boolean) {
        // body...
        if (boolean === true) {
            $(target).addClass('ui-autocomplete-loading');
        } else{
            $(target).removeClass('ui-autocomplete-loading');
        };

    }

    $("input.typeahead").typeahead({
        onSelect: function(item) {
            $('#product_id').val(item.value);
            console.log(item.value);
        },
        ajax: {
            url: "../ajax_call.php?page=searched_products&recommends",
            dataType: 'JSON',
            timeout: 500,
            triggerLength: 1,
            method: "post",
            displayField: "title",
            loadingClass: "loading-circle",
            preDispatch: function (query) {
                showLoadingMask('#product_name',true);
                return {
                    search: query
                }
            },
            preProcess: function (data) {
                showLoadingMask('#product_name',false);
                return data;
                // console.log(data);
                // if (data.success === false) {
                    // Hide the list, there was some error
                    // return false;
                // }
                // We good!
                // return data.mylist;
            }
        }
    });    

</script>
<?php return ob_get_clean(); ?>