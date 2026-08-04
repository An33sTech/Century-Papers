<?php
include_once(__DIR__ . "/../global.php");

global $dbF, $functions;

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$token = isset($_GET['token']) ? trim((string)$_GET['token']) : '';
$tokenHash = $token !== '' ? hash('sha256', $token) : '';

$msgHtml = '';
$validToken = false;
$account = false;

if (empty($_SESSION['reset_csrf'])) {
    $_SESSION['reset_csrf'] = bin2hex(random_bytes(32));
}

function strongPasswordCheck(string $password): array
{
    if (strlen($password) < 8) {
        return [false, 'Password must be at least 8 characters long.'];
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return [false, 'Password must contain at least one uppercase letter.'];
    }

    if (!preg_match('/[a-z]/', $password)) {
        return [false, 'Password must contain at least one lowercase letter.'];
    }

    if (!preg_match('/[0-9]/', $password)) {
        return [false, 'Password must contain at least one number.'];
    }

    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return [false, 'Password must contain at least one special character.'];
    }

    return [true, ''];
}

if ($tokenHash !== '') {
    $sql = "SELECT `acc_id`, `acc_email`, `reset_token_expire`
            FROM `accounts`
            WHERE `reset_token_hash` = ?
            LIMIT 1";

    $account = $dbF->getRow($sql, [$tokenHash]);

    if ($dbF->rowCount > 0 && is_array($account)) {
        $expireTime = strtotime($account['reset_token_expire']);

        if ($expireTime !== false && $expireTime >= time()) {
            $validToken = true;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = isset($_POST['_csrf']) ? (string)$_POST['_csrf'] : '';

    $postedToken = isset($_POST['token']) ? trim((string)$_POST['token']) : '';
    $postedHash = $postedToken !== '' ? hash('sha256', $postedToken) : '';

    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

    if (
        empty($_SESSION['reset_csrf']) ||
        !hash_equals($_SESSION['reset_csrf'], $csrf)
    ) {
        $msgHtml = "<div class='alert alert-danger'>Security token expired. Please try again.</div>";
        $validToken = false;
    } elseif ($postedHash === '') {
        $msgHtml = "<div class='alert alert-danger'>Invalid reset token.</div>";
        $validToken = false;
    } elseif ($password !== $confirmPassword) {
        $msgHtml = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        [$passwordOk, $passwordError] = strongPasswordCheck($password);

        if (!$passwordOk) {
            $msgHtml = "<div class='alert alert-danger'>" . e($passwordError) . "</div>";
        } else {
            $sql = "SELECT `acc_id`, `reset_token_expire`
                    FROM `accounts`
                    WHERE `reset_token_hash` = ?
                    LIMIT 1";

            $account = $dbF->getRow($sql, [$postedHash]);

            if (
                !$account ||
                $dbF->rowCount <= 0 ||
                strtotime($account['reset_token_expire']) < time()
            ) {
                $msgHtml = "<div class='alert alert-danger'>Reset link is invalid or expired.</div>";
                $validToken = false;
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $sql = "UPDATE `accounts`
                        SET `acc_pass` = ?,
                            `reset_token_hash` = NULL,
                            `reset_token_expire` = NULL,
                            `acc_session` = ''
                        WHERE `acc_id` = ?";

                $dbF->setRow($sql, [$passwordHash, (int)$account['acc_id']]);

                $_SESSION['reset_csrf'] = bin2hex(random_bytes(32));

                $validToken = false;
                $msgHtml = "<div class='alert alert-success'>Password updated successfully. You can login now.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password - IBMS</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/style.css">
</head>

<body>

<div class="wrapper container-fluid">
    <div class="IBMS_LOGO col-sm-12 text-center">
        <div style="margin-top: 70px;display: inline-block;">
            <img src="<?php echo WEB_ADMIN_URL; ?>/images/logo_ibms.png" width="120" alt="IBMS">
            <h2>Reset Password</h2>
        </div>
    </div>

    <div class="container-fluid">
        <div class="content_div">
            <div class="col-sm-12">
                <div style="max-width: 420px;margin: 30px auto;">

                    <?php echo $msgHtml; ?>

                    <?php if ($validToken) { ?>
                        <div class="panel panel-default">
                            <div class="panel-body btn-success">Set New Password</div>

                            <div class="panel-footer">
                                <form method="post" action="">
                                    <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                    <input type="hidden" name="_csrf" value="<?php echo e($_SESSION['reset_csrf']); ?>">

                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password"
                                               name="password"
                                               class="form-control"
                                               required
                                               minlength="8"
                                               autocomplete="new-password">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password"
                                               name="confirm_password"
                                               class="form-control"
                                               required
                                               minlength="8"
                                               autocomplete="new-password">
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        Update Password
                                    </button>

                                    <a href="<?php echo WEB_ADMIN_URL; ?>" class="btn btn-success">
                                        Login
                                    </a>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="panel panel-default">
                            <div class="panel-footer text-center">
                                <a href="<?php echo WEB_ADMIN_URL; ?>/trouble" class="btn btn-primary">
                                    Request New Reset Link
                                </a>

                                <a href="<?php echo WEB_ADMIN_URL; ?>" class="btn btn-success">
                                    Login
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $functions->adminFooter(); ?>

</body>
</html>
