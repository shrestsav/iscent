<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/webUsers_ajax.class.php");
    $page=$_GET['page'];

    $ajax=new webusers_ajax();
    switch($page){
        case 'deleteWebUser':
            $ajax->deleteWebUser();
        break;
        case 'activeWebUser':
            $ajax->activeWebUser();
        break;
        case 'deleteAdminUser':
            $ajax->deleteAdminUser();
        break;
        case 'activeAdminUser':
            $ajax->activeAdminUser();
        break;
        case 'deleteAdminGrp':
            $ajax->deleteAdminGrp();
        break;
        case 'activeSponsor':
            $ajax->activeSponsor();
        break;
    }
}

?>