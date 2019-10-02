<?php

if(isset($_POST['submit'])){
echo "<pre>"; print_r($_POST['cart_list']); echo "</pre>";
}
else {

function number_to_words($number){

    if ($number > 999999999) {

        throw new Exception("Number is out of range");

    }

    $Gn = floor($number / 1000000); /* Millions (giga) */

    $number -= $Gn * 1000000;

    $kn = floor($number / 1000); /* Thousands (kilo) */

    $number -= $kn * 1000;

    $Hn = floor($number / 100); /* Hundreds (hecto) */

    $number -= $Hn * 100;

    $Dn = floor($number / 10); /* Tens (deca) */

    $n = $number % 10; /* Ones */

    $cn = round(($number - floor($number)) * 100); /* Cents */

    $result = "";

    if ($Gn) {

        $result .= number_to_words($Gn) . " Million";

    }

    if ($kn) {

        $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand";

    }

    if ($Hn) {

        $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";

    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",

        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",

        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",

        "Nineteen");

    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",

        "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n) {

        if (!empty($result)) {

            $result .= " and ";

        }

        if ($Dn < 2) {

            $result .= $ones[$Dn * 10 + $n];

        } else {

            $result .= $tens[$Dn];

            if ($n) {

                $result .= "-" . $ones[$n];

            }

        }

    }

    if ($cn) {

        if (!empty($result)) {

            $result .= ' and ';

        }

        $title = $cn == 1 ? 'paisa ' : 'paisa';

        $result .= strtolower(number_to_words($cn)) . ' ' . $title;

    }

    if (empty($result)) {

        $result = "zero";

    }

    return $result;

}

function getProductManinImg__($pid)

{

    $qry = mysql_query("SELECT `pImg_file` FROM `product_img` WHERE `pId`='$pid' ORDER BY `pImg_sort` ASC LIMIT 1 ");

    if (mysql_num_rows($qry) != 0) {

        $data = mysql_fetch_array($qry);

        $r = str_replace("_th", "_sm", trim($data['pImg_file']));

        if (file_exists("../images/products_img/" . $r)) {

            return "<img src=../images/products_img/$r width=250>";

        }

    } else {

        return false;

    }

}

function getProductDesc__($pid)

{

    $qry = mysql_query("SELECT `pDesc` FROM `product_details` WHERE `pId`='$pid'");

    if (mysql_num_rows($qry) != 0) {

        $data = mysql_fetch_array($qry);

        $r = trim($data['pDesc']);

    } else {

        $r = '';

    }

    return $r;

}

if (isset($_GET['create']) && $_GET['create'] == 'quote'

    && isset($_POST['cart_list']) && !empty($_POST['cart_list'])

) {

	$file_name=time().rand(99,999).'CSS';



    $user = '__' . $_SESSION['user'];

    $pay_terms = $_POST['pay_terms'];

    $sender_name = $_POST['reciver_name'];

    $sender_email = $_POST['reciver_email'];

    $sender_phone = '-';

    $sender_address = '-';

    $sender_city = '-';

    $sender_country = '-';

    $reciver_name = $_POST['reciver_name'];

    $reciver_address = $_POST['reciver_address'];

    $reciver_city = $_POST['reciver_city'];

    $reciver_country = $_POST['reciver_country'];

    $reciver_phone = $_POST['reciver_phone'];

    $reciver_email = $_POST['reciver_email'];

    $reciver_note = strip_tags($_POST['reciver_note']);

    // $price_mode = 'pPrice';

    // if ($price_mode == 'pPrice') {

    //     $pcode = 'local price code';

    // } else {

    //     $pcode = 'int price code';

    // }

    // $qry_pmc = mysql_query("SELECT `checkText` FROM `checks` WHERE `checkName`='$pcode' ");

    // $data_pmc = mysql_fetch_array($qry_pmc);

    $pmc_code = 'PKR';

    $invoice_number = uniqid();

    $in_place_date = date("Y-m-d H:i:s");

    $pmc_code;

    $sender_name;

    $user;

    $reciver_name;

    $reciver_address;

    $reciver_city;

    $reciver_country;

    $reciver_phone;

    $reciver_email;

    $reciver_note;

    ;

    $form_prod = '';

    $i = 0;

    ob_start(); ?>

    <style>

        table {

            width: 100%;

        }

        .tableBorder {

            width: 100%;

            padding-bottom: 20px;

        }

        .tableBorder td {

            border: solid #000 1px;

            border-bottom: 0;

            border-right: 0;

        }

        .tableBorder td:last-child {

            border-right: solid #000 1px;

        }

        .tableBorder tr:last-child td {

            border-bottom: solid #000 1px;

        }

        .tableBorder thead td {

            text-transform: uppercase;

            text-align: center;

            font-weight: bold;

        }

        #total span {

            text-transform: uppercase;

            font-weight: bold;

            border-top: solid #000 2px;

            border-right: solid #000 2px;

            border-left: solid #000 2px;

            border-bottom: solid #000 2px;

            padding: 5px;

            font-size: 24px;

            margin: 5px;

        }

    </style>

    <div align="center">

        <img src="../images/banner/101.jpg"/>

    </div>

    <div id="invoice_text" align="center"

         style=" border-top: solid #000 1px; border-bottom: double #000 4px; font-weight: bold; font-size: 28px;">

        Quotation

    </div>

    <br/>

    <table style="width: 100%">

        <tr>

            <td colspan=3 style=" width: 100%; text-align: right;">Date : <?php echo $in_place_date; ?></td>

        </tr>

        <tr>

            <td style=" width: 80px">Quotation #</td>

            <td style=" width: 80%; text-align: left;"><?php echo $file_name; ?></td>

        </tr>

        <tr>

            <td valign="top">To,</td>

            <td style="text-align: left;"><?php echo $reciver_name . "<br>" . $reciver_address . "<br>" . $reciver_city . "-" . $reciver_country . "<br>" . $reciver_phone; ?></td>

        </tr>

    </table>

    <page_footer>

        <table class="page_footer" style=" font-size: 11px;">

            <tr>

                <td style="width: 33%; text-align: left;">

                    Quotation # <?php echo $file_name; ?>

                </td>

                <td style="width: 24%; text-align: center">

                    page [[page_cu]]/[[page_nb]]

                </td>

                <td style="width: 43%; text-align: right">

                    IBMS V4.3.2 By Interactive Media (www.imedia.com.pk)

                </td>

            </tr>

        </table>

    </page_footer>

    <br/>

    <table class="tableBorder" border="0" style="width: 100%" cellpadding="0" cellspacing="0">

    <thead>

    <tr>

        <td style=" width: 6%;">S.No</td>

        <td style=" width: 10%;">ITEM CODE</td>

        <td style=" width: 48%;">ITEM</td>

        <td style=" width: 6%;">QTY</td>

        <td style=" width: 15%;">PRICE</td>

        <td style=" width: 15%;">AMOUNT</td>

    </tr>

    </thead>

    <?php

    $total = 0;

    $sr = 0;

    foreach ($_POST['cart_list'] as $itemId) {

        $id = (int)$itemId;

        $__pid = "pro_" . $id;

        $pid = $itemId;

        $__p_name = "p_name_" . $id;

        $p_name = $_POST[$__p_name];

        $__p_qyt = "p_qyt_" . $id;

        $p_qyt = $_POST[$__p_qyt];

        $__p_price = "price_" . $id;

        $p_price = $_POST[$__p_price];

        $add_price = '0';

        $__brand = "brand_" . $id;

        $brand = $_POST[$__brand];

        $i++;

        $p_total_price = ($p_price + $add_price) * $p_qyt;

        $sr++;

        $price = $p_price + $add_price;

        $amount = $price * $p_qyt;

        $total += $amount;

        echo '

		<tr>

		    <td style=" width: 6%;">' . $sr . '</td>

		    <td style=" width: 10%;">' . $pid . '</td>

		    <td style=" width: 48%;">' . $p_name . "<br />" . strip_tags(html_entity_decode(base64_decode(getProductDesc__($pid)))) . "<br>" . getProductManinImg__($pid) . '</td>

		    <td style=" width: 6%;">' . $p_qyt . '</td>

		    <td style=" width: 15%;">' . $price . '</td>

		    <td style=" width: 15%;">' . $amount . '</td>

		</tr>

		';

    }

}

?>

</table>

    <br />

    <div id="total" style="text-align:center; position: relative; left: 0; width: 100%">

        <br />

        <span>TOTAL PKR <?php echo $total; ?>/-</span><br />

        <?php echo number_to_words($total); ?> Only

    </div>



    <div id="total" align="left">

        <br/><br/>

        <strong>Terms and Condtiotions: </strong>

        <?php echo $reciver_note; ?>

        <p style="text-align: center">

            <br/>

            www.citiscientific.com

        </p>

    </div>

<?php

include("html2pdf.class.php");

/*require_once('html2pdf.class.php');*/

$content = ob_get_clean();

/*require_once(dirname(__FILE__).'./html2pdf.class.php');*/

try {

    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));

    $html2pdf->pdf->SetDisplayMode('fullpage');

    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));

	$html2pdf->Output('../quote/'.$file_name . '.pdf', 'F');

    $html2pdf->Output($file_name . '.pdf');

	

	$file_name=$file_name.".pdf";

	mysql_query("INSERT INTO  quote (

	`qName` ,

	`qEmail` ,

	`qDate` ,

	`qFile`

	)

	VALUES

	(

	'$reciver_name',

	'$reciver_email',

	'$in_place_date',

	'$file_name'

	)");

	

	

} catch (HTML2PDF_exception $e) {

    echo $e;

    exit;

}

}

?>