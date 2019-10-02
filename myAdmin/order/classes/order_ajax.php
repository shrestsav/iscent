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
                $sql = "SELECT * from product_inventory
                        WHERE qty_product_id = '$id' AND qty_store_id in
                        (SELECT store_pk FROM `store_name` WHERE store_country = '$country')";

                $this->dbF->getRows($sql);
                if ($this->dbF->rowCount > 0) {
                } else {
                    continue;
                }


                //scale JSON
                $scle = $this->productF->scaleSQL($id, '`prosiz_id`,`prosiz_name`');
                if ($this->dbF->rowCount > 0) {
                    $SCALE = '[';
                    $temp = '';
                    foreach ($scle as $sval) {
                        $sWeight = $this->productF->getProductWeight($id, $sval['prosiz_id']);
                        $temp .= '{"id": "' . $sval['prosiz_id'] . '","label" : "' . $sval['prosiz_name'] . '", "sWeight": "' . $sWeight . '" },';
                    }
                    $temp = trim($temp, ',');
                    $SCALE .= $temp;
                    $SCALE .= ']';
                } else {
                    $SCALE = 'null';
                }

                //color json
                $colr = $this->productF->colorSQL($id, '`propri_id`,`proclr_name`');
                if ($this->dbF->rowCount > 0) {
                    $COLOR = '[';
                    $temp = '';
                    foreach ($colr as $cval) {
                        $temp .= '{"id": "' . $cval['propri_id'] . '","label" : "' . $cval['proclr_name'] . '"},';
                    }
                    $temp = trim($temp, ',');
                    $COLOR .= $temp;
                    $COLOR .= ']';
                } else {
                    $COLOR = 'null';
                }

                //JSON create
                $pSetting = $this->productF->getProductSetting($id); // Full Setting Report of data
                $weight = $this->productF->productSettingArray('defaultWeight', $pSetting, $id);
                $weight = floatval($weight);
                $JSON2 .= '{
                        "id" : "' . $id . '",
                        "label" : "' . $name . '",
                        "scale" : ' . $SCALE . ',
                        "color" : ' . $COLOR . ',
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
        $sql = "SELECT * FROM `product_inventory` WHERE `qty_product_id` = '$pId' AND `qty_product_scale` = '$scleId' AND `qty_product_color` = '$colorId'";
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

            $sql = "SELECT * FROM `order_invoice_product` WHERE  order_invoice_id='$id'";
            $oldData = $this->dbF->getRows($sql);
            foreach ($oldData as $val) {
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
            }

          $sql2 = "DELETE FROM orders WHERE order_id='$id'";
$sql2i = "DELETE FROM order_detail WHERE order_id='$id'";
$this->dbF->setRow($sql2i, false);
$this->dbF->setRow($sql2, false);
if ($this->dbF->rowCount) echo '1';
else echo '0';

            $this->db->commit();
            $this->functions->setlog('DELETE', 'Order Invoice', $id, 'Order Invoice Delete Successfully');
        } catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

public function deldeleteScheduleForm()
{
try {
$this->db->beginTransaction();

$id = $_POST['itemId'];

// var_dump($id);
// exit();


$sql2i = "DELETE FROM schedule_form WHERE schedule_id='$id'";
$this->dbF->setRow($sql2i, false);
if ($this->dbF->rowCount) echo '1';
else echo '0';

$this->db->commit();
$this->functions->setlog('DELETE', 'SCHEDULE', $id, 'SCHEDULE Delete Successfully');
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
                $search_w = ( $search_w == '' && $between_sql != '' ) ? ' WHERE ' . rtrim($between_sql, 'AND ') : $search_w . ' AND ' . rtrim($between_sql, 'AND ');
                # if no between sql then remove the AND which gets appended everytime before between_sql
                $search_w = ( $between_sql == '' ) ? str_replace(' AND ','',$search_w) : $search_w;
                
                $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                        LEFT OUTER JOIN `temp_accounts_user` tau ON tau.acc_id_str = `order_invoice`.`orderUser`
                        LEFT OUTER JOIN `accounts_user` ac       ON ac.acc_id = tau.acc_id 
                        $search_w {$order_by_sql} "; 

                // $sql = "SELECT ac.acc_id,ac.acc_name,ac.acc_email,order_invoice.* FROM `order_invoice`
                //         LEFT OUTER JOIN `accounts_user` ac ON CAST(ac.acc_id as CHAR(12)) = `order_invoice`.`orderUser` 
                //         $search_w {$order_by_sql} ";        

                ############# GET TOTAL ROWS #############
                // $sql_two = "SELECT `order_invoice_pk` FROM `order_invoice` " . $search_w;
                $recordsTotal = $this->get_total_rows($sql);

                $sql .= " LIMIT $start,$length ";
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
            $invoiceStatus = $this->productF->invoiceStatusFind($val['invoice_status']);
            $st = $val['invoice_status'];
            $onclick = " onclick= 'show_quick_invoice(this);' ";
            if ($st == 0) $divInvoice = "<div $onclick class='btn invoice_status btn-danger  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 1) $divInvoice = "<div $onclick class='btn invoice_status btn-warning  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 2) $divInvoice = "<div $onclick class='btn invoice_status btn-info  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else if ($st == 3) $divInvoice = "<div $onclick class='btn invoice_status btn-success  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";
            else $divInvoice = "<div $onclick class='btn invoice_status btn-default  btn-sm' style='min-width:80px;'>$invoiceStatus</div>";


            $invoiceDate = date('Y-m-d H:i:s', strtotime($val['invoice_date']));
            $invoiceId = $val['order_invoice_pk'];

            $country = $val['shippingCountry'];
            $country = $this->functions->countryFullName($country);

            $orderInfo = $this->order_c->orderInvoiceInfo($invoiceId);
            $orderUser_id = $val['orderUser'];
            $customer_Name = $orderInfo['sender_name'];
            if (is_numeric($orderUser_id)) {
                $customer_Name = empty($customer_Name) ? "---" : $customer_Name;
                $customer_Name = "<a href='-webUsers?page=edit&userId=$orderUser_id' class='btn btn-info btn-sm' target='_blank'>$customer_Name</a>";
            }

            //Check order process or not,, if single product process it show 1
            $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '1'";
            $this->dbF->getRow($sql);
            $orderProcess = "<div class='btn btn-danger  btn-sm' style='width:50px;'>" . _uc($_e['NO']) . "</div>";
            if ($this->dbF->rowCount > 0) {
                //make sure all order process or custome process
                $sql = "SELECT * FROM `order_invoice_product` WHERE `order_invoice_id` = '$invoiceId' AND `order_process` = '0' ";
                $this->dbF->getRow($sql);
                if ($this->dbF->rowCount > 0) {
                    //Ja = yes
                    $orderProcess = "<div class='btn btn-warning  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                } else {
                    $orderProcess = "<div class='btn btn-success  btn-sm' style='width:50px;'>" . _uc($_e['Yes']) . "</div>";
                }
            }

            $days = $this->functions->ibms_setting('order_invoice_deleteOn_request_after_days');
            $link = $this->functions->getLinkFolder();
            $date = date('Y-m-d', strtotime($val['dateTime']));
            $minusDays = date('Y-m-d', strtotime("-$days days"));

            $inoivcePdf = '';
            if ($val['orderStatus'] != 'inComplete') {
                $inoivcePdf = " <a href='../invoicePrint?mailId=$invoiceId' target='_blank' class='btn'>
                                    <i class='fa fa-file-pdf-o'></i>
                               </a>";
            }

            $paymentMethod = $val['paymentType'];
            $paymentMethod = $this->productF->paymentArrayFind($paymentMethod);
            $cur_symbol = md5($val['price_code']);

            $action = "<div class='btn-group btn-group-sm'>
                       $inoivcePdf
                        <a href='?pId=$invoiceId' data-method='post' data-action='?page=edit' class='btn'>
                            <i class='glyphicon glyphicon-edit'></i>
                        </a>";
            if ($date < $minusDays) {
                $action .= "<a class='btn' data-id='$invoiceId' onclick='return delOrderInvoice(this);'>
                         <i class='glyphicon glyphicon-trash trash'></i>
                         <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                     </a>";
            } else {
                $action .= "<a class='btn'>
                         <i class='glyphicon glyphicon-trash '></i>
                         <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                     </a>";
            }

            $order_id = $val['order_invoice_pk'];
            $form_invoice = array();
            $form_invoice[] = array(
                "type" => "select",
                "array" => $this->productF->invoiceStatusArray(),
                "select" => $val['invoice_status'],
                "data" => 'onchange="quick_invoice_update(\'' . $order_id . '\',this);"',
                "class" => "form-control invoice_quick_select",
                "format" => "<div class='invoice_quick_select_div'>{{form}}</div>"
            );
            $invoice_status = $this->functions->print_form($form_invoice, "", false);

            //10 columns
            $count_me = "<span  class='countMe_{$order_name}_{$cur_symbol}'>$val[total_price]</span> $val[price_code]";
            $columns["data"][$key] = array(
                $i,
                "$val[invoice_id]",
                $country,
                $invoiceDate,
                $customer_Name,
                $count_me,
                $paymentMethod,
                $orderProcess,
                $divInvoice . $invoice_status,
                $action
            );

        }
        if ($recordsTotal == '0') {
            $columns["data"] = array();
        }
        //Jason Encode
        echo json_encode($columns);
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
                    $adminMail = $functions->ibms_setting('Email');
                    $this->functions->send_mail($adminMail,'','','orderUpdate','',$mailArray);
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

    public function submitSceduleForm(){

        $order_id           = $_POST['order_id'];
        $product_id         = $_POST['product_id'];
        $schedule_date      = $_POST['schedule_date'];
        $schedule_slot      = $_POST['schedule_slot'];
        $sevice_type        = $_POST['sevice_type'];
        $new_machine        = (isset($_POST['new_machine'])) ? $_POST['new_machine'] : 'off';
        $oil_quantity       = json_encode($_POST['oil_quantity']);
        $schedule_oil       = json_encode($_POST['schedule_oil']);
        $schedule_machine   = json_encode($_POST['schedule_machine']);

        $sql_check = "SELECT `schedule_id` FROM `schedule_form` WHERE `schedule_date` = ? AND `schedule_slot` = ?";
        $res_check = $this->dbF->getRow($sql_check, array($schedule_date, $schedule_slot));

        if(!empty($res_check)){
            $this->dbF->prnt(array($schedule_date, $schedule_slot));
            $this->dbF->prnt($res_check);
            echo sizeof($res_check);
            echo '0';
            exit();
        }
        else{

            foreach ($_POST['schedule_machine'] as $key => $value) {
                if($new_machine[$key] == 'on'){
                    $this->deductFromStock($value);
                }
                
            }

            foreach ($_POST['schedule_oil'] as $key => $value) {
                $this->deductFromStock($value, $_POST['oil_quantity'][$key]);
            }

            $sql = "INSERT INTO `schedule_form`(
                            `schedule_date`, 
                            `schedule_slot`,
                            `service_type`,
                            `machines`, 
                            `oils`, 
                            `order_id`, 
                            `quantity`
                        ) VALUES (?,?,?,?,?,?,?)";

            $ary = array($schedule_date,$schedule_slot,$sevice_type,$schedule_machine,$schedule_oil,$order_id,$oil_quantity);
            $this->dbF->setRow($sql, $ary);

            if($this->dbF->rowCount > 0){
                echo '1';
            }else{
                echo 'abc';
            }

        }

    }

    public function deductFromStock($pId, $qty=false){

        if(!$qty){
            $qty = 1;
        }

        $sql = "UPDATE `product_inventory` 
                    SET qty_item = qty_item-$qty
                    WHERE `qty_product_id` = ?";

        $this->dbF->setRow($sql, array($pId));
    }

    public function product_quantity($pid){

        $sql="SELECT SUM(`qty_item`) AS QTY, MAX(`updateTime`) as update_time FROM `product_inventory`
                WHERE `qty_product_id` = '$pid'";
        $data = $this->dbF->getRow($sql);

        $qty=$data['QTY'];
        if($qty==''){ $qty ='0'; }
        return $qty;
    }

    public function getAvailableSlots(){
        $date = $_POST['chosen_date'];
        $slots_array = array('8AM to 11AM', '11AM to 2PM', '2PM to 5PM', '5PM to 8PM');

        $sql = "SELECT `schedule_slot` FROM `schedule_form` WHERE `schedule_date` = ?";
        $res = $this->dbF->getRows($sql, array($date));

        foreach ($res as $key => $value) {
            $slot = $value['schedule_slot'];

            if (($index = array_search($slot, $slots_array)) !== false) {
                unset($slots_array[$index]);
            }
        }
        $return_option = '<option selected disabled>Select Time Slot</option>';
        foreach ($slots_array as $row) {
             $return_option .= '<option value="'.$row.'">'._uc($row).'</option>';       
        }

        echo $return_option;
    }

    public function openTechnicalForm(){

        $schedule_id = $_POST['sched_id'];
        $order_id    = $_POST['order_id'];

        $sql = "SELECT * FROM `schedule_form` WHERE `schedule_id` = ?";
        $res = $this->dbF->getRow($sql, array($schedule_id));

        $sql_cust = "SELECT `company_name`,`fname`,`lname` FROM `order_detail` WHERE `order_id` = ?";
        $res_cust = $this->dbF->getRow($sql_cust, array($order_id));

        $name = (!empty($res_cust['company_name'])) ? $res_cust['company_name'] : $res_cust['fname'].' '.$res_cust['lname'];

        $machines = json_decode($res['machines']);
        $sf_oils  = json_decode($res['oils']);  

        $oil_array = array();
        for ($i=0; $i < sizeof($machines); $i++) { 
            $oil_data = $this->productF->get_product($sf_oils[$i]);
            $oil_name = translateFromSerialize($oil_data['prodet_name']);
            $oil_array[$machines[$i]] = $oil_name;
        }

        $system_info = '';

        foreach ($machines as $row) {
            $machine_name = $this->productF->get_product($row);
            $oil_name = $oil_array[$row];
            $machine = translateFromSerialize($machine_name['prodet_name']);
            $system_info .= '<fieldset class="form-group the-fieldset">
                                <legend class="the-legend">'.$machine.'</legend>
                                <input type="hidden" name="machine[]" value="'.$row.'">

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">Serial Number</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="" name="serial_no[]" class="form-control" placeholder="Serial Number">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">System Location</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="" name="system_location[]" class="form-control" placeholder="System Location">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">Oil</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.$oil_name.'" class="form-control" placeholder="Oil Name" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">Fragrance Intensity</label>
                                    <div class="col-sm-10  col-md-9">
                                        <select name="frag_intense[]" class="form-control">
                                            <option value="0.263">1</option>
                                            <option value="0.621">2</option>
                                            <option value="1.758">3</option>
                                            <option value="2.279">4</option>
                                            <option value="2.963">5</option>
                                            <option value="3.51">6</option>
                                            <option value="4.475">7</option>
                                            <option value="5.271">8</option>
                                            <option value="6.029">9</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-3 col-md-3  control-label">Oil Level Before(Kg)</label>
                                    <div class="col-sm-3  col-md-3">
                                        <input type="text"  name="level_before[]" class="form-control" placeholder="Oil Before">
                                    </div>

                                    <label class="col-sm-3 col-md-3  control-label">Oil Level After(Kg)</label>
                                    <div class="col-sm-3  col-md-3">
                                        <input type="text" name="level_after[]" class="form-control" placeholder="Oil After">
                                    </div>
                                </div>
                            </fieldset>';
        }


        $techincians = $this->functions->ibms_setting('technicians');
        $tech_exp = explode(',', $techincians);

        $tech_name = '<select name="technician_name" class="form-control">
                        <option selected disabled>Select Technician</option>';
        foreach ($tech_exp as $key => $value) {
            $tech_name .= '<option value="'.$value.'">'.$value.'</option>';
        }

        $tech_name .= '</select>';


        echo '<!-- Technical Form Modal -->
            <div class="modal fade" id="technicalFormModal" tabindex="-1" role="dialog" aria-labelledby="technicalFormTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" id="technicalFormTitle">Technical Form</h3>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" id="technicalForm">
                        <input type="hidden" name="order_id" value="'.$order_id.'">
                        <input type="hidden" name="schedule_id" value="'.$schedule_id.'">

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Member Name</label>
                                <div class="col-sm-10  col-md-9">
                                    <input type="text" value="'.$name.'" name="member_name" class="form-control" placeholder="Member Name" required>
                                </div>
                            </div>
                        </div>
                        '.$system_info.'
                        <div class="form-group" style="display:none;">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Home/Property Settings</label>
                                <div class="col-sm-10  col-md-9">
                                    <input type="text" value="" name="home_setting" class="form-control" placeholder="Home/Property Settings" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-3 col-md-3  control-label timepicker">Time ON</label>
                                <div class="col-sm-3  col-md-3">
                                    <select class="form-control" name="time_on">
                                        <option selected disabled>Select Time On</option>
                                        <option value="0000">00:00</option>
                                        <option value="0100">01:00</option>
                                        <option value="0200">02:00</option>
                                        <option value="0300">03:00</option>
                                        <option value="0400">04:00</option>
                                        <option value="0500">05:00</option>
                                        <option value="0600">06:00</option>
                                        <option value="0700">07:00</option>
                                        <option value="0800">08:00</option>
                                        <option value="0900">09:00</option>
                                        <option value="1000">10:00</option>
                                        <option value="1100">11:00</option>
                                        <option value="1200">12:00</option>
                                        <option value="1300">13:00</option>
                                        <option value="1400">14:00</option>
                                        <option value="1500">15:00</option>
                                        <option value="1600">16:00</option>
                                        <option value="1700">17:00</option>
                                        <option value="1800">18:00</option>
                                        <option value="1900">19:00</option>
                                        <option value="2000">20:00</option>
                                        <option value="2100">21:00</option>
                                        <option value="2200">22:00</option>
                                        <option value="2300">23:00</option>
                                        <option value="2400">24:00</option>
                                    </select>
                                </div>

                                <label class="col-sm-3 col-md-3  control-label">Time OFF</label>
                                <div class="col-sm-3  col-md-3">
                                    <select class="form-control" name="time_off">
                                        <option selected disabled>Select Time Off</option>
                                        <option value="0000">00:00</option>
                                        <option value="0100">01:00</option>
                                        <option value="0200">02:00</option>
                                        <option value="0300">03:00</option>
                                        <option value="0400">04:00</option>
                                        <option value="0500">05:00</option>
                                        <option value="0600">06:00</option>
                                        <option value="0700">07:00</option>
                                        <option value="0800">08:00</option>
                                        <option value="0900">09:00</option>
                                        <option value="1000">10:00</option>
                                        <option value="1100">11:00</option>
                                        <option value="1200">12:00</option>
                                        <option value="1300">13:00</option>
                                        <option value="1400">14:00</option>
                                        <option value="1500">15:00</option>
                                        <option value="1600">16:00</option>
                                        <option value="1700">17:00</option>
                                        <option value="1800">18:00</option>
                                        <option value="1900">19:00</option>
                                        <option value="2000">20:00</option>
                                        <option value="2100">21:00</option>
                                        <option value="2200">22:00</option>
                                        <option value="2300">23:00</option>
                                        <option value="2400">24:00</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-3 col-md-3  control-label">Daily Hours</label>
                                <div class="col-sm-3  col-md-3">
                                    <input type="text"  name="daily_hours" class="form-control" placeholder="Hours per Day">
                                </div>

                                <label class="col-sm-3 col-md-3  control-label">Days in Month</label>
                                <div class="col-sm-3  col-md-3">
                                    <input type="text" name="month_days" class="form-control" placeholder="Days in Month">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Clock Time Set</label>
                                <div class="col-sm-10  col-md-9">
                                    <select name="clockTime_set" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Pwr Mode (Time ON)</label>
                                <div class="col-sm-10  col-md-9">
                                    <select name="pwr_mode" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">HVAC Fan Set to ON (Winter)</label>
                                <div class="col-sm-10  col-md-9">
                                    <select name="hvac_fan" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Additional Comments</label>
                                <div class="col-sm-10  col-md-9">
                                    <textarea name="comments" placeholder="Additional Comments" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Technician\'s Name</label>
                                <div class="col-sm-10  col-md-9">
                                '.$tech_name.'
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <!-- <label class="col-sm-2 col-md-3  control-label">&nbsp;</label>
                                <div class="col-sm-10  col-md-9"> -->
                                    <input type="checkbox" name="complete" value="1"/> By ticking the box you confirm that the installation/services has been completed.
                               <!-- </div>
                            </div>-->
                        </div>

                        <div class="form-group">
                          <div class="col-sm-12 col-md-12 marginBot">
                            <label class="col-sm-2 col-md-3  control-label">Signature</label>
                                <div class="col-sm-10  col-md-9">
                                    <div id="signatureparent">
                                        <div id="signature"></div>
                                    </div>
                                    <input type="hidden" name="signature" id="signature_input" />
                                </div>
                            </div>
                        </div>
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitTechnicalForm">Save</button>
                  </div>
                </div>
              </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    var $sigdiv = $("#signature").jSignature({"UndoButton":true})
                    $("#submitTechnicalForm").on("click", function(){

                        var datapair = $sigdiv.jSignature("getData", "svgbase64");
                        sign_data = "data:" + datapair.join(",");
                        $("#signature_input").val(sign_data);

                        technical_form = $("#technicalForm").serialize();

                        $.ajax({
                            url: "order/order_ajax.php?page=submitTechnicalForm",
                            type: "post",
                            data: technical_form
                        }).done(function(res){
                            console.log(res);
                            parse_result = JSON.parse(res);
                            console.log(parse_result);
                            if(parse_result.next_date != "0"){
                                $("#technicalFormModal").modal("hide");
                                $("#schedule_date").val(parse_result.next_date);
                                $("#schedule_form_modalTitle").html("Next Schedule Form");
                                $("#schedule_form_modal").modal("show");
                                availableSlots(parse_result.next_date);
                            }else{
                                jAlertifyAlert("Something Went Wrong! Please Try Again.");
                            }
                            
                        });
                    });
                });
            </script>

            ';
    }

    public function submitTechnicalForm(){

        $order_id       = $_POST['order_id'];
        $schedule_id    = $_POST['schedule_id'];
        $member_name    = $_POST['member_name'];
        $home_setting   = isset($_POST['home_setting']) ? $_POST['home_setting']: '';
        $time_on        = $_POST['time_on'];
        $time_off       = $_POST['time_off'];
        $daily_hours    = $_POST['daily_hours'];
        $month_days     = $_POST['month_days'];
        $clockTime_set  = $_POST['clockTime_set'];
        $pwr_mode       = $_POST['pwr_mode'];
        $hvac_fan       = $_POST['hvac_fan'];
        $frag_intense   = $_POST['frag_intense'];
        $comments       = $_POST['comments'];
        $technician_name = $_POST['technician_name'];
        $machine        = $_POST['machine'];
        $serial_no      = $_POST['serial_no'];
        $system_location = $_POST['system_location'];
        $level_before   = $_POST['level_before'];
        $level_after    = $_POST['level_after'];
        $signature      = $_POST['signature'];
        $complete       = isset($_POST['complete']) ? $_POST['complete'] : 0;

        $remain_oil_buffer = $this->functions->ibms_setting('remain_oil_buffer');

        $minDays = 0;
        foreach ($machine as $key => $value) {
            $oil_after = $level_after[$key];
            $cur_level = $oil_after-$remain_oil_buffer;
            $cons_per_day = $frag_intense[$key]*$daily_hours;

            $noDays = ($cur_level/$cons_per_day);
            $noDays = ceil($noDays);

            if($minDays == 0){
                $minDays = $noDays;
            }

            if($minDays > $noDays){
                $minDays = $noDays;
            }
        }
        $cur_date = date('Y-m-d');
        $next_date = date('Y-m-d', strtotime("+$minDays days $cur_date"));

        $sql = "INSERT INTO `technical_form`( 
                        `order_id`, 
                        `schedule_id`, 
                        `member_name`, 
                        `home_setting`, 
                        `time_on`, 
                        `time_off`, 
                        `month_days`, 
                        `hour_per_day`, 
                        `clockTime_set`, 
                        `pwr_mode`, 
                        `hvac_fan`, 
                        `comments`, 
                        `technician_name`,
                        `signature`,
                         `complete`,
                        `client_confirm`
                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $array = array($order_id,$schedule_id,$member_name,$home_setting,$time_on,$time_off,$month_days,$daily_hours,$clockTime_set,$pwr_mode,$hvac_fan,$comments,$technician_name,$signature,$complete,"1");           
        $res = $this->dbF->setRow($sql, $array);
        $technical_id = $this->dbF->rowLastId;

        if($this->dbF->rowCount > 0){

            foreach ($machine as $key => $value) {

                $s_no       = $serial_no[$key];
                $s_location = $system_location[$key];
                $f_intense  = $frag_intense[$key];
                $o_before   = $level_before[$key];
                $o_after    = $level_after[$key];

                $sql = "INSERT INTO `technical_detail`( 
                                `technical_id`, 
                                `machine`, 
                                `serial_no`, 
                                `sys_location`, 
                                `fragrance_intensity`, 
                                `oil_before`, 
                                `oil_after`
                            ) VALUES (?,?,?,?,?,?,?)";

                $det_array = array($technical_id,$value,$s_no,$s_location,$f_intense,$o_before,$o_after);
                $res = $this->dbF->setRow($sql, $det_array);

            }

            if($complete){

                $sql_up = "UPDATE `schedule_form` SET `sched_complete`= 1 WHERE `schedule_id` = ?";
                $this->dbF->setRow($sql_up, array($schedule_id));

                $sql_det = "SELECT `company_name`, `email` FROM `order_detail` WHERE `order_id` = ?";
                $res_det = $this->dbF->getRow($sql_det, array($order_id));

                $user_email = $res_det['email'];
                $userName = $res_det['company_name'];

                $invoice_no = $this->functions->ibms_setting('invoice_key_start_with').$order_id;

                $mailArray['fromName']    =   $userName;
                $mailArray['invoiceNumber']    =   $invoice_no;

$pArray = explode(" ", $userName); 

$cname = $pArray[0];



$email_send = $this->functions->send_mail($user_email,"","","serviceComplete",$cname,$mailArray, false);

                $adminMail = $this->functions->ibms_setting('Email');
$admin_email = $this->functions->send_mail($adminMail,"","","serviceComplete",$cname,$mailArray, false);

            }

            $ret_array['next_date'] = $next_date;

        }else{
            $ret_array['next_date'] = 0;

        }

        echo json_encode($ret_array);

    }

    public function viewTechnicalForm(){
        $tech_id = $_POST['tech_id'];

        $sql = "SELECT * FROM `technical_form` WHERE `technical_id` = ?";
        $res = $this->dbF->getRow($sql, array($tech_id));

        $schedule_id = $res['schedule_id'];

        $sql_sf = "SELECT `machines`, `oils` FROM `schedule_form` WHERE `schedule_id` = ?";
        $res_sf = $this->dbF->getRow($sql_sf, array($schedule_id));

        $sf_machines = json_decode($res_sf['machines']);
        $sf_oils     = json_decode($res_sf['oils']);        

        $oil_array = array();
        for ($i=0; $i < sizeof($sf_machines); $i++) { 
            $oil_data = $this->productF->get_product($sf_oils[$i]);
            $oil_name = translateFromSerialize($oil_data['prodet_name']);
            $oil_array[$sf_machines[$i]] = $oil_name;
        }

        $sql_det = "SELECT * FROM `technical_detail` WHERE `technical_id` = ?";
        $res_det = $this->dbF->getRows($sql_det, array($tech_id));

        $system_info = '';

        foreach ($res_det as $key => $value) {

            $serial_no              = $value['serial_no'];
            $sys_location           = $value['sys_location'];
            $fragrance_intensity    = $value['fragrance_intensity'];
            $oil_before             = $value['oil_before'];
            $oil_after              = $value['oil_after'];

            $fragr_intense = array('0.263','0.621','1.758','2.279','2.963','3.51','4.475','5.271','6.029');

            $frg_cnt = 0;
            $frg_option = '';
            foreach ($fragr_intense as $row) {
                $frg_cnt++;
                $selected = ($fragrance_intensity == $row) ? 'selected': '';
                $frg_option .= '<option value="'.$row.'" '.$selected.'>'.$frg_cnt.'</option>';

            }

            $machine_name = $this->productF->get_product($value['machine']);
            $machine = translateFromSerialize($machine_name['prodet_name']);

            $oil_name = $oil_array[$value['machine']];

            $system_info .= '<fieldset class="form-group the-fieldset">
                                <legend class="the-legend">'.$machine.'</legend>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">Serial Number</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.$serial_no.'" name="serial_no[]" class="form-control" placeholder="Serial Number">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">System Location</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.$sys_location.'" name="system_location[]" class="form-control" placeholder="System Location">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">Oil</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.$oil_name.'" class="form-control" placeholder="Oil Name" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-2 col-md-3  control-label">Fragrance Intensity</label>
                                    <div class="col-sm-10  col-md-9">
                                        <select name="frag_intense[]" class="form-control" id="frag_intense">
                                        '.$frg_option.'
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 marginBot">
                                    <label class="col-sm-3 col-md-3  control-label">Oil Level Before(Kg)</label>
                                    <div class="col-sm-3  col-md-3">
                                        <input type="text" name="level_before[]" value="'.$oil_before.'" class="form-control" placeholder="Oil Before">
                                    </div>

                                    <label class="col-sm-3 col-md-3  control-label">Oil Level After(Kg)</label>
                                    <div class="col-sm-3  col-md-3">
                                        <input type="text" name="level_after[]" value="'.$oil_after.'" class="form-control" placeholder="Oil After">
                                    </div>
                                </div>
                            </fieldset>';
        }

        $member_name        = $res['member_name'];
        $home_setting       = $res['home_setting'];
        $time_on            = $res['time_on'];
        $time_off           = $res['time_off'];
        $month_days         = $res['month_days'];
        $hour_per_day       = $res['hour_per_day'];
        $clockTime_set      = $res['clockTime_set'];
        $pwr_mode           = $res['pwr_mode'];
        $hvac_fan           = $res['hvac_fan'];
        $comments           = $res['comments'];
        $technician_name    = $res['technician_name'];
        $signature          = $res['signature'];

        $true_time_set = '';
        $false_time_set = '';

        if($clockTime_set == '1'){
            $true_time_set = 'selected';
        }else{
            $false_time_set = 'selected';
        }

        $true_pwr_mode = '';
        $false_pwr_mode = '';

        if($pwr_mode == '1'){
            $true_pwr_mode = 'selected';
        }else{
            $false_pwr_mode = 'selected';
        }

        $true_hvac_fan = '';
        $false_hvac_fan = '';

        if($hvac_fan == '1'){
            $true_hvac_fan = 'selected';
        }else{
            $false_hvac_fan = 'selected';
        }

        $timeon_array = array('0000','0100','0200','0300','0400','0500','0600','0700','0800','0900','1000','1100','1200','1300','1400','1500','1600','1700','1800','1900','2000','2100','2200','2300','2400');

        $timeon_drop = '';
        foreach ($timeon_array as $key => $value) {
            $ab = str_split($value, 2);
            $val = join(':',$ab);

            $selected_on = ($time_on == $value) ? 'selected' : '';

            $timeon_drop .= '<option value='.$value.' '.$selected_on.'>'.$val.'</option>';
        }

        $timeoff_array = array('0000','0100','0200','0300','0400','0500','0600','0700','0800','0900','1000','1100','1200','1300','1400','1500','1600','1700','1800','1900','2000','2100','2200','2300','2400');

        $timeoff_drop = '';
        foreach ($timeoff_array as $key => $value) {
            $ab = str_split($value, 2);
            $val = join(':',$ab);

            $selected_off = ($time_off == $value) ? 'selected' : '';

            $timeoff_drop .= '<option value='.$value.' '.$selected_off.'>'.$val.'</option>';
        }

        echo '<!-- Technical Form Modal -->
            <div class="modal fade" id="technicalFormModal" tabindex="-1" role="dialog" aria-labelledby="technicalFormTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" id="technicalFormTitle">Technical Form</h3>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" id="technicalForm">

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Member Name</label>
                                <div class="col-sm-10  col-md-9">
                                    <input type="text" value="'.$member_name.'" name="member_name" class="form-control" placeholder="Member Name" required>
                                </div>
                            </div>
                        </div>
                        '.$system_info.'
                        <div class="form-group" style="display:none;">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Home/Property Settings</label>
                                <div class="col-sm-10  col-md-9">
                                    <input type="text" value="'.$home_setting.'" name="home_setting" class="form-control" placeholder="Home/Property Settings" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-3 col-md-3  control-label">Time ON</label>
                                <div class="col-sm-3  col-md-3">
                                    <select class="form-control" name="time_on">
                                        <option selected disabled>Select Time On</option>
                                        '.$timeon_drop.'
                                    </select>
                                </div>

                                <label class="col-sm-3 col-md-3  control-label">Time OFF</label>
                                <div class="col-sm-3  col-md-3">
                                    <select class="form-control" name="time_off">
                                        <option selected disabled>Select Time Off</option>
                                        '.$timeoff_drop.'
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-3 col-md-3  control-label">Daily Hours</label>
                                <div class="col-sm-3  col-md-3">
                                    <input type="text" name="daily_hours" value="'.$hour_per_day.'" class="form-control" placeholder="Hours per Day">
                                </div>

                                <label class="col-sm-3 col-md-3  control-label">Days in Month</label>
                                <div class="col-sm-3  col-md-3">
                                    <input type="text" name="month_days" value="'.$month_days.'" class="form-control" placeholder="Days in Month">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Clock Time Set</label>
                                <div class="col-sm-10  col-md-9">
                                    <select name="clockTime_set" class="form-control">
                                        <option value="1" '.$true_time_set.'>Yes</option>
                                        <option value="0" '.$false_time_set.'>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Pwr Mode (Time ON)</label>
                                <div class="col-sm-10  col-md-9">
                                    <select name="pwr_mode" class="form-control">
                                        <option value="1" '.$true_pwr_mode.'>Yes</option>
                                        <option value="0" '.$false_pwr_mode.'>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">HVAC Fan Set to ON (Winter)</label>
                                <div class="col-sm-10  col-md-9">
                                    <select name="hvac_fan" class="form-control">
                                        <option value="1" '.$true_hvac_fan.'>Yes</option>
                                        <option value="0" '.$false_hvac_fan.'>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Additional Comments</label>
                                <div class="col-sm-10  col-md-9">
                                    <textarea name="comments" placeholder="Additional Comments" class="form-control">'.$comments.'</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 marginBot">
                                <label class="col-sm-2 col-md-3  control-label">Technician\'s Name</label>
                                <div class="col-sm-10  col-md-9">
                                    <input type="text" value="'.$technician_name.'" name="technician_name" class="form-control" placeholder="Technician\'s Name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-12 col-md-12 marginBot">
                            <label class="col-sm-2 col-md-3  control-label">Signature</label>
                                <div class="col-sm-10  col-md-9">
                                    <img src="'.$signature.'">
                                </div>
                            </div>
                        </div>
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>';

    }

    public function cancelAgreement($orderId=false, $agrementId=false){

        $agreement_id   = isset($_POST['agreement_id']) ? $_POST['agreement_id'] : $agrementId;
        $order_id       = isset($_POST['order_id']) ? $_POST['order_id'] : $orderId;

        $sql = "SELECT `order_ref` FROM `orders` WHERE order_id='$order_id'";
        $data = $this->dbF->getRow($sql);
        $chkTelr = $data[0];

        $sql = "SELECT `due_date` FROM `invoices` WHERE order_id='$order_id' ORDER BY invoice_pk DESC LIMIT 1";
        $data = $this->dbF->getRow($sql);
        $chkDate = $data[0];
        $dt = date('Y-m-d');

        $sql = "SELECT `product_id` FROM `orders` WHERE order_id='$order_id'";
        $data = $this->dbF->getRow($sql);
        $pId = $data[0];

        $sql = "SELECT `setting_val` FROM `product_setting` WHERE setting_name='actual_expire' AND p_id='$pId'";
        $data = $this->dbF->getRow($sql);
        $expire = $data[0];

        $sql = "SELECT `setting_val` FROM `product_setting` WHERE setting_name='cancel_charges' AND p_id='$pId'";
        $data = $this->dbF->getRow($sql);
        $charges = $data[0];

        if($chkTelr=='manual'){

            $sql = "UPDATE `orders` SET `order_status` = 'cancelled' WHERE `order_id` = ? AND `agrement_id` = ?";
            $res = $this->dbF->setRow($sql, array($order_id,$agreement_id));

            $sql_invoice = "UPDATE `invoices` SET `invoice_status` = 'cancelled' WHERE `order_id` = ? AND `invoice_status` = 'pending'";  
            $res_invoice = $this->dbF->setRow($sql_invoice, array($order_id));

//             if($dt<$chkDate){
//             $sql="INSERT INTO `invoices`( 
//                 `order_id`, 
//                 `price`, 
//                 `due_date`, 
//                 `invoice_status`, 
//                 `update_date`
//             ) VALUES ('$order_id','$charges','$dt','pending','$dt')";
//             $this->dbF->setRow($sql);

// // Zoho
//             $sql = "SELECT `zoho_contact_id`, `zoho_contact_person` FROM `accounts_user` WHERE `acc_id` = '$order_user'";
//             $rss = $this->dbF->getRow($sql);

//             $zoho_contact_id     = $rss['zoho_contact_id'];
//             $zoho_contact_person = $rss['zoho_contact_person'];

// $sql_invDet = "SELECT * FROM `invoices` ORDER BY `invoice_pk` DESC LIMIT 1";
// $res_invDet = $this->dbF->getRow($sql_invDet);
// $iPK = $res_invDet['invoice_pk'];

// $sql_pid = "SELECT o.`product_id` FROM `orders` o JOIN `invoices` i WHERE o.`order_id` = i.`order_id` AND i.`invoice_pk` = '$iPK'";
// $res_pid = $this->dbF->getRow($sql_pid);
// $pID = $res_pid['product_id'];

// $sql_item = "SELECT pd.`prodet_name`,pd.`prodet_shortDesc`,pd.`zoho_item_no`,pp.`propri_price` FROM `proudct_detail` pd JOIN `product_price` pp WHERE pd.`prodet_id` = pp.`propri_prodet_id` AND pd.`prodet_id` = ?";
// $res_item = $this->dbF->getRow($sql_item);

// $pro_name = translateFromSerialize($res_item['prodet_name']);
// $pro_desc = translateFromSerialize($res_item['prodet_shortDesc']);

//             $invoice_det = array(
// 'customer_id' => $zoho_contact_id,
// 'contact_persons' => $zoho_contact_person,
// 'invoice_number' => $order_invoice_print,
// 'date' => $res_invDet['due_date'],
// 'line_items' => array(
// array(
// 'item_id' => $res_item['zoho_item_no'],
// 'name' => $pro_name,
// 'description' => $pro_desc,
// 'item_order' => 1,
// 'rate' => doubleval($res_invDet['price']),
// 'quantity' => 1
// )
// )
// );

// $client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
// $secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

// // Zoho Books Refresh Token with Scope of Full Access ( ZohoBooks.fullaccess.all ).
// $refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 


// $params = array(
// 'refresh_token' => $refresh,
// 'client_id' => $client_id,
// 'client_secret' => $secret,
// 'redirect_uri' => WEB_URL.'/orderInvoice.php',
// 'grant_type' => 'refresh_token'
// );

// // Using refresh token to generate access token.
// $ch = curl_init(); 
// curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
// curl_setopt($ch, CURLOPT_POST, count($params));
// curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $results = curl_exec($ch);
// curl_close($ch);

// $array = json_decode($results,true);

// $access_token = $array['access_token']; // Access Token


// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/invoices?organization_id=667162566&ignore_auto_number_generation=true");
// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
// curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($invoice_det));
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $results2 = curl_exec($ch);
// curl_close($ch);
// // Zoho

//             }

            echo '1';
        }

        else{
        $api_url = 'https://secure.innovatepayments.com/tools/api/xml/agreement/'.$agreement_id;

        $merchantId = '11526';
        $key = '0c9fdfb5M$BbdT42jhkDRM9Z';

        $auth = $merchantId.':'.$key;
        $auth_enc = base64_encode($auth);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_enc"));
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $result = curl_exec($ch);
        curl_close($ch);


        $xml = simplexml_load_string($result);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        
        if(!empty($array) && $array['statustxt'] == 'Cancelled'){
            $sql = "UPDATE `orders` SET `order_status` = 'cancelled' WHERE `order_id` = ? AND `agrement_id` = ?";
            $res = $this->dbF->setRow($sql, array($order_id,$agreement_id));

            $sql_invoice = "UPDATE `invoices` SET `invoice_status` = 'cancelled' WHERE `order_id` = ? AND `invoice_status` = 'pending'";  
            $res_invoice = $this->dbF->setRow($sql_invoice, array($order_id));


//             if($dt<$chkDate){
//             $sql="INSERT INTO `invoices`( 
//                 `order_id`, 
//                 `price`, 
//                 `due_date`, 
//                 `invoice_status`, 
//                 `update_date`
//             ) VALUES ('$order_id','$charges','$dt','pending','$dt')";
//             $this->dbF->setRow($sql);

//             $sql = "SELECT * FROM `order_detail` WHERE order_id='$order_id'";
//             $vall = $this->dbF->getRow($sql);

//             $sql = "SELECT * FROM `order` WHERE order_id='$order_id'";
//             $vall3 = $this->dbF->getRow($sql);
//             $pid = $vall3['product_id'];
//             $order_user = $vall3['order_user'];

//             $sql = "SELECT prodet_name,prodet_shortDesc FROM `proudct_detail` WHERE prodet_id='$pid'";
//             $vall2 = $this->dbF->getRow($sql);
//             $pro_name = translateFromSerialize($vall2['prodet_name']);
//             $prodet_shortDesc = translateFromSerialize($vall2['prodet_shortDesc']);

//             $params = array(
//             'ivp_method'  => 'create',
//             'ivp_store'   => '20901',
//             'ivp_authkey' => 'vJJrn~6LpK-6FR8f',
//             'ivp_amount'  => $charges,
//             'ivp_currency'=> 'AED',
//             'ivp_cart'    => $order_id,  
//             'ivp_test'    => '0',
//             'ivp_desc'    => "Cancellation ".$pro_name." ".$prodet_shortDesc,
//             'bill_fname'  => $vall['fname'],
//             'bill_sname'  => $vall['lname'],
//             'bill_addr1'  => $vall['address'],
//             'bill_city'   => $vall['country'],
//             'bill_email'  => $vall['email'],
//             'bill_country'=> 'ae',
//             'order_ref'   => $chkTelr,
//             );

//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
//             curl_setopt($ch, CURLOPT_POST, count($params));
//             curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
//             $results = curl_exec($ch);
//             curl_close($ch);
            
//             // Zoho
//             $sql = "SELECT `zoho_contact_id`, `zoho_contact_person` FROM `accounts_user` WHERE `acc_id` = '$order_user'";
//             $rss = $this->dbF->getRow($sql);

            
//             $zoho_contact_id     = $rss['zoho_contact_id'];
//             $zoho_contact_person = $rss['zoho_contact_person'];

// $sql_invDet = "SELECT * FROM `invoices` ORDER BY `invoice_pk` DESC LIMIT 1";
// $res_invDet = $this->dbF->getRow($sql_invDet);
// $iPK = $res_invDet['invoice_pk'];

// $sql_pid = "SELECT o.`product_id` FROM `orders` o JOIN `invoices` i WHERE o.`order_id` = i.`order_id` AND i.`invoice_pk` = '$iPK'";
// $res_pid = $this->dbF->getRow($sql_pid);
// $pID = $res_pid['product_id'];

// $sql_item = "SELECT pd.`prodet_name`,pd.`prodet_shortDesc`,pd.`zoho_item_no`,pp.`propri_price` FROM `proudct_detail` pd JOIN `product_price` pp WHERE pd.`prodet_id` = pp.`propri_prodet_id` AND pd.`prodet_id` = ?";
// $res_item = $this->dbF->getRow($sql_item);

// $pro_name = translateFromSerialize($res_item['prodet_name']);
// $pro_desc = translateFromSerialize($res_item['prodet_shortDesc']);



//             $invoice_det = array(
// 'customer_id' => $zoho_contact_id,
// 'contact_persons' => $zoho_contact_person,
// 'invoice_number' => $order_invoice_print,
// 'date' => $res_invDet['due_date'],
// 'line_items' => array(
// array(
// 'item_id' => $res_item['zoho_item_no'],
// 'name' => $pro_name,
// 'description' => $pro_desc,
// 'item_order' => 1,
// 'rate' => doubleval($res_invDet['price']),
// 'quantity' => 1
// )
// )
// );


//             $client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
// $secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

// // Zoho Books Refresh Token with Scope of Full Access ( ZohoBooks.fullaccess.all ).
// $refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 


// $params = array(
// 'refresh_token' => $refresh,
// 'client_id' => $client_id,
// 'client_secret' => $secret,
// 'redirect_uri' => WEB_URL.'/orderInvoice.php',
// 'grant_type' => 'refresh_token'
// );

// // Using refresh token to generate access token.
// $ch = curl_init(); 
// curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
// curl_setopt($ch, CURLOPT_POST, count($params));
// curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $results = curl_exec($ch);
// curl_close($ch);

// $array = json_decode($results,true);

// $access_token = $array['access_token']; // Access Token


// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/invoices?organization_id=667162566&ignore_auto_number_generation=true");
// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
// curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($invoice_det));
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $results2 = curl_exec($ch);
// curl_close($ch);
// // Zoho
//             }

            echo '1';

        }else{
            echo '0';
        }

        }

    }

    public function editSchedule(){
        $id = $_POST['id'];

        $sql = "SELECT * FROM `schedule_form` WHERE `schedule_id` = ?";
        $res = $this->dbF->getRow($sql, array($id));

        $order_id = $res['order_id'];
        $schedule_date = $res['schedule_date'];
        $schedule_slot = $res['schedule_slot'];

        $slots_array = array('8AM to 11AM', '11AM to 2PM', '2PM to 5PM', '5PM to 8PM');

        $sql = "SELECT `schedule_slot` FROM `schedule_form` WHERE `schedule_date` = ?";
        $res = $this->dbF->getRows($sql, array($schedule_date));

        foreach ($res as $key => $value) {
            $slot = $value['schedule_slot'];

            if (($index = array_search($slot, $slots_array)) !== false) {
                unset($slots_array[$index]);
            }
        }
        $return_option = '<option selected disabled>Select Time Slot</option>';
        foreach ($slots_array as $row) {
            $option_select = ($row == $schedule_slot) ? 'selected' : '';
            $return_option .= '<option value="'.$row.'" '.$option_select.'>'._uc($row).'</option>';       
        }

        $return = '<form class="form-horizontal" id="editSchedule_form" role="form">
                        <input type="hidden" name="order_id" value="'.$orderId.'">
                        <input type="hidden" name="sch_id" value="'.$id.'">

                        <div class="form-group">
                            <label class="col-sm-2 col-md-3 control-label">Date</label>
                            <div class="col-sm-10  col-md-9">
                                <input type="text" name="editSchedule_date" id="editSchedule_date" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off" value="'.$schedule_date.'" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-md-3 control-label">Time Slot</label>
                            <div class="col-sm-10  col-md-9">
                                <select name="editSchedule_slot" id="editSchedule_slot" class="form-control" required>
                                '.$return_option.'
                                </select>
                            </div>
                        </div>

                    </form>
                    <script>
                        $("#editSchedule_date").on("change", function(){
                            chosen_date = $(this).val();

                            $.ajax({
                                url: "order/order_ajax.php?page=getAvailableSlots",
                                type: "post",
                                data: {chosen_date:chosen_date}
                            }).done(function(res){
                                $("#editSchedule_slot").html(res);
                            });
                        });
                    </script>';

        echo $return;
    }

    function submitEditSchedule(){

        $sched_id = $_POST['sch_id'];
        $order_id = $_POST['order_id'];

        $edit_sched_date = $_POST['editSchedule_date'];
        $edit_sched_slot = $_POST['editSchedule_slot'];

        $sql = "UPDATE `schedule_form` SET `schedule_date` = '$edit_sched_date', `schedule_slot` = '$edit_sched_slot' WHERE `schedule_id` = $sched_id";
        $this->dbF->setRow($sql);

        if($this->dbF->rowCount > 0){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function updateOrderStatus(){
        $order_id   = $_POST['order_id'];
        $status     = $_POST['status'];

        if($status == 'pending_remove'){
            $sql = "SELECT `agrement_id` FROM `orders` WHERE `order_id` = ?";
            $res_order = $this->dbF->getRow($sql, array($order_id));

            $agrement_id = $res_order['agrement_id'];

            $this->cancelAgreement($order_id,$agrement_id);
        }

        if($status == 'pending_inst' || $status == 'live' || $status == 'pending_remove'){
            $sql = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
            $res = $this->dbF->setRow($sql, array($status, $order_id));
        }else{
            $sql = "UPDATE `orders` SET `order_status` = ? WHERE `order_id` = ?";
            $res = $this->dbF->setRow($sql, array($status, $order_id));
        }

        if($this->dbF->rowCount > 0){
            echo '1';
        }else{
            echo '0';
        }
    }

    //my code
    public function updateInvoice(){
        $id = $_REQUEST['id'];
        $sql = "UPDATE invoices SET invoice_status='paid' WHERE invoice_pk='$id'";
        $res = $this->dbF->setRow($sql);
        if($this->dbF->rowCount > 0){
            echo '1';
        }else{
            echo '0';
        }
    }
    //my code
}

?>