<?php
ob_start();
$sale = new sale();
$product = new product();

$saleForm = include_once('pHoleSaleForm.php');

?>
<div>
    <h4 class="sub_heading"><?php echo _uc($_e['Discount Products']); ?></h4>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist" >
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['WholeSale Products']); ?></a></li>
        <li><a href="#product_draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Sale Off']); ?></a></li>
        <li><a href="#prodcut_pending" role="tab" data-toggle="tab"><?php echo _uc($_e['Pending']); ?></a></li>
        <li><a href="#product_expire" role="tab" data-toggle="tab"><?php echo _uc($_e['Expire']); ?></a></li>
        <li><a href="#product_sale" role="tab" data-toggle="tab"><?php echo _uc($_e['New Sale Offer']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2 class="tab_heading"><?php echo _uc($_e['WholeSale Products']); ?></h2>
                <?php $sale->discountView(); ?>
        </div>
        <div class="tab-pane fade container-fluid" id="product_draft">
            <h2 class="tab_heading"><?php echo _uc($_e['Discount Status Off']); ?></h2>
            <?php $sale->productDiscountDraft(); ?>
        </div>
        <div class="tab-pane fade container-fluid" id="prodcut_pending">
            <h2 class="tab_heading"><?php echo _uc($_e['Pending']); ?></h2>
            <?php $sale->productDiscountPending(); ?>
        </div>
        <div class="tab-pane fade container-fluid" id="product_expire">
            <h2 class="tab_heading"><?php echo _uc($_e['Expire']); ?></h2>
            <?php $sale->productDiscountExpire(); ?>
        </div>
        <div class="tab-pane fade container-fluid" id="product_sale">
            <h2 class="tab_heading"><?php echo _uc($_e['New Sale Offer']); ?></h2>
            <?php echo $saleForm; ?>
        </div>

    </div>


</div>

    <script>
        $(document).ready(function(){
            tableHoverClasses();
        });



        function discountProductDel(ths){
            var remove;
            var checkedValues='';
            var i=true;
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                checkedValues   = $(ths).attr('data-id');
                remove          = '.p_'+checkedValues;
                $.ajax({
                    type: 'POST',
                    url: "product_management/product_ajax.php?page=holeSaleDel",
                    data: { id:checkedValues }
                }).done(function(data)
                    {
                        if(data=='1'){
                            setTimeout(function(){
                                $(remove).hide(700, function(){
                                    $(remove).remove();
                                    btn.removeClass('disabled');
                                    btn.children('.trash').show();
                                    btn.children('.waiting').hide();
                                });
                            },300);
                        }
                        else{
                            btn.removeClass('disabled');
                            btn.children('.trash').show();
                            btn.children('.waiting').hide();
                            jAlertifyAlert('<?php echo _uc($_e['Delete Fail Please Try Again.']); ?>');
                        }
                    });
            }
        }
    </script>

<?php return ob_get_clean(); ?>