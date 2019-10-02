<?php
ob_start();
$product = new product();
$functions->require_once_custom('product_functions');
$productF = new product_function();

$pLink = WEB_URL.'/products?product=';

/**
 * MultiLanguage keys Use where echo;
 * define this class words and where this class will call
 * and define words of file where this class will called
 **/
global $_e;
global $adminPanelLanguage;
$_w=array();
$_w['Select Product Language'] = '' ;
$_w['Select Product Category'] = '' ;
$_w['Select Products'] = '' ;
$_w['GO TO NEWS LETTER'] = '' ;
$_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Letter');

?>
<div class="container-fluid">

    <div>
        <?php

        if(!isset($_GET['cat']) && !isset($_GET['lang'])) {
            echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['Select Product Category']) .'</h4>';
            $functions->modelClasFile("category.php");
            $category_c = new p_category();
            $category = $category_c->get_all_category();
            $link   = $functions->getLinkFolder(false);
            foreach($category as $val){
                echo "<div class='col-sm-3'><a href='-$link&cat=$val[id]' class='btn btn-primary col-sm-12  margin-5'>$val[nm]</a></div>";
            }
        }
        else if(isset($_GET['cat']) && !isset($_GET['lang'])){
                echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['Select Product Language']) .'</h4>';
                //$cat = $_GET['cat'];
                $lang = $functions->IbmsLanguages($dbF);
                $link = $functions->getLinkFolder(false);
                foreach($lang as $val){
                    echo " <div class='col-sm-3'><a href='-$link&lang=$val' class='btn btn-primary col-sm-12 margin-5'>$val</a></div>";
                }
        }
        else{
            $lang = $_GET['lang'];
            $cat = $_GET['cat'];
            echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['Select Products']) .'</h4>';
            $qry    =   "SELECT `proudct_detail`.*, `product_setting`.`setting_val`
                         FROM
                            `proudct_detail` join `product_setting`
                             on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`
                             WHERE `product_setting`.`setting_name`='publicAccess' AND `product_setting`.`setting_val`='1'
                             AND `proudct_detail`.`product_update`='1'
                             AND `proudct_detail`.`prodet_id` IN (SELECT procat_prodet_id FROM product_category WHERE `product_category`.`procat_cat_id` LIKE '%$cat%' )
                             ";
            $data = $dbF->getRows($qry);

            echo "<form method='post' action='-email?page=newsLetter#letter'>";
            foreach($data as $val){
                $id     =   $val['prodet_id'];
                $name   =   unserialize($val['prodet_name']);
                @$name  =   $name[$lang];
                $img    =   $productF->productSpecialImage($id,'main');
                $price  =   $productF->productPrice($id);
                $currencyId =   $price['propri_cur_id'];
                $symbol     =   $productF->currencySymbol($currencyId);
                $priceP =   $price['propri_price'];

                $discount       =   $productF->productDiscount($id,$currencyId);
                @$discountFormat=   $discount['discountFormat'];
                @$discountP     =   $discount['discount'];

                $discountPrice  =   $productF->discountPriceCalculation($priceP,$discount);
                $newPrice       =   $priceP - $discountPrice;

                $priceP         .= ' '.$symbol;
                $newPrice       .= ' '.$symbol;

                if($newPrice    !=  $priceP){
                    $hasDiscount = true;
                    $oldPriceDiv = '<span class="oldPrice">'.$priceP.'</span>';
                    $newPriceDiv = '<span class="NewDiscountPrice">'.$newPrice.'</span>';
                }else{
                    $oldPriceDiv= "";
                    $newPriceDiv = '<span class="NewDiscountPrice">'.$priceP.'</span>';
                }

                $img    = $functions->resizeImage($img,'auto',160,false);

                echo "<label><div class='allProductInfo'>
                        <div class='pImg'>
                            <img data-src='$img' src='$img' class='lazy'/>
                        </div>
                        <div class='pName'>
                            <input type='checkbox' name='producNewsLetter[]' value='".$id."'>
                            <a href='".$pLink.$id."'>$name
                                <br>
                                  $oldPriceDiv $newPriceDiv
                            </a>
                        </div>
                      </div>
                      </label>
                ";
            }

            echo "
                <div class='clearfix'></div>
                <input type='submit' class='btn btn-primary' value='". _u($_e['GO TO NEWS LETTER']) ."'/>
            </form>";
        }
        ?>
    </div>

</div>




<?php return ob_get_clean(); ?>