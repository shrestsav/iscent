<?php
ob_start();
include_once("global.php");
global $webClass;
$pMmsg = '';
$contactAllow = true;
if(isset($_POST) && !empty($_POST) ){ ?>
<?php

if(isset($_POST["code"]) && $_POST["code"]!=$_SESSION["rand_code"]){
$pMmsg = $dbF->hardWords('Captcha Code Not Correct',false);
$contactAllow = true;
}
else{
if($functions->getFormToken('feedbackFormSubmit')){
$img="";

$msg='<table border="1">';
foreach($_POST['form'] as $key=>$val){
$msg.= '
<tr>
<td>'.ucwords(str_replace("_"," ",$key)).'</td>
<td>'.$val.'</td>
</tr>
';
}

// $subject = $_POST['form']['Subject'];

$msg.='<tr>	<td>Date Time</td>	<td>'.date("D j M Y g:i a").'</td> </tr>';
$msg.='</table>';

$to = $functions->ibms_setting('Email');
$functions->send_mail($to,'Feedback Form',$msg);

$nameUser =   $_POST['form']['Name'];
$to =   $_POST['form']['email'];

$thankT = $dbF->hardWords('Thanks for your interest. Our representative will get in touch with you.',false);
$message2="Hello ".ucwords($nameUser).",<br><br>
$thankT.<br><br>";

if($functions->send_mail($to,'','','feedbackFormSubmit',$nameUser)){
$pMmsg = "$thankT";
} else {
$errorT = $dbF->hardWords('An Error occured while sending your mail. Please Try Later',false);
$pMmsg = "$errorT";
}
$contactAllow = false;
}else{
$contactAllow = true;
}
}
if($pMmsg!=''){
echo "<div class='alert alert-info'>$pMmsg</div>";
}

}
if($contactAllow){

$labelClass = "col-sm-3 padding-0";
$divClass = "col-sm-9";

?>


<div class="main_box">
<div class="standard">
<div class="sub_box1">
<!-- <h2>Feedback Form</h2> -->
<form method="post" >
<?php $functions->setFormToken('feedbackFormSubmit'); ?>
<input type="text" name="form[Name]" placeholder="Name *" required>
<input type="email" name="form[email]" placeholder="Email *" required>
<input type="email" name="form[email 2]" placeholder="Email 2 (optional)">
<input type="tel" name="form[Phone]" placeholder="Phone *" required>
<input type="tel" name="form[Phone 2]" placeholder="Phone 2 (optional)">
<input type="tel" name="form[Phone 3]" placeholder="Phone 3 (optional)">
<input type="text" name="form[Address]" placeholder="Address *" required>
<textarea name="form[Comments]" placeholder="Comments *" required></textarea>
<input type="submit" value="Submit">
</form>
</div>

</div>
</div>
<!-- main_box close -->

<?php
}
return ob_get_clean(); ?>