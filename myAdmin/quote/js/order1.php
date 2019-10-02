<?php header('Content-type: application/x-javascript');



    require_once(__DIR__.'/../../global.php');



    /**

     * MultiLanguage keys Use where echo;

     * define this class words and where this class will call

     * and define words of file where this class will called

     **/

    global $_e;

    global $adminPanelLanguage;

    $_w=array();

    $_w['Its Look Like Shipping Is Not Available In Receiver Country.'] = '' ;

    $_w['Its Look Like Shipping Stop In Receiver Country {{country}}'] = '' ;

    $_w['Delete Fail Please Try Again.'] = '' ;

    $_w['Payment Info'] = '' ;

    $_w['Payment Type not select Befor continue please select payment type.'] = '' ;

    $_w['Select product Are not ship in receiver country.'] = '' ;

    $_w['Shipping Error'] = '' ;

    $_w['Please Enter Sender / Required Fields.'] = '' ;

    $_w['Required Fields'] = '' ;

    $_w['Duplicate Entry : Product Item already exist in list!'] = '' ;

    $_w['Duplicate Entry'] = '' ;

    $_w['Required Fields Are Empty'] = '' ;

    $_w['Please enter proper data in all fields.'] = '' ;

    $_w['Before Continue Please Select Product Or Store.'] = '' ;

    $_w['Product Error'] = '' ;

    $_w['Price Error'] = '' ;

    $_w['Your Total Price Of Product Is Not OK, Please Check.'] = '' ;

    $_w['Your Selected Quantity is Greater then or less then real quantity Please Check. Use Mouse Scroll or UP/DOWN arrow to select quantity'] = '' ;

    $_w['Quantity Error'] = '' ;

    $_w['Product Inventory Error'] = '' ;

    $_w['Store not found please try other product. OR Product has no stock avaiable.'] = '' ;

    $_w['Product Color Not Available'] = '' ;

    $_w['Product Scale Not Available'] = '' ;

    $_w['NO Product Found In {{option}}'] = '' ;

    $_w['Please Select Story Country Before Select Product'] = '' ;

    $_w['You not select any product for order.'] = '' ;

    $_w['Update Fail Please Try Again.'] = '' ;



    $_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin OrderScript');



if(1===2){

//Just for suggestion help <script> if not then page behave like txt page

?>

<script>

<?php } ?>



    <?php

       $temp = 'false';

        if($functions->developer_setting('product_Scale')=='1'){

           $temp = 'true';

        }

       echo "var hasScale = '$temp';";

       $temp = 'false';

        if($functions->developer_setting('product_color')=='1'){

           $temp = 'true';

        }

       echo "var hasColor = '$temp';";

    ?>



$(document).ready(function(){

    tableHoverClasses();

    $('.senderOrReceiver').change(function(){

        info = $(this).attr('data-id');

        senderOrReceiver(info);

    });



    $('#sender_name,#sender_phone,' +

        '#sender_email,#sender_city,' +

        '#sender_country,#sender_post,#sender_address').bind('keyup change',function(){

        if($('.iAmReceiver').is(':checked')){

            setTimeout(senderOrReceiver('iAmReceiver'),500);

        }

    });



});









var dateCodeTo   = '';

var dateCodeFrom = '';



minMaxDateFilter = function() {

    $( ".min,#min" ).datepicker({

        defaultDate: "+1w",

        changeMonth: true,

        numberOfMonths: 1,

        changeYear: true,

        yearRange: "1935:2050",

        dateFormat: "yy-mm-dd",

        onClose: function( selectedDate,i ) {

            $( ".max,#max" ).datepicker( "option", "minDate", selectedDate);

        

            try {



            // if(selectedDate !== i.lastVal){

            //     console.log('This changed');

            //     // $(this).change();

            // }

                var date = $(this).datepicker('getDate'),

                    day   = date.getDate(),  

                    month = date.getMonth() + 1,              

                    year  =  date.getFullYear();

                    dateCodeFrom = year + '-' + month + '-' + day;

                    console.log(dateCodeFrom);



                    console.log(selectedDate);



                var max_datepicker_val = $( "#max" ).data('datepicker').lastVal;

                if ( max_datepicker_val != "" ) {

                    fetch_ajax_result_again(dateCodeFrom, max_datepicker_val);

                    console.log("Inside Min");

                };



                console.log("Outside");

            } catch (e) {

                console.log(e);

            }



        }

    });





    $( ".max,#max" ).datepicker({

        defaultDate: "+1w",

        changeMonth: true,

        numberOfMonths: 1,

        changeYear: true,

        yearRange: "1935:2050",

        dateFormat: "yy-mm-dd",

        onClose: function( selectedDate ) {

        

            $( ".min,#min" ).datepicker( "option", "maxDate", selectedDate );



            try {





                var date = $(this).datepicker('getDate'),

                    day  = date.getDate(),  

                    month = date.getMonth() + 1,              

                    year =  date.getFullYear();

                    dateCodeTo = year + '-' + month + '-' + day;

                    console.log(dateCodeTo);



                    console.log(selectedDate);





                        var min_datepicker_val = $( "#min" ).data('datepicker').lastVal;

                        if ( min_datepicker_val != "" ) {

                            fetch_ajax_result_again();

                            console.log("Inside Max");

                        };





            } catch (e) {

                console.log(e);

            }





        }



    });

};







function fetch_ajax_result_again (dateCodeFrom, dateCodeTo) {



    my_dtable = $.fn.dataTable.tables( { visible: true, api: true } );

    $(my_dtable).DataTable().ajax.reload();





}





function countOrderPrice(order){



    $(".currIds").each(function(){

        total = 0;

        id = $(this).val();

        // console.log(id);

        $(".printMe_"+order+"_"+id).html(total);

        $('.countMe_'+order+"_"+id).each(function(k,val){

            co= parseFloat($(this).text());

            total = eval(total+co);

            $(".printMe_"+order+"_"+id).html(total);

        });



    });

}

   orderProductJson = function(ths) {



        productId="#invoice_product_id";

        productHiddenClass = ".invoice_product_id";



        var country = $(ths).val();

        $('.storeCountry').val(country);

        var json;

        if(country==''){

                $('#loadingProgress').html('<?php echo _js($_e["Please Select Story Country Before Select Product"]); ?>');

                $('#productNotFoundInCountry').addClass('orderAddProductNotAllow');

                return false;

        }else{

            getOrderProductJson(country,ths);

            console.log('orderProductJson reached');

        }

    }



//$(function() {

   getOrderProductJson = function(country,ths){



        $('#loadingProgress').html(loading_progress());

        $.ajax({

            type: 'POST',

            url: 'order/order_ajax.php?page=getOrderProductJson',

            data: { country:country }

        }).done(function(data)

            {

                console.log('getOrderProductJson : '+ data);

                $('#loadingProgress').html("");

                if(data=='[]'){

                    $('#loadingProgress').html('<?php echo _js(_replace('{{option}}','\'+$(ths).find("option:selected").text()+\'',$_e["NO Product Found In {{option}}"])); ?>');

                    $('#productNotFoundInCountry').addClass('orderAddProductNotAllow');

                    jsonData(data);

                }else{

                    $('#productNotFoundInCountry').removeClass('orderAddProductNotAllow');

                    jsonData(data);

                }



            });

    }



    jsonData = function(data){

        //products load after country select

        var availableTags = eval(data);



        $(productId).autocomplete({

            source: availableTags,

            minLength: 0,

            select: function( event, ui ) {

                $(productHiddenClass).val(ui.item.id);

                $(productHiddenClass).attr("data-val",ui.item.label);



                $('#priceCode').val(ui.item.priceCode);

                $('#invoice_product_weight').val(ui.item.weight);

                $('.invoice_product_shipping').val(ui.item.interShipping);

                // if(hasScale == 'true'){

                //     scale(ui.item.scale);

                // }

                // if(hasColor == 'true') {

                //     color(ui.item.color);

                // }

                productSelect();

            }

        }).on('focus : click', function(event) {

                $(this).autocomplete("search", "");

        });

    }





    // scale =function(data){



    //     scaleId = "#invoice_product_scale";

    //     scaleHiddenClass= ".invoice_product_scale";

    //     $('.invoice_product_scale , .invoice_product_color').val("");



    //     if(data==null){

    //         $(scaleId).val('<?php //echo _js($_e["Product Scale Not Available"]); ?>').attr("readonly","readonly");

    //         $(scaleHiddenClass).removeClass("has").attr('data-val','').val('');

    //         data = [];

    //     }else{

    //         $(scaleId).val('').removeAttr("readonly");

    //         $(scaleHiddenClass).addClass("has");

    //     }



    //     $(scaleId).autocomplete({

    //         source: data,

    //         minLength: 0,

    //         select: function( event, ui ) {

    //             $(scaleHiddenClass).val(ui.item.id).attr("data-val",ui.item.label);

    //             $('#invoice_product_weight').val(ui.item.sWeight);

    //             productSelect();

    //         }

    //     }).on('focus : click', function(event) {

    //          $(this).autocomplete("search", "");

    //     });

    // };







    // color =function(data){

    //     colorId = "#invoice_product_color";

    //     colorHiddenClass= ".invoice_product_color";

    //     $(colorHiddenClass).val("");

    //     $(colorId).css('border','1px solid #ccc');

    //     if(data==null){

    //         $(colorId).val('<?php //echo _js($_e["Product Color Not Available"]); ?>').attr("readonly","readonly");

    //         $(colorHiddenClass).removeClass("has").attr('data-val','').val('');

    //         data = [];

    //     }else{

    //         $(colorId).val('').removeAttr("readonly");

    //         $(colorHiddenClass).addClass("has");

    //     }

    //     $(colorId).autocomplete({

    //         source: data,

    //         minLength: 0,

    //         select: function( event, ui ) {

    //             $(colorHiddenClass).val(ui.item.id).attr("data-val",ui.item.label);

    //             $(colorId).css('border','3px solid #'+ui.item.label);

    //             productSelect();

    //         }

    //     }).on('focus : click', function(event) {

    //             $(this).autocomplete("search", "");

    //         }).data("ui-autocomplete")._renderItem = function (ul, item) {

    //         return $("<li></li>")

    //             .data("item.autocomplete", item)

    //             .css({"margin":"1px 0",

    //                 "height": "23px",

    //                 "padding":"0"})

    //             .append("<div style='background:#"+item.label+";color:#FFF;height:100%;'>"+item.label+"</div>")

    //             .appendTo(ul);

    //     };

    // };







    productSelect = function(){

        //check product select has complete product select mean scale or color select or not then product store json load

        setTimeout(function(){

            pros = $(".invoice_product_id");

            if($(".invoice_product_scale").hasClass("has")){

                scles = $(".invoice_product_scale.has").val();

            }else{

                scles = '0';

            }



            if($(".invoice_product_color").hasClass('has')){

                clrs = $(".invoice_product_color.has").val();

            }else{

                clrs = '0';

            }



            console.log(clrs);

            if( pros.val() != "" && scles != ""  && clrs != "" ){

                pid=pros.val();

                country= $('#storeCountry').val();

                productStoreJson(pid,scles,clrs,country);

            }else if(scles == ""  || clrs == ""){

                productStoreJsonDefault();

            }

        },250);



    };



    productStoreJson=function(pid,scaleid,colorid,country){

        // after product select ajax load to get stores and quantity price

       $('#invoice_product_store').val("Wait");

       $('#invoice_product_store').attr("readonly","readonly");

        $.ajax({

            type: 'POST',

            url: 'order/order_ajax.php?page=getOrderProductStoreJson',

            data: { country:country,pId:pid,scaleId:scaleid,colorId:colorid }

        }).done(function(data)

            {

                if(data=='[]'){

                    $('#invoice_product_store').val("Not Found");

/*                    jAlert('Store not found please try other product. <br> OR Product has no stock avaiable.','Product Inventory Error');*/



                    notification('<?php echo _js($_e['Product Inventory Error']); ?>','<?php echo _js($_e['Store not found please try other product. OR Product has no stock avaiable.']); ?>',"btn-danger");

                    jsonDataStore(data);

                }else{

                    $('#invoice_product_store').removeAttr("readonly").val("");

                    jsonDataStore(data);

                }

            });

    };



    productStoreJsonDefault = function(){

        $('#invoice_product_store').attr("readonly","readonly").val("Select Product");

        $('.invoice_product_store').val("");



        jsonDataStore('[]');

    };





    jsonDataStore = function(data){

        // after product select ajax load to get stores and quantity price

        var availableTags = eval(data);

        $('#invoice_qty,#invoice_price,#invoice_discount,#invoice_total_price').val('0');



        $("#invoice_product_store").autocomplete({

            source: availableTags,

            minLength: 0,

            select: function( event, ui ) {

                $('.invoice_product_store').val(ui.item.storeId);

                $('.invoice_product_store').attr("data-val",ui.item.label);



                qty= ui.item.qty;

                $("#invoice_qty").attr("data-val",qty).attr("max",qty).val(qty);

                price =ui.item.price;

                $("#invoice_price").attr("data-val",price).val(price);



                discount =ui.item.discount;

                $("#invoice_discount").attr("data-val",discount).val(discount);

                tempTotalPrice();

            }

        }).on('focus : click', function(event) {

                $(this).autocomplete("search", "");

        });

    }



    $("#invoice_qty,#invoice_price,#invoice_discount").change(function(){

        qtyCheck();

        tempTotalPrice();



    });



    qtyCheck = function(){

        val = $("#invoice_qty").val();

        if(val==''){

            return false;

        }

        val = parseInt(val);

        valReal = parseInt($("#invoice_qty").attr("data-val"));

        if(val<=valReal && val>0){

            return true;

        }else{

            /*jAlert('Your Selected Quantity is Greater then or less then real quantity Please Check.<br><br>Use Mouse Scroll or UP/DOWN arrow to select quantity','Quantity Error');*/

            notification('<?php echo _js($_e['Quantity Error']); ?>','<?php echo _js($_e['Your Selected Quantity is Greater then or less then real quantity Please Check. Use Mouse Scroll or UP/DOWN arrow to select quantity']); ?>','btn-warning');

            return false;

        }

    };





    tempTotalPrice = function(){

        qty = parseInt($('#invoice_qty').val());

        updateQty = parseInt($('#invoice_qty').attr('data-update'));

        if(isNaN(updateQty)){

            updateQty = 1;

        }

        price = parseFloat($('#invoice_price').val());

        discount = parseFloat($('#invoice_discount').val());

        if(price=='' || isNaN(price)){ price = 0;}

        if(discount=='' || isNaN(discount)){ discount = 0;}



        $('#invoice_qty').attr('data-update',qty);

         discount = eval(discount/updateQty);

         discount = eval(discount*qty);

        discount = Math.round(discount*100)/100;

        $('#invoice_discount').val(discount);



        total = eval((qty*price)-discount);

        total = Math.round(total*100)/100;

        $('#invoice_total_price').val(total);

        if(total<0){

            /*jAlert('Your Total Price Of Product Is Not OK, Please Check.','Price Error');*/

            notification('<?php echo _js($_e["Price Error"]); ?>','<?php echo _js($_e["Your Total Price Of Product Is Not OK, Please Check."]); ?>',"btn-warning");

            return false;

        }

        return total;

    };



    tempAddProductValid = function(){

        store = $(".invoice_product_store").val();

        if(store==""){

//            jAlert("Before Continue Please Select Product Or Store.","Product Error");

            notification('<?php echo _js($_e["Product Error"]); ?>','<?php echo _js($_e["Before Continue Please Select Product Or Store."]); ?>',"btn-danger");

            return false;

        }



        if(!qtyCheck()){

            return false;

        }

        if(!qtyCheck()){

            return false;

        }



        return true;

    };



//});



function invoiceFormValid(){

    // tempadd product validation

    if(!tempAddProductValid()){

        return false;

    }

        if( $(".invoice_product_id").val() == "" || $("#invoice_product_store").val() == ""

            ){

            /*alert("Required Fields Are Empty.");*/

            notification('<?php echo _js($_e["Required Fields Are Empty"]); ?>','<?php echo _js($_e["Please enter proper data in all fields."]); ?>',"btn-warning");

            return false;

        }

    addListItem();



}

var sr=0;

function addListItem() {

    //add product script, add for submit

    //disable one time required fields

    $("#storeCountry").attr("disabled","disabled");

    //disable end





    var pid = parseInt($(".invoice_product_id").val());

    //var pScaleId = parseInt($(".invoice_product_scale.has").val());

    //var pColorId = parseInt($(".invoice_product_color.has").val());

    var storeId = parseInt($(".invoice_product_store").val());

    //var interShipping = $('.invoice_product_shipping').val();



    //if(isNaN(pScaleId)){pScaleId = 0;}

    //if(isNaN(pColorId)){pColorId = 0;}



    var pName = $(".invoice_product_id").attr('data-val');

    var price = parseFloat($("#invoice_price").val());

    //var discount = parseFloat($("#invoice_discount").val());

    var qty =   parseInt($("#invoice_qty").val());

    var totalPrice = tempTotalPrice();

        if(price=='' || isNaN(price)){ price = 0;}

        //if(discount=='' || isNaN(discount)){ discount = 0;}

        if(totalPrice=='' || isNaN(totalPrice)){ totalPrice = 0;}

    var priceCode =$('#priceCode').val();

    //var pWeight   = $('#invoice_product_weight').val();

    //pWeight     =   eval(pWeight*qty);

    //pWeight     =   Math.round(pWeight*100)/100;

    //var trpid = "p_"+pid+"-"+pScaleId+"-"+pColorId+"-"+storeId;

    var trpid = "p_"+pid+"-"+storeId;

    if (document.getElementById("tr_"+trpid)) {

        notification('<?php echo _js($_e["Duplicate Entry"]); ?>','<?php echo _js($_e["Duplicate Entry : Product Item already exist in list!"]); ?>','btn-danger');

        document.getElementById(trpid).checked = true;

        checkchange(trpid);

    }

    else if (qty > 0 && pid > 0 ) {

        sr++;



        var item = "<tr id='tr_"+trpid+ "' data-id='"+trpid+"'>"+

            "<td><input type='checkbox' id='"+trpid+ "' onchange='checkchange(this.id)' value='" + trpid + "' class='checkboxclass' />" +

            "<span>" + sr + "</span></td>"+

            "<td>"+pName+

                "<input type='hidden' name='cart_list[]' value='"+trpid+"' />"+

                "<input type='hidden' name='pid_"+trpid+"' value='"+pid+"' />" +

                

                "<input type='hidden' name='pQty_"+trpid+"' value='"+qty+"' class='addedQty'/>" +

                "<input type='hidden' name='pPrice_"+trpid+"' value='"+price+"' />" +

                

                "<input type='hidden' name='pTotalprice_"+trpid+"' value='"+totalPrice+"' class='addedTotalPrice'/></td>"+

            "<td>"+qty +"</td>"+

            "<td> ("+qty+"*"+price+")="+totalPrice+" "+priceCode +"</td>"+

            "</tr>";





        $("#vendorProdcutList").append(item);

        blankField();

        addedProductTotalPrice();

    }



}



function blankField(){

    //when product add, inserted fields back to blank

    $("#invoice_qty,#invoice_price," +

        "#invoice_product_id, .invoice_product_id," +

        ".invoice_product_scale, .invoice_product_color, " +

        "#invoice_product_store, .invoice_product_store, " +

        "#invoice_discount,#invoice_total_price").val("");



    //color(null);

    //scale(null);

}



function addedProductTotalPrice(){

    console.log("Qty added");

    priceCode= " "+$('#priceCode').val();



    //Total Qty

    qty = 0;

    $('.addedQty').each(function(i,data){

        temp= parseInt($(this).val());

        qty = eval(temp+qty);

        console.log("Qty added");

    });

    $('.totalQuantity').text(qty);



    //Total Discount

    dis = 0;

    $('.addedDiscount').each(function(i,data){

        temp= parseFloat($(this).val());

        dis = eval(temp+dis);

    });

    $('.totalDiscount').text(dis+priceCode);



    //Total Price

    price = 0;

        $('.addedTotalPrice').each(function(i,data){

            temp= parseFloat($(this).val());

            price = eval(temp+price);

        });

    $('.totalPrice').text(price+priceCode);

    $('.totalPriceInput').val(price);



    //Weight

    weight = 0;

    $('.product_weight').each(function(i,data){

        temp= parseFloat($(this).val());

        weight = eval(temp+weight);

    });

    $('.totalWeight').text(weight + " KG");

    $('.totalWeightInput').val(weight);



}







function isInternationalShipping(){

    chk=true;

    receiverCountry = $("#receiver_country").val();

    storeCounntry = $("#storeCountry").val();

    if(receiverCountry==""){

        notification('<?php echo _js($_e["Required Fields"]); ?>','<?php echo _js($_e["Please Enter Sender / Required Fields."]); ?>',"btn-warning");

        return false;

    }



    $('.interShipping').each(function(i,data){

        if(receiverCountry==storeCounntry){

            return true;

        }else if($(this).val() == '0'){

            tr=$(this).closest('tr').attr('data-id');

            $('#'+tr).prop( "checked",true );

            chk=false;

        }

    });

    if(!chk){

        notification('<?php echo _js($_e["Shipping Error"]); ?>','<?php echo _js($_e["Select product Are not ship in receiver country."]); ?>',"btn-danger");

        return false;

    }

    return chk;

}



function checkchange(pid) {

    var tr = "tr_" + pid;

    if ($('#' + pid).is(":checked")) {

        $("#"+tr).addClass("btn-warning trChecked highlitedtd");

    }

    else {

        $("#"+tr).removeClass("btn-warning trChecked highlitedtd");

    }

}



function removechecked() {

    $('.highlitedtd').remove();

    addedProductTotalPrice();

}





function uncheckall() {

    if($("#vendorProdcutList tr").hasClass('highlitedtd')){

        $( ".checkboxclass" ).prop( "checked",false );

        $("#vendorProdcutList tr").removeClass("btn-warning trChecked highlitedtd");

    }else{

        $( ".checkboxclass" ).prop( "checked",true );

        $("#vendorProdcutList tr").addClass("btn-warning trChecked highlitedtd");

    }

}







/**

 *

 * @returns {boolean}

 */

function formSubmit(){

    //check on formsubmit is product selected

    if ( $('#vendorProdcutList tr').length <= 0 ) {

        notification('<?php echo _js($_e["Product Error"]); ?>','<?php echo _js($_e["You not select any product for Quote."]); ?>',"btn-warning");

        return false;

    }



    if(!isInternationalShipping()){

        return false;

    }



    payment = $("#paymentTypeSelect").val();

    if(payment==""){

        notification('<?php echo _js(_uc($_e["Payment Info"])); ?>','<?php echo _js($_e["Payment Type not select Befor continue please select payment type."]); ?>',"btn-warning");

        return false;

    }



    return true;

}

function finalFormSubmit(){

    //check on formsubmit is product selected

    if ( $('#vendorProdcutList tr').length <= 0 ) {

        notification('<?php echo _js(_uc($_e["Product Error"])); ?>','<?php echo _js($_e["You not select any product for Quote."]); ?>',"btn-warning");

        return false;

    }



    if(!isInternationalShipping()){

        return false;

    }



    payment = $("#paymentTypeSelect").val();

    if(payment==""){

        notification('<?php echo _js(_uc($_e["Payment Info"])); ?>','<?php echo _js(_fc($_e["Payment Type not select Befor continue please select payment type."])); ?>',"btn-warning");

        return false;

    }

    $('.lastReview .reportReview').html('');

    return true;

}



delOrderInvoice = function(ths){

    btn=$(ths);

    if(secure_delete()){

        btn.addClass('disabled');

        btn.children('.trash').hide();

        btn.children('.waiting').show();



        id=btn.attr('data-id');

        $.ajax({

            type: 'POST',

            url: 'quote/order_ajax.php?page=delOrder&id='+id,

            data: { itemId:id }

        }).done(function(data)

        {

            ift =true;

            if(data=='1'){

                ift = false;

                btn.closest('tr').hide(1000,function(){$(this).remove()});

            }

            else if(data=='0'){

                alert('<?php echo _js($_e['Delete Fail Please Try Again.']); ?>');

            }

            else{

                jAlert(data,'Error');

            }

            if(ift){

                btn.removeClass('disabled');

                btn.children('.trash').show();

                btn.children('.waiting').hide();

            }

        });

    }

};



senderOrReceiver = function(order){

    if(order=='iAmReceiver'){

        $('#receiver_name').val($('#sender_name').val());

        $('#receiver_phone').val($('#sender_phone').val());

        $('#receiver_email').val($('#sender_email').val());

        $('#receiver_city').val($('#sender_city').val());

        $('#receiver_country').val($('#sender_country').val()).trigger('change');

        $('#receiver_post').val($('#sender_post').val());

        $('#receiver_address').val($('#sender_address').val());

    }else{

        $('#receiver_name,#receiver_phone,' +

            '#receiver_email,#receiver_city,' +

            '#receiver_country,#receiver_address,receiver_address').val('').change();

    }

};



viewOrderReport = function(){

    if(formSubmit()){

    view = $('#addSelectedProduct').clone();

    $('#reportSelectedProduct').html(view);

    $('#reportStoreCountry').text($('#storeCountry option:selected').text());

    $('#reportPaymentType').text($('#paymentTypeSelect  option:selected').text());

    $('#reportInvoiceStatus').text($('#statusSelect option:selected').text());

    $('#reportPaymentInfo').text($("#paymentInfo").val());



    $('#reportSenderName').text($("#sender_name").val());

    $('#reportSenderPhone').text($("#sender_phone").val());

    $('#reportSenderEmail').text($("#sender_email").val());

    $('#reportSenderCity').text($("#sender_city").val());

    $('#reportSenderCountry').text($("#sender_country option:selected").text());

    $('#reportSenderAddress').text($("#sender_address").val());



    $('#reportReceiverName').text($("#receiver_name").val());

    $('#reportReceiverPhone').text($("#receiver_phone").val());

    $('#reportReceiverEmail').text($("#receiver_email").val());

    $('#reportReceiverCity').text($("#receiver_city").val());

    $('#reportReceiverCountry').text($("#receiver_country option:selected").text());

    $('#reportReceiverAddress').text($("#receiver_address").val());



    view1 = $('.lastReview > div').clone();

    $('#dialog').html(view1);

    $('#dialog').dialog('open');

    }

};



finalPrice = function(){

// final price before order submit

    if(formSubmit()){

    storeCountry = $('#storeCountry').val();

    deliverCountry = $('#receiver_country').val();

        $.ajax({

            type: "POST",

            url: "order/order_ajax.php?page=shippingPrice",

            data: { storeCountry:storeCountry,deliverCountry:deliverCountry},

            success: function (response) {

               shippingData = ($.parseJSON(response)); // receiveing json



               if(shippingData.find=='1'){



                   shp_int      = shippingData.shp_int;

                   shp_weight   = shippingData.shp_weight;

                   shp_price    = shippingData.shp_price;



                   if(shp_int=='0'){

                        jAlertifyAlert('<?php echo _js(_replace('{{country}}',"'+shp_int+'",$_e['Its Look Like Shipping Stop In Receiver Country {{country}}'])); ?>');

                        return false;

                   }

                   $('.topViewP').fadeIn(500);

                    if($.isNumeric( shp_price )){

                        var orderPrice = parseFloat($('.totalPrice ').text());



                        var weight  = $('.totalWeightInput').val();

                        var unitWeight = Math.ceil(weight/shp_weight);



                        var shippingPrice = parseFloat(shp_price);

                        shippingPrice     = eval(shippingPrice*unitWeight);



                        var total = eval(orderPrice+shippingPrice);

                        var priceCode =$('#priceCode').val();



                        $('.totalPriceModel').text(orderPrice+" "+priceCode);

                        $('.totalPriceShipping').text(shippingPrice+" "+priceCode);

                        $('.totalFinal').text(total+" "+priceCode);

                    }

               }else{

                   jAlertifyAlert('<?php echo _js($_e['Its Look Like Shipping Is Not Available In Receiver Country.']); ?>','Shipping Error');



               }

            }

        });

    }

};



function quick_invoice_update(orderid,ths){

    if(secure_delete("Do you want to update?")){

        selected_id = $(ths).val();

        selected_val = $("option:selected",ths).text();



        $.ajax({

            type: 'POST',

            url: 'order/order_ajax.php?page=quick_invoice_update',

            data: { orderid:orderid,invoice:selected_id }

        }).done(function(data)

        {

            if(data=='1'){

                $(ths).closest(".invoice_quick_select_div").hide(200);

                $(ths).closest("td").find(".invoice_status").html(selected_val);

            }

            else if(data=='0'){

                jAlertifyAlert('<?php echo _js($_e['Update Fail Please Try Again.']); ?>');

            }

            else{

                jAlertifyAlert(data);

            }



        });

    }

}



function show_quick_invoice(ths){

    $(ths).closest("td").find("div").show(200);

}



<?php

if(1===2){

?>

    </script>

<?php } ?>