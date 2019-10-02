<?php
    include(__DIR__."/globalVar.php");
    //Encrypt After here
?>
<?php
    //these are variables that declare in global.php write here for license
    global $dbF;
    global $db;
    global $_e;
    global $functions;
    global $menuClassGlobal;
    global $adminPermissions;
    global $defaultAdminLanguage;
    global $adminPanelLanguage;
    global $ActivePagePerm;
?><!DOCTYPE html>
<html >
    <head>
        <?php

        $_w =array();
        //header.php
        $_w['Go To Home'] = "";
        $_w['SignOut'] = "";
        $_w['Account Setting'] = '';
        $_w['SignIn'] ="";
        $_w["Sorry you don't have permission to access this page"] ="";

        //All Inner folder Index Page Handel Here
        //Index also in its class, but in some management there is several class. so its need to place outside of its class
        //In future any need to change index heading, change here.
        $_w['Brands Management'] = '';
        $_w['Banners Management'] = '' ;
        $_w['Blog Management'] = '' ;
        $_w['Email Management'] = '';
        $_w['Gallery Management'] = '' ;
        $_w['Logs Management'] = '' ;
        $_w['Manage Website Menu'] = '' ;
        $_w['News Management'] = '' ;
        $_w['Order / Invoice Management'] = '';
        $_w['Pages Management'] = '' ;
        $_w['Product Management'] = '' ;
        $_w['SEO Management'] = '' ;
        $_w['Gift Card Management'] = '' ;
        $_w['Setting'] = '' ;
        $_w['Shipping Management'] = '' ;
        $_w['Stock Management'] = '' ;
        $_w['WebUsers Management'] = '' ;

        $_e = $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Header');
        ?>

        <title><?php echo _U($_GET['page']) ?> - IBMS v<?php echo $functions->IBMSVersion; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" href="<?php echo WEB_ADMIN_URL ?>/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo WEB_ADMIN_URL ?>/favicon.ico" type="image/x-icon" />

        <!--    <link rel="stylesheet" type="text/css" href="assets/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.css"/> -->
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/jquery-ui/css/jquery-ui-1.11.0.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap/css/bootstrap-theme.css"/>

        <!--Multiselect css-->
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css"/>

        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/font-awesome/css/font-awesome.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/menu/menu.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/fullcalendar.min.css"/>

        <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/jquery.1.11.1.js"></script>

        <!-- twitter bootstrap ajax typeahead plugin -->
        <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/assets/biggora-bootstrap-ajax-typeahead/js/bootstrap-typeahead.js"/></script>
        <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/moment.min.js"/></script>
        <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/fullcalendar.min.js"/></script>


        <!-- PENDING tags input found in sisyphus.. no need to use bootstrap tagsinput
            <script type="text/javascript" src="assets/bootstrap-tagsinput/bootstrap-tagsinput-angular.js"></script>
            <script type="text/javascript" src="assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
            <link rel="stylesheet" type="text/css" href="assets/bootstrap-tagsinput/bootstrap-tagsinput.css">
            -->


        <!-- main common functions -->
        <script type="text/javascript" src="<?php echo WEB_ADMIN_URL; ?>/js/main.php"></script>
        <!-- custome css -->
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/commonuse.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/css/printCss.css"/>

        <!--Alertify css-->
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ADMIN_URL; ?>/assets/alertify/themes/alertify.core.css"/>

        <!-- <link rel="stylesheet" type="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" /> -->

        <?php
        // Checking Email Cron complete
        //Using requiredCron file for fast process instense of using db query
        ob_start();
        include_once('requiredCron.txt');
        $emailComplete =  ob_get_clean();
        if($emailComplete=='okay'){
            echo "<script>
        location.replace('-email?page=newsLetter&completeEmails');
     </script>";
        }
?>

    </head>
<body >

<?php
if ($functions->menu_show === true) { ?>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="mainTopMenu">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand visible-xs" href="<?php echo WEB_URL; ?>"><i class="fa fa-home"></i></a>
            </div>

            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo WEB_URL; ?>"> <i class="fa fa-home" style="font-size: 18px"></i> <?php echo $_e['Go To Home']; ?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <?php
                        if ($functions->log_check()["status"] == "ok") {
                            echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='glyphicon glyphicon-log-out'></i> Hi, $_SESSION[_name]</a>";
                            echo '
                             <ul class="dropdown-menu">
                                <li><a href="-setting?page=account">'.$_e['Account Setting'].'</a></li>
                                <li><a href="logout"> '.$_e['SignOut'].'</a></li>
                            </ul>';

                        } else {
                            echo '<a href="do-login.secure"><i class="glyphicon glyphicon-log-in"></i> '.$_e['SignIn'].'</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
<?php } ?>


<div id="main_Div" class="container-fluid col-md-12 no-margin-padding">
    <div class="IBMS_Main_Menu col-md-2 col-sm-3 col-xs-3 no-margin-padding " >
        <?php
        echo $menuClassGlobal->menu();
        ?>
    </div><!-- .IBMS_Main_Menu -->

    <div id="container_div" class="col-md-10 col-sm-9 col-xs-9">
        <div class="content_div">

<?php

//check inner pages permissions /edit pages
//Function call after menu load or actual page load,, it check active menu status
$functions->pageInnerPermission($menuClassGlobal);

//Check Page Permissions for admin users
global $ActivePagePerm;
if($ActivePagePerm===false){
    echo "<h2>". $_e["Sorry you don't have permission to access this page"] ."</h2>";

    include_once(__DIR__.'/footer.php'); // for js files
    exit;
}

?>