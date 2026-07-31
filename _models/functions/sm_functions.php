<?php
//small functions, are use in client side.. and need to call any where...

//functions with out in class

function translateFromSerialize($serializeData, $serialize = true, $debug = false, $lang = false)
{
    return getTextFromSerializeArray($serializeData, $serialize, $debug, $lang);
}

function translateFromSerializeLanguage($serializeData, $serialize = true)
{
    return getTextFromSerializeArrayLanguage($serializeData, $serialize);
}



function getTextFromSerializeArray($serializeData, $serialize = true, $debug = false, $lang = false)
{
    
    $webLang = $lang ? $lang : currentWebLanguage();

    if ($serialize) {
        $tempA = @unserialize($serializeData);
    } else {
        $tempA = $serializeData;
    }
    
        
    if ($tempA === false && !empty($tempA)) {
        // $temp = getTranslationFromGoogle($serializeData, $webLang, $debug);
        // return $temp;
        // $temp = getTranslationFromGoogle($serializeData, $webLang, $debug);
        return $tempA;
    }
    
    if(is_array($tempA)){
        @$temp = $tempA[$webLang];
        
        if ($temp === false || empty($temp)) {
            // $temp = getTranslationFromGoogle($tempA, $webLang, $debug);
            @$temp = $tempA['English'];
        }
    }else{
        return $serializeData;
    }
    return $temp;
}

function getTranslationFromGoogle($tempA, $lang = false, $debug = false)
{
    global $dbF;
    $webLang = currentWebLanguage();
    $defaultLang = defaultWebLanguage();

    $translate = '';

    if (is_array($tempA)) {
        $temp = $tempA[$defaultLang];
    } else {
        $webLang = $lang;
        $temp = $tempA;
    }

    if (($temp === false || empty($temp))) {

        if (empty($tempA['Swedish'])) {
            if(is_array($tempA)){
                $temp = $tempA[key($tempA)];
            }
        } else {
            $temp = @$tempA['Swedish'];
        }
    }


    if (strpos($temp, '{{WEB_URL}}') !== false) {
        return $temp;
    }



    $q = urlencode($temp);

    if (!empty($q) && $q != '') {
        $target = getTargetLanguage($webLang);

        $hash = $temp . ':' . $webLang;
        $db_hash = md5($hash);

        $sql = "SELECT * FROM `google_translate` WHERE `hash` = ?";
        $res = $dbF->getRow($sql, array($db_hash));
    

        if (!empty($res)) {
            $translate = $res['translate'];
        }
    }

    if (empty($translate)) {
        $temp = @$tempA['Swedish'];
        return $temp;
    } else {
        return $translate;
    }
}

function getTextFromSerializeArrayLanguage($serializeData, $language, $serialize = true)
{
    $webLang = currentWebLanguage();
    $defaultLang = defaultWebLanguage();

    if ($serialize == true) {
        @$tempA = unserialize($serializeData);
    } else {
        @$tempA = $serializeData;
    }
    if ($tempA === false) {
        return $serializeData;
    }

    if (isset($language) && !empty($language)) {
        $temp = $tempA[$language];
    } else {
        @$temp = $tempA[$webLang];
        if ($temp === false || empty($temp)) {
            @$temp = $tempA[$defaultLang];

            if (($temp === false || empty($temp) && ($webLang == 'default' || $defaultLang == 'default'))) {
                $temp = $tempA[key($tempA)];
            }
        }
    }

    return $temp;
}

function getTargetLanguage($webLang)
{
    $target = '';
    switch ($webLang) {
        case 'Swedish':
        case 'default':
            $target = 'sv';
            break;
        case 'German':
            $target = 'de';
            break;
        case 'French':
            $target = 'fr';
            break;
        case 'English':
            $target = 'en';
            break;
        case 'Spanish':
            $target = 'es';
            break;
        case 'Italian':
            $target = 'it';
            break;
        case 'Dutch':
            $target = 'nl';
            break;
        case 'Norwegian':
            $target = 'no';
            break;
        case 'Danish':
            $target = 'da';
            break;
        case 'Finnish':
            $target = 'fi';
            break;
    }
    return $target;
}


function textArea($text){

    //echo text same as textarea,, replace \n to <br>

    return nl2br($text);

}



function adminLoginCheckStatus(){

    //simple login check use for when need to check in website, and no traits there

    //2 step checking.,

    if(isset($_SESSION['_uid']) && $_SESSION['_uid']>0){

        switch ($_SESSION["_role"]):

            case "super_admin":

            case "admin":

            case "manager":

                return true;

                break;

        endswitch;

    }

    return false;



}



function currentWebLanguage(){

    global $functions;

    //Work For website Language

    $lang = '';

    if(isset($_SESSION['webUser']['webLang'])){

        $lang   =  $_SESSION['webUser']['webLang'];

    }

    else{

        $defaultWebLanguage = $functions->WebDefaultLanguage();

        $lang       =   $defaultWebLanguage;

    }



    $_SESSION['webUser']['webLang']  =  $lang;

    return $lang;

}



function defaultWebLanguage(){

    global $functions;

    //Work For website Language

    if(isset($_SESSION['webUser']['defaultLang'])){

        $lang   =  $_SESSION['webUser']['defaultLang'];

    }else {

        $defaultWebLanguage = $functions->WebDefaultLanguage();

        $lang = $defaultWebLanguage;

    }

    $_SESSION['webUser']['defaultLang'] = $lang;

    return $lang;

}



function setRememberMe($name,$value,$days){

    $hour = time() + (3600*24*$days);

    $value = serialize($value);

    setcookie("$name", $value, $hour);

    $_COOKIE["$name"] = '';

    $_COOKIE["$name"] = $value;

}



function getRememberMe($name){

    if(!empty($_COOKIE[$name])){

        return unserialize($_COOKIE["$name"]);

    }

    return false;

}



function resetCookie(){

    // Destroy all cookies.

    if (isset($_SERVER['HTTP_COOKIE'])) {

        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

        foreach($cookies as $cookie) {

            $parts = explode('=', $cookie);

            $name = trim($parts[0]);

            setcookie($name, '', time()-1000);

            setcookie($name, '', time()-1000, '/');

        }

        @$_SERVER['HTTP_COOKIE'] = '';

    }

}



function loginRememberMe(){

    if(getRememberMe("webUser")!=false){

        $array = getRememberMe("webUser");

        if(isset($array['remember']) && $array['remember'] =='1') {

            $_SESSION['webUser'] = $array;

        }

    }

}



function webUserSession(){

    global $functions;

    //Set Session if New User.

    if(!isset($_SESSION['webUser']['login']) && !isset($_SESSION['webUser']['tempId'])){

        $_SESSION['webUser']['login']   =   '0';

        $_SESSION['webUser']['id']      =   '0';

        $_SESSION['webUser']['tempId']  =   uniqid()."_".uniqid();

        //tempId blank if user login, transfer tempId in old temp id on login,

        $_SESSION['webUser']['oldTempId'] =   '';

        $_SESSION['webUser']['name']    =   '';

    }

    return $_SESSION['webUser'];

}



function setUserSession($name,$val='1'){

    $_SESSION['webUser'][$name]    =  $val;

}

function getUserSession($name){

    if(isset($_SESSION['webUser'][$name])){

        return $_SESSION['webUser'][$name];

    }else{

        return false;

    }

}



function webUserName(){

    $userData   = webUserSession();

    return $userData['name'];

}



function webUserId(){

    $userData   = webUserSession();

    return $userData['id'];

}



function webTempUserId(){

    $userData   = webUserSession();

    return $userData['tempId'];

}



function webUserOldTempId(){

    $userData   = webUserSession();

    return $userData['oldTempId'];

}



function clientId(){

    //tell id if not then temp id

    $id = webUserId();

    if($id=='0'){

        $id = webTempUserId();

    }



    return $id;

}



function userLoginCheck(){

    $userData   = webUserSession();

    if($userData['id']=='0')

        return false;

    return true;

}



function pageLink($addParameterSeprator=true){

    global $db;

    $linkPage   =   $db->defaultHttp."".$_SERVER['HTTP_HOST']."".urldecode($_SERVER['REQUEST_URI']);

    if(isset($_GET) && $addParameterSeprator){

        $linkPage .= "&";

    }elseif($addParameterSeprator){

        $linkPage .= "?";

    }

    return $linkPage;

}



function array_delete($array, $value) {

    foreach($array as $key=>$val){

        if($val['prodet_id'] == $value){

            unset($array[$key]);

        }

    }

    return $array;

}



function removeSpace($string){

    //remove \n new line and extra space

    $string = trim(preg_replace('/\s\s+/', ' ', $string));

    return $string;

}



//work on latin content type

function specialChar_to_english_letters($text){

    return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($text));

}



//some time specialChar_to_english_letters not work, due to utf8 or latin content-type

//work on uft-8 content type
function sanitize_slug($text, $lowercase = true){

    if(empty($text)) return "";

    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);// replace non letter or digits by -

    $text = trim($text, '-'); // trim

    $text = specialChar_to_english_letters($text);// transliterate

    if ($lowercase) {

        $text = strtolower($text);  // lowercase

    }

    $text = preg_replace('~[^-\w]+~', '', $text);// remove unwanted characters

    return $text;

}

function sanitize_file_name($string, $anal = false) {

    $string = specialChar_to_english_letters($string);

    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")",/* "_",*/ "=", "+", "[", "{", "]",

        "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",

        "—", "–", ",", "<", /*".",*/ ">", "/", "?");

    $clean = trim(str_replace($strip, "", strip_tags($string)));

    $clean = preg_replace('/\s+/', "-", $clean);

    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;



    return strtolower($clean);

}

function specialChar_to_english_letters2($txt) {

    $transliterationTable = array('�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '?' => 'a', '?' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '?' => 'a', '?' => 'A', '?' => 'a', '?' => 'A', '�' => 'ae', '�' => 'AE', '�' => 'ae', '�' => 'AE', '?' => 'b', '?' => 'B', '?' => 'c', '?' => 'C', '?' => 'c', '?' => 'C', '?' => 'c', '?' => 'C', '?' => 'c', '?' => 'C', '�' => 'c', '�' => 'C', '?' => 'd', '?' => 'D', '?' => 'd', '?' => 'D', '?' => 'd', '?' => 'D', '�' => 'dh', '�' => 'Dh', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '?' => 'e', '?' => 'E', '�' => 'e', '�' => 'E', '?' => 'e', '?' => 'E', '�' => 'e', '�' => 'E', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'f', '?' => 'F', '�' => 'f', '?' => 'F', '?' => 'g', '?' => 'G', '?' => 'g', '?' => 'G', '?' => 'g', '?' => 'G', '?' => 'g', '?' => 'G', '?' => 'h', '?' => 'H', '?' => 'h', '?' => 'H', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '?' => 'i', '?' => 'I', '?' => 'i', '?' => 'I', '?' => 'i', '?' => 'I', '?' => 'j', '?' => 'J', '?' => 'k', '?' => 'K', '?' => 'l', '?' => 'L', '?' => 'l', '?' => 'L', '?' => 'l', '?' => 'L', '?' => 'l', '?' => 'L', '?' => 'm', '?' => 'M', '?' => 'n', '?' => 'N', '?' => 'n', '?' => 'N', '�' => 'n', '�' => 'N', '?' => 'n', '?' => 'N', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '?' => 'o', '?' => 'O', '�' => 'o', '�' => 'O', '�' => 'oe', '�' => 'OE', '?' => 'o', '?' => 'O', '?' => 'o', '?' => 'O', '�' => 'oe', '�' => 'OE', '?' => 'p', '?' => 'P', '?' => 'r', '?' => 'R', '?' => 'r', '?' => 'R', '?' => 'r', '?' => 'R', '?' => 's', '?' => 'S', '?' => 's', '?' => 'S', '�' => 's', '�' => 'S', '?' => 's', '?' => 'S', '?' => 's', '?' => 'S', '?' => 's', '?' => 'S', '�' => 'SS', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '�' => 'u', '�' => 'U', '�' => 'u', '�' => 'U', '?' => 'u', '?' => 'U', '�' => 'u', '�' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '�' => 'ue', '�' => 'UE', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', '�' => 'y', '�' => 'Y', '?' => 'y', '?' => 'Y', '?' => 'y', '?' => 'Y', '�' => 'y', '�' => 'Y', '?' => 'z', '?' => 'Z', '�' => 'z', '�' => 'Z', '?' => 'z', '?' => 'Z', '�' => 'th', '�' => 'Th', '�' => 'u', '?' => 'a', '?' => 'a', '?' => 'b', '?' => 'b', '?' => 'v', '?' => 'v', '?' => 'g', '?' => 'g', '?' => 'd', '?' => 'd', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'zh', '?' => 'zh', '?' => 'z', '?' => 'z', '?' => 'i', '?' => 'i', '?' => 'j', '?' => 'j', '?' => 'k', '?' => 'k', '?' => 'l', '?' => 'l', '?' => 'm', '?' => 'm', '?' => 'n', '?' => 'n', '?' => 'o', '?' => 'o', '?' => 'p', '?' => 'p', '?' => 'r', '?' => 'r', '?' => 's', '?' => 's', '?' => 't', '?' => 't', '?' => 'u', '?' => 'u', '?' => 'f', '?' => 'f', '?' => 'h', '?' => 'h', '?' => 'c', '?' => 'c', '?' => 'ch', '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => 'sch', '?' => 'sch', '?' => '', '?' => '', '?' => 'y', '?' => 'y', '?' => '', '?' => '', '?' => 'e', '?' => 'e', '?' => 'ju', '?' => 'ju', '?' => 'ja', '?' => 'ja');

    return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);

}

?>
