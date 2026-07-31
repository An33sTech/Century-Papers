<?php
include (__DIR__ . '/globalVar.php');
//Encrypt After here
?>
<?php
//these are variables that declare in global.php write here for license
global $dbF;
global $db;
global $_e;
global $functions;
global $menuClassGlobal;
global $adminPermissions;
global $defaultAdminLanguage;
global $adminPanelLanguage;
global $ActivePagePerm;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php

  $_w = array();
  //header.php
  $_w['Go To Home'] = '';
  $_w['SignOut'] = '';
  $_w['Account Setting'] = '';
  $_w['SignIn'] = '';
  $_w["Sorry you don't have permission to access this page"] = '';

  //All Inner folder Index Page Handel Here
  //Index also in its class, but in some management there is several class. so its need to place outside of its class
  //In future any need to change index heading, change here.
  $_w['Brands Management'] = '';
  $_w['Banners Management'] = '';
  $_w['Blog Management'] = '';
  $_w['Email Management'] = '';
  $_w['Gallery Management'] = '';
  $_w['Logs Management'] = '';
  $_w['Manage Website Menu'] = '';
  $_w['News Management'] = '';
  $_w['Order / Invoice Management'] = '';
  $_w['Pages Management'] = '';
  $_w['Product Management'] = '';
  $_w['SEO Management'] = '';
  $_w['Gift Card Management'] = '';
  $_w['Setting'] = '';
  $_w['Shipping Management'] = '';
  $_w['Stock Management'] = '';
  $_w['WebUsers Management'] = '';

  $_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Header');
  ?>

  <title>IBMS v<?php echo $functions->IBMSVersion;
  ?></title>

  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>

  <link rel='icon' href='favicon.ico' type='image/x-icon' />
  <link rel='shortcut icon' href='favicon.ico' type='image/x-icon' />

<!--  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css' />-->
<!--  <link rel='stylesheet' type='text/css'-->
<!--    href='<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui-1.13.3/jquery-ui.min.css' />-->
    
<!--Bootstrap-->
<!--  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.min.css' />-->
  <!--Select-->
<!--  <link rel="stylesheet" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-select/dist/css/bootstrap-select.min.css">-->
    <!--Multiselect-->
<!--  <link rel='stylesheet' type='text/css'-->
<!--    href='<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css' />-->
    
  <!--DataTables CSS-->
<!--  <link href='<?php echo WEB_ADMIN_URL; ?>/assets/DataTables/datatables.min.css' rel='stylesheet'>-->

  <!-- Jstree -->
<!--  <link rel="stylesheet" href="<?php echo WEB_ADMIN_URL; ?>/assets/jstree/dist/themes/default/style.min.css" />-->
  
    <!--Alertify css-->
<!--  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/alertify/css/alertify.min.css' />-->
  
  <!--Bootstrap/Jquery Dual Select Box-->
<!--    <link rel="stylesheet" type="text/css"-->
<!--    href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css">-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"  />
  <!--<link-->
  <!--  href="https://cdn.jsdelivr.net/gh/eliyantosarage/font-awesome-pro@main/fontawesome-pro-6.5.1-web/css/all.min.css"-->
  <!--  rel="stylesheet" /> -->

  <!--  Color Picker -->
<!--  <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/colorpicker/css/colorpicker.css">-->

<!--  <link rel="stylesheet" href="<?php echo WEB_ADMIN_URL; ?>/ajaxFileUpload/css/styles.css" />-->

  <!--<link rel="stylesheet" type="text/css"-->
  <!--  href="<?php echo WEB_ADMIN_URL; ?>/assets/bs-switch/bootstrap-switch.3.0.css" />-->


  <!-- custome css -->
<!--  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/css/commonuse.css' />-->
<!--  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/css/style.css' />-->
<!--  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/css/printCss.css' />-->

  <!-- jquery-3.7.1 -->
  <!--<script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/jquery.1.11.1.js'></script>-->
  <!--<script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/jquery-3.7.1.js'></script>-->
  
  <!-- Jquery UI-->
  <!--<script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/js/jquery-ui.1.11.1.min.js"></script>-->
<!--  <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui-1.13.3/jquery-ui.min.js"></script>-->
 <!--    <link rel = 'stylesheet' type = 'text/css' href = 'assets/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.css'/> -->
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css' />
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css' />
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css' />

  <!--Multiselect css-->
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css' />

  <!--<link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css' />-->
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/menu/menu.css' />

  <script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/jquery.1.11.1.js'></script>
  <!--<script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/jquery-2.1.1.min.js'></script>-->
  <!--<script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/jquery-3.7.1.js'></script>-->

  <!-- twitter bootstrap ajax typeahead plugin -->
  <script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/assets/biggora-bootstrap-ajax-typeahead/js/bootstrap-typeahead.js' />
  </script>

  <!-- PENDING tags input found in sisyphus.. no need to use bootstrap tagsinput
<script type = 'text/javascript' src = 'assets/bootstrap-tagsinput/bootstrap-tagsinput-angular.js'></script>
<script type = 'text/javascript' src = 'assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js'></script>
<link rel = 'stylesheet' type = 'text/css' href = 'assets/bootstrap-tagsinput/bootstrap-tagsinput.css'>
-->

  <!-- main common functions -->
  <script type='text/javascript' src='<?php echo WEB_ADMIN_URL; ?>/js/main.php'></script>
  <!-- custome css -->
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/css/commonuse.css' />
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/css/style.css?ver=<?php echo filemtime(__DIR__ . "/css/style.css"); ?>' />
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/css/printCss.css' />

  <!--Alertify css-->
  <link rel='stylesheet' type='text/css' href='<?php echo WEB_ADMIN_URL; ?>/assets/alertify/themes/alertify.core.css' />

  <!--<script src = '//code.jquery.com/jquery-1.11.3.min.js'></script>-->
  <!--<script src = 'https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js'></script>-->
  <!--<script src = 'https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js'></script>-->
  <!--<script src = 'https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js'></script>-->
  <!--<script src = 'https://cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.js'></script>-->

  <?php
  // Checking Email Cron complete
  //Using requiredCron file for fast process instense of using db query
  ob_start();
  include_once('requiredCron.txt');
  $emailComplete =  ob_get_clean();
  if ($emailComplete == 'okay') {
    echo "<script>
        //location.replace('-email?page=newsLetter&completeEmails');
     </script>";
  }
  ?>

</head>

<body>

  <!-- Preloader -->
  <div class="preloader-it">
    <div class="la-anim-1"></div>
  </div>
  <!-- /Preloader -->
  <div class="wrapper ">

    <?php
    if ($functions->menu_show === true) {
      ?>

      <!-- Top Menu Items -->
      <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="mobile-only-brand pull-left">
          <div class="nav-header pull-left">
            <div class="logo-wrap">
              <a href='<?php echo WEB_URL; ?>'>
                <img class="brand-img" src="assets/header/images/logo_ibms.png" loading="lazy" alt="brand" />
                <span class="brand-text">IBMS</span>
              </a>
            </div>
          </div>
          <a id="collapse_menu" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i
              class="fa-solid fa-bars"></i></a>
          <!--<a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view"-->
          <!--  href="javascript:void(0);"><i class="fa-solid fa-magnifying-glass"></i></a>-->
          <a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
          <!--<form id="search_form" role="search" class="top-nav-search collapse pull-left">-->
          <!--  <div class="input-group">-->
          <!--    <input type="text" name="example-input1-group2" class="form-control" placeholder="Search">-->
          <!--    <span class="input-group-btn">-->
          <!--      <button type="button" class="btn  btn-default" data-target="#search_form" data-toggle="collapse"-->
          <!--        aria-label="Close" aria-expanded="true"><i class="fa-solid fa-magnifying-glass"></i></button>-->
          <!--    </span>-->
          <!--  </div>-->
          <!--</form>-->
        </div>
        <div id="mobile_only_nav" class="mobile-only-nav pull-right">
          <ul class="nav navbar-right top-nav pull-right">
            <li class="dropdown auth-drp">
              <?php
              if ($functions->log_check()['status'] == 'ok') {
                echo "	<a href='#' class='dropdown-toggle pr-0' data-toggle='dropdown'><i class='fa-solid fa-gear'></i></a>";
                echo '
						<ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                                <li><a href="-setting?page=account" data-page="-setting?page=account" title="IBMS - Account Setting"><i class="fa-solid fa-gear"></i><span>' . $_e['Account Setting'] . '</span></a></li>
                                <li><a href="logout"><i class="fa-solid fa-power-off"></i><span> ' . $_e['SignOut'] . '</span></a></li>
                            </ul>';
              } else {
                echo '<a href="do-login.secure"><i class="glyphicon glyphicon-log-in"></i> ' . $_e['SignIn'] . '</a>';
              }
              ?>
            </li>
          </ul>
        </div>
      </nav>
      <!-- /Top Menu Items -->
    <?php }
    ?>

    <div id='main_Div' class='container-fluid col-md-12 no-margin-padding'>
      <div class='IBMS_Main_Menu no-margin-padding '>
        <?php
        echo $menuClassGlobal->menu();
        ?>
      </div>
      <!-- .IBMS_Main_Menu -->

      <div id='container_div' class='page-wrapper '>
        <div class='content_div'>

          <?php

          //check inner pages permissions /edit pages
          //Function call after menu load or actual page load, , it check active menu status
          $functions->pageInnerPermission($menuClassGlobal);

          //Check Page Permissions for admin users
          global $ActivePagePerm;
          if ($ActivePagePerm === false) {
            echo '<h2>' . $_e["Sorry you don't have permission to access this page"] . '</h2>';

            include_once (__DIR__ . '/footer.php');
            // for js files
            exit;
          }

          ?>