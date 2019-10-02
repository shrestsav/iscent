<?php
ob_start();
$view_link = '';
$alerts = '';
$currency = new currency_management();
$currency->add_currecny_controler('add_currency_form');

$country_list = $functions->countrySelectOption();
global $_e;

$add_link = '
    <form method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">'. _uc($_e['Country']) .'</label>
                <div class="col-sm-10">
                  <select class="form-control" name="add_currency_form[country]">'. $country_list .'</select>
                </div>
              </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">'. _uc($_e['Currency Name']) .'</label>
                <div class="col-sm-10">
                  <input type="text" autocomplete="off" class="form-control" name="add_currency_form[currency]">
                </div>
              </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">'. _uc($_e['Currency Symbol']) .'</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" autocomplete="off" name="add_currency_form[symbol]" >
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">'. _uc($_e['ACTION']) .'</label>
                <div class="col-sm-10">
                  <button class="btn btn-success">'. _u($_e['ADD']) .'</button>
                </div>
              </div>
    </form>';


$functions->dTableT();
$view_link .= '
<table class="table table-hover tableIBMS dTableT table-responsive" >
    <thead>
        <tr>
            <th>'. _u($_e['Country']) .'</th>
            <th>'. _u($_e['Currency']) .'</th>
            <th>'. _u($_e['Symbol']) .'</th>
            <th>'. _u($_e['ACTION']) .'</th>
        </tr>
    </thead>';

$getList = $currency->getList();
if ($getList) {
    foreach ($getList as $data) {
        $con = $functions->countrylist()[$data['cur_country']];
        $view_link .= <<<HTML
    <tr class="$data[cur_id]_currency">
        <td  width='25%'>$con</td>
        <td>$data[cur_name]</td>
        <td>$data[cur_symbol]</td>
        <td>

        <div class="btn-group btn-group-sm">
          <a data-toggle="modal" href="#currencyEditModal" onclick="formEditInit('$data[cur_id]','$data[cur_country]','$data[cur_name]','$data[cur_symbol]')"  class="btn"><i class="glyphicon glyphicon-edit"></i></a>
          <a data-id='$data[cur_id]' onclick='AjaxDelScript(this);' class='btn secure_delete'>
                <i class='glyphicon glyphicon-trash trash'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            </a>
        </div>

        </td>
    </tr>
HTML;
    }
} else {
    $view_link .= '
    <tr class="danger">
        <td colspan="4">'. _uc($_e['No data available!']) .'</td>
    </tr>';

}

$view_link .= "</table>";

?>


    <script type="text/javascript">
        var fc = new formControllers();

        $(function () {
            $("tr").hover(function () {
                $(".btn-group .btn", this).addClass('btn-primary');
            }, function () {
                $(".btn-group .btn", this).removeClass('btn-primary');
            });
        });


        function formEditInit(id, cn, cu, sy) {
            //edit_currency_form_selectbox
            fc.inpval("[name='edit_currency_form[symbol]']", sy);
            fc.inpval("[name='edit_currency_form[currency]']", cu);
            fc.inpval("[name='edit_currency_form[cid]']", id);
            fc.selectboxValueSelector("edit_currency_form_selectbox", cn);
        }
    </script>

    <h4 class="sub_heading"><?php echo _uc($_e['Currency Management']); ?></h4>

<?php echo $alerts; ?>

    <div class="bs-example bs-example-tabs">
        <ul id="myTab" class="nav nav-tabs tabs_arrow">
            <li class="active"><a href="#view_link" data-toggle="tab"><?php echo _uc($_e['List']); ?></a></li>
            <li class=""><a href="#add_link" data-toggle="tab"><?php echo _uc($_e['Add Currency']); ?></a></li>
        </ul>
        <div id="myTabContent" class="tab-content" style="padding-top: 20px;">
            <div class="tab-pane fade active in" id="view_link">
                <p><?php echo $view_link; ?></p>
            </div>

            <div class="tab-pane fade " id="add_link">
                <p><?php echo $add_link; ?></p>
            </div>
        </div>
    </div>



    <div class="modal fade" id="currencyEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo _uc($_e['Edit Currency Information']); ?></h4>
                </div>


                <form method="post" id="currency_update" class="form-horizontal">
                    <div class="modal-body">
                        <input type="hidden" name="edit_currency_form[cid]" id="currency_edit_id" value="0"/>

                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo _uc($_e['Country']); ?></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="edit_currency_form_selectbox"
                                        name="edit_currency_form[country]"><?php echo $country_list; ?></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo _uc($_e['Currency Name']); ?></label>
                            <div class="col-sm-8">
                                <input type="text" autocomplete="off" class="form-control"
                                       name="edit_currency_form[currency]">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo _uc($_e['Currency Symbol']); ?> </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" autocomplete="off"
                                       name="edit_currency_form[symbol]">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _uc($_e['Close']); ?></button>
                        <button type="button" class="btn btn-primary save_button" data-loading-text="<?php echo _uc($_e['Saving...']); ?>" onclick="AjaxUpdateScript(this);">
                            <i class="fa fa-check-square-o success" style="display: none"></i>
                            <i class="fa fa-exclamation-triangle fail" style="display: none"></i>
                            <span class="text"><?php echo _uc($_e['Update']); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
$currency->ajaxcurrency();
?>
    <div id="edit_form" style="display: none; margin-top: 30px;"></div>

<?php return ob_get_clean(); ?>