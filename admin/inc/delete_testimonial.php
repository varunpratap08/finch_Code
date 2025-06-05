<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the testimonial to get the image path
    $stmt = $pdo->prepare("SELECT author_image FROM testimonial WHERE id = ?");
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($testimonial) {
        // Delete the author's image from the server
        $imagePath = "../../" . $testimonial['author_image'];
        if (!empty($testimonial['author_image']) && file_exists($imagePath)) {
            unlink($imagePath); // Remove the image file
        }

        // Delete the testimonial from the database
        $deleteStmt = $pdo->prepare("DELETE FROM testimonial WHERE id = ?");
        $deleteStmt->execute([$id]);

        header("Location: ../testimonials.php?message=Testimonial deleted successfully!");
        exit();
    } else {
        header("Location: ../testimonials.php?error=Testimonial not found!");
        exit();
    }
} else {
    header("Location: ../testimonials.php?error=Invalid request!");
    exit();
}
?>
