<?php
session_start(); // Start the session

// Clear specific session variables
unset($_SESSION['user_id']); // Remove user ID from the session

// Optionally destroy the entire session
session_destroy();

// Redirect to login page or home page
header("Location: login.php");
exit();
?>