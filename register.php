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
    <title>User Registration</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h2>User Registration</h2>
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
        <form action="registerProcess.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>