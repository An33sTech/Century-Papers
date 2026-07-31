<?php
ob_start();
require_once "classes/invoice.php";

global $_e, $sharkOnlineConnection;

$invoice = new invoice();
$invoice->update();

if (isset($_POST)) {
  $pId = $_POST['pId'];
  if (empty($pId)) {
    $pId = $_GET['orderId']; // in case of future need just add this in url  &orderId={id}
  }
}

$orderId = $pId;
$data = $invoice->invoiceDetail($orderId);
$country_list = $functions->countrylist();

if (isset($_GET['apiData'])) {
  echo "<pre>";
  print_r(unserialize(base64_decode($data['apiReturn'])));
  echo "</pre>";
}

$extraId = isset($_GET['extraOrderId']) ? $_GET['extraOrderId'] : "";

$sql = "SELECT `apiReturn` FROM `order_extra_amount` WHERE `id` = ? ";
$res = $dbF->getRow($sql, array($extraId));

if (isset($_GET['extraApiData'])) {
  echo "<pre>";
  print_r(unserialize(base64_decode($res['apiReturn'])));
  echo "</pre>";
}


if (!empty($data['apiReturn'])) {
  $viewApiReturnData = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&apiData'>" . $_e['View Api Return Info'] . "</a>";
} else {
  $viewApiReturnData = '';
}
?>

<style>
  .btn-dark,
  .btn-dark:hover {
    background: #000;
    color: #fff;
    font-weight: 600;
  }

  .check_out_offer_body .items {
    padding: 1rem;
    display: flex;
    font-size: 1.5rem;
    gap: 1rem;
  }

  .check_out_offer_body .items .checkbox {
    margin: 0 !important;
    width: 20px !important;
    height: 20px !important;
  }

  p.relativeProducts {
    font-size: 10px;
    font-weight: bold;
    font-style: italic;
    color: #3F51B5;
  }

  .deducted_producted h4 {
    margin: 0;
  }

  .deducted_producted {
    border-bottom: 2px solid;
    margin-bottom: 1rem;
  }

  .deducted_producted:last-child {
    border-bottom: 0;
    margin-bottom: 1rem;
  }
</style>

<!--popup for deducted product-->
<?php

$orderInvoiceId = $data['order_invoice_pk'];
$stock_deduct_sql = "SELECT * FROM `stock_deduct_record` WHERE order_inv_id LIKE '%$orderInvoiceId%' AND is_show = 0";
$stock_deduct_sql_data = $dbF->getRows($stock_deduct_sql);

if ($stock_deduct_sql_data) {
  ?>

  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary stockDeductBtn" data-toggle="modal" data-target="#exampleModalCenter"
    style="display:none"></button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title text-center" id="exampleModalLongTitle">Following products have been deducted
          </h2>
        </div>
        <div class="modal-body">

          <?php if ($stock_deduct_sql_data) {
            foreach ($stock_deduct_sql_data as $key => $val) {
              $id_temp = $val['id'];
              $stock_qty = explode(':', $val['stock_qty']);
              $msg = "";
              if ($stock_qty[0] == 0) {
                $msg = "Previous quantity was $stock_qty[0] and current stock is also 0";
              } else {
                $msg = "Previous quantity was $stock_qty[0] after deduction it remains $stock_qty[1]";
              }
              ?>
              <div class="deducted_producted">
                <h4><?php echo $val['order_product_name'] ?></h4>
                <p style="font-size: large;"><strong><?php echo $msg ?></strong></p>
              </div>
              <?php
              $sql_update = "UPDATE stock_deduct_record SET is_show = ? WHERE id = ?";
              $dbF->setRow($sql_update, array(1, $id_temp));
            }
          } ?>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close <span
              aria-hidden="true">&times;</span></button>
        </div>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function () {
      $('.stockDeductBtn').click();

    });
  </script>

<?php } ?>

<!--popup-->
<div class="modal fade" id="checkoutOfferModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="myModalLabel"><?php echo $_e['STOCK AND SHIP MANAGEMENT'] ?></h2>
      </div>

      <div class="modal-body" style="text-align: center;">

        <div class="check_out_offer_main">
          <div class="check_out_offer_head">
            <div class="checkout_offer_heading_text">
              <h4 class="cart_check_head">Following marked items will be deducted from the stock and will be shipped
              </h4>
            </div>
            <div class="checkout_offer_line"></div>
          </div>

          <div class="check_out_offer_body"></div>

          <div id="checkout_offer_container" class="container-fluid padding-0" style="margin-top:30px;">

          </div><!--r_box_area end-->


        </div><!--related_products_area end-->



        <button id="checkout_offer_dismiss_btnNew" type="button" class="btn btn-dark btn-lg"
          data-dismiss="modal">UPDATE</button>

      </div>

    </div>
  </div>

</div>
<?php

$invForLable = @explode("_", $data['manuall_id'])[1];
$invForLable = (isset($invForLable) && !empty($invForLable)) ? $invForLable : $data['invoice_id'];
?>

<div class="modal fade" id="checkoutOfferModalForShippingLabel" tabindex="-1" role="dialog"
  aria-labelledby="ForShippingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="ForShippingLabel">Shipping Label</h4>
      </div>

      <div class="modal-body ">
        <div>
          <p id="shippingLabelLink"></p>
        </div>
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
          cellspacing="0">
          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Detail']); ?></div>
            </th>
          </thead>

          <tr>
            <td><?php echo _uc($_e['Invoice ID']); ?></td>
            <td>
              <input class="shipLabel_inv_id form-control" value="<?php echo $invForLable ?>" readonly>
              <input type="hidden" class="shipLabel_countryName form-control"
                value="<?php echo $data['shippingCountry'] ?>">
            </td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['PACKAGE CODE']); ?></td>
            <td>
              <select name="" class="shipLabel_basic_code form-control">
                <option value="19">MYPACK COLLECT – 19</option>
                <option value="24">MYPACK RETUR – 24</option>
                <option value="86">VARUBREV - 86</option>
              </select>
            </td>
          </tr>


          <tr>
            <td><?php echo _uc($_e['CUSTOMER NAME']); ?></td>
            <td><input class="shipLabel_cus_name form-control" value="<?php echo $data['receiver_name']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['CUSTOMER EMAIL']); ?></td>
            <td><input class="shipLabel_cus_email form-control" value="<?php echo $data['receiver_email']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['COSTUMER ADRESS']); ?></td>
            <td>
              <textarea class="shipLabel_cus_add form-control"><?php echo $data['receiver_address']; ?></textarea>
            </td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['Contact No']); ?></td>
            <td><input class="shipLabel_contact_no_se form-control" value="<?php echo $data['receiver_phone']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['Postal Code']); ?></td>
            <td><input class="shipLabel_post_code form-control" value="<?php echo $data['receiver_post']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['City']); ?></td>
            <td><input class="shipLabel_city form-control" value="<?php echo $data['receiver_city']; ?>">
            </td>
          </tr>



          <tr>
            <td><?php echo _uc($_e['Total Gross Weight']); ?></td>
            <td><input class="shipLabel_Weight form-control" value="0.2"></td>
          </tr>


        </table>
      </div>


      <div class="modal-footer">
        <button id="shipping_label_dismiss_btnNew" type="button" data-id="se"
          class="btn btn-dark shipping_label_dismiss_btnNew" data-dismiss="modal">Submit</button>
        <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="checkoutOfferModalForShippingLabelNo" tabindex="-1" role="dialog"
  aria-labelledby="ForShippingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="ForShippingLabel">Shipping Label</h4>
      </div>
      <?php

      $invForLable = @explode("_", $data['manuall_id'])[1];
      $invForLable = (isset($invForLable) && !empty($invForLable)) ? $invForLable : $data['invoice_id'];
      ?>

      <div class="modal-body ">
        <div>
          <p id="shippingLabelLink"></p>
        </div>

        <input type="hidden" class="shipLabel_country_code" value="">
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
          cellspacing="0">
          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Invoice Detail']); ?></div>
            </th>
          </thead>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Invoice ID']); ?></td>
            <td colspan="3">
              <input class="shipLabel_inv_id form-control" value="<?php echo $invForLable; ?>" readonly>
              <input type="hidden" class="shipLabel_countryName form-control"
                value="<?php echo $data['shippingCountry'] ?>">
            </td>

          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Order ID']); ?></td>
            <td colspan="3"><input class="shipLabel_order_id form-control"
                value="<?php echo $data['order_invoice_pk']; ?>" readonly></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Order Date']); ?></td>
            <td colspan="3"><input class="form-control" value="<?php echo $data['invoice_date']; ?>" readonly></td>
            <input type="hidden" class="shipLabel_order_date"
              value="<?php echo date('Ymd', strtotime($data['invoice_date'])); ?>">
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Reason For Export']); ?></td>
            <td colspan="3"><input class="shipLabel_res_for_exp form-control" value="SALE"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Currency Code']); ?></td>
            <td colspan="3"><input class="shipLabel_curr_code form-control"
                value="<?php echo ($data['price_code'] == 'EURO' || $data['price_code'] == 'euro') ? 'EUR' : $data['price_code']; ?>">
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Frieght Charges']); ?></td>
            <td colspan="3"><input class="shipLabel_frieght_charges form-control" value=""></td>
          </tr>

          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Customer Information']); ?></div>
            </th>
          </thead>

          <tr>
            <td colspan="3"><?php echo _uc($_e['CUSTOMER NAME']); ?></td>
            <td colspan="3"><input class="shipLabel_cus_name form-control"
                value="<?php echo $data['receiver_name']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['CUSTOMER EMAIL']); ?></td>
            <td colspan="3"><input class="shipLabel_cus_email form-control"
                value="<?php echo $data['receiver_email']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['COSTUMER ADDRESS']); ?></td>
            <td colspan="3">
              <textarea class="shipLabel_cus_add form-control"><?php echo $data['receiver_address']; ?></textarea>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Contact No']); ?></td>
            <td colspan="3"><input class="shipLabel_contact_no form-control"
                value="<?php echo $data['receiver_phone']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Postal Code']); ?></td>
            <td colspan="3"><input class="shipLabel_post_code form-control"
                value="<?php echo $data['receiver_post']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['City']); ?></td>
            <td colspan="3"><input class="shipLabel_city form-control" value="<?php echo $data['receiver_city']; ?>">
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Province Code']); ?></td>
            <td colspan="3"><input class="shipLabel_province_code form-control" value=""></td>
          </tr>

          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Package Information']); ?></div>
            </th>
          </thead>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Package Description']); ?></td>
            <td colspan="3">
              <textarea class="shipLabel_pkg_desc form-control"></textarea>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Packaging Code']); ?></td>
            <td colspan="3">
              <select class="form-control shipLabel_pkg_code">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Package Measurement Code']); ?></td>
            <td colspan="3">
              <select class="form-control shipLabel_pkg_mea_code">
                <option value="KGS">Kilo Gram (KGS)</option>
                <option value="PND">Pounds (PND)</option>
              </select>
            </td>
          </tr>



          <tr>
            <td colspan="3"><?php echo _uc($_e['Package Weight']); ?></td>
            <td colspan="3">
              <input class="shipLabel_pkg_weight form-control" value="1">
            </td>
          </tr>

          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Products']); ?></div>
            </th>
          </thead>

          <tr>
            <td><?php echo _uc($_e['Product Name']); ?></td>
            <td><?php echo _uc($_e['Qty']); ?></td>
            <td><?php echo _uc($_e['Price']); ?></td>
            <td><?php echo _uc($_e['Unit']); ?></td>
            <td><?php echo _uc($_e['Commodity Code']); ?></td>
            <td><?php echo _uc($_e['Part Number']); ?></td>
          </tr>
          <?php
          $pdata = $invoice->invoiceProduct($orderId);
          foreach ($pdata as $p) {

            $pQty = $p['order_pQty'];
            $total = $p['order_salePrice'] * $pQty;

            $discount = $p['order_discount'];
            $totalDiscount += $discount * $pQty;

            $saleIn = (($total / $pQty) - ($discount));
            $saleIn = round($saleIn, 2);


            ?>
            <tr class="order_products_row">
              <td><input class="proName form-control" value="<?php echo $p['order_pName']; ?>" readonly />
              </td>
              <td><input class="proQty form-control" value="<?php echo $p['order_pQty']; ?>" /></td>
              <td><input class="proPrice form-control" value="<?php echo $saleIn / 2; ?>" /></td>
              <td>
                <select class="form-control proUnit">
                  <option value="PC-PIECES">PIECES</option>
                </select>
              </td>
              <td>
                <!--<input class="proCommodityCode form-control" value="000000"  />-->
                <select class="form-control proCommodityCode" onchange="proCommodityCode(this)">
                  <option value="4203.1001">4203.1001</option>
                  <option value="9506.9908">9506.9908</option>
                  <option value="6506.1000">6506.1000</option>
                  <option value="3926.2000">3926.2000</option>
                  <option value="4203.2100">4203.2100</option>
                </select>
                </select>

              </td>
              <td><input class="proPartNo form-control" value="PROTECTION GARMENT" /></td>

            </tr>
          <?php } ?>



        </table>
      </div>


      <div class="modal-footer">
        <button id="shipping_label_dismiss_btnNewNo" type="button" data-id="no"
          class="btn btn-dark shipping_label_dismiss_btnNewNo">Submit</button>
        <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
  function proCommodityCode(ths) {

    let changeValue = $(ths).val();
    let partObj = {
      "4203.1001": "PROTECTION GARMENT",
      "9506.9908": "BACK PROTECTOR / KNEE PROTECTOR",
      "6506.1000": "HELMET MOTORCYCLE",
      "3926.2000": "PROTECTION BOOTS",
      "4203.2100": "PROTECTION GLOVES"
    }

    let updatedPartNo = partObj[`${changeValue}`];
    $(ths).closest('tr').find('.proPartNo').val(updatedPartNo)
  }
</script>

<div class="modal fade" id="checkoutOfferModalForShippingLabelEu" tabindex="-1" role="dialog"
  aria-labelledby="ForShippingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="ForShippingLabel">Shipping Label Eu</h4>
      </div>

      <div class="modal-body ">
        <div>
          <p id="shippingLabelLink"></p>
        </div>
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
          cellspacing="0">
          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Detail']); ?></div>
            </th>
          </thead>

          <tr>
            <td><?php echo _uc($_e['Invoice ID']); ?></td>
            <td>
              <input class="shipLabel_inv_id form-control" value="<?php echo $data['invoice_id']; ?>" readonly>
              <input type="hidden" class="shipLabel_countryName form-control"
                value="<?php echo $data['shippingCountry'] ?>">

            </td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['PACKAGE CODE']); ?></td>
            <td>
              <select name="" class="shipLabel_basic_code form-control">
                <option value="19">MYPACK COLLECT – 19</option>
                <option value="24">MYPACK RETUR – 24</option>
                <option value="86">VARUBREV - 86</option>
              </select>
            </td>
          </tr>


          <tr>
            <td><?php echo _uc($_e['CUSTOMER NAME']); ?></td>
            <td><input class="shipLabel_cus_name form-control" value="<?php echo $data['receiver_name']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['CUSTOMER EMAIL']); ?></td>
            <td><input class="shipLabel_cus_email form-control" value="<?php echo $data['receiver_email']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['COSTUMER ADRESS']); ?></td>
            <td>
              <textarea class="shipLabel_cus_add form-control"><?php echo $data['receiver_address']; ?></textarea>
            </td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['Contact No']); ?></td>
            <td><input class="shipLabel_contact_no form-control" value="<?php echo $data['receiver_phone']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['Postal Code']); ?></td>
            <td><input class="shipLabel_post_code form-control" value="<?php echo $data['receiver_post']; ?>"></td>
          </tr>

          <tr>
            <td><?php echo _uc($_e['City']); ?></td>
            <td><input class="shipLabel_city form-control" value="<?php echo $data['receiver_city']; ?>">
            </td>
          </tr>



          <tr>
            <td><?php echo _uc($_e['Total Gross Weight']); ?></td>
            <td><input class="shipLabel_Weight form-control" value="0.2"></td>
          </tr>


        </table>
      </div>


      <div class="modal-footer">
        <button id="shipping_label_dismiss_btnNewEu" type="button" data-id="eu"
          class="btn btn-dark shipping_label_dismiss_btnNewEu" data-dismiss="modal">Submit</button>
        <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="checkoutOfferModalForShippingLabelCh" tabindex="-1" role="dialog"
  aria-labelledby="ForShippingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="ForShippingLabel">Shipping Label</h4>
      </div>
      <?php

      $invForLable = @explode("_", $data['manuall_id'])[1];
      $invForLable = (isset($invForLable) && !empty($invForLable)) ? $invForLable : $data['invoice_id'];
      ?>
      <div class="modal-body ">
        <div>
          <p id="shippingLabelLink"></p>
        </div>

        <input type="hidden" class="shipLabel_country_code" value="">
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
          cellspacing="0">
          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc($_e['Invoice Detail']); ?></div>
            </th>
          </thead>

          <tr>
            <td colspan="3"><?php echo _uc($_e['Invoice ID']); ?></td>
            <td colspan="3"><input class="shipLabel_inv_id_ch form-control" value="<?php echo $invForLable; ?>"
                readonly>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Order ID'); ?></td>
            <td colspan="3"><input class="shipLabel_order_id_ch form-control"
                value="<?php echo $data['order_invoice_pk']; ?>" readonly></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Order Date'); ?></td>
            <td colspan="3"><input class="form-control" value="<?php echo $data['invoice_date']; ?>" readonly></td>
            <input type="hidden" class="shipLabel_order_date"
              value="<?php echo date('Ymd', strtotime($data['invoice_date'])); ?>">
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Reason For Export'); ?></td>
            <td colspan="3"><input class="shipLabel_res_for_exp form-control" value="SALE"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Currency Code'); ?></td>
            <td colspan="3"><input class="shipLabel_curr_code form-control" value="<?php echo $data['price_code']; ?>">
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Frieght Charges'); ?></td>
            <td colspan="3"><input class="shipLabel_frieght_charges form-control" value=""></td>
          </tr>

          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc('Customer Information'); ?></div>
            </th>
          </thead>

          <tr>
            <td colspan="3"><?php echo _uc('CUSTOMER NAME'); ?></td>
            <td colspan="3"><input class="shipLabel_cus_name form-control"
                value="<?php echo $data['receiver_name']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('CUSTOMER EMAIL'); ?></td>
            <td colspan="3"><input class="shipLabel_cus_email form-control"
                value="<?php echo $data['receiver_email']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('COSTUMER ADDRESS'); ?></td>
            <td colspan="3">
              <textarea class="shipLabel_cus_add form-control"><?php echo $data['receiver_address']; ?></textarea>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Contact No'); ?></td>
            <td colspan="3"><input class="shipLabel_contact_noch form-control"
                value="<?php echo $data['receiver_phone']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Postal Code'); ?></td>
            <td colspan="3"><input class="shipLabel_post_code form-control"
                value="<?php echo $data['receiver_post']; ?>"></td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('City'); ?></td>
            <td colspan="3"><input class="shipLabel_city form-control" value="<?php echo $data['receiver_city']; ?>">
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Province Code'); ?></td>
            <td colspan="3"><input class="shipLabel_province_code form-control" value=""></td>
          </tr>

          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc('Package Information'); ?></div>
            </th>
          </thead>

          <tr>
            <td colspan="3"><?php echo _uc('Package Description'); ?></td>
            <td colspan="3">
              <textarea class="shipLabel_pkg_desc form-control"></textarea>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Packaging Code'); ?></td>
            <td colspan="3">
              <select class="form-control shipLabel_pkg_code">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
          </tr>

          <tr>
            <td colspan="3"><?php echo _uc('Package Measurement Code'); ?></td>
            <td colspan="3">
              <select class="form-control shipLabel_pkg_mea_code">
                <option value="KGS">Kilo Gram (KGS)</option>
                <option value="PND">Pounds (PND)</option>
              </select>
            </td>
          </tr>



          <tr>
            <td colspan="3"><?php echo _uc('Package Weight'); ?></td>
            <td colspan="3">
              <input class="shipLabel_pkg_weight form-control" value="1">
            </td>
          </tr>

          <thead>
            <th colspan="6">
              <div class="text-center"><?php echo _uc('Products'); ?></div>
            </th>
          </thead>

          <tr>
            <td><?php echo _uc('Product Name'); ?></td>
            <td><?php echo _uc('Qty'); ?></td>
            <td><?php echo _uc('Price'); ?></td>
            <td><?php echo _uc('Unit'); ?></td>
            <td><?php echo _uc('Commodity Code'); ?></td>
            <td><?php echo _uc('Part Number'); ?></td>
          </tr>
          <?php
          $pdata = $invoice->invoiceProduct($orderId);
          foreach ($pdata as $p) {
            $pQty = $p['order_pQty'];
            $total = $p['order_salePrice'] * $pQty;

            $discount = $p['order_discount'];
            $totalDiscount += $discount * $pQty;

            $saleIn = (($total / $pQty) - ($discount));
            $saleIn = round($saleIn, 2);
            ?>
            <tr class="order_products_row">
              <td><input class="proName form-control" value="<?php echo $p['order_pName']; ?>" readonly /></td>
              <td><input class="proQty form-control" value="<?php echo $p['order_pQty']; ?>" /></td>
              <td><input class="proPrice form-control" value="<?php echo $saleIn / 2; ?>" /></td>
              <td>
                <select class="form-control proUnit">
                  <option value="PC-PIECES">PIECES</option>
                </select>
              </td>
              <td>
                <select class="form-control proCommodityCode" onchange="proCommodityCode(this)">
                  <option value="4203.1001">4203.1001</option>
                  <option value="9506.9908">9506.9908</option>
                  <option value="6506.1000">6506.1000</option>
                  <option value="3926.2000">3926.2000</option>
                  <option value="4203.2100">4203.2100</option>
                </select>

              </td>
              <td><input class="proPartNo form-control" value="PROTECTION GARMENT" /></td>

            </tr>
          <?php } ?>



        </table>
      </div>


      <div class="modal-footer">
        <button id="shipping_label_dismiss_btnNewCh" type="button" data-id="no"
          class="btn btn-dark shipping_label_dismiss_btnNewCh">Submit</button>
        <button id="shipping_label_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<!--<?php echo $data['sender_country'] ?>-->
<h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Invoice Detail View']); ?></h4>
<!-- sender detail -->
<div class="table-responsive newProduct col-sm-6">
  <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <th colspan="7">
        <div class="text-center"><?php echo _u($_e['ORDER SENDER DETAIL']); ?></div>
      </th>
    </thead>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
      <td><?php echo $data['sender_name']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
      <td><?php echo $data['sender_address']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Post Code']); ?></td>
      <td><?php echo $data['sender_post']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['City']); ?></td>
      <td><?php echo $data['sender_city']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
      <td><?php
      $countryName = $country_list[strtoupper($data['sender_country'])];
      echo $countryName; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
      <td><?php echo $data['sender_email']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
      <td><?php echo $data['sender_phone']; ?></td>
    </tr>
  </table>
</div>
<!-- sender detail end -->

<!-- receiver detail -->
<div class="table-responsive newProduct col-sm-6">
  <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <th colspan="7">
        <div class="text-center"><?php echo _u($_e['ORDER RECEIVER DETAIL']); ?></div>
      </th>
    </thead>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
      <td><?php echo $data['receiver_name']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
      <td><?php echo $data['receiver_address']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Post Code']); ?></td>
      <td><?php echo $data['receiver_post']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['City']); ?></td>
      <td><?php echo $data['receiver_city']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
      <td><?php $countryName = $country_list[strtoupper($data['receiver_country'])];
      echo $countryName; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
      <td><?php echo $data['receiver_email']; ?></td>
    </tr>
    <tr>
      <td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
      <td><?php echo $data['receiver_phone']; ?></td>
    </tr>
  </table>
</div>
<!-- receiver detail end -->


<div class="clearfix"></div>
<div class="padding-20"></div>

<form method="post" id="extraAmountForm">
  <input type="hidden" name="invoiceId" value="<?php echo $data['invoice_id']; ?>" />
  <input type="hidden" name="senderEmail" value="<?php echo $data['sender_email']; ?>" />
  <input type="hidden" name="curSybmol" value="<?php echo $data['price_code']; ?>" />
  <input type="hidden" name="paymentType" value="<?php echo $data['paymentType']; ?>" />
  <div class="table-responsive newProduct">
    <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <th colspan="12">
          <div class="text-center"><?php echo _u($_e['EXTRA PAYMENT FORM']); ?></div>
        </th>
      </thead>
      <tr class="gray-tr">
        <th><?php echo $_e['Amount']; ?></th>
        <th><?php echo $_e['Description']; ?></th>
      </tr>
      <tr>
        <td><input type="text" class="form-control" name="extra_amnt"></td>
        <td><textarea class="form-control" name="description" placeholder="Description"></textarea></td>
      </tr>
      <tr>
        <td colspan="2"><input type="button" class="btn btn-primary" id="submitExtraAmount" name="submitExtraAmount"
            value="Submit"></td>
      </tr>
    </table>
  </div>
  <!-- product detail end -->
</form>

<script type="text/javascript">
  $('#submitExtraAmount').click(function () {
    extraForm = $('#extraAmountForm').serialize();

    $.ajax({
      url: 'order/order_ajax.php?page=submitExtraAmountForm',
      type: 'post',
      data: extraForm
    }).done(function (res) {
      console.log(res);
      if (res == '0') {
        jAlertifyAlert('Something Went Wrong!');
      } else {
        jAlertifyAlert('<?php $dbF->hardWords("Email Sent To Customer For Extra Payment", true); ?>');
      }

    });
  });
</script>

<div class="clearfix"></div>
<div class="padding-20"></div>

<div class="table-responsive newProduct">
  <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <th colspan="12">
        <div class="text-center"><?php echo _u($_e['EXTRA PAYMENTS']); ?></div>
      </th>
    </thead>
    <tr class="gray-tr">
      <th><?php echo $_e['SNO']; ?></th>
      <th><?php echo $_e['INVOICE DATE']; ?></th>
      <th><?php echo $_e['EXTRA AMOUNT']; ?></th>
      <th><?php echo $_e['DESC']; ?></th>
      <th><?php echo $_e['INVOICE STATUS']; ?></th>
      <th><?php echo $_e['PAYMENT TYPE']; ?></th>
      <th><?php echo $_e['PAYMENT STATUS']; ?></th>
      <th><?php echo $_e['RESERVATION NO']; ?></th>
      <th><?php echo $_e['PAYMENT LINK']; ?></th>
      <th><?php echo $_e['PAYMENT INFO']; ?></th>
      <th><?php echo $_e['UPDATE DATE']; ?></th>
    </tr>
    <?php
    $extraPayLink = 'https://sharkspeed.' . _l($data['shippingCountry']);

    $sql = "SELECT * FROM `order_extra_amount` WHERE `invoice_no` = ?";
    $res = $dbF->getRows($sql, array($data['invoice_id']));
    $j = 1;
    if (!empty($res)) {
      foreach ($res as $key => $value) {

        $val = $invoice->productF->paymentArrayFind($value['paymentType']);
        if ($value['paymentType'] == '2') {
          $processT = "<div class='btn-success btn btn-sm'> $val </div> ";
        } else if ($value['paymentType'] == '0') {
          $processT = "<div class='btn-default btn btn-sm'> $val </div> ";
        } else {
          $processT = "<div class='btn-default btn btn-sm'> $val </div> ";
        }

        if ($value['paymentType'] == 2) {
          $link = $extraPayLink . '/extra_pay?inv=' . $data["invoice_id"] . '&id=' . $value['id'];
        } else if ($value['paymentType'] == 5) {
          $link = $extraPayLink . '/extra_payment?inv=' . $invoiceId . '&id=' . $value['id'];
        }

        if (!empty($value['apiReturn'])) {
          $viewExtraApiReturnData = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&extraOrderId=$value[id]&extraApiData'>" . $_e['View Api Return Info'] . "</a>";
        } else {
          $viewExtraApiReturnData = '';
        }

        $paymentStatus = $value['orderStatus'];
        if ($paymentStatus == 'process') {
          $paymentStatus = _uc($_e['OK']);
        } else {
          $paymentStatus = _uc($_e['InComplete']);
        }



        echo "
<tr class=''>
<td>$j</td>
<td>$value[invoice_date]</td>
<td>$value[extra_amount] $value[price_code]</td>
<td>$value[description]</td>
<td>$value[orderStatus]</td>
<td>$processT $viewExtraApiReturnData</td>
<td>$paymentStatus</td>
<td>$value[rsvNo]</td>
<td><input type='text' id='extraAmountLink' value='" . $link . "'><button class='btn btn-primary btn-xs' onclick='copyFunction()'>Copy</button></td>
<td>$value[payment_info]</td>
<td>$value[date_timestamp]</td>
</tr>";

        $j++;
      }
    } else {
      echo "<tr><td colspan='11'>No Record Found</td></tr>";
    }

    ?>
  </table>
</div>
<!-- product detail end -->

<div class="clearfix"></div>
<div class="padding-20"></div>

<!-- product detail -->
<form method="post" id="myFormSubmit">
  <input type="hidden" id="relativeSizes" name="relativeSize" value="">
  <div class="table-responsive newProduct">
    <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <th colspan="14">
          <div class="text-center"><?php echo _u($_e['ORDER PRODUCTS']); ?></div>
        </th>
      </thead>
      <tr class="gray-tr">
        <th><?php echo _u($_e['SNO']); ?></th>
        <th><?php echo _u($_e['PRODUCT NAME']); ?></th>
        <th><?php echo _u($_e['STORE NAME']); ?></th>
        <th><?php echo _u($_e['ORIGINAL PRICE']); ?></th>
        <th><?php echo _u($_e['SALE IN PRICE']); ?></th>
        <th><?php echo _u($_e['DISCOUNT']); ?></th>
        <th><?php echo _u($_e['SALE QTY']); ?></th>
        <th><?php echo _u($_e['OFFER']); ?></th>
        <th><?php echo _u($_e['PROCESS']); ?></th>
        <th><?php echo _u($_e['RETURNS INFO']); ?></th>
        <th><?php echo _u($_e['ASSIGN TO SHIPPER']); ?></th>
        <th><?php echo _u($_e['SUPPLIER']); ?></th>
        <th><?php echo _u($_e['STATUS SUPPLIER']); ?></th>
        <th><?php echo _u($_e['TOTAL']); ?></th>

      </tr>
      <?php
      $totalDiscount = 0;
      $totalProductPrice = 0;
      $pdata = $invoice->invoiceProduct($orderId);
      $totalNet = 0;
      $process = "0";
      $i = 0;
      $done = true;

      foreach ($pdata as $p) {
        $i++;
        $invoice_product_id = $p['invoice_product_pk'];
        $pIds = explode("-", $p['order_pIds']);
        @$pId = $pIds[0];
        @$scaleId = $pIds[1];
        @$colorId = $pIds[2];
        @$storeId = $pIds[3];
        @$customId = $pIds[4];
        

        @$dealId = $p['deal']; // if not it is 0
        @$checkout = $p['checkout']; // if not it is 0
        @$info = unserialize($p['info']);

        $pQty = $p['order_pQty'];
        $total = $p['order_salePrice'] * $pQty;

        $discount = $p['order_discount'];
        $totalDiscount += $discount * $pQty;

        $saleIn = (($total / $pQty) - ($discount));
        $saleIn = round($saleIn, 2);
        $total = $saleIn * $pQty;
        $totalNet += $total;

        $singleDiscount = $discount;

        $process = $p['order_process'];
        $processTemp = "";

        if ($process === '0') {
          $processT = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
          $processTemp = "<input type='checkbox' name='pro[]' class='btn-sm btn' value='$p[invoice_product_pk]'/> &nbsp; ";
          $done = false;
        } else {
          $processT = "<div class='btn btn-success btn-sm'>" . _uc($_e['Yes']) . "</div>";
        }


        # New Returns Management Work
        switch ($process) {
          case '2':
            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Refunded']) . "</div>";
            break;
          case '3':
            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Defected']) . "</div>";
            break;
          case '4':
            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Changed Product']) . "</div>";
            break;
          case '5':
            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Changed Size']) . "</div>";
            break;

          default:
            $returns_info = '';
            break;
        }


        if ($checkout === '1') {
          $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Checkout']) . "</div>";
        } elseif ($checkout === '2') {
          $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Free Gift']) . "</div>";
        } elseif ($checkout === '3') {
          $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Extra Sale']) . "</div>";
        } else {
          $checkoutD = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
        }

        $retrunInput = "";
        @$returnP = @$p['order_return'];
        $retrunStatus = "";
        if ($returnP === '0') {
          if ($process === '0') {
            $retrunInput = '';
            $retrunStatus = "";
          } else {
            $retrunInput = "<input type='checkbox' name='retrun[]' class='btn-sm btn' value='$p[invoice_product_pk]'/> &nbsp; ";
            $retrunStatus = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
          }
        } else {
          $retrunStatus = "<div class='btn btn-success btn-sm'>" . _uc($_e['Yes']) . "</div>";
        }


        $pName = $p['order_pName'];
        //custom Info
        $sizeInfo = '';
        $class = '';
        if ($customId != '0' && !empty($customId) && $scaleId == '0') {
          $sizeInfo = "<a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
          $pName = explode(" - ", $pName);
          $pName[1] = $sizeInfo;
          $pName = implode(" - ", $pName);

          $customFieldsData = $invoice->customSubmitValues($customId);
          $customFields = $customFieldsData["form"];
          $customFormFill = $customFieldsData["formFill"];
          $sizeInfo = $functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
          $processTemp = '';
          if ($customFormFill == '1') { //edit able,, not fill
            $class = 'danger';
          }
        }

        if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
          $dealT = $_e['Deal'];
          $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
          $customFields = $invoice->dealSubmitPackage($info, false);
          $sizeInfo .= $functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
        }

        ############## Buy 2 Get 1 Free ######
        $buy_get_free = $invoice->productF->buy_get_free_invoice_div($orderId, $invoice_product_id, "2");
        if (!empty($buy_get_free)) {
          $pQty = $pQty . $buy_get_free;
        }
        ############## Buy 2 Get 1 Free END ######
      
        ############ FREE GIFT TEXT #############
        $free_gift_product_div = "";
        if ($saleIn == "0" && $p["order_pPrice"] == $singleDiscount) {
          $free_gift_product_div = $invoice->productF->free_gift_text();
        }
        ############ FREE GIFT TEXT #############
      

        $empt = "";
        $dealProCount = 0;
        $dealProCountInventry = 0;



        $order_pName = $p['order_pName'];
        $realtiveProducts = $invoice->invoiceRelativeProduct($order_pName, $pIds);


        $eX = explode(" - ", $order_pName, 2);
        if (count($eX) >= 2) {
          $s = $eX[1];
        } else {
          $s = "";
        }




        $sp = "SELECT prosiz_name FROM `product_size` WHERE `prosiz_id` = '$scaleId' ";
        $spData = $dbF->getRow($sp);



        if (empty($spData)) {
          $pids = explode(' - ', $p['order_pName']);
          $spData['prosiz_name'] = $pids[count($pids) - 1];
        } else {
          $pids = explode(' - ', $p['order_pName']);
          $tempScale = $pids[count($pids) - 1];
          if ($spData['prosiz_name'] == $tempScale) {
          } else {
            $spData['prosiz_name'] = $tempScale;
          }
        }

        $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' and `prosiz_cur_id` = 20 and `prosiz_name`='$spData[prosiz_name]' ";
        $spData = $dbF->getRow($sp);



        if ($dbF->rowCount > 0) {
          $scaleId = $spData['prosiz_id'];
        } else {
          $scaleId = filter_var(
            $pIds[1],
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
          );
        }


        @$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;



        $hash = md5($hashVal);




        $qtyStock = "";
        if (empty($info)) {

          $sqlcc = "SELECT * FROM `product_inventory` WHERE (`qty_product_scale_name` = ? OR `qty_product_scale` = ?)  AND `qty_product_id` = ?";
          $qty_itemData = $dbF->getRow($sqlcc, [$spData['prosiz_name'], $scaleId, $pId]);
          if ($dbF->rowCount == 0) {
          } else {

            if ($qty_itemData !== false) {
              $t_qty = $qty_itemData['qty_item'];
              $qtyStock = "<br>Stock Quantity = ($t_qty)";
            }
            if (intval($qty_itemData['qty_item']) >= intval($p['order_pQty'])) {
              $empt = "<br><span data-tooltip='Yes, item exists in inventory.' data-tooltip-position='bottom' title='Yes, item exists in inventory.1' style='font-size:13px; color: blue;'>&#10004;  
        </span>";
            }
          }
        } else {

          foreach ($info as $key => $value) {

            $pIds = explode("-", $value['pIds']);

            $order_pName = $p['order_pName'];

            $eX = explode(" - ", $order_pName, 2);
            $s = $eX[1];

            $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pIds[0]' and `prosiz_cur_id` = 20 and `prosiz_name`='$s' ";

            $spData = $dbF->getRow($sp);
            if ($dbF->rowCount > 0) {
              $scaleId = $spData['prosiz_id'];
            } else {
              $scaleId = $pIds[1];
            }


            @$hashVal = $pIds[0] . ":" . $scaleId . ":0:6";
            $hash = md5($hashVal);



            $sqlcc = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = '$s' AND `qty_product_id` = '$pIds[0]' AND `qty_item` != '0' AND `qty_item` >= '$pQty'";
            $dbF->getRow($sqlcc);
            if ($dbF->rowCount == 0) {
            } else {
              $dealProCountInventry++;
            }
            $dealProCount++;
          }
          if ($dealProCount == $dealProCountInventry) {

            $empt = "<br><span data-tooltip='Yes, item exists in inventory.' data-tooltip-position='bottom' title='Yes, item exists in inventory2' style='font-size:13px; color: blue;'>&#10004;  
</span>";
          } else {

            $empt = "<br><span data-tooltip='$dealProCountInventry, items exists in inventory, out of $dealProCount products.' data-tooltip-position='bottom' title='$dealProCountInventry, item exists in inventory3, out of $dealProCount products.' style='font-size:13px; color: red;'>x  
</span>";
          }
        }





        $selectBox = "";

        $sqlcC = "SELECT * FROM `accounts` WHERE `acc_role` = '7' and acc_type = '1'";
        $supplierData = $sharkOnlineConnection->getRows($sqlcC);
        if ($sharkOnlineConnection->rowCount > 0) {
          foreach ($supplierData as $valZ) {
            $acc_name = $valZ['acc_name'];
            $acc_email = $valZ['acc_email'];
            $acc_id = $valZ['acc_id'];
            $invoice_id = $data["invoice_id"];
            $selectBox .= "<option value='$acc_id' data-id='$invoice_product_id - $invoice_id'>$acc_name - $acc_email</option>";
          }
        }


        ########### Supplier ##########
        $supplierSettingArray = $invoice->productF->getProductSetting($pId);
        $supplier = $invoice->productF->productSettingArray('supplier', $supplierSettingArray, $pId);

        //<td>$pName $sizeInfo $free_gift_product_div</td>
      


        $tdSupplier = "<td><div class='form-group'>
<label class='col-sm-2 col-md-3  control-label'>Send To:</label>
<div class='col-sm-10  col-md-9'>
<select class='shipperSelect form-control'>
<option value=''>select supplier</option>
$selectBox
</select>
</div>
</div>
</td>";

        $sqlcC = "SELECT opID,ass_id, sFK FROM `history` WHERE `opID` = '$invoice_product_id'";
        $supplierData = $sharkOnlineConnection->getRow($sqlcC);


        if ($sharkOnlineConnection->rowCount > 0) {


          $sqlcc = "SELECT acc_name,acc_email FROM accounts WHERE `acc_id` = '$supplierData[ass_id]'";
          $datavs = $sharkOnlineConnection->getRow($sqlcc);

          $snname = $datavs['acc_name'] . ' - ' . $datavs['acc_email'];


          $tdSupplier = "<td>Supplier Name: $snname";


          $sqlcc = "SELECT boxname FROM supplier WHERE `order_info` = '$invoice_id' and boxname !='' and boxname !=' '";
          $dataBN = $sharkOnlineConnection->getRow($sqlcc);
          $bBname = $dataBN['boxname'];
          if ($sharkOnlineConnection->rowCount > 0) {
            $tdSupplier .= " - Box/Group Name: $bBname";
          }
          $tdSupplier .= "</td>";
        }

        $sqlSharkOnline = "SELECT s.order_status, sp.date_timestamp, sp.message FROM supplier s LEFT JOIN supplier_message sp ON sp.order_id = s.id 
                WHERE s.id = '$supplierData[sFK]' ORDER BY sp.msg_id DESC";
        $dataStatus = $sharkOnlineConnection->getRows($sqlSharkOnline);
        if ($sharkOnlineConnection->rowCount > 0) {
          $statusInvoiceSupplier = '<strong>' . ucwords($dataStatus[0]["order_status"]) . '</strong>';
          $datTimeStatusUpdate = empty($dataStatus[0]["date_timestamp"]) ? "" : date('Y-m-d H:i', strtotime($dataStatus[0]["date_timestamp"]));
          $supplierMessage = empty($dataStatus[0]["message"]) ? "No message yet" : " - " . $dataStatus[0]["message"] . "<br> - " . $dataStatus[1]["message"];
        }


        if ($pQty > 1) {
          $style = "background-color: #000; color: #fff; padding: 15px;";
        } else {
          $style = "";
        }
        
        if($p["shipped"] == 1){
            $storeNameOrShipped = $p['order_pStore'] . '<br><div class="btn btn-success btn-sm">Shipped</div>';
            $styleShipped = "background-color: #b8b8db;";
        }else{
            $storeNameOrShipped = $p['order_pStore'];    
            $styleShipped = "";
        }

        // $processTemp
        echo "
<tr class='$class' style='$styleShipped'>
<td>$i</td>
<td>$pName $sizeInfo $empt $qtyStock <br> $realtiveProducts </td>
<td>$storeNameOrShipped</td>
<td>$p[order_pPrice]</td>
<td>$saleIn</td>
<td>$singleDiscount</td>
<td><span style='$style'>$pQty</span></td>
<td>$checkoutD</td>
<td> $processT</td>
<td>$returns_info</td>
<!-- <td>$retrunInput $retrunStatus</td> -->


$tdSupplier
<td style='font-weight: bold;'>$supplier</td>
<td><span style='text-wrap: nowrap;'>STATUS: $statusInvoiceSupplier $datTimeStatusUpdate</span><br><span>MESSAGE: $supplierMessage</span></td>
<td>$total $data[price_code]</td>

</tr>";
      }

      echo "
<tr>
<td colspan='13'><b>" . _uc($_e['Total Net Amount']) . "</b></td>
<td>$totalNet  $data[price_code]</td>
</tr>";

      ?>

    </table>
  </div>
  <!-- product detail end -->

  <div class="clearfix"></div>
  <div class="padding-20"></div>

  <!-- invoice detail -->

  <input type="hidden" name="pId" value="<?php echo $orderId; ?>" />
  <?php $functions->setFormToken('Invoice'); ?>
  <div class="table-responsive newProduct col-sm-6">
    <input type="hidden" name="invoi_idd" value="<?php echo $data['invoice_id']; ?>">
    <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <th colspan="6">
          <div class="text-center"><?php echo _uc($_e['Invoice Detail']); ?></div>
        </th>
      </thead>
      <?php
      $invForLable = @explode("_", $data['manuall_id'])[1];
      $invForLable1 = (isset($invForLable) && !empty($invForLable)) ? " ($invForLable) " : "";

      ?>
      <tr class="gray-tr">
        <th><?php echo _uc($_e['Property']); ?></th>
        <th><?php echo _uc($_e['Value']); ?></th>
      </tr>
      <tr>
        <td><?php echo _uc($_e['Invoice ID']); ?></td>
        <td><?php echo $data['invoice_id'] . $invForLable1; ?></td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Discount Code']); ?></td>
        <td>
          <?php $temp = $invoice->productF->get_order_invoice_record($orderId, "coupon", false);
          echo @$temp['setting_val'];
          ?>
        </td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Total Weight']); ?></td>
        <td><?php echo $data['total_weight'] . " KG"; ?></td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['DISCOUNT']); ?></td>
        <td><?php echo $totalDiscount . " " . $data['price_code']; ?></td>
      </tr>


      <?php
      $three_for_two_cat = $data['three_for_two_cat'];
      ?>
      <tr class="lasts_tr">
        <td><?php $dbF->hardWords('Three For Two Categry Price'); ?> </td>
        <td> <?php echo $three_for_two_cat . " " . $data['price_code']; ?></td>
      </tr>
      <?php //} 
      ?>

      <?php
      $staple_pro_cat = $data['staple_pro_cat'];
      if ($staple_pro_cat > 0) { ?>
        <tr class="lasts_tr">
          <td><?php $dbF->hardWords('Bundle Category Discount'); ?> </td>
          <td> <?php echo $staple_pro_cat . " " . $data['price_code']; ?></td>
        </tr>
      <?php } ?>

      <tr>
        <td><?php echo _uc($_e['Shipping Price']); ?></td>
        <td><?php echo $data['ship_price'] . " " . $data['price_code']; ?></td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Total Product Price']); ?></td>
        <td><?php echo $totalNet + $totalDiscount . " " . $data['price_code']; ?></td>
      </tr>


      <tr>
        <td><?php echo _uc($_e['Total']); ?></td>
        <td
          title="<?php echo $data['ship_price'] . '+' . ($totalNet + $totalDiscount) . '-' . $totalDiscount . ' - ' . $three_for_two_cat . ' = ' . $data['total_price']; ?>">
          <?php echo $data['total_price'] . " " . $data['price_code']; ?>
          &nbsp;<i class="glyphicon glyphicon-info-sign   "></i>
        </td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Creation Time']); ?></td>
        <td><?php echo $data['invoice_date']; ?></td>
      </tr>


      <tr>
        <td><?php echo _uc($_e['Last Updated Time']); ?></td>
        <td><?php echo $data['dateTime']; ?></td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Invoice Status']); ?></td>
        <td><?php
        $invoiceStatus = $data['invoice_status'];
        $invs = true;
        if ($invoiceStatus == 0) {
          $invStatus = "btn-danger";
        } elseif ($invoiceStatus == 1) {
          $invStatus = "btn-warning";
        } else if ($invoiceStatus == 2) {
          $invStatus = "btn-primary";
        } else if ($invoiceStatus == 3) {
          $invStatus = "btn-success";
          $invs = false;
        } else {
          $invStatus = "btn-info";
        }

        $click = '$("#invStatus").show(500);';
        $btn = '$("#upbtn").show(500);';

        //Done Was working if all product process then always show done order
        
        $oldStatus = $invoice->productF->invoiceStatusFind($invoiceStatus);
        echo "<input type='hidden' name='old_status_id' value='$invoiceStatus'>";
        echo "<input type='hidden' name='old_status_name' value='$oldStatus'>";
        if ($done === 'asad') {
          $invStatus = "btn-success";
          echo "<div class='$invStatus' onclick='$click'>Done Order Complete</div>";
        } else {
          echo "<div class='$invStatus btn' onclick='$click'>" . $invoice->productF->invoiceStatusFind($invoiceStatus) . "</div>";
        }

        ?>
          <select name="invoiceStatus" id="invStatus" style="display: none;" class="form-control">
            <?php echo $invoice->productF->invoiceStatus(); ?>
          </select>
          <script>
            $(document).ready(function () {
              $("#invStatus").val("<?php echo $invoiceStatus; ?>").change();
            });
          </script>
          <?php //} 
          ?>
        </td>
      </tr>



      <tr style="background: #fa6969;">
        <td><?php echo _uc($_e['Stock Deduct Intranet']); ?></td>
        <td class="stock_deduct_div_item">
          <?php
          $pdata = $invoice->invoiceProduct($orderId);
          $totalNet = 0;
          $process = "0";
          $i = 0;
          $done = true;
          $i = 1;
          foreach ($pdata as $p) {
            @$info = unserialize($p['info']);
            if (empty($info)) {
              $pName = $p['order_pName'];
              $order_pIds = $p['order_pIds'];
              $pArray = explode("-", $order_pIds);
              $pId = $pArray[0];
              $scaleId = $pArray[1];
              $order_pQty = $p['order_pQty'];
              
              $sqlCheck = "SELECT `invoice_product_pk`, `deducted`, `shipped` FROM `order_invoice_product` WHERE order_invoice_id = ? AND `order_pIds` = ?";
              $shippedDeductData = $dbF->getRow($sqlCheck, [$orderId, $order_pIds]);
              $deducted  = $shippedDeductData["deducted"];
              $shipped = $shippedDeductData["shipped"];

              $productImage = $invoice->productF->getProductSingleImage($pId);
              if (is_array($productImage)) {
                $productImage = WEB_URL . '/images/' . $productImage['image'];
              }
              $order_pName = $p['order_pName'];
              $eX = explode(" - ", $order_pName, 2);
              if (count($eX) >= 2) {
                $s = $eX[1];
              } else {
                $s = "";
              }

              $order_process = $p['order_process'];

              $sp = "SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id` = '$scaleId'";
              $spData = $dbF->getRow($sp);

              if (empty($spData)) {
                $pids = explode(' - ', $pName);
                $spData['prosiz_name'] = $pids[count($pids) - 1];
              } else {
                $pids = explode(' - ', $pName);
                $tempScale = $pids[count($pids) - 1];
                if ($spData['prosiz_name'] == $tempScale) {
                } else {
                  $spData['prosiz_name'] = $tempScale;
                }
              }

              $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$pId' AND `prosiz_cur_id` = 20 AND `prosiz_name`='$spData[prosiz_name]'";
              $spData = $dbF->getRow($sp);

              if ($dbF->rowCount > 0) {
                $scaleId = $spData['prosiz_id'];
              } else {

                $scaleId = filter_var(
                  $pArray[1],
                  FILTER_SANITIZE_NUMBER_FLOAT,
                  FILTER_FLAG_ALLOW_FRACTION
                );
              }


              @$hashVal = $pId . ":" . $scaleId . ":0:6";
              $hash = md5($hashVal);

              $sqlqty_item = "SELECT `qty_item` FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ?";
              $qty_itemData = $dbF->getRow($sqlqty_item, [$spData['prosiz_name'], $pId]);

              $ded = $qty_itemData['qty_item'];
              $stockChecked = '';
              if ($qty_itemData !== false && $ded >= $order_pQty && $order_process == "0" && $deducted == 0) {
                $stockChecked = 'checked';
              }
               
              echo "<div  class='deduct_products_main'>
                <div class='deduct_products'>
                    <div  class='pro_name' data-image='$productImage' data-type='normal'>" . $pName . "</div>
                    <div class='pro_qty' data-qty='$order_pQty'>Order Qty: (" . $order_pQty . ")</div>
                    <div class='pro_stock' data-stock='$ded'>Stock Qty: (" . $ded . ") </div>
              </div>";
              ?>
              <div class="checkboxesParent">
                <label <?php echo "" ?>>
                  <?php if ($order_process == "0" || 2 > 1) { ?>
                    <input <?php if($deducted == 1) echo "disabled"; ?> type="checkbox" data-type="deduct" data-key="normal" data-id="<?php echo $i ?>" class="table_check_<?php echo $i ?>"
                      value="<?php echo $hash ?>" <?php echo $stockChecked ?> name="dStock[]" /> Deduct Stock
                  <?php } ?>
                </label>
                <label <?php echo "" ?>>
                  <?php if ($order_process == "0" || 2 > 1) { ?>
                    <input <?php if($shipped == 1) echo "disabled"; ?> type="checkbox" data-type="ship" data-key="normal" data-id="<?php echo $i ?>"
                      class="table_check_ship_<?php echo $i ?>" value="<?php echo $shippedDeductData["invoice_product_pk"] ?>"
                      name="dShip[]" /> Ship Product
                  <?php } ?>
                </label>
              </div>
      </div>
      <?php
      $relativeSQL = "SELECT `setting_val` FROM `product_setting` WHERE p_id = ? AND setting_name = ?";
      $dataRelative = $dbF->getRow($relativeSQL, [$pId, 'relative']);

      if ($dbF->rowCount > 0) {
        @$colorId = $pArray[2];
        @$storeId = $pArray[3];
        $relativeProducts = unserialize($dataRelative['setting_val']);
        $iR = 1;
        foreach ($relativeProducts as $key => $val) {
          $iR++;
          if ($spData['prosiz_name'] == NULL) {
            $sp = "SELECT * FROM `product_inventory` WHERE `qty_product_id` = '$val' AND `qty_product_scale` = '$scaleId'";
          } else {
            $sp = "SELECT * FROM `product_size` WHERE `prosiz_prodet_id` = '$val' AND `prosiz_cur_id` = 20 AND `prosiz_name` = '$spData[prosiz_name]'";
          }

          $spData2 = $dbF->getRow($sp);
          if ($dbF->rowCount > 0) {
            $scaleIdNew = ($spData['prosiz_name'] == NULL) ? $scaleId : $spData2['prosiz_id'];
            $related_pname = $pName = $invoice->productF->getProductName($val) . ' - ' . $invoice->productF->getScaleName($scaleIdNew);
            @$hashVal = $val . ":" . $scaleIdNew . ":0:6";
            $hash = md5($hashVal);

            $sqlcc = "SELECT * FROM `product_inventory` WHERE `qty_product_scale_name` = ? AND `qty_product_id` = ? AND `qty_item` != '0'";
            $qty_itemData = $dbF->getRow($sqlcc, [$spData2['prosiz_name'], $val]);
            if ($dbF->rowCount > 0) {
              $qty = $qty_itemData['qty_item'];
            } else {
              $qty = 0;
            }
            $productImage = $invoice->productF->getProductSingleImage($val);
            if (is_array($productImage)) {
              $productImage = WEB_URL . '/images/' . $productImage['image'];
            }
            if ($qty > 0) {
              if ($iR === 2 || $iR > 2) {
                echo "<hr>";
                echo '<h3>' . $_e["Relative Product(s)"] . '</h3>';
              }
              echo "<div  class='deduct_products_main'>
                            <div class='deduct_products'>
                                <div class='pro_name' data-relative-sizes='" . $spData2['prosiz_name'] . "' data-image='$productImage' data-type='relative'>" . $related_pname . "</div>
                                <div class='pro_qty' data-qty='$order_pQty'>Order Qty: (" . $order_pQty . ")</div>
                                <div class='pro_stock' data-stock='$qty'>Stock Qty: (" . $qty . ") </div>
                          </div>";
              ?>
              <div class="checkboxesParent">
                <label <?php echo "" ?>>
                  <?php if ($order_process == "0" || 2 > 1) { ?>
                    <input <?php if($deducted == 1) echo "disabled"; ?> type="checkbox" data-type="deduct" data-key="<?php echo $iR . '_' . $i; ?>" data-id="<?php echo $iR; ?>" class="table_check_<?php echo $iR ?>"
                      value="<?php echo $hash ?>" name="dStock[]" />Deduct Stock
                  <?php } ?>
                </label>

                <label <?php echo "" ?>>
                  <?php if ($order_process == "0" || 2 > 1) { ?>
                    <input <?php if($shipped == 1) echo "disabled"; ?> type="checkbox" data-type="ship" data-key="<?php echo $iR . '_' . $i; ?>" data-id="<?php echo $iR; ?>" class="table_check_ship_<?php echo $iR ?>"
                      value="<?php echo $shippedDeductData["invoice_product_pk"] ?>" name="dShip[]" />Ship Product
                  <?php } ?>
                </label>
              </div>
              <?php
            }
          }
        }
      }
            }
            $i++;
            echo "<hr>";
          }
          ?>

  </td>
  </tr>

  <tr>
    <td><?php echo _uc($_e['Shipping Track Number']); ?></td>
    <td>
      <input type="hidden" name="old_trackNo" value="<?php echo $data['trackNo']; ?>" id="shiping_trackNoOld">
      <input type="text" class="form-control" value="<?php echo $data['trackNo']; ?>" id="shiping_trackNo"
        name="trackNo" />
    </td>
  </tr>



  <tr>
    <td><?php echo _uc($_e['Send Email To Customer']); ?></td>
    <td>
      <input type="hidden" value="<?php echo $data['sender_email'] ?>" name="toEmail" />
      <label><input type="radio" value="1" name="sendEmail" checked /><?php echo _u($_e['Yes']); ?>
      </label>
      <label><input type="radio" value="0" name="sendEmail" /><?php echo _u($_e['NO']); ?></label>
    </td>
  </tr>

  <!--<tr>
<td>Order process</td>
<td><?php
/*                    $click = '$("#payment").show(500);';
if($process==0){
$processT = "<div class='btn-danger' onclick='$click'> Pending, Order Now </div> ";
}else{
$processT = "<div class='btn-success'> SuccessFully </div> ";
}
echo $processT;

if($process==0){    */ ?>
<select name="payment" id="payment"  style="display: none;">
<?php /*echo $invoice->productF->paymentSelect(); */ ?>
</select>
<script>
$(document).ready(function(){
$("#payment").val("<?php /*echo $process;*/ ?>").change();
});
</script>
<?php
/*                    }
 */ ?>
</td>
</tr>-->

  </table>
  </div>


  <div class="table-responsive newProduct col-sm-6">
    <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <th colspan="6">
          <div class="text-center"><?php echo _uc($_e['Payment Information']); ?></div>
        </th>
      </thead>
      <tr class="gray-tr">
        <th><?php echo _uc($_e['Property']); ?></th>
        <th><?php echo _uc($_e['Value']); ?></th>
      </tr>
      <tr>
        <td><?php echo _uc($_e['Payment Type']); ?></td>
        <td>
          <?php $val = $invoice->productF->paymentArrayFind($data['paymentType']);

          $click = '$("#payment").show(500);';

          if ($data['paymentType'] == '2') {
            $processT = "<div class='btn-success btn btn-sm' onclick='$click'> $val </div> ";
          } else if ($data['paymentType'] == '0') {
            $processT = "<div class='btn-danger btn btn-sm' onclick='$click'> $val </div> ";
          } else {
            $processT = "<div class='btn-default btn btn-sm' onclick='$click'> $val </div> ";
          }
          echo $processT;
          if (!$done) {
            ?>
            <select name="payment" id="payment" style="display: none;" class="form-control">
              <?php echo $invoice->productF->paymentSelect(); ?>
            </select>
            <script>
              $(document).ready(function () {
                $("#payment").val("<?php echo $data['paymentType']; ?>").change();
              });
            </script>
            <?php
          } else {
            echo "<input type='hidden' value='$data[paymentType]' name='payment'/>";
          }
          echo $viewApiReturnData;
          ?>
        </td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Payment Status']); ?></td>
        <td>
          <?php $paymentStatus = $data['orderStatus'];
          if ($paymentStatus == 'process') {
            $paymentStatus = _uc($_e['OK']);
          } else {
            $paymentStatus = _uc($_e['InComplete']);
          }

          echo $paymentStatus;
          ?>
        </td>
      </tr>

      <tr>
        <td><?php echo _uc($_e['Reservation Number']); ?></td>
        <td>
          <?php echo $data['rsvNo'];
          ?>
        </td>
      </tr>

      <?php
      $staple_pro_cat_div = '';
      $bundle_type = $data['bundle_type'];
      if (!empty($data['bundle_type'])) {
        $currencyId = $_SESSION['webUser']['currencyId'];
        $currencySymbol = $data['price_code'];
        // $currencyId = $this->productClass->currentCurrencyId();
        // $currencySymbol = $this->productClass->currentCurrencySymbol();
        $staple_cat_setting = unserialize($functions->ibms_setting("stapleProductSetting"));
        $bundle_array = array();
        for ($i = 0; $i < sizeof($staple_cat_setting['quantity']); $i++) {
          $bundle_array[$staple_cat_setting['quantity'][$i]] = $staple_cat_setting['price'][$currencyId][$i];
        }
        krsort($bundle_array);
        $bundelT = $_e['Bundle feature applies:'];

        $text = $bundelT . "\n";
        $bundle_type = rtrim($bundle_type, ',');
        $bundle_type = explode(',', $bundle_type);
        foreach ($bundle_type as $key => $value) {
          $text .= $value . ' ' . $_e['pcs for'] . ' ' . $bundle_array[$value] . ' ' . $currencySymbol . "\n";
        }

        $staple_pro_cat_div = $text;
      }
      ?>

      <tr>
        <td><?php echo _uc($_e['Payment Info']); ?></td>
        <td>


          <div class="col-sm-10 col-md-9">
            <textarea name="paymentInfo" class="form-control"
              placeholder="<?php echo _uc($_e['Enter Vendor Payment Information']); ?>"
              style="width: 320px;height: 268px;">
<?php echo $data['payment_info'];


echo "\n" . $staple_pro_cat_div;
?></textarea>

          </div>
        </td>
      </tr>

    </table>
  </div>



  <!-- invoice detail End -->
  

  <div class="clearfix"></div>
<a class="btn btn-primary" id="made_to_measure"><?php $dbF->hardWords("Send Made to Measure Email", true); ?></a>
  <br>



  <div class="clearfix"></div>
  <br>

  <a href="<?php echo WEB_URL; ?>/invoicePrint.php?mailId=<?php echo $orderId; ?>" target="_blank"
    class="btn btn-info btn-lg"><?php echo _uc($_e['Print Out']); ?></a>
  <input type="submit" id="upbtn" onclick="return formSubmit();" name="submit1" value="UPDATE"
    class="submit btn btn-primary btn-lg">

  <div class="padding-20"></div>

</form>

<div>
  <button class="btn btn-info btn-lg"
    onclick="return printShippingLabel('<?php echo $data['sender_country'] ?>');"><?php echo _uc($_e['Shipping Label']); ?></button>
</div>
<br>


<div class="table-responsive newProduct col-sm-10">
  <form action="" method="post" id="internal_comment_form">
    <input type="hidden" name="orderIdint" value="<?php echo $orderId; ?>">
    <div class="col_portion">
      <h3><?php echo _uc($_e['INTERNAL COMMENT']); ?></h3>
      <div class="main_portion">
        <div class="hd_no1"><?php echo _uc($_e['Write a Comment for the Order']); ?></div>
        <div class="hd_no1"><?php echo _uc($_e['Status']); ?></div>
        <!--hd_no1 close-->
        <div class="col_select_portion">
          <input type="hidden" name="invoiceStatus" value="<?php echo $invoiceStatus; ?>">
          <p style="font-weight: bold;"><?php echo $oldStatus; ?></p>
        </div>
        <!--col_select_portion close-->
        <div class="hd_no1"><?php echo _uc($_e['Comment']); ?></div>
        <div class="txt_area">
          <textarea name="int_comTxt"></textarea>
        </div>
        <!--txt_area-->
        <div class="col_portion_middle">
          <a class="btn btn-info btn-lg" id="create_comment" style="cursor: pointer">

            <?php echo _uc($_e['Create Comment']); ?>
          </a>
          <script>
            $(".chb").change(function () {
              $(".chb").prop('checked', false);
              $(this).prop('checked', true);
            });
          </script>
        </div>
        <!--col_portion_middle-->
        <div class="col_portion_bottom">
          <ul id="prev_internal"></ul>
        </div>
        <!--col_portion_bottom-->
      </div>
      <!--main_portion close-->
    </div>
    <!--col_portion close-->
  </form>
</div>








<div class="table-responsive newProduct col-sm-10">
  <form action="" method="post" id="internal_product_form">

    <div class="col_portion">
      <h3><?php echo _uc($_e['Free Gift Add Products']); ?></h3>
      <div class="main_portion">
        <div class="hd_no1"><?php echo _uc($_e['Quantity']); ?></div>
        <div class="txt_area">
          <input type="hidden" value="<?php echo $orderId; ?>" name="txt_inv_id"></input>
          <input type="number" value="1" name="txt_inv_pro_qty"></input>
          <?php

          $relatedData = $functions->getProData();

          echo '<select name="signUp[ids][]" class="form-control test" id="example-getting-started1" style="height:300px" multiple="">';


          foreach ($relatedData as $key => $val) {

            $id = $val['p_id'];

            if (!$invoice->productF->hasStock($id)) {
              if ($functions->ibms_setting('no_inventory_product_show_onWeb') == 'yes') {
                continue;
              }
            }




            $name = $functions->getPname($id);

            $name = translateFromSerialize($name);
            echo '<option ' . $select . ' data-img=" " value="' . $id . '" >' . $name . '</option>';
          }

          echo '</select>';

          ?>


          <div class="col_portion_middle">
            <a class="btn btn-info btn-lg" id="create_pro_add"
              style="cursor: pointer"><?php echo _uc($_e['Send Free Gift Email To client']); ?></a>
          </div>
          <!--col_portion_middle-->


        </div>
        <!--txt_area-->
      </div>
      <!--main_portion close-->
    </div>
    <!--col_portion close-->
  </form>
</div>




<div class="table-responsive newProduct col-sm-10">
  <textarea class="form-control" style="height: 100px" name="comment"><?php //echo $data['comment'] 
  ?></textarea>
  <div class="col_portion">
    <h3><?php echo _uc($_e['Free Gift Log List']); ?></h3>
    <div class="main_portion">
      <!--hd_no1 close-->
      <div class="col_portion_bottom">
        <ul id="gift_log_ul">
        </ul>
      </div>
      <!--col_portion_bottom-->
    </div>
    <!--main_portion close-->
  </div>
  <!--col_portion close-->
</div>





<div class="table-responsive newProduct col-sm-10">
  <form action="" method="post" id="email_template_form">
    <input type="hidden" name="senderr_email" value="<?php echo $data['sender_email']; ?>">
    <input type="hidden" name="invoic_id" value="<?php echo $data['invoice_id']; ?>">
    <div class="col_portion">
      <h3><?php echo _uc($_e['Email To Customer']); ?></h3>
      <div class="main_portion">
        <div class="">
          <select style="" id="email_temp" name="email_temp" class="form-control">
            <option disabled selected>Select Email Template</option>
            <?php echo $invoice->productF->getOrderEmailTemplate(); ?>
          </select>
        </div>
        <div class="col_select_portion">
          <input type="text" class="form-control" id="titleHtml" name="titleHtml"
            placeholder="<?php echo _uc($_e['LETTER TITLE FOR ADMIN']); ?>" />
        </div>

        <div class="col_select_portion">
          <input type="text" class="form-control" id="nameHtml" name="nameHtml"
            placeholder="<?php echo _uc($_e['FROM NAME']); ?>" />
        </div>

        <div class="col_select_portion">
          <input type="text" class="form-control" id="mailHtml" name="mailHtml"
            placeholder="<?php echo _uc($_e['FROM MAIL']); ?>" />
        </div>

        <div class="col_select_portion" id="subject">
          <input type="text" class="form-control" id="subjectHtml" name="subjectHtml"
            placeholder="<?php echo _uc($_e['SUBJECT']); ?>" />
        </div>

        <div class="col_select_portion" id="giftCard" style="display: none;">
          <select style="" id="giftCardField" name="giftCard" class="form-control">
            <option disabled selected>Select Gift Card</option>
            <?php echo $invoice->productF->getAvailableGiftCards($data['price_code']); ?>
          </select>
        </div>
        <!--col_select_portion close-->
        <div class="hd_no1"><?php echo _uc($_e['EMAIL MESSAGE']); ?></div>
        <div class="txt_area">
          <textarea name="email_msg" id="msgHtml" class="ckeditor form-control"></textarea>
        </div>
        <!--txt_area-->
        <div class="col_portion_middle">
          <a class="btn btn-info btn-lg" id="send_email"
            style="cursor: pointer"><?php echo _uc($_e['Send Email']); ?></a>
          <script>
            $(".chb").change(function () {
              $(".chb").prop('checked', false);
              $(this).prop('checked', true);
            });
          </script>
        </div>
        <!--col_portion_middle-->
        <div class="col_portion_bottom">
          <ul id="prev_internal"></ul>
        </div>
        <!--col_portion_bottom-->
      </div>
      <!--main_portion close-->
    </div>
    <!--col_portion close-->
  </form>
</div>

<div class="table-responsive newProduct col-sm-10">
  <div class="col_portion">
    <h3><?php echo _uc($_e['Log List']); ?></h3>
    <div class="main_portion">
      <!--hd_no1 close-->
      <div class="col_portion_bottom">
        <ul id="log_ul">
        </ul>
      </div>
      <!--col_portion_bottom-->
    </div>
    <!--main_portion close-->
  </div>
  <!--col_portion close-->
</div>

<script type="text/javascript">
  getComments();
  getLogs();
  getLogs1();

  $('#made_to_measure').click(function (event) {
    email = '<?php echo $data['sender_email']; ?>';
    invoice_id = '<?php echo $data['invoice_id']; ?>';
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=sendMadeMeasure',
      data: {
        email: email,
        inv_id: invoice_id
      }
    }).done(function (data) {
      jAlertifyAlert('<?php $dbF->hardWords("Mail Send Successfully, Kindly check inbox/spam folder", true); ?>');
      getLogs();
      getLogs1();
    });
  });


  $('#create_pro_add').on('click', function () {
    var form = $('#internal_product_form').serialize();
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=create_pro_ajax',
      data: form
    }).done(function (res) {
      jAlertifyAlert('<?php $dbF->hardWords("Free Gift Mail Send Successfully", true); ?>');
    });
  });



  $('#create_comment').on('click', function () {
    var form = $('#internal_comment_form').serialize();
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=create_comment',
      data: form
    }).done(function (res) {
      console.log(res);
      getComments();
    });
  });

  $('#send_email').on('click', function () {
    for (instance in CKEDITOR.instances) {
      CKEDITOR.instances[instance].updateElement();
    }
    var form = $('#email_template_form').serialize();
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=sendTemplateEmail',
      data: form
    }).done(function (res) {
      console.log(res);
      jAlertifyAlert('<?php $dbF->hardWords("Mail Send Successfully, Kindly check inbox/spam folder", true); ?>');
      getLogs();
      getLogs1();
    });
  });

  function getComments() {
    var invoice_id = '<?php echo $orderId; ?>';
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=getComments',
      data: {
        invoice_id: invoice_id
      }
    }).done(function (res) {
      $('#prev_internal').html(res);
    });
  }

  function getLogs() {
    var invoice_id = '<?php echo $data['invoice_id']; ?>';
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=getLogs',
      data: {
        invoice_id: invoice_id
      }
    }).done(function (res) {
      $('#log_ul').html(res);
    });
  }


  function getLogs1() {
    var invoice_id = '<?php echo $data['invoice_id']; ?>';
    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=getLogs1',
      data: {
        invoice_id: invoice_id
      }
    }).done(function (res) {
      $('#gift_log_ul').html(res);
    });
  }


  $('.shipperSelect').on('change', function () {

    if (secure_delete("Do you want to update???")) {
      var uId = $(this).val();
      var oId = $(this).find(':selected').attr('data-id')

      $.ajax({
        type: 'POST',
        url: 'order/order_ajax.php?page=saveRecordSPonline',
        data: {
          uId: uId,
          oId: oId
        }
      }).done(function (res) {

        jAlertifyAlert(res);
      });

    }
  });

  $('#email_temp').on('change', function () {
    var tem_id = $(this).val();
    var host = '<?php echo $_SERVER['HTTP_HOST']; ?>';

    if (tem_id == 257) {
      $('#giftCard').show();
    }

    $.ajax({
      type: 'POST',
      url: 'order/order_ajax.php?page=getTemplateDetail',
      data: {
        temp_id: tem_id
      }
    }).done(function (res) {
      var title = $($.parseHTML(res)).find("#title").text();
      var from_name = $($.parseHTML(res)).find("#from_name").text();
      var from_mail = $($.parseHTML(res)).find("#from_mail").text();
      var subject = $($.parseHTML(res)).find("#subject").text();
      var message = $($.parseHTML(res)).find("#message").html();

      $('#titleHtml').val(title);
      $('#nameHtml').val(from_name);
      $('#mailHtml').val(from_mail + '@' + host);
      $('#subjectHtml').val(subject);
      CKEDITOR.instances.msgHtml.setData(message);
      getLogs();
      getLogs1();
    });
  });

  function copyFunction() {
    var copyText = document.getElementById("extraAmountLink");

    /* Select the text field */
    copyText.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    alert("Copied the text: " + copyText.value);

  }
</script>



<script>
  function formSubmit() {

    let tempHtml = '';
    let productMap = new Map();
    
    $(".stock_deduct_div_item input[type='checkbox']").each((idx, checkbox) => {
        let $checkbox = $(checkbox);
        let productId = $checkbox.attr('data-id');
        let productkey = $checkbox.attr('data-key');
        let unique = `${productId}_${productkey}`;
        // Check if product already exists in the map

        if (!productMap.has(unique)) {
            let $parent = $checkbox.closest('.deduct_products_main');
            let name = $parent.find('.pro_name').html();
            let qty = parseInt($parent.find('.pro_qty').attr('data-qty'), 10);
            let stock = parseInt($parent.find('.pro_stock').attr('data-stock'), 10);
            let image = $parent.find('.pro_name').attr('data-image');
            let proType = $parent.find('.pro_name').attr('data-type');
            let relativeSizes = $parent.find('.pro_name').attr('data-relative-sizes'); // Fetch relativeSizes

            if (isNaN(stock) || stock === 0) {
                stock = 0;
                var newStock = '-';
            } else {
                var newStock = stock - qty;
            }
    
            productMap.set(unique, {
                name,
                qty,
                stock,
                productId,
                productkey,
                newStock,
                image,
                proType,
                relativeSizes, // Add relativeSizes here
                checkboxes: {
                    deduct: false,
                    ship: false,
                },
            });
        }
    
        // Update checkbox statuses
        let type = $checkbox.attr('data-type');
        if (type === 'deduct') productMap.get(unique).checkboxes.deduct = $checkbox.is(':checked');
        if (type === 'ship') productMap.get(unique).checkboxes.ship = $checkbox.is(':checked');
    });
    
    // Generate HTML based on grouped data
    productMap.forEach((product, productId) => {
        // Check if the deduct or ship checkbox is disabled
        let isDeductDisabled = $(".stock_deduct_div_item input[data-type='deduct'][data-id='" + product.productId + "'][data-key='" + product.productkey + "']").prop('disabled');
        let isShipDisabled = $(".stock_deduct_div_item input[data-type='ship'][data-id='" + product.productId + "'][data-key='" + product.productkey + "']").prop('disabled');
    
        let deductCheckbox = product.checkboxes.deduct
            ? `<input type="checkbox" class="checkbox" data-type="deduct" data-key="${product.productkey}" data-relative-sizes="${product.relativeSizes}" data-id="${product.productId}" ${isDeductDisabled ? 'disabled' : ''} checked>`
            : `<input type="checkbox" class="checkbox" data-type="deduct" data-key="${product.productkey}" data-relative-sizes="${product.relativeSizes}" data-id="${product.productId}" ${isDeductDisabled ? 'disabled' : ''}>`;
    
        let shipCheckbox = product.checkboxes.ship
            ? `<input type="checkbox" class="checkbox" data-type="ship" data-key="${product.productkey}" data-id="${product.productId}" ${isShipDisabled ? 'disabled' : ''} checked>`
            : `<input type="checkbox" class="checkbox" data-type="ship" data-key="${product.productkey}" data-id="${product.productId}" ${isShipDisabled ? 'disabled' : ''}>`;

        tempHtml += `
            <div class="items ${product.proType}">
                <div class="checkbox-div">Deduct Stock ${deductCheckbox}</div>
                <div class="checkbox-div">Ship Product ${shipCheckbox}</div>
                <label for="inner_${product.productId}">
                    <div class="left">
                        <img src="${product.image}" alt="${product.name}">
                        <h4 class="title">${product.name}</h4>
                    </div>
                    <div class="right">
                        <div class="current">
                            <h5>STOCK QTY CURRENT</h5>
                            <span>${product.stock}</span>
                        </div>
                        <i class="fa fa-arrow-right"></i>
                        <div class="new">
                            <h5>STOCK QTY NEW</h5>
                            <span>${product.newStock}</span>
                        </div>
                    </div>
                </label>
            </div>`;
    });

    if (tempHtml.length == 0) {
      return true;
    } else {
      if ($('#invStatus').val() == 3) {
        $('#checkoutOfferModal').modal('show');
        $('.check_out_offer_body').html(tempHtml);
        return false;
      } else if ($('#invStatus').val() == 9) {
        $('#checkoutOfferModal').modal('show');
        $('.check_out_offer_body').html(tempHtml);
        return false;
      } else {
        true
      }

    }
  }

  function printShippingLabel(country) {
    if (country == 'se' || country == 'SE') {
      $('#checkoutOfferModalForShippingLabel').modal('show');
    } else if (country == 'ch' || country == 'CH') {
      $('#checkoutOfferModalForShippingLabelCh').modal('show');
    } else {
      $('#checkoutOfferModalForShippingLabelNo').modal('show');
      $('.shipLabel_country_code').val(country);

    }
  }

  $('.shipping_label_dismiss_btnNew').on('click', function (ths) {


    $('#shipping_label_dismiss_btnNew').text("Submitting...");
    let refNo = $('.shipLabel_inv_id').val();
    let pkgCode = $('.shipLabel_basic_code').val();
    let cusName = $('.shipLabel_cus_name').val();
    let cusEmail = $('.shipLabel_cus_email').val();
    let cusAdd = $('.shipLabel_cus_add').val();
    let cusConNo = $('.shipLabel_contact_no_se').val();
    let cusPostCode = $('.shipLabel_post_code').val();
    let cusCity = $('.shipLabel_city').val();
    let pkgWeight = $('.shipLabel_Weight').val();
    $.ajax({
      type: "POST",
      url: "order/shiping_label_ajax.php",
      data: {
        'refNo': refNo,
        'pkgCode': pkgCode,
        'cusName': cusName,
        'cusEmail': cusEmail,
        'cusAdd': cusAdd,
        'cusConNo': cusConNo,
        'cusPostCode': cusPostCode,
        'cusCity': cusCity,
        'pkgWeight': pkgWeight,
      },
      cache: false,
      success: function (result) {
        let res = JSON.parse(result)
        if (res.message) {
          jAlertifyAlert(res.message);
        } else {
          let trackId = res.bookingResponse.idInformation[0].ids[0].value;
          let trackLink = res.labelPrintout[0].printout.uriStoreLabel;
          console.log(res, trackId, trackLink);
          $('#shiping_trackNo').val(trackId)

          $('#shippingLabelLink').html(`Shipping Label : <a href='${trackLink}' target="_blank" >${trackLink}</a>`)
          $('#shipping_label_dismiss_btnNew').text("Submit");
        }
      }
    })
    console.log("jawad");
    return false;

  })

  $('.shipping_label_dismiss_btnNewNo').on('click', function (ths) {
    console.log("No")


    $('.shipping_label_dismiss_btnNewNo').text("Submitting...");
    let refNo = $('.shipLabel_inv_id').val();
    let pkgCode = $('.shipLabel_basic_code').val();
    let cusName = $('.shipLabel_cus_name').val();
    let cusEmail = $('.shipLabel_cus_email').val();
    let cusAdd = $('.shipLabel_cus_add').val();
    let cusConNo = $('.shipLabel_contact_no').val();
    let cusPostCode = $('.shipLabel_post_code').val();
    let cusCity = $('.shipLabel_city').val();
    let pkgWeight = $('.shipLabel_pkg_weight').val();
    let pkgMeasureCode = $('.shipLabel_pkg_mea_code').val();
    let pkgPackagingCode = $('.shipLabel_pkg_code').val();
    let pkgDesc = $('.shipLabel_pkg_desc').val();

    let invOrderNo = $('.shipLabel_order_id').val();
    let invOrderDate = $('.shipLabel_order_date').val();
    let invExpReason = $('.shipLabel_res_for_exp').val();
    let invCurrencyCode = $('.shipLabel_curr_code').val();
    let shipLabel_countryName = $('.shipLabel_countryName').val();
    let product = [];

    $('.order_products_row').each((key, val) => {
      let tempObj = {
        'desc': $(val).find('.proName').val(),
        'qty': $(val).find('.proQty').val(),
        'price': $(val).find('.proPrice').val(),
        'unit': $(val).find('.proUnit').val(),
        'commodityCode': $(val).find('.proCommodityCode').val(),
        'partNo': $(val).find('.proPartNo').val(),
      }
      product.push(tempObj)
    })

    let countryCode = $('.shipLabel_country_code').val();

    let ajaxUrl = "";
    if (countryCode == 'no' || countryCode == 'NO') {
      ajaxUrl = "order/shiping_label_ajax_no.php";
    } else {
      ajaxUrl = "order/shiping_label_ajax_eu.php";
    }

    $.ajax({
      type: "POST",
      url: ajaxUrl,
      data: {
        'refNo': refNo,
        'pkgCode': pkgCode,
        'cusName': cusName,
        'cusEmail': cusEmail,
        'cusAdd': cusAdd,
        'cusConNo': cusConNo,
        'cusPostCode': cusPostCode,
        'cusCity': cusCity,

        'invOrderNo': invOrderNo,
        'invOrderDate': invOrderDate,
        'invExpReason': invExpReason,
        'invCurrencyCode': invCurrencyCode,
        'shipLabel_countryName': shipLabel_countryName,

        'pkgWeight': pkgWeight,
        'pkgMeasureCode': pkgMeasureCode,
        'pkgDesc': pkgDesc,
        'pkgPackagingCode': pkgPackagingCode,

        'products': product

      },
      cache: false,
      success: function (result) {
        console.log("result", result);
        let res = JSON.parse(result)
        if (res.status == 0) {
          jAlertifyAlert(res.message);
        } else {

          $('#shiping_trackNo').val(res.trackId)
          $('#checkoutOfferModalForShippingLabelNo #shippingLabelLink').html(`Shipping Label : <a href='${res.url}' target="_blank" >${res.url}</a>`)
          $('#shipping_label_dismiss_btnNewNo').text("Submit");
        }
      }
    })

  })

  $('.shipping_label_dismiss_btnNewCh').on('click', function (ths) {
    console.log("Ch")


    $('.shipping_label_dismiss_btnNewCh').text("Submitting...");
    let refNo = $('.shipLabel_inv_id_ch').val();
    let pkgCode = $('.shipLabel_basic_code').val();
    let cusName = $('.shipLabel_cus_name').val();
    let cusEmail = $('.shipLabel_cus_email').val();
    let cusAdd = $('.shipLabel_cus_add').val();
    let cusConNo = $('.shipLabel_contact_noch').val();
    let cusPostCode = $('.shipLabel_post_code').val();
    let cusCity = $('.shipLabel_city').val();
    let pkgWeight = $('.shipLabel_pkg_weight').val();
    let pkgMeasureCode = $('.shipLabel_pkg_mea_code').val();
    let pkgPackagingCode = $('.shipLabel_pkg_code').val();
    let pkgDesc = $('.shipLabel_pkg_desc').val();

    let invOrderNo = $('.shipLabel_order_id_ch').val();
    let invOrderDate = $('.shipLabel_order_date').val();
    let invExpReason = $('.shipLabel_res_for_exp').val();
    let invCurrencyCode = $('.shipLabel_curr_code').val();
    let shipLabel_countryName = $('.shipLabel_countryName').val();
    let product = [];

    $('.order_products_row').each((key, val) => {
      let tempObj = {
        'desc': $(val).find('.proName').val(),
        'qty': $(val).find('.proQty').val(),
        'price': $(val).find('.proPrice').val(),
        'unit': $(val).find('.proUnit').val(),
        'commodityCode': $(val).find('.proCommodityCode').val(),
        'partNo': $(val).find('.proPartNo').val(),
      }
      product.push(tempObj)
    })

    let countryCode = $('.shipLabel_country_code').val();

    let ajaxUrl = "";
    ajaxUrl = "order/shiping_label_ajax_ch.php";


    $.ajax({
      type: "POST",
      url: ajaxUrl,
      data: {
        'refNo': refNo,
        'pkgCode': pkgCode,
        'cusName': cusName,
        'cusEmail': cusEmail,
        'cusAdd': cusAdd,
        'cusConNo': cusConNo,
        'cusPostCode': cusPostCode,
        'cusCity': cusCity,

        'invOrderNo': invOrderNo,
        'invOrderDate': invOrderDate,
        'invExpReason': invExpReason,
        'invCurrencyCode': invCurrencyCode,
        'shipLabel_countryName': shipLabel_countryName,

        'pkgWeight': pkgWeight,
        'pkgMeasureCode': pkgMeasureCode,
        'pkgDesc': pkgDesc,
        'pkgPackagingCode': pkgPackagingCode,

        'products': product

      },
      cache: false,
      success: function (result) {
        console.log("result", result);
        let res = JSON.parse(result)
        if (res.status == 0) {
          jAlertifyAlert(res.message);
        } else {

          $('#shiping_trackNo').val(res.trackId)
          $('#checkoutOfferModalForShippingLabelCh #shippingLabelLink').html(`Shipping Label : <a href='${res.url}' target="_blank" >${res.url}</a>`)
          $('#shipping_label_dismiss_btnNewCh').text("Submit");
        }
      }
    })

  })

  $('.shipping_label_dismiss_btnNewEu').on('click', function (ths) {

    console.log("Eu")
    return 0

    $('#shipping_label_dismiss_btnNew').text("Submitting...");
    let refNo = $('.shipLabel_inv_id').val();
    let pkgCode = $('.shipLabel_basic_code').val();
    let cusName = $('.shipLabel_cus_name').val();
    let cusEmail = $('.shipLabel_cus_email').val();
    let cusAdd = $('.shipLabel_cus_add').val();
    let cusConNo = $('.shipLabel_contact_no').val();
    let cusPostCode = $('.shipLabel_post_code').val();
    let cusCity = $('.shipLabel_city').val();
    let pkgWeight = $('.shipLabel_Weight').val();
    console.log(refNo, pkgCode, cusName, cusEmail, cusAdd, cusConNo, cusPostCode, cusCity, pkgWeight)
    $.ajax({
      type: "POST",
      url: "order/shiping_label_ajax.php",
      data: {
        'refNo': refNo,
        'pkgCode': pkgCode,
        'cusName': cusName,
        'cusEmail': cusEmail,
        'cusAdd': cusAdd,
        'cusConNo': cusConNo,
        'cusPostCode': cusPostCode,
        'cusCity': cusCity,
        'pkgWeight': pkgWeight,
      },
      cache: false,
      success: function (result) {
        let res = JSON.parse(result)
        if (res.message) {
          jAlertifyAlert(res.message);
        } else {
          let trackId = res.bookingResponse.idInformation[0].ids[0].value;
          let trackLink = res.labelPrintout[0].printout.uriStoreLabel;
          console.log(res, trackId, trackLink);
          $('#shiping_trackNo').val(trackId)

          $('#shippingLabelLink').html(`Shipping Label : <a href='${trackLink}' target="_blank" >${trackLink}</a>`)
          $('#shipping_label_dismiss_btnNew').text("Submit");
        }
      }
    })
    console.log("jawad");
    return false;

  })

  $('#shipping_label_dismiss_btn').on('click', function () {
    console.log("checkoutOfferModalForShippingLabel")
    $('#checkoutOfferModalForShippingLabel').modal('hide');
  })
 

  $('#checkout_offer_dismiss_btnNew').on('click', function () {
  let relativeSizesArray = [];

  // Clear any existing value in the hidden input
  $("#relativeSizes").val('');

  $('.checkbox').each((item, index) => {
    let id = $(index).attr('data-id');
    let key = $(index).attr('data-key');
    let type = $(index).attr('data-type');
    let relativeSizes = $(index).attr('data-relative-sizes');

    // Check if the checkbox is checked and ensure valid relativeSizes 
    // if ($(index).is(':checked') && relativeSizes && relativeSizes !== "undefined" && relativeSizes !== "") { 
      // Push valid sizes only if not already present
  if ($(index).is(':checked')) {
       if (relativeSizes && relativeSizes !== "undefined" && relativeSizes !== "") {
        relativeSizesArray.push(relativeSizes);
       }
      // Set the corresponding checkbox state based on data-key
      if (type === "ship") {
        $(`.table_check_ship_${id}[data-key="${key}"]`).prop("checked", true);
      } else {
        $(`.table_check_${id}[data-key="${key}"]`).prop("checked", true);
      }
    } else {
      // Uncheck corresponding checkboxes for unchecked items
      if (type === "ship") {
        $(`.table_check_ship_${id}[data-key="${key}"]`).prop("checked", false);
      } else {
        $(`.table_check_${id}[data-key="${key}"]`).prop("checked", false);
      }
    }
  });

    // Update the hidden input with separate entries
    $("#relativeSizes").val(relativeSizesArray.join(','));

    $('#checkoutOfferModal').modal('hide');
    $('#myFormSubmit').submit();
  })
  $('#checkout_offer_dismiss_btn').on('click', function () {
    $('#checkoutOfferModal').modal('hide');
  })
</script>



<?php return ob_get_clean(); ?>