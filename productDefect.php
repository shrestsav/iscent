<?php
include_once("global.php");
global $webClass;
$pMmsg = '';

if($functions->developer_setting('product_return_form_login_required') == '1') {
    $login = $webClass->userLoginCheck();
    if (!$login) {
        header("Location: login.php");
        exit();
    }
}

$userId = $webClass->webUserId();

$contactAllow = true;

include_once('header.php'); ?>

<!--Inner Container Starts-->

<div class="container-fluid padding-0">
    <div class="standard container-fluid padding-0">
    <div class="home_links_heading h3 well well-sm"><?php $dbF->hardWords('Defected Product');?></div>

<?php
if(isset($_POST) && !empty($_POST) ){
        if($functions->getFormToken('defectForm')){

            $returnImagesName   =   $functions->uploadMultiImages($_FILES['image'],'defect','');
            $returnImagesName   =   serialize($returnImagesName);

            $_POST['insert']['image'] = $returnImagesName;
            $lastId = $functions->formInsert("product_return_form",$_POST['insert']);
            if($lastId>0){
                $pMmsg  =   $dbF->hardWords('Defect Product Submit Successfully',false);
                $contactAllow = false;
            }else{
                $pMmsg  =   $dbF->hardWords('Defect Product Submit Failed',false);
                $contactAllow = true;
            }
        }else{
            $contactAllow = true;
        }
    if($pMmsg!=''){
        echo "<div class='alert alert-info'>$pMmsg</div>";
    }

}
if($contactAllow){

?>
<div class="col-sm-12">
<br>
    <?php     $productClass->productReturnOrDefectForm('defect'); ?>
</div>


    </div>
    </div>

    <div class="clearfix"></div>
<?php
}


include_once('footer.php');