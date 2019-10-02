<?php
ob_start();

require_once("classes/menu.class.php");
global $dbF;

$menuC  =   new webMenu();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$menuC->footerMenuEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Update Footer Menu']); ?></h2>

 <?php $menuC->FooterMenuEdit(); ?>
<?php $menuC->menuWidgetLinks(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>