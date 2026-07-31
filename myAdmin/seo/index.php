<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = "seoM";

switch ($page):
    case ("seo"):
        $subMenu = 'seo';
        $content = include "seo.php";
        break;
    case ("edit"):
        $subMenu = 'seo';
        $content = include "seoEdit.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
endswitch;


if (!$isAjax) {
    include_once('../header.php');
}
echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['SEO Management']) . "</h3>$content</div>";

?>
<script type="text/javascript">
    function check_slug(id) {
        var inp_slugSwedish = $('.slugSEOSwedish').val();

        $.ajax({
                url: 'seo/seo_ajax.php',
                type: 'POST',
                data: {
                    'inp_slugSwedish': inp_slugSwedish,
                    'is_slug_check': 'check',
                    'id': id
                },
            })
            .done(function(res) {

                console.log(res);
                if (res == "000") {
                    $('#slug_Respose').css('display', 'block');

                    $('#slug_Respose').text('Swedish Slug already available Please provide another slug');
                    $('#submit_btn').prop('disabled', true);
                }  else {
                    $('#slug_Respose').css('display', 'none');
                    $('#submit_btn').prop('disabled', false);
                    // passM();
                }
            });

    }



    // function passM() {
    //     var inp_slugSwedish = $('.slugSEOSwedish').val();



    //     if (inp_slugSwedish == inp_slugEnglish || inp_slugSwedish == inp_slugNorwegian || inp_slugSwedish == inp_slugDanish || inp_slugSwedish == inp_slugFinnish || inp_slugSwedish == inp_slugGerman || inp_slugSwedish == inp_slugFrench) {

    //         document.getElementById("pm").style.color = "red";
    //         document.getElementById("pm").innerHTML = "<?php $dbF->hardWords('Slug Matched!'); ?>";
    //         $('#submit_btn').prop('disabled', true);

    //     } else {
    //         document.getElementById("pm").style.color = "green";
    //         document.getElementById("pm").innerHTML = "";

    //         $('#submit_btn').prop('disabled', false);

    //     }
    // }
</script>


<?php

if (!$isAjax) {
    include_once('../footer.php');
}

?>