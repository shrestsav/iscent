<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="pages"; // ul menu active

switch($page):
    case ("page"):
        $subMenu='page';
        $content = include "page.php";
        break;
    case ("edit"):
        $subMenu='page';
        $content = include "pageEdit.php";
        break;
    case ("pageNew"):
        $subMenu='pageNew';
        $content = include "pageNew.php";
        break;
    case ("homePage"):
        $subMenu='homePage';
        $content = include "homePage.php";
        break;
    case ("homePageEdit"):
        $subMenu='homePage';
        $content = include "homePageEdit.php";
        break;
    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");
echo '<h3 class="main_heading">'. _uc($_e['Pages Management']) .'</h3>';
echo $content;

include("../footer.php");

?>