<?php
require_once 'config.php';
session_start();
require_once 'functions.php';

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit();
}

// Generate a new CSRF token for the form
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h2>User Login</h2>
        <?php
        $error = getErrorMessage();
        $success = getSuccessMessage();
        if ($error) {
            echo "<div class='message error'>$error</div>";
        }
        if ($success) {
            echo "<div class='message success'>$success</div>";
        }
        ?>
        <form action="loginProcess.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="remember">
                <input type="checkbox" id="remember" name="remember">
                Remember Me
            </label>

            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>

</html>