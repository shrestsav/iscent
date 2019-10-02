<?php

// if (session_id() !== "") {
//        session_unset();
//        session_destroy();
//     }
//     session_start();
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
/*
    * Data for REST API calls.
    * $_SESSION['expressCheckoutPaymentData'] is used in the Express Checkout flow
    * $_SESSION['markFlowPaymentData'] is used for the Proceed to Checkout/Mark flow
    */
// $hostName = $_SERVER['HTTP_HOST'];
// $hostName = $_SERVER['HTTP_HOST'];
// $appName = explode("/", $_SERVER['REQUEST_URI'])[1];
// // $cancelUrl= "http://".$hostName."/".$appName."/cancel.php";
// $cancelUrl= "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/cancel.php";
// var_dump($cancelUrl);
// // $payUrl = "http://".$hostName."/".$appName."/pay.php";
// $payUrl = "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/pay.php";
// var_dump($payUrl);
// // $placeOrderUrl = "http://".$hostName."/".$appName."/placeOrder.php";
// // $placeOrderUrl = "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/placeOrder.php";
// $placeOrderUrl = "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/pay_now.php?csrf=".$_SESSION['csrf'];
// var_dump($placeOrderUrl);


$cancelUrl     = WEB_URL . '/order_cancel.php';
$payUrl        = WEB_URL . '/pay.php';
$placeOrderUrl = WEB_URL . '/pay_now.php?csrf='.$_SESSION['csrf'];
// var_dump($cancelUrl, $payUrl, $placeOrderUrl);

// var_dump($invoiceId);

// echo '<pre>' . print_r($_SESSION['expressCheckoutPaymentData']) . '</pre>';

$stmt = $db->prepare(' SELECT ( SELECT ROUND(setting_val, 2) FROM `order_invoice_record` WHERE setting_name = "total_price"           AND order_id = :invoice_id )                                          as total_price_record ,
                              ( SELECT ROUND(setting_val, 2) FROM `order_invoice_record` WHERE setting_name = "price"                 AND order_id = :invoice_id AND `info_id` = oip.`invoice_product_pk` ) as order_record_price,
                              ( SELECT ROUND(setting_val, 2) FROM `order_invoice_record` WHERE setting_name = "discount"              AND order_id = :invoice_id AND `info_id` = oip.`invoice_product_pk` ) as discount,
                              ( SELECT setting_val FROM `order_invoice_record` WHERE setting_name = "coupon"              AND order_id = :invoice_id LIMIT 1 ) as coupon,
                              ( SELECT setting_val FROM `order_invoice_record` WHERE setting_name = "shipping_country_row"  AND order_id = :invoice_id AND `info_id` = oip.`invoice_product_pk` ) as shipping_country_row,  
                              ( SELECT invoice_id  FROM `order_invoice`    WHERE order_invoice_pk = :invoice_id             ) as invoice_id,  
                              ( SELECT ship_price  FROM `order_invoice`    WHERE order_invoice_pk = :invoice_id             ) as ship_price,  
                              oip.*, oi.total_price as total_price, oi.shippingCountry, oi.three_for_two_cat as three_for_two_cat FROM order_invoice_product oip 
                              INNER JOIN order_invoice oi ON oi.order_invoice_pk = oip.order_invoice_id
                              WHERE oip.`order_invoice_id`   = :invoice_id ');
$stmt->bindValue(':invoice_id', $invoiceId, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($_SESSION['ftg']['jsonResponse'], $rows);
// var_dump($invoiceId,$rows);
$total_price_for_gift_card_to_check = $rows[0]['total_price'];
$three_for_two_cat = $rows[0]['three_for_two_cat'];
$shipping_country  = $rows[0]['shippingCountry'];
$shipping_currency_name = '';
$total_product_price    = 0;
$total_products_quantity = 0;
$coupon_discounted_total_product_price = 0;

$nvp_products = array(); // used in NVP request

function make_item($order_row, $product_price = false, $total_coupon_name = false, $i = 0, $shipping_country)
{
  global $db, $webClass, $shipping_currency_name, $total_product_price, $total_products_quantity, $coupon_discounted_total_product_price, $nvp_products, $productClass;

  $pid = explode('-', $order_row['order_pIds'])[0];

  $stmt = $db->prepare(' SELECT * FROM `proudct_detail` WHERE prodet_id = :id ');
  $stmt->bindValue(':id', $pid, PDO::PARAM_INT );
  $stmt->execute();
  $product_row = $stmt->fetch(PDO::FETCH_ASSOC);

  // $shipping_country_row    = $webClass->get_shipping_country_row_by_id($order_row['shipping_country_row']);
  $currency_symbol    = $productClass->currentCurrencySymbol();
  $shipping_currency_name  = ( $currency_symbol == 'EURO' )? 'EUR' : $currency_symbol;


  // ## PRODUCT COUPON
  //   if( $order_row['coupon'] && $total_coupon_name == NULL ) {
  //       # get this order product's discount from order_invoice_record
  //       $product_price_row     = get_order_invoice_record($order_row['order_invoice_id'], 'price', $order_row['invoice_product_pk']);
  //       $product_price_amount  = $product_price_row['setting_val'];  
  //       $product_discout_row   = get_order_invoice_record($order_row['order_invoice_id'], 'discount', $order_row['invoice_product_pk']);
  //       $product_discout_amount= $product_discout_row['setting_val'];


  //       $discounted_after_normal_coupon_product_price_single = round( $product_price_amount - $product_discout_amount );
  //       // var_dump( $discounted_after_normal_coupon_product_price_single );
  //       $order_row['price'] =  ( $discounted_after_normal_coupon_product_price_single < 0 ) ? 0 : $discounted_after_normal_coupon_product_price_single;
  //       // var_dump('COUPON FOUND');
  //   }
  // ## PRODUCT COUPON END


  // if (!$product_price) {
  //   # $order_row['price'] is the converted amount, saved in order_invoice_record
  //   // $total_product_price += ( $order_row['price'] * $order_row['order_pQty'] );

  //   # now we are changing the calculation, we are adding the SEK prices for products and shipping and substracting the total  coupon discount, then we will apply conversion
  //   $coupon_discount_applied = FALSE;
  //   // if( $order_row['coupon'] && $total_coupon_name == NULL ) {
  //   //     ## PRODUCT COUPON
  //   //     $order_row['price']  = $order_row['order_record_price'] - $order_row['discount'];
  //   //     $total_product_price += ( $order_row['price'] * $order_row['order_pQty'] );
  //   //     $coupon_discount_applied = TRUE;
  //   //     // var_dump($order_row['price'], $order_row['discount']);
  //   // } else {
  //   //   if ($shipping_country != 'SE') {
  //   //     $rate_countrywise        = $productClass->apply_currency_rate_countrywise( array( $order_row['order_pPrice'] ) );
  //   //     $converted_order_pPrice  = ($rate_countrywise['pPrice']);
  //   //     $result_product_price = ( $converted_order_pPrice - $order_row['discount'] ) * $order_row['order_pQty'];
  //   //     $total_product_price_converted = true;
  //   //     // var_dump($total_product_price_converted);
  //   //   } else {
  //   //     $result_product_price = ( $order_row['order_pPrice'] - $order_row['discount'] ) * $order_row['order_pQty'];
  //   //     $total_product_price_converted = false;
  //   //   }

  //   //     if ($result_product_price < 0) {
  //   //       $result_product_price = 0;
  //   //     }
  //   //     $total_product_price += $result_product_price;
  //   //     // var_dump( $total_product_price );
  //   // // var_dump($order_row['order_pPrice'] , $order_row['discount'], $result_product_price);
  //   // }


  // } else {
  //   # product price given
  //   // $coupon_discounted_total_product_price += ( $product_price * $order_row['order_pQty'] );

  // }
  // var_dump($order_row);
  $total_products_quantity += $order_row['order_pQty'];
  $order_row['price'] = ( isset($order_row['price']) ? $order_row['price'] : $order_row['order_record_price']);
  if( isset($order_row['order_discount']) && $order_row['order_discount'] != '' ) {
    // var_dump($order_row['order_discount']);


  // if ($shipping_country != 'SE') {
  //   ## convert the discount FOR NON SEK
  //   // var_dump($order_row['order_discount']);

  //   $rate_countrywise     = $productClass->apply_currency_rate_countrywise( array( $order_row['order_discount'] ) );
  //   $order_row['order_discount']   = ($rate_countrywise['pPrice']);
  //   // var_dump($order_row['order_discount']);
  // }
    // var_dump($order_row['order_discount']);
    // if (isset($coupon_discount_applied) && $coupon_discount_applied == TRUE) {
    //   $order_row['order_discount'] = 0;
    // }
    // var_dump('3: ' . $order_row['price']);
    // var_dump('4: ' . $order_row['order_discount']);
    $order_row['price'] = $order_row['order_pPrice'] - $order_row['order_discount'];
    // var_dump('5: ' . $order_row['price']);
    // var_dump($order_row);
    if ( $order_row['price'] < 0 ) {
      $order_row['price'] = 0;
    }

  }

  ## COUPON WORK ON TOTAL AMOUNT
  if ($product_price) {
    // var_dump($order_row);
    $product_price      = ( $product_price < 0 ) ? 0 : $product_price;
    // $order_row['price'] = $product_price;
  }
  ## COUPON WORK ON TOTAL AMOUNT END



  $json = <<<JSON

                                                {
                                                   "name"     :"{$order_row['order_pName']}",
                                                   "quantity" :"{$order_row['order_pQty']}",
                                                   "price"    :"{$order_row['price']}",
                                                   "sku"      :"{$db->productDetail}{$product_row['slug']}",
                                                   "currency" :"{$shipping_currency_name}"
                                                }

JSON;



    $product_desc = translateFromSerialize($product_row['prodet_shortDesc']);

    $nvp_products[$i]['ItemName']  = $order_row['order_pName'];                 //  Item Name
    $nvp_products[$i]['ItemPrice'] = $order_row['price'];                       //  Item Price
    $nvp_products[$i]['ItemNumber']= $db->productDetail.$product_row['slug'];   //  Item Number
    $nvp_products[$i]['ItemDesc']  = $product_desc;                             //  Item Number
    $nvp_products[$i]['ItemQty']   = $order_row['order_pQty'];                  //  Item Quantity

    // $i++;

    return $json . ',';

}

function get_order_invoice_record($invoiceId, $settingName="giftCard", $info_id = false) {

    global $db;
    $info_id_sql = '';
    if ($info_id) {
      $info_id_sql = ' AND info_id = :info_id ';
    }
    $sql  = " SELECT * FROM `order_invoice_record` WHERE order_id = :invoice_id AND setting_name = :setting_name {$info_id_sql} ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invoice_id',   $invoiceId, PDO::PARAM_STR );
    $stmt->bindValue(':setting_name', $settingName, PDO::PARAM_STR );
    if ($info_id) {
      $stmt->bindValue(':info_id', $info_id, PDO::PARAM_INT );
    }
    $stmt->execute();
    if( $stmt->rowCount() > 0 ) {
      $row  = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      $row  = false;
    }
    return $row;
}



$custom_invoiceId    = $rows[0]['invoice_id'];
$p_total             = $rows[0]['total_price'];
$ship_price          = $rows[0]['ship_price'];


$total_coupon_name   = get_order_invoice_record($invoiceId, 'total_coupon_name');
$total_coupon_name   = $total_coupon_name['setting_val'];
// var_dump('$total_coupon_name: '.$total_coupon_name);

$item = '';
$i    = 0;
foreach ($rows as $row) {
  $item .= make_item($row, false, $total_coupon_name, $i, $shipping_country);
  $i++;
}
// remove trailing comma
$item = rtrim($item, ',');

// $currency_rate_countrywise = $productClass->apply_currency_rate_countrywise( array( $rows[0]['ship_price'] ) );
// $ship_price          = round($currency_rate_countrywise['pPrice']); // pPrice is key name for first item, do not get confused, it can contain any number not just product price

$shipping_country = $rows[0]['shippingCountry'];
# if shipping country is SE, then add the shipping price to the total price
if ($shipping_country == 'SE') {
  $total               = $total_product_price + $ship_price;  // 
  $saved_ship_price    = 0;
} else {
  $total               = $total_product_price;  // shipping price will be added at the end because that price does not need currency conversion it is already converted.
  ## IF CURRENCY IS OTHER THAN SE, MAKE SHIP PRICE 0, WE WILL ADD IT AT THE END
  $saved_ship_price    = $ship_price ; // saving the shipping price, will add it at the end
  // $ship_price          = 0;
}


// var_dump($_SESSION['webUser']['coupon']);

# coupon work
$coupon_discount     = 0;
// var_dump($total_products_quantity);
if ($total_coupon_name != FALSE) {
    $total_coupon_discount = get_order_invoice_record($invoiceId, 'total_coupon_discount');
    $total_coupon_discount = $total_coupon_discount['setting_val'];
    $total_coupon_format   = get_order_invoice_record($invoiceId, 'total_coupon_discountFormat');
    $total_coupon_format   = $total_coupon_format['setting_val'];
    // $order_total_calc = ($totalNet + $data['ship_price']);
    if( $total_coupon_format == 'percent' ){
        // var_dump('1: '.$total_coupon_discount);
        // var_dump('2: '.$total);
        $after_coupon_discount = ( $total / 100 ) * $total_coupon_discount;
        // var_dump('3: '.$after_coupon_discount);
        // $total      = $total - $after_coupon_discount;
        // $total      = $total - $after_coupon_discount;
        // $totalDiscount         = round($after_coupon_discount);

        // # add all prices
        // $ship_and_product_total = ( $ship_price + $total_product_price );
        // # now minus the discount coupon amount
        // $ship_and_product_total = $after_coupon_discount;
        // # now divide the amount equally to shipping and product price
        // $ship_price          = ( $ship_and_product_total / 2 );
        // $total_product_price = ( $ship_and_product_total / 2 );

    } else {
        // # price discount
        // $after_coupon_discount = $total_coupon_discount;
    }
        // // var_dump($shipping_country);
        // if ( $shipping_country != 'SE' && $total_coupon_format != 'percent' ) { // CONDITION NUMBER #1
        //   $rate_countrywise_coupon  = $productClass->apply_currency_rate_countrywise( array( $after_coupon_discount ) );
        //   $after_coupon_discount    = $rate_countrywise_coupon['pPrice'];
        //   // var_dump('4: '.$after_coupon_discount);
        // }

        // # minus discount coupon amount from the total
        // $total      = $total - $after_coupon_discount;
        // // $totalDiscount         = round($after_coupon_discount);
        // // var_dump($total);
        // # add all prices
        // $ship_and_product_total = ( $ship_price + $total_product_price );
        // // var_dump($ship_price , $total_product_price, $after_coupon_discount);
        // # now minus the discount coupon amount
        // $ship_and_product_total = $ship_and_product_total - $after_coupon_discount;
        // # now divide the amount equally to shipping and product price
        // // $ship_price          = ( $ship_and_product_total / 2 );
        // $total_product_price = ( $ship_and_product_total / 2 );    
        // // var_dump( $total_product_price, $ship_price );

        // ### APPLIED ON CONDITION NUMBER #1
        // // # apply currency rate conversion
        // // $rate_countrywise     = $productClass->apply_currency_rate_countrywise( array( $total_product_price, $ship_price ) );
        // // $total_product_price  = $rate_countrywise['pPrice'];
        // // $ship_price           = $rate_countrywise['discountPrice'];


        // # reset the total
        // $total = round($total_product_price + $ship_price + $saved_ship_price, 2);
        // // var_dump($total_product_price, $ship_price , $saved_ship_price);

        // if ($shipping_country != 'SE') {
        //     # reset the total for other than SE shipping country
        //     $total_product_price = ( $total - $saved_ship_price );
        //     // var_dump( $total_product_price );
        // }        

        // var_dump($total_product_price);

        // # sub total amount will be subtotal / total_products_quantity
        // $product_price = round(( $total_product_price / $total_products_quantity ), 2);    
        // # make product items again, but this time pass the sub-total amount
        

        $item = '';
        $i    = 0;
        foreach ($rows as $row) {
          $item .= make_item($row, false, false, $i, $shipping_country);
          $i++;
        }                       
        // remove trailing comma
        $item = rtrim($item, ',');

        // // var_dump($total_product_price,$product_price);
        // # reset the sub total
        // $total_product_price = round($coupon_discounted_total_product_price, 2);
        // # reset the shipping total
        // // $ship_price          = round($total - $total_product_price, 2);

        // var_dump($total , $total_product_price);

    // var_dump( $order_total_calc, $after_coupon_discount, ($totalNet + $data['ship_price']), $total_coupon_name, $total_coupon_discount, $total_coupon_format);
    // $coupon_discount     = $total_coupon_discount;

} else {
  // # conversion for other than total coupon
  // # apply currency rate conversion
  // $rate_countrywise     = $productClass->apply_currency_rate_countrywise( array( $total_product_price, $ship_price ) );
  // $total_product_price  = $rate_countrywise['pPrice'];
  // $ship_price           = $rate_countrywise['discountPrice'];
  // # reset the total
  // $total = $total_product_price + $ship_price;
  // var_dump($shipping_country);

  if ( isset($total_product_price_converted) && $total_product_price_converted == FALSE ) {

    # apply currency rate conversion
    $rate_countrywise     = $productClass->apply_currency_rate_countrywise( array( $total, $total_product_price ) );
    $total                = round($rate_countrywise['pPrice'], 2);
    $total_product_price  = round($rate_countrywise['discountPrice'], 2);
    
  }

  $total      = $total + $saved_ship_price;

if ($shipping_country == 'SE') {
  $saved_ship_price    = $ship_price;
}

  // $ship_price = $saved_ship_price;
  // var_dump($total, $ship_price, $total_product_price);
}
###


// $total               = $total_product_price + $ship_price - $coupon_discount;  // 

// var_dump($total_product_price);
// ' . $ship_price . '


$total               = ( $total < 0 )               ? 0.1  : $total;
$ship_price          = ( $ship_price < 0 )          ? 0    : $ship_price;
$total_product_price = ( $total_product_price < 0 ) ? 0    : $total_product_price;
$handling_fee        = ( $total == 0.1 )            ? 0.1  : 0 ;



$nvp_charges['TotalTaxAmount']  = 0;  //Sum of tax for all items in this order. 
$nvp_charges['HandalingCost']   = $handling_fee;  //Handling cost for this order.
$nvp_charges['InsuranceCost']   = 0;  //shipping insurance cost for this order.
$nvp_charges['ShippinDiscount'] = 0; //Shipping discount for this order. Specify this as negative number.
$nvp_charges['ShippinCost']     = $ship_price; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.


$nvp_currency_code              = $shipping_currency_name;
 

$_SESSION['expressCheckoutPaymentData'] = '{
                                  "transactions":[
                                     {
                                        "amount":{
                                           "currency":"' . $shipping_currency_name . '",
                                           "total":"' . $total . '",
                                           "details":{
                                              "shipping":"' . $ship_price . '",
                                              "subtotal":"' . $total_product_price .'",
                                              "tax":"0",
                                              "insurance":"0",
                                              "handling_fee":"'.$handling_fee.'",
                                              "shipping_discount":"0"
                                           }
                                        },
                                         "payee":{
                                             "email": "fhdalikhan-facilitator@imedia.com.pk"
                                         },
                                        "description"   :"creating a payment",
                                        "invoice_number": "' . $custom_invoiceId . '",
                                        "item_list":{
                                           "items":[
                                              ' . $item . '
                                           ]
                                        }
                                     }
                                  ],
                                  "payer":{
                                     "payment_method":"paypal"
                                  },
                                  "intent":"sale",
                                  "redirect_urls":{
                                     "cancel_url":"'.$cancelUrl.'",
                                     "return_url":"'.$placeOrderUrl.'"
                                  }
                               }';


// $_SESSION['expressCheckoutPaymentData'] = '{
//                                   "transactions":[
//                                      {
//                                         "amount":{
//                                            "currency":"' . $shipping_currency_name . '",
//                                            "total":"' . $total . '",
//                                            "details":{
//                                               "shipping":"' . $ship_price . '",
//                                               "subtotal":"' . $total_product_price .'",
//                                               "tax":"0",
//                                               "insurance":"0",
//                                               "handling_fee":"0",
//                                               "shipping_discount":"0"
//                                            }
//                                         },
//                                         "description"   :"creating a payment",
//                                         "invoice_number": "' . $custom_invoiceId . '",
//                                         "item_list":{
//                                            "items":[
//                                               ' . $item . '
//                                            ]
//                                         }
//                                      }
//                                   ],
//                                   "payer":{
//                                      "payment_method":"paypal"
//                                   },
//                                   "intent":"sale",
//                                   "redirect_urls":{
//                                      "cancel_url":"'.$cancelUrl.'",
//                                      "return_url":"'.$placeOrderUrl.'"
//                                   }
//                                }';


// $_SESSION['markFlowPaymentData'] = '{
//                            "intent":"sale",
//                            "payer":{
//                               "payment_method":"paypal"
//                            },
//                            "transactions":[
//                               {
//                                  "amount":{
//                                     "currency":"USD",
//                                     "total":"320",
//                                     "details":{
//                                        "shipping":"2",
// 									   "subtotal":"300",
// 									   "tax":"5",
// 									   "insurance":"10",
// 									   "handling_fee":"5",
// 									   "shipping_discount":"-2"
//                                     }
//                                  },
//                                  "description":"This is the payment transaction description ---->.",
//                                  "custom":"Nouphal Custom",
//                                  "item_list":{
//                                     "items":[
//                                        {
//                                           "name":"Camera",
//                                           "quantity":"1",
//                                           "price":"300",
//                                           "sku":"1",
//                                           "currency":"USD"
//                                        }
//                                     ]
//                                  }
//                               }
//                            ],
//                            "redirect_urls":{
//                               "return_url":"'.$payUrl.'",
//                               "cancel_url":"'.$cancelUrl.'"
//                            }
//                         }';



?>