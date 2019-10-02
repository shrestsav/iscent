<?php
ob_start();
$scale = new scales();
global $productF;


$scale->processAddScale("addScale");

?>

<h4 class='sub_heading'><?php echo _uc($_e['Scale Management']); ?></h4>
<script type="text/javascript">

    var fc = new formControllers();

    var list_count = 1;
    var list_count2 = 1;

    $(function () {
        list_count = Number($("#slot_table > tbody > tr").length);
        list_count2 = Number($("#slot_table2 > tbody > tr").length);

        $("#slot_table,#slot_table2").on("click", ".color_remove", function () {
            $(this).closest("tr").remove();
        });

        $(".table tr").hover(function () {
            $(".btn-group .btn", this).addClass('btn-primary');
        }, function () {
            $(".btn-group .btn", this).removeClass('btn-primary');
        });

    })

    function addSlot() {
        list_count++;
        var x = '<tr style="display:none">' +
            '<td>' + list_count + ') <i class="glyphicon glyphicon-remove color_remove"></i> </td> ' +
            '<td> <input type="text" autocomplete="off" class="inp form-control" name="addScale[scale][]"> </td>' +
            '</tr>';
        $("#slot_table tbody").append(x);
        $("#slot_table tbody tr:last").fadeIn(800);
    }

    function addSlot2() {
        list_count2++;
        var x = '<tr style="display:none">' +
            '<td>' + list_count2 + ') <i onclick="remove_tr(this)" class="glyphicon glyphicon-remove color_remove"></i> </td> ' +
            '<td> <input  type="text" autocomplete="off" class="inp form-control" name="<?php echo $scale->var_edit_fromName; ?>[scale][null' + list_count2 + ']"> </td>' +
            '</tr>';
        $("#slot_table2 tbody").append(x);
        $("#slot_table2 tbody tr:last").fadeIn(800);
    }


</script>

<style>

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


<div class="bs-example bs-example-tabs">
    <ul id="myTab" class="nav nav-tabs tabs_arrow">
        <li class="active"><a href="#view_link" data-toggle="tab"><?php echo _uc($_e['List']); ?></a></li>
        <li class=""><a href="#add_link" data-toggle="tab"><?php echo _uc($_e['Add Scale']); ?></a></li>
    </ul>
    <div id="myTabContent" class="tab-content" style="padding-top: 20px;">
        <div class="tab-pane fade active in" id="view_link">
            <p>
                <?php
                $scale->scaleList();
                ?>
            </p>
        </div>

        <div class="tab-pane fade " id="add_link">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Scale']); ?></h2>
            <p>

            <div>
                <form method="post">
                    <?php $functions->setFormToken('productScaleAdd'); ?>
                    <div style="font-size: 16px" class="col-sm-4">
                        <?php echo _uc($_e['Scale Name']); ?> : <input style="font-size: inherit;" type="text" class="inp form-control" name="addScale[name]">
                    </div>
                    <div class="clearfix"></div>


                    <table id="slot_table" class="slot_table">
                        <tbody>
                        <tr>
                            <td> 1) <i class="icon_fw-remove"></i></td>
                            <td><input type="text" class="inp form-control" autocomplete="off" name="addScale[scale][]"></td>
                        </tr>
                        <tr>
                            <td> 2) <i class="icon_fw-remove"></i></td>
                            <td><input type="text" autocomplete="off" class="inp form-control" name="addScale[scale][]"></td>
                        </tr>
                        <tr>
                            <td> 3) <i class="icon_fw-remove"></i></td>
                            <td><input type="text" autocomplete="off" class="inp form-control" name="addScale[scale][]"></td>
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


<?php $productF->modal('Scale','scale'); ?>


<?php return ob_get_clean(); ?>

