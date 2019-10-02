<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu   =   "product"; // ul menu active

switch($page):
    case ("managecat"):
        $subMenu='managecat';
        $content = include "category.php";
        break;
    case ("edit"):
        $subMenu='managecat';
        $content = include "categoryEdit.php";
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
echo '<h3 class="main_heading">'. _uc($_e['Manage Categories']) .'</h3>';
echo $content;

include("../footer.php");

?>