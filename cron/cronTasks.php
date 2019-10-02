<?php
include_once(__DIR__.'/../global.php');

@$taskTime = $_GET['time'];
if(empty($taskTime)){
    exit;
}
if($taskTime=='test'){
    $email = "asad_raza99@yahoo.com";
    $functions->send_mail($email,"sharspeed testing cron","from cron task");
    exit;
}


switch($taskTime){
    case "daily":
            dailySalesTriggerProduct();
            orderThankYouMail();
            in_stock_trigger();
            notReturningCustomer();
        break;
}


function dailySalesTriggerProduct(){
    global $functions,$productClass,$dbF;
    if($functions->developer_setting('salesTriggerMail') != '1'){
        return false;
    }

    $sql    = "SELECT DISTINCT (p_id) FROM  product_subscribe WHERE `type` = 'sale' ";
    $data   = $dbF->getRows($sql);

    foreach($data as $val){
        $pId    = $val['p_id'];
        $data2  = $productClass->productData($pId);
        if(empty($data2)){
            continue;
        }
        $currencyId = $productClass->currentCurrencyId();
        $discount   = $productClass->productF->productDiscount($pId, $currencyId);

        if(empty($discount)){
            continue;
        }

        $productClass->productF->productOnSaleTrigger($pId);
    }
    echo "Sales Trigger Mail Execute Successfully <br>";
}

function in_stock_trigger(){
    global $functions,$productClass,$dbF;
    if($functions->developer_setting('in_stock_email_subscription') != '1'){
        return false;
    }

    $sql    = "SELECT * FROM product_subscribe WHERE `type` = 'stock' GROUP BY p_id,color_id, scale_id, store_id";
    // SELECT `qty_product_id`, AVG(`qty_item`) FROM `product_inventory` GROUP BY `qty_product_id` // sample query to check stock status W/O any color,size check, TODO: use $getInfo = $productClass->inventoryReport($pId); for a proper report of size and color.
    $data   = $dbF->getRows($sql);

    foreach($data as $val){
        $pId         = $val['p_id'];
        $color_id    = $val['color_id'];
        $scale_id    = $val['scale_id'];
        $store_id    = $val['store_id'];


        $stockStatus    = $productClass->productF->productQTY($pId,$store_id,$scale_id,$color_id);
        # if not in stock then don't send email
        if( ! $stockStatus){
            continue;
        }
        //var_dump($store_id,$scale_id,$color_id);

        $productClass->productF->product_in_stock_trigger($pId,$store_id,$scale_id,$color_id);
    }
    echo "Product in stock E-mail executed successfully <br>";
}

function orderThankYouMail(){
    //send mail to customer who purchase 15 days ago order..
    global $functions,$productClass,$dbF;
    $date   = date('Y-m-d H:i:s',strtotime("-15 days"));

    $saleTriggerLetter = 'orderThankYouMail';
    //get letter id
    $sql    = "SELECT id FROM  email_letters WHERE `email_type` = '$saleTriggerLetter'";
    $dataLetter   = $dbF->getRow($sql);
    if(empty($dataLetter)){
        return false;
    }
    $letterId = $dataLetter['id'];

    //check is data has or not
    $sql    = "SELECT sender_name,sender_email,order_invoice_id
                FROM `order_invoice_info` WHERE order_invoice_id
                  IN (SELECT order_invoice_pk FROM `order_invoice`
                   WHERE invoice_date = '$date' ) GROUP BY order_invoice_id";
    $data   = $dbF->getRow($sql);
    if(empty($data)){
        return false;
    }

    $sql    =   "INSERT INTO email_letter_queue(`letter_id`,`grp`,`email_name`,`email_to`,`p_id`,`status` )
                    SELECT '$letterId','orderThankYouMail',sender_name,sender_email,order_invoice_id,'1'
                    FROM `order_invoice_info` WHERE order_invoice_id
                      IN (SELECT order_invoice_pk FROM `order_invoice`
                       WHERE invoice_date = '$date' ) GROUP BY order_invoice_id";
    $dbF->setRow($sql);
    if(!$dbF->rowCount){
        return false;
    }
    //run cron job
    $functions->cronJob();
    echo "order ThankYouMail Execute Successfully <br>";
}


function notReturningCustomer(){
    //send mail to customer who purchase 15 days ago order..
    global $functions,$productClass,$dbF;
    $date   = date('Y-m-d',strtotime("-60 days"));
    $date2   = date('Y-m-d',strtotime("-59 days"));

    $date = "2015-04-10";
    $date2 = "2015-04-11";
    $saleTriggerLetter = 'notReturningCustomer';
    //get letter id
    $sql    = "SELECT id FROM  email_letters WHERE `email_type` = '$saleTriggerLetter'";
    $dataLetter   = $dbF->getRow($sql);
    if(empty($dataLetter)){
        return false;
    }
    $letterId = $dataLetter['id'];

    //check is data has or not
    $sql    = "SELECT * FROM `order_invoice` where invoice_date >= '$date' AND invoice_date <= '$date2'
                      AND orderUser NOT IN
                    (SELECT orderUser FROM order_invoice WHERE  invoice_date > '$date2')";
    $data   = $dbF->getRow($sql);
    if(empty($data)){
        return false;
    }

   $sql    =   "INSERT INTO email_letter_queue(`letter_id`,`grp`,`email_name`,`email_to`,`p_id`,`status` )
                      SELECT '$letterId','notReturningCustomer',sender_name,sender_email,order_invoice_id,'1'
                      FROM `order_invoice_info` WHERE order_invoice_id
                        IN (
                          SELECT order_invoice_pk FROM `order_invoice` where invoice_date >= '$date' AND invoice_date <= '$date2'
                          AND orderUser NOT IN
                          (SELECT orderUser FROM order_invoice WHERE  invoice_date > '$date2')
                          ) GROUP BY order_invoice_id";
    $dbF->setRow($sql);
    if(!$dbF->rowCount){
        return false;
    }

    //run cron job
    $functions->cronJob();
    echo "not Returning Customer Execute Successfully <br>";
}


?>