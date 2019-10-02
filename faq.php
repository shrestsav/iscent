<?php
ob_start();
include_once("global.php");
global $webClass, $dbF;
?>
<?php echo $webClass->faq(); ?>
<?php
return ob_get_clean(); ?>