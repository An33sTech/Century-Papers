<?php
ob_start();
require_once("classes/order.php");
$order = new order();

$order->addNewOrder();
$functions->sessionMsg();

if (isset($_GET['deleteIncomplete'])) {
    $order->deleteOrders('deleteIncomplete');
}

if ($functions->developer_setting('product_Scale') == '0') {
    echo "<style>.allowProductScale{display:none;}</style>";
}

if ($functions->developer_setting('product_color') == '0') {
    echo "<style>.allowProductColor{display:none;}</style>";
}

$functions->includeAdminFile("product_management/classes/currency.class.php");
$c_currency = new currency_management();
$currency_data = $c_currency->getList(); // get currency list

foreach ($currency_data as $val) {
    $cur_id = $val['cur_id'];
    $cur_symbol = md5($val['cur_symbol']);
    echo '<input type="hidden" class="currIds" value="' . $cur_symbol . '" />';
}

?>

<h4 class="sub_heading"><?php echo _uc($_e['Order Create/View']); ?></h4>

<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['InProcess Invoices']); ?></a>
    </li>
    <li class="all_orders"><a href="#manullayCreated" role="tab"
            data-toggle="tab"><?php echo _uc($_e['Manually Created Orders']); ?></a></li>
    <li class="all_orders"><a href="#allOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['All Orders']); ?></a>
    </li>
    <li class="add_new_order"><a href="#newOrder" role="tab"
            data-toggle="tab"><?php echo _uc($_e['Add New Order']); ?></a></li>
</ul>

<!-- Tab panes -->
<script>
    $(document).ready(function() {
        $(".nav-tabs li").click(function() {
            if ($(this).hasClass("add_new_order")) {
                $("#sortByDate").hide();
            } else {
                $("#sortByDate").show();
            }
        });
    });
</script>
<div class="tab-content">
    <?php $functions->dataTableDateRangeNew(); ?>
    <?php $functions->countrySelectOptionView(); ?>

    <?php
    function print_pricing_div($type)
    {
        global $currency_data;

        $pricing_div = '';
        foreach ($currency_data as $val) {
            $cur_id = $val['cur_id'];
            $cur_country = $val['cur_id'];
            $cur_symbol = md5($val['cur_symbol']);
            $symbol = ($val['cur_symbol']);
            $pricing_div .= "<div class='invoice_price_div'><span id='countMe_{$type}_$cur_id' data-id='$cur_id' data-symbol='$symbol' class='printMe_{$type}_$cur_symbol count_invoice'>0</span> $symbol</div>";
        }
        return $pricing_div;
    }

    ?>

    <div class="tab-pane fade in active container-fluid" id="home">
        <div class="heading_invoice">
            <h2 class="tab_heading"><?php echo _uc($_e['InProcess Invoices']); ?></h2>

            <div class="countMeDiv">
                <?php echo $_e['Selected SubTotal'] ?> :

                <?php echo print_pricing_div('invoices'); ?>

            </div>
        </div>
        <?php $order->invoiceList('invoices'); ?>
        <div class="container-fluid" style="margin-top: 10px;">
            <button class="btn btn-primary" onclick="printSelectedInvoice(this);">
                <i class='glyphicon glyphicon-file print'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                <?php echo _uc($_e['Print Out All Selected Order']); ?>
            </button>
            <button class="btn btn-primary" onclick="selectVisible(this);">
                <i class='glyphicon glyphicon-file print'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                <?php echo _uc($_e['Select Visible Order']); ?>
            </button>
        </div>
    </div>

    <div class="tab-pane fade container-fluid" id="allOrder">
        <div class="heading_invoice">
            <h2 class="tab_heading"><?php echo _uc($_e['All Orders']); ?></h2>

            <div class="countMeDiv">
                <?php echo $_e['Selected SubTotal'] ?> :

                <?php echo print_pricing_div('all'); ?>

            </div>
        </div>

        <?php $order->invoiceListFORallandComplete('all'); ?>
        <div class="container-fluid" style="margin-top: 10px;">
            <button class="btn btn-primary" onclick="printSelectedInvoice(this);">
                <i class='glyphicon glyphicon-file print'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                <?php echo _uc($_e['Print Out All Selected Order']); ?>
            </button>
        </div>
    </div>

    <div class="tab-pane fade container-fluid" id="manullayCreated">
        <div class="heading_invoice">
            <h2 class="tab_heading"><?php echo _uc($_e['Manually Created Orders']); ?></h2>

            <div class="countMeDiv">
                <?php echo $_e['Selected SubTotal'] ?> :

                <?php echo print_pricing_div('all'); ?>

            </div>
        </div>

        <?php $order->invoiceListFORallandComplete('manullayCreated'); ?>
        <div class="container-fluid" style="margin-top: 10px;">
            <button class="btn btn-primary" onclick="printSelectedInvoice(this);">
                <i class='glyphicon glyphicon-file print'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                <?php echo _uc($_e['Print Out All Selected Order']); ?>
            </button>
        </div>
    </div>


    <div class="tab-pane fade container-fluid" id="newOrder">
        <h2 class="tab_heading"><?php echo _uc($_e['Add New Order']); ?></h2>
        <?php $order->newOrderForm(); ?>
    </div>
</div> <!-- tab-content div end-->

<?php $functions->dialogCommon('dialog', 'Order View'); ?>

<style>
    .btn-dark,
    .btn-dark:hover {
        background: #000;
        color: #fff;
        font-weight: 600;
    }

    .shippersInfo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 0.625rem;
    }

    .shippersInfo span {
        color: #3e8f3e;
        font-weight: bold;
    }

    .dataTables_processing {
        position: fixed;
        top: 50%;
        left: 50%;
        border: none;
        background: none;
    }

    .heading_invoice {
        position: relative;
    }

    .small_btn {
        position: absolute;
        right: 0;
        top: -30px;
    }

    @media (max-width: 992px) {

        .small_btn {
            position: static;
            display: block;
            clear: both;
        }

    }
</style>

<script src="order/js/order.php"></script>
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

    function printShippingLabel(country, orderId) {
        $.ajax({
            type: "POST",
            url: "order/order_ajax.php?page=invoiceDetailAjax",
            data: {
                country: country,
                orderId: orderId
            },
            success: function(res) {
                $(res).modal('show');
                if (country !== 'se' && country !== 'SE' && country !== 'ch' && country !== 'CH') {

                   $(res).modal('show').on('shown.bs.modal', function () {
                        $('.shipLabel_country_code').val(country);
                    });
                }
            },
            error: function(xhr, status, errorThrown) {
                console.error(xhr.responseText);
            }
        });
    }
    $(document).on('click', '.shipping_label_dismiss_btnNew', function(e) {
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
            success: function(result) {
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
        
        return false;

    });
    
    $(document).on('click', '.shipping_label_dismiss_btnNewNo', function(e) {
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
            success: function(result) {
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

    });
    
    $(document).on('click', '.shipping_label_dismiss_btnNewCh', function(e) {
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

        let countryCode = $('.shipLabel_country_code_ch').val();

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
            success: function(result) {
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

    });

    $(document).on('click', '#shipping_label_dismiss_btn', function(e) {
        $('#checkoutOfferModalForShippingLabel').modal('hide');
    })

    let toggleState = false;

    function selectVisible(ths) {
        toggleState = !toggleState;
        $(".order_check").each(function() {
            $(this).prop('checked', toggleState);
        });
    }
    
    $(document).on("click", ".showShippers", function() {
        $(this).next(".shipperSelect").fadeToggle("slow");
    });
    
    $(document).on("change", ".shipperSelect", function() {
        if (secure_delete("Do you want to update???")) {
            var uId = $(this).val();
            var oId = $(this).find(':selected').attr('data-id');

            $.ajax({
                type: 'POST',
                url: 'order/order_ajax.php?page=saveRecordSPonline',
                data: {
                    uId: uId,
                    oId: oId
                }
            }).done(function(res) {
                jAlertifyAlert(res);
            });
        }
    });


    function printSelectedInvoice(ths) {
        var checkedValues = '';
        var i = true;
        btn = $(ths);

        btn.addClass('disabled');
        btn.children('.print').hide();
        btn.children('.waiting').show();


        $('.dataTables_wrapper tbody input:checkbox:checked, .dTable_ajax tbody input:checkbox:checked').each(function() {
            if (i) {
                checkedValues = $(this).val();
                i = false;
            } else {
                checkedValues += ", " + $(this).val();
            }
        });

        if (checkedValues == '') {
            alert("Please select an order.");
            btn.removeClass('disabled');
            btn.children('.print').show();
            btn.children('.waiting').hide();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "product_management/product_ajax.php?page=printSelectedInvoice",
            data: {
                id: checkedValues
            }
        }).done(function(data) {
            if (data.includes('invoicePrint')) {
                window.open(data, '_blank').focus();
                btn.removeClass('disabled');
                btn.children('.print').show();
                btn.children('.waiting').hide();
            } else {
                btn.removeClass('disabled');
                btn.children('.print').show();
                btn.children('.waiting').hide();
                jAlertifyAlert('<?php echo _js($_e['Print Fail Please Try Again.']); ?>');
            }
        });
    }

    $(function() {
        dateJqueryUi();
        minMaxDateFilter();

        $(document).on('keydown', '.dataTables_filter input', function(event) {
            orderPrice();
        });

        $("#DataTables_Table_0_length_select,#DataTables_Table_1_length_select,#DataTables_Table_2_length_select,#min,#max").change(function() {
            orderPrice();
        });

        setTimeout(function() {
            orderPrice();
        }, 100);

    });

    function orderPrice() {
        setTimeout(function() {
            countOrderPrice('invoices');
            countOrderPrice('cancel');
            countOrderPrice('complete');
            countOrderPrice('all');
            countOrderPrice('incomplete');
        }, 500);
    }
</script>

<?php return ob_get_clean(); ?>