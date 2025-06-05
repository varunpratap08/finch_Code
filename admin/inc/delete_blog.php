<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the blog to get the image path
    $stmt = $pdo->prepare("SELECT blog_image FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($blog) {
        // Delete the image from the server if it exists
        $imagePath = "../../" . $blog['blog_image'];
        if (!empty($blog['blog_image']) && file_exists($imagePath)) {
            unlink($imagePath); // Remove the image file
        }

        // Delete the blog from the database
        $deleteStmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
        $deleteStmt->execute([$id]);

        header("Location: ../blog.php?message=Blog deleted successfully!");
        exit();
    } else {
        header("Location: ../blog.php?error=Blog not found!");
        exit();
    }
} else {
    header("Location: ../blog.php?error=Invalid request!");
    exit();
}
?>
