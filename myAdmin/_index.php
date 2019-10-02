<?php
include_once(__DIR__."/global.php");
//Encrypt After here

global $menu;
$menu="Dashboard";
include_once("header.php");

include_once("dashboard/dashboard.class.php");
$dashboard = new dashboard();
$hasProduct = $functions->developer_setting('product');
$hasCartSystem = $functions->developer_setting('cartSystem');
@$dashboard_graphs = unserialize($functions->ibms_setting('dashboard_graphs'));
?>

    <div class="container-fluid">
        <div class="IBMS_LOGO col-sm-12 text-center">
            <div style="margin-top: 30px;display: inline-block;">
                <div style="display: inline-block; vertical-align: middle;float: left;margin-right: 10px;">
                    <img src="images/logo_ibms.png" width="120"/>
                </div>

                <div style="font-size: 30px;float: left;display: inline-block;">
                    IBMS
                    <div style="display: inline-block; position: relative; vertical-align: middle;
                            font-size: 12px; text-align: left; border-left: solid #5f5f5f 1px;
                            padding-left: 5px;  margin-left: -5px; ">
                        Interactive
                        Business<br>
                        Management
                        System
                    </div>
                </div>
                <div style="font-size: 25px;">
                    (VERSION <?php echo $functions->IBMSVersion; ?>)
                </div>
            </div>
        </div><!--IBMS logo END-->

        <h2 class="sub_heading borderIfNotabs" style="color:#000"> <?php echo $_e['DASHBOARD']; ?> <small><?php echo $_e['This is place where everything started']; ?></small></h2>

        <div class="col-sm-12 graphs padding-0">
            <div class="col-md-8 BigGraphs">

                <!--stock_big_graph : show no of products in which store-->
                <?php

                if($hasProduct == '1' && $functions->developer_setting('stock_big_graph')=='1' && @$dashboard_graphs['stock_big_graph'] == '1'){ ?>
                    <div class="StoreGraph btn-default col-sm-12">
                        <h2><?php echo $_e['Updated Stock Graph'];?></h2>
                        <div id="storeGraph" class="graphDiv">
                            <?php //$dashboard->storeGraph(); ?>
                            <script>
                                $.get('graphs.php', { get_graph: 'stock_big_graph' }, function(data) {
                                    /*optional stuff to do after success */
                                    console.log('Done!');
                                });
                            </script>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--order_daily_report : show how many order in pending, complete, cancel in which day, show 1 year report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('order_daily_report')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['order_daily_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Daily Order Graph'];?></h2>
                        <div id="order_daily_report" class="">
                            <?php $dashboard->order_daily_report_by_country(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--order_daily_value_report : show how many order in pending, complete, cancel in which day, show 1 year report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('order_daily_value_report')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['order_daily_value_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Daily Order Value Graph'];?></h2>
                        <div id="order_daily_value_report" class="">
                            <?php $dashboard->order_daily_value_report_by_country(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--monthly_order_status : show how many order in pending, complete, cancel in which month, show 1 year report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('order_monthly_report')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['order_monthly_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Monthly Order Graph'];?></h2>
                        <div id="order_monthly_report" class="">
                            <?php $dashboard->order_daily_report_by_country("month"); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--monthly_order_value_status : show how many order in pending, complete, cancel in which month, show 1 year report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('order_monthly_value_report')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['order_monthly_value_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Monthly Order Value Graph'];?></h2>
                        <div id="order_monthly_value_report" class="">
                            <?php $dashboard->order_daily_value_report_by_country("month"); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--monthly_order_status : show how many order in pending, complete, cancel in which month, show 1 year report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('order_yearly_report')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['order_yearly_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Yearly Order Graph'];?></h2>
                        <div id="order_yearly_report" class="">
                            <?php $dashboard->order_daily_report_by_country("year"); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--order_yearly_value_report : show how many order in pending, complete, cancel in which month, show 1 year report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('order_yearly_value_report')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['order_yearly_value_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Yearly Order Value Graph'];?></h2>
                        <div id="order_yearly_value_report" class="">
                            <?php $dashboard->order_daily_value_report_by_country("year"); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


                <!--product_sale_by_store_daily : show how many order in pending, complete, cancel in which day, show daily  report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('product_sale_by_store_daily')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['product_sale_by_store_daily'] == '1'){ ?>
                    <div class="product_sale_by_store_daily btn-default col-sm-12" >
                        <h2><?php echo $_e['Daily Order By Store'];?></h2>
                        <div id="product_sale_by_store_daily" class="">
                            <?php $dashboard->product_sale_by_store(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--product_sale_by_store_monthly : show how many order in pending, complete, cancel in which month, show monthly report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('product_sale_by_store_monthly')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['product_sale_by_store_monthly'] == '1'){ ?>
                    <div class="product_sale_by_store_monthly btn-default col-sm-12" >
                        <h2><?php echo $_e['Monthly Order By Store'];?></h2>
                        <div id="product_sale_by_store_monthly" class="">
                            <?php $dashboard->product_sale_by_store('month'); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--top_coupon_use : show how many order in pending, complete, cancel in which month, show monthly report-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('top_coupon_use')=='1'
                    && $hasCartSystem=='1'  && @$dashboard_graphs['top_coupon_use'] == '1'){ ?>
                    <div class="top_coupon_use btn-default col-sm-12" >
                        <h2><?php echo $_e['Top Coupon Use'];?></h2>
                        <div id="top_coupon_use" class="graphDiv">
                            <?php $dashboard->top_coupon_use(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php }  ?>


                <!--whole_sale_report : show which whole sale offer is active from to expire-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('whole_sale_report')=='1'
                            && @$dashboard_graphs['whole_sale_report'] == '1'){ ?>
                    <div class="whole_sale_report btn-default col-sm-12" >
                        <h2><?php echo $_e['Active Whole Sale Offers'];?></h2>
                        <div id="whole_sale_report" class="">
                            <?php $dashboard->whole_sale_report(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <div class="whole_sale_report btn-default col-sm-12" >

                </div>

            </div><!--big graphs end -->

            <div class="col-md-4 smallGraph padding-0">

                <!--return_Product_from_client : show no of return new form submit-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('return_Product_from_client')=='1'
                            && @$dashboard_graphs['return_Product_from_client'] == '1') { ?>
                    <div class="btn-primary btn col-sm-12" >
                        <h2>
                            <?php
                                $number = $dashboard->get_unread_returnProduct();
                                echo str_replace('{{number}}',$number,$_e['{{number}} New Return Product']);
                            ?>
                        </h2>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--defect_Product_from_client : show no of defect new form submit-->
                <?php if($hasProduct == '1' && $functions->developer_setting('defect_Product_from_client')=='1'
                            && @$dashboard_graphs['defect_Product_from_client'] == '1'){ ?>
                    <div class="btn-danger btn col-sm-12" >
                        <h2><?php $number = $dashboard->get_unread_returnProduct('defect');
                            echo str_replace('{{number}}',$number,$_e['{{number}} New Defect Product']);
                            ?></h2>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>

                <!--total_order_status : show total pending cancel,complete no of orders -->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('total_order_status')=='1'
                            && $hasCartSystem=='1' && @$dashboard_graphs['total_order_status'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Total Order Status'];?></h2>
                        <div id="totalOrderGraph" class="graphDiv">
                            <?php $dashboard->totalOrderGraph(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


                <!--top_order_user : top user by price or by order-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('top_order_user')=='1'
                     && @$dashboard_graphs['top_order_user'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Top Users Order/Price'];?></h2>
                        <div id="top_order_user" class="graphDiv">
                            <?php $dashboard->top_order_user(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


                <!--top_order_user : top user by price or by order-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('top_payment_method')=='1'
                    && $hasCartSystem=='1' && @$dashboard_graphs['top_payment_method'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Top Payment Method'];?></h2>
                        <div id="top_payment_method" class="graphDiv">
                            <?php $dashboard->top_payment_method(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


                <!--no_of_product_report : no of product pending/active/draft or deals-->
                <?php if($hasProduct == '1' &&  $functions->developer_setting('no_of_product_report')=='1'
                        && @$dashboard_graphs['no_of_product_report'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['No Of Products'];?></h2>
                        <div id="no_of_product_report" class="graphDiv">
                            <?php $dashboard->no_of_product_report(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


                <!--subscribe_status : top user by price or by order-->
                <?php if($functions->developer_setting('subscribe_status')=='1'
                    && @$dashboard_graphs['subscribe_status'] == '1'){ ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Subscribe Emails'];?></h2>
                        <div id="subscribe_status" class="graphDiv">
                            <?php $dashboard->subscribe_status(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


                <!--email_sending_status : show email cron running? and pending and success send emails status-->
                <?php

                if($functions->developer_setting('email_sending_status')=='1'
                    && @$dashboard_graphs['email_sending_status'] == '1'){
                    $output = shell_exec('crontab -l');
                    /*              $cron_file = 'SHELL="/usr/local/cpanel/bin/jailshell"
                                                        * * * * * php -q /home/imediaserver/public_html/projects/ecron/cron/cron.php';*/
                    $temp = $functions->get_string_between($output,"\n","php");
                    $running = "glyphicon-remove btn-danger";
                    if(stristr($temp,"*")){
                        $running = "glyphicon-ok btn-success";
                    }
                    //$dbF->prnt(nl2br($cron_file));

                    ?>
                    <div class="orderGraph btn-default col-sm-12" >
                        <h2><?php echo $_e['Email Running'];?>: <i class="glyphicon btn btn-sm <?php echo $running;?>"></i></h2>
                        <div id="email_sending_status" class="graphDiv">
                            <?php $dashboard->email_sending_status(); ?>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                    <div class="clearfix"></div> <br>
                <?php } ?>


            </div><!--small Graph End-->
        </div><!--graphs div end-->

    </div><!-- Container fluid end-->

    <script src="assets/highcharts/js/highstock.js"></script>
    <script src="assets/highcharts/js/exporting.js"></script>

    <script>
        $(function() {
            $( "#orderByCountry,#orderByStore,.jqueryTab" ).tabs();
        });
    </script>
<?php include("footer.php"); ?>