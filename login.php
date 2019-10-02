<?php 
include("global.php");

global $webClass;
global $productClass;
$msg = '';

$arg = '';
if(isset($_GET['set']) && !empty($_GET['set'])){
    $setLink = base64_decode($_GET['set']);
    $setExpl = explode('---', $setLink);
    $_POST['email'] = $setExpl[0];
    $_POST['pass']  = $setExpl[1];
    $_POST['signInUserToken'] = $functions->setFormTokenReturn('signInUser');
    $arg = '?page=Profile&update';
}

$loginReturn = $webClass->userLogin();

if($loginReturn===true){
    if(isset($_GET['ref'])){
        $reffer = $_GET['ref'];
        // $loc = htmlentities(base64_decode($reffer));
        $loc = 'profile.php'.$arg;
    }else{
        $loc = 'profile.php'.$arg;
    }

    //if user success login then refer on previous open page
    header("Location: $loc");
    exit;
}else if($loginReturn!=false){
    $msg = $loginReturn;
}

$login       =  $webClass->userLoginCheck();
if($login){
    //if user already login then go to profile
    header("Location: profile.php");
    exit();
}

include("header.php");

//var_dump($_SESSION);

@$reffer = $_SERVER['HTTP_REFERER'];
$reffer = str_replace(WEB_URL.'/','',$reffer);

if(!empty($reffer)){
    //getting reffer link and set in url, when login success page redirect on location
    if(isset($_GET['ref'])) {
        $reffer = htmlentities(base64_decode($_GET['ref']));
    }
    $reffer = base64_encode($reffer);

?>
    <script>
        $(document).ready(function(){
           history.pushState(null, "login", "?ref=<?php echo $reffer; ?>");
        });
    </script>
<?php } ?>

<style type="text/css">
.login-bg {
    background: url('<?= WEB_URL.'/images/default_banner.jpg' ?>') no-repeat;
    background-size: cover;
    background-position: center center;
}
</style>
<section class="login-bg page-banner">
    <div class="page-heading">
        <h2>Login</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-center">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Login</li>
            </ol>
        </nav>
    </div>
</section>

<section id="testimonial" class="section-container bg-gray">
    <div class="icontainer">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h2 class="sec-title uline">
                        My Account
                    </h2>
                </div>
            </div>
        </div>
        <!--Login Form Start-->
        <?php if($msg!=''): ?>
            <div class="col-sm-12 alert alert-danger">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <?php include_once(__DIR__."/signup_form.php");?>
        <!--Login Form End-->
        <!-- <div class="testimonial-slider clearfix">
            <div class="col-md-12">
                <div class="client-words text-center">
                    
                </div>
            </div>
        </div> -->
    </div>
</section>







    <!--Inner Container Starts-->
<!--     <div class="inner_details_container  container-fluid padding-0">
        <div class="inner_details_content standard ">
            <div class="home_links_heading h3 well well-sm"><h1 class="login_head"><?php $dbF->hardWords('My Account');?></h1></div>
            <div class="inner_content_page_div futura_bk_bt">


                <?php if($msg!=''): ?>
                    <div class="col-sm-12 alert alert-danger">
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>

                <?php include_once(__DIR__."/signup_form.php");?>

            </div>
        </div>
    </div> -->

<?php include("footer.php");  ?>