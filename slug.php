<?php
global $functions;
if($_SERVER['REQUEST_URI']=='/projects/sharkspeedNew/website/abc'){

    echo "Index ";
    if(true){
        echo "true ";

        $_GET['pId'] = 1047;
       include("detail.php");
    }else{

    }
}else{
    $pg = $_SERVER['REQUEST_URI'];
    echo $pg=str_replace("/projects/sharkspeedNew/website/","",$pg);
    require_once("$pg");
}