<?php
// Start session
session_start();

// Remove all session variables
session_unset();

// Destroy the current session to log the user out
session_destroy();

setcookie("user_id", $user_id, time() - 1, "/", "", false, true);

// Redirect user to the homepage after logout
header("Location: /");
exit();
?>
