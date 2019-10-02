<?php

require_once(__DIR__."/../../product_management/functions/product_function.php");



class purchase_receipt extends object_class{

    public $product;

    public $productF;



    public function __construct(){

        parent::__construct('3');

        $this->product=new product_function();



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

        //purchasereceipt.php

        $_w['View All Receipts'] = '' ;

        $_w['Purchase Receipt'] = '' ;

        $_w['Purchase View'] = '' ;

        $_w['Add New Receipt'] = '' ;



        //This class

        $_w['SNO'] = '' ;

        $_w['VENDOR'] = '' ;

        $_w['STORE NAME'] = '' ;

        $_w['RECEIPT DATE'] = '' ;

        $_w['REGISTER DATE'] = '' ;

        $_w['ACTION'] = '' ;

        $_w['Vendor Name'] = '' ;

        $_w['Purchasing Date'] = '' ;

        $_w['Date'] = '' ;

        $_w['Select Store'] = '' ;

        $_w['Add In store'] = '' ;

        $_w['SINGLE PRICE'] = '' ;

        $_w['QTY'] = '' ;

        $_w['PRODUCT COLOR'] = '' ;

        $_w['PRODUCT SCALE'] = '' ;

        $_w['No Scale Avaiable'] = '' ;

        $_w['No Color Avaiable'] = '' ;

        $_w['Enter Product Color'] = '' ;

        $_w['Enter Product Scale'] = '' ;

        $_w['Enter Product Name'] = '' ;

        $_w['Enter Single Product Price'] = '' ;

        $_w['Enter Product Quantity'] = '' ;

        $_w['Add Product'] = '' ;

        $_w['Remove Checked Items'] = '' ;

        $_w['Check/Uncheck All'] = '' ;

        $_w['PRODUCT'] = '' ;

        $_w['PRICE'] = '' ;

        $_w['Generate Receipt'] = '' ;



        $_w['Prodcut Quantity Add in {{n}} different products'] = '' ;

        $_w['New Receipt'] = '' ;

        $_w['Receipt'] = '' ;

        $_w['New Receipt Generate Successfully'] = '' ;

        $_w['New Receipt Generate Failed'] = '' ;





        //Data Range

        $_w['Search By Date Range'] = '' ;

        $_w['Date From'] = '' ;

        $_w['Date To'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin StoreReceipt');





    }



    public function newReceiptForm(){

        global $_e;

        $token = $this->functions->setFormToken('purchaseReceiptAdd',false);

        echo '

    <form method="post" class="form-horizontal" role="form">

    '.$token.'

        <div class="form-horizontal">



            <div class="col-md-6">

                <div class="form-group">

                    <label for="receipt_date" class="col-sm-2 col-md-3 control-label">'. _uc($_e['Date']) .'</label>

                    <div class="col-sm-10 col-md-9">

                        <input type="text" name="receipt_date" class="form-control date" required id="receipt_date" placeholder="'. _uc($_e['Purchasing Date']) .'">

                    </div>

                </div>



                <div class="form-group">

                    <label for="receipt_vendor" class="col-sm-2 col-md-3 control-label">'. _uc($_e['Vendor Name']) .'</label>

                    <div class="col-sm-10 col-md-9">

                        <input type="text" name="receipt_vendor" class="form-control" required id="receipt_vendor" placeholder="'. _uc($_e['Vendor Name']) .'">

                    </div>

                </div>



                  <div class="form-group">

                    <label for="receipt_store_id" class="col-sm-2 col-md-3 control-label">'. _uc($_e['Add In store']) .'</label>

                    <div class="col-sm-10 col-md-9">

                    <input type="hidden" name="receipt_store_id" class="form-control receipt_store_id" data-val="" required>

                    <fieldset id ="store">

                        <select required  id="receipt_store_id" class="form-control product_color">

                        <option value="">'. _uc($_e['Select Store']) .'</option>';

                            echo $this->storeNamesOption();

                        echo '</select>

                    </fieldset>

                    </div>

                 </div>



            </div><!-- First col-md-6 end -->





            <div class="table-responsive bootTable col-md-12" style="padding-left: 0px; padding-right: 0px">

              <table id="selected" class="table sTable table-hover" style="min-width: 570px;" width="100%" border="0" cellpadding="0" cellspacing="0">

            	<thead>

                	<tr>

                        <th>'. _u($_e['PRODUCT']) .'</th>

                        <th class="allowProductScale">'. _u($_e['PRODUCT SCALE']) .'</th>

                        <th class="allowProductColor">'. _u($_e['PRODUCT COLOR']) .'</th>

                        <th>'. _u($_e['QTY']) .'</th>

                        <th>'. _u($_e['SINGLE PRICE']) .'</th>

                    </tr>

                </thead>

                <tbody>

                    <td>

                            <input type="text" class="form-control" id="receipt_product_id" placeholder="'. _uc($_e['Enter Product Name']) .'">

                            <input type="hidden" class="form-control receipt_product_id" data-val="">

                    </td>

                    <td class="allowProductScale">

                            <input type="text" class="form-control" id="receipt_product_scale" placeholder="'. _uc($_e['Enter Product Scale']) .'" readonly value="'. _uc($_e['No Scale Avaiable']) .'">

                            <input type="hidden" class="form-control receipt_product_scale" data-val="">

                    </td>

                    <td class="allowProductColor">

                            <input type="text" class="form-control" required id="receipt_product_color" placeholder="'. _uc($_e['Enter Product Color']) .'" readonly value="'. _uc($_e['No Color Avaiable']) .'">

                            <input type="hidden" class="form-control receipt_product_color" data-val="">

                    </td>

                    <td>

                           <input type="number" class="form-control" id="receipt_qty" placeholder="'. _uc($_e['Enter Product Quantity']) .'">

                    </td>

                    <td>

                           <input type="number" class="form-control" id="receipt_price" placeholder="'. _uc($_e['Enter Single Product Price']) .'">

                    </td>



                </tbody>



                </table>

            </div>



                <div class="form-group">

                    <div class="col-sm-10">

                        <button type="button" onclick="receiptFormValid();" id="AddProduct" class="btn btn-default">'. _uc($_e['Add Product']) .'</button>

                    </div>

                </div>





            </div> <!-- form-horizontal end -->





            <div style="margin:50px 0 0 0;">

                <input type="button" class="btn btn-danger" onclick="removechecked()" value="'. _uc($_e['Remove Checked Items']) .'" >

                <input type="button" class="btn btn-danger" onclick="uncheckall()" value="'. _uc($_e['Check/Uncheck All']) .'">

                <br><br>





             <div class="table-responsive" >

              <table id="selected" class="table sTable table-hover" width="100%" border="0" cellpadding="0" cellspacing="0">

            	<thead>

                	<tr>

                    	<th>'. _u($_e['SNO']) .'</th>

                        <th>'. _u($_e['PRODUCT']) .'</th>

                        <th>'. _u($_e['QTY']) .'</th>

                        <th>'. _u($_e['PRICE']) .'</th>

                    </tr>

                </thead>

                <tbody id="vendorProdcutList">



                </tbody>



                </table>

            </div>



            <br>

				    <button type="submit" onclick="return formSubmit();" name="submit" value="Generate Receipt" class="submit btn btn-primary btn-lg">'. _uc($_e['Generate Receipt']) .'</button>



         </div> <!-- add product script div end -->

       </form>';



    }





    public function receiptList(){

        global $_e;

        $this->functions->dataTableDateRange();

        echo '

            <div class="table-responsive">

                <table class="table table-hover dTable tableIBMS">

                    <thead>

                    <th>'. _u($_e['SNO']) .'</th>

                    <th>'. _u($_e['VENDOR']) .'</th>

                    <th>'. _u($_e['STORE NAME']) .'</th>

                    <th>'. _u($_e['RECEIPT DATE']) .'</th>

                    <th>'. _u($_e['REGISTER DATE']) .'</th>

                    <th>'. _u($_e['ACTION']) .'</th>

                    </thead>

                <tbody>



                ';

        $purchase_receipt=$this->receiptPurchaseSQL("*");

        $data=$this->receiptStoreSQL("*");

        $i=0;

        foreach($purchase_receipt as $val){

            $i++;

            $id=$val['receipt_pk'];

            $storeName =$this->StoreNameSQL($val['store']);

            $receiptDate = date('Y-m-d',strtotime($val['receipt_date']));

            echo "<tr class='tr_$id'>

                    <td>$i</td>

                    <td>$val[vendor]</td>

                    <td>$storeName</td>

                    <td>$receiptDate</td>

                    <td>$val[dateTime]</td>

                    <td> <div class='btn-group btn-group-sm'>

                        <a data-id='$id'  data-target='#ViewReceiptModal' class='btn receiptEdit'><i class='glyphicon glyphicon-list-alt receiptEdit'></i></a>



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

          </div><!-- .table-responsive End -->';

        $this->productF->AjaxDelScript('receiptAjax_del','receipt');



    }





    public function storeNamesOption(){

        $data = $this->receiptStoreSQL("`store_pk`,`store_name`,`store_location`");

        $op='';

        if($this->dbF->rowCount > 0){

            foreach($data as $val){

                $op .="<option value='$val[store_pk]'>$val[store_name] - $val[store_location]</option>";



            }

            return $op;

        }

        return "";

    }



    public function receiptPurchaseSQL($column){

        $sql="SELECT ".$column." FROM `purchase_receipt` ORDER BY `receipt_pk` ASC";

        return $this->dbF->getRows($sql);

    }



    public function receiptStoreSQL($column){

        $sql="SELECT ".$column." FROM `store_name` ORDER BY `store_pk` ASC";

        return $this->dbF->getRows($sql);

    }



    public function StoreNameSQL($id){

        $sql="SELECT `store_name`,`store_location` FROM `store_name` WHERE `store_pk` = '$id'";

        $data = $this->dbF->getRow($sql);



        return $data['store_name']." - ".$data['store_location'];

    }







    public function receiptAdd(){

        global $_e;

        if(!$this->functions->getFormToken('purchaseReceiptAdd')){

            return false;

        }



        if(!empty($_POST) && !empty($_POST['submit']) && !empty($_POST['cart_list'])){

         //$this->dbF->prnt($_POST);

            $sql="INSERT INTO `purchase_receipt`(`receipt_date`, `vendor`, `store`) VALUES (?,?,?)";

            $arry= array($_POST['receipt_date'],$_POST['receipt_vendor'],$_POST['receipt_store_id']);

            @$store=$_POST['receipt_store_id'];

            $this->dbF->setRow($sql,$arry);

            $lastId= $this->dbF->rowLastId;

            $i=0;

            foreach($_POST['cart_list'] as $itemId){

               $id=$itemId;

                $i++;



                $temp="pid_".$id;

                $pid=abs($_POST[$temp]);



                $temp="pscale_".$id;

                @$pscale=abs($_POST[$temp]);



                $temp="pcolor_".$id;

                @$pcolor=abs($_POST[$temp]);



                $temp="pqty_".$id;

                @$pqty=abs($_POST[$temp]);



                $temp="pprice_".$id;

                @$pprice=abs($_POST[$temp]);



                @$hashVal=$pid.":".$pscale.":".$pcolor.":".$store;

                $hash = md5($hashVal);



                $qry_order="INSERT INTO `purchase_receipt_pro`(

                            `receipt_id`,

                            `receipt_product_id`,

                            `receipt_product_scale`,

                            `receipt_product_color`,

                            `receipt_price`,

                            `receipt_qty`,

                            `receipt_hash`

                            ) VALUES (?,?,?,?,?,?,?)";

                $arry=array($lastId,$pid,$pscale,$pcolor,$pprice,$pqty,$hash);

                $this->dbF->setRow($qry_order,$arry);



                $sqlCheck="SELECT `product_store_hash` FROM `product_inventory` WHERE `product_store_hash` = '$hash'";

                $this->dbF->getRow($sqlCheck);

                if($this->dbF->rowCount>0){

                    $date =date('Y-m-d H:i:s'); //2014-09-24 13:46:10

                    $sql= "UPDATE `product_inventory` SET `qty_item` = qty_item+$pqty , `updateTime` = '$date' WHERE `product_store_hash` = '$hash'";

                    $this->dbF->setRow($sql);

                }else{

                    $sql = "INSERT INTO `product_inventory`(

                                            `qty_store_id`,

                                            `qty_product_id`,

                                            `qty_product_scale`,

                                            `qty_product_color`,

                                            `qty_item`,

                                            `product_store_hash`

                                        ) VALUES (?,?,?,?,?,?) ";

                    $arry=array($store,$pid,$pscale,$pcolor,$pqty,$hash);

                    $this->dbF->setRow($sql,$arry);

                }



            } // foreach



            $desc= _replace('{{n}}',$i,$_e["Prodcut Quantity Add in {{n}} different products"]);

            if($this->dbF->rowCount>0){

                $this->functions->setlog('New Receipt','Receipt',$lastId,$desc);

                $this->functions->notificationError(_js(_uc($_e["New Receipt"])),_js(_uc($_e["New Receipt Generate Successfully"])),'btn-success');

            }else{

                $this->functions->notificationError(_js(_uc($_e["New Receipt"])),_js(_uc($_e["New Receipt Generate Failed"])),'btn-danger');

            }



        } // if end

    }







}



?>