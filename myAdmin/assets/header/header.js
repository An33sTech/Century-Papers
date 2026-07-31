// $(document).ready(function () {
// 	$(".preloader-it > .la-anim-1").addClass("la-animate");
// });

// // $(window).load(function () {
// 	setTimeout(function () {
// 		// Fade out the preloader
// 		$(".preloader-it").fadeOut("slow");
// 	}, 4000);
// 	/*Progress Bar Animation*/
// 	var progressAnim = $(".progress-anim");
// 	if (progressAnim.length > 0) {
// 		for (var i = 0; i < progressAnim.length; i++) {
// 			var $this = $(progressAnim[i]);
// 			$this.waypoint(
// 				function () {
// 					var progressBar = $(".progress-anim .progress-bar");
// 					for (var i = 0; i < progressBar.length; i++) {
// 						$this = $(progressBar[i]);
// 						$this.css("width", $this.attr("aria-valuenow") + "%");
// 					}
// 				},
// 				{
// 					triggerOnce: true,
// 					offset: "bottom-in-view",
// 				}
// 			);
// 		}
// 	}
// // });

$(document).ready(function () {
    $(".preloader-it > .la-anim-1").addClass("la-animate");

    setTimeout(function () {
        // Fade out the preloader
        $(".preloader-it").fadeOut("slow");
    }, 4000);

    /*Progress Bar Animation*/
    var progressAnim = $(".progress-anim");
    if (progressAnim.length > 0) {
        progressAnim.each(function () {
            var $this = $(this);
            $this.waypoint(
                function () {
                    var progressBar = $this.find(".progress-bar");
                    progressBar.each(function () {
                        var $bar = $(this);
                        $bar.animate({ width: $bar.attr("aria-valuenow") + "%" }, 1000); // Using jQuery 1.x animate
                    });
                },
                {
                    offset: 'bottom-in-view'
                }
            );
        });
    }
});