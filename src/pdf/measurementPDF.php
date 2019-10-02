<?php

ob_start();
include(__DIR__."/../../global.php");

global $webClass,$functions,$dbF,$db,$productClass;
if(isset($_SESSION['_uid']) && $_SESSION['_uid']>0) {
    //admin access
}else{
    $login = $webClass->userLoginCheck();
    $loginForOrder = $functions->developer_setting('loginForOrder');
    if (!$login && $loginForOrder != '0') {
        header("Location: login.php");
        exit;
    }
    $userId = $webClass->webUserId();
    if ($userId == '0') {
        $userId = webTempUserId(); // for all orders on temp user..
    }
}

    $id = $_GET['id'];
    if(isset($_GET['orderId'])){
        $sId = $_GET['orderId'];
        $sId = $functions->decode($sId);
        if($id != $sId) {
            exit;
        }
    }else{
        echo "Order Id Not Found.";
        exit;
    }

    $id = '134'; // customid
    $customId = $id;

    $sql             = "SELECT * FROM `order_invoice_product` WHERE order_pIds LIKE '%-%-%-%-$id'";
    $orderProducts   =  $dbF->getRow($sql);
    $orderId         =  $orderProducts['order_invoice_id'];

    $pIds = $orderProducts['order_pIds'];
    $pArray = explode("-", $pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
    $pId = $pArray[0]; // 491
    $scaleId = $pArray[1]; // 426
    $colorId = $pArray[2]; // 435
    $storeId = $pArray[3]; // 5
    @$customId = $pArray[4]; // 5


    $sql            = "SELECT * FROM `order_invoice` WHERE order_invoice_pk = '$orderId' AND orderStatus = 'process'";
    $orderInvoice   =   $dbF->getRow($sql);

    if(empty($orderInvoice)){
        echo "Order Not Found";
        exit;
    }

    $sql = "SELECT * FROM `order_invoice_info` WHERE order_invoice_id = '$orderId'";
    $orderInfo   =   $dbF->getRow($sql);

/*var_dump($orderProducts);
var_dump($orderInvoice);
var_dump($orderInfo);

exit;*/


    $country_list   =   $webClass->functions->countrylist();
    $countryName    =   $country_list[strtoupper($orderInfo['receiver_country'])];

?>
    
<style>

body{
	margin:0px;
	padding:0;
	}
.personal-info{
	display: inline-block;
float: left;
padding-left:20px;
	}
.add-product{
	display: inline-block;
	float: left;
	padding-left:0px;
	}

.personal-info table,.add-product table{
	font-size: 11px;
}
.personal-info input, .add-product input{
	font-size: 12px;
	height: 21px;
	width: 85%;
}
.personal-info input{
	margin-left:5px;
	}
h2,h3,h4{
	padding:0px;
	margin:0px;

	}

.measur td{
	width:32%;
	}
	.single img{width:120px;height:87px;}

.single .txt1{
}

.single .txt2{
	width: 110px;
	height: 60px;
	font-size: 11px;
	border-bottom: 1px solid;
	overflow: hidden;
	padding:0 3px;
	clear: right;
	margin-left: 0px;
	text-align:justify;
}

.single .txt3{
	padding: 0 0px;
	width: 110px;
	margin-top: 1px;
	font-size:11px;
}
.single .txt3 input{
	width:70px;
	margin-left:10px;
}
.single .txt4{
	border:1px solid #777;
	width:45%;
	height:12px;
	margin-left:8px;
}
    </style>
    
   
	 <div style="background: #000;padding: 5px 25px;">
	    <img src="<?php echo WEB_URL; ?>/webImages/logo.png" style="height: 50px"/>
	</div>

<table class="table" >
<tr>
    <td style="vertical-align:top" width="40%">
	<div class="personal-info">
    	<h4>PERSONAL INFORMATION</h4>

        	 <table class="table" >
                 <tr>
                    <td valign="middle">NAME</td>
                    <td><input type="text" name="first_name"  value="<?php echo $orderInfo['receiver_name']; ?>"  readonly="readonly" required /> </td>
          		</tr>
               <tr>
                    <td valign="middle">ADDRESS</td>
                    <td><input type="text" name="address" value="<?php echo $orderInfo['receiver_address']; ?>"  readonly="readonly"  required="required" /> </td>
          		</tr>
                 <tr>
                    <td valign="middle">ZIP CODE</td>
                    <td><input type="text" name="zipCode" value="<?php echo $orderInfo['receiver_post']; ?>"  readonly="readonly" /> </td>
          		</tr>
                 <tr>
                    <td valign="middle">CITY</td>
                    <td><input type="text" name="city"  value="<?php echo $orderInfo['receiver_city']; ?>"  readonly="readonly" required /> </td>
          		</tr>
                <tr>
                    <td valign="middle">COUNTRY</td>
                    <td><input type="text" name="country"  value="<?php echo $countryName;?>"  readonly="readonly" required /> </td>
          		</tr>
                <tr>
                    <td valign="middle">EMAIL</td>
                    <td><input type="text" name="tel" value="<?php echo $orderInfo['receiver_email']; ?>"  readonly="readonly"/> </td>
          		</tr>
                <tr>
                    <td valign="middle">CONTACT</td>
                    <td><input type="text" name="cell" value="<?php echo $orderInfo['receiver_phone']; ?>"  readonly="readonly"/> </td>
          		</tr>
        </table>
    </div>
  </td>
  <td style="vertical-align:top">  
    
    <div class="add-product">
    <h4>ORDER</h4>

        <table class="table" >
                <tr>
                    <td valign="middle">Date</td>
                    <td><input type="text" name="date"  value="<?php echo date("Y-m-d",strtoTime($orderInvoice['invoice_date'])); ?>" id="date"  readonly="readonly"/></td>
                </tr>
				<tr>
                    <td valign="middle">ORDER NUMBER</td>
                    <td><input type="text" name="order_no"  readonly="readonly" id="order_no" value="<?php echo $orderInvoice['invoice_id']; ?>"/> </td>
          		</tr>
            <tr>
                <td valign="middle">Product</td>
                <td>
                    <input type="text"  id="prouct_name" readonly tabindex="3" value="<?php
                    $pName = $orderProducts['order_pName'];
                    $pName = explode(" - ",$pName);
                    $pName = $pName[0];
                    echo $pName; ?>"/>
                </td>
            </tr>

            <tr>
                <td valign="middle">Quantity</td>
                <td><input type="number" name="qty" readonly tabindex="5"  min="1" value="<?php echo $orderProducts['order_pQty']; ?>"/></td>
            </tr>

        </table>


    </div>  
  </td>

    <td style="vertical-align:top">
        <div class="personal-info">
            <table>
                <tr>
                    <td align="center" valign="middle">
                        <img src="<?php echo WEB_URL."/images/".$productClass->productSpecialImage($pId,'main'); ?>"
                             alt="select Image" id="c_image" width="140" style="max-heigth:150px;max-width: 140px;"/></td>
                </tr>
            </table>
    </div>
    </td>

</tr>
  </table>  
    
<br>
<h3>MEASUERMENT</h3>
<?php
			$i=0;
			$j='0';
			$brk='0';

            $sql        = "SELECT * FROM p_custom_submit WHERE id = '$id' AND submitLater = '0'";
            $data       = $dbF->getRow($sql);

            $sql          = "SELECT * FROM p_custom_setting WHERE c_id = '$data[custom_id]'";
            $dataFields   = $dbF->getRows($sql);

            $sql          = "SELECT * FROM p_custom_submit_setting WHERE orderId = '$id'";
            $dataFieldsSubmit   = $dbF->getRows($sql);

            foreach($dataFieldsSubmit as $key=>$val) {
                $fields['fields'][] = $val['setting_name'];
                $fields[$val['setting_name']] = $val['setting_value'];
            }

            foreach($fields['fields'] as $key=>$val) {
                $required = translateFromSerialize($productClass->measurementArray($dataFields,$val,'required'));
                $valTemp  = translateFromSerialize($productClass->measurementArray($dataFields,$val,'name'));
                $valId    = translateFromSerialize($productClass->measurementArray($dataFields,$val,'desc',true));
                $image    = $functions->addWebUrlInLink($productClass->measurementArray($dataFields,$val,'image'));

                $j=$j+1;
                if($j=='1'){
                    echo "
                    <table style='padding:0px 15px; font-size: 11px;'><tr>";
                }
			?>
             <td width="32%">
                	<table class="single">
                    	<tr>
                        	<td><div class="txt1">
                            <?php if(!empty($image) && $image != WEB_URL && $image != WEB_URL."/"){
                                ?>
                                    <img src="<?php echo $image; ?>" />
                                <?php
                                }
							else{
                                $image = WEB_URL."/src/pdf/defaultImg/demo.png";
                                ?>
                                    <img src="<?php echo $image; ?>" />
                                <?php
							}
							 ?></div></td>
                            <td>
                              <div class="txt3"><?php echo $valTemp; ?><br />
                                    <div class="txt4"><?php echo $fields[$val]; ?>&nbsp; CM </div>
                              </div>
                            </td>
                       </tr>
                    </table>
              </td>
                 
         
            
            <?php 
				if($j=='3'){ echo "</tr></table><br/><br/>
				";
					$j='0';
				}
							
				}
				if($j!='0'){
				echo "</tr></table><br/><br/>
				";
				}
				
			
				
		?>
<?php
    $content = ob_get_clean();
    $functions->customPdf($content);

?>