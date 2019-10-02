<?php
include_once(__DIR__."/global.php");

include_once("dashboard/dashboard.class.php");
$dashboard = new dashboard();
$hasProduct = $functions->developer_setting('product');
$hasCartSystem = $functions->developer_setting('cartSystem');
@$dashboard_graphs = unserialize($functions->ibms_setting('dashboard_graphs'));

if( $_GET['get_graph'] == 'stock_big_graph'  && $functions->developer_setting('stock_big_graph')=='1' && @$dashboard_graphs['stock_big_graph'] == '1' ){
		$dashboard->storeGraph();
}

if( $_GET['get_graph'] == 'order_daily_report'  && $functions->developer_setting('order_daily_report')=='1' && @$dashboard_graphs['order_daily_report'] == '1' ){
		$dashboard->order_daily_report_by_country();
}

if( $_GET['get_graph'] == 'order_daily_value_report'  && $functions->developer_setting('order_daily_value_report')=='1' && @$dashboard_graphs['order_daily_value_report'] == '1' ){
    	$dashboard->order_daily_value_report_by_country();
}




?>