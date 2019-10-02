<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class service extends object_class{
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
//Index
$_w['Service'] = '' ;
$_w['Services Management'] = '' ;
$_w['Enter Image URL'] = '' ;
//serviceEdit.php
$_w['Manage Service'] = '' ;
$_w['Service Title'] = '' ;
//service.php
$_w['Active Service'] = '' ;
$_w['Draft'] = '' ;
$_w['Sort'] = '' ;
$_w['Sort Service'] = '' ;
$_w['Add New'] = '' ;
$_w['Add New Service'] = '' ;
$_w['Delete Fail Please Try Again.'] = '' ;
$_w['There is an error, Please Refresh Page and Try Again'] = '' ;
$_w['SNO'] = '' ;
$_w['TITLE'] = '' ;
$_w['IMAGE'] = '' ;
$_w['Sort'] = '' ;
$_w['ACTION'] = '' ;
$_w['Add New'] = '' ;

$_w['Image File Error'] = '' ;
$_w['Image Not Found'] = '' ;
$_w['service'] = '' ;
$_w['Added'] = '' ;
$_w['Service Add Successfully'] = '' ;
$_w['Service Add Failed'] = '' ;
$_w['Service Update Failed'] = '' ;
$_w['Service Update Successfully'] = '' ;
$_w['Update'] = '' ;
$_w['Service Title'] = '' ;
$_w['Service Link'] = '' ;
$_w['Short Desc'] = '' ;
$_w['Image Recommended Size : 605px X 334px'] = '' ;
$_w['Publish'] = '' ;
$_w['Layer'] = '' ;
$_w['Service'] = '' ;

$_w['SAVE'] = '' ;
$_w['Designation'] = '' ;
$_w['Email'] = '' ;
$_w['Date'] = '' ;
$_w['Old File Image'] = '' ;
$_w['Service type'] = '' ;
$_w['Service Note'] = '' ;

$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Service');

}


public function serviceSort(){
echo '<div class="table-responsive sortDiv">
<div class="container-fluid activeSort">';
$sql ="SELECT * FROM `service` WHERE publish = '1' ORDER BY sort ASC";
$data = $this->dbF->getRows($sql);

$defaultLang = $this->functions->AdminDefaultLanguage();
foreach($data as $val){
$id = $val['id'];
@$service_image    =   unserialize($val['service_image']);
@$image    =  $this->functions->addWebUrlInLink($service_image[$defaultLang]);
@$title = unserialize($val['service_heading']);
@$title = $title[$defaultLang];
echo '  <div class="singleAlbum " id="album_'.$id.'">
<div class="col-sm-12 albumSortTop"> ::: </div>
<div class="albumImage"><img src="'.$image.'"  class="img-responsive"/></div>
<div class="clearfix"></div>
<div class="albumMange col-sm-12">
<div class="col-sm-12 btn-default" style="">'.$title.'</div>
</div>
</div>';
}

echo '</div>';
echo '</div>';
}


public function serviceView(){
$sql  = "SELECT * FROM `service` WHERE publish='1' ORDER BY ID DESC";
$data =  $this->dbF->getRows($sql);
$this->servicePrint($data);
}

public function serviceDraft(){
$sql  = "SELECT * FROM `service` WHERE publish='0' ORDER BY ID DESC";
$data =  $this->dbF->getRows($sql);
$this->servicePrint($data);
}

public function servicePrint($data){
global $_e;
$class = 'tableIBMS';
$heading = false;
if($this->functions->developer_setting('service_heading')=='0'){
$class=" dTable tableIBMS";
$heading = true;
}
echo '<div class="table-responsive">
<table class="table table-hover '.$class.'">
<thead>
<th>'. _u($_e['SNO']) .'</th>';
if($heading){
echo        '<th>'. _u($_e['TITLE']) .'</th>';
}
echo        '<th>'. _u('Link') .'</th>';
echo            '
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';

$i = 0;
$defaultLang = $this->functions->AdminDefaultLanguage();
foreach($data as $val){
$i++;
$id = $val['id'];
echo "<tr>
<td>$i</td>";
if($heading){
@$service_heading = unserialize($val['service_heading']);
@$service_heading = $service_heading[$defaultLang];
echo "<td>$service_heading</td>";
}

@$service_link    =   ($val['service_link']);
@$service_image    =   unserialize($val['service_image']);
@$service_image    =   $this->functions->addWebUrlInLink($service_image[$defaultLang]);

// @$service_type     = $this->get_service_typename($val['service_type']);

echo "
<td>$service_link</td>

<td>
<div class='btn-group btn-group-sm'>
<a data-id='$id' href='-service?page=edit&fileId=$id' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deleteservice(this);' class='btn'>
<i class='glyphicon glyphicon-trash trash'></i>
<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
</a>
</div>
</td>
</tr>";
}


echo '</tbody>
</table>
</div> <!-- .table-responsive End -->';
}

public function newserviceAdd(){
global $_e;
if(isset($_POST['submit'])){
if(!$this->functions->getFormToken('newservice')){return false;}

$heading        = empty($_POST['service_heading'])   ? ""    : serialize($_POST['service_heading']);
$link           = empty($_POST['service_link'])      ? ""    : $_POST['service_link'];
$short_desc     = empty($_POST['service_shrtDesc'])  ? ""    : serialize($_POST['service_shrtDesc']);
$publish        = empty($_POST['publish'])          ? "0"   : $_POST['publish'];
$service_image         =    empty($_POST['service_image'])          ? ""    : ($_POST['service_image']);
$service_position      = empty($_POST['service_position'])          ? ""    : ($_POST['service_position']);
$service_email         =    empty($_POST['service_email'])          ? ""    : ($_POST['service_email']);
$service_date         =     empty($_POST['service_date'])          ? ""    : ($_POST['service_date']);
$service_type     = empty($_POST['type'])          ? ''    : ($_POST['type']);


$service_image          =   serialize($this->functions->removeWebUrlFromLink($service_image));
$service_position       =   serialize($this->functions->removeWebUrlFromLink($service_position));
$service_email          =   serialize($this->functions->removeWebUrlFromLink($service_email));
$service_date           =   serialize($this->functions->removeWebUrlFromLink($service_date));

try{
$this->db->beginTransaction();

$sql      =   "INSERT INTO `service`(
`service_link`, `service_heading`, `service_shrtDesc`,`service_image`,`service_position`,`service_email`,`service_date`,`service_type`,`publish`)
VALUES (?,?,?,?,?,?,?,?,?)";

$array   = array($link,$heading,$short_desc,$service_image,$service_position,$service_email,$service_date,$service_type,$publish);
$this->dbF->setRow($sql,$array,false);

$lastId = $this->dbF->rowLastId;
// $this->functions->setting_fieldsSet($lastId,'service',false);

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_uc($_e['Service']),($_e['Service Add Successfully']),'btn-success');
$this->functions->setlog(_uc($_e['Added']),_uc($_e['Service']),$lastId,($_e['Service Add Successfully']));
}else{
$this->functions->notificationError(_uc($_e['Service']),($_e['Service Add Failed']),'btn-danger');
}
}catch (Exception $e){
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_uc($_e['Service']),($_e['Service Add Failed']),'btn-danger');
}
} // If end
}




public function serviceEditSubmit(){
global $_e;
if(isset($_POST['submit'])){
if(!$this->functions->getFormToken('editservice')){return false;}

$heading        = empty($_POST['service_heading'])   ? ""    : serialize($_POST['service_heading']);
$link           = empty($_POST['service_link'])      ? ""    : $_POST['service_link'];
$short_desc     = empty($_POST['service_shrtDesc'])  ? ""    : serialize($_POST['service_shrtDesc']);
$publish        = empty($_POST['publish'])          ? "0"   : $_POST['publish'];
$service_image         = empty($_POST['service_image'])          ? ""    : ($_POST['service_image']);
$service_position     = empty($_POST['service_position'])          ? ""    : ($_POST['service_position']);
$service_email         = empty($_POST['service_email'])          ? ""    : ($_POST['service_email']);
$service_date         = empty($_POST['service_date'])          ? ""    : ($_POST['service_date']);
$service_type     = empty($_POST['service_type'])          ? ''    : ($_POST['service_type']);


$service_image          =   serialize($this->functions->removeWebUrlFromLink($service_image));
$service_position       =   serialize($this->functions->removeWebUrlFromLink($service_position));
$service_email          =   serialize($this->functions->removeWebUrlFromLink($service_email));
$service_date           =   serialize($this->functions->removeWebUrlFromLink($service_date));
$service_type           =   serialize($this->functions->removeWebUrlFromLink($service_type));

try{
$this->db->beginTransaction();
$lastId   =   $_POST['editId'];

$sql    =  "UPDATE `service` SET
`service_link`=?,
`service_heading`=?,
`service_shrtDesc`=?,
`service_image`=?,
`service_position`=?,
`service_email`=?,
`service_date`=?,
`service_type`=?,
`publish`=?
WHERE id = '$lastId'
";

$array   = array($link, $heading, $short_desc,
$service_image,$service_position,$service_email,$service_date,$service_type,
$publish);
$this->dbF->setRow($sql,$array,false);
// $this->functions->setting_fieldsSet($lastId,'service',false);

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_uc($_e['Service']),($_e['Service Update Successfully']),'btn-success');
$this->functions->setlog(_uc($_e['Update']),_uc($_e['Service']),$lastId,($_e['Service Update Successfully']));
}else{
$this->functions->notificationError(_uc($_e['Service']),($_e['Service Update Failed']),'btn-danger');
}
}catch (Exception $e){
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_uc($_e['Service']),($_e['Service Update Failed']),'btn-danger');
}

}
}

public function serviceNew(){
global $_e;
$this->serviceEdit(true);
}

public function serviceEdit($new=false){
global $_e;
if($new){
$token       = $this->functions->setFormToken('newservice',false);
}else {
$id = $_GET['fileId'];
$sql = "SELECT * FROM `service` where id = '$id' ";
$data = $this->dbF->getRow($sql);

$token = $this->functions->setFormToken('editservice', false);
$token .= '<input type="hidden" name="editId" value="'.$id.'"/>';
}

$size = $this->functions->developer_setting('service_size');
//No need to remove any thing,, go in developer setting table and set 0

echo '<form method="post" action="-service?page=service" class="form-horizontal" role="form" enctype="multipart/form-data">'.
$token.
'
<div class="form-horizontal">';

$lang = $this->functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}

echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

echo '<div class="panel-group" id="accordion">';


@$service_heading = unserialize($data['service_heading']);
@$service_shrtDesc =  unserialize($data['service_shrtDesc']);
@$service_image = unserialize($data['service_image']);
@$service_position = unserialize($data['service_position']);
@$service_email = unserialize($data['service_email']);
@$service_date = unserialize($data['service_date']);
@$service_type = unserialize($data['service_type']);

$developer_service_heading = $this->functions->developer_setting('service_heading');
$developer_service_shrtDesc     = $this->functions->developer_setting('service_shrtDesc');
$developer_service_shrtDescEditor = $this->functions->developer_setting('service_shrtDescEditor');
$developer_service_image     = $this->functions->developer_setting('service_image');
$developer_service_position      = $this->functions->developer_setting('service_position');
$developer_service_email      = $this->functions->developer_setting('service_email');
$developer_service_type      = $this->functions->developer_setting('service_type');

for ($i = 0; $i < sizeof($lang); $i++) {
if ($i == 0) {
$collapseIn = ' in ';
} else {
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
if($developer_service_heading=='0'){
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Service Title']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" name="service_heading['.$lang[$i].']" value="'.@$service_heading[$lang[$i]].'" class="form-control" placeholder="'. _uc($_e['Service Title']) .'">
</div>
</div>';
}else{ echo '<input type="hidden" name="service_heading['.$lang[$i].']" value="" class="form-control">';}

//Short Desc
if($developer_service_shrtDesc=='0'){
$classEditor = '';
if($developer_service_shrtDescEditor=='0'){
$classEditor = 'ckeditor';
}
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Desc']) .'</label>
<div class="col-sm-10  col-md-9">
<textarea name="service_shrtDesc['.$lang[$i].']" id="service_shrtDesc" maxlength="500" class="'.$classEditor.' form-control" placeholder="'. _uc($_e['Short Desc']) .'">'.@$service_shrtDesc[$lang[$i]].'</textarea>
</div>
</div>';
}else{ echo '<input type="hidden" name="service_shrtDesc['.$lang[$i].']" value="" class="form-control">';}


//service_service_image
if($developer_service_image=='0'){
$image0 = empty($service_image[$lang[$i]]) ? "" : $this->functions->addWebUrlInLink(@$service_image[$lang[$i]]);
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9 ">
<img src="'.$image0.'" class="service_image_'.$i.' kcFinderImage"/>
</div>
</div>';

echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'.'Image Recommended Size : 320px X 333px'.'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url"  name="service_image['.$lang[$i].']" value="'.$image0.'" class="service_image_'.$i.' form-control" placeholder="'.$_e['Enter Image URL'].'">
<div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('service_image_$i')".'"><i class="glyphicon glyphicon-picture"></i></div>
</div>
</div>
</div>';
}else{ echo '<input type="hidden" name="service_image['.$lang[$i].']" value="" class="form-control">';}



//Enter Note
// if($developer_service_type=='0'){
// echo '<div class="form-group">
// <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Service Note']) .'</label>
// <div class="col-sm-10  col-md-9">
// <input type="text" name="service_type['.$lang[$i].']" value="'.@$service_type[$lang[$i]].'" class="form-control" placeholder="'. _uc($_e['Service Note']) .'">
// </div>
// </div>';
// }else{ echo '<input type="hidden" name="service_type['.$lang[$i].']" value="" class="form-control">';}





//service_service_position
$lay1_Status = intval($developer_service_position);
if($lay1_Status>0){
$image1 = empty($service_position[$lang[$i]]) ? "" : $this->functions->addWebUrlInLink(@$service_position[$lang[$i]]);
if($lay1_Status==3) {
$lay1_Status = '<div class="input-group">
<input type="text"  name="service_position['.$lang[$i].']" value="'.$image1.'" class="service_position_'.$i.' form-control" placeholder="">
<div class="input-group-addon pointer " onclick="'."openKCFinderFile($('.service_position_$i'))".'"><i class="glyphicon glyphicon-file"></i></div>
</div>';
}
else if($lay1_Status==2) {
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9 ">
<img src="' .$image1 . '" class="service_position_'.$i.' kcFinderImage"/>
</div>
</div>';
$lay1_Status = '<div class="input-group">
<input type="text"  name="service_position['.$lang[$i].']" value="'.$image1.'" class="service_position_'.$i.' form-control" placeholder="">
<div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('service_position_$i')".'"><i class="glyphicon glyphicon-picture"></i></div>
</div>';
}else{
$lay1_Status = '
<input type="text"  name="service_position['.$lang[$i].']" value="'.@$service_position[$lang[$i]].'" class="service_position_'.$i.' form-control" placeholder="">';
}

echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Designation']) .' </label>
<div class="col-sm-10  col-md-9">
'.$lay1_Status.'
</div>
</div>';
}else{ echo '<input type="hidden" name="service_position['.$lang[$i].']" value="" class="form-control">';}

//service_service_email
$lay2_Status = intval($developer_service_email);
if($lay2_Status>0){
$image2 = empty($service_email[$lang[$i]]) ? "" : $this->functions->addWebUrlInLink(@$service_email[$lang[$i]]);
if($lay2_Status==3) {
$lay2_Status = '<div class="input-group">
<input type="text"  name="service_email['.$lang[$i].']" value="'.$image2.'" class="service_email_'.$i.' form-control" placeholder="">
<div class="input-group-addon pointer " onclick="'."openKCFinderFile($('.service_email_$i'))".'"><i class="glyphicon glyphicon-file"></i></div>
</div>';
}else if($lay2_Status==2) {
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9 ">
<img src="' .$image2. '" class="service_email_'.$i.' kcFinderImage"/>
</div>
</div>';
$lay2_Status = '<div class="input-group">
<input type="text"  name="service_email['.$lang[$i].']" value="'.$image2.'" class="service_email_'.$i.' form-control" placeholder="">
<div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('service_email_$i')".'"><i class="glyphicon glyphicon-picture"></i></div>
</div>';
}else{
$lay2_Status = '
<input type="text"  name="service_email['.$lang[$i].']" value="'.@$service_email[$lang[$i]].'" class="service_email_'.$i.' form-control" placeholder="">';
}

echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Email']) .'</label>
<div class="col-sm-10  col-md-9">
'.$lay2_Status.'
</div>
</div>';
}else{ echo '<input type="hidden" name="service_email['.$lang[$i].']" value="" class="form-control">';}

//service_service_date
// $lay3_Status = intval($developer_service_date);
// if($lay3_Status>0){
//     $image3 = empty($service_email[$lang[$i]]) ? "" : $this->functions->addWebUrlInLink(@$service_date[$lang[$i]]);
//     if($lay3_Status==3) {
//         $lay3_Status = '<div class="input-group">
//                     <input type="text"  name="service_date['.$lang[$i].']" value="'.$image3.'" class="service_date_'.$i.' form-control" placeholder="">
//                     <div class="input-group-addon pointer " onclick="'."openKCFinderFile($('.service_date_$i'))".'"><i class="glyphicon glyphicon-file"></i></div>
//                 </div>';
//     }else if($lay3_Status==2) {
//         echo '<div class="form-group">
//                 <label class="col-sm-2 col-md-3  control-label"></label>
//                 <div class="col-sm-10  col-md-9 ">
//                     <img src="' .$image3. '" class="service_date_'.$i.' kcFinderImage"/>
//                 </div>
//         </div>';
//         $lay3_Status = '<div class="input-group">
//                     <input type="text"  name="service_date['.$lang[$i].']" value="'.$image3.'" class="service_date_'.$i.' form-control" placeholder="">
//                     <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('service_date_$i')".'"><i class="glyphicon glyphicon-picture"></i></div>
//                 </div>';
//     }else{
//         $lay3_Status = '
//             <input type="text"  name="service_date['.$lang[$i].']" value="'.$image3.'" class="date service_date_'.$i.' form-control" placeholder="">';
//     }

//     echo '<div class="form-group">
//             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Date']) .'</label>
//             <div class="col-sm-10  col-md-9">
//                 '.$lay3_Status.'
//             </div>
//         </div>';
// }else{ echo '<input type="hidden" name="service_date['.$lang[$i].']" value="" class="form-control">';}




echo '           </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div><!-- panel end-->';
}


// service_type
// $developer_service_type = $this->functions->developer_setting('service_type');
// $lay1_Status = intval($developer_service_type);
// if($lay1_Status==1){
//     $service_type = isset($data['type']) ? $data['type'] : '';
//     $image1 = empty($service_type) ? '' : $this->functions->addWebUrlInLink(@$service_type);
//         $lay1_Status = '
//                 <div class="select-group">
//                     <select name="type" class="service_position_'.$i.' form-control" placeholder="" >
//                         <option' .  ( $image1 == "service" ? " selected='' " : "") . ' value="service">' . $this->dbF->hardWords('Board of Directors',false) . '</option>
//                         <option ' . ( $image1 == "executive_committee" ? " selected='' " : "") . 'value="executive_committee">' . $this->dbF->hardWords('Executive Committee',false) . '</option>
//                         <option' .  ( $image1 == "senior_management" ? " selected='' " : "") . ' value="senior_management">' . $this->dbF->hardWords('Senior Management',false) . '</option>
//                         <option' .  ( $image1 == "legal_advisor" ? " selected='' " : "") . ' value="legal_advisor">' . $this->dbF->hardWords('Legal Advisor',false) . '</option>
//                         <option' .  ( $image1 == "shareholding_pattern" ? " selected='' " : "") . ' value="shareholding_pattern">' . $this->dbF->hardWords('Shareholding Pattern',false) . '</option>
//                     </select>      
//                 </div>';

//         echo '<br> <div class="form-group">
//             <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Management type']) .' </label>
//             <div class="col-sm-10  col-md-9">
//                 '.$lay1_Status.'
//             </div>
//         </div>';

// } else { echo '<input type="hidden" name="type" value="" class="form-control">';}
echo '</div> <!-- .accordian end -->';



//Title
// if($this->functions->developer_setting('service_heading')=='0'){
//         echo '<div class="form-group">
//                 <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Service Title']) .'</label>
//                 <div class="col-sm-10  col-md-9">
//                     <input type="text" name="service_heading" value="'.@$data['service_heading'].'" class="form-control" placeholder="'. _uc($_e['Service Title']) .'">
//                 </div>
//             </div>';
//     }else{ echo '<input type="hidden" name="service_heading" value="" class="form-control">';}


//Link
if($this->functions->developer_setting('service_link')=='0'){
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Service Link']) .'</label>
<div class="col-sm-10  col-md-9">





<div class="input-group">
<input type="url" id="service_link"  value="'.@$data['service_link'].'" name="service_link" class="pastLinkHereTwo form-control" placeholder="'. _uc('Service Link') .'">
<div class="input-group-addon linkListTwo pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>



</div>
</div>';
}else{ echo '<input type="hidden" name="service_link" value="" class="form-control">';}

//Publish
$checked = "";
if(@$data['publish']=='1'){$checked='checked';}
echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Publish']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
<input type="checkbox" name="publish" value="1" '.$checked.'>
</div>
</div>
</div>';

//echo '<input type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary"/>';
echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _uc($_e['SAVE']) .'</button>';

echo "</div>
</form>";

     $this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
        $menuC  =   new webMenu();
        $menuC->menuWidgetLinks();

}    


/**
* function returns hardword service type name from type value stored in database
*
* @return void
* @author 
**/
public function get_service_typename($type)
{

switch ($type) {
case 'service':
$type_name = $this->dbF->hardWords('Board of Directors',false);
break;
case 'executive_committee':
$type_name = $this->dbF->hardWords('Executive Committee',false);
break;
case 'senior_management':
$type_name = $this->dbF->hardWords('Senior Management',false);
break;
}

return $type_name;
}
}
?>