<?php
require("global.php");

if (!empty($_SESSION['_uid'])) {
    $sql = "UPDATE `accounts`
            SET `acc_session` = ''
            WHERE `acc_id` = ?";

    $dbF->setRow($sql, [(int)$_SESSION['_uid']]);
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires'  => time() - 3600,
        'path'     => $params['path'] ?? '/',
        'domain'   => $params['domain'] ?? '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

setcookie('_uid', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_unset();
session_destroy();

header("Location: " . WEB_ADMIN_URL, true, 302);
exit;
