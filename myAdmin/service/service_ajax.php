<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/service_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new service_ajax();
    switch($page){
        case 'deleteservice':
            $ajax->deleteservice();
        break;
        case 'serviceSort':
            $ajax->serviceSort();
            break;
    }
}

?>