<?php

class invoice extends object_class{
    public $productF;
    public function __construct(){
        parent::__construct('3');
        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../../product_management/functions/product_function.php");
            $this->productF=new product_function();

            /**
             * MultiLanguage keys Use where echo;
             * define this class words and where this class will call
             * and define words of file where this class will called
             **/
            global $_e;
            global $adminPanelLanguage;
            $_w=array();
            //Invoice.php
            $_w['View Api Return Info'] = '' ;
            $_w['Invoice Detail View'] = '' ;
            $_w['ORDER SENDER DETAIL'] = '' ;
            $_w['Name'] = '' ;
            $_w['LOCATION'] = '' ;
            $_w['E-mail'] = '' ;
            $_w['Phone'] = '' ;
            $_w['Address'] = '' ;
            $_w['Post Code'] = '' ;
            $_w['City'] = '' ;
            $_w['Country'] = '' ;
            $_w['ORDER RECEIVER DETAIL'] = '' ;
            $_w['TOTAL'] = '' ;
            $_w['PROCESS'] = '' ;
            $_w['SALE QTY'] = '' ;
            $_w['DISCOUNT'] = '' ;
            $_w['SALE IN PRICE'] = '' ;
            $_w['ORIGINAL PRICE'] = '' ;
            $_w['STORE NAME'] = '' ;
            $_w['PRODUCT NAME'] = '' ;
            $_w['ORDER PRODUCTS'] = '' ;
            $_w['SNO'] = '' ;
            $_w['NO'] = '' ;
            $_w['Yes'] = '' ;
            $_w['Total Net Amount'] = '' ;
            $_w['Print Out'] = '' ;
            $_w['INTERNAL COMMENT'] = '' ;
            $_w['Enter Vendor Payment Information'] = '' ;
            $_w['Payment Info'] = '' ;
            $_w['Reservation Number'] = '' ;
            $_w['InComplete'] = '' ;
            $_w['OK'] = '' ;
            $_w['Payment Status'] = '' ;
            $_w['Payment Type'] = '' ;
            $_w['Value'] = '' ;
            $_w['Property'] = '' ;
            $_w['Payment Information'] = '' ;
            $_w['Send Email To Customer'] = '' ;
            $_w['Shipping Track Number'] = '' ;
            $_w['Invoice Status'] = '' ;
            $_w['Date Time'] = '' ;
            $_w['Total'] = '' ;
            $_w['Total Product Price'] = '' ;
            $_w['Shipping Price'] = '' ;
            $_w['Total Weight'] = '' ;
            $_w['Invoice ID'] = '' ;
            $_w['Invoice Detail'] = '' ;
            $_w['Custom'] = '' ;
            $_w['Close'] = '' ;
            //This class
            $_w['Stock'] = '' ;
            $_w['Submit DateTime'] = '' ;
            $_w['Stock QTY is less then your Order, Please check'] = '' ;
            $_w['Stock Error stock not found for process OR stock QTY error, Please check'] = '' ;
            $_w['Product Update Successfully'] = '' ;
            $_w['Product Update Failed'] = '' ;
            $_w['Product Update'] = '' ;
            $_w['Deal'] = '' ;
            $_w['Edit custom size form'] = '' ;
            $_w['User not fill final form'] = '' ;
            $_w['Print PDF'] = '' ;
            $_w['Discount Code'] = '' ;
            $_w['Creation Time'] = '' ;
            $_w['3 For 2 Category'] = '' ;
            $_w['Free Gift'] = '' ;
            $_w['Checkout'] = '' ;
            $_w['OFFER'] = '' ;
            $_w['Last Updated Time'] = '' ;
            $_w['RETURNS INFO'] = '' ;
            $_w['Refunded'] = '' ;
            $_w['Defected'] = '' ;
            $_w['Changed Product'] = '' ;
            $_w['Changed Size'] = '' ;
            $_w['Status Unknown'] = '' ;
            $_w['PRICE'] = '' ;
            $_w['STATUS'] = '' ;
            $_w['BILLING MODE'] = '' ;
            $_w['Price'] = '' ;
            $_w['SCHEDULE'] = '' ;
            $_w['Transaction Reference'] = '' ;
            $_w['SCHEDULE DATE'] = '' ;
            $_w['TIME SLOT'] = '' ;
            $_w['DETAIL'] = '' ;
            $_w['TECHNICAL FORM'] = '' ;
            $_w['ACTION'] = '' ;
            $_w['Due Date'] = '' ;
            $_w['CUSTOMER DETAILS'] = '' ;
            $_w['PRODUCT DETAILS'] = '' ;
            $_w['INVOICE DETAILS'] = '' ;
            $_w['Order Placed'] = '' ;
            $_w['Pending Installation'] = '' ;
            $_w['Live'] = '' ;
            $_w['Pending Removal'] = '' ;
            $_w['MONTHLY PRICE'] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;
            $_w[''] = '' ;

            $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Invoice');

        }
    }


    public function customSubmitValues($orderId){
        global $_e;
        $sql  = "SELECT *,
                    (SELECT setting_value FROM p_custom_setting as b WHERE b.fieldName=s.setting_name AND b.setting_name='name' AND b.c_id=a.custom_id ) as tName
                     FROM `p_custom_submit` as a JOIN `p_custom_submit_setting` as s ON a.id = s.orderId  WHERE a.id = '$orderId'";
        $data = $this->dbF->getRows($sql);
        if(empty($data)){
            return false;
        }

        foreach($data as $val){
            $name   = $val['setting_name'];
            $tName  = $this->functions->unserializeTranslate($val['tName']);
            if(empty($tName)){
                $tName = $name;
            }
            $value = $val['setting_value'];
            $form_fields[] = array(
                'label' => $tName,
                'format' => "$value"
            );
        }

        if($data[0]['submitLater']=='1' && $this->functions->isWebLink()){
            $customEditLink = WEB_URL."/viewOrder?editCustom=".$this->functions->encode($orderId);
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$customEditLink' class='btn themeButton'>".$_e["Edit custom size form"]."</a></div>"
            );
        }else if($data[0]['submitLater']=='1' && $this->functions->isAdminLink()){
            $form_fields[] = array(
                'thisFormat' => "<div class='text-center form-group  margin-0'>".$_e["User not fill final form"]."</div>"
            );
        }else if($data[0]['submitLater']=='0'){
            $form_fields[] = array(
                'label'  => $_e["Submit DateTime"],
                'type'   => "none",
                'format' => "<div class='text-center form-group  margin-0'>".date('H:i:s d-m-Y',strtoTime($data[0]['dateTime']))."</div>"
            );

            $pdfLink =  WEB_URL."/src/pdf/measurementPDF.php?id=$orderId&orderId=".$this->functions->encode($orderId);
            $form_fields[] = array(
                'label'  => $_e["Print PDF"],
                'type'   => "none",
                'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$pdfLink' target='_blank' class='btn btn-default'>{$_e["Print PDF"]}</a></div>"
            );

        }

        $form_fields['main'] = array(
            'type'   => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>
                         <style>#customSizeInfo_$orderId .modal-body{padding: 0 15px;}</style>
                        "
        );

        $format = '<div class="form-group border padding-5 margin-0">
                        <label class="col-sm-2 col-md-3 text-right">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $array = array("form"=>$this->functions->print_form($form_fields,$format,false),"formFill"=>$data[0]['submitLater']);
        return $array;

    }

    public function dealSubmitPackage($orderId,$cart=true){
        if($cart) {
            $orderId = $this->getDealProductOrders($orderId);
        }
        foreach($orderId as $val){
            $name = $val['name'];
            $form_fields[] = array(
                //'label' => $name,
                'format' => "<div>$name</div>"
            );
        }

        $form_fields['main'] = array(
            'type'   => "form",
            'format' => "<div class='form-horizontal'>{{form}}</div>"
        );

        $format = '<div class="form-group border padding-5 margin-0">
                        <div class="col-sm-12 text-center">
                            {{form}}
                        </div>
                    </div>';

        return $this->functions->print_form($form_fields,$format,false);

    }

    public function invoiceDetail($id){
        $id=    intval($id);
        $sql = "SELECT * FROM orders left join order_detail
                    on orders.order_id= order_detail.order_id
                    WHERE orders.order_id='$id'";

        $data =$this->dbF->getRow($sql);
        return $data;
    }
    public function orderData($id){
        $id=    intval($id);
        $sql = "SELECT * FROM order_invoice WHERE order_invoice.order_invoice_pk='$id'";

        $data =$this->dbF->getRow($sql);
        return $data;
    }
    public function invoiceProduct($id){
        $sql = "SELECT * FROM  order_invoice_product
                    WHERE order_invoice_id='$id'";

        $data =$this->dbF->getRows($sql);
        return $data;
    }


    public function handelKlarna($orderId,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done){
        //All work will Handel Accordingly
        $this->functions->require_once_custom('Class.myKlarna.php');
        $klarnaClass    = new myKlarna();

        return $klarnaClass->klarnaInvoices($orderId,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done);

    }



    public function update(){
        global $_e;
        if(!$this->functions->getFormToken('Invoice')){
            return false;
        }

        try{
        $this->db->beginTransaction();
        $id = $_POST['pId'];
        if(isset($_POST['submit'])){
            if(isset($_POST['invoiceStatus'])){
                $inv = $_POST['invoiceStatus'];
                @$paymentInfo = $_POST['paymentInfo'];
                if(isset($_POST['payment'])){
                    $paymentTypeSql = "paymentType = '".$_POST['payment']."', ";
                    $paymentType    =   $_POST['payment'];
                }else{
                    $paymentType = '';
                    $paymentTypeSql = '';
                }

                /*  if(!isset($_POST['pro']) && isset($_POST['invoiceStatus'])){
                    echo $this->functions->notificationError("Error","Before Process Please select Orders product for continue process","btn-danger");
                    throw new Exception("");
                }*/
                if(($inv=='0' || $inv=='3' || $inv=='6') && ($paymentType=='2')){
                    $sql = "SELECT inTransaction,rsvNo,rsvNo_done FROM `order_invoice` WHERE order_invoice_pk = '$id' AND inTransaction!=''";
                    $dataTrans  = $this->dbF->getRow($sql);
                    if($this->dbF->rowCount>0){
                        $rsvNo  =   $dataTrans['rsvNo'];
                        $rsvNo_done =    $dataTrans['rsvNo_done'];
                        $inTransaction=trim($dataTrans['inTransaction']);
                        /* ------- ---------- KLARNA ------------- ------------ */
                           $klarnaReturn   =   $this->handelKlarna($id,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done);
                           $returnKlarna   =   $klarnaReturn;
                        /* ------- ----------KLARNA End------------- ------------ */
                        $paymentInfo =  $paymentInfo."\n $returnKlarna";
                    }
                }

                $sql ="UPDATE order_invoice SET invoice_status='$inv',
                 payment_info = ?,
                 $paymentTypeSql
                 trackNo    = ?,
                 comment  = ?
                  WHERE order_invoice_pk = '$id'";

                $this->dbF->setRow($sql,array($paymentInfo,$_POST['trackNo'],$_POST['comment']),false);

                if($_POST['sendEmail']=='1'){
                    $link       = WEB_URL."/viewOrder?view=$id&orderId=".$this->functions->encode($id);
                    $invStatus  =   $this->productF->invoiceStatusFind($inv);

                    $to         =  $_POST['toEmail'];
                    $invoice    =   $this->functions->ibms_setting('invoice_key_start_with');
                    $mailArray['link']        =   $link;
                    $mailArray['invoiceStatus'] =   $invStatus;
                    $mailArray['invoiceNumber'] =   $invoice."".$id;
                    $mailArray["other"]['shippingNumber'] =  $_POST['trackNo'];
                    $this->functions->send_mail($to,'','','orderUpdate','',$mailArray);
                    $adminMail = $functions->ibms_setting('Email');
                    $this->functions->send_mail($adminMail,'','','orderUpdate','',$mailArray);
                }
            }

            if(isset($_POST['pro'])){
                @$pr = $_POST['payment'];

                //Stock Not deduct on Admin side
                $status = $this->stockDeductFromOrderAdmin($id,false);
                if($status===false){
                    throw new Exception("");
                }
            }
            if($this->dbF->rowCount>0){
                echo  $this->functions->notificationError(_js(_uc($_e["Product Update"])),_js($_e["Product Update Successfully"]),"btn-success");
            }else{
                echo  $this->functions->notificationError(_js(_uc($_e["Product Update"])),_js($_e["Product Update Failed"]),"btn-danger");
            }
       }

            $this->db->commit();
        }catch(Exception $e){
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }


    public function stockDeductFromOrderAdmin($orderId,$transection=true){
        global $_e;

        $sql ="SELECT * FROM order_invoice_product WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRows($sql,false);

        foreach($data as $d){
            $invProductId = $d['invoice_product_pk'];

            if(in_array($d['invoice_product_pk'],$_POST['pro'])){
            }else{
                continue;
            }

            $pids = $d['order_pIds'];
            $pids = explode("-",$pids);

            $pId = $pids[0];
            $scaleId = $pids[1];
            $colorId = $pids[2];
            $storeId = $pids[3];

            $saleQTY = $d['order_pQty'];

            @$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;
            $hash       =   md5($hashVal);

            $invQty =   $this->productF->stockProductQty($hash);
            if($saleQTY  <= $invQty){
                if( $this->productF->stockProductQtyMinus($hash,$saleQTY) ){
                    $sql ="UPDATE order_invoice_product SET order_process = '1' WHERE invoice_product_pk = '$invProductId'";
                    $this->dbF->setRow($sql);
                    //$this->functions->setlog('Product Sale','Inventory',$invProductId,'Stock Deduct,StockId '.$invProductId.' :  QTY:'.$saleQTY,$transection);
                }else{
                    echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock Error stock not found for process OR stock QTY error, Please check"]),"btn-danger");
                    return false;
                }
            }else{
                echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock QTY is less then your Order, Please check"]),"btn-danger");
                return false;
            }
        } //foreach
    }

    public function stockDeductFromOrder($orderId,$transection=true){
        global $_e;

        //if unlimit stock, then just make all to process, else one by one do all
        if($this->functions->developer_setting('product_check_stock') == '0'){
            $sql = "UPDATE order_invoice_product SET order_process = '1' WHERE order_invoice_id = '$orderId'";
            $this->dbF->setRow($sql);
            return true;
        }


        $sql ="SELECT * FROM order_invoice_product WHERE order_invoice_id = '$orderId'";
        $data = $this->dbF->getRows($sql,false);
       // $this->db->beginTransaction();
        $return = false;
        foreach($data as $d){
            $invProductId   = $d['invoice_product_pk'];
            $pids           = $d['order_pIds'];
            $pids           = explode("-",$pids);
            $pId        =   $pids[0];
            $scaleId    =   $pids[1];
            $colorId    =   $pids[2];
            $storeId    =   $pids[3];
            $customId   =   $pids[4];
            @$dealId    =   $d['deal']; // if not it is 0
            @$info      =   unserialize($d['info']);

            if($customId != '0' && $scaleId == '0'){
                return true;
            }

            $saleQTY   = $d['order_pQty'];
            if($dealId == '0'){
                $return = $this->stockDeductFromOrderLoop($pId,$scaleId,$colorId,$storeId,$d);
            }else{
                foreach($info as $val){
                    $pids       = $val['pIds'];
                    $pids       = explode("-",$pids);
                    $pId        =   $pids[0];
                    $scaleId    =   $pids[1];
                    $colorId    =   $pids[2];
                    $return =  $this->stockDeductFromOrderLoop($pId,$scaleId,$colorId,$storeId,$d);
                    if($return==false){
                        break;
                    }
                }
            }

        } //foreach
        if($return==false){
            return false;
        }
        return true;
    }

    private function stockDeductFromOrderLoop($pId,$scaleId,$colorId,$storeId,$data){
        global $_e;
        $invProductId   = $data['invoice_product_pk'];
        $saleQTY        = $data['order_pQty'];
        @$dealId    =   $data['deal']; // if not it is 0
        @$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;
        $hash       =   md5($hashVal);

        $invQty =   $this->productF->stockProductQty($hash);
        if($saleQTY  <= $invQty){
            if($dealId != '0') {
                $this->productF->productDealCountPlus($dealId,$saleQTY);
            }
            $this->productF->productSaleCountPlus($pId,$saleQTY);
            if($this->productF->stockProductQtyMinus($hash,$saleQTY) ){
                $sql = "UPDATE order_invoice_product SET order_process = '1' WHERE invoice_product_pk = '$invProductId'";
                $this->dbF->setRow($sql);
                //$this->functions->setlog('Product Sale','Inventory',$invProductId,'Stock Deduct,StockId '.$invProductId.' :  QTY:'.$saleQTY,$transection);
            }else{
                echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock Error stock not found for process OR stock QTY error, Please check"]),"btn-danger");
                return false;
            }
        }else{
            echo $this->functions->notificationError(_js(_uc($_e["Stock"])),_js($_e["Stock QTY is less then your Order, Please check"]),"btn-danger");
            return false;
        }
        return true;
    }

    public function getSpecialProducts($category){

        $sql = "SELECT * FROM `categories` WHERE id = ? ";
        $catData = $this->dbF->getRow($sql, array($category));

        $catId = $catData['id'];
        $catId = $this->productF->getSubCatIds($catId); //array

        $LIKE = "";
        foreach ($catId as $val) {
            $cId = $val;
            $LIKE .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";
        }
        $LIKE = trim($LIKE, "OR");

        $sql = "SELECT `procat_prodet_id`,`prodet_id`
                        FROM `product_category`
                            JOIN
                            `proudct_detail` AS detail
                                ON `product_category`.`procat_prodet_id` = `detail`.`prodet_id`
                        WHERE $LIKE
                        GROUP BY `detail`.`prodet_id`";

        $productIds = $this->dbF->getRows($sql);

        return $productIds;
    }

    public function productData($pId)
    {
        $sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                FROM
                   `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE
                    `product_setting`.`setting_name`='publicAccess'
                    AND `product_setting`.`setting_val`='1'
                    AND `proudct_detail`.`product_update`='1'
                    AND `proudct_detail`.`prodet_id` = '$pId'";
        $data = $this->dbF->getRow($sql);
        return $data;
    }

    public function product_quantity($pid){

        $sql="SELECT SUM(`qty_item`) AS QTY, MAX(`updateTime`) as update_time FROM `product_inventory`
                WHERE `qty_product_id` = '$pid'";
        $data = $this->dbF->getRow($sql);

        $qty=$data['QTY'];
        if($qty==''){ $qty ='0'; }
        return $qty;
    }

    public function get_product($pid){
 
        $result = false;

        $sql    =   "SELECT * FROM `proudct_detail` WHERE `prodet_id` = ? AND product_update = '1' ";
        $row    =   $this->dbF->getRow($sql,array($pid));
        if( $this->dbF->rowCount > 0 ){
            $result = $row;
        }

        return $result;
    }

    public function checkStock($id){
        $sql = "SELECT `qty_item` FROM `product_inventory` WHERE `qty_product_id` = ?";
        $res = $this->dbF->getRow($sql, array($id));

        return $res['qty_item'];
    }

    public function order_status(){
        $array = array(
            "process" => "Order Placed",
            "cancelled" => "Cancelled",
            "pending_inst" => "Pending Installation",
            "live" => "Live",
            "pending_remove" => "Pending Removal"
        );

        // $array = array("Order Placed","Cancelled","Pending Installation", "Live", "Pending Removal");
        return $array;
    }

}


?>