<?php 
ob_start(); 

require_once("classes/order.php");
$order = new order();

?>
<h4 class="sub_heading"><?php echo _uc($_e['Pending Schedules']); ?></h4>

<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Pending Schedules']); ?></a></li>
</ul>

<div class="tab-pane fade in active container-fluid" id="home">
    <div class="heading_invoice">
        <h2 class="tab_heading"></h2>
    </div>
    <?php $order->getTechincalForms(); ?>
</div>

<?php return ob_get_clean(); ?>