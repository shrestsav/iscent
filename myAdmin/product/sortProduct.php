<?php

ob_start();

$product = new product();

$functions->require_once_custom('product_functions');

$productF = new product_function();



$pLink = WEB_URL.'/products?product='

?>

<div class="container-fluid">

<style>

    #sortProducts li{

        min-height: 55px;;

        display: inline-block;

        float:left;

        margin: 10px;

        min-width: 150px;



    }

    #sortProducts li img{

        margin: 2px;

    }

    .sortProductDiv{

        cursor: pointer;

        font-size: 14px;

        font-weight: bold;

    }



</style>

    <h2 class="borderIfNotabs sub_heading"><?php echo _uc($_e['Sort Products']); ?></h2>

        <?php

        $defaultLang =   $functions->AdminDefaultLanguage();



        if(!isset($_GET['cat'])) {

            echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['Select Product Category']) .'</h4>';

            $functions->modelClasFile("category.php");

            $category_c = new p_category();

            $category = $category_c->get_all_category();

            $link   = $functions->getLinkFolder(false);

            foreach($category as $val){
                $c_name = translateFromSerialize($val['name']);
                echo "<div class='col-sm-3'><a href='-$link&cat=$val[id]' class='btn btn-primary col-sm-12  margin-5'>$c_name</a></div>";

            }

        } else {

            $catId = $_GET['cat'];

            $catId = $productF->getSubCatIds($catId); //array



            $cat_like = "";

            foreach ($catId as $val) {

                $cId = $val;

                $cat_like .= " `product_category`.`procat_cat_id` LIKE '%$cId%' OR";

            }

            $cat_like = trim($cat_like, "OR");





            $qry    =   "SELECT `proudct_detail`.* FROM

                            `proudct_detail` join `product_setting`

                             on `proudct_detail`.`prodet_id` = `product_setting`.`p_id`

                             WHERE `proudct_detail`.`product_update`='1'

                              AND `proudct_detail`.`prodet_id` IN (SELECT procat_prodet_id FROM product_category WHERE $cat_like GROUP BY procat_prodet_id)

                              GROUP BY `proudct_detail`.`prodet_id` ORDER BY proudct_detail.sort";

            $data = $dbF->getRows($qry);

            $products = '';

            if ($data != false):

                $products .= "<ul id='sortProducts' class='list-unstyled col-sm-12  '>";



                $toggle = false;

                foreach ($data as $val) {

                    $name = unserialize($val['prodet_name']);

                    $name = $name[$defaultLang];

                    $pId = $val['prodet_id'];



                    $showImage = $functions->ibms_setting('sortProductImage');

                    if ($showImage == 'yes') {

                        $img = $productF->productSpecialImage($pId, 'main');

                        $img = $functions->resizeImage($img, 'auto', 80, false);

                        $img = " &nbsp; <img data-src='$img' class='lazy' src='$img' height='70'/> &nbsp; &nbsp;";

                    } else {

                        $img = '<span class="sortProductDiv"> ::: </span>';

                    }



                    if ($toggle) {

                        $class = "btn-success";

                        $toggle = false;

                    } else {

                        $toggle = true;

                        $class = "btn-success";

                    }

                    $products .= "<li id='sort_$pId' class='$class text-center margin-5 padding-0'>

                                        $img <div class='clearfix'></div> $name

                                    </li>";

                }

                $products .= "</ul>";

            endif;

            echo $products;

        }

        ?>



</div>



<script>

    $(document).ready(function() {

        $( "#sortProducts" ).sortable({

            //handle: '.sortProductDiv',

            containment: "parent",

            update : function () {

                serial = $(this).sortable('serialize');

                $.ajax({

                    url: 'product_management/product_ajax.php?page=sortProducts',

                    type: "post",

                    data: serial,

                    error: function(){

                        jAlertifyAlert("<?php echo _js($_e['There is an error, Please Refresh Page and Try Again']); ?>");

                    }

                });

            }

        });

    });

</script>





<?php return ob_get_clean(); ?>