<?php
include_once(__DIR__."/../../global.php"); 	 //
include_once("config.php");
include_once("functions.php");


// $invoiceId = _POST('order'); ## USING SESSION FOR INVOICE ID
$invoiceId = isset($_SESSION['paypal']['nvp']['invoiceId']) ? $_SESSION['paypal']['nvp']['invoiceId'] : '';

if ($invoiceId == '') {

	if ( isset($_SESSION['webUser']['lastInvoiceId']) && $_SESSION['webUser']['lastInvoiceId'] !== false ) {
	    $invoiceId = $_SESSION['webUser']['lastInvoiceId'];
	}

}

if ( $invoiceId == '' && _GET('token') == '' && _GET('PayerID') == '' ) {
	exit('stop!');
}

# if we have invoice then calculate
if ( $invoiceId != '' ) {
	include_once(__DIR__."/../apiCallsData.php"); // PRODUCTS / TOTAL CALCUATIONS
	define('PPL_CURRENCY_CODE', $nvp_currency_code);

}

if(!defined('PPL_CURRENCY_CODE')){
	define('PPL_CURRENCY_CODE', _SESSION('PPL_CURRENCY_CODE'));
}





global $productClass, $total_price_for_gift_card_to_check, $shipping_country, $three_for_two_cat, $total_coupon_name, $after_coupon_discount;
include_once("paypal.class.php");



// var_dump($nvp_currency_code);


	$paypal= new MyPayPal();
	$paypal->dbF = $dbF;
	$paypal->total_coupon_name = $total_coupon_name;
	$paypal->after_coupon_discount = $after_coupon_discount;
	$paypal->invoiceId = $invoiceId;
	$paypal->total_price_for_gift_card_to_check = $total_price_for_gift_card_to_check;
	$paypal->shipping_country = $shipping_country;
	$paypal->three_for_two_cat = $three_for_two_cat;
	//Post Data received from product list page.
	if(_GET('paypal')=='checkout'){


		
		//-------------------- prepare products -------------------------
		
		//Mainly we need 4 variables from product page Item Name, Item Price, Item Number and Item Quantity.
		
		//Please Note : People can manipulate hidden field amounts in form,
		//In practical world you must fetch actual price from database using item id. Eg: 
		//$products[0]['ItemPrice'] = $mysqli->query("SELECT item_price FROM products WHERE id = Product_Number");
		
		$products = [];
		
		
		
		// set an item via POST request
		
		$products[0]['ItemName'] 	= _POST('itemname'); 	//	Item Name
		$products[0]['ItemPrice'] 	= _POST('itemprice'); 	//	Item Price
		$products[0]['ItemNumber'] 	= _POST('itemnumber'); 	//	Item Number
		$products[0]['ItemDesc'] 	= _POST('itemdesc'); 	//	Item Number
		$products[0]['ItemQty']		= _POST('itemQty'); 	// 	Item Quantity
		
		/*
		$products[0]['ItemName'] = 'my item 1'; //Item Name
		$products[0]['ItemPrice'] = 0.5; //Item Price
		$products[0]['ItemNumber'] = 'xxx1'; //Item Number
		$products[0]['ItemDesc'] = 'good item'; //Item Number
		$products[0]['ItemQty']	= 1; // Item Quantity		
		*/
		/*
		
		// set a second item
		
		$products[1]['ItemName'] = 'my item 2'; //Item Name
		$products[1]['ItemPrice'] = 10; //Item Price
		$products[1]['ItemNumber'] = 'xxx2'; //Item Number
		$products[1]['ItemDesc'] = 'good item 2'; //Item Number
		$products[1]['ItemQty']	= 3; // Item Quantity
		*/		
		
		//-------------------- prepare charges -------------------------
		
		$charges = [];
		
		//Other important variables like tax, shipping cost
		$charges['TotalTaxAmount'] = 0;  //Sum of tax for all items in this order. 
		$charges['HandalingCost'] = 0;  //Handling cost for this order.
		$charges['InsuranceCost'] = 0;  //shipping insurance cost for this order.
		$charges['ShippinDiscount'] = 0; //Shipping discount for this order. Specify this as negative number.
		$charges['ShippinCost'] = 0; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
		
		//------------------SetExpressCheckOut-------------------
		
		//We need to execute the "SetExpressCheckOut" method to obtain paypal token
		global $nvp_products, $nvp_charges;
		// var_dump('$nvp_products',$nvp_products, $nvp_charges);
		// exit();
		// $paypal->SetExpressCheckOut($products, $charges);	



		$_SESSION['PPL_CURRENCY_CODE'] =  PPL_CURRENCY_CODE;

		$paypal->SetExpressCheckOut($nvp_products, $nvp_charges, '0');
	}
	elseif(_GET('token')!=''&&_GET('PayerID')!=''){
		
		//------------------DoExpressCheckoutPayment-------------------		
		
		//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
		//we will be using these two variables to execute the "DoExpressCheckoutPayment"
		//Note: we haven't received any payment yet.
		
		// var_dump(1);
		$httpParsedResponseAr = $paypal->DoExpressCheckoutPayment();
		// var_dump(2);
	    global $functions;


	    $sql = ' SELECT * FROM `order_invoice` WHERE order_invoice_pk = ? ';
	    $row = $dbF->getRow( $sql, array($invoiceId) );

	    // var_dump(1, $invoiceId, $row['orderStatus']);
		// var_dump($invoiceId, $row['orderStatus']);

	    if ( $invoiceId != '' && $row['orderStatus'] != 'process' ) {
	    	// var_dump($invoiceId);
	    	// var_dump($row);
	    	
			// $buyerName  	= $httpParsedResponseAr["FIRSTNAME"].' '.$httpParsedResponseAr["LASTNAME"];
			// $buyerEmail 	= $httpParsedResponseAr["EMAIL"];

		    $payerEmail     = filter_var(urldecode($httpParsedResponseAr["EMAIL"]),     		FILTER_SANITIZE_SPECIAL_CHARS);
		    $payerFirstName = filter_var(urldecode($httpParsedResponseAr["FIRSTNAME"]), 		FILTER_SANITIZE_SPECIAL_CHARS);
		    $payerLastName  = filter_var(urldecode($httpParsedResponseAr["LASTNAME"]),  		FILTER_SANITIZE_SPECIAL_CHARS);
		    $recipientName  = filter_var(urldecode($httpParsedResponseAr["SHIPTONAME"]),		FILTER_SANITIZE_SPECIAL_CHARS);
		    $addressLine1   = filter_var(urldecode($httpParsedResponseAr["SHIPTOSTREET"]), 		FILTER_SANITIZE_SPECIAL_CHARS);
		    $city           = filter_var(urldecode($httpParsedResponseAr["SHIPTOCITY"]),		FILTER_SANITIZE_SPECIAL_CHARS);
		    $state          = filter_var(urldecode($httpParsedResponseAr["SHIPTOSTATE"]),		FILTER_SANITIZE_SPECIAL_CHARS);
		    $postalCode     = filter_var(urldecode($httpParsedResponseAr["SHIPTOZIP"]),			FILTER_SANITIZE_SPECIAL_CHARS);
		    $countryCode    = filter_var(urldecode($httpParsedResponseAr["SHIPTOCOUNTRYCODE"]),	FILTER_SANITIZE_SPECIAL_CHARS);
		    $PayerID    	= filter_var(urldecode($httpParsedResponseAr["PAYERID"]),			FILTER_SANITIZE_SPECIAL_CHARS);
		    $transactionID  = filter_var(urldecode($httpParsedResponseAr["PAYMENTREQUESTINFO_0_TRANSACTIONID"]),		FILTER_SANITIZE_SPECIAL_CHARS);
		    $paymentState   = filter_var(urldecode($httpParsedResponseAr["CHECKOUTSTATUS"]),	FILTER_SANITIZE_SPECIAL_CHARS);
		    $finalAmount    = filter_var(urldecode($httpParsedResponseAr["AMT"]),				FILTER_SANITIZE_SPECIAL_CHARS);
		    $currency    	= filter_var(urldecode($httpParsedResponseAr["CURRENCYCODE"]),		FILTER_SANITIZE_SPECIAL_CHARS);



		    $invoiceStatus  =   '2'; //pending
		    $status         =   'process';

		    $return_info    = " TOKEN:  " 			. urldecode($httpParsedResponseAr["TOKEN"]) . " \n ";
		    $return_info   .= "CHECKOUTSTATUS:  " 	. urldecode($httpParsedResponseAr["CHECKOUTSTATUS"]) . " \n ";
		    $return_info   .= "TIMESTAMP:  " 		. urldecode($httpParsedResponseAr["TIMESTAMP"]) . " \n ";
		    $return_info   .= "PAYERID:  " 			. urldecode($httpParsedResponseAr["PAYERID"]) . " \n ";
		    $return_info   .= "PAYERSTATUS:  " 		. urldecode($httpParsedResponseAr["PAYERSTATUS"]) . " \n ";
		    $return_info   .= "AMT:  " 				. urldecode($httpParsedResponseAr["AMT"]) . " \n ";
		    $return_info   .= "ITEMAMT:  " 			. urldecode($httpParsedResponseAr["ITEMAMT"]) . " \n ";
		    $return_info   .= "SHIPPINGAMT:  " 		. urldecode($httpParsedResponseAr["SHIPPINGAMT"]) . " \n ";
		    $return_info   .= "PAYMENTREQUESTINFO_0_TRANSACTIONID:  " . urldecode($httpParsedResponseAr["PAYMENTREQUESTINFO_0_TRANSACTIONID"]) . " \n ";
		    $return_info   .= "PAYMENTREQUESTINFO_0_ERRORCODE:  " . urldecode($httpParsedResponseAr["PAYMENTREQUESTINFO_0_ERRORCODE"]) . " \n ";

		    $return_base64 = ( base64_encode(serialize($httpParsedResponseAr) ) );


		    $sql = "UPDATE  `order_invoice` SET
		            invoice_status = '$invoiceStatus',
		            orderStatus    = '$status',
		            paymentType    = '1',
		            payment_info   = '{$return_info}',
		            apiReturn      = '{$return_base64}'
		            WHERE order_invoice_pk = '$invoiceId' ";
		    $dbF->setRow($sql);

		    //Deduct Stock qty
		    $functions->require_once_custom('orderInvoice');
		    $orderInvoiceClass  =   new invoice();
		    $returnStatus = $orderInvoiceClass->stockDeductFromOrder($invoiceId,false);
		    if($returnStatus===false){
		        throw new Exception("");
		        return false;
		    }

		    $productClass->insert_user_info( array( 
		            $payerEmail    , 
		            $payerFirstName, 
		            $payerLastName , 
		            $recipientName , 
		            $addressLine1  ,   
		            $state         , 
		            $countryCode   ,    
		            $city          , 
		            $postalCode     
		        )
		    );


		    //Email
		    $_GET['mailId'] = $invoiceId;
		    $msg2 = include(__DIR__.'/../../orderMail.php');

		    $orderIdInvoice 		  =   $functions->ibms_setting('invoice_key_start_with').$invoiceId;
		    $orderIdInvoice 		  =   $dbF->hardWords('ORDERING',false)." ($orderIdInvoice)";
		    $fromName       		  =   $functions->webName;
		    // $order_received_t 		  =   $dbF->hardWords('Your order has been received',false);

		    $mailArray['fromName']    =   $fromName;
		    // $payerEmail = 'nitehawkk@gmail.com'; ## TEMPORARY OVERRIDE, FOR TESTING PURPOSES ONLY.
		    $functions->send_mail($payerEmail,$orderIdInvoice,$msg2,'','',$mailArray);
		    // $functions->send_mail($payerEmail,$orderIdInvoice,$order_received_t,'','',$mailArray);

		    $admin_email = $functions->ibms_setting('Email');
		    $functions->send_mail($admin_email,$orderIdInvoice,$msg2,'','',$mailArray);

			// var_dump($payerEmail, 'DoExpressCheckoutPayment: ', $httpParsedResponseAr );


			$thankT 	= $dbF->hardWords('Thank you for your Order!', false);
			$shipT 		= $dbF->hardWords('Shipping Address', false);
			$transidT 	= $dbF->hardWords('Transaction ID', false);
			$stateT 	= $dbF->hardWords('State', false);
			$totalamountT = $dbF->hardWords('Total Amount', false);
			$returntoT 	= $dbF->hardWords('Return to', false);
			$homepageT 	= $dbF->hardWords('home page.', false);


    include(__DIR__.'/../../header.php');

?>

    </div><!-- for resolving div not closing -->


    <div class="main-content">  <!-- closes in footer -->

        <div class='cart3' >
            <div class="">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <h4>


<?php 

					// if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
						
					// 	echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
					// }
					// elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
						
					// 	echo '<div style="color:red">Transaction Complete, but payment may still be pending! '.
					// 	'If that\'s the case, You can manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
					// }

?>


                        <?php echo($payerFirstName.' '.$payerLastName.', ' . $thankT);?><br/><br/>
                        <?php echo($shipT);  ?>: </h4>
                        <?php echo($recipientName);?><br/>
                        <?php echo($addressLine1);?><br/>
                        <?php echo($city);?><br/>
                        <?php echo($state.'-'.$postalCode);?><br/>
                        <?php echo($countryCode);?><br/>

                        <!-- <h4>Payment ID: <?php echo($paymentID);?> <br/> -->
                		<?php echo($transidT);  ?> : <?php echo($transactionID);?> <br/>
                        <?php echo($stateT);  ?>: <?php echo($paymentState);?> <br/>
                        <?php echo($totalamountT);  ?>: <?php echo($finalAmount);?> &nbsp;  <?php echo($currency);?> <br/>
                    </h4>
                    <br/>
                    <?php echo($returntoT);  ?> <a href="index.php"><?php echo($homepageT);  ?></a>
                </div>
                <div class="col-md-4"></div>
                <div style="clear:both"></div>
            </div>
        <!--content_cart end-->
        </div>

	<?php 

	    # google analytics ecommerce
	    $google_analytics_ecommerce = '<script>';
	    $google_analytics_ecommerce .= $webClass->generate_google_analytics_ecommerce($invoiceId);
	    $google_analytics_ecommerce .= 'ga(\'ecommerce:send\');';
	    $google_analytics_ecommerce .= '</script>';
	    echo $google_analytics_ecommerce;
	?>        

<style>
.cart3 {
    padding: 10px 0px;
}    
</style>


<?php

    include(__DIR__.'/../../footer.php');

	    } // if ( $invoiceId != '' && $row['orderStatus'] != 'process' ) END

    // if (session_id() !== "") {
    //            session_unset();
    //            session_destroy();
    //         }
?>	    


<?php

	}
	else{
		
		//order form
		

	}
