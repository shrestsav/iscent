<?php
error_reporting(0);ini_set('display_errors', 0);
//for direct cart submit use orderInvoice.php?ds
//first direct submit then all avaiable payment method will show, for checkout
include("global.php");
global $webClass;
global $productClass;

$msg             = $productClass->cartSubmit();
$cartReturnData  = $productClass->viewCartTable3();
@$cartReturn     = $cartReturnData['cart'];
@$sizeModal     = $cartReturnData['sizeModal'];
@$checkOutOffer  = $cartReturnData['offer'];

define('CART_PAGE', true);
include("header.php");

?>




<!--Inner Container Starts-->
<div class="inner_details_container cart3 container-fluid padding-0">


<div class="inner_details_content">

<?php if(!empty($checkOutOffer)){ ?>
<div class="check_out_offer_main">
<div class="check_out_offer_head">
<div class="checkout_offer_line"></div>
<div class="checkout_offer_heading_text">
<h1 class="cart_check_head"><?php $dbF->hardWords('Check Out our Offer');?></h1>
</div>
</div>

<div class="container-fluid padding-0" style="margin-top:30px;">
<?php echo $checkOutOffer;?>
</div><!--r_box_area end-->
</div><!--related_products_area end-->
<br><br>
<?php } ?>


<div class="inner_content_page_div futura_bk_bt">
<div class="cart2">
<?php
if($msg!=''){
echo '<div class="alert alert-info" role="alert">'.$msg.'</div>';
}
?>

<?php

if($cartReturn===false && $msg==''){
$var=$dbF->hardWords('No Items Found In Your Cart',false);
echo "<div id='EmptyCartView' class='alert alert-info'>$var</div>";
}else{
echo $cartReturn;
}
?>

</div><!--Cart2 end-->
</div>
</div>
<?php echo $sizeModal; ?>
<!--sizeModal end-->    
</div>

<style>
.colors_in_divs {
padding: 11px;
}

.checkout_scale label {
padding: 6px 14px !important;
}

.glyphicon {
font-size: initial !important;
}


</style>

<style>
.cart3 .inner_details_content {
width: auto;
}
.inner_content_page_div {
display: inline-block;
width: 100%;
padding-bottom: 10px;
min-height: 300px;
}
.futura_bk_bt {
/*width: 45px;*/
margin-right: 3px;
transition: .7s;
}
.cart3 .head_cart {
color: #333333;
text-align: center;
font-size: 36px;
font-family: 'Titillium Web', sans-serif;
font-weight: 600;
overflow: hidden;
}
.cart3 .items_cart {
color: #939191;
text-align: center;
font-size: 17px;
font-family: 'Titillium Web', sans-serif;
height: 23px;
overflow: hidden;
}
.cart3 .one_cart {
width: 64%;
height: auto;
margin-top: 2%;
}
.inline_block {
display: inline-block;
vertical-align: top;
width: 25%;
}
.cart3 .oc_head {
height: 80px;
width: 100%;
background: #ebebeb;
padding: 15px;
}
.cart3 .oc_heading {
color: #7cbe35;
font-size: 14px;
font-family: 'Titillium Web', sans-serif;
font-weight: 600;
}
.cart3 .oc_text {
font-size: 15px;
height: 20px;
overflow: hidden;
color: #262626;
font-style: italic;
font-family: 'Titillium Web', sans-serif;
}
.cart3 .oc_text a {
color: #7cbe35;
text-decoration: underline;
}
.cart3 .detail_cart {
width: 100%;
overflow: hidden;
margin-top: 3%;
}
.cart3 .img_detail {
width: 119px;
height: 126px;
}
.cart3 .img_detail img, .img_detail2 img {
max-width: 100%;
max-height: 100%;
}
.cart3 .info_cart {
width: 369px;
overflow: hidden;
margin-left: 12px;
}
.cart3 .info_head {
color: #262626;
font-family: 'Titillium Web', sans-serif;
font-size: 15px;
/* height: 21px; */
overflow: hidden;
font-weight: 600;
margin-bottom: 9px;
}
.cart3 .info_head a{
color: #363636;
}
.cart3 .info_text {
color: #262626;
font-family: 'Titillium Web', sans-serif;
font-size: 14px;
line-height: 14px;
text-shadow: 0 0 0 #262626;
}
.cart3 .info_text label {
color: #262626;
font-family: 'Titillium Web', sans-serif;
font-size: 14px;
font-weight: 600;
width: 53px;
margin-right: 12px;
}
.cart3 .info_btn {
display: inline-block;
vertical-align: top;
width: 167px;
height: 43px;
line-height: 43px;
border: 0;
text-align: center;
border-bottom: 2px solid #d61b3b;
background: #ededed;
font-family: 'Titillium Web', sans-serif;
font-weight: 600;
margin-right: 10px;
border-radius: 5px;
color: #333333;
font-size: 16px;
transition: .7s;
-webkit-transition: .7s;
-moz-transition: .7s;
-o-transition: .7s;
margin-top: 23px;
}
.cart3 .reload_btn {
display: inline-block;
vertical-align: top;
margin-top: 30px;
width: 120px;
height: 43px;
}
.addByQtyDiv {
width: 65px;
margin: 0 5px 0 0;
}
.addByQtyDiv input {
width: 100%;
}
.cart3 .addByQtyBtn {
margin: 0;
margin-top: 30px;
}
.addByQtyBtn {
background-color: #E4E5DF;
padding: 5px 4px !important;
margin: 0 5px;
height: 25px;
width: 40px;
border-radius: 4px;
text-align: center;
display: inline-block;
cursor: pointer;
}
.cart3 .rate_detail {
color: #262626;
font-family: 'Titillium Web', sans-serif;
width: 106px;
height: 20px;
font-size: 18px;
float: right;
text-align: right;
text-shadow: 0 0 0 #262626;
overflow: hidden;
}
.tooltip_css {
margin-left: 5px;
display: inline-block;
display: none !important;
}
.tooltip_css img {
width: 13px;
margin-bottom: 3px;
}
.cart3 .two_cart {
width: 33%;
height: auto;
margin-top: 2%;
background: #363636;
margin-left: 25px;
}
.cart3 .summary {
color: #fff;
font-family: 'Titillium Web', sans-serif;
font-weight: 600;
width: 100%;
height: 65px;
line-height: 65px;
padding-left: 20px;
padding-right: 20px;
font-size: 24px;
}
.cart3 .tc_line {
width: 100%;
height: 3px;
background: url(<?php echo WEB_URL; ?>/images/line.png);
}
.cart3 .promo-code {
width: 100%;
padding: 20px;
}
.cart3 .pc_text {
color: #fff;
font-family: 'Titillium Web', sans-serif;
font-size: 14px;
}
.cart3 .pc_field {
display: inline-block;
vertical-align: top;
width: 171px;
height: 33px;
border: 1px solid transparent;
border: 0;
border-radius: 5px;
background: #fff;
margin-top: 14px;
transition: .7s;
-webkit-transition: .7s;
-moz-transition: .7s;
-o-transition: .7s;
margin-right: 6px;
}
.cart3 .apply {
display: inline-block;
vertical-align: top;
width: 97px;
height: 33px;
transition: .7s;
-webkit-transition: .7s;
-moz-transition: .7s;
-o-transition: .7s;
text-shadow: 0 0 0 #fff;
border: 0;
border: 1px solid transparent;
border-radius: 5px;
background: #ebebeb;
margin-top: 14px;
font-family: 'Titillium Web', sans-serif;
color: #333333;
font-size: 14px;
}
.cart3 .sub_box {
width: 100%;
padding-left: 20px;
padding-right: 20px;
padding-top: 14px;
position: relative;
padding-bottom: 14px;
}
.cart3 .sub_1 {
font-family: 'Titillium Web', sans-serif;
color: #fff;
font-size: 12px;
width: 226px;
display: inline-block;
}
.cart3 .sub_2 {
font-family: 'Titillium Web', sans-serif;
color: #fff;
font-size: 12px;
text-align: right;
display: inline-block;
float: right;
clear: both;
}
.cart3 .checkout {
width: 100%;
padding: 0;
height: 41px;
line-height: 41px;
text-align: center;
color: #fff;
border: 0;
border: 1px solid transparent;
border-radius: 5px;
font-family: 'Titillium Web', sans-serif;
font-size: 15px;
background: #d61b3b;
transition: .7s;
-webkit-transition: .7s;
-moz-transition: .7s;
-o-transition: .7s;
}
.cart3 .sub_font1 {
font-size: 18px;
width: auto;
}
.cart3 .text_sub {
font-family: 'Titillium Web', sans-serif;
color: #fff;
font-size: 13px;
margin-top: 16px;
}
























</style>
<?php include("footer.php"); ?>