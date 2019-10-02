<?php

ob_start();



require_once("classes/webUsers.class.php");

global $dbF;



$webUser  =   new webUsers();



//$dbF->prnt($_POST);

//$dbF->prnt($_FILES);

//exit;

$msg = $webUser->webUserEditSubmit();

@$id     = $_GET['userId'];

?>

<a href="-webUsers?page=view" class="btn btn-primary"><?php echo $_e['Back To WebUsers']; ?></a>

<h2 class="sub_heading borderIfNotabs"><?php echo $_e['Edit User Info']; ?></h2>



<?php



if($msg !=''){

    $functions->notificationError($_e['WebUsers'],$msg,'btn-info');

}

$webUser->webUserEdit();



$product = false;

if( ! empty($id) && $functions->developer_setting('cartSystem')=='1' && $functions->developer_setting('product')=='1') {

    $product = true;

    echo '<br><h2 class="sub_heading borderIfNotabs">'.$_e['User Orders'].'</h2>';



    $functions->includeAdminFile("product_management/classes/currency.class.php");

    $c_currency = new currency_management();

    $currency_data = $c_currency->getList(); // get currency list

    foreach ($currency_data as $val) {

        $cur_id = $val['cur_id'];

        $cur_symbol = md5($val['cur_symbol']);

        echo '<input type="hidden" class="currIds" value="' . $cur_symbol . '" />';

    }



    ?>

    <div class="countMeDiv">

        <?php echo $_e['Selected SubTotal'] ?> :

        <?php

        foreach($currency_data as $val) {

            $cur_id = $val['cur_id'];

            $cur_country = $val['cur_id'];

            $cur_symbol = md5($val['cur_symbol']);

            $symbol = ($val['cur_symbol']);

            echo "<div class='invoice_price_div'><span id='countMe_user_$cur_id' data-id='$cur_id' data-symbol='$symbol' class='printMe_user_$cur_symbol count_invoice'>0</span> $symbol</div>";

        }

        ?>



    </div>

<?php

    $functions->getAdminFile("/order/classes/order.php");

    $order = new order();

    $order->invoiceList('user',$id);



?>



<style>

    form .form-group {

        margin-bottom: 5px;

    }

</style>

    <script src="order/js/order.php"></script>



    <script>

        $(function () {

            $(document).on('keydown', '.dataTables_filter input',function (event){

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

                countOrderPrice('user');

            }, 500);

        }



    </script>



<?php }//products order reports end ?>







<script src="webUsers/js/user.js"></script>



<script>

    $(function(){

        dateJqueryUi();

    });



</script>

<?php return ob_get_clean(); ?>