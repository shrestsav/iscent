<?php

require_once(__DIR__."/../../global.php");

global $webClass, $dbF, $functions;





//var_dump($_GET);



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

    global $dbF;



    $sql = "SELECT `image` FROM `product_image` WHERE `product_id`='$pid' LIMIT 1 ";

    $data = $dbF->getRow($sql,false);



    if ($dbF->rowCount>0) {



        $imag = $data['image'];



        $image = WEB_URL.'/images/'.$imag;



        



            return "<img src=$image width=250>";



        



    } else {



        return false;



    }



}



function insertQuote($array)



{

    global $dbF;



    $sql = 'INSERT INTO `quote`(`qName`, `qEmail`, `qFile`, `qDate`) VALUES (?,?,?,?)';



    $dbF->setRow($sql,$array);



}



function getProductDesc__($pid)



{

global $dbF;

    $sql = "SELECT `prodet_shortDesc` FROM `proudct_detail` WHERE `prodet_id`='$pid' LIMIT 1 ";

    $desc = $dbF->getRow($sql,false);



    if ($dbF->rowCount>0) {

        if($pid > 3408){
            $descript = unserialize($desc['prodet_shortDesc']);
            $description = $descript['English'];
        }
        else{
            $description = $desc['prodet_shortDesc'];
        }

        //$descript = unserialize($desc['prodet_shortDesc']);

        //$description = $descript['English'];



    } else {



        $description = '';



    }

    // if($pid > 3408){
    //     return strip_tags(html_entity_decode(base64_decode($description)));
    //     }
    //     else{
    //     return strip_tags(html_entity_decode(($description));
    //     }

    return strip_tags(html_entity_decode($description));


}



function getProductName__($pid)



{

global $dbF;

    $sql = "SELECT `prodet_name` FROM `proudct_detail` WHERE `prodet_id`='$pid' LIMIT 1 ";

    $name = $dbF->getRow($sql,false);



    if ($dbF->rowCount>0) {
        if($pid > 3408){
            $n = unserialize($name['prodet_name']);
            $pro_name = $n['English'];
        }
        else{
            $pro_name = $name['prodet_name'];
        }


        //$n = unserialize($name['prodet_name']);

        //$pro_name = $n['English'];



    } else {



        $pro_name = '';



    }



    return $pro_name;



}

function getQuoteTop__($boxName)



{

global $dbF;

    $sql = "SELECT * FROM `box` WHERE `box` ='$boxName' ";
    $data = $dbF->getRow($sql);

if ($dbF->rowCount>0) {
    $short_des =  unserialize($data['short_desc']);
    $short_desc = $short_des['English']; 
    //$short_desc =  $data['short_desc'];

}
    
else {

$short_desc = '';

}



    return $short_desc;



}



if (isset($_POST['cart_list']) && !empty($_POST['cart_list'])



) {



	$file_name=time().rand(99,999).'CSS';



    $pay_terms = $_POST['pay_terms'];



    $reciver_name = $_POST['reciver_name'];



    $reciver_address = $_POST['sender_address'];



    $reciver_city = $_POST['sender_city'];



    $reciver_country = $_POST['sender_country'];



    $reciver_phone = $_POST['reciver_phone'];



    $reciver_email = $_POST['reciver_email'];



    $reciver_note = strip_tags($_POST['reciver_note']);



    $pcode = $_POST['priceCode'];



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





    $file_name1 = $file_name.".pdf";

    $aray = array($reciver_name,$reciver_email,$file_name1,$in_place_date);

    insertQuote($aray);



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
   <?php echo getQuoteTop__('box3'); ?>
        <!-- <img src="<?php //echo WEB_URL; ?>/images/101.jpg"/> -->



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



        $pArray     =   explode("_",$itemId);

        $pIds       =   $pArray[1];

        $pArray     =   explode("-",$pIds); 

        $pId        =   $pArray[0]; // 491



        $storeId    =   $pArray[3];



        $pqty = "pQty_p_".$pId."-0-0-".$storeId;

        $ppriceSingle = "pPrice_p_".$pId."-0-0-".$storeId;

        $proTotal = "pTotalprice_p_".$pId."-0-0-".$storeId;



        $pro_qty = $_POST[$pqty];

        $single_price = $_POST[$ppriceSingle];

        $pro_total = $_POST[$proTotal];



        $total += $pro_total;



        $sr++;





        echo '



		<tr>



		    <td style=" width: 6%;">' . $sr . '</td>



		    <td style=" width: 10%;">' . $pId . '</td>



		    <td style=" width: 48%;">'.getProductName__($pId). "<br>" . getProductDesc__($pId) . "<br>" . getProductManinImg__($pId) . '</td>



		    <td style=" width: 6%;">' . $pro_qty . '</td>



		    <td style=" width: 15%;">' . 'PKR '.$single_price . '</td>



		    <td style=" width: 15%;">' . 'PKR '. $pro_total . '</td>



		</tr>



		';

        //

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

//echo $content = ob_get_clean();

$content = ob_get_clean();

//exit;

include("html2pdf.class.php");



/*require_once('html2pdf.class.php');*/





/*require_once(dirname(__FILE__).'./html2pdf.class.php');*/



try {



    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));



    $html2pdf->pdf->SetDisplayMode('fullpage');



    $html2pdf->writeHTML($content);



    $html2pdf->Output('../../../uploads/files/quote/'.$file_name . '.pdf', 'F');



    $html2pdf->Output($file_name . '.pdf');

    



} catch (HTML2PDF_exception $e) {



    echo $e;



    exit;



}





//echo $a."<br>";

//echo getProductDesc__(7)."<br>";

//echo getProductManinImg__(7);

//}



?>