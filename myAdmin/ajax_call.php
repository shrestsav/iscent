<?php

if(isset($_GET['page'])){
    require_once(__DIR__."/classes/ajax_functions.php");
    $page=$_GET['page'];

    $ajax=new ajax_functions();

    switch($page){
        //Color
        case 'menu_session':

            break;

    }


}

?>