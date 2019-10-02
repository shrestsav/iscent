<?php
ob_start();
//require_once("classes/setting.class.php");
global $dbF;
$functions->require_once_custom('setting.class.php');
$setting    =  new setting();

//$functions->require_once_custom('webUsers.class');
//$WebUser = new webUsers();
//$WebUser->adminUserEditSubmit();

$setting->AccountSubmit();
$accountData = $setting->getAccoutSettingData();
?>
<h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Account Setting']); ?></h4>

<?php
//$id = $_SESSION['_uid'];
//$WebUser->newAdminUser($id,'account'); ?>


    <div class="container-fluid">
        <form action="" method="post" class="form-horizontal">
            <input type="hidden" value="<?php echo $accountData['acc_id']; ?>" name="userId" />
            <?php $functions->setFormToken('AccountSetting'); ?>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Account Name']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $accountData['acc_name'];
                    ?>
                    <input type="text" required="" value="<?php echo $temp; ?>" name="acc_name" id="acc_name" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Email']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $accountData['acc_email'];
                    ?>
                    <input type="email" required="" value="<?php echo $temp; ?>" name="acc_email" id="acc_email" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Password']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    ?>
                    <input type="text" value="" onChange="passM();" name="password" id="pass" class="form-control" placeholder="<?php echo _uc($_e['Leave Blank If not want to update']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Retype Password']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    ?>
                    <input type="text" value="" onChange="passM();" onkeyup="passM();" name="retype_password" id="rpass" class="form-control">
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