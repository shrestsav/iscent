<?php header('Content-type: application/x-javascript');
require_once(__DIR__ . '/../global.php');
global $webClass, $functions;
if (1 === 2) {
//Just for suggestion help <script> if not then page behave like txt page+

    ?>

    <script>
<?php }

$check_stock = $functions->developer_setting('product_check_stock');
 ?>

$(document).ready(function () {
     /* $('#menu').slicknav();*/

    $(".accordion").accordion({
        heightStyle: "content"
    });

//auto complete for product

$(".txt_search").each(function(index, el) {
    // console.log($(this));




    $(this).autocomplete({
        source: function (request, response) {
        // var text_value = $(this).val();
        console.log(this.element[0].value);
            $.ajax({
                url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=getSearchJson&limit=3&val=" + this.element[0].value,
                success: function (data) {
                    response(eval(data));
                }
            });
        },


        minLength: 3,
        select: function (event, ui) {
            console.log(ui.item.link);
            window.location = ui.item.link;
            return false;
            $(this).val(ui.item.label);
            $('#searchForm').submit();
        }, open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
            $('.ui-menu').width(300);

            $(".ui-autocomplete").position({
                my: "left bottom",
                at: "left top",
                of: $(this),
                collision: "flip flip"
            });

        },
        position: { my : "right top", at: "right bottom"},
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li></li>")
            .data("item.autocomplete", item)
            .css({
                "margin": "1px 0",
                "height": "85px",
                "padding": "5px"
            })
            .append("<div class='col-xs-12'>" +
            "<div class='col-xs-4'>" +
            "<img class='img-responsive' src='" + item.image + "'/>" +
            "</div>" +
            "<div class='col-xs-8'>" + item.name + "<br> " + item.oldPrice + "  &nbsp; " + item.newPrice + "</div>" +
            "</div>")
            .appendTo(ul);
    };



});
    




    /*    $('.txt_search').keyup(function(){
     srch    =    $('.txt_search').val();
     if(srch.length>=3){
     productSearch(srch);
     }
     });*/

});

function productSearch() {
//not in use,, built for get product for search
    srch = $('.txt_search').val();

    $.ajax({
        type: 'GET',
        url: '<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=getSearchJson',
        data: {val: srch}
    }).done(function (data) {
        if (data == '[]') {
            productSearchAutoComplete(data);
        } else {
            productSearchAutoComplete(data);
        }
    });
}

function productSearchAutoComplete(data) {
    //products load after country select
    var availableTags = eval(data);

    $(".txt_search").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=getSearchJson",
                dataType: "POST",
                data: {val: $('.txt_search').val()},
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 0,
        select: function (event, ui) {
            $('.txt_search').val(ui.item.label);
        }
    }).on('focus : click', function (event) {
        $(this).autocomplete("search", "");
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li></li>")
            .data("item.autocomplete", item)
            .css({
                "margin": "1px 0",
                "height": "70px",
                "padding": "5px"
            })
            .append("<div class='col-sm-12'>" +
            "<div class='col-sm-4'>" +
            "<img class='img-responsive' src='" + item.image + "'/>" +
            "</div>" +
            "<div class='col-sm-8'>" + item.label + "<br> " + item.oldPrice + "<br>" + item.newPrice + "</div>" +
            "</div>")
            .appendTo(ul);
    };


}

//Create Link
function link(id) {
    //document.location.href="?product="+url;
    catP = getParam('cat');
    search = getParam('s');
    var params = "";
    if (catP == "" && search == "") {
        params = "product=" + id;
    } else if (catP != "" && search == "") {
        params = "cat=" + catP + "&product=" + id;
    } else if (catP == "" && search != "") {
        params = "s=" + search + "&product=" + id;
    }

    history.pushState(null, "Product ", "?" + params);
}
function blankLink() {
    //document.location.href="?product="+url;
    catP = getParam('cat');
    search = getParam('s');
    var params = "";
    if (catP == "" && search == "") {
        params = ""
    } else if (catP != "" && search == "") {
        params = "cat=" + catP;
    } else if (catP == "" && search != "") {
        params = "s=" + search;
    }

    history.pushState(null, "Product ", "?" + params);
}
//get Parameter Value
function getParam(sname) {
    var params = location.search.substr(location.search.indexOf("?") + 1);
    var sval = "";
    params = params.split("&");
    // split param and value into individual pieces
    for (var i = 0; i < params.length; i++) {
        temp = params[i].split("=");
        if ([temp[0]] == sname) {
            sval = temp[1];
        }
    }
    return sval;
}

$(document).ready(function () {

    loadId = getParam('product');
    if (isNaN(loadId) || loadId == '') {
    }else{
        quickView(false, loadId);
    }

    $(document).on('click', '.left_image_plus_divTT', function () {
        //remove tt to work like other website
        href = $(this).attr('data-href');
        $('.openProductImage1 img').attr('src', href);
        $('.openProductImage1').css('display', 'block');
        $('body').css('overflow', 'hidden');
    });

    $('.openProductImage1 .close-btn').click(function () {
        $('.openProductImage1').css('display', 'none');
        $('body').css('overflow', 'auto');
    });

    function loadProduct(ths, productId) {
        //first use in happi...
        cat = getParam('cat');
        if (isNaN(cat)) {
            // cat = "";
        }
        url = 'products_detail_ajax.php?cat=' + cat;
        $(".product_detailJs").stop().slideUp(20);
        var thes = $("#detail_" + productId).closest('.pro_box_single');
        $("#detail_" + productId).stop().slideDown("slow");

        link(productId);

        if ($("#detail_" + productId).hasClass('loaded')) {
            scrollProduct(productId);
        } else {
            $.ajax({
                url: url,
                type: "POST",
                data: {id: productId}
            }).done(function (response) {
                $("#detail_" + productId).html(response);
                var left = thes.offset().left;
                var res = 141.5 - left;

                var wid = document.body.offsetWidth;
                $("#detail_" + productId).find(".dive_short_details").css('left', -left);
                $("#detail_" + productId).find(".dive_short_details").css('width', wid);

                $("#detail_" + productId).addClass('loaded');
                scrollProduct(productId);
            });
        }
    }

    function scrollProduct(productId) {
        $('html, body').animate({
            scrollTop: $('#p' + productId).offset().top - 150
        }, 1500);
    }


    $(document).on('click', ".show_details", function () {
        productId = $(this).attr('data-id');
        loadProduct(this, productId);
    });


    $(document).on('click', '.close_details_div', function () {
        $(".product_detailJs").hide("slow");
        blankLink();
    });


    //use this process for click on next to show next product quick view..
    $(document).on('click', '.details_right_btn', function () {
        serialId = parseInt($(this).closest('.product_detailJs').attr('data-serial'));
        serialId = eval(serialId + 1);
        productId = $('.product_serial_' + serialId).attr('data-id');
        if (isNaN(productId)) {
            serialId = 1;
            productId = $('.product_serial_' + serialId).attr('data-id');
        }
        loadProduct(this, productId);
    });

    $(document).on('click', '.details_left_btn', function () {
        serialId = parseInt($(this).closest('.product_detailJs').attr('data-serial'));
        serialId = eval(serialId - 1);
        productId = $('.product_serial_' + serialId).attr('data-id');
        if (isNaN(productId)) {
            serialId = 1;
            productId = $('.product_serial_' + serialId).attr('data-id');
        }
        loadProduct(this, productId);
    });



    ////////////////////////
    /*
    $('.paymentOptionRadio').change(function () {
        paymentOption = $('.paymentOptionRadio:checked').val();
        //0 cash on delivery
        //1 paypal
        //2 klarna
        //5 pason
        //6 GiftCard
        //7 2 checkout

        if (paymentOption == '0' || paymentOption == '1' || paymentOption == '5' ) {
            $("#cartLoading").slideDown(500);
            inv = $('#invoiceId').val();
            $.ajax({
                type: "POST",
                url: "cartContinue.php?inv=" + inv + "&paymentOption=" + paymentOption
            }).done(function (data) {
                $("#cartLoading").slideUp(500);
                $("#cartContinue").html(data);
                add_payment_method_price();
            });
            return true;
        }
        else if (paymentOption == '2') {
            inv = $('#invoiceId').val();
            url = 'klarna.php?ajax=1&inv=' + inv;
            $("#cartLoading").slideDown(500);
            $.ajax({
                type: "POST",
                url: url
            }).done(function (data) {
                $("#cartLoading").slideUp(500);
                $("#cartContinue").html(data);
                add_payment_method_price();
            });
            return true;
        }
        else {
            $("#cartContinue").html("");
        }
    });
*/

    $('#paymentOptionNext').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        select_change_payment();


    });

    $('#overlay_order_container').on('click', '#paymentOptionNext', function(event) {
        /* Act on the event */

        select_change_payment();


    });


});


function select_change_payment() {

    // $('.paymentOptionRadio').change(function () {
    if ( $('.paymentOptionRadio').is(":checked") ) {
        paymentOption = $('.paymentOptionRadio:checked').val();
        //0 cash on delivery
        //1 paypal
        //2 klarna
        //5 pason
        //6 GiftCard
        //7 2 checkout

        if (paymentOption == '0' || paymentOption == '1' || paymentOption == '5' ) {
            $("#cartLoading").slideDown(500);
            inv = $('#invoiceId').val();
            $.ajax({
                type: "POST",
                url: "cartContinue.php?inv=" + inv + "&paymentOption=" + paymentOption
            }).done(function (data) {
                $("#cartLoading").slideUp(500);
                $("#cartContinue").show();
                $("#cartContinue").html(data);

                // hide payment div
                $('#first_option').next('.area_form3').hide('slow');
                $('#first_option').find('.d_tick').show();

                add_payment_method_price();

            });
            return true;
        }
        else if (paymentOption == '2') {
            inv = $('#invoiceId').val();
            url = 'klarna.php?ajax=1&inv=' + inv;
            $("#cartLoading").slideDown(500);
            $.ajax({
                type: "POST",
                url: url
            }).done(function (data) {
                $("#cartLoading").slideUp(500);
                $("#cartContinue").show();
                $("#cartContinue").html(data);
                add_payment_method_price();
            });
            return true;
        }
        else {
            $("#cartContinue").html("");
            add_payment_method_price();
        }



    };
    // });
    
}


function quickView(ths,productId){
    //echo $productClass->productQuickViewModel();
    //call productQuickViewModel() in end of footer file..

    //link(productId);
    //link updating is stop due to some parametrs are remove

    var url = "quickView.php?pId="+productId;
    $("#frameProductQuickView").html(loading_progress()).load(url);
    $("#productQuickView").modal('show');
}


function tax2(total) {
    tax = eval(total * 25 / 100);
    $('.pGrandtax').text(tax);
}


function productColorPriceUpdate(pId, colorId) {
    // console.log(pId);
    // return false;
    // check if this color select n again click nothing happen

    if ($('.colorDiv_' + pId + '.colorId_' + colorId + ":checked").hasClass('colorChecked')) {

    } else {
        var color = $('.colorDiv_' + pId + '.colorId_' + colorId);
        inventoryLimit = $('#deatilStockCheck_' + pId).val();
        //Active color size
        scalesIds = color.attr('data-scales');
        if(inventoryLimit == '1') {
            //hide product size if no stock
           //   $('#p' + pId).find('.size_in_divs.sizeDiv_' + pId).hide();
                $('#p' + pId).find('.size_in_divs.sizeDiv_' + pId).addClass("no_stock");
        }

        var product_div = $('#p' + pId);
        product_div.find(scalesIds).show();
        product_div.find(scalesIds).removeClass("no_stock");
        product_div.find('.sizeId_-1').show(); //custom size
        $('.sizeSelect_' + pId).prop('checked', false);
        $('#stock_' + pId).html("");

        $('.colorDiv_' + pId).removeClass('colorChecked');
        color.addClass('colorChecked');

        //make AddToCart Function to reset
        $('.AddToCart_' + pId).attr('data-scaleSelect', '0');
        $('.sizeChecked').removeClass('sizeChecked');

        var price = color.attr("data-price");

        //if scale not allow in project then show inventory of color,
        colorInv = color.attr("data-colorinv");

        //set old price to default, this is helpful when size has different price
        setOldDefaultPrice(pId);

        if (colorInv != '') {
            stockPrint(pId, colorInv);
        }

        plusPrice(price, pId);

    }
}


function out_of_stock_trigger(pId,colorId,scaleId,storeId){
    var stock_trigger = $("#StockSubscription");
    stock_trigger.find('.out_of_stock.color_id').val(colorId);
    stock_trigger.find('.out_of_stock.scale_id').val(scaleId);
    stock_trigger.find('.out_of_stock.store_id').val(storeId);
    stock_trigger.modal('show');
}

function setOldDefaultPrice (pId) {
    currency        = $('#currency_' + pId);
    defaultPrice    = currency.attr('data-defaultprice');
    //console.log(defaultPrice);
    $('.productOldPrice_'+pId).text(defaultPrice);
}

function stockPrint(pId, data) {

    if (!data) {
        // no data, just print, give default value of 1, this is for the no stock checking 
        $('#hidden_stock_' + pId).val(1);
        <?php
            // $label = $dbF->hardWords("{{no}} Items In Stock - stocklocation: {{location}}",false);
            $unlimited_label = $dbF->hardWords("Unlimited Items In Stock",false);
            // $label = str_replace('{{no}}','',$label);
            // $label = str_replace('{{location}}','',$label);
        ?>
        $('#stock_' + pId).html("<?php echo $unlimited_label; ?>");

        return false;
    }

    if( !pId || !data ) { return false; }
    data_ = $.parseJSON(data);
    data = data_.qty;
    location_ = data_.location_;

    if(data<=0 || isNaN(data)){
        data = 0;
    }

    $('#hidden_stock_' + pId).val(data);
    <?php
     $label = $dbF->hardWords("{{no}} Items In Stock - stocklocation: {{location}}",false);
     $label = str_replace('{{no}}','"+data+"',$label);
     $label = str_replace('{{location}}','"+location_+"',$label);
    ?>
    $('#stock_' + pId).html("<?php echo $label; ?>");
    var hasCustomQty = $('.addByQty_hidden_' + pId).val();
    var customQty = '';
    if(hasCustomQty=='1'){
        if(data>0) {
            $('.addByQty_' + pId).attr("max", data);
            //$('.addByQty_' + pId).val(data);
        }
    }
}

function productRealPrice(pId) {
    currency = $('#currency_' + pId);
    realPrice = parseFloat(currency.attr('data-defaultprice'));
    return realPrice;
}

function productDiscountFormat(pId) {
    currency = $('#currency_' + pId);
    discountFormat = currency.attr('data-discountformat');
    if (discountFormat == "" || isNaN(discountFormat) || discountFormat == undefined) {
        return false;
    }
    return discountFormat;
}

function productDiscountPrice(pId) {
    currency = $('#currency_' + pId);
    discountP = parseFloat(currency.attr('data-discountp'));
    if (discountP == "" || isNaN(discountP) || discountP == undefined) {
        return 0;
    }
    return discountP;
}

function plusPrice(price, pId) {
    price1 = productRealPrice(pId);
    total = eval(price1 + parseFloat(price));
    //$('.productPrice_' + pId).text(price1);

    currency = $('#currency_' + pId);
    discountFormat = currency.attr('data-discountformat');
    discountP = parseFloat(currency.attr('data-discountp'));

    discount = discountCalculation(total, discountP, discountFormat);

    //DiscountFormat = "price"
    //DiscountVal = "50"
    //total     = eval();
    $('.productOldPrice_' + pId).text(total);
    $('.productPrice_' + pId).text(discount);

}

function minusPrice(price, pId) {
    price1 = productRealPrice(pId);
    price1 = eval(price1 - parseFloat(price));
    $('.productPrice_' + pId).text(price1);
}

function plusOldPrice(price, pId) {
    price1 = productDiscountPrice(pId);
    price1 = eval(price1 + parseFloat(price));
    $('.productPrice_' + pId).text(price1);
}

function minusOldPrice(price, pId) {
    price1 = productDiscountPrice(pId);
    price1 = eval(price1 - parseFloat(price));
    $('.productPrice_' + pId).text(price1);
}

function productPriceUpdate ( pId ) {
    //use for scale price update
    setTimeout( function () {
        updatePrice(pId)
    }, 400 );
}

function updatePrice(pId) {
    hasColor = $('#hasColor_' + pId).val();
    hasScale = $('#hasScale_' + pId).val();

    if ($('.sizeSelect_' + pId).is(":checked")) {

        size = $('.sizeSelect_' + pId + ":checked");
        color = $('.colorSelect_' + pId + ':checked');
        currency = $('#currency_' + pId);
        sizeId = size.attr('data-id');

        //check if size already select
        if ($('.sizeId2_' + sizeId + ":checked").hasClass('sizeChecked')) {

        } else {
            $('.sizeSelect_' + pId).removeClass('sizeChecked');
            $('.sizeId2_' + sizeId).addClass('sizeChecked');

            sizeId = size.attr('data-id');
            colorId = color.attr('data-id');

            colorPrice = parseFloat(color.attr('data-price'));
            if (hasColor == '0') {
                colorPrice = 0;
                colorId = 0;
            }
            sizePrice = parseFloat(size.attr('data-price'));
            if (hasScale == '0') {
                sizePrice = 0;
                sizeId = 0;
            }

            realPrice = parseFloat(currency.attr('data-defaultprice'));
            discountFormat = currency.attr('data-discountformat');
            discountP = parseFloat(currency.attr('data-discountp'));

            total = eval(realPrice + colorPrice + sizePrice);

            discount = discountCalculation(total, discountP, discountFormat);

            //DiscountFormat = "price"
            //DiscountVal = "50"
            //total     = eval();
            $('.productOldPrice_' + pId).text(total);
            $('.productPrice_' + pId).text(discount);

            productStockCheck(pId, sizeId, colorId);
                    console.warn(1);

        }
    }
}

function applyCoupon() {
    coupon = $('#couponCode').val();
    if (coupon != '') {
        location.replace('cart?coupon=' + coupon);
    }
}

function applyGiftCard() {
    giftCard = $('#applyGiftCard').val();
    if (giftCard != '') {
        location.replace('cart?giftCard=' + giftCard);
    }
}

function applyGiftCardd() {
    giftCard = $('#applyGiftCard').val();
    if (giftCard != '') {
        location.replace('cart?giftCard=' + giftCard);
    }
}

$(document).ready(function () {
    $('#couponCode').keypress(function (e) {
        var charCode = e.charCode || e.keyCode || e.which;
        if (charCode == 13) {
            applyCoupon();
            return false;
        }
    });

    $('#giftCartId_input').keypress(function (e) {
        var charCode = e.charCode || e.keyCode || e.which;
        if (charCode == 13) {
            applyGiftCard();
            return false;
        }
    });

});




function discountCalculation(total, discountP, discountFormat) {
    var discount = '';
    if (discountFormat != '') {
        if (discountFormat == 'price') {
            discount = eval(total - discountP);
        } else if (discountFormat == 'percent') {
            discount = eval(total * discountP) / 100;
            discount = eval(total - discount);
        }
    } else {
        discount = total;
    }
    discount = Math.ceil(discount);
    return discount;
}


function discountCalculationCart(total, discountP, discountFormat, qty) {
    var discount = '';

    if (discountFormat != '') {
        if (discountFormat == 'price') {
            pricT = parseFloat(eval(discountP * qty));
            discount = pricT;
        } else if (discountFormat == 'percent') {
            discount = eval(total * discountP) / 100;
            discount = Math.round(discount * 100) / 100;
        }
    } else {
        discount = 0;
    }

    discount = eval(discount / qty);
    discount = Math.floor(discount);
    discount = eval(discount * qty);
    return discount;
}


function customFormAddProductFields(pId){
    hasColor    = $('#hasColor_' + pId).val();
    color       = $('.colorSelect_' + pId + ':checked');
    colorId     = color.attr('data-id');
    if (hasColor == '0'){
        colorId = '0';
    }

    storeId     = $('#store_' + pId).val();
    currency    = $('#currency_' + pId);

    $('.customColor_'+pId).val(colorId);
    $('.customStore_'+pId).val(storeId);

}


function customFormSubmit(ths,pId){

    if ( $("#custom_check").is(":checked") ) {
        console.log("THE CHECK BOX IS CHECKED");


        $("#cartLoading").slideDown(500);
        $.ajax({
            type: "POST",
            url: "<?php echo WEB_URL; ?>/_models/functions/products_ajax_functions.php?page=AddToCartCustom",
            data: $('#customForm_'+pId).serialize()
        }).done(function (data) {
            $("#cartLoading").slideUp(500);
            if (data == '1' || data == '') {
                updateCartNoOnWeb();
                cartSmallProduct();
                afterAddToCart_show_goToCart_option();
            } else {
                jAlertifyAlert(data);
            }
        });


    } else {


            $('.checkbox').parents('div.form-group').css({
                'background-color': 'rgb(211, 74, 70)',
                'border': '1px solid'
            });



    };

    return false;
}

productStockCheck = function (pId, scaleId, colorId) {
    if(scaleId == '-1'){
        $('.AddToCart_' + pId).hide(100);

        $('.AddToCart_' + pId).removeAttr("disabled", "disabled");
        $('.AddToCart_' + pId).attr("data-scaleSelect", "1");

        stockPrint(pId, '');
        customFormAddProductFields(pId);
                console.warn(2);
        return true;
    }
    $('.AddToCart_' + pId).show(500);

    inventoryLimit = $('#deatilStockCheck_' + pId).val();
    if(inventoryLimit == '0'){
        $('.AddToCart_' + pId).removeAttr("disabled", "disabled");
        $('.AddToCart_' + pId).attr("data-scaleSelect", "1");
        stockPrint(pId, "");
                console.warn(3);
        return true;
    }

    storeId = $('#store_' + pId).val();
    $('#stock_' + pId).html(loading_progress());
    $.ajax({
        type: "POST",
        url: "<?php echo ADMIN_FOLDER; ?>/stock/stock_ajax.php?page=countCurrentQTY",
        data: {pId: pId, storeID: storeId, scaleId: scaleId, colorId: colorId, loadFromWeb: '1'}
    }).done(function (data) {
        /*$('#stock_'+pId).html(data+" Exemplar i lager");*/
        stockPrint(pId, data);
        if (data == 0) {
            $('.AddToCart_' + pId).attr("disabled", "disabled");
            $('.AddToCart_' + pId).attr("data-scaleSelect", "0");
        } else {
            $('.AddToCart_' + pId).removeAttr("disabled", "disabled");
            $('.AddToCart_' + pId).attr("data-scaleSelect", "1");
        }
    });
                console.warn(4);
}


var checkOutOfferAddtoCart = false; // use this var for check is client add to cart checkout offer or not
var doNotForgetOfferAddtoCart = false; // use this var for check is client add to cart checkout offer or not
function addToCart(ths, pId, show_modal) {
    show_modal = ( typeof(show_modal) == 'undefined' ? false : true );
    hasColor = $('#hasColor_' + pId).val();
    hasScale = $('#hasScale_' + pId).val();
    salePrice = $('#salePrice_' + pId).val();

    // For Special Sale Product on Invoice
    saleProductPrice = ( typeof(salePrice) == 'undefined' ? '' : salePrice );

    scaleSelect = $(ths).attr('data-scaleSelect');
    if ( ( scaleSelect == 0 || scaleSelect == '0' || isNaN(scaleSelect) ) && hasScale == '1' && hasColor == '1' ) {
        //jAlertifyAlert("<?php $webClass->hardWords('Your Selected Item Not in stock. Or select Product Size/Color'); ?>");
        //return false;
    }
    
    if(hasColor != 0 || hasScale !=0 ){
        console.log('has color Or has size');
        if (!$("input[name='sizeSelect_"+pId+"']:checked").val() || !$("input[name='sizeSelect_"+pId+"']:checked").val()){
            console.log('dropdown not selected : '+pId); 
                jAlertifyAlert("<?php $webClass->hardWords("Please select Size and Color first"); ?> <br><br>");
               return false;
            }
    }

    var hasCustomQty = $('.addByQty_hidden_' + pId).val();
    var customQty = '';
    if(hasCustomQty=='1'){
        customQty = $('.addByQty_' + pId).val();
        
        var qtyMax = parseFloat($('.addByQty_' + pId).attr("max"));
        if (customQty > qtyMax) {
            jAlertifyAlert("Product exced Stock qty, max value is : "+qtyMax+"<br><br>");
            return false;
        }
        if( customQty >= '1' || customQty >= 1 ){
            //ok
        }else{
            jAlertifyAlert("<?php $webClass->hardWords("Please Enter Correct Number."); ?> <br><br>");
            $('.addByQty_' + pId).focus();
            return false;
        }
    }

    size        = $('.sizeSelect_' + pId + ":checked");
    color       = $('.colorSelect_' + pId + ':checked');
    currency    = $('#currency_' + pId);

    scaleId = size.attr('data-id');
    if (hasScale == '0') {
        scaleId  = '0';
    }

    colorId = color.attr('data-id');
    if (hasColor == '0') {
        colorId = '0';
    }
    storeId = $('#store_' + pId).val();

    console.log('scaleID : '+scaleId);
    console.log('colorId : '+colorId);


    //Out of stock qty trigger
    qty = $('#hidden_stock_' + pId).val();
    stock_ch = '<?php echo $check_stock; ?>';
    if(qty <=0 && stock_ch == '1'){
        out_of_stock_trigger(pId,colorId,scaleId,storeId);
        return false;
    }


    // Hide Do Not Offer Popup
    // var donot = $('#donotoffer_'+pId).val();
    // if(donot > 0){
    //     $('.pop_side').css({ display: 'none' });
    // }


    productPriceUpdate();
    url = "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=AddToCart";
    if($("#checkout_"+pId).val()==pId){
        checkOutOfferAddtoCart = true;
        url = "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=AddToCart&checkout";
    }

    if($("#donotoffer_"+pId).val()==pId){
        doNotForgetOfferAddtoCart = true;
        url = "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=AddToCart&donotForget";
    }

<?php ### check in developer setting is cart_load_from_side_enabled ?>
        var cart_load_from_side_enabled = <?php echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?>;

        if ( cart_load_from_side_enabled != 1 ) {
            $("#cartLoading").slideDown(500);
        } else {
            $("#cartLoading").slideDown(500);
            $('.overlay').show();
            $('#overlay_container').show();

            /* show the overlay */
            $( "#cart_side" ).animate({ "right": "0px" }, "slow", function() {
                /* stuff to do after animation is complete */
            });

        }

        data1 = {pId: pId, storeID: storeId, scaleId: scaleId, colorId: colorId , customQty:customQty, salePrice:saleProductPrice};
            console.log('RawData : '+data1);

        $.ajax({
            type: "POST",
            url: url,
            data: {pId: pId, storeID: storeId, scaleId: scaleId, colorId: colorId , customQty:customQty, salePrice:saleProductPrice}
        }).done(function (data){
            
            var parsed_data = JSON.parse(data);
            if(parsed_data.status == '1' || parsed_data.status == ''){
                // console.log('donotForget : '+parsed_data.donotForget);
                if ( cart_load_from_side_enabled == 1 ) {
                    if(parsed_data.donotForget != ''){
                        $('#doNotForgetToBuy').html(parsed_data.donotForget);
                        $('.pop_side').show();
                        donot_forget_offers_script();
                    }
                    cart_load_from_side();
                    if (show_modal) {
                        $("#cartLoading").slideUp(500);
                        afterAddToCart_show_goToCart_option();
                    };
                } else {
                    $("#cartLoading").slideUp(500);
                    afterAddToCart_show_goToCart_option();
                }

                updateCartNoOnWeb();
                cartSmallProduct();

            }else{
                jAlertifyAlert(data);
            }
        });
}

function afterAddToCart_show_goToCart_option(){
    var goToCartOption = $('.goToCartOption').val();
    if(goToCartOption=='1'){
        $('#goToCartOptionId').modal('show');
    }
}

function checkOutOffer(){
   if(checkOutOfferAddtoCart == true){
        location.replace("");
       return false;
   }else{
       //alert("checkout");
       return true;
   }
}

function cartSmallProduct() {
    var val     = $('.cartSmallProduct').attr('data-value');
    var val2    = $('.cartPriceAjax').attr('data-value');
    var urlT = '<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=cartSmallProduct';
    if (val == 'has') {
        urlT = urlT + "&product"
    } else if (val2 == 'has') {
        urlT = urlT + "&price"
    }
    if (val == 'has' || val2 == 'has') {
        $('.cartSmallProduct').html(loading_progress());
        $('.cartPriceAjax').html("...");
        $.ajax({
            type: "POST",
            url: urlT,
            data: {}
        }).done(function (data) {
            if (val == 'has') {
                $('.cartSmallProduct').html(data);
            } else if (val2 == 'has') {
                $('.cartPriceAjax').html(data);
            }

        });
    }
}

function addToWishList(ths, pId) {
    $("#cartLoading").slideDown(500);
    $.ajax({
        type: "POST",
        url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=AddToWishList",
        data: {pId: pId}
    }).done(function (data) {
        $("#cartLoading").slideUp(500);
        if(data=='1') {
            updateWishListNoOnWeb();
        }
    });
}
function WishListRemove(ths, pId) {
    $("#cartLoading").slideDown(500);

    $.ajax({
        type: "POST",
        url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=RemoveToWishList",
        data: {pId: pId}
    }).done(function (data) {
        $(ths).closest('.pBox').hide(500);
        $("#cartLoading").slideUp(500);
        if(data=='1') {
            updateWishListNoOnWebMinus();
        }
    });
}

function updateWishListNoOnWeb() {
    no = parseFloat($("#wishListItemNo").text());
    no = eval(no + 1);
    $("#wishListItemNo").text(no);
}
function updateWishListNoOnWebMinus() {
    no = parseFloat($("#wishListItemNo").text());
    no = eval(no - 1);
    $("#wishListItemNo").text(no);
}

function updateCartNoOnWeb() {
    no = parseFloat($(".cartItemNo").eq(0).text());
    no = eval(no + 1);
    $(".cartItemNo").text(no);
}
function updateCartNoOnWebMinus() {
    no = parseFloat($(".cartItemNo").eq(0).text());
    no = eval(no - 1);
    $(".cartItemNo").text(no);
}
function updateCartNoOnWebRemove(qty) {
    no = parseFloat($(".cartItemNo").eq(0).text());
    no = eval(no - qty);
    no = isNaN(no) ? 0: no;
    $(".cartItemNo").text(no);
}

function sumSingleProduct(cartId, qty) {
    ActualPrice = $('#tr_' + cartId).attr('data-realPrice');
    sum = eval(ActualPrice * qty);
    sum = Math.round(sum * 100) / 100;
    $('#tr_' + cartId).find('.sumProduct').text(sum);
    cartDiscount(sum, cartId, qty);
}

function cartDiscount(total, cartId, qty) {
    productId = $('#tr_' + cartId).attr('data-pid');
    discountFormat = $('#discountFormat_' + productId).val();
    discountP = parseFloat($('#discount_' + productId).val());

    discount = discountCalculationCart(total, discountP, discountFormat, qty);
    $('#tr_' + cartId).find('.pDiscount').text(discount);
}

function cart_page_reload(){
    location.reload();
}


function addByQty(ths, cartId) {
    addQty      = parseInt( $(".addByQty_"+cartId).val() );
    qtyOld      = $(".addByQty_"+cartId).attr("data-prev");
    qtyTotal    = parseFloat($('#productTotalQty_' + cartId).val());
    if (addQty > qtyTotal) {
        jAlertifyAlert("Product exced Stock qty, max value is : "+qtyTotal+"<br><br>");
        return false;
    }

    if( addQty >= '1' || addQty >= 1 ){
      //ok
    }else{
        jAlertifyAlert("Please Enter Correct Number. <br><br>");
        return false;
    }

    $("#cartLoading").slideDown(500);
    $.ajax({
        type: "POST",
        url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=addByQty",
        data: {cartId: cartId,addQty:addQty}
    }).done(function (data) {
        $("#cartLoading").slideUp(500);
        if (data == '1' || data == '') {
            updateCartNoOnWeb();
            qty = eval(addQty);
            cartProductQtyUpdate(cartId, qty);
            sumSingleProduct(cartId, qty);
            cartSmallProduct();
            buy_get_free();

            cart_page_reload();
        } else {
            jAlertifyAlert(data);
        }
    });
}



function addPlusToCart(ths, cartId) {
    qty = parseFloat($('#tr_' + cartId).find('.pQty').text());

    qtyTotal = parseFloat($('#productTotalQty_' + cartId).val());
    if (qty == qtyTotal || qty > qtyTotal) {
        return false;
    }

    $("#cartLoading").slideDown(500);
    $.ajax({
        type: "POST",
        url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=AddPlusToCart",
        data: {cartId: cartId}
    }).done(function (data) {
        $("#cartLoading").slideUp(500);
        if (data == '1' || data == '') {
            updateCartNoOnWeb();
            qty = eval(qty + 1);
            cartProductQtyUpdate(cartId, qty);
            sumSingleProduct(cartId, qty);
            cartSmallProduct();
        } else {
            jAlertifyAlert(data);
        }
    });
}

function minusFromCart(ths, cartId) {
    qty = parseFloat($('#tr_' + cartId).find('.pQty').text());
    if (qty == 0 || qty == 1 || qty < 2) {
        return false;
    }

    $("#cartLoading").slideDown(500);
    $.ajax({
        type: "POST",
        url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=minusFromCart",
        data: {cartId: cartId}
    }).done(function (data) {
        $("#cartLoading").slideUp(500);
        if (data == '1' || data == '') {
            updateCartNoOnWebMinus();
            qty = eval(qty - 1);
            cartProductQtyUpdate(cartId, qty);
            sumSingleProduct(cartId, qty);
            cartSmallProduct();
        } else {
            jAlertifyAlert(data);
        }
    });
}


function cartProductRemove(ths, cartId, reload) {
    qty = parseFloat($('#tr_' + cartId).find('.pQty').text());
    reload = ( typeof(reload) == 'undefined' ) ? true : reload ;

    $('.pGrandTotal,.pTotalWeight').html('Loading...');
    $("#cartLoading").slideDown(500);
    $.ajax({
        type: "POST",
        url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=cartProductRemove",
        data: {cartId: cartId}
    }).done(function (data) {
        if (data == '1' || data == '') {
            updateCartNoOnWebRemove(qty);
            remove_div($('#tr_' + cartId), 0);
            $(".tr_"+cartId).hide(500);
            cartSmallProduct();
            setTimeout(function () {
                totalPrice();
                totalWeight();
            }, 1200);

            if (reload) {
                cart_page_reload();
            };

<?php ### check in developer setting is cart_load_from_side_enabled ?>
            var cart_load_from_side_enabled = <?php echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?>;

            if ( cart_load_from_side_enabled == 1 ) {
                var scroll_to_top = false;
                cart_load_from_side(scroll_to_top);
            }      

            $("#cartLoading").slideUp(500);

        } else {
            jAlertifyAlert(data);
        }
    });
}

function totalPrice() {
    total = 0;
    $('.pTotalPrice').each(function (k, v) {
        price = parseFloat($(this).text());
        total = eval(total + price);
    });
    total = Math.round(total * 100) / 100;


    getShippingHightClass();
    shippingFreePriceLimit(total);

    total = addShippingPrice(total);
    updateGiftCardPrice_onCart(total);
    total = removeGiftCardPrice(total);

    $('.pGrandTotal').text(total);
    $('.pGrandTotal').attr('data-total', total);

    tax2(total);

}

function shippingFreePriceLimit(total){
    shippingLimit = $(".shippingLimit").val();
    if(total > shippingLimit){
        //free
        $(".shippingPrice").val(0);
        $(".shippingPriceText").text(0);
    }else{
        var shippingPrice = parseFloat($(".shippingPrice").val());
        $(".shippingPrice").val(shippingPrice);
        $(".shippingPriceText").text(shippingPrice);
    }
}

function removeGiftCardPrice(total){
    var giftCardPrice = parseFloat($(".giftCard_payPrice_input").val());
    if(giftCardPrice > 0){
        total = eval(total - giftCardPrice);
    }
    return total;
}

function updateGiftCardPrice_onCart(totalCartPrice){
    // giftcardPrice_span_cartPrice  giftcardPrice_span_giftPrice

    var giftCardPrice   = $('.giftCard_giftPrice_input').val();
    var giftPayPrice    = $('.giftCard_payPrice_input').val();

    var orderPrice      = parseFloat(totalCartPrice); //grand total
    if(orderPrice > giftCardPrice){
        orderPrice = giftCardPrice;
    }

    $('.giftCard_payPrice_input').val(orderPrice);
    $('.giftcardPrice_span_payPrice').html(orderPrice);

}


function addShippingPrice(total){
    var shippingPrice = parseFloat($(".shippingPrice").val());
    if(shippingPrice > 0){
        total = eval(total + shippingPrice);
    }
    return total;
}


function getShippingHightClass(){
    //this will get high shipping class price.. and change shipping hidden price to new high value

    shippingType = $(".shippingType").val();
    if(shippingType != 'class'){
        return false;
    }

    var shippingBigPrice = 0;
    $(".shippingClass").each(function(){
        price = $(this).val();
        if(shippingBigPrice  < price){
            shippingBigPrice = price;
        }
    });

    $(".shippingPrice").val(shippingBigPrice);
    $(".shippingPriceText").text(shippingBigPrice)
    return shippingBigPrice;
}


function cartProductQtyUpdate(cartId, qty) {
    $('#tr_' + cartId).find('.pQty').text(qty);
    ActualPrice = $('#tr_' + cartId).attr('data-price');
    total = eval(ActualPrice * qty);
    total = Math.round(total * 100) / 100;
    $('#tr_' + cartId).find('.pTotalPrice').text(total);

    totalPrice();
    pWeight(cartId, qty);
}


function cartContinue() {
    $('.paymentOptionDiv').slideDown(500);
    /* $("#cartLoading").slideDown(500);
     $.ajax({
     type: "POST",
     url : "cartContinue.php"
     }).done(function(data) {
     $("#cartLoading").slideUp(500);
     $("#cartContinue ").html(data);
     });*/
}



function pWeight(cartId, qty) {
    ActualWeight = $('#tr_' + cartId).attr('data-weight');
    total = eval(ActualWeight * qty);
    total = Math.round(total * 100) / 100;
    $('#tr_' + cartId).find('.product_weight').val(total);
    $('#tr_' + cartId).find('.pWeight').text(total);
    totalWeight();
}
function totalWeight() {
    total = 0;
    $('.product_weight').each(function (k, v) {
        weight = parseFloat($(this).val());
        total = eval(total + weight);
    });
    total = Math.round(total * 100) / 100;
    $('.pTotalWeight').text(total);
    $('.totalWeightInput').val(total);

}

function shippingPriceWidget() {
    $('.cartSubmit1').attr('disabled', 'true');
    $("#cartLoading").slideDown(500);

    deliverCountry = $('#shippingWidget').val();
    storeCountry = $('#storeCountryShippingWidget').val();
    $.ajax({
        type: "POST",
        url: "<?php echo ADMIN_FOLDER; ?>/order/order_ajax.php?page=shippingPrice",
        data: {storeCountry: storeCountry, deliverCountry: deliverCountry},
        success: function (response) {
            shippingData = ($.parseJSON(response)); // receiveing json

            if (shippingData.find == '1') {
                shp_int = shippingData.shp_int;
                shp_weight = shippingData.shp_weight;
                shp_price = shippingData.shp_price;

                if (shp_int == '0') {
                    jAlertifyAlert('Its Look Like Shipping Stop In Receiver Country ' + shp_int, 'Shipping Error');
                    return false;
                }
                if ($.isNumeric(shp_price)) {
                    var orderPrice = parseFloat($('.pGrandTotal ').attr('data-total'));

                    var weight = parseFloat($('.totalWeightInput').val());
                    var unitWeight = Math.ceil(weight / shp_weight);

                    var shippingPrice = parseFloat(shp_price);
                    shippingPrice = eval(shippingPrice * unitWeight);

                    var total = eval(orderPrice + shippingPrice);
                    $('.pGrandTotal ').text(total).addClass('btn-danger');
                    var priceCode = $('#priceCodeShippingWidget').val();

                    $('.pShippingPriceTemp').text(shippingPrice + " " + priceCode);
                    $('.cartSubmit1').removeAttr('disabled');
                }
            } else {
                jAlertifyAlert('Its Look Like Shipping Is Not Available In Receiver Country', 'Shipping Error');
            }
            $("#cartLoading").slideUp(500);
        }
    });
    intshp = isInternationalShippingWidget();
    if (intshp == false) {
        $('.cartSubmit1').attr('disabled', 'true');
    }
}

function isInternationalShippingWidget() {
    chk = true;
    receiverCountry = $("#shippingWidget").val();
    storeCounntry = $("#storeCountryShippingWidget").val();

    $('.interShipping').each(function (i, data) {
        $(this).closest('tr').find('td').removeClass('btn-danger cartProductHighLight');
        if (receiverCountry == storeCounntry) {
            return true;
        } else if ($(this).val() == '0') {
            tr = $(this).closest('tr').find('td').addClass('btn-danger cartProductHighLight');
            chk = false;
        }
    });
    if (!chk) {
        notification("Shipping Error", "Highlight Product Are not ship in receiver country.", "btn-danger");
        return false;
    }
    return chk;
}


function removeFromSearch(param,value){
    makeAdvanceSearchForm(param,value);
    //param is parameter name and value its value to remove from search
}


function makeAdvanceSearchForm(param,value){
    var isFind = false;
    if(param == undefined || param == '' || param == null){
        param = '';
    }
    if(value == undefined || value == '' || value == null){
        value = '';
    }

    var p = "?";

    //get search query Info
    var search   = getParam('s');
    if(search == "" || search === undefined){
        search  = '';
    }else{
        if(param != 's') {
            search = p + "s=" + search;
            p = "&";
        }
    }

    //get category Info
    var catT   = getParam('cat');
    var catId = '';

    pcatId = $('.activeCategory').val();
    if(pcatId != '' && catT == "") {
        catT = pcatId;
    }
    if(catT == "" || catT === undefined){
        catId = '';
    }else{
        if(param != 'cat') {
            catId = p + "cat=" + catT;
            p = "&";
        }
    }

    //get price info
    var minPrice    = $('#priceMin').val();
    var minPriceData= $('#priceMin').attr('data-min');

    var maxPrice    = $('#priceMax').val();
    var maxPriceData= $('#priceMax').attr('data-max');
    var price       = "";
    if(minPrice != minPriceData || maxPrice != maxPriceData){
        if(param != 'price') {
            price = p + "pMin=" + minPrice + "&pMax=" + maxPrice;
            p = "&";
        }
    }

    //get Color Data
    var color       = "";
    $('.colorCheckBoxes input:checked').each(function(){
        if(param == 'color' && value == $(this).val()) {
        }else{
            color = $(this).val() + "," + color;
        }
    });
    if(color != ""){
        color = p+"color="+color;
        p = "&";
    }

    //get size
    var size       = "";
    $('.sizeCheckBoxes input:checked').each(function(){
        if(param == 'size' && value == $(this).val()) {
        }else{
            size = $(this).val() + "," + size;
        }
    });
    if(size != ""){
        size = p+"size="+size;
        p = "&";
    }
/*    console.log(search);
    console.log(catId);
    console.log(price);
    console.log(color);
    console.log(size);*/
    var link = search+catId+price+color+size;
    //console.log(link);
    location.replace("<?php echo WEB_URL;?>/search"+link);

}



//Scroll product load
moreProduct = true;
load = false;
$(document).scroll(function () {
    if ($('.iHaveProducts').height() - $(window).height() < $(window).scrollTop() && moreProduct == true && load == false) {
        load = true;
        limitFrom = parseInt($('#queryLimit').val());

        if (limitFrom == '' || isNaN(limitFrom)) {
            return false;
        }

        limit   = parseInt($('#queryLimit').attr('data-id'));
        limitTo = eval(limitFrom + limit);

        temp    = '<div class=" loadingDivTemp"><div class="clearfix"></div>' + loading_progress() + '</div>';
        <?php /*  Checking for list view or grid view or any future view, current view is saved in input type hidden in products page  */ ?>
        view   = $('#viewType').val();

        $('.iHaveProducts').append(temp);
        q_tempTable = $("#q_tempTable").val();
        console.log('limitFrom : '+limitFrom+' | limitTO : '+limitTo+' | qTemp : '+q_tempTable);

        if($("#productPage").val()=='deal'){
            url = "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=more_product&productDeals";
        }else {
            url = "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=more_product";
        }
        $.ajax({
            type: "POST",
            url: url,
            data: {id: q_tempTable, limitFrom: limitFrom, limitTo: limitTo, limit: limit, view: view}
        }).done(function (data) {
            load = false;
            $(".loadingDivTemp").remove();
            if (data == '0' || data == '' || parseInt(data) == 0) {
                moreProduct = false;
            } else {
                $('#queryLimit').val(limitTo);
                $('.iHaveProducts').append(data);
                $(document.body).trigger("sticky_kit:recalc");
            }
        });
    }
});


function dealProductAddToCart(ths,dealId){
    // check All select?
    //
    var allOk = true;
    pArry = '';

    var hasCustomQty = $('.addByQty_hidden_' + dealId).val();
    var customQty = '';
    if(hasCustomQty=='1'){
        customQty = $('.addByQty_' + dealId).val();
        var qtyMax = parseFloat($('.addByQty_' + dealId).attr("max"));
        if (customQty > qtyMax) {
            jAlertifyAlert("Product exced Stock qty, max value is : "+qtyMax+"<br><br>");
            return false;
        }
        if( customQty >= '1' || customQty >= 1 ){
            //ok
        }else{
            jAlertifyAlert("<?php $webClass->hardWords("Please Enter Correct Number."); ?> <br><br>");
            $('.addByQty_' + pId).focus();
            return false;
        }
    }

    $('.dealsProducts').each(function(e,data){
        pId = $(this).val();
        pName = $('.pName_'+pId).text();

        hasColor = $('#hasColor_' + pId).val();
        hasScale = $('#hasScale_' + pId).val();
        size        = $('.sizeSelect_' + pId + ":checked");
        color       = $('.colorSelect_' + pId + ':checked');

        scaleId = size.attr('data-id');
        if (hasScale == '0') {
            scaleId = '0';
        }

        colorId = color.attr('data-id');
        if (hasColor == '0') {
            colorId = '0';
        }
        storeId = $('#store_' + pId).val();
        // console.log('storeId : '+storeId);

        // console.log(pId);
        // console.log(pName);
        // console.log(scaleId);
        // console.log(colorId);

        if(scaleId == undefined || isNaN(scaleId) || colorId == undefined || isNaN(colorId) ){
            allOk = false;
            return false;
        }else{
            pArry += '{"pId":'+pId+',"scaleId":'+scaleId+',"colorId":'+colorId+',"storeId":'+storeId+'},';
        }
    });

    if(allOk == false){
        jAlertifyAlert("<?php $webClass->hardWords("Please select product size/color of all package products."); ?> <br><br>");
        return false;
    }else {
       // pArry = "["+pArry+"]";
        $("#packInfo").val(pArry);
        dealId =  $("#packDealId").val();

        var cart_load_from_side_enabled = <?php echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?>;

        if ( cart_load_from_side_enabled != 1 ) {
            $("#cartLoading").slideDown(500);
        } else {
            $("#cartLoading").slideDown(500);
            $('.overlay').show();
            $('#overlay_container').show();

            /* show the overlay */
            $( "#cart_side" ).animate({ "right": "0px" }, "slow", function() {
                /* stuff to do after animation is complete */
            });

        }

        // $("#cartLoading").slideDown(500);
        $.ajax({
            type: "POST",
            url: "<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=AddToCartDeal",
            data: {deal: pArry,dealId:dealId, customQty:customQty}
        }).done(function (data) {
            // console.log('DATA '+data);
            // console.log('Deal : '+pArry+' dealId '+dealId+' customQty : '+customQty);
            $("#cartLoading").slideUp(500);
            if (data == '1' || data == '') {
                if ( cart_load_from_side_enabled == 1 ) {
                    cart_load_from_side();
                    if (show_modal) {
                        $("#cartLoading").slideUp(500);
                        afterAddToCart_show_goToCart_option();
                    };
                } else {
                    $("#cartLoading").slideUp(500);
                    afterAddToCart_show_goToCart_option();
                }
                updateCartNoOnWeb();
                cartSmallProduct();
                // afterAddToCart_show_goToCart_option();
            }else{
                jAlertifyAlert(data);
            }
        });
    }

}



function buy_get_free(){

    $(".buy_2_get_1_free").each(function(){
        buy_qty = parseInt($(this).val());
        cart_id = parseInt($(this).attr("data-id"));
        p_qty   = parseInt($(".cart_product_"+cart_id).find(".pQty").text());
        // console.log(buy_qty);
        // console.log(p_qty);
        if(p_qty >= buy_qty){
            free_qty = Math.floor(p_qty/buy_qty);
            $(".cart_product_"+cart_id).find(".you_get_free_qty").text(free_qty);
            $(".cart_product_"+cart_id).find(".buy_2_get_1_free_div").show(500);
        }else{
            $(".cart_product_"+cart_id).find(".buy_2_get_1_free_div").hide(500);
        }
    });

}


function get_payment_type(){
    return $(".paymentOptionRadio:checked").val();
}


function add_payment_method_price(){
    payment_type = get_payment_type();
    price = parseFloat($(".payment_method_price_"+payment_type).val());
    if(price > 0){
        total_price = parseFloat($(".pGrandTotal").attr("data-total"));
        total_price = total_price+price;
        $(".pGrandTotal").text(total_price);

        //add in shipping price
        shipping_price = parseFloat($('.pShippingPriceTemp').attr("data-real"));
        shipping_price = shipping_price+price;
        $('.pShippingPriceTemp').text(shipping_price);
    }else{
        total_price = parseFloat($(".pGrandTotal").attr("data-total"));
        $(".pGrandTotal").text(total_price);

        //add in shipping price
        shipping_price = parseFloat($('.pShippingPriceTemp').attr("data-real"));
        $('.pShippingPriceTemp').text(shipping_price);
    }
}



function loadCustomFormInfo(customId){
        $('.loadCustomFormInfo').html( loading_progress() );
        url = "<?php echo WEB_URL;?>/src/model.php?page=loadCustomFormInfo&id="+customId;
        $('.loadCustomFormInfo').load(url);
}


function checkout_offers_script() {


            $("#sample dt a").click(function() {

                $(this).parent().next().find('ul').fadeToggle(600);
                $("#sample_select dd ul").fadeOut(600);

            });

            $("#sample dd ul li a").click(function() {
                var text = $(this).html();
                console.log($(this).prevAll('li'));
                console.log('checkout_offers_script : '+text);   

                // show the selected size
                $(this).closest('dd').prev('dt').find('a > span').html(text);

                // $("#sample dt a span").html(text);
                $("#sample dd ul").fadeOut(600);
            });

            $("#sample_select dt a").click(function() {
                $(this).parent().next().find('ul').fadeToggle(600);
              console.log('clicksize');
                $("#sample dd ul").fadeOut(600);



                /*$("#sample dd ul").toggle();*/
            });

            $("#sample_select dd a").click(function() {
                console.log('Clicked Size');
                console.log($(this).html());
                
                // save the selected size
                var size_selected = $(this).find('div.color_name.size').html();
                
                // hide the ul
                $(this).closest('ul').hide();

                // show the selected size
                $('#sample_select > dt > a > span').html(size_selected);
            });

            $("#productSearchAutoComplete_select dd ul li a").click(function() {
                var text = $(this).html();

                $("#sample_select dt a span").html(text);
                $("#sample_select dd ul").fadeOut(600);
            });
    
}

function donot_forget_offers_script() {


            $("#sample dt a").click(function() {

                $(this).parent().next().find('ul').fadeToggle(600);
                $("#sample_select dd ul").fadeOut(600);

            });

            $("#sample dd ul li a").click(function() {
                var text = $(this).html();
                console.log($(this).prevAll('li'));
                console.log('checkout_offers_script : '+text);   

                // show the selected size
                $(this).closest('dd').prev('dt').find('a > span').html(text);

                // $("#sample dt a span").html(text);
                $("#sample dd ul").fadeOut(600);
            });

            $("#sample_select dt a").click(function() {
                $(this).parent().next().find('ul').fadeToggle(600);
              console.log('clicksize');
                $("#sample dd ul").fadeOut(600);



                /*$("#sample dd ul").toggle();*/
            });

            $("#sample_select dd a").click(function() {
                console.log('Clicked Size');
                console.log($(this).html());
                
                // save the selected size
                var size_selected = $(this).find('div.color_name.size').html();
                
                // hide the ul
                $(this).closest('ul').hide();

                // show the selected size
                $('#sample_select > dt > a > span').html(size_selected);
            });

            $("#productSearchAutoComplete_select dd ul li a").click(function() {
                var text = $(this).html();

                $("#sample_select dt a span").html(text);
                $("#sample_select dd ul").fadeOut(600);
            });

            // $('.pop_slide').owlCarousel({
            //     loop: true,
            //     navigation: true,
            //     autoplay: true,
            //     autoplayTimeout: 3000,
            //     autoplayHoverPause: true,
            //     items: 1,
            //     responsiveClass: true,
            //     responsive: {
            //         0: {
            //             items: 1,
            //             nav: true
            //         },
            //         300: {
            //             items: 1,
            //             nav: true,
            //         },
            //         1600: {
            //             items: 1,
            //             nav: true,
            //         }
            //     }
            // });
            // $(".pop_btn2").click(function() {
            //     var owl = $(".pop_slide").data('owlCarousel');
            //     owl.next() // Go to next slide
            // });
            // $(".pop_btn1").click(function() {
            //     var owl = $(".pop_slide").data('owlCarousel');
            //     owl.prev() // Go to previous slide
            // });
    
}


//Color n Size Dropdown Script
        $( document ).ready(function() {

            $("#sample dt a").click(function() {

                $(this).parent().next().find('ul').fadeToggle(600);
                $("#sample_select dd ul").fadeOut(600);

            });

            $("#sample dd ul li a").click(function() {
                var text = $(this).html();
                console.log($(this).prevAll('li'));
                console.log(text);    

                // show the selected size
                $(this).closest('dd').prev('dt').find('a > span').html(text);

                // $("#sample dt a span").html(text);
                $("#sample dd ul").fadeOut(600);
            });

        });

        $( document ).ready(function() {

            $("#sample_select dt a").click(function() {
                $(this).parent().next().find('ul').fadeToggle(600);
              console.log('clicksize');
                $("#sample dd ul").fadeOut(600);



                /*$("#sample dd ul").toggle();*/
            });

            $("#sample_select dd a").click(function() {
                console.log('Clicked Size');
                console.log($(this).html());
                
                // save the selected size
                var size_selected = $(this).find('div.color_name.size').html();
                
                // hide the ul
                $(this).closest('ul').hide();

                // show the selected size
                $('#sample_select > dt > a > span').html(size_selected);
            });

            $("#productSearchAutoComplete_select dd ul li a").click(function() {
                var text = $(this).html();

                $("#sample_select dt a span").html(text);
                $("#sample_select dd ul").fadeOut(600);
            });
        });

// $(document).ready(function(){
//     $("#info2").click(function(){
//         $("#info_cart").fadeToggle(800);
//                 $("#info_account").hide();
//     });
// });

// $(document).ready(function(){
//     $("#info3").click(function(){
//         $("#info_account").fadeToggle(800);
//                     $("#info_cart").hide();
//     });
// });

//Quantity Button Script


        $(document).ready(function(){
            // This button will increment the value
            $('.qtyplus').click(function(e){
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                fieldName = $(this).attr('field');
                // Get its current value
                var currentVal = parseInt($('input[name='+fieldName+']').val());
                // If is not undefined
                if (!isNaN(currentVal)) {
                    // Increment
                    $('input[name='+fieldName+']').val(currentVal + 1);
                } else {
                    // Otherwise put a 0 there
                    $('input[name='+fieldName+']').val(0);
                }
            });
            // This button will decrement the value till 0
            $(".qtyminus").click(function(e) {
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                fieldName = $(this).attr('field');
                // Get its current value
                var currentVal = parseInt($('input[name='+fieldName+']').val());
                // If it isn't undefined or its greater than 0
                if (!isNaN(currentVal) && currentVal > 1) {
                    // Decrement one
                    $('input[name='+fieldName+']').val(currentVal - 1);
                } else {
                    // Otherwise put a 1 there
                    $('input[name='+fieldName+']').val(1);
                }
            });
        });


// function cartSmallProduct() {
//     var val     = $('.cartSmallProduct').attr('data-value');
//     var val2    = $('.cartPriceAjax').attr('data-value');
//     var urlT = '<?php echo WEB_URL;?>/_models/functions/products_ajax_functions.php?page=cartSmallProduct';
//     if (val == 'has') {
//         urlT = urlT + "&product"
//     } else if (val2 == 'has') {
//         urlT = urlT + "&price"
//     }
//     if (val == 'has' || val2 == 'has') {
//         $('.cartSmallProduct').html(loading_progress());
//         $('.cartPriceAjax').html("...");
//         $.ajax({
//             type: "POST",
//             url: urlT,
//             data: {}
//         }).done(function (data) {
//             if (val == 'has') {
//                 $('.cartSmallProduct').html(data);
//             } else if (val2 == 'has') {
//                 $('.cartPriceAjax').html(data);
//             }

//         });
//     }
// }


function total_text_for_input() {
    var total_text_for_input = "<?php $dbF->hardWords("-:BESTÄLL - ") ?> ";
    return total_text_for_input;
}

function cart_load_from_side(to_scroll) {

    var to_scroll = typeof(to_scroll) === 'undefined' ? true : to_scroll;

    $('.overlay').show();
    $('#overlay_container').show();

    // /* show the overlay */
    // $( "#cart_side" ).animate({ "right": "0px" }, "slow", function() {
    //     /* stuff to do after animation is complete */
    // });

    //console.warn( <?php //echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?> );
    $.post('ajax_call.php?page=cart_side_view', {param1: 'value1'}, function(data, textStatus, xhr) {
        /*optional stuff to do after success */
        
        // #cart_items_container
        // console.log('parsed_data : '+data);
        var parsed_data = JSON.parse(data);
        
        
        $('#cart_items_container').html(parsed_data.products);
        $('#coupon_text').html(parsed_data.coupon_text);
        $('#coupon_remove_text').html(parsed_data.remove_coupon_text);
        $('#cart_side_grandtotal').val(total_text_for_input() + parsed_data.price_simple + " " + parsed_data.symbol);
        $('#three_for_two_text').html(parsed_data.three_for_2_cat_div);
        $('#staple_product_text').html(parsed_data.staple_pro_cat_div);
        $('#giftcard_text').html(parsed_data.msg);
        $('#giftcard_remove_text').html(parsed_data.removeGiftCard);
        $('#price_simple').val(parsed_data.price_simple);

    }).done(function(){
        $( "#cart_side" ).animate({ "right": "0px" }, "slow" );
        $("#cartLoading").slideUp(500);
        console.log('INSIDE DONE');
        scroll_to_top(to_scroll);
    });


    
}


    <?php ## set on checkout click ?>
    $('#goToCartOptionId').on('click', '#go_to_checkout, #continue_shopping', function(event) {
        event.preventDefault();
        /* Act on the event */

<?php ### check in developer setting is cart_load_from_side_enabled ?>
        var cart_load_from_side_enabled = <?php echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?>;
        var old_val    = parseInt($('#order_submit').val());

        if ( cart_load_from_side_enabled == 1 ) {

            if (this.id == 'go_to_checkout') {

                $("#cartLoading").slideDown(500);

                if ( old_val > '1' ) {
                    $('#checkoutOfferModal').modal('hide');
                    cart_load_from_side_submit();
                } else {
                    console.info('old_val is: ' + old_val);
                    cart_load_from_side();
                };

            } else {
                    $('#checkoutOfferModal').modal('hide');
                    // #continue_shopping
                    // $('#goToCartOptionId').modal('hide');
            }
                $('#goToCartOptionId').modal('hide');

            console.log('Clicked on ' + this.id);
            console.log('Cart load from side is enabled!');
        }

    });


<?php ### alertify button on clicking OK button ?>
    $('body').on('click', '#alertify-ok', function(event) {
        event.preventDefault();
        /* Act on the event */
        $("#cartLoading").slideUp(500);
    });


<?php ### check out offer dismiss button click ?>
    $('#checkoutOfferModal').on('click', '#checkout_offer_dismiss_btn', function(event) {
        event.preventDefault();
        /* Act on the event */
        $("#cartLoading").slideDown(500);
        cart_load_from_side_submit();

    });

<?php ### overlay div click ?>
    $('body').on('click', '.overlay', function(event) {
        event.preventDefault();
        /* Act on the event */
        console.log('clicked on body');
        var target = $(this);
        $( "#cart_side" ).animate({ "right": "-500px" }, "slow", function() {
            console.log('in cart side function');
            target.hide();
            $('#overlay_container').hide();
        });

    });



    function scroll_to_top(to_scroll) {

        condition = ( $(window).width() <= 758 );
        // if ( typeof(for_all) !== 'undefined' ) {
        //     condition = true;
        // }
        // console.error('to_scroll: ' + to_scroll);
        if ( typeof(to_scroll) !== 'undefined' ) {
            if (to_scroll == false) {
                // console.error('scroll_to_top returned');
                return false;
            };
        }

        condition = true; // hardcoding true, scroll to top always.

        if ( condition ) {
            $('body').animate({scrollTop: 0},'1000')
            console.log('Scrolled to top');
        }

    }
        



    <?php ## set on mobile menu icon click to show cart in responsive screen ?>
    $('#scroll_responsive_section').on('click', '#responsive_cart_menu', function(event) {
        event.preventDefault();
        /* Act on the event */

<?php ### check in developer setting is cart_load_from_side_enabled ?>
        var cart_load_from_side_enabled = <?php echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?>;

        if ( cart_load_from_side_enabled == 1 ) {

            $("#cartLoading").slideDown(500);
            cart_load_from_side();
        }

    });

    <?php ## set on menu icon click to show cart in full screen ?>
    $('#cart_area').on('click', '#cart', function(event) {
        event.preventDefault();
        /* Act on the event */

<?php ### check in developer setting is cart_load_from_side_enabled ?>
        var cart_load_from_side_enabled = <?php echo ($functions->developer_setting('cart_checkout_from_side_modal')); ?>;

        if ( cart_load_from_side_enabled == 1 ) {

            $("#cartLoading").slideDown(500);
            cart_load_from_side();
        }

    });




function cart_load_from_side_coupon_set() {

    var coupon_val = $('#coupon_input').val();

    if (coupon_val == '') {
        return false;
    };

    $.post('ajax_call.php?page=set_unset_coupon', {coupon: coupon_val }, function(data, textStatus, xhr) {
        /*optional stuff to do after success */
        // console.log(data);
        // #cart_items_container
        var parsed_data = JSON.parse(data);
        $('#cart_items_container').html(parsed_data.products);
        $('#coupon_text').html(parsed_data.coupon_text);
        $('#coupon_remove_text').html(parsed_data.remove_coupon_text);
        $('#cart_side_grandtotal').val(total_text_for_input() + parsed_data.price_simple + " " + parsed_data.symbol);

    });

}


    <?php ## set coupon on click ?>
    $('#side_coupon_container').on('click', '#side_coupon_submit', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('Clicked on coupon submit button');
        cart_load_from_side_coupon_set();

    });

function cart_load_from_side_gift_set() {

    var giftcard_val = $('#giftcard_input').val();

    if (giftcard_val == '') {
        return false;
    };

    $.post('ajax_call.php?page=set_unset_giftcard', {giftcard: giftcard_val }, function(data, textStatus, xhr) {
        /*optional stuff to do after success */
        // console.log(data);
        // #cart_items_container
        var parsed_data = JSON.parse(data);
        $('#cart_items_container').html(parsed_data.products);
        $('#giftcard_text').html(parsed_data.msg);
        $('#giftcard_remove_text').html(parsed_data.removeGiftCard);
        $('#cart_side_grandtotal').val(total_text_for_input() + parsed_data.price_simple + " " + parsed_data.symbol);

    });
    
}

function cart_load_from_side_gift_remove() {

    $.post('ajax_call.php?page=set_unset_giftcard', { remove_giftcard: 1 }, function(data, textStatus, xhr) {
        /*optional stuff to do after success */
        var parsed_data = JSON.parse(data);
        $('#cart_items_container').html(parsed_data.products);
        $('#giftcard_text').html(parsed_data.msg);
        $('#giftcard_remove_text').html('');
        $('#giftcard_input').val('');
        $('#cart_side_grandtotal').val(total_text_for_input() + parsed_data.price);

    });
    
}

function cart_load_from_side_coupon_remove() {

    $.post('ajax_call.php?page=set_unset_coupon', { remove_coupon: 1 }, function(data, textStatus, xhr) {
        /*optional stuff to do after success */
        var parsed_data = JSON.parse(data);
        $('#cart_items_container').html(parsed_data.products);
        $('#coupon_text').html(parsed_data.coupon_text);
        $('#coupon_remove_text').html('');
        $('#coupon_input').val('');
        $('#cart_side_grandtotal').val(total_text_for_input() + parsed_data.price_simple + " " + parsed_data.symbol);

    });
    
}

function cart_side_load_order_file(invoiceId) {
    $("#cartLoading").slideDown(500);
    $.get('ajax_call.php?page=cart_side_load_order_file', { invoiceId: invoiceId,  }, function(data) {
        /*optional stuff to do after success */
        var parsed_data  = JSON.parse(data);
        $('#overlay_order_container').html(parsed_data.order_popup_html);
        $('#overlay_order_container').append(parsed_data.cartCustomSizeModals);
        $('#ordered_products_area').html(parsed_data.cart_side_order_products_html);
        $('#ordered_prices').html(parsed_data.order_price_html);
        $("#cartLoading").slideUp(500);
        scroll_to_top();
    });
    
}

function cart_load_from_side_submit() {

    var serialized = $('#cart_side_form').serialize();
    var old_val    = parseInt($('#order_submit').val());

    $.get('ajax_call.php?page=cart_side_submit&'+serialized, function(data) {
        /*optional stuff to do after success */
        var parsed_data = JSON.parse(data);
        console.log(parsed_data.donotforget_offer);
        // var checkout_offer_length = typeof(parsed_data.checkout_offer.length) === 'undefined' ? 0 : parsed_data.checkout_offer.length;
        // console.error(parsed_data.checkout_offer != '');
        if ( old_val == '1' && parsed_data.checkout_offer != '' ) {
            $('#checkout_offer_container').html(parsed_data.checkout_offer);
            $('#checkoutOfferModal').modal('show');
            checkout_offers_script();
        } else {
            cart_side_load_order_file(parsed_data.invoiceId);
            $( "#cart_side" ).animate({ "right": "-500px" }, "slow" );
            scroll_to_top(true);
        };

    });

    $('#order_submit').val(old_val + 1);

}


    <?php ## set giftcard on click ?>
    $('#side_giftcard_container').on('click', '#side_giftcard_submit', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('Clicked on giftcard submit button');
        cart_load_from_side_gift_set();

    });


    <?php ## set giftcard remove on click ?>
    $('#giftcard_remove_text').on('click', '#giftcard_remove', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('Clicked on giftcard remove button');
        cart_load_from_side_gift_remove();

    });


    <?php ## set coupon remove on click ?>
    $('#coupon_remove_text').on('click', '#coupon_remove', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('Clicked on coupon remove button');
        cart_load_from_side_coupon_remove();

    });


    <?php ## set cart submit on click ?>
    $('#cart_side_form').on('click', '#cart_side_grandtotal', function(event) {
        event.preventDefault();
        /* Act on the event */

        console.log('Clicked on cart submit button');
        cart_load_from_side_submit();

    });







<?php
if(1===2){ //
?>
</script>
<?php } // ?>