<?php include_once("global.php");

global $webClass;

$login = $webClass->userLoginCheck();

$loginForOrder = $functions->developer_setting('loginForOrder');

if (!$login && $loginForOrder != '0') {

    header("Location: login.php");

    exit;

}

$userId = $webClass->webUserId();

if ($userId == '0') {

    $userId = webTempUserId(); // for all orders on temp user..

}



// include("header.php");

require_once(__DIR__ . '/' . ADMIN_FOLDER . '/order/classes/order.php');

$orderC = new order();

?>
<!-- <div class="bg_inner" style="background-image: url(<?php echo WEB_URL.'/images/default_banner.jpg' ?>)"></div> -->

	<div class="padding-0 inner_details_container">
        <?php //$dbF->prnt($_REQUEST); ?>
        <div class="standard">



            <div class="inner_content_page_div container-fluid">

            	<?php

                    //list of all schedules

                    $orderC->allSchedules($userId);

                    echo "<br><hr><br>";

                ?>

            </div>

        </div>

        <div id="technical_form"></div>
    </div>

<script type="text/javascript">
	$('.view_technical').on('click', function(){
        tech_id = $(this).data('id');

        $.ajax({
            url: 'ajax_call.php?page=viewTechnicalForm',
            type: 'post',
            data: {tech_id:tech_id}
        }).done(function(res){
        	console.log(res);
            $('#technical_form').html(res);
            $('#technicalFormModal').modal('show');
        });
    });


	$('#confirm_technical').on('click', function(){
        tech_id = $(this).data('id');

        $.ajax({
            url: 'ajax_call.php?page=confirmTechnicalForm',
            type: 'post',
            data: {tech_id:tech_id}
        }).done(function(res){
        	console.log(res);
        	if(res == '1'){
        		alert('Technical Form Confirmed Successfully.');
        	}else{
        		alert('Something Went Wrong! Please Try Again');
        	}
        });
    });
function goBack() {
    window.history.back();
}
</script>



<style>

/*.home_links_heading {

    min-height: 40px;

    text-transform: uppercase;

    width: 100%;

    text-align: center;

    color: #000;

    font-size: 22px;

    font-family: 'ralewayextrabold';

    margin-bottom: 20px;

}*/

.inner_content_page_div {

    display: inline-block;

    width: 100%;

    padding-bottom: 10px;

    min-height: 300px;

}

.tableIBMS {

    border-spacing: 2px;

    border-collapse: separate;

    font-size: 13px;

    width: 100%;

}

.t_head {

    color: #fff;

    border-radius: 5px;

    background-color: #222;

    vertical-align: middle;

    display: inline-block;

    padding: 5px;

    text-align: center;

}

.gray-tr {

    margin-top: 7px;

}

.col_black {

    color: #000;

    text-shadow: 0px 0px 0px #000;

}

.margin-right {

    margin-right: 35px;

}

.t_desc {

    background-color: #f5f5f5;

    border: 1px solid #aaa;

    border-radius: 5px;

    text-align: center;

    padding: 5px;

    border-top: 1px solid #ddd;

}

.tableIBMS th {

    color: #fff;

    border-radius: 5px;

    background-color: #222 !important;

    text-align: center;

    vertical-align: middle !important;

}

.tableIBMS td {

    position: relative;

}

.tableIBMS td {

    border: 1px solid #aaa;

    border-radius: 5px;

    text-align: center;

    vertical-align: middle !important;

}

.padding-20 {

    padding: 20px 0;

}

.d_t_c {

    display: table-cell;

    float: none;

    vertical-align: middle;

}

.d_t {

    display: table;

}

.container-fluid {
	padding-right: 0px;
    padding-left: 0px;
}


/* Technical Form CSS */

#schedule_form_modalTitle{
    text-align: center;
 }

#technicalFormTitle{
    text-align: center;
}

.the-legend {
    border-style: none;
    border-width: 0;
    font-size: 14px;
    line-height: 20px;
    margin-bottom: 0;
    width: auto;
    padding: 0 10px;
    border: 1px solid #e0e0e0;
}
.the-fieldset {
    border: 1px solid #e0e0e0;
    padding: 10px;
}

.marginBot{
    margin-bottom: 10px;
}

</style>

<?php //include("footer.php"); ?>