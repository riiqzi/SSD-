<?php 
// Start a session
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures the page is responsive across various screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <!-- Load Bootstrap CSS for layout and styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Include the navbar -->
    <?php include "../components/navbar.php"; ?>

    <div class="container mt-4">
        <h2>Search Blog Posts</h2>

        <!-- Search form uses GET to send the query to search_results.php -->
        <form action="search_results.php" method="GET">
            <div class="input-group">
                <!-- Input field for user to type search keywords -->
                <input 
                    type="text" 
                    name="query" 
                    class="form-control" 
                    placeholder="Search..." 
                    required
                >
                
                <!-- Submit button but pressing Enter also triggers the form submission -->
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</body>
</html>



