import "../assets/header/header.js";
import "../assets/menu/menu.js";
import "./footer_script.js";

/***** Full height function start *****/

var setHeightWidth = function () {
    var height = $(window).height();
    var width = $(window).width();
    $(".full-height").css("height", height);
    $(".page-wrapper").css("min-height", height);
};

/***** Full height function end *****/

/***** Resize function start *****/

$(window)
    .on("resize", function () {
        setHeightWidth();
    })
    .resize();

/***** Resize function end *****/


