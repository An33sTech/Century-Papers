<?php
require_once("../global.php");

$invoi_idd = $refNo = @$_POST['refNo'];
$pkgCode = @$_POST['pkgCode'];
$cusName = @$_POST['cusName'];
$cusEmail = @$_POST['cusEmail'];
$cusAdd = @$_POST['cusAdd'];
$cusConNo = @$_POST['cusConNo'];
$cusPostCode = @$_POST['cusPostCode'];
$pkgWeight = @$_POST['pkgWeight'];
$cusCity = @$_POST['cusCity'];
$invOrderNo = @$_POST['invOrderNo'];
$cusCountryCode = @$_POST['shipLabel_countryName'];
$pkgDesc = @$_POST['pkgDesc'];
$pkgMeasureCode = @$_POST['pkgMeasureCode'];
$pkgWeight = @$_POST['pkgWeight'];
$pkgPackagingCode = @$_POST['pkgPackagingCode'];
$invOrderDate = @$_POST['invOrderDate'];
$invOrderNo = @$_POST['invOrderNo'];
$invExpReason = @$_POST['invExpReason'];
$invCurrencyCode = @$_POST['invCurrencyCode'];
$productArr = @$_POST['products'];

$date = date("c");
$consignor = array();
$consignee = array();

$shipper_name = "Sharkspeed Online Solutions";
$shipper_attentionName = "Sharkspeed Online Solutions";
$shipper_shipperNumber = "30VR66";
$shipper_contactNo = "010-4103120";
$shipper_streets = "Lextorpsv채gen.985";
$shipper_postalCode = "46165";
$shipper_CountryCode = "SE";
$shipper_city = "Trollh채ttan";
$shipper_smsNo = "010-4103120";
$shipper_emailAddress = "kontor@sharkspeed.com";
$shipper_smsNo = "010-4103120";
$shipper_contactName = "Sharkspeed Online Solutions";

$soldTo = [
    "Name" => "$cusName",
    "AttentionName" => "$cusName",
    "Address" => [
        "AddressLine" => [
            "$cusAdd"
        ],
        "City" => "$cusCity",
        "PostalCode" => "$cusPostCode",
        "CountryCode" => "$cusCountryCode"
    ]
];

$products = [];

foreach ($productArr as $key => $val) {
    list($unit, $unitDesc) = explode('-', $val['unit']);
    $products[] = [
        "Description" => "Product",
        "Unit" => [
            "Number" => "$val[qty]",
            "Value" => "$val[price]",
            "UnitOfMeasurement" => [
                "Code" => "$unit",
                "Description" => "$unitDesc"
            ]
        ],
        "OriginCountryCode" => "SE",
        "CommodityCode" => "$val[commodityCode]",
        "PartNumber" => "$val[partNo]"
    ];
}

$payload = [
    "ShipmentRequest" => [
        "Request" => [
            "SubVersion" => "2205",
            "RequestOption" => "nonvalidate",
            "TransactionReference" => [
                "CustomerContext" => ""
            ]
        ],
        "Shipment" => [
            "Description" => "1206 PTR",
            "Shipper" => [
                "Name" => "$shipper_name",
                "AttentionName" => "$shipper_attentionName",
                "Phone" => [
                    "Number" => "$shipper_contactNo"
                ],
                "ShipperNumber" => "$shipper_shipperNumber",
                "Address" => [
                    "AddressLine" => "$shipper_streets",
                    "City" => "$shipper_city",
                    "PostalCode" => "$shipper_postalCode",
                    "CountryCode" => "$shipper_CountryCode"
                ]
            ],
            "ShipTo" => [
                "Name" => "$cusName",
                "AttentionName" => "$cusName",
                "Phone" => [
                    "Number" => "$cusConNo"
                ],
                "EMailAddress" => "$cusEmail",
                "Address" => [
                    "AddressLine" => "$cusAdd",
                    "City" => "$cusCity",
                    "PostalCode" => "$cusPostCode",
                    "CountryCode" => "$cusCountryCode",
                    "StateProvinceCode" => ""
                ]
            ],
            "PaymentInformation" => [
                "ShipmentCharge" => [
                    [
                        "Type" => "01",
                        "BillShipper" => [
                            "AccountNumber" => "$shipper_shipperNumber"
                        ]
                    ]
                ]
            ],
            "Service" => [
                "Code" => "11",
                "Description" => "UPS Standard"
            ],
            "Package" => [
                [
                    "Description" => "$pkgDesc",
                    "Packaging" => [
                        "Code" => "02"
                    ],
                    "PackageWeight" => [
                        "UnitOfMeasurement" => [
                            "Code" => "$pkgMeasureCode"
                        ],
                        "Weight" => "$pkgWeight"
                    ]
                ]
            ],
            "ShipmentRatingOptions" => [
                "NegotiatedRatesIndicator" => ""
            ],
            "ShipmentServiceOptions" => [
                "InternationalForms" => [
                    "FormType" => "01",
                    "InvoiceNumber" => "$invoi_idd",
                    "InvoiceDate" => "$invOrderDate",
                    "PurchaseOrderNumber" => "$invOrderNo",
                    "TermsOfShipment" => "",
                    "ReasonForExport" => "$invExpReason",
                    "FreightCharges" => [
                        "MonetaryValue" => "20"
                    ],
                    "CurrencyCode" => "$invCurrencyCode",
                    "Contacts" => [
                        "SoldTo" => $soldTo
                    ],
                    "Product" => $products
                ]
            ]
        ],
        "LabelSpecification" => [
            "LabelImageFormat" => [
                "Code" => "PNG"
            ]
        ]
    ]
];

const version = "v1";

/**
 * Requires libcurl
 */

$curl = curl_init();
$payloadForToken = "grant_type=client_credentials";

curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/x-www-form-urlencoded",
        "x-merchant-id: 30VR66",
        "Authorization: Basic " . base64_encode("u7BMxHrIxwFyteyBP95N2rKI6iBI2V5A9UlqusaWTdONZZAj:1t6ysFGGFGBBSxoxcUS40OMReQ23CGEmXj3Oop2eIM2VuNJ5wgnlxRbYQ9aTNhMm")
    ],
    CURLOPT_POSTFIELDS => $payloadForToken,
    CURLOPT_URL => "https://onlinetools.ups.com/security/v1/oauth/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
]);

$response = curl_exec($curl);
$error = curl_error($curl);


if ($error) {
    echo "cURL Error #:" . $error;
} else {

    $res = json_decode($response, true);
    $access_token = $res['access_token'];


    $query = array(
        "additionaladdressvalidation" => "u7BMxHrIxwFyteyBP95N2rKI6iBI2V5A9UlqusaWTdONZZAj"
    );


    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $access_token",
            "Content-Type: application/json",
            "transId: string",
            "transactionSrc: testing"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_URL => "https://onlinetools.ups.com/api/shipments/" . version . "/ship?" . http_build_query($query),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    $responseReturn = [];
    $responseReturn['status'] = '0';
    $res = json_decode($response, true);

    if ($error) {
        echo "cURL Error #:" . $error;
    } else if (isset($res['response']['errors'][0]['message'])) {
        $responseReturn['message'] = $res['response']['errors'][0]['message'];
    } else {
        $lastId = 0;

        if (isset($res["ShipmentResponse"]["ShipmentResults"]["PackageResults"]["ShippingLabel"]["GraphicImage"])) {
            $labelcode = $res["ShipmentResponse"]["ShipmentResults"]["PackageResults"]["ShippingLabel"]["GraphicImage"];
            $invoiveLabelCode = $res['ShipmentResponse']['ShipmentResults']['Form']['Image']['GraphicImage'];
            $trackId = $res["ShipmentResponse"]['ShipmentResults']['PackageResults']['TrackingNumber'];

            $sql = "INSERT INTO `order_invoice_lables` (`invoice_id`, `transactionReference`, `imageData`, `invoiceImageData`) VALUES (?, ?, ?, ?)";
            $dbF->setRow($sql, [$invoi_idd, $trackId, $labelcode, $invoiveLabelCode]);
            $lastId = $dbF->rowLastId;

            if ($lastId > 0) {
                $printLabelURL = WEB_URL . '/print_invoice_label.php?ref=' . $trackId . '&inv=' . $invoi_idd;
                $responseReturn['status'] = 1;
                $responseReturn['url'] = $printLabelURL;
                $responseReturn['trackId'] = $trackId;

                // track id to order table;
                $sql = "UPDATE `order_invoice` SET `trackNo` = ? WHERE `invoice_id` = ?";
                $dbF->setRow($sql, [$trackId, $invoi_idd]);

                $log_des1 = "Order Shipping Track Number Changed To $trackId";
                $functions->orderlog('Order Shipping Track Number Changed', 'Invoice', $invoi_idd, $log_des1);

                $log_des = "Shipping Label Generated : $printLabelURL";
                $functions->orderlog('Invoice Status Updated', 'Invoice', $invoi_idd, $log_des);
            }
            
        }
    }
    echo json_encode($responseReturn);
}

curl_close($curl);
