<?php
ob_start();

require_once("classes/webUsers.class.php");
global $dbF;

$webUser  =   new webUsers();
?>
<a href="-webUsers?page=AdminGrp" class="btn btn-primary"><?php echo _uc($_e['Back To AdminGroups']); ?></a>
<h2 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Edit User Group Permissions']); ?></h2>

<?php $webUser->newAdminGrp(); ?>


<script src="webUsers/js/user.js"></script>

<script>
    $(function(){
        dateJqueryUi();
    });

</script>
<?php return ob_get_clean(); ?>