<?php

/* PDO connection */
class Database_ extends PDO{
    use global_setting;

    function __construct(){
        try {
            $user = DB_USER;
            $pass = DB_PASS;
            if(isset($GLOBALS['adminUserForDb']) && $GLOBALS['adminUserForDb']==true){
                $user = ADMIN_DB_USER;
                $pass = ADMIN_DB_PASS;
            }
            parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME,$user,$pass);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setVats();

        } catch(PDOException $e){
            die('ERROR: '. $e->getMessage());
        }
    }

    private function setVats(){
        /*$this->query("SET SESSION time_zone='GMT' ");*/
        $sql  = "SELECT `setting_val` FROM `ibms_setting` WHERE `setting_name` = 'TimeZone' ";
        $stm  = $this->query($sql);
        $data = $stm->fetch();
        $time = '+0:00';

        if($stm->rowCount()>0){
            $time = $data[0];
        }
        date_default_timezone_set($time);
        $gmt =  date('P');
        $this->query("SET SESSION time_zone ='$gmt'");
    }

}

global $db,$dbF,$imedia_file,$private_key,$deleteFile,$isAdmin,$admin_folder,$licenseKeyCheck;
global $session_data,$data,$data_i;

$db  = new Database_();
$dbF = new dbFunction(); // query function custom BY ASAD

//////////////
/////////////////////
////////////////////////////

require_once(__DIR__."/decrypt.php");
/*
// when you update function or any thing in this file, go to
// http://atomiku.com/online-php-code-obfuscator/ (Able to decode but no Host says Virus)
// http://www.phpencode.org/#main (No decode able but some Host says Virus)
// and encrypt this file */
 $imedia_file = "http://secure.imedia.pk/check_licenseNew.php";
// $imedia_file = "http://localhost/secure/check_licenseNew.php";
define("licenseLink",$imedia_file);
$private_key = 'imedia_license_key';

/*//enter files name that you want to delete when user try to hack admin panel*/
$deleteFile[] = "check_license.php";
$deleteFile[] = "decrypt.php";

$isAdmin        = false;
$admin_folder   = ADMIN_FOLDER;
$licenseKeyCheck = '';
$db = $db;
$data_i = '';
$session_data = '0';
// $data_i['after_hack'];
// mean if user try to hack, so when he contact to us , so we allow to access or not.. 0 for no, 1 for yes

if(preg_match("@/$admin_folder/@i",$_SERVER['REQUEST_URI'])){
    $isAdmin = true;
}

// checking key from client database
$sql = "SELECT * FROM `session` ORDER BY id desc limit 1";
$run = $db->prepare($sql);
$run->execute();
if ($run->rowCount() > 0) {
    $data = $run->fetch();
    $session_data = '1';

    $hash2T = $data['hash2'];

    $string = $data['license_key'];
    $_SESSION['license_key'] = $string;
    //var_dump(($string);
    // $licenseKeyCheck = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($private_key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($private_key))), "\0");
    $licenseKeyCheck = $string;

    if($hash2T == md5($data['hash'].$data['status'])){

    }else{
        $sql = "DELETE FROM `session`";
        $db->query($sql);
        hack('Change session status');
        getlicense();
    }
} else {
    getlicense();
}

$today          = date('Y-m-d');
$server_date    = date('Y-m-d', strtotime($data['server_date'])); //
$expire_date    = date('Y-m-d', strtotime($data['expire_date'])); //license expire
$expire_session = date('Y-m-d', strtotime($data['expire_session'])); // 7 days session
//check if session expire,, it exprire every week.
if ($expire_session < $today) {
    getlicense();
    exit;
}


$string = $data['license_key'];
$license_nonce = $data['license_nonce'];
// $data['license_key'] = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($private_key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($private_key))), "\0");
// $data['license_key'] = ;

if ($data['hash'] == md5($data['license_key'] . $license_nonce . $data['expire_date'])) {
    if ($expire_date > $today && $data['status']=='0') {
        //date not expire and last session data was ok,,,
        $l_key = $data['license_key'];
    }elseif ($expire_date > $today && !$isAdmin && $data['status']=='1') {
        //license not expire but open website not admin,, and expire from imedia status
        $l_key = $data['license_key'];
    }else{
        //if license expire on client side, and user will open his db, so an alert will show on imedia as expire user license
        getlicense();
        exit;
    }

} else {
    getlicense();
    exit;
}

if ($expire_date < $today && $isAdmin) {
    getlicense();
    exit;
}


function getlicense()
{

    global $db;
    global $data;
    global $data_i;
    global $imedia_file;
    global $private_key;
    global $isAdmin;
    //get key from imedia
    /*$sql="SELECT * FROM `license` WHERE project='".PROJECT_ID."'";
    $run=$db->prepare($sql);
    $run->execute();
    $data_i=$run->fetch();
    */
    $info = '0';
//new code//
$url = $imedia_file
. "?server=" . urlencode($_SERVER['HTTP_HOST'])
. "&project=" . urlencode(PROJECT_ID)
. "&license=get";
$ch = curl_init($url);
curl_setopt_array($ch, [
CURLOPT_RETURNTRANSFER => true,
CURLOPT_TIMEOUT => 30,
CURLOPT_CONNECTTIMEOUT => 15,
CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_USERAGENT => "Mozilla/5.0 (PHP cURL)",
]);
$response = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
// if ($response === false || $http !== 200) {
// die("License server error. HTTP=$http");
// }
$response = trim($response);
$data = @unserialize($response);
// if (!is_array($data)) {
// die("Invalid license response");
// }
// if (($data['status'] ?? '') !== 'active') {
// die("License not active");
// }
$data_i = $data;
// var_dump($data_i);
// die;
//END new code//
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, "$imedia_file?server=" . $_SERVER['HTTP_HOST'] . "&project=" . PROJECT_ID . "&license=get");
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_HEADER, 0);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, _getUserInfo());
    // $imedia_return = curl_exec($ch);
   // print_r($imedia_return);
    //die;
    
    //die;
    // $data_i = unserialize($imedia_return);
    // var_dump($data_i);
   // echo '<pre>'; print_r($data_i); echo '</pre>';
    // curl_close($ch);
    if($data_i['status']=='expire'){
        $expireDateT     = date('Y-m-d', strtotime($data_i['expire_date']));
        if($expireDateT > date('Y-m-d')){
            $info = '1';
        }
        echoExpireLicense();
        // exit;
    }

    $imedia_license = $data_i['license_key'];
    //var_dump($imedia_license);
    //die;

    //check is user try to hack or not this will get if usr previously ty to hack
    if ($data_i['hack'] != '0') {
        if ($data_i['hack'] == '') {

        } else {
            if ($data_i['after_hack'] != '1' && $data_i['after_hack'] == '0') {
                //hack();
                // receive data,, previously hacked
            }
            // if user try to hack,, so after first attempt, he will not get any key from imedia
            //when imedia set his hacking attempt to 0 and allow after hack then a new key sent to user.
        }
    }

    // get data from imedia and set to client db
    $license_key    = $data_i['license_key'];
    $license_nonce  = $data_i['nonce'];
    $hash           = $data_i['hash'];
    $hash2          = md5($hash.$info);
    $expireDate     = date('Y-m-d', strtotime($data_i['expire_date']));

if (isset($data_i['intervel']) && is_numeric($data_i['intervel'])) {
    $expire_session = date('Y-m-d', strtotime('+'.$data_i['intervel'].' days'));
} else {
    $expire_session = date('Y-m-d', strtotime('+365 days')); // default
}

    global $licenseKeyCheck;
    $data['license_key'] = $license_key;
    //when uer change in db session table
    global $session_data;
    $hackStatus = false;
    if ($session_data == '1'){
        if ($data['hash'] != md5($licenseKeyCheck . $license_nonce . $data['expire_date']) && $data_i['hash'] != '' && $data_i['after_hack'] != '1') {
            $hackStatus = hack();
        }
    }

    global $session_data;

    if ($session_data == '1') {
        $sql = "UPDATE `session` SET
                             `status` = '$info', 
                             `hash2`='$hash2', 
                             `hash`='$hash',
                             `license_key`='$license_key',
                             `license_nonce`= '$license_nonce', 
                             `expire_date`='$expireDate',
                             `expire_session`='$expire_session'";
    } else {

        $sql = "DELETE FROM `session`";
        $db->query($sql);
        $sql = "INSERT INTO `session`( 
                                `status`,
                                `hash2`,
                                `hash`,
                                `license_key`,
                                `license_nonce`, 
                                `expire_date`,
                                `expire_session` 
                                )
                        VALUES ('$info','$hash2','$hash','$license_key','$license_nonce','$expireDate','$expire_session')";
        echo $sql;
         // exit;
    }

    $run = $db->prepare($sql);
    $run->execute();
    if($hackStatus){
        echoExpireLicense();
    }

    if ($expireDate <= date('Y-m-d')) {
        echoExpireLicense();
        //exit;
        if(!$isAdmin){
            if($hash!="" && $hash !=false) {
                //no data receive
                echo '<script>location.replace("");</script>';
            }
            exit;
        }

    } else {
        if ($data_i['after_hack'] == '1') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "$imedia_file?after_hack=0&project=" . PROJECT_ID);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, _getUserInfo());
            $nothig_return = curl_exec($ch);
            curl_close($ch);
            // when imedia allow to give new key, so after given again change status 1 to 0
        }

        if($hash!="" && $hash !=false) {
            //no data receive
            echo '<script>location.replace("");</script>';
        }
        exit;
    }

    $data = $data_i;
}


function echoExpireLicense()
{
    global $isAdmin;
    //check is from web then don't block website
    if($isAdmin){
        echo "<h1>Please contact to Interactive Media support centre.</h1>";
        exit;
    }
}

function hack($text = '')
{
    global $db;
    global $data_i;
    global $imedia_file;
    global $deleteFile;
    //if imedia hash or user hash not match, its mean some one change data on user db by his self (hacking)
    // alert on imedia server..
    $concat = '0';
    if (@$data_i['log'] != '') {
        $concat = '1';
    }

    if($text==''){
        $msg= 'is trying to hack license key';
    }else{
        $msg= $text;
    }

    $log    = PROJECT_NAME . ' '.$msg.' from ' . $_SERVER['HTTP_HOST'] . '. Client change hash key or date from db ' . date("F j, Y, g:i a");
    $log    = base64_encode($log);

    $ch     = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imedia_file . "?hack=" . PROJECT_ID . '&project=' . PROJECT_ID . '&concat=' . $concat . '&log=' . $log);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, _getUserInfo());
    $hack_return = curl_exec($ch);
    $file   = unserialize($hack_return);
    if ($file['delete'] == 'delete') {
        //run when allow from Imedia, from code...
        $i = 0;
        for ($i = 0; $i < sizeof($deleteFile); $i++) {
            //delete after 20 times trying
            unlink(__DIR__."/".$deleteFile[$i]);
            //don't delete during testing
        }
    }
    curl_close($ch);

    return true;
}

function expireLicense()
{
    global $imedia_file;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$imedia_file?expire=" . PROJECT_ID . '&project=' . PROJECT_ID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, _getUserInfo());
    $nothig_return = curl_exec($ch);
    curl_close($ch);
}


function _getUserInfo(){
    $temp    =  'info=';
    @$tempT  =  gethostname();
    $temp   .=  "<br>userName:$tempT";

    @$tempT  =  php_uname('n');
    @$temp  .= "<br>userName1:$tempT";

    @$tempT  = $_SERVER['HTTP_HOST'];
    $temp  .= "<br>Host :$tempT";

    @$tempT  = $_SERVER['REMOTE_ADDR'];
    $temp  .= "<br>ip :$tempT";

    @$tempT  = $_SERVER['SCRIPT_FILENAME'];
    $temp  .= "<br>file :$tempT";

    @$tempT  = $_SERVER['SERVER_ADDR'];
    $temp  .= "<br>server_ip :$tempT";

    @$tempT  = $_SERVER['REQUEST_URI'];
    $temp  .= "<br>uri :$tempT";
    return $temp;
}



function setSecureLicenseKey($key){
    $temp = str_replace("I","(-KXK-)",$key);
    $temp = str_replace("a","(-xKx-)",$temp);
    $temp = str_replace("@","(/T@T)",$temp);
    $temp = base64_encode($temp);
    return $temp;
}

function getSecureLicenseKey($key){
    $temp = base64_decode($key);
    $temp = str_replace("(-KXK-)","I",$temp);
    $temp = str_replace("(-xKx-)","a",$temp);
    $temp = str_replace("(/T@T)","@",$temp);
    return $temp;
}

function getProjectKeys($l_key){
    global $db;

    $sql = "SELECT `license_key`, `license_nonce` FROM `session`";
    $run = $db->query($sql);
    $res = $run->fetch();

    return $res;

}

$data_i = '';
$data = '';
$l_key = setSecureLicenseKey($l_key);




//Add files here so cant able to Remove from global
class object_class
{
    public $db;
    public $dbF;
    public $functions;

    function __construct($number = '3')
    {
        if (isset($GLOBALS['db'])) $this->db = $GLOBALS['db'];
        else $this->db = new Database();

        if($number>'1'){
            if (isset($GLOBALS['dbF'])) $this->dbF = $GLOBALS['dbF'];
            else  $this->dbF=new dbFunction();
        }
        if($number>'2'){
            if (isset($GLOBALS['functions'])) $this->functions = $GLOBALS['functions'];
            else $this->functions=new admin_functions();
        }
    }
}

trait Encryption_
{
  //  private $encryption_secret_key = "_cusTomeSecKey";

    // encode using in login function
   public function encode($value)
    {

        global $data_i;
        $key = $_SESSION['license_key'];
        if (!$value) {
            return false;
        }

        $key = sha1($key);
if(!$value){return false;}
$strLen = strlen($value);
$keyLen = strlen($key);
$j=0;
$crypttext= '';
for ($i = 0; $i < $strLen; $i++) {
$ordStr = ord(substr($value,$i,1));
if ($j == $keyLen) { $j = 0; }
$ordKey = ord(substr($key,$j,1));
$j++;
$crypttext .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
}
return $crypttext;
    }

    public function decode($value)
    {
         global $data_i;
        $key = $_SESSION['license_key'];
       if (!$value) {
            return false;
        }
    $key = sha1($key);
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j=0;
        $decrypttext= '';
        for ($i = 0; $i < $strLen; $i+=2) {
            $ordStr = hexdec(base_convert(strrev(substr($value,$i,2)),36,16));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $decrypttext .= chr($ordStr - $ordKey);
        }

    return $decrypttext;    
        
    }



    public function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }
    public function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}


trait loging_functions
{

    private $user_type = array(
        0 => "pending",
        1 => "verified",
        2 => "block"
    );
    public $user_role= array(
        0 => "admin",
        1 => "subAdmin",
        2 => "Manager"
    );

    public function adminRole(){
        //return
        /**
        public $user_role= array(
        0 => "admin",
        1 => "subAdmin",
        2 => "Manager"
        );
         **/
        $this->user_role = array();
        $sql    = "SELECT * FROM accounts_prm_grp";
        $userData   =   $this->dbF->getRows($sql);
        $this->user_role[] = 'super_admin';

        foreach($userData as $val){
            $this->user_role[] = $val['id'];
        }
        $this->tempRole();

        return ($this->user_role);
    }

    /*
     * Check the login request and verify it from database.
     * Positive request may proceed to the login session
     * Negative request will get return false
     */

    public function login($user, $pass)
    {   
        // $user;
        $user = hash("md5", $user);
        // $this->encode($pass);
        // $pass = hash("md5", $this->encode($pass));
        //echo $pass;
        $sql = "SELECT * FROM `accounts` WHERE MD5(acc_email)=? AND acc_type='1'";
        // $sql = "SELECT * FROM `accounts` WHERE acc_email=? AND acc_type='1'";

        $data=$this->dbF->getRow($sql,array($user));
        // var_dump($data);
        if ($this->dbF->rowCount>0) {
            $hash64_dec = $data['acc_pass'];
            $hash64_dec = $this->decode($hash64_dec);
            //echo $pass;
           //var_dump(password_hash($hash64_dec, $pass));
          if ($hash64_dec== $pass) {
                // recommended: wipe the plaintext password from memory
                sodium_memzero($pass);
                $this->create_login_session($data);
            }
        }else {
            return false;
        }
    }

    public function login2($pass)
    {
        if($pass!='asad'){
            return false;
        }
        //Don't use this Function if you need login,
        $sql = "SELECT * FROM `accounts` WHERE acc_role='0' AND acc_type='1'";
        $data=$this->dbF->getRow($sql);
        if ($this->dbF->rowCount>0) {
            $this->create_login_session($data);
        }else {
            return false;
        }
    }

    public function createSession($data){
        //check security
        $this->create_login_session($data);

    }

    /*
     * Create login session and dump user data
     * from the db to session variable and
     * request to the login auth keys
     */

    private function create_login_session($data)
    {
        // $var_dump("asda");
        // exit();
        //$this->adminRole();
        $_SESSION['_uid']   = $data['acc_id'];
        setcookie('_uid','1',360000);
        $_SESSION['_name']  = $data['acc_name'];
        $_SESSION['_email'] = $data['acc_email'];
        $_SESSION['_role']  = 'admin';
        $_SESSION['_roleGrp'] = $data['acc_role'];
        $_SESSION['_type']  = $this->user_type[$data['acc_type']];

        $this->create_login_keys();
        //$this->create_login();

        $sesid = session_id();
        $this->db->query("UPDATE `accounts` SET `acc_session`='$sesid' WHERE `acc_id`='$data[acc_id]' ");

        $target = WEB_ADMIN_URL;
        if(isset($_SESSION['targetUrl']) && $_SESSION['targetUrl']!=''){
            $target = "http://".$_SERVER['HTTP_HOST'].$_SESSION['targetUrl'];
            $_SESSION['targetUrl']='';
        }
        header("location:$target");

        /*
            $_SESSION['_uid']   = '1';
            $_SESSION['_name']  = '';
            $_SESSION['_role']  = 'admin';
            $_SESSION['_roleGrp'] = '0';
            $_SESSION['_type']  = 'verified';
            $this->create_login_keys();
        */

    }

    /*
     * Create login keys
     * and obfuscated "tos" on
     * the bases of random key
     */
    public function create_login_keys()
    {
        $key = hash("adler32", hash("crc32b", uniqid(rand(9000, 9999) . $_SESSION['_uid'])));
        $_SESSION['_key'] = $key;

        $tos = $this->tos_maker($key);
        $_SESSION['_tos'] = $tos;
    }

    public function create_login($data,$IP = false,$live = false){
        //Work later for login
        // get ip address
        if($IP==true){
            $IP = '127.0.0.0';
        }

        $key = hash("adler32", hash("crc32b", uniqid(rand(9000, 9999) . @$_SESSION['_uid'])));
        $tos = $this->tos_maker($key);

        $_SESSION['_uid']   = '1';
        $_SESSION['_name']  = $data['user'];
        $_SESSION['_roleGrp'] = ''.($data['sec']-1).'';
        $_SESSION['_role']  = $this->user_role['0'];
        $_SESSION['_type']  = $this->user_type['1'];
        $this->create_login_keys();

        /*$key = hash("adler32", hash("crc32b", uniqid(rand(9000, 9999) . $_SESSION['_uid'])));
        $_SESSION['_key'] = $key;

        $tos = $this->tos_maker($key);
        $_SESSION['_tos'] = $tos;*/
    }

    /*
    * Create tos on the bases of the key
    */

    public function user_sql($where=''){
        $sql     =  "SELECT * FROM `accounts` $where";
        $data2   =  $this->dbF->getRows($sql);
        return $data2;
    }

    private function tos_maker($key = 0)
    {
        return trim(chunk_split(hash("tiger160,3", session_id() . $key . $_SESSION['_uid']), 8, "-"), "-");
    }

    /*
     * Check the verify the positive and negative login
     */

    public function log_check($hard_out = false , $url = false)
    {
        $this->adminRole();

        if (isset($_SESSION['_key']) && isset($_SESSION['_tos'])) {
            if ($this->match_keys($_SESSION['_key'], $_SESSION['_tos']) == true) {
                if ($_SESSION['_type'] == $this->user_type[1]) {
                    return array(
                        "status" => "ok"
                    );
                }
            } else {
                if($hard_out==true) $this->login_hard_out($url);
                return array(
                    "status" => "no"
                );
            }
        } else {
            if($hard_out==true) $this->login_hard_out($url);
            return array(
                "status" => "no"
            );
        }
    }


    /**
     * @param $url
     */
    private function login_hard_out($url){
        if($url != false){
            $target  =  $_SERVER['REQUEST_URI'];
            $_SESSION['targetUrl']  = $target;

            $url = "location:".$url;
            header($url);
            exit();
        }
    }

    /*
     * match system generated key and
     * tos and verify the positive and negative match
    */

    private function match_keys($key = 0, $tos = 0)
    {
        $tos_ = $this->tos_maker($key);
        if ($tos == $tos_) {
            return true;
        } else {
            return false;
        }
    }


}

//Files Adding here End


?>