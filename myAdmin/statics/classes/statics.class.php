<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class statics extends object_class{
    public $productF;
    public function __construct(){
        parent::__construct('3');

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //index
        $_w['Statics'] = '' ;
        $_w['Statics Reports'] = '' ;


        //This Class
        $_w['SNO'] = '' ;
        $_w['QTY'] = '' ;
        $_w['DATE'] = '' ;
        $_w['PENDING'] = '' ;
        $_w['COMPLETE'] = '' ;
        $_w['IN PROCESS'] = '' ;
        $_w['TOTAL'] = '' ;
        $_w['Date From'] = '' ;
        $_w['Date To'] = '' ;
        $_w['Daily'] = '' ;
        $_w['Monthly'] = '' ;
        $_w['Yearly'] = '' ;
        $_w['Report By'] = '' ;
        $_w['Submit'] = '' ;

        $_w['Currency'] = '' ;
        $_w['Price'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Statics');
    }

    public function form(){
        global $_e;
        $form_fields = array();
        $form_fields[] = array(
            'name'  => 'page',
            'value' => "statics",
            'type'  => 'hidden',
        );
        $form_fields[] = array(
            'label' => _uc($_e['Date From']),
            'name'  => 'from',
            'value' => @$_GET['from'],
            'type'  => 'text',
            // 'required'  => 'true',
            'class' => 'form-control from',
        );

        $form_fields[] = array(
            'label' => _uc($_e['Date To']),
            'name'  => 'to',
            'value' => @$_GET['to'],
            'type'  => 'text',
            // 'required'  => 'true',
            'class' => 'form-control to',
        );

        $form_fields[] = array(
            'label' => _uc($_e['Report By']),
            'name'  => 'by',
            'select' => @$_GET['by'],
            'type'  => 'radio',
            'value'  => array("day","month","year"),
            'option'  => array($_e["Daily"],$_e["Monthly"],$_e["Yearly"]),
            'required'  => 'true',
            'class' => '',
            'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>',
        );


        $form_fields[] = array(
            'type'      => 'submit',
            'class'     => 'btn btn-primary',
            'value'     => $_e['Submit'],
        );

        $form_fields['form']  = array(
            'name'      => "form",
            'type'      => 'form',
            'class'     => "form-horizontal",
            'action'   => '',
            'method'   => 'get',
            'format'   => '<div class="form-horizontal">

<small style="color: red;font-size: large;">Leave Blank For Current Day Sales Statistics....<br><br><br>
                            </small>

            {{form}}</div>'
        );

        $format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);
    }

    public function report(){
        if(isset($_GET['by'])){
            global $_e;
            echo '<hr><h2 class="sub_heading borderIfNotabs">'._uc($_e['Statics Reports']).'</h2>';

            // Report By country
            $sql    = "SELECT DISTINCT(shippingCountry) as shippingCountry FROM order_invoice ORDER BY shippingCountry ASC";
            $data   = $this->dbF->getRows($sql);

            $i      = 0;
            $groupBY = $_GET['by'];
            foreach ( $data as $val ) {
                $i++;
                $country = $val['shippingCountry'];
                $countryName = $this->functions->countryFullName($country);
                echo "<hr><h2 class='sub_heading borderIfNotabs'>$countryName</h2>";
                $this->order_daily_value_report($country,$groupBY);
            }

        }
    }


    public function order_daily_value_report($country='',$groupByT='day'){
        global $_e;

        if(empty($country)){
            $countryT = '';
        }else{
            $countryT = " AND shippingCountry = '$country'";
        }
        if($groupByT == 'month'){
            $groupBy = ", Month(`invoice_date`)";
        }elseif($groupByT == 'year'){
            $groupBy = "";
        }else{
            $groupByT = "day";
            $groupBy = ", Month(`invoice_date`),DAY(`invoice_date`)";
        }



   $date_from = (isset($_GET['from']) && $_GET['from'] != '' )  ? $_GET['from'] . ' 00:00:00'  : date('Y-m-d') . ' 00:00:00';


        $date_to   = (isset($_GET['to']) && $_GET['to'] != '' )    ? $_GET['to'] . ' 23:59:59'    : date('Y-m-d') . ' 23:59:59';




        // $date_from  = $_GET['from'];
        // $date_to    = $_GET['to'];

        //Currency separator
        $sql      = "SELECT DISTINCT (price_code) as currency FROM order_invoice WHERE 1=1 $countryT
                      AND invoice_date >= '$date_from'  AND invoice_date <= '$date_to' GROUP BY invoice_status,Year(`invoice_date`) $groupBy ORDER BY invoice_date ASC";
        $dataCurr = $this->dbF->getRows($sql);

        foreach($dataCurr as $curr) {
            $currency = $curr['currency'];
            echo "<div class='container-fluid responsive-table'>";
            echo "<h3 class=' borderIfNotabs btn-primary'>$country :: $currency</h3>";

            $sql = "SELECT SUM(total_price) as cnt,count(order_invoice_pk) as qty,invoice_date,invoice_status FROM order_invoice WHERE price_code = '$currency' $countryT
                    AND invoice_date >= '$date_from'  AND invoice_date <= '$date_to' GROUP BY invoice_status,Year(`invoice_date`) $groupBy ORDER BY invoice_date ASC";
            $dataPen = $this->dbF->getRows($sql);
            $dates = array();
            


            $i = 1;
            $grand_total = 0;
            $pending_total = 0;
            $success_total = 0;
            $in_process_total = 0;
            $success_total_qty = 0;
            $in_process_total_qty = 0;

            echo "<table class='table tableIBMS table-hover'>
                <thead>
                    <tr><th>{$_e['SNO']}</th><th>{$_e['DATE']}</th><th>({$_e['QTY']}) {$_e['COMPLETE']}</th><th>({$_e['QTY']}) {$_e['IN PROCESS']}</th><th>({$_e['QTY']}) {$_e['TOTAL']}</th></tr>
                </thead>
                <tbody>";
            $trs = '';
            $last_date = date("Y-m-d",strtotime($date_from."-1 Day"));
            foreach ($dataPen as $val) {
                $date = date("Y-m-d", strtotime($val['invoice_date']));
                if ($groupByT == 'month') {
                    $date = date("Y-m", strtotime($val['invoice_date']));
                } elseif ($groupByT == 'year') {
                    $date = date("Y", strtotime($val['invoice_date']));
                }
                if ( isset($dates[$date]) ) {
                    continue; //stop repeating dates, in group by different order_status
                }

                //Print Blank Dates
                $expect_date = date("Y-m-d",strtotime($last_date."+1 Day"));
                if($expect_date != $date && $groupByT == 'day'){
                    //find Date Difference for loop
                    $now = strtotime($expect_date);
                    $reach = strtotime($date);
                    $datediff =  $reach-$now;
                    $datediff =  floor($datediff/(60*60*24));
                    for ($j=0; $j<=$datediff; $j++ ) {
                        if($expect_date == $date) break;
                        $last_date      = $expect_date;
                        $trs .= "<tr><td>$i</td><td>$expect_date</td><td>(0) 0</td><td>(0) 0</td><td>(0) 0</td></tr>";
                        $expect_date = date("Y-m-d",strtotime($last_date."+1 Day"));
                        $i++;
                    }
                }

                //var_dump ($val);
                $dates[$date]   = $date;
                $last_date      = $date;
                $pending        = $this->findInArray($dataPen, $date, '2', $groupByT);
                $denied         = $this->findInArray($dataPen, $date, '1', $groupByT);
                $cancel         = $this->findInArray($dataPen, $date, '0', $groupByT);
                $success        = $this->findInArray($dataPen, $date, '3', $groupByT);
                $in_process     = $this->findInArray($dataPen, $date, 'in_process', $groupByT);


                $success_order        = $this->findInArray($dataPen, $date, '3', $groupByT,"qty");
                $in_process_order        = $this->findInArray($dataPen, $date, 'in_process', $groupByT,"qty");

                $total          = $success + $in_process;

                $grand_total    += $total;
                $pending_total  += $pending;
                $success_total  += $success;
                $in_process_total += $in_process;

                $success_total_qty  += $success_order;
                $in_process_total_qty += $in_process_order;

                $total = ($total>0) ? $total." $currency" : $total;

                $total_qty = $success_order+$in_process_order;
                $trs .= "<tr><td>$i</td><td>$date</td><td>($success_order) $success</td><td>($in_process_order) $in_process</td><td>($total_qty) $total</td></tr>";
                $i++;
            }

            if ($grand_total == 0) {
                echo "<tr><td colspan='5' class='text-center bg-danger'>$currency NO RECORD FOUND </td></tr>";
            }else{
                //Print Blank Dates
                $expect_date = date("Y-m-d",strtotime($last_date."+1 Day"));
                if($expect_date != $date_to && $groupByT == 'day'){
                    //find Date Difference for loop
                    $now = strtotime($expect_date);
                    $reach = strtotime($date_to);
                    $datediff =  $reach-$now;
                    $datediff =  floor($datediff/(60*60*24));
                    for($j=0;$j<=$datediff;$j++){
                        $last_date      = $expect_date;
                        $trs .= "<tr><td>$i</td><td>$expect_date</td><td>(0) 0</td><td>(0) 0</td><td>(0) 0</td></tr>";
                        $expect_date = date("Y-m-d",strtotime($last_date."+1 Day"));
                        $i++;
                    }
                }

                echo $trs;
                $total_qty = $in_process_total_qty+$success_total_qty;
                echo "<tr><th colspan='2'>{$_e['TOTAL']}</th><th>($success_total_qty) $success_total</th><th>($in_process_total_qty) $in_process_total</th><th>($total_qty) $grand_total $currency</th></tr>";
            }

            echo "</tbody></table></div>";

        }//currency separator loop end
    }

    function findInArray($data,$date,$invoice,$groupByT,$find="price"){
        $in_process = 0;
        // $in_process_status = array(2,5,7,8,9,10,11); //refer product_function.php invoiceStatusArray();
        $in_process_status = array(2,5,7,8,9,10,11,4,12,13); //refer product_function.php invoiceStatusArray();
        foreach($data as $val){
            $fDate     = date("Y-m-d",strtotime($val['invoice_date']));
            if($groupByT == 'month'){
                $fDate     = date("Y-m",strtotime($val['invoice_date']));
            }elseif($groupByT == 'year'){
                $fDate     = date("Y",strtotime($val['invoice_date']));
            }
            $fInvoice = $val['invoice_status'];
            $count = $val['cnt'];

            if($find=="qty"){
                $count = $val['qty'];
            }


            $count = round($count,2);
            if($date == $fDate && $fInvoice == $invoice){
                return $count;
            }

            //In Process Count
           
            
            
            
            if($date == $fDate && $invoice == "in_process" && in_array($fInvoice,$in_process_status)){
                $in_process += $count;
            }
        }

        if($invoice == "in_process"){
            return $in_process;
        }

        return "0";
    }

}
?>