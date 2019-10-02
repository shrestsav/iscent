<?php
if (isset($_GET['admin'])) {
    include("../admin/secure/functions.ini.php");
    if (!log_chk(false)) {
        die("Not Login!");
    }
    $utype = "admin";
} else {
    session_start();
    @include("../functions.ini.php");
    if (!login_validate()) {
        die("Not Login!");
    }
    $utype = "user";
}
function number_to_words($number)
{
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

if (!isset($_GET['invoice']) || empty($_GET['invoice'])) {
    die("Invoice Not Selected!");
}
$invoice = sql_safe($_GET['invoice']);
$qry = mysql_query("SELECT * FROM `invoice` WHERE `in_number`='$invoice' ");
if ((mysql_num_rows($qry)) != 1) {
    die("Invalid Invoice Number!");
}
$d = mysql_fetch_assoc($qry);
if ($utype == "user") {
    if ($_SESSION['user_'] != $d['cUser']) {
        die("Access Denied!");
    }

    if ($d['in_status'] != "processing") {
        if ($d['in_status'] != "dispatched") {
            die("System Rejected!");
        }
    }

}
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
        DELIVERY CHALLAN
    </div>
    <br/>

    <table style="width: 100%">
        <tr>
            <td colspan=3 style=" width: 100%; text-align: right;">Date
                : <?php echo date("d-m-Y", strtotime($d['in_date_time'])); ?></td>
        </tr>

        <tr>
            <td style=" width: 100px" width>Invoice #</td>
            <td style=" width: 80%; text-align: left;"><?php echo $d['in_number']; ?></td>
        </tr>


        <tr>
            <td valign="top">To</td>
            <td style=" text-align: left;"><?php echo $d['in_reciver_name'] . "<br>" . $d['in_reciver_address'] . "<br>" . $d['in_reciver_city'] . "-" . $d['in_reciver_country'] . "<br>" . $d['in_reciver_phone']; ?></td>
        </tr>
    </table>


    <page_footer>
        <table class="page_footer" style=" font-size: 11px;">
            <tr>
                <td style="width: 33%; text-align: left;">
                    Invoice # <?php echo $d['in_number']; ?>
                </td>
                <td style="width: 24%; text-align: center">
                    page [[page_cu]]/[[page_nb]]

                </td>
                <td style="width: 43%; text-align: right">
                    IBMS V2 By Interactive Media (www.imedia.com.pk)
                </td>
            </tr>
        </table>
    </page_footer>



    <br/>

    <table class="tableBorder" border="0" style="width: 100%" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <td style=" width: 15%;">S.No</td>
            <td style=" width: 70%;">ITEM</td>
            <td style=" width: 15%;">QTY</td>
        </tr>
        </thead>

        <?php
        $total = 0;
        $sr = 0;
        $qry2 = mysql_query("SELECT * FROM `order` WHERE `invoice_number`='$d[in_number]' ");
        while ($data = mysql_fetch_assoc($qry2)) {
            $sr++;
            $qyt = $data['order_qyt'];
            $price = $data['order_pPrice'] + $data['order_add_price'];
            $amount = $price * $qyt;
            $total += $amount;
            echo '
		<tr>
		    <td style=" width: 15%;">' . $sr . '</td>
		    <td style=" width: 70%;">' . $data['order_pName'] . '</td>
		    <td style=" width: 15%;">' . $qyt . '</td>
		</tr>
		';
        }
        ?>


    </table>

    <br/>

    <div id="total" align="center">
        <br/>
        <table align="center">
            <tr>
                <td align="center">
                    <p style="text-align:center;">www.citiscientific.com<br/></p>
                </td>
            </tr>
        </table>

    </div>








<?php
include("html2pdf.class.php");
//require_once('html2pdf.class.php');

$content = ob_get_clean();
//require_once(dirname(__FILE__).'./html2pdf.class.php');
try {
    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('invoice.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>