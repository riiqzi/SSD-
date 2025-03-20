<?php
// Declare connection variables for the Sigma Security blog (will be refered to as Security Blog on user end)
$servername   = "localhost";
$username     = "root";
$password     = "";
$databasename = "sigma_security"; 

// Create the database connection
$conn = new mysqli($servername, $username, $password, $databasename);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

