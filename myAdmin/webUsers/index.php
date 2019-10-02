<?php



require_once("../global.php");



@$page = $_GET['page'];



global $menu;

global $subMenu;

$menu="webUserM"; // ul menu active



switch($page):

    case ("view"):

        $subMenu='webUser';

        $content = include "webUsers.php";

        break;

    case ("edit"):

        $subMenu='webUser';

        $content = include "webUsersEdit.php";

        break;



    //Admin User

    case ("AdminUsers"):

        $subMenu='AdminUsers';

        $content = include "adminUsers.php";

        break;

    case ("adminEdit"):

        $subMenu='AdminUsers';

        $content = include "adminUsersEdit.php";

    break;



    case ("AdminGrp"):

        $subMenu='AdminGrp';

        $content = include "adminGrp.php";

        break;

    case ("groupEdit"):

        $subMenu='AdminGrp';

        $content = include "adminGrpEdit.php";

        break;



    //reviews

    case ("reviews"):

        $subMenu='reviews';

        $content = include "reviews.php";

        break;



    case ("questions"):

        $subMenu='questions';

        $content = include "askQuestion.php";

        break;



    default:

        $content = _uc($_e['Page Not Found.']);

        break;

    endswitch;





include("../header.php");

echo '<h3 class="main_heading">'._uc($_e['WebUsers Management']).'</h3>';

echo $content;



include("../footer.php");



?>