<?php
ob_start();
?>
<div class="about_section innerpage-aboutsection clients-showcase-section" id="clients-showcase">
    <div class="blueprint_grid_bg"></div>
    <div class="container">
        <div class="ca_modern_heading">
            <div class="ghost">PARTNERS</div>
            <div class="tag">STRATEGIC ALLIANCES</div>
            <h3 class="title">Trusted by <span>Industry Leaders</span></h3>
            <div class="line"></div>
        </div>
        <div class="blueprint_desc">
            <p>
                We maintain high-value business relationships with leading national and multinational companies
                globally. Our packaging boards, fine paper, and corrugated solutions are the preferred choice for major
                players across various industrial sectors.
            </p>
        </div>
        <div class="clients_gallery_grid">
            <?php
	    $sqlBrands = "SELECT * FROM `brands` WHERE publish='1'";
	    $brandsData = $dbF->getRows($sqlBrands);
	    foreach($brandsData as $brands){
	        $brand_heading = getTextFromSerializeArray($brands['brand_heading']);
	        $brand_image = WEB_URL . '/images/' . $brands['image'];
	    ?>
                <div class="client_card">
                    <div class="glow_bar"></div>
                    <div class="logo_wrap">
                        <img src="<?php echo $brand_image; ?>" alt="<?php echo $brand_heading; ?>">
                    </div>
                </div>
        <?php
	    }
	    ?>
        </div>
    </div>
</div>
<?php
return ob_get_clean();
?>