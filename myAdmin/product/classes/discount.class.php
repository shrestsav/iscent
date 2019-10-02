<?php
require_once(__DIR__ . "/../../product_management/classes/currency.class.php");
require_once(__DIR__ . "/../../product_management/classes/scale.class.php");
require_once(__DIR__ . "/../../product_management/classes/color.class.php");

class discount extends object_class
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
        //pDiscount.php
        $_w['Discount Products'] = '' ;
        $_w['Discount Status Off'] = '' ;
        $_w['Expire'] = '' ;
        $_w['Pending'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;

        //search by date Range
        $_w['Search By Date Range'] = '' ;
        $_w['Date From'] = '' ;
        $_w['Date To'] = '' ;
        //this class
        $_w['From'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['CREATE DATE'] = '' ;
        $_w['DISCOUNT DATE'] = '' ;
        $_w['PRODUCT NAME'] = '' ;
        $_w['SNO'] = '' ;

        //pDiscountForm.php
        $_w['Discount Product Setting'] = '' ;
        $_w['Discount'] = '' ;
        $_w['Discount From'] = '' ;
        $_w['Discount To'] = '' ;
        $_w['Discount Start Date : Discount will available from start date,Leave blank To Start Now'] = '' ;
        $_w['Discount End Date: Leave blank for Always'] = '' ;
        $_w['Discount Deduct In'] = '' ;
        $_w['In Price'] = '' ;
        $_w['In Percent %'] = '' ;
        $_w['SUBMIT'] = '' ;
        $_w['New Product Discount Added with Product Id : {{pId}} And Discount Id : {{id}}'] = '' ;
        $_w['Product'] = '' ;
        $_w['Product Discount Save Successfully'] = '' ;
        $_w['Product Discount Save Failed'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product discount');

    }


    /************************************/
    /**
     * @param $pId
     * @return bool|MultiArray
     */
    public function discountSettingData($pId){
        $sql ="SELECT `product_discount`.*, `product_discount_setting`.* FROM
                    `product_discount` join `product_discount_setting`
                    on `product_discount`.`product_discount_pk` = `product_discount_setting`.`product_dis_id`
                    WHERE `product_discount`.`discount_PId`    = '$pId' ";
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
    public function discountPriceData($pId){
         $sql ="SELECT `product_discount`.*, `product_discount_prices`.* FROM
                `product_discount` join `product_discount_prices`
                on `product_discount`.`product_discount_pk` = `product_discount_prices`.`product_dis_id`
                WHERE `product_discount`.`discount_PId`    = '$pId' ";
        $data = $this->dbF->getRows($sql);
        if($this->dbF->rowCount>0){
            return $data;
        }
        return false;
    }

    public function discountArrayFound($data,$findKey){
        foreach($data as $val){
            if($val['product_dis_name'] == $findKey){
                return $val['product_dis_value'];
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
        $qry="SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                FROM
                   `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE `product_setting`.`setting_name`='publicAccess'
                      AND `product_setting`.`setting_val`='1'
                      AND `proudct_detail`.`product_update`='1'
                      AND `proudct_detail`.`prodet_id` in
                        (SELECT discount_PId FROM product_discount WHERE product_dis_status = '1'
                          AND `product_discount_pk` in
                                (SELECT product_dis_id FROM product_discount_setting WHERE
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateFrom' AND `product_dis_value` <= '$today') AND
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateTo' AND (`product_dis_value` >= '$today' OR `product_dis_value` = '') )) ) ";
        echo $this->product_list_View($qry,'Active');
    }

    public function productDiscountDraft()
    {
        $qry="SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                FROM
                   `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE `product_setting`.`setting_name`='publicAccess'
                      AND `product_setting`.`setting_val`='1'
                      AND `proudct_detail`.`product_update`='1'
                      AND `proudct_detail`.`prodet_id` in (SELECT discount_PId FROM product_discount WHERE product_dis_status = '0') ";
        echo $this->product_list_View($qry);
    }

    public function productDiscountPending()
    {
        $today  = date('Y-m-d');
        $qry="SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                FROM
                   `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE `product_setting`.`setting_name`='publicAccess'
                      AND `product_setting`.`setting_val`='1'
                      AND `proudct_detail`.`product_update`='1'
                      AND `proudct_detail`.`prodet_id` in
                        (SELECT discount_PId FROM product_discount WHERE product_dis_status = '1'
                          AND `product_discount_pk` in
                                (SELECT product_dis_id FROM product_discount_setting WHERE
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateFrom' AND `product_dis_value` > '$today' ) AND
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateTo' AND `product_dis_value` > '$today' AND `product_dis_value` != '')) ) ";

        echo $this->product_list_View($qry);
    }

    public function productDiscountExpire()
    {
        $today  = date('Y-m-d');
        $qry="SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                FROM
                   `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE `product_setting`.`setting_name`='publicAccess'
                      AND `product_setting`.`setting_val`='1'
                      AND `proudct_detail`.`product_update`='1'
                      AND `proudct_detail`.`prodet_id` in
                        (SELECT discount_PId FROM product_discount WHERE product_dis_status = '1'
                          AND `product_discount_pk` in
                                (SELECT product_dis_id FROM product_discount_setting WHERE
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateFrom' AND `product_dis_value` < '$today') AND
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateTo' AND `product_dis_value` < '$today' AND `product_dis_value` != '')) ) ";

        echo $this->product_list_View($qry);
    }

    /**
     * @param $qry
     */
    private function product_list_View($qry,$calledFrom='Active'){
        $data=$this->dbF->getRows($qry);
        $defaultLang= $this->functions->AdminDefaultLanguage();



        if($this->dbF->rowCount>0){
            $uniq=uniqid('id');
            global $_e;
            echo  '
            <div class="table-responsive">
            <table class="table dTable table-hover tableIBMS ">
                <thead>
                    <tr>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['PRODUCT NAME']) .'</th>
                        <th>'. _u($_e['DISCOUNT DATE']) .'</th>
                        <th>'. _u($_e['CREATE DATE']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </tr>
                </thead>
                <tbody>';

            $i=0;
            foreach($data as $key=>$val){
                $i++;
                $name=unserialize($val['prodet_name']);
                //$sDesc=unserialize($val['prodet_shortDesc']);
                $id=$val['prodet_id'];
                //$link = $this->functions->getLinkFolder();

                $discountSettingData = $this->discountSettingData($id);
                if($discountSettingData==false){continue;}
                $discountId =   $discountSettingData[0]['product_discount_pk'];
                $dateFrom   =   $this->discountArrayFound($discountSettingData,'dateFrom');
                $dateTo     =   $this->discountArrayFound($discountSettingData,'dateTo');
                $dateRange  =   "". _uc($_e['From']) ." : $dateFrom ; ". _uc($_e['Expire']) ." : $dateTo";

                //$today  = date('Y-m-d');

                echo "
                        <tr class='p_$discountId'>
                            <td class='tableBgGray'>
                                <div class='checkbox'>
                                    <label>
                                      $i
                                    </label>
                                  </div>
                            </td>
                            <td>".$name[$defaultLang]."</td>
                            <td>".$dateRange."</td>
                            <td>".$val['prodet_timeStamp']."</td>
                            <td class='tableBgGray' width='110'>
                                <div class='btn-group btn-group-sm'>
                                <a href='-product?page=pDiscountForm&pId=$id' class='btn' target='_blank'><i class='glyphicon glyphicon-edit'></i></a>
                                <a data-id='$discountId' onclick='discountProductDel(this);' class='btn '>
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
}

?>