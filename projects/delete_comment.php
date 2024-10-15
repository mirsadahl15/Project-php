<?php
session_start();
include("../php/db.php");

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: /projects-platform/login.php");
    exit();
}

// Check if the comment ID is set in the query string
if (isset($_GET['id'])) {
    $comment_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Prepare the statement to delete the comment
    $stmt = $conn->prepare("DELETE FROM project_comments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    
    if ($stmt->execute()) {
        // Redirect back to the project page (you might want to change this to the relevant page)
        header("Location: /projects-platform/projects/index.php");
    } else {
        // Handle errors
        echo "Error deleting comment: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    // Redirect back to the project page if no comment ID was provided
    header("Location: /projects-platform/projects/index.php");
}
?>