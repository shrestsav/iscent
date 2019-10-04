<?php
include_once("global.php");
global $webClass;
global $productClass, $functions;
include("header.php");
//for direct cart submit use orderInvoice.php?ds
//first direct submit then all avaiable payment method will show, for checkout

$productClass->orderSubmit();
// var_dump($_GET['firstInv']);
if(isset($_GET['order']) && isset($_GET['firstInv'])){

	$order_id = $_GET['order'];
	$firstInv = $_GET['firstInv'];

	$sql = "SELECT `order_ref` FROM `orders` WHERE `order_id` = ?";
	$res = $dbF->getRow($sql, array($order_id));

	$order_ref = $res['order_ref'];

	$sql_det = "SELECT `company_name`,`country`,`mobile` FROM `order_detail` WHERE `order_id` = ?";
	$res_det = $dbF->getRow($sql_det, array($order_id));

	$company_name = $res_det['company_name'];
	$country = $res_det['country'];
	// $address = $res_det['address'];
	$mobile = $res_det['mobile'];
	// $o_country = $res_det['o_country'];

	$default_banner = WEB_URL.'/webImages/default.jpg';


	$params = array(
		'ivp_method'  => 'check',
		'ivp_store'   => '20901',
		'ivp_authkey' => 'vJJrn~6LpK-6FR8f',
		'order_ref'   => $order_ref
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
	curl_setopt($ch, CURLOPT_POST, count($params));
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

	$results = curl_exec($ch);
	curl_close($ch);

	$api_return = base64_encode($results);

	$results = json_decode($results,true);

	$order_status   = $results['order']['status']['code'];
	$trans_ref      = $results['order']['transaction']['ref'];
	$trans_code     = $results['order']['transaction']['code'];
	$agrement_id    = $results['order']['agreement']['id'];

	### Customer Detail

	$email  = $results['order']['customer']['email'];
	$name   = $results['order']['customer']['name']['forenames'].' '.$results['order']['customer']['name']['surname'];
	$address    = $results['order']['customer']['address']['line1'].', '.$results['order']['customer']['address']['city'].', '.$results['order']['customer']['address']['country'];

	switch ($order_status) {
		case '3':

			$sql = "UPDATE `orders` SET 
			`order_status` = ?,
			`agrement_id` = ?
			WHERE `order_id` = ?";

			$dbF->setRow($sql, array('process',$agrement_id,$order_id), false);

			$sql = "UPDATE `invoices` SET 
			`invoice_status` = 'paid',
			`trans_ref` = ?,
			`trans_code` = ?,
			`api_return` = ? 
			WHERE `invoice_pk` = ?";
			$dbF->setRow($sql, array($trans_ref, $trans_code, $api_return, $firstInv), false);

			if($dbF->rowCount > 0){
				$user_id = $productClass->webUserId();
				if($productClass->webUserId()=='0'){
					createWebUserAccount(false,$order_id,'1',$name,$email,
						array
						('gender'=>'',
							'type'=>'',
							'date_of_birth'=>'',
							'phone'=>$mobile,
							'address'=>$address,
						), 
						array(
							'company' => $company_name,
							'firstName' => $results['order']['customer']['name']['forenames'],
							'lastName' => $results['order']['customer']['name']['surname'],
							'country' => $country
						)
					);

					$sql = "SELECT `order_user` FROM `orders`
					WHERE order_id = '$order_id'";
					$res = $dbF->getRow($sql);

					$user_id = $res['order_user'];

				}

				#############  Zoho Create Invoice Start  ##############


				$sql = "SELECT `zoho_contact_id`, `zoho_contact_person` FROM `accounts_user` WHERE `acc_id` = ?";
				$res = $dbF->getRow($sql, array($user_id));

				if(!empty($res)){

					$zoho_contact_id     = $res['zoho_contact_id'];
					$zoho_contact_person = $res['zoho_contact_person'];

					$sql_invDet = "SELECT * FROM `invoices` WHERE `invoice_pk` = ?";
					$res_invDet = $dbF->getRow($sql_invDet, array($firstInv));

					$sql_pid = "SELECT o.`product_id` FROM `orders` o JOIN `invoices` i WHERE o.`order_id` = i.`order_id` AND i.`invoice_pk` = ?";
					$res_pid = $dbF->getRow($sql_pid, array($firstInv));

					$sql_item = "SELECT pd.`prodet_name`,pd.`prodet_shortDesc`,pd.`zoho_item_no`,pp.`propri_price` FROM `proudct_detail` pd JOIN `product_price` pp WHERE pd.`prodet_id` = pp.`propri_prodet_id` AND pd.`prodet_id` = ?";
					$res_item = $dbF->getRow($sql_item, array($res_pid['product_id']));

					$pro_name = translateFromSerialize($res_item['prodet_name']);
					$pro_desc = translateFromSerialize($res_item['prodet_shortDesc']);



					$order_invoice_print = $functions->ibms_setting('invoice_key_start_with').$order_id.' - '.$firstInv;

					$invoice_det = array(
						'customer_id' => $zoho_contact_id,
						'contact_persons' => array($zoho_contact_person),
						'invoice_number' => $order_invoice_print,
						'date' => $res_invDet['due_date'],
						'line_items' => array(
							array(
								'item_id' => $res_item['zoho_item_no'],
								'name' => $pro_name,
								'description' => $pro_desc,
								'item_order' => 1,
								'rate' => doubleval($res_invDet['price']),
								'quantity' => 1
							)
						)


					);


					$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
					$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

					// Zoho Books Refresh Token with Scope of Full Access ( ZohoBooks.fullaccess.all ).
					$refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 


					$params = array(
						'refresh_token' => $refresh,
						'client_id' => $client_id,
						'client_secret' => $secret,
						'redirect_uri' => WEB_URL.'/orderInvoice.php',
						'grant_type' => 'refresh_token'
					);

					// Using refresh token to generate access token.
					$ch = curl_init(); 
					curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
					curl_setopt($ch, CURLOPT_POST, count($params));
					curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$results = curl_exec($ch);
					curl_close($ch);

					$array = json_decode($results,true);

					$access_token = $array['access_token']; // Access Token


					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/invoices?organization_id=667162566&ignore_auto_number_generation=true");
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
					curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($invoice_det));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$results2 = curl_exec($ch);
					curl_close($ch);

					$array2 = json_decode($results2,true);

					if($array2['code'] == '0'){
						$zoho_inv_id = $array2['invoice']['invoice_id'];

						$sql_upd = "UPDATE `invoices` SET `zoho_inv_id` = ? WHERE `invoice_pk` = ?";
						$dbF->setRow($sql_upd, array($zoho_inv_id,$firstInv), false);
					}

				}


				#############  Zoho Create Invoice Start  ##############

				$_GET['mailId'] = $order_id;
				$msg2 = include(__DIR__.'/orderMail.php');

				$orderIdInvoice =   $functions->ibms_setting('invoice_key_start_with').$order_id;
				$orderIdInvoice =   $dbF->hardWords('Thank you for your purchase. Order ID ',false)." ($orderIdInvoice)";
				$fromName       =   $functions->webName;

				$mailArray['fromName']    =   $fromName;
				$functions->send_mail($email,$orderIdInvoice,$msg2,'',$name,$mailArray);

				$adminMail = $functions->ibms_setting('Email');
				$functions->send_mail($adminMail,$orderIdInvoice,$msg2,'','',$mailArray);

				echo '
				<section class="orderInvoice-bg page-banner">
					<div class="page-heading">
						<h2>Order Invoice</h2>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb text-center">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Order Invoice</li>
							</ol>
						</nav>
					</div>
				</section>
				<section id="content" class="section-container less-space">
					<div class="container">
						<div class="row">
							<div class="section-heading text-left">
								<h3 class="promo-text">Your order has been confirmed, you will receive an email shortly with your login credentials.<br>One of our specialists will contact you shortly to arrange your fragrance consultation.</h3> <br>
							</div>
						</div>
					</div>
				</section>
				';

			}

		break;

		case '-2':

			$sql = "UPDATE `orders` SET 
			`order_status` = ?
			WHERE `order_id` = ?";

			$dbF->setRow($sql, array('cancelled',$order_id), false);

			echo '
			<section class="orderInvoice-bg page-banner">
				<div class="page-heading">
					<h2>Order Invoice</h2>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb text-center">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Order Invoice</li>
						</ol>
					</nav>
				</div>
			</section>
			<section id="content" class="section-container less-space">
				<div class="container">
					<div class="row">
						<div class="section-heading text-left">
							<h3 class="promo-text">Your Order Cancelled!</h3>
						</div>
					</div>
				</div>
			</section>
			';

			break;

		case '-3':

			$sql = "UPDATE `orders` SET 
			`order_status` = ?
			WHERE `order_id` = ?";

			$dbF->setRow($sql, array('cancelled', $order_id), false);

			echo '
			<section class="orderInvoice-bg page-banner">
				<div class="page-heading">
					<h2>Order Invoice</h2>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb text-center">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Order Invoice</li>
						</ol>
					</nav>
				</div>
			</section>
			<section id="content" class="section-container less-space">
				<div class="container">
					<div class="row">
						<div class="section-heading text-left">
							<h3 class="promo-text">Your Order Cancelled!</h3>
						</div>
					</div>
				</div>
			</section>
			';

			break;

		default:
			# code...
			break;
	}

}
else{

}


function createWebUserAccount($orderUser=false,$invoiceId,$status='1',$name,$email,$settingArray=array(), $zohoContact=false){
	//$status = "1"; //pending 0 .. 1 active

	global $functions;
	global $webClass;
	global $dbF;

	$aLink = WEB_URL . "/login.php?";

	$sql = "SELECT * FROM accounts_user WHERE acc_email = '$email'";
	$accData    = $dbF->getRow($sql);
	$already = false;
	if($dbF->rowCount>0){
		$already = true;
		$lastId  =   $accData['acc_id'];
		$zohoContact_id = $accData['zoho_contact_id'];
	}else{

		$today  = date("Y-m-d H:i:s");
		$unique =   uniqid();
		$password  =  $functions->encode($unique);


		##########  ZOHO BOOKS SYNC START  ############

		$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
		$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

		$refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 

		$params = array(
			'refresh_token' => $refresh,
			'client_id' => $client_id,
			'client_secret' => $secret,
			'redirect_uri' => WEB_URL.'/orderInvoice.php',
			'grant_type' => 'refresh_token'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$results = curl_exec($ch);
		curl_close($ch);

		$zoho_return = json_decode($results,true);
		$access_token = $zoho_return['access_token'];

		// Contact fields for testing.
		$contact_array = array(
			"contact_name" => $zohoContact['company'],
			"tax_treatment" => "vat_not_registered",
			"place_of_supply" => $zohoContact['country']
		);

		// Access API to create contact
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/contacts?organization_id=667162566");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
		curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($contact_array));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$results2 = curl_exec($ch);
		curl_close($ch);

		$array2 = json_decode($results2,true);

		$zoho_contact = '';

		if($array2['code'] == '0'){

			$contact_id = $array2['contact']['contact_id'];
			$contactPerson_array = array(

				'contact_id'    => $contact_id,
				'first_name'    => $zohoContact['firstName'],
				'last_name'     => $zohoContact['lastName'],
				'email'         => $email

			);


			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/contacts/contactpersons?organization_id=667162566");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-oauthtoken {$access_token}","Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
			curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($contactPerson_array));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$resultsPerson = curl_exec($ch);
			curl_close($ch);

			$contact_person = json_decode($resultsPerson,true);

			if($contact_person['code'] == '0'){

				$zContact_id = $contact_person['contact_person']['contact_id'];
				$zContact_pid = $contact_person['contact_person']['contact_person_id'];

				$zoho_contact = "zoho_contact_id = '$zContact_id',
				zoho_contact_person = '$zContact_pid',";

			}
		}


		$sql = "INSERT INTO accounts_user SET
		acc_name = ?,
		acc_email = ?,
		acc_pass = ?,
		acc_type = '$status',
		$zoho_contact
		acc_created = '$today'";
		$array = array($name,$email,$password);

		$dbF->setRow($sql,$array,false);
		$lastId = $dbF->rowLastId;
		$acc_name = $accData['acc_name'];
		$sql        =   "INSERT INTO `accounts_user_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";
		$arry       =   array();
		foreach($settingArray as $key=>$val){
			$sql .= "('$lastId',?,?) ,";
			$arry[]= $key ;
			$arry[]= $val ;
		}
		$sql = trim($sql,",");
		$dbF->setRow($sql,$arry,false);
	}

	$sql = "UPDATE  `orders` SET
	order_user = '$lastId'
	WHERE order_id = '$invoiceId'";
	$dbF->setRow($sql,false);

	$ThankWeSend = $dbF->hardWords('Thank you! We have sent verification email. Please check your email.',false);
	if($already){
		$password = $functions->decode($accData['acc_pass']);
		$setPswrdHash = $email.'---'.$password;
		$setPswrdHash = base64_encode($setPswrdHash);
		$mailArray['link']        =   $aLink.'set='.$setPswrdHash;
		// $mailArray['link']        =   $aLink;
		$mailArray['password']     =   $password;
		$fname = explode(" ",$name);


		$functions->send_mail($email,'', '','accountCreateOnOrder',$fname[0],$mailArray);
		return $msg = $ThankWeSend;
	}else{
		// $mailArray['link']        =   $aLink;
		$setPswrdHash = $email.'---'.$unique;
		$setPswrdHash = base64_encode($setPswrdHash);
		$mailArray['link']        =   $aLink.'set='.$setPswrdHash;
		$mailArray['password']    =   $unique;

		$fname = explode(" ",$name);
		$functions->send_mail($email,'', '','accountCreateOnOrder',$fname[0],$mailArray);
		return $msg = $ThankWeSend;
	}


}


?>

<style type="text/css">
.subscribe{
	text-align: center;
}

.subscribe h1{
	border: none;
}

.orderInvoice-bg {
	background: url('<?= $default_banner ?>') no-repeat;
	background-size: cover;
	background-position: center center;
}
</style>

<?php include("footer.php"); ?>