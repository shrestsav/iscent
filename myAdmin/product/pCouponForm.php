<?php
ob_start();
$product = new product();
$coupon = new coupon();
addcoupon();
$editDiscount = false;


$discountPriceData      =   array();
$discountSettingData    =   $discountPriceData;
if(isset($_GET['sId'])){
    $sId    = $_GET['sId'];
    $discountSettingData    =   $coupon->saleSettingData($sId);
    if($discountSettingData==false){
        $editDiscount = false;
    }else{
        $discountPriceData  =   $coupon->salePriceData($sId);
        $editDiscount = true;
    }
}
global $dbF;
//var_dump($discountSettingData);


function addcoupon(){
    global $dbF;
    global $db;
    global $functions;
    global $_e;
    if(!empty($_POST)){
       //$dbF->prnt($_POST) ;
       //exit;
        if(isset($_POST['discount']) && !empty($_POST['curlist']) ){
        //    if(!$functions->getFormToken('saleForm')){ return false;}
            try{
                $db->beginTransaction();
                $editId = '';
                if($_POST['edit']!=''){
                    $editId = $_POST['edit'];
                    $sql ="DELETE FROM `product_coupon_prices` WHERE pSale_price_id = '$editId'";
                    $dbF->setRow($sql);

                    $sql ="DELETE FROM `product_coupon_setting` WHERE pCoupon_id = '$editId'";
                    $dbF->setRow($sql);

                    $sql    = "UPDATE `product_coupon` SET
                                `pCoupon_name`=?,
                                `pCoupon_from`=?,
                                `pCoupon_to`=?,
                                `pCoupon_status`=?,
                                `pCoupon_discount`=?,
                                `pCoupon_category`=?,
                                `pCoupon_country`=?,
                                `pCoupon_type`=?,
                                `pCoupon_format`=?
                               WHERE pCoupon_pk = '$editId'
                              ";
                }else{
                    $sql    = "INSERT INTO `product_coupon`
                                   (`pCoupon_name`,`pCoupon_from`, `pCoupon_to`,
                                      `pCoupon_status`, `pCoupon_discount`, `pCoupon_category`,
                                      `pCoupon_country`,`pCoupon_type`,`pCoupon_format`)
                                    VALUES
                                     (?,?,?,?,?,?,?,?,?)";
                }

                $status     =   isset($_POST['discount']['status'])     ? '1'   :   '0';
                $discount   =   isset($_POST['discount']['discount'])   ? $_POST['discount']['discount']   :   '1';
                $dateFrom   =   isset($_POST['discount']['dateFrom'])   ? $_POST['discount']['dateFrom'] : '';
                $dateTo     =   isset($_POST['discount']['dateTo'])     ? $_POST['discount']['dateTo']   : '';
                $discountFormat = $_POST['discount']['discountFormat'];
                $cats       =   $_POST['cats'];
                $coupon_type       =   $_POST['coupon_type'];
                $sale_name  =   $_POST['sale_name'];
                $shipping   =   '';

                if($_POST['discount']['discountFormat']=='freeShippingPrice' ||
                    $_POST['discount']['discountFormat']=='freeShipping' ){
                    @$freeShipping = empty($_POST['freeShipping'])?array():$_POST['freeShipping'];
                    foreach($freeShipping as $ship){
                        $shipping .= $ship.",";
                    }
                    $shipping = trim($shipping,',');
                }

                $array      =   array($sale_name,$dateFrom,$dateTo,$status,$discount,$cats,$shipping,$coupon_type,$discountFormat);
                $dbF->setRow($sql,$array,false);
                if($editId==''){
                    $lastId     =   $dbF->rowLastId;
                }else{
                    $lastId     =   $editId;
                }

                //Discount Setting
                $sql ="";
                $sql    =   "INSERT INTO `product_coupon_setting`
                            (`pCoupon_id`, `pCoupon_setting_name`, `pCoupon_setting_value`)
                                VALUES ";

                $array = "";
                $array = array();
                foreach($_POST['discount'] as $key=>$post){
                    if($key=='status' || $key=='discount' ||
                        $key=='dateFrom' || $key=='dateTo'){continue;}
                    $sql .= "(?,?,?),";
                    $array[]    =   $lastId;
                    $array[]    =   $key; //Key name use In queryies to filter
                    $array[]    =   $post;
                }
                $sql    =   trim($sql,',');
                $dbF->setRow($sql,$array,false);
                //Discount Setting End

                //Discount Prices
                $sql ="";
                $sql ="INSERT INTO `product_coupon_prices`
                         (`pSale_price_id`, `pSale_price_curr_Id`, `pSale_price_price`, `pSale_price_intShipping`)
                            VALUES ";

                $array = "";
                $array = array();
                foreach($_POST['curlist'] as $key=>$post){
                    $intShipping    =   'intShipping_'.$key;

                    $intShipping     =   isset($_POST[$intShipping])     ? '1'   :   '0';
                    //do not use any other values, because it is use in other $sql queries

                    $sql .= "(?,?,?,?),";
                    $array[]    =   $lastId;
                    $array[]    =   $key;
                    $array[]    =   empty($post)?'0':$post;
                    $array[]    =   $intShipping;
                }
                $sql    =   trim($sql,',');
                //var_dump($array);
                $dbF->setRow($sql,$array,false);
                //Discount Prices End
                $db->commit();
                if($dbF->rowCount>0) {
                    $functions->setlog(_uc($_e['Coupon']), _uc($_e['Product']), $lastId, _replace('{{id}}', $lastId, $_e['New Coupon Added With Id: {{id}}']) );
                    $functions->notificationError(_js($_e['Coupon']), _js($_e['New Coupon Add Successfully']), 'btn-success');
                }else{
                    $functions->notificationError(_js($_e['Coupon Error']),_js($_e['Coupon Add Fail Please Try Again']),'btn-danger');
                }
            }catch (Exception $e){
                $dbF->error_submit($e);
                $db->rollBack();
                $functions->notificationError(_js($_e['Coupon Error']),_js($_e['Coupon Add Fail Please Try Again']),'btn-danger');
            }
        }//if end
    }// if end isset post
} // function end

?>

    <div class="container-fluid">
<?php if(isset($_GET['sId'])){ ?>
        <h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Product Coupon Setting']); ?></h4>
    <br>
<?php } ?>

        <div class="discountForm">
            <form action="" method="post" class="form-horizontal">
                <input type="hidden" name="edit" value="<?php if($editDiscount)echo $discountSettingData[0]['pCoupon_pk'];else echo ""; ?>"/>
                <?php $functions->setFormToken('couponForm'); ?>

                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label"
                           for="saleOnOff"><?php echo _uc($_e['Coupon Status']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <div class="make-switch" data-off="warning" data-on="success">
                            <?php
                            //if edit
                            $status     =    '';
                            if($editDiscount){
                                if($discountSettingData[0]['pCoupon_status']=='1'){
                                    $status = 'checked';
                                }
                            }
                            ?>
                            <input type="checkbox" name="discount[status]"  id="saleOnOff" value="1"  <?php echo $status; ?>>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="saleName"><?php echo _uc($_e['Coupon Name']); ?></label>
                    <div class="col-sm-9 col-md-10 form-inline">
                        <?php
                        //if edit
                        $name     =    '';
                        if($editDiscount){
                            $name = $discountSettingData[0]['pCoupon_name'];
                        }
                        ?>
                        <input name="sale_name" id="saleName" class="form-control" value="<?php echo $name; ?>" placeholder="<?php echo _uc($_e['Enter Coupon Offer Name']); ?>" required="true">
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo _uc($_e['User Type']); ?></label>

                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" class="user_type" name="coupon_type" value="basic"><?php echo _uc($_e['Basic']);?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="user_type" name="coupon_type" value="gold"><?php echo _uc($_e['Gold']);?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="user_type" name="coupon_type" value="platinum"><?php echo _uc($_e['Platinum']);?>
                        </label>
                    </div>
                </div>
                <?php
                //if edit
                $coupon_type     =    '';
                if($editDiscount){
                    $coupon_type = $discountSettingData[0]['pCoupon_type'];
                }
                ?>
                <script>
                    $(document).ready(function(){
                        $(".user_type[value='<?php echo $coupon_type;?>']").attr("checked", true);
                    });
                </script>



                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="discountFrom"><?php echo _uc($_e['Active From']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <?php
                        //if edit
                        $dateFrom     =    '';
                        if($editDiscount){
                                $dateFrom = $discountSettingData[0]['pCoupon_from'];
                        }
                        ?>
                            <input type="text" value="<?php echo $dateFrom; ?>" name="discount[dateFrom]" id="discountFrom" class="form-control from" placeholder="<?php echo _uc($_e['Discount Start Date : Discount will available from start date,Leave blank To Start Now']); ?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="discountTo"><?php echo _uc($_e['Expire']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <?php
                        //if edit
                        $dateTo     =    '';
                        if($editDiscount){
                            $dateTo = $discountSettingData[0]['pCoupon_to'];
                        }
                        ?>
                            <input type="text" value="<?php echo $dateTo; ?>" name="discount[dateTo]" id="discountTo" class="form-control to" placeholder="<?php echo _uc($_e['Discount End Date: Leave blank for Always']); ?> "/>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="discountFormat"><?php echo _uc($_e['Discount Deduct In']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <?php
                        //if edit
                        $discountFormat     =    'price';
                        if($editDiscount){
                            $discountFormat = $coupon->discountArrayFound($discountSettingData,'discountFormat');
                        }
                        ?>
                        <script>
                            $(document).ready(function(){
                                $('#discountFormat').val('<?php echo $discountFormat; ?>').trigger('change');
                            });
                        </script>
                        <select name="discount[discountFormat]" id="discountFormat" class="form-control to">
                            <option value="price"><?php echo _uc($_e['In Price']); ?></option>
                            <option value="percent"><?php echo _uc($_e['In Percent %']); ?> </option>
<!--                            <option value="freeShipping">Free Shipping</option>
                            <option value="freeShippingPrice">Free Shipping On $$ Shopping</option>-->
                        </select>
                    </div>
                </div>

                <div class="form-group btn-default" id="shippingDiv" style="display: none;">
                    <label class="col-sm-3 col-md-2 control-label" for="discountFormat"><?php echo _uc($_e['Free Shipping Allow In Country']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <script>
                            $('#discountFormat').change(function(){
                                value = $('#discountFormat').val();
                                if(value=='freeShipping' || value=='freeShippingPrice'){
                                    $('#shippingDiv').slideDown(500);
                                }else{
                                    $('#shippingDiv').slideUp(500);
                                }

                                if(value=='freeShipping' || value=='freeShippingPrice'){
                                    $('#shippingDiv').slideDown(500);
                                }else{
                                    $('#shippingDiv').slideUp(500);
                                }

                                if(value=='freeShipping'){
                                    $('#priceDiv').slideUp(500);
                                }else{
                                    $('#priceDiv').slideDown(500);
                                }
                            });
                            $(document).ready(function(){
                                <?php
                                //if edit
                                $discountFormat     =    'none';
                                if($editDiscount){
                                    $discountFormat = $discountSettingData[0]['pCoupon_country'];
                                    $discountFormat = str_replace(',',',.',$discountFormat);
                                }
                                if($discountFormat==''){
                                    $discountFormat = 'none';
                                }
                                ?>
                                $('#freeShipping .<?php echo $discountFormat; ?>').attr('selected','true');

                                $("#freeShipping").bootstrapDualListbox({
                                    moveOnSelect:false
                                });
                            });
                        </script>
                        <select multiple name="freeShipping[]" id="freeShipping" class="form-control to">
                            <?php echo $functions->countrySelectOption(); ?>
                        </select>
                        <br>
                    </div>

                </div>


                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label"><?php echo _uc($_e['Product Discount']); ?></label>
                    <div class="col-sm-9 col-md-10 form-inline">
                        <?php echo _uc($_e['If Product Has Individual Discount Then Which situation apply?']); ?>
                        <br>
                        <label for="d0" class="radio-inline"><input type="radio" name="discount[discount]" id="d0" value="0" /><?php echo _uc($_e['Only Coupon Offer (Recommended)']); ?></label>
                        <label for="d1" class="radio-inline"><input type="radio" name="discount[discount]" id="d1" value="1"  /><?php echo _uc($_e['Only Product Discount Offer']); ?></label>
                        <label for="d2" class="radio-inline"><input type="radio" name="discount[discount]" id="d2" value="2"  /><?php echo _uc($_e['Only Product Whole Sale Offer']); ?></label>
                        <?php
                        //if edit
                        $discounts     =    '0';
                        if($editDiscount){
                            $discounts = $discountSettingData[0]['pCoupon_discount'];
                        }
                        ?>
                        <script>
                            $(document).ready(function(){
                                $('#d<?php echo $discounts; ?>').attr('checked','true');
                            });
                        </script>

                    </div>
                </div>




                <div class="form-group btn-default" id="priceDiv" style=""><br>
                    <?php $product->discountPricingViewSystem('saleForm',$editDiscount,$discountPriceData); ?>
                </div>

                <div class="form-group">
                    <h2 class="tab_heading"><?php echo _uc($_e['Product Category']); ?></h2>
                    <div class="col-sm-offset-2 col-sm-8">
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $("#tree").jstree({
                                'core': {
                                    'data': {
                                        'url': '<?php echo WEB_URL; ?>/<?php echo ADMIN_FOLDER; ?>/product_management/?operation=get_node',
                                        'data': function (node) {
                                            return { 'id': node.id };
                                        }
                                    }
                                },

                                "plugins": [ "wholerow", "checkbox","ui" ]
                            })
                                .on('loaded.jstree', function () {
                                    $("#tree").jstree('open_all');
                                }).on('open_all.jstree', function () {
                                    <?php if($editDiscount){
                                         $selectedNode= $discountSettingData[0]['pCoupon_category'];
                                    }else{
                                         $selectedNode="";
                                    }?>
                                    $('#tree').jstree(true).select_node([<?php echo $selectedNode ?>]);
                                })
                                .on('changed.jstree', function (e, data) {
                                    if (data && data.selected && data.selected.length) {
                                        $('.category_make_root').val(data.selected);
                                    }else {
                                        $('.category_make_root').val('0');
                                    }
                                });
                        });
                    </script>

                    <div id="tree"></div>

                    <div>
                        <input type="hidden" class="category_make_root" value="<?php echo $selectedNode; ?>" name="cats">
                    </div>
                    </div><!--offset end-->
                </div>

                <br>


                <button type="submit" class="btn btn-primary btn-lg" onsubmit="return submitDiscount();"><?php echo _u($_e['SUBMIT']); ?></button>

            </form>
        </div>

    </div>

<script>
    $(document).ready(function(){
        dateRangePicker();
    });
</script>

<?php return ob_get_clean(); ?>