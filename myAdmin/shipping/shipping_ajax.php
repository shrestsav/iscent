<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/shipping_ajax.php");
    $page=$_GET['page'];

    $ajax=new shipping_ajax();

    switch($page){
        case 'deleteShipping':
            $ajax->deleteShipping();
            break;
    }


}

?>