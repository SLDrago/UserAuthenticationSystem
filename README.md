# User Authentication System

This is a secure user authentication system built with PHP and MySQL. It provides functionality for user registration, login, and logout with enhanced security features and improved user experience.

## Features

- User registration with input validation
- User login with "Remember Me" functionality
- Welcome page for logged-in users
- User logout
- CSRF protection
- Input sanitization
- Rate limiting for login attempts
- Secure session management
- Error and success message display

## Security Measures

- Password hashing using `password_hash()`
- CSRF token validation for forms
- Input sanitization to prevent XSS attacks
- Rate limiting to prevent brute-force attacks
- Secure session handling
- Prepared statements to prevent SQL injection

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web server (e.g., Apache, Nginx) with SSL/TLS support

## Setup

1. Clone this repository or download the files to your web server's document root.

2. Create a MySQL database named `authentication`.

3. Import the database schema:
   ```sql
   CREATE TABLE user_new (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       fname VARCHAR(50) NOT NULL,
       lname VARCHAR(50) NOT NULL
   );

   CREATE TABLE login_attempts (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) NOT NULL,
       attempt_time DATETIME NOT NULL
   );

   CREATE TABLE auth_tokens (
       id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT NOT NULL,
       token VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   CREATE INDEX idx_username_attempt_time ON login_attempts (username, attempt_time);
   CREATE INDEX idx_user_id ON auth_tokens (user_id);
   ```

4. Update the database credentials and other settings in `config.php`:
   ```php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'your_username');
   define('DB_PASSWORD', 'your_password');
   define('DB_NAME', 'authentication');

   define('CSRF_TOKEN_SECRET', 'your_csrf_secret_key');
   define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour

   define('RATE_LIMIT_PER_MINUTE', 5);
   ```

5. Ensure all PHP files are in the same directory on your web server.

6. Configure your web server to use HTTPS. Obtain and install an SSL/TLS certificate if you haven't already.

## Usage

1. Navigate to `register.php` in your web browser to create a new user account.
2. After successful registration, you'll be redirected to `login.php` where you can log in.
3. Upon successful login, you'll be taken to `welcome.php`.
4. Use the logout link on the welcome page to log out.

## File Structure

- `config.php`: Configuration settings
- `dbconnector.php`: Database connection setup
- `functions.php`: Utility functions for security and error handling
- `register.php`: User registration form
- `registerProcess.php`: Processes user registration
- `login.php`: User login form
- `loginProcess.php`: Processes user login
- `welcome.php`: Welcome page for logged-in users
- `logout.php`: Handles user logout
- `index.php`: Handles initial landing
- `css/styles.css`: CSS styles for all pages

## Error Handling

The system displays error and success messages directly on the registration and login pages. This provides immediate feedback to users without redirecting to separate pages.

## Security Notes

While this system implements several security measures, always keep your server and PHP installation up to date. Regularly review and update your security practices. Consider implementing additional security measures such as:

- Two-factor authentication
- Account lockout after multiple failed attempts
- Regular security audits
- Logging of important events

## License

[MIT License](LICENSE)

## Contributing

Feel free to fork this project and submit pull requests with improvements or bug fixes. Please ensure that any contributions maintain or enhance the existing security measures.
