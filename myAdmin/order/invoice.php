<?php
ob_start();
require_once("classes/invoice.php");
global $_e;
global $functions;
global $dbF;

$invoice = new invoice();
$invoice->update();

@$pId = $_POST['pId'];
if (empty($pId)) {
@$pId = $_GET['orderId']; // in case of future need just add this in url  &orderId={id}
}

$orderId = $pId;
$data = $invoice->invoiceDetail($orderId);

// $dbF->prnt($data);




$country_list = $functions->countrylist();

if (isset($_GET['apiData'])) {
$sql = "SELECT `api_return` FROM `invoices` WHERE `invoice_pk` = ?";
$res = $dbF->getRow($sql, array($_GET['apiData']));

$apiReturn = $res['api_return'];

echo "<pre>";
print_r(json_decode(base64_decode($apiReturn)));
echo "</pre>";
}

if (!empty($data['apiReturn'])) {
$viewApiReturnData = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&apiData'>" . $_e['View Api Return Info'] . "</a>";
} else {
$viewApiReturnData = '';
}
?>

<script src="<?php echo WEB_ADMIN_URL; ?>/order/js/jSignature.js"></script>
<script src="<?php echo WEB_ADMIN_URL; ?>/order/js/plugins/jSignature.CompressorSVG.js"></script>
<script src="<?php echo WEB_ADMIN_URL; ?>/order/js/plugins/jSignature.UndoButton.js"></script>

<style type="text/css">

#signatureparent {
/*padding:20px;
height: 200px !important;*/



}
#signature {
height: 152px !important;
width: 302px;

}

.jSignature{
all:unset;
}
/*#signatureparent {
color:darkblue;
background-color:darkgrey;
padding:20px;
}*/

/*This is the div within which the signature canvas is fitted*/
/*#signature {
border: 2px dotted black;
background-color:lightgrey;
}*/

/* Drawing the 'gripper' for touch-enabled devices */ 
/*html.touch #content {
float:left;
width:92%;
}
html.touch #scrollgrabber {
float:right;
width:4%;
margin-right:2%;
background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAFCAAAAACh79lDAAAAAXNSR0IArs4c6QAAABJJREFUCB1jmMmQxjCT4T/DfwAPLgOXlrt3IwAAAABJRU5ErkJggg==)
}
html.borderradius #scrollgrabber {
border-radius: 1em;
}*/
</style> 

<p style="position: absolute; font-size: 25px; right: 35px; top: 70px;"><span style="color: #e20b0b">Order No. : </span><?php echo $functions->ibms_setting('invoice_key_start_with').@$pId; ?></p>
<h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Invoice Detail View']); ?></h4><div id="asdasds">	</div>

<!-- sender detail -->
<div class="table-responsive newProduct col-sm-12">
<table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
cellspacing="0">
<thead>
<th colspan="7">
<div class="text-center"><?php echo _u($_e['CUSTOMER DETAILS']); ?></div>
</th>
</thead>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
<td><?php echo $data['company_name']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
<td><?php echo $data['address']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
<td><?php echo $functions->gcc_emirates($data['country']);?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
<td><?php echo $data['email']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
<td><?php echo $data['mobile']; ?></td>
</tr>
</table>
</div>
<!-- sender detail end -->

<!-- receiver detail -->
<!--  <div class="table-responsive newProduct col-sm-6" style="display: none;">
<table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
cellspacing="0">
<thead>
<th colspan="7">
<div class="text-center"><?php echo _u($_e['ORDER RECEIVER DETAIL']); ?></div>
</th>
</thead>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
<td><?php echo $data['fname'].' '.$data['lname']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
<td><?php echo $data['address']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
<td><?php echo $data['country']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
<td><?php echo $data['email']; ?></td>
</tr>
<tr>
<td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
<td><?php echo $data['mobile']; ?></td>
</tr>
</table>
</div> -->
<!-- receiver detail end -->


<div class="clearfix"></div>
<div class="padding-20"></div>

<!-- product detail -->
<form method="post">
<div class="table-responsive newProduct">
<table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
cellspacing="0">
<thead>
<th colspan="12">
<div class="text-center"><?php echo _u($_e['PRODUCT DETAILS']); ?></div>
</th>
</thead>
<tr class="gray-tr">
<th><?php echo _u($_e['SNO']); ?></th>
<th><?php echo _u($_e['PRODUCT NAME']); ?></th>
<th><?php echo _u($_e['BILLING MODE']); ?></th>
<th><?php echo _u($_e['MONTHLY PRICE']); ?></th>
<th><?php echo _u($_e['STATUS']); ?></th>
<th><?php echo _u($_e['TOTAL']); ?></th>
</tr>

<?php 
$status_options = $invoice->order_status();

$admin_order_status = '';

if($data['order_status'] == 'process' && $data['status'] == 'pending_inst' || $data['order_status'] == 'process' && $data['status'] == 'live' || $data['order_status'] == 'process' && $data['status'] == 'pending_remove'){
$admin_order_status = $status_options[$data['status']];
}else{
if(array_key_exists($data['order_status'], $status_options)){
$admin_order_status = ''.$status_options[$data['order_status']];
}else{
$admin_order_status = $data['order_status'];
}
}

if($data['del_request'] == 1){
$admin_order_status = 'Pending Removal';
}

$sql_total = "SELECT `price` FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'paid'";
$res_total = $dbF->getRows($sql_total, array($orderId));

$cumulative_total = 0;
foreach ($res_total as $key => $value) {
$cumulative_total += $value['price'];


}



//   if ($data['payment_mode'] == "quarterly") {

// $cumulative_total = $cumulative_total*3;

//     # code...
// }

$prod_detail = $functions->getProductName($data['product_id'], 'prodet_name');
$pname = translateFromSerialize($prod_detail['prodet_name']);



?>

<tr>
<td><?php echo '1'; ?></td>
<td><?php echo $pname; ?></td>
<td><?php echo _uc($data['payment_mode']); ?></td>
<td><?php echo 'AED '.$data['price_per_month']; ?></td>
<td><?php echo _uc($admin_order_status); ?></td>
<td><?php echo 'AED '.$cumulative_total; ?></td>
</tr>
</table>
</div>
<!-- product detail end -->

<div class="clearfix"></div>
<div class="padding-20"></div>


<!-- invoice detail -->

<input type="hidden" name="pId" value="<?php echo $orderId; ?>"/>
<?php $functions->setFormToken('Invoice'); ?>
<div class="table-responsive newProduct col-sm-12" id="rdata">
<table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
cellspacing="0">
<thead>
<th colspan="7">
<div class="text-center"><?php echo _u($_e['INVOICE DETAILS']); ?></div>
</th>
</thead>
<tbody>
<tr>
<th><?php echo _uc($_e['SNO']); ?></th>
<th><?php echo _uc($_e['Invoice ID']); ?></th>
<th><?php echo _uc($_e['Due Date']); ?></th>
<th><?php echo _uc($_e['Price']); ?></th>
<th><?php echo _uc($_e['Payment Status']); ?></th>
<th><?php echo _uc($_e['Transaction Reference']); ?></th>
<th><?php echo _uc($_e['Payment Type']); ?></th>
</tr>

<?php 

$sql = "SELECT * FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'paid' ORDER BY `due_date` ASC";
$res = $dbF->getRows($sql, array($orderId));

$last_paid_date = '';
$count = 0;
foreach ($res as $key => $value) {
$count++;

$inv_id = $value['invoice_pk'];

if(empty($value['api_return'])){
$view_api_info = '';
}else{
$view_api_info = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&apiData=$inv_id'>View Api Return Info</a>";
}

$last_paid_date = $value['due_date'];
$sqls = "SELECT `order_ref` FROM `orders` WHERE order_id='$value[order_id]'";
$datas= $dbF->getRow($sqls);
$chkk = $datas[0];
$pymt = 'Telr';
$dt = date('Y-m-d', strtotime("+1 months"));
if($chkk=='manual'){
	$pymt = 'Cash';
	if($value['invoice_status']=='pending' && $value['due_date']<=$dt){
	$pymt .="&nbsp;<button type='button' data-id='$value[invoice_pk]' class='btn btn-primary btn-sm'>Paid</button>";
	}
}
echo "<tr>
<td>".$count."</td>
<td>".$value['invoice_pk']."</td>
<td>".$value['due_date']."</td>
<td>".$value['price']."</td>
<td>".$value['invoice_status']."</td>
<td>".$value['trans_ref']."</td>
<td>
$last_paid_date 
$pymt 
$view_api_info
</td>
</tr>";
}

$next_due_date = date('Y-m-d', strtotime("+1 day $last_paid_date"));

$sql = "SELECT * FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending' AND `due_date` > ? ORDER BY `due_date` ASC LIMIT 1";
$res = $dbF->getRow($sql, array($orderId,$next_due_date));
$sqls = "SELECT `order_ref` FROM `orders` WHERE order_id='$orderId'";
$datas= $dbF->getRow($sqls);
$chkk = $datas[0];
$pymt2 = 'Telr';

if(!empty($res)):

$nextcount = $count+1;

$dt = date('Y-m-d', strtotime("+1 months"));
if($chkk=='manual'){
	$pymt2 = 'Cash';
	if($res['due_date']<=$dt){
	$pymt2 .="&nbsp;<button type='button' data-id='$res[invoice_pk]' class='btn-iupdate btn btn-primary btn-sm'>Paid</button>";
	}
}

echo "<tr>
<td>Next Payment</td>
<td>".$res['invoice_pk']."</td>
<td>".$res['due_date']."</td>
<td>".$res['price']."</td>
<td>".$res['invoice_status']."</td>
<td>".$res['trans_ref']."</td>
<td>$pymt2</td>
</tr>";

endif;

$sql = "SELECT * FROM `invoices` WHERE `order_id` = ? ORDER BY `due_date` DESC LIMIT 1";
$res = $dbF->getRow($sql, array($orderId));

$sql_count = "SELECT count(*) AS 'count' FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending'";
$res_count = $dbF->getRow($sql_count, array($orderId));
$sqls = "SELECT `order_ref` FROM `orders` WHERE order_id='$orderId'";
$datas= $dbF->getRow($sqls);
$chkk = $datas[0];
$pymt3 = 'Telr';
$dt = date('Y-m-d', strtotime("+1 months"));
if($chkk=='manual'){
	$pymt3 = 'Cash';
	if($res['due_date']<=$dt){
	$pymt3 .="&nbsp;<button type='button' data-id='$res[invoice_pk]' class='btn-iupdate btn btn-primary btn-sm'>Paid</button>";
	}
}

if(!empty($res)):
echo "<tr>
<td>Last Payment</td>
<td>".$res['invoice_pk']."</td>
<td>".$res['due_date']."</td>
<td>".$res['price']."</td>
<td>".$res['invoice_status']."</td>
<td>".$res['trans_ref']."</td>
<td>$pymt3</td>
</tr>";
endif;
?>
</tbody>
</table>
</div>

<div class="clearfix"></div>
<div class="padding-20"></div>

</form>

<div class="table-responsive newProduct">
<table class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
cellspacing="0">
<thead>
<th colspan="12">
<div class="text-center"><span><?php echo _u($_e['SCHEDULE']); ?></span>
<?php if($data['order_status'] == 'process'): ?> 
<a style="float: right; color: #fff;" data-toggle="modal" data-target="#schedule_form_modal"><i class="fa fa-plus-square fa-2x"></i></a>
<?php endif; ?>
</div>
</th>
</thead>
<tr class="gray-tr">
<th><?php echo _u($_e['SNO']); ?></th>
<th><?php echo _u($_e['SCHEDULE DATE']); ?></th>
<th><?php echo _u($_e['TIME SLOT']); ?></th>
<th><?php echo _u($_e['DETAIL']); ?></th>
<th><?php echo _u($_e['TECHNICAL FORM']); ?></th>
<th><?php echo _u($_e['ACTION']); ?></th>
</tr>

<?php 

$sql = "SELECT * FROM `schedule_form` WHERE `order_id` = ?";
$res = $dbF->getRows($sql, array($orderId));

$count=0;
foreach ($res as $key => $value) {
$count++;
$id = $value['schedule_id'];
$machines = json_decode($value['machines']);
$oils = json_decode($value['oils']);
$qty = json_decode($value['quantity']);

$detail = '';
for ($i=0; $i < sizeof($machines); $i++) {
$j = $i+1;
$machine = $invoice->get_product($machines[$i]);
$machine_name = translateFromSerialize($machine['prodet_name']); 

$oil = $invoice->get_product($oils[$i]); 
$oil_name = translateFromSerialize($oil['prodet_name']); 

$detail .= _uc($machine_name).' - '._uc($oil_name).'<br>';
}

$sql = "SELECT `technical_id`,`client_confirm` FROM `technical_form` WHERE `schedule_id` = ?";
$res = $dbF->getRow($sql, array($id));

if(empty($res)){
$add_technical = '<a class="button btn-primary btn-xs" data-id="'.$id.'" onclick="addTechincal('.$id.')" id="add_technical"><i class="fa fa-plus-square"></i> New Technical</a>';
$confirm_button = '';
$confirm_button_del = '<a data-id="'.$id.'" onclick="deleteScheduleForm(this);" class="btn">
<i class="glyphicon glyphicon-remove" style="color: red;
"></i>
<i class="fa fa-refresh waiting fa-spin" style="display: none"></i>
</a>';
$edit_button='<a data-id="'.$id.'" onclick="editScheduleForm(this)" class="btn">
<i class="glyphicon glyphicon-edit"></i>
</a>';
}else{
$tech_id = $res['technical_id'];
$confirm = $res['client_confirm'];
$add_technical = '<a class="button btn-primary btn-xs view_technical" data-id="'.$tech_id.'" ><i class="fa fa-eye"></i> View Technical</a>';

if($confirm == 0){
$confirm_button = '<a class="" style="cursor:pointer; text-decoration: underline; color:red"><i class="fa fa-check-circle"></i> Not Confirmed </a>';

$edit_button='<a data-id="'.$id.'" onclick="editScheduleForm(this)" class="btn">
<i class="glyphicon glyphicon-edit"></i>
</a>';

$confirm_button_del = '<a data-id="'.$id.'" onclick="deleteScheduleForm(this);" class="btn">
<i class="glyphicon glyphicon-remove" style="color: red;
"></i>
<i class="fa fa-refresh waiting fa-spin" style="display: none"></i>
</a>';



}else{
$confirm_button = '<a class="" style="cursor:pointer; text-decoration: underline; color: green"><i class="fa fa-check-circle"></i> Confirmed </a>';

$edit_button='';
$confirm_button_del = '';

}
}

echo '<tr>
<td>'.$count.'</td>
<td>'.$value['schedule_date'].'</td>
<td>'._uc($value['schedule_slot']).'</td>
<td>'.$detail.'</td>
<td>'.$add_technical.'<br><br>'.$confirm_button.'</td>
<td>
<div class="btn-group btn-group-sm">

'.$edit_button.'
'.$confirm_button_del.'
</div>
</td>
</tr>';

}


?>
</table>
</div>

<div class="clearfix"></div>
<br>

<a href="<?php echo WEB_URL; ?>/invoicePrint?mailId=<?php echo $orderId; ?>" target="_blank"
class="btn btn-info btn-lg"><?php echo _uc($_e['Print Out']); ?></a>

<?php if($data['order_status'] == 'process'): ?>       
<input type="button" id="cancel_agreement" data-id="<?php echo $data['agrement_id']; ?>" name="cancel_agreement" value="CANCEL AGREEMENT"
class="btn btn-danger btn-lg">
<?php endif; ?>

<div class="padding-20"></div>

<?php 
$machines = $invoice->getSpecialProducts('1003');
$machine_option = '';

$product_stock_array = array();

foreach ($machines as $key => $value) {
$pId = $value['prodet_id'];
$stock = $invoice->product_quantity($pId);
$product_stock_array[$pId] = $stock;

$stock_qty = $invoice->checkStock($pId);

// if($stock_qty > 0){

$pData = $invoice->productData($pId);

$pName = translateFromSerialize($pData['prodet_name']);

$machine_option .= '<option value="'.$pId.'" data-id="'.$stock_qty.'">'.$pName.'</option>';
// }

}

$oils = $invoice->getSpecialProducts('1004');
$oil_option = '';

foreach ($oils as $key => $value) {
$pId = $value['prodet_id'];

$stock = $invoice->product_quantity($pId);
$product_stock_array[$pId] = $stock;

$stock_qty = $invoice->checkStock($pId);

// if($stock_qty > 0){

$pData = $invoice->productData($pId);

$pName = translateFromSerialize($pData['prodet_name']);

$oil_option .= '<option value="'.$pId.'">'.$pName.'</option>';
// }
}

$product_stock_array = json_encode($product_stock_array);

$sqlis ="SELECT *
FROM schedule_form
WHERE order_id = ? and order_id IN
(SELECT order_id 
FROM technical_form WHERE client_confirm = 1 and order_id = ?) order by schedule_id desc limit 1";



// $sqlis = "SELECT * FROM `schedule_form` WHERE `order_id` = ?";
$resAll = $dbF->getRow($sqlis, array($orderId,$orderId));

// var_dump($resAll."5555555555555555555555");
?>


<!----------------- SCHEDULE FORM ------------------>
<div class="modal fade" id="schedule_form_modal" tabindex="-1" role="dialog" aria-labelledby="schedule_form_modalTitle" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h3 class="modal-title" id="schedule_form_modalTitle">Schedule Form</h3>
</div>
<div class="modal-body">
<form class="form-horizontal schedule_form" role="form">
<input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
<input type="hidden" name="product_id" value="<?php echo $data['product_id']; ?>">
<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">Date</label>
<div class="col-sm-10  col-md-9">
<input type="text" value="" name="schedule_date" id="schedule_date" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">Time Slot</label>
<div class="col-sm-10  col-md-9">
<select name="schedule_slot" id="schedule_slot" class="form-control" required>


<option selected="" disabled="">Select Time Slot</option>


</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">Service Type</label>
<div class="col-sm-10  col-md-9">
<select name="sevice_type" id="sevice_type" class="form-control" required>
<option selected disabled>Select Service Type</option>
<option value="instal">Installation</option>
<option value="maintain">Oil Refill/Maintenance</option>
<option value="incident">Incident</option>
</select>
</div>
</div>

<div class="multiple_machines">


<?php 
// echo $sq ="SELECT *
// FROM schedule_form
// WHERE order_id = ? and order_id IN
// (SELECT order_id 
// FROM technical_form WHERE order_id = ?) order by schedule_id desc limit 1";

echo $sq ="SELECT *
FROM schedule_form
WHERE order_id = ?";



$dataAll = $dbF->getRows($sq, array($orderId));
	
	if ($dbF->rowCount > 0) {

			// var_dump("expressionaaaaaaaaaaaaaaaaaaaaaaa");
		$count = 0;
foreach ($dataAll as $val) {

	if ($val['machines'] == "null") {
?>
<div class="form-group">
<label class="col-sm-2 col-md-2  control-label">Machines</label>
<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_machine[]" data-id="0" class="form-control schedule_machine">
<option selected disabled>Select Machine</option>
<?php echo $machine_option; ?>
</select>
</div>
<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_oil[]" data-id="0" class="form-control" onchange="checkStock(this)">
<option selected disabled>Select Oil</option>
<?php echo $oil_option; ?>
</select>
</div>
<div class="col-sm-2  col-md-2" style="padding-left: 0;">
<input type="number" id="oilQty0" value="" min="0" name="oil_quantity[]" class="form-control oil_qty" placeholder="Oil Qty">
</div>
<div class="col-sm-2 col-md-2" id="newOpt0" style="padding-left: 0;">
<input type="checkbox" name="new_machine[]">New
</div>
</div>
<?php

	}

	$machines = json_decode($val['machines']);
	$oils = json_decode($val['oils']);

	for ($i=0; $i < sizeof($machines); $i++) {



	$machine = $invoice->get_product($machines[$i]);
	$oil = $invoice->get_product($oils[$i]);
	$machine_name = translateFromSerialize($machine['prodet_name']); 
	$oil_name = translateFromSerialize($oil['prodet_name']); 
    $pId = $machine['prodet_id'];
    $pIdoil = $oil['prodet_id'];
$stock_qty = $invoice->checkStock($pId);
$machine_option1 = '<option selected value="'.$pId.'" data-id="'.$stock_qty.'">'.$machine_name.'</option>';
$oil_option1 = '<option selected value="'.$pIdoil.'">'.$oil_name.'</option>';

?>


<div class="form-group">
<label class="col-sm-2 col-md-2  control-label">Machines</label>
<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_machine[]" data-id="0" class="form-control schedule_machine">
<!-- <option selected disabled>Select Machine</option> -->
<?php echo $machine_option1; ?>
</select>
</div>
<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_oil[]" data-id="0" class="form-control" onchange="checkStock(this)">
<!-- <option selected disabled>Select Oil</option> -->
<?php echo $oil_option1; ?>
</select>
</div>


<div class="col-sm-2  col-md-2" style="padding-left: 0;">
<input type="number" id="oilQty0" value="" min="0" name="oil_quantity[]" class="form-control oil_qty" placeholder="Oil Qty">
</div>
<div class="col-sm-2 col-md-2" id="newOpt0" style="padding-left: 0;">
<input type="checkbox" name="new_machine[]">New
</div>
</div>

<?php 

	$count ++;

}
}}else{

	// var_dump("expression");
?>
<div class="form-group">
<label class="col-sm-2 col-md-2  control-label">Machines</label>
<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_machine[]" data-id="0" class="form-control schedule_machine">
<option selected disabled>Select Machine</option>
<?php echo $machine_option; ?>
</select>
</div>
<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_oil[]" data-id="0" class="form-control" onchange="checkStock(this)">
<option selected disabled>Select Oil</option>
<?php echo $oil_option; ?>
</select>
</div>
<div class="col-sm-2  col-md-2" style="padding-left: 0;">
<input type="number" id="oilQty0" value="" min="0" name="oil_quantity[]" class="form-control oil_qty" placeholder="Oil Qty">
</div>
<div class="col-sm-2 col-md-2" id="newOpt0" style="padding-left: 0;">
<input type="checkbox" name="new_machine[]">New
</div>
</div>

<?php

} ?>


<!-- <div class="form-group">
<label class="col-sm-2 col-md-2  control-label">Machines</label>

<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_machine[]" data-id="0" class="form-control schedule_machine">
<option selected disabled>Select Machine</option>
<?php echo $machine_option; ?>
</select>
</div>

<div class="col-sm-3 col-md-3" style="padding: 0;">
<select name="schedule_oil[]" data-id="0" class="form-control" onchange="checkStock(this)">
<option selected disabled>Select Oil</option>
<?php echo $oil_option; ?>
</select>
</div>

<div class="col-sm-2  col-md-2" style="padding-left: 0;">
<input type="number" id="oilQty0" value="" min="0" name="oil_quantity[]" class="form-control oil_qty" placeholder="Oil Qty">
</div>

<div class="col-sm-2 col-md-2" id="newOpt0" style="padding-left: 0;">
<input type="checkbox" name="new_machine[]">New
</div>
</div> -->



</div>

<div class="form-group">
<div class="col-sm-12 col-md-12">
<a style="float: right;" class="button btn-primary btn-xs" data-id="0" id="add_machines"><i class="fa fa-plus-square"></i> Add </a>
<!-- <a style="float: right; color: #000;"><i class="fa fa-plus-square fa-2x"></i></a> -->
</div>
</div>
</form>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary" id="submitSchedule">Save changes</button>
</div>
</div>
</div>
</div>


<div id="technical_form"></div>


<!-- EDIT SCHEDULE FORM -->

<div class="modal fade" id="editSchedule_form_modal" tabindex="-1" role="dialog" aria-labelledby="editSchedule_form_modalTitle" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h3 class="modal-title" id="editSchedule_form_modalTitle">Edit Schedule Form</h3>
</div>
<div class="modal-body" id="editScheduleModal">

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary" id="submitEditSchedule">Save changes</button>
</div>
</div>
</div>
</div>    

<!-- EDIT SCHEDULE FORM END-->


<div class="clearfix"></div>

<br>

<script type="text/javascript">
$(document).ready(function(){
$(".datepicker").datepicker({
minDate: 0,
dateFormat: 'yy-mm-dd'
});
});

$('#add_machines').on('click', function(){

var cur_index = $(this).data('id');
var next_index = parseInt(cur_index+1);
var machines = '<?php echo $machine_option; ?>';
var oils = '<?php echo $oil_option; ?>';

var format = '<div class="form-group">'+
'<label class="col-sm-2 col-md-2  control-label"></label>'+

'<div class="col-sm-3 col-md-3" style="padding: 0;">'+
'<select name="schedule_machine[]" class="form-control schedule_machine">'+
'<option selected disabled>Select Machine</option>'+machines+
'</select>'+
'</div>'+

'<div class="col-sm-3 col-md-3" style="padding: 0;">'+
'<select name="schedule_oil[]" data-id="'+next_index+'"  class="form-control"  onchange="checkStock(this)">'+
'<option selected disabled>Select Oil</option>'+oils+
'</select>'+
'</div>'+

'<div class="col-sm-2  col-md-2" style="padding-left: 0;">'+
'<input type="number" value="" min="0" id="oilQty'+next_index+'" name="oil_quantity[]" class="form-control oil_qty" placeholder="Oil Qty">'+
'</div>'+

'<div class="col-sm-2 col-md-2" id="newOpt'+next_index+'" style="padding-left: 0;">'+
'<input type="checkbox" name="new_machine[]">New'+
'</div>'+
'</div>';

$('.multiple_machines').append(format);
});

$('.schedule_machine').on('change', function(){
index = $(this).data('id');
stock = $('.schedule_machine option:selected').attr('data-id');

if(stock > 0){
$('#newOpt'+index).show();
}else{
$('#newOpt'+index).hide();
}


});

$('#submitSchedule').on('click', function(){
var form = $('.schedule_form').serialize();
var proceed = true;

schedule_slot 	= $('#schedule_slot').val();
sevice_type 	= $('#sevice_type').val();

cust_error = '';

if($('#schedule_date').val() == ''){
proceed = false;
console.log('schedule_date');
cust_error += 'Schedule Date Required<br>';
}

if(schedule_slot == null){
proceed = false;
console.log('schedule_slot');
cust_error += 'Schedule Slot Required<br>';
}

if(sevice_type == null){
proceed = false;
console.log('service type');
cust_error += 'Service Type Required<br>';
}

$('.oil_qty').each(function(index, el) {
input_val = parseInt($(this).val());
max_val = parseInt($(this).attr('max'));

if(input_val > max_val || input_val < 0){
$(this).css('border-color', 'red');
proceed = false;
cust_error += 'Oil Quantity Exceeds the stock, Please Enter Correct Value<br>';
// return false;
}
});

console.log(cust_error);
console.log(proceed);

if(!proceed){
jAlertifyAlert(cust_error);
}

if(proceed){

$.ajax({
url: 'order/order_ajax.php?page=submitSceduleForm',
type: 'post',
data: form
}).done(function(res){
console.log(res);
if(res == '1'){
$('#schedule_form_modal').modal('hide');
jAlertifyAlert('Schedule Form Submitted Successfully!');

var formBody = '<form action="?page=edit" method="POST">';
formBody += '<input type="hidden" name="pId" value="<?php echo $orderId ?>" />';


formBody += '</form>';

var $form = $(formBody).appendTo('#asdasds');
// console.log($form);

$form.submit();



}else if(res == '0'){
jAlertifyAlert('Something Went Wrong! Please Try Again');
}
});
}
});

function checkStock(ths){
var stock_array = '<?php echo $product_stock_array; ?>';
stock_array = JSON.parse(stock_array);

var id = $(ths).data('id');
var val = $(ths).val();

$('#oilQty'+id).attr('max',stock_array[val]);

}

$('#schedule_date').on('change', function(){
chosen_date = $(this).val();

$.ajax({
url: 'order/order_ajax.php?page=getAvailableSlots',
type: 'post',
data: {chosen_date:chosen_date}
}).done(function(res){
$('#schedule_slot').html(res);
});
});

function addTechincal(sched_id){
order_id = '<?php echo $orderId; ?>';

$.ajax({
url: 'order/order_ajax.php?page=openTechnicalForm',
type: 'post',
data: {sched_id:sched_id, order_id:order_id}
}).done(function(res){
$('#technical_form').html(res);
$('#technicalFormModal').modal('show');
});
}

// $(document).ready(function(){
$('.view_technical').on('click', function(){
tech_id = $(this).data('id');

$.ajax({
url: 'order/order_ajax.php?page=viewTechnicalForm',
type: 'post',
data: {tech_id:tech_id}
}).done(function(res){
$('#technical_form').html(res);
$('#technicalFormModal').modal('show');
});
});
// });



function availableSlots(date){
chosen_date = date;

$.ajax({
url: 'order/order_ajax.php?page=getAvailableSlots',
type: 'post',
data: {chosen_date:chosen_date}
}).done(function(res){
$('#schedule_slot').html(res);
});
}

$('#cancel_agreement').on('click', function(){
if(secure_delete('Are You Sure You Want to Cancel Agreement?')){
console.log('Submit Clicked');
agreement_id = $(this).data('id');
order_id = '<?php echo $orderId; ?>';

$.ajax({
url: 'order/order_ajax.php?page=cancelAgreement',
type: 'post',
data: {agreement_id:agreement_id, order_id:order_id}
}).done(function(res){
if(res == '1'){
jAlertifyAlert('Order Cancelled Successfully.');
}else if(res == '0'){
jAlertifyAlert('Something Wrong Happened! Please Try Again.');
}
});
}

});




deleteScheduleForm = function(ths){
btn=$(ths);
if(secure_delete()){
btn.addClass('disabled');
btn.children('.trash').hide();
btn.children('.waiting').show();

id=btn.attr('data-id');
$.ajax({
type: 'POST',
url: 'order/order_ajax.php?page=deldeleteScheduleForm&id='+id,
data: { itemId:id }
}).done(function(data)
{
ift =true;
if(data=='1'){
ift = false;
btn.closest('tr').hide(1000,function(){$(this).remove()});
}
else if(data=='0'){
alert('<?php echo _js($_e['Delete Fail Please Try Again.']); ?>');
}
else{
jAlert(data,'Error');
}
if(ift){
btn.removeClass('disabled');
btn.children('.trash').show();
btn.children('.waiting').hide();
}
});
}
};


function editScheduleForm(ths){
id = $(ths).data('id');

$.ajax({
url: 'order/order_ajax.php?page=editSchedule',
type: 'post',
data: {id:id}
}).done(function(res){
$('#editScheduleModal').html(res);
$(".datepicker").datepicker({
minDate: 0,
dateFormat: 'yy-mm-dd'
});
$('#editSchedule_form_modal').modal('show');
});
}

$('#submitEditSchedule').on('click', function(){
edit_form = $('#editSchedule_form').serialize();

$.ajax({
url: 'order/order_ajax.php?page=submitEditSchedule',
type: 'post',
data: edit_form
}).done(function(res){
if(res == '1'){
jAlertifyAlert('Schedule Updated Successfully.');
}else if(res == '0'){
jAlertifyAlert('Something Wrong Happened! Please Try Again.');
}
});
});

//mycode
$('.btn-iupdate').on('click', function(){
var id = $(this).attr('data-id');
if(secure_delete('Are You Sure You Want to Update')){
$.ajax({
url: 'order/order_ajax.php?page=updateInvoice',
type: 'post',
data: {id:id}
}).done(function(){
var posting = $.post( "-order?page=edit", { pId: <?php echo $_POST['pId']; ?> } );
  posting.done(function( data ) {
    var content = $( data ).find( "#rdata" );
    $( "#rdata" ).html( content );
  });

});
}

});

//mycode

</script>

<?php 

if(isset($_GET['tech']) && !empty($_GET['tech'])){
$sche_id = intval($_GET['tech']);

echo '<script>addTechincal('.$sche_id.')</script>';

}

?>

<style type="text/css">
#schedule_form_modalTitle{
text-align: center;
}

#technicalFormTitle{
text-align: center;
}

.the-legend {
border-style: none;
border-width: 0;
font-size: 14px;
line-height: 20px;
margin-bottom: 0;
width: auto;
padding: 0 10px;
border: 1px solid #e0e0e0;
}
.the-fieldset {
border: 1px solid #e0e0e0;
padding: 10px;
}

.marginBot{
margin-bottom: 10px;
}
</style>

<?php return ob_get_clean(); ?>