<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures the page is responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Blog - Register</title>
    <!-- Importing Bootstrap for layout and styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
</head>
<body>
    <!-- Include the navigation bar -->
    <nav>
        <?php include '../components/navbar.php'; ?>
    </nav>

    <!-- Account creation -->
    <div class="container mt-4">
        <h1>Make an account</h1>
    </div>

    <!-- Registration form for email, username, and password input -->
    <div class="container mt-4">
        <form action="signup.php" method="POST">
            <div class="mb-3">
                <label for="CreateEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="CreateEmail" name="email" required>
            </div>
            <div class="mb-3">
                <label for="CreateUsername" class="form-label">Username</label>
                <input type="text" class="form-control" id="CreateUsername" name="username" required>
            </div>
            <div class="mb-3">
                <label for="CreatePassword" class="form-label">Create a password</label>
                <input type="password" class="form-control" id="CreatePassword" name="password" required>
            </div>
            <div class="mb-3">
                <label for="ConfirmPassword" class="form-label">Confirm your password</label>
                <input type="password" class="form-control" id="ConfirmPassword" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
            <a href="../login" class="btn btn-secondary">Already have an account?</a>
        </form>
    </div>
</body>
</html>

<?php
// Check for errors stored in the session and display them
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Remove the error message after displaying
    
    // Display a specific alert based on the error type
    if ($error == 'missing_field') {
        echo '<div class="alert alert-danger mt-3" role="alert">One or more fields are missing.</div>';
    } elseif ($error == 'username_taken') {
        echo '<div class="alert alert-danger mt-3" role="alert">The username is already taken.</div>';
    } elseif ($error == 'password_mismatch') {
        echo '<div class="alert alert-danger mt-3" role="alert">Passwords do not match.</div>';
    } elseif ($error == 'password_strength') {
        echo '<div class="alert alert-danger mt-3" role="alert">The password does not meet the requirements of: at least 8 characters, a mixture of uppercase and lowercase letters, at least one number, and at least one special character.</div>';
    } elseif ($error == 'email_taken') {
        echo '<div class="alert alert-danger mt-3" role="alert">The email address is already in use.</div>';
    } elseif ($error == 'something_went_wrong') {
        echo '<div class="alert alert-danger mt-3" role="alert">Something went wrong. Please try again later.</div>';
    }
}

// Show success message if the account has been created
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']); // Remove message after displaying

    if ($successMessage == 'account_created') {
        // Updated message to reference the Security Blog
        echo '<div class="alert alert-success mt-3" role="alert">Your account has been created. Please log in. Welcome to the Security Blog.</div>';
    }
}
?>

