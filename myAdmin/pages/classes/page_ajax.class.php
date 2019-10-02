<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class pages_ajax extends object_class{
    public function __construct(){
        parent::__construct('3');
    }

public function deletePage(){
    try{
        $this->db->beginTransaction();

        $id=$_POST['id'];

        $sql3="SELECT page_banner FROM pages WHERE page_pk='$id'";
        $data=$this->dbF->getRows($sql3,false);
        foreach($data as $key=>$val){
            @unlink(__DIR__."/../../../images/$val[page_banner]");
        }

        $del_sql = " DELETE FROM `pages` WHERE `page_pk` = ? ";
        $stmt = $this->db->prepare($del_sql);
        $stmt->execute( array($id) );
        $stmt->rowCount();
        // var_dump($stmt->rowCount());
        $this->functions->setting_fieldsDelete($id,'pages',false);
        if($stmt->rowCount()) echo '1';
        else echo '0';

        // ### This is not working, echo 0 happens, page is deleted but js gives error because 0 is output below instead on 1
        // $sql2="DELETE FROM pages WHERE page_pk='$id'";
        // $this->dbF->setRow($sql2,false);
        // $this->functions->setting_fieldsDelete($id,'pages',false);
        // if($this->dbF->rowCount) echo '1';
        // else echo '0';

        $this->db->commit();
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


}
?>