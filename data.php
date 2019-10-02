<?php include_once("global.php");
error_reporting(0);ini_set('display_errors', 0);
global $webClass;
global $_e;
global $productClass;
global $dbF;

//include_once('header.php');



$sql = "SELECT * FROM `product_category`";
$productIds = $dbF->getRows($sql);

foreach ($productIds as $key => $value) {
	$new_categories = '';
	$pro_id = $value['procat_prodet_id'];
	$cat_id = $value['procat_cat_id'];

	$sql1 = "SELECT * FROM `categories` WHERE `id` = $cat_id";
	$rs1 = $dbF->getRow($sql1);

	$under = $rs1['under'];

	if($under != 0){
		$sql2 = "SELECT * FROM `categories` WHERE `id` = $under";
		$rs2 = $dbF->getRow($sql2);

		$under1 = $rs2['under'];

		if($under1 != 0){
		$sql3 = "SELECT * FROM `categories` WHERE `id` = $under1";
		$rs3 = $dbF->getRow($sql3);

		$under2 = $rs3['under'];

		$new_categories .= $under2.',';

		
		}
		$new_categories .= $under1.',';

	}
	$new_categories .= $under.','.$cat_id;

	$sql4 = "UPDATE `product_category` SET `procat_cat_id`='$new_categories' WHERE `procat_prodet_id` = $pro_id";
	$rs4 = $dbF->setRow($sql4);

	if($dbF->rowCount>0) {
		echo $sql4;
	}


//echo $new_categories;
//die;
}


//echo "</tbody></table>";



?>

<script>
$(document).ready(function(){
	count = $('#body_id tr').length;
	console.log(count);
});
</script>

<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}
</style>