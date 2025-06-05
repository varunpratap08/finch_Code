<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the banner to get the video path
    $stmt = $pdo->prepare("SELECT video FROM banner WHERE id = ?");
    $stmt->execute([$id]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($banner) {
        // Delete the video from the server
        $videoPath = "../../" . $banner['video'];
        if (!empty($banner['video']) && file_exists($videoPath)) {
            unlink($videoPath); // Remove the video file
        }

        // Delete the banner from the database
        $deleteStmt = $pdo->prepare("DELETE FROM banner WHERE id = ?");
        $deleteStmt->execute([$id]);

        header("Location: ../banner.php?message=Banner deleted successfully!");
        exit();
    } else {
        header("Location: ../banner.php?error=Banner not found!");
        exit();
    }
} else {
    header("Location: ../banner.php?error=Invalid request!");
    exit();
}
?>