<?php
############ Export table into CSV ############
require_once("../global.php");
$_d = ","; //$delimiter
####### CSV file Headings, for excel edit mode.
$file_heading = "id{$_d}MainLink{$_d}RefLink{$_d}Sluglink{$_d}Codelink{$_d}Language";
$file_heading .= "\n";
$output = $file_heading;
####### get data from DB
$sql    = "SELECT * FROM `seo` where slug !='' and slug !='/' ORDER BY `seo`.`id` DESC"; // ORDER BY qty_pk DESC
$data   = $dbF->getRows($sql);
foreach ($data as $val) {
$id         = $val['id'];
$lang = $functions->IbmsLanguages();
for ($i = 0; $i < sizeof($lang); $i++) {
// var_dump($lang);
// die;
$j = $lang[$i];

if ($j == "Swedish") {
    $domain = "se";
}

if ($j == "Norwegian") {
    $domain = "no";
}

if ($j == "Danish") {
    $domain = "dk";
}

if ($j == "Finnish") {
    $domain = "fl";
}

if ($j == "English") {
    $domain = "net";
}

$heading = unserialize($val['title']);
$ref_id = ($val['ref_id']);
$ref_id = "https://www.sharkspeed.".$domain."/".$ref_id;
$heading = @$heading[$j];
$headings = "https://www.sharkspeed.".$domain.$val['pageLink'];
// $slug = trim($val['slug']);
// $slug = "'".$slug."'";
$slug = "https://www.sharkspeed.".$domain."/".$val['slug'];

if($val['type'] == "product"){


$str1 = explode("-", $val['ref_id']);

// $exp1 =$str1[0];
$exp2 =$str1[1];



$clink = "https://www.sharkspeed.".$domain."/detail.php?pId=".$exp2;

}else{

$clink = "";

}


####### CSV single row...
$output .= "{$id}{$_d}{$headings}{$_d}{$ref_id}{$_d}{$slug}{$_d}{$clink}{$_d}{$j}";
$output .= "\n";
}
}
####### Download csv File...
$filename = "csv_links_n.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);
echo $output;
exit;
?>