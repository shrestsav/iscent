<?php
    /*
        * Payment Confirmation page : has call to execute the payment and displays the Confirmation details
    */
    if (session_id() == "")
        session_start();

    include('utilFunctions.php');
    include('paypalFunctions.php');

    //Express checkout flow
    // var_dump($_SESSION);
    if(verify_nonce()){
        $_SESSION['paymentID']  = filter_input( INPUT_GET, 'paymentId', FILTER_SANITIZE_STRING );
        $_SESSION['payerID']    = filter_input( INPUT_GET, 'PayerID', FILTER_SANITIZE_STRING );

        $response = doPayment($_SESSION['paymentID'], $_SESSION['payerID'], NULL);
    } else {
        die('Session expired');
    }
	
	// REST validation; route non-HTTP 200 to error page
	if ($response['http_code'] != 200 && $response['http_code'] != 201) {		
		$_SESSION['error'] = $response;
		header( 'Location: show_error.php');
		
		// need exit() here to maintain session data after redirect to error page
		exit();
	}
	
	$json_response  = $response['json']; 
    // echo "<pre>";
    // print_r($json_response);
    // echo "</pre>";	
    $paymentID      = $json_response['id'];
    $paymentState   = $json_response['state'];
    $finalAmount    = $json_response['transactions'][0]['amount']['total'];
    $currency       = $json_response['transactions'][0]['amount']['currency'];
    $transactionID  = $json_response['transactions'][0]['related_resources'][0]['sale']['id'];
    // $invoiceNumber  = filter_var($json_response['transactions'][0]['payee']['invoice_number'],FILTER_SANITIZE_SPECIAL_CHARS);

    $payerEmail     = filter_var($json_response['payer']['payer_info']['email'],FILTER_SANITIZE_SPECIAL_CHARS);
    $payerFirstName = filter_var($json_response['payer']['payer_info']['first_name'],FILTER_SANITIZE_SPECIAL_CHARS);
    $payerLastName  = filter_var($json_response['payer']['payer_info']['last_name'],FILTER_SANITIZE_SPECIAL_CHARS);
    $recipientName  = filter_var($json_response['payer']['payer_info']['shipping_address']['recipient_name'],FILTER_SANITIZE_SPECIAL_CHARS);
    $addressLine1   = filter_var($json_response['payer']['payer_info']['shipping_address']['line1'],FILTER_SANITIZE_SPECIAL_CHARS);
    $addressLine2   = (isset($json_response['payer']['payer_info']['shipping_address']['line2']) ? filter_var($json_response['payer']['payer_info']['shipping_address']['line2'],FILTER_SANITIZE_SPECIAL_CHARS) :  "" );
    $city           = filter_var($json_response['payer']['payer_info']['shipping_address']['city'],FILTER_SANITIZE_SPECIAL_CHARS);
    $state          = filter_var($json_response['payer']['payer_info']['shipping_address']['state'],FILTER_SANITIZE_SPECIAL_CHARS);
    $postalCode     = filter_var($json_response['payer']['payer_info']['shipping_address']['postal_code'],FILTER_SANITIZE_SPECIAL_CHARS);
    $countryCode    = filter_var($json_response['payer']['payer_info']['shipping_address']['country_code'],FILTER_SANITIZE_SPECIAL_CHARS);


    include_once('../global.php');
    global $functions;

    $invoiceId      = $_SESSION['webUser']['lastInvoiceId'];
    $invoiceStatus  =   '2'; //pending
    $status         =   'process';

    $sql = "UPDATE  `order_invoice` SET
            invoice_status = '$invoiceStatus',
            orderStatus = '$status',
            paymentType = '1'
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
        $msg2 = include(__DIR__.'/../orderMail.php');


        $orderIdInvoice =   $functions->ibms_setting('invoice_key_start_with').$invoiceId;
        $orderIdInvoice =   $dbF->hardWords('ORDERING',false)." ($orderIdInvoice)";
        $fromName       =   $functions->webName;

        $mailArray['fromName']    =   $fromName;
        $functions->send_mail($payerEmail,$orderIdInvoice,$msg2,'','',$mailArray);






    // $storeId = $productClass->getStoreId();

    // $sql   = " SELECT `order_invoice_product`.* FROM `order_invoice_product` 
    //            LEFT OUTER JOIN order_invoice ON `order_invoice_product`.`order_invoice_id` = `order_invoice`.`order_invoice_pk`
    //            WHERE `order_invoice_id` = ? ";
    // $rows  = $dbF->getRows($sql,array($invoiceId));

    // foreach ( $rows as $row ) {

    //     $pids      = $row['order_pIds'];
    //     $pids      = explode("-",$pids);
    //     $pId       = $pids[0];
    //     $scaleId   = $pids[1];
    //     $colorId   = $pids[2];
    //     $storeId   = $pids[3];
    //     $customId  = $pids[4];
    //     $sale_qty  = $row['order_pQty'];

    //     $result    = $productClass->RemoveQtyFromInStock($pId,$scaleId,$colorId,$storeId,$sale_qty);

    // }

    include('../header.php');

?>

    </div><!-- for resolving div not closing -->


    <div class="main-content">  <!-- closes in footer -->

        <div class='cart3' >
            <div class="">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <h4>
                        <?php echo($payerFirstName.' '.$payerLastName.', Thank you for your Order!');?><br/><br/>
                        Shipping Address: </h4>
                        <?php echo($recipientName);?><br/>
                        <?php echo($addressLine1);?><br/>
                        <?php echo($addressLine2);?><br/>
                        <?php echo($city);?><br/>
                        <?php echo($state.'-'.$postalCode);?><br/>
                        <?php echo($countryCode);?>

                        <h4>Payment ID: <?php echo($paymentID);?> <br/>
                Transaction ID : <?php echo($transactionID);?> <br/>
                        State : <?php echo($paymentState);?> <br/>
                        Total Amount: <?php echo($finalAmount);?> &nbsp;  <?php echo($currency);?> <br/>
                    </h4>
                    <br/>
                    Return to <a href="index.php">home page</a>.
                </div>
                <div class="col-md-4"></div>
                <div style="clear:both"></div>
            </div>
        <!--content_cart end-->
        </div>

<style>
.cart3 {
    padding: 10px 0px;
}    
</style>


<?php
    // if (session_id() !== "") {
    //            session_unset();
    //            session_destroy();
    //         }
    include('../footer.php');
?>