<?php

// if (isset($_GET['newexport'])) {
//     include_once(__DIR__."/export.php");
// }
ob_start();
global $_e;
global $adminPanelLanguage;
$_w = array();
$_w['Sales Report'] = '';
$_w['Export'] = '';
$_w['New Export Stock'] = '';
$_w['Submit']       = '';
$_w['Export Product Data with size color']       = '';
$_w['Export Product Data']       = '';
$_w['Import Stock'] = '';
$_w['Stock Inventory Exported File'] = '';
$_w['Select Currency']= ''; 
$_w['Model.No']= ''; 
$_w['Country Code']= ''; 
$_w['Label']= ''; 
$_w['Size']= ''; 
$_w['Color']= ''; 
$_w['Categories']= ''; 
$_w['Date From']= '';
$_w['Date To']= '';
$_w['Visitor Report']= '';
$_w['SNO']= '';
$_w['TITLE']= '';
$_w['PUBLISH DATE']= '';
$_w['UPDATE']= '';
$_w['ACTION']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['']= '';
$_w['email']= '';
$_w['phone']= '';
$_w['name']= '';
$_w['Invoice']= '';



$_w['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.'] = '';
$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Visiting Customer');


     function newsView(){
         global $dbF;
        $today = date('Y-m-d');
        // $page_type = $this->page_type;
        $sql  = "SELECT * FROM cart_order_remaining ORDER BY `cart_order_remaining`.`_id` DESC";
        $data =  $dbF->getRows($sql);
        print_news_table($data);
    }

 function print_news_table($data){
        $data   = empty($data) ? array() : $data;
        global $_e,$functions;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['Invoice']) .'</th>
                        <th>'. _u($_e['name']) .'</th>
                        <th>'. _u($_e['email']) .'</th>
                        <th>'. _u($_e['phone']) .'</th>
                    </thead>
                <tbody>';

        $i = 0;
        $defaultLang = $functions->AdminDefaultLanguage();
        foreach($data as $val){
            $i++;
            // $id = $val['id'];
            $invId = ($val['invId']);
            $name = ($val['name']);
            $email = ($val['email']);
            $phone = ($val['phone']);
            // $heading = $heading[$defaultLang];
            echo "<tr>
                    <td>$i</td>
                        <td>
                        <div class='btn-group btn-group-sm'>
                            <a href='../invoicePrint?mailId=$invId' target='_blank' class='btn'>
                                $invId
                            </a>
                        </div>
                    <td>$name</td>
                    <td>$email</td>
                    <td>$phone</td>
        
              
                    </td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }


?>



  

    <!-- <h4 class="sub_heading"><?php echo _uc($_e['Import/Export']); ?></h4> -->
    <!-- Nav tabs -->
<!--     <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Sales Report']); ?></a></li>
    </ul> -->

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2 class="tab_heading"><?php echo _uc($_e['Visitor Report']); ?></h2>
           

            <div class="">
                <?php # echo _uc($_e['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.']); ?>   
            </div>

            <a style="display: none;" href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&newexport" class="btn btn-primary btn-lg"><?php echo _uc($_e['New Export Stock']); ?></a> 

            <div class="container-fluid">
            


<?php     

newsView();
 ?>






            </div>
        </div>
    </div>

<script>
    $(function(){
        dateJqueryUi();
    });
</script>

<?php return ob_get_clean(); ?>