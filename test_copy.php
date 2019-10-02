<?php 

include_once('global.php');
global $db,$dbF,$functions,$productClass;

// $functions->updateInvoiceFromTelr();
// exit;

// $a = 'Thu, 27 Sep 2018 04:09:26 +0000';
// echo date('Y-m-d', strtotime($a));
// exit;

// $st_id = 20901;
// $next_day = date('dmY',strtotime('+1 day'));
// $params = array(
//         'ivp_method'  => 'create',
//         'ivp_store'   => '20901',
//         'ivp_authkey' => 'vJJrn~6LpK-6FR8f',
//         'ivp_cart'    => '123459',  
//         'ivp_test'    => '1',
//         'ivp_amount'  => '100.00',
//         'ivp_currency'=> 'AED',
//         'ivp_desc'    => 'Product Description',
//         'return_auth' => 'http://projects.imedia.pk/php/iscent/test.php',
//         'return_can'  => 'http://projects.imedia.pk/php/iscent/test.php',
//         'return_decl' => 'http://projects.imedia.pk/php/iscent/test.php',

//         'repeat_amount'=> '10',
//         'repeat_period'=> 'W',
//         'repeat_interval'=> '1',
//         'repeat_start'=> $next_day,
//         'repeat_term'=> '2',
//         'repeat_final'=> '0',
//     );
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
// curl_setopt($ch, CURLOPT_POST, count($params));
// curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
// $results = curl_exec($ch);
// curl_close($ch);
// $results = json_decode($results,true);
// $ref= trim($results['order']['ref']);
// $url= trim($results['order']['url']);
// if (empty($ref) || empty($url)) {
// # Failed to create order

// }else{
//     header('Location: '.$url);
// }

// echo '<pre>'; print_r(json_encode($params)); echo '</pre>';
// echo '<pre>'; print_r($results); echo '</pre>';


##############  GENERATE ACTIVE TOKEN  #####################

$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

if(isset($_GET['code'])){
    $code = $_GET['code'];
    echo '<pre>'; print_r($_REQUEST); echo '</pre>';
    // exit;
    $params = array(
    		'code' => $code,
    		'client_id' => $client_id,
    		'client_secret' => $secret,
    		'redirect_uri' => 'http://projects.imedia.pk/php/iscent/test_copy.php',
    		'grant_type' => 'authorization_code'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
	curl_setopt($ch, CURLOPT_POST, count($params));
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$results = curl_exec($ch);
	curl_close($ch);

	echo '<pre>'; print_r($results); echo '</pre>';

// 	echo '<pre>'; print_r($_REQUEST); echo '</pre>';
    
//     // header('Location: https://accounts.zoho.com/oauth/v2/token?code='.$code.'&client_id='.$client_id.'&client_secret='.$secret.'&redirect_uri=http://projects.imedia.pk/php/iscent/test.php&grant_type=authorization_code');
}


##############  GENERATE ACTIVE TOKEN FROM REFRESH TOKEN #####################

// $refresh = '1000.cc9caee05b0016024c38f92854444652.54b24b85ce4509ededf709f011d55182'; // Only invoices in scope
// $refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; // Invoices & contacts in scope
/*
$client_id = '1000.AGGPITUHTRJX796776SOBEHDYZMA7B';
$secret = '4501c354085ff3bfbf65e112d081eefef0235a1246';

// Zoho Books Refresh Token with Scope of Full Access ( ZohoBooks.fullaccess.all ).
$refresh = '1000.fcd984a2fe5cff258eb683d3303d87c5.eaa78c1e95d3e9558ed50626c0cc252b'; 
// $refresh = '1312374d79ea983d4941cdec4f28b152'; 

$params = array(
	'refresh_token' => $refresh,
	'client_id' => $client_id,
	'client_secret' => $secret,
	'redirect_uri' => 'http://projects.imedia.pk/php/iscent/test.php',
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

// Contact fields for testing.
$contact_array = array(
	"contact_name" => 'Test Contact'
);

// Access API to create contact
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://books.zoho.com/api/v3/contacts?organization_id=667162566");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Zoho-authtoken $access_token"));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
// curl_setopt($ch, CURLOPT_POST, count($contact_array));
curl_setopt($ch, CURLOPT_POSTFIELDS,'JSONString='.json_encode($contact_array));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$results2 = curl_exec($ch);
curl_close($ch);

$array2 = json_decode($results2,true);
echo '<pre>'; print_r($array2); echo '</pre>';
*/

// echo '<pre>'; print_r($_REQUEST); echo '</pre>';

##############  GENERATE ACTIVE TOKEN FROM REFRESH TOKEN END #####################

// $_GET['mailId'] = 8;
// $msg2 = include(__DIR__.'/orderMail.php');

// echo $msg2;


$merchantId = '11526';
$key = '0c9fdfb5M$BbdT42jhkDRM9Z';

$auth = $merchantId.':'.$key;
$auth_enc = base64_encode($auth);


######################### GET ORDER DETAILS USING TRANSACTION REF #########################

// $api_url = 'https://secure.innovatepayments.com/tools/api/xml/transaction/030018223237';

// $ch = curl_init();

// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_enc"));
// curl_setopt($ch, CURLOPT_URL, $api_url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// $result = curl_exec($ch);
// curl_close($ch);

// $xml = simplexml_load_string($result);
// $json = json_encode($xml);
// $array = json_decode($json,TRUE);

// echo "<pre>"; print_r($array); echo "</pre>";

######################### GET ORDER DETAILS USING TRANSACTION REF END #########################


#################  RECENT TRANSACTIONS  ######################

// $api_url = 'https://secure.telr.com/tools/api/xml/transaction';

// $ch = curl_init();

// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_enc"));
// curl_setopt($ch, CURLOPT_URL, $api_url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// $result = curl_exec($ch);
// curl_close($ch);

// $xml = simplexml_load_string($result);
// $json = json_encode($xml);
// $array = json_decode($json,TRUE);

// echo "<pre>"; print_r($array); echo "</pre>";


####################  LINKED TRANSACTIONS  ######################

// $api_url = 'https://secure.telr.com/tools/api/xml/transaction/030018368085';
// // $api_url = 'https://secure.telr.com/tools/api/xml/transaction/40000002/card';
// // $api_url = 'https://secure.telr.com/tools/api/xml/agreement/94688';
// $api_url = 'https://secure.innovatepayments.com/tools/api/xml/agreement/94688/history';

// $ch = curl_init();

// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_enc"));
// curl_setopt($ch, CURLOPT_URL, $api_url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

// $result = curl_exec($ch);
// curl_close($ch);

// $xml = simplexml_load_string($result);
// $json = json_encode($xml);
// $array = json_decode($json,TRUE);

// echo "<pre>"; print_r($array); echo "</pre>";


 ?>