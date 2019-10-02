<?php

class product_function extends object_class{
    public $product_inventory_date_time;  // used in productQTY()

    function __construct()
    {
        parent::__construct('3');

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w['Update Done'] = '' ;
        $_w['Update Fail'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;
        $_w['Update'] = '' ;
        $_w['Cash On Delivery'] = '' ;
        $_w['CreditCard'] = '' ;
        $_w['Paid'] = '' ;
        $_w['Denied'] = '' ;
        $_w['Cancel'] = '' ;
        $_w['Full Refunded'] = '' ;
        $_w['Ready For Packaging'] = '' ;
        $_w['Pending'] = '' ;
        $_w['Received'] = '' ;
        $_w['Measure send to factory'] = '' ;
        $_w['Order send for factory'] = '' ;
        $_w['Complete'] = '' ;
        $_w['Gift Card'] = '' ;
        $_w['2 CheckOut'] = '' ;
        $_w['Partial Delivery Done'] = '' ;
        $_w['Awaiting Measures From Customer'] = '' ;
        $_w['Buy {{buy_qty}} Get 1 free'] = '' ;
        $_w['You Get +{{free_qty}} free'] = '' ;
        $_w['+{{free_qty}} Free'] = '' ;
        $_w['FREE GIFT'] = '';
        $_w['DISCOUNT APPLIED'] = '';
        $_w['SALE OFFER APPLIED'] = '';
        $_w['COUPON CODE APPLIED'] = '';
        $_w['CHECKOUT OFFER APPLIED'] = '';
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product');
    }

    public function productSaleCountPlus($pId,$qty){
            $sql = "UPDATE proudct_detail SET `sale` = `sale`+$qty WHERE prodet_id = '$pId'";
            $this->dbF->setRow($sql);
    }

    public function productDealCountPlus($dealId,$qty){
        $sql = "UPDATE product_deal SET `sale` = `sale`+$qty WHERE id = '$dealId'";
        $this->dbF->setRow($sql);
    }

    public function productSaleCountMinus($pId,$qty){
            //not apply
            $sql = "UPDATE proudct_detail SET `sale` = `sale`- $qty WHERE prodet_id = '$pId'";
            $this->dbF->setRow($sql);
    }

    public function getProductFullName($pid,$scaleId,$colorId){
        $pName = $this->getProductName($pid);
        if($pName==false){
            return false;
        }
        if( $scaleId != '0'){
            $sName=  $this->getScaleName($scaleId);
        }else{
            $sName = "";
        }
        if($colorId != '0'){

            $cName = $this->getColorName($colorId);
            $cName ="<span style='background:#$cName;padding: 4px 10px;color: #fff;font-size: 11px;'>$cName</span>";
        }else{
            $cName = "";
        }
        $temp="$pName - $sName - $cName";
        $temp = trim($temp,'- ');
        return $temp;
    }

    /**
     * @param $pId
     * @param int $scaleId
     * @param int $colorId NotInUse
     * @return Weight | Float
     */
    public function getProductWeight($pId,$scaleId=0,$colorId=0){
        // Get weight from sizes
        if($scaleId != '0'){
            $sql ="SELECT * FROM `product_size_weight` WHERE
                    pw_size in
                      (SELECT prosiz_name FROM `product_size` WHERE `prosiz_id` = '$scaleId') AND pwPId = '$pId'";
            $data = $this->dbF->getRow($sql);
            if($this->dbF->rowCount>0){
                return floatval($data['pw_weight']);
            }
        }

        // Get Default Weight if not find from scale
        $sql = "SELECT * FROM `product_setting` WHERE p_id = '$pId' AND setting_name = 'defaultWeight'";
        $data1 = $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0){
            return floatval($data1['setting_val']);
        }

        //return 0 if no weight found
        return 0;
    }


    /**
     * @param $fromCountry
     * @param $toCountry
     * @return bool|mixed
     */
    public function shippingPrice($fromCountry,$toCountry){
        $hash = "$fromCountry:$toCountry";
        $sql ="SELECT * FROM `shipping` WHERE hash = '$hash' AND shp_int = '1'";
        $data = $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0){
            return $data;
        }
        return false;
    }


    /**
     * @param $pid
     * @return string
     */
    public function getProductName($pid){
        if($pid == '0'){
            return "";
        }
        $defaultLang= $this->functions->AdminDefaultLanguage();
        $sql    =   "SELECT `prodet_name` FROM `proudct_detail` WHERE `prodet_id` = '$pid' AND product_update = '1'";
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0){
            $name   =   translateFromSerialize($data['prodet_name']);
            return  $name;
        }
        return false;
    }

    /**
     * @param $pId
     * @return bool|MultiArray
     */
    public function getProductSetting($pId){
        $qry    =   "SELECT * FROM  `product_setting` WHERE `p_id` = '$pId'";
        $eData  =   $this->dbF->getRows($qry);
        if($this->dbF->rowCount>0)
            return $eData;
        return false;
    }

    /**
     * @param $settingName
     * @param $array
     * @return string
     */
    public function productSettingArray($settingName,$array,$pId) {
        // enter setting name and qry execute array.. -> productSettingEdit() array
        foreach ($array as $keya => $val) {
            if ($val['setting_name'] == $settingName && $val['p_id'] == $pId) {
                return $val['setting_val'];
            }
        }
        return "";
    }


    /**
     * @param $id
     * @return string
     */
    public function getScaleName($id){
        if($id == '0'){
            return "";
        }
        $sql="SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id` = '$id'";
        $data = $this->dbF->getRow($sql);
        $name=$data['prosiz_name'];
        return $name;
    }

    /**
     * @param $id
     * @return string
     */
    public function getColorName($id){
        if($id == '0'){
            return "";
        }
        $sql="SELECT `proclr_name` FROM `product_color` WHERE `propri_id` = '$id'";
        $data = $this->dbF->getRow($sql);
        $name=$data['proclr_name'];
        return $name;
    }

    /**
     * @param $id
     * @return string
     */
    public function getStoreName($id){
        if($id == '0'){
            return "";
        }
        $sql="SELECT `store_name`,`store_location` FROM `store_name` WHERE `store_pk` = '$id'";
        $data = $this->dbF->getRow($sql);
        $name = $data['store_name']." - ".$data['store_location'];
        return $name;
    }



    /**
     * @param $pid
     * @param string $storeId
     * @param string $scaleId
     * @param string $colorId
     * @return string
     */
    public function productQTY($pid,$storeId='0',$scaleId='0',$colorId='0',$exactValue = false){
        if($pid == '0'){
            return "";
        }
        if($storeId=='0' && $exactValue==false) $storeId =" >= '$storeId'";
        else $storeId =" = '$storeId'";

        if($scaleId=='0' && $exactValue==false) $scaleId =" >= '$scaleId'";
        else $scaleId =" = '$scaleId'";

        if($colorId=='0' && $exactValue==false) $colorId =" >= '$colorId'";
        else $colorId =" = '$colorId'";

        $sql="SELECT SUM(`qty_item`) AS QTY, MAX(`updateTime`) as update_time FROM `product_inventory`
                WHERE `qty_product_id` = '$pid'
                    AND `qty_store_id` $storeId
                    AND `qty_product_scale` $scaleId
                    AND `qty_product_color` $colorId";
        $data = $this->dbF->getRow($sql);
        $qty=$data['QTY'];
        // $this->product_inventory_date_time = $data['update_time']; 
        if($qty==''){ $qty ='0'; }
        return $qty;
    }


    public function customSizePrice($pId,$currency=false){
        $currencyId = $currency;
        $sql        = "SELECT * FROM product_size_custom WHERE `pId` = '$pId' AND currencyId = '$currencyId'";
        $customData = $this->dbF->getRow($sql);
        $price = $customData['price'];
        $price = empty($price) ? 0 : floatval($price);
        return $price;
    }

    /**
     * @param $productId
     * @param $scaleId
     * @param $colorId
     * @param $countryPK
     * @param string $symbol
     * @param string $betweenSymbol
     * @return float|string
     */
    public function productTotalPrice($productId,$scaleId,$colorId,$customId,$countryPK,$symbol='before/after/false',$betweenSymbol=' '){

// var_dump($countryPK);

        
        if($countryPK=="0" || $countryPK==false){
            $countryPK=$this->functions->ibms_setting('Default Admin_Price_Country');
        }

        $currData   =$this->currencyInfo($countryPK);
        $currId     =$currData['cur_id'];

        $currSymbol =$currData['cur_symbol'];

        $productPrice=$this->productPrice($productId,$currId);
        $productPrice=floatval($productPrice['propri_price']);

        $scalePrice=$this->scalePrice($scaleId,$currId,$productId);
        $scalePrice=floatval($scalePrice['prosiz_price']);

        $colorPrice=$this->colorPrice($colorId,$currId,$productId);
        $colorPrice=floatval($colorPrice['proclr_price']);
        $customPrice = 0;
        if(($scaleId == '0' || empty($scaleId)) && $customId !='0' ){
            $customPrice = $this->customSizePrice($productId,$currId);
        }

        $total=($productPrice+$scalePrice+$colorPrice+$customPrice);

        if($symbol=='after')
            return $total.$betweenSymbol.$currSymbol;
        else if($symbol=='before')
            return $currSymbol.$betweenSymbol.$total;
        else
            return $total;
    }


    /**
     * @param $productId
     * @param $currId
     * @return int|mixed
     */
    public function productPrice($productId,$currId=''){
        if($currId==''){
            $currCountry   =  $this->functions->ibms_setting('Default Web_Price_Country');
            $currData      =   $this->currencyInfo($currCountry);
            $currId        =   $currData['cur_id'];
        }
        $sql ="SELECT * FROM `product_price` WHERE  `propri_prodet_id` = '$productId' AND `propri_cur_id` = '$currId'";
        $data=$this->dbF->getRow($sql);
        if($this->dbF->rowCount>0)return $data;
        else return 0;
        //return
        /**
         * propri_id
         * propri_prodet_id
         * propri_cur_id
         * propri_price
         * propri_intShipping
         * propri_timeStamp
         */
    }

    /**
     * @param $scaleId
     * @param $currId
     * @param $pid
     * @return int|mixed
     */
    public function scalePrice($scaleId,$currId,$pid){
        /*
         * get product size name, then get product size price base on product size name. because only first product size name id by group ASC always remain constant,
        */
        $sql="SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id` = '$scaleId'";
        $temp=$this->dbF->getRow($sql);
        $scaleName=$temp['prosiz_name'];
        $sql="";

        $sql ="SELECT `prosiz_id`,`prosiz_price`,`sizeGroup`
                FROM `product_size`
                WHERE  `prosiz_prodet_id` = '$pid' AND `prosiz_name` = '$scaleName' AND `prosiz_cur_id` = '$currId'";
        $data=$this->dbF->getRow($sql);
        if($this->dbF->rowCount>0)return $data;
        else return 0;
    }

    /**
     * @param $colorId
     * @param $currId
     * @param $pid
     * @return int|mixed
     */
    public function colorPrice($colorId,$currId,$pid){
        /*
        * get product color name, then get product color price base on product color name. because only first product color name id by group ASC always remain constant,
       */
        $sql="SELECT `proclr_name` FROM `product_color` WHERE `propri_id` = '$colorId'";
        $temp=$this->dbF->getRow($sql);
        $colorName=$temp['proclr_name'];
        $sql="";

        $sql ="SELECT `propri_id`,`proclr_price`,`sizeGroup` FROM `product_color` WHERE `proclr_prodet_id` = '$pid' AND `proclr_name` = '$colorName' AND `proclr_cur_id` = '$currId'";
        $data=$this->dbF->getRow($sql);

        if($this->dbF->rowCount>0)return $data;
        else return 0;
    }

    public function scaleInventory($pId,$storeId,$colorGreaterThenZero = true){
        //gives inventory data,
        if($colorGreaterThenZero==true){
            //mean color has stock,,,
            $colorGreaterThenZero = "AND qty_product_color > '0'";
        }else{
            //mean color not allow in project,,,
            $colorGreaterThenZero = "AND qty_product_color = '0'";
        }

        $sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
                                    AND qty_product_id = '$pId'
                                    $colorGreaterThenZero
                                    AND qty_item > 0";
        $data = $this->dbF->getRows($sql);
        return $data;
    }

    public function colorInventory($pId,$currency,$storeId,$scaleGreaterThenZero =true){
        if($scaleGreaterThenZero==true){
            //mean scale has stock,,,
            $scaleGreaterThenZero = "AND qty_product_scale = '0'";
        }else{
            //mean scale not allow in project,,,
            $scaleGreaterThenZero = "AND qty_product_scale > '0'";
        }

        $sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
                                    AND qty_product_id = '$pId'
                                    $scaleGreaterThenZero
                                    AND qty_item > 0";
        $data = $this->dbF->getRows($sql);
        return $data;
    }

    public function hasInventoryInScale($inventory,$scaleId){
        foreach($inventory as $key=>$val){
            if($val['qty_product_scale'] == $scaleId && intval($val['qty_item'])>0){
                return $key;
            }
        }
        return false;
    }


    public function scaleWithInventory($pId,$currency,$storeId,$hasColor=false){
        //take Complete scale data,,, with pId, then filter with scaleInventory
        //and return with new array
        $scaleData = $this->scaleSQL($pId,'*',false);
        $scaleInventory = $this->scaleInventory($pId,$storeId,$hasColor);
     // var_dump($scaleInventory);
     // var_dump($scaleData);

        $scaleNameThatHasInventory = array();
        $scaleData2 = $scaleData; //for inside of loop usage
        $scales_names = array();
        foreach($scaleData as $key=>$val){
            $scaleId    =   $val['prosiz_id'];
            $name       =   $val['prosiz_name'];
            $hasInventoryInScale = $this->hasInventoryInScale($scaleInventory,$scaleId);
            if(in_array($name,$scales_names)) continue;

            if($hasInventoryInScale !== false ){
                $hasInventoryInScale = intval($hasInventoryInScale);
                $scaleNameThatHasInventory[$name]['name']   =   $name;
                $scaleNameThatHasInventory[$name]['id']     =   $scaleInventory[$hasInventoryInScale]['qty_pk'];
                $scaleNameThatHasInventory[$name]['scaleId']=   $scaleInventory[$hasInventoryInScale]['qty_product_scale'];
                $scaleNameThatHasInventory[$name]['qty']    =   $scaleInventory[$hasInventoryInScale]['qty_item'];
                $scales_names[] = $name;
            }else{
                //get first id of scale
                foreach($scaleData2 as $key2 => $val2){
                    $scaleId    =   $val2['prosiz_id'];
                    if($val2['prosiz_name'] == $name) {
                        $scaleNameThatHasInventory[$name]['scaleId'] = $scaleId;
                        break;
                    }
                }

            }

        }

        $scaleData2 = $scaleData;
        //filter data, and Add new arrays
        foreach ($scaleData2 as $key=>$val) {
            if($val['prosiz_cur_id'] != $currency){
                unset($scaleData[$key]);
                continue;
            }
            $scaleId    =   $val['prosiz_id'];
            $name       =   $val['prosiz_name'];

            if( isset($scaleNameThatHasInventory[$name]['name']) ){
                $scaleData[$key]['hasInventory']        = "1";
                $scaleData[$key]['inventoryId']         = $scaleNameThatHasInventory[$name]['id'];
                $scaleData[$key]['inventoryQty']        = $scaleNameThatHasInventory[$name]['qty'];
                $scaleData[$key]['inventoryScaleId']    = $scaleNameThatHasInventory[$name]['scaleId'];
            }else{
                $scaleData[$key]['hasInventory']        = "0";
                $scaleData[$key]['inventoryScaleId']    = $scaleNameThatHasInventory[$name]['scaleId'];
            }
        }
        return $scaleData;

    }



    /**
     * @param $countryPK
     * @return mixed|string
     */
    public function currencyInfo($countryPKorId,$id=false){
        if($id==false) {
            $sql = "SELECT * FROM `currency` WHERE  `cur_country` = '$countryPKorId'";
        }else{
            $sql = "SELECT * FROM `currency` WHERE  `cur_id` = '$countryPKorId'";
        }

// echo $sql;

        $data=$this->dbF->getRow($sql);
        if($this->dbF->rowCount>0)return $data;
        else return "";
    }

    public function currencySymbol($currencyId){
        $sql ="SELECT cur_symbol FROM `currency` WHERE  `cur_id` = '$currencyId'";
        $data=$this->dbF->getRow($sql);
        if($this->dbF->rowCount>0)return $data['cur_symbol'];
        else return "";
    }
    /**
     * @return MultiArray
     */
    public function stockProductInventory(){
        $sql = "SELECT * FROM  `product_inventory` ORDER BY `qty_store_id`,`qty_pk` ASC";
        $data =$this->dbF->getRows($sql);
        return $data;
    }

    /**
     * @param $hash
     * @return mixed
     */
    public function stockProductQty($hash){
        $sql    =   "SELECT `qty_item` FROM `product_inventory` WHERE `product_store_hash` = '$hash'";
        $inventoryData = $this->dbF->getRow($sql);
        $invQty =   $inventoryData[0];
        return $invQty;
    }

    /**
     * @param $hash
     * @param $qty
     * @return bool
     */
    public function stockProductQtyMinus($hash,$qty){
        $sql    =   "UPDATE `product_inventory` SET `qty_item` = qty_item-'$qty' WHERE `product_store_hash` = '$hash'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount > 0)
            return true;
        return false;
    }

    /**
     * @param $hash
     * @param $qty
     * @return bool
     * @todo used in product return in returns.php
     */
    public function stockProductQtyPlus($hash,$qty){
        $sql    =   "UPDATE `product_inventory` SET `qty_item` = qty_item+'$qty' WHERE `product_store_hash` = '$hash'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount > 0)
            return true;
        return false;
    }

    public function productShipping($productId,$currId){
        return $this->productPrice($productId,$currId);
    }

    /*    public function AjaxEditScript($class,$name){

            echo "<script>$('.$class').click(function(){
            $('.save_button').hide();
            $('.save_button .text').html('Update');
            $('.save_button .success, .save_button .fail').hide();

            $('.modal-body').html(loading_progress());
            $('#".$name."EditModal').modal('show');

            id=$(this).attr('data-id');

                $.ajax({
                 type: 'POST',
                  url: 'product_management/product_ajax.php?page=$class&id='+id
                }).done(function(data)
                {
                   if(data!='0'){
                     setTimeout(function(){
                        $('.modal-body').hide().html(data).show(500);
                         $('.save_button').show(500);
                         list_count2 = Number($('#slot_table2 > tbody > tr').length);
                     },".$this->functions->setTimeOutLocal.");
                    }
                });


            });
           </script>";

        }*/

    /**
     * @param $class
     * @param $name
     */
    public function AjaxEditScript($class,$name){

        echo "<script>$('.$class').click(function(){
                    // AjaxEditScript(this);
                    //Function is change, if any where i forget to update function then this alert will open, dont remove this line,
                    //just go to that place and remove class and write this function.
                    alert('Asad Change function to onclickfunction go to edit <a> and write onclick=ajaxEditScript(this);');
                });

         AjaxEditScript = function(ths){
            $('.save_button').hide();
            $('.save_button .text').html('Update');
            $('.save_button .success, .save_button .fail').hide();

            $('.modal-body').html(loading_progress());
            $('#".$name."EditModal').modal('show');

            id=$(ths).attr('data-id');

                $.ajax({
                 type: 'POST',
                  url: 'product_management/product_ajax.php?page=$class&id='+id
                }).done(function(data)
                {
                   if(data!='0'){
                     setTimeout(function(){
                        $('.modal-body').hide().html(data).show(500);
                         $('.save_button').show(500);
                         list_count2 = Number($('#slot_table2 > tbody > tr').length);
                     },".$this->functions->setTimeOutLocal.");
                    }
                });
        };
       </script>";

    }


    /**
     * @param $class
     * @param $name
     */
    public function AjaxUpdateScript($class,$name){
        global $_e;
        echo "<script>
                function AjaxUpdateScript(ths){

                btn=$(ths);
                id=$('#".$name."_edit_id').val();
                $(ths).button('loading');
                 url= 'product_management/product_ajax.php?page=$class';
                $.post(url, $('#".$name."_update').serialize()
                                ,function( data ) {
                                    if(data=='1'){

                                      btn.button('reset');
                                      btn.children('.success').show(100);
                                      btn.children('.text').html('". _js($_e['Update Done']) ."');
                                      $('.'+id+'_name').text($('#".$name."_name').val());
                                      AjaxAfterUpdateScript(id);
                                      setTimeout(function(){
                                        $('#".$name."EditModal').modal('hide');
                                      },700);

                                    }else{
                                       btn.button('reset');
                                       btn.children('.fail').show(100);
                                       btn.children('.text').html('". _js($_e['Update Fail']) ."');
                                    }
                                });

                 };

                function AjaxAfterUpdateScript(id){
                    $('.'+id+'_".$name."').html(loading_progress());
                    $.ajax({
                     type: 'POST',
                      url: 'product_management/product_ajax.php?page=AjaxAfterUpdateScript_".$name."&id='+id
                    }).done(function(data)
                    {
                       if(data!='0'){
                         setTimeout(function(){
                            $('.'+id+'_".$name."').html(data);
                         }," .$this->functions->setTimeOutLocal.");
                }
            });
        }
       </script>";

    }


    /**
     * @param $class
     * @param $name
     */
    public function AjaxDelScript($class,$name){
        global $_e;
        echo "<script>
        function AjaxDelScript(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

            id=btn.attr('data-id');

                $.ajax({
                 type: 'POST',
                  url: 'product_management/product_ajax.php?page=$class&id='+id,
                  data: { itemId:id }
                }).done(function(data)
                {
                   if(data=='1'){
                     setTimeout(function(){
                        btn.closest('tr').hide(1000,function(){\$(this).remove()});
                     },".$this->functions->setTimeOutLocal.");
                    }
                    else if(data=='0'){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                        jAlertifyAlert('".ucwords($name)." ". _js($_e['Delete Fail Please Try Again.']) ."');
                    }
                   else{
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                        btn.append(data);
                    }
                });

            }else{

            }

        };
       </script>";
    }


    public function modal($title,$name,$body=false){
        global $_e;
        echo '<!-- Modal -->
            <div class="modal fade" id="'.$name.'EditModal" tabindex="1" role="dialog" aria-labelledby="'.$name.'EditModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="'.$name.'EditModalLabel">'.$title.'<Edit></Edit></h4>
                        </div>
                        <form action="" method="post" id="'.$name.'_update">
                            <div class="modal-body">
                    Loading...
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary save_button" data-loading-text="Saving..." onclick="AjaxUpdateScript(this);">
                                    <i class="fa fa-check-square-o success" style="display: none"></i>
                                    <i class="fa fa-exclamation-triangle fail" style="display: none"></i>
                                    <span class="text">'. _uc($_e['Update']) .'</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>';
    }


    public function hasStock($pId){
        $sql    =   "SELECT qty_pk FROM `product_inventory` WHERE qty_product_id = '$pId' AND qty_item > '0'";
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0)
            return true;
        return false;
    }

    /**
     * @param string $columnName
     * @return MultiArray
     */
    public function productSQL($columnName='*',$AllWithNewBlank=true){
        $all ='';
        if($AllWithNewBlank===true){
            $all = "WHERE `product_update` = '1' ";
        }else{
            $all    =   $AllWithNewBlank;
        }
        $sql="SELECT ".$columnName." FROM `proudct_detail` $all ORDER BY `sort` ASC";
        return $this->dbF->getRows($sql);
    }

    /**
     * @param $id
     * @param string $columnName
     * @return MultiArray
     */
    public function scaleSQL($id,$columnName='*',$groupByName = true){
        if($groupByName){
            $groupByName = "GROUP BY `prosiz_name`";
        }else{
            $groupByName = "";
        }
        $sql="SELECT ".$columnName." FROM `product_size` WHERE `prosiz_prodet_id` = '$id' $groupByName ORDER BY prosiz_id ASC";
        return $this->dbF->getRows($sql);
    }

    /**
     * @param $id
     * @param string $columnName
     * @return MultiArray
     */
    public function scaleSQLByCurrency($id,$currency,$columnName='*'){
        $sql="SELECT ".$columnName." FROM `product_size` WHERE `prosiz_prodet_id` = '$id' AND `prosiz_cur_id` = '$currency' ORDER BY prosiz_id ASC";
        return $this->dbF->getRows($sql);
    }

    /**
     * @param $pId
     * @param bool $echo
     * @param bool $returnArray
     */
    public function productCategory($pId,$echo = true){
        $sql  = "SELECT procat_cat_id FROM `product_category` WHERE procat_prodet_id = '$pId'";
        $data = $this->dbF->getRows($sql);
        if($this->dbF->rowCount>0){
            $category = "";
            foreach($data as $val){
                $category .= $val['procat_cat_id'].",";
            }
            $category = trim($category,',');
            if($echo)
                echo $category;
            else
                return $category;
        }else{
            return false;
        }

    }
    /**
     * @param string $columnName
     * @return MultiArray
     */
    public function storeSQL($columnName='*'){
        $sql="SELECT ".$columnName." FROM `store_name` ORDER BY `store_pk` ASC";
        return $this->dbF->getRows($sql);
    }

    /**
     * @param $id
     * @param string $columnName
     * @return MultiArray
     */
    public function colorSQL($pId,$columnName='*'){
        $sql="SELECT ".$columnName." FROM `product_color` WHERE `proclr_prodet_id` = '$pId' GROUP BY `proclr_name` ORDER BY propri_id ASC";
        return $this->dbF->getRows($sql);
    }


    /**
     * echo json data
     */
    public function productJSON(){
        $product=$this->productSQL('`prodet_id`,`prodet_name`',true);
        $defaultLang= $this->functions->AdminDefaultLanguage();
        $JSON='[';


        if($this->dbF->rowCount>0){
            $JSON2 ='';
            foreach($product as $val){

                $id=$val['prodet_id'];
                $name=translateFromSerialize($val['prodet_name']);

                $scle=$this->scaleSQL($id,'`prosiz_id`,`prosiz_name`');

                if($this->dbF->rowCount>0){
                    $SCALE = '[';

                    $temp='';
                    foreach($scle as $sval){
                        $temp .='{id : "'.$sval['prosiz_id'].'",label : "'.$sval['prosiz_name'].'"},';
                    }
                    $temp= trim($temp,',');
                    $SCALE .=$temp;

                    $SCALE .= ']';
                }else{
                    $SCALE = 'null';
                }



                $colr=$this->colorSQL($id,'`propri_id`,`proclr_name`');

                if($this->dbF->rowCount>0){
                    $COLOR = '[';

                    $temp='';
                    foreach($colr as $cval){

                        $temp .='{id : "'.$cval['propri_id'].'",label : "'.$cval['proclr_name'].'"},';
                    }
                    $temp= trim($temp,',');
                    $COLOR .=$temp;

                    $COLOR .= ']';
                }else{
                    $COLOR = 'null';
                }

                $JSON2 .='{
                        id : "'.$id.'",
                        label : "'.$name.'",
                        scale : '.$SCALE.',
                        color : '.$COLOR.'
                        },';
            }
            $JSON2 = trim ($JSON2,',');
            $JSON .= $JSON2;

        }
        $JSON .= ']';
        echo $JSON;

    }

    public function paymentSelect(){
        //Payment type select box create
        $payment = $this->paymentArray();
        $option='';
        foreach($payment as $key=>$val){
            $option.= "<option value='$key'>$val</option>";
        }
        return $option;
    }

    public function paymentArrayFind($id){
        $id =   intval($id);
        $invoice = $this->paymentArray();
        return $invoice[$id];
    }
    public function paymentArray(){
        global $_e;
        //Alert Don't change array key Value, If you want new array value, use new key for new value,,
        //On array key processing are working.
        $arr=array(
            0=>_uc($_e['Cash On Delivery'])
        );
        return $arr;
    }

    public function paymentArrayFindWeb($id){
        $id =   intval($id);
        $invoice = $this->paymentArrayWeb();
        return $invoice[$id];
    }

    public function paymentArrayWeb(){
        global $_e;
        //$arr=array(0=>"Cash On Delivery",1=>"PayPal");
        //Alert Don't change array key Value, If you want new array value, use new key for new value,,
        //On array key processing are working.
        // $arr=array(0=>$temp1T,1=>"PayPal",2=>"Klarna",3=>$temp2T);

        //Alert Don't change array key, Or dont use key for new value, If you want new payment method, use new key for new value,,
        //On array key processing are working.
        $arr = array(
            0 => _uc($_e['Cash On Delivery']),
            1 => "PayPal",
            2 => "Klarna",
            3 => _uc($_e['CreditCard']),
            4 => _uc($_e['Paid']),
            5 => "Payson",
            6 => _uc($_e['Gift Card']),
            7 => _uc($_e['2 CheckOut']),
        );
        return $arr;
    }

    public function paymentSelectWeb(){
        //Payment type select box create
        $payment = $this->paymentArrayWeb();
        $option='';
        foreach($payment as $key=>$val){
            $option.= "<option value='$key'>$val</option>";
        }
        return $option;
    }

    public function paymentProcess($index){
        //Payment type select box create
        switch($index){
            // case is index or payment array
            case 0:

                break;
            case 1:

                break;
            case 2:

                break;
        }

    }

    public function invoiceStatus(){
        //Invoice status select box create
        $invoice = $this->invoiceStatusArray();
        $option='';
        foreach($invoice as $key=>$val){
            $option.= "<option value='$key'>$val</option>";
        }
        return $option;
    }


    public function invoiceStatusArray(){
        global $_e;
        //Alert Don't change array key Value, If you want new array value, use new key for new value,,
        //On array key processing are working.
        $arr=array(
            11=>_uc($_e['Received']),
            2=>_uc($_e['Pending']),
            5=>_uc($_e['Ready For Packaging']),
            0=>_uc($_e['Cancel']),
            9=>_uc($_e['Partial Delivery Done']),
            6=>_uc($_e['Full Refunded']),
            10=>_uc($_e['Awaiting Measures From Customer']),
            7=>_uc($_e['Order send for factory']),
            3=>_uc($_e['Complete']),
            1=>_uc($_e['Denied']),
            /*8=>_uc($_e['Measure send to factory']),*/
        );
        //$arr=array(0=>"Cancel",1=>"Denied",2=>"Pending",3=>"Done");
        /*
         *
            0=>_uc($_e['Cancel']),
            1=>_uc($_e['Denied']),
            2=>_uc($_e['Pending']),
            3=>_uc($_e['Complete']),
            4=>"Not Received",
            5=>_uc($_e['Ready For Packaging']),
            6=>_uc($_e['Full Refunded']),
            7=>_uc($_e['Order send for factory']),
            8=>_uc($_e['Measure send to factory']),
            9=>_uc($_e['Partial Delivery Done']),
            10=>_uc($_e['Awaiting Measures From Customer']),
         */

        return $arr;
    }


    public function invoiceStatusFind($id){
        $id =   intval($id);
        $invoice = $this->invoiceStatusArray();
        return $invoice[$id];
    }

    /**
     * @param $country
     * @return int|mixed
     */
    public function productCountryId($country){
        $sql ="SELECT * FROM `currency` WHERE  `cur_country` = '$country'";
        $data=$this->dbF->getRow($sql);
        /* if($this->dbF->rowCount>0)return $data['cur_id'];
        previous code */
        if($this->dbF->rowCount>0)return $data;
        else return 0;
    }


    /*
    /notice that product sale function give same result again and again,,
    so stop repeating excution. same data in variable
    */
    private $productSaleData = false;
    public function productSale($pId,$countryId,$discount){
        //reutrn product sale info price...
        $today  = date('Y-m-d');
//get sale offer
        $sql = "SELECT product_sale.* , product_sale_prices.* FROM product_sale
                product_sale join product_sale_prices
                on `product_sale`.`pSale_pk` = `product_sale_prices`.`pSale_price_id`
                 WHERE pSale_status = '1'
                      AND pSale_category != ''
                      AND pSale_from <= '$today'
                      AND (pSale_to  >= '$today' OR pSale_to  = '')
                      AND pSale_price_curr_Id = '$countryId'
                      AND pSale_price_intShipping = '1'
                       ORDER BY pSale_from DESC";

        if($this->productSaleData==false) {
            $data = $this->dbF->getRow($sql);
            $this->productSaleData = $data;
        }else{
            $data = $this->productSaleData;
        }
        //var_dump($data);

        if(!empty($data) && $data !=false && $this->productSaleData !=false){

            if(intval($data['pSale_discount']) === 0){
                //if both sale and discount then discount apply
                if(!empty($discount) && $discount !== false){
                    return false;
                }
            }

            $category = $data['pSale_category'];
            $categoryArray  = explode(',',$category);

            $productCategory = $this->productCategory($pId,false);
            $productCategoryArray = explode(',',$productCategory);

            //check if this sale apply on product
            $find = false;
            foreach($categoryArray as $arry){
                if(in_array($arry,$productCategoryArray)){
                    $find = true;
                    break;
                }
            }

            if($find==false){ return false; }
            //find true mean sale offer apply on this product
            $saleId = $data['pSale_pk'];
            $sql = " SELECT pSale_setting_value FROM `product_sale_setting` WHERE pSale_id = '$saleId' AND pSale_setting_name = 'discountFormat' ";
            $setting  =  $this->dbF->getRow($sql);
            if($this->dbF->rowCount>0){
                $return = array();
                $return['discount']         =   $data['pSale_price_price'];
                $return['discountFormat']   =   $setting['pSale_setting_value'];
                $return['isSale']           =   '1';
                return $return;
            }

            return false;
        }else{
            return false;
        }
    }

    public function productDiscountFirst($pId,$countryId){
        //get product id run query and return discount price
        $today  = date('Y-m-d');
        $sql ="SELECT `product_discount`.*, `product_discount_prices`.*  FROM
                  `product_discount` join `product_discount_prices`
                  on `product_discount`.`product_discount_pk` = `product_discount_prices`.`product_dis_id`
                  WHERE product_dis_status = '1'
                          AND `product_discount_pk` in
                                (SELECT product_dis_id FROM product_discount_setting WHERE
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateFrom' AND `product_dis_value` <= '$today') AND
                                product_dis_id in (SELECT product_dis_id FROM `product_discount_setting` WHERE `product_dis_name` ='dateTo' AND (`product_dis_value` >= '$today' OR `product_dis_value` = '')))
                          AND `product_discount_prices`.product_dis_curr_Id = '$countryId' AND product_discount_prices.product_dis_intShipping = '1'
                          AND discount_PId = '$pId' ";



// var_dump($sql);


        $data = $this->dbF->getRow($sql);
        $return = array();
        if($this->dbF->rowCount>0){
            $sql ="SELECT * FROM `product_discount_setting`  WHERE product_dis_id = '$data[product_discount_pk]'
                    AND product_dis_name = 'discountFormat' ";
            $settingData    =   $this->dbF->getRow($sql);
            $return['discount']         =   $data['product_dis_price'];
            $return['discountFormat']   =   $settingData['product_dis_value'];
            return $return;
        }
        return $return;
    }



    public function discountPriceCalculation($pPrice,$discountArray,$returnNewPrice=false){
        if(!empty($discountArray)){
            $discount       =   $discountArray['discount'];
            $discountFormat =   $discountArray['discountFormat'];
            if($discountFormat=='price'){
                // $discount   =   $price-$discount;
                //return discount price will calculate on page function
            }else if($discountFormat=='percent'){
                $discount   =   ($pPrice*$discount)/100;
                $discount   =   floor($discount);
                //$discount   =   floor($discount*100)/100;
            }
        }else{
            $discount   = 0;
        }
        if($returnNewPrice)
            return $pPrice-$discount;
        return $discount;
    }

    public function productDiscount($pId,$countryId,$coupon = ''){
        /*
         * $countryId is $currency id,
         * Get product id run query and return discount price
            $return['discount']         =   $data['price'];
            $return['discountFormat']   =   $settingData['deduct in percent or price'];

        first discount find,. then sale discount or calculation in sale function, return array
        */
        $return = array();

        $isSale = '0';
        $discount = $this->productDiscountFirst($pId,$countryId);



// var_dump($discount);

        $sale     = $this->productSale($pId,$countryId,$discount);

        if($sale!=false){
            $isSale = '1';
            $return = $sale;
        }else{
            $return = $discount;
        }

        if($coupon!=''){
            $coupon = $this->productCoupon($coupon,$pId,$countryId);
            if($coupon!=false){
                if($coupon['applyOn']=='0'){
                    $return = $coupon;
                }elseif($coupon['applyOn']=='1' && !empty($discount) && $discount !=false){
                    $return = $discount;
                }elseif($coupon['applyOn']=='2' && $sale !=false && !empty($sale)){
                    $return = $sale;
                }else{
                    $return = $coupon;
                }
            }
        }

        #### making tooltip div here
        if ( $discount ) {
            $return['tooltip'] = $this->tooltip_div('discount');
        } elseif ( $sale ) {
            $return['tooltip'] = $this->tooltip_div('sale');
        } elseif ( $coupon != '' ) {
            $return['tooltip'] = $this->tooltip_div('coupon');
        }

        

        if( ! $return==false && !empty($return)) {
            $return['isSale'] = $isSale;
        }
        return $return;
    }

    public function productCouponStatus($couponCode){
        if($couponCode==''){
            return false;
        }
        $today  = date('Y-m-d');
        $return = array();
        $user_type = getUserSession("user_type");
        if(!empty($user_type) || $user_type != "basic"){
            $user_coupon_type = " OR  pCoupon_type = '$user_type'";
        }
        //get sale offer
        $sql = "SELECT product_coupon.* , product_coupon_prices.* FROM product_coupon
                product_coupon join product_coupon_prices
                on `product_coupon`.`pCoupon_pk` = `product_coupon_prices`.`pSale_price_id`
                 WHERE pCoupon_status = '1'
                      AND  pCoupon_name = '$couponCode'
                      AND pCoupon_from <= '$today'
                      AND (pCoupon_to  >= '$today' OR pCoupon_to  = '')
                      AND (pCoupon_type = 'basic' OR pCoupon_type = '' $user_coupon_type )
                      AND pSale_price_intShipping = '1'
                       ORDER BY pCoupon_from DESC";
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0)
            return true;
        return false;
    }

    public function productCoupon($couponCode,$pId,$countryId){
        $today  = date('Y-m-d');
        $return = array();

        $user_type = getUserSession("user_type");
        if(!empty($user_type) || $user_type != "basic"){
            $user_coupon_type = " OR  pCoupon_type = '$user_type'";
        }
        //get sale offer
        $sql    = "SELECT product_coupon.* , product_coupon_prices.* FROM product_coupon
                  product_coupon join product_coupon_prices
                  on `product_coupon`.`pCoupon_pk` = `product_coupon_prices`.`pSale_price_id`
                    WHERE pCoupon_status = '1'
                      AND pCoupon_name = '$couponCode'
                      AND pCoupon_from <= '$today'
                      AND (pCoupon_to  >= '$today' OR pCoupon_to  = '')
                      AND pSale_price_curr_Id = '$countryId'
                      AND pSale_price_intShipping = '1'
                      AND (pCoupon_type = 'basic' OR pCoupon_type = '' $user_coupon_type )
                       ORDER BY pCoupon_from DESC";

        $data   =   $this->dbF->getRow($sql);
        //var_dump($data);

        if($this->dbF->rowCount>0){
            $category = $data['pCoupon_category'];
            $categoryArray = explode(',',$category);

            $productCategory = $this->productCategory($pId,false);
            $productCategoryArray = explode(',',$productCategory);

            //check if this sale apply on product
            $find = false;
            foreach($categoryArray as $arry){
                if(in_array($arry,$productCategoryArray)){
                    $find = true;
                    break;
                }
            }

            if($find==false){ return false; }
            //find true mean sale offer apply on this product
            $saleId = $data['pCoupon_pk'];
            if($this->dbF->rowCount>0){

                $return['discount']         =   $data['pSale_price_price'];
                $return['discountFormat']   =   $data['pCoupon_format'];
                $return['applyOn']          =   $data['pCoupon_discount'];
                //0 coupon, 1 discount, 2 hole sale.
                return $return;
            }
            return false;
        }else{
            return false;
        }

        return 0;
    }

    public function productCurrencySelectCurrency(){
        $data       = $this->productCurrencySql();
        $countries  = $this->functions->countrylist();
        $option     = '';
        foreach($data as $val){
            $cr     = $val['cur_country'];
            $option .= "<option value='$val[cur_country]'>$countries[$cr] ($val[cur_symbol])</option>";
        }
        return $option;
    }
    public function productCurrencyCountries(){
        $data       = $this->productCurrencySql();
        $countries  = $this->functions->countrylist();
        $option     = '';
        foreach($data as $val){
            $cr     = $val['cur_country'];
            $option .= "<option value='$val[cur_country]'>$countries[$cr]</option>";
        }
        return $option;
    }

    public function productCurrencysymbol($countryCode){
        $sql        = "SELECT `cur_symbol` FROM `currency` WHERE cur_country = '$countryCode'";
        $data = $this->dbF->getRow($sql);
        return $data[0];

    }

    public function productCurrencySql(){
        $sql        = "SELECT * FROM `currency` ORDER BY `cur_country` Asc";
        return $this->dbF->getRows($sql);
    }

    public function getCategoryNames($categories,$echo =true){
        if($categories!=''){
            $sql        = "SELECT nm FROM `tree_data`  WHERE id in ($categories) ORDER BY `nm` Asc";
            $data       = $this->dbF->getRows($sql);
            $names   = "";
            if($this->dbF->rowCount>0)
                foreach($data as $val){
                    $names  .= $val['nm'].", ";
                }
            $names  =   trim($names,', ');

        }else{
            $names   = "";
        }
        return $names;

    }

    public function userNoOfItemsInCart($userId){
        $sql    =   "SELECT sum(qty) as qty FROM cart WHERE userId = '$userId'";
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0){
            if($data['qty']=='')return '0';
            return $data['qty'];
        }
        return '0';
    }

    public function totalOrderSubmit($userId=false,$invoiceStatus=false){
        $user   =   false;
        if($userId===false || $userId==''){
            $userId =   '';
        }else{
            $user = true;
            $userId =   "order_user = '$userId'";
        }

        if($invoiceStatus===false || $invoiceStatus==''){
            $invoiceStatus =   '';
        }else{
            $and = '';
            if($user){
                $and = ' AND ';
            }
            if($invoiceStatus){
                $invoiceStatus = " =  '$invoiceStatus'";
            }
            $invoiceStatus =   " $and order_status $invoiceStatus";
        }

        $sql    =   "SELECT count(order_id) as qty FROM orders WHERE $userId $invoiceStatus";
        $sql    =   trim($sql);
        $sql    =   trim($sql,'WHERE');
        $data   =   $this->dbF->getRow($sql);
        if($this->dbF->rowCount>0){
            if($data['qty']=='')return '0';
            return $data['qty'];
        }else{
            return '0';
        }
    }

    public function productLastImage($id){
        $sql ="SELECT * FROM `product_image` WHERE product_id = '$id' ORDER BY sort ASC ";
        $data = $this->dbF->getRow($sql);
        return $data['image'];
    }

    public function productSpecialImage($id,$alt,$defaultFirstImageShowForMain=true){
        $sql ="SELECT * FROM `product_image` WHERE product_id = '$id' AND alt = '$alt' ORDER BY sort ASC ";
        $data = $this->dbF->getRow($sql);
        $imag = $data['image'];
        if($imag==''){
            if($defaultFirstImageShowForMain && strtolower($alt)=='main'){
                $sql ="SELECT * FROM `product_image` WHERE product_id = '$id' ORDER BY sort ASC ";
                $data = $this->dbF->getRow($sql);
                $imag = $data['image'];
            }
        }
        return $imag;
    }

    /**
     * function execute from cronTask.php, purpose is when product will available for sale discount, then notification show to user through email
     * @param $pId
     * @return bool
     */
    public function productOnSaleTrigger($pId){
        $saleTriggerLetter = 'salesTriggerMail';
        //get letter id
        $sql    = "SELECT id FROM  email_letters WHERE `email_type` = '$saleTriggerLetter'";
        $dataLetter   = $this->dbF->getRow($sql);
        if(empty($dataLetter)){
            return false;
        }
        $letterId = $dataLetter['id'];

        $sql    = "SELECT email FROM  product_subscribe WHERE `p_id` = '$pId' AND `type` = 'sale' ";
        $data   = $this->dbF->getRow($sql);
        /*Just check again is any email subscribe or not.*/
        if( empty($data) ){
            return false;
        }

        $sql    =   "INSERT INTO email_letter_queue(`letter_id`,`grp`,`email_name`,`email_to`,`p_id`,`status` )
                        SELECT '$letterId','SalesTrigger','',`email`,`p_id`,'1' FROM  product_subscribe WHERE `p_id` = '$pId' AND `type` = 'sale' ";
        $this->dbF->setRow($sql);

        //run cron job
        $this->functions->cronJob();

        $sql    = "DELETE FROM  product_subscribe WHERE `p_id` = '$pId' AND `type` = 'sale'";
        $this->dbF->setRow($sql);

    }

    /**
     * Function execute from cronTask.php, purpose is when product will available for sale discount, then notification show to user through email
     * @param $pId
     * @return bool
     */
    public function product_in_stock_trigger($pId,$store_id,$scale_id,$color_id){
        $stockTriggerLetter = 'stockTriggerMail';
        //get letter id
        $sql    = "SELECT id FROM  email_letters WHERE `email_type` = '$stockTriggerLetter'";
        $dataLetter   = $this->dbF->getRow($sql);
        if( empty($dataLetter) ){
            return false;
        }
        $letterId = $dataLetter['id'];

        $sql    = "SELECT email FROM  product_subscribe WHERE `p_id` = '$pId' AND store_id = '$store_id' AND scale_id = '$scale_id' AND color_id = '$color_id' AND `type` = 'stock' ";
        $data   = $this->dbF->getRow($sql);
        /*Just check again is any email subscribe or not.*/
        if( empty($data) ){
            return false;
        }

        $sql    =   "INSERT INTO email_letter_queue(`letter_id`,`grp`,`email_name`,`email_to`,`p_id`,`scale_id`,`color_id`,`store_id`,`status` )
                        SELECT '$letterId','StockTrigger','',`email`,`p_id`,`scale_id`,`color_id`,`store_id`,'1' FROM  product_subscribe WHERE `p_id` = '$pId' AND store_id = '$store_id' AND scale_id = '$scale_id' AND color_id = '$color_id' AND `type` = 'stock' ";
        $this->dbF->setRow($sql);

        //run cron job
        $this->functions->cronJob();

        $sql    = "DELETE FROM  product_subscribe WHERE `p_id` = '$pId' AND store_id = '$store_id' AND scale_id = '$scale_id' AND color_id = '$color_id' AND `type` = 'stock' ";
        $this->dbF->setRow($sql);
    }

    public function productActiveSql($column='prodet_id,prodet_name',$where='',$active='1',$orderBy='DESC',$limit=''){
        if(!empty($where)){
            $where = " AND $where";
        }
       $sql="SELECT $column
                FROM
                   `proudct_detail` join `product_setting`
                    on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                    WHERE
                        `product_setting`.`setting_name`='publicAccess'
                    AND `product_setting`.`setting_val`='$active'
                    AND `proudct_detail`.`product_update`='1'
                    $where
                    ORDER BY `proudct_detail`.`prodet_id` $orderBy $limit";

        $data = $this->dbF->getRows($sql);
        return $data;

    }

    public function buy_get_free_status($pId){
        $setting = $this->getProductSetting($pId);

    }


    /**
     * @param $orderId
     * @param $setting_name
     * @param $setting_val
     * @param int $info_id any id that you want to save pId,Or orderPid, orderInfoId
     * @param string $info any extra info you want to save
     */
    public function set_order_invoice_record($orderId, $setting_name, $setting_val, $info_id = 0, $info = '') {
        //use this function where some extra info need to save in db...
        //first time use to save coupon info
        $setting_val    = is_array($setting_val) ? serialize($setting_val) : $setting_val;
        $info           = is_array($info) ? serialize($info) : $info;

        $sql = "INSERT INTO order_invoice_record(order_id,info_id,setting_name,setting_val,info) VALUES(?,?,?,?,?)";
        $this->dbF->setRow($sql, array($orderId, $info_id, $setting_name, $setting_val, $info));
    }

    public function get_order_invoice_record($invoiceId, $settingName="giftCard",$info_id = 0) {
        //in Future if this use many times,, then make a functions, to get all rows, and then save in variable, and then use on 2nd time if this function call
        if($info_id===false) {
            $sql = "SELECT * FROM `order_invoice_record` WHERE order_id = ? AND setting_name = ? ";
            $array = array($invoiceId,$settingName);
        }
        else{
            $sql = "SELECT * FROM `order_invoice_record` WHERE order_id = ? AND setting_name = ? AND info_id = ?";
            $array = array($invoiceId,$settingName,$info_id);
        }

        $data = $this->dbF->getRow($sql,$array);
        return $data;
    }

    public function buy_get_free_invoice_div($order_id,$product_id,$view= "1") {
        global $_e;
        ############ /* Buy 2 get 1 free start */
        $buy_2_get_1_free_div = "";
        if( $data = $this->has_buy_get_free_offer($order_id,$product_id) ) {
            $free_qty   = $data["setting_val"];
            $array      = unserialize($data["info"]);

            $buy_get_free_apply_limit_qty = $array["offer_limit"];
            //$product_qty = $array["buy"];
            if ($view === "1") {
                $buy_2_get_1_free_div    = "<div class='buy_get_free_css view_$view buy_2_get_1_free_div'>" . _replace("{{buy_qty}}", $buy_get_free_apply_limit_qty, $_e["Buy {{buy_qty}} Get 1 free"]) . "</div>";
                $buy_2_get_1_free_div   .=  "<div class='clearfix'></div><div class='buy_free_qty buy_get_free_css buy_2_get_1_free_div'>"._replace("{{free_qty}}","<span class='you_get_free_qty'>$free_qty</span>",$_e["You Get +{{free_qty}} free"])."</div>";
            } else if($view === "2") {
                $buy_2_get_1_free_div = "<span class='view_$view buy_2_get_1_free_div'>" ._replace("{{free_qty}}","$free_qty",$_e["+{{free_qty}} Free"])."</span>";
            }
        }
        ############ /* Buy 2 get 1 free end */
        return $buy_2_get_1_free_div;
    }

    public function has_buy_get_free_offer($order_id,$product_id) {
        ############ /* Buy 2 get 1 free start */
        $data       = $this->get_order_invoice_record($order_id,"buy_get_free",$product_id);
        $buy_get_free   = $data["setting_val"];
        if( $buy_get_free > "0" && ! empty($buy_get_free) ) {
            return $data;
        }
        ############ /* Buy 2 get 1 free end */
        return false;
    }


    /**
     * Free Gift text show in cart product,
     */
    public function free_gift_text(){
            global $_e;
            return "<div class='buy_get_free_css buy_2_get_1_free_div'>".$_e["FREE GIFT"]."</div>";
    }


    /**
     * Free Gift text show in cart product,
     */
    public function tooltip_div($type = 'coupon'){
            global $_e;
            // $_e["DISCOUNT APPLIED"], $_e["SALE OFFER APPLIED"], $_e["COUPON CODE APPLIED"]
            $discount = ' <div class="tooltip_css">
                            <img src="images/info.png" class="tooltip_discount" data-toggle="tooltip" data-placement="top"  title="'.$_e['DISCOUNT APPLIED'].'">
                         </div>';

            $sale     = ' <div class="tooltip_css">
                            <img src="images/info.png" class="tooltip_sale" data-toggle="tooltip" data-placement="top"  title="'.$_e['SALE OFFER APPLIED'].'">
                         </div>';

            $coupon   = ' <div class="tooltip_css">
                            <img src="images/info.png" class="tooltip_coupon" data-toggle="tooltip" data-placement="top"  title="'.$_e['COUPON CODE APPLIED'].'">
                          </div>';

            $checkout_offer   = ' <div class="tooltip_css">
                            <img src="images/info.png" class="tooltip_checkout" data-toggle="tooltip" data-placement="top"  title="'.$_e['CHECKOUT OFFER APPLIED'].'">
                          </div>';

            $tooltip_js = '
                        
            ';

            return $$type;                    
    }


    /**
     * get inner categories id of parent id
     * @param $parent
     * @return array
     */
    public function getSubCatIds($parent)
    {
        //4 dept first query is 2 dept.
        $sql = "SELECT * FROM `categories` WHERE id = '$parent'";
        $data = $this->dbF->getRows($sql);
        $cat = array();
        if ($this->dbF->rowCount > 0) {
            //1 2 dept
            foreach ($data as $val) {
                $id = $val['id'];
                $cat[] = $id;
                $sql = "SELECT * FROM `categories` WHERE id = '$id'";
                $data2 = $this->dbF->getRows($sql);
                if ($this->dbF->rowCount > 0) {
                    //3 dept
                    foreach ($data2 as $val2) {
                        $id = $val2['id'];
                        $cat[] = $id;
                        $sql = "SELECT * FROM `categories` WHERE id = '$id'";
                        $data3 = $this->dbF->getRows($sql);
                        foreach ($data3 as $val3) {
                            //4 dept
                            $id = $val3['id'];
                            $cat[] = $id;
                        } //4 dept end
                    }
                }//3 dept end
            }
        } //1 2 dept end

        $cat = array_unique($cat);
        return $cat;
    }

    public function product_category($pId){
        $sql = "SELECT * FROM product_category WHERE procat_prodet_id = '$pId'";
        $data =  $this->dbF->getRow($sql);

        $categories = array();
        if (!empty($data)) {
            $categories = explode(",", $data["procat_cat_id"]);
            $categories = array_map("intval", $categories);
        }

        return $categories;
    }

    public function get_three_for_two_category(){
        $three_for_2_ibm_cat = intval( $this->functions->ibms_setting("checkout_two_for_3_category") );
        if ( $three_for_2_ibm_cat > 0 ) {
            $three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
            return $three_for_2_ibm_cat;
        }else{
            return false;
        }
    }

    public function check_product_in_3_for_2($pId){
        $three_for_2_category = $this->get_three_for_two_category();
        $pro_cat    = $this->product_category($pId);

        if ( sizeof( array_intersect($three_for_2_category, $pro_cat ) ) > 0 ){
            return true;
        }
        return false;
    }

    public function get_stock_location($pId,$store_id,$scale_id,$color_id){
        $sql = "SELECT * FROM product_inventory WHERE qty_product_id = ? AND qty_store_id = ? AND qty_product_scale = ? AND qty_product_color = ? ";
        $data = $this->dbF->getRow($sql,array($pId,$store_id,$scale_id,$color_id));
        return $data['location'];
    }


      public function productLocation($pid,$storeId='0',$scaleId='0',$colorId='0',$exactValue = false){
        if($pid == '0'){
            return "";
        }
        if($storeId=='0' && $exactValue==false) $storeId =" >= '$storeId'";
        else $storeId =" = '$storeId'";

        if($scaleId=='0' && $exactValue==false) $scaleId =" >= '$scaleId'";
        else $scaleId =" = '$scaleId'";

        if($colorId=='0' && $exactValue==false) $colorId =" >= '$colorId'";
        else $colorId =" = '$colorId'";

        $sql="SELECT location FROM `product_inventory`
                WHERE `qty_product_id` = '$pid'
                    AND `qty_store_id` $storeId
                    AND `qty_product_scale` $scaleId
                    AND `qty_product_color` $colorId";
        $data = $this->dbF->getRow($sql);
        @$location=$data['location'];
        if($location=='')$location ='';
        return $location;
    }

    public function get_all_colors()
    {
        return $this->dbF->getRows(" SELECT * FROM `colors` ");
    }

    public function get_all_sizes()
    {
        return $this->dbF->getRows(" SELECT * FROM `scales` ");
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

    public function get_product_inventory_by_hash($hash){
 
        $result = false;

        $sql    =   "SELECT * FROM `product_inventory` WHERE `product_store_hash` = ? ";
        $totalQty = $this->dbF->getRow($sql,array($hash));
        if( $this->dbF->rowCount > 0 ){
            $result = $totalQty['qty_item'];
            $this->product_inventory_date_time = $totalQty['updateTime'];
        } else {
            $result = 0;
        }

        return $result;
    }

    public function product_quantity($pid){

        $sql="SELECT SUM(`qty_item`) AS QTY, MAX(`updateTime`) as update_time FROM `product_inventory`
                WHERE `qty_product_id` = '$pid'";
        $data = $this->dbF->getRow($sql);

        $qty=$data['QTY'];
        if($qty==''){ $qty ='0'; }
        return $qty;
    }


}
?>