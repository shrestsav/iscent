<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu   =   "webMenuM"; // ul menu active

switch($page):
    case ("menu"):
        $subMenu='menu';
        $content = include "menu.php";
        break;
    case ("edit"):
        $subMenu='menu';
        $content = include "menuEdit.php";
        break;
    case ("footerMenu"):
        $subMenu='footerMenu';
        $content = include "footerMenu.php";
        break;
    case ("footerMenuEdit"):
        $subMenu='footerMenu';
        $content = include "footerMenuEdit.php";
    break;
    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");
echo '<h3 class="main_heading">'. _uc($_e['Manage Website Menu']) .'</h3>';
echo $content;

include("../footer.php");

?>