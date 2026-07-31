<?php

################ Import CSV into database ###############



require_once("../global.php");

global $_e;



$_d = ","; //$delimiter



$show_form = true;

###### When file submit

if (isset($_POST['csvImportDiscount'])) {

    global $dbF;

    if ($_FILES["csv"]["size"] > 0) {

        ###### check uploaded file type

        if ( ($_FILES["csv"]["type"] == "text/csv") ||

             ($_FILES["csv"]["type"] == "application/csv") ||

             ($_FILES["csv"]["type"] == "application/vnd.ms-excel")) {



            ###### get the csv file

            $file = $_FILES["csv"]["tmp_name"];

            $handle = fopen($file, "r");

            $row = 0; // count find no of rows

            $val = fgetcsv($handle, 1000, "{$_d}"); // First Heading column Skip

            while ( ($val = fgetcsv($handle, 1000, "{$_d}") ) !== FALSE) {

                ###### Get csv value by columns no

                $pId              = $val[0];

                $discountId       = $val[2];

                $curID            = $val[3];

                $discPrice        = $val[4];

                $format           = $val[5];



                $dis = explode(' ', $discPrice);



                if(!empty($discountId) && $discountId != ''){

                    $sql = "UPDATE `product_discount_prices` SET `product_dis_price` = ? WHERE `product_dis_id` = ? AND `product_dis_curr_Id` = ?";

                    $res = $dbF->setRow($sql, array($dis[0], $discountId, $curID));



                    $sql1 = "UPDATE `product_discount_setting` SET `product_dis_value` = ? WHERE `product_dis_id` = ? AND `product_dis_name` = ?";

                    $res1 = $dbF->setRow($sql1, array($format, $discountId, 'discountFormat'));

                }

                else{

                    if($dis != 0){

                        $sql4 = "SELECT `discount_PId` FROM `product_discount` WHERE `discount_PId` = ?";

                        $res4 = $dbF->getRow($sql4, array($pId));

                        $count_res4 = count($res4);

                        $sqlDisable0 = "SET foreign_key_checks = 0";
                        $sqdis0 = $db->prepare($sqlDisable0);
                        $sqdis0->execute();

                        if($count_res4 == 0){

                            $sql2 = "INSERT INTO `product_discount`(`discount_PId`, `product_dis_status`) VALUES(?,?)";

                            $res2 = $dbF->setRow($sql2, array($pId, 1));

                            $diss_id = $dbF->rowLastId;

                        }else{

                            $diss_id = $res4['discount_PId'];

                        }

                            $sql3 = "INSERT INTO `product_discount_prices`(`product_dis_id`,`product_dis_curr_Id`,`product_dis_price`) VALUES(?,?,?)";

                            $res3 = $dbF->setRow($sql3, array($diss_id, $curID, $dis[0]));

                        $sqlDisable1 = "SET foreign_key_checks = 1";
                        $sqdis1 = $db->prepare($sqlDisable1); 
                        $sqdis1->execute();   
                    }

                }

                

                $row++;

            }

            fclose($handle);



            echo "<div class='alert alert-success'>File Import SuccessFully : " . "" . $_FILES["csv"]["name"] . "</div>";

            $show_form = false;

        } else {

            echo "<div class='alert alert-danger'>File Not Supported : " . "" . $_FILES["csv"]["name"] . "</div>";

        }

    }//if end csv size

}//if end on submit





###### Get CSV File HTML Form

if ($show_form) {

    ?>

    <div class="container-fluid">

        <form action="-<?php echo $functions->getLinkFolder(); ?>?page=csv#importDiscount" class="form-horizontal" method="post"

              enctype="multipart/form-data">



            <div class="form-group">

                <label><?php echo $_e['Product Discount Exported File']; ?> :</label>

                <input type="file" name="csv" required/>

            </div>



            <input type="submit" name="csvImportDiscount" value="<?php echo $_e['Submit']; ?>" class="btn btn-primary">

        </form>

    </div>

    <?php

}

?>