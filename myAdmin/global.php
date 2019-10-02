<?php
 /* NOTE
 Search PENDING inproject, pending work need to do after a short time.
 REMOVE1 search, its need to remove after its work complete... it is use for testing.
 */

 /* PENDING session active needed
 session_save_path(__DIR__."/../__sessions_overridepath");
session_name("_uth"); */

    if (session_status() == PHP_SESSION_NONE || session_id() == '') {
        session_set_cookie_params(3600*24*7,"/");
        session_start();
    }

/*****************************************/
global $adminUserForDb;
global $db,$dbF,$functions,$_e,$ActivePagePerm,$adminPermissions,$subMenu,$menu;
$adminUserForDb = true; // allow different user for admin db
require_once (__DIR__."/../_models/setting/global.setting.php"); // connection setting db
require_once (__DIR__."/../_models/functions/functions.as.php"); // execute query functions
require_once (__DIR__."/functions/check_license.php"); // License
global $db,$dbF;

require_once (__DIR__."/../_models/traits/session_security.php"); // check session set
//Move // Encrypting_ trait functions.Encode Fun call in login Fun
//Login Functions move...
require_once (__DIR__."/../_models/traits/ajax_functions.php"); // ..Use for ajax Funtions
require_once (__DIR__.'/../_models/traits/admin_permission.php');
require_once (__DIR__."/../_models/traits/common_functions.php");
require_once (__DIR__."/../_models/traits/common_functions2.php");
require_once (__DIR__."/../_models/functions/sm_functions.php"); //

require_once (__DIR__."/../_models/traits/form_view.php");
/*****************************************/
require_once (__DIR__."/../_models/functions/main_functions.php"); //clear only country list function

require_once (__DIR__. "/functions/admin_functions.php");
require_once (__DIR__ . "/classes/bootstrap.class.php");
require_once (__DIR__ . "/classes/menu.php");

$functions = new admin_functions(); // set $db or $dbF if not set
$functions->admin_panel_access(); // check allow access, if not redirect to error-404 page
//Admin default language, define here to stop again again call this function
$defaultAdminLanguage   = $functions->AdminDefaultLanguage(true);
$adminPanelLanguage     = $functions->AdminPanelLanguage(true);
//Admin default language,

$_e     =   array(); //define new variable for multiLanguage in admin

///////////////////////////////////////////////////////////////////////////////

//Admin Permissions
//return array of all permissions
$editPermissionMessage = '';
$adminPermissions = $functions->adminPermissions();

//call menu here for admin permissions
$menu='index';
$subMenu='';
$menuClassGlobal =new menu();
$menuClassGlobal->menu();

//echo "<br><br>";
$ActivePagePerm   = $functions->pagePermission();
//if not permission i set error on header file

//echo $adminPermissions['product']['-product?page=list'];
//var_dump($menuClassGlobal->AutoVisibleMenu);
//var_dump($adminPermissions);
//var_dump($_SESSION);
//$functions->menu_show =false;

?>