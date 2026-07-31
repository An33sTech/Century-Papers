<?php 
ob_start();
?>

<div class="governance_section pb-80">
    <div class="container">
        
        <div class="governance_intro">
            <div class="ca_modern_heading">
                <div class="ghost">GALLERY</div>
                <span class="tag">Events, Awards &amp; Trainings</span>
                <h3 class="title">Media <span>Gallery</span></h3>
                <div class="line"></div>
            </div>
            <div class="blueprint_desc">
                <p>Browse through high-quality photos highlighting our corporate awards, employee safety training sessions, and developmental activities conducted across our national mill units.</p>
            </div>
        </div>

        <!-- LightGallery Popup Wrapper -->
        <div class="fn_cs_lightgallery">
            <div class="media_grid">
                <?php
                $sql = "SELECT * FROM `gallery_images`";
                $data = $dbF->getRows($sql);
                foreach($data as $images){
                    $image = WEB_URL . '/images/' . $images['image'];
                    $alt = $images['alt'];
                    echo'<div class="media_item">
                    <a class="lightbox" href="'.$image.'">
                        <div class="img_wrap">
                            <img src="'.$image.'" alt="'.$alt.'" loading="lazy" />
                            <div class="zoom_icon">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                    </a>
                </div>';
                }
                ?>
            </div>
        </div>

    </div>
</div>

<?php 
return ob_get_clean();
?>