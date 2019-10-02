<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class setting_ajax extends object_class{
    public function __construct(){
        parent::__construct('3');
    }

public function deleteHardWord(){
    try{
        $this->db->beginTransaction();

        $id=$_POST['id'];

       $sql2="DELETE FROM hardwords WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog('DELETE','Special Words',$id,'Special Words Delete Successfully');
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


}
?>