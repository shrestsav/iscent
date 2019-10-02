<?php

class shipping extends object_class{
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
        $_w     =   array();
        //index.php
        $_w['Shipping Management'] = '' ;

        //Shipping.php
        $_w['Shipping'] = '' ;
        $_w['Shipping View'] = '' ;
        $_w['New Shipping'] = '' ;
        $_w['Add New Shipping Country'] = '' ;
        $_w['Delete Fail Please Try Again.'] = '' ;

        //shippingEdit.php
        $_w['INTERNATIONAL SHIPPING'] = '' ;
        $_w['WEIGHT'] = '' ;
        $_w['SHIPMENT PRICE'] = '' ;
        $_w['SHIPMENT COUNTRY'] = '' ;
        $_w['SNO'] = '' ;
        $_w['UPDATE'] = '' ;
        $_w['Show All Other Countries'] = '' ;

        //shippingByClass.php
        $_w['Shipping By Classes'] = '' ;
        $_w['Active'] = '' ;
        $_w['Draft'] = '' ;
        $_w['Add New'] = '' ;
        $_w['Added'] = '' ;

        //This class
        $_w['Weight'] = '' ;
        $_w['International Shipping'] = '' ;
        $_w['SUBMIT'] = '' ;
        $_w['Shipping From'] = '' ;
        $_w['Select Country'] = '' ;
        $_w['Shipping To'] = '' ;
        $_w['Price Currency'] = '' ;
        $_w['Select Currency'] = '' ;
        $_w['Price per {{weight}}'] = '' ;
        $_w['Shipping Price'] = '' ;

        $_w['Success'] = '' ;
        $_w['Shipping Add Successfully'] = '' ;
        $_w['Shipping Add Failed'] = '' ;
        $_w['Shipping Save Successfully'] = '' ;
        $_w['Shipping Save Failed'] = '' ;
        $_w['Shipping Add Fail required field are empty'] = '' ;
        $_w['Duplicate shipping Found'] = '' ;
        $_w['Error']    = '';
        $_w['Added']    = '';
        $_w['SHIPMENT PRICE - WEIGHT'] = '' ;

        $_w['Publish']  = '';
        $_w['NO']       = '';
        $_w['GO BACK']       = '';
        $_w['SNO']       = '';
        $_w['NAME']      = '';
        $_w['ACTION']    = '';
        $_w['Shipping Class Name']    = '';
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Shipping');
    }

    public function addNewShippingForm(){
        global $_e;

        $country_list       =   $this->functions->countrySelectOption();
        $availableCountry   =   $this->productF->productCurrencyCountries();
        $availableCurrency  =   $this->productF->productCurrencySelectCurrency();
        $shpWeightSelect    =   $this->shpWeightSelect();

        echo '
            <form class="form-horizontal" method="post" role="form" onsubmit="'. "return $('#currency').removeAttr('disabled');".'">
                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['Shipping From']) .'</label>
                    <div class="col-sm-10">
                    <select  id="fromCountry"  name="fromCountry" class="form-control countryList" required="required">
                        <option value="">'. _uc($_e['Select Country']) .'</option>
                            '.$availableCountry.'
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['Shipping To']) .'</label>
                    <div class="col-sm-10">
                    <select  id="toCountry"  name="toCountry" class="form-control countryList" required="required">
                        <option value="">'. _uc($_e['Select Country']) .'</option>
                            '.$country_list.'
                        </select>
                    </div>
                </div>

                <div class="form-group" >
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['Price Currency']) .'</label>
                    <div class="col-sm-10">
                    <select  id="currency" disabled  name="currency" class="form-control" required="required">
                        <option value="">'. _uc($_e['Select Currency']) .'</option>
                            '.$availableCurrency.'
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _replace('{{weight}}','<span id="perkg"></span>',$_e['Price per {{weight}}']) .'</label>
                    <div class="col-sm-10">
                        <input type="text" name="shipping_price" class="form-control"  id="shipping_price" placeholder="'. _uc($_e['Shipping Price']) .'"  required="required"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['Weight']) .'</label>
                    <div class="col-sm-10">
                    <select  id="shpWeight"  name="shpWeight" class="form-control countryList" required="required">
                            '. $shpWeightSelect .'
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input2" class="col-sm-2 control-label">'. _uc($_e['International Shipping']) .'</label>
                    <div class="col-sm-10">
                        <div class="make-switch" data-off="danger" data-on="success">
                            <input type="checkbox" name="intShp" value="1">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" onclick="return submitCheck();">'. _u($_e['SUBMIT']) .'</button>
            </form>';

    }

    public function shpWeightSelect($old=false){
        //Payment type select box create
        $payment = $this->shpWeightArray();
        $option='';
        foreach($payment as $key=>$val){
            $select ='';
            if($old != false){
                if($key == $old){
                   $select = "selected";
                }
            }
            $option.= "<option value='$key' $select>$val</option>";
        }
        return $option;
    }

    public function shpWeightArrayFind($id){
        $invoice = $this->shpWeightArray();
        return $invoice[$id];
    }

    public function shpWeightArray(){
        $arr=array(0=>"0.5 Kg",1=>"1 Kg",2=>"2 Kg");
        return $arr;
    }

    public function addNewShippingSubmit(){
        global $_e;
        if( isset($_POST['fromCountry'])  && isset($_POST['toCountry']) && isset($_POST['shipping_price']) &&
            !empty($_POST['fromCountry']) && !empty($_POST['toCountry']) && !empty($_POST['shipping_price'])
          ){
            $internationalShipping = isset($_POST['intShp']) ? $_POST['intShp'] : 0;
            $hash       =  $_POST['fromCountry'].":".$_POST['toCountry'];
                $sql    ="INSERT INTO `shipping`(
                                `shp_from`,`shp_to`,`shp_price`,
                                `shp_price_code`,`shp_weight`,`shp_int`,`hash`
                                )
                                VALUES(?,?,?,?,?,?,?)";

                $arry=array(
                    $_POST['fromCountry'] , $_POST['toCountry'] , $_POST['shipping_price'],
                    $_POST['currency'] , $_POST['shpWeight'] , $internationalShipping , $hash);

                try{
                    $this->db->beginTransaction();
                    $this->dbF->setRow($sql,$arry,false);
                    $lastId =   $this->dbF->rowLastId;
                    $this->db->commit();
                    if($this->dbF->rowCount>0){
                        $this->functions->notificationError(_js(_uc($_e["Success"])),_js($_e["Shipping Add Successfully"]),"btn-success");
                        $this->functions->setlog(_uc($_e['Added']),_uc($_e['Shipping']),$lastId,_uc($_e['Shipping Add Successfully']));
                    }else{
                        $this->functions->notificationError(_js(_uc($_e["Error"])),_js($_e["Shipping Add Failed"]),"btn-danger");
                    }
                }catch (Exception $e){
                    $this->db->rollBack();
                    $this->functions->notificationError("Error","Duplicate shipping Found","btn-danger");
                }
           }else if(isset($_POST['fromCountry'])){
              $this->functions->notificationError(_js(_uc($_e["Error"])),_js($_e["Shipping Add Fail required field are empty"]),"btn-danger");
           }
    }

    public function addNewShippingUpdate(){
        global $_e;
        if(isset($_POST) && !empty($_POST['toCountry'])){
            try{
                $this->db->beginTransaction();
                $from = $_POST['from'];

                $sql ="DELETE FROM `shipping` WHERE `shp_from` = '$from'";
                $this->dbF->setRow($sql,false);

                foreach($_POST['toCountry'] as $val){
                    $to         = $val;
                    $price      = $_POST[$val."_price"];
                    $weight     = $_POST[$val."_weight"];
                    $price_code = $_POST[$val."_price_code"];
                    $intShp     = isset($_POST[$val."_intShp"]) ? $_POST[$val."_intShp"] : 0;

                    $hash       =  $from.":".$to;
                    $sql="INSERT INTO `shipping`(
                                    `shp_from`,`shp_to`,`shp_price`,
                                    `shp_price_code`,`shp_weight`,`shp_int`,`hash`
                                    )
                                    VALUES(?,?,?,?,?,?,?)";

                    $arry=array($from,$to,$price,
                        $price_code,$weight,$intShp, $hash);
                    $this->dbF->setRow($sql,$arry,false);
                }

                $this->db->commit();
            }catch(Exception $e){
                $this->db->rollBack();
            }
        }
    }

    public function ShippingList(){
        global $_e;
        $countryList    = $this->functions->countrylist();
        $sql        =   "SELECT * FROM `shipping`  GROUP BY `shp_from` ORDER BY `shp_to` ASC";
        $dataGroup  =   $this->dbF->getRows($sql);
        foreach($dataGroup as $g){
            $shp_From = $g['shp_from'];
            $shp_country = $countryList[$shp_From];
            echo '<div class="table-responsive">
                    <table class="table table-hover tableIBMS">
                        <thead>
                            <tr><th colspan="4" >
                                <div class="text-center col-sm-12"> '.$shp_country.'
                                    <a href="-shipping?page=edit&country='.$shp_From.'" class="btn pull-right  navbar-inverse btn-sm editShipping">
                                        <div  id="'.$g['shp_pk'].'" class="" style="right: 40px;">
                                        <i class="glyphicon glyphicon-edit "></i>
                                        </div>
                                    </a>
                                     <a data-id="'.$shp_From.'" onclick="deleteShipping(this)"  class="btn  navbar-inverse btn-sm pull-right deleteShipping">
                                        <div  class="">
                                        <i class="glyphicon glyphicon-trash trash"></i>
                                        <i class="fa fa-refresh waiting fa-spin" style="display: none"></i>
                                        </div>
                                    </a>
                                </div>

                            </th></tr>
                        </thead>
                    <tbody>
                    <tr class="gray-tr">
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['SHIPMENT COUNTRY']) .'</th>
                        <th>'. _u($_e['SHIPMENT PRICE - WEIGHT']) .'</th>
                        <th>'. _u($_e['INTERNATIONAL SHIPPING']) .'</th>
                    </tr>';

            $sql    =   "SELECT * FROM `shipping` WHERE `shp_from` = '$shp_From' ORDER BY `shp_to` ASC";
            $data   =   $this->dbF->getRows($sql);
            $i      =   0;
            foreach($data as $val){
                $i++;
                $country     = $countryList[$val['shp_to']];
                $weight     = $this->shpWeightArrayFind($val['shp_weight']);
                $symbol = $this->productF->productCurrencysymbol($val['shp_price_code']);
                $status = "<div class='btn btn-danger btn-sm'>OFF</div>";
                if($val['shp_int']=='1'){
                    $status = "<div class='btn btn-success btn-sm'>ON</div>";
                }
                echo "<tr>
                        <td>$i</td>
                        <td>$country</td>
                        <td>$val[shp_price] $symbol  (on $weight)</td>
                        <td>$status</td>
                     </tr>";
            }

            echo '</tbody>
                </table>
            </div> <!-- .table-responsive End -->
            <div class="clearfix"></div>
            <div class="padding-20"></div>';

        }
    }

    public function shippingSQL(){
        $sql="SELECT * FROM `shipping` ORDER BY `shp_pk` DESC";
        return $this->dbF->getRows($sql);
    }

    public function shippingClassView(){
        $sql    = "SELECT * FROM shipping_class WHERE publish = '1'";
        $data   = $this->dbF->getRows($sql);
        $this->shippingClassPrint($data);
    }

    public function shippingClassDraft(){
        $sql    = "SELECT * FROM shipping_class WHERE publish = '0'";
        $data   = $this->dbF->getRows($sql);
        $this->shippingClassPrint($data);
    }

    public function shippingClassPrint($data){
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTableFull tableIBMS">
                    <thead>
                        <th>'. _u($_e['SNO']) .'</th>
                        <th>'. _u($_e['NAME']) .'</th>
                        <th>'. _u($_e['ACTION']) .'</th>
                    </thead>
                <tbody>';
        $i = 0;
        foreach($data as $val){
            $i++;
            $id     = $val['id'];
            $name   = $val['name'];

            echo "<tr>
                    <td>$i</td>
                    <td>$name</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-".$this->functions->getLinkFolder(false)."&editId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteShipClass(this);' class='btn'>
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

    public function shippingClassForm($new = false){

        global $_e;
        $isEdit = false;
        $form_fields    = array();
        if($new){
            $token      = $this->functions->setFormToken('shippingClass',false);
        }else {
            $isEdit     = true;
            $token      = $this->functions->setFormToken('shippingClassEdit', false);
            $id         = $_GET['editId'];
            $sql        = "SELECT * FROM shipping_class WHERE id = '$id'";
            $data       = $this->dbF->getRow($sql);

            $form_fields[] = array(
                'type'   => 'hidden',
                'value'  =>  $id,
                'name'   => 'editId',
            );
        }

        $form_fields[] = array(
            'type'   => 'none',
            'thisFormat' => $token,
        );

        $form_fields[] = array(
            'label' => _uc($_e['Shipping Class Name']),
            'name'  => 'insert[name]',
            'value' => @$data['name'],
            'type'  => 'text',
            'class' => 'form-control',
        );

        //publish
        @$valFormTemp = ($data['publish'] == '0') ? "0" : '1';
        $form_fields[]  = array(
            "label" => $_e['Publish'],
            'type'  => 'checkbox',
            'value' => "$valFormTemp",
            'select' => "$valFormTemp",
            'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['NO']) .'">
                            {{form}}
                          <input type="hidden" name="insert[publish]" class="checkboxHidden" value="'.$valFormTemp.'" />
                         </div>'
        );


        //Price
        $this->functions->includeAdminFile("product_management/classes/currency.class.php");
        $c_currency     = new currency_management();
        $countryCodeList    = $this->functions->countrylist(); // country list
        $currency_data      = $c_currency->getList(); // get currency list
        $tds        = "";
        $tds2       = "";
        @$valForm    = unserialize($data['price']);
        foreach ($currency_data as $val) {
            $country_id     = $val['cur_id'];
            $symbol         = $val['cur_symbol'];
            $country_name   = $countryCodeList[$val['cur_country']];
            $currency       = $val["cur_name"];
            @$oldPrice      = $valForm[$country_id];
            $tds .= "<tr><td width='200'>$country_name ($currency)</td>".'<td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon">'.$symbol.'</span>
                          <input type="text" class="form-control" value="'.$oldPrice.'" name="insert[price]['.$country_id.']" >
                        </div>
                      </td>
                      </tr>';
        }

        $form_fields[] = array(
            'type' => 'none',
            'thisFormat' => " <br>
                            <h3>{$_e['Shipping Price']}</h3>
                            <table class='table table-striped table-hover'>$tds</table> <hr>"
        );

        $form_fields[]  = array(
            "name"  => 'submit',
            'class' => 'btn btn-primary',
            'type'  => 'submit',
            'value' => _u($_e['SUBMIT']),
            'thisFormat' => '{{form}}'
        );

        $form_fields['form']  = array(
            'type'      => 'form',
            'class'     => "form-horizontal",
            'action'    => "-".$this->functions->getLinkFolder(false),
            'method'    => 'post',
            'format'    => '{{form}}'
        );

        $format     = '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                            <div class="col-sm-10  col-md-9">
                                {{form}}
                            </div>
                        </div>';

        $this->functions->print_form($form_fields,$format);
    }

    public function shippingClassFormSubmit(){
        global $_e;
        if(isset($_POST) && !empty($_POST) ){
            if($this->functions->getFormToken('shippingClass')){
                $lastId = $this->functions->formInsert("shipping_class",$_POST['insert']);
                if($lastId>0){
                    $this->functions->notificationError(_js(_uc($_e["Success"])),_js($_e["Shipping Add Successfully"]),"btn-success");
                    $this->functions->setlog(_uc($_e['Added']),_uc($_e['Shipping']),$lastId,_uc($_e['Shipping Add Successfully']));
                }else{
                    $this->functions->notificationError(_js(_uc($_e["Error"])),_js($_e["Shipping Add Failed"]),"btn-danger");
                }
            }
        }
    }


    public function shippingClassFormUpdate(){
        global $_e;
        if(isset($_POST) && !empty($_POST) ){
            if($this->functions->getFormToken('shippingClassEdit')){
                $lastId = $_POST['editId'];
                $return = $this->functions->formUpdate("shipping_class",$_POST['insert'],$lastId);
                if($return){
                    $this->functions->notificationError(_js(_uc($_e["Success"])),_js($_e["Shipping Save Successfully"]),"btn-success");
                    $this->functions->setlog(_uc($_e['UPDATE']),_uc($_e['Shipping']),$lastId,_uc($_e['Shipping Save Successfully']));
                }else{
                    $this->functions->notificationError(_js(_uc($_e["Error"])),_js($_e["Shipping Save Failed"]),"btn-danger");
                }
            }
        }
    }


}

?>