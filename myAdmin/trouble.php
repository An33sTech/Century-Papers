<?php
include_once(__DIR__ . "/../global.php");

global $dbF, $db, $_e, $functions;

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

global $_e;

$_w['An email is sent. Please check your emails.'] = '';
$_w['Email Sent Fail.Please Try Again.'] = '';
$_w['Password Trouble Shooting'] = '';
$_w['Security Captcha'] = '';
$_w['Email'] = '';
$_w['Send Email'] = '';
$_w['Incorrect Email.'] = '';
$_w['Please Type Captcha Code'] = '';
$_w['Please type your email address in the given field.'] = '';
$_w['Signin'] = '';
$_w['Login'] = '';
$_w['Go To Home'] = '';
$_w['SignIn'] = '';
$_w['LOGIN'] = '';

$lang = $functions->ibms_setting('Default Language');
$_e = $dbF->hardWordsMulti($_w, $lang, 'Admin Trouble');

$msgHtml = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
    $code  = isset($_POST['code']) ? trim((string)$_POST['code']) : '';

    if (!isset($_SESSION["rand_code"]) || $code !== (string)$_SESSION["rand_code"]) {
        $msgHtml = "<div class='alert alert-danger'>Captcha Code Incorrect. Please try again.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgHtml = "<div class='alert alert-danger'>" . _uc($_e["Incorrect Email."]) . "</div>";
    } else {
        if ($functions->isPasswordResetBlocked($email)) {
            $msgHtml = "<div class='alert alert-warning'>Too many reset requests. Please try again after 15 minutes.</div>";
        } else {
            $sql = "SELECT `acc_id`, `acc_name`, `acc_email`
                    FROM `accounts`
                    WHERE `acc_email` = ?
                    AND `acc_type` = ?
                    LIMIT 1";
    
            $data = $dbF->getRow($sql, [$email, 1]);
    
            $msgHtml = "<div class='alert alert-success'>" . _n($_e["An email is sent. Please check your emails."]) . "</div>";
    
            if ($dbF->rowCount > 0 && is_array($data)) {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $expire = date('Y-m-d H:i:s', time() + 1800); // 30 minutes
    
                $sql = "UPDATE `accounts`
                        SET `reset_token_hash` = ?,
                            `reset_token_expire` = ?
                        WHERE `acc_id` = ?";
    
                $dbF->setRow($sql, [$tokenHash, $expire, (int)$data['acc_id']]);
    
                $resetLink = WEB_ADMIN_URL . "/reset-password?token=" . urlencode($token);
    
                $mailArray = [];
                $mailArray['name'] = $data['acc_name'];
                $mailArray['email'] = $data['acc_email'];
                $mailArray['reset_link'] = $resetLink;
                $mailArray['link'] = WEB_ADMIN_URL;
    
                $functions->send_mail($data['acc_email'], '', '', 'accountTrouble', $data['acc_name'], $mailArray);
                $functions->recordPasswordResetRequest((int)$data['acc_id'], $email);
            } else {
                $functions->recordPasswordResetRequest(null, $email);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>IBMS v<?php echo $functions->IBMSVersion; ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/style.css">
</head>

<body>

<div class="preloader-it">
    <div class="la-anim-1"></div>
</div>

<div class="wrapper container-fluid">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="mainTopMenu">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand visible-xs" href="<?php echo WEB_URL; ?>">
                    <i class="fa fa-home"></i>
                </a>
            </div>

            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="<?php echo WEB_URL; ?>">
                            <i class="fa fa-home" style="font-size: 18px"></i>
                            <?php echo $_e['Go To Home']; ?>
                        </a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?php echo WEB_URL; ?>/do-login.secure">
                            <i class="glyphicon glyphicon-log-in"></i>
                            <?php echo $_e['SignIn']; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="IBMS_LOGO col-sm-12 text-center">
        <div style="margin-top: 70px;display: inline-block;">
            <div style="display: inline-block; vertical-align: middle;float: left;margin-right: 10px;">
                <img src="<?php echo WEB_ADMIN_URL; ?>/images/logo_ibms.png" width="120" alt="IBMS">
            </div>

            <div style="font-size: 30px;float: left;display: inline-block;">
                IBMS
                <div style="display: inline-block; position: relative; vertical-align: middle;
                    font-size: 12px; text-align: left; border-left: solid #5f5f5f 1px;
                    padding-left: 5px; margin-left: -5px;">
                    Interactive Business<br>Management System
                </div>
            </div>

            <div style="font-size: 25px;">
                (VERSION <?php echo $functions->IBMSVersion; ?>)
            </div>
        </div>
    </div>

    <div id="container_div" class="page-wrapper">
        <div class="content_div">
            <div class="col-sm-12">
                <div style="max-width: 580px;margin: 10px auto">
                    <div class="btn-success" style="padding: 8px;">
                        <div><?php echo _uc($_e['Password Trouble Shooting']); ?></div>
                    </div>

                    <div class="panel-default">
                        <div class="panel-footer">

                            <?php echo $msgHtml; ?>

                            <div class="text_inner text-center">
                                <p><?php echo _n($_e['Please type your email address in the given field.']); ?></p>
                                <br>

                                <form method="post" action="" class="again form-horizontal">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            <?php echo _uc($_e['Email']); ?>
                                        </label>

                                        <div class="col-sm-10">
                                            <input type="email"
                                                   required
                                                   value="<?php echo e($email); ?>"
                                                   class="form-control"
                                                   name="email"
                                                   id="inputEmail3"
                                                   placeholder="<?php echo _uc($_e['Email']); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            <?php echo _uc($_e['Security Captcha']); ?>
                                        </label>

                                        <div class="col-sm-10">
                                            <div class="col-sm-5">
                                                <img src="<?php echo WEB_URL; ?>/captcha.php" alt="Captcha">
                                            </div>

                                            <div class="col-sm-7">
                                                <input type="text"
                                                       class="form-control"
                                                       name="code"
                                                       placeholder="<?php echo _uc($_e['Please Type Captcha Code']); ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <a href="<?php echo WEB_ADMIN_URL; ?>" class="btn btn-success">
                                                <?php echo _u($_e['LOGIN']); ?>
                                            </a>

                                            <button type="submit" class="btn btn-primary defaultSpecialButton">
                                                <?php echo _uc($_e['Send Email']); ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php $functions->adminFooter(); ?>

</body>
</html>
