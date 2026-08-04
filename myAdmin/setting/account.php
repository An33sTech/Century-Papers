<?php
ob_start();
global $dbF;
$functions->require_once_custom('setting.class.php');
$setting    =  new setting();

$setting->AccountSubmit();
$accountData = $setting->getAccoutSettingData();
?>
<h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Account Setting']); ?></h4>



    <div class="container-fluid">
        <form action="" method="post" class="form-horizontal">
            <input type="hidden" value="<?php echo $accountData['acc_id']; ?>" name="userId" />
            <?php $functions->setFormToken('AccountSetting'); ?>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Account Name']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <input type="text" required value="<?php echo htmlspecialchars($accountData['acc_name'], ENT_QUOTES, 'UTF-8'); ?>" name="acc_name" id="acc_name" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Email']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <input type="email" required value="<?php echo htmlspecialchars($accountData['acc_email'], ENT_QUOTES, 'UTF-8'); ?>" name="acc_email" id="acc_email" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label"><?php echo _uc($_e['Current Password']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <input type="password"
                           name="current_password"
                           id="current_password"
                           class="form-control"
                           autocomplete="current-password"
                           placeholder="Required only if changing password">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label"><?php echo _uc($_e['Password']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <input type="password"
                           value=""
                           onChange="passM();"
                           onkeyup="passM();"
                           name="password"
                           id="pass"
                           class="form-control"
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="<?php echo _uc($_e['Leave Blank If not want to update']); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label"><?php echo _uc($_e['Retype Password']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <input type="password"
                           value=""
                           onChange="passM();"
                           onkeyup="passM();"
                           name="retype_password"
                           id="rpass"
                           class="form-control"
                           minlength="8"
                           autocomplete="new-password">
                    <div id="pm"></div>
                </div>
            </div>

            <button type="submit" id="signup_btn" class="btn btn-primary btn-lg"><?php echo _u($_e['UPDATE']); ?></button>
        </form>
    </div>

<script src="webUsers/js/user.js"></script>
    <script>
        $(function(){
            dateJqueryUi();
        });
    </script>
<?php return ob_get_clean(); ?>
