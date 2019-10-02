<?php
include_once("global.php");
global $webClass;
$pMmsg = '';

if($functions->developer_setting('product_return_register_login') == '1') {
    $login = $webClass->userLoginCheck();
    if (!$login) {
        $return_referer = base64_encode('returns');
        header("Location: login.php?ref={$return_referer}");
        exit();
    }
}
$userId = $webClass->webUserId();

$contactAllow = true;

include_once('header.php'); ?>

<!--Inner Container Starts-->

<div class="container-fluid padding-0">
    <div class="standard container-fluid padding-0">
    <div class="home_links_heading h3 well well-sm"><?php $dbF->hardWords('Product replacement / return');?></div>

    <div class="inner_content_page_div container-fluid ">




<?php
if(isset($_POST['submit'])){
var_dump($_POST);
var_dump( isset($_POST['insert']['order_product']) ? $_POST['insert']['order_product'] : '' );

foreach ($_POST['insert']['order_product'] as $invoice_product_id) {
    var_dump($invoice_product_id);
## get the ordered product invoice
$sql ="SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoice_product_id'";
$data = $dbF->getRow($sql,false);

$pids     = $data['order_pIds'];
// $pids     = explode("-",$pids);
// $pId      = $pids[0];
// $scaleId  = $pids[1];
// $colorId  = $pids[2];
// $storeId  = $pids[3];
// $customId = $pids[4];
// $hashVal  = $pId.":".$scaleId.":".$colorId.":".$storeId;
// $hash     = md5($hashVal);
// $sale_qty = $data['order_pQty'];

// var_dump($hash);
var_dump($pids);

// 2 = returned and refunded
// 3 = defected and changed

// if( $productF->stockProductQtyPlus($hash,$sale_qty) ){
//     $sql = " UPDATE order_invoice_product SET order_process = '3' WHERE invoice_product_pk = '$invProductId' ";
//     $dbF->setRow($sql);
//     //$functions->setlog('Product Sale','Inventory',$invProductId,'Stock Deduct,StockId '.$invProductId.' :  QTY:'.$saleQTY,$transection);
// }
}


    //     if($functions->getFormToken('returnForm')){
    //         $lastId = $functions->formInsert("product_return_form",$_POST['insert']);
    //         if($lastId>0){
    //             $pMmsg  =   $dbF->hardWords('Replacement / Return Submit Successfully',false);
    //         }
    //         $contactAllow = false;
    //     }else{
    //         $contactAllow = true;
    //     }
    // if($pMmsg!=''){
    //     echo "<div class='alert alert-info'>$pMmsg</div>";
    // }
}

if($contactAllow){
?>

    <div class="col-sm-12">
    <br>
    <?php
        //$productClass->productReturnOrDefectForm();
    ?>

        <form id="returns_reg_form" class="form-horizontal" action="" method="post" enctype="multipart/form-data" >

<!--                 <div class="form-group">
                    <label class="col-sm-5 control-label">Enter your order to proceed</label>
                    <div class="col-sm-7">
                        <input id="order_number" type="text" name="insert[orderId]" class="form-control" placeholder="Enter order number" required="1" >
                    </div>
                </div> -->

<?php 

        // $sql        = " SELECT * FROM `order_invoice_product` WHERE `orderUser` = ? AND `orderStatus` = ? AND `order_invoice_id` = ? ORDER BY `order_invoice_pk` DESC ";
        $sql        = " SELECT * FROM `order_invoice` WHERE `orderUser` = ? AND `orderStatus` = ? ORDER BY `order_invoice_pk` DESC ";
        $invoice    = $dbF->getRows($sql,array($userId,'process'));
        $invoiceKey = $functions->ibms_setting('invoice_key_start_with');
        $select_box = '<select id="order_number" name="insert[orderId]" class="form-control" required="" >';
        $select_box .= "<option value=''>----------</option>";
        foreach ($invoice as $val) {
            $select_box .= "<option value='{$val['order_invoice_pk']}'>{$invoiceKey} {$val['order_invoice_pk']} (Date : {$val['dateTime']})</option>";
        }
        $select_box .= '</select>';


?>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Choose your order to proceed</label>
                    <div class="col-sm-7">
                        <?php echo $select_box; ?>
                    </div>
                </div>

                <div class="form-group" id="products_area" style="display:none">
                    <label class="col-sm-5 control-label">Choose products</label>
                    <div class="col-sm-7" id="products_area_insert">
                        
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-5 control-label"></label>
                    <div class="col-sm-7">
                        <input type="button" name="button" value="Continue" id="order_continue" class="btn btn-success" >
                        <input type="submit" name="submit" value="Send"     id="order_submit"   class="btn btn-success" >
                    </div>
                </div>


        </form>

    </div>

<script>
    $('#returns_reg_form').on('click', '#order_continue', function(event) {
        event.preventDefault();
        /* Act on the event */

        var order_id_value = $('#order_number').val();

        if (order_id_value != '') { 


            console.log('Clicked!');
            //#products_area
            //#products_area_insert


            $.ajax({
                url: 'ajax_call.php?page=orderProducts',
                type: 'POST',
                dataType: 'html',
                data: {order_id: order_id_value},
            })
            .done(function(data) {
                console.log("success");
                $('#products_area').show();
                $('#products_area_insert').html(data);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        };


    });
</script>


    </div>


    <div class="clearfix"></div>
<?php
}

echo "</div> </div>";
include_once('footer.php');