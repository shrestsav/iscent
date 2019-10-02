<?php



class web_gallery extends  object_class

{

public $webClass;

public $galleryC;

function __construct()

{

parent::__construct('3');

$this->webClass = $GLOBALS['webClass'];



//$this->functions->includeOnceCustom(ADMIN_FOLDER."/gallery/classes/gallery.class.php");

//$this->galleryC = new gallery();

/**

* MultiLanguage keys Use where echo;

* define this class words and where this class will call

* and define words of file where this class will called

**/

global $_e;

$_w=array();

// $_e    =   $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'Website Gallery');



}



public function albumPage($desc1){

//{{albumAll}}

//var_dump($_GET);

?>

<!-- <script>alert("albumPage");</script> -->

<?php

if($this->functions->developer_setting('hasGalleryPage') == '1') {





if(isset($_GET['g'])){





$desc1 = $this->album_new($desc1);



} elseif (preg_match("@{{albumAll}}@i", $desc1)) {



$gallery = $this->gallerySimple();

$desc1 = str_replace('{{albumAll}}', $gallery, $desc1);



}





//{{album(albumName)}}

//$desc1 = $this->albumSinglePage($desc1);



//{{albumPictures(albumName)}}

// $desc1 = $this->albumPicturesPage($desc1);

}



return $desc1;

}



private function albumPicturesPage($desc1){

if(preg_match("@{{albumPictures(.*)}}@i",$desc1) || isset($_GET['g'])) {



//get album name

$name1       =  $this->functions->get_string_between($desc1,"{{albumPictures(",")}}");

$name       =   trim(strip_tags($name1));

if(isset($_GET['g'])){

$name       = $_GET['g'];



}

$gallery    =   $this->album_new($name,true);

$desc1      = str_replace("{{albumPictures($name1)}}", "$gallery", $desc1);

if(preg_match("@{{albumPictures(.*)}}@i",$desc1)) {

$desc1  = $this->albumPicturesPage($desc1);

}

}



return $desc1;

}



public function album_new($desc1){

//echo $_GET['gallery-page'];

//var_dump($desc1);

$alb = $_GET['g'];

$html   = "<div class='section4'>
<div class='standard'>
<div class='heading'>
<h4>$alb</h4>
</div>
<div class='main_col'>";



$sql ="SELECT * FROM `gallery_images` INNER JOIN `gallery` ON gallery_images.gallery_id = gallery.gallery_pk WHERE gallery.album = ? ORDER BY gallery_images.sort ASC";

$data = $this->dbF->getRows($sql,array($_GET['g']));

//echo "<pre>"; print_r($data);

foreach ($data as $val) {  

$image     =  $val['image'];

$id        =  $val['gallery_pk'];

$imageLink =  WEB_URL."/images/".$image;

$alt    =   $val['alt'];

$html .= "
<div class='inner_col wow zoomIn'>
<a href='$imageLink' rel='gallery' class='galleryFancyBox_$id' title='$alt'>
<div class='inner_col_box'>
<img src='$imageLink' title='$alt'  alt='$alt'>
<div class='inner_content'>
<h3>$alt</h3>
</div>
</div>
</a>
</div>
";                    

}



$html .= "</div></div></div>
<script>

$(document).ready(function() {

$('.galleryFancyBox_$id').fancybox();

});

</script>";

// var_dump($desc1);

$desc1      = str_replace("{{albumAll}}", "$html", $desc1);

//  var_dump($desc1);

return $desc1;

}







private function albumSinglePage($desc1){

if(preg_match("@{{albumSingle(.*)}}@i",$desc1)) {

//get album name

$name1       =  $this->functions->get_string_between($desc1,"{{albumSingle(",")}}");

$name       =   trim(strip_tags($name1));

$gallery    = $this->gallerySimple($name);

$desc1      = str_replace("{{albumSingle($name1)}}", "$gallery", $desc1);

if(preg_match("@{{albumSingle(.*)}}@i",$desc1)) {

$desc1  = $this->albumSinglePage($desc1);

}

}



return $desc1;

}



public function galleryMain($notIncludeFirstImageInInner=true,$galleryName = ''){

if(!empty($galleryName)){

$galleryName = " AND album = '$galleryName'";

}

$sql ="SELECT * FROM `gallery` WHERE publish = '1' $galleryName ORDER BY sort ASC";

$data = $this->dbF->getRows($sql);

if(empty($data)){

return "";

}

foreach($data as $key=>$val){

$id     = $val['gallery_pk'];



$qry="SELECT * FROM  `gallery_images` WHERE `gallery_id` = '$id' ORDER BY sort ASC";

$eData=$this->dbF->getRows($qry);



if($this->dbF->rowCount>0){

$first = true;

foreach($eData as $key2=>$val2) {

$img    = $val2['image'];

$imgId  = $val2['img_pk'];

$alt    = $val2['alt'];



if(empty($img)){

continue;

}



if($first===true && $notIncludeFirstImageInInner===false){



}else {

$data[$key]['images'][$key2]['image'] = $img;

$data[$key]['images'][$key2]['imageId'] = $imgId;

$data[$key]['images'][$key2]['alt'] = $alt;

}



if($first){

$first = false;

$data[$key]['image'] = $img;

$data[$key]['imageId'] =  $imgId;

$data[$key]['alt'] = $alt;

}

}

}else{

unset($data[$key]);

}

}

return $data;

}



public function gallerySimple($galleryName = '',$all=false){
//$all show all images not album
$temp   = "<div class='section4'>
<div class='standard'>
<div class='main_col'>";
$gallery        = $this->galleryMain(false,$galleryName);
// var_dump($gallery);
if(empty($gallery)){
return "";
}
foreach($gallery as $val){
$id    = $val['gallery_pk'];
$album = $val['album'];
$rel = "<div style='display:none'>";
$relAll = "";
if(isset($val['images'])) {
foreach ($val['images'] as $val2) {
$id2     = $val2['imageId'];
$imageR = WEB_URL."/images/".$val2['image'];
$image  = $this->functions->resizeImage($val2['image'],'220','170',false);
$alt    = $val2['alt'];
if($all) {
$relAll    .= "


<div class='inner_col wow zoomIn'>
<a href='$imageR' rel='gallery' class='galleryFancyBox_$id' title='$alt'>
<div class='inner_col_box'>
<img src='$image' title='$alt'  alt='$alt'>
<div class='inner_content'>
<h3>$alt</h3>
</div>
</div>
</a>
</div>











";
}
$rel    .= "<a href='$imageR' title='$alt' ></a>";
}
}
$rel    .= "</div>";
$imageR = $val['image'];
$image  = $this->functions->resizeImage($imageR,'220','170',false);
$alt    =   $val['alt'];
$imageR = WEB_URL."/page-picture-gallery&g=$album";
$temp .= " 
<div class='inner_col wow zoomIn' id='gallery_$id'>
<a href='$imageR' title='$alt'>
<div class='inner_col_box'>
<img src='$image' title='$alt'  alt='$alt'>
<div class='inner_content'>
<h3>$album</h3>
</div>
</div>
</a>
</div>
















$relAll";
$temp .= "
<script>
$(document).ready(function() {
$('.galleryFancyBox_$id').fancybox();
});
</script>";
}
$temp .= "
</div>
</div>
</div>

";
return $temp;
}



public function gallerySimpleTwo($galleryName = '',$all=false){

//$all show all images not album

$temp   = " <div class='template-grid look-book'>";

$gallery        = $this->galleryMain(false,$galleryName);

//var_dump($gallery);

if(empty($gallery)){

return "";

}

$count = 0;

foreach($gallery as $val){

$id          = $val['gallery_pk'];

$album_name  = $val['album'];





$rel = "<div style='display:none'>";

$relAll = "";

if(isset($val['images'])) {

foreach ($val['images'] as $val2) {

$id2     = $val2['imageId'];

$imageR  = WEB_URL."/images/".$val2['image'];

$image   = $this->functions->resizeImage($val2['image'],'340','470',false);

$alt     = $val2['alt'];

//                     if($all) {



//                 $relAll    .= <<<HTML



//                 <div class="gallerySingle item item-product grid-item" id='gallery_$id2'> 

//                      <a class="btn-product galleryFancyBox_$id" href="$imageR" rel='gallery' title='{$alt}'> 

//                        <span class="loader-context wow fadeInUp" data-wow-delay="0.2s"> 

//                            <span class="product-images"> 

//                                 <img src="{$image}" alt="{$alt}" title='{$alt}' class="loader-load shrink"> 

//                            </span> 

//                            <span class="product-details"> 

//                                 <span class="product-name">{$alt}</span> 

//                            </span> 

//                        </span> 

//                      </a> 

//                </div><!--item-product end-->



// HTML;



//                     }



$rel    .= "<a href='$imageR'  rel='fancy_box' class='galleryFancyBox_$id' title='$alt' ></a>";



$count++;



}

}

$rel    .= "</div>";



$imageR = $val['image'];

$image  = $this->functions->resizeImage($imageR,'340','470',false);

$alt    =   $val['alt'];

$imageR = WEB_URL."/images/".$imageR;



$temp .= <<<HTML



<div class="images gallery_image"><!-- products -->

<div class="img_block">

<a href="{$imageR}" class="galleryFancyBox_{$id} fancy_box" rel="fancy_box" >

<img class="full_image" src="{$image}" alt="{$alt}">



</a>

</div>

<div class="gallery_name"> <a href="{$imageR}" class="galleryFancyBox_{$id} fancy_box" rel="fancy_box" >{$album_name}</a></div>



</div><!-- products End -->



<!-- <div class="gallerySingle item item-product grid-item" id='gallery_$id'> 

<a class="btn-product galleryFancyBox_$id" href="$imageR" rel='gallery' title='{$alt}'> 

<span class="loader-context wow fadeInUp" data-wow-delay="0.2s"> 

<span class="product-images"> 

<img src="{$image}" alt="{$alt}" title='{$alt}' class="loader-load shrink"> 

</span> 

<span class="product-details"> 

<span class="product-name">{$album_name}</span> 

</span> 

</span> 

</a> 

</div> item-product end -->

{$rel}

{$relAll}

HTML;





$temp .= <<<HTML



<script>

$(document).ready(function() {

$('.galleryFancyBox_$id').fancybox();

});

</script>

HTML;

}





$temp .= " </div> <!-- template-grid END --> ";



return $temp;

}





public function galleryAlbum($album_limit=4){

if(!empty($galleryName)){

$galleryName = " AND album = '$galleryName'";

}

$sql ="SELECT * FROM `gallery` WHERE publish = '1' ORDER BY RAND() LIMIT {$album_limit}";

$data = $this->dbF->getRows($sql);

if(empty($data)){

return "";

}

$html='';

foreach($data as $key=>$val){

$id     = $val['gallery_pk'];

$album     = $val['album'];

$qry="SELECT * FROM  `gallery_images` WHERE `gallery_id` = '$id' ORDER BY sort ASC LIMIT 1";

$eData=$this->dbF->getRow($qry);



if($this->dbF->rowCount>0){



// foreach($eData as $key2=>$val2) {

$img    = $eData['image'];

$img_link    = WEB_URL.'/images/'.$eData['image'];

// $page_link    = WEB_URL.'/page-picture-gallery';
$page_link    = WEB_URL.'/page-picture-gallery&page-picture-gallery='.$album.'';
$imgId  = $eData['img_pk'];

$alt    = $eData['alt'];



if(empty($img)){

continue;

}



$html .='<div class="col5_right_box"> <a href="'.$page_link.'"><img src="'.$img_link.'" alt="" class="hvr-grow"></a> </div>';





// }

}

}

return $html;

}



}









?>

<style>

.gallerySingle{

/*width: 29%;*/

display: inline-block;

vertical-align: top;

margin: 5px 8px;





}

.gallery_image{

width: 24%;

display: inline-block;

vertical-align: top;

margin-top: 20px;

}

.gallery_name{

font-size: 20px;

font-family: 'open_sanslight';

border-bottom: 1px solid #bd141b;

height: 40px;

line-height: 40px;

color: #000000;

}

.gallery_name a{ 

display:block;

color: #000000;

}



.gallery_box{

width: 100%;

border: 1px solid #ddd;

padding: 4px 2px;

box-sizing: border-box;

box-shadow: 0px 0px 8px #ddd;

border-radius: 4px;

height: 166px;

}



.gallery_box img{

max-width: 100%;

height: 100%;

}



.album_name{

font-size: 14px;

color:#0a85c1 !important;

font-family: 'Helvetica Neue LT Std Lt';

text-shadow: 0 0 0 #0a85c1;

text-align: left  !important;

}

.album_name a{

font-size: 14px;

color:#0a85c1 !important;

font-family: 'Helvetica Neue LT Std Lt';

text-shadow: 0 0 0 #0a85c1;

}

.look-book{

text-align:center;}



.thumbnail{

border: none !important;

margin-bottom: 0px !important;

box-shadow: none !important;



}

.galleryMain{

padding:0 !important;

margin:0 !important;

}

.about_inside {



text-align: inherit;

}



</style>

