<?php 
    global $dbF, $functions, $productClass, $_e;
?>
    <!--  checkout offer modal -->
    <div class="modal fade" id="checkoutOfferModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            
            <div class="modal-content">
                
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> -->
                    <h4 class="modal-title" id="myModalLabel"><?php //$dbF->hardWords('Check Out our Offer');?></h4>
                </div>
                
                <div class="modal-body " style="text-align: center;">

                    <div class="check_out_offer_main">
                        <div class="check_out_offer_head">
                            <div class="checkout_offer_line"></div>
                            <div class="checkout_offer_heading_text">
                                <h1 class="cart_check_head"><?php $dbF->hardWords('Check Out our Offer');?></h1>
                            </div>
                        </div>

                        <div id="checkout_offer_container" class="container-fluid padding-0" style="margin-top:30px;" >
                            <?php //echo $checkOutOffer; ?>
                        </div><!--r_box_area end-->

                    </div><!--related_products_area end-->

                    <br><br>



                    
                </div>
                <div class="modal-footer">
                    <button id="checkout_offer_dismiss_btn" type="button" class="btn btn-danger" data-dismiss="modal"><?php $dbF->hardWords('Close');?></button>
                </div>

            </div>
        </div>

    </div>
    <script>
    // use: data-toggle="modal" data-target="#YOUR ID" 
    // for eg: <a href=""  data-toggle="modal" data-target="#checkoutOfferModal"></a>
    $(function() {
        // $('#checkoutOfferModal').modal('show');
        // $('#checkoutOfferModal').modal('hide');
    });
    </script>
    <!-- modal end -->