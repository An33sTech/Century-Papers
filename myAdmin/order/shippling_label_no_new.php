<?php
require_once("../global.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$cusName =  @$_POST['cusName'];
$cusConNo =  @$_POST['cusConNo'];
$cusEmail =  @$_POST['cusEmail'];
$cusAdd =  @$_POST['cusAdd'];
$cusCity =  @$_POST['cusCity'];
$cusPostCode =  @$_POST['cusPostCode'];
$cusCityCode =  @$_POST['cusPostCode'];
$cusProviceCode =  @$_POST['cusProviceCode'];


$invoi_idd=$refNo =  @$_POST['refNo'];
$pkgCode =  @$_POST['pkgCode'];
$pkgWeight =  @$_POST['pkgWeight'];
$date = date("c");

$invoi_CurrencyCode = $_POST['inv_cur_code'];
$invoi_FreightCharges = $_POST['inv_frieght_charges'];
$invoi_exportReason  = $_POST['inv_export_reason'];
$invoi_orderNo = $_POST['inv_order_no'];
$invoi_date = $_POST['inv_date'];





$shipper_name = "Sharkspeed Online Solutions";
$shipper_attentionName = "Ali Herani";
$shipper_shipperNumber = "30VR66";
$shipper_contactNo = "010-4103120";
$shipper_streets = "Lextorpsvägen.985";
$shipper_postalCode = "46165";
$shipper_CountryCode = "SE";
$shipper_city = "Trollhättan";
$shipper_smsNo = "010-4103120";
$shipper_emailAddress = "kontor@sharkspeed.com";
$shipper_smsNo = "010-4103120";
$shipper_contactName = "Sharkspeed Online Solutions";
$shipper_acc_name = '30VR66';


$shipmentDesc = "";
$shipmentCode = "65";
$shipmentCodeDesc = "UPS Saver";


$productsArr = [];


$consignor = array();
$consignee = array();


foreach($products as $key => $val){
                  $productsArr[$key] =  [
                            "Description" => "$val[desc]",
                            "CommodityCode" => "$val[commodityCode]",
                            "PartNumber" => "$val[commodityCode]",
                            "Unit" => [
                                "Number" => "$val[qty]",
                                "Value" => "$val[price]",
                                "UnitOfMeasurement" => [
                                    "Code" => "$val[unitMeasure]",
                                    "Description" => "$val[unitMeasureDesc]"
                                ]
                            ]
                        ];
                   
}

$pkgInfo =[
                [
                    "Description" => "$package[desc]",
                    "Packaging" => [
                        "Code" => "$package[descCode]"
                    ],
                    "PackageWeight" => [
                        "UnitOfMeasurement" => [
                            "Code" => "$package[unit]"
                        ],
                        "Weight" => "$package[unitVal]"
                    ]
                ]
                
            ];


// [
//                             "Description" => "test",
//                             "CommodityCode" => "6251.33",
//                             "PartNumber" => "123123",
//                             "OriginCountryCode" => "NO",
//                             "Unit" => [
//                                 "Number" => "1",
//                                 "Value" => "200",
//                                 "UnitOfMeasurement" => [
//                                     "Code" => "PCS",
//                                     "Description" => "Pieces"
//                                 ]
//                             ]
//                         ]
                        


    
$payload = [
    "ShipmentRequest" => [
        "Shipment" => [
            "Description" => "$shipmentDesc",
            "Shipper" => [
                "Name" => "$shipper_name",
                "AttentionName" => "$shipper_attentionName",
                "Phone" => [
                    "Number" => "$shipper_contactNo"
                ],
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
                    "CountryCode" => "$cusCityCode",
                    "StateProvinceCode" => $cusProviceCode
                ] 
            ],
            "PaymentInformation" => [
                "ShipmentCharge" => [
                    "Type" => "01",
                    "BillShipper" => [
                        "AccountNumber" => "$shipper_acc_name"
                    ]
                ]
            ], 
            "Service" => [
                "Code" => "$shipmentCode",
                "Description" => "$shipmentCodeDesc"
            ],
            "NumOfPiecesInShipment" => "",
            "Package" => $pkgInfo,
            "ShipmentRatingOptions" => [
                "NegotiatedRatesIndicator" => ""
            ],
            "ShipmentServiceOptions" => [
                "InternationalForms" => [
                    "FormType" => "01",
                    "InvoiceNumber" => "$invoi_idd",
                    "InvoiceDate" => "$invoi_date",
                    "PurchaseOrderNumber" => "$invoi_orderNo",
                    "TermsOfShipment" => "",
                    "ReasonForExport" => "$invoi_exportReason",
                    "FreightCharges" => [
                        "MonetaryValue" => "$invoi_FreightCharges"
                    ],
                    "CurrencyCode" => "$invoi_CurrencyCode",
                    "Contacts" => [
                        "SoldTo" => [
                            "Name" => "$cusName",
                            "AttentionName" => "$cusName",
                            "Address" => [
                                "AddressLine" => "$cusAdd",
                                "City" => "$cusCity",
                                "PostalCode" => "$cusPostCode",
                                "CountryCode" => "$cusCityCode",
                                "StateProvinceCode" => $cusProviceCode
                            ]
                        ]
                    ],
                    "Product" => $productsArr
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
  CURLOPT_URL => "https://wwwcie.ups.com/security/v1/oauth/token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
]);

$response = curl_exec($curl);
$error = curl_error($curl);


if ($error) {
  echo "cURL Error #:" . $error;
} else {
    
  $res =  json_decode($response, true);
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
          CURLOPT_URL => "https://wwwcie.ups.com/api/shipments/" . version . "/ship?" . http_build_query($query),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    
    
    if ($error) {
      echo "cURL Error #:" . $error;
    } else {
      echo $response;
    }
}

curl_close($curl);



?>