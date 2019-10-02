<?php
ob_start();

include_once("global.php");
global $productClass,$webClass,$dbF,$functions,$db;

@$id = $_GET['mailId'];

$orderUser  = $webClass->webUserId();
if($orderUser=='0'){
$orderUser = $webClass->webTempUserId();
}

$sql            = "SELECT * FROM `orders` WHERE order_id = '$id'";
$orderInvoice   =   $dbF->getRow($sql);

if($dbF->rowCount>0){

$prod_detail = $functions->getProductName($orderInvoice['product_id'], 'prodet_name');

$prodet_shortDesc    = $functions->getProductName($orderInvoice['product_id'], 'prodet_shortDesc');


$prodet_shortDesc       = translateFromSerialize($prodet_shortDesc['prodet_shortDesc']);

$sql = "SELECT * FROM `order_detail` WHERE order_id = '$id'";
$orderInfo   =   $dbF->getRow($sql);

?>

<body>
<style>

*{
margin:0;
padding:0;
}

.container{
font-family:"Arial", Gadget, sans-serif;
height:auto;
width:760px;
background-color:#ebebeb;
margin:20px auto;
padding:20px;
}

.content_main{
height:auto;
width:97%;
background:#FFF;
margin:0 auto;
padding:10px;
}

.inn_content_div{
border:#ebebeb 1px solid;
width:100%;
height:auto;
}

.top_logo_div{
width:100%;
border-bottom:#ebebeb 1px solid;
display:inline-block;
}

.inner_logo_div{
float: left;
width: 35%;
box-sizing: border-box;
padding: 0 0 0 6px;
}

.inner_logo_div img{
max-width: 100%;
}

.second_details_div{
height:auto;
padding: 3px 10px 20px 10px;
}

.first_in_div{
float: left;
width: 65%;
box-sizing: border-box;
padding: 10px;
text-align: justify;
}

p{
font-size:14px;
}

.second_div{
/*height:50px;*/
border-bottom:#ebebeb 1px solid;
padding:2px 0;
}

.third_div{
height:auto;
padding:10px 5px;
}

.details_table{
font-size:14px;
}

.head_tr td{ 
padding: 10px 6px !important;
}

.detail_tr > td{
padding:6px;
}

.lasts_tr > td{ 
padding: 10px !important;
}

.in_table_pro tr td{
padding:4px;
}

.head_tr{
background:#000;
color:#FFF;
}

.detail_tr{
background:#ebebeb;
margin:5px 0;
}

.border_td{
text-align:center;
}

.lasts_tr{
background:#f1f0f0;
text-align:right;
font-weight:bold;
margin-bottom:5px;
}

.center_td{
text-align:center;
}

.forth_div{
height:auto;
width:100%;
margin-top:15px;
}

</style>

<div class="container">
<div class="content_main">
<div class="inn_content_div">
<div class="top_logo_div">
<div class="inner_logo_div">
<a href="#">
<img src="<?php echo WEB_URL;?>/webImages/logo_invoice.png" style="padding-top:32px;" />
</a>
</div>
<div class="first_in_div">
<?php 
$box = $webClass->getBox('box16'); 
echo $box['text'];
?>
</div>
</div>
<div class="second_details_div">
<div class="second_div">
<table width="100%" style=" height:50px;" cellpadding="0" cellspacing="10">
<tr>
<td>
<p><?php $dbF->hardWords('Order Date'); ?>: </p>
</td>

<td>
<?php 
$date = $orderInvoice['order_date'];
$date = date('Y-m-d',strtotime($date));
echo $date;
?>
</td>
</tr>
<tr>
<td>
<p><?php $dbF->hardWords('Order Number'); ?>:</p>
</td>

<td>
<?php echo $functions->ibms_setting('invoice_key_start_with').$orderInvoice['order_id']; ?>
</td>
</tr>

<tr>
<td width="50%" style="vertical-align:top;" >
<p><?php $dbF->hardWords('Billing Address'); ?>:</p>
</td>
<td>
<?php
$country_list   =   $webClass->functions->countrylist();
$countryName    =   $functions->gcc_emirates($orderInfo['country']);

echo $orderInfo['fname']." ".$orderInfo['lname'].",".$orderInfo['address'].",".$countryName;

?>
</td>
</tr>

<tr>
<td>
<p>Payment Method:</p>
</td>
<td>
<?php echo 'Telr'; ?>
</td> 
</tr>

<tr>
<td>
<p>Phone: </p>
</td>
<td>
<?php echo $orderInfo['mobile']; ?>
</td>
</tr>

<tr>
<td>
<p><?php $dbF->hardWords('E-mail'); ?>: </p>
</td>
<td>
<?php echo $orderInfo['email']; ?>
</td>
</tr>

<tr>
<td>Product</td>
<td>
<?php 
$name = translateFromSerialize($prod_detail['prodet_name']);
echo $name." ".$prodet_shortDesc; 
?>  
</td>
</tr>

<tr>
<td>Price Per Month</td>
<td><?php echo 'AED '.$orderInvoice['price_per_month']; ?></td>
</tr>
</table>
</div>
<div class="forth_div">
<?php 
$box = $webClass->getBox('box18'); //footer text
echo translateFromSerialize($box['text']);
?>
</div>
</div>
</div>
</div>
</div>
</body>

<?php } return ob_get_clean(); ?>