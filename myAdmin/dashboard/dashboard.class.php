<?php

class dashboard extends object_class{
    public $productF;
    public $imageName;
    public function __construct(){
        parent::__construct('3');

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();

        $_w["DASHBOARD"]    =   "";
        $_w['This is place where everything started']="";
        $_w['Updated Stock Graph']='';
        $_w['Order Graph']  =   '';
        $_w['{{number}} New Defect Product'] ='';
        $_w['{{number}} New Return Product'] = '';
        $_w['Total Order Status'] = '';
        $_w['Top Users Order/Price'] = '';
        $_w['Product Stock Report'] = '';
        $_w['Top Payment Method'] = '';
        $_w['Subscribe Emails'] = '';
        $_w['Email Running'] = '';
        $_w['No Of Products'] = '';
        $_w["SNO"]     =   "";
        $_w["SALE"]    =   "";
        $_w["CATEGORY"]    =   "";
        $_w["SALE DATE"]    =   "";
        $_w["Active Whole Sale Offers"]    =   "";
        $_w["All Orders"]    =   "";
        $_w["Daily Order By Store"]    =   "";
        $_w["Monthly Order By Store"]    =   "";
        $_w["Daily Order Graph"]    =   "";
        $_w["Monthly Order Graph"]    =   "";
        $_w["Yearly Order Graph"]    =   "";
        $_w["Daily Order Value Graph"]    =   "";
        $_w["Monthly Order Value Graph"]    =   "";
        $_w["Yearly Order Value Graph"]    =   "";

        $_w["Source"]    =   "";
        $_w["Stock"]    =   "";
        $_w["Product Inventory"]    =   "";
        $_w["Store"]    =   "";
        $_w["Success"]    =   "";
        $_w["Denied"]    =   "";
        $_w["Pending"]    =   "";
        $_w["Cancel"]    =   "";
        $_w["Top Coupon Use"]    =   "";
        $_w["No of Orders"]    =   "";
        $_w["Orders"]    =   "";
        $_w["Top Order Users"]    =   "";
        $_w["No of Orders/Price"]    =   "";
        $_w["Sending Email Status"]    =   "";
        $_w["Subscribe Emails"]    =   "";
        $_w["No Of Emails Active/DeActive"]    =   "";
        $_w["Active"]    =   "";
        $_w["DeActive"]    =   "";
        $_w["Products Status"]    =   "";
        $_w["Products"]    =   "";
        $_w["Draft"]    =   "";
        $_w["Price"]    =   "";
        $_w["Send"]    =   "";
        $_w["Top Coupon Use"]    =   "";
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin DashBoard');

        $this->domain();
    }


    public function storeGraph(){
        global $_e;
        require_once('stock/classes/receipt.php');
        $receipt = new purchase_receipt();

        $sql  = " SELECT qty_store_id,sum(qty_item) AS qty FROM product_inventory group by qty_store_id ";
        $data = $this->dbF->getRows($sql);

        $graph = '';
        foreach($data as $val){
            $storeName = $receipt->StoreNameSQL($val['qty_store_id']);
            $qty    = $val['qty'];
            $graph .= "['$storeName',$qty],";
        }

        ?>
        <script>
            $(function () {
                $('#storeGraph').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: "<?php echo $_e['Product Stock Report']; ?>"
                    },
                    subtitle: {
                        text: '<?php echo $_e['Source']; ?>: <a href="<?php echo WEB_ADMIN_URL; ?>/-stock?page=inventory"><?php echo $_e['Stock']; ?></a>'
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?php echo $_e['Product Inventory']; ?>'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        pointFormat: '<b>{point.y:.0f} </b> Product Available In Stock</b>'
                    },
                    series: [{
                        name: '<?php echo $_e['Store']; ?>',
                        data: [
                            <?php echo $graph;?>
                        ],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            x: 4,
                            y: 10,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif',
                                textShadow: '0 0 3px black'
                            }
                        }
                    }]
                });
            });
        </script>
    <?php }


    public function product_sale_by_store($groupByT='day'){
        //Get All stores Where from order place
        //currently only daily view is working,,
        //just pass parameter day|month|year for different views....


        global $_e;

        $sql        = "SELECT store_pk,store_name FROM store_name ORDER BY store_name ASC";
        $data       = $this->dbF->getRows($sql);
        $rowCount   = $this->dbF->rowCount;
        $i          = 0;
        $tempLi     = '';
        $tempDiv = '';

        foreach($data as $val){
            $i++;
            $store = $val['store_pk'];
            $store_name = $val['store_name'];
            $tempLi .= "<li><a href='#AstoreGraph_{$groupByT}_$store'>$store_name</a></li>";
            $tempDiv .= "<div  id='AstoreGraph_{$groupByT}_$store'  style='padding:0'><div  id='storeGraph_{$groupByT}_$store'  class='graphDiv'>".$this->product_orders_by_store($store,$groupByT)."</div></div>";

        }
        $temp = "<div id='orderByStore' style='padding:0'>
                    <ul>
                        $tempLi
                    </ul>
                        $tempDiv
                </div>";
        echo $temp;
    }

    public function product_orders_by_store($store='',$groupByT='day'){
        //currently only daily view is working,,
        //just pass parameter day|month|year for different views....
        global $_e;
        $pending = '';
        $denied = '';
        $cancel ='';
        $success = '';

        if($groupByT == 'month'){
            $groupBy = ", Month(`invoice_date`)";
        }elseif($groupByT == 'year'){
            $groupBy = "";
        }else{
            $groupByT = "day";
                $groupBy = ", Month(`invoice_date`),DAY(`invoice_date`)";
        }

        if(empty($store)){
            $storeT = '';
        }else{
            $storeT = " WHERE order_invoice_pk IN (SELECT DISTINCT(order_invoice_id) FROM `order_invoice_product` WHERE order_pIds LIKE '%-%-%-$store-%')";
        }

        $sql    = "SELECT count(order_invoice_pk) as cnt,invoice_date,invoice_status FROM order_invoice $storeT
                    GROUP BY invoice_status,Year(`invoice_date`) $groupBy ORDER BY invoice_date ASC";

        $dataPen = $this->dbF->getRows($sql);
        $dates  = array();
        foreach($dataPen as $val){
            $date     = date("Y-m-d",strtotime($val['invoice_date']));
            if($groupByT == 'month'){
                $date     = date("Y-m",strtotime($val['invoice_date']));
            }elseif($groupByT == 'year'){
                $date     = date("Y",strtotime($val['invoice_date']));
            }
            if(isset($dates[$date])){
                continue;
            }
            $dates[$date] = $date;
            $timestamp   = strtotime($val['invoice_date'])*1000;
            $pending    .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'2',$groupByT)."],";
            $denied     .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'1',$groupByT)."],";
            $cancel     .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'0',$groupByT)."],";
            $success    .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'3',$groupByT)."],";
        }

        $pending = trim($pending,',');
        $denied = trim($denied,',');
        $cancel = trim($cancel,',');
        $success = trim($success,',');
        ?>
        <script>
            $(function () {
                $('#storeGraph_<?php echo $groupByT."_".$store;?>').highcharts('StockChart', {

                    chart: {
                        <?php if($groupByT=='day'){ ?>
                        type: 'spline' // column,area,spline,line
                        <?php }else{ ?>
                        type: 'column' // column,area,spline,line
                        <?php } ?>
                    },
                    rangeSelector: {
                        selected: 1
                    },
                    yAxis: {
                        min: 0,
                        labels: {
                            rotation: -20,
                            style: {
                                fontSize: '12px'
                            },
                            formatter: function () {
                                return this.value;
                            }
                        }
                    },
                    tooltip: {
                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br>'
                    },
                    series: [

                        {
                            //color:'rgb(138, 242, 234)',
                            name: '<?php echo $_e['Pending']; ?>',
                            data:[<?php echo $pending; ?>]
                        },
                        {
                            //color:'rgb(223, 217, 127)',
                            name: '<?php echo $_e['Denied']; ?>',
                            data:[<?php echo $denied; ?>]
                        },
                        {
                            //color:'rgb(149, 242, 149)',
                            name: '<?php echo $_e['Success']; ?>',
                            data:[<?php echo $success; ?>]
                        },
                        {
                            // color:'rgb(211, 78, 31)',
                            name: '<?php echo $_e['Cancel']; ?>',
                            data:[<?php echo $cancel; ?>]
                        }
                    ]
                });

            });
        </script>
    <?php }


    public function top_coupon_use(){
        global $_e;
        $coupon ='';

        $orders = '';

        $sql="SELECT count(DISTINCT(order_id)) as cnt,setting_val,rec_dateTime FROM `order_invoice_record`
                WHERE setting_name='coupon' GROUP BY setting_val ORDER BY rec_dateTime DESC LIMIT 0,35";

        $dataTopUsers = $this->dbF->getRows($sql);

        $i =0;
        foreach($dataTopUsers as $val) {
            $i++;
            $coupon .= "'$val[setting_val]$i',";
            $orders .= "$val[cnt],";
        }
        $coupon  = trim($coupon,',');
        $orders = trim($orders,',');

        ?>
        <script>
            $(function () {
                $('#top_coupon_use').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $_e['Top Coupon Use']; ?>'
                    },
                    xAxis: {
                        categories: [
                            <?php echo $coupon; ?>
                        ],
                        labels: {
                            rotation: -75,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    legend: {
                        enabled: false // bottom option show off
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?php echo $_e['No of Orders']; ?>'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: '<?php echo $_e['Orders']; ?>',
                        data: [<?php echo $orders; ?>],
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            format: '{point.y}',
                            y: 10, // 10 pixels down from the top
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }

                    }]
                });
            });
        </script>
    <?php }

    public function order_daily_report_by_country($groupBY='day'){
        global $_e;

        $sql    = "SELECT DISTINCT(shippingCountry) as shippingCountry FROM order_invoice ORDER BY shippingCountry ASC";
        $data   = $this->dbF->getRows($sql);
        $rowCount = $this->dbF->rowCount;
        $i      = 0;
        $tempLi = '';
        $tempDiv = '';
        if($rowCount == '1'){
            $tempDiv .= "<div  id='order_daily_report_'  class='graphDiv'>".$this->order_daily_report('',$groupBY)."</div>";
            echo    $tempDiv;
            return false;
        }else{
            $tempLi .= "<li><a href='#Aorder_daily_report_{$groupBY}_All'>{$_e['All Orders']}</a></li>";
            $tempDiv .= "<div  id='Aorder_daily_report_{$groupBY}_All'  style='padding:0'><div  id='order_daily_report_{$groupBY}_'  class='graphDiv'>".$this->order_daily_report('',$groupBY)."</div></div>";
        }
        foreach($data as $val){
            $i++;
            $country = $val['shippingCountry'];
            $countryName = $this->functions->countryFullName($country);
            $tempLi .= "<li><a href='#Aorder_daily_report_{$groupBY}_$country'>$countryName</a></li>";
            $tempDiv .= "<div  id='Aorder_daily_report_{$groupBY}_$country'  style='padding:0'><div  id='order_daily_report_{$groupBY}_$country'  class='graphDiv'>".$this->order_daily_report($country,$groupBY)."</div></div>";

        }
        $temp = "<div class='jqueryTab' style='padding:0'>
                    <ul>
                        $tempLi
                    </ul>
                        $tempDiv
                </div>";

        echo $temp;
    }

    public function order_daily_value_report_by_country($groupBY='day'){
        global $_e;

        $sql    = "SELECT DISTINCT(shippingCountry) as shippingCountry FROM order_invoice ORDER BY shippingCountry ASC";
        $data   = $this->dbF->getRows($sql);
        $rowCount = $this->dbF->rowCount;
        $i      = 0;
        $tempLi = '';
        $tempDiv = '';
        if($rowCount == '1'){
            $tempDiv .= "<div  id='order_daily_value_report_'  class='graphDiv'>".$this->order_daily_value_report('',$groupBY)."</div>";
            echo    $tempDiv;
            return false;
        }else{
            $tempLi .= "<li><a href='#Aorder_daily_value_report_{$groupBY}_All'>{$_e['All Orders']}</a></li>";
            $tempDiv .= "<div  id='Aorder_daily_value_report_{$groupBY}_All'  style='padding:0'><div  id='order_daily_value_report_{$groupBY}_'  class='graphDiv'>".$this->order_daily_value_report('',$groupBY)."</div></div>";
        }
        foreach($data as $val){
            $i++;
            $country = $val['shippingCountry'];
            $countryName = $this->functions->countryFullName($country);
            $tempLi .= "<li><a href='#Aorder_daily_value_report_{$groupBY}_$country'>$countryName</a></li>";
            $tempDiv .= "<div  id='Aorder_daily_value_report_{$groupBY}_$country'  style='padding:0'><div  id='order_daily_value_report_{$groupBY}_$country'  class='graphDiv'>".$this->order_daily_value_report($country,$groupBY)."</div></div>";

        }
        $temp = "<div class='jqueryTab' style='padding:0'>
                    <ul>
                        $tempLi
                    </ul>
                        $tempDiv
                </div>";

        echo $temp;
    }

    function findInArray($data,$date,$invoice,$groupByT){
        foreach($data as $val){
            $fDate     = date("Y-m-d",strtotime($val['invoice_date']));
            if($groupByT == 'month'){
                $fDate     = date("Y-m",strtotime($val['invoice_date']));
            }elseif($groupByT == 'year'){
                $fDate     = date("Y",strtotime($val['invoice_date']));
            }
            $fInvoice = $val['invoice_status'];
            $count = $val['cnt'];
            $count = round($count,2);
            if($date == $fDate && $fInvoice == $invoice){
                return $count;
            }
        }
        return "0";
    }

    public function order_daily_report($country='',$groupByT='day'){
        global $_e;
        $pending = '';
        $denied = '';
        $cancel ='';
        $success = '';

        if(empty($country)){
            $countryT = '';
        }else{
            $countryT = " WHERE shippingCountry = '$country'";
        }
        if($groupByT == 'month'){
            $groupBy = ", Month(`invoice_date`)";
        }elseif($groupByT == 'year'){
            $groupBy = "";
        }else{
            $groupByT = "day";
            $groupBy = ", Month(`invoice_date`),DAY(`invoice_date`)";
        }

        $sql    = "SELECT count(order_invoice_pk) as cnt,invoice_date,invoice_status FROM order_invoice $countryT
                    GROUP BY invoice_status,Year(`invoice_date`) $groupBy ORDER BY invoice_date ASC";
        $dataPen = $this->dbF->getRows($sql);
        $dates  = array();
        foreach($dataPen as $val){
            $date     = date("Y-m-d",strtotime($val['invoice_date']));
            if($groupByT == 'month'){
                $date     = date("Y-m",strtotime($val['invoice_date']));
            }elseif($groupByT == 'year'){
                $date     = date("Y",strtotime($val['invoice_date']));
            }
            if(isset($dates[$date])){
                continue;
            }
            $dates[$date] = $date;
            $timestamp   = strtotime($val['invoice_date'])*1000;
            $pending    .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'2',$groupByT)."],";
            $denied     .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'1',$groupByT)."],";
            $cancel     .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'0',$groupByT)."],";
            $success    .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'3',$groupByT)."],";
        }

            $pending = trim($pending,',');
            $denied = trim($denied,',');
            $cancel = trim($cancel,',');
            $success = trim($success,',');
        ?>
        <script>
            $(function () {
                    $('#order_daily_report_<?php echo $groupByT."_".$country;?>').highcharts('StockChart', {

                        chart: {
                            <?php if($groupByT=='day'){ ?>
                                type: 'spline' // column,area,spline,line
                            <?php }else{ ?>
                                type: 'column' // column,area,spline,line
                            <?php } ?>
                        },
                        rangeSelector: {
                            selected: 1
                        },
                        yAxis: {
                            min: 0,
                            labels: {
                                rotation: -20,
                                style: {
                                    fontSize: '12px'
                                },
                                formatter: function () {
                                    return this.value;
                                }
                            }
                        },

                        tooltip: {
                            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br>'
                        },
                        series: [

                            {
                                //color:'rgb(138, 242, 234)',
                                name: '<?php echo $_e['Pending']; ?>',
                                data:[<?php echo $pending; ?>]
                            },
                            {
                                //color:'rgb(223, 217, 127)',
                                name: '<?php echo $_e['Denied']; ?>',
                                data:[<?php echo $denied; ?>]
                            },
                            {
                                //color:'rgb(149, 242, 149)',
                                name: '<?php echo $_e['Success']; ?>',
                                data:[<?php echo $success; ?>]
                            },
                            {
                               // color:'rgb(211, 78, 31)',
                                name: '<?php echo $_e['Cancel']; ?>',
                                data:[<?php echo $cancel; ?>]
                            }
                        ]
                    });

            });

        </script>
    <?php
    }

    public function order_daily_value_report($country='',$groupByT='day'){
        global $_e;
        $pending = '';
        $denied = '';
        $cancel ='';
        $success = '';

        if(empty($country)){
            $countryT = '';
        }else{
            $countryT = " WHERE shippingCountry = '$country'";
        }
        if($groupByT == 'month'){
            $groupBy = ", Month(`invoice_date`)";
        }elseif($groupByT == 'year'){
            $groupBy = "";
        }else{
            $groupByT = "day";
            $groupBy = ", Month(`invoice_date`),DAY(`invoice_date`)";
        }

        $sql    = "SELECT SUM(total_price) as cnt,invoice_date,invoice_status FROM order_invoice $countryT
                    GROUP BY invoice_status,Year(`invoice_date`) $groupBy ORDER BY invoice_date ASC";
        $dataPen = $this->dbF->getRows($sql);
        $dates  = array();
        foreach($dataPen as $val){
            $date     = date("Y-m-d",strtotime($val['invoice_date']));
            if($groupByT == 'month'){
                $date     = date("Y-m",strtotime($val['invoice_date']));
            }elseif($groupByT == 'year'){
                $date     = date("Y",strtotime($val['invoice_date']));
            }
            if(isset($dates[$date])){
                continue;
            }
            $dates[$date] = $date;
            $timestamp   = strtotime($val['invoice_date'])*1000;
            $pending    .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'2',$groupByT)."],";
            $denied     .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'1',$groupByT)."],";
            $cancel     .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'0',$groupByT)."],";
            $success    .= "[".$timestamp.",".$this->findInArray($dataPen,$date,'3',$groupByT)."],";
        }

            $pending = trim($pending,',');
            $denied = trim($denied,',');
            $cancel = trim($cancel,',');
            $success = trim($success,',');
        ?>
        <script>
            $(function () {
                    $('#order_daily_value_report_<?php echo $groupByT."_".$country;?>').highcharts('StockChart', {

                        chart: {
                            <?php if($groupByT=='day'){ ?>
                                type: 'spline' // column,area,spline,line
                            <?php }else{ ?>
                                type: 'column' // column,area,spline,line
                            <?php } ?>
                        },
                        rangeSelector: {
                            selected: 1
                        },
                        yAxis: {
                            min: 0,
                            labels: {
                                rotation: -20,
                                style: {
                                    fontSize: '12px'
                                },
                                formatter: function () {
                                    return this.value;
                                }
                            }
                        },

                        tooltip: {
                            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br>'
                        },
                        series: [

                            {
                                //color:'rgb(138, 242, 234)',
                                name: '<?php echo $_e['Pending']; ?>',
                                data:[<?php echo $pending; ?>]
                            },
                            {
                                //color:'rgb(223, 217, 127)',
                                name: '<?php echo $_e['Denied']; ?>',
                                data:[<?php echo $denied; ?>]
                            },
                            {
                                //color:'rgb(149, 242, 149)',
                                name: '<?php echo $_e['Success']; ?>',
                                data:[<?php echo $success; ?>]
                            },
                            {
                               // color:'rgb(211, 78, 31)',
                                name: '<?php echo $_e['Cancel']; ?>',
                                data:[<?php echo $cancel; ?>]
                            }
                        ]
                    });

            });

        </script>
    <?php
    }


    public function  totalOrderGraph(){
        global $_e;
        $pending = '0';
        $denied = '0';
        $cancel ='0';
        $success = '0';

        $sql    = "SELECT count(order_invoice_pk) as cnt,invoice_status FROM order_invoice
                    GROUP BY invoice_status ORDER BY invoice_date ASC";
        $dataPen = $this->dbF->getRows($sql);
        foreach($dataPen as $val){
            $invoiceStatus = $val['invoice_status'];
            if($invoiceStatus == '0'){
                $cancel = $val['cnt'];
            }elseif($invoiceStatus == '1'){
                $denied = $val['cnt'];
            }elseif($invoiceStatus == '2'){
                $pending = $val['cnt'];
            }elseif($invoiceStatus == '3'){
                $success = $val['cnt'];
            }
        }


        ?>
<script>
    $(function () {

        $(document).ready(function () {

            // Build the chart
            $('#totalOrderGraph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.0f} ( {point.percentage:.1f}%)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    type: 'pie',
                    name: '<?php echo $_e['No of Orders']; ?>',
                    data: [
                        ['<?php echo $_e['Pending']; ?>', <?php echo $pending; ?>  ],
                        ['<?php echo $_e['Denied']; ?>',  <?php echo $denied; ?>],
                        {
                            name: 'Process',
                            y: <?php echo $success; ?>,
                            sliced: true,
                            selected: true
                        },
                        ['<?php echo $_e['Cancel']; ?>', <?php echo $cancel; ?>]
                    ]
                }]
            });
        });

    });
</script>
    <?php }

    public function top_order_user(){
        global $_e;
        $users ='';
        $price = '';
        $orders = '';

        $sql = "SELECT count(order_invoice.orderUser) as orders,order_invoice.orderUser,sum(order_invoice.total_price) as price,order_invoice_info.sender_name
                  FROM `order_invoice` JOIN order_invoice_info
                    ON  order_invoice.order_invoice_pk = order_invoice_info.order_invoice_id
                    GROUP BY orderUser ORDER BY price DESC,orders DESC LIMIT 0,10";
        $dataTopUsers = $this->dbF->getRows($sql);

        foreach($dataTopUsers as $val) {
            $users .= "'<a href=\"".WEB_ADMIN_URL."/-webUsers?page=edit&userId=$val[orderUser]\">$val[sender_name]</a>',";
            $price .= "$val[price],";
            $orders .= "$val[orders],";
        }
        $users  = trim($users,',');
        $price  = trim($price,',');
        $orders = trim($orders,',');

        ?>
        <script>
            $(function () {
                $('#top_order_user').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $_e['Top Order Users']; ?>'
                    },
                    xAxis: {
                        categories: [
                            <?php echo $users; ?>
                        ],
                        labels: {
                            rotation: -75,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?php echo $_e['No of Orders/Price']; ?>'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            dataLabels: {
                                enabled: true,
                                rotation: -90,
                                align: 'top',
                                style: {
                                    fontSize: '12px',
                                    fontFamily: 'Verdana, sans-serif',
                                    textShadow: '0 0 3px white'
                                }
                            }
                        }
                    },
                    series: [{
                        name: '<?php echo $_e['Price']; ?>',
                        data: [<?php echo $price; ?>]
                    },{
                        name: '<?php echo $_e['Orders']; ?>',
                        data: [<?php echo $orders; ?>]
                    }]
                });
            });
        </script>
    <?php }

    public function top_payment_method(){
        global $_e;
        $this->functions->require_once_custom('product_functions');
        $productF = new product_function();

        $users ='';
        $price = '';
        $orders = '';

        $sql = "SELECT count(paymentType) as orders,paymentType,sum(order_invoice.total_price) as price
                  FROM `order_invoice`
                    GROUP BY paymentType ORDER BY orders DESC,price DESC";
        $dataTopUsers = $this->dbF->getRows($sql);

        foreach($dataTopUsers as $val) {
            $users .= "'".$productF->paymentArrayFind(($val['paymentType']))."',";
            $price .= "$val[price],";
            $orders .= "$val[orders],";
        }
        $users  = trim($users,',');
        $price  = trim($price,',');
        $orders = trim($orders,',');

        ?>
        <script>
            $(function () {
                $('#top_payment_method').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $_e['Top Payment Method']; ?>'
                    },
                    subtitle: {
                        text: '<?php echo $_e['Source']; ?>: <a href="<?php echo WEB_ADMIN_URL; ?>/-order?page=newOrder"><?php echo $_e['Orders']; ?></a>'
                    },
                    xAxis: {
                        categories: [
                            <?php echo $users; ?>
                        ],
                        labels: {
                            rotation: -20,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?php echo $_e['No of Orders/Price']; ?>'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            dataLabels: {
                                enabled: true,
                                rotation: -90,
                                align: 'top',
                                style: {
                                    fontSize: '12px',
                                    fontFamily: 'Verdana, sans-serif',
                                    textShadow: '0 0 3px white'
                                }
                            }
                        }
                    },
                    series: [{
                        name: '<?php echo $_e['Price']; ?>',
                        data: [<?php echo $price; ?>]

                    },{
                        name: '<?php echo $_e['Orders']; ?>',
                        data: [<?php echo $orders; ?>]

                    }]
                });
            });
        </script>
    <?php }

    public function email_sending_status(){
        global $_e;
        $email  =   '';
        $pending =  '';
        $total  =   '';

        $sql  = "SELECT * FROM email_letter_queue GROUP BY letter_id,grp ORDER BY id DESC ";
        $data =  $this->dbF->getRows($sql);
        if(empty($data)){
            $email  =   '0';
            $pending =  '0';
            $total  =   '0';
            $data   =   array();
        }
        $i = 0;
        foreach($data as $val){
            $i++;
            $email .= "'Email $i',";

            //Pending Count
            $sql    = "SELECT count(id) as cnt FROM email_letter_queue WHERE letter_id = '$val[letter_id]' AND grp = '$val[grp]'";
            $countData = $this->dbF->getRow($sql);
            @$temp = $countData['cnt'];
            if($temp == '0' || empty($temp)){
                continue;
            }
            $pending .= intval($countData['cnt']).",";

            //Subscribe Email Count
            $grp    =   $val['grp'];
            if ($grp == 'all' || $grp == '') {
                $sql = "SELECT count(id) as cnt FROM email_subscribe WHERE verify= '1'";
            } else {
                $sql = "SELECT count(id) as cnt FROM email_subscribe WHERE  verify= '1' AND grp = '$grp'";
            }
            $countData2 = $this->dbF->getRow($sql);
            //$temp = $countData2['cnt'];
            $temp   =   intval($countData2['cnt'])-intval($countData['cnt']);
            $total  .= "$temp,";
        }

        $email  = trim($email,',');
        $pending  = trim($pending,',');
        $total = trim($total,',');
        if(empty($pending)){
            $email  =   '0';
            $pending =  '0';
            $total  =   '0';
        }

        ?>
        <script>
            $(function () {
                $('#email_sending_status').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $_e['Sending Email Status']; ?>'
                    },
                    subtitle: {
                        text: '<?php echo $_e['Source']; ?>: <a href="<?php echo WEB_ADMIN_URL; ?>/-order?page=newOrder"><?php echo $_e['Orders']; ?></a>'
                    },
                    xAxis: {
                        categories: [
                            <?php echo $email; ?>
                        ],
                        labels: {
                            rotation: -20,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><table>'+
                            '<tr><td style="padding:0">Total: </td>' +
                            '<td style="padding:0"><b>{point.total}</b></td></tr>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>' ,
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    textShadow: '0 0 3px black'
                                }
                            }
                        }
                    },
                    series: [{
                        name: '<?php echo $_e['Pending']; ?>',
                        color: '#F41919',
                        data: [<?php echo $pending; ?>]

                    },{
                        name: '<?php echo $_e['Send']; ?>',
                        color: 'rgb(144, 237, 125)',
                        data: [<?php echo $total; ?>]

                    }]
                });
            });
        </script>
    <?php }

    public function subscribe_status(){
        global $_e;
        $grp  =   '';
        $active =  '';
        $deActive  =   '';

        $sql  = "SELECT (SELECT count(verify) FROM email_subscribe as b WHERE verify = '1' AND b.grp=a.grp) as active,count(verify) as total,grp FROM `email_subscribe` as a  GROUP BY grp";
        $data =  $this->dbF->getRows($sql);

        $i = 0;
        foreach($data as $val){
            $grp .= "'$val[grp]',";
            $activeT = intval($val['active']);
            $deActiveT = intval($val['total'])-$activeT;
            if($deActiveT <0) $deActiveT = 0;
            $active .= "$activeT,";
            $deActive .= "$deActiveT,";
        }

        $grp  = trim($grp,',');
        $active  = trim($active,',');
        $deActive = trim($deActive,',');

        ?>
        <script>
            $(function () {
                $('#subscribe_status').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $_e['Subscribe Emails']; ?>'
                    },
                    subtitle: {
                        text: '<?php echo $_e['Source']; ?>: <a href="<?php echo WEB_ADMIN_URL; ?>/-order?page=newOrder"><?php echo $_e['Orders']; ?></a>'
                    },
                    xAxis: {
                        categories: [
                            <?php echo $grp; ?>
                        ],
                        labels: {
                            rotation: -50,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?php echo $_e['No Of Emails Active/DeActive']; ?>'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'black',
                                style: {
                                    textShadow: '0 0 3px black'
                                }
                            }
                        }
                    },
                    series: [{
                        name: '<?php echo $_e['Active']; ?>',
                        color: 'rgb(144, 237, 125)',
                        data: [<?php echo $active; ?>]

                    },{
                        name: '<?php echo $_e['DeActive']; ?>',

                        color: '#F41919',
                        data: [<?php echo $deActive; ?>]

                    }]
                });
            });
        </script>
    <?php }

    public function no_of_product_report(){
        global $_e;
        $sql  = "SELECT
                        (SELECT count(p_id) FROM product_setting as s WHERE setting_name = 'publicAccess' AND setting_val='1') as active,
                        (SELECT count(p_id) FROM product_setting as s WHERE setting_name = 'launchDate' AND setting_val > '05/15/2015') as pending,
                        (SELECT count(p_id) FROM product_setting as s WHERE setting_name = 'publicAccess' AND setting_val = '0') as draft
                FROM `proudct_detail` as a
                  WHERE product_update='1'
                    group by product_update";
        $data =  $this->dbF->getRow($sql);

        $sql  = "SELECT (SELECT count(id) FROM `product_deal` WHERE publish = '1') as active,count(id) as total FROM `product_deal`";
        $data2 =  $this->dbF->getRow($sql);

        $grp     = "'Product','Deal Product'";
        $totalDeal = $data2['total'];
        $activeDeal = $data2['active'];
        $draftDeal = $totalDeal-$activeDeal;
        $active  = "$data[active],$activeDeal";
        $pending = "$data[pending]";
        $draft   = "$data[draft],$draftDeal";

        ?>
        <script>
            $(function () {
                $('#no_of_product_report').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $_e['Products Status']; ?>'
                    },
                    subtitle: {
                        text: '<?php echo $_e['Source']; ?>: <a href="<?php echo WEB_ADMIN_URL; ?>/-product?page=list"><?php echo $_e['Products']; ?></a>'
                    },
                    xAxis: {
                        categories: [
                            <?php echo $grp; ?>
                        ],
                        labels: {
                            rotation: -0,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>' ,
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },

                    plotOptions: {
                        column: {
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'black',
                                style: {
                                    textShadow: '0 0 3px white'
                                }
                            }
                        }
                    },
                    series: [
                        {
                            name: '<?php echo $_e['Active']; ?>',
                            data: [<?php echo $active; ?>]
                        },{
                            name: '<?php echo $_e['Draft']; ?>',
                            data: [<?php echo $draft; ?>]
                        }
                        ,{
                            name: '<?php echo $_e['Pending']; ?>',
                            data: [<?php echo $pending; ?>]
                        }
                    ]
                });
            });
        </script>
    <?php }

    public function whole_sale_report(){
        global $_e;
        $this->functions->require_once_custom('product_functions');
        $productF = new product_function();

        $today  = date('Y-m-d');
        $qry="SELECT * FROM product_sale
                 WHERE pSale_status = '1'
                      AND pSale_from <= '$today'
                      AND (pSale_to  >= '$today' OR pSale_to  = '')
                       ";

        global $_e;
        $data=$this->dbF->getRows($qry);

        echo  '
            <div class="table-responsive">
            <table class="table table-hover table-striped   table-condensed table-bordered ">
                <thead>
                    <tr>
                        <th>'. _uc($_e['SNO']) .'</th>
                        <th>'. _uc($_e['SALE']) .'</th>
                        <th>'. _uc($_e['CATEGORY']) .'</th>
                        <th>'. _uc($_e['SALE DATE']) .'</th>
                    </tr>
                </thead>
                <tbody>';

        $i=0;
        foreach($data as $key=>$val){
            $i++;
            $saleId  =  $val['pSale_pk'];
            $dateFrom=  $val['pSale_from'];
            $dateTo  =  $val['pSale_to'];
            $dateRange  =   "<span class='no-wrap'>$dateFrom-</span> <br><span class='no-wrap'>$dateTo</span>";

            $categoryNames  =   $productF->getCategoryNames($val['pSale_category']);
            echo "
                        <tr class='p_$saleId'>
                            <td>
                                    <label>$i</label>
                            </td>
                            <td>".$val['pSale_name']."</td>
                            <td>".$categoryNames."</td>
                            <td>".$dateRange."</td>
                        </tr>";
        }

        echo '
                </tbody>
            </table>
            </div>';
    }

    public function get_unread_returnProduct($type='return'){
        $sql    =   "SELECT COUNT(id) as cnt FROM product_return_form WHERE type = '$type' AND readStatus ='0'";
        $data   =   $this->dbF->getRow($sql);
        return @$data['cnt'];
    }

    private function domain(){
        $ibms = $this->functions->ibms_setting('IBMS-domain');
        $host =md5($_SERVER['HTTP_HOST']."+Asad");

        if($ibms == ($host)){
            //Good
        }else{
            //send email 2 times then update domain name
            $sendMail = "";
            if(isset($_SESSION['domain'])) {
                $_SESSION['domain'] = 2;

            }
        }
    }


}
?>