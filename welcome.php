<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($fname . " " . $lname); ?>!</h2>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>

</html>