<?php
ob_start();

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function loginScript()
{ ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css" />
    <title>IBMS</title>
<?php }

global $_e;

$_w['You are already logged in!'] = '';
$_w['Too many login attempts. Please try after some time later!'] = '';
$_w['Email'] = '';
$_w['Password'] = '';
$_w["Forgotten your password? \n Click Here!"] = '';
$_w['Signin'] = '';
$_w['Login'] = '';
$_w['Go To Home'] = '';
$_w['SignIn'] = '';
$_w['Woops, Too Slow!'] = '';
$_w['Session expired! Please try again. This is for your own security.'] = '';
$_w['Your email or password is incorrect, please type again!'] = '';
$_w['Stop!'] = '';

$lang = $functions->ibms_setting('Default Language');
$_e = $dbF->hardWordsMulti($_w, $lang, 'Admin Login');

if ($functions->log_check()["status"] == "ok") {
    header('Location: ' . WEB_ADMIN_URL, true, 302);
    exit;
}

if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}

$_toss = $_SESSION['login_csrf'];
$alerts = "";

loginScript();
?>

<div class="wrapper container-fluid">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="mainTopMenu">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

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
                    <li class="active">
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
        <div style="margin-top: 100px;display: inline-block;">
            <div style="display: inline-block; vertical-align: middle;float: left;margin-right: 10px;">
                <img src="<?php echo WEB_ADMIN_URL; ?>/images/logo_ibms.png" width="120" alt="IBMS" />
            </div>

            <div style="font-size: 30px;float: left;display: inline-block;">
                IBMS
                <div style="display: inline-block; position: relative; vertical-align: middle;
                        font-size: 12px; text-align: left; border-left: solid #5f5f5f 1px;
                        padding-left: 5px;  margin-left: -5px;">
                    Interactive
                    Business<br>
                    Management
                    System
                </div>
            </div>

            <div style="font-size: 25px;">
                (VERSION <?php echo $functions->IBMSVersion; ?>)
            </div>
        </div>
    </div>

<?php

$fake_form = '
<div class="container-fluid">
    <div class="content_div">
        <div class="col-sm-12">
            <div style="width: 340px; position: relative; margin: 30px auto;">
                <div class="alert alert-warning">
                    <strong>Stop!</strong> ' . _uc($_e['Too many login attempts. Please try after some time later!']) . '
                </div>

                <div class="panel panel-default">
                    <div class="panel-body btn-success">' . _uc($_e['Login']) . '</div>
                    <div class="panel-footer">
                        <div class="input-group">
                            <span class="input-group-addon btn-default">' . _uc($_e['Email']) . '</span>
                            <input type="text" name="user" class="form-control" disabled="disabled">
                        </div>

                        <br>

                        <div class="input-group">
                            <span class="input-group-addon btn-default">' . _uc($_e['Password']) . '</span>
                            <input type="password" name="pass" class="form-control" disabled="disabled">
                        </div>

                        <br>

                        <div style="display: inline-block;">
                            <a href="' . WEB_ADMIN_URL . '/trouble" style="font-size: 12px;">' . _uc($_e["Forgotten your password? \n Click Here!"]) . '</a>
                        </div>

                        <button type="button" class="btn btn-primary pull-right" disabled="disabled">' . _uc($_e['Signin']) . '</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

// if ($_SESSION['login_attempts'] >= 5 && time() < $_SESSION['login_block_time']) {
//     echo $fake_form;
//     return ob_get_clean();
// }

if (
    isset($_POST['_toss'], $_POST['user'], $_POST['pass']) &&
    trim((string)$_POST['user']) !== '' &&
    (string)$_POST['pass'] !== ''
) {
    $csrfOk = isset($_SESSION['login_csrf']) && hash_equals($_SESSION['login_csrf'], (string)$_POST['_toss']);

    if (!$csrfOk) {
        $alerts .= "<div class='alert alert-warning'><strong>" . _uc($_e['Woops, Too Slow!']) . "</strong> " . _n($_e['Session expired! Please try again. This is for your own security.']) . "</div>";
    } else {
        $user = trim((string)$_POST['user']);
        $pass = (string)$_POST['pass'];

        $login_req = $functions->login($user, $pass);
        
        if ($login_req === 'blocked') {
            $alerts .= "<div class='alert alert-warning'>
                <strong>" . _uc($_e['Stop!']) . "</strong>
                Too many failed login attempts. Please try again after 10 minutes.
            </div>";
        } elseif ($login_req === false) {
            $alerts .= "<div class='alert alert-danger'>
                <strong>" . _uc($_e['Stop!']) . "</strong>
                " . _n($_e['Your email or password is incorrect, please type again!']) . "
            </div>";
        }
    }

    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
    $_toss = $_SESSION['login_csrf'];
}

$action_url = "do-login.secure";
?>

<div class="container-fluid">
    <div class="content_div">
        <div class="col-sm-12">
            <div style="width: 340px; position: relative; margin: 30px auto;">
                <?php echo $alerts; ?>

                <div class="panel panel-default">
                    <div class="panel-body btn-success"><?php echo _uc($_e['Login']); ?></div>

                    <div class="panel-footer">
                        <form method="post" action="<?php echo e($action_url); ?>" autocomplete="off">
                            <input type="hidden" name="_toss" value="<?php echo e($_toss); ?>">

                            <div class="input-group">
                                <span class="input-group-addon btn-default"><?php echo _uc($_e['Email']); ?></span>
                                <input type="email" name="user" class="form-control" required="required" autocomplete="username">
                            </div>

                            <br>

                            <div class="input-group">
                                <span class="input-group-addon btn-default"><?php echo _uc($_e['Password']); ?></span>
                                <input type="password" name="pass" class="form-control" required="required" autocomplete="current-password">
                            </div>

                            <br>

                            <div style="display: inline-block;">
                                <a href="<?php echo WEB_ADMIN_URL; ?>/trouble" style="font-size: 12px;">
                                    <?php echo _uc($_e["Forgotten your password? \n Click Here!"]); ?>
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">
                                <?php echo _uc($_e['Signin']); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php
return ob_get_clean();
?>
