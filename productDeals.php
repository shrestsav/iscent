<?php
include("global.php");
global $webClass;
global $_e;
global $productClass;
$productClass->setProductSlug();

//work for deal slug
if(isset($_GET['dealSlug'])){
    $pSlug = ($_GET['dealSlug']);
    $sql = "SELECT id FROM product_deal WHERE slug = '$pSlug'";
    $dealSlug = $dbF->getRow($sql);
    $pId = $dealSlug['id'];
    $_GET['deal']    = $pId;
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
$_w['Product Description'] = '';
$_e = $dbF->hardWordsMulti($_w, currentWebLanguage(), 'Web DealProduct');


$cat            = '0';
$dealId         = '0';
$currencyId     = $productClass->currentCurrencyId();
$currencySymbol = $productClass->currentCurrencySymbol();

if(isset($_GET['deal']) && $_GET['deal']!=''){
    $dealId     = $_GET['deal'];
    $sql        = "SELECT * FROM product_deal WHERE publish = '1' AND id = '$dealId' ";
    $dealData   = $dbF->getRow($sql);
    $price      = unserialize($dealData['price']);
    $price      = $price[$currencyId];

    $products   = $productClass->AllDealsPackage($dealId);

    if(!empty($products)){
        $products   = "<input type='hidden' id='packDealId' value='$dealId' /> <input type='hidden' id='packInfo' /> $products";

        $sql        = "SELECT * FROM product_deal_setting where deal_id = '$dealId' ";
        $dataSetting = $dbF->getRows($sql);
        $shortDesc  = $functions->findArrayFromSettingTable($dataSetting,'sDesc');
        $shortDesc  = translateFromSerialize($shortDesc);

        //Count deal no of view
        $productClass->productDealViewCount($dealId);
    }else{
        $shortDesc = '';
        $dealId= 0;
    }
    //specific deal select end
}else{
    //All Deals Products
    if(isset($_GET['cat']) && $_GET['cat']!='' || (isset($_GET['catId']) && $_GET['catId'] != '' )){
        //Product By category
        if(isset($_GET['catId'])){
            $cat = str_replace("-","",$_GET['catId']);
            $_GET['catId'] = $cat;
            if(!isset($_GET['cat'])) {
                $_GET['cat'] = $cat;
            }
        }else{
            $cat = $_GET['cat'];
        }

        if(intval($cat)>0) {
            $products = $productClass->productDealsByCategory($cat, @$_GET['product']);
        }else{
            $products = $productClass->productDealsByCategory($cat, @$_GET['product'],false);
        }
    }else {
        $products = $productClass->AllProductDeals();
    }
}

if($products == "" || $products == false){
    //print error emssage
    $t          =   $_e["No Product Found"];
    $products   =   "<div class='alert alert-danger'>$t</div>";
}else{
    $products   =   "<div class='iHaveProducts'>$products</div>"; // using product ajax load on scroll
}
$products       =   "<input type='hidden' id='productPage' value='deal' />$products";

$heading = ""; // Page Heading
$heading = $_e['Products'];

@$arraySeo['title'] = $heading;
@$arraySeo['description'] = $shortDesc;
$productClass->productMetaSeo($arraySeo);

include("header.php");
$limit   =   $functions->ibms_setting('productLimit');
?>

<?php $webClass->seoSpecial();  ?>

    <!--main_second_main Start-->
    <div class="content_area product_area">
        <input type="hidden" style="display: none" id="queryLimit" data-id="<?php echo $limit; ?>" value="<?php echo $limit; ?>"/>
        <div class="p_box_area">
            <div class="container-fluid padding-0 ">
               <?php

                   if($dealId != '0'){
                       echo "<div class='standard container-fluid' style='background: #fff'>";

                       echo "<div class='padding-10' style='background: #fff'>
                                <div class='well well-sm'>$heading</div>
                                <div class=''>
                                    $shortDesc
                                </div>
                                <div class=''>
                                    <span class='NewDiscountPrice1'> <div class='p_prize'><h6>$price $currencySymbol </h6></div></span>";

                      if($functions->developer_setting('addQty_custome')=='1') {
                           echo "<div class='container-fluid padding-0 form-horizontal' style='width:200px'>
                                    <label class='col-xs-3 control-label padding-0' style='line-height:20px'>Quantity </label>
                                    <div class='col-xs-9 '>
                                        <input type='number' min='1' value='1' pattern='[0-9]{1,5}' class='form-control addByQty_$dealId' />
                                        <input type='hidden' value='1' class='addByQty_hidden_$dealId' />
                                    </div>
                                  </div>
                              <div class='cart_btn  cursor AddToCart_$dealId pd_btn' onclick='dealProductAddToCart(this,$dealId);'>
                                    {$_e['Add To Cart']}
                              </div>";
                       }else{
                           echo "<div class='cart_btn  cursor AddToCart_$dealId pd_btn' onclick='dealProductAddToCart(this,$dealId);'>
                                    {$_e['Add To Cart']}
                              </div>";
                       }



                       echo "
                                </div>
                              </div>
                              <hr>";
                       echo "<div class='text-left'>";
                            echo $products;
                       echo "</div>";
                       echo "</div>";
                   }else{
                       echo $products;
                   }


               ?>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
    <!--main_second_main Ends-->


<?php include("footer.php"); ?>