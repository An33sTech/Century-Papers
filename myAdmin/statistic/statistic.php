<?php
ob_start();

require_once("classes/statistic.class.php");
global $dbF;

$object  =   new statistics();

// $dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;

// ob_get_clean();
// ob_start();
// $output = ob_get_clean();
// ob_start();

$hidden_inputs = $date_range_selected = '';
if( isset($_POST['submit']) ){
    $date_range_from     = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : date('Y-m-d');
    $date_range_to       = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : '';

    # if no date to is supplied, then use this month's last date, (this month can be the selected month, or the default current month)
    if ( $date_range_to == '' ) {
        $end  = new DateTime( $date_range_from );
        $date_range_to = $end->format("Y-m-t");
    }

    $date_range_selected = ' (' . $date_range_from . ' - ' . $date_range_to . ')';

### using below inputs when sending ajax request for product color and sizes

$hidden_inputs = ' <input type="hidden" name="" id="date_from" value="' . $date_range_from . '">
                    <input type="hidden" name="" id="date_to"   value="' . $date_range_to . '">';

}


?>

<?php echo $hidden_inputs; ?>

<h3 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Statistics Report']); echo $date_range_selected; ?></h3>
    




<small style="color: red;font-size: large;">Leave Blank For Current Day Sales Statistics....<br><br><br>
                            </small>


                            
<?php
    $object->generate_form();
    $object->create_list_report();
    $object->generate_list_report();
?>


<?php //echo $output; ?>



<style>
    .ui-autocomplete-loading {
        background: white url("../images/ui-anim_basic_16x16.gif") right center no-repeat;
    }

    .input_date {
        padding-left: 45px;
    }

    .input-group-addon-date {
        position: absolute;
        left: 15px;
        top: 0;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: normal;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 4px;
    }


/*    .table-wrapper { 
        overflow-x:scroll;
        overflow-y:visible;
        width:250px;
        margin-left: 120px;
    }


    td, th {
        padding: 5px 20px;
        width: 100px;
    }

    th:first-child {
        position: fixed;
        left: 5px
    }*/



/*        body {
    position: relative;
}*/


/* START  */


.table-wrapper { 
    overflow-x:scroll;
    overflow-y:visible;
    min-width: 70%;
    max-width: 90%;
    margin-left: 120px;
}

.table-wrapper-test { 
    overflow-x:scroll;
    overflow-y:visible;
    width:250px;
    margin-left: 120px;
}

.table-wrapper th.first_th {
    height: 108px;
}

.table-wrapper th:first-child.product_name {
    border: 1px solid #aaa;
    background: none !important;
    color: black;
}

.table-wrapper td, th {
    padding: 5px 20px;
    width: 100px;
}
tbody tr {
  
}
.table-wrapper th:first-child {
    position: absolute;
    left: 30px;
    overflow: hidden;
}

/* END  */

/* color palette */
.color_div {
    width: 20px;
    height: 20px;
    display: inline-block;
    vertical-align: bottom;
}

</style>
<script type="text/javascript">

function recalculate_td_height() {
    $('.product_name').each(function(index, el) {
        var height = $(el).next('td').css('height');
        $(el).css('height', height);
        // console.log(height);
        // console.log($(el).css('height'));
    });
}

$(function () {

    $('.table-wrapper tr').each(function () {
        var tr = $(this),
        h = 0;
        tr.children().each(function () {
        var td = $(this),
        tdh = td.height();
        if (tdh > h) h = tdh;
        });
        tr.css({height: h + 'px'});
    });

	// var width  = $('.product_name').next('td').css('width');
	// var height = $('.product_name').next('td').css('height');

	// $('.table-wrapper tr').each(function () {
	// 	var tr = $(this);
	// 	tr.css('width', width);
	// 	tr.css('height', height);
	// });

    recalculate_td_height();


    // $('.table-wrapper').on('click', '.product_name', function(event) {
    //     event.preventDefault();
    //     /* Act on the event */
    //     console.log($(this).data('id'));


    //     var pid       = $(this).data('id');
    //     var date_from = $('#date_from').val();
    //     var date_to   = $('#date_to').val();

    //     $.ajax({
    //         url: 'statistic/statistic_ajax.php?page=size_color_range',
    //         type: 'POST',
    //         dataType: 'html',
    //         data: { id: pid, date_from: date_from , date_to: date_to },
    //     })
    //     .done(function(data) {
    //         console.log("success");
    //         $('#product_size_color_details-modal-body').html(data);
    //         $('#product_size_color_details').modal('show');
    //     })
    //     .fail(function() {
    //         console.log("error");
    //     })
    //     .always(function() {
    //         console.log("complete");
    //     });
        

    // });



    $('.dTableFull').on( 'draw.dt', function () {
        // console.log( 'Table redrawn' );
        recalculate_td_height();
    });

});



</script>
<script>
    $(function(){
        tableHoverClasses();
        // dateRangePickerTwo();
    });
</script>
<?php return ob_get_clean(); ?>