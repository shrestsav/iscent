<?php
ob_start();
require_once("classes/order.php");
$order = new order();
//$dbF->prnt($_POST);
//exit;
// $order->addNewOrder();
$order->orderSubmit();
// print_r($_POST);

$functions->sessionMsg();

if (isset($_GET['deleteIncomplete'])) {
    $order->deleteOrders('deleteIncomplete');
}

if ($functions->developer_setting('product_Scale') == '0') {
    echo "<style>.allowProductScale{display:none;}</style>";
}

if ($functions->developer_setting('product_color') == '0') {
    echo "<style>.allowProductColor{display:none;}</style>";
}

$functions->includeAdminFile("product_management/classes/currency.class.php");
$c_currency = new currency_management();
$currency_data = $c_currency->getList(); // get currency list

foreach ($currency_data as $val) {
    $cur_id = $val['cur_id'];
    $cur_symbol = md5($val['cur_symbol']);
    echo '<input type="hidden" class="currIds" value="' . $cur_symbol . '" />';
}

?>

    <h4 class="sub_heading"><?php echo _uc($_e['Order Create/View']); ?></h4>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">

         <li class="all_orders active"><a href="#allOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['All Orders']); ?></a></li>
        <li><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Order Placed']); ?></a></li>
       
        <!-- <li><a href="#compOrder" role="tab" data-toggle="tab"><?php //echo _uc($_e['Complete Orders']); ?></a></li> -->
        <li><a href="#cancelOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['Cancel Orders']); ?></a></li>
        <li><a href="#pending_instal" role="tab" data-toggle="tab"><?php echo _uc($_e['Pending Installation']); ?></a></li>
        <li><a href="#liveOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['Live']); ?></a></li>
        <!-- <li><a href="#incompOrder" role="tab" data-toggle="tab"><?php //echo _uc($_e['InComplete Orders']); ?></a></li> -->
        <li><a href="#delRequest" role="tab" data-toggle="tab"><?php echo _uc($_e['Pending Removal']); ?></a></li>
        <li class="add_new_order"><a href="#newOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Order']); ?></a></li>
    </ul>

    <!-- Tab panes -->
<script>
    $(document).ready(function(){
        $(".nav-tabs li").click(function(){
            if($(this).hasClass("add_new_order")){
                $("#sortByDate").hide();
            }else{
                $("#sortByDate").show();
            }
        });
    });
</script>
    <div class="tab-content">
        <?php //$functions->dataTableDateRange(); ?>

<?php
        function print_pricing_div($type)
        {   
            global $currency_data;

            $pricing_div = '';
            foreach ($currency_data as $val) {
                $cur_id = $val['cur_id'];
                $cur_country = $val['cur_id'];
                $cur_symbol = md5($val['cur_symbol']);
                $symbol = ($val['cur_symbol']);
                $pricing_div .= "<div class='invoice_price_div'><span id='countMe_{$type}_$cur_id' data-id='$cur_id' data-symbol='$symbol' class='printMe_{$type}_$cur_symbol count_invoice'>0</span> $symbol</div>";
            }
            return $pricing_div;
        }

?>

        <div class="tab-pane fade container-fluid" id="home">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Order Placed']); ?></h2>
            </div>
            <?php $order->invoiceList('invoices'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="pending_instal">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Pending Installation']); ?></h2>
            </div>
            <?php $order->invoiceList('pending_inst'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="liveOrder">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Live']); ?></h2>
            </div>
            <?php $order->invoiceList('live'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="compOrder" style="display: none;">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Complete Orders']); ?></h2>
            </div>

            <?php //$order->completeOrdersSql(); ?>

            <?php $order->invoiceList('complete'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="cancelOrder">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Cancel Orders']); ?></h2>
            </div>
            <?php $order->invoiceList('cancel'); ?>
        </div>

        <div class="tab-pane fade in active container-fluid" id="allOrder">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['All Orders']); ?></h2>
            </div>

            <?php $order->invoiceList('all'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="incompOrder" style="display: none;">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['InComplete Orders']); ?></h2>
            </div>

            <?php $order->invoiceList('incomplete'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="delRequest">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Pending Removal']); ?></h2>
            </div>

            <?php $order->invoiceList('delete_request'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="newOrder">
            <h2 class="tab_heading"><?php echo _uc($_e['Add New Order']); ?></h2>
            <?php $order->newOrderForm(); ?>
        </div>
    </div> <!-- tab-content div end-->

<?php $functions->dialogCommon('dialog', 'Order View'); ?>

<style>

.dataTables_processing {
    position: fixed;
    top: 50%;
    left: 50%;
    border: none; 
    background: none;
}

.heading_invoice {
    position: relative;
}

.small_btn {
    position: absolute;
    right: 0;
    top: -30px;
}

@media (max-width: 992px) {

.small_btn{
    position: static;
    display: block;
    clear: both;
}

}
#SelectProduct{
    cursor: pointer;
    position: relative;
}
#SelectProduct:before{
   content: "";
position: absolute;
top: 8px;
right: 10px;
height: 10px;
width: 10px;
border-right: 1px solid #aaa;
border-bottom: 1px solid #aaa;
transform: rotate(40deg);
}
#SelectProduct ul{
position: absolute;
list-style: none;
top: calc(100% + 0px);
left: 0;
background-color: #fff;
border: 1px solid #ccc;
width: 100%;
border-radius: 5px;
z-index: 2;
display: none;
height: 250px;
overflow-x: auto;
}
#SelectProduct ul li{
padding: 10px;
border-bottom: 1px solid #ccc;
}
#SelectProduct ul li h4{
    color: #444;
font-size: 18px;
margin: 0;
}
#SelectProduct ul li h5{
    font-size: 14px;
color: #444;
display: block;
margin: 5px 0;
}
</style>

    <script src="order/js/order.php"></script>
    <script>
        $(function () {
            dateJqueryUi();
            // minMaxDate();
            // dTableRangeSearch();
            minMaxDateFilter();

            $(document).on('keydown', '.dataTables_filter input', function (event) {
                orderPrice();
            });

            $("#DataTables_Table_0_length_select,#DataTables_Table_1_length_select,#DataTables_Table_2_length_select,#min,#max").change(function () {
                orderPrice();
            });

            setTimeout(function () {
                orderPrice();
            }, 100);

        });

        function orderPrice() {
            setTimeout(function () {
                countOrderPrice('invoices');
                countOrderPrice('cancel');
                countOrderPrice('complete');
                countOrderPrice('all');
                countOrderPrice('incomplete');
            }, 500);
        }

        function changeInvoiceStatus(ths){
            inv_id = $(ths).data('id');
            $('#statusOption_'+inv_id).css('display', 'block');
        }

        function updateInvoiceStatus(ths){
            order_id = $(ths).data('id');
            status = $(ths).val();

            $.ajax({
                url: 'order/order_ajax.php?page=updateOrderStatus',
                type: 'post',
                data: {order_id: order_id, status: status}
            }).done(function(res){
                console.log(res);
                if(res == '1'){
                    location.reload();
                }else{
                    alert('Something Went Wrong, Please Try Again!');
                }
            });
        }

    </script>
    <!-- using stock js same here.. -->

<script>

    // function fetch_ajax_result_again (dateCodeFrom, dateCodeTo) {

    //     my_dtable = $.fn.dataTable.tables( { visible: true, api: true } );
    //     $(my_dtable).DataTable().ajax.reload();


    // }


    // $('.dTableFull').on( 'draw.dt', function () {
    //     console.log( 'Table redrawn' );
    // });

</script>

<?php return ob_get_clean(); ?>