<?php
    include_once(__DIR__."/../global.php");
//Encrypt After here

//these are varibales that declare in global.php write here for license
global $dbF;
global $db;
global $_e;
global $functions;
global $menuClassGlobal;
global $adminPermissions;
global $defaultAdminLanguage;
global $adminPanelLanguage;
global $ActivePagePerm;
?>
<?php
$the_side_menu = false;
/**
 * MultiLanguage keys Use where echo;
 * define this class words and where this class will call
 * and define words of file where this class will called
 **/
global $_e;
$_w['An email is sent. Please check your emails.'] = '' ;
$_w['Email Sent Fail.Please Try Again.'] = '' ;
$_w['No user found! Please Check Your Email'] = '' ;
$_w['Captcha Code Incorrect. Please try again.'] = '' ;
$_w['Password Trouble Shooting'] = '' ;
$_w['Security Captcha'] = '' ;
$_w['Email'] = '' ;
$_w['Send Email'] = '' ;
$_w['Incorrect Email.'] = '' ;
$_w['Please Type Captcha Code'] = '' ;
$_w['Please type your email address in the given field.'] = '' ;
$_w['Signin'] = '' ;
$_w['Login'] = '' ;
$_w['Go To Home'] = '' ;
$_w['SignIn'] = '' ;
$_w['LOGIN'] = '' ;
$lang = $functions->ibms_setting('Default Language');
$_e    =   $dbF->hardWordsMulti($_w,$lang,'Admin Trouble');

?>
    <div class="container-fluid">
        <?php
        //Make it false for Header and IBMS logo to hide
        if (true) { ?>
            <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="mainTopMenu">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand visible-xs" href="<?php echo WEB_URL; ?>"><i class="fa fa-home"></i></a>
                    </div>

                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="<?php echo WEB_URL; ?>"> <i class="fa fa-home" style="font-size: 18px"></i> <?php echo $_e['Go To Home']; ?></a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <?php
                                echo '<a href="'.WEB_URL.'/do-login.secure"><i class="glyphicon glyphicon-log-in"></i> '.$_e['SignIn'].'</a>';
                                ?>
                            </li>
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
            <div class="IBMS_LOGO col-sm-12 text-center">
                <div style="margin-top: 70px;display: inline-block;">
                    <div style="display: inline-block; vertical-align: middle;float: left;margin-right: 10px;">
                        <img src="<?php echo WEB_ADMIN_URL; ?>/images/logo_ibms.png" width="120"/>
                    </div>

                    <div style="font-size: 30px;float: left;display: inline-block;">
                        IBMS
                        <div
                            style="display: inline-block; position: relative; vertical-align: middle;
                            font-size: 12px; text-align: left; border-left: solid #5f5f5f 1px;
                            padding-left: 5px;  margin-left: -5px; ">
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
            </div><!--IBMS logo END-->
        <?php } ?>

        <div class="col-sm-12 " >
            <div class="" style="max-width: 580px;margin: 10px auto">
                <div class=" btn-success" style="padding: 8px;">
                    <div class=""><?php echo _uc($_e['Password Trouble Shooting']); ?></div>
                </div>

                <div class=" panel-default">
                    <div class=" panel-footer">

                        <?php
                        if(isset($_POST["code"]) && $_POST["code"]==$_SESSION["rand_code"]){
                            if(isset($_POST['email']) && !empty($_POST['email'])){
                                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                                    $email=($_POST['email']);
                                    $sql    = "SELECT * FROM `accounts` WHERE `acc_email`='$email' ";
                                    $data   =   $dbF->getRow($sql);
                                    //var_dump($data);
                                    /*Testing data decode */ $functions->trouble();

                                    if($dbF->rowCount>0){
                                        $to =   $email;
                                        $user   = $data['acc_name'];
                                        $passwordDecode = $functions->decode($data['acc_pass']);

                                        $aLink  =   WEB_URL.'/'.ADMIN_FOLDER;
                                        $mailArray['link']        =   $aLink;
                                        $mailArray['password']    =   $passwordDecode;
                                        if($functions->send_mail($to,'','','accountTrouble',$user,$mailArray)) {
                                            $msg = _n($_e["An email is sent. Please check your emails."]);
                                            echo "<div class='alert alert-success'>$msg</div>";
                                        }else{
                                            $msg = _n($_e["Email Sent Fail.Please Try Again."]);
                                            echo "<div class='alert alert-success'>$msg</div>";
                                        }
                                    }
                                    else{
                                        $msg    =   _n($_e["No user found! Please Check Your Email"]);
                                        echo "<div class='alert alert-danger'>$msg</div>";
                                    }
                                }
                                else{
                                    $msg    =   _uc($_e["Incorrect Email."]);
                                    echo "<div class='alert alert-danger'>$msg</div>";
                                }
                            }
                        }
                        elseif(isset($_POST["code"]) && $_POST["code"]!=$_SESSION["rand_code"]){
                            $msg="Captcha Code Incorrect. Please try again.";
                            echo "<div class='alert alert-danger'>$msg</div>";
                        }
                        $email = empty($_POST['email']) ? "" : $_POST['email'];
                        ?>


                        <div class="text_inner text-center">
                            <p>
                                <?php echo _n($_e['Please type your email address in the given field.']); ?>
                            </p>
                            <br>
                            <div class=" ">
                                <form method="post" action="?do=resend&r=email" class="again form-horizontal">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2  control-label"><?php echo _uc($_e['Email']); ?></label>
                                        <div class="col-sm-10">
                                            <input type="email" required="" value="<?php echo $email; ?>" class="form-control" name="email"  id="inputEmail3" placeholder="<?php echo _uc($_e['Email']); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo _uc($_e['Security Captcha']); ?></label>

                                        <div class="col-sm-10">
                                            <div class="col-sm-5"><img src="<?php echo WEB_URL; ?>/captcha.php" alt="Please Type The Code."/></div>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="code"
                                                       placeholder="<?php echo _uc($_e['Please Type Captcha Code']); ?>" required="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <a href="<?php echo WEB_ADMIN_URL ?>" class="btn btn-success"> <?php echo _u($_e['LOGIN']); ?> </a>
                                            <button type="submit" class="btn btn-primary defaultSpecialButton"><?php echo _uc($_e['Send Email']); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div><!--Panel-body-->
                </div><!--panel-->
            </div>
        </div><!--col-sm-12-->
    </div><!--container fluid-->

    <!--    <script type="text/javascript" src="<?php /*echo WEB_ADMIN_URL; */?>/js/jquery.1.11.1.js"></script>-->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css"/>

<?php //include("footer.php");
$functions->adminFooter();
?>