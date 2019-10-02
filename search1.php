<?php include("global.php");
global $webClass;
require_once(__DIR__ . '/_models/functions/webProduct_functions.php');
$productClass = new webProduct_functions();

$_GET['cat'] = $_GET['s'];
$products = $productClass->productAdvanceSearch();
if($products == "" || $products == false){
    //print error emssage
    $t = $_e["No Product Found"];
    $products = "<div class='alert alert-danger'>$t</div>";
}else {
    $products = "<div class='iHaveProducts'>$products</div>"; // using product ajax load on scroll
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
?>
    <input type="hidden" style="display: none" id="queryLimit" data-id="<?php echo $limit; ?>" value="<?php echo $limit; ?>"/>
    <!--Inner Container Starts-->

    <!--main_second_main Start-->
    <div class="content_area product_area">
        <div class="align">
        <div class="p_box_area">
            <div class="content_3">
            <!-- Strat Of product top navigation -->
<!--            <div class="container-fluid text-right well well-sm">
                <?php /*echo $catnav; */
         ?>
            </div>-->
            <!-- End Of product Top Navigation-->
            <!-- Start Of product top navigation bottom-->

<!--                <div class="content_3_heading container-fluid"><?php /*echo $heading; */?>
                </div>-->
                <?php // echo $searchLabels; ?>

            <!-- End OF product top navigation bottom-->

            <!-- Start Of Filter-->


            <div class="container-fluid padding-0">
                <!-- Start Of Products-->
                        <?php echo $products; ?>
                <!-- End Of Products -->
            </div>

            </div>
        </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--main_second_main Ends-->



<?php include("footer.php"); ?>