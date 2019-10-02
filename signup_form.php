<link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css"/>
<?php

global $dbF;

global $db,$functions;

$dbp = $db;





function signUpSubmit()

{

    global $dbF;

    global $db;

    global $functions;

    global $webClass;



    if (isset($_POST['name']) && !empty($_POST['name'])

        && isset($_POST['email']) && !empty($_POST['email'])

        && isset($_POST['pass']) && !empty($_POST['pass'])

        && isset($_POST['rpass']) && !empty($_POST['rpass'])

    ){



     if(!$functions->getFormToken('signUpUser')){return false;}





 $useralreadyT = $dbF->hardWords('User Name/Email name already exist',false);

 $TryagainT = $dbF->hardWords('Try again. Or contact administrator.',false);

 $ThankWeSend = $dbF->hardWords('Thank you! We have sent verification email. Please check your email.',false);





 $sql = "SELECT * FROM accounts_user WHERE acc_email = '$_POST[email]'";

 $test   =   $dbF->getRow($sql);

 if($dbF->rowCount>0){

    $msg = "$useralreadyT <br /><br>";

    return $msg;

}





$DearT = $dbF->hardWords('Dear',false);

 /*       $ThankForRegT = $dbF->hardWords('Thank you for your registration to our website.',false);

        $verifyT = $dbF->hardWords('Please verify your account from the link below:',false);

        $YourVerifyT = $dbF->hardWords('Your verification code is',false);

        $AccVerifyT = $dbF->hardWords('Account Verification',false);

*/





        $thankYoumsg    =   $dbF->hardWords('Thank you for registering.',false);



        if (isset($_POST["code"]) && $_POST["code"] != $_SESSION["rand_code"]) {

            $msg = $dbF->hardWords('Captcha Code Not Match Please Try Again',false);

            return $msg;

        } else {

            try {

                $email = strip_tags(strtolower(trim($_POST['email'])));

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {



                    $status = "1"; //pending 0 .. 1 active

                    $name   = empty($_POST['name'])  ? "" : $_POST['name'];

                    $pass   = empty($_POST['pass'])  ? "" : $_POST['pass'];

                    $rpass  = empty($_POST['rpass']) ? "" : $_POST['rpass'];

                    $today  = date("Y-m-d H:i:s");



                    if($pass != $rpass){

                        $msg = $dbF->hardWords('Password Not Matched!',false);

                        return $msg;

                    }

                    $password  =  $functions->encode($pass);



                    $db->beginTransaction();

                    $sql = "INSERT INTO accounts_user SET

                    acc_name = ?,

                    acc_email = ?,

                    acc_pass = ?,

                    acc_type = '$status',

                    acc_created = '$today'";

                    $array = array($name,$email,$password);



                    $dbF->setRow($sql,$array,false);

                    $lastId = $dbF->rowLastId;



                    $setting    = empty($_POST['signUp']) ? array() : $_POST['signUp'];



                    $sql        =   "INSERT INTO `accounts_user_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";

                    $arry       =   array();

                    foreach($setting as $key=>$val){

                        $sql .= "('$lastId',?,?) ,";

                        $arry[]= $key ;

                        $arry[]= $val ;

                    }

                    $sql = trim($sql,",");

                    $dbF->setRow($sql,$arry,false);



                    $code   = $webClass->vcode($name, $email);

                    $aLink  = WEB_URL . "/verify.php?a=" . urlencode($email);



                    $SincerelyT = $dbF->hardWords('Sincerely',false);

                    $msg  = "$DearT " . ucwords($name) . ".! <br> $thankYoumsg<br /><br>" . "\n";

                    $mailArray['link']        =   $aLink;

                    $mailArray['code']        =   $code;

                    $functions->send_mail($email,'','','signUp',$name,$mailArray);

                    //$msg = $msg;

                } else {

                    $AccLoginInfoT = $dbF->hardWords('Invalid Email Address!',false);

                    $msg = $AccLoginInfoT;

                    return $msg;

                }





                $db->commit();

                $loginReturn = $webClass->userLogin(false);

                if($loginReturn===true){

                    if(isset($_GET['ref']) && ($_GET['ref']=='cart' || $_GET['ref']=='cart.php')){

                        $loc = 'cart.php';

                    }else{

                        $loc = 'viewOrder.php';

                    }

                    echo '<script>

                    //location.replace("'.$loc.'");

                    </script>';

                }

                return $msg;

            } catch (PDOException $e) {

                $msg = "$useralreadyT <br /><br>

                $TryagainT<br><br>";

                $db->rollBack();

                return $msg;

            }

        }

    }

}



$msg = signUpSubmit();



?>





<script type="text/javascript">
    function passM() {
        var pass = document.getElementById("pass").value;
        var rpass = document.getElementById("rpass").value;
        if (pass.length >= 4) {
            if (pass == rpass) {
                document.getElementById("pm").style.color = "green";
                document.getElementById("pm").innerHTML = "<?php $dbF->hardWords('Password Matched!');?>";
                document.getElementById("signup_btn").disabled = false;
            }
            else {
                document.getElementById("pm").style.color = "red";
                document.getElementById("pm").innerHTML = "<?php $dbF->hardWords('Password Not Matched!');?>";
                document.getElementById("signup_btn").disabled = true;
            }
        }
        else {
            document.getElementById("pm").style.color = "orange";
            document.getElementById("pm").innerHTML = "<?php $dbF->hardWords('Atleat 4 characters!');?>";
            document.getElementById("signup_btn").disabled = true;
        }
    }

    function vali() {
        var u_l = document.getElementById("user").value.length;
        if (u_l <= 3) {
            document.getElementById("um").style.color = "red";
            document.getElementById("signup_btn").disabled = true;
        }
        else {
            document.getElementById("um").style.color = "black";
            document.getElementById("signup_btn").disabled = false;
        }
    }

    function subf() {
        var terms = document.getElementById("ch").checked;
        if (terms == true) {
            document.getElementById("sf").submit();
        }
    }
</script>



<?php if($msg!=''){ ?>

    <div class="col-sm-12 alert alert-danger">

        <?php echo $msg; ?>

    </div>

<?php }
$country_list = $functions->countrySelectOption();
?>

<div class="testimonial-slider clearfix">
    <div class="col-md-12">
        <div class="client-words text-center">
            <form class="contact-form" action="" role="form" method="post">
                <?php $functions->setFormToken('signInUser'); ?>
                <h3 class="promo-text"><?php $dbF->hardWords('LOGIN');?></h3><br>
                <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="<?php $dbF->hardWords('Email');?>" required>
                <input type="password" class="form-control" name="pass" id="inputPassword3" placeholder="<?php $dbF->hardWords('Password');?>" required="">
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" value="1" name="remember"> <?php $dbF->hardWords('Remember me');?>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn_img btn themeButton defaultSpecialButton" style="width: auto"><?php $dbF->hardWords('Sign in');?></button>
            </form>
            <br>
            <a href="trouble.php" class="btn"><?php $dbF->hardWords('Having trouble in logging in?');?></a>
            <br><br>
        </div>
    </div>

    <!--Register-->
    <div class="col-md-12">
        <div class="client-words text-center">
            <form class="contact-form" role="form" method="post">
                <h3 class="promo-text"><?php $dbF->hardWords('REGISTER');?></h3>
                <br>
                <?php $functions->setFormToken('signUpUser'); ?>

                <div class="col-sm-12">
                    <input type="text" pattern="[a-zA-z ]{3,50}" class="form-control" name="name" id="user" placeholder="<?php $dbF->hardWords('Name');?>" required onChange="filter(this); vali()">
                    <div id="um"></div>
                </div>
                <div class="col-sm-12">
                    <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="<?php $dbF->hardWords('Email');?>" required>
                </div>
                <div class="col-sm-12">
                    <input type="password" onChange="passM();" class="form-control" name="pass" id="pass" placeholder="<?php $dbF->hardWords('Password');?>" required="">
                </div>
                <div class="col-sm-12">
                    <input type="password" onChange="passM();" onkeyup="passM();" class="form-control" name="rpass" id="rpass" placeholder="<?php $dbF->hardWords('Retype Password');?>" required="">
                    <div id="pm"></div>
                </div>

                <div class="col-sm-12">
                    <label class="radio-inline">
                        <input type="radio" class="" name="signUp[gender]" value="Female"> <?php $dbF->hardWords('Female');?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" class="" name="signUp[gender]" value="Male"> <?php $dbF->hardWords('Male');?>
                    </label>
                </div>

                <!--<div class="form-group">
                    <label class="col-sm-2 control-label"><?php /*$dbF->hardWords('Security Captcha');*/?></label>
                    <div class="col-sm-10">
                        <div class="col-sm-6"><img src="captcha.php" alt="<?php /*$dbF->hardWords('Please Type The Code.');*/?>"/></div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="code" placeholder="<?php /*$dbF->hardWords('Please Type The Code.');*/?>" required="">
                        </div>
                    </div>
                </div>-->

                <!--New Fields -->

                <div class="col-sm-12">
                    <input type="text" class="form-control datepicker"  required="" value="" name="signUp[date_of_birth]" placeholder="<?php $dbF->hardWords('Date Of Birth');?> mm/dd/yyyy e.g: 12/31/2014 " >
                </div>

                <div class="col-sm-12">
                    <input type="text" class="form-control"  value="" name="signUp[phone]" placeholder="<?php $dbF->hardWords('Phone'); ?>" >
                </div>
                <div class="col-sm-12">
                    <textarea class="form-control" name="signUp[address]" required placeholder="<?php $dbF->hardWords('Address'); ?>"></textarea>
                </div>
                <div class="col-sm-12">
                    <input type="text" class="form-control" required  value="" name="signUp[post_code]" placeholder="<?php $dbF->hardWords('Post Code');?>">
                </div>
                <div class="col-sm-12">
                    <select required  id="sender_country" name="signUp[country]" class="form-control">
                        <option value="" disabled selected><?php $dbF->hardWords('Country');?></option>
                        <?php echo $country_list; ?>
                    </select>
                    <!-- <input type="text" class="form-control" required  value="" name="signUp[country]" placeholder="" > -->
                </div>
                <div class="col-sm-12">
                    <input type="text" class="form-control" required  value="" name="signUp[city]" placeholder="<?php $dbF->hardWords('City');?>">
                </div>

                <!--New field End-->

                <div class="col-sm-12">
                    <button type="submit" name="submit" id="signup_btn" class="btn_img btn themeButton defaultSpecialButton" onClick="subf()" style="width: auto"><?php $dbF->hardWords('REGISTER');?></button>
                </div>

            </form>
        </div>
    </div>
</div>



<script>
    $(document).ready(function(){
        $(".datepicker").datepicker();
    });
</script>
<style>
/*.btn-primary:hover, .btn-primary:focus {
    background-position: 0 0px !important;*/
    .navbar-inverse {
        color: white;
    }
</style>







