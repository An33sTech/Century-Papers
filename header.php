<?php
global $dbF, $functions, $productClass, $webClass, $menuClass, $seo;

$link = '';
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isset($seo['seoPK']) || empty(trim($seo['seoPK']))) {
	$segment = explode("-", $segment, 2);
	$pId = $segment[1];
	$segment = "/" . $segment[0] . "-" . intval($segment[1]);

	$sql_seo_slug = "SELECT `id` FROM `seo` WHERE `ref_id` = ?";
	$seo11 = $dbF->getRow($sql_seo_slug, [$segment]);

	$seo['seoPK'] = $seo11['id'];
}

$current_lang = currentWebLanguage();

$cur_lang = match ($current_lang) {
	'English' => 'EN'
};

$val1 = $current_lang;
$current_url = $webClass->langSlugNew($val1, $seo['seoPK']);

$link = WEB_URL . $current_url;
$canonical = $seo['canonical'];

if ($canonical != $actual_link) {
	if ($link != $actual_link && $current_url != "/") {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: {$link}");
		exit;
	}
}
$Email =  $functions->ibms_setting('Email');
$contact =  $functions->ibms_setting('contact');
$Address =  $functions->ibms_setting('Address');
$facebook =  $functions->ibms_setting('Facebook');
$Instagram =  $functions->ibms_setting('Instagram');
$linkedIn =  $functions->ibms_setting('linkedIn');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
	$webClass->AllSeoPrint();
	echo "<link rel='canonical' href='$canonical' />\n";
	?>
    <link rel="shortcut icon" href="webImages/favicon.ico">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Outfit:wght@300;400;500;600;700;800;900&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- /Google Fonts -->

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="css/base592e.css?ver=3" />
    <link type="text/css" rel="stylesheet" href="css/justified592e.css?ver=3" />
    <link type="text/css" rel="stylesheet" href="css/fontello592e.css?ver=3" />
    <link type="text/css" rel="stylesheet" href="css/magnific-popup592e.css?ver=3" />
    <link type="text/css" rel="stylesheet" href="css/swiper592e.css?ver=3" />
    <link type="text/css" rel="stylesheet" href="css/lightgallery592e.css?ver=3" />
    <link type="text/css" rel="stylesheet" href="css/owl-carousel592e.css?ver=3" />
    <link href="css/style592e.css?ver=<?php echo filemtime('css/style592e.css'); ?>  " rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <!--[if lt IE 9]> <script type="text/javascript" src="js/modernizr.custom.js?ver=3"></script> <![endif]-->
    <!-- /Styles -->

</head>

<body>


    <!-- Wrapper All -->
    <div class="industify_fn_wrapper_all" data-nav-skin="transdark">

        <!-- Wrapper -->
        <div class="industify_fn_wrapper">

            <!-- Header -->
            <div class="industify_fn_header">

                <!-- Header: Top Panel -->
                <div class="industify_fn_toppanel">
                    <div class="left_panel">
                        <div class="info">
                            <a href="tel:<?php echo $contact ?>"><?php echo $contact ?></a>
                        </div>
                        <div class="info">
                            <a href="mailto:<?php echo $Email ?>"><?php echo $Email ?></a>
                        </div>
                        <div class="industify_fn_social_list">
                            <ul>
                                <li>
                                    <a href="<?php echo $facebook ?>" target="_blank">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $Instagram ?>" target="_blank">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $linkedIn ?>"
                                        target="_blank">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right_panel">
                        <div class="lang_switcher">
                            <span class="click">Eng</span>
                            <ul>
                                <li class="active"><span>Eng</span></li>
                                <li><a href="#"><span>Urdu</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Header: Top Panel -->

                <!-- Header: Bottom Panel -->
                <div class="header_inner">
                    <div class="menu_logo">
                        <a href="<?php echo WEB_URL ?>"
                            style="display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                            <img class="desktop_logo" src="webImages/logo.png"
                                alt="Century Paper & Board Mills Limited">
                            <img class="desktop_logo_dark" src="webImages/logo.png"
                                alt="Century Paper & Board Mills Limited">
                            <span class="logo_text">CENTURY PAPER & BOARD MILLS LIMITED</span>
                        </a>
                    </div>
                    <div class="menu_nav">
                        <ul class="industify_fn_main_nav vert_nav">
    <?php
    $currentScript = basename($_SERVER['SCRIPT_NAME']);

    if (!function_exists('renderMainDynamicMenu')) {
        function renderMainDynamicMenu($menuClass, $parentId = 0, $currentScript = '')
        {
            $menuItems = $menuClass->menuTypeSingle('main', $parentId);

            if (!empty($menuItems) && is_array($menuItems)) {
                foreach ($menuItems as $item) {

                    $menuId = $item['id'];
                    $text   = getTextFromSerializeArray($item['name']);
                    $link   = getTextFromSerializeArray($item['link']);

                    $childMenu = $menuClass->menuTypeSingle('main', $menuId);
                    $hasChild  = !empty($childMenu) && is_array($childMenu);

                    $liClass = '';
                    if ($hasChild && $parentId != 0) {
                        $liClass = ' class="menu-item-has-children"';
                    }

                    echo '<li' . $liClass . '>';

                    echo '<a href="' . htmlspecialchars($link) . '">';
                    echo htmlspecialchars($text);

                    if ($hasChild) {
                        if ($parentId == 0) {
                            echo ' <span><i class="fa-solid fa-angle-down"></i></span>';
                        } else {
                            echo ' <span><i class="fa-solid fa-angle-right"></i></span>';
                        }
                    }

                    echo '</a>';

                    if ($hasChild) {
                        echo '<ul class="sub-menu">';
                        renderMainDynamicMenu($menuClass, $menuId, $currentScript);
                        echo '</ul>';
                    }

                    echo '</li>';
                }
            }
        }
    }

    renderMainDynamicMenu($menuClass, 0, $currentScript);
    ?>
</ul>
                    </div>
                </div>
                <!-- /Header: Bottom Panel -->

            </div>
            <!-- /Header -->

            <!-- Mobile Menu -->
            <div class="industify_fn_mobilemenu_wrap">
                <div class="industify_fn_toppanel">
                    <div class="left_panel">
                        <div class="info"><a href="tel:<?php echo $contact ?>"><?php echo $contact ?></a></div>
                        <div class="info"><a href="<?php echo $Email ?>"><?php echo $Email ?></a></div>
                        <div class="industify_fn_social_list">
                            <ul>
                                <li>
                                    <a href="<?php echo $facebook ?>" target="_blank">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $Instagram ?>" target="_blank">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $Instagram ?>"
                                        target="_blank">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right_panel">
                        <div class="lang_switcher">
                            <span class="click">Eng</span>
                            <ul>
                                <li class="active"><span>Eng</span></li>
                                <li><a href="#"><span>Urdu</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- TOLL FREE MOBILE -->
                <div class="m_toll_free_lang">
                    <div class="m_toll_free">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <span class="shape3"></span>
                        <div class="tf_in">
                            <!-- <div class="img_holder" data-fn-bg-img="webImages/call.html"></div> -->
                            <p><span>Tel:</span> <?php echo $contact ?></p>
                        </div>
                    </div>
                </div>
                <!-- /TOLL FREE MOBILE -->
                <!-- LOGO & HAMBURGER -->
                <div class="logo_hamb">
                    <div class="in">
                        <div class="menu_logo">
                            <a href="index.php"
                                style="display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                                <img src="webImages/logo.png" alt="Century Paper & Board Mills Limited" />
                                <span class="logo_text logo_text_mobile">CENTURY PAPER & BOARD MILLS LIMITED</span>
                            </a>
                        </div>
                        <div class="hamburger hamburger--collapse-r">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /LOGO & HAMBURGER -->
                <!-- MOBILE DROPDOWN MENU -->
                <div class="mobilemenu">
                    <div>
                        <ul class="vert_menu_list">
    <?php
    if (!function_exists('renderMobileDynamicMenu')) {
        function renderMobileDynamicMenu($menuClass, $parentId = 0)
        {
            $menuItems = $menuClass->menuTypeSingle('mobile_menu', $parentId);

            if (!empty($menuItems) && is_array($menuItems)) {
                foreach ($menuItems as $item) {

                    $menuId = $item['id'];
                    $text   = getTextFromSerializeArray($item['name']);
                    $link   = getTextFromSerializeArray($item['link']);

                    $childMenu = $menuClass->menuTypeSingle('mobile_menu', $menuId);
                    $hasChild  = !empty($childMenu) && is_array($childMenu);

                    echo '<li>';
                    echo '<a href="' . htmlspecialchars($link) . '">' . htmlspecialchars($text) . '</a>';

                    if ($hasChild) {
                        echo '<ul class="sub-menu">';
                        renderMobileDynamicMenu($menuClass, $menuId);
                        echo '</ul>';
                    }

                    echo '</li>';
                }
            }
        }
    }

    renderMobileDynamicMenu($menuClass, 0);
    ?>
</ul>
                    </div>
                </div>
                <!-- /MOBILE DROPDOWN MENU -->
            </div>
            <!-- /Mobile Menu -->

            <!-- Preloader -->
            <div id="industify-fn-loader">
                <div class="fn_loader"></div>
                <div class="loader-section section-left"></div>
                <div class="loader-section section-right"></div>
            </div>
            <!-- /Preloader -->