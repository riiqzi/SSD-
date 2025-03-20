<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures the page is responsive across various devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    
    <!-- Display any error messages stored in the session -->
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Clear error after displaying
            ?>
        </p>
    <?php endif; ?>

    <!-- Form to request a password reset link -->
    <form action="generate_reset_token.php" method="POST">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Request Password Reset</button>
    </form>

    <!-- Link back to the login page -->
    <a href="../login">Back to Login</a>
</body>
</html>




