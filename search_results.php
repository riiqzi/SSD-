<?php
// Start session
session_start();
// Load database connection config
require '../db/config.php';

// Verify that a search query exists and is not empty
if (!isset($_GET['query']) || empty($_GET['query'])) {
    // If there's no query, redirect back to the search page
    header("Location: index.php");
    exit;
}

// Sanitize the search term by trimming whitespace
$searchTerm = trim($_GET['query']);

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT id, title, content 
                        FROM posts 
                        WHERE title LIKE ? 
                           OR content LIKE ?");
$likeTerm = "%{$searchTerm}%";

// Bind parameters
$stmt->bind_param("ss", $likeTerm, $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Escape user content to prevent XSS
        $safeTitle   = htmlspecialchars($row['title']);
        $safeContent = htmlspecialchars($row['content']);

        echo "<h2>{$safeTitle}</h2>";
        echo "<p>{$safeContent}</p>";
    }
} else {
    echo "No results found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures the page is responsive on various devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <!-- Optional: Load Bootstrap for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Include the nav bar -->
    <?php include '../components/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Display the sanitized search term to the user -->
        <h2>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>

        <?php if ($result->num_rows > 0): ?>
            <!-- If there are matching posts, list them -->
            <ul class="list-group">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <!-- Link to the individual post -->
                        <a href="../post.php?id=<?php echo $row['id']; ?>">
                            <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                        </a>
                        <!-- Provide a short preview of the post content -->
                        <p><?php echo substr(htmlspecialchars($row['content']), 0, 100) . '...'; ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <!-- Message if there are no matching posts -->
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>



