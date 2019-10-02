<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/setting_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new setting_ajax();
    switch($page){
        case 'deleteHardWord':
            $ajax->deleteHardWord();
        break;

    }
}

?>