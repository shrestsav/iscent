<?php



require_once (__DIR__."/../../global_ajax.php"); //connection setting db



class support_ajax extends object_class{



    public function __construct(){



        parent::__construct('3');



    }


public function getUserMessages(){
    $id = $_POST['user_id'];

    $sql_msg = "SELECT * FROM `user_messages` WHERE `user_id` = ? ORDER BY `date` ASC";
    $res = $this->dbF->getRows($sql_msg, array($id));

    // $this->dbF->prnt($res);

    if($this->dbF->rowCount > 0){
        $result = '';
        $dummy_img = WEB_URL.'/webImages/dummy.png';

        foreach ($res as $key => $value) {
            $user_id        = $value['user_id'];
            $message_by     = $value['message_by'];

            if($id == $user_id && $message_by == 'user'){
                $result .= '<div class="container darker" style="width: 100%">
                              <img src="'.$dummy_img.'" alt="Avatar" class="right" style="width:100%;">
                              <p>'.$value['message'].'</p>
                              <span class="time-left">'.$value['date'].'</span>
                            </div>';
            }
            else if($id == $user_id && $message_by == 'admin'){
                $result .= '<div class="container" style="width: 100%">
                              <img src="'.$dummy_img.'" alt="Avatar" class="left" style="width:100%;">
                              <p>'.$value['message'].'</p>
                              <span class="time-left">'.$value['date'].'</span>
                            </div>';
            }
        }
    }
    echo $result;
}

public function sendUserMessage(){

    $message_cUser = $_POST['message_cUser'];
    $message_text  = $_POST['message_text'];
    $cur_date = date('Y-m-d H:i:s');
    $dummy_img = WEB_URL.'/webImages/dummy.png';

    $sql = "INSERT INTO `user_messages`(`user_id`, `message`, `message_by`, `date`) VALUES (?,?,?,?)";
    $this->dbF->setRow($sql, array($message_cUser, $message_text, 'admin', $cur_date));

    

    if($this->dbF->rowCount > 0){





$sql        =   "SELECT * FROM `accounts_user` WHERE acc_id = '$message_cUser'";
$userData   =   $this->dbF->getRow($sql);
if($this->dbF->rowCount>0){
// $name       =   $userData['acc_name'];
$email      =   $userData['acc_email'];
}else{
// $name       =   "Anonymox";
$email      =   "";
}





        // $to =  $this->functions->ibms_setting('Email');
$this->functions->send_mail($email,"iScent- Message Reply By Admin", $message_text);


        $result = '<div class="container" style="width: 100%">
                  <img src="'.$dummy_img.'" alt="dummi" class="right" style="width:100%;">
                  <p>'.$message_text.'</p>
                  <span class="time-left">'.$cur_date.'</span>
                </div>';

        
    }else{
        $result = '';
    }


    // echo $result;
    $return_array['ret'] = $result;
    echo json_encode($return_array);

}





public function deleteDaily(){



    try{



        $this->db->beginTransaction();







        $id=$_POST['id'];



        $del_sql = " DELETE FROM `daily_book` WHERE `id` = ? ";



        $stmt = $this->db->prepare($del_sql);



        $stmt->execute( array($id) );



        $stmt->rowCount();



        // var_dump($stmt->rowCount());



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