<?php
require_once("../global.php");

$url = 'https://api2.postnord.com/rest/shipment/v3/edi/labels/pdf?apikey=9f82b3f9782dd6118f21419f1731ba9b&generateQrcodeImage=false&qrCodeScale=9&qrCodeFormat=PNG&emailQRcodeTo=someone%40example.com&smsQRcodeTo=%2B46707219595&locale=sv&paperSize=LABEL&rotate=0&multiPDF=false&labelType=standard&pnInfoText=false&printSecurityDeclaration=false&labelsPerPage=100&page=1&processOffline=false&storeLabel=true&pageHorizontalAlign=JUSTIFY&pageVerticalAlign=JUSTIFY';

$invoi_idd = $refNo = @$_POST['refNo'];
$pkgCode = @$_POST['pkgCode'];
$cusName = @$_POST['cusName'];
$cusEmail = @$_POST['cusEmail'];
$cusAdd = @$_POST['cusAdd'];
$cusConNo = @$_POST['cusConNo'];
$cusPostCode = @$_POST['cusPostCode'];
$pkgWeight = @$_POST['pkgWeight'];
$cusCity = @$_POST['cusCity'];

$date = date("c");
$consignor = array();
$consignee = array();

if ($pkgCode == 24) {
    $consignee['name'] = "Sharkspeed Online Solutions";
    $consignee['streets'] = "Lextorpsvägen.985";
    $consignee['postalCode'] = "46165";
    $consignee['city'] = "Trollhättan";
    $consignee['emailAddress'] = "kontor@sharkspeed.com";
    $consignee['smsNo'] = "010-4103120";
    $consignee['contactName'] = "Sharkspeed Online Solutions";
    $consignor['name'] = $cusName;
    $consignor['streets'] = $cusAdd;
    $consignor['postalCode'] = $cusPostCode;
    $consignor['city'] = $cusCity;
    $consignor['contactName'] = $cusName;
    $consignor['emailAddress'] = $cusEmail;
    $consignor['smsNo'] = $cusConNo;
} else {
    $consignor['name'] = "Sharkspeed Online Solutions";
    $consignor['streets'] = "Lextorpsvägen.985";
    $consignor['postalCode'] = "46165";
    $consignor['city'] = "Trollhättan";
    $consignor['emailAddress'] = "kontor@sharkspeed.com";
    $consignor['smsNo'] = "010-4103120";
    $consignor['contactName'] = "Sharkspeed Online Solutions";
    $consignee['name'] = $cusName;
    $consignee['streets'] = $cusAdd;
    $consignee['postalCode'] = $cusPostCode;
    $consignee['city'] = $cusCity;
    $consignee['contactName'] = $cusName;
    $consignee['emailAddress'] = $cusEmail;
    $consignee['smsNo'] = $cusConNo;
}

$data = array(
    "messageDate" => $date,
    "messageFunction" => "Instruction",
    "messageId" => "20201126_2",
    "application" => array(
        "applicationId" => 1954,
        "name" => "Sharkspeed",
        "version" => "1.0"
    ),
    "updateIndicator" => "Original",
    "shipment" => array(
        array(
            "shipmentIdentification" => array(
                "shipmentId" => "0"
            ),
            "dateAndTimes" => array(
                "loadingDate" => $date
            ),
            "service" => array(
                "basicServiceCode" => "$pkgCode",
                "additionalServiceCode" => array("A3")
            ),
            "freeText" => array(),
            "numberOfPackages" => array(
                "value" => 1
            ),
            "totalGrossWeight" => array(
                "value" => $pkgWeight,
                "unit" => "KGM"
            ),
            "references" => array(
                array(
                    "referenceNo" => "$refNo",
                    "referenceType" => "CU"
                )
            ),
            "parties" => array(
                "consignor" => array(
                    "issuerCode" => "Z12",
                    "partyIdentification" => array(
                        "partyId" => "20467735",
                        "partyIdType" => "160"
                    ),
                    "party" => array(
                        "nameIdentification" => array(
                            "name" => "$consignor[name]"
                        ),
                        "address" => array(
                            "streets" => array("$consignor[streets]"),
                            "postalCode" => "$consignor[postalCode]",
                            "city" => "$consignor[city]",
                            "countryCode" => "SE"
                        ),
                        "contact" => array(
                            "contactName" => "$consignor[contactName]",
                            "emailAddress" => "$consignor[emailAddress]",
                            "smsNo" => "$consignor[smsNo]"
                        )
                    )
                ),
                "consignee" => array(
                    "party" => array(
                        "nameIdentification" => array(
                            "name" => "$consignee[name]"
                        ),
                        "address" => array(
                            "streets" => array($consignee['streets']),
                            "postalCode" => "$consignee[postalCode]",
                            "city" => "$consignee[city]",
                            "countryCode" => "SE"
                        ),
                        "contact" => array(
                            "contactName" => "$consignee[contactName]",
                            "emailAddress" => "$consignee[emailAddress]",
                            "smsNo" => "$consignee[smsNo]"
                        )
                    )
                )
            ),
            "goodsItem" => array(
                array(
                    "packageTypeCode" => "PC",
                    "items" => array(
                        array(
                            "itemIdentification" => array(
                                "itemId" => "0",
                                "itemIdType" => "SSCC"
                            ),
                            "grossWeight" => array(
                                "value" => $pkgWeight,
                                "unit" => "KGM"
                            )
                        )
                    )
                )
            )
        )
    )
);

$jsonData = json_encode($data);

$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'accept: application/json'
));

// Execute cURL session and fetch response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

$data = json_decode($response, true);
if (isset($data['bookingResponse']['idInformation'])) {
    //track id to order table;
    $trackId = $data['bookingResponse']['idInformation'][0]['ids'][0]['value'];

    $sql = "UPDATE `order_invoice` SET `trackNo` = ? WHERE `invoice_id` = ?";
    $dbF->setRow($sql, [$trackId, $invoi_idd]);

    $log_des1 = "Order Shipping Track Number Changed To $trackId";
    $functions->orderlog('Order Shipping Track Number Changed', 'Invoice', $invoi_idd, $log_des1);

    $shipingLabel = $data['labelPrintout'][0]['printout']['uriStoreLabel'];
    $log_des = "Shipping Label Generated : $shipingLabel";
    $functions->orderlog('Invoice Status Updated', 'Invoice', $invoi_idd, $log_des);
}
// Display the response
echo $response;
