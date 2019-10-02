<?php 
ob_start();
include_once("global.php");
global $webClass;
//var_dump($_GET['news_page']);

?>

<?php

if(!isset($_GET['news-page'])){

echo '<script>
$(function() {
$( "#accordion_news" ).accordion({
heightStyle: "content"
});
});
</script>

<style>
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
  
    background: #e8e61f !important;
   
}
</style>



<div id="accordion_news" class="container-fluid news_type_detail">';
$sql="SELECT * from news where publish = 1 ORDER BY `date` DESC";
$data = $dbF->getRows($sql);
foreach ($data as $key => $value) {

$id=$value['id'];
$heading = translatefromserialize($value['heading']);
$desc = translatefromserialize($value['shortDesc']);

$date = date('d/m/y',strtotime($value['date']));
// $image = WEB_URL.'/images/'.$value['image'];

// date('Y-m-d',strtotime($date));


// $datetime = strtotime($date);
// $month = date("M", $datetime);
// $year = date("Y", $datetime);
// $day = date("D", $datetime);

$link = WEB_URL."/page-current-news&news-page=$id";

echo "<h4>($date) $heading </h4>
<div class='newCollapse'>
<p>{$desc}</p><br />
<div class='bttn2' style=''>
<a href='$link' class=''>READ MORE</a>
</div>
</div>";
}

echo "</div>";
}
else{
$data_=array();
$getid = $_GET['news-page'];
$sql_="SELECT * from news where id={$getid}";
$data_=$dbF->getRow($sql_);
//echo "<pre>"; print_r($data_);
$heading_ = translatefromserialize($data_['heading']);
$desc_ = translatefromserialize($data_['shortDesc']);
$large = translateFromSerialize(base64_decode($data_['dsc']));
$date_ = date('d/m/y',strtotime($data_['date']));

$img = ($data_['image']);



$datetime_ = strtotime($date_);
$month_ = date("M", $datetime_);
$year_ = date("Y", $datetime_);
$day_ = date("D", $datetime_);

echo "



<div class='about'>
<div class='standard'>
<div class='about_col1'>
<h2>$heading_ [{$date_}]</h2>


<div class='about_col1_txt'>

  {$desc_}

  <hr>
  {$large}
</div>
</div>






<div class='about_col2'>
<img src='images/$img' alt='' />
</div>




</div>
</div>




";



}


return ob_get_clean();
?>


<style>
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
  
    background: #e8e61f !important;
   
}
.ui-widget-content a {
color: #fff !important;
float: right;
}

</style>




