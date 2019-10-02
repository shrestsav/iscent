<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class webusers_ajax extends object_class{
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
        $_w["Delete WebUser"]    =   "";
        $_w["Update WebUser"]    =   "";
        $_w["WebUser Update Successfully"]    =   "";
        $_w["WebUser Delete Successfully"]    =   "";
        $_w["WebUser"]    =   "";
        $_w["Delete AdminUser"]    =   "";
        $_w["Delete UserGroup"]    =   "";
        $_w["Admin User Group"]    =   "";
        $_w["Admin User Group Delete Successfully"]    =   "";
        $_w["AdminUser Update Successfully"]    =   "";
        $_w["AdminUser"]    =   "";
        $_w["Update AdminUser"]    =   "";
        $_w["AdminUser Delete Successfully"]    =   "";
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,"Users Management");
    }

    public function deleteWebuser(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM accounts_user WHERE acc_id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Delete WebUser']),_uc($_e['WebUser']),$id,_uc($_e['WebUser Delete Successfully']));
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function deleteAdminUser(){
        try{
            global $_e;
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM accounts WHERE acc_id='$id' AND acc_role != '0'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Delete AdminUser']),_uc($_e['AdminUser']),$id,_uc($_e['AdminUser Delete Successfully']));
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }
    public function activeWebUser(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];
            $verify = $_POST['val'];

            $sql2="UPDATE accounts_user SET acc_type = '$verify' WHERE acc_id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Update WebUser']),_uc($_e['WebUser']),$id,_uc($_e['WebUser Update Successfully']));
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function activeSponsor(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];
            $verify = $_POST['val'];

            $sql2="UPDATE accounts_user SET acc_role = '$verify' WHERE acc_id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Update WebUser']),_uc($_e['WebUser']),$id,_uc($_e['WebUser Update Successfully']));
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }


    public function activeAdminUser(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];
            $verify = $_POST['val'];

            $sql2="UPDATE accounts SET acc_type = '$verify' WHERE acc_id='$id' AND acc_role != '0'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Update AdminUser']),_uc($_e['AdminUser']),$id,_uc($_e['AdminUser Update Successfully']));
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function deleteAdminGrp(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM accounts_prm_grp WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Delete UserGroup']),_uc($_e['Admin User Group']),$id,_uc($_e['Admin User Group Delete Successfully']));
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }
}
?>