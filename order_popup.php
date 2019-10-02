<?php
ob_start();
global $webClass, $productClass, $_e, $functions; 

$login = false;
if ($webClass->userLoginCheck()) {
    $login = true;
}

?>
<div class="main_side_content">
    <div class="align">

        <div class="main_side_content_inner">

            <div class="content_1"> <?php echo $_e['SUMMARY']; ?> </div>

            <div class="cart3" id="ordered_prices">
                   

            </div>


            <?php # IN YOUR CART
                //$box = $webClass->getBox('box9');
    /*
            
            <div class="content_1">
                //echo $box['heading'];
            </div>
            <!-- content_1 close -->

            <div class="main_side_text">
                 //echo $box['text']; 
            </div>
            <!-- main_side_text close -->
    */
            ?>
            
            
            <?php # ADDITIONAL BOX
                $box = $webClass->getBox('box14');
            ?>
            
            <div class="content_1">
                <?php echo $box['heading']; ?>
            </div>
            <!-- content_1 close -->

            <div class="main_side_text">
                <?php echo $box['text']; ?>
            </div>
            <!-- main_side_text close -->


            <div class="box_1" id="order_status_message" style="display: none;">
                
            </div>
            <!-- box_1 close -->
            

            <div class="cart_2" id="ordered_products_area">
                
<?php 

    /*

                <div class="cart_1_inner">
                    <div class="cart_1_inner_1">
                        <a href="#">
                            <img src="images/ajax/product/2016/05/2317_671_thunderhomepage.jpg" alt="">
                        </a>
                    </div>
                    <!-- cart_1_inner_1 close -->
                    <div class="cart_1_inner_2 left_side">
                        <h3>CE-Godkänd Skyddsjacka</h3>
                        <div class="info_main">
                            <div class="info_1">Storlek:<span>M</span></div>
                            <!-- info_1 close -->
                            <div class="info_1">Farg:<span style="background: #050005;padding: 2px 9px;color: #fff;font-size: 11px;border-radius: 50%;width: 10px;height: 16px;margin-left: 10px;"></span></div>
                            <div class="info_1">Antal:<span>1 @ 599</span><span>SEK</span></div>
                            <div class="info_1">599<span>SEK</span></div>
                            <!-- info_1 close -->
                        </div>
                        <!-- info_main close -->
                    </div>
                    <!-- cart_1_inner_2 close -->
                </div>
                <!-- cart_1_inner close -->


                <div class="cart_1_inner last_child">
                    <div class="cart_1_inner_1">
                        <a href="#">
                            <img src="images/ajax/product/2016/05/2317_671_thunderhomepage.jpg" alt="">
                        </a>
                    </div>
                    <!-- cart_1_inner_1 close -->
                    <div class="cart_1_inner_2">
                        <h3>CE-Godkänd Skyddsjacka</h3>
                        <div class="info_main">
                            <div class="info_1">Storlek:<span>M</span></div>
                            <!-- info_1 close -->
                            <div class="info_1">Farg:<span style="background: #050005;padding: 2px 9px;color: #fff;font-size: 11px;border-radius: 50%;width: 10px;height: 16px;margin-left: 10px;"></span></div>
                            <div class="info_1">Antal:<span>1 @ 599</span><span>SEK</span></div>
                            <div class="info_1">599<span>SEK</span></div>
                            <!-- info_1 close -->
                        </div>
                        <!-- info_main close -->
                    </div>
                    <!-- cart_1_inner_2 close -->
                </div>
                <!-- cart_1_inner close -->


    */

?>

            </div>


        </div>
        <!-- main_side_content_inner close -->

        <div class="content_2">
            <div class="cart3 cart_set">
                
                <div id="first_option" class="option1 option3 wow fadeInLeft">1. <?php echo $_e['Payment Option'] ?></div>
                
                <div class="area_form3 wow fadeInUp">
                    <div style="display:none" class="bill_text"><?php echo $_e['Billing Country']; ?></div>
                    <input type="hidden" class="drop_drop" disabled="" readonly="" value="SWEDEN">
                    <div style="display:none" class="method_type wow fadeInLeft">
                        <?php echo $_e['Payment Type']; ?>
                    </div>
                    <!--method_type end-->
                    

                    <?php 
                        // if ($productClass->cartInvoice) {
                            echo "<input type='hidden' id='invoiceId' value='" . $_GET['inv'] . "'/>";
                        // }
                    ?>


                    <div class="paymentOptions">
                            <!--Credit Cart Option not develop now-->
                            <!--<div class="border radio">
                                        <label><input type="radio" name="paymentType" value="3" class="paymentOptionRadio"><?php /*echo $productClass->productF->paymentArrayFindWeb('3'); */
                            ?> </label>
                                        <img src="images/creditcard.png" class="pull-right"/>
                                        <div class="clearfix"></div>
                                    </div>-->

                            <?php

                            $country = $productClass->currentCountry();

                            $AllowKlarna = false;
                            //check country , kalrna not allow in some country as a payment method
                            //allow in sweden, norway and Finland
                            if ($functions->developer_setting('klarna') == '1' && preg_match('@SE|NO|FI@', $country)) {
                                $AllowKlarna = true;
                                ?>
                                <!--Klarna Option-->
                                <div class="border radio">
                                    <label><input type="radio" name="paymentType" value="2"
                                                  class="paymentOptionRadio" checked="checked" 
                                        ><?php echo $_e['Klarna = Faktura, Delbetalning, Kort & Internetbank'];
                                        // echo $productClass->productF->paymentArrayFindWeb('2');
                                        echo $productClass->payment_additional_price("2");
                                        ?>
                                    </label>
                                    <img src="images/klarna.png" class="pull-right"/>

                                    <div class="clearfix"></div>
                                </div>

                                <style>
                                    .cart_set {
                                        display: none !important;
                                    }
                                </style>

                            <?php } ?>

                            <?php
                            $AllowPaypal = false;
                            //check country , payson not allow in some country as a payment method
                            if ($login && $functions->developer_setting('paypal') == '1') {
                                $AllowPaypal = false;
                                ?>
                                <!--PayPal Option-->
                                <div class="border radio">
                                    <label><input type="radio" name="paymentType" value="1"
                                                  class="paymentOptionRadio">
                                        <?php
                                            echo $productClass->productF->paymentArrayFindWeb('1');
                                            echo $productClass->payment_additional_price("1");
                                        ?>
                                    </label>
                                    <img src="images/paypal.png" class="pull-right"/>

                                    <div class="clearfix"></div>
                                </div>
                            <?php } ?>

                            <?php
                            $AllowPayson = false;
                            //check country , payson not allow in some country as a payment method
                            //allow in denmark
                            if ( $functions->developer_setting('payson') == '1' && preg_match('@DK@', $country) ) {
                                $AllowPayson = true;
                                ?>
                                <!--PayPal Option-->
                                <div class="border radio">
                                    <label><input type="radio" name="paymentType" value="5"
                                                  class="paymentOptionRadio">
                                        <?php
                                        echo $productClass->productF->paymentArrayFindWeb('5');
                                        echo $productClass->payment_additional_price("5");
                                        ?>

                                    </label>
                                    <div class="clearfix"></div>
                                </div>
                            <?php } ?>

                            <?php
                            $cashOnDelivery = false;
                            //check country , cashOnDelivery not allow in some country as a payment method
                            // allow in sweden and norway
                            if (
                                ($login || $functions->ibms_setting('loginForOrder') == '0')
                                && $functions->developer_setting('cashOnDelivery') == '1'
                                && preg_match('@SE|NO@', $country)
                            ) { 
                                $cashOnDelivery = true;
                                ?>
                                <!--Cash on delivery Option-->
                                <div class="border radio">
                                    <label><input type="radio" name="paymentType" value="0"
                                                  class="paymentOptionRadio">
                                        <?php
                                        echo $productClass->productF->paymentArrayFindWeb('0');
                                        echo $productClass->payment_additional_price("0");
                                        ?>
                                    </label>

                                    <div class="clearfix"></div>
                                </div>
                            <?php } ?>


                    </div><!--paymentOptions end-->


                    <div class="button_area wow fadeInLeft">

<?php if (!preg_match('@SE@', $country) && $functions->developer_setting('paypal_nvp') == '1' ): ?>
                    <form method="post" action="process.php?paypal=checkout" id="paypal_form" >
                        <?php $_SESSION['paypal']['nvp']['invoiceId'] = $invoiceId; ?>
                        <input type="hidden" name="order" value="<?php echo $invoiceId; ?>" >
                        <span class="paypal-button-widget">
                            <button class="paypal-button paypal-style-checkout paypal-color-gold paypal-size-small paypal-shape-pill en_US" type="submit" >
                                <span class="paypal-button-logo"><img src="images/button_logo.svg"></span>
                                <span class="paypal-button-content"><img src="images/paypal.svg" alt="PayPal">
                                    <span> Check out</span>
                                </span>
                                <br>
                                <span class="paypal-button-tag-content">The safer, easier way to pay</span>
                            </button>
                        </span>                      
                    </form>
<?php endif; ?>
                            
                        <div class="req2"></div>
                        <input type="submit" id="paymentOptionNext" value="<?php echo $_e['NEXT STEP']; ?>" class="check_btn2">
                    </div>
                    <!--btn_area end-->
                </div>
            </div>


<style type="text/css">
    <?php $functions->includeOnceCustom('css/paypal.css'); ?>
#paypal_form {
    display: inline-block;
}
</style>


            <!-- <h3>Din order Laddas upp i snabbkassan.Var god droj.</h3> -->

            <div id='cartContinue' class='cart3'>
                <?php
                        // $_GET['inv'] = 6223;
                        $_GET['ajax'] = "a";
                        echo "<div class='klarna_container'> ";
                        try {

                            if ($AllowKlarna) {

                                if (isset($product_ajax_function)) {
                                    $this->functions->includeOnceCustom('klarna.php');
                                } else {
                                    $functions->includeOnceCustom('klarna.php');

                                }

                            }

                            // $this->includeOnceCustom('cartContinue.php');
                            // include_once('klarna.php');
                            // include_once('cartContinue.php');
                        } catch (Exception $e) {
                            
                        }
                        echo "</div";
                    ?>
            </div>

        </div><!-- content_2 close -->

    </div>
    <!-- align close -->

</div>
<!-- main_side_content close -->


            <!--</div>
             main_side_text close -->


<?php 

if( isset($AllowKlarna) && $AllowKlarna == false && $AllowPaypal == false && $AllowPayson == false && $cashOnDelivery == false && $functions->developer_setting('paypal_nvp') == '1' ):

?>

<script>
    $('#paymentOptionNext').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        $('button.paypal-button').click();
    });
</script>

<?php endif; ?>



<?php

return ob_get_clean(); ?>