<?php
include "global.php";
include_once 'header.php';
$id = $_GET["industry"];
$sqlService = "SELECT * FROM industries WHERE slug = '$id'";
$serviceData = $dbF->getRow($sqlService);
$image = WEB_URL . '/images/' . $serviceData['image'];
$heading = getTextFromSerializeArray($serviceData['heading']);
$shortDesc = getTextFromSerializeArray($serviceData['shortDesc']);
$dsc = getTextFromSerializeArray($serviceData['dsc']);
?>
<div class="industify_fn_pagetitle innovative_banner">
    <div class="banner_bg_shapes">
        <div class="bg_shape grid_pattern"></div>
        <div class="bg_shape glow_1"></div>
        <div class="bg_shape glow_2"></div>
    </div>
    <div class="banner_decorations">
        <!-- Floating Leaf 1 -->
        <div class="decor_item leaf_1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 22C2 22 6 18 12 17C18 16 22 12 22 2C22 2 12 2 7 8C2 14 2 22 2 22Z" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 22C10 20 16 16 22 2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Leaf 2 -->
        <div class="decor_item leaf_2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 22C2 22 6 18 12 17C18 16 22 12 22 2C22 2 12 2 7 8C2 14 2 22 2 22Z" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 22C10 20 16 16 22 2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div class="container">
        <div class="title_holder">
            <h3><?php echo $heading ?></h3>
            <div class="industify_fn_breadcrumbs">
                <ul>
                    <li><a href="<?php echo WEB_URL ?>" title="Home">Home</a></li>
                    <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                    <li><span class="bread-current"><?php echo $heading ?></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /Page Title -->
 
<!-- Sidebar Page -->
<div class="industify_fn_sidebarpage">
    <div class="container">
        <div class="s_inner">
 
            <!-- Main Sidebar: Left -->
            <div class="industify_fn_leftsidebar fullWidth">
                
                <div class="governance_intro" style="padding: 0; margin-bottom: 30px;">
                    <div class="ca_modern_heading ce_modern_heading_left">
                        <div class="ghost">INTEGRITY</div>
                        <h3 class="title"><?php echo $heading ?></span></h3>
                        <div class="line"></div>
                    </div>
                </div>
 
                <div class="blueprint_desc">
                    <p><?php echo $dsc ?></p>
                </div>
                <div class="ce_images_grid">
                    <div class="ce_image_box">
                        <img src="<?php echo $image ?>" alt="<?php echo $heading ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Sidebar Page -->
 
<?php include_once("footer.php"); ?>