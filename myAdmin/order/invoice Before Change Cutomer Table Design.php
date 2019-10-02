<?php
ob_start();
echo '<h4 class="sub_heading borderIfNotabs">Invoice Detail View</h4>';
require_once("classes/invoice.php");
$invoice = new invoice();
$invoice->update();

$pId = $_POST['pId'];
$data = $invoice->invoiceDetail($pId);
//var_dump(unserialize(base64_decode($data['apiReturn'])));

?>
<!-- sender detail -->
    <div class="table-responsive newProduct" >
        <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <th colspan="7">
                <div class="text-center">ORDER SENDER DETAIL</div>
            </th>
            </thead>
            <tr class="gray-tr">
                <th><?php $dbF->hardWords('Name');?></th>
                <th><?php $dbF->hardWords('E-mail');?></th>
                <th><?php $dbF->hardWords('Phone');?></th>
                <th><?php $dbF->hardWords('Address');?></th>
                <th><?php $dbF->hardWords('Post');?></th>
                <th><?php $dbF->hardWords('City');?></th>
                <th><?php $dbF->hardWords('Country');?></th>
            </tr>
            <tr>
                <td><?php echo $data['sender_name']; ?></td>
                <td><?php echo $data['sender_email']; ?></td>
                <td><?php echo $data['sender_phone']; ?></td>
                <td><?php echo $data['sender_address']; ?></td>
                <td><?php echo $data['sender_post']; ?></td>
                <td><?php echo $data['sender_city']; ?></td>
                <td><?php echo $data['sender_country']; ?></td>
            </tr>
        </table>
    </div>
<!-- sender detail end -->

<div class="clearfix"></div>
<div class="padding-20"></div>

<!-- receiver detail -->
    <div class="table-responsive newProduct" >
        <table id="receiverInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <th colspan="7">
                <div class="text-center">ORDER RECEIVER DETAIL</div>
            </th>
            </thead>
            <tr class="gray-tr">
                <th><?php $dbF->hardWords('Name');?></th>
                <th><?php $dbF->hardWords('E-mail');?></th>
                <th><?php $dbF->hardWords('Phone');?></th>
                <th><?php $dbF->hardWords('Address');?></th>
                <th><?php $dbF->hardWords('Post');?></th>
                <th><?php $dbF->hardWords('City');?></th>
                <th><?php $dbF->hardWords('Country');?></th>
            </tr>
            <tr>
                <td><?php echo $data['receiver_name']; ?></td>
                <td><?php echo $data['receiver_email']; ?></td>
                <td><?php echo $data['receiver_phone']; ?></td>
                <td><?php echo $data['receiver_address']; ?></td>
                <td><?php echo $data['receiver_post']; ?></td>
                <td><?php echo $data['receiver_city']; ?></td>
                <td><?php echo $data['receiver_country']; ?></td>
            </tr>
        </table>
    </div>
<!-- receiver detail end -->


    <div class="clearfix"></div>
    <div class="padding-20"></div>

    <!-- product detail -->
    <form method="post">
    <div class="table-responsive newProduct" >
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <th colspan="9">
                <div class="text-center">ORDER PRODUCTS</div>
            </th>
            </thead>
            <tr class="gray-tr">
                <th>SNO</th>
                <th>PRODUCT NAME</th>
                <th>STORE NAME</th>
                <th>ORIGINAL PRICE</th>
                <th>SALE IN PRICE</th>
                <th>DISCOUNT</th>
                <th>SALE QTY</th>
                <th>PROCESS</th>
                <th>Total</th>
            </tr>
            <?php
            $totalDiscount = 0;
            $totalProductPrice = 0;
            $pdata = $invoice->invoiceProduct($pId);
            $totalNet=0;
            $process= "0";
            $i = 0;
            $done = true;

            foreach($pdata as $p){
                $i++;
                $pQty = $p['order_pQty'];
                $total = $p['order_salePrice']*$pQty;

                $discount=$p['order_discount'];
                $totalDiscount += $discount;

                $saleIn = (($total/$pQty)-($discount/$pQty));
                $total = $saleIn*$pQty;
                $totalNet += $total;

                $singleDiscount = $discount/$pQty;

                $process = $p['order_process'];

                if($process==0){
                    $processT = "<div class='btn btn-danger  btn-sm'>NO</div>";
                }else{
                    $processT = "<div class='btn btn-success btn-sm'>Yes</div>";
                }
            echo "
                <tr>
                    <td>";
                if($process==0){
                    echo "<input type='checkbox' name='pro[]' value='$p[invoice_product_pk]'/>";
                    $done = false;
                }
                echo "$i </td>
                    <td>$p[order_pName]</td>
                    <td>$p[order_pStore]</td>
                    <td>$p[order_pPrice]</td>
                    <td>$saleIn</td>
                    <td>$singleDiscount</td>
                    <td>$pQty</td>
                    <td>$processT</td>
                    <td>$total $data[price_code]</td>
                </tr>";
            }

            echo "
                <tr>
                    <td colspan='8'><b>Total Net Amount</b></td>
                    <td>$totalNet  $data[price_code] </td>
                </tr>";

            ?>

        </table>
    </div>
    <!-- product detail end -->

    <div class="clearfix"></div>
    <div class="padding-20"></div>


<!-- invoice detail -->

    <input type="hidden" name="pId" value="<?php echo $pId; ?>" />
    <?php $functions->setFormToken('Invoice'); ?>
    <div class="table-responsive newProduct col-sm-6" >
        <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <th colspan="6">
                <div class="text-center">Invoice Detail</div>
            </th>
            </thead>
            <tr class="gray-tr">
                <th>Property</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Invoice ID</td>
                <td><?php echo $data['invoice_id']; ?></td>
            </tr>
            <tr>
                <td>Payment Type</td>
                <td><?php  $val = $invoice->productF->paymentArrayFind($data['paymentType']); ?>
                    <?php
                    $click = '$("#payment").show(500);';

                    $processT = "<div class='btn-info' onclick='$click'> $val </div> ";
                    echo $processT;
                    if(!$done){
                    ?>
                    <select name="payment" id="payment"  style="display: none;">
                        <?php echo $invoice->productF->paymentSelect(); ?>
                    </select>
                    <script>
                        $(document).ready(function(){
                            $("#payment").val("<?php echo $data['paymentType'];?>").change();
                        });
                    </script>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Payment Info</td>
                <td><div class="col-sm-10 col-md-9">
                        <textarea name="paymentInfo" class="form-control" placeholder="Enter Vendor Payment Information"><?php echo $data['payment_info']; ?></textarea>
                    </div>
                </td>
            </tr>

            <tr>
                <td>Total Weight</td>
                <td><?php echo $data['total_weight']." KG"; ?></td>
            </tr>

            <tr>
                <td>Discount</td>
                <td><?php echo $totalDiscount." ".$data['price_code']; ?></td>
            </tr>

            <tr>
                <td>Shipping Price</td>
                <td><?php echo $data['ship_price']." ".$data['price_code']; ?></td>
            </tr>

            <tr>
                <td>Total Product Price</td>
                <td><?php echo $totalNet+$totalDiscount." ".$data['price_code']; ?></td>
            </tr>

            <tr>
                <td>Total</td>
                <td title="<?php echo $data['ship_price'].'+'.($totalNet+$totalDiscount).'-'.$totalDiscount.' = '.$data['total_price']; ?>"><?php echo $data['total_price']." ".$data['price_code']; ?> &nbsp;<i class="glyphicon glyphicon-info-sign   "></i></td>
            </tr>
            <tr>
                <td>Date Time</td>
                <td><?php echo $data['dateTime']; ?></td>
            </tr>
            <tr>
                <td>Invoice Status</td>
                <td><?php
                    $invoiceStatus = $data['invoice_status'];
                    $invs = true;
                    if($invoiceStatus==0){
                        $invStatus = "btn-danger";
                    }elseif($invoiceStatus==1){
                        $invStatus = "btn-warning";
                    }else if($invoiceStatus==2){
                        $invStatus = "btn-primary";
                    }else if($invoiceStatus==3){
                        $invStatus = "btn-success";
                        $invs = false;
                    }else{
                        $invStatus = "btn-defaults";
                    }

                    $click = '$("#invStatus").show(500);';
                    $btn = '$("#upbtn").show(500);';

                    //Done Was working if all product process then always show done order
                    //if($done){

                    if($done==='asad'){
                        $invStatus = "btn-success";
                        echo "<div class='$invStatus' onclick='$click'>Done Order Complete</div>";
                    }else{
                        echo "<div class='$invStatus' onclick='$click'>".$invoice->productF->invoiceStatusFind($invoiceStatus)."</div>";
                    }


                    if(!$done || $invoiceStatus!=3){
                        ?>
                        <select name="invoiceStatus" id="invStatus" style="display: none;">
                            <?php echo $invoice->productF->invoiceStatus(); ?>
                        </select>
                        <script>
                            $(document).ready(function(){
                                $("#invStatus").val("<?php echo $invoiceStatus;?>").change();
                            });
                        </script>
                    <?php
                    }
                    ?></td>
            </tr>


            <!--<tr>
                <td>Order process</td>
                <td><?php
/*                    $click = '$("#payment").show(500);';
                    if($process==0){
                        $processT = "<div class='btn-danger' onclick='$click'> Pending, Order Now </div> ";
                    }else{
                        $processT = "<div class='btn-success'> SuccessFully </div> ";
                    }
                    echo $processT;

                    if($process==0){    */?>
                        <select name="payment" id="payment"  style="display: none;">
                            <?php /*echo $invoice->productF->paymentSelect(); */?>
                        </select>
                        <script>
                            $(document).ready(function(){
                                $("#payment").val("<?php /*echo $process;*/?>").change();
                            });
                        </script>
                    <?php
/*                    }
                    */?>
                </td>
            </tr>-->

        </table>
    </div>

<!-- invoice detail End -->

    <div class="clearfix"></div>


    <input type="submit" id="upbtn" onclick="return formSubmit();" name="submit" value="UPDATE" class="submit btn btn-primary btn-lg">
    <div class="padding-20"></div>
</form>
<?php return ob_get_clean(); ?>