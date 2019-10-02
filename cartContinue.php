<?php include_once("global.php");
global $webClass,$productClass,$dbF,$db,$functions;

$paymentOption = 0; // cash on delivery
if(isset($_GET['paymentOption'])){
    $paymentOption = $_GET['paymentOption'];
}

$paymentSelectOption = $productClass->productF->paymentSelectWeb();
$invoiceStatus  = $productClass->productF->invoiceStatus();
$country_list   = $productClass->functions->countrySelectOption();
$token          = $productClass->functions->setFormToken('WebOrderReady',false);

$storeCountry   = $productClass->currentCountry();

$orderUser      = webUserId();
if($orderUser   =='0'){
    $orderUser  = webTempUserId();
}

$invoiceId = "";
if(isset($_GET['inv'])){
    $invoiceId  =   $_GET['inv'];
    $sql        = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$invoiceId' AND orderUser = '$orderUser'";
    $orderInvoice   =   $dbF->getRow($sql);
    if(!$dbF->rowCount){
        echo "Order Invoice Not Found. Please Refresh page And Try Again.";
        exit;
    }
    $country_list   =   $functions->countrylist();
    $storeCountry   =   $orderInvoice['shippingCountry'];
    $countryName    =   $country_list[$storeCountry];
}


$nameT = $dbF->hardWords('Name',false);
$lastT = $dbF->hardWords('Last Name',false);
$phoneT = $dbF->hardWords('Phone',false);
$emailT = $dbF->hardWords('E-mail',false);
$addressT = $dbF->hardWords('Address',false);
$CityT = $dbF->hardWords('City',false);
$CountryT = $dbF->hardWords('Country',false);

$paymentInfoT = $dbF->hardWords('Payment info',false);
$EpaymentInfoT = $dbF->hardWords('Enter Payment Information',false);

$senderNReceiverT = $dbF->hardWords('Sender And Reciever Information',false);
$iamSenderT = $dbF->hardWords('I am sender and friend is receiver',false);
$friendReceiverT = $dbF->hardWords('I am Sender And Friend is receiver',false);
$senderInfoT = $dbF->hardWords('Sender Information',false);
$receiverInfoT = $dbF->hardWords('Receiver Information',false);
$RnameT = $dbF->hardWords('Receiver Name',false);
$RphoneT = $dbF->hardWords('Receiver Phone',false);
$RemailT = $dbF->hardWords('Receiver Email',false);
$RaddressT = $dbF->hardWords('Receiver Address',false);
$RCityT = $dbF->hardWords('Receiver City',false);
$RCountryT = $dbF->hardWords('Receiver Country',false);

$orderT = $dbF->hardWords('ORDER',false);
$orderFormT = $dbF->hardWords('ORDER FORM',false);

$deliveryT = $dbF->hardWords('DELIVERY',false);
$zipcodeT = $dbF->hardWords('ZIP CODE',false);
$nextstepT = $dbF->hardWords('NEXT STEP',false);
$standardT = $dbF->hardWords('Standard',false);
$expressT = $dbF->hardWords('Express',false);
$receiverZipCodeT = $dbF->hardWords('Receiver - Zip Code',false);
$billing_address_sameT = $dbF->hardWords('My delivery address is the same as my billing address.',false);

$deliverymethodT = $dbF->hardWords('SELECT DELIVERY METHOD',false);

$link = WEB_URL;
// var_dump($orderUser);

//User INFO Variables
// if(!is_int($orderUser)){
//   $orderUser = '';
// }





$sql        = "SELECT * FROM accounts_user WHERE acc_id = '$orderUser'";
$userData   =   $dbF->getRow($sql);

$sql        = "SELECT * FROM accounts_user_detail WHERE id_user = '$orderUser'";


$userInfo   = $dbF->getRows($sql);

$uName      =   $userData['acc_name'];
$uEmail     =   $userData['acc_email'];
$uAddress   =   $webClass->webUserInfoArray($userInfo,'address');
$uPhone     =   $webClass->webUserInfoArray($userInfo,'phone');
$uPost      =   $webClass->webUserInfoArray($userInfo,'post_code');
$uCity      =   $webClass->webUserInfoArray($userInfo,'city');
$uCountry   =   $webClass->webUserInfoArray($userInfo,'country');

//if giftcard
$giftcardId = '';
if($paymentOption=='6'){
    @$giftcardId = $_GET['giftId'];
}


echo <<<HTML


<div class='area_form'>

<form method='post' action='orderInvoice.php' >
$token
    <div class='form1 wow fadeInUp' id="sender_area" >


        <input type='hidden' id='priceCode' name='priceCode'/>
        <input type='hidden' id='paymentOption' name='paymentOption' value='$paymentOption'/>
        <input type='hidden' id='giftCardId' name='giftCardId' value='$giftcardId'/>
        <input type='hidden' id='' name='invoiceId' value='$invoiceId'/>
        <input type='hidden' id='storeCountry' name='storeCountry' value='$storeCountry'>

        <input type='hidden' id='paymentInfo' name='paymentInfo' value='$storeCountry'>

        <input type='hidden' name='order_form' value='invoice'>


    <input id="billing_address_check" type="checkbox" checked='checked' name="senderOrReceiver" class="senderOrReceiver iAmReceiver" data-id="iAmReceiver" value="iAmReceiver">
    <label for="billing_address_check">{$billing_address_sameT}</label>
    
    <div class="navbar-inverse bg-black" style="color:#fff">$senderInfoT</div>

        <div class='form_fill'>
         <div class='form_text'>$nameT<span>*</span></div>
        <input type="text" class="form_field" value="$uName" name="sender_name" id="sender_name" required="" />
        </div><!--form_fill end-->

        <div class='form_fill'>
         <div class='form_text'>$emailT<span>*</span></div>
         <input type="email" class="form_field" value="$uEmail" name="sender_email" id="sender_email" />
        </div><!--form_fill end-->

        <div class='form_fill'>
         <div class='form_text'>$addressT<span>*</span></div>
         <textarea class="form_field" name="sender_address" id="sender_address" required >$uAddress</textarea>
        </div><!--form_fill end--> 

        <div class='form_fill'>
         <div class='form_text'>$zipcodeT<span>*</span></div>
         <input type="text" class="form_field" name="sender_post" id="sender_post" required="" value="$uPost" />
        </div><!--form_fill end--> 

        <div class='form_fill'>
         <div class='form_text'>$CityT<span>*</span></div>
         <input type="text" class="form_field" name="sender_city" id="sender_city" value="$uCity" required />
        </div><!--form_fill end--> 

        <div class='form_fill'>
        <div class='form_text'>$CountryT<span>*</span></div>
        <fieldset class="sender_countryFieldset">
           <input type='text' class="form_field" readonly id="sender_country" name="sender_country" value='$storeCountry' class=''/>
        </fieldset>
        </div><!--form_fill end--> 

        <div class='form_fill'>
         <div class='form_text'>$phoneT<span>*</span></div>
         <input type="text" class="form_field" name="sender_phone" id="sender_phone" value="$uPhone" required />
        </div><!--form_fill end-->     


    </div><!--form1 end-->
     
    <br>

    <div class='form1 wow fadeInUp' id="receiver_area" style='display: none;' >
        <div class="navbar-inverse bg-black" style="color:#fff">$receiverInfoT</div>



        <div class='form_fill'>
         <div class='form_text'>$RnameT<span>*</span></div>
        <input type="text" class="form_field" value="$uName" name="receiver_name" id="receiver_name" required="" />
        </div><!--form_fill end-->

        <div class='form_fill'>
         <div class='form_text'>$RemailT<span>*</span></div>
         <input type="email" class="form_field" value="$uEmail" name="receiver_email" id="receiver_email" />
        </div><!--form_fill end-->

        <div class='form_fill'>
         <div class='form_text'>$RaddressT<span>*</span></div>
         <textarea class="form_field" name="receiver_address" id="receiver_address" required ></textarea>
        </div><!--form_fill end--> 

        <div class='form_fill'>
         <div class='form_text'>$receiverZipCodeT<span>*</span></div>
         <input type="text" class="form_field" name="receiver_post" id="receiver_post" required="" value="$uPost" />
        </div><!--form_fill end--> 

        <div class='form_fill'>
         <div class='form_text'>$RCityT<span>*</span></div>
         <input type="text" class="form_field" name="receiver_city" id="receiver_city" value="$uCity" required />
        </div><!--form_fill end--> 

        <div class='form_fill'>
        <div class='form_text'>$RCountryT<span>*</span></div>
        <fieldset class="sender_countryFieldset">
           <input type='text' class="form_field" readonly id="receiver_country1" name="receiver_country" value='$storeCountry' class=''/>
        </fieldset>
        </div><!--form_fill end--> 

        <div class='form_fill'>
         <div class='form_text'>$RphoneT<span>*</span></div>
         <input type="text" class="form_field" name="receiver_phone" id="receiver_phone" value="$uPhone" required />
        </div><!--form_fill end-->    



    </div><!--form1 end-->
     

    <div class='button_area wow fadeInLeft' >
        <button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER" class="submit btn themeButton btn-lg">
            $orderT
        </button>
    </div><!--btn_area end-->

</form>
     
    </div><!--area_form end-->

    <script>
$('#billing_address_check').change(function(event) {
/* Act on the event */

 if ( $(this).is(':checked') ) {
    $('#receiver_area').hide('slow');
 } else {
    $('#receiver_area').show('slow');
 };

});
    </script>

HTML;



















<<<ABCD
<h3>$orderFormT</h3>
<br>
    <form method="post" class="form-horizontal" role="form">
        $token
        <input type="hidden" id="priceCode" name="priceCode"/>
        <input type="hidden" id="paymentOption" name="paymentOption" value="$paymentOption"/>
        <input type="hidden" id="giftCardId" name="giftCardId" value="$giftcardId"/>
        <input type="hidden" id="" name="invoiceId" value="$invoiceId"/>
        <input type="hidden" id="storeCountry" name="storeCountry" value="$storeCountry">

        <div class="form-horizontal">
        <div class="col-md-12">
            <div class="col-md-6">

                <div class="form-group">
                    <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$paymentInfoT</label>
                    <div class="col-sm-10 col-md-9">
                        <textarea name="paymentInfo" id="paymentInfo" class="form-control" placeholder="$EpaymentInfoT"></textarea>
                    </div>
                </div>

            </div><!-- First col-md-6 end -->
        </div>

<div class="clearfix"></div>
            <div class="container-fluid clearfix">
                <h3 class="navbar-inverse bg-black text-center" >$senderNReceiverT</h3>
                <div class="form-group col-md-12">
                    <label class="radio-inline senderOrReceiver_label">
                      <input type="radio" checked name="senderOrReceiver" class="senderOrReceiver iAmReceiver" data-id="iAmReceiver" value="iAmReceiver">$iamSenderT
                    </label>

                    <label class="radio-inline senderOrReceiver_label">
                      <input type="radio"  name="senderOrReceiver" class="senderOrReceiver" data-id="iAmSender" value="receiverFriend"> $friendReceiverT
                    </label>

                </div>
                <div class="col-sm-6">
                    <div class="navbar-inverse bg-black" style="color:#fff">$senderInfoT</div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$nameT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" value="$uName" name="sender_name" id="sender_name" required="" placeholder="$nameT"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$emailT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="email" class="form-control" value="$uEmail" name="sender_email" id="sender_email" placeholder="$emailT"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$addressT</label>
                            <div class="col-sm-10 col-md-9">
                                <textarea class="form-control" name="sender_address" id="sender_address" required placeholder="$addressT">$uAddress</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">Postnummer</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" name="sender_post" id="sender_post" required="" value="$uPost" placeholder="Postnummer"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$CityT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" name="sender_city" id="sender_city" value="$uCity" required placeholder="$CityT"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$CountryT</label>
                            <div class="col-sm-10 col-md-9">
                                <fieldset class="sender_countryFieldset">
                                   <input type='text' readonly id="sender_country" name="sender_country" value='$storeCountry' class='form-control'/>
                                </fieldset>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$phoneT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" name="sender_phone" id="sender_phone" value="$uPhone" required placeholder="$phoneT"/>
                            </div>
                        </div>

                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 sender info end -->

                <div class="col-sm-6" id="receiverDiv">
                    <div class="navbar-inverse bg-black" style="color:#fff">$receiverInfoT</div>
                    <div class="col-sm-12">

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$nameT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" value="$uName" class="form-control" name="receiver_name" id="receiver_name" required="" placeholder="$RnameT"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$emailT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="email" value="$uEmail" class="form-control" name="receiver_email" id="receiver_email" placeholder="$RemailT"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$addressT</label>
                            <div class="col-sm-10 col-md-9">
                                <textarea class="form-control" name="receiver_address" id="receiver_address" required placeholder="$RaddressT">$uAddress</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">Postnummer</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" value="$uPost" class="form-control" name="receiver_post" id="receiver_post" required="" placeholder="Mottagare - Postnummer"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$CityT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" value="$uCity" class="form-control" name="receiver_city" id="receiver_city" required placeholder="$RCityT"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$CountryT</label>
                            <div class="col-sm-10 col-md-9">
                                <fieldset class="receiver_countryFieldset">
                                    <!--<input type='text' readonly id="receiver_country" name="receiver_country" value='$storeCountry' class='form-control'/>-->
                                    <input type='text' readonly id="receiver_country1" name="receiver_country" value='$storeCountry' class='form-control'/>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">$phoneT</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" value="$uPhone" name="receiver_phone" id="receiver_phone" required placeholder="$RphoneT"/>
                            </div>
                        </div>


                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 receiver info end -->

            </div> <!-- Send Receiver Info-->

            <div class="clearfix"></div>

        <div class="clearfix"></div>
        <br>
            <div class="container-fluid ReviewButtons">
                <button type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER" class="submit btn themeButton btn-lg">$orderT</button>
            </div>
        <div class="clearfix"></div>
        <br/>
ABCD;
<<<ABC
				<!-- if you change value of button then must change from addNewOrder(); -->

         </div> <!-- added product script div end -->

         </div> <!-- form-horizontal end -->
       </form>
       </div>
ABC;

?>

<script>
    $('.senderOrReceiver').change(function(){
        info = $(this).attr('data-id');
        senderOrReceiver(info);
    });

    $('#sender_name,#sender_phone,' +
        '#sender_email,#sender_city,' +
        '#sender_country,#sender_post,#sender_address').bind('keyup change',function(){
            if($('.iAmReceiver').is(':checked')){
                setTimeout(senderOrReceiver('iAmReceiver'),500);
            }
        });


    senderOrReceiver = function(order){
        if(order=='iAmReceiver'){
            $('#receiver_name').val($('#sender_name').val());
            $('#receiver_phone').val($('#sender_phone').val());
            $('#receiver_email').val($('#sender_email').val());
            $('#receiver_city').val($('#sender_city').val());
            $('#receiver_country').val($('#sender_country').val()).trigger('change');
            $('#receiver_post').val($('#sender_post').val());
            $('#receiver_address').val($('#sender_address').val());
        }else{
            $('#receiver_name,#receiver_phone,' +
                '#receiver_email,#receiver_city,' +
                '#receiver_country,#receiver_address,receiver_address').val('').change();
        }
    };

    finalPrice = function(){
// final price before order submit
        if(formSubmit()){

            storeCountry = $('#storeCountry').val();
            deliverCountry = $('#receiver_country').val();
            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_FOLDER; ?>/order/order_ajax.php?page=shippingPrice",
                data: { storeCountry:storeCountry,deliverCountry:deliverCountry},
                success: function (response) {
                    shippingData = ($.parseJSON(response)); // receiveing json

                    if(shippingData.find=='1'){
                        shp_int      = shippingData.shp_int;
                        shp_weight   = shippingData.shp_weight;
                        shp_price    = shippingData.shp_price;


                        jAlertifyAlert('<?php $dbF->hardWords('It Looks Like Shipping Stop In Receiving Country'); ?> '+shp_int,'<?php $dbF->hardWords('Shipping Error'); ?>');
                            return false;
                        }
                        $('.topViewP').fadeIn(500);
                    if(shp_int=='0'){
                        if($.isNumeric( shp_price )){
                            var orderPrice = parseFloat($('.pGrandTotal ').text());

                            var weight  = parseFloat($('.totalWeightInput').val());
                            var unitWeight = Math.ceil(weight/shp_weight);

                            var shippingPrice = parseFloat(shp_price);
                            shippingPrice     = eval(shippingPrice*unitWeight);

                            var total = eval(orderPrice+shippingPrice);
                            var priceCode =$('#priceCode').val();

                            $('.totalPriceModel').text(orderPrice+" "+priceCode);
                            $('.totalPriceShipping').text(shippingPrice+" "+priceCode);
                            $('.totalFinal').text(total+" "+priceCode);

                            $('#totalPrice').val(orderPrice);
                            $('#totalWeight').val(weight);
                        }
                    }else{
                        jAlertifyAlert('<?php $dbF->hardWords('It Looks Like Shipping Stop In Receiving Country'); ?>','<?php $dbF->hardWords('Shipping Error'); ?>');

                    }
                }
            });
        }
    };

    function formSubmit(){
        if(!isInternationalShipping()){
            return false;
        }

        payment = $("#paymentTypeSelect").val();
        if(payment==""){
            notification("<?php $dbF->hardWords('Payment Info'); ?>","<?php $dbF->hardWords('Payment Type not selected'); ?> <br> <?php $dbF->hardWords('Before continuing, please select payment type.'); ?>","btn-warning");
            return false;
        }

        return true;
    }
    function isInternationalShipping(){
        chk=true;
        receiverCountry = $("#receiver_country").val();
        storeCounntry = $("#storeCountry").val();

        $('.interShipping').each(function(i,data){
            $(this).closest('tr').find('td').removeClass('btn-danger cartProductHighLight');
            if(receiverCountry==storeCounntry){
                return true;
            }else if($(this).val() == '0'){
                tr=$(this).closest('tr').find('td').addClass('btn-danger cartProductHighLight');
                chk=false;
            }
        });
        if(!chk){
            notification("<?php $dbF->hardWords('Shipping Error'); ?>","<?php $dbF->hardWords('Highlighted Product Are not shipped in receiver country.'); ?>","btn-danger");
            return false;
        }
        return chk;
    }
    $('.topViewP .topViewClose,.topViewP .topViewCloseX').click(function(){
        $('.topViewP').fadeOut(500);
    });

</script>