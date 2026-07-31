<?php

class web_gallery extends  object_class
{
    public $webClass;
    public $galleryC;
    function __construct()
    {
        parent::__construct('3');
        $this->webClass =   $GLOBALS['webClass'];

        //$this->functions->includeOnceCustom(ADMIN_FOLDER."/gallery/classes/gallery.class.php");
        //$this->galleryC = new gallery();
        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        $_w=array();
        // $_e    =   $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'Website Gallery');

    }

    public function albumPage($desc1){
        //{{albumAll}}
        if($this->functions->developer_setting('hasGalleryPage') == '1') {
            if (preg_match("@{{albumAll}}@i", $desc1)) {
                $gallery = $this->gallerySimple();
                $desc1 = str_replace('{{albumAll}}', $gallery, $desc1);
            }

            //{{album(albumName)}}
            $desc1 = $this->albumSinglePage($desc1);

            //{{albumPictures(albumName)}}
            $desc1 = $this->albumPicturesPage($desc1);
        }

        return $desc1;
    }

    private function albumPicturesPage($desc1){
        if(preg_match("@{{albumPictures(.*)}}@i",$desc1)) {
            //get album name
            $name1       =  $this->functions->get_string_between($desc1,"{{albumPictures(",")}}");
            $name       =   trim(strip_tags($name1));
            $gallery    =   $this->gallerySimple($name,true);
            $desc1      = str_replace("{{albumPictures($name1)}}", "$gallery", $desc1);
            if(preg_match("@{{albumPictures(.*)}}@i",$desc1)) {
                $desc1  = $this->albumPicturesPage($desc1);
            }
        }

        return $desc1;
    }

    private function albumSinglePage($desc1){
        if(preg_match("@{{albumSingle(.*)}}@i",$desc1)) {
            //get album name
            $name1       =  $this->functions->get_string_between($desc1,"{{albumSingle(",")}}");
            $name       =   trim(strip_tags($name1));
            $gallery    = $this->gallerySimple($name);
            $desc1      = str_replace("{{albumSingle($name1)}}", "$gallery", $desc1);
            if(preg_match("@{{albumSingle(.*)}}@i",$desc1)) {
                $desc1  = $this->albumSinglePage($desc1);
            }
        }

        return $desc1;
    }

    public function galleryMain($notIncludeFirstImageInInner=true,$galleryName = ''){
        if(!empty($galleryName)){
            $galleryName = " AND album = '$galleryName'";
        }
        $sql ="SELECT * FROM `gallery` WHERE publish = '1' $galleryName ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);
        if(empty($data)){
            return "";
        }
        foreach($data as $key=>$val){
            $id     = $val['gallery_pk'];

            $qry="SELECT * FROM  `gallery_images` WHERE `gallery_id` = '$id' ORDER BY sort ASC";
            $eData=$this->dbF->getRows($qry);

            if($this->dbF->rowCount>0){
                $first = true;
                foreach($eData as $key2=>$val2) {
                    $img    = $val2['image'];
                    $imgId  = $val2['img_pk'];
                    $alt    = $val2['alt'];

                    if(empty($img)){
                        continue;
                    }

                    if($first===true && $notIncludeFirstImageInInner===false){

                    }else {
                        $data[$key]['images'][$key2]['image'] = $img;
                        $data[$key]['images'][$key2]['imageId'] = $imgId;
                        $data[$key]['images'][$key2]['alt'] = $alt;
                    }

                    if($first){
                        $first = false;
                        $data[$key]['image'] = $img;
                        $data[$key]['imageId'] =  $imgId;
                        $data[$key]['alt'] = $alt;
                    }
                }
            }else{
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function gallerySimple($galleryName = '',$all=false){
        //$all show all images not album
        $temp   = "<div class='galleryMain container-fluid padding-0'>";
        $gallery        = $this->galleryMain(false,$galleryName);
        //var_dump($gallery);
        if(empty($gallery)){
            return "";
        }
        foreach($gallery as $val){
            $id = $val['gallery_pk'];


            $rel = "<div style='display:none'>";
            $relAll = "";
            if(isset($val['images'])) {
                foreach ($val['images'] as $val2) {
                    $id2     = $val2['imageId'];
                    $imageR = WEB_URL."/images/".$val2['image'];
                    $image  = $this->functions->resizeImage($val2['image'],'220','170',false);
                    $alt    = $val2['alt'];
                    if($all) {
                        $relAll    .= "
                                    <div class='gallerySingle col-md-3 col-sm-4 col-xs-6 thumb' id='gallery_$id2'>
                                        <a href='$imageR'  rel='gallery' class='grow thumbnail galleryFancyBox_$id' title='$alt' >
                                            <img src='$image' class='img-responsive'  title='$alt'  alt='$alt' />
                                        </a>
                                   </div>";
                    }
                    $rel    .= "<a href='$imageR'  rel='gallery' class='galleryFancyBox_$id' title='$alt' ></a>";
                }
            }
            $rel    .= "</div>";
            $imageR = $val['image'];
            $image  = $this->functions->resizeImage($imageR,'220','170',false);
            $alt    =   $val['alt'];
            $imageR = WEB_URL."/images/".$imageR;

            $temp .= "<div class='gallerySingle col-md-3 col-sm-4 col-xs-6 thumb' id='gallery_$id'>
                            <a href='$imageR'  rel='gallery'  class='grow thumbnail galleryFancyBox_$id' title='$alt'>
                                <img src='$image' class='img-responsive'  title='$alt'  alt='$alt' />
                            </a>
                            $rel
                        </div>
                        $relAll";

            $temp .= "
                <script>
                    $(document).ready(function() {
                        $('.galleryFancyBox_$id').fancybox();
                    });
                </script>
                ";
        }


        $temp .= "</div>

                ";

        return $temp;
    }

}

?>
