<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class email_ajax extends object_class{
    public $email;
    public function __construct(){
        parent::__construct('3');

        require_once(__DIR__."/email.class.php");
        $this->email = new email();


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        $_w['Delete Email'] = '';
        $_w['Delete Email Group'] = '';
        $_w['Email'] = '';
        $_w['Email Delete Successfully'] = '';
        $_w['Email Group Delete Successfully'] = '';
        $_w['Update Email'] = '';
        $_w['Email'] = '';
        $_w['Email Update Successfully'] = '';
        $_w['Update Email Group']= '';
        $_w['Email Group Update Successfully']= '';
        $_w['Delete News Letter']= '';
        $_w['Email News Letter']= '';
        $_w['Email News Letter Delete Successfully']= '';
        $_w['Delete Email Queue']= '';
        $_w['Email Queue']= '';
        $_w['Email Queue Delete Successfully']= '';
        $_w['Email Queue Status Update Successfully']= '';
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Email');
    }

    public function deleteEmail(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM email_subscribe WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog($_e['Delete Email'],$_e['Email'],$id,$_e['Email Delete Successfully']);
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function deleteGroup(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM email_subscribe WHERE grp='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog($_e['Delete Email Group'],$_e['Email'],$id,$_e['Email Group Delete Successfully']);
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function activeEmail(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];
            $verify = $_POST['val'];

            $sql2="UPDATE email_subscribe SET verify = '$verify' WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog($_e['Update Email'],$_e['Email'],$id,$_e['Email Update Successfully']);
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function emailGrp(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id     =   $_POST['id'];
            $grp =   $_POST['val'];

            $sql2="UPDATE email_subscribe SET `grp` = '$grp' WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog($_e['Update Email Group'],$_e['Email'],$id,$_e['Email Group Update Successfully']);
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function deleteLetter(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM email_letters WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog($_e['Delete News Letter'],$_e['Email News Letter'],$id,$_e['Email News Letter Delete Successfully']);
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function deleteQueue(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql  = "SELECT * FROM email_letter_queue where id ='$id' ";
            $data =  $this->dbF->getRow($sql);
            $letterId   =   $data['letter_id'];
            $grp   =   $data['grp'];


            $sql2="DELETE FROM email_letter_queue WHERE letter_id = '$letterId' AND grp = '$grp'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->email->cronJob();
            $this->functions->setlog($_e['Delete Email Queue'],$_e['Email Queue'],$id,$_e['Email Queue Delete Successfully']);
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function startQueue(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id     = $_POST['id'];
            $val    =   $_POST['val'];

            $sql  = "SELECT * FROM email_letter_queue where id ='$id' ";
            $data =  $this->dbF->getRow($sql);
            $letterId   =   $data['letter_id'];
            $grp   =   $data['grp'];


            $sql2="UPDATE email_letter_queue set status = '$val' WHERE letter_id = '$letterId' AND grp = '$grp'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->email->cronJob();
            $status = "Start";
            if($val=='0'){
                $status = "Stop";
            }
            $this->functions->setlog($_e['Email Queue'],$_e['Email Queue'],$id,$_e['Email Queue Status Update Successfully']." Status : $status, ID : $id");
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }


    public function email_fetch(){
        global $_e;
        $start  = ( isset($_POST['start']) ) ? $_POST['start'] : 0;
        $length = ( isset($_POST['length']) ) ? $_POST['length'] : 10;
        $draw   = ( isset($_POST['draw']) ) ? (int) $_POST['draw'] : null;
        $search = ( isset($_POST['search']) ) ? ($_POST['search']['value']) : null;

        #### Search Query #####
        @$page  = $_GET['page'];
        $verify = " verify = '1'";
        if($page == 'data_ajax_unactive_email'){
            $verify = " verify = '0'";
        }

        if($search) { $search_sql = " (`email` LIKE '%$search%' OR
                                        `name` LIKE '%$search%' OR
                                        `grp` LIKE '%$search%') AND $verify "; } else { $search_sql = " $verify"; }

        ############# GET TOTAL ROWS #############
        $search_w = !empty($search_sql) ? " WHERE ". $search_sql : "";
        $sql = "SELECT `id` FROM `email_subscribe` ".$search_w;
        $data = $this->dbF->getRows($sql);
        $recordsTotal = $this->dbF->rowCount;

        $sql = "SELECT * FROM `email_subscribe` $search_w ORDER BY id DESC LIMIT $start,$length ";

        ###### Get Data ######
        $data = $this->dbF->getRows($sql);

        $columns = array();
        if($draw == 1){ $draw - 1; }

        $columns["draw"] =$draw+1;
        $columns["recordsTotal"] = $recordsTotal; //total record,
        $columns["recordsFiltered"] = $recordsTotal; //filter record, same as total record, then next button will appear

        $i = $start;
        foreach($data as $key => $val){
            $i++;
            $id = $val['id'];

            $grpOption  =   $this->email->emailGrpOption($val['grp']);
            $group      = "<div class='btn-group grpDiv btn-group-sm  col-sm-12' data-id='$id'>
                                <select class='form-control emailGrp col-sm-10' onchange='emailGroup(this);' style='width: 80%'>
                                    $grpOption
                                </select>
                                <div class='col-sm-2' style='padding: 8px 0'>
                                    <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                                </div>
                                <div class='col-sm-12 padding-0 emailOtherGrp displaynone' style='padding: 8px 0'>
                                    <div class='col-sm-8 padding-0'>
                                        <input type='text' class='form-control emailOtherInput' style='width: 100%'/>
                                    </div>
                                    <div class='col-sm-4 padding-0'>
                                        <button class='btn btn-sm btn-primary emailOtherButton' onclick='emailOtherGroup(this)' type='button'>". _uc($_e['Update']) ."</button>
                                    </div>
                                </div>
                            </div>";

            $action = "<div class='btn-group btn-group-sm'>
                            <a data-id='$id' data-val='0' onclick='activeEmail(this);' class='btn'   title='". $_e['DeActive Email'] ."'>
                                <i class='glyphicon glyphicon-thumbs-down trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                            <a data-id='$id' onclick='deleteEmail(this);' class='btn'   title='". $_e['Delete Email'] ."'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>";
            if($page == 'data_ajax_unactive_email') {
                $action = "<div class='btn-group btn-group-sm'>
                            <a data-id='$id' data-val='1' onclick='activeEmail(this);' class='btn'  title='" . $_e['Active Email'] . "'>
                                <i class='glyphicon glyphicon-thumbs-up trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                            <a data-id='$id' onclick='deleteEmail(this);' title='" . $_e['Delete'] . "' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>";
            }

            //6 columns
            $columns["data"][$key] = array(
                $i,
                "$val[email]",
                "$val[dateTime]",
                "$val[grp]",
                $group,
                $action
            );
        }
        if($recordsTotal =='0'){
            $columns["data"] = array();
        }
        //Jason Encode
        echo json_encode( $columns );
    }

}
?>