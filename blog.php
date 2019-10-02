<?php include("global.php");
global $webClass;

//var_dump($_GET);

$pg = empty($_GET['blogId']) ? "null" : $_GET['blogId'];
$pg = empty($_GET['blog'])   ? "null" : $_GET['blog'];


require_once(__DIR__ . '/_models/functions/webBlog_functions.php');
$blogC = new webBlog_functions();
$page = $blogC->getBlog("$pg");

//var_dump($page);

if ($seo['title'] == '' || $seo['reWriteTitle'] == '0') {
    $seo['title'] = $page['heading'];
}
if ($seo['description'] == '' || $seo['default'] == '1') {
    $seo['description'] = substr(trim(strip_tags($page['desc'])), 0, 250);
}

include("header.php");
?>
    <link href="<?php echo WEB_URL; ?>/css/blog.css" rel="stylesheet" type="text/css"/>
    <style>
        .blogPageFull ul {
            list-style: none;
        }

        .blogPageFull .newsContImage {
            height: auto;
            text-align: center;
        }

        .newsContDate {
            font-size: 14px;
            text-align: center;
        }

        .newsCont {
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }
    </style>
<?php

$bID = empty($_GET['blog']) ? "null" : $_GET['blog'];


if (!isset($_GET['blog']) || $bID == '')


{
    $functions->require_once_custom("webBlog_functions");
    $blogC = new webBlog_functions();
    $blogData = $blogC->latestBlog(500);
    ?>
    <div class="standard">
    <div class='mainContainerInnerPage padding_inner_content'>
        <div class="tmH2 h3"><?php echo $e['Blog']; ?></div>
        <div class='ContainerInnerPage'>
            <div class="blogPageFull">
                <ul>
                    <?php echo $blogData; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <!--Inner Container Starts-->
    <div class='mainContainerInnerPage'>
        <div class='standard'>

            <div class='ContainerInnerPage'>
                <div class='slider_three_slidess'>
                    <div class="slider_three_img"><img src="<?php echo $page['image']; ?>"/></div>

                    <div class="all_slider_text">
                        <div class="slider_three_cap"><?php echo $page['heading']; ?></div>
                        <div class="slider_three_dis">
                            <?php $desc1 = ($page['desc']);
                            echo $desc1; ?>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
    <!--Inner Container Ends-->
    <?php
}
?>
    </div>

<?php include("footer.php"); ?>