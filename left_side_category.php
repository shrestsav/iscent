<?php

global $productClass;

global $_e,$webClass,$functions,$dbF,$db;

global $menuClass;



$pCategory = 'pCategory-';

?>


<div class="left_product">
<div class="left_product_col1">
<div class="u-vmenu">
 <ul>





		<?php



		##### Main MENU

		$css = false;

		$view_css= '';

		$mainMenu = $menuClass->menuTypeSingle1('main');

		foreach ($mainMenu as $val) {

		$insideActive = false;

		$innerUl = '';

		$menuId = $val['id'];

		$text = _n($val['name']);

		$text_name = str_replace(' ', '-', $text);

		$link = $pCategory.$menuId.'-'.$text_name;

		//$link = $val['link'];



		// $underid = $val['under'];

		$has_inner_level_two_class = '';

		$inner_level_two = null;

		$mainMenu2 = $menuClass->menuTypeSingle1('main', $menuId);

		if (!empty($mainMenu2)) {

		$has_inner_level_two_class = 'has-sub';

		$inner_level_two = true;



		$innerUl .= '



		<ul>

		';

		foreach ($mainMenu2 as $val2) {

		$innerUl3 = '';

		$text2 = _n($val2['name']);

		$text_name2 = str_replace(' ', '-', $text2);

		$menuId2 = $val2['id'];

		//$link2 = $val2['link'];

		$link2 = $pCategory.$menuId2.'-'.$text_name2;

		$menuIcon = '';

		$active = $val2['active'];





		// $underid = $val2['under'];



		if ($active == '1') {

		$active = 'active';

		$insideActive = $css = true;

		}



		$has_inner_level_three_class = '';





		$mainMenu3 = $menuClass->menuTypeSingle1('main', $menuId2);

		# count the inner level 3 lis

		$innerUl3count = ( $mainMenu3 == false ? 0 : count($mainMenu3) ) ;

		$innerUl3 .= ( $innerUl3count > 0 ) ? '<ul>' : '';



		if ( $innerUl3count > 0) {



		foreach ($mainMenu3 as $val3) {

		$view_css3 = '';

		$text3       = _n($val3['name']);

		$text_name3 = str_replace(' ', '-', $text3);

		$menuId3     = $val3['id'];

		//$link3       = $val3['link'];

		$link3 = $pCategory.$menuId3.'-'.$text_name3;

		$menuIcon3   = $val3['icon'];

		$active3     = $val3['active'];

		if ($active3 == '1') {

		$active3 = 'active';

		$insideActiveThree = true;

		}



		$has_inner_level_three_class = 'has-sub';





		$innerUl3 .= '



		<li class="'.$view_css3.'">

		<a href="' . $link3 . '">' . $text3 . '

		</a>



		</li>





		';



		}



		}







		$innerUl3 .= ( $innerUl3count > 0 ) ? '</ul><!--3rd array End-->' : '';



		// $innerUl3 .= "</ul><!--3rd array End-->";





		if ($innerUl3) {



		// var_dump($menuId);



		$image_div = '';



		} else {

		$image_div = '';

		}







		$innerUl .= '



		<li class="'.$view_css.'">



		<a href="' . $link2 . '">



		' . $text2 . '



		



		</a><span></span> ' . $innerUl3 . '



		</li>

		';

		}



		$innerUl .= "</ul><!--2nd array End-->";

		}



		$text = _n($val['name']);

		$text_name = str_replace(' ', '-', $text);



		//$link = $val['link'];

		$link = $pCategory.$menuId.'-'.$text_name;

		$menuIcon = $val['icon'];

		if (!empty($menuIcon)) {

		$image_div = '';

		} else {

		$image_div = '';

		}

		$active = $val['active'];



		if ($active == '1' || $insideActive) {



		if (!empty($mainMenu2)) {

		$css = true;

		}

		$active = 'active';

		}

		echo '

		<li>

		<a href="' . $link . '">



		' . $text . '



		</a><span></span>







		' . $innerUl . '













		</li>



















		';

		}



		echo '';





		?>

		</ul>


<!--  ul close -->
<script>
$(document).ready(function() {
$(".u-vmenu").vmenuModule({
Speed: 200,
autostart: false,
autohide: true
});
});
</script>
</div>
</div>
<!-- left_product_col1 close -->
</div>
<!-- left_product close -->
<script>
$(document).ready(function() {
$(".show_on").click(function() {
$(".left_product").fadeToggle();
});
});
</script>



   