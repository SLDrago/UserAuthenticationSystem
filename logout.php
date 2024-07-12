<?php
session_start();

session_destroy();

if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
    setcookie('fname', '', time() - 3600, '/');
    setcookie('lname', '', time() - 3600, '/');
}

header("Location: login.php");
exit();
