<?php

require_once(__DIR__ . "/../../global_ajax.php"); //connection setting db


class order_ajax extends object_class
{
    public $productF; // admin/product_management/functions/
    public $product; // admin/product/classes/
    public $order_c;

    public function  __construct()
    {
        parent::__construct('3');
        //product_management functions
        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__ . "/../../product_management/functions/product_function.php");
            $this->productF = new product_function();
        }

        //product add/edit class
        if (isset($GLOBALS['product'])) $this->product = $GLOBALS['product'];
        else {
            require_once(__DIR__ . "/../../product/classes/product.class.php");
            $this->product = new product();
        }

        require_once(__DIR__ . "/order.php");
        $this->order_c = new order();

    }




    public function getOrderProductJson()
    {

        // var_dump("expression");
        $country = $_POST['country'];
        $countryData = $this->productF->productCountryId($country);
        $countryId = $countryData['cur_id'];
        $priceCode = $countryData['cur_symbol'];

        $sql = "SELECT `proudct_detail`.`prodet_id`, `proudct_detail`.`prodet_name`,`product_price`.`propri_intShipping`
            FROM `proudct_detail` join `product_price` on
                `proudct_detail`.`prodet_id`=`product_price`.`propri_prodet_id`
                where `product_price`.`propri_cur_id`='$countryId'
                ORDER BY `proudct_detail`.`prodet_id` ASC";

        $product = $this->dbF->getRows($sql);
        $defaultLang = $this->functions->AdminDefaultLanguage();
        $JSON = '[';


        if ($this->dbF->rowCount > 0) {
            $JSON2 = '';
            foreach ($product as $val) {
                $id = $val['prodet_id'];
                $name = translateFromSerialize($val['prodet_name']);

                //verify story country Product
                // $sql = "SELECT * from product_inventory
                //         WHERE qty_product_id = '$id' AND qty_store_id in
                //         (SELECT store_pk FROM `store_name` WHERE store_country = '$country')";

                // $this->dbF->getRows($sql);
                // if ($this->dbF->rowCount > 0) {

                // } else {
                //     continue;
                // }


                //scale JSON
                // $scle = $this->productF->scaleSQL($id, '`prosiz_id`,`prosiz_name`');
                // if ($this->dbF->rowCount > 0) {
                //     $SCALE = '[';
                //     $temp = '';
                //     foreach ($scle as $sval) {
                //         $sWeight = $this->productF->getProductWeight($id, $sval['prosiz_id']);
                //         $temp .= '{"id": "' . $sval['prosiz_id'] . '","label" : "' . $sval['prosiz_name'] . '", "sWeight": "' . $sWeight . '" },';
                //     }
                //     $temp = trim($temp, ',');
                //     $SCALE .= $temp;
                //     $SCALE .= ']';
                // } else {
                //     $SCALE = 'null';
                // }

                //color json
                // $colr = $this->productF->colorSQL($id, '`propri_id`,`proclr_name`');
                // if ($this->dbF->rowCount > 0) {
                //     $COLOR = '[';
                //     $temp = '';
                //     foreach ($colr as $cval) {
                //         $temp .= '{"id": "' . $cval['propri_id'] . '","label" : "' . $cval['proclr_name'] . '"},';
                //     }
                //     $temp = trim($temp, ',');
                //     $COLOR .= $temp;
                //     $COLOR .= ']';
                // } else {
                //     $COLOR = 'null';
                // }

                //JSON create
                $pSetting = $this->productF->getProductSetting($id); // Full Setting Report of data
                $weight = $this->productF->productSettingArray('defaultWeight', $pSetting, $id);
                $weight = floatval($weight);
                $JSON2 .= '{
                        "id" : "' . $id . '",
                        "label" : "' . $name . '",
                       
                       
                        "priceCode" : "' . $priceCode . '",
                        "weight" : "' . $weight . '",
                        "interShipping" : "' . $val['propri_intShipping'] . '"
                        },';
            }
            $JSON2 = trim($JSON2, ',');
            $JSON .= $JSON2;

        }
        $JSON .= ']';
        $JSON = trim($JSON);
        echo $JSON;
        /**
         * Out Put :
         * {
         * id:1,
         * label:asad,
         * scale: {id:2,label:raza},
         * color: {id:2,label:sheerazi},
         * weight:64
         * }
         */
    }

    public function getOrderProductStoreJson()
    {
        $country = $_POST['country'];
        $countryData = $this->productF->productCountryId($country);
        $countryId = $countryData['cur_id'];
        $pId = $_POST['pId'];
        $scleId = $_POST['scaleId'];
        $colorId = $_POST['colorId'];
        @$customId = $_POST['customId'];
        $sql = "SELECT * FROM `product_inventory`";
        $product = $this->dbF->getRows($sql);
        $JSON = '[';

        if ($this->dbF->rowCount > 0) {
            $JSON2 = '';
            foreach ($product as $val) {
                $storeName = $this->productF->getStoreName($val['qty_store_id']);
                $price = $this->productF->productTotalPrice($pId, $scleId, $colorId, $customId, $country);
                $discountArray = $this->productF->productDiscount($pId, $countryId);
                if (!empty($discountArray)) {
                    $discount = $discountArray['discount'];
                    $discountFormat = $discountArray['discountFormat'];
                    if ($discountFormat == 'price') {
                        // $discount   =   $price-$discount;
                    } else if ($discountFormat == 'percent') {
                        $discount = ($price * $discount) / 100;
                    }
                } else {
                    $discount = 0;
                }
                $JSON2 .= '{
                        "label" : "' . $storeName . '",
                        "id"    : "' . $val['qty_pk'] . '",
                        "storeId": "' . $val['qty_store_id'] . '",
                        "qty"   : ' . $val['qty_item'] . ',
                        "price" : "' . $price . '",
                        "discount" : "' . $discount . '"
                        },';
            }
            $JSON .= trim($JSON2, ',');
        }

        $JSON .= ']';
        $JSON = trim($JSON);
        echo $JSON;
    }

    public function finalPriceShipping()
    {
        require_once(__DIR__ . '/../../shipping/classes/shipping.php');
        $shippingC = new shipping();


        $storeCountry = $_POST['storeCountry'];
        $deliverCountry = $_POST['deliverCountry'];
        //$storeCountry = 'PK';
        //$deliverCountry = 'PK';
        $hash = "$storeCountry:$deliverCountry";

        $sql = "SELECT * FROM `shipping` WHERE hash = '$hash'";
        $data = $this->dbF->getRow($sql);
        $array = array();
        if ($this->dbF->rowCount > 0) {
            $array['find'] = "1"; // Found
            $array['shp_int'] = $data['shp_int'];
            $weight = $shippingC->shpWeightArrayFind($data['shp_weight']);
            $array['shp_weight'] = floatval($weight);
            $array['shp_price'] = $data['shp_price'];
        } else {
            $array['find'] = "0"; // Not Found
            $array['message'] = "N0 Shipping Date Found";
        }
        echo json_encode($array);

    }

    public function delOrder()
    {
        try {
            $this->db->beginTransaction();

            $id = $_POST['itemId'];
            $arrayy = array($id);

            $sql = "DELETE FROM `quote` WHERE `qId`=?";
            $data = $this->dbF->setRow($sql,$arrayy);

            $this->db->commit();
            $this->functions->setlog('DELETE', 'Quote', $id, 'Quote Delete Successfully');
            echo 1;
        } catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function order_fetch($page)
    {
        global $_e;
        $start  = (isset($_POST['start']))  ? $_POST['start']               : 0;
        $length = (isset($_POST['length'])) ? $_POST['length']              : 10;
        $draw   = (isset($_POST['draw']))   ? (int)$_POST['draw']           : null;
        $search = (isset($_POST['search']) && $_POST['search'] != '') ? ($_POST['search']['value'])   : null;
        $order  = (isset($_POST['order']))  ? $_POST['order'][0]            : null;

        $order_by_sql = ' ORDER BY order_invoice_pk DESC ';
        if ( $order ) {
            # order by sql generation
            // $columns_array      = array('SNO','INVOICE','Country','INVOICE DATE','CUSTOMER NAME','SOLD PRICE','PAYMENT METHOD','ORDER PROCESS','Invoice Status');
            $order_by           = ($order['column']);
            $order_by_direction = strtoupper($order['dir']);

            switch ($order_by) {
                case '0':
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '1':
                    # INVOICE...
                    $order_by_sql = ' ORDER BY invoice_id ' . $order_by_direction;
                    break;
                case '2':
                    # Country...
                    $order_by_sql = ' ORDER BY shippingCountry ' . $order_by_direction;
                    break;
                case '3':
                    # INVOICE DATE...
                    $order_by_sql = ' ORDER BY invoice_date ' . $order_by_direction;
                    break;
                case '4':
                    # CUSTOMER NAME...
                    $order_by_sql = ' ORDER BY ac.acc_name ' . $order_by_direction;
                    break;
                case '5':
                    # SOLD PRICE...
                    $order_by_sql = ' ORDER BY total_price ' . $order_by_direction;
                    break;
                case '6':
                    # PAYMENT METHOD...
                    $order_by_sql = ' ORDER BY paymentType ' . $order_by_direction;
                    break;
                case '7':
                    # ORDER PROCESS... CANNOT DO THIS CURRENTLY, BECAUSE THIS COMES FROM ORDER_INVOICE_PRODUCT AND CAN BE MULTIPLE
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
                case '8':
                    # Invoice Status...
                    $order_by_sql = ' ORDER BY invoice_status ' . $order_by_direction;
                    break;
                
                default:
                    # SNO...
                    $order_by_sql = ' ORDER BY order_invoice_pk ' . $order_by_direction;
                    break;
            }

        }



        ##### ADDITIONAL CUSTOM FILTER FILEDS #####
        $dateCodeFrom = (isset($_POST['dateCodeFrom']) && $_POST['dateCodeFrom'] != '' )  ? $_POST['dateCodeFrom'] . ' 00:00:00 '  : NULL;
        $dateCodeTo   = (isset($_POST['dateCodeTo']) && $_POST['dateCodeTo'] != '' )    ? $_POST['dateCodeTo'] . ' 23:59:59 '    : NULL;

        ## make between sql for date
        $between_sql = ( isset($dateCodeFrom) && isset($dateCodeTo) ) ? " `dateTime` BETWEEN '${dateCodeFrom}' AND '${dateCodeTo}' AND " : '' ;

        ## if date range filter is applied then apply its date order by sql
        ## if order is null, and date range is not empty then use between sql order by , else use order by of the datatable column selected
        $order_by_sql = ( !$order && $between_sql != '' ) ? ' ORDER BY `dateTime` ASC ' : $order_by_sql;


        #### Search SQL #####
        $country = $this->functions->countryKeyByName($search);
        $country = !empty($country) ? " `shippingCountry` = '{$country}' OR " : "";
        if ($search) {
            $search_sql = " ( `invoice_id` LIKE '%{$search}%'               OR
                                        $country
                                        `orderUser` = '{$search}'         OR
                                        `invoice_date` LIKE '%{$search}%' OR
                                        `orderStatus`  LIKE '%{$search}%' OR 
                                        `total_price`  LIKE '%{$search}%' OR
                                         ac.acc_name   LIKE '%{$search}%' ) AND";
        } else {
            $search_sql = '';
        }

        //############# GET TOTAL ROWS #############
        $search_w = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';


        ## DATE RANGE SQL
        // $between_sql = $search_sql == '' ? $between_sql : $between_sql;
        ## make between sql for date
        $between_sql = ( isset($dateCodeFrom) && isset($dateCodeTo) ) ? " `dateTime` BETWEEN '${dateCodeFrom}' AND '${dateCodeTo}' AND " : '' ;

        switch ($page) {
            case 'data_ajax_complete':
                $order_name = "complete";

                $sql = " SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                WHERE {$search_sql} {$between_sql} invoice_status = '3' {$order_by_sql} ";

                // $sql = " SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //         LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser` 
                //         WHERE {$search_sql} {$between_sql} invoice_status = '3' {$order_by_sql} ";

                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);

                $sql .= " LIMIT $start,$length ";
                break;
            case 'data_ajax_invoices':
                $order_name = "invoices";

                # now added user name searching in all
                // # specific search sql, adding user name searching by joining user account table
                // $search_sql = ($search) ? trim($search_sql, ' AND') : '';
                // $search_sql = ($search) ? $search_sql . " OR ac.acc_name LIKE '%{$search}%' AND" : $search_sql;

                $sql = " SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        WHERE {$search_sql} {$between_sql} orderStatus != 'inComplete' AND invoice_status != '3' AND invoice_status != '0' {$order_by_sql} ";

                // $sql = " SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //          LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser` 
                //          WHERE {$search_sql} {$between_sql} orderStatus != 'inComplete' AND invoice_status != '3' AND invoice_status != '0' {$order_by_sql} ";

                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);

                $sql .= " LIMIT $start,$length ";
                break;
            case 'data_ajax_cancel':
                $order_name = "cancel";
                # doing this, because we are changing the search sql, for cancelled orders.
                $search_sql  = trim($search_sql, 'AND');
                $search_sql  = ( isset($search_sql) && $search_sql != '' ) ? "  AND {$search_sql} " : '';
                // $between_sql = ( $search_sql == '' && $between_sql != ''  ) ? ' AND ' . rtrim($between_sql,' AND ') : rtrim($between_sql,' AND ');
                // if ( $search_sql == '' && $between_sql != '' ) {
                    // $between_sql = rtrim($between_sql,' AND ');
                // } elseif () {
                    $between_sql = rtrim($between_sql,' AND ');
                // }
                    
                    if ( $between_sql != '' ) {
                        $between_sql = ' AND ' . $between_sql;
                    }
                $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        WHERE invoice_status = '0' {$search_sql} {$between_sql} {$order_by_sql} ";

                // $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //         LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser`
                //         WHERE invoice_status = '0' {$search_sql} {$between_sql} {$order_by_sql} ";

                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);

                $sql .= " LIMIT $start,$length ";
                break;
            case 'data_ajax_incomplete':
                $order_name = "incomplete";
                $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id  
                        WHERE {$search_sql} {$between_sql} orderStatus = 'inComplete' {$order_by_sql} ";

                // $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //         LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser` 
                //         WHERE {$search_sql} {$between_sql} orderStatus = 'inComplete' {$order_by_sql} ";

                ############# GET TOTAL ROWS #############
                $recordsTotal = $this->get_total_rows($sql);
                // $recordsTotal = 2;

                $sql .= " LIMIT $start,$length ";
                break;
            default: //all
                $order_name = "all";
                
                # adding between sql with $search_w
                //$search_w = ( $search_w == '' && $between_sql != '' ) ? ' WHERE ' . rtrim($between_sql, 'AND ') : $search_w . ' AND ' . rtrim($between_sql, 'AND ');
                # if no between sql then remove the AND which gets appended everytime before between_sql
                //$search_w = ( $between_sql == '' ) ? str_replace(' AND ','',$search_w) : $search_w;

                $sql = "SELECT * FROM `quote` ORDER BY `qId` DESC";
                
                // $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //         LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                //         LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                //         $search_w {$order_by_sql} "; 

                // $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //         LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser` 
                //         $search_w {$order_by_sql} ";        

                ############# GET TOTAL ROWS #############
                // $sql_two = "SELECT `order_invoice_pk` FROM `order_invoice` " . $search_w;
                $recordsTotal = $this->get_total_rows($sql);

                //$sql .= " LIMIT $start,$length ";
                break;
        }

        // ###### Get Data ####
        // $sql2 = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
        //          LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser` 
        //          WHERE  `order_invoice`.`dateTime` BETWEEN '2016-10-1 00:00:00' AND '2016-10-29 23:59:59'  ORDER BY `order_invoice`.`dateTime` ASC";

        $data = $this->dbF->getRows($sql);

        $columns = array();
        if ($draw == 1) {
            $draw - 1;
        }

        $columns["draw"] = $draw + 1;
        $columns["recordsTotal"] = $recordsTotal; //total record,
        $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear

        $i = $start;
        foreach ($data as $key => $val) {
            $i++;
            $divInvoice = '';
            $quote_id = $val['qId'];
            

            $action = "<div class='btn-group btn-group-sm'>
                        <a href='?pId=$quote_id' data-method='post' data-action='?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a><a class='btn'>
                         <i class='glyphicon glyphicon-trash '></i>
                     </a></div>";

        

            //10 columns
            $columns["data"][$key] = array(
                $i,
                $val['qName'],
                $val['qEmail'],
                $val['qFile'],
                $val['qDate'],
                $action
            );

        }
        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }
        //Jason Encode
        //echo json_encode($columns);
        //echo $recordsTotal;
        echo "<pre>"; print_r($columns);
        //print_r($data);
    }

    protected function get_total_rows($sql, $search_sql = '')
    {
        $search_w = !empty($search_sql) ? " WHERE " . trim($search_sql, "AND") : '';
        $sql  = $sql . ' ' . $search_w;
        $data = $this->dbF->getRows($sql);
        return $recordsTotal = $this->dbF->rowCount;
    }


    public function quick_invoice_update()
    {
        if (!empty($_POST["orderid"]) && isset($_POST["invoice"])) {
            $order_id = $_POST["orderid"];
            $id = $order_id;
            $invoice_id = $_POST["invoice"];
            $inv = $invoice_id;

            $sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id'";
            $dataTrans = $this->dbF->getRow($sql);

            $paymentType = $dataTrans['paymentType'];
            $paymentInfo = $dataTrans['payment_info'];
            $_POST['trackNo'] = $dataTrans['trackNo'];

            if (($inv == '0' || $inv == '3' || $inv == '6')) {
                $sql = "SELECT inTransaction,rsvNo,rsvNo_done FROM `order_invoice` WHERE order_invoice_pk = '$id' AND inTransaction!=''";
                $dataTrans = $this->dbF->getRow($sql);

                if ( $this->dbF->rowCount > 0 && ($paymentType == '2') ) {
                    $rsvNo = $dataTrans['rsvNo'];
                    $rsvNo_done = $dataTrans['rsvNo_done'];
                    $inTransaction = trim($dataTrans['inTransaction']);
                    /* ------- ---------- KLARNA ------------- ------------ */
                    $klarnaReturn = $this->handelKlarna($id, $inTransaction, $inv, $paymentType, $rsvNo, $rsvNo_done);
                    $returnKlarna = $klarnaReturn;
                    /* ------- ----------KLARNA End------------- ------------ */
                    $paymentInfo = $paymentInfo . "\n $returnKlarna";
                }
            }

            $sql = "UPDATE `order_invoice` SET invoice_status = ?,payment_info = ?  WHERE order_invoice_pk = ? ";
            $this->dbF->setRow($sql, array($invoice_id, $paymentInfo, $order_id));

            if ($this->dbF->rowCount > 0) {
                echo "1";
                {
                    $sql = "SELECT * FROM `order_invoice_info` WHERE order_invoice_id = '$id'";
                    $data_info = $this->dbF->getRow($sql);

                    $link       = WEB_URL."/viewOrder?view=$id&orderId=".$this->functions->encode($id);
                    $invStatus  =   $this->productF->invoiceStatusFind($inv);

                    $to         =  $data_info['sender_email'];
                    $invoice    =   $this->functions->ibms_setting('invoice_key_start_with');
                    $mailArray['link']        =   $link;
                    $mailArray['invoiceStatus'] =   $invStatus;
                    $mailArray['invoiceNumber'] =   $invoice."".$id;
                    $mailArray["other"]['shippingNumber'] =  $_POST['trackNo'];
                    $this->functions->send_mail($to,'','','orderUpdate','',$mailArray);
                }
            } else {
                echo "0";
            }
        }
    }

    public function handelKlarna($orderId,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done){
        //All work will Handel Accordingly
        $this->functions->require_once_custom('Class.myKlarna.php');
        $klarnaClass    = new myKlarna();
        return $klarnaClass->klarnaInvoices($orderId,$inTransaction,$inv,$paymentType,$rsvNo,$rsvNo_done);
    }

}

?>