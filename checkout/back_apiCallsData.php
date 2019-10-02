<?php

if (session_id() !== "") {
       session_unset();
       session_destroy();
    }
    session_start();
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
/*
    * Data for REST API calls.
    * $_SESSION['expressCheckoutPaymentData'] is used in the Express Checkout flow
    * $_SESSION['markFlowPaymentData'] is used for the Proceed to Checkout/Mark flow
    */
$hostName = $_SERVER['HTTP_HOST'];
$appName = explode("/", $_SERVER['REQUEST_URI'])[1];
// $cancelUrl= "http://".$hostName."/".$appName."/cancel.php";
$cancelUrl= "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/cancel.php";
var_dump($cancelUrl);
// $payUrl = "http://".$hostName."/".$appName."/pay.php";
$payUrl = "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/pay.php";
var_dump($payUrl);
// $placeOrderUrl = "http://".$hostName."/".$appName."/placeOrder.php";
// $placeOrderUrl = "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/placeOrder.php";
$placeOrderUrl = "http://".$hostName."/projects/work/paypal/Checkout-REST-php-master/pay_now.php?csrf=".$_SESSION['csrf'];
var_dump($placeOrderUrl);

$_SESSION['expressCheckoutPaymentData'] = '{
                                  "transactions":[
                                     {
                                        "amount":{
                                           "currency":"USD",
                                           "total":"130",
                                           "details":{
                                              "shipping":"22",
                                              "subtotal":"290",
                                              "tax":"5",
                                              "insurance":"10",
                                              "handling_fee":"5",
                                              "shipping_discount":"-2"
                                           }
                                        },
                                        "description"   :"creating a payment",
                                        "invoice_number": "DYP_123458",
                                        "item_list":{
                                           "items":[
                                              {
                                                 "name":"Camera",
                                                 "quantity":"1",
                                                 "price":"90",
                                                 "sku":"1",
                                                 "currency":"USD"
                                              }
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