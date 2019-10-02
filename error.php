<?php
$lifetime=(3600*24*30);
if (session_status() == PHP_SESSION_NONE || session_id() == '') {
    session_set_cookie_params(3600 * 24 * 7, "/");
    session_start();
}

if(isset($_GET['errorId']) && isset($_SESSION['error'])){
    $error = $_GET['errorId'];
    echo "<pre>";
        print_r(array_map('htmlentities', $_SESSION['error']));
        // (print_r($_SESSION['error'][$error]));
    echo "</pre>";
}
else {

    echo "Set 404 custome page from admin.";
    echo "<pre>";
        print_r(array_map('htmlentities', $_REQUEST));
        print_r(array_map('htmlentities', $_GET));
    echo "</pre>";
}
?>