<?php
require '../../inc/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $blog_title = trim($_POST['blog_title']);
    $author = trim($_POST['author']);
    $content = trim($_POST['content']);
    $image = $_FILES['blog_image'];

    // Validate inputs
    if (!empty($blog_title) && !empty($author) && !empty($content) && !empty($image['name'])) {
        $uploadDir = "../../uploads/blogs/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . '_' . basename($image["name"]);
        $targetFile = $uploadDir . $imageName;
        $dbImagePath = "uploads/blogs/" . $imageName;

        // Check and move the uploaded image
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO blogs (blog_title, author, content, blog_image) 
                    VALUES (:blog_title, :author, :content, :blog_image)
                ");
                $stmt->execute([
                    ':blog_title' => $blog_title,
                    ':author' => $author,
                    ':content' => $content,
                    ':blog_image' => $dbImagePath
                ]);

                header("Location: ../blog.php?message=Blog added successfully!");
                exit();
            } catch (PDOException $e) {
                header("Location: ../blog.php?error=Database error: " . urlencode($e->getMessage()));
                exit();
            }
        } else {
            header("Location: ../blog.php?error=Failed to upload image!");
            exit();
        }
    } else {
        header("Location: ../blog.php?error=All fields are required!");
        exit();
    }
}
?>
