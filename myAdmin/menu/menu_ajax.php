<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/menu_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new menu_ajax();
    switch($page){
        case 'deleteMenu':
            $ajax->deleteMenu();
        break;
        case 'menuSort':
            $ajax->menuSort();
            break;

        case 'deleteFooterMenu':
        $ajax->deleteFooterMenu();
        break;
        case 'footerMenuSort':
            $ajax->footerMenuSort();
            break;
    }
}

?>