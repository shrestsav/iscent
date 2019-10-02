<?php

require_once("../global.php");

@$page = $_GET['page'];

global $menu;
global $subMenu;

$menu="webUserM"; // ul menu active

switch($page):



    case ("view"):



        $subMenu='support';



        $content = include "support.php";



        break;



    case ("edit"):



        $subMenu='support';



        $content = include "supportEdit.php";



        break;



    default:



        $content = "Page Not Found.";



        break;



    endswitch;











include("../header.php");



echo '<h3 class="main_heading">'. _uc($_e['Support']) .'</h3>';



echo $content;







include("../footer.php");







?>