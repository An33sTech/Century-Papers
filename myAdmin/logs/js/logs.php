<?php header('Content-type: application/x-javascript');
require_once(__DIR__.'/../../global.php');
/**
 * MultiLanguage keys Use where echo;
 * define this class words and where this class will call
 * and define words of file where this class will called
 **/
global $_e;
global $adminPanelLanguage;
$_w=array();
$_w['Error Found While Deleting, Please Try Again.'] = '' ;
$_w['Data Not Found'] = '' ;
$_w['Image Not Delete Please Try Again'] = '' ;
$_w['Product Scale Not Available'] = '' ;
$_w['Product Color Not Available'] = '' ;
$_w['Required Fields Are Empty.'] = '' ;
$_w['Product Quantity Is Not Correct.'] = '' ;
$_w['Duplicate Entry : Product Item already exist in list!'] = '' ;

$_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin LogsScript');

if(1===2){
//Just for suggestion help <script> if not then page behave like txt page
?>
<script>
<?php } ?>

////////////
function defectDelete(ths){
    if(secure_delete()){
        id=$(ths).attr('data-id');
        obj=$(ths);
        $.ajax({
            type: 'POST',
            url: 'logs/logs_ajax.php?page=defectDel',
            data: {id:id}
        }).done(function(data)
            {
                if(data=='1'){
                    remove_tr(obj);
                }else{
                    jAlertifyAlert('<?php echo _js($_e['Error Found While Deleting, Please Try Again.']); ?>');
                }
            });
    }
}


//////////////////
function returnDel(ths){
    if(secure_delete()){
        id=$(ths).attr('data-id');
        obj=$(ths);
        $.ajax({
            type: 'POST',
            url: 'logs/logs_ajax.php?page=returnDel',
            data: {id:id}
        }).done(function(data)
            {
                if(data!=''){
                    remove_tr(obj);
                }else{
                    jAlertifyAlert('<?php echo _js($_e['Error Found While Deleting, Please Try Again.']); ?>');
                }
            });
    }
}


function ViewReturnModal(ths){
    $('#returnModal  .modal-body').html(loading_progress());
    $('#returnModal').modal('show');
    id=$(ths).attr('data-id');
    obj=$(ths);
    $.ajax({
        type: 'POST',
        url: 'logs/logs_ajax.php?page=returnReport',
        data: {id:id}
    }).done(function(data)
        {
            if(data!=''){
                $('#returnModal .modal-body').hide().html(data).show(500);
            }else{
                $('#returnModal .modal-body').hide().html('<?php echo _js($_e['Data Not Found']); ?>').show(500);
            }
        });
}




$(function() {

    $(".defectProduct").click(function(){
        if(secure_delete()){
            id=$(this).attr('id');
            obj=$(this);
            $.ajax({
                type: 'POST',
                url: 'logs/logs_ajax.php?page=defectProductDel',
                data: {id:id}
            }).done(function(data)
                {
                    if(data=='1'){
                        if(obj.hasClass('removeMe')){
                            obj.hide(500,function(){obj.remove( )});
                        }else{
                            remove_tr(obj);
                        }
                    }else{
                        jAlertifyAlert('<?php echo _js($_e['Error Found While Deleting, Please Try Again.']); ?>');
                    }
                });
        }
    });

/////////////////////////
    $(".imageHolder").click(function(){
        img=$(this).find("img").attr('src');
        $('#productImgDialog').modal('show');
        $("#productImgDialog .modal-body").find("img").attr("src",img).hide().show(600);
    });
/////////////////////////
    $(".productEditImageDel").click(function(){
        if(secure_delete()){
        id=$(this).attr("data-id");
        parnt=$(this).closest(".preview");
        $.ajax({
            type: "POST",
            url: 'logs/logs_ajax.php?page=defectEditImageDel',
            data: { imageId:id }
        }).done(function(data) {
                if(data=='1'){
                    parnt.hide(500);
                }else if(data=='0'){
                    jAlertifyAlert("<?php echo _js($_e['Image Not Delete Please Try Again']); ?>");
                    return false;
                }
            });
        }
    });

    scale =function(data){

        scaleId = "#receipt_product_scale";
        scaleHiddenClass= ".receipt_product_scale";
        if(data==null){
            $(scaleId).val("<?php echo _js($_e['Product Scale Not Available']); ?>").attr("readonly","readonly");
            $(scaleHiddenClass).removeClass("has");
            data = [];

        }else{
            $(scaleId).val('').removeAttr("readonly");
            $(scaleHiddenClass).addClass("has");
        }

        $(scaleId).autocomplete({
            source: data,
            minLength: 0,
            select: function( event, ui ) {
                $(scaleHiddenClass).val(ui.item.id).attr("data-val",ui.item.label);
            }
        }).on('focus : click', function(event) {
                $(this).autocomplete("search", "");
            });
    };



    color =function(data){
        colorId = "#receipt_product_color";
        colorHiddenClass= ".receipt_product_color";
        $(colorId).css('border','1px solid #ccc');
        if(data==null){
            $(colorId).val("<?php echo _js($_e['Product Color Not Available']); ?>").attr("readonly","readonly");
            $(colorHiddenClass).removeClass("has");
            data = [];
        }else{
            $(colorId).val('').removeAttr("readonly");
            $(colorHiddenClass).addClass("has");
        }
        $(colorId).autocomplete({
            source: data,
            minLength: 0,
            select: function( event, ui ) {
                $(colorHiddenClass).val(ui.item.id).attr("data-val",ui.item.label);
                $(colorId).css('border','3px solid #'+ui.item.label);
            }
        }).on('focus : click', function(event) {
                $(this).autocomplete("search", "");
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .css({"margin":"1px 0",
                    "height": "23px",
                    "padding":"0"})
                .append("<div style='background:#"+item.label+";color:#FFF;height:100%;'>"+item.label+"</div>")
                .appendTo(ul);
        };
    };



});

function receiptFormValid(){
    if( $(".receipt_product_id").val() == "" ||
        $(".receipt_product_scale.has").val() == ""  || $(".receipt_product_color.has").val() == "" ||
        $("#receipt_store_id").val() == ""
        ){
        jAlertifyAlert("<?php echo _js($_e['Required Fields Are Empty.']); ?>");
        return false;
    }

    qty =parseInt($("#receipt_qty").val());
    if(qty > 0){
        $("#receipt_qty").val(qty);
    }else{
        jAlertifyAlert("<?php echo _js($_e['Product Quantity Is Not Correct.']); ?>");
        return false;
    }

    addListItem();


}
var sr=0;
function addListItem() {
    //disable one time required fields
 $(".receipt_store_id").val($("#receipt_store_id").val());
    $("#store").attr("disabled","disabled");
    $("#receipt_date,#receipt_vendor").attr("readonly","readonly");
    //disable end


    var pid = parseInt($(".receipt_product_id").val());
    var pScaleId = parseInt($(".receipt_product_scale").val());
    var pColorId = parseInt($(".receipt_product_color").val());

    if(isNaN(pScaleId)){pScaleId = 0;}
    if(isNaN(pColorId)){pColorId = 0;}

    var pName = $("#receipt_product_id").val() +" -- "+ $("#receipt_product_scale").val() +" -- "+ $("#receipt_product_color").val();
    // var vendor =$("#receipt_vendor").val();
    var date =  $("#receipt_date").val();
    var desc = $("#receipt_desc").val();


    var img =  document.getElementById('image_from_url').src;

    var qty =   parseInt($("#receipt_qty").val());
    var store = parseInt($(".receipt_store_id").val());
    //   var storeName = $("#receipt_store_id option:selected").text();

    var trpid = "p_"+pid+"-"+pScaleId+"-"+pColorId+"-"+store;
    if (document.getElementById("tr_"+trpid)) {

        jAlertifyAlert("<?php echo _js($_e['Duplicate Entry : Product Item already exist in list!']); ?>");

        document.getElementById(trpid).checked = true;
        checkchange(trpid);
    }
    else if (qty > 0 && pid > 0 ) {
        sr++;

        var item = "<tr id='tr_"+trpid+ "'>"+
            "<td><input type='checkbox' id='"+trpid+ "' onchange='checkchange(this.id)' value='" + trpid + "' class='checkboxclass' />" +
            "<input type='hidden' name='cart_list[]' value='"+trpid+"' /><span>" + sr + "</span></td>"+
            //  "<td>"+date+"<input type='hidden' name='pdate_"+trpid+"' value='"+date+"' /></td>"+
            //  "<td>"+vendor+"<input type='hidden' name='pvendor_"+trpid+"' value='"+vendor+"' /></td>"+
            "<td>"+pName+"<input type='hidden' name='pid_"+trpid+"' value='"+pid+"' />" +
            "<input type='hidden' name='pName_"+trpid+"' value='"+pName+"' />" +
            "<input type='hidden' name='pscale_"+trpid+"' value='"+pScaleId+"' />" +
            "<input type='hidden' name='pcolor_"+trpid+"' value='"+pColorId+"' /></td>"+
            "<td> <img src='"+img+"' width='100' height='100'><input type='hidden' name='img_"+trpid+"' value='"+img+"' /></td>"+
            "<td>"+qty + "<input type='hidden' name='pqty_"+trpid+"' value='"+qty+"' /></td>"+
            "<td>"+desc+"<input type='hidden' name='pdesc_"+trpid+"' value='"+desc+"' /></td>"+
            //  "<td>"+storeName+"<input type='hidden' name='pstore_"+trpid+"' value='"+store+"' /></td>"+
            "</tr>";

        $("#vendorProdcutList").append(item);
        blankField();
    }
























}






function blankField(){
    $("#receipt_qty,#receipt_desc,#receipt_product_id, .receipt_product_id, .receipt_product_scale, .receipt_product_color").val("");
 $("#image_from_url").attr("src", "https://sharkspeed.se/myAdmin/images/logo_ibms.png");


    


    color(null);
    scale(null);
}



function checkchange(pid) {
    var tr = "tr_" + pid;
    if ($('#' + pid).is(":checked")) {
        $("#"+tr).addClass("highlitedtd");
    }
    else {
        $("#"+tr).removeClass("highlitedtd");
    }
}

function removechecked() {
    $('.highlitedtd').remove();
}


function uncheckall() {
    if($("#vendorProdcutList tr").hasClass('highlitedtd')){
        $( ".checkboxclass" ).prop( "checked",false )
        $("#vendorProdcutList tr").removeClass("highlitedtd");
    }else{
        $( ".checkboxclass" ).prop( "checked",true )
        $("#vendorProdcutList tr").addClass("highlitedtd");
    }
}

function formSubmit(){
    if ( $('#vendorProdcutList tr').length > 0 ) {
        return true;
    }else{
        return false;
    }
}


<?php
if(1===2){
?>
    </script>
<?php } ?>