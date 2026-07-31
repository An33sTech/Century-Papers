//-----u-vmenu-----//
// $(document).ready(function() {
//     $(".u-vmenu").vmenuModule({
//         Speed: 200,
//         autostart: true,
//         autohide: true
//     });
// });
$('.search_side_box').click(function() {
    $(this).find('input[type="text"]').focus();
});
//-----u-vmenu close-----//
//-----u-vmenu li active-----//
// $('.u-vmenu li').on('click', function() {
//     $('.u-vmenu .active1').removeClass('active1');
//     $(this).addClass('active1');
// });
//-----u-vmenu li active close-----//
//-----left menu colum open script-----//
// $('.menu_side').click(function() {
//     $(".col_left").toggleClass("col_left_add");
//     $(".col_right").toggleClass("col_right_add");
//     $(".left_hover").toggleClass("left_hover_add");
// });
//-----left menu colum close script-----//
//-----search box script-----//
$('.search_side').click(function() {
    $(".search_side_box").toggleClass("search_side_box_add");
});
//-----search box script close-----//
//-----search box script close button-----//
$('.close_btn').click(function() {
    $(".search_side_box").removeClass("search_side_box_add");
});
//-----search box script close button end-----//
//-----on click menu right menu div add-----//
$('.col_left').hover(function() {
    $(".col_left").toggleClass("col_left_add2");
});
//-----on click menu right menu div add close-----//
//-----select_side add-----//
// $(document).ready(function() {
//     $('.user_side select').niceSelect();
// });
//-----select_side close-----//
//-----tabs add-----//
$(function() {
    $(".tabs").tabs();
});
//-----tabs close-----//
//-----col_top_bell script-----//
$('.col_top_bell').click(function() {
    $(".message_side").toggleClass("message_side_add");
});
//-----col_top_bell script close-----//
//-----col_top_bell script-----//
$('.col_top_subscribe').click(function() {
    $(".message_side").toggleClass("message_side_add");
});
//-----col_top_bell script close-----//
//-----select_side add-----//
// $(document).ready(function() {
//     $('.col_right_main2_select select').niceSelect();
// });
//-----select_side close-----//
//-----col2_main2_right2 add-----//
// $(document).ready(function() {
//     $('.col2_main2_right2 select').niceSelect();
// });
//-----col2_main2_right2 close-----//
//-----col3_main add-----//
// $(document).ready(function() {
//     $('.col3_main select').niceSelect();
// });
//-----col3_main close-----//
//-----menu_side tablet_visible -----//
$(document).ready(function() {
    $(".menu_side").click(function() {
        $(".tablet_visible").slideToggle();
    });
});
//-----menu_side tablet_visible -----//
//----- tablet_visible -----//
$(document).ready(function() {
    $(".tablet_visible").click(function() {
        $(".tablet_visible").slideToggle();
    });
});
//----- tablet_visible close -----//
//-----tablet_visible close button-----//
$('.tablet_visible').click(function() {
    $(".col_left").removeClass("col_left_add");
});
//-----search box script close button end-----//
//------ full screen ------//
function toggleFullScreen() {
    if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}
//------ full screen ------//
$(document).ready(function(){
    //-----material_icon add-----//
    $('.material_icon').click(function() {
        $(".material_main").toggleClass("material_main_add");
    });
    //-----material_icon close-----//
    //-----check_positive add-----//
    $('.material_box').click(function() {
        $(".check_positive").toggleClass("check_positive_add");
    });
    //-----check_positive close-----//
    //-----check_positive add-----//
    $('.notification_side2_box_close_btn').click(function() {
        $(".check_positive").removeClass("check_positive_add");
    });
    //-----check_positive close-----//
    //-----check_positive add-----//
    $('.material_main_right ul li').click(function() {
        $(".alert_popup").toggleClass("alert_popup_add");
    });
    //-----check_positive close-----//
    //-----check_positive add-----//
    $('.notification_side2_box_close_btn').click(function() {
        $(".alert_popup").removeClass("alert_popup_add");
    });
    //-----check_positive close-----//
});
//-----Other Click Event-----//
$(document).mouseup(function(e) {
    var container = $(".message_side_add");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.removeClass('message_side_add');
    }
});
//-----Other Click Event-----//
//-----Other Click Event-----//
$(document).mouseup(function(e) {
    var container = $(".material_main");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.removeClass('material_main_add');
    }
});
//-----Other Click Event-----//
//-----check_positive add-----//
$('.branches_side_input').focusin(function() {
    $(this).addClass("branches_side_input_add");
});
$('.branches_side_input').focusout(function() {
    $(this).removeClass("branches_side_input_add");
});
//-----check_positive close-----//
$(function() {
    $("#accordion").accordion({
        heightStyle: "content"
    });
});
//-----accordion close-----//
//-----select-----//
$(function() {
    $("#speed").selectmenu();
    $("#files").selectmenu();
    $("#number").selectmenu().selectmenu("menuWidget").addClass("overflow");
    $("#salutation").selectmenu();
    $("#select_box_one").selectmenu();
    $("#select_box_two").selectmenu();
    // $("select").selectmenu();
});
//-----select-----//
// ------range slider------//
// $(function() {
//     $("#range").ionRangeSlider({
//         hide_min_max: true,
//         keyboard: true,
//         min: 0,
//         max: 5000,
//         from: 1000,
//         to: 4000,
//         type: 'double',
//         step: 1,
//         prefix: "$",
//         grid: true
//     });
// });
// ------range slider------//
// ------autocomplete select------//
$(function() {
    var availableTags = ["ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme"];

    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
    $("#tags")
        // don't navigate away from the field on tab when selecting an item
        .on("keydown", function(event) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            minLength: 0,
            source: function(request, response) {
                // delegate back to autocomplete, but extract the last term
                response($.ui.autocomplete.filter(availableTags, extractLast(request.term)));
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function(event, ui) {
                var terms = split(this.value);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(ui.item.value);
                // add placeholder to get the comma-and-space at the end
                terms.push("");
                this.value = terms.join(", ");
                return false;
            }
        });
});
// ------autocomplete select------//
// ------date picker ------//
$(function() {
    $("#datepicker").datepicker();
});
// ------date picker ------//
///-----bootstrap range slider-----///
var slider = document.getElementById("myRange");

// console.log(slider);
var output = document.getElementById("demo");
// output.innerHTML = slider.value;
// slider.oninput = function() {
//     output.innerHTML = this.value;
// };
///-----bootstrap range slider close-----///
// ------ password show hide ------//
// $(".toggle-password").click(function() {
//     $(this).toggleClass("fa-eye fa-eye-slash");
//     var input = $($(this).attr("toggle"));
//     console.log('input :'+input);
//     if (input.attr("type") == "password") {
//         input.attr("type", "text");
//     } else {
//         input.attr("type", "password");
//     }
// });
$(".toggle-passwords").click(function() {
    console.log('input :');
    // $(this).toggleClass("fa-eye fa-eye-slash");
    // var input = $($(this).attr("toggle"));

    // if (input.attr("type") == "password") {
    //     input.attr("type", "text");
    // } else {
    //     input.attr("type", "password");
    // }
});
// ------ password show hide ------//
// ------ select  ------//
document.querySelector("html").classList.add('js');
var fileInput = document.querySelector(".input-file"),
    button = document.querySelector(".input-file-trigger"),
    the_return = document.querySelector(".file-return");
button.addEventListener("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
        fileInput.focus();
    }
});
button.addEventListener("click", function(event) {
    fileInput.focus();
    return false;
});
fileInput.addEventListener("change", function(event) {
    the_return.innerHTML = this.value;
});
// ------ select  ------//
// ------ time  ------//
$(function() {
    $("#time").ionRangeSlider({
        min: +moment().subtract(12, "hours").format("X"),
        max: +moment().format("X"),
        from: +moment().subtract(6, "hours").format("X"),
        grid: true,
        force_edges: true,
        prettify: function(num) {
            var m = moment(num, "X").locale("ru");
            return m.format("HH:mm");
            ///---Do MMMM,---////
        }
    });
});
// ------ time  ------//

$(
    "#example-getting-started, #example-getting-started1, #example-getting-started2"
).multiselect({
    includeSelectAllOption: true,
    enableHTML: true,
    filterPlaceholder: "Search for something...",
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    optionLabel: function (element) {
        return (
            '<img src="' + $(element).attr("data-img") + '"> ' + $(element).text()
        );
    },
});

$("#newDealProduct").multiselect({
    includeSelectAllOption: true,
    enableHTML: true,
    filterPlaceholder: "Search for something...",
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
});

$(".newDealProduct").multiselect({
    includeSelectAllOption: true,
    enableHTML: true,
    filterPlaceholder: "Search for something...",
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
});

$(".salesFeatureSetting").multiselect({
    includeSelectAllOption: true,
    enableHTML: true,
    filterPlaceholder: "Search for something...",
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
});

$("#example-getting-started_without_img").multiselect({
    includeSelectAllOption: true,
    enableHTML: true,
    filterPlaceholder: "Search for something...",
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
});
// $(".make-switch").on("switch-change", function (e, data) {
//     if (data.value) {
//         $(this).find(".checkboxHidden").val("1");
//     } else {
//         $(this).find(".checkboxHidden").val("0");
//     }
// });
/* jAlert */
$("#jAlert").dialog({
    modal: true,
    autoOpen: false,
    buttons: {
        Close: function () {
            $(this).dialog("close");
        },
    },
});
$(".ui-dialog-buttonset").find("button").addClass("btn-danger").addClass("btn");
/* jAlert End */

function seleScale(id) {
    var iid = id;

    var divId = "#divScaleVal" + iid;

    $(".classScaleVal input[data-name]").attr("name", null);

    $(".classScaleVal").hide(0, function () {
        $(divId).show(0, function () {
            $(" input[data-name]", this).each(function () {
                var name = $(this).attr("data-name");

                $(this).attr("name", name);
            });
        });
    });
}

$("#seleScale").selectmenu({
    change: function (event, data) {
        seleScale(data.item.value);
    },
});



