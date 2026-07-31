<?php 

require_once("classes/statistic.class.php");
global $dbF;


$date_start = '2016-10-07 00:00:00';
$date_end   = '2016-10-07 23:23:59';


$sql = "    SELECT shippingCountry, COUNT(order_invoice_pk) as total FROM `order_invoice` 
            WHERE dateTime BETWEEN ? AND ?
            GROUP BY shippingCountry  ";

$sql = "    SELECT * FROM `order_invoice` 
            WHERE dateTime BETWEEN ? AND ? ";      



$time = 'Europe/Stockholm';
date_default_timezone_set($time);      

// $total_order_rows = $dbF->getRows($sql,array($date_start,$date_end),false);

echo "<pre>";

$sql = "SELECT s . * 
FROM  `statistics` s
WHERE s.type =  'total_orders_daily'
AND s.date
BETWEEN  '2016-10-01'
AND  '2016-10-31'";

$total_order_rows = $dbF->getRows($sql,NULL,false);
var_dump($dbF->rowCount);

$sql = "SELECT * 
FROM  `order_invoice` 
WHERE DATETIME
BETWEEN  '2016-10-01 00:00:00'
AND  '2016-10-31 23:23:59'";

$total_order_rows = $dbF->getRows($sql,NULL,false);
var_dump($dbF->rowCount);

var_dump(date_default_timezone_get());
$gmt =  date('P');
var_dump($gmt);
// var_dump($total_order_rows);
echo "</pre>";
