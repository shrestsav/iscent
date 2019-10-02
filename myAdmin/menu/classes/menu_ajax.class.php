<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class menu_ajax extends object_class{
    public function __construct(){
        parent::__construct('3');
    }

    public function deleteMenu($id=false){

        if($id==false){
            $first = true;
            $id = $_POST['id'];
        }else{
            $first = false;
        }
        $sql = "SELECT * FROM webmenu WHERE  under = '$id'";
        $data = $this->dbF->getRows($sql);
        $count = $this->dbF->rowCount;
        if(!$count){
            $sql = "DELETE FROM webmenu WHERE  id = '$id'";
            $this->dbF->setRow($sql);
            //echo '1delete : '.$id;
            if($first){
                echo '1';
            }
            return false;
        }
        else{
            //echo 'cont : '.$id;
        }
        foreach($data as $val){
            $this->deleteMenu($val['id']);
        }
        $sql = "DELETE FROM webmenu WHERE  id = '$id'";
        $this->dbF->setRow($sql);
            if($this->dbF->rowCount>0){
                echo '1';
            }else{
                echo '0';
            }
    }


    public function menuSort(){
        $list=$_POST['menu'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `webmenu` SET sort='$i' WHERE `id`='$list[$i]'";
            $data=$this->dbF->setRow($sql3);
        }
    }


    public function deleteFooterMenu($id=false){

        if($id==false){
            $first = true;
            $id = $_POST['id'];
        }else{
            $first = false;
        }
        $sql = "SELECT * FROM webfootermenu WHERE  under = '$id'";
        $data = $this->dbF->getRows($sql);
        $count = $this->dbF->rowCount;
        if(!$count){
            $sql = "DELETE FROM webfootermenu WHERE  id = '$id'";
            $this->dbF->setRow($sql);
            //echo '1delete : '.$id;
            if($first){
                echo '1';
            }
            return false;
        }
        else{
            //echo 'cont : '.$id;
        }
        foreach($data as $val){
            $this->deleteFooterMenu($val['id']);
        }
        $sql = "DELETE FROM webfootermenu WHERE  id = '$id'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            echo '1';
        }else{
            echo '0';
        }
    }


    public function footerMenuSort(){
        $list=$_POST['menu'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `webfootermenu` SET sort='$i' WHERE `id`='$list[$i]'";
            $data=$this->dbF->setRow($sql3);
        }
    }

}
?>