<?php
    /*
        * Cancel Order page
    */

    // if (session_id() == "")
    //     session_start();

    include('../global.php');
    include('../header.php');
	
	$arr = $_SESSION["error"];
	
?>

    </div><!-- for resolving div not closing -->



    <div class="main-content">  <!-- closes in footer -->

        <div class='cart3' >
            <div class="">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="alert alert-danger" role="alert">                    
                        <p class="text-center"><strong>The payment could not be completed.</strong></p>
                    </div>

                    <br />
                    <strong>Reason: </strong> <?php echo $arr["json"]["name"]; ?> <br />
                    <br />
                    <strong>Message: </strong> <?php echo $arr["json"]["message"]; ?> <br />
                    
                    <br />
                    <br />

                    Return to <a href="index.php">home page</a>.
                </div>
                <div class="col-md-4"></div>
                <div style="clear:both"></div>
            </div>
        <!--content_cart end-->
        </div>

<style>
.cart3 {
    padding: 10px 0px;
}    
</style>

<?php
    include('../footer.php');
?>