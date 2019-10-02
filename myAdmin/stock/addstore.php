<?php
ob_start();

require_once("classes/store.php");
$store=new store();

$store->addNewStore();
?>
<h4 class="sub_heading"><?php echo _uc($_e['Store Location']); ?></h4>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['View Stores']); ?></a></li>
        <li><a href="#profile" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Store']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['View Stores']); ?></h2>
            <?php $store->StoreList(); ?>
        </div>

        <div class="tab-pane fade container-fluid" id="profile">
            <h2 class="tab_heading"><?php echo _uc($_e['Add New Store']); ?></h2>
            <?php $store->newStoreForm(); ?>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            tableHoverClasses();
        });
    </script>
<?php return ob_get_clean(); ?>