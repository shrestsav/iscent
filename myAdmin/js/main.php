<?php header('Content-type: application/x-javascript');
require_once(__DIR__ . '/../global.php');
global $webClass;
if (1 === 2) {
//Just for suggestion help <script> if not then page behave like txt page
?>
<script>

    <?php } ?>

/* defines
*
* function secure_delete(text) return true or false, user sure to delete item.
*    // text = 'view on alert'; if blank show default content
*
* function remove_tr(ths,time) // remove click parent tr,
*     // remove_tr(this,time for wait); this required.
*
* loading_progress(){
 //work with bootstrap.
*
*
* // use .dTable in table for datatable
*
*
* dateJqueryUi(); apply date on class .date
*
*
* jAlert(val1,title); and define <div id="jAlert"></div> in code
*
*
* count animation countAnimation(obj,from,to,textVal); obj = $('id'), from = 50, to=100,textval = val/text mean text()
*
* notification('heading work','textwork','btn-primary');
* */


var formControllers = function () {
    $fc = this;
    this.selectboxValueSelector = function (id, val) {
         /*   var ob = "#" + id + " option[value='" + val + "']";
            $(ob).attr("selected", "selected");
            Second Method
            */
        $('#'+id).val(val).trigger('change');
    };
    this.inpval = function (ob, text) {
        $(ob).val(text);
    };
    this.arrayKeyExists = function (key, arr) {
        if (arr[key] === undefined) {
            return false;
        } else {
            return true;
        }
    };
    var msgArray = new Array();
    msgArray["_msgDeleteConfirmation"] = "Are you sure you want to delete this?";
    this.errorMsg = function (msgCode) {
        if (this.arrayKeyExists(msgCode, msgArray) === true) {
            return msgArray[msgCode];
        } else {
            return msgCode;
        }
    };
    this.anchorByPost = function (obj) {
        $(function () {
            $(obj).on("click", function () {
                var form_action = "";
                if ($(this).attr("data-action") !== undefined) {
                    form_action = $(this).attr("data-action");
                }
                if ($(this).attr("data-window") !== undefined) {
                    var formBody = '<form action="' + form_action + '" method="POST" target="_blank">';
                } else{
                    var formBody = '<form action="' + form_action + '" method="POST">';
                }
                var href = $(this).attr("href");
                href = href.replace("?", "");
                var str_array = href.split('&');
                for (var i = 0; i < str_array.length; i++) {
                    str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
                    var x2str = str_array[i];
                    var str_array_2 = x2str.split('=');
                    for (var i2 = 0; i2 < str_array_2.length; i2 = i2 + 2) {
                        formBody += '<input type="hidden" name="' + str_array_2[i2] + '" value="' + str_array_2[(i2 + 1)] + '" />';
                    }
                }
                formBody += '</form>';
                var $form = $(formBody).appendTo('body');
                if ($(this).attr("data-confirmation") !== undefined) {
                    var data_confirmation = $(this).attr("data-confirmation");
                    var msg_ = $fc.errorMsg(data_confirmation);
                    var conformation = confirm(msg_);
                    if (conformation == true) {
                        $form.submit();
                    }
                } else {
                    $form.submit();
                }
                return false;
            });
        });
    };
}
functions = {   uniqid: function (prefix) {
    prefix = (typeof (prefix) != "undefined") ? prefix : "";
    var d = new Date();
    var dx = d.toJSON();
    /*dx = dx.replace(/\:|\-|\./g,"");*/
    dx = dx.replace(/[^a-zA-Z0-9]+/g, "");
    dx += (Math.floor(Math.random() * 100));
    return prefix + (dx.split('').sort(function () {
        return 0.5 - Math.random()
    }).join(''));
}, CRC32: function (str) {
    var CRCTable = [0x00000000, 0x77073096, 0xEE0E612C, 0x990951BA, 0x076DC419, 0x706AF48F, 0xE963A535, 0x9E6495A3,
        0x0EDB8832, 0x79DCB8A4, 0xE0D5E91E, 0x97D2D988, 0x09B64C2B, 0x7EB17CBD, 0xE7B82D07, 0x90BF1D91, 0x1DB71064,
        0x6AB020F2, 0xF3B97148, 0x84BE41DE, 0x1ADAD47D, 0x6DDDE4EB, 0xF4D4B551, 0x83D385C7, 0x136C9856, 0x646BA8C0,
        0xFD62F97A, 0x8A65C9EC, 0x14015C4F, 0x63066CD9, 0xFA0F3D63, 0x8D080DF5, 0x3B6E20C8, 0x4C69105E, 0xD56041E4,
        0xA2677172, 0x3C03E4D1, 0x4B04D447, 0xD20D85FD, 0xA50AB56B, 0x35B5A8FA, 0x42B2986C, 0xDBBBC9D6, 0xACBCF940,
        0x32D86CE3, 0x45DF5C75, 0xDCD60DCF, 0xABD13D59, 0x26D930AC, 0x51DE003A, 0xC8D75180, 0xBFD06116, 0x21B4F4B5,
        0x56B3C423, 0xCFBA9599, 0xB8BDA50F, 0x2802B89E, 0x5F058808, 0xC60CD9B2, 0xB10BE924, 0x2F6F7C87, 0x58684C11,
        0xC1611DAB, 0xB6662D3D, 0x76DC4190, 0x01DB7106, 0x98D220BC, 0xEFD5102A, 0x71B18589, 0x06B6B51F, 0x9FBFE4A5,
        0xE8B8D433, 0x7807C9A2, 0x0F00F934, 0x9609A88E, 0xE10E9818, 0x7F6A0DBB, 0x086D3D2D, 0x91646C97, 0xE6635C01,
        0x6B6B51F4, 0x1C6C6162, 0x856530D8, 0xF262004E, 0x6C0695ED, 0x1B01A57B, 0x8208F4C1, 0xF50FC457, 0x65B0D9C6,
        0x12B7E950, 0x8BBEB8EA, 0xFCB9887C, 0x62DD1DDF, 0x15DA2D49, 0x8CD37CF3, 0xFBD44C65, 0x4DB26158, 0x3AB551CE,
        0xA3BC0074, 0xD4BB30E2, 0x4ADFA541, 0x3DD895D7, 0xA4D1C46D, 0xD3D6F4FB, 0x4369E96A, 0x346ED9FC, 0xAD678846,
        0xDA60B8D0, 0x44042D73, 0x33031DE5, 0xAA0A4C5F, 0xDD0D7CC9, 0x5005713C, 0x270241AA, 0xBE0B1010, 0xC90C2086,
        0x5768B525, 0x206F85B3, 0xB966D409, 0xCE61E49F, 0x5EDEF90E, 0x29D9C998, 0xB0D09822, 0xC7D7A8B4, 0x59B33D17,
        0x2EB40D81, 0xB7BD5C3B, 0xC0BA6CAD, 0xEDB88320, 0x9ABFB3B6, 0x03B6E20C, 0x74B1D29A, 0xEAD54739, 0x9DD277AF,
        0x04DB2615, 0x73DC1683, 0xE3630B12, 0x94643B84, 0x0D6D6A3E, 0x7A6A5AA8, 0xE40ECF0B, 0x9309FF9D, 0x0A00AE27,
        0x7D079EB1, 0xF00F9344, 0x8708A3D2, 0x1E01F268, 0x6906C2FE, 0xF762575D, 0x806567CB, 0x196C3671, 0x6E6B06E7,
        0xFED41B76, 0x89D32BE0, 0x10DA7A5A, 0x67DD4ACC, 0xF9B9DF6F, 0x8EBEEFF9, 0x17B7BE43, 0x60B08ED5, 0xD6D6A3E8,
        0xA1D1937E, 0x38D8C2C4, 0x4FDFF252, 0xD1BB67F1, 0xA6BC5767, 0x3FB506DD, 0x48B2364B, 0xD80D2BDA, 0xAF0A1B4C,
        0x36034AF6, 0x41047A60, 0xDF60EFC3, 0xA867DF55, 0x316E8EEF, 0x4669BE79, 0xCB61B38C, 0xBC66831A, 0x256FD2A0,
        0x5268E236, 0xCC0C7795, 0xBB0B4703, 0x220216B9, 0x5505262F, 0xC5BA3BBE, 0xB2BD0B28, 0x2BB45A92, 0x5CB36A04,
        0xC2D7FFA7, 0xB5D0CF31, 0x2CD99E8B, 0x5BDEAE1D, 0x9B64C2B0, 0xEC63F226, 0x756AA39C, 0x026D930A, 0x9C0906A9,
        0xEB0E363F, 0x72076785, 0x05005713, 0x95BF4A82, 0xE2B87A14, 0x7BB12BAE, 0x0CB61B38, 0x92D28E9B, 0xE5D5BE0D,
        0x7CDCEFB7, 0x0BDBDF21, 0x86D3D2D4, 0xF1D4E242, 0x68DDB3F8, 0x1FDA836E, 0x81BE16CD, 0xF6B9265B, 0x6FB077E1,
        0x18B74777, 0x88085AE6, 0xFF0F6A70, 0x66063BCA, 0x11010B5C, 0x8F659EFF, 0xF862AE69, 0x616BFFD3, 0x166CCF45,
        0xA00AE278, 0xD70DD2EE, 0x4E048354, 0x3903B3C2, 0xA7672661, 0xD06016F7, 0x4969474D, 0x3E6E77DB, 0xAED16A4A,
        0xD9D65ADC, 0x40DF0B66, 0x37D83BF0, 0xA9BCAE53, 0xDEBB9EC5, 0x47B2CF7F, 0x30B5FFE9, 0xBDBDF21C, 0xCABAC28A,
        0x53B39330, 0x24B4A3A6, 0xBAD03605, 0xCDD70693, 0x54DE5729, 0x23D967BF, 0xB3667A2E, 0xC4614AB8, 0x5D681B02,
        0x2A6F2B94, 0xB40BBE37, 0xC30C8EA1, 0x5A05DF1B, 0x2D02EF8D ];
    var len = str.length;
    var r = 0xffffffff;
    for (var i = 0; i < len; i++) {
        r = (r >> 8) ^ CRCTable[str[i] ^ (r & 0x000000FF)];
    }
    return ~r;
} }
var fc = new formControllers();
$(document).ready(function () {
    $(function () {
<?php // ####### product edit on click binding here in jquery. ?>
        fc.anchorByPost("a[data-method='post']");

        $('.dTable tbody, .dTable_ajax tbody').on('click', 'tr', function () {
            fc.anchorByPost("a[data-method='post']");
        } );

        $(".dTable tbody, .dTable_ajax tbody").on( "click", "a[data-method='post']", function() {

            var form_action = "";
            if ($(this).attr("data-action") !== undefined) {
                form_action = $(this).attr("data-action");
            }
            if ($(this).attr("data-window") !== undefined) {
                var formBody = '<form action="' + form_action + '" method="POST" target="_blank">';
            } else{
                var formBody = '<form action="' + form_action + '" method="POST">';
            }
            var href = $(this).attr("href");
            href = href.replace("?", "");
            var str_array = href.split('&');
            for (var i = 0; i < str_array.length; i++) {
                str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
                var x2str = str_array[i];
                var str_array_2 = x2str.split('=');
                for (var i2 = 0; i2 < str_array_2.length; i2 = i2 + 2) {
                    formBody += '<input type="hidden" name="' + str_array_2[i2] + '" value="' + str_array_2[(i2 + 1)] + '" />';
                }
            }
            formBody += '</form>';
            var $form = $(formBody).appendTo('body');
            if ($(this).attr("data-confirmation") !== undefined) {
                var data_confirmation = $(this).attr("data-confirmation");
                var msg_ = $fc.errorMsg(data_confirmation);
                var conformation = confirm(msg_);
                if (conformation == true) {
                    $form.submit();
                }
            } else {
                $form.submit();
            }
            return false;
        });

    });
});
function color_picker() {
    $('.color_picker').ColorPicker({
        onSubmit: function (hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).css({ borderColor: '#' + hex, borderWidth: "3px"   });
        },

        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    }).bind('keyup', function () {
            $(this).ColorPickerSetColor(this.value);
    });
}


$(function(){
    $(document).ajaxError(function(event, request, settings) {
        console.log("ERR1");
        console.log(event);

        console.log("ERR2");
        console.log(request);

        console.log("ERR3");
        console.log(settings);


    });
});



/*
*
*
*
*
*
*
*
*
*
*
 */
/*******************************************************/
/*******************************************************/


function secure_delete(text){
    // text = 'view on alert';
    text = typeof text !== 'undefined' ? text : 'Are you sure you want to Delete?';

    bool=confirm(text);
    if(bool==false){return false;}else{return true;}

}


function remove_tr(ths,time){
    // remove_tr(this,time for wait);
    if(typeof ths == 'undefined')return false;

    time = typeof text !== 'undefined' ? time : 500;
    remv=time+500;
    $(ths).closest("tr").hide(time);

    setTimeout(function(){
        $(ths).closest("tr").remove();
    },remv);

}


function loading_progress(){
    //work with bootstrap.
    return '<div class="progress"><div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="r-only">Loading...</span></div></div>';
}


// datatable ..

$(document).ready(function() {
<?php
global $adminPanelLanguage,$functions,$_e,$dbF;
 $_w = array();
 $_w['Display _MENU_ records per page'] = '';
 $_w['Nothing found - sorry'] = '';
 $_w['Showing page _PAGE_ of _PAGES_'] = '';
 $_w['No records available'] = '';
 $_w['(filtered from _MAX_ total records)'] = '';
 $_e = $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Js');
 //var_dump($_e);
 $dataTable_language = '"language": {
                           "lengthMenu": "'.$_e['Display _MENU_ records per page'].'",
                           "zeroRecords": "'. $_e['Nothing found - sorry'].'",
                           "info": "'.$_e['Showing page _PAGE_ of _PAGES_'].'",
                           "infoEmpty": "'.$_e['No records available'].'",
                           "infoFiltered": "'.$_e['(filtered from _MAX_ total records)'].'"
                       },';
 $oTableTools        = '"oTableTools": {
                            "sSwfPath": "assets/data_table_bs/swf/copy_csv_xls_pdf.swf",
                            "aButtons": [
                                {
                                    "sExtends":    "collection",
                                    "sButtonText": "Save",
                                    "aButtons": [
                                        {
                                            "sExtends": "copy",
                                            "oSelectorOpts": { filter: "applied", order: "current" },
                                            "mColumns": "visible"
                                        },
                                        {
                                            "sExtends": "csv",
                                            "mColumns": "visible"
                                        },
                                        {
                                            "sExtends": "xls",
                                            "mColumns": "visible"
                                        },
                                        {
                                            "sExtends": "pdf",
                                            "mColumns": "visible"
                                        }
                                    ]
                                }

                            ]
                        },';
$aLengthMenu = 'aaSorting: [],
                aLengthMenu: [
                    [10,25, 50, 100, 200, -1],
                    [10,25, 50, 100, 200, "All"]
                ],';
$aLengthMenu = 'aaSorting: [],';
?>

    var dTableFull = $('.dTableFull').DataTable({
        <?php echo $aLengthMenu; ?>
        "sDom": 'T C<"clearfix">lfrtip',
        <?php echo $oTableTools; ?>
        <?php echo $dataTable_language; ?>
        stateSave: true
    });



 //show column, export, sort, disply none column 2,3, sate save true
   table = $('.dTable').DataTable( {
       <?php echo $aLengthMenu; ?>
        "dom": 'Bfrtip',
        "colReorder": true,
        "fixedHeader": true,
        "columnDefs": [
            {
                "targets": [ 2 ],
                "visible": true
            },
            {
                "targets": [ 3 ],
                "visible": true
            }
        ],
        <?php echo $oTableTools; ?>
        <?php echo $dataTable_language; ?>
        "bJQueryUI": false,
        "bAutoWidth": false,
        "stateSave": true,
        "buttons": [ 'pageLength', 'copy', 'print','excel'
        , 
        // {
        //         extend: 'excelHtml5',
        //         text : 'Excel',
        //         filename: function(){
        //         var d = new Date();
        //         var n = d.getDate();
        //          var nn = d.getFullYear();
        //         return 'date_' + n + '_year_'+ nn;
        //     }
        //     },

             'colvis' ],
    });

    dTableT = function(){
        $('.dTableT').DataTable( {
            <?php echo $aLengthMenu; ?>
            "sDom": 'T<"clearfix">lfrtip',
            <?php echo $oTableTools; ?>
            <?php echo $dataTable_language; ?>
            stateSave: true
        });
    };

    dateCodeFrom   = ( typeof dateCodeFrom === 'undefined' ) ? console.log('dateCodeFrom is undefined') : dateCodeFrom;
    dateCodeTo     = ( typeof dateCodeTo === 'undefined' )   ? console.log('dateCodeTo is undefined') : dateCodeTo;

    $('.dTable_ajax').each(function(){
        url  = $(this).attr("data-href");
        uniq = $(this).attr("data-uniq");
        <?php #### ////// columnDefs is used in columnDefs of dataTable. ?>
        var columnDefs;
        cols    = ( 'undefined' === typeof $(this).attr("data-cols") )    ? false : $(this).attr("data-cols");
        sorting = ( 'undefined' === typeof $(this).attr("data-sorting") ) ? false : $(this).attr("data-sorting");
        <?php # add uniq as $_GET param on the current link ?>
        url = url + "&uniq="+ uniq;

        var columnDefsSixColumns = [
                {
                    "targets": [0],
                    "orderable": false
                },
                {
                    "targets": [1],
                    "orderable": false
                },
                {
                    "targets": [2],
                    "visible": false, 
                    "orderable": false
                },
                {
                    "targets": [3],
                    "visible": false, 
                    "orderable": false
                },
                {
                    "targets": [4],
                    "orderable": false
                },
                {
                    "targets": [5],
                    "orderable": false
                },
                
                
                
                {
                    "targets": [-1], // targets last column, use 0 for first column
                    className: 'tableBgGray custom_width'
                }
            ];


        var columnDefsSevenColumns = [
                {
                    "targets": [0],
                    "orderable": true
                },
                {
                    "targets": [1],
                    "orderable": true
                },
                {
                    "targets": [2],
                    "visible": false, 
                    "orderable": true
                },
                {
                    "targets": [3],
                    "visible": false, 
                    "orderable": true
                },
                {
                    "targets": [4],
                    "orderable": true
                },
                {
                    "targets": [5],
                    "orderable": true
                },
                {
                    "targets": [6],
                    "orderable": true
                },



                {
                    "targets": [-1], // targets last column, use 0 for first column
                    "orderable": false,
                    className: 'tableBgGray custom_width'
                }
            ];

        console.info(cols);
        columnDefs = cols == 6 ? columnDefsSixColumns : columnDefsSevenColumns;
        console.info(columnDefs);

        bSortValue = sorting == false ? false : true;
        console.info("bSortValue: "+bSortValue);

        OrdersTable = $(this).DataTable({
            <?php echo $aLengthMenu; ?>
            // Show Entries Values
            "lengthMenu": [ 5, 10, 25, 50, 100, 250, 500, 1000, 5000 ],
            "sDom": 'T C<"clearfix">lfrtip',
            "bSort": bSortValue, // this will disable sorting all around, "orderable" inside "columnDefs" won't work 
            "columnDefs": columnDefs,
            <?php echo $oTableTools; ?>
            <?php echo $dataTable_language; ?>
            "bJQueryUI": false,
            "bAutoWidth": false,

            "bProcessing": true,
            "bServerSide": true,
            // Show AJAX Loading text or image
            oLanguage: {
                sProcessing: "<img src='throbber-dark.gif'>"
            },

            // Show Entries Selected Length
            "iDisplayLength": 10,
            ajax: {
                url: url,
                type: 'POST',
                data: function (d){
                    d.dateCodeFrom = dateCodeFrom;
                    d.dateCodeTo   = dateCodeTo;
                }
            }
        });

        OrdersTable.on( 'xhr', function () {
            console.log( 'Table load completed' );
            if ( 'function' == typeof window.orderPrice ) {
               orderPrice();
            } else {
               // do something else
               console.log('Function does not exist!');
            }
            
        } );
        console.log(OrdersTable);
    });

    /*
    Previous was button is this style
     "aButtons": [
          {
         "sExtends": "copy",
         "oSelectorOpts": { filter: 'applied', order: 'current' },
         "mColumns": "visible"
         },
         {
         "sExtends": "csv",
         "mColumns": "visible"
         },
         {
         "sExtends": "xls",
         "mColumns": "visible"
         },
         {
         "sExtends": "pdf",
         "mColumns": "visible"
         }

     ]*/

    //Column hide view not show,, just search and Export



    dTableRangeSearch =function(){$.fn.dataTable.ext.search.push(
        // search by date range
        function( settings, data, dataIndex ) {
            var min = ( $('#min').val());
            var max = ( $('#max').val() );
            if(max==""){
                max="99999999999999";
            }
            var age = ( data[3] ) || 0; // use data for the age column
            min = min.replace(/:/g, "");
            min = min.replace(/-/g, "");
            min = min.replace(" " ,"");
            i=min.length;
            for(i;i<14;i++){
                min+="0";

            }

            max = max.replace(/:/g, "");
            max = max.replace(/-/g, "");
            max = max.replace(" " ,"");
            i=max.length;
            j=0;
            if(i>=8){
                j=1;
            }
            for(i;i<14;i++){
                if(j==1){
                    max+="9";
                }else{
                    max+="0";
                }
            }
            age = age.replace(/:/g, "");
            age = age.replace(/-/g, "");
            age = age.replace(" " ,"");
            i=age.length;
            for(i;i<14;i++){
                age+="0";

            }

            if ( ( isNaN( min ) && isNaN( max ) ) ||
                ( isNaN( min ) && age <= max ) ||
                ( min <= age   && isNaN( max ) ) ||
                ( min <= age   && age <= max ) )
            {
                return true;
            }
            return false;
        }
    );

        $('#min, #max').change( function() {
            table.ajax.reload(null, false).draw();
        });
    };



    tabActiveByHash();
});


minMaxDate = function() {
    $( "#min" ).datepicker({
        changeMonth: true,
        numberOfMonths: 2,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate ) {
            $( "#max" ).datepicker( "option", "minDate", selectedDate);
        }
    });

    $( "#max" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate ) {
            $( "#min" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
};

tableHoverClasses=(function(){
    $(".table").on("mouseover","tr",function () {
        $(".btn-group .btn").removeClass('btn-primary');
        $(".btn-group .btn", this).addClass('btn-primary');
    });
    $(".table").on("mouseleave",function () {
        $(".btn-group .btn").removeClass('btn-primary');
    });
});


dateJqueryUi=(function(){
    $( ".date" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
});



jAlertifyAlert = function(title){
    alertify.alert(title);
}

/**
 *
 * @param val1 HTML
 * @param title title
 */
jAlert = function(val1,title){
    /* work with this code, written in footer
     $( "#jAlert" ).dialog({
     modal: true,
     autoOpen:false,
     buttons: {
     "Close": function() {
     $( this ).dialog( "close" );
     }
     }
     });
     <div id="jAlert" style="display: none;"></div> */
    $("#jAlert").html(val1);
    $("#jAlert").attr("title",title);
    $("#jAlert").parent().find('.ui-dialog-title').text(title);
    $( "#jAlert" ).dialog('open');
};


//use for count animation
var countAnimation = function(obj,from,to,textVal){
    //obj = $('#text');
    from  = parseFloat(from);
    to    = parseFloat(to);

    if(from<to){
        temp = eval(to-from);
        val2  = parseInt(eval(temp/1.1));
        from  = eval(from+val2);
        countIncrease(obj,from,to,textVal);
    }
    if(from>to){
        temp = eval(from-to);
        val2  = parseInt(eval(temp/1.1));
        from  = eval(from-val2);
        countDecrease(obj,from,to,textVal);
    }
}

var countIncrease=function(obj,from,to,textVal){
    i=from;
    if(i<=to){
        if(textVal=='val')
            obj.val(i++);
        else
            obj.text(i++);
        setTimeout(function(){
            countIncrease(obj,i,to);
        },5);
    }
};
var countDecrease=function(obj,from,to,textVal){
    i=from;
    if(i >= to){
        if(textVal=='val')
            obj.val(i--);
        else
            obj.text(i--);
        setTimeout(function(){
            countDecrease(obj,i,to);
        },5);
    }
};

$(document).ready(function(){

    $(document).on('click','.notification_close',function(){
        ths= $(this).closest('.notification');
        ths.stop().slideUp(800,function(){
            ths.remove();
        });
    });

    notificationAutoRemove = function(uniqueId,seconds){
        ths= $("#noti_"+uniqueId);
        time = eval(seconds*1000);

        if(time>=3000){
            setTimeout(function(){
                $('.notification').stop().animate({
                    'opacity':'0.5'
                },1500);
            },3000);
        }
        /*
         //unique id auto remove..
         setTimeout(function(){
         ths.animate({
         'opacity':'1'
         },500);
         },time);

         setTimeout(function(){
         ths.hide(500,function(){
         ths.remove();
         });
         },time+1200);
         */
    };

//use for count animation End
    /********************************/

    notification = function(heading,text,clas){
        var uniqueNum = Math.floor( Math.random()*99999 );
        notifi = "<div  id='noti_"+uniqueNum+"' class='notification'>" +
        " <div class='notification_close btn btn-default'>x</div>" +
            "<div class='notification_heading navbar-inverse'>" +
            ""+heading+""+
            "</div>"+
            "<div class='notification_text "+clas+"'>" +
            ""+text+"</div>"+
        "</div>";

        $('.notifications').prepend(notifi);
        $('#noti_'+uniqueNum).hide().fadeIn('slow');
        notificationAutoRemove(uniqueNum,10);
    };

 /* notification('heading work','textwork','btn-primary');
    notification('heading work','textwork','btn-info');
    notification('heading work','textwork','btn-success');
    notification('heading work','textwork','btn-warning');
    notification('heading work','textwork','btn-danger');*/


    $('.topViewP .topViewClose,.topViewP .topViewCloseX').click(function(){
        $('.topViewP').fadeOut(500);
    });

    /**
     *  Update all .convertNumber value to float value from page, and remove characters from value when this function call.
     */

    dateRangePicker = function() {
        $( ".from,#from" ).datepicker({
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: "yy-mm-dd",
            onClose: function( selectedDate ) {
                $( ".to,#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( ".to,#to" ).datepicker({
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: "yy-mm-dd",
            onClose: function( selectedDate ) {
                $( ".from,#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    };


    dateRangePickerTwo = function() {
        $( "#min" ).datepicker({
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: "yy-mm-dd",
            onClose: function( selectedDate ) {
                $( "#max" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#max" ).datepicker({
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: "yy-mm-dd",
            onClose: function( selectedDate ) {
                $( "#min" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    };    


});


function convertNumber(){
    // If user enter 1kg , so this function change val 1kg to 1
    $('.convertNumber').each(function(){
        temp = parseFloat($(this).val());
        $(this).val(temp);
    });
}




///Bootstrap tab open with hash in link

tabActiveByHash = function(){
    if (location.hash !== '') $('.nav a[href="' + location.hash + '"]').tab('show');
}
/* // Tab change , change link url hash
$('a[data-toggle="tab"]').click(function(e) {
    location.hash = e.target.hash.substr(1) ;
});*/



function openKCFinderImage(target) {
    window.KCFinder = {
        callBack: function(url) {
            target.val(url);
            window.KCFinder = null;
        }
    };
    window.open('editor/kcfinder/browse.php?type=images', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
            'resizable=1, scrollbars=0, width=800, height=600'
    );
}

function openKCFinderImageWithImg(className) {
    //required class target

    window.KCFinder = {
        callBack: function(url) {
            $('input.'+className).val(url);
           $('img.'+className).attr("src",url);
            window.KCFinder = null;
        }
   };

    window.open('editor/kcfinder/browse.php?type=images', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}

function openKCFinderMultiImages(textareaId) {

    window.KCFinder = {
        callBackMultiple: function(files) {
            window.KCFinder = null;
            $("#"+textareaId).val("");
            var  filesT= "";
            for (var i = 0; i < files.length; i++) {
                filesT += "<img src='" +files[i] + "' class='img-responsive'/>";
            }
            CKEDITOR.instances[textareaId].setData(filesT);
        }

    };

    window.open('editor/kcfinder/browse.php?type=images&dir=images/feature',
        'kcfinder_multiple', 'status=0, toolbar=0, location=0, menubar=0, ' +
        'directories=0, resizable=1, scrollbars=0, width=800, height=600'
    );
}



function openKCFinderFile(target) {
    window.KCFinder = {
        callBack: function(url) {
            target.val(url);
            window.KCFinder = null;

        }
    };

    window.open('editor/kcfinder/browse.php?type=files', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}


function ourLazyImages(){
    $('img.myLazy').each(function(){
        //Use <img data-original="imgUrl" src="default.png" />
        var imageSrc = $(this).attr("data-original");
        $(this).attr("src", imageSrc).removeAttr("data-original");
    });
}




<?php
if(1===2){ //
?>
</script>

<?php }
// ?>