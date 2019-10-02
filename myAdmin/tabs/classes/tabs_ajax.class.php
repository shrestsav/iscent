<?php
require_once (__DIR__."/../../global_ajax.php"); //connection setting db
class tabs_ajax extends object_class{
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
        //ajax class
        $_w['Delete'] = '' ;
        $_w['Slider'] = '' ;
        $_w['Slider Deleted Successfully'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin FAQ management');
    }

public function deletetabs(){
    global $_e;
    try{
        $this->db->beginTransaction();

        $id=$_POST['id'];

        $sql3="SELECT image FROM tabs WHERE id='$id'";
        $data=$this->dbF->getRows($sql3,false);
        foreach($data as $key=>$val){
            @unlink(__DIR__."/../../../images/$val[image]");
        }

        $sql2="DELETE FROM tabs WHERE id='$id'";
       $this->dbF->setRow($sql2,false);
        if($this->dbF->rowCount) echo '1';
        else echo '0';

        $this->db->commit();
        $this->functions->setlog(($_e['Delete']),($_e['Slider']),$id,($_e['Slider Deleted Successfully']));
    }catch (PDOException $e) {
        echo '0';
        $this->db->rollBack();
        $this->dbF->error_submit($e);
    }
}


    public function tabsort(){
        $list=$_POST['album'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `tabs` SET sort='$i' WHERE `id`='$list[$i]'";
            $data=$this->dbF->setRow($sql3);
        }
    }


}
?>