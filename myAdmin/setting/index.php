<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="adminSetting"; // ul menu active

switch($page):
    case ("IBMSSetting"):
        $subMenu='IBMSSetting';
        $content = include "IBMSSetting.php";
        break;
    case ("history"):
        $subMenu='history';
        $content = include "history.php";
        break;
    case ("account"):
        $subMenu='account';
        $content = include "account.php";
        break;
    case ("hardWords"):
        $subMenu='hardWords';
        $content = include "hardWords.php";
        break;
    default:
        $content = "Page Not Found";
        break;
    endswitch;


include("../header.php");
echo '<h3 class="main_heading">'. _uc($_e['Setting']) .'</h3>';
echo $content;

include("../footer.php");

?>