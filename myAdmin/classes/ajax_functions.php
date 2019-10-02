<?php

require_once (__DIR__."/../global_ajax.php"); //connection setting db



class ajax_functions extends object_class{


    public function  __construct()
    {
        parent::__construct('3');
    }
    public function productImage($id,$name){
        $sql="INSERT INTO `product_image`(`product_id`,`image`) values($id,'$name')";
        $this->dbF->setRow($sql);
    }


    public function defectImage($id,$name){
        $sql="INSERT INTO `defect_image` (`defect_id`,`image`) values ($id,'$name')";
        $this->dbF->setRow($sql);
    }

    public function albumImage($id,$name){
        $sql="INSERT INTO `gallery_images` (`gallery_id`,`image`) values ($id,'$name')";
        $this->dbF->setRow($sql);
    }



}
?>