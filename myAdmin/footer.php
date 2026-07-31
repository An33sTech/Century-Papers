<?php $functions->adminFooter(); ?>

    <!-- scripts -->
    
    <!--- Jquery 2 not support IE 6,7,8-->
    <!--   <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/angular.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/angularApp.js"></script>
    <!-- js tree --> 
    <link rel="stylesheet" href="<?php echo WEB_ADMIN_URL; ?>/assets/jstree/themes/default/style.min.css" />
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/jstree/jstree.js"></script>
    <!--  Color Picker -->
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/colorpicker/js/colorpicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/colorpicker/css/colorpicker.css">
    <!-- Ajax file upload-->
    <!-- Our CSS stylesheet file -->
    <link rel="stylesheet" href="<?php echo WEB_ADMIN_URL; ?>/ajaxFileUpload/css/styles.css" />
    <!-- switch on off -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/bs-switch/bootstrap-switch.css" />
    <script type="text/javascript" src="assets/bs-switch/bootstrap-switch.js"></script>
    -->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bs-switch/bootstrap-switch.3.0.css" />
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/bs-switch/bootstrap-switch.3.0.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/editor/ckeditor.js"></script>
    
        <!-- Including the HTML5 Uploader plugin -->
    <script src="<?php echo WEB_ADMIN_URL; ?>/ajaxFileUpload/js/jquery.filedrop.js"></script>
    <!-- The main script file -->
    <script src="<?php echo WEB_ADMIN_URL; ?>/ajaxFileUpload/js/script.js"></script>
    <!-- new datatable css-->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/css/buttons.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/css/select.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/css/colReorder.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/css/responsive.dataTables.min.css" />
    <!-- new datatable css END-->
    <!-- new datatable js-->
    <script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/jquery.dataTables.min.js"></script>
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/dataTables.bootstrap.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/dataTables.buttons.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/buttons.flash.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/buttons.bootstrap.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/jszip.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/pdfmake.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/vfs_fonts.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/buttons.html5.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/buttons.print.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/buttons.colVis.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/dataTables.select.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/dataTables.fixedHeader.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/dataTables.colReorder.min.js"></script>-->
    <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/datatables/js/dataTables.responsive.min.js"></script>-->
    <!-- new datatable js END-->
    <!-- old datatable-->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/data_table_bs/colVis/dataTables.colVis.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/data_table_bs/DT_bootstrap.css" />
    <script src="<?php echo WEB_ADMIN_URL; ?>/assets/data_table_bs/jquery.dataTables.1.10.2.min.js"></script>
    <script src="<?php echo WEB_ADMIN_URL; ?>/assets/data_table_bs/dataTables.tableTools.2.2.2.min.js"></script>
    <script src="<?php echo WEB_ADMIN_URL; ?>/assets/data_table_bs/colVis/dataTables.colVis.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/data_table_bs/DT_bootstrap.js"></script>
    <!-- old datatable -->
    <!--Bootstrap Dual Select Box-->
    <script src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js">
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-duallistbox/src/bootstrap-duallistbox.css">
    <!-- Jquery UI-->
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/js/jquery-ui.1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/js/bootstrap.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?php echo WEB_ADMIN_URL; ?>/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo WEB_ADMIN_URL; ?>/js/bootstrap-select.min.js"></script>
    <!--multiselect js-->
    <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        // $('#example-getting-started, #example-getting-started1, #example-getting-started2').multiselect({
        //   includeSelectAllOption: true,
        //   enableHTML: true,
        //   filterPlaceholder: 'Search for something...',
        //   enableFiltering: true,
        //   enableCaseInsensitiveFiltering: true,
        //   optionLabel: function(element) {
        //     return '<img src="' + $(element).attr('data-img') + '"> ' + $(element).text();
        //   },
        // });
    
        // $('#newDealProduct').multiselect({
        //   includeSelectAllOption: true,
        //   enableHTML: true,
        //   filterPlaceholder: 'Search for something...',
        //   enableFiltering: true,
        //   enableCaseInsensitiveFiltering: true
        // });
    
        // $('.newDealProduct').multiselect({
        //   includeSelectAllOption: true,
        //   enableHTML: true,
        //   filterPlaceholder: 'Search for something...',
        //   enableFiltering: true,
        //   enableCaseInsensitiveFiltering: true
        // });
    
        // $('.salesFeatureSetting').multiselect({
        //   includeSelectAllOption: true,
        //   enableHTML: true,
        //   filterPlaceholder: 'Search for something...',
        //   enableFiltering: true,
        //   enableCaseInsensitiveFiltering: true
        // });
    
        // $('#example-getting-started_without_img').multiselect({
        //   includeSelectAllOption: true,
        //   enableHTML: true,
        //   filterPlaceholder: 'Search for something...',
        //   enableFiltering: true,
        //   enableCaseInsensitiveFiltering: true
        // });
    
      });
    </script>
    <!-- Alertify JS-->
    <script src="<?php echo WEB_ADMIN_URL; ?>/assets/alertify/lib/alertify.min.js"></script>  
    <script src="<?php echo WEB_ADMIN_URL; ?>/js/footer_script.js"></script>  
            <script type="module" src="<?php echo WEB_ADMIN_URL; ?>/js/main.js"></script>
        <script src="<?php echo WEB_ADMIN_URL; ?>/js/controller.js?ver=<?php echo filemtime(__DIR__ . '/js/controller.js'); ?>"></script>
    <script>
      $(document).ready(function() {
        $('.make-switch').on('switch-change', function(e, data) {
          if (data.value) {
            $(this).find(".checkboxHidden").val('1');
          } else {
            $(this).find(".checkboxHidden").val('0');
          }
        });
        
        /* jAlert */
        $("#jAlert").dialog({
          modal: true,
          autoOpen: false,
          buttons: {
            "Close": function() {
              $(this).dialog("close");
            }
          }
        });
        $('.ui-dialog-buttonset').find('button').addClass('btn-danger').addClass('btn');
        /* jAlert End */
      });
    </script>
    <div id="jAlert" style="display: none;"></div> <!-- use for jAlert custome alert show -->
    <div class="notifications ">
      <!-- <div  id="uniqueId" class="notification ">
            <div class="notification_close btn btn-default">x</div>
            <div class="notification_heading navbar-inverse">
                Alert Will show here, Product
            </div>
            <div class="notification_text btn-success">   Message</div>
        </div>-->
    
    
    
    </div>

        <!--</div>-->
        <!-- scripts -->
        
         <!-- Bootstrap -->
        <!--<script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/js/bootstrap.min.js"></script>-->
          <!--Select-->
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-select/dist/js/bootstrap-select.min.js"></script>-->
        
           <!--Multiselect-->
        <!--<script type="text/javascript"-->
        <!--  src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>-->
          
        <!-- Jstree -->
        <!--<script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/jstree/dist/jstree.min.js"></script>-->
        
        <!-- DataTables JS-->
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/DataTables/datatables.min.js"></script>-->
        
        <!-- Alertify JS-->
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/alertify/alertify.min.js"></script>-->
        
        <!--Bootstrap/Jquery Dual Select Box-->
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js">-->
        <!--</script>-->
        
         <!-- Progressbar Animation JavaScript -->
        <!--<script src='<?php echo WEB_ADMIN_URL; ?>/assets/waypoints/lib/jquery.waypoints.min.js'></script>-->
        <!--<script src='<?php echo WEB_ADMIN_URL; ?>/assets/jquery.counterup/jquery.counterup.min.js'></script>-->
        
        <!-- Twitter Bootstrap Ajax Typeahead Plugin -->
        <!--<script type='text/javascript'-->
        <!--  src='<?php echo WEB_ADMIN_URL; ?>/assets/biggora-bootstrap-ajax-typeahead/js/bootstrap-typeahead.min.js' />-->
        <!--</script>-->
        
        
        
        
   
        
        <!--  Color Picker -->
        <!--<script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/colorpicker/js/colorpicker.js"></script>-->

        <!-- Including the HTML5 Uploader plugin -->
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/ajaxFileUpload/js/jquery.filedrop.js"></script>-->
        
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/ajaxFileUpload/js/script.js"></script>-->
        
        <!--<script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/bs-switch/bootstrap-switch.3.0.js"></script>-->
        <!--<script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/editor/ckeditor.js"></script>-->
        

        
       
        

        
       
        

        

        
         
        
        <!-- main common functions -->
        
        <!--<script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/main.php'></script>-->
        <!--<script type="module" src="<?php echo WEB_ADMIN_URL; ?>/js/main.js"></script>-->
        <!--<script src="<?php echo WEB_ADMIN_URL; ?>/js/controller.js"></script>-->
         <!-- use for jAlert custome alert show -->
        <!--<div id="jAlert" style="display: none;"></div>-->
        
        <!--<div class="notifications "></div> -->
 
    </body>
        
</html>