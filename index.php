<?php 
session_start();
include './config/database.php';

// Check if the session variable 'username' exists
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = ''; // Fallback if not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include 'linkcss.php' ?>
</head>
<body>

<!-- including navbar start -->
 <?php include 'navbar.php' ?>
<!-- including navbar end -->

    <!-- hello user start -->
    <?php
    if (!empty($username)) {
        echo "<h1 id='username' class='text-center' >Hello, {$username}</h1>"; // Display the username if set
    } else {
        echo "<h1>Welcome, Guest!</h1>"; // Fallback for guests
    }
    ?>
    <!-- hello user end -->



    <!-- javascript scripting start -->
    <?php include 'linkscript.php' ?>
    <script src="script.js"></script>
</body>
</html>
