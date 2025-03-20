<?php
// Start a session 
session_start();

// Include the database config
require '../db/config.php';

// Process the form if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and trim the email to remove extra whitespace
    $email = trim($_POST["email"]);

    // Check if the provided email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If no record is found for this email, redirect back with an error
    if ($stmt->num_rows == 0) {
        $_SESSION['error'] = "Email not found, please try again.";
        header("Location: reset_password.php");
        exit;
    }

    /**
     * Major Security Flaw: Weak & Predictable Token
     *  - The reset token is generated using md5(email + time), which is predictable.
     *  - The token is only 8 characters long, making it susceptible to brute force attempts.
     *  - In production, it’s recommended to use a more secure method (e.g., bin2hex(random_bytes(32))).
     */
    $token = substr(md5($email . time()), 0, 8);

    // Set the token expiry time to 30 minutes from now
    $expiry = date("Y-m-d H:i:s", time() + 1800);

    // Update the users table with the generated token and its expiry time
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expiry, $email);
    $stmt->execute();
    $stmt->close();

    // For demo purposes, the reset link is displayed directly rather than emailed
    echo "<p>Use the following reset link:</p>";
    echo "<a href='new_password.php?token=$token'>Reset Password</a>";

    /**
     *  - This approach uses md5(), a predictable hashing function, to generate a token.
     *  - Attackers could potentially guess or brute force the token.
     *  - A more secure approach would be to use something like bin2hex(random_bytes(32)).
     *  - Tokens should typically be emailed to the user, not displayed outright.
     */
}
?>







