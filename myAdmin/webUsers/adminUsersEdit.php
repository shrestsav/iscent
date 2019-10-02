<?php
ob_start();

require_once("classes/webUsers.class.php");
global $dbF;

$webUser  =   new webUsers();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
//$msg = $webUser->adminUserEditSubmit();
/*if($msg !=''){
    $functions->notificationError('WebUser',$msg,'btn-info');
}*/
?>
<a href="-webUsers?page=AdminUsers" class="btn btn-primary"><?php echo _uc($_e['Back To AdminUsers']); ?></a>
<h2 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Edit User Info']); ?></h2>

<?php
$webUser->newAdminUser(); ?>

<script src="webUsers/js/user.js"></script>

<script>
    $(function(){
        dateJqueryUi();
    });
</script>
<?php return ob_get_clean(); ?>