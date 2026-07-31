$(document).ready(function () {
    var $wrapper = $(".wrapper");

    // $(document).on(
    //     "mouseenter mouseleave",
    //     ".wrapper .IBMS_Main_Menu",
    //     function (e) {
    //         if (e.type == "mouseenter") {
    //             $wrapper.addClass("sidebar_hover");
    //         } else {
    //             $wrapper.removeClass("sidebar_hover");
    //             $("#IBMS_Menu ul ul").slideUp();

    //             if (!$(this).next().is(":visible")) {
    //                 $(this).next().slideDown();
    //             }
    //         }
    //         return false;
    //     }
    // );
    
    $(document).on(
        "mouseenter mouseleave",
        ".wrapper .IBMS_Main_Menu",
        function (e) {
            if (e.type == "mouseenter") {
                $wrapper.addClass("sidebar_hover");
            } else {
                $wrapper.removeClass("sidebar_hover");
                
                if ($(this).hasClass("collapse_menu")) {
                    $("#IBMS_Menu ul ul").slideUp();
                }
    
                if (!$(this).next().is(":visible")) {
                     if ($(this).hasClass("collapse_menu")) {
                        $(this).next().slideDown();
                    }
                }
            }
            return false;
        }
    );




    // Function to handle menu click events
    $("#IBMS_Menu h3").click(function () {
        // Slide up all the link lists
        $("#IBMS_Menu ul ul").slideUp();

        // Slide down the link list below the h3 clicked - only if it's closed
        if (!$(this).next().is(":visible")) {
            $(this).next().slideDown();
        }
    });

    // Function to handle collapse menu click events
    $("#collapse_menu").click(function () {
        $(".IBMS_Main_Menu,#container_div").stop();

        if ($(".IBMS_Main_Menu").hasClass("collapse_menu")) {
            $(".IBMS_Main_Menu,#container_div").removeAttr("style");
            $(".nav-header").removeClass("nav-header-collapse");
            $(".IBMS_Main_Menu").removeClass("collapse_menu");
            $("#container_div").removeClass("collapse_menu_active");
            $("#IBMS_Menu ul ul").slideUp();

            if (!$(this).next().is(":visible")) {
                $(this).next().slideDown();
            }

            $.cookie("showTop", "null");
        } else {
            $(".nav-header").addClass("nav-header-collapse");
            $(".IBMS_Main_Menu").addClass("collapse_menu");
            $("#container_div").addClass("collapse_menu_active");
            $("#IBMS_Menu ul ul").slideUp();

            if (!$(this).next().is(":visible")) {
                $(this).next().slideDown();
            }

            $.cookie("showTop", "collapse_menu");
        }
    });

    // Function to add 'active' class when clicking on a link
    $("#IBMS_Menu>ul>li").click(function () {
        // Remove 'active' class from all links
        $("#IBMS_Menu>ul>li").removeClass("active");
        // Add 'active' class to the clicked link
        $(this).addClass("active");
    });

    // Function to add 'subMenu' class when clicking on a link
    $("#IBMS_Menu>ul>li>ul>li").click(function () {
        // Remove 'active' class from all links
        $("#IBMS_Menu>ul>li>ul>li").removeClass("subMenu");
        // Add 'subMenu' class to the clicked link
        $(this).addClass("subMenu");
    });

    // Check if 'showTop' cookie is set to 'collapse_menu' and apply styles accordingly
    // if ($.cookie("showTop") == "collapse_menu") {
    //     $(".IBMS_Main_Menu").addClass("collapse_menu");
    //     $("#container_div").addClass("collapse_menu_active");
    // }
});
