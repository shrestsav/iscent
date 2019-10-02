<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="bannersM"; // ul menu active

switch($page):
    case ("banners"):
        $subMenu='banners';
        $content = include "banners.php";
        break;
    case ("edit"):
        $subMenu='banners';
        $content = include "bannerEdit.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");
echo '<h3 class="main_heading">'. _uc($_e['Banners Management']) .'</h3>';
echo $content;

include("../footer.php");

?>