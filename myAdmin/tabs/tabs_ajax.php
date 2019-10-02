<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/tabs_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new tabs_ajax();
    switch($page){
        case 'deletetabs':
            $ajax->deletetabs();
        break;
        case 'tabsSort':
            $ajax->tabsort();
            break;
    }
}

?>