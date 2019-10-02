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
if($functions->getFormToken('contactFormSubmit')){
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
$functions->send_mail($to,'Contact Form',$msg);

$nameUser =   $_POST['form']['name'];
$to =   $_POST['form']['email'];

$thankT = $dbF->hardWords('Thanks for your interest. Our representative will get in touch with you.',false);
$message2="Hello ".ucwords($nameUser).",<br><br>
$thankT.<br><br>";

if($functions->send_mail($to,'','','contactFormSubmit',$nameUser)){
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

<!-- <div class="cleft">
<form method="post">

<input class="form-control" type="text" name="form[name]" placeholder="Your Name *" required>
<input class="form-control" type="email" name="form[email]" placeholder="Your Mail *" required>
<input class="form-control" type="phone" name="form[phone]" placeholder="Your Phone">
<input class="form-control" type="text" name="form[subject]" placeholder="Subject">
<textarea name="form[message]" placeholder="Your Message...."></textarea>
<input type="submit" name="submit" value="send message">
</form>
</div> -->



<div class="row">
	<div class="col-md-12">
		<form action="#" class="contact-form" method="post">
			<?php $functions->setFormToken('contactFormSubmit'); ?>
			<h3 class="promo-text">Have any question? Just let us know.</h3> <br>
			<input class="form-control" type="text" name="form[name]" placeholder="Your Name *" required>
			<input class="form-control" type="email" name="form[email]" placeholder="Your E-mail *" required>
			<input class="form-control" type="phone" name="form[phone]" placeholder="Your Phone">
			<input class="form-control" type="text" name="form[subject]" placeholder="Subject">
			<select name="" id="" class="form-control" name="form[country]">
				<option value="" disabled selected>Country</option>
				<option>Dubai</option>
				<option>USA</option>
				<option>Australia</option>
			</select>
			<input type="text" placeholder="Subject" class="form-control">
			<textarea name="" class="form-control" id="" cols="30" rows="10"></textarea>
			<button class="btn btn-secondary" type="submit">Submit</button>
		</form>
	</div>
</div>


<?php
}
return ob_get_clean(); ?>