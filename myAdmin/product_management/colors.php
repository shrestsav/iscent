<?php
ob_start();
$color = new colors();
$color->processAddcolor("addcolor");

?>
<h4 class='sub_heading'><?php echo _uc($_e['Color Management']); ?></h4>

<script type="text/javascript">

    var fc = new formControllers();
    fc.anchorByPost("a[data-method='post']");

    var list_count = 1;
    var list_count2 = 1;

    $(function () {

        list_count = Number($("#slot_table > tbody > tr").length);
        list_count2 = Number($("#slot_table2 > tbody > tr").length);

        color_picker();
        $("#slot_table,#slot_table2").on("click", ".color_remove", function () {
            $(this).closest("tr").remove();
        });

        $(".table tr").hover(function () {
            $(".btn-group .btn", this).addClass('btn-primary');
        }, function () {
            $(".btn-group .btn", this).removeClass('btn-primary');
        });

    });



    function addSlot() {
        list_count++;
        var x = '<tr style="display:none">' +
            '<td>' + list_count + ') <i class="glyphicon glyphicon-remove color_remove"></i></td> ' +
            '<td> <input type="text" autocomplete="off" class="inp color_picker form-control" name="addcolor[color][]"> </td>' +
            '</tr>';
        $("#slot_table tbody").append(x);
        $("#slot_table tbody tr:last").fadeIn(800);

        color_picker();
    }

    function addSlot2() {
        list_count2++;
        var x = '<tr style="display:none">' +
            '<td>' + list_count2 + ') <i onclick="remove_tr(this)" class="glyphicon glyphicon-remove color_remove"></i></td> ' +
            '<td> <input type="text" autocomplete="off" class="inp color_picker  form-control" name="<?php echo $color->var_edit_fromName; ?>[color][null' + list_count2 + ']"> </td>' +
            '</tr>';
        $("#slot_table2 tbody").append(x);
        $("#slot_table2 tbody tr:last").fadeIn(800);

        color_picker();
    }


</script>

<style>

    .colorBox {
        display: inline-block;
        width: 12px;
        height: 12px;
        margin: 6px;
    }

    .slot_table {
        min-width: 300px;
        position: relative;
        margin-top: 16px;
    }


    .slot_table .icon_fw-remove {
        position: absolute;
        color: #F05B5B;
        display: none;
        right: 0;
        top: 50%;
        margin-top: -7px;
        cursor: pointer;
    }

    .slot_table td {

        position: relative;
        padding: 8px 12px;
    }

    .slot_table tr:hover .icon_fw-remove {
        display: inline-block;
    }


</style>


<div class="bs-example  bs-example-tabs">
    <ul id="myTab" class="nav nav-tabs tabs_arrow">
        <li class="active"><a href="#view_link" data-toggle="tab"><?php echo _uc($_e['List']); ?></a></li>
        <li class=""><a href="#add_link" data-toggle="tab"><?php echo _uc($_e['Add Color']); ?></a></li>
    </ul>
    <div id="myTabContent" class="tab-content" style="padding-top: 20px;">
        <div class="tab-pane fade active in" id="view_link">
            <h2  class="tab_heading"><?php echo _uc($_e['View All Colors']); ?> </h2>
            <p>
                <?php
                $color->colorList();
                ?>
            </p>
        </div>

        <div class="tab-pane fade " id="add_link">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Color']); ?></h2>
            <p>

            <div>
                <form method="post">
                    <?php $functions->setFormToken('productColorAdd'); ?>
                    <div style="font-size: 16px" class="col-sm-4">
                        <?php echo _uc($_e['Color Name']); ?> : <input style="font-size: inherit;" type="text" class="inp form-control" name="addcolor[name]">
                    </div>
<div class="clearfix"></div>

                    <table id="slot_table" class="slot_table">
                        <tbody>
                        <tr>
                            <td> 1) <i class="glyphicon glyphicon-remove color_remove"></i></td>
                            <td><input type="text" class="inp color_picker form-control" autocomplete="off" name="addcolor[color][]">
                            </td>
                        </tr>
                        <tr>
                            <td> 2) <i class="glyphicon glyphicon-remove color_remove"></i></td>
                            <td><input type="text" autocomplete="off" class="inp color_picker form-control" name="addcolor[color][]">
                            </td>
                        </tr>
                        <tr>
                            <td> 3) <i class="glyphicon glyphicon-remove color_remove"></i></td>
                            <td><input type="text" autocomplete="off" class="inp color_picker form-control" name="addcolor[color][]">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-info" onclick="addSlot(); return false;">
                        <i class="icon_bs-plus"></i> <?php echo _uc($_e['Add Slot']); ?>
                    </button>

                    <button type="submit" class="btn btn-success">
                        <?php echo _u($_e['SUBMIT']); ?>
                    </button>

                </form>

            </div>
            </p>
        </div>
    </div>
</div>



<?php $productF->modal('Update Color','color'); ?>
<?php return ob_get_clean(); ?>

