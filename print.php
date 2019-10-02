<?php include("global.php");
global $webClass;
global $_e;
global $productClass;

//work for htaccess file..
if(isset($_GET['pId'])){
    $pId = $_GET['pId'];
    if(stristr($pId,"-")){
        $pId = explode("-",$pId,2);
        $_GET['pId']    = $pId[0];
        @$_GET['pName'] = $pId[1];
    }
}

//work for product slug
if(isset($_GET['pSlug'])){
    $pSlug = ($_GET['pSlug']);
    $sql = "SELECT prodet_id FROM proudct_detail WHERE slug = '$pSlug'";
    $productSlug = $dbF->getRow($sql);
    $pId = $productSlug['prodet_id'];
    $_GET['pId']    = $pId;
}

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
$_e = $dbF->hardWordsMulti($_w, currentWebLanguage(), 'Web Product Detail');


$pId = isset($_GET['pId']) ? floatval($_GET['pId']) : 0;

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
$pFeaturePoints = translateFromSerialize($productClass->productF->productSettingArray('featurePoints', $pSetting, $pId));
$pAdditionalInfo = translateFromSerialize($productClass->productF->productSettingArray('tags', $pSetting, $pId));
$pReturnDesc = $pAdditionalInfo;
$featureIcon = translateFromSerialize($productClass->productF->productSettingArray('featureIcon', $pSetting, $pId));
$pShrtDesc  = translateFromSerialize($data['prodet_shortDesc']);

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
    $stockStatus = _uc($_e['In Stock']);
} else {
    $stockStatus = _uc($_e['Out Stock']);
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

?>

    <!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/hover.css"/>
<link rel="stylesheet" href="<?php echo WEB_ADMIN_URL ?>/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo WEB_ADMIN_URL ?>/assets/bootstrap/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/commonuse.css"/>
<link rel="stylesheet" type="text/css"  media="print"  href="<?php echo WEB_URL ?>/css/print.css">

<script type="text/javascript" src="<?php echo WEB_URL ?>/js/jquery.js"></script>

<script type="text/javascript" defer="defer" src="<?php echo WEB_URL ?>/js/jquery.slicknav.min.js"></script>

<script type="text/javascript" defer="defer" src="<?php echo WEB_URL ?>/js/jquery.ulslide.js"></script>
<script type="text/javascript" defer="defer" src="<?php echo WEB_URL ?>/js/jquery-ui.js"></script>
<script type="text/javascript" defer="defer" src="<?php echo WEB_URL ?>/js/product.php"></script>
<input type="hidden" class="txt_search"/>
    <script>
        $(document).ready(function () {
            <?php if($isSingleProduct){ ?>
            productStockCheck(<?php echo $pId; ?>, 0, 0);
            <?php } ?>
        });
    </script>

    <!--<div id="fb-root"></div>
    <script>
        facebookSocial = function() {
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=551931871510090&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        }
      setTimeout(facebookSocial,<?php echo $db->setTimeOutSocial; ?>);
    </script>-->

    <!--Fb Twitter and google share buttons save here to use in future easly-->
    <!--<div class="detail_top_r_icons">
        <?php /*$pageLink = WEB_URL."/detail.php?pId=".$pId; */ ?>
        <table valign="middel">
            <td style="padding:0 5px"><div class="fb-share-button" data-href="<?php /*echo $pageLink; */ ?>" data-layout="button_count"></div></td>
            <td>
                <div class="twitter-share">
                    <a class="twitter-share-button" href="<?php /*echo $pageLink; */ ?>"
                       data-related="twitterdev"
                       data-count="yes">
                        Tweet
                    </a>
                    <script>
                        function TwitterSocial(){
                            window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
                        }
                        setTimeout(TwitterSocial(),<?php /*echo $db->setTimeOutSocial; */ ?>);
                    </script>
                </div>
            </td>

            <td>
                <script src="https://apis.google.com/js/platform.js" async defer></script>
                <div class="g-plus" data-action="share" data-annotation="bubble"></div>
            </td>


        </table>
    </div>-->

    <!--Product Images-->
<?php
/*
 * $allImages  =   $productClass->productAllImage($pId);
foreach($allImages as $val){
    $img = $val['image'];
    $imgSize1 = $functions->resizeImage($img,100,'auto',false);
    $imgSize2 = $functions->resizeImage($img,430,530,false);
    $real       =   WEB_URL."/images/".$img;
    $alt = $val['alt'];

    echo "<div class='col-xs-3 padding-0'>
        <img  class='cloudzoom-gallery img-responsive' alt='$alt'
         src='$imgSize1'
         data-cloudzoom=\"useZoom: '.cloudzoom',
    image: '$imgSize2',
    zoomImage: '$real'  \">
    </div>";

}
*/
?>

    <!--Small Status , total view, in stock, label Name-->
    <!--
        <div class="container-fluid padding-0">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            <span><?php /*echo $pViews;*/ ?></span>
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span><?php /*echo $stockStatus; */ ?></span>
            <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
            <span><?php /*echo $label; */ ?></span>
        </div>
    -->

    <!--Stock quantity or AddtoCart OR wishList-->
    <!--
        <small id="stock_<?php /*echo $pId; */ ?>"></small>
        <a onclick="addToWishList(this,1091);" class="btn-default futura_bk_bt">
           Add TO
        </a>

        <a onclick="addToCart(this,<?php /*echo $pId; */ ?>);"
           class="add_cart cursor AddToCart_<?php /*echo $pId; */ ?>" style="white-space: nowrap;">
            <?php /*echo $_e['Add To Cart']; */ ?>
        </a>
        -->

    <!--<a href="#" data-toggle="modal" data-target="#ProductSubscribe" >Subscribe On Sale</a>
        <br>
        <a href="#" data-toggle="modal" data-target="#referToFriend" >Refer To Friend</a>-->

    <div id="fb-root"></div>
    <script>
        facebookSocial = function () {
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=551931871510090&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        }
        setTimeout(facebookSocial, <?php echo $db->setTimeOutSocial; ?>);
    </script>


    <link rel="stylesheet" type="text/css" href="<?php echo WEB_URL ?>/css/cloudzoom.css"/>
    <script type="text/javascript" src="<?php echo WEB_URL ?>/js/cloudzoom.js"></script>
    <script type="text/javascript">
        CloudZoom.quickStart();
        $(document).ready(function () {
            // Bind a click event to a Cloud Zoom instance.
            $('.onClickCloudZoomShowBig').bind('click', function () {
                // On click, get the Cloud Zoom object,
                var cloudZoom = $(this).data('CloudZoom');
                // Close the zoom window (from 2.1 rev 1211291557)
                cloudZoom.closeZoom();
                // and pass Cloud Zoom's image list to Fancy Box.
                $.fancybox.open(cloudZoom.getGalleryList());
                return false;
            });
        });
    </script>


<?php $webClass->seoSpecial();  ?>


    <div class="content_area">
    <div class="align"   id="p<?php echo $pId; ?>">

    <div class="pro_left_panel">
        <?php
            echo $reviewMsg;
            echo $jsInfo;
        ?>
        <div class="pro_left_panel_in">
            <div class="big_img">
                <img class="cloudzoom onClickCloudZoomShowBig" style="max-height:100%;" alt=""
                     src="<?php echo $productThumb; ?>"
                     data-cloudzoom="zoomImage: '<?php echo $productImage; ?>',
                                  zoomSizeMode : 'image',autoInside: true, zoomPosition: 'inside',zoomOffsetX:0">
            </div><!--big_img end-->



        </div> <!--pro_left_panel_in end-->

        <div class="cd-tabs hidden-xs hidden-sm">
            <nav>
                <ul class="cd-tabs-navigation">
                    <li><a data-content="inbox" class="selected" href="#0"><?php echo $_e['DESCRIPTION']; ?></a></li>
                </ul><!-- cd-tabs-navigation -->
            </nav>

            <ul class="cd-tabs-content">
                <li data-content="inbox" class="selected">
                    <?php echo $pDesc; ?>
                </li>

            </ul> <!-- cd-tabs-content -->
        </div> <!-- cd-tabs -->
    </div><!--pro_left_panel end-->



    <div class="pro_right_panel">
        <div class="all_items">
            <div class="pro_text">
                <?php echo $pName; ?><br>
                <span><?php echo $label; ?></span>
            </div>

            <div class="pro_prize">
                <ul>
                    <li class="p_style1"><?php echo $_e['Price']; ?></li>
                    <li class="p_style1"><?php
                        echo $newPriceDiv;
                        ?></li>
                </ul>
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
            </div><!--pro_prize end-->

            <div class="pro_prize pro_available">
                <ul>
                    <li class="p_style2" style="color:#000;"><?php echo $_e['Availability']; ?></li>
                    <li class="p_style2" style="color:#f7961d;"><?php echo $stockStatus; ?></li>
                </ul>
                <div style="clear:both"></div>

            </div><!--pro_prize end-->

            <div class="pro_cart">
                <div class="select">
                    <div id="size_color" class="container_detail_RightR_color choice_color">
                        <!-- Heading -->
                        <?php echo $colorDiv; ?>
                    </div>

                    <div class="detail_top_r_size" id="size_radio">
                        <?php echo $scaleDiv; ?>
                    </div>

                    <div class="padding-0" style="  height: 34px;  margin-top: 10px;">
                        <small id="stock_<?php echo $pId; ?>"></small>
                    </div>

                </div>
                <div class="cart_btn_area">

                    <?php if($functions->developer_setting('addQty_custome')=='1') {
                        echo "<div class='container-fluid padding-0 form-horizontal'>
                                <label class='col-xs-3 control-label padding-0' style='line-height:20px'>Quantity </label>
                                <div class='col-xs-9 '>
                                    <input type='number' min='1' value='' pattern='[0-9]{1,5}' class='form-control addByQty_$pId' />
                                    <input type='hidden' value='1' class='addByQty_hidden_$pId' />
                                </div>
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
                    <a onclick="addToWishList(this,<?php echo $pId; ?>);" class="btn btn-xs btn-default futura_bk_bt">
                        <?php echo $_e['Add To Wishlist']; ?>
                    </a>

                </div>

            </div>
            <div class="clearfix"></div>

            <div class="detail_top_r_icons text-center">

                <div class="container-fluid text-left padding-f10 margin-5">
                    <?php if(!$isSale){ ?>
                        <i class="fa fa-rss cursor" style="font-size: 16px;" data-toggle="modal" data-target="#ProductSubscribe"> <?php echo $_e['Send email on sale offer']; ?></i>
                    <?php } ?>
                    <i class="fa fa-envelope cursor" style="font-size: 16px;" data-toggle="modal" data-target="#referToFriend"> <?php echo $_e['Refer to a friend']; ?></i>
                </div>

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
            </div>

        </div><!--all_items end-->




        <div class="more_items_panel">
            <div class="more_head"><?php echo $_e['DO NOT FORGET TO BUY']; ?></div>
            <?php echo $productClass->dontForgetToBuyProduct($pId,$relatedIds,'2',2); ?>
        </div><!--more_items_panel end-->

        <?php if(!empty($pFeaturePoints)){ ?>
            <div class="more_items_panel">
                <div class="points">
                    <?php echo $pFeaturePoints; ?>
                </div>
            </div><!--more_items_panel end-->
        <?php } ?>







        <?php $randomReview = $blogC->reviewRandom($pId,'product',$myReview,$reviewOff);
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

    </div><!--pro_right_panel end-->

    </div><!--align end-->
    </div><!--content_area end-->




<script src="<?php echo WEB_URL; ?>/js/mainTabs.js"></script>
<?php

echo $productClass->productSubscribeOnSale($pId);
echo $productClass->referToFriend($pId);

 ?>