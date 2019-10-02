<?php
ob_start();
require_once("classes/invoice.php");
global $_e;
$invoice = new invoice();
$invoice->update();

@$pId = $_POST['pId'];
if (empty($pId)) {
    @$pId = $_GET['orderId']; // in case of future need just add this in url  &orderId={id}
}
$orderId = $pId;
$data = $invoice->invoiceDetail($orderId);
$country_list = $functions->countrylist();
if (isset($_GET['apiData'])) {
    echo "<pre>";
    print_r(unserialize(base64_decode($data['apiReturn'])));
    echo "</pre>";
}

if (!empty($data['apiReturn'])) {
    $viewApiReturnData = "<a class='btn btn-xs btn-info' href='-order?page=edit&orderId=$pId&apiData'>" . $_e['View Api Return Info'] . "</a>";
} else {
    $viewApiReturnData = '';
}
?>

    <h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Invoice Detail View']); ?></h4>
    <!-- sender detail -->
    <div class="table-responsive newProduct col-sm-6">
        <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
               cellspacing="0">
            <thead>
            <th colspan="7">
                <div class="text-center"><?php echo _u($_e['ORDER SENDER DETAIL']); ?></div>
            </th>
            </thead>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
                <td><?php echo $data['sender_name']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
                <td><?php echo $data['sender_address']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Post Code']); ?></td>
                <td><?php echo $data['sender_post']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['City']); ?></td>
                <td><?php echo $data['sender_city']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
                <td><?php
                    $countryName = $country_list[strtoupper($data['sender_country'])];
                    echo $countryName; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
                <td><?php echo $data['sender_email']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
                <td><?php echo $data['sender_phone']; ?></td>
            </tr>
        </table>
    </div>
    <!-- sender detail end -->

    <!-- receiver detail -->
    <div class="table-responsive newProduct col-sm-6">
        <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
               cellspacing="0">
            <thead>
            <th colspan="7">
                <div class="text-center"><?php echo _u($_e['ORDER RECEIVER DETAIL']); ?></div>
            </th>
            </thead>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Name']); ?></td>
                <td><?php echo $data['receiver_name']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Address']); ?></td>
                <td><?php echo $data['receiver_address']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Post Code']); ?></td>
                <td><?php echo $data['receiver_post']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['City']); ?></td>
                <td><?php echo $data['receiver_city']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Country']); ?></td>
                <td><?php $countryName = $country_list[strtoupper($data['receiver_country'])];
                    echo $countryName; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['E-mail']); ?></td>
                <td><?php echo $data['receiver_email']; ?></td>
            </tr>
            <tr>
                <td class="gray-tr"><?php echo _uc($_e['Phone']); ?></td>
                <td><?php echo $data['receiver_phone']; ?></td>
            </tr>
        </table>
    </div>
    <!-- receiver detail end -->


    <div class="clearfix"></div>
    <div class="padding-20"></div>

    <!-- product detail -->
    <form method="post">
        <div class="table-responsive newProduct">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="12">
                    <div class="text-center"><?php echo _u($_e['ORDER PRODUCTS']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo _u($_e['SNO']); ?></th>
                    <th><?php echo _u($_e['PRODUCT NAME']); ?></th>
                    <th><?php echo _u($_e['STORE NAME']); ?></th>
                    <th><?php echo _u($_e['LOCATION']); ?></th>
                    <th><?php echo _u($_e['ORIGINAL PRICE']); ?></th>
                    <th><?php echo _u($_e['SALE IN PRICE']); ?></th>
                    <th><?php echo _u($_e['DISCOUNT']); ?></th>
                    <th><?php echo _u($_e['SALE QTY']); ?></th>
                    <th><?php echo _u($_e['OFFER']); ?></th>
                    <th><?php echo _u($_e['PROCESS']); ?></th>
                    <th><?php echo _u($_e['RETURNS INFO']); ?></th>
                    <!--<th>RETURN</th>-->
                    <th><?php echo _u($_e['TOTAL']); ?></th>
                </tr>
                <?php
                $totalDiscount = 0;
                $totalProductPrice = 0;
                $pdata = $invoice->invoiceProduct($orderId);
                $totalNet = 0;
                $process = "0";
                $i = 0;
                $done = true;

                foreach ($pdata as $p) {
                    $i++;
                    $invoice_product_id = $p['invoice_product_pk'];
                    $pIds = explode("-", $p['order_pIds']);
                    @$pId = $pIds[0];
                    @$scaleId = $pIds[1];
                    @$colorId = $pIds[2];
                    @$storeId = $pIds[3];
                    @$customId = $pIds[4];

                    @$dealId = $p['deal']; // if not it is 0
                    @$checkout = $p['checkout']; // if not it is 0
                    @$info = unserialize($p['info']);

                    $pQty = $p['order_pQty'];
                    $total = $p['order_salePrice'] * $pQty;

                    $discount = $p['order_discount'];
                    $totalDiscount += $discount * $pQty;

                    $saleIn = ( ($total / $pQty) - ($discount) );
                    $saleIn = round($saleIn, 2);
                    $total = $saleIn * $pQty;
                    $totalNet += $total;

                    $singleDiscount = $discount;

                    $process = $p['order_process'];
                    $processTemp = "";

                    if ($process === '0') {
                        $processT = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
                        $processTemp = "<input type='checkbox' name='pro[]' class='btn-sm btn' value='$p[invoice_product_pk]'/> &nbsp; ";
                        $done = false;
                    } else {
                        $processT = "<div class='btn btn-success btn-sm'>" . _uc($_e['Yes']) . "</div>";
                    }


                    # New Returns Management Work
                    switch ($process) {
                        case '2':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Refunded']) . "</div>";
                             break;
                        case '3':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Defected']) . "</div>";
                             break;
                        case '4':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Changed Product']) . "</div>";
                             break;
                        case '5':
                            $returns_info = "<div class='btn btn-danger btn-sm'>" . _uc($_e['Changed Size']) . "</div>";
                             break;
                         
                         default:
                            $returns_info = '';
                             break;
                    } 


                    if ( $checkout === '1' )  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Checkout']) . "</div>";
                    }elseif ($checkout === '2')  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Free Gift']) . "</div>";
                    }else {
                        $checkoutD = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
                    }

                    $retrunInput = "";
                    @$returnP = @$p['order_return'];
                    $retrunStatus = "";
                    if ($returnP === '0') {
                        if ($process === '0') {
                            $retrunInput = '';
                            $retrunStatus = "";
                        } else {
                            $retrunInput = "<input type='checkbox' name='retrun[]' class='btn-sm btn' value='$p[invoice_product_pk]'/> &nbsp; ";
                            $retrunStatus = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
                        }
                    } else {
                        $retrunStatus = "<div class='btn btn-success btn-sm'>" . _uc($_e['Yes']) . "</div>";
                    }


                    $pName = $p['order_pName'];
                    //custom Info
                    $sizeInfo = '';
                    $class = '';
                    if ($customId != '0' && !empty($customId) && $scaleId == '0') {
                        $sizeInfo = "<a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                        $pName = explode(" - ", $pName);
                        $pName[1] = $sizeInfo;
                        $pName = implode(" - ", $pName);

                        $customFieldsData = $invoice->customSubmitValues($customId);
                        $customFields = $customFieldsData["form"];
                        $customFormFill = $customFieldsData["formFill"];
                        $sizeInfo = $functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
                        $processTemp = '';
                        if ($customFormFill == '1') { //edit able,, not fill
                            $class = 'danger';
                        }
                    }

                    if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                        $dealT = $_e['Deal'];
                        $sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                        $customFields = $invoice->dealSubmitPackage($info, false);
                        $sizeInfo .= $functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
                    }

                    ############## Buy 2 Get 1 Free ######
                    $buy_get_free = $invoice->productF->buy_get_free_invoice_div($orderId, $invoice_product_id, "2");
                    if (!empty($buy_get_free)) {
                        $pQty = $pQty . $buy_get_free;
                    }
                    ############## Buy 2 Get 1 Free END ######

                    ############ FREE GIFT TEXT #############
                    $free_gift_product_div = "";
                    if ($saleIn == "0" && $p["order_pPrice"] == $singleDiscount) {
                        $free_gift_product_div = $invoice->productF->free_gift_text();
                    }
                    ############ FREE GIFT TEXT #############

                    ########### Store Location ##########
                    $location = $invoice->productF->get_stock_location($pId, $storeId, $scaleId, $colorId);

                    //<td>$pName $sizeInfo $free_gift_product_div</td>
                    echo "
                    <tr class='$class'>
                        <td>$i</td>
                        <td>$pName $sizeInfo</td>
                        <td>$p[order_pStore]</td>
                        <td>$location</td>
                        <td>$p[order_pPrice]</td>
                        <td>$saleIn</td>
                        <td>$singleDiscount</td>
                        <td>$pQty</td>
                        <td>$checkoutD</td>
                        <td>$processTemp $processT</td>
                        <td>$returns_info</td>
                        <!-- <td>$retrunInput $retrunStatus</td> -->
                        <td>$total $data[price_code]</td>
                    </tr>";
                }

                echo "
                <tr>
                    <td colspan='11'><b>" . _uc($_e['Total Net Amount']) . "</b></td>
                    <td>$totalNet  $data[price_code]</td>
                </tr>";

                ?>

            </table>
        </div>
        <!-- product detail end -->

        <div class="clearfix"></div>
        <div class="padding-20"></div>


        <!-- invoice detail -->

        <input type="hidden" name="pId" value="<?php echo $orderId; ?>"/>
        <?php $functions->setFormToken('Invoice'); ?>
        <div class="table-responsive newProduct col-sm-6">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="6">
                    <div class="text-center"><?php echo _uc($_e['Invoice Detail']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo _uc($_e['Property']); ?></th>
                    <th><?php echo _uc($_e['Value']); ?></th>
                </tr>
                <tr>
                    <td><?php echo _uc($_e['Invoice ID']); ?></td>
                    <td><?php echo $data['invoice_id']; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Discount Code']); ?></td>
                    <td><?php $temp = $invoice->productF->get_order_invoice_record($orderId, "coupon", false);
                        echo @$temp['setting_val'];
                        ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Total Weight']); ?></td>
                    <td><?php echo $data['total_weight'] . " KG"; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['DISCOUNT']); ?></td>
                    <td><?php echo $totalDiscount . " " . $data['price_code']; ?></td>
                </tr>


                <?php
                $three_for_two_cat = $data['three_for_two_cat'];
                // if($three_for_two_cat>0){ ?>
                <tr class="lasts_tr">
                    <td><?php $dbF->hardWords('Three For Two Categry Price'); ?> </td>
                    <td> <?php echo $three_for_two_cat . " " . $data['price_code']; ?></td>
                </tr>
                <?php //} ?>

                <tr>
                    <td><?php echo _uc($_e['Shipping Price']); ?></td>
                    <td><?php echo $data['ship_price'] . " " . $data['price_code']; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Total Product Price']); ?></td>
                    <td><?php echo $totalNet + $totalDiscount . " " . $data['price_code']; ?></td>
                </tr>

               <!-- <?php /*if( intval($data['three_for_two_cat']) > 0 ){ */?>
                    <tr>
                        <td><?php /*echo _uc($_e['3 For 2 Category']); */?></td>
                        <td><?php /*echo @$data['three_for_two_cat']." ". $data['price_code'];  */?></td>
                    </tr>
                --><?php /*} */?>

                <tr>
                    <td><?php echo _uc($_e['Total']); ?></td>
                    <td title="<?php echo $data['ship_price'] . '+' . ($totalNet + $totalDiscount) . '-' . $totalDiscount . ' - ' . $three_for_two_cat . ' = ' . $data['total_price']; ?>"><?php echo $data['total_price'] . " " . $data['price_code']; ?>
                        &nbsp;<i class="glyphicon glyphicon-info-sign   "></i></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Creation Time']); ?></td>
                    <td><?php echo $data['invoice_date']; ?></td>
                </tr>


                <tr>
                    <td><?php echo _uc($_e['Last Updated Time']); ?></td>
                    <td><?php echo $data['dateTime']; ?></td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Invoice Status']); ?></td>
                    <td><?php
                        $invoiceStatus = $data['invoice_status'];
                        $invs = true;
                        if ($invoiceStatus == 0) {
                            $invStatus = "btn-danger";
                        } elseif ($invoiceStatus == 1) {
                            $invStatus = "btn-warning";
                        } else if ($invoiceStatus == 2) {
                            $invStatus = "btn-primary";
                        } else if ($invoiceStatus == 3) {
                            $invStatus = "btn-success";
                            $invs = false;
                        } else {
                            $invStatus = "btn-info";
                        }

                        $click = '$("#invStatus").show(500);';
                        $btn = '$("#upbtn").show(500);';

                        //Done Was working if all product process then always show done order
                        //if($done){
                        if ($done === 'asad') {
                            $invStatus = "btn-success";
                            echo "<div class='$invStatus' onclick='$click'>Done Order Complete</div>";
                        } else {
                            echo "<div class='$invStatus btn' onclick='$click'>" . $invoice->productF->invoiceStatusFind($invoiceStatus) . "</div>";
                        }


                        //if(!$done || $invoiceStatus!=3){
                        //var_dump($done);
                        // if(!$done){ ?>
                        <select name="invoiceStatus" id="invStatus" style="display: none;" class="form-control">
                            <?php echo $invoice->productF->invoiceStatus(); ?>
                        </select>
                        <script>
                            $(document).ready(function () {
                                $("#invStatus").val("<?php echo $invoiceStatus;?>").change();
                            });
                        </script>
                        <?php //} ?></td>
                </tr>


                <tr>
                    <td><?php echo _uc($_e['Shipping Track Number']); ?></td>
                    <td><input type="text" class="form-control" value="<?php echo $data['trackNo']; ?>" name="trackNo"/>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Send Email To Customer']); ?></td>
                    <td>
                        <input type="hidden" value="<?php echo $data['sender_email'] ?>" name="toEmail"/>
                        <label><input type="radio" value="1" name="sendEmail" checked/><?php echo _u($_e['Yes']); ?>
                        </label>
                        <label><input type="radio" value="0" name="sendEmail"/><?php echo _u($_e['NO']); ?></label>
                    </td>
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

                                    if($process==0){    */ ?>
                        <select name="payment" id="payment"  style="display: none;">
                            <?php /*echo $invoice->productF->paymentSelect(); */ ?>
                        </select>
                        <script>
                            $(document).ready(function(){
                                $("#payment").val("<?php /*echo $process;*/ ?>").change();
                            });
                        </script>
                    <?php
                /*                    }
                                    */ ?>
                </td>
            </tr>-->

            </table>
        </div>


        <div class="table-responsive newProduct col-sm-6">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="6">
                    <div class="text-center"><?php echo _uc($_e['Payment Information']); ?></div>
                </th>
                </thead>
                <tr class="gray-tr">
                    <th><?php echo _uc($_e['Property']); ?></th>
                    <th><?php echo _uc($_e['Value']); ?></th>
                </tr>
                <tr>
                    <td><?php echo _uc($_e['Payment Type']); ?></td>
                    <td><?php $val = $invoice->productF->paymentArrayFind($data['paymentType']);

                        $click = '$("#payment").show(500);';

                        if ($data['paymentType'] == '2') {
                            $processT = "<div class='btn-success btn btn-sm' onclick='$click'> $val </div> ";
                        } else if ($data['paymentType'] == '0') {
                            $processT = "<div class='btn-danger btn btn-sm' onclick='$click'> $val </div> ";
                        } else {
                            $processT = "<div class='btn-default btn btn-sm' onclick='$click'> $val </div> ";
                        }
                        echo $processT;
                        if (!$done) {
                            ?>
                            <select name="payment" id="payment" style="display: none;" class="form-control">
                                <?php echo $invoice->productF->paymentSelect(); ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                    $("#payment").val("<?php echo $data['paymentType'];?>").change();
                                });
                            </script>
                            <?php
                        } else {
                            echo "<input type='hidden' value='$data[paymentType]' name='payment'/>";
                        }
                        echo $viewApiReturnData;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Payment Status']); ?></td>
                    <td>
                        <?php $paymentStatus = $data['orderStatus'];
                        if ($paymentStatus == 'process') {
                            $paymentStatus = _uc($_e['OK']);
                        } else {
                            $paymentStatus = _uc($_e['InComplete']);
                        }

                        echo $paymentStatus;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Reservation Number']); ?></td>
                    <td>
                        <?php echo $data['rsvNo'];
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo _uc($_e['Payment Info']); ?></td>
                    <td>
                        <div class="col-sm-10 col-md-9">
                            <textarea name="paymentInfo" class="form-control"
                                      placeholder="<?php echo _uc($_e['Enter Vendor Payment Information']); ?>"
                                      style="width: 320px;height: 268px;"><?php echo $data['payment_info']; ?></textarea>
                        </div>
                    </td>
                </tr>

            </table>
        </div>
        <!-- invoice detail End -->

        <div class="clearfix"></div>

        <br>

        <div class="table-responsive newProduct col-sm-10">
            <?php echo _uc($_e['INTERNAL COMMENT']); ?> :
            <textarea class="form-control" style="height: 100px"
                      name="comment"><?php echo $data['comment'] ?></textarea>
        </div>

        <div class="clearfix"></div>
        <br>

        <a href="<?php echo WEB_URL; ?>/invoicePrint?mailId=<?php echo $orderId; ?>" target="_blank"
           class="btn btn-info btn-lg"><?php echo _uc($_e['Print Out']); ?></a>
        <input type="submit" id="upbtn" onclick="return formSubmit();" name="submit" value="UPDATE"
               class="submit btn btn-primary btn-lg">

        <div class="padding-20"></div>

    </form>
<?php return ob_get_clean(); ?>