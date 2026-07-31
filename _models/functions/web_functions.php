<?php
class web_functions extends object_class
{
    use common_function;
    private $webProduct_handle;
    private $hasSeo;

    function __construct()
    {
        parent::__construct('3');
        $this->setMultiLanguage();
        //header('Content-Type: text/html; charset=utf-8');
        //header('Content-Type: text/html; charset=latin1');
        //return webUser Data
        $currentUser = webUserSession();


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        $_w = array();
        $_w['My Account'] = '';
        $_w['Wishlist'] = '';
        $_w['Compare'] = '';
        $_w['Items'] = '';
        $_w['LogOut'] = '';
        $_w['Search'] = '';
        $_w['WELCOME'] = '';
        $_w['Visitor'] = '';
        $_w['All right reserved'] = '';
        $_w['Customer Support'] = '';
        $_w['SUBSCRIBE'] = '';
        $_w['Enter Email'] = '';
        $_w['Categories'] = '';
        $_w['Reset Search'] = '';
        $_w['Filter Search'] = '';
        $_w['Cart'] = '';
        $_w['KUNDTJANST'] = '';
        $_w['Need Help'] = '';
        $_w['Link'] = '';
        $_w['CHECKOUT'] = '';
        $_w['LOGIN'] = '';
        $_w['REGISTER'] = '';
        $_w['VIEW CART'] = '';
        $_w['Links'] = '';
        $_w['Return Policy'] = '';
        $_w['Shipping Info'] = '';
        $_w['About Us'] = '';
        $_w['Home'] = '';
        $_w['CONTACT US'] = '';
        $_w['Follow Us'] = '';
        $_w['FAQ'] = '';
        $_w['LATEST NEWS'] = '';
        $_w['Subscribe to Our Newsletter'] = '';
        $_w['Thank You For Subscribe.'] = '';
        $_w['Subscribe Fail, You Already Subscribe.'] = '';
        $_w['Send'] = '';
        $_w['CUSTOMER SUPPORT'] = '';
        $_w['MY SHOPPING CART'] = '';
        $_w['Select Language'] = '';
        $_w['Member Login'] = '';
        $_w['CONTINUE SHOPPING'] = '';
        $_w['SELECT YOUR COUNTRY:'] = '';
        $_w['Your Currency'] = '';
        $_w['Total'] = '';
        $_w['SHOPPING CART'] = '';
        $_w['SUMMARY'] = '';
        $_w['Quantity'] = '';
        $_w['No Of Items'] = '';
        $_w['CONTINUE SHOPPING'] = '';
        $_w['GO TO CHECKOUT'] = '';
        $_w['SUMMARY'] = '';
        $_w['Best Seller'] = '';
        $_e = $this->dbF->hardWordsMulti($_w, currentWebLanguage(), 'Website');
    }

    public function p404()
    {
        header("Location: " . WEB_URL . "/data/404");
    }

    public function ibms_setting($name)
    {
        return $this->functions->ibms_setting($name);
    }

    public function hardWords($en, $echo = true)
    {
        return $this->dbF->hardWords($en, $echo);
    }

    public function get_searchVal()
    {
        if (isset($_GET['s']) && $_GET['s'] != '')
            return $_GET['s'];
        return "";
    }
    /**
     * @param $image
     * @param string $width
     * @param string $height
     * @param bool $echo
     * @param bool $cache
     * @param bool $imageWithWebUrl
     * @return string
     * Bing image to small, not small to big
     */
    public function resizeImage($image, $width = 'auto', $height = 'auto', $echo = true, $pngBgColor = false, $imageWithWebUrl = true, $cache = true)
    {
        //It has one setting also in src/image.php development folder name
        return $this->functions->resizeImage($image, $width, $height, $echo, $pngBgColor, $imageWithWebUrl, $cache);
    }

    public function webUserId()
    {
        return webUserId();
    }
    public function webUserOldTempId()
    {
        $userData = webUserSession();
        return $userData['oldTempId'];
    }

    public function webTempUserId()
    {
        return webTempUserId();
    }

    public function userLoginCheck()
    {
        return userLoginCheck();
    }

    public function userLogin($checkToken = true)
    {
        if (
            isset($_POST['email']) && !empty($_POST['email'])
            && isset($_POST['pass']) && !empty($_POST['pass'])
        ) {
            if ($checkToken == true) {
                if (!$this->functions->getFormToken('signInUser')) {
                    return false;
                }
            }

            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $pass = $this->functions->encode($pass);
            $sql = "SELECT * FROM `accounts_user` WHERE acc_email = '$email' And acc_pass = '$pass' AND acc_type != 0";
            $data = $this->dbF->getRow($sql);

            if ($this->dbF->rowCount > 0) {
                $this->cartUpdateTempUser($data);
                $this->cartWishListUpdateTempUser($data);
                $this->create_webLogin_session($data);

                $_SESSION['webUser']['remember'] = '0';
                if (isset($_POST['remember'])) {
                    $_SESSION['webUser']['remember'] = '1';
                    setRememberMe("webUser", $_SESSION['webUser'], 90);
                }
                return true;
            } else {
                $var = $this->dbF->hardWords('Account Not Found Or Account Not Verify.', false);
                return $var;
            }
        }
    }

    public function userLogOut($sessionDestroy = false)
    {
        if ($sessionDestroy) {
            session_unset();
            session_destroy();
        } else {
            $_SESSION['webUser'] = 'logout';
        }
    }

    public function create_webLogin_session($data)
    {
        $_SESSION['webUser']['login'] = '1';
        $_SESSION['webUser']['id'] = $data['acc_id'];
        $_SESSION['webUser']['oldTempId'] = $_SESSION['webUser']['tempId'];
        $_SESSION['webUser']['tempId'] = "";
        $_SESSION['webUser']['email'] = $data['acc_email'];
        $_SESSION['webUser']['name'] = $data['acc_name'];
        $_SESSION['webUser']['type'] = $data['acc_type'];

        $sql = "SELECT * FROM accounts_user_detail WHERE id_user = '$data[acc_id]' AND setting_name = 'user_type'";
        $data = $this->dbF->getRow($sql);
        if ($this->dbF->rowCount > 0) {
            $user_type = $data["setting_val"];
        } else {
            $user_type = false;
        }
        $_SESSION['webUser']['user_type'] = $user_type;
    }

    public function cartUpdateTempUser($data)
    {
        if ($this->functions->developer_setting('product') == '1') {
            $userId = $data['acc_id'];
            $userData = webUserSession();
            $tempId = $userData['tempId'];

            $sql = "UPDATE `cart` SET `userId` = ?, `tempUser` = ? WHERE `tempUser`= ?";
            $this->dbF->setRow($sql, array($userId, '', $tempId));
        }
    }

    public function cartWishListUpdateTempUser($data)
    {
        if ($this->functions->developer_setting('product') == '1') {
            $userId = $data['acc_id'];
            $userData = webUserSession();
            $tempId = $userData['tempId'];

            $sql = "UPDATE `cartwishlist` SET
                                userId = ?,
                                tempUser  = ?
                                  WHERE `tempUser`= ?";
            $this->dbF->setRow($sql, array($userId, '', $tempId));
        }
    }

    public function web_banners($columns = '*', $publish = true)
    {
        /**
         * $return All,but main array is, [title],[image],[text],[link];
         */
        if ($publish) {
                $where = " WHERE publish = '1'";
        } else {
            $where = '';
        }
        $sql = "SELECT $columns FROM banners $where ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);

        if ($this->dbF->rowCount > 0) {
            $i = 0;
            $data2 = $data; //for save $data array
            foreach ($data2 as $val) {

                //get title and set into data so developer no need to translate it
                if (isset($val['banner_heading'])) {
                    $title = getTextFromSerializeArray($val['banner_heading']);
                    $data[$i]['title'] = $title;
                }

                //get shortDesc and set into data so developer no need to translate it
                if (isset($val['banner_subheading'])) {
                    $subtitle = getTextFromSerializeArray($val['banner_subheading']);
                    $data[$i]['subtitle'] = $subtitle;
                }
                if (isset($val['layer0'])) {
                    $temp = getTextFromSerializeArray($val['layer0']);
                    $data[$i]['layer0'] = $this->addWebUrlInLink($temp);
                }
                //LINK
                if (isset($val['banner_link'])) {
                    $lang = currentWebLanguage();
                    
                    $datas = @unserialize($val['banner_link']);
                    if ($datas !== false) {
                        $link = unserialize($val['banner_link']);
                        $data[$i]['link'] = $this->functions->addWebUrlInLink($link[$lang]);
                    } else {
                        $data[$i]['link'] = $this->functions->addWebUrlInLink($val['banner_link']);
                    }
                }
                $i++;
            }
        }
        return $data;
    }

    public function web_brands($columns = '*', $publish = true)
    {
        /**
         * $return All,but main array is, [title],[image],[text],[link];
         */
        if ($publish) {
            $where = " WHERE publish = '1'";
        } else {
            $where = '';
        }
        $sql = "SELECT $columns FROM brands $where  ORDER BY sort ASC";
        $data = $this->dbF->getRows($sql);
        if ($this->dbF->rowCount > 0) {
            $i = 0;
            $data2 = $data; //for save $data array
            foreach ($data2 as $val) {
                //get title and set into data so developer no need to translate it
                @$title = getTextFromSerializeArray($val['brand_heading']);
                $data[$i]['title'] = $title;

                //get shortDesc and set into data so developer no need to translate it
                @$desc = getTextFromSerializeArray($val['brand_shrtDesc']);
                $data[$i]['text'] = $desc;

                //LINK
                $data[$i]['link'] = $val['brand_link'];
                $i++;
            }
        }
        return $data;
    }


    public function web_brandsDiv()
    {
        $brands = $this->web_brands();
        $temp = '';
        foreach ($brands as $val) {
            $image = WEB_URL . "/images/" . $val['image'];
            $link = $val['link'];
            $text = $val['text'];
            $title = $val['title'];
            $lang = currentWebLanguage();
            @$link = unserialize($link);
            @$link = $link[$lang];

            $temp .= '<a href="' . $link . '" rel="nofollow"><img src="' . $image . '" alt="' . $title . '"></a>';
        }
        return $temp;
    }


    /**
     * @param $boxName
     * @param bool $textCharacters
     * @param bool $headingCharacter
     * @param bool $subHeadingCharacters
     * @return array
     */
    public function getBox($boxName, $textCharacters = false, $headingCharacter = false, $subHeadingCharacters = false, $lang = false)
    {
        /* Will Return
            $array['heading']
            $array['heading2']
            $array['text']
            $array['link']
            $array['linkText']
            $array['image']  ;
        */

        $sql = "SELECT * FROM `box` WHERE box ='$boxName'";
        $data = $this->dbF->getRow($sql);
        $webLang = $lang ? $lang : currentWebLanguage();

        $heading = translateFromSerialize($data['heading'], true, false, $webLang);
        $sub_heading = translateFromSerialize($data['sub_heading'], true, false, $webLang);
        $short_desc = translateFromSerialize($data['short_desc'], true, false, $webLang);
        $linkText = translateFromSerialize($data['linktext'], true, false, $webLang);

        $heading = ($headingCharacter != false) ? substr($heading, 0, $headingCharacter) : $heading;
        $sub_heading = ($subHeadingCharacters != false) ? substr($sub_heading, 0, $subHeadingCharacters) : $sub_heading;
        $short_desc = ($textCharacters != false) ? substr($short_desc, 0, $textCharacters) : $short_desc;

        //Link
        $link = translateFromSerialize($data['redirect'], true, false, $webLang);
        if (preg_match('@http://@i', $link) || preg_match('@https://@i', $link)) {
        } else {
            $link = WEB_URL . $link;
        }

        $array = [];
        $array['heading'] = $heading;
        $array['heading2'] = $sub_heading;
        $array['text'] = $short_desc;
        $array['link'] = $link;
        $array['linkText'] = $linkText;
        $array['image'] = WEB_URL . '/images/' . $data['image'];


        return $array;
    }

    /**
     * @param $returnStatus
     * @return bool
     */
    public function subscribeFormSubmit($returnStatus = false)
    {
        if (isset($_POST['subscribeEmail']) && isset($_POST['subscribeEmailButton'])) {
            global $_e;
            if (!$this->getFormToken('SubscribeForm')) {
                return false;
            }

            $email = $_POST['subscribeEmail'];
            $sql = "INSERT INTO email_subscribe (email)
                        VALUES (?)";
            $array = array($email);
            $this->dbF->setRow($sql, $array);
            if (!$this->dbF->hasException) {
                if ($returnStatus) {
                    return true;
                } else {
                    echo "<script>
                            $(document).ready(function(){
                                jAlertifyAlert('" . _js($_e['Thank You For Subscribe.']) . "');
                            });
                            </script>";
                }

                $verifyLink = WEB_URL . "/verify?sEmail=$email";
                $mailArray['link'] = $verifyLink;
                $this->functions->send_mail($email, '', '', 'subscribeEmail', '', $mailArray);
            } else {
                if ($returnStatus) {
                    return false;
                } else {
                    echo "<script>
                            $(document).ready(function(){
                                jAlertifyAlert('" . _js($_e['Subscribe Fail, You Already Subscribe.']) . "');
                            });
                            </script>";
                }
            }
        }
    }

    public function longWaitFormSubmit($returnStatus = false)
    {
        if (isset($_POST['longWaitSubscribe'])) {
            global $_e;
            if (!$this->getFormToken('LongWaitForm')) {
                return false;
            }

            $_SESSION['webUser']['longTime_popup'] = 1;

            $l_name = $_POST['l_name'];
            $l_email = $_POST['l_email'];
            $l_phone = $_POST['l_phone'];

            $sql = "INSERT INTO `long_time_subscribe`(`email`, `name`, `phone_no`) VALUES (?,?,?)";

            $array = array($l_email, $l_name, $l_phone);
            $this->dbF->setRow($sql, $array);
            if (!$this->dbF->hasException) {
                if ($returnStatus) {
                    return true;
                } else {
                    echo "<script>
                            $(document).ready(function(){
                                jAlertifyAlert('" . _js($_e['Thank You For Subscribe.']) . "');
                            });
                        </script>";
                }
            } else {
                if ($returnStatus) {
                    return false;
                } else {
                    echo "<script>
                            $(document).ready(function(){
                                jAlertifyAlert('" . _js($_e['Subscribe Fail, You Already Subscribe.']) . "');
                            });
                            </script>";
                }
            }
        }
    }


    public function getPage($page)
    {
        $sql = "SELECT * FROM `pages` WHERE slug = ? AND publish = '1'";
        $data = $this->dbF->getRow($sql, array($page));

        if (!$this->dbF->rowCount) {
            return false;
        }

        $lang = currentWebLanguage();
        $defaultLang = defaultWebLanguage();

        $heading = translateFromSerialize($data['heading']);
        $sub_heading = translateFromSerialize($data['sub_heading']);
        $short_desc = translateFromSerialize($data['short_desc']);
        $desc = translateFromSerialize(base64_decode($data['dsc']));

        //Link
        $link = $data['redirect'];
        if (preg_match('@http://@i', $link) || preg_match('@https://@i', $link) || $link == '') {
        } else {
            $link = WEB_URL . $link;
        }

        $array = array();
        $array['id'] = $data['page_pk'];
        $array['link'] = $link;
        $array['slug'] = $data['slug'];
        $array['heading'] = $heading;
        $array['heading2'] = $sub_heading;
        $array['short_desc'] = $short_desc;
        $array['desc'] = $desc;
        $array['image'] = $data['page_banner'];
        $array['comment'] = $data['comment'];
        $array['update'] = $data['dateTime'];

        return $array;
    }



    public function vcode($user, $email)
    {
        $v = round(str_replace("-", "", (crc32(md5($user . $email . 'imedia'))) / 7923));
        return $v;
    }


    public function webSeo()
    {
        $array = array();
        $link = "" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI'];
        $link = str_replace("http://", "", $link);
        $link = str_replace("https://", "", $link);
        $link = $this->functions->defaultHttp . "" . $link;
        $link = urldecode($link);
        $link = str_replace(WEB_URL, "", $link);
        if ($link == "/" || $link == "/home" || $link == "/index" || $link == "index.php") {
            $link = '/';
        }


        $lang = currentWebLanguage();
        $sql_seo_slug = "SELECT `seo_id` FROM seo_slug WHERE `slug` = ? and `lang`= ?";
        $check_seo_slug = $this->dbF->getRow($sql_seo_slug, array("$link", "$lang"));
        if ($this->dbF->rowCount > 0) {
            $sql = "SELECT * FROM seo WHERE id = '$check_seo_slug[seo_id]'";
            $data = $this->dbF->getRow($sql);
        } else {
            $sql = "SELECT * FROM seo WHERE pageLink LIKE ? AND publish = '1'";
            $data = $this->dbF->getRow($sql, array("%$link%"));
        }



        if ($this->dbF->rowCount > 0) {
            $array['default'] = '0';
        } else {
            $sql_seo_slug = "SELECT `seo_id` FROM seo_slug WHERE `slug` = ? and `lang`= ?";
            $check_seo_slug = $this->dbF->getRow($sql_seo_slug, array("$link", "$lang"));
            if ($this->dbF->rowCount > 0) {
                $sql = "SELECT * FROM seo WHERE id = '$check_seo_slug[seo_id]'";
                $data = $this->dbF->getRow($sql);
            } else {

                $explod = explode('?', $link);
                $link = preg_replace("/.php/", "", $explod[0]); //removing .php from link

                $sql = "SELECT * FROM seo WHERE pageLink = ? AND publish = '1'";
                $data = $this->dbF->getRow($sql, array($link));
            }


            if ($this->dbF->rowCount > 0) {
                $array['default'] = '0';
            } else {
                $sql = "SELECT * FROM seo WHERE pageLink = '/default' AND publish = '1'";
                $data = $this->dbF->getRow($sql, array($link));
                $array['default'] = '1';
            }
        }

        $robots = "";
        if ($data['sIndex'] == '1') {
            $robots .= "index,";
        } else if ($data['sIndex'] == '0') {
            $robots .= "noindex,";
        }
        if ($data['sFollow'] == '1') {
            $robots .= " follow";
        } else if ($data['sFollow'] == '0') {
            $robots .= " nofollow";
        }

        $array['title'] = translateFromSerialize($data['title']);
        $array['special'] = translateFromSerialize($data['special']);
        $array['keywords'] = translateFromSerialize($data['keywords']);
        $array['description'] = translateFromSerialize($data['dsc']);
        $array['canonical'] = translateFromSerialize($data['canonical']);
        $array['sIndex'] = $data['sIndex'];
        $array['type'] = $data['type'];
        $array['sFollow'] = $data['sFollow'];
        $array['reWriteTitle'] = $data['rewriteTitle']; //rewrite Title with seo title
        $array['author'] = translateFromSerialize($data['author']);
        $array['revisit-after'] = $data['revisit-after'];
        $array['robots'] = $robots;
        $array['link'] = $this->currentUrl(false);


        $array['b1'] = $data['b1'];
        $array['b2'] = $data['b2'];
        $array['l1'] = $data['l1'];
        $array['l2'] = $data['l2'];
        $array['seoPK'] = $data['id'];



        $this->hasSeo = true;
        if ($this->functions->developer_setting('seo') == '0') {
            //if seo 0 from developer setting then blank all seo array
            $this->hasSeo = false;
        }
        return $array;
    }

    public function webSeoNew()
    {
        $array = [];
        $link = "" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI'];
        $link = str_replace("http://", "", $link);
        $link = str_replace("https://", "", $link);
        $link = $this->functions->defaultHttp . "" . $link;
        $link = urldecode($link);
        $link = str_replace(WEB_URL, "", $link);
        if ($link == "/" || $link == "/home" || $link == "/index" || $link == "index.php") {
            $link = '/';
        } else {
            $link = ltrim($link, "/");
        }



        $lang = currentWebLanguage();

        $sql_seo_slug = "SELECT `seo_id` FROM seo_slug WHERE `slug` = ? and `lang`= ?";
        $check_seo_slug = $this->dbF->getRow($sql_seo_slug, array("$link", "$lang"));
        if ($this->dbF->rowCount > 0) {
            $sql = "SELECT * FROM seo WHERE id = '$check_seo_slug[seo_id]'";
            $data = $this->dbF->getRow($sql);
        } else {
            $sql = "SELECT * FROM seo WHERE (`slug` = ? || `pageLink` LIKE ?) AND publish = '1'";
            $data = $this->dbF->getRow($sql, array("$link", "%$link%"));
        }



        if ($this->dbF->rowCount > 0) {
            $array['default'] = '0';
        } else {


            $sql_seo_slug = "SELECT `seo_id` FROM seo_slug WHERE `slug` = ? and `lang`= ?";
            $check_seo_slug = $this->dbF->getRow($sql_seo_slug, array("$link", "$lang"));
            if ($this->dbF->rowCount > 0) {
                $sql = "SELECT * FROM seo WHERE id = '$check_seo_slug[seo_id]'";
                $data = $this->dbF->getRow($sql);
            } else {

                $explod = explode('?', $link);
                $link = preg_replace("/.php/", "", $explod[0]); //removing .php from link

                $sql = "SELECT * FROM seo WHERE (`slug` = ? || `pageLink` LIKE ?) AND publish = '1'";
                $data = $this->dbF->getRow($sql, array("$link", "%$link%"));
            }

            if ($this->dbF->rowCount > 0) {
                $array['default'] = '0';
            } else {
                $uri_segments = explode('/', $link);
                $segment = end($uri_segments);
                $sql = "SELECT * FROM seo WHERE slug = '$segment' AND publish = '1'";
                $data = $this->dbF->getRow($sql);
                $array['default'] = '0';
                
                if($this->dbF->rowCount == 0){
                    $sql = "SELECT * FROM seo WHERE `pageLink` = '/default' AND publish = '1'";
                    $data = $this->dbF->getRow($sql);
                    $array['default'] = '1';
                }
            }
        }

        $robots = "";
        if (@$data['sIndex'] == '1') {
            $robots .= "index,";
        } else if (@$data['sIndex'] == '0') {
            $robots .= "noindex,";
        }
        if (@$data['sFollow'] == '1') {
            $robots .= " follow";
        } else if (@$data['sFollow'] == '0') {
            $robots .= " nofollow";
        }
        if (isset($data['canonical']) && is_string($data['canonical']) && stripos($data['canonical'], WEB_URL) === false) {
            $data['canonical'] = str_replace("https://sharkspeed.se", WEB_URL, $data['canonical']);
        }
        @$array['title'] = translateFromSerialize($data['title']);
        @$array['seo_title'] = translateFromSerialize($data['seo_title']);
        @$array['special'] = translateFromSerialize($data['special']);
        @$array['keywords'] = translateFromSerialize($data['keywords']);
        @$array['description'] = translateFromSerialize($data['dsc']);
        @$canonical = translateFromSerialize($data['canonical']);
        
        if (!empty($canonical) && strpos($canonical, WEB_URL) !== 0) {
            @$array['canonical'] = WEB_URL . '/' . $canonical;
        } else {
            @$array['canonical'] = $canonical;
        }
        
        @$array['sIndex'] = @$data['sIndex'];
        @$array['type'] = @$data['type'];
        @$array['sFollow'] = @$data['sFollow'];
        @$array['reWriteTitle'] = @$data['rewriteTitle']; //rewrite Title with seo title
        @$array['author'] = translateFromSerialize($data['author']);
        @$array['revisit-after'] = @$data['revisit-after'];
        @$array['robots'] = $robots;
        @$array['link'] = $this->currentUrl(false);
        @$array['checklink'] = $link;

        @$lang = currentWebLanguage();
        @$mobileBannerImage = "";
        @$mobileBannerLink = "";
        if (!empty($data['b2']) && empty(unserialize($data['b2'])[$lang])) {
            $sql = "SELECT b2, l2 FROM `seo` WHERE pageLink = '/default'";
            $ddata = $this->dbF->getRow($sql);
            $mobileBannerImage = $ddata['b2'];
            $mobileBannerLink = $ddata['l2'];
        } else {
            @$mobileBannerImage = $data['b2'];
            @$mobileBannerLink = $data['l2'];
        }


        @$array['b1'] = $data['b1'];
        @$array['b2'] = $mobileBannerImage;
        @$array['l1'] = $data['l1'];
        @$array['l2'] = $mobileBannerLink;
        @$array['seoPK'] = $data['id'];

        $this->hasSeo = true;
        if ($this->functions->developer_setting('seo') == '0') {
            //if seo 0 from developer setting then blank all seo array
            $this->hasSeo = false;
        }
        return $array;
    }

    /**
     * if seo false from developer setting then blank all seo array, and blank values not print on website
     * @param bool|false $all
     */
    private function seoBlank($all = false)
    {
        global $seo;
        if ($this->hasSeo == false) {
            foreach ($seo as $key => $val) {
                if ($key == 'title' && $all == false) {
                    continue;
                }
                $seo[$key] = '';
            }
        }
    }

    public function seo_page_type()
    {
        global $seo;
        if (isset($seo["type"])) {
        }
    }

    public function seoChange($key, $val)
    {
        if (isset($seo["$key"])) {
            $seo["$key"] = $val;
        }
    }

    public function imediaMeta($echo = true)
    {
        $temp = '<meta content="SHARKSPEED" name="author" />' . "\n";
        if ($echo) {
            echo $temp;
        } else {
            return $temp;
        }
    }

    public function seoOgGraph($echo = true)
    {
        $this->seoBlank(true);
        $temp = "";
        global $seo;
        if (isset($seo['title']) && $seo['title'] != '') {
            $temp .= "<meta property='og:title' content='$seo[title]' />\n";
        }
        if (isset($seo['type']) && $seo['type'] != '') {
            $temp .= "<meta property='og:type' content='$seo[type]' />\n";
        }
        if (isset($seo['description']) && $seo['description'] != '') {
            $temp .= "<meta property='og:description' content='$seo[description]' />\n";
        }
        if (isset($seo['url']) && $seo['url'] != '') {
            $temp .= "<meta property='og:url' content='$seo[url]' />\n";
        }
        if (isset($seo['link']) && $seo['link'] != '') {
            $temp .= "<meta property='og:url' content='$seo[link]' />\n";
        }
        $siteName = $this->functions->ibms_setting('Site Name');
        $temp .= "<meta property='og:site_name' content='$siteName' />\n";
        

        /*Og Product graph*/
        if (
            ($seo['type'] == "product" || $seo['type'] == "og:product" || $seo['type'] == "item")
            && isset($seo['price']) && $seo['price'] != '' && isset($seo['currency']) && $seo['currency'] != ''
        ) {
            if (isset($seo['title']) && $seo['title'] != '') {
                $temp .= "<meta property='product:plural_title' content='$seo[title]' />\n";
            }
            $temp .= "<meta property='product:price:amount' content='$seo[price]' />\n";
            $temp .= "<meta property='product:price:currency' content='$seo[currency]' />\n";

            if (isset($seo['shipping']) && $seo['shipping'] != '') {
                $temp .= "<meta property='product:shipping_cost:amount' content='$seo[shipping]' />\n";
                $temp .= "<meta property='product:shipping_cost:currency' content='$seo[currency]' />\n";
            }
        }



        if (isset($seo['image']) && $seo['image'] != '') {
            $temp .= "<meta property='og:image' content='$seo[image]' />\n";
        }

        $fbIds = $this->functions->ibms_setting('facebookIntId');
        $fbIds = str_replace(" ", "", $fbIds);
        $fbIds = explode(",", $fbIds);
        foreach ($fbIds as $fbId) {
            $temp .= "<meta property='fb:admins' content='" . $fbId . "' />\n";
        }

        if ($echo) {
            echo $temp;
        } else {
            return $temp;
        }
    }

    public function seoTwitter($echo = true)
    {
        $this->seoBlank(true);
        $temp = "";
        global $seo;


        //Twitter only these type of card type
        $twitter_type = "summery";
        @$seo_type = $seo['type'];
        if ($seo_type == "photo") {
            $twitter_type = "photo";
        } elseif ($seo_type == "gallery" || $seo_type == "album") {
            $twitter_type = "gallery";
        } elseif ($seo_type == "product" || $seo_type == "item") {
            $twitter_type = "product";
        }

        $temp .= "<meta name='twitter:card' content='$twitter_type' />\n";

        if (isset($seo['link']) && $seo['link'] != '') {
            $temp .= "<meta name='twitter:url' content='$seo[link]' />\n";
        }
        if (isset($seo['title']) && $seo['title'] != '') { //Title of content (max 70 characters)
            $temp .= "<meta name='twitter:title' content='$seo[title]' />\n";
        }
        if (isset($seo['description']) && $seo['description'] != '') { //Description of content (maximum 200 characters)
            $temp .= "<meta name='twitter:description' content='$seo[description]' />\n";
        }

        if (isset($seo['author']) && $seo['author'] != '') { // @username of content creator
            $temp .= "<meta name='twitter:creator' content='$seo[author]' />\n";
        }        
        
        if (isset($seo['author']) && $seo['author'] != '') {
            $temp .= "<meta name='twitter:site' content='$seo[author]' />\n";
        }

        if (isset($seo['image'])) {
            $temp .= "<meta name='twitter:image' content='$seo[image]' />\n";
        }

        /*twitter Product card*/
        if (
            ($seo['type'] == "product" || $seo['type'] == "og:product" || $seo['type'] == "item")
            && isset($seo['price']) && $seo['price'] != '' && isset($seo['currency']) && $seo['currency'] != ''
        ) {
            $temp .= "<meta name='twitter:label1' content='price' />\n";
            $temp .= "<meta name='twitter:data1' content='$seo[price] $seo[currency]' />\n";
        }


        if ($echo) {
            echo $temp;
        } else {
            return $temp;
        }
    }




    public function imediaMetaNew($echo = true)
    {
        $this->seoBlank(true);
        $temp = base64_encode("Developed and maintained by Interactive Media International imediaintl.com");
        return $temp;
    }

    public function seoScheme($echo = true)
    {
        $this->seoBlank(true);
        $temp = "";
        global $seo;
        if (isset($seo['title']) && $seo['title'] != '') {
            $temp .= "<meta itemprop='name' content='$seo[title]' />\n";
        }
        if (isset($seo['description']) && $seo['description'] != '') {
            $temp .= "<meta itemprop='description' content='$seo[description]' />\n";
        }
        if (isset($seo['image']) && $seo['image'] != '') {
            $temp .= "<meta itemprop='image' content='$seo[image]' />\n";
        }
        if ($echo) {
            echo $temp;
        } else {
            return $temp;
        }
    }

    public function seoMetaName($nameVal, $val, $echo = true, $metaNameOrPropertyDefaultName = true)
    {
        if ($val == "") {
            return false;
        }
        $metaName = 'name';
        if ($metaNameOrPropertyDefaultName === false) {
            //if false mean meta property
            $metaName = 'property';
        } else if ($metaNameOrPropertyDefaultName !== true && $metaNameOrPropertyDefaultName !== false) {
            //If you define meta name
            $metaName = $metaNameOrPropertyDefaultName;
        }

        if ($echo == true) {
            echo "<meta $metaName = '$nameVal' content='$val'/>\n";
        } else {
            return "<meta $metaName = '$nameVal' content='$val'/>\n";
        }
    }
    public function seoTitle($title, $echo = true)
    {
        $site_name = $this->functions->ibms_setting("Site Name");
        $site_name = trim($site_name, "-");
        $title = trim($title, "-") . " - " . $site_name;
        if ($echo == true) {
            echo "<title>$title</title>\n";
        } else {
            return "<title>$title</title>\n";
        }
    }

    public function AllSeoPrint()
    {
        global $seo;
        $webLang = currentWebLanguage();
        foreach ($seo as $key => $val) {
            //remove extra space from text and remove tags,
            //space and tags add form data that fetch from db, or where page heading or desc use as seo
            if ($key == "special")
                continue;
            $seo[$key] = removeSpace(strip_tags($val ?? ''));
        }

        $this->seoBlank();
        $this->seoTitle($seo['title']);
        $this->seoMetaName('keywords', $seo['keywords']);
        $this->seoMetaName('description', $seo['description']);
        $this->seoMetaName('canonical', $seo['canonical']);
        $this->seoMetaName('author', $seo['author']);
        $this->seoMetaName('robots', $seo['robots']);
        $this->seoMetaName('revisit-after', $seo['revisit-after']);
        $this->seoMetaName('title', $seo['seo_title']);
        $this->seoMetaName('language', $webLang);

        $this->seo_page_type();

        $this->seoOgGraph();
        $this->seoTwitter();
        $this->seoScheme();
    }
    public function seoBanner($pageLink = true)
    {
        global $seo;
        $lang = currentWebLanguage();
        $show = "";

        $b1 = unserialize($seo['b1'] ?? '');
        $b1 = isset($b1[$lang]) ? $b1[$lang] : '';




        $l1 = unserialize($seo['l1'] ?? '');
        $l1 = isset($l1[$lang]) ? $l1[$lang] : '';


        if ($b1) {


            if (!$l1) {

                $l1 = "#";
            }



            $show .= '
<div class="inner_banner">
<a href="' . ($l1) . '"> 
<img src="' . ($b1) . '" alt=""> 
</a>
</div>
';
        }





        return $show;
    }
    public function country_IP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = ($_SERVER['REQUEST_URI']);
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }


        $post = [
            'ip' => $ip
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://services.imedia.pk/ip");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $country_Code = curl_exec($ch);

        curl_close($ch);

        if ($country_Code == "SE") {
            header("location:https://sharkspeed.se" . $url);
            exit;
        }


        if ($country_Code == "NO") {
            header("location:https://sharkspeed.no" . $url);
            exit;
        }

        if ($country_Code == "FI") {
            header("location:https://sharkspeed.fi" . $url);
            exit;
        }

        if ($country_Code == "CH") {
            header("location:https://sharkspeedmoto.ch" . $url);
            exit;
        }

        if ($country_Code == "FR") {
            header("location:https://sharkspeed.fr" . $url);
            exit;
        }


        if ($country_Code == "NL") {
            header("location:https://sharkspeed.nl" . $url);
            exit;
        }

        if ($country_Code == "US") {
            header("location:https://sharkspeed.net" . $url);
            exit;
        }

        if ($country_Code == "BE") {
            header("location:https://sharkspeed.be" . $url);
            exit;
        }


        if ($country_Code == "GB") {
            header("location:https://sharkspeed.co.uk" . $url);
            exit;
        }


        if ($country_Code == "ES") {
            header("location:https://sharkspeed.es" . $url);
            exit;
        }

        if ($country_Code == "AT") {
            header("location:https://sharkspeed.at" . $url);
            exit;
        }

        if ($country_Code == "IT") {
            header("location:https://sharkspeed.it" . $url);
            exit;
        }

        if ($country_Code == "DK") {
            header("location:https://sharkspeed.dk" . $url);
            exit;
        }

        if ($country_Code == "DE") {
            header("location:https://sharkspeed.de" . $url);
            exit;
        }

        header("location:https://sharkspeed.eu" . $url);
    }

    public function seoBannerRes($pageLink = true)
    {
        global $seo;
        $lang = currentWebLanguage();
        $show = "";


        $b2 = unserialize($seo['b2']);
        $b2 = isset($b2[$lang]) ? $b2[$lang] : '';



        $l2 = unserialize($seo['l2']);
        $l2 = isset($l2[$lang]) ? $l2[$lang] : '';



        if ($b2) {


            if (!$l2) {

                $l2 = "#";
            }

            $show .= '
<div class="inner_banner_mobile">
<a href="' . ($l2) . '"> 
<img src="' . ($b2) . '" alt=""> 
</a>
</div>


';
        }



        return $show;
    }



    public function seoSpecial($echo = true)
    {
        global $seo;
        $temp = "";
        if (!empty($seo['special'])) {


            $temp = '
<div class="col1_right_des"><div class="col1_right_des_txt">

' . $seo['special'] . '

</div></div>
';
        }
        if ($echo) {
            echo $temp;
        } else {
            return $temp;
        }
    }


    public function webUserEditSubmit()
    {
        if (
            isset($_POST['name']) && !empty($_POST['name'])
            && isset($_POST['email']) && !empty($_POST['email'])
            && isset($_POST['oldId']) && !empty($_POST['oldId'])
        ) {

            if (!$this->functions->getFormToken('webUserEdit')) {
                return false;
            }
            try {

                $email = strip_tags(strtolower(trim($_POST['email'])));
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $id = empty($_POST['oldId']) ? "" : $_POST['oldId'];
                    $name = empty($_POST['name']) ? "" : $_POST['name'];
                    $pass = empty($_POST['pass']) ? "" : $_POST['pass'];
                    $rpass = empty($_POST['rpass']) ? "" : $_POST['rpass'];

                    $passwordStatus = false;
                    if ($pass != $rpass) {
                        $msg = 'Password Not Matched!';
                        $msg = $this->dbF->hardWords($msg, false);
                        return $msg;
                    }
                    if ($pass != '') {
                        $passwordStatus = true;
                    }


                    $this->db->beginTransaction();
                    $sql = "UPDATE  accounts_user SET
                                acc_name = ?,
                                acc_email = ?
                                WHERE acc_id = ?";
                    $array = array($name, $email, $id);
                    $this->dbF->setRow($sql, $array, false);

                    if ($passwordStatus) {
                        $password = hash('md5', $this->functions->encode($pass));
                        $sql = "UPDATE  accounts_user SET
                                acc_pass = ?
                                WHERE acc_id = ?";
                        $array = array($password, $id);
                        $this->dbF->setRow($sql, $array, false);
                    }

                    $lastId = $id;
                    $setting = empty($_POST['signUp']) ? array() : $_POST['signUp'];

                    $sql = "DELETE FROM `accounts_user_detail` WHERE id_user= '$id'";
                    $this->dbF->setRow($sql);

                    $sql = "INSERT INTO `accounts_user_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";
                    $arry = array();
                    foreach ($setting as $key => $val) {
                        $sql .= "('$lastId',?,?) ,";
                        $arry[] = $key;
                        $arry[] = $val;
                    }
                    $sql = trim($sql, ",");
                    $this->dbF->setRow($sql, $arry, false);
                } else {
                    $AccLoginInfoT = 'Invalid Email Address! Or Some Thing Went Wrong';
                    $msg = $AccLoginInfoT;
                    $msg = $this->dbF->hardWords($msg, false);
                    return $msg;
                }

                $this->db->commit();
                $msg = $this->dbF->hardWords('Profile Update Successfully!', false);
                return $msg;
            } catch (PDOException $e) {
                $msg = "WebUser Update fail please try again.!";
                $msg = $this->dbF->hardWords($msg, false);
                $this->db->rollBack();
                return $msg;
            }
        }
        return "";
    }

    public function webUserInfoArray($data, $settingName)
    {
        foreach ($data as $val) {
            if ($val['setting_name'] == $settingName) {
                return $val['setting_val'];
            }
        }
        return "";
    }

    public function webUserEdit($id = '')
    {
        if ($id == '') {
            $id = $_GET['userId'];
        }
        $sql = "SELECT * FROM accounts_user WHERE acc_id = '$id'";
        $userData = $this->dbF->getRow($sql);
        if (!$this->dbF->rowCount) {
            return false;
        }

        $sql = "SELECT * FROM accounts_user_detail WHERE id_user = '$id'";
        $userInfo = $this->dbF->getRows($sql);
        $token = $this->functions->setFormToken('webUserEdit', false);

        echo '<form class="form-horizontal" role="form" method="post">' . $token . '
        <input type="hidden" name="oldId" value="' . $id . '"/>
                <div class="form-group">
                    <label for="user" class="col-sm-2 control-label">' . $this->dbF->hardWords('Name', false) . '</label>

                    <div class="col-sm-10">
                    <!-- patteren not working for sweden lang pattern="[a-zA-z- ]{3,50}"-->
                        <input type="text" class="form-control" name="name" id="user"
                               placeholder="Name" value="' . $userData['acc_name'] . '" required onChange="filter(this); vali()">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label" >' . $this->dbF->hardWords('Email', false) . '</label>

                    <div class="col-sm-10">
                        <input type="email" class="form-control"  value="' . $userData['acc_email'] . '" name="email" id="inputEmail3" placeholder="Email" required>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">' . $this->dbF->hardWords('Gender', false) . '</label>

                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" class="gender" name="signUp[gender]" value="female">' . $this->dbF->hardWords('Female', false) . '
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="gender" name="signUp[gender]" value="male">' . $this->dbF->hardWords('Male', false) . '
                        </label>
                    </div>
                </div>
                <script>
                $(document).ready(function(){
                    $(".gender[value=\'' . strtolower($this->webUserInfoArray($userInfo, 'gender')) . '\']").attr("checked", true);
                });

                </script>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >' . $this->dbF->hardWords('Date Of Birth', false) . '</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control date"  value="' . $this->webUserInfoArray($userInfo, 'date_of_birth') . '" name="signUp[date_of_birth]" placeholder="mm/dd/yyyy e.g: 12/31/2014" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >' . $this->dbF->hardWords('phone', false) . '</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control"  value="' . $this->webUserInfoArray($userInfo, 'phone') . '" name="signUp[phone]" placeholder="" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >' . $this->dbF->hardWords('address', false) . '</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" name="signUp[address]" placeholder="" >' . $this->webUserInfoArray($userInfo, 'address') . '</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >' . $this->dbF->hardWords('Post Code', false) . '</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control"  value="' . $this->webUserInfoArray($userInfo, 'post_code') . '" name="signUp[post_code]" placeholder="" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >' . $this->dbF->hardWords('city', false) . '</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control"  value="' . $this->webUserInfoArray($userInfo, 'city') . '" name="signUp[city]" placeholder="" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" >' . $this->dbF->hardWords('country', false) . '</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control"  value="' . $this->webUserInfoArray($userInfo, 'country') . '" name="signUp[country]" placeholder="" >
                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label" >' . $this->dbF->hardWords('Account Create', false) . '</label>

                    <div class="col-sm-10">
                        <input type="email" class="form-control"  value="' . $userData['acc_created'] . '" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label" >' . $this->dbF->hardWords('Last Update', false) . '</label>

                    <div class="col-sm-10">
                        <input type="email" class="form-control"  value="' . $userData['acc_timestamp'] . '" readonly>
                    </div>
                </div>


<hr>

                <div class="form-group">
                    <label for="pass" class="col-sm-2 control-label">' . $this->dbF->hardWords('password', false) . '</label>

                    <div class="col-sm-10">
                        <input type="password" onChange="passM();" class="form-control" name="pass" id="pass"
                               placeholder="" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="rpass" class="col-sm-2 control-label">' . $this->dbF->hardWords('retype Password', false) . '</label>
                    <div class="col-sm-10">
                        <input type="password" onChange="passM();" onkeyup="passM();" class="form-control" name="rpass" id="rpass"
                               placeholder="">

                        <div id="pm"></div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="submit" id="signup_btn" class="btn btn-primary defaultSpecialButton" onClick="subf()">
                               ' . $this->dbF->hardWords('Update', false) . '
                        </button>
                    </div>
                </div>
            </form>


            '; ?>
        <script type="text/javascript">
            function passM() {
                var pass = document.getElementById("pass").value;
                var rpass = document.getElementById("rpass").value;
                if (pass.length >= 4) {
                    if (pass == rpass) {
                        document.getElementById("pm").style.color = "green";
                        document.getElementById("pm").innerHTML = "<?php $this->dbF->hardWords('Password Matched!'); ?>";
                        document.getElementById("signup_btn").disabled = false;
                    } else {
                        document.getElementById("pm").style.color = "red";
                        document.getElementById("pm").innerHTML = "<?php $this->dbF->hardWords('Password Not Matched!'); ?>";
                        document.getElementById("signup_btn").disabled = true;
                    }
                } else {
                    document.getElementById("pm").style.color = "orange";
                    document.getElementById("pm").innerHTML = "<?php $this->dbF->hardWords('Atleat 4 characters!'); ?>";
                    document.getElementById("signup_btn").disabled = true;
                }
                if (pass == '' && rpass == '') {
                    document.getElementById("signup_btn").disabled = false;
                }
            }

            function vali() {
                var u_l = document.getElementById("user").value.length;
                if (u_l <= 3) {
                    document.getElementById("um").style.color = "red";
                    document.getElementById("signup_btn").disabled = true;
                } else {
                    document.getElementById("um").style.color = "black";
                    document.getElementById("signup_btn").disabled = false;
                }
            }

            function subf() {
                var terms = document.getElementById("ch").checked;
                if (terms == true) {
                    document.getElementById("sf").submit();
                }
            }
        </script>

<?php
    }

    public function multiLanguageFlags()
    {
        $currentLang = currentWebLanguage();
        $temp = "";
        $data = unserialize($this->functions->ibms_setting('Languages'));
        $link = $this->functions->defaultHttp . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parm = false;
        if (!empty($_GET)) {
            $parm = true;
            if (isset($_GET['lang'])) {
                $old = $_GET['lang'];
                foreach ($data as $val) {
                    $link = str_replace("&lang=$val", "", $link);
                    $link = str_replace("?lang=$val", "?", $link);
                }
            }
        }

        foreach ($data as $val) {
            $link2 = $link;
            if ($parm) {
                $link2 .= "&lang=$val";
            } else {
                $link2 .= "?lang=$val";
            }
            $active = '';
            if ($currentLang == $val) {
                $active = 'active';
            }

            $countryKey = $this->functions->countryKeyByName($val);
            $temp .= "<a href='$link2'>
                         <div class='$active country flag flag-$countryKey  transition_3 float-shadow'></div>
                      </a>";
        }

        return $temp;
    }
    public function multiLanguage()
    {
        $currentLang = currentWebLanguage();
        $temp = "";
        $temp .= '<div class="dropdown ">
                    <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                        ' . $currentLang . '
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">';


        $data = unserialize($this->functions->ibms_setting('Languages'));
        $link = $this->functions->defaultHttp . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parm = false;
        if (!empty($_GET)) {
            $parm = true;
            if (isset($_GET['lang'])) {
                $old = $_GET['lang'];
                foreach ($data as $val) {
                    $link = str_replace("&lang=$val", "", $link);
                    $link = str_replace("?lang=$val", "?", $link);
                }
            }
        }

        foreach ($data as $val) {
            $link2 = $link;
            if ($parm) {
                $link2 .= "&lang=$val";
            } else {
                $link2 .= "?lang=$val";
            }
            $active = '';
            if ($currentLang == $val) {
                $active = 'active';
            }

            $temp .= "<li class='$active' role='presentation'><a role='menuitem' tabindex='-1' href='$link2'>$val</a></li>";
        }

        $temp .= '</ul>
           </div>';
        return $temp;
    }


    public function setMultiLanguage()
    {
        if (isset($_GET['lang']) && lang_define == false) {
            unset($_SESSION['klarna_checkout']);
            //check is language has
            $data = unserialize($this->functions->ibms_setting('Languages'));
            $lang = $_GET['lang'];
            foreach ($data as $val) {
                if ($lang == $val) {
                    $_SESSION['webUser']['webLang'] = $val;
                }
            }

            $this->make_available_webproduct_functions();
            $this->webProduct_handle->emptyCart();
        } elseif (isset($_GET['lang'])) {
            //check is language has
            $data = unserialize($this->functions->ibms_setting('Languages'));
            $lang = $_GET['lang'];
            foreach ($data as $val) {
                if ($lang == $val) {
                    $_SESSION['webUser']['webLang'] = $val;
                    $_SESSION['webUser']['customLang'] = true;
                }
            }
        }
    }


    public function includeScript()
    {
        $temp = <<<HTML

HTML;
        return $temp;
    }

    public function make_available_webproduct_functions()
    {
        $this->functions->includeOnceCustom("_models/functions/webProduct_functions.php");
        $this->webProduct_handle = new webProduct_functions();
    }

    public function get_product($pid)
    {

        $result = false;

        $sql = "SELECT * FROM `proudct_detail` WHERE `prodet_id` = ? AND product_update = '1' ";
        $row = $this->dbF->getRow($sql, array($pid));
        if ($this->dbF->rowCount > 0) {
            $result = $row;
        }

        return $result;
    }

    public function get_product_cat($pId)
    {
        $result = false;
        $cat_ids = '';
        $sql = "SELECT * FROM `product_category` WHERE `procat_prodet_id` = '$pId'";
        $row = $this->dbF->getRow($sql);

        if ($this->dbF->rowCount > 0) {
            if ($row['procat_cat_id'] != '') {
                $cats_array = explode(',', $row['procat_cat_id']);
                $cats_array = array_filter($cats_array, function($cat) {
                    return !empty($cat);
                });
                $cat_ids = implode(',', $cats_array);
                if(!empty($cat_ids)){
                    $sql = 'SELECT * FROM tree_data WHERE id IN (' . str_repeat('?,', count($cats_array) - 1) . '?) ORDER BY `id` ASC';
                    $rows = $this->dbF->getRows($sql, $cats_array);
                    $row = isset($rows[0]) ? $rows[0]['nm'] : '';
                }
            }

            $result = $row;
        }
        return $result;
    }

    // Function to return the JavaScript representation of a TransactionData object.
    public function getTransactionJs($trans)
    {
        $transId = $trans['order_invoice_pk'];
        $total = $trans['total_price'] - $trans['ship_price'];
        $shipping = $trans['ship_price'];
        $currency = $trans['price_code'];


        $price_cal = $this->functions->product_tax_cal($total, 25);
        $tax = $price_cal['tax_price'];

        $revenue = $total - $tax;

        return <<<HTML
        ga('ecommerce:addTransaction', {
          'id'      : '{$transId}',
          'revenue' : '{$revenue}',
          'shipping': '{$shipping}',
          'tax'     : '{$tax}',
          'currency': '{$currency}'
        }); \n
HTML;
    }





    // Function to return the JavaScript representation of a TransactionData object.
    public function getTransactionJsNEW($trans)
    {

        $transId = $trans['order_invoice_pk'];
        $total = $trans['total_price'] - $trans['ship_price'];
        $shipping = $trans['ship_price'];
        $currency = $trans['price_code'];
        $orderUser = $trans['orderUser'];



        $price_cal = $this->functions->product_tax_cal($total, 25);
        $tax = $price_cal['tax_price'];

        $revenue = $total - $tax;

        return $temp = "
    'event':'ecomm_event',
    'transactionId': '{$transId}', 
    'transactionAffiliation':  '{$orderUser}', 
    'transactionTotal': '{$revenue}',  
    'transactionCurrency':'{$currency}', 
    'transactionTax': '{$tax}',
    'transactionShipping':'{$shipping}'";
    }


    // Function to return the JavaScript representation of an ItemData object.
    public function getItemJsNEW($transId, $item)
    {

        $pName = $item['order_pName'];
        $pNames = explode(" - ", $pName);
        @$pName1 = $pNames[0];

        $pIds = $item['order_pIds'];
        $order_pIds = explode("-", $pIds);
        $pId = $order_pIds[0];

        $product = $this->get_product($pId);
        $product_cat = $this->get_product_cat($pId); // First cat name is returned

        $price = $item['order_pPrice'] - $item['order_discount'];

        return $temp = "{
                'sku': '" . $product['slug'] . "',  
                'name': '" . $pName1 . "',  
                'category': '" . $product_cat . "',
                'price': '" . $price . "',
                'quantity':'" . $item['order_pQty'] . "'
                }";
    }
    // Function to return the JavaScript representation of an ItemData object.
    public function getItemJs($transId, $item)
    {

        $pName = $item['order_pName'];
        $pNames = explode(" - ", $pName);
        @$pName1 = $pNames[0];

        $pIds = $item['order_pIds'];
        $order_pIds = explode("-", $pIds);
        $pId = $order_pIds[0];

        $product = $this->get_product($pId);
        $product_cat = $this->get_product_cat($pId); // First cat name is returned

        $price = $item['order_pPrice'] - $item['order_discount'];


        return <<<HTML
        ga('ecommerce:addItem', {
          'id'      : '$transId',
          'name'    : '{$pName1}',
          'sku'     : '{$product['slug']}',
          'category': '{$product_cat}',
          'price'   : '{$price}',
          'quantity': '{$item['order_pQty']}'
        }); \n
HTML;
    }

    public function generate_google_analytics_ecommerce($order_invoice_pk)
    {
        $orderSQL = "SELECT * FROM `order_invoice` WHERE `order_invoice_pk` = ?";
        $order_row = $this->dbF->getRow($orderSQL, [$order_invoice_pk]);

        $orderProductSQL = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = ?";
        $order_product_rows = $this->dbF->getRows($orderProductSQL, [$order_invoice_pk]);

        $customerSQL = "SELECT * FROM `order_invoice_info` WHERE `order_invoice_id` = ?";
        $dataCustomer = $this->dbF->getRow($customerSQL, [$order_invoice_pk]);
        
        $customerEmail = $dataCustomer["sender_email"];
    
        $currency = $order_row["price_code"];
        $payPrice = (float) $order_row["total_price"] + (float) $order_row["ship_price"];

        $google_analytics_ecommerceNew2 = "<script>";
        $google_analytics_ecommerceNew2 .= "gtag('event', 'purchase', {";
        $google_analytics_ecommerceNew2 .= "'send_to': 'AW-789759517/Ht8uCMnekJUBEJ2My_gC',";
        $google_analytics_ecommerceNew2 .= "'value': parseFloat(" . $payPrice . "),";
        $google_analytics_ecommerceNew2 .= "'currency': '" . $currency . "',";
        $google_analytics_ecommerceNew2 .= "'transaction_id': '" . $order_invoice_pk . "',";
        $google_analytics_ecommerceNew2 .= "'customer_email': '" . $customerEmail . "'";
        $google_analytics_ecommerceNew2 .= "});";
        $google_analytics_ecommerceNew2 .= '</script>';

        echo $google_analytics_ecommerceNew2;

        
        $output = '';
        $output .= $this->getTransactionJs($order_row);
        foreach ($order_product_rows as $order_product_row) {
            $output .= $this->getItemJs($order_invoice_pk, $order_product_row);
        }

        return $output;
    }



    public function generate_google_analytics_ecommerceNew($order_invoice_pk)
    {
        $ordersSQL = "SELECT * FROM `order_invoice` WHERE `order_invoice_pk` = ?";
        $order_row = $this->dbF->getRow($ordersSQL, [$order_invoice_pk]);

        $orderProductSQL = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = ?";
        $order_product_rows = $this->dbF->getRows($orderProductSQL, [$order_invoice_pk]);

        $output = '';
        $output .= $this->getTransactionJsNEW($order_row);


        $output .= ",'transactionProducts':[";

        foreach ($order_product_rows as $order_product_row) {
            $output .= $this->getItemJsNEW($order_invoice_pk, $order_product_row);
        }
        $output .= "]";

        return $output;
    }




    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), "", strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
} //class End
?>