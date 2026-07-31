<?php
$Email =  $functions->ibms_setting('Email');
$contact =  $functions->ibms_setting('contact');
$address =  $functions->ibms_setting('address');
?>
<footer class="industify_fn_footer" id="contact">
    <div class="top_footer">
        <div class="top_footer_img" data-fn-bg-img="webImages/footer/bg.jpg"></div>
        <!-- SUBSCRIBE -->
        <div class="subscribe_f">
            <div class="container">
                <div class="subscribe_in">
                    <div class="s_left">
                        <img class="fn__svg" src="svg/open-book.svg" alt="svg" />
                        <p>Newsletter — get updates with latest topics</p>
                    </div>
                    <div class="s_right">
                        <div class="subscriber">
                            <input type="email" placeholder="Your e-mail address *" />
                            <input type="submit" value="Subscribe" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /SUBSCRIBE -->
        <!-- TRIPLE WIDGET -->
        <div class="footer_widget">
            <div class="container">
                <div class="inner">
                    <ul class="widget_area">
                        <li>
                            <div class="item">
                                <div class="logo">
                                    <a href="index.php"><img src="webImages/logo.png" alt="Century Paper"></a>
                                </div>
                                <div class="textwidget">
                                    <p>Century Paper & Board Mills Limited (CPBM), established in 1984, is the flagship
                                        company of the Lakson Group and a market leader in packaging boards in Pakistan.
                                    </p>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="item">
                                <div class="wid-title"><span>Contact Details</span></div>
                                <div class="textwidget">
                                    <p><strong>HEAD OFFICE:</strong><br><?php echo $address ?></p>
                                    <p>Tel: <a href="tel:<?php echo $contact ?>"><?php echo $contact ?></a><br>
                                        Fax: <a href="tel:+922135681163">+92 21 3568 1163</a><br>
                                        Email: <a href="mailto:<?php echo $Email ?>"><?php echo $Email ?></a>
                                    </p>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="item">
                                <div class="industify_fn_widget_business_hours">
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="https://sdms.secp.gov.pk/" target="_blank">
                                                    <img src="webImages/secp.png">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://jamapunji.pk/" target="_blank">
                                                    <img src="webImages/jama.jpg">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.shareholderagahi.com/" target="_blank">
                                                    <img src="webImages/pop1.png">
                                                </a>
                                            </li>
                                            <li class="psx-pill-wrap">
                                                <a href="https://dps.psx.com.pk/company/CEPB" target="_blank"
                                                    title="View CEPB on Pakistan Stock Exchange" class="psx-pill-badge">
                                                    <span class="psx-pill-left"><i class="fa-solid fa-chart-line"></i>
                                                        PSX Listed</span>
                                                    <span class="psx-pill-right">CEPB</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /TRIPLE WIDGET -->
    </div>
    <!-- BOTTOM -->
    <div class="footer_bottom">
        <div class="container">
            <div class="footer_bottom_in">
                <div class="bottom_widget">
                    <div class="widget_nav_menu">
                        <ul class="menu">
                            <?php
                            $mainMenu = $menuClass->footerMainSingleMenu();
                            if (!empty($mainMenu) && is_array($mainMenu)) {
                            foreach ($mainMenu as $val) {
                                $menuId   = $val['id'];
                                $menuName = getTextFromSerializeArray($val['menu']);
                                $menulink = getTextFromSerializeArray($val['link']);
                                ?>
                                <li><a href="<?php echo $menulink ?>"><?php echo $menuName ?></a></li>
                                <?php
                                }
                                }   
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="footer_copyright">
                    <p>&copy; 1984 – 2026 Century Paper & Board Mills Limited. All rights reserved | Developed by <a
                            href="https://imdigitalagency.com" target="_blank"><img
                                src="webImages/developed-by-interactive-media.png" style="max-height:40px;"></a></p>
                </div>
                <a class="industify_fn_totop" href="#">
                    <span class="top"></span>
                    <span class="text">To Top</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /BOTTOM -->
</footer>
<!-- /Site Footer -->



</div>
<!-- /Wrapper -->


</div>
<!-- /Wrapper All -->


<!-- Scripts -->
<script type="text/javascript" src="js/jquery592e.js?ver=3"></script>
<script type="text/javascript" src="js/justified592e.js?ver=3"></script>
<script type="text/javascript" src="js/waypoints592e.js?ver=3"></script>
<script type="text/javascript" src="js/countto592e.js?ver=3"></script>
<script type="text/javascript" src="js/magnific-popup592e.js?ver=3"></script>
<script type="text/javascript" src="js/kenburnsy592e.js?ver=3"></script>
<script type="text/javascript" src="js/isotope592e.js?ver=3"></script>
<script type="text/javascript" src="js/lightgallery592e.js?ver=3"></script>
<script type="text/javascript" src="js/swiper592e.js?ver=3"></script>
<script type="text/javascript" src="js/parallax592e.js?ver=3"></script>
<script type="text/javascript" src="js/owl-carousel592e.js?ver=3"></script>
<script type="text/javascript" src="js/init592e.js?ver=3"></script>
<script src="js/main.js?ver=<?php echo filemtime('js/main.js'); ?>"></script>
<!-- /Scripts -->

</body>

</html>