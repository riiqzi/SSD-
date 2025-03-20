<?php
// Start session
session_start();
// Load database connection conf
require '../db/config.php';
// Process the form if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract user inputs and trim extra whitespaces
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure all required fields have values
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "missing_field";
        header("Location: /register");
        exit;
    }

    // Check if the username is already taken
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "username_taken";
        header("Location: /register");
        exit;
    }
    $stmt->close();

    // Confirm both password fields match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "password_mismatch";
        header("Location: /register");
        exit;
    }

    // Validate the password against defined complexity requirements
    $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
    if (!preg_match($password_regex, $password)) {
        $_SESSION['error'] = "password_strength";
        header("Location: /register");
        exit;
    }

    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Attempt to insert the new user record into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "account_created";
        header("Location: /register");
        exit;
    } else {
        // Check for any MySQL errors
        if ($stmt->errno == 1062) {
            $_SESSION['error'] = "email_taken";
        } else {
            $_SESSION['error'] = "something_went_wrong";
        }
        $stmt->close();
        header("Location: /register");
        exit;
    }
}
?>

