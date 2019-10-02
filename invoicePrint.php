<?php

include_once('global.php');

// $dbF->prnt($_REQUEST);
if(isset($_SESSION['_uid']) && $_SESSION['_uid']>0){



}else{

    $id = $_GET['mailId'];

    if(isset($_GET['orderId'])){

        $sId = $_GET['orderId'];

        $sId = $functions->decode($sId);

        if($id == $sId) {

            $id = $sId;

            echo "<script>alert('".$dbF->hardWords("Print This Invoice or Save this Link",false)."');</script>";

        }else{

            exit;

        }

    }else{

        exit;

    }

}



$msg = include_once('orderMail.php');

echo $msg;