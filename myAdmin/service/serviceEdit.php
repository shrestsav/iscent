<?php
ob_start();

require_once("classes/service.class.php");
global $dbF;

$service  =   new service();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$service->serviceEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc('Manage Page'); ?></h2>
<?php $service->serviceEdit(); ?>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>