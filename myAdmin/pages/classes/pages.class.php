<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class pages extends object_class{
public $productF;
public $imageName;
public $script_js;
// public $webClass;

public function __construct(){
parent::__construct('3');

# saving script
$this->script_js = array();


/**
* MultiLanguage keys Use where echo;
* define this class words and where this class will call
* and define words of file where this class will called
**/
global $_e;
global $adminPanelLanguage;
$_w=array();//homePage.php
$_w['Manage Home Page Content'] = '' ;
$_w['Home Page Boxes'] = '' ;
$_w['Manage'] = '' ;
$_w['Delete Fail Please Try Again.'] = '' ;

//homePageEdit.php
$_w['Update Home Page Box'] = '' ;

//index.php
$_w['Pages Management'] = '' ;
//page.php
$_w['Manage Pages'] = '' ;
$_w['Pages'] = '' ;
$_w['Draft'] = '' ;
$_w['Add New Page'] = '' ;
$_w['UnPublish Pages'] = '' ;
//pageEdit.php
$_w['Update'] = '' ;

//This class
$_w['SNO'] = '' ;
$_w['SLUG'] = '' ;
$_w['TITLE'] = '' ;
$_w['UPDATE'] = '' ;
$_w['ACTION'] = '' ;

$_w['Image File Error'] = '' ;
$_w['Page'] = '' ;
$_w['Page Save Successfully'] = '' ;
$_w['Page Add Successfully'] = '' ;
$_w['Page Save Failed,Please Enter Correct Values, And unique slug'] = '' ;
$_w['Added'] = '' ;
$_w['Update'] = '' ;
$_w['Detail'] = '' ;
$_w['Page Setting'] = '' ;
$_w['Page Detail'] = '' ;

$_w['Title'] = '' ;
$_w['Page Title'] = '' ;
$_w['Sub Title'] = '' ;
$_w['Short Description'] = '' ;
$_w['Enter Short Description'] = '' ;
$_w['Enter Full Detail'] = '' ;
$_w['use : {{inquiryForm}} FOR INQUIRY FORM'] = '' ;
$_w['use : {{feedback}}  FOR FEEDBACK FORM'] = '' ;

$_w['use : {{contactForm}}  FOR CONTACT FORM'] = '' ;
$_w['PageLink'] = '' ;
$_w['Custom Page Slug'] = '' ;
$_w['You Can write Your Custom Page Link,Leave Blank For Default'] = '' ;
$_w['Redirect Link'] = '' ;
$_w['Allow Comment'] = '' ;
$_w['Publish'] = '' ;
$_w['Old Banner Image'] = '' ;
$_w['Page Image <br> Recommened Image Size: 1263 X 555 px'] = '' ;
$_w['SAVE'] = '' ;
$_w['BOX NAME'] = '' ;

$_w['Page Not Found For Update'] = '' ;
$_w['Image'] = '' ;
$_w['Old Image'] = '' ;
$_w['Link'] = '' ;
$_w['Link Text'] = '' ;
$_w['Update Detail'] = '' ;
$_w['Update Box'] = '' ;
$_w['Home Page Box Save Successfully'] = '' ;
$_w['Home Page Box Save Failed'] = '' ;
$_w['Home Page Box'] = '' ;
$_w['Login Required'] = '' ;
$_w['Yes'] = '' ;
$_w['No'] = '' ;
$_w['use : {{employee}} FOR EMPLOYEE PAGE'] = '' ;
$_w['use : {{files-Manager}} FOR FILES MANAGER PAGE'] = '' ;
$_w['use : {{testimonial}} FOR TESTIMONIAL PAGE'] = '' ;

$_w['use : {{albumSingle(AlbumName)}} FOR SINGLE ALBUM (Enter your album name inside ())'] = '' ;
$_w['use : {{albumAll}} FOR ALL ALBUMS'] = '' ;
$_w["use : {{albumPictures(AlbumName)}} FOR ALBUM's ALL IMAGES (Enter your album name inside ())"] = '' ;
$_w['Use Widget In Your Page'] = '' ;
$_w['Close'] = '' ;
$_w['Use Widgets'] = '' ;
$_w['Use Box Widget'] = '' ;
$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Page Management');

}


public function pagesView(){
$sql  = "SELECT page_pk,slug,heading,dateTime FROM pages WHERE publish = '1' ORDER BY page_pk DESC ";
$data =  $this->dbF->getRows($sql);
$this->printViewTable($data);
}

public function pagesDraft(){
$sql  = "SELECT page_pk,slug,heading,dateTime FROM pages WHERE publish = '0'  ORDER BY page_pk DESC ";
$data =  $this->dbF->getRows($sql);
$this->printViewTable($data);
}

private function printViewTable($data){
global $_e;
echo '<div class="table-responsive">
<table class="table table-hover dTable tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>';
$slug = false;
if($this->functions->developer_setting('page_slug')=='1'){
echo '<th>'. _u($_e['SLUG']) .'</th>';
$slug = true;
}
echo '          <th>'. _u($_e['TITLE']) .'</th>
<th>'. _u($_e['UPDATE']) .'</th>
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';
$i = 0;
$defaultLang = $this->functions->AdminDefaultLanguage();
foreach($data as $val){
$i++;
$id = $val['page_pk'];
$heading = unserialize($val['heading']);
$heading = $heading[$defaultLang];
echo "<tr>
<td>$i</td>";
if($slug){
echo "  <td>$val[slug]</td>";
}

$seoLink = '';
if($this->functions->developer_setting('seo') == '1'){
$this->functions->getAdminFile("seo/classes/seo.class.php");
$seoC = new seo();
$seoLink = $seoC->seoQuickLink($id,urlencode("/".$this->db->dataPage."$val[slug]"));
}

echo "  <td>$heading</td>
<td>$val[dateTime]</td>
<td>
<div class='btn-group btn-group-sm'>
$seoLink
<a data-id='$id' href='-".$this->functions->getLinkFolder()."?page=edit&pageId=$id' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>
<a data-id='$id' onclick='deletePage(this);' class='btn'>
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


public function newPageAdd(){
global $_e;
if(isset($_POST['heading']) && isset($_POST['submit'])){
if(!$this->functions->getFormToken('newPage')){return false;}

$heading        = empty($_POST['heading'])     ? ""    : serialize($_POST['heading']);
$sub_heading    = empty($_POST['sub_heading']) ? ""    : serialize($_POST['sub_heading']);
$short_desc     = empty($_POST['short_desc'])  ? ""    : serialize($_POST['short_desc']);
$dsc            = empty($_POST['dsc'])         ? ""    : (serialize($_POST['dsc']));
$slug           = empty($_POST['slug'])        ? ""    : sanitize_slug($_POST['slug']);
$redirect       = empty($_POST['redirect'])    ? ""    : $_POST['redirect'];
$publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
$comment        = empty($_POST['comment'])     ? "0"   : $_POST['comment'];
$file           = empty($_FILES['page_banner']['name'])? false    : true;
$files           = empty($_FILES['banner']['name'])? false    : true;
$special_page   = empty($_POST['special_page'])? "0"   : $_POST['special_page'];
$returnImage    = "";
$returnImages    = "";

$redirect       = str_replace(WEB_URL,'',$redirect);

try{
$this->db->beginTransaction();
if($file){
$returnImage =  $this->functions->uploadSingleImage($_FILES['page_banner'],'pages',$slug);


// $returnImages =  $this->functions->uploadSingleImage($_FILES['banner'],'pages',$slug);




if($returnImage==false){
throw new Exception($_e['Image File Error']);
}
}


if($files){
// $returnImage =  $this->functions->uploadSingleImage($_FILES['page_banner'],'pages',$slug);


$returnImages =  $this->functions->uploadSingleImage($_FILES['banner'],'pages',$slug);




if($returnImages==false){
throw new Exception($_e['Image File Error']);
}
}





$sql      =   "INSERT INTO `pages`
( `heading`, `sub_heading`,
`short_desc`, `dsc`, `redirect`,
`publish`, `comment`,`page_banner`,`banner` 
,`special_page`
) VALUES (?,?,  ?,?,?,   ?,?,? ,?,?)";

$array   = array($heading,$sub_heading,
$short_desc,$dsc,$redirect,
$publish,$comment,$returnImage,$returnImages,$special_page);

$this->dbF->setRow($sql,$array,false);
$lastId  =   $this->dbF->rowLastId;


$sql_slug = "SELECT * FROM pages where slug = '$slug' ";
$data_slug = $this->dbF->getRow($sql_slug);

if($this->dbF->rowCount!=0){
$slug = $slug."-".rand(1, 15);
}


if($slug == ""){
$slug = $this->db->dataPage.$lastId;
}

$sql ="UPDATE `pages` SET slug = ? WHERE page_pk = '$lastId'";
$arry = array($slug);
$this->dbF->setRow($sql,$arry,false);

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Successfully'])),'btn-success');
$this->functions->setlog(_js(_uc($_e['Added'])),_js(_uc($_e['Page'])),$lastId,_js(_uc($_e['Page Save Successfully'])));
}else{
$this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');
}
}catch (Exception $e){
if($returnImage!==false){
$this->functions->deleteOldSingleImage($returnImage);
}
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');
}
} //If end
}




public function PageEditSubmit(){
global $_e;
if(isset($_POST['heading']) && isset($_POST['submit'])){
if(!$this->functions->getFormToken('editPage')){return false;}

$heading        = empty($_POST['heading'])     ? ""    : serialize($_POST['heading']);
$sub_heading    = empty($_POST['sub_heading']) ? ""    : serialize($_POST['sub_heading']);
$short_desc     = empty($_POST['short_desc'])  ? ""    : serialize($_POST['short_desc']);
$dsc            = empty($_POST['dsc'])         ? ""  : (serialize($_POST['dsc']));
$slug           = empty($_POST['slug'])        ? ""    : sanitize_slug($_POST['slug']);
$redirect       = empty($_POST['redirect'])    ? ""    : $_POST['redirect'];
$publish        = empty($_POST['publish'])     ? "0"   : $_POST['publish'];
$comment        = empty($_POST['comment'])     ? "0"   : $_POST['comment'];
$file           = empty($_FILES['page_banner']['name'])? false    : true;
$files           = empty($_FILES['banner']['name'])? false    : true;
$special_page   = empty($_POST['special_page'])? "0"   : $_POST['special_page'];

$oldImg         = empty($_POST['oldImg'])     ? ""   : $_POST['oldImg'];
$returnImage    = $oldImg;

$oldImgs         = empty($_POST['oldImgs'])     ? ""   : $_POST['oldImgs'];
$returnImages    = $oldImgs;

$redirect       = str_replace(WEB_URL,'',$redirect);

try{
$this->db->beginTransaction();

$lastId   =   $_POST['editId'];
if($file){
$this->functions->deleteOldSingleImage($oldImg);
$returnImage = $this->functions->uploadSingleImage($_FILES['page_banner'],'pages',$slug);
// $returnImages = $this->functions->uploadSingleImage($_FILES['banner'],'pages',$slug);
}


if($files){
$this->functions->deleteOldSingleImage($oldImgs);
// $returnImage = $this->functions->uploadSingleImage($_FILES['banner'],'pages',$slug);
$returnImages = $this->functions->uploadSingleImage($_FILES['banner'],'pages',$slug);
}



$sql      =   "UPDATE `pages` SET
`slug`      = ?,
`heading`   =?,
`sub_heading`=?,
`short_desc`=?,
`dsc`       =?,
`redirect`  =?,
`publish`   =?,
`comment`   =?,
`page_banner`=?,
`banner`=?,
`special_page`=?
WHERE page_pk = '$lastId'
";

$array   = array($slug,$heading,$sub_heading,
$short_desc,$dsc,$redirect,
$publish,$comment,$returnImage,$returnImages,$special_page);
$this->dbF->setRow($sql,$array,false);
// $this->functions->setting_fieldsSet($lastId,'pages',false);

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Successfully'])),'btn-success');
$this->functions->setlog(_js(_uc($_e['Update'])),_js(_uc($_e['Page'])),$lastId,_js(_uc($_e['Page Save Successfully'])));
}else{
$this->functions->notificationError(_js(_uc($_e['Page'])),_js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');
}

}catch (Exception $e) {
if ($file && $returnImage !== false) {
$this->functions->deleteOldSingleImage($returnImage);
}
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_js(_uc($_e['Page'])), _js(_uc($_e['Page Save Failed,Please Enter Correct Values, And unique slug'])), 'btn-danger');
}
}
}



public function pageNew(){
$this->pageEdit(true);
return '';
}

public function pageEdit($new = false){
global $_e;
$isEdit = false;
if($new ===true){
$token       = $this->functions->setFormToken('newPage',false);
}else {
//For Edit Page.
$isEdit = true;
$token = $this->functions->setFormToken('editPage', false);
$id = $_GET['pageId'];
$sql = "SELECT * FROM pages where page_pk = '$id' ";
$data = $this->dbF->getRow($sql);

if($this->dbF->rowCount==0){
echo  _uc($_e["Page Not Found For Update"]);
return false;
}

$settingInfo = $this->functions->setting_fieldsGet($id,'pages');
}
//No need to remove any thing,, go in developer setting table and set 0
echo '<form method="post" action="-pages?page=page" class="form-horizontal" role="form" enctype="multipart/form-data">
<input type="hidden" name="editId" value="'.@$id.'"/>'.
$token.
'<div class="form-horizontal">
<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
<li class="active"><a href="#homeDetail" role="tab" data-toggle="tab">'. _uc($_e['Detail']) .'</a></li>
<li><a href="#setting" role="tab" data-toggle="tab">'. _uc($_e['Page Setting']) .'</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
<div class="tab-pane fade in active container-fluid" id="homeDetail">
<h2  class="tab_heading">'. _uc($_e['Page Detail']) .'</h2>
';

$lang = $this->functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}
echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

echo '<div class="panel-group" id="accordion">';
//For Edit
if($isEdit) {
$heading = unserialize($data['heading']);
$sub_heading = unserialize($data['sub_heading']);
$short_desc = unserialize($data['short_desc']);
$dsc = unserialize(($data['dsc']));
}
//For Edit End



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
echo '              <div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Title']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" value="'.@$heading[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Page Title']) .'">
</div>
</div>';

//Sub Heading
if($this->functions->developer_setting('page_subHeading')=='1'){

// CSS for hiding / showing sub heading input
$special_subhead_css = 'displaynones';
if(@$data['special_page']=='1'){$special_subhead_css='';}

echo '          <div class="form-group ' . $special_subhead_css . '" id="short_desc_container">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Sub Title']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text"  value="'.@$sub_heading[$lang[$i]].'" name="sub_heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Sub Title']) .'">
</div>
</div>';
}else{ echo '<input type="hidden" name="sub_heading" value="" class="form-control">';}


//Short Desc
if($this->functions->developer_setting('page_shortDesc')=='1'){
echo '          <div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Description']) .'</label>
<div class="col-sm-10  col-md-9">
<textarea name="short_desc['.$lang[$i].']" class="ckeditor form-control" placeholder="'. _uc($_e['Enter Short Description']) .'" maxlength="500">'.@$short_desc[$lang[$i]].'</textarea>
</div>
</div>';
}else{ echo '<input type="hidden" name="short_desc['.$lang[$i].']" value="" class="form-control">';}

//Desc

echo '            <div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Detail']) .'</label>
<div class="col-sm-10  col-md-9">
<textarea name="dsc['.$lang[$i].']" id="dsc_'.$lang[$i].'" placeholder="'. _uc($_e['Enter Full Detail']) .'" class="ckeditor">'.@$dsc[$lang[$i]].'</textarea>

<a href="#"  class="btn btn-sm btn-info" data-toggle="modal" data-target="#useWidgets">'.$_e['Use Widgets'].'</a>


</div>
</div>';

### onclick=\'lets_do_this'.$i.'("dsc_'.$lang[$i].'")\' 

echo '       </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div>
';





$js_id = 'dsc_'.$lang[$i];
$script_js_id = 'id="dsc_'.$lang[$i].'"';
array_push($this->script_js, $script_js_id);

// CSS for hiding / showing HTML Widget inputs
$special_css = 'displaynone';
if(@$data['special_page']=='1'){$special_css='';}

echo '<br> <fieldset id="box_inputs_fieldset" class="box_inputs_fieldset ' . $special_css . '" >';

## input for image

echo '          <div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc('Box Image') .'
<br>   Recommended size (
for small box 280 X 150 px <br> &
for large box 550 X 380 px )
</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="text" id="box_image_link"  value="" name="box_image_link['.$lang[$i].']" class="form-control box_image_link" placeholder="'. _uc('Enter Box image') .'">
<div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('box_image_link')".'"><i class="glyphicon glyphicon-picture"></i></div>
</div>
</div>
</div>';

## input for link
echo '          <div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc('Box Link') .'</label>
<div class="col-sm-10  col-md-9">



<div class="input-group">
<input type="url" id="box_link" name="box_link['.$lang[$i].']" class="pastLinkHereTwo form-control" placeholder="'. _uc('Enter Box link') .'">
<div class="input-group-addon linkListTwo pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>



</div>
</div>';

## input for text
echo '          <div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc('Box title') .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" id="box_text"  value="" name="box_text['.$lang[$i].']" class="form-control" placeholder="'. _uc('Enter text') .'">
</div>
</div>';

## input for text
echo '          <div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _fu('Box short description: Enter for making a bigger box.') .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" id="box_short_desc"  value="" name="box_short_desc['.$lang[$i].']" class="form-control" placeholder="'. _uc('Enter short description, note box title will not be used.') .'">
</div>
</div>';


echo '<div class="col-sm-4 text-center" >
<span onclick=\'lets_do_this("dsc_'.$lang[$i].'", "'.$lang[$i].'")\' id="insert_template" class="btn btn-sm btn-info" >'.$_e['Use Box Widget'].'</span>
</div>';                               


echo '</fieldset> <!-- end of box_inputs_fieldset --> ';


}



echo '<br>';

// // HTML Widget
// $special_checked = "";
// if(@$data['special_page']=='1'){$special_checked='checked';}
// echo '<div class="form-group" id="special_page_checkbox_form_group">
//             <label  class="col-sm-2 col-md-3  control-label">'. _fu('Turn special page') .'</label>
//             <div class="col-sm-10  col-md-9">
//                 <div id="special_page_make_switch" class="make-switch" data-off="danger" data-on="success" data-on-label="'. _fu('On') .'" data-off-label="'. _fu('Off') .'">
//                     <input id="special_page_checkbox" type="checkbox" name="special_page" value="1" '.$special_checked.'>
//                 </div>
//             </div>
//        </div>';

echo '</div>';
echo '<input type="hidden" name="special_page" value="0">';

echo <<<SCRIPT

<script>
/*
$('#special_page_checkbox_form_group').on('click', '#special_page_make_switch', function(event) {
event.preventDefault();
Act on the event 
console.log(this.id);
console.log("status:"+$('#special_page_checkbox').is(':checked'));
});
*/

$('#special_page_checkbox_form_group').on('change', '#special_page_checkbox', function(event) {
event.preventDefault();
/* Act on the event */
// console.log(this.id);
// console.log("status two:"+$('#special_page_checkbox').is(':checked'));
var checkbox_status = $('#special_page_checkbox').is(':checked');

// save the short_desc status, is it visible or is it showing
var short_desc_container  = $("#short_desc_container");

console.log(short_desc_container);

if ( checkbox_status ) {
$('#box_inputs_fieldset').fadeIn(200);
short_desc_container.fadeIn(200);
} else {
$('#box_inputs_fieldset').fadeOut(200);
short_desc_container.fadeOut(200);
}
});


function lets_do_this(js_id, lang_id) {

// get the calling id
var js_id = arguments[0];

// get the current instance data
var current_data = CKEDITOR.instances[js_id].getData();
console.log(current_data);


// save the image link from input value, will use this later.
var box_image_link = $("#box_image_link").val();
// save the url from input value, will use this later.
var box_link   = $("#box_link").val();
// save the box_text from input value, will use this later.
var box_text   = $("#box_text").val();
// save the box_short_desc from input value, will use this later.
var box_short_desc  = ( $("#box_short_desc").val() === '' ) ? false : $("#box_short_desc").val() ;



// box html
var html =  "<div class='p_box'>" +
"<div class='p_box_img'>" +
"<a href='" + box_link + "'><img src='" + box_image_link + "' alt='" + box_text + "' class='img-full'>" + 
"<div class='masked_text'>" + box_text + "</div>" + 
"</a>" + 
"</div>" + 
"<div class='p_text'><a href='" + box_link + "' class='text-blue'>" + box_text + "</a></div>" +
"</div><!--p_box end-->";

// *** overriding html if short description is entered ***
if( box_short_desc != false ) {

var html =  "<div class='left_welcome_area transition_3'>" +
"<a href='" + box_link + "' class='display-block'><img src='" + box_image_link + "' alt='" + box_short_desc + "' class='grow low-opacity transition_3'></a>" + 
"<div class='private_text'><h4 class=''>" + box_short_desc + "</h4>" + 
"<a href='" + box_link + "' class='discover_btn_pos btn_common bg-green transition_3'>Discover More</a>" + 
"</div>" + 
"</div><!--left_welcome_area end-->"; 

}

// save current data
var current_data = CKEDITOR.instances[js_id].setData(current_data + html);


}

</script>

SCRIPT;


// echo '<script>';
// // var_dump($this->script_js);
// echo 'console.log($("$this->script_js").html());';
// echo '</script>';


echo '</div> <!-- home Tab End -->
<div class="tab-pane fade in container-fluid" id="setting">
<h2  class="tab_heading">'. _uc($_e['Page Setting']) .'</h2>
';

if($isEdit) {
## Correction point 1 //pageLink 
$link = WEB_URL . '/' . $this->db->dataPage . $data['slug'];
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['PageLink']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" value="' . $link . '" class="form-control" placeholder="'. _uc($_e['PageLink']) .'" readonly>
</div>
</div>';
}
//Slug
if($this->functions->developer_setting('page_slug')=='1'){
echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Custom Page Slug']) .'</label>
<div class="col-sm-10  col-md-9">
<input  pattern="[A-Za-z0-9-_]{1,150}" type="text" name="slug" class="form-control" value="'.@$data['slug'].'" placeholder="'. _uc($_e['You Can write Your Custom Page Link,Leave Blank For Default']) .'">
</div>
</div>';
}else{ echo '<input type="hidden" name="slug" value="'.@$data['slug'].'" class="form-control">';}

//Redirect
//Link
@$link = $data['redirect'];
if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){

}else if($link!=''){
$link = WEB_URL.$link;
}
// echo '<div class="form-group">
//             <label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Redirect Link']) .'</label>
//             <div class="col-sm-10  col-md-9">
//                 <div class="input-group">
//                     <input type="text" name="redirect" value="'.$link.'" class="pastLinkHere form-control" placeholder="http://www.google.com">
//                     <div class="input-group-addon linkList pointer"><i class="glyphicon glyphicon-search"></i></div>
//                 </div>
//             </div>
//        </div>';

// echo '<div class="form-group">
//             <label class="col-sm-2 col-md-3   control-label" >'.$_e['Login Required'].'</label>

//             <div class="col-sm-10 col-md-9">
//                 <label class="radio-inline">
//                     <input type="radio" class="loginReq" name="setting_f[loginReq]" value="1">'.$_e['Yes'].'
//                 </label>
//                 <label class="radio-inline">
//                     <input type="radio" class="loginReq" name="setting_f[loginReq]" value="0">'.$_e['No'].'
//                 </label>
//             </div>
//         </div>
//         <script>
//         $(document).ready(function(){
//             $(".loginReq[value=\''.@strtolower($this->functions->setting_fieldsArray($settingInfo,'loginReq')).'\']").attr("checked", true);
//         });

//         </script>';

/*echo '
<div class="form-group">
<label class="col-sm-2 col-md-3  control-label"></label>
<div class="col-sm-10  col-md-9 ">
<img src="" class="pageIcon kcFinderImage"/>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 col-md-3   control-label" >'.$_e['Login Required'].'</label>

<div class="col-sm-10 col-md-9">
<div class="input-group">
<input type="text" class="pageIcon form-control" name="setting_f[icon]" value="'.@($this->functions->setting_fieldsArray($settingInfo,'icon')).'">
<div class="input-group-addon  pointer"  onclick="'."openKCFinderImageWithImg('pageIcon')".'"><i class="glyphicon glyphicon-picture"></i></div>
</div>
</div>
</div>';*/



//Comment
if($this->functions->developer_setting('page_comment')=='1'){
$checked = "";
if(@$data['comment']=='1'){$checked='checked';}
echo '<div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Allow Comment']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="make-switch" data-off="warning" data-on="success">
<input type="checkbox" name="comment" value="1" '.$checked.'>
</div>
</div>
</div>';
}else{ echo '<input type="hidden" name="comment" value="0" class="form-control">';}

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
echo "<hr>";
echo "<hr>";
echo "<hr>";

//Banner
if($this->functions->developer_setting('pageBanner')=='1'){
$img = "";
if(@$data['page_banner']!=''  && $isEdit){
$img=$data['page_banner'];
echo "<input type='hidden' name='oldImg' value='$img' />";
echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Old Banner Image']) .'</label>
<div class="col-sm-10  col-md-9">
<img src="../images/'.$img.'" style="max-height:250px;" >
</div>
</div>';
}






echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($this->dbF->hardWords('Recommened Banner Image Size: 1370 x 546 px', false)) .' </label><br>
<div class="col-sm-10  col-md-9">
<input type="file" name="page_banner" class="btn-file btn btn-primary">
</div>
</div>';
}else{ echo '<input type="hidden" name="page_banner" value="" class="form-control">';}


echo "<hr>";
echo "<hr>";
echo "<hr>";




// if($this->functions->developer_setting('pageBanner')=='1'){
// $imgs = "";
// if(@$data['banner']!=''  && $isEdit){
// $imgs=$data['banner'];
// echo "<input type='hidden' name='oldImgs' value='$imgs' />";
// echo '<div class="form-group">
// <label  class="col-sm-2 col-md-3  control-label">Old Banner Image</label>
// <div class="col-sm-10  col-md-9">
// <img src="../images/'.$imgs.'" style="max-height:250px;" >
// </div>
// </div>';
// }
// echo '<div class="form-group">
// <label  class="col-sm-2 col-md-3  control-label">'. _uc($this->dbF->hardWords('Recommened Content Image Size: 450 x 300 px', false)) .' </label><br>
// <div class="col-sm-10  col-md-9">
// <input type="file" name="banner" class="btn-file btn btn-primary">
// </div>
// </div>';
// }else{ echo '<input type="hidden" name="banner" value="" class="form-control">';}




echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

echo "</div><!-- setting tabs end -->
</div> <!-- tab-content end -->
</div> <!-- container end -->
</form>";

$this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
$menuC  =   new webMenu();
$menuC->menuWidgetLinks();


$employeePage = $this->functions->developer_setting('employeePage');
$filesManagerPage = $this->functions->developer_setting('filesManagerPage');
$testimonialPage = $this->functions->developer_setting('testimonialPage');
$galleryPage = $this->functions->developer_setting('hasGalleryPage');

$employeeFormat = '';
if($employeePage == '1'){
$employeeFormat = "<tr><td>"._n($_e['use : {{employee}} FOR EMPLOYEE PAGE']) ."</td></tr>";
}

$filesManagerFormat = '';
if($filesManagerPage == '1'){
$filesManagerFormat = "<tr><td>"._n($_e['use : {{files-Manager}} FOR FILES MANAGER PAGE']) ."</td></tr>";
}
$testimonialFormat = '';
if($testimonialPage == '1'){
$testimonialFormat = "<tr><td>". _n($_e['use : {{testimonial}} FOR TESTIMONIAL PAGE']) ."</td></tr>";
}
$galleryPageFormat = '';
if($galleryPage == '1'){
$galleryPageFormat .= "<tr><td>"._n($_e['use : {{albumAll}} FOR ALL ALBUMS']) ."</td></tr>";
// $galleryPageFormat .= "<tr><td>"._n($_e['use : {{albumSingle(AlbumName)}} FOR SINGLE ALBUM (Enter your album name inside ())']) ."</td></tr>";
// $galleryPageFormat .= "<tr><td>"._n($_e["use : {{albumPictures(AlbumName)}} FOR ALBUM's ALL IMAGES (Enter your album name inside ())"]) ."</td></tr>";


}





// $packages= $this->get_categoryfromTbl();



// var_dump($packages);


$packagess = '';

// for ($i=0; $i < count($packages); $i++) { 
// foreach ( $packages as $field ){ 

// // print_r($packages[$i]);


// $packagess .= "<tr><td>"._n('use : {{Print::'.$field['category'].'}} FOR '.$field['category'].' Print Projects') ."</td></tr>";

//     # code...
// }





$useWidget = '<table class="table table-striped table-hover">
<tr><td>'. _n($_e['use : {{contactForm}}  FOR CONTACT FORM']) .'</td></tr>
<tr><td>'. _n('use : {{All_Faq}}  FOR Print All FAQs Question Answer') .'</td></tr>

</table>
';

echo $this->functions->blankModal($_e["Use Widget In Your Page"],"useWidgets",$useWidget,$_e['Close']);

} //function end


public function homePageBoxView(){
global $_e;
echo '<div class="table-responsive">
<script>$(document).ready(function(){dTableT()});</script>
<table class="table table-hover dTableT tableIBMS">
<thead>
<th>'. _u($_e['SNO']) .'</th>
<th>'. _u($_e['BOX NAME']) .'</th>
<th>'. _u($_e['TITLE']) .'</th>
<th>'. _u($_e['ACTION']) .'</th>
</thead>
<tbody>';

$sql  = "SELECT box,id,heading FROM box WHERE publish='1' ORDER BY id ASC ";
$data =  $this->dbF->getRows($sql);
$i = 0;
$defaultLang = $this->functions->AdminDefaultLanguage();
foreach($data as $val){
$i++;
$id = $val['id'];
@$heading = unserialize($val['heading']);
if($heading===false){
$heading = $val['heading'];
}else{
@$heading = $heading[$defaultLang];
}

echo "<tr>
<td>$i</td>";
echo "  <td>$val[box]</td>";
echo "  <td>$heading</td>
<td>
<div class='btn-group btn-group-sm'>
<a data-id='$id' href='-".$this->functions->getLinkFolder()."?page=homePageEdit&pageId=$id' class='btn'>
<i class='glyphicon glyphicon-edit'></i>
</a>
</div>
</td>
</tr>";
}


echo '</tbody>
</table>
</div> <!-- .table-responsive End -->';
}



public function homePageBoxEdit(){
global $_e;
$token  =   $this->functions->setFormToken('homePageBoxEdit',false);
$id     =   $_GET['pageId'];
$sql    =   "SELECT * FROM box where id = '$id' AND publish = '1'";
$data   =   $this->dbF->getRow($sql);
if($this->dbF->rowCount==0){
echo "Box Page Not Found For Update";
return false;
}

$boxName = $data['box'];
$sql     =   "SELECT * FROM box_setting where box = '$boxName' ";
$box_setting  =   $this->dbF->getRow($sql);
if($this->dbF->rowCount==0){
echo "Box Setting Not Found";
return false;
}


//No need to remove any thing,, go in developer setting table and set 0
echo '<form method="post" action="-'.$this->functions->getLinkFolder().'?page=homePage" class="form-horizontal" role="form" enctype="multipart/form-data">
<input type="hidden" name="editId" value="'.$id.'"/>
'.$token.
'<div class="form-horizontal col-sm-12">
<h2  class="tab_heading ">'. _uc($_e['Update Detail']) .'</h2>
';

$lang = $this->functions->IbmsLanguages();
if($lang != false){
$lang_nonArray = implode(',', $lang);
}
echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';

echo '<div class="panel-group" id="accordion">';

@$heading = unserialize($data['heading']);
@$sub_heading   =  unserialize($data['sub_heading']);
@$short_desc    =  unserialize($data['short_desc']);
@$linkText      =  unserialize($data['linktext']);

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
echo '              <div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['TITLE']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text" value="'.$heading[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['TITLE']) .'">
</div>
</div>';

//Sub Heading
if($box_setting['sub_heading']=='1'){
echo '          <div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Sub Title']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text"  value="'.$sub_heading[$lang[$i]].'" name="sub_heading['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Sub Title']) .'">
</div>
</div>';
}else{ echo '<input type="hidden" name="sub_heading" value="" class="form-control">';}


//Short Desc
if($box_setting['short_desc']=='1'){
$editor = '';
if($box_setting['editor']=='1'){
$editor = 'ckeditor';
}

echo '          <div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Short Description']) .'</label>
<div class="col-sm-10  col-md-9">
<textarea name="short_desc['.$lang[$i].']" class="form-control '.$editor.'" placeholder="'. _uc($_e['Enter Short Description']) .'" maxlength="500">'.$short_desc[$lang[$i]].'</textarea>
</div>
</div>';
}else{ echo '<input type="hidden" name="short_desc" value="" class="form-control">';}

//linkText
if($box_setting['linktext']=='1'){
echo '            <div class="form-group">
<label class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link Text']) .'</label>
<div class="col-sm-10  col-md-9">
<input type="text"  value="'.$linkText[$lang[$i]].'" name="linkText['.$lang[$i].']" class="form-control" placeholder="'. _uc($_e['Link Text']) .'">
</div>
</div>';
}else{ echo '<input type="hidden" name="linkText" value="" class="form-control">';}

echo '       </div> <!-- panel-body end -->
</div> <!-- collapse end-->
</div><!--.Panel end-->
';
}
echo '</div><!--.accordion end--> <hr><hr>';

//Redirect
if($box_setting['redirect']=='1'){
//Link
$link = $data['redirect'];
if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){

}else if($link!=''){
$link = WEB_URL.$link;
}
echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Link']) .'</label>
<div class="col-sm-10  col-md-9">
<div class="input-group">
<input type="url" name="redirect" value="'.$link.'" class="pastLinkHere form-control" placeholder="http://www.google.com">
<div class="input-group-addon linkList pointer"><i class="glyphicon glyphicon-search"></i></div>
</div>
</div>
</div>';
}else{ echo '<input type="hidden" name="redirect" value="" class="form-control">';}

//Banner
if($box_setting['image']=='1'){


$this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
$menuC  =   new webMenu();
$menuC->menuWidgetLinks();


$boxes = array( 
'box1'  => ' Recommended size ( 1420 X 400 px ) ',
'box4'  => ' Recommended size ( 578 X 280 px ) ', 
// 'box5'  => ' Recommended size ( 5366 X 3577 px ) ', 
// 'box6'  => ' Recommended size ( 1200 X 800 px ) ', 
// 'box7' => ' Recommended size ( 3840 X 2160 px ) ',
// 'box8' => ' Recommended size ( 2048 X 1365 px ) ',
'box9' => ' Recommended size ( 801 X 276 px ) ',
'box11' => ' Recommended size ( 1400 X 800 px ) ',
'box13' => ' Recommended size ( 1420 X 625 px ) ',
'box12' => ' Recommended size ( 1420 X 625 px ) '
);

$recommended_size = isset($boxes[$data['box']]) ? $boxes[$data['box']] : '';

$img = "";
if($data['image']!=''){
$img=$data['image'];
echo "<input type='hidden' name='oldImg' value='$img' />";
echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Old Image']) .'</label>
<div class="col-sm-10  col-md-9">
<img src="../images/'.$img.'" style="max-height:250px;" >
</div>
</div>';
}

echo '<div class="form-group">
<label  class="col-sm-2 col-md-3  control-label">'. _uc($_e['Image']) . $recommended_size .'</label>
<div class="col-sm-10  col-md-9">
<input type="file" name="image" class="btn-file btn btn-primary">
</div>
</div>';
}else{ echo '<input type="hidden" name="image" value="" class="form-control">';}

echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

echo "</div><!-- form-horizontal end -->

</form>";

$this->functions->includeOnceCustom(ADMIN_FOLDER."/menu/classes/menu.class.php");
$menuC  =   new webMenu();
$menuC->menuWidgetLinks();
} //function end

public function get_categoryfromTbl(){
return $this->dbF->getRows('SELECT `category` FROM `products` WHERE `publish` = 1 and `product_update` = 1 group by category'); 
}
public function homePageBoxEditSubmit(){
global $_e;

if(isset($_POST['heading']) && isset($_POST['submit'])){
if(!$this->functions->getFormToken('homePageBoxEdit')){return false;}

$heading        = empty($_POST['heading'])     ? ""    : serialize($_POST['heading']);
$sub_heading    = empty($_POST['sub_heading']) ? ""    : serialize($_POST['sub_heading']);
$short_desc     = empty($_POST['short_desc'])  ? ""    : serialize($_POST['short_desc']);
$linkText       = empty($_POST['linkText'])    ? ""    : serialize($_POST['linkText']);
$redirect       = empty($_POST['redirect'])    ? ""    : $_POST['redirect'];
$file           = empty($_FILES['image']['name'])? false    : true;

$redirect       = str_replace(WEB_URL,'',$redirect);


$oldImg         = empty($_POST['oldImg'])     ? ""   : $_POST['oldImg'];
$returnImage    = $oldImg;

try{
$this->db->beginTransaction();

$lastId   =   $_POST['editId'];
if($file){
$this->functions->deleteOldSingleImage($oldImg);
$returnImage = $this->functions->uploadSingleImage($_FILES['image'],'box');
}
$sql      =   "UPDATE `box` SET
`heading`   =?,
`sub_heading`=?,
`short_desc`=?,
`linktext`       =?,
`redirect`  =?,
`image`=?
WHERE id = '$lastId'
";

$array   = array($heading,$sub_heading,
$short_desc,$linkText,$redirect,$returnImage);
$this->dbF->setRow($sql,$array,false);

$this->db->commit();
if($this->dbF->rowCount>0){
$this->functions->notificationError(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box Save Successfully'])),'btn-success');
$this->functions->setlog(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box'])),$lastId,_js(_uc($_e['Home Page Box Save Successfully'])));
}else{
$this->functions->notificationError(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box Save Failed'])),'btn-danger');
}

}catch (Exception $e){
if($file && $returnImage!==false){
$this->functions->deleteOldSingleImage($returnImage);
}
$this->db->rollBack();
$this->dbF->error_submit($e);
$this->functions->notificationError(_js(_uc($_e['Update Box'])),_js(_uc($_e['Home Page Box Save Failed'])),'btn-danger');
}
}
}


}
?>