<?php
include("global.php");

global $dbF;
global $db,$functions;
$dbp = $db;

$sql = "SELECT * FROM accounts_user";
$test   =   $dbF->getRows($sql);

foreach ($test as $key => $value) {
	# code...
	$pas = base64_decode($value['acc_pass']);

	$new_pas = $functions->encode($pas);

	$acc_id = $value['acc_id'];

	$ary = array($new_pas, $acc_id);

	$sql = "UPDATE `accounts_user` SET `acc_pass`= ? WHERE `acc_id` = ?";
	$dbF->setRow($sql, $ary);
}
