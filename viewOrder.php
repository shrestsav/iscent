<?php include("global.php");

global $webClass;

$login = $webClass->userLoginCheck();

$loginForOrder = $functions->developer_setting('loginForOrder');

if (!$login && $loginForOrder != '1') {

    header("Location: login.php");

    exit;

}

$userId = $webClass->webUserId();

if ($userId == '0') {

    $userId = webTempUserId(); // for all orders on temp user..

}



include("header.php");

require_once(__DIR__ . '/' . ADMIN_FOLDER . '/order/classes/order.php');

$orderC = new order();



if(isset($_GET['success'])){

    $functions->mail_success_msg();

}
//my code
if(isset($_GET['order']) && isset($_GET['firstInv'])){

$order_id = $_GET['order'];
$firstInv = $_GET['firstInv'];

$sql = "SELECT `order_ref` FROM `orders` WHERE `order_id` = ?";
$res = $dbF->getRow($sql, array($order_id));

$order_ref = $res['order_ref'];

$sql_det = "SELECT `company_name`,`country`,`mobile` FROM `order_detail` WHERE `order_id` = ?";
$res_det = $dbF->getRow($sql_det, array($order_id));

$company_name = $res_det['company_name'];
$country = $res_det['country'];
$mobile = $res_det['mobile'];

$params = array(
'ivp_method'  => 'check',
'ivp_store'   => '20901',
'ivp_authkey' => 'vJJrn~6LpK-6FR8f',
'order_ref'   => $order_ref
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
curl_setopt($ch, CURLOPT_POST, count($params));
curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

$results = curl_exec($ch);
curl_close($ch);

$api_return = base64_encode($results);

$results = json_decode($results,true);
//print_r($results);
$order_status   = $results['order']['status']['code'];
$trans_ref      = $results['order']['transaction']['ref'];
$trans_code     = $results['order']['transaction']['code'];
$agrement_id    = $results['order']['agreement']['id'];

$email  = $results['order']['customer']['email'];
$name   = $results['order']['customer']['name']['forenames'].' '.$results['order']['customer']['name']['surname'];
$address    = $results['order']['customer']['address']['line1'].', '.$results['order']['customer']['address']['city'].', '.$results['order']['customer']['address']['country'];

switch ($order_status) {
    case '3':

    $sql = "UPDATE `orders` SET 
`order_status` = ?,
`agrement_id` = ?
WHERE `order_id` = ?";

$dbF->setRow($sql, array('process',$agrement_id,$order_id), false);

$sql = "UPDATE `invoices` SET 
`invoice_status` = 'paid',
`trans_ref` = ?,
`trans_code` = ?,
`api_return` = ? 
WHERE `invoice_pk` = ?";
$dbF->setRow($sql, array($trans_ref, $trans_code, $api_return, $firstInv), false);

        $sql = "UPDATE `orders` SET `del_request` = 1, `status` = 'pending_remove' WHERE `order_id` = ?";
        $res = $dbF->setRow($sql, array($order));

$sql = "SELECT `order_user` FROM `orders`
WHERE order_id = '$order_id'";
$res = $dbF->getRow($sql);

$user_id = $res['order_user'];
$sql = "SELECT `zoho_contact_id`, `zoho_contact_person` FROM `accounts_user` WHERE `acc_id` = ?";
$res = $dbF->getRow($sql, array($user_id));

if(!empty($res)){

$zoho_contact_id     = $res['zoho_contact_id'];
$zoho_contact_person = $res['zoho_contact_person'];

$sql_invDet = "SELECT * FROM `invoices` WHERE `invoice_pk` = ?";
$res_invDet = $dbF->getRow($sql_invDet, array($firstInv));

$sql_pid = "SELECT o.`product_id` FROM `orders` o JOIN `invoices` i WHERE o.`order_id` = i.`order_id` AND i.`invoice_pk` = ?";
$res_pid = $dbF->getRow($sql_pid, array($firstInv));

$sql_item = "SELECT pd.`prodet_name`,pd.`prodet_shortDesc`,pd.`zoho_item_no`,pp.`propri_price` FROM `proudct_detail` pd JOIN `product_price` pp WHERE pd.`prodet_id` = pp.`propri_prodet_id` AND pd.`prodet_id` = ?";
$res_item = $dbF->getRow($sql_item, array($res_pid['product_id']));

$pro_name = translateFromSerialize($res_item['prodet_name']);
$pro_desc = translateFromSerialize($res_item['prodet_shortDesc']);



$order_invoice_print = $functions->ibms_setting('invoice_key_start_with').$order_id.' - '.$firstInv;

$invoice_det = array(
'customer_id' => $zoho_contact_id,
'contact_persons' => array($zoho_contact_person),
'invoice_number' => $order_invoice_print,
'date' => $res_invDet['due_date'],
'line_items' => array(
array(
'item_id' => $res_item['zoho_item_no'],
'name' => $pro_name,
'description' => $pro_desc,
'item_order' => 1,
'rate' => doubleval($res_invDet['price']),
'quantity' => 1
)
)


);


$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

// Zoho Books Refresh Token with Scope of Full Access ( ZohoBooks.fullaccess.all ).
$refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 


$params = array(
'refresh_token' => $refresh,
'client_id' => $client_id,
'client_secret' => $secret,
'redirect_uri' => WEB_URL.'/orderInvoice.php',
'grant_type' => 'refresh_token'
);

// Using refresh token to generate access token.
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
curl_setopt($ch, CURLOPT_POST, count($params));
curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results = curl_exec($ch);
curl_close($ch);

$array = json_decode($results,true);

$access_token = $array['access_token']; // Access Token


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/invoices?organization_id=667162566&ignore_auto_number_generation=true");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($invoice_det));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results2 = curl_exec($ch);
curl_close($ch);

$array2 = json_decode($results2,true);

if($array2['code'] == '0'){
$zoho_inv_id = $array2['invoice']['invoice_id'];

$sql_upd = "UPDATE `invoices` SET `zoho_inv_id` = ? WHERE `invoice_pk` = ?";
$dbF->setRow($sql_upd, array($zoho_inv_id,$firstInv), false);
}

}
$_GET['mailId'] = $order_id;
$msg2 = include(__DIR__.'/orderMail.php');

$orderIdInvoice =   $functions->ibms_setting('invoice_key_start_with').$order_id;
// $orderIdInvoice =   $dbF->hardWords('Thank you for your purchase. Order ID ',false)." ($orderIdInvoice)";
$orderIdInvoice =   $dbF->hardWords('Cancelled. Order ID ',false)." ($orderIdInvoice)";
$fromName       =   $functions->webName;

$mailArray['fromName']    =   $fromName;
$functions->send_mail($email,$orderIdInvoice,$msg2,'',$name,$mailArray);

$adminMail = $functions->ibms_setting('Email');
$functions->send_mail($adminMail,$orderIdInvoice,$msg2,'','',$mailArray);

break;
    
case '-2':
//     $sql = "UPDATE `orders` SET 
// `order_status` = ?
// WHERE `order_id` = ?";
// $dbF->setRow($sql, array('cancelled', $order_id), false);
$sql="DELETE FROM invoices WHERE invoice_pk='$firstInv'";
$dbF->setRow($sql);

break;

case '-3':
// $sql = "UPDATE `orders` SET 
// `order_status` = ?
// WHERE `order_id` = ?";
// $dbF->setRow($sql, array('cancelled', $order_id), false);
$sql="DELETE FROM invoices WHERE invoice_pk='$firstInv'";
$dbF->setRow($sql);

break;
}

}
//my code
?>

<div class="bg_inner" style="background-image: url(<?php echo WEB_URL.'/images/default_banner.jpg' ?>)"></div>

    <div class="container-fluid padding-0 inner_details_container">
        <?php //$dbF->prnt($_REQUEST); ?>
        <div class="standard">


            <div class="home_links_heading h3 well well-sm">
<a href="#" onclick="goBack()">
                <?php $dbF->hardWords('Back'); ?>
                </a>
            </div>
            <div class="home_links_heading h3 well well-sm"><?php



                if (!isset($_GET['view']) && !isset($_GET['editCustom']) && !isset($_GET['pId'])) {

                    $dbF->hardWords('Order list');

                } else {

                    $dbF->hardWords('Order Invoice Information');

                }



                ?></div>

            <div class="inner_content_page_div container-fluid">

                <?php

                if (!isset($_GET['view']) && !isset($_GET['editCustom']) && !isset($_GET['pId'])) {

                    //list of all orders

                    $orderC->invoiceListUser('user',$userId);
                    // $orderC->invoiceList('user',$userId);

                    echo "<br><hr><br>";

                }





                if (isset($_GET['view']) || isset($_GET['editCustom']) || isset($_GET['submit'])) {

                    //creating object of class.

                    $functions->getPage("viewOrder.php");

                    $viewOrder = new viewOrder();

                }

                if(isset($_GET['pId'])){ 
                    $orderId = @$_GET['pId'];
                    ?>

                    <div class="table-responsive newProduct col-sm-12">
                        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                               cellspacing="0">
                            <thead>
                            <th colspan="6">
                                <div class="text-center"><?php echo 'Invoice Detail'; ?></div>
                            </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th><?php echo 'SNO'; ?></th>
                                    <th><?php echo 'Invoice ID'; ?></th>
                                    <th><?php echo 'Price'; ?></th>
                                    <th><?php echo 'Payment Status'; ?></th>
                                    <th><?php echo 'Transaction Reference'; ?></th>
                                    <th><?php echo 'Payment Type'; ?></th>
                                </tr>

                                <?php 

                                    $sql = "SELECT * FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'paid' ORDER BY `due_date` ASC";
                                    $res = $dbF->getRows($sql, array($orderId));

                                    $last_paid_date = '';
                                    $count = 0;
                                    foreach ($res as $key => $value) {
                                        $count++;

                                        $inv_id = $value['invoice_pk'];

                                        if(empty($value['api_return'])){
                                            $view_api_info = '';
                                        }else{
                                            $view_api_info = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$orderId&apiData=$inv_id'>View Api Return Info</a>";
                                        }

                                        $last_paid_date = $value['due_date'];

                                        echo "<tr>
                                                <td>".$count."</td>
                                                <td>".$value['invoice_pk']."</td>
                                                <td>".$value['price']."</td>
                                                <td>".$value['invoice_status']."</td>
                                                <td>".$value['trans_ref']."</td>
                                                <td>Telr</td>
                                            </tr>";
                                    }

                                    $next_due_date = date('Y-m-d', strtotime("+1 day $last_paid_date"));

                                    $sql = "SELECT * FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending' AND `due_date` > '2018-09-25' ORDER BY `due_date` ASC LIMIT 1";
                                    $res = $dbF->getRow($sql, array($orderId));

                                    if(!empty($res)):

                                    $nextcount = $count+1;

                                    echo "<tr>
                                            <td>".$nextcount."</td>
                                            <td>".$res['invoice_pk']."</td>
                                            <td>".$res['price']."</td>
                                            <td>".$res['invoice_status']."</td>
                                            <td>".$res['trans_ref']."</td>
                                            <td>Telr</td>
                                        </tr>";

                                    endif;

                                    $sql = "SELECT * FROM `invoices` WHERE `order_id` = ? ORDER BY `due_date` DESC LIMIT 1";
                                    $res = $dbF->getRow($sql, array($orderId));

                                    $sql_count = "SELECT count(*) AS 'count' FROM `invoices` WHERE `order_id` = ? AND `invoice_status` = 'pending'";
                                    $res_count = $dbF->getRow($sql_count, array($orderId));

                                    if(!empty($res)):
                                    echo "<tr>
                                            <td>".$res_count['count']."</td>
                                            <td>".$res['invoice_pk']."</td>
                                            <td>".$res['price']."</td>
                                            <td>".$res['invoice_status']."</td>
                                            <td>".$res['trans_ref']."</td>
                                            <td>Telr</td>
                                        </tr>";
                                    endif;
                                     ?>
                            </tbody>
                        </table>
                    </div>


               <?php }



                if (isset($_GET['view'])) {

                    //view submit orders invoice

                    $viewOrder->viewOrder($_GET['view']);

                } else if (isset($_GET['editCustom'])) {

                    //edit custom measurement order.

                    echo $viewOrder->editCustomOrder($_GET['editCustom']);

                } else if (isset($_GET['submit'])) {

                    //custom form submit

                    $msg = $viewOrder->customFormSubmit();

                    if (!empty($msg)) {

                        $functions->jAlertifyAlert($msg);

                    }

                }



                ?>

            </div>

        </div>

    </div>





    <div class="modal fade" id="customF_vieworder" tabindex="1" role="dialog" aria-labelledby="customF_1047ModalLabel"

         aria-hidden="true" style="display: none;">

        <div class="modal-dialog ">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span

                            class="sr-only">Close</span></button>

                    <h4 class="modal-title" id="customF_1047ModalLabel">View Order</h4>

                </div>

                <div class="modal-body" style="text-align: center;">

                    <?php $box = $webClass->getBox('box12'); ?>



                    <div class="vieworder_text" style="width:100%; text-align:center;">

                        <?php echo $box['text']; ?>

                    </div>





                    <div class="form-horizontal">



                        <label class="col-sm-2 col-md-3  control-label"></label>



                        <div class="col-sm-10  col-md-9" style="width: 100%;">

                            <label class="checkbox"><input type="checkbox" name="customSubmit_later_1047" value="1"

                                                           id="custom_check" class="btn btn-danger"> I ACCEPT</label>

                        </div>

                    </div>



                    <div class="form-group">

                        <label class="col-sm-2 col-md-3  control-label"></label>



                    </div>

                </div>

                <div class="" style="margin:0 auto; text-align: center; margin-top: 10px;">

                    <input type="submit" name="submit" value="<?php echo $_e['Send To Factory']; ?>" id="custom_submit"

                           class="btn themeButton">

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                </div>

            </div>

        </div>

    </div>


<script type="text/javascript">
    
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return !!pattern.test(str);
}

   function delOrderInvoice(ths){
        order = $(ths).data('id');

        confirm_order = 'Are you sure you want to Delete?\nIf you are canceling before the 12th month, please note you will be charged a cancelation fee of 499dhs.';

        if(secure_delete(confirm_order)){
            $.ajax({
                url: 'ajax_call.php?page=cancelOrder',
                type: 'post',
                data: {order:order}
            }).done(function(res){
                if(validURL(res)){location.replace(res)}
                else if(res == '1'){
                    alert('Your request has been processed, someone from our operations team will contact you shortly to arrange pick up of the system');
                }else{
                    
                    alert('Something Went Wrong! Please Try Again.');
                }
                console.log(res);

            });
        }


    function secure_delete(text){
        // text = 'view on alert';
        text = typeof text !== 'undefined' ? text : 'Are you sure you want to Delete?';

        bool=confirm(text);
        if(bool==false){return false;}else{return true;}

    }
function goBack() {
    window.history.back();
}
</script>


<style>

.home_links_heading {

    min-height: 40px;

    text-transform: uppercase;

    width: 100%;

    text-align: center;

    color: #000;

    font-size: 22px;

    font-family: 'ralewayextrabold';

    margin-bottom: 20px;

}

.inner_content_page_div {

    display: inline-block;

    width: 100%;

    padding-bottom: 10px;

    min-height: 300px;

}

.tableIBMS {

    border-spacing: 2px;

    border-collapse: separate;

    font-size: 13px;

    width: 100%;

}

.t_head {

    color: #fff;

    border-radius: 5px;

    background-color: #222;

    vertical-align: middle;

    display: inline-block;

    padding: 5px;

    text-align: center;

}

.gray-tr {

    margin-top: 7px;

}

.col_black {

    color: #000;

    text-shadow: 0px 0px 0px #000;

}

.margin-right {

    margin-right: 35px;

}

.t_desc {

    background-color: #f5f5f5;

    border: 1px solid #aaa;

    border-radius: 5px;

    text-align: center;

    padding: 5px;

    border-top: 1px solid #ddd;

}

.tableIBMS th {

    color: #fff;

    border-radius: 5px;

    background-color: #222 !important;

    text-align: center;

    vertical-align: middle !important;

}

.tableIBMS td {

    position: relative;

}

.tableIBMS td {

    border: 1px solid #aaa;

    border-radius: 5px;

    text-align: center;

    vertical-align: middle !important;

}

.padding-20 {

    padding: 20px 0;

}

.d_t_c {

    display: table-cell;

    float: none;

    vertical-align: middle;

}

.d_t {

    display: table;

}

</style>



<?php include("footer.php"); ?>