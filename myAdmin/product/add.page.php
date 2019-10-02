<?php

ob_start();

global $db, $dbF, $functions;

$product = new product();



$product->editProductInformation();

$isEdit = false;

if ($product->editPid != "") {

$isEdit = true;

}



//if(!empty($_POST))$dbF->prnt($_POST);

//exit;

if (!isset($_POST[$product->prefix_editPro]) || isset($_POST['edit_proSubmit'])) {

$product->addProductInformation(); //add or edit



if (!isset($_POST['edit_proSubmit'])) {

$product->firstInsert();

}

}



// if product edit

if ($isEdit) {

//product setting Data

$eData = $product->productSettingEdit();

}





/*

* Create Json Country List

* with price code data format

* for custom slots

*/

$product->currencyListJson("cdata");





//Words

/**

* MultiLanguage keys Use where echo;

* define this class words and where this class will call

* and define words of file where this class will called

**/

global $_e;

global $adminPanelLanguage;

$_w = array();

$_w['Size Name'] = '';

$_w['Weight In KG'] = '';

$_w['Edit Product'] = '';

$_w['Add New Product'] = '';

$_w['Basic Information'] = '';

$_w['Category'] = '';

$_w['Price'] = '';

$_w['Sizes'] = '';

$_w['Colors'] = '';

$_w['Images'] = '';

$_w['Setting'] = '';

$_w['Product Basic Information'] = '';

$_w['Product Name'] = '';

$_w['Name'] = '';

$_w['Detail Description'] = '';

$_w['SIZES & MEASUREMENTS'] = '';

$_w['DELIVERY & RETURN'] = '';

$_w['Product Price'] = '';

$_w['Add Slot'] = '';

$_w['Product Category'] = '';

$_w['Product Sizes'] = '';

$_w['Product Sizes Weight'] = '';

$_w['Enter Exact name that use in product Size name, IF Size Name not match Product Weight will not work in shipping and It will use product default weight'] = '';

$_w['Product Color'] = '';

$_w['Product Images'] = '';

$_w[""] = '';

$_w['Drop images here to upload.'] = '';

$_w['they will only be visible to you'] = '';

$_w['Public Access'] = '';

$_w['Product Launch Date'] = '';

$_w['Launch Date : leave blank if you want to Publish Now'] = '';

$_w['Default Weight In KG'] = '';

$_w['Minimum Quantity Allow'] = '';

$_w['Use Config Setting'] = '';

$_w['Maximum Quantity Allow'] = '';

$_w['Product Discount'] = '';

$_w['Manage Discount'] = '';

$_w['SUBMIT'] = '';

$_w['Image Preview'] = '';

$_w['Close'] = '';

$_w['Image Not Delete Please Try Again'] = '';

$_w['There is an error, Please Refresh Page and Try Again'] = '';

$_w['Done'] = '';

$_w['Fail'] = '';

$_w['Model'] = '';

$_w['Model No'] = '';

$_w['Label'] = '';

$_w['Publish'] = '';

$_w['Draft'] = '';

$_w['Short Description'] = '';

$_w['Feature Icons'] = '';

$_w['Specification'] = '';

$_w['Size Chart'] = '';





$_w['Buy 2 get 1 Free'] = '';

$_w['Buy QTY Limit'] = '';



$_w['Review'] = '';

$_w['Facebook Comment'] = '';

$_w['Review Off Msg'] = '';

$_w['Ask Questions'] = '';

$_w['Question Off Msg'] = '';



$_w['Fb Comment Off Msg'] = '';

$_w['Video Link'] = '';

$_w['Product Main Image Size : 450x300, Detail Image Size:450x300'] = '';



$_w['Custom Size Type'] = '';

$_w['Custom Size Price +'] = '';

$_w['Feature Points'] = '';

$_w['Related Products'] = '';

$_w['Slug'] = '';

$_w['Quick Product Quantity Add Successfully'] = '';

$_w['Stock'] = '';

$_w['Selected Products'] = '';

$_w['Get A Look'] = '';

$_w['Get Look Products'] = '';

$_w['Get This Feature Look Products'] = '';

$_w['Validity(In Months)'] = '';

$_w['Actual Validity(In Months)'] = '';

$_w['Cancellation Charges'] = '';

$_w['Validity'] = '';

$_w['Payment Mode'] = '';

$_w[''] = '';

$_w[''] = '';

$_w[''] = '';

$_w[''] = '';

$_w[''] = '';

$_w[''] = '';

$_w[''] = '';


$_e = $dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Product Add');





if (isset($_GET["quickadd"]) && !$isEdit) {

$functions->notificationError(_js($_e["Stock"]), _js($_e["Quick Product Quantity Add Successfully"]), "btn-success");

}



$format = '<div class="form-group">

<label class="col-sm-2 control-label">{{label}}</label>

<div class="col-sm-10">

{{form}}

</div>

</div>';

?>

<script type="text/javascript">

var curTable = (function (json_data) {

$self = this;

/***************/

this.checkbox_name = "_undefined_";

this.input_name = "_undefined_";

this.input_name__extra_class = "";

this.input_addprice_name = "_undefined_";

this.slotType = "";

this.panel_note_footer = "";

this.panel_heading = "Customize Slots";

this.always_callback = function () {

fireSortable();

};

this.addSlot_callback = function () {

};

/***************/

this.uniqid = functions.uniqid();

this.json = (typeof (json_data) == "undefined" ) ? new Object() : json_data;

this.objNumber = 0;

this.initializeCheck = false;

this.divid = "body";

this.tbody = $("<tbody></tbody>").attr("id", "tbody_" + this.uniqid);

this.construct = function () {

this.objNumber = Number(this.json.length) || 0;

};

this.construct();



this.initializeTable = function (id) {

if (this.initializeCheck == false) {

this.initializeCheck = true;

id = (document.getElementById(id)) ? "#" + id : this.divid;

$table = $("<table></table>").attr({

id: "table_" + this.uniqid,

class: "table table-condensed table-responsive"

});

$thead = $("<thead></thead>");

$tr = $("<tr></tr>");

$tr.append("<th>Name</th>");

var data = this.json;

for (x in data) {

$tr.append("<th>" + data[x].country + " (" + data[x].name + ") " + "</th>");

}

$thead.append($tr);

$table.append($thead);

$tbody = this.tbody;

$table.append($tbody);

var panel_heading = this.panel_heading;

var slef_panel_note_footer = this.panel_note_footer;

$(function () {

$panel = $("<div class='panel panel-primary'></div>");

$panel.append("<div class='panel-heading'>" + "<h3 class='panel-title'>" + panel_heading + "</h3>" +

"</div>");

$panel_body = $("<div class='panel-body table-responsive'></div>");

$panel_body.append($table);

$panel_body.append("<div class='panel_note_footer'>" + slef_panel_note_footer + "</div>");

$panel.append($panel_body);

$(id).append("<br><hr>");

$(id).append($panel);

});

}

};



this.xhtmText = function (rowID, trCount) {

return "<div class='input-group input-group-sm slotDiv_" + trCount + "'>" + "<span class='input-group-addon'>" +

"<input class='slotCheckBox_" + trCount + "'  type='checkbox' value='" + rowID + "' name='" + this.checkbox_name + "[]'>" + "</span>" +

"<input type='text' value='' name='" + rowID + "-" + this.input_name + "' class='form-control slotInput_" + trCount + " " + this.input_name__extra_class + "'>" + "</div>";

};





var trCount = 0;

this.addSlot = function () {

if (this.initializeCheck == false) {

this.initializeTable(this.divid);

}

trCount++;

var rowID = functions.uniqid("rowid");

$tr = $("<tr></tr>");

$td = $("<td></td>");

$xht = this.xhtmText(rowID, trCount);

$td.append($xht);

$tr.append($td);

var data = this.json;

for (x in data) {

$td = $("<td></td>");

$xht = "<div class='input-group input-group-sm'>" +

"<span class='input-group-addon'>" + data[x].symbol +

"</span>" +

"<input type='text' class='form-control' name='" + rowID + "-" + this.input_addprice_name + "[" + data[x].id + "]' >" +

"</div>";

$td.append($xht);

$tr.append($td);

}

this.tbody.append($tr);

this.addSlot_callback();

this.always_callback();

}

});



var mscale = new curTable(cdata);

mscale.divid = "tab_sizes_div";

mscale.checkbox_name = "<?php echo $product->prefix_scaleCheckBox; ?>";

mscale.input_name = "<?php echo $product->prefix_scaleName; ?>";

mscale.input_addprice_name = "<?php echo $product->prefix_scaleCost; ?>";





var addCharges = new curTable(cdata);

addCharges.panel_heading = "Additional Charges";

addCharges.divid = "addCharges_div";

addCharges.checkbox_name = "<?php echo $product->prefix_addCostCheckBox; ?>";

addCharges.input_name = "<?php echo $product->prefix_addCostName; ?>";

addCharges.input_addprice_name = "<?php echo $product->prefix_addCostCost; ?>";





var mcolors = new curTable(cdata);

mcolors.divid = "tab_color_div";

mcolors.checkbox_name = "<?php echo $product->prefix_colorCheckBox; ?>";

mcolors.input_name = "<?php echo $product->prefix_colorName; ?>";

mcolors.input_addprice_name = "<?php echo $product->prefix_colorCost; ?>";

/**/

mcolors.input_name__extra_class = "color_picker";

mcolors.slotType = "color_picker_input";

mcolors.addSlot_callback = function () {

color_picker();

};





$(function () {

fireSortable();

});



var fixHelperModified = function (e, tr) {

var $originals = tr.children();

var $helper = tr.clone();

$helper.children().each(function (index) {

$(this).width($originals.eq(index).width())

});

return $helper;

}, updateIndex = function (e, ui) {

$('td.index', ui.item.parent()).each(function (i) {

$(this).html(i + 1);

});

};

function fireSortable() {

$(".tableSort tbody").sortable({

helper: fixHelperModified, axis: 'y', placeholder: "ui-state-highlight",

stop: updateIndex

}).disableSelection();

}



var uniqueId = 1;

function addWeightSlot() {

uniqueId = uniqueId + 1;

var weightVar = '<tr>' +

'<td>' +

'<div class="input-group input-group-sm">' +

'<span class="input-group-addon"><?php echo _js($_e['Size Name']); ?></span>' +

'<input type="text" class="form-control" value="" name="sizeWeightName[' + uniqueId + ']" placeholder="<?php echo _js($_e['Size Name']); ?>">' +

'</div>' +

'</td>' +

'<td>' +

'<div class="input-group input-group-sm">' +

'<span class="input-group-addon"><?php echo _js($_e['Weight In KG']); ?></span>' +

'<input type="text" class="form-control" value="" name="sizeWeight[' + uniqueId + ']" placeholder="<?php echo _js($_e['Weight In KG']); ?>,e.g: 200,2,0.2">' +

'</div>' +

'</td>' +

'</tr>';



$('#sizeWeightDiv').append(weightVar);

}



</script>

</script>





<style> .ui-state-highlight {

background: #FAEBCC;

height: 1.5em;

line-height: 1.2em;

} </style>



<?php

if ($isEdit) {

echo '<h4 class="sub_heading">' . _uc($_e['Edit Product']) . '</h4>';

} else {

echo '<h4 class="sub_heading">' . _uc($_e['Add New Product']) . '</h4>';

}

?>



<form method="post" class="form-horizontal">



<?php

$functions->setFormToken('edit_pro');



//using this hidden page not submit and again go to edit position, i was try to do if page submit then again submited page open

if ($isEdit) { ?>

<input type="hidden" name="edit_pro" value="<?php echo $product->pid; ?>"/>

<input type="hidden" name="edit_proSubmit" value="<?php echo $product->pid; ?>"/>

<?php } ?>





<div class="container-fluid">

<div class="row">

<div class="tabbable">

<ul class="nav nav-tabs tabs_arrow">

<li class="active"><a href="#tab_bi"

data-toggle="tab"><?php echo _uc($_e['Basic Information']); ?></a></li>

<li><a href="#tab_categroy" data-toggle="tab"><?php echo _uc($_e['Category']); ?></a></li>

<li><a href="#tab_price" data-toggle="tab"><?php echo _uc($_e['Price']); ?></a></li>



<?php if ($functions->developer_setting('product_Scale') == '1') { ?>

<li><a href="#tab_sizes" data-toggle="tab"><?php echo _uc($_e['Sizes']); ?></a></li>

<?php } ?>

<?php if ($functions->developer_setting('product_color') == '1') { ?>

<li><a href="#tab_colors" data-toggle="tab"><?php echo _uc($_e['Colors']); ?></a></li>

<?php } ?>

<?php if ($functions->developer_setting("product_related_item")) { ?>

<li><a href="#tab_realtedProduct"

data-toggle="tab"><?php echo _uc($_e['Related Products']); ?></a></li>

<?php } ?>

<!-- <li><a href="#tab_getlookProduct"

data-toggle="tab"><?php //echo _uc($_e['Get This Feature Look Products']); ?></a></li> -->





<li><a href="#tab_images" style="display: none;" data-toggle="tab"><?php echo _uc($_e['Images']); ?></a></li>

<li><a href="#tab_setting" data-toggle="tab"><?php echo _uc($_e['Setting']); ?></a></li>

</ul>

<div class="tab-content" style="padding-top: 20px">

<div class="tab-pane fade in active container-fluid" id="tab_bi">

<h2 class="tab_heading"><?php echo _uc($_e['Product Basic Information']); ?></h2>

<?php

/*Add language

*  $lang=array("English","Arabic","French","British");

$lang=serialize($lang);

$sql="UPDATE `ibms_setting` set `setting_val`='$lang' WHERE id='4'";

$dbF->setRow($sql);

*/



/**

* @param $dbF

* @return mixed

*/





$lang = $functions->IbmsLanguages();

if ($lang != false) {

$lang_nonArray = implode(',', $lang);



echo <<<HTML

<input type="hidden" name="lang" value="$lang_nonArray" />

<div class="panel-group" id="accordion">

HTML;



if ($product->editPid != "") {

$editId = $product->editPid;

echo "<input type='hidden' name='editProduct' value='$editId' />";



$qry = "SELECT * FROM `proudct_detail` WHERE `prodet_id` = '$product->editPid'";

$editData = $dbF->getRow($qry);



$editPName = unserialize($editData['prodet_name']);

$editPShortDesc = unserialize($editData['prodet_shortDesc']);

$editValidity = $editData['validity'];

$editPaymentMode = $editData['payment_mode'];

$editPTag = unserialize($product->productSettingArray('tags', $eData));

$editPLongDesc = unserialize($product->productSettingArray('ldesc', $eData));

$editSizeChart = unserialize($product->productSettingArray('size_chart', $eData));

$editFeatureIcon = unserialize($product->productSettingArray('featureIcon', $eData));

$editFeaturePoints = unserialize($product->productSettingArray('featurePoints', $eData));

$editSpecification = unserialize($product->productSettingArray('specification', $eData));



$isEdit = true;

}



for ($i = 0; $i < sizeof($lang); $i++) {

if ($i == 0) {

$collapseIn = ' in ';

} else {

$collapseIn = '';

}

$lang[$i];

if ($isEdit) {

@$eName = $editPName[$lang[$i]];

@$eTag = $editPTag[$lang[$i]];

@$eShortDesc = $editPShortDesc[$lang[$i]];

@$eLongDesc = $editPLongDesc[$lang[$i]];

@$size_chart = $editSizeChart[$lang[$i]];

@$eEditFeature = $editFeatureIcon[$lang[$i]];

@$eEditFeatureP = $editFeaturePoints[$lang[$i]];

@$specification = $editSpecification[$lang[$i]];

@$validity = $editValidity;

@$payment_mode = $editPaymentMode;

} else {

$eName = $eTag = $eShortDesc = $eLongDesc = $eEditFeature = $eEditFeatureP = $specification = $size_chart = "";

}











echo '<div class="panel panel-default">

<div class="panel-heading">

<a data-toggle="collapse" data-parent="#accordion" href="#' . $lang[$i] . '">

<h4 class="panel-title">

' . $lang[$i] . '

</h4>

</a>

</div>

<div id="' . $lang[$i] . '" class="panel-collapse collapse ' . $collapseIn . '">

<div class="panel-body">';

$form_fields = array();

$form_fields[] = array(

'label' => _uc($_e['Name']),

'name' => "$product->prefix_productBasicInformation[name][$lang[$i]]",

'placeholder' => _uc($_e['Product Name']),

'value' => "$eName",

'type' => 'text',

'class' => 'form-control',

);



$form_fields[] = array(

'label' => _uc($_e['Short Description']),

'name' => "$product->prefix_productBasicInformation[sdesc][$lang[$i]]",

'value' => "$eShortDesc",

'type' => 'text',

'class' => 'form-control ckeditor',

);



$form_fields[] = array(

'label' => _uc($_e['Detail Description']),

'name' => "$product->prefix_setting[ldesc][$lang[$i]]",

'value' => "$eLongDesc",

'type' => 'textarea',

'class' => 'form-control ckeditor'

);





// $form_fields[] = array(

//     'label' => _uc($_e['Size Chart']),

//     'name' => "$product->prefix_setting[size_chart][$lang[$i]]",

//     'value' => "$size_chart",

//     'type' => 'textarea',

//     'class' => 'form-control ckeditor'

// );





// $form_fields[] = array(

//     'label' => _uc($_e['DELIVERY & RETURN']),

//     'name' => "$product->prefix_setting[tags][$lang[$i]]",

//     'value' => "$eTag",

//     'type' => 'textarea',

//     'class' => 'form-control ckeditor'

// );



// $form_fields[] = array(

//     'label' => _uc($_e['Specification']),

//     'name' => "$product->prefix_setting[specification][$lang[$i]]",

//     'value' => "$specification",

//     'type' => 'textarea',

//     'class' => 'form-control ckeditor'

// );



// $form_fields[] = array(

//     'label' => _uc($_e['Feature Icons']),

//     'name' => "$product->prefix_setting[featureIcon][$lang[$i]]",

//     'value' => "$eEditFeature",

//     'type' => 'textarea',

//     'class' => 'form-control ckeditor',

//     'id' => 'FeatureId_' . $i,

// );



// $form_fields[] = array(

//     'label' => _uc($_e['Feature Points']),

//     'name' => "$product->prefix_setting[featurePoints][$lang[$i]]",

//     'value' => "$eEditFeatureP",

//     'type' => 'textarea',

//     'class' => 'form-control ckeditor'

// );



$functions->print_form($form_fields, $format);

echo '



</div> <!-- panel-body-->

</div> <!-- #$lang[$i] -->



</div> <!-- .panel-default -->';



//                                     echo "<script>

//                     $(document).ready(function(){

//                         CKEDITOR.replace( 'FeatureId_$i', {

//                             toolbar: [

//                                 { name: 'document', items: [ 'Source','featureIcon'] },

//                                 [ 'Cut', 'Copy', 'Paste','-', 'Undo', 'Redo' ],

//                             ]

//                         });

//                     });

// </script>";



}

echo '</div> <!-- .panel-group -->';

}

?>



</div>





<div class="tab-pane container-fluid fade" id="tab_price">

<h2 class="tab_heading"><?php echo _uc($_e['Product Price']); ?></h2>



<div>   <?php $product->createPricingViewSystem(); ?>

<div id="addCharges_div" class="">

<?php

$product->priceAdditionChargesEdit();

if ($functions->developer_setting('check_out_offer') == '1' && $functions->ibms_setting('check_out_offer') == '1') {

$product->priceCheckOutChargesEdit();

}

?>

</div>

<button type="button" class="btn btn-info pull-right" onclick="addCharges.addSlot()">

<?php echo _uc($_e['Add Slot']); ?>

</button>

</div>

</div>





<div class="tab-pane container-fluid fade" id="tab_categroy">

<h2 class="tab_heading" id="pro_cat"><?php echo _uc($_e['Product Category']); ?></h2>

<ul id="nestedlist">

<?php



##### Main MENU

$css = false;

$view_css= '';

$mainMenu = $product->menuTypeSingle('main');

foreach ($mainMenu as $val) {

$insideActive = false;

$innerUl = '';

$menuId = $val['id'];

$text = _n($val['name']);

$link = $val['link'];



// $underid = $val['under'];

$has_inner_level_two_class = '';

$inner_level_two = null;

$mainMenu2 = $product->menuTypeSingle('main', $menuId);

if (!empty($mainMenu2)) {

$has_inner_level_two_class = 'has-sub';

$inner_level_two = true;



$innerUl .= '



<ul>

';

foreach ($mainMenu2 as $val2) {

$innerUl3 = '';

$text = _n($val2['name']);

$menuId2 = $val2['id'];

$link = $val2['link'];

$menuIcon = '';

$active = $val2['active'];





// $underid = $val2['under'];



if ($active == '1') {

$active = 'active';

$insideActive = $css = true;

}



$has_inner_level_three_class = '';



$mainMenu3 = $product->menuTypeSingle('main', $menuId2);

# count the inner level 3 lis

$innerUl3count = ( $mainMenu3 == false ? 0 : count($mainMenu3) ) ;

$innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';



if ( $innerUl3count > 0) {



foreach ($mainMenu3 as $val3) {

$view_css3 = '';

$text3       = _n($val3['name']);

$menuId3     = $val3['id'];

$link3       = $val3['link'];

$menuIcon3   = $val3['icon'];

$active3     = $val3['active'];

if ($active3 == '1') {

$active3 = 'active';

$insideActiveThree = true;

}





$has_inner_level_three_class = 'has-sub';



$innerUl3 .= '



<li><input type="checkbox" name="cats[]" value='.$menuId3.'>

'. $text3 . '



</li>





';



}



}



$innerUl3 .= ( $innerUl3count > 0 ) ? '</ul><!--3rd array End-->' : '';



// $innerUl3 .= "</ul><!--3rd array End-->";

if ($innerUl3) {



// var_dump($menuId);



$image_div = '';



} else {

$image_div = '';

}



$innerUl .= '



<li><input type="checkbox" name="cats[]" value='.$menuId2.'>



' . $text . '



<span>



'.$image_div.'



</span>' . $innerUl3 . '



</li>

';

}



$innerUl .= "</ul><!--2nd array End-->";

}



$text = _n($val['name']);



$link = $val['link'];

$menuIcon = $val['icon'];

if (!empty($menuIcon)) {

$image_div = '<img src="' . $menuIcon . '" alt="">';

} else {

$image_div = '';

}

$active = $val['active'];



if ($active == '1' || $insideActive) {



if (!empty($mainMenu2)) {

$css = true;

}

$active = 'active';

}

echo '

<li><input type="checkbox" name="cats[]" value='.$menuId.'>



' . $text . '







' . $innerUl . '



</li>

';

}



echo '';





?>



</ul>



</div>





<div class="tab-pane container-fluid fade" id="tab_sizes">

<h2 class="tab_heading"><?php echo _uc($_e['Product Sizes']); ?> </h2>



<div class=""><?php $product->createListOfScales(); ?></div>



<div id="tab_sizes_div" class=""></div>

<button type="button" class="btn btn-info pull-right"

onclick="mscale.addSlot()"><?php echo _uc($_e['Add Slot']); ?></button>

<div class="clearfix"></div>

<br>



<div class="margin-10 bg-info">&nbsp;</div>

<br>



<div class=""><?php $product->createListOfCustomSize(); ?></div>



<div class="clearfix"></div>

<br>



<div class="margin-10 bg-info">&nbsp;</div>

<br>



<h2 class="tab_heading"><?php echo _uc($_e['Product Sizes Weight']); ?></h2>

<small><?php echo _n($_e['Enter Exact name that use in product Size name, IF Size Name not match Product Weight will not work in shipping and It will use product default weight']); ?>  </small>

<div class=""><?php $product->createListOfScalesWeight(); ?></div>

</div>





<div class="tab-pane container-fluid fade" id="tab_colors">

<h2 class="tab_heading"><?php echo _uc($_e['Product Color']); ?></h2>



<div class="">

<?php $product->createListOfColor(); ?>

</div>

<div id="tab_color_div" class=""></div>

<button type="button" class="btn btn-info pull-right" onclick="mcolors.addSlot()">

<?php echo _uc($_e['Add Slot']); ?>

</button>

</div>





<?php

if ($functions->developer_setting("product_related_item") == "1" ) {

$relatedData    = $product->productF->productActiveSql('prodet_id,prodet_name', "prodet_id != '" . $product->pid . "'");

/*  $product_array  = array("" => "----");*/

foreach ($relatedData as $val) {

$name = $functions->unserializeTranslate($val['prodet_name']);

$product_array[$val['prodet_id']] = $name;

}

?>



<!--Related Product-->

<div class="tab-pane container-fluid fade" id="tab_realtedProduct">

<!--<h2 class="tab_heading"><?php /*echo _uc($_e['Related Products']); */?></h2>

--><?php

/*                             $form_fields    = array();



@$model         = unserialize($product->productSettingArray('related', $eData));

$form_fields[]  = array(

'label' => _uc($_e['Products']),

'name'  => $product->prefix_setting . '[related][]',

'array' => $product_array,

'type'  => 'select',

'select' => $model,

'multi' => 'true',

'class' => 'form-control',

"data"  => "style='height:300px'"

);

$functions->print_form($form_fields, $format);

*/?>









<!--Related Products-->



<h2 class="tab_heading"><?php echo _uc($_e['Related Products']); ?></h2>

<?php

$form_fields    = array();

@$model         = unserialize($product->productSettingArray('related', $eData));

$form_fields[]  = array(

'id' => 'example-getting-started1',

'label' => _uc($_e['Products']),

'name'  => $product->prefix_setting . '[related][]',

'array' => $product_array,

'type'  => 'select',

'multiple' => 'multiple',

'select' => $model,

'multi' => 'true',

'class' => 'form-control',

'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>',

"data"  => "style='height:300px'"



);

$functions->print_form($form_fields, $format);
}





if ($functions->developer_setting("add_free_gift_in_cart") == "1") {
?>



<h2 class="tab_heading"><?php echo _uc($_e['Free Gift Product']); ?></h2>

<small class=""><strong><?php echo _uc($_e['When this product exist in cart then which free gift add in cart, please select']); ?></strong></small><br><br>

<?php

$form_fields    = array();

@$model         = unserialize($product->productSettingArray('free_gift', $eData));

$form_fields[]  = array(

'id' => 'example-getting-started2',

'label' => _uc($_e['Products']),

'name'  => $product->prefix_setting . '[free_gift][]',

'array' => $product_array,

'type'  => 'select',

'multiple' => 'multiple',

'select' => $model,

'multi' => 'false',

'class' => 'form-control',

//"data"  => "style='height:300px'"

);

$functions->print_form($form_fields, $format);

?>

</div>

<?php } ?>



<!--Product Images-->



<!--GET LOOK PRODUCTS-->





<div class="tab-pane container-fluid fade" id="tab_getlookProduct">

<h2 class="tab_heading"><?php echo _uc($_e['Get This Feature Look Products']); ?></h2>

<?php

$form_fields    = array();

@$model         = unserialize($product->productSettingArray('getlook', $eData));

$form_fields[]  = array(

'id' => 'example-getting-started',

'label' => _uc($_e['Products']),

'name'  => $product->prefix_setting . '[getlook][]',

'array' => $product_array,

'type'  => 'select',

'multiple' => 'multiple',

'select' => $model,

'multi' => 'true',

'class' => 'form-control col-md-12',

'format' => '<label class="checkbox-inline">{{form}} {{option}}

</label>',



);

$functions->print_form($form_fields, $format);

?>



</div>





<!--GET LOOK PRODUCT END-->



<div class="tab-pane container-fluid fade" id="tab_images">

<h2 class="tab_heading"><?php echo _uc($_e['Product Images']); ?></h2>

<small><?php echo _n($_e[""]); ?>

<br> <?php echo _n($_e["Product Main Image Size : 450x300, Detail Image Size:450x300"]); ?>

</small>



<input type="hidden" id="AjaxFileNewId" name="ProductNewId"

value="<?php echo $product->pid; ?>">

<input type="hidden" id="AjaxFileNewPage" value="product">



<div id="dropbox">



<?php

// if product edit

if ($isEdit) {

$product->productEditImages();

}

?>

<style>

#dropbox .preview {

height: 255px !important;

padding: 4px;

background: #eee;

}



#dropbox .progressHolder.album {

height: 80px !important;

padding: 5px;

}

</style>

</div>

<span class="message">

<?php echo _uc($_e['Drop images here to upload.']);?>

<br/>

<i>

(<?php echo _fc($_e['they will only be visible to you']); ?>)

</i>

</span>

</div>



<!--Product Settings-->

<div class="tab-pane container-fluid fade" id="tab_setting">

<h2 class="tab_heading">Product Setting</h2>

<?php

$form_fields = array();



// Form Publish or Draft

$val = '0';

if ($isEdit) {

if ($product->productSettingArray('publicAccess', $eData)) {

$val = '1';

}

}

$valFormTemp = $val;

$form_fields[] = array(

"label" => $_e['Public Access'],

'type' => 'checkbox',

'value' => "$valFormTemp",

'select' => "1",

'format' => '<div class="make-switch" data-off="warning" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">

{{form}}

<input type="hidden" name="' . $product->prefix_setting . '[publicAccess]" class="checkboxHidden" value="' . $val . '" />

</div>'

);



//Product Slug

$slug = $product->pid;

if ($isEdit) {

$slug = $editData['slug'];

}

$form_fields[] = array(

'label' => _uc($_e['Slug']),

'name' => $product->prefix_productBasicInformation . '[slug]',

'placeholder' => _n($_e['Slug']),

'value' => "$slug",

'type' => 'text',

'pattern' => '[A-Za-z0-9-_+]{1,150}',

'class' => 'form-control',

);



//Product Model No

// $model = "";

// if ($isEdit) {

//     $model = $product->productSettingArray('Model', $eData);

// }

// $form_fields[] = array(

//     'label' => _uc($_e['Model']),

//     'name' => $product->prefix_setting . '[Model]',

//     'placeholder' => _n($_e['Model No']),

//     'value' => "$model",

//     'type' => 'text',

//     'class' => 'form-control',

// );



//Product Validity

// $valid = "";

// if ($isEdit) {

//     $valid = $product->productSettingArray('expire', $eData);

// }

if($isEdit){
	@$validity = $editValidity;
	@$payment_mode = $editPaymentMode;

	$selectQuarter = '';
	$selectMonthly = '';

	if($payment_mode == 'quarterly'){
		$selectQuarter = 'selected';
	}else if($payment_mode == 'monthly'){
		$selectMonthly = 'selected';
	}
}else{
	$validity = '';
}

$form_fields[] = array(

    'label' => _uc($_e['Validity(In Months)']),

    'name' => $product->prefix_productBasicInformation . '[expire]',

    'placeholder' => _n($_e['Validity(In Months)']),

    'value' => "$validity",

    'type' => 'text',

    'class' => 'form-control vldy',

);

$actual_expire = "";

if ($isEdit) {

$actual_expire = $product->productSettingArray('actual_expire', $eData);

}

$form_fields[] = array(

    'label' => _uc($_e['Actual Validity(In Months)']),

    'name' => $product->prefix_setting . '[actual_expire]',

    'placeholder' => _n($_e['Actual Validity(In Months)']),

    'value' => "$actual_expire",

    'type' => 'text',

    'class' => 'form-control avldy',

);

$cancel_charges = "";

if ($isEdit) {

$cancel_charges = $product->productSettingArray('cancel_charges', $eData);

}

$form_fields[] = array(

    'label' => _uc($_e['Cancellation Charges']),

    'name' => $product->prefix_setting . '[cancel_charges]',

    'placeholder' => _n($_e['Cancellation Charges']),

    'value' => "$cancel_charges",

    'type' => 'text',

    'class' => 'form-control',

);

$modeOptions = '<option selected disabled>Select Payment Mode</option>
				<option value="quarterly" '.@$selectQuarter.'>Quarterly</option>
				<option value="monthly" '.@$selectMonthly.'>Monthly</option>';

$form_fields[] = array(

    'label' => _uc($_e['Payment Mode']),

    'name' => $product->prefix_productBasicInformation . '[bill_mode]',

    'format' => '<select name="'.$product->prefix_productBasicInformation.'[bill_mode]" class="form-control">
                            '.$modeOptions.'
                        </select>'

);








//Product Label

// $label = "";

// if ($isEdit) {

//     $label = $product->productSettingArray('label', $eData);

// }

// $form_fields[] = array(

//     'label' => _uc($_e['Label']),

//     'name' => $product->prefix_setting . '[label]',

//     'placeholder' => _n($_e['Label']),

//     'value' => "$label",

//     'type' => 'text',

//     'class' => 'form-control',

// );



//Product Video Link

// $label = "";

// if ($isEdit) {

//     $label = $product->productSettingArray('video', $eData);

// }

// $form_fields[] = array(

//     'label' => _uc($_e['Video Link']),

//     'name' => $product->prefix_setting . '[video]',

//     'placeholder' => _n($_e['Video Link']),

//     'value' => "$label",

//     'type' => 'url',

//     'class' => 'form-control',

// );





//Product Launch Date

$label = "";

if ($isEdit) {

$label = $product->productSettingArray('launchDate', $eData);

}

$form_fields[] = array(

'label' => _uc($_e['Product Launch Date']),

'name' => $product->prefix_setting . '[launchDate]',

'placeholder' => _n($_e['Launch Date : leave blank if you want to Publish Now']),

'value' => "$label",

'type' => 'text',

'class' => 'form-control datepicker',

);





//Product Default Weight

// $label = "";

// if ($isEdit) {

//     $label = $product->productSettingArray('defaultWeight', $eData);

// }

// $form_fields[] = array(

//     'label' => _uc($_e['Default Weight In KG']),

//     'name' => $product->prefix_setting . '[defaultWeight]',

//     'placeholder' => _uc($_e['Default Weight In KG']),

//     'value' => "$label",

//     'type' => 'text',

//     'class' => 'form-control convertNumber',

// );



// if ($isEdit) {

// $linkDiscount = '<a href="-product?page=pDiscountForm&pId=' . $product->editPid . '" class="btn btn-success" target="_blank">' . _uc($_e['Manage Discount']) . '</a>';

// $form_fields[] = array(

// 'label' => _uc($_e['Product Discount']),

// 'format' => $linkDiscount

// );

// }





if ($functions->developer_setting('shipping_class') == '1') {

$sql = "SELECT * FROM shipping_class WHERE publish  = '1' ORDER BY name";

$data = $dbF->getRows($sql);

$classesName = array("" => "---");

foreach ($data as $val) {

$classesName[$val['id']] = $val['name'];

}



$val = '0';

if ($isEdit) {

$val = $product->productSettingArray('shippingClass', $eData);

}

$valFormTemp = $val;

$form_fields[] = array(

"label" => $_e["Shipping Class"],

'type' => 'select',

'required' => 'true',

'name' => $product->prefix_setting . '[shippingClass]',

'array' => $classesName,

'select' => "$valFormTemp",

'class' => 'form-control',

);

}



// Form 2 for 3 Product allow on this product or not

if ($functions->developer_setting("buy_2_get_1_free") == "1" && true === false) {

$val = '0';

if ($isEdit) {

if ($product->productSettingArray('buy_2_get_1_free', $eData)) {

$val = '1';

}

}

$valFormTemp = $val;

$form_fields[] = array(

"label" => $_e['Buy 2 get 1 Free'],

'type' => 'checkbox',

'value' => "$valFormTemp",

'select' => "1",

'format' => '<div class="make-switch" data-off="warning" data-on="success">

{{form}}

<input type="hidden" name="' . $product->prefix_setting . '[buy_2_get_1_free]" class="checkboxHidden" value="' . $val . '" />

</div>'

);



$valTemp = "2";

if ($isEdit) {

$valTemp = $product->productSettingArray('buy_2_get_1_free_qty', $eData);

}

$form_fields[] = array(

'label' => _uc($_e['Buy QTY Limit']),

'name' => $product->prefix_setting . '[buy_2_get_1_free_qty]',

'value' => "$valTemp",

'type' => 'number',

"min" => "1",

'class' => 'form-control',

);



}



if ($functions->developer_setting('reviews') == '1' && $functions->ibms_setting('showReview') == '1') {

//want to send some thing between form

$form_fields[] = array(

'thisFormat' => "<hr><hr><hr>"

);



// Form review allow on this product or not

$val = '0';

if ($isEdit) {

if ($product->productSettingArray('review', $eData)) {

$val = '1';

}

}

$valFormTemp = $val;

$form_fields[] = array(

"label" => $_e['Review'],

'type' => 'checkbox',

'value' => "$valFormTemp",

'select' => "1",

'format' => '<div class="make-switch" data-off="warning" data-on="success"">

{{form}}

<input type="hidden" name="' . $product->prefix_setting . '[review]" class="checkboxHidden" value="' . $val . '" />

</div>'

);



//Review off msg

$valTemp = "";

if ($isEdit) {

$valTemp = $product->productSettingArray('reviewOffMsg', $eData);

}

$form_fields[] = array(

'label' => _uc($_e['Review Off Msg']),

'name' => $product->prefix_setting . '[reviewOffMsg]',

'placeholder' => _uc($_e['Review Off Msg']),

'value' => "$valTemp",

'type' => 'text',

'class' => 'form-control',

);



}// review Setting





if ($functions->developer_setting('askQuestion') == '1' && $functions->ibms_setting('showQuestion') == '1') {

// Form Question allow on this product or not

$val = '0';

if ($isEdit) {

if ($product->productSettingArray('askQuestion', $eData)) {

$val = '1';

}

}

$valFormTemp = $val;

$form_fields[] = array(

"label" => $_e['Ask Questions'],

'type' => 'checkbox',

'value' => "$valFormTemp",

'select' => "1",

'format' => '<div class="make-switch" data-off="warning" data-on="success"">

{{form}}

<input type="hidden" name="' . $product->prefix_setting . '[askQuestion]" class="checkboxHidden" value="' . $val . '" />

</div>'

);



//Review off msg

$valTemp = "";

if ($isEdit) {

$valTemp = $product->productSettingArray('questionOffMsg', $eData);

}

$form_fields[] = array(

'label' => _uc($_e['Question Off Msg']),

'name' => $product->prefix_setting . '[questionOffMsg]',

'placeholder' => _uc($_e['Question Off Msg']),

'value' => "$valTemp",

'type' => 'text',

'class' => 'form-control',

);



}// review Setting





if ($functions->developer_setting('isFacebookComments') == '1' && $functions->ibms_setting('showFacebookComment') == '1') {

// Form Facebook Comment allow on this product or not

$val = '0';

if ($isEdit) {

if ($product->productSettingArray('facebookComment', $eData)) {

$val = '1';

}

}

$valFormTemp = $val;

$form_fields[] = array(

"label" => $_e['Facebook Comment'],

'type' => 'checkbox',

'value' => "$valFormTemp",

'select' => "$valFormTemp",

'format' => '<div class="make-switch" data-off="warning" data-on="success"">

{{form}}

<input type="hidden" name="' . $product->prefix_setting . '[facebookComment]" class="checkboxHidden" value="' . $val . '" />

</div>'

);



//Review off msg

$valTemp = "";

if ($isEdit) {

$valTemp = $product->productSettingArray('fbCommentOffMsg', $eData);

}

$form_fields[] = array(

'label' => _uc($_e['Fb Comment Off Msg']),

'name' => $product->prefix_setting . '[fbCommentOffMsg]',

'placeholder' => _uc($_e['Fb Comment Off Msg']),

'value' => "$valTemp",

'type' => 'text',

'class' => 'form-control',

);



} // Facebook Comment Setting End



$functions->print_form($form_fields, $format);

?>



<!--<div class="form-group">

<label class="col-sm-2 control-label"><?php /*echo _uc($_e['Minimum Quantity Allow']); */ ?> </label>

<div class="col-sm-10 ">

<div class="input-group">

<?php

/*                // if product edit

$minQty="";

$checked = 'checked';

$disabled = 'disabled';

if($isEdit){

$minQty=$product->productSettingArray('minQty',$eData);

if($minQty!=''){

$checked = '';

$disabled = '';

}

}

*/ ?>

<span class="input-group-addon">

<label><input type="checkbox" <?php /*echo $checked; */ ?> data-id="minQty" class="QtyAllow"><?php /*echo _uc($_e['Use Config Setting']); */ ?></label>

</span>

<input type='text' <?php /*echo $disabled; */ ?> id="minQty" data-old="<?php /*echo $minQty;*/ ?>"

value="<?php /*echo $minQty;*/ ?>"

name="<?php /*echo $product->prefix_setting;*/ ?>[minQty]"

data-name="<?php /*echo $product->prefix_setting;*/ ?>[minQty]"

class="form-control" placeholder="<?php /*echo _uc($_e['Minimum Quantity Allow']); */ ?>"/>

</span>

</div>

</div>

</div>--><!-- setting single .Form group  End-->



<!--<div class="form-group">

<label class="col-sm-2 control-label"><?php /*echo _uc($_e['Maximum Quantity Allow']); */ ?> </label>

<div class="col-sm-10 ">

<div class="input-group">

<?php

/*                // if product edit

$maxQty="";

$checked = 'checked';

$disabled = 'disabled';

if($isEdit){

$maxQty=$product->productSettingArray('maxQty',$eData);

if($maxQty!=''){

$checked = '';

$disabled = '';

}

}

*/ ?>

<span class="input-group-addon">

<label><input type="checkbox" <?php /*echo $checked; */ ?> data-id="maxQty" class="QtyAllow"><?php /*echo _uc($_e['Use Config Setting']); */ ?></label>

</span>

<input type='text' <?php /*echo $disabled; */ ?> id="maxQty" data-old="<?php /*echo $maxQty;*/ ?>" value="<?php /*echo $maxQty;*/ ?>"

name="<?php /*echo $product->prefix_setting;*/ ?>[maxQty]"

data-name="<?php /*echo $product->prefix_setting;*/ ?>[maxQty]"

class="form-control" placeholder="<?php /*echo _uc($_e['Maximum Quantity Allow']); */ ?>"/>

</span>

</div>

</div>

</div>--> <!-- setting single .Form group  End-->





<!--<div class="form-group">

<label class="col-sm-2 control-label">Size Select Type </label>

<div class="col-sm-10 ">

<div class="input-group">

<?php

// if product edit

$selectType = "";

$disabled = 'disabled';

$checked = 'checked';

if ($isEdit) {

$selectType = $product->productSettingArray('selectType', $eData);

if ($selectType != '') {

$checked = '';

$disabled = '';

}

}

?>



<span class="input-group-addon">

<label><input type="checkbox" <?php echo $checked; ?> data-id="selectType" class="QtyAllow"><?php echo _uc($_e['Use Config Setting']); ?></label>

</span>

<select <?php echo $disabled; ?> data-old="<?php echo $selectType; ?>" id="selectType" class="form-control" data-name="<?php echo $product->prefix_setting; ?>[selectType]" name="<?php echo $product->prefix_setting; ?>[selectType]">

<option value="">Select Type</option>

<option value="check">Check Box</option>

<option value="select">Select Drop Box</option>

<option value="radio">Radio Button</option>

</select>

<script >

$(document).ready(function(){

$('#selectType').val('<?php echo $selectType; ?>').trigger('change');

});

</script>

</div>

</div>

</div> <!-- setting single .Form group  End-->





</div>

<!-- Product Setting tab End-->

</div>

<!-- Tabs Div End-->

</div>

<!-- Parent Tabs End-->

</div>

<!-- Page row Div end-->

</div>

<hr/>



<button type="submit" class="btn btn-primary btn-lg"

onsubmit="return submitProduct();"><?php echo _u($_e['SUBMIT']); ?></button>

</form>





<!-- Modal use in modal div-->

<div class="modal fade bs-example-modal-lg" id="productImgDialog" tabindex="-1" role="dialog"

aria-labelledby="myModalLabel" aria-hidden="true">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<div class="modal-header">

<button type="button" class="close" data-dismiss="modal"><span

aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

<h4 class="modal-title" id="myModalLabel"><?php echo _uc($_e['Image Preview']); ?></h4>

</div>

<div class="modal-body" style="text-align: center">

<img src="" align="center"/>

</div>

<div class="modal-footer">

<button type="button" class="btn btn-default"

data-dismiss="modal"><?php echo _uc($_e['Close']); ?></button>

</div>

</div>

</div>

</div>

<?php
if($isEdit){
$sqll = "SELECT * FROM `product_category` WHERE `procat_prodet_id` = $editId";
$ress = $dbF->getRow($sqll);
$categ = $ress['procat_cat_id'];
$cat_Array = explode(',', $categ);
$cnt = sizeof($cat_Array);

for ($i=0; $i < $cnt; $i++) { 
$cat_idd = $cat_Array[$i];


?>

<script>

var p_idd = '<?php echo $cat_idd; ?>';

$("input[name='cats[]']").each( function () {
    var ths = $(this);
    if(ths.val() == '<?php echo @$cat_idd; ?>'){
        ths.attr({'checked':'true'});
    }
});

</script>

<?php
}
}
?>



<script>

$(document).ready(function () {

////////////////////////////////////

$(".datepicker").datepicker({minDate: 0});



///////////////////////////////////////

$(".imageHolder").click(function () {

img = $(this).find("img").attr('src');

$('#productImgDialog').modal('show');

$("#productImgDialog .modal-body").find("img").attr("src", img).hide().show(600);

});



////////////////////////////////////////////

$(".productEditImageDel").click(function () {

if (secure_delete()) {

id = $(this).attr("data-id");

parnt = $(this).closest(".preview");

$.ajax({

type: "POST",

url: 'product_management/product_ajax.php?page=productEditImageDel',

data: {imageId: id}

}).done(function (data) {

if (data == '1') {

parnt.hide(500);

} else if (data == '0') {

jAlertifyAlert("<?php echo _js($_e['Image Not Delete Please Try Again']); ?>");

return false;

}

});

}

});

/////////////////////////////////////////////////



$('.QtyAllow').change(function () {

data = $(this).attr('data-id');

input = $('#' + data);

name = input.attr('data-name');

old = input.attr('data-old');

if ($(this).prop("checked")) {

input.removeAttr('name');

input.attr('readonly', 'readonly');

input.attr('disabled', 'disabled');

input.val("").change();



} else {

input.attr('name', name);

input.removeAttr('readonly');

input.removeAttr('disabled');

input.val(old).change();



}

});

/////////////////////////////////////////////////////////



$(".pImageAltUpdate").click(function () {

btn = $(this);

btn.addClass('disabled');

btn.children('.trash').hide();

btn.children('.waiting').show();



id = btn.attr('data-id');

alt = $('#alt-' + id).val();

btn.children('span').text('Wait...');

$.ajax({

type: 'POST',

url: 'product_management/product_ajax.php?page=pImageAltUpdate',

data: {imageId: id, altT: alt}

}).done(function (data) {

ift = true;

if (data == '1') {

btn.children('span').text('<?php echo _js($_e['Done']); ?>');

}

else {

btn.children('span').text('<?php echo _js($_e['Fail']); ?>');

}

btn.removeClass('disabled');

btn.children('.trash').show();

btn.children('.waiting').hide();



});

});

////////////////////////////////////////////

function submitProduct() {

convertNumber();

return true;

}



//////////////////////////

$("#dropbox").sortable({

handle: '.imageHolder',

containment: "parent",

update: function () {

serial = $(this).sortable('serialize');

$.ajax({

url: 'product_management/product_ajax.php?page=sortProductImage',

type: "post",

data: serial,

error: function () {

jAlertifyAlert("<?php echo _js($_e['There is an error, Please Refresh Page and Try Again']); ?>");

}

});

}

});

////////////////////////////////

});

</script>



<script>



// $(function () {

//     $("input[type='checkbox']").change(function () {

//         $(this).siblings('ul')

//             .find("input[type='checkbox']")

//             .prop('checked', this.checked);

//     });

//     $("input[type='checkbox'] ~ ul input[type='checkbox']").change(function() {

//         $(this).closest("li:has(li)").children("input[type='checkbox']").prop('checked', $(this).closest('ul').find("input[type='checkbox']").is(':checked'));

//     });

//   });

</script>



<script>

$('input[type=checkbox]').click(function () {

$(this).parent()

.find('li input[type=checkbox]')

.prop('checked', $(this)

.is(':checked'));

var sibs = false;



$(this).closest('ul')

.children('li').each(function () {

if($('input[type=checkbox]', this).is(':checked')) 

sibs=true;

})

$(this).parents('ul').prev().prop('checked', sibs);



$("input[type='checkbox'] ~ ul input[type='checkbox']").change(function() {

$(this).closest("li:has(li)").children("input[type='checkbox']").prop('checked', $(this).closest('ul').find("input[type='checkbox']").is(':checked'));

});

});


$('.avldy').keyup(function() {

    var val1 = $('.vldy').val();
    var val2 = $('.avldy').val();
    if(val1 < val2){
        alert('Actual validity should less than or equal to validity');
        $('.avldy').val('');
    }
    
});

</script>





<style>



#nestedlist, #nestedlist ul {

list-style-type: none;

margin-left:0;

padding-left:30px;

text-indent: -4px;

}



/* UL Layer 1 Rules */

#nestedlist {

/*font-size: 20px;*/

font-weight:bold;

}



/* UL Layer 2 Rules */

#nestedlist ul {

/*font-size: 18px;*/

font-weight: normal;

margin-top: 3px;

}



/* UL Layer 3 Rules */

#nestedlist ul ul {

font-size: 16px;

}



/* UL 4 Rules */

#nestedlist ul ul ul {

font-size: 14px;

}





/*ul li a {

text-decoration: none;

border: 1px solid #000;

border-width: 0 0 1px 1px;

border-radius: 0 0 0 10px;

}*/  

</style>

<?php return ob_get_clean(); ?>