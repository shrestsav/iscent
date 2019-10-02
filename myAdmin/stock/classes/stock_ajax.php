<?php

if(!isset($_POST['loadFromWeb'])) {
    require_once(__DIR__ . "/../../global_ajax.php"); //connection setting db
}else{
    require_once(__DIR__ . "/../../../global.php"); //connection setting db
}


class stock_ajax extends object_class{
    public $productF;

    public function  __construct()
    {
        parent::__construct('3');
        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../../product_management/functions/product_function.php");
            $this->productF=new product_function();
        }

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        $_w['Some Thing Is Wrong Please Try Again.'] = '' ;
        $_w['SNO'] = '' ;
        $_w['Product Stock Location Update'] = '' ;
        $_w['PRODUCT'] = '' ;
        $_w['PURCHASE PRICE'] = '' ;
        $_w['PURCHASE QTY'] = '' ;
        $_w['Product Add QTY {{qty}}'] = '' ;
        $_w['Product Add'] = '' ;
        $_w['Product QTY'] = '' ;
        $_w['Product Deduct QTY {{qty}}'] = '' ;
        $_w['Product Deduct'] = '' ;
        $_w['Product QTY From Stock'] = '' ;


        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin StockAjax');

    }



    public function receiptDetail()
    {
        global $_e;
        if(isset($_POST['itemId'])){
        $id = $_POST['itemId'];
        }else{
            echo _uc($_e['Some Thing Is Wrong Please Try Again.']);
            exit;
        }
        $sql = "SELECT * FROM  `purchase_receipt`,`purchase_receipt_pro`
                    WHERE `purchase_receipt_pro`.`receipt_id`='$id'
                            AND  `purchase_receipt`.`receipt_pk` ='$id' ";
        $data=$this->dbF->getRows($sql);

      echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                    <th>'. _u($_e['SNO']) .'</th>
                    <th>'. _u($_e['PRODUCT']) .'</th>
                    <th>'. _u($_e['PURCHASE PRICE']) .'</th>
                    <th>'. _u($_e['PURCHASE QTY']) .'</th>
                    </thead>
                <tbody>';


        $i=0;
        foreach($data as $val){
            $i++;
            $pName= $this->productF->getProductFullName($val['receipt_product_id'],$val['receipt_product_scale'],$val['receipt_product_color']);
                    echo "<tr>
                        <td>$i</td>
                        <td>$pName</td>
                        <td>$val[receipt_price]</td>
                        <td>$val[receipt_qty]</td>
                    </tr>";
        }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";

    }

    public  function countCurrentQTY(){
        $pId=$_POST['pId'];
        $storeID=$_POST['storeID'];
        $scaleId=$_POST['scaleId'];
        $colorId=$_POST['colorId'];
        $exact = false;//call from admin stock inventory
        if(isset($_POST['loadFromWeb'])){
            $exact = true; // call from website
        }

        $qty = $this->productF->productQTY($pId,$storeID,$scaleId,$colorId,$exact);
        if($qty < 1) {
            echo "0";
            return false;
        }

        $location_ = $this->productF->productLocation($pId,$storeID,$scaleId,$colorId,$exact);
        echo json_encode(array("qty"=>$qty,"location_"=>$location_));
    }

    public  function directQTYAdd(){
        global $_e;
        $pId=$_POST['pId'];
        $pqty =intval($_POST['pqty']);

        $sqlCheck="SELECT * FROM `product_inventory` WHERE `qty_product_id` = '$pId'";
        $this->dbF->getRow($sqlCheck);
        if($this->dbF->rowCount>0){
            $date =date('Y-m-d H:i:s'); //2014-09-24 13:46:10
            $sql= "UPDATE `product_inventory` SET `qty_item` = qty_item+$pqty , `updateTime` = '$date' WHERE `qty_product_id` = '$pId'";
            $this->dbF->setRow($sql);
        }else{
            $sql = "INSERT INTO `product_inventory`(
                                            `qty_product_id`,
                                            `qty_item`
                                        ) VALUES (?,?) ";
            $arry=array($pId,$pqty);
            $this->dbF->setRow($sql,$arry);

        }
        if($this->dbF->rowCount>0){
            echo '1';
            $desc= _replace('{{qty}}',$pqty,$_e['Product Add QTY {{qty}}']);
            $this->functions->setlog(_uc($_e['Product Add']),_uc($_e['Product QTY']),$pId,$desc);
        }else{
            echo '0';
        }

    }


    public  function directQTYRemove(){
        global $_e;
        $pId=$_POST['pId'];
        $pqty =$_POST['pqty'];

        $sqlCheck="SELECT `qty_product_id`,`qty_item` FROM `product_inventory` WHERE `qty_product_id` = '$pId'";
        $data=$this->dbF->getRow($sqlCheck);
        if($this->dbF->rowCount>0){
            $oldQty=$data['qty_item'];
            if($oldQty<$pqty){
                echo '0';
                exit;
            }
            $date =date('Y-m-d H:i:s'); //2014-09-24 13:46:10
            $sql= "UPDATE `product_inventory` SET `qty_item` = qty_item-$pqty , `updateTime` = '$date' WHERE `qty_product_id` = '$pId'";
            $this->dbF->setRow($sql);

            if($this->dbF->rowCount>0) {
                $desc = _replace('{{qty}}', $pqty, $_e['Product Deduct QTY {{qty}}']);
                $this->functions->setlog(_uc($_e['Product Deduct']), _uc($_e['Product QTY']), $pId, $desc);
                echo '1';
            }else{
                echo "0";
            }

        }
        else{
            echo '0';
        }

    }


    public function directStockQTYAdd(){
        global $_e;
        $id     =   $_POST['id'];
        $pqty   =   intval($_POST['pqty']);

        $sql    =   "UPDATE product_inventory SET qty_item = qty_item+$pqty WHERE qty_pk = '$id'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            echo '1';
            $desc= _replace('{{qty}}',$pqty,$_e['Product Add QTY {{qty}}']);
            $this->functions->setlog(_uc($_e['Product Add']),_uc($_e['Product QTY From Stock']),$id,$desc);
        }else{
            echo '0';
        }
    }

    public function directStockQTYRemove(){
        global $_e;
        $id     =   $_POST['id'];
        $pqty   =   intval($_POST['pqty']);

        $sql    =   "SELECT qty_item FROM  product_inventory WHERE qty_pk = '$id'";
        $data   =   $this->dbF->getRow($sql);
        $oldQty =   $data['qty_item'];
        if($oldQty<$pqty){
            echo '0';
            exit;
        }

        $sql    =   "UPDATE product_inventory SET qty_item = qty_item-$pqty  WHERE qty_pk = '$id'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            echo '1';
            $desc = _replace('{{qty}}', $pqty, $_e['Product Deduct QTY {{qty}}']);
            $this->functions->setlog(_uc($_e['Product Deduct']), _uc($_e['Product QTY From Stock']), $id, $desc);
        }else{
            echo '0';
        }
    }

    public function directStockLocationAdd(){
        global $_e;
        $id         =   $_POST['id'];
        $location   =   $_POST['location'];

        $sql    =   "UPDATE product_inventory SET location = ?  WHERE qty_pk = '$id'";
        $this->dbF->setRow($sql,array($location));
        if($this->dbF->rowCount>0){
            echo '1';
            $desc = $_e['Product Stock Location Update'];
            $this->functions->setlog(_uc($_e['Product Deduct']), _uc($_e['Product Stock Location Update']), $id, "$desc <br> Product : $id");
        }else{
            echo '0';
        }
    }

}
?>