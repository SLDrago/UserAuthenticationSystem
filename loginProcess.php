<?php
session_start();

require_once 'dbconnector.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!validateCSRFToken($_POST['csrf_token'])) {
        setErrorMessage("CSRF token validation failed");
        header("Location: login.php");
        exit();
    }

    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        setErrorMessage("Username and password are required");
        header("Location: login.php");
        exit();
    }

    if (!checkRateLimit($conn, $username)) {
        setErrorMessage("Too many login attempts. Please try again later.");
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM user_new WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];

            session_regenerate_id(true);

            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(16));
                $hashed_token = password_hash($token, PASSWORD_DEFAULT);

                $sql = "INSERT INTO auth_tokens (user_id, token) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $row['id'], $hashed_token);
                $stmt->execute();

                setcookie('remember_token', $token, time() + (86400 * 30), "/", "", true, true);
            }

            header("Location: welcome.php");
            exit();
        } else {
            setErrorMessage("Invalid password");
            header("Location: login.php");
            exit();
        }
    } else {
        setErrorMessage("User not found");
        header("Location: login.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: login.php");
    exit();
}

$conn->close();
