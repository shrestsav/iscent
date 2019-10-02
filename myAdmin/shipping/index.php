<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;
$menu="shippingManagement";

switch($page):

    case ("shipping"):
        $subMenu    =   'shipping by weight';
        $content    =   include "shipping.php";
        break;

    case ("edit"):
        $subMenu    =   'shipping by weight';
        $content    =   include "shippingEdit.php";
        break;

    case ("shippingByClass"):
        $subMenu    =   'shipping by class';
        $content    =   include "shippingByClass.php";
        break;

    default:
        $content = "Page Not Found.";
        break;
    endswitch;


include("../header.php");

echo '<h3 class="main_heading">'. _uc($_e['Shipping Management']) .'</h3>';
echo $content;

include("../footer.php");

?>