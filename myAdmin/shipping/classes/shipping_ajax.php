<?php

require_once (__DIR__."/../../global_ajax.php"); //connection setting db



class shipping_ajax extends object_class{
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
        $_w['SHIPPING'] = '' ;
        $_w['Shipping Delete Successfully'] = '' ;
        $_w['DELETE'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Shipping');
    }


    public function deleteShipping(){
        global $_e;
        try{
            $this->db->beginTransaction();

            $id=$_POST['id'];
            $sql2="DELETE FROM shipping WHERE shp_from='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['DELETE']),_uc($_e['SHIPPING']),$id,_uc($_e['Shipping Delete Successfully']));
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }




}
?>