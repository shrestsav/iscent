<?php require("global.php");
unset($_SESSION);
session_unset();
session_destroy();
@setcookie(session_name(), '', time()-55000, '/');
if(isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-55000, '/');
}

// Destroy all cookies.
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
    $_SERVER['HTTP_COOKIE'] = '';
}

header("location:index.php");
?>