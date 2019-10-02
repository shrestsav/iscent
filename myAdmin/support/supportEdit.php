<?php

ob_start();



require_once("classes/fileM.class.php");

global $dbF;



$file  =   new fileM();



//$dbF->prnt($_POST);

//$dbF->prnt($_FILES);

//exit;

$file->FileEditSubmit();

?>

<h2 class="sub_heading"><?php echo _uc($_e['Update']); ?></h2>



<?php $file->fileEdit(); ?>



<?php return ob_get_clean(); ?>