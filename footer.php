<?php
	global $webClass;
	global $functions;
	global $_e;
	global $webClass;
	global $db;
	//No Need to define global it is just for PHPstrom suggestion list...
?>

		<footer id="footer" class="section-container">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<ul>
							<?php
								$mainMenu = $menuClass->menuTypeSingle('main');
								foreach ($mainMenu as $val) {
									echo '<li><a href="' . $val['link'] . '">' . $val['name'] . '</a></li>';
								}
							?>
						</ul>
					</div>
					<div class="col-md-3">
						<ul>
							<?php
								$footerMenu = $menuClass->menuTypeSingle('footer_Links');
								foreach ($footerMenu as $val) {
									echo '<li><a href="' . $val['link'] . '">' . $val['name'] . '</a></li>';
								}
							?>
						</ul>
					</div>
					<div class="col-md-4">
						<?php $box29 = $webClass->getBox("box29"); ?> 
						<p class="footer-p"><?php echo $box29['heading2'] ?>
							<br><br>
							<?php echo $box29['text'] ?>
						</div>
						<div class="col-md-3">
							<div class="social-block">
								<ul class="no-style social-links">
									<li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
									<li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
									<li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
								</ul>
							</div>
						</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<hr>
						<div class="row">
							<div class="col-md-6">
								<p class="footer-p">
									Copyright © 2019 <span>All rights reserved.</span>
								</p>
							</div>
							<!-- <div class="col-md-6 text-right">
								<p class="footer-p">Design with <i class="fas fa-heart" style="color: red;"></i>  by <a href="#" style="color: #000;">Fifth Designs</a></p>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
</body>
<!-- Scripts -->
<script src="<?php echo WEB_URL ?>/codeilo/js/jquery.js"></script>
<script src="<?php echo WEB_URL ?>/codeilo/fontawesome/js/all.min.js"></script>
<script src="<?php echo WEB_URL ?>/codeilo/wow/wow.min.js"></script>
<script src="<?php echo WEB_URL ?>/codeilo/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo WEB_URL ?>/codeilo/slick/slick/slick.js"></script>
<script src="<?php echo WEB_URL ?>/codeilo/js/custom.js"></script>


<!--Start of Tawk.to Script-->

<!-- <script type="text/javascript">

	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
		var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
		s1.async=true;
		s1.src='https://embed.tawk.to/595e1eee6edc1c10b0344995/default';
		s1.charset='UTF-8';
		s1.setAttribute('crossorigin','*');
		s0.parentNode.insertBefore(s1,s0);
	})();

</script> -->
</html>