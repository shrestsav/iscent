<?php

class order extends object_class{

public $productF;



public function __construct(){

parent::__construct('3');



if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];

else {

require_once(__DIR__."/../../product_management/functions/product_function.php");

$this->productF=new product_function();

}



/**

* MultiLanguage keys Use where echo;

* define this class words and where this class will call

* and define words of file where this class will called

**/

global $_e;

global $adminPanelLanguage;

$_w=array();

//newOrder.php

$_w['Add New Order'] = '' ;

$_w['InComplete Orders'] = '' ;

$_w['All Orders'] = '' ;

$_w['Complete Orders'] = '' ;

$_w['Cancel Orders'] = '' ;

$_w['InProcess Invoices'] = '' ;

$_w['Order Create/View'] = '' ;



//New order form Function

$_w['Store Country'] = '' ;

$_w['Select Country'] = '' ;

$_w['User'] = '' ;

$_w['Select User'] = '' ;

$_w['No User'] = '' ;

$_w['Invoice Status'] = '' ;

$_w['Payment Type'] = '' ;

$_w['Payment Info'] = '' ;

$_w['Enter Vendor Payment Information'] = '' ;

$_w['PRODUCT SCALE'] = '' ;

$_w['PRODUCT COLOR'] = '' ;

$_w['STORE'] = '' ;

$_w['QUANTITY'] = '' ;

$_w['PRICE'] = '' ;

$_w['Select Product Name'] = '' ;

$_w['Select Scale'] = '' ;

$_w['Select Color'] = '' ;

$_w['Select Store'] = '' ;

$_w['Product QTY'] = '' ;

$_w['Single Price'] = '' ;

$_w['Product Discount'] = '' ;

$_w['Add Product'] = '' ;

$_w['Remove Checked Items'] = '' ;

$_w['Check/Uncheck All'] = '' ;

$_w['NO'] = '' ;

$_w['PRODUCT'] = '' ;

$_w['WEIGHT'] = '' ;

$_w['QTY'] = '' ;

$_w['(QTY*PRICE) - DISCOUNT = TOTAL PRICE'] = '' ;

$_w['DISCOUNT'] = '' ;

$_w['TOTAL WEIGHT'] = '' ;

$_w['TOTAL PRICE'] = '' ;

$_w['Sender And Receiver Information'] = '' ;

$_w['I am sender And Receiver'] = '' ;

$_w['I am Sender And Friend Is receiver'] = '' ;

$_w['Sender Information'] = '' ;

$_w['Sender Name'] = '' ;

$_w['Sender Phone'] = '' ;

$_w['Sender Email'] = '' ;

$_w['Sender City'] = '' ;

$_w['Sender Country'] = '' ;

$_w['Country'] = '' ;

$_w['Sender Post Code'] = '' ;

$_w['Sender Address'] = '' ;

$_w['Receiver Information'] = '' ;

$_w['Receiver Name'] = '' ;

$_w['Receiver Phone'] = '' ;

$_w['Receiver Email'] = '' ;

$_w['Receiver City'] = '' ;

$_w['Receiver Country'] = '' ;

$_w['Receiver Post Code'] = '' ;

$_w['Receiver Address'] = '' ;

$_w['Last Order View'] = '' ;

$_w['ORDER'] = '' ;

$_w['Order  Price'] = '' ;

$_w['Shipping Price'] = '' ;

$_w['Total'] = '' ;

$_w['ORDER AND PROCESS'] = '' ;

$_w['Selected Products'] = '' ;



//Add new order function

$_w['Order QTY is Greater Than stock Quantity'] = '' ;

$_w['Shipping Error'] = '' ;

$_w['Some thing went wrong Please try again'] = '' ;

$_w['Product Submit Fail'] = '' ;

$_w['Product Submit'] = '' ;

$_w['Product Submit Failed'] = '' ;

$_w['New Order Added Successfully'] = '' ;

$_w['New Order'] = '' ;

$_w['Product Successfully Submit'] = '' ;

$_w['Thank you your product is successfully submit'] = '' ;



//Order view function

$_w['SNO'] = '' ;

$_w['INVOICE'] = '' ;

$_w['CUSTOMER NAME'] = '' ;

$_w['INVOICE DATE'] = '' ;

$_w['SOLD PRICE'] = '' ;

$_w['PAYMENT METHOD'] = '' ;

$_w['ORDER PROCESS'] = '' ;

$_w['ACTION'] = '' ;

$_w['Yes'] = '' ;

$_w['PURCHASE PRICE'] = '' ;

$_w['VIEW ORDER'] = '' ;

$_w['Delete All Old Incomplete Orders'] = '' ;

$_w['Search By Date Range'] = '' ;

$_w['Date To'] = '' ;

$_w['Date From'] = '' ;

$_w['Selected SubTotal'] = '' ;

$_w['ORDER ID'] = '' ;

$_w['CUSTOMER NAME'] = '' ;

$_w['ORDER DATE'] = '' ;

$_w['ORDER EXPIRY'] = '' ;

$_w['PAYMENT MODE'] = '' ;

$_w['Cancel Requests'] = '' ;

$_w['ORDER STATUS'] = '' ;

$_w['Orders Cancel Requests'] = '' ;

$_w['SCHEDULE DATE'] = '' ;

$_w['SCHEDULE SLOT'] = '' ;

$_w['TECHNICAL FORM'] = '' ;

$_w['Pending Schedules'] = '' ;

$_w['TIME SLOT'] = '' ;

$_w['DETAIL'] = '' ;

$_w['SUBSCRIPTION PLAN'] = '' ;

$_w['NEXT BILLING DATE'] = '' ;

$_w['Order Placed'] = '' ;

$_w['Pending Installation'] = '' ;

$_w['Live'] = '' ;

$_w['Pending Removal'] = '' ;

$_w['Order Placed'] = '' ;

$_w['Your Order Placed Successfully'] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Order');



}





public function deleteOrders($type){

$days    = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');

$minusDays  =   date('Y-m-d',strtotime("-$days days"));



try {

$this->db->beginTransaction();



$sql = "SELECT order_pIds FROM `order_invoice_product` WHERE  dateTime <= '$minusDays' AND orderStatus = 'inComplete'";

$oldData = $this->dbF->getRows($sql);

foreach ($oldData as $val) {

$orderId = $val['order_invoice_id'];

$pIds = $val['order_pIds'];

$pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;

$pId = $pArray[0]; // 491

$scaleId = $pArray[1]; // 426

$colorId = $pArray[2]; // 435

$storeId = $pArray[3]; // 5

@$customId = $pArray[4]; // 5



//delete custom if has

if ($customId != '0' && !empty($customId)) {

$sql = "DELETE FROM p_custom_submit WHERE id = '$customId'";

$this->dbF->setRow($sql);

}



$sql = "DELETE FROM order_invoice WHERE order_invoice_pk = '$orderId' '";

$this->dbF->setRow($sql);

}

$this->db->commit();

}catch (Exception $e){

$this->db->rollBack();

}

$this->deleteCartOld();

}



public function deleteCartOld(){

//delete Old Cart and custom From table...

$date = date('Y-m-d',strtotime("-30 days"));

$sql = "DELETE FROM p_custom_submit WHERE dateTime <= '$date' AND id in (SELECT customId FROM cart WHERE dateTime <= '$date')";

$this->dbF->setRow($sql);



$sql = "DELETE FROM cart WHERE dateTime <= '$date'";

$this->dbF->setRow($sql);

}







/**

*  Simple Form For New Order

*/

function products(){
	$name='';
	$sql = "SELECT * FROM `proudct_detail` WHERE `prodet_id` IN 
			(SELECT `procat_prodet_id` FROM `product_category`
			 WHERE `procat_cat_id` LIKE '%1002%')";

	$data = $this->dbF->getRows($sql);
	foreach ($data as $key => $value) {
		$pid = $value['prodet_id'];
		$nm = translateFromSerialize($value['prodet_name']);
		$sdec = translateFromSerialize($value['prodet_shortDesc']);
		$valid = translateFromSerialize($value['validity']);
		$pm = translateFromSerialize($value['payment_mode']);

		$sql = "SELECT `propri_price` FROM `product_price` WHERE propri_prodet_id='$pid'";
		$data = $this->dbF->getRow($sql);
		$price=$data[0];

		$name  .= "<li><h4>$nm</h4><h5>$sdec</h5>
		<input type='hidden' value='$pid' class='h_pid'>
		<input type='hidden' value='$valid' class='h_validity'>
		<input type='hidden' value='$pm' class='h_pm'>
		<input type='hidden' value='$price' class='h_price'>
		</li>";
	}
	return $name;
}

public function newOrderForm(){

global $_e;

$this->functions->require_once_custom('store');

$storeC   = new store();


$paymentSelectOption = $this->productF->paymentSelect();

$invoiceStatus = $this->productF->invoiceStatus();

$country_list = $this->functions->countrySelectOption();

$storeList      =    $storeC->storeNamesCountryValueOption();

$token       = $this->functions->setFormToken('orderAdd',false);





//user list

$this->functions->require_once_custom('webUsers.class');

$userC = new webUsers();

$usersOption = $userC->userSelectOptionList();



echo '

<form method="post" class="form-horizontal" role="form">

'.$token.'

<input type="hidden" name="s_valid" class="s_valid" value="">
<input type="hidden" name="s_price" class="s_price" value="">
<input type="hidden" name="s_payment" class="s_payment" value="">
<input type="hidden" name="s_productid" class="s_productid" value="">
<input type="hidden" name="fname" value="">
<input type="hidden" name="lname" value="">

<div class="form-horizontal">

<div class="col-md-8">


<!--------------------------my Code-------------------------->


<div class="form-group">
<label for="company_name" class="col-sm-2 col-md-3 control-label">Company Name / Full Name</label>
<div class="col-sm-10 col-md-9">
<input type="text" name="company_name" class="form-control" required id="company_name" placeholder="Company Name / Full Name">
</div>

</div>

<div class="form-group">
<label for="mobile" class="col-sm-2 col-md-3 control-label">Mobile No</label>
<div class="col-sm-10 col-md-9">
<input type="text" name="mobile" class="form-control" required id="mobile" placeholder="Mobile No">
</div>
</div>

<div class="form-group">
<label for="address" class="col-sm-2 col-md-3 control-label">Address</label>
<div class="col-sm-10 col-md-9">
<input type="text" name="address" class="form-control" required id="address" placeholder="Address">
</div>
</div>

<div class="form-group">
<label for="country" class="col-sm-2 col-md-3 control-label">Select City</label>
<div class="col-sm-10 col-md-9">
<select class="form-control" name="country" required>
<option value="" selected="selected">Select Emirate</option>
<option value="AB">Abu Dhabi</option>
<option value="AJ">Ajman</option>
<option value="SH">Sharjah</option>
<option value="DU">Dubai</option>
<option value="FU">Fujairah</option>
<option value="RA">Ras Al Khaimah</option>
<option value="UM">Umm Al Quwain</option>
</select>
</div>
</div>

<div class="form-group">
<label for="email" class="col-sm-2 col-md-3 control-label">Email Address</label>
<div class="col-sm-10 col-md-9">
<input type="text" name="email" class="form-control" required id="email" placeholder="Email Address">
</div>
</div>



<div class="form-group">
<label for="product" class="col-sm-2 col-md-3 control-label">Select Product</label>
<div class="col-sm-10 col-md-9">
<div class="form-control" id="SelectProduct">
<span>Select Product</span>
<ul>
'.$this->products().'
</ul>	
</div>
</div>
</div>



<!----------------------------my Code---------------------------->


<div class="clearfix"></div>

<br>

<div class="container-fluid ReviewButtons">

<button type="submit" name="submit" onclick="return chk()" value="ORDER" class="submit btn btn-primary btn-lg">'. _u($_e['ORDER']) .'</button>

</div>

<div class="clearfix"></div>

<br/>';



$viewBody ='

<div class="FinalPriceReport">

<div class="h4"> '. _uc($_e['Order  Price']) .' : <span class="totalPriceModel bold"></span></div>

<div class="h4"> '. _uc($_e['Shipping Price']) .' : <span class="totalPriceShipping bold"></span></div>

<div class="h4"> '. _uc($_e['Total']) .' : <span class="totalFinal bold"></span></div>

</div>

<br>

<div id="submitButtons">

<button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER" class="submit btn btn-primary">'. _uc($_e['ORDER']) .'</button>

<button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER AND PROCESS" class="submit btn btn-primary">'. _uc($_e['ORDER AND PROCESS']) .'</button>

</div>

<br>';



$this->functions->customDialogView('Check Out',$viewBody,'Close');



echo '

<!-- if you change value of button then must change from addNewOrder();

</div> <!-- added product script div end -->



</div> <!-- form-horizontal end -->

</form>

</div>

<div class="container-fluid lastReview displaynone">

<div class="reportReview">

<div class="form-horizontal">

<div class="col-md-6">



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Store Country']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportStoreCountry">View</div>

</div>

</div>



<div class="form-group">

<label  class="col-sm-4 col-md-5">'. _uc($_e['Payment Type']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportPaymentType">View</div>

</div>

</div>

</div><!-- col-md-6 end -->

<div class="col-md-6">

<div class="form-group">

<label  class="col-sm-4 col-md-5">'. _uc($_e['Invoice Status']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportInvoiceStatus">View</div>

</div>

</div>



<div class="form-group">

<label  class="col-sm-4 col-md-5">'. _uc($_e['Payment Info']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportPaymentInfo">View</div>

</div>

</div>



</div><!-- col-md-6 end -->

</div><!-- Form horizontal 1 end-->

<hr>

<h4>'. _uc($_e['Selected Products']) .'</h4>

<div class="col-sm-12" id="reportSelectedProduct"></div>

<hr>

<h4>'. _uc($_e['Sender And Receiver Information']) .'</h4>



<div class="form-horizontal">

<div class="col-md-6">



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Sender Name']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportSenderName">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Sender Phone']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportSenderPhone">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Sender Email']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportSenderEmail">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Sender City']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportSenderCity">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Sender Country']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportSenderCountry">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Sender Address']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportSenderAddress">View</div>

</div>

</div>



</div><!-- col-sm-6 end 1-->



<div class="col-md-6">



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Name']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportReceiverName">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Phone']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportReceiverPhone">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Email']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportReceiverEmail">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Receiver City']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportReceiverCity">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Country']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportReceiverCountry">View</div>

</div>

</div>



<div class="form-group">

<label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Address']) .'</label>

<div class="col-sm-8 col-md-7">

<div id="reportReceiverAddress">View</div>

</div>

</div>



</div> <!--col sm 6 end 2-->



</div><!-- Form horizontal 2 end-->

</div>

</div><!-- Last review Info-->';





}







// public function addNewOrder(){

// global $_e;

// if(!$this->functions->getFormToken('orderAdd')){ return false;}

// $btn1   =   'ORDER';

// $btn2   =   'ORDER AND PROCESS';

// //set Submit buttons value here

// if(isset($_POST) && !empty($_POST) && !empty($_POST['cart_list']) && !empty($_POST['receiver_name']) && !empty($_POST['receiver_country'])  ){





// try{

// $this->db->beginTransaction();

// if($_POST['submit'] == $btn1){

// $process    =   0;

// }else if($_POST['submit'] == $btn2){

// $process    =   1; //submit product quantity from inventory

// }else{

// throw new Exception("");

// }



// $invoiceId  =   '';

// @$paymentType    =   $_POST['paymentType']; //int

// @$payment_info   =   $_POST['paymentInfo']; //text

// @$invoiceStatus  =   $_POST['invoiceStatus']; // varchar

// @$total_price    =   $_POST['totalPrice']; //Using In Security, If price from web form or php calculated not match, mean Hacking Attempt

// @$price_code     =   $_POST['priceCode'];

// @$country        =   $_POST['storeCountry'];

// @$userId        =   $_POST['userId'];

// @$totalWeightReceiveFromForm = $_POST['totalWeight']; //Using In Security, If Weight from web form or php calculated not match, mean Hacking Attempt

// $total_priceNew  =   0; //Calculateing in foreach loop, test with $total_price after loop, If not match its hacking attempt

// $total_weightNew =  0;//Calculateing in foreach loop, test with $totalWeightReceiveFromForm after loop, If not match its hacking attempt





// $countryData = $this->productF->productCountryId($country);

// $countryId   =  $countryData['cur_id'];



// //major data submit here, will later here, update this table

// $now = date('Y-m-d H:i:s');

// $sql = "INSERT INTO `order_invoice`

// (

// `paymentType`,

// `invoice_date`,

// `orderUser`,

// `payment_info`,

// `price_code`,

// `invoice_status`

// )

// VALUES (

// ?,?,?,?,?,?

// )";

// $array=array($paymentType,$now,$userId,$payment_info,$price_code,$invoiceStatus);

// $this->dbF->setRow($sql,$array,false);

// $invoiceId=$this->dbF->rowLastId;

// // invoice first data Enter



// //Invoice Product add

// foreach($_POST['cart_list'] as $key=>$id){

// $pArray     =   explode("_",$id); //p_491-246-435-5    => p_ pid - scaleId - colorId - storeId;

// $pIds       =   $pArray[1];

// $pArray     =   explode("-",$pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;

// $pId        =   $pArray[0]; // 491

// $scaleId    =   $pArray[1]; // 426

// $colorId    =   $pArray[2]; // 435

// $storeId    =   $pArray[3]; // 5

// @$customId    =   $pArray[4]; // 5





// $pName      =   $this->productF->getProductFullName($pId,$scaleId,$colorId);

// $storeName  =   $this->productF->getStoreName($storeId);

// $pPrice     =   $this->productF->productTotalPrice($pId,$scaleId,$colorId,$customId,$country);







// //price calculation

// $salePrice  =   $_POST['pTotalprice_'.$id];



// /*$discountArray = $this->productF->productDiscount($pId,$countryId);

// if(!empty($discountArray)){

// $discount       =   $discountArray['discount'];

// $discountFormat =   $discountArray['discountFormat'];

// if($discountFormat=='price'){

// $discount   =   $pPrice-$discount;

// }else if($discountFormat=='percent'){

// $discount   =   ($pPrice*$discount)/100;

// }

// }else{

// $discount   = 0;

// }*/



// $discount   =   floatval($_POST['pDiscount_'.$id]);

// $total_priceNew += floatval($salePrice);

// $saleQTY    =   $_POST['pQty_'.$id];

// $salePrice  =   ($salePrice+$discount)/$saleQTY; // get single product QTY price





// //Weight Calculation

// $weight     =   $this->productF->getProductWeight($pId,$scaleId,$colorId);

// $total_weightNew += $weight*$saleQTY;



// @$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;

// $hash       =   md5($hashVal);



// $sql    =   "INSERT INTO `order_invoice_product`

// (

// `order_invoice_id`,

// `order_pIds`,

// `order_pName`,

// `order_pStore`,

// `order_pPrice`,

// `order_salePrice`,

// `order_discount`,

// `order_pQty`,

// `order_pWeight`,

// `order_process`,

// `order_hash`

// ) VALUES (

// ?,?,?,?,?,?,?,?,?,?,?

// )";

// $array  =   array($invoiceId,$pIds,$pName,$storeName,$pPrice,$salePrice,$discount,$saleQTY,$weight,$process,$hash);

// $this->dbF->setRow($sql,$array,false);



// // Remove QTY FROM inventory

// if($process==1){

// $invQty =   $this->productF->stockProductQty($hash);

// if($invQty >= $saleQTY){

// if($this->productF->stockProductQtyMinus($hash,$saleQTY)){

// }else{

// throw new Exception($_e['Order QTY is Greater Than stock Quantity']);

// }

// }else{

// throw new Exception($_e['Order QTY is Greater Than stock Quantity']);

// }

// } // If Process Order End

// } // Foreach loop End



// //check php calculate price and javascript price

// if(floatval($total_price) != floatval($total_priceNew)){

// throw new Exception("Hacking Attempt Found Code : 151");

// }



// //check php calculate weight and javascript weight

// if(floatval($totalWeightReceiveFromForm) != floatval($total_weightNew)){

// throw new Exception("Hacking Attempt Found Code : 152");

// }



// // User Info Add

// //first add order invoice,, addNewOrder(); // not klarna

// if(intval($paymentType) !=intval('2')){



// $sql    =   "INSERT INTO `order_invoice_info`

// (

// `order_invoice_id`,



// `sender_name`,

// `sender_phone`,

// `sender_email`,

// `sender_address`,

// `sender_city`,

// `sender_country`,

// `sender_post`,



// `receiver_name`,

// `receiver_phone`,

// `receiver_email`,

// `receiver_address`,

// `receiver_city`,

// `receiver_country`,

// `receiver_post`

// )

// VALUES (

// ?,

// ?,?,?,?,?,?,?,

// ?,?,?,?,?,?,?

// )";

// $array  =   array(

// $invoiceId,

// $_POST['sender_name'] , $_POST['sender_phone'] , $_POST['sender_email'] , $_POST['sender_address'] , $_POST['sender_city'] , $_POST['sender_country'],$_POST['sender_post'],

// $_POST['receiver_name'],$_POST['receiver_phone'],$_POST['receiver_email'],$_POST['receiver_address'],$_POST['receiver_city'],$_POST['receiver_country'],$_POST['receiver_post'],

// );

// $this->dbF->setRow($sql,$array,false);

// }

// //Update invoice after

// //Calculating Shiping price

// $shippingData = $this->productF->shippingPrice($country,$_POST['receiver_country']);

// if($shippingData==false){

// //throw new Exception("Hacking Attempt Found OR Shipping Error");

// throw new Exception($_e["Shipping Error"]);

// }



// $shippingWeight    =    $shippingData['shp_weight'];

// $shippingPrice     =    $shippingData['shp_price'];

// //calculating

// @$unitWeight       =   ceil($total_weightNew/$shippingWeight);

// $unitWeight        =   round($unitWeight,2);

// $finalShippingPrice=    $shippingPrice*$unitWeight;



// $total_priceNew += $finalShippingPrice;



// $invoiceKey =   $this->functions->ibms_setting('invoice_key_start_with'); // Invoice Number start with





// if(intval($paymentType)===intval('2') ){

// $processStatus  = 'inComplete';

// }else{

// $processStatus= 'process';

// }

// $sql    =   "UPDATE `order_invoice` SET

// `invoice_id`    =   '".$invoiceKey.''.$invoiceId."',

// `total_price`   =   '$total_priceNew',

// `ship_price`     =   '$finalShippingPrice',

// `total_weight`  =   '$total_weightNew',

// `orderStatus`       =   '$processStatus',

// `shippingCountry`   =   ?

// WHERE `order_invoice_pk`  = '$invoiceId'";

// $this->dbF->setRow($sql,array($_POST['receiver_country']),false);



// $this->db->commit();



// if($this->dbF->rowCount>0){

// $msg    = $this->functions->notificationError(_js(_uc($_e['Product Successfully Submit'])),_js($_e['Thank you your product is successfully submit']),'btn-success');

// $_SESSION['msg'] =base64_encode($msg);

// $this->functions->setlog(_uc($_e['New Order']),_uc($_e['ORDER']),$invoiceKey.''.$invoiceId,$_e['New Order Added Successfully']);

// }else{

// $msg    = $this->functions->notificationError(_js(_uc($_e['Product Submit'])),_js(_uc($_e['Product Submit Failed'])),'btn-danger');

// $_SESSION['msg'] =base64_encode($msg);

// }



// $this->productF->paymentProcess($paymentType);

// // $this->functions->submitRefresh();

// }catch(Exception $e){

// $this->dbF->error_submit($e);

// $this->db->rollBack();

// $msg  = '';

// $msg  = $e->getMessage();

// if($msg != ''){

// $msg  =  $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])),$msg,'btn-danger');

// }

// $msg  =  $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])),_js($_e['Some thing went wrong Please try again']),'btn-danger');

// $_SESSION['msg'] =base64_encode($msg);

// }



// }else if(isset($_POST) && !empty($_POST) && ($_POST['submit']==$btn1 || $_POST['submit']==$btn2) ){

// $msg  =  $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])),_js($_e['Some thing went wrong Please try again']),'btn-danger');

// $_SESSION['msg'] =base64_encode($msg);

// }



// } // Function End





public function order_status(){

$array = array(

"process" => "Order Placed",

"cancelled" => "Cancelled",

"pending_inst" => "Pending Installation",

"live" => "Live",

"pending_remove" => "Pending Removal"

);



// $array = array("Order Placed","Cancelled","Pending Installation", "Live", "Pending Removal");

return $array;

}







public function  invoiceOrdersSql(){

$sql="SELECT * FROM `orders` WHERE order_status = 'process' AND `status` IS NULL ORDER BY order_id DESC";

$invoice = $this->dbF->getRows($sql);

return $invoice;

}



public function  pendingInstalSql(){

$sql="SELECT * FROM `orders` WHERE order_status = 'process' AND `status` = 'pending_inst' ORDER BY order_id DESC";

$invoice = $this->dbF->getRows($sql);

return $invoice;

}



public function  liveOrdersSql(){

$sql="SELECT * FROM `orders` WHERE order_status = 'process' AND `status` = 'live' ORDER BY order_id DESC";

$invoice = $this->dbF->getRows($sql);

return $invoice;

}



public function  all($user_id=false){

$user = "";

$array = array();

if( ! empty($user_id) ) {

$user = " WHERE `order_user` = ?";

$array[] = $user_id;

}

$sql     =  "SELECT * FROM `orders` $user ORDER BY order_id DESC";

$invoice =  $this->dbF->getRows($sql,$array);

return $invoice;

}



public function  completeOrdersSql(){

$comp_order = '';



$sql="SELECT * FROM `orders`";

$invoice = $this->dbF->getRows($sql);



foreach ($invoice as $key => $value) {

$order_id = $value['order_id'];



$sql = "SELECT COUNT(*) AS 'count' FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending'";

$res = $this->dbF->getRow($sql, array($order_id));



if($res['count'] == 0){

$comp_order .= $order_id.',';

}

}



$comp_order = rtrim($comp_order,',');



if(!empty($comp_order)){

$sql = "SELECT * FROM `orders` WHERE `order_id` IN ($comp_order)";

$res = $this->dbF->getRows($sql);

}else{

$res = array();

}



return $res;

}

public function  cancelOrdersSql(){

$sql="SELECT * FROM `orders` WHERE order_status = 'cancelled' ORDER BY order_id DESC";

$invoice = $this->dbF->getRows($sql);

return $invoice;

}

public function  inCompleteOrdersSql(){

$sql="SELECT * FROM `orders` WHERE order_status = 'incomplete' ORDER BY order_id DESC";

$invoice = $this->dbF->getRows($sql);

return $invoice;

}

public function deleteRequest(){

$sql="SELECT * FROM `orders` WHERE order_status = 'process' AND `del_request` = 1 ORDER BY order_id DESC";

$invoice = $this->dbF->getRows($sql);

return $invoice;

}



public function  invoiceList($order= '',$user_id = false){

global $_e;

$href      = "order/order_ajax.php?page=data_ajax_".$order;

$class     = "dTable";

$data_attr = '';

switch($order){

case 'complete':

$invoice = $this->completeOrdersSql();

break;

case 'pending_inst':

$invoice = $this->pendingInstalSql();

break;

case 'live':

$invoice = $this->liveOrdersSql();

break;

case 'all':

$invoice = $this->all();

break;

case 'cancel':

$invoice = $this->cancelOrdersSql();

break;

case 'incomplete':

$invoice = $this->inCompleteOrdersSql();

break;

case 'user':

$invoice = $this->all($user_id);

break;

case 'delete_request':

$invoice = $this->deleteRequest();

break;

case 'invoices':

$invoice = $this->invoiceOrdersSql();

$data_attr = ' data-sorting="true" ';

break;

default:

$invoice = array();

$class   = '';

break;

}



echo '

<div class="table-responsive">

<table class="table table-hover '.$class.' tableIBMS" '.$data_attr.'>

<thead>

<th>'. _u($_e['SNO']) .'</th>

<th>'. _u($_e['ORDER ID']) .'</th>
<th>'. _u($_e['ORDER DATE']) .'</th>
<th>'. _u('Company NAME') .'</th>

<th>'. _u('CUSTOMER Address') .'</th>

<th>'. _u('Phone Number') .'</th>

<th>'. _u('Installation Date') .'</th>
<th>'. _u('Detail') .'</th>




<th>'. _u($_e['SUBSCRIPTION PLAN']) .'</th>

<th>'. _u($_e['NEXT BILLING DATE']) .'</th>

<th>'. _u($_e['ORDER STATUS']) .'</th>

<!-- <th>'. _u($_e['ORDER EXPIRY']) .'</th>

<th>'. _u($_e['PAYMENT MODE']) .'</th>

<th>'. _u($_e['ORDER STATUS']) .'</th> -->

<th width="120">'. _u($_e['ACTION']) .'</th>

</thead>

<tbody>';



$isno=0;

//echo "<pre>"; print_r($invoice); echo "</pre>";

foreach($invoice as $val){

$isno++;

$id = $val['order_id'];

$invoiceId = $id;

$invoice_print = $this->functions->ibms_setting('invoice_key_start_with').$id;

$sql = "SELECT `company_name`,`mobile`,`address` FROM `order_detail` WHERE `order_id` = ?";

$res = $this->dbF->getRow($sql, array($id));



$cust_name  = $res['company_name'];
$cust_address  = $res['address'];
$cust_pno  = $res['mobile'];




$inoivcePdf = '';

if($val['order_status']!='inComplete'){



$inoivcePdf =" <a href='".WEB_URL."/invoicePrint.php?mailId=$invoiceId' target='_blank' class='btn'>

<i class='fa fa-file-pdf-o'></i>

</a>";

}



$edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>

<i class='glyphicon glyphicon-edit'></i>

</a>";

if(!empty($user_id)){

$edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>

<i class='glyphicon glyphicon-edit'></i>

</a>";

}





$sql = "SELECT `due_date` FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'paid' ORDER BY `due_date` ASC LIMIT 1";

$res = $this->dbF->getRow($sql, array($id));



$last_paid_date = $res['due_date'];



$next_due_date = date('Y-m-d', strtotime("+1 day $last_paid_date"));



$sql_due = "SELECT * FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending' AND `due_date` > ? ORDER BY `due_date` ASC LIMIT 1";

$res_due = $this->dbF->getRow($sql_due, array($id,$next_due_date));



$next_due = $res_due['due_date'];



$status_options = $this->order_status();



$stat_opt = '';

foreach ($status_options as $key => $value) {

$stat_opt .= '<option value="'.$key.'">'.$value.'</option>'; 

}



$admin_order_status = '';



if($val['order_status'] == 'process' && $val['status'] == 'pending_inst' || $val['order_status'] == 'process' && $val['status'] == 'live' || $val['order_status'] == 'process' && $val['status'] == 'pending_remove'){

$admin_order_status = $status_options[$val['status']];

}else{

if(array_key_exists($val['order_status'], $status_options)){

$admin_order_status = ''.$status_options[$val['order_status']];

}else{

$admin_order_status = $val['order_status'];

}

}



if($val['del_request'] == 1){

$admin_order_status = 'Pending Removal';

}









$sqliz = "SELECT `prodet_name`,`prodet_shortDesc` FROM `proudct_detail` WHERE `prodet_id` = ?";

$reso = $this->dbF->getRow($sqliz, array($val['product_id']));



$sub  = translateFromSerialize($reso['prodet_name']);
$prodet_shortDesc  = translateFromSerialize($reso['prodet_shortDesc']);

$sa = _uc($val['status']);
$sd ="";
if($prodet_shortDesc){

$sd ="(".$prodet_shortDesc.")";

}

$sa =str_ireplace("_", " ", $sa);


echo "<tr>


<td>$isno</td>
<td>$invoice_print</td>
<td>$val[order_date]</td>


<td>$cust_name</td>

<td>$cust_address</td>
<td>$cust_pno</td>
";




include_once("invoice.php");


// $this->functions->getAdminFile("classes/invoice.php");
$invoice = new invoice();
    // $order = new order();




$sqli = "SELECT * FROM `schedule_form` WHERE `order_id` = ?";
$resi = $this->dbF->getRows($sqli, array($id));
echo "<td>";
$count=0;
foreach ($resi as $key => $valuei) {
$count++;
$id = $valuei['schedule_id'];
$machines = json_decode($valuei['machines']);
// $oils = json_decode($valuei['oils']);
// $qty = json_decode($valuei['quantity']);
$schedule_date = ($valuei['schedule_date']);

// $detail = '';
$detail_machi = '';
$detail_oil = '';
for ($i=0; $i < sizeof($machines); $i++) {
$j = $i+1;
// $machine = $invoice->get_product($machines[$i]);
// $machine_name = translateFromSerialize($machine['prodet_name']); 
// $oil = $invoice->get_product($oils[$i]); 
// $oil_name = translateFromSerialize($oil['prodet_name']); 
// // $detail .= $j.') : '._uc($machine_name).'<br>'._uc($oil_name).' ('.$qty[$i].')<hr>';
// $detail_machi .= $j.') : '._uc($machine_name).'<br>('.$qty[$i].')<hr>';
// $detail_oil .= $j.') : <br>'._uc($oil_name).' ('.$qty[$i].')<hr>';



echo $schedule_date."<hr>";
}
}


echo "</td>";





echo "<td>";
$count=0;
foreach ($resi as $key => $valuei) {
$count++;
// $id = $valuei['schedule_id'];
$machines = json_decode($valuei['machines']);
// $oils = json_decode($valuei['oils']);
// $qty = json_decode($valuei['quantity']);
// $schedule_date = ($valuei['schedule_date']);

// $detail = '';
$detail_machi = '';
$detail_oil = '';
for ($i=0; $i < sizeof($machines); $i++) {
$j = $i+1;
$machine = $invoice->get_product($machines[$i]);
$machine_name = translateFromSerialize($machine['prodet_name']); 
$oils = json_decode($valuei['oils']);
// $oil = $invoice->get_product($oils[$i]); 
// $oil_name = translateFromSerialize($oil['prodet_name']); 
// $detail .= $j.') : '._uc($machine_name).'<br>'._uc($oil_name).' ('.$qty[$i].')<hr>';

$oil = $invoice->get_product($oils[$i]); 
$oil_name = translateFromSerialize($oil['prodet_name']); 


$detail_machi .= _uc($machine_name).' - '.$oil_name.'<br>';
// $detail_oil .= $j.') : <br>'._uc($oil_name).' ('.$qty[$i].')<hr>';



echo $detail_machi."<hr>";
}
}


echo "</td>";








// echo "<td>";
// $count=0;
// foreach ($resi as $key => $valuei) {
// $count++;
// $id = $valuei['schedule_id'];
// $machines = json_decode($valuei['machines']);
// $oils = json_decode($valuei['oils']);
// $qty = json_decode($valuei['quantity']);
// // $schedule_date = ($valuei['schedule_date']);

// // $detail = '';
// $detail_machi = '';
// $detail_oil = '';
// for ($i=0; $i < sizeof($machines); $i++) {
// $j = $i+1;
// // $machine = $invoice->get_product($machines[$i]);
// // $machine_name = translateFromSerialize($machine['prodet_name']); 
// $oil = $invoice->get_product($oils[$i]); 
// $oil_name = translateFromSerialize($oil['prodet_name']); 
// // $detail .= $j.') : '._uc($machine_name).'<br>'._uc($oil_name).' ('.$qty[$i].')<hr>';
// // $detail_machi .= $j.') : '._uc($machine_name).'<br>('.$qty[$i].')<hr>';
// $detail_oil .= $j.') : <br>'._uc($oil_name).' ('.$qty[$i].')<br>';



// echo $detail_oil."<hr>";
// }
// }


// echo "</td>";
echo "


<td>$sub $sd</td>

<td>".$next_due."</td>

<!-- <td>$val[expire_duration] "._uc('months')."</td>

<td>"._uc($val['payment_mode'])."</td> -->

<td>

<a data-id=".$invoiceId." onclick='changeInvoiceStatus1(this)'>"._uc($admin_order_status)."</a>

<div class='invoice_status_".$invoiceId."'>

<select class='form-control' data-id='".$invoiceId."' id='statusOption3_".$invoiceId."' style='display:none;' onchange='updateInvoiceStatus(this)'>

".$stat_opt."

</select>

</div>



</td>

<td>

<div class='btn-group btn-group-sm'>

$inoivcePdf

$edit_link ";

// if($date<$minusDays){

echo "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>

<i class='glyphicon glyphicon-remove' style='
color: red;
'></i>

<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

</a>";

// }else{

//     echo "<a class='btn'>

//          <i class='glyphicon glyphicon-trash '></i>

//          <i class='glyphicon glyphicon-ban-circle combineicon'></i>

//      </a>";

// }



echo "</div>

</td>

</tr>";




}


echo '

</tbody>

</table>

</div> <!-- .table-responsive End -->';



}





public function orderInvoiceInfo($orderId){

$sql    =   "SELECT * FROM order_invoice_info WHERE order_invoice_id = '$orderId'";

$data   =   $this->dbF->getRow($sql);

return $data;

}



public function invoiceListUser1($userId,$echo = true){

global $_e;

$temp = '';



$temp .= '

<div class="table-responsive">

<table class="table table-hover dTable tableIBMS">

<thead>

<th class="hidden-xs">'. _u($_e['SNO']) .'</th>

<th>'. _u($_e['INVOICE']) .'</th>

<th class="hidden-xs">'. _u($_e['CUSTOMER NAME']) .'</th>

<th class="hidden-xs">'. _u($_e['INVOICE DATE']) .'</th>

<th>'. _u($_e['PURCHASE PRICE']) .'</th>

<th class="hidden-xs hidden-sm">'. _u($_e['PAYMENT METHOD']) .'</th>

<th class="hidden-xs">'. _u($_e['ORDER PROCESS']) .'</th>

<th>'. _u($_e['Invoice Status']) .'</th>

<th>'. _u($_e['VIEW ORDER']) .'</th>

</thead>

<tbody>';



$sql="SELECT * FROM `order_invoice` WHERE orderUser = '$userId' ORDER BY order_invoice_pk DESC";

$invoice = $this->dbF->getRows($sql);

if(!$this->dbF->rowCount){

$noFound = "<div class='alert alert-danger text-center'>".$this->dbF->hardWords('No Invoice Found',false)."</div>";

if($echo){

echo $noFound;

}else{

return $noFound;

}

return "";

}

$i=0;

foreach($invoice as $val){

$i++;

$divInvoice     =   '';

$invoiceStatus  =   $this->productF->invoiceStatusFind($val['invoice_status']);

$st = $val['invoice_status'];



if($st=='0') $divInvoice = "<div class='btn btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

else if($st=='1') $divInvoice = "<div class='btn btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

else if($st=='2') $divInvoice = "<div class='btn btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

else if($st=='3') $divInvoice = "<div class='btn btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

else $divInvoice = "<div class='btn btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";



$invoiceDate    =   date('Y-m-d H:i:s',strtotime($val['dateTime']));

$invoiceId      =   $val['order_invoice_pk'];



$orderInfo      =   $this->orderInvoiceInfo($invoiceId);

$customeName    =   $orderInfo['sender_name'];



//Check order process or not,, if single product process it show 1

$sql    =   "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";

$this->dbF->getRow($sql);



$orderProcess   ="<div class='btn btn-danger  btn-sm' style='width:50px;'>". _uc($_e['NO']) ."</div>";

if($this->dbF->rowCount>0){

//make sure all order process or custome process

$sql    =   "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";

$this->dbF->getRow($sql);

if($this->dbF->rowCount>0){

$orderProcess   ="<div class='btn btn-warning  btn-sm' style='width:50px;'>". _uc($_e['Yes']) ."</div>";

}else{

$orderProcess   ="<div class='btn btn-success  btn-sm' style='width:50px;'>". _uc($_e['Yes']) ."</div>";

}

}

$days    = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');

$link    = $this->functions->getLinkFolder();

$date    =   date('Y-m-d',strtotime($val['dateTime']));

$minusDays  =   date('Y-m-d',strtotime("-$days days"));



$class = "

<a href='invoicePrint?mailId=$invoiceId&orderId=".$this->functions->encode($invoiceId)."'  target='_blank' class='btn btn-success'>

<i class='fa fa-file-pdf-o'></i>

</a>

<a href='?view=$invoiceId&orderId=".$this->functions->encode($invoiceId)."' class='btn  btn-success'>

<i class='glyphicon glyphicon-list-alt'></i>

</a>";

if($val['orderStatus']=='inComplete'

|| $val['orderStatus']=='pendingPaypal'

|| $val['orderStatus']=='pendingPayson'){

$class = "

<a href='orderInvoice.php?inv=$invoiceId' target='_blank' class='btn btn-danger'>

<i class='glyphicon glyphicon-share-alt '></i>

</a>

<a href='?view=$invoiceId&orderId=".$this->functions->encode($invoiceId)."' class='btn  btn-success'>

<i class='glyphicon glyphicon-list-alt'></i>

</a>



";

}



$paymentMethod  =   $val['paymentType'];

$paymentMethod  =   $this->productF->paymentArrayFindWeb($paymentMethod);

$temp .= "<tr>

<td class='hidden-xs'>$i</td>

<td>$val[invoice_id]</td>

<td class='hidden-xs'>$customeName</td>

<td class='hidden-xs'>$invoiceDate</td>

<td>$val[total_price] $val[price_code]</td>

<td class='hidden-xs hidden-sm'>$paymentMethod</td>

<td class='hidden-xs'>$orderProcess</td>

<td>$divInvoice</td>

<td>

<div class='btn-group btn-group-sm'>

$class";

$temp .= "</div>

</td>

</tr>";

}



$temp .= '

</tbody>

</table>

</div> <!-- .table-responsive End -->';



if($echo){

echo $temp;

}else{

return $temp;

}



}



public function invoiceListUser($order= '',$user_id = false){

global $_e;

$href      = "order/order_ajax.php?page=data_ajax_".$order;

$class     = "dTable";

$data_attr = '';

switch($order){

case 'complete':

$invoice = $this->completeOrdersSql();

// $invoice = array();

// $class   = "dTable_ajax";

break;

case 'all':

$invoice = $this->all();

// $invoice = array();

// $class   = "dTable_ajax";

break;

case 'cancel':

$invoice = $this->cancelOrdersSql();

// $invoice = array();

// $class   = "dTable_ajax";

break;

case 'incomplete':

$invoice = $this->inCompleteOrdersSql();

// $invoice = array();

// $class   = "dTable_ajax";

break;

case 'user':

$invoice = $this->all($user_id);

break;

case 'invoices':

$invoice = $this->invoiceOrdersSql();

// $invoice   = array();

// $class     = "dTable_ajax";

$data_attr = ' data-sorting="true" ';

break;

default:

$invoice = array();

$class   = '';

break;

}



echo '

<div class="table-responsive">

<table class="table table-hover '.$class.' tableIBMS" '.$data_attr.'>

<thead>

<th>'. _u($_e['SNO']) .'</th>

<th>'. _u($_e['ORDER ID']) .'</th>

<th>'. _u($_e['CUSTOMER NAME']) .'</th>

<th>'. _u($_e['ORDER DATE']) .'</th>

<th>'. _u($_e['SUBSCRIPTION PLAN']) .'</th>

<th>'. _u($_e['NEXT BILLING DATE']) .'</th>

<!-- <th>'. _u($_e['ORDER EXPIRY']) .'</th>

<th>'. _u($_e['PAYMENT MODE']) .'</th> -->

<th>'. _u($_e['ORDER STATUS']) .'</th>

<th width="120">'. _u($_e['ACTION']) .'</th>

</thead>

<tbody>';



$i=0;

//echo "<pre>"; print_r($invoice); echo "</pre>";

foreach($invoice as $val){

$i++;

$id = $val['order_id'];

$invoiceId = $id;

$invoiceId_print = $this->functions->ibms_setting('invoice_key_start_with').$id;

$sql = "SELECT `company_name` FROM `order_detail` WHERE `order_id` = ?";

$res = $this->dbF->getRow($sql, array($id));



$cust_name  = $res['company_name'];



$inoivcePdf = '';

if($val['order_status']!='inComplete'){



$inoivcePdf =" <a href='".WEB_URL."/printInvoice.php?mailId=$invoiceId' target='_blank' class='btn'>

<i class='glyphicon glyphicon-print'></i>

</a>";

}



$edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>

<i class='glyphicon glyphicon-edit'></i>

</a>";

if(!empty($user_id)){

$edit_link = "<a href='?pId=$invoiceId' data-method='post' data-action='-order?page=edit' class='btn'>

<i class='glyphicon glyphicon-edit'></i>

</a>";

}



$delete_link = "<a class='btn' data-id='$invoiceId' onclick='delOrderInvoice(this);'>

<i class='glyphicon glyphicon-remove' style='
color: red;
'></i>

<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

</a>";



if($val['order_status'] != 'process'){

$inoivcePdf = '';

$delete_link = '';

}



if($val['del_request'] == 1){

// $inoivcePdf = '';

$delete_link = '';

}



$sql = "SELECT `due_date` FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'paid' ORDER BY `due_date` ASC LIMIT 1";

$res = $this->dbF->getRow($sql, array($id));



$last_paid_date = $res['due_date'];



$next_due_date = date('Y-m-d', strtotime("+1 day $last_paid_date"));



$sql_due = "SELECT * FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending' AND `due_date` > ? ORDER BY `due_date` ASC LIMIT 1";

$res_due = $this->dbF->getRow($sql_due, array($id,$next_due_date));



$next_due = $res_due['due_date'];



$status_options = $this->order_status();



$admin_order_status = '';



if($val['order_status'] == 'process' && $val['status'] == 'pending_inst' || $val['order_status'] == 'process' && $val['status'] == 'live' || $val['order_status'] == 'process' && $val['status'] == 'pending_remove'){

$admin_order_status = $status_options[$val['status']];

}else{

if(array_key_exists($val['order_status'], $status_options)){

$admin_order_status = ''.$status_options[$val['order_status']];

}else{

$admin_order_status = $val['order_status'];

}

}



if($val['del_request'] == 1){

$admin_order_status = 'Pending Removal';

}



echo "<tr>

<td>$i</td>

<td>$invoiceId_print</td>

<td>$cust_name</td>

<td>$val[order_date]</td>

<td>AED $val[price_per_month]</td>

<td>$next_due</td>

<!-- <td>$val[expire_duration] "._uc('months')."</td>

<td>"._uc($val['payment_mode'])."</td> -->

<td>"._uc($admin_order_status)."</td>

<td>

<div class='btn-group btn-group-sm'>

$inoivcePdf

$edit_link 

$delete_link

</div>

</td>

</tr>";

}



echo '

</tbody>

</table>

</div> <!-- .table-responsive End -->';

}



public function invoiceSQL($column = '*'){

$sql="SELECT ".$column." FROM `order_invoice`";

return $this->dbF->getRows($sql);

}



public function getAllSchedules(){

$sql = "SELECT * FROM `schedule_form` 
ORDER BY (
CASE schedule_slot
WHEN '8AM to 11AM'
THEN 1
WHEN '11AM to 2PM'
THEN 2
WHEN '2PM to 5PM'
THEN 3
WHEN '5PM to 8PM'
THEN 4
END
) asc";


$schedules = $this->dbF->getRows($sql);



$finalJson = array();

foreach ($schedules as $key => $value) {

$order_id = $value['order_id'];

$order_id_print = $this->functions->ibms_setting('invoice_key_start_with').$order_id;

$schedule_slot = $value['schedule_slot'];

$service_type = $value['service_type'];

$schedule_date = date('c',strtotime($value['schedule_date']));



$sql_user = "SELECT `company_name`,`fname`,`lname` FROM `order_detail` WHERE `order_id` = ?";

$res_user = $this->dbF->getRow($sql_user, array($order_id));



$name = empty($res_user['company_name']) ? $res_user['fname'].' '.$res_user['lname'] : $res_user['company_name'];

if($schedule_slot=='8AM to 11AM'){$c='1';}
if($schedule_slot=='11AM to 2PM'){$c='2';}
if($schedule_slot=='2PM to 5PM'){$c='3';}
if($schedule_slot=='5PM to 8PM'){$c='4';}

$title = 'Slot '.$c.': '.$schedule_slot.'<br>Order: '.$order_id_print.'<br>Client: '.$name.'';


$array = array(

'title' =>  $title,

'start' => $schedule_date,

'end' => $schedule_date,

'url' => WEB_ADMIN_URL.'/-order?page=edit&orderId='.$order_id

);



$finalJson[] = $array;

}



return json_encode($finalJson);

// return $schedules;

}



public function getTechincalForms(){

global $_e;



$sql = "SELECT * FROM `schedule_form`";

$schedule = $this->dbF->getRows($sql);



echo '

<div class="table-responsive">

<table class="table table-hover dTable tableIBMS">

<thead>

<th>'. _u($_e['SNO']) .'</th>

<th>'. _u($_e['ORDER ID']) .'</th>

<th>'. _u($_e['CUSTOMER NAME']) .'</th>

<th>'. _u($_e['SCHEDULE DATE']) .'</th>

<th>'. _u($_e['SCHEDULE SLOT']) .'</th>

<th>'. _u('TECHINCAL FORM') .'</th>

</thead>

<tbody>';

$i = 0;

foreach ($schedule as $key => $value) {

$i++;



$schedule_id = $value['schedule_id'];

$order_id = $value['order_id'];

$order_id_print = $this->functions->ibms_setting('invoice_key_start_with').$order_id;



$tech_sql = "SELECT `technical_id` FROM `technical_form` WHERE `schedule_id` = ?";

$tech_res = $this->dbF->getRow($tech_sql, array($schedule_id));



$sql_det = "SELECT `company_name`,`fname`,`lname` FROM `order_detail` WHERE `order_id` = ?";

$res_det = $this->dbF->getRow($sql_det, array($order_id));



$user_name = (empty($res_det['company_name']))? $res_det['fname'].' '.$res_det['lname'] : $res_det['company_name'];



$techincal = '<a href="?pId='.$order_id.'" data-method="post" data-action="?page=edit&tech='.$schedule_id.'" class="btn btn-primary">

<i class="fa fa-plus-square"></i>

</a>';



if(empty($tech_res)){  

echo "<tr>

<td>$i</td>

<td>$order_id_print</td>

<td>$user_name</td>

<td>$value[schedule_date]</td>

<td>"._uc($value['schedule_slot'])."</td>

<td><div class='btn-group btn-group-sm'>$techincal</div></td>

</tr>";

}    

}



echo '</tbody>

</table>

<div>';

}



public function allScheduleData($userId, $complete=false){

global $_e;



$where = '';

if($complete){

$where = "AND sf.`sched_complete` = 0";

}



$sql = "SELECT sf.`schedule_id`,sf.`schedule_date`,sf.`schedule_slot` FROM `schedule_form` sf JOIN `orders` o WHERE o.`order_id` = sf.`order_id` AND o.`order_user` = ? $where";

$res = $this->dbF->getRows($sql, array($userId));



return $res;



}



public function allOpenOrders($userId){



$sql = "SELECT DISTINCT(`product_id`),`order_id`,`del_request` FROM `orders` WHERE `order_user` = ? AND `order_status` = 'process'";

$res = $this->dbF->getRows($sql, array($userId));



return $res;



}



public function allSchedules($userId){

global $_e;



$class     = "dTable";

echo '

<div class="table-responsive">

<table class="table table-hover '.$class.' tableIBMS" >

<thead>

<th>'. _u($_e['SNO']) .'</th>

<th>'. _u($_e['ORDER ID']) .'</th>

<th>'. _u($_e['SCHEDULE DATE']) .'</th>

<th>'. _u($_e['TIME SLOT']) .'</th>

<th>'. _u($_e['DETAIL']) .'</th>

<th>'. _u($_e['TECHNICAL FORM']) .'</th>

</thead>

<tbody>';





$sql = "SELECT sf.`schedule_id`,sf.`machines`,sf.`oils`,sf.`quantity`,sf.`schedule_date`,o.`order_id`,sf.`schedule_slot` FROM `schedule_form` sf JOIN `orders` o WHERE o.`order_id` = sf.`order_id` AND o.`order_user` = ?";

$res = $this->dbF->getRows($sql, array($userId));



$count=0;

foreach ($res as $key => $value) {

$count++;

$id = $value['schedule_id'];

$machines = json_decode($value['machines']);

$oils = json_decode($value['oils']);

$qty = json_decode($value['quantity']);

$order_id = $this->functions->ibms_setting('invoice_key_start_with').$value['order_id'];



$detail = '';

for ($i=0; $i < sizeof($machines); $i++) {

$j = $i+1;

$machine = $this->get_product($machines[$i]);

$machine_name = translateFromSerialize($machine['prodet_name']); 



$oil = $this->get_product($oils[$i]); 

$oil_name = translateFromSerialize($oil['prodet_name']); 



$detail .= $j.') : '._uc($machine_name).'<br>'._uc($oil_name).' ('.$qty[$i].')<br>';

}



$sql = "SELECT `technical_id`,`client_confirm` FROM `technical_form` WHERE `schedule_id` = ?";

$res = $this->dbF->getRow($sql, array($id));



if(!empty($res)){

$tech_id = $res['technical_id'];

$confirm = $res['client_confirm'];

$add_technical = '<a class="button btn-primary btn-xs view_technical" data-id="'.$tech_id.'" ><i class="fa fa-eye"></i> View Technical</a>';



if($confirm == 0){

$confirm_button = '<a class="" style="cursor:pointer; text-decoration: underline;" data-id="'.$tech_id.'" id="confirm_technical"><i class="fa fa-check-circle"></i> Confirm </a>';

}else{

$confirm_button = '<a class="" style="color: green" data-id="'.$tech_id.'"><i class="fa fa-check-circle"></i> Confirmed </a>';

}



}else{

$add_technical = '';

$confirm_button = '';

}











echo '<tr>

<td>'.$count.'</td>

<td>'.$order_id.'</td>

<td>'.$value['schedule_date'].'</td>

<td>'._uc($value['schedule_slot']).'</td>

<td>'.$detail.'</td>

<td>'.$add_technical.'<br><br>'.$confirm_button.'</td>

</tr>';



}



echo '</tbody>

</table>

</div>

';



}










//my code
public function orderSubmit(){

global $_e;

if (isset($_POST) && !empty($_POST) && !empty($_POST['country'])) {

if (!$this->functions->getFormToken('orderAdd')) {
return false;
}

try {
$this->db->beginTransaction();

$userId = webUserId();
if ($userId == '0') {
$userId = webTempUserId();
}

// $this_script = 

$status = 'process';

$expire_months  = $_POST['s_valid'];
$price          = $_POST['s_price'];
$payment        = $_POST['s_payment'];
$productId      = $_POST['s_productid'];
$fname          = $_POST['fname'];
$company_name   = $_POST['company_name'];
$lname          = $_POST['lname'];
$mobile         = $_POST['mobile'];
$address        = $_POST['address'];
$country        = $_POST['country'];
$email          = $_POST['email'];
$submit         = $_POST['submit'];

$prod_detail    = $this->functions->getProductName($productId, 'prodet_name');

$prodet_shortDesc    = $this->functions->getProductName($productId, 'prodet_shortDesc');
$pro_name       = translateFromSerialize($prod_detail['prodet_name']);
$prodet_shortDesc       = translateFromSerialize($prodet_shortDesc['prodet_shortDesc']);
$now            = date('Y-m-d H:i:s');
$cur_date       = date('Y-m-d');
$expire_date    = date('Y-m-d H:i:s', strtotime("+$expire_months months $now"));

$sql = "INSERT INTO `orders`(
`product_id`, 
`order_user`, 
`order_date`, 
`price_per_month`,
`order_status`,
`expire_duration`, 
`expire_date`,
`order_ref`, 
`payment_mode`
) VALUES (?,?,?,?,?,?,?,?,?)";

$array = array($productId,$userId,$now,$price,$status,$expire_months,$expire_date,'manual',$payment);
$this->dbF->setRow($sql, $array, false);
$orderId = $this->dbF->rowLastId;
$recur_payment = false;

if($orderId > 0){

$sql = "INSERT INTO `order_detail`(
    `order_id`, 
    `company_name`,
    `fname`, 
    `lname`, 
    `mobile`, 
    `address`, 
    `country`, 
    `email`
) VALUES (?,?,?,?,?,?,?,?)";

$this->dbF->setRow($sql, array($orderId,$company_name,$fname,$lname,$mobile,$address,$country,$email), false);

$noOfInvoices = ($expire_months);
$invoice_floor = floor($noOfInvoices);
$firstInv = '';

if($invoice_floor == 0 || $invoice_floor == 1){
$inv_status = 'pending';
$sql = "INSERT INTO `invoices`( 
        `order_id`, 
        `price`, 
        `due_date`, 
        `invoice_status`, 
        `update_date`
    ) VALUES (?,?,?,?,?)";

$this->dbF->setRow($sql, array($orderId,$price,$cur_date,$inv_status,$cur_date), false);
$firstInv = $this->dbF->rowLastId;

}else{
$due_date = $cur_date;
$recur_payment = true;

if($payment == 'monthly'){
$noOfInvoices = ($expire_months);

$repeat_amount      = $price;
$repeat_period      = 'M';
$repeat_interval    = 1;
$repeat_start       = date('dmY', strtotime("+1 month $due_date"));
$repeat_term        = $invoice_floor;

for ($i=0; $i < $invoice_floor; $i++) {

$inv_status = 'pending';
$inv_price  = $price;

$sql = "INSERT INTO `invoices`( 
                `order_id`, 
                `price`, 
                `due_date`, 
                `invoice_status`, 
                `update_date`
            ) VALUES (?,?,?,?,?)";

$this->dbF->setRow($sql, array($orderId,$inv_price,$due_date,$inv_status,$cur_date), false);

if($i == 0){
    $firstInv = $this->dbF->rowLastId;
}

$due_date = date('Y-m-d', strtotime("+1 month $due_date"));

}

}else if($payment == 'quarterly'){

$repeat_amount      = $price;
$repeat_period      = 'M';
$repeat_interval    = 3;
$repeat_start       = date('dmY', strtotime("+3 months $due_date"));
$noOfInvoices = ($expire_months/3);
$invoice_floor = floor($noOfInvoices);
$repeat_term        = $invoice_floor-1;

for ($i=0; $i < $invoice_floor; $i++) {

$inv_status = 'pending';
$inv_price  = ($price*3);

$sql = "INSERT INTO `invoices`( 
                `order_id`, 
                `price`, 
                `due_date`, 
                `invoice_status`, 
                `update_date`
            ) VALUES (?,?,?,?,?)";

$this->dbF->setRow($sql, array($orderId,$inv_price,$due_date,$inv_status,$cur_date), false);

if($i == 0){
    $firstInv = $this->dbF->rowLastId;
}

$due_date = date('Y-m-d', strtotime("+3 months $due_date"));

}

}

}

// $params = array(
// 'ivp_method'  => 'create',
// 'ivp_store'   => '20901',
// 'ivp_authkey' => 'vJJrn~6LpK-6FR8f',
// 'ivp_cart'    => $orderId,  
// 'ivp_test'    => '0',
// 'ivp_amount'  => $inv_price,
// 'ivp_currency'=> 'AED',
// 'ivp_desc'    => $pro_name." ".$prodet_shortDesc,
// 'return_auth' => 'http://iscent.ae/orderInvoice.php?order='.$orderId.'&firstInv='.$firstInv,
// 'return_can'  => 'http://iscent.ae/orderInvoice.php?order='.$orderId.'&firstInv='.$firstInv,
// 'return_decl' => 'http://iscent.ae/orderInvoice.php?order='.$orderId.'&firstInv='.$firstInv,

// 'bill_fname'  => $fname,
// 'bill_sname'  => $lname,
// 'bill_addr1'  => $address,
// 'bill_city'   => $country,
// 'bill_email'  => $email,
// 'bill_country'=> 'ae',

// 'repeat_amount'=> $inv_price,
// 'repeat_period'=> $repeat_period,
// 'repeat_interval'=> $repeat_interval,
// 'repeat_start'=> $repeat_start,
// 'repeat_term'=> $repeat_term,
// 'repeat_final'=> '0',
// );

// $ch = curl_init();

// curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
// curl_setopt($ch, CURLOPT_POST, count($params));
// curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

// $results = curl_exec($ch);
// curl_close($ch);

// $results = json_decode($results,true);

// // echo '<pre>'; print_r($params); echo '</pre>';
// // echo '<pre>'; print_r($results); echo '</pre>';

// $ref= trim($results['order']['ref']);
// $url= trim($results['order']['url']);
// if (empty($ref) || empty($url)) {
# Failed to create order

// $sql_upd = "UPDATE `orders` SET `order_status` = 'incomplete' WHERE `order_id` = ?";
// $this->dbF->setRow($sql_upd, array($orderId), false);

// }else{

// $sql_upd = "UPDATE `orders` SET `order_ref` = ? WHERE `order_id` = ?";
// $this->dbF->setRow($sql_upd, array($ref,$orderId), false);
 
if($this->dbF->rowCount > 0){
	$name = "";
	$this->createWebUserAccount(false,$orderId,'1',$name,$email,
array
('gender'=>'',
'type'=>'',
'date_of_birth'=>'',
'phone'=>$mobile,
'address'=>$address,
), 
array(
'company' => $company_name,
'firstName' => $fname,
'lastName' => $lname,
'country' => $country
)
);
// header('Location: '.$url); 
// echo "<script>location.replace('$url');</script>";
	$this->functions->notificationError(_js($_e["Order Placed"]),_js($_e["Your Order Placed Successfully"]),"btn-success");

}

// }
}



} catch (Exception $e) {
$this->dbF->error_submit($e);
$msgT = $e->getMessage();
$this->db->rollBack();
$msg = $this->dbF->hardWords('Something went wrong Please try again', false);
return $msg . " <br> " . $msgT;
}

}

}


function createWebUserAccount($orderUser=false,$invoiceId,$status='1',$name,$email,$settingArray=array(), $zohoContact=false){
//$status = "1"; //pending 0 .. 1 active

global $functions;
global $webClass;
global $dbF;

$aLink = WEB_URL . "/login.php?";

$sql = "SELECT * FROM accounts_user WHERE acc_email = '$email'";
$accData    = $dbF->getRow($sql);
$already = false;
if($dbF->rowCount>0){
$already = true;
$lastId  =   $accData['acc_id'];
$zohoContact_id = $accData['zoho_contact_id'];
}else{

$today  = date("Y-m-d H:i:s");
$unique =   uniqid();
$password  =  $functions->encode($unique);


##########  ZOHO BOOKS SYNC START  ############

$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

$refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 

$params = array(
'refresh_token' => $refresh,
'client_id' => $client_id,
'client_secret' => $secret,
'redirect_uri' => WEB_URL.'/orderInvoice.php',
'grant_type' => 'refresh_token'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
curl_setopt($ch, CURLOPT_POST, count($params));
curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results = curl_exec($ch);
curl_close($ch);

$zoho_return = json_decode($results,true);
$access_token = $zoho_return['access_token'];

// Contact fields for testing.
$contact_array = array(
"contact_name" => $zohoContact['company'],
"tax_treatment" => "vat_not_registered",
"place_of_supply" => $zohoContact['country']
);

// Access API to create contact
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/contacts?organization_id=667162566");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($contact_array));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results2 = curl_exec($ch);
curl_close($ch);

$array2 = json_decode($results2,true);

$zoho_contact = '';

if($array2['code'] == '0'){

$contact_id = $array2['contact']['contact_id'];
$contactPerson_array = array(

'contact_id'    => $contact_id,
'first_name'    => $zohoContact['firstName'],
'last_name'     => $zohoContact['lastName'],
'email'         => $email

);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/contacts/contactpersons?organization_id=667162566");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($contactPerson_array));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resultsPerson = curl_exec($ch);
curl_close($ch);

$contact_person = json_decode($resultsPerson,true);

if($contact_person['code'] == '0'){

$zContact_id = $contact_person['contact_person']['contact_id'];
$zContact_pid = $contact_person['contact_person']['contact_person_id'];

$zoho_contact = "zoho_contact_id = '$zContact_id',
zoho_contact_person = '$zContact_pid',";

}
}


$sql = "INSERT INTO accounts_user SET
acc_name = ?,
acc_email = ?,
acc_pass = ?,
acc_type = '$status',
$zoho_contact
acc_created = '$today'";
$array = array($name,$email,$password);

$dbF->setRow($sql,$array,false);
$lastId = $dbF->rowLastId;
$acc_name = $accData['acc_name'];
$sql        =   "INSERT INTO `accounts_user_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";
$arry       =   array();
foreach($settingArray as $key=>$val){
$sql .= "('$lastId',?,?) ,";
$arry[]= $key ;
$arry[]= $val ;
}
$sql = trim($sql,",");
$dbF->setRow($sql,$arry,false);
}

$sql = "UPDATE  `orders` SET
order_user = '$lastId'
WHERE order_id = '$invoiceId'";
$dbF->setRow($sql,false);

$ThankWeSend = $dbF->hardWords('Thank you! We have sent verification email. Please check your email.',false);
if($already){
$password = $functions->decode($accData['acc_pass']);
$setPswrdHash = $email.'---'.$password;
$setPswrdHash = base64_encode($setPswrdHash);
$mailArray['link']        =   $aLink.'set='.$setPswrdHash;
// $mailArray['link']        =   $aLink;
$mailArray['password']     =   $password;
$fname = explode(" ",$name);


$functions->send_mail($email,'', '','accountCreateOnOrder',$fname[0],$mailArray);
return $msg = $ThankWeSend;
}else{
// $mailArray['link']        =   $aLink;
$setPswrdHash = $email.'-'.$unique;
$setPswrdHash = base64_encode($setPswrdHash);
$mailArray['link']        =   $aLink.'set='.$setPswrdHash;
$mailArray['password']    =   $unique;

$fname = explode(" ",$name);
$functions->send_mail($email,'', '','accountCreateOnOrder',$fname[0],$mailArray);
return $msg = $ThankWeSend;
}

 ############## invoice

$sql = "SELECT `zoho_contact_id`, `zoho_contact_person` FROM `accounts_user` WHERE `acc_id` = ?";
$res = $dbF->getRow($sql, array($user_id));

if(!empty($res)){

$zoho_contact_id     = $res['zoho_contact_id'];
$zoho_contact_person = $res['zoho_contact_person'];

$sql_invDet = "SELECT * FROM `invoices` WHERE `invoice_pk` = ?";
$res_invDet = $dbF->getRow($sql_invDet, array($firstInv));

$sql_pid = "SELECT o.`product_id` FROM `orders` o JOIN `invoices` i WHERE o.`order_id` = i.`order_id` AND i.`invoice_pk` = ?";
$res_pid = $dbF->getRow($sql_pid, array($firstInv));

$sql_item = "SELECT pd.`prodet_name`,pd.`prodet_shortDesc`,pd.`zoho_item_no`,pp.`propri_price` FROM `proudct_detail` pd JOIN `product_price` pp WHERE pd.`prodet_id` = pp.`propri_prodet_id` AND pd.`prodet_id` = ?";
$res_item = $dbF->getRow($sql_item, array($res_pid['product_id']));

$pro_name = translateFromSerialize($res_item['prodet_name']);
$pro_desc = translateFromSerialize($res_item['prodet_shortDesc']);



$order_invoice_print = $functions->ibms_setting('invoice_key_start_with').$order_id.' - '.$firstInv;

$invoice_det = array(
'customer_id' => $zoho_contact_id,
'contact_persons' => array($zoho_contact_person),
'invoice_number' => $order_invoice_print,
'date' => $res_invDet['due_date'],
'line_items' => array(
array(
'item_id' => $res_item['zoho_item_no'],
'name' => $pro_name,
'description' => $pro_desc,
'item_order' => 1,
'rate' => doubleval($res_invDet['price']),
'quantity' => 1
)
)


);


$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

// Zoho Books Refresh Token with Scope of Full Access ( ZohoBooks.fullaccess.all ).
$refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 


$params = array(
'refresh_token' => $refresh,
'client_id' => $client_id,
'client_secret' => $secret,
'redirect_uri' => WEB_URL.'/orderInvoice.php',
'grant_type' => 'refresh_token'
);

// Using refresh token to generate access token.
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
curl_setopt($ch, CURLOPT_POST, count($params));
curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results = curl_exec($ch);
curl_close($ch);

$array = json_decode($results,true);

$access_token = $array['access_token']; // Access Token


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/invoices?organization_id=667162566&ignore_auto_number_generation=true");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($invoice_det));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results2 = curl_exec($ch);
curl_close($ch);

$array2 = json_decode($results2,true);

if($array2['code'] == '0'){
$zoho_inv_id = $array2['invoice']['invoice_id'];

$sql_upd = "UPDATE `invoices` SET `zoho_inv_id` = ? WHERE `invoice_pk` = ?";
$dbF->setRow($sql_upd, array($zoho_inv_id,$firstInv), false);
}

}

$_GET['mailId'] = $order_id;
$msg2 = include(__DIR__.'/orderMail.php');

$orderIdInvoice =   $functions->ibms_setting('invoice_key_start_with').$order_id;
$orderIdInvoice =   $dbF->hardWords('Thank you for your purchase. Order ID ',false)." ($orderIdInvoice)";
$fromName       =   $functions->webName;

$mailArray['fromName']    =   $fromName;
$functions->send_mail($email,$orderIdInvoice,$msg2,'',$name,$mailArray);

$adminMail = $functions->ibms_setting('Email');
$functions->send_mail($adminMail,$orderIdInvoice,$msg2,'','',$mailArray);
	
	############## invoice


}




//my code





















public function get_product($pid){



$result = false;



$sql    =   "SELECT * FROM `proudct_detail` WHERE `prodet_id` = ? AND product_update = '1' ";

$row    =   $this->dbF->getRow($sql,array($pid));

if( $this->dbF->rowCount > 0 ){

$result = $row;

}

return $result;

}


}

echo "<script>

    function changeInvoiceStatus1(ths){
            inv_id = $(ths).data('id');
            console.log(inv_id);
            $('#statusOption3_'+inv_id).css('display', 'block');
        }

</script>";

?>