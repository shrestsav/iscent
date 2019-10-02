#!/usr/bin/php
<?php

include_once("cronConfig.php");
// read from stdin
$fd = fopen("php://stdin", "r");
$email = "";

while (!feof($fd)) {
    $email .= fread($fd, 1024);
}

fclose($fd);

// handle email
$lines = explode("\n", $email);

// empty vars
$from   = "";
$subject = "";
$headers = "";
$message = "";
$splittingheaders = true;
for ($i=0; $i < count($lines); $i++) {
    if ($splittingheaders) {
        // this is a header
        $headers .= $lines[$i]."\n";

        // look out for special headers
        if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
            $subject = $matches[1];
        }
        if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
            $from = $matches[1];
        }
        if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
            $to = $matches[1];
        }
    } else {
        // not a header, but message
        $message .= $lines[$i]."\n";
    }

    if (trim($lines[$i])=="") {
        // empty line, header section has ended
        $splittingheaders = false;
    }
}

function extract_emails_from($string){
    preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
    return $matches[0];
}



  $email   = ($email);
  $emails  = extract_emails_from($message);

if(isset($emails[0])) {
    if (substr($emails[0], 0, 2) == 'A0')
        $email = substr($emails[0], 2);
    $email = $emails[0];
}
$sql    =   "INSERT INTO email_bounce (`email`) VALUES('$email')";
$db->query($sql);

?>