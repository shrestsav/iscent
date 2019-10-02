<?php
 $ruri = $_SERVER['REQUEST_URI'];

 $ruri_do = strpos($ruri, '/do-');

 $ruri_secure = strpos($ruri, '.secure');

if ($ruri_do >= 0 && $ruri_secure > ($ruri_do + 2)) {
} else {
    header("HTTP/1.0 404 Not Found");
    exit();
}
include("global.php");
$functions->menu_show = false;
@$do = empty($_GET['do']) ? 0 : $_GET['do'];
switch ($do):
    case "login":
        $echo =  include("_models/pages/login.page.php");
        break;
    case "register":
        $echo =  include("_models/pages/register.page.php");
        break;
    default:
        $echo =  "<h1>404 - Error</h1>";
        break;
endswitch;
?>
<?php
echo $echo;
//include(__DIR__."/".ADMIN_FOLDER."/footer.php");
$functions->adminFooter();


?>
