<?php
// Start session
session_start();
// Load database connection config
require '../db/config.php';

// Process the form if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the token and the new password from the submitted form
    $token = $_POST["token"];
    $new_password = $_POST["password"];

    // Verify the token exists in the database and has not expired
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // If no rows are returned, the token is invalid or has expired
    if ($result->num_rows == 0) {
        // There is no rate limiting here so an attacker could theoretically attempt many tokens
        die("Invalid or expired token.");
    }

    // Extracts the user’s ID associated with the token
    $user = $result->fetch_assoc();
    $user_id = $user["id"];

    // Hash the new password before storing (BCRYPT is used for secure hashing)
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Updates the password in the database, then clear the reset token and expiry
    $stmt = $conn->prepare(
        "UPDATE users 
         SET password_hash = ?, reset_token = NULL, reset_expiry = NULL 
         WHERE id = ?"
    );
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();
    $stmt->close();

    // Informs the user that the password update was successful and provide a login link
    echo "<p>Password updated successfully! 
            <a href='../login'>Login Here</a>
          </p>";
}




