<?php include("global.php");
global $webClass;
global $productClass;

include("header.php");

?>
<style>
    .inner_details_container{
        padding: 30px 0;
        min-width: 450px;
    }
    .inner_details_content{
        background: #fff;
        padding: 0 3px;
    }
    .border{
        border-bottom: 1px solid #ddd;
    }
    .p_box_white {
        width: 480px;
    }
    @media (max-width: 768px){
        .inner_details_content {
            min-width: 100%;
        }
    }
</style>
    <!--Inner Container Starts-->
    <div class="inner_details_container  container-fluid padding-0">
        <div class="standard padding-0">
            <div class="home_links_heading h3 well well-sm"><?php $dbF->hardWords('WishList');?></div>
            <div class="inner_content_page_div container-fluid padding-0">
                    <div class="pro_ducts_in_divs iHaveProducts" style="text-align: center">

                <?php
                $userId     =   $productClass->webUserId();
                $TempUserId =   $productClass->webTempUserId();

                $sql = "SELECT distinct (pId) FROM cartwishlist WHERE userId = ? AND tempUser = ?";
                $check = $dbF->getRows($sql,array($userId,$TempUserId));
                foreach($check as $val){
                    $id = $val['pId'];
                    echo $productClass->pBox($id,true);
                }
                ?>
            </div>

                <br>
                <br>
        </div>
    </div>
    </div>

<?php include("footer.php"); ?>