<?php

ob_start();

//Encrypt after ob_start();

?>

<?php

//require_once("classes/setting.class.php");

global $dbF;

global $functions;

global $_e;

$functions->require_once_custom('setting.class.php');

$setting    =  new setting();



//$dbF->prnt($_POST);

//exit;

$setting->IBMSSubmit();

$settingData = $setting->getIBMSSettingData();

?>

    <h4 class="sub_heading"><?php echo _uc($_e['IBMS Setting']); ?></h4>



    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist">



        <!-- ################ General Setting ############## -->

        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['General']); ?></a></li>



        <!-- ################ Social Links ############## -->

        <?php if($functions->developer_setting('hasSocialLinks')=='1') { ?>

            <li><a href="#social" role="tab" data-toggle="tab"><?php echo _uc($_e['Social']); ?></a></li>

        <?php } ?>



        <!-- ################ Reviews / Comments Setting ############## -->

        <?php

        $ourReview          = $functions->developer_setting('reviews');

        $facebookComment    = $functions->developer_setting('isFacebookComments');

        if($ourReview=='1' || $facebookComment=='1') { ?>

            <li><a href="#reviews" role="tab" data-toggle="tab"><?php echo _uc($_e['Reviews']); ?></a></li>

        <?php } ?>



        <!-- ################ Products Setting ############## -->

        <?php if( $functions->developer_setting('product') == '1' && $functions->developer_setting('cartSystem') == '1' ) { ?>

            <li><a href="#product" role="tab" data-toggle="tab"><?php echo _uc($_e['Product']); ?></a></li>

        <?php } ?>





        <!-- ################ Free shipping price limit OR check out products ############## -->

        <?php if($functions->developer_setting('check_out_offer')=='1' || $functions->developer_setting('free_shipping_price')=='1') { ?>

            <li><a href="#offers" role="tab" data-toggle="tab"><?php echo _uc($_e['Check Out Offer']); ?></a></li>

        <?php } ?>



        <!-- ################ Show graphs on dashboard ############## -->

        <?php if($functions->developer_setting('dashboard_graph_setting')=='1') { ?>

            <li><a href="#dashboard_graph_setting" role="tab" data-toggle="tab"><?php echo _uc($_e['Dashboard']); ?></a></li>

        <?php } ?>



        <!-- ################ Payments method additional price ############## -->

        <?php if( $functions->developer_setting('product') == '1' && $functions->developer_setting('cartSystem') == '1' ) { ?>

            <li><a href="#payment" role="tab" data-toggle="tab"><?php echo _uc($_e['Payment']); ?></a></li>

        <?php } ?>



    </ul>





    <div class="container-fluid">

        <form action="" method="post" class="form-horizontal">

            <?php $functions->setFormToken('IBMSSetting'); ?>



            <!-- Tab panes -->

            <div class="tab-content">



                <!-- ################ General Setting ############## -->

                <div class="tab-pane fade in active container-fluid" id="home">

                    <h2  class="tab_heading"><?php echo _uc($_e['General']); ?></h2>

                    <?php $setting->generalSetting($settingData); ?>

                </div>

                <!-- ################ General Setting  End ############## -->



                <?php

                    ################ Social Links ##############

                //Check If socialLinks is allow in project

                if($functions->developer_setting('hasSocialLinks')=='1') { ?>

                    <div class="tab-pane fade in container-fluid" id="social">

                        <h2  class="tab_heading"><?php echo _uc($_e['Social']); ?></h2>

                        <?php $setting->socialSetting($settingData); ?>

                    </div>

                <?php }

                    ################ Social Links End ##############

                ?>





                <?php

                    ################ Reviews / Comments Setting ##############

                //Check If Review is allow in project

                if($ourReview == '1' || $facebookComment == '1') { ?>

                    <div class="tab-pane fade in container-fluid" id="reviews">

                        <?php if($ourReview=='1') { ?>

                            <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Reviews']); ?></h2>

                            <?php $setting->reviewSetting($settingData);

                        }



                        ?>

                        <?php if($facebookComment=='1') { ?>

                            <br>

                            <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Facebook Comment']); ?></h2>

                            <?php $setting->facebookReviewSetting($settingData);

                        }

                        ?>



                    </div>

                <?php }

                    ################ Reviews / Comments Setting End ##############

                ?>





                <?php

                    ############### Products Setting ##############

                //Check If product is allow in project

                if($functions->developer_setting('product')=='1') { ?>

                    <div class="tab-pane fade in container-fluid" id="product">

                        <h2  class="tab_heading"><?php echo _uc($_e['Product']); ?></h2>

                        <?php $setting->productSetting($settingData);



                        if($functions->developer_setting('askQuestion') == '1'){

                            echo "<h2 class='tab_heading borderIfNotabs'>"._uc($_e['Products Ask Question'])."</h2>";

                            $setting->askQuestionSetting($settingData);

                        }



                        if($functions->developer_setting('grid_view') == '1'){

                            echo "<h2 class='tab_heading borderIfNotabs'>"._uc($_e['Grid View By Category'])."</h2>";

                            $setting->grid_view_setting($settingData);

                        }



                        ?>

                    </div>

                <?php }

                ############### Products Setting END ##############

                ?>





                <?php

                ################ Free shipping price limit OR check out products ##############

                //Checkout offer setting,

                if($functions->developer_setting('check_out_offer')=='1' || $functions->developer_setting('free_shipping_price')=='1') { ?>

                    <div class="tab-pane fade in container-fluid" id="offers">



                        <?php //free_shipping_price setting,

                        if($functions->developer_setting('free_shipping_price')=='1') { ?>

                            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Free Shipping Offer']); ?></h2>

                            <?php $setting->free_shipping_offer($settingData); ?>

                        <?php } ?>

                            <hr>

                        <?php //Checkout offer setting,

                        if($functions->developer_setting('check_out_offer')=='1') { ?>

                            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Check Out Offer']); ?></h2>

                            <?php $setting->checkOutOffer($settingData); ?>

                        <?php } ?>



                        <?php ################# FREE GIFT DEFAULT PRODUCT ################# ?>

                        <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['Free Gift Product']); ?></h2>

                        <?php $setting->free_default_product($settingData); ?>





                        <?php ################# 3 for 2 Category ################# ?>

                        <h2 class="tab_heading borderIfNotabs"><?php echo _uc($_e['3 for 2 Category Offer']); ?></h2>

                        <?php $setting->two_for_3_category($settingData); ?>

                    </div>

                <?php }

                    ################ Free shipping price limit OR check out products END ##############

                ?>





                <?php

                    ################ Show graphs on dashboard ##############

                //dashboard graph setting, which graph show in dashboard... other all graph will show on their proper page

                if($functions->developer_setting('dashboard_graph_setting')=='1') { ?>

                    <div class="tab-pane fade in container-fluid" id="dashboard_graph_setting">

                        <h2  class="tab_heading"><?php

                            echo _uc($_e['Dashboard']);

                            echo "<br><small>"._uc($_e['Which Graph report you want to show on dashboard'])."</small>";

                            ?>

                        </h2>

                        <?php $setting->dashboard_graph_setting($settingData); ?>

                    </div>

                <?php }

                    ################ Show graphs on dashboard END##############

                ?>







                <?php

                ################ Payments method additional price ##############

                //dashboard graph setting, which graph show in dashboard... other all graph will show on their proper page

                //if($functions->developer_setting('dashboard_graph_setting')=='1') { 
                    ?>

                    <div class="tab-pane fade in container-fluid" id="payment">

                        <h2 class="tab_heading"><?php

                            echo _uc($_e['Payment method additional price']);

                            ?>

                        </h2>

                        <?php $setting->payment_setting($settingData); ?>

                    </div>

                <?php //}

                ################ Payments method additional price END ##############

                ?>



            </div>



            <button type="submit" class="btn btn-primary btn-lg"><?php echo _u($_e['SAVE']); ?></button>

        </form>

    </div>

<?php return ob_get_clean(); ?>