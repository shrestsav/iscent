<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="seoM"; // ul menu active

switch($page):
    case ("seo"):
        $subMenu='seo';
        $content = include "seo.php";
        break;
    case ("edit"):
        $subMenu='seo';
        $content = include "seoEdit.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");
echo '<h3 class="main_heading">'. _uc($_e['SEO Management']) .'</h3>';
echo $content;

include("../footer.php");

?>