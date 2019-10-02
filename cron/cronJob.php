<?php
//admin login required to access this page
session_start();
if(isset($_SESSION['_uid']) && $_SESSION['_uid']>0){
    switch ($_SESSION["_role"]):
        case "super_admin":
        case "admin":
        case "manager":
            break;
        default :
            //no admin login
            exit;
            break;
    endswitch;
}else{
    //no admin login
    exit;
}


$output = shell_exec('crontab -l');

$cron_file = "crontab.txt";

//exec('crontab '.$cron_file);?>
<?php

?>

<!-- Execute script when form is submitted -->
<?php if(isset($_POST['add_cron'])) { ?>

    <!-- Add new cron job -->
    <?php if(!empty($_POST['add_cron'])) {
        echo $_POST['add_cron'];
        ?>
        <?php file_put_contents($cron_file, $output.$_POST['add_cron'].PHP_EOL); ?>
    <?php } ?>

    <!-- Remove cron job -->
    <?php if(!empty($_POST['remove_cron'])) { ?>
        <?php $remove_cron = str_replace($_POST['remove_cron']."\n", "", $output);
              //$remove_cron = preg_replace('/^[ \t]*[\r\n]+/m', '', $remove_cron);
              //remove blank lines
        ?>
        <?php file_put_contents($cron_file, $remove_cron.PHP_EOL); ?>
    <?php } ?>

    <!-- Remove all cron jobs -->
    <?php if(isset($_POST['remove_all_cron'])) { ?>
        <?php
        echo exec("crontab -r");
        file_put_contents($cron_file, 'SHELL="/usr/local/cpanel/bin/jailshell"'); // blank file
?>
    <?php } else { ?>
        <?php
        echo exec("crontab $cron_file");
            //execute cron job, what is crob.txt file... write job in .txt file and execute
        ?>
    <?php } ?>

    <!-- Reload page to get updated cron jobs -->
    <?php $uri = $_SERVER['REQUEST_URI']; ?>
    <?php //header("Location: $uri"); ?>
    <?php exit; ?>
<?php } ?>





<b>Current Cron Jobs:</b><br>
<?php echo nl2br($output); ?>

<h2>Add or Remove Cron Job</h2>
<form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
    <b>Add New Cron Job:</b><br>
    <input type="text" name="add_cron" size="100" placeholder="e.g.: * * * * * /usr/local/bin/php -q /home/username/public_html/my_cron.php"><br>
    <b>Remove Cron Job:</b><br>
    <input type="text" name="remove_cron" size="100" placeholder="e.g.: * * * * * /usr/local/bin/php -q /home/username/public_html/my_cron.php"><br>
    <input type="checkbox" name="remove_all_cron" value="1"> Remove all cron jobs?<br>
    <input type="submit"><br>
</form>