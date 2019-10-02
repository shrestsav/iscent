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
    $_w['Product Scale Not Available'] = '' ;
    $_w['Product Color Not Available'] = '' ;
    $_w['Required Fields Are Empty'] = '' ;
    $_w['Product Quantity Is Not Correct.'] = '' ;
    $_w['Product Price Is Not Correct.'] = '' ;
    $_w['Duplicate Entry : Product Item already exist in list!'] = '' ;

    $_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin StockScript');

if(1===2){
//Just for suggestion help <script> if not then page behave like txt page
?>
<script>
    <?php } ?>
    function scale(data){
        scaleId = "#receipt_product_scale";
        scaleHiddenClass= ".receipt_product_scale";
        if(data==null){
            $(scaleId).val("<?php echo _js($_e['Product Scale Not Available']); ?>").attr("readonly","readonly");
            $(scaleHiddenClass).removeClass("has");
            $(scaleHiddenClass).val('0').attr("data-val",'0');
            data = [];
        }else{
            $(scaleId).val('').removeAttr("readonly");
            $(scaleHiddenClass).addClass("has");
        }

        $(scaleId).autocomplete({
            source: data,
            minLength: 0,
            select: function( event, ui ) {
                $(scaleHiddenClass).val(ui.item.id).attr("data-val",ui.item.label);
            }
        }).on('focus : click', function(event) {
                $(this).autocomplete("search", "");
            });
    };



    function color(data){
        colorId = "#receipt_product_color";
        colorHiddenClass= ".receipt_product_color";
        $(colorId).css('border','1px solid #ccc');
        if(data==null){
            $(colorId).val("<?php echo _js($_e['Product Color Not Available']); ?>").attr("readonly","readonly");
            $(colorHiddenClass).removeClass("has");
            $(colorHiddenClass).val('0').attr("data-val",'0');
            data = [];
        }else{
            $(colorId).val('').removeAttr("readonly");
            $(colorHiddenClass).addClass("has");
        }
        $(colorId).autocomplete({
            source: data,
            minLength: 0,
            select: function( event, ui ) {
                $(colorHiddenClass).val(ui.item.id).attr("data-val",ui.item.label);
                $(colorId).css('border','3px solid #'+ui.item.label);
            }
        }).on('focus : click', function(event) {
                $(this).autocomplete("search", "");
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .css({"margin":"1px 0",
                    "height": "23px",
                    "padding":"0"})
                .append("<div style='background:#"+item.label+";color:#FFF;height:100%;'>"+item.label+"</div>")
                .appendTo(ul);
        };
    };

function receiptFormValid(){
    if( $("#receipt_date").val() == "" ||  $(".receipt_product_id").val() == "" ||
        $(".receipt_product_scale.has").val() == ""  || $(".receipt_product_color.has").val() == "" ||
        $("#receipt_store_id").val() == ""
        ){
        jAlertifyAlert("<?php echo _js($_e['Required Fields Are Empty']); ?>");
        return false;
    }

    qty =parseInt($("#receipt_qty").val());
    if(qty > 0){
        $("#receipt_qty").val(qty);
    }else{
        jAlertifyAlert("<?php echo _js($_e['Product Quantity Is Not Correct.']); ?>");
        return false;
    }

    price =parseFloat($("#receipt_price").val());
    if(price > 0){
        $("#receipt_price").val(price)
    }else{
        jAlertifyAlert("<?php echo _js($_e['Product Price Is Not Correct.']); ?>");
        return false;
    }
    addListItem();

}
var sr=0;
function addListItem() {
    //disable one time required fields
    $(".receipt_store_id").val($("#receipt_store_id").val());
    $("#store").attr("disabled","disabled");
    $("#receipt_date,#receipt_vendor").attr("readonly","readonly");
    //disable end


    var pid     = parseInt($(".receipt_product_id").val());
    var pScaleId = parseInt($(".receipt_product_scale").val());
    var pColorId = parseInt($(".receipt_product_color").val());

    if(isNaN(pScaleId)){pScaleId = 0;}
    if(isNaN(pColorId)){pColorId = 0;}

    scaleVal = " -- "   +   $("#receipt_product_scale").val();
    colorVal = " -- "   +   $("#receipt_product_color").val();

    //if no color or scale has then scale and color name blank to show on temparary
    if(pScaleId == '0'){
        scaleVal = '';
    }
    if(pColorId == '0'){
        colorVal = '';
    }

    var pName = $("#receipt_product_id").val() + scaleVal +colorVal;
    //    var vendor =$("#receipt_vendor").val();
    var date    =   $("#receipt_date").val();
    var price   =   parseFloat($("#receipt_price").val());
    var qty     =   parseInt($("#receipt_qty").val());
    var store   =   parseInt($(".receipt_store_id").val());
    //   var storeName = $("#receipt_store_id option:selected").text();

    var trpid = "p_"+pid+"-"+pScaleId+"-"+pColorId+"-"+store;
    if (document.getElementById("tr_"+trpid)) {

        jAlertifyAlert("<?php echo _js($_e['Duplicate Entry : Product Item already exist in list!']); ?>");

        document.getElementById(trpid).checked = true;
        checkchange(trpid);
    }
    else if (qty > 0 && pid > 0 ) {
        sr++;

        var item = "<tr id='tr_"+trpid+ "'>"+
            "<td><input type='checkbox' id='"+trpid+ "' onchange='checkchange(this.id)' value='" + trpid + "' class='checkboxclass' />" +
            "<input type='hidden' name='cart_list[]' value='"+trpid+"' /><span>" + sr + "</span></td>"+
            //  "<td>"+date+"<input type='hidden' name='pdate_"+trpid+"' value='"+date+"' /></td>"+
            //  "<td>"+vendor+"<input type='hidden' name='pvendor_"+trpid+"' value='"+vendor+"' /></td>"+
            "<td>"+pName+"<input type='hidden' name='pid_"+trpid+"' value='"+pid+"' />" +
            "<input type='hidden' name='pscale_"+trpid+"' value='"+pScaleId+"' />" +
            "<input type='hidden' name='pcolor_"+trpid+"' value='"+pColorId+"' /></td>"+
            "<td>"+qty + "<input type='hidden' name='pqty_"+trpid+"' value='"+qty+"' /></td>"+
            "<td>"+price+"<input type='hidden' name='pprice_"+trpid+"' value='"+price+"' /></td>"+
            //  "<td>"+storeName+"<input type='hidden' name='pstore_"+trpid+"' value='"+store+"' /></td>"+
            "</tr>";

        $("#vendorProdcutList").append(item);
        blankField();
    }

}

function blankField(){
    $("#receipt_qty,#receipt_price,#receipt_product_id, .receipt_product_id, .receipt_product_scale, .receipt_product_color").val("");
    color(null);
    scale(null);
}

function checkchange(pid) {
    var tr = "tr_" + pid;
    if ($('#' + pid).is(":checked")) {
        $("#"+tr).addClass("highlitedtd");
    }
    else {
        $("#"+tr).removeClass("highlitedtd");
    }
}

function removechecked() {
    $('.highlitedtd').remove();
}


function uncheckall() {
    if($("#vendorProdcutList tr").hasClass('highlitedtd')){
        $( ".checkboxclass" ).prop( "checked",false )
        $("#vendorProdcutList tr").removeClass("highlitedtd");
    }else{
        $( ".checkboxclass" ).prop( "checked",true )
        $("#vendorProdcutList tr").addClass("highlitedtd");
    }
}

function formSubmit(){
    if ( $('#vendorProdcutList tr').length > 0 ) {
        return true;
    }else{
        return false;
    }
}


<?php
if(1===2){
?>
</script>
<?php } ?>