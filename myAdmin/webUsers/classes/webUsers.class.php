<?php

require_once (__DIR__."/../../global.php"); //connection setting db

class webUsers extends object_class{

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

        $_w['Add New'] = '' ;

        $_w['Profile Add Successfully!'] = '' ;

        $_w['Profile Add Failed!'] = '' ;

        $_w['Employee/User Add fail please try again.!'] = '' ;

        $_w['Duplicate Email, User Already Exist.'] = '' ;



        $_w['Manage WebUsers'] = '';

        $_w['Verify Users'] = '';

        $_w['Not Verify'] = '';

        $_w['UnVerify Users'] = '';

        $_w['SNO']  =   '';

        $_w['USER NAME'] ='';

        $_w['Account Create'] = '';

        $_w['Account Status'] = '';

        $_w['Account Type'] = '';

        $_w['ACTION'] = '';

        $_w['Active'] = '';

        $_w['Active User'] = '';

        $_w['Address'] = '';

        $_w['Admin Update with Name : {{name}}'] = '';

        $_w['Admin User Group'] = '';

        $_w['Admin Users'] = '';

        $_w['adminUser Update fail please try again.!'] = '';

        $_w['adminUser Update fail please try again.! Or Duplicate Email.'] = '';

        $_w['Are You Sure You Want TO Update?'] = '';

        $_w['Back To WebUsers'] = '';

        $_w['Back To AdminUsers'] = '';

        $_w['Back To AdminGroups'] = '';

        $_w['City'] = '';

        $_w['Country'] = '';

        $_w['Admin Panel'] = '';

        $_w['Date Of Birth'] = '';

        $_w['DeActive'] = '';

        $_w['DeActive User'] = '';

        $_w['DeActive Users'] = '';

        $_w['Delete Email'] = '';

        $_w['Delete Fail Please Try Again.'] = '';

        $_w['Delete Group'] = '';

        $_w['Delete User'] = '';

        $_w['Draft Users'] = '';

        $_w['Edit User Info'] = '';

        $_w['Email'] = '';

        $_w['Edit User Group Permissions'] = '';

        $_w['Female'] = '';

        $_w['Gender'] = '';

        $_w['Groups'] = '';

        $_w['Group add'] = '';

        $_w['Group Name'] = '';

        $_w['GROUP NAME'] = '';

        $_w['Group Update'] = '';

        $_w['Invalid Email Address! Or Some Thing Went Wrong'] = '';

        $_w['ITEMS IN CART'] = '';

        $_w['Last Update'] = '';

        $_w['Male'] = '';

        $_w['Manage AdminUsers'] = '';

        $_w['Manage Admin Groups'] = '';

        $_w['Name'] = '';

        $_w['New Admin Add with Name : {{name}}'] = '';

        $_w['New Admin User group Add with name : {{name}}'] = '';

        $_w['New AdminUser'] = '';

        $_w['New Group'] = '';

        $_w['New Group Add Failed'] = '';

        $_w['New Group Add Successfully'] = '';

        $_w['New Group Update Failed'] = '';

        $_w['New Group Update Successfully'] = '';

        $_w['Not Access'] = '';

        $_w['New Users'] = '';

        $_w['ORDER CANCEL'] = '';

        $_w['ORDER DONE'] = '';

        $_w['ORDER PENDING'] = '';

        $_w['ORDER STATUS'] = '';

        $_w['ORDER SUBMIT'] = '';

        $_w['OTHERS STATUS'] = '';

        $_w['OWNER'] = '';

        $_w['Selected SubTotal'] = '';

        $_w['User Orders'] = '';

        $_w['Password'] = '';

        $_w['Password Not Matched!'] = '';

        $_w['Password Required!'] = '';

        $_w['Permissions'] = '';

        $_w['Phone'] = '';

        $_w['Post Code'] = '';

        $_w['Profile Update Failed!'] = '';

        $_w['Profile Update Successfully!'] = '';

        $_w['Read Only'] = '';

        $_w['Read, Write and Delete'] = '';

        $_w['Retype Password'] = '';

        $_w['Save'] = '';

        $_w['This Is Owner Account Please Go Back:'] = '';

        $_w['Update'] = '';

        $_w['Update AdminUser'] = '';

        $_w['Update Fail Please Try Again.'] = '';

        $_w['USER EMAIL'] = '';

        $_w['USER FROM'] = '';

        $_w['User Group'] = '';

        $_w['Users'] = '';

        $_w['WebUsers'] = '';

        $_w['WebUser Update fail please try again.!'] = '';

        $_w['Write Only'] = '';

        $_w['WebUsers Management'] = '';

        $_w['Page Not Found.'] = '';

        $_w['Admin User'] = '';

        $_w['Admin User group Update with name : {{name}}'] = '';

        $_w['Make Sponsor'] = '' ;

        $_w['DeActive Sponsor'] = '' ;

        $_w['Are You Sure You Want TO Change Sponsor Status?'] = '' ;

        $_w['Employee'] = '' ;

        $_w['Yes'] = '' ;

        $_w['No'] = '' ;

        $_w['Designation'] = '' ;

        $_w['Interests'] = '' ;

        $_w['Image'] = '' ;

        $_w['Basic'] = '' ;

        $_w['User Type'] = '' ;

        $_w['Gold'] = '' ;

        $_w['Platinum'] = '' ;

        $_w['Sort Position'] = '' ;

        $_w['ORDER IN PROCESS'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,"Users Management");

    }



    public function userSelectOptionList(){

        //Payment type select box create

        $sql  = "SELECT acc_id,acc_name,acc_email FROM accounts_user WHERE acc_type = '1' ORDER BY acc_name ASC ";

        $data =  $this->dbF->getRows($sql);

        $option='';

        foreach($data as $key=>$val){

            $option.= "<option value='$val[acc_id]'>$val[acc_name] -- ($val[acc_email])</option>";

        }

        return $option;

    }



    public function webUsersView(){

        global $_e;

        echo '<div class="table-responsive">

                <table class="table table-hover dTable tableIBMS">

                    <thead>

                        <th>'._u($_e['SNO']).'</th>

                        <th>'._u($_e['USER NAME']).'</th>

                        <th>'._u($_e['USER EMAIL']).'</th>';



        $product = false;

        if($this->functions->developer_setting('cartSystem')=='1' && $this->functions->developer_setting('product')=='1'){

            $product = true;

            echo "<th>"._u($_e['ORDER SUBMIT'])."</th>";

            echo "<th>"._u($_e['ORDER STATUS'])."</th>";

        }



        echo    '    <th>'._u($_e['USER FROM']).'</th>

                     <th>'._u($_e['ACTION']).'</th>

                    </thead>

                <tbody>';

        $sql  = "SELECT * FROM accounts_user WHERE acc_type = '1' ORDER BY acc_id DESC ";

        $data =  $this->dbF->getRows($sql);

        $i = 0;

        foreach($data as $val){

            $i++;

            $id = $val['acc_id'];

            echo "<tr>

                    <td>$i</td>

                    <td>$val[acc_name]</td>

                    <td>$val[acc_email]</td>";



            if($product){

                $this->functions->require_once_custom('product_functions');

                $productClass   = new product_function();



                $inCart         =   $productClass->userNoOfItemsInCart($id);

                $totalSubmit    =   $productClass->totalOrderSubmit($id);



                $totalDone      =    $productClass->totalOrderSubmit($id,'completed');

                $totalPending   =    $productClass->totalOrderSubmit($id,'process');

                $totalCancel    =    $productClass->totalOrderSubmit($id,'cancelled');

                $total_other    =    $productClass->totalOrderSubmit($id,'incomplete');




                echo "<td>$totalSubmit</td>";

                echo "<td>

                        <div class='btn-group input-group'>

                            <div class='btn btn-success'    title='"._u($_e['ORDER DONE'])."'>$totalDone</div>

                            <div class='btn btn-info'       title='"._u($_e['ORDER IN PROCESS'])."'>$totalPending</div>

                            <div class='btn btn-danger'     title='"._u($_e['ORDER CANCEL'])."'>$totalCancel</div>

                            <div class='btn btn-warning'    title='"._u($_e['OTHERS STATUS'])."'>$total_other</div>

                        </div>

                      </td>";

            }





            echo "  <td>$val[acc_created]</td>

                    <td>

                        <div class='btn-group btn-group-sm'>

                            <a data-id='$id' data-val='0' onclick='activeWebUser(this);' class='btn' title='".$_e['DeActive User']."'>

                                <i class='glyphicon glyphicon-thumbs-down trash'></i>

                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                            </a>



                            <a data-id='$id' href='-webUsers?page=edit&userId=$id' class='btn'>

                                <i class='glyphicon glyphicon-edit'></i>

                            </a>



                            <a data-id='$id' onclick='deleteWebUser(this);' class='btn'   title='".$_e['Delete Email']."'>

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



    public function webUsersPending(){

        global $_e;

        echo '<div class="table-responsive">

                <table class="table table-hover dTable tableIBMS">

                    <thead>

                        <th>'._u($_e['SNO']).'</th>

                        <th>'._u($_e['USER NAME']).'</th>

                        <th>'._u($_e['USER EMAIL']).'</th>

                        <th>'._u($_e['USER FROM']).'</th>

                        <th>'._u($_e['ACTION']).'</th>

                    </thead>

                <tbody>';

        $sql  = "SELECT * FROM accounts_user WHERE acc_type = '0' ORDER BY acc_id DESC ";

        $data =  $this->dbF->getRows($sql);

        $i = 0;

        foreach($data as $val){

            $i++;

            $id = $val['acc_id'];

            echo "<tr>

                    <td>$i</td>

                    <td>$val[acc_name]</td>

                    <td>$val[acc_email]</td>

                    <td>$val[acc_created]</td>

                    <td>

                        <div class='btn-group btn-group-sm'>



                            <a data-id='$id' data-val='1' onclick='activeWebUser(this);' class='btn' title='".$_e['DeActive User']."'>

                                <i class='glyphicon glyphicon-thumbs-up trash'></i>

                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                            </a>



                            <a data-id='$id' href='-webUsers?page=edit&userId=$id' class='btn'>

                                <i class='glyphicon glyphicon-edit'></i>

                            </a>



                            <a data-id='$id' onclick='deleteWebUser(this);' class='btn'   title='".$_e['Delete Email']."'>

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



    public function webUserAddSubmit(){

        global $_e;

        if (isset($_POST['name']) && !empty($_POST['name'])

            && isset($_POST['email']) && !empty($_POST['email'])

            && isset($_POST['oldId']) && $_POST['oldId']==''

        ){



            //if(!$this->functions->getFormToken('webUserAdd')){return false;}

            try {

                $email = strip_tags(strtolower(trim($_POST['email'])));

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {



                    $id     = empty($_POST['oldId']) ? "" : $_POST['oldId'];

                    $name   = empty($_POST['name']) ? "" : $_POST['name'];

                    $pass   = empty($_POST['pass']) ? "" : $_POST['pass'];

                    $rpass  = empty($_POST['rpass']) ? "" : $_POST['rpass'];

                    $type   = empty($_POST['acc_type']) ? 0 : $_POST['acc_type'];



                    if($pass != $rpass){

                        $msg = $_e['Password Not Matched!'];

                        return $msg;

                    }

                    if($pass == ''){

                        $msg = $_e['Password Required!'];

                        return $msg;

                    }



                    $this->db->beginTransaction();

                    $date = date('Y-m-d H:i:s');

                    $sql = "INSERT INTO  accounts_user SET

                                acc_name = ?,

                                acc_email = ?,

                                acc_type = ?,

                                acc_pass = ?,

                                acc_created = '$date'

                                ";



                    $password  =  $this->functions->encode($pass);

                    $array = array($name,$email,$type,$password);

                    $this->dbF->setRow($sql,$array,false);

                    if($this->dbF->hasException){

                        $msg = $_e['Duplicate Email, User Already Exist.'];

                        return $msg;

                    }



                    $lastId = $this->dbF->rowLastId;

                    $setting    = empty($_POST['signUp']) ? array() : $_POST['signUp'];



                    $sql        =   "INSERT INTO `accounts_user_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";

                    $arry       =   array();

                    foreach($setting as $key=>$val){

                        $sql .= "('$lastId',?,?) ,";

                        $arry[]= $key ;

                        $arry[]= $val ;

                    }

                    $sql = trim($sql,",");

                    $this->dbF->setRow($sql,$arry,false);



                } else {

                    $AccLoginInfoT = $_e['Invalid Email Address! Or Some Thing Went Wrong'];

                    $msg = $AccLoginInfoT;

                    return $msg;

                }



                $this->db->commit();

                if($this->dbF->rowCount>0){

                    $msg = $_e['Profile Add Successfully!'];

                }else{

                    $msg = $_e['Profile Add Failed!'];

                }

                return $msg;

            } catch (PDOException $e) {

                $msg = $_e['Employee/User Add fail please try again.!'];

                $this->db->rollBack();

                return $msg;

            }

        }

        return "";



    }



    public function webUserEditSubmit(){

        global $_e;

        if (isset($_POST['name']) && !empty($_POST['name'])

            && isset($_POST['email']) && !empty($_POST['email'])

            && isset($_POST['oldId']) && !empty($_POST['oldId'])

        ){



            if(!$this->functions->getFormToken('webUserEdit')){return false;}

            try {



                $email = strip_tags(strtolower(trim($_POST['email'])));

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {



                    $id     = empty($_POST['oldId']) ? "" : $_POST['oldId'];

                    $name   = empty($_POST['name']) ? "" : $_POST['name'];

                    $pass   = empty($_POST['pass']) ? "" : $_POST['pass'];

                    $rpass  = empty($_POST['rpass']) ? "" : $_POST['rpass'];

                    $type   = empty($_POST['acc_type']) ? 0 : $_POST['acc_type'];



                    $passwordStatus = false;

                    if($pass != $rpass){

                        $msg = $_e['Password Not Matched!'];

                        return $msg;

                    }

                    if($pass != ''){

                        $passwordStatus =true;

                    }





                    $this->db->beginTransaction();

                    $sql = "UPDATE  accounts_user SET

                                acc_name = ?,

                                acc_email = ?,

                                acc_type = ?

                                WHERE acc_id = '$id'";

                    $array = array($name,$email,$type);

                    $this->dbF->setRow($sql,$array,false);



                    if($passwordStatus){

                        $password  =  $this->functions->encode($pass);

                        $sql = "UPDATE  accounts_user SET

                                acc_pass = ?

                                WHERE acc_id = '$id'";

                        $array = array($password);

                        $this->dbF->setRow($sql,$array,false);

                    }



                    $lastId = $id;

                    $setting    = empty($_POST['signUp']) ? array() : $_POST['signUp'];



                    $sql = "DELETE FROM `accounts_user_detail` WHERE id_user= '$id'";

                    $this->dbF->setRow($sql);



                    $sql        =   "INSERT INTO `accounts_user_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";

                    $arry       =   array();

                    foreach($setting as $key=>$val){

                        $sql .= "('$lastId',?,?) ,";

                        $arry[]= $key ;

                        $arry[]= $val ;

                    }

                    $sql = trim($sql,",");

                    $this->dbF->setRow($sql,$arry,false);



                } else {

                    $AccLoginInfoT = $_e['Invalid Email Address! Or Some Thing Went Wrong'];

                    $msg = $AccLoginInfoT;

                    $this->db->rollBack();

                    return $msg;

                }



                $this->db->commit();

                if($this->dbF->rowCount>0){

                    $msg = $_e['Profile Update Successfully!'];

                }else{

                    $msg = $_e['Profile Update Failed!'];

                }

                return $msg;

            } catch (PDOException $e) {

                $msg = $_e['WebUser Update fail please try again.!'];

                $this->db->rollBack();

                return $msg;

            }

        }

        return "";

    }



    public function webUserInfoArray($data,$settingName){

        foreach($data as $val){

            if($val['setting_name']==$settingName){

                return $val['setting_val'];

            }

        }

        return "";

    }





    public function webUserEdit($id ='',$new=false){

        global $_e;

        $edit = false;

        $required = '';

        if($id=='' && $new == false){

            $id     = $_GET['userId'];

        }else if($new == true){

            $token  =    $this->functions->setFormToken('webUserAdd',false);

            $required = " required='required'";

        }





        if($new == false){

            $sql    = "SELECT * FROM accounts_user WHERE acc_id = '$id'";

            $userData   =   $this->dbF->getRow($sql);

            if(!$this->dbF->rowCount){return false;}



            $sql    = "SELECT * FROM accounts_user_detail WHERE id_user = '$id'";

            $userInfo   = $this->dbF->getRows($sql);

            $token  =    $this->functions->setFormToken('webUserEdit',false);

            $edit = true;

        }



        $employeePage = $this->functions->developer_setting('employeePage');





        echo '<form class="form-horizontal" role="form" method="post">'.$token.'

        <input type="hidden" name="oldId" value="'.$id.'"/> ';





        if($employeePage == '1'){

            echo '<div class="form-group">

                    <label class="col-sm-2  control-label"></label>

                    <div class="col-sm-10  ">

                        <img src="' . @$this->webUserInfoArray($userInfo, 'image') . '" class="userImage kcFinderImage"/>

                    </div>

                </div>



                <div class="form-group">

                    <label class="col-sm-2 control-label">' . $_e['Image'] . '</label>

                    <div class="col-sm-10 ">



                        <div class="input-group">

                            <input type="url" name="signUp[image]" value="' . @$this->webUserInfoArray($userInfo, 'image') . '"  class="userImage form-control" placeholder="">

                            <div class="input-group-addon pointer " onclick="' . "openKCFinderImageWithImg('userImage')" . '"><i class="glyphicon glyphicon-picture"></i></div>

                        </div>

                    </div>

                </div>';

        }





        echo '<div class="form-group">

                    <label for="user" class="col-sm-2 control-label">'.$_e['Name'].'</label>



                    <div class="col-sm-10">

                    <!-- patteren not working for sweden lang pattern="[a-zA-z- ]{3,50}"-->

                        <input type="text" class="form-control" name="name" id="user"

                               placeholder="'.$_e['Name'].'" value="'.@$userData['acc_name'].'" required onChange="filter(this); vali()">

                    </div>

                </div>



                <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label" >'.$_e['Email'].'</label>



                    <div class="col-sm-10">

                        <input type="email" class="form-control"  value="'.@$userData['acc_email'].'" name="email" id="inputEmail3" placeholder="'.$_e['Email'].'" required>

                    </div>

                </div>





                <div class="form-group">

                    <label class="col-sm-2 control-label">'.$_e['Gender'].'</label>



                    <div class="col-sm-10">

                        <label class="radio-inline">

                            <input type="radio" class="gender" name="signUp[gender]" value="female">'.$_e['Female'].'

                        </label>

                        <label class="radio-inline">

                            <input type="radio" class="gender" name="signUp[gender]" value="male">'.$_e['Male'].'

                        </label>

                    </div>

                </div>

                <script>

                $(document).ready(function(){

                    $(".gender[value=\''.@strtolower($this->webUserInfoArray($userInfo,'gender')).'\']").attr("checked", true);

                });



                </script>









                <div class="form-group">

                    <label class="col-sm-2 control-label">'.$_e['User Type'].'</label>



                    <div class="col-sm-10">

                        <label class="radio-inline">

                            <input type="radio" class="user_type" name="signUp[user_type]" value="basic">'.$_e['Basic'].'

                        </label>

                        <label class="radio-inline">

                            <input type="radio" class="user_type" name="signUp[user_type]" value="gold">'.$_e['Gold'].'

                        </label>

                        <label class="radio-inline">

                            <input type="radio" class="user_type" name="signUp[user_type]" value="platinum">'.$_e['Platinum'].'

                        </label>

                    </div>

                </div>

                <script>

                $(document).ready(function(){

                    $(".user_type[value=\''.@strtolower($this->webUserInfoArray($userInfo,'user_type')).'\']").attr("checked", true);

                });



                </script>







                ';



        if($employeePage == '1') {

            echo '<div class="form-group">

                    <label class="col-sm-2 control-label">' . $_e['Employee'] . '</label>



                    <div class="col-sm-10">

                        <label class="radio-inline">

                            <input type="radio" class="employee" name="signUp[employee]" value="1">' . $_e['Yes'] . '

                        </label>

                        <label class="radio-inline">

                            <input type="radio" class="employee" name="signUp[employee]" value="0">' . $_e['No'] . '

                        </label>

                    </div>

                </div>

                <script>

                $(document).ready(function(){

                    $(".employee[value=\'' . @strtolower($this->webUserInfoArray($userInfo, 'employee')) . '\']").attr("checked", true);

                });

                </script>



                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Designation'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'designation').'" name="signUp[designation]" placeholder="'.$_e['Designation'].'" >

                    </div>

                </div>





                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Sort Position'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'sort').'" name="signUp[sort]" placeholder="'.$_e['Sort Position'].'" >

                    </div>

                </div>

                ';

        }



        echo '<div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Date Of Birth'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control date"  value="'.@$this->webUserInfoArray($userInfo,'date_of_birth').'" name="signUp[date_of_birth]" placeholder="'.$_e['Date Of Birth'].'" >

                    </div>

                </div>



                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Phone'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'phone').'" name="signUp[phone]" placeholder="'.$_e['Phone'].'" >

                    </div>

                </div>







                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Interests'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'interests').'" name="signUp[interests]" placeholder="'.$_e['Interests'].'" >

                    </div>

                </div>



                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Address'].'</label>



                    <div class="col-sm-10">

                        <textarea class="form-control" name="signUp[address]" placeholder="'.$_e['Address'].'" >'.@$this->webUserInfoArray($userInfo,'address').'</textarea>

                    </div>

                </div>



                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Post Code'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'post_code').'" name="signUp[post_code]" placeholder="'.$_e['Post Code'].'" >

                    </div>

                </div>



                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['City'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'city').'" name="signUp[city]" placeholder="'.$_e['City'].'" >

                    </div>

                </div>



                <div class="form-group">

                    <label class="col-sm-2 control-label" >'.$_e['Country'].'</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'country').'" name="signUp[country]" placeholder="'.$_e['Country'].'" >

                    </div>

                </div>









                <div class="form-group">

                    <label class="col-sm-2 control-label">'.$_e['Account Status'].'</label>



                    <div class="col-sm-10">

                        <label class="radio-inline">

                            <input type="radio" class="gender" name="acc_type" value="1" '. $required .'>'.$_e['Active'].'

                        </label>

                        <label class="radio-inline">

                            <input type="radio" class="gender" name="acc_type" value="0" '. $required .'>'.$_e['DeActive'].'

                        </label>

                    </div>

                </div>

                <script>

                $(document).ready(function(){

                    $(".gender[value=\''.@strtolower($userData['acc_type']).'\']").attr("checked", true);

                });



                </script> ';



        if($edit) {

            echo '<div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label" >' . $_e['Account Create'] . '</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="' . @$userData['acc_created'] . '" readonly>

                    </div>

                </div>



                <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label" >' . $_e['Last Update'] . '</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="' . @$userData['acc_timestamp'] . '" readonly>

                    </div>

                </div>';

        }



        echo '<!--          <div class="form-group">

                    <label class="col-sm-2 control-label" >Account Type</label>



                    <div class="col-sm-10">

                        <input type="text" class="form-control"  value="'.@$this->webUserInfoArray($userInfo,'type').'" name="signUp[type]"  placeholder="Account Type" >

                    </div>

                </div>-->



<hr>



                <div class="form-group ">

                    <label for="pass" class="col-sm-2 control-label">'.$_e['Password'].'</label>



                    <div class="col-sm-10">

                        <input type="password" onChange="passM();" class="form-control" name="pass" '. $required .' id="pass"

                               placeholder="'.$_e['Password'].'" >

                    </div>

                </div>



                <div class="form-group">

                    <label for="rpass" class="col-sm-2 control-label">'.$_e['Retype Password'].'</label>

                    <div class="col-sm-10">

                        <input type="password" onChange="passM();" onkeyup="passM();" class="form-control" '. $required .' name="rpass" id="rpass"

                               placeholder="'.$_e['Retype Password'].'">



                        <div id="pm"></div>

                    </div>

                </div>





                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" name="submit" id="signup_btn" class="btn btn-primary defaultSpecialButton" onClick="subf()">

                               '.$_e['Save'].'

                        </button>

                    </div>

                </div>

            </form>





            ';



    }





    public function newAdminUser($id ='',$action=''){

        global $_e;

        $isEdit = false;

        if($id !=''){

            $sql        = "SELECT * FROM accounts WHERE acc_id = '$id'";

            $userData   =   $this->dbF->getRow($sql);

            if($this->dbF->rowCount)

            {

                $isEdit =true;

                $sql        = "SELECT * FROM accounts_detail WHERE id_user = '$id'";

                $userInfo   = $this->dbF->getRows($sql);

            }

        }else if($id=='' && isset($_GET['userId'])){

            @$id     = $_GET['userId'];



            $sql    = "SELECT * FROM accounts WHERE acc_id = '$id'";

            $userData   =   $this->dbF->getRow($sql);

            if($this->dbF->rowCount)

            {

                if($userData['acc_role']=='0'){

                    //Will not work just for security

                    echo $_e['This Is Owner Account Please Go Back:'];

                    return false;

                }

                $isEdit =   true;

                $sql    =   "SELECT * FROM accounts_detail WHERE id_user = '$id'";

                $userInfo   = $this->dbF->getRows($sql);

                $pagePermission = $this->functions->pagePermissionStatus();

                if($pagePermission===true || $pagePermission==='3'){}

                else if($pagePermission==='2' && $_SESSION['_uid']==$id){}

                else{

                    return false;

                }

            }else{

                $id = '';

            }

        }



        if($id ==''){

            $token  =    $this->functions->setFormToken('adminUserNew',false);

        }else{

            $token  =    $this->functions->setFormToken('adminUserEdit',false);

        }





        $form_fields = array();



        $form_fields[] = array(

            'type' => 'none',

            'thisFormat' => "<div class='col-sm-12 padding-0'>"

        );



        $form_fields[] = array(

            'type' => 'none',

            'format' => $token

        );



        $form_fields[] = array(

            'name' => 'oldId',

            'value' => "$id",

            'type' => 'hidden',

        );







        $form_fields[] = array(

            'label' => $_e['Name'],

            'name' => 'name',

            'value' => @$userData['acc_name'],

            'type' => 'text',

            'class' => 'form-control',

            'required' => 'true',

            'id'        => 'user',

            'data' => 'onChange="filter(this); vali()"'

        );



        $form_fields[] = array(

            'label' => $_e['Email'],

            'name' => 'email',

            'value' => @$userData['acc_email'],

            'type' => 'email',

            'class' => 'form-control',

            'required' => 'true',

            'id'        => 'inputEmail3',

            'data' => 'onChange="filter(this); vali()"'

        );



        $form_fields[] = array(

            'label' => $_e['User Group'],

            'type' => 'none',

            'format' => '<select name="acc_grp" class="acc_grp form-control"  required>

                            '.$this->adminUserGrpOption().'

                            </select>

                            <script>

                                $(document).ready(function(){

                                    $(".acc_grp").val("'.@$userData['acc_role'].'").change();

                                });

                            </script>'

        );



        $form_fields[] = array(

            'label' => $_e['Gender'],

            'name' => 'signUp[gender]',

            'type' => 'radio',

            'option' => $_e['Female'].",".$_e['Male'],

            'value' => "female,male",

            'class' => 'gender',

            'selected' => @strtolower($this->webUserInfoArray($userInfo,'gender')),

            'format' => '<label class="radio-inline">{{form}} {{option}}</label>'

        );



        $form_fields[] = array(

            'label' => $_e['Date Of Birth'],

            'name' => 'signUp[date_of_birth]',

            'value' => @$this->webUserInfoArray($userInfo,'date_of_birth'),

            'type' => 'text',

            'class' => 'form-control date',

        );



        $form_fields[] = array(

            'label' => $_e['Phone'],

            'name' => 'signUp[phone]',

            'value' => @$this->webUserInfoArray($userInfo,'phone'),

            'type' => 'text',

            'class' => 'form-control',

        );



        $form_fields[] = array(

            'label' => $_e['Address'],

            'name' => 'signUp[address]',

            'value' => @$this->webUserInfoArray($userInfo,'address'),

            'type' => 'textarea',

            'class' => 'form-control',

        );



        $form_fields[] = array(

            'label' => $_e['Post Code'],

            'name' => 'signUp[post_code]',

            'value' => @$this->webUserInfoArray($userInfo,'post_code'),

            'type' => 'text',

            'class' => 'form-control',

        );



        $form_fields[] = array(

            'label' => $_e['City'],

            'name' => 'signUp[city]',

            'value' => @$this->webUserInfoArray($userInfo,'city'),

            'type' => 'text',

            'class' => 'form-control',

        );



        $form_fields[] = array(

            'label' => $_e['Country'],

            'name' => 'signUp[country]',

            'value' => @$this->webUserInfoArray($userInfo,'country'),

            'type' => 'text',

            'class' => 'form-control',

        );



        //checking adminPanel Language

        $option = "default,";

        if($this->functions->developer_setting('multi_language')=='1') {

            $langs = $this->functions->IbmsLanguages();

            @$temp2 = $this->webUserInfoArray($userInfo, 'adminLang');

            foreach ($langs as $op) {

                $option .= "$op,";

            }



            $form_fields[] = array(

                'label' => $_e['Admin Panel'],

                'name' => 'signUp[adminLang]',

                'option' => $option,

                'value' => $option,

                'type' => 'select',

                'select' => $temp2,

                'class' => 'form-control',

            );

        }else{

            $form_fields[] = array(

                'label' => $_e['Admin Panel'],

                'name' => 'signUp[adminLang]',

                'value' => "",

                'type' => 'hidden',

                'class' => 'form-control',

            );

        }



        $form_fields[] = array(

            'label' => $_e['Account Status'],

            'name' => 'acc_type',

            'type' => 'radio',

            'option' => $_e['Active'].",".$_e['DeActive'],

            'value' => "1,0",

            'class' => 'acc_type',

            'selected' =>  @$userData['acc_type'],

            'format' => '<label class="radio-inline">{{form}} {{option}}</label>'

        );



        $form_fields[] = array(

            'label' => $_e['Account Create'],

            'value' => @$userData['acc_created'],

            'type' => 'text',

            'class' => 'form-control',

            'readonly' => 'true'

        );



        $form_fields[] = array(

            'label' => $_e['Last Update'],

            'value' => @$userData['acc_timestamp'],

            'type' => 'text',

            'class' => 'form-control',

            'readonly' => 'true',

            'format' => "{{form}} <hr>"

        );





        $form_fields[] = array(

            'label' => $_e['Password'],

            'name' => 'pass',

            'id' => 'pass',

            'type' => 'password',

            'class' => 'form-control',

            'data' => 'onChange="passM();"'

        );



        $form_fields[] = array(

            'label' => $_e['Retype Password'],

            'name' => 'rpass',

            'id' => 'rpass',

            'type' => 'password',

            'class' => 'form-control',

            'data' => 'onChange="passM();" onkeyup="passM();"',

            'format' => '{{form}} <div id="pm"></div>'

        );



        $form_fields[]  = array(

            "value" => $_e['Save'],

            "name"  => 'btn',

            'class' => 'btn btn-primary defaultSpecialButton',

            'type'  => 'submit',

            'data'  => ' onClick="subf()"',

        );



        $form_fields[] = array(

            'type' => 'none',

            'thisFormat' => "</div>"

        );



        if($action==''){

            $action = 'AdminUsers';

        }

        $form_fields['form']  = array(

            'type'      => 'form',

            'class'     => "form-horizontal",

            'id'        => "formId",

            'action'   => '-'.$this->functions->getLinkFolder().'?page='.$action,

            'method'   => 'post',

            'format'   => '<div class="form-horizontal">{{form}}</div>'

        );



        $format = '<div class="form-group">

                        <label class="col-md-4  control-label">{{label}}</label>

                        <div class="col-md-8">

                            {{form}}

                        </div>

                    </div>';

        //$format = false;

        $this->functions->print_form($form_fields,$format);

    }





    public function adminUserGrpOption(){

        $sql    =   "SELECT id,name FROM accounts_prm_grp ORDER BY name ASC";

        $data   =   $this->dbF->getRows($sql);



        $op='';

        if($this->dbF->rowCount > 0){

            foreach($data as $val){

                $op .="<option value='$val[id]'>$val[name]</option>";

            }

            return $op;

        }

        return "";

    }









    public function adminUserEditSubmit(){

        global $_e;

        if (isset($_POST['name']) && !empty($_POST['name'])

            && isset($_POST['email']) && !empty($_POST['email'])

            && isset($_POST['oldId']) && !empty($_POST['oldId'])

        ){



            if(!$this->functions->getFormToken('adminUserEdit')){return false;}

            try {



                $email = strip_tags(strtolower(trim($_POST['email'])));

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {



                    $id     = empty($_POST['oldId']) ? "" : $_POST['oldId'];

                    $name   = empty($_POST['name']) ? "" : $_POST['name'];

                    $pass   = empty($_POST['pass']) ? "" : $_POST['pass'];

                    $rpass  = empty($_POST['rpass']) ? "" : $_POST['rpass'];

                    $type   = empty($_POST['acc_type']) ? 0 : $_POST['acc_type'];

                    $role   = empty($_POST['acc_grp']) ? 0 : $_POST['acc_grp'];



                    $passwordStatus = false;

                    if($pass != $rpass){

                        $msg = $_e['Password Not Matched!'];

                        return $msg;

                    }

                    if($pass != ''){

                        $passwordStatus =true;

                    }



                    $this->db->beginTransaction();

                    $sql = "UPDATE  accounts SET

                                acc_name = ?,

                                acc_email = ?,

                                acc_type  = ?,

                                acc_role  =?

                                WHERE acc_id = '$id'";

                    $array = array($name,$email,$type,$role);

                    $this->dbF->setRow($sql,$array,false);



                    if($passwordStatus){

                        $password  =  $this->functions->encode($pass);

                        $sql = "UPDATE  accounts SET

                                acc_pass = ?

                                WHERE acc_id = '$id'";

                        $array = array($password);

                        $this->dbF->setRow($sql,$array,false);

                    }



                    $lastId = $id;

                    $setting    = empty($_POST['signUp']) ? array() : $_POST['signUp'];



                    $sql = "DELETE FROM `accounts_detail` WHERE id_user= '$id'";

                    $this->dbF->setRow($sql);



                    $sql        =   "INSERT INTO `accounts_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";

                    $arry       =   array();

                    foreach($setting as $key=>$val){

                        $sql .= "('$lastId',?,?) ,";

                        $arry[]= $key ;

                        $arry[]= $val ;

                    }

                    $sql = trim($sql,",");

                    $this->dbF->setRow($sql,$arry,false);



                } else {

                    $AccLoginInfoT = $_e['Invalid Email Address! Or Some Thing Went Wrong'];

                    $msg = $AccLoginInfoT;

                    $this->db->rollBack();

                    return $msg;

                }



                $this->db->commit();

                if($this->dbF->rowCount>0){

                    $this->functions->setlog($_e['Update AdminUser'],$_e['Admin User'],$lastId,str_replace( '{{name}}' , $name  , $_e['Admin Update with Name : {{name}}']  ));

                    $msg = $_e['Profile Update Successfully!'];

                }else{

                    $msg = $_e['Profile Update Failed!'];

                }

                return $msg;

            } catch (PDOException $e) {

                $msg = $_e['adminUser Update fail please try again.!'];

                $this->db->rollBack();

                return $msg;

            }

        }

        return "";

    }



    public function adminUserAddSubmit(){

        global $_e;

        if (isset($_POST['name']) && !empty($_POST['name'])

            && isset($_POST['email']) && !empty($_POST['email'])

        ){



            if(!$this->functions->getFormToken('adminUserNew')){return false;}

            try {



                $email = strip_tags(strtolower(trim($_POST['email'])));

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {



                    $id     = empty($_POST['oldId']) ? "" : $_POST['oldId'];

                    $name   = empty($_POST['name']) ? "" : $_POST['name'];

                    $pass   = empty($_POST['pass']) ? "" : $_POST['pass'];

                    $rpass  = empty($_POST['rpass']) ? "" : $_POST['rpass'];



                    $type   = empty($_POST['acc_type']) ? 0 : $_POST['acc_type'];

                    $role   = empty($_POST['acc_grp']) ? 0 : $_POST['acc_grp'];



                    $passwordStatus = false;

                    if($pass != $rpass){

                        $msg = $_e['Password Not Matched!'];

                        return $msg;

                    }

                    if($pass == ''){

                        $msg = $_e['Password Required!'];

                        return $msg;

                    }



                    $password  =  $this->functions->encode($pass);

                    $this->db->beginTransaction();

                    $sql = "INSERT INTO accounts SET

                                acc_name = ?,

                                acc_email = ?,

                                acc_pass  = ?,

                                acc_type  = ?,

                                acc_role  =?,

                                acc_created = ?

                                ";

                    $array = array($name,$email,$password,$type,$role,date('Y-m-d H:i:s'));

                    $this->dbF->setRow($sql,$array,false);



                    if($this->dbF->hasException===true){

                        throw new Exception("");

                    }



                    $lastId = $this->dbF->rowLastId;



                    $setting    = empty($_POST['signUp']) ? array() : $_POST['signUp'];



                    $sql        =   "INSERT INTO `accounts_detail` (`id_user`,`setting_name`,`setting_val`) VALUES ";

                    $arry       =   array();

                    foreach($setting as $key=>$val){

                        $sql .= "('$lastId',?,?) ,";

                        $arry[]= $key ;

                        $arry[]= $val ;

                    }

                    $sql = trim($sql,",");

                    $this->dbF->setRow($sql,$arry,false);

                } else {

                    $AccLoginInfoT = $_e['Invalid Email Address! Or Some Thing Went Wrong'];

                    $msg = $AccLoginInfoT;

                    $this->db->rollBack();

                    return $msg;

                }



                $this->db->commit();

                $this->functions->setlog($_e['New AdminUser'],$_e['Admin User'],$lastId,str_replace( '{{name}}' , $name  , $_e['New Admin Add with Name : {{name}}']  ));

                $msg = $_e['Profile Add Successfully!'];

                return $msg;

            } catch (Exception $e) {

                $msg = $_e['adminUser Update fail please try again.! Or Duplicate Email.'];

                $this->db->rollBack();

                return $msg;

            }

        }

        return "";

    }





    public function adminUsersView(){

        $sql  = "SELECT * FROM accounts WHERE acc_type = '1' AND acc_grp = '1' ORDER BY acc_id DESC ";

        $data =  $this->dbF->getRows($sql);

        $this->adminUserPrint($data,true);

    }



    public function adminUserPrint($data,$active=true){

        global $_e;

        echo '<div class="table-responsive">

                <table class="table table-hover dTable tableIBMS">

                    <thead>

                        <th>'._u($_e['SNO']).'</th>

                        <th>'._u($_e['USER NAME']).'</th>

                        <th>'._u($_e['USER EMAIL']).'</th>

                        <th>'._u($_e['USER FROM']).'</th>

                        <th>'._u($_e['ACTION']).'</th>

                    </thead>

                <tbody>';

        $i = 0;

        $pagePermission = $this->functions->pagePermissionStatus();

        foreach($data as $val){

            $i++;

            $id = $val['acc_id'];



            if($val['acc_role']=='0'){

                $editDiv    =   "<div class='btn btn-sm btn-danger'>"._u($_e['OWNER'])."</div>";

            }else{

                $editDiv    = "<div class='btn-group btn-group-sm'>";



                if($pagePermission==='3' || $pagePermission===true) {

                    if($active) {

                        $editDiv .= "<a data-id = '$id' data-val = '0' onclick = 'activeAdminUser(this);' class='btn' title = '" . $_e['DeActive User'] . "' >

                                <i class='glyphicon glyphicon-thumbs-down trash' ></i >

                                <i class='fa fa-refresh waiting fa-spin' style = 'display: none' ></i >

                            </a>";

                    }else{

                        $editDiv.=" <a data-id='$id' data-val='1' onclick='activeAdminUser(this);' class='btn' title='".$_e['Active User']."'>

                                <i class='glyphicon glyphicon-thumbs-up trash'></i>

                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                            </a>";

                    }

                }

                if($pagePermission==='2' && $_SESSION['_uid']==$id || $pagePermission===true) {

                    $editDiv.= "<a data-id='$id' href='-webUsers?page=adminEdit&userId=$id' class='btn'>

                                <i class='glyphicon glyphicon-edit'></i>

                            </a>";

                }elseif($pagePermission==='3'){

                    $editDiv.= "<a data-id='$id' href='-webUsers?page=adminEdit&userId=$id' class='btn'>

                                <i class='glyphicon glyphicon-edit'></i>

                            </a>";

                }

                if($pagePermission==='3'|| $pagePermission===true) {

                    $editDiv.= "<a data-id='$id' onclick='deleteAdminUser(this);' class='btn'   title='" . $_e['Delete User'] . "'>

                                <i class='glyphicon glyphicon-trash trash'></i>

                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                            </a>";

                }

                echo " </div>";

            }





            echo "<tr>

                    <td>$i</td>

                    <td>$val[acc_name]</td>

                    <td>$val[acc_email]</td>

                    <td>$val[acc_created]</td>

                    <td>$editDiv</td>

                  </tr>";

        }





        echo '</tbody>

             </table>

            </div> <!-- .table-responsive End -->';

    }



    public function adminUsersViewDeActive(){

        $sql  = "SELECT * FROM accounts WHERE acc_type = '0' AND acc_grp = '1'  ORDER BY acc_id DESC ";

        $data =  $this->dbF->getRows($sql);

        $this->adminUserPrint($data,false);

    }







    public function adminUsersGrpView(){

        global $_e;

        echo '<div class="table-responsive">

                <table class="table table-hover tableIBMS">

                    <thead>

                        <th>'._u($_e['SNO']).'</th>

                        <th>'._u($_e['GROUP NAME']).'</th>

                        <th>'._u($_e['ACTION']).'</th>

                    </thead>

                <tbody>';



        $sql  = "SELECT * FROM accounts_prm_grp ORDER BY id DESC ";

        $data =  $this->dbF->getRows($sql);

        $i = 0;

        foreach($data as $val){

            $i++;

            $id = $val['id'];



            $editDiv    = "<div class='btn-group btn-group-sm'>



                            <a data-id='$id' href='-webUsers?page=groupEdit&grpId=$id' class='btn'>

                                <i class='glyphicon glyphicon-edit'></i>

                            </a>



                            <a data-id='$id' onclick='deleteAdminGrp(this);' class='btn'   title='".$_e['Delete Group']."'>

                                <i class='glyphicon glyphicon-trash trash'></i>

                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

                            </a>

                        </div>";



            echo "<tr>

                    <td>$i</td>

                    <td>$val[name]</td>

                    <td>$editDiv</td>

                  </tr>";

        }





        echo '</tbody>

             </table>

            </div> <!-- .table-responsive End -->';

    }





    public function newAdminGrp($id=''){

        global $_e;

        if($id=='' && isset($_GET['grpId'])){

            @$id     = $_GET['grpId'];



            $sql    = "SELECT * FROM accounts_prm_grp WHERE id = '$id'";

            $userData   =   $this->dbF->getRow($sql);

            if($this->dbF->rowCount)

            {

                $token  =    $this->functions->setFormToken('adminGrpEdit',false);

            }else{

                $id = '';

            }

        }

        if($id ==''){

            $token  =    $this->functions->setFormToken('adminGrpNew',false);

        }

        echo '<form action="-webUsers?page=AdminGrp" class="form-horizontal" role="form" method="post">'.$token.'

                <input type="hidden" name="oldId" value="'.$id.'"/>

                <div class="form-group">

                    <label for="user" class="col-sm-2 control-label">'.$_e['Group Name'].'</label>



                    <div class="col-sm-10">

                    <!-- patteren not working for sweden lang pattern="[a-zA-z- ]{3,50}"-->

                        <input type="text" class="form-control" name="name" id="user"

                               placeholder="'.$_e['Name'].'" value="'.@$userData['name'].'" required">

                    </div>

                </div>



                <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label" >'.$_e['Permissions'].'</label>



                    <div class="col-sm-10">

                        '.$this->userPermissions(@$userData['permission']).'

                    </div>

                </div>



                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" name="submit" id="signup_btn" class="btn btn-primary defaultSpecialButton">

                               '.$_e['Save'].'

                        </button>

                    </div>

                </div>

            </form>';





    }



    public function userPermissions($permission){

        global $_e;

        $edit = false;

        if($permission!=''){

            $edit = true;

            $permission     =   unserialize($permission);

        }

        //var_dump($permission);



        $menuClass  =   new menu();

        $MenuVisible = $menuClass->autoVisibleMenuArray();

        $menuLink   =   $menuClass->AutoVisibleMenuLink;

        $menuName   =   $menuClass->AutoVisibleMenuName;



        $temp       =   "<div class='' style='height: 300px;overflow-y: scroll;padding: 5px;'>";



        foreach($MenuVisible['menu'] as $val){

            // var_dump($val);

            $check = 'checked';

            if($edit){

                $check = '';

                if(in_array($val,$permission['menu'])){

                    $check = 'checked';

                }

            }

            $temp .= "<div class='col-sm-12 h2 btn-info'><label>

                                <input type='checkbox' value='$val' $check name='MainMenu[]'>

                                    $menuName[$val]

                                </label></div>";

            if(!empty($MenuVisible[$val])){

                foreach($MenuVisible[$val] as $val2){

                    $link   =   $menuLink[$val2];

                    $check1 = 'checked';

                    $check2 = '';

                    $check3 = '';

                    $check4 = '';

                    if($edit){

                        $check1 = '';

                        if('0'===@$permission[$val][$link]){

                            $check1 = 'checked';

                        }elseif('1'===@$permission[$val][$link]){

                            $check2 = 'checked';

                        }elseif('2'===@$permission[$val][$link]){

                            $check3 = 'checked';

                        }elseif('3'===@$permission[$val][$link]){

                            $check4 = 'checked';

                        }else{

                            $check1 = 'checked';

                        }

                    }

                    $temp .= "<div class='col-sm-12 btn-default'>

                                <div class='col-sm-3 btn-sm'>  $val2</div>

                                <div class='col-sm-9 radio btn-sm'>

                                    <label>

                                        <input type='radio' value='0' $check1 name='permissions[$val][$link]'>

                                        ".$_e['Not Access']."

                                    </label>

                                    <label>

                                        <input type='radio' value='1' $check2 name='permissions[$val][$link]'>

                                        ".$_e['Read Only']."

                                    </label>

                                    <label>

                                        <input type='radio' value='2' $check3 name='permissions[$val][$link]'>

                                        ".$_e['Write Only']."

                                    </label>

                                    <label>

                                        <input type='radio' value='3' $check4 name='permissions[$val][$link]'>

                                        ".$_e['Read, Write and Delete']."

                                    </label>

                                </div>

                              </div>";

                }

            }



        }



        $temp .=    '</div>';

        return $temp;

    }





    public function adminGrpSubmit(){

        global $_e;

        if (isset($_POST['name']) && !empty($_POST['name'])

            && isset($_POST['MainMenu']) && !empty($_POST['MainMenu'])

        ){



            if(!$this->functions->getFormToken('adminGrpNew')){return false;}

            $name   =   $_POST['name'];

            $mainMenu   =   $_POST['MainMenu'];

            $permissions = $_POST['permissions'];

            $makeNewLink    =       array();

            foreach($mainMenu as $val){

                $makeNewLink['menu'][] = $val;

                if(!empty($permissions[$val])){

                    foreach($permissions[$val] as $key=>$val2){

                        $makeNewLink['subMenu'][] =   $key;

                        $makeNewLink['subMenuP'][$key] =   $val2;

                        $makeNewLink[$val][$key]  =   $val2;

                    }

                }

            }



            $makeNewLink = serialize($makeNewLink);



            $sql    =   "INSERT INTO `accounts_prm_grp` SET

                            `name` = ?,

                            `permission` = ?";

            $this->dbF->setRow($sql,array($name,$makeNewLink));

            //var_dump($makeNewLink);



            if($this->dbF->rowCount>0){

                $this->functions->notificationError($_e['User Group'],$_e['New Group Add Successfully'],'btn-success');

                $this->functions->setlog($_e['Group add'],$_e['Admin User Group'],$this->dbF->rowLastId,str_replace( '{{name}}' , $name  , $_e['New Admin User group Add with name : {{name}}']  ));



            }else{

                $this->functions->notificationError($_e['User Group'],$_e['New Group Add Failed'],'btn-danger');

            }

        }

    }



    public function adminGrpEditSubmit(){

        global $_e;

        if (isset($_POST['name']) && !empty($_POST['name'])

            && isset($_POST['MainMenu']) && !empty($_POST['MainMenu'])

        ){



            if(!$this->functions->getFormToken('adminGrpEdit')){return false;}



            $id     = empty($_POST['oldId']) ? "" : $_POST['oldId'];



            $name   =   $_POST['name'];

            $mainMenu   =   $_POST['MainMenu'];

            $permissions = $_POST['permissions'];

            $makeNewLink    =       array();

            foreach($mainMenu as $val){

                $makeNewLink['menu'][] = $val;

                if(!empty($permissions[$val])){

                    foreach($permissions[$val] as $key=>$val2){

                        $makeNewLink['subMenu'][] =   $key;

                        $makeNewLink['subMenuP'][$key] =   $val2;

                        $makeNewLink[$val][$key]  =   $val2;

                    }

                }

            }



            $makeNewLink = serialize($makeNewLink);



            $sql    =   "UPDATE `accounts_prm_grp` SET

                            `name` = ?,

                            `permission` = ? WHERE id = '$id'";

            $this->dbF->setRow($sql,array($name,$makeNewLink));

            //var_dump($makeNewLink);



            if($this->dbF->rowCount>0){

                $this->functions->notificationError($_e['User Group'],$_e['New Group Update Successfully'],'btn-success');

                $this->functions->setlog($_e['Group Update'],$_e['Admin User Group'],$this->dbF->rowLastId,str_replace( '{{name}}' , $name  , $_e['Admin User group Update with name : {{name}}']  ));



            }else{

                $this->functions->notificationError($_e['User Group'],$_e['New Group Update Failed'],'btn-danger');

            }

        }

    }







}

?>