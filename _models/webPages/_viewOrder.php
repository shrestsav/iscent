<?php

class viewOrder extends object_class
{
    public $productClass;
    public $webClass;

    public function __construct()
    {
        parent::__construct('3');
        $this->productClass =  $GLOBALS['productClass'];
        $this->webClass     = $GLOBALS['webClass'];

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        $_w=array();
        $_w['Thank you for submit your info'] = '';
        $_w['Save'] = '';
        $_w['Your info save successfully'] = '';
        $_e    =   $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'Web ViewOrder');
    }


    public function editCustomOrder($orderId){
        global $_e;

        $id     = $this->functions->decode($orderId);
        $sql        = "SELECT * FROM p_custom_submit WHERE id = '$id' AND submitLater = '1'";
        $data       = $this->dbF->getRow($sql);

        if(empty($data)){
            return false;
        }
        $token   =  $this->functions->setFormToken('editCustomOrder', false);
        $token  .= '<input type="hidden" name="editId" value="'.$orderId.'"/>';


        $sql          = "SELECT * FROM p_custom_setting WHERE c_id = '$data[custom_id]'";
        $dataFields   = $this->dbF->getRows($sql);

        $sql          = "SELECT * FROM p_custom_submit_setting WHERE orderId = '$id'";
        $dataFieldsSubmit   = $this->dbF->getRows($sql);

        foreach($dataFieldsSubmit as $key=>$val) {
            $fields['fields'][] = $val['setting_name'];
            $fields[$val['setting_name']] = $val['setting_value'];
        }

        $form_fields = array();
        $form_fields[] = array(
            'type'      => 'none',
            'thisFormat'=> "$token"
        );

        foreach($fields['fields'] as $key=>$val) {
            $required = translateFromSerialize($this->productClass->measurementArray($dataFields,$val,'required'));
            $valTemp  = translateFromSerialize($this->productClass->measurementArray($dataFields,$val,'name'));
            $valId    = translateFromSerialize($this->productClass->measurementArray($dataFields,$val,'desc',true));
            $form_fields[] = array(
                'label'     => $valTemp." <i class='cursor grow glyphicon glyphicon-info-sign' onclick='loadCustomFormInfo($valId);'></i>",
                'name'      => "custom[$val]",
                'type'      => 'text',
                'class'     => 'form-control',
                'value'     =>  $fields[$val],
                'required'  =>  "$required"
            );
        }

        $valId    = translateFromSerialize($this->productClass->measurementArray($dataFields,$fields['fields'][0],'desc',true));
        $form_fields[] = array(
            'type'      => 'none',
            'thisFormat'=> "<script>
                                $(document).ready(function(){
                                    loadCustomFormInfo($valId);
                                });
                            </script>"
        );

        //Submit Button
        $form_fields[] = array(
            'name'      => "submit",
            'type'      => 'button',
            'class'     => 'btn btn-lg themeButton',
            'value'     => 'submit',
            'option'     => "<i class='glyphicon glyphicon-send '></i> ".$_e['Submit'],
            'thisFormat'=> "",
            'inFormat'  => 'submit',
            'submit'    => 'true'
        );

        //Save Button
        $form_fields[] = array(
            'name'      => "submit",
            'type'      => 'button',
            'class'     => 'btn btn-lg themeButton',
            'value'     => 'save',
            'option'     => "<i class='glyphicon glyphicon-floppy-saved'></i> ".$_e['Save'],
            'thisFormat'=> "",
            'inFormat'  => 'save',
            'submit'    => 'true'
        );

        $form_fields[] = array(
            'type'      => "none",
            'thisFormat' => '<div class="text-center">{{save}} {{submit}}</div>',
        );

        //Make <form, call first or any where then make array index key is 'form',
        //now mange more clear, just make format here... no thisFormat work here.
        $form_fields['form']  = array(
            'name'      => "form",
            'type'      => 'form',
            'class'     => "formClass",
            'id'        => "customForm_$orderId",
            'data'      => 'onsubmit="return customFormSubmit(this,'.$orderId.')"',
            'action'    => "viewOrder?submit",
            'method'    => 'post',
            'format'    => '<div class="form-horizontal col-sm-4 padding-F5">{{form}}</div>
                             <div class="form-horizontal col-sm-8 padding-F5 loadCustomFormInfo" ></div>'
        );
        $format     = '<div class="form-group">
                            <label class="col-sm-7 control-label" style="padding-left:5px;padding-right:5px;">{{label}}</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                {{form}}
                                <div class="input-group-addon">CM</div>
                                </div>
                            </div>
                        </div>';

        $form = $this->functions->print_form($form_fields,$format,false);
        return $form;

    }

    public function customFormSubmit(){
        if(isset($_POST['submit'])) {
            if (!$this->functions->getFormToken("editCustomOrder")) return false;

            $submitVal = $_POST['submit'];

            global $_e;
            @$id     = $this->functions->decode($_POST['editId']);
            if($id > 0){
                if($submitVal == 'submit') {
                    $sql = "UPDATE p_custom_submit SET submitLater = '0' WHERE id = '$id'";
                    $cartData = $this->dbF->setRow($sql);
                }
                    $sql = "DELETE FROM p_custom_submit_setting WHERE orderId = '$id'";
                    $this->dbF->setRow($sql);
            }
            else{
                return false;
            }

            $sqlArray = array();
            $isFields = false;
            $sql = "INSERT INTO p_custom_submit_setting(orderId,setting_name,setting_value) VALUES ";

            foreach ($_POST['custom'] as $key2 => $val2) {
                $isFields   = true;
                $sql        .= "(?,?,?),";
                $sqlArray[] = $id;
                $sqlArray[] = $key2;
                $sqlArray[] = $val2;
            }

            $sql = trim($sql, ",");
            if ($isFields) {
                $this->dbF->setRow($sql, $sqlArray);
                if($submitVal == 'submit') {

                //send email
                    //get order and user info
                    $orderData = $this->productClass->orderDataByCustomSizeId($id);
                    $orderIdInvoice = $orderData['invoice_id'];

                    $link = WEB_URL."/src/pdf/measurementPDF.php?id=$id&orderId=".$this->functions->encode($id);
                    $mailArray['link'] = $link;
                    $mailArray['invoiceNumber'] = $orderIdInvoice;
                    //send email to client
                    $this->functions->send_mail($orderData['sender_email'], $orderIdInvoice, '', 'measurementSubmitClient', $orderData['sender_name'], $mailArray);

                    ///send email to admin
                    $adminMail = $this->functions->ibms_setting('Email');
                    $this->functions->send_mail($adminMail, $orderIdInvoice, '', 'measurementSubmitClient', '', $mailArray);
                //send email End

                    return $_e["Thank you for submit your info"];
                }
                return $_e["Your info save successfully"];
            }else{
                return "";
            }

        }
    }

    public function getCustomSingleFieldInfo($id){
        $sql       = "SELECT * FROM p_custom_setting WHERE id = '$id'";
        $data      = $this->dbF->getRow($sql);

        $desc      = $data['setting_value'];
        $desc      = translateFromSerialize($desc);
        return $desc;

    }


    public function viewOrder($orderId)
    {
        global $_e;
        $login          =  $this->webClass->userLoginCheck();
        $loginForOrder  = $this->functions->developer_setting('loginForOrder');
        $userId = $this->webClass->webUserId();
        if($userId=='0'){
            $userId = webTempUserId(); // for all orders on temp user..
        }

            $this->functions->getAdminFile("order/classes/invoice.php");
            $invoice = new invoice();
            $pId = $orderId;
            $id  = intval($pId);

            if (isset($_GET['orderId'])) {
                $sId = $_GET['orderId'];
                $sId = $this->functions->decode($sId);
                if ($id == $sId) {
                    $id = $sId;
                } else {
                    $id = '0';
                }
            } else {
                $id = '0';
            }

            $checkUserId = '';
            if ($loginForOrder == '1') {
                $checkUserId = " AND order_invoice.orderuser = '$userId'";
            }

            $sql = "SELECT * FROM order_invoice join order_invoice_info
                              on order_invoice.order_invoice_pk= order_invoice_info.order_invoice_id
                              WHERE order_invoice.order_invoice_pk='$id' $checkUserId";
            $data = $this->dbF->getRow($sql);
            if ($this->dbF->rowCount > 0) {
                ?>
                <!-- sender detail -->


                <div class="table-responsive newProduct">
                    <table id="newProduct" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                           cellspacing="0">
                        <thead>
                        <th colspan="7">
                            <div class="text-center"><?php $this->dbF->hardWords('ORDER BILLING DETAIL'); ?></div>
                        </th>
                        </thead>
                        <tr class="gray-tr">
                            <th><?php $this->dbF->hardWords('Name'); ?></th>
                            <th><?php $this->dbF->hardWords('E-mail'); ?></th>
                            <th><?php $this->dbF->hardWords('Phone'); ?></th>
                            <th><?php $this->dbF->hardWords('Address'); ?></th>
                            <th><?php $this->dbF->hardWords('Post'); ?></th>
                            <th><?php $this->dbF->hardWords('City'); ?></th>
                            <th><?php $this->dbF->hardWords('Country'); ?></th>
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
                <div class="table-responsive newProduct">
                    <table id="receiverInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                           cellspacing="0">
                        <thead>
                        <th colspan="7">
                            <div class="text-center"><?php $this->dbF->hardWords('ORDER SHIPPING DETAIL'); ?></div>
                        </th>
                        </thead>
                        <tr class="gray-tr">
                            <th><?php $this->dbF->hardWords('Name'); ?></th>
                            <th><?php $this->dbF->hardWords('E-mail'); ?></th>
                            <th><?php $this->dbF->hardWords('Phone'); ?></th>
                            <th><?php $this->dbF->hardWords('Address'); ?></th>
                            <th><?php $this->dbF->hardWords('Post'); ?></th>
                            <th><?php $this->dbF->hardWords('City'); ?></th>
                            <th><?php $this->dbF->hardWords('Country'); ?></th>
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
            <div class="table-responsive newProduct">
                <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                       cellspacing="0">
                    <thead>
                    <th colspan="9">
                        <div class="text-center"><?php $this->dbF->hardWords('ORDER PRODUCTS'); ?></div>
                    </th>
                    </thead>
                    <tr class="gray-tr">
                        <th><?php $this->dbF->hardWords('Sno'); ?></th>
                        <th><?php $this->dbF->hardWords('Product Name'); ?></th>
                        <th><?php $this->dbF->hardWords('Shop'); ?></th>
                        <th><?php $this->dbF->hardWords('Order Price'); ?></th>
                        <th><?php $this->dbF->hardWords('Sale Price'); ?></th>
                        <th><?php $this->dbF->hardWords('Discount'); ?></th>
                        <th><?php $this->dbF->hardWords('Quantity'); ?></th>
                        <th><?php $this->dbF->hardWords('Process'); ?></th>
                        <th><?php $this->dbF->hardWords('Total'); ?></th>
                    </tr>
                    <?php
                    $totalDiscount = 0;
                    $totalProductPrice = 0;
                    $pdata = $invoice->invoiceProduct($pId);
                    $totalNet = 0;
                    $process = "0";
                    $i = 0;
                    $done = true;

                    foreach ($pdata as $p) {
                        $i++;
                        $pQty = $p['order_pQty'];
                        $total = $p['order_salePrice'] * $pQty;

                        $pIds = explode("-", $p['order_pIds']);
                        @$pId = $pIds[0];
                        @$scaleId = $pIds[1];
                        @$colorId = $pIds[3];
                        @$customId = $pIds[4];
                        @$dealId = $p['deal']; // if not it is 0
                        @$info = unserialize($p['info']);

                        $discount = $p['order_discount'];
                        $totalDiscount += $discount * $pQty;

                        $salePrice = $p['order_salePrice'];
                        $saleIn = $salePrice - $discount;
                        $total = $saleIn * $pQty;
                        $totalNet += $total;

                        //$singleDiscount = $discount/$pQty;
                        $singleDiscount = $discount;
                        $process = $p['order_process'];

                        if ($process == 0) {
                            $processT = "<div class='btn btn-danger  btn-sm'>" . $this->dbF->hardWords('No', false) . "</div>";
                        } else {
                            $processT = "<div class='btn btn-success btn-sm'>" . $this->dbF->hardWords('Yes', false) . "</div>";
                        }

                        $pName = $p['order_pName'];
                        //custom Info
                        $sizeInfo = '';
                        $class= '';
                        if ($customId != '0' && !empty($customId) && $scaleId == '0') {
                            $sizeInfo = "<a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a>";
                            $pName = explode(" - ", $pName);
                            $pName[1] = $sizeInfo;
                            $pName = implode(" - ", $pName);

                            $customFieldsData   = $this->productClass->customSubmitValues($customId, true);
                            $customFields       = $customFieldsData["form"];
                            $customFormFill     = $customFieldsData["formFill"];
                            $sizeInfo = $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);

                            if($customFormFill=='1'){ //edit able,, not fill
                                $class = 'danger';
                            }
                        }
                        if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
                            $dealT = $this->dbF->hardWords('Deal', false);
                            $sizeInfo = "<div><a href='#$customId' data-toggle='modal' data-target='#dealInfo_$customId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
                            $customFields = $this->productClass->dealSubmitPackage($info, false);
                            $sizeInfo .= $this->functions->blankModal($_e['Custom'], "dealInfo_$customId", $customFields, $_e['Close']);
                        }

                        echo "
                   <tr class='$class'>
                        <td>";
                        if ($process == 0) {
                            echo "";
                            $done = false;
                        }
                        echo "$i </td>
                                <td>$pName $sizeInfo</td>
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
                                <td colspan='8'><b>" . $this->dbF->hardWords('Grand Total', false) . "</b></td>
                                <td>$totalNet  $data[price_code] </td>
                            </tr>";

                    ?>

                </table>
            </div>
            <!-- product detail end -->

            <div class="clearfix"></div>
            <div class="padding-20"></div>


            <!-- invoice detail -->

            <input type="hidden" name="pId" value="<?php echo $pId; ?>"/>
            <?php $this->functions->setFormToken('Invoice'); ?>
            <div class="table-responsive newProduct col-sm-8">
                <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                       cellspacing="0">
                    <thead>
                    <th colspan="6">
                        <div class="text-center"><?php $this->dbF->hardWords('Invoice Detail'); ?></div>
                    </th>
                    </thead>
                    <tr class="gray-tr">
                        <th><?php $this->dbF->hardWords('Property'); ?></th>
                        <th><?php $this->dbF->hardWords('Value'); ?></th>
                    </tr>
                    <tr>
                        <td><?php $this->dbF->hardWords('Invoice ID'); ?></td>
                        <td><?php echo $data['invoice_id']; ?></td>
                    </tr>
                    <tr>
                        <td><?php $this->dbF->hardWords('PAYMENT TYPE'); ?></td>
                        <td><?php $val = $invoice->productF->paymentArrayFind($data['paymentType']); ?>
                            <?php
                            $click = '$("#payment").show(500);';

                            $processT = "<div class='btn-info btn-sm btn' onclick='$click'> $val </div> ";
                            echo $processT;

                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Reservation'); ?></td>
                        <td><?php echo $data['rsvNo']; ?></td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Payment info'); ?></td>
                        <td>
                            <div class="col-sm-10 col-md-9">
                                <textarea readonly name="paymentInfo" style="width: 350px;height: 150px;"
                                          class="form-control"
                                          placeholder="---"><?php echo $data['payment_info']; ?></textarea>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Total Weight'); ?></td>
                        <td><?php echo $data['total_weight'] . " KG"; ?></td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Discount'); ?></td>
                        <td><?php echo $totalDiscount . " " . $data['price_code']; ?></td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Shipping Price'); ?></td>
                        <td><?php echo $data['ship_price'] . " " . $data['price_code']; ?></td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Total Product Price'); ?></td>
                        <td><?php echo $totalNet + $totalDiscount . " " . $data['price_code']; ?></td>
                    </tr>

                    <tr>
                        <td><?php $this->dbF->hardWords('Total'); ?></td>
                        <td title="<?php echo $data['ship_price'] . '+' . ($totalNet + $totalDiscount) . '-' . $totalDiscount . ' = ' . $data['total_price']; ?>"><?php echo $data['total_price'] . " " . $data['price_code']; ?>
                            &nbsp;<i class="glyphicon glyphicon-info-sign   "></i></td>
                    </tr>
                    <tr>
                        <td><?php $this->dbF->hardWords('Date Time'); ?></td>
                        <td><?php echo $data['dateTime']; ?></td>
                    </tr>
                    <tr>
                        <td><?php $this->dbF->hardWords('Invoice Status'); ?></td>
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
                                $invStatus = "btn-defaults";
                            }

                            $click = '$("#invStatus").show(500);';
                            $btn = '$("#upbtn").show(500);';
                            //if($done){
                            //Change to stop to go in condition
                            if ($done === 'asad') {
                                $invStatus = "btn-success";
                                echo "<div class='$invStatus btn-sm btn' onclick='$click'>" . $this->dbF->hardWords('Done Order Complete', false) . "</div>";
                            } else {
                                echo "<div class='$invStatus btn-sm btn' onclick='$click'>" . $invoice->productF->invoiceStatusFind($invoiceStatus) . "</div>";
                            }
                            ?></td>
                    </tr>

                    <tr>
                        <td><?php echo _uc($_e['Shipping Track Number']); ?></td>
                        <td><?php echo $data['trackNo']; ?></td>
                    </tr>

                </table>
            </div>

            <!-- invoice detail End -->
            <?php
            } else {
                echo "<div class='alert alert-danger text-center'>" . $this->dbF->hardWords('No Invoice Found', false) . "</div>";

            }
    }
}