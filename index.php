<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    header("Location: welcome.php");
    exit();
}
