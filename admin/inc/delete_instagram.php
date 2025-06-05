<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the Instagram post to get the image path
    $stmt = $pdo->prepare("SELECT image_path FROM instagram WHERE id = ?");
    $stmt->execute([$id]);
    $instagram = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($instagram) {
        // Delete the image from the server
        $imagePath = "../../" . $instagram['image_path'];
        if (!empty($instagram['image_path']) && file_exists($imagePath)) {
            unlink($imagePath); // Remove the image file
        }

        // Delete the Instagram post from the database
        $deleteStmt = $pdo->prepare("DELETE FROM instagram WHERE id = ?");
        $deleteStmt->execute([$id]);

        header("Location: ../instagram.php?message=Instagram post deleted successfully!");
        exit();
    } else {
        header("Location: ../instagram.php?error=Instagram post not found!");
        exit();
    }
} else {
    header("Location: ../instagram.php?error=Invalid request!");
    exit();
}
?>