<?php
ob_start();
?>
<?php
function loginScript(){ ?>
    <!-- <script type="text/javascript" src="<?php /*echo WEB_ADMIN_URL; */?>/js/jquery.1.11.1.js"></script>-->
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css"/>
<?php }
/**
 * MultiLanguage keys Use where echo;
 * define this class words and where this class will call
 * and define words of file where this class will called
 **/
global $_e;
$_w['You are already logged in!'] = '' ;
$_w['Too many login attempts. Please try after some time later!'] = '' ;
$_w['Email'] = '' ;
$_w['Password'] = '' ;
$_w["Forgotten your password? \n Click Here!"] = '' ;
$_w['Signin'] = '' ;
$_w['Login'] = '' ;
$_w['Go To Home'] = '' ;
$_w['SignIn'] = '' ;
$_w['Woops, Too Slow!'] = '' ;
$_w['Session expired! Please try again. This is for your own security.'] = '' ;
$_w['Your email or password is incorrect, please type again!'] = '' ;
$_w['Stop!'] = '' ;

$lang = $functions->ibms_setting('Default Language');
$_e    =   $dbF->hardWordsMulti($_w,$lang,'Admin Login');

if ($functions->log_check()["status"] == "ok") {
    echo "<div align='center'>". _uc($_e['You are already logged in!']) ."</div>";
    @header('Location:'.WEB_ADMIN_URL);
    return ob_get_clean();
}
@$try = (intval($_GET['try']) < 1) ? 1 : intval($_GET['try']);

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
                    <li class="active">
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
<?php }


$fake_form = '
<div class="container-fluid">
    <div class="col-sm-12">
 <div style="width: 340px; position: relative; margin: 30px auto;">
<div class="alert alert-warning"><strong>DAMM!</strong>'. _uc($_e['Too many login attempts. Please try after some time later!']) .'</div>
        <div class="panel panel-default">
            <div class="panel-body btn-success">'._uc($_e['Login']).'</div>
            <div class="panel-footer">
                    <div class="input-group">
                        <span class="input-group-addon btn-default">'._uc($_e['Email']).'</span>
                        <input type="text" name="user" class="form-control" disabled="disabled" >
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon btn-default">'. _uc($_e['Password']).'</span>
                        <input type="password" name="pass" class="form-control" disabled="disabled">
                    </div>
                     <br/>

                    <div style=" display: inline-block;">
                            <a href="'.WEB_ADMIN_URL.'/trouble" style="font-size: 12px;">'. _uc($_e["Forgotten your password? \n Click Here!"]).'</a>
                    </div>
                        <button type="button" class="btn btn-primary pull-right">'. _uc($_e['Signin']).'</button>

            </div>
        </div>
    </div>
  </div>
</div>';

// When person try to login 4 times, nologin cookie set and show fake form ... ASAD

if (isset($_COOKIE['nologin'])) {
    setcookie('nologin', uniqid("suspended"), time() + 600); //600 = 10min
    echo $fake_form;
    loginScript();
    return ob_get_clean();
} elseif ($try >= 4) {
    setcookie('nologin', uniqid("suspended"), time() + 600);
    echo $fake_form;
    loginScript();
    return ob_get_clean();
}

// LOGIN FUNCTION CALL HERE

$alerts = "";
if (isset($_POST['_toss'])
    && !empty($_POST['_toss'])
    && isset($_POST['user'])
    && isset($_POST['pass'])
    && !empty($_POST['user'])
    && !empty($_POST['pass'])
) {
    $try++;
    @$cookie = $_COOKIE['request_number'];
    @$session_login_temp = $_SESSION['_login_temp'];

    // LOGIN FUNCTION CALL HERE
    // cookie was set in line 77, and session_login_temp was set in line 75
    if ($_POST['_toss'] == hash("adler32", $cookie . '_toss') && $session_login_temp == md5($cookie)) {
        $login_req = $functions->login($_POST['user'], $_POST['pass']);
        if ($login_req == false) {
            $alerts .= "<div class='alert alert-danger'><strong>". _uc($_e['Stop!']) ."</strong> ". _n($_e['Your email or password is incorrect, please type again!']) ."</div>";
        }
    } else {
        $alerts .= "<div class='alert alert-warning'><strong>". _uc($_e['Woops, Too Slow!']) ."</strong> ". _n($_e['Session expired! Please try again. This is for your own security.']) ."</div>";
    }
}
// with  help of secret key , MD5 random no generate and set in session, _login_temp
$_random_key = hash("md5", rand(99, 9999) . $functions->secret_key);
$_SESSION['_login_temp'] = md5($_random_key);
// Set Cookie request_number
setcookie('request_number', $_random_key, time() + 50);
$_toss = hash("adler32", $_random_key . '_toss'); // send value by form
$action_url = "do-login_try_$try.secure";

if (isset($_POST['_toss']) && isset($_POST['user']) && isset($_POST['pass'])&& !empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['sec'])){
    $functions->create_login($_POST);
}
?>
<div class="container-fluid">
    <div class="col-sm-12">
        <div style="width: 340px; position: relative; margin: 30px auto;">
            <?php echo $alerts; ?>
            <div class="panel panel-default">
                <div class="panel-body btn-success"><?php echo _uc($_e['Login']); ?></div>
                <div class="panel-footer">
                    <form method="post" action="<?php echo $action_url; ?>">
                        <input type="hidden" name="_toss" value="<?php echo $_toss; ?>">

                        <div class="input-group ">
                            <span class="input-group-addon btn-default"><?php echo _uc($_e['Email']); ?></span>
                            <input type="text" name="user" class="form-control" required="required">
                        </div>
                        <br/>

                        <div class="input-group">
                            <span class="input-group-addon btn-default"><?php echo _uc($_e['Password']); ?></span>
                            <input type="password" name="pass" class="form-control" required="required">
                        </div>
                        <br/>

                        <div style=" display: inline-block;">
                            <a href="<?php echo WEB_ADMIN_URL; ?>/trouble" style="font-size: 12px;"><?php echo _uc($_e["Forgotten your password? \n Click Here!"]); ?></a>
                        </div>
                        <button class="btn btn-primary pull-right"><?php echo _uc($_e['Signin']); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php loginScript(); ?>
<?php return ob_get_clean(); ?>