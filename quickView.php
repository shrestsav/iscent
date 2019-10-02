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
$_w['Return to Previous Page'] = '';
$_w['Related Products'] = '';
$_w['Reviews'] = '';
$_w['Product'] = '';
$_w['Product Code'] = '';
$_w['Model'] = '';
$_w['Additional Information'] = '';
$_w['Product Description'] = '';
$_w['SHIPPING & RETURNS'] = '';
$_w['DESCRIPTION'] = '';
$_w['SIZE CHART'] = '';
$_w['REVIEWS'] = '';
$_w['Home'] = '';
$_w['Info'] = '';
$_w['Shipping'] = '';
$_w['Color'] = '';
$_w['Size'] = '';
$_w['In Stock'] = '';
$_w['Out Stock'] = '';
$_w['Return & Defected'] = '';
$_w['Add To Cart'] = '';
$_w['Add To Wishlist'] = '';
$_w['share'] = '';
$_w['Original Price'] = '';
$_w['Product Description'] = '';
$_w['Availability'] = '';
$_w['PRODUCT FEATURE ICONS'] = '';
$_w['YOU MIGHT ALSO LIKE'] = '';
$_w['WHAT DO OUR CUSTOMER SAY?'] = '';
$_w['Send email on sale offer'] = '';
$_w['Refer to a friend'] = '';
$_w['Ask Question'] = '';
$_w['Asked Questions'] = '';
$_w['DO NOT FORGET TO BUY'] = '';
$_w['Quantity'] = '';
$_w['Qty'] = '';
$_w['Return to Previous Page'] = '';
$_w['Related Products'] = '';
$_w['Reviews'] = '';
$_w['Product'] = '';
$_w['Product Code'] = '';
$_w['Model'] = '';
$_w['Add To Compare'] = '';
$_w['Additional Information'] = '';
$_w['Product Description'] = '';
$_w['SHIPPING & RETURNS'] = '';
$_w['DESCRIPTION'] = '';
$_w['SIZE CHART'] = '';
$_w['REVIEWS'] = '';
$_w['Home'] = '';
$_w['Info'] = '';
$_w['Shipping'] = '';
$_w['Color'] = '';
$_w['Size'] = '';
$_w['In Stock'] = '';
$_w['Out Stock'] = '';
$_w['Return & Defected'] = '';
$_w['BACK TO SUITS AND SPORTSCOASTS'] = '';
$_w['Add To Cart'] = '';
$_w['Add To Wishlist'] = '';
$_w['share'] = '';
$_w['Original Price'] = '';
$_w['Product Description'] = '';
$_w['Availability'] = '';
$_w['PRODUCT FEATURE ICONS'] = '';
$_w['YOU MIGHT ALSO LIKE'] = '';
$_w['WHAT DO OUR CUSTOMER SAY?'] = '';
$_w['Send email on sale offer'] = '';
$_w['Refer to a friend'] = '';
$_w['Ask Question'] = '';
$_w['Asked Questions'] = '';
$_w['Specification'] = '';
$_w['DO NOT FORGET TO BUY'] = '';
$_w['Shipping Class'] = '';
$_w['Size Chart'] = '';
$_w['Qty'] = '';
$_w['Custom'] = '';
$_w['Custom Size'] = '';
$_w['Value'] = '';
$_w['Quality'] = '';
$_w['Price'] = '';
$_w['Notify me when product available'] = '';
$_e = $dbF->hardWordsMulti($_w, currentWebLanguage(), 'Web Product Detail');


$pId = isset($_GET['pId']) ? floatval($_GET['pId']) : 0;
$cat = '';
if (isset($_GET['cat']) && $_GET['cat'] != '') {
    //Product By category
    $cat = $_GET['cat'];
    if ($products == false) {
        //Do If no product found on category
    }
}


require_once(__DIR__ . "/" . ADMIN_FOLDER . "/product_management/functions/product_function.php");
require_once(__DIR__ . "/" . ADMIN_FOLDER . "/product/classes/product.class.php");
$product = new product();
$productF = new product_function();
$webLang = currentWebLanguage();
$defaultLang = defaultWebLanguage();

$subsMsg = $productClass->productSubscribeOnSaleSubmit();
$referMsg = $productClass->referToFriendSubmit();
//$sql = "SELECT * FROM proudct_detail WHERE prodet_id = '$pId'";
$data = $productClass->productData($pId);

if (!$dbF->rowCount) {
    //header("HTTP/1.0 404 Not Found");
    $webClass->P404();
    exit;
}
$pLink = WEB_URL . "/products.php?pId=$pId";

$pViews = $data['view'];
$pSetting = $productClass->productF->getProductSetting($pId);
$model = $productClass->productF->productSettingArray('Model', $pSetting, $pId);
$label = $productClass->productF->productSettingArray('label', $pSetting, $pId);
$video = $productClass->productF->productSettingArray('video', $pSetting, $pId);
$relatedIds = unserialize($productClass->productF->productSettingArray('related', $pSetting, $pId));

$videoDiv = '';
if (!empty($video)) {
    $videoDiv = '<a href="' . $video . '" class="shrink fancybox-video"><img src="' . WEB_URL . '/images/video_icon.png" /></a>';

    $videoDiv .= "<script>
        $(document).ready(function(){
            $('.fancybox-video').fancybox({
                    'width'				: '75%',
                    'height'			: '100%',
                    'autoScale'     	: false,
                    'type'				: 'iframe'
                });
        });
    </script>
    ";
}


$loaderGif = WEB_URL . '/images/loader.gif';

$productImage = $productClass->productSpecialImage($pId, 'main');

if ($productImage == "") {
    $productImage = "default.jpg";
}
$productThumb = $webClass->resizeImage($productImage, 619, 613, false);
$productImage = WEB_URL . '/images/' . $productImage;


$pName      = translateFromSerialize($data['prodet_name']);
$pDesc      = translateFromSerialize($productClass->productF->productSettingArray('ldesc', $pSetting, $pId));
$size_chart      = translateFromSerialize($productClass->productF->productSettingArray('size_chart', $pSetting, $pId));
$pFeaturePoints = translateFromSerialize($productClass->productF->productSettingArray('featurePoints', $pSetting, $pId));
$pAdditionalInfo = translateFromSerialize($productClass->productF->productSettingArray('tags', $pSetting, $pId));
$specificationDesc = translateFromSerialize($productClass->productF->productSettingArray('specification', $pSetting, $pId));
$pReturnDesc = $pAdditionalInfo;
$featureIcon = translateFromSerialize($productClass->productF->productSettingArray('featureIcon', $pSetting, $pId));
$pShrtDesc  = translateFromSerialize($data['prodet_shortDesc']);

$specificationDesc = translateFromSerialize($productClass->productF->productSettingArray('specification', $pSetting, $pId));
$size_chart      = translateFromSerialize($productClass->productF->productSettingArray('size_chart', $pSetting, $pId));
$currencyId = $productClass->currentCurrencyId();
$currencySymbol = $productClass->currentCurrencySymbol();

$pPriceData     = $productF->productPrice($pId, $currencyId);
//$pPriceData Return , currency id,international shipping, price, id,
$priceDefault   = $pPriceData['propri_price'];
$pPrice = $priceDefault;

$storeId = $productClass->getStoreId();

$discount = $productClass->productF->productDiscount($pId, $currencyId);

@$discountFormat = $discount['discountFormat'];
@$discountP = $discount['discount'];

$isSale         =   false;
if(isset($discount['isSale']) && $discount['isSale']=='1'){
    $isSale     =   true;
}

$discountPrice = $productClass->productF->discountPriceCalculation($pPrice, $discount);
$newPrice = $pPrice - $discountPrice;


$hasDiscount = false;
//print where you want
if ($newPrice   != $pPrice) {
    $hasDiscount = true;
    $oldPriceDiv = '<span class="oldPrice tabprice"><span class="productOldPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . '</span>';
    $newPriceDiv = '<span class="NewDiscountPrice1"><span class="productPrice_' . $pId . '">' . $newPrice . '</span> ' . $currencySymbol . ' </span>';
} else {
    $oldPriceDiv = "";
    $newPriceDiv = '<span class="NewDiscountPrice1">
                        <span class="productPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . '
                    </span>';
}


$inventoryLimit = $functions->developer_setting('product_check_stock'); // mean is unlimit inventory
$inventoryLimit = ($inventoryLimit == '1' ? true : false);

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

if($inventoryLimit){
    $getInfo = $productClass->inventoryReport($pId);
}else {
    $getInfo = $productClass->productSclaeColorReport($pId);
}
$getInfoReport  = $getInfo['report'];

if ($getInfo['scale'] == false && $hasWebOrder_with_Scale == '0') {
    //if scale not found then make scale data empty,
    //if product scale allow from setting and dont have inventory, it will make scale val to 0
    // we will assume scale not allow from setting for javascript
    $scaleDiv = "";
    $hasScaleVal = 0;
    $hasScale = false;
}

if ($getInfo['color'] == false && $hasWebOrder_with_color == '0') {
    //if color not found then make color data empty,
    //if product color allow from setting and dont have inventory, it will make color val to 0
    // we will assume color not allow from setting for javascript
    $colorDiv = "";
    $hasColorVal = 0;
    $hasColor = false;
}

//Make Color Divs,, print where you want
$colorDiv = "";
if ($hasColor) {
    $colorDiv = $productClass->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
    $colorDiv = "<div class='container_detail_RightR_color_heading'>
                        <p>" . _uc($_e['Color']) . "</p>" . $colorDiv . "</div>";
}

//Make Scale Divs,, print where you want
$isCustomSize = false;
$customSizeForm = '';

$scaleDiv = "";

if ($hasScale) {
    $scaleDiv = $productClass->getScalesDiv($pId, $storeId, $currencyId, $currencySymbol, $hasColor);
}
//Custom Size
if ($functions->developer_setting('product_customSize') == '1') {
    $sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
    $customData = $dbF->getRows($sql);

    if (!empty($customData)) {
        @$customSize        = $customData[0]['type_id'];
        if ($customSize != "0" && !empty($customSize)) {
            $isCustomSize = true;

            $customSizeForm = $productClass->customSizeForm($pId, $customSize);
            if ($customSizeForm == false) {
                $isCustomSize = true;
                $customSize = 0;
            } else {
                $customSize_price = $productClass->customSizeArrayFilter($customData, $currencyId);
                $customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
                $onclick    = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '"';
                $scaleDiv   = $scaleDiv . $productClass->getScaleDivFormat($pId, 'Custom', -1, $customSize_price, -1, '', $onclick);
            }

        }//if customSize==0 end
    } //if customData end

} // if developer setting end
//Custom Size End

if(!empty($scaleDiv)){
    $scaleDiv = "<div class='container_detail_RightR_color_heading'><p>" . _uc($_e['Size']) . "</p>" . $scaleDiv . "</div>";
}


$isSingleProduct = false;
if (!$hasScale && !$hasColor) {
    $isSingleProduct = true;
}

$inventoryLimit = ($inventoryLimit == true ? "1" : "0");

//print jsInfo after body start, or in product div
$jsInfo = " <!-- javascript Info use in js-->
                <input type='hidden' id='currency_$pId' value= '$currencySymbol'
                                            data-discountP      = '$discountP'
                                            data-discountFormat = '$discountFormat'
                                            data-discountDefaultPrice  ='$newPrice'
                                            data-defaultPrice   = '$priceDefault'/>
                <input type='hidden' id='store_$pId' value='$storeId'/>
                <input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
                <input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>

                <input type='hidden' id='order_with_Color_$pId' value='$hasWebOrder_with_color'/>
                <input type='hidden' id='order_with_Scale_$pId' value='$hasWebOrder_with_Scale'/>
                <input type='hidden' id='deatilStockCheck_$pId' value='$inventoryLimit' >
                $getInfoReport
                <!-- javascript Info use in js End-->";


$stockStatus    = $productClass->productF->hasStock($pId);
if ($stockStatus) {
    $stockStatus_T = _uc($_e['In Stock']);
} else {
    $stockStatus_T = _uc($_e['Out Stock']);
}


//Blog Class For Reviews Or Facebook Comment
$functions->require_once_custom('webBlog_functions');
$blogC = new webBlog_functions();
$reviewMsg = "";
$reviews = "";
$reviewOff  = $productClass->productF->productSettingArray('reviewOffMsg', $pSetting, $pId);
$myReview   = $productClass->productF->productSettingArray('review', $pSetting, $pId); // 1 or 0 from single product

if ($myReview == '1' || empty($myReview)) {
    //check product setting
    $reviewMsg = $blogC->reviewSubmit();
    $reviews   = $blogC->reviews($pId, 'product', 2, true, $myReview, $reviewOff);
} else if ($reviewOff != '') {
    $reviews = "<hr><div class='reviewoffMsg alert alert-warning  margin-0'>$reviewOff</div>";
}

$questionOff    =   $productClass->productF->productSettingArray('questionOffMsg', $pSetting, $pId);
$questionAllow  =   $productClass->productF->productSettingArray('askQuestion', $pSetting, $pId); // 1 or 0 from single product
$askQuestion    =   '';
$askQuestionForm=   $askQuestion;
if ($questionAllow == '1' || empty($questionOff)) {
    $reviewMsg  = $blogC->askQuestionSubmit();
    $askQuestion = $blogC->askQuestion($pId, 'question',false,$questionAllow, $questionOff);
    $askQuestionForm = $blogC->askQuestionForm($pId,'question');
} else if ($questionOff != '') {
    $askQuestion = "<hr><div class='reviewoffMsg alert alert-warning  margin-0'>$questionOff</div>";
    $askQuestionForm = $askQuestion;
}

// Use $reviews variable to show reviews
$facebookOff = $productClass->productF->productSettingArray('fbCommentOffMsg', $pSetting, $pId);
$facebookComments = "";
if ($productClass->productF->productSettingArray('facebookComment', $pSetting, $pId) == '1') {// check product setting
    $fbComments = $blogC->facebookComment();
    $facebookComments = "<div class='container-fluid padding-0 facebookCommentsDiv'>$fbComments</div>";
} else if ($facebookOff != '') {
    $facebookComments = "<div class='fbOffMsg alert alert-warning'>$facebookOff</div>";
}

//Review Or Comment End
$fbLikeShare = $functions->socialFbLikeShare();
// Use $facebookComments variable to show Facebook Comments
$WEB_URL = WEB_URL;
//Seo
$arryySeo = array();
$arraySeo['title'] = $pName;
$arraySeo['description'] = $pShrtDesc;
$arraySeo['image'] = $productImage;
$productClass->productMetaSeo($arraySeo);
//Seo End


//Return policy page
$usingReturnPolicy = false; // make if true, if you want to use return policy data
if ($usingReturnPolicy) {
    $returnPageId = "return";
    $pgData = $webClass->getPage($returnPageId);
    $returnPolicyDesc = $pgData['desc'];
    $returnPolicyShortDesc = $pgData['short_desc'];
}

//defect policy page
$usingDefectPolicy = false; // make if true, if you want to use return policy data
if ($usingDefectPolicy) {
    $returnPageId = "defect";
    $pgData = $webClass->getPage($returnPageId);
    $defectPolicyDesc = $pgData['desc'];
    $defectPolicyShortDesc = $pgData['short_desc'];
}

$productClass->productViewCount($pId);


$addToCartButton = "";

echo $subsMsg;
echo $referMsg;
if ($isCustomSize) {
    echo $functions->blankModal("Custom Size", "customF_$pId", $customSizeForm, "Close");
}
######### 3 For 2 Category ########
$three_for_2_category = $productF->check_product_in_3_for_2($pId);
if($three_for_2_category){
    $three_for_2_category = " <img src='".WEB_URL."/images/3for2.jpg' height='40' />";
}else{
    $three_for_2_category = "";
}


//Shipping Class price
$shippingClassId = $productClass->productF->productSettingArray('shippingClass', $pSetting, $pId);
if($shippingClassId >0){
    $shipClassData  = $productClass->shippingClassInfo($shippingClassId);
    $shipClassPrice = $shipClassData['price'];
    $shipClass      = $shipClassData['name'];
}else{
    $shipClassData  = false;
    $shipClassPrice = $shipClass = '';
}
//Shipping Class price End

?>
    <script>
        $(document).ready(function () {
            <?php if($isSingleProduct){ ?>
            productStockCheck(<?php echo $pId; ?>, 0, 0);
            <?php } ?>
        });
    </script>

    <div class="content_area quick_content">
    <div id="p<?php echo $pId; ?>">


    <link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/cloudzoom.css"/>
    <script type="text/javascript" src="<?php echo WEB_URL ?>/js/cloudzoom.js"></script>
    <script type="text/javascript">
        CloudZoom.quickStart();
      
    </script>
    <div class="pro_left_panel">

        <div class="pro_left_panel_in">
            <div class="big_img hidden-xs">
                <img class="cloudzoom onClickCloudZoomShowBig" style="max-height:100%;" alt=""
                     src="<?php echo $productThumb; ?>"
                     data-cloudzoom="zoomImage: '<?php echo $productImage; ?>',
                                  zoomSizeMode : 'image',autoInside: true, zoomPosition: 'inside',zoomOffsetX:0,touchStartDelay:1">
            </div><!--big_img end-->


            <div class="small_slider hidden-xs">


                <div class="s_common s_left"></div>


                <div class="hidden-xs small_images">
                    <ul id="slider_pd">
                        <?php
                        $allImages = $productClass->productAllImage($pId);
                        foreach ($allImages as $val) {
                            $img = $val['image'];
                            $imgSize1 = $functions->resizeImage($img, 107, '100', false);
                            $imgSize2 = $functions->resizeImage($img, 619, 613, false);
                            $real = WEB_URL . "/images/" . $img;
                            $alt = $val['alt'];

                            echo "<li><div class='pd_box'>
                                                <img  class='cloudzoom-gallery' alt='$alt'
                                                 src='$imgSize1'
                                                 data-cloudzoom=\"useZoom: '.cloudzoom',
                                            image: '$imgSize2',
                                            zoomImage: '$real'  \">
                                       </div></li>";

                        }

                        ?>
                    </ul>
                    <script>
                        $(function() {
                            $('#slider_pd').ulslide({
                                height:140,
                                effect: {
                                    type: 'carousel', // slide or fade
                                    axis: 'y',        // x, y
                                    showCount:3,
                                    distance:16

                                },

                                nextButton: '.s_left',
                                prevButton:'.s_right',
                                duration: 1000,
                                mousewheel: false,
                                autoslide: 5000,
                                animateOut: 'fadeOut',
                                animateIn: 'fadeIn'
                            });
                        });
                    </script>
                </div><!--small_images end-->
                <div class="s_common s_right"></div>
            </div><!--small_slider end-->

            <div class="pd_5">
                <div class="slider_pics5">
                    <div class="all_main_items1 all2 visible-xs">
                        <?php
                        $allImages = $productClass->productAllImage($pId);
                        foreach ($allImages as $val) {
                            $img = $val['image'];
                            $imgSize1 = $functions->resizeImage($img, '271', '363', false);
                            $imgSize2 = $functions->resizeImage($img, 619, 613, false);
                            $real = WEB_URL . "/images/" . $img;
                            $alt = $val['alt'];

                            echo "
                            <div class='item_container'>
                              <div class=' sink'> <a class='fancybox-media' href='$imgSize2' > <img src='$imgSize1' alt='$alt'></a> </div>
                            </div>
                            <!--item1 end-->";
                        }
                        ?>
                    </div>
                    <!-- all_main_items1 end -->
                </div>
                <!--slider 5 end-->
            </div>
            <!--pd_5 end-->





        </div>



        <!--pd_5 end-->
    </div><!--pro_left_panel end-->




    <div class="pro_right_panel">
    <div class="all_items">
    <?php
    $nextProduct = $productClass->getNextOrPrevProduct($pId);
    $prevProduct = $productClass->getNextOrPrevProduct($pId,false);
    ?>

    <div class="back_link"><a href="<?php echo $prevProduct['link']; ?>"><?php echo $prevProduct['name'];?></a></div>
    <div class="text_btns_area">
        <a href="<?php echo $prevProduct['link']; ?>"><div class="text_btns t_left transition_7"></div></a>
        <a href="<?php echo $nextProduct['link']; ?>"><div class="text_btns t_right transition_7"></div></a>
    </div>
    <div class="pro_text">
        <h1 class="detail_heading"> <?php echo $pName; ?> </h1>
        <span><?php echo $label.$three_for_2_category; ?></span>
    </div>

    <div class="pro_prize">
        <ul>
            <li class="p_style1"><span style="color: #7cbe35;"> <?php echo $_e['Price']; ?></span></li>
            <li class="p_style1"><?php
                echo $newPriceDiv;
                ?></li>
        </ul>
        <div style="clear:both"></div>

        <?php
        $functions->require_once_custom('Class.myKlarna.php');
        $klarnaClass    =   new myKlarna();
        $klarnaSecrets  =   $klarnaClass->klarnaSharedSecret();
        $eid            =   $klarnaSecrets['eId'];
        $sharedSecret   =   $klarnaSecrets['sharedSecret'];
        ?>
        <div style="width:210px; height:80px"
             class="klarna-widget klarna-part-payment"
             data-eid="<?php echo $eid; ?>"
             data-locale="<?php echo str_replace("-","_",$klarnaClass->locale); ?>"
             data-price="<?php echo $newPrice; ?>"
             data-layout="pale-v2"
            >
        </div>
        <script async src="https://cdn.klarna.com/1.0/code/client/all.js"></script>

        <div style="clear:both"></div>

        <?php if(!empty($oldPriceDiv)){ ?>
            <ul>

                <li class="p_style2"><?php echo $_e['Original Price']; ?></li>
                <li class="p_style2"><?php
                    echo $oldPriceDiv;
                    ?></li>
            </ul>
        <?php } ?>
        <div style="clear:both"></div>

        <ul>
            <li class="p_style2" style="color:#000;"><?php echo $_e['Availability']; ?></li>
            <li class="p_style2"id="stock_<?php echo $pId; ?> "style="color:#7cbe35;"><?php echo $stockStatus_T; ?></li>
        </ul>



        <div style="clear:both"></div>
    </div><!--pro_prize end-->

    <?php
    if($shippingClassId >0){
        echo "<div class='container-fluid text-right shipping_text'> ".$_e["Shipping Class"]." : $shipClass : $shipClassPrice $currencySymbol</div>";
    }
    ?>

    <div class="pro_cart">
        <div class="select">
            <div id="size_color" class="container_detail_RightR_color choice_color">
                <!-- Heading -->
                <?php echo $colorDiv; ?>
            </div>

            <div class="detail_top_r_size" id="size_radio">
                <?php echo $scaleDiv; ?>
            </div>
            <div class="item_note">5 items in stock</div>
            <div class="padding-0" style=" margin-top: 10px;">
                <small id="stock_<?php echo $pId; ?>"></small>
                <input type='hidden' id="hidden_stock_<?php echo $pId; ?>" />
            </div>

        </div>
        <div class="cart_btn_area">

            <?php if($functions->developer_setting('addQty_custome')=='1') {
                echo "<div class='container-fluid padding-0 form-horizontal col-xs-5 inline_block'>
                                <label class='col-xs-5 control-label padding-0' style='line-height:20px;'>".$_e["Qty"].":</label>
                                <div class='col-xs-7 '>
                                    <input type='number' min='1' value='1' pattern='[0-9]{1,5}' class='form-control addByQty_$pId' />
                                    <input type='hidden' value='1' class='addByQty_hidden_$pId' />
                                </div>
                              </div>
                 <a onclick='addToWishList(this, echo $pId; );' class='btn btn-xs btn-default futura_bk_bt wishlist inline_block col-xs-2'><span class='glyphicon glyphicon-heart'></span></a>
                 <a href='echo WEB_URL ?>/compare.php?pId1=<?php echo $pId' class='btn btn-xs btn-default futura_bk_bt wishlist inline_block col-xs-2'><span class='glyphicon glyphicon-plus'></span></a>
                 <a href='echo WEB_URL ?>/compare.php?pId1=<?php echo $pId' class='btn btn-xs btn-default futura_bk_bt wishlist inline_block col-xs-2'><span class='glyphicon glyphicon-tags'></span></a>
                 <a href='echo WEB_URL ?>/compare.php?pId1=<?php echo $pId' class='btn btn-xs btn-default futura_bk_bt wishlist inline_block col-xs-2'><span class='glyphicon glyphicon-user'></span></a>
</div>
                              <div class='cart_btn  cursor AddToCart_$pId' onclick='addToCart(this,$pId);'>
                                    {$_e['Add To Cart']}
                              </div>";
            }else{
                echo "<div class='cart_btn  cursor AddToCart_$pId' onclick='addToCart(this,$pId);'>
                                    {$_e['Add To Cart']}
                              </div>";
            }
            ?>


        </div>
        <div class="clearfix"></div>

        <div class="detail_top_r_icons text-center">
            <div class="container-fluid padding-f10 margin-5 my_text_center">

                <?php $pageLink = WEB_URL."/detail.php?pId=".$pId; ?>
                <table valign="middel" style="margin: 0 auto;">
                    <td style="padding:0 5px"><div class="fb-share-button" data-href="<?php echo $pageLink; ?>" data-layout="button_count"></div></td>
                    <td>
                        <div class="twitter-share">
                            <a class="twitter-share-button" href="<?php echo $pageLink; ?>"
                               data-related="twitterdev"
                               data-count="yes">
                                Tweet
                            </a>
                            <script>
                                function TwitterSocial(){
                                    window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
                                }
                                setTimeout(TwitterSocial(),<?php echo $db->setTimeOutSocial; ?>);
                            </script>
                        </div>
                    </td>

                    <td>
                        <script src="https://apis.google.com/js/platform.js" async defer></script>
                        <div class="g-plus" data-action="share" data-annotation="bubble"></div>
                    </td>


                </table>



                <?php if(!$stockStatus){
                    $cart_target = "data-toggle='modal' data-target='#StockSubscription'";
                    echo '<div class="notify_me transition_3"><span class="glyphicon glyphicon-plus" style="font-size: 16px;" data-toggle="modal" data-target="#StockSubscription"> '.$_e['Notify me when product available'].'</span></div>';
                } ?>


                <!---->
                <!--                    <div class="notify_me transition_3"><span class="glyphicon glyphicon-plus" style="font-size: 16px;" data-toggle="modal" data-target="#ProductSubscribe"> --><?php //echo $_e['Send email on sale offer']; ?><!--</span></div>-->
                <!---->
                <!--                    <div class="notify_me transition_3"><span class="glyphicon glyphicon-plus" style="font-size: 16px;" data-toggle="modal" data-target="#referToFriend"> --><?php //echo $_e['Refer to a friend']; ?><!--</span>  </div>-->
            </div>


        </div>

    </div><!--all_items end-->


    <div class="cd-tabs hidden-md hidden-lg">
        <nav>
            <ul class="cd-tabs-navigation">
                <li><a data-content="inbox" class="selected"  href="#"><?php echo $_e['DESCRIPTION']; ?></a></li>
                <li><a data-content="specification" href="#"><?php echo $_e['Specification']; ?></a></li>
                <li><a data-content="sizechart" href="#"><?php echo $_e['Size Chart']; ?></a></li>
                <li><a data-content="new" href="#"><?php echo $_e['SHIPPING & RETURNS']; ?></a></li>
                <li><a data-content="gallery" href="#"><?php echo $_e['REVIEWS']; ?></a></li>

                <?php if($questionAllow=='1'){ ?>
                    <li><a data-content="ask" href="#"><?php echo $_e['Asked Questions']; ?></a></li>
                    <li><a data-content="askNew" href="#"><?php echo $_e['Ask Question']; ?></a></li>
                <?php } ?>
            </ul><!-- cd-tabs-navigation -->
        </nav>

        <ul class="cd-tabs-content">
            <li data-content="inbox" class="selected">
                <?php echo $pDesc; ?>
            </li>

            <li data-content="new">
                <?php echo $pReturnDesc; ?>
            </li>


            <li data-content="sizechart">
                <?php echo $size_chart; ?>
            </li>


            <li data-content="gallery">
                <div class="all_ratings">
                    <?php
                    $functions->_modelFile("classes/rating.php");
                    $rating     = new rating();
                    echo $rating->ratingView($pId,'value',$_e["Value"]);
                    echo "<div class='clearix'></div>";
                    echo $rating->ratingView($pId,'quality',$_e["Quality"]);
                    echo "<div class='clearix'></div>";
                    echo $rating->ratingView($pId,'price',$_e["Price"]);
                    ?>
                </div>

                <?php echo $reviews; ?>
                <hr>
                <?php echo $facebookComments; ?>
            </li>

            <?php if($questionAllow=='1'){ ?>
                <li data-content="ask">
                    <?php echo $askQuestion; ?>
                </li>
                <li data-content="askNew">
                    <?php echo $askQuestionForm; ?>
                </li>
            <?php } ?>

            <li data-content="specification">
                <?php echo $specificationDesc; ?>
            </li>
        </ul> <!-- cd-tabs-content -->

    </div> <!-- cd-tabs -->



    </div><!--pro_right_panel end-->

    <div class="cd-tabs hidden-xs hidden-sm">
        <nav>
            <ul class="cd-tabs-navigation">
                <li><a data-content="inbox" class="selected" href="#0"><?php echo $_e['DESCRIPTION']; ?></a></li>
                <li><a data-content="specification" href="#"><?php echo $_e['Specification']; ?></a></li>
                <li><a data-content="sizechart" href="#"><?php echo $_e['Size Chart']; ?></a></li>
                <li><a data-content="new" href="#0"><?php echo $_e['SHIPPING & RETURNS']; ?></a></li>
                <li><a data-content="gallery" href="#0"><?php echo $_e['REVIEWS']; ?></a></li>
                <li><a data-content="ask"  href="#"><?php echo $_e['Asked Questions']; ?></a></li>
                <li><a data-content="askNew"  href="#"><?php echo $_e['Ask Question']; ?></a></li>

            </ul><!-- cd-tabs-navigation -->
        </nav>

        <ul class="cd-tabs-content">
            <li data-content="inbox" class="selected">
                <?php echo $pDesc; ?>
            </li>

            <li data-content="new">
                <?php echo $pReturnDesc; ?>
            </li>


            <li data-content="sizechart">
                <?php echo $size_chart; ?>
            </li>

            <li data-content="gallery">
                <div class="all_ratings">
                    <?php
                    $functions->_modelFile("classes/rating.php");
                    $rating = new rating();
                    echo $rating->ratingView($pId,'value',"Value");
                    echo "<div class='clearix'></div>";
                    echo $rating->ratingView($pId,'quality',"Quality");
                    echo "<div class='clearix'></div>";
                    echo $rating->ratingView($pId,'price',"Price");
                    ?>
                </div>


                <?php echo $reviews; ?>
                <hr>
                <?php echo $facebookComments; ?>
            </li>

            <li data-content="ask">
                <?php echo $askQuestion; ?>
            </li>

            <li data-content="askNew">
                <?php echo $askQuestionForm; ?>
            </li>
            <li data-content="specification">
                <?php echo $specificationDesc; ?>
            </li>

        </ul> <!-- cd-tabs-content -->
    </div> <!-- cd-tabs -->

    <div class="one_main_div_panel">

        <div class="more_items_panel">
            <div class="more_head"><?php echo $_e['DO NOT FORGET TO BUY']; ?></div>
            <?php echo $productClass->dontForgetToBuyProduct($pId,$relatedIds,'2',2); ?>
        </div><!--more_items_panel end-->


        <div class="two_div_panels">
            <div class="more_items_panel more_one">
                <div class="airoh_head"><?php echo $_e['PRODUCT FEATURE ICONS']; ?>:</div>
                <div class="all_icons">
                    <?php echo $featureIcon;     ?>
                </div>
            </div><!--more_items_panel end-->


            <div class="more_items_panel more_one">
                <?php $box = $webClass->getBox('box9'); ?>
                <div class="k_logo"><img src="<?php echo $box['image'] ?>" alt="<?php echo $box['heading'] ?>"></div>
                <div class="k_text">
                <span style="color:#000;">
                    <?php echo $box['heading']; ?>
                </span><?php echo textArea($box['text']); ?>
                </div>
            </div><!--more_items_panel end-->

        </div>


        <?php if(!empty($pFeaturePoints)){ ?>
            <div class="more_items_panel fea_panel">
                <div class="points">
                    <?php echo $pFeaturePoints; ?>
                </div>
            </div><!--more_items_panel end-->
        <?php } ?>


    </div>

    <?php
    //$randomReview = $blogC->reviewRandom($pId,'product',$myReview,$reviewOff);
    $randomReview = "";
    if(!empty($randomReview)){
        ?>
        <div class="more_items_panel">
            <div class="more_head"><?php echo _uc($_e['WHAT DO OUR CUSTOMER SAY?']); ?></div>
            <div class="customer_img container-fluid padding-0">
                <h3 class="text-center well well-sm margin-0 padding-0">
                    <?php echo $functions->webUserName($randomReview['user_id'],'acc_name'); ?>
                </h3>
                <div class="lead margin-0 padding-0"><?php echo $randomReview['subject']; ?></div>
                <div class="padding-F5"><?php echo $randomReview['comment']; ?></div>
            </div>
        </div><!--more_items_panel end-->
    <?php } ?>


    <div class="related_products_area">
        <div class="related_head">

            <div class="related_head_text"><h2 class="detail_reco_head"><?php echo _uc($_e['YOU MIGHT ALSO LIKE']); ?></h2></div>

        </div>


        <div class="r_box_area owl-carousel3">
            <?php echo $productClass->youMayAlsoLikeProduct($pId,8,'1'); ?>
        </div><!--r_box_area end-->
    </div><!--related_products_area end-->


    <div class="related_products_area">
        <div class="related_head">

            <div class="related_head_text"><h2 class="detail_reco_head"><?php echo _uc($_e['Related Products']); ?></h2></div>


        </div>

        <div class="r_box_area owl-carousel2">
            <?php echo $productClass->relatedProduct($pId,8,'1'); ?>
        </div><!--r_box_area end-->
    </div><!--related_products_area end-->

    </div><!--right_panle end-->




    </div><!--align end-->
    </div><!--content_area end-->

    <script src="<?php echo WEB_URL; ?>/js/mainTabs.js"></script>