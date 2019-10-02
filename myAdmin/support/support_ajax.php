<?php

if(isset($_GET['page'])){

    require_once(__DIR__ . "/classes/support_ajax.class.php");

    $page=$_GET['page'];

    $ajax=new support_ajax();

    switch($page){

        case 'getUserMessages':
            $ajax->getUserMessages();
        break;

        case 'sendUserMessage':
            $ajax->sendUserMessage();
        break;

    }
}


?>