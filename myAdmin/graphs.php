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

if( $_GET['get_graph'] == 'order_monthly_report'  && $functions->developer_setting('order_monthly_report')=='1' && @$dashboard_graphs['order_monthly_report'] == '1' ){
    	$dashboard->order_daily_report_by_country("month");
}

if( $_GET['get_graph'] == 'order_monthly_value_report'  && $functions->developer_setting('order_monthly_value_report')=='1' && @$dashboard_graphs['order_monthly_value_report'] == '1' ){
    	$dashboard->order_daily_value_report_by_country("month");
}

if( $_GET['get_graph'] == 'order_yearly_report'  && $functions->developer_setting('order_yearly_report')=='1' && @$dashboard_graphs['order_yearly_report'] == '1' ){
    	$dashboard->order_daily_report_by_country("year");
}

if( $_GET['get_graph'] == 'order_yearly_value_report'  && $functions->developer_setting('order_yearly_value_report')=='1' && @$dashboard_graphs['order_yearly_value_report'] == '1' ){
    	$dashboard->order_daily_value_report_by_country("year");
}

if( $_GET['get_graph'] == 'product_sale_by_store_daily'  && $functions->developer_setting('product_sale_by_store_daily')=='1' && @$dashboard_graphs['product_sale_by_store_daily'] == '1' ){
    	$dashboard->product_sale_by_store();
}

if( $_GET['get_graph'] == 'product_sale_by_store_monthly'  && $functions->developer_setting('product_sale_by_store_monthly')=='1' && @$dashboard_graphs['product_sale_by_store_monthly'] == '1' ){
    	$dashboard->product_sale_by_store('month');
}

if( $_GET['get_graph'] == 'top_coupon_use'  && $functions->developer_setting('top_coupon_use')=='1' && @$dashboard_graphs['top_coupon_use'] == '1' ){
    	$dashboard->top_coupon_use();
}

if( $_GET['get_graph'] == 'whole_sale_report'  && $functions->developer_setting('whole_sale_report')=='1' && @$dashboard_graphs['whole_sale_report'] == '1' ){
    	$dashboard->whole_sale_report();
}

if( $_GET['get_graph'] == 'total_order_status'  && $functions->developer_setting('total_order_status')=='1' && @$dashboard_graphs['total_order_status'] == '1' ){
    	$dashboard->totalOrderGraph();
}

if( $_GET['get_graph'] == 'top_order_user'  && $functions->developer_setting('top_order_user')=='1' && @$dashboard_graphs['top_order_user'] == '1' ){
    	$dashboard->top_order_user();
}

if( $_GET['get_graph'] == 'top_payment_method'  && $functions->developer_setting('top_payment_method')=='1' && @$dashboard_graphs['top_payment_method'] == '1' ){
    	$dashboard->top_payment_method();
}

if( $_GET['get_graph'] == 'no_of_product_report'  && $functions->developer_setting('no_of_product_report')=='1' && @$dashboard_graphs['no_of_product_report'] == '1' ){
    	$dashboard->no_of_product_report();
}

if( $_GET['get_graph'] == 'subscribe_status'  && $functions->developer_setting('subscribe_status')=='1' && @$dashboard_graphs['subscribe_status'] == '1' ){
    	$dashboard->subscribe_status();
}

if( $_GET['get_graph'] == 'email_sending_status'  && $functions->developer_setting('email_sending_status')=='1' && @$dashboard_graphs['email_sending_status'] == '1' ){
    	$dashboard->email_sending_status();
}




?>