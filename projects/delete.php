<?php
session_start();
include '../php/db.php'; // Ensure you have the correct path to your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the project ID is set and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $project_id = $_GET['id'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM project WHERE id = ?");
    $stmt->bind_param("i", $project_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Optionally, add a success message to the session
        $_SESSION['message'] = "Project deleted successfully.";
    } else {
        // Optionally, add an error message to the session
        $_SESSION['error'] = "Error deleting project: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid project ID.";
}

// Redirect back to the projects page
header("Location: index.php");
exit();
?>