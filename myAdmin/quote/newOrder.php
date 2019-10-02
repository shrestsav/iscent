<?php
ob_start();
require_once("classes/order.php");
$order = new order();
//$dbF->prnt($_POST);
//exit;
$order->addNewOrder();
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

    <h4 class="sub_heading"><?php echo _uc($_e['Quote Create/View']); ?></h4>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <!-- <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php //echo _uc($_e['InProcess Invoices']); ?></a></li> -->
        <li class="all_orders active"><a href="#allOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['All Quotes']); ?></a></li>
        <!-- <li><a href="#compOrder" role="tab" data-toggle="tab"><?php //echo _uc($_e['Complete Orders']); ?></a></li>
        <li><a href="#cancelOrder" role="tab" data-toggle="tab"><?php //echo _uc($_e['Cancel Orders']); ?></a></li>
        <li><a href="#incompOrder" role="tab" data-toggle="tab"><?php //echo _uc($_e['InComplete Orders']); ?></a></li> -->
        <li class="add_new_order"><a href="#newOrder" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Quotes']); ?></a></li>
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
        <?php $functions->dataTableDateRange1(); ?>

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

        <!-- <div class="tab-pane fade in active container-fluid" id="home">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php //echo _uc($_e['InProcess Invoices']); ?></h2>

                <div class="countMeDiv">
                    <?php //echo $_e['Selected SubTotal'] ?> :

                    <?php //echo print_pricing_div('invoices'); ?>

                </div>
            </div>
            <?php //$order->invoiceList('invoices'); ?>
        </div> -->

        <div class="tab-pane fade in active container-fluid" id="compOrder" hidden>
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Complete Orders']); ?></h2>

                <div class="countMeDiv">
                    <?php //echo $_e['Selected SubTotal'] ?> 
                    <?php
                    // foreach ($currency_data as $val) {
                    //     $cur_id = $val['cur_id'];
                    //     $cur_country = $val['cur_id'];
                    //     $cur_symbol = md5($val['cur_symbol']);
                    //     $symbol = ($val['cur_symbol']);
                    //     echo "<div class='invoice_price_div'><span id='countMe_complete_$cur_id' data-id='$cur_id' data-symbol='$symbol' class='printMe_complete_$cur_symbol count_invoice'>0</span> $symbol</div>";
                    // }
                    ?>
                    <?php //echo print_pricing_div('complete'); ?>

                </div>
            </div>

            <?php $order->invoiceList('complete'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="cancelOrder" hidden>
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['Cancel Orders']); ?></h2>

                <div class="countMeDiv">
                    <?php echo $_e['Selected SubTotal'] ?> :
                    <?php
                    // foreach ($currency_data as $val) {
                    //     $cur_id = $val['cur_id'];
                    //     $cur_country = $val['cur_id'];
                    //     $cur_symbol = md5($val['cur_symbol']);
                    //     $symbol = ($val['cur_symbol']);
                    //     echo "<div class='invoice_price_div'><span id='countMe_cancel_$cur_id' data-id='$cur_id' data-symbol='$symbol' class='printMe_cancel_$cur_symbol count_invoice'>0</span> $symbol</div>";
                    // }
                    ?>
                    <?php echo print_pricing_div('cancel'); ?>

                </div>
            </div>
            <?php $order->invoiceList('cancel'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="allOrder">
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['All Orders']); ?></h2>

                <div class="countMeDiv">
                    <?php echo $_e['Selected SubTotal'] ?> :

                    <?php echo print_pricing_div('all'); ?>
                    
                </div>
            </div>

            <?php $order->invoiceList('all'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="incompOrder" hidden>
            <div class="heading_invoice">
                <h2 class="tab_heading"><?php echo _uc($_e['InComplete Orders']); ?></h2>
                <small class="small_btn"><a href="-order?page=newOrder&deleteIncomplete"
                          class="btn btn-info btn-sm"><?php echo _n($_e['Delete All Old Incomplete Orders']); ?></a></small>

                <div class="countMeDiv">
                    <?php echo $_e['Selected SubTotal'] ?> :

                    <?php echo print_pricing_div('incomplete'); ?>
                    
                </div>
            </div>

            <?php $order->invoiceList('incomplete'); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="newOrder">
            <h2 class="tab_heading"><?php echo _uc($_e['Add New Quote']); ?></h2>
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

</style>

    <script src="quote/js/order.php"></script>
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