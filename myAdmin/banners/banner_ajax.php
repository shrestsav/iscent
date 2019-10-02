<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/banner_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new banner_ajax();
    switch($page){
        case 'deleteBanner':
            $ajax->deleteBanner();
        break;
        case 'bannersSort':
            $ajax->bannersSort();
            break;
    }
}

?>