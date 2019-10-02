<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class seo_ajax extends object_class{
    public function __construct(){
        parent::__construct('3');

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        $_w['SEO Delete Successfully'] = '' ;
        $_w['SEO'] = '' ;
        $_w['DELETE'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin SEO');

    }

public function deleteSeo(){
    global $_e;
    try{
       $this->db->beginTransaction();

       $id=$_POST['id'];
       $sql2="DELETE FROM seo WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog(_uc($_e['DELETE']),_uc($_e['SEO']),$id,_uc($_e['SEO Delete Successfully']));
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


}
?>