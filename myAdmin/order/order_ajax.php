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
 case 'deldeleteScheduleForm':

            $ajax->deldeleteScheduleForm();

            break;
        case 'data_ajax_complete':

        case 'data_ajax_incomplete':

        case 'data_ajax_all':

        case 'data_ajax_cancel':

        case 'data_ajax_invoices':

            $ajax->order_fetch($page);

            break;

        case 'quick_invoice_update':

            $ajax->quick_invoice_update($page);

            break;

        case 'submitSceduleForm':

            $ajax->submitSceduleForm();

            break;

        case 'getAvailableSlots':

            $ajax->getAvailableSlots();

            break;

        case 'openTechnicalForm':

            $ajax->openTechnicalForm();

            break;

        case 'submitTechnicalForm':

            $ajax->submitTechnicalForm();

            break;

        case 'viewTechnicalForm':

            $ajax->viewTechnicalForm();

            break;

        case 'cancelAgreement':

            $ajax->cancelAgreement();

            break;

        case 'editSchedule':

            $ajax->editSchedule();

            break;

        case 'submitEditSchedule':

            $ajax->submitEditSchedule();

            break;

        case 'updateOrderStatus':

            $ajax->updateOrderStatus();

            break;

        //mycode    
        case 'updateInvoice':

            $ajax->updateInvoice();

            break;  
        //mycode      

    }





}



?>