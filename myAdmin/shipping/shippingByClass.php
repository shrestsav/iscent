<?php
ob_start();

$functions->require_once_custom("shipping");
global $dbF;

$shipping   =   new shipping();
$shipping->shippingClassFormSubmit();
$shipping->shippingClassFormUpdate();

if(isset($_GET['editId']) && $_GET['editId'] != ''){
    echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['Shipping By Classes']) .'</h4>';
    echo '<a href="-'.$functions->getLinkFolder().'?page=shippingByClass" class="btn btn-primary">'. _u($_e['GO BACK']) .'</a><br><br>';
    $shipping->shippingClassForm();
}else{ ?>

    <h4 class="sub_heading"><?php echo _uc($_e['Shipping By Classes']); ?></h4>
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active']); ?></a></li>
        <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active']); ?></h2>
            <?php
                $shipping->shippingClassView();
            ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php
                $shipping->shippingClassDraft();
            ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Add New']); ?></h2>
            <?php
                $shipping->shippingClassForm(true);
            ?>
        </div>
    </div>

<?php } ?>



<script>
    $(document).ready(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function deleteShipClass(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'logs/logs_ajax.php?page=returnFormDel&id='+id,
                data: { id:id }
            }).done(function(data)
            {
                ift =true;
                if(data=='1'){
                    ift = false;
                    btn.closest('tr').hide(1000,function(){$(this).remove()});
                }
                else if(data=='0'){
                    jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
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
<?php return ob_get_clean(); ?>