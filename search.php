<?php include("global.php");
global $webClass;
require_once(__DIR__ . '/_models/functions/webProduct_functions.php');
$productClass = new webProduct_functions();

$_GET['cat'] = isset($_GET['s'])? $_GET['s'] : '';
$products = $productClass->productAdvanceSearch();
if($products == "" || $products == false){
    //print error emssage
    $t = $_e["No Product Found"];
    $products = "<div class='alert alert-danger'>$t</div>";
}else {
    $products = "<div class='iHaveProducts' style='padding: 35px;'>$products</div>"; // using product ajax load on scroll
}
$cat = '0';
if(isset($_GET['cat']) && $_GET['cat']!='') {
    $cat = $_GET['cat'];
}


$heading = "";
$catName =  $productClass->getCategoryName($cat);

$catnav = "";
$catnav  .= "<td><a href='".WEB_URL."/products' class='grow'>"._u($_e['Products'])." </a></td>";
if(isset($_GET['cat']) && $catName ==false) {
    $heading = $dbF->hardWords($cat,false);
    $catnav  .= "<td> / </td><td><a href='".WEB_URL."/products?cat=$_GET[cat]' class='grow'> "._u($heading)."</a></td>";
}elseif($catName===false){
    $heading = $_e['Products'];
}
else{

    $heading  = $catName;
    $catnav  .= "<td> / </td><td><a href='".WEB_URL."/products?cat=$_GET[cat]' class='grow'> "._u($heading)."</a></td>";
}

if(isset($_GET['s'])){
    $heading = $_GET['s'];
}

$heading = _u($heading);


//filter search labels
$searchLabelsT = $productClass->productAdvanceSearchLabels();
if($catName !== false && $cat!= '0'){
    $searchLabelsT = $productClass->makeSearchLabel("cat",$catName).$searchLabelsT;
}
$searchLabels = '<div class="searchLabels">'.$searchLabelsT.'</div>';


include("header.php");
$limit = $functions->ibms_setting('productLimit');

$box22 = $webClass->getBox("box27"); 


$bannerImgs   = ( @$page['image'] ==  WEB_URL . '/images/' || @$page['image'] === NULL ) ?  $box22['image'] : @$page['image'];
?>  



<div class="divide" style="background: url(<?php echo $bannerImgs ?>);">
            <div class="standard">
                <h1>product Search</h1>
            </div>
        </div>
<div class="product_side">
<div class="standard">
<!-- show_on close -->

<?php $functions->includeOnceCustom('left_side_category.php'); ?>



<div class="right_product">
<h1><?php echo    $heading; ?> </h1>
<div class="right_product_top">
<div class="right_product_top_select sort_side">

      <?php echo $productClass->sortByProduct(); ?>

<!--      
<select>
<option>Sort</option>
<option>Lowest Price Assending</option>
<option>Highest Price Assending</option>
<option>Most Visited Product</option>
</select> -->
</div>
<!-- right_product_top_select close -->
</div>
<!-- right_product_top close -->

<input type="hidden" style="display: none" id="queryLimit" data-id="<?php echo $limit; ?>" value="<?php echo $limit; ?>"/>
<input type="hidden" style="display: none" id="viewType" value="<?php echo isset($_GET["viewType"]) ? (string) $_GET["viewType"] : $productClass->get_product_view();
?>"/>
<div class="product_main">






<?php echo $products; ?>

<!-- <div class="col1_box">
<a href="#">
<div class="col1_box_img"> <img src="webImages/p8.jpg" alt=""> </div>
<div class="col1_box_txt">
<h3>Example 8</h3>
<h2>PKR 2,900.00</h2>
<h4>PKR 5,800.00</h4>
<div class="col1_box_btn"> <span>ADD TO CART</span> </div>
</div>
</a>
</div> -->




</div>
<!-- product_main close -->
<!-- <div class="product_main_page"> Items <span>1-12</span> of <span>62</span> </div> -->
<!-- product_main_page close -->
</div>
<!-- right_product close -->
</div>
<!-- standard close -->
</div>
<!-- product_side close -->


<?php include("footer.php"); ?>