<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the slider to get the image path
    $stmt = $pdo->prepare("SELECT image FROM slider WHERE id = ?");
    $stmt->execute([$id]);
    $slider = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($slider) {
        // Delete the image from the server
        $imagePath = "../../" . $slider['image'];
        if (!empty($slider['image']) && file_exists($imagePath)) {
            unlink($imagePath); // Remove the image file
        }

        // Delete the slider from the database
        $deleteStmt = $pdo->prepare("DELETE FROM slider WHERE id = ?");
        $deleteStmt->execute([$id]);

        header("Location: ../slider.php?message=Slider deleted successfully!");
        exit();
    } else {
        header("Location: ../slider.php?error=Slider not found!");
        exit();
    }
} else {
    header("Location: ../slider.php?error=Invalid request!");
    exit();
}
?>
