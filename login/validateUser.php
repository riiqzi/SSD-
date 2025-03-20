<?php
// Start session
session_start();
// Load database connection config
require '../db/config.php';

// Capture input from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Remove extra spaces from input
    $password = $_POST['password'];

    // Check if the fields are left blank
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "missing_credentials"; // Error if fields are empty
        header("Location: /login");
        exit;
    }

    // Statement to fetch the user by email
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "database_error";
        header("Location: /login");
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists in the database
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        // Verify the entered password against the hashed password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;

            // Set a cookie to maintain user login state (Valid for 7 days)
            setcookie("user_id", $user_id, time() + 604800, "/", "", false, false);

            // Close resources before redirecting
            $stmt->close();
            $conn->close();
            
            $_SESSION['success'] = "logged_in";
            header("Location: /login"); // Redirect after successful login
            exit;
        } else {
            // Incorrect password
            $stmt->close();
            $conn->close();
            
            $_SESSION['error'] = "incorrect_credentials";
            header("Location: /login");
            exit;
        }
    } else {
        // User not found - invalid email
        $stmt->close();
        $conn->close();
        
        $_SESSION['error'] = "incorrect_credentials";
        header("Location: /login");
        exit;
    }
}
?>
