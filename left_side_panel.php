<?php
    global $dbF, $db, $_e, $functions, $productClass, $webClass, $menuClass, $seo;
?>


<div id="categoryMenu2"><!--1st array-->
    <ul>

<?php
    ##### MOBILE MENU
    $css = false;
    $mainMenu = $menuClass->menuTypeSingle('side_menu');
    foreach ($mainMenu as $val) {
        $insideActive = false;
        $innerUl = '';
        $menuId = $val['id'];
        $text = _u($val['name']);

        /*$link = $val['link'];*/
        $val['link'] = str_replace(WEB_URL. '/', '', $val['link']);
        $link = sanitize_slug($val['link'], $lowercase = false);
        $val['link'] = $link = WEB_URL .'/'. $link;

        $has_inner_level_two_class = '';
        $inner_level_two = null;
        $mainMenu2 = $menuClass->menuTypeSingle('side_menu', $menuId);
        if (!empty($mainMenu2)) {
            $has_inner_level_two_class = 'has-sub';
            $inner_level_two = true;


            $innerUl .= '
                
                <ul class="_2 inner_class_level_one" style="overflow: hidden; display: none;">

                ';
            foreach ($mainMenu2 as $val2) {
                $innerUl3 = '';
                $text = _u($val2['name']);
                $menuId = $val2['id'];

                /*$link = $val2['link'];*/
                $val2['link'] = str_replace(WEB_URL. '/', '', $val2['link']);
                $link = sanitize_slug($val2['link'], $lowercase = false);
                $val2['link'] = $link = WEB_URL .'/'. $link;

                $menuIcon = $val2['icon'];
                $active = $val2['active'];
                if ($active == '1') {
                    $active = 'active';
                    $insideActive = $css = true;
                }
                if ($menuIcon != '') {
                    $image_div = '<img src="' . $menuIcon . '" alt="">';
                } else {
                    $image_div = '';
                }

                $has_inner_level_three_class = '';


                // $innerUl3 .= '
                
                //     <ul class="_3">

                // ';
                $mainMenu3 = $menuClass->menuTypeSingle('side_menu', $menuId);
                # count the inner level 3 lis
                $innerUl3count = ( $mainMenu3 == false ? 0 : count($mainMenu3) ) ;
                $innerUl3 .= ( $innerUl3count > 0 ) ? '<ul class="_3">' : '';

                if ( $innerUl3count > 0 ) {
                    
                    foreach ($mainMenu3 as $val3) {
                        $text3       = _u($val3['name']);
                        $menuId3     = $val3['id'];
                        
                        /*$link3       = $val3['link'];*/
                        $val3['link'] = str_replace(WEB_URL. '/', '', $val3['link']);
                        $link3 = sanitize_slug($val3['link'], $lowercase = false);
                        $val3['link'] = $link = WEB_URL .'/'. $link3;

                        $menuIcon3   = $val3['icon'];
                        $active3     = $val3['active'];
                        if ($active3 == '1') {
                            $active3 = 'active';
                            $insideActiveThree = true;
                        }


                        $has_inner_level_three_class = 'has-sub';


                        $innerUl3 .= '

                            <li class="_3">
                                <a href="' . $link3 . '" class="_3 ">
                                    <span class="_3">' . $text3 . '</span>
                                </a>
                            </li>


                        ';

                    }

                }



                $innerUl3 .= ( $innerUl3count > 0 ) ? '</ul><!--3rd array End-->' : '';
                // $innerUl3 .= "</ul><!--3rd array End-->";


                $innerUl .= '

                        <li class="' . $has_inner_level_three_class . ' _2">
                            <a href="' . $link . '" class="_2 ">
                                <span class="_2">' . $text . '</span>
                            </a>
                            ' . $innerUl3 . '
                        </li>

                    ';
            }

            $innerUl .= "</ul><!--2nd array End-->";
        }

        $text = _u($val['name']);

        $link = $val['link'];
        $menuIcon = $val['icon'];
        if (!empty($menuIcon)) {
            $image_div = '<img src="' . $menuIcon . '" alt="">';
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
                <li class="' . $has_inner_level_two_class . ' transition_7">
                    <a href="' . $link . '" class="">
                        <span class=" transition_7">' . $text . '</span>
                    </a>

                ' . $innerUl . '

                </li>';
    }

    echo '</ul>';


?>
    </ul><!--1st array End-->

</div>