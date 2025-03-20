<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Blog - Create Post</title>
    <!-- Importing bootstrap css framework for navigation bar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
</head>

<body>
    <!-- Navigation bar -->
    <nav>
        <?php include '../components/navbar.php'; ?>
    </nav>
    <!-- Page heading -->
    <div class="container mt-4">
        <h1>Create a Post</h1>
    </div>
    <!-- Post creation form -->
    <div class="container mt-4">
        <form action="createpostlogic.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="PostTitle" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="PostTitle" name="title" required>
            </div>
            <div class="mb-3">
                <label for="PostContent" class="form-label">Post Contents</label>
                <textarea class="form-control" id="PostContent" name="content" rows=5 required></textarea>
            </div>
            <input type="file" class="btn btn-secondary" name="image">
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>

</body>
</html>

<?php
// Display error messages based on session data
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Remove error after displaying it

    if ($error == 'file_uperror') {
        echo '<div class="alert alert-danger mt-3" role="alert">There was an error uploading your file :(</div>';
    } elseif ($error == 'file_type_invalid') {
        echo '<div class="alert alert-danger mt-3" role="alert">The file type uploaded was invalid :(</div>';
    } elseif ($error == 'something_went_wrong') {
        echo '<div class="alert alert-danger mt-3" role="alert">Something went wrong. Please try again later.</div>';
    }
}

?>