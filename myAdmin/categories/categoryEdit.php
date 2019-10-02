<?php
ob_start();

$functions->includeOnceCustom(ADMIN_FOLDER."/categories/classes/category.class.php");
global $dbF;

$menuC  =   new webMenu();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
//$menu->menuEditSubmit();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Update Menu']); ?></h2>

<?php $menuC->menuEdit(); ?>
<?php $menuC->menuWidgetLinks(); ?>

<script>
    $(function(){
        dateJqueryUi();

        $(".menuType").change(function(){
            menuTypes();
        });
        menuTypes();


        function menuTypes(){
            icon = $(".menuType option:selected").attr("data-icon");
            if(icon=='1'){
                $(".icon-div").show(500);
            }else{
                $(".icon-div").hide(500);
            }
            console.log(icon)
        }

    });

</script>
<?php return ob_get_clean(); ?>