<?php include("global.php");
$the_side_menu = false;

include("header.php");
?>
    <div class="container-fluid padding-0 inner_details_container">
        <div class="standard inner_details_content">
<?php
if(isset($_POST["code"]) && $_POST["code"]==$_SESSION["rand_code"]){

    if(isset($_POST['email']) && !empty($_POST['email'])){

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $email=($_POST['email']);
            $sql    = "SELECT * FROM `accounts_user` WHERE `acc_email`='$email' ";
            $data   =   $dbF->getRow($sql);
            if($dbF->rowCount>0){
                $to =   $email;
                $user   = $data['acc_name'];
                $passwordDecode = $functions->decode($data['acc_pass']);

                $aLink  =   WEB_URL.'/login';
                $mailArray['link']        =   $aLink;
                $mailArray['password']    =   $passwordDecode;
                $functions->send_mail($to,'','','accountTrouble',$user,$mailArray);

                $msg    =   "An email is sent. Please check your emails.";
                $msg= $dbF->hardWords($msg,false);
                echo        "<div class='alert alert-success'>$msg</div>";
            }
            else{
                $msg="No user found! Please Check Your Email";
                $msg= $dbF->hardWords($msg,false);
                echo "<div class='alert alert-danger'>$msg</div>";
            }
        }
        else{
            $msg="Incorrect Email.";
            $msg= $dbF->hardWords($msg,false);
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

            <div class="home_links_heading well well-sm h3"><?php $msg= $dbF->hardWords('Password Trouble Shooting'); ?>
            </div>
            <div class="text_inner text-center">
                <p><?php $msg= $dbF->hardWords('Please type you email address in the given field.Your account details will be sent on the given email address!'); ?>

                </p>
                <br>
                <div class="col-sm-6 col-sm-offset-2">
                    <form method="post" action="?do=resend&r=email" class="again form-horizontal">

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"><?php $msg= $dbF->hardWords('Email'); ?></label>
                            <div class="col-sm-10">
                                <input type="email" required="" value="<?php echo $email; ?>" class="form-control" name="email"  id="inputEmail3" placeholder="<?php $msg= $dbF->hardWords('Email'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php $msg= $dbF->hardWords('Security Captcha'); ?></label>

                            <div class="col-sm-10">
                                <div class="col-sm-6"><img src="captcha.php" alt="<?php $msg= $dbF->hardWords('Please Type The Code.'); ?>"/></div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="code"
                                           placeholder="<?php $msg= $dbF->hardWords('Please Type Captcha Code'); ?>" required="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary defaultSpecialButton"><?php $msg= $dbF->hardWords('Send Email'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="clearfix"></div>
            <a href="<?php echo WEB_URL; ?>/login.php" class="btn btn-primary clearfix"><?php $msg= $dbF->hardWords('Login Page.'); ?></a>
        </div>
</div>




<?php include("footer.php"); ?>