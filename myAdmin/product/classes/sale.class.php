<?php
require_once(__DIR__ . "/../../product_management/classes/currency.class.php");
require_once(__DIR__ . "/../../product_management/classes/scale.class.php");
require_once(__DIR__ . "/../../product_management/classes/color.class.php");

class sale extends object_class
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
        //pHoleSale.php
        $_w['WholeSale Products'] = '' ;
        $_w['Discount Products'] = '' ;
        $_w['Sale Off'] = '' ;
        $_w['Pending'] = '' ;
        $_w['Expire'] = '' ;
        $_w['New Sale Offer'] = '' ;
        $_w['Discount Status Off'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        //pHoleSaleForm.php
        $_w['SUBMIT'] = '' ;
        $_w['Only Product Discount Offer'] = '' ;
        $_w['Only Sale Offer (Recommended)'] = '' ;
        $_w['Product Discount'] = '' ;
        $_w['If Product Has Individual Discount Then Which situation apply?'] = '' ;
        $_w['Apply Both(Coupon & Discount)'] = '' ;
        $_w['Only Coupon'] = '' ;
        $_w['Only Discount (Recommended)'] = '' ;
        $_w['If Client use coupon'] = '' ;
        $_w['Coupon Status'] = '' ;
        $_w['In Percent %'] = '' ;
        $_w['In Price'] = '' ;
        $_w['Discount Deduct In'] = '' ;
        $_w['Discount End Date: Leave blank for Always'] = '' ;
        $_w['Discount To'] = '' ;
        $_w['Discount Start Date : Discount will available from start date,Leave blank To Start Now'] = '' ;
        $_w['Discount From'] = '' ;
        $_w['Enter Sale Offer Name'] = '' ;
        $_w['Sale Offer Name'] = '' ;
        $_w['Sale Status'] = '' ;
        $_w['Product Whole Sale Offer Setting'] = '' ;
        $_w['New Product Whole Sale Added with Sale Discount Id: {{id}}'] = '' ;
        $_w['Product'] = '' ;
        $_w['Sale Offer'] = '' ;
        $_w['Product Whole Sale Offer Save Successfully'] = '' ;
        $_w['Product Whole Sale Offer Save Fail'] = '' ;

        //this class
        $_w['SNO'] = '' ;
        $_w['SALE'] = '' ;
        $_w['CATEGORY'] = '' ;
        $_w['SALE DATE'] = '' ;
        $_w['ACTION'] = '' ;


        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product WholeSale');

    }


    /************************************/
    /**
     * @param $pId
     * @return bool|MultiArray
     */
    public function saleSettingData($pId){
        $sql ="SELECT `product_sale`.*, `product_sale_setting`.* FROM
                    `product_sale` join `product_sale_setting`
                    on `product_sale`.`pSale_pk` = `product_sale_setting`.`pSale_id`
                    WHERE `product_sale`.`pSale_pk`    = '$pId' ";
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
        $sql ="SELECT `product_sale`.*, `product_sale_prices`.* FROM
                    `product_sale` join `product_sale_prices`
                    on `product_sale`.`pSale_pk` = `product_sale_prices`.`pSale_price_id`
                    WHERE `product_sale`.`pSale_pk`    = '$pId' ";
        $data = $this->dbF->getRows($sql);
        if($this->dbF->rowCount>0){
            return $data;
        }
        return false;
    }

    public function discountArrayFound($data,$findKey){
        foreach($data as $val){
            if($val['pSale_setting_name'] == $findKey){
                return $val['pSale_setting_value'];
            }
        }
        return '';
    }

    /**
     *
     */
    public function discountView()
    {
        // $qry="SELECT * FROM `proudct_detail` WHERE `product_update` = '1'";
        $today  = date('Y-m-d');
        $qry="SELECT * FROM product_sale
                 WHERE pSale_status = '1'
                      AND pSale_from <= '$today'
                      AND (pSale_to  >= '$today' OR pSale_to  = '')
                       ";

        echo $this->product_list_View($qry,'Active');
    }

    public function productDiscountDraft()
    {
        $today  = date('Y-m-d');
        $qry="SELECT * FROM product_sale
                 WHERE pSale_status = '0' ";
        echo $this->product_list_View($qry);
    }

    public function productDiscountPending()
    {
        $today  = date('Y-m-d');
        $qry="SELECT * FROM product_sale
                 WHERE pSale_status = '1'
                      AND pSale_from > '$today' ";
        echo $this->product_list_View($qry);
    }

    public function productDiscountExpire()
    {
        $today  = date('Y-m-d');
        $qry="SELECT * FROM product_sale
                 WHERE pSale_status = '1'
                      AND pSale_from < '$today'
                      AND pSale_to  < '$today'
                      AND pSale_to  != ''";
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
                        <th>'. _uc($_e['SNO']) .'</th>
                        <th>'. _uc($_e['SALE']) .'</th>
                        <th>'. _uc($_e['CATEGORY']) .'</th>
                        <th>'. _uc($_e['SALE DATE']) .'</th>
                        <th>'. _uc($_e['ACTION']) .'</th>
                    </tr>
                </thead>
                <tbody>';

            $i=0;
            foreach($data as $key=>$val){
                $i++;
                $saleId  =  $val['pSale_pk'];
                $dateFrom=  $val['pSale_from'];
                $dateTo  =  $val['pSale_to'];
                $dateRange  =   "From : $dateFrom ; Expire : $dateTo";

                $categoryNames  =   $this->productF->getCategoryNames($val['pSale_category']);

                echo "
                        <tr class='p_$saleId'>
                            <td class='tableBgGray'>
                                <div class='checkbox'>
                                    <label>$i</label>
                                </div>
                            </td>
                            <td>".$val['pSale_name']."</td>
                            <td>".$categoryNames."</td>
                            <td>".$dateRange."</td>
                            <td class='tableBgGray' width='110'>
                                <div class='btn-group btn-group-sm'>
                                <a href='-product?page=pHoleSaleForm&sId=$saleId' class='btn' ><i class='glyphicon glyphicon-edit'></i></a>
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