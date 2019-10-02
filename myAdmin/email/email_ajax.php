<?php

if (isset($_GET['page'])) {
    require_once(__DIR__ . "/classes/email_ajax.class.php");
    $page = $_GET['page'];

    $ajax = new email_ajax();
    switch ($page) {
        case 'deleteEmail':
            $ajax->deleteEmail();
            break;
        case 'deleteGroup':
            $ajax->deleteGroup();
            break;
        case 'activeEmail':
            $ajax->activeEmail();
            break;
        case 'emailGrp':
            $ajax->emailGrp();
            break;
        case 'deleteLetter':
            $ajax->deleteLetter();
            break;

        case 'deleteQueue':
            $ajax->deleteQueue();
            break;

        case 'data_ajax_active_email':
        case 'data_ajax_unactive_email':
            $ajax->email_fetch();
            break;

        case 'startQueue':
            $ajax->startQueue();
            break;
    }
}

?>