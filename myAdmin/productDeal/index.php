<?php

/**
 * For add new page, just copy past all files,
 * and replace words with new page name.
 * if new fields required use setting_fields table for additional fields,
 */
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu = "product";

switch ($page):
  case ("deal"):
    $subMenu = 'Deal Product';
    $content = include "deal.php";
    break;
  case ("edit"):
    $subMenu = 'Deal Product';
    $content = include "dealEdit.php";
    break;

  default:
    $content = "Page Not Found.";
    break;
endswitch;

if (!$isAjax) {
  include_once('../header.php');
}

echo  "<div id='content'><h3 class='main_heading'>" . _uc($_e['Deal Management']) . "</h3>$content</div>";

?>
<script>
  $('.show_product').on('switch-change', function(e, data) {

    if (data.value) {
      $($('.multiproduct').closest(".form-group")).css("display", "block")
    } else {

      $($('.multiproduct').closest(".form-group")).css("display", "none")
    }
  });
  //  const toFindDuplicates = arry => arry.filter((item, index) => arr.indexOf(item) !== index);
  function findDuplicates(arr) {
    return arr.filter((currentValue, currentIndex) =>
      arr.indexOf(currentValue) !== currentIndex);
  }
  $(document).on('click', 'input[type=submit]', function(event) {
    event.preventDefault();

    let arr = [];
    $(".checkbox input[type=checkbox]:checked").map((a, b) => {
      if ($(b).val() !== '') {
        arr.push($(b).val());
      }
    });

    const duplicateElementa = findDuplicates(arr);
    if (duplicateElementa.length == 0) {
      $('#formId').submit();
      return true
    } else {
      jAlertifyAlert(
        '<?php $dbF->hardWords('Same product appear in multiple options please select the unique product'); ?>'
      );
      // console.log("Abc")
      console.log(duplicateElementa.length);
    }


  });
  // $('.newDealProduct').on('change', function(ths){
  //     console.log($(ths), $(ths).val())
  // })
  //  $('.show_product').on('switch-change', function (e, data) {

  //     if(data.value){
  //         $($('.multiproduct').closest(".form-group")).css("display", "block")
  //     }else{

  //          $($('.multiproduct').closest(".form-group")).css("display", "none")
  //     }
  // });
</script>

</div>
<?php

if (!$isAjax) {
  include_once('../footer.php');
}
?>