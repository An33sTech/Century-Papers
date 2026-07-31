// Function to initialize main.js
function initializeMain() {
  $("#content").addClass("default");
  console.log("Initializing main.js");
  // Your main.js initialization code goes here
}

// Function to reset main.js
function resetMain() {
  console.log("Resetting main.js");
  // Any cleanup or reset logic for main.js goes here
}

function initializePlugins() {
  // Reinitialize CKEditor for textareas with class 'ckeditor' if any are available
  if ($('textarea.ckeditor').length) {
    $('textarea.ckeditor').each(function () {
      if (CKEDITOR.instances[this.id]) {
        CKEDITOR.instances[this.id].destroy(true);
      }
      CKEDITOR.replace(this);
    });
  }
  // Additional jQuery reinitialization logic goes here...
}

// Call initializePlugins function on document ready
$(document).ready(function() {
  initializePlugins();
});

$(document).ready(function () {
  initializeMain();

  // Define maximum number of retries
  var maxRetries = 2;
  var retryCount = 0;
  var isLoading = false; // Track loading state

  // Function to load content dynamically
  function loadPage(page, title) {
    console.log("i am in load page");
    if (isLoading) {
      console.log("A page is already loading. Skipping...");
      return;
    }

    isLoading = true;
    $(".preloader-it").fadeIn("slow");

    // Get all DataTable instances and destroy them
    var tables = $.fn.dataTable.tables({ api: true });
    $(tables).each(function() {
      $(this).DataTable().destroy();
    });

    var currentPage = window.location.pathname.split('/').pop();
    console.log(currentPage);
    // if (currentPage === page) {
    //   console.log("Same page clicked. Skipping load.");
    //   $(".preloader-it").fadeOut("slow");
    //   isLoading = false;
    //   return;
    // }

    // Set timeout for retrying loadPage
    var loadPageTimeout = setTimeout(function () {
      console.log("Load page timeout exceeded. Retrying...");
      if (retryCount < maxRetries) {
        retryCount++;
        loadPage(page, title); // Retry loading the page
      } else {
        console.log("Maximum number of retries reached. Reloading page...");
        location.reload(); // Reload the page
      }
    }, 5000); // Adjust the timeout duration as needed (e.g., 5 seconds)

    $.get(page, function (data) {
      $("#content").html(data);
      document.title = title; // Update document title
      window.history.pushState(
        {
          path: page,
        }, 
        "",
        page
      ); // Update URL
    }).done(function () {
      clearTimeout(loadPageTimeout); // Clear the timeout since loading is successful
      console.log("i am in load page done");
      $("html, body").animate({ scrollTop: "0" }, 100);
      $(".preloader-it").fadeOut("slow");

      // Reset retry count and loading state
      retryCount = 0;
      isLoading = false;
        if(page.includes('-adminorder')){
            var scriptFiles = [
                "/myAdmin/assets/bs-switch/bootstrap-switch.3.0.js",
                "/myAdmin/ajaxFileUpload/js/script.js",
                "/myAdmin/js/main.php",
                "/myAdmin/js/footer_script.js",
                "/myAdmin/js/main.js",
                "/myAdmin/adminorder/js/order.php",
              ];
        }else{
              // Define an array of file paths to load
              var scriptFiles = [
                "/myAdmin/assets/bs-switch/bootstrap-switch.3.0.js",
                "/myAdmin/ajaxFileUpload/js/script.js",
                "/myAdmin/js/main.php",
                "/myAdmin/js/footer_script.js",
                "/myAdmin/js/main.js",
                "/myAdmin/order/js/order.php"
              ];
        }

      // Get the domain name
      var domain = window.location.origin;

      // Iterate over the array of script file paths
      scriptFiles.forEach(function (scriptSrc) {
        // Construct the full script path
        var fullScriptSrc = domain + scriptSrc;

        // Remove existing script tag if it exists
        var existingScript = document.querySelector(
          'script[src="' + fullScriptSrc + '"]'
        );
        if (existingScript) {
          existingScript.parentNode.removeChild(existingScript);
        }

        // Dynamically load the JavaScript file from the specified domain
        var script = document.createElement("script");
        script.src = fullScriptSrc;

        // Add type="module" only to main.js
        if (scriptSrc === "/myAdmin/js/main.js") {
          script.type = "module";
        }

        document.head.appendChild(script);
      });
      
      initializePlugins();
    }).fail(function () {
      clearTimeout(loadPageTimeout);
      console.log("Failed to load page: " + page);
      retryCount++;
      if (retryCount < maxRetries) {
        loadPage(page, title); // Retry loading the page
      } else {
        console.log("Maximum number of retries reached. Reloading page...");
        location.reload(); // Reload the page
      }
    }).always(function () {
      isLoading = false;
    });
  }

  // Event listener for page navigation
  $(document).on("click", "a[data-page]", function (e) {
    e.preventDefault();
    resetMain();
    var page = $(this).data("page");
    var title = $(this).attr("title");

    loadPage(page, title);
  });

  // Handle back/forward buttons
  window.onpopstate = function (e) {
    resetMain();
    var page = e.state ? e.state.path : window.location.pathname.split('/').pop();
    loadPage(page, document.title);
  };
});
