<?php
ob_start();

require_once("classes/pages.class.php");
global $dbF;

$pages  =   new pages();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$pages->PageEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Update']); ?></h2>

<?php $pages->pageEdit(); ?>

<?php return ob_get_clean(); ?>