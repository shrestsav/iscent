<?php 
include("global.php");
global $webClass;

if(!isset($_GET['page']) || $_GET['page']==''){
	header("HTTP/1.0 404 Not Found");
}

//var_dump($seo);

$pg         = $_GET['page'];
$page       = $webClass->getPage("$pg");

if(empty($page)){
		http_response_code(404);
		include('page_404.php'); 
		die();
}
$pg_id      = $page['id'];
// $setting_field  = $functions->setting_fieldsGet($pg_id,'pages');
$contact = false;
$inquiry = false;
$news = false;
$career = false;
$products = false;
$all_faq = false;
$gall = false;
// echo '<pre>'; print_r($page); echo '</pre>'; 
//Redirect If link
$redirectLink = $page['link'];
if($redirectLink!=''){
	header("Location: $redirectLink");
	exit;
}

global $seo;
if($seo['title']==''  || $seo['reWriteTitle']=='0'){
	$seo['title'] = $page['heading'];
}
if($seo['description']=='' || $seo['default']=='1'){
//$seo['description'] = substr(trim(strip_tags($page['desc'])),0,250);
$seo['description'] = substr(trim(strip_tags($page['desc'])),0,500); //500 for facebook share
}

$desc1 =  ($page['desc']);
$pageTitle = $page['heading'];

if(stristr($desc1,'{{All_Faq}}')){
	$service = include_once(__DIR__.'/faq.php');
	$desc1       = str_replace('{{All_Faq}}',$service,$desc1);
	$all_faq = true;
}



if($page['slug']=='contact'){
	$contact = true;
}


include("header.php");
?>



<style type="text/css">
.page-bg {
	background: url('<?= $page['image'] ?>') no-repeat;
	background-size: cover;
	background-position: center center;
}
</style>
<!--Inner Container Starts-->
<?php $webClass->seoSpecial();  ?>

<section class="page-bg page-banner">
	<div class="page-heading">
		<h2><?= $pageTitle ?></h2>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb text-center">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $pageTitle ?></li>
			</ol>
		</nav>
	</div>
</section>

<?php if($all_faq): ?>
	<div class="faq">
		<div class="standard">
			<div class="panel-group" id="accordion">

				<?php

				echo $desc1;

				?>
			</div>
		</div>
	</div>
	<!-- faq -->

<?php elseif($contact): ?>

	<div id="contact">
		<section id="content" class="section-container less-space">
			<div class="container">
				<div class="row">
	                <!-- <div class="section-heading text-left">
	                  <p>
	                    At iScent we are perfectionists for all your home fragrance needs, contact us anytime.
	                  </p>
	              </div> -->
	          </div>
				<!-- 
	          <div class="row section-container">
	          	<div class="col-md-4">
	          		<div class="contact-infos">
	          			<i class="fas fa-map-marker-alt"></i>
	          			<p>3100 West Cary Street <br>Richmond, Virginia 23221</p>
	          		</div>
	          	</div>
	          	<div class="col-md-4">
	          		<div class="contact-infos">
	          			<i class="fas fa-clock"></i>
	          			<p>
	          				Richmond, Virginia 23221 <br>11am - 7pm
	          			</p>
	          		</div>
	          	</div>
	          	<div class="col-md-4">
	          		<div class="contact-infos">
	          			<i class="fas fa-phone fa-flip-horizontal"></i>
	          			<p>
	          				P: 804.355.4383 <br>F: 804.367.7901
	          			</p>
	          		</div>
	          	</div>
	          </div> -->
	          <?= $desc1; ?>
	          <hr>
	          <?= include_once(__DIR__.'/contact.php')?>
	          

	      </div>
	  </section>
	</div>
	<!-- End of content -->

	<div id="map">
		<!-- <img src="codeilo/images/map.jpg"  class="img-responsive" alt=""> -->
		<iframe src="<?php echo $functions->ibms_setting('locationMap'); ?>"   class="img-responsive" allowfullscreen></iframe>
	</div>
	<style type="text/css">
		#map iframe {
		    width: 100%;
		    height: 600px;
		}
		iframe.img-responsive {
		    max-width: 100%;
		}
		iframe {
		    vertical-align: middle;
		    border-style: none;
		}
	</style>
	<!-- <iframe src="<?php echo $functions->ibms_setting('locationMap'); ?>" allowfullscreen></iframe> -->



<?php else: ?>
	<section id="content" class="section-container">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<?= $desc1; ?>
					<!-- <h2 class="sec-title">
						Would you believe that your body is able to distinguish over <strong>10,000 different scents</strong>? 
					</h2>
					<p>These scents are sent to the brain as new neurons that the body is able to produce, as signals.
						<br><br>
						Smell is the most powerful of the five senses which is closely connected to one’s emotional behavior and memories. It is one of the most important factors, proven to affect one’s mood, remind of fond memories or even influence decisions such as those affecting customer loyalty and repeat purchases.
					</p> -->
				</div>
			</div>
		</div>
	</section>

<?php 
	include("__pricing.php");
	include("__testimonials.php");
	
endif;

include("footer.php"); 
?>