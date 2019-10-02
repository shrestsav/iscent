<?php 
include("global.php");
include("header.php");
global $webClass;
$box33 	= 	$webClass->getBox("box33"); 
$box34 	= 	$webClass->getBox("box34"); 
$box35 	= 	$webClass->getBox("box35"); 
$box36 	= 	$webClass->getBox("box36"); 
$box37 	= 	$webClass->getBox("box37"); 
$box38 	= 	$webClass->getBox("box38"); 
$box39 	= 	$webClass->getBox("box39"); 
$box40 	= 	$webClass->getBox("box40"); 
?>

<style type="text/css">
.about-bg {
	background: url('<?= $box33['image'] ?>') no-repeat;
	background-size: cover;
	background-position: center center;
}
</style>

<section class="about-bg page-banner">
	<div class="page-heading">
		<h2><?= $box33['heading'] ?></h2>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb text-center">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $box33['heading'] ?></li>
			</ol>
		</nav>
	</div>
</section>
<!-- ENd of elegance -->

<div id="about">
	<section id="content" class="section-container less-space">
		<div class="container">
			<div class="row">
				<div class="section-heading text-left">
					<h3 class="promo-text"><?= $box33['heading2'] ?></h3> <br>
					<p><?= $box33['text'] ?></p>
				</div>
			</div>
		</div>

		<div class="about-feats bg-gray row section-container">
			<div class="col-md-12">
				<div class="section-heading">
					<h2 class="sec-title uline"><?= $box34['heading'] ?></h2>
				</div>
			</div>

			<div class="col-md-3">
				<i class="fa fa-<?= $box35['text'] ?>" aria-hidden="true"></i>
				<span><?= $box35['heading2'] ?></span>
				<h3><?= $box35['heading'] ?></h3>
			</div>
			<div class="col-md-3">
				<i class="fas fa-<?= $box36['text'] ?>"></i>
				<span><?= $box36['heading2'] ?></span>
				<h3><?= $box36['heading'] ?></h3>
			</div>
			<div class="col-md-3">
				<i class="fas fa-<?= $box37['text'] ?>"></i>
				<span><?= $box37['heading2'] ?></span>
				<h3><?= $box37['heading'] ?></h3>
			</div>
			<div class="col-md-3">
				<i class="fas fa-<?= $box38['text'] ?>"></i>
				<span><?= $box38['heading2'] ?></span>
				<h3><?= $box38['heading'] ?></h3>
			</div>
		</div>
		<div class="mission container" style="padding-top: 100px;">
			<div class="row">
				<div class="col-md-6" style="padding-right: 100px; border-right: 1px solid #ccc;">
					<h3><?= $box39['heading'] ?></h3>
					<p><?= $box39['text'] ?></p>
				</div>
				<div class="col-md-6" style="padding-left: 100px;">
					<h3><?= $box40['heading'] ?></h3>
					<p><?= $box40['text'] ?></p>
				</div>

			</div>
		</div>
	</section>
</div>

<?php include("__testimonials.php"); ?>

<?php include("footer.php"); ?>


