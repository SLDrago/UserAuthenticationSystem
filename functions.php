<?php
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token)
{
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time'])) {
        return false;
    }
    if (hash_equals($_SESSION['csrf_token'], $token)) {
        if (time() - $_SESSION['csrf_token_time'] < CSRF_TOKEN_LIFETIME) {
            return true;
        }
    }
    return false;
}

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function checkRateLimit($conn, $username)
{
    $sql = "SELECT COUNT(*) as attempt_count FROM login_attempts WHERE username = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['attempt_count'] >= RATE_LIMIT_PER_MINUTE) {
        return false;
    }

    $sql = "INSERT INTO login_attempts (username, attempt_time) VALUES (?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    return true;
}

function setErrorMessage($message)
{
    $_SESSION['error_message'] = $message;
}

function getErrorMessage()
{
    $message = $_SESSION['error_message'] ?? '';
    unset($_SESSION['error_message']);
    return $message;
}

function setSuccessMessage($message)
{
    $_SESSION['success_message'] = $message;
}

function getSuccessMessage()
{
    $message = $_SESSION['success_message'] ?? '';
    unset($_SESSION['success_message']);
    return $message;
}
