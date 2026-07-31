<?php
################ Import CSV into database ###############

require_once("../global.php");
global $_e;

$_d = ","; //$delimiter

$show_form = true;
###### When file submit
if (isset($_POST['csvImport'])) {
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
                $manufacture      = $val[2];
                $sweden20         = $val[3];
                $norwegian23      = $val[4];
                $denmark24        = $val[5];
                $finland25        = $val[6];
                $ch26        = $val[7];

                $sql = "UPDATE `product_setting` SET `setting_val` = ? WHERE `p_id` = ? AND `setting_name` = ?";
                $res = $dbF->setRow($sql, array($manufacture, $pId, 'Model'));

                $sql1 = "UPDATE `product_price` SET `propri_price` = ? WHERE `propri_cur_id` = ? AND `propri_prodet_id` = ?";
                $res1 = $dbF->setRow($sql1, array($sweden20, 20, $pId));

                $sql2 = "UPDATE `product_price` SET `propri_price` = ? WHERE `propri_cur_id` = ? AND `propri_prodet_id` = ?";
                $res2 = $dbF->setRow($sql2, array($norwegian23, 23, $pId));

                $sql3 = "UPDATE `product_price` SET `propri_price` = ? WHERE `propri_cur_id` = ? AND `propri_prodet_id` = ?";
                $res3 = $dbF->setRow($sql3, array($denmark24, 24, $pId));

                $sql4 = "UPDATE `product_price` SET `propri_price` = ? WHERE `propri_cur_id` = ? AND `propri_prodet_id` = ?";
                $res4 = $dbF->setRow($sql4, array($finland25, 25, $pId));                
                
                $sql5 = "UPDATE `product_price` SET `propri_price` = ? WHERE `propri_cur_id` = ? AND `propri_prodet_id` = ?";
                $res5 = $dbF->setRow($sql5, array($ch26, 26, $pId));
                
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
        <form action="-<?php echo $functions->getLinkFolder(); ?>?page=csv#importPrice" class="form-horizontal" method="post"
              enctype="multipart/form-data">

            <div class="form-group">
                <label><?php echo $_e['Product Price Exported File']; ?> :</label>
                <input type="file" name="csv" required/>
            </div>

            <input type="submit" name="csvImport" value="<?php echo $_e['Submit']; ?>" class="btn btn-primary">
        </form>
    </div>
    <?php
}
?>