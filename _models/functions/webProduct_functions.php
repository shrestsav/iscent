<?php

_traitFileInclude("webCart.php");

class webProduct_functions extends object_class
{
	use webcart;

	public $product;
	public $productF;
	public $webClass;
	private $totalProductFrNav;
	private $productLimit;
	public $comparePage = false;

	function __construct()
	{
		parent::__construct('3');
		$this->functions->includeAdminFile("product_management/functions/product_function.php");
		$this->functions->includeAdminFile("product/classes/product.class.php");
		//require_once(__DIR__."/../../".ADMIN_FOLDER."/product_management/functions/product_function.php");
		//require_once(__DIR__."/../../".ADMIN_FOLDER."/product/classes/product.class.php");


		$this->product = new product();
		$this->productF = new product_function();
		if (isset($GLOBALS['webClass'])) {
			$this->webClass = $GLOBALS['webClass'];
		} 

		// else {
		//     $this->functions->includeOnceCustom("_models/functions/webProduct_functions.php");
		// }


/**
* MultiLanguage keys Use where echo;
* define this class words and where this class will call
* and define words of file where this class will called
**/
global $_e;
$_w = array();
$_w['Products'] = '';
$_w['Close'] = '';
$_w['No Product Found'] = '';
$_w['Price'] = '';
$_w['In Stock'] = '';
$_w['Out Stock'] = '';
$_w['Color'] = '';
$_w['DISCOUNT'] = '';
$_w['Size'] = '';
$_w['No Product Found'] = '';
$_w['Specials'] = '';
$_w['Collection by Designer'] = '';
$_w['Compare Product'] = '';
$_w['Add to WishList'] = '';
$_w['BUY'] = '';
$_w['Details'] = '';
$_w['Show Details'] = '';
$_w['Product Quick View'] = '';
$_w['Email'] = '';
$_w['Three For Two Category'] = '';
$_w['SALE'] = '';
$_w['Subscribe'] = '';
$_w['Close'] = '';
$_w['From'] = '';
$_w['Lagg till i varukorgen'] = '';
$_w['Custom'] = '';
$_w['Subscribe Successfully'] = '';
$_w['Subscribe Fail'] = '';
$_w['Refer To Friend'] = '';
$_w['Refer to a Friend Description'] = '';
$_w['Email Send Successfully'] = '';
$_w['Email Send Fail'] = '';
$_w['Send Coupon Code'] = '';
$_w['CouponOffer'] = '';
$_w['Enter your email and get Latest Coupons Code'] = '';
$_w['Add To Cart'] = '';
$_w['Add To Wishlist'] = '';
$_w['Check Out Offer But Now & Get Special Discount'] = '';
$_w['I Accept'] = '';
$_w['Order Code'] = '';
$_w['User Info'] = '';
$_w['User Name'] = '';
$_w['Sale Trigger Form'] = '';
$_w['Stock Trigger Form'] = '';
$_w['Email'] = '';
$_w['Contact'] = '';
$_w['Menu'] = '';
$_w['Product Name'] = '';
$_w['Number Which Claims In'] = '';
$_w['Get New Item'] = '';
$_w['Get Money Back'] = '';
$_w['Want to switch to another product or get your money back?'] = '';
$_w['Buy Back'] = '';
$_w['Name of your bank'] = '';
$_w['sortCode'] = '';
$_w['Account Number'] = '';
$_w['When Replacing'] = '';
$_w['I want to change to'] = '';
$_w['Message'] = '';
$_w['Submit'] = '';
$_w['Submit DateTime'] = '';
$_w['Edit custom size form'] = '';
$_w['User not fill final form'] = '';
$_w['Submit now, But i will fill this form later'] = '';
$_w['Defect Image'] = '';
$_w['Return Product Save Successfully'] = '';
$_w['Product add to cart successfully'] = '';
$_w['Print PDF'] = '';
$_w['Send To Factory'] = '';
$_w['Your Gift card Id "{{giftId}}"  will be charged {{cartPrice}} from {{giftPrice}}'] = '';
$_w['Gift Card Id is Not Valid. Note: It is Case Sensitive.'] = '';
$_w['You have low price in you Gift card :{{giftPrice}}'] = '';
$_w['Your Gift card in ( {{giftCurrency}} ) currency, and not valid for ( {{cartCurrency}} ) currency'] = '';

$_w['Default'] = '';
$_w['By Low Price'] = '';
$_w['By High Price'] = '';
$_w['By Low Rate'] = '';
$_w['By High Rate'] = '';
$_w['By Low View'] = '';
$_w['By Top View'] = '';
$_w['By Low Sale'] = '';
$_w['By Top Sale'] = '';
$_w['Show'] = '';
$_w['EDIT'] = '';
$_w['SUBTOTAL'] = '';
$_w['ESTIMATED DELIVERY & HANDLING'] = '';
$_w['TOTAL'] = '';
$_w['REMOVE'] = '';
$_w['YOUR CART'] = '';
$_w['ITEM(s)'] = '';
$_w['COUNT(*)'] = '';
$_w['Payment Type'] = '';
$_w['NEXT STEP'] = '';
$_w['DELIVERY'] = '';
$_w['SUMMARY'] = '';
$_w['ESTIMATED DELIVERY & HANDLING'] = '';
$_w['Click to view your previous orders OR'] = '';
$_w['Continue Shopping'] = '';
$_w['Click to view your invoice OR'] = '';
$_w['ORDER PREVIEW'] = '';
$_w['Klarna = Faktura, Delbetalning, Kort & Internetbank'] = '';
$_w['CHECK OUT'] = '';
$_w['Payment Option'] = '';
$_w['Billing Country'] = '';
$_w['You Get +{{free_qty}} free'] = '';
$_w['Buy {{buy_qty}} Get 1 free'] = '';
$_w['Subscription for stock availability'] = '';
$_w['By Latest Added'] = '';
$_w['By Best Seller']  = '';
$_w['Search By Date Range']  = '';
$_w['You are already subscribed on this sale offer']  = '';
$_w['You are already subscribed for this product']  = '';
$_w['View']  = '';
$_w['Select Color']  = '';
$_w['Select Scale']  = '';
$_w['Sale']  = '';

$_e = $this->dbF->hardWordsMulti($_w, currentWebLanguage(), 'Website Products');
}

public function setStore()
{
	//Not Proper,,, Which is default and which store user select Not define
	$sql = "SELECT * FROM `store_name` ORDER BY store_pk ASC ";
	$data = $this->dbF->getRow($sql);
	$_SESSION['webUser']['store_Id'] = $data['store_pk'];
}

public function getStoreId()
{
	//Not Proper,,, Which is default and which store user select Not define
	if (isset($_SESSION['webUser']['store_Id'])) {

	} else {
		$sql = "SELECT * FROM `store_name` ORDER BY store_pk ASC ";
		$data = $this->dbF->getRow($sql);
		$_SESSION['webUser']['store_Id'] = $data['store_pk'];
	}
	return $_SESSION['webUser']['store_Id'];
}

public function setMultiCurrency()
{
	if (isset($_GET['currency']) && intval($_GET['currency']) > 0 && cur_define == false ) {
		unset($_SESSION['klarna_checkout']);
		$currData = $this->productF->currencyInfo($_GET['currency'], true);
		$this->setDefaultCurrencySession($currData);
	}elseif (isset($_GET['currency']) && intval($_GET['currency']) > 0){
		$currData = $this->productF->currencyInfo($_GET['currency'], true);
		$this->setDefaultCurrencySession($currData);
	}
}

public function multiCurrency($openRight = false)
{
	$currentCurrency = $this->currentCurrencySymbol();
	$temp = "";
	if ($openRight) {
		$openRight = " dropdown-menu-right";
	} else {
		$openRight = "";
	}
	$temp .= '<div class="dropdown">
	<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
	' . $currentCurrency . '
	<span class="caret"></span>
	</button>
	<ul class="dropdown-menu ' . $openRight . '" role="menu" aria-labelledby="dropdownMenu1">';

	$sql = "SELECT * FROM `currency` ORDER BY cur_id";
	$data = $this->dbF->getRows($sql);
	$link = $this->functions->defaultHttp . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$parm = false;
	if (!empty($_GET)) {
		$parm = true;
		if (isset($_GET['currency'])) {
			$old = $_GET['currency'];
			foreach ($data as $val) {
				$link = str_replace("&currency=$val[cur_id]", "", $link);
				$link = str_replace("?currency=$val[cur_id]", "?", $link);
			}
		}
	}

	foreach ($data as $val) {
		$link2 = $link;
		if ($parm) {
			$link2 .= "&currency=$val[cur_id]";
		} else {
			$link2 .= "?currency=$val[cur_id]";
		}
		$active = '';
		if ($currentCurrency == $val['cur_symbol']) {
			$active = 'active';
		}

		$temp .= "<li class='$active' role='presentation'><a role='menuitem' tabindex='-1' href='$link2'>$val[cur_symbol] $val[cur_name]</a></li>";
	}

	$temp .= '</ul>
	</div>';
	return $temp;
}

public function multiCurrencyCustom()
{
	$currentCurrency = $this->currentCurrencySymbol();
	$currentCurrencyH = $currentCurrency . " - " . $this->currentCurrencyCountry(true);
	$temp = "";

	$temp .= '<h6>' . $currentCurrencyH . '</h6>
	<!--<div class="more_currency"> -->';

	return $temp;

	$sql = "SELECT * FROM `currency` ORDER BY cur_id";
	$data = $this->dbF->getRows($sql);
	$link = $this->functions->defaultHttp . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$parm = false;
	if (!empty($_GET)) {
		$parm = true;
		if (isset($_GET['currency'])) {
			$old = $_GET['currency'];
			foreach ($data as $val) {
				$link = str_replace("&currency=$val[cur_id]", "", $link);
				$link = str_replace("?currency=$val[cur_id]", "?", $link);
			}
		}
	}

	foreach ($data as $val) {
		$link2 = $link;
		if ($parm) {
			$link2 .= "&currency=$val[cur_id]";
		} else {
			$link2 .= "?currency=$val[cur_id]";
		}
		$active = '';
		if ($currentCurrency == $val['cur_symbol']) {
			$active = 'active';
		}
		$countryKey = $val['cur_country'];
		$countryName = $this->functions->countryFullName($countryKey);

		$temp .= " <a href='$link2' class='$active'>$val[cur_symbol] - $countryName</a>";
	}

	$temp .= '
	</div>';
	return $temp;
}

public function currentCurrencyId()
{
	if (!isset($_SESSION['webUser']['currencyId'])) {
		$this->setDefaultCurrencySession();
	}
	$currId = $_SESSION['webUser']['currencyId'];
	return $currId;
}

public function currentCurrencyCountry($fullname = false)
{

	if (!isset($_SESSION['webUser']['currencyC'])) {
		$this->setDefaultCurrencySession();
	}
	$currC = $_SESSION['webUser']['currencyC'];
	if ($fullname == true) {
		$currC = $this->functions->countryFullName($currC);
	}
	return $currC;
}

public function currentCurrencySymbol()
{
	if (!isset($_SESSION['webUser']['currencySymbol'])) {
		$this->setDefaultCurrencySession();
	}
	$currSymbol = $_SESSION['webUser']['currencySymbol'];
	return $currSymbol;
}

private function setDefaultCurrencySession($data = false)
{
	if ($data == false) {
		$currCountry = $this->functions->ibms_setting('Default Web_Price_Country');



		$data = $this->productF->currencyInfo($currCountry);
	}
	$_SESSION['webUser']['currencyId'] = $data['cur_id'];
	$_SESSION['webUser']['currencySymbol'] = $data['cur_symbol'];
	$_SESSION['webUser']['currencyC'] = $data['cur_country'];
}

public function currentCountry()
{
/*if(isset($_SESSION['webUser']['selectCountry'])){
$country    =   $_SESSION['webUser']['selectCountry'];
}else{
$country   =   $this->functions->ibms_setting('Default Web_Price_Country');
$_SESSION['webUser']['selectCountry'] = $country;
} */

// country same as currency
$country = $this->currentCurrencyCountry();

return $country;
}

public function productSpecialImage($id, $alt)
{
	$sql = "SELECT * FROM `product_image` WHERE product_id = '$id' AND alt = '$alt' ORDER BY sort ASC ";
	$data = $this->dbF->getRow($sql);
	if ($this->dbF->rowCount > 0) {
		$imag = $data['image'];
	} else {
//get first Image
		$imag = $this->productFirstImage($id);
	}
	return $imag;
}

public function productFirstImage($pId)
{
	$data = $this->productAllImage($pId, '1', true);
	if (!empty($data)) {
		$imag = $data['image'];
		return $imag;
	}
	return "";
}

public function productAllImage($id, $limitP = false, $OnlyFirstImage = false)
{
	$limit = '';
	if ($limitP != false) {
		$limit = " LIMIT 0,$limitP";
	}
	if ($OnlyFirstImage == true) {
		$limit = " LIMIT 0,1";
	}
	$sql = "SELECT * FROM `product_image` WHERE product_id = '$id' ORDER BY sort ASC $limit";

	if ($OnlyFirstImage == true || $limitP == '1') {
		$data = $this->dbF->getRow($sql);
	} else {
		$data = $this->dbF->getRows($sql);
	}
	return $data;
}

public function productDateIsReadyForLaunch($pId)
{
	$today = date('m/d/Y');
	$sql = "SELECT * FROM `product_setting`
	WHERE
	`p_id` = '$pId' AND `setting_name` = 'launchDate'
	AND `setting_val` <= '$today'";
	$data = $this->dbF->getRow($sql);
	if ($this->dbF->rowCount > 0)
return true;//yes ready for launch
return false; // not ready for launch
}


public function get_product_view($cat_id=false){
	$this->functions->modelClasFile("category.php");
	$category_c = new p_category();
	return $category_c->get_view_option($cat_id);
}

public $productSerial = 0;

public function pBox($pId, $wishList = false, $view = true)
{

	// var_dump($view);
	global $_e;
	//   $sql    =   "SELECT * FROM proudct_detail WHERE prodet_id = '$pId' ";

	//Allow with no qty on web will add in setting
	if (!$this->productF->hasStock($pId)) {
		if ($this->functions->ibms_setting('no_inventory_product_show_onWeb') == 'no') {
			return false;
		}
	}

	$data = $this->productData($pId);
	// var_dump($data);
	if (!$this->dbF->rowCount) {
		return false;
	}

	$productSlug = $data['slug'];

	if (!$this->productDateIsReadyForLaunch($pId)) {
		return false;
	}

	$webLang = currentWebLanguage();
	$loaderGif = WEB_URL . '/images/loader.gif';
	$serialNo = $this->productSerial = $this->productSerial + 1;

	//var_dump($pColorData);

	//$productImage     =   $this->product->productLastImage($pId);
	$productImage = $this->productSpecialImage($pId, 'main');

	if ($productImage == "") {
		$productImage = "default.jpg";
	}
	$productBigImage = $productImage;
	$productImage = $this->functions->resizeImage($productImage, '300', '500', false);

	$productHoverImage = $this->productSpecialImage($pId, 'hover');
	if ($productHoverImage == "") {
		$productHoverImage = "default.jpg";
	}
	$productHoverImage = $this->functions->resizeImage($productHoverImage, '300', '500', false);

	$showAllImages = false; // make it false if you dont need all images, so this query will not execute => save time
	if ($showAllImages) {
		$images = $this->productAllImage($pId, 3);
		$smallImages = "";
		foreach ($images as $val) {
			$smlImage = $this->functions->resizeImage($val['image'], '35', '35', false);
			$smallImages .= "<img src='$smlImage' />";
		}
	}

	$pName = translateFromSerialize($data['prodet_name']);
	$shortDesc = translateFromSerialize($data['prodet_shortDesc']);
	$productPaymentMode = translateFromSerialize($data['payment_mode']);

	$pSetting = $this->productF->getProductSetting($pId);

	$pDesc      = translateFromSerialize($this->productF->productSettingArray('ldesc', $pSetting, $pId));


	$currencyId = $this->currentCurrencyId();
	$currencySymbol = $this->currentCurrencySymbol();
	$pPriceData = $this->productF->productPrice($pId, $currencyId);
	//$pPriceData Return , currency id,international shipping, price, id,
	$pPriceActual = $pPriceData['propri_price'];
	$pPrice = $pPriceActual;

	$discount = $this->productF->productDiscount($pId, $currencyId);
	@$discountFormat = $discount['discountFormat'];
	@$discountP = $discount['discount'];

	$discountValue = $discountP;
	if ($discountFormat == "percent") {
		$discountValue .= " %";
	} else {
		$discountValue = $discountValue . " $currencySymbol";
	}


	$discountPrice = $this->productF->discountPriceCalculation($pPriceActual, $discount);
	$newPrice = $pPriceActual - $discountPrice;

	$isSale = false;
	if (isset($discount['isSale']) && $discount['isSale'] == '1') {
		$isSale = true;
	}

	$isDiscount = false;
	if ($newPrice != $pPrice) {
		$isDiscount = true;
	}

	$pPrice = empty($pPrice) ? 0 : $pPrice;
	$newPrice = empty($newPrice) ? 0 : $newPrice;
	$pPrice .= ' ' . $currencySymbol;
	// $newPrice .= ' ' . $currencySymbol;
	$newPrice1 = $currencySymbol.' '.$newPrice;

	$hasDiscount = false;

	if ($newPrice1 != $pPrice && $pPriceActual != '') {
		$hasDiscount = true;
		$oldPriceNormal = $pPrice;
		$newPriceNormal = $newPrice1;
		$oldPriceDiv = '<h4>' . $pPriceActual . '</h4> ';
		$newPriceDiv =  $newPrice1 ;
	} else {
		$oldPriceNormal = "";
		$newPriceNormal = $pPrice;
		$oldPriceDiv = "";
		$newPriceDiv =  $pPrice ;
	}

	//var_dump($discount);
	/*Update Your Product Box Here... BY using Upper Variables*/
	$wishListRemove = "";
	if ($wishList) {
		$wishListRemove = "<div class='wishListRemove cursor' onclick='WishListRemove(this,$pId)'>X</div>";
	}


	//$link       =   WEB_URL."/detail.php?pId=$pId"; // normal and working link
	//$link       =   WEB_URL."/product-$pId-".urlencode($pName); // .htaccess handel this link
	$link = WEB_URL . "/" . $this->db->productDetail . "$productSlug"; // product slug
	$Qucicklink = WEB_URL . "/quickView.php?pId=$pId";

	$saleDiv = "";
	if ($isSale) {
	//Sale
		$saleDiv = "
		<div class='discount_tag'>
		<h4>$discountValue</h4>
		<h5>" . $_e['SALE'] . "</h5>
		</div>";
	} else if ($isDiscount) {
	//Discount
		$saleDiv = "
		<div class='discount_tag'>
		<h4>$discountValue</h4>
		<h5>" . $_e['DISCOUNT'] . "</h5>
		</div>";
	}

	$saleDivDis = "";
	if ($isSale) {
	//Sale
		$saleDivDis = "
		<div class='grid_discount_tag'>
		<h4>$discountValue</h4>
		<h5>" . $_e['SALE'] . "</h5>
		</div>";
	} else if ($isDiscount) {
	//Discount
		$saleDivDis = "
		<div class='grid_discount_tag'>
		<h4>$discountValue</h4>
		<h5>" . $_e['DISCOUNT'] . "</h5>
		</div>";
	}
	/**
	* For Product Quick View, Also need to call function productQuickViewModel(); in footer
	* <a class="" data-toggle="modal" data-target="#paroductQuickView" onclick="quickView(this,$pId);">
	*
	*
	* </a>
	*/

	//Which view Print
	//Default View
	//Use pBox Class in main product div
	$webUrl = WEB_URL;
	$wishlistT = _u($_e['Add to WishList']);
	$buyT = _u($_e['BUY']);
	$detailsT = _u($_e['Details']);
	$showDetailsT = _u($_e['Show Details']);
	$viewT = _u($_e['Lagg till i varukorgen']);



######### 3 For 2 Category ########
$three_for_2_category = $this->productF->check_product_in_3_for_2($pId);
if($three_for_2_category){
	$three_for_2_category = "<div class='three_for_2_icon'><img src='".WEB_URL."/images/3for2.jpg' /></div>";
}else{
	$three_for_2_category = "";
}

$temp = '';
if ($view === "List") {
	$viewT = $this->dbF->hardWords('view details',false);
	$shortDesc = strip_tags($shortDesc);
	$temp = <<<HTML

	<div class="p_box_white wow fadeInDown pBox list_view" data-id="$pId" data-wow-delay="0.2s">
	$wishListRemove

	<input type="hidden" class="hidden" value="$discountFormat" id="discountFormat_$pId">
	<input type="hidden" class="hidden" value="$discountP" id="discount_$pId">
	<input type="hidden" class="hidden" value="$newPrice" id="discountPrice_$pId">

	<div class="p_box p_box_list">
	$three_for_2_category
	<div class="p_box_right hidden-xs p_bright">
	<a href="$link">
	<img src="$productImage" alt="$pName" class="first_img">

	</a>

	</div>
	<div class="p_box_left p_bleft">

	<div class="container-fluid padding-0 text-center visible-xs">
	<a href="$link">
	<img src="$productImage" alt="$pName" class="">
	</a>
	</div>

	<div class="p_head_text">
	<div>
	<a href="$link" class="p_text_head">$pName</a>
	</div>

	<div class="p_text_desc">

	$shortDesc
	</div>

	<div class="white_box">
	<div class="view_btn">


	<a href="$link">$viewT</a>
	</div>
	</div>

	<div class="list_prize ">$newPriceNormal <span> $oldPriceNormal</span></div>


	</div>
	<!--p_text_desc end-->



	</div>
	<!--p_box_left end-->
	$saleDiv

	</div>

	</div>
HTML;
} else if ($view == "Grid") {

	//This temp has been modified by ShreStsaV

	// <div class="col-md-4">
	// 	<div class="system-item text-center">
	// 		<span class="duration">1 month</span>
	// 		<h3>AC System</h3>
	// 		<h4>AED <span>599</span> / Month</h4>
	// 		<a href="#" class="btn btn-secondary" data-toggle="modal" data-target=".bd-example-modal-lg">Select Plan</a>
	// 	</div>
	// </div>

	// '.$shortDesc.$newPriceNormal.$oldPriceNormal.'
	$temp = '
	<div class="col-md-4 media product_'.$pId.'">
		<div class="system-item text-center">
			<span class="duration">'.$shortDesc.'</span>
			<h3>'.$pName.'</h3>
			<h4>'.$currencySymbol.' <span>'.$newPrice.'</span> / '.$productPaymentMode.'</h4>
			<a href="javascript:;" class="btn btn-secondary" onclick="selectPlan('.$pId.')">Select Plan</a>
		</div>
	</div>
	';
}



else if ($view == "IndexView") {

	$temp = '<div class="inner_col wow zoomIn">
	<a href='.$link.'>
	<div class="inner_col_box">
	<img src='.$productImage.' alt='.$pName.'>
	<div class="inner_content">
	<h3>'.$pName.'</h3>
	</div>
	</div>
	</a>
	</div>';
}

else if ($view == "SixGrid") {
	$temp = "
	<div class='grid_box_white pBox pSixGrid' data-id='$pId'>
	$wishListRemove

	<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
	<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
	<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>

	<div class='grid_box_outer'>
	<div class='grid_box_inner'>
	$three_for_2_category

	<table class='gridbox_right'>
	<tr valign='middle'>
	<td>
	<a href='$link'>
	<img src='$productImage' alt='$pName' class='first_img'>

	</a></td>

	</tr>

	</table>



	$saleDivDis

	<div class='mobile_div'>
	<div class='product_name'>
	<a href='$link' class='pgrid_text_head'>$pName</a> <img src='".WEB_URL."/webImages/left-arrow.png' alt='' style='margin-top:-1px;'>
	</div>



	<div class='pgrid_prize_mob'>
	<h4>$newPriceDiv</h4>
	<h6>
	$oldPriceDiv
	</h6>

	</div>

	</div><!--mobile div end-->
	</div><!--grid_box_inner end-->


	<div class='pgrid_text'>
	<div>
	<a href='$link' class='pgrid_text_head'>$pName</a>
	</div>

	</div> <!--pgrid_text end-->
	<div class='pgrid_prize'>
	<h4>$newPriceDiv</h4>
	<h6>
	$oldPriceDiv
	</h6>

	</div>

	<div class='both_icons'>
	<div class='basket cursor' onclick='quickView(this,$pId);'></div>
	<a class='glyphicon glyphicon-briefcase' href='$link'></a>
	</div>
	</div><!--grid_box _outer outer-->




	</div>";

}

else if ($view == "IndexView") {
	$temp = "
	<div class='grid_box_white pBox' data-id='$pId'  data-sr='enter bottom and scale up 80% over 1.5s' >
	$wishListRemove

	<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
	<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
	<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>

	<div class='grid_box_outer'>
	<div class='grid_box_inner'>
	$three_for_2_category

	<table class='gridbox_right'>
	<tr valign='middle'>
	<td>
	<a href='$link'>
	<img src='$productImage' alt='$pName' class='first_img'>

	</a></td>

	</tr>

	</table>
	$saleDivDis


	<div class='mobile_div'>
	<div class='product_name'>
	<a href='$link' class='pgrid_text_head'>$pName</a> <img src='".WEB_URL."/webImages/left-arrow.png' alt='' style='margin-top:-1px;'>
	</div>



	<div class='pgrid_prize_mob'>
	<h4>$newPriceDiv</h4>
	<h6>
	$oldPriceDiv
	</h6>

	</div>

	</div><!--movie div end-->


	</div><!--grid_box_inner end-->


	<div class='pgrid_text'>
	<div>
	<a href='$link' class='pgrid_text_head'>$pName</a>
	</div>

	</div> <!--pgrid_text end-->
	<div class='pgrid_prize'>
	<h4>$newPriceDiv</h4>
	<h6>
	$oldPriceDiv
	</h6>

	</div>

	<div class='both_icons'>
	<div class='basket cursor' onclick='quickView(this,$pId);'></div>
	<a class='glyphicon glyphicon-briefcase' href='$link'></a>
	</div>
	</div><!--grid_box _outer outer-->




	</div>";

}

else if ($view == '1') {
	$saleDiv = '';
	if ($isSale) {
		$saleDiv = '<div class="sale_tag">' . $_e['SALE'] . '</div>';
	}
	$productImage = $this->functions->resizeImage($productBigImage, '175', '330', false);
	$temp = <<<HTML
	<div class="r_box">

	<div class="r_img">
	<a href="$link">
	<img src="$productImage" alt="$pName" class="shrink"></a>

	</div><!--r_img end-->
	<div class="r_text_head">$pName</div>

	<div class="r_prize">$newPriceNormal <span> $oldPriceNormal</span></div>

	</div><!--r_box end-->
HTML;
} else if ($view == '2') {

	$productImage = $this->functions->resizeImage($productBigImage, '48', '52', false);
	$temp = <<<HTML
	<div class="buy_box">
	<div class="buy_img"><a href="$link"><img src="$productImage" alt=""></a></div>
	<div class="buy_text">
	<a href="$link"><h5>$pName</h5></a>
	<div class="r_prize">$newPriceNormal <span> $oldPriceNormal</span></div>
	</div>
	<a href="$link" class="glyphicon glyphicon-briefcase my_cart_img transition_3"></a>

	</div><!--buy_box end-->
HTML;
}

/*$pColorData = $this->productF->colorSQL($pId);
foreach($pColorData as $val){
$temp .= '<div class="colors_in_divs grow" style="background-color:#'.$val['proclr_name'].';background-color:'.$val['proclr_name'].';"></div>';
}*/

return $temp;
}

public function productQuickViewModel()
{
//use this functions in end of footer file.
//<a class="" data-toggle="modal" data-target="#productQuickView" onclick="quickView(this,$pId);">
	global $_e;
	$temp = '
	<!-- Modal -->
	<div class="modal fade" id="productQuickView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">' . _uc($_e['Product Quick View']) . '</h4>
	</div>
	<div class="modal-body ">
	<div id="frameProductQuickView"></div>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-danger" data-dismiss="modal">' . _uc($_e['Close']) . '</button>
	</div>
	</div>
	</div>
	</div>';
	return $temp;

}

public function getSubCatIds($parent)
{
	return $this->productF->getSubCatIds($parent);
}

public function productByCategory($category, $QuickViewId = false, $hasId = true)
{

	$sql = "SELECT * FROM `tree_data` WHERE id = ? ";
	if ($hasId == false) {
		$sql = "SELECT * FROM `tree_data` WHERE nm = ?";
	}
	$catData = $this->dbF->getRow($sql, array($category));

	$currentPage = isset($_GET['page']) ? floatval($_GET['page']) : 0;

	if (!$this->dbF->rowCount) {
		return false;
	}

	$catId = $catData['id'];
$catId = $this->getSubCatIds($catId); //array

$LIKE = "";
foreach ($catId as $val) {
	$cId = $val;
	$LIKE .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";
}
$LIKE = trim($LIKE, "OR");


$limitFrom = 0;
$limitT0 = $this->productLimitShowOnWeb();


//product sort by filteration, e.g by price, by sale etc
$sortByArray = $this->sortByProductQuery();
$newField = $sortByArray[0];
$sortGroupBy = $sortByArray[1];
$best_seller_sql = $sortByArray[2];

// $sortGroupBy = '';

//Find Product That in this category
$sql = "SELECT `procat_prodet_id`,`prodet_id`
$newField
FROM `product_category`
JOIN
`proudct_detail` as detail
on `product_category`.`procat_prodet_id` = `detail`.`prodet_id`

WHERE $LIKE
GROUP BY `detail`.`prodet_id` $sortGroupBy";


# best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
$best_seller_products = array();
if ($best_seller_sql != '') {
	$best_seller_products = $this->newBestSellerSQL($LIKE);
}


$tempTId = $this->functions->setTempTableVal('Query', $sql);
//For navigation
/*$limitFrom      = $currentPage*$limitT0-$limitT0;
$limitT0        = $currentPage*$limitT0;
$this->dbF->getRows($sql);
$this->totalProductFrNav    = $this->dbF->rowCount;
$this->productLimit         = $limitT0;*/
$products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

$sql .= " LIMIT $limitFrom,$limitT0";
$productIds = $this->dbF->getRows($sql);

$mergerd_products = $best_seller_products + $productIds;
return $this->productPrint($mergerd_products, $QuickViewId, $products);
}

public function productByCategoryNew($category, $QuickViewId = false, $hasId = true)
{

	$sql = "SELECT * FROM `categories` WHERE id = ? ";
	if ($hasId == false) {
		$sql = "SELECT * FROM `categories` WHERE name = ?";
	}
	$catData = $this->dbF->getRow($sql, array($category));

	$currentPage = isset($_GET['page']) ? floatval($_GET['page']) : 0;

	if (!$this->dbF->rowCount) {
		return false;
	}

	$catId = $catData['id'];
	$catId = $this->getSubCatIds($catId); //array

	$LIKE = "";
	foreach ($catId as $val) {
		$cId = $val;
		$LIKE .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";
	}
	$LIKE = trim($LIKE, "OR");


	$limitFrom = 0;
	$limitT0 = $this->productLimitShowOnWeb();


	//product sort by filteration, e.g by price, by sale etc
	$sortByArray = $this->sortByProductQuery();
	$newField = $sortByArray[0];
	$sortGroupBy = $sortByArray[1];
	$best_seller_sql = $sortByArray[2];

	// $sortGroupBy = '';

	//Find Product That in this category
	$sql = "SELECT `procat_prodet_id`,`prodet_id`
	$newField
	FROM `product_category`
	JOIN
	`proudct_detail` as detail
	on `product_category`.`procat_prodet_id` = `detail`.`prodet_id`

	WHERE $LIKE
	GROUP BY `detail`.`prodet_id` $sortGroupBy";


	# best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
	$best_seller_products = array();
	if ($best_seller_sql != '') {
		$best_seller_products = $this->newBestSellerSQL($LIKE);
	}


	$tempTId = $this->functions->setTempTableVal('Query', $sql);
	//For navigation
	/*$limitFrom      = $currentPage*$limitT0-$limitT0;
	$limitT0        = $currentPage*$limitT0;
	$this->dbF->getRows($sql);
	$this->totalProductFrNav    = $this->dbF->rowCount;
	$this->productLimit         = $limitT0;*/
	$products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);

	$mergerd_products = $best_seller_products + $productIds;
	return $this->productPrint($mergerd_products, $QuickViewId, $products);
}

public function get_change_grid_view_link($type){
	$link = $this->functions->currentUrl(false,true); //viewType
	$link = preg_replace("/([&|?])viewType=Grid|([&|?])viewType=list/i","",$link);
	$link .= "viewType=$type";
	return $link;
}

private function productPrint($productIds, $QuickViewId = false, $products = "", $array = array())
{ 

	// var_dump($array);


	$viewType = isset($array['viewType']) ? $array['viewType'] : true;
	// var_dump($viewType);
	if($viewType === true){
		$cat_id = empty($_GET["catId"]) ? false : $_GET["catId"];
		$viewType = $this->get_product_view($cat_id);
	}
	if ($QuickViewId === false || $QuickViewId == '') {
		if (isset($_GET['pId'])) {
			$QuickViewId = $_GET['pId'];
		} else{
			$QuickViewId = '0';
		}
	}
	$QuickFind = false; // use in when user share product, and detail show under product
	foreach ($productIds as $p) {
		if ($QuickViewId == $p['prodet_id']) {
			$QuickFind = true;
		}
		$products .= $this->pBox($p['prodet_id'], false, $viewType);
	}

	/*When product show in quick view, and client share that url, so on request same product open again, need to do some work*/
	if ($QuickFind === false && $QuickViewId != '0') {
		$products .= $this->pBox($QuickViewId, false, $viewType);
	}
	return $products;
}

public function sortByProduct()
{
	global $_e;

	$array = array(
		"default"    => _uc($_e["Default"]),
		"lowPrice"   => _uc($_e["By Low Price"]),
		"highPrice"  => _uc($_e["By High Price"]),
	// "lowRate"    => _uc($_e["By Low Rate"]),
	// "highRate"   => _uc($_e["By High Rate"]),
	// "lowView"    => _uc($_e["By Low View"]),
	// "topView"    => _uc($_e["By Top View"]),
	// "lowSale"    => _uc($_e["By Low Sale"]),
		"topSale"    => _uc($_e["By Top Sale"]),
		"latest"     => _uc($_e["By Latest Added"]),
	// "bestseller" => _uc($_e["By Best Seller"]),
	);


	if (!empty($_GET['sortBy'])) {
		$sortBy = $_GET['sortBy'];
	} else {
		$sortBy = getUserSession("sortBy");
	}
	if (empty($sortBy)) {
		$sortBy = 'default';
	}
	$form[] = array(
		"array" => $array,
		"class" => "productSortBy selectpicker",
		"type" => "select",
		"data" => "onchange='productSortBy(this)'",
		"select" => $sortBy,


	);

	$temp = $this->functions->print_form($form, false, false);
	$link = $this->functions->activeLink(false);
	$link = $this->functions->getLinkExpectOneParameter("sortBy", $link);

	if (stripos($link, "?") || !empty($_GET)) {
//if product page and first parameter not sortBy
		if ($this->webClass->isProductPage() && !stripos($link, "?sortBy="))
			$link .= "?sortBy=";
		else
			$link .= "?sortBy=";
	} else {
		$link .= "?sortBy=";
	}

	$temp .= "<script>
	function productSortBy(ths){
		sortBy = $(ths).val();
		link   = '$link';
		location.replace(''+link+sortBy+'');
	}
	</script>";

	return $temp;
}

private function sortByProductQuery()
{

	@$sortBy = $_GET['sortBy'];

	if (!empty($sortBy)) {
		setUserSession("sortBy", $sortBy);
	} else {
		$sortBy = getUserSession("sortBy");
	}

	//First time
	if ( empty($sortBy) ) {
		$sortBy = "default";
		setUserSession("sortBy", $sortBy);
	}

	$sql = $sql2 = $sql3 = '';

	$currencyId = $this->currentCurrencyId();

	switch ($sortBy) {
		case "lowPrice":
		$sql = " , (SELECT propri_price FROM product_price as sr
		WHERE sr.propri_prodet_id = detail.prodet_id AND sr.propri_cur_id = '$currencyId') as price";
		$sql2 = " ORDER BY price ASC";
		break;
		case "highPrice":
		$sql = " , (SELECT propri_price FROM product_price as sr
		WHERE sr.propri_prodet_id = detail.prodet_id AND sr.propri_cur_id = '$currencyId') as price";
		$sql2 = " ORDER BY price DESC";
		break;
		case "lowRate":
	/*$sql     = " , (SELECT AVG(rate)+SUM(rate)/(
	    SELECT COUNT(id) FROM rating as r2
	        WHERE r2.type='rate'
	) FROM rating as ra
	WHERE ra.p_id = detail.prodet_id AND ra.type='rate') as rate";*/
	$sql = " , (SELECT AVG(rate)+SUM(rate)/(
	SELECT COUNT(id) FROM rating as r2
	) FROM rating as ra
	WHERE ra.p_id = detail.prodet_id) as rate";
	$sql2 = " ORDER BY rate ASC";
	break;
	case "highRate":
	$sql = " , (SELECT AVG(rate)+SUM(rate)/(
	SELECT COUNT(id) FROM rating as r2
	) FROM rating as ra
	WHERE ra.p_id = detail.prodet_id) as rate";
	$sql2 = " ORDER BY rate DESC";
	break;
	case "topView":
	$sql2 = " ORDER BY view DESC";
	break;
	case "lowView":
	$sql2 = " ORDER BY view ASC";
	break;
	case "topSale":
	$sql2 = " ORDER BY sale DESC";
	break;
	case "lowSale":
	$sql2 = " ORDER BY sale ASC";
	break;
	case "latest":
	$sql2 = " ORDER BY `prodet_addOn` DESC ";
	break;
	case "bestseller":
	$sql3 = $this->bestSellerSQL(); // Do not comment, used in AllProducts()
	// $sql2 = " ORDER BY bsp.sort ASC ";
	$sql2 = " ORDER BY sort ASC";
	break;
	case "default":
	$sql2 = " ORDER BY sort ASC";
	break;
	}

	return array($sql, $sql2, $sql3);
}

public function productLimitByUserForm()
{
	global $_e;
	$maxlimit = 150;

	if (!empty($_POST['showPLimit'])) {
		$limitT0 = $_POST['showPLimit'];
	} else {
		$limitT0 = getUserSession("showPLimit");
	}
	if (empty($limitT0)) {
		$limitT0 = intval($this->functions->ibms_setting('productLimit'));
	}

	$limitT0 = abs($limitT0);
	if ($limitT0 > $maxlimit) {
		$limitT0 = $maxlimit;
	}

	setUserSession("showPLimit", $limitT0);
	$form[] = array(
		"class" => "form-control showPLimit padding-F5 selectpicker",
		"type" => "number",
		"min" => "1",
		"required" => "true",
		"max" => "$maxlimit",
		"name" => "showPLimit",
		'value' => $limitT0,
		'format' => "<div class='padding-0 container-fluid'>
		<label class='col-xs-2 padding-F5 control-label' style='width:auto'>" . $_e["Show"] . "</label>
		<div class='padding-0 input-group'>
		{{form}}
		<div class='input-group-addon padding-0'><input class='btn btn-default btn-sm my_btn' type='submit' value='" . $_e["Submit"] . "'></div>
		</div>
		</div>
		"
	);

	$link = $this->functions->activeLink(false);
	$form["form"] = array(
		"class" => "form-horizontal",
		"type" => "form",
		'method' => "post",
	);

	$temp = $this->functions->print_form($form, false, false);
	return $temp;
}

public function productLimitShowOnWeb()
{
	if (!empty($_POST['showPLimit'])) {
		$limitT0 = $_POST['showPLimit'];
	} else {
		$limitT0 = getUserSession("showPLimit");
	}
	if (empty($limitT0)) {
		$limitT0 = intval($this->functions->ibms_setting('productLimit'));
	}
	$limitT0 = abs($limitT0);
	setUserSession("showPLimit", $limitT0);
	return $limitT0;
}

public function AllProducts($QuickViewId = false)
{
	$currentPage = isset($_GET['page']) ? floatval($_GET['page']) : 0;
	$limitT0 = $this->productLimitShowOnWeb();
	$limitFrom = 0;

//product sort by filteration, e.g by price, by sale etc
	$sortByArray     = $this->sortByProductQuery();
	$newField        = $sortByArray[0];
	$sortGroupBy     = $sortByArray[1];
	$best_seller_sql = $sortByArray[2];

	$sql = "SELECT `detail`.*, `setting`.`setting_val`
	$newField
	FROM
	`proudct_detail` as detail join `product_setting` as setting
	on `detail`.`prodet_id` = `setting`.`p_id`
	WHERE
	`setting`.`setting_name`='publicAccess'
	AND `setting`.`setting_val`='1'
	AND `detail`.`product_update`='1' $sortGroupBy";


# best seller rows are shown first, then the rows from the previous sql are joined, both arrays are joined with the best seller array at first. update 2016-12-21
	$best_seller_products = array();
	if ($best_seller_sql != '') {
		$best_seller_products = $this->newBestSellerSQL();
	}

	$tempTId = $this->functions->setTempTableVal('Query', $sql);

	$sql        .= " LIMIT $limitFrom,$limitT0 ";
	$products   = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
	$productIds = $this->dbF->getRows($sql);
// $mergerd_products = array_merge($best_seller_products, $productIds); ## array merge, reindexes the keys, sorts them again, if same keys then the last one will replace the previous key
// var_dump($best_seller_products);
	$mergerd_products = $best_seller_products + $productIds;
	return $this->productPrint($mergerd_products, $QuickViewId, $products);
}

public function AllProductDeals()
{
	$limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));
	$limitFrom = 0;
	$sql = "SELECT id,name,price,image,slug FROM product_deal WHERE publish = '1' ORDER BY sort ASC ";
	$tempTId = $this->functions->setTempTableVal('Query', $sql);

	$sql .= " LIMIT $limitFrom,$limitT0";
	$deals = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
	$productDeals = $this->dbF->getRows($sql);
	foreach ($productDeals as $val) {
		$deals .= $this->pBoxDeal($val);
	}
	if (empty($productDeals)) $deals = false;
	return $deals;
}

public function productDealsByCategory($category, $QuickViewId = false, $hasId = true)
{
	$sql = "SELECT * FROM `tree_data` WHERE id = ? ";
	if ($hasId == false) {
		$sql = "SELECT * FROM `tree_data` WHERE nm = ?";
	}
	$catData = $this->dbF->getRow($sql, array($category));
	if (!$this->dbF->rowCount) {
		return false;
	}

	$catId = $catData['id'];
$catId = $this->getSubCatIds($catId); //array

$LIKE = "";
foreach ($catId as $val) {
	$cId = $val;
	$LIKE .= " `category` LIKE '%$cId%' OR";
}
$LIKE = trim($LIKE, "OR");

$limitFrom = 0;
$limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));

//Find Product That in this category
$sql = "SELECT id,name,price,image,slug FROM product_deal WHERE publish = '1' AND $LIKE  ORDER BY sort ASC ";
$tempTId = $this->functions->setTempTableVal('Query', $sql);
$deals = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';

$sql .= " LIMIT $limitFrom,$limitT0";
$productDeals = $this->dbF->getRows($sql);
foreach ($productDeals as $val) {
	$deals .= $this->pBoxDeal($val);
}
if (empty($productDeals)) $deals = false;
return $deals;
}

public function AllDealsPackage($id)
{
	$sql  = "SELECT * FROM product_deal WHERE publish = '1' AND id = '$id' ";
	$data = $this->dbF->getRow($sql);

	$package = unserialize($data['package']);
	$prodcts = '';
	if (empty($package)) {
		$package = array();
	}
	foreach ($package as $p) {
		$pId = $p;
		$prodcts .= $this->dealProductChoice($pId, $data);
	}

	return $prodcts;
}

public function checkOutProductPrice($pId, $currencyId)
{
	$sql = "SELECT proadc_price FROM product_addcost WHERE
	proadc_prodet_id = '$pId'
	AND proadc_name = 'checkout_price'
	AND proadc_cur_id = '$currencyId'
	AND proadc_price > 0";
	$data = $this->dbF->getRow($sql);
	return $data['proadc_price'];
}

public function checkOutProductIncludePrice($pId, $currencyId)
{
	$sql = "SELECT * FROM product_addcost 
	WHERE proadc_prodet_id = '$pId' 
	AND proadc_name = 'when_cart_price'
	AND proadc_cur_id = '$currencyId'
	AND proadc_price > 0
	";
	$data = $this->dbF->getRow($sql);
	return $data['proadc_price'];
}


/**
* Work as deal offer products and check out offers products.
*
* @param $pId
* @param $dealData
* @param bool|false $checkOutOffer
* @return string
*/
private function dealProductChoice($pId, $dealData, $checkOutOffer = false)
{
//if $checkOutOffer is true, $dealData will not be use
//functions use to print product data, in deal detail view, or checkout offer at cart page.
	global $_e;
	$echo = "";
	$data = $this->productData($pId);
	if (empty($data)) {
		return false;
	}

$inventoryLimit = $this->functions->developer_setting('product_check_stock'); // mean is unlimit inventory
$inventoryLimit = ($inventoryLimit == '1' ? true : false);

$productImage = $this->productSpecialImage($pId, 'main');

if ($productImage == "") {
	$productImage = "default.jpg";
}
$productThumb = $this->webClass->resizeImage($productImage, 250, 300, false);
//$productImage = WEB_URL . '/images/' . $productImage;

$pName = translateFromSerialize($data['prodet_name']);
$currencyId = $this->currentCurrencyId();
$currencySymbol = $this->currentCurrencySymbol();
$storeId = $this->getStoreId();

$oldPriceDiv = $newPriceDiv = $oldPriceNormal = $newPriceNormal = '';
if ($checkOutOffer == false) {
	$price = unserialize($dealData['price']);
	$priceDefault = $price[$currencyId];
	$addToCartButton = '';
	$js_data_attrs   = '';

} else {

	$addToCartButton = "<div class='cart_btn text-center margin-5D  cursor AddToCart_$pId' onclick='addToCart(this,$pId,true);'>
	{$_e['Add To Cart']}
	</div>";
	$pPriceData = $this->productF->productPrice($pId, $currencyId);
//$pPriceData Return , currency id,international shipping, price, id,
	$pPrice = $priceDefault = $pPriceData['propri_price'];

	$newPrice = $checkOutPrice = $checkOutOffer['proadc_price'];

//$priceDefault         .= ' '.$currencySymbol;
//$checkOutPrice        .= ' '.$currencySymbol;

	$discountP = $priceDefault - $checkOutPrice;
	$discountFormat = 'price';
	if ($priceDefault != $checkOutPrice) {
		$oldPriceNormal = $priceDefault . " " . $currencySymbol;
		$newPriceNormal = $checkOutPrice . " " . $currencySymbol;
		$oldPriceDiv = ' <span class="oldPrice tabprice"><span class="productOldPrice_' . $pId . '">' . $pPrice . '</span> ' . $currencySymbol . ' </span>';
		$newPriceDiv = '<span class="NewDiscountPrice productPrice_' . $pId . '">' . $checkOutPrice . '</span> ' . $currencySymbol . '';
//$newPriceDiv = '<span class="NewDiscountPrice1"><span class="productPrice_' . $pId . '">' . $newPrice . '</span> ' . $currencySymbol . ' </span>';
	} else {
		$oldPriceNormal = "";
		$newPriceNormal = $priceDefault . " " . $currencySymbol;
		$oldPriceDiv = "";
		$newPriceDiv = '<h2>' . $priceDefault . '</h2>';
	}



	$js_data_attrs   = " data-discountP             = '$discountP'
	data-discountFormat        = '$discountFormat'
	data-discountDefaultPrice  = '$newPrice'
	data-defaultPrice          = '$priceDefault' ";



}

$hasScaleVal = $this->functions->developer_setting('product_Scale');
$hasColorVal = $this->functions->developer_setting('product_color');

$hasWebOrder_with_Scale = $this->functions->developer_setting('webOrder_with_Scale');
$hasWebOrder_with_color = $this->functions->developer_setting('webOrder_with_color');

$hasScale = ($hasScaleVal == '1' ? true : false);
$hasColor = ($hasColorVal == '1' ? true : false);

/*
*
Info Of is size & color insert in product either it is out of stock
Or either it is out of store,
*/

if ($inventoryLimit) {
	$getInfo = $this->inventoryReport($pId);
} else {
	$getInfo = $this->productSclaeColorReport($pId);
}
$getInfoReport = $getInfo['report'];

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
	$colorDiv = $this->getColorsDiv($pId, $storeId, '0', $currencyId, $currencySymbol, '0', $hasScale);
// $colorDiv = "<div class='container_detail_RightR_color_heading'>
//             <p>" . _uc($_e['Color']) . "</p>" . $colorDiv . "</div>";

	$colorDiv = <<<COLORDIV

	<div id='size_color' class='container_detail_RightR_color choice_color'>
	<dl id='sample' class='dropdown_select detail_top_r_size'>
	<dt><a><span>{$_e['Select Color']}</span></a></dt>
	<dd>
	<ul> {$colorDiv} </ul>
	</dd>
	</dl> 
	</div>

COLORDIV;

}

//Make Scale Divs,, print where you want
$isCustomSize = false;
$customSizeForm = '';

$scaleDiv = "";
if ($hasScale) {
	$scaleDiv = $this->getScalesDiv($pId, $storeId, $currencyId, $currencySymbol, $hasColor);
}
//Custom Size
$isCustomSize = false;
if ($this->functions->developer_setting('product_customSize') == '1' && true == false) { //stop cutome size in checkout offer
	$sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId'";
	$customData = $this->dbF->getRows($sql);

	if (!empty($customData)) {
		@$customSize = $customData[0]['type_id'];
		if ($customSize != "0" && !empty($customSize)) {
			$isCustomSize = true;

			$customSizeForm = $this->customSizeForm($pId, $customSize);
			if ($customSizeForm == false) {
				$isCustomSize = true;
				$customSize = 0;
			} else {
				$customSize_price = $this->customSizeArrayFilter($customData, $currencyId);
				$customSize_price = empty($customSize_price) ? 0 : floatval($customSize_price);
				$onclick = 'onclick="productPriceUpdate(' . $pId . ');" data-toggle="modal" data-target="#customF_' . $pId . '"';
				$scaleDiv = $scaleDiv . $this->getScaleDivFormat($pId, 'Custom', -1, $customSize_price, -1, '', $onclick);
			}

}//if customSize==0 end
} //if customData end

} // if developer setting end
//Custom Size End

if (!empty($scaleDiv)) {
// $scaleDiv = "<div class='container_detail_RightR_color_heading'><p>" . _uc($_e['Size']) . "</p>" . $scaleDiv . "</div>";
	if ($isCustomSize) {
		$echo .= $this->functions->blankModal("Custom Size", "customF_$pId", $customSizeForm, "Close");
	}

	$scaleDiv = <<<SIZEDIV

	<div id='size_color' class='container_detail_RightR_color choice_color'>
	<dl id='sample' class='dropdown detail_top_r_size'>
	<dt><a><span> {$_e['Select Scale']} </span></a></dt>
	<dd>
	<ul> {$scaleDiv}    </ul>
	</dd>
	</dl> 
	</div>            
SIZEDIV;

}


$isSingleProduct = false;
if (!$hasScale && !$hasColor) {
	$isSingleProduct = true;
}

$link = WEB_URL . "/detail?pId=$pId";

$inventoryLimit = ($inventoryLimit == true ? "1" : "0");
//print jsInfo after body start, or in product div
$jsInfo = "
<!-- javascript Info use in js-->
<input type='hidden' class='dealsProducts' value='$pId'/>
<input type='hidden' id='currency_$pId' value= '$currencySymbol'
{$js_data_attrs} />
<input type='hidden' id='store_$pId' value='$storeId'/>
<input type='hidden' id='checkout_$pId' value='$pId'/>
<input type='hidden' id='hasColor_$pId' value='$hasColorVal'/>
<input type='hidden' id='hasScale_$pId' value='$hasScaleVal'/>

<input type='hidden' id='order_with_Color_$pId' value='$hasWebOrder_with_color'/>
<input type='hidden' id='order_with_Scale_$pId' value='$hasWebOrder_with_Scale'/>
<input type='hidden' id='deatilStockCheck_$pId' value='$inventoryLimit' >
$getInfoReport
<!-- javascript Info use in js End-->";


$stockStatus = $this->productF->hasStock($pId);
if ($stockStatus) {
	$stockStatus = _uc($_e['In Stock']);
} else {
	$stockStatus = _uc($_e['Out Stock']);
}


if ($checkOutOffer) {
	$productThumb = $this->webClass->resizeImage($productImage, 150, 210, false);
//checkout offer view
// $echo .= "<div class='container-fluid padding-0 checkOutOffer' id='p$pId'>
//                   $jsInfo
//              <div class='col-sm-4 padding-0 text-center checkout_img'>
//                 <a href='$link' target='_blank'><img src='$productThumb' class='glow grow thumbnail img-responsive display-inlineBlock'/></a>
//              </div>
//              <div class='col-sm-8 padding-F5 text-center-xs'>
//                 <h4 class='tmH4 h4 pName_$pId'><a href='$link' target='_blank'>$pName</a></h4>
//                  $oldPriceDiv
//                   $newPriceDiv

//                 <div id='size_color' class='container_detail_RightR_color choice_color'>
//                   $colorDiv
//                 </div>
//                 <div class='detail_top_r_size' id='size_radio'>
//                   $scaleDiv
//                 </div>
//                     $addToCartButton
//                 <div class='padding-0' style='height: 22px;  margin-top: 5px;'>
//                     <small id='stock_$pId'></small>
//                 </div>
//             </div>
//         </div>";




	/*Page cart*/
	$echo .= "<div class='container-fluid padding-0 checkOutOffer' id='p$pId'>
	$jsInfo
	<div class='col-sm-4 padding-0 text-center checkout_img'>
	<a href='$link' target='_blank'><img src='$productThumb' class='glow grow thumbnail img-responsive display-inlineBlock'/></a>
	</div>
	<div class='col-sm-8 padding-F5 text-center-xs'>
	<h4 class='tmH4 h4 pName_$pId'><a href='$link' target='_blank'>$pName</a></h4>
	$oldPriceDiv
	$newPriceDiv


	{$colorDiv}


	{$scaleDiv}

	" . 



	$addToCartButton .  "<div class='padding-0' style='height: 22px;  margin-top: 5px;'>
	<small id='stock_$pId'></small>
	</div>
	</div>
	</div>";

} else {
//deal offer detail view
//working on product deal package view, color or size
	$echo .= "<div class='container-fluid padding-0 pDealBox' id='p$pId'>
	$jsInfo
	<div class='col-sm-4 padding-0 text-center'>
	<a href='$link' target='_blank'><img src='$productThumb' class='glow grow thumbnail img-responsive display-inlineBlock'/></a>
	</div>
	<div class='col-sm-8 padding-0 text-center-xs'>
	<h4 class='tmH4 h4 pName_$pId'><a href='$link' target='_blank'>$pName</a></h4>
	$newPriceDiv
	$oldPriceDiv
	<div id='size_color' class='container_detail_RightR_color choice_color'>
	$colorDiv
	</div>
	<div class='detail_top_r_size' id='size_radio'>
	$scaleDiv
	</div>
	<div class='padding-0' style='height: 34px;  margin-top: 10px;'>
	<small id='stock_$pId'></small>
	</div>
	</div>
	</div>";
}

if ($isSingleProduct) {
	$echo .= "<script>
	$(document).ready(function () {
		productStockCheck($pId, 0, 0);
		});
		</script>";
	}

	return $echo;
}

public function pBoxDeal($data)
{
	global $_e;
	$id = $data['id'];
	$slug = $data['slug'];
	$name = translateFromSerialize($data['name']);
	$imageR = $data['image'];
	$price = unserialize($data['price']);
//$link   =   WEB_URL."/productDeals?deal=$id";
$link    = WEB_URL . "/" . $this->db->dealProduct . "$slug"; // slug link
$web_url = WEB_URL;

$currencyId = $this->currentCurrencyId();
$price = isset($price[$currencyId]) ? $price[$currencyId] : "0";
$currencySymbol = $this->currentCurrencySymbol();

if ($price == '0' || empty($price)) {
//if price 0 it will not show OR it wil not show in that country
	return "";
} else {
	$priceDiv = "<span class='dealPrice'>$price $currencySymbol</span>";
}

$viewT = _u($_e['View']);
$saleT = _u($_e['Sale']);
$image = $this->functions->resizeImage($imageR, '205', '332', false);

# get deal setting
$deal_setting_data = $this->get_deal_setting($id);
$shortDesc   = $this->functions->findArrayFromSettingTable($deal_setting_data,'sDesc');
$shortDesc   = translateFromSerialize($shortDesc);

// $format = "<div class='c3_box3 pBoxDeal' data-id='$id'>
//                 <input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>
//                 <a href='$link'><div class='c3_image3 shadow-radial'><img src='$image' alt='$name'></div></a>
//                 <div class='c3_matter'>$name</div>
//                 <div class='price'>$priceDiv</div>
//             </div>";

//         $format = <<<HTML
//                 <div class='p_box_white pBoxDeal' data-id='$id' data-wow-delay="0.2s">
//                 <input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>
//         <div class="p_box">

//             <div class="p_box_right_res hidden-xs">
//                 <a href="$link">
//                     <img src="$image" alt="$name" class="">
//                 </a>
//             </div>

//             <div class="p_box_left">

//                 <div class="container-fluid padding-0 text-center visible-xs">
//                       <a href='$link'><div class='c3_image3 shadow-radial'><img src='$image' alt='$name'></div></a>
//                 </div>

//                 <div class="p_head_text">
//                     <div>
//                         <a href="$link" class="p_text_head">$name</a>
//                     </div>
//                     <div class="p_text_desc">
//                         $shortDesc
//                     </div>
//                 </div>
//                 <!--p_text_desc end-->
//                 <div class="p_prize">
//                     <h4>$priceDiv</h4>
//                 </div>
//                 <div class="white_box">
//                     <div class="view_btn deal_view_btn">
//                        <!--  <div class="basket"></div>
//                         <div class="line"></div> -->
//                         <a href="$link">$viewT</a>
//                     </div>
//                 </div>

//             </div>
//             <!--p_box_left end-->
//             <div class="p_box_right hidden-xs">
//                 <a href="$link">
//                     <img src="$image" alt="$name" class="">
//                 </a>
//             </div>
//         </div>

//     </div>
// HTML;

//         $format = <<<HTML

//             <div class="product1" data-id='$id' data-wow-delay="0.2s" >

//                 <input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>

//                 <div class="product_main">


//                     <div class="product2_inner visible_responsive">

//                         <img src="{$image}" alt="{$name}" class="">

//                         <div class="sales_btn">
//                             <img src="{$web_url}/images/sales.png" alt="">
//                         </div>

//                     </div><!--product2_inner-->


//                     <div class="product1_inner">

//                         <h3>{$name}</h3>

//                         <div class="text_product">
//                             {$shortDesc}
//                         </div><!--text_product close-->

//                         <div class="price_side">
//                             {$priceDiv}
//                         </div><!--price_side-->

//                         <div class="btn_side_product hvr-sweep-to-bottom">
//                             <a href="{$link}">
//                                 <span><img src="{$web_url}/images/cart2.png" alt=""></span>
//                                 {$viewT}
//                             </a>
//                         </div><!--btn_side_product-->

//                     </div><!--product1_inner-->

//                     <div class="product2_inner">

//                         <img src="{$image}" alt="{$name}" class="">

//                         <div class="sales_btn">
//                             <img src="{$web_url}/images/sales.png" alt="">
//                         </div>

//                     </div><!--product2_inner-->

//                 </div>

//             </div><!--product1-->


// HTML;





$format = <<<HTML

<div class="product1"  data-id='$id' data-wow-delay="0.2s">

<input type='hidden' class='hidden' value='$price $currencySymbol' id='dealPrice_$id'>

<div class="product_main">


<div class="product2_inner visible_responsive">

<img src='{$image}' alt='{$name}'>

<div class="sales_btn">
<img src="{$web_url}/images/sales.png" alt="">
<div class="tag">{$saleT}</div><!--tag close-->
</div>

</div><!--product2_inner-->


<div class="product1_inner">

<h3>{$name}</h3>

<div class="text_product">
{$shortDesc}
</div><!--text_product close-->

<div class="price_side">
<span class="dealPrice">{$priceDiv}</span>
</div><!--price_side-->

<div class="btn_side_product hvr-sweep-to-bottom">
<a href="{$link}">
<span><img src="{$web_url}/images/cart2.png" alt=""></span>
{$viewT}
</a>
</div><!--btn_side_product-->

</div><!--product1_inner-->

<div class="product2_inner">

<img src="{$image}" alt="{$name}" class="">

<div class="sales_btn">
<img src="{$web_url}/images/sales.png" alt="">
<div class="tag">
{$saleT}
</div><!--tag close-->
</div>

</div><!--product2_inner-->

</div>

</div>


HTML;



return $format;
}

public function get_deal_setting($deal_id)
{

	$sql         = " SELECT * FROM product_deal_setting where deal_id = ? ";
	$dataSetting = $this->dbF->getRows($sql,array($deal_id));
	if ($this->dbF->rowCount > 0) {
		$result = $dataSetting;
	} else {
		$result = array();
	}

	return $result;

}

public function featuredProducts($limitT0 = false, $QuickViewId = false)
{
	$limitFrom = 0;
	if ($limitT0 == false) {
		$limitT0 = abs(intval($this->functions->ibms_setting('featuredProductLimit')));
		if ($limitT0 <= 0) {
			$limitT0 = 20;
		}
	}

	$sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
	FROM
	`proudct_detail` join `product_setting`
	on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
	WHERE
	`product_setting`.`setting_name`='publicAccess'
	AND `product_setting`.`setting_val`='1'
	AND `proudct_detail`.feature = '1'
	AND `proudct_detail`.`product_update`='1' ORDER BY sort ASC";

	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);
//  var_dump($productIds);

	return $this->productPrint($productIds, $QuickViewId);
}

public function featuredProducts2($limitT0 = false, $QuickViewId = false)
{
//Use for 2nd type of view product..
	$limitFrom = 0;
	if ($limitT0 == false) {
		$limitT0 = abs(intval($this->functions->ibms_setting('featureProduct2Limit')));
		if ($limitT0 <= 0) {
			$limitT0 = 15;
		}
	}

	$sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
	FROM
	`proudct_detail` join `product_setting`
	on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
	WHERE
	`product_setting`.`setting_name`='publicAccess'
	AND `product_setting`.`setting_val`='1'
	AND `proudct_detail`.feature = '2'
	AND `proudct_detail`.`product_update`='1' ORDER BY sort ASC";

	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);
//  var_dump($productIds);
	if ($QuickViewId === false || $QuickViewId == '') {
		if (isset($_GET['pId'])) {
			$QuickViewId = $_GET['pId'];
		} else {
			$QuickViewId = '0';
		}
	}
$QuickFind = false; // use in when user share product, and detail show under product
$products = "";
foreach ($productIds as $p) {
	if ($QuickViewId == $p['prodet_id']) {
		$QuickFind = true;
	}
	$products .= $this->pBox($p['prodet_id'], false, 2);
}

if ($QuickFind === false && $QuickViewId != '0') {
	$products .= $this->pBox($QuickViewId, false, 2);
}

return $products;
}

public function latestProducts($limitT0 = false, $QuickViewId = false,$viewType = false)
{
	$limitFrom = 0;
	if ($limitT0 == false) {
		$limitT0 = abs(intval($this->functions->ibms_setting('latestProductLimit')));
		if ($limitT0 <= 0) {
			$limitT0 = 20;
		}
	}

	$sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
	FROM
	`proudct_detail` join `product_setting`
	on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
	WHERE
	`product_setting`.`setting_name`='publicAccess'
	AND `product_setting`.`setting_val`='1'
	AND `proudct_detail`.`product_update`='1' ORDER BY prodet_id DESC";

	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);
// var_dump($viewType);


	$array['viewType'] = 'IndexView';


	return $this->productPrint($productIds, $QuickViewId,'', $array);
}

public function topSaleProducts($limitT0 = false, $QuickViewId = false,$viewType = true)
{
	$limitFrom = 0;
	if ($limitT0 == false) {
		$limitT0 = abs(intval($this->functions->ibms_setting('topSaleProductLimit')));
		if ($limitT0 <= 0) {
			$limitT0 = 20;
		}
	}

	$sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
	FROM
	`proudct_detail` join `product_setting`
	on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
	WHERE
	`product_setting`.`setting_name`='publicAccess'
	AND `product_setting`.`setting_val`='1'
	AND `proudct_detail`.`product_update`='1' ORDER BY sale DESC";
	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);
//var_dump($productIds);
	$array['viewType'] = $viewType;
	return $this->productPrint($productIds, $QuickViewId, '', $array);
}

public function dontForgetToBuyProduct($pId, $relatedIds = false, $viewType = true, $limitT0 = 5)
{
	$limitFrom = 0;
	$limitT02 = $limitT0 + 10;

	if (empty($relatedIds)) {
		$sql = "SELECT * FROM (
		SELECT `proudct_detail`.*, `product_setting`.`setting_val`
		FROM
		`proudct_detail` join `product_setting`
		on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
		WHERE
		`product_setting`.`setting_name`='publicAccess'
		AND `product_setting`.`setting_val`='1'
		AND `proudct_detail`.`product_update`='1'

		ORDER BY sale DESC  LIMIT $limitFrom,$limitT02

		) AS temptable
		ORDER BY RAND()
		LIMIT $limitT0";
		$productIds = $this->dbF->getRows($sql);
	} else {
		@$relatedIdsT = array_rand($relatedIds, $limitT0);
		$relatedIdsT = empty($relatedIdsT) ? array() : $relatedIdsT;
		foreach ($relatedIdsT as $id) {
			$productIds[]['prodet_id'] = $relatedIds[$id];
		}
	}
//var_dump($productIds);
	if (empty($productIds)) {
		$productIds[]['prodet_id'] = false;
	}

	$array['viewType'] = $viewType;
	return $this->productPrint($productIds, '0', '', $array);
}

public function relatedProduct($pId, $limit, $viewType = true)
{
//Get Pid Category
//return all related categorys product...
	$sql = "SELECT * FROM product_category WHERE procat_prodet_id = '$pId'";
	$categoryData = $this->dbF->getRow($sql);

// var_dump($sql)


	if (!$this->dbF->rowCount) {
		return false;
	}
	$cats = explode(",", $categoryData['procat_cat_id']);

	$LIKE = "";
	foreach ($cats as $catIds) {
//find related Category products
		$LIKE .= " `product_category`.`procat_cat_id` LIKE '%$catIds%' OR";
	}
	$LIKE = trim($LIKE, "OR");

	$limitFrom = 0;
	$limitT0 = $limit;

//Find Product That in this category
	$sql = "SELECT `prodet_id` FROM `product_category`
	JOIN
	`proudct_detail`
	on `product_category`.`procat_prodet_id` = `proudct_detail`.`prodet_id`
	WHERE $LIKE
	GROUP BY `proudct_detail`.`prodet_id` ORDER BY RAND()";
	$products = '';

	$sql .= " LIMIT $limitFrom,$limitT0";


// var_dump($sql);


	$productIds = $this->dbF->getRows($sql);

//remove current id from list
	$productIds = array_delete($productIds, $pId);

	if (!$this->dbF->rowCount || empty($productIds)) {
		return false;
	}

	$array['viewType'] = $viewType;
	return $this->productPrint($productIds, '0', $products, $array);
}

public function youMayAlsoLikeProduct($pId, $limit, $viewType = true)
{
//Get Pid Category
//return all related categorys product...
	$sql = "SELECT * FROM product_category WHERE procat_prodet_id != '$pId' ORDER BY RAND()";
	$categoryData = $this->dbF->getRow($sql);
	if (!$this->dbF->rowCount) {
		return false;
	}
	$cats = explode(",", $categoryData['procat_cat_id']);

	$LIKE = "";
	foreach ($cats as $catIds) {
//find related Category products
		$LIKE .= " `product_category`.`procat_cat_id` LIKE '%$catIds%' OR";
	}
	$LIKE = trim($LIKE, "OR");

	$limitFrom = 0;
	$limitT0 = $limit;

//Find Product That in this category
	$sql = "SELECT `prodet_id` FROM `product_category`
	JOIN
	`proudct_detail`
	on `product_category`.`procat_prodet_id` = `proudct_detail`.`prodet_id`
	WHERE $LIKE
	GROUP BY `proudct_detail`.`prodet_id` ORDER BY RAND()";
	$products = '';

	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);

//remove current id from list
	$productIds = array_delete($productIds, $pId);

	if (!$this->dbF->rowCount || empty($productIds)) {
		return false;
	}

	$array['viewType'] = $viewType;
	return $this->productPrint($productIds, '0', $products, $array);
}

public function matchingProduct($pId, $limit, $viewType = true)
{
//Get Pid Category
//return all related categorys product...
	$data = array();
	$sql_ = "SELECT * FROM `product_setting` WHERE setting_name = 'related' AND p_id = ?";
	$data = $this->dbF->getRow($sql_,array($pId));
	if($this->dbF->rowCount> 0){
		$getlook_array = unserialize($data['setting_val']);
		$ids='';
		foreach ($getlook_array as $row_id) {
			$ids.=$row_id . ', ';
		}
		$ids=rtrim($ids,', ');
// var_dump($ids);
		$sql = "SELECT * FROM `proudct_detail` WHERE prodet_id IN ($ids) ORDER BY RAND() LIMIT $limit";
		$rows = $this->dbF->getRows($sql);

		$array['viewType'] = $viewType;
		return $this->productPrint($rows, '0', '', $array);
	} else {
		return false;
	}

// var_dump($data['setting_val']);
// var_dump(unserialize($data['setting_val']));

}    

public function bestSellerProducts($limitT0 = false, $QuickViewId = false, $viewType = true)
{
	$limitFrom = 0;
	if ($limitT0 == false) {
		$limitT0 = abs(intval($this->functions->ibms_setting('bestSellerProductLimit')));
		if ($limitT0 <= 0) {
			$limitT0 = 20;
		}
	}

	$sql = " SELECT * FROM `best_seller_products` b
	LEFT OUTER JOIN `proudct_detail` p ON p.prodet_id = b.product_id
	WHERE b.publish='1' ORDER BY b.SORT ASC ";
	$sql .= " LIMIT $limitFrom,$limitT0";
	$productIds = $this->dbF->getRows($sql);
// var_dump($productIds);
	$array['viewType'] = $viewType;
	return $this->productPrint($productIds, $QuickViewId, '', $array);
}

public function productSearch($key)
{
	$limitFrom = 0;
	$limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));
	$key = addslashes($key);
//search All related Match products
	$sql = "SELECT prodet_id,prodet_name,product_update
	FROM `proudct_detail`
	WHERE prodet_name LIKE '%$key%'
	GROUP BY prodet_id
	UNION
	SELECT prodet_id,prodet_name,product_update
	FROM `proudct_detail`
	WHERE prodet_name LIKE '%$key%'";

	$keyArray = explode(" ", $key);
	if (sizeof($keyArray) > 1) {
		foreach ($keyArray as $key2) {
			if (intval($key2) > 0) {
				continue;
			}
			$sql .= " OR prodet_name LIKE '%$key2%'";
		}
	}
	$sql .= " GROUP BY prodet_id";

	$tempTId = $this->functions->setTempTableVal('Query', $sql);
//$products       =     '<input type="hidden" style="display: none" id="q_tempTable" value="'.$tempTId.'"/>';

	$sql .= " LIMIT $limitFrom,$limitT0";
//echo $sql;
	$productIds = $this->dbF->getRows($sql);
	$productIds = $this->dbF->getRows($sql);
	if (!$this->dbF->rowCount) {
		return "";
	}
	$products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
	foreach ($productIds as $p) {
		$products .= $this->pBox($p['prodet_id']);
	}
	return $products;
}

public function makeSearchLabel($parameter, $val)
{
	return "<div class='btn searchLabel btn-xs btn-default'>$val
	<div class='btn btn-xs'>
	<i onclick='removeFromSearch(\"$parameter\",\"$val\")' class='glyphicon glyphicon-remove'></i></div>
	</div>";
}

public function productAdvanceSearchLabels()
{
//search word
	$searchWord = '';
	if (isset($_GET['s'])) {
		$key = $_GET['s'];
		$searchWord .= $this->makeSearchLabel("s", $key);
	}

// search price
	$price = "";
	if (isset($_GET['pMin']) || isset($_GET['pMax'])) {
		$pMin = floatval($_GET['pMin']);
		$pMax = floatval($_GET['pMax']);
		$searchWord .= $this->makeSearchLabel("price", "$pMin - $pMax");
	}

//search by color
	$color = "";
	if (isset($_GET['color'])) {
		$colorKey = $_GET['color'];
		$colorKey = trim($colorKey, ",");
		$colorKey = explode(",", $colorKey);
		foreach ($colorKey as $val) {
			$searchWord .= $this->makeSearchLabel("color", $val);
		}
	}

//search by size
	$size = "";
	if (isset($_GET['size'])) {
		$sizeKey = $_GET['size'];
		$sizeKey = trim($sizeKey, ",");
		$sizeKey = explode(",", $sizeKey);
		foreach ($sizeKey as $val) {
			$searchWord .= $this->makeSearchLabel("size", $val);
		}
	}

	return $searchWord;
}

public function productAdvanceSearch()
{
	$limitFrom = 0;
	$limitT0 = abs(intval($this->functions->ibms_setting('productLimit')));

//search word
	$searchWord = "";
	if (isset($_GET['s'])) {
		$key = $_GET['s'];
		$key = addslashes($key);
		$searchWord = "AND `prodet_name` LIKE '%$key%'";
	}

// search price
	$price = "";
	if (isset($_GET['pMin']) || isset($_GET['pMax'])) {
		$pMin = empty($_GET['pMin']) ? 0 : floatval($_GET['pMin']);
		$pMax = empty($_GET['pMax']) ? 900000 : floatval($_GET['pMax']);
		$price = "AND `prodet_id` in (SELECT `propri_prodet_id` FROM product_price WHERE propri_price BETWEEN $pMin AND $pMax)";
	}

//search by category
	$category = "";
	if (isset($_GET['cat'])) {
		$catId = intval($_GET['cat']);
		if ($catId == 0 || $catId == false) {
			$catId = $this->getCategoryId($_GET['cat']);
		}
		if ($catId == false) {
			$category = "";
		} else {
			$category = "AND `prodet_id` in (SELECT `procat_prodet_id` FROM product_category WHERE `procat_cat_id` LIKE '%$catId%')";
		}
	}

//search by color
	$color = "";
	if (isset($_GET['color'])) {
		$colorKey = $_GET['color'];
		$colorKey = trim($colorKey, ",");
		$colorKey = "'" . str_replace(",", "','", $colorKey) . "'";
		$color = "AND `prodet_id` in (SELECT `proclr_prodet_id` FROM product_color WHERE `color_name` in ($colorKey) )";
	}

//search by size
	$size = "";
	if (isset($_GET['size'])) {
		$sizeKey = $_GET['size'];
		$sizeKey = trim($sizeKey, ",");
		$sizeKey = "'" . str_replace(",", "','", $sizeKey) . "'";
		$size = "AND `prodet_id` in (SELECT `prosiz_prodet_id` FROM product_size WHERE `prosiz_name` in ($sizeKey) )";
	}


//search All related Match products
	$sql = "SELECT prodet_id,prodet_name,product_update
	FROM `proudct_detail`
	WHERE 1
	$searchWord
	$price
	$category
	$color
	$size
	GROUP BY prodet_id";

//if not found then found similar
	$union = "";
	if (isset($_GET['s'])) {
		$key = $_GET['s'];
		$key = addslashes($key);
		$union = " UNION
		SELECT prodet_id,prodet_name,product_update
		FROM `proudct_detail`
		WHERE 1
		$price
		$category
		$color
		$size
		AND (`prodet_name` LIKE '%$key%'";

		$keyArray = explode(" ", $key);
		if (sizeof($keyArray) > 1) {
			foreach ($keyArray as $key2) {
				if (intval($key2) > 0) {
					continue;
				}
				$union .= " OR prodet_name LIKE '%$key2%'";
			}
		}
		$union .= " ) GROUP BY prodet_id";
	}

	$sql .= $union;

	$tempTId = $this->functions->setTempTableVal('Query', $sql);
//$products       =     '<input type="hidden" style="display: none" id="q_tempTable" value="'.$tempTId.'"/>';

	$sql .= " LIMIT $limitFrom,$limitT0";
//echo $sql;
	$productIds = $this->dbF->getRows($sql);
	if (!$this->dbF->rowCount) {
		return "";
	}
	$products = '<input type="hidden" style="display: none" id="q_tempTable" value="' . $tempTId . '"/>';
	foreach ($productIds as $p) {
		$products .= $this->pBox($p['prodet_id']);
	}
	return $products;
}

public function noOfItemInMyCart()
{
	$userId = $this->webUserId();
	$tempUser = $this->webTempUserId();

	if ($userId == '0') {
		$sql = "SELECT sum(qty) as qty FROM `cart` WHERE `tempUser` = '$tempUser'";
		$data = $this->dbF->getRow($sql);
	} else {
		$sql = "SELECT sum(qty) as qty FROM `cart` WHERE `userId` = '$userId'";
		$data = $this->dbF->getRow($sql);
	}

	if ($this->dbF->rowCount > 0) {
		if ($data['qty'] != "") {
			return $data['qty'];
		}
	}
	return '0';
}

public function wishListInfo()
{
	$userId = webUserId();
	$tempUser = webTempUserId();

	if ($userId == '0') {
		$sql = "SELECT COUNT(pId) as pId FROM `cartwishlist` WHERE `tempUser` = '$tempUser'";
		$dataC = $this->dbF->getRow($sql);
	} else {
		$sql = "SELECT COUNT(pId) as pId FROM `cartwishlist` WHERE `userId` = '$userId'";
		$dataC = $this->dbF->getRow($sql);
	}

	$array = array('qty' => 0);

	if ($this->dbF->rowCount > 0) {
		$array['qty'] = $dataC['pId'];
		return $array;
	}
	return $array;
}

/**
* @param $pId
*/
public function productData($pId)
{
	$sql = "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
	FROM
	`proudct_detail` join `product_setting`
	on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
	WHERE
	`product_setting`.`setting_name`='publicAccess'
	AND `product_setting`.`setting_val`='1'
	AND `proudct_detail`.`product_update`='1'
	AND `proudct_detail`.`prodet_id` = '$pId'";
	$data = $this->dbF->getRow($sql);
	return $data;
}

/**
* @param $pId
* @return customPrice
*/
public function customSizePrice($pId)
{
	$currencyId = $this->currentCurrencyId();
	$sql = "SELECT * FROM product_size_custom WHERE `pId` = '$pId' AND currencyId = '$currencyId'";
	$customData = $this->dbF->getRow($sql);
	$price = $customData['price'];
	$price = empty($price) ? 0 : floatval($price);
	return $price;
}

public function three_for_two_data($grandTotal)
{

	$three_for_2_ibm_cat = intval( $this->functions->ibms_setting("checkout_two_for_3_category") );
	$three_for_2_cat_div = "3 For 2 Category";
	$three_for_2_qty     = 0;
	$three_for_2_pro_price = array();
	if ( $three_for_2_ibm_cat > 0 ) {
		$three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
	}else{
		$three_for_2_ibm_cat = array();
	}


##################### check if product category in 3 For 2 Category START ##############
	$three_for_2_category = "";
	if ( sizeof ( array_intersect($three_for_2_ibm_cat, $pro_cat ) ) > 0 && $newPrice > 0) {
		$three_for_2_pro_price[$cartId]["id"] = $pId;
		$three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
		$three_for_2_pro_price[$cartId]["qty"] = $qty;
		$three_for_2_qty += $qty;
		$three_for_2_category = " <img src='".WEB_URL."/images/3for2.jpg' height='40' />";
	}


	$three_for_2_qty = floor($three_for_2_qty/3);
	$three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price,$three_for_2_qty);
	$grandTotal = $grandTotal-$three_for_2_minus_price;
	$three_for_2_cat_div = '';

	$result_array = array();
	if( $three_for_2_minus_price > 0 ){

		$three_for_2_cat_div = "<div class='tc_line'></div>
		<div class='sub_box'>
		<div class='sub_1'>" . $_e['Three For Two Category'] . " </div>
		<div class='sub_2'>({$three_for_2_minus_price}) {$currencySymbol}</div>
		</div>
		<!--sub_box end-->";

		$result_array['three_for_two_discount']   = $three_for_2_minus_price;
		$result_array['three_for_2_div']          = $three_for_2_cat_div;
		$result_array['grandTotal']               = $grandTotal;
		$result_array['three_for_two_applied']    = true;

	} else {
		$result_array['three_for_two_applied']    = false;
	}



	return $three_for_2_minus_price;

}

public function cartInfo($productShortView = false, $view = 'first')
{
	global $_e;
/*
* qty | price| items |symbol| store| products
//if $productShortView true, price with discount and product shot view return
//else only total price return
// Cart Items <span class="cartItemNo"> echo $cartInfo['qty'];</span> echo _n($_e['Items']);)
//Cart Price   - <span class="cartPriceAjax" data-value="has">echo $cartInfo['price']." ".$cartInfo['symbol'];</span>
cart Product  <div class="cartSmallProduct" data-value="has">
<?php echo $cartInfo['products']; ?>
</div>
*/
$userId = webUserId();
$tempUser = webTempUserId();
$productsDiv = '';

if ($userId == '0') {
	$sql = "SELECT * FROM `cart` WHERE `tempUser` = '$tempUser'";
	$dataC = $this->dbF->getRows($sql);
} else {
	$sql = "SELECT * FROM `cart` WHERE `userId` = '$userId'";
	$dataC = $this->dbF->getRows($sql);
}

$array = array('qty' => 0, 'price' => 0, 'items' => 0, 'symbol' => '', 'store' => 0, 'products' => "");

if ($this->dbF->rowCount > 0) {
	$totalQty = 0;
	$totalPrice = 0;
	$totalItems = 0;
	$currencyCountry = $this->currentCurrencyCountry();
	$countryPk = $this->currentCountry();
	$currencyId = $this->currentCurrencyId();



# three for two category start
	$three_for_2_ibm_cat = intval( $this->functions->ibms_setting("checkout_two_for_3_category") );
	$three_for_2_cat_div = "3 For 2 Category";
	$three_for_2_qty     = 0;
	$three_for_2_pro_price = array();
	if ( $three_for_2_ibm_cat > 0 ) {
		$three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
	}else{
		$three_for_2_ibm_cat = array();
	}
# three for two category end




	$currencySymbol = $this->currentCurrencySymbol();
	$web_url      = WEB_URL;
	$qtyT         = $this->dbF->hardWords('Quantity', false);

	$pIdsForCheckOutOffer = '';



	foreach ($dataC as $val) {
		$pId = $val['pId'];
		$cartId = $val['id'];
		$qty = floatval($val['qty']);
$dealId = $val['deal']; // if not it is 0
@$checkout = $val['checkout']; // if not it is 0
@$info = unserialize($val['info']);

$pIdsForCheckOutOffer[$pId] = '1';

$totalQty += $qty;

$totalItems++;

$price = $this->productF->productTotalPrice($val['pId'], $val['scaleId'], $val['colorId'], $val['customId'], $currencyCountry, false);
$price = floatval($price);


if ($productShortView) {


	if ($dealId == '0') {
		$productImage = $this->productSpecialImage($pId, 'main');

		$data = $this->productData($pId);
		$pName = translateFromSerialize($data['prodet_name']);
		$link = WEB_URL . "/detail.php?pId=$pId";
		$scaleId = $val['scaleId'];
		$colorId = $val['colorId'];
		$customId = $val['customId'];

		$pPriceActual = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);
		$pPrice = $pPriceActual;
		$coupon = '';
		if ($checkout == '1') {
			$checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
			$discountPrice = $pPrice - $checkoutPrice;
			@$discountFormat = "price";
			@$discountP = $discountPrice;
		} else {

			$coupon = $this->getCoupon();
			if (!empty($coupon)) {
				$couponApply = true;
			}

			$discount = $this->productF->productDiscount($pId, $currencyId, $coupon);
//var_dump($discount);
			@$discountFormat = $discount['discountFormat'];
			@$discountP = $discount['discount'];
			$discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
			@$couponHas = $this->productF->productCouponStatus($coupon);
			if (!$couponHas) {
				$_SESSION['webUser']['coupon'] = '';
			}
		}

		$newPrice        = $pPriceActual - $discountPrice;
		$newPrice_simple = $newPrice;
		$pPrice_simple   = $pPrice;
		$pPrice         .= ' ' . $currencySymbol;
		$newPrice       .= ' ' . $currencySymbol;

# COLOR AND SIZE WORKING
		$hasScaleVal    =   $this->functions->developer_setting('product_Scale');
		$hasColorVal    =   $this->functions->developer_setting('product_color');

		$hasScale       =   ($hasScaleVal=='1' ? true : false);
		$hasColor       =   ($hasColorVal=='1' ? true : false);

		$pName          = $this->getProductFullNameWeb( $pId, $scaleId, $colorId );

		$pNames         = explode(' - ', $pName);
		$pName1         = $pNames[0];
		$pScaleName     = isset($pNames[1]) ? $pNames[1] : '';
		$pColorName     = isset($pNames[2]) ? $pNames[2] : '';
		$slugname       = $this->get_product_slugname($pId);
		$slug_link      = WEB_URL . '/' . $this->db->productDetail . $slugname;


		$pColorName     = ( $pColorName != '' ) ? str_replace('padding: 5px 12px;', 'padding: 2px 9px;', $pColorName) : '';
// $pName1         = "<a href='".WEB_URL."/detail?pId=$pId'>{$pName1}</a>";
		$pName1         = "<a href='{$slug_link}'>{$pName1}</a>";

		$sizeT          = $this->dbF->hardWords('Size', false);
		$colorT         = $this->dbF->hardWords('Color', false);

		$sizeInfo       =   '';
		$colorInfo      =   '';

		if( $hasScale && $pScaleName ){

			$sizeInfo       = "<div class='info_1'>{$sizeT}:<span>{$pScaleName}</span></div>";

			if($customId    != '0' && !empty($customId) && $scaleId == '0' ){
				$sizeInfo   = "<div class='info_1'>{$sizeT}:</div> <a href='#{$customId}' data-toggle='modal' data-target='#customSizeInfo_{$customId}'>".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a>";
				$customFieldsData   = $this->customSubmitValues($customId);
				$customFields       = $customFieldsData['form'];
				$sizeModal .= $this->functions->blankModal($_e['Custom'],"customSizeInfo_$customId",$customFields,$_e['Close']);
			}

			if($dealId      != '0' && !empty($dealId) && $scaleId == '0' ){
				$sizeInfo   = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>".$dealT." ".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
				$customFields = $this->dealSubmitPackage($info);
				$sizeModal  .= $this->functions->blankModal($_e['Custom'],"dealInfo_$dealId",$customFields,$_e['Close']);
			}
		}

		if( $hasColor && $pColorName ){
			$colorInfo = "<div class='info_1 colorspan'>{$colorT}: {$pColorName}</div>";
		}





	} else {
		$dealData = $this->getDealData($dealId);
		$productImage = $dealData['image'];
		$pName = $this->getDealNameWeb($dealData);
		$link = WEB_URL . "/productDeals?deal=$dealId  ";

		$newPrice = $pPrice = $this->getDealPrice($dealData);
		$discountPrice = $discount = 0;
		$discountFormat = $discountP = $pPrice = '';
		$newPrice_simple = $newPrice;
		$newPrice .= ' ' . $currencySymbol;
	}

	$totalPrice += $newPrice * $qty;
	if ($productImage == "") {
		$productImage = "default.jpg";
	}
	$productImageReal = $productImage;
	$productImage = $this->functions->resizeImage($productImage, '80', '', false);


	if ($view == 'first') {
		$productsDiv .= "<div class='cart_small glow tr_$cartId'>
		<div class='cart_pro_img_div cart_img'>
		<a href='$link' class=''><img src='$productImage'></a>
		</div>
		<div class='top_cart_detials_div'>
		<h2 class='r_text_head margin-0'>$pName1</h2>
		<span class='qtyS '><span class='pQtyS'>$qty</span> X $newPrice</span>
		<div>
		<span class='newPriceS '>$newPrice</span>
		<span class='oldPriceS  xs'><del>$pPrice</del></span>
		</div>
		</div>
		<div class='removeCartproduct' onclick='cartProductRemove(this,$cartId)'>X</div>
		</div>";

	} elseif ($view == 'checkout_popup_side_cart_view') {


		$slugname       = $this->get_product_slugname($pId);
		$slug_link      = WEB_URL . '/' . $this->db->productDetail . $slugname;

		$productImage = $this->functions->resizeImage($productImageReal, '', '200', false);

		$totalPriceSimple = $newPrice_simple * $qty;

############ Product Categories ##########
		$pro_cat    = $this->productF->product_category($pId);

##################### check if product category in 3 For 2 Category START ##############
		$three_for_2_category = "";
		if ( sizeof ( array_intersect($three_for_2_ibm_cat, $pro_cat ) ) > 0 && $newPrice_simple > 0) {
			$three_for_2_pro_price[$cartId]["id"] = $pId;
			$three_for_2_pro_price[$cartId]["price"] = intval($newPrice_simple);
			$three_for_2_pro_price[$cartId]["qty"] = $qty;
			$three_for_2_qty += $qty;
			$three_for_2_category = " <img src='".WEB_URL."/images/3for2.jpg' height='40' />";
		}

		$productsDiv .= "


		<div class='cart_1_inner          tr_{$cartId}'>

		<div class='cart_1_inner_1'>

		<div class='cart_1_btn ' onclick='cartProductRemove( this, {$cartId}, false )' >
		<img src='{$web_url}/webImages/close_btn.png' alt=''>
		</div>

		<a href='{$slug_link}'>

		<img src='{$productImage}' alt='' >

		</a>
		</div>

		<div class='cart_1_inner_2'>
		<h3>{$pName1} {$three_for_2_category}</h3>

		<div class='info_main'>

		{$colorInfo}

		{$sizeInfo}

		<div class='info_1'>
		{$qtyT}:<span>{$qty} @ {$newPrice_simple}</span>
		<span style='padding-left: 2px;' >{$currencySymbol}</span>
		</div>

		<div class='info_1'>{$totalPriceSimple}<span>{$currencySymbol}</span></div>

		</div>

		</div>

		</div>";


	}



} else {
	$totalPrice += $price * $qty;
}
}






############ 3 For 2 Category START #########
$three_for_2_qty = floor($three_for_2_qty/3);
$three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price,$three_for_2_qty);
$totalPrice = $totalPrice-$three_for_2_minus_price;
$three_for_2_cat_div = '';
if($three_for_2_minus_price > 0){
	$three_for_2_cat_div = "<div class='tc_line'></div>
	<div class='sub_box'>
	<div class='sub_1'>" . $_e['Three For Two Category'] . " </div>
	<div class='sub_2'>({$three_for_2_minus_price}) {$currencySymbol}</div>
	</div>
	<!--sub_box end-->
	<div class='tc_line'></div>
	<br><br>";
}
############ 3 For 2 Category END #########


$array['qty']                     = $totalQty;
$array['price']                   = $totalPrice;
$array['items']                   = $totalItems;
$array['symbol']                  = $this->currentCurrencySymbol();
$array['store']                   = $this->getStoreId();
$array['products']                = $productsDiv;
$array['three_for_2_cat_div']     = $three_for_2_cat_div;
$array['three_for_2_minus_price'] = $three_for_2_minus_price;
$array['pIdsForCheckOutOffer']    = $pIdsForCheckOutOffer;
return $array;

}

$array['symbol'] = $this->currentCurrencySymbol();
return $array;
}

/**
* @param $pid
* @return string | array if 2nd parameter is passed
*/
public function getProductNameWeb($pid, $slug = false)
{
	if ($pid == '0') {
		return "";
	}
	$defaultLang = currentWebLanguage();
	$sql  = " SELECT `slug`, `prodet_name` FROM `proudct_detail` WHERE `prodet_id` = '$pid' AND product_update = '1' ";
	$data = $this->dbF->getRow($sql);
	if ( $this->dbF->rowCount > 0 ) {
		$name = translateFromSerialize($data['prodet_name']);
		if ($slug) {
			$name = array( 'prodet_name' => $name, 'slug' => $data['slug'] );
		}
		return $name;
	}
	return false;
}

/**
* @param $id
* @return string
*/
public function getScaleNameWeb($id)
{
	if ($id == '0') {
		return "";
	}
	$sql = "SELECT `prosiz_name` FROM `product_size` WHERE `prosiz_id` = '$id'";
	$data = $this->dbF->getRow($sql);
	$name = $data['prosiz_name'];
	return $name;
}

/**
* @param $id
* @return string
*/
public function getColorNameWeb($id)
{
	if ($id == '0') {
		return "";
	}
	$sql = "SELECT `proclr_name` FROM `product_color` WHERE `propri_id` = '$id'";
	$data = $this->dbF->getRow($sql);
	$name = $data['proclr_name'];
	return $name;
}

public function getDealData($id)
{
	$sql = "SELECT * FROM product_deal WHERE id = '$id'";
	$data = $this->dbF->getRow($sql);
	return $data;
}

public function getDealNameWeb($data)
{
	$name = translateFromSerialize($data['name']);
	return $name;
}

public function getDealPrice($data)
{
	$price = unserialize($data['price']);
	$currencyId = $this->currentCurrencyId();
	return @$price[$currencyId];
}

public function getDealShippingStatus($info)
{
	$currencyId = $this->currentCurrencyId();
	foreach ($info as $val) {
		$pId = $val['pId'];
		$scaleId = $val['scaleId'];
		$colorId = $val['colorId'];
		$storeId = $val['storeId'];

		$shippingData = $this->productF->productShipping($pId, $currencyId);
		$shipping = $shippingData['propri_intShipping'];
		if ($shipping == '0') {
			return "0";
		}
	}
	return "1";
}

public function getDealWeight($info)
{
	$weight = 0;
	foreach ($info as $val) {
		$pId = $val['pId'];
		$scaleId = $val['scaleId'];
		$colorId = $val['colorId'];
		$storeId = $val['storeId'];

		$weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);
		$weight = $weight + $weightSingle;
	}
	return $weight;
}

public function getDealpIds($info, $pIdsForCheckOutOffer)
{
	$package = unserialize($info['package']);
	foreach ($package as $val) {
		$pId = $val;
		$pIdsForCheckOutOffer[$pId] = '1';
	}
	return $pIdsForCheckOutOffer;
}

public function getDealLowestProductQty($json)
{
	$totalQtyV = 0;

	foreach ($json as $val) {
		$pId = $val['pId'];
		$scaleId = $val['scaleId'];
		$colorId = $val['colorId'];
		$storeId = $val['storeId'];
		@$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
		$hash = md5($hashVal);

//Check stock in store
		$sqlCheck = "SELECT `qty_item`,`product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = '$hash'";
		$totalQty = $this->dbF->getRow($sqlCheck);
		if (intval($totalQty['qty_item']) < $totalQtyV || $totalQtyV == 0)
			$totalQtyV = intval($totalQty['qty_item']);

		if ($totalQtyV <= 0) {
			return false;
		}
	}
	return $totalQtyV;
}

public function getProductFullNameWeb($pid, $scaleId, $colorId)
{
	$pName = $this->getProductNameWeb($pid);
	if ($pName == false) {
		return false;
	}
	if ($scaleId != '0') {
		$sName = $this->getScaleNameWeb($scaleId);
	} else {
		$sName = "";
	}
	if ($colorId != '0') {
		$cName = $this->getColorNameWeb($colorId);
		$cName = "<span style='background:#$cName;padding: 5px 12px;color: #fff;font-size: 11px;border-radius: 50px;'></span>";
	} else {
		$cName = "";
	}
	$temp = "$pName - $sName - $cName";
	$temp = trim($temp, '- ');
	return $temp;
}

public function viewCartTable()
{
	$userId = $this->webUserId();
	$tempUser = $this->webTempUserId();

	if ($userId == '0') {
		$sql = "SELECT * FROM `cart` WHERE `tempUser` = '$tempUser'";
		$data = $this->dbF->getRowS($sql);
	} else {
		$sql = "SELECT * FROM `cart` WHERE `userId` = '$userId'";
		$data = $this->dbF->getRows($sql);
	}

	$temp = '';
	if ($this->dbF->rowCount > 0) {
		$i = 0;
		$SNOT = $this->dbF->hardWords('SNO', false);
		$PRODUCTT = $this->dbF->hardWords('PRODUCT', false);
		$PRICET = $this->dbF->hardWords('PRICE', false);
		$DISCOUNTT = $this->dbF->hardWords('DISCOUNT', false);
		$QUANTITYT = $this->dbF->hardWords('QUANTITY', false);
		$WEIGHTT = $this->dbF->hardWords('WEIGHT', false);
		$TOTALT = $this->dbF->hardWords('TOTAL', false);
		$temp = "<table id='cartViewTable' class='table tableIBMS table-hover table-striped'>
		<thead>
		<tr>
		<th>$SNOT</th>
		<th>$PRODUCTT</th>
		<th>$PRICET</th>
		<th>$DISCOUNTT</th>
		<th>$QUANTITYT</th>
		<th>$WEIGHTT</th>
		<th>$TOTALT</th>
		</tr>
		</thead>
		<tbody>
		";

		$grandTotal = 0;
		$totalWeight = 0;
		foreach ($data as $val) {
			$i++;
			$cartId = $val['id'];
			$pId = $val['pId'];
			$scaleId = $val['scaleId'];
			$colorId = $val['colorId'];
			$customId = $val['customId'];
			$storeId = $val['storeId'];
			$qty = $val['qty'];

			$country = $this->currentCountry();
			$currencyId = $this->currentCurrencyId();

			$pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
			$pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $country, false);


			$discount = $this->productF->productDiscount($pId, $currencyId);
			$discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
			$newPrice = $pPrice - $discountPrice;

			$totalPrice = $newPrice * $qty;
			$grandTotal += $totalPrice;
			$currencySymbol = $this->currentCurrencySymbol();

			$shippingData = $this->productF->productShipping($pId, $currencyId);
			$shipping = $shippingData['propri_intShipping'];
			$pImage = $this->productSpecialImage($pId, 'main');
			$weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);
			$weight = $weightSingle * $qty;
			$totalWeight += $weight;

			$temp .= "<tr id='tr_$cartId' class='$cartId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
			<td>$i
			<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
			<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
			</td>
			<td>$pName</td>
			<td><span class='pPrice'>$pPrice</span></td>
			<td><span class='pDiscount'>$discountPrice</span></td>
			<td>
			<div class='col-sm-12 padding-0'>
			<div class='col-sm-5 btn btn-sm '>
			<span class='pQty'>$qty</span>
			</div>
			<div class='col-sm-6 padding-0'>
			<div class='btn-success btn-sm btn col-sm-4' onclick='addPlusToCart(this,$cartId)'>
			<i class='glyphicon glyphicon-thumbs-up'></i>
			</div>
			<div class='btn-warning btn-sm btn  col-sm-4' onclick='minusFromCart(this,$cartId)'>
			<i class='glyphicon glyphicon-thumbs-down'></i>
			</div>

			<div class='btn-danger btn-sm btn col-sm-4' onclick='cartProductRemove(this,$cartId)'>
			<i class='glyphicon glyphicon-trash'></i>
			</div>
			</div>
			</div>
			</td>
			<td><span class='pWeight'>$weight </span> KG</td>
			<td><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</td>
			</tr>";
		}
		$GRANDT = $this->dbF->hardWords('GRAND TOTAL', false);
		$WEIGHTT = $this->dbF->hardWords('TOTAL WEIGHT', false);
		$temp .= "
		<tr><th colspan='4'>$GRANDT</th>
		<th colspan='3'>
		<input type='hidden' class='totalWeightInput' value='$totalWeight' data_cart='$cartId'/>
		<input type='hidden' id='priceCode' value='$currencySymbol' data_cart='$cartId'/>

		<span class='pGrandTotal'>$grandTotal </span> $currencySymbol</th>
		<tr><th colspan='4'>$WEIGHTT</th>
		<th colspan='3'>
		<span class='pTotalWeight'>$totalWeight</span> KG</th>
		</tr>
		</tbody></table>";
	} else {
		$temp = false;
	}

	return $temp;
}

public function customSubmitValues($customId, $submit = false)
{
	global $_e;

	$sql = "SELECT *,
	(SELECT setting_value FROM p_custom_setting as b WHERE b.fieldName=s.setting_name AND b.setting_name='name' AND b.c_id=a.custom_id ) as tName
	FROM `p_custom_submit` as a JOIN `p_custom_submit_setting` as s ON a.id = s.orderId  WHERE a.id = '$customId'";
	$data = $this->dbF->getRows($sql);
	if (empty($data)) {
		return false;
	}

	foreach ($data as $val) {
		$name = $val['setting_name'];
		$tName = translateFromSerialize($val['tName']);
		if (empty($tName)) {
			$tName = $name;
		}
		$value = $val['setting_value'];
		$form_fields[] = array(
			'label' => $tName,
			'format' => "$value"
		);
	}


	if ($data[0]['submitLater'] == '1' && $this->functions->isWebLink()) {
		$customEditLink = WEB_URL . "/viewOrder?editCustom=" . $this->functions->encode($customId);
		if ($submit) {
			$temp = "<div class='text-center form-group  margin-0'><a href='$customEditLink' class='btn themeButton'>" . $_e["Edit custom size form"] . "</a></div>";
		} else {
			$temp = "<div class='text-center form-group  margin-0'><div class='btn themeButton'>" . $_e["Submit now, But i will fill this form later"] . "</div></div>";
		}

		$form_fields[] = array(
			'thisFormat' => "$temp"
		);
	} else if ($data[0]['submitLater'] == '1' && $this->functions->isAdminLink()) {
		$form_fields[] = array(
			'thisFormat' => "<div class='text-center form-group  margin-0'>" . $_e["User not fill final form"] . "</div>"
		);
	} else if ($data[0]['submitLater'] == '0' && $submit) {
		$form_fields[] = array(
			'label' => $_e["Submit DateTime"],
			'type' => "none",
			'format' => "<div class='text-center form-group  margin-0'>" . date('H:i:s d-m-Y', strtoTime($data[0]['dateTime'])) . "</div>"
		);

		$pdfLink = WEB_URL . "/src/pdf/measurementPDF.php?id=$customId&orderId=" . $this->functions->encode($customId);
		$form_fields[] = array(
			'label' => $_e["Print PDF"],
			'type' => "none",
			'thisFormat' => "<div class='text-center form-group  margin-0'><a href='$pdfLink' target='_blank' class='btn themeButton'>{$_e["Print PDF"]}</a></div>"
		);

	}

	$form_fields['main'] = array(
		'type' => "form",
		'format' => "<div class='form-horizontal'>{{form}}</div>
		<style>#customSizeInfo_$customId .modal-body{padding: 0 15px;}</style>
		"
	);

	$format = '<div class="form-group border padding-5 margin-0">
	<label class="col-sm-2 col-md-3 text-right">{{label}}</label>
	<div class="col-sm-10  col-md-9 text-center">
	{{form}}
	</div>
	</div>';

	$array = array("form" => $this->functions->print_form($form_fields, $format, false), "formFill" => $data[0]['submitLater']);
	return $array;
}

public function dealSubmitPackage($orderId, $cart = true)
{
	if ($cart) {
		$orderId = $this->getDealProductOrders($orderId);
	}
	foreach ($orderId as $val) {
		$name = $val['name'];
		$form_fields[] = array(
//'label' => $name,
			'format' => "<div>$name</div>"
		);
	}

	$form_fields['main'] = array(
		'type' => "form",
		'format' => "<div class='form-horizontal'>{{form}}</div>"
	);

	$format = '<div class="form-group border padding-5 margin-0">
	<div class="col-sm-12 text-center">
	{{form}}
	</div>
	</div>';

	return $this->functions->print_form($form_fields, $format, false);

}


/**
* Find related GIFT from cart related product, then add in cart.
* @param $price
* @param array $PIds
*/
private function free_gift_add( $price , array $PIds = array() ){
	if($this->functions->developer_setting("add_free_gift_in_cart") == '1'){
		$currencyId     = $this->currentCurrencyId();

		$_POST['storeID'] = $this->getStoreId();
		$_POST['scaleId'] = 0;
		$_POST['colorId'] = 0;
		$_POST['customQty'] = 0;
		$_POST['free_gift'] = "1";

		$whereIds       = '';
		foreach ($PIds as $key => $val) {
			$whereIds .= "'$key',";
		}
		$whereIds       = trim($whereIds, ",");
		$today          = date('m/d/Y');
		$sql            = "SELECT * FROM product_setting WHERE
		p_id IN (
		SELECT proadc_prodet_id FROM product_addcost WHERE
		proadc_name = 'giftAdd_when_cart_price'
		AND proadc_cur_id = '$currencyId'
		AND proadc_price <= '$price'
		AND proadc_price > '0'

		AND product_addcost.proadc_prodet_id IN (
		SELECT p_id FROM product_setting
		WHERE setting_name = 'launchDate'
		AND setting_val < '$today'
		)
		)
		AND p_id IN ($whereIds)
		AND setting_name = 'free_gift'
		GROUP BY p_id ORDER BY rand() LIMIT 0,5";

		$data           = $this->dbF->getRows($sql);

		if( ! empty( $data ) ) {
// Add to cart only one product.. so why loop?
// whe product add to cart fail, then try next, if success then break loop
			foreach( $data as $key=>$val ) {
				@$pId   = unserialize($val["setting_val"]);
				@$pId    =  $pId[0];
				if( empty($pId) ){ continue;}

				$_POST['pId']     = $pId;

//Add Free Gift
				$this->functions->modelFunFile("products_ajax_functions.php");
				$pro_ajax = new product_ajax();
				$return = $pro_ajax->AddToCart();
				if($return === "1" || $return === true){
					header("Location: ".$this->functions->currentUrl(false));
//exit;
					break;
				}
}// foreach loop end
}// if end..

//If function reach here, its mean no free gift product add, now check free gift default product
$default_product_id     =   $this->functions->ibms_setting("default_free_gift");
if( $default_product_id>0 && empty($return) ){
	$check_out_gift_price_limit   =   unserialize($this->functions->ibms_setting("check_out_gift_price_limit"));
	$check_out_price   = floatval($check_out_gift_price_limit[$currencyId]);

	if($price>=$check_out_price){
		$_POST['pId']   = $default_product_id;

//Add Free Gift
		$this->functions->modelFunFile("products_ajax_functions.php");
		$pro_ajax = new product_ajax();
		$return         = $pro_ajax->AddToCart();
		if ( $return === "1" || $return === true ){
			header("Location: ".$this->functions->currentUrl(false));

		}
}//if end.. check_out_price
}//if end.. default free gift product
}//check developer setting if end
}

/**
* Call from cart view product.
* @param $price Cart price limit.
* @param array $notPIds list of product ids, that dont want to show in cart offers
* @return bool|string
*/
public function checkOutOffer($price, $notPIds = array())
{
	$isCheckOutOfferFromDev = $this->functions->developer_setting('check_out_offer');
	$isCheckOutOfferFromAdmin = $this->functions->ibms_setting('check_out_offer');

	if ($isCheckOutOfferFromDev == '0' || $isCheckOutOfferFromAdmin == '0') {
		return false;
	}

	$country = $this->currentCountry();
	$currencyId = $this->currentCurrencyId();
	$currencySymbol = $this->currentCurrencySymbol();

/*
* Old work was, only for 1 cart price limit,that get from IBMS Setting,
* now new work: on different cart price, show different products, .
*
*  $checkOutPriceLimit       = unserialize($this->functions->ibms_setting('check_out_price_limit'));
@$checkOutPriceLimit       = $checkOutPriceLimit[$currencyId];
if(empty($checkOutPriceLimit)) $checkOutPriceLimit =0;
if($price < $checkOutPriceLimit || empty($checkOutPriceLimit)){
return false;
}*/
$offerIds = array();
$whereNotIds = '';
foreach ($notPIds as $key => $val) {
	$whereNotIds .= "'$key',";
}
$whereNotIds    = trim($whereNotIds, ",");
$today          = date('m/d/Y');

//for get pIds, Checkout offers products
$sql    = "SELECT product_addcost.* FROM product_addcost WHERE
product_addcost.proadc_prodet_id NOT IN ($whereNotIds)
AND product_addcost.proadc_name = 'checkout_price'
AND product_addcost.proadc_cur_id = '$currencyId'
AND product_addcost.proadc_price > 0

AND product_addcost.proadc_prodet_id IN (
SELECT proadc_prodet_id FROM product_addcost WHERE
proadc_prodet_id NOT IN ($whereNotIds)
AND proadc_name = 'when_cart_price'
AND proadc_cur_id = '$currencyId'
AND proadc_price < '$price'
)

AND product_addcost.proadc_prodet_id IN (SELECT p_id FROM product_setting
WHERE setting_name = 'launchDate'
AND setting_val < '$today')

GROUP BY proadc_prodet_id ORDER BY rand() LIMIT 0,3";
$data = $this->dbF->getRows($sql);
$temp = '';
foreach ( $data as $val ) {
	$pId = $val['proadc_prodet_id'];
$return = $this->productDateIsReadyForLaunch($pId); //check product is ready for launch
if (!$return) continue;
$temp .= $this->dealProductChoice($pId, false, $val);
}

return $temp;
}

public function viewCartTable2()
{
//view from cart.php from cart table
	global $_e;
	$userId     = $this->webUserId();
	$tempUser   = $this->webTempUserId();

############ Buy 2 get 1 free START #########
	$has_buy_get_free   = false;
	$buy_get_free_query = "";
	if( $this->functions->developer_setting("buy_2_get_1_free") == "1" ){
		$has_buy_get_free = true;
		$buy_get_free_query = ", (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free') as buy_2_get_1_free
		, (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free_qty') as buy_get_free_apply_limit_qty";
	}
############# Buy 2 get 1 free END ##########

	if($userId == '0'){
		$sql = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `tempUser` = '$tempUser'";
		$data = $this->dbF->getRowS($sql);
	}else{
		$sql = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `userId` = '$userId'";
		$data = $this->dbF->getRows($sql);
	}

	$temp   = '';
	if ($this->dbF->rowCount > 0) {
		$i  = 0;

//Main cart products wrapper div start
		$temp           = "<div class='container-fluid table-responsive padding-0' id='cartViewTable'>";

		$grandTotal     = 0;
		$totalWeight    = 0;
		$shippingPrice  = 0;
		$pIdsForCheckOutOffer = '';

		$hasScaleVal = $this->functions->developer_setting('product_Scale');
		$hasColorVal = $this->functions->developer_setting('product_color');

		$hasScale   = ($hasScaleVal == '1' ? true : false);
		$hasColor   = ($hasColorVal == '1' ? true : false);
		$couponApply = false;
		$country    = $this->currentCountry();
		$currencyCountry = $this->currentCurrencyCountry();
		$currencyId = $this->currentCurrencyId();
		$currencySymbol = $this->currentCurrencySymbol();
$has_free_gift  = false; // it is use to check if any product has free? then dont add other free gift product
foreach ($data as $val) {
	$i++;
	$discountPrice  = 0;
	$discountFormat = $discountP = '';

$cartId     = $val['id'];       // Cart table primary ID
$pId        = $val['pId'];      // Product ID
$scaleId    = $val['scaleId'];  // Product size ID, size=>(small,medium,large)
$colorId    = $val['colorId'];  // Product color ID,
$storeId    = $val['storeId'];  // Product store ID, sale from this store
$qty        = $val['qty'];      // Product Quantity
$customId   = $val['customId']; // Product custom size Id, if has
$dealId     = $val['deal'];     // Product Deal Id, => Get 3 product in 1000 price    // if not it is 0
@$checkout  = $val['checkout']; // Checkout Product Price, it is on discount from cart page. // if not it is 0
@$info      = unserialize($val['info']); //product info, use to save any random data on required.

@$shippingId = $val['shippingPrice'];   // Product shipping Price

$shippingPriceData  = $this->shippingPriceByClass($shippingId, $shippingPrice);
$shippingPriceT     = $shippingPriceData["price"];
$shippingPrice      = $shippingPriceData["classPrice"];

if ($customId != '0') {
$totalAllowQty  = "9999999"; //If product has custom size, then its Stock qty...
} else {
	$totalAllowQty  = $this->productF->productQTY($pId, $storeId, $scaleId, $colorId, true);
}

$pScaleName         = $pColorName = '';
$coupon             = '';

############ Buy 2 Product get 1 free START ############
$buy_2_get_1_free_div   = "";
$buy_2_get_1_free_input = "";

###################### FREE GIFT #################
$free_gift_product_div = "";

if ($dealId == '0') {
//Normal Product / checkout product / custom size product

	$pName          = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
	$pNames         = explode(" - ", $pName);
	@$pName1        = $pNames[0];
	@$pScaleName    = $pNames[1];
	@$pColorName    = $pNames[2];
	$pName1         = "<a href='" . WEB_URL . "/detail?pId=$pId'>$pName1</a>";
	$pIdsForCheckOutOffer[$pId] = '1';

	$pPrice         = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);

####################################################################################
################ Buy 2 Product get 1 free START ################
	if( $val["buy_2_get_1_free"] == '1' && $has_buy_get_free ){
$buy_get_free_apply_limit_qty       =  empty($val["buy_get_free_apply_limit_qty"]) ? "2" : $val["buy_get_free_apply_limit_qty"]; //limit of product to buy
$class  = "displaynone";
if( $qty >= $buy_get_free_apply_limit_qty ){
	$class = "";
}
$free_qty               =  floor($qty/$buy_get_free_apply_limit_qty);
$buy_2_get_1_free_input =  "<input type='hidden' class='hidden buy_2_get_1_free' value='$buy_get_free_apply_limit_qty' data-id='$cartId' data-limit='$buy_get_free_apply_limit_qty' id='buy_2_get_1_free_$cartId'>";
$buy_2_get_1_free_div   =  "<div class='$class buy_get_free_css buy_2_get_1_free_div'>"._replace("{{buy_qty}}",$buy_get_free_apply_limit_qty,$_e["Buy {{buy_qty}} Get 1 free"])."</div>";
$buy_2_get_1_free_div   .=  "<div class='clearfix'></div><div class='$class buy_free_qty buy_get_free_css buy_2_get_1_free_div'>"._replace("{{free_qty}}","<span class='you_get_free_qty'>$free_qty</span>",$_e["You Get +{{free_qty}} free"])."</div>";
}
################ Buy 2 Product get 1 free END ##################
####################################################################################


######### //checking if this is checkout offer then checkout discount offer apply
######### //if deal is 0 then checout offer need to check,
if ($checkout == '1') {
	$checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
//add color or scale price
	$p_color_price      = $this->productF->colorPrice($colorId, $currencyId, $pId);
	@$p_color_price     = $p_color_price['proclr_price'];
	$p_size_price       = $this->productF->scalePrice($scaleId, $currencyId, $pId);
	@$p_size_price      = $p_size_price['prosiz_price'];
	$checkoutPrice      = $checkoutPrice + floatval($p_color_price) + floatval($p_size_price);
	$discountPrice      = $pPrice - $checkoutPrice;
	@$discountFormat    = "price";
	@$discountP         = $discountPrice;
}
############################################################
################# FREE GIFT Check Is product FREE then make it price 0 free ############
else if ($checkout == '2') {
	$has_free_gift      = true;
	$discountPrice      = $pPrice;
	@$discountFormat    = "price";
	@$discountP         = $discountPrice;

	$free_gift_product_div = $this->productF->free_gift_text();
}
else {
	$coupon             = $this->getCoupon();
	@$couponHas         = $this->productF->productCouponStatus($coupon);
	if (!$couponHas) {
		$_SESSION['webUser']['coupon'] = '';
	}

	$discount           = $this->productF->productDiscount($pId, $currencyId, $coupon);
//var_dump($discount);
	@$discountFormat = $discount['discountFormat'];
	@$discountP = $discount['discount'];
	$discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
} // /$checkout else end

$shippingData = $this->productF->productShipping($pId, $currencyId);
$shipping = $shippingData['propri_intShipping'];
$pImage = $this->productSpecialImage($pId, 'main');
$weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);

} else {
// Deal product
	$dealData = $this->getDealData($dealId);
	$pName1 = $this->getDealNameWeb($dealData);
	$pName1 = "<a href='" . WEB_URL . "/productDeals?deal=$dealId'>$pName1</a>";

	$pPrice = $this->getDealPrice($dealData);
	$pIdsForCheckOutOffer = $this->getDealpIds($dealData, $pIdsForCheckOutOffer);

	$totalAllowQty = $this->getDealLowestProductQty($info);

	$shipping = $this->getDealShippingStatus($info);
	$pImage = $dealData['image'];
	$weightSingle = $this->getDealWeight($info);
}

$discountPrice  = round($discountPrice, 2);
$newPrice       = round($pPrice - $discountPrice, 2);
$productQtyDiscount = $discountPrice * $qty;
$totalPrice     = $newPrice * $qty;
$grandTotal     += $totalPrice;

$weight         = $weightSingle * $qty;
$totalWeight    += $weight;

$sum            =   $qty * $pPrice;
$sumT           = $this->dbF->hardWords('sum', false);
$weightT        = $this->dbF->hardWords('Weight', false);
$discountT      = $this->dbF->hardWords('Discount', false);
$qtyT           = $this->dbF->hardWords('Quantity', false);
$totalT         = $this->dbF->hardWords('Total', false);
$Farg           = $this->dbF->hardWords('Farg', false);
$TotalITaxT     = $this->dbF->hardWords('Total Including Tax', false);
$sizeT          = $this->dbF->hardWords('Size', false);
$dealT          = $this->dbF->hardWords('Deal', false);
$sizeInfo       = '';
$colorInfo      = '';
if ($hasScale) {
	$sizeInfo   = "<div>$sizeT# : $pScaleName</div>";

############## Show custom size info on popup ###############
	if ($customId != '0' && !empty($customId) && $scaleId == '0') {
		$sizeInfo = "<div>$sizeT# : <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
		$customFieldsData = $this->customSubmitValues($customId);
		$customFields = $customFieldsData['form'];
		$sizeInfo .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
	}
############## Show deal's product info on popup ###############
	if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
		$sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
		$customFields = $this->dealSubmitPackage($info);
		$sizeInfo .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
	}
}

if ($hasColor) {
	$colorInfo = "<div>$Farg : $pColorName</div>";
}

//if deal then color or scale make blank
if ($dealId != '0') {
//$sizeInfo = '';
	$colorInfo = '';
}

$stockCheck = $this->functions->developer_setting('product_check_stock');
if ($stockCheck == '0') {
	$totalAllowQty = 9999999;
}

if ($this->functions->developer_setting('addQty_custome') == '1') {
	$addQty_custome = "<div class='addByQtyDiv'>
	<input type='number' min='1' max='$totalAllowQty' data-prev='$qty' value='$qty' pattern='[0-9]{1,5}' class='addByQty_$cartId' />
	</div>
	<div class='addByQtyBtn' onclick='addByQty(this,$cartId)'><i class='glyphicon glyphicon-refresh'></i></div>";
} else {
	$addQty_custome = "<div class='cartPlus'  onclick='addPlusToCart(this,$cartId)'>+</div>
	<div class='cartMinus' onclick='minusFromCart(this,$cartId)'>-</div>";
}

######################### FREE GIFT #################
//If free gift then add more qty option hide.
if($checkout == "2" ){
	$addQty_custome     = "";
}
######################### FREE GIFT #################


// Product HTML to display in cart.php
$temp .= "

<div class='container-fluid padding-0 padding-bottom border cart_product_wrapper cart_product_$cartId $cartId' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
<div class='col-md-1 col-sm-1 padding-0 serialTd'>
<div class='cartSerial'>$i.</div>
<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
<input type='hidden' class='hidden' value='$totalAllowQty' id='productTotalQty_$cartId'>
<input type='hidden' class='hidden shippingClass' value='$shippingPriceT' id='shippingClass_$cartId'>
<input type='hidden' class='hidden' value='$stockCheck' id='stockCheck_$cartId'>
$buy_2_get_1_free_input
</div>
<div class='col-md-3 col-sm-3 padding-0 col-xs-12 text-center-xs imgTd'><img src='images/$pImage'/></div>
<div class='col-md-2 col-sm-2 col-xs-12 text-center-xs padding-0 nameTd'>
<div class=''>
<div>$pName1</div>
$sizeInfo
$colorInfo
$buy_2_get_1_free_div
$free_gift_product_div
</div>
</div>
<div class='col-md-6 col-sm-6 padding-0 col-xs-12 priceTd'>
<div class='removeCartproduct' onclick='cartProductRemove(this,$cartId)'>X</div>
<div class='sumMainDiv'>
<div class='qtyDiv'>
<div>$qtyT</div>
$addQty_custome
<div class='productTotalQ'>
<div class='productQty'><span class='pQty'>$qty</span></div>
<div>X</div>
<div class='productPrice'><span class='pPrice'>$pPrice</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$sumT</div>
<div class='priceTemp'><span class='sumProduct'>$sum</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$weightT</div>
<div class='priceTemp'><span class='pWeight'>$weight </span> KG</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$discountT</div>
<div class='priceTemp'><span class='pDiscount'>$productQtyDiscount</span> $currencySymbol</div>
</div>
</div>

<div class='productTotal'>
<div class='productSum'>
<div class=''>$TotalITaxT</div>
<div class='priceTemp'><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</div>
</div>
</div>
</div>
</div>
</div>
";
}

//free

$temp .= "
</div>
<br>";


############ FREE GIFT ADD START #################
// When new free gift add in cart, reload whole page. to show this free product in cart list.
if ( $this->functions->developer_setting("add_free_gift_in_cart") == "1" && ! $has_free_gift) {
	$this->free_gift_add($grandTotal, $pIdsForCheckOutOffer);
}
############ FREE GIFT ADD END  #################


############ CHECK OUT OFFER #################
//get checkout offers, and show on submit page...
$checkoutOnClick    =   $checkoutModelClick = '';
$checkoutOffers     =   '';
if (!isset($_GET['checkout'])) {
	$checkoutOffers = $this->checkOutOffer($grandTotal, $pIdsForCheckOutOffer);
	if (!empty($checkoutOffers)) {
//Remove comment then check out offer show in popup screen
//$temp .=$this->functions->blankModal($_e["Check Out Offer But Now & Get Special Discount"],"checkoutOffer",$checkoutOffers,$_e['Close'],'',true,5000);
		$checkoutOnClick    = " onsubmit='return checkOutOffer();'";
		$checkoutModelClick = " data-toggle='modal' data-target='#checkoutOffer' ";
	}
}
############ CHECK OUT OFFER #################


$country_list   = $this->functions->countrylist();
$storeCountry   = $this->currentCountry();
$countryName    = $country_list[$storeCountry];
$token          = $this->functions->setFormToken('WebOrderReadyForCheckOut', false);
$countryT       = $this->dbF->hardWords('Country', false);
$ShippingT      = $this->dbF->hardWords('Shipping Price', false);
$FreeT          = $this->dbF->hardWords('FREE', false);
$TotalWeightT   = $this->dbF->hardWords('Total Weight', false);
$KlarnaFCheckOut = $this->dbF->hardWords('Klarna Fast CheckOut', false);
$GrandTotalT    = $this->dbF->hardWords('Grand Total', false);
$LoginContinue  = $this->dbF->hardWords('Login To Continue', false);
$Continue       = $this->dbF->hardWords('Continue', false);

$price_cal      = $this->functions->product_tax_cal($grandTotal, 25);

$tax            = $price_cal['tax_price'];

$shippingInfo   = $this->shippingInfo();
$shippingType   = $shippingInfo["shippingType"];
$shippingPriceLimit = $shippingInfo["priceLimit"];

if ($grandTotal > $shippingPriceLimit) {
	$shippingPrice = 0;
}

if ($this->functions->developer_setting('shipping_class') == '1' && $shippingType == 'class') {
	$grandTotal = ($grandTotal + $shippingPrice);
} else {
	$shippingPrice = 0;
}


$giftCardSystemData = $this->giftCardSystem($grandTotal);
$giftCard_payPrice = 0;
if (isset($giftCardSystemData['payPrice'])) {
	$giftCard_payPrice = $giftCardSystemData['payPrice'];
}

$jsInfo         = "
<input type='hidden' class='shippingPrice' value='$shippingPrice'/>
<input type='hidden' class='shippingLimit' value='$shippingPriceLimit'>
<input type='hidden' class='shippingType'  value='$shippingType'>
";

//<div class='col-sm-12 lead'>$ShippingT : $FreeT</div>
$temp .= "
<form $checkoutOnClick  action='orderInvoice.php' method='post'>
$token
$jsInfo
<div class='shippingWight col-sm-12'>
<div class='col-sm-6 text-center'>
<div class='col-sm-12 lead'>
$ShippingT : <span class='shippingPriceText'>$shippingPrice</span> $currencySymbol
</div>
<div class='col-sm-12 form-horizontal'>
<div class='form-group'>
<label for='receipt_vendor' class='col-sm-2 control-label'>" . $countryT . "</label>
<div class='col-sm-8'>
<fieldset class='sender_countryFieldset'>
<input type='hidden'  name='shippingWidget' value='$storeCountry'/>
<input type='text' readonly id='shippingWidget' required
value='$countryName' class='form-control'/>
</fieldset>
</div>
</div>
<span class='pShippingPriceTemp'> </span>
</div>
<div class='col-sm-12 displaynone'>
<button type='button' class='btn btn-primary btn-sm' onclick='shippingPriceWidget();'>Update Shipping Price</button>
<input type='hidden' id='storeCountryShippingWidget' name='storeCountry' value='$storeCountry'>
<input type='hidden' id='priceCodeShippingWidget' value='$currencySymbol'/>
</div>
<div class='clearfix'></div>
<br>
</div>";


$temp .= "<div class='col-sm-6 col-xs-12 padding-0'>";
$temp .= $this->couponSystem();
$temp .= $giftCardSystemData['form'];
$temp .= "</div>";

//detuct giftPrice from grand Total/
$giftCardDiv = '';
if ($giftCard_payPrice > 0) {
	$grandTotal = $grandTotal - $giftCard_payPrice;
	$giftCardDiv = "<div class='productSum'>
	<div class=''>" . $this->dbF->hardWords('Gift Card Price', false) . "</div>
	<div class='priceTemp'>
	( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
	</div>
	</div>
	";
}

$temp .= "
</div><!--shippingWight End-->


<br>
<div class='GrandTotalDiv'>
<div class='col-xs-12 padding-0 g_div'>

<div class='productSum'>
<div class=''>$TotalWeightT</div>
<div class='priceTemp'><span class='pTotalWeight'>$totalWeight</span> KG</div>
</div>

<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Total Tax 25%', false) . "</div>
<div class='priceTemp'>
<span class='pGrandtax'>$tax </span> $currencySymbol
</div>
</div>

$giftCardDiv

<div class='productSum'>
<div class=''>$GrandTotalT</div>
<div class='priceTemp'>
<input type='hidden' class='totalWeightInput' value='$totalWeight' data-cart='$cartId'/>
<input type='hidden' id='priceCode' value='$currencySymbol' data-cart='$cartId'/>
<span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol
</div>
</div>
</div><!--GrandTotalDiv > col-sm-12 End-->
<div class='continueBtn'>";

$login = $this->webClass->userLoginCheck();
$loginForOrder = $this->functions->developer_setting('loginForOrder');

$temp .= '<div><!-- Buttons Div-->';
if ($this->functions->developer_setting('klarna') == '1') {
	$temp .= '<button type="submit" value="Klarna" class="btn themeButton cartSubmit1">' . $KlarnaFCheckOut . '</button>';
}

if (!$login && $loginForOrder == '1') {
	$temp .= ' <a href="' . WEB_URL . '/login" class="btn themeButton">' . $LoginContinue . '</a>';
} else {
	$temp .= '<button type="submit"  value="continue"  class="btn themeButton cartSubmit1">' . $Continue . '</button>';
}
$temp .= '</div><!-- Buttons Div end-->';

$temp .= "</div><!--continueBtn end-->
</div><!--GrandTotalDiv end-->
</form>";
} else {
	$temp = false;
}

return array("cart" => $temp, "offer" => @$checkoutOffers);
}

public function removeCartCheckoutOfferProduct($cartId, $pId)
{
	$sql = "DELETE FROM `cart` WHERE `id`= '$cartId' AND `pId` = '$pId' AND `checkout` = 1 ";
	$this->dbF->setRow($sql);
}

public function viewCartTable3($grandprevTotal = 0){

	static $counter = 0;
// var_dump('Counter: '.$counter);
// $checkoutOffersTotalPrice    this is total checkout offers price
// $checkoutIncludeHighestPrice this is the highest including price of a checkout offer, this is in the Charges On Offers on admin panel's price tab with name of when_cart_price.
	$checkoutOffersTotalPrice = $checkoutIncludeHighestPrice = 0;
	$two_cart = '';
//view from cart.php from cart table

	global $_e, $productClass, $webClass;
	$userId     = $this->webUserId();
	$tempUser   = $this->webTempUserId();

############ Buy 2 get 1 free START #########
	$has_buy_get_free   = false;
	$buy_get_free_query = "";
	if ( $this->functions->developer_setting("buy_2_get_1_free") == "1" && true === false ) {
		$has_buy_get_free = true;
		$buy_get_free_query = ", (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free') as buy_2_get_1_free
		, (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free_qty') as buy_get_free_apply_limit_qty";
	}
############# Buy 2 get 1 free END ##########


############ 3 For 2 Category START #########
	$three_for_2_ibm_cat = intval( $this->functions->ibms_setting("checkout_two_for_3_category") );
	$three_for_2_cat_div = "3 For 2 Category";
	$three_for_2_qty     = 0;
	$three_for_2_pro_price = array();
	if ( $three_for_2_ibm_cat > 0 ) {
		$three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
	}else{
		$three_for_2_ibm_cat = array();
	}
############ 3 For 2 Category END  ##########


	if($userId=='0'){
		$sql    =   "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `tempUser` = '$tempUser'";
		$data   =   $this->dbF->getRowS($sql);
	}else{
		$sql    =   "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `userId` = '$userId'";
		$data   =   $this->dbF->getRows($sql);
	}

// echo $sql . '<br><br>' . PHP_EOL;

	$temp  = '';
	if( $this->dbF->rowCount>0 ) {
		$i      =   0;

// //Main cart products wrapper div start
// $cart_info = $productClass->cartInfo();
// $shippingInfo   = $this->shippingInfo();
// $shippingType   = $shippingInfo["shippingType"];
// $shippingPriceLimit = $shippingInfo["priceLimit"];
		$currencySymbol =   $this->currentCurrencySymbol();

// $box = $webClass->getBox('box9');

// $temp   =   "   <div class='head_cart wow fadeInDown' >" . $_e['YOUR CART'] . "</div>
//                 <div class='items_cart wow fadeInDown' >" . $cart_info['items'] . ' ' . $_e['ITEM(s)'] . "</div>
//                 <div class='one_cart inline_block wow fadeInLeft' id='cartViewTable' >

//                 <div class='oc_head'>
//                     <div class='oc_heading'>" . $box['heading'] . "</div>
//                     <div class='oc_text'>
//                         " . $box['text'] . "
//                     </div>
//                 </div><!--oc_head end-->
//             ";

		$grandTotal     =   0;
		$totalWeight    =   0;
		$shippingPrice  =   0;
		$pIdsForCheckOutOffer = '';

		$hasScaleVal    =   $this->functions->developer_setting('product_Scale');
		$hasColorVal    =   $this->functions->developer_setting('product_color');

		$hasScale       =   ($hasScaleVal=='1' ? true : false);
		$hasColor       =   ($hasColorVal=='1' ? true : false);
		$couponApply    =   false;
		$country        =   $this->currentCountry();
		$currencyCountry = $this->currentCurrencyCountry();
		$currencyId     =   $this->currentCurrencyId();
$has_free_gift  = false; // it is use to check if any product has free? then dont add other free gift product

$subtotal       = 0;
$sizeModal      = '';

foreach ( $data as $val ) {
	$i++;
// var_dump('Counter is at : '.$i);
	$discountPrice  =   0;
	$discountFormat =   $discountP = '';

$cartId     =   $val['id'];      // Cart table primary ID
$pId        =   $val['pId'];     // Product ID
$scaleId    =   $val['scaleId']; // Product size ID, size=>(small,medium,large)
$colorId    =   $val['colorId']; // Product color ID,
$storeId    =   $val['storeId']; // Product store ID, sale from this store
$qty        =   $val['qty'];     // Product Quantity
$customId   =   $val['customId'];// Product custom size Id, if has
$dealId     =   $val['deal'];    // Product Deal Id, => Get 3 product in 1000 price    // if not it is 0
@$checkout  =   $val['checkout'];// Checkout Product Price, it is on discount from cart page. // if not it is 0
@$info      =   unserialize($val['info']); //product info, use to save any random data on required.


############ Product Categories ##########
$pro_cat    = $this->productF->product_category($pId);


@$shippingId= $val['shippingPrice'];   // Product shipping Price

$shippingPriceData  =   $this->shippingPriceByClass($shippingId,$shippingPrice);
$shippingPriceT     =   $shippingPriceData["price"];
$shippingPrice      =   $shippingPriceData["classPrice"];


if( $customId != '0' ) {
$totalAllowQty = "9999999"; //If product has custom size, then its Stock qty...
}else{
	$totalAllowQty  = $this->productF->productQTY($pId,$storeId,$scaleId,$colorId,true);
}

$pScaleName = $pColorName = '';
$coupon     =   '';

############ Buy 2 get 1 free START #########
$buy_2_get_1_free_div   = "";
$buy_2_get_1_free_input = "";

################# FREE GIFT ############
$free_gift_product_div = "";

if($dealId == '0'){
//Normal Product / checkout product / custom size product

	$pName      = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
	$pNames     = explode(" - ", $pName);
	@$pName1    = $pNames[0];
	@$pScaleName = $pNames[1];
	@$pColorName = $pNames[2];
	$pColorName = str_replace('padding: 5px 12px;', 'padding: 2px 9px;', $pColorName);
	$pName1     = "<a href='".WEB_URL."/detail?pId=$pId'>$pName1</a>";
	$pIdsForCheckOutOffer[$pId] = '1';

	$pPrice     = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);

####################################################################################
################ Buy 2 Product get 1 free START #####################
/*if( $val["buy_2_get_1_free"] == '1' && $has_buy_get_free ){
$buy_get_free_apply_limit_qty       =  empty($val["buy_get_free_apply_limit_qty"]) ? "2" : $val["buy_get_free_apply_limit_qty"]; //limit of product to buy
$class  = "displaynone";
if( $qty >= $buy_get_free_apply_limit_qty ){
$class = "";
}
$free_qty               =  floor($qty/$buy_get_free_apply_limit_qty);
$buy_2_get_1_free_input =  "<input type='hidden' class='hidden buy_2_get_1_free' value='$buy_get_free_apply_limit_qty' data-id='$cartId' data-limit='$buy_get_free_apply_limit_qty' id='buy_2_get_1_free_$cartId'>";
$buy_2_get_1_free_div   =  "<div class='$class buy_get_free_css buy_2_get_1_free_div'>"._replace("{{buy_qty}}",$buy_get_free_apply_limit_qty,$_e["Buy {{buy_qty}} Get 1 free"])."</div>";
$buy_2_get_1_free_div   .=  "<div class='clearfix'></div><div class='$class buy_free_qty buy_get_free_css buy_2_get_1_free_div'>"._replace("{{free_qty}}","<span class='you_get_free_qty'>$free_qty</span>",$_e["You Get +{{free_qty}} free"])."</div>";
}*/
############ Buy 2 get 1 free END #########
####################################################################################


############ //checking if this is checkout offer then checkout discount offer apply
############ //if deal is 0 then checout offer need to check,
if($checkout == '1'){
	$checkoutPrice            =   $this->checkOutProductPrice($pId,$currencyId);
	$checkoutIncludePrice     =   $this->checkOutProductIncludePrice($pId,$currencyId);
	$checkoutIncludeHighestPrice = ( $checkoutIncludePrice > $checkoutIncludeHighestPrice ) ? $checkoutIncludePrice : $checkoutIncludeHighestPrice;
// var_dump('$checkoutIncludeHighestPrice: ' . $checkoutIncludeHighestPrice, '$checkoutIncludePrice: ' . $checkoutIncludePrice);

############################ CONTINUING HERE ############################
	if( $grandprevTotal > 0 && $grandprevTotal < $checkoutIncludePrice ) {
// var_dump('INSIDE',$pId);
		$this->removeCartCheckoutOfferProduct($cartId,$pId);
		continue;
// $temp .= '<h1>ABCDEFG</h1>';
	} 

//add color or scale price
	$p_color_price = $this->productF->colorPrice($colorId,$currencyId,$pId);
	@$p_color_price = $p_color_price['proclr_price'];
	$p_size_price  = $this->productF->scalePrice($scaleId,$currencyId,$pId);
	@$p_size_price = $p_size_price['prosiz_price'] ;
	$checkoutPrice = $checkoutPrice + floatval($p_color_price) + floatval($p_size_price);
	$discountPrice     =   $pPrice-$checkoutPrice;
	@$discountFormat   =   "price";
	@$discountP        =   $discountPrice;
	@$discountTooltip  =   $this->productF->tooltip_div('checkout_offer');
}
############################################################
################# FREE GIFT Check Is product FREE then make it price 0 free ############
else if ($checkout == '2') {
	$has_free_gift      = true;
	$discountPrice      = $pPrice;
	@$discountFormat    = "price";
	@$discountP         = $discountPrice;

	$free_gift_product_div = $this->productF->free_gift_text();
}else{
	$coupon = $this->getCoupon();
	@$couponHas     =   $this->productF->productCouponStatus($coupon);
	if(!$couponHas){
		$_SESSION['webUser']['coupon'] = '';
	}

	$discount       =   $this->productF->productDiscount($pId,$currencyId,$coupon);
//var_dump($discount);
	@$discountFormat=   $discount['discountFormat'];
	@$discountP     =   $discount['discount'];
	@$discountTooltip =   $discount['tooltip'];
	$discountPrice  =   $this->productF->discountPriceCalculation($pPrice,$discount);
} // /$checkout else end

$shippingData   =   $this->productF->productShipping($pId,$currencyId);
$shipping       =   $shippingData['propri_intShipping'];
$pImage         =   $this->productSpecialImage($pId,'main');
$weightSingle   =   $this->productF->getProductWeight($pId,$scaleId,$colorId);

}else{
	$dealData       =   $this->getDealData($dealId);
	$pName1         =   $this->getDealNameWeb($dealData);
	$pName1         =   "<a href='".WEB_URL."/productDeals?deal=$dealId'>$pName1</a>";

	$pPrice         =   $this->getDealPrice($dealData);
	$pIdsForCheckOutOffer = $this->getDealpIds($dealData,$pIdsForCheckOutOffer);

	$totalAllowQty  =   $this->getDealLowestProductQty($info);

	$shipping       =   $this->getDealShippingStatus($info);
	$pImage         =   $dealData['image'];
	$weightSingle   =   $this->getDealWeight($info);
}

$discountPrice      =   round($discountPrice,2);
$newPrice           =   round($pPrice - $discountPrice,2);
$productQtyDiscount =   $discountPrice*$qty;
$totalPrice         =   $newPrice*$qty;
$grandTotal         +=  $totalPrice;


// #### if checkout offer than include its price into $checkoutOffersTotalPrice
$checkoutOffersTotalPrice = ( $checkout == 1 ) ? $checkoutOffersTotalPrice + $totalPrice : $checkoutOffersTotalPrice ;


$weight             =   $weightSingle*$qty;
$totalWeight        +=  $weight;

$sum                =   $qty*$pPrice;

$subtotal           += $sum;

$sumT               =   $this->dbF->hardWords('sum',false);
$weightT            =   $this->dbF->hardWords('Weight',false);
$discountT          =   $this->dbF->hardWords('Discount',false);
$qtyT               =   $this->dbF->hardWords('Quantity',false);
$totalT             =   $this->dbF->hardWords('Total',false);
$Farg               =   $this->dbF->hardWords('Farg',false);
$TotalITaxT         =   $this->dbF->hardWords('Total Including Tax',false);
$sizeT              =   $this->dbF->hardWords('Size',false);
$dealT              =   $this->dbF->hardWords('Deal',false);
$sizeInfo           =   '';
$colorInfo          =   '';
if($hasScale){
	$sizeInfo       = "<label>$sizeT:</label> $pScaleName";
	if($customId    != '0' && !empty($customId) && $scaleId == '0' ){
		$sizeInfo   = "<label>$sizeT:</label> <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a>";
		$customFieldsData   = $this->customSubmitValues($customId);
		$customFields       = $customFieldsData['form'];
		$sizeModal .= $this->functions->blankModal($_e['Custom'],"customSizeInfo_$customId",$customFields,$_e['Close']);
	}
	if($dealId      != '0' && !empty($dealId) && $scaleId == '0' ){
		$sizeInfo   = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>".$dealT." ".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
		$customFields = $this->dealSubmitPackage($info);
		$sizeModal  .= $this->functions->blankModal($_e['Custom'],"dealInfo_$dealId",$customFields,$_e['Close']);
	}
}

if($hasColor && $pColorName){
	$colorInfo = "<label>$Farg:</label> $pColorName";
}

//if deal then color or scale make blank
if($dealId!='0'){
//$sizeInfo = '';
	$colorInfo = '';
}

$stockCheck = $this->functions->developer_setting('product_check_stock');
if($stockCheck=='0'){
	$totalAllowQty = 9999999;
}

if($this->functions->developer_setting('addQty_custome')=='1') {
	$addQty_custome = "<div class='addByQtyDiv reload_btn'>
	<input type='number' min='1' max='$totalAllowQty' data-prev='$qty' value='$qty' pattern='[0-9]{1,5}' class='addByQty_$cartId' />
	</div>
	<div class='addByQtyBtn' onclick='addByQty(this,$cartId)'><i class='glyphicon glyphicon-refresh'></i></div>";
}else{
	$addQty_custome = "<div class='cartPlus'  onclick='addPlusToCart(this,$cartId)'>+</div>
	<div class='cartMinus' onclick='minusFromCart(this,$cartId)'>-</div>";
}

######################### FREE GIFT #################
//If free gift then add more qty option hide.
if($checkout == "2" ){
	$addQty_custome     = "";
}
######################### FREE GIFT #################


##################### check if product category in 3 For 2 Category START ##############
$three_for_2_category = "";
if ( sizeof ( array_intersect($three_for_2_ibm_cat, $pro_cat ) ) > 0 && $newPrice > 0) {
	$three_for_2_pro_price[$cartId]["id"] = $pId;
	$three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
	$three_for_2_pro_price[$cartId]["qty"] = $qty;
	$three_for_2_qty += $qty;
	$three_for_2_category = " <img src='".WEB_URL."/images/3for2.jpg' height='40' />";
}
#################### check if product category in 3 For 2 Category END ###############



### if grandprevTotal is given (gt 0), means we are in recursive function call, check $checkoutIncludePrice with previous $grandprevTotal price and remove checkout offer if price is greater than grandprevTotal, using continue to just go up and not include this offer.

// if( $grandprevTotal > 0 && $grandprevTotal < $checkoutIncludePrice ) {
//     // var_dump('INSIDE',$grandprevTotal,$checkoutIncludePrice);
//     $temp .= '<h1>ABCDEFG</h1>';
// } else { 

// Product HTML to display in cart.php
$temp .=    " 
<div class=' {$counter} detail_cart wow fadeInLeft cart_product_wrapper cart_product_{$cartId} {$cartId}' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle' >

<div class='img_detail  inline_block'><a href='".WEB_URL."/detail?pId={$pId}'><img src='images/$pImage' alt='{$pNames[0]}'></a></div>
<div class='info_cart inline_block'>

<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
<input type='hidden' class='hidden' value='$totalAllowQty' id='productTotalQty_$cartId'>
<input type='hidden' class='hidden shippingClass' value='$shippingPriceT' id='shippingClass_$cartId'>
<input type='hidden' class='hidden' value='$stockCheck' id='stockCheck_$cartId'>
$buy_2_get_1_free_input

<div class='info_head'> {$pName1} {$three_for_2_category} </div>
<div class='info_text'> {$sizeInfo} </div>
<div class='info_text'> {$colorInfo} </div>
<div class='info_text'><label> {$qtyT}: </label> <span class='pQty'>{$qty}</span> @ <span class='pPrice'>{$newPrice}</span> {$currencySymbol}  </div>
$buy_2_get_1_free_div
$free_gift_product_div
<div class='clearfix'></div>

<input type='submit' value='" . $_e['REMOVE'] . "' class='info_btn' onclick='cartProductRemove(this,$cartId)'>
{$addQty_custome}
<!-- <input type='submit' value='" . $_e['EDIT'] . "' class='info_btn'> -->
</div><!--info_cart end-->

<div class='rate_detail inline_block wow fadeInLeft'><span class='pTotalPrice'>{$totalPrice}</span> {$currencySymbol}
" . $discountTooltip . "
</div>
</div><!--detail_cart end-->";
// }
}


$temp .= "

</div><!--one_cart end-->";



############ FREE GIFT ADD START #################
// When new free gift add in cart, reload whole page. to show this free product in cart list.

if ( $this->functions->developer_setting("add_free_gift_in_cart") == "1" && !$has_free_gift) {
	$this->free_gift_add($grandTotal, $pIdsForCheckOutOffer);
}
############ FREE GIFT ADD END  #################


############ CHECK OUT OFFER #################
//get checkout offers, and show on submit page...
$checkoutOnClick    = $checkoutModelClick= '';
$checkoutOffers = '';
if(!isset($_GET['checkout'])) {
	$checkoutOffers = $this->checkOutOffer($grandTotal, $pIdsForCheckOutOffer);
	if (!empty($checkoutOffers)) {
//$temp .=$this->functions->blankModal($_e["Check Out our Offer Now & Get Special Discount"],"checkoutOffer",$checkoutOffers,$_e['Close'],'',true,5000);
		$checkoutOnClick    = " onsubmit='return checkOutOffer();'";
		$checkoutModelClick = " data-toggle='modal' data-target='#checkoutOffer' ";
	}
}
############ CHECK OUT OFFER #################

$country_list   =   $this->functions->countrylist();
$storeCountry   =   $this->currentCountry();
$countryName    =   $country_list[$storeCountry];
$token          =   $this->functions->setFormToken('WebOrderReadyForCheckOut',false);
$countryT       =   $this->dbF->hardWords('Country',false);
$ShippingT      =   $this->dbF->hardWords('Shipping Price',false);
$FreeT          =   $this->dbF->hardWords('FREE',false);
$TotalWeightT   =   $this->dbF->hardWords('Total Weight',false);
$KlarnaFCheckOut=   $this->dbF->hardWords('Klarna Fast CheckOut',false);
$GrandTotal     =   $this->dbF->hardWords('Grand Total',false);
$LoginContinue  =   $this->dbF->hardWords('Login To Continue',false);
$Continue       =   $this->dbF->hardWords('Continue',false);
$ContinueGuest  =   $this->dbF->hardWords('Checkout as Guest',false);

$price_cal  = $this->functions->product_tax_cal($grandTotal,25);
$tax        = $price_cal['tax_price'];
// $tax        = $grandTotal*25/100;
// $tax        = round($tax,2);

$shippingInfo   = $this->shippingInfo();
$shippingType   = $shippingInfo["shippingType"];
$shippingPriceLimit = $shippingInfo["priceLimit"];

if($grandTotal > $shippingPriceLimit){
	$shippingPrice = 0;
}

if($this->functions->developer_setting('shipping_class')=='1' && $shippingType == 'class') {
	$grandTotal = ($grandTotal+$shippingPrice);
}else{
	$shippingPrice = 0;
}


$giftCardSystemData = $this->giftCardSystem($grandTotal);
$giftCard_payPrice = 0;
if(isset($giftCardSystemData['payPrice'])) {
	$giftCard_payPrice = $giftCardSystemData['payPrice'];
}

$jsInfo     =   "
<input type='hidden' class='shippingPrice' value='$shippingPrice'/>
<input type='hidden' class='shippingLimit' value='$shippingPriceLimit'>
<input type='hidden' class='shippingType'  value='$shippingType'>
";

//<div class='col-sm-12 lead'>$ShippingT : $FreeT</div>


// Coupon Code
$couponDiv = $giftCardDiv = '';
// $couponDiv .= "<div class='col-sm-6 col-xs-12 padding-0'>";
$couponDiv .= $this->couponSystem();
$giftCardDiv .= $giftCardSystemData['form'];
// $couponDiv .= "</div>";

//detuct giftPrice from grand Total/
$giftCard   = '';
if($giftCard_payPrice > 0){
	$grandTotal     = $grandTotal-$giftCard_payPrice;
	$giftCard    = "<div class='productSum'>
	<div class=''>".$this->dbF->hardWords('Gift Card Price',false)."</div>
	<div class='priceTemp'>
	( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
	</div>
	</div>
	";
}

$checkoutButton = $loginButton = '';
$login          =   $this->webClass->userLoginCheck();
$loginForOrder  =   $this->functions->developer_setting('loginForOrder');

if(!$login && $loginForOrder=='1'){
	$checkoutButton .= ' <a href="'.WEB_URL.'/login" class="checkout btn themeButton">'.$LoginContinue.'</a>';
}else{
// if not logged in then show login button, this project has loginfororder = 0
	if(!$login) {
		$loginButton = '<div class="sub_box"> <a href="'.WEB_URL.'/login" class="checkout btn ">'.$LoginContinue.'</a> </div><!--sub_box end-->';
		$checkoutButton .=  '<button type="submit"  value="continue"  class="checkout btn cartSubmit1">'.$ContinueGuest.'</button>';
	} else {
# Logged in, show proceed
		$checkoutButton .=  '<button type="submit"  value="continue"  class="checkout btn cartSubmit1">'.$Continue.'</button>';
	}
}

############ 3 For 2 Category START #########
$three_for_2_qty = floor($three_for_2_qty/3);
$three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price,$three_for_2_qty);
$grandTotal = $grandTotal-$three_for_2_minus_price;
$three_for_2_cat_div = '';
if($three_for_2_minus_price > 0){
	$three_for_2_cat_div = "<div class='tc_line'></div>
	<div class='sub_box'>
	<div class='sub_1'>" . $_e['Three For Two Category'] . " </div>
	<div class='sub_2'>({$three_for_2_minus_price}) {$currencySymbol}</div>
	</div>
	<!--sub_box end-->";
}
############ 3 For 2 Category END #########

$two_cart .= "
<div class='two_cart inline_block wow fadeInUp' >

<form $checkoutOnClick  action='orderInvoice.php' method='post'>
$token
$jsInfo

<input type='hidden'  name='shippingWidget' value='$storeCountry'/>
<input type='hidden' readonly id='shippingWidget' required value='$countryName' class='form-control'/>
<div class='summary'>" . $_e['SUMMARY'] . "</div>

{$couponDiv}

{$giftCardDiv}

<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['SUBTOTAL'] . " </div>
<div class='sub_2'>{$subtotal} {$currencySymbol}</div>
</div><!--sub_box end-->

{$three_for_2_cat_div}
<!--sub_box
<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1'>" . $_e['ESTIMATED DELIVERY & HANDLING'] . "</div>
<div class='sub_2'><span class='pShippingPriceTemp' data-real='$shippingPrice'>$shippingPrice</span> {$currencySymbol}</div>
</div> end-->

<div class='tc_line'></div>
<div class='sub_box'>
<div class='sub_1 sub_font'>" . $_e['TOTAL'] . "</div>
<div class='sub_2 sub_font'><span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol</div>
</div><!--sub_box end-->

<div class='tc_line'></div>
{$loginButton}
<div class='sub_box'>
{$checkoutButton}
</div><!--sub_box end-->

<div class='tc_line'></div>";
$shipping_div = '';
if($shippingPrice == 0){
# free shipping last div in cart page at bottom right
	$box = $webClass->getBox('box13');
	$shipping_div = $box['text'];

	$shipping_div = "
	<div class='sub_box'>
	</div><!--sub_box end-->";
}

$two_cart .= " $shipping_div ";

$two_cart .= "
</form></div><!--two_cart end-->";


//Main cart products wrapper div start
$cart_info = $productClass->cartInfo();
$shippingInfo   = $this->shippingInfo();
$shippingType   = $shippingInfo["shippingType"];
$shippingPriceLimit = $shippingInfo["priceLimit"];
// $currencySymbol =   $this->currentCurrencySymbol();

$box = $webClass->getBox('box9');

$temp   =   "   <div class='head_cart wow fadeInDown' >" . $_e['YOUR CART'] . "</div>
<div class='items_cart wow fadeInDown' >" . $cart_info['items'] . ' ' . $_e['ITEM(s)'] . "</div>
<div class='one_cart inline_block wow fadeInLeft' id='cartViewTable' >


" . $temp;
### if there are products in cart only then include the right side prices and continue buttons.
if ( $cart_info['items'] > 0 ) {
	$temp = $temp . $two_cart;
} else {
# if no products in cart then set cart to false
	$temp = false;
}


}else{
	$temp  =   false;
}

// if ( $checkoutIncludePrice ) {
// var_dump('Grand Total: '.$grandTotal,'checkoutIncludeHighestPrice: '.$checkoutIncludeHighestPrice,'checkoutIncludePrice: '.$checkoutIncludePrice,'checkoutOffersTotalPrice: '.$checkoutOffersTotalPrice);
// }

#### if inside recursive function than return, need to verify if I need this or not.
// if ($counter == 1) {
// return array("cart"=>$temp,"offer"=>@$checkoutOffers, "sizeModal"=>@$sizeModal);
// }

################################  RECURSIVE FUNC CALL  ##########################################
### if price to include checkout offer is 500 and grand total is less than that, also checking static counter, we are running this function once again with grandTotal - $checkoutOffersTotalPrice in it, to check each checkout offers including price with that of grandTotal - $checkoutOffersTotalPrice to avoid checkout offer misuse, $checkoutOffersTotalPrice is the total price of all the checkout offers.
if ( isset($grandTotal) && $checkoutIncludeHighestPrice > ( $grandTotal - $checkoutOffersTotalPrice ) && $counter == 0 ) {
// var_dump('Grand Prev Total'.$grandprevTotal);
	$counter++;
	$new_array = $this->viewCartTable3($grandTotal - $checkoutOffersTotalPrice);
	$temp = $new_array['cart'];
	$checkoutOffers = $new_array['offer'];
	$sizeModal = $new_array['sizeModal'];
}


return array("cart"=>$temp,"offer"=>@$checkoutOffers, "sizeModal"=>@$sizeModal);
}


private function three_for_2_category_rec( $three_for_2_pro_array, $three_for_2_qty ){
	$small_price = 0;
	$three_for_2_pro = 0;
	$three_for_2_array_qty = 0;
	$three_for_2_array_key = 0;
	$total_min = 0;
	foreach( $three_for_2_pro_array as $key => $val ){
		if ( $val["price"] < $small_price || $small_price === 0 ){
			$small_price        = $val["price"];
			$three_for_2_pro    = $val['id'];
			$three_for_2_array_qty        = intval($val['qty']);
			$three_for_2_array_key        = $key;
		}
	}


	$temp            = $three_for_2_qty;
	$three_for_2_qty = $three_for_2_qty-$three_for_2_array_qty;
	if( $three_for_2_qty > 0 ){
		$small_price = ($small_price * $three_for_2_array_qty );
		$total_min   += $small_price;
	}else{
		$small_price = ($small_price*$temp);
		$total_min += $small_price;
	}

	if($three_for_2_qty > 0){
		unset ( $three_for_2_pro_array[$three_for_2_array_key] );
		$total_min += $this->three_for_2_category_rec( $three_for_2_pro_array, $three_for_2_qty );
	}

	return $total_min;
}

public function proCategories($id = false,$limit = false){
	$filter = '';
	if($limit){
		$filter = " LIMIT $limit";
	}
	$sql = "SELECT `procat_cat_id` FROM `product_category` WHERE `procat_prodet_id` = ? $filter";
	$res = $this->dbF->getRow($sql, array($id));

	$catString = rtrim($res['procat_cat_id'], ',');
	$categor = explode(',', $catString);

	$proCat = array();

	foreach ($categor as $key => $value) {
		$sql1 = "SELECT `name` FROM `categories` WHERE `id` = ?";
		$res1 = $this->dbF->getRow($sql1, array($value));

		$cat_name = translateFromSerialize($res1['name']);
		$proCat[$value]   = $cat_name;
// $proCat['id'][]   = $res1['name'];
// $proCat['name'][] = $cat_name;
	}
// var_dump($proCat);
	return $proCat;
}

private function getCoupon(){
	$coupon = '';

	if (isset($_GET['coupon']) && $_GET['coupon'] != '') {
		$coupon = $_GET['coupon'];
		$_SESSION['webUser']['coupon'] = $coupon;
	} else if (isset($_SESSION['webUser']['coupon'])) {
		$coupon = $_SESSION['webUser']['coupon'];
	} else {
		$_SESSION['webUser']['coupon'] = $coupon;
	}

	if (isset($_GET['coupon']) && $_GET['coupon'] == 'remove') {
		$_SESSION['webUser']['coupon'] = '';
		$coupon = '';
	}
	return $coupon;
}

private function couponSystem()
{
	$removeCoupon = $temp = '';
	$coupon = $this->getCoupon();
	$couponApply = false;
	if (!empty($coupon)) {
		$couponApply = true;
	}
	@$couponHas = $this->productF->productCouponStatus($coupon);

	$couponStatus = $this->dbF->hardWords('Discount Code', false);
	if ($couponApply) {
		if ($couponHas) {
			$couponApplyT = $this->dbF->hardWords('Discount code apply', false);
			$couponStatus = "<div class='alert alert-success '>$couponApplyT</div>";

			$couponApplyT = $this->dbF->hardWords('Remove Coupon', false);
			$removeCoupon = '<div class="clearfix margin-5"></div><a href="cart?coupon=remove" class="btn-danger btn-sm">' . $couponApplyT . '</a>';
		} else {
			$coupon = '';
			$couponApplyT = $this->dbF->hardWords('Code Not Found or expired', false);
			$couponStatus = "<div class='alert alert-danger'>$couponApplyT</div>";
		}
	}

	if ($this->functions->developer_setting('couponSystem') == '1') {
		$couponT = $this->dbF->hardWords('Coupon', false);
		$checkT = $this->dbF->hardWords('Check', false);
		$checkT = $this->dbF->hardWords('Apply', false);

######### For old Cart two, bootstrap #######
		$work_with_old_cart2 = "
		<!-- Coupon Code -->
		<div class='tc_line for_cart3 displaynone'></div>
		<div class='col-xs-12 text-center couponCode promo-code'>
		<div class='col-sm-12 margin-5'>
		$couponStatus
		</div>
		<div class='col-sm-12 form-horizontal'>
		<div class='form-group'>
		<label class='col-sm-3 control-label'>$couponT</label>
		<div class='col-sm-8 input-group' >
		<input type='text' id='couponCode' value='$coupon' class='form-control' />
		<div class='input-group-addon' onclick='applyCoupon();' style='cursor: pointer; '>$checkT</div>
		</div>
		$removeCoupon
		</div>
		</div>
		</div>
		<div class='clearfix'></div>
		<!-- Coupon Code End -->";

		$temp = "
		<!-- Coupon Code -->
		<div class='tc_line'></div>
		<div class='promo-code'> 
		<div class='pc_text'>{$couponStatus}</div>
		<input type='text' id='couponCode' value='' class='pc_field'>
		<input type='button' onclick='applyCoupon();' value='" . $checkT . "' class='apply'>
		$removeCoupon
		</div><!--promo-code end-->
		<!-- Coupon Code End -->";


	}
	return $temp;
}

private function getGiftCardId()
{
	$giftCard = '';
	if (isset($_GET['giftCard']) && $_GET['giftCard'] != '') {
		$giftCard = $_GET['giftCard'];
		$_SESSION['webUser']['giftCard'] = $giftCard;
	} else if (isset($_SESSION['webUser']['giftCard'])) {
		$giftCard = $_SESSION['webUser']['giftCard'];
	} else {
		$_SESSION['webUser']['giftCard'] = $giftCard;
	}

	if (isset($_GET['giftCard']) && $_GET['giftCard'] == 'remove') {
		$_SESSION['webUser']['giftCard'] = '';
		$giftCard = '';
	}

	return $giftCard;
}

private function giftCardSystem($cartPrice)
{
	$removeGiftCard = $temp = '';
	$giftId = $this->getGiftCardId();

	$giftCardStatus = $this->dbF->hardWords('Gift Card Id', false);
	$giftCardData = $this->giftCardCheck($cartPrice);
	$giftCardStatus2 = $giftCardData['msg'];
	if (!empty($giftCardStatus2)) {
		$giftCardStatus = $giftCardStatus2;
		$giftCardStatus .= "<input type='hidden' class='giftCard_giftPrice_input' value='$giftCardData[giftPrice]' />";
		$giftCardStatus .= "<input type='hidden' class='giftCard_payPrice_input' value='$giftCardData[payPrice]' />";


		$tempT = $this->dbF->hardWords('Remove GiftCard', false);
		$removeGiftCard = '<div class="clearfix margin-5"></div><a href="cart?giftCard=remove" class="btn-danger btn-sm">' . $tempT . '</a>';

	}

	if ($this->functions->developer_setting('giftCard') == '1') {
		$giftCardT = $this->dbF->hardWords('Gift Card', false);
		$checkT = $this->dbF->hardWords('Check', false);
		$checkT = $this->dbF->hardWords('Apply', false);

######### That was use for old cart two, bootstrap  ######
		$temp['form'] = "
		<!-- Gift Card -->
		<div class='tc_line for_cart3 displaynone'></div>
		<div class='col-sm-12 text-center giftCard_formDiv'>
		<div class='col-xs-12'>
		<div class='col-sm-12 margin-0'>
		$giftCardStatus
		</div>
		<div class='col-sm-12 form-horizontal padding-0'>
		<div class='form-group'>
		<label class='col-sm-3 control-label'>$giftCardT</label>
		<div class='col-sm-8 input-group' >
		<input type='text' id='applyGiftCard' value='$giftId' class='form-control' />
		<div class='input-group-addon' onclick='applyGiftCard();' style='cursor: pointer; '>$checkT</div>
		</div>
		$removeGiftCard
		</div>
		</div>
		</div>
		</div>
		<div class='clearfix'></div>
		<!-- Gift Card End -->";



		$temp['form'] = "
		<!-- Gift Card -->
		<div class='tc_line'></div>
		<div class='promo-code'>
		<div class='pc_text'>{$giftCardStatus}</div>
		<input type='text' id='applyGiftCard' value='$giftId' class='pc_field'>
		<input type='button' onclick='applyGiftCardd();' value='" . $checkT . "' class='apply'>
		$removeGiftCard
		</div><!--promo-code end-->
		<!-- Gift Card End -->

		";
	}
	return array_merge($temp, $giftCardData);
}

public function giftCardCheck($cartPrice)
{
	global $_e;
//first check is giftid ok, then check is gift card price greater then cart price,,

	$giftId = $this->getGiftCardId();

	$array['giftPrice'] = 0;
	$array['giftCardId'] = $giftId;
	$array['payPrice'] = 0;
	$array['msg'] = '';
	$array['info'] = '';
//$array['error']    = true; //error !isset if, if condition not execute
	if (!empty($giftId)) {
		$giftId = removeSpace($giftId);
		$giftId = str_replace(" ", "", $giftId);
		$giftId = str_replace("-", "", $giftId);

		$error = false;
		$errormsg = '';

		$sql = "SELECT * FROM gift_card WHERE publish = '1' AND sale='1' AND giftId = ?";
		$data = $this->dbF->getRow($sql, array($giftId));
		if ( empty($data) && !$error || $data['usePrice'] > $data['price'] ) {
			$errormsg = $_e["Gift Card Id is Not Valid. Note: It is Case Sensitive."];
			$error = true;
		}
		$array['info'] = $data['info'];

		$giftCardPrice = floatval($data['price']);
		$giftUsePrice = floatval($data['usePrice']);
		$giftCurrency = $data['currency'];
		$giftPrice = $giftCardPrice - $giftUsePrice;

//now check if giftcard price is greater then cart price

//check gift card Currency..
		$cartCurrency = $this->currentCurrencySymbol();
		if ($cartCurrency != $giftCurrency && !$error) {
			$errormsg = _replace("{{giftCurrency}}", $giftCurrency, $_e["Your Gift card in ( {{giftCurrency}} ) currency, and not valid for ( {{cartCurrency}} ) currency"]);
			$errormsg = _replace("{{cartCurrency}}", $cartCurrency, $errormsg);
			$error = true;
		}

//check giftcard and cart price
		if ($cartPrice > $giftPrice && !$error) {
			$cartPrice = $giftPrice;
/*$errormsg     = _replace("{{giftPrice}}","$giftPrice $cartCurrency",$_e["You have low price in you Gift card :{{giftPrice}}"]);
$error          = true;*/
}


if ($error) {
	$array['msg'] = "<div class='alert alert-danger margin-5'>$errormsg</div>";
} else {
	$array['giftPrice'] = $giftPrice;
	$array['payPrice'] = $cartPrice;

	$errormsg = _replace("{{giftId}}", $giftId, $_e['Your Gift card Id "{{giftId}}"  will be charged {{cartPrice}} from {{giftPrice}}']);
	$errormsg = _replace("{{cartPrice}}", "<span class='giftcardPrice_span_payPrice'>$cartPrice</span> $giftCurrency", $errormsg);
	$errormsg = _replace("{{giftPrice}}", "<span class='giftcardPrice_span_giftPrice'>$giftPrice</span> $giftCurrency", $errormsg);

	if ($giftPrice <= 0) {
		$error = true;
		$array['msg'] = "<div class='alert alert-danger  margin-5'>$errormsg</div>";
	} else {
		$array['msg'] = "<div class='alert alert-success margin-5'>$errormsg</div>";
	}

//now every thing is ok,, send to cardcontinue page for submit form..
}

$array['error'] = $error;
}

return $array;
}

public function openPaypal($PaymentType, $invoiceId)
{
	if ($PaymentType === '1' && $PaymentType !== false) {
//Means User select PayPal Payment type;
		header("Location:" . WEB_URL . "/src/paypal/paypal.php?inv=$invoiceId");
		exit;
	} else {
		return false;
	}
}

public function openPayson($PaymentType, $invoiceId)
{
	if ($PaymentType === '5' && $PaymentType !== false) {
//Means User select PayPal Payment type;
		header("Location:" . WEB_URL . "/src/payson/main.php?inv=$invoiceId");
		exit;
	} else {
		return false;
	}
}

public function checkGiftCardid($giftId, $invoiceId, $userId, $updatePrice = true)
{
	$giftId = removeSpace($giftId);
	$giftId = str_replace(" ", "", $giftId);
	$giftId = str_replace("-", "", $giftId);

	$sql = "SELECT * FROM gift_card WHERE publish = '1' AND sale='1' AND giftId = ?";
	$data = $this->dbF->getRow($sql, array($giftId));
	if (empty($data)) {
		$errormsg = "Gift Card Id \"$giftId\" is Not Valid. Note: It is Case Sensitive.";
		return false;
	}

	$giftCardPrice = floatval($data['price']);
	$giftUsePrice = floatval($data['usePrice']);
	$giftCurrency = $data['currency'];
	$giftPrice = $giftCardPrice - $giftUsePrice;

//now check if giftcard price is greater then cart price
	$sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$invoiceId' AND orderUser = '$userId'";
	$orderInvoice = $this->dbF->getRow($sql);
	if (empty($orderInvoice)) {
		$errormsg = "Cart Error.";
		return false;
	}

	$cartPrice = floatval($orderInvoice['total_price']);
	$cartCurrency = $orderInvoice['price_code'];

//check gift card Currency..
	if ($cartCurrency != $giftCurrency) {
		$errormsg = "Your Gift card in ( $giftCurrency ) currency, and not valid for ( $cartCurrency ) currency";
		return false;
	}

//check giftcard and cart price
	if ($cartPrice > $giftPrice) {
		$errormsg = "You have low price in you Gift card :$giftPrice $cartCurrency";
		return false;
	}

//now every thing is ok,,
	if ($updatePrice) {
		$this->updateGiftCard($giftId, $cartPrice, $invoiceId, $data['info']);
	}

	return true;
}

public function updateGiftCard($giftId, $price, $orderId, $oldInfo)
{
	$orderId = "GiftCard \"$giftId\" Use In :\nOrder Id : $orderId; Price:$price \n ____ \n";
	if (empty($oldInfo)) {
		$info = " info = '$orderId'";
	} else {
		$info = " info = CONCAT(info,'$orderId')";
	}
	$sql = "UPDATE gift_card SET
	usePrice = usePrice+'$price',
	$info
	WHERE giftId = ?";

	$giftId = preg_replace("/-| /", "", $giftId);

	$this->dbF->setRow($sql, array($giftId));
	return $orderId;
}

public $cartInvoice = false;
public function viewCheckOutProduct($id)
{
	global $_e;

	$orderUser = $this->webUserId();
	if ($orderUser == '0') {
		$orderUser = $this->webTempUserId();
	}
	$sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id' AND orderUser = '$orderUser'";
	$orderInvoice = $this->dbF->getRow($sql);
	if (!$this->dbF->rowCount) {
		return false;
	}

	$sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
	$orderProducts = $this->dbF->getRows($sql);
	if ($orderInvoice['orderStatus'] == 'process') {
		$submitSuccessT = $this->dbF->hardWords('Your Order Submit SuccessFully', false);
		$msg = "<br><div class='well alert alert-success h4 text-center'>$submitSuccessT </div>";

		if ($this->functions->developer_setting('invoice_print_after_Checkout') == '1'){
			$print_link = WEB_URL . "/invoicePrint?mailId=$id&orderId=" . $this->functions->encode($id);
			header("Location: $print_link");
			exit;
		}

		$msg .= "
		<script>
		$(document).ready(function(){
			setTimeout(function(){location.replace('viewOrder?success')},2000);
			});
			</script>
			";
			return $msg;
		}

		if ($orderInvoice['orderStatus'] == 'pendingPaypal' || $orderInvoice['paymentType'] == '1') {
//redirect to paypal page for payment
			$this->openPaypal($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
		} else if ($orderInvoice['orderStatus'] == 'pendingPayson' || $orderInvoice['paymentType'] == '5') {
//redirect to payson page for payment
			$this->openPayson($orderInvoice['paymentType'], $orderInvoice['order_invoice_pk']);
		}

		$temp = '';
		if ($this->dbF->rowCount > 0) {
			$i = 0;

			$temp = "<div class='container-fluid table-responsive' id='cartViewTable'>";

			$grandTotal = 0;
			$totalWeight = 0;

			$hasScaleVal = $this->functions->developer_setting('product_Scale');
			$hasColorVal = $this->functions->developer_setting('product_color');

			$hasScale = ($hasScaleVal == '1' ? true : false);
			$hasColor = ($hasColorVal == '1' ? true : false);

			$currencyCountry = $this->currentCurrencyCountry();
			$country = $this->currentCountry();
			$currencyId = $this->currentCurrencyId();

			foreach ($orderProducts as $val) {
				$this->cartInvoice = true;
				$i++;
				$cartId = $val['invoice_product_pk'];
//Product hash
				$pIds   = $val['order_pIds'];
$pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
$pId    = $pArray[0]; // 491
$scaleId = $pArray[1]; // 426
$colorId = $pArray[2]; // 435
$storeId = $pArray[3]; // 5
@$customId = $pArray[4]; // 5
$dealId = $val['deal']; // if not it is 0
@$info  = unserialize($val['info']);
if (empty($customId)) {
	$customId = 0;
}
$qty    = $val['order_pQty'];

if ($dealId == '0') {
	$pImage = $this->productSpecialImage($pId, 'main');
} else {
	$dealData = $this->getDealData($dealId);
	$pImage = $dealData['image'];
}
$pName      = $val['order_pName'];
$pNames     = explode(" - ", $pName);
@$pName1    = $pNames[0];
@$pScaleName = $pNames[1];
@$pColorName = $pNames[2];

//$pPrice   =   $this->productF->productTotalPrice($pId,$scaleId,$colorId,$currencyCountry,false);
$pPrice     = $val['order_salePrice'];
$discount   = $this->productF->productDiscount($pId, $currencyId);
@$discountFormat = $discount['discountFormat'];
@$discountP = $discount['discount'];
/*
$discountPrice  =   $this->productF->discountPriceCalculation($pPrice,$discount);
$discountPrice  =   round($discountPrice,2);
*/
$discountPrice = $val['order_discount'];
$newPrice   = $pPrice - $discountPrice;
$productQtyDiscount = $discountPrice * $qty;
$totalPrice = $newPrice * $qty;
$grandTotal += $totalPrice;
$currencySymbol = $this->currentCurrencySymbol();

$shippingData = $this->productF->productShipping($pId, $currencyId);
$shipping   = $shippingData['propri_intShipping'];

$weightSingle = $val['order_pWeight'];
$weight     = $weightSingle;
$totalWeight += $weight;

$sum        = $qty * $pPrice;

$sumT       = $this->dbF->hardWords('sum', false);
$weightT    = $this->dbF->hardWords('Weight', false);
$discountT  = $this->dbF->hardWords('Discount', false);
$qtyT       = $this->dbF->hardWords('Quantity', false);
$totalT     = $this->dbF->hardWords('Total', false);
$Farg       = $this->dbF->hardWords('Farg', false);
$sizeT      = $this->dbF->hardWords('Size', false);
$dealT      = $this->dbF->hardWords('Deal', false);

$sizeInfo   = '';
$colorInfo  = '';
if ($hasScale) {
	$sizeInfo = "<div>$sizeT# : $pScaleName</div>";
	if ($customId != '0' && !empty($customId)) {
		$sizeInfo = "<div>$sizeT# : <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>" . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
		$customFieldsData = $this->customSubmitValues($customId);
		$customFields = $customFieldsData['form'];
		$sizeInfo .= $this->functions->blankModal($_e['Custom'], "customSizeInfo_$customId", $customFields, $_e['Close']);
	}
	if ($dealId != '0' && !empty($dealId) && $scaleId == '0') {
		$sizeInfo = "<div><a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>" . $dealT . " " . $_e['Custom'] . " <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
		$customFields = $this->dealSubmitPackage($info, false);
		$sizeInfo .= $this->functions->blankModal($_e['Custom'], "dealInfo_$dealId", $customFields, $_e['Close']);
	}
}
if ($hasColor) {
	$colorInfo = "<div>$Farg : $pColorName</div>";
}

############ /* Buy 2 get 1 free start */
$buy_2_get_1_free_div = $this->productF->buy_get_free_invoice_div($id,$cartId);
############ /* Buy 2 get 1 free end */


############ FREE GIFT TEXT #############
$free_gift_product_div = "";
if($totalPrice == "0" && $sum == $productQtyDiscount) {
	$free_gift_product_div = $this->productF->free_gift_text();
}
############ FREE GIFT TEXT #############


//if deal then color or scale make blank
if ($dealId != '0') {
//$sizeInfo = '';
	$colorInfo = '';
}

$temp .= "
<div class='container-fluid padding-0 padding-bottom border $cartId' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
<div class='col-md-1 col-sm-1 padding-0 serialTd'>
<div class='cartSerial'>$i.</div>
<input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
<input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
<input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
<input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
<input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
</div>
<div class='col-md-3 col-sm-3 padding-0 col-xs-12 text-center-xs imgTd'><img src='images/$pImage'/></div>
<div class='col-md-2 col-sm-2 col-xs-12 text-center-xs padding-0 nameTd'>
<div class=''>
<div>$pName1</div>
<div>$sizeInfo</div>
<div>$colorInfo</div>
$buy_2_get_1_free_div
$free_gift_product_div
</div>
</div>
<div class='col-md-6 col-sm-6 padding-0 col-xs-12 priceTd'>
<div class='sumMainDiv'>
<div class='qtyDiv'>
<div>$qtyT</div>
<div class='productTotalQ'>
<div class='productQty'><span class='pQty'>$qty</span></div>
<div>X</div>
<div class='productPrice'><span class='pPrice'>$pPrice</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$sumT</div>
<div class='priceTemp'><span class='sumProduct'>$sum</span> $currencySymbol</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$weightT</div>
<div class='priceTemp'><span class='pWeight'>$weight </span> KG</div>
</div>
</div>
<div>
<div class='productSum'>
<div class=''>$discountT</div>
<div class='priceTemp'><span class='pDiscount'>$productQtyDiscount</span> $currencySymbol</div>
</div>
</div>

<div class='productTotal'>
<div class='productSum'>
<div class=''>$totalT</div>
<div class='priceTemp'><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</div>
</div>
</div>
</div>
</div>
</div>
";
}

$country_list = $this->functions->countrylist();
$storeCountry = $orderInvoice['shippingCountry'];
$countryName = $country_list[$storeCountry];
$shipPrice = $orderInvoice['ship_price'];
$orderTotal = $orderInvoice['total_price'];
$countryT = $this->dbF->hardWords('Country', false);

$shipPriceT = $this->dbF->hardWords('Shipping Price', false);
$totalWeightT = $this->dbF->hardWords('Total Weight', false);
$grandTotalT = $this->dbF->hardWords('Grand Total', false);

$grandTotal = ($grandTotal + $shipPrice);


$price_cal = $this->functions->product_tax_cal($grandTotal, 25);
$tax = $price_cal['tax_price'];

$giftCardDiv = '';
$recordData = $this->productF->get_order_invoice_record($id, 'giftCard');
if ($recordData != false) {
	$giftCardId = $recordData['setting_val'];
	$giftCardNewPriceData = $this->giftCardNewPrice($giftCardId, $grandTotal);
	$grandTotal = $giftCardNewPriceData['total'];
	$giftCard_payPrice = $giftCardNewPriceData['payPrice'];
	$giftCardDiv = "<div class='productSum'>
	<div class=''>" . $this->dbF->hardWords('Gift Card Price', false) . "</div>
	<div class='priceTemp'>
	( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
	</div>
	</div>";
}

$temp .= "
</div>
<br>
<div class='shippingWight col-sm-12'>
<div class='col-sm-6 text-center'>
<div class='col-sm-12 lead'>$shipPriceT : <span class='pShippingPriceTemp' data-real='$shipPrice'> $shipPrice </span> $currencySymbol</div>
<div class='col-sm-12 form-horizontal'>
<div class='form-group'>
<label for='receipt_vendor' class='col-sm-2 control-label'>" . $countryT . "</label>
<div class='col-sm-8'>
<fieldset class='sender_countryFieldset'>
<input type='text' readonly value='$countryName' class='form-control'/>
</fieldset>
</div>
</div>
</div>
<div class='col-sm-12'>
<input type='hidden' id='storeCountryShippingWidget' name='storeCountry' value='$storeCountry'>
<input type='hidden' id='priceCodeShippingWidget' value='$currencySymbol'/>
</div>
<div class='clearfix'></div>
<br>
</div>
</div>


<br>
<div class='GrandTotalDiv'>
<div class='inner_grand_div'>

<div class='productSum'>
<div class=''>$totalWeightT</div>
<div class='priceTemp'><span class='pTotalWeight'>$totalWeight</span> KG</div>
</div>

<div class='productSum'>
<div class=''>" . $this->dbF->hardWords('Total Tax 25%', false) . "</div>
<div class='priceTemp'>
<span class='pGrandtax'>$tax </span> $currencySymbol
</div>
</div>

$giftCardDiv

<div class='productSum'>
<div class=''>$grandTotalT</div>
<div class='priceTemp'>
<input type='hidden' class='totalWeightInput' value='$totalWeight' data_cart='$cartId'/>
<input type='hidden' id='priceCode' value='$currencySymbol' data_cart='$cartId'/>
<span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol
</div>
</div>
</div>
<div class='continueBtn'>";
$login = $this->webClass->userLoginCheck();
if (!$login) {

} else {

}
$temp .= "</div>
</div>";
} else {
	$temp = false;
}
return $temp;
}

public $preview = false;
public function viewCheckOutProduct3($id){
	global $subtotal, $currencySymbol, $shipPrice, $grandTotal;

	global $_e, $productClass, $webClass, $functions;

	$orderUser  = $this->webUserId();
	if($orderUser=='0'){
		$orderUser = $this->webTempUserId();
	}
	$sql        = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id' AND orderUser = '$orderUser'";
	$orderInvoice   =   $this->dbF->getRow($sql);
	if(!$this->dbF->rowCount){
		return false;
	}

	$preview = $this->preview;
	if($orderInvoice['orderStatus']=='process'){
		$this->preview = $preview = true;
	}


	$sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
	$orderProducts   =   $this->dbF->getRows($sql);


# preview for showing in order preview
	if ($preview == false) {

		if($orderInvoice['orderStatus']=='process'){
			$submitSuccessT = $this->dbF->hardWords('Your Order Submitted Successfully',false);
			$msg    =   "<br><div class='well alert alert-success h4 text-center'>$submitSuccessT </div>";

			if($this->functions->developer_setting('invoice_print_after_Checkout') == '1') {
				$print_link = WEB_URL . "/invoicePrint?mailId=$id&orderId=" . $this->functions->encode($id);
				header("Location: $print_link");
				exit;

			}
			$msg .= "   <script>
			$(document).ready(function(){
    // setTimeout(function(){location.replace('viewOrder')},1500);
    // setTimeout(function(){location.href = location.href+'&preview=1'},1500);
				});
				</script>";

				return $msg;
			}

			if($orderInvoice['orderStatus']=='pendingPaypal' || $orderInvoice['paymentType']=='1' ){
//redirect to paypal page for payment
				$this->openPaypal($orderInvoice['paymentType'],$orderInvoice['order_invoice_pk']);
			}
			else if($orderInvoice['orderStatus']=='pendingPayson' || $orderInvoice['paymentType']=='5' ){
//redirect to payson page for payment
				$this->openPayson($orderInvoice['paymentType'],$orderInvoice['order_invoice_pk']);
			}

		}

		$temp  = '';
		if($this->dbF->rowCount>0){
			$i = 0;

			$currencyCountry = $this->currentCurrencyCountry();
			$country    =   $this->currentCountry();
			$currencyId =   $this->currentCurrencyId();


			$temp   =   "";

			$abc    = " <div class='container-fluid table-responsive'> ";

			$grandTotal =   0;
			$totalWeight=   0;
			$totalPriceProducts = 0;

			$hasScaleVal    =   $this->functions->developer_setting('product_Scale');
			$hasColorVal    =   $this->functions->developer_setting('product_color');

			$hasScale       =   ($hasScaleVal=='1' ? true : false);
			$hasColor       =   ($hasColorVal=='1' ? true : false);
			$sizeModal      = '' ;


			$count = 0;
			$google_ecommerce = $cart_side_order_products = $top_divs = $last_divs = $order_products = '';

			$web_url      = WEB_URL;

# three for two category start
			$three_for_2_ibm_cat = intval( $this->functions->ibms_setting("checkout_two_for_3_category") );
			$three_for_2_cat_div = "3 For 2 Category";
			$three_for_2_qty     = 0;
			$three_for_2_pro_price = array();
			if ( $three_for_2_ibm_cat > 0 ) {
				$three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
			}else{
				$three_for_2_ibm_cat = array();
			}
# three for two category end


			foreach($orderProducts as $val){
				$this->cartInvoice = true;
				$i++;
				$cartId     =   $val['invoice_product_pk'];
//Product hash
				$pIds       =   $val['order_pIds'];
$pArray     =   explode("-",$pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
$pId        =   $pArray[0]; // 491
$scaleId    =   $pArray[1]; // 426
$colorId    =   $pArray[2]; // 435
$storeId    =   $pArray[3]; // 5
@$customId  =   $pArray[4]; // 5
$dealId     =   $val['deal']; // if not it is 0
@$info      =   unserialize($val['info']);
if(empty($customId)){
	$customId = 0;
}
$qty        =   $val['order_pQty'];
$link       = WEB_URL . '/detail.php?pId=' . $pId;

if($dealId == '0'){
	$pImage     =   $this->productSpecialImage($pId,'main');
}
else{
	$dealData       = $this->getDealData($dealId);
	$pImage         = $dealData['image'];
}
$pName      =   $val['order_pName'];
$pNames     =   explode(" - ",$pName);
@$pName1    =   $pNames[0];
@$pScaleName=   $pNames[1];
@$pColorName=   $pNames[2];

//$pPrice     =   $this->productF->productTotalPrice($pId,$scaleId,$colorId,$currencyCountry,false);
$pPrice       =     $val['order_salePrice'];
$discount     =     $this->productF->productDiscount($pId,$currencyId);
@$discountFormat=   $discount['discountFormat'];
@$discountP     =   $discount['discount'];
/*
$discountPrice  =   $this->productF->discountPriceCalculation($pPrice,$discount);
$discountPrice  =   round($discountPrice,2);
*/
$discountPrice  =   $val['order_discount'];
$newPrice       =   $pPrice - $discountPrice;
$productQtyDiscount =   $discountPrice*$qty;
$totalPrice     =   $newPrice*$qty;
$totalPriceProducts     +=   $newPrice*$qty;
$grandTotal     += $totalPrice;
$currencySymbol =   $this->currentCurrencySymbol();

$shippingData   =   $this->productF->productShipping($pId,$currencyId);
$shipping   =   $shippingData['propri_intShipping'];

$weightSingle   =   $val['order_pWeight'];
$weight     =   $weightSingle;
$totalWeight    += $weight;

$sum        =   $qty*$pPrice;

$subtotal += $sum;


$sumT       =   $this->dbF->hardWords('sum',false);
$weightT    =   $this->dbF->hardWords('Weight',false);
$discountT  =   $this->dbF->hardWords('Discount',false);
$qtyT       =   $this->dbF->hardWords('Quantity',false);
$totalT     =   $this->dbF->hardWords('Total',false);
$Farg       =   $this->dbF->hardWords('Farg',false);
$sizeT      =   $this->dbF->hardWords('Size',false);
$dealT      =   $this->dbF->hardWords('Deal',false);

$sizeInfo   = '';
$colorInfo  = '';
if($hasScale){
	$sizeInfo = "$pScaleName";
	if($customId != '0' && !empty($customId)){
		$sizeInfo = "<a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a>";
		$customFieldsData   = $this->customSubmitValues($customId);
		$customFields       = $customFieldsData['form'];
		$sizeModal .= $this->functions->blankModal($_e['Custom'],"customSizeInfo_$customId",$customFields,$_e['Close']);
	}
	if($dealId != '0' && !empty($dealId) && $scaleId == '0' ){
		$sizeInfo = "<a href='#$dealId' data-toggle='modal' data-target='#dealInfo_$dealId'>".$dealT." ".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a>";
		$customFields = $this->dealSubmitPackage($info,false);
		$sizeModal .= $this->functions->blankModal($_e['Custom'],"dealInfo_$dealId",$customFields,$_e['Close']);
	}
}
if($hasColor){
// decrease the color circle
	$pColorName = str_replace('padding: 5px 12px;', 'padding: 2px 9px;', $pColorName);
	$colorInfo = "$pColorName";
}


############ /* Buy 2 get 1 free start */
$buy_2_get_1_free_div = $this->productF->buy_get_free_invoice_div($id,$cartId);
############ /* Buy 2 get 1 free end */


############ FREE GIFT TEXT #############
$free_gift_product_div = "";
if($totalPrice == "0" && $sum == $productQtyDiscount) {
	$free_gift_product_div = $this->productF->free_gift_text();
}
############ FREE GIFT TEXT #############

//if deal then color or scale make blank
if($dealId!='0'){
//$sizeInfo = '';
	$colorInfo = '';
}


$order_products .=    "
<div class='tc_line2'></div>
<div class='detail_cart2'>
<div class='img_detail2  inline_block'><a href='{$link}'><img src='images/{$pImage}' alt=''></a></div>
<div class='info_cart2 inline_block'>
<div class='info_head2'><a href='{$link}'>{$pName1}</a></div>
<!--
<div class='info_text2'><label>" . $sizeT . ": </label>{$sizeInfo} </div>

<div class='info_text2'><label>" . $Farg . ": </label>{$colorInfo} </div>

-->
<div class='info_text2'><label>" . $qtyT . ": </label>{$qty} @ {$pPrice} {$currencySymbol}</div>
<div class='info_text2'>$buy_2_get_1_free_div</div>
<div class='info_text2'>$free_gift_product_div</div>
<div class='rate_detail2'>{$newPrice} {$currencySymbol}</div>
</div><!--info_cart2 end-->
</div><!--detail_cart2 end-->
";

$slugname       = $this->get_product_slugname($pId);
$slug_link      = WEB_URL . '/' . $this->db->productDetail . $slugname;

$productImageReal = $pImage;
$productImage = $this->functions->resizeImage($productImageReal, '', '200', false);

$totalPriceSimple = $newPrice * $qty;

############ Product Categories ##########
$pro_cat    = $this->productF->product_category($pId);

##################### check if product category in 3 For 2 Category START ##############
$three_for_2_category = "";
if ( sizeof ( array_intersect($three_for_2_ibm_cat, $pro_cat ) ) > 0 && $newPrice > 0) {
	$three_for_2_pro_price[$cartId]["id"] = $pId;
	$three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
	$three_for_2_pro_price[$cartId]["qty"] = $qty;
	$three_for_2_qty += $qty;
	$three_for_2_category = " <img src='".WEB_URL."/images/3for2.jpg' height='40' />";
}


// $google_ecommerce .= $this->webClass->getItemJs($orderInvoice['order_invoice_pk'], $val);



$cart_side_order_products .= "


<div class='cart_1_inner'>

<div class='cart_1_inner_1'>

<a href='{$slug_link}'>

<img src='{$productImage}' alt='' >

</a>
</div>

<div class='cart_1_inner_2'>
<h3>{$pName1} {$three_for_2_category}</h3>

<div class='info_main'>

{$colorInfo}
&nbsp;
{$sizeInfo}

<div class='info_1'>
{$qtyT}:<span>{$qty} @ {$newPrice}</span>
<span style='padding-left: 2px;' >{$currencySymbol}</span>
</div>

<div class='info_1'>{$totalPrice}<span>{$currencySymbol}</span></div>

</div>

</div>

</div>";


$count++;

}

$country_list   =   $this->functions->countrylist();
$storeCountry   =   $orderInvoice['shippingCountry'];
$countryName    =   $country_list[$storeCountry];
$shipPrice      =   $orderInvoice['ship_price'];
$orderTotal     =   $orderInvoice['total_price'];
$countryT       =   $this->dbF->hardWords('Country',false);

$shipPriceT     =   $this->dbF->hardWords('Shipping Price',false);
$totalWeightT   =   $this->dbF->hardWords('Total Weight',false);
$grandTotalT    =   $this->dbF->hardWords('Grand Total',false);

$grandTotal     = ($grandTotal+$shipPrice);


// $tax = $grandTotal*25/100;
// $tax = round($tax,2);
$price_cal  = $this->functions->product_tax_cal($grandTotal,25);
$tax        = $price_cal['tax_price'];

$giftCardDiv = '';
$recordData = $this->productF->get_order_invoice_record($id, 'giftCard');
if($recordData!=false){
	$giftCardId     = $recordData['setting_val'];
	$giftCardNewPriceData     = $this->giftCardNewPrice($giftCardId,$grandTotal);
	$grandTotal     = $giftCardNewPriceData['total'];
	$giftCard_payPrice = $giftCardNewPriceData['payPrice'];
	$giftCardDiv    = "<div class='productSum'>
	<div class=''>".$this->dbF->hardWords('Gift Card Price',false)."</div>
	<div class='priceTemp'>
	( <span class='giftcardPrice_span_payPrice'>$giftCard_payPrice</span> $currencySymbol )
	</div>
	</div>
	";
}


$grandTotal     =   $orderInvoice['total_price'];
global $three_for_2_minus_price;
$three_for_2_minus_price     =   $orderInvoice['three_for_two_cat'];

// $abc .= "
// </div>
// <br>
//        <div class='shippingWight col-sm-12'>
//             <div class='col-sm-6 text-center'>
//                 <div class='col-sm-12 lead'>$shipPriceT : <span class='pHippingPriceTemp'> $shipPrice </span> $currencySymbol</div>
//                 <div class='col-sm-12 form-horizontal'>
//                         <div class='form-group'>
//                             <label for='receipt_vendor' class='col-sm-2 control-label'>".$countryT."</label>
//                             <div class='col-sm-8'>
//                                 <fieldset class='sender_countryFieldset'>
//                                     <input type='text' readonly value='$countryName' class='form-control'/>
//                                 </fieldset>
//                             </div>
//                         </div>
//                 </div>
//                 <div class='col-sm-12'>
//                     <input type='hidden' id='storeCountryShippingWidget' name='storeCountry' value='$storeCountry'>
//                     <input type='hidden' id='priceCodeShippingWidget' value='$currencySymbol'/>
//                 </div>
//                 <div class='clearfix'></div>
//                 <br>
//             </div>
//         </div>


//         <br>
//         <div class='GrandTotalDiv'>
//             <div class='inner_grand_div'>

//                 <div class='productSum'>
//                     <div class=''>$totalWeightT</div>
//                     <div class='priceTemp'><span class='pTotalWeight'>$totalWeight</span> KG</div>
//                 </div>

//                 <div class='productSum'>
//                     <div class=''>".$this->dbF->hardWords('Total Tax 25%',false)."</div>
//                     <div class='priceTemp'>
//                         <span class='pGrandtax'>$tax </span> $currencySymbol
//                     </div>
//                 </div>

//                 $giftCardDiv

//                 <div class='productSum'>
//                     <div class=''>$grandTotalT</div>
//                     <div class='priceTemp'>
//                         <input type='hidden' class='totalWeightInput' value='$totalWeight' data_cart='$cartId'/>
//                         <input type='hidden' id='priceCode' value='$currencySymbol' data_cart='$cartId'/>
//                         <span class='pGrandTotal' data-total='$grandTotal'>$grandTotal </span> $currencySymbol
//                     </div>
//                 </div>
//             </div>
//             <div class='continueBtn'>";
$login  =   $this->webClass->userLoginCheck();
if(!$login){

}else{

}

$abc = '
</div>
</div>';


$temp = $order_products;
$cart_side_order_products_html = $cart_side_order_products;

}else{
	$temp  =   false;
}

return array( 'temp' => $temp, 'sizeModal' => $sizeModal, 'cart_side_order_products_html' => $cart_side_order_products, 'subtotal' => $subtotal, 'shipPrice' => $shipPrice, 'grandTotal' => $grandTotal, 'three_for_2_minus_price' => $three_for_2_minus_price, 'currencySymbol' => $currencySymbol, 'totalPriceProducts' => $totalPriceProducts );
}


/*
*   public function viewCheckOutProduct($id){
global $_e;

$orderUser  = $this->webUserId();
if($orderUser=='0'){
$orderUser = $this->webTempUserId();
}
$sql        = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$id' AND orderUser = '$orderUser'";
$orderInvoice   =   $this->dbF->getRow($sql);
if(!$this->dbF->rowCount){
return false;
}

$sql = "SELECT * FROM `order_invoice_product` WHERE order_invoice_id = '$id'";
$orderProducts   =   $this->dbF->getRows($sql);
if($orderInvoice['orderStatus']=='process'){
$submitSuccessT = $this->dbF->hardWords('Your Order Submit SuccessFully',false);
$msg    =   "<br><div class='well alert alert-success h4 text-center'>$submitSuccessT </div>";

$msg .= "
<script>
$(document).ready(function(){
setTimeout(function(){location.replace('viewOrder')},1500);
});
</script>
";

return $msg;
}

if($orderInvoice['orderStatus']=='pendingPaypal'){
$this->openPaypal($orderInvoice['paymentType'],$orderInvoice['order_invoice_pk']);
}

$temp  = '';
if($this->dbF->rowCount>0){
$i = 0;

$temp   =   "<table class='table-responsive' id='cartViewTable'>
";

$grandTotal =   0;
$totalWeight=   0;

$hasScaleVal    =   $this->functions->developer_setting('product_Scale');
$hasColorVal    =   $this->functions->developer_setting('product_color');

$hasScale       =   ($hasScaleVal=='1' ? true : false);
$hasColor       =   ($hasColorVal=='1' ? true : false);

foreach($orderProducts as $val){
$this->cartInvoice = true;
$i++;
$cartId     =   $val['invoice_product_pk'];
//Product hash
$pIds       =   $val['order_pIds'];
$pArray     =   explode("-",$pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
$pId        =   $pArray[0]; // 491
$scaleId    =   $pArray[1]; // 426
$colorId    =   $pArray[2]; // 435
$storeId    =   $pArray[3]; // 5
@$customId  =   $pArray[4]; // 5
if(empty($customId)){
$customId = 0;
}
$qty        =   $val['order_pQty'];

$country    =   $this->currentCountry();
$currencyId =   $this->currentCurrencyId();

$pName      =   $this->getProductFullNameWeb($pId,$scaleId,$colorId);
$pNames     =   explode(" - ",$pName);
@$pName1    =   $pNames[0];
@$pScaleName=   $pNames[1];
@$pColorName=   $pNames[2];


//$pPrice     =   $this->productF->productTotalPrice($pId,$scaleId,$colorId,$country,false);
$pPrice       = $val['order_salePrice'];
$discount     =   $this->productF->productDiscount($pId,$currencyId);
@$discountFormat=   $discount['discountFormat'];
@$discountP     =   $discount['discount'];

$discountPrice  =   $val['order_discount'];
$newPrice       =   $pPrice - $discountPrice;
$productQtyDiscount =   $discountPrice*$qty;
$totalPrice     =   $newPrice*$qty;
$grandTotal     += $totalPrice;
$currencySymbol =   $this->currentCurrencySymbol();

$shippingData   =   $this->productF->productShipping($pId,$currencyId);
$shipping   =   $shippingData['propri_intShipping'];
$pImage     =   $this->productSpecialImage($pId,'main');
$weightSingle   =   $this->productF->getProductWeight($pId,$scaleId,$colorId);
$weight     =   $weightSingle*$qty;
$totalWeight    += $weight;

$sum    =   $qty*$pPrice;

$sumT   =   $this->dbF->hardWords('sum',false);
$weightT   =   $this->dbF->hardWords('Weight',false);
$discountT   =   $this->dbF->hardWords('Discount',false);
$qtyT       =   $this->dbF->hardWords('Quantity',false);
$totalT     =    $this->dbF->hardWords('Total',false);
$Farg       =   $this->dbF->hardWords('Farg',false);
$sizeT      =   $this->dbF->hardWords('Size',false);

$sizeInfo   = '';
$colorInfo  = '';
if($hasScale){
$sizeInfo = "<div>$sizeT# : $pScaleName</div>";
if($customId != '0' && !empty($customId)){
$sizeInfo = "<div>$sizeT# : <a href='#$customId' data-toggle='modal' data-target='#customSizeInfo_$customId'>".$_e['Custom']." <i class='small glyphicon glyphicon-resize-full'></i></a></div>";
$customFields = $this->customSubmitValues($customId);
$sizeInfo .= $this->functions->blankModal($_e['Custom'],"customSizeInfo_$customId",$customFields,$_e['Close']);
}
}
if($hasColor){
$colorInfo = "<div>$Farg : $pColorName</div>";
}


$temp .=    "

    <tr class='border $cartId' id='tr_$cartId' data-realPrice='$pPrice' data-pId='$pId' data-id='$cartId' data-price='$newPrice' data-weight='$weightSingle'>
            <td class='serialTd'>
                <div class='cartSerial'>$i.</div>
                    <input type='hidden' class='product_weight' value='$weight' data-weight='$weightSingle' data_cart='$cartId'/>
                    <input type='hidden' class='interShipping' value='$shipping' data_cart='$cartId'/>
                     <input type='hidden' class='hidden' value='$discountFormat' id='discountFormat_$pId'>
                    <input type='hidden' class='hidden' value='$discountP' id='discount_$pId'>
                    <input type='hidden' class='hidden' value='$newPrice' id='discountPrice_$pId'>
            </td>
            <td class='imgTd'><img src='images/$pImage'/></td>
            <td class='nameTd'>
                <div class=''>
                    <div>$pName1</div>
                    <div>$sizeInfo</div>
                    <div>$colorInfo</div>
                </div>
            </td>
            <td class='priceTd'>
                <div class='sumMainDiv'>
                    <div class='qtyDiv'>
                        <div>$qtyT</div>
                        <div class='productTotalQ'>
                            <div class='productQty'><span class='pQty'>$qty</span></div>
                            <div>X</div>
                            <div class='productPrice'><span class='pPrice'>$pPrice</span> $currencySymbol</div>
                        </div>
                    </div>
                    <div>
                        <div class='productSum'>
                            <div class=''>$sumT</div>
                            <div class='priceTemp'><span class='sumProduct'>$sum</span> $currencySymbol</div>
                        </div>
                    </div>
                    <div>
                        <div class='productSum'>
                            <div class=''>$weightT</div>
                            <div class='priceTemp'><span class='pWeight'>$weight </span> KG</div>
                        </div>
                    </div>
                    <div>
                        <div class='productSum'>
                            <div class=''>$discountT</div>
                            <div class='priceTemp'><span class='pDiscount'>$productQtyDiscount</span> $currencySymbol</div>
                        </div>
                    </div>

                    <div class='productTotal'>
                        <div class='productSum'>
                            <div class=''>$totalT</div>
                            <div class='priceTemp'><span class='pTotalPrice'>$totalPrice </span> $currencySymbol</div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    ";
}

$country_list   =   $this->functions->countrylist();
$storeCountry   =   $orderInvoice['shippingCountry'];
$countryName    =   $country_list[$storeCountry];
$shipPrice      =   $orderInvoice['ship_price'];
$orderTotal     =   $orderInvoice['total_price'];
$countryT   =   $this->dbF->hardWords('Country',false);

$shipPriceT = $this->dbF->hardWords('Shipping Price',false);
$totalWeightT =$this->dbF->hardWords('Total Weight',false);
$grandTotalT = $this->dbF->hardWords('Grand Total',false);

$tax = $grandTotal*25/100;
$tax = round($tax,2);

$temp .= "
</table>
<br>
   <div class='shippingWight col-sm-12'>
        <div class='col-sm-6 text-center'>
            <div class='col-sm-12 lead'>$shipPriceT : <span class='pShippingPriceTemp'> $shipPrice </span> $currencySymbol</div>
            <div class='col-sm-12 form-horizontal'>
                    <div class='form-group'>
                        <label for='receipt_vendor' class='col-sm-2 control-label'>".$countryT."</label>
                        <div class='col-sm-8'>
                            <fieldset class='sender_countryFieldset'>
                                <input type='text' readonly value='$countryName' class='form-control'/>
                            </fieldset>
                        </div>
                    </div>
            </div>
            <div class='col-sm-12'>
                <input type='hidden' id='storeCountryShippingWidget' name='storeCountry' value='$storeCountry'>
                <input type='hidden' id='priceCodeShippingWidget' value='$currencySymbol'/>
            </div>
            <div class='clearfix'></div>
            <br>
        </div>
    </div>


    <br>
    <div class='GrandTotalDiv'>
        <div class='col-sm-12'>

            <div class='productSum'>
                <div class=''>$totalWeightT</div>
                <div class='priceTemp'><span class='pTotalWeight'>$totalWeight</span> KG</div>
            </div>

            <div class='productSum'>
                <div class=''>".$this->dbF->hardWords('Total Tax 25%',false)."</div>
                <div class='priceTemp'>
                    <span class='pGrandtax'>$tax </span> $currencySymbol
                </div>
            </div>

            <div class='productSum'>
                <div class=''>$grandTotalT</div>
                <div class='priceTemp'>
                    <input type='hidden' class='totalWeightInput' value='$totalWeight' data_cart='$cartId'/>
                    <input type='hidden' id='priceCode' value='$currencySymbol' data_cart='$cartId'/>
                    <span class='pGrandTotal' data-total='$grandTotal'>$orderTotal </span> $currencySymbol
                </div>
            </div>
        </div>
        <div class='continueBtn'>";
$login  =   $this->webClass->userLoginCheck();
if(!$login){

}else{

}
$temp .= "</div>
    </div>";
}else{
$temp  =   false;
}
return $temp;

}
*
*/
public function webUserId()
{
	return webUserId();
}

public function webUserOldTempId()
{
	return webUserOldTempId();
}

public function webTempUserId()
{
	return webTempUserId();
}

public function cartSubmit()
{
//submit from carContinue... Cash on delivery or like that work
//submit from orderInvoice.php
	$btn1 = 'ORDER';
//set Submit buttons value here
	if (isset($_POST) && !empty($_POST) && !empty($_POST['receiver_country'])) {
		if (!$this->functions->getFormToken('WebOrderReady')) {
			return false;
		}

		try {
			$this->db->beginTransaction();
			if ($_POST['submit'] == $btn1) {
				$process = 0;
			} else {
				throw new Exception("Cart Submit Error");
			}

$invoiceStatus = "2"; //pending
$invoiceId = $_POST['invoiceId'];

@$paymentType = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : '0'; //int
@$payment_info = $_POST['paymentInfo']; //text
@$total_price = $_POST['totalPrice']; //Using In Security, If price from web form or php calculated not match, mean Hacking Attempt
$userId = webUserId();
if ($userId == '0') {
	$userId = webTempUserId();
}

//major data submit here, will later here, update this table
$rsv = '';
if ($paymentType == '1') {
//PayPal
	$status = 'pendingPayPal';
//$status      =   'inComplete';
$invoiceStatus = "1"; //Denied
} else if ($paymentType == '0') {
//cash on delivery
	$status = 'process';

//giftcard /....
	$sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$invoiceId'";
	$orderInvoice = $this->dbF->getRow($sql);

//gift Card work...
	$recordData = $this->productF->get_order_invoice_record($invoiceId, 'giftCard');
	$giftCard_payPrice = 0;
	if ($recordData != false) {
		$giftCardId = $recordData['setting_val'];
		$giftCardNewPriceData = $this->giftCardNewPrice($giftCardId, $orderInvoice['total_price']);
		$giftCard_payPrice = $giftCardNewPriceData['payPrice'];
		if ($giftCard_payPrice > 0) {
//now every thing is ok in giftcard,,
			$payment_info = $this->updateGiftCard($giftCardId, $giftCard_payPrice, $invoiceId, $giftCardNewPriceData['data']['info']) . $payment_info;
			$rsv = ",`rsvNo` = 'GiftCard : $giftCardId; Price:$giftCard_payPrice'";
		}
	}
//giftcard End....

} else if ($paymentType == '5') {
//payson
	$status = 'pendingPayson';
//$status     =   'inComplete';
$invoiceStatus = "1"; //Denied
}

################## Add additional price of selected payment method ##############
$payment_type_price = $this->payment_additional_price($paymentType,true);
$payment_type_price_sql = '';
if($payment_type_price > 0){
	$payment_type_price_sql = " , `total_price` = total_price+$payment_type_price
	, `ship_price` = ship_price+$payment_type_price ";
}
################## Add additional price of selected payment method  END ##############

$sql = "UPDATE  `order_invoice` SET
`orderStatus` = '$status',
`invoice_status` = '$invoiceStatus',
`paymentType` = '$paymentType',
`payment_info` = ?
$rsv
$payment_type_price_sql
WHERE order_invoice_pk = '$invoiceId' && orderUser = '$userId'";
$this->dbF->setRow($sql, array($payment_info), false);
// invoice first data Enter

//Deduct Stock qty
//Paypal qty deduct after paypal confirmation
if ($paymentType == '0') {
	$this->functions->require_once_custom('orderInvoice');
	$orderInvoiceClass = new invoice();
	$returnStatus = $orderInvoiceClass->stockDeductFromOrder($invoiceId, false);
	if ($returnStatus === false) {
		throw new Exception($this->dbF->hardWords("Stock Deduction Error", false));
		return false;
	}
}

// User Info Add
//first add order invoice,, addNewOrder();
$sql = "INSERT INTO `order_invoice_info`
(
	`order_invoice_id`,

	`sender_Id`,

	`sender_name`,
	`sender_phone`,
	`sender_email`,
	`sender_address`,
	`sender_city`,
	`sender_country`,
	`sender_post`,

	`receiver_name`,
	`receiver_phone`,
	`receiver_email`,
	`receiver_address`,
	`receiver_city`,
	`receiver_country`,
	`receiver_post`
)
VALUES (
?, ?,
?,?,?,?,?,?,?,
?,?,?,?,?,?,?
)";
$array = array(
	$invoiceId, $userId,
	$_POST['sender_name'], $_POST['sender_phone'], $_POST['sender_email'], $_POST['sender_address'], $_POST['sender_city'], $_POST['sender_country'], $_POST['sender_post'],
	$_POST['receiver_name'], $_POST['receiver_phone'], $_POST['receiver_email'], $_POST['receiver_address'], $_POST['receiver_city'], $_POST['receiver_country'], $_POST['receiver_post'],
);
$this->dbF->setRow($sql, $array, false);


$this->emptyCart();
$this->db->commit(); // important work is end...


/////////////////////Sending Email
{
//invoice id,
	$_GET['mailId'] = $invoiceId;
	$orderIdInvoiceF = $this->functions->ibms_setting('invoice_key_start_with') . $invoiceId;
$orderIdInvoice = $this->dbF->hardWords('ORDERING', false) . " ($orderIdInvoiceF)"; // return Order invoice-12
$fromName = $this->functions->webName; // website name
$mailArray['fromName'] = $fromName;
$mailArray['invoiceNumber'] = $orderIdInvoiceF;

if ($paymentType == '0') {
//send email when payment method is cas on delivery
	$msg2 = include(__DIR__ . '/../../orderMail.php');
//send email to client
	$this->functions->send_mail($_POST['sender_email'], $orderIdInvoice, $msg2, '', $_POST['sender_name'], $mailArray);

// Special offer send on invoice generate
	$this->functions->send_mail($_POST['sender_email'], "", "", 'todayOffer', $_POST['sender_name'], $mailArray);

	if ($_POST['sender_email'] != $_POST['receiver_email']) {
//send email to client if both email not equal
		$this->functions->send_mail($_POST['receiver_email'], $orderIdInvoice, $msg2, '', $_POST['sender_name'], $mailArray);
	}

//send email to admin
	$adminMail = $this->functions->ibms_setting('Email');
	$this->functions->send_mail($adminMail, $orderIdInvoice, $msg2, '', '', $mailArray);

} //////////////////////Sending Email PDF End


//check is custom product has
$sql = "SELECT * FROM `order_invoice_product` WHERE order_pIds NOT LIKE '%-%-%-%-0' AND `order_invoice_id` = '$invoiceId'";
$customData = $this->dbF->getRow($sql);
if ($this->dbF->rowCount > 0) {
//Email custom measurement link...
	$invoiceLink = WEB_URL . "/viewOrder?view=$invoiceId&orderId=" . $this->functions->encode($invoiceId);
	$mailArray['link'] = $invoiceLink;
	$this->functions->send_mail($_POST['sender_email'], $orderIdInvoice, '', 'Measure_email_on_invoice_create', $_POST['sender_name'], $mailArray);
}
}//////////////////////Sending Email End
$msg = $this->dbF->hardWords('Thank you your Order is successfully submitted', false);


$this->restCartSession();
if ($paymentType == '1') {
	$this->openPaypal($paymentType, $invoiceId);
} elseif ($paymentType == '5') {
	$this->openPayson($paymentType, $invoiceId);
}
//return array("msg"=>$msg,"paymentType"=>$paymentType, "invoiceId"=>$invoiceId);
return $msg;
} catch (Exception $e) {
	$this->dbF->error_submit($e);
	$msgT = $e->getMessage();
	$this->db->rollBack();
	$msg = $this->dbF->hardWords('Something went wrong Please try again', false);
	return $msg . " <br> " . $msgT;
}

} else if (isset($_POST) && !empty($_POST) && isset($_POST['submit']) && @$_POST['submit'] == $btn1) {
	$msg = $this->dbF->hardWords('Something Went Wrong, Order Submit Fail', false);
	return $msg;
}
} //Function End

public $orderLastInvoiceId = 0;

public function cartSubmitForCheckOut($directSubmit = false)
{
// call from cart.php, first submit here...
// klarna or after login continue,, this will call function

	$btn1 = 'ORDER';
	global $_e;
//set Submit buttons value here
	if (isset($_POST) && !empty($_POST) || $directSubmit) {

		if ($directSubmit) {
			$_POST['storeCountry']      = $this->currentCountry();
			$_POST['shippingWidget']    = $this->currentCountry();
		}

		if (!$this->functions->getFormToken('WebOrderReadyForCheckOut') && $directSubmit == false) {
			return false;
		}

		try {
			$this->db->beginTransaction();

$invoiceStatus = "1"; //denied
$invoiceId = '';     //
$process = 0;
$orderStatus = 'inComplete';
@$country = $_POST['storeCountry'];
$price_code = $this->currentCurrencySymbol();

$orderUserId = $this->webUserId();
if ($orderUserId == '0') {
	$orderUserId = $this->webTempUserId();
}
$shippingCountry = $_POST['shippingWidget'];

$countryData    = $this->productF->productCountryId($country);
$countryId      = $countryData['cur_id'];



############## ORDER INVOICE REMOVING OLD ORDER, BACK BUTTON CART WORKING BELOW ################

$this->order_remove_unprocessed();

################################################################################################

$array = array($price_code, $invoiceStatus, $orderStatus, $orderUserId, $shippingCountry);



############################### //major data submit here, will later here, update this table #################################
$now = date('Y-m-d H:i:s');
$sql = "INSERT INTO `order_invoice`
(
	`price_code`,
	`invoice_date`,
	`invoice_status`,
	`orderStatus`,
	`orderUser`,
	`shippingCountry`
)
VALUES (
?,?,?,?,?,?
)";
$array = array($price_code, $now, $invoiceStatus, $orderStatus, $orderUserId, $shippingCountry);
$this->dbF->setRow($sql, $array, false);
$invoiceId = $this->dbF->rowLastId;
$this->orderLastInvoiceId = $invoiceId;
##################### //invoice first data Enter ###################


##################### //Invoice Product add ################
$userId     = $this->webUserId();
$tempId     = $this->webTempUserId();


############## /* Buy 2 get 1 free start */
$has_buy_get_free   = false;
$buy_get_free_query = "";
if( $this->functions->developer_setting("buy_2_get_1_free") == "1" && true == false){
	$has_buy_get_free   = true;
	$buy_get_free_query = ", (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free') as buy_2_get_1_free
	, (SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='buy_2_get_1_free_qty') as buy_2_get_1_free_qty";
}
############# /* Buy 2 get 1 free end */


############ 3 For 2 Category START #########
$three_for_2_ibm_cat = intval( $this->functions->ibms_setting("checkout_two_for_3_category") );
$three_for_2_cat_div = "3 For 2 Category";
$three_for_2_qty     = 0;
$three_for_2_pro_price = array();
if ( $three_for_2_ibm_cat > 0 ) {
	$three_for_2_ibm_cat = $this->getSubCatIds($three_for_2_ibm_cat);
}else{
	$three_for_2_ibm_cat = array();
}
############ 3 For 2 Category END  ##########


$sql        = "SELECT *,(SELECT setting_val FROM product_setting as s WHERE c.pId=s.p_id AND s.setting_name='shippingClass') as shippingPrice $buy_get_free_query FROM `cart` as c WHERE `userId` = '$userId' AND tempUser = '$tempId'";
$data       = $this->dbF->getRows($sql);
$temp       = '';
if (!$this->dbF->rowCount) {
	throw new Exception("Some Thing Is Wrong, No items in cart: error:42251");
//42251 = hack1
//Hack
}
$grandTotal = 0;
$totalWeight = 0;
$shippingClassPrice = 0;

$currencyCountry = $this->currentCurrencyCountry();
$country = $this->currentCountry();
$currencyId = $this->currentCurrencyId();

foreach ($data as $key => $val) {
	$cartId     = $val['id'];
$pId        = $val['pId']; // 491
$scaleId    = $val['scaleId']; // 426
$colorId    = $val['colorId']; // 435
$storeId    = $val['storeId']; // 5
$customId   = $val['customId']; // 10 custom Size id, if not it is 0
$dealId     = $val['deal']; // if not it is 0
@$checkout  = $val['checkout']; // if not it is 0
@$info      = unserialize($val['info']);
$infoM      = $info;
$pIds       = array($pId, $scaleId, $colorId, $storeId, $customId);
$pIds       = implode("-", $pIds);


############ Product Categories ##########
$pro_cat    = $this->productF->product_category($pId);

$qty        = $val['qty'];
$coupon     = "";
@$shippingId = $val['shippingPrice'];
$shippingPriceData = $this->shippingPriceByClass($shippingId, $shippingClassPrice);
$shippingPriceT = $shippingPriceData["price"];
$shippingClassPrice = $shippingPriceData["classPrice"];

//if not deal, it is product
if ($dealId == '0') {
	$pName = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
	if ( $customId != '0' && $scaleId == '0' ) {
		$pName = explode(" - ", $pName);
		$pName[1] = $_e['Custom'];
		$pName = implode(" - ", $pName);
	}
	$pPrice = $this->productF->productTotalPrice($pId, $scaleId, $colorId, $customId, $currencyCountry, false);

//checking if this is checkout offer then checkout discount offer apply
//if deal is 0 then checout offer need to check,
//if checkOut is 1 then coupon not apply and different price will apply
	if ($checkout == '1') {
		$checkoutPrice = $this->checkOutProductPrice($pId, $currencyId);
//add color or scale price
		$p_color_price = $this->productF->colorPrice($colorId, $currencyId, $pId);
		@$p_color_price = $p_color_price['proclr_price'];
		$p_size_price = $this->productF->scalePrice($scaleId, $currencyId, $pId);
		@$p_size_price = $p_size_price['prosiz_price'];
		$checkoutPrice = $checkoutPrice + floatval($p_color_price) + floatval($p_size_price);
		$discountPrice = $pPrice - $checkoutPrice;
		@$discountFormat = "price";
		@$discountP = $discountPrice;
	}
############################################################
################# FREE GIFT Check Is product FREE then make it price 0 free ############
	else if ($checkout == '2') {
		$discountPrice      = $pPrice;
		@$discountFormat    = "price";
		@$discountP         = $discountPrice;
	} else {
		$coupon = $this->getCoupon();
		if (!empty($coupon)) {
			$this->productF->set_order_invoice_record($invoiceId, 'coupon', $coupon, $pId);
		}

		$discount = $this->productF->productDiscount($pId, $currencyId, $coupon);
		$discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
}///$checkout else end

$shippingData = $this->productF->productShipping($pId, $currencyId);
$shipping = $shippingData['propri_intShipping'];
$pImage = $this->productSpecialImage($pId, 'main');
$weightSingle = $this->productF->getProductWeight($pId, $scaleId, $colorId);
} else {
//if order is deal
	$dealData = $this->getDealData($dealId);
	$pName = $this->getDealNameWeb($dealData);

	$pPrice = $this->getDealPrice($dealData);
	$discountPrice = 0;
	$discountFormat = $discountP = '';

	$shipping = $this->getDealShippingStatus($infoM);
	$pImage = $dealData['image'];
	$weightSingle = $this->getDealWeight($infoM);

	$info = $this->getDealProductOrders($infoM);
}

$discountPrice = round($discountPrice, 2);
$newPrice = round($pPrice - $discountPrice, 2);
$totalPrice = $newPrice * $qty;
$grandTotal += $totalPrice;

$productQtyDiscount = $discountPrice * $qty;

$weight = $weightSingle * $qty;
$totalWeight += $weight;

$storeName = $this->productF->getStoreName($storeId);

@$hashVal = $pId . ":" . $scaleId . ":" . $colorId . ":" . $storeId;
$hash = md5($hashVal);

//insert product info into order products
$sql = "INSERT INTO `order_invoice_product`
(
	`order_invoice_id`,
	`order_pIds`,
	`order_pName`,
	`order_pStore`,
	`order_pPrice`,
	`order_salePrice`,
	`order_discount`,
	`order_pQty`,
	`order_pWeight`,
	`order_process`,
	`deal`,
	`checkout`,
	`info`,
	`order_hash`
) VALUES (
?,?,?,?,?,?,?,?,?,?,?,?,?,?
)";

@$info = serialize($info);
$array = array($invoiceId, $pIds, $pName, $storeName, $pPrice, $pPrice, $discountPrice, $qty, $weight, $process, $dealId,$checkout, $info, $hash);
$this->dbF->setRow($sql, $array, false);
$invoice_product_id = $this->dbF->rowLastId;

############ Buy 2 Product get 1 free START  ##############
/*if( $val["buy_2_get_1_free"] == '1' && $has_buy_get_free ){
$buy_get_free_apply_limit_qty   =  empty($val["buy_2_get_1_free_qty"]) ? "2" : $val["buy_2_get_1_free_qty"];
if( $qty >= $buy_get_free_apply_limit_qty ){
$free_qty           = floor($qty/$buy_get_free_apply_limit_qty);
$buy_free_info = array("buy" => $qty,"offer_limit" => $buy_get_free_apply_limit_qty, "free_qty" => $free_qty);
$this->productF->set_order_invoice_record($invoiceId,"buy_get_free",$free_qty,$invoice_product_id,$buy_free_info);
}
}*/
############ Buy 2 get 1 free END #########
####################################################################################


##################### check if product category in 3 For 2 Category START ##############
if ( sizeof ( array_intersect($three_for_2_ibm_cat, $pro_cat ) ) > 0 && $newPrice > 0) {
	$three_for_2_pro_price[$cartId]["id"] = $pId;
	$three_for_2_pro_price[$cartId]["price"] = intval($newPrice);
	$three_for_2_pro_price[$cartId]["qty"] = $qty;
	$three_for_2_qty += $qty;
}
#################### check if product category in 3 For 2 Category END ###############


} // Foreach loop End


###################### //Update invoice after

//Calculating Shiping price
$shippingInfo = $this->shippingInfo();
$shippingType = $shippingInfo["shippingType"];
$shippingPriceLimit = $shippingInfo["priceLimit"];

if ($grandTotal > $shippingPriceLimit) {
//Free shipping
	$finalShippingPrice = 0;

} else {
	if ($shippingType == 'class') {
		$finalShippingPrice = $shippingClassPrice;
	} else {
		$shippingData = $this->productF->shippingPrice($country, $_POST['shippingWidget']);
		if ($shippingData == false) {
			throw new Exception("Shipping Error Found");
		}

		$shippingWeight = $shippingData['shp_weight'];
		$shippingPrice = $shippingData['shp_price'];
		$this->functions->includeOnceCustom(ADMIN_FOLDER . '/shipping/classes/shipping.php');
		$shippingC = new shipping();
		$shippingWeight = $shippingC->shpWeightArrayFind($shippingWeight);

//calculating
		$unitWeight = ceil($totalWeight / $shippingWeight);
		$unitWeight = round($unitWeight, 2);
		$finalShippingPrice = $shippingPrice * $unitWeight;
	}
	$grandTotal += $finalShippingPrice;

}

$invoiceKey = $this->functions->ibms_setting('invoice_key_start_with'); // Invoice Number start with


$giftCardData = $this->giftCardCheck($grandTotal);
$giftCardId = $giftCardData['giftCardId'];

if (!empty($giftCardId)) {
	$giftCard_payPrice = $giftCardData['payPrice'];
	if (!$giftCardData['error'] && $giftCard_payPrice > 0) {
		$this->productF->set_order_invoice_record($invoiceId, 'giftCard', $giftCardId);
//$grandTotal  = $grandTotal-$giftCard_payPrice; // save original price, for when giftcard use some where else, then show original price..
	}
}

############ 3 For 2 Category START #########
$three_for_2_qty = floor($three_for_2_qty/3);
$three_for_2_minus_price = $this->three_for_2_category_rec($three_for_2_pro_price,$three_for_2_qty);
$grandTotal      = $grandTotal-$three_for_2_minus_price;
############ 3 For 2 Category END #########

$sql = "UPDATE `order_invoice` SET
`invoice_id`    =   '" . $invoiceKey . '' . $invoiceId . "',
`total_price`   =   '$grandTotal',
`ship_price`    =   '$finalShippingPrice',
`three_for_two_cat`    =   '$three_for_2_minus_price',
`total_weight`  =   '$totalWeight'
WHERE `order_invoice_pk`  = '$invoiceId'";
$this->dbF->setRow($sql, false);

$this->db->commit();
// $this->emptyCart();
$this->updateCartStatus();


$msg = $this->dbF->hardWords('Your Cart Is ready to submit, Please Select Payment Type To place your Order.', false);
return $msg;
} catch (Exception $e) {
	$this->dbF->error_submit($e);
	$this->db->rollBack();
	$msg = $this->dbF->hardWords('Something went wrong Please try again', false);
	return $msg;
}
}

} //Function End

public function restCartSession()
{
	unset($_SESSION['webUser']['giftCard']);
	unset($_SESSION['webUser']['coupon']);
}

public function giftCardNewPrice($giftCardId, $grandTotal)
{
	$_SESSION['webUser']['giftCard'] = $giftCardId;
	$giftCardData = $this->giftCardCheck($grandTotal);
	if (!empty($giftCardId)) {
		$giftCard_payPrice = $giftCardData['payPrice'];
		if (isset($giftCardData['error']) && $giftCardData['error'] == false && $giftCard_payPrice > 0) {
//$this->orderRecordTableDataSave($invoiceId, 'giftCard', $giftCardId);
			$grandTotal = $grandTotal - $giftCard_payPrice;
		}

	}
	return array('total' => $grandTotal, 'payPrice' => $giftCard_payPrice, 'data' => $giftCardData);
}

public function shippingInfo()
{
	$shippingType = $this->functions->ibms_setting("shippingType");
	$currencyId = $this->currentCurrencyId();
	$shippingPriceLimit = unserialize($this->functions->ibms_setting('check_out_shiping_price_limit'));
	@$shippingPriceLimit = $shippingPriceLimit[$currencyId];

	$array = array(
		"shippingType" => $shippingType,
		"priceLimit" => $shippingPriceLimit,
	);

	return $array;
}

public function shippingPriceByClass($shippingId, $oldClassPrice = 0)
{
	$shippingPrice = 0;
	if ($this->functions->developer_setting('shipping_class') == '1') {
		if (empty($shippingId)) {
//Shipping by weight
		} else {
			$currencyId = $this->currentCurrencyId();
			$sql = "SELECT * FROM `shipping_class` WHERE `id` = '$shippingId'";
			$shippingData = $this->dbF->getRow($sql);
			$shippingDataPrice = unserialize($shippingData['price']);

			$shippingPrice = $shippingDataPrice[$currencyId];
			if ($oldClassPrice < $shippingPrice) {
				$oldClassPrice = $shippingDataPrice[$currencyId];
			}
		}
	}
	$array = array(
		"price" => $shippingPrice,
"classPrice" => $oldClassPrice, //high price of all classes...
);
	return $array;
}

public function getDealProductOrders($info)
{
//working here,, get all product info and save in order product table info,, and show array in order view
	$pName = array();
	foreach ($info as $val) {
		$pId = $val['pId'];
		$scaleId = $val['scaleId'];
		$colorId = $val['colorId'];
		$pName[$pId]['pIds'] = "$pId-$scaleId-$colorId";
		$pName[$pId]['name'] = $this->getProductFullNameWeb($pId, $scaleId, $colorId);
	}
	return $pName;
}

public function emptyCart()
{

	$userId = $this->webUserId();
	$sql    = " DELETE FROM `cart` WHERE `userId` = ?  ";

	if ($userId == 0) {
# means not logged in user
		$userId = $this->webTempUserId();
		$sql    = " DELETE FROM `cart` WHERE `tempUser` = ? ";

	}

	$this->dbF->setRow($sql, array($userId));
	$result = ( $this->dbF->rowCount > 0 ) ? TRUE : FALSE;
	return $result;
}

public function updateCartStatus()
{
//Update status so we can come back from the order page and resume this cart session
	$userId     = $this->webUserId();
	$tempUserId = $this->webTempUserId();
	$sql        = " UPDATE `cart` SET `order_status` = ? WHERE `userId` = ? AND `tempUser` = ? ";
	$this->dbF->setRow($sql,array('1',$userId,$tempUserId));
}

public function order_remove_unprocessed()
{

# delete old unprocessed order, person has submitted cart button again, delete old order and create new order.
	$sql    = "
	DELETE FROM `order_invoice` WHERE `order_invoice_pk` IN (
	SELECT * FROM (
	SELECT oi.order_invoice_pk FROM `order_invoice` oi
	INNER JOIN `cart` c ON c.tempUser = oi.orderUser
	WHERE oi.orderStatus = 'inComplete' AND c.order_status = 1
) as t
)
";
$this->dbF->setRow($sql);
// var_dump($this->dbF->rowCount);
}



/**
* @param string $ulClass
* @param string $liClass
* @param string $aClass
* @param string $divClass
* @return string
* First ul Li list
*/
public function getCategorySingle($ulClass = '', $liClass = '', $aClass = '', $divClass = '')
{
	$sql = "SELECT * FROM tree_struct JOIN tree_data
	ON  tree_struct.id = tree_data.id
	WHERE lvl  = '1' ORDER BY pos ASC";
	$data = $this->dbF->getRows($sql);
	$webLang = currentWebLanguage();

	$array = array();
	$data2 = $data;
	foreach ($data2 as $val) {
		$name = $val['nm'];
		$array[$name] = '';
	}
	global $_e;
	$_e = $this->dbF->hardWordsMulti($array, $webLang, 'webCategory');

	$link = WEB_URL . "/" . $this->db->pCategory;

	$temp = "<ul class='$ulClass'>";
	foreach ($data2 as $val) {
		$name = $_e[$val['nm']];

//$link = WEB_URL."/products?cat=".$val['id'];
$linkT = $link . "$val[id]-$val[nm]"; // pCategory slug

//print Structure
$temp .= "<li class='$liClass'>
<a href='$linkT' class='$aClass'>
<div  class='$divClass'>$name</div>
</a>
</li>";

}
$temp .= "</ul>";
return $temp;
}

public function getCategoryQueryData($level, $parentId = false)
{
	$parent = '';
	if ($parentId != false) {
		$parent = " AND pid = '$parentId'";
	}
	if ($level === false) {
		$level = "";
	} else {
		$level = " AND lvl = '$level'";
	}
	$sql = "SELECT * FROM tree_struct JOIN tree_data
	ON  tree_struct.id = tree_data.id
	WHERE tree_data.id != '1' $level $parent ORDER BY pos ASC";
	$data = $this->dbF->getRows($sql);
	return $data;
}

private function categoryArraySearch($data, $pid)
{
	$temp = array();
	foreach ($data as $val) {
		if ($val['pid'] == $pid) {
$val['has-sub'] = '0'; // 2nd and 3rd array initital value
$temp[$val['nm']] = $val;
}
}
return $temp;
}

public function getCategoryArray($dept = 3)
{
	$data = $this->getCategoryQueryData(false);
	$cat = array();
	foreach ($data as $val) {
		if ($val['lvl'] == '1') {
$val['has-sub'] = '0'; // initial 1st array value
$cat[$val['nm']] = $val;
}
}

if ($dept > 1) {
	foreach ($cat as $key => $val) {
		$c2 = $this->categoryArraySearch($data, $val['id']);
		if (!empty($c2)) {
$cat[$key]['has-sub'] = '1'; // 1st array
$cat[$key][$val['nm']] = $c2;
if ($dept > 2) {
	foreach ($c2 as $key3 => $val3) {
		$c3 = $this->categoryArraySearch($data, $val3['id']);
		if (!empty($c3)) {
			$cat[$key][$val['nm']][$val3['nm']][$val3['nm']] = $c3;
$cat[$key][$val['nm']][$key3]['has-sub'] = '1'; // 2nd array
}
}
}//if >2 end

} // if c2 not empty
else {
	$cat[$key]['has-sub'] = '0';
}
}//foreach
}//if >1


$this->makeCategoryArrayMultiLanguageE($data);
//$this->dbF->prnt($cat);
return $cat;
}

private function makeCategoryArrayMultiLanguageE($data, $lang = false)
{
	if ($lang == false) {
		$lang = currentWebLanguage();
	}

	$_w = array();
	foreach ($data as $val) {
		$_w[$val['nm']] = '';
	}

	$_e = $this->dbF->hardWordsMulti($_w, $lang, 'product category');

}

/**
* get url from .htaccess and make it proper parameter.
*/
public function setProductSlug()
{
	if (isset($_GET['catSlug'])) {
		$slug = ($_GET['catSlug']);
		if (stristr($slug, "-")) {
			$slug = explode("-", $slug, 2);
			$_GET['catId'] = $slug[0];
			@$_GET['cat'] = $slug[1];
		} else if (intval($slug) > 0) {
			$_GET['catId'] = $slug;
		} else {
			$_GET['cat'] = $slug;
		}
	}
}

public function getCategoryList($ulClass = '', $liClass = '', $aClass = '', $spanClass = '')
{
	$data = $this->getCategoryArray();
	$webLang = currentWebLanguage();
	$page = basename($_SERVER['PHP_SELF']);
	if ($page == 'search' || $page == 'search.php') {
		$page = 'search';
	} else {
		$page = 'products';
		$baseLink = WEB_URL . "/" . $this->db->pCategory;
	}


	if (isset($_GET['catId'])) {
		@$cat = $_GET['catId'];
	} else {
		@$cat = $_GET['cat'];
	}


	global $_e;
	$temp1 = '';
	$is3Active = false;
	$is2ndActive = false;
	$display = '';

	$temp = "<!--1st array-->
	<ul class='$ulClass'>";
	foreach ($data as $key => $val) {
		$active = '';
		$aActive = '';
//var_dump($val);
		$temp2 = '';
		if (isset($val['has-sub']) && $val['has-sub'] == '1') {
//2nd array
			$temp2V = '';
			foreach ($data[$key][$key] as $key2 => $val2) {
				$temp3 = '';
				if (isset($val2['has-sub']) && $val2['has-sub'] == '1') {
//3rd array

					$temp3V = '';
					foreach ($data[$key][$key][$key2][$key2] as $key3 => $val3) {
						if ($cat == $val3['id'] || $cat == $val3['nm']) {
							$active = 'active';
							$aActive = 'aActive';
							$is3Active = true;
						} else {
							$active = '';
							$aActive = '';
						}
						$name3 = $_e[$val3['nm']];
						if ($page == 'products') {
$link3 = $baseLink . "$val3[id]-$val3[nm]"; // pCategory slug
} else {
	$link3 = WEB_URL . "/$page?cat=" . $val3['id'];
}

//print Structure
$temp3V .= "<li class='" . $liClass . "_3 $active'>
<a href='$link3' class='" . $aClass . "_3 $aActive'>$name3
</a><span><!-- drop --></span>";

$temp3V .= "</li>";
}
$display = '';
if ($is3Active) {
	$display = "style='display:block'";
}
$temp3 .= "<!--2nd array--><ul class='" . $ulClass . "_3' $display>
$temp3V
</ul><!--3rd array End-->";
//3rd array end
}

//2nd array
$name2 = $_e[$val2['nm']];
if ($page == 'products') {
$link2 = $baseLink . "$val2[id]-$val2[nm]"; // pCategory slug
} else {
	$link2 = WEB_URL . "/$page?cat=" . $val2['id'];
}
$active = '';
$aActive = '';
if ( $cat == $val2['id'] || $cat == $val2['nm'] || $is3Active ) {
	$active = 'active';
	$aActive = 'aActive';
	$is2ndActive = true;
}
$lisHasSub = '';
if ( $val2['has-sub'] == '1' ) {
	$lisHasSub = 'has-sub';
}

//print Structure
$temp2V .= "<li class='" . $liClass . "_2 $lisHasSub $active'>
<a href='$link2' class='" . $aClass . "_2 $aActive'>$name2
</a><span><!-- drop --></span>
$temp3";
$is3Active = false;

$temp2V .= "</li>";
}

$display = '';
if ($is2ndActive) {
	$display = "style='display:block'";
}
$temp2 .= "<!--2nd array--><ul class='" . $ulClass . "_2' $display>
$temp2V
</ul><!--2nd array End-->";
//2nd array end
}

//1st array
$active = '';
$aActive = '';
if ($cat == $val['id'] || $cat == $val['nm'] || $is2ndActive) {
	$active = 'active';
	$aActive = 'aActive';
	$is2ndActive = true;
}


$name1 = $_e[$val['nm']];
if ($page == 'products') {
$link1 = $baseLink . "$val[id]-$val[nm]"; // pCategory slug
} else {
	$link1 = WEB_URL . "/$page?cat=" . $val['id'];
}

$lisHasSub = '';
if ($val['has-sub'] == '1') {
	$lisHasSub = 'has-sub';
}


$temp1 .= "<li class='$liClass $lisHasSub $active' $display>
<a href='$link1' class='$aClass $aActive'>
<span  class='$spanClass'>$name1</span>
</a><span><!-- drop --></span>
$temp2";
$is2ndActive = false;
$temp1 .= "</li>";
}
$temp .= "$temp1 </ul><!--1st array End-->
<input type='hidden' class='activeCategory' value='$cat'/>
";
return $temp;
}

public function getCategoryList1()
{
	$temp = "<div class='u-vmenu'>";
	$temp .= $this->getCategoryList();
	$temp .= "</div>";
//$temp .= '<link rel="stylesheet" type="text/css" href="' . WEB_URL . '/css/category/categoryStyle1.css"/>';
	$temp .= '<script>
	$(document).ready(function() {
		$(".u-vmenu").vmenuModule({
			Speed: 200,
			autostart: false,
			autohide: true
			});
			});
			</script>';
			return $temp;
		}

		public function getCategoryList2()
		{
			$temp = "<div id='categoryMenu2'>";
			$temp .= $this->getCategoryList();
			$temp .= "</div>";
			$temp .= '<link rel="stylesheet" type="text/css" href="' . WEB_URL . '/css/category/categoryStyle2.css"/>';
			$temp .= '<script type="text/javascript" src="' . WEB_URL . '/css/category/category2.js"></script>';
			return $temp;
		}

		public function getDistinctColor()
		{
			if ($this->functions->developer_setting('product_color') == '0') {
				return "";
			}
			global $_e;
			$sql = "SELECT DISTINCT(proclr_name) as name , propri_id FROM `product_color` GROUP BY proclr_name ORDER BY propri_id ASC";
			$data = $this->dbF->getRows($sql);
			$temp = "
			<div class='padding-0 pColorSearch'>
			<div class='heading'>
			<span>" . $_e['Color'] . "</span>
			</div>
			<div class='container-fluid colorCheckBoxes text-center'>

			";
			$color = array();
			@$selected = $_GET['color'];
			if ($selected == "" || $selected == false) {
				$selected = array();
			} else {
				$selected = explode(",", $selected);
			}
			foreach ($data as $val) {
				$name = $val['name'];
				$name = str_replace(" ", "", $name);
				$name = str_replace("#", "", $name);
				$name = HexToColorName($name);
				if (in_array($name, $color)) {
					continue;
				}
				$checked = "";
				if (in_array($name, $selected)) {
					$checked = "checked = 'checked'";
				}
				$color[] = $name;
				$id = "$val[propri_id]";
				$temp .= "<label class='grow'>
				<input type='checkbox' name='colorCheckbox' value='$name' $checked>
				<span class='checkColorDiv ' style='background:$name;'></span>
				</label>
				";
			}
			$temp .= "</div>
			</div><!--.pColorSearch end-->";
			return $temp;

		}

		public function getDistinctSize()
		{
			if ($this->functions->developer_setting('product_Scale') == '0') {
				return "";
			}
			global $_e;
			$sql = "SELECT DISTINCT(prosiz_name) as name , prosiz_id FROM `product_size` GROUP BY prosiz_name ORDER BY prosiz_id ASC";
			$data = $this->dbF->getRows($sql);
			$temp = "<div class='padding-0 pSizeSearch'>
			<div class='heading'>
			<span>" . $_e['Size'] . "</span>
			</div>
			<div class='container-fluid sizeCheckBoxes  text-center'> ";
			$size = array();
			@$selected = $_GET['size'];
			if ($selected == "" || $selected == false) {
				$selected = array();
			} else {
				$selected = explode(",", $selected);
			}
			foreach ($data as $val) {
				$name = $val['name'];
				if (in_array($name, $size)) {
					continue;
				}
				$checked = "";
				if (in_array($name, $selected)) {
					$checked = "checked = 'checked'";
				}
				$size[] = $name;
				$id = "$val[prosiz_id]";
				$temp .= "
				<label class='grow'>
				<input type='checkbox' name='colorSizeBox' value='$name' $checked>
				<span class='checkSizeDiv' style=''> $name </span>
				</label>
				";
			}
			$temp .= "</div>
			</div><!--.pSizeSearch end-->";
			return $temp;

		}



		public function getCategoryName($catId)
		{
			$sql = "SELECT * FROM `categories` WHERE id = '$catId'";
			$data = $this->dbF->getRow($sql);
			$webLang = currentWebLanguage();
			if ($this->dbF->rowCount > 0) {
				$name = translateFromSerialize($data['name']);
				$array = array($name => '');
				global $_e;
				$_e = $this->dbF->hardWordsMulti($array, $webLang, 'webCategory');
				$name = $_e[$name];
				return $name;
			} else {
				return false;
			}
		}
// public function getCategoryName($catId)
// {
//     $sql = "SELECT * FROM tree_data WHERE id = '$catId'";
//     $data = $this->dbF->getRow($sql);
//     $webLang = currentWebLanguage();
//     if ($this->dbF->rowCount > 0) {
//         $name = $data['nm'];
//         $array = array($name => '');
//         global $_e;
//         $_e = $this->dbF->hardWordsMulti($array, $webLang, 'webCategory');
//         $name = $_e[$name];
//         return $name;
//     } else {
//         return false;
//     }
// }

		public function getCategoryId($catName)
		{
			$sql = "SELECT * FROM tree_data WHERE nm = '$catName'";
			$data = $this->dbF->getRow($sql);
			if ($this->dbF->rowCount > 0) {
				$id = $data['id'];
				return $id;
			} else {
				return false;
			}
		}

		public function getPrinceRange()
		{
			$sql = "SELECT MIN(propri_price) as min,MAX(propri_price) as max FROM `product_price`";
			$data = $this->dbF->getRow($sql);
			$array = array("min" => 0, "max" => 0);
			if ($this->dbF->rowCount > 0) {
				$array['min'] = $data['min'];
				$array['max'] = $data['max'];
			}
			$array['cMin'] = $array['min'];
			if (isset($_GET['pMin'])) {
				$array['cMin'] = $_GET['pMin'];
			}
			$array['cMax'] = $array['max'];
			if (isset($_GET['pMax'])) {
				$array['cMax'] = $_GET['pMax'];
			}
			return $array;
		}

		public function navigation()
		{
			$totalP = floatval($this->totalProductFrNav);
			$limit = floatval($this->productLimit);
			$noOfPage = ceil($totalP / $limit);
			if ($noOfPage < 2) {
//return false;
			}

			$current = isset($_GET['page']) ? intval($_GET['page']) : 0;
			$parameters = '';

			$hasPara = false;
			foreach ($_GET as $key => $val) {
				if ($key == 'page') {
					continue;
				}
				if ($hasPara) {
					$parameters .= "?$key=$val";
				} else {
					$parameters .= "&$key=$val";
				}
				$hasPara = true;
			}


			$start = 0;
			$end = $noOfPage;
			$i = 1;
			$pagi = '';


//Previous Link
			if ($i == 1 && $current > $i) {
				$page = "page=" . ($current - 1);
				$link = WEB_URL . "/products?$parameters$page";
				$pagi .= '<li>
				<a href="' . $link . '" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				</a>
				</li>';
			}

//If page 6 then show ... before pages
			if ($current > 5 && $noOfPage > $current + 3) {
				$start = $current - 3;
				$pagi .= '<li class=""><a href="">...</a></li>';
			}

//If page 6 then End on where show ... before pages
			if ($current > 5 && $noOfPage > $current + 3) {
				$end = $current + 3;
			}


			$more = false;
			for ($i = $start; $i <= $end; $i++) {
				$active = '';
				if ($i == $current) {
					$active = 'active';
				}

				if ($hasPara) {
					$parameters .= "&";
				}
				$page = "page=" . $i;
				$link = WEB_URL . "/products?$parameters$page";

				$pagi .= '<li class="' . $active . '"><a href="' . $link . '">' . $i . '</a></li>';
			}

			for ($i = $noOfPage - 3; $i <= $noOfPage; $i++) {
				$active = '';
				if ($i == $current) {
					$active = 'active';
				}

				if ($hasPara) {
					$parameters .= "&";
				}
				$page = "page=" . $i;
				$link = WEB_URL . "/products?$parameters$page";

				$pagi .= '<li class="' . $active . '"><a href="' . $link . '">' . $i . '</a></li>';
			}


			if ($i == $noOfPage && $i > $current) {
				$parameters .= "page=" . ($current + 1);
				$link = WEB_URL . "/products?$parameters";
				$pagi .= '<li>
				<a href="' . $link . '" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				</a>
				</li>';
			}


			?>
			<div class="clearfix"></div>


			<nav>
				<ul class="pagination">
					<?php echo $pagi; ?>
				</ul>
			</nav>

		<?php }

		public function productSclaeColorReport($pId)
		{
//get info is product has sclae or colors
			$sql = "SELECT (SELECT count(propri_id) FROM product_color  WHERE `proclr_prodet_id` = '$pId' GROUP BY proclr_prodet_id) as color,
			(SELECT count(prosiz_id) FROM product_size  WHERE `prosiz_prodet_id` = '$pId' GROUP BY prosiz_prodet_id) as size ";
			$data = $this->dbF->getRow($sql);

			$scale = false;
			$color = false;

			if (!empty($data['size'])) {
				$scale = true;
			}
			if (!empty($data['color'])) {
				$color = true;
			}


//make report
			$report = "";
			if ($scale) {
				$report .= "<input type='hidden' id='hasScaleInventory_$pId' value='1' />";
			} else {
				$report .= "<input type='hidden' id='hasScaleInventory_$pId' value='0' />";
			}

			if ($color) {
				$report .= "<input type='hidden' id='hasColorInventory_$pId' value='1' />";
			} else {
				$report .= "<input type='hidden' id='hasColorInventory_$pId' value='0' />";
			}

			$array = array();
			$array['report'] = $report;
			$array['scale'] = $scale;
			$array['color'] = $color;
			return $array;
		}

		public function inventoryReport($pId)
		{
/*
Info Of is size & color insert in product either it is out of stock
Or either it is out of store,
*/
$sql = "SELECT * FROM product_inventory  WHERE `qty_product_id` = '$pId'";
$data = $this->dbF->getRows($sql);
$scale = false;
$color = false;
foreach ($data as $val) {
	if ($val['qty_product_scale'] > 0) {
		$scale = true;
	}
	if ($val['qty_product_color'] > 0) {
		$color = true;
	}
}

//make report
$report = "";
if ($scale) {
	$report .= "<input type='hidden' id='hasScaleInventory_$pId' value='1' />";
} else {
	$report .= "<input type='hidden' id='hasScaleInventory_$pId' value='0' />";
}

if ($color) {
	$report .= "<input type='hidden' id='hasColorInventory_$pId' value='1' />";
} else {
	$report .= "<input type='hidden' id='hasColorInventory_$pId' value='0' />";
}

$array = array();
$array['report'] = $report;
$array['scale'] = $scale;
$array['color'] = $color;
return $array;
}

public function getColorsDiv($pId, $storeId = false, $pPrice = false, $currencyId = false, $currencySymbol = false, $discountP = false, $hasScale = false)
{
//Collect Data
	if ($currencyId == false || $currencyId == "") {
		$currencyId = $this->currentCurrencyId();
	}

	if ($storeId == false || $storeId == "") {
		$storeId = $this->getStoreId();
	}
	if ($currencySymbol == false || $currencySymbol == "") {
		$currencySymbol = $this->currentCurrencySymbol();
	}

	if ($pPrice == false || $pPrice == "") {
		$pPriceData = $this->productF->productPrice($pId, $currencyId);
//$pPriceData Return , currency id,international shipping, price, id,
		$priceDefault = $pPriceData['propri_price'];
		$pPrice = $priceDefault;
	} else {
		$priceDefault = $pPrice;
	}

	if ($discountP == false || $discountP == "") {
		$discount = $this->productF->productDiscount($pId, $currencyId);
		@$discountFormat = $discount['discountFormat'];
		@$discountP = $discount['discount'];
		$discountPrice = $this->productF->discountPriceCalculation($pPrice, $discount);
		$newPrice = $pPrice - $discountPrice;
	}

//Make Color Divs,,
	$select_color = '';
	$colorDiv = '';
	$colorImg = '';

	$firstColorId = 0;
	$firstColor = true;
//just color sql, to show product colors,,,
	$pColorData = $this->productF->colorSQL($pId);
// var_dump($pColorData);

	foreach ($pColorData as $val) {
		$colorName = $val['proclr_name'];
		$colorName = str_replace('#', "", $colorName);
		$colorName = str_replace(' ', "", $colorName);
		$colorId = $val['propri_id'];

		$scalesInColor = '';
		$colorInventory = '';
		if ($hasScale) {
//size in color... get Sizes That are in this Color
			$sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
			AND qty_product_id = '$pId'
			AND qty_product_color = '$colorId'
			AND qty_item > 0";
			$colorSizeData = $this->dbF->getRows($sql);

			foreach ($colorSizeData as $val2) {
				$scaleIdInColor = $val2['qty_product_scale'];
				$scalesInColor .= ".sizeId_$scaleIdInColor,";
			}
			$scalesInColor = trim($scalesInColor, ",");
//size in color end

		} else {
//if not scale then give inventory detail direct in color, else it will load ajax inventory
//size in color... get Sizes That are in this Color
			$sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
			AND qty_product_id = '$pId'
			AND qty_product_color = '$colorId'
			AND qty_item > 0";
			$colorSizeData = $this->dbF->getRow($sql);
			$colorInventory = $colorSizeData['qty_item'];
			$colorInventory = ' ' . $colorInventory . '';
			$colorInventory = trim($colorInventory);
		}

		if ($scalesInColor == "" && $colorInventory == "" && $this->functions->developer_setting('product_check_stock') == '1') {
// if color inventory and scale invoentory not found
//continue;
		}

		$colorData  = $this->productF->colorPrice($colorId, $currencyId, $pId);
		$colorPrice = $colorData['proclr_price'];

//First Color Id get, For Auto Select or Call Ajax on load
		if ($firstColor) {
			$firstColor = false;
			$firstColorId = $colorId;
			$checked = "checked";
		} else {
			$checked = "";
		}
		$onclick = '';
		if ($this->comparePage == false) {
			$onclick = 'onclick="productColorPriceUpdate(' . $pId . ',' . $colorId . ');';
		}

		$no_inv_class = '';
		if(empty($colorInventory)){
			$no_inv_class = 'no_stock no_color_stock' ;
			$colorInventory = 0;
		}



/*
$colorDiv .= '<input type="radio" name="colorSelect_' . $pId . '" id="' . $colorName . '_' . $pId . '"
data-colorInv="' . $colorInventory . '" data-price="' . $colorPrice . '" ' . $checked . ' data-id="' . $colorId . '" value="' . $colorName . '"
class="productRadioHidden colorSelect_' . $pId . ' colorId_' . $colorId . '" style="display:hidden"/>

<div class="bgColorActive grow '.$no_inv_class.' colorId_' . $colorId . ' colorDiv_' . $pId . '"
data-colorInv="' . $colorInventory . '" data-price="' . $colorPrice . '" data-scales="' . $scalesInColor . '" data-id="' . $colorId . '" data-name="' . $colorName . '"
style="display:block" >
<label for="' . $colorName . '_' . $pId . '">
<div class="colors_in_divs grow "
data-price="' . $colorPrice . '" data-scales="' . $scalesInColor . '" data-id="' . $colorId . '" data-name="' . $colorName . '"
style="background-color:#' . $colorName . ';background-color:' . $colorName . ';"
' . $onclick . '" style="display:block">
</div>
</label>
</div>
';*/

$colorName_uppercase = strtoupper($colorName);
$colorDiv .= '

<li><input type="radio" name="colorSelect_' . $pId . '" id="' . $colorName . '_' . $pId . '"
data-colorInv="' . $colorInventory . '" data-price="' . $colorPrice . '" ' . $checked . ' data-id="' . $colorId . '" value="' . $colorName . '"
class="productRadioHidden colorSelect_' . $pId . ' colorId_' . $colorId . '" style="display:hidden"/>
<a><div class="bgColorActive'.$no_inv_class.' colorId_' . $colorId . ' colorDiv_' . $pId . '"
data-colorInv="' . $colorInventory . '" data-price="' . $colorPrice . '" data-scales="' . $scalesInColor . '" data-id="' . $colorId . '" data-name="' . $colorName . '"
style="display:inline-block" >
<label for="' . $colorName . '_' . $pId . '">
<div class="colors_in_divs"
data-price="' . $colorPrice . '" data-scales="' . $scalesInColor . '" data-id="' . $colorId . '" data-name="' . $colorName . '"
style="background-color:#' . $colorName . ';background-color:' . $colorName . ';"
' . $onclick . '" style="display:block">
</div>
</label>
</div> <div class = "color_name"> ' . $colorName_uppercase . ' </div>
</a>
</li>

';

}

if ($this->comparePage == false && $pColorData != array() ) {
	$colorDiv .= "<script>
	$(document).ready(function(){
		productColorPriceUpdate('$pId','$firstColorId');
//productPriceUpdate('$pId'); use for scale price update
		});
		</script>";
	}

	return $colorDiv;
}


public function get_product_stock($pId){
	$store_id   = $this->getStoreId();
	$sql        = "SELECT * FROM `product_inventory` WHERE
	qty_store_id = '$storeId'
	AND qty_product_id = '$pId'
	AND qty_item > 0";
	$qtyData = $this->dbF->getRows($sql);
}


public function getScalesDiv($pId, $storeId = false, $currencyId = false, $currencySymbol = false, $hasColor)
{
	$size = '';
	$firstScale = true;
	if ($hasColor == false) {
//Collect Data
		if ($currencyId == false || $currencyId == "") {
			$currencyId = $this->currentCurrencyId();
		}

		if ($storeId == false || $storeId == "") {
			$storeId = $this->getStoreId();
		}
		if ($currencySymbol == false || $currencySymbol == "") {
			$currencySymbol = $this->currentCurrencySymbol();
		}

//Get Color Inventory
//size in color... get Sizes That are in this Color
		$sql = "SELECT * FROM `product_inventory` WHERE qty_store_id = '$storeId'
		AND qty_product_id = '$pId'
		AND qty_product_color = '0'
		AND qty_item > 0";
//$colorQtyData = $this->dbF->getRows($sql);
//var_dump($colorQtyData);

		if ( $this->comparePage == false ) {
			$size .= "<script>
			$(document).ready(function(){
				try {
					productPriceUpdate('$pId');
					} catch(e){

					}
					});
					</script>";
				}

			}

			$sizeData = $this->productF->scaleWithInventory($pId, $currencyId, $storeId, $hasColor);
// var_dump($sizeData);

			foreach ($sizeData as $val) {
				$class = '';
				if (empty($val['hasInventory']) && $this->functions->developer_setting('product_check_stock') == '1') {
//check if inventory not found, then continue, if inventory unlimit then condition false
					if ($this->comparePage == false) {
//this work only when product has only size, no color
// continue; //dont show size if its not has any stock
					}

					if($hasColor == false){
						$class = 'no_stock';
					}


				}

				$sizeName = $val['prosiz_name'];
				$sizePrice = $val['prosiz_price'];
				$sizeId = $val['prosiz_id'];
				@$sizeInventoryId = $val['inventoryScaleId'];
// if (empty($sizeInventoryId) && $this->functions->developer_setting('product_check_stock') == '0') {
				if (empty($sizeInventoryId)) {
@$sizeInventoryId = $sizeId; //i hope both are same.. 24 / 6 /2015
}
if ($firstScale) {
	$checked = "checked";
	$firstScale = false;
} else {
	$checked = "";
}

$onclick = '';
if ($this->comparePage == false) {
	$onclick = 'onclick="productPriceUpdate(' . $pId . ');';
}



$size .= $this->getScaleDivFormat($pId, $sizeName, $sizeId, $sizePrice, $sizeInventoryId, $checked, $onclick,$class);


}
return $size;
}

// public function getScaleDivFormat($pId, $sizeName, $sizeId, $sizePrice, $sizeInventoryId, $checked, $onclick,$class = '')
// {

//     $size = '<input type="radio" name="sizeSelect_' . $pId . '" id="' . $sizeName . '_' . $pId . '"
//                         data-price="' . $sizePrice . '"  ' . $checked . '  data-id="' . $sizeInventoryId . '" value="' . $sizeName . '"
//                         class="productRadioHidden sizeSelect_' . $pId . ' sizeId2_' . $sizeId . '"/>

//                         <div class="bgSizeActive size_in_divs grow sizeId_' . $sizeInventoryId . ' sizeDiv_' . $pId . '  '.$class.'  " ' . $onclick . '">
//                             <label for="' . $sizeName . '_' . $pId . '">' . $sizeName . '
//                             </label>
//                         </div>
//                         ';

//     return $size;
// }


/*Page: Product Detail
Select Size Option Format Can be changed from Here
*/

public function getScaleDivFormat($pId, $sizeName, $sizeId, $sizePrice, $sizeInventoryId, $checked, $onclick,$class = '')
{

	$size = '<li><input type="radio" name="sizeSelect_' . $pId . '" id="' . $sizeName . '_' . $pId . '"
	data-price="' . $sizePrice . '"  ' . $checked . '  data-id="' . $sizeInventoryId . '" value="' . $sizeName . '"
	class="productRadioHidden sizeSelect_' . $pId . ' sizeId2_' . $sizeId . '"/>

	<a>    <div class="bgSizeActive size_in_divs sizeId_' . $sizeInventoryId . ' sizeDiv_' . $pId . '  '.$class.'  " ' . $onclick . '">
	<label for="' . $sizeName . '_' . $pId . '" style="display: block; margin: 0px !important; border-radius: 0px !important; text-align: left; width: 100%; ">   <div class = "color_name size">      ' . $sizeName . '  </div>
	</label>
	</div></a>
	</li> ';

	return $size;
}

public function productViewCount($pId)
{
	if (!isset($_SESSION['webUser']['productView'][$pId])) {
		$sql = "UPDATE proudct_detail SET `view` = `view`+1 WHERE prodet_id = '$pId'";
		$this->dbF->setRow($sql);
		$_SESSION['webUser']['productView'][$pId] = 'view';
	}
}

public function productDealViewCount($pId)
{
	if (!isset($_SESSION['webUser']['dealView'][$pId])) {
		$sql = "UPDATE product_deal SET `view` = `view`+1 WHERE id = '$pId'";
		$this->dbF->setRow($sql);
		$_SESSION['webUser']['dealView'][$pId] = 'view';
	}
}


public function productMetaSeo($array)
{
	global $seo;
	if (isset($array['title'])) {
		if ($seo['title'] == '' || $seo['reWriteTitle'] == '0') {
			$seo['title'] = strip_tags($array['title']);
		}
	}
	if (isset($array['description'])) {
		if ($seo['description'] == '' || $seo['default'] == '1') {
			$seo['description'] = strip_tags($array['description']);
		}
	}
	if (isset($array['image'])) {
		$seo['image'] = strip_tags($array['image']);
	}
	if (isset($array['price'])) {
		$seo['price'] = strip_tags($array['price']);
	}
	if (isset($array['currency'])) {
		$seo['currency'] = strip_tags($array['currency']);
	}
	if (isset($array['shipping'])) {
		$seo['shipping'] = strip_tags($array['shipping']);
	}

}

public function referToFriend($pId)
{
/*
* where this open on click
* use: data-toggle="modal" data-target="#referToFriend"
* */
global $_e;
$token = $this->functions->setFormToken('referToFriend', false);
$body = '<div class="container-fluid">
<form  action="" method="post" class="form-horizontal"> ' . $token . '
<input type="hidden" name="pId" value="' . $pId . '" />
<div class="form-group">
<div class="container-fluid">
<div class="sales_text">' . $_e['Refer to a Friend Description'] . '</div>
<label class="col-sm-2 control-label" for="referToFriendInput">' . $_e['Email'] . '</label>
<div class="col-sm-10">
<input type="email" required name="email" class="form-control" id="referToFriendInput" placeholder="">
</div>
</div>
</div>
<button type="submit" name="referToFriend" class="btn btn-default">' . $_e['Refer To Friend'] . '</button>
</form>
</div>';
$temp = $this->functions->blankModal($_e["Refer To Friend"], 'referToFriend', $body, $_e['Close']);
return $temp;
}

public function productSubscribeOnSale($pId)
{
/*
* where this open on click
* use: data-toggle="modal" data-target="#ProductSubscribe"
* */
global $_e;
$token = $this->functions->setFormToken('ProductSubscribe', false);
$body = '<div class="container-fluid">

<form  action="" method="post" class="form-horizontal"> ' . $token . '
<input type="hidden" name="insert[p_id]" value="' . $pId . '" />
<input type="hidden" name="insert[type]" value="sale" />
<div class="form-group">
<div class="container-fluid">
<div class="sales_text">' . $_e['Sale Trigger Form'] . '</div>
<label for="productSubscribeOnSale" class="col-sm-2 control-label">' . $_e['Email'] . '</label>
<div class="col-sm-10">
<input type="email" required name="insert[email]" class="form-control" id="productSubscribeOnSale" placeholder="">
</div>
</div>
</div>
<button type="submit" name="ProductSubscribe" class="btn themeButton">' . $_e['Subscribe'] . '</button>
</form>
</div>';
$temp = $this->functions->blankModal($_e["Subscribe"], 'ProductSubscribe', $body, $_e['Close']);
return $temp;
}

public function productSubscribeOnSaleSubmit()
{
	global $_e;
	if (isset($_POST['insert']) && isset($_POST['ProductSubscribe'])) {
		if ( ! $this->functions->getFormToken('ProductSubscribe') ) {
			return false;
		}

		$sql    = "SELECT * FROM product_subscribe WHERE `p_id` = ? AND `type`=?  AND `email`=? ";
		$this->dbF->getRow($sql, $_POST['insert'] ,true , true);
		$row_exists = $return = false;
		if ( ! $this->dbF->rowCount ) {
			$return = $this->functions->formInsert('product_subscribe', $_POST['insert']);
		} else {
			$row_exists = TRUE;
		}

		if ($return) {
			return $this->functions->jAlertifyAlert($_e["Subscribe Successfully"], false);
		} else {
			$hardword = ( $row_exists == TRUE) ? $_e['You are already subscribed on this sale offer'] : $_e['Subscribe Fail'];
			return $this->functions->jAlertifyAlert($hardword,false);
		}
	}
	return "";
}


public function subscribed_on_stock_availability($pId){
/*
* where this open on click
* use: data-toggle="modal" data-target="#StockSubscription"
* */
global $_e;
$token = $this->functions->setFormToken('StockSubscription',false);
$body = '<div class="container-fluid">

<form  action="" method="post" class="form-horizontal"> '.$token.'
<input type="hidden" name="insert[p_id]" value="'.$pId.'" />
<input type="hidden" name="insert[color_id]" class="out_of_stock color_id" value="0" />
<input type="hidden" name="insert[scale_id]" class="out_of_stock scale_id" value="0" />
<input type="hidden" name="insert[store_id]" class="out_of_stock store_id" value="0" />
<input type="hidden" name="insert[type]" value="stock">
<div class="form-group">
<div class="container-fluid">
<div class="sales_text">'.$_e['Stock Trigger Form'].'</div>
<label for="productSubscribeOnStock" class="col-sm-2 control-label">'.$_e['Email'].'</label>
<div class="col-sm-10">
<input type="email" required name="insert[email]" class="form-control" id="productSubscribeOnStock" placeholder="">
</div>
</div>
</div>
<button type="submit" name="StockSubscription" class="btn themeButton">'.$_e['Subscribe'].'</button>
</form>
</div>';
$temp = $this->functions->blankModal($_e['Subscription for stock availability'],'StockSubscription',$body,$_e['Close']);
return $temp;
}


public function subscribed_on_stock_availability_submit(){
	global $_e;
	if(isset($_POST['insert']) && isset($_POST['StockSubscription'])){
		if(!$this->functions->getFormToken('StockSubscription')){return false;}

		$sql = "SELECT * FROM product_subscribe WHERE `p_id` = ? AND `color_id`=? AND `scale_id`=? AND `store_id`=?  AND `type`=?  AND `email`=? ";
		$this->dbF->getRow($sql, $_POST['insert'] ,true , true);
		$row_exists = $return = false;
		if( ! $this->dbF->rowCount){
			$return = $this->functions->formInsert('product_subscribe',$_POST['insert']);
		} else {
			$row_exists = TRUE;
		}

		if($return){
			return $this->functions->jAlertifyAlert($_e['Subscribe Successfully'],false);
		}else{
			$hardword = ( $row_exists == TRUE) ? $_e['You are already subscribed for this product'] : $_e['Subscribe Fail'];
			return $this->functions->jAlertifyAlert($hardword,false);
		}
	}
	return "";
}


public function referToFriendSubmit()
{
	global $_e;
	if (isset($_POST['referToFriend']) && isset($_POST['email'])) {
		if (!$this->functions->getFormToken('referToFriend')) {
			return false;
		}

		@$pId = $_POST['pId'];
		$link = WEB_URL . "/detail.php?pId=$pId";
		if (isset($_GET['pSlug'])) {
			$pId = $_GET['pSlug'];
			$link = WEB_URL . "/" . $this->db->productDetail . $pId;
		}

		@$email = $_POST['email'];
		$mailArray = array('link' => $link);
		$return = $this->functions->send_mail($email, '', '', 'ReferToFriend', '', $mailArray);

		if ($return) {
			return $this->functions->jAlertifyAlert($_e["Email Send Successfully"], false);
		} else {
			return $this->functions->jAlertifyAlert($_e["Email Send Fail"], false);
		}
	}
	return "";
}

public function measurementArray($data, $filedName, $settingName, $returnId = false)
{
	foreach ($data as $key => $val) {
		if ($val['fieldName'] == $filedName && $val['setting_name'] == $settingName) {
			if ($returnId) {
				return $val['id'];
			}
			return $val['setting_value'];
		}
	}

	return "";
}

public function customSizeForm($pId, $customSizeType)
{
	global $_e;
	$sql = "SELECT * FROM p_custom WHERE id = '$customSizeType'";
	$data = $this->dbF->getRow($sql);

	if (!$this->dbF->rowCount) {
		return false;
	}

	$fields = $data['custom_fields'];
	$fields = str_replace(",,", "", $fields);
	$fields = trim($fields, ",");
	$fields = explode(",", $fields);

	$sql = "SELECT * FROM p_custom_setting WHERE c_id = '$customSizeType'";
	$dataFields = $this->dbF->getRows($sql);

	$token = $this->functions->setFormToken('measurementFields', false);
	$token .= '<input type="hidden" name="editId" value="' . $pId . '"/>';

	$form = '';
	$form_fields = array();

	$form_fields[] = array(
		'name' => "customPId",
		'type' => 'hidden',
		'value' => "$pId",
	);

	$form_fields[] = array(
		'name' => "custom_id",
		'type' => 'hidden',
		'value' => "$data[id]",
	);

//Price field, For update if needed
	$form_fields[] = array(
		'name' => "customStore_$pId",
		'type' => 'hidden',
		'value' => $this->getStoreId(),
		'class' => "customStore_$pId",
	);

//Color field, For update if needed
	$form_fields[] = array(
		'name' => "customColor_$pId",
		'type' => 'hidden',
		'value' => '0',
		'class' => "customColor_$pId",
	);


	foreach ($fields as $key => $val) {
		$required = translateFromSerialize($this->measurementArray($dataFields, $val, 'required'));
		$valTemp = translateFromSerialize($this->measurementArray($dataFields, $val, 'name'));
		$form_fields[] = array(
			'label' => $valTemp,
			'name' => "custom[$val]",
			'type' => 'hidden',
			'class' => 'form-control',
			'value' => "",
			'required' => "$required"
		);
	}

//edit link
	if ($data['allowSubmitLater'] == '1') {
		$form_fields[] = array(
			'name' => "customSubmit_later_$pId",
			'type' => 'check',
			'id' => 'custom_check',
			'class' => 'btn btn-danger',
			'value' => "1",
			'option' => array(_u($_e["I Accept"])),
			'format' => "<label class='checkbox-inline'>{{form}} {{option}}</label>",
		);
	}

//Submit Button
	$form_fields[] = array(
		'name' => "submit",
		'id' => 'custom_submit',
		'type' => 'submit',
		'class' => 'btn themeButtonn ',
		'value' => _u($_e['Add To Cart']),
	);

//Make <form, call first or any where then make array index key is 'form',
//now mange more clear, just make format here... no thisFormat work here.
	$form_fields['form'] = array(
		'name' => "form",
		'type' => 'form',
		'class' => "formClass",
		'id' => "customForm_$pId",
		'data' => 'onsubmit="return customFormSubmit(this,' . $pId . ')"',
		'action' => '',
		'disabled' => '',
		'method' => 'post',
		'format' => '<div class="form-horizontal">{{form}}</div>'
	);
	$format = '<div class="form-group">
	<label class="col-sm-2 col-md-3  control-label">{{label}}</label>
	<div class="col-sm-12  col-md-12 col-xs-12 sub_mob_btn">
	{{form}}
	</div>
	</div>';
	$form .= $this->functions->print_form($form_fields, $format, false);

	return $form;


}

public function customSizeArrayFilter($data, $country_id)
{
	foreach ($data as $key => $val) {
		if ($val['currencyId'] == $country_id) {
			return $val['price'];
		}
	}
	return "";
}

public function couponOfferOnVisit()
{
	if (getUserSession('couponOfferMail') == false) {

		if ($this->functions->developer_setting('couponOfferMail') == '1') {
			$couponOfferStatus = $this->functions->ibms_setting('couponOfferEmail');

			if ($couponOfferStatus == '1') {
				setUserSession('couponOfferMail');
/*
* where this open on click
* use: data-toggle="modal" data-target="#ProductSubscribe"
* */
global $_e;
$token = $this->functions->setFormToken('ProductCouponOffer', false);
$body = '<div class="container-fluid pop_up">
<h3 class="my_alert">' . $_e['Enter your email and get Latest Coupons Code'] . '</h3>
<form  action="" method="post" class="form-horizontal"> ' . $token . '
<div class="form-group">
<div class="pop_subscribe">
<label for="productSubscribeOnSale" class="sub_label">' . $_e['Email'] . '</label>
<div class="pop_email">
<input type="email" required name="email" class="form-control" placeholder="">
</div>
</div>
</div>
<button type="submit" name="CouponOfferSubmit" class="btn themeButton pop_btn">' . $_e['Send Coupon Code'] . '</button>
</form>
</div>';
$temp = $this->functions->blankModal($_e["CouponOffer"], 'ProductCouponOffer', $body, $_e['Close']);
$temp .= "<script>
$(document).ready(function(){
	setTimeout(function(){
		$('#ProductCouponOffer').modal('show');
		},5000);
		});
		</script>";
		return $temp;
	}
}

}
$this->couponOfferOnVisitSubmit();
}

private function couponOfferOnVisitSubmit()
{
	if (isset($_POST['CouponOfferSubmit'])) {
		if (!$this->functions->getFormToken('ProductCouponOffer')) {
			return false;
		}

		$email = $_POST['email'];
		$this->functions->send_mail($email, '', '', 'couponOfferEmail', '');
	}
}

public function productReturnOrDefectForm($type = 'return')
{
	global $_e;
//$data varibale was using in admin, on edit -> logs/
	$userId = $this->webClass->webUserId();
	$form_fields = array();
	$token = $this->functions->setFormToken($type . 'Form', false);

	$data = array();

	$form_fields[] = array(
		'type' => 'none',
		'thisFormat' => $token,
	);

	$form_fields[] = array(
		'type' => 'hidden',
		'name' => 'insert[userId]',
		'value' => $userId,
	);

	$form_fields[] = array(
		'type' => 'hidden',
		'name' => 'insert[type]',
		'value' => $type,
	);

	$form_fields[] = array(
		'type' => 'none',
		'thisFormat' => '<div class="form-group">
		<label class="col-sm-5 control-label">' . $_e['User Info'] . '</label>
		</div>',
	);

	$form_fields[] = array(
		'label' => $_e['User Name'],
		'name' => 'insert[name]',
		'required' => "true",
		"value" => getUserSession("name"),
		'type' => 'text',
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'label' => $_e['Email'],
		'name' => 'insert[email]',
		'required' => "true",
		'type' => 'email',
		"value" => getUserSession("email"),
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'label' => $_e['Contact'],
		'name' => 'insert[phone]',
		'required' => "true",
		'type' => 'text',
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'type' => 'none',
		'thisFormat' => "<hr>",
	);

	if ($userId > '0') {
		$sql = "SELECT * FROM `order_invoice` WHERE orderUser = '$userId' AND orderStatus = 'process' ORDER BY order_invoice_pk DESC";
		$invoice = $this->dbF->getRows($sql);
		$invoiceKey = $this->functions->ibms_setting('invoice_key_start_with');
		$array = array("" => "----");
		foreach ($invoice as $val) {
			$array[$val['order_invoice_pk']] = "$invoiceKey $val[order_invoice_pk] (Date : $val[dateTime])";
		}
		$form_fields[] = array(
			'label' => $this->dbF->hardWords('ORDER CODE', false),
			'name' => 'insert[orderId]',
			'required' => "true",
			'array' => $array,
			'type' => 'select',
			'class' => 'form-control',
		);
	} else {
		$form_fields[] = array(
			'label' => $_e['Order Code'],
			'name' => 'insert[orderId]',
			'required' => "true",
			"value" => @$data['orderId'],
			'type' => 'text',
			'class' => 'form-control',
		);
	}

	$form_fields[] = array(
		'label' => $_e['Product Name'],
		'name' => 'insert[products]',
		'required' => "true",
		"value" => @$data['products'],
		'type' => 'textarea',
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'label' => $_e['Number Which Claims In'],
		'name' => 'insert[claimNo]',
		"value" => @$data['claimNo'],
		'type' => 'text',
		'class' => 'form-control',
	);

	$getNewT = $_e['Get New Item'];
	$moneybackT = $_e['Get Money Back'];
	$form_fields[] = array(
		'label' => $_e['Want to switch to another product or get your money back?'],
		'name' => 'insert[switchProduct]',
		'required' => "true",
		"value" => "1,2",
		"option" => "$getNewT,$moneybackT",
		"select" => @$data['switchProduct'],
		'type' => 'radio',
		'format' => '<label class="radio-inline">{{form}} {{option}}</label>',
	);

	$form_fields[] = array(
		'type' => 'none',
		'thisFormat' => '<div class="form-group">
		<label class="col-sm-5 control-label">' . $_e['Buy Back'] . '</label>
		</div>',
	);


	$form_fields[] = array(
		'label' => $_e['Name of your bank'],
		'name' => 'insert[bankName]',
		"value" => @$data['bankName'],
		'type' => 'text',
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'label' => $_e['sortCode'],
		'name' => 'insert[sortCode]',
		'type' => 'text',
		"value" => @$data['sortCode'],
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'label' => $_e['Account Number'],
		'name' => 'insert[accountNo]',
		'type' => 'text',
		"value" => @$data['accountNo'],
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'type' => 'none',
		'thisFormat' => '<div class="form-group">
		<label class="col-sm-5 control-label">' . $_e['When Replacing'] . '</label>
		</div>',
	);

	$form_fields[] = array(
		'label' => $_e['I want to change to'],
		'name' => 'insert[changeTo]',
		'type' => 'text',
		"value" => @$data['changeTo'],
		'class' => 'form-control',
	);

	$form_fields[] = array(
		'label' => $_e['Message'],
		'name' => 'insert[message]',
		'type' => 'textarea',
		"value" => @$data['message'],
		'class' => 'form-control',
	);

	if ($type == 'defect') {
		$form_fields[] = array(
			'label' => $_e['Defect Image'],
			'type' => 'none',
			'format' => '<input type="file" name="image[]" >
			<input type="file" name="image[]" >
			<input type="file" name="image[]" >',
		);
	}

	$form_fields[] = array(
		"name" => 'submit',
		'class' => 'btn btn-success defaultSpecialButton',
		'type' => 'submit',
		'id' => 'signup_btn',
		"value" => $_e['Submit'],
	);

	$form_fields['form'] = array(
		'type' => 'form',
		'class' => "form-horizontal",
		'method' => 'post',
		'format' => '{{form}}'
	);

	$format = '<div class="form-group">
	<label class="col-sm-5 control-label">{{label}}</label>
	<div class="col-sm-7">
	{{form}}
	</div>
	</div>';

	$this->functions->print_form($form_fields, $format);
}

public function afterAddToCart_show_goToCart_option()
{
//call in footer
	global $_e;

	if ($this->functions->ibms_setting('afterAddToCart_show_goToCart_option') == '1' && !$this->functions->isCartPage()) {

		$checkoutO = '<div class="container-fluid">
		<input type="hidden" class="goToCartOption"  value="1" />
		<div class="col-sm-6 col-xs-12"><a id="continue_shopping" style="" href="#" data-dismiss="modal"><div class="col-xs-12 btn btn-lg btn-default">' . $_e["CONTINUE SHOPPING"] . '</div></a></div>
		<div class="visible-xs clearfix margin-5"></div>
		<div class="col-sm-6  col-xs-12"><a id="go_to_checkout" class="" href="' . WEB_URL . '/cart"><div class="col-xs-12 btn btn-lg btn-success">' . $_e["GO TO CHECKOUT"] . '</div></a></div>
		</div><!--check_btn_area end-->
		';
		$title = $_e["Product add to cart successfully"];
		return $this->functions->blankModal("$title", "goToCartOptionId", $checkoutO);
	}
	return "";
}

public function shippingClassInfo($shippingClassId)
{
	$sql = "SELECT id,price as priceSer,name FROM shipping_class WHERE id = '$shippingClassId' AND publish = '1'";
	$data = $this->dbF->getRow($sql);
	if (!empty($data)) {
		$price = unserialize($data['priceSer']);
		$currency = $this->currentCurrencyId();
		$data['price'] = $price[$currency];
		return $data;
	}
	return false;
}

public function orderDataByCustomSizeId($id)
{
	$sql = "SELECT * FROM `order_invoice_product` WHERE order_pIds LIKE '%-%-%-%-$id'";
	$orderProducts = $this->dbF->getRow($sql);
	$orderId = $orderProducts['order_invoice_id'];

	$sql = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$orderId' AND orderStatus = 'process'";
	$orderInvoice = $this->dbF->getRow($sql);

	$sql = "SELECT * FROM `order_invoice_info` WHERE order_invoice_id = '$orderId'";
	$orderInfo = $this->dbF->getRow($sql);

	$data = array_merge($orderProducts, $orderInvoice, $orderInfo);
	return $data;
}

public function getNextOrPrevProduct($pid, $next = true)
{
	if (empty($pid)) {
		return "";
	}

	if ($next) {
		$where = "detail.prodet_id > '$pid'";
	} else {
		$where = "detail.prodet_id < '$pid'";
	}

	$sql = "SELECT detail.prodet_id,detail.slug, detail.prodet_name, setting.p_id, setting.setting_val
	FROM `proudct_detail` detail INNER JOIN `product_setting` setting
	ON detail.prodet_id = setting.p_id
	WHERE
	$where AND setting.setting_name = 'publicAccess' AND setting.setting_val = 1
	AND detail.product_update = '1'
	ORDER BY `prodet_id` DESC LIMIT 1";

	$data = $this->dbF->getRow($sql);
	$name = array();
	if ($this->dbF->rowCount > 0) {

	} else {
		if ($next) {
//First Product.
			$where = "ORDER BY `prodet_id` ASC";
		} else {
//last Product.
			$where = "ORDER BY `prodet_id` DESC";
		}
		$sql = "SELECT detail.prodet_id,detail.slug, detail.prodet_name, setting.p_id, setting.setting_val
		FROM `proudct_detail` detail INNER JOIN `product_setting` setting ON detail.prodet_id = setting.p_id
		WHERE setting.setting_name = 'publicAccess' AND setting.setting_val = 1 AND detail.product_update = '1'
		$where LIMIT 1";
		$data = $this->dbF->getRow($sql);
	}

	$name['name'] = translateFromSerialize($data['prodet_name']);
	$name['id'] = $data['prodet_id'];
	$name['slug'] = $data['slug'];
	$name['link'] = WEB_URL . "/" . $this->db->productDetail . $name['slug'];
	if ($this->productDateIsReadyForLaunch($name['id'])) {
		return $name;
	} else {
// getNextProduct($name['id']);
	}
	return false;
}

/**
* return addional total price of select payment method
* @param $payment_type
*/
public function payment_additional_price($payment_type,$return_price=false){
//Add additional price to selected payment method
	@$payment_additional_price  = unserialize($this->functions->ibms_setting("payment_method_price"));
	$currency_id                = $this->currentCurrencyId();
	$currency_symbol            = $this->currentCurrencySymbol();

	@$price                     = $payment_additional_price[$payment_type][$currency_id];
	$price                      = empty($price) ? 0 : $price;

//only return price for calculation
	if($return_price)
		return floatval($price);

	if(!empty($price) && $price > 0){
		return  " (+$price $currency_symbol)
		<input type='hidden' value='$price' data-symbol='$currency_symbol' data-payment='$payment_type' class='payment_method_price payment_method_price_$payment_type'/>
		";
	}
	return "";
}

private function bestSellerSQL()
{
	return " INNER JOIN best_seller_products bsp ON bsp.product_id = detail.prodet_id ";
}

// private function newBestSellerSQL()
// {

//     return " (

//             SELECT `prodet_id` FROM `product_category` 
//             JOIN `proudct_detail` as detail on `product_category`.`procat_prodet_id` = `detail`.`prodet_id` 
//             INNER JOIN best_seller_products bsp ON bsp.product_id = detail.prodet_id 
//             GROUP BY `detail`.`prodet_id` 
//             ORDER BY bsp.sort ASC
//             LIMIT 182000000

//             )

//             UNION

//             (
//                 SELECT prodet_id
//                 FROM
//                 `proudct_detail` join `product_setting`
//                 on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
//                 WHERE `product_setting`.`setting_name`='publicAccess'
//                 AND `product_setting`.`setting_val`='1'
//                 AND `proudct_detail`.`product_update`='1' ORDER BY sale DESC
//                 LIMIT 182000000

//             ) ";

// }

private function newBestSellerSQL($cat_like_sql = '')
{

	$cat_like_sql = ( $cat_like_sql != '' ) ? '( ' . $cat_like_sql . ') AND ' : '';

	$sql = " SELECT pd.*, bsp.sort FROM proudct_detail as pd
	INNER JOIN best_seller_products bsp ON bsp.product_id = pd.prodet_id
	INNER JOIN product_setting ps       ON ps.p_id = pd.prodet_id
	INNER JOIN product_category ON product_category.procat_prodet_id = pd.prodet_id
	WHERE {$cat_like_sql}
	`ps`.`setting_name`       = 'publicAccess'
	AND   `ps`.`setting_val`        = '1'
	AND   `pd`.`product_update`     = '1'
	ORDER BY bsp.sort ASC ";
	$best_seller_products = $this->dbF->getRows($sql);

	return $best_seller_products;   

}

public function get_product_slugname($pid)
{
	$sql  = " SELECT `slug` FROM `proudct_detail` WHERE `prodet_id` = ? AND product_update = '1' ";
	$row  = $this->dbF->getRow($sql, array($pid) );
	if ( $this->dbF->rowCount > 0 ) {
		$result = $row['slug'];
	} else {
		$result = false;
	}

	return $result;

}

public function insert_user_info($data_array)
{

	$invoiceId     = $_SESSION['webUser']['lastInvoiceId'];
	$userId        = $this->webUserId();

	$payer_email   = $data_array[0];
	$payer_name    = $data_array[1] . ' ' . $data_array[2];
	$recipientName = $data_array[3];
$address       = $data_array[4] . ', ' . $data_array[5]; // address line 1, state
$countryCode   = $data_array[6];
$city          = $data_array[7];
$postalCode    = $data_array[8];

$sql = "INSERT INTO `order_invoice_info`
(
	`order_invoice_id`,
	`sender_Id`,
	`sender_name`,

	`sender_email`,
	`receiver_name`,
	`receiver_address`,

	`receiver_city`,
	`receiver_country`,
	`receiver_post`
)
VALUES (
?,?,?,
?,?,?,
?,?,?
)";

$array = array(
	$invoiceId, 
	$userId,
	$payer_name     ,

	$payer_email    ,
	$recipientName  ,
	$address        ,

	$city           ,
	$countryCode    ,
	$postalCode 

);

$this->dbF->setRow($sql, $array, false);
if( $this->dbF->rowCount > 0 ) {
	$result = TRUE;
} else {
	$result = FALSE;
}

return $result;

}

public function orderSubmit(){

	if (isset($_POST) && !empty($_POST) && !empty($_POST['country'])) {

		if (!$this->functions->getFormToken('WebOrderReady')) {
			return false;
		}

		try {
			$this->db->beginTransaction();

			$userId = webUserId();
			if ($userId == '0') {
				$userId = webTempUserId();
			}

// $this_script = 

			$status = 'pending';

			$expire_months  = $_POST['expire_months'];
			$price          = $_POST['price'];
			$payment        = $_POST['payment'];
			$productId      = $_POST['productId'];
			$fname          = $_POST['fname'];
			$company_name   = $_POST['company_name'];
			$lname          = $_POST['lname'];
			$mobile         = $_POST['mobile'];
			$address        = $_POST['address'];
			$country        = $_POST['country'];
			$email          = $_POST['email'];
			$submit         = $_POST['submit'];

			$prod_detail    = $this->functions->getProductName($productId, 'prodet_name');

			$prodet_shortDesc    = $this->functions->getProductName($productId, 'prodet_shortDesc');
			$pro_name       = translateFromSerialize($prod_detail['prodet_name']);
			$prodet_shortDesc       = translateFromSerialize($prodet_shortDesc['prodet_shortDesc']);
			$now            = date('Y-m-d H:i:s');
			$cur_date       = date('Y-m-d');
			$expire_date    = date('Y-m-d H:i:s', strtotime("+$expire_months months $now"));

			$sql = "INSERT INTO `orders`(
			`product_id`, 
			`order_user`, 
			`order_date`, 
			`price_per_month`,
			`order_status`,
			`expire_duration`, 
			`expire_date`, 
			`payment_mode`
		) VALUES (?,?,?,?,?,?,?,?)";

		$array = array($productId,$userId,$now,$price,$status,$expire_months,$expire_date,$payment);
		$this->dbF->setRow($sql, $array, false);
		$orderId = $this->dbF->rowLastId;

		$recur_payment = false;

		if($orderId > 0){

			$sql = "INSERT INTO `order_detail`(
			`order_id`, 
			`company_name`,
			`fname`, 
			`lname`, 
			`mobile`, 
			`address`, 
			`country`, 
			`email`
		) VALUES (?,?,?,?,?,?,?,?)";

		$this->dbF->setRow($sql, array($orderId,$company_name,$fname,$lname,$mobile,$address,$country,$email), false);

		$noOfInvoices = ($expire_months);
		$invoice_floor = floor($noOfInvoices);
		$firstInv = '';

		if($invoice_floor == 0 || $invoice_floor == 1){
			$inv_status = 'pending';
			$sql = "INSERT INTO `invoices`( 
			`order_id`, 
			`price`, 
			`due_date`, 
			`invoice_status`, 
			`update_date`
		) VALUES (?,?,?,?,?)";

		$this->dbF->setRow($sql, array($orderId,$price,$cur_date,$inv_status,$cur_date), false);
		$firstInv = $this->dbF->rowLastId;

	}else{
		$due_date = $cur_date;
		$recur_payment = true;

		if($payment == 'monthly'){
			$noOfInvoices = ($expire_months);

			$repeat_amount      = $price;
			$repeat_period      = 'M';
			$repeat_interval    = 1;
			$repeat_start       = date('dmY', strtotime("+1 month $due_date"));
			$repeat_term        = $invoice_floor;

			for ($i=0; $i < $invoice_floor; $i++) {

				$inv_status = 'pending';
				$inv_price  = $price;

				$sql = "INSERT INTO `invoices`( 
				`order_id`, 
				`price`, 
				`due_date`, 
				`invoice_status`, 
				`update_date`
			) VALUES (?,?,?,?,?)";

			$this->dbF->setRow($sql, array($orderId,$inv_price,$due_date,$inv_status,$cur_date), false);

			if($i == 0){
				$firstInv = $this->dbF->rowLastId;
			}

			$due_date = date('Y-m-d', strtotime("+1 month $due_date"));

		}

	}else if($payment == 'quarterly'){

		$repeat_amount      = $price;
		$repeat_period      = 'M';
		$repeat_interval    = 3;
		$repeat_start       = date('dmY', strtotime("+3 months $due_date"));
		$noOfInvoices = ($expire_months/3);
		$invoice_floor = floor($noOfInvoices);
		$repeat_term        = $invoice_floor-1;

		for ($i=0; $i < $invoice_floor; $i++) {

			$inv_status = 'pending';
			$inv_price  = ($price*3);

			$sql = "INSERT INTO `invoices`( 
			`order_id`, 
			`price`, 
			`due_date`, 
			`invoice_status`, 
			`update_date`
		) VALUES (?,?,?,?,?)";

		$this->dbF->setRow($sql, array($orderId,$inv_price,$due_date,$inv_status,$cur_date), false);

		if($i == 0){
			$firstInv = $this->dbF->rowLastId;
		}

		$due_date = date('Y-m-d', strtotime("+3 months $due_date"));

	}

}

}

$params = array(
	'ivp_method'  => 'create',
	'ivp_store'   => '20901',
	'ivp_authkey' => 'vJJrn~6LpK-6FR8f',
	'ivp_cart'    => $orderId,  
	'ivp_test'    => '0',
	'ivp_amount'  => $inv_price,
	'ivp_currency'=> 'AED',
	'ivp_desc'    => $pro_name." ".$prodet_shortDesc,
	'return_auth' => 'http://iscent.ae/orderInvoice.php?order='.$orderId.'&firstInv='.$firstInv,
	'return_can'  => 'http://iscent.ae/orderInvoice.php?order='.$orderId.'&firstInv='.$firstInv,
	'return_decl' => 'http://iscent.ae/orderInvoice.php?order='.$orderId.'&firstInv='.$firstInv,

	'bill_fname'  => $fname,
	'bill_sname'  => $lname,
	'bill_addr1'  => $address,
	'bill_city'   => $country,
	'bill_email'  => $email,
	'bill_country'=> 'ae',

	'repeat_amount'=> $inv_price,
	'repeat_period'=> $repeat_period,
	'repeat_interval'=> $repeat_interval,
	'repeat_start'=> $repeat_start,
	'repeat_term'=> $repeat_term,
	'repeat_final'=> '0',
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
curl_setopt($ch, CURLOPT_POST, count($params));
curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

$results = curl_exec($ch);
curl_close($ch);

$results = json_decode($results,true);

// echo '<pre>'; print_r($params); echo '</pre>';
// echo '<pre>'; print_r($results); echo '</pre>';

$ref= trim($results['order']['ref']);
$url= trim($results['order']['url']);
if (empty($ref) || empty($url)) {
# Failed to create order

	$sql_upd = "UPDATE `orders` SET `order_status` = 'incomplete' WHERE `order_id` = ?";
	$this->dbF->setRow($sql_upd, array($orderId), false);

}else{

	$sql_upd = "UPDATE `orders` SET `order_ref` = ? WHERE `order_id` = ?";
	$this->dbF->setRow($sql_upd, array($ref,$orderId), false);

	if($this->dbF->rowCount > 0){
		header('Location: '.$url); 
		echo "<script>location.replace('$url');</script>";
	}

}

}



} catch (Exception $e) {
	$this->dbF->error_submit($e);
	$msgT = $e->getMessage();
	$this->db->rollBack();
	$msg = $this->dbF->hardWords('Something went wrong Please try again', false);
	return $msg . " <br> " . $msgT;
}

}
}




}



?>
