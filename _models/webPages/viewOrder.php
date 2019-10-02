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

        $count = 1;
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
                'required'  =>  "$required",
                'id'  =>  'order_invoice_' . $count
            );
            $count++;
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
        $form_fields[]  = array(
            'name'      => "submit",
            'type'      => 'button',
            'class'     => 'btn btn-lg themeButton',
            'value'     => 'submit',
            'option'    => "<i class='glyphicon glyphicon-send '></i> ".$_e['Submit'],
            'thisFormat'=> "",
            'inFormat'  => 'submit',
            'id'        => 'submit_vieworder',
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
            'thisFormat' => '<div class="text-center"> {{save}} {{submit}}</div>',
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


            <div class="col-sm-6 table-responsive newProduct">
                <div id="newProduct" class="table tableIBMS table-hover">

                    <div class="t_head col-sm-12 col-xs-12 text-center"><?php $this->dbF->hardWords('ORDER BILLING DETAIL'); ?></div>




                    <div class="container-fluid padding-0 main_gray_tr">

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Name'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_name']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('E-mail'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_email']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Phone'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_phone']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Address'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_address']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Post'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_post']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('City'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_city']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Country'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['sender_country']; ?></div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- sender detail end -->

            <!-- receiver detail -->
            <div class="col-sm-6 table-responsive newProduct">
                <div id="receiverInfo" class="table tableIBMS table-hover">


                    <div class="t_head col-sm-12 col-xs-12 text-center"><?php $this->dbF->hardWords('ORDER SHIPPING DETAIL'); ?></div>

                    <div class="container-fluid padding-0 main_gray_tr">

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Name'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_name']; ?></div>
                        </div>

                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('E-mail'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_email']; ?></div>
                        </div>


                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Phone'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_phone']; ?></div>
                        </div>


                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Address'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_address']; ?></div>
                        </div>


                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Post'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_post']; ?></div>
                        </div>


                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('City'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_city']; ?></div>
                        </div>


                        <div class="col-sm-12 padding-0 gray-tr">
                            <div class="col-sm-4 margin-right t_desc col_black"><?php $this->dbF->hardWords('Country'); ?></div>
                            <div class="col-sm-7 t_desc"><?php echo $data['receiver_country']; ?></div>
                        </div>

                    </div>







                </div>
            </div>
            <!-- receiver detail end -->


            <!-- product detail -->
            <form method="post">
        <div class="table-responsive newProduct">
            <table id="productInfo" class="table tableIBMS table-hover" width="100%" border="0" cellpadding="0"
                   cellspacing="0">
                <thead>
                <th colspan="10">
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
                    <th><?php $this->dbF->hardWords('Offer'); ?></th>
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
                    $invoice_product_id = $p['invoice_product_pk'];
                    $pQty = $p['order_pQty'];
                    $total = $p['order_salePrice'] * $pQty;

                    $pIds = explode("-", $p['order_pIds']);
                    @$pId = $pIds[0];
                    @$scaleId = $pIds[1];
                    @$colorId = $pIds[3];
                    @$customId = $pIds[4];
                    @$dealId = $p['deal']; // if not it is 0
                    @$checkout = $p['checkout']; // if not it is 0
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

                    if ( $checkout === '1' )  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Checkout']) . "</div>";
                    }elseif ($checkout === '2')  {
                        $checkoutD = "<div class='btn btn-success btn-sm'>" . _uc($_e['Free Gift']) . "</div>";
                    }else {
                        $checkoutD = "<div class='btn btn-danger  btn-sm'>" . _uc($_e['NO']) . "</div>";
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

                    ############## Buy 2 Get 1 Free ######
                    $buy_get_free = $this->productClass->productF->buy_get_free_invoice_div($orderId,$invoice_product_id,"2");
                    if(!empty($buy_get_free)){
                        $pQty = $pQty.$buy_get_free;
                    }
                    ############## Buy 2 Get 1 Free END ######


                    ############ FREE GIFT TEXT #############
                    $free_gift_product_div = "";
                    if($saleIn == "0" && $p["order_pPrice"] == $singleDiscount) {
                        $free_gift_product_div = $this->productClass->productF->free_gift_text();
                    }
                    ############ FREE GIFT TEXT #############


                    echo "$i </td>
                                <td>$pName $sizeInfo </td>
                                <td>$p[order_pStore]</td>
                                <td>$p[order_pPrice]</td>
                                <td>$saleIn</td>
                                <td>$singleDiscount</td>
                                <td>$pQty</td>
                                <td>$checkoutD</td>
                                <td>$processT</td>
                                <td>$total $data[price_code]</td>
                            </tr>";
                }

                echo "
                            <tr>
                                <td colspan='9'><b>" . $this->dbF->hardWords('Grand Total', false) . "</b></td>
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
        <div class="table-responsive newProduct  col-md-6">
            <div id="productInfo" class="table tableIBMS table-hover container-fluid">

                <div class="text-center t_head col-sm-12 col-xs-12"><?php $this->dbF->hardWords('Invoice Detail'); ?></div>

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_head d_t_c col-xs-6"><?php $this->dbF->hardWords('Property'); ?></div>
                    <div class="col-sm-8 t_head d_t_c col-xs-6"><?php $this->dbF->hardWords('Value'); ?></div>
                </div>


                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black d_t_c col-xs-6"><?php $this->dbF->hardWords('Invoice ID'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php echo $data['invoice_id']; ?></div>
                </div>


                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black d_t_c col-xs-6"><?php $this->dbF->hardWords('PAYMENT TYPE'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php $val = $invoice->productF->paymentArrayFind($data['paymentType']); ?>
                        <?php
                        $click = '$("#payment").show(500);';

                        $processT = "<div class='btn-info btn-sm btn' onclick='$click'> $val </div> ";
                        echo $processT;

                        ?>
                    </div>
                </div>

                <!-- <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php //$this->dbF->hardWords('Reservation'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php //echo $data['rsvNo']; ?></div>
                </div> -->

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php $this->dbF->hardWords('Payment info'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6">
                        <div class="col-sm-12 col-xs-12 padding-0">
                            <textarea readonly name="paymentInfo" style="resize:none; min-height:150px" class="form-control"
                                      placeholder="---"><?php echo $data['payment_info']; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php //$this->dbF->hardWords('Total Weight'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php //echo $data['total_weight'] . " KG"; ?></div>
                </div> -->

                <!-- <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php //$this->dbF->hardWords('Discount Code'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"> -->
                        <?php
                            // $temp = $invoice->productF->get_order_invoice_record($orderId, "coupon", false);
                            // echo @$temp['setting_val'];
                        ?>
                            
                        <!-- </div>
                </div> -->

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php $this->dbF->hardWords('Discount'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php echo $totalDiscount . " " . $data['price_code']; ?></div>
                </div>

                <?php
                $three_for_two_cat     =   $data['three_for_two_cat']; ?>
                <!-- <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php //$this->dbF->hardWords('Three For Two Categry Price'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php //echo $three_for_two_cat . " " . $data['price_code']; ?></div>
                </div> -->

                <!-- <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php //$this->dbF->hardWords('Shipping Price'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php //echo $data['ship_price'] . " " . $data['price_code']; ?></div>
                </div> -->

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php $this->dbF->hardWords('Total Product Price'); ?></div>
                    <div class="col-sm-8 t_desc col-xs-6 d_t_c"><?php echo $totalNet + $totalDiscount . " " . $data['price_code']; ?></div>
                </div>

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php $this->dbF->hardWords('Total'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6" title="<?php echo $data['ship_price'] . '+' . ($totalNet + $totalDiscount) . '-' . $totalDiscount . ' = ' . $data['total_price']; ?>"><?php echo $data['total_price'] . " " . $data['price_code']; ?>
                        &nbsp;<i class="glyphicon glyphicon-info-sign   "></i></div>
                </div>

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php $this->dbF->hardWords('Date Time'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php echo $data['dateTime']; ?></div>
                </div>

                <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php $this->dbF->hardWords('Invoice Status'); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php
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
                        ?></div>
                </div>

                <!-- <div class="col-sm-12 padding-0 gray-tr d_t col-xs-12">
                    <div class="col-sm-3 margin-right t_desc col_black col-xs-6 d_t_c"><?php //echo _uc($_e['Shipping Track Number']); ?></div>
                    <div class="col-sm-8 t_desc d_t_c col-xs-6"><?php //echo $data['trackNo']; ?></div>
                </div> -->

            </div>
        </div>

        <!-- invoice detail End -->
        <?php
        } else {
            echo "<div class='alert alert-danger text-center'>" . $this->dbF->hardWords('No Invoice Found', false) . "</div>";

        }
    }
}