<?php include_once("global.php");

global $webClass;

if($seo['title']==''){

$seo['title'] = 'User Profile';

}

$login = $webClass->userLoginCheck();

if(!$login){

header('Location: login');

exit();

}

$user_id = $webClass->webUserId();
$user_name = $webClass->webUserName($user_id);



$msg = $webClass->webUserEditSubmit();

include("header.php");

require_once(__DIR__ . '/' . ADMIN_FOLDER . '/order/classes/order.php');

$orderC = new order();

?>

<div class="bg_inner" style="background-image: url(<?php echo WEB_URL.'/images/default_banner.jpg' ?>)"></div>

<!--Inner Container Starts-->

<div class="container-fluid padding-0">

<div class="standard">

<?php

if(isset($_GET['page'])){

?>

 <div class="home_links_heading h3 well well-sm">
<a href="#" onclick="goBack()">
                <?php $dbF->hardWords('Back'); ?>
                </a>
            </div>



<?php




 echo '<div class="home_links_heading h3 well well-sm">';
$page = $_GET['page'];
echo htmlentities($_GET['page']);

echo "</div>";

}else{
echo '<div class="home_links_heading h3 well well-sm">

<span style="float: left;">
Welcome '.$user_name.'
</span>

<span style="float: right;">
<a href="'.WEB_URL.'/profile?page=Profile">Edit Profile </a>
</span>


</div>';
}

if(isset($_GET['update'])):

?>


<div class="alert alert-warning">
<strong>Warning!</strong> Please update your password.
</div>
<style type="text/css">

.hide{
visibility: hidden;
}

</style>
<?php endif; ?>

<div class="inner_content_page_div container-fluid">

<?php if(!isset($_GET['page'])){ ?>

<div class="wrapper_main">
<div class="wrapper">
<h4>Schedule</h4>
<a href="<?php echo WEB_URL; ?>/profile?page=schedule" class="n_btn">View All</a>
<div class="wrapper_txt">
<?php 
$avail_call = $orderC->allScheduleData($user_id, true);

foreach ($avail_call as $key => $value) {
echo '<p>'.$value['schedule_date'].'</p>';
echo '<p>'.$value['schedule_slot'].'</p>';
}


?>
</div>
</div>

<div class="wrapper">
<h4>Subscription Plan</h4>


<a href="<?php echo WEB_URL; ?>/viewOrder" class="n_btn">View All</a>
<div class="wrapper_txt">
<?php 
$open_orders = $orderC->allOpenOrders($user_id);

foreach ($open_orders as $key => $value) {
$pId = $value['product_id'];
$order_id = $value['order_id'];
$del_request = $value['del_request'];

// $payment_mode = $value['payment_mode'];
// $price_per_month = $value['price_per_month'];


$sql = "SELECT `prodet_name`,`prodet_shortDesc` FROM `proudct_detail` WHERE `prodet_id` = ?";
$res = $dbF->getRow($sql, array($pId));

$prodet_name = translateFromSerialize($res['prodet_name']);
$prodet_shortDesc = translateFromSerialize($res['prodet_shortDesc']);


echo '<p>'.$prodet_name.'</p>';
echo '<p>'.$prodet_shortDesc.'</p>';


$sqli = "SELECT `setting_val` FROM `product_setting` WHERE `p_id` = ? and `setting_name` = ?";
$resi = $dbF->getRow($sqli, array($pId,"cancel_charges"));
$cancel_charges = ($resi['setting_val']);



$sqlj = "SELECT `setting_val` FROM `product_setting` WHERE `p_id` = ? and `setting_name` = ?";
$resj = $dbF->getRow($sqlj, array($pId,"actual_expire"));
$actual_expire = ($resj['setting_val']);


// echo '<a data-id="'.$order_id.'" onclick="delOrderInvoice(this);" class="n_btn1">';



if($del_request == 1){
echo '<a class="n_btn1">';

echo'Canceled</a>';

}else{
echo '<a data-id="'.$order_id.'" data-exp="'.$actual_expire.'" data-fee="'.$cancel_charges.'" onclick="delOrderInvoice(this);" class="n_btn1">';

echo'Cancel Subscription</a>';


}
echo '<hr>';
}


?>
</div>
</div>

<div class="wrapper">
<h4>Support</h4>
 <a data-toggle="modal" id="openSupport" data-target="#message_modal" class="n_btn">New Query</a>
<div class="wrapper_txt">
<?php 

$sql = "SELECT `message`,`message_by` FROM `user_messages` WHERE `user_id` = ? ORDER BY `date` DESC LIMIT 1";
$res = $dbF->getRow($sql, array($user_id));

        if($dbF->rowCount>0){

echo '<p>'.$res['message'].' ( '.$res['message_by'].' )</p>';
}
?>
</div>
</div>
</div><!--wrapper_main-->

<?php } ?>


<?php

if(!isset($_GET['page'])){

?>


<div class="wrapper_main2">

<!-- <div class="col-sm-6 text-center padding-0">

<a href="<?php# echo WEB_URL;?>/profile?page=Profile">

<img src="<?php #echo WEB_URL;?>/images/profile.png" width="90">

<div><?php #$dbF->hardWords('Customer Profile'); ?></div>

</a>

</div> -->



<?php if($functions->developer_setting('cartSystem') == '1'){ ?>

<div class="col-sm-6 text-center padding-0">

<a href="<?php echo WEB_URL;?>/viewOrder">

<img src="<?php echo WEB_URL;?>/images/cartorder.png" width="90">

<div><?php $dbF->hardWords('Orders');?></div>

</a>

</div>

<?php } ?>



<?php if($functions->developer_setting('return_Product_from_client') == '1'){ ?>

<div class="col-sm-6 text-center padding-0">

<a href="<?php echo WEB_URL;?>/productReturn">

<img src="<?php echo WEB_URL;?>/images/returnorder.png" width="90">

<div><?php $dbF->hardWords('Return');?></div>

</a>

</div>

<?php } ?>



<?php if($functions->developer_setting('defect_Product_from_client') == '1'){ ?>

<div class="col-sm-6 text-center padding-0">

<a href="<?php echo WEB_URL;?>/productDefect">

<img src="<?php echo WEB_URL;?>/images/defect.png" height="90" width="">

<div><?php $dbF->hardWords('Defect');?></div>

</a>

</div>

<?php } ?>



<!--<div class="col-sm-2 text-center padding-0">

<a href="<?php /*echo WEB_URL;*/?>/cartWishList.php">

<img src="<?php /*echo WEB_URL;*/?>/images/wishlish.png" width="90">

<div><?php /*$dbF->hardWords('Wish List');*/?></div>

</a>

</div>-->


<div class="col-sm-6 text-center padding-0">

<a href="<?php echo WEB_URL;?>/profile?page=schedule">

<img src="<?php echo WEB_URL;?>/images/schedule.png" width="90">

<div><?php $dbF->hardWords('Schedules');?></div>

</a>

</div>


<div class="col-sm-6 text-center padding-0">

<a href="<?php echo WEB_URL;?>/logout">

<img src="<?php echo WEB_URL;?>/images/logout.png" width="90">

<div><?php $dbF->hardWords('LogOut');?></div>

</a>

</div>

<div class="col-sm-6 text-center padding-0">

<a data-toggle="modal" data-target="#message_modal" id="openSupport">

<img src="<?php echo WEB_URL;?>/images/support-icon.jpg" width="90">

<div><?php $dbF->hardWords('Support');?></div>

</a>

</div>



<div class="clearfix"></div>
</div>
<!--wrapper_main2-->
<?php

}else{

?>



<?php

if($msg!=''){

echo "<div class='alert alert-success'>$msg</div>";

}

if($page == 'Profile'){

$webClass->webUserEdit($webClass->webUserId());

}


if($page == 'schedule'){

include('schedules.php');

}

}

?>

</div>

</div>

</div>

<?php //include_once('support.php'); ?>

<?php 

$message_body = '<div class="container-fluid">

<div class="error_msg"></div>
<div id="message_div"></div>
<form action="" method="post" class="form-horizontal" id="message_form" >
<input type="hidden" name="message_cUser" id="message_cUser" value="'.$user_id.'">

<div class="form-group">
<div class="container-fluid" style="position: relative;">


<textarea class="form-control" name="message_text" id="message_text" placeholder="Message Text"></textarea>

<br>

<input type="button" class="form-control btn btn-primary" id="messageSendButton" name="messageSendButton" value="SUBMIT" />

</div>
</div>

</form>
</div>'; 

echo $functions->blankModal('Messages', 'message_modal', $message_body, "Close");


?>

<!--Inner Container Ends-->

<style type="text/css">
.n_btn1 {
    width: 170px; 

        font-size: 16px;
    border: 2px solid #54c2bb;
    transition: .5s;
    padding: 4px;
    text-align: center;
    color: #232332;
    display: block;
    position: relative;

    float: right;
    margin-top: 8px;
    margin-right: 20px;



    }

hr {

    border-top: 1px solid #D7D7D7;
}
.home_links_heading {

min-height: 40px;

text-transform: uppercase;

width: 100%;

text-align: center;

color: #000;

font-size: 22px;

font-family: 'ralewayextrabold';

margin-bottom: 20px;

}

/*#message_div {
height: 200px;
border: 1px solid #cccccc;
border-radius: 5px;
margin-bottom: 10px;
}*/
</style>

<style>
#status_infoModalLabel{
text-align: center;
}

.highlighed:hover td {
background-color: #7cbe35 !important;
}

#loader {
position: fixed;
top: 0;
width: 100%;
height: 100%;
z-index: 999999999999999999999999999;
background: url(images/loader.gif) center center no-repeat rgba(0,0,0,0.8);
display: none;
}

#dropbox .progress {
background-image: none;
}

.container {
border: 2px solid #dedede;
background-color: #f1f1f1;
border-radius: 5px;
padding: 10px;
margin: 10px 0;
}

.darker {
border-color: #ccc;
background-color: #ddd;
text-align: right;
}

.container::after {
content: "";
clear: both;
display: table;
}

.container img {
float: left;
max-width: 60px;
width: 100%;
margin-right: 20px;
border-radius: 50%;
}

.container img.right {
float: right;
margin-left: 20px;
margin-right:0;
}

.time-right {
float: right;
color: #aaa;
}

.time-left {
/*float: left;*/
color: #999;
}
</style>


<script type="text/javascript">
//modal open button click for tracking info
$('body').on('click', '#openSupport', function(event) {
event.preventDefault();
/* Act on the event */
var id = '<?php echo $user_id; ?>';

$.post('ajax_call.php?page=get_user_message', {id:id}, function(data, textStatus, xhr) {
/*optional stuff to do after success */
$('#message_div').html(data);
// console.log(data);
// $('#tracking_info').modal('hide');
// $("#loader").slideUp(500);
// $('#tracking_text').val(''); // clear the previous value
});

});
</script>


<script type="text/javascript">
$('#messageSendButton').on('click', function(){
form = $('#message_form').serialize();

$.ajax({
url: 'ajax_call.php?page=sendUserMessage',
type: 'post',
data: form
}).done(function(res){
// console.log(res);
result = JSON.parse(res);
if(result.ret == ''){
$('.error_msg').html('<span class="alert alert-danger">Something Went Wrong! Please Try Again.</span>');
}else{
$('#message_div').append(result.ret);
$('.error_msg').html('<span class="alert alert-success">Message Sent Successfullly.</span>');
}
});
});

function goBack() {
    window.history.back();
}
function validURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return !!pattern.test(str);
}

   function delOrderInvoice(ths){
        order = $(ths).data('id');
        month = $(ths).data('exp');
        fee = $(ths).data('fee');


confirm_order = 'Are you sure you want to Delete?\nIf you are canceling before the '+month+' th month, please note you will be charged a cancelation fee of '+fee+' AED.';



        // confirm_order = 'Are you sure you want to Delete?\nIf you are canceling before the 12th month, please note you will be charged a cancelation fee of 499dhs.';

        if(secure_delete(confirm_order)){
            $.ajax({
                url: 'ajax_call.php?page=cancelOrder',
                type: 'post',
                data: {order:order}
            }).done(function(res){
                if(validURL(res)){location.replace(res)}
                else if(res == '1'){
                    alert('Your request has been processed, someone from our operations team will contact you shortly to arrange pick up of the system');
                }else{
                    
                    alert('Something Went Wrong! Please Try Again.');
                }
            });
                 console.log(res);
       }
    }

    function secure_delete(text){
        // text = 'view on alert';
        text = typeof text !== 'undefined' ? text : 'Are you sure you want to Delete?';

        bool=confirm(text);
        if(bool==false){return false;}else{return true;}

    }
</script>

<?php include("footer.php"); ?>