<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Blog - Login</title>
    <!-- Importing bootstrap css framework for navigation bar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
</head>
<body>
    <nav>
        <?php include '../components/navbar.php'; ?> <!-- Include the navigation bar -->
    </nav>

    <div class="container mt-4">
        <h1>Login</h1>
    </div>

    <div class="container mt-4">
    <form action="validateUser.php" method="POST">
            <div class="mb-3">
                <label for="LoginEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="LoginEmail" name="email" required>
            </div>
            <div class="mb-3">
                <label for="LoginPassword" class="form-label">Enter your password</label>
                <input type="password" class="form-control" id="LoginPassword" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="../register" class="btn btn-secondary">Don't have an account?</a>
            <a href="../reset/reset_password.php" class="btn btn-secondary">Forgot Password?</a>
        </form>
    </div>
</body>
</html>

<?php
// This section is basically just loading messages about the login
// Display error messages
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Clear error after displaying

    if ($error == 'missing_credentials') {
        echo '<div class="alert alert-danger mt-3" role="alert">One or more fields are missing.</div>';
    } elseif ($error == 'incorrect_credentials') {
        echo '<div class="alert alert-danger mt-3" role="alert">Email or password is incorrect.</div>';
    }
}

// Display success message if logged in (mainly for debug, should redirect in final version)
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']); // Clear success message after displaying

    if ($successMessage == 'logged_in') {
        echo '<div class="alert alert-success mt-3" role="alert">Sucessfully logged in.</div>';
    }
}
?>
