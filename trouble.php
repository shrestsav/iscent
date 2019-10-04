<?php include("global.php");
$the_side_menu = false;

include("header.php");
?>

<style type="text/css">
.login-bg {
    background: url('<?= WEB_URL.'/images/default_banner.jpg' ?>') no-repeat;
    background-size: cover;
    background-position: center center;
}
</style>
<section class="login-bg page-banner">
    <div class="page-heading">
        <h2><?php $msg= $dbF->hardWords('Password Trouble Shooting'); ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-center">
                <li class="breadcrumb-item"><?php $msg= $dbF->hardWords('Please type you email address in the given field.Your account details will be sent on the given email address!'); ?></li>
            </ol>
        </nav>
    </div>
</section>

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
        <div class="text_inner text-center">
            <div class="col-sm-12">
                <form method="post" action="?do=resend&r=email" class="again contact-form">
                    <div class="form-group">
                        <input type="email" required="" value="<?php echo $email; ?>" class="form-control" name="email"  id="inputEmail3" placeholder="<?php $msg= $dbF->hardWords('Email'); ?>">
                    </div>
                    <div class="form-group">
                        <img src="captcha.php" alt="<?php $msg= $dbF->hardWords('Please Type The Code.'); ?>"/>
                        <input type="text" class="form-control" name="code" placeholder="<?php $msg= $dbF->hardWords('Please Type Captcha Code'); ?>" required="">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn_img btn themeButton defaultSpecialButton"><?php $msg= $dbF->hardWords('Send Email'); ?></button>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo WEB_URL; ?>/login.php" class="btn_img btn themeButton defaultSpecialButton"><?php $msg= $dbF->hardWords('Go Back'); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<?php include("footer.php"); ?>