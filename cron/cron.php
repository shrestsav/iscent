<?php

/*cron add ways
1) /usr/bin/curl -A cron http://link
2) php -q /home/username/public_html/folderName/file
3) /usr/bin/php /home/username/public_html/folderName/file
*/

# (Use to post in the top of your crontab)
# ------------- minute (0 - 59)
# | ----------- hour (0 - 23)
# | | --------- day of month (1 - 31)
# | | | ------- month (1 - 12)
# | | | | ----- day of week (0 - 6) (Sunday=0)
# | | | | |
# * * * * * command to be executed

/**
 * =========> 1)
 * If your script is one of those that must be called from a web browser, like "cron.php" on a Drupal installation,
 * you will need to use a command called "wget". Technically speaking, wget is not really a browser,
 * but it works adequately like one for our purpose here,
 * which is to simply get the web server to run the script called "cron.php".
 *
 * 30 11 * * * /usr/bin/wget http://www.example.com/cron.php
 * e.g 2: * * * * * php -q /home/imediare/public_html/cron/cron.php
 *
 * =========> 2)
 *
 * If your script is very talkative, and issues all sort of information when it executes,
 * you'll probably want to shut it up (unless you are starved for email messages).
 * To do this, we need to send all the normal output to a place called "/dev/null" which is basically like a black hole.
 * It accepts anything you dump there, but you will never see it again. In the case of our first example,
 * modify the command line to read:
 *
 * 30 11 * * * /your/directory/whatever.pl >/dev/null
 *
 *
 * =========> 3)
 *
 * The ">" sign means to redirect every normal message sent to screen to whatever is next in the command line,
 * which, in our case, is /dev/null. If your script is designed to work correctly in a Unix environment,
 * only the normal output will be swallowed up. Error messages will still be processed by the cron program.
 * This is desirable, since you will want to informed when something is wrong so that you can fix the problem.
 *
 *
 *
 * =========> 4)
 *
 * To receive the remaining unredirected messages, you will need to add another line to your crontab schedule
 * to specify your email address. Use the following format:
 *
 * MAILTO=email@example.com
 * 30 11 * * * /your/directory/whatever.pl >/dev/null
 *
 * The MAILTO line must be on a separate line. It is optional.
 * That is, you don't have to specify it if you don't want to.
 * Depending on how your web host has set up the system, cron might still be able to successfully send you error messages.
 * If you really don't want to hear from cron at all, you will need to make your MAILTO line look like this:
 *
 * MAILTO=""
 * put two double quotation marks without any space between them.
 *
 *
 * =========> 5)
 *
 *
 *
 */


// {{name}} , {{email}} ,{{group}} ,{{webName}} ,{{webLink}} ,{{link}} for product/order link

//include_once("cronConfig.php");
include_once(__DIR__.'/../global.php');
global $db,$dbF,$functions,$productClass;

$bounceEmail    = $db->bounceEmail;
$bounceHas      = true; // make false to use same as reply to

$sql    =   "SELECT * FROM `email_letter_queue` WHERE status = '1' ORDER BY id ASC  LIMIT 0,3";
$data   =   $dbF->getRows($sql);

if($dbF->rowCount>0){
    foreach($data as $val){
        $sql        = "SELECT * FROM email_letters WHERE id = '$val[letter_id]' ";
        $letterData =  $dbF->getRow($sql);

        $subject    =   $letterData['subject'];
        $msg        =   $letterData['message'];
        $replay     =   $letterData['reply_to'];
        if($bounceHas){
            $return_to  =   $bounceEmail;
        }else{
            $return_to  =   $letterData['return_path'];
        }

        $name_from  =   $letterData['from_name'];
        $email_from =   $letterData['from_mail']."@".$db->defaultEmail;

        $grp        =   $val['grp'];
        $email_to   =   $val['email_to'];
        $name_to    =   $val['email_name'];

        if($name_to==''){
            $email_toArray  =   explode("@",$email_to);
            $email_toArray  =   $email_toArray[0];
            $email_toArray  =   explode("@",$email_toArray);

            $email_toArray  =   $email_toArray[0];
            $email_toArray  =   explode("_",$email_toArray);

            $email_toArray  =   $email_toArray[0];
            $email_toArray  =   explode(".",$email_toArray);

            $email_toArray  =   $email_toArray[0];
            $email_toArray  =   explode("-",$email_toArray);
            $name_to        =   $email_toArray[0];
        }

        //replace characters
        /*
         * USE these Keys to replace user INFO in SUBJECT OR IN Letter <br>
           email : {{email}} , name : {{name}} , group : {{group}}
         */
        $subject        =   str_replace("{{name}}",$name_to,$subject);
        $subject        =   str_replace("{{email}}",$email_to,$subject);
        $subject        =   str_replace("{{group}}",$grp,$subject);

        $msg        =   str_replace("{{name}}",$name_to,$msg);
        $msg        =   str_replace("{{webName}}",$db->webName,$msg);
        $msg        =   str_replace("{{webLink}}",WEB_URL,$msg);
        $msg        =   str_replace("{{email}}",$email_to,$msg);
        $msg        =   str_replace("{{group}}",$grp,$msg);

        $pId        =   intval(@$val['p_id']);

        if($pId > 0 && $val['grp'] == 'SalesTrigger' || $pId > 0 && $val['grp'] == 'StockTrigger' ){
            //this is for product sale links
            $p_data       =     $productClass->productData($pId);
            $pName        =     translateFromSerialize($p_data['prodet_name']);
            $pShrtDesc    =     translateFromSerialize($p_data['prodet_shortDesc']);
            $p_slug       =     $p_data['slug'];
            $p_link       =     WEB_URL."/".$db->productDetail.$p_slug;

            $msg        =   str_replace("{{link}}",$p_link,$msg);
            $msg        =   str_replace("{{product}}",$pName,$msg);
            $msg        =   str_replace("{{shrt_desc}}",$pShrtDesc,$msg);
        }

        if($pId > 0 && $val['grp'] == 'orderThankYouMail'){
            //this is for product sale links
            $msg        =   str_replace("{{link}}",WEB_URL."/viewOrder?view=$pId&orderId=".$functions->encode($pId),$msg);
        }

        $org    =   $db->webName;
        //sending Email
        $to = $email_to;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= "X-Mailer: PHP v".phpversion()."\r\n";
        $headers .= "From: $name_from <$email_from>\r\n";
        $headers .= "X-Sender: $email_from <$email_from>\n";
        //$headers .= "To: $name_to <".$email_to.">\r\n";
        $headers .= "Delivered-to: $name_to\n";
        $headers .= "Reply-To: <$replay>\r\n";
        $headers .= "Organization: $org\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "Importance: 3\r\n";
        $headers .= "Content-Transfer-encoding: 8bit\r\n";
        $headers .= "X-MSMail-Priority: High\r\n";
        $headers .= "Date: ".date('r')."\r\n";

        $msgId  = date('r')." webmaster@".$db->defaultEmail;
        $msgId = str_replace(" ","_",$msgId);
        $headers .= "Message-ID: <$msgId>\r\n";

        $returnpath="'-f $return_to'";
        /*
              echo $to;
              echo "<br>";
              echo $subject;
              echo "<br>";
              echo $msg;
              echo "<br>";
              echo $headers;
       */

        //mail($to, $subject, $msg, $headers,$returnpath);
        $mail_send  = $functions->send_phpmailer_mail($to, $subject, $msg, $headers,$returnpath);


        $sql    =   "DELETE FROM `email_letter_queue` WHERE id='$val[id]' ";
        $db->query($sql);
    }
}else{

    /*$sql    =   "INSERT INTO cronjob (job) values('complete')";
    $dbF->setRow($sql);*/

    $requiredCronFile   =   "../".ADMIN_FOLDER."/requiredCron.txt";
    $file   =  "okay";
    file_put_contents($requiredCronFile, $file); //blank file..


    //shell not work from cron
    //remove CronJob
    $output = shell_exec('crontab -l');
    $cron_file = "crontab.txt";
    /*remove single crone*/
     $file   =   CRON_FILE;
     $remove_cron = str_replace($file."\n", "", $output);
     $remove_cron = str_replace("\n\n", "", $remove_cron);
     file_put_contents($cron_file, $remove_cron.PHP_EOL);

     exec('crontab '.$cron_file);


    //remove all crone
     /* shell_exec("crontab -r");
    $file  = 'SHELL="/usr/local/cpanel/bin/jailshell"'."\n";
    file_put_contents($cron_file, $file);*/
     //blank file..
}



?>