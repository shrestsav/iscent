<?php include("global.php");
global $webClass;
global $_e;
global $productClass;
// var_dump($_POST);
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
$_w['Select a color'] = '';
$_w['Select Scale'] = '';
$_e = $dbF->hardWordsMulti($_w, currentWebLanguage(), 'Web Product Detail');


$pId        = isset($_GET['pId']) ? floatval($_GET['pId']) : 0;
$WEB_URL    = WEB_URL;
require_once(__DIR__ . "/" . ADMIN_FOLDER . "/product_management/functions/product_function.php");
require_once(__DIR__ . "/" . ADMIN_FOLDER . "/product/classes/product.class.php");
$product = new product();
$productF = new product_function();
$webLang = currentWebLanguage();
$defaultLang = defaultWebLanguage();

$subsMsg = $productClass->productSubscribeOnSaleSubmit();
$subsMsg1 = $productClass->subscribed_on_stock_availability_submit();
$referMsg = $productClass->referToFriendSubmit();
//$sql = "SELECT * FROM proudct_detail WHERE prodet_id = '$pId'";
$data = $productClass->productData($pId);


$proCat = $productClass->proCategories($pId, 5);

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
$videoDiv = '<a href="' . $video . '" class="shrink fancybox-video">
<img src="' . WEB_URL . '/images/video_icon.png" />
</a>';

$videoDiv .= "<script>
$(document).ready(function(){
$('.fancybox-video').fancybox({
'width'             : '75%',
'height'            : '100%',
'autoScale'         : false,
'type'              : 'iframe'
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
$productThumb = $webClass->resizeImage($productImage, 619, 680, false);
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
if(isset( $discount['discount'] ) && $discount['discount']>'0' ){
$isSale     =   true;
}


// var_dump($pId);

// var_dump($currencyId);
$discountPrice = $productClass->productF->discountPriceCalculation($pPrice, $discount);
$newPrice = $pPrice - $discountPrice;

$hasDiscount = false;

if ($newPrice   != $pPrice) {
$hasDiscount = true;
$oldPriceDiv = '<span class="oldPrice tabprice"><span class="productOldPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . '</span>';
$newPriceDiv = '
<span class="NewDiscountPrice1"><span class="productPrice_' . $pId . '">' . $newPrice . '</span> ' . $currencySymbol .
'</span>
<div class="pop_price_inside2">' . $pPrice .  $currencySymbol . '
</div> ';
} else {
$oldPriceDiv = "";
$newPriceDiv = '<span class="NewDiscountPrice1">
<span class="productPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . '
</span>

';
}


//Work For discounted value Box

$discountValue = $discountP;
if ($discountFormat == "percent") {
$discountValue .= " %";
} else {
$discountValue = $discountValue . " $currencySymbol";
}

$isSale = false;
if (isset($discount['isSale']) && $discount['isSale'] == '1') {
$isSale = true;
}

$isDiscount = false;
if ($newPrice != $pPrice) {
$isDiscount = true;
}

$saleDiv = "";
if ($isSale) {
//Sale
$saleDiv = "
<div class='off_price'>
<h4>$discountValue
" . $_e['SALE'] . "</h4>
</div>";
} else if ($isDiscount) {
//Discount
$saleDiv = "
<div class='off_price'>
<h4>$discountValue
" . $_e['DISCOUNT'] . "</h4>
</div>";
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
// we will assume color not all ow from setting for javascript

$colorDiv = "";
$hasColorVal = 0;
$hasColor = false;
}


/*Make Color Divs,, print where you want*/
/*$colorDiv = "";
if ($hasColor) {
$colorDiv = $productClass->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
$colorDiv = "<div class='container_detail_RightR_color_heading'>
<p>" . _uc($_e['Color']) . "</p>" . $colorDiv . "</div>";
}*/




/* My Work
$colorDiv = "";
if ($hasColor) {
$colorDiv = $productClass->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
$colorDiv = "<div class='container_detail_RightR_color_heading'>
<p>" . _uc($_e['Color']) . "</p>" . "<select>" . $colorDiv .  "</select>" . "</div>";
}*/

?>
<?php

$colorDiv = "";
if ($hasColor) {
$colorDiv = $productClass->getColorsDiv($pId, $storeId, $pPrice, $currencyId, $currencySymbol, $discountP, $hasScale);
$colorDiv = "<div class='container_detail_RightR_color_heading'>
<dl id='sample' class='dropdown'> <dt><a> <span>" . $_e['Select a color'] . "</span></a></dt><dd>
<ul> " . $colorDiv .  "    </ul>
</dd></dl>" . "</div>";

}



//Make Scale Divs,, print where you want
$isCustomSize = false;
$customSizeForm = '';

$scaleDiv = "";

if ($hasScale) {
//var_dump($pId, $storeId, $currencyId, $currencySymbol, $hasColor);

$scaleDiv = $productClass->getScalesDiv($pId, $storeId, $currencyId, $currencySymbol, $hasColor);

}
//Custom Size
if ($functions->developer_setting('product_customSize') == '1') {
$sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
$customData = $dbF->getRows($sql);

if (!empty($customData)) {
@$customSize    = $customData[0]['type_id'];
if ($customSize != "0" && !empty($customSize)) {
$isCustomSize = true;

$customSizeForm = $productClass->customSizeForm($pId, $customSize);
if ($customSizeForm == false) {
$isCustomSize = true;
$customSize = 0;
}else{
$customSize_price = $productClass->customSizeArrayFilter($customData, $currencyId);
$customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
$onclick    = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '" id="custom_size_button" ';
$scaleDiv   = $scaleDiv . $productClass->getScaleDivFormat($pId, $_e['Custom'], -1, $customSize_price, -1, '', $onclick);
}
}//if customSize==0 end
} //if customData end
} // if developer setting end
//Custom Size End

##############################################################################################
$box = $webClass->getBox('box11');
$made_to_measure_box = $box['text'];
$customSizeForm = $made_to_measure_box . $customSizeForm;

/*if(!empty($scaleDiv)){
$scaleDiv = "<div class='container_detail_RightR_color_heading'><p>" . _uc($_e['Size']) . "</p>" . $scaleDiv . "</div>";
}*/







if(!empty($scaleDiv)){
$scaleDiv = "<div class='container_detail_RightR_color_heading'>
<dl id='sample_select' class='dropdown_select'> <dt><a> <span>" . $_e['Select Scale'] . "</span></a></dt><dd>
<ul> " . $scaleDiv .  "    </ul>
</dd></dl>" . "</div>";
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
$reviews   = $blogC->reviews($pId, 'product', 3, true, $myReview, $reviewOff);
} else if ($reviewOff != '') {
$reviews = "<hr><div class='reviewoffMsg alert alert-warning  margin-0'>$reviewOff</div>";
}

$questionOff    =   $productClass->productF->productSettingArray('questionOffMsg', $pSetting, $pId);
$questionAllow  =   $productClass->productF->productSettingArray('askQuestion', $pSetting, $pId); // 1 or 0 from single product
$askQuestion    =   '';
$askQuestionForm=   $askQuestion;
if ($questionAllow == '1' || empty($questionOff)) {
if(empty($reviewMsg)) {
//if $reviewMsg is not empty ,, so ask question definatly not submit.... so no need to call this function, or save previous msg to print
$reviewMsg = $blogC->askQuestionSubmit();
}
$askQuestion = $blogC->askQuestion($pId, 'question',false,$questionAllow, $questionOff);
$askQuestionForm = $blogC->askQuestionForm($pId,'question');
}else if ($questionOff != '') {
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

//Seo
$arryySeo = array();
$arraySeo['title'] = $pName;
$arraySeo['description'] = $pShrtDesc;
$arraySeo['image'] = $productImage;
$arraySeo['price'] = $priceDefault;
$arraySeo['currency'] = $currencySymbol;
$arraySeo['shipping'] = $shipClassPrice;
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

######### 3 For 2 Category ########
$three_for_2_category = $productF->check_product_in_3_for_2($pId);
if($three_for_2_category){
$three_for_2_category = " <img src='".WEB_URL."/images/3for2.jpg' height='40' />";
}else{
$three_for_2_category = "";
}









include("header.php");
echo $subsMsg;
echo $subsMsg1;
echo $referMsg;
if ($isCustomSize) {
echo $functions->blankModal($_e["Custom Size"], "customF_$pId", $customSizeForm, "Close");
}

?>
<script>
$(document).ready(function() {
<?php if($isSingleProduct){ ?>
productStockCheck(<?php echo $pId; ?>, 0, 0);
<?php } ?>
});
</script>





<?php $webClass->seoSpecial(); 

$box22 = $webClass->getBox("box26"); 


$bannerImgs   = ( @$page['image'] ==  WEB_URL . '/images/' || @$page['image'] === NULL ) ?  $box22['image'] : @$page['image'];
?>
  <div class="divide" style="background: url(<?php echo $bannerImgs ?>);">
<div class="standard">
<h1>Product Detail</h1>
</div>
</div>
<div class="about_col">
<div class="standard">
<div class="add_to_cart_side">
<div class="add_to_cart_main_pic_slide">
<div class="inside_slide123">
<div class="image_slider"> <img src="<?php echo str_replace("_th","",$productImage); ?>" alt="" class="hvr-grow"> </div>
<!-- image_slider close -->
<div class="product_owl1">
<div class="all7">




<?php 
$allImages = $productClass->productAllImage($pId);
//echo "<pre>"; print_r($allImages); echo "</pre>";
foreach ($allImages as $val) {
$img = $val['image'];
$imgSize1 = $functions->resizeImage($img, 450, '300', false);
$imgSize2 = $functions->resizeImage($img, 619, 613, false);
$real = WEB_URL . "/images/" . $img;
$alt = $val['alt'];
?>

<div class="slide1"> <img src="<?php echo $imgSize1; ?>" alt=""> </div>
<?php


}
?>


<!-- slide1 close -->
<!-- <div class="slide1"> <img src="webImages/p3.jpg" alt=""> </div> -->
<!-- slide1 close -->
<!-- <div class="slide1"> <img src="webImages/p4.jpg" alt=""> </div>
<div class="slide1"> <img src="webImages/p5.jpg" alt=""> </div> -->
<!-- slide1 close -->
</div>
<!-- all7 close -->
<div class="btns_area1">
<div class="left_btn1">
<!-- <img src="webImages/left_btn alt="" --></div>
<!-- left_btn1 active hvr-push close -->
<div class="right_btn1">
<!-- <img src="webImages/left_btn alt="" --></div>
<!-- right_btn1 close-->
</div>
<!-- btns_area1 close -->
<script>
$(function() {
$("#tabs").tabs();
});
</script>
<script>
$(document).ready(function() {

    <?php if(count($allImages) > 1){?>


$('.all7').owlCarousel({
loop: true,
navigation: true,
autoplay: true,
autoplayTimeout: 3000,
autoplayHoverPause: true,
items: 4,
responsiveClass: true,
responsive: {
0: {
items: 2,
nav: true
},
300: {
items: 2,
nav: false
},
400: {
items: 2,
nav: false
},
500: {
items: 2,
nav: false
},
600: {
items: 2,
nav: false
},
750: {
items: 3,
nav: true,
},
800: {
items: 3,
nav: true,
},
900: {
items: 3,
nav: true,
},
1000: {
items: 4,
nav: true,
},
1200: {
items: 4,
nav: true,
},
1280: {
items: 4,
nav: true,
}
}
})

<?php } ?>
});
$(".left_btn1").click(function() {
var owl = $(".all7").data('owlCarousel');
owl.next() // Go to next slide
});
$(".right_btn1").click(function() {
var owl = $(".all7").data('owlCarousel');
owl.prev() // Go to previous slide
});
</script>
</div>
<!-- product_owl1 close -->
</div>
<!-- inside_slide123 close -->
</div>
<!-- add_to_cart_main_pic_slide close -->
<div class="add_to_cart_main_pic_responsive">
<div class="owl_4">
<div class="all4">
<!-- <div class="slide4"> <img src="webImages/p5.jpg" alt=""> </div> -->
<!-- slide4 close -->


<?php 
$allImages = $productClass->productAllImage($pId);
//echo "<pre>"; print_r($allImages); echo "</pre>";
foreach ($allImages as $val) {
$img = $val['image'];
$imgSize1 = $functions->resizeImage($img, 400, '250', false);
$imgSize2 = $functions->resizeImage($img, 619, 613, false);
$real = WEB_URL . "/images/" . $img;
$alt = $val['alt'];
?>


<div class="slide4"> <img src="<?php echo $real; ?>" alt=""> </div>

<?php


}
?>





<!-- slide4 close -->
</div>
<!-- all4 close -->
<script>
$(document).ready(function() {
    <?php if(count($allImages) > 1){?>
$('.all4').owlCarousel({
loop: true,
navigation: true,
autoplay: true,
autoplayTimeout: 3000,
autoplayHoverPause: true,
items: 3,
responsiveClass: true,
responsive: {
0: {
items: 1,
nav: true
},
300: {
items: 1,
nav: false
},
400: {
items: 1,
nav: false
},
500: {
items: 1,
nav: false
},
600: {
items: 1,
nav: false
},
750: {
items: 1,
nav: true,
},
800: {
items: 1,
nav: true,
},
900: {
items: 1,
nav: true,
},
1000: {
items: 1,
nav: true,
},
1200: {
items: 1,
nav: true,
},
1280: {
items: 1,
nav: true,
}
}
})

<?php } ?>
});
$(".left_btn122").click(function() {
var owl = $(".all4").data('owlCarousel');
owl.next() // Go to next slide
});
$(".right_btn122").click(function() {
var owl = $(".all4").data('owlCarousel');
owl.prev() // Go to previous slide
});
</script>
</div>
<!-- owl_4 close -->
</div>
<!-- add_to_cart_main_pic_responsive close -->
<div class="add_product_to_cart">
<div class="inside_cart_inner">
<div class="pop_info_cart">
<h3>
<?php echo $pName; ?>
</h3>
<div class="pop_menu">
<!-- <ul> -->
<!-- <li> <a href="#">PPRC Pipes</a> </li> -->
<!-- <li> <a href="#">PPRC Pipes</a> </li>
<li> <a href="#">PPRC Pipes</a> </li>
<li> <a href="#">PPRC Pipes</a> </li> -->


<ul>
<?php 
//$pCate = '';
foreach ($proCat as $key => $value) {
if($key != 1){
$seoLink = WEB_URL.'/pCategory-'.$key;
echo '<li> <a href="'.$seoLink.'">'.$value.',</a> </li>';
}
}
?>
</ul>



<!-- </ul> -->
</div>
<!-- pop_menu close -->
<div class="fashion_text_side12"><?php  echo $pShrtDesc; ?> </div>
<!-- fashion_text_side12 close -->
<div class="pop_price_main_side">

<div class="pop_price_inside"> <?php  echo $newPriceDiv; ?> </div>
<!-- pop_price_inside close -->

<!-- <div class="pop_price_inside2"> <?php  #echo $oldPriceDiv; ?> </div> -->

</div>
<!-- pop_price_main_side close -->
<div class="pop_price_main_side_txt"> Availability: <span><?php  echo $stockStatus_T; ?> </span> </div>
<!-- pop_price_main_side_txt close -->
<div class="pop_price_main_side_txt2">

 <!-- Shipping Class : Karachi-PK : 500 PKR -->


  </div>
<!-- pop_price_main_side_txt close -->
<div class="pop_price_main_side_txt3" style=""> Quantity:
<form id='myform' method='POST' action='#'>
<input type="number" placeholder="1" value="1" name="quantity" min="1" class='addByQty_<?php echo $pId ?>'> 



<!-- <input type='button' class='qtyminus' field='quantity' /> -->
<!-- <input type='text' name='quantity' min='1' value='1' class='qty addByQty_$pId' /> -->
<!-- <input type='button' class='qtyplus' field='quantity' /> -->
<input type='hidden' value='1' class='addByQty_hidden_<?php echo $pId ?>' />


</form>



</div>
<!-- pop_price_main_side_txt close -->
<div class="cart_btn AddToCart_<?php echo $pId ?>"> <a href="#" onclick="addToCart(this,<?php echo $pId; ?>);">ADD TO CART</a> </div>
<!-- cart_btn close -->

</div>
</div>
<!-- pop_info_cart close -->
</div>
<!-- inside_cart_inner close -->
<div id="tabs">
<ul>
<li><a href="#tabs-1">DESCRIPTION</a></li>
<!-- <li><a href="#tabs-2">SIZE GUIDE</a></li>
<li><a href="#tabs-3">REVIEWS</a></li> -->
</ul>
<div id="tabs-1">
<?php echo $pDesc; ?>
</div>
<!-- <div id="tabs-2">
<p>Lorem ipsum dolor sit amet, aliquid percipit repudiare pro an, pri iriure vivendum no. Salutandi patrioque interesset ut nec, an sit latine fierent consulatu. Mei accumsan maiestatis necessitatibus no, amet ipsum cu per. Maluisset abhorreant vel eu, vel an suscipit singulis. Usu ea unum essent. Sea alienum epicuri cu, vix ancillae nominati ad, ea erat affert gubergren has.
<br>
<br> Euismod mediocritatem an has, has dicta option ad. An sit velit nulla munere, nec ad porro torquatos, in pro utroque lobortis facilisis. Ius ne apeirian pertinax, ex aliquid delectus pro. Per duis dicant patrioque ne, nec purto meliore ne. Vide virtute mei id, no vix doctus erroribus, vim at tale errem.</p>
</div> -->
<!-- <div id="tabs-3">
<p>Lorem ipsum dolor sit amet, aliquid percipit repudiare pro an, pri iriure vivendum no. Salutandi patrioque interesset ut nec, an sit latine fierent consulatu. Mei accumsan maiestatis necessitatibus no, amet ipsum cu per. Maluisset abhorreant vel eu, vel an suscipit singulis. Usu ea unum essent. Sea alienum epicuri cu, vix ancillae nominati ad, ea erat affert gubergren has.
<br>
<br> Mollis cetero nostrud id sit, nam essent audire an. Mei aliquip noluisse erroribus ad, per no eripuit sententiae, ornatus vivendo euripidis mei ne. An pro justo convenire. Ceteros recteque posidonium eu duo, mollis blandit salutandi nec in, ei mei adhuc meliore concludaturque. Mentitum intellegat definiebas ea mei. Phaedrum pertinax usu cu, an clita oblique civibus duo. Ex cibo dolore ius. Movet tation nullam ut duo. At tota meliore sit, timeam scribentur cu quo. Est ea tota idque eligendi, nec in consul verterem.</p>
</div> -->
</div>
<div class="col1 wow fadeInDown">
<div class="standard">
<div class="col1_main">
<div class="all1">

<?php 


echo $productClass->matchingProduct($pId,8,'Grid');

?>
<!-- <div class="col1_box">
<a href="#">
<div class="col1_box_img"> <img src="webImages/p1.jpg" alt=""> </div>
<div class="col1_box_txt">
<h3>Example 1</h3>
<h2>PKR 3,990.00</h2>
<h4>PKR 4,200.00</h4>
<div class="col1_box_btn"> <span>ADD TO CART</span> </div>
</div>
</a>
</div> -->




</div>
<!-- all1 close -->
<div class="all1_btn">
<div class="btn3 hvr-pulse-grow active">
<!-- button -->
</div>
<!-- btn1 close -->
<div class="btn4 hvr-pulse-grow active">
<!-- button -->
</div>
<!-- btn2 close -->
</div>
<!-- all1_btn close -->
<script>
$(document).ready(function() {

// if ($('.all1').children().length > 1) {

$('.all1').owlCarousel({
loop: true,
navigation: true,
autoplay: true,
autoplayTimeout: 3000,
autoplayHoverPause: true,
items: 4,
responsiveClass: true,
responsive: {
0: {
items: 1,
nav: true
},
300: {
items: 1,
nav: false
},
400: {
items: 2,
nav: false
},
500: {
items: 2,
nav: false
},
600: {
items: 3,
nav: false
},
750: {
items: 3,
nav: true,
},
800: {
items: 3,
nav: true,
},
900: {
items: 3,
nav: true,
},
1000: {
items: 4,
nav: true,
},
1200: {
items: 4,
nav: true,
},
1280: {
items: 4,
nav: true,
}
}
})

// }
});
$(".btn3").click(function() {
var owl = $(".all1").data('owlCarousel');
owl.next() // Go to next slide
});
$(".btn4").click(function() {
var owl = $(".all1").data('owlCarousel');
owl.prev() // Go to previous slide
});
</script>
</div>
<!-- col1_main close -->
</div>
<!-- standard close -->
</div>
<!-- col1 close -->
</div>
<!-- add_product_to_cart close -->
</div>
<!-- standard close -->
</div>
<!-- about_col close -->

<?php

include_once(__DIR__ . "/footer.php"); 

?>