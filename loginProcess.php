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

        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            throw new Exception("Username and password are required");
        }

        if (!checkRateLimit($conn, $username)) {
            throw new Exception("Too many login attempts. Please try again later.");
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
                throw new Exception("Invalid credentials");
            }
        } else {
            throw new Exception("Invalid credentials");
        }
    } catch (Exception $e) {
        setErrorMessage($e->getMessage());
        error_log("Login error: " . $e->getMessage());
        header("Location: login.php");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
} else {
    header("Location: login.php");
    exit();
}

$conn->close();
