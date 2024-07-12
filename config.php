<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username'); // Your database username
define('DB_PASSWORD', 'your_username'); // Your database password
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
