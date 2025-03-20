<?php
// Start session
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if the user is authenticated via the `user_id` cookie
    if (!isset($_COOKIE['user_id'])) {
        // If no authentication cookie is found, redirect to posts with an error message
        $_SESSION['error'] = "nocookie";
        header("Location: /posts");
        exit;
    } else {
        // If authenticated, redirect to the create post page
        header("Location: /createposts");
        exit;
    }
}
?>
