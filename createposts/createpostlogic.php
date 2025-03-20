<?php
// Start session
session_start();
// Load database connection config
require '../db/config.php';

$uploadDir = '../uploads/'; 
//Ensures the upload directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

//grabs userid from cookie
$usrid = $_COOKIE['user_id'];
$newFileName = null;

// Capture Input from Post From
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ptitle = $_POST['title'];
    $pcont = $_POST['content'];
    //handle file upload if an image is uploaded 
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $fileTmpPath = $file['tmp_name'];
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileType = mime_content_type($fileTmpPath);  // Only checks MIME type - doesn't check for malicious file contents. 
        
        // Restricting file uploads to only specific MIME types.
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($fileType, $allowedTypes)) {
            // Making a unique identifier for the uploaded file to stop it overwriting existing files.
            $newFileName = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $destPath = $uploadDir . $newFileName; 
    
            // The script doesnt restrict file extensions, so an attacker could rename a file and add a legitimate image extension.
            // while embedding malware/malicious content within the file.
            
            // Moving the uploaded file to the directory.
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                //echo "There was an error uploading the file.";
                $_SESSION['error'] = "file_uperror";
                header("Location: /createposts");
                exit;
            }
            } else {
            //echo "Sorry, this file type is invalid: " . htmlspecialchars($fileType);
            $_SESSION['error'] = "file_type_invalid"; // Specific error - attackers can find out specifically what file types are accepted. 
            header("Location: /createposts");
            exit;
        }
    }
        // Insert the new post into the database, using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO posts (title, content, usrid, picname) VALUES (?, ?, ?, ?)"); // Using prepared statements to prevent SQL injection
        $stmt->bind_param("ssss", $ptitle, $pcont, $usrid, $newFileName);
        
        // Execute the query and handle success or failure
        if ($stmt->execute()) {
            $_SESSION['success'] = "post_success";
            header("Location: /posts");
            exit;
        } else {
            // Check if the error is because of the UNIQUE constraint
            $_SESSION['error'] = "something_went_wrong";
            $stmt->close();
            header("Location: /createposts");
            exit;
        }
}