<?php
ob_start();

require_once("classes/banner.class.php");
global $dbF;

$banners  =   new banners();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$banners->bannersEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Banners']); ?></h2>
<?php $banners->bannersEdit(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>