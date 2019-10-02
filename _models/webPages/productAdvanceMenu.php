<?php
global $productClass;
global $_e,$webClass,$functions,$dbF,$db;
?>

<div class="productAdvanceSearchLeft" >

    <div class="SideBarInner">
        <!-- Start Of Heading-->
        <div class="heading1">
            <span><?php echo _n($_e['Categories']); ?></span>
        </div>
        <!-- ENd Of Heading-->


        <!--Category menu,, describe for width-->
        <div class="categoryMenuWidth">
            <?php echo $productClass->getCategoryList2(); ?>

            <!-- Start Of Heading-->
            <div class="priceRange">
                <div class="heading">
                    <span><?php echo $_e['Price']; ?></span>
                </div>
                <!-- ENd Of Heading-->
                <?php $priceRangeArray = $productClass->getPrinceRange();?>
                <script>
                    $('document').ready(function (e) {
                        $("#rangeSlider").slider({
                            range: true,
                            min: <?php echo $priceRangeArray['min']; ?>,
                            max: <?php echo $priceRangeArray['max']; ?>,
                            values: [<?php echo $priceRangeArray['cMin']; ?>, <?php echo $priceRangeArray['cMax']; ?>],
                            slide: function (event, ui) {
                                $("#priceMin").val(ui.values[0]);
                                $("#priceMax").val(ui.values[1]);
                            }
                        });
                        $("#priceMin").val($("#rangeSlider").slider("values", 0));
                        $("#priceMax").val($("#rangeSlider").slider("values", 1));
                    });
                </script>

                <div class="container-fluid padding-0">
                    <div class="form-group col-xs-6 padding-0">
                        <label class=" padding-0 col-xs-4"><?php echo $productClass->currentCurrencySymbol(); ?></label>
                        <input type="number" data-min="<?php echo $priceRangeArray['min']; ?>" class=" padding-0 col-xs-8" id="priceMin">
                    </div>
                    <div class="form-group col-xs-6 padding-0">
                        <label class=" padding-0 col-xs-4">-<?php echo $productClass->currentCurrencySymbol(); ?></label>
                        <input type="number" data-max="<?php echo $priceRangeArray['max']; ?>" class=" padding-0 col-xs-8" id="priceMax">
                    </div>
                </div>

                <div id="rangeSlider" class="rangeSlider"></div>
            </div>

            <?php echo $productClass->getDistinctColor(); ?>
            <?php echo $productClass->getDistinctSize(); ?>

            <div class="container-fluid text-right padding-0 advanceSearchButton">
                <br>
                <div class="col-xs-6 padding-0">
                    <a href="<?php echo WEB_URL; ?>/products.php" class="btn themeButton"><?php echo _uc($_e['Reset Search']); ?></a>
                </div>
                <div class="col-xs-6 padding-0">
                    <input type="button" onclick="makeAdvanceSearchForm();" class="btn themeButton" value="<?php echo _uc($_e['Filter Search']); ?>">
                </div>
            </div>
            <div class="container-fluid padding-20">
                <br>
            </div>

            <div class="FloatDivSideBar">
            </div>

        </div>

    </div>
</div>