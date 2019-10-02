<?php
require_once(__DIR__ . "/../../product_management/classes/currency.class.php");
require_once(__DIR__ . "/../../product_management/classes/scale.class.php");
require_once(__DIR__ . "/../../product_management/classes/color.class.php");

class coupon extends object_class
{
    use global_setting;
    public $productF;

    public $c_scale;
    public $c_color;
    public $c_currency;

    public $prefix_editPro = "edit_pro"; // asad

    function __construct()
    {
        parent::__construct();

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../../product_management/functions/product_function.php");
            $this->productF=new product_function();
        }

        $this->c_color = new colors();
        $this->c_scale = new scales();
        $this->c_currency = new currency_management();


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //pCoupon.php
        $_w['Discount Products'] = '' ;
        $_w['Active Coupons'] = '' ;
        $_w['Coupon Off'] = '' ;
        $_w['Pending'] = '' ;
        $_w['Expire'] = '' ;
        $_w['New Coupon'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['New Sale Offer'] = '' ;
        $_w['Expire Discount Products'] = '' ;
        $_w['Pending Discount Products'] = '' ;
        $_w['Discount Products Status Off'] = '' ;
        $_w['All Products'] = '' ;
        //this class
        $_w['SNO'] = '' ;
        $_w['USE'] = '' ;
        $_w['COUPON'] = '' ;
        $_w['CATEGORY'] = '' ;
        $_w['SALE DATE'] = '' ;
        $_w['ACTION'] = '' ;
        //coupon form
        $_w['Coupon'] = '' ;
        $_w['New Coupon Add Successfully'] = '' ;
        $_w['New Coupon Added With Id: {{id}}'] = '' ;
        $_w['Product'] = '' ;
        $_w['Coupon Add Fail Please Try Again'] = '' ;
        $_w['SUBMIT'] = '' ;
        $_w['Product Category'] = '' ;
        $_w['Only Product Whole Sale Offer'] = '' ;
        $_w['Only Product Discount Offer'] = '' ;
        $_w['Only Coupon Offer (Recommended)'] = '' ;
        $_w['If Product Has Individual Discount Then Which situation apply?'] = '' ;
        $_w['Product Discount'] = '' ;
        $_w['Free Shipping Allow In Country'] = '' ;
        $_w['In Percent %'] = '' ;
        $_w['In Price'] = '' ;
        $_w['Discount Deduct In'] = '' ;
        $_w['Discount End Date: Leave blank for Always'] = '' ;
        $_w['Discount Start Date : Discount will available from start date,Leave blank To Start Now'] = '' ;
        $_w['Active From'] = '' ;
        $_w['Enter Coupon Offer Name'] = '' ;
        $_w['Coupon Name'] = '' ;
        $_w['Coupon Status'] = '' ;
        $_w['Product Coupon Setting'] = '' ;
        $_w['User Type'] = '' ;
        $_w['Gold'] = '' ;
        $_w['Basic'] = '' ;
        $_w['Platinum'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Coupon');
    }


    /************************************/
    /**
     * @param $pId
     * @return bool|MultiArray
     */
    public function saleSettingData($pId){
        $sql ="SELECT `product_coupon`.*, `product_coupon_setting`.* FROM
                    `product_coupon` join `product_coupon_setting`
                    on `product_coupon`.`pCoupon_pk` = `product_coupon_setting`.`pCoupon_id`
                    WHERE `product_coupon`.`pCoupon_pk`    = '$pId' ";
        $data = $this->dbF->getRows($sql);
        if($this->dbF->rowCount>0){
            return $data;
        }
        return false;
    }

    /**
     * @param $pId
     * @return bool|MultiArray
     */
    public function salePriceData($pId){
        $sql ="SELECT `product_coupon`.*, `product_coupon_prices`.* FROM
                    `product_coupon` join `product_coupon_prices`
                    on `product_coupon`.`pCoupon_pk` = `product_coupon_prices`.`pSale_price_id`
                    WHERE `product_coupon`.`pCoupon_pk`    = '$pId' ";
        $data = $this->dbF->getRows($sql);
        if($this->dbF->rowCount>0){
            return $data;
        }
        return false;
    }

    public function discountArrayFound($data,$findKey){
        foreach($data as $val){
            if($val['pCoupon_setting_name'] == $findKey){
                return $val['pCoupon_setting_value'];
            }
        }
        return '';
    }

    /**
     *
     */
    public function couponView()
    {
        // $qry="SELECT * FROM `proudct_detail` WHERE `product_update` = '1'";
        $today  = date('Y-m-d');
        $qry="SELECT pCoupon_pk,pCoupon_from,pCoupon_to,pCoupon_category,pCoupon_name,pCoupon_status
                        ,(
                          SELECT count(DISTINCT(order_id)) as cnt
                          FROM `order_invoice_record` WHERE setting_name='coupon' AND setting_val = c.pCoupon_name
                        ) as sale
                    FROM product_coupon as c
                     WHERE pCoupon_status = '1'
                      AND pCoupon_from <= '$today'
                      AND pCoupon_to  >= '$today' OR pCoupon_to  = '' ";

        echo $this->product_list_View($qry,'Active');
    }

    public function productDiscountDraft()
    {
        $today  = date('Y-m-d');
        $qry="SELECT pCoupon_pk,pCoupon_from,pCoupon_to,pCoupon_category,pCoupon_name,pCoupon_status
                        ,(
                          SELECT count(DISTINCT(order_id)) as cnt
                          FROM `order_invoice_record` WHERE setting_name='coupon' AND setting_val = c.pCoupon_name
                        ) as sale
                    FROM product_coupon as c
                 WHERE pCoupon_status = '0' ";
        echo $this->product_list_View($qry);
    }

    public function productDiscountPending()
    {
        $today  = date('Y-m-d');
        $qry="SELECT pCoupon_pk,pCoupon_from,pCoupon_to,pCoupon_category,pCoupon_name,pCoupon_status
                        ,(
                          SELECT count(DISTINCT(order_id)) as cnt
                          FROM `order_invoice_record` WHERE setting_name='coupon' AND setting_val = c.pCoupon_name
                        ) as sale
                    FROM product_coupon as c
                 WHERE pCoupon_status = '1'
                      AND pCoupon_from > '$today' ";
        echo $this->product_list_View($qry);
    }

    public function productDiscountExpire()
    {
        $today  = date('Y-m-d');
        $qry="SELECT pCoupon_pk,pCoupon_from,pCoupon_to,pCoupon_category,pCoupon_name,pCoupon_status
                        ,(
                          SELECT count(DISTINCT(order_id)) as cnt
                          FROM `order_invoice_record` WHERE setting_name='coupon' AND setting_val = c.pCoupon_name
                        ) as sale
                    FROM product_coupon as c
                 WHERE pCoupon_status = '1'
                      AND pCoupon_from < '$today'
                      AND pCoupon_to  < '$today' AND pCoupon_to  != '' ";
        echo $this->product_list_View($qry);
    }

    /**
     * @param $qry
     */
    private function product_list_View($qry,$calledFrom='Active'){
        global $_e;
        $data=$this->dbF->getRows($qry);
            $uniq   =   uniqid('id');

            echo  '
            <div class="table-responsive">
            <table class="table dTable table-hover tableIBMS ">
                <thead>
                    <tr>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['COUPON']) .'</th>
                        <th>'. _u($_e['CATEGORY']) .'</th>
                        <th>'. _u($_e['SALE DATE']) .'</th>
                        <th>'. _u($_e['USE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </tr>
                </thead>
                <tbody>';
            $i=0;
            foreach($data as $key=>$val){
                $i++;
                $saleId  =  $val['pCoupon_pk'];
                $dateFrom=  $val['pCoupon_from'];
                $dateTo  =  $val['pCoupon_to'];
                $dateRange  =   "From : $dateFrom ; Expire : $dateTo";

                $categoryNames  =   $this->productF->getCategoryNames($val['pCoupon_category']);

                echo "
                        <tr class='p_$saleId'>
                            <td class='tableBgGray'>
                                <div class='checkbox'>
                                    <label>$i</label>
                                </div>
                            </td>
                            <td>".$val['pCoupon_name']."</td>
                            <td>".$categoryNames."</td>
                            <td>".$dateRange."</td>
                            <td>".$val['sale']."</td>
                            <td class='tableBgGray' width='110'>
                                <div class='btn-group btn-group-sm'>
                                <a href='-product?page=pCouponForm&sId=$saleId' class='btn'><i class='glyphicon glyphicon-edit'></i></a>
                                <a data-id='$saleId' onclick='discountProductDel(this);' class='btn '>
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
            </div>';

        }

}

?>