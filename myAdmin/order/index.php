<?php



require_once("../global.php");



@$page = $_GET['page'];



global $menu;

global $subMenu;

$menu="orderManagement"; // ul menu active



switch($page):

    case ("newOrder"):

        $subMenu='newOrder';

        $content = include "newOrder.php";

        break;

    case ("edit"):

        $subMenu='newOrder';

        $content = include "invoice.php";

        break;

    case ("calendar"):

        $subMenu='calendar';

        $content = include "schedule_calendar.php";

        break;

    case ("technicalForm"):

        $subMenu='technicalForm';

        $content = include "techincal_form.php";

        break;



    default:

        $content = "Page Not Found.";

        break;

    endswitch;





include("../header.php");





echo '<h3 class="main_heading">'. _uc($_e['Order / Invoice Management']) .'</h3>';

echo $content;



include("../footer.php");



?>