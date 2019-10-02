<?php
class order extends object_class{
    public $productF;

    public function __construct(){
        parent::__construct('3');

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../../product_management/functions/product_function.php");
            $this->productF=new product_function();
        }

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //newOrder.php
        $_w['Add New Quote'] = '' ;
        $_w['Add New Quotes'] = '' ;
        $_w['InComplete Orders'] = '' ;
        $_w['All Orders'] = '' ;
        $_w['All Quotes'] = '' ;
        $_w['Complete Orders'] = '' ;
        $_w['Cancel Orders'] = '' ;
        $_w['InProcess Invoices'] = '' ;
        $_w['Quote Create/View'] = '' ;

        //New order form Function
        $_w['Store Country'] = '' ;
        $_w['Select Country'] = '' ;
        $_w['User'] = '' ;
        $_w['Select User'] = '' ;
        $_w['No User'] = '' ;
        $_w['Invoice Status'] = '' ;
        $_w['Payment Type'] = '' ;
        $_w['Payment Info'] = '' ;
        $_w['Enter Vendor Payment Information'] = '' ;
        $_w['PRODUCT SCALE'] = '' ;
        $_w['PRODUCT COLOR'] = '' ;
        $_w['STORE'] = '' ;
        $_w['QUANTITY'] = '' ;
        $_w['PRICE'] = '' ;
        $_w['Select Product Name'] = '' ;
        $_w['Select Scale'] = '' ;
        $_w['Select Color'] = '' ;
        $_w['Select Store'] = '' ;
        $_w['Product QTY'] = '' ;
        $_w['Single Price'] = '' ;
        $_w['Product Discount'] = '' ;
        $_w['Add Product'] = '' ;
        $_w['Remove Checked Items'] = '' ;
        $_w['Check/Uncheck All'] = '' ;
        $_w['NO'] = '' ;
        $_w['PRODUCT'] = '' ;
        $_w['WEIGHT'] = '' ;
        $_w['QTY'] = '' ;
        $_w['(QTY*PRICE) - DISCOUNT = TOTAL PRICE'] = '' ;
        $_w['(QTY*PRICE) = TOTAL PRICE'] = '' ;
        $_w['DISCOUNT'] = '' ;
        $_w['TOTAL WEIGHT'] = '' ;
        $_w['TOTAL PRICE'] = '' ;
        $_w['Sender And Receiver Information'] = '' ;
        $_w['I am sender And Receiver'] = '' ;
        $_w['I am Sender And Friend Is receiver'] = '' ;
        $_w['Sender Information'] = '' ;
        $_w['Sender Name'] = '' ;
        $_w['Sender Phone'] = '' ;
        $_w['Sender Email'] = '' ;
        $_w['Sender City'] = '' ;
        $_w['Sender Country'] = '' ;
        $_w['Country'] = '' ;
        $_w['Sender Post Code'] = '' ;
        $_w['Sender Address'] = '' ;
        $_w['Receiver Information'] = '' ;
        $_w['Receiver Name'] = '' ;
        $_w['Receiver Phone'] = '' ;
        $_w['Receiver Email'] = '' ;
        $_w['Receiver City'] = '' ;
        $_w['Receiver Country'] = '' ;
        $_w['Receiver Post Code'] = '' ;
        $_w['Receiver Address'] = '' ;
        $_w['Last Order View'] = '' ;
        $_w['ORDER'] = '' ;
        $_w['Order  Price'] = '' ;
        $_w['Shipping Price'] = '' ;
        $_w['Total'] = '' ;
        $_w['ORDER AND PROCESS'] = '' ;
        $_w['Selected Products'] = '' ;

        //Add new order function
        $_w['Order QTY is Greater Than stock Quantity'] = '' ;
        $_w['Shipping Error'] = '' ;
        $_w['Some thing went wrong Please try again'] = '' ;
        $_w['Product Submit Fail'] = '' ;
        $_w['Product Submit'] = '' ;
        $_w['Product Submit Failed'] = '' ;
        $_w['New Order Added Successfully'] = '' ;
        $_w['New Order'] = '' ;
        $_w['Product Successfully Submit'] = '' ;
        $_w['Thank you your product is successfully submit'] = '' ;

        //Order view function
        $_w['SNO'] = '' ;
        $_w['INVOICE'] = '' ;
        $_w['CUSTOMER NAME'] = '' ;
        $_w['INVOICE DATE'] = '' ;
        $_w['SOLD PRICE'] = '' ;
        $_w['PAYMENT METHOD'] = '' ;
        $_w['ORDER PROCESS'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Yes'] = '' ;
        $_w['PURCHASE PRICE'] = '' ;
        $_w['VIEW ORDER'] = '' ;
        $_w['Delete All Old Incomplete Orders'] = '' ;
        $_w['Search By Date Range'] = '' ;
        $_w['Date To'] = '' ;
        $_w['Date From'] = '' ;
        $_w['Selected SubTotal'] = '' ;
        $_w['Quote Information'] = '' ;
        $_w['Terms & Conditions'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Order');

    }


    public function deleteOrders($type){
        $days    = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
        $minusDays  =   date('Y-m-d',strtotime("-$days days"));

        try {
            $this->db->beginTransaction();

            $sql = "SELECT order_pIds FROM `order_invoice_product` WHERE  dateTime <= '$minusDays' AND orderStatus = 'inComplete'";
            $oldData = $this->dbF->getRows($sql);
            foreach ($oldData as $val) {
                $orderId = $val['order_invoice_id'];
                $pIds = $val['order_pIds'];
                $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                $pId = $pArray[0]; // 491
                $scaleId = $pArray[1]; // 426
                $colorId = $pArray[2]; // 435
                $storeId = $pArray[3]; // 5
                @$customId = $pArray[4]; // 5

                //delete custom if has
                if ($customId != '0' && !empty($customId)) {
                    $sql = "DELETE FROM p_custom_submit WHERE id = '$customId'";
                    $this->dbF->setRow($sql);
                }

                $sql = "DELETE FROM order_invoice WHERE order_invoice_pk = '$orderId' '";
                $this->dbF->setRow($sql);
            }
            $this->db->commit();
        }catch (Exception $e){
            $this->db->rollBack();
        }
        $this->deleteCartOld();
    }

    public function deleteCartOld(){
        //delete Old Cart and custom From table...
        $date = date('Y-m-d',strtotime("-30 days"));
        $sql = "DELETE FROM p_custom_submit WHERE dateTime <= '$date' AND id in (SELECT customId FROM cart WHERE dateTime <= '$date')";
        $this->dbF->setRow($sql);

        $sql = "DELETE FROM cart WHERE dateTime <= '$date'";
        $this->dbF->setRow($sql);
    }



    /**
     *  Simple Form For New Order
     */
    public function newOrderForm(){
        global $_e;
        $this->functions->require_once_custom('store');
        $storeC   = new store();


        $paymentSelectOption = $this->productF->paymentSelect();
        $invoiceStatus = $this->productF->invoiceStatus();
        $country_list = $this->functions->countrySelectOption();
        $storeList      =    $storeC->storeNamesCountryValueOption();
        $token       = $this->functions->setFormToken('orderAdd',false);


        //user list
        $this->functions->require_once_custom('webUsers.class');
        $userC = new webUsers();
        $usersOption = $userC->userSelectOptionList();

        echo '

    <form method="post" class="form-horizontal" action="quote/classes/quote.php">
        '.$token.'
        <input type="hidden" id="priceCode" name="priceCode"/>
        <input type="hidden" name="pay_terms" value="Cash On Devlivery" />
        <div class="form-horizontal">

        <div class="col-md-6">
                <div class="form-group">
                    <label for="input2" class="col-sm-2 col-md-3  control-label">'. _uc($_e['Store Country']) .'</label>
                    <div class="col-sm-10  col-md-9">
                    <input type="hidden" name="storeCountry" class="form-control storeCountry" data-val="">
                    <select  id="storeCountry" onchange="orderProductJson(this);"  name="storeCountry" class="form-control" required="required">
                        <option value="">'. _uc($_e['Select Country']) .'</option>
                            '. $storeList .'
                        </select>
                    </div>
                </div>

                <div class="form-group" style="position: relative;margin-bottom: 30px;">
                        <div id="loadingProgress" style="position: absolute;width: 100%;"></div>
                </div>


            </div><!-- First col-md-6 end -->


         <div class="table-responsive inline-block newProduct " >
            <div id="productNotFoundInCountry"></div>
              <table id="newProduct" class="table sTable table-hover " width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>'. _u($_e['PRODUCT']) .'</th>
                        <th>'. _u($_e['STORE']) .'</th>
                        <th width="110">'. _u($_e['QUANTITY']) .'</th>
                        <th>'. _u($_e['PRICE']) .'</th>
                        <th>'. _u($_e['TOTAL PRICE']) .'</th>
                    </tr>
                </thead>
                <tbody>
                    <td>
                            <input type="text" class="form-control" id="invoice_product_id" placeholder="'. _uc($_e['Select Product Name']) .'">
                            <input type="hidden" class="form-control invoice_product_id" data-val="">
                            <input type="hidden" class="form-control invoice_product_shipping" data-val="">
                            <input type="hidden" class="" id="invoice_product_weight">
                    </td>
                    <td>
                            <input type="text" class="form-control" id="invoice_product_store" placeholder="'. _uc($_e['Select Store']) .'" readonly value="No Store Avaiable">
                            <input type="hidden" class="form-control invoice_product_store" data-val="">
                    </td>
                    
                    <td>
                            <input type="number" class="form-control" data-val="" data-max="0" min="1"  id="invoice_qty" placeholder="'. _uc($_e['Product QTY']) .'">
                    </td>
                    <td>
                            <input type="number" class="form-control" data-val="" min="0"   id="invoice_price" placeholder="'. _uc($_e['Single Price']) .'">
                    </td>
                    <td>
                            <input type="number" readonly class="form-control" min="0" id="invoice_total_price" placeholder="'. _uc($_e['TOTAL PRICE']) .'">
                    </td>
                </tbody>
              </table>

            <div class="form-group">
                <div class="col-sm-10">
                    <button type="button" onclick="invoiceFormValid();" id="AddProduct" class="btn btn-default">'. _uc($_e['Add Product']) .'</button>
                </div>
             </div>


            </div> <!-- first table end-->

            <div style="margin:70px 0 0 0;">
                <input type="button" class="btn btn-danger" onclick="removechecked()" value="'. _uc($_e['Remove Checked Items']) .'" >
                <input type="button" class="btn btn-danger" onclick="uncheckall()" value="'. _uc($_e['Check/Uncheck All']) .'">
                <br><br>


             <div class="table-responsive" >
              <table id="addSelectedProduct" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>'. _u($_e['NO']) .'</th>
                        <th>'. _u($_e['PRODUCT']) .'</th>
                    
                        <th>'. _u($_e['QTY']) .'</th>
                        <th width="300">'. _u($_e['(QTY*PRICE) = TOTAL PRICE']) .'</th>
                    </tr>
                </thead>
                <tbody id="vendorProdcutList">

                </tbody>

                </table>
            </div><!-- table-responsive added -->

            <div class="container-fluid text-right">
                <div class="h4"> '. _u($_e['QUANTITY']) .': <span class="totalQuantity bold">0</span></div>
                
                <div class="h4"> '. _u($_e['TOTAL PRICE']) .': <span class="totalPrice bold">0</span></div>
                <input type="hidden" name="totalPrice" class="totalPriceInput" />
                <input type="hidden" name="totalWeight" class="totalWeightInput" />
            </div>



            <div class="container-fluid">
                <h3 class="navbar-inverse bg-black text-center" >'. _uc($_e['Quote Information']) .'</h3>
                <div class="col-sm-8">
                    <div class="navbar-inverse bg-black" style="color:#fff">'. _uc($_e['Quote Information']) .'</div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['Name']) .'</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="reciver_name" id="sender_name" required="" placeholder="'. _uc($_e['Name']) .'"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['Phone']) .'</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="reciver_phone" id="sender_phone" placeholder="'. _uc($_e['Phone']) .'"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['Email']) .'</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="reciver_email" id="sender_email" placeholder="'. _uc($_e['Email']) .'"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['City']) .'</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sender_city" id="sender_city" placeholder="'. _uc($_e['City']) .'"/>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['Country']) .'</label>
                            <div class="col-sm-9">
                                <fieldset class="sender_countryFieldset">
                                    <select required  id="sender_country" name="sender_country" class="form-control">
                                        <option value="">------</option>
                                        '. $country_list .'
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['Address']) .'</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="sender_address" id="sender_address" placeholder="'. _uc($_e['Address']) .'"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receipt_vendor" class="col-sm-3 control-label">'. _uc($_e['Terms & Conditions']) .'</label>
                            <div class="col-sm-9">
                                <textarea class="form-control ckeditor" name="reciver_note" id="sender_address" ></textarea>
                            </div>
                        </div>

                    </div><!-- col-md-12 sender info end -->
                </div>
                <!-- col-md-6 sender info end -->

                

            </div> <!-- Send Receiver Info-->

            <div class="clearfix"></div>


        <div class="clearfix"></div>
        <br>
            <div class="container-fluid ReviewButtons">
                <input type="submit" name="submit" value="ORDER" class="submit btn btn-primary btn-lg">
            </div>
        <div class="clearfix"></div>
        <br/>';

            $viewBody ='
                <div class="FinalPriceReport">
                   <div class="h4"> '. _uc($_e['Order  Price']) .' : <span class="totalPriceModel bold"></span></div>
                   <div class="h4"> '. _uc($_e['Shipping Price']) .' : <span class="totalPriceShipping bold"></span></div>
                   <div class="h4"> '. _uc($_e['Total']) .' : <span class="totalFinal bold"></span></div>
                </div>
                <br>
                
                <br>';

            $this->functions->customDialogView('Check Out',$viewBody,'Close');

echo '
         <!-- if you change value of button then must change from addNewOrder();
         </div> <!-- added product script div end -->

         </div> <!-- form-horizontal end -->
       </form>
       </div>
       <div class="container-fluid lastReview displaynone">
           <div class="reportReview">
               <div class="form-horizontal">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="col-sm-4 col-md-5">'. _uc($_e['Store Country']) .'</label>
                            <div class="col-sm-8 col-md-7">
                                <div id="reportStoreCountry">View</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-sm-4 col-md-5">'. _uc($_e['Payment Type']) .'</label>
                            <div class="col-sm-8 col-md-7">
                                <div id="reportPaymentType">View</div>
                            </div>
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label  class="col-sm-4 col-md-5">'. _uc($_e['Invoice Status']) .'</label>
                            <div class="col-sm-8 col-md-7">
                                <div id="reportInvoiceStatus">View</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-sm-4 col-md-5">'. _uc($_e['Payment Info']) .'</label>
                            <div class="col-sm-8 col-md-7">
                                <div id="reportPaymentInfo">View</div>
                            </div>
                        </div>

                    </div><!-- col-md-6 end -->
                </div><!-- Form horizontal 1 end-->
            <hr>
                <h4>'. _uc($_e['Selected Products']) .'</h4>
                <div class="col-sm-12" id="reportSelectedProduct"></div>
            <hr>
                <h4>'. _uc($_e['Sender And Receiver Information']) .'</h4>

            <div class="form-horizontal">
                <div class="col-md-6">

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Sender Name']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportSenderName">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Sender Phone']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportSenderPhone">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Sender Email']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportSenderEmail">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Sender City']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportSenderCity">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Sender Country']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportSenderCountry">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Sender Address']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportSenderAddress">View</div>
                        </div>
                    </div>

                </div><!-- col-sm-6 end 1-->

                <div class="col-md-6">

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Name']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportReceiverName">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Phone']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportReceiverPhone">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Email']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportReceiverEmail">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Receiver City']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportReceiverCity">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Country']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportReceiverCountry">View</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 col-md-5">'. _uc($_e['Receiver Address']) .'</label>
                        <div class="col-sm-8 col-md-7">
                            <div id="reportReceiverAddress">View</div>
                        </div>
                    </div>

                </div> <!--col sm 6 end 2-->

            </div><!-- Form horizontal 2 end-->
            </div>
        </div><!-- Last review Info-->';


    }



    public function addNewOrder(){
        global $_e;
        if(!$this->functions->getFormToken('orderAdd')){ return false;}
        $btn1   =   'ORDER';
        $btn2   =   'ORDER AND PROCESS';
        //set Submit buttons value here
        if(isset($_POST) && !empty($_POST) && !empty($_POST['cart_list']) && !empty($_POST['receiver_name']) && !empty($_POST['receiver_country'])  ){


          try{
            $this->db->beginTransaction();
              if($_POST['submit'] == $btn1){
                  $process    =   0;
              }else if($_POST['submit'] == $btn2){
                  $process    =   1; //submit product quantity from inventory
              }else{
                  throw new Exception("");
              }

            $invoiceId  =   '';
            @$paymentType    =   $_POST['paymentType']; //int
            @$payment_info   =   $_POST['paymentInfo']; //text
            @$invoiceStatus  =   $_POST['invoiceStatus']; // varchar
            @$total_price    =   $_POST['totalPrice']; //Using In Security, If price from web form or php calculated not match, mean Hacking Attempt
            @$price_code     =   $_POST['priceCode'];
            @$country        =   $_POST['storeCountry'];
            @$userId        =   $_POST['userId'];
            @$totalWeightReceiveFromForm = $_POST['totalWeight']; //Using In Security, If Weight from web form or php calculated not match, mean Hacking Attempt
            $total_priceNew  =   0; //Calculateing in foreach loop, test with $total_price after loop, If not match its hacking attempt
            $total_weightNew =  0;//Calculateing in foreach loop, test with $totalWeightReceiveFromForm after loop, If not match its hacking attempt


            $countryData = $this->productF->productCountryId($country);
            $countryId   =  $countryData['cur_id'];

            //major data submit here, will later here, update this table
              $now = date('Y-m-d H:i:s');
            $sql = "INSERT INTO `order_invoice`
                        (
                            `paymentType`,
                            `invoice_date`,
                            `orderUser`,
                            `payment_info`,
                            `price_code`,
                            `invoice_status`
                        )
                        VALUES (
                            ?,?,?,?,?,?
                        )";
            $array=array($paymentType,$now,$userId,$payment_info,$price_code,$invoiceStatus);
            $this->dbF->setRow($sql,$array,false);
            $invoiceId=$this->dbF->rowLastId;
            // invoice first data Enter

            //Invoice Product add
            foreach($_POST['cart_list'] as $key=>$id){
                $pArray     =   explode("_",$id); //p_491-246-435-5    => p_ pid - scaleId - colorId - storeId;
                $pIds       =   $pArray[1];
                $pArray     =   explode("-",$pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
                $pId        =   $pArray[0]; // 491
                $scaleId    =   $pArray[1]; // 426
                $colorId    =   $pArray[2]; // 435
                $storeId    =   $pArray[3]; // 5
                @$customId    =   $pArray[4]; // 5


                $pName      =   $this->productF->getProductFullName($pId,$scaleId,$colorId);
                $storeName  =   $this->productF->getStoreName($storeId);
                $pPrice     =   $this->productF->productTotalPrice($pId,$scaleId,$colorId,$customId,$country);



            //price calculation
                $salePrice  =   $_POST['pTotalprice_'.$id];

                /*$discountArray = $this->productF->productDiscount($pId,$countryId);
                if(!empty($discountArray)){
                    $discount       =   $discountArray['discount'];
                    $discountFormat =   $discountArray['discountFormat'];
                    if($discountFormat=='price'){
                        $discount   =   $pPrice-$discount;
                    }else if($discountFormat=='percent'){
                        $discount   =   ($pPrice*$discount)/100;
                    }
                }else{
                    $discount   = 0;
                }*/

                $discount   =   floatval($_POST['pDiscount_'.$id]);
                $total_priceNew += floatval($salePrice);
                $saleQTY    =   $_POST['pQty_'.$id];
                $salePrice  =   ($salePrice+$discount)/$saleQTY; // get single product QTY price


            //Weight Calculation
                $weight     =   $this->productF->getProductWeight($pId,$scaleId,$colorId);
                $total_weightNew += $weight*$saleQTY;

                @$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;
                $hash       =   md5($hashVal);

                $sql    =   "INSERT INTO `order_invoice_product`
                                (
                                `order_invoice_id`,
                                `order_pIds`,
                                `order_pName`,
                                `order_pStore`,
                                `order_pPrice`,
                                `order_salePrice`,
                                `order_discount`,
                                `order_pQty`,
                                `order_pWeight`,
                                `order_process`,
                                `order_hash`
                                ) VALUES (
                                    ?,?,?,?,?,?,?,?,?,?,?
                                )";
                $array  =   array($invoiceId,$pIds,$pName,$storeName,$pPrice,$salePrice,$discount,$saleQTY,$weight,$process,$hash);
                $this->dbF->setRow($sql,$array,false);

                // Remove QTY FROM inventory
                if($process==1){
                    $invQty =   $this->productF->stockProductQty($hash);
                    if($invQty >= $saleQTY){
                        if($this->productF->stockProductQtyMinus($hash,$saleQTY)){
                        }else{
                            throw new Exception($_e['Quote QTY is Greater Than stock Quantity']);
                        }
                    }else{
                        throw new Exception($_e['Quote QTY is Greater Than stock Quantity']);
                    }
                } // If Process Order End
            } // Foreach loop End

              //check php calculate price and javascript price
              if(floatval($total_price) != floatval($total_priceNew)){
                  throw new Exception("Hacking Attempt Found Code : 151");
              }

              //check php calculate weight and javascript weight
              if(floatval($totalWeightReceiveFromForm) != floatval($total_weightNew)){
                  throw new Exception("Hacking Attempt Found Code : 152");
              }

            // User Info Add
            //first add order invoice,, addNewOrder(); // not klarna
              if(intval($paymentType) !=intval('2')){

            $sql    =   "INSERT INTO `order_invoice_info`
                        (
                            `order_invoice_id`,

                            `sender_name`,
                            `sender_phone`,
                            `sender_email`,
                            `sender_address`,
                            `sender_city`,
                            `sender_country`,
                            `sender_post`,

                            `receiver_name`,
                            `receiver_phone`,
                            `receiver_email`,
                            `receiver_address`,
                            `receiver_city`,
                            `receiver_country`,
                            `receiver_post`
                        )
                        VALUES (
                            ?,
                            ?,?,?,?,?,?,?,
                            ?,?,?,?,?,?,?
                        )";
                $array  =   array(
                    $invoiceId,
                    $_POST['sender_name'] , $_POST['sender_phone'] , $_POST['sender_email'] , $_POST['sender_address'] , $_POST['sender_city'] , $_POST['sender_country'],$_POST['sender_post'],
                    $_POST['receiver_name'],$_POST['receiver_phone'],$_POST['receiver_email'],$_POST['receiver_address'],$_POST['receiver_city'],$_POST['receiver_country'],$_POST['receiver_post'],
                );
                $this->dbF->setRow($sql,$array,false);
              }
            //Update invoice after
              //Calculating Shiping price
            $shippingData = $this->productF->shippingPrice($country,$_POST['receiver_country']);
            if($shippingData==false){
                //throw new Exception("Hacking Attempt Found OR Shipping Error");
                throw new Exception($_e["Shipping Error"]);
            }

            $shippingWeight    =    $shippingData['shp_weight'];
            $shippingPrice     =    $shippingData['shp_price'];
            //calculating
            @$unitWeight       =   ceil($total_weightNew/$shippingWeight);
            $unitWeight        =   round($unitWeight,2);
            $finalShippingPrice=    $shippingPrice*$unitWeight;

            $total_priceNew += $finalShippingPrice;

            $invoiceKey =   $this->functions->ibms_setting('invoice_key_start_with'); // Invoice Number start with


              if(intval($paymentType)===intval('2') ){
                  $processStatus  = 'inComplete';
              }else{
                  $processStatus= 'process';
              }
            $sql    =   "UPDATE `order_invoice` SET
                            `invoice_id`    =   '".$invoiceKey.''.$invoiceId."',
                            `total_price`   =   '$total_priceNew',
                            `ship_price`     =   '$finalShippingPrice',
                            `total_weight`  =   '$total_weightNew',
                            `orderStatus`       =   '$processStatus',
                            `shippingCountry`   =   ?
                               WHERE `order_invoice_pk`  = '$invoiceId'";
            $this->dbF->setRow($sql,array($_POST['receiver_country']),false);

            $this->db->commit();

           if($this->dbF->rowCount>0){
            $msg    = $this->functions->notificationError(_js(_uc($_e['Product Successfully Submit'])),_js($_e['Thank you your product is successfully submit']),'btn-success');
            $_SESSION['msg'] =base64_encode($msg);
            $this->functions->setlog(_uc($_e['New Quote']),_uc($_e['Quote']),$invoiceKey.''.$invoiceId,$_e['New Quote Added Successfully']);
           }else{
               $msg    = $this->functions->notificationError(_js(_uc($_e['Product Submit'])),_js(_uc($_e['Product Submit Failed'])),'btn-danger');
               $_SESSION['msg'] =base64_encode($msg);
           }

              $this->productF->paymentProcess($paymentType);
              // $this->functions->submitRefresh();
          }catch(Exception $e){
              $this->dbF->error_submit($e);
              $this->db->rollBack();
              $msg  = '';
              $msg  = $e->getMessage();
              if($msg != ''){
                  $msg  =  $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])),$msg,'btn-danger');
              }
              $msg  =  $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])),_js($_e['Some thing went wrong Please try again']),'btn-danger');
              $_SESSION['msg'] =base64_encode($msg);
          }

        }else if(isset($_POST) && !empty($_POST) && ($_POST['submit']==$btn1 || $_POST['submit']==$btn2) ){
              $msg  =  $this->functions->notificationError(_js(_uc($_e['Product Submit Fail'])),_js($_e['Some thing went wrong Please try again']),'btn-danger');
              $_SESSION['msg'] =base64_encode($msg);
        }

    } // Function End



    public function  invoiceOrdersSql(){
        $sql="SELECT * FROM `order_invoice` WHERE orderStatus != 'inComplete' AND invoice_status != '3'  AND invoice_status != '0' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function  all($user_id=false){
        $user = "";
        $array = array();
        if( ! empty($user_id) ) {
            $user = " WHERE `orderUser` = ? ";
            $array[] = $user_id;
        }
        $sql     =  "SELECT * FROM `order_invoice` $user ORDER BY order_invoice_pk DESC";
        $invoice =  $this->dbF->getRows($sql,$array);
        return $invoice;
    }

    public function  completeOrdersSql(){
        $sql="SELECT * FROM `order_invoice` WHERE invoice_status = '3' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function  cancelOrdersSql(){
        $sql="SELECT * FROM `order_invoice` WHERE invoice_status = '0' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function  inCompleteOrdersSql(){
        $sql="SELECT * FROM `order_invoice` WHERE orderStatus = 'inComplete' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        return $invoice;
    }
    public function  invoiceList($order= '',$user_id = false){
        global $_e;
        $href      = "quote/order_ajax.php?page=data_ajax_all";
        $class     = "dTable";
        $data_attr = '';

        $sql="SELECT * FROM `quote` ORDER BY `qId` DESC";
        $invoice = $this->dbF->getRows($sql);  
        //$class   = "dTable_ajax";      

        echo '
            <div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _u('SNO') .'</th>
                        <th>'. _u('Name') .'</th>
                        <th>'. _u('Email') .'</th>
                        <th>'. _u('File') .'</th>
                        <th>'. _u('Date') .'</th>
                        <th width="120">'. _u('ACTION') .'</th>
                    </thead>
                <tbody>';

         $i=0;
        foreach($invoice as $val){
            $i++;
            $divInvoice ='';
            $id = $val['qId'];
            $link = WEB_URL.'/uploads/files/quote/'.$val['qFile'];

            echo "<tr>
                <td>$i</td>
                <td>$val[qName]</a></td>
                <td>$val[qEmail]</td>
                <td><a href=".$link.">Download</a></td>
                <td>$val[qDate]</td>
                <td>
                    <div class='btn-group btn-group-sm'>
                        <a data-id='$id' onclick='return delOrderInvoice(this);' class='btn'>
                            <i class='glyphicon glyphicon-trash trash'></i>
                            <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                        </a>
                    </div>
                </td>
            </tr>";

        }

        echo '
                </tbody>
                </table>
            </div> <!-- .table-responsive End -->';

    }


    public function orderInvoiceInfo($orderId){
        $sql    =   "SELECT * FROM order_invoice_info WHERE order_invoice_id = '$orderId'";
        $data   =   $this->dbF->getRow($sql);
        return $data;
    }

    public function invoiceListUser($userId,$echo = true){
        global $_e;
        $temp = '';

        $temp .= '
            <div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th class="hidden-xs">'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['INVOICE']) .'</th>
                        <th class="hidden-xs">'. _u($_e['CUSTOMER NAME']) .'</th>
                        <th class="hidden-xs">'. _u($_e['INVOICE DATE']) .'</th>
                        <th>'. _u($_e['PURCHASE PRICE']) .'</th>
                        <th class="hidden-xs hidden-sm">'. _u($_e['PAYMENT METHOD']) .'</th>
                        <th class="hidden-xs">'. _u($_e['ORDER PROCESS']) .'</th>
                        <th>'. _u($_e['Invoice Status']) .'</th>
                        <th>'. _u($_e['VIEW ORDER']) .'</th>
                    </thead>
                <tbody>';

        $sql="SELECT * FROM `order_invoice` WHERE orderUser = '$userId' ORDER BY order_invoice_pk DESC";
        $invoice = $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){
            $noFound = "<div class='alert alert-danger text-center'>".$this->dbF->hardWords('No Invoice Found',false)."</div>";
            if($echo){
                echo $noFound;
            }else{
                return $noFound;
            }
            return "";
        }
        $i=0;
        foreach($invoice as $val){
            $i++;
            $divInvoice     =   '';
            $invoiceStatus  =   $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];

            if($st=='0') $divInvoice = "<div class='btn btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if($st=='1') $divInvoice = "<div class='btn btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if($st=='2') $divInvoice = "<div class='btn btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if($st=='3') $divInvoice = "<div class='btn btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else $divInvoice = "<div class='btn btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";

            $invoiceDate    =   date('Y-m-d H:i:s',strtotime($val['dateTime']));
            $invoiceId      =   $val['order_invoice_pk'];

            $orderInfo      =   $this->orderInvoiceInfo($invoiceId);
            $customeName    =   $orderInfo['sender_name'];

            //Check order process or not,, if single product process it show 1
            $sql    =   "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);

            $orderProcess   ="<div class='btn btn-danger  btn-sm' style='width:50px;'>". _uc($_e['NO']) ."</div>";
            if($this->dbF->rowCount>0){
                //make sure all order process or custome process
                $sql    =   "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if($this->dbF->rowCount>0){
                    $orderProcess   ="<div class='btn btn-warning  btn-sm' style='width:50px;'>". _uc($_e['Yes']) ."</div>";
                }else{
                    $orderProcess   ="<div class='btn btn-success  btn-sm' style='width:50px;'>". _uc($_e['Yes']) ."</div>";
                }
            }
            $days    = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link    = $this->functions->getLinkFolder();
            $date    =   date('Y-m-d',strtotime($val['dateTime']));
            $minusDays  =   date('Y-m-d',strtotime("-$days days"));

            $class = "
                    <a href='invoicePrint?mailId=$invoiceId&orderId=".$this->functions->encode($invoiceId)."'  target='_blank' class='btn btn-success'>
                       <i class='fa fa-file-pdf-o'></i>
                    </a>
                    <a href='?view=$invoiceId&orderId=".$this->functions->encode($invoiceId)."' class='btn  btn-success'>
                        <i class='glyphicon glyphicon-list-alt'></i>
                    </a>";
            if($val['orderStatus']=='inComplete'
                || $val['orderStatus']=='pendingPaypal'
                || $val['orderStatus']=='pendingPayson'){
                $class = "
                    <a href='orderInvoice.php?inv=$invoiceId' target='_blank' class='btn btn-danger'>
                       <i class='glyphicon glyphicon-share-alt '></i>
                    </a>
                    <a href='?view=$invoiceId&orderId=".$this->functions->encode($invoiceId)."' class='btn  btn-success'>
                        <i class='glyphicon glyphicon-list-alt'></i>
                    </a>

                        ";
            }

            $paymentMethod  =   $val['paymentType'];
            $paymentMethod  =   $this->productF->paymentArrayFindWeb($paymentMethod);
            $temp .= "<tr>
                <td class='hidden-xs'>$i</td>
                <td>$val[invoice_id]</td>
                <td class='hidden-xs'>$customeName</td>
                <td class='hidden-xs'>$invoiceDate</td>
                <td>$val[total_price] $val[price_code]</td>
                <td class='hidden-xs hidden-sm'>$paymentMethod</td>
                <td class='hidden-xs'>$orderProcess</td>
                 <td>$divInvoice</td>
                <td>
                <div class='btn-group btn-group-sm'>
                    $class";
            $temp .= "</div>
                </td>
            </tr>";
        }

        $temp .= '
                </tbody>
                </table>
            </div> <!-- .table-responsive End -->';

        if($echo){
            echo $temp;
        }else{
            return $temp;
        }

    }

    public function invoiceSQL($column = '*'){
        $sql="SELECT ".$column." FROM `order_invoice`";
        return $this->dbF->getRows($sql);
    }

}

?>