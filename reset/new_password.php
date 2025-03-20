<?php
// Start session
session_start();
// Load database connection config
require '../db/config.php';

// Verify that a reset token is provided and stop if not
if (!isset($_GET['token'])) {
    die("Invalid request."); 
}

// Retrieve the token from GET parameter
$token = $_GET['token'];

// Check if the token is valid and not expired
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// If no matching record is found, the token is invalid or expired
if ($result->num_rows == 0) {
    die("Invalid or expired token, please try again.");
}

// Extract the user’s ID
$user = $result->fetch_assoc();
$user_id = $user['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures responsiveness on various device sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
</head>
<body>
    <h2>Enter a New Password</h2>

    <!-- Form to submit update_password.php -->
    <form action="update_password.php" method="POST">
        <!-- Pass the token securely as a hidden input -->
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        
        <label for="password">New Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Update Password</button>
    </form>
   
</body>
</html>




