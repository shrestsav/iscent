<?php global $webClass;
/** Help don't remove this
 *
    $bannersData    =   $webClass->web_banners();
    $banners = '';
    $pager = '';
    $i = 1;
    foreach($bannersData as $val){
        $title  =   $val['title'];
        $text   =   $val['text'];
        $image  =   $val['layer0'];
        $layer1 =   $val['layer1'];
        $layer2 =   $val['layer2'];
        $layer3 =   $val['layer3'];
        $link   =   $val['link'];
        $banners .= '<li><img src="'.$image.'" alt="'.$title.'"></li>';
        $pager  .= '<li class=""><a href="#'.$i.'" class=""></a></li>';
        $i++;
    }
    echo $banners; //where want
 * echo $pager; //where want
 *
 */
?>

<div class="banner_side">
    <ul id="banner">

        <?php
            $bannersData    =   $webClass->web_banners();
            $banners        = '';
            foreach($bannersData as $val){
                $title  =   $val['title'];
                $text   =   $val['text'];
                $image  =   $val['layer0'];
                $layer1 =   $val['layer1'];
                $layer2 =   $val['layer2'];
                $layer3 =   $val['layer3'];
                $link   =   $val['link'];
                $banners .= '
                    <li><img src='.$image.' alt="">
                        <div class="banner_text wow fadeInLeft">
                            <div class="banner_txt1">
                                <h2>'.$title.'</h2>
                                <div class="border_side">
                                </div>
                                <div class="text_banner">
                                    '.$text.'
                                </div>
                            </div>
                        </div>
                    </li>
                ';
            }
            echo $banners;
        ?>
    </ul>
    <div class="btns_area wow fadeInLeft">
        <div class="left_btn hvr-pop">
            <!-- <img src="webImages/right-arrow.png" alt=""> --></div>
        <div class="right_btn hvr-pop">
            <!-- <img src="webImages/left-arrow.png" alt=""> --></div>
    </div>
    <!-- btns_area2 close -->
    <script>
    $(function() {
        $('#banner').ulslide({

            effect: {
                type: 'crossfade', // slide or fade
                axis: 'x', // x, y
                showCount: 0,

                distance: 0

            },
            pager: '#slide-pager a',
            nextButton: '.right_btn',
            prevButton: '.left_btn',
            duration: 800,
            mousewheel: false,
            autoslide: 3000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
        });
    });
    </script>
</div>
