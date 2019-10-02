<?php

if (isset($_GET['page'])) {
    require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."product_management".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."ajax.php");
    $page = $_GET['page'];

    $ajax = new ajax();
    switch ($page) {

        case 'active_products':
        case 'draft_products':
        case 'pending_products':
            $ajax->fetch_products();
            break;

    }
}

?>