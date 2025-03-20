<?php
//Starts the Session
session_start();

// Load database configuration for connecting to the Security Blog database
require '../db/config.php';

// Directory for storing uploaded images
$uploadDir = '../uploads/'; 
// Create the directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures the page is responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Blog - Posts</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
</head>
<body>
    <!-- Navigation bar included  -->
    <nav>
        <?php include '../components/navbar.php'; ?>
    </nav>

    <!-- Page heading -->
    <div class="container mt-4">
        <h1>Security Blog Posts</h1>
    </div>

    <!-- Button to create a new post -->
    <div class="container mt-4">
        <form action="createpostbtnlogic.php" method="POST">
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
    <?php
    // Prepare statement to retrieve all existing posts, most recent first
    $stmt = $conn->prepare(
        "SELECT title, content, usrid, picname, timeposted 
         FROM posts 
         ORDER BY timeposted DESC"
    );
    $stmt->execute();

    // Bind columns to variables for fetching data
    $stmt->bind_result($title, $content, $usrid, $picname, $time);

    // Store retrieved data in an array for easier handling
    $blogPosts = [];
    while ($stmt->fetch()) {
        $blogPosts[] = [
            'title'   => $title,
            'content' => $content,
            'usrid'   => $usrid,
            'picname' => $picname,
            'time'    => $time
        ];
    }

    // Check if we have any posts to display
    if (count($blogPosts) > 0) {
        // Loop through each post and display its contents
        foreach ($blogPosts as $post) {
            // Retrieve the username of the post writer
            $stmt2 = $conn->prepare("SELECT username FROM users WHERE id = ?");
            // Binding parameter to avoid SQL injection
            $stmt2->bind_param("s", $post['usrid']);
            $stmt2->execute();
            $stmt2->store_result();
            $stmt2->bind_result($usrname);
            $stmt2->fetch();
            $stmt2->close();

            // Display the post information in a Bootstrap container
            echo '<div class="container mt-4">';
            echo "<h2>" . $post['title'] . " by " . $usrname . "</h2>";
            echo "<p>" . $post['content'] . "</p>";

            // If the post has an associated image, display it
            if ($post['picname'] !== null) {
                echo '<img src="../uploads/' . $post['picname'] . '" alt="' 
                     . $post['title'] 
                     . '" style="max-width: 100%; height: auto;">';
            }

            // Show the time the post was created
            echo "<p>" . $post['time'] . "</p>";
            echo "</div>";
        }
    }

    $stmt->close();

    ?>

</body>
</html>

<?php
// Check if there's an error message stored in the session
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Clear error after displaying

    if ($error === 'nocookie') {
        // Prompt user to log in before creating a post
        echo '<div class="alert alert-danger mt-3" role="alert">
                Please Login before creating a post.
              </div>';
    }
}

// Check if there's a success message stored in the session
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']); // Remove the success message after displaying it
    if ($successMessage === 'post_success') {
        // Inform user that their post was created
        echo '<div class="alert alert-success mt-3" role="alert">
                Your Post has been created!
              </div>';
    }
}
?>

