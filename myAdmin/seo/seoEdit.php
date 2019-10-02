<?php
ob_start();

require_once("classes/seo.class.php");
global $dbF;

$seo  =   new seo();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$seo->seoEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage SEO']); ?></h2>

<?php $seo->seoEdit(); ?>
<?php

global $functions;
$functions->includeAdminFile("menu/classes/menu.class.php");
$menuC    =   new WebMenu();
$menuC->menuWidgetLinks();
?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>