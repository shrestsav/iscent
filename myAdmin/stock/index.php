<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="stock";

switch($page):
    case ("addStore"):
        $subMenu='add store';
        $content = include "addstore.php";
        break;

    case ("purchaseReceipt"):
        $subMenu='purchase receipt';
        $content = include "purchaseReceipt.php";
        break;

    case ("inventory"):
        $subMenu='inventory';
        $content = include "inventory.php";
        break;

    case ("quickAdd"):
        $subMenu='inventory';
        $content = include "qucik_stock_byproduct.php";
        break;

    case ("csv"):
        $subMenu='Import/Export';
        $content = include "csv.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");

echo '<h3 class="main_heading">'. _uc($_e['Stock Management']) .'</h3>';
echo $content;

include("../footer.php");

?>