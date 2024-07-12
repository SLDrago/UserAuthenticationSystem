<?php
require_once 'config.php';
session_start();
require_once 'dbconnector.php';

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    setcookie('remember_token', '', time() - 3600, '/');

    $sql = "DELETE FROM auth_tokens WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: login.php");
exit();
