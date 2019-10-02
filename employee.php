<?php
ob_start();
include_once("global.php");
global $webClass;
global $dbF;
global $_e;
$_w = array();
$_w['Email'] = '' ;
$_w['Interest'] = '' ;
$_e    =   $dbF->hardWordsMulti($_w,currentWebLanguage(),'Website Employee');

$sql = " SELECT `accounts_user`.*,
                `accounts_user_detail`.`id_user`,`accounts_user_detail`.`setting_name`, `accounts_user_detail`.`setting_val` as sortV
           FROM accounts_user join accounts_user_detail
            on `accounts_user`.`acc_id` = `accounts_user_detail`.`id_user`
              WHERE   acc_type = '1'
               AND `acc_id` in (SELECT id_user FROM  accounts_user_detail WHERE setting_name = 'employee' AND setting_val = '1')
                AND `accounts_user_detail`.`setting_name` = 'sort'
                  ORDER BY   sortV ASC";
$data = $dbF->getRows($sql);
if (!$dbF->rowCount) {
    return false;
}

echo  '<div class="container-fluid padding-0">';
foreach ($data as $key => $val) {
    $id = $val['acc_id'];

    $sql = "SELECT * FROM accounts_user_detail WHERE id_user = '$id'";
    $userInfo = $dbF->getRows($sql);
    //var_dump($userInfo);
    $image    = $webClass->webUserInfoArray($userInfo,'image');
    $email    = $val['acc_email'];
    $imageR   = $image;
    $image   =  $functions->resizeImage($imageR,'250','',false,false,false);
    $designation    = $webClass->webUserInfoArray($userInfo,'designation');
    $phone    = $webClass->webUserInfoArray($userInfo,'phone');
    $category = $webClass->webUserInfoArray($userInfo,'category');
    $interests    = $webClass->webUserInfoArray($userInfo,'interests');
    $name = $val['acc_name'];

    $categoryU = _uc($category);


    $linkCat = _uc(empty($_GET['eCat']) ? "" : $_GET['eCat']);
    if(isset($_GET['eCat'])){
        if($linkCat != $categoryU){
            continue;
        }
    }else{
        $currentLInk = pageLink(false);
        $currentLInk.= "&eCat=$categoryU";
        echo "<a href='$currentLInk' class='btn btn-info '>$category</a>  ";

    }

    if(isset($_GET['eCat'])) {

        echo '
            <div class="col-sm-4 col-md-3 employees" style="padding:0 5px;">
                <div class="thumbnail margin-0" >
                   <a class="employee_elements" rel="employee-group" href="'.$imageR.'" ><img src="'.$image.'" style="max-height:130px" class="img-responsive " alt="'.$name.'"></a>
                    <div class="caption ">
                        <h3  class="">' . $name . '</h3>
                        <div class="empposition">' . $designation . '</div>
                        <div class="empnumber">' . $phone . '</div>
                        <div class="empemail">' . $_e['Email'] . ': <label>' . $email . '</label></div>
                        <div class="empinterest">' . $_e['Interest'] . ': ' . $interests . ' </div>
                </div>
                </div>
            </div> <!--emp 1 end-->';



    }

}

echo '</div><!--container-fluid-->';

echo "
<style>
.employees{
    min-height:330px;

}
</style>
<script>
        $(document).ready(function(){
            $('a.employee_elements').fancybox();
        });
</script>";


return ob_get_clean(); ?>