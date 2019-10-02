<?php
    ob_start();
    $functions->require_once_custom("shipping");
    $shipping   =   new shipping();
    $shipping->addNewShippingSubmit();
?>
<h4 class="sub_heading"><?php echo _uc($_e['Shipping']); ?></h4>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Shipping View']); ?></a></li>
        <li><a href="#profile" role="tab" data-toggle="tab"><?php echo _uc($_e['New Shipping']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Shipping View']); ?></h2>
            <?php $shipping->ShippingList(); ?>

        </div>

        <div class="tab-pane fade container-fluid" id="profile">
            <h2 class="tab_heading"><?php echo _uc($_e['Add New Shipping Country']); ?></h2>
            <?php $shipping->addNewShippingForm(); ?>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            tableHoverClasses();
        });

        function deleteShipping(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'shipping/shipping_ajax.php?page=deleteShipping&id='+id,
                    data: { id:id }
                }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('table').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo _js($_e['Delete Fail Please Try Again.']); ?>');
                    }
                    else{
                        jAlertifyAlert(data);
                    }
                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
            }
        };
    </script>
<script src="shipping/js/shipping.js"></script>
<?php return ob_get_clean(); ?>