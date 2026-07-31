<?php

/*

 * Admin Functions

 * this is a extended class of the user functions

 * which includes all user functions included with

 * the customize administrator functions and traits.

 */



class admin_functions extends functions

{



    function __construct(){

        parent::__construct();

    }



    public function getEncryptFolderName(){

        //Only Folder Name in admin, that want to be encrypt,, write here to easy to manage

        $array   = array();

        $array[] = 'banners';

        $array[] = 'brands';

        $array[] = 'blog';

        $array[] = 'dashboard';

        $array[] = 'email';

        $array[] = 'gallery';

        $array[] = 'logs';

        $array[] = 'menu';

        $array[] = 'news';

        $array[] = 'order';

        $array[] = 'pages';

        $array[] = 'product';

        $array[] = 'product_management';

        $array[] = 'seo';

        $array[] = 'setting';

        $array[] = 'shipping';

        $array[] = 'stock';

        $array[] = 'webUsers';

        $array[] = '';

        return $array;

    }



    public function admin_panel_access()

    {

        $this->log_check(true, WEB_URL.'/do-login.secure');

        switch ($_SESSION["_role"]):

            case "super_admin":

            case "admin":

            case "manager":

                $allow_access_to_panel = true;

                break;



            default:

                $allow_access_to_panel = false;

                break;

        endswitch;

        if ($allow_access_to_panel == false) header("location:../error-404");

    }





    public function setlog($title = "untitled",$ref_name = false,$ref_id = "", $desc = "",$transaction=true)

    {

        $log_check = $this->log_check();

        if(!empty($ref_name) && $log_check['status']=="ok"){

            $user_email = $_SESSION['_email'];

            $ip = $_SERVER['REMOTE_ADDR'];

            $browser = "";

            foreach($this->getBrowser() as $key=>$val){

                $browser .= "$key : $val <br />";

            }



            $sql = "INSERT INTO `activity_log` (`log_title`, `ref_name`, `ref_id`, `ref_user`, `log_desc`, `log_ip`, `log_browser`)

                VALUES (?,?,?,?,?,?,?) ";



            $arr= array($title,$ref_name,$ref_id,$user_email,$desc,$ip,$browser);

            $this->dbF->setRow($sql,$arr,$transaction);

            return true;

        }

        return false;

    }

    public function orderlog($title = "untitled",$ref_name = false,$ref_id = "", $desc = "",$transaction=true)
    {
        $log_check = $this->log_check();
        // $this->dbF->prnt($log_check);
        if(!empty($ref_name) && $log_check['status']=="ok"){
            $user_email = $_SESSION['_email'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = "";
            foreach($this->getBrowser() as $key=>$val){
                $browser .= "$key : $val <br />";
            }

            $sql = "INSERT INTO `order_activity_log` (`log_title`, `ref_name`, `ref_id`, `ref_user`, `log_desc`, `log_ip`, `log_browser`)
                VALUES (?,?,?,?,?,?,?) ";

            $arr= array($title,$ref_name,$ref_id,$user_email,$desc,$ip,$browser);
            $this->dbF->setRow($sql,$arr,$transaction);
            return true;
        }
        return false;
    }



    private function getBrowser()

    {

        $u_agent = $_SERVER['HTTP_USER_AGENT'];

        $bname = 'Unknown';

        $platform = 'Unknown';

        $version = "";



        //First get the platform?

        if (preg_match('/linux/i', $u_agent)) {

            $platform = 'linux';

        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {

            $platform = 'mac';

        } elseif (preg_match('/windows|win32/i', $u_agent)) {

            $platform = 'windows';

        }



        // Next get the name of the useragent yes seperately and for good reason

        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {

            $bname = 'Internet Explorer';

            $ub = "MSIE";

        } elseif (preg_match('/Firefox/i', $u_agent)) {

            $bname = 'Mozilla Firefox';

            $ub = "Firefox";

        } elseif (preg_match('/Chrome/i', $u_agent)) {

            $bname = 'Google Chrome';

            $ub = "Chrome";

        } elseif (preg_match('/Safari/i', $u_agent)) {

            $bname = 'Apple Safari';

            $ub = "Safari";

        } elseif (preg_match('/Opera/i', $u_agent)) {

            $bname = 'Opera';

            $ub = "Opera";

        } elseif (preg_match('/Netscape/i', $u_agent)) {

            $bname = 'Netscape';

            $ub = "Netscape";

        }



        // finally get the correct version number

        $known = array('Version', $ub, 'other');

        $pattern = '#(?<browser>' . join('|', $known) .

            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        if (!preg_match_all($pattern, $u_agent, $matches)) {

            // we have no matching number just continue

        }



        // see how many we have

        $i = count($matches['browser']);

        if ($i != 1) {

            //we will have two since we are not using 'other' argument yet

            //see if version is before or after the name

            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {

                $version = $matches['version'][0];

            } else {

                $version = $matches['version'][1];

            }

        } else {

            $version = $matches['version'][0];

        }



        // check if we have a number

        if ($version == null || $version == "") {

            $version = "?";

        }



        return array(

            'userAgent' => $u_agent,

            'name' => $bname,

            'version' => $version,

            'platform' => $platform,

            'pattern' => $pattern

        );

    }





    public function get_timezone_options($selectedzone="")

    {

        function timezonechoice($selectedzone) {

            $all = timezone_identifiers_list();



            $i = 0;

            foreach($all AS $zone) {

                $zone = explode('/',$zone);

                $zonen[$i]['continent'] = isset($zone[0]) ? $zone[0] : '';

                $zonen[$i]['city'] = isset($zone[1]) ? $zone[1] : '';

                $zonen[$i]['subcity'] = isset($zone[2]) ? $zone[2] : '';

                $i++;

            }



            asort($zonen);

            $structure = '';

            foreach($zonen AS $zone) {

                extract($zone);

                if($continent == 'Africa' || $continent == 'America' || $continent == 'Antarctica' || $continent == 'Arctic' || $continent == 'Asia' || $continent == 'Atlantic' || $continent == 'Australia' || $continent == 'Europe' || $continent == 'Indian' || $continent == 'Pacific') {

                    if(!isset($selectcontinent)) {

                        $structure .= '<optgroup label="'.$continent.'">'; // continent

                    } elseif($selectcontinent != $continent) {

                        $structure .= '</optgroup><optgroup label="'.$continent.'">'; // continent

                    }



                    if(isset($city) != ''){

                        if (!empty($subcity) != ''){

                            $city = $city . '/'. $subcity;

                        }

                        $structure .= "<option ".((($continent.'/'.$city)==$selectedzone)?'selected="selected "':'')." value=\"".($continent.'/'.$city)."\">".str_replace('_',' ',$city)."</option>"; //Timezone

                    } else {

                        if (!empty($subcity) != ''){

                            $city = $city . '/'. $subcity;

                        }

                        $structure .= "<option ".(($continent==$selectedzone)?'selected="selected "':'')." value=\"".$continent."\">".$continent."</option>"; //Timezone

                    }



                    $selectcontinent = $continent;

                }

            }

            $structure .= '</optgroup>';

            return $structure;

        }

        return timezonechoice($selectedzone);

    }







}



?>