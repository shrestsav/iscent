<?php
//Only for Code Help in new Admin panel

//Product hash
$pArray     =   explode("_",$id); //p_491-246-435-5    => p_ pid - scaleId - colorId - storeId;
$pIds       =   $pArray[1];
$pArray     =   explode("-",$pIds); // 491-246-435-5 => p_ pid - scaleId - colorId - storeId;
$pId        =   $pArray[0]; // 491
$scaleId    =   $pArray[1]; // 426
$colorId    =   $pArray[2]; // 435
$storeId    =   $pArray[3]; // 5

@$hashVal   =   $pId.":".$scaleId.":".$colorId.":".$storeId;
$hash       =   md5($hashVal);

//////////////////////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////
//Insert Into Temp table
$tempTId        = $this->functions->setTempTableVal('Query',$sql);
$products       =    '<input type="hidden" style="display: none" id="q_tempTable" value="'.$tempTId.'"/>';

/////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

// submit refresh save msg
$msg    = $this->functions->notificationError('Product Successfully Submit','Thank you your product is successfully submit','btn-success');
$_SESSION['msg'] =base64_encode($msg);

//and use
if(isset($_SESSION['msg']) && $_SESSION['msg']!=''){
    echo base64_decode($_SESSION['msg']);
    $_SESSION['msg'] ='';
}
// OR
$functions->sessionMsg(); /* OR */  $functions->sessionMsg(false);
/////////////////////////////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////



///// Upload Single image
$returnImage =  $this->functions->uploadSingleImage($_FILES['image'],'FolderName');
if($returnImage==false){
    throw new Exception(($_e['Image File Error']));
}
//if image upload but error show on query remove last upload image in try catch catch
if($returnImage!==false){ // if image was upload successfully, $returnImage contain image name
    $this->functions->deleteOldSingleImage($returnImage);
}



///date + days
date('Y-m-d',strtotime("-40 days"));


/////  Kc finder Image In field
echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label"></label>
                    <div class="col-sm-10  col-md-9 ">
                        <img src="" class="menuIcon kcFinderImage"/>
                    </div>
                </div>';
echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">Label</label>
                    <div class="col-sm-10  col-md-9 ">

                        <div class="input-group">
                            <input type="url" name="icon" value=""  class="menuIcon form-control" placeholder="">
                            <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('menuIcon')".'"><i class="glyphicon glyphicon-picture"></i></div>
                        </div>
                    </div>
                </div>';



?>


    ///////

    Marked Check radio button
    <script>
        $(document).ready(function(){
            $('.sortProductImage[value="1"').attr('checked',true);
        });
    </script>



    ///////
    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////

    Edit Or Delete

    <div class='btn-group btn-group-sm'>
        <a data-id='$id' href='?$this->prefix_editPro=$id'  data-method='post' data-action='-$link?page=edit' class='btn'>
            <i class='glyphicon glyphicon-edit'></i>
        </a>
        <a data-id='$id'  class='btn'>
            <i class='glyphicon glyphicon-trash trash'></i>
            <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
        </a>
    </div>


    <script>
        function AjaxDelScript(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'product_management/product_ajax.php?page=$class&id='+id,
                    data: { itemId:id }
                }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('Delete Fail Please Try Again.');
                    }
                    else{
                        btn.append(data);
                    }
                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
            }
        };


        //for small delete in other project
        function secure_delete(text){
            // text = 'view on alert';
            text = typeof text !== 'undefined' ? text : 'Are you sure you want to Delete?';

            bool=confirm(text);
            if(bool==false){return false;}else{return true;}

        }

        function AjaxDelScript(ths,id){
            btn=$(ths);
            if(secure_delete()){
                $.ajax({
                    type: 'POST',
                    url: 'product_management/product_ajax.php?page=$class&id='+id,
                    data: { itemId:id }
                }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else(data=='0'){
                    alert('Delete Fail Please Try Again.');
                }


                });
            }
        };
    </script>


    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////


    <!-- Custome Dialog model view Default Display none, will open on your own js call -->

    <div id="submitButtons" class="topViewP">
        <div class="topViewInner">
            <div class="topViewTitle"> <div class="topViewCloseX btn-danger">X</div>
                Title
            </div>

            <div class="topViewBody">
                Body Text
            </div>

            <div class="topViewFooter">
                <div class="topViewClose btn btn-danger pull-right">Close</div>
            </div>
        </div>
    </div>
    <!-- Custome Dialog model view End -->

    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////


    <!-- Custome Dialog model view Default Display none, will open on your own js call -->
    <div id="submitButtons" class="topViewP">
        <div class="topViewInner">
            <div class="topViewTitle"><div class="topViewCloseX btn-danger">X</div>
                '.$title.'
            </div>

            <div class="topViewBody"><div class="FinalPriceReport">
                    <div class="h4"> Order  Price : <span class="totalPriceModel bold pull-right">1000RS</span></div>
                    <div class="h4"> Shipping Price : <span class="totalPriceShipping bold pull-right">550RS</span></div>
                    <div class="h4 displaynon btn-success"> Coupon : <span class="totalFinal bold pull-right">1500Rs</span></div>
                    <div class="h4"> Total : <span class="totalFinal bold pull-right">1500Rs</span></div>

                    <div class="btn-default margin-5" onclick="$('.couponApply').slideToggle(500);">I Have Coupon</div>

                    <div class="couponApply displaynone padding-10">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter Coupon">
                            <span class="input-group-addon"><input type="button" onclick="return checkCoupon();" class="btn btn-sm btn-primary" value="Apply"/> </span>
                        </div>
                        <div class="couponResponse col-sm-12 ">Error show Here</div>
                    </div>

                    <br>
                </div>
                <br>
                <div id="submitButtons">
                    <input type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER" class="submit btn btn-primary">
                    <input type="submit" onclick="return finalFormSubmit();" name="submit" value="ORDER AND PROCESS" class="submit btn btn-primary">
                </div>
            </div>

            <div class="topViewFooter">
                <div class="topViewClose btn btn-danger pull-right">'.$closeText.'</div>
            </div>
        </div>
    </div>
    <!-- Custome Dialog model view End -->



    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////





    /**
    * Now No need to redirect page
    * Stop Client to repeated form submit, by refresh,
    * just Use setFormToken('formName');
    * and IN form submit function, where form is submit, at top , just  use
    * if(!getFormToken('formName')){ return false;}
    */



    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////


<?php
/**
 *
 * MultiLanguage Code For Edit
 *
 *
 */

$lang = $this->functions->IbmsLanguages();
if($lang != false){
    $lang_nonArray = implode(',', $lang);
}
echo '<input type="hidden" name="lang" value="'.$lang_nonArray.'" />';
echo '<div class="panel-group" id="accordion">';

$langWord = unserialize($data['lang']);
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
                                    <label class="col-sm-2 col-md-3  control-label">Title</label>
                                    <div class="col-sm-10  col-md-9">
                                        <input type="text" value="'.$langWord[$lang[$i]].'" name="heading['.$lang[$i].']" class="form-control" placeholder="Box Title">
                                    </div>
                                </div>';

    echo '       </div> <!-- panel-body end -->
                          </div> <!-- collapse end-->
                     </div>';
}
echo '</div>';

/**
 *
 * MultiLanguage Code For Edit End
 *
 *
 */

?>


    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
<?php
//Special Words New Function, call on top and use array to print

$_e['oRDERING']="";
$_e["asad'raza"]   ="";
$_e    =   $dbF->hardWordsMulti($enArray,currentWebLanguage());
echo $_e["asad'raza"];
//if single Word use
$myordersT = $dbF->hardWords('MY ORDERS',false);
?>

    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////

    KCFinder insert image / file into input field// special use for when want a link of file.
    Functions
    openKCFinderImage($(target));
    openKCFinderFile($(target));
    <div class="form-group">
        <label class="col-sm-4 col-md-3 control-label" >FavIcon <small> Size: 16x16 OR 32 x 32 </small></label>
        <div class="col-sm-8 col-md-9">
            <div class="input-group">
                <input type="url" name="setting[favicon]" value="" id="favicon" class="favicon form-control" placeholder="favicon">
                <div class="input-group-addon linkList pointer"  onclick="openKCFinderImage($('#ID OR Class where you want to insert link on select'))"><i class="glyphicon glyphicon-search"></i></div>
                <!-- E.g
                Target field
                    <input type="url" name="setting[favicon]" value="" id="favicon" class="favicon form-control" placeholder="favicon">
                OPEN KC finder when you want on click
                    <div class="input-group-addon linkList pointer"  onclick="openKCFinderImage($('#favicon'))"><i class="glyphicon glyphicon-search"></i></div>
                -->
            </div>
        </div>
    </div>

    //////////////////////////////////////////////////////
    //////////////////////////////////////////////////////



    Add New Setting if table dont have setting table => setting_fields
<?php
// Weakness is  its not auto delete on data delete from main table.. you need to delete manually..

//get Submited values..
$settingInfo    =   $this->functions->setting_fieldsGet($id,'pages');
//Submit New / Edit Values... on edit old first delete..
$this->functions->setting_fieldsSet($lastId,'pages',false);

//For delete
$this->setting_fieldsDelete($id,$tableName,false);

echo '<div class="form-group">
    <label class="col-sm-2 col-md-3   control-label" >'.$_e['Login Required'].'</label>

    <div class="col-sm-10 col-md-9">
        <label class="radio-inline">
            <input type="radio" class="loginRequired" name="setting_f[loginRequired]" value="1">'.$_e['Yes'].'
        </label>
        <label class="radio-inline">
            <input type="radio" class="loginRequired" name="setting_f[loginRequired]" value="0">'.$_e['No'].'
        </label>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".loginRequired[value=\''.@strtolower($this->functions->setting_fieldsArray($settingInfo,'loginRequired')).'\']").attr("checked", true);
    });

</script>';

// OR
echo '<div class="form-group">
                    <label class="col-sm-2 control-label" >'.$_e['Date Of Birth'].'</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control date"  value="'.@strtolower($this->functions->setting_fieldsArray($settingInfo,'date_of_birth')).'" name="setting_f[date_of_birth]" placeholder="'.$_e['Date Of Birth'].'" >
                    </div>
                </div>';
?>




<?php

//Mysql Join query


/////Form Create With array
//Examples

$form_fields = array();
//Make <form, call first or any where then make array index key is 'form',
//now mange more clear, just make format here... no thisFormat work here.

$form_fields['form']  = array(
    'name'      => "form",
    'type'      => 'form',
    'class'     => "form-horizontal",
    'id'        => "formId",
    'data'     => "data-id='formdata'",
    'action'   => '',
    'method'   => 'post',
    'format'   => '<div class="form-horizontal">{{form}}</div>'
);

//if you dont want to use <form and want to put all fields inside other div,
//best and clean way is // only format tag use
$form_fields['main'] = array(
    'format' => "<div class='form-horizontal'>{{form}}</div>"
);

//All Array key are optionals
//Text Field,email,number,url,password,hidden

$form_fields[] = array(
    'label' => "Text",
    'name'  => 'Text1',
    'placeholder' => "",
    'value' => "Default",
    'type'  => 'text',
    'class' => 'form-control',
);

$form_fields[] = array(
    'label' => "Text field",
    'name'  => 'Text1',
    'placeholder' => _uc($_e['Measurement Title']),
    'value' => "Default",
    'type'  => 'text',
    'class' => 'form-control',
    'id'  => 'textId',
    'required'  => 'true',
    'min-length'  => '30',
    'max-length'  => '50',

    'pattern'     => '[a-z]',
    'data' => 'data-id="10" data-name="formAuto"',
    'format' => '<div class="input-group">
                  <div class="input-group-addon">$</div>
                      {{form}}
                  <div class="input-group-addon">.00</div>
                </div>',
    'formatHead' => '<div>{{form}}</div>', //its need in radio to print format inside format its not effet main format,radio print multiple, so all parent format is set here..
    'thisFormat' => '<div></div>', // $format will not work with this key
    'group'  => 'groupName', // want to print some group array in one div? easy to manage and view,,, use group key with same group name...
    // then where you want to print use type='group', and name = 'groupName'
    //if group key exists, it will not print until call from type = group..
);

//Group Name || Group Array || this array For Print groups
//this array must be call after all same group array execute/declare
$form_fields[] = array(
    'type' => 'group',
    'name' => 'groupName',
    'label' => 'optional',
    'group' => 'groupInsideGroup', //you can also use group inside other group
    'thisFormat' => '<div class="panel-group" id="accordion">{{form}}</div><!--#accordion-->',
    'format' => 'it will use default format {{form}} same as other'
    //if no format, it will consider default all format
);



//inFormat , When 2 or more field want to print in other div, inFormat is unique name. you can alos use group with inFormat.
$form_fields[] = array(
    'name'      => "submit",
    'type'      => 'submit',
    'class'     => 'btn themeButton',
    'value'     => $_e['Submit'],
    'thisFormat'=> "",
    'inFormat'  => 'save'
);

//inFromat Submit Button
$form_fields[] = array(
    'name'      => "submit",
    'type'      => 'submit',
    'class'     => 'btn themeButton',
    'value'     => $_e['Save'],
    'thisFormat'=> "",
    'inFormat'  => 'submit'
);

$form_fields[] = array(
    'type'      => "none",
    'format'    => '{{save}} {{submit}}',
);
//In end all match inFormat name will be replace.
//inFormat End


//Text Field,email,number,url,password,hidden
$form_fields[] = array(
    'label' => "Text field2",
    'name'  => 'Text2',
    'value' => "Example 2 no css",
    'type'  => 'search',
);

//Number Field
$form_fields[] = array(
    'label' => "number",
    'name'  => 'num',
    'value' => "10",
    'type'  => 'tel',
    'class' => 'form-control',
    'required' => 'true',
    'min'   => '10',
    'max'   => '50',
    'format' => '<div class="col-sm-3">{{form}}</div>'
);

//date Field
$form_fields[] = array(
    'label' => "Date",
    'name'  => 'date',
    'type'  => 'date',
    'class' => 'form-control',
    'required' => 'true',
    'format' => '<div class="col-sm-3">{{form}}</div>'
);

//TextArea
$form_fields[] = array(
    'label' => "test",
    'name'  => 'measurement_headinga',
    'value' => "val <textarea> ''".'" asad" ',
    'type'  => 'textarea',
    'class' => 'form-control',
    'max-length' => '500',
    'min-length' => '100',
);

//Radio Buttons with array control
$form_fields[] = array(
    'label' => "radio",
    'option' => array("male","female","ali","hassan"),
    'name'  => 'radio',
    'value' => array("male","female","ali","hassan"),
    'type'  => 'radio',
    'class' => '',
    'data' => 'data-if="as"',
    'selected' => array('female'),
    'required'  => 'true',
    'format' => '<label class="radio-inline">{{form}} {{option}}</label>'
);

//Radio Buttons with comma seprate values
$form_fields[] = array(
    'label' => "radio Comma seprate",
    'option' => "male1,female1,ali1,hassan1",
    'name'  => 'radio2',
    'value' => "male,female,ali,hassan",
    'type'  => 'radio',
    'class' => '',
    'data' => 'data-if="as"',
    'selected' => 'female',
    'format' => '<label class="radio-inline">{{form}} {{option}}</label>'
    //'formatHead' => '<div>{{form}}</div>' its need in radio to print format inside format its not effet main format
);

//CheckBox with array seprate values
$form_fields[] = array(
    'label' => "chekbox inner Array",
    'option' => array("male1","female1","ali1","hassan1"),
    'name'  => 'check',
    'value' => array("male","female","ali","hassan"),
    'type'  => 'check',
    'class' => '',
    'selected' => array('female','male'),
    'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>'
);

//CheckBox with comma seprate values
$form_fields[] = array(
    'label' => "chekbox with comma",
    'option' => "male1,female1,ali1,hassan1",
    'name'  => 'check',
    'value' => "male,female,ali,hassan",
    'type'  => 'check',
    'class' => '',
    'selected' => 'female,male',
    'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>'
);

//Select Box
$form_fields[] = array(
    'label' => "select",

    'option' => array("male1","female1","ali1","hassan1"),
    'optionClass' => "optClass",
    'optionData' => "data-field='1' data-f2='2'",
    'value' => array("male","female","ali","hassan"),

    'name'  => 'select',
    'type'  => 'select',
    'data'  => 'data-select="selectdata"',
    'class' => 'form-control',
    'id'    => 'test',
    'selected' => 'female,male',
    'multi' => 'true',
    'format' => '<div class="col-sm-8">{{form}}</div>'
);

//Select example 2
$form_fields[] = array(
    'label' => "Select example 2",

    'option' => array("---","male1","female1","ali1","hassan1"),
    'value' => array("","male","female","ali","hassan"),

    'name'  => 'select',
    'type'  => 'select',
    'class' => 'form-control',
);

//Select example 3
$form_fields[] = array(
    'label' => "Select example 3",

    'option' => array("---","male1","female1","ali1","hassan1"),
    'value' => array("","male","female","ali","hassan"),

    'name'  => 'select',
    'type'  => 'select',
    "check" => 'female'
);

//select Example 4 , single Array with KEY, Key is value and value is options
$form_fields[] = array(
    'label' => 'Job Status',
    'name'  => 'rank',
    'array' => array("PK"=>"Pakistan","IN"=>"India","USA"=>"America"),
    'type'  => 'select',
    'class' => 'form-control',
);

//real Example
$sql    =   "SELECT * FROM `p_custom` WHERE publish = '1'";
$data = $dbF->getRows($sql);

$customTypeArray = array(); // for initial value
$customTypeArray['value']   = "0";
$customTypeArray['option']  = "----------";
$customTypeArray = $functions->getSelectValueAndOptions($data,'id','custom_type',false,$customTypeArray);

//Custom Size type select
$label = "select value";
$form_fields[] = array(
    'label' => _uc($_e['Custom Size Type']),
    'name'  => $product->prefix_setting.'[customSize]',
    'select' => "$label",
    'value'  => $customTypeArray['value'],
    'option' => $customTypeArray['option'],
    'type'  => 'select',
    'class' => 'form-control',
);

//real select example end

$form_fields[] = array(
    'name'  => 'test',
    'value' => "asad",
    'type'  => 'hidden',
);

//new Admin File Select field
$form_fields[] = array(
    'label'  => 'New Admin file',
    'name' => "new",
    'type'  => 'url',
    'id'    => 'favicon',
    'class' => 'form-control',
    'format' => '<div class="input-group">
                    {{form}}
                    <div class="input-group-addon linkList pointer"
                        onclick="openKCFinderImage($(\'#favicon\'))"><i class="glyphicon glyphicon-picture"></i></div>
                </div>'
);

//File Image Upload Field
$form_fields[] = array(
    'label' => 'image',
    'name'  => 'image',
    'type'  => 'image',
    'required'  => 'true',
    'filter'  => 'image',
    'format'  => '<div class="col-sm-10 col-md-9">{{form}} {{image}}</div>',
    'multi' => 'true',
    'class' => 'img',
    'image'  => 'image/image.png',
    'imageClass'  => 'abClass',
    'imageData'  => 'data-id="data"',
);

$form_fields[] = array(
    'label' => 'Upload PDF',
    'name'  => 'pdf',
    'type'  => 'file',
    'required'  => 'true',
    'filter'  => 'pdf',
    'format'  => '<div class="col-sm-10 col-md-9">{{form}} {{file}}</div>',
    'multi' => 'true',
    'class' => 'fil',
    'file'  => 'images/file/file.pdf',
    'fileClass'  => 'abClass',
    'fileData'  => 'data-id="data"',
);

//File Upload Field
$form_fields[] = array(
    'label' => 'file',
    'name'  => 'file',
    'type'  => 'file',
    'format'  => '<div class="col-sm-10 col-md-9">{{form}}</div>',
);

//Submit Button
$form_fields[]  = array(
    "label" => "submit",
    "name"  => 'btn',
    'class' => 'btn btn-default',
    'type'  => 'submit'
);

//Submit <button
$form_fields[] = array(
    'name'      => "submit",
    'type'      => 'button',
    'class'     => 'btn themeButton',
    'value'     => 'Save',
    'option'     => $_e['Save'], // if option not set, value show.
    'thisFormat'=> "", // required to set this, for stop format on this.... if want only field result.
    'inFormat'  => 'submit',
    'submit'    => 'true' // use this for change type button to submit.
);

//Button, with own format , $format will not works
$form_fields[]  = array(
    "name"  => 'btn',
    'class' => 'btn btn-default',
    'type'  => 'button',
    'value' => 'btn',
    'thisFormat' => '<div class="form-group">
                       {{form}}
                     </div>'
);

// Bootstrap Switch Button Publish Or draft
$valFormTemp   =   '1';
$form_fields[]  = array(
    "label" => $_e['Publish'],
    'type'  => 'checkbox',
    'value' => "$valFormTemp",
    'select' => "$valFormTemp",
    'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">
                    {{form}}
                  <input type="hidden" name="insert[publish]" class="checkboxHidden" value="'.$valFormTemp.'" />
                 </div>'
);

//want to send some thing between form
$form_fields[] = array(
    'format' => "<hr><hr><hr>",
    //OR 'thisFormat' => "<hr><hr><hr>"
);

$format = '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>
                        <div class="col-sm-10  col-md-9">
                            {{form}}
                        </div>
                    </div>';
//$format = false;
$this->functions->print_form($form_fields,$format);