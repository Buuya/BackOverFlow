<?php
session_start(); // Start the session

// Destroy all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the homepage or login page after logging out
header("Location: login.php");  // Or login.php if you want the user to log in again
exit();
?>
