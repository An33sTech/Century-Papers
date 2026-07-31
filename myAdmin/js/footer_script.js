// Initialize multiselect if elements are available
//   if ($('#example-getting-started, #example-getting-started1, #example-getting-started2').length) {
//     $('#example-getting-started, #example-getting-started1, #example-getting-started2').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true,
//       optionLabel: function(element) {
//         return '<img src="' + $(element).attr('data-img') + '"> ' + $(element).text();
//       },
//     });
//   }

//   if ($('#newDealProduct').length) {
//     $('#newDealProduct').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });
//   }

//   if ($('.newDealProduct').length) {
//     $('.newDealProduct').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });
//   }

//   if ($('.salesFeatureSetting').length) {
//     $('.salesFeatureSetting').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });
//   }

//   if ($('#example-getting-started_without_img').length) {
//     $('#example-getting-started_without_img').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });
//   }

// $('#example-getting-started, #example-getting-started1, #example-getting-started2').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true,
//       optionLabel: function(element) {
//         return '<img src="' + $(element).attr('data-img') + '"> ' + $(element).text();
//       },
//     });

//     $('#newDealProduct').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });

//     $('.newDealProduct').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });

//     $('.salesFeatureSetting').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });

//     $('#example-getting-started_without_img').multiselect({
//       includeSelectAllOption: true,
//       enableHTML: true,
//       filterPlaceholder: 'Search for something...',
//       enableFiltering: true,
//       enableCaseInsensitiveFiltering: true
//     });

//       $('.make-switch').on('switch-change', function(e, data) {
//       if (data.value) {
//         $(this).find(".checkboxHidden").val('1');
//       } else {
//         $(this).find(".checkboxHidden").val('0');
//       }
//     });

// Initialize multiselect for the first group of elements
if (
    $("#example-getting-started").length ||
    $("#example-getting-started1").length ||
    $("#example-getting-started2").length
) {
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
}

// Initialize multiselect for #newDealProduct
if ($("#newDealProduct").length) {
    $("#newDealProduct").multiselect({
        includeSelectAllOption: true,
        enableHTML: true,
        filterPlaceholder: "Search for something...",
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
}

// Initialize multiselect for .newDealProduct
if ($(".newDealProduct").length) {
    $(".newDealProduct").multiselect({
        includeSelectAllOption: true,
        enableHTML: true,
        filterPlaceholder: "Search for something...",
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
}

// Initialize multiselect for .salesFeatureSetting
if ($(".salesFeatureSetting").length) {
    $(".salesFeatureSetting").multiselect({
        includeSelectAllOption: true,
        enableHTML: true,
        filterPlaceholder: "Search for something...",
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
}

// Initialize multiselect for #example-getting-started_without_img
if ($("#example-getting-started_without_img").length) {
    $("#example-getting-started_without_img").multiselect({
        includeSelectAllOption: true,
        enableHTML: true,
        filterPlaceholder: "Search for something...",
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
}

if ($("#example-getting-started_without_img2").length) {
    $("#example-getting-started_without_img2").multiselect({
        includeSelectAllOption: true,
        enableHTML: true,
        filterPlaceholder: "Search for something...",
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
}

if ($("#example-getting-started_without_img3").length) {
    $("#example-getting-started_without_img3").multiselect({
        includeSelectAllOption: true,
        enableHTML: true,
        filterPlaceholder: "Search for something...",
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
}

// Initialize switch-change event listener
if ($(".make-switch").length) {
    $(".make-switch").on("switch-change", function (e, data) {
        if (data.value) {
            $(this).find(".checkboxHidden").val("1");
        } else {
            $(this).find(".checkboxHidden").val("0");
        }
    });
}

$(function () {
    tableHoverClasses();

    dateJqueryUi();

    if ($(".min,#min").length && $(".max,#max").length) {
        minMaxDateFilter();
    }

    // dTableRangeSearch();
});

function deletePage(ths) {
    btn = $(ths);

    if (secure_delete()) {
        btn.addClass("disabled");

        btn.children(".trash").hide();

        btn.children(".waiting").show();

        id = btn.attr("data-id");

        $.ajax({
            type: "POST",

            url: "pages/page_ajax.php?page=deletePage",

            data: { id: id },
        }).done(function (data) {
            ift = true;

            if (data == "1") {
                ift = false;

                btn.closest("tr").hide(1000, function () {
                    $(this).remove();
                });
            } else if (data == "0") {
                jAlertifyAlert("Delete Fail Please Try Again.");
            } else {
                btn.append(data);
            }

            if (ift) {
                btn.removeClass("disabled");

                btn.children(".trash").show();

                btn.children(".waiting").hide();
            }
        });
    }
}

var dateCodeTo = "";
var dateCodeFrom = "";

function minMaxDateFilter() {
    $(".min,#min").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        changeYear: true,
        yearRange: "1935:2050",
        dateFormat: "yy-mm-dd",
        onClose: function (selectedDate, i) {
            $(".max,#max").datepicker("option", "minDate", selectedDate);

            try {
                var date = $(this).datepicker("getDate"),
                    day = date.getDate(),
                    month = ("0" + (date.getMonth() + 1)).slice(-2),
                    year = date.getFullYear();
                dateCodeFrom = year + "-" + month + "-" + day;

                var max_datepicker_val = $("#max").data("datepicker").lastVal;
                if (max_datepicker_val != "") {
                    fetch_ajax_result_again(dateCodeFrom, max_datepicker_val);
                }
            } catch (e) {
                console.log(e);
            }
        },
    });

    $(".max,#max").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        changeYear: true,
        yearRange: "1935:2050",
        dateFormat: "yy-mm-dd",
        onClose: function (selectedDate) {
            $(".min,#min").datepicker("option", "maxDate", selectedDate);

            try {
                var date = $(this).datepicker("getDate"),
                    day = date.getDate(),
                    month = date.getMonth() + 1,
                    year = date.getFullYear();
                dateCodeTo = year + "-" + month + "-" + day;

                var min_datepicker_val = $("#min").data("datepicker").lastVal;
                if (min_datepicker_val != "") {
                    fetch_ajax_result_again(dateCodeFrom, dateCodeTo);
                }
            } catch (e) {
                console.log(e);
            }
        },
    });
}

function fetch_ajax_result_again(dateCodeFrom, dateCodeTo) {
    if ($($.fn.dataTable.tables({ visible: true, api: true })).length) {
        $($.fn.dataTable.tables({ visible: true, api: true }))
            .DataTable()
            .ajax.reload();
    }
}

// OrderCH
$(function () {
    dateJqueryUi();
    minMaxDateFilter();

    $(document).on("keydown", ".dataTables_filter input", function (event) {
        orderPrice();
    });

    $(
        "#DataTables_Table_0_length_select,#DataTables_Table_1_length_select,#DataTables_Table_2_length_select,#min,#max"
    ).change(function () {
        orderPrice();
    });
    var shopSelling = document.getElementById("shopSelling");
    if (shopSelling) {
        setTimeout(function () {
            orderPrice();
        }, 100);
    }
});

function orderPrice() {
    setTimeout(function () {
        countOrderPrice("invoices");

        countOrderPrice("all");
    }, 500);
}

$("#p_tags").tagsinput({
    trimValue: true,
    confirmKeys: [13, 44],
});
