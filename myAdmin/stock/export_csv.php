<?php
############ Export table into CSV ############

require_once("../global.php");

$_d = ","; //$delimiter

####### CSV file Headings, for excel edit mode.
$file_heading = "product_name{$_d}size{$_d}color{$_d}qty_item{$_d}location{$_d}";
$file_heading .= "qty_pk{$_d}qty_store_id{$_d}qty_product_id{$_d}qty_product_scale{$_d}qty_product_color{$_d}product_store_hash";
$file_heading .= "\n";

$output = $file_heading;

####### get data from DB
$sql    = "SELECT *,
              (SELECT prodet_name FROM proudct_detail  WHERE prodet_id = qty_product_id   ) as product,
              (SELECT prosiz_name FROM product_size    WHERE prosiz_id = qty_product_scale) as size,
              (SELECT proclr_name FROM product_color   WHERE propri_id = qty_product_color) as color
           FROM product_inventory"; // ORDER BY qty_pk DESC
$data   = $dbF->getRows($sql);

foreach ($data as $val) {

    $qty_pk         = $val['qty_pk'];
    $qty_store_id   = $val['qty_store_id'];
    $qty_product_id = $val['qty_product_id'];
    $qty_product_scale = $val['qty_product_scale'];
    $qty_product_color = $val['qty_product_color'];
    $qty_item       = $val['qty_item'];
    $location       = $val['location'];
    $product_store_hash = $val['product_store_hash'];
    $product        = $val['product'];
    $product_name   = $functions->unserializeTranslate($val['product']);
    $product_name   = specialChar_to_english_letters($product_name);
    $size           = $val['size'];
    $color          = $val['color'];

    ####### CSV single row...
    $output .= "{$product_name}{$_d}{$size}{$_d}{$color}{$_d}{$qty_item}{$_d}{$location}{$_d}";
    $output .= "{$qty_pk}{$_d}{$qty_store_id}{$_d}{$qty_product_id}{$_d}{$qty_product_scale}{$_d}{$qty_product_color}{$_d}{$product_store_hash}";
    $output .= "\n";
}


####### Download csv File...
$filename = "IBMS_stock_inventory.csv";
header('Content-type: application/csv;charset=UTF-8');
header('Content-Disposition: attachment; filename=' . $filename);

echo $output;
exit;

?>