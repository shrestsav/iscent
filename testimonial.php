<?php
ob_start();
include_once("global.php");
global $webClass;
global $dbF;
global $_e;
$_w = array();
$_w['Email']    = '' ;
//$_e    =   $dbF->hardWordsMulti($_w,currentWebLanguage(),'Website Testimonial');

$sql = "SELECT id,testimonial_link,testimonial_heading,testimonial_shrtDesc,testimonial_image,testimonial_position,testimonial_email,testimonial_date FROM testimonial WHERE publish = '1' ORDER BY sort";
$data = $dbF->getRows($sql);
if (!$dbF->rowCount) {
    return false;
}

echo  '<div class="container-fluid padding-0"> <div class="list-group">';
foreach ($data as $key => $val) {
    $id = $val['id'];

    //var_dump($userInfo);
    $image    = $functions->addWebUrlInLink(translateFromSerialize($val['testimonial_image']));
    $imageR   = $image;
    $image   =  $functions->resizeImage($imageR,'290','',false,false,false);

    $heading  =  translateFromSerialize($val['testimonial_heading']);
    $shrtDescTesti  =  translateFromSerialize($val['testimonial_shrtDesc']);
    $designation  =  translateFromSerialize($val['testimonial_position']);
    $email  =  translateFromSerialize($val['testimonial_email']);
    $date  =  translateFromSerialize($val['testimonial_date']);
    if(!empty($date)){
        $date = "<br>".$date;
    }


    echo "<div class='my-testimonial-Main container-fluid padding-0' id='testimonial_$id'>
        <div class='col-sm-3  padding-0 text-center '>
            <a class='testimonial_image' rel='testimonial-group' href='$imageR' >
                <img alt='$heading' src='$image'   class='img-thumbnail img-responsive'/>
            </a>
        </div>
        <div class='col-sm-9 padding-0 '>
            <div class='popover right my-testimonial hidden-xs' style='display: block;max-width: 100%'>
                <div class='arrow'></div>
                <h3 class='popover-title ' id='popover-top'>$heading
                </h3>
                <div class='popover-content'>
                    <p>$shrtDescTesti</p>
                    <div class='small margin-0 testimonial-by'>
                        $designation &nbsp; <a href='mailto:$email' class='btn-link ' target='_blank'>$email</a>
                        $date
                    </div>
                </div>
            </div>

            <div class='popover  bottom my-testimonial visible-xs ' style='display: block;max-width: 100%'>
                <div class='arrow'></div>
                <h3 class='popover-title ' id='popover-top'>$heading
                </h3>
                <div class='popover-content'>
                    <p>$shrtDescTesti</p>
                    <div class='small margin-0 testimonial-by'>
                        $designation &nbsp; <a href='mailto:$email' class='btn-link ' target='_blank'>$email</a>
                        $date
                    </div>
                </div>
            </div>
        </div>
    </div>";
}

echo '</div></div><!--container-fluid-->';


echo "";?>

    <script>
        $(document).ready(function(){
            $('a.testimonial_image').fancybox();
        });
    </script>

<?php

return ob_get_clean(); ?>