<?php 
    global $dbF, $functions, $productClass;
?>

    <div class="cart_side" id="cart_side">
        
        <div class="btn_1_side">
            <a href="#" id="back_to_store" >
                <span><img src="<?php echo WEB_URL ?>/webImages/btn_1_side.png" alt=""></span><?php echo $this->dbF->hardWords('BACK TO STORE', false); ?>
            </a>
        </div>
        

        <div class="cart_1" id="cart_items_container">


        <?php /* ?>

            <div class="cart_1_inner">
                <div class="cart_1_inner_1">
                    <a href="#">
                        <img src="images/ajax/product/2016/05/2317_671_thunderhomepage.jpg" alt="">
                        <div class="cart_1_btn">
                            <img src="webImages/close_btn.png" alt="">
                        </div>
                        <!-- cart_1_btn close -->
                    </a>
                </div>
                <!-- cart_1_inner_1 close -->
                <div class="cart_1_inner_2">
                    <h3>CE-Godkänd Skyddsjacka</h3>
                    <div class="info_main">
                        <div class="info_1">Storlek:<span>M</span></div>
                        <!-- info_1 close -->
                        <div class="info_1">Farg:<span style="background: #050005;padding: 2px 9px;color: #fff;font-size: 11px;border-radius: 50%;width: 10px;height: 16px;margin-left: 10px;"></span></div>
                        <div class="info_1">Antal:<span>1 @ 599</span><span>SEK</span></div>
                        <div class="info_1">599<span>SEK</span></div>
                        <!-- info_1 close -->
                    </div>
                    <!-- info_main close -->
                </div>
                <!-- cart_1_inner_2 close -->
            </div>
            <!-- cart_1_inner close -->




            <div class="cart_1_inner last_child">
                <div class="cart_1_inner_1">
                    <a href="#">
                        <img src="images/ajax/product/2016/05/2317_671_thunderhomepage.jpg" alt="">
                        <div class="cart_1_btn">
                            <img src="webImages/close_btn.png" alt="">
                        </div>
                        <!-- cart_1_btn close -->
                    </a>
                </div>
                <!-- cart_1_inner_1 close -->
                <div class="cart_1_inner_2">
                    <h3>CE-Godkänd Skyddsjacka</h3>
                    <div class="info_main">
                        <div class="info_1">Storlek:<span>M</span></div>
                        <!-- info_1 close -->
                        <div class="info_1">Farg:<span style="background: #050005;padding: 2px 9px;color: #fff;font-size: 11px;border-radius: 50%;width: 10px;height: 16px;margin-left: 10px;"></span></div>
                        <div class="info_1">Antal:<span>1 @ 599</span><span>SEK</span></div>
                        <div class="info_1">599<span>SEK</span></div>
                        <!-- info_1 close -->
                    </div>
                    <!-- info_main close -->
                </div>
                <!-- cart_1_inner_2 close -->
            </div>
            <!-- cart_1_inner close -->

        <?php */ ?>



        </div>
        <!-- cart_1 close -->
        <div class="border_side"></div>
        <!-- border_side close -->
        <div class="main_box_side cart3">

            <div id="three_for_two_text"></div>


            <div class="pc_text" id="coupon_text" ></div>

            <div class="main_box_side_inner">
                <div class="box_1_side">
                    <?php echo $this->dbF->hardWords('Discount Code', false); ?>
                </div>
                <!-- box_1_side close -->
                <div class="main_form_side">
                    <div class="form_1" id="side_coupon_container">
                        <form id="">
                            <input type="text" id="coupon_input" >
                            <input type="submit" id="side_coupon_submit" class="submit_side" value="Använd">
                        </form>
                    </div>
                    <!-- form_1 close -->
                </div>
                <!-- main_form_side close -->


                <div id="coupon_remove_text"></div>


            </div>
            <!-- main_box_side_inner close -->


            <div class="pc_text" id="giftcard_text" ></div>

            <div class="main_box_side_inner">
                <div class="box_1_side">
                    <?php echo $this->dbF->hardWords('Gift Card Id', false); ?>
                </div>
                <!-- box_1_side close -->
                <div class="main_form_side">
                    <div class="form_1" id="side_giftcard_container">
                        <form>
                            <input type="text"   id="giftcard_input" >
                            <input type="submit" id="side_giftcard_submit" class="submit_side" value="Använd">
                        </form>
                    </div>
                    <!-- form_1 close -->
                </div>
                <!-- main_form_side close -->


                <div id="giftcard_remove_text"></div>


            </div>
            <!-- main_box_side_inner close -->



            <form action='orderInvoice.php' method='post' id="cart_side_form">
            <?php
                $functions->setFormToken('WebOrderReadyForCheckOut');
                $storeCountry   =   $productClass->currentCountry();
                $country_list   =   $functions->countrylist();
                $countryName    =   $country_list[$storeCountry];
                echo "<input type='hidden'  name='storeCountry'   value='{$storeCountry}'/>";
                echo "<input type='hidden'  name='shippingWidget' value='{$storeCountry}'/>";
            ?>
                <input type="hidden" name="order_submit" value="1" id="order_submit" >
                <input type="hidden" name="price_simple" value="0" id="price_simple">
                <input type="submit" class="submit_side2" id="cart_side_grandtotal" value="">

            </form>

        </div>
        <!-- main_box_side close -->
    </div>
    <!-- cart_side close -->