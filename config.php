<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'authentication');

// CSRF token settings
define('CSRF_TOKEN_SECRET', 'your_csrf_secret_key');
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour

// Rate limiting settings
define('RATE_LIMIT_PER_MINUTE', 5);

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
session_name('SecureSessionID');

// Error reporting
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    $message = "Error [$errno] $errstr on line $errline in file $errfile";
    error_log($message);

    if (ini_get('display_errors')) {
        echo "<p>An error occurred. Please try again later.</p>";
    }
}
set_error_handler("customErrorHandler");
