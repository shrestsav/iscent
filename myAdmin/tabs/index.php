<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
//$menu="tabsM"; // ul menu active
$menu="pages"; // ul menu active
switch($page):
    case ("tabs"):
        $subMenu='tabs';
        $content = include "tabs.php";
        break;
    case ("edit"):
        $subMenu='tabs';
        $content = include "tabsEdit.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");
echo '<h3 class="main_heading">'. _uc('FAQ Management') .'</h3>';
echo $content;

include("../footer.php");

?>