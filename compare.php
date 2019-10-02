<?php include("global.php");
global $webClass;
global $_e;
global $productClass;

/**
 * MultiLanguage keys Use where echo;
 * define this class words and where this class will call
 * and define words of file where this class will called
 **/
$_w = array();
$_w['Color'] = '';
$_w['Size'] = '';
$_w['Select Product To Compare'] = '' ;
$_w['Image'] = '' ;
$_w['Select Product'] = '' ;
$_w['Name'] = '' ;
$_w['Price'] = '' ;
$_w['Short Desc'] = '' ;
$_w['Compare Page'] = '' ;
$_w['Specification'] = '' ;
$_e = $dbF->hardWordsMulti($_w, currentWebLanguage(), 'Web Product Detail');


$pId = isset($_GET['pId1']) ? floatval($_GET['pId1']) : 0;
$pId2 = isset($_GET['pId2']) ? floatval($_GET['pId2']) : 0;
$cat = '';


require_once(__DIR__ . "/".ADMIN_FOLDER."/product_management/functions/product_function.php");
require_once(__DIR__ . "/".ADMIN_FOLDER."/product/classes/product.class.php");
$product = new product();
$productF = new product_function();

$productClass->comparePage = true;
$WEB_URL = WEB_URL;

$sql = "SELECT * FROM proudct_detail WHERE prodet_id = '$pId'";
$data = $dbF->getRow($sql);

if (!$dbF->rowCount) {
    //return false;
}
$webLang = currentWebLanguage();
$defaultLang = defaultWebLanguage();
$loaderGif = WEB_URL . '/images/loader.gif';


//$productImage   =   $this->product->productLastImage($pId);
$productImage = $productClass->productSpecialImage($pId, 'main');

if ($productImage == "") {
    $productImage = "default.jpg";
}
$productThumb = $webClass->resizeImage($productImage, 320, 370, false);
$productImage = WEB_URL . '/images/' . $productImage;


$pName = translateFromSerialize($data['prodet_name']);
$pShrtDesc = translateFromSerialize($data['prodet_shortDesc']);
$currencyId = $productClass->currentCurrencyId();
$pSetting = $productClass->productF->getProductSetting($pId);
$specificationDesc = translateFromSerialize($productClass->productF->productSettingArray('specification', $pSetting, $pId));

$currencySymbol = $productClass->currentCurrencySymbol();
$pPriceData = $productF->productPrice($pId, $currencyId);
//$pPriceData Return , currency id,international shipping, price, id,
$priceDefault = $pPriceData['propri_price'];
$pPrice = $priceDefault;

$storeId = $productClass->getStoreId();

$discount = $productClass->productF->productDiscount($pId, $currencyId);
@$discountFormat = $discount['discountFormat'];
@$discountP = $discount['discount'];

$discountPrice = $productClass->productF->discountPriceCalculation($pPrice, $discount);
$newPrice = $pPrice - $discountPrice;


$hasDiscount = false;
//print where you want
if ($newPrice != $pPrice) {
    $hasDiscount = true;
    $oldPriceDiv = '<span class="oldPrice tabprice"><span class="productOldPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . '</span>';
    $newPriceDiv = '<span class="NewDiscountPrice1"><span class="productPrice_' . $pId . '">' . $newPrice . '</span> ' . $currencySymbol . ' </span>';
} else {
    $oldPriceDiv = "";
    $newPriceDiv = '<span class="NewDiscountPrice1">
                        <span class="productPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . '
                    </span>';
}


$hasScaleVal = $functions->developer_setting('product_Scale');
$hasColorVal = $functions->developer_setting('product_color');

$hasWebOrder_with_Scale = $functions->developer_setting('webOrder_with_Scale');
$hasWebOrder_with_color = $functions->developer_setting('webOrder_with_color');

$hasScale = ($hasScaleVal == '1' ? true : false);
$hasColor = ($hasColorVal == '1' ? true : false);


/*
 *
   Info Of is size & color insert in product either it is out of stock
    Or either it is out of store,
*/
$getInfo = $productClass->inventoryReport($pId);
$getInfoReport = $getInfo['report'];

if ($getInfo['scale'] == false && $hasWebOrder_with_Scale == '0') {
    //if scale not found then make scale data empty,
    //if product scale allow from setting and dont have inventory, it will make scale val to 0
    // we will assume scale not allow from setting
    $scaleDiv = "";
    $hasScaleVal = 0;
    $hasScale = false;
}
if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {
    //if color not found then make color data empty,
    //if product color allow from setting and dont have inventory, it will make color val to 0
    // we will assume color not allow from setting
    $colorDiv = "";
    $hasColorVal = 0;
    $hasColor = false;
}

//Make Color Divs,, print where you want
$colorDiv = "";
if ($hasColor) {
    $colorDiv = $productClass->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
}

//Make Scale Divs,, print where you want
$scaleDiv = "";
if ($hasScale) {
    $scaleDiv = $productClass->getScalesDiv($pId, $storeId, $currencyId, $currencySymbol, $hasColor);
}


// Product 2
$pId_2 = isset($_GET['pId2']) ? floatval($_GET['pId2']) : 0;
$cat = '';


$WEB_URL = WEB_URL;

$sql    = "SELECT * FROM proudct_detail WHERE prodet_id = '$pId_2'";
$data_2 = $dbF->getRow($sql);

if(!$dbF->rowCount){
    //return false;
}

//$productImage   =   $this->product->productLastImage($pId);
$productImage_2 = $productClass->productSpecialImage($pId_2, 'main');
if ($productImage_2 == "") {
    $productImage_2 = "default.jpg";
}

$productThumb_2 = $webClass->resizeImage($productImage_2, 320, 370, false);
$productImage_2 = WEB_URL . '/images/' . $productImage_2;


$pName_2        = translateFromSerialize($data_2['prodet_name']);
$pShrtDesc_2    = translateFromSerialize($data_2['prodet_shortDesc']);

$pSetting_2 = $productClass->productF->getProductSetting($pId_2);
$specificationDesc_2 = translateFromSerialize($productClass->productF->productSettingArray('specification', $pSetting_2, $pId_2));


$pPriceData_2 = $productF->productPrice($pId_2, $currencyId);
//$pPriceData Return , currency id,international shipping, price, id,
$priceDefault_2 = $pPriceData_2['propri_price'];
$pPrice_2 = $priceDefault_2;

$storeId_2 = $productClass->getStoreId();

$discount_2 = $productClass->productF->productDiscount($pId_2, $currencyId);
@$discountFormat_2 = $discount_2['discountFormat'];
@$discountP_2 = $discount_2['discount'];

$discountPrice_2 = $productClass->productF->discountPriceCalculation($pPrice_2, $discount_2);
$newPrice_2 = $pPrice_2 - $discountPrice_2;


$hasDiscount_2 = false;
//print where you want
if ($newPrice_2 != $pPrice_2) {
    $hasDiscount_2 = true;
    $oldPriceDiv_2 = '<span class="oldPrice tabprice"><span class="productOldPrice_' . $pId_2 . '">' . $pPrice_2 . '</span> ' . $currencySymbol . '</span>';
    $newPriceDiv_2 = '<span class="NewDiscountPrice1"><span class="productPrice_' . $pId_2 . '">' . $newPrice_2 . '</span> ' . $currencySymbol . ' </span>';
} else {
    $oldPriceDiv_2 = "";
    $newPriceDiv_2 = '<span class="NewDiscountPrice1">
                        <span class="productPrice_' . $pId_2 . '">' . $pPrice_2 . '</span> ' . $currencySymbol . '
                    </span>';
}


$hasScaleVal_2 = $functions->developer_setting('product_Scale');
$hasColorVal_2 = $functions->developer_setting('product_color');

$hasWebOrder_with_Scale_2 = $functions->developer_setting('webOrder_with_Scale');
$hasWebOrder_with_color_2 = $functions->developer_setting('webOrder_with_color');

$hasScale_2 = ($hasScaleVal_2 == '1' ? true : false);
$hasColor_2 = ($hasColorVal_2 == '1' ? true : false);


/*
 *
   Info Of is size & color insert in product either it is out of stock
    Or either it is out of store,
*/
$getInfo_2 = $productClass->inventoryReport($pId_2);
$getInfoReport_2 = $getInfo['report'];

if ($getInfo_2['scale'] == false && $hasWebOrder_with_Scale_2 == '0') {
    //if scale not found then make scale data empty,
    //if product scale allow from setting and dont have inventory, it will make scale val to 0
    // we will assume scale not allow from setting
    $scaleDiv_2 = "";
    $hasScaleVal_2 = 0;
    $hasScale_2 = false;
}
if ($getInfo_2['color'] == false && $hasWebOrder_with_color_2 == '0') {
    //if color not found then make color data empty,
    //if product color allow from setting and dont have inventory, it will make color val to 0
    // we will assume color not allow from setting
    $colorDiv_2 = "";
    $hasColorVal_2 = 0;
    $hasColor_2 = false;
}

//Make Color Divs,, print where you want
$colorDiv_2 = "";
if ($hasColor_2) {
    $colorDiv_2 = $productClass->getColorsDiv($pId_2, $storeId_2, $pPrice_2, $currencyId, $currencySymbol, $discountP_2, $hasScale_2);
}

//Make Scale Divs,, print where you want
$scaleDiv_2 = "";
if ($hasScale_2) {
    $scaleDiv_2 = $productClass->getScalesDiv($pId_2, $storeId_2, $currencyId, $currencySymbol, $hasColor_2);
}


include("header.php");

?>

<?php $webClass->seoSpecial();  ?>

    <div class="main_container_tow_inner ContainerInnerPage ">
        <div class="container-fluid comparePage">
            <div class="col-xs-12 text-center">
                <div class="lead bold"></div>
                <div class="lead bold"><?php echo _uc($_e['Compare Page']); ?></div>
            </div>
            <div class="col-xs-12 compare1">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <tr>
                            <th width="20%"><?php echo $_e['Select Product']; ?></th>
                            <th width="40%"><input type="text" id="compareSearch1" placeholder="<?php echo $_e['Select Product To Compare']; ?>" class="form-control compare_search" autocomplete="off"></th>
                            <th width="40%"><input type="text" id="compareSearch2" placeholder="<?php echo $_e['Select Product To Compare']; ?>" class="form-control compare_search2" autocomplete="off"></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th><?php
                                if(!isset($_GET['pId1']) || $_GET['pId1'] == '' || $_GET['pId1'] =='0'){
                                    echo $_e['Select Product To Compare'];
                                }else{
                                    echo $pName;
                                }
                                ?></th>
                            <th>
                                <?php
                                if(!isset($_GET['pId2']) || $_GET['pId2'] == '' || $_GET['pId2'] =='0'){
                                    echo $_e['Select Product To Compare'];
                                }else{
                                    echo $pName_2;
                                } ?>
                            </th>
                        </tr>
                        <tr>
                            <td class="td1"><?php echo _uc($_e['Image']); ?></td>
                            <td class="td2"><img src="<?php echo $productThumb; ?>" class="img-responsive"/></td>
                            <td class="td3"><img src="<?php echo $productThumb_2; ?>" class="img-responsive"/></td>
                        </tr>
                        <tr>
                            <td class="td1"><?php echo _uc($_e['Name']); ?></td>
                            <td class="td2"><div class="wrap-normal"><?php echo $pName; ?></div></td>
                            <td class="td3"><div class="wrap-normal"><?php echo $pName_2; ?></div></td>
                        </tr>
                        <tr>
                            <td class="td1"><?php echo _uc($_e['Price']); ?></td>
                            <td class="td2">
                                <?php
                                if(trim(strip_tags($newPriceDiv))==$currencySymbol){
                                    $newPriceDiv = '';
                                }
                                echo $newPriceDiv;
                                echo $oldPriceDiv;
                                ?>
                            </td>
                            <td class="td3"><?php
                                if(trim(strip_tags($newPriceDiv_2))==$currencySymbol){
                                    $newPriceDiv_2 = '';
                                }
                                echo $newPriceDiv_2;
                                echo $oldPriceDiv_2;
                                ?></td>
                        </tr>
                        <tr id="size_radio">
                            <td class="td1"><?php echo _uc($_e['Size']); ?></td>
                            <td class="td2"><?php echo $scaleDiv; ?></td>
                            <td class="td3"><?php echo $scaleDiv_2; ?></td>
                        </tr>
                        <tr id="size_color">
                            <td class="td1"><?php echo _uc($_e['Color']); ?></td>
                            <td class="td2"><?php echo $colorDiv; ?></td>
                            <td class="td3"><?php echo $colorDiv_2; ?></td>
                        </tr>

                        <tr>
                            <td class="td1"><?php echo _uc($_e['Specification']); ?></td>
                            <td class="td2"><div class="wrap-normal"><?php echo strip_tags($specificationDesc); ?></div></td>
                            <td class="td3"><div class="wrap-normal"><?php echo strip_tags($specificationDesc_2); ?></div></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<style>
    .ui-autocomplete {
        max-height: 220px;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
<script>
    $(document).ready(function(){
        $(".compare_search").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "_models/functions/products_ajax_functions.php?page=getSearchJson&limit=15&val=" + $('.compare_search').val(),
                    success: function (data) {
                        response(eval(data));
                    }
                });
            },

            minLength: 1,
            select: function (event, ui) {
                id      = ui.item.id;
                id2     = getParam('pId2');
                link    = "compare.php?pId1="+id+"&pId2="+id2;
                location.replace(link);
            }, open: function () {
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                $('.ui-menu').width(280);
            },
            close: function () {
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .css({"margin":"1px 0",
                    "height": "50px",
                    "padding":"5px"
                })
                .append("<div class='col-xs-12 compare padding-0'>" +
                "<div class='col-xs-2 padding-0 '>" +
                "<img class='img-responsive1' src='"+item.image+"' style='height:40px;'/>" +
                "</div>" +
                "<div class='col-xs-10 padding-0'>"+item.name+" "+item.oldPrice+""+item.newPrice+"</div>" +
                "</div>")
                .appendTo(ul);
        };

        $(".compare_search2").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "_models/functions/products_ajax_functions.php?page=getSearchJson&limit=15&val=" + $('.compare_search2').val(),
                    success: function (data) {
                        response(eval(data));
                    }
                });
            },

            minLength: 1,
            select: function (event, ui) {
                id      = getParam('pId1');
                id2     = ui.item.id;
                link    = "compare.php?pId1="+id+"&pId2="+id2;
                location.replace(link);
            }, open: function () {
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                $('.ui-menu').width(280);
            },
            close: function () {
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .css({"margin":"1px 0",
                    "height": "50px",
                    "padding":"5px"})
                .append("<div class='col-xs-12 compare padding-0'>" +
                "<div class='col-xs-2 padding-0'>" +
                "<img class='img-responsive1' src='"+item.image+"' style='height:40px;'/>" +
                "</div>" +
                "<div class='col-xs-10 padding-0'>"+item.name+" "+item.oldPrice+""+item.newPrice+"</div>" +
                "</div>")
                .appendTo(ul);
        };
    });
</script>

<!-- End of main cointainer About us -->
<?php include_once(__DIR__ . "/footer.php"); ?>