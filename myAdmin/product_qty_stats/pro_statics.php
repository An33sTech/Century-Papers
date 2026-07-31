<?php

ob_start();



require_once("classes/proStatics.class.php");
  require_once ("../product_management/functions/product_function.php");
global $dbF;



$proStatics  =   new proStatics();

   $productF = new product_function();

// $dbF->prnt($_POST);

//$dbF->prnt($_FILES);

//exit;

?>

<h2 class="sub_heading"><?php echo _uc($_e['Product Stats']); ?></h2>



    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist">

  <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc('All Country'); ?></a></li>

        <li class=""><a href="#sweden" role="tab" data-toggle="tab"><?php echo _uc($_e['Sweden']); ?></a></li>
        <li><a href="#norwegian" role="tab" data-toggle="tab"><?php echo _uc($_e['Norwegian']); ?></a></li>
        <li><a href="#finland" role="tab" data-toggle="tab"><?php echo _uc($_e['Finland']); ?></a></li>
        <li><a href="#denmark" role="tab" data-toggle="tab"><?php echo _uc($_e['Denmark']); ?></a></li>

    </ul>





    <!-- Tab panes -->



 <form class="form-horizontal" enctype="multipart/form-data" method="post">
        
        <?php    

 
if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {




    $date_range_from1     = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : '';
    $date_range_to1       = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : '';


$invoiceStatus3 = (isset($_POST['invoiceStatus']) && $_POST['invoiceStatus'] != '' )  ? $_POST['invoiceStatus'] : NULL;

    $invoiceStatus = $productF->invoiceStatusAdmin($invoiceStatus3);


}else{

    $date_range_from1     = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : '';
    $date_range_to1       = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : '';




    $invoiceStatus = $productF->invoiceStatus();


}
        ?>

 <div class="container-fluid" id="sortByDate">
                 <form class="form-inline" role="form">
                    <h4><?php echo $_e['Search By Date Range'] ?></h4>


                    <small>Leave Blank For Current Day Sales Statistics....<br><br><br>
                            </small>





                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i> </div>
                                <input type="text" name="min" value="<?php echo $date_range_from1 ?>" id="min" autocomplete="off" class="form-control" placeholder="<?php echo $_e['Date From'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i> </div>
                                <input type="text" name="max" value="<?php echo $date_range_to1 ?>" id="max" autocomplete="off" class="form-control" placeholder="<?php echo $_e['Date To'] ?>">
                            </div>

          </div>











                                       <div class="form-group">


<div class="col-sm-10 col-md-9">
<fieldset id ="store" class="statusSelectFieldset">
<select name="invoiceStatus" required id="statusSelect"  class="form-control statusSelect">
    <option value="">Select Order Status</option>
    <option <?php if (@$invoiceStatus3 == "9999"): ?>
        selected
    <?php endif ?> value="9999">ALL Except Cancel,Denied,Complete,Full Refunded</option>
<?php echo $invoiceStatus ?>
</select>
</fieldset>
</div>
</div>

    <div class="col-sm-7" style=" margin-left: 0; padding-left: 5px; ">
                    <button type="submit" name="submit" value="GENERATE" class="btn btn-sm btn-primary st_generate_btn"> Generate Statistics  </button>
                </div>
            
                        </div>
            
                









        </form>

    <div class="tab-content">






    <?php

 

         


// $dbF->prnt($invoiceStatus);


     // $functions->dataTableDateRange(true, 1, true,$invoiceStatus); 


     ?>






        <div class="tab-pane fade in active container-fluid" id="home">

        

            <?php


    $date_range_selected = '';
if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {

    $date_range_from     = isset($_POST['min']) && $_POST['min'] != '' ? $_POST['min'] : 'All';
    $date_range_to       = isset($_POST['max']) && $_POST['max'] != '' ? $_POST['max'] : 'All';

    # if no date to is supplied, then use this month's last date, (this month can be the selected month, or the default current month)
    // if ( $date_range_to == '' ) {
    //     $end  = new DateTime( $date_range_from );
    //     $date_range_to = $end->format("Y-m-t");
    // }

    $date_range_selected = ' (' . $date_range_from . ' - ' . $date_range_to . ')';
}
?>

  <h2  class="tab_heading"><?php echo $date_range_selected; ?></h2>

  <?php


if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {
   $proStatics->statsView();
}


              ?>

        </div>

        <div class="tab-pane fade container-fluid" id="sweden">
            <h2  class="tab_heading"><?php echo $date_range_selected; ?></h2>
            <?php

            if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {
   $proStatics->statsViewSEK();
}



 ?>
        </div>
        <div class="tab-pane fade container-fluid" id="norwegian">
            <h2  class="tab_heading"><?php echo $date_range_selected; ?></h2>
            <?php 

                        if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {
   $proStatics->statsViewNOK();
}



 ?>
        </div>
        <div class="tab-pane fade container-fluid" id="finland">
            <h2  class="tab_heading"><?php echo $date_range_selected; ?></h2>
            <?php 



                        if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {
   $proStatics->statsViewFI();
}
  ?>
        </div>
        <div class="tab-pane fade container-fluid" id="denmark">
            <h2  class="tab_heading"><?php echo $date_range_selected; ?></h2>
            <?php 


                if (isset($_POST['submit']) && $_POST['submit'] == 'GENERATE') {
   $proStatics->statsViewDK();
}



  ?>
        </div>

    </div>



<script>

    $(function(){
// $( "#min" ).datepicker();
        tableHoverClasses();

        dateJqueryUi();

        minMaxDateFilter();

     // dateJqueryUi();
        dateRangePicker();
        minMaxDate();








    });



    function deletePage(ths){

        btn=$(ths);

        if(secure_delete()){

            btn.addClass('disabled');

            btn.children('.trash').hide();

            btn.children('.waiting').show();



            id=btn.attr('data-id');

            $.ajax({

                type: 'POST',

                url: 'pages/page_ajax.php?page=deletePage',

                data: { id:id }

            }).done(function(data)

                {

                    ift =true;

                    if(data=='1'){

                        ift = false;

                        btn.closest('tr').hide(1000,function(){$(this).remove()});

                    }

                    else if(data=='0'){

                        jAlertifyAlert('<?php echo _js($_e['Delete Fail Please Try Again.']); ?>');

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





var dateCodeTo   = '';
var dateCodeFrom = '';

minMaxDateFilter = function() {


//         $(".input_date" ).datepicker({

// yearRange: "-100:+10", changeYear: true, dateFormat: 'yy-mm-dd',minDate: 0




// });




    console.log("minMaxDateFilter");
    $( ".input_date" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        changeYear: true,
        yearRange: "1935:2050",
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate,i ) {
            $( ".input_date" ).datepicker( "option", "minDate", selectedDate);
        
       console.log("selectedDate");

        }


    });


    $( ".max,#max" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        changeYear: true,
        yearRange: "1935:2050",
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate ) {
        
            $( ".min,#min" ).datepicker( "option", "maxDate", selectedDate );

            try {


                var date = $(this).datepicker('getDate'),
                    day  = date.getDate(),  
                    month = date.getMonth() + 1,  
                     year =  date.getFullYear();
                    dateCodeTo = year + '-' + month + '-' + day;
                    console.log(dateCodeTo);

                    console.log(selectedDate);


                        var min_datepicker_val = $( "#min" ).data('datepicker').lastVal;
                        if ( min_datepicker_val != "" ) {
                            fetch_ajax_result_again(dateCodeFrom,dateCodeTo);
                            console.log("Inside Max");
                        };


            } catch (e) {
                console.log(e);
            }


        }

    });
};

function fetch_ajax_result_again(dateCodeFrom, dateCodeTo) {

    my_dtable = $.fn.dataTable.tables( { visible: true, api: true } );
    console.log($.fn.dataTable.tables(true));
    $(my_dtable).DataTable().ajax.reload();

}





</script>

<?php return ob_get_clean(); ?>