<?php
ob_start();

require_once("classes/shipping.php");
$shipping=new shipping();
$shipping->addNewShippingUpdate();
//var_dump($_POST);
if(!isset($_GET['country']) || $_GET['country']==""){
    exit;
}

$countryList= $functions->countrylist();
$country    = $_GET['country'];
$price_code = $shipping->productF->productCurrencysymbol($country);
$sql        = "SELECT * FROM `shipping` WHERE `shp_from` = '$country' ";
$data       = $dbF->getRows($sql);

?>
    <h4 class="sub_heading"><?php echo _uc($_e['Shipping']); ?></h4>

    <div class="table-responsive">
        <form method="post">
            <input type="hidden" name="from" value="<?php echo $country; ?>" />
        <table class="table table-hover tableIBMS">
            <thead>
                <tr>
                    <th colspan="5">
                        <div class="text-center col-sm-12"><?php echo $countryList[$country]; //unset($countryList[$country]); ?></div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="gray-tr">
                    <th><?php echo _u($_e['SNO']); ?></th>
                    <th><?php echo _u($_e['SHIPMENT COUNTRY']); ?></th>
                    <th><?php echo _u($_e['SHIPMENT PRICE']); ?></th>
                    <th><?php echo _u($_e['WEIGHT']); ?></th>
                    <th><?php echo _u($_e['INTERNATIONAL SHIPPING']); ?></th>
                </tr>
            <?php
            $i=0;

                foreach($data as $val ){
                    $i++;
                    $cntry = $countryList[$val['shp_to']];
                    unset($countryList[$val['shp_to']]);
                    $symbol = $shipping->productF->productCurrencysymbol($val['shp_price_code']);
                    $weight = $shipping->shpWeightSelect($val['shp_weight']);
                    $checked = ($val['shp_int']=='1') ? "checked": "";

                    echo "<tr>";
                    echo "<td><input type='checkbox' name='toCountry[]' checked value='$val[shp_to]' /> $i </td>";
                    echo "<td>$cntry</td>";
                    echo "<td><input type='text' name='$val[shp_to]_price' value='$val[shp_price]' /> $symbol
                                <input type='hidden' name='$val[shp_to]_price_code' value='".$val['shp_price_code']."' </td>";
                    echo "<td><select name='$val[shp_to]_weight'>$weight</select> </td>";
                    echo '<td>
                             <div class="make-switch"  data-on="success" data-off="danger">
                                 <input type="checkbox" name="'.$val['shp_to'].'_intShp" value="1" '.$checked.' >
                             </div>
                          </td>';
                    echo "</tr>";
                }

            foreach($countryList as $key => $val ){
                $i++;
                $cntry = $countryList[$key];
                $weight = $shipping->shpWeightSelect();

                echo "<tr class='showAll hidden'>";
                echo "<td><input type='checkbox' name='toCountry[]' value='".$key."' /> $i </td>";
                echo "<td>$cntry</td>";
                echo "<td><input type='text' name='".$key."_price' value='' /> $price_code
                        <input type='hidden' name='".$key."_price_code' value='$country' </td>";
                echo "<td><select name='".$key."_weight'>$weight</select> </td>";
                echo '<td>
                             <div class="make-switch"  data-on="success" data-off="danger">
                                 <input type="checkbox" name="'.$key.'_intShp" value="1" >
                             </div>
                          </td>';
                echo "</tr>";
            }

            ?>


            </tbody>
        </table>


        <a href="#" onclick="$('.showAll').removeClass('hidden');$(this).hide();" ><?php echo _uc($_e['Show All Other Countries']); ?></a>
            <br/>
            <button type="submit" class="btn btn-primary btn-lg"><?php echo _u($_e['UPDATE']); ?></button>
        </form>
    </div>
    <script>
        $(document).ready(function(){
            tableHoverClasses();
        });
    </script>
<script src="shipping/js/shipping.js"></script>
<?php return ob_get_clean(); ?>