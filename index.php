<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
include "global.php";
global $dbF;

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$default_lang = defaultWebLanguage();

if (!empty($uri_segments[1])) { // $uri_segments[2] When it is sub folder otherwise $uri_segments[1]
    // unset($uri_segments[1]); // Only use When it is sub folder otherwise comment it
    // $uri_segments = array_values($uri_segments); // Only use When it is sub folder otherwise comment it
    $full_hierarchy = implode('/', $uri_segments);
} else {
    $full_hierarchy = "";
}

$segment = end($uri_segments);
$temp = "1";

if ($full_hierarchy != "") {
    $sql_seo_slugNew = "SELECT `seo_id` FROM `seo_slug` WHERE `slug` = ?";
    $check_seo_slugNew = $dbF->getRow($sql_seo_slugNew, [ltrim($full_hierarchy, '/')]);

    if ($dbF->rowCount > 0) {
        $sql = "SELECT `pageLink` FROM `seo` WHERE `id` = '$check_seo_slugNew[seo_id]'";
    } else {
        $sql = "SELECT `pageLink` FROM `seo` WHERE `slug` = '$segment'";
    }

    $check = $dbF->getRow($sql);

    if ($dbF->rowCount > 0) {
        $temp = "0";
    } else {
        $sqlSEOSlugNew = "SELECT `seo_id` FROM `seo_slug` WHERE `slug` = '$segment'";
        $checkSEOSlugNew = $dbF->getRow($sqlSEOSlugNew);

        if ($dbF->rowCount > 0) {
            $sql = "SELECT `pageLink` FROM `seo` WHERE `id` = '$checkSEOSlugNew[seo_id]'";
        } else {
            $sql = "SELECT `pageLink` FROM `seo` WHERE `slug` = '$segment'";
        }

        $check = $dbF->getRow($sql);

        if ($dbF->rowCount > 0) {
            $temp = "0";
        }
    }
} else {
    $sql_seo_slug = "SELECT `seo_id` FROM `seo_slug` WHERE `slug` = '$segment'";
    $check_seo_slug = $dbF->getRow($sql_seo_slug);

    if ($dbF->rowCount > 0) {
        $sql = "SELECT `pageLink` FROM `seo` WHERE `id` = '$check_seo_slug[seo_id]'";
    } else {
        $sql = "SELECT `pageLink` FROM `seo` WHERE `slug` = '$segment'";
    }

    $check = $dbF->getRow($sql);

    if ($dbF->rowCount > 0) {
        $temp = "0";
    }
}

if (isset($temp) && $segment != "" && $segment != "home" && $segment != "index.php" && $segment != "index") {
    if ($temp == "1") {
        $p_link = "/" . $segment;
    } else {
        $p_link = $check['pageLink'];
    }

    $a = explode('-', $p_link, 2);
    $key = $a[0];

    switch ($key) {
        case '/product':
            $_GET['pSlug'] = $a[1];
            include __DIR__ . "/detail.php";
            break;

        case '/pCategory':
        case '/products':
            $_GET['catSlug'] = $a[1];
            include __DIR__ . "/products.php";
            break;

        case '/productDeals':
        case '/dealCategory':
            $_GET['catSlug'] = $a[1];
            include __DIR__ . "/productDeals.php";
            break;

        case '/page':
            $_GET['page'] = $a[1];
            include __DIR__ . "/page.php";
            break;

        case '/service':
            $_GET['service'] = $a[1];
            include __DIR__ . "/service.php";
            break;

        case '/industry':
            $_GET['industry'] = $a[1];
            include __DIR__ . "/industry.php";
            break;

        case '/blog':
            $_GET['blog'] = $a[1];
            include __DIR__ . "/blog.php";
            break;

        case '/deal':
            $_GET['dealSlug'] = $a[1];
            include __DIR__ . "/dealDetailNew.php";
            break;

        case '/sitemap':
            $_GET['param'] = $a[1];
            $b = explode('-', $a[1]);
            @$_GET['range'] = $b[1];
            include __DIR__ . "/sitemap.php";
            break;

        default:
            $filePath = __DIR__ . "/" . $key . ".php";
            if (file_exists($filePath)) {
                include $filePath;
            } else {
                include __DIR__ . "/404.php";
            }
            break;
    }
} else {
    include_once 'header.php';
    $box1  = $webClass->getBox('box1');
    $box2  = $webClass->getBox('box2');
    $box3  = $webClass->getBox('box3');
    $box4  = $webClass->getBox('box4');
    $box5  = $webClass->getBox('box5');
    $box6  = $webClass->getBox('box6');
    $box7  = $webClass->getBox('box7');
    $box8  = $webClass->getBox('box8');
    $box9  = $webClass->getBox('box9');
    $box10 = $webClass->getBox('box10');
    $box11 = $webClass->getBox('box11');
    $box12 = $webClass->getBox('box12');
    $box13 = $webClass->getBox('box13');
    $box14 = $webClass->getBox('box14');
    $box15 = $webClass->getBox('box15');
?>

<div class="industify_slider_alpha" id="top" data-desc-show="yes" data-category-show="yes" data-nav-types="square"
	data-autoplay-switch="disabled" data-autoplay-time="15000" data-effect="cards" data-progress="enabled"
	data-box-pos="cr" data-img-effect="enabled" data-text-effect="enabled">
	<div class="swiper-pagination"></div>
	<div class="swiper-wrapper">
		<div class="swiper-slide">
			<div class="item">
				<div class="img_holder" data-bg-img="webImages/slider/1.jpg">
					<video autoplay muted loop playsinline poster="webImages/slider/1.jpg" class="fn_video_bg">
						<source src="webImages/century-video.mp4" type="video/mp4">
					</video>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Alpha Slider -->

<!-- Master Blueprint About Section -->
<div class="about_section" id="about">
	<div class="blueprint_grid_bg"></div>

	<div class="container">
		<div class="blueprint_wrap">

			<div class="blueprint_left">
				<div class="ca_modern_heading">
					<div class="ghost">EST 1984</div>
					<span class="tag"><?php echo $box1['heading']; ?></span>
					<h3 class="title" style="font-size: 24px; font-weight: 600;"><?php echo $box1['heading2']; ?></h3>
					<div class="line"></div>
				</div>
				<?php echo $box1['text']; ?>
			</div>

			<div class="blueprint_right">
				<div class="blueprint_image_frame">
					<div class="frame_border"></div>
					<div class="scan_animation"></div>

					<!-- Hotspots -->
					<div class="hotspot hs-1" data-info="7 Advanced Paper Machines">
						<div class="hs_dot"></div>
					</div>
					<div class="hotspot hs-2" data-info="Integrated Corrugation Facility">
						<div class="hs_dot"></div>
					</div>
					<div class="hotspot hs-3" data-info="Self-Sustaining Energy Plant">
						<div class="hs_dot"></div>
					</div>

					<div class="image_holder">
						<img src="<?php echo $box1['image']; ?>" alt="Century Paper Integrated Mill">
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /Master Blueprint About Section -->

<!-- Project Sticky Full -->
<div class="fn_cs_project_sticky_full">
	<div class="inner">
		<div class="left_part">
			<div class="fn_cs_sticky_section">
				<h3><?php echo $box2['heading']; ?></h3>
				<?php echo $box2['text']; ?>
				<a href="#about"><?php echo $box2['linkText']; ?></a>
			</div>
		</div>

		<div class="right_part">
			<div class="fn_cs_sticky_section">
				<ul>
					<li>
						<div class="item">
							<div class="img_holder">
								<img src="webImages/thumb/700-500.jpg" alt="">
								<div class="abs_img" data-bg-img="<?php echo $box3['image']; ?>"></div>
							</div>
							<div class="title_holder">
								<h3><?php echo $box3['heading']; ?></h3>
								<span class="desc"><?php echo $box3['heading2']; ?></span>
							</div>
						</div>
					</li>
					<li>
						<div class="item">
							<div class="img_holder">
								<img src="webImages/thumb/700-500.jpg" alt="">
								<div class="abs_img" data-bg-img="<?php echo $box4['image']; ?>"></div>
							</div>
							<div class="title_holder">
								<h3><?php echo $box4['heading']; ?></h3>
								<span class="desc"><?php echo $box4['heading2']; ?></span>
							</div>
						</div>
					</li>
					<li>
						<div class="item">
							<div class="img_holder">
								<img src="webImages/thumb/700-500.jpg" alt="">
								<div class="abs_img" data-bg-img="<?php echo $box5['image']; ?>"></div>
							</div>
							<div class="title_holder">
								<h3><?php echo $box5['heading']; ?></h3>
								<span class="desc"><?php echo $box5['heading2']; ?></span>
							</div>
						</div>
					</li>
					<li>
						<div class="item">
							<div class="img_holder">
								<img src="webImages/thumb/700-500.jpg" alt="">
								<div class="abs_img" data-bg-img="<?php echo $box6['image']; ?>"></div>
							</div>
							<div class="title_holder">
								<h3><?php echo $box6['heading']; ?></h3>
								<span class="desc"><?php echo $box6['heading2']; ?></span>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

	</div>
</div>
<!-- /Project Sticky Full -->

<div id="history">
	<!-- PRODUCTION MILESTONES -->
	<div class="container">
		<div class="ca_modern_heading pt-70">
			<div class="ghost">JOURNEY</div>
			<h3 class="title"><span>Milestones</span></h3>
			<div class="line"></div>
		</div>
	</div>

	<div class="ca-horizontal-timeline" id="timeline-milestones">
		<div class="timeline-scroll-track">
		    <?php
            $plainCategory      = 'milestones';
            $serializedCategory = serialize($plainCategory);
            
            $sql = "SELECT `heading`, `description`, `year`
                FROM `milestone`
                WHERE `publish` = '1'
                AND (
                    `category` = '$plainCategory'
                    OR `category` = '$serializedCategory'
                )
                ORDER BY `id` ASC";
            $milestonesData = $dbF->getRows($sql);
            foreach ($milestonesData as $milestones) {
                $heading = getTextFromSerializeArray($milestones['heading']);
                $description = getTextFromSerializeArray($milestones['description']);
                $year = getTextFromSerializeArray($milestones['year']);
                echo '
                    <div class="timeline-scroll-item">
                        <div class="timeline-node"></div>
                        <div class="timeline-scroll-content">
                            <span class="timeline-scroll-year">' . $year . '</span>
                            <h4>' . $heading . '</h4>
                            <ul>
                                <li>' . $description . '</li>
                            </ul>
                        </div>
                    </div>
                ';
            }
            ?>
		</div>
	</div>

	<!-- CERTIFICATIONS & AWARDS -->
	<div class="container">
		<div class="ca_modern_heading pt-70">
			<div class="ghost">AWARDS</div>
			<h3 class="title"><span>Awards</span></h3>
			<div class="line"></div>
		</div>
	</div>

	<div class="ca-horizontal-timeline" id="timeline-awards">
		<div class="timeline-scroll-track">
		    <?php
            $plainCategory      = 'awards';
            $serializedCategory = serialize($plainCategory);
            
            $sql = "SELECT `heading`, `description`, `year`
                FROM `milestone`
                WHERE `publish` = '1'
                AND (
                    `category` = '$plainCategory'
                    OR `category` = '$serializedCategory'
                )
                ORDER BY `id` ASC";
            $awardsData = $dbF->getRows($sql);
            foreach ($awardsData as $awards) {
                $awardsheading = getTextFromSerializeArray($awards['heading']);
                $awardsdescription = getTextFromSerializeArray($awards['description']);
                $awardsyear = getTextFromSerializeArray($awards['year']);
                echo '<div class="timeline-scroll-item">
				<div class="timeline-node"></div>
				<div class="timeline-scroll-content">
					<span class="timeline-scroll-year">' . $awardsyear . '</span>
					<h4>' . $awardsheading . '</h4>
					' . $awardsdescription . '
				</div>
			</div>
                ';
            }
            ?>
		</div>
	</div>
</div>

<!-- Principles Section - Architectural Prism -->
<div id="principles" class="ca-principles-prism-section">
	<div class="container">
		<div class="ca_modern_heading ca_modern_heading_light">
			<div class="ghost">VALUES</div>
			<h3 class="title"><span>Our</span> Core Principles</h3>
			<div class="line"></div>
		</div>

		<div class="principles-horizon">
			<div class="horizon-panel active">
				<div class="horizon-bg-text">01</div>
				<div class="horizon-content">
					<div class="horizon-icon">
						<i class="fa-solid fa-gem"></i>
					</div>
					<div class="horizon-info">
						<h3><?php echo $box8['heading']; ?></h3>
						<p><?php echo $box8['heading2']; ?></p>
						<div class="horizon-line"></div>
					</div>
				</div>
			</div>
			<div class="horizon-panel">
				<div class="horizon-bg-text">02</div>
				<div class="horizon-content">
					<div class="horizon-icon">
						<i class="fa-solid fa-users-gear"></i>
					</div>
					<div class="horizon-info">
						<h3><?php echo $box9['heading']; ?></h3>
						<p><?php echo $box9['heading2']; ?></p>
						<div class="horizon-line"></div>
					</div>
				</div>
			</div>
			<div class="horizon-panel">
				<div class="horizon-bg-text">03</div>
				<div class="horizon-content">
					<div class="horizon-icon">
						<i class="fa-solid fa-scale-balanced"></i>
					</div>
					<div class="horizon-info">
						<h3><?php echo $box10['heading']; ?></h3>
						<p><?php echo $box10['heading2']; ?></p>
						<div class="horizon-line"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Principles Section -->

<div class="ca-strategic-network">
	<div class="container">
		<div class="ca_modern_heading">
			<div class="ghost">LOCATIONS</div>
			<h3 class="title"><?php echo $box11['heading']; ?></h3>
			<div class="line"></div>
		</div>

		<div class="sn-blueprint-hub">
			<!-- Technical Background Grid -->
			<div class="sn-blueprint-grid"></div>

			<!-- Floating Architectural Overlays (Low Opacity) -->
			<div class="sn-blueprint-decor">
				<div class="decor-item item-1"><i class="fa-solid fa-industry"></i> <span>EST. 1984</span></div>
				<div class="decor-item item-2">ISO 9001:2015</div>
				<div class="decor-item item-3"><i class="fa-solid fa-location-crosshairs"></i> 62-KM</div>
				<div class="decor-item item-4">CPBM_NETWORK</div>
				<div class="decor-item item-5"><i class="fa-solid fa-truck-ramp-box"></i></div>
				<div class="decor-item item-6">HQ_KHI_01</div>
			</div>

			<!-- Connection Lines (Solid) -->
			<svg class="sn-blueprint-svg" viewBox="0 0 1000 600">
				<path class="blueprint-line" d="M500 300 L 150 450" stroke="rgba(25, 71, 130, 0.1)" stroke-width="2"
					fill="none" />
				<path class="blueprint-line" d="M500 300 L 850 450" stroke="rgba(25, 71, 130, 0.1)" stroke-width="2"
					fill="none" />
				<path class="blueprint-line" d="M500 300 L 850 150" stroke="rgba(25, 71, 130, 0.1)" stroke-width="2"
					fill="none" />
			</svg>

			<!-- Central Industrial Map -->
			<div class="blueprint-map-wrap">
				<img src="webImages/map.gif" alt="Strategic Network Map" class="blueprint-map-img">

				<!-- Map Pins -->
				<a href="https://www.google.com/maps/search/?api=1&query=Lakson+Square+Building+No.+2+Karachi" target="_blank" class="map-pin pin-karachi" style="top: 85%; left: 30%;">
					<div class="pin-label">Karachi HQ</div>
				</a>
				<a href="https://www.google.com/maps/search/?api=1&query=Century+Paper+%26+Board+Mills+Ltd+Kasur" target="_blank" class="map-pin pin-kasur" style="top: 25%; left: 70%;">
					<div class="pin-label">Kasur Mill</div>
				</a>
				<a href="https://www.google.com/maps/search/?api=1&query=14-Ali+Block+New+Garden+Town+Lahore" target="_blank" class="map-pin pin-lahore" style="top: 18%; left: 75%;">
					<div class="pin-label">Lahore Office</div>
				</a>
			</div>
 
			<!-- Architectural Info Cards -->
			<div class="blueprint-cards">
				<!-- Site 01 -->
				<a href="https://www.google.com/maps/search/?api=1&query=Lakson+Square+Building+No.+2+Karachi" target="_blank" class="blueprint-card" style="text-decoration: none; color: inherit;">
					<div class="card-num">01</div>
					<div class="card-body">
						<div class="card-icon"><i class="fa-solid fa-building-circle-check"></i></div>
						<div class="card-text">
							<h4><?php echo $box12['heading']; ?></h4>
							<p><?php echo $box12['heading2']; ?></p>
						</div>
					</div>
				</a>
 
				<!-- Site 02 -->
				<a href="https://www.google.com/maps/search/?api=1&query=Century+Paper+%26+Board+Mills+Ltd+Kasur" target="_blank" class="blueprint-card" style="text-decoration: none; color: inherit;">
					<div class="card-num">02</div>
					<div class="card-body">
						<div class="card-icon"><i class="fa-solid fa-industry"></i></div>
						<div class="card-text">
							<h4><?php echo $box13['heading']; ?></h4>
							<p><?php echo $box13['heading2']; ?></p>
						</div>
					</div>
				</a>
 
				<!-- Site 03 -->
				<a href="https://www.google.com/maps/search/?api=1&query=14-Ali+Block+New+Garden+Town+Lahore" target="_blank" class="blueprint-card" style="text-decoration: none; color: inherit;">
					<div class="card-num">03</div>
					<div class="card-body">
						<div class="card-icon"><i class="fa-solid fa-map-location-dot"></i></div>
						<div class="card-text">
							<h4><?php echo $box14['heading']; ?></h4>
							<p><?php echo $box14['heading2']; ?></p>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- /Strategic Network Section -->

<!-- Service Query Shortcode -->
<div class="fn_cs_service_query" id="products" data-mobile="disable" data-column-count="4">
	<div class="top_bar">
		<div class="t_inner">
			<div class="ca_modern_heading">
				<div class="ghost">PORTFOLIO</div>
				<h3 class="title">Market <span>Leader</span></h3>
				<div class="line"></div>
			</div>
			<span>We are the market leader in high-quality paper, board and packaging products in Pakistan.</span>
			<div class="owl_control">
				<div class="fn_prev"></div>
				<div class="fn_next"></div>
			</div>
		</div>
	</div>
	<div class="service_part">
		<div class="owl-carousel">
		    <?php
            $sqlProducts = "
                SELECT `prodet_id`, `prodet_name`, `slug`
                FROM `proudct_detail`
                WHERE `product_update` = '1'
            ";
            
            $productsData = $dbF->getRows($sqlProducts);
            
            foreach ($productsData as $product) {
            
                $productId = (int) $product['prodet_id'];
                $title = getTextFromSerializeArray($product['prodet_name']);
                $slug = $product['slug'];
                $sqlImage = "
                    SELECT `image`
                    FROM `product_image`
                    WHERE `product_id` = ?
                    ORDER BY `sort` ASC
                    LIMIT 1
                ";
                $imageData = $dbF->getRow($sqlImage, [$productId]);
                $image = WEB_URL . '/images/' .$imageData['image'];
                echo '<div class="item">
				<a class="full_link" href="'.$slug.'"></a>
				<div class="img_holder">
					<img src="webImages/thumb/480-700.jpg" alt="">
					<div class="abs_img" data-bg-img="'.$image.'"></div>
				</div>
				<div class="title">
					<h3>'.$title.'</h3>
				</div>
				<div class="view_more">
					<span class="more_link">
						<span class="text">View More</span>
						<span class="arrow"><img class="fn__svg" src="svg/arrow-r.svg" alt="svg" /></span>
					</span>
				</div>
			</div>
                ';
            }
            ?>
		</div>
	</div>
</div>

<div id="sustainability" class="ca-eco-sustainability">
	<div class="container">

		<div class="ca_modern_heading">
			<div class="ghost">COMMITMENT</div>
			<h3 class="title">Sustainability <span>Ecosystem</span></h3>
			<div class="line"></div>
		</div>

		<div class="eco-dashboard">

			<div class="eco-dashboard-main">
				<!-- Left Grid (3 Tiles) -->
				<div class="eco-info-grid eco-grid-left">
					<div class="eco-tile">
						<div class="eco-tile-icon"><i class="fa-solid fa-sun"></i></div>
						<div class="eco-tile-content">
							<h5>Solar Energy</h5>
							<p>Harnessing renewable resources to reduce reliance on traditional power and lower carbon
								emissions.</p>
						</div>
					</div>

					<div class="eco-tile">
						<div class="eco-tile-icon"><i class="fa-solid fa-droplet"></i></div>
						<div class="eco-tile-content">
							<h5>Water Initiative</h5>
							<p>Rs. 250M Waste Water Treatment Plant ensuring NEQS compliance and water fit for
								agriculture.</p>
						</div>
					</div>

					<div class="eco-tile">
						<div class="eco-tile-icon"><i class="fa-solid fa-venus"></i></div>
						<div class="eco-tile-content">
							<h5>Women Empowerment</h5>
							<p>Promoting gender equality (SDG 5) and inclusion through dedicated skill development
								programs.</p>
						</div>
					</div>
				</div>

				<!-- Center Visual Orbit -->
				<div class="eco-visual-wrap">
					<div class="eco-orbit">
						<div class="eco-node"><i class="fa-solid fa-solar-panel"></i></div>
						<div class="eco-node"><i class="fa-solid fa-person-breastfeeding"></i></div>
						<div class="eco-node"><i class="fa-solid fa-water-ladder"></i></div>
						<div class="eco-node"><i class="fa-solid fa-tree"></i></div>
					</div>
					<div class="eco-core">
						<div class="eco-core-content">
							<div class="eco-img-wrap">
								<img src="webImages/recycle.png" alt="Solar Power Plant">
							</div>
						</div>
					</div>
				</div>

				<!-- Right Grid (3 Tiles) -->
				<div class="eco-info-grid eco-grid-right">
					<div class="eco-tile">
						<div class="eco-tile-icon"><i class="fa-solid fa-arrows-spin"></i></div>
						<div class="eco-tile-content">
							<h5>Recycling</h5>
							<p>Leading the circular economy by transforming post-consumer waste into high-grade
								sustainable packaging.</p>
						</div>
					</div>

					<div class="eco-tile">
						<div class="eco-tile-icon"><i class="fa-solid fa-globe"></i></div>
						<div class="eco-tile-content">
							<h5>CDP Reporting</h5>
							<p>Strengthening environmental transparency through annual CDP disclosures on climate,
								water, forests and biodiversity performance.</p>
						</div>
					</div>

					<div class="eco-tile">
						<div class="eco-tile-icon"><i class="fa-solid fa-award"></i></div>
						<div class="eco-tile-content">
							<h5>EcoVadis Assessment</h5>
							<p>Driving continuous improvement through independent sustainability assessments covering
								environment, labor practices, ethics, and procurement.</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Bottom Row (1 Full Width Tile) -->
			<div class="eco-dashboard-bottom">
				<div class="eco-tile eco-tile-full">
					<div class="eco-tile-icon"><i class="fa-solid fa-clipboard-check"></i></div>
					<div class="eco-tile-content">
						<h5>Sedex 7.0</h5>
						<p>Promoting responsible business practices through ethical audits focused on labor standards,
							health & safety, environment, and business ethics.</p>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>
<!-- /Sustainability Ecosystem Section -->

<!-- Call to Action -->
<div class="fn_cs_call_to_action">
	<div class="container">
		<div class="cta_holder">
			<div class="title_holder">
				<h3>Committed to Packaging Excellence</h3>
				<p>Join our network of strategic alliances and experience the quality of Century Paper.</p>
			</div>
			<div class="link_holder">
				<a href="#about">Discover Our Heritage</a>
			</div>
		</div>
	</div>
</div>
<!-- /Call to Action -->

<!-- CEO Message Section -->
<div class="testimonial_section" id="ceo-message" data-bg-img="webImages/testimonial/bg.jpg">
	<div class="overlay"></div>
	<!-- CEO Message Shortcode -->
	<div class="fn_cs_single_testimonial">
		<div class="container">
			<div class="inner">
				<img class="fn__svg" src="svg/quotes.svg" alt="svg" />
				<div class="content_holder">
					<p>At Century Paper, we are dedicated to providing our customers with the best quality paper and
						board products. Our commitment to excellence, sustainability, and innovation drives us to be the
						market leader in Pakistan. We value our strategic partnerships and strive to deliver value to
						all our stakeholders while maintaining the highest ethical standards.</p>
					<h3>Aftab Ahmad</h3>
					<h5>Chief Executive Officer</h5>
				</div>
			</div>
		</div>
	</div>
	<!-- /CEO Message Shortcode -->
</div>
<!-- /CEO Message Section -->

<!-- Investors & Financial Disclosures Section -->
<div id="investors">
	<div class="container">

		<!-- Section Title (Standardized) -->
		<div class="ca_modern_heading">
			<div class="ghost">PORTAL</div>
			<h3 class="title"><?php echo $box15['heading']; ?></span></h3>
			<div class="line"></div>
		</div>

		<!-- PSX + SECP Highlight Bar -->
		<div class="inv-psx-bar">
			<div class="inv-psx-bar-left">
				<i class="fa-solid fa-chart-line inv-psx-bar-icon"></i>
				<div>
					<p class="inv-psx-bar-label">Pakistan Stock Exchange Listing</p>
					<h3 class="inv-psx-bar-ticker">CEPB</h3>
				</div>
			</div>
			<div class="inv-psx-bar-actions">
				<a href="https://dps.psx.com.pk/company/CEPB" target="_blank" class="inv-btn-psx">
					<i class="fa-solid fa-arrow-up-right-from-square"></i> PSX Data Portal
				</a>
				<a href="http://www.secp.gov.pk/" target="_blank" class="inv-btn-secp">
					<i class="fa-solid fa-circle-info"></i> SECP Compliance
				</a>
			</div>
		</div>

		<!-- Financial Report Cards -->
		<div class="inv-cards-grid">
			<?php echo $box15['text']; ?>
		</div>

		<!-- Regulatory Portals -->
		<div class="inv-portals-footer">
			<span class="inv-portals-label">Regulatory & Investor Education Portals</span>
			<div class="inv-portals-row">
				<a href="http://www.secp.gov.pk/" target="_blank" class="inv-portal-link">
					<i class="fa-solid fa-building-shield"></i> SECP Official
				</a>
				<a href="http://jamapunji.pk/" target="_blank" class="inv-portal-link">
					<i class="fa-solid fa-user-graduate"></i> Jamapunji
				</a>
				<a href="https://www.shareholderagahi.com/" target="_blank" class="inv-portal-link">
					<i class="fa-solid fa-handshake-angle"></i> Shareholder Agahi
				</a>
				<a href="https://dps.psx.com.pk/company/CEPB" target="_blank"
					class="inv-portal-link inv-portal-link--dark">
					<i class="fa-solid fa-chart-simple"></i> PSX Profile
				</a>
			</div>
		</div>

	</div>
</div>
<!-- /Investors & Financial Disclosures Section -->

<!-- News & Events Section -->
<div class="blog_section">
	<div class="overlay" data-bg-img="webImages/blog/map.png"></div>
	<!-- Main Title -->
	<div class="container">
		<div class="ca_modern_heading">
			<div class="ghost">UPDATES</div>
			<h3 class="title">News &amp; <span>Events</span></h3>
			<div class="line"></div>
		</div>
	</div>
	<!-- /Main Title -->

	<!-- Triple Blog Modern Shortcode -->
	<div class="fn_cs_triple_blog_modern fn_alpha" id="news">
		<div class="container">
			<div class="inner">

				<ul>
				<?php
        	    $sqlindustries = "SELECT * FROM `industries` WHERE publish='1'";
        	    $industriesData = $dbF->getRows($sqlindustries);
        	    foreach($industriesData as $industries){
        	        $industries_heading = getTextFromSerializeArray($industries['heading']);
        	        $industries_shortDesc = getTextFromSerializeArray($industries['shortDesc']);
        	        $industries_date = getTextFromSerializeArray($industries['date']);
        	        $industries_slug = getTextFromSerializeArray($industries['slug']);
        	        $industries_image = WEB_URL . '/images/' . $industries['image'];
        	        echo'<li>
						<div class="item">
							<div class="img_holder" data-bg-img="'.$industries_image.'">
								<div class="time">
									<span></span>';
									if (!empty($industries_date)) {
    $timestamp = strtotime($industries_date);
?>
    <h3><?= date('d', $timestamp); ?></h3>
    <h5><?= date('M', $timestamp); ?></h5>
    <h5><?= date('Y', $timestamp); ?></h5>
<?php
}
								echo'</div>
								<a href="'.$industries_slug.'"></a>
								<img src="'.$industries_image.'" alt="">
							</div>
							<div class="title_holder">
								<p class="t_header">'.$industries_heading.'</p>
								<h3><a href="'.$industries_slug.'">'.$industries_shortDesc.'</a></h3>
								<p class="t_footer"><a href="'.$industries_slug.'">Read More</a></p>
							</div>
						</div>
					</li>';
        	    }
        	    ?>
				</ul>

			</div>
		</div>
	</div>
	<!-- /Triple Blog Modern Shortcode -->

</div>
<!-- /News & Events Section -->

<!-- Clients & Partners Slider -->
<div class="fn_cs_clients_marquee">
	<div class="marquee_track">
	    <?php
	    $sqlBrands = "SELECT * FROM `brands` WHERE publish='1'";
	    $brandsData = $dbF->getRows($sqlBrands);
	    foreach($brandsData as $brands){
	        $brand_heading = getTextFromSerializeArray($brands['brand_heading']);
	        $brand_image = WEB_URL . '/images/' . $brands['image'];
	        echo'<img class="client_logo" src="'.$brand_image.'" alt="'.$brand_heading.'">';
	    }
	    ?>
	</div>
</div>
<?php
    include_once 'footer.php';
}
?>
