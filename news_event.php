<?php
ob_start();
?>

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
<?php
return ob_get_clean();
?>