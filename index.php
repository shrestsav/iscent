<?php
include("global.php");
include("header.php");
global $webClass;
global $productClass;

	/**
	* MultiLanguage keys Use where echo;
	* define this class words and where this class will call
	* and define words of file where this class will called
	**/
	global $_e;
	$_w = array();
	$_w['Best Sales'] = '';
	$_w['Latest Products'] = '';
	$_w['Feature Products'] = '';
	$_w['You Click...'] = '';
	$_w['To View Other Products,'] = '';
	$_w['Trending Fashion Winter 2014'] = '';
	$_w['From The Blog'] = '';
	$_w['Subscribe to our newsletter massa In Curabitur id risus sit quis justo sed ovanti'] = '';
	$_w['Newsletter'] = '';
	$_w['Social Network'] = '';
	$_w['Contact Us'] = '';
	$_w['Newsletter'] = '';
	$_w['News and Events'] = '';
	$_w['Here’s the best part of our impressive serives'] = '';
	$_w['Why Choose Us?'] = '';
	$_e = $dbF->hardWordsMulti($_w, currentWebLanguage(), 'Website Index');
	?>

	<!-- BANNER SECTION -->
	<section id="banner">
		<div class="feat-slider">
			<?php
			$bannersData = $webClass->web_banners();
			foreach($bannersData as $val):
				$title  =  $val['title'];
				$text   =  $val['text'];
				$image  =  $val['layer0'];
				$layer1 =  $val['layer1'];
				$layer2 =  $val['layer2'];
				$link   =  $val['link'];
				?>
				<div class="feat-item">
					<div class="feat-item-inner">
						<img src="<?= $image ?>" alt="">
						<div class="banner-content">
							<h2><?= $title ?></h2>
							<h4><?= $layer1 ?></h4>
							<a href="<?= $link ?>" class="btn btn-primary"><?= $layer2 ?></a>
						</div>
					</div>
				</div>
				<?php 
			endforeach;
			?>
		</div>
	</section>

	<!-- WHY US SECTION -->
	<section id="area" class="section-container">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<?php $box1 = $webClass->getBox("box1"); ?> 
					<h2 class="sec-title"> <?= $box1['heading'] ?> </h2>
					<p><?= $box1['text'] ?></p>
				</div>
			</div>
		</div>
		<div class="icontainer">
			<div class="row">
				<div class="col-md-3">
					<?php $box2 = $webClass->getBox("box2"); ?> 
					<a href="<?= $box2['link'] ?>" class="area-item">
						<img class="area-image" src="<?= $box2['image'] ?>" alt="">
						<span><?= $box2['heading'] ?></span>
					</a>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<?php $box3 = $webClass->getBox("box3"); ?> 
							<a href="<?= $box3['link'] ?>" class="area-item">
								<img src="<?= $box3['image'] ?>" alt="">
								<span><?= $box3['heading'] ?></span>
							</a>
						</div>
						<div class="col-md-6">
							<?php $box4 = $webClass->getBox("box4"); ?> 
							<a href="<?= $box4['link'] ?>" class="area-item">
								<img src="<?= $box4['image'] ?>" alt="">
								<span><?= $box4['heading'] ?></span>
							</a>
						</div>
						<div class="col-md-12">
							<?php $box5 = $webClass->getBox("box5"); ?> 
							<a href="<?= $box5['link'] ?>" class="area-item">
								<img src="<?= $box5['image'] ?>" alt="">
								<span><?= $box5['heading'] ?></span>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="row">
						<div class="col-md-12">
							<?php $box6 = $webClass->getBox("box6"); ?> 
							<a href="<?= $box6['link'] ?>" class="area-item">
								<img src="<?= $box6['image'] ?>" alt="">
								<span><?= $box6['heading'] ?></span>
							</a>
						</div>
						<div class="col-md-12">
							<?php $box7 = $webClass->getBox("box7"); ?> 
							<a  href="<?= $box7['link'] ?>" class="area-item">
								<img src="<?= $box7['image'] ?>" alt="">
								<span><?= $box7['heading'] ?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="row hiddenIndustries" style="display: none;">
				<div class="col-md-3">
					<?php $box8 = $webClass->getBox("box8"); ?> 
					<a href="<?= $box8['link'] ?>" class="area-item">
						<img src="<?= $box8['image'] ?>" alt="">
						<span><?= $box8['heading'] ?></span>
					</a>
				</div>
				<div class="col-md-3">
					<?php $box9 = $webClass->getBox("box9"); ?> 
					<a href="<?= $box9['link'] ?>" class="area-item">
						<img src="<?= $box9['image'] ?>" alt="">
						<span><?= $box9['heading'] ?></span>
					</a>
				</div>
				<div class="col-md-3">
					<?php $box10 = $webClass->getBox("box10"); ?> 
					<a href="<?= $box10['link'] ?>" class="area-item">
						<img src="<?= $box10['image'] ?>" alt="">
						<span><?= $box10['heading'] ?></span>
					</a>
				</div>
				<div class="col-md-3">
					<?php $box11 = $webClass->getBox("box11"); ?> 
					<a href="<?= $box11['link'] ?>" class="area-item">
						<img src="<?= $box11['image'] ?>" alt="">
						<span><?= $box11['heading'] ?></span>
					</a>
				</div>
			</div>
			<div class="text-center">
				<a href="javascript:;" class="btn btn-secondary loadMoreIndustries"><?= $box1['linkText'] ?></a>
			</div>
		</div>
	</section>

	<!-- VALUABLE CLIENTS SECTION -->
	<section id="clients" class="section-container">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<?php $box41 = $webClass->getBox("box41"); ?> 
					<h2 class="sec-title uline">
						<?= $box41['heading'] ?>
					</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<ul class="c-slider responsive">
						<li><img src="<?= $webClass->getBox("box42")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box43")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box44")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box45")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box46")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box47")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box48")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box49")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box50")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box51")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box51")['image'] ?>" alt="" /></li>
						<li><img src="<?= $webClass->getBox("box53")['image'] ?>" alt="" /></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<?php include("__pricing.php"); ?>

	<?php include("__testimonials.php"); ?>

	<?php include('footer.php'); ?> 

	<script type="text/javascript">
		$('.loadMoreIndustries').on('click',function(e){
			$('.hiddenIndustries').show();
			$(this).hide();
		})
	</script>