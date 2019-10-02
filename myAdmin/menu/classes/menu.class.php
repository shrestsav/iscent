<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class WebMenu extends object_class{
public $productF;
public function __construct(){
parent::__construct('3');

/**
* MultiLanguage keys Use where echo;
* define this class words and where this class will call
* and define words of file where this class will called
**/
global $_e;
global $adminPanelLanguage;
$_w=array();
//FooterMenu.php
$_w['Manage WebSite Footer Menu'] = '' ;
$_w['Footer Menu'] = '' ;
$_w['Add New Footer Menu'] = '' ;
$_w['Delete Fail Please Try Again.'] = '' ;
$_w['There is an error, Please Refresh Page and Try Again'] = '' ;

//footerMenuEdit.php
$_w['Update Footer Menu'] = '' ;
//Index Page
$_w['Manage Website Menu'] = '' ;

//menu.php
$_w['Add New Menu'] = '' ;
$_w['Website Menu'] = '' ;
//menuEdit.php
$_w['Update Menu'] = '' ;

//This Class
$_w['Menu Update Fail'] = '' ;
$_w['Menu'] = '' ;
$_w['Menu Update Successfully'] = '' ;

$_w['Added'] = '' ;
$_w['Menu Add Successfully'] = '' ;
$_w['New Menu Add Fail'] = '' ;
$_w['SAVE'] = '' ;
$_w['Menu Name'] = '' ;
$_w['Short Desc'] = '' ;
$_w['Menu Under'] = '' ;
$_w['Icon Image Link'] = '' ;
$_w['Icon'] = '' ;
$_w['Old Icon'] = '' ;
$_w['Root Menu'] = '' ;
$_w['Link'] = '' ;
$_w['Add link for this URL'] = '' ;
$_w['Show related link for this page'] = '' ;
$_w['Image alt text'] = '' ;
$_w['Enter image alt text'] = '' ;

$_w['Page Links'] = '' ;
$_w['USE'] = '' ;
$_w['Home'] = '' ;
$_w['All Products'] = '' ;
$_w['All Deals Products'] = '' ;
$_w['Product Category Links'] = '' ;
$_w['Product Deals Category Links'] = '' ;
$_w['Featured'] = '' ;
$_w['Top Sale'] = '' ;

$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin WebSite Menu');

}



public function newMenuAdd(){
global $_e;

if(isset($_POST['submit'])){
if(!$this->functions->getFormToken('newMenu')){return false;}

$heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
$short_desc     = empty($_POST['short_desc'])   ? "" : serialize($_POST['short_desc']);
$short_desc     = data_compress($short_desc);

$link           = empty($_POST['link'])      ? ""    : $_POST['link'];
$icon           = empty($_POST['icon'])      ? ""    : $_POST['icon'];
$underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
$menuType       = empty($_POST['menuType'])  ? "main"    : $_POST['menuType'];
$link           = $this->functions->removeWebUrlFromLink($link);
$icon           = $this->functions->removeWebUrlFromLink($icon);

$image_alt      = empty($_POST['image_alt']) ? ""    : $_POST['image_alt'];


// custom working for related links menu
$link_for_value = empty($_POST['link_for'])  ? ''    : $_POST['link_for'];
$link_for_value = $this->functions->removeWebUrlFromLink($link_for_value);

$link_for_sql_one = ( isset($_GET['type']) && $_GET['type'] == 'related links') ? ' , `link_for` ' : '';
$link_for_sql_two = ( $link_for_sql_one != '' ) ? ' , ? ' : '';


try{
$this->db->beginTransaction();

$sql      =   "INSERT INTO `webmenu`(`name`,`short_desc`, `link`,`icon`, `under`,`type`,`image_alt` {$link_for_sql_one} ) VALUES (?,?,?,?,?,?,? {$link_for_sql_two} )";
$array    =     array($heading,$short_desc,$link,$icon,$underMenu,$menuType,$image_alt);

// custom working for related links menu
( $link_for_sql_two != '' ) ? array_push($array,$link_for_value) : '';

$this->dbF->setRow($sql,$array,false);
$lastId = $this->dbF->rowLastId;

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Add Successfully']),'btn-success');
$this->functions->setlog(_uc($_e['Added']),($_e['Menu']),$lastId,($_e['Menu Add Successfully']));
}else{
$this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-danger');
}
}catch (Exception $e){
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-danger');
}
} // If end
}

public function menuEditSubmit(){
global $_e;
if(isset($_POST['submit']) && isset($_POST['editId'])){
if(!$this->functions->getFormToken('editMenu')){return false;}

$heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
$short_desc     = empty($_POST['short_desc'])   ? "" : serialize($_POST['short_desc']);
$short_desc     = data_compress($short_desc);

$link           = empty($_POST['link'])      ? ""    : $_POST['link'];
$icon           = empty($_POST['icon'])      ? ""    : $_POST['icon'];

$image_alt      = empty($_POST['image_alt']) ? ""    : $_POST['image_alt'];


$underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
$menuType       = empty($_POST['menuType'])  ? ""    : $_POST['menuType'];
$lastId         = $_POST['editId'];
$link   = $this->functions->removeWebUrlFromLink($link);
$icon   = $this->functions->removeWebUrlFromLink($icon);

// custom working for related links menu
$link_for_value = empty($_POST['link_for'])  ? ''    : $_POST['link_for'];
$link_for_value = $this->functions->removeWebUrlFromLink($link_for_value);

$link_for_sql_one = isset($_POST['is_related_link']) && $_POST['is_related_link'] == 'yes' ? ' , `link_for` = ? ' : '';


try{
$this->db->beginTransaction();
if($underMenu==$lastId){
throw new Exception('Fail');
}
$sql      =   "UPDATE `webmenu` SET
`name` = ?,
`short_desc`  = ?,
`link` = ?,
`icon` = ?,
`under` = ?,
`type` = ?,
`image_alt` = ?
{$link_for_sql_one}
WHERE id = '$lastId'";
$array    =     array($heading,$short_desc,$link,$icon,$underMenu,$menuType,$image_alt);

// custom working for related links menu
( $link_for_sql_one != '' ) ? array_push($array,$link_for_value) : '';

$this->dbF->setRow($sql,$array,false);

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Successfully']),'btn-success');
$this->functions->setlog(_uc($_e['Update Menu']),_uc($_e['Menu']),$lastId,($_e['Menu Update Successfully']));
}else{
$this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-danger');
}
}catch (Exception $e){
$this->db->rollBack();
$this->dbF->error_submit($e);
$msg = $e->getMessage();
$this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-danger');
}
} // If end
}


public function menuNew(){
$this->menuEdit(true);
return false;
}



public function menuEdit($new=false){
global $_e;
$isEdit = false;
if($new ===true){
$token      =   $this->functions->setFormToken('newMenu',false);
}else {
$id         =   $_GET['menuId'];
$token      =   $this->functions->setFormToken('editMenu',false);
$token      = '<input type="hidden" name="editId" value="'.$id.'"/> '.$token;

$sql        =   "SELECT * FROM webmenu where id = '$id' ";
$data       =   $this->dbF->getRow($sql);
if($this->dbF->rowCount==0){
echo "Menu Not Found For Update";
return false;
}
$isEdit = true;
}

$action = "";
if(isset($_GET['type'])){
$action = "-".$this->functions->getLinkFolder(false);
}else if(isset($data['type'])){
$action = "-menu?page=menu&type=$data[type]";
}


// custom working for related links menu
$is_related_links_menu = ( isset($_GET['type']) && $_GET['type'] == 'related links' || isset($data) && $data['link_for'] != '' ) ? TRUE : FALSE;

# used to check if this is top most menu, then option for adding image, alt text and short desc fields appear.
$is_top_most_menu = ( isset($data['under']) && $data['under'] == '0' && $data['type'] != 'related links' || isset($_GET['type']) && $_GET['type'] == 'main' ) ? TRUE : FALSE;
// $link_label     = ( $is_related_links_menu ) ? $_e['Add link for this URL'] : $_e['Link'];
// $link_label_two = ( $is_related_links_menu ) ? $_e['Show related link for this page'] : $_e['Link'];

echo '<form method="post" action="'.$action.'" class="form-horizontal" role="form" enctype="multipart/form-data">'. $token.
'<div class="form-horizontal">';

$lang   =   $this->functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}
echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

//Link
@$link = $data['link'];
@$link   = $this->functions->addWebUrlInLink($link);

echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url" value="'.$link.'" name="link" class="pastLinkHere form-control" placeholder="'. _uc($_e['Link']) .'">
<div class="input-group-addon linkList pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>
</div>
</div>';


if ( $is_related_links_menu ):

echo '<input type="hidden" name="is_related_link" value="yes" /> ';


// Link for page. this is the link for which this related link will show.
@$link_for = $data['link_for'];
@$link_for = $this->functions->addWebUrlInLink($link_for);

echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Add link for this URL']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url" value="'.$link_for.'" name="link_for" class="pastLinkHereTwo form-control" placeholder="'. _uc($_e['Show related link for this page']) .'">
<div class="input-group-addon linkListTwo pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>
</div>
</div>';
endif;

//icon Link
@$icon = $data['icon'];
$icon   = $this->functions->addWebUrlInLink($icon);
/*if($this->functions->developer_setting('main_menu_icon')=="1") { */
// if("1"=="1") {
if( $is_top_most_menu ) {
echo "<div class='icon-div'>";
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9 ">
<img src="'.$icon.'" class="menuIcon kcFinderImage"/>
</div>
</div>';

$iconText = $this->functions->developer_setting('main_menu_icon_size');
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Icon']) ." ".$iconText. '</label>
<div class="col-sm-10  col-md-9 ">
<div class="input-group">
<input type="url" name="icon" value="'.$icon.'"  class="menuIcon form-control" placeholder="' . _uc($_e['Icon Image Link']) . '">
<div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('menuIcon')".'"><i class="glyphicon glyphicon-picture"></i></div>
</div>
</div>
</div>';
echo "</div>";
} else {

echo '<input type="hidden" name="icon" value="" /> ';

}


if ( $is_top_most_menu ):

// Link for page. this is the link for which this related link will show.
@$image_alt = $data['image_alt'];


// echo '<div class="form-group">
//         <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Image alt text']) .'</label>
//         <div class="col-sm-10  col-md-9">
//             <input type="text" value="'.$image_alt.'" name="image_alt" class="form-control" placeholder="'. _uc($_e['Enter image alt text']) .'">
//         </div>
//     </div>';
endif;


//Under Menu
//$menu_under_css = ( $is_top_most_menu == FALSE ) ? ' displaynone ' : '';
$option = $this->underMenuOption();
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
<div class="col-sm-10  col-md-9">
<select name="underMenu" id="underMenu" class="underMenu form-control">
<option value="0">'. _uc($_e['Root Menu']) .'</option>
'.$option.'
</select>
</div>
</div>';

if($isEdit) {
@$oldunder = $data['under'];
echo '<script>
$(document).ready(function(){
$("#underMenu").val("' . $oldunder . '").change();
});
</script>';
}





//Menu Type
$option = $this->menuTypesOption();
echo '<div class="form-group displaynone">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
<div class="col-sm-10  col-md-9">
<select name="" class="menuType form-control">
'.$option.'
</select>
</div>
</div>';
if($isEdit){
@$oldunder = $data['type'];
echo '<script>
$(document).ready(function(){
$(".menuType").val("' . $oldunder . '").change();
});
</script>';
}else if(isset($_GET['type'])){
@$oldunder = $_GET['type'];
echo '<script>
$(document).ready(function(){
$(".menuType").val("' . $oldunder . '").change();
});
</script>';
}

if(isset($_GET['type'])) {
echo '<input type="hidden" name="menuType" value="' . $_GET['type'] . '" />';
}elseif(isset($data['type'])) {
echo '<input type="hidden" name="menuType" value="' . $data['type'] . '" />';
}

echo '<div class="panel-group" id="accordion">';
for ($i = 0; $i < sizeof($lang); $i++) {
if($i==0){
$collapseIn = ' in ';
}else{
$collapseIn = '';
}
@$heading = unserialize($data['name']);
@$shortDesc = unserialize(data_unCompress($data['short_desc']));
echo '<div class="panel panel-default">
<div class="panel-heading">
<a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
<h4 class="panel-title">
'.$lang[$i].'
</h4>
</a>
</div>
<div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
<div class="panel-body">';

//Title
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Name']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" value="'.htmlspecialchars($heading[$lang[$i]]).'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Menu Name']) .'">
</div>
</div>';


//Short Desc
$short_desc_css = ( $is_top_most_menu == FALSE ) ? ' displaynone ' : '';
// echo '<div class="form-group ' . $short_desc_css .'">
//         <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Desc']) .'</label>
//         <div class="col-sm-10  col-md-9">
//             <input type="text" value="'.htmlspecialchars($shortDesc[$lang[$i]]).'" name="short_desc['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Short Desc']) .'">
//         </div>
//     </div>';


echo '       </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';


//echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

echo "</div>
</form>";
}

public function menuTypes()
{
$types  = $this->functions->developer_setting('main_menu_type');
$types   = explode("/",$types);
echo '<div class="panel-group accordion">';
foreach ($types as $val) {
$val = explode(",",$val);
$heading = $val[0];
$icon = $val[1];
$headingUC = _uc($heading);
echo '<div class="panel panel-default relative" ">
<div class="panel-heading">
<a href="-menu?page=menu&type='.$heading.'">
<h4 class="panel-title">
' . $headingUC . '</h4>
</a>';
echo "               ";
echo '
</div>
</div> <!-- collapse end-->';
}
echo ' </div>';
}

public function menuView($type='main'){
$sql = "SELECT * FROM webmenu WHERE  under = '0' AND type='$type' ORDER BY sort";
$data = $this->dbF->getRows($sql);

$defaultLang = $this->functions->AdminDefaultLanguage();
echo '<div class="panel-group accordion">';
foreach($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$heading = htmlspecialchars($heading);
$id =$val['id'];

$hide = "";
if($_GET['type'] != "main"){

$hide = "displaynone";


}


echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
<div class="panel-heading">
<div  class="menuSortDiv">  :: </div>
<a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
<h4 class="panel-title">
'.$heading.'</h4>
</a>';
echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
<a onclick='addNewMenu($id);' class='btn btn-primary $hide'>
<i class='glyphicon glyphicon-plus'></i>
</a>
<a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div> ";
echo '
</div>
<div id="'.$id.'" class="panel-collapse collapse">
<div class="panel-body">';
echo $this->menuViewUnder2($id,$defaultLang,$type);

echo '           </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';
}


public function menuViewUnder2($id,$defaultLang,$type='main'){
$sql = "SELECT * FROM webmenu WHERE  under = '$id' AND type='$type' ORDER BY sort";
$data = $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){return false;}

echo '<div class="panel-group accordion2">';
foreach($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$id =$val['id'];
echo '<div class="panel panel-default relative" id="menu_'.$id.'">
<div class="panel-heading" >
<div  class="menuSortDiv">  :: </div>
<a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
<h4 class="panel-title">
'.$heading.'</h4>
</a>';
echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
<a onclick='addNewMenu($id);' class='btn btn-primary'>
<i class='glyphicon glyphicon-plus'></i>
</a>
<a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div> ";
echo '
</div>
<div id="'.$id.'" class="panel-collapse collapse">
<div class="panel-body">';
echo $this->menuViewUnder3($id,$defaultLang,$type);

echo '           </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';
}

public function menuViewUnder3($id,$defaultLang,$type='main'){
$sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
$data = $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){return false;}

echo '<div class="panel-group accordion3">';
foreach($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$id =$val['id'];
echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
<div class="panel-heading">
<div  class="menuSortDiv">  :: </div>
<a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
<h4 class="panel-title">
'.$heading.'</h4>
</a>';
echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>

<a onclick='addNewMenu($id);' class='btn btn-primary'>
<i class='glyphicon glyphicon-plus'></i>
</a>


<a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div> ";


echo '

<div id="'.$id.'" class="panel-collapse collapse">
<div class="panel-body">';
echo $this->menuViewUnder4($id,$defaultLang,$type);

echo '</div> <!-- panel-body end -->
</div> <!-- collapse end-->

';

echo '
</div></div>
';
}
echo '</div> <!-- .accordian end -->';
}







public function menuViewUnder4($id,$defaultLang,$type='main'){
$sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
$data = $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){return false;}

echo '<div class="panel-group accordion3">';
foreach($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$id =$val['id'];
echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
<div class="panel-heading">
<div  class="menuSortDiv">  :: </div>
<a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
<h4 class="panel-title">
'.$heading.'</h4>
</a>';
echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
<a data-id='$id' href='-menu?page=edit&menuId=$id' class='btn btn-primary'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteMenu(this);' class='btn btn-primary'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div> ";
echo '
</div>
</div>
';
}
echo '</div> <!-- .accordian end -->';
}


public function underMenuOption(){
$type = '';
if(isset($_GET['type'])) {
$type = "AND type = '$_GET[type]' ";
}

$sql  = "SELECT * FROM webmenu WHERE  under = '0' $type ORDER BY sort";
$data = $this->dbF->getRows($sql);
$opt  = '';
$defaultLang = $this->functions->AdminDefaultLanguage();

foreach ($data as $val){
$menu2       = $this->underMenu2Option($val['id'],$defaultLang);
$heading    = unserialize($val['name']);
@$heading    = $heading[$defaultLang];
$opt        .= '<option value="'.$val['id'].'">'.htmlentities($heading).'</option>';
if($menu2   !=  false){
$opt    .= $menu2;
} else{
continue;
}
}
return $opt;
}

public function menuTypesOption(){
$types  = $this->functions->developer_setting('main_menu_type');
$types   = explode("/",$types);
$opt = '';

foreach($types as $val){
$val = explode(",",$val);
$value = $val[0];
$icon = $val[1];
$type = _uc($value);

$opt .='<option value="'.$value.'" data-icon="'.$icon.'">'.htmlentities($type).'</option>';
}

return $opt;


}

public function underMenu2Option($id,$defaultLang){
$sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
$data = $this->dbF->getRows($sql);
$temp = '';
if($this->dbF->rowCount){
foreach ($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$temp .='<option value="'.$val['id'].'"> -- '.$heading.'</option>';
$menu3 = $this->underMenu3Option($val['id'],$defaultLang);
if($menu3!=false){
$temp .= $menu3;
}
else{
continue;
}
}
return $temp;
}else{
return false;
}
}


public function underMenu3Option($id,$defaultLang){
$sql = "SELECT * FROM webmenu WHERE  under = '$id' ORDER BY sort";
$data = $this->dbF->getRows($sql);
$temp = '';
if($this->dbF->rowCount){
foreach ($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$temp .='<option value="'.$val['id'].'"> -- -- '.$heading.'</option>';
}
return $temp;
}else{
return false;
}
}



/*
*
*
* Footer Menu
*
*
*/

public function footerNewMenuAdd(){
global $_e;
if(isset($_POST['submit'])){
if(!$this->functions->getFormToken('footerNewMenu')){return false;}

$heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
$link           = empty($_POST['link'])      ? ""    : $_POST['link'];
$underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
$link           =   str_replace(WEB_URL,'',$link);
try{
$this->db->beginTransaction();

$sql      =   "INSERT INTO `webfootermenu`(`name`, `link`, `under`) VALUES (?,?,?)";
$array    =     array($heading,$link,$underMenu);
$this->dbF->setRow($sql,$array,false);
$lastId = $this->dbF->rowLastId;

$this->db->commit();
if($this->dbF->rowCount>0) {
$this->functions->notificationError(_uc($_e['Menu']), ($_e['Menu Add Successfully']), 'btn-success');
$this->functions->setlog(_uc($_e['Added']), _uc($_e['Footer Menu']), $lastId, ($_e['Menu Add Successfully']));
}else{
$this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-success');
}
}catch (Exception $e){
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_uc($_e['Menu']),($_e['New Menu Add Fail']),'btn-success');
}
} // If end
}

public function footerMenuEditSubmit(){
global $_e;
if(isset($_POST['submit']) && isset($_POST['editId'])){
if(!$this->functions->getFormToken('footerEditMenu')){return false;}

$heading        = empty($_POST['heading'])   ? ""    : serialize($_POST['heading']);
$link           = empty($_POST['link'])      ? ""    : $_POST['link'];
$underMenu      = empty($_POST['underMenu']) ? ""    : $_POST['underMenu'];
$lastId         = $_POST['editId'];
$link   = str_replace(WEB_URL,'',$link);
try{
$this->db->beginTransaction();
if($underMenu==$lastId){
throw new Exception('Fail');
}
$sql      =   "UPDATE `webfootermenu` SET
`name` = ?,
`link` = ?,
`under` = ?
WHERE id = '$lastId'";
$array    =     array($heading,$link,$underMenu);
$this->dbF->setRow($sql,$array,false);

$this->db->commit();
if($this->dbF->rowCount>0) {
$this->functions->notificationError(_uc($_e['Menu']), ($_e['Menu Update Successfully']), 'btn-success');
$this->functions->setlog(_uc($_e['Update Menu']), _uc($_e['Footer Menu']), $lastId, ($_e['Menu Update Successfully']));
}else{
$this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-success');
}
}catch (Exception $e){
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_uc($_e['Menu']),($_e['Menu Update Fail']),'btn-success');
}
} // If end
}


public function footerMenuView(){
$sql = "SELECT * FROM webfootermenu WHERE  under = '0' ORDER BY sort";
$data = $this->dbF->getRows($sql);

$defaultLang = $this->functions->AdminDefaultLanguage();
echo '<div class="panel-group accordion">';
foreach($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$id =$val['id'];
echo '<div class="panel panel-default relative"  id="menu_'.$id.'">
<div class="panel-heading">
<div  class="menuSortDiv">  :: </div>
<a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
<h4 class="panel-title">
'.$heading.'</h4>
</a>';


echo "


<div class='btn-group btn-group-sm pull-right menu_action'>
   <a onclick='addNewMenu($id);' class='btn btn-primary'>
                                                    <i class='glyphicon glyphicon-plus'></i>
                                               </a>



<a data-id='$id' href='-menu?page=footerMenuEdit&menuId=$id' class='btn btn-primary'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteFooterMenu(this);' class='btn btn-primary'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div> ";
echo '
</div>
<div id="'.$id.'" class="panel-collapse collapse">
<div class="panel-body">';
echo $this->footerMenuViewUnder2($id,$defaultLang);

echo '           </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';
}

public function footerMenuViewUnder2($id,$defaultLang){
$sql = "SELECT * FROM webfootermenu WHERE  under = '$id' ORDER BY sort";
$data = $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){return false;}

echo '<div class="panel-group accordion2">';
foreach($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$id =$val['id'];
echo '<div class="panel panel-default relative" id="menu_'.$id.'">
<div class="panel-heading" >
<div  class="menuSortDiv">  :: </div>
<a data-toggle="collapse" data-parent="#accordion" href="#'.$id.'">
<h4 class="panel-title">
'.$heading.'</h4>
</a>';
echo "                       <div class='btn-group btn-group-sm pull-right menu_action'>
<a data-id='$id' href='-menu?page=footerMenuEdit&menuId=$id' class='btn btn-primary'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteFooterMenu(this);' class='btn btn-primary'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div> ";
echo '
</div>
<div id="'.$id.'" class="panel-collapse collapse">
';

echo '
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';
}



public function footerMenuNew(){
global $_e;
$token       = $this->functions->setFormToken('footerNewMenu',false);
//No need to remove any thing,, go in developer setting table and set 0
echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">'.
$token.
'<div class="form-horizontal">';


$lang = $this->functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}
echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';


//Link
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url" name="link" class="form-control pastLinkHere" placeholder="'. _uc($_e['Link']) .'">
<div class="input-group-addon linkList pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>
</div>
</div>';

//Under Menu
$option = $this->underFooterMenuOption();
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
<div class="col-sm-10  col-md-9">
<select name="underMenu" class="underMenu form-control">
<option value="0">'. _uc($_e['Root Menu']) .'</option>
'.$option.'
</select>
</div>
</div>';

echo '<div class="panel-group" id="accordion">';
for ($i = 0; $i < sizeof($lang); $i++) {
if($i==0){
$collapseIn = ' in ';
}else{
$collapseIn = '';
}
echo '<div class="panel panel-default">
<div class="panel-heading">
<a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
<h4 class="panel-title">
'.$lang[$i].'
</h4>
</a>
</div>
<div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
<div class="panel-body">';

//Title
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Name']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Menu Name']) .'">
</div>
</div>';


echo '       </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';

//echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

echo "</div>
</form>";
}

public function underFooterMenuOption(){
global $_e;
$sql = "SELECT * FROM webfootermenu WHERE  under = '0' ORDER BY sort";
$data = $this->dbF->getRows($sql);
$opt = '';
$defaultLang = $this->functions->AdminDefaultLanguage();

foreach ($data as $val){
$heading = unserialize($val['name']);
$heading = $heading[$defaultLang];
$opt .='<option value="'.$val['id'].'">'.$heading.'</option>';
}
return $opt;
}

public function FooterMenuEdit(){
global $_e;
$token       = $this->functions->setFormToken('footerEditMenu',false);
$id     =   $_GET['menuId'];
$sql    =   "SELECT * FROM webfootermenu where id = '$id' ";
$data   =   $this->dbF->getRow($sql);
if($this->dbF->rowCount==0){
echo "Footer Menu Not Found For Update";
return false;
}
echo '<form method="post" action="-menu?page=footerMenu" class="form-horizontal" role="form" enctype="multipart/form-data">
<input type="hidden" name="editId" value="'.$id.'"/>'.
$token.
'<div class="form-horizontal">';


$lang = $this->functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}
echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';


//Link
$link = $data['link'];
if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){

}else{
$link = WEB_URL.$link;
}
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url" value="'.$link.'" name="link" class="pastLinkHere form-control" placeholder="'. _uc($_e['Link']) .'">
<div class="input-group-addon linkList pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>
</div>
</div>';

//Under Menu
$option = $this->underFooterMenuOption();
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Under']) .'</label>
<div class="col-sm-10  col-md-9">
<select name="underMenu" id="underMenu" class="form-control">
<option value="0">'. _uc($_e['Root Menu']) .'</option>
'.$option.'
</select>
</div>
</div>';
$oldunder = $data['under'];
echo '<script>
$(document).ready(function(){
$("#underMenu").val("'.$oldunder.'").change();
});
</script>';

echo '<div class="panel-group" id="accordion">';
for ($i = 0; $i < sizeof($lang); $i++) {
if($i==0){
$collapseIn = ' in ';
}else{
$collapseIn = '';
}
$heading = unserialize($data['name']);
echo '<div class="panel panel-default">
<div class="panel-heading">
<a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
<h4 class="panel-title">
'.$lang[$i].'
</h4>
</a>
</div>
<div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
<div class="panel-body">';

//Title
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Menu Name']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" value="'.$heading[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Menu Name']) .'">
</div>
</div>';


echo '       </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';
}
echo '</div> <!-- .accordian end -->';

//echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

echo "</div>
</form>";
}

public function pageLinks($copyDiv=true, $copy_func_name = 'copyLink'){
global $_e;
$sql    =   "SELECT page_pk,slug FROM `pages` WHERE publish = '1'";
$data   =   $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){
return false;
}
$temp   =   "<div class='col-sm-12 form-horizontal'>
<h3>". _uc($_e['Page Links']) ."</h3>";

$link   =   WEB_URL."/";

$link1   =   WEB_URL."/products";

$link2   =   WEB_URL."/news";

$slug = _uc($_e['Home']);
$slug1 = _uc('Products');
$slug2 = _uc('News');
$temp2 = "";

$temp22 = "";
$temp33 = "";

if($copyDiv){
$temp2 = "<div class='input-group-addon pointer' data-val='$link' onclick='{$copy_func_name}(this);'>". _u($_e['USE']) ."</div>";

// $temp22 = "<div class='input-group-addon pointer' data-val='$link1' onclick='{$copy_func_name}(this);'>". _u($_e['USE']) ."</div>";


// $temp33 = "<div class='input-group-addon pointer' data-val='$link2' onclick='{$copy_func_name}(this);'>". _u($_e['USE']) ."</div>";


}

$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$slug
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control'>
$temp2
</div>
</div>

";

foreach($data as $val){
$link   =   WEB_URL."/".$this->db->dataPage."$val[slug]";
$slug   =   $val['slug'];
$temp2 = "";
if($copyDiv){
$temp2 = "<div class='input-group-addon pointer' data-val='$link' onclick='{$copy_func_name}(this);'>". _u($_e['USE']) ."</div>";
}

$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$slug
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control'>
$temp2
</div>
</div>
";
}


############# PROJECT SPECIFIC WORK #############

############ Products Page Links Generation ############ 
// $temp .= '<h3>'. _uc('Products Page Links') .'</h3>';

// $link   =   WEB_URL . '/' . $this->db->productsPage;
// $slug = _uc('Products Page');
// $temp2 = "";
// if($copyDiv){
//     $temp2 = "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>". _u($_e['USE']) ."</div>";
// }

// $temp   .=    "<div class='form-group'>
//                         <label class='col-sm-2 control-label'>
//                             $slug
//                         </label>
//                         <div class='input-group col-sm-10'>
//                           <input type='text' value='$link' class='form-control'>
//                           $temp2
//                         </div>
//                     </div>
//                   ";

// $temp   .= $this->get_product_categories();



$temp .= "</div>";
return $temp;
}

public function categoryLinks($copyDiv=true,$productDeal=false){
global $_e;
$sql    =   "SELECT * FROM `tree_data` WHERE id != '1'";
$data   =   $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){
return false;
}
$temp   =   "<div class='col-sm-12 form-horizontal'>";

if($productDeal){
$temp .= "<h3>" . _uc($_e['Product Deals Category Links']) . "</h3>";
}else {
$temp .= "<h3>" . _uc($_e['Product Category Links']) . "</h3>";
}

if($productDeal) {
$link = WEB_URL . "/productDeals";
$slug = _uc($_e['All Deals Products']);
}else {
$link = WEB_URL . "/products";
$slug = _uc($_e['All Products']);
}


$temp2 = "";
if($copyDiv){
$temp2 = "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";
}

$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$slug
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control'>
$temp2
</div>
</div>";

if($productDeal) {
$link = WEB_URL . "/".$this->db->dealCategory;
}else {
$link = WEB_URL . "/".$this->db->pCategory;
}


// featured
$featured      = _uc($_e['Featured']);
$featured_link = WEB_URL . "/products?view=featured";;
$temp3 = "<div class='input-group-addon pointer' data-val='$featured_link' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";

// adding featured link
$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
{$featured}
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$featured_link' class='form-control'>
$temp3
</div>
</div>
";

// top sale
$top_sale      = _uc($_e['Top Sale']);
$top_sale_link = WEB_URL . "/products?view=top_sale";;
$temp3 = "<div class='input-group-addon pointer' data-val='$top_sale_link' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";

// adding featured link
$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
{$top_sale}
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$top_sale_link' class='form-control'>
$temp3
</div>
</div>
";




// foreach($data as $val){
// //$linkThis =  $link."?cat=$val[nm]&catId=$val[id]";
// $linkThis =  $link."$val[id]-$val[nm]"; // pCategory slug
// $slug  =  $val['nm'];

// $temp2 = "";
// if($copyDiv){
// $temp2 = "<div class='input-group-addon pointer' data-val='$linkThis' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";
// }

// $temp   .=    "<div class='form-group'>
// <label class='col-sm-2 control-label'>
// $slug
// </label>
// <div class='input-group col-sm-10'>
// <input type='text' value='$linkThis' class='form-control'>
// $temp2
// </div>
// </div>
// ";
// }
$temp .= "</div>";
return $temp;
}

public function categoryLinks1($copyDiv=true,$productDeal=false){
global $_e;
$sql    =   "SELECT * FROM `categories`";
$data   =   $this->dbF->getRows($sql);
if(!$this->dbF->rowCount){
return false;
}
$temp   =   "<div class='col-sm-12 form-horizontal'>";

if($productDeal){
$temp .= "<h3>" . _uc($_e['Product Deals Category Links']) . "</h3>";
}else {
// $temp .= "<h3>" . _uc($_e['Product Category Links']) . "</h3>";
}

if($productDeal) {
$link = WEB_URL . "/productDeals";
$slug = _uc($_e['All Deals Products']);
}else {
$link = WEB_URL . "/products";
$slug = _uc($_e['All Products']);
}


$temp2 = "";
if($copyDiv){
$temp2 = "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";
}

// $temp   .=    "<div class='form-group'>
//                         <label class='col-sm-2 control-label'>
//                             $slug
//                         </label>
//                         <div class='input-group col-sm-10'>
//                           <input type='text' value='$link' class='form-control'>
//                           $temp2
//                         </div>
//                     </div>";

if($productDeal) {
$link = WEB_URL . "/".$this->db->dealCategory;
}else {
$link = WEB_URL . "/products-";
}


// featured
$featured      = _uc($_e['Featured']);
$featured_link = WEB_URL . "/products?view=featured";;
$temp3 = "<div class='input-group-addon pointer' data-val='$featured_link' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";

// adding featured link
// $temp   .=    "<div class='form-group'>
//                     <label class='col-sm-2 control-label'>
//                         {$featured}
//                     </label>
//                     <div class='input-group col-sm-10'>
//                       <input type='text' value='$featured_link' class='form-control'>
//                       $temp3
//                     </div>
//                 </div>
// ";

// top sale
$top_sale      = _uc($_e['Top Sale']);
$top_sale_link = WEB_URL . "/products?view=top_sale";;
$temp3 = "<div class='input-group-addon pointer' data-val='$top_sale_link' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";

// adding featured link
// $temp   .=    "<div class='form-group'>
//                     <label class='col-sm-2 control-label'>
//                         {$top_sale}
//                     </label>
//                     <div class='input-group col-sm-10'>
//                       <input type='text' value='$top_sale_link' class='form-control'>
//                       $temp3
//                     </div>
//                 </div>
// ";


//echo "<pre>"; print_r($data);

// foreach($data as $val){
// //$linkThis =  $link."?cat=$val[nm]&catId=$val[id]";
// $name = unserialize($val['name']);
// $nm = $name['English'];
// $linkThis =  $link."$val[id]"; // pCategory slug
// $slug  =  $nm;

// $temp2 = "";
// if($copyDiv){
// $temp2 = "<div class='input-group-addon pointer' data-val='$linkThis' onclick='copyLink(this);'>". _uc($_e['USE']) ."</div>";
// }

// $temp   .=    "<div class='form-group'>
// <label class='col-sm-2 control-label'>
// $slug
// </label>
// <div class='input-group col-sm-10'>
// <input type='text' value='$linkThis' class='form-control'>
// $temp2
// </div>
// </div>
// ";
// }
$temp .= "</div>";
return $temp;
}

public function menuWidgetLinks($copyDiv=true){
global $_e;
//Required class .linkList where click to open Diaglog,
//if want to past, use .pastLinkHere class on target input area
// if copyDiv is true, its mean USE will show, to copy link
$body    = $this->pageLinks($copyDiv);
$bodyTwo = $this->pageLinks($copyDiv, $js_func_name = 'copyLinkTwo');
if($this->functions->developer_setting('product') == '1') {
$body .= $this->categoryLinks1($copyDiv);
if($this->functions->developer_setting('dealProduct') == '1') {
$body .= $this->categoryLinks($copyDiv, true);
}
}



$this->functions->dialogCommon('linkList','linkList',true,$body);
$this->functions->dialogCommon('linkListTwo','linkList',true,$bodyTwo);
echo "
<style>
#linkList{
max-height: 400px !important;
}
</style>
<script>
$(document).ready(function(){
$('.linkList').click(function(){
$('#linkList').dialog('open');
});

});
function copyLink(ths){
link    = $(ths).attr('data-val');
$('.pastLinkHere').val(link);
$('#linkList').dialog('close');
}
$(document).ready(function(){
$('.linkListTwo').click(function(){
$('#linkListTwo').dialog('open');
});

});
function copyLinkTwo(ths){
link    = $(ths).attr('data-val');
$('.pastLinkHereTwo').val(link);
$('#linkListTwo').dialog('close');
}


</script>";

}

public function get_product_categories()
{

$temp = '';
$rows = $this->dbF->getRows(' SELECT DISTINCT `category` FROM `products` WHERE `category` != "" ');



foreach ($rows as $row) {


$link   =   WEB_URL . '/' . $this->db->productsPage . '?pcat=' . urlencode($row['category']);
$slug   = _uc($row['category']);

$temp2 = "<div class='input-group-addon pointer' data-val='$link' onclick='copyLink(this);'>". _u('USE') ."</div>";


$temp   .=    "<div class='form-group'>
<label class='col-sm-2 control-label'>
$slug
</label>
<div class='input-group col-sm-10'>
<input type='text' value='$link' class='form-control'>
$temp2
</div>
</div>
";




}


return $temp;
}

}
?>