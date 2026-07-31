<?php

ob_start();

$product = new product();

$lang = $functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}




function underMenu3Option($id,$defaultLang){

        global $dbF;
    global $functions;
$sql = "SELECT * FROM categories WHERE  under = '$id' ORDER BY sort";
$data = $dbF->getRows($sql);
$temp = '';
if($dbF->rowCount){
foreach ($data as $val){
$heading = translateFromSerialize($val['name']);

    $id    = @$_GET['recommendsId'];

$sql = "SELECT cat_id FROM sp_recommends WHERE  id = '$id' ORDER BY sort";
$datas = $dbF->getRow($sql);
// $heading = $heading[$defaultLang];
// $temp .='<option value="'.$val['id'].'"> -- -- '.$heading.'</option>';

$temp .='<option value="'.$val['id'].'"';



if (@$val['id'] == @$datas['cat_id']) {
 $temp .="selected";
}

$temp .='> -- -- '.$heading.'</option>';



}
return $temp;
}else{
return false;
}
}



function underMenu2Option($id,$defaultLang){

        global $dbF;
    global $functions;
$sql = "SELECT * FROM categories WHERE  under = '$id' ORDER BY sort";
$data = $dbF->getRows($sql);
$temp = '';
if($dbF->rowCount){
foreach ($data as $val){

    $id    = @$_GET['recommendsId'];
// $sql   = " SELECT * FROM `sp_recommends` b
// LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
// WHERE b.id = ?
// ORDER BY b.ID DESC ";
// $data  = $this->dbF->getRow($sql,array($id));
// $product_id = $data['prodet_id'];



$sql = "SELECT cat_id FROM sp_recommends WHERE  id = '$id' ORDER BY sort";
$datas = $dbF->getRow($sql);



$heading = translateFromSerialize($val['name']);
// $heading = $heading[$defaultLang];
$temp .='<option value="'.$val['id'].'"';

 // $temp .=$id;

if (@$val['id'] == @$datas['cat_id']) {
 $temp .="selected";
}

$temp .='> -- '.$heading.'</option>';
$menu3 = underMenu3Option($val['id'],$defaultLang);



if($menu3!=false){
$temp .= $menu3;
}



else{
continue;
}
}
return $temp;
}else{
return false;
}
}


function underMenuOption(){
    global $dbF;
    global $functions;
// $type = '';
// if(isset($_GET['type'])) {
// $type = "AND type = '$_GET[type]' ";
// }

$sql  = "SELECT * FROM categories WHERE  under = '0' ORDER BY sort";
$data = $dbF->getRows($sql);
$opt  = '';
$defaultLang = $functions->AdminDefaultLanguage();

foreach ($data as $val){
$menu2       = underMenu2Option($val['id'],$defaultLang);
$heading    = translateFromSerialize($val['name']);
// @$heading    = $heading[$defaultLang];
$opt        .= '<option value="'.$val['id'].'" disabled>'.htmlentities($heading).'</option>';
if($menu2   !=  false){
$opt    .= $menu2;
} else{
continue;
}
}
return $opt;
}




$lang_select = '<select id="lang_select" name="lang_select" class="form-control">
<option selected disables>Select Language</option>';
foreach ($lang as $row) {
$lang_select .= '<option value="'.$row.'">'.$row.'</option>';
}
$lang_select .= '</select>';

?>

<div>
<!-- <h4 class="sub_heading"><?php echo _uc($_e['Discount Products']); ?></h4> -->

<ul class="nav nav-tabs tabs_arrow" role="tablist" >

<li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Mass Update']); ?></a></li>

</ul>

<div class="tab-content">

<div class="tab-pane fade in active container-fluid" id="home">




<h2 class="tab_heading"><?php echo _uc($_e['Mass Update']); ?></h2>


<div class="form-horizontal"> 







<form action="" method="post" id="massUpdateForm">
<input type="hidden" name="mode" id="mode" />

<div class="form-group">
<?php //Under Menu
$option = underMenuOption();
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc('Select Categories') .'</label>
<div class="col-sm-10  col-md-9">
<select name="underMenu" id="underMenu" class="underMenu form-control">
<option value="0">'. _uc('Categories Menu') .'</option>
'.$option.'
</select>
</div>
</div>'; ?>

</div>

<div id="languages" class="form-group" hidden>
<label class="col-sm-2 col-md-3  control-label">Select Language</label>
<div class="col-sm-10  col-md-9">
<?php echo $lang_select; ?>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9">
<input type="button" class="btn btn-primary btn-md" id="submit_form" value="Submit" />
</div>
</div>

</form>



            









<!--          <span class="message">

Drop images here to upload

<br/>

<i>

they will only be visible to you

</i>

</span> -->

<!-- </div> -->



<!-- <form action="" method="post" id="massUpdateForm">
<input type="hidden" name="mode" id="mode" />

<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">Product Fields</label>
<div class="col-sm-10  col-md-9">
<select id="field_select" name="field_select[]" class="form-control" multiple="multiple">
<option selected disabled>Select Product Fields</option>
<option data-id="basic" value="prodet_name">Name</option>
<option data-id="basic" value="prodet_shortDesc">Short Description</option>
<option data-id="basic" value="ldesc">Detail Description</option>
<option data-id="basic" value="size_chart">Size Chart</option>
<option data-id="basic" value="tags">Delivery & Return</option>
<option data-id="basic" value="specification">Specification</option>
<option data-id="basic" value="featureIcon">Feature Icons</option>
<option data-id="basic" value="featurePoints">Feature Points</option>
<option data-id="setting" value="publicAccess">Public Access</option>
<option data-id="setting" value="slug">Slug</option>
<option data-id="setting" value="Model">Model</option>
<option data-id="setting" value="label">Lable</option>
<option data-id="setting" value="launchDate">Launch Date</option>
<option data-id="setting" value="shippingClass">Shipping Class</option>
</select>
</div>
</div>

<div id="languages" class="form-group" hidden>
<label class="col-sm-2 col-md-3  control-label">Select Language</label>
<div class="col-sm-10  col-md-9">
<?php echo $lang_select; ?>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9">
<input type="button" class="btn btn-primary btn-md" id="submit_form" value="Submit" />
</div>
</div>

</form> -->
</div>

<div id="product_table">

</div>


<?php 
$uniq=uniqid('id');

?>
<div id="preloader" style="display: none;"></div>
<div class="table-responsive" id="script-width">
<table class="table table-hover tableIBMS dTable_dynamic " data-sorting="true">

</table>
</div>


</div>

</div>

</div>
<script type="text/javascript">

$(document).ready(function(){
// $.fn.dataTable.ext.errMode = 'none';
});

$('#field_select').on('change', function(){
selectedOption = $('#field_select option:selected');
if(selectedOption.length > 1){
selectedOption.each(function(){
// Removes the first disabled option
if($(this).is(":enabled"))
{
tab = $(this).data('id');

//update hidden field
$('#mode').val(tab);

if(tab == 'basic'){
// Disable other tab data
$('#field_select option').each(function(){
if($(this).is(":enabled"))
{
test = $(this).data('id');
if(test == 'setting'){
var input = $('input[value="' + $(this).val() + '"]');
input.prop('disabled', true);
input.parent('li').addClass('disabled');
}
}
});

// Show Language Select dropdown
$('#languages').show();

}else if(tab == 'setting'){
//update hidden field
$('#mode').val(tab);

// Disable other tab data
$('#field_select option').each(function(){
if($(this).is(":enabled"))
{
test = $(this).data('id');
if(test == 'basic'){
var input = $('input[value="' + $(this).val() + '"]');
input.prop('disabled', true);
input.parent('li').addClass('disabled');
}
}
}); 

// Show Language Select dropdown
$('#languages').show();
}
}
}); 
}
else{
$('#field_select option').each(function(){
if($(this).is(":enabled"))
{
var input = $('input[value="' + $(this).val() + '"]');
input.prop('disabled', false);
input.parent('li').removeClass('disabled');
}
}); 

//update hidden field
$('#mode').val('');

// Show Language Select dropdown
$('#languages').show();
}
});


$('#submit_form').on('click', function(){
form = $('#massUpdateForm').serialize();
var data,
tableName= '.dTable_dynamic',
columns,
str;

$('#preloader').css('display', 'block');

$.ajax({
url: 'product/products_listing_ajax.php?page=getMassData1',
type: 'post',
data: form
}).done(function(responseText){
// console.log(responseText);
// document.getElementById('preloader').style.display = 'none';
$('#preloader').css('display', 'none');



// product_table


$('#product_table').html(responseText);

// data = JSON.parse(responseText);

// Iterate each column and print table headers for Datatables
// head = '<thead>';
// $.each(data.columns, function (k, colObj) {
// str = '<th>' + colObj + '</th>';
// head += str;
// });
// head += '</thead>';
// $(tableName).html(head);


// bkLib.onDomLoaded(function() { toggleArea1(); });        
// Debug? console.log(data.columns[0]);

// var table = $(tableName).DataTable({
// "columns": data.columns,
// "data": data.data,
// "retrieve": true,
// "fnInitComplete": function () {

// console.log('rendering completed');
// Event handler to be fired when rendering is complete (Turn off Loading gif for example)
})
});

// if ( $.fn.DataTable.isDataTable( tableName ) ) {
// table.destroy();
// $(tableName).DataTable({
// "columns": data.columns,
// "data": data.data,
// "retrieve": true,
// "fnInitComplete": function () {

// console.log('rendering completed');
// // Event handler to be fired when rendering is complete (Turn off Loading gif for example)
// }
// });
// }
// });
// });

var area1;

function toggleArea1(ths, remove=false) {
if(!area1) {
id = $(ths).attr('id');
pId = $(ths).data('id');

area1 = new nicEditor({buttonList : ['fontSize','bold','italic']}).panelInstance(id,{hasPanel : true});
$('#update_'+pId).attr('disabled', true);
$(ths).next('.remove').css('display', 'block');
} else {
area1.removeInstance(id);
area1 = null;
$('#update_'+pId).attr('disabled', false);
$(ths).prev('.empty_div').removeClass('empty_div');
if(remove){
$(ths).css('display', 'none');
}
}
}

// $('body').on('click', function(event){
//  console.log('Clicked on body!');
//  area1 = null;
// });

</script>

<script type="text/javascript">
$(document).ready(function() {
$('#field_select').multiselect();
});
</script>

<script type="text/javascript">
function updateProduct(pId){
select_fields = $('#field_select').val();
lang = $('#lang_select').val();

// field_obj = new Object;
field_obj = {};

for (var i = 0; i < select_fields.length; i++) {
// console.log($('#area'+select_fields[i]+pId).text());
field_val = $('#area'+select_fields[i]+pId).html();
console.log('field_val TOP : '+field_val);

if(field_val == ''){
field_val = $('#area'+select_fields[i]+pId).val();
}
console.log('field_val : '+field_val);
var fi = select_fields[i];
field_obj[fi] = field_val;
}

$.ajax({
url: 'product/products_listing_ajax.php?page=massUpdPro',
type: 'post',
data: {fields : field_obj, pId : pId, lang : lang}
}).done(function(res){
jAlertifyAlert('Product Updated Successfully!');
console.log(res);
});
console.log(field_obj);

}
</script>



<script>
// $( document ).ready( function( $ ) {
//     $.ajax({
//             "url": 'product/products_listing_ajax.php?page=getMassData',
//             "success": function(json) {
//                 var tableHeaders;
//                 $.each(json.columns, function(i, val){
//                     tableHeaders += "<th>" + val + "</th>";
//                 });

//                 $("#script-width").empty();
//                 $("#script-width").append('<table id="displayTable" class="display table table-hover tableIBMS dTable_dynamic" cellspacing="0" width="100%"><thead><tr>' + tableHeaders + '</tr></thead></table>');
//                 //$("#tableDiv").find("table thead tr").append(tableHeaders);  

//                 $('#displayTable').dataTable(json);
//             },
//             "dataType": "json"
//         });
// });
</script>

<script>
// var data,
//         tableName= '.dTable_dynamic',
//         columns,
//         str,
//         jqxhr = $.ajax('product/products_listing_ajax.php?page=getMassData')
//                 .done(function () {
//                     data = JSON.parse(jqxhr.responseText);

//         head = '<thead>';
//         // Iterate each column and print table headers for Datatables
//         $.each(data.columns, function (k, colObj) {
//             str = '<th>' + colObj + '</th>';
//             console.log(str);
//             head += str;
//         });
//         head += '</thead>';
//         $(tableName).append(head);

//         // Add some Render transformations to Columns
//         // Not a good practice to add any of this in API/ Json side
//         // data.columns[0].render = function (data, type, row) {
//         //     return '<h4>' + data + '</h4>';
//         // }
//                 // Debug? console.log(data.columns[0]);
//         $(tableName).dataTable({
//          "columns": data.columns,
//             "data": data.data,
//             "fnInitComplete": function () {
//                 // Event handler to be fired when rendering is complete (Turn off Loading gif for example)
//                 console.log(data.columns);
//                 console.log('Datatable rendering complete');
//             }
//         });
//     })
//     .fail(function (jqXHR, exception) {
//                     var msg = '';
//                     if (jqXHR.status === 0) {
//                         msg = 'Not connect.\n Verify Network.';
//                     } else if (jqXHR.status == 404) {
//                         msg = 'Requested page not found. [404]';
//                     } else if (jqXHR.status == 500) {
//                         msg = 'Internal Server Error [500].';
//                     } else if (exception === 'parsererror') {
//                         msg = 'Requested JSON parse failed.';
//                     } else if (exception === 'timeout') {
//                         msg = 'Time out error.';
//                     } else if (exception === 'abort') {
//                         msg = 'Ajax request aborted.';
//                     } else {
//                         msg = 'Uncaught Error.\n' + jqXHR.responseText;
//                     }
//         console.log(msg);
//     });
</script>

<style type="text/css">
div#preloader {
overflow: hidden;
background-color: #000;
background-image: url(../webImages/throbber-dark.gif);
background-repeat: no-repeat;
background-position: center center;
height: 100%;
left: 0;
position: fixed;
top: 0;
width: 100%;
z-index: 9999;
opacity: 0.7;
}

.empty_div {
position: absolute;
display: block;
width: 100%;
height: 100%;
left: 0px;
top: 0px;
cursor: pointer;
}

.dt_div{
width: 350px;
}

</style>



<script>

$(document).ready(function () {

////////////////////////////////////

$(".datepicker").datepicker({minDate: 0});



///////////////////////////////////////

$(".imageHolder").click(function () {

img = $(this).find("img").attr('src');

$('#productImgDialog').modal('show');

$("#productImgDialog .modal-body").find("img").attr("src", img).hide().show(600);

});



////////////////////////////////////////////

$(".productEditImageDel").click(function () {

if (secure_delete()) {

id = $(this).attr("data-id");

parnt = $(this).closest(".preview");

$.ajax({

type: "POST",

url: 'product_management/product_ajax.php?page=productEditImageDel',

data: {imageId: id}

}).done(function (data) {

if (data == '1') {

parnt.hide(500);

} else if (data == '0') {

jAlertifyAlert('Image Not Delete Please Try Again');

return false;

}

});

}

});

/////////////////////////////////////////////////



$('.QtyAllow').change(function () {

data = $(this).attr('data-id');

input = $('#' + data);

name = input.attr('data-name');

old = input.attr('data-old');

if ($(this).prop("checked")) {

input.removeAttr('name');

input.attr('readonly', 'readonly');

input.attr('disabled', 'disabled');

input.val("").change();



} else {

input.attr('name', name);

input.removeAttr('readonly');

input.removeAttr('disabled');

input.val(old).change();



}

});

/////////////////////////////////////////////////////////



// $(".pImageAltUpdate").click(function () {

// btn = $(this);

// btn.addClass('disabled');

// btn.children('.trash').hide();

// btn.children('.waiting').show();



// id = btn.attr('data-id');

// alt = $('#alt-' + id).val();

// btn.children('span').text('Wait...');

// $.ajax({

// type: 'POST',

// url: 'product_management/product_ajax.php?page=pImageAltUpdate',

// data: {imageId: id, altT: alt}

// }).done(function (data) {

// ift = true;

// if (data == '1') {

// btn.children('span').text('Done');

// }

// else {

// btn.children('span').text('Fail');

// }

// btn.removeClass('disabled');

// btn.children('.trash').show();

// btn.children('.waiting').hide();



// });

// });

////////////////////////////////////////////

function submitProduct() {

convertNumber();

return true;

}




////////////////////////////////

$( "table tbody" ).sortable( {
update: function( event, ui ) {
$(this).children().each(function(index) {
// $(this).find('td').last().html(index + 1)
});
serial = $(this).sortable('serialize');
$.ajax({
url: 'product_management/product_ajax.php?page=sortProductSize',
type: "post",
data: serial,
error: function () {
jAlertifyAlert("<?php echo _js($_e['There is an error, Please Refresh Page and Try Again']); ?>");
}
}).done(function(res){
console.log(res);
});
// console.log(serial);
}
});

});

</script>



<script>

$('input[type=checkbox]').click(function () {

console.log('checkbox clicked');

$(this).parent()

.find('li input[type=checkbox]')

.prop('checked', $(this)

.is(':checked'));

var sibs = false;



$(this).closest('ul')

.children('li').each(function () {

if($('input[type=checkbox]', this).is(':checked')) 

sibs=true;

})

$(this).parents('ul').prev().prop('checked', sibs);



$("input[type='checkbox'] ~ ul input[type='checkbox']").change(function() {

$(this).closest("li:has(li)").children("input[type='checkbox']").prop('checked', $(this).closest('ul').find("input[type='checkbox']").is(':checked'));

});

});



</script>



<style>



#nestedlist, #nestedlist ul {

list-style-type: none;

margin-left:0;

padding-left:30px;

text-indent: -4px;

}



/* UL Layer 1 Rules */

#nestedlist {

/*font-size: 20px;*/

font-weight:bold;

}



/* UL Layer 2 Rules */

#nestedlist ul {

/*font-size: 18px;*/

font-weight: normal;

margin-top: 3px;

}



/* UL Layer 3 Rules */

#nestedlist ul ul {

font-size: 16px;

}



/* UL 4 Rules */

#nestedlist ul ul ul {

font-size: 14px;

}





/*ul li a {

text-decoration: none;

border: 1px solid #000;

border-width: 0 0 1px 1px;

border-radius: 0 0 0 10px;

}*/  

</style>

<?php return ob_get_clean(); ?>