<?php

require_once("../global.php");
require_once("functions/product_function.php");
$productF=new product_function();

@$page = $_GET['page'];
if(isset($_GET['operation'])){ // use in ajax
    $page = 'category';
}
global $menu;
global $subMenu;
$menu="product";

switch($page):
    case ("currency"):
        $subMenu='Manage Currency';
        require "classes/currency.class.php";
        $content = include "currency.php";
        break;

    case ("category"):
        $subMenu='Manage Category';
        $content = include "category.php";
        break;

    case ("scale"):
        $subMenu='Manage Scales';
        require "classes/scale.class.php";
        $content = include "scales.php";
        break;

    case ("color"):
        $subMenu='Manage Color';
        require "classes/color.class.php";
        $content = include "colors.php";
        break;

    default:
        $content = include "main.php";
        break;
    endswitch;


include("../header.php");

echo '<h3 class="main_heading">'. _uc($_e['Product Management']) .'</h3>';
echo $content;

include("../footer.php");

?>