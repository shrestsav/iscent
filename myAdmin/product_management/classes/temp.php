<?php

require_once (__DIR__."/../../global_ajax.php"); //connection setting db

class ajax extends object_class{

    private $colorC;
    private $scaleC;
    private $var_del;
    private $var_edit;
    private $var_edit_fromName;

    public function  __construct()
    {
        parent::__construct('3');

        $page=$_GET['page'];
        if($page=='colorAjax_edit' || $page=='AjaxUpdate_color' ||
            $page=='colorAjax_del' || $page=='AjaxAfterUpdateScript_color'){
            $this->color();
        }else if($page=='scaleAjax_edit' || $page=='AjaxUpdate_scale' ||
            $page=='scaleAjax_del' || $page=='AjaxAfterUpdateScript_scale'){
            $this->scale();
        }


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //This Class
        $_w['Add Slot'] = '' ;
        $_w['Delete'] = '' ;
        $_w['Color Name'] = '' ;
        $_w['Scale Name'] = '' ;
        $_w['Store Is Not Empty.\n Please Delete Store`s Product First.'] = '' ;
        $_w['Store In Use'] = '' ;
        $_w['Store Description'] = '' ;
        $_w['Select Country'] = '' ;
        $_w['Store Country'] = '' ;
        $_w['Store City'] = '' ;
        $_w['Store Name'] = '' ;
        $_w['Store Officer Name'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product ajax');


    }

    private function color(){

        require_once (__DIR__."/color.class.php");
        $this->colorC=new colors();

        $this->var_del = $this->colorC->var_del;
        $this->var_edit = $this->colorC->var_edit;
        $this->var_edit_fromName = $this->colorC->var_edit_fromName;
    }

    private function scale(){
        require_once (__DIR__."/scale.class.php");
        $this->scaleC=new scales();

        $this->var_del = $this->scaleC->var_del;
        $this->var_edit = $this->scaleC->var_edit;
        $this->var_edit_fromName = $this->scaleC->var_edit_fromName;
    }

    public function processEdit($page)
    {
        $id = intval($_GET['id']);
        switch($page){
            case 'color':
                $this->createEditFormColor($id); //Color
                break;
            case 'scale':
                $this->createEditFormScale($id); //scale
                break;
        }

    }

    private function createEditFormColor($id)
    {
        global $_e;
        $data = $this->colorC->getDataSQL($id);

        $name = $data[0]['name'];
        $colors = $data[0]['color'];


        $i = 0;
        $trs = "";

        foreach ($colors as $color) {
            $i++;
            $trs .= "
                <tr>
                    <td> $i) </td>
                    <td>
                     <div class='col-xs-8'>
                        <input type='text' style='border-color: #$color[color_name]; border-width: 3px; '
                         class='inp color_picker form-control'
                         name='$this->var_edit_fromName[color][$color[color_id]]'
                         value='$color[color_name]' >
                     </div>
                     <div class='checkbox col-xs-4'>
                        <label><input type='checkbox' name='$this->var_edit_fromName[colorDel][]' value='$color[color_id]'  >". _uc($_e['Delete']) ."</label>
                     </div>
                    </td>
                </tr>
            ";
        }

        echo '
        <input type="hidden" name="'. $this->var_edit_fromName['id'] .'" id="color_edit_id" value="'. $name['colorName_id'].'">

        '. _uc($_e['Color Name']) .' : <input type="text" autocomplete="off" id="color_name" class="inp " name="'.$this->var_edit_fromName['name'].'" value="'. $name['colorName_name'] .'">
        <br><br>

       <table id="slot_table2" class="table slot_table">
                        <tbody>
                        '.$trs .'
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" onclick="addSlot2(); return false;">
                        <i class="icon_bs-plus"></i> '. _uc($_e['Add Slot']) .'
                    </button>
                    <script>
                    color_picker();
                    </script>';

    }


    private function createEditFormScale($id)
    {
        global $_e;
        $data = $this->scaleC->getDataSQL($id);

        $name = $data[0]['name'];
        $scales = $data[0]['scale'];


        $i = 0;
        $trs = "";
        foreach ($scales as $scale) {
            $i++;
            $trs .= "
                <tr>
                    <td> $i) </td>
                    <td>
                    <div class='col-xs-8'>
                        <input type='text' class='inp form-control' name='$this->var_edit_fromName[scale][$scale[scale_id]]' value='$scale[scale_name]' >
                     </div>

                     <div class='checkbox col-xs-4'>
                        <label><input type='checkbox' name='$this->var_edit_fromName[scaleDel][]' value='$scale[scale_id]'>". _uc($_e['Delete']) ."</label>
                     </div>
                     </td>
                </tr>
            ";
        }

        $id  =  $this->var_edit_fromName['id'];
        echo '
        <input type="hidden" name="'.$id .'" id="scale_edit_id" value="'.$name['scaleName_id'] .'">

        '. _uc($_e['Scale Name']) .' : <input type="text" autocomplete="off" id="scale_name" class="inp" name="'.$this->var_edit_fromName['name'] .'" value="'.$name['scaleName_name'] .'">

        <br /><br />

        <table id="slot_table2" class="table slot_table">
            <tbody>
            '. $trs .'
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" onclick="addSlot2(); return false;">
            <i class="icon_bs-plus"></i> '. _uc($_e['Add Slot']) .'
        </button>';


    }


    public function AjaxAfterUpdateScript_color(){
        $id=$_GET['id'];
        $sql_2 = "SELECT * FROM `colors` WHERE `color_name_id` = '$id' ";
        $data=$this->dbF->getRows($sql_2);

        if($this->dbF->rowCount>0){
            foreach($data as $key=>$val){
                echo "<div class='colorBox' style='background-color:#$val[color_name]' ></div>";
            }
        }
    }


    public function AjaxUpdate_color(){
        if (isset($_POST[$this->var_edit_fromName]))
        {
            $form = $_POST[$this->var_edit_fromName];
            $this->updateColorSQL($form);
        }
    }

    private function updateColorSQL($form)
    {
        $id = intval($form['id']);
        @$colors = $form['color'];
        @$name = $form['name'];
        if($name==""){
            echo '0';
            exit;
        }

        $sql = "UPDATE `color_name` SET `colorName_name` =  ? WHERE `colorName_id` = ? ";
        $arry=array($name,$id);
        $this->dbF->setRow($sql,$arry);

        if (is_array($colors) && $id > 0) {

            $sql = "INSERT INTO `colors` (`color_id`,`color_name`,`color_name_id`) VALUES (?,?,?)
                        ON DUPLICATE KEY
                        UPDATE `color_name`= ? ";

            foreach ($colors as $key => $color) {
                $key = intval($key);
                $color_name = $color;
                if (!isset($form['colorDel']) || !in_array(intval($key), $form['colorDel'])) {
                    $arry=array($key, $color_name, $id, $color_name);
                    $this->dbF->setRow($sql,$arry);
                }
            }

            if (isset($form['colorDel']) && is_array($form['colorDel'])) {
                $ids = "";
                foreach ($form['colorDel'] as $del_id) {
                    $ids .= intval($del_id) . ",";
                }
                $ids = trim($ids, ",");
                $sql = "DELETE FROM `colors` WHERE `color_id` IN ($ids) ";
                $qry = $this->dbF->setRow($sql);
            }
        }

        echo "1";
    }


    public function AjaxDelScript_color()
    {
        @$id = intval($_POST['itemId']);
        $sql = "DELETE FROM `color_name` WHERE `colorName_id` = '$id' ";
        $this->dbF->setRow($sql);
        if ($this->dbF->rowCount > 0) {
            echo '1';
        } else {
            echo '0';
        }

    }

    public function AjaxDelScript_scale()
    {
        @$id = intval($_POST['itemId']);
        $sql = "DELETE FROM `scale_name` WHERE `scaleName_id` = '$id' ";
        $this->dbF->setRow($sql);
        if ($this->dbF->rowCount > 0) {
            echo '1';
        } else {
            echo '0';
        }

    }


    public function AjaxUpdate_scale(){
        if (isset($_POST[$this->var_edit_fromName]))
        {
            $form = $_POST[$this->var_edit_fromName];
            $this->updateScaleSQL($form);
        }
    }

    private function updateScaleSQL($form)
    {
        try{
            $this->db->beginTransaction();

            $id = intval($form['id']);
            @$scales = $form['scale'];
            @$name = $form['name'];

            $sql = "UPDATE `scale_name` SET `scaleName_name` =  ? WHERE `scaleName_id` = ? ";
            $arry =array($name,$id);
            $this->dbF->setRow($sql,$arry,false);

            if (is_array($scales) && $id > 0) {

                $sql = "INSERT INTO `scales` (`scale_id`,`scale_name`,`scale_name_id`) VALUES (?,?,?)
                        ON DUPLICATE KEY
                        UPDATE `scale_name`= ? ";

                foreach ($scales as $sid => $scale) {
                    $sid = intval($sid);
                    $scale_name = $scale;
                    $sm_id = $id;
                    if (!isset($form['scaleDel']) || !in_array(intval($sid), $form['scaleDel'])) {
                        $arry =array($sid, $scale_name, $sm_id, $scale_name);
                        $this->dbF->setRow($sql,$arry,false);
                    }
                }

                if (isset($form['scaleDel']) && is_array($form['scaleDel'])) {
                    $ids = "";
                    foreach ($form['scaleDel'] as $del_id) {
                        $ids .= intval($del_id) . ",";
                    }
                    $ids = trim($ids, ",");
                    $sql = "DELETE FROM `scales` WHERE `scale_id` IN ($ids) ";
                    $qry = $this->dbF->setRow($sql,false);
                }
            }

            $this->db->commit();
            echo '1';
        }catch (Exception $e){
            echo '0';
            $this->dbF->error_submit($e);
            $this->db->rollBack();

        }
    }

    public function AjaxAfterUpdateScript_scale(){
        $id=$_GET['id'];
        $sql_2 = "SELECT * FROM `scales` WHERE `scale_name_id` = '$id' ";
        $data=$this->dbF->getRows($sql_2);

        $temp='';
        if($this->dbF->rowCount>0){
            foreach($data as $key=>$val){
                $temp .=  $val['scale_name'] . ', ';
            }
            $temp= trim($temp);
            echo trim($temp,',');
        }
    }




    public function AjaxUpdate_currency(){
        $form_array_prefix ='edit_currency_form';
        if (isset($_POST[$form_array_prefix])) {
            $form = $_POST[$form_array_prefix];
            if (
                isset($form['country']) && !empty($form['country'])
                && isset($form['cid']) && !empty($form['cid'])
                && isset($form['currency']) && !empty($form['currency'])
                && isset($form['symbol']) && !empty($form['symbol'])
            ) {
                $sql = "UPDATE `currency` SET
                            `cur_country` = ?,
                            `cur_name` = ?,
                            `cur_symbol` = ?
                            WHERE `cur_id` = ?";
                $arry=array( $form['country'], $form['currency'], $form['symbol'],$form['cid']);
                $this->dbF->setRow($sql,$arry);
                echo '1';
            }
        }
    }

    public function AjaxAfterUpdateScript_currency(){
        $id=$_GET['id'];
        $data = $this->dbF->getRow("SELECT * FROM `currency` WHERE `cur_id`='$id'");
        if($this->dbF->rowCount > 0){
            $con= $this->functions->countrylist()[$data['cur_country']];
            echo  '<td>'.$con.'</td>
                <td>'.$data['cur_name'].'</td>
                <td>'.$data['cur_symbol'].'</td>
                <td>

                <div class="btn-group btn-group-sm">
                  <a data-toggle="modal" href="#currencyEditModal" onclick="formEditInit(\''.$data['cur_id'].'\',\''.$data['cur_country'].'\',\''.$data['cur_name'].'\',\''.$data['cur_symbol'].'\')"  class="btn"><i class="glyphicon glyphicon-edit"></i></a>
                  <a data-id="'.$data['cur_id'].'" onclick="AjaxDelScript(this);" class="btn secure_delete">
                    <i class="glyphicon glyphicon-trash trash"></i>
                    <i class="fa fa-refresh waiting fa-spin" style="display: none"></i>
                  </a>
                </div>
                </td>';
        }
    }


    public function AjaxDelScript_currency()
    {
        $id = intval($_GET['id']);
        $sql = "DELETE FROM `currency` WHERE `cur_id`= ?";
        $arry=array($id);
        $this->dbF->setRow($sql,$arry);
        if ($this->dbF->rowCount > 0) {
            echo '1';
        } else {
            echo '0';

        }

    }

    public function AjaxDelScript_product()
    {
        try{
            $this->db->beginTransaction();
            @$id = intval($_POST['itemId']);


            $sql3="SELECT * FROM `product_image` WHERE `product_id`='$id'";
            $data=$this->dbF->getRows($sql3,false);
            foreach($data as $key=>$val){
                unlink(__DIR__."/../../../images/$val[image]");
            }
            $sql3="DELETE FROM `product_image` WHERE `product_id`='$id'";
            $this->dbF->setRow($sql3,false);

            $sql3="DELETE FROM `proudct_detail` WHERE `prodet_id`='$id'";
            $this->dbF->setRow($sql3);

            if ($this->dbF->rowCount > 0) {
                echo '1';
            } else {
                echo '0';
            }
            $this->db->commit();
        }catch(Exception $e){
            echo '0';
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }


    public function AjaxDelScript_productSelected(){
        try{
            $ids=$_POST['id'];
            $this->db->beginTransaction();
            $ids=explode(",",$ids);
            for($i=0;$i<sizeof($ids);$i++){
                $id=$ids[$i];

                $sql3="SELECT * FROM `product_image` WHERE `product_id`='$id'";
                $data=$this->dbF->getRows($sql3,false);
                foreach($data as $key=>$val){
                    unlink(__DIR__."/../../../images/$val[image]");
                }
                $sql3="DELETE FROM `product_image` WHERE `product_id`='$id'";
                $this->dbF->setRow($sql3,false);

                $sql3="DELETE FROM `proudct_detail` WHERE `prodet_id`='$id'";
                $this->dbF->setRow($sql3);
            }


            $this->db->commit();
            echo "1";
        }catch(Exception $e){
            echo "0";
            $this->db->rollBack();
            $this->dbF->error_submit($e);
        }
    }

    public function AjaxDelScript_productImageDel(){
        $id=$_POST['imageId'];

        $sql3="SELECT * FROM `product_image` WHERE `img_id`='$id'";
        $data=$this->dbF->getRow($sql3);

        unlink(__DIR__."/../../../images/$data[image]");

        $sql3="DELETE FROM `product_image` WHERE `img_id`='$id'";
        $this->dbF->setRow($sql3);

        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }

    }

    function AjaxDelScript_storeDel(){
        global $_e;
        $id=$_POST['itemId'];

        $sql="SELECT * FROM  `product_inventory`  WHERE `qty_store_id`='$id' AND `qty_item`>'0'";
        $this->dbF->getRows($sql);
        if($this->dbF->rowCount>0){
            echo "<script>jAlert(". _js($_e["Store Is Not Empty.\n Please Delete Store`s Product First."]).",". _js($_e['Store In Use']).");</script>";
        }else{
            $sql3="DELETE FROM `store_name` WHERE `store_pk`='$id'";
            $this->dbF->setRow($sql3);

            if($this->dbF->rowCount>0){
                echo "1";
            }else{
                echo "0";
            }
        }
    }

    function AjaxDelScript_receiptDel(){
        $id=$_POST['itemId'];

        $sql3="DELETE FROM `purchase_receipt` WHERE `receipt_pk`='$id'";
        $this->dbF->setRow($sql3);

        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }

    public function AjaxEditStore(){
        global $_e;
        $id = $_GET['id'];
        $sql="SELECT * FROM `store_name` WHERE `store_pk` = '$id' ";
        $data = $this->dbF->getRow($sql);
        $country_list = $this->functions->countrySelectOption();

        echo '<div class="form-horizontal">
                <div class="form-group">
                    <label for="input1" class="col-sm-4 control-label">'. _uc($_e['Store Officer Name']) .'</label>
                    <div class="col-sm-8">
                        <input type="hidden" value="'.$data['store_pk'].'" id="store_edit_id" name="storeId" />
                        <input type="text" value="'.$data['store_owner'].'" name="storeOfficer" class="form-control" required id="input2" placeholder="'. _uc($_e['Store Officer Name']) .'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="input3" class="col-sm-4 control-label">'. _uc($_e['Store Name']) .'</label>
                    <div class="col-sm-8">
                        <input type="text" value="'.$data['store_name'].'"  name="storeName" class="form-control" required id="input3" placeholder="'. _uc($_e['Store Name']) .'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-4 control-label">'. _uc($_e['Store City']) .'</label>
                    <div class="col-sm-8">
                        <input type="text" value="'.$data['store_location'].'" name="storeLocation" class="form-control" required id="input2" placeholder="'. _uc($_e['Store City']) .'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-4 control-label">'. _uc($_e['Store Country']) .'</label>
                    <div class="col-sm-8">
                    <select name="storCountry" id="storCountry" class="form-control" required="required">
                        <option value="">'. _uc($_e['Select Country']) .'</option>
                            '.$country_list.'
                        </select>
                        <script>
                        $(document).ready(function(){
                            $("#storCountry").val("'.$data['store_country'].'").change();
                        });
                        </script>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input4" class="col-sm-4 control-label">'. _uc($_e['Store Description']) .'</label>
                    <div class="col-sm-8">
                        <textarea  name="storeDesc" class="form-control" rows="3" id="input4" placeholder="'. _uc($_e['Store Description']) .'">'.$data['store_desc'].'</textarea>
                    </div>
                </div>

                </div>';
    }

    public function AjaxEditRequestStore(){
        if( isset($_POST['storeOfficer'])  && isset($_POST['storeLocation']) && isset($_POST['storeName']) &&
            !empty($_POST['storeOfficer']) && !empty($_POST['storeLocation']) && !empty($_POST['storeName']) && !empty($_POST['storCountry'])
        ){
            $id =$_POST['storeId'];

            $sql="UPDATE `store_name` SET
                    `store_owner`=?,
                    `store_location`=?,
                    `store_country`=?,
                    `store_name`=?,
                    `store_desc`=?
                    WHERE `store_pk` = '$id'";
            $arry=array($_POST['storeOfficer'],$_POST['storeLocation'],$_POST['storCountry'],$_POST['storeName'],$_POST['storeDesc']);

            $this->dbF->setRow($sql,$arry);
            if($this->dbF->rowCount>0)  echo '1';
            else  echo '0';
        }else{
            echo '0';
        }
    }


    public function AjaxAfterUpdateScript_store(){
        $id = $_GET['id'];
        $sql="SELECT * FROM `store_name` WHERE `store_pk` = '$id' ";
        $val = $this->dbF->getRow($sql);
        echo "
                    <td>*</td>
                    <td>$val[store_owner]</td>
                    <td>$val[store_name]</td>
                    <td>$val[store_location] - $val[store_country]</td>
                    <td>$val[store_desc]</td>
                    <td><div class='btn-group btn-group-sm'>
                        <a data-id='$id'  data-target='#storeEditModal' onclick='AjaxEditScript(this);' class='btn _storeEdit'><i class='glyphicon glyphicon-edit '></i></a>

                         <a data-id='$id' onclick='AjaxDelScript(this);' class='btn'>
                                 <i class='glyphicon glyphicon-trash trash'></i>
                                 <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                         </a>
                         </div>
                    </td>
                ";
    }

    public function AjaxDelScript_discountDel(){
        $id     =   $_POST['id'];
        $sql ="DELETE FROM `product_discount` WHERE product_discount_pk = '$id'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }
    public function AjaxDelScript_holeSaleDel(){
        $id     =   $_POST['id'];
        $sql ="DELETE FROM `product_sale` WHERE pSale_pk = '$id'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }
    public function AjaxDelScript_couponDel(){
        $id     =   $_POST['id'];
        $sql ="DELETE FROM `product_coupon` WHERE pCoupon_pk = '$id'";
        $this->dbF->setRow($sql);
        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }


    public  function sortProductImage(){
        $list=$_POST['image'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `product_image` SET sort='$i' WHERE `img_id`='$list[$i]'";
            $data=$this->dbF->setRow($sql3);
        }
    }

    public  function pImageAltUpdate(){
        $id=$_POST['imageId'];
        $alt=$_POST['altT'];
        $sql3="UPDATE `product_image` SET alt=? WHERE `img_id`='$id'";
        $array = array($alt);
        $data=$this->dbF->setRow($sql3,$array);
        if($this->dbF->rowCount>0){
            echo "1";
        }else{
            echo "0";
        }
    }

    public  function sortProducts(){
        $list=$_POST['sort'];
        for ($i = 0; $i < count($list); $i++) {
            $sql3="UPDATE `proudct_detail` SET sort='$i' WHERE `prodet_id`='$list[$i]'";
            $data=$this->dbF->setRow($sql3);
        }
    }
}
?>