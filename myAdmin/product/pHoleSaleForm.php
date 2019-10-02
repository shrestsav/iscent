<?php
ob_start();
$product = new product();
$sale = new sale();
addSale();
$editDiscount = false;


$discountPriceData      =   array();
$discountSettingData    =   $discountPriceData;
if(isset($_GET['sId'])){
    $sId    = $_GET['sId'];
    $discountSettingData    =   $sale->saleSettingData($sId);
    if($discountSettingData==false){
        $editDiscount = false;
    }else{
        $discountPriceData  =   $sale->salePriceData($sId);
        $editDiscount = true;
    }
}
global $dbF;
//var_dump($discountSettingData);


function addSale(){
    global $dbF;
    global $db;
    global $functions;
    global $_e;
    if(!empty($_POST)){
        //$dbF->prnt($_POST) ;
       //exit;
        if(isset($_POST['discount']) && !empty($_POST['curlist']) ){
            if(!$functions->getFormToken('saleForm')){ return false;}
            try{
                $db->beginTransaction();
                $editId = '';
                if($_POST['edit']!=''){
                    $editId = $_POST['edit'];
                    $sql ="DELETE FROM `product_sale_prices` WHERE pSale_price_id = '$editId'";
                    $dbF->setRow($sql);

                    $sql ="DELETE FROM `product_sale_setting` WHERE pSale_id = '$editId'";
                    $dbF->setRow($sql);

                    $sql    = "UPDATE `product_sale` SET
                                `pSale_name`=?,
                                `pSale_from`=?,
                                `pSale_to`=?,
                                `pSale_status`=?,
                                `pSale_discount`=?,
                                `pSale_category`=? WHERE pSale_pk = '$editId'
                              ";
                }else{
                    $sql    = "INSERT INTO `product_sale`
                                   (`pSale_name`,`pSale_from`, `pSale_to`,
                                      `pSale_status`, `pSale_discount`, `pSale_category`)
                                    VALUES
                                     (?,?,?,?,?,?)";
                }

                $status     =   isset($_POST['discount']['status'])     ? '1'   :   '0';
                $discount   =   isset($_POST['discount']['discount'])   ? $_POST['discount']['discount']   :   '1';
                $dateFrom   =   isset($_POST['discount']['dateFrom'])   ? $_POST['discount']['dateFrom'] : '';
                $dateTo     =   isset($_POST['discount']['dateTo'])     ? $_POST['discount']['dateTo']   : '';
                $cats       =   $_POST['cats'];
                $sale_name  =   $_POST['sale_name'];

                $array      =   array($sale_name,$dateFrom,$dateTo,$status,$discount,$cats);
                $dbF->setRow($sql,$array,false);
                if($editId==''){
                    $lastId     =   $dbF->rowLastId;
                }else{
                    $lastId     =   $editId;
                }

                //Discount Setting
                $sql ="";
                $sql    =   "INSERT INTO `product_sale_setting`
                            (`pSale_id`, `pSale_setting_name`, `pSale_setting_value`)
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
                $sql ="INSERT INTO `product_sale_prices`
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
                    $functions->setlog(_uc($_e['Sale Offer']), _uc($_e['Product']), $lastId, _replace('{{id}}', $lastId, $_e['New Product Whole Sale Added with Sale Discount Id: {{id}}']) );
                    $functions->notificationError(_js($_e['Sale Offer']), _js($_e['Product Whole Sale Offer Save Successfully']), 'btn-success');
                }else{
                    $functions->notificationError(_js($_e['Sale Offer']),_js($_e['Product Whole Sale Offer Save Fail']),'btn-danger');
                }
            }catch (Exception $e){
                $dbF->error_submit($e);
                $db->rollBack();
                $functions->notificationError(_js($_e['Sale Offer']),_js($_e['Product Whole Sale Offer Save Fail']),'btn-danger');
            }
        }//if end
    }// if end isset post
} // function end

?>

    <div class="container-fluid">
<?php if(isset($_GET['sId'])){ ?>
        <h4 class="sub_heading borderIfNotabs"><?php echo _uc($_e['Product Whole Sale Offer Setting']); ?></h4>
    <br>
<?php } ?>

        <div class="discountForm">
            <form action="" method="post" class="form-horizontal">
                <input type="hidden" name="edit" value="<?php if($editDiscount)echo $discountSettingData[0]['pSale_pk'];else echo ""; ?>"/>
                <?php $functions->setFormToken('saleForm'); ?>




                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label"
                           for="saleOnOff"><?php echo _uc($_e['Sale Status']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <div class="make-switch" data-off="warning" data-on="success">
                            <?php
                            //if edit
                            $status     =    '';
                            if($editDiscount){
                                if($discountSettingData[0]['pSale_status']=='1'){
                                    $status = 'checked';
                                }
                            }
                            ?>
                            <input type="checkbox" name="discount[status]"  id="saleOnOff" value="1"  <?php echo $status; ?>>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="saleName"><?php echo _uc($_e['Sale Offer Name']); ?></label>
                    <div class="col-sm-9 col-md-10 form-inline">
                        <?php
                        //if edit
                        $name     =    '';
                        if($editDiscount){
                            $name = $discountSettingData[0]['pSale_name'];
                        }
                        ?>
                        <input name="sale_name" id="saleName" class="form-control" value="<?php echo $name; ?>" placeholder="<?php echo _uc($_e['Enter Sale Offer Name']); ?>" required="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="discountFrom"><?php echo _uc($_e['Discount From']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <?php
                        //if edit
                        $dateFrom     =    '';
                        if($editDiscount){
                                $dateFrom = $discountSettingData[0]['pSale_from'];
                        }
                        ?>
                            <input type="text" value="<?php echo $dateFrom; ?>" name="discount[dateFrom]" id="discountFrom" class="form-control from" placeholder="<?php echo _uc($_e['Discount Start Date : Discount will available from start date,Leave blank To Start Now']); ?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="discountTo"><?php echo _uc($_e['Discount To']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <?php
                        //if edit
                        $dateTo     =    '';
                        if($editDiscount){
                            $dateTo = $discountSettingData[0]['pSale_to'];
                        }
                        ?>
                            <input type="text" value="<?php echo $dateTo; ?>" name="discount[dateTo]" id="discountTo" class="form-control to" placeholder="<?php echo _uc($_e['Discount End Date: Leave blank for Always']); ?>"/>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label" for="discountFormat"><?php echo _uc($_e['Discount Deduct In']); ?></label>
                    <div class="col-sm-9 col-md-10">
                        <?php
                        //if edit
                        $discountFormat     =    'price';
                        if($editDiscount){
                            $discountFormat = $sale->discountArrayFound($discountSettingData,'discountFormat');
                        }
                        ?>
                        <script>
                            $(document).ready(function(){
                                $('#discountFormat').val('<?php echo $discountFormat; ?>').change();
                            });
                        </script>
                        <select type="text" name="discount[discountFormat]" id="discountFormat" class="form-control to">
                            <option value="price"><?php echo _uc($_e['In Price']); ?></option>
                            <option value="percent"><?php echo _uc($_e['In Percent %']); ?></option>
                        </select>

                    </div>
                </div>


                <!--<div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label"><?php /*echo _uc($_e['Coupon Status']); */?></label>
                    <div class="col-sm-9 col-md-10 form-inline">
                        <?php /*echo _uc($_e['If Client use coupon']); */?>
                        <br>

                        <label for="onlyDiscount" class="radio-inline"><input type="radio" name="discount[coupon]" id="onlyDiscount" value="onlyDiscount" /><?php /*echo _uc($_e['Only Discount (Recommended)']); */?></label>
                        <label for="onlyCoupon" class="radio-inline"><input type="radio" name="discount[coupon]" id="onlyCoupon" value="onlyCoupon"  /><?php /*echo _uc($_e['Only Coupon']); */?></label>
                        <label for="couponBoth" class="radio-inline"><input type="radio" name="discount[coupon]" id="couponBoth" value="both" /><?php /*echo _uc($_e['Apply Both(Coupon & Discount)']); */?></label>
                        <?php
/*                        //if edit
                        $coupon     =    'onlyDiscount';
                        if($editDiscount){
                            $coupon = $sale->discountArrayFound($discountSettingData,'coupon');
                        }
                        */?>
                        <script>
                            $(document).ready(function(){
                                $('#<?php /*echo $coupon; */?>').attr('checked','true');
                            });
                        </script>

                    </div>
                </div>-->


                <div class="form-group">
                    <label class="col-sm-3 col-md-2 control-label"><?php echo _uc($_e['Product Discount']); ?></label>
                    <div class="col-sm-9 col-md-10 form-inline">
                        <?php echo _uc($_e['If Product Has Individual Discount Then Which situation apply?']); ?>
                        <br>
                        <label for="d1" class="radio-inline"><input type="radio" name="discount[discount]" id="d1" value="1" /><?php echo _uc($_e['Only Sale Offer (Recommended)']); ?></label>
                        <label for="d0" class="radio-inline"><input type="radio" name="discount[discount]" id="d0" value="0"  /><?php echo _uc($_e['Only Product Discount Offer']); ?></label>
                        <?php
                        //if edit
                        $discounts     =    '1';
                        if($editDiscount){
                            $discounts = $discountSettingData[0]['pSale_discount'];
                        }
                        ?>
                        <script>
                            $(document).ready(function(){
                                $('#d<?php echo $discounts; ?>').attr('checked','true');
                            });
                        </script>

                    </div>
                </div>




                <div class="form-group"><br>
                    <?php $product->discountPricingViewSystem('saleForm',$editDiscount,$discountPriceData); ?>
                </div>

                <div class="form-group">
                    <h2 class="tab_heading">Product Category</h2>
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
                                         $selectedNode= $discountSettingData[0]['pSale_category'];
                                    }else{
                                         $selectedNode="";
                                    }?>
                                    $('#tree').jstree(true).select_node([<?php echo $selectedNode ?>]);
                                })
                                .on('changed.jstree', function (e, data) {
                                    if (data && data.selected && data.selected.length) {
                                        $('.category_make_root').val(data.selected);
                                    } else {
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