<?php

ob_start();



require_once("classes/pages.class.php");

global $dbF;



$pages  =   new pages();

$pages->homePageBoxEditSubmit();

?>

<h2 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Update Home Page Box']); ?></h2>



<?php $pages->homePageBoxEdit(); ?>



<?php return ob_get_clean(); ?>