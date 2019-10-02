<section id="testimonial" class="section-container bg-gray">
	<div class="icontainer">
		<div class="row">
			<div class="col-md-12">
				<div class="section-heading">
					<h2 class="sec-title uline">
						<?= $webClass->getBox("box22")['heading'] ?> 
					</h2>
				</div>
			</div>
		</div>
		<div class="testimonial-slider clearfix">
			<?php 
				$box23 = $webClass->getBox("box23");
			?> 
			<div class="col-md-12">
				<div class="client-words text-center">
					<div class="client-img"><img src="<?= $box23['image'] ?>" alt=""></div>
					<i class="fas fa-quote-left"></i>
					<p><?= $box23['text'] ?></p>
				</div>
				<h3><?= $box23['heading2'] ?></h3>
			</div>
			<?php 
				$box24 = $webClass->getBox("box24") 
			?> 
			<div class="col-md-12">
				<div class="client-words text-center">
					<div class="client-img"><img src="<?= $box24['image'] ?>" alt=""></div>
					<i class="fas fa-quote-left"></i>
					<p><?= $box24['text'] ?></p>
				</div>
				<h3><?= $box24['heading2'] ?></h3>
			</div>
			<?php 
				$box25 = $webClass->getBox("box25"); 
			?> 
			<div class="col-md-12">
				<div class="client-words text-center">
					<div class="client-img"><img src="<?= $box25['image'] ?>" alt=""></div>
					<i class="fas fa-quote-left"></i>
					<p><?= $box25['text'] ?></p>
				</div>
				<h3><?= $box25['heading2'] ?></h3>
			</div>
			<?php 
				$box26 = $webClass->getBox("box26"); 
			?> 
			<div class="col-md-12">
				<div class="client-words text-center">
					<div class="client-img"><img src="<?= $box26['image'] ?>" alt=""></div>
					<i class="fas fa-quote-left"></i>
					<p><?= $box26['text'] ?></p>
				</div>
				<h3><?= $box26['heading2'] ?></h3>
			</div>
			<?php 
				$box27 = $webClass->getBox("box27"); 
			?> 
			<div class="col-md-12">
				<div class="client-words text-center">
					<div class="client-img"><img src="<?= $box27['image'] ?>" alt=""></div>
					<i class="fas fa-quote-left"></i>
					<p><?= $box27['text'] ?></p>
				</div>
				<h3><?= $box27['heading2'] ?></h3>
			</div>
		</div>
	</div>
</section>