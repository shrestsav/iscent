<?php 
include("global.php");
global $webClass;
global $_e;
global $productClass;
$productClass->setProductSlug();

$cat = '0';
if(isset($_GET['cat']) && $_GET['cat']!='' || (isset($_GET['catId']) && $_GET['catId'] != '' )){
		//Product By category
	if(isset($_GET['catId'])){
		$cat = str_replace("-","",$_GET['catId']);
		$_GET['catId'] = $cat;
		if(!isset($_GET['cat'])) {
			$_GET['cat'] = $cat;
		}
	}
	else
	{
		$cat = $_GET['cat'];
	}

	if(intval($cat)>0) {
		$products = $productClass->productByCategoryNew($cat, @$_GET['product']);
	}
	else
	{
		$products = $productClass->productByCategoryNew($cat, @$_GET['product'],false);
	}

	if($products==false){
		//Do If no product found on category
	}

}
else
{
		//All Products
		// $products = $productClass->AllProducts(@$_GET['product']);
	$cat = '1002';
	$products = $productClass->productByCategoryNew($cat,@$_GET['product']);
}

if($products == "" || $products == false){
		//print error emssage
	$t        = $_e["No Product Found"];
	$products = "<div class='alert alert-danger'>$t</div>";

}
else 
{
	// $products = "<div class='bottom_session_img'>$products</div>";
	// using product ajax load on scroll
}

	$heading = ""; // Page Heading
	$catName =  $productClass->getCategoryName($cat);

	$catnav = "";//category navigation e.g: product / Men's / Shirts
	$catnav  .= "<a href='".WEB_URL."/products' class='grow'>"._u($_e['Products'])." </a>";

	if(isset($_GET['cat']) && $catName ==false) {
		$heading = $dbF->hardWords($cat,false);
		$catnav  .= " / <a href='".WEB_URL."/products?cat=$_GET[cat]' class='grow'> "._u($heading)."</a>";
	}
	elseif($catName===false)
	{
		$heading = $_e['Products'];
	}
	else
	{
		$heading  = $catName;
		@$catnav  .= " / <a href='".WEB_URL."/products?cat=$_GET[cat]' class='grow'> "._u($heading)."</a>";
	}

	$heading = _u($heading);

	include("header.php");

	$limit  =   $productClass->productLimitShowOnWeb();
	$box30 	= 	$webClass->getBox("box30"); 
	$termsOfService = $webClass->getBox("box31"); 


	$bannerImgs   = ( @$page['image'] ==  WEB_URL . '/images/' || @$page['image'] === NULL ) ?  $box22['image'] : @$page['image'];
	?>

	<script> 
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) })(window,document,'script','//www.google-analytics.com/analytics.js','ga'); ga('create', 'UA-102304446-1', 'auto'); ga('send', 'pageview'); 
	</script>

	<style type="text/css">
		.subscribe-bg {
		    background: url('<?= $box30['image'] ?>') no-repeat;
		    background-size: cover;
		    background-position: center center;
		}
	</style>
	
	<section class="subscribe-bg page-banner">
		<div class="page-heading">
			<h2><?= $box30['heading'] ?></h2>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb text-center">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?= $box30['heading'] ?></li>
				</ol>
			</nav>
		</div>
	</section>
	<!-- ENd of elegance -->

	<section id="content" class="less-space section-container">
		<div class="container">
			<div class="row">
				<div class="section-heading text-left">
					<h3 class="promo-text"><?= $box30['heading2'] ?></h3>
					<p><?= $box30['text'] ?></p>
				</div>
			</div>
			<div class="row">
				<?= $products; ?>
			</div>
		</div>
	</section>

	<div class="payment-modal modal fade bd-example-modal-lg" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="m-header">
					<h2>Order Summary <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button></h2>
					<table class="table">
						<tr>
							<td class="productName"><!-- Filled from JS --></td>
							<td>1 Month</td>
							<td class="productMode"><!-- Filled from JS --></td>
							<td>AED <span class="productPrice"><!-- Filled from JS --></span></td>
						</tr>
					</table>
					<h3>Total: AED <span class="productPrice"><!-- Filled from JS --></span></h3>
					<hr>
					<h3 class="modal-subheading">Billing Details</h3>
					<form id="orderForm" method="post" action="orderInvoice.php" class="row">
						<?php $productClass->functions->setFormToken('WebOrderReady'); ?>
						<div id="hidden_field"></div>
						<?php 
						$user_id = $webClass->webUserId();
						$sql    = "SELECT * FROM accounts_user WHERE acc_id = '$user_id'";
						$userData   =   $dbF->getRow($sql);

						$sql    = "SELECT * FROM accounts_user_detail WHERE id_user = '$user_id'";

						$userInfo   = $dbF->getRows($sql);
						?>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" placeholder="Full Name / Company Name" name="company_name" value="<?= @$userData['acc_name']; ?>" >
							<input type="hidden" name="fname" placeholder="First Name" required>
							<input type="hidden" name="lname" placeholder="Last Name" required>
						</div>
						<div class="form-group col-md-6">
							<input type="phone" class="form-control" name="mobile" value="<?= $webClass->webUserInfoArray($userInfo,'phone'); ?>" placeholder="Mobile No" required>
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" name="address" value="<?= $webClass->webUserInfoArray($userInfo,'address'); ?>" placeholder="Address" required>
						</div>
						<div class="form-group col-md-6">
							<select class="form-control" name="country" required>
								<option value="" selected="selected">Select Emirate</option>
								<option value="AB">Abu Dhabi</option>
								<option value="AJ">Ajman</option>
								<option value="SH">Sharjah</option>
								<option value="DU">Dubai</option>
								<option value="FU">Fujairah</option>
								<option value="RA">Ras Al Khaimah</option>
								<option value="UM">Umm Al Quwain</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<input type="email" class="form-control" name="email" value="<?= @$userData['acc_email']; ?>" placeholder="Email Address" required>
						</div>
						<div class="payment-terms bg-gray" style="display: block;padding: 30px;">
							<span class="TOS"><!-- Filled from JS --></span>
							<div id="product_desc"><!-- Filled from JS --></div>
						</div>
						<input type="submit" name="submit" id="checkoutBtn" class="btn btn-success" value="proceed to checkout">
					</form>
				</div>
			</div>
		</div>
	</div>



	<script type="text/javascript">
		let TOS = JSON.parse('<?= json_encode($termsOfService,true) ?>');
		function getWords(monthCount) {
			function getPlural(number, word) {
				return number === 1 && word.one || word.other;
			}

			var months = { one: 'month', other: 'months' },
			years = { one: 'year', other: 'years' },
			m = monthCount % 12,
			y = Math.floor(monthCount / 12),
			result = [];

			y && result.push(y + ' ' + getPlural(y, years));
			m && result.push(m + ' ' + getPlural(m, months));
			return result.join(' and ');
		}

		function selectPlan(id){
			var pId = id;
			$('#paymentModal').modal('toggle');
			$.ajax({
				url: 'ajax_call.php?page=selectPlan',
				type: 'post',
				data: {pId: pId}
			}).done(function(res){
				console.log(res)
				var parsed = JSON.parse(res);
				expire = parsed.expire;
				mode = parsed.mode;
				price = parsed.price;
				name = parsed.name;
				desc = parsed.desc;
				currency = parsed.currency;

				months = getWords(13);

				bill_detail = '1 Month - '+price+' (Billed '+mode+') '+name+' ';
				total_price = currency+' '+price;

				$('.TOS').html(TOS['heading2']+' <a href="'+TOS['link']+'">'+TOS['heading']+'</a>');
				$('#product_desc').html(desc);

				$('.productName').text(name);
				$('.productMode').text(mode);
				$('.productPrice').text(price);

				$('#bill_detail').html(bill_detail);
				$('#total_price').html(total_price);

				hidden_fields = '<input type="hidden" name="expire_months" value="'+expire+'">'+
				'<input type="hidden" name="price" value="'+price+'">'+
				'<input type="hidden" name="payment" value="'+mode+'">'+
				'<input type="hidden" name="productId" value="'+pId+'">';

				$('#hidden_field').html(hidden_fields);
			});
		}
	</script>

	<?php include("footer.php"); ?>


