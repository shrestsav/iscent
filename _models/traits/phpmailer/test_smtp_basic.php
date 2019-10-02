<html>
<head>
<title>PHPMailer - SMTP basic test with authentication</title>
</head>
<body>

<?php

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Toronto');

require_once('class.phpmailer.php');
require_once('class.smtp.php');
$mail             = new PHPMailer();
$body             ='test';
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mehransugar.com"; // SMTP server
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "mehransugar.com"; // sets the SMTP server
$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
$mail->Username   = "noreply@mehransugar.com"; // SMTP account username
$mail->Password   = "123mehran";        // SMTP account password
$mail->SetFrom('noreply@mehransugar.com', 'First Last');
$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";
$mail->MsgHTML($body);
//$mail->AddAddress("noreply@mehransugar.com", "John Doe");
$mail->AddAddress("shebkhan@yahoo.ie", "John Doe");
if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
