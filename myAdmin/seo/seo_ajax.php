<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/seo_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new seo_ajax();
    switch($page){
        case 'deleteSeo':
            $ajax->deleteSeo();
        break;

    }
}

?>