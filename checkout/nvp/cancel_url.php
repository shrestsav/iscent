<?php
    /*
        * Cancel Order page
    */
include(__DIR__."/../../global.php");
global $webClass;
global $productClass, $functions, $productF;

$productF = new product_function();

    // var_dump($_SESSION);
    // var_dump($_POST);
    // var_dump($_GET);



    $token     = filter_input( INPUT_GET, 'token', FILTER_SANITIZE_STRING );
    $token_str = ( $token == '' ) ? '' : 'token:'. $token;

    $status          = 'canceled';
    $info            = "PayPal Transaction cancelled by user";
    $invoiceId       = $_SESSION['webUser']['lastInvoiceId'];
    $invoiceStatus   = '0'; //cancel


    # check for cancelled order, and stop update again and invoice_record insert
    $dbF->getRow(' SELECT `orderStatus` FROM `order_invoice` WHERE `order_invoice_pk` = ? AND `orderStatus` = ?  ', array( $invoiceId, $status ) );
    if( $dbF->rowCount == 0 ) {
        $sql = "UPDATE  `order_invoice` SET
                            invoice_status  = ?,
                            orderStatus     = ?,
                            paymentType     = ?,
                            payment_info    = ?
                            WHERE order_invoice_pk = ? ";
        $dbF->setRow($sql, array($invoiceStatus, $status, 1, $info, $invoiceId) );
        // # invoice status = 0 means cancel invoice
        // $dbF->setRow(' UPDATE `order_invoice` SET `invoice_status` = ? ', array(1) );
        if( $dbF->rowCount > 0 ) {
            $productF->set_order_invoice_record($invoiceId, 'paypal_order_status', 'cancelled', 0, $token_str);
        }
    }

include(__DIR__ . "/../../header.php");

?>
    </div><!-- for resolving div not closing -->


    <div class="main-content">  <!-- closes in footer -->

        <div class='cart3' >
            <div class="">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="alert alert-danger" role="alert">                    
                        <p class="text-center"><strong><?php $dbF->hardWords('You cancelled the order.'); ?></strong></p>
                    </div>

                    <a href="<?php echo WEB_URL ?>"><?php $dbF->hardWords('Return to home page.'); ?></a>

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


<?php include(__DIR__ . "/../../footer.php"); ?>