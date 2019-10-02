<?php
require_once (__DIR__."/../../global.php"); //connection setting db
class reviews extends object_class{
    public $productF;
    public $imageName;
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
        $_w['Manage Reviews'] = '';
        $_w['Verify Reviews'] = '';
        $_w['Not Verify'] = '';
        $_w['UnVerify Reviews'] = '';

        $_w['Manage Questions'] = '';
        $_w['Verify Questions'] = '';
        $_w['UnVerify Questions'] = '';

        $_w['SNO']  =   '';
        $_w['ACTION'] = '';
        $_w['Active'] = '';
        $_w['Active Review'] = '';
        $_w['DeActive'] = '';
        $_w['DeActive Review'] = '';
        $_w['Delete Review'] = '';
        $_w['Delete Question'] = '';
        $_w['Edit Question'] = '';
        $_w['Delete Fail Please Try Again.'] = '';
        $_w['Name'] = '';
        $_w['Update'] = '';
        $_w['COMMENT'] = '' ;
        $_w['TIME'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['LINK'] = '' ;
        $_w['SUBJECT'] = '' ;
        $_w['USER NAME'] = '' ;
        $_w['SNO'] = '' ;
        $_w['Delete Review'] = '' ;
        $_w['Review'] = '' ;
        $_w['Review Delete Successfully'] = '' ;
        $_w['Are You Sure You Want TO Update?'] = '' ;
        $_w['Update Fail Please Try Again.'] = '' ;
        $_w['Review Status Update Successfully'] = '' ;
        $_w['Update Review'] = '' ;
        $_w['PAGE LINK'] = '' ;

        $_w['Subject']  =   '';
        $_w['Question']  =   '';
        $_w['Reply']  =   '';
        $_w['SAVE']  =   '';
        $_w['Question']  =   '';
        $_w['Question Update Failed,Please Enter Correct Values']  =   '';
        $_w['Question Update Successfully']  =   '';
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,"Reviews Management");
    }

    public function reviewEditSubmit(){
        if(isset($_POST['submit'])  && isset($_POST['editId'])){
            if(!$this->functions->getFormToken('editQuestion')){return false;}

            global $_e;
            $lastId   =     $_POST['editId'];
            $return   =     $this->functions->formUpdate('reviews',$_POST['insert'],$lastId);

            if($return) {
                //send email to client
                $email = $this->functions->webUserName($_POST['userId'],'acc_email');
                $array_mail['subject']  = $_POST['insert']['subject'];
                $array_mail['question'] = $_POST['insert']['comment'];
                $array_mail['reply']    = $_POST['insert']['reply'];
                $this->functions->send_mail($email,'','','askQuestion','',$array_mail);

                $this->functions->notificationError(_js(_uc($_e['Question'])), _js(_uc($_e['Question Update Successfully'])), 'btn-success');
                $this->functions->setlog(_uc($_e['Update']), _uc($_e['Question']), $lastId, _uc($_e['Question Update Successfully'])."Id:$lastId");
            }else{
                $this->functions->notificationError(_js(_uc($_e['Question'])),_js(_uc($_e['Question Update Failed,Please Enter Correct Values'])),'btn-danger');
            }
        }
    }

    public function reviewEdit(){
        global $_e;
        $id     = $_GET['edit'];
        $sql    = "SELECT * FROM reviews WHERE id= '$id'";
        $data   =  $this->dbF->getRow($sql);

        if(empty($data)){return "";}

        $token = $this->functions->setFormToken('editQuestion', false);
        $form_fields = array();

        $form_fields[] = array(
            'type'  => 'none',
            'format' => $token,
        );

        $form_fields[] = array(
            'name'  => 'editId',
            'value' => @$id,
            'type'  => 'hidden',
        );

        $formTemp = $data['user_id'];
        $form_fields[] = array(
            'name'  => 'userId',
            'value' =>$formTemp,
            'type'  => 'hidden',
        );


        $formTemp = $data['subject'];
        $form_fields[] = array(
            'label' => _uc($_e['Subject']),
            'name'  => 'insert[subject]',
            'value' => $formTemp,
            'type'  => 'text',
            'class' => 'form-control',
        );

        $formTemp = $data['comment'];
        $form_fields[] = array(
            'label' => _uc($_e['Question']),
            'name'  => 'insert[comment]',
            'value' => $formTemp,
            'type'  => 'textarea',
            'class' => 'form-control',
        );

        $formTemp = $data['reply'];
        $form_fields[] = array(
            'label' => _uc($_e['Reply']),
            'name'  => 'insert[reply]',
            'value' => $formTemp,
            'type'  => 'textarea',
            'class' => 'form-control',
        );

        $form_fields[]  = array(
            "name"  => 'submit',
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'value' => _u($_e['SAVE']),
            'thisFormat' => '{{form}}'
        );

        $form_fields['form']  = array(
            'type'      => 'form',
            'class'     => "form-horizontal",
            'action'   => '-'.$this->functions->getLinkFolder().'?page=questions',
            'method'   => 'post',
            'format'   => '{{form}}'
        );

        $format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);


    }



    public function webQuestionsView(){
        $sql  = "SELECT * FROM reviews WHERE status= '1' AND type = 'question' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->webQuestionPrint($data);
    }
    public function webQuestionsPending(){
        $sql  = "SELECT * FROM reviews WHERE status= '0'  AND type = 'question' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->webQuestionPrint($data);
    }




    public function webQuestionPrint($data){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'._u($_e['SNO']).'</th>
                        <th>'._u($_e['USER NAME']).'</th>';
        echo "<th>"._u($_e['SUBJECT'])."</th>";

        echo    '    <th>'._u($_e['COMMENT']).'</th>
                     <th>'._u($_e['PAGE LINK']).'</th>
                     <th>'._u($_e['TIME']).'</th>
                     <th>'._u($_e['ACTION']).'</th>
                    </thead>
                <tbody>';

        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];

            $sql        =   "SELECT * FROM `accounts_user` WHERE acc_id = '$val[user_id]'";
            $userData   =   $this->dbF->getRow($sql);
            if($this->dbF->rowCount>0){
                $name       =   $userData['acc_name'];
                $name       =   "<a href='".WEB_ADMIN_URL."/-webUsers?page=edit&userId=$val[user_id]' class='btn btn-primary' target='_blank'>$name</a>";
            }else{
                $name       =   "Anonymox";
                $email      =   "";
            }


            echo "<tr>
                    <td>$i</td>
                    <td>$name</td>";

                echo "<td>$val[subject]</td>";

            $comment    = htmlentities($val['comment']);
            $place      = $val['place'];
            if($place == ""){
                $place = '/';
            }
            $pageLink = WEB_URL.$place;
            if(stristr($place,"?") || stristr($place,"data")){
                $pageLink.= "&askQuestionAll&askQuestionId=$id#askQuestion_$id";
            }else{
                $pageLink.= "?askQuestionAll&askQuestionId=$id#askQuestion_$id";
            }
            echo "  <td>$comment</td>
                    <td><a href='$pageLink' target='_blank'>$pageLink</a></td>
                    <td>$val[dateTime]</td>";

            $active = "<a data-id='$id' data-val='0' onclick='activeReview(this);' class='btn' title='".$_e['DeActive Review']."'>
                                <i class='glyphicon glyphicon-thumbs-down trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            //UnVerify
            if($val['status']=='0'){
                $active = "<a data-id='$id' data-val='1' onclick='activeReview(this);' class='btn' title='".$_e['Active Review']."'>
                                <i class='glyphicon glyphicon-thumbs-up trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }

            echo "<td>
                        <div class='btn-group btn-group-sm'>
                            $active

                            <a href='-".$this->functions->getLinkFolder(false)."&edit=$id' class='btn'   title='".$_e['Edit Question']."'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>

                            <a data-id='$id' onclick='deleteReview(this);' class='btn'   title='".$_e['Delete Question']."'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }

    public function webReviewsView(){
        $sql  = "SELECT * FROM reviews WHERE status= '1' AND type != 'question' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->webReviewsPrint($data);
    }

    public function webReviewsPending(){
        $sql  = "SELECT * FROM reviews WHERE status= '0'  AND type != 'question' ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        $this->webReviewsPrint($data);
    }
    public function webReviewsPrint($data){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'._u($_e['SNO']).'</th>
                        <th>'._u($_e['USER NAME']).'</th>';

        $subject = false;
        if($this->functions->developer_setting('$subject')=='1'){
            $subject = true;
            echo "<th>"._u($_e['SUBJECT'])."</th>";
        }


        $link = false;
        if($this->functions->developer_setting('review_link')=='1'){
            $link = true;
            echo "   <th>"._u($_e['LINK'])."</th>";
        }

        echo    '    <th>'._u($_e['COMMENT']).'</th>
                     <th>'._u($_e['PAGE LINK']).'</th>
                     <th>'._u($_e['TIME']).'</th>
                     <th>'._u($_e['ACTION']).'</th>
                    </thead>
                <tbody>';

        $i = 0;
        foreach($data as $val){
            $i++;
            $id = $val['id'];

            $sql        =   "SELECT * FROM `accounts_user` WHERE acc_id = '$val[user_id]'";
            $userData   =   $this->dbF->getRow($sql);
            if($this->dbF->rowCount>0){
                $name       =   $userData['acc_name'];
                $name       =   "<a href='".WEB_ADMIN_URL."/-webUsers?page=edit&userId=$val[user_id]' class='btn btn-primary' target='_blank'>$name</a>";
            }else{
                $name       =   "Anonymox";
                $email      =   "";
            }


            echo "<tr>
                    <td>$i</td>
                    <td>$name</td>";

            if($subject){
                echo "<td>$val[subject]</td>";
            }
            if($link){
                echo "<td>$val[text2]</td>";
            }

            $comment    = htmlentities($val['comment']);
            $place      = $val['place'];
            if($place == ""){
                $place = '/';
            }
            $pageLink = WEB_URL.$place;
            if(stristr($place,"?") || stristr($place,"data")){
                $pageLink.= "&reviewAll&reviewId=$id#review_$id";
            }else{
                $pageLink.= "?reviewAll&reviewId=$id#review_$id";
            }
            echo "  <td>$comment</td>
                    <td><a href='$pageLink' target='_blank'>$pageLink</a></td>
                    <td>$val[dateTime]</td>";

            $active = "<a data-id='$id' data-val='0' onclick='activeReview(this);' class='btn' title='".$_e['DeActive Review']."'>
                                <i class='glyphicon glyphicon-thumbs-down trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            //UnVerify
            if($val['status']=='0'){
                $active = "<a data-id='$id' data-val='1' onclick='activeReview(this);' class='btn' title='".$_e['Active Review']."'>
                                <i class='glyphicon glyphicon-thumbs-up trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }

            echo "<td>
                        <div class='btn-group btn-group-sm'>
                            $active

                            <a data-id='$id' onclick='deleteReview(this);' class='btn'   title='".$_e['Delete Review']."'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }


        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }

    public function deleteReview(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];

            $sql2="DELETE FROM reviews WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Delete Review']),_uc($_e['Review']),$id,_uc($_e['Review Delete Successfully']));
        }catch (PDOException $e) {
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }



    public function activeReview(){
        global $_e;
        try{
            $this->db->beginTransaction();
            $id=$_POST['id'];
            $verify = $_POST['val'];

            $sql2="UPDATE reviews SET status = '$verify' WHERE id='$id'";
            $this->dbF->setRow($sql2,false);
            if($this->dbF->rowCount) echo '1';
            else echo '0';

            $this->db->commit();
            $this->functions->setlog(_uc($_e['Update Review']),_uc($_e['Review']),$id,_uc($_e['Review Status Update Successfully']).', Id:'.$id);
        }catch (PDOException $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

}
?>