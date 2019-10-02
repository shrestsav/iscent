<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/page_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new pages_ajax();
    switch($page){
        case 'deletePage':
            $ajax->deletePage();
        break;

    }
}

?>