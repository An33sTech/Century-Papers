<?php 
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

include_once("global.php");
global $webClass;

if(!isset($_GET['page']) || $_GET['page']==''){
    header("HTTP/1.0 404 Not Found");
}

//var_dump($seo);

$pg         = $_GET['page'];
$page       = $webClass->getPage("$pg");
$pg_id      = $page['id'];
$setting_field  = $functions->setting_fieldsGet($pg_id,'pages');
$loginReq       = $functions->setting_fieldsArray($setting_field,'loginReq');
$icons          = $functions->setting_fieldsArray($setting_field,'icon');

if( $loginReq == '1' ){
    if(!userLoginCheck()){
        header('Location: '.WEB_URL.'/login');
    }
}

//Redirect If link
$redirectLink = $page['link'];
if($redirectLink!=''){
    header("Location: $redirectLink");
    exit;
}

global $seo;
if($seo['title']==''  || $seo['reWriteTitle']=='0'){
    $seo['title'] = $page['heading'];
}
if($seo['description']=='' || $seo['default']=='1'){
    //$seo['description'] = substr(trim(strip_tags($page['desc'])),0,250);
    $seo['description'] = substr(trim(strip_tags($page['desc'])),0,500); //500 for facebook share
}

if($page['comment']=='1'){
    $functions->require_once_custom('webBlog_functions');
    $blogC = new webBlog_functions();
    $reviewMsg = $blogC->reviewSubmit();
    $reviews =  $blogC->reviews($pg,'page',2);
    $reviews = '<div class="clearfix"></div><br><div class="pageReview container-fluid padding-0 table-bordered">'.$reviews.'</div><div class="clearfix"><br><br></div>';
}else{
    $reviews = '';
}


$desc1 =  ($page['desc']);
if(stristr($desc1,'{{contactForm}}')){
    $contact = include_once(__DIR__.'/contact.php');
    $desc1       = str_replace('{{contactForm}}',$contact,$desc1);
}
// $desc1 =  ($page['desc']);
// if(stristr($desc1,'{{Service}}')){
//         $services = include_once(__DIR__ . '/services.php');
//         $desc1 = str_replace('{{Service}}', $services, $desc1);
// }
if(stristr($desc1,'{{about}}')){
        $about = include_once(__DIR__ . '/about-us.php');
        $desc1 = str_replace('{{about}}', $about, $desc1);
}

if(stristr($desc1,'{{media}}')){
        $media = include_once(__DIR__ . '/media.php');
        $desc1 = str_replace('{{media}}', $media, $desc1);
}

if(stristr($desc1,'{{career}}')){
        $career = include_once(__DIR__ . '/careers.php');
        $desc1 = str_replace('{{career}}', $career, $desc1);
}


if(stristr($desc1,'{{news_events}}')){
        $news_events = include_once(__DIR__ . '/news_event.php');
        $desc1 = str_replace('{{news_events}}', $news_events, $desc1);
}

if(stristr($desc1,'{{client}}')){
        $clients = include_once(__DIR__ . '/client.php');
        $desc1 = str_replace('{{client}}', $clients, $desc1);
}


if(stristr($desc1,'{{files-Manager}}')){
    if($functions->developer_setting('filesManagerPage') == '1') {
        $employee = include_once(__DIR__ . '/files-Manager.php');
        $desc1 = str_replace('{{files-Manager}}', $employee, $desc1);
    }
}

if(stristr($desc1,'{{testimonial}}')){
    if($functions->developer_setting('testimonialPage') == '1') {
        $employee = include_once(__DIR__ . '/testimonial.php');
        $desc1 = str_replace('{{testimonial}}', $employee, $desc1);
    }
}

if(preg_match("@{{album.*}}@i",$desc1)){
    $functions->modelFunFile('webGallery_functions.php');
    $galleryC = new web_gallery();
    $desc1 = '{{albumSingle(gallery)}}';
    $desc1 = $galleryC->albumPage($desc1);
}

    $bannerImg = WEB_URL . '/images/' . $page['image'];
    $shrtDesc  = $page['short_desc'];
    $subHeading  = $page['heading2'];
    $Heading  = $page['heading'];


  if (!$isAjax) {
        include_once 'header.php';
    }?>

<!--Inner Container Starts-->
<?php $webClass->seoSpecial();
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
        <!-- Recycling Symbol -->
        <div class="decor_item recycling_icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 12V20C4 20.5523 4.44772 21 5 21H13" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20 12V4C20 3.44772 19.5523 3 19 3H11" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 8L20 4L16 0" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8 16L4 20L8 24" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17 13.5L12 22L7 13.5H17Z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Paper Sheet 1 -->
        <div class="decor_item paper_1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                    d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 2V8H20" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 13H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 17H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10 9H8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Paper Sheet 2 -->
        <div class="decor_item paper_2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                    d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 2V8H20" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 13H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 17H8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div class="container">
        <div class="title_holder">
            <h3><?php echo $Heading ?></h3>
            <div class="industify_fn_breadcrumbs">
                <ul>
                    <li><a href="<?php echo WEB_URL ?>" title="Home">Home</a></li>
                    <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                    <li><span class="bread-current"><?php echo $Heading ?></span></li>
                </ul>
            </div>

        </div>
    </div>
</div>
<?php echo $desc1; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.history-tab-btn');
    const panels = document.querySelectorAll('.history-tab-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and panels
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            // Add active class to clicked tab
            tab.classList.add('active');

            // Show corresponding panel with fade effect
            const targetPanel = document.getElementById(tab.dataset.tab + '-panel');
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });
});
</script>
<?php 
  if (!$isAjax) {
        include_once 'footer.php';
    }
?>