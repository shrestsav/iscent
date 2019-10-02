<?php $product = new product();

$data = $product->process_editProductById();
if ($data === false) {
    return bs_alert::danger("Unable To Edit Product!", false);
}

$p_detail = $data['details'];
$currency = $data['currency'];
$p_category = $data['category'];
$p_price = $data['price'];
$p_addcost = $data['addcost'];
$p_colors = $data['colors'];


$product->currencyListJson("cdata");

ob_start();

?>
    <script type="text/javascript">
    var curTable = (function (json_data) {
        $self = this;
        /***************/
        this.checkbox_name = "_undefined_";
        this.input_name = "_undefined_";
        this.input_name__extra_class = "";
        this.input_addprice_name = "_undefined_";
        this.slotType = "";
        this.panel_note_footer = "";
        this.panel_heading = "Customize Slots";
        this.always_callback = function () {
            fireSortable();
        };

        this.addSlot_callback = function () {
        };
        /***************/
        this.uniqid = functions.uniqid();
        this.json = (typeof (json_data) == "undefined" ) ? new Object() : json_data;
        this.objNumber = 0;
        this.initializeCheck = false;
        this.divid = "body";
        this.tbody = $("<tbody></tbody>").attr("id", "tbody_" + this.uniqid);
        this.construct = function () {
            this.objNumber = Number(this.json.length) || 0;
        };
        this.construct();

        this.initializeTable = function (id) {
            if (this.initializeCheck == false) {
                this.initializeCheck = true;
                id = (document.getElementById(id)) ? "#" + id : this.divid;
                $table = $("<table></table>").attr({
                    id: "table_" + this.uniqid,
                    class: "table table-condensed table-responsive"
                });
                $thead = $("<thead></thead>");
                $tr = $("<tr></tr>");
                $tr.append("<th>Name</th>");
                var data = this.json;
                for (x in data) {
                    $tr.append("<th>" + data[x].country + " (" + data[x].name + ") " + "</th>");
                }
                $thead.append($tr);
                $table.append($thead);
                $tbody = this.tbody;
                $table.append($tbody);
                var panel_heading = this.panel_heading;
                var slef_panel_note_footer = this.panel_note_footer;
                $(function () {

                    $panel = $("<div class='panel panel-primary'></div>");
                    $panel.append("<div class='panel-heading'>" + "<h3 class='panel-title'>" + panel_heading + "</h3>" +
                        "</div>");
                    $panel_body = $("<div class='panel-body table-responsive'></div>");
                    $panel_body.append($table);
                    $panel_body.append("<div class='panel_note_footer'>" + slef_panel_note_footer + "</div>");
                    $panel.append($panel_body);
                    $(id).append("<br><hr>");
                    $(id).append($panel);
                });
            }
        };

        this.xhtmText = function (rowID) {
            return  "<div class='input-group input-group-sm'>" + "<span class='input-group-addon'>" +
                "<input  type='checkbox' value='" + rowID + "' name='" + this.checkbox_name + "[]'>" + "</span>" +
                "<input type='text' value='' name='" + rowID + "-" + this.input_name + "' class='form-control " + this.input_name__extra_class + "'>" + "</div>";
        };


        this.addSlot = function () {
            if (this.initializeCheck == false) {
                this.initializeTable(this.divid);
            }
            var rowID = functions.uniqid("rowid");
            $tr = $("<tr></tr>");
            $td = $("<td></td>");
            $xht = this.xhtmText(rowID);
            $td.append($xht);
            $tr.append($td);
            var data = this.json;
            for (x in data) {
                $td = $("<td></td>");
                $xht = "<div class='input-group input-group-sm'>" +
                    "<span class='input-group-addon'>" + data[x].symbol +
                    "</span>" +
                    "<input type='text' class='form-control' name=' " + rowID + "-" + this.input_addprice_name + "[" + data[x].id + "]' >" +
                    "</div>";
                $td.append($xht);
                $tr.append($td);
            }
            this.tbody.append($tr);
            this.addSlot_callback();
            this.always_callback();
        }
    });


    var mscale = new curTable(cdata);
    mscale.divid = "tab_sizes_div";
    mscale.checkbox_name = "<?php echo $product->prefix_scaleCheckBox; ?>";
    mscale.input_name = "<?php echo $product->prefix_scaleName; ?>";
    mscale.input_addprice_name = "<?php echo $product->prefix_scaleCost; ?>";


    var addCharges = new curTable(cdata);
    addCharges.panel_heading = "Additional Charges";
    addCharges.divid = "addCharges_div";
    addCharges.checkbox_name = "<?php echo $product->prefix_addCostCheckBox; ?>";
    addCharges.input_name = "<?php echo $product->prefix_addCostName; ?>";
    addCharges.input_addprice_name = "<?php echo $product->prefix_addCostCost; ?>";


    var mcolors = new curTable(cdata);
    mcolors.divid = "tab_color_div";
    mcolors.checkbox_name = "<?php echo $product->prefix_colorCheckBox; ?>";
    mcolors.input_name = "<?php echo $product->prefix_colorName; ?>";
    mcolors.input_addprice_name = "<?php echo $product->prefix_colorCost; ?>";
    /**/
    mcolors.input_name__extra_class = "color_picker";
    mcolors.slotType = "color_picker_input";
    mcolors.addSlot_callback = function () {
        color_picker();
    };


    $(function () {
        fireSortable();
    });

    var fixHelperModified = function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
            $(this).width($originals.eq(index).width())
        });
        return $helper;
    }, updateIndex = function (e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
            $(this).html(i + 1);
        });
    };
    function fireSortable() {
        $(".tableSort tbody").sortable({ helper: fixHelperModified, axis: 'y', placeholder: "ui-state-highlight",
            stop: updateIndex }).disableSelection();
    }


        function process_productDetails(){
            var a = $("#productDetailTable").serialize();
            console.log(a);

            $.ajax({
                type: "POST",
                data: "ajx[auth]=<?php echo md5(session_id()); ?>&"+a
            }).done(function (data){
                   console.log(data);
                });
        }



    </script>



    <style>
        .ui-state-highlight {
            background: #FAEBCC;
            height: 1.5em;
            line-height: 1.2em;
        }
    </style>


<button onclick="process_productDetails()">asd</button>


    <h4>Add Product</h4> <br>


    <!--<form method="post" role="form" class="form-horizontal">-->
        <div class="container">
            <div class="row">
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_bi" data-toggle="tab">Basic Information</a></li>
                        <li><a href="#tab_categroy" data-toggle="tab">Category</a></li>
                        <li><a href="#tab_price" data-toggle="tab">Price</a></li>
                        <li><a href="#tab_sizes" data-toggle="tab">Sizes</a></li>
                        <li><a href="#tab_colors" data-toggle="tab">Colors</a></li>
                        <li><a href="#tab_images" data-toggle="tab">images</a></li>
                    </ul>
                    <div class="tab-content" style="padding-top: 20px">
                        <div class="tab-pane active" id="tab_bi">
                            <form id="productDetailTable">
                            <table id="">
                                <tr>
                                    <td>Name</td>
                                    <td><input type="text" class="inp" value="<?php echo $p_detail['prodet_name']; ?>"
                                               name="<?php echo $product->prefix_productBasicInformation; ?>[name]">
                                    </td>
                                </tr>

                                <tr>
                                    <td>Tags</td>
                                    <td><input type="text" value="<?php echo $p_detail['prodet_tags']; ?>"
                                               name="<?php echo $product->prefix_productBasicInformation; ?>[tags]"
                                               class="inp f-prodet_tags" data-role="tagsinput"></td>
                                </tr>

                                <tr>
                                    <td>Short Description</td>
                                    <td><textarea name="<?php echo $product->prefix_productBasicInformation; ?>[sdesc]"
                                                  class="inp "><?php echo $p_detail['prodet_shortDesc']; ?></textarea></td>
                                </tr>

                                <tr>
                                    <td>Long Description</td>
                                    <td><textarea name="<?php echo $product->prefix_productBasicInformation; ?>[ldesc]"
                                                  class="inp f-prodet_longDesc"><?php echo $p_detail['prodet_longDesc']; ?></textarea></td>
                                </tr>


                                <tr>
                                    <td>Public Access</td>
                                    <td>
                                        <div class="make-switch" data-on="success">
                                            <input type="checkbox" value="1" <?php echo (($p_detail['prodet_publicAccess']>0) ? "checked" : ""); ?> class="f-prodet_addOn" name="<?php echo $product->prefix_productBasicInformation; ?>[publicAccess]">
                                        </div>
                                    </td>
                                </tr>

                            </table>
                            </form>

                        </div>


                        <div class="tab-pane" id="tab_price">
                            <div>   <?php $product->createPricingViewSystem(); ?>
                                <div id="addCharges_div" class="tableSort"></div>
                                <button type="button" class="btn btn-info pull-right" onclick="addCharges.addSlot()">
                                    Add Additional Slot
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_categroy">
                            <script>   $(function () {
                                    $("#tree1").jstree({   "themes": {   "theme": "apple", "dots": true, "icons": true   }, "plugins": [ "themes", "html_data" ]   }).on('loaded.jstree', function () {
                                        $("#tree1").jstree('open_all');
                                    });
                                });   </script>
                            <div id="tree1">   <?php $product->createListOfcategory(); ?>   </div>
                        </div>
                        <div class="tab-pane" id="tab_sizes">
                            <div class="table-responsive tableSort">   <?php $product->createListOfScales(); ?>   </div>
                            <div id="tab_sizes_div" class="tableSort"></div>
                            <button type="button" class="btn btn-info pull-right" onclick="mscale.addSlot()">Add Custom
                                Slot
                            </button>
                        </div>
                        <div class="tab-pane" id="tab_colors">
                            <div class="table-responsive tableSort">
                                <?php $product->createListOfColor(); ?>
                            </div>
                            <div id="tab_color_div" class="tableSort">
                            </div>
                            <button type="button" class="btn btn-info pull-right" onclick="mcolors.addSlot()">
                                Add Custom Slot
                            </button>
                        </div>
                        <div class="tab-pane" id="tab_images"> 6</div>
                    </div>
                </div>
            </div>
        </div>
        <!--<button type="submit">OKO</button>-->
    <!--</form>-->
<?php //return ob_get_clean(); ?>