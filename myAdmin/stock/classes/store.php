<?php

class store extends object_class{
    public $productF;

    public function __construct(){
        parent::__construct('3');

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../../product_management/functions/product_function.php");
            $this->productF=new product_function();
        }

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //addStore.php
        $_w['Stock Management'] = '' ;
        $_w['Store Location'] = '' ;
        $_w['Store View'] = '' ;
        $_w['Add New'] = '' ;
        $_w['Store Location'] = '' ;
        $_w['View Stores'] = '' ;
        $_w['Add New Store'] = '' ;

        //This class
        $_w['Prodcut Quantity Add in {{n}} different products'] = '' ;
        $_w['New Receipt'] = '' ;
        $_w['Receipt'] = '' ;
        $_w['New Receipt Generate Successfully'] = '' ;
        $_w['New Receipt Generate Failed'] = '' ;
        $_w['Select Country'] = '' ;
        $_w['Store Country'] = '' ;
        $_w['Store City'] = '' ;
        $_w['Store Name'] = '' ;
        $_w['Store Officer Name'] = '' ;
        $_w['Store Description'] = '' ;
        $_w['Add Store'] = '' ;
        $_w['New Store Add Failed!'] = '' ;
        $_w['New Store Add SuccessFully!'] = '' ;
        $_w['Added'] = '' ;
        $_w['Store'] = '' ;
        $_w['SNO'] = '' ;
        $_w['Store Officer'] = '' ;
        $_w['Store Location'] = '' ;
        $_w['Store Desc'] = '' ;
        $_w['Action'] = '' ;


        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin store');



    }

    public function newStoreForm(){
        global $_e;
        $country_list = $this->functions->countrySelectOption();
        $token = $this->functions->setFormToken('storeAdd',false);
        echo '
            <form class="form-horizontal" method="post" role="form">
            '. $token .'
                <div class="form-group">
                    <label for="input1" class="col-sm-2 control-label">'. _uc($_e['Store Officer Name']) .'</label>
                    <div class="col-sm-10">
                        <input type="text" name="storeOfficer" class="form-control" required id="input2" placeholder="'. _uc($_e['Store Officer Name']) .'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="input3" class="col-sm-2 control-label">'. _uc($_e['Store Name']) .'</label>
                    <div class="col-sm-10">
                        <input type="text" name="storeName" class="form-control" required id="input3" placeholder="'. _uc($_e['Store Name']) .'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['Store City']) .'</label>
                    <div class="col-sm-10">
                        <input type="text" name="storeLocation" class="form-control" required id="input2" placeholder="'. _uc($_e['Store City']) .'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['Store Country']) .'</label>
                    <div class="col-sm-10">
                    <select  id="storCountry"  name="storCountry" class="form-control" required="required">
                        <option value="">'. _uc($_e['Select Country']) .'</option>
                           '. $country_list .'
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input4" class="col-sm-2 control-label">'. _uc($_e['Store Description']) .'</label>
                    <div class="col-sm-10">
                        <textarea  name="storeDesc" class="form-control" rows="3" id="input4" placeholder="'. _uc($_e['Store Description']) .'"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="newStore" value="submit" class="btn btn-primary">'. _uc($_e['Add Store']) .'</button>
                    </div>
                </div>
            </form>';

    }



    public function addNewStore(){
        global $_e;
        if(!$this->functions->getFormToken('storeAdd')){
            return false;
        }

        if( isset($_POST['storeOfficer'])  && isset($_POST['storeLocation']) && isset($_POST['storeName']) &&
            !empty($_POST['storeOfficer']) && !empty($_POST['storeLocation']) && !empty($_POST['storeName']) && !empty($_POST['storCountry'])
          ){
                $sql="INSERT INTO `store_name`(`store_owner`,`store_location`,`store_country`,`store_name`,`store_desc`) VALUES(?,?,?,?,?)";
                $arry=array($_POST['storeOfficer'],$_POST['storeLocation'],$_POST['storCountry'],$_POST['storeName'],$_POST['storeDesc']);

                $this->dbF->setRow($sql,$arry);
                $lastId =   $this->dbF->rowLastId;
                if($this->dbF->rowCount>0){
                    $this->functions->notificationError(_js(_uc($_e["Added"])),_js(_uc($_e["New Store Add SuccessFully!"])),"btn-success");
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Store']),$lastId,_uc($_e["New Store Add SuccessFully!"]));
                }else{
                    $this->functions->notificationError(_js(_uc($_e["Added"])),_js(_uc($_e["New Store Add Failed!"])),"btn-danger");
                }
           }else if(isset($_POST['newStore'])){
                    $this->functions->notificationError(_js(_uc($_e["Added"])),_js(_uc($_e["New Store Add Failed!"])),"btn-danger");
           }
    }


    public function StoreList(){
        global $_e;
        echo '<div class="table-responsive">
                <script>$(document).ready(function(){
                    dTableT();
                });
                </script>
                <table class="table table-hover dTableT tableIBMS">
                    <thead>
                    <th>'. _u($_e['SNO']) .'</th>
                    <th>'. _u($_e['Store Officer']) .'</th>
                    <th>'. _u($_e['Store Name']) .'</th>
                    <th>'. _u($_e['Store Location']) .'</th>
                    <th>'. _u($_e['Store Desc']) .'</th>
                    <th>'. _u($_e['Action']) .'</th>
                    </thead>
                <tbody>

                ';
            $data=$this->storeSQL();
        $i=0;
        foreach($data as $val){
            $i++;
            $id=$val['store_pk'];
            echo "<tr class='tr_$id ".$id."_store'>
                    <td>$i</td>
                    <td>$val[store_owner]</td>
                    <td>$val[store_name]</td>
                    <td>$val[store_location] - $val[store_country]</td>
                    <td>$val[store_desc]</td>
                    <td> <div class='btn-group btn-group-sm'>
                        <a data-id='$id' onclick='AjaxEditScript(this);'  data-target='#storeEditModal' class='btn _storeEdit'><i class='glyphicon glyphicon-edit '></i></a>

                         <a data-id='$id' onclick='AjaxDelScript(this);' class='btn'>
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
        $this->productF->modal('Edit Store','store');
        $this->productF->AjaxEditScript('storeEdit','store');
        $this->productF->AjaxUpdateScript('storeEditRequest','store');
        $this->productF->AjaxDelScript('storeAjax_del','store');

    }

    public function storeSQL($column=' * '){
        $sql="SELECT ".$column." FROM `store_name` ORDER BY `store_pk` ASC";
        return $this->dbF->getRows($sql);
    }


    public function storeNamesOption(){
        $data = $this->storeSQL("`store_pk`,`store_name`,`store_location`");
        $op='';
        if($this->dbF->rowCount > 0){
            foreach($data as $val){
                $op .="<option value='$val[store_pk]'>$val[store_name] - $val[store_location]</option>";

            }
            return $op;
        }
        return "";
    }

    public function storeNamesCountryValueOption(){
        $data = $this->storeSQL("`store_pk`,`store_country`,`store_name`,`store_location`");
        $op='';
        if($this->dbF->rowCount > 0){
            foreach($data as $val){
                $op .="<option value='$val[store_country]'>$val[store_name] - $val[store_location]</option>";

            }
            return $op;
        }
        return "";
    }
    public function StoreNameSQL($id){
        $sql="SELECT `store_name`,`store_location` FROM `store_name` WHERE `store_pk` = '$id'";
        $data = $this->dbF->getRow($sql);

        return $data['store_name']." - ".$data['store_location'];
    }
}

?>