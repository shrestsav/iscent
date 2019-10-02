<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/reviews.class.php");
    $page=$_GET['page'];

    $ajax=new reviews();
    switch($page){
        case 'deleteReview':
            $ajax->deleteReview();
        break;
        case 'activeReview':
            $ajax->activeReview();
        break;

    }
}

?>