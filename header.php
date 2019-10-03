<?php
global $dbF;
global $db;
global $_e;
global $functions;
global $productClass;
global $webClass;
global $menuClass;
global $seo;
$functions->updateInvoiceFromTelr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
	<meta name="format-detection" content="telephone=no">

	<?php echo $functions->ibms_setting('Google_Analythics'); ?>
	<!-- <title>iScent - Home Fragrance Dubai | Scent Diffusers in Dubai</title> -->
	<?php  $webClass->AllSeoPrint(); ?>


	<!--ADDED SEO META-->
	<meta name="rating" content="General" />
	<meta http-equiv="Expires" content="Never" /> 
	<meta http-equiv="Distribution" content="global" /> 
	<meta http-equiv="Rating" content="general" />
	<META NAME="city" CONTENT="Dubai">
	<META NAME="state" CONTENT="UAE">
	<meta http-equiv="Robots" content="index, follow" /> 
	<meta name="GOOGLEBOT" content="index, follow, all" />
	<!--ADDED SEO META-->

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?= WEB_URL ?>/codeilo/images/favicon.ico">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

	<!-- Styles -->
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/fontawesome/css/all.css">
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/wow/animate.css">
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/slick/slick/slick.css">
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/slick/slick/slick-theme.css">
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/css/style.css">

	<!-- Media queries -->
	<link rel="stylesheet" href="<?= WEB_URL ?>/codeilo/css/media.css">

	<!--ADDED SEO SCRIPT-->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-101255756-1', 'auto');
		ga('send', 'pageview');
	</script>
	<!--ADDED SEO SCRIPT-->

</head>

<body>
	<div class="wrapper">
		<div class="header-container">
			<header id="header">
				<span class="ovlay" style="display: none;"></span>
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="head-wrap clearfix">
								<h1 id="logo"><a href="<?= WEB_URL ?>"><img src="<?= WEB_URL ?>/codeilo/images/logo.svg" alt=""></a></h1>
								<div id="main-nav">
									<div class="top-links">
										<a href="<?= WEB_URL ?>/page-contact" class="mail-link">
											<img src="<?= WEB_URL ?>/codeilo/images/contact-white.svg" alt="">
										</a>
										<!-- <a href="#" class="cart-link">
											<img src="<?= WEB_URL ?>/codeilo/images/cart-white.svg" alt="">
										</a> -->
										<?php 
											$url = '/login';
											$icon = 'user-white.svg';
											if($webClass->userLoginCheck()){
												$icon = 'user-white.svg';
												$url = '/profile';
											}
										?>
										<a href="<?= WEB_URL.$url ?>" class="login-link">
											<img src="<?= WEB_URL ?>/codeilo/images/<?= $icon ?>" alt="">
										</a>
									</div>
									<nav class="navbar navbar-expand-lg navbar-light ">
										<div class="nav-header">
											<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
												<!--  <span class="navbar-toggler-icon"></span> -->
												<div id="burger-menu" class="c-hamburger c-hamburger--htx">
													<span>Menu</span>
												</div>
											</button>
										</div>
										<div class="main-navigation collapse navbar-collapse" id="navbarSupportedContent">
											<ul class="navbar-nav nav mr-auto">
												<?php
													$mainMenu = $menuClass->menuTypeSingle('main');
													foreach ($mainMenu as $val):
														$SM = false; //Sub Menu
														$menuID = $val['id'];
														$subMenu = $menuClass->menuTypeSingle('main', $menuID);
														if(!empty($subMenu)){
															$SM = true;	
														}
												?>
													<li class="nav-item<?= ($SM ? ' dropdown dropdown-sub' : '') ?>">
														<a class="nav-link <?= ($SM ? 'dropdown-toggle' : 'effect-underline') ?>" id="subDropdown_<?= $menuID ?>" <?= ($SM ? 'data-toggle="dropdown"' : '') ?> href="<?= $val['link'] ?>" title="<?= $val['name'] ?>"><?= $val['name'] ?></a>
														<?php if($SM): ?>
															<div class="dropdown-menu" aria-labelledby="subDropdown_<?= $menuID ?>">
																<?php foreach ($subMenu as $val2): ?>
																	<a class="dropdown-item" href="<?= $val2['link'] ?>"><?= $val2['name'] ?></a>
																<?php endforeach; ?>
															</div>
														<?php endif; ?>
													</li>
												<?php 
													endforeach; 
												?>
											</ul>
										</div>
									</nav>
								</div>
							</div>   
						</div>
					</div>
				</div>
			</header>
		</div>
		<!-- End of header-container -->