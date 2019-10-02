<?php

include_once('global.php');

$login = $webClass->userLoginCheck();

if(!$login){

    header('Location: login');

    exit();

}

$msg = include_once('orderMail.php');

echo $msg;