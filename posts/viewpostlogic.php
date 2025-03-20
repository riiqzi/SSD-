<?php
// Start session
session_start();
// Load database connection config
require '../db/config.php';

// Prepare a query to retrieve all posts, ordered by the most recent posting time
// The single quotes around 'posts' in the query may cause an issue in some SQL environments.

$stmt = $conn->prepare("SELECT * FROM 'posts' ORDER BY timeposted DESC");

// Fetch all posts using PDO::FETCH_ASSOC, storing them in an array
$blogPosts = $stmt->fetchALL(PDO::FETCH_ASSOC);

// Check if we have any returned posts
if (count($blogPosts) > 0) {
    // Loop through each post
    foreach ($blogPosts as $post) {
        // Display the user's ID as a heading, with HTML-escaping for security
        echo "<h1>" . htmlspecialchars($post['usrid']) . "</h1>";
    }
}

// Prepare another statement to retrieve a username based on user ID from the 'users' table
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");

// Bind the parameter ($usrid) to the SQL query, to prevent SQL injection
$stmt->bind_param("s", $usrid);

// Execute
$stmt->execute();

// Store the result
$stmt->store_result();

// Bind the username column from the results to $usrname
$stmt->bind_result($usrname);

// Fetch the username data from the results
$stmt->fetch();

// Explicitly close the statement
$stmt->close();
?>
