// Retrieve IDs and Pages from the DOM
var newId = $("#AjaxFileNewId").val();
var page = $("#AjaxFileNewPage").val();
var newIdDetail = $("#AjaxFileNewIdDetail").val();
var pageDetail = $("#AjaxFileNewPageDetail").val();

// Select dropzones and messages
var dropbox = $("#dropbox");
var message = $(".message", dropbox);
var dropboxDetail = $("#dropboxDetail");
var messageDetail = $(".messageDetail", dropboxDetail);

var newPageSlug = page;
var newCallBackImageCreater = createImage;

// Template for image preview
var template = `
    <div class="preview">
        <span class="imageHolder">
            <img width="100" height="100" />
            <span class="uploaded"></span>
        </span>
        <div class="progressHolder">
            <div class="progress"></div>
        </div>
    </div>`;

// Show messages
function showMessage(msg) {
    message.html(msg);
}

function showMessage2(msg) {
    messageDetail.html(msg);
}

// Create image preview
function createImage(file) {
    var preview = $(template);
    var image = $("img", preview);

    var reader = new FileReader();

    reader.onload = function (e) {
        image.attr("src", e.target.result);
    };

    reader.readAsDataURL(file);
    message.hide();
    preview.appendTo(dropbox);
    $.data(file, preview);
}

// Create detail image preview
function createDetailImage(file,dropbox) {
    
    var preview = $(template);
    var image = $("img", preview);

    var reader = new FileReader();

    reader.onload = function (e) {
        image.attr("src", e.target.result);
    };

    reader.readAsDataURL(file);
    messageDetail.hide();
    preview.appendTo(dropbox);
    $.data(file, preview);
}

// Common settings for both dropboxes
function commonFileDropSettings(dropbox, createImageFunc, messageFunc, item_id, pageFunc) {
    if (dropbox.length) { // Check if the dropbox exists
        console.log("Initializing filedrops for dropbox", dropbox);
        try {
            dropbox.filedrop({
            paramname: "pic",
            maxfiles: 5,
            maxfilesize: 3, // MB
            url: "post_file.php",
            data: {
                item_id: item_id,
                page: pageFunc
            },
            uploadFinished: function (i, file, response) {
                $.data(file).addClass("done");
            },
            error: function (err, file) {
                switch (err) {
                    case "BrowserNotSupported":
                        messageFunc("Your browser does not support HTML5 file uploads!");
                        break;
                    case "TooManyFiles":
                        alert("Too many files! Please select 5 at most! (configurable)");
                        break;
                    case "FileTooLarge":
                        alert(file.name + " is too large! Please upload files up to 2mb (configurable).");
                        break;
                    default:
                        break;
                }
            },
            beforeEach: function (file) {
                if (!file.type.match(/^image\//)) {
                    alert("Only images are allowed!");
                    return false;
                }
            },
            uploadStarted: function (i, file, len) {
                createImageFunc(file, dropbox);
            },
            progressUpdated: function (i, file, progress) {
                $.data(file).find(".progress").width(progress);
            }
        });
        } catch (error) {
            console.error("Error initializing filedrops:", error);
            // Optionally call messageFunc or alert to notify about the error
            messageFunc("An error occurred during file upload initialization. Please try again.");
        }
    } else {
        console.warn("Dropbox is not defined or has no length.");
    }
}

// Function to handle tab click events
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href"); // Get the target tab pane ID
    if (target === "#tab_images") {
        // Reinitialize main dropbox
        if ($("#dropbox").length && !$("#dropbox").hasClass('initialize')) {
            $("#dropbox").addClass('initialize'); // Add initialize class
            console.log("Initializing main dropbox with item_id:", newId, "and page:", page);
            commonFileDropSettings($("#dropbox"), createImage, showMessage, newId, "product");
        } else {
            console.log("Main dropbox not found or already initialized");
        }
    } else if (target === "#tab_detail_images") {
        // Reinitialize detail dropbox
        if ($("#dropboxDetail").length && !$("#dropboxDetail").hasClass('initialize')) {
            $("#dropboxDetail").addClass('initialize'); // Add initialize class
            console.log("Initializing detail dropbox with item_id:", newIdDetail, "and page:", newPageSlug);
            commonFileDropSettings($("#dropboxDetail"), createDetailImage, showMessage2, newIdDetail, "productDetail");
        } else {
            console.log("Detail dropbox not found or already initialized");
        }
    }
});


//Initialize the dropboxes with their respective settings if they exist
var dropboxGallery = $("#dropbox");
if (dropboxGallery.length) {
    console.log("Initializing main dropboxGallery with item_id:", newId, "and page:", page);
    commonFileDropSettings($("#dropbox"), createImage, showMessage, newId, "album");
} else {
    console.log("Main dropbox not found");
}

// if (dropboxDetail.length) {
//     console.log("Initializing detail dropbox with item_id:", newIdDetail, "and page:", newPageSlug);
//     commonFileDropSettings(dropboxDetail, createDetailImage, showMessage2, newIdDetail, function () {
//         return newPageSlug;
//     });

//     // Update settings when files are dropped into dropboxDetail
//     // dropboxDetail.on('drop', function (e) {
//     //     if ($(e.target).is("#dropboxDetail")) {
//     //         newPageSlug = "productDetail";
//     //         newCallBackImageCreater = createDetailImage;
//     //     }
//     // });
// } else {
//     console.log("Detail dropbox not found");
// }