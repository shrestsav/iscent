<?php
//multi language PENDING
header('Content-type: text/xml');
include("global.php");

global $webClass;
// sitemap

$links      =   array();

$links[]    =   "Main Links";
$links[]    =   array(
    "loc"       => WEB_URL,
    "lastmod"   => date("Y-m-d"),
    "changefreq"=> "weekly",
    "priority"  => "1.0",
    "extLink"  => "?"
);
//Get Pages Links
$sql    = "SELECT slug,dateTime FROM  pages WHERE publish = '1'";
$data   = $dbF->getRows($sql);
foreach($data as $val){
    $link = WEB_URL."/".$db->dataPage.$val['slug'];
    $date = date("Y-m-d",strtotime($val['dateTime']));
    $links[]    =   array(
        "loc"       => $link,
        "lastmod"   => $date,
        "priority"  => "0.8",
        "extLink"  => "&"
    );
}


//get Categories
$links[] = "Main Categories";
category();

//Get Products that are active
$links[] = "Main Products";
$date   =date('m/d/Y');
$sql    ="SELECT prodet_id,prodet_timeStamp,slug
                    FROM
                       `proudct_detail`
                        WHERE
                            prodet_id IN (SELECT p_id FROM `product_setting`
                                            WHERE `setting_name`='publicAccess'
                                                  AND `setting_val`   = '1')
                            AND prodet_id IN (SELECT p_id FROM `product_setting`
                                            WHERE `setting_name`='launchDate'
                                                  AND `setting_val`  <= '$date')
                            AND `proudct_detail`.`product_update` = '1'
                     ORDER BY `proudct_detail`.`prodet_id` ASC";
$data   = $dbF->getRows($sql);
foreach($data as $val){
    //$link = WEB_URL."/detail?pId=".$val['prodet_id'];
    $link         =   WEB_URL."/".$db->productDetail."$val[slug]"; // product slug

    $date = date("Y-m-d",strtotime($val['prodet_timeStamp']));
    $links[]    =   array(
        "loc"       => $link,
        "lastmod"   => $date,
        "changefreq"=> "monthly",
        "priority"  => "0.7",
        "extLink"  => "&"
    );
}


//get Deal Product categories if have
if($functions->developer_setting('dealProduct') == '1') {
    $links[] = "Deals Categories";
    category("productDeals");

    //get deal products
    $links[] = "Deals Products";
    $sql    = "SELECT id,slug,dateTime FROM  product_deal WHERE publish = '1'";
    $data   = $dbF->getRows($sql);
    foreach($data as $val){
        //$link = WEB_URL."/productDeals?deal=".$val['id'];
        $link         =   WEB_URL."/".$db->dealProduct."$val[slug]"; // product slug
        $date = date("Y-m-d",strtotime($val['dateTime']));
        $links[]    =   array(
            "loc"       => $link,
            "lastmod"   => $date,
            "changefreq"=> "monthly",
            "priority"  => "0.6",
            "extLink"  => "&"
        );
    }
}


//Functions
//Categories
function category($page = "products")
{
    global $links;
    global $dbF;
    global $db;

    $sql = "SELECT * FROM `tree_data` WHERE id != '1'";
    $data = $dbF->getRows($sql);
    $link = WEB_URL . "/$page";
    $links[] = array(
        "loc" => $link,
        "extLink"  => "?"
    );

    if($page=='products') {
        $link = WEB_URL . "/".$db->pCategory;
    }else {
        $link = WEB_URL . "/".$db->dealCategory;
    }

    foreach ($data as $val) {
        //$linkT = $link . "?cat=$val[nm]&catId=$val[id]";
        $linkT  =  $link."$val[id]-$val[nm]"; // pCategory slug
        $slug = $val['nm'];
        $links[] = array(
            "loc" => $linkT,
            "priority"  => "0.5",
            "extLink"  => "&"
        );
    }
}


/**
 *
 *  Final Print XML
 *
 */


$newLine = "";
$tab  = "";
$msg = "<!-- use ?view to view Proper data --> ";
$msg = "";
if(isset($_GET['view']) || isset($_GET['uncompress'])){
    $newLine = "\n";
    $tab = "\t";
    $msg = "";
}

echo $msg.'<?xml version="1.0" encoding="UTF-8"?> <!-- use ?view to view Proper data -->'.$newLine.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';


foreach($links as $val){


    if(!is_array($val)){
        if(empty($msg)) {
            echo $newLine . $newLine . "<!-- $val -->";
        }
        continue;
    }
    echo $newLine."<url>";
    foreach($val as $key=>$property){
        if($key=='extLink') continue;
        echo $newLine.$tab."<$key>$property</$key>";
    }
    echo $newLine."</url>";
}

echo $newLine."</urlset>";