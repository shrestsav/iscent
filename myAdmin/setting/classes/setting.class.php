<?php

class setting extends object_class{
    public $productF;
    public function __construct(){
        parent::__construct('3');
        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            if($this->functions->developer_setting('product') == '1') {
                $this->functions->require_once_custom('product_functions');
                $this->productF = new product_function();
            }
        }

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //IBMS setting.php
        $_w['General'] = '' ;
        $_w['Product'] = '' ;
        $_w['Social'] = '' ;
        $_w['IBMS Setting'] = '' ;
        $_w['SAVE'] = '' ;
        $_w['Which Graph report you want to show on dashboard'] = '' ;
        $_w['Free Shipping Offer'] = '' ;
        $_w['Grid View By Category'] = '' ;
        $_w['Payment method additional price'] = '' ;
        $_w['Payment'] = '' ;

        //Account.php
        $_w['Account Setting'] = '' ;
        $_w['Account Name'] = '' ;
        $_w['Email'] = '' ;
        $_w['Password'] = '' ;
        $_w['Leave Blank If not want to update'] = '' ;
        $_w['Retype Password'] = '' ;
        $_w['UPDATE'] = '' ;

        //hardWords.php
        $_w['WebSite Special Words'] = '' ;
        $_w['GO BACK'] = '' ;
        $_w['List'] = '' ;
        $_w['Add New'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;

        //Index.php
        $_w['Setting'] = '' ;
        $_w['Free Gift Product'] = '' ;
        $_w['Free product add when cart reach at price'] = '' ;
        $_w['FREE GIFT PRODUCT'] = '' ;
        $_w['Check out offer show when cart reach at price'] = '' ;
        //This Class
        $_w['How Many Product Show On Page?'] = '' ;
        $_w['IBMS Setting Update Failed'] = '' ;
        $_w['IBMS Setting Update Successfully'] = '' ;
        $_w['Password Not match'] = '' ;
        $_w['Account Setting Update Successfully'] = '' ;
        $_w['Account Setting Update Fail'] = '' ;
        $_w['Fail'] = '' ;
        $_w['Update'] = '' ;
        $_w['Error'] = '' ;
        $_w['Real Word'] = '' ;
        $_w['VISIBLE WORD'] = '' ;
        $_w['Translate Word Update Successfully'] = '' ;
        $_w['Translate Words'] = '' ;
        $_w['Translate Word Update Fail'] = '' ;
        $_w['{{word}} Word Is Update'] = '' ;
        $_w['Added'] = '' ;
        $_w['Translate Word Add Successfully'] = '' ;
        $_w['{{word}} Word Is Added'] = '' ;
        $_w['SNO'] = '' ;
        $_w['DEFAULT WORDS'] = '' ;
        $_w['VISIBLE'] = '' ;
        $_w['LOCATION'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Site Name'] = '' ;
        $_w['IBMS History Delete After ? Days'] = '' ;
        $_w['TimeZone'] = '' ;
        $_w['Languages'] = '' ;
        $_w['separate with comma'] = '' ;
        $_w['Default Admin Language'] = '' ;
        $_w['Default Admin Panel Language'] = '' ;
        $_w['Default Website Language'] = '' ;
        $_w['Invoice Receipt Start With'] = '' ;
        $_w['Invoice Able To Delete After ? Days'] = '' ;
        $_w["0 Qty's Product Remove After ? Days From Inventory Record"] = '' ;
        $_w['Show Image In Sort Product'] = '' ;
        $_w['If product has no stock show on web page?'] = '' ;
        $_w['No (Fast Speed Recommended)'] = '' ;
        $_w['Yes'] = '' ;
        $_w['Default Admin Price Country'] = '' ;
        $_w['No'] = '' ;
        $_w['Default Website Price Country'] = '' ;
        //search by date Range
        $_w['Search By Date Range'] = '' ;
        $_w['Date From'] = '' ;
        $_w['Date To'] = '' ;
        //history.php
        $_w['SNO'] = '' ;
        $_w['TITLE'] = '' ;
        $_w['REFERENCE'] = '' ;
        $_w['DATE TIME'] = '' ;
        $_w['USER'] = '' ;
        $_w['DESCRIPTION'] = '' ;
        $_w['IP'] = '' ;
        $_w['BROWSER'] = '' ;
        $_w['IBMS History'] = '' ;
        $_w['Paypal Email'] = '' ;

        $_w['How Many Top Sale Product Show?'] = '' ;
        $_w['How Many Latest Product Show?'] = '' ;
        $_w['How Many Feature Product Show?'] = '' ;
        $_w['How Many Feature2 Product Show?'] = '' ;
        $_w['Contact'] = '' ;
        $_w['Location Map Link'] = '' ;
        $_w['Twitter Name'] = '';
        $_w['How Many Best Seller Product Show?'] = '';
        

        //Reviews
        $_w['Review Allow?'] = '' ;

        $_w['Login Required?'] = '' ;
        $_w['Yes (Recommended)'] = '' ;
        $_w['Reviews'] = '' ;
        $_w['Approve By Admin'] = '' ;
        $_w['Active'] = '' ;
        $_w['New Review Status?'] = '' ;
        $_w['How Many Review Show On Page?'] = '' ;
        $_w['Ascending'] = '' ;
        $_w['Descending'] = '' ;
        $_w['Show New First'] = '' ;
        $_w['Show Old First'] = '' ;
        $_w['Review Order'] = '' ;
        $_w['Comment Order'] = '' ;
        $_w['Facebook Comment BackGround'] = '';
        $_w['Facebook Numeric Id'] = '' ;
        $_w['Show Popular First'] = '' ;
        $_w['Facebook Comment Allow?'] = '' ;
        $_w['Facebook Comment'] = '' ;
        $_w['Review Off Msg'] = '' ;
        $_w['Fb Comment Off Msg'] = '' ;
        $_w['Show Coupon Offer On User Visit'] = '' ;
        $_w['Check Out Offer'] = '' ;
        $_w['Show Products on check out offer'] = '' ;
        $_w['Question Allow?'] = '' ;
        $_w['Question Off Msg'] = '' ;
        $_w['How Many Question Show On Page?'] = '' ;
        $_w['Question Order'] = '' ;
        $_w['Products Ask Question'] = '' ;

        $_w['Script in head section'] = '' ;
        $_w['Script in footer section'] = '' ;

        $_w['Shipping Price'] = '' ;
        $_w['By Weight'] = '' ;
        $_w['By Class'] = '' ;
        $_w['3 for 2 Category'] = '' ;
        $_w['3 for 2 Category Offer'] = '' ;
        $_w['Remain Oil Buffer (ml)'] = '' ;
        $_w['Technician Names'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Setting');
    }

    public function getIBMSSettingData(){
        $sql    =   "SELECT * FROM ibms_setting ORDER BY id ASC";
        $data   =   $this->dbF->getRows($sql);
        return $data;
    }

    public function getIBMSSettingArrayValue($Key,$data){
        foreach ($data as $keya => $val) {
            if ($val['setting_name'] == $Key) {
                return $val['setting_val'];
            }
        }
        return "";
    }


    public  function IBMSSubmit(){
        global $_e;
        if(!empty($_POST['setting'])){
            if(!$this->functions->getFormToken('IBMSSetting')){return false;}

            try{
                $this->db->beginTransaction();
                $setting  = $_POST['setting'];
                $submitSuccessfully =false;
                foreach($setting as $key=>$val){
                    $sql ="UPDATE ibms_setting SET `setting_val` = ? WHERE  `setting_name` = '$key'";
                    if($key=='Languages'){
                        $val = str_replace(" ","",$val);
                        $val = explode(",",$val);
                        $val = serialize($val);
                    }
                    if(is_array($val)){
                        $val = serialize($val);
                    }
                    $this->dbF->setRow($sql,array($val),false);
                    if($this->dbF->rowCount>0){
                        $submitSuccessfully = true;
                    }
                }

                $this->db->commit();

                if($submitSuccessfully){
                    $this->functions->notificationError(_js(_uc($_e['Setting'])),_js(_uc($_e['IBMS Setting Update Successfully'])),'btn-success');
                    $this->functions->setlog(_uc($_e['Setting']),'IBMS',"",_uc($_e["IBMS Setting Update Successfully"]),false);
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Setting'])),_js(_uc($_e['IBMS Setting Update Failed'])),'btn-danger');
                }
            }catch (Exception $e){
                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_js(_uc($_e['Setting'])),_js(_uc($_e['IBMS Setting Update Failed'])),'btn-danger');
            }

        }
    }


    public function getAccoutSettingData(){
        $user = $_SESSION['_uid'];
        $sql = "SELECT * FROM `accounts` WHERE acc_id ='$user'";
        $data = $this->dbF->getRow($sql);
        return $data;
    }


    public function AccountSubmit(){
        global $_e;
        if(!empty($_POST['acc_email']) && !empty($_POST['acc_name'])){
            if(!$this->functions->getFormToken('AccountSetting')){return false;}
            $userId = $_POST['userId'];

            $sql ="UPDATE accounts SET
                    `acc_name` = ?,
                     `acc_email` =?";
            $array = array($_POST['acc_name'],$_POST['acc_email']);

            if(isset($_POST['password']) && isset($_POST['retype_password']) && $_POST['retype_password'] !=''){
                if($_POST['password'] == $_POST['retype_password']){
                    $password = $_POST['password'];
                    $password = $this->functions->encode($password);
                    $sql .=', `acc_pass`=? ';
                    $array[]= $password;
                }else{
                    $this->functions->notificationError(_js(_uc($_e['Error'])),_js(_uc($_e['Password Not match'])),'btn-warning');
                    return false;
                }
            }

            $sql .= " WHERE acc_id = '$userId'";
            $this->dbF->setRow($sql,$array);
            if($this->dbF->rowCount){
                $this->functions->notificationError(_js(_uc($_e['Update'])),_js(_uc($_e['Account Setting Update Successfully'])),'btn-success');
            }else{
                $this->functions->notificationError(_js(_uc($_e['Fail'])),_js(_uc($_e['Account Setting Update Fail'])),'btn-danger');
            }
        }
    }

    public function hardWordsEdit(){
        global $_e;
        $id =   $_GET['editId'];
        $sql  = "SELECT * FROM hardwords WHERE id = '$id'";
        $data =  $this->dbF->getRow($sql);

        if($this->dbF->rowCount){
            $token = $this->functions->setFormToken('hardWordEdit',false);
            $realWord   =   $data['en'];

            $readonly = ' name="en" ';
            if($data['allowDelete']=='0'){
                $readonly = ' name="en" readonly';
            }

            echo '<div class="container-fluid">
                <form action="-setting?page=hardWords" method="post" class="form-horizontal">
                    '.$token.'
                    <input type="hidden" name="editId" value="'.$id.'"/>

                    <div class="form-group">
                            <label class="col-sm-4 col-md-3 control-label" >'. _uc($_e['Real Word']) .'</label>
                            <div class="col-sm-8 col-md-9">
                                <input type="text" value="'.$realWord.'" '.$readonly.' id="" class="form-control">
                            </div>
                    </div>';

            $lang = $this->functions->IbmsLanguages();
            if($lang != false){
                $lang_nonArray = implode(',', $lang);
            }
            echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';
            echo '<div class="panel-group" id="accordion">';

            @$langWord = unserialize($data['lang']);
            $serial =   true;
            if($langWord===false){
                $serial =   false;
            }

            for ($i = 0; $i < sizeof($lang); $i++) {
                if($i==0){$collapseIn = ' in ';}
                else{$collapseIn = '';}

                echo '<div class="panel panel-default">
                          <div class="panel-heading">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
                                    <h4 class="panel-title">
                                        '.$lang[$i].'
                                    </h4>
                                 </a>
                          </div>
                          <div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
                             <div class="panel-body">';

                //Title
                $value = $data['lang'];
                if($serial){
                    $value = $langWord[$lang[$i]];
                }
                //<input type="text" value="'.$value.'" name="lang['.$lang[$i].']" class="form-control" placeholder="'.$lang[$i].'">
                echo '         <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['VISIBLE WORD']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <textarea type="text" name="lang['.$lang[$i].']" class="form-control" placeholder="'.$lang[$i].'">'.$value.'</textarea>
                                    </div>
                                </div>';

                echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>';

            }
            echo '</div>';

            echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

            echo '</form>
                </div>';
        }
    }

    public function hardWordNew(){
        global $_e;
        $token = $this->functions->setFormToken('hardWordNew',false);

        echo '<div class="container-fluid">
                <form action="" method="post" class="form-horizontal">
                    '.$token.'

                    <div class="form-group">
                            <label class="col-sm-4 col-md-3 control-label" >'. _uc($_e['Real Word']) .'</label>
                            <div class="col-sm-8 col-md-9">
                                <input type="text" name="en" class="form-control">
                            </div>
                    </div>';

        $lang = $this->functions->IbmsLanguages();
        if($lang != false){
            $lang_nonArray = implode(',', $lang);
        }
        echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';
        echo '<div class="panel-group" id="accordion">';

        for ($i = 0; $i < sizeof($lang); $i++) {
            if($i==0){$collapseIn = ' in ';}
            else{$collapseIn = '';}

            echo '<div class="panel panel-default">
                          <div class="panel-heading">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#'.$lang[$i].'">
                                    <h4 class="panel-title">
                                        '.$lang[$i].'
                                    </h4>
                                 </a>
                          </div>
                          <div id="'.$lang[$i].'" class="panel-collapse collapse '.$collapseIn.'">
                             <div class="panel-body">';

            //Title
            echo '         <div class="form-group">
                                    <label class="col-sm-2 col-md-3  control-label">'. _uc($_e['VISIBLE WORD']) .'</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" name="lang['.$lang[$i].']" class="form-control" placeholder="'.$lang[$i].'">
                                    </div>
                                </div>';

            echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>';

        }
        echo '</div>';

        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">'. _u($_e['SAVE']) .'</button>';

        echo '</form>
                </div>';
    }

    public function hardWordsEditSubmit(){
        global $_e;
        if(isset($_POST['lang']) && isset($_POST['submit'])){
            if(!$this->functions->getFormToken('hardWordEdit')){return false;}

            $lang        = empty($_POST['lang'])? ""    : serialize($_POST['lang']);
            $en          = empty($_POST['en'])  ? ""    : $_POST['en'];
            $lastId      = $_POST['editId'];

            $sql         =   "UPDATE `hardwords` SET
                                `en`    = ?,
                                `lang`  = ?
                                 WHERE  id = '$lastId'";

            $array      = array($en,$lang);
            $this->dbF->setRow($sql,$array);

            if($this->dbF->rowCount){
                $this->functions->notificationError(_js(_uc($_e['Translate Words'])),_js(_uc($_e['Translate Word Update Successfully'])),'btn-success');
                $this->functions->setlog(_uc($_e['Update']),_uc($_e['Translate Words']),$lastId,_replace('{{word}}',$en,$_e['{{word}} Word Is Update']));
            }else{
                $this->functions->notificationError(_js(_uc($_e['Translate Words'])),_js(_uc($_e['Translate Word Update Fail'])),'btn-danger');
            }
        }
    }


    public function hardWordsSubmit(){
        global $_e;
        if(isset($_POST['lang']) && isset($_POST['submit'])){
            if(!$this->functions->getFormToken('hardWordNew')){return false;}

            $lang        = empty($_POST['lang'])? ""    : serialize($_POST['lang']);
            $en          = empty($_POST['en'])  ? ""    : $_POST['en'];

            $sql         =   "INSERT INTO `hardwords` SET
                                `en`    = ?,
                                `lang`  = ?,
                                `allowDelete` = '1'";

            $array      = array($en,$lang);
            $this->dbF->setRow($sql,$array);
            $lastId = $this->dbF->rowLastId;

            if($this->dbF->rowCount){
                $this->functions->notificationError(_js(_uc($_e['Translate Words'])),_js(_uc($_e['Translate Word Add Successfully'])),'btn-success');
                $this->functions->setlog(_uc($_e['Added']),_uc($_e['Translate Words']),$lastId,_replace('{{word}}',$en,$_e['{{word}} Word Is Added']));
            }else{
                $this->functions->notificationError(_js(_uc($_e['Translate Words'])),_js(_uc($_e['Translate Word Update Fail'])),'btn-danger');
            }

        }
    }


    public function hardWordsList(){
        global $_e;
        echo '<div class="table-responsive">
                    <table class="table table-hover dTable tableIBMS">
                        <thead>
                            <th>'. _u($_e['SNO']) .'</th>
                            <th>'. _u($_e['DEFAULT WORDS']) .'</th>
                            <th>'. _u($_e['VISIBLE']) .'</th>
                            <th>'. _u($_e['LOCATION']) .'</th>
                            <th>'. _u($_e['ACTION']) .'</th>
                        </thead>
                    <tbody>';
        $sql  = "SELECT * FROM hardwords ORDER by id DESC ";
        $data =  $this->dbF->getRows($sql);
        $i = 0;

        $defaultlang    =   $this->functions->AdminDefaultLanguage();
        foreach($data as $val){
            $i++;
            $id =   $val['id'];
            $delete = "<a data-id='$id' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='glyphicon glyphicon-ban-circle combineicon'></i>
                            </a>";
            if($val['allowDelete']=='1' || 1===1){
                $delete = "<a data-id='$id' onclick='deleteHardWords(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>";
            }

            @$langWord = unserialize($val['lang']);
            $serial =   true;
            if($langWord===false){
                $serial =   false;
            }
            $value = $val['lang'];
            if($serial){
                @$value = $langWord[$defaultlang];
                if(!isset($value) || $value=='') {
                    $value= $val['en'];
                }
            }

            echo "<tr>
                        <td>$i</td>
                        <td>$val[en]</td>

                        <td>$value</td>
                        <td>$val[place]</td>
                        <td><div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-setting?page=hardWords&editId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            $delete
                        </div></td>
                      </tr>";
        }

        echo '</tbody>
          </table>
         </div> <!-- .table-responsive End -->';
    }


    public function socialSetting($settingData){
        global $_e;
        ?>

        <?php if($this->functions->developer_setting('twitter')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Twitter</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Twitter',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[Twitter]" id="Twitter" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($this->functions->developer_setting('Facebook')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Facebook</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Facebook',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[Facebook]" id="Facebook" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($this->functions->developer_setting('Vimeo')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Vimeo</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Vimeo',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[Vimeo]" id="Vimeo" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($this->functions->developer_setting('Google')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Google</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Google',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[Google]" id="Google" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($this->functions->developer_setting('linkedIn')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >LinkedIn</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('linkedIn',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[linkedIn]" id="linkedIn" class="form-control">
                </div>
            </div>
        <?php } ?>


        <?php if($this->functions->developer_setting('pinterest')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Pinterest</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('pinterest',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[pinterest]" id="pinterest" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($this->functions->developer_setting('Instagram')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Instagram</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Instagram',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[Instagram]" id="Instagram" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($this->functions->developer_setting('youtube')=='1'){ ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" >Youtube</label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('youtube',$settingData);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[youtube]" id="youtube" class="form-control">
                </div>
            </div>
        <?php } ?>


    <?php }

    public function generalSetting($settingData){
        global $_e;
        $form_fields = array();

        $valForm = $this->getIBMSSettingArrayValue('Site Name',$settingData);
        $form_fields[] = array(
            'label' => _uc($_e['Site Name']),
            'name' => 'setting[Site Name]',
            'value' => $valForm,
            'type' => 'text',
            'class' => 'form-control'
        );

        $valForm = $this->getIBMSSettingArrayValue('Email',$settingData);
        $form_fields[] = array(
            'label' => _uc($_e['Email']),
            'name' => 'setting[Email]',
            'value' => $valForm,
            'type' => 'email',
            'class' => 'form-control'


        );



//         $valForm = $this->getIBMSSettingArrayValue('available_positions',$settingData);
// $form_fields[] = array(
// 'label' => _uc('Available Positions for Career Form'),
// 'placeholder' => _fu('Enter comma seperated values eg: position one,position two,three'),
// 'name' => 'setting[available_positions]',
// 'value' => $valForm,
// 'type' => 'text',
// 'class' => 'form-control'
// );


        // $valForm = $this->getIBMSSettingArrayValue('contact',$settingData);
        // $form_fields[] = array(
        //     'label' => _uc($_e['Contact']),
        //     'name' => 'setting[contact]',
        //     'value' => $valForm,
        //     'type' => 'text',
        //     'class' => 'form-control'
        // );

        if($this->functions->developer_setting('LocationMap')=='1') {
            $valForm = $this->getIBMSSettingArrayValue('locationMap',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['Location Map Link']),
                'name' => 'setting[locationMap]',
                'value' => $valForm,
                'type' => 'text',
                'class' => 'form-control'
            );
        }

        $valForm = $this->getIBMSSettingArrayValue('remain_oil_buffer',$settingData);
        $form_fields[] = array(
            'label' => _n($_e['Remain Oil Buffer (ml)']),
            'name' => 'setting[remain_oil_buffer]',
            'value' => $valForm,
            'type' => 'text',
            'class' => 'form-control'
        );

        $valForm = $this->getIBMSSettingArrayValue('technicians',$settingData);
        $form_fields[] = array(
            'label' => _n($_e['Technician Names']),
            'name' => 'setting[technicians]',
            'placeholder' => _fu('Enter comma seperated values'),
            'value' => $valForm,
            'type' => 'text',
            'class' => 'form-control'
        );

        // $valForm = $this->getIBMSSettingArrayValue('TwitterSite',$settingData);
        // $form_fields[] = array(
        //     'label' => _uc($_e['Twitter Name']),
        //     'name' => 'setting[TwitterSite]',
        //     'value' => $valForm,
        //     'type' => 'text',
        //     'class' => 'form-control'
        // );

        $valForm = $this->getIBMSSettingArrayValue('historyDeleteAfterDays',$settingData);
        $form_fields[] = array(
            'label' => _uc($_e['IBMS History Delete After ? Days']),
            'name' => 'setting[historyDeleteAfterDays]',
            'value' => $valForm,
            'type' => 'text',
            'class' => 'form-control',
            'min'   => '7'
        );

        $valForm = $this->getIBMSSettingArrayValue('TimeZone',$settingData);
        $option = $this->functions->get_timezone_options($valForm);
        $form_fields[] = array(
            'label' => _uc($_e['TimeZone']),
            'type' => 'none',
            'format' => '<select name="setting[TimeZone]" id="TimeZone" class="form-control">
                            '.$option.'
                        </select>'
        );


        $valForm = $this->getIBMSSettingArrayValue('headScript',$settingData);
        $form_fields[] = array(
            'label' => _uc($_e['Script in head section']),
            'name' => 'setting[headScript]',
            'value' => $valForm,
            'type' => 'textarea',
            'class' => 'form-control'
        );

        $valForm = $this->getIBMSSettingArrayValue('footerScript',$settingData);
        $form_fields[] = array(
            'label' => _uc($_e['Script in footer section']),
            'name' => 'setting[footerScript]',
            'value' => $valForm,
            'type' => 'textarea',
            'class' => 'form-control'
        );


        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';
        $this->functions->print_form($form_fields,$format);

        $this->languageSetting($settingData);


    }
    public function reviewSetting($settingData){
        global $_e;
        ?>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label"><?php echo _uc($_e['Review Allow?']); ?> </label>
            <div class="col-sm-8 col-md-9">
                <div class="make-switch" data-off="warning" data-on="success">
                    <?php
                    // if product edit
                    $check="";
                    $val = '0';
                    $temp = $this->getIBMSSettingArrayValue('showReview',$settingData);
                    if($temp=='1'){
                        $check="checked";
                        $val = '1';
                    }
                    ?>
                    <input type="checkbox"  value="1" <?php echo $check;?> >
                    <input type="hidden" name="setting[showReview]" class="showReview checkboxHidden" value="<?php echo $val;?>" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Review Off Msg']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('reviewOffMsg',$settingData);
                ?>
                <input type="text" value="<?php echo $temp; ?>" name="setting[reviewOffMsg]" id="reviewOffMsg" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['How Many Review Show On Page?']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('reviewLimit',$settingData);
                ?>
                <input type="number" min="3" value="<?php echo $temp; ?>" name="setting[reviewLimit]" id="reviewLimit" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Review Order']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('reviewOrderBY',$settingData);
                ?>
                <script>
                    $(document).ready(function(){
                        $('.reviewOrderBY[value="<?php echo $temp; ?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label><input type="radio" value="ASC" name="setting[reviewOrderBY]" class="reviewOrderBY"><?php echo _uc($_e['Show Old First']); ?></label>
                    <label> <input type="radio" value="DESC" name="setting[reviewOrderBY]" class="reviewOrderBY"><?php echo _uc($_e['Show New First']); ?></label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Login Required?']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('loginForComment',$settingData);
                ?>
                <script>
                    $(document).ready(function(){
                        $('.loginForComment[value="<?php echo $temp; ?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label><input type="radio" value="0" name="setting[loginForComment]" class="loginForComment"><?php echo _uc($_e['No']); ?></label>
                    <label> <input type="radio" value="1" name="setting[loginForComment]" class="loginForComment"><?php echo _uc($_e['Yes (Recommended)']); ?></label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['New Review Status?']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('commentStatus',$settingData);
                ?>
                <script>
                    $(document).ready(function(){
                        $('.commentStatus[value="<?php echo $temp; ?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label><input type="radio" value="0" name="setting[commentStatus]" class="commentStatus"><?php echo _uc($_e['Approve By Admin']); ?></label>
                    <label> <input type="radio" value="1" name="setting[commentStatus]" class="commentStatus"><?php echo _uc($_e['Active']); ?></label>
                </div>
            </div>
        </div>

    <?php
    }
    public function askQuestionSetting($settingData){
        global $_e;
        ?>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label"><?php echo _uc($_e['Question Allow?']); ?> </label>
            <div class="col-sm-8 col-md-9">
                <div class="make-switch" data-off="warning" data-on="success">
                    <?php
                    // if product edit
                    $check="";
                    $val = '0';
                    $temp = $this->getIBMSSettingArrayValue('showQuestion',$settingData);
                    if($temp=='1'){
                        $check="checked";
                        $val = '1';
                    }
                    ?>
                    <input type="checkbox"  value="1" <?php echo $check;?> >
                    <input type="hidden" name="setting[showQuestion]" class="showQuestion checkboxHidden" value="<?php echo $val;?>" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Question Off Msg']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('questionOffMsg',$settingData);
                ?>
                <input type="text" value="<?php echo $temp; ?>" name="setting[questionOffMsg]" id="questionOffMsg" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['How Many Question Show On Page?']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('askQuestionLimit',$settingData);
                ?>
                <input type="number" min="3" value="<?php echo $temp; ?>" name="setting[askQuestionLimit]" id="askQuestionLimit" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Question Order']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('askQuestionOrderBY',$settingData);
                ?>
                <script>
                    $(document).ready(function(){
                        $('.askQuestionOrderBY[value="<?php echo $temp; ?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label><input type="radio" value="ASC" name="setting[askQuestionOrderBY]" class="askQuestionOrderBY"><?php echo _uc($_e['Show Old First']); ?></label>
                    <label> <input type="radio" value="DESC" name="setting[askQuestionOrderBY]" class="askQuestionOrderBY"><?php echo _uc($_e['Show New First']); ?></label>
                </div>
            </div>
        </div>

    <?php
    }


    public function grid_view_setting($settingData){
        @$category_save = unserialize($this->getIBMSSettingArrayValue('grid_view',$settingData));

        $this->functions->modelClasFile("category.php");
        $category_c = new p_category();
        $category = $category_c->get_all_category();
        $grid_type = "Grid,SixGrid";
        $form_fields = array();
        $temp = isset($category_save["default"]) ? $category_save["default"] : "Grid";
        $form_fields[] = array(
            "label" => "Default",
            "name"  => "setting[grid_view][default]",
            'type'  => 'radio',
            'value' => "$grid_type",
            'option' => "$grid_type",
            "select" => "$temp",
            'format' => '<label class="radio-inline">{{form}} {{option}}</label>'
        );
        $form_fields[] = array(
            'type'  => 'none',
            'thisFormat' => '<hr>'
        );
        foreach($category as $val){
            $temp = @$category_save["cat_{$val['id']}"];
            $form_fields[] = array(
                "label" => $val["nm"],
                "name"  => "setting[grid_view][cat_{$val['id']}]",
                'type'  => 'radio',
                "select" => "$temp",
                'value' => "$grid_type",
                'option' => "$grid_type",
                'format' => '<label class="radio-inline">{{form}} {{option}}</label>'
            );
        }

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);

    }

    public function facebookReviewSetting($settingData){
        global $_e;
        ?>


        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label"><?php echo _uc($_e['Facebook Comment Allow?']); ?> </label>
            <div class="col-sm-8 col-md-9">
                <div class="make-switch" data-off="warning" data-on="success">
                    <?php
                    // if product edit
                    $check="";
                    $val = '0';
                    $temp = $this->getIBMSSettingArrayValue('showFacebookComment',$settingData);
                    if($temp=='1'){
                        $check="checked";
                        $val = '1';
                    }
                    ?>
                    <input type="checkbox"  value="1" <?php echo $check;?> >
                    <input type="hidden" name="setting[showFacebookComment]" class="showFacebookComment checkboxHidden" value="<?php echo $val;?>" />
                </div>
            </div>
        </div>

        <!--
        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php /*echo _uc($_e['Facebook Comment Allow?']); */?></label>
            <div class="col-sm-8 col-md-9">
                <?php
/*                $temp = $this->getIBMSSettingArrayValue('showFacebookComment',$settingData);
                */?>
                <script>
                    $(document).ready(function(){
                        $('.showFacebookComment[value="<?php /*echo $temp; */?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label><input type="radio" value="0" name="setting[showFacebookComment]" class="showFacebookComment"><?php /*echo _uc($_e['No']); */?></label>
                    <label><input type="radio" value="1" name="setting[showFacebookComment]" class="showFacebookComment"><?php /*echo _uc($_e['Yes']); */?></label>
                </div>
            </div>
        </div>-->


        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Fb Comment Off Msg']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                    $temp = $this->getIBMSSettingArrayValue('fbOffMsg',$settingData);
                ?>
                <input type="text" value="<?php echo $temp; ?>" name="setting[fbOffMsg]" id="fbOffMsg" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Facebook Numeric Id']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('facebookIntId',$settingData);
                ?>
                <input type="text" value="<?php echo $temp; ?>" name="setting[facebookIntId]" id="facebookIntId" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['How Many Review Show On Page?']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('facebookCommentLimit',$settingData);
                ?>
                <input type="number" min="3" value="<?php echo $temp; ?>" name="setting[facebookCommentLimit]" id="facebookCommentLimit" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Facebook Comment BackGround']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('facebookColorScheme',$settingData);
                ?>
                <script>
                    $(document).ready(function(){
                        $('.facebookColorScheme[value="<?php echo $temp; ?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label><input type="radio" value="light" name="setting[facebookColorScheme]" class="facebookColorScheme">light</label>
                    <label> <input type="radio" value="dark" name="setting[facebookColorScheme]" class="facebookColorScheme">dark</label>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Comment Order']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('facebookOrder_by',$settingData);
                ?>
                <script>
                    $(document).ready(function(){
                        $('.facebookOrder_by[value="<?php echo $temp; ?>"]').attr('checked',true);
                    });
                </script>
                <div class="radio">
                    <label> <input type="radio" value="social" name="setting[facebookOrder_by]" class="facebookOrder_by"><?php echo _uc($_e['Show Popular First']); ?></label>
                    <label> <input type="radio" value="time" name="setting[facebookOrder_by]" class="facebookOrder_by"><?php echo _uc($_e['Show Old First']); ?></label>
                    <label> <input type="radio" value="reverse_time" name="setting[facebookOrder_by]" class="facebookOrder_by"><?php echo _uc($_e['Show New First']); ?></label>
                </div>
            </div>
        </div>


    <?php }


    public function languageSetting($settingData){global $_e;

        $langHas = false;
        if($this->functions->developer_setting('multi_language')=='1'){
            $langHas = true;
            ?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Languages']); ?>: <small><?php echo _n($_e['separate with comma']); ?></small> </label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Languages',$settingData);
                    $temp = unserialize($temp);
                    $temp = implode(", ",$temp);
                    ?>
                    <input type="text" value="<?php echo $temp; ?>" name="setting[Languages]" id="Languages" class="form-control">
                </div>
            </div>
        <?php } ?>

        <?php if($langHas){?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Default Admin Language']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Languages',$settingData);
                    $temp = unserialize($temp);
                    $option = "";
                    $temp2 = $this->getIBMSSettingArrayValue('Default Language',$settingData);
                    foreach($temp as $op){
                        $select ="";
                        if($op==$temp2){$select='selected';}
                        $option .= "<option value='$op' $select>$op</option>";
                    }
                    ?>
                    <select name="setting[Default Language]" id="adminLanguage" class="form-control">
                        <?php echo $option; ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php ?>
        <div class="form-group displaynone">
            <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Default Admin Panel Language']); ?></label>
            <div class="col-sm-8 col-md-9">
                <?php
                $temp = $this->getIBMSSettingArrayValue('Languages',$settingData);
                $temp = unserialize($temp);
                $option = "";
                $temp2 = $this->getIBMSSettingArrayValue('Default_Admin_Panel_Language',$settingData);
                foreach($temp as $op){
                    $select ="";
                    if($op==$temp2){$select='selected';}
                    $option .= "<option value='$op' $select>$op</option>";
                }
                ?>
                <select name="setting[Default_Admin_Panel_Language]" id="adminLanguage" class="form-control">
                    <option value="default">Default</option>
                    <?php echo $option; ?>
                </select>
            </div>
        </div>
        <?php  ?>


        <?php if($langHas){?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Default Website Language']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $temp = $this->getIBMSSettingArrayValue('Languages',$settingData);
                    $temp = unserialize($temp);
                    $option = "";
                    $temp2 = $this->getIBMSSettingArrayValue('Default Web Language',$settingData);
                    foreach($temp as $op){
                        $select ="";
                        if($op==$temp2){$select='selected';}
                        $option .= "<option value='$op' $select>$op</option>";
                    }
                    ?>
                    <select name="setting[Default Web Language]" id="webLanguage" class="form-control">
                        <option value="default">Default</option>
                        <?php echo $option; ?>
                    </select>
                </div>
            </div>
        <?php } ?>

    <?php }//Lang Setting function end

    public function productSetting($settingData){global $_e;
        $productHas = true;
        $productCart = false;
        if($this->functions->developer_setting('cartSystem')=='1') {
            $productCart = true;
        }

        $form_fields = array();
        if($this->functions->developer_setting('paypal') == '1') {
            $valForm = $this->getIBMSSettingArrayValue('PayPal_email',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['Paypal Email']),
                'name' => 'setting[PayPal_email]',
                'value' => $valForm,
                'type' => 'email',
                'class' => 'form-control'
            );
        }


        if($productCart){
            $valForm = $this->getIBMSSettingArrayValue('invoice_key_start_with',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['Invoice Receipt Start With']),
                'name' => 'setting[invoice_key_start_with]',
                'value' => $valForm,
                'type' => 'text',
                'class' => 'form-control'
            );
        }



        if($productHas){
            $valForm = $this->getIBMSSettingArrayValue('productLimit',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['How Many Product Show On Page?']),
                'name' => 'setting[productLimit]',
                'value' => $valForm,
                'type' => 'text',
                'class' => 'form-control'
            );
        }

        if($this->functions->developer_setting('featureProduct') == '1'){
            $valForm = $this->getIBMSSettingArrayValue('featuredProductLimit',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['How Many Feature Product Show?']),
                'name' => 'setting[featuredProductLimit]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '1'
            );
        }

        if($this->functions->developer_setting('featureProduct2') == '1'){
            $valForm = $this->getIBMSSettingArrayValue('featureProduct2Limit',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['How Many Feature2 Product Show?']),
                'name' => 'setting[featureProduct2Limit]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '1'
            );
        }

        if($this->functions->developer_setting('latestProduct') == '1'){
            $valForm = $this->getIBMSSettingArrayValue('latestProductLimit',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['How Many Latest Product Show?']),
                'name' => 'setting[latestProductLimit]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '1'
            );
        }

        if($this->functions->developer_setting('topSaleProductLimit') == '1'){
            $valForm = $this->getIBMSSettingArrayValue('topSaleProductLimit',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['How Many Top Sale Product Show?']),
                'name' => 'setting[topSaleProductLimit]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '1'
            );
        }

        if($this->functions->developer_setting('bestSellerProductLimit') == '1'){
            $valForm = $this->getIBMSSettingArrayValue('bestSellerProductLimit',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['How Many Best Seller Product Show?']),
                'name' => 'setting[bestSellerProductLimit]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '1'
            );
        }

        if($productCart){
            $valForm = $this->getIBMSSettingArrayValue('order_invoice_deleteOn_request_after_days',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e['Invoice Able To Delete After ? Days']),
                'name' => 'setting[order_invoice_deleteOn_request_after_days]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '5'
            );
        }

        if($productCart){
            $valForm = $this->getIBMSSettingArrayValue('Inventory_0_delete_afterDays',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e["0 Qty's Product Remove After ? Days From Inventory Record"]),
                'name' => 'setting[Inventory_0_delete_afterDays]',
                'value' => $valForm,
                'type' => 'number',
                'class' => 'form-control',
                'min'  => '5'
            );
        }

        if($productHas){
            $valForm = $this->getIBMSSettingArrayValue('sortProductImage',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e["Show Image In Sort Product"]),
                'name' => 'setting[sortProductImage]',
                'select' => $valForm,
                'type' => 'radio',
                'option'  => array(_uc($_e['No (Fast Speed Recommended)']),_uc($_e['Yes'])),
                'value'  => array('no','yes'),
                'format' => '<label class="radio-inline">{{form}} {{option}}</label>',
            );
        }


        // if($this->functions->developer_setting("shipping_class")=='1'){
        //     $valForm    = $this->getIBMSSettingArrayValue('shippingType',$settingData);
        //     $form_fields[] = array(
        //         'label' => _uc($_e["Shipping Price"]),
        //         'name'  => 'setting[shippingType]',
        //         'select' => $valForm,
        //         'type'  => 'radio',
        //         'option'  => array(_uc($_e['By Weight']),_uc($_e['By Class'])),
        //         'value'  => array('weight','class'),
        //         'format' => '<label class="radio-inline">{{form}} {{option}}</label>',
        //     );
        // }else{
        //     //if class shipping off by developer, then default shipping price by weight
        //     $form_fields[] = array(
        //         'name'  => 'setting[shippingType]',
        //         'type'  => 'hidden',
        //         'value'  => 'weight'
        //     );
        // }


        if($productCart){
            $valForm = $this->getIBMSSettingArrayValue('no_inventory_product_show_onWeb',$settingData);
            $form_fields[] = array(
                'label' => _uc($_e["If product has no stock show on web page?"]),
                'name' => 'setting[no_inventory_product_show_onWeb]',
                'select' => $valForm,
                'type' => 'radio',
                'option'  => array(_uc($_e['No']),_uc($_e['Yes'])),
                'value'  => array('no','yes'),
                'format' => '<label class="radio-inline">{{form}} {{option}}</label>',
            );
        }


        if($this->functions->developer_setting('couponOfferMail') == '1') {
            $valForm = $this->getIBMSSettingArrayValue('couponOfferEmail',$settingData);
            $form_fields[] = array(
                "label" => $_e['Show Coupon Offer On User Visit'],
                'type' => 'checkbox',
                'value' => "$valForm",
                'select' => "1",
                'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Yes']) . '" data-off-label="' . _uc($_e['No']) . '">
                            {{form}}
                          <input type="hidden" name="setting[couponOfferEmail]" class="checkboxHidden" value="' . $valForm . '" />
                         </div>'
            );
        }
       /* $valForm = $this->getIBMSSettingArrayValue('',$settingData);
        $form_fields[] = array(
            'label' => _uc($_e['']),
            'name' => 'setting[]',
            'value' => $valForm,
            'type' => 'number',
            'class' => 'form-control',
            'min'  => '1'
        );*/



        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';
        $this->functions->print_form($form_fields,$format);

        ?>

        <?php if($productHas){?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Default Admin Price Country']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $option = "";
                    $temp2  = $this->getIBMSSettingArrayValue('Default Admin_Price_Country',$settingData);
                    $option = $this->productF->productCurrencyCountries();
                    ?>
                    <script>
                        $(document).ready(function(){
                            $('#Admin_Price_Country').val('<?php echo $temp2; ?>').change();
                        });
                    </script>
                    <select name="setting[Default Admin_Price_Country]" id="Admin_Price_Country" class="form-control">
                        <?php echo $option; ?>
                    </select>
                </div>
            </div>
        <?php } ?>


        <?php if($productHas){?>
            <div class="form-group">
                <label class="col-sm-4 col-md-3 control-label" ><?php echo _uc($_e['Default Website Price Country']); ?></label>
                <div class="col-sm-8 col-md-9">
                    <?php
                    $option = "";
                    $temp2  = $this->getIBMSSettingArrayValue('Default Web_Price_Country',$settingData);
                    $option = $this->productF->productCurrencyCountries();
                    ?>
                    <script>
                        $(document).ready(function(){
                            $('#web_Price_Country').val('<?php echo $temp2; ?>').change();
                        });
                    </script>
                    <select name="setting[Default Web_Price_Country]" id="web_Price_Country" class="form-control">
                        <?php echo $option; ?>
                    </select>
                </div>
            </div>
        <?php } ?>



    <?php } //ProductSetting Function End

    public function checkOutOffer($settingData){
        global $_e;

        $valForm = $this->getIBMSSettingArrayValue('check_out_offer',$settingData);
        $form_fields[] = array(
            "label" => $_e['Show Products on check out offer'],
            'type' => 'checkbox',
            'value' => "$valForm",
            'select' => "1",
            'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Yes']) . '" data-off-label="' . _uc($_e['No']) . '">
                            {{form}}
                          <input type="hidden" name="setting[check_out_offer]" class="checkboxHidden" value="' . $valForm . '" />
                         </div>'
        );

        //Price
        ############# MULTI CURRENCY ################
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency = new currency_management();
        $countryCodeList    = $this->functions->countrylist(); // country list
        $currency_data      = $c_currency->getList(); // get currency list
        ############# MULTI CURRENCY ################

        $tds    = "";
        $valForm = unserialize($this->getIBMSSettingArrayValue('check_out_price_limit',$settingData));
        foreach ($currency_data as $val) {
            $country_id     = $val['cur_id'];
            $symbol         = $val['cur_symbol'];
            $country_name   = $countryCodeList[$val['cur_country']];
            $currency       = $val["cur_name"];
            @$oldPrice      = $valForm[$country_id];
            $tds .= "<tr><td width='170'>$country_name ($currency)</td>";
            $tds .= '<td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon">'.$symbol.'</span>
                          <input type="text" class="form-control" value="'.$oldPrice.'" pattern="\d+(\.\d+)?"  name="setting[check_out_price_limit]['.$country_id.']" >
                        </div>
                     </td>
                     </tr>';
        }

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => " <br>
                        <h3 class='h3'>".$_e['Check out offer show when cart reach at price']."</h3>
                        <table class='table table-striped table-hover'>$tds</table> <hr>"
        );


        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);

    }


    public function free_default_product($settingData){
        global $_e;

        $relatedData    = $this->productF->productActiveSql('prodet_id,prodet_name');
        $product_array  = array("" => "----");
        foreach ($relatedData as $val) {
            $name = $this->functions->unserializeTranslate($val['prodet_name']);
            $product_array[$val['prodet_id']] = $name;
        }

        $valForm = $this->getIBMSSettingArrayValue('default_free_gift',$settingData);
        $form_fields[] = array(
            "label" => $_e['FREE GIFT PRODUCT'],
            'type'  => 'select',
            'name'  => 'setting[default_free_gift]',
            'array' => $product_array,
            'select'=> "$valForm",
            'class' => 'form-control',
        );

        //Price
        ############# MULTI CURRENCY ################
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency         = new currency_management();
        $countryCodeList    = $this->functions->countrylist(); // country list
        $currency_data      = $c_currency->getList(); // get currency list
        ############# MULTI CURRENCY ################

        $tds                = "";
        $valForm            = unserialize($this->getIBMSSettingArrayValue('check_out_gift_price_limit',$settingData));
        foreach ($currency_data as $val) {
            $country_id     = $val['cur_id'];
            $symbol         = $val['cur_symbol'];
            $country_name   = $countryCodeList[$val['cur_country']];
            $currency       = $val["cur_name"];
            @$oldPrice      = $valForm[$country_id];
            $tds .= "<tr><td width='170'>$country_name ($currency)</td>";
            $tds .= '<td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon">'.$symbol.'</span>
                          <input type="text" class="form-control" value="'.$oldPrice.'" pattern="\d+(\.\d+)?"  name="setting[check_out_gift_price_limit]['.$country_id.']" >
                        </div>
                      </td>
                       </tr>';
        }

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => " <br>
                        <h3 class='h3'>".$_e['Free product add when cart reach at price']."</h3>
                        <table class='table table-striped table-hover'>$tds</table> <hr>"
        );

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);
    }

    public function two_for_3_category($settingData){
        global $_e;

        ################# 3 for 2 Category ##################
        $this->functions->modelClasFile("category.php");
        $category_c = new p_category();
        $category   = $category_c->get_all_category();
        $cat_array  = array("" => "--------");
        foreach ( $category as $val ){
            $cat_array[$val['id']] = $val["nm"];
        }

        $valForm = $this->getIBMSSettingArrayValue('checkout_two_for_3_category',$settingData);
        $form_fields[] = array(
            "label" => $_e['3 for 2 Category'],
            'type'  => 'select',
            'name'  => 'setting[checkout_two_for_3_category]',
            'array' => $cat_array,
            'select' => $valForm,
            'class' => "form-control",
        );

        $format = '<div class="form-group">
                            <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                            <div class="col-sm-8  col-md-9">
                                {{form}}
                            </div>
                        </div>';

        $this->functions->print_form($form_fields,$format);
    }

    public function free_shipping_offer($settingData){
        global $_e;

        ############# MULTI CURRENCY ################
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency = new currency_management();
        $countryCodeList    = $this->functions->countrylist(); // country list
        $currency_data      = $c_currency->getList(); // get currency list
        ############# MULTI CURRENCY ################

        $tds    = "";
        $tds2   = "";
        $valForm = unserialize($this->getIBMSSettingArrayValue('check_out_shiping_price_limit',$settingData));
        foreach ($currency_data as $val) {
            $country_id     = $val['cur_id'];
            $symbol         = $val['cur_symbol'];
            $country_name   = $countryCodeList[$val['cur_country']];
            $currency       = $val["cur_name"];
            @$oldPrice      = $valForm[$country_id];
            $tds  .= "<tr><td width='170'>$country_name ($currency)</td>";
            $tds .= '<td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon">'.$symbol.'</span>
                          <input type="text" class="form-control" value="'.$oldPrice.'" pattern="\d+(\.\d+)?"  name="setting[check_out_shiping_price_limit]['.$country_id.']" >
                        </div>
                      </td></tr>';
        }

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => " <br>
                        <h3 class='h3'>Shipping is free when cart reach at amount</h3>
                        <table class='table table-striped table-hover'>$tds</table> <hr>"
        );

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);

    }

    public function dashboard_graph_setting($settingData){
        global $_e;
        $sql  = "SELECT setting_name,setting_val FROM developer_setting WHERE category = 'graph' AND setting_val='1' ORDER BY setting_name ASC";
        $data = $this->dbF->getRows($sql);
        if(empty($data)){
            return false;
        }

        @$dashboard_graphs = unserialize($this->getIBMSSettingArrayValue('dashboard_graphs',$settingData));
        $form_fields = array();

        //loop for multi language, and stop multi times execute query
        $_w = array();
        foreach($data as $val) {
            $key = $val['setting_name'];
            $label = str_replace("_", " ", $key);
            $_w[$label] = '';
        }
        global $adminPanelLanguage;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Setting');

        foreach($data as $val){
            $key = $val['setting_name'];
            $val = $val['setting_val'];

            @$valForm = $dashboard_graphs[$key];
            $label  = str_replace("_"," ",$key);
            $form_fields[] = array(
                "label" => _uc($_e[$label]),
                'type' => 'checkbox',
                'value' => "$valForm",
                'select' => "$valForm",
                'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Yes']) . '" data-off-label="' . _uc($_e['No']) . '">
                            {{form}}
                          <input type="hidden" name="setting[dashboard_graphs]['.$key.']" class="checkboxHidden" value="' . $valForm . '" />
                         </div>'
            );
        } // 2nd loop end

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);
    }

    /**
     * Add additional price on selected payment
     * @param $settingData
     * @return string
     */
    public function payment_setting($settingData){
        global $_e;
        $sql  = "SELECT setting_name,setting_val FROM developer_setting WHERE category = 'payment' AND setting_val='1' ORDER BY setting_name ASC";
        $data = $this->dbF->getRows($sql);
        if(empty($data)){
            return false;
        }
        $available_payment_method = array();

        //loop for multi language, and stop multi times execute query
        $_w = array();
        foreach($data as $val) {
            $key = $val['setting_name'];
            switch( $key ){
                case "cashOnDelivery":
                    $available_payment_method[0] = 0;
                    break;
                case "paypal":
                    $available_payment_method[1] = 1;
                    break;
                case "klarna":
                    $available_payment_method[2] = 2;
                    break;
                case "payson":
                    $available_payment_method[5] = 5;
                    break;
            }
        }

       // $available_payment_method = array(0); //currently avaiable only cashondelivery...

        //get value from IBMS Setting table
        @$payment_method_price = unserialize($this->getIBMSSettingArrayValue('payment_method_price',$settingData));
        $form_fields = array();


        ############# MULTI CURRENCY ################
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency = new currency_management();
        $countryCodeList    = $this->functions->countrylist(); // country list
        $currency_data      = $c_currency->getList(); // get currency list
        ############# MULTI CURRENCY ################

        $tds    = "";
        foreach($available_payment_method as $key=>$payment){
            @$valForm = $payment_method_price[$payment];
            $label  = $this->productF->paymentArrayFindWeb($payment);

            $tds .= "<tr ><th colspan='2' class='borderIfNotabs'><h4>$label</h4></th></tr>";
            foreach ($currency_data as $val) {
                $country_id = $val['cur_id'];
                $symbol     = $val['cur_symbol'];
                $country_name = $countryCodeList[$val['cur_country']];
                $currency   = $val["cur_name"];
                @$oldPrice  = $valForm[$country_id];

                $tds  .= "<tr><td width='170'>$country_name ($currency)</td>";
                $tds .= '<td>
                            <div class="input-group input-group-sm">
                              <span class="input-group-addon">'.$symbol.'</span>
                              <input type="text" class="form-control" value="'.$oldPrice.'" pattern="\d+(\.\d+)?" name="setting[payment_method_price]['.$payment.']['.$country_id.']" >
                            </div>
                         </td></tr>';
            }

        } // 2nd loop end

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => "<table class='table table-striped table-hover'>$tds</table> <hr>"
        );

        $format = '<div class="form-group">
                        <label class="col-sm-4 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-8  col-md-9">
                            {{form}}
                        </div>
                    </div>';

        $this->functions->print_form($form_fields,$format);

    }


}


?>