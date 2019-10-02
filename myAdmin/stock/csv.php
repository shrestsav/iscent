<?php

if (isset($_GET['export'])) {
    include_once(__DIR__."/export_csv.php");
}

ob_start();
global $_e;
global $adminPanelLanguage;
$_w = array();
$_w['Export Stock'] = '';
$_w['Submit']       = '';
$_w['Import Stock'] = '';
$_w['Stock Inventory Exported File'] = '';
$_w['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.'] = '';
$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Stock Import');
?>
    <h4 class="sub_heading"><?php echo _uc($_e['Import/Export']); ?></h4>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Export Stock']); ?></a></li>
        <li><a href="#import" role="tab" data-toggle="tab"><?php echo _uc($_e['Import Stock']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2 class="tab_heading"><?php echo _uc($_e['Export Stock']); ?></h2>
            <div class=""><?php echo _uc($_e['Note: After Export all stock inventory, Only update data from 2 columns (QTY and location), or you can delete any inventory row.']); ?></div>
            <a href="-<?php echo $functions->getLinkFolder(); ?>?page=csv&export" class="btn btn-primary btn-lg"><?php echo _uc($_e['Export Stock']); ?></a>
        </div>

        <div class="tab-pane fade container-fluid" id="import">
            <h2 class="tab_heading"><?php echo _uc($_e['Import Stock']); ?></h2>
            <?php
                ob_start();
                    include_once( 'import_csv.php' );
                echo $importEmail = ob_get_clean();
            ?>
        </div>
    </div>

<?php return ob_get_clean(); ?>