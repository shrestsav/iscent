<?php

if(isset($_GET['page'])){
    require_once(__DIR__ . "/classes/stock_ajax.php");
    $page=$_GET['page'];

    $ajax=new stock_ajax();

    switch($page){
        //Color
        case 'receiptDetail':
            $ajax->receiptDetail('color');
            break;
        case 'countCurrentQTY':
            $ajax->countCurrentQTY();
            break;
        case 'directQTYAdd':
            $ajax->directQTYAdd();
            break;

        case 'directQTYRemove':
            $ajax->directQTYRemove();
        break;

        case 'directStockQTYAdd':
            $ajax->directStockQTYAdd();
        break;

        case 'directStockQTYRemove':
        $ajax->directStockQTYRemove();
        break;

        case 'directStockLocationAdd':
        $ajax->directStockLocationAdd();
        break;
    }


}

?>