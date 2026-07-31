<?php
ob_start();
$box1  = $webClass->getBox('box1');
$box8  = $webClass->getBox('box8');
$box9  = $webClass->getBox('box9');
$box10 = $webClass->getBox('box10');
$box11 = $webClass->getBox('box11');
?>

<div class="about_section innerpage-aboutsection" id="about">
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

<!-- Principles -->
<div class="industify_fn_principles">
    <div class="container">
        <div class="principles">
            <ul>
                <li>
                    <div class="item">
                        <div class="item_left">
                            <h2>01.</h2>
                            <h3>Vision</h3>
                        </div>
                        <div class="item_right">
                            <p>To be the market leader and an enduring force in the paper, board and packaging industry, positively influencing and providing value to our stakeholders, society and our nation.</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <div class="item_left">
                            <h2>02.</h2>
                            <h3>Mission</h3>
                        </div>
                        <div class="item_right">
                            <p>To strive incessantly for excellence and sustain our position as a preferred supplier of quality paper, board and packaging material within a team environment and with a customer focussed strategy.</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Principles -->

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

<!-- /Service Query Shortcode --><!-- Sustainability Ecosystem Section -->
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
<?php
return ob_get_clean();
?>