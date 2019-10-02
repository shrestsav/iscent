<?php
ob_start();



require_once("classes/inventory.php");
$inventory=new inventory();

global $_e;
global $adminPanelLanguage;
$_w=array();
//index
$_w['Add Product Stock Quanity'] = '' ;
$_w['SAVE'] = '' ;
$_w['Store'] = '' ;
$_w['Success'] = '' ;
$_w['Failed'] = '' ;
$_w['Quick Product Quantity Add Successfully'] = '' ;
$_w['Product Add'] = '' ;
$_w['Your New Product Add Successfully'] = '' ;
$_w['Quick Product Qty'] = '' ;
$_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin SEO');

if(empty($_GET["pid"])){
    header("Location: -product?page=add");
}else{
    $functions->notificationError(_js($_e["Product Add"]),_js($_e["Your New Product Add Successfully"]),"btn-success");
}

//Submit Quick by product
if(isset($_POST["pid"]) && isset($_POST["submit"])  && isset($_POST["qty"]) ){

    try{
        $db->beginTransaction();
        $pid = $_POST["pid"];
            $sql = "DELETE FROM product_inventory WHERE qty_product_id = '$pid'";
            $dbF->setRow($sql);

            $data = $_POST["qty"];
            $sql = "INSERT INTO product_inventory (qty_store_id,qty_product_id,qty_product_scale,qty_product_color,qty_item,location,product_store_hash) VALUE";
            $arry = array();

            foreach($data as $store_id=>$store_val){
                foreach($store_val as $scale_id=>$scale_val) {
                    foreach($scale_val as $color_id=>$color_val) {
                        $scaleId = empty($scale_id) ? 0 :$scale_id;
                        $colorId = empty($color_id) ? 0 :$color_id;
                        $storeID = empty($store_id) ? 0 :$store_id;

                        @$hashVal=$pid.":".$scaleId.":".$colorId.":".$storeID;
                        $hash = md5($hashVal);

                        @$location = $_POST['location'][$store_id][$scale_id][$color_id];

                        $sql .= "(?,?,?,?,?,?,?),";

                        $arry[] = $storeID;
                        $arry[] = $pid;
                        $arry[] = $scaleId;
                        $arry[] = $colorId;
                        $arry[] = $color_val; // QTY
                        $arry[] = $location;
                        $arry[] = $hash;
                    }//Color Loop
                }//Scale Loop
            }//Store loop
            $sql   =   trim($sql,",");
            $dbF->setRow($sql,$arry);

            $db->commit();
            $functions->notificationError(_js(_uc($_e["Success"])),_js($_e["Quick Product Quantity Add Successfully"]),"btn-success");
            $functions->setlog(_uc($_e['Quick Product Qty']),'Stock','',_uc($_e['Quick Product Quantity Add Successfully']));

            $inventory->cleanInventory();

            header("Location: -product?page=add&quickadd=true");
        }catch(Exception $e){
            $db->rollBack();
            $functions->notificationError(_js(_uc($_e["Failed"])),_js($_e["Quick Add Qty"]),"btn-danger");
        }
}


function qty_form($form_fields,$store_id,$scale="s",$scale_id=0,$color="#000",$color_id=0){
    global $_e;
    $color_span = '';
    if($color_id>0){
        $color_span = "<span style='padding:2px;background: #{$color};color:#eee;'>#{$color}</span>";
    }

    $form_fields[] = array(
        'label' => "$scale $color_span",
        'name'  => "qty[$store_id][$scale_id][$color_id]",
        'type'  => 'number',
        'value' => '0',
        'min'   => '0',
        'class' => 'form-control',
        'format' => "<div class='col-sm-6'>{{form}}</div>
                        <div class='col-sm-6'><input type='text' class='form-control' placeholder='Stock Location' name='location[$store_id][$scale_id][$color_id]' /></div>",
    );
    return $form_fields;
}



$pid = $_GET["pid"];


$form_fields = array();

$form_fields[] = array(
    'type'  => 'none',
    'thisFormat' => ' <h5>'. _uc($_e['Add Product Stock Quanity']) .'</h5>',
);



$pName=$inventory->productF->getProductName($pid);
$form_fields[] = array(
    'type'  => 'none',
    'thisFormat' => "<h3 class='borderIfNotabs'>$pName</h3>",
);
$form_fields[] = array(
    'type'  => 'none',
    'thisFormat' => "<input type='hidden' name='pid' value='$pid' />",
);

$storeData=$inventory->productF->storeSQL("`store_name`,`store_location`,`store_pk`");
//var_dump($storeData);

$scaleData = $inventory->productF->scaleSQL($pid,"`prosiz_name`,`prosiz_id`");
//var_dump($scaleData);
//$scaleData = array();

$colorData=$inventory->productF->colorSQL($pid,"`proclr_name`,`propri_id`");
//var_dump($colorData);

foreach($storeData as $store) {
    $store_id = $store['store_pk'];
    $form_fields[] = array(
        'type'  => 'none',
        'thisFormat' => "<h4> ".$_e["Store"]." $store[store_name] $store[store_location] </h4>",
    );

    if (is_array($scaleData) && sizeof($scaleData) > 0){
        //Only size or size with color
        foreach ($scaleData as $scale_val){
            $scale = $scale_val["prosiz_name"];
            $scale_id = $scale_val["prosiz_id"];

            if (is_array($colorData) && sizeof($colorData) > 0) {
                foreach ($colorData as $color_val) {
                    $color = $color_val["proclr_name"];
                    $color_id = $color_val["propri_id"];
                    $form_fields = qty_form($form_fields,$store_id, $scale, $scale_id, $color, $color_id);
                }
            } else {
                $form_fields = qty_form($form_fields, $store_id, $scale, $scale_id);
            }
        }
    } else if (is_array($colorData) && sizeof($colorData) > 0) {
        //Only Color
        foreach ($colorData as $color_val) {
            $color = $color_val["proclr_name"];
            $color_id = $color_val["propri_id"];
            $form_fields = qty_form($form_fields, $store_id, "", 0, $color, $color_id);
        }
    }

}

$form_fields[]  = array(
    "name"  => 'submit',
    'class' => 'btn btn-primary',
    'type'  => 'submit',
    'value' => _u($_e['SAVE']),
);

$form_fields['form']  = array(
    'type'      => 'form',
    'class'     => "form-horizontal",
    'action'   => '',
    'method'   => 'post',
    'format'   => '{{form}}'
);


$format = '<div class="form-group">
                <label class="col-sm-2 col-md-2  control-label">{{label}}</label>
                <div class="col-sm-10  col-md-10">
                    {{form}}
                </div>
            </div>';

$functions->print_form($form_fields,$format);


?>

<?php return ob_get_clean(); ?>