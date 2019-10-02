<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="quoteManagement"; // ul menu active

switch($page):
    case ("newQuote"):
        $subMenu='newQuote';
        $content = include "newOrder.php";
        break;
    case ("edit"):
        $subMenu='newQuote';
        $content = include "invoice.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");


echo '<h3 class="main_heading">'. _uc($_e['Quote Management']) .'</h3>';
echo $content;

include("../footer.php");

?>