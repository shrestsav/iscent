<?php
ob_start();
include_once("global.php");
global $webClass;
$pMmsg = '';
$contactAllow = true;
if(isset($_POST) && !empty($_POST) ){ ?>
<?php
// if(isset($_POST["code"]) && $_POST["code"]!=$_SESSION["rand_code"]){
// $pMmsg = $dbF->hardWords('Captcha Code Not Correct',false);
// $contactAllow = true;
// }
// else{
if($functions->getFormToken('careerFormSubmit')){
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
// $subject = $_POST['form']['subject'];
$subject = 'Career Form';
if(isset($_FILES['file']) && ($_FILES["file"]["size"])>0 && (
($_FILES["file"]["type"] == "application/msword") ||
($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")  ||
($_FILES["file"]["type"] == "application/pdf"))) 
{
$replaced = str_replace(' ', '_', $_FILES["file"]["name"]);
$num=time().rand(10,9999);
$filename=$num.$replaced;
if ($_FILES["file"]["error"] > 0){
// echo "Return Code: ".$_FILES["file"]["error"]."<br />";
}
else{
if (file_exists("uploads/".$_FILES["file"]["name"])){}
else{
move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/files/cv/".$num.$replaced);
}
}
}
else{
// if(empty($_FILES["file"]["name"])){
// $entery=false;
// echo '<strong>Can not upload your resume! Either file size exceeded or format not allowed!<br />
// <button onclick="window.history.back()" style="border:solid #000 1px;padding:2px;background:#FFF; ">Go Back</button>
// And Try Again!</strong>
// ';
// }
// //else{/*File not Selected*/}
// exit();
// else{/*File not Selected*/}
}
$file = empty($filename) ? '' : 'http://'.$_SERVER['HTTP_HOST'].'/php/build_durable/uploads/files/cv/'.$filename.'';
$filelink  = "<a href={$file}>Download file</a>";
$msg.='<tr> <td>File</td>   <td>'.$filelink.'</td> </tr>';
$msg.='<tr> <td>Date Time</td>  <td>'.date("D j M Y g:i a").'</td> </tr>';
$msg.='</table>';
// sending admin email
$to = $functions->ibms_setting('Email');
$functions->send_mail($to,$subject,$msg);
// sending user email
$nameUser =   $_POST['form']['name'];
$to =   $_POST['form']['email'];
$thankT = $dbF->hardWords('Thanks for your interest. Our representative will get in touch with you.',false);
$message2="Hello ".ucwords($nameUser).",<br><br>
$thankT.<br><br>";
if($functions->send_mail($to,'','','careerFormSubmit',$nameUser)){
$pMmsg = "$thankT";
} else {
$errorT = $dbF->hardWords('An Error occured while sending your mail. Please Try Later',false);
$pMmsg = "$errorT";
}
$contactAllow = true;
}else{
$contactAllow = true;
}
// }
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
<form method="post"  enctype="multipart/form-data" class=''>
<?php $functions->setFormToken('careerFormSubmit'); ?>
<input type="text" name="form[name]" placeholder="Name *" required>

<select name="form[applied_for]" placeholder="Applied For" required="required" class="">
<option disabled="disabled" selected="selected">
Select Applied For
</option>
<?php $applied_for_fields_array = explode(',', $functions->ibms_setting('available_positions'));
foreach ($applied_for_fields_array as $field): ?>
<option value="<?php echo $field; ?>"><?php echo ($field); ?>
</option>
<?php endforeach;  ?>
</select>



<input type="text" name="form[employment_status]" placeholder="Employment Status *" required="required">

<input type="text" name="form[education_level]" placeholder="Education Level *" required=""> 



<select name="form[experience_level]" placeholder="Experience Level" required="required" class="">
<option disabled="disabled" selected="selected">
Select Experience Level
</option>
<option value="1 Year">1 Year</option>
<option value="2 Years">2 Years</option>
<option value="3 Years">3 Years</option>
<option value="4 Years">4 Years</option>
<option value="5 Years">5 Years</option>
<option value="5+ Years">5+ Years</option>
</select>



<input type="email" name="form[email]" placeholder="Email *" required>

<input type="tel" name="form[Phone]" placeholder="Phone *" required>
<input type="text" name="form[city]" placeholder="City *" required>



<textarea name="form[Comments]" placeholder="Comments *" required></textarea>

<label for="fileupload"> Select a file to upload</label>
<input type="file" required="" accept=".pdf,.doc,.docx" name="file">



<input type="submit" name="submit" value="Submit">
</form>
</div>

</div>
</div>
<!-- main_box close -->





<?php
}
return ob_get_clean(); 
?>