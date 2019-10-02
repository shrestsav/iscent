<?php 
ob_start(); 

require_once("classes/order.php");
$order = new order();

$schedules = $order->getAllSchedules();

?>
<h4 class="sub_heading"><?php echo _uc($_e['Order Create/View']); ?></h4>

<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Schedule Calendar']); ?></a></li>
</ul>

<div class="tab-pane fade in active container-fluid" id="home">
    <div class="heading_invoice">
        <h2 class="tab_heading"><?php //echo _uc($_e['Schedule Calendar']); ?></h2>
    </div>
    <div id="calendar"></div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $('#calendar').fullCalendar({
          events: <?php echo $schedules; ?>,
          eventRender: function (event, element) {
                element.find('.fc-title').html(event.title);
            },
          eventClick: function(event) {
            if (event.url) {
              window.open(event.url);
              return false;
            }
          },
            displayEventTime: false
        });
    });    
</script>


<style type="text/css">

@media print{
    a[href]:after{content:none}
    .IBMS_Main_Menu,
    .main_heading,
    .sub_heading,
    .fc-right,
    .fc-center,
    .nav,
    .btn-group,.ColVis,.dataTables_length,.dataTables_filter,.dataTables_paginate,.dataTables_info,
    #footer,#sortByDate,
    form[method="get"]
    {
        display:none;
    }
    #calendar *{

        width: 100%
    }
     #container_div{
        width:100% !important;
    }
}

</style>

<?php return ob_get_clean(); ?>