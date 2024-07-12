<?php
require_once 'config.php';
session_start();
require_once 'dbconnector.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
            throw new Exception("CSRF token validation failed");
        }

        $fname = sanitizeInput($_POST['fname']);
        $lname = sanitizeInput($_POST['lname']);
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];

        if (empty($fname) || empty($lname) || empty($username) || empty($password)) {
            throw new Exception("All fields are required");
        }

        if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
            throw new Exception("Username should only contain letters and numbers");
        }

        if (strlen($password) < 8) {
            throw new Exception("Password should be at least 8 characters long");
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
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        setErrorMessage($e->getMessage());
        error_log("Registration error: " . $e->getMessage());
        header("Location: register.php");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
} else {
    header("Location: register.php");
    exit();
}

$conn->close();
