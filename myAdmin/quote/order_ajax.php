<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/order_ajax.php");
    $page=$_GET['page'];

    $ajax=new order_ajax();

    switch($page){
        //
        case 'getOrderProductJson':
            $ajax->getOrderProductJson();
            break;
        case 'getOrderProductStoreJson':
            $ajax->getOrderProductStoreJson();
            break;
        case 'shippingPrice':
            $ajax->finalPriceShipping();
            break;
        case 'delOrder':
            $ajax->delOrder();
            break;
        case 'data_ajax_all':
            $ajax->order_fetch($page);
            break;
        case 'quick_invoice_update':
            $ajax->quick_invoice_update($page);
            break;
    }


}

?>