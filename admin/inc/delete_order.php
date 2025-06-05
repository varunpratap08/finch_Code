<?php
require '../../inc/db.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the product to get the image path
    $stmt = $pdo->prepare("SELECT product_image FROM product WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Delete the image from the server
        $imagePath = "../../" . $product['product_image'];
        if (!empty($product['product_image']) && file_exists($imagePath)) {
            unlink($imagePath); // Remove the image file
        }

        // Delete the product from the database
        $deleteStmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
        $deleteStmt->execute([$id]);

        header("Location: ../products.php?message=Product deleted successfully!");
        exit();
    } else {
        header("Location: ../products.php?error=Product not found!");
        exit();
    }
} else {
    header("Location: ../products.php?error=Invalid request!");
    exit();
}
?>
