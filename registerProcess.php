<?php
session_start();
require_once 'dbconnector.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!validateCSRFToken($_POST['csrf_token'])) {
        setErrorMessage("CSRF token validation failed");
        header("Location: register.php");
        exit();
    }

    $fname = sanitizeInput($_POST['fname']);
    $lname = sanitizeInput($_POST['lname']);
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (empty($fname) || empty($lname) || empty($username) || empty($password)) {
        setErrorMessage("All fields are required");
        header("Location: register.php");
        exit();
    }

    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        setErrorMessage("Username should only contain letters and numbers");
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 8) {
        setErrorMessage("Password should be at least 8 characters long");
        header("Location: register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user_new (username, password, fname, lname) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $hashed_password, $fname, $lname);

    if ($stmt->execute()) {
        setSuccessMessage("Registration successful! You can now log in.");
        header("Location: login.php");
        exit();
    } else {
        setErrorMessage("Error: " . $stmt->error);
        header("Location: register.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: register.php");
    exit();
}

$conn->close();
