<?php include("global.php");

$the_title = "Verify";
global $dbF;
 include("header.php");

//functions
function resendEmail(){?>
    <form method="post" action="?do=resend&r=email" class="again form-horizontal">

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">E-post</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="r_e"  id="inputEmail3" placeholder="E-post">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <!--Resend Verification Email-->
                <button type="submit" class="btn btn-default btn-lg defaultSpecialButton">Skicka om verifieringsmeddelande</button>
            </div>
        </div>
    </form>
<?php
}

function verifyEmailEnter(){
    global $functions;
    ?><form method="get" action="?a" class=" form-horizontal">
          <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">E-post</label>
              <div class="col-sm-10">
                  <input type="email" class="form-control" name="a"  id="inputEmail3" placeholder="E-post">
              </div>
          </div>
          <div class="form-group">
              <div class="col-sm-12">
                  <button type="submit" class="btn btn-default btn-lg defaultSpecialButton">Verifiera</button>
              </div>
          </div>
      </form>
<?php }


function verifyCodeEmail(){
    global $functions;
    global $dbF;
    global $webClass;

    if(!$functions->getFormToken('VerifyForm1')){return false;}

    $user   =   $_POST['user'];
    $email  =   $_POST['email'];
    $pass   =   $_POST['pass'];

    $code   =   (int)trim($_POST['code']);

    if($webClass->vcode($user,$email)==$code){
        $user   = hash("md5", $email);
        $pass   = hash("md5", $functions->encode($pass));

        $sql = "SELECT * FROM `accounts_user` WHERE MD5(acc_email)=? && MD5(acc_pass)=? AND acc_type = '0'";
        $data=$dbF->getRow($sql,array($user,$pass));
    if($dbF->rowCount>0){
        $id=$data['acc_id'];
        $qry_up= "UPDATE `accounts_user` SET `acc_type`='1' WHERE `acc_id`='$id' ";
        $sup = $dbF->setRow($qry_up);

        return 'Your Account is activated!';
    }
    else{
        // Invalid Account
        $temp = "Invalid Account. ";
        $temp .= '<a href="'.WEB_URL.'/verify?a='.urlencode($_POST['email']).'" class="btn-danger btn">Go Back</a> and try again.';
        return $temp;
    }
    }
    else{
        //Invalid Code
        return "Invalid Code";
    }
}
?>
   <div class="container-fluid">
      <div class="col-sm-12" >
        <div class="" style="max-width: 980px;margin: 10px auto">
            <div class="navbar-inverse bg-black">
                      <div class="">Verifiera konto</div>
            </div>
            <div class="text_inner">
                

      <?php

if( (isset($_GET['a']) && !empty($_GET['a']) )){

	if(isset($_GET['a'])){
		$email=urldecode(trim($_GET['a']));
	}
	else{
		$email=trim($_POST['email']);
	}

        $sql    = "SELECT * FROM `accounts_user` WHERE `acc_email`='$email' ";
		$data   =   $dbF->getRow($sql);
		if($dbF->rowCount>0){
			
			if($data['acc_type']=='0'){
                  $token = $functions->setFormToken('VerifyForm1',false);
                  $verifyT= $dbF->hardWords('Verify',false);
                $passwordT = $dbF->hardWords('password',false);
				echo '
				<div class="col-sm-12">
					<form method="post" action="verify.php?action=v" class="form-horizontal">
					    '.$token.'
					<input type="hidden" name="user" value="'.$data['acc_name'].'" />
					<input type="hidden" name="email" value="'.$data['acc_email'].'" />

					<div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Namn</label>
                        <div class="col-sm-10">
                          <input type="text" readonly value="'.$data['acc_name'].'" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">E-post</label>
                        <div class="col-sm-10">
                          <input type="text" readonly value="'.$data['acc_email'].'" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">'.$passwordT.'</label>
                        <div class="col-sm-10">
                          <input type="password"  name="pass" class="form-control" placeholder="'.$passwordT.'"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">verifieringskod </label>
                        <div class="col-sm-10">
                          <input type="text" name="code" class="form-control"  placeholder="verifieringskod "/>
                        </div>
                    </div>

                     <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-default defaultSpecialButton">'.$verifyT.'</button>
                        </div>
                      </div>
					</form>
	    		</div>
				';
				?>
				<br />
      			<a href="?do=resend" class="btn btn-danger">
                    <?php
                    $dbF->hardWords('No verification email received? Click here!');
                    ?>
                </a>
				<?php
		}
		else{
			//Account already verified.
			?>
                <div class="text-center">
                    <div class="lead btn-success">Your account is already verified!</div> <br />
                    <a href="trouble.php" class="btn btn-danger">Having trouble in logging in? Click Here!</a>
                </div>
      <?php
		}
	}
	else{
		//Eamil not found

		echo '<div class="text-center">
               <!-- <b>Invalid Email. Please check &amp; type again! </b>-->
                <b>Ogiltig e-post. Kontrollera &amp; skriv igen!</b>
                <br /><br />';
                verifyEmailEnter();
        echo "</div> ";
	}
}

elseif( isset($_GET['action']) && $_GET['action'] == 'v'
		&& isset($_POST['email'])	&& !empty($_POST['email'])
		&& isset($_POST['user'])	 && !empty($_POST['user']) 
		&& isset($_POST['pass']) 	 && !empty($_POST['pass']) 
		&& isset($_POST['code'])	 && !empty ($_POST['code'])
    ){

    $msg = verifyCodeEmail();
    if($msg!='')
    echo "<div class='alert alert-success'>$msg</div> ";
    else
    echo "<div class='alert alert-danger'>Some Thing Is Wrong Please Try Again, Error Code: 15532</div> ";
}


elseif(isset($_GET['do']) && $_GET['do']=='resend'){ ?>
      <br />

    <?php
if(isset($_GET['r']) && $_GET['r']=='email' && isset($_POST['r_e']) && !empty($_POST['r_e']) ){
	$email=strip_tags(strtolower(trim($_POST['r_e'])));
    $sql    = "SELECT * FROM `accounts_user` WHERE `acc_email`='$email' ";
    $data   =   $dbF->getRow($sql);

    if($dbF->rowCount>0){
		if($data['acc_type']=='0'){
			$user=$data['acc_name'];
			$email=$data['acc_email'];
			
			$code   =   $webClass->vcode($user,$email);
			$aLink  =   WEB_URL."/verify?a=".urlencode($email);

            $mailArray['link']        =   $aLink;
            $mailArray['code']        =   $code;
			$functions->send_mail($email,"",'','verifyEmail',$user,$mailArray);

			$msg    =   "Thank you! We have resend the verification email. Please check your emails.";

			echo "<div class='alert alert-success'>$msg</div>";
            echo '<a href="?#" class="btn btn-primary">Go To Verify Page? Click Here!</a>';

		}
		else{
            $msg = "Your account is already verified!";
            echo "<div class='alert alert-success'>$msg</div>";

            ?>
            <a href="trouble.php" class="btn btn-danger">Having trouble in logging in? Click Here!</a>
    <?php
		}
	}
	else{

        $msg = "Sorry! We cant find your email! Please check and try again.";
        echo "<div class='alert alert-danger'>$msg</div>";
        echo '<div class="text-center">';
        resendEmail();
        echo '</div>';
        ?>


    <?php	
	}
}
else{
?>
    <div class="text-center">
   <!-- <b>Enter your email address to verify account</b><br />-->
        <b>Fyll i din e-postadress för att verifiera kontot</b><br />
        <?php resendEmail(); ?>

    <br />
    <a href="?#" class="btn btn-primary"><?php $dbF->hardWords('Already received email? Click Here'); ?>!</a>
    </div>
    <?php }
echo '</center>';
}


    //Verify Subscribe Email
    elseif(isset($_GET['sEmail']) && $_GET['sEmail']!=''){
        //if user not come from  referer url
        //Subcribe only verify when user click on verify link
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=''){
            $email  =   $_GET['sEmail'];
            $sql2="UPDATE email_subscribe SET verify = '1' WHERE email='$email'";
            $dbF->setRow($sql2);
            $msg = "Thank You to subscribe Email";
            echo "<div class='alert alert-success'>$msg</div>";
        }else{
            $msg = "Sorry! Subscribe Email Fail. Please Try Again";
            echo "<div class='alert alert-danger'>$msg</div>";
        }
    }

//UnSubscribe Email
      elseif(isset($_GET['unSubscribe']) && $_GET['unSubscribe']!=''){
          //if user not come from  referer url
          //unSubcribe only verify when user click on link
          if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=''){
              $email  =   $_GET['unSubscribe'];
              $sql2="UPDATE email_subscribe SET verify = '0' WHERE email='$email'";
              $dbF->setRow($sql2);
              $msg = "Successfully UnSubscribe Email";
              echo "<div class='alert alert-success'>$msg</div>";
          }else{
              $msg = "Sorry! UnSubscribe Email Fail. Please Try Again";
              echo "<div class='alert alert-danger'>$msg</div>";
          }
      }

else{
?>
    <div class="text-center">
      <b>Enter your email address to verify account</b><br />
      <br />
      <?php verifyEmailEnter(); ?>
      <br />
      <br />
      <a href="?do=resend"  class="btn btn-danger">No varification email recived? Click Here!</a>
      <br />
    </div>
    <?php
}
?>
            
            
            
       </div>
              <!--- inner text close--->
        </div>
              <!--- inner page close--->
      </div>
            <!--- container close--->
	</div>
    <!--- main container close--->

<?php include("footer.php"); ?>