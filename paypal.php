<?php include_once("global.php");
global $webClass;
global $productClass;
require_once 'src/Klarna/Checkout_Method/Checkout.php';

//Make Testing server false in klarna class because it is using some other place

$functions->require_once_custom('Class.myKlarna.php');
$klarnaClass    = new myKlarna();
$klarnaSecrets  = $klarnaClass->klarnaSharedSecret();

$eid            =   $klarnaSecrets['eId'];
$sharedSecret   =   $klarnaSecrets['sharedSecret'];
$orderUrl       =   $klarnaSecrets['url'];
/*
if($testingServer==true){
    //Testing Imedia Merchant eId
    $eid = '1173';
    // Shared secret
    $sharedSecret = '5zWdni3xNVcbAUN';
    // testing URL
    $orderUrl = "https://checkout.testdrive.klarna.com/checkout/orders";
    /**
     * test drive
     * Swedish consumer
        E-mail address: checkout-se@testdrive.klarna.com
        Postal code: 12345
        Personal identity number: 410321-9202

}else{
    //Real Merchant Id
    //Also Change Klarna Id From order/classes/invoice.php
    $eid = '34266';
    //Shared secret
    $sharedSecret = 'ulPhDhler4beKLa';
    $orderUrl = "https://checkout.klarna.com/checkout/orders";
}*/

//$str = "TzoyMToiS2xhcm5hX0NoZWNrb3V0X09yZGVyIjozOntzOjMyOiIAS2xhcm5hX0NoZWNrb3V0X09yZGVyAF9sb2NhdGlvbiI7czo4MToiaHR0cHM6Ly9jaGVja291dC50ZXN0ZHJpdmUua2xhcm5hLmNvbS9jaGVja291dC9vcmRlcnMvMTRBMzNFQzU2MDgyMjg0RTZBMkQ2RkMwMDAwIjtzOjI4OiIAS2xhcm5hX0NoZWNrb3V0X09yZGVyAF9kYXRhIjthOjE5OntzOjI6ImlkIjtzOjI3OiIxNEEzM0VDNTYwODIyODRFNkEyRDZGQzAwMDAiO3M6MTg6Im1lcmNoYW50X3JlZmVyZW5jZSI7YToxOntzOjg6Im9yZGVyaWQxIjtzOjM6IjEwNiI7fXM6MTY6InB1cmNoYXNlX2NvdW50cnkiO3M6Mjoic2UiO3M6MTc6InB1cmNoYXNlX2N1cnJlbmN5IjtzOjM6InNlayI7czo2OiJsb2NhbGUiO3M6NToic3Ytc2UiO3M6Njoic3RhdHVzIjtzOjc6ImNyZWF0ZWQiO3M6OToicmVmZXJlbmNlIjtzOjI3OiIxNEEzM0VDNTYwODIyODRFNkEyRDZGQzAwMDAiO3M6MTE6InJlc2VydmF0aW9uIjtzOjEwOiIxMDQ4ODE5MDAwIjtzOjEwOiJzdGFydGVkX2F0IjtzOjI1OiIyMDE0LTEyLTEwVDEyOjE4OjU0KzAxOjAwIjtzOjEyOiJjb21wbGV0ZWRfYXQiO3M6MjU6IjIwMTQtMTItMTBUMTI6MTk6MTcrMDE6MDAiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MjU6IjIwMTQtMTItMTBUMTI6MTk6MTkrMDE6MDAiO3M6MTY6Imxhc3RfbW9kaWZpZWRfYXQiO3M6MjU6IjIwMTQtMTItMTBUMTI6MTk6MTkrMDE6MDAiO3M6MTA6ImV4cGlyZXNfYXQiO3M6MjU6IjIwMTQtMTItMjRUMTI6MTk6MTcrMDE6MDAiO3M6NDoiY2FydCI7YTo0OntzOjI1OiJ0b3RhbF9wcmljZV9leGNsdWRpbmdfdGF4IjtpOjE3Mjg2ODtzOjE2OiJ0b3RhbF90YXhfYW1vdW50IjtpOjQzMjE3O3M6MjU6InRvdGFsX3ByaWNlX2luY2x1ZGluZ190YXgiO2k6MjE2MDg1O3M6NToiaXRlbXMiO2E6Mzp7aTowO2E6MTA6e3M6OToicmVmZXJlbmNlIjtzOjM6IjE3MSI7czo0OiJuYW1lIjtzOjEwNjoiVC1TaGlydDEgLSBzbWFsbCAtIDxzcGFuIHN0eWxlPSdiYWNrZ3JvdW5kOiNmMjExMTE7cGFkZGluZzogNHB4IDE1cHg7Y29sb3I6ICNmZmY7Zm9udC1zaXplOiAxMXB4Oyc+PC9zcGFuPiI7czo4OiJxdWFudGl0eSI7aToxO3M6MTA6InVuaXRfcHJpY2UiO2k6MTIwMDtzOjg6InRheF9yYXRlIjtpOjI1MDA7czoxMzoiZGlzY291bnRfcmF0ZSI7aTo2MDtzOjQ6InR5cGUiO3M6ODoicGh5c2ljYWwiO3M6MjU6InRvdGFsX3ByaWNlX2luY2x1ZGluZ190YXgiO2k6MTE5MztzOjI1OiJ0b3RhbF9wcmljZV9leGNsdWRpbmdfdGF4IjtpOjk1NDtzOjE2OiJ0b3RhbF90YXhfYW1vdW50IjtpOjIzOTt9aToxO2E6MTA6e3M6OToicmVmZXJlbmNlIjtzOjM6IjE3MiI7czo0OiJuYW1lIjtzOjEwNToiVmVzdGFlIEtuaXQgLSBTIC0gPHNwYW4gc3R5bGU9J2JhY2tncm91bmQ6I2IzNzE3MTtwYWRkaW5nOiA0cHggMTVweDtjb2xvcjogI2ZmZjtmb250LXNpemU6IDExcHg7Jz48L3NwYW4+IjtzOjg6InF1YW50aXR5IjtpOjE7czoxMDoidW5pdF9wcmljZSI7aToxNjIwNTtzOjg6InRheF9yYXRlIjtpOjI1MDA7czoxMzoiZGlzY291bnRfcmF0ZSI7aTo4MTA7czo0OiJ0eXBlIjtzOjg6InBoeXNpY2FsIjtzOjI1OiJ0b3RhbF9wcmljZV9pbmNsdWRpbmdfdGF4IjtpOjE0ODkyO3M6MjU6InRvdGFsX3ByaWNlX2V4Y2x1ZGluZ190YXgiO2k6MTE5MTQ7czoxNjoidG90YWxfdGF4X2Ftb3VudCI7aToyOTc4O31pOjI7YToxMDp7czo5OiJyZWZlcmVuY2UiO3M6ODoiU0hJUFBJTkciO3M6NDoibmFtZSI7czoxMjoiU2hpcHBpbmcgRmVlIjtzOjg6InF1YW50aXR5IjtpOjE7czoxMDoidW5pdF9wcmljZSI7aToyMDAwMDA7czo4OiJ0YXhfcmF0ZSI7aToyNTAwO3M6MTM6ImRpc2NvdW50X3JhdGUiO2k6MDtzOjQ6InR5cGUiO3M6MTI6InNoaXBwaW5nX2ZlZSI7czoyNToidG90YWxfcHJpY2VfaW5jbHVkaW5nX3RheCI7aToyMDAwMDA7czoyNToidG90YWxfcHJpY2VfZXhjbHVkaW5nX3RheCI7aToxNjAwMDA7czoxNjoidG90YWxfdGF4X2Ftb3VudCI7aTo0MDAwMDt9fX1zOjg6ImN1c3RvbWVyIjthOjM6e3M6NDoidHlwZSI7czo2OiJwZXJzb24iO3M6MTM6ImRhdGVfb2ZfYmlydGgiO3M6MTA6IjE5NDEtMDMtMjEiO3M6NjoiZ2VuZGVyIjtzOjY6ImZlbWFsZSI7fXM6MTY6InNoaXBwaW5nX2FkZHJlc3MiO2E6ODp7czoxMDoiZ2l2ZW5fbmFtZSI7czoxMzoiVGVzdHBlcnNvbi1zZSI7czoxMToiZmFtaWx5X25hbWUiO3M6ODoiQXBwcm92ZWQiO3M6MTQ6InN0cmVldF9hZGRyZXNzIjtzOjEyOiJTdMOlcmdhdGFuIDEiO3M6MTE6InBvc3RhbF9jb2RlIjtzOjU6IjEyMzQ1IjtzOjQ6ImNpdHkiO3M6ODoiQW5rZWJvcmciO3M6NzoiY291bnRyeSI7czoyOiJzZSI7czo1OiJlbWFpbCI7czozMjoiY2hlY2tvdXQtc2VAdGVzdGRyaXZlLmtsYXJuYS5jb20iO3M6NToicGhvbmUiO3M6MTM6IjA3MCAxMTEgMTEgMTEiO31zOjE1OiJiaWxsaW5nX2FkZHJlc3MiO2E6ODp7czoxMDoiZ2l2ZW5fbmFtZSI7czoxMzoiVGVzdHBlcnNvbi1zZSI7czoxMToiZmFtaWx5X25hbWUiO3M6ODoiQXBwcm92ZWQiO3M6MTQ6InN0cmVldF9hZGRyZXNzIjtzOjEyOiJTdMOlcmdhdGFuIDEiO3M6MTE6InBvc3RhbF9jb2RlIjtzOjU6IjEyMzQ1IjtzOjQ6ImNpdHkiO3M6ODoiQW5rZWJvcmciO3M6NzoiY291bnRyeSI7czoyOiJzZSI7czo1OiJlbWFpbCI7czozMjoiY2hlY2tvdXQtc2VAdGVzdGRyaXZlLmtsYXJuYS5jb20iO3M6NToicGhvbmUiO3M6MTM6IjA3MCAxMTEgMTEgMTEiO31zOjM6Imd1aSI7YToyOntzOjY6ImxheW91dCI7czo3OiJkZXNrdG9wIjtzOjc6InNuaXBwZXQiO3M6MjE5NjoiPGRpdiBpZD0ia2xhcm5hLWNoZWNrb3V0LWNvbnRhaW5lciIgc3R5bGU9Im92ZXJmbG93LXg6IGhpZGRlbjsiPgogICAgPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPgogICAgLyogPCFbQ0RBVEFbICovCiAgICAgICAgKGZ1bmN0aW9uKHcsayxpLGQsbixjLGwscCl7CiAgICAgICAgICAgIHdba109d1trXXx8ZnVuY3Rpb24oKXsod1trXS5xPXdba10ucXx8W10pLnB1c2goYXJndW1lbnRzKX07CiAgICAgICAgICAgIHdba10uY29uZmlnPXsKICAgICAgICAgICAgICAgIGNvbnRhaW5lcjp3LmRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGkpLAogICAgICAgICAgICAgICAgT1JERVJfVVJMOidodHRwczovL2NoZWNrb3V0LnRlc3Rkcml2ZS5rbGFybmEuY29tL2NoZWNrb3V0L29yZGVycy8xNEEzM0VDNTYwODIyODRFNkEyRDZGQzAwMDAnLAogICAgICAgICAgICAgICAgQVVUSF9IRUFERVI6J0tsYXJuYUNoZWNrb3V0IGFnWjFvN0xSTWI0QVpFUEowTDRDJywKICAgICAgICAgICAgICAgIFRFU1REUklWRTp0cnVlLAogICAgICAgICAgICAgICAgTEFZT1VUOidkZXNrdG9wJywKICAgICAgICAgICAgICAgIExPQ0FMRTonc3Ytc2UnLAogICAgICAgICAgICAgICAgT1JERVJfU1RBVFVTOidjcmVhdGVkJywKICAgICAgICAgICAgICAgIE1FUkNIQU5UX1RBQ19VUkk6J2h0dHA6Ly9sb2NhbGhvc3QvcHJvamVjdHMvSGFwcHkvd2Vic2l0ZS9kYXRhL3Rlcm1zJywKICAgICAgICAgICAgICAgIE1FUkNIQU5UX1RBQ19USVRMRTonSW50ZXJhY3RpdmUgTWVkaWEnLAogICAgICAgICAgICAgICAgTUVSQ0hBTlRfTkFNRTonSW50ZXJhY3RpdmUgTWVkaWEnLAogICAgICAgICAgICAgICAgR1VJX09QVElPTlM6W10sCiAgICAgICAgICAgICAgICBBTExPV19TRVBBUkFURV9TSElQUElOR19BRERSRVNTOgogICAgICAgICAgICAgICAgZmFsc2UsCiAgICAgICAgICAgICAgICBOQVRJT05BTF9JREVOVElGSUNBVElPTl9OVU1CRVJfTUFOREFUT1JZOgogICAgICAgICAgICAgICAgZmFsc2UsCiAgICAgICAgICAgICAgICBBTkFMWVRJQ1M6J1VBLTM2MDUzMTM3LTEnLAogICAgICAgICAgICAgICAgUEhPTkVfTUFOREFUT1JZOmZhbHNlLAogICAgICAgICAgICAgICAgUEFDS1NUQVRJT05fRU5BQkxFRDpmYWxzZSwKICAgICAgICAgICAgICAgIFBVUkNIQVNFX0NPVU5UUlk6J3N3ZScsCiAgICAgICAgICAgICAgICBQVVJDSEFTRV9DVVJSRU5DWTonc2VrJywKICAgICAgICAgICAgICAgIEJPT1RTVFJBUF9TUkM6J2h0dHBzOi8vY2hlY2tvdXQudGVzdGRyaXZlLmtsYXJuYS5jb20vMTQxMjA0LTZjZmE5OGEvY2hlY2tvdXQuYm9vdHN0cmFwLmpzJwogICAgICAgICAgICB9OwogICAgICAgICAgICBuPWQuY3JlYXRlRWxlbWVudCgnc2NyaXB0Jyk7CiAgICAgICAgICAgIGM9ZC5nZXRFbGVtZW50QnlJZChpKTsKICAgICAgICAgICAgbi5hc3luYz0hMDsKICAgICAgICAgICAgbi5zcmM9d1trXS5jb25maWcuQk9PVFNUUkFQX1NSQzsKICAgICAgICAgICAgYy5pbnNlcnRCZWZvcmUobixjLmZpcnN0Q2hpbGQpOwogICAgICAgICAgICB0cnl7CiAgICAgICAgICAgICAgICBwID0gd1trXS5jb25maWcuQk9PVFNUUkFQX1NSQy5zcGxpdCgnLycpOwogICAgICAgICAgICAgICAgcCA9IHAuc2xpY2UoMCwgcC5sZW5ndGggLSAxKTsKICAgICAgICAgICAgICAgIGwgPSBwLmpvaW4oJy8nKSArCiAgICAgICAgICAgICAgICAgICAgJy9hcGkvX3RyYWNraW5nL3YxL3NuaXBwZXQvbG9hZD9vcmRlclVybD0nICsKICAgICAgICAgICAgICAgICAgICB3LmVuY29kZVVSSUNvbXBvbmVudCh3W2tdLmNvbmZpZy5PUkRFUl9VUkwpICsgJyYnICsKICAgICAgICAgICAgICAgICAgICAobmV3IERhdGUpLmdldFRpbWUoKTsKICAgICAgICAgICAgICAgICgody5JbWFnZSAmJiAobmV3IHcuSW1hZ2UpKXx8KGQuY3JlYXRlRWxlbWVudCYmZC5jcmVhdGVFbGVtZW50KCdpbWcnKSl8fHt9KS5zcmM9bDsKICAgICAgICAgICAgfWNhdGNoKGUpe30KICAgICAgICB9KSh0aGlzLCdfa2xhcm5hQ2hlY2tvdXQnLCdrbGFybmEtY2hlY2tvdXQtY29udGFpbmVyJyxkb2N1bWVudCk7CiAgICAvKiBdXT4gKi8KICAgIDwvc2NyaXB0PgogICAgPG5vc2NyaXB0PgogICAgICAgIFBsZWFzZSA8YSBocmVmPSJodHRwOi8vZW5hYmxlLWphdmFzY3JpcHQuY29tIj5lbmFibGUgSmF2YVNjcmlwdDwvYT4uCiAgICA8L25vc2NyaXB0Pgo8L2Rpdj4KIjt9czo4OiJtZXJjaGFudCI7YTo1OntzOjI6ImlkIjtzOjQ6IjExNzMiO3M6OToidGVybXNfdXJpIjtzOjUwOiJodHRwOi8vbG9jYWxob3N0L3Byb2plY3RzL0hhcHB5L3dlYnNpdGUvZGF0YS90ZXJtcyI7czoxMjoiY2hlY2tvdXRfdXJpIjtzOjUwOiJodHRwOi8vbG9jYWxob3N0L3Byb2plY3RzL0hhcHB5L3dlYnNpdGUva2xhcm5hLnBocCI7czoxNjoiY29uZmlybWF0aW9uX3VyaSI7czo5MDoiaHR0cDovL2xvY2FsaG9zdC9wcm9qZWN0cy9IYXBweS93ZWJzaXRlL2tsYXJuYS5waHA/YWN0aW9uPXN1Y2Nlc3MmaW52PTEwNiZrbGFybmFfb3JkZXI9MTA2IjtzOjg6InB1c2hfdXJpIjtzOjg3OiJodHRwOi8vbG9jYWxob3N0L3Byb2plY3RzL0hhcHB5L3dlYnNpdGUva2xhcm5hLnBocD9hY3Rpb249Y2FuY2VsJmk9MTA2JmtsYXJuYV9vcmRlcj0xMDYiO319czoxMjoiACoAY29ubmVjdG9yIjtPOjMwOiJLbGFybmFfQ2hlY2tvdXRfQmFzaWNDb25uZWN0b3IiOjM6e3M6NzoiACoAaHR0cCI7TzozNDoiS2xhcm5hX0NoZWNrb3V0X0hUVFBfQ1VSTFRyYW5zcG9ydCI6Mjp7czo3OiIAKgBjdXJsIjtPOjMyOiJLbGFybmFfQ2hlY2tvdXRfSFRUUF9DVVJMRmFjdG9yeSI6MDp7fXM6MTA6IgAqAHRpbWVvdXQiO2k6MTA7fXM6MTE6IgAqAGRpZ2VzdGVyIjtPOjIyOiJLbGFybmFfQ2hlY2tvdXRfRGlnZXN0IjowOnt9czozOToiAEtsYXJuYV9DaGVja291dF9CYXNpY0Nvbm5lY3RvcgBfc2VjcmV0IjtzOjE1OiI1eldkbmkzeE5WY2JBVU4iO319";
//var_dump(unserialize(base64_decode($str)));
if(isset($_GET['inv'])){
    $invoiceId  =   $_GET['inv'];
}else{
    exit;
}
$orderUser  = webUserId();
if($orderUser=='0'){
    $orderUser = $productClass->webTempUserId();
}
$webURL  = WEB_URL;
if(isset($_GET['action'])){

}else{
    $_GET['action'] = 'process';
}


if(isset($_SESSION['invoiceId']) && $_SESSION['invoiceId']==$invoiceId){

}else{
    unset($_SESSION['klarna_checkout']);
}

$_SESSION['invoiceId']      =   $invoiceId;


if(!isset($_GET['ajax'])){include("header.php");?>
<!--Inner Container Starts Place Your Css Classes-->
<div class="inner_details_container container-fluid padding-0">
<div class="inner_details_content">
<div class="home_links_heading border"><?php $dbF->hardWords('CHECK OUT');?></div>
<div class="inner_content_page_div futura_bk_bt">

<?php

}
if(isset($_GET['action'])){

    switch ($_GET['action']) {
        case 'process':      // Process and order...

?>


    <div class="text-center"><h3><?php $dbF->hardWords('Please wait for the checkout form below, your order is Processing.');?></h3></div>

            <?php
            $sql  = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$invoiceId' AND orderUser = '$orderUser' AND orderStatus = 'inComplete'";
            $orderInvoice   =   $dbF->getRow($sql);
            if(!$dbF->rowCount){
                $dbF->hardWords('Order Invoice Not Found. Please Refresh page And Try Again.');
                exit;
            }

            $sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$invoiceId'";
            $orderProducts   =   $dbF->getRows($sql);

            //$qry_order=mysql_query("SELECT invoice_number AS reference, order_pName AS name, order_qyt AS quantity, CAST(((order_pPrice+order_size_charge+order_add_price)*100) AS unsigned) AS unit_price,'0' AS tax_rate FROM `order` WHERE `invoice_number`='$invn' ");
            //	$cart = array();
            $x = array();
            $temp  = '';
            if($dbF->rowCount>0){
                $i = 0;
                foreach($orderProducts as $val){
               //	array_push($cart,$data_order);
                    $ref = $val['order_pIds'];

                    //get color id, for get color name,
                    $pArray     =   explode("-",$ref); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                    $pId        =   $pArray[0]; // 491
                    $scaleId    =   $pArray[1]; // 426
                    $colorId    =   $pArray[2]; // 435
                    $storeId    =   $pArray[3]; // 5

                    $color      = "#".$productClass->productF->getColorName($colorId);
                    $name       = strip_tags(trim($val['order_pName']));
                    $name       = $name.$color;

                    $price = intval(floatval($val['order_salePrice'])*100);
                    $discount = intval(floatval($val['order_discount'])*100);
                    if($discount!='0'){
                        $tempDis = intval(($discount/$price)*10000);
                    }else{
                        $tempDis    = 0;
                    }

                    $x[] = array(
                        'reference' => $ref,
                        'name' =>  $name,
                        'quantity' => intval($val['order_pQty']),
                        'unit_price' => $price,
                        'discount_rate' => $tempDis,
                        'tax_rate' => 2500

                    );
                }
                $x[] = array(
                    'type' => 'shipping_fee',
                    'reference' => 'SHIPPING',
                    'name' => 'Shipping Fee',
                    'quantity' => 1,
                    'unit_price' => intval(floatval($orderInvoice['ship_price'])*100),
                    'tax_rate' => 2500
                );
            }

            $cart = $x;
   //var_dump($x);
            /*$cart = array(
                    array(
                        'reference' => '123456789',
                        'name' => 'Klarna t-shirt',
                        'quantity' => 1,
                        'unit_price' => 2169,
                        'discount_rate' => 0,
                        'tax_rate' => 0
                    )
            );
            echo"<pre>";print_r($cart);
            echo"</pre>";*/


            Klarna_Checkout_Order::$baseUri
                = $orderUrl;
            Klarna_Checkout_Order::$contentType
                = "application/vnd.klarna.checkout.aggregated-order-v2+json";

            /*session_start();*/

            $connector = Klarna_Checkout_Connector::create($sharedSecret);

            $order = null;
            if (array_key_exists('klarna_checkout', $_SESSION)) {
                // Resume session
                $order = new Klarna_Checkout_Order(
                    $connector,
                    $_SESSION['klarna_checkout']
                );
                try {
                    $order->fetch();

                    // Reset cart
                    $update['cart']['items'] = array();
                    foreach ($cart as $item) {
                        $update['cart']['items'][] = $item;
                    }
                    $order->update($update);
                } catch (Exception $e) {
                    // Reset session
                    $order = null;
                    unset($_SESSION['klarna_checkout']);
                }
            }

            if ($order == null) {
                // Start new session
                $create['purchase_country'] = 'SE';
                $create['purchase_currency'] = 'SEK';
                $create['locale'] = 'sv-se';
                $create['merchant']['id'] = $eid;
                $create['merchant']['terms_uri'] = $webURL.'/data/kopvillkor';
                $create['merchant']['checkout_uri'] = $webURL.'/klarna.php';
                $create['merchant']['confirmation_uri']
                    = $webURL.'/klarna.php?action=success&inv='.$invoiceId.'&klarna_order='.$invoiceId;
                $create['merchant']['push_uri'] = $webURL.'/klarna.php?action=cancel&i='.$invoiceId.'&klarna_order='.$invoiceId;

                foreach ($cart as $item) {
                    $create['cart']['items'][] = $item;
                }


                //var_dump($create);
                $order = new Klarna_Checkout_Order($connector);
                $order->create($create);
                $order->fetch();
            }

            // Store location of checkout session
            $_SESSION['klarna_checkout'] = $sessionId = $order->getLocation();

            // Display checkout
            $snippet = $order['gui']['snippet'];
            // DESKTOP: Width of containing block shall be at least 750px
            // MOBILE: Width of containing block shall be 100% of browser window (No
            // padding or margin)
            echo "<div>{$snippet}</div>";
            ?>

            <?php

            break;

        case 'success':      // Order was successful...
            // The order Is Successfully completed.
            $invoiceId=(trim($_GET['inv']));
            $status='success';
            $var = $dbF->hardWords('Thank you for your order.',false);
            echo "<h3 class='alert alert-success'>$var</h3>";
            if(!isset($_SESSION['klarna_checkout'])){
                break;
            }
            require_once 'src/Klarna/Checkout_Method/Checkout.php';

            Klarna_Checkout_Order::$contentType
                = "application/vnd.klarna.checkout.aggregated-order-v2+json";

            $connector = Klarna_Checkout_Connector::create($sharedSecret);

            $checkoutId = $_SESSION['klarna_checkout'];
            $order = new Klarna_Checkout_Order($connector, $checkoutId);
            $order->fetch();

            if ($order['status'] == 'checkout_incomplete') {
                $status='canceled';
                $api    =   base64_encode(serialize($order));
                $inTransiction = "
                    Order Cancel From Client Side \n
                    Klarna Transaction Id : ".$order['id']."\n".
                    "Total Price Paid : ".(intval($order['cart']['total_price_including_tax'])/100)."\n".
                    "Total Tax Paid   : ".(intval($order['cart']['total_tax_amount'])/100);
                $inTransactionId    =$order['id'];
                @$rsv                =   $order['reservation'];
                $invoiceStatus  =    '0'; //cancel
                $sql = "UPDATE  `order_invoice` SET
                             invoice_status = '$invoiceStatus',
                             orderStatus = '$status',
                             apiReturn = '$api',
                             inTransaction  = '$inTransactionId',
                             paymentType = '2',
                             rsvNo     = '$rsv',
                             payment_info = '$inTransiction'
                             WHERE order_invoice_pk = '$invoiceId' && orderUser = '$orderUser'";
                $dbF->setRow($sql);
                break;
            }

            if ($order['status'] == "checkout_complete") {
                // At this point make sure the order is created in your system and send a
                // confirmation email to the customer
                $update['status'] = 'created';
                $status='created';
                $update['merchant_reference'] = array(
                    'orderid1' => $invoiceId
                );
                $order->update($update);
            }



            $snippet = $order['gui']['snippet'];
            // DESKTOP: Width of containing block shall be at least 750px
            // MOBILE: Width of containing block shall be 100% of browser window (No
            // padding or margin)
            echo "<div>{$snippet}</div>";




            unset($_SESSION['klarna_checkout']);

            echo '<hr />';

            $status='process';
            $api    =   base64_encode(serialize($order));
            $inTransiction = "Klarna Transaction Id : ".$order['id']."\n".
                "Total Price Paid : ".(intval($order['cart']['total_price_including_tax'])/100)."\n".
                "Total Tax Paid   : ".(intval($order['cart']['total_tax_amount'])/100);
            $inTransactionId    =$order['id'];
            @$rsv                =   $order['reservation'];
            $sql = "UPDATE  `order_invoice` SET
                             orderStatus = '$status',
                             apiReturn = '$api',
                             inTransaction  = '$inTransactionId',
                             paymentType = '2',
                             rsvNo     = '$rsv',
                             payment_info = '$inTransiction'
                             WHERE order_invoice_pk = '$invoiceId' && orderUser = '$orderUser'";
            $dbF->setRow($sql);


            //Deduct Stock qty
            $functions->require_once_custom('orderInvoice');
            $orderInvoiceClass  =   new invoice();
            $returnStatus = $orderInvoiceClass->stockDeductFromOrder($invoiceId,false);
            if($returnStatus===false){
                throw new Exception("");
                return false;
            }

            //var_dump($order);
            // User Info Add
                    //first add order invoice,, addNewOrder();
                    $sql    =   "INSERT INTO `order_invoice_info`
                        (
                            `order_invoice_id`,

                            `sender_Id`,

                            `sender_name`,
                            `sender_phone`,
                            `sender_email`,
                            `sender_address`,
                            `sender_city`,
                            `sender_country`,
                            `sender_post`,

                            `receiver_name`,
                            `receiver_phone`,
                            `receiver_email`,
                            `receiver_address`,
                            `receiver_city`,
                            `receiver_country`,
                            `receiver_post`
                        )
                        VALUES (
                            ?, ?,
                            ?,?,?,?,?,?,?,
                            ?,?,?,?,?,?,?
                        )";
                    $array  =   array(
                        $invoiceId,$orderUser,
                        $order['billing_address']['given_name']." ".$order['billing_address']['family_name'],$order['billing_address']['phone'] ,
                        $order['billing_address']['email']  , $order['billing_address']['street_address']  ,
                        $order['billing_address']['city']  , $order['billing_address']['country'] ,
                        $order['billing_address']['postal_code'],

                        $order['shipping_address']['given_name']." ".$order['shipping_address']['family_name'] ,$order['shipping_address']['phone'] ,
                        $order['shipping_address']['email'] ,$order['shipping_address']['street_address'] ,
                        $order['shipping_address']['city'] ,$order['shipping_address']['country'],
                        $order['shipping_address']['postal_code']
                    );
                    $dbF->setRow($sql,$array,false);
            if($productClass->webUserId()=='0'){
                createWebUserAccount
                ($orderUser,$invoiceId,'1',$order['billing_address']['given_name']." ".$order['billing_address']['family_name'] ,$order['billing_address']['email'],
                    array
                    ('gender'=>$order['customer']['gender'],
                      'type'=>$order['customer']['type'],
                      'date_of_birth'=>$order['customer']['date_of_birth'],
                    )
                );
            }

            $_GET['mailId'] = $invoiceId;
            $msg2 = include(__DIR__.'/orderMail.php');


            $orderIdInvoice =   $functions->ibms_setting('invoice_key_start_with').$invoiceId;
            $orderIdInvoice =   $dbF->hardWords('ORDERING',false)." ($orderIdInvoice)";
            $fromName       =   $functions->webName;

            $mailArray['fromName']    =   $fromName;
            $functions->send_mail($order['billing_address']['email'],$orderIdInvoice,$msg2,'','',$mailArray);
            if($order['billing_address']['email'] != $order['shipping_address']['email']){
                // if order billing and shipping email not same then send email on both.
                $mailArray['fromName']    =   $fromName;
                $functions->send_mail($order['shipping_address']['email'],$orderIdInvoice,$msg2,'',$order['billing_address']['given_name'],$mailArray);
            }

            break;

        case 'cancel':       // Order was canceled...

            // The order was canceled before being completed.
            $invoiceId=(trim($_GET['inv']));
            ?>
            <h3><?php $dbF->hardWords('The order was canceled.');?></h3>
            <?php
            $status='canceled';

            $api    =   base64_encode(serialize($order));
            $inTransiction = "Klarna Transaction Id : ".$order['id']."\n".
                "Total Price Paid : ".(intval($order['cart']['total_price_including_tax'])/100)."\n".
                "Total Tax Paid   : ".(intval($order['cart']['total_tax_amount'])/100);
            $inTransactionId    =$order['id'];
            @$rsv                =   $order['reservation'];
            $sql = "UPDATE  `order_invoice` SET
                            orderStatus = '$status',
                             apiReturn = '$api',
                             inTransaction  = '$inTransactionId',
                             paymentType = '2',
                             rsvNo     = '$rsv',
                             payment_info = '$inTransiction'
                             WHERE order_invoice_pk = '$invoiceId' && orderUser = '$orderUser'";
            $dbF->setRow($sql);

            break;
    } // Isser acction Ends Here

}

function createWebUserAccount($orderUser,$invoiceId,$status='1',$name,$email,$settingArray=array()){
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
    }else{

    $today  = date("Y-m-d H:i:s");
    $unique =   uniqid();
    $password  =  $functions->encode($unique);

    $sql = "INSERT INTO accounts_user SET
                                acc_name = ?,
                                acc_email = ?,
                                acc_pass = ?,
                                acc_type = '$status',
                                acc_created = '$today'";
    $array = array($name,$email,$password);

    $dbF->setRow($sql,$array,false);
    $lastId = $dbF->rowLastId;

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

    $sql = "UPDATE  `order_invoice` SET
                            orderUser = '$lastId'
                             WHERE order_invoice_pk = '$invoiceId'";
    $dbF->setRow($sql,false);
    $sql = "UPDATE  `order_invoice_info` SET
                            sender_Id = '$lastId'
                             WHERE order_invoice_id = '$invoiceId'";
    $dbF->setRow($sql,false);

    $ThankWeSend = $dbF->hardWords('Thank you! We have sent verification email. Please check your email.',false);
    if($already){
        $password = $functions->decode($accData['acc_pass']);
        $mailArray['link']        =   $aLink;
        $mailArray['password']     =   $password;
        $functions->send_mail($email,'', '','accountCreateOnOrder','',$mailArray);
        return $msg = $ThankWeSend;
    }else{
        $mailArray['link']        =   $aLink;
        $mailArray['password']    =   $unique;
        $functions->send_mail($email,'', '','accountCreateOnOrder','',$mailArray);
        return $msg = $ThankWeSend;
    }


}
if(!isset($_GET['ajax'])){            ?>
</div>
    </div>
    </div>

<?php
include("footer.php");}
?>